<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class AsesorAdd extends Asesor
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'asesor';

    // Page object name
    public $PageObjName = "AsesorAdd";

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

        // Table object (asesor)
        if (!isset($GLOBALS["asesor"]) || get_class($GLOBALS["asesor"]) == PROJECT_NAMESPACE . "asesor") {
            $GLOBALS["asesor"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'asesor');
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
                $doc = new $class(Container("asesor"));
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
                    if ($pageName == "AsesorView") {
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
        $this->ci_rif->setVisibility();
        $this->nombre->setVisibility();
        $this->ciudad->setVisibility();
        $this->direccion->setVisibility();
        $this->telefono1->setVisibility();
        $this->telefono2->setVisibility();
        $this->email1->setVisibility();
        $this->email2->setVisibility();
        $this->web->setVisibility();
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
        $this->setupLookupOptions($this->ciudad);

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
                    $this->terminate("AsesorList"); // No matching record, return to list
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
                    if (GetPageName($returnUrl) == "AsesorList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "AsesorView") {
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
    }

    // Load default values
    protected function loadDefaultValues()
    {
        $this->id->CurrentValue = null;
        $this->id->OldValue = $this->id->CurrentValue;
        $this->ci_rif->CurrentValue = null;
        $this->ci_rif->OldValue = $this->ci_rif->CurrentValue;
        $this->nombre->CurrentValue = null;
        $this->nombre->OldValue = $this->nombre->CurrentValue;
        $this->ciudad->CurrentValue = null;
        $this->ciudad->OldValue = $this->ciudad->CurrentValue;
        $this->direccion->CurrentValue = null;
        $this->direccion->OldValue = $this->direccion->CurrentValue;
        $this->telefono1->CurrentValue = null;
        $this->telefono1->OldValue = $this->telefono1->CurrentValue;
        $this->telefono2->CurrentValue = null;
        $this->telefono2->OldValue = $this->telefono2->CurrentValue;
        $this->email1->CurrentValue = null;
        $this->email1->OldValue = $this->email1->CurrentValue;
        $this->email2->CurrentValue = null;
        $this->email2->OldValue = $this->email2->CurrentValue;
        $this->web->CurrentValue = null;
        $this->web->OldValue = $this->web->CurrentValue;
        $this->activo->CurrentValue = "S";
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;

        // Check field name 'ci_rif' first before field var 'x_ci_rif'
        $val = $CurrentForm->hasValue("ci_rif") ? $CurrentForm->getValue("ci_rif") : $CurrentForm->getValue("x_ci_rif");
        if (!$this->ci_rif->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ci_rif->Visible = false; // Disable update for API request
            } else {
                $this->ci_rif->setFormValue($val);
            }
        }

        // Check field name 'nombre' first before field var 'x_nombre'
        $val = $CurrentForm->hasValue("nombre") ? $CurrentForm->getValue("nombre") : $CurrentForm->getValue("x_nombre");
        if (!$this->nombre->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->nombre->Visible = false; // Disable update for API request
            } else {
                $this->nombre->setFormValue($val);
            }
        }

        // Check field name 'ciudad' first before field var 'x_ciudad'
        $val = $CurrentForm->hasValue("ciudad") ? $CurrentForm->getValue("ciudad") : $CurrentForm->getValue("x_ciudad");
        if (!$this->ciudad->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ciudad->Visible = false; // Disable update for API request
            } else {
                $this->ciudad->setFormValue($val);
            }
        }

        // Check field name 'direccion' first before field var 'x_direccion'
        $val = $CurrentForm->hasValue("direccion") ? $CurrentForm->getValue("direccion") : $CurrentForm->getValue("x_direccion");
        if (!$this->direccion->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->direccion->Visible = false; // Disable update for API request
            } else {
                $this->direccion->setFormValue($val);
            }
        }

        // Check field name 'telefono1' first before field var 'x_telefono1'
        $val = $CurrentForm->hasValue("telefono1") ? $CurrentForm->getValue("telefono1") : $CurrentForm->getValue("x_telefono1");
        if (!$this->telefono1->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->telefono1->Visible = false; // Disable update for API request
            } else {
                $this->telefono1->setFormValue($val);
            }
        }

        // Check field name 'telefono2' first before field var 'x_telefono2'
        $val = $CurrentForm->hasValue("telefono2") ? $CurrentForm->getValue("telefono2") : $CurrentForm->getValue("x_telefono2");
        if (!$this->telefono2->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->telefono2->Visible = false; // Disable update for API request
            } else {
                $this->telefono2->setFormValue($val);
            }
        }

        // Check field name 'email1' first before field var 'x_email1'
        $val = $CurrentForm->hasValue("email1") ? $CurrentForm->getValue("email1") : $CurrentForm->getValue("x_email1");
        if (!$this->email1->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->email1->Visible = false; // Disable update for API request
            } else {
                $this->email1->setFormValue($val);
            }
        }

        // Check field name 'email2' first before field var 'x_email2'
        $val = $CurrentForm->hasValue("email2") ? $CurrentForm->getValue("email2") : $CurrentForm->getValue("x_email2");
        if (!$this->email2->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->email2->Visible = false; // Disable update for API request
            } else {
                $this->email2->setFormValue($val);
            }
        }

        // Check field name 'web' first before field var 'x_web'
        $val = $CurrentForm->hasValue("web") ? $CurrentForm->getValue("web") : $CurrentForm->getValue("x_web");
        if (!$this->web->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->web->Visible = false; // Disable update for API request
            } else {
                $this->web->setFormValue($val);
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
        $this->ci_rif->CurrentValue = $this->ci_rif->FormValue;
        $this->nombre->CurrentValue = $this->nombre->FormValue;
        $this->ciudad->CurrentValue = $this->ciudad->FormValue;
        $this->direccion->CurrentValue = $this->direccion->FormValue;
        $this->telefono1->CurrentValue = $this->telefono1->FormValue;
        $this->telefono2->CurrentValue = $this->telefono2->FormValue;
        $this->email1->CurrentValue = $this->email1->FormValue;
        $this->email2->CurrentValue = $this->email2->FormValue;
        $this->web->CurrentValue = $this->web->FormValue;
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
        $this->ci_rif->setDbValue($row['ci_rif']);
        $this->nombre->setDbValue($row['nombre']);
        $this->ciudad->setDbValue($row['ciudad']);
        $this->direccion->setDbValue($row['direccion']);
        $this->telefono1->setDbValue($row['telefono1']);
        $this->telefono2->setDbValue($row['telefono2']);
        $this->email1->setDbValue($row['email1']);
        $this->email2->setDbValue($row['email2']);
        $this->web->setDbValue($row['web']);
        $this->activo->setDbValue($row['activo']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $this->loadDefaultValues();
        $row = [];
        $row['id'] = $this->id->CurrentValue;
        $row['ci_rif'] = $this->ci_rif->CurrentValue;
        $row['nombre'] = $this->nombre->CurrentValue;
        $row['ciudad'] = $this->ciudad->CurrentValue;
        $row['direccion'] = $this->direccion->CurrentValue;
        $row['telefono1'] = $this->telefono1->CurrentValue;
        $row['telefono2'] = $this->telefono2->CurrentValue;
        $row['email1'] = $this->email1->CurrentValue;
        $row['email2'] = $this->email2->CurrentValue;
        $row['web'] = $this->web->CurrentValue;
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

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // id

        // ci_rif

        // nombre

        // ciudad

        // direccion

        // telefono1

        // telefono2

        // email1

        // email2

        // web

        // activo
        if ($this->RowType == ROWTYPE_VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;
            $this->id->ViewCustomAttributes = "";

            // ci_rif
            $this->ci_rif->ViewValue = $this->ci_rif->CurrentValue;
            $this->ci_rif->ViewCustomAttributes = "";

            // nombre
            $this->nombre->ViewValue = $this->nombre->CurrentValue;
            $this->nombre->ViewCustomAttributes = "";

            // ciudad
            $this->ciudad->ViewValue = $this->ciudad->CurrentValue;
            $curVal = trim(strval($this->ciudad->CurrentValue));
            if ($curVal != "") {
                $this->ciudad->ViewValue = $this->ciudad->lookupCacheOption($curVal);
                if ($this->ciudad->ViewValue === null) { // Lookup from database
                    $filterWrk = "`campo_codigo`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $lookupFilter = function() {
                        return "`tabla` = 'CIUDAD'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    $sqlWrk = $this->ciudad->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->ciudad->Lookup->renderViewRow($rswrk[0]);
                        $this->ciudad->ViewValue = $this->ciudad->displayValue($arwrk);
                    } else {
                        $this->ciudad->ViewValue = $this->ciudad->CurrentValue;
                    }
                }
            } else {
                $this->ciudad->ViewValue = null;
            }
            $this->ciudad->ViewCustomAttributes = "";

            // direccion
            $this->direccion->ViewValue = $this->direccion->CurrentValue;
            $this->direccion->ViewCustomAttributes = "";

            // telefono1
            $this->telefono1->ViewValue = $this->telefono1->CurrentValue;
            $this->telefono1->ViewCustomAttributes = "";

            // telefono2
            $this->telefono2->ViewValue = $this->telefono2->CurrentValue;
            $this->telefono2->ViewCustomAttributes = "";

            // email1
            $this->email1->ViewValue = $this->email1->CurrentValue;
            $this->email1->ViewCustomAttributes = "";

            // email2
            $this->email2->ViewValue = $this->email2->CurrentValue;
            $this->email2->ViewCustomAttributes = "";

            // web
            $this->web->ViewValue = $this->web->CurrentValue;
            $this->web->ViewCustomAttributes = "";

            // activo
            if (strval($this->activo->CurrentValue) != "") {
                $this->activo->ViewValue = $this->activo->optionCaption($this->activo->CurrentValue);
            } else {
                $this->activo->ViewValue = null;
            }
            $this->activo->ViewCustomAttributes = "";

            // ci_rif
            $this->ci_rif->LinkCustomAttributes = "";
            $this->ci_rif->HrefValue = "";
            $this->ci_rif->TooltipValue = "";

            // nombre
            $this->nombre->LinkCustomAttributes = "";
            $this->nombre->HrefValue = "";
            $this->nombre->TooltipValue = "";

            // ciudad
            $this->ciudad->LinkCustomAttributes = "";
            $this->ciudad->HrefValue = "";
            $this->ciudad->TooltipValue = "";

            // direccion
            $this->direccion->LinkCustomAttributes = "";
            $this->direccion->HrefValue = "";
            $this->direccion->TooltipValue = "";

            // telefono1
            $this->telefono1->LinkCustomAttributes = "";
            $this->telefono1->HrefValue = "";
            $this->telefono1->TooltipValue = "";

            // telefono2
            $this->telefono2->LinkCustomAttributes = "";
            $this->telefono2->HrefValue = "";
            $this->telefono2->TooltipValue = "";

            // email1
            $this->email1->LinkCustomAttributes = "";
            $this->email1->HrefValue = "";
            $this->email1->TooltipValue = "";

            // email2
            $this->email2->LinkCustomAttributes = "";
            $this->email2->HrefValue = "";
            $this->email2->TooltipValue = "";

            // web
            $this->web->LinkCustomAttributes = "";
            $this->web->HrefValue = "";
            $this->web->TooltipValue = "";

            // activo
            $this->activo->LinkCustomAttributes = "";
            $this->activo->HrefValue = "";
            $this->activo->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // ci_rif
            $this->ci_rif->EditAttrs["class"] = "form-control";
            $this->ci_rif->EditCustomAttributes = "";
            if (!$this->ci_rif->Raw) {
                $this->ci_rif->CurrentValue = HtmlDecode($this->ci_rif->CurrentValue);
            }
            $this->ci_rif->EditValue = HtmlEncode($this->ci_rif->CurrentValue);
            $this->ci_rif->PlaceHolder = RemoveHtml($this->ci_rif->caption());

            // nombre
            $this->nombre->EditAttrs["class"] = "form-control";
            $this->nombre->EditCustomAttributes = "";
            if (!$this->nombre->Raw) {
                $this->nombre->CurrentValue = HtmlDecode($this->nombre->CurrentValue);
            }
            $this->nombre->EditValue = HtmlEncode($this->nombre->CurrentValue);
            $this->nombre->PlaceHolder = RemoveHtml($this->nombre->caption());

            // ciudad
            $this->ciudad->EditAttrs["class"] = "form-control";
            $this->ciudad->EditCustomAttributes = "";
            if (!$this->ciudad->Raw) {
                $this->ciudad->CurrentValue = HtmlDecode($this->ciudad->CurrentValue);
            }
            $this->ciudad->EditValue = HtmlEncode($this->ciudad->CurrentValue);
            $curVal = trim(strval($this->ciudad->CurrentValue));
            if ($curVal != "") {
                $this->ciudad->EditValue = $this->ciudad->lookupCacheOption($curVal);
                if ($this->ciudad->EditValue === null) { // Lookup from database
                    $filterWrk = "`campo_codigo`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $lookupFilter = function() {
                        return "`tabla` = 'CIUDAD'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    $sqlWrk = $this->ciudad->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->ciudad->Lookup->renderViewRow($rswrk[0]);
                        $this->ciudad->EditValue = $this->ciudad->displayValue($arwrk);
                    } else {
                        $this->ciudad->EditValue = HtmlEncode($this->ciudad->CurrentValue);
                    }
                }
            } else {
                $this->ciudad->EditValue = null;
            }
            $this->ciudad->PlaceHolder = RemoveHtml($this->ciudad->caption());

            // direccion
            $this->direccion->EditAttrs["class"] = "form-control";
            $this->direccion->EditCustomAttributes = "";
            $this->direccion->EditValue = HtmlEncode($this->direccion->CurrentValue);
            $this->direccion->PlaceHolder = RemoveHtml($this->direccion->caption());

            // telefono1
            $this->telefono1->EditAttrs["class"] = "form-control";
            $this->telefono1->EditCustomAttributes = "";
            if (!$this->telefono1->Raw) {
                $this->telefono1->CurrentValue = HtmlDecode($this->telefono1->CurrentValue);
            }
            $this->telefono1->EditValue = HtmlEncode($this->telefono1->CurrentValue);
            $this->telefono1->PlaceHolder = RemoveHtml($this->telefono1->caption());

            // telefono2
            $this->telefono2->EditAttrs["class"] = "form-control";
            $this->telefono2->EditCustomAttributes = "";
            if (!$this->telefono2->Raw) {
                $this->telefono2->CurrentValue = HtmlDecode($this->telefono2->CurrentValue);
            }
            $this->telefono2->EditValue = HtmlEncode($this->telefono2->CurrentValue);
            $this->telefono2->PlaceHolder = RemoveHtml($this->telefono2->caption());

            // email1
            $this->email1->EditAttrs["class"] = "form-control";
            $this->email1->EditCustomAttributes = "";
            if (!$this->email1->Raw) {
                $this->email1->CurrentValue = HtmlDecode($this->email1->CurrentValue);
            }
            $this->email1->EditValue = HtmlEncode($this->email1->CurrentValue);
            $this->email1->PlaceHolder = RemoveHtml($this->email1->caption());

            // email2
            $this->email2->EditAttrs["class"] = "form-control";
            $this->email2->EditCustomAttributes = "";
            if (!$this->email2->Raw) {
                $this->email2->CurrentValue = HtmlDecode($this->email2->CurrentValue);
            }
            $this->email2->EditValue = HtmlEncode($this->email2->CurrentValue);
            $this->email2->PlaceHolder = RemoveHtml($this->email2->caption());

            // web
            $this->web->EditAttrs["class"] = "form-control";
            $this->web->EditCustomAttributes = "";
            if (!$this->web->Raw) {
                $this->web->CurrentValue = HtmlDecode($this->web->CurrentValue);
            }
            $this->web->EditValue = HtmlEncode($this->web->CurrentValue);
            $this->web->PlaceHolder = RemoveHtml($this->web->caption());

            // activo
            $this->activo->EditAttrs["class"] = "form-control";
            $this->activo->EditCustomAttributes = "";
            $this->activo->EditValue = $this->activo->options(true);
            $this->activo->PlaceHolder = RemoveHtml($this->activo->caption());

            // Add refer script

            // ci_rif
            $this->ci_rif->LinkCustomAttributes = "";
            $this->ci_rif->HrefValue = "";

            // nombre
            $this->nombre->LinkCustomAttributes = "";
            $this->nombre->HrefValue = "";

            // ciudad
            $this->ciudad->LinkCustomAttributes = "";
            $this->ciudad->HrefValue = "";

            // direccion
            $this->direccion->LinkCustomAttributes = "";
            $this->direccion->HrefValue = "";

            // telefono1
            $this->telefono1->LinkCustomAttributes = "";
            $this->telefono1->HrefValue = "";

            // telefono2
            $this->telefono2->LinkCustomAttributes = "";
            $this->telefono2->HrefValue = "";

            // email1
            $this->email1->LinkCustomAttributes = "";
            $this->email1->HrefValue = "";

            // email2
            $this->email2->LinkCustomAttributes = "";
            $this->email2->HrefValue = "";

            // web
            $this->web->LinkCustomAttributes = "";
            $this->web->HrefValue = "";

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
        if ($this->ci_rif->Required) {
            if (!$this->ci_rif->IsDetailKey && EmptyValue($this->ci_rif->FormValue)) {
                $this->ci_rif->addErrorMessage(str_replace("%s", $this->ci_rif->caption(), $this->ci_rif->RequiredErrorMessage));
            }
        }
        if ($this->nombre->Required) {
            if (!$this->nombre->IsDetailKey && EmptyValue($this->nombre->FormValue)) {
                $this->nombre->addErrorMessage(str_replace("%s", $this->nombre->caption(), $this->nombre->RequiredErrorMessage));
            }
        }
        if ($this->ciudad->Required) {
            if (!$this->ciudad->IsDetailKey && EmptyValue($this->ciudad->FormValue)) {
                $this->ciudad->addErrorMessage(str_replace("%s", $this->ciudad->caption(), $this->ciudad->RequiredErrorMessage));
            }
        }
        if ($this->direccion->Required) {
            if (!$this->direccion->IsDetailKey && EmptyValue($this->direccion->FormValue)) {
                $this->direccion->addErrorMessage(str_replace("%s", $this->direccion->caption(), $this->direccion->RequiredErrorMessage));
            }
        }
        if ($this->telefono1->Required) {
            if (!$this->telefono1->IsDetailKey && EmptyValue($this->telefono1->FormValue)) {
                $this->telefono1->addErrorMessage(str_replace("%s", $this->telefono1->caption(), $this->telefono1->RequiredErrorMessage));
            }
        }
        if ($this->telefono2->Required) {
            if (!$this->telefono2->IsDetailKey && EmptyValue($this->telefono2->FormValue)) {
                $this->telefono2->addErrorMessage(str_replace("%s", $this->telefono2->caption(), $this->telefono2->RequiredErrorMessage));
            }
        }
        if ($this->email1->Required) {
            if (!$this->email1->IsDetailKey && EmptyValue($this->email1->FormValue)) {
                $this->email1->addErrorMessage(str_replace("%s", $this->email1->caption(), $this->email1->RequiredErrorMessage));
            }
        }
        if (!CheckEmail($this->email1->FormValue)) {
            $this->email1->addErrorMessage($this->email1->getErrorMessage(false));
        }
        if ($this->email2->Required) {
            if (!$this->email2->IsDetailKey && EmptyValue($this->email2->FormValue)) {
                $this->email2->addErrorMessage(str_replace("%s", $this->email2->caption(), $this->email2->RequiredErrorMessage));
            }
        }
        if (!CheckEmail($this->email2->FormValue)) {
            $this->email2->addErrorMessage($this->email2->getErrorMessage(false));
        }
        if ($this->web->Required) {
            if (!$this->web->IsDetailKey && EmptyValue($this->web->FormValue)) {
                $this->web->addErrorMessage(str_replace("%s", $this->web->caption(), $this->web->RequiredErrorMessage));
            }
        }
        if ($this->activo->Required) {
            if (!$this->activo->IsDetailKey && EmptyValue($this->activo->FormValue)) {
                $this->activo->addErrorMessage(str_replace("%s", $this->activo->caption(), $this->activo->RequiredErrorMessage));
            }
        }

        // Validate detail grid
        $detailTblVar = explode(",", $this->getCurrentDetailTable());
        $detailPage = Container("AsesorClienteGrid");
        if (in_array("asesor_cliente", $detailTblVar) && $detailPage->DetailAdd) {
            $detailPage->validateGridForm();
        }
        $detailPage = Container("AsesorFabricanteGrid");
        if (in_array("asesor_fabricante", $detailTblVar) && $detailPage->DetailAdd) {
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

        // ci_rif
        $this->ci_rif->setDbValueDef($rsnew, $this->ci_rif->CurrentValue, null, false);

        // nombre
        $this->nombre->setDbValueDef($rsnew, $this->nombre->CurrentValue, null, false);

        // ciudad
        $this->ciudad->setDbValueDef($rsnew, $this->ciudad->CurrentValue, null, false);

        // direccion
        $this->direccion->setDbValueDef($rsnew, $this->direccion->CurrentValue, null, false);

        // telefono1
        $this->telefono1->setDbValueDef($rsnew, $this->telefono1->CurrentValue, null, false);

        // telefono2
        $this->telefono2->setDbValueDef($rsnew, $this->telefono2->CurrentValue, null, false);

        // email1
        $this->email1->setDbValueDef($rsnew, $this->email1->CurrentValue, null, false);

        // email2
        $this->email2->setDbValueDef($rsnew, $this->email2->CurrentValue, null, false);

        // web
        $this->web->setDbValueDef($rsnew, $this->web->CurrentValue, null, false);

        // activo
        $this->activo->setDbValueDef($rsnew, $this->activo->CurrentValue, null, strval($this->activo->CurrentValue) == "");

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

        // Add detail records
        if ($addRow) {
            $detailTblVar = explode(",", $this->getCurrentDetailTable());
            $detailPage = Container("AsesorClienteGrid");
            if (in_array("asesor_cliente", $detailTblVar) && $detailPage->DetailAdd) {
                $detailPage->asesor->setSessionValue($this->id->CurrentValue); // Set master key
                $Security->loadCurrentUserLevel($this->ProjectID . "asesor_cliente"); // Load user level of detail table
                $addRow = $detailPage->gridInsert();
                $Security->loadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
                if (!$addRow) {
                $detailPage->asesor->setSessionValue(""); // Clear master key if insert failed
                }
            }
            $detailPage = Container("AsesorFabricanteGrid");
            if (in_array("asesor_fabricante", $detailTblVar) && $detailPage->DetailAdd) {
                $detailPage->asesor->setSessionValue($this->id->CurrentValue); // Set master key
                $Security->loadCurrentUserLevel($this->ProjectID . "asesor_fabricante"); // Load user level of detail table
                $addRow = $detailPage->gridInsert();
                $Security->loadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
                if (!$addRow) {
                $detailPage->asesor->setSessionValue(""); // Clear master key if insert failed
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
            if (in_array("asesor_cliente", $detailTblVar)) {
                $detailPageObj = Container("AsesorClienteGrid");
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
                    $detailPageObj->asesor->IsDetailKey = true;
                    $detailPageObj->asesor->CurrentValue = $this->id->CurrentValue;
                    $detailPageObj->asesor->setSessionValue($detailPageObj->asesor->CurrentValue);
                }
            }
            if (in_array("asesor_fabricante", $detailTblVar)) {
                $detailPageObj = Container("AsesorFabricanteGrid");
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
                    $detailPageObj->asesor->IsDetailKey = true;
                    $detailPageObj->asesor->CurrentValue = $this->id->CurrentValue;
                    $detailPageObj->asesor->setSessionValue($detailPageObj->asesor->CurrentValue);
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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("AsesorList"), "", $this->TableVar, true);
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
                case "x_ciudad":
                    $lookupFilter = function () {
                        return "`tabla` = 'CIUDAD'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
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
