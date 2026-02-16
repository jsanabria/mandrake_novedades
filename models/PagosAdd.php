<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class PagosAdd extends Pagos
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'pagos';

    // Page object name
    public $PageObjName = "PagosAdd";

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

        // Table object (pagos)
        if (!isset($GLOBALS["pagos"]) || get_class($GLOBALS["pagos"]) == PROJECT_NAMESPACE . "pagos") {
            $GLOBALS["pagos"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'pagos');
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
                $doc = new $class(Container("pagos"));
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
                    if ($pageName == "PagosView") {
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
        $this->id_documento->Visible = false;
        $this->tipo_documento->Visible = false;
        $this->tipo_pago->setVisibility();
        $this->fecha->setVisibility();
        $this->referencia->setVisibility();
        $this->monto->setVisibility();
        $this->nota->setVisibility();
        $this->comprobante_pago->setVisibility();
        $this->comprobante->setVisibility();
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
        $this->setupLookupOptions($this->tipo_pago);

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
                    $this->terminate("PagosList"); // No matching record, return to list
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
                    if (GetPageName($returnUrl) == "PagosList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "PagosView") {
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
        $this->comprobante_pago->Upload->Index = $CurrentForm->Index;
        $this->comprobante_pago->Upload->uploadFile();
        $this->comprobante_pago->CurrentValue = $this->comprobante_pago->Upload->FileName;
    }

    // Load default values
    protected function loadDefaultValues()
    {
        $this->id->CurrentValue = null;
        $this->id->OldValue = $this->id->CurrentValue;
        $this->id_documento->CurrentValue = null;
        $this->id_documento->OldValue = $this->id_documento->CurrentValue;
        $this->tipo_documento->CurrentValue = null;
        $this->tipo_documento->OldValue = $this->tipo_documento->CurrentValue;
        $this->tipo_pago->CurrentValue = null;
        $this->tipo_pago->OldValue = $this->tipo_pago->CurrentValue;
        $this->fecha->CurrentValue = null;
        $this->fecha->OldValue = $this->fecha->CurrentValue;
        $this->referencia->CurrentValue = null;
        $this->referencia->OldValue = $this->referencia->CurrentValue;
        $this->monto->CurrentValue = null;
        $this->monto->OldValue = $this->monto->CurrentValue;
        $this->nota->CurrentValue = null;
        $this->nota->OldValue = $this->nota->CurrentValue;
        $this->comprobante_pago->Upload->DbValue = null;
        $this->comprobante_pago->OldValue = $this->comprobante_pago->Upload->DbValue;
        $this->comprobante_pago->CurrentValue = null; // Clear file related field
        $this->comprobante->CurrentValue = null;
        $this->comprobante->OldValue = $this->comprobante->CurrentValue;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;

        // Check field name 'tipo_pago' first before field var 'x_tipo_pago'
        $val = $CurrentForm->hasValue("tipo_pago") ? $CurrentForm->getValue("tipo_pago") : $CurrentForm->getValue("x_tipo_pago");
        if (!$this->tipo_pago->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->tipo_pago->Visible = false; // Disable update for API request
            } else {
                $this->tipo_pago->setFormValue($val);
            }
        }

        // Check field name 'fecha' first before field var 'x_fecha'
        $val = $CurrentForm->hasValue("fecha") ? $CurrentForm->getValue("fecha") : $CurrentForm->getValue("x_fecha");
        if (!$this->fecha->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->fecha->Visible = false; // Disable update for API request
            } else {
                $this->fecha->setFormValue($val);
            }
            $this->fecha->CurrentValue = UnFormatDateTime($this->fecha->CurrentValue, 0);
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

        // Check field name 'monto' first before field var 'x_monto'
        $val = $CurrentForm->hasValue("monto") ? $CurrentForm->getValue("monto") : $CurrentForm->getValue("x_monto");
        if (!$this->monto->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->monto->Visible = false; // Disable update for API request
            } else {
                $this->monto->setFormValue($val);
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

        // Check field name 'comprobante' first before field var 'x_comprobante'
        $val = $CurrentForm->hasValue("comprobante") ? $CurrentForm->getValue("comprobante") : $CurrentForm->getValue("x_comprobante");
        if (!$this->comprobante->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->comprobante->Visible = false; // Disable update for API request
            } else {
                $this->comprobante->setFormValue($val);
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
        $this->tipo_pago->CurrentValue = $this->tipo_pago->FormValue;
        $this->fecha->CurrentValue = $this->fecha->FormValue;
        $this->fecha->CurrentValue = UnFormatDateTime($this->fecha->CurrentValue, 0);
        $this->referencia->CurrentValue = $this->referencia->FormValue;
        $this->monto->CurrentValue = $this->monto->FormValue;
        $this->nota->CurrentValue = $this->nota->FormValue;
        $this->comprobante->CurrentValue = $this->comprobante->FormValue;
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
        $this->id_documento->setDbValue($row['id_documento']);
        $this->tipo_documento->setDbValue($row['tipo_documento']);
        $this->tipo_pago->setDbValue($row['tipo_pago']);
        $this->fecha->setDbValue($row['fecha']);
        $this->referencia->setDbValue($row['referencia']);
        $this->monto->setDbValue($row['monto']);
        $this->nota->setDbValue($row['nota']);
        $this->comprobante_pago->Upload->DbValue = $row['comprobante_pago'];
        $this->comprobante_pago->setDbValue($this->comprobante_pago->Upload->DbValue);
        $this->comprobante->setDbValue($row['comprobante']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $this->loadDefaultValues();
        $row = [];
        $row['id'] = $this->id->CurrentValue;
        $row['id_documento'] = $this->id_documento->CurrentValue;
        $row['tipo_documento'] = $this->tipo_documento->CurrentValue;
        $row['tipo_pago'] = $this->tipo_pago->CurrentValue;
        $row['fecha'] = $this->fecha->CurrentValue;
        $row['referencia'] = $this->referencia->CurrentValue;
        $row['monto'] = $this->monto->CurrentValue;
        $row['nota'] = $this->nota->CurrentValue;
        $row['comprobante_pago'] = $this->comprobante_pago->Upload->DbValue;
        $row['comprobante'] = $this->comprobante->CurrentValue;
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
        if ($this->monto->FormValue == $this->monto->CurrentValue && is_numeric(ConvertToFloatString($this->monto->CurrentValue))) {
            $this->monto->CurrentValue = ConvertToFloatString($this->monto->CurrentValue);
        }

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // id

        // id_documento

        // tipo_documento

        // tipo_pago

        // fecha

        // referencia

        // monto

        // nota

        // comprobante_pago

        // comprobante
        if ($this->RowType == ROWTYPE_VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;
            $this->id->ViewCustomAttributes = "";

            // id_documento
            $this->id_documento->ViewValue = $this->id_documento->CurrentValue;
            $this->id_documento->ViewCustomAttributes = "";

            // tipo_documento
            $this->tipo_documento->ViewValue = $this->tipo_documento->CurrentValue;
            $this->tipo_documento->ViewCustomAttributes = "";

            // tipo_pago
            $curVal = trim(strval($this->tipo_pago->CurrentValue));
            if ($curVal != "") {
                $this->tipo_pago->ViewValue = $this->tipo_pago->lookupCacheOption($curVal);
                if ($this->tipo_pago->ViewValue === null) { // Lookup from database
                    $filterWrk = "`valor1`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $lookupFilter = function() {
                        return "`codigo` = '009'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    $sqlWrk = $this->tipo_pago->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->tipo_pago->Lookup->renderViewRow($rswrk[0]);
                        $this->tipo_pago->ViewValue = $this->tipo_pago->displayValue($arwrk);
                    } else {
                        $this->tipo_pago->ViewValue = $this->tipo_pago->CurrentValue;
                    }
                }
            } else {
                $this->tipo_pago->ViewValue = null;
            }
            $this->tipo_pago->ViewCustomAttributes = "";

            // fecha
            $this->fecha->ViewValue = $this->fecha->CurrentValue;
            $this->fecha->ViewValue = FormatDateTime($this->fecha->ViewValue, 0);
            $this->fecha->ViewCustomAttributes = "";

            // referencia
            $this->referencia->ViewValue = $this->referencia->CurrentValue;
            $this->referencia->ViewCustomAttributes = "";

            // monto
            $this->monto->ViewValue = $this->monto->CurrentValue;
            $this->monto->ViewValue = FormatNumber($this->monto->ViewValue, $this->monto->DefaultDecimalPrecision);
            $this->monto->ViewCustomAttributes = "";

            // nota
            $this->nota->ViewValue = $this->nota->CurrentValue;
            $this->nota->ViewCustomAttributes = "";

            // comprobante_pago
            if (!EmptyValue($this->comprobante_pago->Upload->DbValue)) {
                $this->comprobante_pago->ImageWidth = 120;
                $this->comprobante_pago->ImageHeight = 120;
                $this->comprobante_pago->ImageAlt = $this->comprobante_pago->alt();
                $this->comprobante_pago->ViewValue = $this->comprobante_pago->Upload->DbValue;
            } else {
                $this->comprobante_pago->ViewValue = "";
            }
            $this->comprobante_pago->ViewCustomAttributes = "";

            // comprobante
            $this->comprobante->ViewValue = $this->comprobante->CurrentValue;
            $this->comprobante->ViewCustomAttributes = "";

            // tipo_pago
            $this->tipo_pago->LinkCustomAttributes = "";
            $this->tipo_pago->HrefValue = "";
            $this->tipo_pago->TooltipValue = "";

            // fecha
            $this->fecha->LinkCustomAttributes = "";
            $this->fecha->HrefValue = "";
            $this->fecha->TooltipValue = "";

            // referencia
            $this->referencia->LinkCustomAttributes = "";
            $this->referencia->HrefValue = "";
            $this->referencia->TooltipValue = "";

            // monto
            $this->monto->LinkCustomAttributes = "";
            $this->monto->HrefValue = "";
            $this->monto->TooltipValue = "";

            // nota
            $this->nota->LinkCustomAttributes = "";
            $this->nota->HrefValue = "";
            $this->nota->TooltipValue = "";

            // comprobante_pago
            $this->comprobante_pago->LinkCustomAttributes = "";
            if (!EmptyValue($this->comprobante_pago->Upload->DbValue)) {
                $this->comprobante_pago->HrefValue = GetFileUploadUrl($this->comprobante_pago, $this->comprobante_pago->htmlDecode($this->comprobante_pago->Upload->DbValue)); // Add prefix/suffix
                $this->comprobante_pago->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->comprobante_pago->HrefValue = FullUrl($this->comprobante_pago->HrefValue, "href");
                }
            } else {
                $this->comprobante_pago->HrefValue = "";
            }
            $this->comprobante_pago->ExportHrefValue = $this->comprobante_pago->UploadPath . $this->comprobante_pago->Upload->DbValue;
            $this->comprobante_pago->TooltipValue = "";
            if ($this->comprobante_pago->UseColorbox) {
                if (EmptyValue($this->comprobante_pago->TooltipValue)) {
                    $this->comprobante_pago->LinkAttrs["title"] = $Language->phrase("ViewImageGallery");
                }
                $this->comprobante_pago->LinkAttrs["data-rel"] = "pagos_x_comprobante_pago";
                $this->comprobante_pago->LinkAttrs->appendClass("ew-lightbox");
            }

            // comprobante
            $this->comprobante->LinkCustomAttributes = "";
            $this->comprobante->HrefValue = "";
            $this->comprobante->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // tipo_pago
            $this->tipo_pago->EditAttrs["class"] = "form-control";
            $this->tipo_pago->EditCustomAttributes = "";
            $curVal = trim(strval($this->tipo_pago->CurrentValue));
            if ($curVal != "") {
                $this->tipo_pago->ViewValue = $this->tipo_pago->lookupCacheOption($curVal);
            } else {
                $this->tipo_pago->ViewValue = $this->tipo_pago->Lookup !== null && is_array($this->tipo_pago->Lookup->Options) ? $curVal : null;
            }
            if ($this->tipo_pago->ViewValue !== null) { // Load from cache
                $this->tipo_pago->EditValue = array_values($this->tipo_pago->Lookup->Options);
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`valor1`" . SearchString("=", $this->tipo_pago->CurrentValue, DATATYPE_STRING, "");
                }
                $lookupFilter = function() {
                    return "`codigo` = '009'";
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->tipo_pago->Lookup->getSql(true, $filterWrk, $lookupFilter, $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->tipo_pago->EditValue = $arwrk;
            }
            $this->tipo_pago->PlaceHolder = RemoveHtml($this->tipo_pago->caption());

            // fecha
            $this->fecha->EditAttrs["class"] = "form-control";
            $this->fecha->EditCustomAttributes = "";
            $this->fecha->EditValue = HtmlEncode(FormatDateTime($this->fecha->CurrentValue, 8));
            $this->fecha->PlaceHolder = RemoveHtml($this->fecha->caption());

            // referencia
            $this->referencia->EditAttrs["class"] = "form-control";
            $this->referencia->EditCustomAttributes = "";
            if (!$this->referencia->Raw) {
                $this->referencia->CurrentValue = HtmlDecode($this->referencia->CurrentValue);
            }
            $this->referencia->EditValue = HtmlEncode($this->referencia->CurrentValue);
            $this->referencia->PlaceHolder = RemoveHtml($this->referencia->caption());

            // monto
            $this->monto->EditAttrs["class"] = "form-control";
            $this->monto->EditCustomAttributes = "";
            $this->monto->EditValue = HtmlEncode($this->monto->CurrentValue);
            $this->monto->PlaceHolder = RemoveHtml($this->monto->caption());
            if (strval($this->monto->EditValue) != "" && is_numeric($this->monto->EditValue)) {
                $this->monto->EditValue = FormatNumber($this->monto->EditValue, -2, -1, -2, 0);
            }

            // nota
            $this->nota->EditAttrs["class"] = "form-control";
            $this->nota->EditCustomAttributes = "";
            if (!$this->nota->Raw) {
                $this->nota->CurrentValue = HtmlDecode($this->nota->CurrentValue);
            }
            $this->nota->EditValue = HtmlEncode($this->nota->CurrentValue);
            $this->nota->PlaceHolder = RemoveHtml($this->nota->caption());

            // comprobante_pago
            $this->comprobante_pago->EditAttrs["class"] = "form-control";
            $this->comprobante_pago->EditCustomAttributes = "";
            if (!EmptyValue($this->comprobante_pago->Upload->DbValue)) {
                $this->comprobante_pago->ImageWidth = 120;
                $this->comprobante_pago->ImageHeight = 120;
                $this->comprobante_pago->ImageAlt = $this->comprobante_pago->alt();
                $this->comprobante_pago->EditValue = $this->comprobante_pago->Upload->DbValue;
            } else {
                $this->comprobante_pago->EditValue = "";
            }
            if (!EmptyValue($this->comprobante_pago->CurrentValue)) {
                $this->comprobante_pago->Upload->FileName = $this->comprobante_pago->CurrentValue;
            }
            if ($this->isShow() || $this->isCopy()) {
                RenderUploadField($this->comprobante_pago);
            }

            // comprobante
            $this->comprobante->EditAttrs["class"] = "form-control";
            $this->comprobante->EditCustomAttributes = "";
            $this->comprobante->EditValue = HtmlEncode($this->comprobante->CurrentValue);
            $this->comprobante->PlaceHolder = RemoveHtml($this->comprobante->caption());

            // Add refer script

            // tipo_pago
            $this->tipo_pago->LinkCustomAttributes = "";
            $this->tipo_pago->HrefValue = "";

            // fecha
            $this->fecha->LinkCustomAttributes = "";
            $this->fecha->HrefValue = "";

            // referencia
            $this->referencia->LinkCustomAttributes = "";
            $this->referencia->HrefValue = "";

            // monto
            $this->monto->LinkCustomAttributes = "";
            $this->monto->HrefValue = "";

            // nota
            $this->nota->LinkCustomAttributes = "";
            $this->nota->HrefValue = "";

            // comprobante_pago
            $this->comprobante_pago->LinkCustomAttributes = "";
            if (!EmptyValue($this->comprobante_pago->Upload->DbValue)) {
                $this->comprobante_pago->HrefValue = GetFileUploadUrl($this->comprobante_pago, $this->comprobante_pago->htmlDecode($this->comprobante_pago->Upload->DbValue)); // Add prefix/suffix
                $this->comprobante_pago->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->comprobante_pago->HrefValue = FullUrl($this->comprobante_pago->HrefValue, "href");
                }
            } else {
                $this->comprobante_pago->HrefValue = "";
            }
            $this->comprobante_pago->ExportHrefValue = $this->comprobante_pago->UploadPath . $this->comprobante_pago->Upload->DbValue;

            // comprobante
            $this->comprobante->LinkCustomAttributes = "";
            $this->comprobante->HrefValue = "";
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
        if ($this->tipo_pago->Required) {
            if (!$this->tipo_pago->IsDetailKey && EmptyValue($this->tipo_pago->FormValue)) {
                $this->tipo_pago->addErrorMessage(str_replace("%s", $this->tipo_pago->caption(), $this->tipo_pago->RequiredErrorMessage));
            }
        }
        if ($this->fecha->Required) {
            if (!$this->fecha->IsDetailKey && EmptyValue($this->fecha->FormValue)) {
                $this->fecha->addErrorMessage(str_replace("%s", $this->fecha->caption(), $this->fecha->RequiredErrorMessage));
            }
        }
        if (!CheckDate($this->fecha->FormValue)) {
            $this->fecha->addErrorMessage($this->fecha->getErrorMessage(false));
        }
        if ($this->referencia->Required) {
            if (!$this->referencia->IsDetailKey && EmptyValue($this->referencia->FormValue)) {
                $this->referencia->addErrorMessage(str_replace("%s", $this->referencia->caption(), $this->referencia->RequiredErrorMessage));
            }
        }
        if ($this->monto->Required) {
            if (!$this->monto->IsDetailKey && EmptyValue($this->monto->FormValue)) {
                $this->monto->addErrorMessage(str_replace("%s", $this->monto->caption(), $this->monto->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->monto->FormValue)) {
            $this->monto->addErrorMessage($this->monto->getErrorMessage(false));
        }
        if ($this->nota->Required) {
            if (!$this->nota->IsDetailKey && EmptyValue($this->nota->FormValue)) {
                $this->nota->addErrorMessage(str_replace("%s", $this->nota->caption(), $this->nota->RequiredErrorMessage));
            }
        }
        if ($this->comprobante_pago->Required) {
            if ($this->comprobante_pago->Upload->FileName == "" && !$this->comprobante_pago->Upload->KeepFile) {
                $this->comprobante_pago->addErrorMessage(str_replace("%s", $this->comprobante_pago->caption(), $this->comprobante_pago->RequiredErrorMessage));
            }
        }
        if ($this->comprobante->Required) {
            if (!$this->comprobante->IsDetailKey && EmptyValue($this->comprobante->FormValue)) {
                $this->comprobante->addErrorMessage(str_replace("%s", $this->comprobante->caption(), $this->comprobante->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->comprobante->FormValue)) {
            $this->comprobante->addErrorMessage($this->comprobante->getErrorMessage(false));
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

        // tipo_pago
        $this->tipo_pago->setDbValueDef($rsnew, $this->tipo_pago->CurrentValue, null, false);

        // fecha
        $this->fecha->setDbValueDef($rsnew, UnFormatDateTime($this->fecha->CurrentValue, 0), null, false);

        // referencia
        $this->referencia->setDbValueDef($rsnew, $this->referencia->CurrentValue, null, false);

        // monto
        $this->monto->setDbValueDef($rsnew, $this->monto->CurrentValue, null, false);

        // nota
        $this->nota->setDbValueDef($rsnew, $this->nota->CurrentValue, null, false);

        // comprobante_pago
        if ($this->comprobante_pago->Visible && !$this->comprobante_pago->Upload->KeepFile) {
            $this->comprobante_pago->Upload->DbValue = ""; // No need to delete old file
            if ($this->comprobante_pago->Upload->FileName == "") {
                $rsnew['comprobante_pago'] = null;
            } else {
                $rsnew['comprobante_pago'] = $this->comprobante_pago->Upload->FileName;
            }
        }

        // comprobante
        $this->comprobante->setDbValueDef($rsnew, $this->comprobante->CurrentValue, null, false);

        // id_documento
        if ($this->id_documento->getSessionValue() != "") {
            $rsnew['id_documento'] = $this->id_documento->getSessionValue();
        }

        // tipo_documento
        if ($this->tipo_documento->getSessionValue() != "") {
            $rsnew['tipo_documento'] = $this->tipo_documento->getSessionValue();
        }
        if ($this->comprobante_pago->Visible && !$this->comprobante_pago->Upload->KeepFile) {
            $oldFiles = EmptyValue($this->comprobante_pago->Upload->DbValue) ? [] : [$this->comprobante_pago->htmlDecode($this->comprobante_pago->Upload->DbValue)];
            if (!EmptyValue($this->comprobante_pago->Upload->FileName)) {
                $newFiles = [$this->comprobante_pago->Upload->FileName];
                $NewFileCount = count($newFiles);
                for ($i = 0; $i < $NewFileCount; $i++) {
                    if ($newFiles[$i] != "") {
                        $file = $newFiles[$i];
                        $tempPath = UploadTempPath($this->comprobante_pago, $this->comprobante_pago->Upload->Index);
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
                            $file1 = UniqueFilename($this->comprobante_pago->physicalUploadPath(), $file); // Get new file name
                            if ($file1 != $file) { // Rename temp file
                                while (file_exists($tempPath . $file1) || file_exists($this->comprobante_pago->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                    $file1 = UniqueFilename([$this->comprobante_pago->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                }
                                rename($tempPath . $file, $tempPath . $file1);
                                $newFiles[$i] = $file1;
                            }
                        }
                    }
                }
                $this->comprobante_pago->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                $this->comprobante_pago->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                $this->comprobante_pago->setDbValueDef($rsnew, $this->comprobante_pago->Upload->FileName, null, false);
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
                if ($this->comprobante_pago->Visible && !$this->comprobante_pago->Upload->KeepFile) {
                    $oldFiles = EmptyValue($this->comprobante_pago->Upload->DbValue) ? [] : [$this->comprobante_pago->htmlDecode($this->comprobante_pago->Upload->DbValue)];
                    if (!EmptyValue($this->comprobante_pago->Upload->FileName)) {
                        $newFiles = [$this->comprobante_pago->Upload->FileName];
                        $newFiles2 = [$this->comprobante_pago->htmlDecode($rsnew['comprobante_pago'])];
                        $newFileCount = count($newFiles);
                        for ($i = 0; $i < $newFileCount; $i++) {
                            if ($newFiles[$i] != "") {
                                $file = UploadTempPath($this->comprobante_pago, $this->comprobante_pago->Upload->Index) . $newFiles[$i];
                                if (file_exists($file)) {
                                    if (@$newFiles2[$i] != "") { // Use correct file name
                                        $newFiles[$i] = $newFiles2[$i];
                                    }
                                    if (!$this->comprobante_pago->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                @unlink($this->comprobante_pago->oldPhysicalUploadPath() . $oldFile);
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
        if ($addRow) {
            // Call Row Inserted event
            $this->rowInserted($rsold, $rsnew);
        }

        // Clean upload path if any
        if ($addRow) {
            // comprobante_pago
            CleanUploadTempPath($this->comprobante_pago, $this->comprobante_pago->Upload->Index);
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
            if ($masterTblVar == "salidas") {
                $validMaster = true;
                $masterTbl = Container("salidas");
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
                if (($parm = Get("fk_tipo_documento", Get("tipo_documento"))) !== null) {
                    $masterTbl->tipo_documento->setQueryStringValue($parm);
                    $this->tipo_documento->setQueryStringValue($masterTbl->tipo_documento->QueryStringValue);
                    $this->tipo_documento->setSessionValue($this->tipo_documento->QueryStringValue);
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
            if ($masterTblVar == "salidas") {
                $validMaster = true;
                $masterTbl = Container("salidas");
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
                if (($parm = Post("fk_tipo_documento", Post("tipo_documento"))) !== null) {
                    $masterTbl->tipo_documento->setFormValue($parm);
                    $this->tipo_documento->setFormValue($masterTbl->tipo_documento->FormValue);
                    $this->tipo_documento->setSessionValue($this->tipo_documento->FormValue);
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
            if ($masterTblVar != "salidas") {
                if ($this->id_documento->CurrentValue == "") {
                    $this->id_documento->setSessionValue("");
                }
                if ($this->tipo_documento->CurrentValue == "") {
                    $this->tipo_documento->setSessionValue("");
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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("PagosList"), "", $this->TableVar, true);
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
                case "x_tipo_pago":
                    $lookupFilter = function () {
                        return "`codigo` = '009'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
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
