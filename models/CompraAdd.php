<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class CompraAdd extends Compra
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'compra';

    // Page object name
    public $PageObjName = "CompraAdd";

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

        // Table object (compra)
        if (!isset($GLOBALS["compra"]) || get_class($GLOBALS["compra"]) == PROJECT_NAMESPACE . "compra") {
            $GLOBALS["compra"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'compra');
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
                $doc = new $class(Container("compra"));
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
                    if ($pageName == "CompraView") {
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
    public $MultiPages; // Multi pages object

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
        $this->proveedor->setVisibility();
        $this->tipo_documento->setVisibility();
        $this->doc_afectado->setVisibility();
        $this->documento->setVisibility();
        $this->nro_control->setVisibility();
        $this->fecha->setVisibility();
        $this->descripcion->setVisibility();
        $this->aplica_retencion->Visible = false;
        $this->monto_exento->setVisibility();
        $this->monto_gravado->setVisibility();
        $this->alicuota->setVisibility();
        $this->monto_iva->Visible = false;
        $this->monto_total->Visible = false;
        $this->monto_pagar->Visible = false;
        $this->ret_iva->Visible = false;
        $this->ref_iva->Visible = false;
        $this->ret_islr->Visible = false;
        $this->ref_islr->Visible = false;
        $this->ret_municipal->Visible = false;
        $this->ref_municipal->Visible = false;
        $this->fecha_registro->Visible = false;
        $this->_username->Visible = false;
        $this->comprobante->Visible = false;
        $this->tipo_iva->Visible = false;
        $this->tipo_islr->Visible = false;
        $this->sustraendo->Visible = false;
        $this->tipo_municipal->Visible = false;
        $this->anulado->Visible = false;
        $this->hideFieldsForAddEdit();

        // Do not use lookup cache
        $this->setUseLookupCache(false);

        // Set up multi page object
        $this->setupMultiPages();

        // Global Page Loading event (in userfn*.php)
        Page_Loading();

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Set up lookup cache
        $this->setupLookupOptions($this->proveedor);
        $this->setupLookupOptions($this->_username);
        $this->setupLookupOptions($this->comprobante);

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
                    $this->terminate("CompraList"); // No matching record, return to list
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
                    if (GetPageName($returnUrl) == "CompraList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "CompraView") {
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
        $this->proveedor->CurrentValue = null;
        $this->proveedor->OldValue = $this->proveedor->CurrentValue;
        $this->tipo_documento->CurrentValue = null;
        $this->tipo_documento->OldValue = $this->tipo_documento->CurrentValue;
        $this->doc_afectado->CurrentValue = null;
        $this->doc_afectado->OldValue = $this->doc_afectado->CurrentValue;
        $this->documento->CurrentValue = null;
        $this->documento->OldValue = $this->documento->CurrentValue;
        $this->nro_control->CurrentValue = null;
        $this->nro_control->OldValue = $this->nro_control->CurrentValue;
        $this->fecha->CurrentValue = null;
        $this->fecha->OldValue = $this->fecha->CurrentValue;
        $this->descripcion->CurrentValue = null;
        $this->descripcion->OldValue = $this->descripcion->CurrentValue;
        $this->aplica_retencion->CurrentValue = null;
        $this->aplica_retencion->OldValue = $this->aplica_retencion->CurrentValue;
        $this->monto_exento->CurrentValue = null;
        $this->monto_exento->OldValue = $this->monto_exento->CurrentValue;
        $this->monto_gravado->CurrentValue = null;
        $this->monto_gravado->OldValue = $this->monto_gravado->CurrentValue;
        $this->alicuota->CurrentValue = null;
        $this->alicuota->OldValue = $this->alicuota->CurrentValue;
        $this->monto_iva->CurrentValue = null;
        $this->monto_iva->OldValue = $this->monto_iva->CurrentValue;
        $this->monto_total->CurrentValue = null;
        $this->monto_total->OldValue = $this->monto_total->CurrentValue;
        $this->monto_pagar->CurrentValue = null;
        $this->monto_pagar->OldValue = $this->monto_pagar->CurrentValue;
        $this->ret_iva->CurrentValue = null;
        $this->ret_iva->OldValue = $this->ret_iva->CurrentValue;
        $this->ref_iva->CurrentValue = null;
        $this->ref_iva->OldValue = $this->ref_iva->CurrentValue;
        $this->ret_islr->CurrentValue = null;
        $this->ret_islr->OldValue = $this->ret_islr->CurrentValue;
        $this->ref_islr->CurrentValue = null;
        $this->ref_islr->OldValue = $this->ref_islr->CurrentValue;
        $this->ret_municipal->CurrentValue = null;
        $this->ret_municipal->OldValue = $this->ret_municipal->CurrentValue;
        $this->ref_municipal->CurrentValue = null;
        $this->ref_municipal->OldValue = $this->ref_municipal->CurrentValue;
        $this->fecha_registro->CurrentValue = null;
        $this->fecha_registro->OldValue = $this->fecha_registro->CurrentValue;
        $this->_username->CurrentValue = null;
        $this->_username->OldValue = $this->_username->CurrentValue;
        $this->comprobante->CurrentValue = null;
        $this->comprobante->OldValue = $this->comprobante->CurrentValue;
        $this->tipo_iva->CurrentValue = null;
        $this->tipo_iva->OldValue = $this->tipo_iva->CurrentValue;
        $this->tipo_islr->CurrentValue = null;
        $this->tipo_islr->OldValue = $this->tipo_islr->CurrentValue;
        $this->sustraendo->CurrentValue = null;
        $this->sustraendo->OldValue = $this->sustraendo->CurrentValue;
        $this->tipo_municipal->CurrentValue = null;
        $this->tipo_municipal->OldValue = $this->tipo_municipal->CurrentValue;
        $this->anulado->CurrentValue = "N";
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;

        // Check field name 'proveedor' first before field var 'x_proveedor'
        $val = $CurrentForm->hasValue("proveedor") ? $CurrentForm->getValue("proveedor") : $CurrentForm->getValue("x_proveedor");
        if (!$this->proveedor->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->proveedor->Visible = false; // Disable update for API request
            } else {
                $this->proveedor->setFormValue($val);
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

        // Check field name 'doc_afectado' first before field var 'x_doc_afectado'
        $val = $CurrentForm->hasValue("doc_afectado") ? $CurrentForm->getValue("doc_afectado") : $CurrentForm->getValue("x_doc_afectado");
        if (!$this->doc_afectado->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->doc_afectado->Visible = false; // Disable update for API request
            } else {
                $this->doc_afectado->setFormValue($val);
            }
        }

        // Check field name 'documento' first before field var 'x_documento'
        $val = $CurrentForm->hasValue("documento") ? $CurrentForm->getValue("documento") : $CurrentForm->getValue("x_documento");
        if (!$this->documento->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->documento->Visible = false; // Disable update for API request
            } else {
                $this->documento->setFormValue($val);
            }
        }

        // Check field name 'nro_control' first before field var 'x_nro_control'
        $val = $CurrentForm->hasValue("nro_control") ? $CurrentForm->getValue("nro_control") : $CurrentForm->getValue("x_nro_control");
        if (!$this->nro_control->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->nro_control->Visible = false; // Disable update for API request
            } else {
                $this->nro_control->setFormValue($val);
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
            $this->fecha->CurrentValue = UnFormatDateTime($this->fecha->CurrentValue, 7);
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

        // Check field name 'monto_exento' first before field var 'x_monto_exento'
        $val = $CurrentForm->hasValue("monto_exento") ? $CurrentForm->getValue("monto_exento") : $CurrentForm->getValue("x_monto_exento");
        if (!$this->monto_exento->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->monto_exento->Visible = false; // Disable update for API request
            } else {
                $this->monto_exento->setFormValue($val);
            }
        }

        // Check field name 'monto_gravado' first before field var 'x_monto_gravado'
        $val = $CurrentForm->hasValue("monto_gravado") ? $CurrentForm->getValue("monto_gravado") : $CurrentForm->getValue("x_monto_gravado");
        if (!$this->monto_gravado->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->monto_gravado->Visible = false; // Disable update for API request
            } else {
                $this->monto_gravado->setFormValue($val);
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

        // Check field name 'id' first before field var 'x_id'
        $val = $CurrentForm->hasValue("id") ? $CurrentForm->getValue("id") : $CurrentForm->getValue("x_id");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->proveedor->CurrentValue = $this->proveedor->FormValue;
        $this->tipo_documento->CurrentValue = $this->tipo_documento->FormValue;
        $this->doc_afectado->CurrentValue = $this->doc_afectado->FormValue;
        $this->documento->CurrentValue = $this->documento->FormValue;
        $this->nro_control->CurrentValue = $this->nro_control->FormValue;
        $this->fecha->CurrentValue = $this->fecha->FormValue;
        $this->fecha->CurrentValue = UnFormatDateTime($this->fecha->CurrentValue, 7);
        $this->descripcion->CurrentValue = $this->descripcion->FormValue;
        $this->monto_exento->CurrentValue = $this->monto_exento->FormValue;
        $this->monto_gravado->CurrentValue = $this->monto_gravado->FormValue;
        $this->alicuota->CurrentValue = $this->alicuota->FormValue;
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
        $this->proveedor->setDbValue($row['proveedor']);
        $this->tipo_documento->setDbValue($row['tipo_documento']);
        $this->doc_afectado->setDbValue($row['doc_afectado']);
        $this->documento->setDbValue($row['documento']);
        $this->nro_control->setDbValue($row['nro_control']);
        $this->fecha->setDbValue($row['fecha']);
        $this->descripcion->setDbValue($row['descripcion']);
        $this->aplica_retencion->setDbValue($row['aplica_retencion']);
        $this->monto_exento->setDbValue($row['monto_exento']);
        $this->monto_gravado->setDbValue($row['monto_gravado']);
        $this->alicuota->setDbValue($row['alicuota']);
        $this->monto_iva->setDbValue($row['monto_iva']);
        $this->monto_total->setDbValue($row['monto_total']);
        $this->monto_pagar->setDbValue($row['monto_pagar']);
        $this->ret_iva->setDbValue($row['ret_iva']);
        $this->ref_iva->setDbValue($row['ref_iva']);
        $this->ret_islr->setDbValue($row['ret_islr']);
        $this->ref_islr->setDbValue($row['ref_islr']);
        $this->ret_municipal->setDbValue($row['ret_municipal']);
        $this->ref_municipal->setDbValue($row['ref_municipal']);
        $this->fecha_registro->setDbValue($row['fecha_registro']);
        $this->_username->setDbValue($row['username']);
        $this->comprobante->setDbValue($row['comprobante']);
        $this->tipo_iva->setDbValue($row['tipo_iva']);
        $this->tipo_islr->setDbValue($row['tipo_islr']);
        $this->sustraendo->setDbValue($row['sustraendo']);
        $this->tipo_municipal->setDbValue($row['tipo_municipal']);
        $this->anulado->setDbValue($row['anulado']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $this->loadDefaultValues();
        $row = [];
        $row['id'] = $this->id->CurrentValue;
        $row['proveedor'] = $this->proveedor->CurrentValue;
        $row['tipo_documento'] = $this->tipo_documento->CurrentValue;
        $row['doc_afectado'] = $this->doc_afectado->CurrentValue;
        $row['documento'] = $this->documento->CurrentValue;
        $row['nro_control'] = $this->nro_control->CurrentValue;
        $row['fecha'] = $this->fecha->CurrentValue;
        $row['descripcion'] = $this->descripcion->CurrentValue;
        $row['aplica_retencion'] = $this->aplica_retencion->CurrentValue;
        $row['monto_exento'] = $this->monto_exento->CurrentValue;
        $row['monto_gravado'] = $this->monto_gravado->CurrentValue;
        $row['alicuota'] = $this->alicuota->CurrentValue;
        $row['monto_iva'] = $this->monto_iva->CurrentValue;
        $row['monto_total'] = $this->monto_total->CurrentValue;
        $row['monto_pagar'] = $this->monto_pagar->CurrentValue;
        $row['ret_iva'] = $this->ret_iva->CurrentValue;
        $row['ref_iva'] = $this->ref_iva->CurrentValue;
        $row['ret_islr'] = $this->ret_islr->CurrentValue;
        $row['ref_islr'] = $this->ref_islr->CurrentValue;
        $row['ret_municipal'] = $this->ret_municipal->CurrentValue;
        $row['ref_municipal'] = $this->ref_municipal->CurrentValue;
        $row['fecha_registro'] = $this->fecha_registro->CurrentValue;
        $row['username'] = $this->_username->CurrentValue;
        $row['comprobante'] = $this->comprobante->CurrentValue;
        $row['tipo_iva'] = $this->tipo_iva->CurrentValue;
        $row['tipo_islr'] = $this->tipo_islr->CurrentValue;
        $row['sustraendo'] = $this->sustraendo->CurrentValue;
        $row['tipo_municipal'] = $this->tipo_municipal->CurrentValue;
        $row['anulado'] = $this->anulado->CurrentValue;
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
        if ($this->monto_exento->FormValue == $this->monto_exento->CurrentValue && is_numeric(ConvertToFloatString($this->monto_exento->CurrentValue))) {
            $this->monto_exento->CurrentValue = ConvertToFloatString($this->monto_exento->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->monto_gravado->FormValue == $this->monto_gravado->CurrentValue && is_numeric(ConvertToFloatString($this->monto_gravado->CurrentValue))) {
            $this->monto_gravado->CurrentValue = ConvertToFloatString($this->monto_gravado->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->alicuota->FormValue == $this->alicuota->CurrentValue && is_numeric(ConvertToFloatString($this->alicuota->CurrentValue))) {
            $this->alicuota->CurrentValue = ConvertToFloatString($this->alicuota->CurrentValue);
        }

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // id

        // proveedor

        // tipo_documento

        // doc_afectado

        // documento

        // nro_control

        // fecha

        // descripcion

        // aplica_retencion

        // monto_exento

        // monto_gravado

        // alicuota

        // monto_iva

        // monto_total

        // monto_pagar

        // ret_iva

        // ref_iva

        // ret_islr

        // ref_islr

        // ret_municipal

        // ref_municipal

        // fecha_registro

        // username

        // comprobante

        // tipo_iva

        // tipo_islr

        // sustraendo

        // tipo_municipal

        // anulado
        if ($this->RowType == ROWTYPE_VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;
            $this->id->ViewCustomAttributes = "";

            // proveedor
            $curVal = trim(strval($this->proveedor->CurrentValue));
            if ($curVal != "") {
                $this->proveedor->ViewValue = $this->proveedor->lookupCacheOption($curVal);
                if ($this->proveedor->ViewValue === null) { // Lookup from database
                    $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->proveedor->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->proveedor->Lookup->renderViewRow($rswrk[0]);
                        $this->proveedor->ViewValue = $this->proveedor->displayValue($arwrk);
                    } else {
                        $this->proveedor->ViewValue = $this->proveedor->CurrentValue;
                    }
                }
            } else {
                $this->proveedor->ViewValue = null;
            }
            $this->proveedor->ViewCustomAttributes = "";

            // tipo_documento
            if (strval($this->tipo_documento->CurrentValue) != "") {
                $this->tipo_documento->ViewValue = $this->tipo_documento->optionCaption($this->tipo_documento->CurrentValue);
            } else {
                $this->tipo_documento->ViewValue = null;
            }
            $this->tipo_documento->ViewCustomAttributes = "";

            // doc_afectado
            $this->doc_afectado->ViewValue = $this->doc_afectado->CurrentValue;
            $this->doc_afectado->ViewCustomAttributes = "";

            // documento
            $this->documento->ViewValue = $this->documento->CurrentValue;
            $this->documento->ViewCustomAttributes = "";

            // nro_control
            $this->nro_control->ViewValue = $this->nro_control->CurrentValue;
            $this->nro_control->ViewCustomAttributes = "";

            // fecha
            $this->fecha->ViewValue = $this->fecha->CurrentValue;
            $this->fecha->ViewValue = FormatDateTime($this->fecha->ViewValue, 7);
            $this->fecha->ViewCustomAttributes = "";

            // descripcion
            $this->descripcion->ViewValue = $this->descripcion->CurrentValue;
            $this->descripcion->ViewCustomAttributes = "";

            // aplica_retencion
            if (strval($this->aplica_retencion->CurrentValue) != "") {
                $this->aplica_retencion->ViewValue = $this->aplica_retencion->optionCaption($this->aplica_retencion->CurrentValue);
            } else {
                $this->aplica_retencion->ViewValue = null;
            }
            $this->aplica_retencion->ViewCustomAttributes = "";

            // monto_exento
            $this->monto_exento->ViewValue = $this->monto_exento->CurrentValue;
            $this->monto_exento->ViewValue = FormatNumber($this->monto_exento->ViewValue, 2, -1, -1, -1);
            $this->monto_exento->ViewCustomAttributes = "";

            // monto_gravado
            $this->monto_gravado->ViewValue = $this->monto_gravado->CurrentValue;
            $this->monto_gravado->ViewValue = FormatNumber($this->monto_gravado->ViewValue, 2, -1, -1, -1);
            $this->monto_gravado->ViewCustomAttributes = "";

            // alicuota
            $this->alicuota->ViewValue = $this->alicuota->CurrentValue;
            $this->alicuota->ViewValue = FormatNumber($this->alicuota->ViewValue, 2, -1, -1, -1);
            $this->alicuota->ViewCustomAttributes = "";

            // monto_iva
            $this->monto_iva->ViewValue = $this->monto_iva->CurrentValue;
            $this->monto_iva->ViewValue = FormatNumber($this->monto_iva->ViewValue, 2, -1, -1, -1);
            $this->monto_iva->ViewCustomAttributes = "";

            // monto_total
            $this->monto_total->ViewValue = $this->monto_total->CurrentValue;
            $this->monto_total->ViewValue = FormatNumber($this->monto_total->ViewValue, 2, -1, -1, -1);
            $this->monto_total->ViewCustomAttributes = "";

            // monto_pagar
            $this->monto_pagar->ViewValue = $this->monto_pagar->CurrentValue;
            $this->monto_pagar->ViewValue = FormatNumber($this->monto_pagar->ViewValue, 2, -1, -1, -1);
            $this->monto_pagar->CssClass = "font-weight-bold";
            $this->monto_pagar->ViewCustomAttributes = "";

            // ret_iva
            $this->ret_iva->ViewValue = $this->ret_iva->CurrentValue;
            $this->ret_iva->ViewValue = FormatNumber($this->ret_iva->ViewValue, 2, -1, -1, -1);
            $this->ret_iva->CssClass = "font-weight-bold";
            $this->ret_iva->ViewCustomAttributes = "";

            // ref_iva
            $this->ref_iva->ViewValue = $this->ref_iva->CurrentValue;
            $this->ref_iva->ViewCustomAttributes = "";

            // ret_islr
            $this->ret_islr->ViewValue = $this->ret_islr->CurrentValue;
            $this->ret_islr->ViewValue = FormatNumber($this->ret_islr->ViewValue, 2, -1, -1, -1);
            $this->ret_islr->CssClass = "font-weight-bold";
            $this->ret_islr->ViewCustomAttributes = "";

            // ref_islr
            $this->ref_islr->ViewValue = $this->ref_islr->CurrentValue;
            $this->ref_islr->ViewCustomAttributes = "";

            // ret_municipal
            $this->ret_municipal->ViewValue = $this->ret_municipal->CurrentValue;
            $this->ret_municipal->ViewValue = FormatNumber($this->ret_municipal->ViewValue, 2, -2, -2, -2);
            $this->ret_municipal->CssClass = "font-weight-bold";
            $this->ret_municipal->ViewCustomAttributes = "";

            // ref_municipal
            $this->ref_municipal->ViewValue = $this->ref_municipal->CurrentValue;
            $this->ref_municipal->ViewCustomAttributes = "";

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
            $curVal = trim(strval($this->comprobante->CurrentValue));
            if ($curVal != "") {
                $this->comprobante->ViewValue = $this->comprobante->lookupCacheOption($curVal);
                if ($this->comprobante->ViewValue === null) { // Lookup from database
                    $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->comprobante->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->comprobante->Lookup->renderViewRow($rswrk[0]);
                        $this->comprobante->ViewValue = $this->comprobante->displayValue($arwrk);
                    } else {
                        $this->comprobante->ViewValue = $this->comprobante->CurrentValue;
                    }
                }
            } else {
                $this->comprobante->ViewValue = null;
            }
            $this->comprobante->ViewCustomAttributes = "";

            // tipo_iva
            $this->tipo_iva->ViewValue = $this->tipo_iva->CurrentValue;
            $this->tipo_iva->ViewCustomAttributes = "";

            // tipo_islr
            $this->tipo_islr->ViewValue = $this->tipo_islr->CurrentValue;
            $this->tipo_islr->ViewCustomAttributes = "";

            // sustraendo
            $this->sustraendo->ViewValue = $this->sustraendo->CurrentValue;
            $this->sustraendo->ViewValue = FormatNumber($this->sustraendo->ViewValue, $this->sustraendo->DefaultDecimalPrecision);
            $this->sustraendo->ViewCustomAttributes = "";

            // tipo_municipal
            $this->tipo_municipal->ViewValue = $this->tipo_municipal->CurrentValue;
            $this->tipo_municipal->ViewCustomAttributes = "";

            // anulado
            if (strval($this->anulado->CurrentValue) != "") {
                $this->anulado->ViewValue = $this->anulado->optionCaption($this->anulado->CurrentValue);
            } else {
                $this->anulado->ViewValue = null;
            }
            $this->anulado->ViewCustomAttributes = "";

            // proveedor
            $this->proveedor->LinkCustomAttributes = "";
            $this->proveedor->HrefValue = "";
            $this->proveedor->TooltipValue = "";

            // tipo_documento
            $this->tipo_documento->LinkCustomAttributes = "";
            $this->tipo_documento->HrefValue = "";
            $this->tipo_documento->TooltipValue = "";

            // doc_afectado
            $this->doc_afectado->LinkCustomAttributes = "";
            $this->doc_afectado->HrefValue = "";
            $this->doc_afectado->TooltipValue = "";

            // documento
            $this->documento->LinkCustomAttributes = "";
            $this->documento->HrefValue = "";
            $this->documento->TooltipValue = "";

            // nro_control
            $this->nro_control->LinkCustomAttributes = "";
            $this->nro_control->HrefValue = "";
            $this->nro_control->TooltipValue = "";

            // fecha
            $this->fecha->LinkCustomAttributes = "";
            $this->fecha->HrefValue = "";
            $this->fecha->TooltipValue = "";

            // descripcion
            $this->descripcion->LinkCustomAttributes = "";
            $this->descripcion->HrefValue = "";
            $this->descripcion->TooltipValue = "";

            // monto_exento
            $this->monto_exento->LinkCustomAttributes = "";
            $this->monto_exento->HrefValue = "";
            $this->monto_exento->TooltipValue = "";

            // monto_gravado
            $this->monto_gravado->LinkCustomAttributes = "";
            $this->monto_gravado->HrefValue = "";
            $this->monto_gravado->TooltipValue = "";

            // alicuota
            $this->alicuota->LinkCustomAttributes = "";
            $this->alicuota->HrefValue = "";
            $this->alicuota->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // proveedor
            $this->proveedor->EditCustomAttributes = "";
            $curVal = trim(strval($this->proveedor->CurrentValue));
            if ($curVal != "") {
                $this->proveedor->ViewValue = $this->proveedor->lookupCacheOption($curVal);
            } else {
                $this->proveedor->ViewValue = $this->proveedor->Lookup !== null && is_array($this->proveedor->Lookup->Options) ? $curVal : null;
            }
            if ($this->proveedor->ViewValue !== null) { // Load from cache
                $this->proveedor->EditValue = array_values($this->proveedor->Lookup->Options);
                if ($this->proveedor->ViewValue == "") {
                    $this->proveedor->ViewValue = $Language->phrase("PleaseSelect");
                }
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`id`" . SearchString("=", $this->proveedor->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->proveedor->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->proveedor->Lookup->renderViewRow($rswrk[0]);
                    $this->proveedor->ViewValue = $this->proveedor->displayValue($arwrk);
                } else {
                    $this->proveedor->ViewValue = $Language->phrase("PleaseSelect");
                }
                $arwrk = $rswrk;
                $this->proveedor->EditValue = $arwrk;
            }
            $this->proveedor->PlaceHolder = RemoveHtml($this->proveedor->caption());

            // tipo_documento
            $this->tipo_documento->EditAttrs["class"] = "form-control";
            $this->tipo_documento->EditCustomAttributes = "";
            $this->tipo_documento->EditValue = $this->tipo_documento->options(true);
            $this->tipo_documento->PlaceHolder = RemoveHtml($this->tipo_documento->caption());

            // doc_afectado
            $this->doc_afectado->EditAttrs["class"] = "form-control";
            $this->doc_afectado->EditCustomAttributes = "";
            if (!$this->doc_afectado->Raw) {
                $this->doc_afectado->CurrentValue = HtmlDecode($this->doc_afectado->CurrentValue);
            }
            $this->doc_afectado->EditValue = HtmlEncode($this->doc_afectado->CurrentValue);
            $this->doc_afectado->PlaceHolder = RemoveHtml($this->doc_afectado->caption());

            // documento
            $this->documento->EditAttrs["class"] = "form-control";
            $this->documento->EditCustomAttributes = "";
            if (!$this->documento->Raw) {
                $this->documento->CurrentValue = HtmlDecode($this->documento->CurrentValue);
            }
            $this->documento->EditValue = HtmlEncode($this->documento->CurrentValue);
            $this->documento->PlaceHolder = RemoveHtml($this->documento->caption());

            // nro_control
            $this->nro_control->EditAttrs["class"] = "form-control";
            $this->nro_control->EditCustomAttributes = "";
            if (!$this->nro_control->Raw) {
                $this->nro_control->CurrentValue = HtmlDecode($this->nro_control->CurrentValue);
            }
            $this->nro_control->EditValue = HtmlEncode($this->nro_control->CurrentValue);
            $this->nro_control->PlaceHolder = RemoveHtml($this->nro_control->caption());

            // fecha
            $this->fecha->EditAttrs["class"] = "form-control";
            $this->fecha->EditCustomAttributes = "";
            $this->fecha->EditValue = HtmlEncode(FormatDateTime($this->fecha->CurrentValue, 7));
            $this->fecha->PlaceHolder = RemoveHtml($this->fecha->caption());

            // descripcion
            $this->descripcion->EditAttrs["class"] = "form-control";
            $this->descripcion->EditCustomAttributes = "";
            $this->descripcion->EditValue = HtmlEncode($this->descripcion->CurrentValue);
            $this->descripcion->PlaceHolder = RemoveHtml($this->descripcion->caption());

            // monto_exento
            $this->monto_exento->EditAttrs["class"] = "form-control";
            $this->monto_exento->EditCustomAttributes = "";
            $this->monto_exento->EditValue = HtmlEncode($this->monto_exento->CurrentValue);
            $this->monto_exento->PlaceHolder = RemoveHtml($this->monto_exento->caption());
            if (strval($this->monto_exento->EditValue) != "" && is_numeric($this->monto_exento->EditValue)) {
                $this->monto_exento->EditValue = FormatNumber($this->monto_exento->EditValue, -2, -1, -2, -1);
            }

            // monto_gravado
            $this->monto_gravado->EditAttrs["class"] = "form-control";
            $this->monto_gravado->EditCustomAttributes = "";
            $this->monto_gravado->EditValue = HtmlEncode($this->monto_gravado->CurrentValue);
            $this->monto_gravado->PlaceHolder = RemoveHtml($this->monto_gravado->caption());
            if (strval($this->monto_gravado->EditValue) != "" && is_numeric($this->monto_gravado->EditValue)) {
                $this->monto_gravado->EditValue = FormatNumber($this->monto_gravado->EditValue, -2, -1, -2, -1);
            }

            // alicuota
            $this->alicuota->EditAttrs["class"] = "form-control";
            $this->alicuota->EditCustomAttributes = "";
            $this->alicuota->EditValue = HtmlEncode($this->alicuota->CurrentValue);
            $this->alicuota->PlaceHolder = RemoveHtml($this->alicuota->caption());
            if (strval($this->alicuota->EditValue) != "" && is_numeric($this->alicuota->EditValue)) {
                $this->alicuota->EditValue = FormatNumber($this->alicuota->EditValue, -2, -1, -2, -1);
            }

            // Add refer script

            // proveedor
            $this->proveedor->LinkCustomAttributes = "";
            $this->proveedor->HrefValue = "";

            // tipo_documento
            $this->tipo_documento->LinkCustomAttributes = "";
            $this->tipo_documento->HrefValue = "";

            // doc_afectado
            $this->doc_afectado->LinkCustomAttributes = "";
            $this->doc_afectado->HrefValue = "";

            // documento
            $this->documento->LinkCustomAttributes = "";
            $this->documento->HrefValue = "";

            // nro_control
            $this->nro_control->LinkCustomAttributes = "";
            $this->nro_control->HrefValue = "";

            // fecha
            $this->fecha->LinkCustomAttributes = "";
            $this->fecha->HrefValue = "";

            // descripcion
            $this->descripcion->LinkCustomAttributes = "";
            $this->descripcion->HrefValue = "";

            // monto_exento
            $this->monto_exento->LinkCustomAttributes = "";
            $this->monto_exento->HrefValue = "";

            // monto_gravado
            $this->monto_gravado->LinkCustomAttributes = "";
            $this->monto_gravado->HrefValue = "";

            // alicuota
            $this->alicuota->LinkCustomAttributes = "";
            $this->alicuota->HrefValue = "";
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
        if ($this->proveedor->Required) {
            if (!$this->proveedor->IsDetailKey && EmptyValue($this->proveedor->FormValue)) {
                $this->proveedor->addErrorMessage(str_replace("%s", $this->proveedor->caption(), $this->proveedor->RequiredErrorMessage));
            }
        }
        if ($this->tipo_documento->Required) {
            if (!$this->tipo_documento->IsDetailKey && EmptyValue($this->tipo_documento->FormValue)) {
                $this->tipo_documento->addErrorMessage(str_replace("%s", $this->tipo_documento->caption(), $this->tipo_documento->RequiredErrorMessage));
            }
        }
        if ($this->doc_afectado->Required) {
            if (!$this->doc_afectado->IsDetailKey && EmptyValue($this->doc_afectado->FormValue)) {
                $this->doc_afectado->addErrorMessage(str_replace("%s", $this->doc_afectado->caption(), $this->doc_afectado->RequiredErrorMessage));
            }
        }
        if ($this->documento->Required) {
            if (!$this->documento->IsDetailKey && EmptyValue($this->documento->FormValue)) {
                $this->documento->addErrorMessage(str_replace("%s", $this->documento->caption(), $this->documento->RequiredErrorMessage));
            }
        }
        if ($this->nro_control->Required) {
            if (!$this->nro_control->IsDetailKey && EmptyValue($this->nro_control->FormValue)) {
                $this->nro_control->addErrorMessage(str_replace("%s", $this->nro_control->caption(), $this->nro_control->RequiredErrorMessage));
            }
        }
        if ($this->fecha->Required) {
            if (!$this->fecha->IsDetailKey && EmptyValue($this->fecha->FormValue)) {
                $this->fecha->addErrorMessage(str_replace("%s", $this->fecha->caption(), $this->fecha->RequiredErrorMessage));
            }
        }
        if (!CheckEuroDate($this->fecha->FormValue)) {
            $this->fecha->addErrorMessage($this->fecha->getErrorMessage(false));
        }
        if ($this->descripcion->Required) {
            if (!$this->descripcion->IsDetailKey && EmptyValue($this->descripcion->FormValue)) {
                $this->descripcion->addErrorMessage(str_replace("%s", $this->descripcion->caption(), $this->descripcion->RequiredErrorMessage));
            }
        }
        if ($this->monto_exento->Required) {
            if (!$this->monto_exento->IsDetailKey && EmptyValue($this->monto_exento->FormValue)) {
                $this->monto_exento->addErrorMessage(str_replace("%s", $this->monto_exento->caption(), $this->monto_exento->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->monto_exento->FormValue)) {
            $this->monto_exento->addErrorMessage($this->monto_exento->getErrorMessage(false));
        }
        if ($this->monto_gravado->Required) {
            if (!$this->monto_gravado->IsDetailKey && EmptyValue($this->monto_gravado->FormValue)) {
                $this->monto_gravado->addErrorMessage(str_replace("%s", $this->monto_gravado->caption(), $this->monto_gravado->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->monto_gravado->FormValue)) {
            $this->monto_gravado->addErrorMessage($this->monto_gravado->getErrorMessage(false));
        }
        if ($this->alicuota->Required) {
            if (!$this->alicuota->IsDetailKey && EmptyValue($this->alicuota->FormValue)) {
                $this->alicuota->addErrorMessage(str_replace("%s", $this->alicuota->caption(), $this->alicuota->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->alicuota->FormValue)) {
            $this->alicuota->addErrorMessage($this->alicuota->getErrorMessage(false));
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

        // proveedor
        $this->proveedor->setDbValueDef($rsnew, $this->proveedor->CurrentValue, null, false);

        // tipo_documento
        $this->tipo_documento->setDbValueDef($rsnew, $this->tipo_documento->CurrentValue, null, false);

        // doc_afectado
        $this->doc_afectado->setDbValueDef($rsnew, $this->doc_afectado->CurrentValue, null, false);

        // documento
        $this->documento->setDbValueDef($rsnew, $this->documento->CurrentValue, null, false);

        // nro_control
        $this->nro_control->setDbValueDef($rsnew, $this->nro_control->CurrentValue, null, false);

        // fecha
        $this->fecha->setDbValueDef($rsnew, UnFormatDateTime($this->fecha->CurrentValue, 7), null, false);

        // descripcion
        $this->descripcion->setDbValueDef($rsnew, $this->descripcion->CurrentValue, null, false);

        // monto_exento
        $this->monto_exento->setDbValueDef($rsnew, $this->monto_exento->CurrentValue, null, false);

        // monto_gravado
        $this->monto_gravado->setDbValueDef($rsnew, $this->monto_gravado->CurrentValue, null, false);

        // alicuota
        $this->alicuota->setDbValueDef($rsnew, $this->alicuota->CurrentValue, null, false);

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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("CompraList"), "", $this->TableVar, true);
        $pageId = ($this->isCopy()) ? "Copy" : "Add";
        $Breadcrumb->add("add", $pageId, $url);
    }

    // Set up multi pages
    protected function setupMultiPages()
    {
        $pages = new SubPages();
        $pages->Style = "tabs";
        $pages->add(0);
        $pages->add(1);
        $pages->add(2);
        $this->MultiPages = $pages;
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
                case "x_proveedor":
                    break;
                case "x_tipo_documento":
                    break;
                case "x_aplica_retencion":
                    break;
                case "x__username":
                    break;
                case "x_comprobante":
                    break;
                case "x_anulado":
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
