<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class ArticuloList extends Articulo
{
    use MessagesTrait;

    // Page ID
    public $PageID = "list";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'articulo';

    // Page object name
    public $PageObjName = "ArticuloList";

    // Rendering View
    public $RenderingView = false;

    // Grid form hidden field names
    public $FormName = "farticulolist";
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

        // Table object (articulo)
        if (!isset($GLOBALS["articulo"]) || get_class($GLOBALS["articulo"]) == PROJECT_NAMESPACE . "articulo") {
            $GLOBALS["articulo"] = &$this;
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
        $this->AddUrl = "ArticuloAdd?" . Config("TABLE_SHOW_DETAIL") . "=";
        $this->InlineAddUrl = $pageUrl . "action=add";
        $this->GridAddUrl = $pageUrl . "action=gridadd";
        $this->GridEditUrl = $pageUrl . "action=gridedit";
        $this->MultiDeleteUrl = "ArticuloDelete";
        $this->MultiUpdateUrl = "ArticuloUpdate";

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
        $this->FilterOptions->TagClassName = "ew-filter-option farticulolistsrch";

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
        $this->codigo_ims->setVisibility();
        $this->codigo->setVisibility();
        $this->nombre_comercial->Visible = false;
        $this->principio_activo->setVisibility();
        $this->presentacion->Visible = false;
        $this->fabricante->setVisibility();
        $this->codigo_de_barra->Visible = false;
        $this->categoria->Visible = false;
        $this->lista_pedido->Visible = false;
        $this->unidad_medida_defecto->Visible = false;
        $this->cantidad_por_unidad_medida->Visible = false;
        $this->foto->Visible = false;
        $this->cantidad_minima->Visible = false;
        $this->cantidad_maxima->Visible = false;
        $this->cantidad_en_mano->setVisibility();
        $this->cantidad_en_pedido->setVisibility();
        $this->cantidad_en_transito->setVisibility();
        $this->ultimo_costo->Visible = false;
        $this->descuento->setVisibility();
        $this->precio->Visible = false;
        $this->precio2->Visible = false;
        $this->alicuota->Visible = false;
        $this->articulo_inventario->Visible = false;
        $this->activo->setVisibility();
        $this->puntos_ventas->setVisibility();
        $this->puntos_premio->setVisibility();
        $this->sincroniza->Visible = false;
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
        $this->setupLookupOptions($this->fabricante);
        $this->setupLookupOptions($this->categoria);
        $this->setupLookupOptions($this->lista_pedido);
        $this->setupLookupOptions($this->unidad_medida_defecto);
        $this->setupLookupOptions($this->cantidad_por_unidad_medida);
        $this->setupLookupOptions($this->alicuota);

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
        $filterList = Concat($filterList, $this->codigo_ims->AdvancedSearch->toJson(), ","); // Field codigo_ims
        $filterList = Concat($filterList, $this->codigo->AdvancedSearch->toJson(), ","); // Field codigo
        $filterList = Concat($filterList, $this->nombre_comercial->AdvancedSearch->toJson(), ","); // Field nombre_comercial
        $filterList = Concat($filterList, $this->principio_activo->AdvancedSearch->toJson(), ","); // Field principio_activo
        $filterList = Concat($filterList, $this->presentacion->AdvancedSearch->toJson(), ","); // Field presentacion
        $filterList = Concat($filterList, $this->fabricante->AdvancedSearch->toJson(), ","); // Field fabricante
        $filterList = Concat($filterList, $this->codigo_de_barra->AdvancedSearch->toJson(), ","); // Field codigo_de_barra
        $filterList = Concat($filterList, $this->categoria->AdvancedSearch->toJson(), ","); // Field categoria
        $filterList = Concat($filterList, $this->lista_pedido->AdvancedSearch->toJson(), ","); // Field lista_pedido
        $filterList = Concat($filterList, $this->unidad_medida_defecto->AdvancedSearch->toJson(), ","); // Field unidad_medida_defecto
        $filterList = Concat($filterList, $this->cantidad_por_unidad_medida->AdvancedSearch->toJson(), ","); // Field cantidad_por_unidad_medida
        $filterList = Concat($filterList, $this->foto->AdvancedSearch->toJson(), ","); // Field foto
        $filterList = Concat($filterList, $this->cantidad_minima->AdvancedSearch->toJson(), ","); // Field cantidad_minima
        $filterList = Concat($filterList, $this->cantidad_maxima->AdvancedSearch->toJson(), ","); // Field cantidad_maxima
        $filterList = Concat($filterList, $this->cantidad_en_mano->AdvancedSearch->toJson(), ","); // Field cantidad_en_mano
        $filterList = Concat($filterList, $this->cantidad_en_pedido->AdvancedSearch->toJson(), ","); // Field cantidad_en_pedido
        $filterList = Concat($filterList, $this->cantidad_en_transito->AdvancedSearch->toJson(), ","); // Field cantidad_en_transito
        $filterList = Concat($filterList, $this->ultimo_costo->AdvancedSearch->toJson(), ","); // Field ultimo_costo
        $filterList = Concat($filterList, $this->descuento->AdvancedSearch->toJson(), ","); // Field descuento
        $filterList = Concat($filterList, $this->precio->AdvancedSearch->toJson(), ","); // Field precio
        $filterList = Concat($filterList, $this->precio2->AdvancedSearch->toJson(), ","); // Field precio2
        $filterList = Concat($filterList, $this->alicuota->AdvancedSearch->toJson(), ","); // Field alicuota
        $filterList = Concat($filterList, $this->articulo_inventario->AdvancedSearch->toJson(), ","); // Field articulo_inventario
        $filterList = Concat($filterList, $this->activo->AdvancedSearch->toJson(), ","); // Field activo
        $filterList = Concat($filterList, $this->puntos_ventas->AdvancedSearch->toJson(), ","); // Field puntos_ventas
        $filterList = Concat($filterList, $this->puntos_premio->AdvancedSearch->toJson(), ","); // Field puntos_premio
        $filterList = Concat($filterList, $this->sincroniza->AdvancedSearch->toJson(), ","); // Field sincroniza
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
            $UserProfile->setSearchFilters(CurrentUserName(), "farticulolistsrch", $filters);
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

        // Field codigo_ims
        $this->codigo_ims->AdvancedSearch->SearchValue = @$filter["x_codigo_ims"];
        $this->codigo_ims->AdvancedSearch->SearchOperator = @$filter["z_codigo_ims"];
        $this->codigo_ims->AdvancedSearch->SearchCondition = @$filter["v_codigo_ims"];
        $this->codigo_ims->AdvancedSearch->SearchValue2 = @$filter["y_codigo_ims"];
        $this->codigo_ims->AdvancedSearch->SearchOperator2 = @$filter["w_codigo_ims"];
        $this->codigo_ims->AdvancedSearch->save();

        // Field codigo
        $this->codigo->AdvancedSearch->SearchValue = @$filter["x_codigo"];
        $this->codigo->AdvancedSearch->SearchOperator = @$filter["z_codigo"];
        $this->codigo->AdvancedSearch->SearchCondition = @$filter["v_codigo"];
        $this->codigo->AdvancedSearch->SearchValue2 = @$filter["y_codigo"];
        $this->codigo->AdvancedSearch->SearchOperator2 = @$filter["w_codigo"];
        $this->codigo->AdvancedSearch->save();

        // Field nombre_comercial
        $this->nombre_comercial->AdvancedSearch->SearchValue = @$filter["x_nombre_comercial"];
        $this->nombre_comercial->AdvancedSearch->SearchOperator = @$filter["z_nombre_comercial"];
        $this->nombre_comercial->AdvancedSearch->SearchCondition = @$filter["v_nombre_comercial"];
        $this->nombre_comercial->AdvancedSearch->SearchValue2 = @$filter["y_nombre_comercial"];
        $this->nombre_comercial->AdvancedSearch->SearchOperator2 = @$filter["w_nombre_comercial"];
        $this->nombre_comercial->AdvancedSearch->save();

        // Field principio_activo
        $this->principio_activo->AdvancedSearch->SearchValue = @$filter["x_principio_activo"];
        $this->principio_activo->AdvancedSearch->SearchOperator = @$filter["z_principio_activo"];
        $this->principio_activo->AdvancedSearch->SearchCondition = @$filter["v_principio_activo"];
        $this->principio_activo->AdvancedSearch->SearchValue2 = @$filter["y_principio_activo"];
        $this->principio_activo->AdvancedSearch->SearchOperator2 = @$filter["w_principio_activo"];
        $this->principio_activo->AdvancedSearch->save();

        // Field presentacion
        $this->presentacion->AdvancedSearch->SearchValue = @$filter["x_presentacion"];
        $this->presentacion->AdvancedSearch->SearchOperator = @$filter["z_presentacion"];
        $this->presentacion->AdvancedSearch->SearchCondition = @$filter["v_presentacion"];
        $this->presentacion->AdvancedSearch->SearchValue2 = @$filter["y_presentacion"];
        $this->presentacion->AdvancedSearch->SearchOperator2 = @$filter["w_presentacion"];
        $this->presentacion->AdvancedSearch->save();

        // Field fabricante
        $this->fabricante->AdvancedSearch->SearchValue = @$filter["x_fabricante"];
        $this->fabricante->AdvancedSearch->SearchOperator = @$filter["z_fabricante"];
        $this->fabricante->AdvancedSearch->SearchCondition = @$filter["v_fabricante"];
        $this->fabricante->AdvancedSearch->SearchValue2 = @$filter["y_fabricante"];
        $this->fabricante->AdvancedSearch->SearchOperator2 = @$filter["w_fabricante"];
        $this->fabricante->AdvancedSearch->save();

        // Field codigo_de_barra
        $this->codigo_de_barra->AdvancedSearch->SearchValue = @$filter["x_codigo_de_barra"];
        $this->codigo_de_barra->AdvancedSearch->SearchOperator = @$filter["z_codigo_de_barra"];
        $this->codigo_de_barra->AdvancedSearch->SearchCondition = @$filter["v_codigo_de_barra"];
        $this->codigo_de_barra->AdvancedSearch->SearchValue2 = @$filter["y_codigo_de_barra"];
        $this->codigo_de_barra->AdvancedSearch->SearchOperator2 = @$filter["w_codigo_de_barra"];
        $this->codigo_de_barra->AdvancedSearch->save();

        // Field categoria
        $this->categoria->AdvancedSearch->SearchValue = @$filter["x_categoria"];
        $this->categoria->AdvancedSearch->SearchOperator = @$filter["z_categoria"];
        $this->categoria->AdvancedSearch->SearchCondition = @$filter["v_categoria"];
        $this->categoria->AdvancedSearch->SearchValue2 = @$filter["y_categoria"];
        $this->categoria->AdvancedSearch->SearchOperator2 = @$filter["w_categoria"];
        $this->categoria->AdvancedSearch->save();

        // Field lista_pedido
        $this->lista_pedido->AdvancedSearch->SearchValue = @$filter["x_lista_pedido"];
        $this->lista_pedido->AdvancedSearch->SearchOperator = @$filter["z_lista_pedido"];
        $this->lista_pedido->AdvancedSearch->SearchCondition = @$filter["v_lista_pedido"];
        $this->lista_pedido->AdvancedSearch->SearchValue2 = @$filter["y_lista_pedido"];
        $this->lista_pedido->AdvancedSearch->SearchOperator2 = @$filter["w_lista_pedido"];
        $this->lista_pedido->AdvancedSearch->save();

        // Field unidad_medida_defecto
        $this->unidad_medida_defecto->AdvancedSearch->SearchValue = @$filter["x_unidad_medida_defecto"];
        $this->unidad_medida_defecto->AdvancedSearch->SearchOperator = @$filter["z_unidad_medida_defecto"];
        $this->unidad_medida_defecto->AdvancedSearch->SearchCondition = @$filter["v_unidad_medida_defecto"];
        $this->unidad_medida_defecto->AdvancedSearch->SearchValue2 = @$filter["y_unidad_medida_defecto"];
        $this->unidad_medida_defecto->AdvancedSearch->SearchOperator2 = @$filter["w_unidad_medida_defecto"];
        $this->unidad_medida_defecto->AdvancedSearch->save();

        // Field cantidad_por_unidad_medida
        $this->cantidad_por_unidad_medida->AdvancedSearch->SearchValue = @$filter["x_cantidad_por_unidad_medida"];
        $this->cantidad_por_unidad_medida->AdvancedSearch->SearchOperator = @$filter["z_cantidad_por_unidad_medida"];
        $this->cantidad_por_unidad_medida->AdvancedSearch->SearchCondition = @$filter["v_cantidad_por_unidad_medida"];
        $this->cantidad_por_unidad_medida->AdvancedSearch->SearchValue2 = @$filter["y_cantidad_por_unidad_medida"];
        $this->cantidad_por_unidad_medida->AdvancedSearch->SearchOperator2 = @$filter["w_cantidad_por_unidad_medida"];
        $this->cantidad_por_unidad_medida->AdvancedSearch->save();

        // Field foto
        $this->foto->AdvancedSearch->SearchValue = @$filter["x_foto"];
        $this->foto->AdvancedSearch->SearchOperator = @$filter["z_foto"];
        $this->foto->AdvancedSearch->SearchCondition = @$filter["v_foto"];
        $this->foto->AdvancedSearch->SearchValue2 = @$filter["y_foto"];
        $this->foto->AdvancedSearch->SearchOperator2 = @$filter["w_foto"];
        $this->foto->AdvancedSearch->save();

        // Field cantidad_minima
        $this->cantidad_minima->AdvancedSearch->SearchValue = @$filter["x_cantidad_minima"];
        $this->cantidad_minima->AdvancedSearch->SearchOperator = @$filter["z_cantidad_minima"];
        $this->cantidad_minima->AdvancedSearch->SearchCondition = @$filter["v_cantidad_minima"];
        $this->cantidad_minima->AdvancedSearch->SearchValue2 = @$filter["y_cantidad_minima"];
        $this->cantidad_minima->AdvancedSearch->SearchOperator2 = @$filter["w_cantidad_minima"];
        $this->cantidad_minima->AdvancedSearch->save();

        // Field cantidad_maxima
        $this->cantidad_maxima->AdvancedSearch->SearchValue = @$filter["x_cantidad_maxima"];
        $this->cantidad_maxima->AdvancedSearch->SearchOperator = @$filter["z_cantidad_maxima"];
        $this->cantidad_maxima->AdvancedSearch->SearchCondition = @$filter["v_cantidad_maxima"];
        $this->cantidad_maxima->AdvancedSearch->SearchValue2 = @$filter["y_cantidad_maxima"];
        $this->cantidad_maxima->AdvancedSearch->SearchOperator2 = @$filter["w_cantidad_maxima"];
        $this->cantidad_maxima->AdvancedSearch->save();

        // Field cantidad_en_mano
        $this->cantidad_en_mano->AdvancedSearch->SearchValue = @$filter["x_cantidad_en_mano"];
        $this->cantidad_en_mano->AdvancedSearch->SearchOperator = @$filter["z_cantidad_en_mano"];
        $this->cantidad_en_mano->AdvancedSearch->SearchCondition = @$filter["v_cantidad_en_mano"];
        $this->cantidad_en_mano->AdvancedSearch->SearchValue2 = @$filter["y_cantidad_en_mano"];
        $this->cantidad_en_mano->AdvancedSearch->SearchOperator2 = @$filter["w_cantidad_en_mano"];
        $this->cantidad_en_mano->AdvancedSearch->save();

        // Field cantidad_en_pedido
        $this->cantidad_en_pedido->AdvancedSearch->SearchValue = @$filter["x_cantidad_en_pedido"];
        $this->cantidad_en_pedido->AdvancedSearch->SearchOperator = @$filter["z_cantidad_en_pedido"];
        $this->cantidad_en_pedido->AdvancedSearch->SearchCondition = @$filter["v_cantidad_en_pedido"];
        $this->cantidad_en_pedido->AdvancedSearch->SearchValue2 = @$filter["y_cantidad_en_pedido"];
        $this->cantidad_en_pedido->AdvancedSearch->SearchOperator2 = @$filter["w_cantidad_en_pedido"];
        $this->cantidad_en_pedido->AdvancedSearch->save();

        // Field cantidad_en_transito
        $this->cantidad_en_transito->AdvancedSearch->SearchValue = @$filter["x_cantidad_en_transito"];
        $this->cantidad_en_transito->AdvancedSearch->SearchOperator = @$filter["z_cantidad_en_transito"];
        $this->cantidad_en_transito->AdvancedSearch->SearchCondition = @$filter["v_cantidad_en_transito"];
        $this->cantidad_en_transito->AdvancedSearch->SearchValue2 = @$filter["y_cantidad_en_transito"];
        $this->cantidad_en_transito->AdvancedSearch->SearchOperator2 = @$filter["w_cantidad_en_transito"];
        $this->cantidad_en_transito->AdvancedSearch->save();

        // Field ultimo_costo
        $this->ultimo_costo->AdvancedSearch->SearchValue = @$filter["x_ultimo_costo"];
        $this->ultimo_costo->AdvancedSearch->SearchOperator = @$filter["z_ultimo_costo"];
        $this->ultimo_costo->AdvancedSearch->SearchCondition = @$filter["v_ultimo_costo"];
        $this->ultimo_costo->AdvancedSearch->SearchValue2 = @$filter["y_ultimo_costo"];
        $this->ultimo_costo->AdvancedSearch->SearchOperator2 = @$filter["w_ultimo_costo"];
        $this->ultimo_costo->AdvancedSearch->save();

        // Field descuento
        $this->descuento->AdvancedSearch->SearchValue = @$filter["x_descuento"];
        $this->descuento->AdvancedSearch->SearchOperator = @$filter["z_descuento"];
        $this->descuento->AdvancedSearch->SearchCondition = @$filter["v_descuento"];
        $this->descuento->AdvancedSearch->SearchValue2 = @$filter["y_descuento"];
        $this->descuento->AdvancedSearch->SearchOperator2 = @$filter["w_descuento"];
        $this->descuento->AdvancedSearch->save();

        // Field precio
        $this->precio->AdvancedSearch->SearchValue = @$filter["x_precio"];
        $this->precio->AdvancedSearch->SearchOperator = @$filter["z_precio"];
        $this->precio->AdvancedSearch->SearchCondition = @$filter["v_precio"];
        $this->precio->AdvancedSearch->SearchValue2 = @$filter["y_precio"];
        $this->precio->AdvancedSearch->SearchOperator2 = @$filter["w_precio"];
        $this->precio->AdvancedSearch->save();

        // Field precio2
        $this->precio2->AdvancedSearch->SearchValue = @$filter["x_precio2"];
        $this->precio2->AdvancedSearch->SearchOperator = @$filter["z_precio2"];
        $this->precio2->AdvancedSearch->SearchCondition = @$filter["v_precio2"];
        $this->precio2->AdvancedSearch->SearchValue2 = @$filter["y_precio2"];
        $this->precio2->AdvancedSearch->SearchOperator2 = @$filter["w_precio2"];
        $this->precio2->AdvancedSearch->save();

        // Field alicuota
        $this->alicuota->AdvancedSearch->SearchValue = @$filter["x_alicuota"];
        $this->alicuota->AdvancedSearch->SearchOperator = @$filter["z_alicuota"];
        $this->alicuota->AdvancedSearch->SearchCondition = @$filter["v_alicuota"];
        $this->alicuota->AdvancedSearch->SearchValue2 = @$filter["y_alicuota"];
        $this->alicuota->AdvancedSearch->SearchOperator2 = @$filter["w_alicuota"];
        $this->alicuota->AdvancedSearch->save();

        // Field articulo_inventario
        $this->articulo_inventario->AdvancedSearch->SearchValue = @$filter["x_articulo_inventario"];
        $this->articulo_inventario->AdvancedSearch->SearchOperator = @$filter["z_articulo_inventario"];
        $this->articulo_inventario->AdvancedSearch->SearchCondition = @$filter["v_articulo_inventario"];
        $this->articulo_inventario->AdvancedSearch->SearchValue2 = @$filter["y_articulo_inventario"];
        $this->articulo_inventario->AdvancedSearch->SearchOperator2 = @$filter["w_articulo_inventario"];
        $this->articulo_inventario->AdvancedSearch->save();

        // Field activo
        $this->activo->AdvancedSearch->SearchValue = @$filter["x_activo"];
        $this->activo->AdvancedSearch->SearchOperator = @$filter["z_activo"];
        $this->activo->AdvancedSearch->SearchCondition = @$filter["v_activo"];
        $this->activo->AdvancedSearch->SearchValue2 = @$filter["y_activo"];
        $this->activo->AdvancedSearch->SearchOperator2 = @$filter["w_activo"];
        $this->activo->AdvancedSearch->save();

        // Field puntos_ventas
        $this->puntos_ventas->AdvancedSearch->SearchValue = @$filter["x_puntos_ventas"];
        $this->puntos_ventas->AdvancedSearch->SearchOperator = @$filter["z_puntos_ventas"];
        $this->puntos_ventas->AdvancedSearch->SearchCondition = @$filter["v_puntos_ventas"];
        $this->puntos_ventas->AdvancedSearch->SearchValue2 = @$filter["y_puntos_ventas"];
        $this->puntos_ventas->AdvancedSearch->SearchOperator2 = @$filter["w_puntos_ventas"];
        $this->puntos_ventas->AdvancedSearch->save();

        // Field puntos_premio
        $this->puntos_premio->AdvancedSearch->SearchValue = @$filter["x_puntos_premio"];
        $this->puntos_premio->AdvancedSearch->SearchOperator = @$filter["z_puntos_premio"];
        $this->puntos_premio->AdvancedSearch->SearchCondition = @$filter["v_puntos_premio"];
        $this->puntos_premio->AdvancedSearch->SearchValue2 = @$filter["y_puntos_premio"];
        $this->puntos_premio->AdvancedSearch->SearchOperator2 = @$filter["w_puntos_premio"];
        $this->puntos_premio->AdvancedSearch->save();

        // Field sincroniza
        $this->sincroniza->AdvancedSearch->SearchValue = @$filter["x_sincroniza"];
        $this->sincroniza->AdvancedSearch->SearchOperator = @$filter["z_sincroniza"];
        $this->sincroniza->AdvancedSearch->SearchCondition = @$filter["v_sincroniza"];
        $this->sincroniza->AdvancedSearch->SearchValue2 = @$filter["y_sincroniza"];
        $this->sincroniza->AdvancedSearch->SearchOperator2 = @$filter["w_sincroniza"];
        $this->sincroniza->AdvancedSearch->save();
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
        $this->buildSearchSql($where, $this->codigo_ims, $default, false); // codigo_ims
        $this->buildSearchSql($where, $this->codigo, $default, false); // codigo
        $this->buildSearchSql($where, $this->nombre_comercial, $default, false); // nombre_comercial
        $this->buildSearchSql($where, $this->principio_activo, $default, false); // principio_activo
        $this->buildSearchSql($where, $this->presentacion, $default, false); // presentacion
        $this->buildSearchSql($where, $this->fabricante, $default, false); // fabricante
        $this->buildSearchSql($where, $this->codigo_de_barra, $default, false); // codigo_de_barra
        $this->buildSearchSql($where, $this->categoria, $default, false); // categoria
        $this->buildSearchSql($where, $this->lista_pedido, $default, false); // lista_pedido
        $this->buildSearchSql($where, $this->unidad_medida_defecto, $default, false); // unidad_medida_defecto
        $this->buildSearchSql($where, $this->cantidad_por_unidad_medida, $default, false); // cantidad_por_unidad_medida
        $this->buildSearchSql($where, $this->foto, $default, false); // foto
        $this->buildSearchSql($where, $this->cantidad_minima, $default, false); // cantidad_minima
        $this->buildSearchSql($where, $this->cantidad_maxima, $default, false); // cantidad_maxima
        $this->buildSearchSql($where, $this->cantidad_en_mano, $default, false); // cantidad_en_mano
        $this->buildSearchSql($where, $this->cantidad_en_pedido, $default, false); // cantidad_en_pedido
        $this->buildSearchSql($where, $this->cantidad_en_transito, $default, false); // cantidad_en_transito
        $this->buildSearchSql($where, $this->ultimo_costo, $default, false); // ultimo_costo
        $this->buildSearchSql($where, $this->descuento, $default, false); // descuento
        $this->buildSearchSql($where, $this->precio, $default, false); // precio
        $this->buildSearchSql($where, $this->precio2, $default, false); // precio2
        $this->buildSearchSql($where, $this->alicuota, $default, false); // alicuota
        $this->buildSearchSql($where, $this->articulo_inventario, $default, false); // articulo_inventario
        $this->buildSearchSql($where, $this->activo, $default, false); // activo
        $this->buildSearchSql($where, $this->puntos_ventas, $default, false); // puntos_ventas
        $this->buildSearchSql($where, $this->puntos_premio, $default, false); // puntos_premio
        $this->buildSearchSql($where, $this->sincroniza, $default, false); // sincroniza

        // Set up search parm
        if (!$default && $where != "" && in_array($this->Command, ["", "reset", "resetall"])) {
            $this->Command = "search";
        }
        if (!$default && $this->Command == "search") {
            $this->id->AdvancedSearch->save(); // id
            $this->codigo_ims->AdvancedSearch->save(); // codigo_ims
            $this->codigo->AdvancedSearch->save(); // codigo
            $this->nombre_comercial->AdvancedSearch->save(); // nombre_comercial
            $this->principio_activo->AdvancedSearch->save(); // principio_activo
            $this->presentacion->AdvancedSearch->save(); // presentacion
            $this->fabricante->AdvancedSearch->save(); // fabricante
            $this->codigo_de_barra->AdvancedSearch->save(); // codigo_de_barra
            $this->categoria->AdvancedSearch->save(); // categoria
            $this->lista_pedido->AdvancedSearch->save(); // lista_pedido
            $this->unidad_medida_defecto->AdvancedSearch->save(); // unidad_medida_defecto
            $this->cantidad_por_unidad_medida->AdvancedSearch->save(); // cantidad_por_unidad_medida
            $this->foto->AdvancedSearch->save(); // foto
            $this->cantidad_minima->AdvancedSearch->save(); // cantidad_minima
            $this->cantidad_maxima->AdvancedSearch->save(); // cantidad_maxima
            $this->cantidad_en_mano->AdvancedSearch->save(); // cantidad_en_mano
            $this->cantidad_en_pedido->AdvancedSearch->save(); // cantidad_en_pedido
            $this->cantidad_en_transito->AdvancedSearch->save(); // cantidad_en_transito
            $this->ultimo_costo->AdvancedSearch->save(); // ultimo_costo
            $this->descuento->AdvancedSearch->save(); // descuento
            $this->precio->AdvancedSearch->save(); // precio
            $this->precio2->AdvancedSearch->save(); // precio2
            $this->alicuota->AdvancedSearch->save(); // alicuota
            $this->articulo_inventario->AdvancedSearch->save(); // articulo_inventario
            $this->activo->AdvancedSearch->save(); // activo
            $this->puntos_ventas->AdvancedSearch->save(); // puntos_ventas
            $this->puntos_premio->AdvancedSearch->save(); // puntos_premio
            $this->sincroniza->AdvancedSearch->save(); // sincroniza
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
        $this->buildBasicSearchSql($where, $this->codigo_ims, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->codigo, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->nombre_comercial, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->principio_activo, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->presentacion, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->codigo_de_barra, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->categoria, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->lista_pedido, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->unidad_medida_defecto, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->foto, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->alicuota, $arKeywords, $type);
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
        if ($this->codigo_ims->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->codigo->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->nombre_comercial->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->principio_activo->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->presentacion->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->fabricante->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->codigo_de_barra->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->categoria->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->lista_pedido->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->unidad_medida_defecto->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->cantidad_por_unidad_medida->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->foto->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->cantidad_minima->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->cantidad_maxima->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->cantidad_en_mano->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->cantidad_en_pedido->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->cantidad_en_transito->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->ultimo_costo->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->descuento->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->precio->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->precio2->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->alicuota->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->articulo_inventario->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->activo->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->puntos_ventas->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->puntos_premio->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->sincroniza->AdvancedSearch->issetSession()) {
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
        return false;
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
                $this->codigo_ims->AdvancedSearch->unsetSession();
                $this->codigo->AdvancedSearch->unsetSession();
                $this->nombre_comercial->AdvancedSearch->unsetSession();
                $this->principio_activo->AdvancedSearch->unsetSession();
                $this->presentacion->AdvancedSearch->unsetSession();
                $this->fabricante->AdvancedSearch->unsetSession();
                $this->codigo_de_barra->AdvancedSearch->unsetSession();
                $this->categoria->AdvancedSearch->unsetSession();
                $this->lista_pedido->AdvancedSearch->unsetSession();
                $this->unidad_medida_defecto->AdvancedSearch->unsetSession();
                $this->cantidad_por_unidad_medida->AdvancedSearch->unsetSession();
                $this->foto->AdvancedSearch->unsetSession();
                $this->cantidad_minima->AdvancedSearch->unsetSession();
                $this->cantidad_maxima->AdvancedSearch->unsetSession();
                $this->cantidad_en_mano->AdvancedSearch->unsetSession();
                $this->cantidad_en_pedido->AdvancedSearch->unsetSession();
                $this->cantidad_en_transito->AdvancedSearch->unsetSession();
                $this->ultimo_costo->AdvancedSearch->unsetSession();
                $this->descuento->AdvancedSearch->unsetSession();
                $this->precio->AdvancedSearch->unsetSession();
                $this->precio2->AdvancedSearch->unsetSession();
                $this->alicuota->AdvancedSearch->unsetSession();
                $this->articulo_inventario->AdvancedSearch->unsetSession();
                $this->activo->AdvancedSearch->unsetSession();
                $this->puntos_ventas->AdvancedSearch->unsetSession();
                $this->puntos_premio->AdvancedSearch->unsetSession();
                $this->sincroniza->AdvancedSearch->unsetSession();
    }

    // Restore all search parameters
    protected function restoreSearchParms()
    {
        $this->RestoreSearch = true;

        // Restore basic search values
        $this->BasicSearch->load();

        // Restore advanced search values
                $this->id->AdvancedSearch->load();
                $this->codigo_ims->AdvancedSearch->load();
                $this->codigo->AdvancedSearch->load();
                $this->nombre_comercial->AdvancedSearch->load();
                $this->principio_activo->AdvancedSearch->load();
                $this->presentacion->AdvancedSearch->load();
                $this->fabricante->AdvancedSearch->load();
                $this->codigo_de_barra->AdvancedSearch->load();
                $this->categoria->AdvancedSearch->load();
                $this->lista_pedido->AdvancedSearch->load();
                $this->unidad_medida_defecto->AdvancedSearch->load();
                $this->cantidad_por_unidad_medida->AdvancedSearch->load();
                $this->foto->AdvancedSearch->load();
                $this->cantidad_minima->AdvancedSearch->load();
                $this->cantidad_maxima->AdvancedSearch->load();
                $this->cantidad_en_mano->AdvancedSearch->load();
                $this->cantidad_en_pedido->AdvancedSearch->load();
                $this->cantidad_en_transito->AdvancedSearch->load();
                $this->ultimo_costo->AdvancedSearch->load();
                $this->descuento->AdvancedSearch->load();
                $this->precio->AdvancedSearch->load();
                $this->precio2->AdvancedSearch->load();
                $this->alicuota->AdvancedSearch->load();
                $this->articulo_inventario->AdvancedSearch->load();
                $this->activo->AdvancedSearch->load();
                $this->puntos_ventas->AdvancedSearch->load();
                $this->puntos_premio->AdvancedSearch->load();
                $this->sincroniza->AdvancedSearch->load();
    }

    // Set up sort parameters
    protected function setupSortOrder()
    {
        // Check for "order" parameter
        if (Get("order") !== null) {
            $this->CurrentOrder = Get("order");
            $this->CurrentOrderType = Get("ordertype", "");
            $this->updateSort($this->codigo_ims); // codigo_ims
            $this->updateSort($this->codigo); // codigo
            $this->updateSort($this->principio_activo); // principio_activo
            $this->updateSort($this->fabricante); // fabricante
            $this->updateSort($this->cantidad_en_mano); // cantidad_en_mano
            $this->updateSort($this->cantidad_en_pedido); // cantidad_en_pedido
            $this->updateSort($this->cantidad_en_transito); // cantidad_en_transito
            $this->updateSort($this->descuento); // descuento
            $this->updateSort($this->activo); // activo
            $this->updateSort($this->puntos_ventas); // puntos_ventas
            $this->updateSort($this->puntos_premio); // puntos_premio
            $this->setStartRecordNumber(1); // Reset start position
        }
    }

    // Load sort order parameters
    protected function loadSortOrder()
    {
        $orderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
        if ($orderBy == "") {
            $this->DefaultSort = "`codigo_ims` ASC";
            if ($this->getSqlOrderBy() != "") {
                $useDefaultSort = true;
                if ($this->codigo_ims->getSort() != "") {
                    $useDefaultSort = false;
                }
                if ($useDefaultSort) {
                    $this->codigo_ims->setSort("ASC");
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
                $this->codigo_ims->setSort("");
                $this->codigo->setSort("");
                $this->nombre_comercial->setSort("");
                $this->principio_activo->setSort("");
                $this->presentacion->setSort("");
                $this->fabricante->setSort("");
                $this->codigo_de_barra->setSort("");
                $this->categoria->setSort("");
                $this->lista_pedido->setSort("");
                $this->unidad_medida_defecto->setSort("");
                $this->cantidad_por_unidad_medida->setSort("");
                $this->foto->setSort("");
                $this->cantidad_minima->setSort("");
                $this->cantidad_maxima->setSort("");
                $this->cantidad_en_mano->setSort("");
                $this->cantidad_en_pedido->setSort("");
                $this->cantidad_en_transito->setSort("");
                $this->ultimo_costo->setSort("");
                $this->descuento->setSort("");
                $this->precio->setSort("");
                $this->precio2->setSort("");
                $this->alicuota->setSort("");
                $this->articulo_inventario->setSort("");
                $this->activo->setSort("");
                $this->puntos_ventas->setSort("");
                $this->puntos_premio->setSort("");
                $this->sincroniza->setSort("");
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

        // "delete"
        $item = &$this->ListOptions->add("delete");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canDelete();
        $item->OnLeft = true;

        // "detail_articulo_unidad_medida"
        $item = &$this->ListOptions->add("detail_articulo_unidad_medida");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->allowList(CurrentProjectID() . 'articulo_unidad_medida') && !$this->ShowMultipleDetails;
        $item->OnLeft = true;
        $item->ShowInButtonGroup = false;

        // "detail_adjunto"
        $item = &$this->ListOptions->add("detail_adjunto");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->allowList(CurrentProjectID() . 'adjunto') && !$this->ShowMultipleDetails;
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
        $pages->add("articulo_unidad_medida");
        $pages->add("adjunto");
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

        // "detail_articulo_unidad_medida"
        $opt = $this->ListOptions["detail_articulo_unidad_medida"];
        if ($Security->allowList(CurrentProjectID() . 'articulo_unidad_medida')) {
            $body = $Language->phrase("DetailLink") . $Language->TablePhrase("articulo_unidad_medida", "TblCaption");
            $body = "<a class=\"btn btn-default ew-row-link ew-detail\" data-action=\"list\" href=\"" . HtmlEncode("ArticuloUnidadMedidaList?" . Config("TABLE_SHOW_MASTER") . "=articulo&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue) . "") . "\">" . $body . "</a>";
            $links = "";
            $detailPage = Container("ArticuloUnidadMedidaGrid");
            if ($detailPage->DetailView && $Security->canView() && $Security->allowView(CurrentProjectID() . 'articulo')) {
                $caption = $Language->phrase("MasterDetailViewLink");
                $url = $this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=articulo_unidad_medida");
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-view\" data-action=\"view\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode($url) . "\">" . HtmlImageAndText($caption) . "</a></li>";
                if ($detailViewTblVar != "") {
                    $detailViewTblVar .= ",";
                }
                $detailViewTblVar .= "articulo_unidad_medida";
            }
            if ($detailPage->DetailEdit && $Security->canEdit() && $Security->allowEdit(CurrentProjectID() . 'articulo')) {
                $caption = $Language->phrase("MasterDetailEditLink");
                $url = $this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=articulo_unidad_medida");
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-edit\" data-action=\"edit\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode($url) . "\">" . HtmlImageAndText($caption) . "</a></li>";
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
            $opt->Body = $body;
            if ($this->ShowMultipleDetails) {
                $opt->Visible = false;
            }
        }

        // "detail_adjunto"
        $opt = $this->ListOptions["detail_adjunto"];
        if ($Security->allowList(CurrentProjectID() . 'adjunto')) {
            $body = $Language->phrase("DetailLink") . $Language->TablePhrase("adjunto", "TblCaption");
            $body = "<a class=\"btn btn-default ew-row-link ew-detail\" data-action=\"list\" href=\"" . HtmlEncode("AdjuntoList?" . Config("TABLE_SHOW_MASTER") . "=articulo&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue) . "") . "\">" . $body . "</a>";
            $links = "";
            $detailPage = Container("AdjuntoGrid");
            if ($detailPage->DetailView && $Security->canView() && $Security->allowView(CurrentProjectID() . 'articulo')) {
                $caption = $Language->phrase("MasterDetailViewLink");
                $url = $this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=adjunto");
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-view\" data-action=\"view\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode($url) . "\">" . HtmlImageAndText($caption) . "</a></li>";
                if ($detailViewTblVar != "") {
                    $detailViewTblVar .= ",";
                }
                $detailViewTblVar .= "adjunto";
            }
            if ($detailPage->DetailEdit && $Security->canEdit() && $Security->allowEdit(CurrentProjectID() . 'articulo')) {
                $caption = $Language->phrase("MasterDetailEditLink");
                $url = $this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=adjunto");
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-edit\" data-action=\"edit\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode($url) . "\">" . HtmlImageAndText($caption) . "</a></li>";
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
                $item = &$option->add("detailadd_articulo_unidad_medida");
                $url = $this->getAddUrl(Config("TABLE_SHOW_DETAIL") . "=articulo_unidad_medida");
                $detailPage = Container("ArticuloUnidadMedidaGrid");
                $caption = $Language->phrase("Add") . "&nbsp;" . $this->tableCaption() . "/" . $detailPage->tableCaption();
                $item->Body = "<a class=\"ew-detail-add-group ew-detail-add\" title=\"" . HtmlTitle($caption) . "\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode(GetUrl($url)) . "\">" . $caption . "</a>";
                $item->Visible = ($detailPage->DetailAdd && $Security->allowAdd(CurrentProjectID() . 'articulo') && $Security->canAdd());
                if ($item->Visible) {
                    if ($detailTableLink != "") {
                        $detailTableLink .= ",";
                    }
                    $detailTableLink .= "articulo_unidad_medida";
                }
                $item = &$option->add("detailadd_adjunto");
                $url = $this->getAddUrl(Config("TABLE_SHOW_DETAIL") . "=adjunto");
                $detailPage = Container("AdjuntoGrid");
                $caption = $Language->phrase("Add") . "&nbsp;" . $this->tableCaption() . "/" . $detailPage->tableCaption();
                $item->Body = "<a class=\"ew-detail-add-group ew-detail-add\" title=\"" . HtmlTitle($caption) . "\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode(GetUrl($url)) . "\">" . $caption . "</a>";
                $item->Visible = ($detailPage->DetailAdd && $Security->allowAdd(CurrentProjectID() . 'articulo') && $Security->canAdd());
                if ($item->Visible) {
                    if ($detailTableLink != "") {
                        $detailTableLink .= ",";
                    }
                    $detailTableLink .= "adjunto";
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
        $item->Body = "<a class=\"ew-save-filter\" data-form=\"farticulolistsrch\" href=\"#\" onclick=\"return false;\">" . $Language->phrase("SaveCurrentFilter") . "</a>";
        $item->Visible = true;
        $item = &$this->FilterOptions->add("deletefilter");
        $item->Body = "<a class=\"ew-delete-filter\" data-form=\"farticulolistsrch\" href=\"#\" onclick=\"return false;\">" . $Language->phrase("DeleteFilter") . "</a>";
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
                $item->Body = '<a class="ew-action ew-list-action" title="' . HtmlEncode($caption) . '" data-caption="' . HtmlEncode($caption) . '" href="#" onclick="return ew.submitAction(event,jQuery.extend({f:document.farticulolist},' . $listaction->toJson(true) . '));">' . $icon . '</a>';
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
        $sqlwrk = "`articulo`=" . AdjustSql($this->id->CurrentValue, $this->Dbid) . "";

        // Column "detail_articulo_unidad_medida"
        if ($this->DetailPages && $this->DetailPages["articulo_unidad_medida"] && $this->DetailPages["articulo_unidad_medida"]->Visible && $Security->allowList(CurrentProjectID() . 'articulo_unidad_medida')) {
            $link = "";
            $option = $this->ListOptions["detail_articulo_unidad_medida"];
            $url = "ArticuloUnidadMedidaPreview?t=articulo&f=" . Encrypt($sqlwrk);
            $btngrp = "<div data-table=\"articulo_unidad_medida\" data-url=\"" . $url . "\">";
            if ($Security->allowList(CurrentProjectID() . 'articulo')) {
                $label = $Language->TablePhrase("articulo_unidad_medida", "TblCaption");
                $link = "<li class=\"nav-item\"><a href=\"#\" class=\"nav-link\" data-toggle=\"tab\" data-table=\"articulo_unidad_medida\" data-url=\"" . $url . "\">" . $label . "</a></li>";
                $links .= $link;
                $detaillnk = JsEncodeAttribute("ArticuloUnidadMedidaList?" . Config("TABLE_SHOW_MASTER") . "=articulo&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue) . "");
                $btngrp .= "<a href=\"#\" class=\"mr-2\" title=\"" . $Language->TablePhrase("articulo_unidad_medida", "TblCaption") . "\" onclick=\"window.location='" . $detaillnk . "';return false;\">" . $Language->phrase("MasterDetailListLink") . "</a>";
            }
            $detailPageObj = Container("ArticuloUnidadMedidaGrid");
            if ($detailPageObj->DetailView && $Security->canView() && $Security->allowView(CurrentProjectID() . 'articulo')) {
                $caption = $Language->phrase("MasterDetailViewLink");
                $url = $this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=articulo_unidad_medida");
                $btngrp .= "<a href=\"#\" class=\"mr-2\" title=\"" . HtmlTitle($caption) . "\" onclick=\"window.location='" . HtmlEncode($url) . "';return false;\">" . $caption . "</a>";
            }
            if ($detailPageObj->DetailEdit && $Security->canEdit() && $Security->allowEdit(CurrentProjectID() . 'articulo')) {
                $caption = $Language->phrase("MasterDetailEditLink");
                $url = $this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=articulo_unidad_medida");
                $btngrp .= "<a href=\"#\" class=\"mr-2\" title=\"" . HtmlTitle($caption) . "\" onclick=\"window.location='" . HtmlEncode($url) . "';return false;\">" . $caption . "</a>";
            }
            $btngrp .= "</div>";
            if ($link != "") {
                $btngrps .= $btngrp;
                $option->Body .= "<div class=\"d-none ew-preview\">" . $link . $btngrp . "</div>";
            }
        }
        $sqlwrk = "`articulo`=" . AdjustSql($this->id->CurrentValue, $this->Dbid) . "";

        // Column "detail_adjunto"
        if ($this->DetailPages && $this->DetailPages["adjunto"] && $this->DetailPages["adjunto"]->Visible && $Security->allowList(CurrentProjectID() . 'adjunto')) {
            $link = "";
            $option = $this->ListOptions["detail_adjunto"];
            $url = "AdjuntoPreview?t=articulo&f=" . Encrypt($sqlwrk);
            $btngrp = "<div data-table=\"adjunto\" data-url=\"" . $url . "\">";
            if ($Security->allowList(CurrentProjectID() . 'articulo')) {
                $label = $Language->TablePhrase("adjunto", "TblCaption");
                $link = "<li class=\"nav-item\"><a href=\"#\" class=\"nav-link\" data-toggle=\"tab\" data-table=\"adjunto\" data-url=\"" . $url . "\">" . $label . "</a></li>";
                $links .= $link;
                $detaillnk = JsEncodeAttribute("AdjuntoList?" . Config("TABLE_SHOW_MASTER") . "=articulo&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue) . "");
                $btngrp .= "<a href=\"#\" class=\"mr-2\" title=\"" . $Language->TablePhrase("adjunto", "TblCaption") . "\" onclick=\"window.location='" . $detaillnk . "';return false;\">" . $Language->phrase("MasterDetailListLink") . "</a>";
            }
            $detailPageObj = Container("AdjuntoGrid");
            if ($detailPageObj->DetailView && $Security->canView() && $Security->allowView(CurrentProjectID() . 'articulo')) {
                $caption = $Language->phrase("MasterDetailViewLink");
                $url = $this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=adjunto");
                $btngrp .= "<a href=\"#\" class=\"mr-2\" title=\"" . HtmlTitle($caption) . "\" onclick=\"window.location='" . HtmlEncode($url) . "';return false;\">" . $caption . "</a>";
            }
            if ($detailPageObj->DetailEdit && $Security->canEdit() && $Security->allowEdit(CurrentProjectID() . 'articulo')) {
                $caption = $Language->phrase("MasterDetailEditLink");
                $url = $this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=adjunto");
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

        // codigo_ims
        if (!$this->isAddOrEdit() && $this->codigo_ims->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->codigo_ims->AdvancedSearch->SearchValue != "" || $this->codigo_ims->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // codigo
        if (!$this->isAddOrEdit() && $this->codigo->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->codigo->AdvancedSearch->SearchValue != "" || $this->codigo->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // nombre_comercial
        if (!$this->isAddOrEdit() && $this->nombre_comercial->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->nombre_comercial->AdvancedSearch->SearchValue != "" || $this->nombre_comercial->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // principio_activo
        if (!$this->isAddOrEdit() && $this->principio_activo->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->principio_activo->AdvancedSearch->SearchValue != "" || $this->principio_activo->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // presentacion
        if (!$this->isAddOrEdit() && $this->presentacion->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->presentacion->AdvancedSearch->SearchValue != "" || $this->presentacion->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // fabricante
        if (!$this->isAddOrEdit() && $this->fabricante->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->fabricante->AdvancedSearch->SearchValue != "" || $this->fabricante->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // codigo_de_barra
        if (!$this->isAddOrEdit() && $this->codigo_de_barra->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->codigo_de_barra->AdvancedSearch->SearchValue != "" || $this->codigo_de_barra->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // categoria
        if (!$this->isAddOrEdit() && $this->categoria->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->categoria->AdvancedSearch->SearchValue != "" || $this->categoria->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
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

        // unidad_medida_defecto
        if (!$this->isAddOrEdit() && $this->unidad_medida_defecto->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->unidad_medida_defecto->AdvancedSearch->SearchValue != "" || $this->unidad_medida_defecto->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // cantidad_por_unidad_medida
        if (!$this->isAddOrEdit() && $this->cantidad_por_unidad_medida->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->cantidad_por_unidad_medida->AdvancedSearch->SearchValue != "" || $this->cantidad_por_unidad_medida->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // foto
        if (!$this->isAddOrEdit() && $this->foto->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->foto->AdvancedSearch->SearchValue != "" || $this->foto->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // cantidad_minima
        if (!$this->isAddOrEdit() && $this->cantidad_minima->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->cantidad_minima->AdvancedSearch->SearchValue != "" || $this->cantidad_minima->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // cantidad_maxima
        if (!$this->isAddOrEdit() && $this->cantidad_maxima->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->cantidad_maxima->AdvancedSearch->SearchValue != "" || $this->cantidad_maxima->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // cantidad_en_mano
        if (!$this->isAddOrEdit() && $this->cantidad_en_mano->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->cantidad_en_mano->AdvancedSearch->SearchValue != "" || $this->cantidad_en_mano->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // cantidad_en_pedido
        if (!$this->isAddOrEdit() && $this->cantidad_en_pedido->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->cantidad_en_pedido->AdvancedSearch->SearchValue != "" || $this->cantidad_en_pedido->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // cantidad_en_transito
        if (!$this->isAddOrEdit() && $this->cantidad_en_transito->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->cantidad_en_transito->AdvancedSearch->SearchValue != "" || $this->cantidad_en_transito->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // ultimo_costo
        if (!$this->isAddOrEdit() && $this->ultimo_costo->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->ultimo_costo->AdvancedSearch->SearchValue != "" || $this->ultimo_costo->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
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

        // precio
        if (!$this->isAddOrEdit() && $this->precio->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->precio->AdvancedSearch->SearchValue != "" || $this->precio->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // precio2
        if (!$this->isAddOrEdit() && $this->precio2->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->precio2->AdvancedSearch->SearchValue != "" || $this->precio2->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // alicuota
        if (!$this->isAddOrEdit() && $this->alicuota->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->alicuota->AdvancedSearch->SearchValue != "" || $this->alicuota->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // articulo_inventario
        if (!$this->isAddOrEdit() && $this->articulo_inventario->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->articulo_inventario->AdvancedSearch->SearchValue != "" || $this->articulo_inventario->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
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

        // puntos_ventas
        if (!$this->isAddOrEdit() && $this->puntos_ventas->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->puntos_ventas->AdvancedSearch->SearchValue != "" || $this->puntos_ventas->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // puntos_premio
        if (!$this->isAddOrEdit() && $this->puntos_premio->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->puntos_premio->AdvancedSearch->SearchValue != "" || $this->puntos_premio->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // sincroniza
        if (!$this->isAddOrEdit() && $this->sincroniza->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->sincroniza->AdvancedSearch->SearchValue != "" || $this->sincroniza->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
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
        if ($this->descuento->FormValue == $this->descuento->CurrentValue && is_numeric(ConvertToFloatString($this->descuento->CurrentValue))) {
            $this->descuento->CurrentValue = ConvertToFloatString($this->descuento->CurrentValue);
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

            // codigo_ims
            $this->codigo_ims->LinkCustomAttributes = "";
            $this->codigo_ims->HrefValue = "";
            $this->codigo_ims->TooltipValue = "";

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

            // descuento
            $this->descuento->LinkCustomAttributes = "";
            $this->descuento->HrefValue = "";
            $this->descuento->TooltipValue = "";

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
        } elseif ($this->RowType == ROWTYPE_SEARCH) {
            // codigo_ims
            $this->codigo_ims->EditAttrs["class"] = "form-control";
            $this->codigo_ims->EditCustomAttributes = "";
            if (!$this->codigo_ims->Raw) {
                $this->codigo_ims->AdvancedSearch->SearchValue = HtmlDecode($this->codigo_ims->AdvancedSearch->SearchValue);
            }
            $this->codigo_ims->EditValue = HtmlEncode($this->codigo_ims->AdvancedSearch->SearchValue);
            $this->codigo_ims->PlaceHolder = RemoveHtml($this->codigo_ims->caption());

            // codigo
            $this->codigo->EditAttrs["class"] = "form-control";
            $this->codigo->EditCustomAttributes = "";
            if (!$this->codigo->Raw) {
                $this->codigo->AdvancedSearch->SearchValue = HtmlDecode($this->codigo->AdvancedSearch->SearchValue);
            }
            $this->codigo->EditValue = HtmlEncode($this->codigo->AdvancedSearch->SearchValue);
            $this->codigo->PlaceHolder = RemoveHtml($this->codigo->caption());

            // principio_activo
            $this->principio_activo->EditAttrs["class"] = "form-control";
            $this->principio_activo->EditCustomAttributes = "";
            if (!$this->principio_activo->Raw) {
                $this->principio_activo->AdvancedSearch->SearchValue = HtmlDecode($this->principio_activo->AdvancedSearch->SearchValue);
            }
            $this->principio_activo->EditValue = HtmlEncode($this->principio_activo->AdvancedSearch->SearchValue);
            $this->principio_activo->PlaceHolder = RemoveHtml($this->principio_activo->caption());

            // fabricante
            $this->fabricante->EditCustomAttributes = "";
            $curVal = trim(strval($this->fabricante->AdvancedSearch->SearchValue));
            if ($curVal != "") {
                $this->fabricante->AdvancedSearch->ViewValue = $this->fabricante->lookupCacheOption($curVal);
            } else {
                $this->fabricante->AdvancedSearch->ViewValue = $this->fabricante->Lookup !== null && is_array($this->fabricante->Lookup->Options) ? $curVal : null;
            }
            if ($this->fabricante->AdvancedSearch->ViewValue !== null) { // Load from cache
                $this->fabricante->EditValue = array_values($this->fabricante->Lookup->Options);
                if ($this->fabricante->AdvancedSearch->ViewValue == "") {
                    $this->fabricante->AdvancedSearch->ViewValue = $Language->phrase("PleaseSelect");
                }
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`Id`" . SearchString("=", $this->fabricante->AdvancedSearch->SearchValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->fabricante->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->fabricante->Lookup->renderViewRow($rswrk[0]);
                    $this->fabricante->AdvancedSearch->ViewValue = $this->fabricante->displayValue($arwrk);
                } else {
                    $this->fabricante->AdvancedSearch->ViewValue = $Language->phrase("PleaseSelect");
                }
                $arwrk = $rswrk;
                $this->fabricante->EditValue = $arwrk;
            }
            $this->fabricante->PlaceHolder = RemoveHtml($this->fabricante->caption());

            // cantidad_en_mano
            $this->cantidad_en_mano->EditAttrs["class"] = "form-control";
            $this->cantidad_en_mano->EditCustomAttributes = "";
            $this->cantidad_en_mano->EditValue = HtmlEncode($this->cantidad_en_mano->AdvancedSearch->SearchValue);
            $this->cantidad_en_mano->PlaceHolder = RemoveHtml($this->cantidad_en_mano->caption());

            // cantidad_en_pedido
            $this->cantidad_en_pedido->EditAttrs["class"] = "form-control";
            $this->cantidad_en_pedido->EditCustomAttributes = "";
            $this->cantidad_en_pedido->EditValue = HtmlEncode($this->cantidad_en_pedido->AdvancedSearch->SearchValue);
            $this->cantidad_en_pedido->PlaceHolder = RemoveHtml($this->cantidad_en_pedido->caption());

            // cantidad_en_transito
            $this->cantidad_en_transito->EditAttrs["class"] = "form-control";
            $this->cantidad_en_transito->EditCustomAttributes = "";
            $this->cantidad_en_transito->EditValue = HtmlEncode($this->cantidad_en_transito->AdvancedSearch->SearchValue);
            $this->cantidad_en_transito->PlaceHolder = RemoveHtml($this->cantidad_en_transito->caption());

            // descuento
            $this->descuento->EditAttrs["class"] = "form-control";
            $this->descuento->EditCustomAttributes = "";
            $this->descuento->EditValue = HtmlEncode($this->descuento->AdvancedSearch->SearchValue);
            $this->descuento->PlaceHolder = RemoveHtml($this->descuento->caption());

            // activo
            $this->activo->EditAttrs["class"] = "form-control";
            $this->activo->EditCustomAttributes = "";
            $this->activo->EditValue = $this->activo->options(true);
            $this->activo->PlaceHolder = RemoveHtml($this->activo->caption());

            // puntos_ventas
            $this->puntos_ventas->EditAttrs["class"] = "form-control";
            $this->puntos_ventas->EditCustomAttributes = "";
            $this->puntos_ventas->EditValue = HtmlEncode($this->puntos_ventas->AdvancedSearch->SearchValue);
            $this->puntos_ventas->PlaceHolder = RemoveHtml($this->puntos_ventas->caption());

            // puntos_premio
            $this->puntos_premio->EditAttrs["class"] = "form-control";
            $this->puntos_premio->EditCustomAttributes = "";
            $this->puntos_premio->EditValue = HtmlEncode($this->puntos_premio->AdvancedSearch->SearchValue);
            $this->puntos_premio->PlaceHolder = RemoveHtml($this->puntos_premio->caption());
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
        $this->codigo_ims->AdvancedSearch->load();
        $this->codigo->AdvancedSearch->load();
        $this->nombre_comercial->AdvancedSearch->load();
        $this->principio_activo->AdvancedSearch->load();
        $this->presentacion->AdvancedSearch->load();
        $this->fabricante->AdvancedSearch->load();
        $this->codigo_de_barra->AdvancedSearch->load();
        $this->categoria->AdvancedSearch->load();
        $this->lista_pedido->AdvancedSearch->load();
        $this->unidad_medida_defecto->AdvancedSearch->load();
        $this->cantidad_por_unidad_medida->AdvancedSearch->load();
        $this->foto->AdvancedSearch->load();
        $this->cantidad_minima->AdvancedSearch->load();
        $this->cantidad_maxima->AdvancedSearch->load();
        $this->cantidad_en_mano->AdvancedSearch->load();
        $this->cantidad_en_pedido->AdvancedSearch->load();
        $this->cantidad_en_transito->AdvancedSearch->load();
        $this->ultimo_costo->AdvancedSearch->load();
        $this->descuento->AdvancedSearch->load();
        $this->precio->AdvancedSearch->load();
        $this->precio2->AdvancedSearch->load();
        $this->alicuota->AdvancedSearch->load();
        $this->articulo_inventario->AdvancedSearch->load();
        $this->activo->AdvancedSearch->load();
        $this->puntos_ventas->AdvancedSearch->load();
        $this->puntos_premio->AdvancedSearch->load();
        $this->sincroniza->AdvancedSearch->load();
    }

    // Get export HTML tag
    protected function getExportTag($type, $custom = false)
    {
        global $Language;
        $pageUrl = $this->pageUrl();
        if (SameText($type, "excel")) {
            if ($custom) {
                return "<a href=\"#\" class=\"ew-export-link ew-excel\" title=\"" . HtmlEncode($Language->phrase("ExportToExcelText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToExcelText")) . "\" onclick=\"return ew.export(document.farticulolist, '" . $this->ExportExcelUrl . "', 'excel', true);\">" . $Language->phrase("ExportToExcel") . "</a>";
            } else {
                return "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ew-export-link ew-excel\" title=\"" . HtmlEncode($Language->phrase("ExportToExcelText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToExcelText")) . "\">" . $Language->phrase("ExportToExcel") . "</a>";
            }
        } elseif (SameText($type, "word")) {
            if ($custom) {
                return "<a href=\"#\" class=\"ew-export-link ew-word\" title=\"" . HtmlEncode($Language->phrase("ExportToWordText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToWordText")) . "\" onclick=\"return ew.export(document.farticulolist, '" . $this->ExportWordUrl . "', 'word', true);\">" . $Language->phrase("ExportToWord") . "</a>";
            } else {
                return "<a href=\"" . $this->ExportWordUrl . "\" class=\"ew-export-link ew-word\" title=\"" . HtmlEncode($Language->phrase("ExportToWordText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToWordText")) . "\">" . $Language->phrase("ExportToWord") . "</a>";
            }
        } elseif (SameText($type, "pdf")) {
            if ($custom) {
                return "<a href=\"#\" class=\"ew-export-link ew-pdf\" title=\"" . HtmlEncode($Language->phrase("ExportToPDFText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToPDFText")) . "\" onclick=\"return ew.export(document.farticulolist, '" . $this->ExportPdfUrl . "', 'pdf', true);\">" . $Language->phrase("ExportToPDF") . "</a>";
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
            return '<button id="emf_articulo" class="ew-export-link ew-email" title="' . $Language->phrase("ExportToEmailText") . '" data-caption="' . $Language->phrase("ExportToEmailText") . '" onclick="ew.emailDialogShow({lnk:\'emf_articulo\', hdr:ew.language.phrase(\'ExportToEmailText\'), f:document.farticulolist, sel:false' . $url . '});">' . $Language->phrase("ExportToEmail") . '</button>';
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
        $item->Body = "<a class=\"btn btn-default ew-search-toggle" . $searchToggleClass . "\" href=\"#\" role=\"button\" title=\"" . $Language->phrase("SearchPanel") . "\" data-caption=\"" . $Language->phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"farticulolistsrch\" aria-pressed=\"" . ($searchToggleClass == " active" ? "true" : "false") . "\">" . $Language->phrase("SearchLink") . "</a>";
        $item->Visible = true;

        // Show all button
        $item = &$this->SearchOptions->add("showall");
        $item->Body = "<a class=\"btn btn-default ew-show-all\" title=\"" . $Language->phrase("ShowAll") . "\" data-caption=\"" . $Language->phrase("ShowAll") . "\" href=\"" . $pageUrl . "cmd=reset\">" . $Language->phrase("ShowAllBtn") . "</a>";
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
    	/*
    	$sql = "SELECT SUM(cantidad_en_mano) AS cantidad from articulo;";
    	$cantidad = ExecuteScalar($sql);
    	*/
    	$header = '<div class="row">
    				<div class="col-sm-3 col-md-2">
    					<div style="color:#222; background-color:#8ad3d3;" class="alert" role="alert">Sin Existencia</div>
    				</div>
    				<div class="col-sm-3 col-md-2">
    					<div style="color:#222; background-color:#ffcccc;" class="alert" role="alert">Desactivado</div>
    				</div>
    			</div>';
    	/*
    				<div class="col-sm-3 col-md-2">
    					<div style="color:#222; background-color:#ffcc99;" class="alert" role="alert">Total General Articulos <b>' . number_format($cantidad, 2, ".", ".") . ' Unidades</b></div>
    				</div>
    	*/
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
    	$opt = &$this->ListOptions->Add("foto");
    	$opt->Header = "Foto";
    	$opt->OnLeft = FALSE; // Link on left
    	$opt->MoveTo(0); // Move to first column
    	$opt = &$this->ListOptions->Add("qr");
    	$opt->Header = "";
    	$opt->OnLeft = TRUE; // Link on left
    	$opt->MoveTo(4); // Move to first column
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
    	$precio = 0.00;
    	$precio_USD = 0.00;
    	if($this->id->CurrentValue != "") {
            $tarifa = ExecuteScalar("SELECT nombre FROM tarifa WHERE patron = 'S';");
    		$sql = "SELECT 
    				b.precio 
    			FROM 
    				tarifa AS a JOIN tarifa_articulo AS b ON b.tarifa = a.id 
    			WHERE a.patron = 'S' AND b.articulo = " . $this->id->CurrentValue . ";";
    		$precio = floatval(ExecuteScalar($sql));
    		$sql = "SELECT tasa FROM tasa_usd WHERE moneda = 'USD' ORDER BY fecha DESC, hora DESC LIMIT 0,1;";
    		$tasa = ExecuteScalar($sql);
    		$precio_USD = $precio/$tasa;
    	}
    	if($precio != 0.00) 
    		$xPrecio = 'Tarifa ' . $tarifa . ' ' . number_format($precio, 2, ".", ",") . 'Bs ' . number_format($precio_USD, 2, ".", ",") . 'USD';
    	else
    		$xPrecio = '';
    	if(trim($this->foto->CurrentValue) != "")
    		$this->ListOptions->Items["foto"]->Body = '<a href="carpetacarga/' . $this->foto->CurrentValue . '" target="_blank"><img src="carpetacarga/' . $this->foto->CurrentValue . '" class="img-thumbnail" alt="' . $this->principio_activo->CurrentValue . '" width="150">' . $xPrecio . '</a>';
    	else
    		$this->ListOptions->Items["foto"]->Body = $xPrecio;		
    	$url = "reportes/articulo_qr.php?id=" . $this->id->CurrentValue . "";
    	$this->ListOptions->Items["qr"]->Body = '<a class="fas fa-qrcode" href="' . $url . '" target="_blank"  data-toggle="tooltip" title="QR" data-placement="bottom"></a>';
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
