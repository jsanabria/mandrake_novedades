<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class SalidasEdit extends Salidas
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'salidas';

    // Page object name
    public $PageObjName = "SalidasEdit";

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
        $this->tipo_documento->setVisibility();
        $this->nro_documento->setVisibility();
        $this->nro_control->setVisibility();
        $this->fecha->setVisibility();
        $this->cliente->setVisibility();
        $this->documento->setVisibility();
        $this->doc_afectado->setVisibility();
        $this->moneda->setVisibility();
        $this->monto_total->setVisibility();
        $this->alicuota_iva->setVisibility();
        $this->iva->setVisibility();
        $this->total->setVisibility();
        $this->tasa_dia->setVisibility();
        $this->monto_usd->Visible = false;
        $this->lista_pedido->Visible = false;
        $this->nota->setVisibility();
        $this->_username->Visible = false;
        $this->estatus->setVisibility();
        $this->id_documento_padre->Visible = false;
        $this->asesor->setVisibility();
        $this->pago_divisa->Visible = false;
        $this->dias_credito->setVisibility();
        $this->entregado->setVisibility();
        $this->fecha_entrega->Visible = false;
        $this->pagado->setVisibility();
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
        $this->ci_rif->Visible = false;
        $this->nombre->Visible = false;
        $this->direccion->Visible = false;
        $this->telefono->Visible = false;
        $this->_email->Visible = false;
        $this->activo->Visible = false;
        $this->comprobante->Visible = false;
        $this->nro_despacho->setVisibility();
        $this->cerrado->Visible = false;
        $this->impreso->setVisibility();
        $this->igtf->setVisibility();
        $this->monto_base_igtf->setVisibility();
        $this->monto_igtf->Visible = false;
        $this->pago_premio->Visible = false;
        $this->hideFieldsForAddEdit();
        $this->tipo_documento->Required = false;
        $this->documento->Required = false;

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
        $this->FormClassName = "ew-form ew-edit-form ew-horizontal";
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
            }

            // Load recordset
            if ($this->isShow()) {
                // Load current record
                $loaded = $this->loadRow();
                $this->OldKey = $loaded ? $this->getKey(true) : ""; // Get from CurrentValue
            }
        }

        // Process form if post back
        if ($postBack) {
            $this->loadFormValues(); // Get form values

            // Set up detail parameters
            $this->setupDetailParms();
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
                if (!$loaded) { // Load record based on key
                    if ($this->getFailureMessage() == "") {
                        $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                    }
                    $this->terminate("SalidasList"); // No matching record, return to list
                    return;
                }

                // Set up detail parameters
                $this->setupDetailParms();
                break;
            case "update": // Update
                $returnUrl = "SalidasView/" . urlencode($this->id->CurrentValue) . "?showdetail=entradas_salidas&tipo=" . urlencode($this->tipo_documento->CurrentValue);
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

                    // Set up detail parameters
                    $this->setupDetailParms();
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render the record
        $this->RowType = ROWTYPE_EDIT; // Render as Edit
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

        // Check field name 'monto_total' first before field var 'x_monto_total'
        $val = $CurrentForm->hasValue("monto_total") ? $CurrentForm->getValue("monto_total") : $CurrentForm->getValue("x_monto_total");
        if (!$this->monto_total->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->monto_total->Visible = false; // Disable update for API request
            } else {
                $this->monto_total->setFormValue($val);
            }
        }

        // Check field name 'alicuota_iva' first before field var 'x_alicuota_iva'
        $val = $CurrentForm->hasValue("alicuota_iva") ? $CurrentForm->getValue("alicuota_iva") : $CurrentForm->getValue("x_alicuota_iva");
        if (!$this->alicuota_iva->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->alicuota_iva->Visible = false; // Disable update for API request
            } else {
                $this->alicuota_iva->setFormValue($val);
            }
        }

        // Check field name 'iva' first before field var 'x_iva'
        $val = $CurrentForm->hasValue("iva") ? $CurrentForm->getValue("iva") : $CurrentForm->getValue("x_iva");
        if (!$this->iva->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->iva->Visible = false; // Disable update for API request
            } else {
                $this->iva->setFormValue($val);
            }
        }

        // Check field name 'total' first before field var 'x_total'
        $val = $CurrentForm->hasValue("total") ? $CurrentForm->getValue("total") : $CurrentForm->getValue("x_total");
        if (!$this->total->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->total->Visible = false; // Disable update for API request
            } else {
                $this->total->setFormValue($val);
            }
        }

        // Check field name 'tasa_dia' first before field var 'x_tasa_dia'
        $val = $CurrentForm->hasValue("tasa_dia") ? $CurrentForm->getValue("tasa_dia") : $CurrentForm->getValue("x_tasa_dia");
        if (!$this->tasa_dia->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->tasa_dia->Visible = false; // Disable update for API request
            } else {
                $this->tasa_dia->setFormValue($val);
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

        // Check field name 'estatus' first before field var 'x_estatus'
        $val = $CurrentForm->hasValue("estatus") ? $CurrentForm->getValue("estatus") : $CurrentForm->getValue("x_estatus");
        if (!$this->estatus->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->estatus->Visible = false; // Disable update for API request
            } else {
                $this->estatus->setFormValue($val);
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

        // Check field name 'dias_credito' first before field var 'x_dias_credito'
        $val = $CurrentForm->hasValue("dias_credito") ? $CurrentForm->getValue("dias_credito") : $CurrentForm->getValue("x_dias_credito");
        if (!$this->dias_credito->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->dias_credito->Visible = false; // Disable update for API request
            } else {
                $this->dias_credito->setFormValue($val);
            }
        }

        // Check field name 'entregado' first before field var 'x_entregado'
        $val = $CurrentForm->hasValue("entregado") ? $CurrentForm->getValue("entregado") : $CurrentForm->getValue("x_entregado");
        if (!$this->entregado->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->entregado->Visible = false; // Disable update for API request
            } else {
                $this->entregado->setFormValue($val);
            }
        }

        // Check field name 'pagado' first before field var 'x_pagado'
        $val = $CurrentForm->hasValue("pagado") ? $CurrentForm->getValue("pagado") : $CurrentForm->getValue("x_pagado");
        if (!$this->pagado->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->pagado->Visible = false; // Disable update for API request
            } else {
                $this->pagado->setFormValue($val);
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

        // Check field name 'nro_despacho' first before field var 'x_nro_despacho'
        $val = $CurrentForm->hasValue("nro_despacho") ? $CurrentForm->getValue("nro_despacho") : $CurrentForm->getValue("x_nro_despacho");
        if (!$this->nro_despacho->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->nro_despacho->Visible = false; // Disable update for API request
            } else {
                $this->nro_despacho->setFormValue($val);
            }
        }

        // Check field name 'impreso' first before field var 'x_impreso'
        $val = $CurrentForm->hasValue("impreso") ? $CurrentForm->getValue("impreso") : $CurrentForm->getValue("x_impreso");
        if (!$this->impreso->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->impreso->Visible = false; // Disable update for API request
            } else {
                $this->impreso->setFormValue($val);
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

        // Check field name 'monto_base_igtf' first before field var 'x_monto_base_igtf'
        $val = $CurrentForm->hasValue("monto_base_igtf") ? $CurrentForm->getValue("monto_base_igtf") : $CurrentForm->getValue("x_monto_base_igtf");
        if (!$this->monto_base_igtf->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->monto_base_igtf->Visible = false; // Disable update for API request
            } else {
                $this->monto_base_igtf->setFormValue($val);
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
        $this->tipo_documento->CurrentValue = $this->tipo_documento->FormValue;
        $this->nro_documento->CurrentValue = $this->nro_documento->FormValue;
        $this->nro_control->CurrentValue = $this->nro_control->FormValue;
        $this->fecha->CurrentValue = $this->fecha->FormValue;
        $this->fecha->CurrentValue = UnFormatDateTime($this->fecha->CurrentValue, 7);
        $this->cliente->CurrentValue = $this->cliente->FormValue;
        $this->documento->CurrentValue = $this->documento->FormValue;
        $this->doc_afectado->CurrentValue = $this->doc_afectado->FormValue;
        $this->moneda->CurrentValue = $this->moneda->FormValue;
        $this->monto_total->CurrentValue = $this->monto_total->FormValue;
        $this->alicuota_iva->CurrentValue = $this->alicuota_iva->FormValue;
        $this->iva->CurrentValue = $this->iva->FormValue;
        $this->total->CurrentValue = $this->total->FormValue;
        $this->tasa_dia->CurrentValue = $this->tasa_dia->FormValue;
        $this->nota->CurrentValue = $this->nota->FormValue;
        $this->estatus->CurrentValue = $this->estatus->FormValue;
        $this->asesor->CurrentValue = $this->asesor->FormValue;
        $this->dias_credito->CurrentValue = $this->dias_credito->FormValue;
        $this->entregado->CurrentValue = $this->entregado->FormValue;
        $this->pagado->CurrentValue = $this->pagado->FormValue;
        $this->descuento->CurrentValue = $this->descuento->FormValue;
        $this->nro_despacho->CurrentValue = $this->nro_despacho->FormValue;
        $this->impreso->CurrentValue = $this->impreso->FormValue;
        $this->igtf->CurrentValue = $this->igtf->FormValue;
        $this->monto_base_igtf->CurrentValue = $this->monto_base_igtf->FormValue;
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
        $row = [];
        $row['id'] = null;
        $row['tipo_documento'] = null;
        $row['nro_documento'] = null;
        $row['nro_control'] = null;
        $row['fecha'] = null;
        $row['cliente'] = null;
        $row['documento'] = null;
        $row['doc_afectado'] = null;
        $row['moneda'] = null;
        $row['monto_total'] = null;
        $row['alicuota_iva'] = null;
        $row['iva'] = null;
        $row['total'] = null;
        $row['tasa_dia'] = null;
        $row['monto_usd'] = null;
        $row['lista_pedido'] = null;
        $row['nota'] = null;
        $row['username'] = null;
        $row['estatus'] = null;
        $row['id_documento_padre'] = null;
        $row['asesor'] = null;
        $row['pago_divisa'] = null;
        $row['dias_credito'] = null;
        $row['entregado'] = null;
        $row['fecha_entrega'] = null;
        $row['pagado'] = null;
        $row['bultos'] = null;
        $row['fecha_bultos'] = null;
        $row['user_bultos'] = null;
        $row['fecha_despacho'] = null;
        $row['user_despacho'] = null;
        $row['consignacion'] = null;
        $row['unidades'] = null;
        $row['descuento'] = null;
        $row['monto_sin_descuento'] = null;
        $row['factura'] = null;
        $row['ci_rif'] = null;
        $row['nombre'] = null;
        $row['direccion'] = null;
        $row['telefono'] = null;
        $row['email'] = null;
        $row['activo'] = null;
        $row['comprobante'] = null;
        $row['nro_despacho'] = null;
        $row['cerrado'] = null;
        $row['impreso'] = null;
        $row['igtf'] = null;
        $row['monto_base_igtf'] = null;
        $row['monto_igtf'] = null;
        $row['pago_premio'] = null;
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
        if ($this->monto_total->FormValue == $this->monto_total->CurrentValue && is_numeric(ConvertToFloatString($this->monto_total->CurrentValue))) {
            $this->monto_total->CurrentValue = ConvertToFloatString($this->monto_total->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->alicuota_iva->FormValue == $this->alicuota_iva->CurrentValue && is_numeric(ConvertToFloatString($this->alicuota_iva->CurrentValue))) {
            $this->alicuota_iva->CurrentValue = ConvertToFloatString($this->alicuota_iva->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->iva->FormValue == $this->iva->CurrentValue && is_numeric(ConvertToFloatString($this->iva->CurrentValue))) {
            $this->iva->CurrentValue = ConvertToFloatString($this->iva->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->total->FormValue == $this->total->CurrentValue && is_numeric(ConvertToFloatString($this->total->CurrentValue))) {
            $this->total->CurrentValue = ConvertToFloatString($this->total->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->tasa_dia->FormValue == $this->tasa_dia->CurrentValue && is_numeric(ConvertToFloatString($this->tasa_dia->CurrentValue))) {
            $this->tasa_dia->CurrentValue = ConvertToFloatString($this->tasa_dia->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->monto_base_igtf->FormValue == $this->monto_base_igtf->CurrentValue && is_numeric(ConvertToFloatString($this->monto_base_igtf->CurrentValue))) {
            $this->monto_base_igtf->CurrentValue = ConvertToFloatString($this->monto_base_igtf->CurrentValue);
        }

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

            // dias_credito
            $this->dias_credito->ViewValue = $this->dias_credito->CurrentValue;
            $this->dias_credito->ViewCustomAttributes = "";

            // entregado
            if (strval($this->entregado->CurrentValue) != "") {
                $this->entregado->ViewValue = $this->entregado->optionCaption($this->entregado->CurrentValue);
            } else {
                $this->entregado->ViewValue = null;
            }
            $this->entregado->CssClass = "font-weight-bold font-italic";
            $this->entregado->ViewCustomAttributes = "";

            // pagado
            if (strval($this->pagado->CurrentValue) != "") {
                $this->pagado->ViewValue = $this->pagado->optionCaption($this->pagado->CurrentValue);
            } else {
                $this->pagado->ViewValue = null;
            }
            $this->pagado->CssClass = "font-weight-bold font-italic";
            $this->pagado->ViewCustomAttributes = "";

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

            // nro_despacho
            $this->nro_despacho->ViewValue = $this->nro_despacho->CurrentValue;
            $this->nro_despacho->ViewCustomAttributes = "";

            // impreso
            if (strval($this->impreso->CurrentValue) != "") {
                $this->impreso->ViewValue = $this->impreso->optionCaption($this->impreso->CurrentValue);
            } else {
                $this->impreso->ViewValue = null;
            }
            $this->impreso->ViewCustomAttributes = "";

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

            // monto_total
            $this->monto_total->LinkCustomAttributes = "";
            $this->monto_total->HrefValue = "";
            $this->monto_total->TooltipValue = "";

            // alicuota_iva
            $this->alicuota_iva->LinkCustomAttributes = "";
            $this->alicuota_iva->HrefValue = "";
            $this->alicuota_iva->TooltipValue = "";

            // iva
            $this->iva->LinkCustomAttributes = "";
            $this->iva->HrefValue = "";
            $this->iva->TooltipValue = "";

            // total
            $this->total->LinkCustomAttributes = "";
            $this->total->HrefValue = "";
            $this->total->TooltipValue = "";

            // tasa_dia
            $this->tasa_dia->LinkCustomAttributes = "";
            $this->tasa_dia->HrefValue = "";
            $this->tasa_dia->TooltipValue = "";

            // nota
            $this->nota->LinkCustomAttributes = "";
            $this->nota->HrefValue = "";
            $this->nota->TooltipValue = "";

            // estatus
            $this->estatus->LinkCustomAttributes = "";
            $this->estatus->HrefValue = "";
            $this->estatus->TooltipValue = "";

            // asesor
            $this->asesor->LinkCustomAttributes = "";
            $this->asesor->HrefValue = "";
            $this->asesor->TooltipValue = "";

            // dias_credito
            $this->dias_credito->LinkCustomAttributes = "";
            $this->dias_credito->HrefValue = "";
            $this->dias_credito->TooltipValue = "";

            // entregado
            $this->entregado->LinkCustomAttributes = "";
            $this->entregado->HrefValue = "";
            $this->entregado->TooltipValue = "";

            // pagado
            $this->pagado->LinkCustomAttributes = "";
            $this->pagado->HrefValue = "";
            $this->pagado->TooltipValue = "";

            // descuento
            $this->descuento->LinkCustomAttributes = "";
            $this->descuento->HrefValue = "";
            $this->descuento->TooltipValue = "";

            // nro_despacho
            $this->nro_despacho->LinkCustomAttributes = "";
            $this->nro_despacho->HrefValue = "";
            $this->nro_despacho->TooltipValue = "";

            // impreso
            $this->impreso->LinkCustomAttributes = "";
            $this->impreso->HrefValue = "";
            $this->impreso->TooltipValue = "";

            // igtf
            $this->igtf->LinkCustomAttributes = "";
            $this->igtf->HrefValue = "";
            $this->igtf->TooltipValue = "";

            // monto_base_igtf
            $this->monto_base_igtf->LinkCustomAttributes = "";
            $this->monto_base_igtf->HrefValue = "";
            $this->monto_base_igtf->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // tipo_documento
            $this->tipo_documento->EditAttrs["class"] = "form-control";
            $this->tipo_documento->EditCustomAttributes = "";
            $this->tipo_documento->EditValue = $this->tipo_documento->CurrentValue;
            $this->tipo_documento->ViewCustomAttributes = "";

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
            if (strval($this->documento->CurrentValue) != "") {
                $this->documento->EditValue = $this->documento->optionCaption($this->documento->CurrentValue);
            } else {
                $this->documento->EditValue = null;
            }
            $this->documento->ViewCustomAttributes = "";

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

            // monto_total
            $this->monto_total->EditAttrs["class"] = "form-control";
            $this->monto_total->EditCustomAttributes = "";
            $this->monto_total->EditValue = $this->monto_total->CurrentValue;
            $this->monto_total->EditValue = FormatNumber($this->monto_total->EditValue, 2, -1, -1, -1);
            $this->monto_total->ViewCustomAttributes = "";

            // alicuota_iva
            $this->alicuota_iva->EditAttrs["class"] = "form-control";
            $this->alicuota_iva->EditCustomAttributes = "";
            $this->alicuota_iva->EditValue = $this->alicuota_iva->CurrentValue;
            $this->alicuota_iva->EditValue = FormatNumber($this->alicuota_iva->EditValue, 2, -1, -1, -1);
            $this->alicuota_iva->ViewCustomAttributes = "";

            // iva
            $this->iva->EditAttrs["class"] = "form-control";
            $this->iva->EditCustomAttributes = "";
            $this->iva->EditValue = $this->iva->CurrentValue;
            $this->iva->EditValue = FormatNumber($this->iva->EditValue, 2, -1, -1, -1);
            $this->iva->ViewCustomAttributes = "";

            // total
            $this->total->EditAttrs["class"] = "form-control";
            $this->total->EditCustomAttributes = "";
            $this->total->EditValue = $this->total->CurrentValue;
            $this->total->EditValue = FormatNumber($this->total->EditValue, 2, -1, -1, -1);
            $this->total->ViewCustomAttributes = "";

            // tasa_dia
            $this->tasa_dia->EditAttrs["class"] = "form-control";
            $this->tasa_dia->EditCustomAttributes = "";
            $this->tasa_dia->EditValue = $this->tasa_dia->CurrentValue;
            $this->tasa_dia->EditValue = FormatNumber($this->tasa_dia->EditValue, 2, -1, -1, -1);
            $this->tasa_dia->ViewCustomAttributes = "";

            // nota
            $this->nota->EditAttrs["class"] = "form-control";
            $this->nota->EditCustomAttributes = "";
            $this->nota->EditValue = HtmlEncode($this->nota->CurrentValue);
            $this->nota->PlaceHolder = RemoveHtml($this->nota->caption());

            // estatus
            $this->estatus->EditAttrs["class"] = "form-control";
            $this->estatus->EditCustomAttributes = "";
            $this->estatus->EditValue = $this->estatus->options(true);
            $this->estatus->PlaceHolder = RemoveHtml($this->estatus->caption());

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

            // dias_credito
            $this->dias_credito->EditAttrs["class"] = "form-control";
            $this->dias_credito->EditCustomAttributes = "";
            $this->dias_credito->EditValue = HtmlEncode($this->dias_credito->CurrentValue);
            $this->dias_credito->PlaceHolder = RemoveHtml($this->dias_credito->caption());

            // entregado
            $this->entregado->EditAttrs["class"] = "form-control";
            $this->entregado->EditCustomAttributes = "";
            $this->entregado->EditValue = $this->entregado->options(true);
            $this->entregado->PlaceHolder = RemoveHtml($this->entregado->caption());

            // pagado
            $this->pagado->EditAttrs["class"] = "form-control";
            $this->pagado->EditCustomAttributes = "";
            $this->pagado->EditValue = $this->pagado->options(true);
            $this->pagado->PlaceHolder = RemoveHtml($this->pagado->caption());

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

            // nro_despacho
            $this->nro_despacho->EditAttrs["class"] = "form-control";
            $this->nro_despacho->EditCustomAttributes = "";
            if (!$this->nro_despacho->Raw) {
                $this->nro_despacho->CurrentValue = HtmlDecode($this->nro_despacho->CurrentValue);
            }
            $this->nro_despacho->EditValue = HtmlEncode($this->nro_despacho->CurrentValue);
            $this->nro_despacho->PlaceHolder = RemoveHtml($this->nro_despacho->caption());

            // impreso
            $this->impreso->EditAttrs["class"] = "form-control";
            $this->impreso->EditCustomAttributes = "";
            if (strval($this->impreso->CurrentValue) != "") {
                $this->impreso->EditValue = $this->impreso->optionCaption($this->impreso->CurrentValue);
            } else {
                $this->impreso->EditValue = null;
            }
            $this->impreso->ViewCustomAttributes = "";

            // igtf
            $this->igtf->EditAttrs["class"] = "form-control";
            $this->igtf->EditCustomAttributes = "";
            $this->igtf->EditValue = $this->igtf->options(true);
            $this->igtf->PlaceHolder = RemoveHtml($this->igtf->caption());

            // monto_base_igtf
            $this->monto_base_igtf->EditAttrs["class"] = "form-control";
            $this->monto_base_igtf->EditCustomAttributes = "";
            $this->monto_base_igtf->EditValue = HtmlEncode($this->monto_base_igtf->CurrentValue);
            $this->monto_base_igtf->PlaceHolder = RemoveHtml($this->monto_base_igtf->caption());
            if (strval($this->monto_base_igtf->EditValue) != "" && is_numeric($this->monto_base_igtf->EditValue)) {
                $this->monto_base_igtf->EditValue = FormatNumber($this->monto_base_igtf->EditValue, -2, -2, -2, -2);
            }

            // Edit refer script

            // tipo_documento
            $this->tipo_documento->LinkCustomAttributes = "";
            $this->tipo_documento->HrefValue = "";
            $this->tipo_documento->TooltipValue = "";

            // nro_documento
            $this->nro_documento->LinkCustomAttributes = "";
            $this->nro_documento->HrefValue = "";

            // nro_control
            $this->nro_control->LinkCustomAttributes = "";
            $this->nro_control->HrefValue = "";

            // fecha
            $this->fecha->LinkCustomAttributes = "";
            $this->fecha->HrefValue = "";

            // cliente
            $this->cliente->LinkCustomAttributes = "";
            $this->cliente->HrefValue = "";

            // documento
            $this->documento->LinkCustomAttributes = "";
            $this->documento->HrefValue = "";
            $this->documento->TooltipValue = "";

            // doc_afectado
            $this->doc_afectado->LinkCustomAttributes = "";
            $this->doc_afectado->HrefValue = "";

            // moneda
            $this->moneda->LinkCustomAttributes = "";
            $this->moneda->HrefValue = "";

            // monto_total
            $this->monto_total->LinkCustomAttributes = "";
            $this->monto_total->HrefValue = "";
            $this->monto_total->TooltipValue = "";

            // alicuota_iva
            $this->alicuota_iva->LinkCustomAttributes = "";
            $this->alicuota_iva->HrefValue = "";
            $this->alicuota_iva->TooltipValue = "";

            // iva
            $this->iva->LinkCustomAttributes = "";
            $this->iva->HrefValue = "";
            $this->iva->TooltipValue = "";

            // total
            $this->total->LinkCustomAttributes = "";
            $this->total->HrefValue = "";
            $this->total->TooltipValue = "";

            // tasa_dia
            $this->tasa_dia->LinkCustomAttributes = "";
            $this->tasa_dia->HrefValue = "";
            $this->tasa_dia->TooltipValue = "";

            // nota
            $this->nota->LinkCustomAttributes = "";
            $this->nota->HrefValue = "";

            // estatus
            $this->estatus->LinkCustomAttributes = "";
            $this->estatus->HrefValue = "";

            // asesor
            $this->asesor->LinkCustomAttributes = "";
            $this->asesor->HrefValue = "";

            // dias_credito
            $this->dias_credito->LinkCustomAttributes = "";
            $this->dias_credito->HrefValue = "";

            // entregado
            $this->entregado->LinkCustomAttributes = "";
            $this->entregado->HrefValue = "";

            // pagado
            $this->pagado->LinkCustomAttributes = "";
            $this->pagado->HrefValue = "";

            // descuento
            $this->descuento->LinkCustomAttributes = "";
            $this->descuento->HrefValue = "";

            // nro_despacho
            $this->nro_despacho->LinkCustomAttributes = "";
            $this->nro_despacho->HrefValue = "";

            // impreso
            $this->impreso->LinkCustomAttributes = "";
            $this->impreso->HrefValue = "";
            $this->impreso->TooltipValue = "";

            // igtf
            $this->igtf->LinkCustomAttributes = "";
            $this->igtf->HrefValue = "";

            // monto_base_igtf
            $this->monto_base_igtf->LinkCustomAttributes = "";
            $this->monto_base_igtf->HrefValue = "";
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
        if ($this->monto_total->Required) {
            if (!$this->monto_total->IsDetailKey && EmptyValue($this->monto_total->FormValue)) {
                $this->monto_total->addErrorMessage(str_replace("%s", $this->monto_total->caption(), $this->monto_total->RequiredErrorMessage));
            }
        }
        if ($this->alicuota_iva->Required) {
            if (!$this->alicuota_iva->IsDetailKey && EmptyValue($this->alicuota_iva->FormValue)) {
                $this->alicuota_iva->addErrorMessage(str_replace("%s", $this->alicuota_iva->caption(), $this->alicuota_iva->RequiredErrorMessage));
            }
        }
        if ($this->iva->Required) {
            if (!$this->iva->IsDetailKey && EmptyValue($this->iva->FormValue)) {
                $this->iva->addErrorMessage(str_replace("%s", $this->iva->caption(), $this->iva->RequiredErrorMessage));
            }
        }
        if ($this->total->Required) {
            if (!$this->total->IsDetailKey && EmptyValue($this->total->FormValue)) {
                $this->total->addErrorMessage(str_replace("%s", $this->total->caption(), $this->total->RequiredErrorMessage));
            }
        }
        if ($this->tasa_dia->Required) {
            if (!$this->tasa_dia->IsDetailKey && EmptyValue($this->tasa_dia->FormValue)) {
                $this->tasa_dia->addErrorMessage(str_replace("%s", $this->tasa_dia->caption(), $this->tasa_dia->RequiredErrorMessage));
            }
        }
        if ($this->nota->Required) {
            if (!$this->nota->IsDetailKey && EmptyValue($this->nota->FormValue)) {
                $this->nota->addErrorMessage(str_replace("%s", $this->nota->caption(), $this->nota->RequiredErrorMessage));
            }
        }
        if ($this->estatus->Required) {
            if (!$this->estatus->IsDetailKey && EmptyValue($this->estatus->FormValue)) {
                $this->estatus->addErrorMessage(str_replace("%s", $this->estatus->caption(), $this->estatus->RequiredErrorMessage));
            }
        }
        if ($this->asesor->Required) {
            if (!$this->asesor->IsDetailKey && EmptyValue($this->asesor->FormValue)) {
                $this->asesor->addErrorMessage(str_replace("%s", $this->asesor->caption(), $this->asesor->RequiredErrorMessage));
            }
        }
        if ($this->dias_credito->Required) {
            if (!$this->dias_credito->IsDetailKey && EmptyValue($this->dias_credito->FormValue)) {
                $this->dias_credito->addErrorMessage(str_replace("%s", $this->dias_credito->caption(), $this->dias_credito->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->dias_credito->FormValue)) {
            $this->dias_credito->addErrorMessage($this->dias_credito->getErrorMessage(false));
        }
        if ($this->entregado->Required) {
            if (!$this->entregado->IsDetailKey && EmptyValue($this->entregado->FormValue)) {
                $this->entregado->addErrorMessage(str_replace("%s", $this->entregado->caption(), $this->entregado->RequiredErrorMessage));
            }
        }
        if ($this->pagado->Required) {
            if (!$this->pagado->IsDetailKey && EmptyValue($this->pagado->FormValue)) {
                $this->pagado->addErrorMessage(str_replace("%s", $this->pagado->caption(), $this->pagado->RequiredErrorMessage));
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
        if ($this->nro_despacho->Required) {
            if (!$this->nro_despacho->IsDetailKey && EmptyValue($this->nro_despacho->FormValue)) {
                $this->nro_despacho->addErrorMessage(str_replace("%s", $this->nro_despacho->caption(), $this->nro_despacho->RequiredErrorMessage));
            }
        }
        if ($this->impreso->Required) {
            if ($this->impreso->FormValue == "") {
                $this->impreso->addErrorMessage(str_replace("%s", $this->impreso->caption(), $this->impreso->RequiredErrorMessage));
            }
        }
        if ($this->igtf->Required) {
            if (!$this->igtf->IsDetailKey && EmptyValue($this->igtf->FormValue)) {
                $this->igtf->addErrorMessage(str_replace("%s", $this->igtf->caption(), $this->igtf->RequiredErrorMessage));
            }
        }
        if ($this->monto_base_igtf->Required) {
            if (!$this->monto_base_igtf->IsDetailKey && EmptyValue($this->monto_base_igtf->FormValue)) {
                $this->monto_base_igtf->addErrorMessage(str_replace("%s", $this->monto_base_igtf->caption(), $this->monto_base_igtf->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->monto_base_igtf->FormValue)) {
            $this->monto_base_igtf->addErrorMessage($this->monto_base_igtf->getErrorMessage(false));
        }

        // Validate detail grid
        $detailTblVar = explode(",", $this->getCurrentDetailTable());
        $detailPage = Container("EntradasSalidasGrid");
        if (in_array("entradas_salidas", $detailTblVar) && $detailPage->DetailEdit) {
            $detailPage->validateGridForm();
        }
        $detailPage = Container("PagosGrid");
        if (in_array("pagos", $detailTblVar) && $detailPage->DetailEdit) {
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

    // Update record based on key values
    protected function editRow()
    {
        global $Security, $Language;
        $oldKeyFilter = $this->getRecordFilter();
        $filter = $this->applyUserIDFilters($oldKeyFilter);
        $conn = $this->getConnection();
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $rsold = $conn->fetchAssoc($sql);
        $editRow = false;
        if (!$rsold) {
            $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
            $editRow = false; // Update Failed
        } else {
            // Begin transaction
            if ($this->getCurrentDetailTable() != "") {
                $conn->beginTransaction();
            }

            // Save old values
            $this->loadDbValues($rsold);
            $rsnew = [];

            // nro_documento
            $this->nro_documento->setDbValueDef($rsnew, $this->nro_documento->CurrentValue, null, $this->nro_documento->ReadOnly);

            // nro_control
            $this->nro_control->setDbValueDef($rsnew, $this->nro_control->CurrentValue, null, $this->nro_control->ReadOnly);

            // fecha
            $this->fecha->setDbValueDef($rsnew, UnFormatDateTime($this->fecha->CurrentValue, 7), null, $this->fecha->ReadOnly);

            // cliente
            $this->cliente->setDbValueDef($rsnew, $this->cliente->CurrentValue, null, $this->cliente->ReadOnly);

            // doc_afectado
            $this->doc_afectado->setDbValueDef($rsnew, $this->doc_afectado->CurrentValue, null, $this->doc_afectado->ReadOnly);

            // moneda
            $this->moneda->setDbValueDef($rsnew, $this->moneda->CurrentValue, null, $this->moneda->ReadOnly);

            // nota
            $this->nota->setDbValueDef($rsnew, $this->nota->CurrentValue, null, $this->nota->ReadOnly);

            // estatus
            $this->estatus->setDbValueDef($rsnew, $this->estatus->CurrentValue, null, $this->estatus->ReadOnly);

            // asesor
            $this->asesor->setDbValueDef($rsnew, $this->asesor->CurrentValue, null, $this->asesor->ReadOnly);

            // dias_credito
            $this->dias_credito->setDbValueDef($rsnew, $this->dias_credito->CurrentValue, null, $this->dias_credito->ReadOnly);

            // entregado
            $this->entregado->setDbValueDef($rsnew, $this->entregado->CurrentValue, null, $this->entregado->ReadOnly);

            // pagado
            $this->pagado->setDbValueDef($rsnew, $this->pagado->CurrentValue, null, $this->pagado->ReadOnly);

            // descuento
            $this->descuento->setDbValueDef($rsnew, $this->descuento->CurrentValue, null, $this->descuento->ReadOnly);

            // nro_despacho
            $this->nro_despacho->setDbValueDef($rsnew, $this->nro_despacho->CurrentValue, null, $this->nro_despacho->ReadOnly);

            // igtf
            $this->igtf->setDbValueDef($rsnew, $this->igtf->CurrentValue, null, $this->igtf->ReadOnly);

            // monto_base_igtf
            $this->monto_base_igtf->setDbValueDef($rsnew, $this->monto_base_igtf->CurrentValue, null, $this->monto_base_igtf->ReadOnly);

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

                // Update detail records
                $detailTblVar = explode(",", $this->getCurrentDetailTable());
                if ($editRow) {
                    $detailPage = Container("EntradasSalidasGrid");
                    if (in_array("entradas_salidas", $detailTblVar) && $detailPage->DetailEdit) {
                        $Security->loadCurrentUserLevel($this->ProjectID . "entradas_salidas"); // Load user level of detail table
                        $editRow = $detailPage->gridUpdate();
                        $Security->loadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
                    }
                }
                if ($editRow) {
                    $detailPage = Container("PagosGrid");
                    if (in_array("pagos", $detailTblVar) && $detailPage->DetailEdit) {
                        $Security->loadCurrentUserLevel($this->ProjectID . "pagos"); // Load user level of detail table
                        $editRow = $detailPage->gridUpdate();
                        $Security->loadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
                    }
                }

                // Commit/Rollback transaction
                if ($this->getCurrentDetailTable() != "") {
                    if ($editRow) {
                        $conn->commit(); // Commit transaction
                    } else {
                        $conn->rollback(); // Rollback transaction
                    }
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
                if ($detailPageObj->DetailEdit) {
                    $detailPageObj->CurrentMode = "edit";
                    $detailPageObj->CurrentAction = "gridedit";

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
                if ($detailPageObj->DetailEdit) {
                    $detailPageObj->CurrentMode = "edit";
                    $detailPageObj->CurrentAction = "gridedit";

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
    	/*
    	if(isset($_REQUEST["tipo"])) { 
    		$tipo = $_REQUEST["tipo"];
    		$sql = "SELECT descripcion FROM tipo_documento WHERE codigo = '$tipo';";
    		$tipo_name = ExecuteScalar($sql);
       		$tipo_name = '<a href="SalidasList?tipo=' . $tipo . '">' . $tipo_name . '</a>';
    		$this->setTableCaption($tipo_name);
    	}
    	elseif(isset($_REQUEST["x_tipo_documento"])) { 
    		$tipo = $_REQUEST["x_tipo_documento"];
    		$sql = "SELECT descripcion FROM tipo_documento WHERE codigo = '$tipo';";
    		$tipo_name = ExecuteScalar($sql);
       		$tipo_name = '<a href="SalidasList?tipo=' . $tipo . '">' . $tipo_name . '</a>';
    		$this->setTableCaption($tipo_name);
    	}
    	else header("Location: Home");
    	*/
       	$tipo = isset($_REQUEST["tipo"]) ? $_REQUEST["tipo"] : $_REQUEST["x_tipo_documento"];
    	$sql = "SELECT descripcion FROM tipo_documento WHERE codigo = '$tipo';";
    	$tipo_name = ExecuteScalar($sql);
    	$tipo_name = '<a href="SalidasList?tipo=' . $tipo . '">' . $tipo_name . '</a>';
    	$this->setTableCaption($tipo_name);
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
    public function messageShowing(&$msg, $type) {
    	if ($type == 'success') {
    		//$msg = "Copia Realizada Exitosamente";
    		$msg = "";
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
    	$this->tasa_dia->Visible = TRUE;
    	$this->asesor->Visible = FALSE;
    	$this->ci_rif->Visible = FALSE;
    	$tipo = $this->tipo_documento->CurrentValue;
    	switch($tipo) {
    	case "TDCPDV":
    		$this->fecha->ReadOnly = TRUE;
    		$this->nro_documento->ReadOnly = TRUE;
    		$this->nro_control->Visible = FALSE;
    		$this->monto_total->Visible = FALSE;
    		$this->alicuota_iva->Visible = FALSE;
    		$this->iva->Visible = FALSE;
    		$this->total->Visible = FALSE;
    		$this->id_documento_padre->Visible = FALSE;
    		$this->moneda->Visible = FALSE;
    		$this->documento->Visible = FALSE;
    		$this->doc_afectado->Visible = FALSE;
    		$this->consignacion->Visible = TRUE;
    		$this->tasa_dia->Visible = FALSE;
    		$this->monto_usd->Visible = FALSE;
    		$this->dias_credito->Visible = FALSE;
    		$this->entregado->Visible = FALSE;
    		$this->fecha_entrega->Visible = FALSE;
    		$this->pagado->Visible = FALSE;
    		$this->bultos->Visible = FALSE;
    		$this->fecha_bultos->Visible = FALSE;
    		$this->fecha_despacho->Visible = FALSE;
    		$this->user_bultos->Visible = FALSE;
    		$this->user_despacho->Visible = FALSE;
    		$this->lista_pedido->Visible = FALSE;
    		break;
    	case "TDCNET":
    		$this->descuento->Visible = TRUE;
    		$this->fecha->ReadOnly = TRUE;
    		$this->nro_documento->ReadOnly = TRUE;
    		$this->nro_control->Visible = FALSE;
    		$this->monto_total->Visible = FALSE;
    		$this->alicuota_iva->Visible = FALSE;
    		$this->iva->Visible = FALSE;
    		$this->total->Visible = FALSE;
    		$this->id_documento_padre->Visible = TRUE;
    		$this->monto_usd->Visible = FALSE;
    		$this->moneda->Visible = FALSE;
    		$this->documento->Visible = FALSE;
    		$this->doc_afectado->Visible = FALSE;
    		//$this->consignacion->Visible = FALSE;
    		$this->dias_credito->Visible = FALSE;
    		$this->entregado->Visible = FALSE;
    		$this->fecha_entrega->Visible = FALSE;
    		$this->pagado->Visible = FALSE;
    		if($this->estatus->CurrentValue != "PROCESADO" and $this->estatus->CurrentValue != "NUEVO") {
    			$this->bultos->Visible = FALSE;
    		}
    		$this->lista_pedido->Visible = FALSE;
    		$this->impreso->Visible = TRUE;
    		$this->asesor->Visible = TRUE;
    		$this->impreso->Visible = FALSE;
    		$this->igtf->Visible = FALSE;
    		$this->monto_base_igtf->Visible = FALSE;
    		$this->monto_igtf->Visible = FALSE;
    		break;
    	case "TDCFCV":
    		$this->descuento->Visible = TRUE;
    		$this->id_documento_padre->Visible = FALSE;
    		if($this->documento->CurrentValue == "FC") {
    			$this->entregado->Visible = TRUE;
    			$this->fecha_entrega->Visible = TRUE;
    			$this->pagado->Visible = TRUE;
    		}
    		else {
    			$this->entregado->Visible = FALSE;
    			$this->fecha_entrega->Visible = FALSE;
    			$this->pagado->Visible = FALSE;
    		}

    		//$this->consignacion->Visible = FALSE;
    		$this->bultos->Visible = FALSE;
    		$this->fecha_bultos->Visible = FALSE;
    		$this->fecha_despacho->Visible = FALSE;
    		$this->user_bultos->Visible = FALSE;
    		$this->user_despacho->Visible = FALSE;
    		$this->lista_pedido->Visible = FALSE;
    		$this->nro_despacho->Visible = TRUE;
    		$this->impreso->Visible = TRUE;
    		break;
    	case "TDCASA":
    		$this->factura->Visible = TRUE;
    		$this->nombre->Visible = TRUE;
    		$this->fecha->ReadOnly = TRUE;
    		$this->nro_documento->ReadOnly = TRUE;
    		$this->nro_control->Visible = FALSE;
    		$this->monto_total->Visible = FALSE;
    		$this->alicuota_iva->Visible = FALSE;
    		$this->iva->Visible = FALSE;
    		$this->total->Visible = FALSE;
    		$this->id_documento_padre->Visible = FALSE;
    		$this->tasa_dia->Visible = FALSE;
    		$this->monto_usd->Visible = FALSE;
    		$this->moneda->Visible = FALSE;
    		$this->documento->Visible = FALSE;
    		$this->doc_afectado->Visible = FALSE;
    		$this->consignacion->Visible = FALSE;
    		$this->dias_credito->Visible = FALSE;
    		$this->entregado->Visible = FALSE;
    		$this->fecha_entrega->Visible = FALSE;
    		$this->pagado->Visible = FALSE;
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
    }

    // Page Data Rendered event
    public function pageDataRendered(&$footer) {
    	// Example:
    	if($this->tipo_documento->CurrentValue == "TDCFCV") {
    		$sql = "SELECT id_documento_padre FROM salidas WHERE id = " . $this->id->CurrentValue . ";";
    		$id_documento_padre = intval(ExecuteScalar($sql));
    		$sql = "SELECT 
    					a.nro_documento, a.id_documento_padre, b.descripcion, a.nota  
    				FROM 
    					salidas AS a JOIN tipo_documento AS b ON b.codigo = a.tipo_documento  
    				WHERE a.id = $id_documento_padre;";
    		if($row = ExecuteRow($sql)) {
    			$nro_documento = $row["nro_documento"];
    			$id_documento_padre = intval($row["id_documento_padre"]);
    			$descripcion = $row["descripcion"];
    			$nota = $row["nota"];
    		}
    		else {
    			$nro_documento = "";
    			$id_documento_padre = "";
    			$descripcion = "";
    			$nota = "";
    		}
    		$footer = '<div class="alert alert-warning" role="alert">';
    		$footer .= '<h2><strong>Notas</strong></h2>';
    		$footer .= '<strong>' . $descripcion . ' #' . $nro_documento . '</strong>: ';
    		$footer .= '' . $nota . '';
    		if($id_documento_padre != 0) {
    			$sql = "SELECT 
    								a.nro_documento, a.id_documento_padre, b.descripcion, a.nota  
    					FROM 
    						salidas AS a JOIN tipo_documento AS b ON b.codigo = a.tipo_documento  
    					WHERE a.id = $id_documento_padre;";
    			$row = ExecuteRow($sql);
    			$nro_documento = $row["nro_documento"];
    			$id_documento_padre = $row["id_documento_padre"];
    			$descripcion = $row["descripcion"];
    			$nota = $row["nota"];
    			$footer .= '<br>';
    			$footer .= '<strong>' . $descripcion . ' #' . $nro_documento . '</strong>: ';
    			$footer .= '' . $nota . '';
    		}
    		$footer .= '</div>';
    	}
    }

    // Form Custom Validate event
    public function formCustomValidate(&$customError)
    {
        // Return error message in CustomError
        return true;
    }
}
