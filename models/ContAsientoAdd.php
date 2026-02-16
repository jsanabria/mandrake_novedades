<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class ContAsientoAdd extends ContAsiento
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'cont_asiento';

    // Page object name
    public $PageObjName = "ContAsientoAdd";

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

        // Table object (cont_asiento)
        if (!isset($GLOBALS["cont_asiento"]) || get_class($GLOBALS["cont_asiento"]) == PROJECT_NAMESPACE . "cont_asiento") {
            $GLOBALS["cont_asiento"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'cont_asiento');
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
                $doc = new $class(Container("cont_asiento"));
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
                    if ($pageName == "ContAsientoView") {
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
        $this->comprobante->setVisibility();
        $this->cuenta->setVisibility();
        $this->nota->setVisibility();
        $this->referencia->setVisibility();
        $this->debe->setVisibility();
        $this->haber->setVisibility();
        $this->id_referencia->Visible = false;
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
        $this->setupLookupOptions($this->cuenta);

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

        // Set up master/detail parameters
        // NOTE: must be after loadOldRecord to prevent master key values overwritten
        $this->setupMasterParms();

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
                    $this->terminate("ContAsientoList"); // No matching record, return to list
                    return;
                }
                break;
            case "insert": // Add new record
                $this->SendEmail = true; // Send email on add success
                if ($this->addRow($this->OldRecordset)) { // Add successful
                    if ($this->getSuccessMessage() == "" && Post("addopt") != "1") { // Skip success message for addopt (done in JavaScript)
                        $this->setSuccessMessage($Language->phrase("AddSuccess")); // Set up success message
                    }
                    $returnUrl = "ContAsientoList";
                    if (GetPageName($returnUrl) == "ContAsientoList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "ContAsientoView") {
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
        $this->comprobante->CurrentValue = null;
        $this->comprobante->OldValue = $this->comprobante->CurrentValue;
        $this->cuenta->CurrentValue = null;
        $this->cuenta->OldValue = $this->cuenta->CurrentValue;
        $this->nota->CurrentValue = null;
        $this->nota->OldValue = $this->nota->CurrentValue;
        $this->referencia->CurrentValue = null;
        $this->referencia->OldValue = $this->referencia->CurrentValue;
        $this->debe->CurrentValue = null;
        $this->debe->OldValue = $this->debe->CurrentValue;
        $this->haber->CurrentValue = null;
        $this->haber->OldValue = $this->haber->CurrentValue;
        $this->id_referencia->CurrentValue = 0;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;

        // Check field name 'comprobante' first before field var 'x_comprobante'
        $val = $CurrentForm->hasValue("comprobante") ? $CurrentForm->getValue("comprobante") : $CurrentForm->getValue("x_comprobante");
        if (!$this->comprobante->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->comprobante->Visible = false; // Disable update for API request
            } else {
                $this->comprobante->setFormValue($val);
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

        // Check field name 'nota' first before field var 'x_nota'
        $val = $CurrentForm->hasValue("nota") ? $CurrentForm->getValue("nota") : $CurrentForm->getValue("x_nota");
        if (!$this->nota->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->nota->Visible = false; // Disable update for API request
            } else {
                $this->nota->setFormValue($val);
            }
        }

        // Check field name 'referencia' first before field var 'x_referencia'
        $val = $CurrentForm->hasValue("referencia") ? $CurrentForm->getValue("referencia") : $CurrentForm->getValue("x_referencia");
        if (!$this->referencia->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->referencia->Visible = false; // Disable update for API request
            } else {
                $this->referencia->setFormValue($val);
            }
        }

        // Check field name 'debe' first before field var 'x_debe'
        $val = $CurrentForm->hasValue("debe") ? $CurrentForm->getValue("debe") : $CurrentForm->getValue("x_debe");
        if (!$this->debe->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->debe->Visible = false; // Disable update for API request
            } else {
                $this->debe->setFormValue($val);
            }
        }

        // Check field name 'haber' first before field var 'x_haber'
        $val = $CurrentForm->hasValue("haber") ? $CurrentForm->getValue("haber") : $CurrentForm->getValue("x_haber");
        if (!$this->haber->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->haber->Visible = false; // Disable update for API request
            } else {
                $this->haber->setFormValue($val);
            }
        }

        // Check field name 'id' first before field var 'x_id'
        $val = $CurrentForm->hasValue("id") ? $CurrentForm->getValue("id") : $CurrentForm->getValue("x_id");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->comprobante->CurrentValue = $this->comprobante->FormValue;
        $this->cuenta->CurrentValue = $this->cuenta->FormValue;
        $this->nota->CurrentValue = $this->nota->FormValue;
        $this->referencia->CurrentValue = $this->referencia->FormValue;
        $this->debe->CurrentValue = $this->debe->FormValue;
        $this->haber->CurrentValue = $this->haber->FormValue;
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
        $this->comprobante->setDbValue($row['comprobante']);
        $this->cuenta->setDbValue($row['cuenta']);
        $this->nota->setDbValue($row['nota']);
        $this->referencia->setDbValue($row['referencia']);
        $this->debe->setDbValue($row['debe']);
        $this->haber->setDbValue($row['haber']);
        $this->id_referencia->setDbValue($row['id_referencia']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $this->loadDefaultValues();
        $row = [];
        $row['id'] = $this->id->CurrentValue;
        $row['comprobante'] = $this->comprobante->CurrentValue;
        $row['cuenta'] = $this->cuenta->CurrentValue;
        $row['nota'] = $this->nota->CurrentValue;
        $row['referencia'] = $this->referencia->CurrentValue;
        $row['debe'] = $this->debe->CurrentValue;
        $row['haber'] = $this->haber->CurrentValue;
        $row['id_referencia'] = $this->id_referencia->CurrentValue;
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
        if ($this->debe->FormValue == $this->debe->CurrentValue && is_numeric(ConvertToFloatString($this->debe->CurrentValue))) {
            $this->debe->CurrentValue = ConvertToFloatString($this->debe->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->haber->FormValue == $this->haber->CurrentValue && is_numeric(ConvertToFloatString($this->haber->CurrentValue))) {
            $this->haber->CurrentValue = ConvertToFloatString($this->haber->CurrentValue);
        }

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // id

        // comprobante

        // cuenta

        // nota

        // referencia

        // debe

        // haber

        // id_referencia
        if ($this->RowType == ROWTYPE_VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;
            $this->id->ViewCustomAttributes = "";

            // comprobante
            $this->comprobante->ViewValue = $this->comprobante->CurrentValue;
            $this->comprobante->ViewCustomAttributes = "";

            // cuenta
            $curVal = trim(strval($this->cuenta->CurrentValue));
            if ($curVal != "") {
                $this->cuenta->ViewValue = $this->cuenta->lookupCacheOption($curVal);
                if ($this->cuenta->ViewValue === null) { // Lookup from database
                    $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->cuenta->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->cuenta->Lookup->renderViewRow($rswrk[0]);
                        $this->cuenta->ViewValue = $this->cuenta->displayValue($arwrk);
                    } else {
                        $this->cuenta->ViewValue = $this->cuenta->CurrentValue;
                    }
                }
            } else {
                $this->cuenta->ViewValue = null;
            }
            $this->cuenta->ViewCustomAttributes = "";

            // nota
            $this->nota->ViewValue = $this->nota->CurrentValue;
            $this->nota->ViewCustomAttributes = "";

            // referencia
            $this->referencia->ViewValue = $this->referencia->CurrentValue;
            $this->referencia->ViewCustomAttributes = "";

            // debe
            $this->debe->ViewValue = $this->debe->CurrentValue;
            $this->debe->ViewValue = FormatNumber($this->debe->ViewValue, 2, -1, -1, -1);
            $this->debe->CellCssStyle .= "text-align: right;";
            $this->debe->ViewCustomAttributes = "";

            // haber
            $this->haber->ViewValue = $this->haber->CurrentValue;
            $this->haber->ViewValue = FormatNumber($this->haber->ViewValue, 2, -1, -1, -1);
            $this->haber->CellCssStyle .= "text-align: right;";
            $this->haber->ViewCustomAttributes = "";

            // id_referencia
            $this->id_referencia->ViewValue = $this->id_referencia->CurrentValue;
            $this->id_referencia->ViewCustomAttributes = "";

            // comprobante
            $this->comprobante->LinkCustomAttributes = "";
            $this->comprobante->HrefValue = "";
            $this->comprobante->TooltipValue = "";

            // cuenta
            $this->cuenta->LinkCustomAttributes = "";
            $this->cuenta->HrefValue = "";
            $this->cuenta->TooltipValue = "";

            // nota
            $this->nota->LinkCustomAttributes = "";
            $this->nota->HrefValue = "";
            $this->nota->TooltipValue = "";

            // referencia
            $this->referencia->LinkCustomAttributes = "";
            $this->referencia->HrefValue = "";
            $this->referencia->TooltipValue = "";

            // debe
            $this->debe->LinkCustomAttributes = "";
            $this->debe->HrefValue = "";
            $this->debe->TooltipValue = "";

            // haber
            $this->haber->LinkCustomAttributes = "";
            $this->haber->HrefValue = "";
            $this->haber->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // comprobante
            $this->comprobante->EditAttrs["class"] = "form-control";
            $this->comprobante->EditCustomAttributes = "";
            if ($this->comprobante->getSessionValue() != "") {
                $this->comprobante->CurrentValue = GetForeignKeyValue($this->comprobante->getSessionValue());
                $this->comprobante->ViewValue = $this->comprobante->CurrentValue;
                $this->comprobante->ViewCustomAttributes = "";
            } else {
                $this->comprobante->EditValue = HtmlEncode($this->comprobante->CurrentValue);
                $this->comprobante->PlaceHolder = RemoveHtml($this->comprobante->caption());
            }

            // cuenta
            $this->cuenta->EditCustomAttributes = "";
            $curVal = trim(strval($this->cuenta->CurrentValue));
            if ($curVal != "") {
                $this->cuenta->ViewValue = $this->cuenta->lookupCacheOption($curVal);
            } else {
                $this->cuenta->ViewValue = $this->cuenta->Lookup !== null && is_array($this->cuenta->Lookup->Options) ? $curVal : null;
            }
            if ($this->cuenta->ViewValue !== null) { // Load from cache
                $this->cuenta->EditValue = array_values($this->cuenta->Lookup->Options);
                if ($this->cuenta->ViewValue == "") {
                    $this->cuenta->ViewValue = $Language->phrase("PleaseSelect");
                }
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`id`" . SearchString("=", $this->cuenta->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->cuenta->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->cuenta->Lookup->renderViewRow($rswrk[0]);
                    $this->cuenta->ViewValue = $this->cuenta->displayValue($arwrk);
                } else {
                    $this->cuenta->ViewValue = $Language->phrase("PleaseSelect");
                }
                $arwrk = $rswrk;
                $this->cuenta->EditValue = $arwrk;
            }
            $this->cuenta->PlaceHolder = RemoveHtml($this->cuenta->caption());

            // nota
            $this->nota->EditAttrs["class"] = "form-control";
            $this->nota->EditCustomAttributes = "";
            if (!$this->nota->Raw) {
                $this->nota->CurrentValue = HtmlDecode($this->nota->CurrentValue);
            }
            $this->nota->EditValue = HtmlEncode($this->nota->CurrentValue);
            $this->nota->PlaceHolder = RemoveHtml($this->nota->caption());

            // referencia
            $this->referencia->EditAttrs["class"] = "form-control";
            $this->referencia->EditCustomAttributes = "";
            if (!$this->referencia->Raw) {
                $this->referencia->CurrentValue = HtmlDecode($this->referencia->CurrentValue);
            }
            $this->referencia->EditValue = HtmlEncode($this->referencia->CurrentValue);
            $this->referencia->PlaceHolder = RemoveHtml($this->referencia->caption());

            // debe
            $this->debe->EditAttrs["class"] = "form-control";
            $this->debe->EditCustomAttributes = "";
            $this->debe->EditValue = HtmlEncode($this->debe->CurrentValue);
            $this->debe->PlaceHolder = RemoveHtml($this->debe->caption());
            if (strval($this->debe->EditValue) != "" && is_numeric($this->debe->EditValue)) {
                $this->debe->EditValue = FormatNumber($this->debe->EditValue, -2, -1, -2, -1);
            }

            // haber
            $this->haber->EditAttrs["class"] = "form-control";
            $this->haber->EditCustomAttributes = "";
            $this->haber->EditValue = HtmlEncode($this->haber->CurrentValue);
            $this->haber->PlaceHolder = RemoveHtml($this->haber->caption());
            if (strval($this->haber->EditValue) != "" && is_numeric($this->haber->EditValue)) {
                $this->haber->EditValue = FormatNumber($this->haber->EditValue, -2, -1, -2, -1);
            }

            // Add refer script

            // comprobante
            $this->comprobante->LinkCustomAttributes = "";
            $this->comprobante->HrefValue = "";

            // cuenta
            $this->cuenta->LinkCustomAttributes = "";
            $this->cuenta->HrefValue = "";

            // nota
            $this->nota->LinkCustomAttributes = "";
            $this->nota->HrefValue = "";

            // referencia
            $this->referencia->LinkCustomAttributes = "";
            $this->referencia->HrefValue = "";

            // debe
            $this->debe->LinkCustomAttributes = "";
            $this->debe->HrefValue = "";

            // haber
            $this->haber->LinkCustomAttributes = "";
            $this->haber->HrefValue = "";
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
        if ($this->comprobante->Required) {
            if (!$this->comprobante->IsDetailKey && EmptyValue($this->comprobante->FormValue)) {
                $this->comprobante->addErrorMessage(str_replace("%s", $this->comprobante->caption(), $this->comprobante->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->comprobante->FormValue)) {
            $this->comprobante->addErrorMessage($this->comprobante->getErrorMessage(false));
        }
        if ($this->cuenta->Required) {
            if (!$this->cuenta->IsDetailKey && EmptyValue($this->cuenta->FormValue)) {
                $this->cuenta->addErrorMessage(str_replace("%s", $this->cuenta->caption(), $this->cuenta->RequiredErrorMessage));
            }
        }
        if ($this->nota->Required) {
            if (!$this->nota->IsDetailKey && EmptyValue($this->nota->FormValue)) {
                $this->nota->addErrorMessage(str_replace("%s", $this->nota->caption(), $this->nota->RequiredErrorMessage));
            }
        }
        if ($this->referencia->Required) {
            if (!$this->referencia->IsDetailKey && EmptyValue($this->referencia->FormValue)) {
                $this->referencia->addErrorMessage(str_replace("%s", $this->referencia->caption(), $this->referencia->RequiredErrorMessage));
            }
        }
        if ($this->debe->Required) {
            if (!$this->debe->IsDetailKey && EmptyValue($this->debe->FormValue)) {
                $this->debe->addErrorMessage(str_replace("%s", $this->debe->caption(), $this->debe->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->debe->FormValue)) {
            $this->debe->addErrorMessage($this->debe->getErrorMessage(false));
        }
        if ($this->haber->Required) {
            if (!$this->haber->IsDetailKey && EmptyValue($this->haber->FormValue)) {
                $this->haber->addErrorMessage(str_replace("%s", $this->haber->caption(), $this->haber->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->haber->FormValue)) {
            $this->haber->addErrorMessage($this->haber->getErrorMessage(false));
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

        // Check referential integrity for master table 'cont_asiento'
        $validMasterRecord = true;
        $masterFilter = $this->sqlMasterFilter_cont_comprobante();
        if (strval($this->comprobante->CurrentValue) != "") {
            $masterFilter = str_replace("@id@", AdjustSql($this->comprobante->CurrentValue, "DB"), $masterFilter);
        } else {
            $validMasterRecord = false;
        }
        if ($validMasterRecord) {
            $rsmaster = Container("cont_comprobante")->loadRs($masterFilter)->fetch();
            $validMasterRecord = $rsmaster !== false;
        }
        if (!$validMasterRecord) {
            $relatedRecordMsg = str_replace("%t", "cont_comprobante", $Language->phrase("RelatedRecordRequired"));
            $this->setFailureMessage($relatedRecordMsg);
            return false;
        }
        $conn = $this->getConnection();

        // Load db values from rsold
        $this->loadDbValues($rsold);
        if ($rsold) {
        }
        $rsnew = [];

        // comprobante
        $this->comprobante->setDbValueDef($rsnew, $this->comprobante->CurrentValue, null, false);

        // cuenta
        $this->cuenta->setDbValueDef($rsnew, $this->cuenta->CurrentValue, null, false);

        // nota
        $this->nota->setDbValueDef($rsnew, $this->nota->CurrentValue, null, false);

        // referencia
        $this->referencia->setDbValueDef($rsnew, $this->referencia->CurrentValue, null, false);

        // debe
        $this->debe->setDbValueDef($rsnew, $this->debe->CurrentValue, null, false);

        // haber
        $this->haber->setDbValueDef($rsnew, $this->haber->CurrentValue, null, false);

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
            if ($masterTblVar == "cont_comprobante") {
                $validMaster = true;
                $masterTbl = Container("cont_comprobante");
                if (($parm = Get("fk_id", Get("comprobante"))) !== null) {
                    $masterTbl->id->setQueryStringValue($parm);
                    $this->comprobante->setQueryStringValue($masterTbl->id->QueryStringValue);
                    $this->comprobante->setSessionValue($this->comprobante->QueryStringValue);
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
            if ($masterTblVar == "cont_comprobante") {
                $validMaster = true;
                $masterTbl = Container("cont_comprobante");
                if (($parm = Post("fk_id", Post("comprobante"))) !== null) {
                    $masterTbl->id->setFormValue($parm);
                    $this->comprobante->setFormValue($masterTbl->id->FormValue);
                    $this->comprobante->setSessionValue($this->comprobante->FormValue);
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

            // Reset start record counter (new master key)
            if (!$this->isAddOrEdit()) {
                $this->StartRecord = 1;
                $this->setStartRecordNumber($this->StartRecord);
            }

            // Clear previous master key from Session
            if ($masterTblVar != "cont_comprobante") {
                if ($this->comprobante->CurrentValue == "") {
                    $this->comprobante->setSessionValue("");
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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("ContAsientoList"), "", $this->TableVar, true);
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
                case "x_cuenta":
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
