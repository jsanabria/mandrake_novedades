<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class EntradasEdit extends Entradas
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'entradas';

    // Page object name
    public $PageObjName = "EntradasEdit";

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
        $this->proveedor->setVisibility();
        $this->almacen->Visible = false;
        $this->monto_total->setVisibility();
        $this->alicuota_iva->setVisibility();
        $this->iva->setVisibility();
        $this->total->setVisibility();
        $this->documento->setVisibility();
        $this->doc_afectado->setVisibility();
        $this->nota->setVisibility();
        $this->estatus->setVisibility();
        $this->_username->Visible = false;
        $this->id_documento_padre->Visible = false;
        $this->moneda->setVisibility();
        $this->consignacion->Visible = false;
        $this->consignacion_reportada->Visible = false;
        $this->aplica_retencion->setVisibility();
        $this->ret_iva->Visible = false;
        $this->ref_iva->setVisibility();
        $this->ret_islr->Visible = false;
        $this->ref_islr->setVisibility();
        $this->ret_municipal->Visible = false;
        $this->ref_municipal->setVisibility();
        $this->monto_pagar->Visible = false;
        $this->comprobante->Visible = false;
        $this->tipo_iva->Visible = false;
        $this->tipo_islr->Visible = false;
        $this->sustraendo->Visible = false;
        $this->fecha_registro_retenciones->setVisibility();
        $this->tasa_dia->setVisibility();
        $this->monto_usd->setVisibility();
        $this->fecha_libro_compra->Visible = false;
        $this->tipo_municipal->Visible = false;
        $this->cerrado->Visible = false;
        $this->cliente->Visible = false;
        $this->descuento->setVisibility();
        $this->hideFieldsForAddEdit();
        $this->tipo_documento->Required = false;
        $this->proveedor->Required = false;

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
                    $this->terminate("EntradasList"); // No matching record, return to list
                    return;
                }

                // Set up detail parameters
                $this->setupDetailParms();
                break;
            case "update": // Update
                $returnUrl = "EntradasView/" . urlencode($this->id->CurrentValue) . "?showdetail=entradas_salidas&tipo=" . urlencode($this->tipo_documento->CurrentValue);
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

        // Check field name 'proveedor' first before field var 'x_proveedor'
        $val = $CurrentForm->hasValue("proveedor") ? $CurrentForm->getValue("proveedor") : $CurrentForm->getValue("x_proveedor");
        if (!$this->proveedor->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->proveedor->Visible = false; // Disable update for API request
            } else {
                $this->proveedor->setFormValue($val);
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

        // Check field name 'estatus' first before field var 'x_estatus'
        $val = $CurrentForm->hasValue("estatus") ? $CurrentForm->getValue("estatus") : $CurrentForm->getValue("x_estatus");
        if (!$this->estatus->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->estatus->Visible = false; // Disable update for API request
            } else {
                $this->estatus->setFormValue($val);
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

        // Check field name 'aplica_retencion' first before field var 'x_aplica_retencion'
        $val = $CurrentForm->hasValue("aplica_retencion") ? $CurrentForm->getValue("aplica_retencion") : $CurrentForm->getValue("x_aplica_retencion");
        if (!$this->aplica_retencion->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->aplica_retencion->Visible = false; // Disable update for API request
            } else {
                $this->aplica_retencion->setFormValue($val);
            }
        }

        // Check field name 'ref_iva' first before field var 'x_ref_iva'
        $val = $CurrentForm->hasValue("ref_iva") ? $CurrentForm->getValue("ref_iva") : $CurrentForm->getValue("x_ref_iva");
        if (!$this->ref_iva->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ref_iva->Visible = false; // Disable update for API request
            } else {
                $this->ref_iva->setFormValue($val);
            }
        }

        // Check field name 'ref_islr' first before field var 'x_ref_islr'
        $val = $CurrentForm->hasValue("ref_islr") ? $CurrentForm->getValue("ref_islr") : $CurrentForm->getValue("x_ref_islr");
        if (!$this->ref_islr->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ref_islr->Visible = false; // Disable update for API request
            } else {
                $this->ref_islr->setFormValue($val);
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

        // Check field name 'tasa_dia' first before field var 'x_tasa_dia'
        $val = $CurrentForm->hasValue("tasa_dia") ? $CurrentForm->getValue("tasa_dia") : $CurrentForm->getValue("x_tasa_dia");
        if (!$this->tasa_dia->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->tasa_dia->Visible = false; // Disable update for API request
            } else {
                $this->tasa_dia->setFormValue($val);
            }
        }

        // Check field name 'monto_usd' first before field var 'x_monto_usd'
        $val = $CurrentForm->hasValue("monto_usd") ? $CurrentForm->getValue("monto_usd") : $CurrentForm->getValue("x_monto_usd");
        if (!$this->monto_usd->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->monto_usd->Visible = false; // Disable update for API request
            } else {
                $this->monto_usd->setFormValue($val);
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
        $this->proveedor->CurrentValue = $this->proveedor->FormValue;
        $this->monto_total->CurrentValue = $this->monto_total->FormValue;
        $this->alicuota_iva->CurrentValue = $this->alicuota_iva->FormValue;
        $this->iva->CurrentValue = $this->iva->FormValue;
        $this->total->CurrentValue = $this->total->FormValue;
        $this->documento->CurrentValue = $this->documento->FormValue;
        $this->doc_afectado->CurrentValue = $this->doc_afectado->FormValue;
        $this->nota->CurrentValue = $this->nota->FormValue;
        $this->estatus->CurrentValue = $this->estatus->FormValue;
        $this->moneda->CurrentValue = $this->moneda->FormValue;
        $this->aplica_retencion->CurrentValue = $this->aplica_retencion->FormValue;
        $this->ref_iva->CurrentValue = $this->ref_iva->FormValue;
        $this->ref_islr->CurrentValue = $this->ref_islr->FormValue;
        $this->ref_municipal->CurrentValue = $this->ref_municipal->FormValue;
        $this->fecha_registro_retenciones->CurrentValue = $this->fecha_registro_retenciones->FormValue;
        $this->fecha_registro_retenciones->CurrentValue = UnFormatDateTime($this->fecha_registro_retenciones->CurrentValue, 0);
        $this->tasa_dia->CurrentValue = $this->tasa_dia->FormValue;
        $this->monto_usd->CurrentValue = $this->monto_usd->FormValue;
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
        $row = [];
        $row['id'] = null;
        $row['tipo_documento'] = null;
        $row['nro_documento'] = null;
        $row['nro_control'] = null;
        $row['fecha'] = null;
        $row['proveedor'] = null;
        $row['almacen'] = null;
        $row['monto_total'] = null;
        $row['alicuota_iva'] = null;
        $row['iva'] = null;
        $row['total'] = null;
        $row['documento'] = null;
        $row['doc_afectado'] = null;
        $row['nota'] = null;
        $row['estatus'] = null;
        $row['username'] = null;
        $row['id_documento_padre'] = null;
        $row['moneda'] = null;
        $row['consignacion'] = null;
        $row['consignacion_reportada'] = null;
        $row['aplica_retencion'] = null;
        $row['ret_iva'] = null;
        $row['ref_iva'] = null;
        $row['ret_islr'] = null;
        $row['ref_islr'] = null;
        $row['ret_municipal'] = null;
        $row['ref_municipal'] = null;
        $row['monto_pagar'] = null;
        $row['comprobante'] = null;
        $row['tipo_iva'] = null;
        $row['tipo_islr'] = null;
        $row['sustraendo'] = null;
        $row['fecha_registro_retenciones'] = null;
        $row['tasa_dia'] = null;
        $row['monto_usd'] = null;
        $row['fecha_libro_compra'] = null;
        $row['tipo_municipal'] = null;
        $row['cerrado'] = null;
        $row['cliente'] = null;
        $row['descuento'] = null;
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
        if ($this->monto_usd->FormValue == $this->monto_usd->CurrentValue && is_numeric(ConvertToFloatString($this->monto_usd->CurrentValue))) {
            $this->monto_usd->CurrentValue = ConvertToFloatString($this->monto_usd->CurrentValue);
        }

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

            // ref_iva
            $this->ref_iva->ViewValue = $this->ref_iva->CurrentValue;
            $this->ref_iva->ViewCustomAttributes = "";

            // ref_islr
            $this->ref_islr->ViewValue = $this->ref_islr->CurrentValue;
            $this->ref_islr->ViewCustomAttributes = "";

            // ref_municipal
            $this->ref_municipal->ViewValue = $this->ref_municipal->CurrentValue;
            $this->ref_municipal->ViewCustomAttributes = "";

            // fecha_registro_retenciones
            $this->fecha_registro_retenciones->ViewValue = $this->fecha_registro_retenciones->CurrentValue;
            $this->fecha_registro_retenciones->ViewValue = FormatDateTime($this->fecha_registro_retenciones->ViewValue, 0);
            $this->fecha_registro_retenciones->ViewCustomAttributes = "";

            // tasa_dia
            $this->tasa_dia->ViewValue = $this->tasa_dia->CurrentValue;
            $this->tasa_dia->ViewValue = FormatNumber($this->tasa_dia->ViewValue, $this->tasa_dia->DefaultDecimalPrecision);
            $this->tasa_dia->ViewCustomAttributes = "";

            // monto_usd
            $this->monto_usd->ViewValue = $this->monto_usd->CurrentValue;
            $this->monto_usd->ViewValue = FormatNumber($this->monto_usd->ViewValue, $this->monto_usd->DefaultDecimalPrecision);
            $this->monto_usd->ViewCustomAttributes = "";

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

            // estatus
            $this->estatus->LinkCustomAttributes = "";
            $this->estatus->HrefValue = "";
            $this->estatus->TooltipValue = "";

            // moneda
            $this->moneda->LinkCustomAttributes = "";
            $this->moneda->HrefValue = "";
            $this->moneda->TooltipValue = "";

            // aplica_retencion
            $this->aplica_retencion->LinkCustomAttributes = "";
            $this->aplica_retencion->HrefValue = "";
            $this->aplica_retencion->TooltipValue = "";

            // ref_iva
            $this->ref_iva->LinkCustomAttributes = "";
            if (!EmptyValue($this->id->CurrentValue)) {
                $this->ref_iva->HrefValue = "reportes/rptRetencionE.php?Nretencion=" . $this->id->CurrentValue; // Add prefix/suffix
                $this->ref_iva->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->ref_iva->HrefValue = FullUrl($this->ref_iva->HrefValue, "href");
                }
            } else {
                $this->ref_iva->HrefValue = "";
            }
            $this->ref_iva->TooltipValue = "";

            // ref_islr
            $this->ref_islr->LinkCustomAttributes = "";
            if (!EmptyValue($this->id->CurrentValue)) {
                $this->ref_islr->HrefValue = "reportes/rptRetencionE2.php?Nretencion=" . $this->id->CurrentValue; // Add prefix/suffix
                $this->ref_islr->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->ref_islr->HrefValue = FullUrl($this->ref_islr->HrefValue, "href");
                }
            } else {
                $this->ref_islr->HrefValue = "";
            }
            $this->ref_islr->TooltipValue = "";

            // ref_municipal
            $this->ref_municipal->LinkCustomAttributes = "";
            $this->ref_municipal->HrefValue = "";
            $this->ref_municipal->TooltipValue = "";

            // fecha_registro_retenciones
            $this->fecha_registro_retenciones->LinkCustomAttributes = "";
            $this->fecha_registro_retenciones->HrefValue = "";
            $this->fecha_registro_retenciones->TooltipValue = "";

            // tasa_dia
            $this->tasa_dia->LinkCustomAttributes = "";
            $this->tasa_dia->HrefValue = "";
            $this->tasa_dia->TooltipValue = "";

            // monto_usd
            $this->monto_usd->LinkCustomAttributes = "";
            $this->monto_usd->HrefValue = "";
            $this->monto_usd->TooltipValue = "";

            // descuento
            $this->descuento->LinkCustomAttributes = "";
            $this->descuento->HrefValue = "";
            $this->descuento->TooltipValue = "";
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

            // proveedor
            $this->proveedor->EditAttrs["class"] = "form-control";
            $this->proveedor->EditCustomAttributes = "";
            $curVal = trim(strval($this->proveedor->CurrentValue));
            if ($curVal != "") {
                $this->proveedor->EditValue = $this->proveedor->lookupCacheOption($curVal);
                if ($this->proveedor->EditValue === null) { // Lookup from database
                    $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->proveedor->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->proveedor->Lookup->renderViewRow($rswrk[0]);
                        $this->proveedor->EditValue = $this->proveedor->displayValue($arwrk);
                    } else {
                        $this->proveedor->EditValue = $this->proveedor->CurrentValue;
                    }
                }
            } else {
                $this->proveedor->EditValue = null;
            }
            $this->proveedor->ViewCustomAttributes = "";

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

            // estatus
            $this->estatus->EditAttrs["class"] = "form-control";
            $this->estatus->EditCustomAttributes = "";
            $this->estatus->EditValue = $this->estatus->options(true);
            $this->estatus->PlaceHolder = RemoveHtml($this->estatus->caption());

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

            // aplica_retencion
            $this->aplica_retencion->EditCustomAttributes = "";
            $this->aplica_retencion->EditValue = $this->aplica_retencion->options(false);
            $this->aplica_retencion->PlaceHolder = RemoveHtml($this->aplica_retencion->caption());

            // ref_iva
            $this->ref_iva->EditAttrs["class"] = "form-control";
            $this->ref_iva->EditCustomAttributes = "";
            if (!$this->ref_iva->Raw) {
                $this->ref_iva->CurrentValue = HtmlDecode($this->ref_iva->CurrentValue);
            }
            $this->ref_iva->EditValue = HtmlEncode($this->ref_iva->CurrentValue);
            $this->ref_iva->PlaceHolder = RemoveHtml($this->ref_iva->caption());

            // ref_islr
            $this->ref_islr->EditAttrs["class"] = "form-control";
            $this->ref_islr->EditCustomAttributes = "";
            if (!$this->ref_islr->Raw) {
                $this->ref_islr->CurrentValue = HtmlDecode($this->ref_islr->CurrentValue);
            }
            $this->ref_islr->EditValue = HtmlEncode($this->ref_islr->CurrentValue);
            $this->ref_islr->PlaceHolder = RemoveHtml($this->ref_islr->caption());

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

            // tasa_dia
            $this->tasa_dia->EditAttrs["class"] = "form-control";
            $this->tasa_dia->EditCustomAttributes = "";
            $this->tasa_dia->EditValue = HtmlEncode($this->tasa_dia->CurrentValue);
            $this->tasa_dia->PlaceHolder = RemoveHtml($this->tasa_dia->caption());
            if (strval($this->tasa_dia->EditValue) != "" && is_numeric($this->tasa_dia->EditValue)) {
                $this->tasa_dia->EditValue = FormatNumber($this->tasa_dia->EditValue, -2, -1, -2, 0);
            }

            // monto_usd
            $this->monto_usd->EditAttrs["class"] = "form-control";
            $this->monto_usd->EditCustomAttributes = "";
            $this->monto_usd->EditValue = HtmlEncode($this->monto_usd->CurrentValue);
            $this->monto_usd->PlaceHolder = RemoveHtml($this->monto_usd->caption());
            if (strval($this->monto_usd->EditValue) != "" && is_numeric($this->monto_usd->EditValue)) {
                $this->monto_usd->EditValue = FormatNumber($this->monto_usd->EditValue, -2, -1, -2, 0);
            }

            // descuento
            $this->descuento->EditAttrs["class"] = "form-control";
            $this->descuento->EditCustomAttributes = "";
            $this->descuento->EditValue = HtmlEncode($this->descuento->CurrentValue);
            $this->descuento->PlaceHolder = RemoveHtml($this->descuento->caption());
            if (strval($this->descuento->EditValue) != "" && is_numeric($this->descuento->EditValue)) {
                $this->descuento->EditValue = FormatNumber($this->descuento->EditValue, -2, -2, -2, -2);
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

            // proveedor
            $this->proveedor->LinkCustomAttributes = "";
            $this->proveedor->HrefValue = "";
            $this->proveedor->TooltipValue = "";

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

            // documento
            $this->documento->LinkCustomAttributes = "";
            $this->documento->HrefValue = "";

            // doc_afectado
            $this->doc_afectado->LinkCustomAttributes = "";
            $this->doc_afectado->HrefValue = "";

            // nota
            $this->nota->LinkCustomAttributes = "";
            $this->nota->HrefValue = "";

            // estatus
            $this->estatus->LinkCustomAttributes = "";
            $this->estatus->HrefValue = "";

            // moneda
            $this->moneda->LinkCustomAttributes = "";
            $this->moneda->HrefValue = "";

            // aplica_retencion
            $this->aplica_retencion->LinkCustomAttributes = "";
            $this->aplica_retencion->HrefValue = "";

            // ref_iva
            $this->ref_iva->LinkCustomAttributes = "";
            if (!EmptyValue($this->id->CurrentValue)) {
                $this->ref_iva->HrefValue = "reportes/rptRetencionE.php?Nretencion=" . $this->id->CurrentValue; // Add prefix/suffix
                $this->ref_iva->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->ref_iva->HrefValue = FullUrl($this->ref_iva->HrefValue, "href");
                }
            } else {
                $this->ref_iva->HrefValue = "";
            }

            // ref_islr
            $this->ref_islr->LinkCustomAttributes = "";
            if (!EmptyValue($this->id->CurrentValue)) {
                $this->ref_islr->HrefValue = "reportes/rptRetencionE2.php?Nretencion=" . $this->id->CurrentValue; // Add prefix/suffix
                $this->ref_islr->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->ref_islr->HrefValue = FullUrl($this->ref_islr->HrefValue, "href");
                }
            } else {
                $this->ref_islr->HrefValue = "";
            }

            // ref_municipal
            $this->ref_municipal->LinkCustomAttributes = "";
            $this->ref_municipal->HrefValue = "";

            // fecha_registro_retenciones
            $this->fecha_registro_retenciones->LinkCustomAttributes = "";
            $this->fecha_registro_retenciones->HrefValue = "";

            // tasa_dia
            $this->tasa_dia->LinkCustomAttributes = "";
            $this->tasa_dia->HrefValue = "";

            // monto_usd
            $this->monto_usd->LinkCustomAttributes = "";
            $this->monto_usd->HrefValue = "";

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
        if ($this->estatus->Required) {
            if (!$this->estatus->IsDetailKey && EmptyValue($this->estatus->FormValue)) {
                $this->estatus->addErrorMessage(str_replace("%s", $this->estatus->caption(), $this->estatus->RequiredErrorMessage));
            }
        }
        if ($this->moneda->Required) {
            if (!$this->moneda->IsDetailKey && EmptyValue($this->moneda->FormValue)) {
                $this->moneda->addErrorMessage(str_replace("%s", $this->moneda->caption(), $this->moneda->RequiredErrorMessage));
            }
        }
        if ($this->aplica_retencion->Required) {
            if ($this->aplica_retencion->FormValue == "") {
                $this->aplica_retencion->addErrorMessage(str_replace("%s", $this->aplica_retencion->caption(), $this->aplica_retencion->RequiredErrorMessage));
            }
        }
        if ($this->ref_iva->Required) {
            if (!$this->ref_iva->IsDetailKey && EmptyValue($this->ref_iva->FormValue)) {
                $this->ref_iva->addErrorMessage(str_replace("%s", $this->ref_iva->caption(), $this->ref_iva->RequiredErrorMessage));
            }
        }
        if ($this->ref_islr->Required) {
            if (!$this->ref_islr->IsDetailKey && EmptyValue($this->ref_islr->FormValue)) {
                $this->ref_islr->addErrorMessage(str_replace("%s", $this->ref_islr->caption(), $this->ref_islr->RequiredErrorMessage));
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
        if ($this->tasa_dia->Required) {
            if (!$this->tasa_dia->IsDetailKey && EmptyValue($this->tasa_dia->FormValue)) {
                $this->tasa_dia->addErrorMessage(str_replace("%s", $this->tasa_dia->caption(), $this->tasa_dia->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->tasa_dia->FormValue)) {
            $this->tasa_dia->addErrorMessage($this->tasa_dia->getErrorMessage(false));
        }
        if ($this->monto_usd->Required) {
            if (!$this->monto_usd->IsDetailKey && EmptyValue($this->monto_usd->FormValue)) {
                $this->monto_usd->addErrorMessage(str_replace("%s", $this->monto_usd->caption(), $this->monto_usd->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->monto_usd->FormValue)) {
            $this->monto_usd->addErrorMessage($this->monto_usd->getErrorMessage(false));
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
        if (in_array("entradas_salidas", $detailTblVar) && $detailPage->DetailEdit) {
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

            // documento
            $this->documento->setDbValueDef($rsnew, $this->documento->CurrentValue, null, $this->documento->ReadOnly);

            // doc_afectado
            $this->doc_afectado->setDbValueDef($rsnew, $this->doc_afectado->CurrentValue, null, $this->doc_afectado->ReadOnly);

            // nota
            $this->nota->setDbValueDef($rsnew, $this->nota->CurrentValue, null, $this->nota->ReadOnly);

            // estatus
            $this->estatus->setDbValueDef($rsnew, $this->estatus->CurrentValue, null, $this->estatus->ReadOnly);

            // moneda
            $this->moneda->setDbValueDef($rsnew, $this->moneda->CurrentValue, null, $this->moneda->ReadOnly);

            // aplica_retencion
            $this->aplica_retencion->setDbValueDef($rsnew, $this->aplica_retencion->CurrentValue, null, $this->aplica_retencion->ReadOnly);

            // ref_iva
            $this->ref_iva->setDbValueDef($rsnew, $this->ref_iva->CurrentValue, null, $this->ref_iva->ReadOnly);

            // ref_islr
            $this->ref_islr->setDbValueDef($rsnew, $this->ref_islr->CurrentValue, null, $this->ref_islr->ReadOnly);

            // ref_municipal
            $this->ref_municipal->setDbValueDef($rsnew, $this->ref_municipal->CurrentValue, null, $this->ref_municipal->ReadOnly);

            // fecha_registro_retenciones
            $this->fecha_registro_retenciones->setDbValueDef($rsnew, UnFormatDateTime($this->fecha_registro_retenciones->CurrentValue, 0), null, $this->fecha_registro_retenciones->ReadOnly);

            // tasa_dia
            $this->tasa_dia->setDbValueDef($rsnew, $this->tasa_dia->CurrentValue, null, $this->tasa_dia->ReadOnly);

            // monto_usd
            $this->monto_usd->setDbValueDef($rsnew, $this->monto_usd->CurrentValue, null, $this->monto_usd->ReadOnly);

            // descuento
            $this->descuento->setDbValueDef($rsnew, $this->descuento->CurrentValue, null, $this->descuento->ReadOnly);

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
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("Home");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("EntradasList"), "", $this->TableVar, true);
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
    public function messageShowing(&$msg, $type) {
    	if ($type == 'success') {
    		$msg = ""; // "Copia Realizada Exitosamente";
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
    	$sql = "SELECT tipo_documento FROM entradas WHERE id = " . $this->id->CurrentValue;
    	$tipo = ExecuteScalar($sql);
    	$sql = "SELECT descripcion FROM tipo_documento WHERE codigo = '$tipo';";
    	$tipo_name = ExecuteScalar($sql);
    	$tipo_name = '<a href="EntradasList?tipo=' . $tipo . '">' . $tipo_name . '</a>';
    	$this->setTableCaption($tipo_name);
    	$this->ret_iva->Visible = FALSE;
    	$this->ref_iva->Visible = FALSE;
    	$this->ret_islr->Visible = FALSE;
    	$this->ref_islr->Visible = FALSE;
    	$this->ret_municipal->Visible = FALSE;
    	$this->ref_municipal->Visible = FALSE;
    	$this->nro_control->Visible = FALSE;
    	$this->documento->Visible = FALSE;
    	$this->tasa_dia->Visible = FALSE;
    	$this->monto_usd->Visible = FALSE;
    	$this->doc_afectado->Visible = FALSE;
    	$this->aplica_retencion->Visible = FALSE;
    	$this->cliente->Visible = FALSE;
    	$this->descuento->Visible = FALSE;
    	$tipo = $this->tipo_documento->CurrentValue;
    	switch($tipo) {
    	case "TDCPDC":
    		$this->fecha->ReadOnly = TRUE;
    		$this->monto_total->Visible = FALSE;
    		$this->alicuota_iva->Visible = FALSE;
    		$this->iva->Visible = FALSE;
    		$this->total->Visible = FALSE;
    		$this->id_documento_padre->Visible = FALSE;
    		$this->descuento->Visible = TRUE;
    		break;
    	case "TDCNRP":
    		$this->fecha->ReadOnly = TRUE;
    		$this->monto_total->Visible = FALSE;
    		$this->alicuota_iva->Visible = FALSE;
    		$this->iva->Visible = FALSE;
    		$this->total->Visible = FALSE;
    		$this->id_documento_padre->Visible = TRUE;
    		$this->descuento->Visible = TRUE;
    		break;
    	case "TDCFCC":
    		$this->id_documento_padre->Visible = FALSE;
    		$this->ref_iva->Visible = TRUE;
    		$this->ref_islr->Visible = TRUE;
    		$this->ref_municipal->Visible = TRUE;
    		$this->nro_control->Visible = TRUE;
    		$this->documento->Visible = TRUE;
    		$this->tasa_dia->Visible = TRUE;
    		$this->monto_usd->Visible = TRUE;
    		$this->doc_afectado->Visible = TRUE;
    		$this->aplica_retencion->Visible = TRUE;
    		$this->descuento->Visible = TRUE;
    		break;
    	case "TDCAEN":
    		$this->fecha->ReadOnly = TRUE;
    		$this->monto_total->Visible = FALSE;
    		$this->alicuota_iva->Visible = FALSE;
    		$this->iva->Visible = FALSE;
    		$this->total->Visible = FALSE;
    		$this->id_documento_padre->Visible = FALSE;
    		break;
    	}
    }

    // Page Data Rendering event
    public function pageDataRendering(&$header)
    {
        // Example:
        //$header = "your header";
    	$tipo = $this->tipo_documento->CurrentValue;
    	switch($tipo) {
    	case "TDCPDC":
    		$header .= '<div id="xSubTotal"></div>';
    		break;
    	case "TDCNRP":
    		$header .= '<div id="xSubTotal"></div>';
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
