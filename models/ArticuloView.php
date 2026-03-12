<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class ArticuloView extends Articulo
{
    use MessagesTrait;

    // Page ID
    public $PageID = "view";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'articulo';

    // Page object name
    public $PageObjName = "ArticuloView";

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

        // Table object (articulo)
        if (!isset($GLOBALS["articulo"]) || get_class($GLOBALS["articulo"]) == PROJECT_NAMESPACE . "articulo") {
            $GLOBALS["articulo"] = &$this;
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
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'articulo');
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
                $doc = new $class(Container("articulo"));
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
                    if ($pageName == "ArticuloView") {
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
        $this->codigo_ims->setVisibility();
        $this->codigo->setVisibility();
        $this->nombre_comercial->setVisibility();
        $this->principio_activo->setVisibility();
        $this->presentacion->setVisibility();
        $this->fabricante->setVisibility();
        $this->codigo_de_barra->setVisibility();
        $this->categoria->setVisibility();
        $this->lista_pedido->setVisibility();
        $this->unidad_medida_defecto->setVisibility();
        $this->cantidad_por_unidad_medida->setVisibility();
        $this->foto->setVisibility();
        $this->cantidad_minima->setVisibility();
        $this->cantidad_maxima->setVisibility();
        $this->cantidad_en_mano->setVisibility();
        $this->cantidad_en_pedido->setVisibility();
        $this->cantidad_en_transito->setVisibility();
        $this->ultimo_costo->setVisibility();
        $this->descuento->setVisibility();
        $this->precio->setVisibility();
        $this->precio2->setVisibility();
        $this->alicuota->setVisibility();
        $this->articulo_inventario->setVisibility();
        $this->activo->setVisibility();
        $this->puntos_ventas->setVisibility();
        $this->puntos_premio->setVisibility();
        $this->sincroniza->setVisibility();
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
        $this->setupLookupOptions($this->fabricante);
        $this->setupLookupOptions($this->categoria);
        $this->setupLookupOptions($this->lista_pedido);
        $this->setupLookupOptions($this->unidad_medida_defecto);
        $this->setupLookupOptions($this->cantidad_por_unidad_medida);
        $this->setupLookupOptions($this->alicuota);

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
                        $this->terminate("ArticuloList"); // Return to list page
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
                        $returnUrl = "ArticuloList"; // No matching record, return to list
                    } else {
                        $this->loadRowValues($this->Recordset); // Load row values
                    }
                    break;
            }
        } else {
            $returnUrl = "ArticuloList"; // Not page request, return to list
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

        // "detail_articulo_unidad_medida"
        $item = &$option->add("detail_articulo_unidad_medida");
        $body = $Language->phrase("ViewPageDetailLink") . $Language->TablePhrase("articulo_unidad_medida", "TblCaption");
        $body = "<a class=\"btn btn-default ew-row-link ew-detail\" data-action=\"list\" href=\"" . HtmlEncode(GetUrl("ArticuloUnidadMedidaList?" . Config("TABLE_SHOW_MASTER") . "=articulo&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue) . "")) . "\">" . $body . "</a>";
        $links = "";
        $detailPageObj = Container("ArticuloUnidadMedidaGrid");
        if ($detailPageObj->DetailView && $Security->canView() && $Security->allowView(CurrentProjectID() . 'articulo')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-view\" data-action=\"view\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailViewLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=articulo_unidad_medida"))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailViewLink")) . "</a></li>";
            if ($detailViewTblVar != "") {
                $detailViewTblVar .= ",";
            }
            $detailViewTblVar .= "articulo_unidad_medida";
        }
        if ($detailPageObj->DetailEdit && $Security->canEdit() && $Security->allowEdit(CurrentProjectID() . 'articulo')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-edit\" data-action=\"edit\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailEditLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=articulo_unidad_medida"))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailEditLink")) . "</a></li>";
            if ($detailEditTblVar != "") {
                $detailEditTblVar .= ",";
            }
            $detailEditTblVar .= "articulo_unidad_medida";
        }
        if ($links != "") {
            $body .= "<button class=\"dropdown-toggle btn btn-default ew-detail\" data-toggle=\"dropdown\"></button>";
            $body .= "<ul class=\"dropdown-menu\">" . $links . "</ul>";
        }
        $body = "<div class=\"btn-group btn-group-sm ew-btn-group\">" . $body . "</div>";
        $item->Body = $body;
        $item->Visible = $Security->allowList(CurrentProjectID() . 'articulo_unidad_medida');
        if ($item->Visible) {
            if ($detailTableLink != "") {
                $detailTableLink .= ",";
            }
            $detailTableLink .= "articulo_unidad_medida";
        }
        if ($this->ShowMultipleDetails) {
            $item->Visible = false;
        }

        // "detail_adjunto"
        $item = &$option->add("detail_adjunto");
        $body = $Language->phrase("ViewPageDetailLink") . $Language->TablePhrase("adjunto", "TblCaption");
        $body = "<a class=\"btn btn-default ew-row-link ew-detail\" data-action=\"list\" href=\"" . HtmlEncode(GetUrl("AdjuntoList?" . Config("TABLE_SHOW_MASTER") . "=articulo&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue) . "")) . "\">" . $body . "</a>";
        $links = "";
        $detailPageObj = Container("AdjuntoGrid");
        if ($detailPageObj->DetailView && $Security->canView() && $Security->allowView(CurrentProjectID() . 'articulo')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-view\" data-action=\"view\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailViewLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=adjunto"))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailViewLink")) . "</a></li>";
            if ($detailViewTblVar != "") {
                $detailViewTblVar .= ",";
            }
            $detailViewTblVar .= "adjunto";
        }
        if ($detailPageObj->DetailEdit && $Security->canEdit() && $Security->allowEdit(CurrentProjectID() . 'articulo')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-edit\" data-action=\"edit\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailEditLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=adjunto"))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailEditLink")) . "</a></li>";
            if ($detailEditTblVar != "") {
                $detailEditTblVar .= ",";
            }
            $detailEditTblVar .= "adjunto";
        }
        if ($links != "") {
            $body .= "<button class=\"dropdown-toggle btn btn-default ew-detail\" data-toggle=\"dropdown\"></button>";
            $body .= "<ul class=\"dropdown-menu\">" . $links . "</ul>";
        }
        $body = "<div class=\"btn-group btn-group-sm ew-btn-group\">" . $body . "</div>";
        $item->Body = $body;
        $item->Visible = $Security->allowList(CurrentProjectID() . 'adjunto');
        if ($item->Visible) {
            if ($detailTableLink != "") {
                $detailTableLink .= ",";
            }
            $detailTableLink .= "adjunto";
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
        $this->codigo_ims->setDbValue($row['codigo_ims']);
        $this->codigo->setDbValue($row['codigo']);
        $this->nombre_comercial->setDbValue($row['nombre_comercial']);
        $this->principio_activo->setDbValue($row['principio_activo']);
        $this->presentacion->setDbValue($row['presentacion']);
        $this->fabricante->setDbValue($row['fabricante']);
        $this->codigo_de_barra->setDbValue($row['codigo_de_barra']);
        $this->categoria->setDbValue($row['categoria']);
        $this->lista_pedido->setDbValue($row['lista_pedido']);
        $this->unidad_medida_defecto->setDbValue($row['unidad_medida_defecto']);
        $this->cantidad_por_unidad_medida->setDbValue($row['cantidad_por_unidad_medida']);
        $this->foto->Upload->DbValue = $row['foto'];
        $this->foto->setDbValue($this->foto->Upload->DbValue);
        $this->cantidad_minima->setDbValue($row['cantidad_minima']);
        $this->cantidad_maxima->setDbValue($row['cantidad_maxima']);
        $this->cantidad_en_mano->setDbValue($row['cantidad_en_mano']);
        $this->cantidad_en_pedido->setDbValue($row['cantidad_en_pedido']);
        $this->cantidad_en_transito->setDbValue($row['cantidad_en_transito']);
        $this->ultimo_costo->setDbValue($row['ultimo_costo']);
        $this->descuento->setDbValue($row['descuento']);
        $this->precio->setDbValue($row['precio']);
        $this->precio2->setDbValue($row['precio2']);
        $this->alicuota->setDbValue($row['alicuota']);
        $this->articulo_inventario->setDbValue($row['articulo_inventario']);
        $this->activo->setDbValue($row['activo']);
        $this->puntos_ventas->setDbValue($row['puntos_ventas']);
        $this->puntos_premio->setDbValue($row['puntos_premio']);
        $this->sincroniza->setDbValue($row['sincroniza']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['id'] = null;
        $row['codigo_ims'] = null;
        $row['codigo'] = null;
        $row['nombre_comercial'] = null;
        $row['principio_activo'] = null;
        $row['presentacion'] = null;
        $row['fabricante'] = null;
        $row['codigo_de_barra'] = null;
        $row['categoria'] = null;
        $row['lista_pedido'] = null;
        $row['unidad_medida_defecto'] = null;
        $row['cantidad_por_unidad_medida'] = null;
        $row['foto'] = null;
        $row['cantidad_minima'] = null;
        $row['cantidad_maxima'] = null;
        $row['cantidad_en_mano'] = null;
        $row['cantidad_en_pedido'] = null;
        $row['cantidad_en_transito'] = null;
        $row['ultimo_costo'] = null;
        $row['descuento'] = null;
        $row['precio'] = null;
        $row['precio2'] = null;
        $row['alicuota'] = null;
        $row['articulo_inventario'] = null;
        $row['activo'] = null;
        $row['puntos_ventas'] = null;
        $row['puntos_premio'] = null;
        $row['sincroniza'] = null;
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
        if ($this->cantidad_minima->FormValue == $this->cantidad_minima->CurrentValue && is_numeric(ConvertToFloatString($this->cantidad_minima->CurrentValue))) {
            $this->cantidad_minima->CurrentValue = ConvertToFloatString($this->cantidad_minima->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->cantidad_maxima->FormValue == $this->cantidad_maxima->CurrentValue && is_numeric(ConvertToFloatString($this->cantidad_maxima->CurrentValue))) {
            $this->cantidad_maxima->CurrentValue = ConvertToFloatString($this->cantidad_maxima->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->cantidad_en_mano->FormValue == $this->cantidad_en_mano->CurrentValue && is_numeric(ConvertToFloatString($this->cantidad_en_mano->CurrentValue))) {
            $this->cantidad_en_mano->CurrentValue = ConvertToFloatString($this->cantidad_en_mano->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->cantidad_en_pedido->FormValue == $this->cantidad_en_pedido->CurrentValue && is_numeric(ConvertToFloatString($this->cantidad_en_pedido->CurrentValue))) {
            $this->cantidad_en_pedido->CurrentValue = ConvertToFloatString($this->cantidad_en_pedido->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->cantidad_en_transito->FormValue == $this->cantidad_en_transito->CurrentValue && is_numeric(ConvertToFloatString($this->cantidad_en_transito->CurrentValue))) {
            $this->cantidad_en_transito->CurrentValue = ConvertToFloatString($this->cantidad_en_transito->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->ultimo_costo->FormValue == $this->ultimo_costo->CurrentValue && is_numeric(ConvertToFloatString($this->ultimo_costo->CurrentValue))) {
            $this->ultimo_costo->CurrentValue = ConvertToFloatString($this->ultimo_costo->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->descuento->FormValue == $this->descuento->CurrentValue && is_numeric(ConvertToFloatString($this->descuento->CurrentValue))) {
            $this->descuento->CurrentValue = ConvertToFloatString($this->descuento->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->precio->FormValue == $this->precio->CurrentValue && is_numeric(ConvertToFloatString($this->precio->CurrentValue))) {
            $this->precio->CurrentValue = ConvertToFloatString($this->precio->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->precio2->FormValue == $this->precio2->CurrentValue && is_numeric(ConvertToFloatString($this->precio2->CurrentValue))) {
            $this->precio2->CurrentValue = ConvertToFloatString($this->precio2->CurrentValue);
        }

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // id

        // codigo_ims

        // codigo

        // nombre_comercial

        // principio_activo

        // presentacion

        // fabricante

        // codigo_de_barra

        // categoria

        // lista_pedido

        // unidad_medida_defecto

        // cantidad_por_unidad_medida

        // foto

        // cantidad_minima

        // cantidad_maxima

        // cantidad_en_mano

        // cantidad_en_pedido

        // cantidad_en_transito

        // ultimo_costo

        // descuento

        // precio

        // precio2

        // alicuota

        // articulo_inventario

        // activo

        // puntos_ventas

        // puntos_premio

        // sincroniza
        if ($this->RowType == ROWTYPE_VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;
            $this->id->ViewCustomAttributes = "";

            // codigo_ims
            $this->codigo_ims->ViewValue = $this->codigo_ims->CurrentValue;
            $this->codigo_ims->CssClass = "font-weight-bold font-italic";
            $this->codigo_ims->ViewCustomAttributes = "";

            // codigo
            $this->codigo->ViewValue = $this->codigo->CurrentValue;
            $this->codigo->ViewCustomAttributes = "";

            // nombre_comercial
            $this->nombre_comercial->ViewValue = $this->nombre_comercial->CurrentValue;
            $this->nombre_comercial->ViewCustomAttributes = "";

            // principio_activo
            $this->principio_activo->ViewValue = $this->principio_activo->CurrentValue;
            $this->principio_activo->ViewCustomAttributes = "";

            // presentacion
            $this->presentacion->ViewValue = $this->presentacion->CurrentValue;
            $this->presentacion->ViewCustomAttributes = "";

            // fabricante
            $curVal = trim(strval($this->fabricante->CurrentValue));
            if ($curVal != "") {
                $this->fabricante->ViewValue = $this->fabricante->lookupCacheOption($curVal);
                if ($this->fabricante->ViewValue === null) { // Lookup from database
                    $filterWrk = "`Id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->fabricante->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->fabricante->Lookup->renderViewRow($rswrk[0]);
                        $this->fabricante->ViewValue = $this->fabricante->displayValue($arwrk);
                    } else {
                        $this->fabricante->ViewValue = $this->fabricante->CurrentValue;
                    }
                }
            } else {
                $this->fabricante->ViewValue = null;
            }
            $this->fabricante->ViewCustomAttributes = "";

            // codigo_de_barra
            $this->codigo_de_barra->ViewValue = $this->codigo_de_barra->CurrentValue;
            $this->codigo_de_barra->ViewCustomAttributes = "";

            // categoria
            $curVal = trim(strval($this->categoria->CurrentValue));
            if ($curVal != "") {
                $this->categoria->ViewValue = $this->categoria->lookupCacheOption($curVal);
                if ($this->categoria->ViewValue === null) { // Lookup from database
                    $filterWrk = "`campo_codigo`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $lookupFilter = function() {
                        return "`tabla` = 'CATEGORIA'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    $sqlWrk = $this->categoria->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->categoria->Lookup->renderViewRow($rswrk[0]);
                        $this->categoria->ViewValue = $this->categoria->displayValue($arwrk);
                    } else {
                        $this->categoria->ViewValue = $this->categoria->CurrentValue;
                    }
                }
            } else {
                $this->categoria->ViewValue = null;
            }
            $this->categoria->ViewCustomAttributes = "";

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

            // unidad_medida_defecto
            $curVal = trim(strval($this->unidad_medida_defecto->CurrentValue));
            if ($curVal != "") {
                $this->unidad_medida_defecto->ViewValue = $this->unidad_medida_defecto->lookupCacheOption($curVal);
                if ($this->unidad_medida_defecto->ViewValue === null) { // Lookup from database
                    $filterWrk = "`codigo`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $lookupFilter = function() {
                        return "`codigo` = 'UDM001'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    $sqlWrk = $this->unidad_medida_defecto->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->unidad_medida_defecto->Lookup->renderViewRow($rswrk[0]);
                        $this->unidad_medida_defecto->ViewValue = $this->unidad_medida_defecto->displayValue($arwrk);
                    } else {
                        $this->unidad_medida_defecto->ViewValue = $this->unidad_medida_defecto->CurrentValue;
                    }
                }
            } else {
                $this->unidad_medida_defecto->ViewValue = null;
            }
            $this->unidad_medida_defecto->ViewCustomAttributes = "";

            // cantidad_por_unidad_medida
            $curVal = trim(strval($this->cantidad_por_unidad_medida->CurrentValue));
            if ($curVal != "") {
                $this->cantidad_por_unidad_medida->ViewValue = $this->cantidad_por_unidad_medida->lookupCacheOption($curVal);
                if ($this->cantidad_por_unidad_medida->ViewValue === null) { // Lookup from database
                    $filterWrk = "`cantidad`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->cantidad_por_unidad_medida->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->cantidad_por_unidad_medida->Lookup->renderViewRow($rswrk[0]);
                        $this->cantidad_por_unidad_medida->ViewValue = $this->cantidad_por_unidad_medida->displayValue($arwrk);
                    } else {
                        $this->cantidad_por_unidad_medida->ViewValue = $this->cantidad_por_unidad_medida->CurrentValue;
                    }
                }
            } else {
                $this->cantidad_por_unidad_medida->ViewValue = null;
            }
            $this->cantidad_por_unidad_medida->ViewCustomAttributes = "";

            // foto
            if (!EmptyValue($this->foto->Upload->DbValue)) {
                $this->foto->ImageWidth = 120;
                $this->foto->ImageHeight = 120;
                $this->foto->ImageAlt = $this->foto->alt();
                $this->foto->ViewValue = $this->foto->Upload->DbValue;
            } else {
                $this->foto->ViewValue = "";
            }
            $this->foto->ViewCustomAttributes = "";

            // cantidad_minima
            $this->cantidad_minima->ViewValue = $this->cantidad_minima->CurrentValue;
            $this->cantidad_minima->ViewValue = FormatNumber($this->cantidad_minima->ViewValue, 2, -1, -1, -1);
            $this->cantidad_minima->ViewCustomAttributes = "";

            // cantidad_maxima
            $this->cantidad_maxima->ViewValue = $this->cantidad_maxima->CurrentValue;
            $this->cantidad_maxima->ViewValue = FormatNumber($this->cantidad_maxima->ViewValue, 2, -1, -1, -1);
            $this->cantidad_maxima->ViewCustomAttributes = "";

            // cantidad_en_mano
            $this->cantidad_en_mano->ViewValue = $this->cantidad_en_mano->CurrentValue;
            $this->cantidad_en_mano->ViewValue = FormatNumber($this->cantidad_en_mano->ViewValue, 2, -1, -1, -1);
            $this->cantidad_en_mano->ViewCustomAttributes = "";

            // cantidad_en_pedido
            $this->cantidad_en_pedido->ViewValue = $this->cantidad_en_pedido->CurrentValue;
            $this->cantidad_en_pedido->ViewValue = FormatNumber($this->cantidad_en_pedido->ViewValue, 2, -1, -1, -1);
            $this->cantidad_en_pedido->ViewCustomAttributes = "";

            // cantidad_en_transito
            $this->cantidad_en_transito->ViewValue = $this->cantidad_en_transito->CurrentValue;
            $this->cantidad_en_transito->ViewValue = FormatNumber($this->cantidad_en_transito->ViewValue, 2, -1, -1, -1);
            $this->cantidad_en_transito->ViewCustomAttributes = "";

            // ultimo_costo
            $this->ultimo_costo->ViewValue = $this->ultimo_costo->CurrentValue;
            $this->ultimo_costo->ViewValue = FormatNumber($this->ultimo_costo->ViewValue, 2, -1, -1, -1);
            $this->ultimo_costo->ViewCustomAttributes = "";

            // descuento
            $this->descuento->ViewValue = $this->descuento->CurrentValue;
            $this->descuento->ViewValue = FormatNumber($this->descuento->ViewValue, 2, -1, -1, -1);
            $this->descuento->ViewCustomAttributes = "";

            // precio
            $this->precio->ViewValue = $this->precio->CurrentValue;
            $this->precio->ViewValue = FormatNumber($this->precio->ViewValue, 2, -1, -1, -1);
            $this->precio->ViewCustomAttributes = "";

            // precio2
            $this->precio2->ViewValue = $this->precio2->CurrentValue;
            $this->precio2->ViewValue = FormatNumber($this->precio2->ViewValue, 2, -2, -2, -2);
            $this->precio2->ViewCustomAttributes = "";

            // alicuota
            $curVal = trim(strval($this->alicuota->CurrentValue));
            if ($curVal != "") {
                $this->alicuota->ViewValue = $this->alicuota->lookupCacheOption($curVal);
                if ($this->alicuota->ViewValue === null) { // Lookup from database
                    $filterWrk = "`codigo`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $lookupFilter = function() {
                        return "`activo` = 'S'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    $sqlWrk = $this->alicuota->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->alicuota->Lookup->renderViewRow($rswrk[0]);
                        $this->alicuota->ViewValue = $this->alicuota->displayValue($arwrk);
                    } else {
                        $this->alicuota->ViewValue = $this->alicuota->CurrentValue;
                    }
                }
            } else {
                $this->alicuota->ViewValue = null;
            }
            $this->alicuota->ViewCustomAttributes = "";

            // articulo_inventario
            if (strval($this->articulo_inventario->CurrentValue) != "") {
                $this->articulo_inventario->ViewValue = $this->articulo_inventario->optionCaption($this->articulo_inventario->CurrentValue);
            } else {
                $this->articulo_inventario->ViewValue = null;
            }
            $this->articulo_inventario->ViewCustomAttributes = "";

            // activo
            if (strval($this->activo->CurrentValue) != "") {
                $this->activo->ViewValue = $this->activo->optionCaption($this->activo->CurrentValue);
            } else {
                $this->activo->ViewValue = null;
            }
            $this->activo->ViewCustomAttributes = "";

            // puntos_ventas
            $this->puntos_ventas->ViewValue = $this->puntos_ventas->CurrentValue;
            $this->puntos_ventas->ViewValue = FormatNumber($this->puntos_ventas->ViewValue, 0, -2, -2, -2);
            $this->puntos_ventas->ViewCustomAttributes = "";

            // puntos_premio
            $this->puntos_premio->ViewValue = $this->puntos_premio->CurrentValue;
            $this->puntos_premio->ViewValue = FormatNumber($this->puntos_premio->ViewValue, 0, -2, -2, -2);
            $this->puntos_premio->ViewCustomAttributes = "";

            // sincroniza
            if (strval($this->sincroniza->CurrentValue) != "") {
                $this->sincroniza->ViewValue = $this->sincroniza->optionCaption($this->sincroniza->CurrentValue);
            } else {
                $this->sincroniza->ViewValue = null;
            }
            $this->sincroniza->ViewCustomAttributes = "";

            // codigo
            $this->codigo->LinkCustomAttributes = "";
            $this->codigo->HrefValue = "";
            $this->codigo->TooltipValue = "";

            // principio_activo
            $this->principio_activo->LinkCustomAttributes = "";
            $this->principio_activo->HrefValue = "";
            $this->principio_activo->TooltipValue = "";

            // fabricante
            $this->fabricante->LinkCustomAttributes = "";
            $this->fabricante->HrefValue = "";
            $this->fabricante->TooltipValue = "";

            // codigo_de_barra
            $this->codigo_de_barra->LinkCustomAttributes = "";
            $this->codigo_de_barra->HrefValue = "";
            $this->codigo_de_barra->TooltipValue = "";

            // unidad_medida_defecto
            $this->unidad_medida_defecto->LinkCustomAttributes = "";
            $this->unidad_medida_defecto->HrefValue = "";
            $this->unidad_medida_defecto->TooltipValue = "";

            // cantidad_por_unidad_medida
            $this->cantidad_por_unidad_medida->LinkCustomAttributes = "";
            $this->cantidad_por_unidad_medida->HrefValue = "";
            $this->cantidad_por_unidad_medida->TooltipValue = "";

            // foto
            $this->foto->LinkCustomAttributes = "";
            if (!EmptyValue($this->foto->Upload->DbValue)) {
                $this->foto->HrefValue = GetFileUploadUrl($this->foto, $this->foto->htmlDecode($this->foto->Upload->DbValue)); // Add prefix/suffix
                $this->foto->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->foto->HrefValue = FullUrl($this->foto->HrefValue, "href");
                }
            } else {
                $this->foto->HrefValue = "";
            }
            $this->foto->ExportHrefValue = $this->foto->UploadPath . $this->foto->Upload->DbValue;
            $this->foto->TooltipValue = "";
            if ($this->foto->UseColorbox) {
                if (EmptyValue($this->foto->TooltipValue)) {
                    $this->foto->LinkAttrs["title"] = $Language->phrase("ViewImageGallery");
                }
                $this->foto->LinkAttrs["data-rel"] = "articulo_x_foto";
                $this->foto->LinkAttrs->appendClass("ew-lightbox");
            }

            // cantidad_minima
            $this->cantidad_minima->LinkCustomAttributes = "";
            $this->cantidad_minima->HrefValue = "";
            $this->cantidad_minima->TooltipValue = "";

            // cantidad_maxima
            $this->cantidad_maxima->LinkCustomAttributes = "";
            $this->cantidad_maxima->HrefValue = "";
            $this->cantidad_maxima->TooltipValue = "";

            // cantidad_en_mano
            $this->cantidad_en_mano->LinkCustomAttributes = "";
            $this->cantidad_en_mano->HrefValue = "";
            $this->cantidad_en_mano->TooltipValue = "";

            // cantidad_en_pedido
            $this->cantidad_en_pedido->LinkCustomAttributes = "";
            $this->cantidad_en_pedido->HrefValue = "";
            $this->cantidad_en_pedido->TooltipValue = "";

            // cantidad_en_transito
            $this->cantidad_en_transito->LinkCustomAttributes = "";
            $this->cantidad_en_transito->HrefValue = "";
            $this->cantidad_en_transito->TooltipValue = "";

            // ultimo_costo
            $this->ultimo_costo->LinkCustomAttributes = "";
            $this->ultimo_costo->HrefValue = "";
            $this->ultimo_costo->TooltipValue = "";

            // descuento
            $this->descuento->LinkCustomAttributes = "";
            $this->descuento->HrefValue = "";
            $this->descuento->TooltipValue = "";

            // precio
            $this->precio->LinkCustomAttributes = "";
            $this->precio->HrefValue = "";
            $this->precio->TooltipValue = "";

            // precio2
            $this->precio2->LinkCustomAttributes = "";
            $this->precio2->HrefValue = "";
            $this->precio2->TooltipValue = "";

            // alicuota
            $this->alicuota->LinkCustomAttributes = "";
            $this->alicuota->HrefValue = "";
            $this->alicuota->TooltipValue = "";

            // articulo_inventario
            $this->articulo_inventario->LinkCustomAttributes = "";
            $this->articulo_inventario->HrefValue = "";
            $this->articulo_inventario->TooltipValue = "";

            // activo
            $this->activo->LinkCustomAttributes = "";
            $this->activo->HrefValue = "";
            $this->activo->TooltipValue = "";

            // puntos_ventas
            $this->puntos_ventas->LinkCustomAttributes = "";
            $this->puntos_ventas->HrefValue = "";
            $this->puntos_ventas->TooltipValue = "";

            // puntos_premio
            $this->puntos_premio->LinkCustomAttributes = "";
            $this->puntos_premio->HrefValue = "";
            $this->puntos_premio->TooltipValue = "";

            // sincroniza
            $this->sincroniza->LinkCustomAttributes = "";
            $this->sincroniza->HrefValue = "";
            $this->sincroniza->TooltipValue = "";
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
            if (in_array("articulo_unidad_medida", $detailTblVar)) {
                $detailPageObj = Container("ArticuloUnidadMedidaGrid");
                if ($detailPageObj->DetailView) {
                    $detailPageObj->CurrentMode = "view";

                    // Save current master table to detail table
                    $detailPageObj->setCurrentMasterTable($this->TableVar);
                    $detailPageObj->setStartRecordNumber(1);
                    $detailPageObj->articulo->IsDetailKey = true;
                    $detailPageObj->articulo->CurrentValue = $this->id->CurrentValue;
                    $detailPageObj->articulo->setSessionValue($detailPageObj->articulo->CurrentValue);
                }
            }
            if (in_array("adjunto", $detailTblVar)) {
                $detailPageObj = Container("AdjuntoGrid");
                if ($detailPageObj->DetailView) {
                    $detailPageObj->CurrentMode = "view";

                    // Save current master table to detail table
                    $detailPageObj->setCurrentMasterTable($this->TableVar);
                    $detailPageObj->setStartRecordNumber(1);
                    $detailPageObj->articulo->IsDetailKey = true;
                    $detailPageObj->articulo->CurrentValue = $this->id->CurrentValue;
                    $detailPageObj->articulo->setSessionValue($detailPageObj->articulo->CurrentValue);
                    $detailPageObj->cliente->setSessionValue(""); // Clear session key
                    $detailPageObj->proveedor->setSessionValue(""); // Clear session key
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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("ArticuloList"), "", $this->TableVar, true);
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
                case "x_presentacion":
                    break;
                case "x_fabricante":
                    break;
                case "x_categoria":
                    $lookupFilter = function () {
                        return "`tabla` = 'CATEGORIA'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    break;
                case "x_lista_pedido":
                    $lookupFilter = function () {
                        return "`tabla` = 'LISTA_PEDIDO'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    break;
                case "x_unidad_medida_defecto":
                    $lookupFilter = function () {
                        return "`codigo` = 'UDM001'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    break;
                case "x_cantidad_por_unidad_medida":
                    break;
                case "x_alicuota":
                    $lookupFilter = function () {
                        return "`activo` = 'S'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    break;
                case "x_articulo_inventario":
                    break;
                case "x_activo":
                    break;
                case "x_sincroniza":
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
    public function pageDataRendering(&$header) {
    	// Example:
    	//$header = "your header";
    	$sql = "SELECT valor1 AS moneda FROM parametro WHERE codigo = '006' AND valor2 = 'default';";
    	$moneda = ExecuteScalar($sql);
    	$desc = floatval($this->descuento->CurrentValue);
    	$sql = "SELECT tasa FROM tasa_usd WHERE moneda = 'USD' ORDER BY id DESC LIMIT 0, 1;";
    	$tasa = ExecuteScalar($sql);
    	$sql = "SELECT 
    				COUNT(a.nombre) AS tarifas  
    			FROM
    				tarifa AS a 
    				INNER JOIN tarifa_articulo AS b ON b.tarifa = a.id 
    			WHERE 
    				b.articulo = " . $this->id->CurrentValue . ";";
    	$tarifas = ExecuteScalar($sql);
    	$header .= '<div class="row">
    				<div class="col-sm-9 col-md-6">
    				<div class="list-group">
    				  <a class="list-group-item active">
    				      Precio art&iacute;culo seg&uacute;n tarifa - <strong>Tasa ' . number_format($tasa, 2, ",", ".") . ' Bs.</strong>
    				  </a>';
    	for($i=0; $i<$tarifas; $i++) {
    		$sql = "SELECT 
    				a.nombre AS tarifa, 
    				b.precio AS precio_ful,
    				(b.precio - (b.precio * ($desc/100))) AS precio 
    			FROM
    				tarifa AS a 
    				INNER JOIN tarifa_articulo AS b ON b.tarifa = a.id 
    			WHERE 
    				b.articulo = " . $this->id->CurrentValue . "
    			LIMIT $i, 1;";
    		$row = ExecuteRow($sql);
    		if(substr(strtoupper($moneda), 0, 2) == "BS") {
    			if($desc > 0)
    				$header .= '<a class="list-group-item">' . $row["tarifa"]  . ': ' . number_format($row["precio_ful"], 2, ".", ",") . ' ' . $moneda . ' <strong><i>Descuento ' . round($desc, 2) . '% Total: ' . number_format($row["precio"], 2, ".", ",") . ' ' . $moneda . ' -  </i>' . number_format($row["precio"]/$tasa, 2, ".", ",") . ' USD</strong></a>';
    			else
    				$header .= '<a class="list-group-item">' . $row["tarifa"]  . ': ' . number_format($row["precio"], 2, ".", ",") . ' ' . $moneda . ' ' . number_format($row["precio"]/$tasa, 2, ".", ",") . ' USD</strong></a>';
    		}
    		else {
    			if($desc > 0)
    				$header .= '<a class="list-group-item">' . $row["tarifa"]  . ': ' . number_format($row["precio_ful"], 2, ".", ",") . ' ' . $moneda . ' <strong><i>Descuento ' . round($desc, 2) . '% Total: ' . number_format($row["precio"], 2, ".", ",") . ' ' . $moneda . ' -  </i>' . number_format($row["precio"]*$tasa, 2, ".", ",") . ' Bs.</strong></a>';
    			else
    				$header .= '<a class="list-group-item">' . $row["tarifa"]  . ': ' . number_format($row["precio"], 2, ".", ",") . ' ' . $moneda . ' ' . number_format($row["precio"]*$tasa, 2, ".", ",") . ' Bs.</strong></a>';
    		}
    	}
    	$header .= '</div></div></div>';
    }

    // Page Data Rendered event
    public function pageDataRendered(&$footer) {
    	// Example:
    	// Listos lo lotes disponibles de el artículo
    	$sql = "SELECT 
    				COUNT(a.id) AS cantidad 
    			FROM 
    				entradas_salidas AS a 
    				JOIN entradas AS b ON
    					b.tipo_documento = a.tipo_documento
    					AND b.id = a.id_documento 
    				JOIN almacen AS c ON
    					c.codigo = a.almacen AND c.movimiento = 'S' 
    				LEFT OUTER JOIN (
    						SELECT 
    							a.id_compra AS id, SUM(IFNULL(a.cantidad_movimiento, 0)) AS cantidad_movimiento 
    						FROM 
    							entradas_salidas AS a 
    							JOIN salidas AS b ON
    								b.tipo_documento = a.tipo_documento
    								AND b.id = a.id_documento 
    							LEFT OUTER JOIN almacen AS c ON
    								c.codigo = a.almacen AND c.movimiento = 'S'
    						WHERE
    							a.tipo_documento IN ('TDCNET','TDCASA') 
    							AND b.estatus IN ('NUEVO', 'PROCESADO') AND a.articulo = '" . $this->id->CurrentValue . "' 
    						GROUP BY a.id_compra
    					) AS d ON d.id = a.id 
    			WHERE
    				((a.tipo_documento IN ('TDCNRP','TDCAEN') 
    				AND b.estatus = 'PROCESADO')
    				 OR
    				(a.tipo_documento = 'TDCNRP' AND b.consignacion = 'S'
    				AND b.estatus = 'NUEVO')) AND a.articulo = '" . $this->id->CurrentValue . "' 
    				AND (IFNULL(a.cantidad_movimiento, 0) + IFNULL(d.cantidad_movimiento, 0)) > 0;";
    	$lotes = ExecuteScalar($sql);
    	$footer .= '<div class="row">
    				<div class="col-sm-9 col-md-6">
    				<div class="list-group">
    				  <a class="list-group-item active">
    				      Existencia por lote
    				  </a>';
    	for($i=0; $i<$lotes; $i++) {
    		$sql = "SELECT 
    				a.id, a.lote, date_format(a.fecha_vencimiento, '%d/%m/%Y') AS fecha_vencimiento, 
    				(IFNULL(a.cantidad_movimiento, 0) + IFNULL(d.cantidad_movimiento, 0)) AS cantidad,
    				a.id_documento ,a.tipo_documento
    			FROM 
    				entradas_salidas AS a 
    				JOIN entradas AS b ON
    					b.tipo_documento = a.tipo_documento
    					AND b.id = a.id_documento 
    				JOIN almacen AS c ON
    					c.codigo = a.almacen AND c.movimiento = 'S' 
    				LEFT OUTER JOIN (
    						SELECT 
    							a.id_compra AS id, SUM(IFNULL(a.cantidad_movimiento, 0)) AS cantidad_movimiento 
    						FROM 
    							entradas_salidas AS a 
    							JOIN salidas AS b ON
    								b.tipo_documento = a.tipo_documento
    								AND b.id = a.id_documento 
    							LEFT OUTER JOIN almacen AS c ON
    								c.codigo = a.almacen AND c.movimiento = 'S'
    						WHERE
    							a.tipo_documento IN ('TDCNET','TDCASA') 
    							AND b.estatus IN ('NUEVO', 'PROCESADO') AND a.articulo = '" . $this->id->CurrentValue . "' 
    						GROUP BY a.id_compra
    					) AS d ON d.id = a.id 
    			WHERE
    				((a.tipo_documento IN ('TDCNRP','TDCAEN') 
    				AND b.estatus = 'PROCESADO')
    				 OR
    				(a.tipo_documento = 'TDCNRP' AND b.consignacion = 'S'
    				AND b.estatus = 'NUEVO')) AND a.articulo = '" . $this->id->CurrentValue . "' 
    				AND (IFNULL(a.cantidad_movimiento, 0) + IFNULL(d.cantidad_movimiento, 0)) > 0 
    			ORDER BY a.fecha_vencimiento ASC, 1 LIMIT $i, 1;"; 
    		$row = ExecuteRow($sql);
    		$footer .= '<a target="_blank" href="../EntradasView?showdetail=entradas_salidas&id=' . $row["id_documento"]  . '&tipo=' . $row["tipo_documento"]  . '" class="list-group-item">Lote: ' . $row["lote"]  . ' Fecha Vencimiento: ' . $row["fecha_vencimiento"] . ' Cantidad: ' . $row["cantidad"] . '</a>';
    	}
    	$footer .= '</div></div></div><br>';
    	$footer .= '<div class="row">
    				<div class="col-sm-9 col-md-6">
    				<div class="list-group">
    				  <a class="list-group-item active">
    				      Ultimas 10 entradas y/o compras
    				  </a>';
    	$sql = "SELECT
    				COUNT(c.descripcion) AS entradas  
    			FROM 
    				entradas AS a 
    				JOIN entradas_salidas AS b ON 
    				b.tipo_documento = a.tipo_documento AND b.id_documento = a.id 
    				JOIN tipo_documento AS c ON c.codigo = a.tipo_documento 
    			WHERE 
    				((a.tipo_documento IN ('TDCAEN', 'TDCNRP')
    				AND a.estatus = 'PROCESADO') OR
    				(a.tipo_documento = 'TDCNRP' AND a.consignacion = 'S'
    				AND a.estatus = 'NUEVO'))
    				AND b.articulo = '" . $this->id->CurrentValue . "';";
    	$entradas = ExecuteScalar($sql);
    	for($i=0; $i<$entradas; $i++) {
    		$sql = "SELECT
    				c.descripcion AS tipo, date_format(a.fecha, '%d/%m/%Y') AS fecha,
    				b.lote, date_format(b.fecha_vencimiento, '%d/%m/%Y') AS fecha_vencimiento, 
    				b.cantidad_movimiento AS cantidad, a.id, a.tipo_documento  
    			FROM 
    				entradas AS a 
    				JOIN entradas_salidas AS b ON 
    				b.tipo_documento = a.tipo_documento AND b.id_documento = a.id 
    				JOIN tipo_documento AS c ON c.codigo = a.tipo_documento 
    			WHERE 
    				((a.tipo_documento IN ('TDCAEN', 'TDCNRP')
    				AND a.estatus = 'PROCESADO') OR
    				(a.tipo_documento = 'TDCNRP' AND a.consignacion = 'S'
    				AND a.estatus = 'NUEVO'))
    				AND b.articulo = '" . $this->id->CurrentValue . "' 
    			ORDER BY a.fecha DESC LIMIT $i, 1;";
    		$row = ExecuteRow($sql);
    		$footer .= '<a target="_blank" href="../EntradasView?showdetail=entradas_salidas&id=' . $row["id"]  . '&tipo=' . $row["tipo_documento"]  . '" class="list-group-item">' . $row["tipo"]  . ' ' . $row["fecha"] . ' Lote: ' . $row["lote"]  . ' Venc.: ' . $row["fecha_vencimiento"] . ' Cant: ' . $row["cantidad"] . '</a>';
    	}
    	$footer .= '</div></div></div><br>';

    	// Articulo en pedido de ventas en estatus NUEVO
    	$sql = "SELECT 
    	  			COUNT(b.id) AS cantidad  
    	  		FROM 
    	  			entradas_salidas AS a 
    	  			JOIN salidas AS b ON
    	  				b.tipo_documento = a.tipo_documento
    	  				AND b.id = a.id_documento 
    	  			JOIN almacen AS c ON
    	  				c.codigo = a.almacen AND c.movimiento = 'S'
    	  		WHERE
    	  			a.tipo_documento IN ('TDCPDV')
    	  			AND a.articulo = '" . $this->id->CurrentValue . "' AND b.estatus = 'NUEVO' 
    	  		ORDER BY b.id DESC;";
    	$pedidos = ExecuteScalar($sql);
    	$footer .= '<div class="row">
    				<div class="col-sm-9 col-md-6">
    				<div class="list-group">
    				  <a class="list-group-item active">
    				      Pedidos de Venta en estatus NUEVO
    				  </a>';
    	for($i=0; $i<$pedidos; $i++) {
    		$sql = "SELECT 
    	  			b.id, b.nro_documento, date_format(b.fecha, '%d/%m/%Y') AS fecha, a.cantidad_movimiento, 
    	  			(SELECT descripcion FROM tipo_documento WHERE codigo = b.tipo_documento) AS tipo,
    	  			a.tipo_documento 
    	  		FROM 
    	  			entradas_salidas AS a 
    	  			JOIN salidas AS b ON
    	  				b.tipo_documento = a.tipo_documento
    	  				AND b.id = a.id_documento 
    	  			JOIN almacen AS c ON
    	  				c.codigo = a.almacen AND c.movimiento = 'S'
    	  		WHERE
    	  			a.tipo_documento IN ('TDCPDV')
    	  			AND a.articulo = '" . $this->id->CurrentValue . "' AND b.estatus = 'NUEVO' 
    	  		ORDER BY b.id DESC LIMIT $i, 1;";
    		$row = ExecuteRow($sql);
    		$footer .= '<a target="_blank" href="../SalidasView?showdetail=entradas_salidas&id=' . $row["id"]  . '&tipo=' . $row["tipo_documento"]  . '" class="list-group-item">' . $row["tipo"]  . ' ' . $row["fecha"] . ' Nro. Documento: ' . $row["nro_documento"]  . ' Cant: ' . $row["cantidad_movimiento"] . '</a>';
    	}
    	$footer .= '</div></div></div><br>';

    	// Articulo en Transito; factura de entrada en estatus NUEVO
    	$sql = "SELECT 
    	  			COUNT(b.id) AS cantidad  
    	  		FROM 
    	  			entradas_salidas AS a 
    	  			JOIN entradas AS b ON
    	  				b.tipo_documento = a.tipo_documento
    	  				AND b.id = a.id_documento 
    	  			JOIN almacen AS c ON
    	  				c.codigo = a.almacen AND c.movimiento = 'S'
    	  		WHERE
    	  			a.tipo_documento IN ('TDCFCC')
    	  			AND a.articulo = '" . $this->id->CurrentValue . "' AND b.estatus = 'NUEVO'
    	  		ORDER BY b.id DESC;";
    	$pedidos = ExecuteScalar($sql);
    	$footer .= '<div class="row">
    				<div class="col-sm-9 col-md-6">
    				<div class="list-group">
    				  <a class="list-group-item active">
    				      En Transito
    				  </a>';
    	for($i=0; $i<$pedidos; $i++) {
    		$sql = "SELECT 
    	  			b.id, b.nro_documento, date_format(b.fecha, '%d/%m/%Y') AS fecha, a.cantidad_movimiento, 
    	  			(SELECT descripcion FROM tipo_documento WHERE codigo = b.tipo_documento) AS tipo,
    	  			a.tipo_documento 
    	  		FROM 
    	  			entradas_salidas AS a 
    	  			JOIN entradas AS b ON
    	  				b.tipo_documento = a.tipo_documento
    	  				AND b.id = a.id_documento 
    	  			JOIN almacen AS c ON
    	  				c.codigo = a.almacen AND c.movimiento = 'S'
    	  		WHERE
    	  			a.tipo_documento IN ('TDCFCC')
    	  			AND a.articulo = '" . $this->id->CurrentValue . "' AND b.estatus = 'NUEVO'
    	  		ORDER BY b.id DESC LIMIT $i, 1;";
    		$row = ExecuteRow($sql);
    		$footer .= '<a target="_blank" href="../EntradasView?showdetail=entradas_salidas&id=' . $row["id"]  . '&tipo=' . $row["tipo_documento"]  . '" class="list-group-item">' . $row["tipo"]  . ' ' . $row["fecha"] . ' Nro. Documento: ' . $row["nro_documento"]  . ' Cant: ' . $row["cantidad_movimiento"] . '</a>';
    	}
    	$footer .= '</div></div></div><br>';
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
