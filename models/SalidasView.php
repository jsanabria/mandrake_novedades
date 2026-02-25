<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class SalidasView extends Salidas
{
    use MessagesTrait;

    // Page ID
    public $PageID = "view";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'salidas';

    // Page object name
    public $PageObjName = "SalidasView";

    // Rendering View
    public $RenderingView = false;

    // Page URLs
    public $AddUrl;
    public $EditUrl;
    public $CopyUrl;
    public $DeleteUrl;
    public $ViewUrl;
    public $ListUrl;

    // Export URLs
    public $ExportPrintUrl;
    public $ExportHtmlUrl;
    public $ExportExcelUrl;
    public $ExportWordUrl;
    public $ExportXmlUrl;
    public $ExportCsvUrl;
    public $ExportPdfUrl;

    // Custom export
    public $ExportExcelCustom = false;
    public $ExportWordCustom = false;
    public $ExportPdfCustom = false;
    public $ExportEmailCustom = false;

    // Update URLs
    public $InlineAddUrl;
    public $InlineCopyUrl;
    public $InlineEditUrl;
    public $GridAddUrl;
    public $GridEditUrl;
    public $MultiDeleteUrl;
    public $MultiUpdateUrl;

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
        if (($keyValue = Get("id") ?? Route("id")) !== null) {
            $this->RecKey["id"] = $keyValue;
        }
        $this->ExportPrintUrl = $pageUrl . "export=print";
        $this->ExportHtmlUrl = $pageUrl . "export=html";
        $this->ExportExcelUrl = $pageUrl . "export=excel";
        $this->ExportWordUrl = $pageUrl . "export=word";
        $this->ExportXmlUrl = $pageUrl . "export=xml";
        $this->ExportCsvUrl = $pageUrl . "export=csv";
        $this->ExportPdfUrl = $pageUrl . "export=pdf";

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

        // Export options
        $this->ExportOptions = new ListOptions("div");
        $this->ExportOptions->TagClassName = "ew-export-option";

        // Other options
        if (!$this->OtherOptions) {
            $this->OtherOptions = new ListOptionsArray();
        }
        $this->OtherOptions["action"] = new ListOptions("div");
        $this->OtherOptions["action"]->TagClassName = "ew-action-option";
        $this->OtherOptions["detail"] = new ListOptions("div");
        $this->OtherOptions["detail"]->TagClassName = "ew-detail-option";
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
    public $ExportOptions; // Export options
    public $OtherOptions; // Other options
    public $DisplayRecords = 1;
    public $DbMasterFilter;
    public $DbDetailFilter;
    public $StartRecord;
    public $StopRecord;
    public $TotalRecords = 0;
    public $RecordRange = 10;
    public $RecKey = [];
    public $IsModal = false;

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
        $this->CurrentAction = Param("action"); // Set up current action
        $this->id->setVisibility();
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
        $this->monto_usd->setVisibility();
        $this->lista_pedido->setVisibility();
        $this->nota->setVisibility();
        $this->_username->setVisibility();
        $this->estatus->setVisibility();
        $this->id_documento_padre->setVisibility();
        $this->asesor->setVisibility();
        $this->dias_credito->setVisibility();
        $this->entregado->setVisibility();
        $this->fecha_entrega->setVisibility();
        $this->pagado->setVisibility();
        $this->bultos->setVisibility();
        $this->fecha_bultos->setVisibility();
        $this->user_bultos->setVisibility();
        $this->fecha_despacho->setVisibility();
        $this->user_despacho->setVisibility();
        $this->consignacion->setVisibility();
        $this->unidades->setVisibility();
        $this->descuento->setVisibility();
        $this->monto_sin_descuento->setVisibility();
        $this->factura->setVisibility();
        $this->ci_rif->setVisibility();
        $this->nombre->setVisibility();
        $this->direccion->setVisibility();
        $this->telefono->setVisibility();
        $this->_email->setVisibility();
        $this->activo->setVisibility();
        $this->comprobante->setVisibility();
        $this->nro_despacho->setVisibility();
        $this->cerrado->setVisibility();
        $this->impreso->setVisibility();
        $this->igtf->setVisibility();
        $this->monto_base_igtf->setVisibility();
        $this->monto_igtf->setVisibility();
        $this->pago_premio->setVisibility();
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

        // Load current record
        $loadCurrentRecord = false;
        $returnUrl = "";
        $matchRecord = false;
        if ($this->isPageRequest()) { // Validate request
            if (($keyValue = Get("id") ?? Route("id")) !== null) {
                $this->id->setQueryStringValue($keyValue);
                $this->RecKey["id"] = $this->id->QueryStringValue;
            } elseif (Post("id") !== null) {
                $this->id->setFormValue(Post("id"));
                $this->RecKey["id"] = $this->id->FormValue;
            } elseif (IsApi() && ($keyValue = Key(0) ?? Route(2)) !== null) {
                $this->id->setQueryStringValue($keyValue);
                $this->RecKey["id"] = $this->id->QueryStringValue;
            } else {
                $loadCurrentRecord = true;
            }

            // Get action
            $this->CurrentAction = "show"; // Display
            switch ($this->CurrentAction) {
                case "show": // Get a record to display
                    $this->StartRecord = 1; // Initialize start position
                    if ($this->Recordset = $this->loadRecordset()) { // Load records
                        $this->TotalRecords = $this->Recordset->recordCount(); // Get record count
                    }
                    if ($this->TotalRecords <= 0) { // No record found
                        if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "") {
                            $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                        }
                        $this->terminate("SalidasList"); // Return to list page
                        return;
                    } elseif ($loadCurrentRecord) { // Load current record position
                        $this->setupStartRecord(); // Set up start record position
                        // Point to current record
                        if ($this->StartRecord <= $this->TotalRecords) {
                            $matchRecord = true;
                            $this->Recordset->move($this->StartRecord - 1);
                        }
                    } else { // Match key values
                        while (!$this->Recordset->EOF) {
                            if (SameString($this->id->CurrentValue, $this->Recordset->fields['id'])) {
                                $this->setStartRecordNumber($this->StartRecord); // Save record position
                                $matchRecord = true;
                                break;
                            } else {
                                $this->StartRecord++;
                                $this->Recordset->moveNext();
                            }
                        }
                    }
                    if (!$matchRecord) {
                        if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "") {
                            $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                        }
                        $returnUrl = "SalidasList"; // No matching record, return to list
                    } else {
                        $this->loadRowValues($this->Recordset); // Load row values
                    }
                    break;
            }
        } else {
            $returnUrl = "SalidasList"; // Not page request, return to list
        }
        if ($returnUrl != "") {
            $this->terminate($returnUrl);
            return;
        }

        // Set up Breadcrumb
        if (!$this->isExport()) {
            $this->setupBreadcrumb();
        }

        // Render row
        $this->RowType = ROWTYPE_VIEW;
        $this->resetAttributes();
        $this->renderRow();

        // Set up detail parameters
        $this->setupDetailParms();

        // Normal return
        if (IsApi()) {
            $rows = $this->getRecordsFromRecordset($this->Recordset, true); // Get current record only
            $this->Recordset->close();
            WriteJson(["success" => true, $this->TableVar => $rows]);
            $this->terminate(true);
            return;
        }

        // Set up pager
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

    // Set up other options
    protected function setupOtherOptions()
    {
        global $Language, $Security;
        $options = &$this->OtherOptions;
        $option = $options["action"];

        // Add
        $item = &$option->add("add");
        $addcaption = HtmlTitle($Language->phrase("ViewPageAddLink"));
        if ($this->IsModal) {
            $item->Body = "<a class=\"ew-action ew-add\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"#\" onclick=\"return ew.modalDialogShow({lnk:this,url:'" . HtmlEncode(GetUrl($this->AddUrl)) . "'});\">" . $Language->phrase("ViewPageAddLink") . "</a>";
        } else {
            $item->Body = "<a class=\"ew-action ew-add\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . HtmlEncode(GetUrl($this->AddUrl)) . "\">" . $Language->phrase("ViewPageAddLink") . "</a>";
        }
        $item->Visible = ($this->AddUrl != "" && $Security->canAdd());

        // Edit
        $item = &$option->add("edit");
        $editcaption = HtmlTitle($Language->phrase("ViewPageEditLink"));
        if ($this->IsModal) {
            $item->Body = "<a class=\"ew-action ew-edit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"#\" onclick=\"return ew.modalDialogShow({lnk:this,url:'" . HtmlEncode(GetUrl($this->EditUrl)) . "'});\">" . $Language->phrase("ViewPageEditLink") . "</a>";
        } else {
            $item->Body = "<a class=\"ew-action ew-edit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . HtmlEncode(GetUrl($this->EditUrl)) . "\">" . $Language->phrase("ViewPageEditLink") . "</a>";
        }
        $item->Visible = ($this->EditUrl != "" && $Security->canEdit());

        // Copy
        $item = &$option->add("copy");
        $copycaption = HtmlTitle($Language->phrase("ViewPageCopyLink"));
        if ($this->IsModal) {
            $item->Body = "<a class=\"ew-action ew-copy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"#\" onclick=\"return ew.modalDialogShow({lnk:this,btn:'AddBtn',url:'" . HtmlEncode(GetUrl($this->CopyUrl)) . "'});\">" . $Language->phrase("ViewPageCopyLink") . "</a>";
        } else {
            $item->Body = "<a class=\"ew-action ew-copy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . HtmlEncode(GetUrl($this->CopyUrl)) . "\">" . $Language->phrase("ViewPageCopyLink") . "</a>";
        }
        $item->Visible = ($this->CopyUrl != "" && $Security->canAdd());

        // Delete
        $item = &$option->add("delete");
        if ($this->IsModal) { // Handle as inline delete
            $item->Body = "<a onclick=\"return ew.confirmDelete(this);\" class=\"ew-action ew-delete\" title=\"" . HtmlTitle($Language->phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("ViewPageDeleteLink")) . "\" href=\"" . HtmlEncode(UrlAddQuery(GetUrl($this->DeleteUrl), "action=1")) . "\">" . $Language->phrase("ViewPageDeleteLink") . "</a>";
        } else {
            $item->Body = "<a class=\"ew-action ew-delete\" title=\"" . HtmlTitle($Language->phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("ViewPageDeleteLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->DeleteUrl)) . "\">" . $Language->phrase("ViewPageDeleteLink") . "</a>";
        }
        $item->Visible = ($this->DeleteUrl != "" && $Security->canDelete());
        $option = $options["detail"];
        $detailTableLink = "";
        $detailViewTblVar = "";
        $detailCopyTblVar = "";
        $detailEditTblVar = "";

        // "detail_entradas_salidas"
        $item = &$option->add("detail_entradas_salidas");
        $body = $Language->phrase("ViewPageDetailLink") . $Language->TablePhrase("entradas_salidas", "TblCaption");
        $body = "<a class=\"btn btn-default ew-row-link ew-detail\" data-action=\"list\" href=\"" . HtmlEncode(GetUrl("EntradasSalidasList?" . Config("TABLE_SHOW_MASTER") . "=salidas&" . GetForeignKeyUrl("fk_tipo_documento", $this->tipo_documento->CurrentValue) . "&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue) . "")) . "\">" . $body . "</a>";
        $links = "";
        $detailPageObj = Container("EntradasSalidasGrid");
        if ($detailPageObj->DetailView && $Security->canView() && $Security->allowView(CurrentProjectID() . 'salidas')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-view\" data-action=\"view\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailViewLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=entradas_salidas"))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailViewLink")) . "</a></li>";
            if ($detailViewTblVar != "") {
                $detailViewTblVar .= ",";
            }
            $detailViewTblVar .= "entradas_salidas";
        }
        if ($detailPageObj->DetailEdit && $Security->canEdit() && $Security->allowEdit(CurrentProjectID() . 'salidas')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-edit\" data-action=\"edit\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailEditLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=entradas_salidas"))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailEditLink")) . "</a></li>";
            if ($detailEditTblVar != "") {
                $detailEditTblVar .= ",";
            }
            $detailEditTblVar .= "entradas_salidas";
        }
        if ($detailPageObj->DetailAdd && $Security->canAdd() && $Security->allowAdd(CurrentProjectID() . 'salidas')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-copy\" data-action=\"add\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailCopyLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getCopyUrl(Config("TABLE_SHOW_DETAIL") . "=entradas_salidas"))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailCopyLink")) . "</a></li>";
            if ($detailCopyTblVar != "") {
                $detailCopyTblVar .= ",";
            }
            $detailCopyTblVar .= "entradas_salidas";
        }
        if ($links != "") {
            $body .= "<button class=\"dropdown-toggle btn btn-default ew-detail\" data-toggle=\"dropdown\"></button>";
            $body .= "<ul class=\"dropdown-menu\">" . $links . "</ul>";
        }
        $body = "<div class=\"btn-group btn-group-sm ew-btn-group\">" . $body . "</div>";
        $item->Body = $body;
        $item->Visible = $Security->allowList(CurrentProjectID() . 'entradas_salidas');
        if ($item->Visible) {
            if ($detailTableLink != "") {
                $detailTableLink .= ",";
            }
            $detailTableLink .= "entradas_salidas";
        }
        if ($this->ShowMultipleDetails) {
            $item->Visible = false;
        }

        // "detail_pagos"
        $item = &$option->add("detail_pagos");
        $body = $Language->phrase("ViewPageDetailLink") . $Language->TablePhrase("pagos", "TblCaption");
        $body = "<a class=\"btn btn-default ew-row-link ew-detail\" data-action=\"list\" href=\"" . HtmlEncode(GetUrl("PagosList?" . Config("TABLE_SHOW_MASTER") . "=salidas&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue) . "&" . GetForeignKeyUrl("fk_tipo_documento", $this->tipo_documento->CurrentValue) . "")) . "\">" . $body . "</a>";
        $links = "";
        $detailPageObj = Container("PagosGrid");
        if ($detailPageObj->DetailView && $Security->canView() && $Security->allowView(CurrentProjectID() . 'salidas')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-view\" data-action=\"view\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailViewLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=pagos"))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailViewLink")) . "</a></li>";
            if ($detailViewTblVar != "") {
                $detailViewTblVar .= ",";
            }
            $detailViewTblVar .= "pagos";
        }
        if ($detailPageObj->DetailEdit && $Security->canEdit() && $Security->allowEdit(CurrentProjectID() . 'salidas')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-edit\" data-action=\"edit\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailEditLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=pagos"))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailEditLink")) . "</a></li>";
            if ($detailEditTblVar != "") {
                $detailEditTblVar .= ",";
            }
            $detailEditTblVar .= "pagos";
        }
        if ($detailPageObj->DetailAdd && $Security->canAdd() && $Security->allowAdd(CurrentProjectID() . 'salidas')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-copy\" data-action=\"add\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailCopyLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getCopyUrl(Config("TABLE_SHOW_DETAIL") . "=pagos"))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailCopyLink")) . "</a></li>";
            if ($detailCopyTblVar != "") {
                $detailCopyTblVar .= ",";
            }
            $detailCopyTblVar .= "pagos";
        }
        if ($links != "") {
            $body .= "<button class=\"dropdown-toggle btn btn-default ew-detail\" data-toggle=\"dropdown\"></button>";
            $body .= "<ul class=\"dropdown-menu\">" . $links . "</ul>";
        }
        $body = "<div class=\"btn-group btn-group-sm ew-btn-group\">" . $body . "</div>";
        $item->Body = $body;
        $item->Visible = $Security->allowList(CurrentProjectID() . 'pagos');
        if ($item->Visible) {
            if ($detailTableLink != "") {
                $detailTableLink .= ",";
            }
            $detailTableLink .= "pagos";
        }
        if ($this->ShowMultipleDetails) {
            $item->Visible = false;
        }

        // Multiple details
        if ($this->ShowMultipleDetails) {
            $body = "<div class=\"btn-group btn-group-sm ew-btn-group\">";
            $links = "";
            if ($detailViewTblVar != "") {
                $links .= "<li><a class=\"ew-row-link ew-detail-view\" data-action=\"view\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailViewLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=" . $detailViewTblVar))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailViewLink")) . "</a></li>";
            }
            if ($detailEditTblVar != "") {
                $links .= "<li><a class=\"ew-row-link ew-detail-edit\" data-action=\"edit\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailEditLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=" . $detailEditTblVar))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailEditLink")) . "</a></li>";
            }
            if ($detailCopyTblVar != "") {
                $links .= "<li><a class=\"ew-row-link ew-detail-copy\" data-action=\"add\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailCopyLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getCopyUrl(Config("TABLE_SHOW_DETAIL") . "=" . $detailCopyTblVar))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailCopyLink")) . "</a></li>";
            }
            if ($links != "") {
                $body .= "<button class=\"dropdown-toggle btn btn-default ew-master-detail\" title=\"" . HtmlTitle($Language->phrase("MultipleMasterDetails")) . "\" data-toggle=\"dropdown\">" . $Language->phrase("MultipleMasterDetails") . "</button>";
                $body .= "<ul class=\"dropdown-menu ew-menu\">" . $links . "</ul>";
            }
            $body .= "</div>";
            // Multiple details
            $item = &$option->add("details");
            $item->Body = $body;
        }

        // Set up detail default
        $option = $options["detail"];
        $options["detail"]->DropDownButtonPhrase = $Language->phrase("ButtonDetails");
        $ar = explode(",", $detailTableLink);
        $cnt = count($ar);
        $option->UseDropDownButton = ($cnt > 1);
        $option->UseButtonGroup = true;
        $item = &$option->add($option->GroupOptionName);
        $item->Body = "";
        $item->Visible = false;

        // Set up action default
        $option = $options["action"];
        $option->DropDownButtonPhrase = $Language->phrase("ButtonActions");
        $option->UseDropDownButton = false;
        $option->UseButtonGroup = true;
        $item = &$option->add($option->GroupOptionName);
        $item->Body = "";
        $item->Visible = false;
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
        if ($this->AuditTrailOnView) {
            $this->writeAuditTrailOnView($row);
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

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs
        $this->AddUrl = $this->getAddUrl();
        $this->EditUrl = $this->getEditUrl();
        $this->CopyUrl = $this->getCopyUrl();
        $this->DeleteUrl = $this->getDeleteUrl();
        $this->ListUrl = $this->getListUrl();
        $this->setupOtherOptions();

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
        if ($this->monto_base_igtf->FormValue == $this->monto_base_igtf->CurrentValue && is_numeric(ConvertToFloatString($this->monto_base_igtf->CurrentValue))) {
            $this->monto_base_igtf->CurrentValue = ConvertToFloatString($this->monto_base_igtf->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->monto_igtf->FormValue == $this->monto_igtf->CurrentValue && is_numeric(ConvertToFloatString($this->monto_igtf->CurrentValue))) {
            $this->monto_igtf->CurrentValue = ConvertToFloatString($this->monto_igtf->CurrentValue);
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

            // monto_usd
            $this->monto_usd->ViewValue = $this->monto_usd->CurrentValue;
            $this->monto_usd->ViewValue = FormatNumber($this->monto_usd->ViewValue, 2, -1, -1, -1);
            $this->monto_usd->ViewCustomAttributes = "";

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

            // fecha_entrega
            $this->fecha_entrega->ViewValue = $this->fecha_entrega->CurrentValue;
            $this->fecha_entrega->ViewValue = FormatDateTime($this->fecha_entrega->ViewValue, 7);
            $this->fecha_entrega->ViewCustomAttributes = "";

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

            // monto_usd
            $this->monto_usd->LinkCustomAttributes = "";
            $this->monto_usd->HrefValue = "";
            $this->monto_usd->TooltipValue = "";

            // lista_pedido
            $this->lista_pedido->LinkCustomAttributes = "";
            $this->lista_pedido->HrefValue = "";
            $this->lista_pedido->TooltipValue = "";

            // username
            $this->_username->LinkCustomAttributes = "";
            $this->_username->HrefValue = "";
            $this->_username->TooltipValue = "";

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

            // fecha_entrega
            $this->fecha_entrega->LinkCustomAttributes = "";
            $this->fecha_entrega->HrefValue = "";
            $this->fecha_entrega->TooltipValue = "";

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

            // monto_igtf
            $this->monto_igtf->LinkCustomAttributes = "";
            $this->monto_igtf->HrefValue = "";
            $this->monto_igtf->TooltipValue = "";

            // pago_premio
            $this->pago_premio->LinkCustomAttributes = "";
            $this->pago_premio->HrefValue = "";
            $this->pago_premio->TooltipValue = "";
        }

        // Call Row Rendered event
        if ($this->RowType != ROWTYPE_AGGREGATEINIT) {
            $this->rowRendered();
        }
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
                if ($detailPageObj->DetailView) {
                    $detailPageObj->CurrentMode = "view";

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
                if ($detailPageObj->DetailView) {
                    $detailPageObj->CurrentMode = "view";

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
        $pageId = "view";
        $Breadcrumb->add("view", $pageId, $url);
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
        //Log("Page Load");
        if(isset($_REQUEST["tipo"])) { 
       		$tipo = $_REQUEST["tipo"];
       		$sql = "SELECT descripcion FROM tipo_documento WHERE codigo = '$tipo';";
       		$tipo_name = ExecuteScalar($sql);
       		$tipo_name = '<a href="../SalidasList?tipo=' . $tipo . '">' . $tipo_name . '</a>';
       		$this->setTableCaption($tipo_name);
        }
        else header("Location: Home");
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
    	$this->doc_afectado->Visible = FALSE;
    	$this->fecha_entrega->Visible = FALSE;
    	$this->nro_despacho->Visible = FALSE;
    	$this->impreso->Visible = FALSE;
    	$this->asesor->Visible = FALSE;
    	$this->ci_rif->Visible = FALSE;
    	$tipo = $_REQUEST["tipo"];
    	switch($tipo) {
    	case "TDCPDV":
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
    		$this->lista_pedido->Visible = TRUE;
    		break;
    	case "TDCNET":
    		$this->descuento->Visible = TRUE;
    		$this->nro_control->Visible = FALSE;
    		$this->monto_total->Visible = FALSE;
    		$this->alicuota_iva->Visible = FALSE;
    		$this->iva->Visible = FALSE;
    		$this->total->Visible = TRUE;
    		$this->id_documento_padre->Visible = TRUE;
    		$this->tasa_dia->Visible = TRUE;
    		$this->monto_usd->Visible = FALSE;
    		$this->moneda->Visible = FALSE;
    		$this->documento->Visible = FALSE;
    		$this->dias_credito->Visible = FALSE;
    		$this->entregado->Visible = FALSE;
    		$this->fecha_entrega->Visible = FALSE;
    		$this->pagado->Visible = FALSE;
    		$this->lista_pedido->Visible = FALSE;
    		$this->fecha_despacho->Visible = TRUE;
    		$this->impreso->Visible = TRUE;
    		$this->asesor->Visible = TRUE;
    		$this->ci_rif->Visible = TRUE;
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
    		$this->doc_afectado->Visible = TRUE;
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
    	if($this->tipo_documento->CurrentValue == "TDCFCV") {
    		if($this->comprobante->CurrentValue == "") {
    			if($this->estatus->CurrentValue == "PROCESADO") {
    				/*
    				$header = '<div id="result" class="container">
    					<buttom class="btn btn-success" id="cmbImpFact" name="cmbImpFact"><span class="fas fa-print"></span> Imprimir Factura</buttom>
    				</div>';
    				*/
    			}
    			else {
    				$header = '<div class="alert alert-success" role="alert">
    							Ya se le Gener&oacute; Comprobante Contable a este Documento
    						</div>';
    			}
    		}
    		$header = '<div id="result" class="container">
    					<buttom class="btn btn-success" id="cmbImpFact" name="cmbImpFact"><span class="fas fa-print"></span> Imprimir Factura</buttom>
    				</div>';
    		$total = $this->total->CurrentValue;
    		$monto_base_igtf = $this->monto_base_igtf->CurrentValue;
    		$igtf = $this->monto_igtf->CurrentValue;
    		$header .= '<br><div class="alert alert-success text-right" role="alert"><b>';
    		$header .= 'Total Factura Bs.: ' . number_format($total, 2, ".", ",") . "<br>";
    		$header .= 'Monto IGTF sobre Bs. ' . number_format($monto_base_igtf, 2, ".", ",") . ' Bs.: ' . number_format($igtf, 2, ".", ",") . "<br>";
    		$header .= 'Total a Pagar Bs.: ' . number_format(($total+$igtf), 2, ".", ",") . "<br>";
    		$header .= '</b></div>';
    	}
    	if($this->factura->CurrentValue == "S") {
    		echo '<a class="btn btn-primary" target="_blank" href="reportes/factura_ajuste_de_salida.php?id=' . $this->id->CurrentValue . '&tipo=' . $this->tipo_documento->CurrentValue . '"><span class="fas fa-print"></span> Factura</a>';
    	}
    	$cliente = intval($this->cliente->CurrentValue);
    	if($this->tipo_documento->CurrentValue == "TDCNET" and $this->estatus->CurrentValue != "ANULADO") {
    	   	$url = "../SalidasEdit?showdetail=entradas_salidas&id=" . $this->id->CurrentValue . "&tipo=" . $this->tipo_documento->CurrentValue . "";
    		$header .= '<a class="btn btn-primary" href="' . $url . '" >Editar Nota de Entrega</a>
    				&nbsp&nbsp';
    		if($this->estatus->CurrentValue == "PROCESADO") {				
    			$url = "../reportes/nota_de_entrega.php?&id=" . $this->id->CurrentValue . "&tipo=" . $this->tipo_documento->CurrentValue . "&CurrentUserName=" . CurrentUserName() . "";
    			/*if($this->estatus->CurrentValue == "NUEVO")
    				$header .= '<a class="btn btn-primary" onclick="js: return confirm(\'Seguro de Imprimir la Nota de Entrega?. Si la imprime se procesará la misma y no se podrá editar.\') ? true : false;" href="' . $url . '" target="_blank" >Imprimir Nota de Entrega</a>&nbsp&nbsp';
    			else*/
    				$header .= '<a class="btn btn-primary" href="' . $url . '" target="_blank" ><span class="fas fa-print"></span> Nota de Entrega</a>&nbsp&nbsp';
    				$url = "../reportes/nota_de_entrega_ticket.php?&id=" . $this->id->CurrentValue . "&tipo=" . $this->tipo_documento->CurrentValue . "&CurrentUserName=" . CurrentUserName() . "";
    				$header .= '<a class="btn btn-primary" href="' . $url . '" target="_blank" ><span class="fas fa-map"></span> Ticket Nota de Entrega</a>&nbsp&nbsp';
    		}
    		$sql = "SELECT id FROM cobros_cliente WHERE id_documento = " . $this->id->CurrentValue . ";";
    		if($row = ExecuteRow($sql)) {
    			$url = "../CobrosClienteDetalleList?showmaster=cobros_cliente&fk_id=" . $row["id"] . "";
    			$header .= '<a class="btn btn-primary" href="' . $url . '">Ver Pago(s)</a>&nbsp&nbsp';
    		}
    		else {
    			$dsc = intval($this->descuento->CurrentValue);
    			$cashea = $this->ci_rif->CurrentValue;
    			$url = "../CobrosClienteAdd?showdetail=&id_compra=" . $this->id->CurrentValue . "&dsc=$dsc&" . "cashea=$cashea";
    			$header .= '<a class="btn btn-primary" href="' . $url . '">Registrar Pago</a>&nbsp&nbsp';
    		}
    		$sql = "SELECT c.id
    				FROM cobros_cliente AS a JOIN recarga AS b ON b.cobro_cliente_reverso = a.id
    					JOIN abono AS c ON c.id = b.abono 
    				WHERE a.id_documento = " . $this->id->CurrentValue . ";";
    		$rows = ExecuteRows($sql);
    		foreach ($rows as $key => $value) {
    			$url = "../reportes/rptRecibo.php?id=" . $value["id"] . "";
    			$header .= '<a class="btn btn-primary" target="_blank" href="' . $url . '"><span class="fas fa-print"></span> Recibo de Abono en Bs # ' . $value["id"] . '</a>&nbsp&nbsp';
    		}
    		$sql = "SELECT c.id
    				FROM cobros_cliente AS a JOIN recarga2 AS b ON b.cobro_cliente_reverso = a.id
    					JOIN abono2 AS c ON c.id = b.abono 
    				WHERE a.id_documento = " . $this->id->CurrentValue . ";";
    		$rows = ExecuteRows($sql);
    		foreach ($rows as $key => $value) {
    			$url = "../reportes/rptRecibo2.php?id=" . $value["id"] . "";
    			$header .= '<a class="btn btn-primary" target="_blank" href="' . $url . '"><span class="fas fa-print"></span> Recibo de Abono en USD # ' . $value["id"] . '</a>&nbsp&nbsp';
    		}
        	$cliente = CurrentPage()->cliente->CurrentValue;
        	$sql = "SELECT nombre FROM cliente WHERE id = $cliente;";
        	$NombreCliente = ExecuteScalar($sql);
        	$sql = "SELECT saldo FROM recarga WHERE cliente = $cliente ORDER BY id DESC LIMIT 0, 1;";
        	$saldoBs = floatval(ExecuteScalar($sql));
        	$sql = "SELECT saldo FROM recarga2 WHERE cliente = $cliente ORDER BY id DESC LIMIT 0, 1;";
        	$saldoUSD = floatval(ExecuteScalar($sql));
        	$saldo = $saldoBs + $saldoUSD;
        	if($saldo > 0.00) {
        		$header .= '<div class="alert alert-success">
        			<strong>Saldo Cliente ' . $NombreCliente . ': </strong> USD ' . number_format($saldo, 2, ",", ".") . '.
        			 ($ ' . number_format($saldoUSD, 2, ",", ".") . ' | Bs. ' . number_format($saldoBs, 2, ",", ".") . ') 
        			</div>';
        	}
        	else {
        		$header .= '<div class="alert alert-danger">
        			<strong>Saldo Cliente ' . $NombreCliente . ': </strong> USD ' . number_format($saldo, 2, ",", ".") . '.
        			</div>';
        	}
    	}
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

    // Page Exporting event
    // $this->ExportDoc = export document object
    public function pageExporting()
    {
        //$this->ExportDoc->Text = "my header"; // Export header
        //return false; // Return false to skip default export and use Row_Export event
        return true; // Return true to use default export and skip Row_Export event
    }

    // Row Export event
    // $this->ExportDoc = export document object
    public function rowExport($rs)
    {
        //$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
    }

    // Page Exported event
    // $this->ExportDoc = export document object
    public function pageExported()
    {
        //$this->ExportDoc->Text .= "my footer"; // Export footer
        //Log($this->ExportDoc->Text);
    }
}
