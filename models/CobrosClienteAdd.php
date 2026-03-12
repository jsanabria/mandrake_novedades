<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class CobrosClienteAdd extends CobrosCliente
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'cobros_cliente';

    // Page object name
    public $PageObjName = "CobrosClienteAdd";

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

        // Table object (cobros_cliente)
        if (!isset($GLOBALS["cobros_cliente"]) || get_class($GLOBALS["cobros_cliente"]) == PROJECT_NAMESPACE . "cobros_cliente") {
            $GLOBALS["cobros_cliente"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'cobros_cliente');
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
                $doc = new $class(Container("cobros_cliente"));
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
                    if ($pageName == "CobrosClienteView") {
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
        $this->cliente->setVisibility();
        $this->id_documento->Visible = false;
        $this->pivote->setVisibility();
        $this->fecha->Visible = false;
        $this->moneda->setVisibility();
        $this->pago->setVisibility();
        $this->nota->setVisibility();
        $this->fecha_registro->Visible = false;
        $this->_username->Visible = false;
        $this->comprobante->Visible = false;
        $this->tipo_pago->setVisibility();
        $this->pivote2->setVisibility();
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
        $this->setupLookupOptions($this->cliente);
        $this->setupLookupOptions($this->id_documento);
        $this->setupLookupOptions($this->_username);
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
                    $this->terminate("CobrosClienteList"); // No matching record, return to list
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
                    $returnUrl = "CobrosClienteDetalleList?showmaster=cobros_cliente&fk_id=" . urlencode($this->id->CurrentValue);
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
        $this->cliente->CurrentValue = null;
        $this->cliente->OldValue = $this->cliente->CurrentValue;
        $this->id_documento->CurrentValue = null;
        $this->id_documento->OldValue = $this->id_documento->CurrentValue;
        $this->pivote->CurrentValue = null;
        $this->pivote->OldValue = $this->pivote->CurrentValue;
        $this->fecha->CurrentValue = null;
        $this->fecha->OldValue = $this->fecha->CurrentValue;
        $this->moneda->CurrentValue = null;
        $this->moneda->OldValue = $this->moneda->CurrentValue;
        $this->pago->CurrentValue = null;
        $this->pago->OldValue = $this->pago->CurrentValue;
        $this->nota->CurrentValue = null;
        $this->nota->OldValue = $this->nota->CurrentValue;
        $this->fecha_registro->CurrentValue = null;
        $this->fecha_registro->OldValue = $this->fecha_registro->CurrentValue;
        $this->_username->CurrentValue = null;
        $this->_username->OldValue = $this->_username->CurrentValue;
        $this->comprobante->CurrentValue = null;
        $this->comprobante->OldValue = $this->comprobante->CurrentValue;
        $this->tipo_pago->CurrentValue = null;
        $this->tipo_pago->OldValue = $this->tipo_pago->CurrentValue;
        $this->pivote2->CurrentValue = null;
        $this->pivote2->OldValue = $this->pivote2->CurrentValue;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;

        // Check field name 'cliente' first before field var 'x_cliente'
        $val = $CurrentForm->hasValue("cliente") ? $CurrentForm->getValue("cliente") : $CurrentForm->getValue("x_cliente");
        if (!$this->cliente->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cliente->Visible = false; // Disable update for API request
            } else {
                $this->cliente->setFormValue($val);
            }
        }

        // Check field name 'pivote' first before field var 'x_pivote'
        $val = $CurrentForm->hasValue("pivote") ? $CurrentForm->getValue("pivote") : $CurrentForm->getValue("x_pivote");
        if (!$this->pivote->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->pivote->Visible = false; // Disable update for API request
            } else {
                $this->pivote->setFormValue($val);
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

        // Check field name 'pago' first before field var 'x_pago'
        $val = $CurrentForm->hasValue("pago") ? $CurrentForm->getValue("pago") : $CurrentForm->getValue("x_pago");
        if (!$this->pago->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->pago->Visible = false; // Disable update for API request
            } else {
                $this->pago->setFormValue($val);
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

        // Check field name 'tipo_pago' first before field var 'x_tipo_pago'
        $val = $CurrentForm->hasValue("tipo_pago") ? $CurrentForm->getValue("tipo_pago") : $CurrentForm->getValue("x_tipo_pago");
        if (!$this->tipo_pago->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->tipo_pago->Visible = false; // Disable update for API request
            } else {
                $this->tipo_pago->setFormValue($val);
            }
        }

        // Check field name 'pivote2' first before field var 'x_pivote2'
        $val = $CurrentForm->hasValue("pivote2") ? $CurrentForm->getValue("pivote2") : $CurrentForm->getValue("x_pivote2");
        if (!$this->pivote2->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->pivote2->Visible = false; // Disable update for API request
            } else {
                $this->pivote2->setFormValue($val);
            }
        }

        // Check field name 'id' first before field var 'x_id'
        $val = $CurrentForm->hasValue("id") ? $CurrentForm->getValue("id") : $CurrentForm->getValue("x_id");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->cliente->CurrentValue = $this->cliente->FormValue;
        $this->pivote->CurrentValue = $this->pivote->FormValue;
        $this->moneda->CurrentValue = $this->moneda->FormValue;
        $this->pago->CurrentValue = $this->pago->FormValue;
        $this->nota->CurrentValue = $this->nota->FormValue;
        $this->tipo_pago->CurrentValue = $this->tipo_pago->FormValue;
        $this->pivote2->CurrentValue = $this->pivote2->FormValue;
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
        $this->cliente->setDbValue($row['cliente']);
        $this->id_documento->setDbValue($row['id_documento']);
        $this->pivote->setDbValue($row['pivote']);
        $this->fecha->setDbValue($row['fecha']);
        $this->moneda->setDbValue($row['moneda']);
        $this->pago->setDbValue($row['pago']);
        $this->nota->setDbValue($row['nota']);
        $this->fecha_registro->setDbValue($row['fecha_registro']);
        $this->_username->setDbValue($row['username']);
        $this->comprobante->setDbValue($row['comprobante']);
        $this->tipo_pago->setDbValue($row['tipo_pago']);
        $this->pivote2->setDbValue($row['pivote2']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $this->loadDefaultValues();
        $row = [];
        $row['id'] = $this->id->CurrentValue;
        $row['cliente'] = $this->cliente->CurrentValue;
        $row['id_documento'] = $this->id_documento->CurrentValue;
        $row['pivote'] = $this->pivote->CurrentValue;
        $row['fecha'] = $this->fecha->CurrentValue;
        $row['moneda'] = $this->moneda->CurrentValue;
        $row['pago'] = $this->pago->CurrentValue;
        $row['nota'] = $this->nota->CurrentValue;
        $row['fecha_registro'] = $this->fecha_registro->CurrentValue;
        $row['username'] = $this->_username->CurrentValue;
        $row['comprobante'] = $this->comprobante->CurrentValue;
        $row['tipo_pago'] = $this->tipo_pago->CurrentValue;
        $row['pivote2'] = $this->pivote2->CurrentValue;
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
        if ($this->pago->FormValue == $this->pago->CurrentValue && is_numeric(ConvertToFloatString($this->pago->CurrentValue))) {
            $this->pago->CurrentValue = ConvertToFloatString($this->pago->CurrentValue);
        }

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // id

        // cliente

        // id_documento

        // pivote

        // fecha

        // moneda

        // pago

        // nota

        // fecha_registro

        // username

        // comprobante

        // tipo_pago

        // pivote2
        if ($this->RowType == ROWTYPE_VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;
            $this->id->ViewCustomAttributes = "";

            // cliente
            $curVal = trim(strval($this->cliente->CurrentValue));
            if ($curVal != "") {
                $this->cliente->ViewValue = $this->cliente->lookupCacheOption($curVal);
                if ($this->cliente->ViewValue === null) { // Lookup from database
                    $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $lookupFilter = function() {
                        return (CurrentPageID() == "add") ? 
            	"`id` IN 
            		(SELECT cliente 
            		FROM view_x_cobrar 
            		WHERE IFNULL(monto_pagar, 0) > 0 AND  
            		IFNULL(monto_pagar, 0) > (IFNULL(monto_pagado, 0) + IFNULL(retivamonto, 0) + IFNULL(retislrmonto, 0)) AND fecha > '2021-07-31')" : "";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    $sqlWrk = $this->cliente->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->cliente->Lookup->renderViewRow($rswrk[0]);
                        $this->cliente->ViewValue = $this->cliente->displayValue($arwrk);
                    } else {
                        $this->cliente->ViewValue = $this->cliente->CurrentValue;
                    }
                }
            } else {
                $this->cliente->ViewValue = null;
            }
            $this->cliente->ViewCustomAttributes = "";

            // id_documento
            $this->id_documento->ViewValue = $this->id_documento->CurrentValue;
            $curVal = trim(strval($this->id_documento->CurrentValue));
            if ($curVal != "") {
                $this->id_documento->ViewValue = $this->id_documento->lookupCacheOption($curVal);
                if ($this->id_documento->ViewValue === null) { // Lookup from database
                    $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->id_documento->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->id_documento->Lookup->renderViewRow($rswrk[0]);
                        $this->id_documento->ViewValue = $this->id_documento->displayValue($arwrk);
                    } else {
                        $this->id_documento->ViewValue = $this->id_documento->CurrentValue;
                    }
                }
            } else {
                $this->id_documento->ViewValue = null;
            }
            $this->id_documento->ViewCustomAttributes = "";

            // pivote
            $this->pivote->ViewValue = $this->pivote->CurrentValue;
            $this->pivote->ViewCustomAttributes = "";

            // fecha
            $this->fecha->ViewValue = $this->fecha->CurrentValue;
            $this->fecha->ViewValue = FormatDateTime($this->fecha->ViewValue, 7);
            $this->fecha->ViewCustomAttributes = "";

            // moneda
            $this->moneda->ViewValue = $this->moneda->CurrentValue;
            $this->moneda->ViewCustomAttributes = "";

            // pago
            $this->pago->ViewValue = $this->pago->CurrentValue;
            $this->pago->ViewValue = FormatNumber($this->pago->ViewValue, 2, -1, -1, -1);
            $this->pago->ViewCustomAttributes = "";

            // nota
            $this->nota->ViewValue = $this->nota->CurrentValue;
            $this->nota->ViewCustomAttributes = "";

            // fecha_registro
            $this->fecha_registro->ViewValue = $this->fecha_registro->CurrentValue;
            $this->fecha_registro->ViewValue = FormatDateTime($this->fecha_registro->ViewValue, 7);
            $this->fecha_registro->ViewCustomAttributes = "";

            // username
            $this->_username->ViewValue = $this->_username->CurrentValue;
            $curVal = trim(strval($this->_username->CurrentValue));
            if ($curVal != "") {
                $this->_username->ViewValue = $this->_username->lookupCacheOption($curVal);
                if ($this->_username->ViewValue === null) { // Lookup from database
                    $filterWrk = "`username`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $sqlWrk = $this->_username->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->_username->Lookup->renderViewRow($rswrk[0]);
                        $this->_username->ViewValue = $this->_username->displayValue($arwrk);
                    } else {
                        $this->_username->ViewValue = $this->_username->CurrentValue;
                    }
                }
            } else {
                $this->_username->ViewValue = null;
            }
            $this->_username->ViewCustomAttributes = "";

            // comprobante
            $this->comprobante->ViewValue = $this->comprobante->CurrentValue;
            $this->comprobante->ViewCustomAttributes = "";

            // tipo_pago
            $curVal = trim(strval($this->tipo_pago->CurrentValue));
            if ($curVal != "") {
                $this->tipo_pago->ViewValue = $this->tipo_pago->lookupCacheOption($curVal);
                if ($this->tipo_pago->ViewValue === null) { // Lookup from database
                    $filterWrk = "`valor1`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $lookupFilter = function() {
                        return ($_REQUEST["pago_divisa"] == "S") 
                ? "`codigo` = '009' AND valor1 IN ('EF','RD','ZL')" 
                : (isset($_REQUEST["dsc"]) 
                    ? ($_REQUEST["dsc"] >= 25 
                        ? "`codigo` = '009' AND valor1 IN ('EF','RD')" 
                        : "`codigo` = '009' AND valor1 NOT IN ('PC','PF','DV','NC','ND')") 
                    : "`codigo` = '009' AND valor1 NOT IN ('PC','PF','DV','NC','ND')");
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

            // pivote2
            $this->pivote2->ViewValue = $this->pivote2->CurrentValue;
            $this->pivote2->ViewCustomAttributes = "";

            // cliente
            $this->cliente->LinkCustomAttributes = "";
            $this->cliente->HrefValue = "";
            $this->cliente->TooltipValue = "";

            // pivote
            $this->pivote->LinkCustomAttributes = "";
            $this->pivote->HrefValue = "";
            $this->pivote->TooltipValue = "";

            // moneda
            $this->moneda->LinkCustomAttributes = "";
            $this->moneda->HrefValue = "";
            $this->moneda->TooltipValue = "";

            // pago
            $this->pago->LinkCustomAttributes = "";
            $this->pago->HrefValue = "";
            $this->pago->TooltipValue = "";

            // nota
            $this->nota->LinkCustomAttributes = "";
            $this->nota->HrefValue = "";
            $this->nota->TooltipValue = "";

            // tipo_pago
            $this->tipo_pago->LinkCustomAttributes = "";
            $this->tipo_pago->HrefValue = "";
            $this->tipo_pago->TooltipValue = "";

            // pivote2
            $this->pivote2->LinkCustomAttributes = "";
            $this->pivote2->HrefValue = "";
            $this->pivote2->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // cliente
            $this->cliente->EditCustomAttributes = "";
            $curVal = trim(strval($this->cliente->CurrentValue));
            if ($curVal != "") {
                $this->cliente->ViewValue = $this->cliente->lookupCacheOption($curVal);
            } else {
                $this->cliente->ViewValue = $this->cliente->Lookup !== null && is_array($this->cliente->Lookup->Options) ? $curVal : null;
            }
            if ($this->cliente->ViewValue !== null) { // Load from cache
                $this->cliente->EditValue = array_values($this->cliente->Lookup->Options);
                if ($this->cliente->ViewValue == "") {
                    $this->cliente->ViewValue = $Language->phrase("PleaseSelect");
                }
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`id`" . SearchString("=", $this->cliente->CurrentValue, DATATYPE_NUMBER, "");
                }
                $lookupFilter = function() {
                    return (CurrentPageID() == "add") ? 
            	"`id` IN 
            		(SELECT cliente 
            		FROM view_x_cobrar 
            		WHERE IFNULL(monto_pagar, 0) > 0 AND  
            		IFNULL(monto_pagar, 0) > (IFNULL(monto_pagado, 0) + IFNULL(retivamonto, 0) + IFNULL(retislrmonto, 0)) AND fecha > '2021-07-31')" : "";
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->cliente->Lookup->getSql(true, $filterWrk, $lookupFilter, $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->cliente->Lookup->renderViewRow($rswrk[0]);
                    $this->cliente->ViewValue = $this->cliente->displayValue($arwrk);
                } else {
                    $this->cliente->ViewValue = $Language->phrase("PleaseSelect");
                }
                $arwrk = $rswrk;
                $this->cliente->EditValue = $arwrk;
            }
            $this->cliente->PlaceHolder = RemoveHtml($this->cliente->caption());

            // pivote
            $this->pivote->EditAttrs["class"] = "form-control";
            $this->pivote->EditCustomAttributes = "";
            if (!$this->pivote->Raw) {
                $this->pivote->CurrentValue = HtmlDecode($this->pivote->CurrentValue);
            }
            $this->pivote->EditValue = HtmlEncode($this->pivote->CurrentValue);
            $this->pivote->PlaceHolder = RemoveHtml($this->pivote->caption());

            // moneda
            $this->moneda->EditAttrs["class"] = "form-control";
            $this->moneda->EditCustomAttributes = "";
            if (!$this->moneda->Raw) {
                $this->moneda->CurrentValue = HtmlDecode($this->moneda->CurrentValue);
            }
            $this->moneda->EditValue = HtmlEncode($this->moneda->CurrentValue);
            $this->moneda->PlaceHolder = RemoveHtml($this->moneda->caption());

            // pago
            $this->pago->EditAttrs["class"] = "form-control";
            $this->pago->EditCustomAttributes = "";
            $this->pago->EditValue = HtmlEncode($this->pago->CurrentValue);
            $this->pago->PlaceHolder = RemoveHtml($this->pago->caption());
            if (strval($this->pago->EditValue) != "" && is_numeric($this->pago->EditValue)) {
                $this->pago->EditValue = FormatNumber($this->pago->EditValue, -2, -1, -2, -1);
            }

            // nota
            $this->nota->EditAttrs["class"] = "form-control";
            $this->nota->EditCustomAttributes = "";
            if (!$this->nota->Raw) {
                $this->nota->CurrentValue = HtmlDecode($this->nota->CurrentValue);
            }
            $this->nota->EditValue = HtmlEncode($this->nota->CurrentValue);
            $this->nota->PlaceHolder = RemoveHtml($this->nota->caption());

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
                    return ($_REQUEST["pago_divisa"] == "S") 
                ? "`codigo` = '009' AND valor1 IN ('EF','RD','ZL')" 
                : (isset($_REQUEST["dsc"]) 
                    ? ($_REQUEST["dsc"] >= 25 
                        ? "`codigo` = '009' AND valor1 IN ('EF','RD')" 
                        : "`codigo` = '009' AND valor1 NOT IN ('PC','PF','DV','NC','ND')") 
                    : "`codigo` = '009' AND valor1 NOT IN ('PC','PF','DV','NC','ND')");
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->tipo_pago->Lookup->getSql(true, $filterWrk, $lookupFilter, $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->tipo_pago->EditValue = $arwrk;
            }
            $this->tipo_pago->PlaceHolder = RemoveHtml($this->tipo_pago->caption());

            // pivote2
            $this->pivote2->EditAttrs["class"] = "form-control";
            $this->pivote2->EditCustomAttributes = "";
            if (!$this->pivote2->Raw) {
                $this->pivote2->CurrentValue = HtmlDecode($this->pivote2->CurrentValue);
            }
            $this->pivote2->EditValue = HtmlEncode($this->pivote2->CurrentValue);
            $this->pivote2->PlaceHolder = RemoveHtml($this->pivote2->caption());

            // Add refer script

            // cliente
            $this->cliente->LinkCustomAttributes = "";
            $this->cliente->HrefValue = "";

            // pivote
            $this->pivote->LinkCustomAttributes = "";
            $this->pivote->HrefValue = "";

            // moneda
            $this->moneda->LinkCustomAttributes = "";
            $this->moneda->HrefValue = "";

            // pago
            $this->pago->LinkCustomAttributes = "";
            $this->pago->HrefValue = "";

            // nota
            $this->nota->LinkCustomAttributes = "";
            $this->nota->HrefValue = "";

            // tipo_pago
            $this->tipo_pago->LinkCustomAttributes = "";
            $this->tipo_pago->HrefValue = "";

            // pivote2
            $this->pivote2->LinkCustomAttributes = "";
            $this->pivote2->HrefValue = "";
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
        if ($this->cliente->Required) {
            if (!$this->cliente->IsDetailKey && EmptyValue($this->cliente->FormValue)) {
                $this->cliente->addErrorMessage(str_replace("%s", $this->cliente->caption(), $this->cliente->RequiredErrorMessage));
            }
        }
        if ($this->pivote->Required) {
            if (!$this->pivote->IsDetailKey && EmptyValue($this->pivote->FormValue)) {
                $this->pivote->addErrorMessage(str_replace("%s", $this->pivote->caption(), $this->pivote->RequiredErrorMessage));
            }
        }
        if ($this->moneda->Required) {
            if (!$this->moneda->IsDetailKey && EmptyValue($this->moneda->FormValue)) {
                $this->moneda->addErrorMessage(str_replace("%s", $this->moneda->caption(), $this->moneda->RequiredErrorMessage));
            }
        }
        if ($this->pago->Required) {
            if (!$this->pago->IsDetailKey && EmptyValue($this->pago->FormValue)) {
                $this->pago->addErrorMessage(str_replace("%s", $this->pago->caption(), $this->pago->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->pago->FormValue)) {
            $this->pago->addErrorMessage($this->pago->getErrorMessage(false));
        }
        if ($this->nota->Required) {
            if (!$this->nota->IsDetailKey && EmptyValue($this->nota->FormValue)) {
                $this->nota->addErrorMessage(str_replace("%s", $this->nota->caption(), $this->nota->RequiredErrorMessage));
            }
        }
        if ($this->tipo_pago->Required) {
            if (!$this->tipo_pago->IsDetailKey && EmptyValue($this->tipo_pago->FormValue)) {
                $this->tipo_pago->addErrorMessage(str_replace("%s", $this->tipo_pago->caption(), $this->tipo_pago->RequiredErrorMessage));
            }
        }
        if ($this->pivote2->Required) {
            if (!$this->pivote2->IsDetailKey && EmptyValue($this->pivote2->FormValue)) {
                $this->pivote2->addErrorMessage(str_replace("%s", $this->pivote2->caption(), $this->pivote2->RequiredErrorMessage));
            }
        }

        // Validate detail grid
        $detailTblVar = explode(",", $this->getCurrentDetailTable());
        $detailPage = Container("CobrosClienteDetalleGrid");
        if (in_array("cobros_cliente_detalle", $detailTblVar) && $detailPage->DetailAdd) {
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

        // cliente
        $this->cliente->setDbValueDef($rsnew, $this->cliente->CurrentValue, 0, false);

        // pivote
        $this->pivote->setDbValueDef($rsnew, $this->pivote->CurrentValue, null, false);

        // moneda
        $this->moneda->setDbValueDef($rsnew, $this->moneda->CurrentValue, null, false);

        // pago
        $this->pago->setDbValueDef($rsnew, $this->pago->CurrentValue, null, false);

        // nota
        $this->nota->setDbValueDef($rsnew, $this->nota->CurrentValue, null, false);

        // tipo_pago
        $this->tipo_pago->setDbValueDef($rsnew, $this->tipo_pago->CurrentValue, null, false);

        // pivote2
        $this->pivote2->setDbValueDef($rsnew, $this->pivote2->CurrentValue, null, false);

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
            $detailPage = Container("CobrosClienteDetalleGrid");
            if (in_array("cobros_cliente_detalle", $detailTblVar) && $detailPage->DetailAdd) {
                $detailPage->cobros_cliente->setSessionValue($this->id->CurrentValue); // Set master key
                $Security->loadCurrentUserLevel($this->ProjectID . "cobros_cliente_detalle"); // Load user level of detail table
                $addRow = $detailPage->gridInsert();
                $Security->loadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
                if (!$addRow) {
                $detailPage->cobros_cliente->setSessionValue(""); // Clear master key if insert failed
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
            if (in_array("cobros_cliente_detalle", $detailTblVar)) {
                $detailPageObj = Container("CobrosClienteDetalleGrid");
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
                    $detailPageObj->cobros_cliente->IsDetailKey = true;
                    $detailPageObj->cobros_cliente->CurrentValue = $this->id->CurrentValue;
                    $detailPageObj->cobros_cliente->setSessionValue($detailPageObj->cobros_cliente->CurrentValue);
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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("CobrosClienteList"), "", $this->TableVar, true);
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
                case "x_cliente":
                    $lookupFilter = function () {
                        return (CurrentPageID() == "add") ? 
	"`id` IN 
		(SELECT cliente 
		FROM view_x_cobrar 
		WHERE IFNULL(monto_pagar, 0) > 0 AND  
		IFNULL(monto_pagar, 0) > (IFNULL(monto_pagado, 0) + IFNULL(retivamonto, 0) + IFNULL(retislrmonto, 0)) AND fecha > '2021-07-31')" : "";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    break;
                case "x_id_documento":
                    break;
                case "x__username":
                    break;
                case "x_tipo_pago":
                    $lookupFilter = function () {
                        return ($_REQUEST["pago_divisa"] == "S") 
    ? "`codigo` = '009' AND valor1 IN ('EF','RD','ZL')" 
    : (isset($_REQUEST["dsc"]) 
        ? ($_REQUEST["dsc"] >= 25 
            ? "`codigo` = '009' AND valor1 IN ('EF','RD')" 
            : "`codigo` = '009' AND valor1 NOT IN ('PC','PF','DV','NC','ND')") 
        : "`codigo` = '009' AND valor1 NOT IN ('PC','PF','DV','NC','ND')");
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
        $id = isset($_GET["id_compra"]) ? intval($_GET["id_compra"]) : 0;
        $sql = "SELECT total, IFNULL(descuento, 0) AS descuento FROM salidas WHERE id = $id;";
        $row = ExecuteRow($sql);
        $total_pagar = floatval($row["total"]);
        $descuento = floatval($row["descuento"]);
        $sql = "SELECT tasa FROM tasa_usd WHERE moneda = 'USD' ORDER BY id DESC LIMIT 0, 1;";
        $tasa_usd = ExecuteScalar($sql);
        $totBs = 0;
        $totUSD = 0;
        if($id > 0) { 
        	$sql = "SELECT cliente FROM salidas WHERE id = $id;";
        	$cliente = ExecuteScalar($sql);
        	$sql = "SELECT ci_rif FROM cliente WHERE id = $cliente;";
        	$ci_rif = ExecuteScalar($sql);

        	/* Tarigo Saldo en USD y en Bs */
            $sql = "SELECT moneda, monto_usd, saldo FROM recarga WHERE cliente = $cliente ORDER BY id DESC;"; 
            if($row = ExecuteRow($sql)) $totBs = $row["saldo"];
        	$sql = "SELECT moneda, monto_usd, saldo FROM recarga2 WHERE cliente = $cliente ORDER BY id DESC;"; 
        	if($row = ExecuteRow($sql)) $totUSD = $row["saldo"];
            $si_puede = "N";
            if($totUSD >= $total_pagar) $si_puede = "S";
            if($descuento < 25) $si_puede = "S";
        	$mis_depositos = " <small>$ $totUSD | Bs $totBs </small> <input type='hidden' id='puede' value='$si_puede'>";
            // $mis_depositos = " <small>Abonos $$totUSD | Bs $$totBs</small> <input type='hidden' id='puede' value='$si_puede'>";
        	/********* **************** *********/
            $saldo = 0.00;
        	$sql = "SELECT id, saldo FROM recarga WHERE cliente = $cliente ORDER BY id DESC LIMIT 0, 1;";
        	if($row = ExecuteRow($sql)) {
        		$saldo = floatval($row["saldo"]);
            }
            $sql = "SELECT id, saldo FROM recarga2 WHERE cliente = $cliente ORDER BY id DESC LIMIT 0, 1;";
            if($row = ExecuteRow($sql)) {
                $saldo += floatval($row["saldo"]);
            }
    		if($saldo > 0.00) { 
    			$tipo_name = '<span class="text-success"><strong>Saldo: </strong>$ ' . number_format($saldo, 2, ".", ",") . ' (' . $mis_depositos . ')</span>';
    		}
    		else { 
    			$si_puede = "S";
    			$tipo_name = '<span class="text-danger"><strong>Saldo: </strong>$ ' . number_format($saldo, 2, ".", ",") . ' <input type="hidden" id="puede" value="' . $si_puede . '"></span>';
    		}
    		$this->setTableCaption("<small>" . $tipo_name . "<br>C.I: " . $ci_rif . " <b><i>BCV Bs $tasa_usd</i></b></small>");
        }
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
