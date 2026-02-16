<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class EntradasSalidasEdit extends EntradasSalidas
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'entradas_salidas';

    // Page object name
    public $PageObjName = "EntradasSalidasEdit";

    // Rendering View
    public $RenderingView = false;

    // Audit Trail
    public $AuditTrailOnAdd = true;
    public $AuditTrailOnEdit = true;
    public $AuditTrailOnDelete = true;
    public $AuditTrailOnView = false;
    public $AuditTrailOnViewData = false;
    public $AuditTrailOnSearch = false;

    // Page headings
    public $Heading = "";
    public $Subheading = "";
    public $PageHeader;
    public $PageFooter;

    // Page terminated
    private $terminated = false;

    // Page heading
    public function pageHeading()
    {
        global $Language;
        if ($this->Heading != "") {
            return $this->Heading;
        }
        if (method_exists($this, "tableCaption")) {
            return $this->tableCaption();
        }
        return "";
    }

    // Page subheading
    public function pageSubheading()
    {
        global $Language;
        if ($this->Subheading != "") {
            return $this->Subheading;
        }
        if ($this->TableName) {
            return $Language->phrase($this->PageID);
        }
        return "";
    }

    // Page name
    public function pageName()
    {
        return CurrentPageName();
    }

    // Page URL
    public function pageUrl()
    {
        $url = ScriptName() . "?";
        if ($this->UseTokenInUrl) {
            $url .= "t=" . $this->TableVar . "&"; // Add page token
        }
        return $url;
    }

    // Show Page Header
    public function showPageHeader()
    {
        $header = $this->PageHeader;
        $this->pageDataRendering($header);
        if ($header != "") { // Header exists, display
            echo '<p id="ew-page-header">' . $header . '</p>';
        }
    }

    // Show Page Footer
    public function showPageFooter()
    {
        $footer = $this->PageFooter;
        $this->pageDataRendered($footer);
        if ($footer != "") { // Footer exists, display
            echo '<p id="ew-page-footer">' . $footer . '</p>';
        }
    }

    // Validate page request
    protected function isPageRequest()
    {
        global $CurrentForm;
        if ($this->UseTokenInUrl) {
            if ($CurrentForm) {
                return ($this->TableVar == $CurrentForm->getValue("t"));
            }
            if (Get("t") !== null) {
                return ($this->TableVar == Get("t"));
            }
        }
        return true;
    }

    // Constructor
    public function __construct()
    {
        global $Language, $DashboardReport, $DebugTimer;
        global $UserTable;

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("language");

        // Parent constuctor
        parent::__construct();

        // Table object (entradas_salidas)
        if (!isset($GLOBALS["entradas_salidas"]) || get_class($GLOBALS["entradas_salidas"]) == PROJECT_NAMESPACE . "entradas_salidas") {
            $GLOBALS["entradas_salidas"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'entradas_salidas');
        }

        // Start timer
        $DebugTimer = Container("timer");

        // Debug message
        LoadDebugMessage();

        // Open connection
        $GLOBALS["Conn"] = $GLOBALS["Conn"] ?? $this->getConnection();

        // User table object
        $UserTable = Container("usertable");
    }

    // Get content from stream
    public function getContents($stream = null): string
    {
        global $Response;
        return is_object($Response) ? $Response->getBody() : ob_get_clean();
    }

    // Is lookup
    public function isLookup()
    {
        return SameText(Route(0), Config("API_LOOKUP_ACTION"));
    }

    // Is AutoFill
    public function isAutoFill()
    {
        return $this->isLookup() && SameText(Post("ajax"), "autofill");
    }

    // Is AutoSuggest
    public function isAutoSuggest()
    {
        return $this->isLookup() && SameText(Post("ajax"), "autosuggest");
    }

    // Is modal lookup
    public function isModalLookup()
    {
        return $this->isLookup() && SameText(Post("ajax"), "modal");
    }

    // Is terminated
    public function isTerminated()
    {
        return $this->terminated;
    }

    /**
     * Terminate page
     *
     * @param string $url URL for direction
     * @return void
     */
    public function terminate($url = "")
    {
        if ($this->terminated) {
            return;
        }
        global $ExportFileName, $TempImages, $DashboardReport, $Response;

        // Page is terminated
        $this->terminated = true;

         // Page Unload event
        if (method_exists($this, "pageUnload")) {
            $this->pageUnload();
        }

        // Global Page Unloaded event (in userfn*.php)
        Page_Unloaded();

        // Export
        if ($this->CustomExport && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, Config("EXPORT_CLASSES"))) {
            $content = $this->getContents();
            if ($ExportFileName == "") {
                $ExportFileName = $this->TableVar;
            }
            $class = PROJECT_NAMESPACE . Config("EXPORT_CLASSES." . $this->CustomExport);
            if (class_exists($class)) {
                $doc = new $class(Container("entradas_salidas"));
                $doc->Text = @$content;
                if ($this->isExport("email")) {
                    echo $this->exportEmail($doc->Text);
                } else {
                    $doc->export();
                }
                DeleteTempImages(); // Delete temp images
                return;
            }
        }
        if (!IsApi() && method_exists($this, "pageRedirecting")) {
            $this->pageRedirecting($url);
        }

        // Close connection
        CloseConnections();

        // Return for API
        if (IsApi()) {
            $res = $url === true;
            if (!$res) { // Show error
                WriteJson(array_merge(["success" => false], $this->getMessages()));
            }
            return;
        } else { // Check if response is JSON
            if (StartsString("application/json", $Response->getHeaderLine("Content-type")) && $Response->getBody()->getSize()) { // With JSON response
                $this->clearMessages();
                return;
            }
        }

        // Go to URL if specified
        if ($url != "") {
            if (!Config("DEBUG") && ob_get_length()) {
                ob_end_clean();
            }

            // Handle modal response
            if ($this->IsModal) { // Show as modal
                $row = ["url" => GetUrl($url), "modal" => "1"];
                $pageName = GetPageName($url);
                if ($pageName != $this->getListUrl()) { // Not List page
                    $row["caption"] = $this->getModalCaption($pageName);
                    if ($pageName == "EntradasSalidasView") {
                        $row["view"] = "1";
                    }
                } else { // List page should not be shown as modal => error
                    $row["error"] = $this->getFailureMessage();
                    $this->clearFailureMessage();
                }
                WriteJson($row);
            } else {
                SaveDebugMessage();
                Redirect(GetUrl($url));
            }
        }
        return; // Return to controller
    }

    // Get records from recordset
    protected function getRecordsFromRecordset($rs, $current = false)
    {
        $rows = [];
        if (is_object($rs)) { // Recordset
            while ($rs && !$rs->EOF) {
                $this->loadRowValues($rs); // Set up DbValue/CurrentValue
                $row = $this->getRecordFromArray($rs->fields);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
                $rs->moveNext();
            }
        } elseif (is_array($rs)) {
            foreach ($rs as $ar) {
                $row = $this->getRecordFromArray($ar);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
            }
        }
        return $rows;
    }

    // Get record from array
    protected function getRecordFromArray($ar)
    {
        $row = [];
        if (is_array($ar)) {
            foreach ($ar as $fldname => $val) {
                if (array_key_exists($fldname, $this->Fields) && ($this->Fields[$fldname]->Visible || $this->Fields[$fldname]->IsPrimaryKey)) { // Primary key or Visible
                    $fld = &$this->Fields[$fldname];
                    if ($fld->HtmlTag == "FILE") { // Upload field
                        if (EmptyValue($val)) {
                            $row[$fldname] = null;
                        } else {
                            if ($fld->DataType == DATATYPE_BLOB) {
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . $fld->Param . "/" . rawurlencode($this->getRecordKeyValue($ar))));
                                $row[$fldname] = ["type" => ContentType($val), "url" => $url, "name" => $fld->Param . ContentExtension($val)];
                            } elseif (!$fld->UploadMultiple || !ContainsString($val, Config("MULTIPLE_UPLOAD_SEPARATOR"))) { // Single file
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $val)));
                                $row[$fldname] = ["type" => MimeContentType($val), "url" => $url, "name" => $val];
                            } else { // Multiple files
                                $files = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $val);
                                $ar = [];
                                foreach ($files as $file) {
                                    $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                        "/" . $fld->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $file)));
                                    if (!EmptyValue($file)) {
                                        $ar[] = ["type" => MimeContentType($file), "url" => $url, "name" => $file];
                                    }
                                }
                                $row[$fldname] = $ar;
                            }
                        }
                    } else {
                        $row[$fldname] = $val;
                    }
                }
            }
        }
        return $row;
    }

    // Get record key value from array
    protected function getRecordKeyValue($ar)
    {
        $key = "";
        if (is_array($ar)) {
            $key .= @$ar['id'];
        }
        return $key;
    }

    /**
     * Hide fields for add/edit
     *
     * @return void
     */
    protected function hideFieldsForAddEdit()
    {
        if ($this->isAdd() || $this->isCopy() || $this->isGridAdd()) {
            $this->id->Visible = false;
        }
    }

    // Lookup data
    public function lookup()
    {
        global $Language, $Security;

        // Get lookup object
        $fieldName = Post("field");
        $lookup = $this->Fields[$fieldName]->Lookup;

        // Get lookup parameters
        $lookupType = Post("ajax", "unknown");
        $pageSize = -1;
        $offset = -1;
        $searchValue = "";
        if (SameText($lookupType, "modal")) {
            $searchValue = Post("sv", "");
            $pageSize = Post("recperpage", 10);
            $offset = Post("start", 0);
        } elseif (SameText($lookupType, "autosuggest")) {
            $searchValue = Param("q", "");
            $pageSize = Param("n", -1);
            $pageSize = is_numeric($pageSize) ? (int)$pageSize : -1;
            if ($pageSize <= 0) {
                $pageSize = Config("AUTO_SUGGEST_MAX_ENTRIES");
            }
            $start = Param("start", -1);
            $start = is_numeric($start) ? (int)$start : -1;
            $page = Param("page", -1);
            $page = is_numeric($page) ? (int)$page : -1;
            $offset = $start >= 0 ? $start : ($page > 0 && $pageSize > 0 ? ($page - 1) * $pageSize : 0);
        }
        $userSelect = Decrypt(Post("s", ""));
        $userFilter = Decrypt(Post("f", ""));
        $userOrderBy = Decrypt(Post("o", ""));
        $keys = Post("keys");
        $lookup->LookupType = $lookupType; // Lookup type
        if ($keys !== null) { // Selected records from modal
            if (is_array($keys)) {
                $keys = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $keys);
            }
            $lookup->FilterFields = []; // Skip parent fields if any
            $lookup->FilterValues[] = $keys; // Lookup values
            $pageSize = -1; // Show all records
        } else { // Lookup values
            $lookup->FilterValues[] = Post("v0", Post("lookupValue", ""));
        }
        $cnt = is_array($lookup->FilterFields) ? count($lookup->FilterFields) : 0;
        for ($i = 1; $i <= $cnt; $i++) {
            $lookup->FilterValues[] = Post("v" . $i, "");
        }
        $lookup->SearchValue = $searchValue;
        $lookup->PageSize = $pageSize;
        $lookup->Offset = $offset;
        if ($userSelect != "") {
            $lookup->UserSelect = $userSelect;
        }
        if ($userFilter != "") {
            $lookup->UserFilter = $userFilter;
        }
        if ($userOrderBy != "") {
            $lookup->UserOrderBy = $userOrderBy;
        }
        $lookup->toJson($this); // Use settings from current page
    }
    public $FormClassName = "ew-horizontal ew-form ew-edit-form";
    public $IsModal = false;
    public $IsMobileOrModal = false;
    public $DbMasterFilter;
    public $DbDetailFilter;
    public $HashValue; // Hash Value
    public $DisplayRecords = 1;
    public $StartRecord;
    public $StopRecord;
    public $TotalRecords = 0;
    public $RecordRange = 10;
    public $RecordCount;

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $CustomExportType, $ExportFileName, $UserProfile, $Language, $Security, $CurrentForm,
            $SkipHeaderFooter;

        // Is modal
        $this->IsModal = Param("modal") == "1";

        // Create form object
        $CurrentForm = new HttpForm();
        $this->CurrentAction = Param("action"); // Set up current action
        $this->id->Visible = false;
        $this->tipo_documento->Visible = false;
        $this->id_documento->Visible = false;
        $this->fabricante->Visible = false;
        $this->articulo->setVisibility();
        $this->lote->Visible = false;
        $this->fecha_vencimiento->Visible = false;
        $this->almacen->Visible = false;
        $this->cantidad_articulo->setVisibility();
        $this->articulo_unidad_medida->setVisibility();
        $this->cantidad_unidad_medida->Visible = false;
        $this->cantidad_movimiento->Visible = false;
        $this->precio_unidad_sin_desc->Visible = false;
        $this->descuento->Visible = false;
        $this->costo_unidad->setVisibility();
        $this->costo->setVisibility();
        $this->precio_unidad->setVisibility();
        $this->precio->setVisibility();
        $this->id_compra->Visible = false;
        $this->alicuota->Visible = false;
        $this->cantidad_movimiento_consignacion->Visible = false;
        $this->id_consignacion->Visible = false;
        $this->check_ne->Visible = false;
        $this->hideFieldsForAddEdit();

        // Do not use lookup cache
        $this->setUseLookupCache(false);

        // Global Page Loading event (in userfn*.php)
        Page_Loading();

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Set up lookup cache
        $this->setupLookupOptions($this->articulo);
        $this->setupLookupOptions($this->almacen);
        $this->setupLookupOptions($this->articulo_unidad_medida);
        $this->setupLookupOptions($this->precio_unidad);

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        $this->FormClassName = "ew-form ew-edit-form ew-horizontal";

        // Load record by position
        $loadByPosition = false;
        $loaded = false;
        $postBack = false;

        // Set up current action and primary key
        if (IsApi()) {
            // Load key values
            $loaded = true;
            if (($keyValue = Get("id") ?? Key(0) ?? Route(2)) !== null) {
                $this->id->setQueryStringValue($keyValue);
                $this->id->setOldValue($this->id->QueryStringValue);
            } elseif (Post("id") !== null) {
                $this->id->setFormValue(Post("id"));
                $this->id->setOldValue($this->id->FormValue);
            } else {
                $loaded = false; // Unable to load key
            }

            // Load record
            if ($loaded) {
                $loaded = $this->loadRow();
            }
            if (!$loaded) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                $this->terminate();
                return;
            }
            $this->CurrentAction = "update"; // Update record directly
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
            $postBack = true;
        } else {
            if (Post("action") !== null) {
                $this->CurrentAction = Post("action"); // Get action code
                if (!$this->isShow()) { // Not reload record, handle as postback
                    $postBack = true;
                }

                // Get key from Form
                $this->setKey(Post($this->OldKeyName), $this->isShow());
            } else {
                $this->CurrentAction = "show"; // Default action is display

                // Load key from QueryString
                $loadByQuery = false;
                if (($keyValue = Get("id") ?? Route("id")) !== null) {
                    $this->id->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->id->CurrentValue = null;
                }
                if (!$loadByQuery) {
                    $loadByPosition = true;
                }
            }

            // Set up master detail parameters
            $this->setupMasterParms();

            // Load recordset
            if ($this->isShow()) {
                $this->StartRecord = 1; // Initialize start position
                if ($rs = $this->loadRecordset()) { // Load records
                    $this->TotalRecords = $rs->recordCount(); // Get record count
                }
                if ($this->TotalRecords <= 0) { // No record found
                    if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "") {
                        $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                    }
                    $this->terminate("EntradasSalidasList"); // Return to list page
                    return;
                } elseif ($loadByPosition) { // Load record by position
                    $this->setupStartRecord(); // Set up start record position
                    // Point to current record
                    if ($this->StartRecord <= $this->TotalRecords) {
                        $rs->move($this->StartRecord - 1);
                        $loaded = true;
                    }
                } else { // Match key values
                    if ($this->id->CurrentValue != null) {
                        while (!$rs->EOF) {
                            if (SameString($this->id->CurrentValue, $rs->fields['id'])) {
                                $this->setStartRecordNumber($this->StartRecord); // Save record position
                                $loaded = true;
                                break;
                            } else {
                                $this->StartRecord++;
                                $rs->moveNext();
                            }
                        }
                    }
                }

                // Load current row values
                if ($loaded) {
                    $this->loadRowValues($rs);
                }
                $this->OldKey = $loaded ? $this->getKey(true) : ""; // Get from CurrentValue
            }
        }

        // Process form if post back
        if ($postBack) {
            $this->loadFormValues(); // Get form values
        }

        // Validate form if post back
        if ($postBack) {
            if (!$this->validateForm()) {
                $this->EventCancelled = true; // Event cancelled
                $this->restoreFormValues();
                if (IsApi()) {
                    $this->terminate();
                    return;
                } else {
                    $this->CurrentAction = ""; // Form error, reset action
                }
            }
        }

        // Perform current action
        switch ($this->CurrentAction) {
            case "show": // Get a record to display
                if (!$loaded) {
                    if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "") {
                        $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                    }
                    $this->terminate("EntradasSalidasList"); // Return to list page
                    return;
                } else {
                }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "EntradasSalidasList") {
                    $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                }
                $this->SendEmail = true; // Send email on update success
                if ($this->editRow()) { // Update record based on key
                    if ($this->getSuccessMessage() == "") {
                        $this->setSuccessMessage($Language->phrase("UpdateSuccess")); // Update success
                    }
                    if (IsApi()) {
                        $this->terminate(true);
                        return;
                    } else {
                        $this->terminate($returnUrl); // Return to caller
                        return;
                    }
                } elseif (IsApi()) { // API request, return
                    $this->terminate();
                    return;
                } elseif ($this->getFailureMessage() == $Language->phrase("NoRecord")) {
                    $this->terminate($returnUrl); // Return to caller
                    return;
                } else {
                    $this->EventCancelled = true; // Event cancelled
                    $this->restoreFormValues(); // Restore form values if update failed
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render the record
        $this->RowType = ROWTYPE_EDIT; // Render as Edit
        $this->resetAttributes();
        $this->renderRow();
        $this->Pager = new PrevNextPager($this->StartRecord, $this->DisplayRecords, $this->TotalRecords, "", $this->RecordRange, $this->AutoHidePager);

        // Set LoginStatus / Page_Rendering / Page_Render
        if (!IsApi() && !$this->isTerminated()) {
            // Pass table and field properties to client side
            $this->toClientVar(["tableCaption"], ["caption", "Visible", "Required", "IsInvalid", "Raw"]);

            // Setup login status
            SetupLoginStatus();

            // Pass login status to client side
            SetClientVar("login", LoginStatus());

            // Global Page Rendering event (in userfn*.php)
            Page_Rendering();

            // Page Render event
            if (method_exists($this, "pageRender")) {
                $this->pageRender();
            }
        }
    }

    // Get upload files
    protected function getUploadFiles()
    {
        global $CurrentForm, $Language;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;

        // Check field name 'articulo' first before field var 'x_articulo'
        $val = $CurrentForm->hasValue("articulo") ? $CurrentForm->getValue("articulo") : $CurrentForm->getValue("x_articulo");
        if (!$this->articulo->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->articulo->Visible = false; // Disable update for API request
            } else {
                $this->articulo->setFormValue($val);
            }
        }

        // Check field name 'cantidad_articulo' first before field var 'x_cantidad_articulo'
        $val = $CurrentForm->hasValue("cantidad_articulo") ? $CurrentForm->getValue("cantidad_articulo") : $CurrentForm->getValue("x_cantidad_articulo");
        if (!$this->cantidad_articulo->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cantidad_articulo->Visible = false; // Disable update for API request
            } else {
                $this->cantidad_articulo->setFormValue($val);
            }
        }

        // Check field name 'articulo_unidad_medida' first before field var 'x_articulo_unidad_medida'
        $val = $CurrentForm->hasValue("articulo_unidad_medida") ? $CurrentForm->getValue("articulo_unidad_medida") : $CurrentForm->getValue("x_articulo_unidad_medida");
        if (!$this->articulo_unidad_medida->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->articulo_unidad_medida->Visible = false; // Disable update for API request
            } else {
                $this->articulo_unidad_medida->setFormValue($val);
            }
        }

        // Check field name 'costo_unidad' first before field var 'x_costo_unidad'
        $val = $CurrentForm->hasValue("costo_unidad") ? $CurrentForm->getValue("costo_unidad") : $CurrentForm->getValue("x_costo_unidad");
        if (!$this->costo_unidad->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->costo_unidad->Visible = false; // Disable update for API request
            } else {
                $this->costo_unidad->setFormValue($val);
            }
        }

        // Check field name 'costo' first before field var 'x_costo'
        $val = $CurrentForm->hasValue("costo") ? $CurrentForm->getValue("costo") : $CurrentForm->getValue("x_costo");
        if (!$this->costo->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->costo->Visible = false; // Disable update for API request
            } else {
                $this->costo->setFormValue($val);
            }
        }

        // Check field name 'precio_unidad' first before field var 'x_precio_unidad'
        $val = $CurrentForm->hasValue("precio_unidad") ? $CurrentForm->getValue("precio_unidad") : $CurrentForm->getValue("x_precio_unidad");
        if (!$this->precio_unidad->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->precio_unidad->Visible = false; // Disable update for API request
            } else {
                $this->precio_unidad->setFormValue($val);
            }
        }

        // Check field name 'precio' first before field var 'x_precio'
        $val = $CurrentForm->hasValue("precio") ? $CurrentForm->getValue("precio") : $CurrentForm->getValue("x_precio");
        if (!$this->precio->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->precio->Visible = false; // Disable update for API request
            } else {
                $this->precio->setFormValue($val);
            }
        }

        // Check field name 'id' first before field var 'x_id'
        $val = $CurrentForm->hasValue("id") ? $CurrentForm->getValue("id") : $CurrentForm->getValue("x_id");
        if (!$this->id->IsDetailKey) {
            $this->id->setFormValue($val);
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->id->CurrentValue = $this->id->FormValue;
        $this->articulo->CurrentValue = $this->articulo->FormValue;
        $this->cantidad_articulo->CurrentValue = $this->cantidad_articulo->FormValue;
        $this->articulo_unidad_medida->CurrentValue = $this->articulo_unidad_medida->FormValue;
        $this->costo_unidad->CurrentValue = $this->costo_unidad->FormValue;
        $this->costo->CurrentValue = $this->costo->FormValue;
        $this->precio_unidad->CurrentValue = $this->precio_unidad->FormValue;
        $this->precio->CurrentValue = $this->precio->FormValue;
    }

    // Load recordset
    public function loadRecordset($offset = -1, $rowcnt = -1)
    {
        // Load List page SQL (QueryBuilder)
        $sql = $this->getListSql();

        // Load recordset
        if ($offset > -1) {
            $sql->setFirstResult($offset);
        }
        if ($rowcnt > 0) {
            $sql->setMaxResults($rowcnt);
        }
        $stmt = $sql->execute();
        $rs = new Recordset($stmt, $sql);

        // Call Recordset Selected event
        $this->recordsetSelected($rs);
        return $rs;
    }

    /**
     * Load row based on key values
     *
     * @return void
     */
    public function loadRow()
    {
        global $Security, $Language;
        $filter = $this->getRecordFilter();

        // Call Row Selecting event
        $this->rowSelecting($filter);

        // Load SQL based on filter
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $res = false;
        $row = $conn->fetchAssoc($sql);
        if ($row) {
            $res = true;
            $this->loadRowValues($row); // Load row values
        }
        return $res;
    }

    /**
     * Load row values from recordset or record
     *
     * @param Recordset|array $rs Record
     * @return void
     */
    public function loadRowValues($rs = null)
    {
        if (is_array($rs)) {
            $row = $rs;
        } elseif ($rs && property_exists($rs, "fields")) { // Recordset
            $row = $rs->fields;
        } else {
            $row = $this->newRow();
        }

        // Call Row Selected event
        $this->rowSelected($row);
        if (!$rs) {
            return;
        }
        $this->id->setDbValue($row['id']);
        $this->tipo_documento->setDbValue($row['tipo_documento']);
        $this->id_documento->setDbValue($row['id_documento']);
        $this->fabricante->setDbValue($row['fabricante']);
        $this->articulo->setDbValue($row['articulo']);
        $this->lote->setDbValue($row['lote']);
        $this->fecha_vencimiento->setDbValue($row['fecha_vencimiento']);
        $this->almacen->setDbValue($row['almacen']);
        $this->cantidad_articulo->setDbValue($row['cantidad_articulo']);
        $this->articulo_unidad_medida->setDbValue($row['articulo_unidad_medida']);
        $this->cantidad_unidad_medida->setDbValue($row['cantidad_unidad_medida']);
        $this->cantidad_movimiento->setDbValue($row['cantidad_movimiento']);
        $this->precio_unidad_sin_desc->setDbValue($row['precio_unidad_sin_desc']);
        $this->descuento->setDbValue($row['descuento']);
        $this->costo_unidad->setDbValue($row['costo_unidad']);
        $this->costo->setDbValue($row['costo']);
        $this->precio_unidad->setDbValue($row['precio_unidad']);
        $this->precio->setDbValue($row['precio']);
        $this->id_compra->setDbValue($row['id_compra']);
        $this->alicuota->setDbValue($row['alicuota']);
        $this->cantidad_movimiento_consignacion->setDbValue($row['cantidad_movimiento_consignacion']);
        $this->id_consignacion->setDbValue($row['id_consignacion']);
        $this->check_ne->setDbValue($row['check_ne']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['id'] = null;
        $row['tipo_documento'] = null;
        $row['id_documento'] = null;
        $row['fabricante'] = null;
        $row['articulo'] = null;
        $row['lote'] = null;
        $row['fecha_vencimiento'] = null;
        $row['almacen'] = null;
        $row['cantidad_articulo'] = null;
        $row['articulo_unidad_medida'] = null;
        $row['cantidad_unidad_medida'] = null;
        $row['cantidad_movimiento'] = null;
        $row['precio_unidad_sin_desc'] = null;
        $row['descuento'] = null;
        $row['costo_unidad'] = null;
        $row['costo'] = null;
        $row['precio_unidad'] = null;
        $row['precio'] = null;
        $row['id_compra'] = null;
        $row['alicuota'] = null;
        $row['cantidad_movimiento_consignacion'] = null;
        $row['id_consignacion'] = null;
        $row['check_ne'] = null;
        return $row;
    }

    // Load old record
    protected function loadOldRecord()
    {
        // Load old record
        $this->OldRecordset = null;
        $validKey = $this->OldKey != "";
        if ($validKey) {
            $this->CurrentFilter = $this->getRecordFilter();
            $sql = $this->getCurrentSql();
            $conn = $this->getConnection();
            $this->OldRecordset = LoadRecordset($sql, $conn);
        }
        $this->loadRowValues($this->OldRecordset); // Load row values
        return $validKey;
    }

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs

        // Convert decimal values if posted back
        if ($this->cantidad_articulo->FormValue == $this->cantidad_articulo->CurrentValue && is_numeric(ConvertToFloatString($this->cantidad_articulo->CurrentValue))) {
            $this->cantidad_articulo->CurrentValue = ConvertToFloatString($this->cantidad_articulo->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->costo_unidad->FormValue == $this->costo_unidad->CurrentValue && is_numeric(ConvertToFloatString($this->costo_unidad->CurrentValue))) {
            $this->costo_unidad->CurrentValue = ConvertToFloatString($this->costo_unidad->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->costo->FormValue == $this->costo->CurrentValue && is_numeric(ConvertToFloatString($this->costo->CurrentValue))) {
            $this->costo->CurrentValue = ConvertToFloatString($this->costo->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->precio->FormValue == $this->precio->CurrentValue && is_numeric(ConvertToFloatString($this->precio->CurrentValue))) {
            $this->precio->CurrentValue = ConvertToFloatString($this->precio->CurrentValue);
        }

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // id

        // tipo_documento

        // id_documento

        // fabricante

        // articulo

        // lote

        // fecha_vencimiento

        // almacen

        // cantidad_articulo

        // articulo_unidad_medida

        // cantidad_unidad_medida

        // cantidad_movimiento

        // precio_unidad_sin_desc

        // descuento

        // costo_unidad

        // costo

        // precio_unidad

        // precio

        // id_compra

        // alicuota

        // cantidad_movimiento_consignacion

        // id_consignacion

        // check_ne
        if ($this->RowType == ROWTYPE_VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;
            $this->id->ViewCustomAttributes = "";

            // tipo_documento
            $this->tipo_documento->ViewValue = $this->tipo_documento->CurrentValue;
            $this->tipo_documento->ViewCustomAttributes = "";

            // id_documento
            $this->id_documento->ViewValue = $this->id_documento->CurrentValue;
            $this->id_documento->ViewCustomAttributes = "";

            // fabricante
            $this->fabricante->ViewCustomAttributes = "";

            // articulo
            $this->articulo->ViewValue = $this->articulo->CurrentValue;
            $curVal = trim(strval($this->articulo->CurrentValue));
            if ($curVal != "") {
                $this->articulo->ViewValue = $this->articulo->lookupCacheOption($curVal);
                if ($this->articulo->ViewValue === null) { // Lookup from database
                    $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->articulo->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->articulo->Lookup->renderViewRow($rswrk[0]);
                        $this->articulo->ViewValue = $this->articulo->displayValue($arwrk);
                    } else {
                        $this->articulo->ViewValue = $this->articulo->CurrentValue;
                    }
                }
            } else {
                $this->articulo->ViewValue = null;
            }
            $this->articulo->ViewCustomAttributes = "";

            // lote
            $this->lote->ViewValue = $this->lote->CurrentValue;
            $this->lote->ViewCustomAttributes = "";

            // fecha_vencimiento
            $this->fecha_vencimiento->ViewValue = $this->fecha_vencimiento->CurrentValue;
            $this->fecha_vencimiento->ViewValue = FormatDateTime($this->fecha_vencimiento->ViewValue, 7);
            $this->fecha_vencimiento->ViewCustomAttributes = "";

            // almacen
            $curVal = trim(strval($this->almacen->CurrentValue));
            if ($curVal != "") {
                $this->almacen->ViewValue = $this->almacen->lookupCacheOption($curVal);
                if ($this->almacen->ViewValue === null) { // Lookup from database
                    $filterWrk = "`codigo`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $sqlWrk = $this->almacen->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->almacen->Lookup->renderViewRow($rswrk[0]);
                        $this->almacen->ViewValue = $this->almacen->displayValue($arwrk);
                    } else {
                        $this->almacen->ViewValue = $this->almacen->CurrentValue;
                    }
                }
            } else {
                $this->almacen->ViewValue = null;
            }
            $this->almacen->ViewCustomAttributes = "";

            // cantidad_articulo
            $this->cantidad_articulo->ViewValue = $this->cantidad_articulo->CurrentValue;
            $this->cantidad_articulo->ViewValue = FormatNumber($this->cantidad_articulo->ViewValue, $this->cantidad_articulo->DefaultDecimalPrecision);
            $this->cantidad_articulo->ViewCustomAttributes = "";

            // articulo_unidad_medida
            $curVal = trim(strval($this->articulo_unidad_medida->CurrentValue));
            if ($curVal != "") {
                $this->articulo_unidad_medida->ViewValue = $this->articulo_unidad_medida->lookupCacheOption($curVal);
                if ($this->articulo_unidad_medida->ViewValue === null) { // Lookup from database
                    $filterWrk = "`unidad_medida`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $sqlWrk = $this->articulo_unidad_medida->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->articulo_unidad_medida->Lookup->renderViewRow($rswrk[0]);
                        $this->articulo_unidad_medida->ViewValue = $this->articulo_unidad_medida->displayValue($arwrk);
                    } else {
                        $this->articulo_unidad_medida->ViewValue = $this->articulo_unidad_medida->CurrentValue;
                    }
                }
            } else {
                $this->articulo_unidad_medida->ViewValue = null;
            }
            $this->articulo_unidad_medida->ViewCustomAttributes = "";

            // cantidad_unidad_medida
            $this->cantidad_unidad_medida->ViewValue = $this->cantidad_unidad_medida->CurrentValue;
            $this->cantidad_unidad_medida->ViewValue = FormatNumber($this->cantidad_unidad_medida->ViewValue, 2, -1, -1, -1);
            $this->cantidad_unidad_medida->ViewCustomAttributes = "";

            // cantidad_movimiento
            $this->cantidad_movimiento->ViewValue = $this->cantidad_movimiento->CurrentValue;
            $this->cantidad_movimiento->ViewValue = FormatNumber($this->cantidad_movimiento->ViewValue, 2, -1, -1, -1);
            $this->cantidad_movimiento->ViewCustomAttributes = "";

            // precio_unidad_sin_desc
            $this->precio_unidad_sin_desc->ViewValue = $this->precio_unidad_sin_desc->CurrentValue;
            $this->precio_unidad_sin_desc->ViewValue = FormatNumber($this->precio_unidad_sin_desc->ViewValue, $this->precio_unidad_sin_desc->DefaultDecimalPrecision);
            $this->precio_unidad_sin_desc->ViewCustomAttributes = "";

            // descuento
            $this->descuento->ViewValue = $this->descuento->CurrentValue;
            $this->descuento->ViewValue = FormatNumber($this->descuento->ViewValue, $this->descuento->DefaultDecimalPrecision);
            $this->descuento->ViewCustomAttributes = "";

            // costo_unidad
            $this->costo_unidad->ViewValue = $this->costo_unidad->CurrentValue;
            $this->costo_unidad->ViewValue = FormatNumber($this->costo_unidad->ViewValue, 2, -1, -1, -1);
            $this->costo_unidad->ViewCustomAttributes = "";

            // costo
            $this->costo->ViewValue = $this->costo->CurrentValue;
            $this->costo->ViewValue = FormatNumber($this->costo->ViewValue, 2, -1, -1, -1);
            $this->costo->ViewCustomAttributes = "";

            // precio_unidad
            $this->precio_unidad->ViewValue = $this->precio_unidad->CurrentValue;
            $curVal = trim(strval($this->precio_unidad->CurrentValue));
            if ($curVal != "") {
                $this->precio_unidad->ViewValue = $this->precio_unidad->lookupCacheOption($curVal);
                if ($this->precio_unidad->ViewValue === null) { // Lookup from database
                    $filterWrk = "`precio`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->precio_unidad->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->precio_unidad->Lookup->renderViewRow($rswrk[0]);
                        $this->precio_unidad->ViewValue = $this->precio_unidad->displayValue($arwrk);
                    } else {
                        $this->precio_unidad->ViewValue = $this->precio_unidad->CurrentValue;
                    }
                }
            } else {
                $this->precio_unidad->ViewValue = null;
            }
            $this->precio_unidad->ViewCustomAttributes = "";

            // precio
            $this->precio->ViewValue = $this->precio->CurrentValue;
            $this->precio->ViewValue = FormatNumber($this->precio->ViewValue, 2, -1, -1, -1);
            $this->precio->ViewCustomAttributes = "";

            // id_compra
            $this->id_compra->ViewValue = $this->id_compra->CurrentValue;
            $this->id_compra->ViewCustomAttributes = "";

            // alicuota
            $this->alicuota->ViewCustomAttributes = "";

            // cantidad_movimiento_consignacion
            $this->cantidad_movimiento_consignacion->ViewValue = $this->cantidad_movimiento_consignacion->CurrentValue;
            $this->cantidad_movimiento_consignacion->ViewValue = FormatNumber($this->cantidad_movimiento_consignacion->ViewValue, $this->cantidad_movimiento_consignacion->DefaultDecimalPrecision);
            $this->cantidad_movimiento_consignacion->ViewCustomAttributes = "";

            // id_consignacion
            $this->id_consignacion->ViewValue = $this->id_consignacion->CurrentValue;
            $this->id_consignacion->ViewCustomAttributes = "";

            // check_ne
            if (strval($this->check_ne->CurrentValue) != "") {
                $this->check_ne->ViewValue = new OptionValues();
                $arwrk = explode(",", strval($this->check_ne->CurrentValue));
                $cnt = count($arwrk);
                for ($ari = 0; $ari < $cnt; $ari++)
                    $this->check_ne->ViewValue->add($this->check_ne->optionCaption(trim($arwrk[$ari])));
            } else {
                $this->check_ne->ViewValue = null;
            }
            $this->check_ne->CssClass = "font-weight-bold font-italic";
            $this->check_ne->ViewCustomAttributes = "";

            // articulo
            $this->articulo->LinkCustomAttributes = "";
            $this->articulo->HrefValue = "";
            $this->articulo->TooltipValue = "";

            // cantidad_articulo
            $this->cantidad_articulo->LinkCustomAttributes = "";
            $this->cantidad_articulo->HrefValue = "";
            $this->cantidad_articulo->TooltipValue = "";

            // articulo_unidad_medida
            $this->articulo_unidad_medida->LinkCustomAttributes = "";
            $this->articulo_unidad_medida->HrefValue = "";
            $this->articulo_unidad_medida->TooltipValue = "";

            // costo_unidad
            $this->costo_unidad->LinkCustomAttributes = "";
            $this->costo_unidad->HrefValue = "";
            $this->costo_unidad->TooltipValue = "";

            // costo
            $this->costo->LinkCustomAttributes = "";
            $this->costo->HrefValue = "";
            $this->costo->TooltipValue = "";

            // precio_unidad
            $this->precio_unidad->LinkCustomAttributes = "";
            $this->precio_unidad->HrefValue = "";
            $this->precio_unidad->TooltipValue = "";

            // precio
            $this->precio->LinkCustomAttributes = "";
            $this->precio->HrefValue = "";
            $this->precio->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // articulo
            $this->articulo->EditAttrs["class"] = "form-control";
            $this->articulo->EditCustomAttributes = "";
            $this->articulo->EditValue = HtmlEncode($this->articulo->CurrentValue);
            $curVal = trim(strval($this->articulo->CurrentValue));
            if ($curVal != "") {
                $this->articulo->EditValue = $this->articulo->lookupCacheOption($curVal);
                if ($this->articulo->EditValue === null) { // Lookup from database
                    $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->articulo->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->articulo->Lookup->renderViewRow($rswrk[0]);
                        $this->articulo->EditValue = $this->articulo->displayValue($arwrk);
                    } else {
                        $this->articulo->EditValue = HtmlEncode($this->articulo->CurrentValue);
                    }
                }
            } else {
                $this->articulo->EditValue = null;
            }
            $this->articulo->PlaceHolder = RemoveHtml($this->articulo->caption());

            // cantidad_articulo
            $this->cantidad_articulo->EditAttrs["class"] = "form-control";
            $this->cantidad_articulo->EditCustomAttributes = "";
            $this->cantidad_articulo->EditValue = HtmlEncode($this->cantidad_articulo->CurrentValue);
            $this->cantidad_articulo->PlaceHolder = RemoveHtml($this->cantidad_articulo->caption());
            if (strval($this->cantidad_articulo->EditValue) != "" && is_numeric($this->cantidad_articulo->EditValue)) {
                $this->cantidad_articulo->EditValue = FormatNumber($this->cantidad_articulo->EditValue, -2, -1, -2, 0);
            }

            // articulo_unidad_medida
            $this->articulo_unidad_medida->EditAttrs["class"] = "form-control";
            $this->articulo_unidad_medida->EditCustomAttributes = "";
            $curVal = trim(strval($this->articulo_unidad_medida->CurrentValue));
            if ($curVal != "") {
                $this->articulo_unidad_medida->ViewValue = $this->articulo_unidad_medida->lookupCacheOption($curVal);
            } else {
                $this->articulo_unidad_medida->ViewValue = $this->articulo_unidad_medida->Lookup !== null && is_array($this->articulo_unidad_medida->Lookup->Options) ? $curVal : null;
            }
            if ($this->articulo_unidad_medida->ViewValue !== null) { // Load from cache
                $this->articulo_unidad_medida->EditValue = array_values($this->articulo_unidad_medida->Lookup->Options);
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`unidad_medida`" . SearchString("=", $this->articulo_unidad_medida->CurrentValue, DATATYPE_STRING, "");
                }
                $sqlWrk = $this->articulo_unidad_medida->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                $arwrk = $rswrk;
                foreach ($arwrk as &$row)
                    $row = $this->articulo_unidad_medida->Lookup->renderViewRow($row);
                $this->articulo_unidad_medida->EditValue = $arwrk;
            }
            $this->articulo_unidad_medida->PlaceHolder = RemoveHtml($this->articulo_unidad_medida->caption());

            // costo_unidad
            $this->costo_unidad->EditAttrs["class"] = "form-control";
            $this->costo_unidad->EditCustomAttributes = "";
            $this->costo_unidad->EditValue = HtmlEncode($this->costo_unidad->CurrentValue);
            $this->costo_unidad->PlaceHolder = RemoveHtml($this->costo_unidad->caption());
            if (strval($this->costo_unidad->EditValue) != "" && is_numeric($this->costo_unidad->EditValue)) {
                $this->costo_unidad->EditValue = FormatNumber($this->costo_unidad->EditValue, -2, -1, -2, -1);
            }

            // costo
            $this->costo->EditAttrs["class"] = "form-control";
            $this->costo->EditCustomAttributes = "";
            $this->costo->EditValue = HtmlEncode($this->costo->CurrentValue);
            $this->costo->PlaceHolder = RemoveHtml($this->costo->caption());
            if (strval($this->costo->EditValue) != "" && is_numeric($this->costo->EditValue)) {
                $this->costo->EditValue = FormatNumber($this->costo->EditValue, -2, -1, -2, -1);
            }

            // precio_unidad
            $this->precio_unidad->EditAttrs["class"] = "form-control";
            $this->precio_unidad->EditCustomAttributes = "";
            $this->precio_unidad->EditValue = HtmlEncode($this->precio_unidad->CurrentValue);
            $curVal = trim(strval($this->precio_unidad->CurrentValue));
            if ($curVal != "") {
                $this->precio_unidad->EditValue = $this->precio_unidad->lookupCacheOption($curVal);
                if ($this->precio_unidad->EditValue === null) { // Lookup from database
                    $filterWrk = "`precio`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->precio_unidad->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->precio_unidad->Lookup->renderViewRow($rswrk[0]);
                        $this->precio_unidad->EditValue = $this->precio_unidad->displayValue($arwrk);
                    } else {
                        $this->precio_unidad->EditValue = HtmlEncode($this->precio_unidad->CurrentValue);
                    }
                }
            } else {
                $this->precio_unidad->EditValue = null;
            }
            $this->precio_unidad->PlaceHolder = RemoveHtml($this->precio_unidad->caption());

            // precio
            $this->precio->EditAttrs["class"] = "form-control";
            $this->precio->EditCustomAttributes = "";
            $this->precio->EditValue = HtmlEncode($this->precio->CurrentValue);
            $this->precio->PlaceHolder = RemoveHtml($this->precio->caption());
            if (strval($this->precio->EditValue) != "" && is_numeric($this->precio->EditValue)) {
                $this->precio->EditValue = FormatNumber($this->precio->EditValue, -2, -1, -2, -1);
            }

            // Edit refer script

            // articulo
            $this->articulo->LinkCustomAttributes = "";
            $this->articulo->HrefValue = "";

            // cantidad_articulo
            $this->cantidad_articulo->LinkCustomAttributes = "";
            $this->cantidad_articulo->HrefValue = "";

            // articulo_unidad_medida
            $this->articulo_unidad_medida->LinkCustomAttributes = "";
            $this->articulo_unidad_medida->HrefValue = "";

            // costo_unidad
            $this->costo_unidad->LinkCustomAttributes = "";
            $this->costo_unidad->HrefValue = "";

            // costo
            $this->costo->LinkCustomAttributes = "";
            $this->costo->HrefValue = "";

            // precio_unidad
            $this->precio_unidad->LinkCustomAttributes = "";
            $this->precio_unidad->HrefValue = "";

            // precio
            $this->precio->LinkCustomAttributes = "";
            $this->precio->HrefValue = "";
        }
        if ($this->RowType == ROWTYPE_ADD || $this->RowType == ROWTYPE_EDIT || $this->RowType == ROWTYPE_SEARCH) { // Add/Edit/Search row
            $this->setupFieldTitles();
        }

        // Call Row Rendered event
        if ($this->RowType != ROWTYPE_AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Validate form
    protected function validateForm()
    {
        global $Language;

        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        if ($this->articulo->Required) {
            if (!$this->articulo->IsDetailKey && EmptyValue($this->articulo->FormValue)) {
                $this->articulo->addErrorMessage(str_replace("%s", $this->articulo->caption(), $this->articulo->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->articulo->FormValue)) {
            $this->articulo->addErrorMessage($this->articulo->getErrorMessage(false));
        }
        if ($this->cantidad_articulo->Required) {
            if (!$this->cantidad_articulo->IsDetailKey && EmptyValue($this->cantidad_articulo->FormValue)) {
                $this->cantidad_articulo->addErrorMessage(str_replace("%s", $this->cantidad_articulo->caption(), $this->cantidad_articulo->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->cantidad_articulo->FormValue)) {
            $this->cantidad_articulo->addErrorMessage($this->cantidad_articulo->getErrorMessage(false));
        }
        if ($this->articulo_unidad_medida->Required) {
            if (!$this->articulo_unidad_medida->IsDetailKey && EmptyValue($this->articulo_unidad_medida->FormValue)) {
                $this->articulo_unidad_medida->addErrorMessage(str_replace("%s", $this->articulo_unidad_medida->caption(), $this->articulo_unidad_medida->RequiredErrorMessage));
            }
        }
        if ($this->costo_unidad->Required) {
            if (!$this->costo_unidad->IsDetailKey && EmptyValue($this->costo_unidad->FormValue)) {
                $this->costo_unidad->addErrorMessage(str_replace("%s", $this->costo_unidad->caption(), $this->costo_unidad->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->costo_unidad->FormValue)) {
            $this->costo_unidad->addErrorMessage($this->costo_unidad->getErrorMessage(false));
        }
        if ($this->costo->Required) {
            if (!$this->costo->IsDetailKey && EmptyValue($this->costo->FormValue)) {
                $this->costo->addErrorMessage(str_replace("%s", $this->costo->caption(), $this->costo->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->costo->FormValue)) {
            $this->costo->addErrorMessage($this->costo->getErrorMessage(false));
        }
        if ($this->precio_unidad->Required) {
            if (!$this->precio_unidad->IsDetailKey && EmptyValue($this->precio_unidad->FormValue)) {
                $this->precio_unidad->addErrorMessage(str_replace("%s", $this->precio_unidad->caption(), $this->precio_unidad->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->precio_unidad->FormValue)) {
            $this->precio_unidad->addErrorMessage($this->precio_unidad->getErrorMessage(false));
        }
        if ($this->precio->Required) {
            if (!$this->precio->IsDetailKey && EmptyValue($this->precio->FormValue)) {
                $this->precio->addErrorMessage(str_replace("%s", $this->precio->caption(), $this->precio->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->precio->FormValue)) {
            $this->precio->addErrorMessage($this->precio->getErrorMessage(false));
        }

        // Return validate result
        $validateForm = !$this->hasInvalidFields();

        // Call Form_CustomValidate event
        $formCustomError = "";
        $validateForm = $validateForm && $this->formCustomValidate($formCustomError);
        if ($formCustomError != "") {
            $this->setFailureMessage($formCustomError);
        }
        return $validateForm;
    }

    // Update record based on key values
    protected function editRow()
    {
        global $Security, $Language;
        $oldKeyFilter = $this->getRecordFilter();
        $filter = $this->applyUserIDFilters($oldKeyFilter);
        $conn = $this->getConnection();
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $rsold = $conn->fetchAssoc($sql);
        $editRow = false;
        if (!$rsold) {
            $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
            $editRow = false; // Update Failed
        } else {
            // Save old values
            $this->loadDbValues($rsold);
            $rsnew = [];

            // articulo
            $this->articulo->setDbValueDef($rsnew, $this->articulo->CurrentValue, null, $this->articulo->ReadOnly);

            // cantidad_articulo
            $this->cantidad_articulo->setDbValueDef($rsnew, $this->cantidad_articulo->CurrentValue, null, $this->cantidad_articulo->ReadOnly);

            // articulo_unidad_medida
            $this->articulo_unidad_medida->setDbValueDef($rsnew, $this->articulo_unidad_medida->CurrentValue, null, $this->articulo_unidad_medida->ReadOnly);

            // costo_unidad
            $this->costo_unidad->setDbValueDef($rsnew, $this->costo_unidad->CurrentValue, null, $this->costo_unidad->ReadOnly);

            // costo
            $this->costo->setDbValueDef($rsnew, $this->costo->CurrentValue, null, $this->costo->ReadOnly);

            // precio_unidad
            $this->precio_unidad->setDbValueDef($rsnew, $this->precio_unidad->CurrentValue, null, $this->precio_unidad->ReadOnly);

            // precio
            $this->precio->setDbValueDef($rsnew, $this->precio->CurrentValue, null, $this->precio->ReadOnly);

            // Call Row Updating event
            $updateRow = $this->rowUpdating($rsold, $rsnew);
            if ($updateRow) {
                if (count($rsnew) > 0) {
                    try {
                        $editRow = $this->update($rsnew, "", $rsold);
                    } catch (\Exception $e) {
                        $this->setFailureMessage($e->getMessage());
                    }
                } else {
                    $editRow = true; // No field to update
                }
                if ($editRow) {
                }
            } else {
                if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                    // Use the message, do nothing
                } elseif ($this->CancelMessage != "") {
                    $this->setFailureMessage($this->CancelMessage);
                    $this->CancelMessage = "";
                } else {
                    $this->setFailureMessage($Language->phrase("UpdateCancelled"));
                }
                $editRow = false;
            }
        }

        // Call Row_Updated event
        if ($editRow) {
            $this->rowUpdated($rsold, $rsnew);
        }

        // Clean upload path if any
        if ($editRow) {
        }

        // Write JSON for API request
        if (IsApi() && $editRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            WriteJson(["success" => true, $this->TableVar => $row]);
        }
        return $editRow;
    }

    // Set up master/detail based on QueryString
    protected function setupMasterParms()
    {
        $validMaster = false;
        // Get the keys for master table
        if (($master = Get(Config("TABLE_SHOW_MASTER"), Get(Config("TABLE_MASTER")))) !== null) {
            $masterTblVar = $master;
            if ($masterTblVar == "") {
                $validMaster = true;
                $this->DbMasterFilter = "";
                $this->DbDetailFilter = "";
            }
            if ($masterTblVar == "entradas") {
                $validMaster = true;
                $masterTbl = Container("entradas");
                if (($parm = Get("fk_tipo_documento", Get("tipo_documento"))) !== null) {
                    $masterTbl->tipo_documento->setQueryStringValue($parm);
                    $this->tipo_documento->setQueryStringValue($masterTbl->tipo_documento->QueryStringValue);
                    $this->tipo_documento->setSessionValue($this->tipo_documento->QueryStringValue);
                } else {
                    $validMaster = false;
                }
                if (($parm = Get("fk_id", Get("id_documento"))) !== null) {
                    $masterTbl->id->setQueryStringValue($parm);
                    $this->id_documento->setQueryStringValue($masterTbl->id->QueryStringValue);
                    $this->id_documento->setSessionValue($this->id_documento->QueryStringValue);
                    if (!is_numeric($masterTbl->id->QueryStringValue)) {
                        $validMaster = false;
                    }
                } else {
                    $validMaster = false;
                }
            }
            if ($masterTblVar == "salidas") {
                $validMaster = true;
                $masterTbl = Container("salidas");
                if (($parm = Get("fk_tipo_documento", Get("tipo_documento"))) !== null) {
                    $masterTbl->tipo_documento->setQueryStringValue($parm);
                    $this->tipo_documento->setQueryStringValue($masterTbl->tipo_documento->QueryStringValue);
                    $this->tipo_documento->setSessionValue($this->tipo_documento->QueryStringValue);
                } else {
                    $validMaster = false;
                }
                if (($parm = Get("fk_id", Get("id_documento"))) !== null) {
                    $masterTbl->id->setQueryStringValue($parm);
                    $this->id_documento->setQueryStringValue($masterTbl->id->QueryStringValue);
                    $this->id_documento->setSessionValue($this->id_documento->QueryStringValue);
                    if (!is_numeric($masterTbl->id->QueryStringValue)) {
                        $validMaster = false;
                    }
                } else {
                    $validMaster = false;
                }
            }
        } elseif (($master = Post(Config("TABLE_SHOW_MASTER"), Post(Config("TABLE_MASTER")))) !== null) {
            $masterTblVar = $master;
            if ($masterTblVar == "") {
                    $validMaster = true;
                    $this->DbMasterFilter = "";
                    $this->DbDetailFilter = "";
            }
            if ($masterTblVar == "entradas") {
                $validMaster = true;
                $masterTbl = Container("entradas");
                if (($parm = Post("fk_tipo_documento", Post("tipo_documento"))) !== null) {
                    $masterTbl->tipo_documento->setFormValue($parm);
                    $this->tipo_documento->setFormValue($masterTbl->tipo_documento->FormValue);
                    $this->tipo_documento->setSessionValue($this->tipo_documento->FormValue);
                } else {
                    $validMaster = false;
                }
                if (($parm = Post("fk_id", Post("id_documento"))) !== null) {
                    $masterTbl->id->setFormValue($parm);
                    $this->id_documento->setFormValue($masterTbl->id->FormValue);
                    $this->id_documento->setSessionValue($this->id_documento->FormValue);
                    if (!is_numeric($masterTbl->id->FormValue)) {
                        $validMaster = false;
                    }
                } else {
                    $validMaster = false;
                }
            }
            if ($masterTblVar == "salidas") {
                $validMaster = true;
                $masterTbl = Container("salidas");
                if (($parm = Post("fk_tipo_documento", Post("tipo_documento"))) !== null) {
                    $masterTbl->tipo_documento->setFormValue($parm);
                    $this->tipo_documento->setFormValue($masterTbl->tipo_documento->FormValue);
                    $this->tipo_documento->setSessionValue($this->tipo_documento->FormValue);
                } else {
                    $validMaster = false;
                }
                if (($parm = Post("fk_id", Post("id_documento"))) !== null) {
                    $masterTbl->id->setFormValue($parm);
                    $this->id_documento->setFormValue($masterTbl->id->FormValue);
                    $this->id_documento->setSessionValue($this->id_documento->FormValue);
                    if (!is_numeric($masterTbl->id->FormValue)) {
                        $validMaster = false;
                    }
                } else {
                    $validMaster = false;
                }
            }
        }
        if ($validMaster) {
            // Save current master table
            $this->setCurrentMasterTable($masterTblVar);
            $this->setSessionWhere($this->getDetailFilter());

            // Reset start record counter (new master key)
            if (!$this->isAddOrEdit()) {
                $this->StartRecord = 1;
                $this->setStartRecordNumber($this->StartRecord);
            }

            // Clear previous master key from Session
            if ($masterTblVar != "entradas") {
                if ($this->tipo_documento->CurrentValue == "") {
                    $this->tipo_documento->setSessionValue("");
                }
                if ($this->id_documento->CurrentValue == "") {
                    $this->id_documento->setSessionValue("");
                }
            }
            if ($masterTblVar != "salidas") {
                if ($this->tipo_documento->CurrentValue == "") {
                    $this->tipo_documento->setSessionValue("");
                }
                if ($this->id_documento->CurrentValue == "") {
                    $this->id_documento->setSessionValue("");
                }
            }
        }
        $this->DbMasterFilter = $this->getMasterFilter(); // Get master filter
        $this->DbDetailFilter = $this->getDetailFilter(); // Get detail filter
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("Home");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("EntradasSalidasList"), "", $this->TableVar, true);
        $pageId = "edit";
        $Breadcrumb->add("edit", $pageId, $url);
    }

    // Setup lookup options
    public function setupLookupOptions($fld)
    {
        if ($fld->Lookup !== null && $fld->Lookup->Options === null) {
            // Get default connection and filter
            $conn = $this->getConnection();
            $lookupFilter = "";

            // No need to check any more
            $fld->Lookup->Options = [];

            // Set up lookup SQL and connection
            switch ($fld->FieldVar) {
                case "x_articulo":
                    break;
                case "x_almacen":
                    break;
                case "x_articulo_unidad_medida":
                    break;
                case "x_precio_unidad":
                    break;
                case "x_check_ne":
                    break;
                default:
                    $lookupFilter = "";
                    break;
            }

            // Always call to Lookup->getSql so that user can setup Lookup->Options in Lookup_Selecting server event
            $sql = $fld->Lookup->getSql(false, "", $lookupFilter, $this);

            // Set up lookup cache
            if ($fld->UseLookupCache && $sql != "" && count($fld->Lookup->Options) == 0) {
                $totalCnt = $this->getRecordCount($sql, $conn);
                if ($totalCnt > $fld->LookupCacheCount) { // Total count > cache count, do not cache
                    return;
                }
                $rows = $conn->executeQuery($sql)->fetchAll(\PDO::FETCH_BOTH);
                $ar = [];
                foreach ($rows as $row) {
                    $row = $fld->Lookup->renderViewRow($row);
                    $ar[strval($row[0])] = $row;
                }
                $fld->Lookup->Options = $ar;
            }
        }
    }

    // Set up starting record parameters
    public function setupStartRecord()
    {
        if ($this->DisplayRecords == 0) {
            return;
        }
        if ($this->isPageRequest()) { // Validate request
            $startRec = Get(Config("TABLE_START_REC"));
            $pageNo = Get(Config("TABLE_PAGE_NO"));
            if ($pageNo !== null) { // Check for "pageno" parameter first
                if (is_numeric($pageNo)) {
                    $this->StartRecord = ($pageNo - 1) * $this->DisplayRecords + 1;
                    if ($this->StartRecord <= 0) {
                        $this->StartRecord = 1;
                    } elseif ($this->StartRecord >= (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1) {
                        $this->StartRecord = (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1;
                    }
                    $this->setStartRecordNumber($this->StartRecord);
                }
            } elseif ($startRec !== null) { // Check for "start" parameter
                $this->StartRecord = $startRec;
                $this->setStartRecordNumber($this->StartRecord);
            }
        }
        $this->StartRecord = $this->getStartRecordNumber();

        // Check if correct start record counter
        if (!is_numeric($this->StartRecord) || $this->StartRecord == "") { // Avoid invalid start record counter
            $this->StartRecord = 1; // Reset start record counter
            $this->setStartRecordNumber($this->StartRecord);
        } elseif ($this->StartRecord > $this->TotalRecords) { // Avoid starting record > total records
            $this->StartRecord = (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to last page first record
            $this->setStartRecordNumber($this->StartRecord);
        } elseif (($this->StartRecord - 1) % $this->DisplayRecords != 0) {
            $this->StartRecord = (int)(($this->StartRecord - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to page boundary
            $this->setStartRecordNumber($this->StartRecord);
        }
    }

    // Page Load event
    public function pageLoad()
    {
        //Log("Page Load");
    }

    // Page Unload event
    public function pageUnload()
    {
        //Log("Page Unload");
    }

    // Page Redirecting event
    public function pageRedirecting(&$url)
    {
        // Example:
        //$url = "your URL";
    }

    // Message Showing event
    // $type = ''|'success'|'failure'|'warning'
    public function messageShowing(&$msg, $type)
    {
        if ($type == 'success') {
            //$msg = "your success message";
        } elseif ($type == 'failure') {
            //$msg = "your failure message";
        } elseif ($type == 'warning') {
            //$msg = "your warning message";
        } else {
            //$msg = "your message";
        }
    }

    // Page Render event
    public function pageRender() {
    	//echo "Page Render";
    	$this->check_ne->Visible = FALSE;
    	$this->costo_unidad->Visible = FALSE;
    	$this->costo->Visible = FALSE;
    	$this->descuento->Visible = FALSE;
    	$this->precio_unidad_sin_desc->Visible = FALSE;
    	$sql = "SELECT tipo_documento FROM username_tipo_documento WHERE username = '" . CurrentUserName() . "';";
    	$tipo = ExecuteScalar($sql);
    	switch($tipo) {
    	case "TDCPDC":
    		$this->precio_unidad_sin_desc->Visible = TRUE;
    		$this->descuento->Visible = TRUE;
    		$this->precio_unidad->Visible = FALSE;
    		$this->precio->Visible = FALSE;
    		$this->id_compra->Visible = FALSE;
    		$this->costo_unidad->Visible = FALSE;
    		break;
    	case "TDCNRP":
    		$this->precio_unidad_sin_desc->Visible = TRUE;
    		$this->descuento->Visible = TRUE;
    		$this->precio_unidad->Visible = FALSE;
    		$this->precio->Visible = FALSE;
    		$this->id_compra->Visible = FALSE;
    		$this->costo_unidad->Visible = FALSE;
    		break;
    	case "TDCFCC":
    		$this->lote->Visible = TRUE;
    		$this->fecha_vencimiento->Visible = TRUE;
    		$this->precio_unidad_sin_desc->Visible = TRUE;
    		$this->descuento->Visible = TRUE;
    		$this->precio_unidad->Visible = FALSE;
    		$this->precio->Visible = FALSE;
    		$this->id_compra->Visible = FALSE;
    		$this->costo_unidad->Visible = FALSE;
    		break;
    	case "TDCAEN":
    		$this->precio_unidad_sin_desc->Visible = TRUE;
    		$this->descuento->Visible = TRUE;
    		$this->precio_unidad->Visible = FALSE;
    		$this->precio->Visible = FALSE;
    		$this->id_compra->Visible = FALSE;
    		$this->costo_unidad->Visible = FALSE;
    		break;
    	case "TDCPDV":
    		$this->lote->Visible = FALSE;
    		$this->fecha_vencimiento->Visible = FALSE;
    		$this->costo_unidad->Visible = FALSE;
    		$this->costo->Visible = FALSE;
    		$this->precio_unidad->Visible = FALSE;
    		$this->precio->Visible = FALSE;
    		$this->id_compra->Visible = FALSE;
    		break;
    	case "TDCNET":
    		$this->lote->Visible = TRUE;
    		$this->fecha_vencimiento->Visible = TRUE;
    		$this->costo_unidad->Visible = TRUE;
    		$this->costo->Visible = TRUE;
    		$this->precio_unidad->Visible = TRUE;
    		$this->precio->Visible = TRUE;
    		$this->id_compra->Visible = FALSE;
    		$this->check_ne->Visible = TRUE;
    		break;
    	case "TDCFCV":
    		$this->lote->Visible = TRUE;
    		$this->fecha_vencimiento->Visible = TRUE;
    		$this->costo_unidad->Visible = FALSE;
    		$this->costo->Visible = FALSE;
    		$this->precio_unidad->Visible = TRUE;
    		$this->precio->Visible = TRUE;
    		$this->id_compra->Visible = FALSE;
    		break;
    	case "TDCASA":
    		$this->lote->Visible = FALSE;
    		$this->fecha_vencimiento->Visible = FALSE;
    		$this->costo_unidad->Visible = FALSE;
    		$this->costo->Visible = FALSE;
    		$this->precio_unidad->Visible = FALSE;
    		$this->precio->Visible = FALSE;
    		$this->id_compra->Visible = TRUE;
    	}
    }

    // Page Data Rendering event
    public function pageDataRendering(&$header)
    {
        // Example:
        //$header = "your header";
    }

    // Page Data Rendered event
    public function pageDataRendered(&$footer)
    {
        // Example:
        //$footer = "your footer";
    }

    // Form Custom Validate event
    public function formCustomValidate(&$customError)
    {
        // Return error message in CustomError
        return true;
    }
}
