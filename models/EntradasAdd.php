<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class EntradasAdd extends Entradas
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'entradas';

    // Page object name
    public $PageObjName = "EntradasAdd";

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

        // Table object (entradas)
        if (!isset($GLOBALS["entradas"]) || get_class($GLOBALS["entradas"]) == PROJECT_NAMESPACE . "entradas") {
            $GLOBALS["entradas"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'entradas');
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
                $doc = new $class(Container("entradas"));
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
                    if ($pageName == "EntradasView") {
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
        $this->tipo_documento->setVisibility();
        $this->nro_documento->setVisibility();
        $this->nro_control->setVisibility();
        $this->fecha->setVisibility();
        $this->proveedor->setVisibility();
        $this->almacen->Visible = false;
        $this->monto_total->Visible = false;
        $this->alicuota_iva->Visible = false;
        $this->iva->Visible = false;
        $this->total->Visible = false;
        $this->documento->setVisibility();
        $this->doc_afectado->setVisibility();
        $this->nota->setVisibility();
        $this->estatus->Visible = false;
        $this->_username->Visible = false;
        $this->id_documento_padre->Visible = false;
        $this->moneda->Visible = false;
        $this->consignacion->Visible = false;
        $this->consignacion_reportada->Visible = false;
        $this->aplica_retencion->setVisibility();
        $this->ret_iva->Visible = false;
        $this->ref_iva->Visible = false;
        $this->ret_islr->Visible = false;
        $this->ref_islr->Visible = false;
        $this->ret_municipal->Visible = false;
        $this->ref_municipal->setVisibility();
        $this->monto_pagar->Visible = false;
        $this->comprobante->Visible = false;
        $this->tipo_iva->Visible = false;
        $this->tipo_islr->Visible = false;
        $this->sustraendo->Visible = false;
        $this->fecha_registro_retenciones->setVisibility();
        $this->tasa_dia->Visible = false;
        $this->monto_usd->Visible = false;
        $this->fecha_libro_compra->Visible = false;
        $this->tipo_municipal->Visible = false;
        $this->cerrado->Visible = false;
        $this->cliente->setVisibility();
        $this->descuento->setVisibility();
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
        $this->setupLookupOptions($this->proveedor);
        $this->setupLookupOptions($this->almacen);
        $this->setupLookupOptions($this->_username);
        $this->setupLookupOptions($this->moneda);
        $this->setupLookupOptions($this->comprobante);
        $this->setupLookupOptions($this->cliente);

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
                    $this->terminate("EntradasList"); // No matching record, return to list
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
                    $returnUrl = "EntradasView/" . urlencode($this->id->CurrentValue) . "?showdetail=entradas_salidas&tipo=" . urlencode($this->tipo_documento->CurrentValue);
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
        $this->tipo_documento->CurrentValue = null;
        $this->tipo_documento->OldValue = $this->tipo_documento->CurrentValue;
        $this->nro_documento->CurrentValue = null;
        $this->nro_documento->OldValue = $this->nro_documento->CurrentValue;
        $this->nro_control->CurrentValue = null;
        $this->nro_control->OldValue = $this->nro_control->CurrentValue;
        $this->fecha->CurrentValue = null;
        $this->fecha->OldValue = $this->fecha->CurrentValue;
        $this->proveedor->CurrentValue = null;
        $this->proveedor->OldValue = $this->proveedor->CurrentValue;
        $this->almacen->CurrentValue = null;
        $this->almacen->OldValue = $this->almacen->CurrentValue;
        $this->monto_total->CurrentValue = null;
        $this->monto_total->OldValue = $this->monto_total->CurrentValue;
        $this->alicuota_iva->CurrentValue = null;
        $this->alicuota_iva->OldValue = $this->alicuota_iva->CurrentValue;
        $this->iva->CurrentValue = null;
        $this->iva->OldValue = $this->iva->CurrentValue;
        $this->total->CurrentValue = null;
        $this->total->OldValue = $this->total->CurrentValue;
        $this->documento->CurrentValue = null;
        $this->documento->OldValue = $this->documento->CurrentValue;
        $this->doc_afectado->CurrentValue = null;
        $this->doc_afectado->OldValue = $this->doc_afectado->CurrentValue;
        $this->nota->CurrentValue = null;
        $this->nota->OldValue = $this->nota->CurrentValue;
        $this->estatus->CurrentValue = null;
        $this->estatus->OldValue = $this->estatus->CurrentValue;
        $this->_username->CurrentValue = null;
        $this->_username->OldValue = $this->_username->CurrentValue;
        $this->id_documento_padre->CurrentValue = null;
        $this->id_documento_padre->OldValue = $this->id_documento_padre->CurrentValue;
        $this->moneda->CurrentValue = null;
        $this->moneda->OldValue = $this->moneda->CurrentValue;
        $this->consignacion->CurrentValue = null;
        $this->consignacion->OldValue = $this->consignacion->CurrentValue;
        $this->consignacion_reportada->CurrentValue = "N";
        $this->aplica_retencion->CurrentValue = null;
        $this->aplica_retencion->OldValue = $this->aplica_retencion->CurrentValue;
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
        $this->monto_pagar->CurrentValue = null;
        $this->monto_pagar->OldValue = $this->monto_pagar->CurrentValue;
        $this->comprobante->CurrentValue = null;
        $this->comprobante->OldValue = $this->comprobante->CurrentValue;
        $this->tipo_iva->CurrentValue = null;
        $this->tipo_iva->OldValue = $this->tipo_iva->CurrentValue;
        $this->tipo_islr->CurrentValue = null;
        $this->tipo_islr->OldValue = $this->tipo_islr->CurrentValue;
        $this->sustraendo->CurrentValue = null;
        $this->sustraendo->OldValue = $this->sustraendo->CurrentValue;
        $this->fecha_registro_retenciones->CurrentValue = null;
        $this->fecha_registro_retenciones->OldValue = $this->fecha_registro_retenciones->CurrentValue;
        $this->tasa_dia->CurrentValue = null;
        $this->tasa_dia->OldValue = $this->tasa_dia->CurrentValue;
        $this->monto_usd->CurrentValue = null;
        $this->monto_usd->OldValue = $this->monto_usd->CurrentValue;
        $this->fecha_libro_compra->CurrentValue = null;
        $this->fecha_libro_compra->OldValue = $this->fecha_libro_compra->CurrentValue;
        $this->tipo_municipal->CurrentValue = null;
        $this->tipo_municipal->OldValue = $this->tipo_municipal->CurrentValue;
        $this->cerrado->CurrentValue = "N";
        $this->cliente->CurrentValue = null;
        $this->cliente->OldValue = $this->cliente->CurrentValue;
        $this->descuento->CurrentValue = null;
        $this->descuento->OldValue = $this->descuento->CurrentValue;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;

        // Check field name 'tipo_documento' first before field var 'x_tipo_documento'
        $val = $CurrentForm->hasValue("tipo_documento") ? $CurrentForm->getValue("tipo_documento") : $CurrentForm->getValue("x_tipo_documento");
        if (!$this->tipo_documento->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->tipo_documento->Visible = false; // Disable update for API request
            } else {
                $this->tipo_documento->setFormValue($val);
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

        // Check field name 'proveedor' first before field var 'x_proveedor'
        $val = $CurrentForm->hasValue("proveedor") ? $CurrentForm->getValue("proveedor") : $CurrentForm->getValue("x_proveedor");
        if (!$this->proveedor->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->proveedor->Visible = false; // Disable update for API request
            } else {
                $this->proveedor->setFormValue($val);
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

        // Check field name 'doc_afectado' first before field var 'x_doc_afectado'
        $val = $CurrentForm->hasValue("doc_afectado") ? $CurrentForm->getValue("doc_afectado") : $CurrentForm->getValue("x_doc_afectado");
        if (!$this->doc_afectado->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->doc_afectado->Visible = false; // Disable update for API request
            } else {
                $this->doc_afectado->setFormValue($val);
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

        // Check field name 'aplica_retencion' first before field var 'x_aplica_retencion'
        $val = $CurrentForm->hasValue("aplica_retencion") ? $CurrentForm->getValue("aplica_retencion") : $CurrentForm->getValue("x_aplica_retencion");
        if (!$this->aplica_retencion->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->aplica_retencion->Visible = false; // Disable update for API request
            } else {
                $this->aplica_retencion->setFormValue($val);
            }
        }

        // Check field name 'ref_municipal' first before field var 'x_ref_municipal'
        $val = $CurrentForm->hasValue("ref_municipal") ? $CurrentForm->getValue("ref_municipal") : $CurrentForm->getValue("x_ref_municipal");
        if (!$this->ref_municipal->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ref_municipal->Visible = false; // Disable update for API request
            } else {
                $this->ref_municipal->setFormValue($val);
            }
        }

        // Check field name 'fecha_registro_retenciones' first before field var 'x_fecha_registro_retenciones'
        $val = $CurrentForm->hasValue("fecha_registro_retenciones") ? $CurrentForm->getValue("fecha_registro_retenciones") : $CurrentForm->getValue("x_fecha_registro_retenciones");
        if (!$this->fecha_registro_retenciones->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->fecha_registro_retenciones->Visible = false; // Disable update for API request
            } else {
                $this->fecha_registro_retenciones->setFormValue($val);
            }
            $this->fecha_registro_retenciones->CurrentValue = UnFormatDateTime($this->fecha_registro_retenciones->CurrentValue, 0);
        }

        // Check field name 'cliente' first before field var 'x_cliente'
        $val = $CurrentForm->hasValue("cliente") ? $CurrentForm->getValue("cliente") : $CurrentForm->getValue("x_cliente");
        if (!$this->cliente->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cliente->Visible = false; // Disable update for API request
            } else {
                $this->cliente->setFormValue($val);
            }
        }

        // Check field name 'descuento' first before field var 'x_descuento'
        $val = $CurrentForm->hasValue("descuento") ? $CurrentForm->getValue("descuento") : $CurrentForm->getValue("x_descuento");
        if (!$this->descuento->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->descuento->Visible = false; // Disable update for API request
            } else {
                $this->descuento->setFormValue($val);
            }
        }

        // Check field name 'id' first before field var 'x_id'
        $val = $CurrentForm->hasValue("id") ? $CurrentForm->getValue("id") : $CurrentForm->getValue("x_id");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->tipo_documento->CurrentValue = $this->tipo_documento->FormValue;
        $this->nro_documento->CurrentValue = $this->nro_documento->FormValue;
        $this->nro_control->CurrentValue = $this->nro_control->FormValue;
        $this->fecha->CurrentValue = $this->fecha->FormValue;
        $this->fecha->CurrentValue = UnFormatDateTime($this->fecha->CurrentValue, 7);
        $this->proveedor->CurrentValue = $this->proveedor->FormValue;
        $this->documento->CurrentValue = $this->documento->FormValue;
        $this->doc_afectado->CurrentValue = $this->doc_afectado->FormValue;
        $this->nota->CurrentValue = $this->nota->FormValue;
        $this->aplica_retencion->CurrentValue = $this->aplica_retencion->FormValue;
        $this->ref_municipal->CurrentValue = $this->ref_municipal->FormValue;
        $this->fecha_registro_retenciones->CurrentValue = $this->fecha_registro_retenciones->FormValue;
        $this->fecha_registro_retenciones->CurrentValue = UnFormatDateTime($this->fecha_registro_retenciones->CurrentValue, 0);
        $this->cliente->CurrentValue = $this->cliente->FormValue;
        $this->descuento->CurrentValue = $this->descuento->FormValue;
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
        $this->tipo_documento->setDbValue($row['tipo_documento']);
        $this->nro_documento->setDbValue($row['nro_documento']);
        $this->nro_control->setDbValue($row['nro_control']);
        $this->fecha->setDbValue($row['fecha']);
        $this->proveedor->setDbValue($row['proveedor']);
        $this->almacen->setDbValue($row['almacen']);
        $this->monto_total->setDbValue($row['monto_total']);
        $this->alicuota_iva->setDbValue($row['alicuota_iva']);
        $this->iva->setDbValue($row['iva']);
        $this->total->setDbValue($row['total']);
        $this->documento->setDbValue($row['documento']);
        $this->doc_afectado->setDbValue($row['doc_afectado']);
        $this->nota->setDbValue($row['nota']);
        $this->estatus->setDbValue($row['estatus']);
        $this->_username->setDbValue($row['username']);
        $this->id_documento_padre->setDbValue($row['id_documento_padre']);
        $this->moneda->setDbValue($row['moneda']);
        $this->consignacion->setDbValue($row['consignacion']);
        $this->consignacion_reportada->setDbValue($row['consignacion_reportada']);
        $this->aplica_retencion->setDbValue($row['aplica_retencion']);
        $this->ret_iva->setDbValue($row['ret_iva']);
        $this->ref_iva->setDbValue($row['ref_iva']);
        $this->ret_islr->setDbValue($row['ret_islr']);
        $this->ref_islr->setDbValue($row['ref_islr']);
        $this->ret_municipal->setDbValue($row['ret_municipal']);
        $this->ref_municipal->setDbValue($row['ref_municipal']);
        $this->monto_pagar->setDbValue($row['monto_pagar']);
        $this->comprobante->setDbValue($row['comprobante']);
        $this->tipo_iva->setDbValue($row['tipo_iva']);
        $this->tipo_islr->setDbValue($row['tipo_islr']);
        $this->sustraendo->setDbValue($row['sustraendo']);
        $this->fecha_registro_retenciones->setDbValue($row['fecha_registro_retenciones']);
        $this->tasa_dia->setDbValue($row['tasa_dia']);
        $this->monto_usd->setDbValue($row['monto_usd']);
        $this->fecha_libro_compra->setDbValue($row['fecha_libro_compra']);
        $this->tipo_municipal->setDbValue($row['tipo_municipal']);
        $this->cerrado->setDbValue($row['cerrado']);
        $this->cliente->setDbValue($row['cliente']);
        $this->descuento->setDbValue($row['descuento']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $this->loadDefaultValues();
        $row = [];
        $row['id'] = $this->id->CurrentValue;
        $row['tipo_documento'] = $this->tipo_documento->CurrentValue;
        $row['nro_documento'] = $this->nro_documento->CurrentValue;
        $row['nro_control'] = $this->nro_control->CurrentValue;
        $row['fecha'] = $this->fecha->CurrentValue;
        $row['proveedor'] = $this->proveedor->CurrentValue;
        $row['almacen'] = $this->almacen->CurrentValue;
        $row['monto_total'] = $this->monto_total->CurrentValue;
        $row['alicuota_iva'] = $this->alicuota_iva->CurrentValue;
        $row['iva'] = $this->iva->CurrentValue;
        $row['total'] = $this->total->CurrentValue;
        $row['documento'] = $this->documento->CurrentValue;
        $row['doc_afectado'] = $this->doc_afectado->CurrentValue;
        $row['nota'] = $this->nota->CurrentValue;
        $row['estatus'] = $this->estatus->CurrentValue;
        $row['username'] = $this->_username->CurrentValue;
        $row['id_documento_padre'] = $this->id_documento_padre->CurrentValue;
        $row['moneda'] = $this->moneda->CurrentValue;
        $row['consignacion'] = $this->consignacion->CurrentValue;
        $row['consignacion_reportada'] = $this->consignacion_reportada->CurrentValue;
        $row['aplica_retencion'] = $this->aplica_retencion->CurrentValue;
        $row['ret_iva'] = $this->ret_iva->CurrentValue;
        $row['ref_iva'] = $this->ref_iva->CurrentValue;
        $row['ret_islr'] = $this->ret_islr->CurrentValue;
        $row['ref_islr'] = $this->ref_islr->CurrentValue;
        $row['ret_municipal'] = $this->ret_municipal->CurrentValue;
        $row['ref_municipal'] = $this->ref_municipal->CurrentValue;
        $row['monto_pagar'] = $this->monto_pagar->CurrentValue;
        $row['comprobante'] = $this->comprobante->CurrentValue;
        $row['tipo_iva'] = $this->tipo_iva->CurrentValue;
        $row['tipo_islr'] = $this->tipo_islr->CurrentValue;
        $row['sustraendo'] = $this->sustraendo->CurrentValue;
        $row['fecha_registro_retenciones'] = $this->fecha_registro_retenciones->CurrentValue;
        $row['tasa_dia'] = $this->tasa_dia->CurrentValue;
        $row['monto_usd'] = $this->monto_usd->CurrentValue;
        $row['fecha_libro_compra'] = $this->fecha_libro_compra->CurrentValue;
        $row['tipo_municipal'] = $this->tipo_municipal->CurrentValue;
        $row['cerrado'] = $this->cerrado->CurrentValue;
        $row['cliente'] = $this->cliente->CurrentValue;
        $row['descuento'] = $this->descuento->CurrentValue;
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
        if ($this->descuento->FormValue == $this->descuento->CurrentValue && is_numeric(ConvertToFloatString($this->descuento->CurrentValue))) {
            $this->descuento->CurrentValue = ConvertToFloatString($this->descuento->CurrentValue);
        }

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // id

        // tipo_documento

        // nro_documento

        // nro_control

        // fecha

        // proveedor

        // almacen

        // monto_total

        // alicuota_iva

        // iva

        // total

        // documento

        // doc_afectado

        // nota

        // estatus

        // username

        // id_documento_padre

        // moneda

        // consignacion

        // consignacion_reportada

        // aplica_retencion

        // ret_iva

        // ref_iva

        // ret_islr

        // ref_islr

        // ret_municipal

        // ref_municipal

        // monto_pagar

        // comprobante

        // tipo_iva

        // tipo_islr

        // sustraendo

        // fecha_registro_retenciones

        // tasa_dia

        // monto_usd

        // fecha_libro_compra

        // tipo_municipal

        // cerrado

        // cliente

        // descuento
        if ($this->RowType == ROWTYPE_VIEW) {
            // tipo_documento
            $this->tipo_documento->ViewValue = $this->tipo_documento->CurrentValue;
            $this->tipo_documento->ViewCustomAttributes = "";

            // nro_documento
            $this->nro_documento->ViewValue = $this->nro_documento->CurrentValue;
            $this->nro_documento->ViewCustomAttributes = "";

            // nro_control
            $this->nro_control->ViewValue = $this->nro_control->CurrentValue;
            $this->nro_control->ViewCustomAttributes = "";

            // fecha
            $this->fecha->ViewValue = $this->fecha->CurrentValue;
            $this->fecha->ViewValue = FormatDateTime($this->fecha->ViewValue, 7);
            $this->fecha->ViewCustomAttributes = "";

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

            // monto_total
            $this->monto_total->ViewValue = $this->monto_total->CurrentValue;
            $this->monto_total->ViewValue = FormatNumber($this->monto_total->ViewValue, 2, -1, -1, -1);
            $this->monto_total->ViewCustomAttributes = "";

            // alicuota_iva
            $this->alicuota_iva->ViewValue = $this->alicuota_iva->CurrentValue;
            $this->alicuota_iva->ViewValue = FormatNumber($this->alicuota_iva->ViewValue, 2, -1, -1, -1);
            $this->alicuota_iva->ViewCustomAttributes = "";

            // iva
            $this->iva->ViewValue = $this->iva->CurrentValue;
            $this->iva->ViewValue = FormatNumber($this->iva->ViewValue, 2, -1, -1, -1);
            $this->iva->ViewCustomAttributes = "";

            // total
            $this->total->ViewValue = $this->total->CurrentValue;
            $this->total->ViewValue = FormatNumber($this->total->ViewValue, 2, -1, -1, -1);
            $this->total->ViewCustomAttributes = "";

            // documento
            if (strval($this->documento->CurrentValue) != "") {
                $this->documento->ViewValue = $this->documento->optionCaption($this->documento->CurrentValue);
            } else {
                $this->documento->ViewValue = null;
            }
            $this->documento->ViewCustomAttributes = "";

            // doc_afectado
            $this->doc_afectado->ViewValue = $this->doc_afectado->CurrentValue;
            $this->doc_afectado->ViewCustomAttributes = "";

            // nota
            $this->nota->ViewValue = $this->nota->CurrentValue;
            $this->nota->ViewCustomAttributes = "";

            // estatus
            if (strval($this->estatus->CurrentValue) != "") {
                $this->estatus->ViewValue = $this->estatus->optionCaption($this->estatus->CurrentValue);
            } else {
                $this->estatus->ViewValue = null;
            }
            $this->estatus->ViewCustomAttributes = "";

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

            // aplica_retencion
            if (strval($this->aplica_retencion->CurrentValue) != "") {
                $this->aplica_retencion->ViewValue = $this->aplica_retencion->optionCaption($this->aplica_retencion->CurrentValue);
            } else {
                $this->aplica_retencion->ViewValue = null;
            }
            $this->aplica_retencion->ViewCustomAttributes = "";

            // ref_municipal
            $this->ref_municipal->ViewValue = $this->ref_municipal->CurrentValue;
            $this->ref_municipal->ViewCustomAttributes = "";

            // fecha_registro_retenciones
            $this->fecha_registro_retenciones->ViewValue = $this->fecha_registro_retenciones->CurrentValue;
            $this->fecha_registro_retenciones->ViewValue = FormatDateTime($this->fecha_registro_retenciones->ViewValue, 0);
            $this->fecha_registro_retenciones->ViewCustomAttributes = "";

            // cliente
            $curVal = trim(strval($this->cliente->CurrentValue));
            if ($curVal != "") {
                $this->cliente->ViewValue = $this->cliente->lookupCacheOption($curVal);
                if ($this->cliente->ViewValue === null) { // Lookup from database
                    $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->cliente->Lookup->getSql(false, $filterWrk, '', $this, true, true);
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

            // descuento
            $this->descuento->ViewValue = $this->descuento->CurrentValue;
            $this->descuento->ViewValue = FormatNumber($this->descuento->ViewValue, 2, -2, -2, -2);
            $this->descuento->ViewCustomAttributes = "";

            // tipo_documento
            $this->tipo_documento->LinkCustomAttributes = "";
            $this->tipo_documento->HrefValue = "";
            $this->tipo_documento->TooltipValue = "";

            // nro_documento
            $this->nro_documento->LinkCustomAttributes = "";
            $this->nro_documento->HrefValue = "";
            $this->nro_documento->TooltipValue = "";

            // nro_control
            $this->nro_control->LinkCustomAttributes = "";
            $this->nro_control->HrefValue = "";
            $this->nro_control->TooltipValue = "";

            // fecha
            $this->fecha->LinkCustomAttributes = "";
            $this->fecha->HrefValue = "";
            $this->fecha->TooltipValue = "";

            // proveedor
            $this->proveedor->LinkCustomAttributes = "";
            $this->proveedor->HrefValue = "";
            $this->proveedor->TooltipValue = "";

            // documento
            $this->documento->LinkCustomAttributes = "";
            $this->documento->HrefValue = "";
            $this->documento->TooltipValue = "";

            // doc_afectado
            $this->doc_afectado->LinkCustomAttributes = "";
            $this->doc_afectado->HrefValue = "";
            $this->doc_afectado->TooltipValue = "";

            // nota
            $this->nota->LinkCustomAttributes = "";
            $this->nota->HrefValue = "";
            $this->nota->TooltipValue = "";

            // aplica_retencion
            $this->aplica_retencion->LinkCustomAttributes = "";
            $this->aplica_retencion->HrefValue = "";
            $this->aplica_retencion->TooltipValue = "";

            // ref_municipal
            $this->ref_municipal->LinkCustomAttributes = "";
            $this->ref_municipal->HrefValue = "";
            $this->ref_municipal->TooltipValue = "";

            // fecha_registro_retenciones
            $this->fecha_registro_retenciones->LinkCustomAttributes = "";
            $this->fecha_registro_retenciones->HrefValue = "";
            $this->fecha_registro_retenciones->TooltipValue = "";

            // cliente
            $this->cliente->LinkCustomAttributes = "";
            $this->cliente->HrefValue = "";
            $this->cliente->TooltipValue = "";

            // descuento
            $this->descuento->LinkCustomAttributes = "";
            $this->descuento->HrefValue = "";
            $this->descuento->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // tipo_documento
            $this->tipo_documento->EditAttrs["class"] = "form-control";
            $this->tipo_documento->EditCustomAttributes = "";
            if (!$this->tipo_documento->Raw) {
                $this->tipo_documento->CurrentValue = HtmlDecode($this->tipo_documento->CurrentValue);
            }
            $this->tipo_documento->EditValue = HtmlEncode($this->tipo_documento->CurrentValue);
            $this->tipo_documento->PlaceHolder = RemoveHtml($this->tipo_documento->caption());

            // nro_documento
            $this->nro_documento->EditAttrs["class"] = "form-control";
            $this->nro_documento->EditCustomAttributes = "";
            if (!$this->nro_documento->Raw) {
                $this->nro_documento->CurrentValue = HtmlDecode($this->nro_documento->CurrentValue);
            }
            $this->nro_documento->EditValue = HtmlEncode($this->nro_documento->CurrentValue);
            $this->nro_documento->PlaceHolder = RemoveHtml($this->nro_documento->caption());

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

            // documento
            $this->documento->EditAttrs["class"] = "form-control";
            $this->documento->EditCustomAttributes = "";
            $this->documento->EditValue = $this->documento->options(true);
            $this->documento->PlaceHolder = RemoveHtml($this->documento->caption());

            // doc_afectado
            $this->doc_afectado->EditAttrs["class"] = "form-control";
            $this->doc_afectado->EditCustomAttributes = "";
            if (!$this->doc_afectado->Raw) {
                $this->doc_afectado->CurrentValue = HtmlDecode($this->doc_afectado->CurrentValue);
            }
            $this->doc_afectado->EditValue = HtmlEncode($this->doc_afectado->CurrentValue);
            $this->doc_afectado->PlaceHolder = RemoveHtml($this->doc_afectado->caption());

            // nota
            $this->nota->EditAttrs["class"] = "form-control";
            $this->nota->EditCustomAttributes = "";
            $this->nota->EditValue = HtmlEncode($this->nota->CurrentValue);
            $this->nota->PlaceHolder = RemoveHtml($this->nota->caption());

            // aplica_retencion
            $this->aplica_retencion->EditCustomAttributes = "";
            $this->aplica_retencion->EditValue = $this->aplica_retencion->options(false);
            $this->aplica_retencion->PlaceHolder = RemoveHtml($this->aplica_retencion->caption());

            // ref_municipal
            $this->ref_municipal->EditAttrs["class"] = "form-control";
            $this->ref_municipal->EditCustomAttributes = "";
            if (!$this->ref_municipal->Raw) {
                $this->ref_municipal->CurrentValue = HtmlDecode($this->ref_municipal->CurrentValue);
            }
            $this->ref_municipal->EditValue = HtmlEncode($this->ref_municipal->CurrentValue);
            $this->ref_municipal->PlaceHolder = RemoveHtml($this->ref_municipal->caption());

            // fecha_registro_retenciones
            $this->fecha_registro_retenciones->EditAttrs["class"] = "form-control";
            $this->fecha_registro_retenciones->EditCustomAttributes = "";
            $this->fecha_registro_retenciones->EditValue = HtmlEncode(FormatDateTime($this->fecha_registro_retenciones->CurrentValue, 8));
            $this->fecha_registro_retenciones->PlaceHolder = RemoveHtml($this->fecha_registro_retenciones->caption());

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
                $sqlWrk = $this->cliente->Lookup->getSql(true, $filterWrk, '', $this, false, true);
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

            // descuento
            $this->descuento->EditAttrs["class"] = "form-control";
            $this->descuento->EditCustomAttributes = "";
            $this->descuento->EditValue = HtmlEncode($this->descuento->CurrentValue);
            $this->descuento->PlaceHolder = RemoveHtml($this->descuento->caption());
            if (strval($this->descuento->EditValue) != "" && is_numeric($this->descuento->EditValue)) {
                $this->descuento->EditValue = FormatNumber($this->descuento->EditValue, -2, -2, -2, -2);
            }

            // Add refer script

            // tipo_documento
            $this->tipo_documento->LinkCustomAttributes = "";
            $this->tipo_documento->HrefValue = "";

            // nro_documento
            $this->nro_documento->LinkCustomAttributes = "";
            $this->nro_documento->HrefValue = "";

            // nro_control
            $this->nro_control->LinkCustomAttributes = "";
            $this->nro_control->HrefValue = "";

            // fecha
            $this->fecha->LinkCustomAttributes = "";
            $this->fecha->HrefValue = "";

            // proveedor
            $this->proveedor->LinkCustomAttributes = "";
            $this->proveedor->HrefValue = "";

            // documento
            $this->documento->LinkCustomAttributes = "";
            $this->documento->HrefValue = "";

            // doc_afectado
            $this->doc_afectado->LinkCustomAttributes = "";
            $this->doc_afectado->HrefValue = "";

            // nota
            $this->nota->LinkCustomAttributes = "";
            $this->nota->HrefValue = "";

            // aplica_retencion
            $this->aplica_retencion->LinkCustomAttributes = "";
            $this->aplica_retencion->HrefValue = "";

            // ref_municipal
            $this->ref_municipal->LinkCustomAttributes = "";
            $this->ref_municipal->HrefValue = "";

            // fecha_registro_retenciones
            $this->fecha_registro_retenciones->LinkCustomAttributes = "";
            $this->fecha_registro_retenciones->HrefValue = "";

            // cliente
            $this->cliente->LinkCustomAttributes = "";
            $this->cliente->HrefValue = "";

            // descuento
            $this->descuento->LinkCustomAttributes = "";
            $this->descuento->HrefValue = "";
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
        if ($this->tipo_documento->Required) {
            if (!$this->tipo_documento->IsDetailKey && EmptyValue($this->tipo_documento->FormValue)) {
                $this->tipo_documento->addErrorMessage(str_replace("%s", $this->tipo_documento->caption(), $this->tipo_documento->RequiredErrorMessage));
            }
        }
        if ($this->nro_documento->Required) {
            if (!$this->nro_documento->IsDetailKey && EmptyValue($this->nro_documento->FormValue)) {
                $this->nro_documento->addErrorMessage(str_replace("%s", $this->nro_documento->caption(), $this->nro_documento->RequiredErrorMessage));
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
        if ($this->proveedor->Required) {
            if (!$this->proveedor->IsDetailKey && EmptyValue($this->proveedor->FormValue)) {
                $this->proveedor->addErrorMessage(str_replace("%s", $this->proveedor->caption(), $this->proveedor->RequiredErrorMessage));
            }
        }
        if ($this->documento->Required) {
            if (!$this->documento->IsDetailKey && EmptyValue($this->documento->FormValue)) {
                $this->documento->addErrorMessage(str_replace("%s", $this->documento->caption(), $this->documento->RequiredErrorMessage));
            }
        }
        if ($this->doc_afectado->Required) {
            if (!$this->doc_afectado->IsDetailKey && EmptyValue($this->doc_afectado->FormValue)) {
                $this->doc_afectado->addErrorMessage(str_replace("%s", $this->doc_afectado->caption(), $this->doc_afectado->RequiredErrorMessage));
            }
        }
        if ($this->nota->Required) {
            if (!$this->nota->IsDetailKey && EmptyValue($this->nota->FormValue)) {
                $this->nota->addErrorMessage(str_replace("%s", $this->nota->caption(), $this->nota->RequiredErrorMessage));
            }
        }
        if ($this->aplica_retencion->Required) {
            if ($this->aplica_retencion->FormValue == "") {
                $this->aplica_retencion->addErrorMessage(str_replace("%s", $this->aplica_retencion->caption(), $this->aplica_retencion->RequiredErrorMessage));
            }
        }
        if ($this->ref_municipal->Required) {
            if (!$this->ref_municipal->IsDetailKey && EmptyValue($this->ref_municipal->FormValue)) {
                $this->ref_municipal->addErrorMessage(str_replace("%s", $this->ref_municipal->caption(), $this->ref_municipal->RequiredErrorMessage));
            }
        }
        if ($this->fecha_registro_retenciones->Required) {
            if (!$this->fecha_registro_retenciones->IsDetailKey && EmptyValue($this->fecha_registro_retenciones->FormValue)) {
                $this->fecha_registro_retenciones->addErrorMessage(str_replace("%s", $this->fecha_registro_retenciones->caption(), $this->fecha_registro_retenciones->RequiredErrorMessage));
            }
        }
        if (!CheckDate($this->fecha_registro_retenciones->FormValue)) {
            $this->fecha_registro_retenciones->addErrorMessage($this->fecha_registro_retenciones->getErrorMessage(false));
        }
        if ($this->cliente->Required) {
            if (!$this->cliente->IsDetailKey && EmptyValue($this->cliente->FormValue)) {
                $this->cliente->addErrorMessage(str_replace("%s", $this->cliente->caption(), $this->cliente->RequiredErrorMessage));
            }
        }
        if ($this->descuento->Required) {
            if (!$this->descuento->IsDetailKey && EmptyValue($this->descuento->FormValue)) {
                $this->descuento->addErrorMessage(str_replace("%s", $this->descuento->caption(), $this->descuento->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->descuento->FormValue)) {
            $this->descuento->addErrorMessage($this->descuento->getErrorMessage(false));
        }

        // Validate detail grid
        $detailTblVar = explode(",", $this->getCurrentDetailTable());
        $detailPage = Container("EntradasSalidasGrid");
        if (in_array("entradas_salidas", $detailTblVar) && $detailPage->DetailAdd) {
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

        // tipo_documento
        $this->tipo_documento->setDbValueDef($rsnew, $this->tipo_documento->CurrentValue, null, false);

        // nro_documento
        $this->nro_documento->setDbValueDef($rsnew, $this->nro_documento->CurrentValue, null, false);

        // nro_control
        $this->nro_control->setDbValueDef($rsnew, $this->nro_control->CurrentValue, null, false);

        // fecha
        $this->fecha->setDbValueDef($rsnew, UnFormatDateTime($this->fecha->CurrentValue, 7), null, false);

        // proveedor
        $this->proveedor->setDbValueDef($rsnew, $this->proveedor->CurrentValue, null, false);

        // documento
        $this->documento->setDbValueDef($rsnew, $this->documento->CurrentValue, null, false);

        // doc_afectado
        $this->doc_afectado->setDbValueDef($rsnew, $this->doc_afectado->CurrentValue, null, false);

        // nota
        $this->nota->setDbValueDef($rsnew, $this->nota->CurrentValue, null, false);

        // aplica_retencion
        $this->aplica_retencion->setDbValueDef($rsnew, $this->aplica_retencion->CurrentValue, null, false);

        // ref_municipal
        $this->ref_municipal->setDbValueDef($rsnew, $this->ref_municipal->CurrentValue, null, false);

        // fecha_registro_retenciones
        $this->fecha_registro_retenciones->setDbValueDef($rsnew, UnFormatDateTime($this->fecha_registro_retenciones->CurrentValue, 0), null, false);

        // cliente
        $this->cliente->setDbValueDef($rsnew, $this->cliente->CurrentValue, null, false);

        // descuento
        $this->descuento->setDbValueDef($rsnew, $this->descuento->CurrentValue, null, false);

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
            $detailPage = Container("EntradasSalidasGrid");
            if (in_array("entradas_salidas", $detailTblVar) && $detailPage->DetailAdd) {
                $detailPage->tipo_documento->setSessionValue($this->tipo_documento->CurrentValue); // Set master key
                $detailPage->id_documento->setSessionValue($this->id->CurrentValue); // Set master key
                $Security->loadCurrentUserLevel($this->ProjectID . "entradas_salidas"); // Load user level of detail table
                $addRow = $detailPage->gridInsert();
                $Security->loadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
                if (!$addRow) {
                $detailPage->tipo_documento->setSessionValue(""); // Clear master key if insert failed
                $detailPage->id_documento->setSessionValue(""); // Clear master key if insert failed
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
            if (in_array("entradas_salidas", $detailTblVar)) {
                $detailPageObj = Container("EntradasSalidasGrid");
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
                    $detailPageObj->tipo_documento->IsDetailKey = true;
                    $detailPageObj->tipo_documento->CurrentValue = $this->tipo_documento->CurrentValue;
                    $detailPageObj->tipo_documento->setSessionValue($detailPageObj->tipo_documento->CurrentValue);
                    $detailPageObj->id_documento->IsDetailKey = true;
                    $detailPageObj->id_documento->CurrentValue = $this->id->CurrentValue;
                    $detailPageObj->id_documento->setSessionValue($detailPageObj->id_documento->CurrentValue);
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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("EntradasList"), "", $this->TableVar, true);
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
                case "x_proveedor":
                    break;
                case "x_almacen":
                    break;
                case "x_documento":
                    break;
                case "x_estatus":
                    break;
                case "x__username":
                    break;
                case "x_moneda":
                    $lookupFilter = function () {
                        return "`codigo` = '006'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    break;
                case "x_consignacion":
                    break;
                case "x_consignacion_reportada":
                    break;
                case "x_aplica_retencion":
                    break;
                case "x_comprobante":
                    break;
                case "x_cerrado":
                    break;
                case "x_cliente":
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
       	$tipo = isset($_REQUEST["tipo"]) ? $_REQUEST["tipo"] : $_REQUEST["x_tipo_documento"];
    	$sql = "SELECT descripcion FROM tipo_documento WHERE codigo = '$tipo';";
    	$tipo_name = ExecuteScalar($sql);
    	$tipo_name = '<a href="EntradasList?tipo=' . $tipo . '">' . $tipo_name . '</a>';
    	$this->setTableCaption($tipo_name);
        if(trim($tipo) == "") {
        	header("Location: Home");
        	die();
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
       	$tipo = isset($_REQUEST["tipo"]) ? $_REQUEST["tipo"] : $_REQUEST["x_tipo_documento"];
        if($tipo == "TDCNRP") {
    		if(!VerificaFuncion('039')) {
    			$url = "Home";
    		}
        }
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
    public function pageRender() {
    	//echo "Page Render";
    	$this->fecha->Visible = FALSE;
    	$this->nro_documento->Visible = FALSE;
    	$this->nro_control->Visible = FALSE;
    	$this->documento->Visible = FALSE;
    	$this->ret_municipal->Visible = FALSE;
    	$this->ref_municipal->Visible = FALSE;
    	$this->doc_afectado->Visible = FALSE;
    	$this->aplica_retencion->Visible = FALSE;
    	$this->cliente->Visible = FALSE;
    	$this->descuento->Visible = FALSE;
       	$tipo = isset($_REQUEST["tipo"]) ? $_REQUEST["tipo"] : $_REQUEST["x_tipo_documento"];
    	if($tipo == "TDCFCC") {
    		$this->fecha->Visible = TRUE;
    		$this->nro_documento->Visible = TRUE;
    		$this->nro_control->Visible = TRUE;
    		$this->documento->Visible = TRUE;
    		$this->doc_afectado->Visible = TRUE;
    		$this->aplica_retencion->Visible = TRUE;
    		$this->descuento->Visible = TRUE;
    	}
    	if($tipo == "TDCNRP") {
    		$this->cliente->Visible = TRUE;
    		$this->descuento->Visible = TRUE;
    	}
    	if($tipo == "TDCPDC") $this->descuento->Visible = TRUE;
    }

    // Page Data Rendering event
    public function pageDataRendering(&$header) {
    	// Example:
       	$tipo = isset($_REQUEST["tipo"]) ? $_REQUEST["tipo"] : $_REQUEST["x_tipo_documento"];
    	switch($tipo) {
    	case "TDCPDC":
    		$header .= '<div id="xSubTotal"></div>';
    		break;
    	case "TDCNRP":
    		if(VerificaFuncion('039')) {
    			$header = '<a href="ViewEntradasList?crear=TDCNRP" class="btn btn-primary">
    				Crear nota de recepci&oacute;n desde Pedido de Compra o Factura
    			</a>';
    		}
    		$header .= '<div id="xSubTotal"></div>';
    		break;
    	case "TDCFCC":
    		$header = '<a href="ViewEntradasList?crear=TDCFCC" class="btn btn-primary">
    				Crear Factura desde Nota de Recepci&oacute;n
    			</a>';
    		break;
    	case "TDCAEN":
    		break;
    	}
    	$sql = "SELECT userlevelid AS grupo FROM usuario WHERE username = '" . CurrentUserName() . "';";
    	if($row = ExecuteRow($sql)) $xGrupo = $row["grupo"];
    	else $xGrupo = -1;
    	$header .= '<input type="hidden" id="xGroup" name="xGroup" value="' . $xGrupo . '">';
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
