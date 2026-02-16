<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class TablaRetencionesAdd extends TablaRetenciones
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'tabla_retenciones';

    // Page object name
    public $PageObjName = "TablaRetencionesAdd";

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

        // Table object (tabla_retenciones)
        if (!isset($GLOBALS["tabla_retenciones"]) || get_class($GLOBALS["tabla_retenciones"]) == PROJECT_NAMESPACE . "tabla_retenciones") {
            $GLOBALS["tabla_retenciones"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'tabla_retenciones');
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
                $doc = new $class(Container("tabla_retenciones"));
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
                    if ($pageName == "TablaRetencionesView") {
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
        $this->codigo->setVisibility();
        $this->tipo->setVisibility();
        $this->base_imponible->setVisibility();
        $this->tarifa->setVisibility();
        $this->sustraendo->setVisibility();
        $this->pagos_mayores->setVisibility();
        $this->activo->setVisibility();
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
        $this->setupLookupOptions($this->codigo);

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
                    $this->terminate("TablaRetencionesList"); // No matching record, return to list
                    return;
                }
                break;
            case "insert": // Add new record
                $this->SendEmail = true; // Send email on add success
                if ($this->addRow($this->OldRecordset)) { // Add successful
                    if ($this->getSuccessMessage() == "" && Post("addopt") != "1") { // Skip success message for addopt (done in JavaScript)
                        $this->setSuccessMessage($Language->phrase("AddSuccess")); // Set up success message
                    }
                    $returnUrl = $this->getReturnUrl();
                    if (GetPageName($returnUrl) == "TablaRetencionesList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "TablaRetencionesView") {
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
    }

    // Load default values
    protected function loadDefaultValues()
    {
        $this->id->CurrentValue = null;
        $this->id->OldValue = $this->id->CurrentValue;
        $this->codigo->CurrentValue = null;
        $this->codigo->OldValue = $this->codigo->CurrentValue;
        $this->tipo->CurrentValue = null;
        $this->tipo->OldValue = $this->tipo->CurrentValue;
        $this->base_imponible->CurrentValue = null;
        $this->base_imponible->OldValue = $this->base_imponible->CurrentValue;
        $this->tarifa->CurrentValue = null;
        $this->tarifa->OldValue = $this->tarifa->CurrentValue;
        $this->sustraendo->CurrentValue = null;
        $this->sustraendo->OldValue = $this->sustraendo->CurrentValue;
        $this->pagos_mayores->CurrentValue = null;
        $this->pagos_mayores->OldValue = $this->pagos_mayores->CurrentValue;
        $this->activo->CurrentValue = null;
        $this->activo->OldValue = $this->activo->CurrentValue;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;

        // Check field name 'codigo' first before field var 'x_codigo'
        $val = $CurrentForm->hasValue("codigo") ? $CurrentForm->getValue("codigo") : $CurrentForm->getValue("x_codigo");
        if (!$this->codigo->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->codigo->Visible = false; // Disable update for API request
            } else {
                $this->codigo->setFormValue($val);
            }
        }

        // Check field name 'tipo' first before field var 'x_tipo'
        $val = $CurrentForm->hasValue("tipo") ? $CurrentForm->getValue("tipo") : $CurrentForm->getValue("x_tipo");
        if (!$this->tipo->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->tipo->Visible = false; // Disable update for API request
            } else {
                $this->tipo->setFormValue($val);
            }
        }

        // Check field name 'base_imponible' first before field var 'x_base_imponible'
        $val = $CurrentForm->hasValue("base_imponible") ? $CurrentForm->getValue("base_imponible") : $CurrentForm->getValue("x_base_imponible");
        if (!$this->base_imponible->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->base_imponible->Visible = false; // Disable update for API request
            } else {
                $this->base_imponible->setFormValue($val);
            }
        }

        // Check field name 'tarifa' first before field var 'x_tarifa'
        $val = $CurrentForm->hasValue("tarifa") ? $CurrentForm->getValue("tarifa") : $CurrentForm->getValue("x_tarifa");
        if (!$this->tarifa->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->tarifa->Visible = false; // Disable update for API request
            } else {
                $this->tarifa->setFormValue($val);
            }
        }

        // Check field name 'sustraendo' first before field var 'x_sustraendo'
        $val = $CurrentForm->hasValue("sustraendo") ? $CurrentForm->getValue("sustraendo") : $CurrentForm->getValue("x_sustraendo");
        if (!$this->sustraendo->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->sustraendo->Visible = false; // Disable update for API request
            } else {
                $this->sustraendo->setFormValue($val);
            }
        }

        // Check field name 'pagos_mayores' first before field var 'x_pagos_mayores'
        $val = $CurrentForm->hasValue("pagos_mayores") ? $CurrentForm->getValue("pagos_mayores") : $CurrentForm->getValue("x_pagos_mayores");
        if (!$this->pagos_mayores->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->pagos_mayores->Visible = false; // Disable update for API request
            } else {
                $this->pagos_mayores->setFormValue($val);
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

        // Check field name 'id' first before field var 'x_id'
        $val = $CurrentForm->hasValue("id") ? $CurrentForm->getValue("id") : $CurrentForm->getValue("x_id");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->codigo->CurrentValue = $this->codigo->FormValue;
        $this->tipo->CurrentValue = $this->tipo->FormValue;
        $this->base_imponible->CurrentValue = $this->base_imponible->FormValue;
        $this->tarifa->CurrentValue = $this->tarifa->FormValue;
        $this->sustraendo->CurrentValue = $this->sustraendo->FormValue;
        $this->pagos_mayores->CurrentValue = $this->pagos_mayores->FormValue;
        $this->activo->CurrentValue = $this->activo->FormValue;
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
        $this->codigo->setDbValue($row['codigo']);
        $this->tipo->setDbValue($row['tipo']);
        $this->base_imponible->setDbValue($row['base_imponible']);
        $this->tarifa->setDbValue($row['tarifa']);
        $this->sustraendo->setDbValue($row['sustraendo']);
        $this->pagos_mayores->setDbValue($row['pagos_mayores']);
        $this->activo->setDbValue($row['activo']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $this->loadDefaultValues();
        $row = [];
        $row['id'] = $this->id->CurrentValue;
        $row['codigo'] = $this->codigo->CurrentValue;
        $row['tipo'] = $this->tipo->CurrentValue;
        $row['base_imponible'] = $this->base_imponible->CurrentValue;
        $row['tarifa'] = $this->tarifa->CurrentValue;
        $row['sustraendo'] = $this->sustraendo->CurrentValue;
        $row['pagos_mayores'] = $this->pagos_mayores->CurrentValue;
        $row['activo'] = $this->activo->CurrentValue;
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
        if ($this->base_imponible->FormValue == $this->base_imponible->CurrentValue && is_numeric(ConvertToFloatString($this->base_imponible->CurrentValue))) {
            $this->base_imponible->CurrentValue = ConvertToFloatString($this->base_imponible->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->tarifa->FormValue == $this->tarifa->CurrentValue && is_numeric(ConvertToFloatString($this->tarifa->CurrentValue))) {
            $this->tarifa->CurrentValue = ConvertToFloatString($this->tarifa->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->sustraendo->FormValue == $this->sustraendo->CurrentValue && is_numeric(ConvertToFloatString($this->sustraendo->CurrentValue))) {
            $this->sustraendo->CurrentValue = ConvertToFloatString($this->sustraendo->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->pagos_mayores->FormValue == $this->pagos_mayores->CurrentValue && is_numeric(ConvertToFloatString($this->pagos_mayores->CurrentValue))) {
            $this->pagos_mayores->CurrentValue = ConvertToFloatString($this->pagos_mayores->CurrentValue);
        }

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // id

        // codigo

        // tipo

        // base_imponible

        // tarifa

        // sustraendo

        // pagos_mayores

        // activo
        if ($this->RowType == ROWTYPE_VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;
            $this->id->ViewCustomAttributes = "";

            // codigo
            $curVal = trim(strval($this->codigo->CurrentValue));
            if ($curVal != "") {
                $this->codigo->ViewValue = $this->codigo->lookupCacheOption($curVal);
                if ($this->codigo->ViewValue === null) { // Lookup from database
                    $filterWrk = "`campo_codigo`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $lookupFilter = function() {
                        return "`tabla` = 'TABLA_RETENCIONES'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    $sqlWrk = $this->codigo->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->codigo->Lookup->renderViewRow($rswrk[0]);
                        $this->codigo->ViewValue = $this->codigo->displayValue($arwrk);
                    } else {
                        $this->codigo->ViewValue = $this->codigo->CurrentValue;
                    }
                }
            } else {
                $this->codigo->ViewValue = null;
            }
            $this->codigo->ViewCustomAttributes = "";

            // tipo
            if (strval($this->tipo->CurrentValue) != "") {
                $this->tipo->ViewValue = $this->tipo->optionCaption($this->tipo->CurrentValue);
            } else {
                $this->tipo->ViewValue = null;
            }
            $this->tipo->ViewCustomAttributes = "";

            // base_imponible
            $this->base_imponible->ViewValue = $this->base_imponible->CurrentValue;
            $this->base_imponible->ViewValue = FormatNumber($this->base_imponible->ViewValue, 2, -2, -2, -2);
            $this->base_imponible->ViewCustomAttributes = "";

            // tarifa
            $this->tarifa->ViewValue = $this->tarifa->CurrentValue;
            $this->tarifa->ViewValue = FormatNumber($this->tarifa->ViewValue, 2, -2, -2, -2);
            $this->tarifa->ViewCustomAttributes = "";

            // sustraendo
            $this->sustraendo->ViewValue = $this->sustraendo->CurrentValue;
            $this->sustraendo->ViewValue = FormatNumber($this->sustraendo->ViewValue, 2, -2, -2, -2);
            $this->sustraendo->ViewCustomAttributes = "";

            // pagos_mayores
            $this->pagos_mayores->ViewValue = $this->pagos_mayores->CurrentValue;
            $this->pagos_mayores->ViewValue = FormatNumber($this->pagos_mayores->ViewValue, 2, -2, -2, -2);
            $this->pagos_mayores->ViewCustomAttributes = "";

            // activo
            if (strval($this->activo->CurrentValue) != "") {
                $this->activo->ViewValue = $this->activo->optionCaption($this->activo->CurrentValue);
            } else {
                $this->activo->ViewValue = null;
            }
            $this->activo->ViewCustomAttributes = "";

            // codigo
            $this->codigo->LinkCustomAttributes = "";
            $this->codigo->HrefValue = "";
            $this->codigo->TooltipValue = "";

            // tipo
            $this->tipo->LinkCustomAttributes = "";
            $this->tipo->HrefValue = "";
            $this->tipo->TooltipValue = "";

            // base_imponible
            $this->base_imponible->LinkCustomAttributes = "";
            $this->base_imponible->HrefValue = "";
            $this->base_imponible->TooltipValue = "";

            // tarifa
            $this->tarifa->LinkCustomAttributes = "";
            $this->tarifa->HrefValue = "";
            $this->tarifa->TooltipValue = "";

            // sustraendo
            $this->sustraendo->LinkCustomAttributes = "";
            $this->sustraendo->HrefValue = "";
            $this->sustraendo->TooltipValue = "";

            // pagos_mayores
            $this->pagos_mayores->LinkCustomAttributes = "";
            $this->pagos_mayores->HrefValue = "";
            $this->pagos_mayores->TooltipValue = "";

            // activo
            $this->activo->LinkCustomAttributes = "";
            $this->activo->HrefValue = "";
            $this->activo->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // codigo
            $this->codigo->EditCustomAttributes = "";
            $curVal = trim(strval($this->codigo->CurrentValue));
            if ($curVal != "") {
                $this->codigo->ViewValue = $this->codigo->lookupCacheOption($curVal);
            } else {
                $this->codigo->ViewValue = $this->codigo->Lookup !== null && is_array($this->codigo->Lookup->Options) ? $curVal : null;
            }
            if ($this->codigo->ViewValue !== null) { // Load from cache
                $this->codigo->EditValue = array_values($this->codigo->Lookup->Options);
                if ($this->codigo->ViewValue == "") {
                    $this->codigo->ViewValue = $Language->phrase("PleaseSelect");
                }
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`campo_codigo`" . SearchString("=", $this->codigo->CurrentValue, DATATYPE_STRING, "");
                }
                $lookupFilter = function() {
                    return "`tabla` = 'TABLA_RETENCIONES'";
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->codigo->Lookup->getSql(true, $filterWrk, $lookupFilter, $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->codigo->Lookup->renderViewRow($rswrk[0]);
                    $this->codigo->ViewValue = $this->codigo->displayValue($arwrk);
                } else {
                    $this->codigo->ViewValue = $Language->phrase("PleaseSelect");
                }
                $arwrk = $rswrk;
                $this->codigo->EditValue = $arwrk;
            }
            $this->codigo->PlaceHolder = RemoveHtml($this->codigo->caption());

            // tipo
            $this->tipo->EditAttrs["class"] = "form-control";
            $this->tipo->EditCustomAttributes = "";
            $this->tipo->EditValue = $this->tipo->options(true);
            $this->tipo->PlaceHolder = RemoveHtml($this->tipo->caption());

            // base_imponible
            $this->base_imponible->EditAttrs["class"] = "form-control";
            $this->base_imponible->EditCustomAttributes = "";
            $this->base_imponible->EditValue = HtmlEncode($this->base_imponible->CurrentValue);
            $this->base_imponible->PlaceHolder = RemoveHtml($this->base_imponible->caption());
            if (strval($this->base_imponible->EditValue) != "" && is_numeric($this->base_imponible->EditValue)) {
                $this->base_imponible->EditValue = FormatNumber($this->base_imponible->EditValue, -2, -2, -2, -2);
            }

            // tarifa
            $this->tarifa->EditAttrs["class"] = "form-control";
            $this->tarifa->EditCustomAttributes = "";
            $this->tarifa->EditValue = HtmlEncode($this->tarifa->CurrentValue);
            $this->tarifa->PlaceHolder = RemoveHtml($this->tarifa->caption());
            if (strval($this->tarifa->EditValue) != "" && is_numeric($this->tarifa->EditValue)) {
                $this->tarifa->EditValue = FormatNumber($this->tarifa->EditValue, -2, -2, -2, -2);
            }

            // sustraendo
            $this->sustraendo->EditAttrs["class"] = "form-control";
            $this->sustraendo->EditCustomAttributes = "";
            $this->sustraendo->EditValue = HtmlEncode($this->sustraendo->CurrentValue);
            $this->sustraendo->PlaceHolder = RemoveHtml($this->sustraendo->caption());
            if (strval($this->sustraendo->EditValue) != "" && is_numeric($this->sustraendo->EditValue)) {
                $this->sustraendo->EditValue = FormatNumber($this->sustraendo->EditValue, -2, -2, -2, -2);
            }

            // pagos_mayores
            $this->pagos_mayores->EditAttrs["class"] = "form-control";
            $this->pagos_mayores->EditCustomAttributes = "";
            $this->pagos_mayores->EditValue = HtmlEncode($this->pagos_mayores->CurrentValue);
            $this->pagos_mayores->PlaceHolder = RemoveHtml($this->pagos_mayores->caption());
            if (strval($this->pagos_mayores->EditValue) != "" && is_numeric($this->pagos_mayores->EditValue)) {
                $this->pagos_mayores->EditValue = FormatNumber($this->pagos_mayores->EditValue, -2, -2, -2, -2);
            }

            // activo
            $this->activo->EditCustomAttributes = "";
            $this->activo->EditValue = $this->activo->options(false);
            $this->activo->PlaceHolder = RemoveHtml($this->activo->caption());

            // Add refer script

            // codigo
            $this->codigo->LinkCustomAttributes = "";
            $this->codigo->HrefValue = "";

            // tipo
            $this->tipo->LinkCustomAttributes = "";
            $this->tipo->HrefValue = "";

            // base_imponible
            $this->base_imponible->LinkCustomAttributes = "";
            $this->base_imponible->HrefValue = "";

            // tarifa
            $this->tarifa->LinkCustomAttributes = "";
            $this->tarifa->HrefValue = "";

            // sustraendo
            $this->sustraendo->LinkCustomAttributes = "";
            $this->sustraendo->HrefValue = "";

            // pagos_mayores
            $this->pagos_mayores->LinkCustomAttributes = "";
            $this->pagos_mayores->HrefValue = "";

            // activo
            $this->activo->LinkCustomAttributes = "";
            $this->activo->HrefValue = "";
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
        if ($this->codigo->Required) {
            if (!$this->codigo->IsDetailKey && EmptyValue($this->codigo->FormValue)) {
                $this->codigo->addErrorMessage(str_replace("%s", $this->codigo->caption(), $this->codigo->RequiredErrorMessage));
            }
        }
        if ($this->tipo->Required) {
            if (!$this->tipo->IsDetailKey && EmptyValue($this->tipo->FormValue)) {
                $this->tipo->addErrorMessage(str_replace("%s", $this->tipo->caption(), $this->tipo->RequiredErrorMessage));
            }
        }
        if ($this->base_imponible->Required) {
            if (!$this->base_imponible->IsDetailKey && EmptyValue($this->base_imponible->FormValue)) {
                $this->base_imponible->addErrorMessage(str_replace("%s", $this->base_imponible->caption(), $this->base_imponible->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->base_imponible->FormValue)) {
            $this->base_imponible->addErrorMessage($this->base_imponible->getErrorMessage(false));
        }
        if ($this->tarifa->Required) {
            if (!$this->tarifa->IsDetailKey && EmptyValue($this->tarifa->FormValue)) {
                $this->tarifa->addErrorMessage(str_replace("%s", $this->tarifa->caption(), $this->tarifa->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->tarifa->FormValue)) {
            $this->tarifa->addErrorMessage($this->tarifa->getErrorMessage(false));
        }
        if ($this->sustraendo->Required) {
            if (!$this->sustraendo->IsDetailKey && EmptyValue($this->sustraendo->FormValue)) {
                $this->sustraendo->addErrorMessage(str_replace("%s", $this->sustraendo->caption(), $this->sustraendo->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->sustraendo->FormValue)) {
            $this->sustraendo->addErrorMessage($this->sustraendo->getErrorMessage(false));
        }
        if ($this->pagos_mayores->Required) {
            if (!$this->pagos_mayores->IsDetailKey && EmptyValue($this->pagos_mayores->FormValue)) {
                $this->pagos_mayores->addErrorMessage(str_replace("%s", $this->pagos_mayores->caption(), $this->pagos_mayores->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->pagos_mayores->FormValue)) {
            $this->pagos_mayores->addErrorMessage($this->pagos_mayores->getErrorMessage(false));
        }
        if ($this->activo->Required) {
            if ($this->activo->FormValue == "") {
                $this->activo->addErrorMessage(str_replace("%s", $this->activo->caption(), $this->activo->RequiredErrorMessage));
            }
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

        // Load db values from rsold
        $this->loadDbValues($rsold);
        if ($rsold) {
        }
        $rsnew = [];

        // codigo
        $this->codigo->setDbValueDef($rsnew, $this->codigo->CurrentValue, null, false);

        // tipo
        $this->tipo->setDbValueDef($rsnew, $this->tipo->CurrentValue, null, false);

        // base_imponible
        $this->base_imponible->setDbValueDef($rsnew, $this->base_imponible->CurrentValue, null, false);

        // tarifa
        $this->tarifa->setDbValueDef($rsnew, $this->tarifa->CurrentValue, null, false);

        // sustraendo
        $this->sustraendo->setDbValueDef($rsnew, $this->sustraendo->CurrentValue, null, false);

        // pagos_mayores
        $this->pagos_mayores->setDbValueDef($rsnew, $this->pagos_mayores->CurrentValue, null, false);

        // activo
        $this->activo->setDbValueDef($rsnew, $this->activo->CurrentValue, null, false);

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
        if ($addRow) {
            // Call Row Inserted event
            $this->rowInserted($rsold, $rsnew);
        }

        // Clean upload path if any
        if ($addRow) {
        }

        // Write JSON for API request
        if (IsApi() && $addRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            WriteJson(["success" => true, $this->TableVar => $row]);
        }
        return $addRow;
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("Home");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("TablaRetencionesList"), "", $this->TableVar, true);
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
                case "x_codigo":
                    $lookupFilter = function () {
                        return "`tabla` = 'TABLA_RETENCIONES'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    break;
                case "x_tipo":
                    break;
                case "x_activo":
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
