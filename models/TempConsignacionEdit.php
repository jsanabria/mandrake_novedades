<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class TempConsignacionEdit extends TempConsignacion
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'temp_consignacion';

    // Page object name
    public $PageObjName = "TempConsignacionEdit";

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

        // Table object (temp_consignacion)
        if (!isset($GLOBALS["temp_consignacion"]) || get_class($GLOBALS["temp_consignacion"]) == PROJECT_NAMESPACE . "temp_consignacion") {
            $GLOBALS["temp_consignacion"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'temp_consignacion');
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
                $doc = new $class(Container("temp_consignacion"));
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
                    if ($pageName == "TempConsignacionView") {
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
        $this->id->setVisibility();
        $this->_username->setVisibility();
        $this->nro_documento->setVisibility();
        $this->id_documento->setVisibility();
        $this->tipo_documento->setVisibility();
        $this->fabricante->setVisibility();
        $this->articulo->setVisibility();
        $this->cantidad_movimiento->setVisibility();
        $this->cantidad_entre_fechas->setVisibility();
        $this->cantidad_acumulada->setVisibility();
        $this->cantidad_ajuste->setVisibility();
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
                    $this->terminate("TempConsignacionList"); // Return to list page
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
                    $this->terminate("TempConsignacionList"); // Return to list page
                    return;
                } else {
                }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "TempConsignacionList") {
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

        // Check field name 'id' first before field var 'x_id'
        $val = $CurrentForm->hasValue("id") ? $CurrentForm->getValue("id") : $CurrentForm->getValue("x_id");
        if (!$this->id->IsDetailKey) {
            $this->id->setFormValue($val);
        }

        // Check field name 'username' first before field var 'x__username'
        $val = $CurrentForm->hasValue("username") ? $CurrentForm->getValue("username") : $CurrentForm->getValue("x__username");
        if (!$this->_username->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->_username->Visible = false; // Disable update for API request
            } else {
                $this->_username->setFormValue($val);
            }
        }

        // Check field name 'nro_documento' first before field var 'x_nro_documento'
        $val = $CurrentForm->hasValue("nro_documento") ? $CurrentForm->getValue("nro_documento") : $CurrentForm->getValue("x_nro_documento");
        if (!$this->nro_documento->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->nro_documento->Visible = false; // Disable update for API request
            } else {
                $this->nro_documento->setFormValue($val);
            }
        }

        // Check field name 'id_documento' first before field var 'x_id_documento'
        $val = $CurrentForm->hasValue("id_documento") ? $CurrentForm->getValue("id_documento") : $CurrentForm->getValue("x_id_documento");
        if (!$this->id_documento->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->id_documento->Visible = false; // Disable update for API request
            } else {
                $this->id_documento->setFormValue($val);
            }
        }

        // Check field name 'tipo_documento' first before field var 'x_tipo_documento'
        $val = $CurrentForm->hasValue("tipo_documento") ? $CurrentForm->getValue("tipo_documento") : $CurrentForm->getValue("x_tipo_documento");
        if (!$this->tipo_documento->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->tipo_documento->Visible = false; // Disable update for API request
            } else {
                $this->tipo_documento->setFormValue($val);
            }
        }

        // Check field name 'fabricante' first before field var 'x_fabricante'
        $val = $CurrentForm->hasValue("fabricante") ? $CurrentForm->getValue("fabricante") : $CurrentForm->getValue("x_fabricante");
        if (!$this->fabricante->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->fabricante->Visible = false; // Disable update for API request
            } else {
                $this->fabricante->setFormValue($val);
            }
        }

        // Check field name 'articulo' first before field var 'x_articulo'
        $val = $CurrentForm->hasValue("articulo") ? $CurrentForm->getValue("articulo") : $CurrentForm->getValue("x_articulo");
        if (!$this->articulo->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->articulo->Visible = false; // Disable update for API request
            } else {
                $this->articulo->setFormValue($val);
            }
        }

        // Check field name 'cantidad_movimiento' first before field var 'x_cantidad_movimiento'
        $val = $CurrentForm->hasValue("cantidad_movimiento") ? $CurrentForm->getValue("cantidad_movimiento") : $CurrentForm->getValue("x_cantidad_movimiento");
        if (!$this->cantidad_movimiento->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cantidad_movimiento->Visible = false; // Disable update for API request
            } else {
                $this->cantidad_movimiento->setFormValue($val);
            }
        }

        // Check field name 'cantidad_entre_fechas' first before field var 'x_cantidad_entre_fechas'
        $val = $CurrentForm->hasValue("cantidad_entre_fechas") ? $CurrentForm->getValue("cantidad_entre_fechas") : $CurrentForm->getValue("x_cantidad_entre_fechas");
        if (!$this->cantidad_entre_fechas->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cantidad_entre_fechas->Visible = false; // Disable update for API request
            } else {
                $this->cantidad_entre_fechas->setFormValue($val);
            }
        }

        // Check field name 'cantidad_acumulada' first before field var 'x_cantidad_acumulada'
        $val = $CurrentForm->hasValue("cantidad_acumulada") ? $CurrentForm->getValue("cantidad_acumulada") : $CurrentForm->getValue("x_cantidad_acumulada");
        if (!$this->cantidad_acumulada->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cantidad_acumulada->Visible = false; // Disable update for API request
            } else {
                $this->cantidad_acumulada->setFormValue($val);
            }
        }

        // Check field name 'cantidad_ajuste' first before field var 'x_cantidad_ajuste'
        $val = $CurrentForm->hasValue("cantidad_ajuste") ? $CurrentForm->getValue("cantidad_ajuste") : $CurrentForm->getValue("x_cantidad_ajuste");
        if (!$this->cantidad_ajuste->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cantidad_ajuste->Visible = false; // Disable update for API request
            } else {
                $this->cantidad_ajuste->setFormValue($val);
            }
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->id->CurrentValue = $this->id->FormValue;
        $this->_username->CurrentValue = $this->_username->FormValue;
        $this->nro_documento->CurrentValue = $this->nro_documento->FormValue;
        $this->id_documento->CurrentValue = $this->id_documento->FormValue;
        $this->tipo_documento->CurrentValue = $this->tipo_documento->FormValue;
        $this->fabricante->CurrentValue = $this->fabricante->FormValue;
        $this->articulo->CurrentValue = $this->articulo->FormValue;
        $this->cantidad_movimiento->CurrentValue = $this->cantidad_movimiento->FormValue;
        $this->cantidad_entre_fechas->CurrentValue = $this->cantidad_entre_fechas->FormValue;
        $this->cantidad_acumulada->CurrentValue = $this->cantidad_acumulada->FormValue;
        $this->cantidad_ajuste->CurrentValue = $this->cantidad_ajuste->FormValue;
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
        $this->_username->setDbValue($row['username']);
        $this->nro_documento->setDbValue($row['nro_documento']);
        $this->id_documento->setDbValue($row['id_documento']);
        $this->tipo_documento->setDbValue($row['tipo_documento']);
        $this->fabricante->setDbValue($row['fabricante']);
        $this->articulo->setDbValue($row['articulo']);
        $this->cantidad_movimiento->setDbValue($row['cantidad_movimiento']);
        $this->cantidad_entre_fechas->setDbValue($row['cantidad_entre_fechas']);
        $this->cantidad_acumulada->setDbValue($row['cantidad_acumulada']);
        $this->cantidad_ajuste->setDbValue($row['cantidad_ajuste']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['id'] = null;
        $row['username'] = null;
        $row['nro_documento'] = null;
        $row['id_documento'] = null;
        $row['tipo_documento'] = null;
        $row['fabricante'] = null;
        $row['articulo'] = null;
        $row['cantidad_movimiento'] = null;
        $row['cantidad_entre_fechas'] = null;
        $row['cantidad_acumulada'] = null;
        $row['cantidad_ajuste'] = null;
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
        if ($this->cantidad_movimiento->FormValue == $this->cantidad_movimiento->CurrentValue && is_numeric(ConvertToFloatString($this->cantidad_movimiento->CurrentValue))) {
            $this->cantidad_movimiento->CurrentValue = ConvertToFloatString($this->cantidad_movimiento->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->cantidad_entre_fechas->FormValue == $this->cantidad_entre_fechas->CurrentValue && is_numeric(ConvertToFloatString($this->cantidad_entre_fechas->CurrentValue))) {
            $this->cantidad_entre_fechas->CurrentValue = ConvertToFloatString($this->cantidad_entre_fechas->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->cantidad_acumulada->FormValue == $this->cantidad_acumulada->CurrentValue && is_numeric(ConvertToFloatString($this->cantidad_acumulada->CurrentValue))) {
            $this->cantidad_acumulada->CurrentValue = ConvertToFloatString($this->cantidad_acumulada->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->cantidad_ajuste->FormValue == $this->cantidad_ajuste->CurrentValue && is_numeric(ConvertToFloatString($this->cantidad_ajuste->CurrentValue))) {
            $this->cantidad_ajuste->CurrentValue = ConvertToFloatString($this->cantidad_ajuste->CurrentValue);
        }

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // id

        // username

        // nro_documento

        // id_documento

        // tipo_documento

        // fabricante

        // articulo

        // cantidad_movimiento

        // cantidad_entre_fechas

        // cantidad_acumulada

        // cantidad_ajuste
        if ($this->RowType == ROWTYPE_VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;
            $this->id->ViewCustomAttributes = "";

            // username
            $this->_username->ViewValue = $this->_username->CurrentValue;
            $this->_username->ViewCustomAttributes = "";

            // nro_documento
            $this->nro_documento->ViewValue = $this->nro_documento->CurrentValue;
            $this->nro_documento->ViewCustomAttributes = "";

            // id_documento
            $this->id_documento->ViewValue = $this->id_documento->CurrentValue;
            $this->id_documento->ViewCustomAttributes = "";

            // tipo_documento
            $this->tipo_documento->ViewValue = $this->tipo_documento->CurrentValue;
            $this->tipo_documento->ViewCustomAttributes = "";

            // fabricante
            $this->fabricante->ViewValue = $this->fabricante->CurrentValue;
            $this->fabricante->ViewCustomAttributes = "";

            // articulo
            $this->articulo->ViewValue = $this->articulo->CurrentValue;
            $this->articulo->ViewCustomAttributes = "";

            // cantidad_movimiento
            $this->cantidad_movimiento->ViewValue = $this->cantidad_movimiento->CurrentValue;
            $this->cantidad_movimiento->ViewValue = FormatNumber($this->cantidad_movimiento->ViewValue, $this->cantidad_movimiento->DefaultDecimalPrecision);
            $this->cantidad_movimiento->ViewCustomAttributes = "";

            // cantidad_entre_fechas
            $this->cantidad_entre_fechas->ViewValue = $this->cantidad_entre_fechas->CurrentValue;
            $this->cantidad_entre_fechas->ViewValue = FormatNumber($this->cantidad_entre_fechas->ViewValue, $this->cantidad_entre_fechas->DefaultDecimalPrecision);
            $this->cantidad_entre_fechas->ViewCustomAttributes = "";

            // cantidad_acumulada
            $this->cantidad_acumulada->ViewValue = $this->cantidad_acumulada->CurrentValue;
            $this->cantidad_acumulada->ViewValue = FormatNumber($this->cantidad_acumulada->ViewValue, $this->cantidad_acumulada->DefaultDecimalPrecision);
            $this->cantidad_acumulada->ViewCustomAttributes = "";

            // cantidad_ajuste
            $this->cantidad_ajuste->ViewValue = $this->cantidad_ajuste->CurrentValue;
            $this->cantidad_ajuste->ViewValue = FormatNumber($this->cantidad_ajuste->ViewValue, $this->cantidad_ajuste->DefaultDecimalPrecision);
            $this->cantidad_ajuste->ViewCustomAttributes = "";

            // id
            $this->id->LinkCustomAttributes = "";
            $this->id->HrefValue = "";
            $this->id->TooltipValue = "";

            // username
            $this->_username->LinkCustomAttributes = "";
            $this->_username->HrefValue = "";
            $this->_username->TooltipValue = "";

            // nro_documento
            $this->nro_documento->LinkCustomAttributes = "";
            $this->nro_documento->HrefValue = "";
            $this->nro_documento->TooltipValue = "";

            // id_documento
            $this->id_documento->LinkCustomAttributes = "";
            $this->id_documento->HrefValue = "";
            $this->id_documento->TooltipValue = "";

            // tipo_documento
            $this->tipo_documento->LinkCustomAttributes = "";
            $this->tipo_documento->HrefValue = "";
            $this->tipo_documento->TooltipValue = "";

            // fabricante
            $this->fabricante->LinkCustomAttributes = "";
            $this->fabricante->HrefValue = "";
            $this->fabricante->TooltipValue = "";

            // articulo
            $this->articulo->LinkCustomAttributes = "";
            $this->articulo->HrefValue = "";
            $this->articulo->TooltipValue = "";

            // cantidad_movimiento
            $this->cantidad_movimiento->LinkCustomAttributes = "";
            $this->cantidad_movimiento->HrefValue = "";
            $this->cantidad_movimiento->TooltipValue = "";

            // cantidad_entre_fechas
            $this->cantidad_entre_fechas->LinkCustomAttributes = "";
            $this->cantidad_entre_fechas->HrefValue = "";
            $this->cantidad_entre_fechas->TooltipValue = "";

            // cantidad_acumulada
            $this->cantidad_acumulada->LinkCustomAttributes = "";
            $this->cantidad_acumulada->HrefValue = "";
            $this->cantidad_acumulada->TooltipValue = "";

            // cantidad_ajuste
            $this->cantidad_ajuste->LinkCustomAttributes = "";
            $this->cantidad_ajuste->HrefValue = "";
            $this->cantidad_ajuste->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // id
            $this->id->EditAttrs["class"] = "form-control";
            $this->id->EditCustomAttributes = "";
            $this->id->EditValue = $this->id->CurrentValue;
            $this->id->ViewCustomAttributes = "";

            // username
            $this->_username->EditAttrs["class"] = "form-control";
            $this->_username->EditCustomAttributes = "";
            if (!$this->_username->Raw) {
                $this->_username->CurrentValue = HtmlDecode($this->_username->CurrentValue);
            }
            $this->_username->EditValue = HtmlEncode($this->_username->CurrentValue);
            $this->_username->PlaceHolder = RemoveHtml($this->_username->caption());

            // nro_documento
            $this->nro_documento->EditAttrs["class"] = "form-control";
            $this->nro_documento->EditCustomAttributes = "";
            if (!$this->nro_documento->Raw) {
                $this->nro_documento->CurrentValue = HtmlDecode($this->nro_documento->CurrentValue);
            }
            $this->nro_documento->EditValue = HtmlEncode($this->nro_documento->CurrentValue);
            $this->nro_documento->PlaceHolder = RemoveHtml($this->nro_documento->caption());

            // id_documento
            $this->id_documento->EditAttrs["class"] = "form-control";
            $this->id_documento->EditCustomAttributes = "";
            $this->id_documento->EditValue = HtmlEncode($this->id_documento->CurrentValue);
            $this->id_documento->PlaceHolder = RemoveHtml($this->id_documento->caption());

            // tipo_documento
            $this->tipo_documento->EditAttrs["class"] = "form-control";
            $this->tipo_documento->EditCustomAttributes = "";
            if (!$this->tipo_documento->Raw) {
                $this->tipo_documento->CurrentValue = HtmlDecode($this->tipo_documento->CurrentValue);
            }
            $this->tipo_documento->EditValue = HtmlEncode($this->tipo_documento->CurrentValue);
            $this->tipo_documento->PlaceHolder = RemoveHtml($this->tipo_documento->caption());

            // fabricante
            $this->fabricante->EditAttrs["class"] = "form-control";
            $this->fabricante->EditCustomAttributes = "";
            $this->fabricante->EditValue = HtmlEncode($this->fabricante->CurrentValue);
            $this->fabricante->PlaceHolder = RemoveHtml($this->fabricante->caption());

            // articulo
            $this->articulo->EditAttrs["class"] = "form-control";
            $this->articulo->EditCustomAttributes = "";
            $this->articulo->EditValue = HtmlEncode($this->articulo->CurrentValue);
            $this->articulo->PlaceHolder = RemoveHtml($this->articulo->caption());

            // cantidad_movimiento
            $this->cantidad_movimiento->EditAttrs["class"] = "form-control";
            $this->cantidad_movimiento->EditCustomAttributes = "";
            $this->cantidad_movimiento->EditValue = HtmlEncode($this->cantidad_movimiento->CurrentValue);
            $this->cantidad_movimiento->PlaceHolder = RemoveHtml($this->cantidad_movimiento->caption());
            if (strval($this->cantidad_movimiento->EditValue) != "" && is_numeric($this->cantidad_movimiento->EditValue)) {
                $this->cantidad_movimiento->EditValue = FormatNumber($this->cantidad_movimiento->EditValue, -2, -1, -2, 0);
            }

            // cantidad_entre_fechas
            $this->cantidad_entre_fechas->EditAttrs["class"] = "form-control";
            $this->cantidad_entre_fechas->EditCustomAttributes = "";
            $this->cantidad_entre_fechas->EditValue = HtmlEncode($this->cantidad_entre_fechas->CurrentValue);
            $this->cantidad_entre_fechas->PlaceHolder = RemoveHtml($this->cantidad_entre_fechas->caption());
            if (strval($this->cantidad_entre_fechas->EditValue) != "" && is_numeric($this->cantidad_entre_fechas->EditValue)) {
                $this->cantidad_entre_fechas->EditValue = FormatNumber($this->cantidad_entre_fechas->EditValue, -2, -1, -2, 0);
            }

            // cantidad_acumulada
            $this->cantidad_acumulada->EditAttrs["class"] = "form-control";
            $this->cantidad_acumulada->EditCustomAttributes = "";
            $this->cantidad_acumulada->EditValue = HtmlEncode($this->cantidad_acumulada->CurrentValue);
            $this->cantidad_acumulada->PlaceHolder = RemoveHtml($this->cantidad_acumulada->caption());
            if (strval($this->cantidad_acumulada->EditValue) != "" && is_numeric($this->cantidad_acumulada->EditValue)) {
                $this->cantidad_acumulada->EditValue = FormatNumber($this->cantidad_acumulada->EditValue, -2, -1, -2, 0);
            }

            // cantidad_ajuste
            $this->cantidad_ajuste->EditAttrs["class"] = "form-control";
            $this->cantidad_ajuste->EditCustomAttributes = "";
            $this->cantidad_ajuste->EditValue = HtmlEncode($this->cantidad_ajuste->CurrentValue);
            $this->cantidad_ajuste->PlaceHolder = RemoveHtml($this->cantidad_ajuste->caption());
            if (strval($this->cantidad_ajuste->EditValue) != "" && is_numeric($this->cantidad_ajuste->EditValue)) {
                $this->cantidad_ajuste->EditValue = FormatNumber($this->cantidad_ajuste->EditValue, -2, -1, -2, 0);
            }

            // Edit refer script

            // id
            $this->id->LinkCustomAttributes = "";
            $this->id->HrefValue = "";

            // username
            $this->_username->LinkCustomAttributes = "";
            $this->_username->HrefValue = "";

            // nro_documento
            $this->nro_documento->LinkCustomAttributes = "";
            $this->nro_documento->HrefValue = "";

            // id_documento
            $this->id_documento->LinkCustomAttributes = "";
            $this->id_documento->HrefValue = "";

            // tipo_documento
            $this->tipo_documento->LinkCustomAttributes = "";
            $this->tipo_documento->HrefValue = "";

            // fabricante
            $this->fabricante->LinkCustomAttributes = "";
            $this->fabricante->HrefValue = "";

            // articulo
            $this->articulo->LinkCustomAttributes = "";
            $this->articulo->HrefValue = "";

            // cantidad_movimiento
            $this->cantidad_movimiento->LinkCustomAttributes = "";
            $this->cantidad_movimiento->HrefValue = "";

            // cantidad_entre_fechas
            $this->cantidad_entre_fechas->LinkCustomAttributes = "";
            $this->cantidad_entre_fechas->HrefValue = "";

            // cantidad_acumulada
            $this->cantidad_acumulada->LinkCustomAttributes = "";
            $this->cantidad_acumulada->HrefValue = "";

            // cantidad_ajuste
            $this->cantidad_ajuste->LinkCustomAttributes = "";
            $this->cantidad_ajuste->HrefValue = "";
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
        if ($this->id->Required) {
            if (!$this->id->IsDetailKey && EmptyValue($this->id->FormValue)) {
                $this->id->addErrorMessage(str_replace("%s", $this->id->caption(), $this->id->RequiredErrorMessage));
            }
        }
        if ($this->_username->Required) {
            if (!$this->_username->IsDetailKey && EmptyValue($this->_username->FormValue)) {
                $this->_username->addErrorMessage(str_replace("%s", $this->_username->caption(), $this->_username->RequiredErrorMessage));
            }
        }
        if ($this->nro_documento->Required) {
            if (!$this->nro_documento->IsDetailKey && EmptyValue($this->nro_documento->FormValue)) {
                $this->nro_documento->addErrorMessage(str_replace("%s", $this->nro_documento->caption(), $this->nro_documento->RequiredErrorMessage));
            }
        }
        if ($this->id_documento->Required) {
            if (!$this->id_documento->IsDetailKey && EmptyValue($this->id_documento->FormValue)) {
                $this->id_documento->addErrorMessage(str_replace("%s", $this->id_documento->caption(), $this->id_documento->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->id_documento->FormValue)) {
            $this->id_documento->addErrorMessage($this->id_documento->getErrorMessage(false));
        }
        if ($this->tipo_documento->Required) {
            if (!$this->tipo_documento->IsDetailKey && EmptyValue($this->tipo_documento->FormValue)) {
                $this->tipo_documento->addErrorMessage(str_replace("%s", $this->tipo_documento->caption(), $this->tipo_documento->RequiredErrorMessage));
            }
        }
        if ($this->fabricante->Required) {
            if (!$this->fabricante->IsDetailKey && EmptyValue($this->fabricante->FormValue)) {
                $this->fabricante->addErrorMessage(str_replace("%s", $this->fabricante->caption(), $this->fabricante->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->fabricante->FormValue)) {
            $this->fabricante->addErrorMessage($this->fabricante->getErrorMessage(false));
        }
        if ($this->articulo->Required) {
            if (!$this->articulo->IsDetailKey && EmptyValue($this->articulo->FormValue)) {
                $this->articulo->addErrorMessage(str_replace("%s", $this->articulo->caption(), $this->articulo->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->articulo->FormValue)) {
            $this->articulo->addErrorMessage($this->articulo->getErrorMessage(false));
        }
        if ($this->cantidad_movimiento->Required) {
            if (!$this->cantidad_movimiento->IsDetailKey && EmptyValue($this->cantidad_movimiento->FormValue)) {
                $this->cantidad_movimiento->addErrorMessage(str_replace("%s", $this->cantidad_movimiento->caption(), $this->cantidad_movimiento->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->cantidad_movimiento->FormValue)) {
            $this->cantidad_movimiento->addErrorMessage($this->cantidad_movimiento->getErrorMessage(false));
        }
        if ($this->cantidad_entre_fechas->Required) {
            if (!$this->cantidad_entre_fechas->IsDetailKey && EmptyValue($this->cantidad_entre_fechas->FormValue)) {
                $this->cantidad_entre_fechas->addErrorMessage(str_replace("%s", $this->cantidad_entre_fechas->caption(), $this->cantidad_entre_fechas->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->cantidad_entre_fechas->FormValue)) {
            $this->cantidad_entre_fechas->addErrorMessage($this->cantidad_entre_fechas->getErrorMessage(false));
        }
        if ($this->cantidad_acumulada->Required) {
            if (!$this->cantidad_acumulada->IsDetailKey && EmptyValue($this->cantidad_acumulada->FormValue)) {
                $this->cantidad_acumulada->addErrorMessage(str_replace("%s", $this->cantidad_acumulada->caption(), $this->cantidad_acumulada->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->cantidad_acumulada->FormValue)) {
            $this->cantidad_acumulada->addErrorMessage($this->cantidad_acumulada->getErrorMessage(false));
        }
        if ($this->cantidad_ajuste->Required) {
            if (!$this->cantidad_ajuste->IsDetailKey && EmptyValue($this->cantidad_ajuste->FormValue)) {
                $this->cantidad_ajuste->addErrorMessage(str_replace("%s", $this->cantidad_ajuste->caption(), $this->cantidad_ajuste->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->cantidad_ajuste->FormValue)) {
            $this->cantidad_ajuste->addErrorMessage($this->cantidad_ajuste->getErrorMessage(false));
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

            // username
            $this->_username->setDbValueDef($rsnew, $this->_username->CurrentValue, null, $this->_username->ReadOnly);

            // nro_documento
            $this->nro_documento->setDbValueDef($rsnew, $this->nro_documento->CurrentValue, null, $this->nro_documento->ReadOnly);

            // id_documento
            $this->id_documento->setDbValueDef($rsnew, $this->id_documento->CurrentValue, null, $this->id_documento->ReadOnly);

            // tipo_documento
            $this->tipo_documento->setDbValueDef($rsnew, $this->tipo_documento->CurrentValue, null, $this->tipo_documento->ReadOnly);

            // fabricante
            $this->fabricante->setDbValueDef($rsnew, $this->fabricante->CurrentValue, null, $this->fabricante->ReadOnly);

            // articulo
            $this->articulo->setDbValueDef($rsnew, $this->articulo->CurrentValue, null, $this->articulo->ReadOnly);

            // cantidad_movimiento
            $this->cantidad_movimiento->setDbValueDef($rsnew, $this->cantidad_movimiento->CurrentValue, null, $this->cantidad_movimiento->ReadOnly);

            // cantidad_entre_fechas
            $this->cantidad_entre_fechas->setDbValueDef($rsnew, $this->cantidad_entre_fechas->CurrentValue, null, $this->cantidad_entre_fechas->ReadOnly);

            // cantidad_acumulada
            $this->cantidad_acumulada->setDbValueDef($rsnew, $this->cantidad_acumulada->CurrentValue, null, $this->cantidad_acumulada->ReadOnly);

            // cantidad_ajuste
            $this->cantidad_ajuste->setDbValueDef($rsnew, $this->cantidad_ajuste->CurrentValue, null, $this->cantidad_ajuste->ReadOnly);

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

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("Home");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("TempConsignacionList"), "", $this->TableVar, true);
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
    public function pageRender()
    {
        //Log("Page Render");
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
