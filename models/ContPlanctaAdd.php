<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class ContPlanctaAdd extends ContPlancta
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'cont_plancta';

    // Page object name
    public $PageObjName = "ContPlanctaAdd";

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

        // Table object (cont_plancta)
        if (!isset($GLOBALS["cont_plancta"]) || get_class($GLOBALS["cont_plancta"]) == PROJECT_NAMESPACE . "cont_plancta") {
            $GLOBALS["cont_plancta"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'cont_plancta');
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
                $doc = new $class(Container("cont_plancta"));
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
                    if ($pageName == "ContPlanctaView") {
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
        $this->clase->setVisibility();
        $this->grupo->setVisibility();
        $this->cuenta->setVisibility();
        $this->subcuenta->setVisibility();
        $this->descripcion->setVisibility();
        $this->clasificacion->Visible = false;
        $this->naturaleza->Visible = false;
        $this->tipo->Visible = false;
        $this->moneda->setVisibility();
        $this->activa->setVisibility();
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
        $this->setupLookupOptions($this->moneda);

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
                    $this->terminate("ContPlanctaList"); // No matching record, return to list
                    return;
                }
                break;
            case "insert": // Add new record
                $this->SendEmail = true; // Send email on add success
                if ($this->addRow($this->OldRecordset)) { // Add successful
                    if ($this->getSuccessMessage() == "" && Post("addopt") != "1") { // Skip success message for addopt (done in JavaScript)
                        $this->setSuccessMessage($Language->phrase("AddSuccess")); // Set up success message
                    }
                    $returnUrl = $this->GetViewUrl();
                    if (GetPageName($returnUrl) == "ContPlanctaList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "ContPlanctaView") {
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
        $this->clase->CurrentValue = null;
        $this->clase->OldValue = $this->clase->CurrentValue;
        $this->grupo->CurrentValue = null;
        $this->grupo->OldValue = $this->grupo->CurrentValue;
        $this->cuenta->CurrentValue = null;
        $this->cuenta->OldValue = $this->cuenta->CurrentValue;
        $this->subcuenta->CurrentValue = null;
        $this->subcuenta->OldValue = $this->subcuenta->CurrentValue;
        $this->descripcion->CurrentValue = null;
        $this->descripcion->OldValue = $this->descripcion->CurrentValue;
        $this->clasificacion->CurrentValue = null;
        $this->clasificacion->OldValue = $this->clasificacion->CurrentValue;
        $this->naturaleza->CurrentValue = null;
        $this->naturaleza->OldValue = $this->naturaleza->CurrentValue;
        $this->tipo->CurrentValue = null;
        $this->tipo->OldValue = $this->tipo->CurrentValue;
        $this->moneda->CurrentValue = null;
        $this->moneda->OldValue = $this->moneda->CurrentValue;
        $this->activa->CurrentValue = null;
        $this->activa->OldValue = $this->activa->CurrentValue;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;

        // Check field name 'clase' first before field var 'x_clase'
        $val = $CurrentForm->hasValue("clase") ? $CurrentForm->getValue("clase") : $CurrentForm->getValue("x_clase");
        if (!$this->clase->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->clase->Visible = false; // Disable update for API request
            } else {
                $this->clase->setFormValue($val);
            }
        }

        // Check field name 'grupo' first before field var 'x_grupo'
        $val = $CurrentForm->hasValue("grupo") ? $CurrentForm->getValue("grupo") : $CurrentForm->getValue("x_grupo");
        if (!$this->grupo->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->grupo->Visible = false; // Disable update for API request
            } else {
                $this->grupo->setFormValue($val);
            }
        }

        // Check field name 'cuenta' first before field var 'x_cuenta'
        $val = $CurrentForm->hasValue("cuenta") ? $CurrentForm->getValue("cuenta") : $CurrentForm->getValue("x_cuenta");
        if (!$this->cuenta->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cuenta->Visible = false; // Disable update for API request
            } else {
                $this->cuenta->setFormValue($val);
            }
        }

        // Check field name 'subcuenta' first before field var 'x_subcuenta'
        $val = $CurrentForm->hasValue("subcuenta") ? $CurrentForm->getValue("subcuenta") : $CurrentForm->getValue("x_subcuenta");
        if (!$this->subcuenta->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->subcuenta->Visible = false; // Disable update for API request
            } else {
                $this->subcuenta->setFormValue($val);
            }
        }

        // Check field name 'descripcion' first before field var 'x_descripcion'
        $val = $CurrentForm->hasValue("descripcion") ? $CurrentForm->getValue("descripcion") : $CurrentForm->getValue("x_descripcion");
        if (!$this->descripcion->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->descripcion->Visible = false; // Disable update for API request
            } else {
                $this->descripcion->setFormValue($val);
            }
        }

        // Check field name 'moneda' first before field var 'x_moneda'
        $val = $CurrentForm->hasValue("moneda") ? $CurrentForm->getValue("moneda") : $CurrentForm->getValue("x_moneda");
        if (!$this->moneda->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->moneda->Visible = false; // Disable update for API request
            } else {
                $this->moneda->setFormValue($val);
            }
        }

        // Check field name 'activa' first before field var 'x_activa'
        $val = $CurrentForm->hasValue("activa") ? $CurrentForm->getValue("activa") : $CurrentForm->getValue("x_activa");
        if (!$this->activa->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->activa->Visible = false; // Disable update for API request
            } else {
                $this->activa->setFormValue($val);
            }
        }

        // Check field name 'id' first before field var 'x_id'
        $val = $CurrentForm->hasValue("id") ? $CurrentForm->getValue("id") : $CurrentForm->getValue("x_id");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->clase->CurrentValue = $this->clase->FormValue;
        $this->grupo->CurrentValue = $this->grupo->FormValue;
        $this->cuenta->CurrentValue = $this->cuenta->FormValue;
        $this->subcuenta->CurrentValue = $this->subcuenta->FormValue;
        $this->descripcion->CurrentValue = $this->descripcion->FormValue;
        $this->moneda->CurrentValue = $this->moneda->FormValue;
        $this->activa->CurrentValue = $this->activa->FormValue;
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
        $this->clase->setDbValue($row['clase']);
        $this->grupo->setDbValue($row['grupo']);
        $this->cuenta->setDbValue($row['cuenta']);
        $this->subcuenta->setDbValue($row['subcuenta']);
        $this->descripcion->setDbValue($row['descripcion']);
        $this->clasificacion->setDbValue($row['clasificacion']);
        $this->naturaleza->setDbValue($row['naturaleza']);
        $this->tipo->setDbValue($row['tipo']);
        $this->moneda->setDbValue($row['moneda']);
        $this->activa->setDbValue($row['activa']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $this->loadDefaultValues();
        $row = [];
        $row['id'] = $this->id->CurrentValue;
        $row['clase'] = $this->clase->CurrentValue;
        $row['grupo'] = $this->grupo->CurrentValue;
        $row['cuenta'] = $this->cuenta->CurrentValue;
        $row['subcuenta'] = $this->subcuenta->CurrentValue;
        $row['descripcion'] = $this->descripcion->CurrentValue;
        $row['clasificacion'] = $this->clasificacion->CurrentValue;
        $row['naturaleza'] = $this->naturaleza->CurrentValue;
        $row['tipo'] = $this->tipo->CurrentValue;
        $row['moneda'] = $this->moneda->CurrentValue;
        $row['activa'] = $this->activa->CurrentValue;
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

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // id

        // clase

        // grupo

        // cuenta

        // subcuenta

        // descripcion

        // clasificacion

        // naturaleza

        // tipo

        // moneda

        // activa
        if ($this->RowType == ROWTYPE_VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;
            $this->id->ViewCustomAttributes = "";

            // clase
            $this->clase->ViewValue = $this->clase->CurrentValue;
            $this->clase->ViewCustomAttributes = "";

            // grupo
            $this->grupo->ViewValue = $this->grupo->CurrentValue;
            $this->grupo->ViewCustomAttributes = "";

            // cuenta
            $this->cuenta->ViewValue = $this->cuenta->CurrentValue;
            $this->cuenta->ViewCustomAttributes = "";

            // subcuenta
            $this->subcuenta->ViewValue = $this->subcuenta->CurrentValue;
            $this->subcuenta->ViewCustomAttributes = "";

            // descripcion
            $this->descripcion->ViewValue = $this->descripcion->CurrentValue;
            $this->descripcion->ViewCustomAttributes = "";

            // clasificacion
            if (strval($this->clasificacion->CurrentValue) != "") {
                $this->clasificacion->ViewValue = $this->clasificacion->optionCaption($this->clasificacion->CurrentValue);
            } else {
                $this->clasificacion->ViewValue = null;
            }
            $this->clasificacion->ViewCustomAttributes = "";

            // naturaleza
            if (strval($this->naturaleza->CurrentValue) != "") {
                $this->naturaleza->ViewValue = $this->naturaleza->optionCaption($this->naturaleza->CurrentValue);
            } else {
                $this->naturaleza->ViewValue = null;
            }
            $this->naturaleza->ViewCustomAttributes = "";

            // tipo
            if (strval($this->tipo->CurrentValue) != "") {
                $this->tipo->ViewValue = $this->tipo->optionCaption($this->tipo->CurrentValue);
            } else {
                $this->tipo->ViewValue = null;
            }
            $this->tipo->ViewCustomAttributes = "";

            // moneda
            $curVal = trim(strval($this->moneda->CurrentValue));
            if ($curVal != "") {
                $this->moneda->ViewValue = $this->moneda->lookupCacheOption($curVal);
                if ($this->moneda->ViewValue === null) { // Lookup from database
                    $filterWrk = "`valor1`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $lookupFilter = function() {
                        return "`codigo` = '006'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    $sqlWrk = $this->moneda->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->moneda->Lookup->renderViewRow($rswrk[0]);
                        $this->moneda->ViewValue = $this->moneda->displayValue($arwrk);
                    } else {
                        $this->moneda->ViewValue = $this->moneda->CurrentValue;
                    }
                }
            } else {
                $this->moneda->ViewValue = null;
            }
            $this->moneda->ViewCustomAttributes = "";

            // activa
            if (strval($this->activa->CurrentValue) != "") {
                $this->activa->ViewValue = $this->activa->optionCaption($this->activa->CurrentValue);
            } else {
                $this->activa->ViewValue = null;
            }
            $this->activa->ViewCustomAttributes = "";

            // clase
            $this->clase->LinkCustomAttributes = "";
            $this->clase->HrefValue = "";
            $this->clase->TooltipValue = "";

            // grupo
            $this->grupo->LinkCustomAttributes = "";
            $this->grupo->HrefValue = "";
            $this->grupo->TooltipValue = "";

            // cuenta
            $this->cuenta->LinkCustomAttributes = "";
            $this->cuenta->HrefValue = "";
            $this->cuenta->TooltipValue = "";

            // subcuenta
            $this->subcuenta->LinkCustomAttributes = "";
            $this->subcuenta->HrefValue = "";
            $this->subcuenta->TooltipValue = "";

            // descripcion
            $this->descripcion->LinkCustomAttributes = "";
            $this->descripcion->HrefValue = "";
            $this->descripcion->TooltipValue = "";

            // moneda
            $this->moneda->LinkCustomAttributes = "";
            $this->moneda->HrefValue = "";
            $this->moneda->TooltipValue = "";

            // activa
            $this->activa->LinkCustomAttributes = "";
            $this->activa->HrefValue = "";
            $this->activa->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // clase
            $this->clase->EditAttrs["class"] = "form-control";
            $this->clase->EditCustomAttributes = "";
            if (!$this->clase->Raw) {
                $this->clase->CurrentValue = HtmlDecode($this->clase->CurrentValue);
            }
            $this->clase->EditValue = HtmlEncode($this->clase->CurrentValue);
            $this->clase->PlaceHolder = RemoveHtml($this->clase->caption());

            // grupo
            $this->grupo->EditAttrs["class"] = "form-control";
            $this->grupo->EditCustomAttributes = "";
            if (!$this->grupo->Raw) {
                $this->grupo->CurrentValue = HtmlDecode($this->grupo->CurrentValue);
            }
            $this->grupo->EditValue = HtmlEncode($this->grupo->CurrentValue);
            $this->grupo->PlaceHolder = RemoveHtml($this->grupo->caption());

            // cuenta
            $this->cuenta->EditAttrs["class"] = "form-control";
            $this->cuenta->EditCustomAttributes = "";
            if (!$this->cuenta->Raw) {
                $this->cuenta->CurrentValue = HtmlDecode($this->cuenta->CurrentValue);
            }
            $this->cuenta->EditValue = HtmlEncode($this->cuenta->CurrentValue);
            $this->cuenta->PlaceHolder = RemoveHtml($this->cuenta->caption());

            // subcuenta
            $this->subcuenta->EditAttrs["class"] = "form-control";
            $this->subcuenta->EditCustomAttributes = "";
            if (!$this->subcuenta->Raw) {
                $this->subcuenta->CurrentValue = HtmlDecode($this->subcuenta->CurrentValue);
            }
            $this->subcuenta->EditValue = HtmlEncode($this->subcuenta->CurrentValue);
            $this->subcuenta->PlaceHolder = RemoveHtml($this->subcuenta->caption());

            // descripcion
            $this->descripcion->EditAttrs["class"] = "form-control";
            $this->descripcion->EditCustomAttributes = "";
            if (!$this->descripcion->Raw) {
                $this->descripcion->CurrentValue = HtmlDecode($this->descripcion->CurrentValue);
            }
            $this->descripcion->EditValue = HtmlEncode($this->descripcion->CurrentValue);
            $this->descripcion->PlaceHolder = RemoveHtml($this->descripcion->caption());

            // moneda
            $this->moneda->EditAttrs["class"] = "form-control";
            $this->moneda->EditCustomAttributes = "";
            $curVal = trim(strval($this->moneda->CurrentValue));
            if ($curVal != "") {
                $this->moneda->ViewValue = $this->moneda->lookupCacheOption($curVal);
            } else {
                $this->moneda->ViewValue = $this->moneda->Lookup !== null && is_array($this->moneda->Lookup->Options) ? $curVal : null;
            }
            if ($this->moneda->ViewValue !== null) { // Load from cache
                $this->moneda->EditValue = array_values($this->moneda->Lookup->Options);
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`valor1`" . SearchString("=", $this->moneda->CurrentValue, DATATYPE_STRING, "");
                }
                $lookupFilter = function() {
                    return "`codigo` = '006'";
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->moneda->Lookup->getSql(true, $filterWrk, $lookupFilter, $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->moneda->EditValue = $arwrk;
            }
            $this->moneda->PlaceHolder = RemoveHtml($this->moneda->caption());

            // activa
            $this->activa->EditCustomAttributes = "";
            $this->activa->EditValue = $this->activa->options(false);
            $this->activa->PlaceHolder = RemoveHtml($this->activa->caption());

            // Add refer script

            // clase
            $this->clase->LinkCustomAttributes = "";
            $this->clase->HrefValue = "";

            // grupo
            $this->grupo->LinkCustomAttributes = "";
            $this->grupo->HrefValue = "";

            // cuenta
            $this->cuenta->LinkCustomAttributes = "";
            $this->cuenta->HrefValue = "";

            // subcuenta
            $this->subcuenta->LinkCustomAttributes = "";
            $this->subcuenta->HrefValue = "";

            // descripcion
            $this->descripcion->LinkCustomAttributes = "";
            $this->descripcion->HrefValue = "";

            // moneda
            $this->moneda->LinkCustomAttributes = "";
            $this->moneda->HrefValue = "";

            // activa
            $this->activa->LinkCustomAttributes = "";
            $this->activa->HrefValue = "";
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
        if ($this->clase->Required) {
            if (!$this->clase->IsDetailKey && EmptyValue($this->clase->FormValue)) {
                $this->clase->addErrorMessage(str_replace("%s", $this->clase->caption(), $this->clase->RequiredErrorMessage));
            }
        }
        if ($this->grupo->Required) {
            if (!$this->grupo->IsDetailKey && EmptyValue($this->grupo->FormValue)) {
                $this->grupo->addErrorMessage(str_replace("%s", $this->grupo->caption(), $this->grupo->RequiredErrorMessage));
            }
        }
        if ($this->cuenta->Required) {
            if (!$this->cuenta->IsDetailKey && EmptyValue($this->cuenta->FormValue)) {
                $this->cuenta->addErrorMessage(str_replace("%s", $this->cuenta->caption(), $this->cuenta->RequiredErrorMessage));
            }
        }
        if ($this->subcuenta->Required) {
            if (!$this->subcuenta->IsDetailKey && EmptyValue($this->subcuenta->FormValue)) {
                $this->subcuenta->addErrorMessage(str_replace("%s", $this->subcuenta->caption(), $this->subcuenta->RequiredErrorMessage));
            }
        }
        if ($this->descripcion->Required) {
            if (!$this->descripcion->IsDetailKey && EmptyValue($this->descripcion->FormValue)) {
                $this->descripcion->addErrorMessage(str_replace("%s", $this->descripcion->caption(), $this->descripcion->RequiredErrorMessage));
            }
        }
        if ($this->moneda->Required) {
            if (!$this->moneda->IsDetailKey && EmptyValue($this->moneda->FormValue)) {
                $this->moneda->addErrorMessage(str_replace("%s", $this->moneda->caption(), $this->moneda->RequiredErrorMessage));
            }
        }
        if ($this->activa->Required) {
            if ($this->activa->FormValue == "") {
                $this->activa->addErrorMessage(str_replace("%s", $this->activa->caption(), $this->activa->RequiredErrorMessage));
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

        // clase
        $this->clase->setDbValueDef($rsnew, $this->clase->CurrentValue, null, false);

        // grupo
        $this->grupo->setDbValueDef($rsnew, $this->grupo->CurrentValue, null, false);

        // cuenta
        $this->cuenta->setDbValueDef($rsnew, $this->cuenta->CurrentValue, null, false);

        // subcuenta
        $this->subcuenta->setDbValueDef($rsnew, $this->subcuenta->CurrentValue, null, false);

        // descripcion
        $this->descripcion->setDbValueDef($rsnew, $this->descripcion->CurrentValue, null, false);

        // moneda
        $this->moneda->setDbValueDef($rsnew, $this->moneda->CurrentValue, null, false);

        // activa
        $this->activa->setDbValueDef($rsnew, $this->activa->CurrentValue, null, false);

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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("ContPlanctaList"), "", $this->TableVar, true);
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
                case "x_clasificacion":
                    break;
                case "x_naturaleza":
                    break;
                case "x_tipo":
                    break;
                case "x_moneda":
                    $lookupFilter = function () {
                        return "`codigo` = '006'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    break;
                case "x_activa":
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
    public function pageDataRendering(&$header) {
    	// Example:
    	$header = '<div id="cuenta"></div>';
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
