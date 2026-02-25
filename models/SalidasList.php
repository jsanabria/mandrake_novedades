<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class SalidasList extends Salidas
{
    use MessagesTrait;

    // Page ID
    public $PageID = "list";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'salidas';

    // Page object name
    public $PageObjName = "SalidasList";

    // Rendering View
    public $RenderingView = false;

    // Grid form hidden field names
    public $FormName = "fsalidaslist";
    public $FormActionName = "k_action";
    public $FormBlankRowName = "k_blankrow";
    public $FormKeyCountName = "key_count";

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

        // Initialize URLs
        $this->ExportPrintUrl = $pageUrl . "export=print";
        $this->ExportExcelUrl = $pageUrl . "export=excel";
        $this->ExportWordUrl = $pageUrl . "export=word";
        $this->ExportPdfUrl = $pageUrl . "export=pdf";
        $this->ExportHtmlUrl = $pageUrl . "export=html";
        $this->ExportXmlUrl = $pageUrl . "export=xml";
        $this->ExportCsvUrl = $pageUrl . "export=csv";
        $this->AddUrl = "SalidasAdd?" . Config("TABLE_SHOW_DETAIL") . "=";
        $this->InlineAddUrl = $pageUrl . "action=add";
        $this->GridAddUrl = $pageUrl . "action=gridadd";
        $this->GridEditUrl = $pageUrl . "action=gridedit";
        $this->MultiDeleteUrl = "SalidasDelete";
        $this->MultiUpdateUrl = "SalidasUpdate";

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

        // List options
        $this->ListOptions = new ListOptions();
        $this->ListOptions->TableVar = $this->TableVar;

        // Export options
        $this->ExportOptions = new ListOptions("div");
        $this->ExportOptions->TagClassName = "ew-export-option";

        // Import options
        $this->ImportOptions = new ListOptions("div");
        $this->ImportOptions->TagClassName = "ew-import-option";

        // Other options
        if (!$this->OtherOptions) {
            $this->OtherOptions = new ListOptionsArray();
        }
        $this->OtherOptions["addedit"] = new ListOptions("div");
        $this->OtherOptions["addedit"]->TagClassName = "ew-add-edit-option";
        $this->OtherOptions["detail"] = new ListOptions("div");
        $this->OtherOptions["detail"]->TagClassName = "ew-detail-option";
        $this->OtherOptions["action"] = new ListOptions("div");
        $this->OtherOptions["action"]->TagClassName = "ew-action-option";

        // Filter options
        $this->FilterOptions = new ListOptions("div");
        $this->FilterOptions->TagClassName = "ew-filter-option fsalidaslistsrch";

        // List actions
        $this->ListActions = new ListActions();
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
            SaveDebugMessage();
            Redirect(GetUrl($url));
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
                        if ($fld->DataType == DATATYPE_MEMO && $fld->MemoMaxLength > 0) {
                            $val = TruncateMemo($val, $fld->MemoMaxLength, $fld->TruncateMemoRemoveHtml);
                        }
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

    // Class variables
    public $ListOptions; // List options
    public $ExportOptions; // Export options
    public $SearchOptions; // Search options
    public $OtherOptions; // Other options
    public $FilterOptions; // Filter options
    public $ImportOptions; // Import options
    public $ListActions; // List actions
    public $SelectedCount = 0;
    public $SelectedIndex = 0;
    public $DisplayRecords = 20;
    public $StartRecord;
    public $StopRecord;
    public $TotalRecords = 0;
    public $RecordRange = 10;
    public $PageSizes = ""; // Page sizes (comma separated)
    public $DefaultSearchWhere = ""; // Default search WHERE clause
    public $SearchWhere = ""; // Search WHERE clause
    public $SearchPanelClass = "ew-search-panel collapse"; // Search Panel class
    public $SearchRowCount = 0; // For extended search
    public $SearchColumnCount = 0; // For extended search
    public $SearchFieldsPerRow = 1; // For extended search
    public $RecordCount = 0; // Record count
    public $EditRowCount;
    public $StartRowCount = 1;
    public $RowCount = 0;
    public $Attrs = []; // Row attributes and cell attributes
    public $RowIndex = 0; // Row index
    public $KeyCount = 0; // Key count
    public $RowAction = ""; // Row action
    public $MultiColumnClass = "col-sm";
    public $MultiColumnEditClass = "w-100";
    public $DbMasterFilter = ""; // Master filter
    public $DbDetailFilter = ""; // Detail filter
    public $MasterRecordExists;
    public $MultiSelectKey;
    public $Command;
    public $RestoreSearch = false;
    public $HashValue; // Hash value
    public $DetailPages;
    public $OldRecordset;

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $CustomExportType, $ExportFileName, $UserProfile, $Language, $Security, $CurrentForm;

        // Get export parameters
        $custom = "";
        if (Param("export") !== null) {
            $this->Export = Param("export");
            $custom = Param("custom", "");
        } elseif (IsPost()) {
            if (Post("exporttype") !== null) {
                $this->Export = Post("exporttype");
            }
            $custom = Post("custom", "");
        } elseif (Get("cmd") == "json") {
            $this->Export = Get("cmd");
        } else {
            $this->setExportReturnUrl(CurrentUrl());
        }
        $ExportFileName = $this->TableVar; // Get export file, used in header

        // Get custom export parameters
        if ($this->isExport() && $custom != "") {
            $this->CustomExport = $this->Export;
            $this->Export = "print";
        }
        $CustomExportType = $this->CustomExport;
        $ExportType = $this->Export; // Get export parameter, used in header

        // Update Export URLs
        if (Config("USE_PHPEXCEL")) {
            $this->ExportExcelCustom = false;
        }
        if (Config("USE_PHPWORD")) {
            $this->ExportWordCustom = false;
        }
        if ($this->ExportExcelCustom) {
            $this->ExportExcelUrl .= "&amp;custom=1";
        }
        if ($this->ExportWordCustom) {
            $this->ExportWordUrl .= "&amp;custom=1";
        }
        if ($this->ExportPdfCustom) {
            $this->ExportPdfUrl .= "&amp;custom=1";
        }
        $this->CurrentAction = Param("action"); // Set up current action

        // Get grid add count
        $gridaddcnt = Get(Config("TABLE_GRID_ADD_ROW_COUNT"), "");
        if (is_numeric($gridaddcnt) && $gridaddcnt > 0) {
            $this->GridAddRowCount = $gridaddcnt;
        }

        // Set up list options
        $this->setupListOptions();

        // Setup export options
        $this->setupExportOptions();
        $this->id->Visible = false;
        $this->tipo_documento->setVisibility();
        $this->nro_documento->setVisibility();
        $this->nro_control->Visible = false;
        $this->fecha->setVisibility();
        $this->cliente->setVisibility();
        $this->documento->setVisibility();
        $this->doc_afectado->setVisibility();
        $this->moneda->Visible = false;
        $this->monto_total->setVisibility();
        $this->alicuota_iva->setVisibility();
        $this->iva->setVisibility();
        $this->total->setVisibility();
        $this->tasa_dia->Visible = false;
        $this->monto_usd->Visible = false;
        $this->lista_pedido->setVisibility();
        $this->nota->Visible = false;
        $this->_username->setVisibility();
        $this->estatus->setVisibility();
        $this->id_documento_padre->Visible = false;
        $this->asesor->setVisibility();
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
        $this->unidades->setVisibility();
        $this->descuento->Visible = false;
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
        $this->impreso->Visible = false;
        $this->igtf->Visible = false;
        $this->monto_base_igtf->Visible = false;
        $this->monto_igtf->Visible = false;
        $this->pago_premio->setVisibility();
        $this->hideFieldsForAddEdit();

        // Global Page Loading event (in userfn*.php)
        Page_Loading();

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Setup other options
        $this->setupOtherOptions();

        // Set up custom action (compatible with old version)
        foreach ($this->CustomActions as $name => $action) {
            $this->ListActions->add($name, $action);
        }

        // Show checkbox column if multiple action
        foreach ($this->ListActions->Items as $listaction) {
            if ($listaction->Select == ACTION_MULTIPLE && $listaction->Allow) {
                $this->ListOptions["checkbox"]->Visible = true;
                break;
            }
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

        // Search filters
        $srchAdvanced = ""; // Advanced search filter
        $srchBasic = ""; // Basic search filter
        $filter = "";

        // Get command
        $this->Command = strtolower(Get("cmd"));
        if ($this->isPageRequest()) {
            // Process list action first
            if ($this->processListAction()) { // Ajax request
                $this->terminate();
                return;
            }

            // Set up records per page
            $this->setupDisplayRecords();

            // Handle reset command
            $this->resetCmd();

            // Set up Breadcrumb
            if (!$this->isExport()) {
                $this->setupBreadcrumb();
            }

            // Hide list options
            if ($this->isExport()) {
                $this->ListOptions->hideAllOptions(["sequence"]);
                $this->ListOptions->UseDropDownButton = false; // Disable drop down button
                $this->ListOptions->UseButtonGroup = false; // Disable button group
            } elseif ($this->isGridAdd() || $this->isGridEdit()) {
                $this->ListOptions->hideAllOptions();
                $this->ListOptions->UseDropDownButton = false; // Disable drop down button
                $this->ListOptions->UseButtonGroup = false; // Disable button group
            }

            // Hide options
            if ($this->isExport() || $this->CurrentAction) {
                $this->ExportOptions->hideAllOptions();
                $this->FilterOptions->hideAllOptions();
                $this->ImportOptions->hideAllOptions();
            }

            // Hide other options
            if ($this->isExport()) {
                $this->OtherOptions->hideAllOptions();
            }

            // Get default search criteria
            AddFilter($this->DefaultSearchWhere, $this->basicSearchWhere(true));
            AddFilter($this->DefaultSearchWhere, $this->advancedSearchWhere(true));

            // Get basic search values
            $this->loadBasicSearchValues();

            // Get and validate search values for advanced search
            $this->loadSearchValues(); // Get search values

            // Process filter list
            if ($this->processFilterList()) {
                $this->terminate();
                return;
            }
            if (!$this->validateSearch()) {
                // Nothing to do
            }

            // Restore search parms from Session if not searching / reset / export
            if (($this->isExport() || $this->Command != "search" && $this->Command != "reset" && $this->Command != "resetall") && $this->Command != "json" && $this->checkSearchParms()) {
                $this->restoreSearchParms();
            }

            // Call Recordset SearchValidated event
            $this->recordsetSearchValidated();

            // Set up sorting order
            $this->setupSortOrder();

            // Get basic search criteria
            if (!$this->hasInvalidFields()) {
                $srchBasic = $this->basicSearchWhere();
            }

            // Get search criteria for advanced search
            if (!$this->hasInvalidFields()) {
                $srchAdvanced = $this->advancedSearchWhere();
            }
        }

        // Restore display records
        if ($this->Command != "json" && $this->getRecordsPerPage() != "") {
            $this->DisplayRecords = $this->getRecordsPerPage(); // Restore from Session
        } else {
            $this->DisplayRecords = 20; // Load default
            $this->setRecordsPerPage($this->DisplayRecords); // Save default to Session
        }

        // Load Sorting Order
        if ($this->Command != "json") {
            $this->loadSortOrder();
        }

        // Load search default if no existing search criteria
        if (!$this->checkSearchParms()) {
            // Load basic search from default
            $this->BasicSearch->loadDefault();
            if ($this->BasicSearch->Keyword != "") {
                $srchBasic = $this->basicSearchWhere();
            }

            // Load advanced search from default
            if ($this->loadAdvancedSearchDefault()) {
                $srchAdvanced = $this->advancedSearchWhere();
            }
        }

        // Restore search settings from Session
        if (!$this->hasInvalidFields()) {
            $this->loadAdvancedSearch();
        }

        // Build search criteria
        AddFilter($this->SearchWhere, $srchAdvanced);
        AddFilter($this->SearchWhere, $srchBasic);

        // Call Recordset_Searching event
        $this->recordsetSearching($this->SearchWhere);

        // Save search criteria
        if ($this->Command == "search" && !$this->RestoreSearch) {
            $this->setSearchWhere($this->SearchWhere); // Save to Session
            $this->StartRecord = 1; // Reset start record counter
            $this->setStartRecordNumber($this->StartRecord);
        } elseif ($this->Command != "json") {
            $this->SearchWhere = $this->getSearchWhere();
        }

        // Build filter
        $filter = "";
        if (!$Security->canList()) {
            $filter = "(0=1)"; // Filter all records
        }
        AddFilter($filter, $this->DbDetailFilter);
        AddFilter($filter, $this->SearchWhere);

        // Set up filter
        if ($this->Command == "json") {
            $this->UseSessionForListSql = false; // Do not use session for ListSQL
            $this->CurrentFilter = $filter;
        } else {
            $this->setSessionWhere($filter);
            $this->CurrentFilter = "";
        }

        // Export data only
        if (!$this->CustomExport && in_array($this->Export, array_keys(Config("EXPORT_CLASSES")))) {
            $this->exportData();
            $this->terminate();
            return;
        }
        if ($this->isGridAdd()) {
            $this->CurrentFilter = "0=1";
            $this->StartRecord = 1;
            $this->DisplayRecords = $this->GridAddRowCount;
            $this->TotalRecords = $this->DisplayRecords;
            $this->StopRecord = $this->DisplayRecords;
        } else {
            $this->TotalRecords = $this->listRecordCount();
            $this->StartRecord = 1;
            if ($this->DisplayRecords <= 0 || ($this->isExport() && $this->ExportAll)) { // Display all records
                $this->DisplayRecords = $this->TotalRecords;
            }
            if (!($this->isExport() && $this->ExportAll)) { // Set up start record position
                $this->setupStartRecord();
            }
            $this->Recordset = $this->loadRecordset($this->StartRecord - 1, $this->DisplayRecords);

            // Set no record found message
            if (!$this->CurrentAction && $this->TotalRecords == 0) {
                if (!$Security->canList()) {
                    $this->setWarningMessage(DeniedMessage());
                }
                if ($this->SearchWhere == "0=101") {
                    $this->setWarningMessage($Language->phrase("EnterSearchCriteria"));
                } else {
                    $this->setWarningMessage($Language->phrase("NoRecord"));
                }
            }

            // Audit trail on search
            if ($this->AuditTrailOnSearch && $this->Command == "search" && !$this->RestoreSearch) {
                $searchParm = ServerVar("QUERY_STRING");
                $searchSql = $this->getSessionWhere();
                $this->writeAuditTrailOnSearch($searchParm, $searchSql);
            }
        }

        // Search options
        $this->setupSearchOptions();

        // Set up search panel class
        if ($this->SearchWhere != "") {
            AppendClass($this->SearchPanelClass, "show");
        }

        // Normal return
        if (IsApi()) {
            $rows = $this->getRecordsFromRecordset($this->Recordset);
            $this->Recordset->close();
            WriteJson(["success" => true, $this->TableVar => $rows, "totalRecordCount" => $this->TotalRecords]);
            $this->terminate(true);
            return;
        }

        // Set up pager
        $this->Pager = new PrevNextPager($this->StartRecord, $this->getRecordsPerPage(), $this->TotalRecords, $this->PageSizes, $this->RecordRange, $this->AutoHidePager, $this->AutoHidePageSizeSelector);

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

    // Set up number of records displayed per page
    protected function setupDisplayRecords()
    {
        $wrk = Get(Config("TABLE_REC_PER_PAGE"), "");
        if ($wrk != "") {
            if (is_numeric($wrk)) {
                $this->DisplayRecords = (int)$wrk;
            } else {
                if (SameText($wrk, "all")) { // Display all records
                    $this->DisplayRecords = -1;
                } else {
                    $this->DisplayRecords = 20; // Non-numeric, load default
                }
            }
            $this->setRecordsPerPage($this->DisplayRecords); // Save to Session
            // Reset start position
            $this->StartRecord = 1;
            $this->setStartRecordNumber($this->StartRecord);
        }
    }

    // Build filter for all keys
    protected function buildKeyFilter()
    {
        global $CurrentForm;
        $wrkFilter = "";

        // Update row index and get row key
        $rowindex = 1;
        $CurrentForm->Index = $rowindex;
        $thisKey = strval($CurrentForm->getValue($this->OldKeyName));
        while ($thisKey != "") {
            $this->setKey($thisKey);
            if ($this->OldKey != "") {
                $filter = $this->getRecordFilter();
                if ($wrkFilter != "") {
                    $wrkFilter .= " OR ";
                }
                $wrkFilter .= $filter;
            } else {
                $wrkFilter = "0=1";
                break;
            }

            // Update row index and get row key
            $rowindex++; // Next row
            $CurrentForm->Index = $rowindex;
            $thisKey = strval($CurrentForm->getValue($this->OldKeyName));
        }
        return $wrkFilter;
    }

    // Get list of filters
    public function getFilterList()
    {
        global $UserProfile;

        // Initialize
        $filterList = "";
        $savedFilterList = "";
        $filterList = Concat($filterList, $this->id->AdvancedSearch->toJson(), ","); // Field id
        $filterList = Concat($filterList, $this->tipo_documento->AdvancedSearch->toJson(), ","); // Field tipo_documento
        $filterList = Concat($filterList, $this->nro_documento->AdvancedSearch->toJson(), ","); // Field nro_documento
        $filterList = Concat($filterList, $this->nro_control->AdvancedSearch->toJson(), ","); // Field nro_control
        $filterList = Concat($filterList, $this->fecha->AdvancedSearch->toJson(), ","); // Field fecha
        $filterList = Concat($filterList, $this->cliente->AdvancedSearch->toJson(), ","); // Field cliente
        $filterList = Concat($filterList, $this->documento->AdvancedSearch->toJson(), ","); // Field documento
        $filterList = Concat($filterList, $this->doc_afectado->AdvancedSearch->toJson(), ","); // Field doc_afectado
        $filterList = Concat($filterList, $this->moneda->AdvancedSearch->toJson(), ","); // Field moneda
        $filterList = Concat($filterList, $this->monto_total->AdvancedSearch->toJson(), ","); // Field monto_total
        $filterList = Concat($filterList, $this->alicuota_iva->AdvancedSearch->toJson(), ","); // Field alicuota_iva
        $filterList = Concat($filterList, $this->iva->AdvancedSearch->toJson(), ","); // Field iva
        $filterList = Concat($filterList, $this->total->AdvancedSearch->toJson(), ","); // Field total
        $filterList = Concat($filterList, $this->tasa_dia->AdvancedSearch->toJson(), ","); // Field tasa_dia
        $filterList = Concat($filterList, $this->monto_usd->AdvancedSearch->toJson(), ","); // Field monto_usd
        $filterList = Concat($filterList, $this->lista_pedido->AdvancedSearch->toJson(), ","); // Field lista_pedido
        $filterList = Concat($filterList, $this->nota->AdvancedSearch->toJson(), ","); // Field nota
        $filterList = Concat($filterList, $this->_username->AdvancedSearch->toJson(), ","); // Field username
        $filterList = Concat($filterList, $this->estatus->AdvancedSearch->toJson(), ","); // Field estatus
        $filterList = Concat($filterList, $this->id_documento_padre->AdvancedSearch->toJson(), ","); // Field id_documento_padre
        $filterList = Concat($filterList, $this->asesor->AdvancedSearch->toJson(), ","); // Field asesor
        $filterList = Concat($filterList, $this->dias_credito->AdvancedSearch->toJson(), ","); // Field dias_credito
        $filterList = Concat($filterList, $this->entregado->AdvancedSearch->toJson(), ","); // Field entregado
        $filterList = Concat($filterList, $this->fecha_entrega->AdvancedSearch->toJson(), ","); // Field fecha_entrega
        $filterList = Concat($filterList, $this->pagado->AdvancedSearch->toJson(), ","); // Field pagado
        $filterList = Concat($filterList, $this->bultos->AdvancedSearch->toJson(), ","); // Field bultos
        $filterList = Concat($filterList, $this->fecha_bultos->AdvancedSearch->toJson(), ","); // Field fecha_bultos
        $filterList = Concat($filterList, $this->user_bultos->AdvancedSearch->toJson(), ","); // Field user_bultos
        $filterList = Concat($filterList, $this->fecha_despacho->AdvancedSearch->toJson(), ","); // Field fecha_despacho
        $filterList = Concat($filterList, $this->user_despacho->AdvancedSearch->toJson(), ","); // Field user_despacho
        $filterList = Concat($filterList, $this->consignacion->AdvancedSearch->toJson(), ","); // Field consignacion
        $filterList = Concat($filterList, $this->unidades->AdvancedSearch->toJson(), ","); // Field unidades
        $filterList = Concat($filterList, $this->descuento->AdvancedSearch->toJson(), ","); // Field descuento
        $filterList = Concat($filterList, $this->monto_sin_descuento->AdvancedSearch->toJson(), ","); // Field monto_sin_descuento
        $filterList = Concat($filterList, $this->factura->AdvancedSearch->toJson(), ","); // Field factura
        $filterList = Concat($filterList, $this->ci_rif->AdvancedSearch->toJson(), ","); // Field ci_rif
        $filterList = Concat($filterList, $this->nombre->AdvancedSearch->toJson(), ","); // Field nombre
        $filterList = Concat($filterList, $this->direccion->AdvancedSearch->toJson(), ","); // Field direccion
        $filterList = Concat($filterList, $this->telefono->AdvancedSearch->toJson(), ","); // Field telefono
        $filterList = Concat($filterList, $this->_email->AdvancedSearch->toJson(), ","); // Field email
        $filterList = Concat($filterList, $this->activo->AdvancedSearch->toJson(), ","); // Field activo
        $filterList = Concat($filterList, $this->comprobante->AdvancedSearch->toJson(), ","); // Field comprobante
        $filterList = Concat($filterList, $this->nro_despacho->AdvancedSearch->toJson(), ","); // Field nro_despacho
        $filterList = Concat($filterList, $this->cerrado->AdvancedSearch->toJson(), ","); // Field cerrado
        $filterList = Concat($filterList, $this->impreso->AdvancedSearch->toJson(), ","); // Field impreso
        $filterList = Concat($filterList, $this->igtf->AdvancedSearch->toJson(), ","); // Field igtf
        $filterList = Concat($filterList, $this->monto_base_igtf->AdvancedSearch->toJson(), ","); // Field monto_base_igtf
        $filterList = Concat($filterList, $this->monto_igtf->AdvancedSearch->toJson(), ","); // Field monto_igtf
        $filterList = Concat($filterList, $this->pago_premio->AdvancedSearch->toJson(), ","); // Field pago_premio
        if ($this->BasicSearch->Keyword != "") {
            $wrk = "\"" . Config("TABLE_BASIC_SEARCH") . "\":\"" . JsEncode($this->BasicSearch->Keyword) . "\",\"" . Config("TABLE_BASIC_SEARCH_TYPE") . "\":\"" . JsEncode($this->BasicSearch->Type) . "\"";
            $filterList = Concat($filterList, $wrk, ",");
        }

        // Return filter list in JSON
        if ($filterList != "") {
            $filterList = "\"data\":{" . $filterList . "}";
        }
        if ($savedFilterList != "") {
            $filterList = Concat($filterList, "\"filters\":" . $savedFilterList, ",");
        }
        return ($filterList != "") ? "{" . $filterList . "}" : "null";
    }

    // Process filter list
    protected function processFilterList()
    {
        global $UserProfile;
        if (Post("ajax") == "savefilters") { // Save filter request (Ajax)
            $filters = Post("filters");
            $UserProfile->setSearchFilters(CurrentUserName(), "fsalidaslistsrch", $filters);
            WriteJson([["success" => true]]); // Success
            return true;
        } elseif (Post("cmd") == "resetfilter") {
            $this->restoreFilterList();
        }
        return false;
    }

    // Restore list of filters
    protected function restoreFilterList()
    {
        // Return if not reset filter
        if (Post("cmd") !== "resetfilter") {
            return false;
        }
        $filter = json_decode(Post("filter"), true);
        $this->Command = "search";

        // Field id
        $this->id->AdvancedSearch->SearchValue = @$filter["x_id"];
        $this->id->AdvancedSearch->SearchOperator = @$filter["z_id"];
        $this->id->AdvancedSearch->SearchCondition = @$filter["v_id"];
        $this->id->AdvancedSearch->SearchValue2 = @$filter["y_id"];
        $this->id->AdvancedSearch->SearchOperator2 = @$filter["w_id"];
        $this->id->AdvancedSearch->save();

        // Field tipo_documento
        $this->tipo_documento->AdvancedSearch->SearchValue = @$filter["x_tipo_documento"];
        $this->tipo_documento->AdvancedSearch->SearchOperator = @$filter["z_tipo_documento"];
        $this->tipo_documento->AdvancedSearch->SearchCondition = @$filter["v_tipo_documento"];
        $this->tipo_documento->AdvancedSearch->SearchValue2 = @$filter["y_tipo_documento"];
        $this->tipo_documento->AdvancedSearch->SearchOperator2 = @$filter["w_tipo_documento"];
        $this->tipo_documento->AdvancedSearch->save();

        // Field nro_documento
        $this->nro_documento->AdvancedSearch->SearchValue = @$filter["x_nro_documento"];
        $this->nro_documento->AdvancedSearch->SearchOperator = @$filter["z_nro_documento"];
        $this->nro_documento->AdvancedSearch->SearchCondition = @$filter["v_nro_documento"];
        $this->nro_documento->AdvancedSearch->SearchValue2 = @$filter["y_nro_documento"];
        $this->nro_documento->AdvancedSearch->SearchOperator2 = @$filter["w_nro_documento"];
        $this->nro_documento->AdvancedSearch->save();

        // Field nro_control
        $this->nro_control->AdvancedSearch->SearchValue = @$filter["x_nro_control"];
        $this->nro_control->AdvancedSearch->SearchOperator = @$filter["z_nro_control"];
        $this->nro_control->AdvancedSearch->SearchCondition = @$filter["v_nro_control"];
        $this->nro_control->AdvancedSearch->SearchValue2 = @$filter["y_nro_control"];
        $this->nro_control->AdvancedSearch->SearchOperator2 = @$filter["w_nro_control"];
        $this->nro_control->AdvancedSearch->save();

        // Field fecha
        $this->fecha->AdvancedSearch->SearchValue = @$filter["x_fecha"];
        $this->fecha->AdvancedSearch->SearchOperator = @$filter["z_fecha"];
        $this->fecha->AdvancedSearch->SearchCondition = @$filter["v_fecha"];
        $this->fecha->AdvancedSearch->SearchValue2 = @$filter["y_fecha"];
        $this->fecha->AdvancedSearch->SearchOperator2 = @$filter["w_fecha"];
        $this->fecha->AdvancedSearch->save();

        // Field cliente
        $this->cliente->AdvancedSearch->SearchValue = @$filter["x_cliente"];
        $this->cliente->AdvancedSearch->SearchOperator = @$filter["z_cliente"];
        $this->cliente->AdvancedSearch->SearchCondition = @$filter["v_cliente"];
        $this->cliente->AdvancedSearch->SearchValue2 = @$filter["y_cliente"];
        $this->cliente->AdvancedSearch->SearchOperator2 = @$filter["w_cliente"];
        $this->cliente->AdvancedSearch->save();

        // Field documento
        $this->documento->AdvancedSearch->SearchValue = @$filter["x_documento"];
        $this->documento->AdvancedSearch->SearchOperator = @$filter["z_documento"];
        $this->documento->AdvancedSearch->SearchCondition = @$filter["v_documento"];
        $this->documento->AdvancedSearch->SearchValue2 = @$filter["y_documento"];
        $this->documento->AdvancedSearch->SearchOperator2 = @$filter["w_documento"];
        $this->documento->AdvancedSearch->save();

        // Field doc_afectado
        $this->doc_afectado->AdvancedSearch->SearchValue = @$filter["x_doc_afectado"];
        $this->doc_afectado->AdvancedSearch->SearchOperator = @$filter["z_doc_afectado"];
        $this->doc_afectado->AdvancedSearch->SearchCondition = @$filter["v_doc_afectado"];
        $this->doc_afectado->AdvancedSearch->SearchValue2 = @$filter["y_doc_afectado"];
        $this->doc_afectado->AdvancedSearch->SearchOperator2 = @$filter["w_doc_afectado"];
        $this->doc_afectado->AdvancedSearch->save();

        // Field moneda
        $this->moneda->AdvancedSearch->SearchValue = @$filter["x_moneda"];
        $this->moneda->AdvancedSearch->SearchOperator = @$filter["z_moneda"];
        $this->moneda->AdvancedSearch->SearchCondition = @$filter["v_moneda"];
        $this->moneda->AdvancedSearch->SearchValue2 = @$filter["y_moneda"];
        $this->moneda->AdvancedSearch->SearchOperator2 = @$filter["w_moneda"];
        $this->moneda->AdvancedSearch->save();

        // Field monto_total
        $this->monto_total->AdvancedSearch->SearchValue = @$filter["x_monto_total"];
        $this->monto_total->AdvancedSearch->SearchOperator = @$filter["z_monto_total"];
        $this->monto_total->AdvancedSearch->SearchCondition = @$filter["v_monto_total"];
        $this->monto_total->AdvancedSearch->SearchValue2 = @$filter["y_monto_total"];
        $this->monto_total->AdvancedSearch->SearchOperator2 = @$filter["w_monto_total"];
        $this->monto_total->AdvancedSearch->save();

        // Field alicuota_iva
        $this->alicuota_iva->AdvancedSearch->SearchValue = @$filter["x_alicuota_iva"];
        $this->alicuota_iva->AdvancedSearch->SearchOperator = @$filter["z_alicuota_iva"];
        $this->alicuota_iva->AdvancedSearch->SearchCondition = @$filter["v_alicuota_iva"];
        $this->alicuota_iva->AdvancedSearch->SearchValue2 = @$filter["y_alicuota_iva"];
        $this->alicuota_iva->AdvancedSearch->SearchOperator2 = @$filter["w_alicuota_iva"];
        $this->alicuota_iva->AdvancedSearch->save();

        // Field iva
        $this->iva->AdvancedSearch->SearchValue = @$filter["x_iva"];
        $this->iva->AdvancedSearch->SearchOperator = @$filter["z_iva"];
        $this->iva->AdvancedSearch->SearchCondition = @$filter["v_iva"];
        $this->iva->AdvancedSearch->SearchValue2 = @$filter["y_iva"];
        $this->iva->AdvancedSearch->SearchOperator2 = @$filter["w_iva"];
        $this->iva->AdvancedSearch->save();

        // Field total
        $this->total->AdvancedSearch->SearchValue = @$filter["x_total"];
        $this->total->AdvancedSearch->SearchOperator = @$filter["z_total"];
        $this->total->AdvancedSearch->SearchCondition = @$filter["v_total"];
        $this->total->AdvancedSearch->SearchValue2 = @$filter["y_total"];
        $this->total->AdvancedSearch->SearchOperator2 = @$filter["w_total"];
        $this->total->AdvancedSearch->save();

        // Field tasa_dia
        $this->tasa_dia->AdvancedSearch->SearchValue = @$filter["x_tasa_dia"];
        $this->tasa_dia->AdvancedSearch->SearchOperator = @$filter["z_tasa_dia"];
        $this->tasa_dia->AdvancedSearch->SearchCondition = @$filter["v_tasa_dia"];
        $this->tasa_dia->AdvancedSearch->SearchValue2 = @$filter["y_tasa_dia"];
        $this->tasa_dia->AdvancedSearch->SearchOperator2 = @$filter["w_tasa_dia"];
        $this->tasa_dia->AdvancedSearch->save();

        // Field monto_usd
        $this->monto_usd->AdvancedSearch->SearchValue = @$filter["x_monto_usd"];
        $this->monto_usd->AdvancedSearch->SearchOperator = @$filter["z_monto_usd"];
        $this->monto_usd->AdvancedSearch->SearchCondition = @$filter["v_monto_usd"];
        $this->monto_usd->AdvancedSearch->SearchValue2 = @$filter["y_monto_usd"];
        $this->monto_usd->AdvancedSearch->SearchOperator2 = @$filter["w_monto_usd"];
        $this->monto_usd->AdvancedSearch->save();

        // Field lista_pedido
        $this->lista_pedido->AdvancedSearch->SearchValue = @$filter["x_lista_pedido"];
        $this->lista_pedido->AdvancedSearch->SearchOperator = @$filter["z_lista_pedido"];
        $this->lista_pedido->AdvancedSearch->SearchCondition = @$filter["v_lista_pedido"];
        $this->lista_pedido->AdvancedSearch->SearchValue2 = @$filter["y_lista_pedido"];
        $this->lista_pedido->AdvancedSearch->SearchOperator2 = @$filter["w_lista_pedido"];
        $this->lista_pedido->AdvancedSearch->save();

        // Field nota
        $this->nota->AdvancedSearch->SearchValue = @$filter["x_nota"];
        $this->nota->AdvancedSearch->SearchOperator = @$filter["z_nota"];
        $this->nota->AdvancedSearch->SearchCondition = @$filter["v_nota"];
        $this->nota->AdvancedSearch->SearchValue2 = @$filter["y_nota"];
        $this->nota->AdvancedSearch->SearchOperator2 = @$filter["w_nota"];
        $this->nota->AdvancedSearch->save();

        // Field username
        $this->_username->AdvancedSearch->SearchValue = @$filter["x__username"];
        $this->_username->AdvancedSearch->SearchOperator = @$filter["z__username"];
        $this->_username->AdvancedSearch->SearchCondition = @$filter["v__username"];
        $this->_username->AdvancedSearch->SearchValue2 = @$filter["y__username"];
        $this->_username->AdvancedSearch->SearchOperator2 = @$filter["w__username"];
        $this->_username->AdvancedSearch->save();

        // Field estatus
        $this->estatus->AdvancedSearch->SearchValue = @$filter["x_estatus"];
        $this->estatus->AdvancedSearch->SearchOperator = @$filter["z_estatus"];
        $this->estatus->AdvancedSearch->SearchCondition = @$filter["v_estatus"];
        $this->estatus->AdvancedSearch->SearchValue2 = @$filter["y_estatus"];
        $this->estatus->AdvancedSearch->SearchOperator2 = @$filter["w_estatus"];
        $this->estatus->AdvancedSearch->save();

        // Field id_documento_padre
        $this->id_documento_padre->AdvancedSearch->SearchValue = @$filter["x_id_documento_padre"];
        $this->id_documento_padre->AdvancedSearch->SearchOperator = @$filter["z_id_documento_padre"];
        $this->id_documento_padre->AdvancedSearch->SearchCondition = @$filter["v_id_documento_padre"];
        $this->id_documento_padre->AdvancedSearch->SearchValue2 = @$filter["y_id_documento_padre"];
        $this->id_documento_padre->AdvancedSearch->SearchOperator2 = @$filter["w_id_documento_padre"];
        $this->id_documento_padre->AdvancedSearch->save();

        // Field asesor
        $this->asesor->AdvancedSearch->SearchValue = @$filter["x_asesor"];
        $this->asesor->AdvancedSearch->SearchOperator = @$filter["z_asesor"];
        $this->asesor->AdvancedSearch->SearchCondition = @$filter["v_asesor"];
        $this->asesor->AdvancedSearch->SearchValue2 = @$filter["y_asesor"];
        $this->asesor->AdvancedSearch->SearchOperator2 = @$filter["w_asesor"];
        $this->asesor->AdvancedSearch->save();

        // Field dias_credito
        $this->dias_credito->AdvancedSearch->SearchValue = @$filter["x_dias_credito"];
        $this->dias_credito->AdvancedSearch->SearchOperator = @$filter["z_dias_credito"];
        $this->dias_credito->AdvancedSearch->SearchCondition = @$filter["v_dias_credito"];
        $this->dias_credito->AdvancedSearch->SearchValue2 = @$filter["y_dias_credito"];
        $this->dias_credito->AdvancedSearch->SearchOperator2 = @$filter["w_dias_credito"];
        $this->dias_credito->AdvancedSearch->save();

        // Field entregado
        $this->entregado->AdvancedSearch->SearchValue = @$filter["x_entregado"];
        $this->entregado->AdvancedSearch->SearchOperator = @$filter["z_entregado"];
        $this->entregado->AdvancedSearch->SearchCondition = @$filter["v_entregado"];
        $this->entregado->AdvancedSearch->SearchValue2 = @$filter["y_entregado"];
        $this->entregado->AdvancedSearch->SearchOperator2 = @$filter["w_entregado"];
        $this->entregado->AdvancedSearch->save();

        // Field fecha_entrega
        $this->fecha_entrega->AdvancedSearch->SearchValue = @$filter["x_fecha_entrega"];
        $this->fecha_entrega->AdvancedSearch->SearchOperator = @$filter["z_fecha_entrega"];
        $this->fecha_entrega->AdvancedSearch->SearchCondition = @$filter["v_fecha_entrega"];
        $this->fecha_entrega->AdvancedSearch->SearchValue2 = @$filter["y_fecha_entrega"];
        $this->fecha_entrega->AdvancedSearch->SearchOperator2 = @$filter["w_fecha_entrega"];
        $this->fecha_entrega->AdvancedSearch->save();

        // Field pagado
        $this->pagado->AdvancedSearch->SearchValue = @$filter["x_pagado"];
        $this->pagado->AdvancedSearch->SearchOperator = @$filter["z_pagado"];
        $this->pagado->AdvancedSearch->SearchCondition = @$filter["v_pagado"];
        $this->pagado->AdvancedSearch->SearchValue2 = @$filter["y_pagado"];
        $this->pagado->AdvancedSearch->SearchOperator2 = @$filter["w_pagado"];
        $this->pagado->AdvancedSearch->save();

        // Field bultos
        $this->bultos->AdvancedSearch->SearchValue = @$filter["x_bultos"];
        $this->bultos->AdvancedSearch->SearchOperator = @$filter["z_bultos"];
        $this->bultos->AdvancedSearch->SearchCondition = @$filter["v_bultos"];
        $this->bultos->AdvancedSearch->SearchValue2 = @$filter["y_bultos"];
        $this->bultos->AdvancedSearch->SearchOperator2 = @$filter["w_bultos"];
        $this->bultos->AdvancedSearch->save();

        // Field fecha_bultos
        $this->fecha_bultos->AdvancedSearch->SearchValue = @$filter["x_fecha_bultos"];
        $this->fecha_bultos->AdvancedSearch->SearchOperator = @$filter["z_fecha_bultos"];
        $this->fecha_bultos->AdvancedSearch->SearchCondition = @$filter["v_fecha_bultos"];
        $this->fecha_bultos->AdvancedSearch->SearchValue2 = @$filter["y_fecha_bultos"];
        $this->fecha_bultos->AdvancedSearch->SearchOperator2 = @$filter["w_fecha_bultos"];
        $this->fecha_bultos->AdvancedSearch->save();

        // Field user_bultos
        $this->user_bultos->AdvancedSearch->SearchValue = @$filter["x_user_bultos"];
        $this->user_bultos->AdvancedSearch->SearchOperator = @$filter["z_user_bultos"];
        $this->user_bultos->AdvancedSearch->SearchCondition = @$filter["v_user_bultos"];
        $this->user_bultos->AdvancedSearch->SearchValue2 = @$filter["y_user_bultos"];
        $this->user_bultos->AdvancedSearch->SearchOperator2 = @$filter["w_user_bultos"];
        $this->user_bultos->AdvancedSearch->save();

        // Field fecha_despacho
        $this->fecha_despacho->AdvancedSearch->SearchValue = @$filter["x_fecha_despacho"];
        $this->fecha_despacho->AdvancedSearch->SearchOperator = @$filter["z_fecha_despacho"];
        $this->fecha_despacho->AdvancedSearch->SearchCondition = @$filter["v_fecha_despacho"];
        $this->fecha_despacho->AdvancedSearch->SearchValue2 = @$filter["y_fecha_despacho"];
        $this->fecha_despacho->AdvancedSearch->SearchOperator2 = @$filter["w_fecha_despacho"];
        $this->fecha_despacho->AdvancedSearch->save();

        // Field user_despacho
        $this->user_despacho->AdvancedSearch->SearchValue = @$filter["x_user_despacho"];
        $this->user_despacho->AdvancedSearch->SearchOperator = @$filter["z_user_despacho"];
        $this->user_despacho->AdvancedSearch->SearchCondition = @$filter["v_user_despacho"];
        $this->user_despacho->AdvancedSearch->SearchValue2 = @$filter["y_user_despacho"];
        $this->user_despacho->AdvancedSearch->SearchOperator2 = @$filter["w_user_despacho"];
        $this->user_despacho->AdvancedSearch->save();

        // Field consignacion
        $this->consignacion->AdvancedSearch->SearchValue = @$filter["x_consignacion"];
        $this->consignacion->AdvancedSearch->SearchOperator = @$filter["z_consignacion"];
        $this->consignacion->AdvancedSearch->SearchCondition = @$filter["v_consignacion"];
        $this->consignacion->AdvancedSearch->SearchValue2 = @$filter["y_consignacion"];
        $this->consignacion->AdvancedSearch->SearchOperator2 = @$filter["w_consignacion"];
        $this->consignacion->AdvancedSearch->save();

        // Field unidades
        $this->unidades->AdvancedSearch->SearchValue = @$filter["x_unidades"];
        $this->unidades->AdvancedSearch->SearchOperator = @$filter["z_unidades"];
        $this->unidades->AdvancedSearch->SearchCondition = @$filter["v_unidades"];
        $this->unidades->AdvancedSearch->SearchValue2 = @$filter["y_unidades"];
        $this->unidades->AdvancedSearch->SearchOperator2 = @$filter["w_unidades"];
        $this->unidades->AdvancedSearch->save();

        // Field descuento
        $this->descuento->AdvancedSearch->SearchValue = @$filter["x_descuento"];
        $this->descuento->AdvancedSearch->SearchOperator = @$filter["z_descuento"];
        $this->descuento->AdvancedSearch->SearchCondition = @$filter["v_descuento"];
        $this->descuento->AdvancedSearch->SearchValue2 = @$filter["y_descuento"];
        $this->descuento->AdvancedSearch->SearchOperator2 = @$filter["w_descuento"];
        $this->descuento->AdvancedSearch->save();

        // Field monto_sin_descuento
        $this->monto_sin_descuento->AdvancedSearch->SearchValue = @$filter["x_monto_sin_descuento"];
        $this->monto_sin_descuento->AdvancedSearch->SearchOperator = @$filter["z_monto_sin_descuento"];
        $this->monto_sin_descuento->AdvancedSearch->SearchCondition = @$filter["v_monto_sin_descuento"];
        $this->monto_sin_descuento->AdvancedSearch->SearchValue2 = @$filter["y_monto_sin_descuento"];
        $this->monto_sin_descuento->AdvancedSearch->SearchOperator2 = @$filter["w_monto_sin_descuento"];
        $this->monto_sin_descuento->AdvancedSearch->save();

        // Field factura
        $this->factura->AdvancedSearch->SearchValue = @$filter["x_factura"];
        $this->factura->AdvancedSearch->SearchOperator = @$filter["z_factura"];
        $this->factura->AdvancedSearch->SearchCondition = @$filter["v_factura"];
        $this->factura->AdvancedSearch->SearchValue2 = @$filter["y_factura"];
        $this->factura->AdvancedSearch->SearchOperator2 = @$filter["w_factura"];
        $this->factura->AdvancedSearch->save();

        // Field ci_rif
        $this->ci_rif->AdvancedSearch->SearchValue = @$filter["x_ci_rif"];
        $this->ci_rif->AdvancedSearch->SearchOperator = @$filter["z_ci_rif"];
        $this->ci_rif->AdvancedSearch->SearchCondition = @$filter["v_ci_rif"];
        $this->ci_rif->AdvancedSearch->SearchValue2 = @$filter["y_ci_rif"];
        $this->ci_rif->AdvancedSearch->SearchOperator2 = @$filter["w_ci_rif"];
        $this->ci_rif->AdvancedSearch->save();

        // Field nombre
        $this->nombre->AdvancedSearch->SearchValue = @$filter["x_nombre"];
        $this->nombre->AdvancedSearch->SearchOperator = @$filter["z_nombre"];
        $this->nombre->AdvancedSearch->SearchCondition = @$filter["v_nombre"];
        $this->nombre->AdvancedSearch->SearchValue2 = @$filter["y_nombre"];
        $this->nombre->AdvancedSearch->SearchOperator2 = @$filter["w_nombre"];
        $this->nombre->AdvancedSearch->save();

        // Field direccion
        $this->direccion->AdvancedSearch->SearchValue = @$filter["x_direccion"];
        $this->direccion->AdvancedSearch->SearchOperator = @$filter["z_direccion"];
        $this->direccion->AdvancedSearch->SearchCondition = @$filter["v_direccion"];
        $this->direccion->AdvancedSearch->SearchValue2 = @$filter["y_direccion"];
        $this->direccion->AdvancedSearch->SearchOperator2 = @$filter["w_direccion"];
        $this->direccion->AdvancedSearch->save();

        // Field telefono
        $this->telefono->AdvancedSearch->SearchValue = @$filter["x_telefono"];
        $this->telefono->AdvancedSearch->SearchOperator = @$filter["z_telefono"];
        $this->telefono->AdvancedSearch->SearchCondition = @$filter["v_telefono"];
        $this->telefono->AdvancedSearch->SearchValue2 = @$filter["y_telefono"];
        $this->telefono->AdvancedSearch->SearchOperator2 = @$filter["w_telefono"];
        $this->telefono->AdvancedSearch->save();

        // Field email
        $this->_email->AdvancedSearch->SearchValue = @$filter["x__email"];
        $this->_email->AdvancedSearch->SearchOperator = @$filter["z__email"];
        $this->_email->AdvancedSearch->SearchCondition = @$filter["v__email"];
        $this->_email->AdvancedSearch->SearchValue2 = @$filter["y__email"];
        $this->_email->AdvancedSearch->SearchOperator2 = @$filter["w__email"];
        $this->_email->AdvancedSearch->save();

        // Field activo
        $this->activo->AdvancedSearch->SearchValue = @$filter["x_activo"];
        $this->activo->AdvancedSearch->SearchOperator = @$filter["z_activo"];
        $this->activo->AdvancedSearch->SearchCondition = @$filter["v_activo"];
        $this->activo->AdvancedSearch->SearchValue2 = @$filter["y_activo"];
        $this->activo->AdvancedSearch->SearchOperator2 = @$filter["w_activo"];
        $this->activo->AdvancedSearch->save();

        // Field comprobante
        $this->comprobante->AdvancedSearch->SearchValue = @$filter["x_comprobante"];
        $this->comprobante->AdvancedSearch->SearchOperator = @$filter["z_comprobante"];
        $this->comprobante->AdvancedSearch->SearchCondition = @$filter["v_comprobante"];
        $this->comprobante->AdvancedSearch->SearchValue2 = @$filter["y_comprobante"];
        $this->comprobante->AdvancedSearch->SearchOperator2 = @$filter["w_comprobante"];
        $this->comprobante->AdvancedSearch->save();

        // Field nro_despacho
        $this->nro_despacho->AdvancedSearch->SearchValue = @$filter["x_nro_despacho"];
        $this->nro_despacho->AdvancedSearch->SearchOperator = @$filter["z_nro_despacho"];
        $this->nro_despacho->AdvancedSearch->SearchCondition = @$filter["v_nro_despacho"];
        $this->nro_despacho->AdvancedSearch->SearchValue2 = @$filter["y_nro_despacho"];
        $this->nro_despacho->AdvancedSearch->SearchOperator2 = @$filter["w_nro_despacho"];
        $this->nro_despacho->AdvancedSearch->save();

        // Field cerrado
        $this->cerrado->AdvancedSearch->SearchValue = @$filter["x_cerrado"];
        $this->cerrado->AdvancedSearch->SearchOperator = @$filter["z_cerrado"];
        $this->cerrado->AdvancedSearch->SearchCondition = @$filter["v_cerrado"];
        $this->cerrado->AdvancedSearch->SearchValue2 = @$filter["y_cerrado"];
        $this->cerrado->AdvancedSearch->SearchOperator2 = @$filter["w_cerrado"];
        $this->cerrado->AdvancedSearch->save();

        // Field impreso
        $this->impreso->AdvancedSearch->SearchValue = @$filter["x_impreso"];
        $this->impreso->AdvancedSearch->SearchOperator = @$filter["z_impreso"];
        $this->impreso->AdvancedSearch->SearchCondition = @$filter["v_impreso"];
        $this->impreso->AdvancedSearch->SearchValue2 = @$filter["y_impreso"];
        $this->impreso->AdvancedSearch->SearchOperator2 = @$filter["w_impreso"];
        $this->impreso->AdvancedSearch->save();

        // Field igtf
        $this->igtf->AdvancedSearch->SearchValue = @$filter["x_igtf"];
        $this->igtf->AdvancedSearch->SearchOperator = @$filter["z_igtf"];
        $this->igtf->AdvancedSearch->SearchCondition = @$filter["v_igtf"];
        $this->igtf->AdvancedSearch->SearchValue2 = @$filter["y_igtf"];
        $this->igtf->AdvancedSearch->SearchOperator2 = @$filter["w_igtf"];
        $this->igtf->AdvancedSearch->save();

        // Field monto_base_igtf
        $this->monto_base_igtf->AdvancedSearch->SearchValue = @$filter["x_monto_base_igtf"];
        $this->monto_base_igtf->AdvancedSearch->SearchOperator = @$filter["z_monto_base_igtf"];
        $this->monto_base_igtf->AdvancedSearch->SearchCondition = @$filter["v_monto_base_igtf"];
        $this->monto_base_igtf->AdvancedSearch->SearchValue2 = @$filter["y_monto_base_igtf"];
        $this->monto_base_igtf->AdvancedSearch->SearchOperator2 = @$filter["w_monto_base_igtf"];
        $this->monto_base_igtf->AdvancedSearch->save();

        // Field monto_igtf
        $this->monto_igtf->AdvancedSearch->SearchValue = @$filter["x_monto_igtf"];
        $this->monto_igtf->AdvancedSearch->SearchOperator = @$filter["z_monto_igtf"];
        $this->monto_igtf->AdvancedSearch->SearchCondition = @$filter["v_monto_igtf"];
        $this->monto_igtf->AdvancedSearch->SearchValue2 = @$filter["y_monto_igtf"];
        $this->monto_igtf->AdvancedSearch->SearchOperator2 = @$filter["w_monto_igtf"];
        $this->monto_igtf->AdvancedSearch->save();

        // Field pago_premio
        $this->pago_premio->AdvancedSearch->SearchValue = @$filter["x_pago_premio"];
        $this->pago_premio->AdvancedSearch->SearchOperator = @$filter["z_pago_premio"];
        $this->pago_premio->AdvancedSearch->SearchCondition = @$filter["v_pago_premio"];
        $this->pago_premio->AdvancedSearch->SearchValue2 = @$filter["y_pago_premio"];
        $this->pago_premio->AdvancedSearch->SearchOperator2 = @$filter["w_pago_premio"];
        $this->pago_premio->AdvancedSearch->save();
        $this->BasicSearch->setKeyword(@$filter[Config("TABLE_BASIC_SEARCH")]);
        $this->BasicSearch->setType(@$filter[Config("TABLE_BASIC_SEARCH_TYPE")]);
    }

    // Advanced search WHERE clause based on QueryString
    protected function advancedSearchWhere($default = false)
    {
        global $Security;
        $where = "";
        if (!$Security->canSearch()) {
            return "";
        }
        $this->buildSearchSql($where, $this->id, $default, false); // id
        $this->buildSearchSql($where, $this->tipo_documento, $default, false); // tipo_documento
        $this->buildSearchSql($where, $this->nro_documento, $default, false); // nro_documento
        $this->buildSearchSql($where, $this->nro_control, $default, false); // nro_control
        $this->buildSearchSql($where, $this->fecha, $default, false); // fecha
        $this->buildSearchSql($where, $this->cliente, $default, false); // cliente
        $this->buildSearchSql($where, $this->documento, $default, false); // documento
        $this->buildSearchSql($where, $this->doc_afectado, $default, false); // doc_afectado
        $this->buildSearchSql($where, $this->moneda, $default, false); // moneda
        $this->buildSearchSql($where, $this->monto_total, $default, false); // monto_total
        $this->buildSearchSql($where, $this->alicuota_iva, $default, false); // alicuota_iva
        $this->buildSearchSql($where, $this->iva, $default, false); // iva
        $this->buildSearchSql($where, $this->total, $default, false); // total
        $this->buildSearchSql($where, $this->tasa_dia, $default, false); // tasa_dia
        $this->buildSearchSql($where, $this->monto_usd, $default, false); // monto_usd
        $this->buildSearchSql($where, $this->lista_pedido, $default, false); // lista_pedido
        $this->buildSearchSql($where, $this->nota, $default, false); // nota
        $this->buildSearchSql($where, $this->_username, $default, false); // username
        $this->buildSearchSql($where, $this->estatus, $default, false); // estatus
        $this->buildSearchSql($where, $this->id_documento_padre, $default, false); // id_documento_padre
        $this->buildSearchSql($where, $this->asesor, $default, false); // asesor
        $this->buildSearchSql($where, $this->dias_credito, $default, false); // dias_credito
        $this->buildSearchSql($where, $this->entregado, $default, false); // entregado
        $this->buildSearchSql($where, $this->fecha_entrega, $default, false); // fecha_entrega
        $this->buildSearchSql($where, $this->pagado, $default, false); // pagado
        $this->buildSearchSql($where, $this->bultos, $default, false); // bultos
        $this->buildSearchSql($where, $this->fecha_bultos, $default, false); // fecha_bultos
        $this->buildSearchSql($where, $this->user_bultos, $default, false); // user_bultos
        $this->buildSearchSql($where, $this->fecha_despacho, $default, false); // fecha_despacho
        $this->buildSearchSql($where, $this->user_despacho, $default, false); // user_despacho
        $this->buildSearchSql($where, $this->consignacion, $default, false); // consignacion
        $this->buildSearchSql($where, $this->unidades, $default, false); // unidades
        $this->buildSearchSql($where, $this->descuento, $default, false); // descuento
        $this->buildSearchSql($where, $this->monto_sin_descuento, $default, false); // monto_sin_descuento
        $this->buildSearchSql($where, $this->factura, $default, false); // factura
        $this->buildSearchSql($where, $this->ci_rif, $default, false); // ci_rif
        $this->buildSearchSql($where, $this->nombre, $default, false); // nombre
        $this->buildSearchSql($where, $this->direccion, $default, false); // direccion
        $this->buildSearchSql($where, $this->telefono, $default, false); // telefono
        $this->buildSearchSql($where, $this->_email, $default, false); // email
        $this->buildSearchSql($where, $this->activo, $default, false); // activo
        $this->buildSearchSql($where, $this->comprobante, $default, false); // comprobante
        $this->buildSearchSql($where, $this->nro_despacho, $default, false); // nro_despacho
        $this->buildSearchSql($where, $this->cerrado, $default, false); // cerrado
        $this->buildSearchSql($where, $this->impreso, $default, false); // impreso
        $this->buildSearchSql($where, $this->igtf, $default, false); // igtf
        $this->buildSearchSql($where, $this->monto_base_igtf, $default, false); // monto_base_igtf
        $this->buildSearchSql($where, $this->monto_igtf, $default, false); // monto_igtf
        $this->buildSearchSql($where, $this->pago_premio, $default, false); // pago_premio

        // Set up search parm
        if (!$default && $where != "" && in_array($this->Command, ["", "reset", "resetall"])) {
            $this->Command = "search";
        }
        if (!$default && $this->Command == "search") {
            $this->id->AdvancedSearch->save(); // id
            $this->tipo_documento->AdvancedSearch->save(); // tipo_documento
            $this->nro_documento->AdvancedSearch->save(); // nro_documento
            $this->nro_control->AdvancedSearch->save(); // nro_control
            $this->fecha->AdvancedSearch->save(); // fecha
            $this->cliente->AdvancedSearch->save(); // cliente
            $this->documento->AdvancedSearch->save(); // documento
            $this->doc_afectado->AdvancedSearch->save(); // doc_afectado
            $this->moneda->AdvancedSearch->save(); // moneda
            $this->monto_total->AdvancedSearch->save(); // monto_total
            $this->alicuota_iva->AdvancedSearch->save(); // alicuota_iva
            $this->iva->AdvancedSearch->save(); // iva
            $this->total->AdvancedSearch->save(); // total
            $this->tasa_dia->AdvancedSearch->save(); // tasa_dia
            $this->monto_usd->AdvancedSearch->save(); // monto_usd
            $this->lista_pedido->AdvancedSearch->save(); // lista_pedido
            $this->nota->AdvancedSearch->save(); // nota
            $this->_username->AdvancedSearch->save(); // username
            $this->estatus->AdvancedSearch->save(); // estatus
            $this->id_documento_padre->AdvancedSearch->save(); // id_documento_padre
            $this->asesor->AdvancedSearch->save(); // asesor
            $this->dias_credito->AdvancedSearch->save(); // dias_credito
            $this->entregado->AdvancedSearch->save(); // entregado
            $this->fecha_entrega->AdvancedSearch->save(); // fecha_entrega
            $this->pagado->AdvancedSearch->save(); // pagado
            $this->bultos->AdvancedSearch->save(); // bultos
            $this->fecha_bultos->AdvancedSearch->save(); // fecha_bultos
            $this->user_bultos->AdvancedSearch->save(); // user_bultos
            $this->fecha_despacho->AdvancedSearch->save(); // fecha_despacho
            $this->user_despacho->AdvancedSearch->save(); // user_despacho
            $this->consignacion->AdvancedSearch->save(); // consignacion
            $this->unidades->AdvancedSearch->save(); // unidades
            $this->descuento->AdvancedSearch->save(); // descuento
            $this->monto_sin_descuento->AdvancedSearch->save(); // monto_sin_descuento
            $this->factura->AdvancedSearch->save(); // factura
            $this->ci_rif->AdvancedSearch->save(); // ci_rif
            $this->nombre->AdvancedSearch->save(); // nombre
            $this->direccion->AdvancedSearch->save(); // direccion
            $this->telefono->AdvancedSearch->save(); // telefono
            $this->_email->AdvancedSearch->save(); // email
            $this->activo->AdvancedSearch->save(); // activo
            $this->comprobante->AdvancedSearch->save(); // comprobante
            $this->nro_despacho->AdvancedSearch->save(); // nro_despacho
            $this->cerrado->AdvancedSearch->save(); // cerrado
            $this->impreso->AdvancedSearch->save(); // impreso
            $this->igtf->AdvancedSearch->save(); // igtf
            $this->monto_base_igtf->AdvancedSearch->save(); // monto_base_igtf
            $this->monto_igtf->AdvancedSearch->save(); // monto_igtf
            $this->pago_premio->AdvancedSearch->save(); // pago_premio
        }
        return $where;
    }

    // Build search SQL
    protected function buildSearchSql(&$where, &$fld, $default, $multiValue)
    {
        $fldParm = $fld->Param;
        $fldVal = ($default) ? $fld->AdvancedSearch->SearchValueDefault : $fld->AdvancedSearch->SearchValue;
        $fldOpr = ($default) ? $fld->AdvancedSearch->SearchOperatorDefault : $fld->AdvancedSearch->SearchOperator;
        $fldCond = ($default) ? $fld->AdvancedSearch->SearchConditionDefault : $fld->AdvancedSearch->SearchCondition;
        $fldVal2 = ($default) ? $fld->AdvancedSearch->SearchValue2Default : $fld->AdvancedSearch->SearchValue2;
        $fldOpr2 = ($default) ? $fld->AdvancedSearch->SearchOperator2Default : $fld->AdvancedSearch->SearchOperator2;
        $wrk = "";
        if (is_array($fldVal)) {
            $fldVal = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $fldVal);
        }
        if (is_array($fldVal2)) {
            $fldVal2 = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $fldVal2);
        }
        $fldOpr = strtoupper(trim($fldOpr));
        if ($fldOpr == "") {
            $fldOpr = "=";
        }
        $fldOpr2 = strtoupper(trim($fldOpr2));
        if ($fldOpr2 == "") {
            $fldOpr2 = "=";
        }
        if (Config("SEARCH_MULTI_VALUE_OPTION") == 1 || !IsMultiSearchOperator($fldOpr)) {
            $multiValue = false;
        }
        if ($multiValue) {
            $wrk1 = ($fldVal != "") ? GetMultiSearchSql($fld, $fldOpr, $fldVal, $this->Dbid) : ""; // Field value 1
            $wrk2 = ($fldVal2 != "") ? GetMultiSearchSql($fld, $fldOpr2, $fldVal2, $this->Dbid) : ""; // Field value 2
            $wrk = $wrk1; // Build final SQL
            if ($wrk2 != "") {
                $wrk = ($wrk != "") ? "($wrk) $fldCond ($wrk2)" : $wrk2;
            }
        } else {
            $fldVal = $this->convertSearchValue($fld, $fldVal);
            $fldVal2 = $this->convertSearchValue($fld, $fldVal2);
            $wrk = GetSearchSql($fld, $fldVal, $fldOpr, $fldCond, $fldVal2, $fldOpr2, $this->Dbid);
        }
        AddFilter($where, $wrk);
    }

    // Convert search value
    protected function convertSearchValue(&$fld, $fldVal)
    {
        if ($fldVal == Config("NULL_VALUE") || $fldVal == Config("NOT_NULL_VALUE")) {
            return $fldVal;
        }
        $value = $fldVal;
        if ($fld->isBoolean()) {
            if ($fldVal != "") {
                $value = (SameText($fldVal, "1") || SameText($fldVal, "y") || SameText($fldVal, "t")) ? $fld->TrueValue : $fld->FalseValue;
            }
        } elseif ($fld->DataType == DATATYPE_DATE || $fld->DataType == DATATYPE_TIME) {
            if ($fldVal != "") {
                $value = UnFormatDateTime($fldVal, $fld->DateTimeFormat);
            }
        }
        return $value;
    }

    // Return basic search SQL
    protected function basicSearchSql($arKeywords, $type)
    {
        $where = "";
        $this->buildBasicSearchSql($where, $this->tipo_documento, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->nro_documento, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->nro_control, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->documento, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->doc_afectado, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->moneda, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->nota, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->_username, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->estatus, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->asesor, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->user_bultos, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->user_despacho, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->ci_rif, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->nombre, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->direccion, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->telefono, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->_email, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->nro_despacho, $arKeywords, $type);
        return $where;
    }

    // Build basic search SQL
    protected function buildBasicSearchSql(&$where, &$fld, $arKeywords, $type)
    {
        $defCond = ($type == "OR") ? "OR" : "AND";
        $arSql = []; // Array for SQL parts
        $arCond = []; // Array for search conditions
        $cnt = count($arKeywords);
        $j = 0; // Number of SQL parts
        for ($i = 0; $i < $cnt; $i++) {
            $keyword = $arKeywords[$i];
            $keyword = trim($keyword);
            if (Config("BASIC_SEARCH_IGNORE_PATTERN") != "") {
                $keyword = preg_replace(Config("BASIC_SEARCH_IGNORE_PATTERN"), "\\", $keyword);
                $ar = explode("\\", $keyword);
            } else {
                $ar = [$keyword];
            }
            foreach ($ar as $keyword) {
                if ($keyword != "") {
                    $wrk = "";
                    if ($keyword == "OR" && $type == "") {
                        if ($j > 0) {
                            $arCond[$j - 1] = "OR";
                        }
                    } elseif ($keyword == Config("NULL_VALUE")) {
                        $wrk = $fld->Expression . " IS NULL";
                    } elseif ($keyword == Config("NOT_NULL_VALUE")) {
                        $wrk = $fld->Expression . " IS NOT NULL";
                    } elseif ($fld->IsVirtual && $fld->Visible) {
                        $wrk = $fld->VirtualExpression . Like(QuotedValue("%" . $keyword . "%", DATATYPE_STRING, $this->Dbid), $this->Dbid);
                    } elseif ($fld->DataType != DATATYPE_NUMBER || is_numeric($keyword)) {
                        $wrk = $fld->BasicSearchExpression . Like(QuotedValue("%" . $keyword . "%", DATATYPE_STRING, $this->Dbid), $this->Dbid);
                    }
                    if ($wrk != "") {
                        $arSql[$j] = $wrk;
                        $arCond[$j] = $defCond;
                        $j += 1;
                    }
                }
            }
        }
        $cnt = count($arSql);
        $quoted = false;
        $sql = "";
        if ($cnt > 0) {
            for ($i = 0; $i < $cnt - 1; $i++) {
                if ($arCond[$i] == "OR") {
                    if (!$quoted) {
                        $sql .= "(";
                    }
                    $quoted = true;
                }
                $sql .= $arSql[$i];
                if ($quoted && $arCond[$i] != "OR") {
                    $sql .= ")";
                    $quoted = false;
                }
                $sql .= " " . $arCond[$i] . " ";
            }
            $sql .= $arSql[$cnt - 1];
            if ($quoted) {
                $sql .= ")";
            }
        }
        if ($sql != "") {
            if ($where != "") {
                $where .= " OR ";
            }
            $where .= "(" . $sql . ")";
        }
    }

    // Return basic search WHERE clause based on search keyword and type
    protected function basicSearchWhere($default = false)
    {
        global $Security;
        $searchStr = "";
        if (!$Security->canSearch()) {
            return "";
        }
        $searchKeyword = ($default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
        $searchType = ($default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;

        // Get search SQL
        if ($searchKeyword != "") {
            $ar = $this->BasicSearch->keywordList($default);
            // Search keyword in any fields
            if (($searchType == "OR" || $searchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
                foreach ($ar as $keyword) {
                    if ($keyword != "") {
                        if ($searchStr != "") {
                            $searchStr .= " " . $searchType . " ";
                        }
                        $searchStr .= "(" . $this->basicSearchSql([$keyword], $searchType) . ")";
                    }
                }
            } else {
                $searchStr = $this->basicSearchSql($ar, $searchType);
            }
            if (!$default && in_array($this->Command, ["", "reset", "resetall"])) {
                $this->Command = "search";
            }
        }
        if (!$default && $this->Command == "search") {
            $this->BasicSearch->setKeyword($searchKeyword);
            $this->BasicSearch->setType($searchType);
        }
        return $searchStr;
    }

    // Check if search parm exists
    protected function checkSearchParms()
    {
        // Check basic search
        if ($this->BasicSearch->issetSession()) {
            return true;
        }
        if ($this->id->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->tipo_documento->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->nro_documento->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->nro_control->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->fecha->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->cliente->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->documento->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->doc_afectado->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->moneda->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->monto_total->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->alicuota_iva->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->iva->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->total->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->tasa_dia->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->monto_usd->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->lista_pedido->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->nota->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->_username->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->estatus->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->id_documento_padre->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->asesor->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->dias_credito->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->entregado->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->fecha_entrega->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->pagado->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->bultos->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->fecha_bultos->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->user_bultos->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->fecha_despacho->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->user_despacho->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->consignacion->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->unidades->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->descuento->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->monto_sin_descuento->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->factura->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->ci_rif->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->nombre->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->direccion->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->telefono->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->_email->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->activo->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->comprobante->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->nro_despacho->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->cerrado->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->impreso->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->igtf->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->monto_base_igtf->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->monto_igtf->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->pago_premio->AdvancedSearch->issetSession()) {
            return true;
        }
        return false;
    }

    // Clear all search parameters
    protected function resetSearchParms()
    {
        // Clear search WHERE clause
        $this->SearchWhere = "";
        $this->setSearchWhere($this->SearchWhere);

        // Clear basic search parameters
        $this->resetBasicSearchParms();

        // Clear advanced search parameters
        $this->resetAdvancedSearchParms();
    }

    // Load advanced search default values
    protected function loadAdvancedSearchDefault()
    {
                $this->descuento->AdvancedSearch->loadDefault();
        return true;
    }

    // Clear all basic search parameters
    protected function resetBasicSearchParms()
    {
        $this->BasicSearch->unsetSession();
    }

    // Clear all advanced search parameters
    protected function resetAdvancedSearchParms()
    {
                $this->id->AdvancedSearch->unsetSession();
                $this->tipo_documento->AdvancedSearch->unsetSession();
                $this->nro_documento->AdvancedSearch->unsetSession();
                $this->nro_control->AdvancedSearch->unsetSession();
                $this->fecha->AdvancedSearch->unsetSession();
                $this->cliente->AdvancedSearch->unsetSession();
                $this->documento->AdvancedSearch->unsetSession();
                $this->doc_afectado->AdvancedSearch->unsetSession();
                $this->moneda->AdvancedSearch->unsetSession();
                $this->monto_total->AdvancedSearch->unsetSession();
                $this->alicuota_iva->AdvancedSearch->unsetSession();
                $this->iva->AdvancedSearch->unsetSession();
                $this->total->AdvancedSearch->unsetSession();
                $this->tasa_dia->AdvancedSearch->unsetSession();
                $this->monto_usd->AdvancedSearch->unsetSession();
                $this->lista_pedido->AdvancedSearch->unsetSession();
                $this->nota->AdvancedSearch->unsetSession();
                $this->_username->AdvancedSearch->unsetSession();
                $this->estatus->AdvancedSearch->unsetSession();
                $this->id_documento_padre->AdvancedSearch->unsetSession();
                $this->asesor->AdvancedSearch->unsetSession();
                $this->dias_credito->AdvancedSearch->unsetSession();
                $this->entregado->AdvancedSearch->unsetSession();
                $this->fecha_entrega->AdvancedSearch->unsetSession();
                $this->pagado->AdvancedSearch->unsetSession();
                $this->bultos->AdvancedSearch->unsetSession();
                $this->fecha_bultos->AdvancedSearch->unsetSession();
                $this->user_bultos->AdvancedSearch->unsetSession();
                $this->fecha_despacho->AdvancedSearch->unsetSession();
                $this->user_despacho->AdvancedSearch->unsetSession();
                $this->consignacion->AdvancedSearch->unsetSession();
                $this->unidades->AdvancedSearch->unsetSession();
                $this->descuento->AdvancedSearch->unsetSession();
                $this->monto_sin_descuento->AdvancedSearch->unsetSession();
                $this->factura->AdvancedSearch->unsetSession();
                $this->ci_rif->AdvancedSearch->unsetSession();
                $this->nombre->AdvancedSearch->unsetSession();
                $this->direccion->AdvancedSearch->unsetSession();
                $this->telefono->AdvancedSearch->unsetSession();
                $this->_email->AdvancedSearch->unsetSession();
                $this->activo->AdvancedSearch->unsetSession();
                $this->comprobante->AdvancedSearch->unsetSession();
                $this->nro_despacho->AdvancedSearch->unsetSession();
                $this->cerrado->AdvancedSearch->unsetSession();
                $this->impreso->AdvancedSearch->unsetSession();
                $this->igtf->AdvancedSearch->unsetSession();
                $this->monto_base_igtf->AdvancedSearch->unsetSession();
                $this->monto_igtf->AdvancedSearch->unsetSession();
                $this->pago_premio->AdvancedSearch->unsetSession();
    }

    // Restore all search parameters
    protected function restoreSearchParms()
    {
        $this->RestoreSearch = true;

        // Restore basic search values
        $this->BasicSearch->load();

        // Restore advanced search values
                $this->id->AdvancedSearch->load();
                $this->tipo_documento->AdvancedSearch->load();
                $this->nro_documento->AdvancedSearch->load();
                $this->nro_control->AdvancedSearch->load();
                $this->fecha->AdvancedSearch->load();
                $this->cliente->AdvancedSearch->load();
                $this->documento->AdvancedSearch->load();
                $this->doc_afectado->AdvancedSearch->load();
                $this->moneda->AdvancedSearch->load();
                $this->monto_total->AdvancedSearch->load();
                $this->alicuota_iva->AdvancedSearch->load();
                $this->iva->AdvancedSearch->load();
                $this->total->AdvancedSearch->load();
                $this->tasa_dia->AdvancedSearch->load();
                $this->monto_usd->AdvancedSearch->load();
                $this->lista_pedido->AdvancedSearch->load();
                $this->nota->AdvancedSearch->load();
                $this->_username->AdvancedSearch->load();
                $this->estatus->AdvancedSearch->load();
                $this->id_documento_padre->AdvancedSearch->load();
                $this->asesor->AdvancedSearch->load();
                $this->dias_credito->AdvancedSearch->load();
                $this->entregado->AdvancedSearch->load();
                $this->fecha_entrega->AdvancedSearch->load();
                $this->pagado->AdvancedSearch->load();
                $this->bultos->AdvancedSearch->load();
                $this->fecha_bultos->AdvancedSearch->load();
                $this->user_bultos->AdvancedSearch->load();
                $this->fecha_despacho->AdvancedSearch->load();
                $this->user_despacho->AdvancedSearch->load();
                $this->consignacion->AdvancedSearch->load();
                $this->unidades->AdvancedSearch->load();
                $this->descuento->AdvancedSearch->load();
                $this->monto_sin_descuento->AdvancedSearch->load();
                $this->factura->AdvancedSearch->load();
                $this->ci_rif->AdvancedSearch->load();
                $this->nombre->AdvancedSearch->load();
                $this->direccion->AdvancedSearch->load();
                $this->telefono->AdvancedSearch->load();
                $this->_email->AdvancedSearch->load();
                $this->activo->AdvancedSearch->load();
                $this->comprobante->AdvancedSearch->load();
                $this->nro_despacho->AdvancedSearch->load();
                $this->cerrado->AdvancedSearch->load();
                $this->impreso->AdvancedSearch->load();
                $this->igtf->AdvancedSearch->load();
                $this->monto_base_igtf->AdvancedSearch->load();
                $this->monto_igtf->AdvancedSearch->load();
                $this->pago_premio->AdvancedSearch->load();
    }

    // Set up sort parameters
    protected function setupSortOrder()
    {
        // Check for "order" parameter
        if (Get("order") !== null) {
            $this->CurrentOrder = Get("order");
            $this->CurrentOrderType = Get("ordertype", "");
            $this->updateSort($this->tipo_documento); // tipo_documento
            $this->updateSort($this->nro_documento); // nro_documento
            $this->updateSort($this->fecha); // fecha
            $this->updateSort($this->cliente); // cliente
            $this->updateSort($this->documento); // documento
            $this->updateSort($this->doc_afectado); // doc_afectado
            $this->updateSort($this->monto_total); // monto_total
            $this->updateSort($this->alicuota_iva); // alicuota_iva
            $this->updateSort($this->iva); // iva
            $this->updateSort($this->total); // total
            $this->updateSort($this->lista_pedido); // lista_pedido
            $this->updateSort($this->_username); // username
            $this->updateSort($this->estatus); // estatus
            $this->updateSort($this->asesor); // asesor
            $this->updateSort($this->unidades); // unidades
            $this->updateSort($this->nro_despacho); // nro_despacho
            $this->updateSort($this->pago_premio); // pago_premio
            $this->setStartRecordNumber(1); // Reset start position
        }
    }

    // Load sort order parameters
    protected function loadSortOrder()
    {
        $orderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
        if ($orderBy == "") {
            $this->DefaultSort = "`id` DESC";
            if ($this->getSqlOrderBy() != "") {
                $useDefaultSort = true;
                if ($this->id->getSort() != "") {
                    $useDefaultSort = false;
                }
                if ($useDefaultSort) {
                    $this->id->setSort("DESC");
                    $orderBy = $this->getSqlOrderBy();
                    $this->setSessionOrderBy($orderBy);
                } else {
                    $this->setSessionOrderBy("");
                }
            }
        }
    }

    // Reset command
    // - cmd=reset (Reset search parameters)
    // - cmd=resetall (Reset search and master/detail parameters)
    // - cmd=resetsort (Reset sort parameters)
    protected function resetCmd()
    {
        // Check if reset command
        if (StartsString("reset", $this->Command)) {
            // Reset search criteria
            if ($this->Command == "reset" || $this->Command == "resetall") {
                $this->resetSearchParms();
            }

            // Reset (clear) sorting order
            if ($this->Command == "resetsort") {
                $orderBy = "";
                $this->setSessionOrderBy($orderBy);
                $this->id->setSort("");
                $this->tipo_documento->setSort("");
                $this->nro_documento->setSort("");
                $this->nro_control->setSort("");
                $this->fecha->setSort("");
                $this->cliente->setSort("");
                $this->documento->setSort("");
                $this->doc_afectado->setSort("");
                $this->moneda->setSort("");
                $this->monto_total->setSort("");
                $this->alicuota_iva->setSort("");
                $this->iva->setSort("");
                $this->total->setSort("");
                $this->tasa_dia->setSort("");
                $this->monto_usd->setSort("");
                $this->lista_pedido->setSort("");
                $this->nota->setSort("");
                $this->_username->setSort("");
                $this->estatus->setSort("");
                $this->id_documento_padre->setSort("");
                $this->asesor->setSort("");
                $this->dias_credito->setSort("");
                $this->entregado->setSort("");
                $this->fecha_entrega->setSort("");
                $this->pagado->setSort("");
                $this->bultos->setSort("");
                $this->fecha_bultos->setSort("");
                $this->user_bultos->setSort("");
                $this->fecha_despacho->setSort("");
                $this->user_despacho->setSort("");
                $this->consignacion->setSort("");
                $this->unidades->setSort("");
                $this->descuento->setSort("");
                $this->monto_sin_descuento->setSort("");
                $this->factura->setSort("");
                $this->ci_rif->setSort("");
                $this->nombre->setSort("");
                $this->direccion->setSort("");
                $this->telefono->setSort("");
                $this->_email->setSort("");
                $this->activo->setSort("");
                $this->comprobante->setSort("");
                $this->nro_despacho->setSort("");
                $this->cerrado->setSort("");
                $this->impreso->setSort("");
                $this->igtf->setSort("");
                $this->monto_base_igtf->setSort("");
                $this->monto_igtf->setSort("");
                $this->pago_premio->setSort("");
            }

            // Reset start position
            $this->StartRecord = 1;
            $this->setStartRecordNumber($this->StartRecord);
        }
    }

    // Set up list options
    protected function setupListOptions()
    {
        global $Security, $Language;

        // Add group option item
        $item = &$this->ListOptions->add($this->ListOptions->GroupOptionName);
        $item->Body = "";
        $item->OnLeft = true;
        $item->Visible = false;

        // "view"
        $item = &$this->ListOptions->add("view");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canView();
        $item->OnLeft = true;

        // "edit"
        $item = &$this->ListOptions->add("edit");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canEdit();
        $item->OnLeft = true;

        // "copy"
        $item = &$this->ListOptions->add("copy");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canAdd();
        $item->OnLeft = true;

        // "delete"
        $item = &$this->ListOptions->add("delete");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canDelete();
        $item->OnLeft = true;

        // "detail_entradas_salidas"
        $item = &$this->ListOptions->add("detail_entradas_salidas");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->allowList(CurrentProjectID() . 'entradas_salidas') && !$this->ShowMultipleDetails;
        $item->OnLeft = true;
        $item->ShowInButtonGroup = false;

        // "detail_pagos"
        $item = &$this->ListOptions->add("detail_pagos");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->allowList(CurrentProjectID() . 'pagos') && !$this->ShowMultipleDetails;
        $item->OnLeft = true;
        $item->ShowInButtonGroup = false;

        // Multiple details
        if ($this->ShowMultipleDetails) {
            $item = &$this->ListOptions->add("details");
            $item->CssClass = "text-nowrap";
            $item->Visible = $this->ShowMultipleDetails;
            $item->OnLeft = true;
            $item->ShowInButtonGroup = false;
        }

        // Set up detail pages
        $pages = new SubPages();
        $pages->add("entradas_salidas");
        $pages->add("pagos");
        $this->DetailPages = $pages;

        // List actions
        $item = &$this->ListOptions->add("listactions");
        $item->CssClass = "text-nowrap";
        $item->OnLeft = true;
        $item->Visible = false;
        $item->ShowInButtonGroup = false;
        $item->ShowInDropDown = false;

        // "checkbox"
        $item = &$this->ListOptions->add("checkbox");
        $item->Visible = false;
        $item->OnLeft = true;
        $item->Header = "<div class=\"custom-control custom-checkbox d-inline-block\"><input type=\"checkbox\" name=\"key\" id=\"key\" class=\"custom-control-input\" onclick=\"ew.selectAllKey(this);\"><label class=\"custom-control-label\" for=\"key\"></label></div>";
        $item->moveTo(0);
        $item->ShowInDropDown = false;
        $item->ShowInButtonGroup = false;

        // Drop down button for ListOptions
        $this->ListOptions->UseDropDownButton = false;
        $this->ListOptions->DropDownButtonPhrase = $Language->phrase("ButtonListOptions");
        $this->ListOptions->UseButtonGroup = false;
        if ($this->ListOptions->UseButtonGroup && IsMobile()) {
            $this->ListOptions->UseDropDownButton = true;
        }

        //$this->ListOptions->ButtonClass = ""; // Class for button group

        // Call ListOptions_Load event
        $this->listOptionsLoad();
        $this->setupListOptionsExt();
        $item = $this->ListOptions[$this->ListOptions->GroupOptionName];
        $item->Visible = $this->ListOptions->groupOptionVisible();
    }

    // Render list options
    public function renderListOptions()
    {
        global $Security, $Language, $CurrentForm;
        $this->ListOptions->loadDefault();

        // Call ListOptions_Rendering event
        $this->listOptionsRendering();
        $pageUrl = $this->pageUrl();
        if ($this->CurrentMode == "view") {
            // "view"
            $opt = $this->ListOptions["view"];
            $viewcaption = HtmlTitle($Language->phrase("ViewLink"));
            if ($Security->canView()) {
                $opt->Body = "<a class=\"ew-row-link ew-view\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . HtmlEncode(GetUrl($this->ViewUrl)) . "\">" . $Language->phrase("ViewLink") . "</a>";
            } else {
                $opt->Body = "";
            }

            // "edit"
            $opt = $this->ListOptions["edit"];
            $editcaption = HtmlTitle($Language->phrase("EditLink"));
            if ($Security->canEdit()) {
                $opt->Body = "<a class=\"ew-row-link ew-edit\" title=\"" . HtmlTitle($Language->phrase("EditLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("EditLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->EditUrl)) . "\">" . $Language->phrase("EditLink") . "</a>";
            } else {
                $opt->Body = "";
            }

            // "copy"
            $opt = $this->ListOptions["copy"];
            $copycaption = HtmlTitle($Language->phrase("CopyLink"));
            if ($Security->canAdd()) {
                $opt->Body = "<a class=\"ew-row-link ew-copy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . HtmlEncode(GetUrl($this->CopyUrl)) . "\">" . $Language->phrase("CopyLink") . "</a>";
            } else {
                $opt->Body = "";
            }

            // "delete"
            $opt = $this->ListOptions["delete"];
            if ($Security->canDelete()) {
            $opt->Body = "<a class=\"ew-row-link ew-delete\"" . "" . " title=\"" . HtmlTitle($Language->phrase("DeleteLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("DeleteLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->DeleteUrl)) . "\">" . $Language->phrase("DeleteLink") . "</a>";
            } else {
                $opt->Body = "";
            }
        } // End View mode

        // Set up list action buttons
        $opt = $this->ListOptions["listactions"];
        if ($opt && !$this->isExport() && !$this->CurrentAction) {
            $body = "";
            $links = [];
            foreach ($this->ListActions->Items as $listaction) {
                if ($listaction->Select == ACTION_SINGLE && $listaction->Allow) {
                    $action = $listaction->Action;
                    $caption = $listaction->Caption;
                    $icon = ($listaction->Icon != "") ? "<i class=\"" . HtmlEncode(str_replace(" ew-icon", "", $listaction->Icon)) . "\" data-caption=\"" . HtmlTitle($caption) . "\"></i> " : "";
                    $links[] = "<li><a class=\"dropdown-item ew-action ew-list-action\" data-action=\"" . HtmlEncode($action) . "\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"#\" onclick=\"return ew.submitAction(event,jQuery.extend({key:" . $this->keyToJson(true) . "}," . $listaction->toJson(true) . "));\">" . $icon . $listaction->Caption . "</a></li>";
                    if (count($links) == 1) { // Single button
                        $body = "<a class=\"ew-action ew-list-action\" data-action=\"" . HtmlEncode($action) . "\" title=\"" . HtmlTitle($caption) . "\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"#\" onclick=\"return ew.submitAction(event,jQuery.extend({key:" . $this->keyToJson(true) . "}," . $listaction->toJson(true) . "));\">" . $icon . $listaction->Caption . "</a>";
                    }
                }
            }
            if (count($links) > 1) { // More than one buttons, use dropdown
                $body = "<button class=\"dropdown-toggle btn btn-default ew-actions\" title=\"" . HtmlTitle($Language->phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->phrase("ListActionButton") . "</button>";
                $content = "";
                foreach ($links as $link) {
                    $content .= "<li>" . $link . "</li>";
                }
                $body .= "<ul class=\"dropdown-menu" . ($opt->OnLeft ? "" : " dropdown-menu-right") . "\">" . $content . "</ul>";
                $body = "<div class=\"btn-group btn-group-sm\">" . $body . "</div>";
            }
            if (count($links) > 0) {
                $opt->Body = $body;
                $opt->Visible = true;
            }
        }
        $detailViewTblVar = "";
        $detailCopyTblVar = "";
        $detailEditTblVar = "";

        // "detail_entradas_salidas"
        $opt = $this->ListOptions["detail_entradas_salidas"];
        if ($Security->allowList(CurrentProjectID() . 'entradas_salidas')) {
            $body = $Language->phrase("DetailLink") . $Language->TablePhrase("entradas_salidas", "TblCaption");
            $body = "<a class=\"btn btn-default ew-row-link ew-detail\" data-action=\"list\" href=\"" . HtmlEncode("EntradasSalidasList?" . Config("TABLE_SHOW_MASTER") . "=salidas&" . GetForeignKeyUrl("fk_tipo_documento", $this->tipo_documento->CurrentValue) . "&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue) . "") . "\">" . $body . "</a>";
            $links = "";
            $detailPage = Container("EntradasSalidasGrid");
            if ($detailPage->DetailView && $Security->canView() && $Security->allowView(CurrentProjectID() . 'salidas')) {
                $caption = $Language->phrase("MasterDetailViewLink");
                $url = $this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=entradas_salidas");
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-view\" data-action=\"view\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode($url) . "\">" . HtmlImageAndText($caption) . "</a></li>";
                if ($detailViewTblVar != "") {
                    $detailViewTblVar .= ",";
                }
                $detailViewTblVar .= "entradas_salidas";
            }
            if ($detailPage->DetailEdit && $Security->canEdit() && $Security->allowEdit(CurrentProjectID() . 'salidas')) {
                $caption = $Language->phrase("MasterDetailEditLink");
                $url = $this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=entradas_salidas");
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-edit\" data-action=\"edit\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode($url) . "\">" . HtmlImageAndText($caption) . "</a></li>";
                if ($detailEditTblVar != "") {
                    $detailEditTblVar .= ",";
                }
                $detailEditTblVar .= "entradas_salidas";
            }
            if ($detailPage->DetailAdd && $Security->canAdd() && $Security->allowAdd(CurrentProjectID() . 'salidas')) {
                $caption = $Language->phrase("MasterDetailCopyLink");
                $url = $this->getCopyUrl(Config("TABLE_SHOW_DETAIL") . "=entradas_salidas");
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-copy\" data-action=\"add\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode($url) . "\">" . HtmlImageAndText($caption) . "</a></li>";
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
            $opt->Body = $body;
            if ($this->ShowMultipleDetails) {
                $opt->Visible = false;
            }
        }

        // "detail_pagos"
        $opt = $this->ListOptions["detail_pagos"];
        if ($Security->allowList(CurrentProjectID() . 'pagos')) {
            $body = $Language->phrase("DetailLink") . $Language->TablePhrase("pagos", "TblCaption");
            $body = "<a class=\"btn btn-default ew-row-link ew-detail\" data-action=\"list\" href=\"" . HtmlEncode("PagosList?" . Config("TABLE_SHOW_MASTER") . "=salidas&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue) . "&" . GetForeignKeyUrl("fk_tipo_documento", $this->tipo_documento->CurrentValue) . "") . "\">" . $body . "</a>";
            $links = "";
            $detailPage = Container("PagosGrid");
            if ($detailPage->DetailView && $Security->canView() && $Security->allowView(CurrentProjectID() . 'salidas')) {
                $caption = $Language->phrase("MasterDetailViewLink");
                $url = $this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=pagos");
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-view\" data-action=\"view\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode($url) . "\">" . HtmlImageAndText($caption) . "</a></li>";
                if ($detailViewTblVar != "") {
                    $detailViewTblVar .= ",";
                }
                $detailViewTblVar .= "pagos";
            }
            if ($detailPage->DetailEdit && $Security->canEdit() && $Security->allowEdit(CurrentProjectID() . 'salidas')) {
                $caption = $Language->phrase("MasterDetailEditLink");
                $url = $this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=pagos");
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-edit\" data-action=\"edit\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode($url) . "\">" . HtmlImageAndText($caption) . "</a></li>";
                if ($detailEditTblVar != "") {
                    $detailEditTblVar .= ",";
                }
                $detailEditTblVar .= "pagos";
            }
            if ($detailPage->DetailAdd && $Security->canAdd() && $Security->allowAdd(CurrentProjectID() . 'salidas')) {
                $caption = $Language->phrase("MasterDetailCopyLink");
                $url = $this->getCopyUrl(Config("TABLE_SHOW_DETAIL") . "=pagos");
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-copy\" data-action=\"add\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode($url) . "\">" . HtmlImageAndText($caption) . "</a></li>";
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
            $opt->Body = $body;
            if ($this->ShowMultipleDetails) {
                $opt->Visible = false;
            }
        }
        if ($this->ShowMultipleDetails) {
            $body = "<div class=\"btn-group btn-group-sm ew-btn-group\">";
            $links = "";
            if ($detailViewTblVar != "") {
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-view\" data-action=\"view\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailViewLink")) . "\" href=\"" . HtmlEncode($this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=" . $detailViewTblVar)) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailViewLink")) . "</a></li>";
            }
            if ($detailEditTblVar != "") {
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-edit\" data-action=\"edit\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailEditLink")) . "\" href=\"" . HtmlEncode($this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=" . $detailEditTblVar)) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailEditLink")) . "</a></li>";
            }
            if ($detailCopyTblVar != "") {
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-copy\" data-action=\"add\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailCopyLink")) . "\" href=\"" . HtmlEncode($this->GetCopyUrl(Config("TABLE_SHOW_DETAIL") . "=" . $detailCopyTblVar)) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailCopyLink")) . "</a></li>";
            }
            if ($links != "") {
                $body .= "<button class=\"dropdown-toggle btn btn-default ew-master-detail\" title=\"" . HtmlTitle($Language->phrase("MultipleMasterDetails")) . "\" data-toggle=\"dropdown\">" . $Language->phrase("MultipleMasterDetails") . "</button>";
                $body .= "<ul class=\"dropdown-menu ew-menu\">" . $links . "</ul>";
            }
            $body .= "</div>";
            // Multiple details
            $opt = $this->ListOptions["details"];
            $opt->Body = $body;
        }

        // "checkbox"
        $opt = $this->ListOptions["checkbox"];
        $opt->Body = "<div class=\"custom-control custom-checkbox d-inline-block\"><input type=\"checkbox\" id=\"key_m_" . $this->RowCount . "\" name=\"key_m[]\" class=\"custom-control-input ew-multi-select\" value=\"" . HtmlEncode($this->id->CurrentValue) . "\" onclick=\"ew.clickMultiCheckbox(event);\"><label class=\"custom-control-label\" for=\"key_m_" . $this->RowCount . "\"></label></div>";
        $this->renderListOptionsExt();

        // Call ListOptions_Rendered event
        $this->listOptionsRendered();
    }

    // Set up other options
    protected function setupOtherOptions()
    {
        global $Language, $Security;
        $options = &$this->OtherOptions;
        $option = $options["addedit"];

        // Add
        $item = &$option->add("add");
        $addcaption = HtmlTitle($Language->phrase("AddLink"));
        $item->Body = "<a class=\"ew-add-edit ew-add\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . HtmlEncode(GetUrl($this->AddUrl)) . "\">" . $Language->phrase("AddLink") . "</a>";
        $item->Visible = $this->AddUrl != "" && $Security->canAdd();
        $option = $options["detail"];
        $detailTableLink = "";
                $item = &$option->add("detailadd_entradas_salidas");
                $url = $this->getAddUrl(Config("TABLE_SHOW_DETAIL") . "=entradas_salidas");
                $detailPage = Container("EntradasSalidasGrid");
                $caption = $Language->phrase("Add") . "&nbsp;" . $this->tableCaption() . "/" . $detailPage->tableCaption();
                $item->Body = "<a class=\"ew-detail-add-group ew-detail-add\" title=\"" . HtmlTitle($caption) . "\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode(GetUrl($url)) . "\">" . $caption . "</a>";
                $item->Visible = ($detailPage->DetailAdd && $Security->allowAdd(CurrentProjectID() . 'salidas') && $Security->canAdd());
                if ($item->Visible) {
                    if ($detailTableLink != "") {
                        $detailTableLink .= ",";
                    }
                    $detailTableLink .= "entradas_salidas";
                }
                $item = &$option->add("detailadd_pagos");
                $url = $this->getAddUrl(Config("TABLE_SHOW_DETAIL") . "=pagos");
                $detailPage = Container("PagosGrid");
                $caption = $Language->phrase("Add") . "&nbsp;" . $this->tableCaption() . "/" . $detailPage->tableCaption();
                $item->Body = "<a class=\"ew-detail-add-group ew-detail-add\" title=\"" . HtmlTitle($caption) . "\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode(GetUrl($url)) . "\">" . $caption . "</a>";
                $item->Visible = ($detailPage->DetailAdd && $Security->allowAdd(CurrentProjectID() . 'salidas') && $Security->canAdd());
                if ($item->Visible) {
                    if ($detailTableLink != "") {
                        $detailTableLink .= ",";
                    }
                    $detailTableLink .= "pagos";
                }

        // Add multiple details
        if ($this->ShowMultipleDetails) {
            $item = &$option->add("detailsadd");
            $url = $this->getAddUrl(Config("TABLE_SHOW_DETAIL") . "=" . $detailTableLink);
            $caption = $Language->phrase("AddMasterDetailLink");
            $item->Body = "<a class=\"ew-detail-add-group ew-detail-add\" title=\"" . HtmlTitle($caption) . "\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode(GetUrl($url)) . "\">" . $caption . "</a>";
            $item->Visible = $detailTableLink != "" && $Security->canAdd();
            // Hide single master/detail items
            $ar = explode(",", $detailTableLink);
            $cnt = count($ar);
            for ($i = 0; $i < $cnt; $i++) {
                if ($item = $option["detailadd_" . $ar[$i]]) {
                    $item->Visible = false;
                }
            }
        }
        $option = $options["action"];

        // Set up options default
        foreach ($options as $option) {
            $option->UseDropDownButton = false;
            $option->UseButtonGroup = true;
            //$option->ButtonClass = ""; // Class for button group
            $item = &$option->add($option->GroupOptionName);
            $item->Body = "";
            $item->Visible = false;
        }
        $options["addedit"]->DropDownButtonPhrase = $Language->phrase("ButtonAddEdit");
        $options["detail"]->DropDownButtonPhrase = $Language->phrase("ButtonDetails");
        $options["action"]->DropDownButtonPhrase = $Language->phrase("ButtonActions");

        // Filter button
        $item = &$this->FilterOptions->add("savecurrentfilter");
        $item->Body = "<a class=\"ew-save-filter\" data-form=\"fsalidaslistsrch\" href=\"#\" onclick=\"return false;\">" . $Language->phrase("SaveCurrentFilter") . "</a>";
        $item->Visible = true;
        $item = &$this->FilterOptions->add("deletefilter");
        $item->Body = "<a class=\"ew-delete-filter\" data-form=\"fsalidaslistsrch\" href=\"#\" onclick=\"return false;\">" . $Language->phrase("DeleteFilter") . "</a>";
        $item->Visible = true;
        $this->FilterOptions->UseDropDownButton = true;
        $this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
        $this->FilterOptions->DropDownButtonPhrase = $Language->phrase("Filters");

        // Add group option item
        $item = &$this->FilterOptions->add($this->FilterOptions->GroupOptionName);
        $item->Body = "";
        $item->Visible = false;
    }

    // Render other options
    public function renderOtherOptions()
    {
        global $Language, $Security;
        $options = &$this->OtherOptions;
        $option = $options["action"];
        // Set up list action buttons
        foreach ($this->ListActions->Items as $listaction) {
            if ($listaction->Select == ACTION_MULTIPLE) {
                $item = &$option->add("custom_" . $listaction->Action);
                $caption = $listaction->Caption;
                $icon = ($listaction->Icon != "") ? '<i class="' . HtmlEncode($listaction->Icon) . '" data-caption="' . HtmlEncode($caption) . '"></i>' . $caption : $caption;
                $item->Body = '<a class="ew-action ew-list-action" title="' . HtmlEncode($caption) . '" data-caption="' . HtmlEncode($caption) . '" href="#" onclick="return ew.submitAction(event,jQuery.extend({f:document.fsalidaslist},' . $listaction->toJson(true) . '));">' . $icon . '</a>';
                $item->Visible = $listaction->Allow;
            }
        }

        // Hide grid edit and other options
        if ($this->TotalRecords <= 0) {
            $option = $options["addedit"];
            $item = $option["gridedit"];
            if ($item) {
                $item->Visible = false;
            }
            $option = $options["action"];
            $option->hideAllOptions();
        }
    }

    // Process list action
    protected function processListAction()
    {
        global $Language, $Security;
        $userlist = "";
        $user = "";
        $filter = $this->getFilterFromRecordKeys();
        $userAction = Post("useraction", "");
        if ($filter != "" && $userAction != "") {
            // Check permission first
            $actionCaption = $userAction;
            if (array_key_exists($userAction, $this->ListActions->Items)) {
                $actionCaption = $this->ListActions[$userAction]->Caption;
                if (!$this->ListActions[$userAction]->Allow) {
                    $errmsg = str_replace('%s', $actionCaption, $Language->phrase("CustomActionNotAllowed"));
                    if (Post("ajax") == $userAction) { // Ajax
                        echo "<p class=\"text-danger\">" . $errmsg . "</p>";
                        return true;
                    } else {
                        $this->setFailureMessage($errmsg);
                        return false;
                    }
                }
            }
            $this->CurrentFilter = $filter;
            $sql = $this->getCurrentSql();
            $conn = $this->getConnection();
            $rs = LoadRecordset($sql, $conn, \PDO::FETCH_ASSOC);
            $this->CurrentAction = $userAction;

            // Call row action event
            if ($rs) {
                $conn->beginTransaction();
                $this->SelectedCount = $rs->recordCount();
                $this->SelectedIndex = 0;
                while (!$rs->EOF) {
                    $this->SelectedIndex++;
                    $row = $rs->fields;
                    $processed = $this->rowCustomAction($userAction, $row);
                    if (!$processed) {
                        break;
                    }
                    $rs->moveNext();
                }
                if ($processed) {
                    $conn->commit(); // Commit the changes
                    if ($this->getSuccessMessage() == "" && !ob_get_length()) { // No output
                        $this->setSuccessMessage(str_replace('%s', $actionCaption, $Language->phrase("CustomActionCompleted"))); // Set up success message
                    }
                } else {
                    $conn->rollback(); // Rollback changes

                    // Set up error message
                    if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                        // Use the message, do nothing
                    } elseif ($this->CancelMessage != "") {
                        $this->setFailureMessage($this->CancelMessage);
                        $this->CancelMessage = "";
                    } else {
                        $this->setFailureMessage(str_replace('%s', $actionCaption, $Language->phrase("CustomActionFailed")));
                    }
                }
            }
            if ($rs) {
                $rs->close();
            }
            $this->CurrentAction = ""; // Clear action
            if (Post("ajax") == $userAction) { // Ajax
                if ($this->getSuccessMessage() != "") {
                    echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
                    $this->clearSuccessMessage(); // Clear message
                }
                if ($this->getFailureMessage() != "") {
                    echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
                    $this->clearFailureMessage(); // Clear message
                }
                return true;
            }
        }
        return false; // Not ajax request
    }

    // Set up list options (extended codes)
    protected function setupListOptionsExt()
    {
        // Hide detail items for dropdown if necessary
        $this->ListOptions->hideDetailItemsForDropDown();
    }

    // Render list options (extended codes)
    protected function renderListOptionsExt()
    {
        global $Security, $Language;
        $links = "";
        $btngrps = "";
        $sqlwrk = "`tipo_documento`='" . AdjustSql($this->tipo_documento->CurrentValue, $this->Dbid) . "'";
        $sqlwrk = $sqlwrk . " AND " . "`id_documento`=" . AdjustSql($this->id->CurrentValue, $this->Dbid) . "";

        // Column "detail_entradas_salidas"
        if ($this->DetailPages && $this->DetailPages["entradas_salidas"] && $this->DetailPages["entradas_salidas"]->Visible && $Security->allowList(CurrentProjectID() . 'entradas_salidas')) {
            $link = "";
            $option = $this->ListOptions["detail_entradas_salidas"];
            $url = "EntradasSalidasPreview?t=salidas&f=" . Encrypt($sqlwrk);
            $btngrp = "<div data-table=\"entradas_salidas\" data-url=\"" . $url . "\">";
            if ($Security->allowList(CurrentProjectID() . 'salidas')) {
                $label = $Language->TablePhrase("entradas_salidas", "TblCaption");
                $link = "<li class=\"nav-item\"><a href=\"#\" class=\"nav-link\" data-toggle=\"tab\" data-table=\"entradas_salidas\" data-url=\"" . $url . "\">" . $label . "</a></li>";
                $links .= $link;
                $detaillnk = JsEncodeAttribute("EntradasSalidasList?" . Config("TABLE_SHOW_MASTER") . "=salidas&" . GetForeignKeyUrl("fk_tipo_documento", $this->tipo_documento->CurrentValue) . "&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue) . "");
                $btngrp .= "<a href=\"#\" class=\"mr-2\" title=\"" . $Language->TablePhrase("entradas_salidas", "TblCaption") . "\" onclick=\"window.location='" . $detaillnk . "';return false;\">" . $Language->phrase("MasterDetailListLink") . "</a>";
            }
            $detailPageObj = Container("EntradasSalidasGrid");
            if ($detailPageObj->DetailView && $Security->canView() && $Security->allowView(CurrentProjectID() . 'salidas')) {
                $caption = $Language->phrase("MasterDetailViewLink");
                $url = $this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=entradas_salidas");
                $btngrp .= "<a href=\"#\" class=\"mr-2\" title=\"" . HtmlTitle($caption) . "\" onclick=\"window.location='" . HtmlEncode($url) . "';return false;\">" . $caption . "</a>";
            }
            if ($detailPageObj->DetailEdit && $Security->canEdit() && $Security->allowEdit(CurrentProjectID() . 'salidas')) {
                $caption = $Language->phrase("MasterDetailEditLink");
                $url = $this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=entradas_salidas");
                $btngrp .= "<a href=\"#\" class=\"mr-2\" title=\"" . HtmlTitle($caption) . "\" onclick=\"window.location='" . HtmlEncode($url) . "';return false;\">" . $caption . "</a>";
            }
            $btngrp .= "</div>";
            if ($link != "") {
                $btngrps .= $btngrp;
                $option->Body .= "<div class=\"d-none ew-preview\">" . $link . $btngrp . "</div>";
            }
        }
        $sqlwrk = "`id_documento`=" . AdjustSql($this->id->CurrentValue, $this->Dbid) . "";
        $sqlwrk = $sqlwrk . " AND " . "`tipo_documento`='" . AdjustSql($this->tipo_documento->CurrentValue, $this->Dbid) . "'";

        // Column "detail_pagos"
        if ($this->DetailPages && $this->DetailPages["pagos"] && $this->DetailPages["pagos"]->Visible && $Security->allowList(CurrentProjectID() . 'pagos')) {
            $link = "";
            $option = $this->ListOptions["detail_pagos"];
            $url = "PagosPreview?t=salidas&f=" . Encrypt($sqlwrk);
            $btngrp = "<div data-table=\"pagos\" data-url=\"" . $url . "\">";
            if ($Security->allowList(CurrentProjectID() . 'salidas')) {
                $label = $Language->TablePhrase("pagos", "TblCaption");
                $link = "<li class=\"nav-item\"><a href=\"#\" class=\"nav-link\" data-toggle=\"tab\" data-table=\"pagos\" data-url=\"" . $url . "\">" . $label . "</a></li>";
                $links .= $link;
                $detaillnk = JsEncodeAttribute("PagosList?" . Config("TABLE_SHOW_MASTER") . "=salidas&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue) . "&" . GetForeignKeyUrl("fk_tipo_documento", $this->tipo_documento->CurrentValue) . "");
                $btngrp .= "<a href=\"#\" class=\"mr-2\" title=\"" . $Language->TablePhrase("pagos", "TblCaption") . "\" onclick=\"window.location='" . $detaillnk . "';return false;\">" . $Language->phrase("MasterDetailListLink") . "</a>";
            }
            $detailPageObj = Container("PagosGrid");
            if ($detailPageObj->DetailView && $Security->canView() && $Security->allowView(CurrentProjectID() . 'salidas')) {
                $caption = $Language->phrase("MasterDetailViewLink");
                $url = $this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=pagos");
                $btngrp .= "<a href=\"#\" class=\"mr-2\" title=\"" . HtmlTitle($caption) . "\" onclick=\"window.location='" . HtmlEncode($url) . "';return false;\">" . $caption . "</a>";
            }
            if ($detailPageObj->DetailEdit && $Security->canEdit() && $Security->allowEdit(CurrentProjectID() . 'salidas')) {
                $caption = $Language->phrase("MasterDetailEditLink");
                $url = $this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=pagos");
                $btngrp .= "<a href=\"#\" class=\"mr-2\" title=\"" . HtmlTitle($caption) . "\" onclick=\"window.location='" . HtmlEncode($url) . "';return false;\">" . $caption . "</a>";
            }
            $btngrp .= "</div>";
            if ($link != "") {
                $btngrps .= $btngrp;
                $option->Body .= "<div class=\"d-none ew-preview\">" . $link . $btngrp . "</div>";
            }
        }

        // Hide detail items if necessary
        $this->ListOptions->hideDetailItemsForDropDown();

        // Column "preview"
        $option = $this->ListOptions["preview"];
        if (!$option) { // Add preview column
            $option = &$this->ListOptions->add("preview");
            $option->OnLeft = true;
            if ($option->OnLeft) {
                $option->moveTo($this->ListOptions->itemPos("checkbox") + 1);
            } else {
                $option->moveTo($this->ListOptions->itemPos("checkbox"));
            }
            $option->Visible = !($this->isExport() || $this->isGridAdd() || $this->isGridEdit());
            $option->ShowInDropDown = false;
            $option->ShowInButtonGroup = false;
        }
        if ($option) {
            $option->Body = "<i class=\"ew-preview-row-btn ew-icon icon-expand\"></i>";
            $option->Body .= "<div class=\"d-none ew-preview\">" . $links . $btngrps . "</div>";
            if ($option->Visible) {
                $option->Visible = $links != "";
            }
        }

        // Column "details" (Multiple details)
        $option = $this->ListOptions["details"];
        if ($option) {
            $option->Body .= "<div class=\"d-none ew-preview\">" . $links . $btngrps . "</div>";
            if ($option->Visible) {
                $option->Visible = $links != "";
            }
        }
    }

    // Load basic search values
    protected function loadBasicSearchValues()
    {
        $this->BasicSearch->setKeyword(Get(Config("TABLE_BASIC_SEARCH"), ""), false);
        if ($this->BasicSearch->Keyword != "" && $this->Command == "") {
            $this->Command = "search";
        }
        $this->BasicSearch->setType(Get(Config("TABLE_BASIC_SEARCH_TYPE"), ""), false);
    }

    // Load search values for validation
    protected function loadSearchValues()
    {
        // Load search values
        $hasValue = false;

        // id
        if (!$this->isAddOrEdit() && $this->id->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->id->AdvancedSearch->SearchValue != "" || $this->id->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // tipo_documento
        if (!$this->isAddOrEdit() && $this->tipo_documento->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->tipo_documento->AdvancedSearch->SearchValue != "" || $this->tipo_documento->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // nro_documento
        if (!$this->isAddOrEdit() && $this->nro_documento->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->nro_documento->AdvancedSearch->SearchValue != "" || $this->nro_documento->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // nro_control
        if (!$this->isAddOrEdit() && $this->nro_control->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->nro_control->AdvancedSearch->SearchValue != "" || $this->nro_control->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // fecha
        if (!$this->isAddOrEdit() && $this->fecha->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->fecha->AdvancedSearch->SearchValue != "" || $this->fecha->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // cliente
        if (!$this->isAddOrEdit() && $this->cliente->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->cliente->AdvancedSearch->SearchValue != "" || $this->cliente->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // documento
        if (!$this->isAddOrEdit() && $this->documento->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->documento->AdvancedSearch->SearchValue != "" || $this->documento->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // doc_afectado
        if (!$this->isAddOrEdit() && $this->doc_afectado->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->doc_afectado->AdvancedSearch->SearchValue != "" || $this->doc_afectado->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // moneda
        if (!$this->isAddOrEdit() && $this->moneda->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->moneda->AdvancedSearch->SearchValue != "" || $this->moneda->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // monto_total
        if (!$this->isAddOrEdit() && $this->monto_total->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->monto_total->AdvancedSearch->SearchValue != "" || $this->monto_total->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // alicuota_iva
        if (!$this->isAddOrEdit() && $this->alicuota_iva->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->alicuota_iva->AdvancedSearch->SearchValue != "" || $this->alicuota_iva->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // iva
        if (!$this->isAddOrEdit() && $this->iva->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->iva->AdvancedSearch->SearchValue != "" || $this->iva->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // total
        if (!$this->isAddOrEdit() && $this->total->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->total->AdvancedSearch->SearchValue != "" || $this->total->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // tasa_dia
        if (!$this->isAddOrEdit() && $this->tasa_dia->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->tasa_dia->AdvancedSearch->SearchValue != "" || $this->tasa_dia->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // monto_usd
        if (!$this->isAddOrEdit() && $this->monto_usd->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->monto_usd->AdvancedSearch->SearchValue != "" || $this->monto_usd->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // lista_pedido
        if (!$this->isAddOrEdit() && $this->lista_pedido->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->lista_pedido->AdvancedSearch->SearchValue != "" || $this->lista_pedido->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // nota
        if (!$this->isAddOrEdit() && $this->nota->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->nota->AdvancedSearch->SearchValue != "" || $this->nota->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // username
        if (!$this->isAddOrEdit() && $this->_username->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->_username->AdvancedSearch->SearchValue != "" || $this->_username->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // estatus
        if (!$this->isAddOrEdit() && $this->estatus->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->estatus->AdvancedSearch->SearchValue != "" || $this->estatus->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // id_documento_padre
        if (!$this->isAddOrEdit() && $this->id_documento_padre->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->id_documento_padre->AdvancedSearch->SearchValue != "" || $this->id_documento_padre->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // asesor
        if (!$this->isAddOrEdit() && $this->asesor->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->asesor->AdvancedSearch->SearchValue != "" || $this->asesor->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // dias_credito
        if (!$this->isAddOrEdit() && $this->dias_credito->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->dias_credito->AdvancedSearch->SearchValue != "" || $this->dias_credito->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // entregado
        if (!$this->isAddOrEdit() && $this->entregado->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->entregado->AdvancedSearch->SearchValue != "" || $this->entregado->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // fecha_entrega
        if (!$this->isAddOrEdit() && $this->fecha_entrega->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->fecha_entrega->AdvancedSearch->SearchValue != "" || $this->fecha_entrega->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // pagado
        if (!$this->isAddOrEdit() && $this->pagado->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->pagado->AdvancedSearch->SearchValue != "" || $this->pagado->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // bultos
        if (!$this->isAddOrEdit() && $this->bultos->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->bultos->AdvancedSearch->SearchValue != "" || $this->bultos->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // fecha_bultos
        if (!$this->isAddOrEdit() && $this->fecha_bultos->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->fecha_bultos->AdvancedSearch->SearchValue != "" || $this->fecha_bultos->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // user_bultos
        if (!$this->isAddOrEdit() && $this->user_bultos->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->user_bultos->AdvancedSearch->SearchValue != "" || $this->user_bultos->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // fecha_despacho
        if (!$this->isAddOrEdit() && $this->fecha_despacho->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->fecha_despacho->AdvancedSearch->SearchValue != "" || $this->fecha_despacho->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // user_despacho
        if (!$this->isAddOrEdit() && $this->user_despacho->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->user_despacho->AdvancedSearch->SearchValue != "" || $this->user_despacho->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // consignacion
        if (!$this->isAddOrEdit() && $this->consignacion->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->consignacion->AdvancedSearch->SearchValue != "" || $this->consignacion->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // unidades
        if (!$this->isAddOrEdit() && $this->unidades->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->unidades->AdvancedSearch->SearchValue != "" || $this->unidades->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // descuento
        if (!$this->isAddOrEdit() && $this->descuento->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->descuento->AdvancedSearch->SearchValue != "" || $this->descuento->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // monto_sin_descuento
        if (!$this->isAddOrEdit() && $this->monto_sin_descuento->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->monto_sin_descuento->AdvancedSearch->SearchValue != "" || $this->monto_sin_descuento->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // factura
        if (!$this->isAddOrEdit() && $this->factura->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->factura->AdvancedSearch->SearchValue != "" || $this->factura->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // ci_rif
        if (!$this->isAddOrEdit() && $this->ci_rif->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->ci_rif->AdvancedSearch->SearchValue != "" || $this->ci_rif->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // nombre
        if (!$this->isAddOrEdit() && $this->nombre->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->nombre->AdvancedSearch->SearchValue != "" || $this->nombre->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // direccion
        if (!$this->isAddOrEdit() && $this->direccion->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->direccion->AdvancedSearch->SearchValue != "" || $this->direccion->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // telefono
        if (!$this->isAddOrEdit() && $this->telefono->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->telefono->AdvancedSearch->SearchValue != "" || $this->telefono->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // email
        if (!$this->isAddOrEdit() && $this->_email->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->_email->AdvancedSearch->SearchValue != "" || $this->_email->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // activo
        if (!$this->isAddOrEdit() && $this->activo->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->activo->AdvancedSearch->SearchValue != "" || $this->activo->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // comprobante
        if (!$this->isAddOrEdit() && $this->comprobante->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->comprobante->AdvancedSearch->SearchValue != "" || $this->comprobante->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // nro_despacho
        if (!$this->isAddOrEdit() && $this->nro_despacho->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->nro_despacho->AdvancedSearch->SearchValue != "" || $this->nro_despacho->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // cerrado
        if (!$this->isAddOrEdit() && $this->cerrado->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->cerrado->AdvancedSearch->SearchValue != "" || $this->cerrado->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // impreso
        if (!$this->isAddOrEdit() && $this->impreso->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->impreso->AdvancedSearch->SearchValue != "" || $this->impreso->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // igtf
        if (!$this->isAddOrEdit() && $this->igtf->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->igtf->AdvancedSearch->SearchValue != "" || $this->igtf->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // monto_base_igtf
        if (!$this->isAddOrEdit() && $this->monto_base_igtf->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->monto_base_igtf->AdvancedSearch->SearchValue != "" || $this->monto_base_igtf->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // monto_igtf
        if (!$this->isAddOrEdit() && $this->monto_igtf->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->monto_igtf->AdvancedSearch->SearchValue != "" || $this->monto_igtf->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // pago_premio
        if (!$this->isAddOrEdit() && $this->pago_premio->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->pago_premio->AdvancedSearch->SearchValue != "" || $this->pago_premio->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }
        return $hasValue;
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
        $this->ViewUrl = $this->getViewUrl();
        $this->EditUrl = $this->getEditUrl();
        $this->InlineEditUrl = $this->getInlineEditUrl();
        $this->CopyUrl = $this->getCopyUrl();
        $this->InlineCopyUrl = $this->getInlineCopyUrl();
        $this->DeleteUrl = $this->getDeleteUrl();

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

            // unidades
            $this->unidades->ViewValue = $this->unidades->CurrentValue;
            $this->unidades->ViewCustomAttributes = "";

            // nro_despacho
            $this->nro_despacho->ViewValue = $this->nro_despacho->CurrentValue;
            $this->nro_despacho->ViewCustomAttributes = "";

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

            // unidades
            $this->unidades->LinkCustomAttributes = "";
            $this->unidades->HrefValue = "";
            $this->unidades->TooltipValue = "";

            // nro_despacho
            $this->nro_despacho->LinkCustomAttributes = "";
            $this->nro_despacho->HrefValue = "";
            $this->nro_despacho->TooltipValue = "";

            // pago_premio
            $this->pago_premio->LinkCustomAttributes = "";
            $this->pago_premio->HrefValue = "";
            $this->pago_premio->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_SEARCH) {
            // tipo_documento
            $this->tipo_documento->EditAttrs["class"] = "form-control";
            $this->tipo_documento->EditCustomAttributes = "";
            if (!$this->tipo_documento->Raw) {
                $this->tipo_documento->AdvancedSearch->SearchValue = HtmlDecode($this->tipo_documento->AdvancedSearch->SearchValue);
            }
            $this->tipo_documento->EditValue = HtmlEncode($this->tipo_documento->AdvancedSearch->SearchValue);
            $this->tipo_documento->PlaceHolder = RemoveHtml($this->tipo_documento->caption());

            // nro_documento
            $this->nro_documento->EditAttrs["class"] = "form-control";
            $this->nro_documento->EditCustomAttributes = "";
            if (!$this->nro_documento->Raw) {
                $this->nro_documento->AdvancedSearch->SearchValue = HtmlDecode($this->nro_documento->AdvancedSearch->SearchValue);
            }
            $this->nro_documento->EditValue = HtmlEncode($this->nro_documento->AdvancedSearch->SearchValue);
            $this->nro_documento->PlaceHolder = RemoveHtml($this->nro_documento->caption());

            // fecha
            $this->fecha->EditAttrs["class"] = "form-control";
            $this->fecha->EditCustomAttributes = "";
            $this->fecha->EditValue = HtmlEncode(FormatDateTime(UnFormatDateTime($this->fecha->AdvancedSearch->SearchValue, 7), 7));
            $this->fecha->PlaceHolder = RemoveHtml($this->fecha->caption());
            $this->fecha->EditAttrs["class"] = "form-control";
            $this->fecha->EditCustomAttributes = "";
            $this->fecha->EditValue2 = HtmlEncode(FormatDateTime(UnFormatDateTime($this->fecha->AdvancedSearch->SearchValue2, 7), 7));
            $this->fecha->PlaceHolder = RemoveHtml($this->fecha->caption());

            // cliente
            $this->cliente->EditAttrs["class"] = "form-control";
            $this->cliente->EditCustomAttributes = "";
            $this->cliente->EditValue = HtmlEncode($this->cliente->AdvancedSearch->SearchValue);
            $curVal = trim(strval($this->cliente->AdvancedSearch->SearchValue));
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
                        $this->cliente->EditValue = HtmlEncode($this->cliente->AdvancedSearch->SearchValue);
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
                $this->doc_afectado->AdvancedSearch->SearchValue = HtmlDecode($this->doc_afectado->AdvancedSearch->SearchValue);
            }
            $this->doc_afectado->EditValue = HtmlEncode($this->doc_afectado->AdvancedSearch->SearchValue);
            $this->doc_afectado->PlaceHolder = RemoveHtml($this->doc_afectado->caption());

            // monto_total
            $this->monto_total->EditAttrs["class"] = "form-control";
            $this->monto_total->EditCustomAttributes = "";
            $this->monto_total->EditValue = HtmlEncode($this->monto_total->AdvancedSearch->SearchValue);
            $this->monto_total->PlaceHolder = RemoveHtml($this->monto_total->caption());

            // alicuota_iva
            $this->alicuota_iva->EditAttrs["class"] = "form-control";
            $this->alicuota_iva->EditCustomAttributes = "";
            $this->alicuota_iva->EditValue = HtmlEncode($this->alicuota_iva->AdvancedSearch->SearchValue);
            $this->alicuota_iva->PlaceHolder = RemoveHtml($this->alicuota_iva->caption());

            // iva
            $this->iva->EditAttrs["class"] = "form-control";
            $this->iva->EditCustomAttributes = "";
            $this->iva->EditValue = HtmlEncode($this->iva->AdvancedSearch->SearchValue);
            $this->iva->PlaceHolder = RemoveHtml($this->iva->caption());

            // total
            $this->total->EditAttrs["class"] = "form-control";
            $this->total->EditCustomAttributes = "";
            $this->total->EditValue = HtmlEncode($this->total->AdvancedSearch->SearchValue);
            $this->total->PlaceHolder = RemoveHtml($this->total->caption());

            // lista_pedido
            $this->lista_pedido->EditAttrs["class"] = "form-control";
            $this->lista_pedido->EditCustomAttributes = "";
            $curVal = trim(strval($this->lista_pedido->AdvancedSearch->SearchValue));
            if ($curVal != "") {
                $this->lista_pedido->AdvancedSearch->ViewValue = $this->lista_pedido->lookupCacheOption($curVal);
            } else {
                $this->lista_pedido->AdvancedSearch->ViewValue = $this->lista_pedido->Lookup !== null && is_array($this->lista_pedido->Lookup->Options) ? $curVal : null;
            }
            if ($this->lista_pedido->AdvancedSearch->ViewValue !== null) { // Load from cache
                $this->lista_pedido->EditValue = array_values($this->lista_pedido->Lookup->Options);
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`campo_codigo`" . SearchString("=", $this->lista_pedido->AdvancedSearch->SearchValue, DATATYPE_STRING, "");
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

            // username
            $this->_username->EditAttrs["class"] = "form-control";
            $this->_username->EditCustomAttributes = "";
            $curVal = trim(strval($this->_username->AdvancedSearch->SearchValue));
            if ($curVal != "") {
                $this->_username->AdvancedSearch->ViewValue = $this->_username->lookupCacheOption($curVal);
            } else {
                $this->_username->AdvancedSearch->ViewValue = $this->_username->Lookup !== null && is_array($this->_username->Lookup->Options) ? $curVal : null;
            }
            if ($this->_username->AdvancedSearch->ViewValue !== null) { // Load from cache
                $this->_username->EditValue = array_values($this->_username->Lookup->Options);
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`username`" . SearchString("=", $this->_username->AdvancedSearch->SearchValue, DATATYPE_STRING, "");
                }
                $sqlWrk = $this->_username->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->_username->EditValue = $arwrk;
            }
            $this->_username->PlaceHolder = RemoveHtml($this->_username->caption());

            // estatus
            $this->estatus->EditAttrs["class"] = "form-control";
            $this->estatus->EditCustomAttributes = "";
            $this->estatus->EditValue = $this->estatus->options(true);
            $this->estatus->PlaceHolder = RemoveHtml($this->estatus->caption());

            // asesor
            $this->asesor->EditAttrs["class"] = "form-control";
            $this->asesor->EditCustomAttributes = "";
            $curVal = trim(strval($this->asesor->AdvancedSearch->SearchValue));
            if ($curVal != "") {
                $this->asesor->AdvancedSearch->ViewValue = $this->asesor->lookupCacheOption($curVal);
            } else {
                $this->asesor->AdvancedSearch->ViewValue = $this->asesor->Lookup !== null && is_array($this->asesor->Lookup->Options) ? $curVal : null;
            }
            if ($this->asesor->AdvancedSearch->ViewValue !== null) { // Load from cache
                $this->asesor->EditValue = array_values($this->asesor->Lookup->Options);
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`ci_rif`" . SearchString("=", $this->asesor->AdvancedSearch->SearchValue, DATATYPE_STRING, "");
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

            // unidades
            $this->unidades->EditAttrs["class"] = "form-control";
            $this->unidades->EditCustomAttributes = "";
            $this->unidades->EditValue = HtmlEncode($this->unidades->AdvancedSearch->SearchValue);
            $this->unidades->PlaceHolder = RemoveHtml($this->unidades->caption());

            // nro_despacho
            $this->nro_despacho->EditAttrs["class"] = "form-control";
            $this->nro_despacho->EditCustomAttributes = "";
            if (!$this->nro_despacho->Raw) {
                $this->nro_despacho->AdvancedSearch->SearchValue = HtmlDecode($this->nro_despacho->AdvancedSearch->SearchValue);
            }
            $this->nro_despacho->EditValue = HtmlEncode($this->nro_despacho->AdvancedSearch->SearchValue);
            $this->nro_despacho->PlaceHolder = RemoveHtml($this->nro_despacho->caption());

            // pago_premio
            $this->pago_premio->EditAttrs["class"] = "form-control";
            $this->pago_premio->EditCustomAttributes = "";
            $this->pago_premio->EditValue = $this->pago_premio->options(true);
            $this->pago_premio->PlaceHolder = RemoveHtml($this->pago_premio->caption());
        }
        if ($this->RowType == ROWTYPE_ADD || $this->RowType == ROWTYPE_EDIT || $this->RowType == ROWTYPE_SEARCH) { // Add/Edit/Search row
            $this->setupFieldTitles();
        }

        // Call Row Rendered event
        if ($this->RowType != ROWTYPE_AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Validate search
    protected function validateSearch()
    {
        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        if (!CheckEuroDate($this->fecha->AdvancedSearch->SearchValue)) {
            $this->fecha->addErrorMessage($this->fecha->getErrorMessage(false));
        }
        if (!CheckEuroDate($this->fecha->AdvancedSearch->SearchValue2)) {
            $this->fecha->addErrorMessage($this->fecha->getErrorMessage(false));
        }
        if (!CheckInteger($this->cliente->AdvancedSearch->SearchValue)) {
            $this->cliente->addErrorMessage($this->cliente->getErrorMessage(false));
        }

        // Return validate result
        $validateSearch = !$this->hasInvalidFields();

        // Call Form_CustomValidate event
        $formCustomError = "";
        $validateSearch = $validateSearch && $this->formCustomValidate($formCustomError);
        if ($formCustomError != "") {
            $this->setFailureMessage($formCustomError);
        }
        return $validateSearch;
    }

    // Load advanced search
    public function loadAdvancedSearch()
    {
        $this->id->AdvancedSearch->load();
        $this->tipo_documento->AdvancedSearch->load();
        $this->nro_documento->AdvancedSearch->load();
        $this->nro_control->AdvancedSearch->load();
        $this->fecha->AdvancedSearch->load();
        $this->cliente->AdvancedSearch->load();
        $this->documento->AdvancedSearch->load();
        $this->doc_afectado->AdvancedSearch->load();
        $this->moneda->AdvancedSearch->load();
        $this->monto_total->AdvancedSearch->load();
        $this->alicuota_iva->AdvancedSearch->load();
        $this->iva->AdvancedSearch->load();
        $this->total->AdvancedSearch->load();
        $this->tasa_dia->AdvancedSearch->load();
        $this->monto_usd->AdvancedSearch->load();
        $this->lista_pedido->AdvancedSearch->load();
        $this->nota->AdvancedSearch->load();
        $this->_username->AdvancedSearch->load();
        $this->estatus->AdvancedSearch->load();
        $this->id_documento_padre->AdvancedSearch->load();
        $this->asesor->AdvancedSearch->load();
        $this->dias_credito->AdvancedSearch->load();
        $this->entregado->AdvancedSearch->load();
        $this->fecha_entrega->AdvancedSearch->load();
        $this->pagado->AdvancedSearch->load();
        $this->bultos->AdvancedSearch->load();
        $this->fecha_bultos->AdvancedSearch->load();
        $this->user_bultos->AdvancedSearch->load();
        $this->fecha_despacho->AdvancedSearch->load();
        $this->user_despacho->AdvancedSearch->load();
        $this->consignacion->AdvancedSearch->load();
        $this->unidades->AdvancedSearch->load();
        $this->descuento->AdvancedSearch->load();
        $this->monto_sin_descuento->AdvancedSearch->load();
        $this->factura->AdvancedSearch->load();
        $this->ci_rif->AdvancedSearch->load();
        $this->nombre->AdvancedSearch->load();
        $this->direccion->AdvancedSearch->load();
        $this->telefono->AdvancedSearch->load();
        $this->_email->AdvancedSearch->load();
        $this->activo->AdvancedSearch->load();
        $this->comprobante->AdvancedSearch->load();
        $this->nro_despacho->AdvancedSearch->load();
        $this->cerrado->AdvancedSearch->load();
        $this->impreso->AdvancedSearch->load();
        $this->igtf->AdvancedSearch->load();
        $this->monto_base_igtf->AdvancedSearch->load();
        $this->monto_igtf->AdvancedSearch->load();
        $this->pago_premio->AdvancedSearch->load();
    }

    // Get export HTML tag
    protected function getExportTag($type, $custom = false)
    {
        global $Language;
        $pageUrl = $this->pageUrl();
        if (SameText($type, "excel")) {
            if ($custom) {
                return "<a href=\"#\" class=\"ew-export-link ew-excel\" title=\"" . HtmlEncode($Language->phrase("ExportToExcelText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToExcelText")) . "\" onclick=\"return ew.export(document.fsalidaslist, '" . $this->ExportExcelUrl . "', 'excel', true);\">" . $Language->phrase("ExportToExcel") . "</a>";
            } else {
                return "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ew-export-link ew-excel\" title=\"" . HtmlEncode($Language->phrase("ExportToExcelText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToExcelText")) . "\">" . $Language->phrase("ExportToExcel") . "</a>";
            }
        } elseif (SameText($type, "word")) {
            if ($custom) {
                return "<a href=\"#\" class=\"ew-export-link ew-word\" title=\"" . HtmlEncode($Language->phrase("ExportToWordText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToWordText")) . "\" onclick=\"return ew.export(document.fsalidaslist, '" . $this->ExportWordUrl . "', 'word', true);\">" . $Language->phrase("ExportToWord") . "</a>";
            } else {
                return "<a href=\"" . $this->ExportWordUrl . "\" class=\"ew-export-link ew-word\" title=\"" . HtmlEncode($Language->phrase("ExportToWordText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToWordText")) . "\">" . $Language->phrase("ExportToWord") . "</a>";
            }
        } elseif (SameText($type, "pdf")) {
            if ($custom) {
                return "<a href=\"#\" class=\"ew-export-link ew-pdf\" title=\"" . HtmlEncode($Language->phrase("ExportToPDFText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToPDFText")) . "\" onclick=\"return ew.export(document.fsalidaslist, '" . $this->ExportPdfUrl . "', 'pdf', true);\">" . $Language->phrase("ExportToPDF") . "</a>";
            } else {
                return "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ew-export-link ew-pdf\" title=\"" . HtmlEncode($Language->phrase("ExportToPDFText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToPDFText")) . "\">" . $Language->phrase("ExportToPDF") . "</a>";
            }
        } elseif (SameText($type, "html")) {
            return "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ew-export-link ew-html\" title=\"" . HtmlEncode($Language->phrase("ExportToHtmlText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToHtmlText")) . "\">" . $Language->phrase("ExportToHtml") . "</a>";
        } elseif (SameText($type, "xml")) {
            return "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ew-export-link ew-xml\" title=\"" . HtmlEncode($Language->phrase("ExportToXmlText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToXmlText")) . "\">" . $Language->phrase("ExportToXml") . "</a>";
        } elseif (SameText($type, "csv")) {
            return "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ew-export-link ew-csv\" title=\"" . HtmlEncode($Language->phrase("ExportToCsvText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToCsvText")) . "\">" . $Language->phrase("ExportToCsv") . "</a>";
        } elseif (SameText($type, "email")) {
            $url = $custom ? ",url:'" . $pageUrl . "export=email&amp;custom=1'" : "";
            return '<button id="emf_salidas" class="ew-export-link ew-email" title="' . $Language->phrase("ExportToEmailText") . '" data-caption="' . $Language->phrase("ExportToEmailText") . '" onclick="ew.emailDialogShow({lnk:\'emf_salidas\', hdr:ew.language.phrase(\'ExportToEmailText\'), f:document.fsalidaslist, sel:false' . $url . '});">' . $Language->phrase("ExportToEmail") . '</button>';
        } elseif (SameText($type, "print")) {
            return "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ew-export-link ew-print\" title=\"" . HtmlEncode($Language->phrase("PrinterFriendlyText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("PrinterFriendlyText")) . "\">" . $Language->phrase("PrinterFriendly") . "</a>";
        }
    }

    // Set up export options
    protected function setupExportOptions()
    {
        global $Language;

        // Printer friendly
        $item = &$this->ExportOptions->add("print");
        $item->Body = $this->getExportTag("print");
        $item->Visible = false;

        // Export to Excel
        $item = &$this->ExportOptions->add("excel");
        $item->Body = $this->getExportTag("excel");
        $item->Visible = true;

        // Export to Word
        $item = &$this->ExportOptions->add("word");
        $item->Body = $this->getExportTag("word");
        $item->Visible = false;

        // Export to Html
        $item = &$this->ExportOptions->add("html");
        $item->Body = $this->getExportTag("html");
        $item->Visible = false;

        // Export to Xml
        $item = &$this->ExportOptions->add("xml");
        $item->Body = $this->getExportTag("xml");
        $item->Visible = false;

        // Export to Csv
        $item = &$this->ExportOptions->add("csv");
        $item->Body = $this->getExportTag("csv");
        $item->Visible = true;

        // Export to Pdf
        $item = &$this->ExportOptions->add("pdf");
        $item->Body = $this->getExportTag("pdf");
        $item->Visible = false;

        // Export to Email
        $item = &$this->ExportOptions->add("email");
        $item->Body = $this->getExportTag("email");
        $item->Visible = false;

        // Drop down button for export
        $this->ExportOptions->UseButtonGroup = true;
        $this->ExportOptions->UseDropDownButton = false;
        if ($this->ExportOptions->UseButtonGroup && IsMobile()) {
            $this->ExportOptions->UseDropDownButton = true;
        }
        $this->ExportOptions->DropDownButtonPhrase = $Language->phrase("ButtonExport");

        // Add group option item
        $item = &$this->ExportOptions->add($this->ExportOptions->GroupOptionName);
        $item->Body = "";
        $item->Visible = false;
    }

    // Set up search options
    protected function setupSearchOptions()
    {
        global $Language, $Security;
        $pageUrl = $this->pageUrl();
        $this->SearchOptions = new ListOptions("div");
        $this->SearchOptions->TagClassName = "ew-search-option";

        // Search button
        $item = &$this->SearchOptions->add("searchtoggle");
        $searchToggleClass = ($this->SearchWhere != "") ? " active" : "";
        $item->Body = "<a class=\"btn btn-default ew-search-toggle" . $searchToggleClass . "\" href=\"#\" role=\"button\" title=\"" . $Language->phrase("SearchPanel") . "\" data-caption=\"" . $Language->phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fsalidaslistsrch\" aria-pressed=\"" . ($searchToggleClass == " active" ? "true" : "false") . "\">" . $Language->phrase("SearchLink") . "</a>";
        $item->Visible = true;

        // Show all button
        $item = &$this->SearchOptions->add("showall");
        $item->Body = "<a class=\"btn btn-default ew-show-all\" title=\"" . $Language->phrase("ResetSearch") . "\" data-caption=\"" . $Language->phrase("ResetSearch") . "\" href=\"" . $pageUrl . "cmd=reset\">" . $Language->phrase("ResetSearchBtn") . "</a>";
        $item->Visible = ($this->SearchWhere != $this->DefaultSearchWhere && $this->SearchWhere != "0=101");

        // Button group for search
        $this->SearchOptions->UseDropDownButton = false;
        $this->SearchOptions->UseButtonGroup = true;
        $this->SearchOptions->DropDownButtonPhrase = $Language->phrase("ButtonSearch");

        // Add group option item
        $item = &$this->SearchOptions->add($this->SearchOptions->GroupOptionName);
        $item->Body = "";
        $item->Visible = false;

        // Hide search options
        if ($this->isExport() || $this->CurrentAction) {
            $this->SearchOptions->hideAllOptions();
        }
        if (!$Security->canSearch()) {
            $this->SearchOptions->hideAllOptions();
            $this->FilterOptions->hideAllOptions();
        }
    }

    /**
    * Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
    *
    * @param bool $return Return the data rather than output it
    * @return mixed
    */
    public function exportData($return = false)
    {
        global $Language;
        $utf8 = SameText(Config("PROJECT_CHARSET"), "utf-8");

        // Load recordset
        $this->TotalRecords = $this->listRecordCount();
        $this->StartRecord = 1;

        // Export all
        if ($this->ExportAll) {
            if (Config("EXPORT_ALL_TIME_LIMIT") >= 0) {
                @set_time_limit(Config("EXPORT_ALL_TIME_LIMIT"));
            }
            $this->DisplayRecords = $this->TotalRecords;
            $this->StopRecord = $this->TotalRecords;
        } else { // Export one page only
            $this->setupStartRecord(); // Set up start record position
            // Set the last record to display
            if ($this->DisplayRecords <= 0) {
                $this->StopRecord = $this->TotalRecords;
            } else {
                $this->StopRecord = $this->StartRecord + $this->DisplayRecords - 1;
            }
        }
        $rs = $this->loadRecordset($this->StartRecord - 1, $this->DisplayRecords <= 0 ? $this->TotalRecords : $this->DisplayRecords);
        $this->ExportDoc = GetExportDocument($this, "h");
        $doc = &$this->ExportDoc;
        if (!$doc) {
            $this->setFailureMessage($Language->phrase("ExportClassNotFound")); // Export class not found
        }
        if (!$rs || !$doc) {
            RemoveHeader("Content-Type"); // Remove header
            RemoveHeader("Content-Disposition");
            $this->showMessage();
            return;
        }
        $this->StartRecord = 1;
        $this->StopRecord = $this->DisplayRecords <= 0 ? $this->TotalRecords : $this->DisplayRecords;

        // Call Page Exporting server event
        $this->ExportDoc->ExportCustom = !$this->pageExporting();
        $header = $this->PageHeader;
        $this->pageDataRendering($header);
        $doc->Text .= $header;
        $this->exportDocument($doc, $rs, $this->StartRecord, $this->StopRecord, "");
        $footer = $this->PageFooter;
        $this->pageDataRendered($footer);
        $doc->Text .= $footer;

        // Close recordset
        $rs->close();

        // Call Page Exported server event
        $this->pageExported();

        // Export header and footer
        $doc->exportHeaderAndFooter();

        // Clean output buffer (without destroying output buffer)
        $buffer = ob_get_contents(); // Save the output buffer
        if (!Config("DEBUG") && $buffer) {
            ob_clean();
        }

        // Write debug message if enabled
        if (Config("DEBUG") && !$this->isExport("pdf")) {
            echo GetDebugMessage();
        }

        // Output data
        if ($this->isExport("email")) {
            // Export-to-email disabled
        } else {
            $doc->export();
            if ($return) {
                RemoveHeader("Content-Type"); // Remove header
                RemoveHeader("Content-Disposition");
                $content = ob_get_contents();
                if ($content) {
                    ob_clean();
                }
                if ($buffer) {
                    echo $buffer; // Resume the output buffer
                }
                return $content;
            }
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("Home");
        $url = CurrentUrl();
        $url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
        $Breadcrumb->add("list", $this->TableVar, $url, "", $this->TableVar, true);
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
       		$tipo_name = '<a href="SalidasList?tipo=' . $tipo . '">' . $tipo_name . '</a>';
       		$this->setTableCaption($tipo_name);
       	}
        else {
    		$sql = "SELECT tipo_documento FROM username_tipo_documento WHERE username = '" . CurrentUserName() . "';";
    		$x_tipo = ExecuteScalar($sql);
    		$tipo = isset($_REQUEST["tipo"]) ? $_REQUEST["tipo"] : (isset($_REQUEST["x_tipo_documento"]) ? $_REQUEST["x_tipo_documento"] : $x_tipo);
       		$sql = "SELECT descripcion FROM tipo_documento WHERE codigo = '$tipo';";
       		$tipo_name = ExecuteScalar($sql);
       		$tipo_name = '<a href="SalidasList?tipo=' . $tipo . '">' . $tipo_name . '</a>';
       		$this->setTableCaption($tipo_name);
        }
    }

    // Page Unload event
    public function pageUnload()
    {
        //Log("Page Unload");
    }

    // Page Redirecting event
    public function pageRedirecting(&$url) {
    	// Example:
    	//$url = "your URL";
    	$sql = "SELECT tipo_documento FROM username_tipo_documento WHERE username = '" . CurrentUserName() . "';";
       	$x_tipo = ExecuteScalar($sql);
       	$tipo = isset($_REQUEST["tipo"]) ? $_REQUEST["tipo"] : (isset($_REQUEST["x_tipo_documento"]) ? $_REQUEST["x_tipo_documento"] : $x_tipo);
    	if($tipo == "TDCPDV") {
    		$sql = "SELECT tipo_acceso FROM userlevels
    			WHERE userlevelid = '" . CurrentUserLevel() . "';"; 
    		$grupo = trim(ExecuteScalar($sql));

    		//if($grupo == "CLIENTE") {
    			$sql = "SELECT valor1 FROM parametro WHERE codigo = '013';";
    			$bloquea = ExecuteScalar($sql);
    			if($bloquea == "SI") {
    				$url = "Home";
    			}
    		//}
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
    	$this->factura->Visible = FALSE;
    	$this->nombre->Visible = FALSE;
    	$this->comprobante->Visible = FALSE;
    	$this->fecha_entrega->Visible = FALSE;
    	$this->nro_despacho->Visible = FALSE;
    	$this->impreso->Visible = FALSE;
    	$this->asesor->Visible = FALSE;
    	$this->ci_rif->Visible = FALSE;
    	$sql = "SELECT tipo_documento FROM username_tipo_documento WHERE username = '" . CurrentUserName() . "';";
       	$x_tipo = ExecuteScalar($sql);
       	$tipo = isset($_REQUEST["tipo"]) ? $_REQUEST["tipo"] : (isset($_REQUEST["x_tipo_documento"]) ? $_REQUEST["x_tipo_documento"] : $x_tipo);
    	switch($tipo) {
    	case "TDCPDV":
    		$this->monto_total->Visible = TRUE;
    		$this->alicuota_iva->Visible = TRUE;
    		$this->iva->Visible = TRUE;
    		$this->total->Visible = TRUE;
    		$this->id_documento_padre->Visible = FALSE;
    		$this->moneda->Visible = FALSE;
    		$this->documento->Visible = FALSE;
    		$this->doc_afectado->Visible = FALSE;
    		$this->estatus->Visible = FALSE;
    		$this->consignacion->Visible = FALSE;
    		$this->lista_pedido->Visible = TRUE;
    		break;
    	case "TDCNET":
    		$this->monto_total->Visible = FALSE;
    		$this->alicuota_iva->Visible = FALSE;
    		$this->iva->Visible = FALSE;
    		$this->total->Visible = TRUE;
    		$this->id_documento_padre->Visible = TRUE;
    		$this->moneda->Visible = FALSE;
    		$this->documento->Visible = FALSE;
    		$this->doc_afectado->Visible = FALSE;
    		$this->estatus->Visible = TRUE;
    		$this->lista_pedido->Visible = FALSE;
    		$this->impreso->Visible = TRUE;
    		$this->asesor->Visible = TRUE;
    		break;
    	case "TDCFCV":
    		$this->id_documento_padre->Visible = TRUE;
    		$this->estatus->Visible = TRUE;
    		$this->lista_pedido->Visible = FALSE;
    		$this->doc_afectado->Visible = FALSE;
    		$this->comprobante->Visible = TRUE;
    		$this->impreso->Visible = TRUE;
    		break;
    	case "TDCASA":
    		$this->factura->Visible = TRUE;
    		$this->nombre->Visible = TRUE;
    		$this->monto_total->Visible = FALSE;
    		$this->alicuota_iva->Visible = FALSE;
    		$this->iva->Visible = FALSE;
    		$this->total->Visible = FALSE;
    		$this->id_documento_padre->Visible = FALSE;
    		$this->moneda->Visible = FALSE;
    		$this->documento->Visible = FALSE;
    		$this->doc_afectado->Visible = FALSE;
    		$this->estatus->Visible = TRUE;
    		$this->consignacion->Visible = FALSE;
    		$this->lista_pedido->Visible = FALSE;
    		break;
    	}
    }

    // Page Data Rendering event
    public function pageDataRendering(&$header) {
    	// Example:
    	$sql = "SELECT tipo_documento FROM username_tipo_documento WHERE username = '" . CurrentUserName() . "';";
       	$x_tipo = ExecuteScalar($sql);
       	$tipo = isset($_REQUEST["tipo"]) ? $_REQUEST["tipo"] : (isset($_REQUEST["x_tipo_documento"]) ? $_REQUEST["x_tipo_documento"] : $x_tipo);
    	$pagado = "";
    	$recibido = "";
    	switch($tipo) {
    	case "TDCPDV":
    		$header = '<button class="btn btn-primary" id="btnResumen">Resumen de Venta</button> ';
    		break;
    	case "TDCFCV":
    		$header = '<button class="btn btn-primary" id="btnResumen">Resumen de Facturaci&oacute;n</button> <button class="btn btn-primary" id="btnGanancia">Resumen Utilidad</button> ';
    		break;
    	case "TDCNET":
    		$header = '<button class="btn btn-primary" id="btnResumenVentas">Resumen de Ventas</button> ';
    		$header .= '<button class="btn btn-primary" id="btnIngresoCaja">Ingreso de Caja</button> ';
    		break;
    	default: 
    		$header = '';
    	}
    	$sql = "SELECT descripcion FROM tipo_documento WHERE codigo = '$tipo';";
    	$doc = ExecuteScalar($sql);

    	// $header .= '<h2>' . $doc .'</h2>';
    	$header .= '<a class="btn btn-success" id="btnNuevo" href="SalidasAdd?showdetail=entradas_salidas&tipo=' . $tipo . '"><span class="fas fa-plus"></span> Nuevo Documento "' . $doc . '"</a> ';
    	$header .= '<input type="hidden" class="form-control" id="x_Tipo" nam="x_Tipo" value="' . $tipo . '" size="8">';
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

    // ListOptions Load event
    public function listOptionsLoad() {
    	// Example:
    	$opt = &$this->ListOptions->Add("print");
    	$opt->Header = "";
    	$opt->OnLeft = TRUE; // Link on left
    	$opt->MoveTo(6); // Move to first column
    	if(VerificaFuncion('001')) {
    		$opt = &$this->ListOptions->Add("new");
    		$opt->Header = "";
    		$opt->OnLeft = TRUE; // Link on left
    		$opt->MoveTo(7); // Move to first column
    	}
    	$opt = &$this->ListOptions->Add("ticket");
    	$opt->Header = "";
    	$opt->OnLeft = TRUE; // Link on left
    	$opt->MoveTo(8); // Move to first column
    	$opt = &$this->ListOptions->Add("edit2");
    	$opt->Header = "";
    	$opt->OnLeft = TRUE; // Link on left
    	$opt->MoveTo(3); // Move to first column
    }

    // ListOptions Rendering event
    public function listOptionsRendering()
    {
        //Container("DetailTableGrid")->DetailAdd = (...condition...); // Set to true or false conditionally
        //Container("DetailTableGrid")->DetailEdit = (...condition...); // Set to true or false conditionally
        //Container("DetailTableGrid")->DetailView = (...condition...); // Set to true or false conditionally
    }

    // ListOptions Rendered event
    public function listOptionsRendered() {
    	// Example:
    	$sql = "SELECT tipo_documento FROM username_tipo_documento WHERE username = '" . CurrentUserName() . "';";
       	$x_tipo = ExecuteScalar($sql);
       	$tipo = isset($_REQUEST["tipo"]) ? $_REQUEST["tipo"] : (isset($_REQUEST["x_tipo_documento"]) ? $_REQUEST["x_tipo_documento"] : $x_tipo);
        $id = $this->id->CurrentValue;
        $procesado = $this->estatus->CurrentValue; // ExecuteScalar($sql);
        switch($tipo) {
        case "TDCPDV":
        	$url = "SalidasView?showdetail=entradas_salidas&id=$id&tipo=TDCPDV";
            $this->ListOptions->Items["view"]->Body = '<a class="fas fa-eye" href="' . $url . '" data-toggle="tooltip" title="Ver" data-placement="bottom"></a>';
        	$url = "PedidoDeVentaDetalle?id=$id&tipo=TDCPDV";
            $this->ListOptions->Items["edit"]->Body = '<a class="fas fa-edit" href="' . $url . '" data-toggle="tooltip" title="Editar" data-placement="bottom"></a>';
        	$url = "PedidoDeVentaDetalleCopia?id=$id&tipo=TDCPDV";
            $this->ListOptions->Items["copy"]->Body = '<a class="fas fa-copy" href="' . $url . '"  data-toggle="tooltip" title="Copia" data-placement="bottom"></a>';
        	$url = "SalidasDelete?id=$id&tipo=TDCPDV";
            $this->ListOptions->Items["delete"]->Body ='<a class="fas fa-trash" href="' . $url . '" data-toggle="tooltip" title="Borrar" data-placement="bottom"></a>';
        	$url = "reportes/pedido_de_venta.php?id=$id&tipo=TDCPDV";
            $this->ListOptions->Items["print"]->Body ='<a target="_blank" class="fas fa-print" href="' . $url . '" data-toggle="tooltip" title="Imprimir" data-placement="bottom"></a>';
        	$sql = "SELECT COUNT(*) AS cantidad FROM entradas_salidas WHERE tipo_documento = '$tipo' AND id_documento = '$id' AND IFNULL(cantidad_articulo, 0) > 0;";
        	if(ExecuteScalar($sql) > 0) {
        		if(VerificaFuncion('001')) {
        			if($this->estatus->CurrentValue == "NUEVO") {
    					$url = "ConfirmPage?page=TDCPDV&id=$id";
    					$this->ListOptions->Items["new"]->Body = '<a class="fas fa-cog" href=" ' . $url . '" data-caption="View" data-toggle="tooltip" title="Crear Factura" data-placement="bottom" ></a>';
        			}
        			else
        				$this->ListOptions->Items["new"]->Body ='';
        		}
        	}
        	break;
        case "TDCNET":
        	$url = "SalidasView/$id?showdetail=entradas_salidas&tipo=TDCNET";
            $this->ListOptions->Items["view"]->Body = '<a class="fas fa-eye" href="' . $url . '" data-toggle="tooltip" title="Ver" data-placement="bottom"></a>';
        	$url = "SalidasEdit?showdetail=entradas_salidas&id=$id&tipo=TDCNET";
            $this->ListOptions->Items["edit2"]->Body = '<a class="fas fa-edit" href="' . $url . '" data-toggle="tooltip" title="Editar Nota de Entrega Completa" data-placement="bottom"></a>';
            $this->ListOptions->Items["edit"]->Body = '';
        	$this->ListOptions->Items["copy"]->Body = '';
        	$url = "SalidasDelete?id=$id&tipo=TDCNET";
            $this->ListOptions->Items["delete"]->Body ='<a class="fas fa-trash" href="' . $url . '" data-toggle="tooltip" title="Borrar" data-placement="bottom"></a>';
           	$url = "reportes/nota_de_entrega.php?id=$id&tipo=TDCNET";
            if($procesado != "NUEVO")
            	$this->ListOptions->Items["print"]->Body = '<a class="fas fa-print" target="_blank" href="' . $url . '" data-toggle="tooltip" title="Imprimir" data-placement="bottom"></a>';
            else $this->ListOptions->Items["print"]->Body = '';
       		$url = "reportes/nota_de_entrega_ticket.php?id=$id&tipo=TDCNET";
            if($procesado != "NUEVO")
            	$this->ListOptions->Items["ticket"]->Body = '<a class="fas fa-map" target="_blank" href="' . $url . '" data-toggle="tooltip" title="ticket" data-placement="bottom"></a>';
            else $this->ListOptions->Items["ticket"]->Body = '';
        	break;
        case "TDCFCV":
        	$url = "SalidasView?showdetail=entradas_salidas&id=$id&tipo=TDCFCV";
            $this->ListOptions->Items["view"]->Body = '<a class="fas fa-eye" href="' . $url . '" data-toggle="tooltip" title="Ver" data-placement="bottom"></a>';
        	$url = "SalidasEdit?showdetail=entradas_salidas&id=$id&tipo=TDCFCV";
            $this->ListOptions->Items["edit2"]->Body = '<a class="fas fa-edit" href="' . $url . '" data-toggle="tooltip" title="Editar Factura Completa" data-placement="bottom"></a>';
        	$url = "FacturaDeVentaCopiarComo?id=$id&tipo=TDCFCV";
            $this->ListOptions->Items["copy"]->Body = '<a class="fas fa-copy" href="' . $url . '"  data-toggle="tooltip" title="Copia" data-placement="bottom"></a>';
        	$url = "SalidasDelete?id=$id&tipo=TDCFCV";
            $this->ListOptions->Items["delete"]->Body ='<a class="fas fa-trash" href="' . $url . '" data-toggle="tooltip" title="Borrar" data-placement="bottom"></a>';
        	$url = "reportes/factura_de_venta.php?id=$id&tipo=TDCFCV&username=" . CurrentUserName();
            $this->ListOptions->Items["print"]->Body ='<a target="_blank" class="fas fa-print" href="' . $url . '" data-toggle="tooltip" title="Imprimir" data-placement="bottom"></a>';
        	break;
        case "TDCASA":
        	$url = "SalidasView?showdetail=entradas_salidas&id=$id&tipo=TDCASA";
            $this->ListOptions->Items["view"]->Body = '<a class="fas fa-eye" href="' . $url . '" data-toggle="tooltip" title="Ver" data-placement="bottom"></a>';
        	$url = "SalidasEdit?showdetail=entradas_salidas&id=$id&tipo=TDCASA";
        	$this->ListOptions->Items["edit"]->Body = '<a class="fas fa-edit" href="' . $url . '" data-toggle="tooltip" title="Editar Factura Completa" data-placement="bottom"></a>';
        	$this->ListOptions->Items["copy"]->Body = '';
        	$url = "SalidasDelete?id=$id&tipo=TDCASA";
        	$this->ListOptions->Items["delete"]->Body ='<a class="fas fa-trash" href="' . $url . '" data-toggle="tooltip" title="Borrar" data-placement="bottom"></a>';
        	$url = "reportes/ajuste_de_salida.php?id=$id&tipo=TDCASA";
        	$this->ListOptions->Items["print"]->Body = '<a target="_blank" class="fas fa-print" href="' . $url . '" data-toggle="tooltip" title="Imprimir" data-placement="bottom"></a>';
        	break;
        }
    }

    // Row Custom Action event
    public function rowCustomAction($action, $row)
    {
        // Return false to abort
        return true;
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

    // Page Importing event
    public function pageImporting($reader, &$options)
    {
        //var_dump($reader); // Import data reader
        //var_dump($options); // Show all options for importing
        //return false; // Return false to skip import
        return true;
    }

    // Row Import event
    public function rowImport(&$row, $cnt)
    {
        //Log($cnt); // Import record count
        //var_dump($row); // Import row
        //return false; // Return false to skip import
        return true;
    }

    // Page Imported event
    public function pageImported($reader, $results)
    {
        //var_dump($reader); // Import data reader
        //var_dump($results); // Import results
    }
}
