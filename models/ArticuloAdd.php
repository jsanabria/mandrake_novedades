<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class ArticuloAdd extends Articulo
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'articulo';

    // Page object name
    public $PageObjName = "ArticuloAdd";

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

        // Table object (articulo)
        if (!isset($GLOBALS["articulo"]) || get_class($GLOBALS["articulo"]) == PROJECT_NAMESPACE . "articulo") {
            $GLOBALS["articulo"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'articulo');
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
                $doc = new $class(Container("articulo"));
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
                    if ($pageName == "ArticuloView") {
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
    public $FormClassName = "ew-horizontal ew-form ew-add-form";
    public $IsModal = false;
    public $IsMobileOrModal = false;
    public $DbMasterFilter = "";
    public $DbDetailFilter = "";
    public $StartRecord;
    public $Priv = 0;
    public $OldRecordset;
    public $CopyRecord;

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
        $this->codigo_ims->setVisibility();
        $this->codigo->setVisibility();
        $this->nombre_comercial->Visible = false;
        $this->principio_activo->setVisibility();
        $this->presentacion->Visible = false;
        $this->fabricante->setVisibility();
        $this->codigo_de_barra->setVisibility();
        $this->categoria->Visible = false;
        $this->lista_pedido->Visible = false;
        $this->unidad_medida_defecto->setVisibility();
        $this->cantidad_por_unidad_medida->setVisibility();
        $this->foto->setVisibility();
        $this->cantidad_minima->setVisibility();
        $this->cantidad_maxima->setVisibility();
        $this->cantidad_en_mano->Visible = false;
        $this->cantidad_en_pedido->Visible = false;
        $this->cantidad_en_transito->Visible = false;
        $this->ultimo_costo->Visible = false;
        $this->descuento->Visible = false;
        $this->precio->Visible = false;
        $this->precio2->Visible = false;
        $this->alicuota->setVisibility();
        $this->articulo_inventario->setVisibility();
        $this->activo->setVisibility();
        $this->puntos_ventas->setVisibility();
        $this->puntos_premio->setVisibility();
        $this->sincroniza->setVisibility();
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
        $this->setupLookupOptions($this->fabricante);
        $this->setupLookupOptions($this->categoria);
        $this->setupLookupOptions($this->lista_pedido);
        $this->setupLookupOptions($this->unidad_medida_defecto);
        $this->setupLookupOptions($this->cantidad_por_unidad_medida);
        $this->setupLookupOptions($this->alicuota);

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        $this->FormClassName = "ew-form ew-add-form ew-horizontal";
        $postBack = false;

        // Set up current action
        if (IsApi()) {
            $this->CurrentAction = "insert"; // Add record directly
            $postBack = true;
        } elseif (Post("action") !== null) {
            $this->CurrentAction = Post("action"); // Get form action
            $this->setKey(Post($this->OldKeyName));
            $postBack = true;
        } else {
            // Load key values from QueryString
            if (($keyValue = Get("id") ?? Route("id")) !== null) {
                $this->id->setQueryStringValue($keyValue);
            }
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
            $this->CopyRecord = !EmptyValue($this->OldKey);
            if ($this->CopyRecord) {
                $this->CurrentAction = "copy"; // Copy record
            } else {
                $this->CurrentAction = "show"; // Display blank record
            }
        }

        // Load old record / default values
        $loaded = $this->loadOldRecord();

        // Load form values
        if ($postBack) {
            $this->loadFormValues(); // Load form values
        }

        // Set up detail parameters
        $this->setupDetailParms();

        // Validate form if post back
        if ($postBack) {
            if (!$this->validateForm()) {
                $this->EventCancelled = true; // Event cancelled
                $this->restoreFormValues(); // Restore form values
                if (IsApi()) {
                    $this->terminate();
                    return;
                } else {
                    $this->CurrentAction = "show"; // Form error, reset action
                }
            }
        }

        // Perform current action
        switch ($this->CurrentAction) {
            case "copy": // Copy an existing record
                if (!$loaded) { // Record not loaded
                    if ($this->getFailureMessage() == "") {
                        $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                    }
                    $this->terminate("ArticuloList"); // No matching record, return to list
                    return;
                }

                // Set up detail parameters
                $this->setupDetailParms();
                break;
            case "insert": // Add new record
                $this->SendEmail = true; // Send email on add success
                if ($this->addRow($this->OldRecordset)) { // Add successful
                    if ($this->getSuccessMessage() == "" && Post("addopt") != "1") { // Skip success message for addopt (done in JavaScript)
                        $this->setSuccessMessage($Language->phrase("AddSuccess")); // Set up success message
                    }
                    if ($this->getCurrentDetailTable() != "") { // Master/detail add
                        $returnUrl = $this->getDetailUrl();
                    } else {
                        $returnUrl = $this->getReturnUrl();
                    }
                    if (GetPageName($returnUrl) == "ArticuloList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "ArticuloView") {
                        $returnUrl = $this->getViewUrl(); // View page, return to View page with keyurl directly
                    }
                    if (IsApi()) { // Return to caller
                        $this->terminate(true);
                        return;
                    } else {
                        $this->terminate($returnUrl);
                        return;
                    }
                } elseif (IsApi()) { // API request, return
                    $this->terminate();
                    return;
                } else {
                    $this->EventCancelled = true; // Event cancelled
                    $this->restoreFormValues(); // Add failed, restore form values

                    // Set up detail parameters
                    $this->setupDetailParms();
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render row based on row type
        $this->RowType = ROWTYPE_ADD; // Render add type

        // Render row
        $this->resetAttributes();
        $this->renderRow();

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
        $this->foto->Upload->Index = $CurrentForm->Index;
        $this->foto->Upload->uploadFile();
        $this->foto->CurrentValue = $this->foto->Upload->FileName;
    }

    // Load default values
    protected function loadDefaultValues()
    {
        $this->id->CurrentValue = null;
        $this->id->OldValue = $this->id->CurrentValue;
        $this->codigo_ims->CurrentValue = null;
        $this->codigo_ims->OldValue = $this->codigo_ims->CurrentValue;
        $this->codigo->CurrentValue = null;
        $this->codigo->OldValue = $this->codigo->CurrentValue;
        $this->nombre_comercial->CurrentValue = null;
        $this->nombre_comercial->OldValue = $this->nombre_comercial->CurrentValue;
        $this->principio_activo->CurrentValue = null;
        $this->principio_activo->OldValue = $this->principio_activo->CurrentValue;
        $this->presentacion->CurrentValue = null;
        $this->presentacion->OldValue = $this->presentacion->CurrentValue;
        $this->fabricante->CurrentValue = null;
        $this->fabricante->OldValue = $this->fabricante->CurrentValue;
        $this->codigo_de_barra->CurrentValue = null;
        $this->codigo_de_barra->OldValue = $this->codigo_de_barra->CurrentValue;
        $this->categoria->CurrentValue = null;
        $this->categoria->OldValue = $this->categoria->CurrentValue;
        $this->lista_pedido->CurrentValue = null;
        $this->lista_pedido->OldValue = $this->lista_pedido->CurrentValue;
        $this->unidad_medida_defecto->CurrentValue = null;
        $this->unidad_medida_defecto->OldValue = $this->unidad_medida_defecto->CurrentValue;
        $this->cantidad_por_unidad_medida->CurrentValue = null;
        $this->cantidad_por_unidad_medida->OldValue = $this->cantidad_por_unidad_medida->CurrentValue;
        $this->foto->Upload->DbValue = null;
        $this->foto->OldValue = $this->foto->Upload->DbValue;
        $this->foto->CurrentValue = null; // Clear file related field
        $this->cantidad_minima->CurrentValue = 0;
        $this->cantidad_maxima->CurrentValue = 0;
        $this->cantidad_en_mano->CurrentValue = 0;
        $this->cantidad_en_pedido->CurrentValue = 0;
        $this->cantidad_en_transito->CurrentValue = 0;
        $this->ultimo_costo->CurrentValue = 0.00;
        $this->descuento->CurrentValue = 0.00;
        $this->precio->CurrentValue = 0.00;
        $this->precio2->CurrentValue = 0.00;
        $this->alicuota->CurrentValue = "0";
        $this->articulo_inventario->CurrentValue = "S";
        $this->activo->CurrentValue = null;
        $this->activo->OldValue = $this->activo->CurrentValue;
        $this->puntos_ventas->CurrentValue = 0;
        $this->puntos_premio->CurrentValue = 0;
        $this->sincroniza->CurrentValue = "S";
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;

        // Check field name 'codigo_ims' first before field var 'x_codigo_ims'
        $val = $CurrentForm->hasValue("codigo_ims") ? $CurrentForm->getValue("codigo_ims") : $CurrentForm->getValue("x_codigo_ims");
        if (!$this->codigo_ims->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->codigo_ims->Visible = false; // Disable update for API request
            } else {
                $this->codigo_ims->setFormValue($val);
            }
        }

        // Check field name 'codigo' first before field var 'x_codigo'
        $val = $CurrentForm->hasValue("codigo") ? $CurrentForm->getValue("codigo") : $CurrentForm->getValue("x_codigo");
        if (!$this->codigo->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->codigo->Visible = false; // Disable update for API request
            } else {
                $this->codigo->setFormValue($val);
            }
        }

        // Check field name 'principio_activo' first before field var 'x_principio_activo'
        $val = $CurrentForm->hasValue("principio_activo") ? $CurrentForm->getValue("principio_activo") : $CurrentForm->getValue("x_principio_activo");
        if (!$this->principio_activo->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->principio_activo->Visible = false; // Disable update for API request
            } else {
                $this->principio_activo->setFormValue($val);
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

        // Check field name 'codigo_de_barra' first before field var 'x_codigo_de_barra'
        $val = $CurrentForm->hasValue("codigo_de_barra") ? $CurrentForm->getValue("codigo_de_barra") : $CurrentForm->getValue("x_codigo_de_barra");
        if (!$this->codigo_de_barra->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->codigo_de_barra->Visible = false; // Disable update for API request
            } else {
                $this->codigo_de_barra->setFormValue($val);
            }
        }

        // Check field name 'unidad_medida_defecto' first before field var 'x_unidad_medida_defecto'
        $val = $CurrentForm->hasValue("unidad_medida_defecto") ? $CurrentForm->getValue("unidad_medida_defecto") : $CurrentForm->getValue("x_unidad_medida_defecto");
        if (!$this->unidad_medida_defecto->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->unidad_medida_defecto->Visible = false; // Disable update for API request
            } else {
                $this->unidad_medida_defecto->setFormValue($val);
            }
        }

        // Check field name 'cantidad_por_unidad_medida' first before field var 'x_cantidad_por_unidad_medida'
        $val = $CurrentForm->hasValue("cantidad_por_unidad_medida") ? $CurrentForm->getValue("cantidad_por_unidad_medida") : $CurrentForm->getValue("x_cantidad_por_unidad_medida");
        if (!$this->cantidad_por_unidad_medida->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cantidad_por_unidad_medida->Visible = false; // Disable update for API request
            } else {
                $this->cantidad_por_unidad_medida->setFormValue($val);
            }
        }

        // Check field name 'cantidad_minima' first before field var 'x_cantidad_minima'
        $val = $CurrentForm->hasValue("cantidad_minima") ? $CurrentForm->getValue("cantidad_minima") : $CurrentForm->getValue("x_cantidad_minima");
        if (!$this->cantidad_minima->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cantidad_minima->Visible = false; // Disable update for API request
            } else {
                $this->cantidad_minima->setFormValue($val);
            }
        }

        // Check field name 'cantidad_maxima' first before field var 'x_cantidad_maxima'
        $val = $CurrentForm->hasValue("cantidad_maxima") ? $CurrentForm->getValue("cantidad_maxima") : $CurrentForm->getValue("x_cantidad_maxima");
        if (!$this->cantidad_maxima->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cantidad_maxima->Visible = false; // Disable update for API request
            } else {
                $this->cantidad_maxima->setFormValue($val);
            }
        }

        // Check field name 'alicuota' first before field var 'x_alicuota'
        $val = $CurrentForm->hasValue("alicuota") ? $CurrentForm->getValue("alicuota") : $CurrentForm->getValue("x_alicuota");
        if (!$this->alicuota->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->alicuota->Visible = false; // Disable update for API request
            } else {
                $this->alicuota->setFormValue($val);
            }
        }

        // Check field name 'articulo_inventario' first before field var 'x_articulo_inventario'
        $val = $CurrentForm->hasValue("articulo_inventario") ? $CurrentForm->getValue("articulo_inventario") : $CurrentForm->getValue("x_articulo_inventario");
        if (!$this->articulo_inventario->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->articulo_inventario->Visible = false; // Disable update for API request
            } else {
                $this->articulo_inventario->setFormValue($val);
            }
        }

        // Check field name 'activo' first before field var 'x_activo'
        $val = $CurrentForm->hasValue("activo") ? $CurrentForm->getValue("activo") : $CurrentForm->getValue("x_activo");
        if (!$this->activo->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->activo->Visible = false; // Disable update for API request
            } else {
                $this->activo->setFormValue($val);
            }
        }

        // Check field name 'puntos_ventas' first before field var 'x_puntos_ventas'
        $val = $CurrentForm->hasValue("puntos_ventas") ? $CurrentForm->getValue("puntos_ventas") : $CurrentForm->getValue("x_puntos_ventas");
        if (!$this->puntos_ventas->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->puntos_ventas->Visible = false; // Disable update for API request
            } else {
                $this->puntos_ventas->setFormValue($val);
            }
        }

        // Check field name 'puntos_premio' first before field var 'x_puntos_premio'
        $val = $CurrentForm->hasValue("puntos_premio") ? $CurrentForm->getValue("puntos_premio") : $CurrentForm->getValue("x_puntos_premio");
        if (!$this->puntos_premio->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->puntos_premio->Visible = false; // Disable update for API request
            } else {
                $this->puntos_premio->setFormValue($val);
            }
        }

        // Check field name 'sincroniza' first before field var 'x_sincroniza'
        $val = $CurrentForm->hasValue("sincroniza") ? $CurrentForm->getValue("sincroniza") : $CurrentForm->getValue("x_sincroniza");
        if (!$this->sincroniza->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->sincroniza->Visible = false; // Disable update for API request
            } else {
                $this->sincroniza->setFormValue($val);
            }
        }

        // Check field name 'id' first before field var 'x_id'
        $val = $CurrentForm->hasValue("id") ? $CurrentForm->getValue("id") : $CurrentForm->getValue("x_id");
        $this->getUploadFiles(); // Get upload files
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->codigo_ims->CurrentValue = $this->codigo_ims->FormValue;
        $this->codigo->CurrentValue = $this->codigo->FormValue;
        $this->principio_activo->CurrentValue = $this->principio_activo->FormValue;
        $this->fabricante->CurrentValue = $this->fabricante->FormValue;
        $this->codigo_de_barra->CurrentValue = $this->codigo_de_barra->FormValue;
        $this->unidad_medida_defecto->CurrentValue = $this->unidad_medida_defecto->FormValue;
        $this->cantidad_por_unidad_medida->CurrentValue = $this->cantidad_por_unidad_medida->FormValue;
        $this->cantidad_minima->CurrentValue = $this->cantidad_minima->FormValue;
        $this->cantidad_maxima->CurrentValue = $this->cantidad_maxima->FormValue;
        $this->alicuota->CurrentValue = $this->alicuota->FormValue;
        $this->articulo_inventario->CurrentValue = $this->articulo_inventario->FormValue;
        $this->activo->CurrentValue = $this->activo->FormValue;
        $this->puntos_ventas->CurrentValue = $this->puntos_ventas->FormValue;
        $this->puntos_premio->CurrentValue = $this->puntos_premio->FormValue;
        $this->sincroniza->CurrentValue = $this->sincroniza->FormValue;
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
        $this->codigo_ims->setDbValue($row['codigo_ims']);
        $this->codigo->setDbValue($row['codigo']);
        $this->nombre_comercial->setDbValue($row['nombre_comercial']);
        $this->principio_activo->setDbValue($row['principio_activo']);
        $this->presentacion->setDbValue($row['presentacion']);
        $this->fabricante->setDbValue($row['fabricante']);
        $this->codigo_de_barra->setDbValue($row['codigo_de_barra']);
        $this->categoria->setDbValue($row['categoria']);
        $this->lista_pedido->setDbValue($row['lista_pedido']);
        $this->unidad_medida_defecto->setDbValue($row['unidad_medida_defecto']);
        $this->cantidad_por_unidad_medida->setDbValue($row['cantidad_por_unidad_medida']);
        $this->foto->Upload->DbValue = $row['foto'];
        $this->foto->setDbValue($this->foto->Upload->DbValue);
        $this->cantidad_minima->setDbValue($row['cantidad_minima']);
        $this->cantidad_maxima->setDbValue($row['cantidad_maxima']);
        $this->cantidad_en_mano->setDbValue($row['cantidad_en_mano']);
        $this->cantidad_en_pedido->setDbValue($row['cantidad_en_pedido']);
        $this->cantidad_en_transito->setDbValue($row['cantidad_en_transito']);
        $this->ultimo_costo->setDbValue($row['ultimo_costo']);
        $this->descuento->setDbValue($row['descuento']);
        $this->precio->setDbValue($row['precio']);
        $this->precio2->setDbValue($row['precio2']);
        $this->alicuota->setDbValue($row['alicuota']);
        $this->articulo_inventario->setDbValue($row['articulo_inventario']);
        $this->activo->setDbValue($row['activo']);
        $this->puntos_ventas->setDbValue($row['puntos_ventas']);
        $this->puntos_premio->setDbValue($row['puntos_premio']);
        $this->sincroniza->setDbValue($row['sincroniza']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $this->loadDefaultValues();
        $row = [];
        $row['id'] = $this->id->CurrentValue;
        $row['codigo_ims'] = $this->codigo_ims->CurrentValue;
        $row['codigo'] = $this->codigo->CurrentValue;
        $row['nombre_comercial'] = $this->nombre_comercial->CurrentValue;
        $row['principio_activo'] = $this->principio_activo->CurrentValue;
        $row['presentacion'] = $this->presentacion->CurrentValue;
        $row['fabricante'] = $this->fabricante->CurrentValue;
        $row['codigo_de_barra'] = $this->codigo_de_barra->CurrentValue;
        $row['categoria'] = $this->categoria->CurrentValue;
        $row['lista_pedido'] = $this->lista_pedido->CurrentValue;
        $row['unidad_medida_defecto'] = $this->unidad_medida_defecto->CurrentValue;
        $row['cantidad_por_unidad_medida'] = $this->cantidad_por_unidad_medida->CurrentValue;
        $row['foto'] = $this->foto->Upload->DbValue;
        $row['cantidad_minima'] = $this->cantidad_minima->CurrentValue;
        $row['cantidad_maxima'] = $this->cantidad_maxima->CurrentValue;
        $row['cantidad_en_mano'] = $this->cantidad_en_mano->CurrentValue;
        $row['cantidad_en_pedido'] = $this->cantidad_en_pedido->CurrentValue;
        $row['cantidad_en_transito'] = $this->cantidad_en_transito->CurrentValue;
        $row['ultimo_costo'] = $this->ultimo_costo->CurrentValue;
        $row['descuento'] = $this->descuento->CurrentValue;
        $row['precio'] = $this->precio->CurrentValue;
        $row['precio2'] = $this->precio2->CurrentValue;
        $row['alicuota'] = $this->alicuota->CurrentValue;
        $row['articulo_inventario'] = $this->articulo_inventario->CurrentValue;
        $row['activo'] = $this->activo->CurrentValue;
        $row['puntos_ventas'] = $this->puntos_ventas->CurrentValue;
        $row['puntos_premio'] = $this->puntos_premio->CurrentValue;
        $row['sincroniza'] = $this->sincroniza->CurrentValue;
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
        if ($this->cantidad_minima->FormValue == $this->cantidad_minima->CurrentValue && is_numeric(ConvertToFloatString($this->cantidad_minima->CurrentValue))) {
            $this->cantidad_minima->CurrentValue = ConvertToFloatString($this->cantidad_minima->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->cantidad_maxima->FormValue == $this->cantidad_maxima->CurrentValue && is_numeric(ConvertToFloatString($this->cantidad_maxima->CurrentValue))) {
            $this->cantidad_maxima->CurrentValue = ConvertToFloatString($this->cantidad_maxima->CurrentValue);
        }

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // id

        // codigo_ims

        // codigo

        // nombre_comercial

        // principio_activo

        // presentacion

        // fabricante

        // codigo_de_barra

        // categoria

        // lista_pedido

        // unidad_medida_defecto

        // cantidad_por_unidad_medida

        // foto

        // cantidad_minima

        // cantidad_maxima

        // cantidad_en_mano

        // cantidad_en_pedido

        // cantidad_en_transito

        // ultimo_costo

        // descuento

        // precio

        // precio2

        // alicuota

        // articulo_inventario

        // activo

        // puntos_ventas

        // puntos_premio

        // sincroniza
        if ($this->RowType == ROWTYPE_VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;
            $this->id->ViewCustomAttributes = "";

            // codigo_ims
            $this->codigo_ims->ViewValue = $this->codigo_ims->CurrentValue;
            $this->codigo_ims->CssClass = "font-weight-bold font-italic";
            $this->codigo_ims->ViewCustomAttributes = "";

            // codigo
            $this->codigo->ViewValue = $this->codigo->CurrentValue;
            $this->codigo->ViewCustomAttributes = "";

            // nombre_comercial
            $this->nombre_comercial->ViewValue = $this->nombre_comercial->CurrentValue;
            $this->nombre_comercial->ViewCustomAttributes = "";

            // principio_activo
            $this->principio_activo->ViewValue = $this->principio_activo->CurrentValue;
            $this->principio_activo->ViewCustomAttributes = "";

            // presentacion
            $this->presentacion->ViewValue = $this->presentacion->CurrentValue;
            $this->presentacion->ViewCustomAttributes = "";

            // fabricante
            $curVal = trim(strval($this->fabricante->CurrentValue));
            if ($curVal != "") {
                $this->fabricante->ViewValue = $this->fabricante->lookupCacheOption($curVal);
                if ($this->fabricante->ViewValue === null) { // Lookup from database
                    $filterWrk = "`Id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->fabricante->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->fabricante->Lookup->renderViewRow($rswrk[0]);
                        $this->fabricante->ViewValue = $this->fabricante->displayValue($arwrk);
                    } else {
                        $this->fabricante->ViewValue = $this->fabricante->CurrentValue;
                    }
                }
            } else {
                $this->fabricante->ViewValue = null;
            }
            $this->fabricante->ViewCustomAttributes = "";

            // codigo_de_barra
            $this->codigo_de_barra->ViewValue = $this->codigo_de_barra->CurrentValue;
            $this->codigo_de_barra->ViewCustomAttributes = "";

            // categoria
            $curVal = trim(strval($this->categoria->CurrentValue));
            if ($curVal != "") {
                $this->categoria->ViewValue = $this->categoria->lookupCacheOption($curVal);
                if ($this->categoria->ViewValue === null) { // Lookup from database
                    $filterWrk = "`campo_codigo`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $lookupFilter = function() {
                        return "`tabla` = 'CATEGORIA'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    $sqlWrk = $this->categoria->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->categoria->Lookup->renderViewRow($rswrk[0]);
                        $this->categoria->ViewValue = $this->categoria->displayValue($arwrk);
                    } else {
                        $this->categoria->ViewValue = $this->categoria->CurrentValue;
                    }
                }
            } else {
                $this->categoria->ViewValue = null;
            }
            $this->categoria->ViewCustomAttributes = "";

            // lista_pedido
            $curVal = trim(strval($this->lista_pedido->CurrentValue));
            if ($curVal != "") {
                $this->lista_pedido->ViewValue = $this->lista_pedido->lookupCacheOption($curVal);
                if ($this->lista_pedido->ViewValue === null) { // Lookup from database
                    $filterWrk = "`campo_codigo`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $lookupFilter = function() {
                        return "`tabla` = 'LISTA_PEDIDO'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    $sqlWrk = $this->lista_pedido->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->lista_pedido->Lookup->renderViewRow($rswrk[0]);
                        $this->lista_pedido->ViewValue = $this->lista_pedido->displayValue($arwrk);
                    } else {
                        $this->lista_pedido->ViewValue = $this->lista_pedido->CurrentValue;
                    }
                }
            } else {
                $this->lista_pedido->ViewValue = null;
            }
            $this->lista_pedido->ViewCustomAttributes = "";

            // unidad_medida_defecto
            $curVal = trim(strval($this->unidad_medida_defecto->CurrentValue));
            if ($curVal != "") {
                $this->unidad_medida_defecto->ViewValue = $this->unidad_medida_defecto->lookupCacheOption($curVal);
                if ($this->unidad_medida_defecto->ViewValue === null) { // Lookup from database
                    $filterWrk = "`codigo`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $lookupFilter = function() {
                        return "`codigo` = 'UDM001'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    $sqlWrk = $this->unidad_medida_defecto->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->unidad_medida_defecto->Lookup->renderViewRow($rswrk[0]);
                        $this->unidad_medida_defecto->ViewValue = $this->unidad_medida_defecto->displayValue($arwrk);
                    } else {
                        $this->unidad_medida_defecto->ViewValue = $this->unidad_medida_defecto->CurrentValue;
                    }
                }
            } else {
                $this->unidad_medida_defecto->ViewValue = null;
            }
            $this->unidad_medida_defecto->ViewCustomAttributes = "";

            // cantidad_por_unidad_medida
            $curVal = trim(strval($this->cantidad_por_unidad_medida->CurrentValue));
            if ($curVal != "") {
                $this->cantidad_por_unidad_medida->ViewValue = $this->cantidad_por_unidad_medida->lookupCacheOption($curVal);
                if ($this->cantidad_por_unidad_medida->ViewValue === null) { // Lookup from database
                    $filterWrk = "`cantidad`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->cantidad_por_unidad_medida->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->cantidad_por_unidad_medida->Lookup->renderViewRow($rswrk[0]);
                        $this->cantidad_por_unidad_medida->ViewValue = $this->cantidad_por_unidad_medida->displayValue($arwrk);
                    } else {
                        $this->cantidad_por_unidad_medida->ViewValue = $this->cantidad_por_unidad_medida->CurrentValue;
                    }
                }
            } else {
                $this->cantidad_por_unidad_medida->ViewValue = null;
            }
            $this->cantidad_por_unidad_medida->ViewCustomAttributes = "";

            // foto
            if (!EmptyValue($this->foto->Upload->DbValue)) {
                $this->foto->ImageWidth = 120;
                $this->foto->ImageHeight = 120;
                $this->foto->ImageAlt = $this->foto->alt();
                $this->foto->ViewValue = $this->foto->Upload->DbValue;
            } else {
                $this->foto->ViewValue = "";
            }
            $this->foto->ViewCustomAttributes = "";

            // cantidad_minima
            $this->cantidad_minima->ViewValue = $this->cantidad_minima->CurrentValue;
            $this->cantidad_minima->ViewValue = FormatNumber($this->cantidad_minima->ViewValue, 2, -1, -1, -1);
            $this->cantidad_minima->ViewCustomAttributes = "";

            // cantidad_maxima
            $this->cantidad_maxima->ViewValue = $this->cantidad_maxima->CurrentValue;
            $this->cantidad_maxima->ViewValue = FormatNumber($this->cantidad_maxima->ViewValue, 2, -1, -1, -1);
            $this->cantidad_maxima->ViewCustomAttributes = "";

            // cantidad_en_mano
            $this->cantidad_en_mano->ViewValue = $this->cantidad_en_mano->CurrentValue;
            $this->cantidad_en_mano->ViewValue = FormatNumber($this->cantidad_en_mano->ViewValue, 2, -1, -1, -1);
            $this->cantidad_en_mano->ViewCustomAttributes = "";

            // cantidad_en_pedido
            $this->cantidad_en_pedido->ViewValue = $this->cantidad_en_pedido->CurrentValue;
            $this->cantidad_en_pedido->ViewValue = FormatNumber($this->cantidad_en_pedido->ViewValue, 2, -1, -1, -1);
            $this->cantidad_en_pedido->ViewCustomAttributes = "";

            // cantidad_en_transito
            $this->cantidad_en_transito->ViewValue = $this->cantidad_en_transito->CurrentValue;
            $this->cantidad_en_transito->ViewValue = FormatNumber($this->cantidad_en_transito->ViewValue, 2, -1, -1, -1);
            $this->cantidad_en_transito->ViewCustomAttributes = "";

            // ultimo_costo
            $this->ultimo_costo->ViewValue = $this->ultimo_costo->CurrentValue;
            $this->ultimo_costo->ViewValue = FormatNumber($this->ultimo_costo->ViewValue, 2, -1, -1, -1);
            $this->ultimo_costo->ViewCustomAttributes = "";

            // descuento
            $this->descuento->ViewValue = $this->descuento->CurrentValue;
            $this->descuento->ViewValue = FormatNumber($this->descuento->ViewValue, 2, -1, -1, -1);
            $this->descuento->ViewCustomAttributes = "";

            // precio
            $this->precio->ViewValue = $this->precio->CurrentValue;
            $this->precio->ViewValue = FormatNumber($this->precio->ViewValue, 2, -1, -1, -1);
            $this->precio->ViewCustomAttributes = "";

            // precio2
            $this->precio2->ViewValue = $this->precio2->CurrentValue;
            $this->precio2->ViewValue = FormatNumber($this->precio2->ViewValue, 2, -2, -2, -2);
            $this->precio2->ViewCustomAttributes = "";

            // alicuota
            $curVal = trim(strval($this->alicuota->CurrentValue));
            if ($curVal != "") {
                $this->alicuota->ViewValue = $this->alicuota->lookupCacheOption($curVal);
                if ($this->alicuota->ViewValue === null) { // Lookup from database
                    $filterWrk = "`codigo`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $lookupFilter = function() {
                        return "`activo` = 'S'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    $sqlWrk = $this->alicuota->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->alicuota->Lookup->renderViewRow($rswrk[0]);
                        $this->alicuota->ViewValue = $this->alicuota->displayValue($arwrk);
                    } else {
                        $this->alicuota->ViewValue = $this->alicuota->CurrentValue;
                    }
                }
            } else {
                $this->alicuota->ViewValue = null;
            }
            $this->alicuota->ViewCustomAttributes = "";

            // articulo_inventario
            if (strval($this->articulo_inventario->CurrentValue) != "") {
                $this->articulo_inventario->ViewValue = $this->articulo_inventario->optionCaption($this->articulo_inventario->CurrentValue);
            } else {
                $this->articulo_inventario->ViewValue = null;
            }
            $this->articulo_inventario->ViewCustomAttributes = "";

            // activo
            if (strval($this->activo->CurrentValue) != "") {
                $this->activo->ViewValue = $this->activo->optionCaption($this->activo->CurrentValue);
            } else {
                $this->activo->ViewValue = null;
            }
            $this->activo->ViewCustomAttributes = "";

            // puntos_ventas
            $this->puntos_ventas->ViewValue = $this->puntos_ventas->CurrentValue;
            $this->puntos_ventas->ViewValue = FormatNumber($this->puntos_ventas->ViewValue, 0, -2, -2, -2);
            $this->puntos_ventas->ViewCustomAttributes = "";

            // puntos_premio
            $this->puntos_premio->ViewValue = $this->puntos_premio->CurrentValue;
            $this->puntos_premio->ViewValue = FormatNumber($this->puntos_premio->ViewValue, 0, -2, -2, -2);
            $this->puntos_premio->ViewCustomAttributes = "";

            // sincroniza
            if (strval($this->sincroniza->CurrentValue) != "") {
                $this->sincroniza->ViewValue = $this->sincroniza->optionCaption($this->sincroniza->CurrentValue);
            } else {
                $this->sincroniza->ViewValue = null;
            }
            $this->sincroniza->ViewCustomAttributes = "";

            // codigo_ims
            $this->codigo_ims->LinkCustomAttributes = "";
            $this->codigo_ims->HrefValue = "";
            $this->codigo_ims->TooltipValue = "";

            // codigo
            $this->codigo->LinkCustomAttributes = "";
            $this->codigo->HrefValue = "";
            $this->codigo->TooltipValue = "";

            // principio_activo
            $this->principio_activo->LinkCustomAttributes = "";
            $this->principio_activo->HrefValue = "";
            $this->principio_activo->TooltipValue = "";

            // fabricante
            $this->fabricante->LinkCustomAttributes = "";
            $this->fabricante->HrefValue = "";
            $this->fabricante->TooltipValue = "";

            // codigo_de_barra
            $this->codigo_de_barra->LinkCustomAttributes = "";
            $this->codigo_de_barra->HrefValue = "";
            $this->codigo_de_barra->TooltipValue = "";

            // unidad_medida_defecto
            $this->unidad_medida_defecto->LinkCustomAttributes = "";
            $this->unidad_medida_defecto->HrefValue = "";
            $this->unidad_medida_defecto->TooltipValue = "";

            // cantidad_por_unidad_medida
            $this->cantidad_por_unidad_medida->LinkCustomAttributes = "";
            $this->cantidad_por_unidad_medida->HrefValue = "";
            $this->cantidad_por_unidad_medida->TooltipValue = "";

            // foto
            $this->foto->LinkCustomAttributes = "";
            if (!EmptyValue($this->foto->Upload->DbValue)) {
                $this->foto->HrefValue = GetFileUploadUrl($this->foto, $this->foto->htmlDecode($this->foto->Upload->DbValue)); // Add prefix/suffix
                $this->foto->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->foto->HrefValue = FullUrl($this->foto->HrefValue, "href");
                }
            } else {
                $this->foto->HrefValue = "";
            }
            $this->foto->ExportHrefValue = $this->foto->UploadPath . $this->foto->Upload->DbValue;
            $this->foto->TooltipValue = "";
            if ($this->foto->UseColorbox) {
                if (EmptyValue($this->foto->TooltipValue)) {
                    $this->foto->LinkAttrs["title"] = $Language->phrase("ViewImageGallery");
                }
                $this->foto->LinkAttrs["data-rel"] = "articulo_x_foto";
                $this->foto->LinkAttrs->appendClass("ew-lightbox");
            }

            // cantidad_minima
            $this->cantidad_minima->LinkCustomAttributes = "";
            $this->cantidad_minima->HrefValue = "";
            $this->cantidad_minima->TooltipValue = "";

            // cantidad_maxima
            $this->cantidad_maxima->LinkCustomAttributes = "";
            $this->cantidad_maxima->HrefValue = "";
            $this->cantidad_maxima->TooltipValue = "";

            // alicuota
            $this->alicuota->LinkCustomAttributes = "";
            $this->alicuota->HrefValue = "";
            $this->alicuota->TooltipValue = "";

            // articulo_inventario
            $this->articulo_inventario->LinkCustomAttributes = "";
            $this->articulo_inventario->HrefValue = "";
            $this->articulo_inventario->TooltipValue = "";

            // activo
            $this->activo->LinkCustomAttributes = "";
            $this->activo->HrefValue = "";
            $this->activo->TooltipValue = "";

            // puntos_ventas
            $this->puntos_ventas->LinkCustomAttributes = "";
            $this->puntos_ventas->HrefValue = "";
            $this->puntos_ventas->TooltipValue = "";

            // puntos_premio
            $this->puntos_premio->LinkCustomAttributes = "";
            $this->puntos_premio->HrefValue = "";
            $this->puntos_premio->TooltipValue = "";

            // sincroniza
            $this->sincroniza->LinkCustomAttributes = "";
            $this->sincroniza->HrefValue = "";
            $this->sincroniza->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // codigo_ims
            $this->codigo_ims->EditAttrs["class"] = "form-control";
            $this->codigo_ims->EditCustomAttributes = "";
            if (!$this->codigo_ims->Raw) {
                $this->codigo_ims->CurrentValue = HtmlDecode($this->codigo_ims->CurrentValue);
            }
            $this->codigo_ims->EditValue = HtmlEncode($this->codigo_ims->CurrentValue);
            $this->codigo_ims->PlaceHolder = RemoveHtml($this->codigo_ims->caption());

            // codigo
            $this->codigo->EditAttrs["class"] = "form-control";
            $this->codigo->EditCustomAttributes = "";
            if (!$this->codigo->Raw) {
                $this->codigo->CurrentValue = HtmlDecode($this->codigo->CurrentValue);
            }
            $this->codigo->EditValue = HtmlEncode($this->codigo->CurrentValue);
            $this->codigo->PlaceHolder = RemoveHtml($this->codigo->caption());

            // principio_activo
            $this->principio_activo->EditAttrs["class"] = "form-control";
            $this->principio_activo->EditCustomAttributes = "";
            if (!$this->principio_activo->Raw) {
                $this->principio_activo->CurrentValue = HtmlDecode($this->principio_activo->CurrentValue);
            }
            $this->principio_activo->EditValue = HtmlEncode($this->principio_activo->CurrentValue);
            $this->principio_activo->PlaceHolder = RemoveHtml($this->principio_activo->caption());

            // fabricante
            $this->fabricante->EditCustomAttributes = "";
            $curVal = trim(strval($this->fabricante->CurrentValue));
            if ($curVal != "") {
                $this->fabricante->ViewValue = $this->fabricante->lookupCacheOption($curVal);
            } else {
                $this->fabricante->ViewValue = $this->fabricante->Lookup !== null && is_array($this->fabricante->Lookup->Options) ? $curVal : null;
            }
            if ($this->fabricante->ViewValue !== null) { // Load from cache
                $this->fabricante->EditValue = array_values($this->fabricante->Lookup->Options);
                if ($this->fabricante->ViewValue == "") {
                    $this->fabricante->ViewValue = $Language->phrase("PleaseSelect");
                }
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`Id`" . SearchString("=", $this->fabricante->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->fabricante->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->fabricante->Lookup->renderViewRow($rswrk[0]);
                    $this->fabricante->ViewValue = $this->fabricante->displayValue($arwrk);
                } else {
                    $this->fabricante->ViewValue = $Language->phrase("PleaseSelect");
                }
                $arwrk = $rswrk;
                $this->fabricante->EditValue = $arwrk;
            }
            $this->fabricante->PlaceHolder = RemoveHtml($this->fabricante->caption());

            // codigo_de_barra
            $this->codigo_de_barra->EditAttrs["class"] = "form-control";
            $this->codigo_de_barra->EditCustomAttributes = "";
            if (!$this->codigo_de_barra->Raw) {
                $this->codigo_de_barra->CurrentValue = HtmlDecode($this->codigo_de_barra->CurrentValue);
            }
            $this->codigo_de_barra->EditValue = HtmlEncode($this->codigo_de_barra->CurrentValue);
            $this->codigo_de_barra->PlaceHolder = RemoveHtml($this->codigo_de_barra->caption());

            // unidad_medida_defecto
            $this->unidad_medida_defecto->EditAttrs["class"] = "form-control";
            $this->unidad_medida_defecto->EditCustomAttributes = "";
            $curVal = trim(strval($this->unidad_medida_defecto->CurrentValue));
            if ($curVal != "") {
                $this->unidad_medida_defecto->ViewValue = $this->unidad_medida_defecto->lookupCacheOption($curVal);
            } else {
                $this->unidad_medida_defecto->ViewValue = $this->unidad_medida_defecto->Lookup !== null && is_array($this->unidad_medida_defecto->Lookup->Options) ? $curVal : null;
            }
            if ($this->unidad_medida_defecto->ViewValue !== null) { // Load from cache
                $this->unidad_medida_defecto->EditValue = array_values($this->unidad_medida_defecto->Lookup->Options);
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`codigo`" . SearchString("=", $this->unidad_medida_defecto->CurrentValue, DATATYPE_STRING, "");
                }
                $lookupFilter = function() {
                    return "`codigo` = 'UDM001'";
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->unidad_medida_defecto->Lookup->getSql(true, $filterWrk, $lookupFilter, $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->unidad_medida_defecto->EditValue = $arwrk;
            }
            $this->unidad_medida_defecto->PlaceHolder = RemoveHtml($this->unidad_medida_defecto->caption());

            // cantidad_por_unidad_medida
            $this->cantidad_por_unidad_medida->EditAttrs["class"] = "form-control";
            $this->cantidad_por_unidad_medida->EditCustomAttributes = "";
            $curVal = trim(strval($this->cantidad_por_unidad_medida->CurrentValue));
            if ($curVal != "") {
                $this->cantidad_por_unidad_medida->ViewValue = $this->cantidad_por_unidad_medida->lookupCacheOption($curVal);
            } else {
                $this->cantidad_por_unidad_medida->ViewValue = $this->cantidad_por_unidad_medida->Lookup !== null && is_array($this->cantidad_por_unidad_medida->Lookup->Options) ? $curVal : null;
            }
            if ($this->cantidad_por_unidad_medida->ViewValue !== null) { // Load from cache
                $this->cantidad_por_unidad_medida->EditValue = array_values($this->cantidad_por_unidad_medida->Lookup->Options);
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`cantidad`" . SearchString("=", $this->cantidad_por_unidad_medida->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->cantidad_por_unidad_medida->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                $arwrk = $rswrk;
                foreach ($arwrk as &$row)
                    $row = $this->cantidad_por_unidad_medida->Lookup->renderViewRow($row);
                $this->cantidad_por_unidad_medida->EditValue = $arwrk;
            }
            $this->cantidad_por_unidad_medida->PlaceHolder = RemoveHtml($this->cantidad_por_unidad_medida->caption());

            // foto
            $this->foto->EditAttrs["class"] = "form-control";
            $this->foto->EditCustomAttributes = "";
            if (!EmptyValue($this->foto->Upload->DbValue)) {
                $this->foto->ImageWidth = 120;
                $this->foto->ImageHeight = 120;
                $this->foto->ImageAlt = $this->foto->alt();
                $this->foto->EditValue = $this->foto->Upload->DbValue;
            } else {
                $this->foto->EditValue = "";
            }
            if (!EmptyValue($this->foto->CurrentValue)) {
                $this->foto->Upload->FileName = $this->foto->CurrentValue;
            }
            if ($this->isShow() || $this->isCopy()) {
                RenderUploadField($this->foto);
            }

            // cantidad_minima
            $this->cantidad_minima->EditAttrs["class"] = "form-control";
            $this->cantidad_minima->EditCustomAttributes = "";
            $this->cantidad_minima->EditValue = HtmlEncode($this->cantidad_minima->CurrentValue);
            $this->cantidad_minima->PlaceHolder = RemoveHtml($this->cantidad_minima->caption());
            if (strval($this->cantidad_minima->EditValue) != "" && is_numeric($this->cantidad_minima->EditValue)) {
                $this->cantidad_minima->EditValue = FormatNumber($this->cantidad_minima->EditValue, -2, -1, -2, -1);
            }

            // cantidad_maxima
            $this->cantidad_maxima->EditAttrs["class"] = "form-control";
            $this->cantidad_maxima->EditCustomAttributes = "";
            $this->cantidad_maxima->EditValue = HtmlEncode($this->cantidad_maxima->CurrentValue);
            $this->cantidad_maxima->PlaceHolder = RemoveHtml($this->cantidad_maxima->caption());
            if (strval($this->cantidad_maxima->EditValue) != "" && is_numeric($this->cantidad_maxima->EditValue)) {
                $this->cantidad_maxima->EditValue = FormatNumber($this->cantidad_maxima->EditValue, -2, -1, -2, -1);
            }

            // alicuota
            $this->alicuota->EditAttrs["class"] = "form-control";
            $this->alicuota->EditCustomAttributes = "";
            $curVal = trim(strval($this->alicuota->CurrentValue));
            if ($curVal != "") {
                $this->alicuota->ViewValue = $this->alicuota->lookupCacheOption($curVal);
            } else {
                $this->alicuota->ViewValue = $this->alicuota->Lookup !== null && is_array($this->alicuota->Lookup->Options) ? $curVal : null;
            }
            if ($this->alicuota->ViewValue !== null) { // Load from cache
                $this->alicuota->EditValue = array_values($this->alicuota->Lookup->Options);
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`codigo`" . SearchString("=", $this->alicuota->CurrentValue, DATATYPE_STRING, "");
                }
                $lookupFilter = function() {
                    return "`activo` = 'S'";
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->alicuota->Lookup->getSql(true, $filterWrk, $lookupFilter, $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                $arwrk = $rswrk;
                foreach ($arwrk as &$row)
                    $row = $this->alicuota->Lookup->renderViewRow($row);
                $this->alicuota->EditValue = $arwrk;
            }
            $this->alicuota->PlaceHolder = RemoveHtml($this->alicuota->caption());

            // articulo_inventario
            $this->articulo_inventario->EditAttrs["class"] = "form-control";
            $this->articulo_inventario->EditCustomAttributes = "";
            $this->articulo_inventario->EditValue = $this->articulo_inventario->options(true);
            $this->articulo_inventario->PlaceHolder = RemoveHtml($this->articulo_inventario->caption());

            // activo
            $this->activo->EditAttrs["class"] = "form-control";
            $this->activo->EditCustomAttributes = "";
            $this->activo->EditValue = $this->activo->options(true);
            $this->activo->PlaceHolder = RemoveHtml($this->activo->caption());

            // puntos_ventas
            $this->puntos_ventas->EditAttrs["class"] = "form-control";
            $this->puntos_ventas->EditCustomAttributes = "";
            $this->puntos_ventas->EditValue = HtmlEncode($this->puntos_ventas->CurrentValue);
            $this->puntos_ventas->PlaceHolder = RemoveHtml($this->puntos_ventas->caption());

            // puntos_premio
            $this->puntos_premio->EditAttrs["class"] = "form-control";
            $this->puntos_premio->EditCustomAttributes = "";
            $this->puntos_premio->EditValue = HtmlEncode($this->puntos_premio->CurrentValue);
            $this->puntos_premio->PlaceHolder = RemoveHtml($this->puntos_premio->caption());

            // sincroniza
            $this->sincroniza->EditAttrs["class"] = "form-control";
            $this->sincroniza->EditCustomAttributes = "";
            $this->sincroniza->EditValue = $this->sincroniza->options(true);
            $this->sincroniza->PlaceHolder = RemoveHtml($this->sincroniza->caption());

            // Add refer script

            // codigo_ims
            $this->codigo_ims->LinkCustomAttributes = "";
            $this->codigo_ims->HrefValue = "";

            // codigo
            $this->codigo->LinkCustomAttributes = "";
            $this->codigo->HrefValue = "";

            // principio_activo
            $this->principio_activo->LinkCustomAttributes = "";
            $this->principio_activo->HrefValue = "";

            // fabricante
            $this->fabricante->LinkCustomAttributes = "";
            $this->fabricante->HrefValue = "";

            // codigo_de_barra
            $this->codigo_de_barra->LinkCustomAttributes = "";
            $this->codigo_de_barra->HrefValue = "";

            // unidad_medida_defecto
            $this->unidad_medida_defecto->LinkCustomAttributes = "";
            $this->unidad_medida_defecto->HrefValue = "";

            // cantidad_por_unidad_medida
            $this->cantidad_por_unidad_medida->LinkCustomAttributes = "";
            $this->cantidad_por_unidad_medida->HrefValue = "";

            // foto
            $this->foto->LinkCustomAttributes = "";
            if (!EmptyValue($this->foto->Upload->DbValue)) {
                $this->foto->HrefValue = GetFileUploadUrl($this->foto, $this->foto->htmlDecode($this->foto->Upload->DbValue)); // Add prefix/suffix
                $this->foto->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->foto->HrefValue = FullUrl($this->foto->HrefValue, "href");
                }
            } else {
                $this->foto->HrefValue = "";
            }
            $this->foto->ExportHrefValue = $this->foto->UploadPath . $this->foto->Upload->DbValue;

            // cantidad_minima
            $this->cantidad_minima->LinkCustomAttributes = "";
            $this->cantidad_minima->HrefValue = "";

            // cantidad_maxima
            $this->cantidad_maxima->LinkCustomAttributes = "";
            $this->cantidad_maxima->HrefValue = "";

            // alicuota
            $this->alicuota->LinkCustomAttributes = "";
            $this->alicuota->HrefValue = "";

            // articulo_inventario
            $this->articulo_inventario->LinkCustomAttributes = "";
            $this->articulo_inventario->HrefValue = "";

            // activo
            $this->activo->LinkCustomAttributes = "";
            $this->activo->HrefValue = "";

            // puntos_ventas
            $this->puntos_ventas->LinkCustomAttributes = "";
            $this->puntos_ventas->HrefValue = "";

            // puntos_premio
            $this->puntos_premio->LinkCustomAttributes = "";
            $this->puntos_premio->HrefValue = "";

            // sincroniza
            $this->sincroniza->LinkCustomAttributes = "";
            $this->sincroniza->HrefValue = "";
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
        if ($this->codigo_ims->Required) {
            if (!$this->codigo_ims->IsDetailKey && EmptyValue($this->codigo_ims->FormValue)) {
                $this->codigo_ims->addErrorMessage(str_replace("%s", $this->codigo_ims->caption(), $this->codigo_ims->RequiredErrorMessage));
            }
        }
        if ($this->codigo->Required) {
            if (!$this->codigo->IsDetailKey && EmptyValue($this->codigo->FormValue)) {
                $this->codigo->addErrorMessage(str_replace("%s", $this->codigo->caption(), $this->codigo->RequiredErrorMessage));
            }
        }
        if ($this->principio_activo->Required) {
            if (!$this->principio_activo->IsDetailKey && EmptyValue($this->principio_activo->FormValue)) {
                $this->principio_activo->addErrorMessage(str_replace("%s", $this->principio_activo->caption(), $this->principio_activo->RequiredErrorMessage));
            }
        }
        if ($this->fabricante->Required) {
            if (!$this->fabricante->IsDetailKey && EmptyValue($this->fabricante->FormValue)) {
                $this->fabricante->addErrorMessage(str_replace("%s", $this->fabricante->caption(), $this->fabricante->RequiredErrorMessage));
            }
        }
        if ($this->codigo_de_barra->Required) {
            if (!$this->codigo_de_barra->IsDetailKey && EmptyValue($this->codigo_de_barra->FormValue)) {
                $this->codigo_de_barra->addErrorMessage(str_replace("%s", $this->codigo_de_barra->caption(), $this->codigo_de_barra->RequiredErrorMessage));
            }
        }
        if ($this->unidad_medida_defecto->Required) {
            if (!$this->unidad_medida_defecto->IsDetailKey && EmptyValue($this->unidad_medida_defecto->FormValue)) {
                $this->unidad_medida_defecto->addErrorMessage(str_replace("%s", $this->unidad_medida_defecto->caption(), $this->unidad_medida_defecto->RequiredErrorMessage));
            }
        }
        if ($this->cantidad_por_unidad_medida->Required) {
            if (!$this->cantidad_por_unidad_medida->IsDetailKey && EmptyValue($this->cantidad_por_unidad_medida->FormValue)) {
                $this->cantidad_por_unidad_medida->addErrorMessage(str_replace("%s", $this->cantidad_por_unidad_medida->caption(), $this->cantidad_por_unidad_medida->RequiredErrorMessage));
            }
        }
        if ($this->foto->Required) {
            if ($this->foto->Upload->FileName == "" && !$this->foto->Upload->KeepFile) {
                $this->foto->addErrorMessage(str_replace("%s", $this->foto->caption(), $this->foto->RequiredErrorMessage));
            }
        }
        if ($this->cantidad_minima->Required) {
            if (!$this->cantidad_minima->IsDetailKey && EmptyValue($this->cantidad_minima->FormValue)) {
                $this->cantidad_minima->addErrorMessage(str_replace("%s", $this->cantidad_minima->caption(), $this->cantidad_minima->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->cantidad_minima->FormValue)) {
            $this->cantidad_minima->addErrorMessage($this->cantidad_minima->getErrorMessage(false));
        }
        if ($this->cantidad_maxima->Required) {
            if (!$this->cantidad_maxima->IsDetailKey && EmptyValue($this->cantidad_maxima->FormValue)) {
                $this->cantidad_maxima->addErrorMessage(str_replace("%s", $this->cantidad_maxima->caption(), $this->cantidad_maxima->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->cantidad_maxima->FormValue)) {
            $this->cantidad_maxima->addErrorMessage($this->cantidad_maxima->getErrorMessage(false));
        }
        if ($this->alicuota->Required) {
            if (!$this->alicuota->IsDetailKey && EmptyValue($this->alicuota->FormValue)) {
                $this->alicuota->addErrorMessage(str_replace("%s", $this->alicuota->caption(), $this->alicuota->RequiredErrorMessage));
            }
        }
        if ($this->articulo_inventario->Required) {
            if (!$this->articulo_inventario->IsDetailKey && EmptyValue($this->articulo_inventario->FormValue)) {
                $this->articulo_inventario->addErrorMessage(str_replace("%s", $this->articulo_inventario->caption(), $this->articulo_inventario->RequiredErrorMessage));
            }
        }
        if ($this->activo->Required) {
            if (!$this->activo->IsDetailKey && EmptyValue($this->activo->FormValue)) {
                $this->activo->addErrorMessage(str_replace("%s", $this->activo->caption(), $this->activo->RequiredErrorMessage));
            }
        }
        if ($this->puntos_ventas->Required) {
            if (!$this->puntos_ventas->IsDetailKey && EmptyValue($this->puntos_ventas->FormValue)) {
                $this->puntos_ventas->addErrorMessage(str_replace("%s", $this->puntos_ventas->caption(), $this->puntos_ventas->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->puntos_ventas->FormValue)) {
            $this->puntos_ventas->addErrorMessage($this->puntos_ventas->getErrorMessage(false));
        }
        if ($this->puntos_premio->Required) {
            if (!$this->puntos_premio->IsDetailKey && EmptyValue($this->puntos_premio->FormValue)) {
                $this->puntos_premio->addErrorMessage(str_replace("%s", $this->puntos_premio->caption(), $this->puntos_premio->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->puntos_premio->FormValue)) {
            $this->puntos_premio->addErrorMessage($this->puntos_premio->getErrorMessage(false));
        }
        if ($this->sincroniza->Required) {
            if (!$this->sincroniza->IsDetailKey && EmptyValue($this->sincroniza->FormValue)) {
                $this->sincroniza->addErrorMessage(str_replace("%s", $this->sincroniza->caption(), $this->sincroniza->RequiredErrorMessage));
            }
        }

        // Validate detail grid
        $detailTblVar = explode(",", $this->getCurrentDetailTable());
        $detailPage = Container("ArticuloUnidadMedidaGrid");
        if (in_array("articulo_unidad_medida", $detailTblVar) && $detailPage->DetailAdd) {
            $detailPage->validateGridForm();
        }
        $detailPage = Container("AdjuntoGrid");
        if (in_array("adjunto", $detailTblVar) && $detailPage->DetailAdd) {
            $detailPage->validateGridForm();
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

    // Add record
    protected function addRow($rsold = null)
    {
        global $Language, $Security;
        $conn = $this->getConnection();

        // Begin transaction
        if ($this->getCurrentDetailTable() != "") {
            $conn->beginTransaction();
        }

        // Load db values from rsold
        $this->loadDbValues($rsold);
        if ($rsold) {
        }
        $rsnew = [];

        // codigo_ims
        $this->codigo_ims->setDbValueDef($rsnew, $this->codigo_ims->CurrentValue, null, false);

        // codigo
        $this->codigo->setDbValueDef($rsnew, $this->codigo->CurrentValue, null, false);

        // principio_activo
        $this->principio_activo->setDbValueDef($rsnew, $this->principio_activo->CurrentValue, null, false);

        // fabricante
        $this->fabricante->setDbValueDef($rsnew, $this->fabricante->CurrentValue, null, false);

        // codigo_de_barra
        $this->codigo_de_barra->setDbValueDef($rsnew, $this->codigo_de_barra->CurrentValue, null, false);

        // unidad_medida_defecto
        $this->unidad_medida_defecto->setDbValueDef($rsnew, $this->unidad_medida_defecto->CurrentValue, null, false);

        // cantidad_por_unidad_medida
        $this->cantidad_por_unidad_medida->setDbValueDef($rsnew, $this->cantidad_por_unidad_medida->CurrentValue, null, false);

        // foto
        if ($this->foto->Visible && !$this->foto->Upload->KeepFile) {
            $this->foto->Upload->DbValue = ""; // No need to delete old file
            if ($this->foto->Upload->FileName == "") {
                $rsnew['foto'] = null;
            } else {
                $rsnew['foto'] = $this->foto->Upload->FileName;
            }
        }

        // cantidad_minima
        $this->cantidad_minima->setDbValueDef($rsnew, $this->cantidad_minima->CurrentValue, 0, strval($this->cantidad_minima->CurrentValue) == "");

        // cantidad_maxima
        $this->cantidad_maxima->setDbValueDef($rsnew, $this->cantidad_maxima->CurrentValue, 0, strval($this->cantidad_maxima->CurrentValue) == "");

        // alicuota
        $this->alicuota->setDbValueDef($rsnew, $this->alicuota->CurrentValue, null, false);

        // articulo_inventario
        $this->articulo_inventario->setDbValueDef($rsnew, $this->articulo_inventario->CurrentValue, null, strval($this->articulo_inventario->CurrentValue) == "");

        // activo
        $this->activo->setDbValueDef($rsnew, $this->activo->CurrentValue, null, strval($this->activo->CurrentValue) == "");

        // puntos_ventas
        $this->puntos_ventas->setDbValueDef($rsnew, $this->puntos_ventas->CurrentValue, 0, strval($this->puntos_ventas->CurrentValue) == "");

        // puntos_premio
        $this->puntos_premio->setDbValueDef($rsnew, $this->puntos_premio->CurrentValue, 0, strval($this->puntos_premio->CurrentValue) == "");

        // sincroniza
        $this->sincroniza->setDbValueDef($rsnew, $this->sincroniza->CurrentValue, null, strval($this->sincroniza->CurrentValue) == "");
        if ($this->foto->Visible && !$this->foto->Upload->KeepFile) {
            $oldFiles = EmptyValue($this->foto->Upload->DbValue) ? [] : [$this->foto->htmlDecode($this->foto->Upload->DbValue)];
            if (!EmptyValue($this->foto->Upload->FileName)) {
                $newFiles = [$this->foto->Upload->FileName];
                $NewFileCount = count($newFiles);
                for ($i = 0; $i < $NewFileCount; $i++) {
                    if ($newFiles[$i] != "") {
                        $file = $newFiles[$i];
                        $tempPath = UploadTempPath($this->foto, $this->foto->Upload->Index);
                        if (file_exists($tempPath . $file)) {
                            if (Config("DELETE_UPLOADED_FILES")) {
                                $oldFileFound = false;
                                $oldFileCount = count($oldFiles);
                                for ($j = 0; $j < $oldFileCount; $j++) {
                                    $oldFile = $oldFiles[$j];
                                    if ($oldFile == $file) { // Old file found, no need to delete anymore
                                        array_splice($oldFiles, $j, 1);
                                        $oldFileFound = true;
                                        break;
                                    }
                                }
                                if ($oldFileFound) { // No need to check if file exists further
                                    continue;
                                }
                            }
                            $file1 = UniqueFilename($this->foto->physicalUploadPath(), $file); // Get new file name
                            if ($file1 != $file) { // Rename temp file
                                while (file_exists($tempPath . $file1) || file_exists($this->foto->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                    $file1 = UniqueFilename([$this->foto->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                }
                                rename($tempPath . $file, $tempPath . $file1);
                                $newFiles[$i] = $file1;
                            }
                        }
                    }
                }
                $this->foto->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                $this->foto->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                $this->foto->setDbValueDef($rsnew, $this->foto->Upload->FileName, null, false);
            }
        }

        // Call Row Inserting event
        $insertRow = $this->rowInserting($rsold, $rsnew);
        $addRow = false;
        if ($insertRow) {
            try {
                $addRow = $this->insert($rsnew);
            } catch (\Exception $e) {
                $this->setFailureMessage($e->getMessage());
            }
            if ($addRow) {
                if ($this->foto->Visible && !$this->foto->Upload->KeepFile) {
                    $oldFiles = EmptyValue($this->foto->Upload->DbValue) ? [] : [$this->foto->htmlDecode($this->foto->Upload->DbValue)];
                    if (!EmptyValue($this->foto->Upload->FileName)) {
                        $newFiles = [$this->foto->Upload->FileName];
                        $newFiles2 = [$this->foto->htmlDecode($rsnew['foto'])];
                        $newFileCount = count($newFiles);
                        for ($i = 0; $i < $newFileCount; $i++) {
                            if ($newFiles[$i] != "") {
                                $file = UploadTempPath($this->foto, $this->foto->Upload->Index) . $newFiles[$i];
                                if (file_exists($file)) {
                                    if (@$newFiles2[$i] != "") { // Use correct file name
                                        $newFiles[$i] = $newFiles2[$i];
                                    }
                                    if (!$this->foto->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
                                        $this->setFailureMessage($Language->phrase("UploadErrMsg7"));
                                        return false;
                                    }
                                }
                            }
                        }
                    } else {
                        $newFiles = [];
                    }
                    if (Config("DELETE_UPLOADED_FILES")) {
                        foreach ($oldFiles as $oldFile) {
                            if ($oldFile != "" && !in_array($oldFile, $newFiles)) {
                                @unlink($this->foto->oldPhysicalUploadPath() . $oldFile);
                            }
                        }
                    }
                }
            }
        } else {
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("InsertCancelled"));
            }
            $addRow = false;
        }

        // Add detail records
        if ($addRow) {
            $detailTblVar = explode(",", $this->getCurrentDetailTable());
            $detailPage = Container("ArticuloUnidadMedidaGrid");
            if (in_array("articulo_unidad_medida", $detailTblVar) && $detailPage->DetailAdd) {
                $detailPage->articulo->setSessionValue($this->id->CurrentValue); // Set master key
                $Security->loadCurrentUserLevel($this->ProjectID . "articulo_unidad_medida"); // Load user level of detail table
                $addRow = $detailPage->gridInsert();
                $Security->loadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
                if (!$addRow) {
                $detailPage->articulo->setSessionValue(""); // Clear master key if insert failed
                }
            }
            $detailPage = Container("AdjuntoGrid");
            if (in_array("adjunto", $detailTblVar) && $detailPage->DetailAdd) {
                $detailPage->articulo->setSessionValue($this->id->CurrentValue); // Set master key
                $Security->loadCurrentUserLevel($this->ProjectID . "adjunto"); // Load user level of detail table
                $addRow = $detailPage->gridInsert();
                $Security->loadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
                if (!$addRow) {
                $detailPage->articulo->setSessionValue(""); // Clear master key if insert failed
                }
            }
        }

        // Commit/Rollback transaction
        if ($this->getCurrentDetailTable() != "") {
            if ($addRow) {
                $conn->commit(); // Commit transaction
            } else {
                $conn->rollback(); // Rollback transaction
            }
        }
        if ($addRow) {
            // Call Row Inserted event
            $this->rowInserted($rsold, $rsnew);
        }

        // Clean upload path if any
        if ($addRow) {
            // foto
            CleanUploadTempPath($this->foto, $this->foto->Upload->Index);
        }

        // Write JSON for API request
        if (IsApi() && $addRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            WriteJson(["success" => true, $this->TableVar => $row]);
        }
        return $addRow;
    }

    // Set up detail parms based on QueryString
    protected function setupDetailParms()
    {
        // Get the keys for master table
        $detailTblVar = Get(Config("TABLE_SHOW_DETAIL"));
        if ($detailTblVar !== null) {
            $this->setCurrentDetailTable($detailTblVar);
        } else {
            $detailTblVar = $this->getCurrentDetailTable();
        }
        if ($detailTblVar != "") {
            $detailTblVar = explode(",", $detailTblVar);
            if (in_array("articulo_unidad_medida", $detailTblVar)) {
                $detailPageObj = Container("ArticuloUnidadMedidaGrid");
                if ($detailPageObj->DetailAdd) {
                    if ($this->CopyRecord) {
                        $detailPageObj->CurrentMode = "copy";
                    } else {
                        $detailPageObj->CurrentMode = "add";
                    }
                    $detailPageObj->CurrentAction = "gridadd";

                    // Save current master table to detail table
                    $detailPageObj->setCurrentMasterTable($this->TableVar);
                    $detailPageObj->setStartRecordNumber(1);
                    $detailPageObj->articulo->IsDetailKey = true;
                    $detailPageObj->articulo->CurrentValue = $this->id->CurrentValue;
                    $detailPageObj->articulo->setSessionValue($detailPageObj->articulo->CurrentValue);
                }
            }
            if (in_array("adjunto", $detailTblVar)) {
                $detailPageObj = Container("AdjuntoGrid");
                if ($detailPageObj->DetailAdd) {
                    if ($this->CopyRecord) {
                        $detailPageObj->CurrentMode = "copy";
                    } else {
                        $detailPageObj->CurrentMode = "add";
                    }
                    $detailPageObj->CurrentAction = "gridadd";

                    // Save current master table to detail table
                    $detailPageObj->setCurrentMasterTable($this->TableVar);
                    $detailPageObj->setStartRecordNumber(1);
                    $detailPageObj->articulo->IsDetailKey = true;
                    $detailPageObj->articulo->CurrentValue = $this->id->CurrentValue;
                    $detailPageObj->articulo->setSessionValue($detailPageObj->articulo->CurrentValue);
                    $detailPageObj->cliente->setSessionValue(""); // Clear session key
                    $detailPageObj->proveedor->setSessionValue(""); // Clear session key
                }
            }
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("Home");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("ArticuloList"), "", $this->TableVar, true);
        $pageId = ($this->isCopy()) ? "Copy" : "Add";
        $Breadcrumb->add("add", $pageId, $url);
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
                case "x_presentacion":
                    break;
                case "x_fabricante":
                    break;
                case "x_categoria":
                    $lookupFilter = function () {
                        return "`tabla` = 'CATEGORIA'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    break;
                case "x_lista_pedido":
                    $lookupFilter = function () {
                        return "`tabla` = 'LISTA_PEDIDO'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    break;
                case "x_unidad_medida_defecto":
                    $lookupFilter = function () {
                        return "`codigo` = 'UDM001'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    break;
                case "x_cantidad_por_unidad_medida":
                    break;
                case "x_alicuota":
                    $lookupFilter = function () {
                        return "`activo` = 'S'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    break;
                case "x_articulo_inventario":
                    break;
                case "x_activo":
                    break;
                case "x_sincroniza":
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
