<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class SalidasAdd extends Salidas
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'salidas';

    // Page object name
    public $PageObjName = "SalidasAdd";

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

        // Table object (salidas)
        if (!isset($GLOBALS["salidas"]) || get_class($GLOBALS["salidas"]) == PROJECT_NAMESPACE . "salidas") {
            $GLOBALS["salidas"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'salidas');
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
                $doc = new $class(Container("salidas"));
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
                    if ($pageName == "SalidasView") {
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
        $this->nro_documento->Visible = false;
        $this->nro_control->Visible = false;
        $this->fecha->Visible = false;
        $this->cliente->setVisibility();
        $this->documento->setVisibility();
        $this->doc_afectado->setVisibility();
        $this->moneda->setVisibility();
        $this->monto_total->Visible = false;
        $this->alicuota_iva->Visible = false;
        $this->iva->Visible = false;
        $this->total->Visible = false;
        $this->tasa_dia->Visible = false;
        $this->monto_usd->Visible = false;
        $this->lista_pedido->setVisibility();
        $this->nota->setVisibility();
        $this->_username->Visible = false;
        $this->estatus->Visible = false;
        $this->id_documento_padre->Visible = false;
        $this->asesor->setVisibility();
        $this->pago_divisa->setVisibility();
        $this->dias_credito->Visible = false;
        $this->entregado->Visible = false;
        $this->fecha_entrega->Visible = false;
        $this->pagado->Visible = false;
        $this->bultos->Visible = false;
        $this->fecha_bultos->Visible = false;
        $this->user_bultos->Visible = false;
        $this->fecha_despacho->Visible = false;
        $this->user_despacho->Visible = false;
        $this->consignacion->Visible = false;
        $this->unidades->Visible = false;
        $this->descuento->setVisibility();
        $this->monto_sin_descuento->Visible = false;
        $this->factura->Visible = false;
        $this->ci_rif->setVisibility();
        $this->nombre->Visible = false;
        $this->direccion->Visible = false;
        $this->telefono->Visible = false;
        $this->_email->Visible = false;
        $this->activo->Visible = false;
        $this->comprobante->Visible = false;
        $this->nro_despacho->setVisibility();
        $this->cerrado->Visible = false;
        $this->impreso->Visible = false;
        $this->igtf->setVisibility();
        $this->monto_base_igtf->Visible = false;
        $this->monto_igtf->Visible = false;
        $this->pago_premio->Visible = false;
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
        $this->setupLookupOptions($this->moneda);
        $this->setupLookupOptions($this->lista_pedido);
        $this->setupLookupOptions($this->_username);
        $this->setupLookupOptions($this->asesor);
        $this->setupLookupOptions($this->user_bultos);
        $this->setupLookupOptions($this->user_despacho);
        $this->setupLookupOptions($this->descuento);
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
                    $this->terminate("SalidasList"); // No matching record, return to list
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
                    $returnUrl = "SalidasView/" . urlencode($this->id->CurrentValue) . "?showdetail=entradas_salidas&tipo=" . urlencode($this->tipo_documento->CurrentValue);
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
        $this->cliente->CurrentValue = null;
        $this->cliente->OldValue = $this->cliente->CurrentValue;
        $this->documento->CurrentValue = null;
        $this->documento->OldValue = $this->documento->CurrentValue;
        $this->doc_afectado->CurrentValue = null;
        $this->doc_afectado->OldValue = $this->doc_afectado->CurrentValue;
        $this->moneda->CurrentValue = null;
        $this->moneda->OldValue = $this->moneda->CurrentValue;
        $this->monto_total->CurrentValue = null;
        $this->monto_total->OldValue = $this->monto_total->CurrentValue;
        $this->alicuota_iva->CurrentValue = null;
        $this->alicuota_iva->OldValue = $this->alicuota_iva->CurrentValue;
        $this->iva->CurrentValue = null;
        $this->iva->OldValue = $this->iva->CurrentValue;
        $this->total->CurrentValue = null;
        $this->total->OldValue = $this->total->CurrentValue;
        $this->tasa_dia->CurrentValue = null;
        $this->tasa_dia->OldValue = $this->tasa_dia->CurrentValue;
        $this->monto_usd->CurrentValue = null;
        $this->monto_usd->OldValue = $this->monto_usd->CurrentValue;
        $this->lista_pedido->CurrentValue = null;
        $this->lista_pedido->OldValue = $this->lista_pedido->CurrentValue;
        $this->nota->CurrentValue = null;
        $this->nota->OldValue = $this->nota->CurrentValue;
        $this->_username->CurrentValue = null;
        $this->_username->OldValue = $this->_username->CurrentValue;
        $this->estatus->CurrentValue = null;
        $this->estatus->OldValue = $this->estatus->CurrentValue;
        $this->id_documento_padre->CurrentValue = null;
        $this->id_documento_padre->OldValue = $this->id_documento_padre->CurrentValue;
        $this->asesor->CurrentValue = null;
        $this->asesor->OldValue = $this->asesor->CurrentValue;
        $this->pago_divisa->CurrentValue = null;
        $this->pago_divisa->OldValue = $this->pago_divisa->CurrentValue;
        $this->dias_credito->CurrentValue = null;
        $this->dias_credito->OldValue = $this->dias_credito->CurrentValue;
        $this->entregado->CurrentValue = "N";
        $this->fecha_entrega->CurrentValue = null;
        $this->fecha_entrega->OldValue = $this->fecha_entrega->CurrentValue;
        $this->pagado->CurrentValue = "N";
        $this->bultos->CurrentValue = 0;
        $this->fecha_bultos->CurrentValue = "0000-00-00 00:00:00";
        $this->user_bultos->CurrentValue = null;
        $this->user_bultos->OldValue = $this->user_bultos->CurrentValue;
        $this->fecha_despacho->CurrentValue = "0000-00-00 00:00:00";
        $this->user_despacho->CurrentValue = null;
        $this->user_despacho->OldValue = $this->user_despacho->CurrentValue;
        $this->consignacion->CurrentValue = "N";
        $this->unidades->CurrentValue = null;
        $this->unidades->OldValue = $this->unidades->CurrentValue;
        $this->descuento->CurrentValue = null;
        $this->descuento->OldValue = $this->descuento->CurrentValue;
        $this->monto_sin_descuento->CurrentValue = null;
        $this->monto_sin_descuento->OldValue = $this->monto_sin_descuento->CurrentValue;
        $this->factura->CurrentValue = "N";
        $this->ci_rif->CurrentValue = "N";
        $this->nombre->CurrentValue = null;
        $this->nombre->OldValue = $this->nombre->CurrentValue;
        $this->direccion->CurrentValue = null;
        $this->direccion->OldValue = $this->direccion->CurrentValue;
        $this->telefono->CurrentValue = null;
        $this->telefono->OldValue = $this->telefono->CurrentValue;
        $this->_email->CurrentValue = null;
        $this->_email->OldValue = $this->_email->CurrentValue;
        $this->activo->CurrentValue = "S";
        $this->comprobante->CurrentValue = null;
        $this->comprobante->OldValue = $this->comprobante->CurrentValue;
        $this->nro_despacho->CurrentValue = null;
        $this->nro_despacho->OldValue = $this->nro_despacho->CurrentValue;
        $this->cerrado->CurrentValue = "N";
        $this->impreso->CurrentValue = "N";
        $this->igtf->CurrentValue = "N";
        $this->monto_base_igtf->CurrentValue = null;
        $this->monto_base_igtf->OldValue = $this->monto_base_igtf->CurrentValue;
        $this->monto_igtf->CurrentValue = null;
        $this->monto_igtf->OldValue = $this->monto_igtf->CurrentValue;
        $this->pago_premio->CurrentValue = null;
        $this->pago_premio->OldValue = $this->pago_premio->CurrentValue;
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

        // Check field name 'cliente' first before field var 'x_cliente'
        $val = $CurrentForm->hasValue("cliente") ? $CurrentForm->getValue("cliente") : $CurrentForm->getValue("x_cliente");
        if (!$this->cliente->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cliente->Visible = false; // Disable update for API request
            } else {
                $this->cliente->setFormValue($val);
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

        // Check field name 'moneda' first before field var 'x_moneda'
        $val = $CurrentForm->hasValue("moneda") ? $CurrentForm->getValue("moneda") : $CurrentForm->getValue("x_moneda");
        if (!$this->moneda->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->moneda->Visible = false; // Disable update for API request
            } else {
                $this->moneda->setFormValue($val);
            }
        }

        // Check field name 'lista_pedido' first before field var 'x_lista_pedido'
        $val = $CurrentForm->hasValue("lista_pedido") ? $CurrentForm->getValue("lista_pedido") : $CurrentForm->getValue("x_lista_pedido");
        if (!$this->lista_pedido->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->lista_pedido->Visible = false; // Disable update for API request
            } else {
                $this->lista_pedido->setFormValue($val);
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

        // Check field name 'asesor' first before field var 'x_asesor'
        $val = $CurrentForm->hasValue("asesor") ? $CurrentForm->getValue("asesor") : $CurrentForm->getValue("x_asesor");
        if (!$this->asesor->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->asesor->Visible = false; // Disable update for API request
            } else {
                $this->asesor->setFormValue($val);
            }
        }

        // Check field name 'pago_divisa' first before field var 'x_pago_divisa'
        $val = $CurrentForm->hasValue("pago_divisa") ? $CurrentForm->getValue("pago_divisa") : $CurrentForm->getValue("x_pago_divisa");
        if (!$this->pago_divisa->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->pago_divisa->Visible = false; // Disable update for API request
            } else {
                $this->pago_divisa->setFormValue($val);
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

        // Check field name 'ci_rif' first before field var 'x_ci_rif'
        $val = $CurrentForm->hasValue("ci_rif") ? $CurrentForm->getValue("ci_rif") : $CurrentForm->getValue("x_ci_rif");
        if (!$this->ci_rif->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ci_rif->Visible = false; // Disable update for API request
            } else {
                $this->ci_rif->setFormValue($val);
            }
        }

        // Check field name 'nro_despacho' first before field var 'x_nro_despacho'
        $val = $CurrentForm->hasValue("nro_despacho") ? $CurrentForm->getValue("nro_despacho") : $CurrentForm->getValue("x_nro_despacho");
        if (!$this->nro_despacho->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->nro_despacho->Visible = false; // Disable update for API request
            } else {
                $this->nro_despacho->setFormValue($val);
            }
        }

        // Check field name 'igtf' first before field var 'x_igtf'
        $val = $CurrentForm->hasValue("igtf") ? $CurrentForm->getValue("igtf") : $CurrentForm->getValue("x_igtf");
        if (!$this->igtf->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->igtf->Visible = false; // Disable update for API request
            } else {
                $this->igtf->setFormValue($val);
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
        $this->cliente->CurrentValue = $this->cliente->FormValue;
        $this->documento->CurrentValue = $this->documento->FormValue;
        $this->doc_afectado->CurrentValue = $this->doc_afectado->FormValue;
        $this->moneda->CurrentValue = $this->moneda->FormValue;
        $this->lista_pedido->CurrentValue = $this->lista_pedido->FormValue;
        $this->nota->CurrentValue = $this->nota->FormValue;
        $this->asesor->CurrentValue = $this->asesor->FormValue;
        $this->pago_divisa->CurrentValue = $this->pago_divisa->FormValue;
        $this->descuento->CurrentValue = $this->descuento->FormValue;
        $this->ci_rif->CurrentValue = $this->ci_rif->FormValue;
        $this->nro_despacho->CurrentValue = $this->nro_despacho->FormValue;
        $this->igtf->CurrentValue = $this->igtf->FormValue;
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
        $this->cliente->setDbValue($row['cliente']);
        $this->documento->setDbValue($row['documento']);
        $this->doc_afectado->setDbValue($row['doc_afectado']);
        $this->moneda->setDbValue($row['moneda']);
        $this->monto_total->setDbValue($row['monto_total']);
        $this->alicuota_iva->setDbValue($row['alicuota_iva']);
        $this->iva->setDbValue($row['iva']);
        $this->total->setDbValue($row['total']);
        $this->tasa_dia->setDbValue($row['tasa_dia']);
        $this->monto_usd->setDbValue($row['monto_usd']);
        $this->lista_pedido->setDbValue($row['lista_pedido']);
        $this->nota->setDbValue($row['nota']);
        $this->_username->setDbValue($row['username']);
        $this->estatus->setDbValue($row['estatus']);
        $this->id_documento_padre->setDbValue($row['id_documento_padre']);
        $this->asesor->setDbValue($row['asesor']);
        $this->pago_divisa->setDbValue($row['pago_divisa']);
        $this->dias_credito->setDbValue($row['dias_credito']);
        $this->entregado->setDbValue($row['entregado']);
        $this->fecha_entrega->setDbValue($row['fecha_entrega']);
        $this->pagado->setDbValue($row['pagado']);
        $this->bultos->setDbValue($row['bultos']);
        $this->fecha_bultos->setDbValue($row['fecha_bultos']);
        $this->user_bultos->setDbValue($row['user_bultos']);
        $this->fecha_despacho->setDbValue($row['fecha_despacho']);
        $this->user_despacho->setDbValue($row['user_despacho']);
        $this->consignacion->setDbValue($row['consignacion']);
        $this->unidades->setDbValue($row['unidades']);
        $this->descuento->setDbValue($row['descuento']);
        $this->monto_sin_descuento->setDbValue($row['monto_sin_descuento']);
        $this->factura->setDbValue($row['factura']);
        $this->ci_rif->setDbValue($row['ci_rif']);
        $this->nombre->setDbValue($row['nombre']);
        $this->direccion->setDbValue($row['direccion']);
        $this->telefono->setDbValue($row['telefono']);
        $this->_email->setDbValue($row['email']);
        $this->activo->setDbValue($row['activo']);
        $this->comprobante->setDbValue($row['comprobante']);
        $this->nro_despacho->setDbValue($row['nro_despacho']);
        $this->cerrado->setDbValue($row['cerrado']);
        $this->impreso->setDbValue($row['impreso']);
        $this->igtf->setDbValue($row['igtf']);
        $this->monto_base_igtf->setDbValue($row['monto_base_igtf']);
        $this->monto_igtf->setDbValue($row['monto_igtf']);
        $this->pago_premio->setDbValue($row['pago_premio']);
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
        $row['cliente'] = $this->cliente->CurrentValue;
        $row['documento'] = $this->documento->CurrentValue;
        $row['doc_afectado'] = $this->doc_afectado->CurrentValue;
        $row['moneda'] = $this->moneda->CurrentValue;
        $row['monto_total'] = $this->monto_total->CurrentValue;
        $row['alicuota_iva'] = $this->alicuota_iva->CurrentValue;
        $row['iva'] = $this->iva->CurrentValue;
        $row['total'] = $this->total->CurrentValue;
        $row['tasa_dia'] = $this->tasa_dia->CurrentValue;
        $row['monto_usd'] = $this->monto_usd->CurrentValue;
        $row['lista_pedido'] = $this->lista_pedido->CurrentValue;
        $row['nota'] = $this->nota->CurrentValue;
        $row['username'] = $this->_username->CurrentValue;
        $row['estatus'] = $this->estatus->CurrentValue;
        $row['id_documento_padre'] = $this->id_documento_padre->CurrentValue;
        $row['asesor'] = $this->asesor->CurrentValue;
        $row['pago_divisa'] = $this->pago_divisa->CurrentValue;
        $row['dias_credito'] = $this->dias_credito->CurrentValue;
        $row['entregado'] = $this->entregado->CurrentValue;
        $row['fecha_entrega'] = $this->fecha_entrega->CurrentValue;
        $row['pagado'] = $this->pagado->CurrentValue;
        $row['bultos'] = $this->bultos->CurrentValue;
        $row['fecha_bultos'] = $this->fecha_bultos->CurrentValue;
        $row['user_bultos'] = $this->user_bultos->CurrentValue;
        $row['fecha_despacho'] = $this->fecha_despacho->CurrentValue;
        $row['user_despacho'] = $this->user_despacho->CurrentValue;
        $row['consignacion'] = $this->consignacion->CurrentValue;
        $row['unidades'] = $this->unidades->CurrentValue;
        $row['descuento'] = $this->descuento->CurrentValue;
        $row['monto_sin_descuento'] = $this->monto_sin_descuento->CurrentValue;
        $row['factura'] = $this->factura->CurrentValue;
        $row['ci_rif'] = $this->ci_rif->CurrentValue;
        $row['nombre'] = $this->nombre->CurrentValue;
        $row['direccion'] = $this->direccion->CurrentValue;
        $row['telefono'] = $this->telefono->CurrentValue;
        $row['email'] = $this->_email->CurrentValue;
        $row['activo'] = $this->activo->CurrentValue;
        $row['comprobante'] = $this->comprobante->CurrentValue;
        $row['nro_despacho'] = $this->nro_despacho->CurrentValue;
        $row['cerrado'] = $this->cerrado->CurrentValue;
        $row['impreso'] = $this->impreso->CurrentValue;
        $row['igtf'] = $this->igtf->CurrentValue;
        $row['monto_base_igtf'] = $this->monto_base_igtf->CurrentValue;
        $row['monto_igtf'] = $this->monto_igtf->CurrentValue;
        $row['pago_premio'] = $this->pago_premio->CurrentValue;
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

        // tipo_documento

        // nro_documento

        // nro_control

        // fecha

        // cliente

        // documento

        // doc_afectado

        // moneda

        // monto_total

        // alicuota_iva

        // iva

        // total

        // tasa_dia

        // monto_usd

        // lista_pedido

        // nota

        // username

        // estatus

        // id_documento_padre

        // asesor

        // pago_divisa

        // dias_credito

        // entregado

        // fecha_entrega

        // pagado

        // bultos

        // fecha_bultos

        // user_bultos

        // fecha_despacho

        // user_despacho

        // consignacion

        // unidades

        // descuento

        // monto_sin_descuento

        // factura

        // ci_rif

        // nombre

        // direccion

        // telefono

        // email

        // activo

        // comprobante

        // nro_despacho

        // cerrado

        // impreso

        // igtf

        // monto_base_igtf

        // monto_igtf

        // pago_premio
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

            // cliente
            $this->cliente->ViewValue = $this->cliente->CurrentValue;
            $curVal = trim(strval($this->cliente->CurrentValue));
            if ($curVal != "") {
                $this->cliente->ViewValue = $this->cliente->lookupCacheOption($curVal);
                if ($this->cliente->ViewValue === null) { // Lookup from database
                    $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $lookupFilter = function() {
                        return FiltraClientes();
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

            // tasa_dia
            $this->tasa_dia->ViewValue = $this->tasa_dia->CurrentValue;
            $this->tasa_dia->ViewValue = FormatNumber($this->tasa_dia->ViewValue, 2, -1, -1, -1);
            $this->tasa_dia->ViewCustomAttributes = "";

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

            // nota
            $this->nota->ViewValue = $this->nota->CurrentValue;
            $this->nota->ViewCustomAttributes = "";

            // username
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

            // estatus
            if (strval($this->estatus->CurrentValue) != "") {
                $this->estatus->ViewValue = $this->estatus->optionCaption($this->estatus->CurrentValue);
            } else {
                $this->estatus->ViewValue = null;
            }
            $this->estatus->ViewCustomAttributes = "";

            // asesor
            $curVal = trim(strval($this->asesor->CurrentValue));
            if ($curVal != "") {
                $this->asesor->ViewValue = $this->asesor->lookupCacheOption($curVal);
                if ($this->asesor->ViewValue === null) { // Lookup from database
                    $filterWrk = "`ci_rif`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $lookupFilter = function() {
                        return ($this->PageID == "add" OR $this->PageID == "edit") ? "activo = 'S'" : "";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    $sqlWrk = $this->asesor->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->asesor->Lookup->renderViewRow($rswrk[0]);
                        $this->asesor->ViewValue = $this->asesor->displayValue($arwrk);
                    } else {
                        $this->asesor->ViewValue = $this->asesor->CurrentValue;
                    }
                }
            } else {
                $this->asesor->ViewValue = null;
            }
            $this->asesor->ViewCustomAttributes = "";

            // pago_divisa
            if (strval($this->pago_divisa->CurrentValue) != "") {
                $this->pago_divisa->ViewValue = $this->pago_divisa->optionCaption($this->pago_divisa->CurrentValue);
            } else {
                $this->pago_divisa->ViewValue = null;
            }
            $this->pago_divisa->ViewCustomAttributes = "";

            // unidades
            $this->unidades->ViewValue = $this->unidades->CurrentValue;
            $this->unidades->ViewCustomAttributes = "";

            // descuento
            $this->descuento->ViewValue = $this->descuento->CurrentValue;
            $curVal = trim(strval($this->descuento->CurrentValue));
            if ($curVal != "") {
                $this->descuento->ViewValue = $this->descuento->lookupCacheOption($curVal);
                if ($this->descuento->ViewValue === null) { // Lookup from database
                    $filterWrk = "`valor1`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $lookupFilter = function() {
                        return "`codigo` = '047'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    $sqlWrk = $this->descuento->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->descuento->Lookup->renderViewRow($rswrk[0]);
                        $this->descuento->ViewValue = $this->descuento->displayValue($arwrk);
                    } else {
                        $this->descuento->ViewValue = $this->descuento->CurrentValue;
                    }
                }
            } else {
                $this->descuento->ViewValue = null;
            }
            $this->descuento->CssClass = "font-weight-bold";
            $this->descuento->ViewCustomAttributes = "";

            // ci_rif
            if (strval($this->ci_rif->CurrentValue) != "") {
                $this->ci_rif->ViewValue = $this->ci_rif->optionCaption($this->ci_rif->CurrentValue);
            } else {
                $this->ci_rif->ViewValue = null;
            }
            $this->ci_rif->ViewCustomAttributes = "";

            // nro_despacho
            $this->nro_despacho->ViewValue = $this->nro_despacho->CurrentValue;
            $this->nro_despacho->ViewCustomAttributes = "";

            // igtf
            if (strval($this->igtf->CurrentValue) != "") {
                $this->igtf->ViewValue = $this->igtf->optionCaption($this->igtf->CurrentValue);
            } else {
                $this->igtf->ViewValue = null;
            }
            $this->igtf->ViewCustomAttributes = "";

            // monto_base_igtf
            $this->monto_base_igtf->ViewValue = $this->monto_base_igtf->CurrentValue;
            $this->monto_base_igtf->ViewValue = FormatNumber($this->monto_base_igtf->ViewValue, 2, -2, -2, -2);
            $this->monto_base_igtf->ViewCustomAttributes = "";

            // monto_igtf
            $this->monto_igtf->ViewValue = $this->monto_igtf->CurrentValue;
            $this->monto_igtf->ViewValue = FormatNumber($this->monto_igtf->ViewValue, 2, -2, -2, -2);
            $this->monto_igtf->ViewCustomAttributes = "";

            // pago_premio
            if (strval($this->pago_premio->CurrentValue) != "") {
                $this->pago_premio->ViewValue = $this->pago_premio->optionCaption($this->pago_premio->CurrentValue);
            } else {
                $this->pago_premio->ViewValue = null;
            }
            $this->pago_premio->ViewCustomAttributes = "";

            // tipo_documento
            $this->tipo_documento->LinkCustomAttributes = "";
            $this->tipo_documento->HrefValue = "";
            $this->tipo_documento->TooltipValue = "";

            // cliente
            $this->cliente->LinkCustomAttributes = "";
            $this->cliente->HrefValue = "";
            $this->cliente->TooltipValue = "";

            // documento
            $this->documento->LinkCustomAttributes = "";
            $this->documento->HrefValue = "";
            $this->documento->TooltipValue = "";

            // doc_afectado
            $this->doc_afectado->LinkCustomAttributes = "";
            $this->doc_afectado->HrefValue = "";
            $this->doc_afectado->TooltipValue = "";

            // moneda
            $this->moneda->LinkCustomAttributes = "";
            $this->moneda->HrefValue = "";
            $this->moneda->TooltipValue = "";

            // lista_pedido
            $this->lista_pedido->LinkCustomAttributes = "";
            $this->lista_pedido->HrefValue = "";
            $this->lista_pedido->TooltipValue = "";

            // nota
            $this->nota->LinkCustomAttributes = "";
            $this->nota->HrefValue = "";
            $this->nota->TooltipValue = "";

            // asesor
            $this->asesor->LinkCustomAttributes = "";
            $this->asesor->HrefValue = "";
            $this->asesor->TooltipValue = "";

            // pago_divisa
            $this->pago_divisa->LinkCustomAttributes = "";
            $this->pago_divisa->HrefValue = "";
            $this->pago_divisa->TooltipValue = "";

            // descuento
            $this->descuento->LinkCustomAttributes = "";
            $this->descuento->HrefValue = "";
            $this->descuento->TooltipValue = "";

            // ci_rif
            $this->ci_rif->LinkCustomAttributes = "";
            $this->ci_rif->HrefValue = "";
            $this->ci_rif->TooltipValue = "";

            // nro_despacho
            $this->nro_despacho->LinkCustomAttributes = "";
            $this->nro_despacho->HrefValue = "";
            $this->nro_despacho->TooltipValue = "";

            // igtf
            $this->igtf->LinkCustomAttributes = "";
            $this->igtf->HrefValue = "";
            $this->igtf->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // tipo_documento
            $this->tipo_documento->EditAttrs["class"] = "form-control";
            $this->tipo_documento->EditCustomAttributes = "";
            if (!$this->tipo_documento->Raw) {
                $this->tipo_documento->CurrentValue = HtmlDecode($this->tipo_documento->CurrentValue);
            }
            $this->tipo_documento->EditValue = HtmlEncode($this->tipo_documento->CurrentValue);
            $this->tipo_documento->PlaceHolder = RemoveHtml($this->tipo_documento->caption());

            // cliente
            $this->cliente->EditAttrs["class"] = "form-control";
            $this->cliente->EditCustomAttributes = "";
            $this->cliente->EditValue = HtmlEncode($this->cliente->CurrentValue);
            $curVal = trim(strval($this->cliente->CurrentValue));
            if ($curVal != "") {
                $this->cliente->EditValue = $this->cliente->lookupCacheOption($curVal);
                if ($this->cliente->EditValue === null) { // Lookup from database
                    $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $lookupFilter = function() {
                        return FiltraClientes();
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    $sqlWrk = $this->cliente->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->cliente->Lookup->renderViewRow($rswrk[0]);
                        $this->cliente->EditValue = $this->cliente->displayValue($arwrk);
                    } else {
                        $this->cliente->EditValue = HtmlEncode($this->cliente->CurrentValue);
                    }
                }
            } else {
                $this->cliente->EditValue = null;
            }
            $this->cliente->PlaceHolder = RemoveHtml($this->cliente->caption());

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

            // lista_pedido
            $this->lista_pedido->EditAttrs["class"] = "form-control";
            $this->lista_pedido->EditCustomAttributes = "";
            $curVal = trim(strval($this->lista_pedido->CurrentValue));
            if ($curVal != "") {
                $this->lista_pedido->ViewValue = $this->lista_pedido->lookupCacheOption($curVal);
            } else {
                $this->lista_pedido->ViewValue = $this->lista_pedido->Lookup !== null && is_array($this->lista_pedido->Lookup->Options) ? $curVal : null;
            }
            if ($this->lista_pedido->ViewValue !== null) { // Load from cache
                $this->lista_pedido->EditValue = array_values($this->lista_pedido->Lookup->Options);
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`campo_codigo`" . SearchString("=", $this->lista_pedido->CurrentValue, DATATYPE_STRING, "");
                }
                $lookupFilter = function() {
                    return "`tabla` = 'LISTA_PEDIDO'";
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->lista_pedido->Lookup->getSql(true, $filterWrk, $lookupFilter, $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->lista_pedido->EditValue = $arwrk;
            }
            $this->lista_pedido->PlaceHolder = RemoveHtml($this->lista_pedido->caption());

            // nota
            $this->nota->EditAttrs["class"] = "form-control";
            $this->nota->EditCustomAttributes = "";
            $this->nota->EditValue = HtmlEncode($this->nota->CurrentValue);
            $this->nota->PlaceHolder = RemoveHtml($this->nota->caption());

            // asesor
            $this->asesor->EditAttrs["class"] = "form-control";
            $this->asesor->EditCustomAttributes = "";
            $curVal = trim(strval($this->asesor->CurrentValue));
            if ($curVal != "") {
                $this->asesor->ViewValue = $this->asesor->lookupCacheOption($curVal);
            } else {
                $this->asesor->ViewValue = $this->asesor->Lookup !== null && is_array($this->asesor->Lookup->Options) ? $curVal : null;
            }
            if ($this->asesor->ViewValue !== null) { // Load from cache
                $this->asesor->EditValue = array_values($this->asesor->Lookup->Options);
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`ci_rif`" . SearchString("=", $this->asesor->CurrentValue, DATATYPE_STRING, "");
                }
                $lookupFilter = function() {
                    return ($this->PageID == "add" OR $this->PageID == "edit") ? "activo = 'S'" : "";
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->asesor->Lookup->getSql(true, $filterWrk, $lookupFilter, $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->asesor->EditValue = $arwrk;
            }
            $this->asesor->PlaceHolder = RemoveHtml($this->asesor->caption());

            // pago_divisa
            $this->pago_divisa->EditAttrs["class"] = "form-control";
            $this->pago_divisa->EditCustomAttributes = "";
            $this->pago_divisa->EditValue = $this->pago_divisa->options(true);
            $this->pago_divisa->PlaceHolder = RemoveHtml($this->pago_divisa->caption());

            // descuento
            $this->descuento->EditAttrs["class"] = "form-control";
            $this->descuento->EditCustomAttributes = "";
            $this->descuento->EditValue = HtmlEncode($this->descuento->CurrentValue);
            $curVal = trim(strval($this->descuento->CurrentValue));
            if ($curVal != "") {
                $this->descuento->EditValue = $this->descuento->lookupCacheOption($curVal);
                if ($this->descuento->EditValue === null) { // Lookup from database
                    $filterWrk = "`valor1`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $lookupFilter = function() {
                        return "`codigo` = '047'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    $sqlWrk = $this->descuento->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->descuento->Lookup->renderViewRow($rswrk[0]);
                        $this->descuento->EditValue = $this->descuento->displayValue($arwrk);
                    } else {
                        $this->descuento->EditValue = HtmlEncode($this->descuento->CurrentValue);
                    }
                }
            } else {
                $this->descuento->EditValue = null;
            }
            $this->descuento->PlaceHolder = RemoveHtml($this->descuento->caption());

            // ci_rif
            $this->ci_rif->EditCustomAttributes = "";
            $this->ci_rif->EditValue = $this->ci_rif->options(false);
            $this->ci_rif->PlaceHolder = RemoveHtml($this->ci_rif->caption());

            // nro_despacho
            $this->nro_despacho->EditAttrs["class"] = "form-control";
            $this->nro_despacho->EditCustomAttributes = "";
            if (!$this->nro_despacho->Raw) {
                $this->nro_despacho->CurrentValue = HtmlDecode($this->nro_despacho->CurrentValue);
            }
            $this->nro_despacho->EditValue = HtmlEncode($this->nro_despacho->CurrentValue);
            $this->nro_despacho->PlaceHolder = RemoveHtml($this->nro_despacho->caption());

            // igtf
            $this->igtf->EditAttrs["class"] = "form-control";
            $this->igtf->EditCustomAttributes = "";
            $this->igtf->EditValue = $this->igtf->options(true);
            $this->igtf->PlaceHolder = RemoveHtml($this->igtf->caption());

            // Add refer script

            // tipo_documento
            $this->tipo_documento->LinkCustomAttributes = "";
            $this->tipo_documento->HrefValue = "";

            // cliente
            $this->cliente->LinkCustomAttributes = "";
            $this->cliente->HrefValue = "";

            // documento
            $this->documento->LinkCustomAttributes = "";
            $this->documento->HrefValue = "";

            // doc_afectado
            $this->doc_afectado->LinkCustomAttributes = "";
            $this->doc_afectado->HrefValue = "";

            // moneda
            $this->moneda->LinkCustomAttributes = "";
            $this->moneda->HrefValue = "";

            // lista_pedido
            $this->lista_pedido->LinkCustomAttributes = "";
            $this->lista_pedido->HrefValue = "";

            // nota
            $this->nota->LinkCustomAttributes = "";
            $this->nota->HrefValue = "";

            // asesor
            $this->asesor->LinkCustomAttributes = "";
            $this->asesor->HrefValue = "";

            // pago_divisa
            $this->pago_divisa->LinkCustomAttributes = "";
            $this->pago_divisa->HrefValue = "";

            // descuento
            $this->descuento->LinkCustomAttributes = "";
            $this->descuento->HrefValue = "";

            // ci_rif
            $this->ci_rif->LinkCustomAttributes = "";
            $this->ci_rif->HrefValue = "";

            // nro_despacho
            $this->nro_despacho->LinkCustomAttributes = "";
            $this->nro_despacho->HrefValue = "";

            // igtf
            $this->igtf->LinkCustomAttributes = "";
            $this->igtf->HrefValue = "";
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
        if ($this->cliente->Required) {
            if (!$this->cliente->IsDetailKey && EmptyValue($this->cliente->FormValue)) {
                $this->cliente->addErrorMessage(str_replace("%s", $this->cliente->caption(), $this->cliente->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->cliente->FormValue)) {
            $this->cliente->addErrorMessage($this->cliente->getErrorMessage(false));
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
        if ($this->moneda->Required) {
            if (!$this->moneda->IsDetailKey && EmptyValue($this->moneda->FormValue)) {
                $this->moneda->addErrorMessage(str_replace("%s", $this->moneda->caption(), $this->moneda->RequiredErrorMessage));
            }
        }
        if ($this->lista_pedido->Required) {
            if (!$this->lista_pedido->IsDetailKey && EmptyValue($this->lista_pedido->FormValue)) {
                $this->lista_pedido->addErrorMessage(str_replace("%s", $this->lista_pedido->caption(), $this->lista_pedido->RequiredErrorMessage));
            }
        }
        if ($this->nota->Required) {
            if (!$this->nota->IsDetailKey && EmptyValue($this->nota->FormValue)) {
                $this->nota->addErrorMessage(str_replace("%s", $this->nota->caption(), $this->nota->RequiredErrorMessage));
            }
        }
        if ($this->asesor->Required) {
            if (!$this->asesor->IsDetailKey && EmptyValue($this->asesor->FormValue)) {
                $this->asesor->addErrorMessage(str_replace("%s", $this->asesor->caption(), $this->asesor->RequiredErrorMessage));
            }
        }
        if ($this->pago_divisa->Required) {
            if (!$this->pago_divisa->IsDetailKey && EmptyValue($this->pago_divisa->FormValue)) {
                $this->pago_divisa->addErrorMessage(str_replace("%s", $this->pago_divisa->caption(), $this->pago_divisa->RequiredErrorMessage));
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
        if ($this->ci_rif->Required) {
            if ($this->ci_rif->FormValue == "") {
                $this->ci_rif->addErrorMessage(str_replace("%s", $this->ci_rif->caption(), $this->ci_rif->RequiredErrorMessage));
            }
        }
        if ($this->nro_despacho->Required) {
            if (!$this->nro_despacho->IsDetailKey && EmptyValue($this->nro_despacho->FormValue)) {
                $this->nro_despacho->addErrorMessage(str_replace("%s", $this->nro_despacho->caption(), $this->nro_despacho->RequiredErrorMessage));
            }
        }
        if ($this->igtf->Required) {
            if (!$this->igtf->IsDetailKey && EmptyValue($this->igtf->FormValue)) {
                $this->igtf->addErrorMessage(str_replace("%s", $this->igtf->caption(), $this->igtf->RequiredErrorMessage));
            }
        }

        // Validate detail grid
        $detailTblVar = explode(",", $this->getCurrentDetailTable());
        $detailPage = Container("EntradasSalidasGrid");
        if (in_array("entradas_salidas", $detailTblVar) && $detailPage->DetailAdd) {
            $detailPage->validateGridForm();
        }
        $detailPage = Container("PagosGrid");
        if (in_array("pagos", $detailTblVar) && $detailPage->DetailAdd) {
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

        // cliente
        $this->cliente->setDbValueDef($rsnew, $this->cliente->CurrentValue, null, false);

        // documento
        $this->documento->setDbValueDef($rsnew, $this->documento->CurrentValue, null, false);

        // doc_afectado
        $this->doc_afectado->setDbValueDef($rsnew, $this->doc_afectado->CurrentValue, null, false);

        // moneda
        $this->moneda->setDbValueDef($rsnew, $this->moneda->CurrentValue, null, false);

        // lista_pedido
        $this->lista_pedido->setDbValueDef($rsnew, $this->lista_pedido->CurrentValue, null, false);

        // nota
        $this->nota->setDbValueDef($rsnew, $this->nota->CurrentValue, null, false);

        // asesor
        $this->asesor->setDbValueDef($rsnew, $this->asesor->CurrentValue, null, false);

        // pago_divisa
        $this->pago_divisa->setDbValueDef($rsnew, $this->pago_divisa->CurrentValue, null, false);

        // descuento
        $this->descuento->setDbValueDef($rsnew, $this->descuento->CurrentValue, null, false);

        // ci_rif
        $this->ci_rif->setDbValueDef($rsnew, $this->ci_rif->CurrentValue, null, false);

        // nro_despacho
        $this->nro_despacho->setDbValueDef($rsnew, $this->nro_despacho->CurrentValue, null, false);

        // igtf
        $this->igtf->setDbValueDef($rsnew, $this->igtf->CurrentValue, null, strval($this->igtf->CurrentValue) == "");

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
            $detailPage = Container("PagosGrid");
            if (in_array("pagos", $detailTblVar) && $detailPage->DetailAdd) {
                $detailPage->id_documento->setSessionValue($this->id->CurrentValue); // Set master key
                $detailPage->tipo_documento->setSessionValue($this->tipo_documento->CurrentValue); // Set master key
                $Security->loadCurrentUserLevel($this->ProjectID . "pagos"); // Load user level of detail table
                $addRow = $detailPage->gridInsert();
                $Security->loadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
                if (!$addRow) {
                $detailPage->id_documento->setSessionValue(""); // Clear master key if insert failed
                $detailPage->tipo_documento->setSessionValue(""); // Clear master key if insert failed
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
            if (in_array("pagos", $detailTblVar)) {
                $detailPageObj = Container("PagosGrid");
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
                    $detailPageObj->id_documento->IsDetailKey = true;
                    $detailPageObj->id_documento->CurrentValue = $this->id->CurrentValue;
                    $detailPageObj->id_documento->setSessionValue($detailPageObj->id_documento->CurrentValue);
                    $detailPageObj->tipo_documento->IsDetailKey = true;
                    $detailPageObj->tipo_documento->CurrentValue = $this->tipo_documento->CurrentValue;
                    $detailPageObj->tipo_documento->setSessionValue($detailPageObj->tipo_documento->CurrentValue);
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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("SalidasList"), "", $this->TableVar, true);
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
                        return FiltraClientes();
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    break;
                case "x_documento":
                    break;
                case "x_moneda":
                    $lookupFilter = function () {
                        return "`codigo` = '006'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    break;
                case "x_lista_pedido":
                    $lookupFilter = function () {
                        return "`tabla` = 'LISTA_PEDIDO'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    break;
                case "x__username":
                    break;
                case "x_estatus":
                    break;
                case "x_asesor":
                    $lookupFilter = function () {
                        return ($this->PageID == "add" OR $this->PageID == "edit") ? "activo = 'S'" : "";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    break;
                case "x_pago_divisa":
                    break;
                case "x_entregado":
                    break;
                case "x_pagado":
                    break;
                case "x_user_bultos":
                    break;
                case "x_user_despacho":
                    break;
                case "x_consignacion":
                    break;
                case "x_descuento":
                    $lookupFilter = function () {
                        return "`codigo` = '047'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    break;
                case "x_factura":
                    break;
                case "x_ci_rif":
                    break;
                case "x_activo":
                    break;
                case "x_comprobante":
                    break;
                case "x_cerrado":
                    break;
                case "x_impreso":
                    break;
                case "x_igtf":
                    break;
                case "x_pago_premio":
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
    	$tipo_name = '<a href="SalidasList?tipo=' . $tipo . '">' . $tipo_name . '</a>';
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
    public function pageRender() {
    	//echo "Page Render";
    	$this->descuento->Visible = FALSE;
    	$this->factura->Visible = FALSE;
    	$this->nombre->Visible = FALSE;
    	$this->nro_despacho->Visible = FALSE;
    	$this->impreso->Visible = FALSE;
    	$this->monto_base_igtf->Visible = FALSE;
    	$this->monto_igtf->Visible = FALSE;
    	$this->igtf->Visible = FALSE;
    	$this->asesor->Visible = FALSE;
    	$this->ci_rif->Visible = FALSE;
    	$this->pago_divisa->Visible = FALSE;
       	$tipo = isset($_REQUEST["tipo"]) ? $_REQUEST["tipo"] : $_REQUEST["x_tipo_documento"];
    	switch($tipo) {
    	case "TDCPDV":
    		$this->moneda->Visible = FALSE;
    		$this->documento->Visible = FALSE;
    		$this->doc_afectado->Visible = FALSE;
    		$this->consignacion->Visible = FALSE;
    		$this->bultos->Visible = FALSE;
    		$this->fecha_bultos->Visible = FALSE;
    		$this->fecha_despacho->Visible = FALSE;
    		$this->user_bultos->Visible = FALSE;
    		$this->user_despacho->Visible = FALSE;
    		$this->lista_pedido->Visible = TRUE;
    		break;
    	case "TDCNET":
    		$this->descuento->Visible = TRUE;
    		$this->moneda->Visible = FALSE;
    		$this->documento->Visible = FALSE;
    		$this->doc_afectado->Visible = FALSE;
    		$this->lista_pedido->Visible = FALSE;
    		$this->asesor->Visible = TRUE;
    		$this->ci_rif->Visible = TRUE;
    		$this->pago_divisa->Visible = TRUE;
    		break;
    	case "TDCFCV":
    		$this->descuento->Visible = TRUE;
    		$this->consignacion->Visible = FALSE;
    		$this->monto_base_igtf->Visible = TRUE;
    		$this->monto_igtf->Visible = TRUE;
    		$this->igtf->Visible = TRUE;
    		$this->bultos->Visible = FALSE;
    		$this->fecha_bultos->Visible = FALSE;
    		$this->fecha_despacho->Visible = FALSE;
    		$this->user_bultos->Visible = FALSE;
    		$this->user_despacho->Visible = FALSE;
    		$this->lista_pedido->Visible = FALSE;
    		$this->nro_despacho->Visible = TRUE;
    		break;
    	case "TDCASA":
    		$this->factura->Visible = TRUE;
    		$this->nombre->Visible = TRUE;
    		$this->moneda->Visible = FALSE;
    		$this->documento->Visible = FALSE;
    		$this->doc_afectado->Visible = FALSE;
    		$this->consignacion->Visible = FALSE;
    		$this->bultos->Visible = FALSE;
    		$this->fecha_bultos->Visible = FALSE;
    		$this->fecha_despacho->Visible = FALSE;
    		$this->user_bultos->Visible = FALSE;
    		$this->user_despacho->Visible = FALSE;
    		$this->lista_pedido->Visible = FALSE;
    		break;
    	}
    }

    // Page Data Rendering event
    public function pageDataRendering(&$header) {
    	// Example:
    	//$header = "your header";
       	$tipo = isset($_REQUEST["tipo"]) ? $_REQUEST["tipo"] : $_REQUEST["x_tipo_documento"];
    	switch($tipo) {
    	case "TDCNET":
    	   $header = '<div id="xSubTotal"></div><input type="hidden" id="xUserN" value="' . CurrentUserName() . '" />';	
           $header .= '<button class="btn btn-default" data-toggle="modal" data-target="#ventanaModal" id="btnClave"></button>
            <div class="modal fade" id="ventanaModal" tabindex="-1" role="dialog" aria-labelledby="tituloVentana" aria-label="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
                    <form class="form-horizontal" action="/action_page.php">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 id="tituloVentana">Clave de Autorizaci&oacute;n</h5>
                                <!--<button class="close" data-dismiss="modal" aria-label="Cerrar">
                                    <span aria-hidden="true">&times;</span>
                                </button>-->
                            </div>
                            <div class="modal-body text-center">
                              <div class="form-group">
                                <div class="col-sm-10">
                                  <input type="email" class="form-control" id="xusername" name="xusername" size="20" placeholder="Coloque Usuario">
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="col-sm-10">
                                  <input type="password" class="form-control" id="xpassword" name="xpassword" size="20" placeholder="Coloque Password">
                                </div>
                              </div>
                              <input type="hidden" id="txtCtrl" value="">
                              <input type="hidden" id="txtvalor" value="">
                              <input type="hidden" id="txtCtrl2" value="">
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-warning" type="button" data-dismiss="modal" id="btnCerrar">
                                    Cerrar
                                </button>
                                <button class="btn btn-success" type="button" id="btnAceptar">
                                    Aceptar
                                </button>
                            </div>
                        </div>
                    </form>                    
                </div>
            </div>';
    		break;
    	case "TDCFCV":
    		$header = '<a href="ViewSalidasList?crear=TDCFCV&consig=0" class="btn btn-primary">
    				Crear factura desde Nota de Entrega
    			</a>&nbsp;&nbsp;';

    		/*
    		$header .= '<a href="ViewSalidasList?crear=TDCFCV&consig=1" class="btn btn-primary">
    				Crear factura desde Nota de Entrega Consignaci&oacute;n
    			</a>';
    		*/
    		break;
    	}	
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
