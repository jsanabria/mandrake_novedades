<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class ContMesContableEdit extends ContMesContable
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'cont_mes_contable';

    // Page object name
    public $PageObjName = "ContMesContableEdit";

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

        // Table object (cont_mes_contable)
        if (!isset($GLOBALS["cont_mes_contable"]) || get_class($GLOBALS["cont_mes_contable"]) == PROJECT_NAMESPACE . "cont_mes_contable") {
            $GLOBALS["cont_mes_contable"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'cont_mes_contable');
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
                $doc = new $class(Container("cont_mes_contable"));
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
                    if ($pageName == "ContMesContableView") {
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
        $this->tipo_comprobante->setVisibility();
        $this->descripcion->setVisibility();
        $this->M01->setVisibility();
        $this->M02->setVisibility();
        $this->M03->setVisibility();
        $this->M04->setVisibility();
        $this->M05->setVisibility();
        $this->M06->setVisibility();
        $this->M07->setVisibility();
        $this->M08->setVisibility();
        $this->M09->setVisibility();
        $this->M10->setVisibility();
        $this->M11->setVisibility();
        $this->M12->setVisibility();
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
                    $this->terminate("ContMesContableList"); // Return to list page
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
                    $this->terminate("ContMesContableList"); // Return to list page
                    return;
                } else {
                }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "ContMesContableList") {
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

        // Check field name 'tipo_comprobante' first before field var 'x_tipo_comprobante'
        $val = $CurrentForm->hasValue("tipo_comprobante") ? $CurrentForm->getValue("tipo_comprobante") : $CurrentForm->getValue("x_tipo_comprobante");
        if (!$this->tipo_comprobante->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->tipo_comprobante->Visible = false; // Disable update for API request
            } else {
                $this->tipo_comprobante->setFormValue($val);
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

        // Check field name 'M01' first before field var 'x_M01'
        $val = $CurrentForm->hasValue("M01") ? $CurrentForm->getValue("M01") : $CurrentForm->getValue("x_M01");
        if (!$this->M01->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->M01->Visible = false; // Disable update for API request
            } else {
                $this->M01->setFormValue($val);
            }
        }

        // Check field name 'M02' first before field var 'x_M02'
        $val = $CurrentForm->hasValue("M02") ? $CurrentForm->getValue("M02") : $CurrentForm->getValue("x_M02");
        if (!$this->M02->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->M02->Visible = false; // Disable update for API request
            } else {
                $this->M02->setFormValue($val);
            }
        }

        // Check field name 'M03' first before field var 'x_M03'
        $val = $CurrentForm->hasValue("M03") ? $CurrentForm->getValue("M03") : $CurrentForm->getValue("x_M03");
        if (!$this->M03->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->M03->Visible = false; // Disable update for API request
            } else {
                $this->M03->setFormValue($val);
            }
        }

        // Check field name 'M04' first before field var 'x_M04'
        $val = $CurrentForm->hasValue("M04") ? $CurrentForm->getValue("M04") : $CurrentForm->getValue("x_M04");
        if (!$this->M04->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->M04->Visible = false; // Disable update for API request
            } else {
                $this->M04->setFormValue($val);
            }
        }

        // Check field name 'M05' first before field var 'x_M05'
        $val = $CurrentForm->hasValue("M05") ? $CurrentForm->getValue("M05") : $CurrentForm->getValue("x_M05");
        if (!$this->M05->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->M05->Visible = false; // Disable update for API request
            } else {
                $this->M05->setFormValue($val);
            }
        }

        // Check field name 'M06' first before field var 'x_M06'
        $val = $CurrentForm->hasValue("M06") ? $CurrentForm->getValue("M06") : $CurrentForm->getValue("x_M06");
        if (!$this->M06->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->M06->Visible = false; // Disable update for API request
            } else {
                $this->M06->setFormValue($val);
            }
        }

        // Check field name 'M07' first before field var 'x_M07'
        $val = $CurrentForm->hasValue("M07") ? $CurrentForm->getValue("M07") : $CurrentForm->getValue("x_M07");
        if (!$this->M07->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->M07->Visible = false; // Disable update for API request
            } else {
                $this->M07->setFormValue($val);
            }
        }

        // Check field name 'M08' first before field var 'x_M08'
        $val = $CurrentForm->hasValue("M08") ? $CurrentForm->getValue("M08") : $CurrentForm->getValue("x_M08");
        if (!$this->M08->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->M08->Visible = false; // Disable update for API request
            } else {
                $this->M08->setFormValue($val);
            }
        }

        // Check field name 'M09' first before field var 'x_M09'
        $val = $CurrentForm->hasValue("M09") ? $CurrentForm->getValue("M09") : $CurrentForm->getValue("x_M09");
        if (!$this->M09->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->M09->Visible = false; // Disable update for API request
            } else {
                $this->M09->setFormValue($val);
            }
        }

        // Check field name 'M10' first before field var 'x_M10'
        $val = $CurrentForm->hasValue("M10") ? $CurrentForm->getValue("M10") : $CurrentForm->getValue("x_M10");
        if (!$this->M10->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->M10->Visible = false; // Disable update for API request
            } else {
                $this->M10->setFormValue($val);
            }
        }

        // Check field name 'M11' first before field var 'x_M11'
        $val = $CurrentForm->hasValue("M11") ? $CurrentForm->getValue("M11") : $CurrentForm->getValue("x_M11");
        if (!$this->M11->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->M11->Visible = false; // Disable update for API request
            } else {
                $this->M11->setFormValue($val);
            }
        }

        // Check field name 'M12' first before field var 'x_M12'
        $val = $CurrentForm->hasValue("M12") ? $CurrentForm->getValue("M12") : $CurrentForm->getValue("x_M12");
        if (!$this->M12->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->M12->Visible = false; // Disable update for API request
            } else {
                $this->M12->setFormValue($val);
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
        if (!$this->id->IsDetailKey) {
            $this->id->setFormValue($val);
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->id->CurrentValue = $this->id->FormValue;
        $this->tipo_comprobante->CurrentValue = $this->tipo_comprobante->FormValue;
        $this->descripcion->CurrentValue = $this->descripcion->FormValue;
        $this->M01->CurrentValue = $this->M01->FormValue;
        $this->M02->CurrentValue = $this->M02->FormValue;
        $this->M03->CurrentValue = $this->M03->FormValue;
        $this->M04->CurrentValue = $this->M04->FormValue;
        $this->M05->CurrentValue = $this->M05->FormValue;
        $this->M06->CurrentValue = $this->M06->FormValue;
        $this->M07->CurrentValue = $this->M07->FormValue;
        $this->M08->CurrentValue = $this->M08->FormValue;
        $this->M09->CurrentValue = $this->M09->FormValue;
        $this->M10->CurrentValue = $this->M10->FormValue;
        $this->M11->CurrentValue = $this->M11->FormValue;
        $this->M12->CurrentValue = $this->M12->FormValue;
        $this->activo->CurrentValue = $this->activo->FormValue;
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
        $this->tipo_comprobante->setDbValue($row['tipo_comprobante']);
        $this->descripcion->setDbValue($row['descripcion']);
        $this->M01->setDbValue($row['M01']);
        $this->M02->setDbValue($row['M02']);
        $this->M03->setDbValue($row['M03']);
        $this->M04->setDbValue($row['M04']);
        $this->M05->setDbValue($row['M05']);
        $this->M06->setDbValue($row['M06']);
        $this->M07->setDbValue($row['M07']);
        $this->M08->setDbValue($row['M08']);
        $this->M09->setDbValue($row['M09']);
        $this->M10->setDbValue($row['M10']);
        $this->M11->setDbValue($row['M11']);
        $this->M12->setDbValue($row['M12']);
        $this->activo->setDbValue($row['activo']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['id'] = null;
        $row['tipo_comprobante'] = null;
        $row['descripcion'] = null;
        $row['M01'] = null;
        $row['M02'] = null;
        $row['M03'] = null;
        $row['M04'] = null;
        $row['M05'] = null;
        $row['M06'] = null;
        $row['M07'] = null;
        $row['M08'] = null;
        $row['M09'] = null;
        $row['M10'] = null;
        $row['M11'] = null;
        $row['M12'] = null;
        $row['activo'] = null;
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

        // tipo_comprobante

        // descripcion

        // M01

        // M02

        // M03

        // M04

        // M05

        // M06

        // M07

        // M08

        // M09

        // M10

        // M11

        // M12

        // activo
        if ($this->RowType == ROWTYPE_VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;
            $this->id->ViewCustomAttributes = "";

            // tipo_comprobante
            $this->tipo_comprobante->ViewValue = $this->tipo_comprobante->CurrentValue;
            $this->tipo_comprobante->ViewCustomAttributes = "";

            // descripcion
            $this->descripcion->ViewValue = $this->descripcion->CurrentValue;
            $this->descripcion->ViewCustomAttributes = "";

            // M01
            if (strval($this->M01->CurrentValue) != "") {
                $this->M01->ViewValue = $this->M01->optionCaption($this->M01->CurrentValue);
            } else {
                $this->M01->ViewValue = null;
            }
            $this->M01->ViewCustomAttributes = "";

            // M02
            if (strval($this->M02->CurrentValue) != "") {
                $this->M02->ViewValue = $this->M02->optionCaption($this->M02->CurrentValue);
            } else {
                $this->M02->ViewValue = null;
            }
            $this->M02->ViewCustomAttributes = "";

            // M03
            if (strval($this->M03->CurrentValue) != "") {
                $this->M03->ViewValue = $this->M03->optionCaption($this->M03->CurrentValue);
            } else {
                $this->M03->ViewValue = null;
            }
            $this->M03->ViewCustomAttributes = "";

            // M04
            if (strval($this->M04->CurrentValue) != "") {
                $this->M04->ViewValue = $this->M04->optionCaption($this->M04->CurrentValue);
            } else {
                $this->M04->ViewValue = null;
            }
            $this->M04->ViewCustomAttributes = "";

            // M05
            if (strval($this->M05->CurrentValue) != "") {
                $this->M05->ViewValue = $this->M05->optionCaption($this->M05->CurrentValue);
            } else {
                $this->M05->ViewValue = null;
            }
            $this->M05->ViewCustomAttributes = "";

            // M06
            if (strval($this->M06->CurrentValue) != "") {
                $this->M06->ViewValue = $this->M06->optionCaption($this->M06->CurrentValue);
            } else {
                $this->M06->ViewValue = null;
            }
            $this->M06->ViewCustomAttributes = "";

            // M07
            if (strval($this->M07->CurrentValue) != "") {
                $this->M07->ViewValue = $this->M07->optionCaption($this->M07->CurrentValue);
            } else {
                $this->M07->ViewValue = null;
            }
            $this->M07->ViewCustomAttributes = "";

            // M08
            if (strval($this->M08->CurrentValue) != "") {
                $this->M08->ViewValue = $this->M08->optionCaption($this->M08->CurrentValue);
            } else {
                $this->M08->ViewValue = null;
            }
            $this->M08->ViewCustomAttributes = "";

            // M09
            if (strval($this->M09->CurrentValue) != "") {
                $this->M09->ViewValue = $this->M09->optionCaption($this->M09->CurrentValue);
            } else {
                $this->M09->ViewValue = null;
            }
            $this->M09->ViewCustomAttributes = "";

            // M10
            if (strval($this->M10->CurrentValue) != "") {
                $this->M10->ViewValue = $this->M10->optionCaption($this->M10->CurrentValue);
            } else {
                $this->M10->ViewValue = null;
            }
            $this->M10->ViewCustomAttributes = "";

            // M11
            if (strval($this->M11->CurrentValue) != "") {
                $this->M11->ViewValue = $this->M11->optionCaption($this->M11->CurrentValue);
            } else {
                $this->M11->ViewValue = null;
            }
            $this->M11->ViewCustomAttributes = "";

            // M12
            if (strval($this->M12->CurrentValue) != "") {
                $this->M12->ViewValue = $this->M12->optionCaption($this->M12->CurrentValue);
            } else {
                $this->M12->ViewValue = null;
            }
            $this->M12->ViewCustomAttributes = "";

            // activo
            if (strval($this->activo->CurrentValue) != "") {
                $this->activo->ViewValue = $this->activo->optionCaption($this->activo->CurrentValue);
            } else {
                $this->activo->ViewValue = null;
            }
            $this->activo->ViewCustomAttributes = "";

            // tipo_comprobante
            $this->tipo_comprobante->LinkCustomAttributes = "";
            $this->tipo_comprobante->HrefValue = "";
            $this->tipo_comprobante->TooltipValue = "";

            // descripcion
            $this->descripcion->LinkCustomAttributes = "";
            $this->descripcion->HrefValue = "";
            $this->descripcion->TooltipValue = "";

            // M01
            $this->M01->LinkCustomAttributes = "";
            $this->M01->HrefValue = "";
            $this->M01->TooltipValue = "";

            // M02
            $this->M02->LinkCustomAttributes = "";
            $this->M02->HrefValue = "";
            $this->M02->TooltipValue = "";

            // M03
            $this->M03->LinkCustomAttributes = "";
            $this->M03->HrefValue = "";
            $this->M03->TooltipValue = "";

            // M04
            $this->M04->LinkCustomAttributes = "";
            $this->M04->HrefValue = "";
            $this->M04->TooltipValue = "";

            // M05
            $this->M05->LinkCustomAttributes = "";
            $this->M05->HrefValue = "";
            $this->M05->TooltipValue = "";

            // M06
            $this->M06->LinkCustomAttributes = "";
            $this->M06->HrefValue = "";
            $this->M06->TooltipValue = "";

            // M07
            $this->M07->LinkCustomAttributes = "";
            $this->M07->HrefValue = "";
            $this->M07->TooltipValue = "";

            // M08
            $this->M08->LinkCustomAttributes = "";
            $this->M08->HrefValue = "";
            $this->M08->TooltipValue = "";

            // M09
            $this->M09->LinkCustomAttributes = "";
            $this->M09->HrefValue = "";
            $this->M09->TooltipValue = "";

            // M10
            $this->M10->LinkCustomAttributes = "";
            $this->M10->HrefValue = "";
            $this->M10->TooltipValue = "";

            // M11
            $this->M11->LinkCustomAttributes = "";
            $this->M11->HrefValue = "";
            $this->M11->TooltipValue = "";

            // M12
            $this->M12->LinkCustomAttributes = "";
            $this->M12->HrefValue = "";
            $this->M12->TooltipValue = "";

            // activo
            $this->activo->LinkCustomAttributes = "";
            $this->activo->HrefValue = "";
            $this->activo->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // tipo_comprobante
            $this->tipo_comprobante->EditAttrs["class"] = "form-control";
            $this->tipo_comprobante->EditCustomAttributes = "";
            $this->tipo_comprobante->EditValue = $this->tipo_comprobante->CurrentValue;
            $this->tipo_comprobante->ViewCustomAttributes = "";

            // descripcion
            $this->descripcion->EditAttrs["class"] = "form-control";
            $this->descripcion->EditCustomAttributes = "";
            $this->descripcion->EditValue = $this->descripcion->CurrentValue;
            $this->descripcion->ViewCustomAttributes = "";

            // M01
            $this->M01->EditCustomAttributes = "";
            $this->M01->EditValue = $this->M01->options(false);
            $this->M01->PlaceHolder = RemoveHtml($this->M01->caption());

            // M02
            $this->M02->EditCustomAttributes = "";
            $this->M02->EditValue = $this->M02->options(false);
            $this->M02->PlaceHolder = RemoveHtml($this->M02->caption());

            // M03
            $this->M03->EditCustomAttributes = "";
            $this->M03->EditValue = $this->M03->options(false);
            $this->M03->PlaceHolder = RemoveHtml($this->M03->caption());

            // M04
            $this->M04->EditCustomAttributes = "";
            $this->M04->EditValue = $this->M04->options(false);
            $this->M04->PlaceHolder = RemoveHtml($this->M04->caption());

            // M05
            $this->M05->EditCustomAttributes = "";
            $this->M05->EditValue = $this->M05->options(false);
            $this->M05->PlaceHolder = RemoveHtml($this->M05->caption());

            // M06
            $this->M06->EditCustomAttributes = "";
            $this->M06->EditValue = $this->M06->options(false);
            $this->M06->PlaceHolder = RemoveHtml($this->M06->caption());

            // M07
            $this->M07->EditCustomAttributes = "";
            $this->M07->EditValue = $this->M07->options(false);
            $this->M07->PlaceHolder = RemoveHtml($this->M07->caption());

            // M08
            $this->M08->EditCustomAttributes = "";
            $this->M08->EditValue = $this->M08->options(false);
            $this->M08->PlaceHolder = RemoveHtml($this->M08->caption());

            // M09
            $this->M09->EditCustomAttributes = "";
            $this->M09->EditValue = $this->M09->options(false);
            $this->M09->PlaceHolder = RemoveHtml($this->M09->caption());

            // M10
            $this->M10->EditCustomAttributes = "";
            $this->M10->EditValue = $this->M10->options(false);
            $this->M10->PlaceHolder = RemoveHtml($this->M10->caption());

            // M11
            $this->M11->EditCustomAttributes = "";
            $this->M11->EditValue = $this->M11->options(false);
            $this->M11->PlaceHolder = RemoveHtml($this->M11->caption());

            // M12
            $this->M12->EditCustomAttributes = "";
            $this->M12->EditValue = $this->M12->options(false);
            $this->M12->PlaceHolder = RemoveHtml($this->M12->caption());

            // activo
            $this->activo->EditCustomAttributes = "";
            $this->activo->EditValue = $this->activo->options(false);
            $this->activo->PlaceHolder = RemoveHtml($this->activo->caption());

            // Edit refer script

            // tipo_comprobante
            $this->tipo_comprobante->LinkCustomAttributes = "";
            $this->tipo_comprobante->HrefValue = "";
            $this->tipo_comprobante->TooltipValue = "";

            // descripcion
            $this->descripcion->LinkCustomAttributes = "";
            $this->descripcion->HrefValue = "";
            $this->descripcion->TooltipValue = "";

            // M01
            $this->M01->LinkCustomAttributes = "";
            $this->M01->HrefValue = "";

            // M02
            $this->M02->LinkCustomAttributes = "";
            $this->M02->HrefValue = "";

            // M03
            $this->M03->LinkCustomAttributes = "";
            $this->M03->HrefValue = "";

            // M04
            $this->M04->LinkCustomAttributes = "";
            $this->M04->HrefValue = "";

            // M05
            $this->M05->LinkCustomAttributes = "";
            $this->M05->HrefValue = "";

            // M06
            $this->M06->LinkCustomAttributes = "";
            $this->M06->HrefValue = "";

            // M07
            $this->M07->LinkCustomAttributes = "";
            $this->M07->HrefValue = "";

            // M08
            $this->M08->LinkCustomAttributes = "";
            $this->M08->HrefValue = "";

            // M09
            $this->M09->LinkCustomAttributes = "";
            $this->M09->HrefValue = "";

            // M10
            $this->M10->LinkCustomAttributes = "";
            $this->M10->HrefValue = "";

            // M11
            $this->M11->LinkCustomAttributes = "";
            $this->M11->HrefValue = "";

            // M12
            $this->M12->LinkCustomAttributes = "";
            $this->M12->HrefValue = "";

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
        if ($this->tipo_comprobante->Required) {
            if (!$this->tipo_comprobante->IsDetailKey && EmptyValue($this->tipo_comprobante->FormValue)) {
                $this->tipo_comprobante->addErrorMessage(str_replace("%s", $this->tipo_comprobante->caption(), $this->tipo_comprobante->RequiredErrorMessage));
            }
        }
        if ($this->descripcion->Required) {
            if (!$this->descripcion->IsDetailKey && EmptyValue($this->descripcion->FormValue)) {
                $this->descripcion->addErrorMessage(str_replace("%s", $this->descripcion->caption(), $this->descripcion->RequiredErrorMessage));
            }
        }
        if ($this->M01->Required) {
            if ($this->M01->FormValue == "") {
                $this->M01->addErrorMessage(str_replace("%s", $this->M01->caption(), $this->M01->RequiredErrorMessage));
            }
        }
        if ($this->M02->Required) {
            if ($this->M02->FormValue == "") {
                $this->M02->addErrorMessage(str_replace("%s", $this->M02->caption(), $this->M02->RequiredErrorMessage));
            }
        }
        if ($this->M03->Required) {
            if ($this->M03->FormValue == "") {
                $this->M03->addErrorMessage(str_replace("%s", $this->M03->caption(), $this->M03->RequiredErrorMessage));
            }
        }
        if ($this->M04->Required) {
            if ($this->M04->FormValue == "") {
                $this->M04->addErrorMessage(str_replace("%s", $this->M04->caption(), $this->M04->RequiredErrorMessage));
            }
        }
        if ($this->M05->Required) {
            if ($this->M05->FormValue == "") {
                $this->M05->addErrorMessage(str_replace("%s", $this->M05->caption(), $this->M05->RequiredErrorMessage));
            }
        }
        if ($this->M06->Required) {
            if ($this->M06->FormValue == "") {
                $this->M06->addErrorMessage(str_replace("%s", $this->M06->caption(), $this->M06->RequiredErrorMessage));
            }
        }
        if ($this->M07->Required) {
            if ($this->M07->FormValue == "") {
                $this->M07->addErrorMessage(str_replace("%s", $this->M07->caption(), $this->M07->RequiredErrorMessage));
            }
        }
        if ($this->M08->Required) {
            if ($this->M08->FormValue == "") {
                $this->M08->addErrorMessage(str_replace("%s", $this->M08->caption(), $this->M08->RequiredErrorMessage));
            }
        }
        if ($this->M09->Required) {
            if ($this->M09->FormValue == "") {
                $this->M09->addErrorMessage(str_replace("%s", $this->M09->caption(), $this->M09->RequiredErrorMessage));
            }
        }
        if ($this->M10->Required) {
            if ($this->M10->FormValue == "") {
                $this->M10->addErrorMessage(str_replace("%s", $this->M10->caption(), $this->M10->RequiredErrorMessage));
            }
        }
        if ($this->M11->Required) {
            if ($this->M11->FormValue == "") {
                $this->M11->addErrorMessage(str_replace("%s", $this->M11->caption(), $this->M11->RequiredErrorMessage));
            }
        }
        if ($this->M12->Required) {
            if ($this->M12->FormValue == "") {
                $this->M12->addErrorMessage(str_replace("%s", $this->M12->caption(), $this->M12->RequiredErrorMessage));
            }
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

    // Update record based on key values
    protected function editRow()
    {
        global $Security, $Language;
        $oldKeyFilter = $this->getRecordFilter();
        $filter = $this->applyUserIDFilters($oldKeyFilter);
        $conn = $this->getConnection();
        if ($this->tipo_comprobante->CurrentValue != "") { // Check field with unique index
            $filterChk = "(`tipo_comprobante` = '" . AdjustSql($this->tipo_comprobante->CurrentValue, $this->Dbid) . "')";
            $filterChk .= " AND NOT (" . $filter . ")";
            $this->CurrentFilter = $filterChk;
            $sqlChk = $this->getCurrentSql();
            $rsChk = $conn->executeQuery($sqlChk);
            if (!$rsChk) {
                return false;
            }
            if ($rsChk->fetch()) {
                $idxErrMsg = str_replace("%f", $this->tipo_comprobante->caption(), $Language->phrase("DupIndex"));
                $idxErrMsg = str_replace("%v", $this->tipo_comprobante->CurrentValue, $idxErrMsg);
                $this->setFailureMessage($idxErrMsg);
                $rsChk->closeCursor();
                return false;
            }
        }
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

            // M01
            $this->M01->setDbValueDef($rsnew, $this->M01->CurrentValue, null, $this->M01->ReadOnly);

            // M02
            $this->M02->setDbValueDef($rsnew, $this->M02->CurrentValue, null, $this->M02->ReadOnly);

            // M03
            $this->M03->setDbValueDef($rsnew, $this->M03->CurrentValue, null, $this->M03->ReadOnly);

            // M04
            $this->M04->setDbValueDef($rsnew, $this->M04->CurrentValue, null, $this->M04->ReadOnly);

            // M05
            $this->M05->setDbValueDef($rsnew, $this->M05->CurrentValue, null, $this->M05->ReadOnly);

            // M06
            $this->M06->setDbValueDef($rsnew, $this->M06->CurrentValue, null, $this->M06->ReadOnly);

            // M07
            $this->M07->setDbValueDef($rsnew, $this->M07->CurrentValue, null, $this->M07->ReadOnly);

            // M08
            $this->M08->setDbValueDef($rsnew, $this->M08->CurrentValue, null, $this->M08->ReadOnly);

            // M09
            $this->M09->setDbValueDef($rsnew, $this->M09->CurrentValue, null, $this->M09->ReadOnly);

            // M10
            $this->M10->setDbValueDef($rsnew, $this->M10->CurrentValue, null, $this->M10->ReadOnly);

            // M11
            $this->M11->setDbValueDef($rsnew, $this->M11->CurrentValue, null, $this->M11->ReadOnly);

            // M12
            $this->M12->setDbValueDef($rsnew, $this->M12->CurrentValue, null, $this->M12->ReadOnly);

            // activo
            $this->activo->setDbValueDef($rsnew, $this->activo->CurrentValue, null, $this->activo->ReadOnly);

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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("ContMesContableList"), "", $this->TableVar, true);
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
                case "x_M01":
                    break;
                case "x_M02":
                    break;
                case "x_M03":
                    break;
                case "x_M04":
                    break;
                case "x_M05":
                    break;
                case "x_M06":
                    break;
                case "x_M07":
                    break;
                case "x_M08":
                    break;
                case "x_M09":
                    break;
                case "x_M10":
                    break;
                case "x_M11":
                    break;
                case "x_M12":
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
