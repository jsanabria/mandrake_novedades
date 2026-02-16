<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class RecargaList extends Recarga
{
    use MessagesTrait;

    // Page ID
    public $PageID = "list";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'recarga';

    // Page object name
    public $PageObjName = "RecargaList";

    // Rendering View
    public $RenderingView = false;

    // Grid form hidden field names
    public $FormName = "frecargalist";
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

        // Table object (recarga)
        if (!isset($GLOBALS["recarga"]) || get_class($GLOBALS["recarga"]) == PROJECT_NAMESPACE . "recarga") {
            $GLOBALS["recarga"] = &$this;
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
        $this->AddUrl = "RecargaAdd";
        $this->InlineAddUrl = $pageUrl . "action=add";
        $this->GridAddUrl = $pageUrl . "action=gridadd";
        $this->GridEditUrl = $pageUrl . "action=gridedit";
        $this->MultiDeleteUrl = "RecargaDelete";
        $this->MultiUpdateUrl = "RecargaUpdate";

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'recarga');
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
        $this->FilterOptions->TagClassName = "ew-filter-option frecargalistsrch";

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
                $doc = new $class(Container("recarga"));
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
    public $DisplayRecords = 100;
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
        $this->nro_recibo->Visible = false;
        $this->cliente->setVisibility();
        $this->fecha->setVisibility();
        $this->metodo_pago->setVisibility();
        $this->referencia->setVisibility();
        $this->reverso->Visible = false;
        $this->monto_moneda->setVisibility();
        $this->moneda->setVisibility();
        $this->tasa_moneda->Visible = false;
        $this->monto_bs->setVisibility();
        $this->tasa_usd->setVisibility();
        $this->monto_usd->setVisibility();
        $this->saldo->setVisibility();
        $this->nota->Visible = false;
        $this->cobro_cliente_reverso->Visible = false;
        $this->_username->setVisibility();
        $this->nota_recepcion->Visible = false;
        $this->id->Visible = false;
        $this->abono->Visible = false;
        $this->hideFieldsForAddEdit();

        // Global Page Loading event (in userfn*.php)
        Page_Loading();

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Set up master detail parameters
        $this->setupMasterParms();

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
        $this->setupLookupOptions($this->metodo_pago);
        $this->setupLookupOptions($this->moneda);
        $this->setupLookupOptions($this->_username);

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
            $this->DisplayRecords = 100; // Load default
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

        // Restore master/detail filter
        $this->DbMasterFilter = $this->getMasterFilter(); // Restore master filter
        $this->DbDetailFilter = $this->getDetailFilter(); // Restore detail filter
        AddFilter($filter, $this->DbDetailFilter);
        AddFilter($filter, $this->SearchWhere);

        // Load master record
        if ($this->CurrentMode != "add" && $this->getMasterFilter() != "" && $this->getCurrentMasterTable() == "abono") {
            $masterTbl = Container("abono");
            $rsmaster = $masterTbl->loadRs($this->DbMasterFilter)->fetch(\PDO::FETCH_ASSOC);
            $this->MasterRecordExists = $rsmaster !== false;
            if (!$this->MasterRecordExists) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record found
                $this->terminate("AbonoList"); // Return to master page
                return;
            } else {
                $masterTbl->loadListRowValues($rsmaster);
                $masterTbl->RowType = ROWTYPE_MASTER; // Master row
                $masterTbl->renderListRow();
            }
        }

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
                    $this->DisplayRecords = 100; // Non-numeric, load default
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
        $filterList = Concat($filterList, $this->nro_recibo->AdvancedSearch->toJson(), ","); // Field nro_recibo
        $filterList = Concat($filterList, $this->cliente->AdvancedSearch->toJson(), ","); // Field cliente
        $filterList = Concat($filterList, $this->fecha->AdvancedSearch->toJson(), ","); // Field fecha
        $filterList = Concat($filterList, $this->metodo_pago->AdvancedSearch->toJson(), ","); // Field metodo_pago
        $filterList = Concat($filterList, $this->referencia->AdvancedSearch->toJson(), ","); // Field referencia
        $filterList = Concat($filterList, $this->reverso->AdvancedSearch->toJson(), ","); // Field reverso
        $filterList = Concat($filterList, $this->monto_moneda->AdvancedSearch->toJson(), ","); // Field monto_moneda
        $filterList = Concat($filterList, $this->moneda->AdvancedSearch->toJson(), ","); // Field moneda
        $filterList = Concat($filterList, $this->tasa_moneda->AdvancedSearch->toJson(), ","); // Field tasa_moneda
        $filterList = Concat($filterList, $this->monto_bs->AdvancedSearch->toJson(), ","); // Field monto_bs
        $filterList = Concat($filterList, $this->tasa_usd->AdvancedSearch->toJson(), ","); // Field tasa_usd
        $filterList = Concat($filterList, $this->monto_usd->AdvancedSearch->toJson(), ","); // Field monto_usd
        $filterList = Concat($filterList, $this->saldo->AdvancedSearch->toJson(), ","); // Field saldo
        $filterList = Concat($filterList, $this->nota->AdvancedSearch->toJson(), ","); // Field nota
        $filterList = Concat($filterList, $this->cobro_cliente_reverso->AdvancedSearch->toJson(), ","); // Field cobro_cliente_reverso
        $filterList = Concat($filterList, $this->_username->AdvancedSearch->toJson(), ","); // Field username
        $filterList = Concat($filterList, $this->nota_recepcion->AdvancedSearch->toJson(), ","); // Field nota_recepcion
        $filterList = Concat($filterList, $this->id->AdvancedSearch->toJson(), ","); // Field id
        $filterList = Concat($filterList, $this->abono->AdvancedSearch->toJson(), ","); // Field abono
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
            $UserProfile->setSearchFilters(CurrentUserName(), "frecargalistsrch", $filters);
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

        // Field nro_recibo
        $this->nro_recibo->AdvancedSearch->SearchValue = @$filter["x_nro_recibo"];
        $this->nro_recibo->AdvancedSearch->SearchOperator = @$filter["z_nro_recibo"];
        $this->nro_recibo->AdvancedSearch->SearchCondition = @$filter["v_nro_recibo"];
        $this->nro_recibo->AdvancedSearch->SearchValue2 = @$filter["y_nro_recibo"];
        $this->nro_recibo->AdvancedSearch->SearchOperator2 = @$filter["w_nro_recibo"];
        $this->nro_recibo->AdvancedSearch->save();

        // Field cliente
        $this->cliente->AdvancedSearch->SearchValue = @$filter["x_cliente"];
        $this->cliente->AdvancedSearch->SearchOperator = @$filter["z_cliente"];
        $this->cliente->AdvancedSearch->SearchCondition = @$filter["v_cliente"];
        $this->cliente->AdvancedSearch->SearchValue2 = @$filter["y_cliente"];
        $this->cliente->AdvancedSearch->SearchOperator2 = @$filter["w_cliente"];
        $this->cliente->AdvancedSearch->save();

        // Field fecha
        $this->fecha->AdvancedSearch->SearchValue = @$filter["x_fecha"];
        $this->fecha->AdvancedSearch->SearchOperator = @$filter["z_fecha"];
        $this->fecha->AdvancedSearch->SearchCondition = @$filter["v_fecha"];
        $this->fecha->AdvancedSearch->SearchValue2 = @$filter["y_fecha"];
        $this->fecha->AdvancedSearch->SearchOperator2 = @$filter["w_fecha"];
        $this->fecha->AdvancedSearch->save();

        // Field metodo_pago
        $this->metodo_pago->AdvancedSearch->SearchValue = @$filter["x_metodo_pago"];
        $this->metodo_pago->AdvancedSearch->SearchOperator = @$filter["z_metodo_pago"];
        $this->metodo_pago->AdvancedSearch->SearchCondition = @$filter["v_metodo_pago"];
        $this->metodo_pago->AdvancedSearch->SearchValue2 = @$filter["y_metodo_pago"];
        $this->metodo_pago->AdvancedSearch->SearchOperator2 = @$filter["w_metodo_pago"];
        $this->metodo_pago->AdvancedSearch->save();

        // Field referencia
        $this->referencia->AdvancedSearch->SearchValue = @$filter["x_referencia"];
        $this->referencia->AdvancedSearch->SearchOperator = @$filter["z_referencia"];
        $this->referencia->AdvancedSearch->SearchCondition = @$filter["v_referencia"];
        $this->referencia->AdvancedSearch->SearchValue2 = @$filter["y_referencia"];
        $this->referencia->AdvancedSearch->SearchOperator2 = @$filter["w_referencia"];
        $this->referencia->AdvancedSearch->save();

        // Field reverso
        $this->reverso->AdvancedSearch->SearchValue = @$filter["x_reverso"];
        $this->reverso->AdvancedSearch->SearchOperator = @$filter["z_reverso"];
        $this->reverso->AdvancedSearch->SearchCondition = @$filter["v_reverso"];
        $this->reverso->AdvancedSearch->SearchValue2 = @$filter["y_reverso"];
        $this->reverso->AdvancedSearch->SearchOperator2 = @$filter["w_reverso"];
        $this->reverso->AdvancedSearch->save();

        // Field monto_moneda
        $this->monto_moneda->AdvancedSearch->SearchValue = @$filter["x_monto_moneda"];
        $this->monto_moneda->AdvancedSearch->SearchOperator = @$filter["z_monto_moneda"];
        $this->monto_moneda->AdvancedSearch->SearchCondition = @$filter["v_monto_moneda"];
        $this->monto_moneda->AdvancedSearch->SearchValue2 = @$filter["y_monto_moneda"];
        $this->monto_moneda->AdvancedSearch->SearchOperator2 = @$filter["w_monto_moneda"];
        $this->monto_moneda->AdvancedSearch->save();

        // Field moneda
        $this->moneda->AdvancedSearch->SearchValue = @$filter["x_moneda"];
        $this->moneda->AdvancedSearch->SearchOperator = @$filter["z_moneda"];
        $this->moneda->AdvancedSearch->SearchCondition = @$filter["v_moneda"];
        $this->moneda->AdvancedSearch->SearchValue2 = @$filter["y_moneda"];
        $this->moneda->AdvancedSearch->SearchOperator2 = @$filter["w_moneda"];
        $this->moneda->AdvancedSearch->save();

        // Field tasa_moneda
        $this->tasa_moneda->AdvancedSearch->SearchValue = @$filter["x_tasa_moneda"];
        $this->tasa_moneda->AdvancedSearch->SearchOperator = @$filter["z_tasa_moneda"];
        $this->tasa_moneda->AdvancedSearch->SearchCondition = @$filter["v_tasa_moneda"];
        $this->tasa_moneda->AdvancedSearch->SearchValue2 = @$filter["y_tasa_moneda"];
        $this->tasa_moneda->AdvancedSearch->SearchOperator2 = @$filter["w_tasa_moneda"];
        $this->tasa_moneda->AdvancedSearch->save();

        // Field monto_bs
        $this->monto_bs->AdvancedSearch->SearchValue = @$filter["x_monto_bs"];
        $this->monto_bs->AdvancedSearch->SearchOperator = @$filter["z_monto_bs"];
        $this->monto_bs->AdvancedSearch->SearchCondition = @$filter["v_monto_bs"];
        $this->monto_bs->AdvancedSearch->SearchValue2 = @$filter["y_monto_bs"];
        $this->monto_bs->AdvancedSearch->SearchOperator2 = @$filter["w_monto_bs"];
        $this->monto_bs->AdvancedSearch->save();

        // Field tasa_usd
        $this->tasa_usd->AdvancedSearch->SearchValue = @$filter["x_tasa_usd"];
        $this->tasa_usd->AdvancedSearch->SearchOperator = @$filter["z_tasa_usd"];
        $this->tasa_usd->AdvancedSearch->SearchCondition = @$filter["v_tasa_usd"];
        $this->tasa_usd->AdvancedSearch->SearchValue2 = @$filter["y_tasa_usd"];
        $this->tasa_usd->AdvancedSearch->SearchOperator2 = @$filter["w_tasa_usd"];
        $this->tasa_usd->AdvancedSearch->save();

        // Field monto_usd
        $this->monto_usd->AdvancedSearch->SearchValue = @$filter["x_monto_usd"];
        $this->monto_usd->AdvancedSearch->SearchOperator = @$filter["z_monto_usd"];
        $this->monto_usd->AdvancedSearch->SearchCondition = @$filter["v_monto_usd"];
        $this->monto_usd->AdvancedSearch->SearchValue2 = @$filter["y_monto_usd"];
        $this->monto_usd->AdvancedSearch->SearchOperator2 = @$filter["w_monto_usd"];
        $this->monto_usd->AdvancedSearch->save();

        // Field saldo
        $this->saldo->AdvancedSearch->SearchValue = @$filter["x_saldo"];
        $this->saldo->AdvancedSearch->SearchOperator = @$filter["z_saldo"];
        $this->saldo->AdvancedSearch->SearchCondition = @$filter["v_saldo"];
        $this->saldo->AdvancedSearch->SearchValue2 = @$filter["y_saldo"];
        $this->saldo->AdvancedSearch->SearchOperator2 = @$filter["w_saldo"];
        $this->saldo->AdvancedSearch->save();

        // Field nota
        $this->nota->AdvancedSearch->SearchValue = @$filter["x_nota"];
        $this->nota->AdvancedSearch->SearchOperator = @$filter["z_nota"];
        $this->nota->AdvancedSearch->SearchCondition = @$filter["v_nota"];
        $this->nota->AdvancedSearch->SearchValue2 = @$filter["y_nota"];
        $this->nota->AdvancedSearch->SearchOperator2 = @$filter["w_nota"];
        $this->nota->AdvancedSearch->save();

        // Field cobro_cliente_reverso
        $this->cobro_cliente_reverso->AdvancedSearch->SearchValue = @$filter["x_cobro_cliente_reverso"];
        $this->cobro_cliente_reverso->AdvancedSearch->SearchOperator = @$filter["z_cobro_cliente_reverso"];
        $this->cobro_cliente_reverso->AdvancedSearch->SearchCondition = @$filter["v_cobro_cliente_reverso"];
        $this->cobro_cliente_reverso->AdvancedSearch->SearchValue2 = @$filter["y_cobro_cliente_reverso"];
        $this->cobro_cliente_reverso->AdvancedSearch->SearchOperator2 = @$filter["w_cobro_cliente_reverso"];
        $this->cobro_cliente_reverso->AdvancedSearch->save();

        // Field username
        $this->_username->AdvancedSearch->SearchValue = @$filter["x__username"];
        $this->_username->AdvancedSearch->SearchOperator = @$filter["z__username"];
        $this->_username->AdvancedSearch->SearchCondition = @$filter["v__username"];
        $this->_username->AdvancedSearch->SearchValue2 = @$filter["y__username"];
        $this->_username->AdvancedSearch->SearchOperator2 = @$filter["w__username"];
        $this->_username->AdvancedSearch->save();

        // Field nota_recepcion
        $this->nota_recepcion->AdvancedSearch->SearchValue = @$filter["x_nota_recepcion"];
        $this->nota_recepcion->AdvancedSearch->SearchOperator = @$filter["z_nota_recepcion"];
        $this->nota_recepcion->AdvancedSearch->SearchCondition = @$filter["v_nota_recepcion"];
        $this->nota_recepcion->AdvancedSearch->SearchValue2 = @$filter["y_nota_recepcion"];
        $this->nota_recepcion->AdvancedSearch->SearchOperator2 = @$filter["w_nota_recepcion"];
        $this->nota_recepcion->AdvancedSearch->save();

        // Field id
        $this->id->AdvancedSearch->SearchValue = @$filter["x_id"];
        $this->id->AdvancedSearch->SearchOperator = @$filter["z_id"];
        $this->id->AdvancedSearch->SearchCondition = @$filter["v_id"];
        $this->id->AdvancedSearch->SearchValue2 = @$filter["y_id"];
        $this->id->AdvancedSearch->SearchOperator2 = @$filter["w_id"];
        $this->id->AdvancedSearch->save();

        // Field abono
        $this->abono->AdvancedSearch->SearchValue = @$filter["x_abono"];
        $this->abono->AdvancedSearch->SearchOperator = @$filter["z_abono"];
        $this->abono->AdvancedSearch->SearchCondition = @$filter["v_abono"];
        $this->abono->AdvancedSearch->SearchValue2 = @$filter["y_abono"];
        $this->abono->AdvancedSearch->SearchOperator2 = @$filter["w_abono"];
        $this->abono->AdvancedSearch->save();
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
        $this->buildSearchSql($where, $this->nro_recibo, $default, false); // nro_recibo
        $this->buildSearchSql($where, $this->cliente, $default, false); // cliente
        $this->buildSearchSql($where, $this->fecha, $default, false); // fecha
        $this->buildSearchSql($where, $this->metodo_pago, $default, false); // metodo_pago
        $this->buildSearchSql($where, $this->referencia, $default, false); // referencia
        $this->buildSearchSql($where, $this->reverso, $default, false); // reverso
        $this->buildSearchSql($where, $this->monto_moneda, $default, false); // monto_moneda
        $this->buildSearchSql($where, $this->moneda, $default, false); // moneda
        $this->buildSearchSql($where, $this->tasa_moneda, $default, false); // tasa_moneda
        $this->buildSearchSql($where, $this->monto_bs, $default, false); // monto_bs
        $this->buildSearchSql($where, $this->tasa_usd, $default, false); // tasa_usd
        $this->buildSearchSql($where, $this->monto_usd, $default, false); // monto_usd
        $this->buildSearchSql($where, $this->saldo, $default, false); // saldo
        $this->buildSearchSql($where, $this->nota, $default, false); // nota
        $this->buildSearchSql($where, $this->cobro_cliente_reverso, $default, false); // cobro_cliente_reverso
        $this->buildSearchSql($where, $this->_username, $default, false); // username
        $this->buildSearchSql($where, $this->nota_recepcion, $default, false); // nota_recepcion
        $this->buildSearchSql($where, $this->id, $default, false); // id
        $this->buildSearchSql($where, $this->abono, $default, false); // abono

        // Set up search parm
        if (!$default && $where != "" && in_array($this->Command, ["", "reset", "resetall"])) {
            $this->Command = "search";
        }
        if (!$default && $this->Command == "search") {
            $this->nro_recibo->AdvancedSearch->save(); // nro_recibo
            $this->cliente->AdvancedSearch->save(); // cliente
            $this->fecha->AdvancedSearch->save(); // fecha
            $this->metodo_pago->AdvancedSearch->save(); // metodo_pago
            $this->referencia->AdvancedSearch->save(); // referencia
            $this->reverso->AdvancedSearch->save(); // reverso
            $this->monto_moneda->AdvancedSearch->save(); // monto_moneda
            $this->moneda->AdvancedSearch->save(); // moneda
            $this->tasa_moneda->AdvancedSearch->save(); // tasa_moneda
            $this->monto_bs->AdvancedSearch->save(); // monto_bs
            $this->tasa_usd->AdvancedSearch->save(); // tasa_usd
            $this->monto_usd->AdvancedSearch->save(); // monto_usd
            $this->saldo->AdvancedSearch->save(); // saldo
            $this->nota->AdvancedSearch->save(); // nota
            $this->cobro_cliente_reverso->AdvancedSearch->save(); // cobro_cliente_reverso
            $this->_username->AdvancedSearch->save(); // username
            $this->nota_recepcion->AdvancedSearch->save(); // nota_recepcion
            $this->id->AdvancedSearch->save(); // id
            $this->abono->AdvancedSearch->save(); // abono
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
        $this->buildBasicSearchSql($where, $this->metodo_pago, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->referencia, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->moneda, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->nota, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->_username, $arKeywords, $type);
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
        if ($this->nro_recibo->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->cliente->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->fecha->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->metodo_pago->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->referencia->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->reverso->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->monto_moneda->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->moneda->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->tasa_moneda->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->monto_bs->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->tasa_usd->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->monto_usd->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->saldo->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->nota->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->cobro_cliente_reverso->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->_username->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->nota_recepcion->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->id->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->abono->AdvancedSearch->issetSession()) {
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
                $this->nro_recibo->AdvancedSearch->unsetSession();
                $this->cliente->AdvancedSearch->unsetSession();
                $this->fecha->AdvancedSearch->unsetSession();
                $this->metodo_pago->AdvancedSearch->unsetSession();
                $this->referencia->AdvancedSearch->unsetSession();
                $this->reverso->AdvancedSearch->unsetSession();
                $this->monto_moneda->AdvancedSearch->unsetSession();
                $this->moneda->AdvancedSearch->unsetSession();
                $this->tasa_moneda->AdvancedSearch->unsetSession();
                $this->monto_bs->AdvancedSearch->unsetSession();
                $this->tasa_usd->AdvancedSearch->unsetSession();
                $this->monto_usd->AdvancedSearch->unsetSession();
                $this->saldo->AdvancedSearch->unsetSession();
                $this->nota->AdvancedSearch->unsetSession();
                $this->cobro_cliente_reverso->AdvancedSearch->unsetSession();
                $this->_username->AdvancedSearch->unsetSession();
                $this->nota_recepcion->AdvancedSearch->unsetSession();
                $this->id->AdvancedSearch->unsetSession();
                $this->abono->AdvancedSearch->unsetSession();
    }

    // Restore all search parameters
    protected function restoreSearchParms()
    {
        $this->RestoreSearch = true;

        // Restore basic search values
        $this->BasicSearch->load();

        // Restore advanced search values
                $this->nro_recibo->AdvancedSearch->load();
                $this->cliente->AdvancedSearch->load();
                $this->fecha->AdvancedSearch->load();
                $this->metodo_pago->AdvancedSearch->load();
                $this->referencia->AdvancedSearch->load();
                $this->reverso->AdvancedSearch->load();
                $this->monto_moneda->AdvancedSearch->load();
                $this->moneda->AdvancedSearch->load();
                $this->tasa_moneda->AdvancedSearch->load();
                $this->monto_bs->AdvancedSearch->load();
                $this->tasa_usd->AdvancedSearch->load();
                $this->monto_usd->AdvancedSearch->load();
                $this->saldo->AdvancedSearch->load();
                $this->nota->AdvancedSearch->load();
                $this->cobro_cliente_reverso->AdvancedSearch->load();
                $this->_username->AdvancedSearch->load();
                $this->nota_recepcion->AdvancedSearch->load();
                $this->id->AdvancedSearch->load();
                $this->abono->AdvancedSearch->load();
    }

    // Set up sort parameters
    protected function setupSortOrder()
    {
        // Check for "order" parameter
        if (Get("order") !== null) {
            $this->CurrentOrder = Get("order");
            $this->CurrentOrderType = Get("ordertype", "");
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

            // Reset master/detail keys
            if ($this->Command == "resetall") {
                $this->setCurrentMasterTable(""); // Clear master table
                $this->DbMasterFilter = "";
                $this->DbDetailFilter = "";
                        $this->abono->setSessionValue("");
            }

            // Reset (clear) sorting order
            if ($this->Command == "resetsort") {
                $orderBy = "";
                $this->setSessionOrderBy($orderBy);
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
        $item->Body = "<a class=\"ew-save-filter\" data-form=\"frecargalistsrch\" href=\"#\" onclick=\"return false;\">" . $Language->phrase("SaveCurrentFilter") . "</a>";
        $item->Visible = true;
        $item = &$this->FilterOptions->add("deletefilter");
        $item->Body = "<a class=\"ew-delete-filter\" data-form=\"frecargalistsrch\" href=\"#\" onclick=\"return false;\">" . $Language->phrase("DeleteFilter") . "</a>";
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
                $item->Body = '<a class="ew-action ew-list-action" title="' . HtmlEncode($caption) . '" data-caption="' . HtmlEncode($caption) . '" href="#" onclick="return ew.submitAction(event,jQuery.extend({f:document.frecargalist},' . $listaction->toJson(true) . '));">' . $icon . '</a>';
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

        // nro_recibo
        if (!$this->isAddOrEdit() && $this->nro_recibo->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->nro_recibo->AdvancedSearch->SearchValue != "" || $this->nro_recibo->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
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

        // fecha
        if (!$this->isAddOrEdit() && $this->fecha->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->fecha->AdvancedSearch->SearchValue != "" || $this->fecha->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // metodo_pago
        if (!$this->isAddOrEdit() && $this->metodo_pago->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->metodo_pago->AdvancedSearch->SearchValue != "" || $this->metodo_pago->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // referencia
        if (!$this->isAddOrEdit() && $this->referencia->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->referencia->AdvancedSearch->SearchValue != "" || $this->referencia->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // reverso
        if (!$this->isAddOrEdit() && $this->reverso->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->reverso->AdvancedSearch->SearchValue != "" || $this->reverso->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // monto_moneda
        if (!$this->isAddOrEdit() && $this->monto_moneda->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->monto_moneda->AdvancedSearch->SearchValue != "" || $this->monto_moneda->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
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

        // tasa_moneda
        if (!$this->isAddOrEdit() && $this->tasa_moneda->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->tasa_moneda->AdvancedSearch->SearchValue != "" || $this->tasa_moneda->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // monto_bs
        if (!$this->isAddOrEdit() && $this->monto_bs->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->monto_bs->AdvancedSearch->SearchValue != "" || $this->monto_bs->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // tasa_usd
        if (!$this->isAddOrEdit() && $this->tasa_usd->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->tasa_usd->AdvancedSearch->SearchValue != "" || $this->tasa_usd->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
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

        // saldo
        if (!$this->isAddOrEdit() && $this->saldo->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->saldo->AdvancedSearch->SearchValue != "" || $this->saldo->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
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

        // cobro_cliente_reverso
        if (!$this->isAddOrEdit() && $this->cobro_cliente_reverso->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->cobro_cliente_reverso->AdvancedSearch->SearchValue != "" || $this->cobro_cliente_reverso->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
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

        // nota_recepcion
        if (!$this->isAddOrEdit() && $this->nota_recepcion->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->nota_recepcion->AdvancedSearch->SearchValue != "" || $this->nota_recepcion->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // id
        if (!$this->isAddOrEdit() && $this->id->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->id->AdvancedSearch->SearchValue != "" || $this->id->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // abono
        if (!$this->isAddOrEdit() && $this->abono->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->abono->AdvancedSearch->SearchValue != "" || $this->abono->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
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
        $this->nro_recibo->setDbValue($row['nro_recibo']);
        $this->cliente->setDbValue($row['cliente']);
        $this->fecha->setDbValue($row['fecha']);
        $this->metodo_pago->setDbValue($row['metodo_pago']);
        $this->referencia->setDbValue($row['referencia']);
        $this->reverso->setDbValue($row['reverso']);
        $this->monto_moneda->setDbValue($row['monto_moneda']);
        $this->moneda->setDbValue($row['moneda']);
        $this->tasa_moneda->setDbValue($row['tasa_moneda']);
        $this->monto_bs->setDbValue($row['monto_bs']);
        $this->tasa_usd->setDbValue($row['tasa_usd']);
        $this->monto_usd->setDbValue($row['monto_usd']);
        $this->saldo->setDbValue($row['saldo']);
        $this->nota->setDbValue($row['nota']);
        $this->cobro_cliente_reverso->setDbValue($row['cobro_cliente_reverso']);
        $this->_username->setDbValue($row['username']);
        $this->nota_recepcion->setDbValue($row['nota_recepcion']);
        $this->id->setDbValue($row['id']);
        $this->abono->setDbValue($row['abono']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['nro_recibo'] = null;
        $row['cliente'] = null;
        $row['fecha'] = null;
        $row['metodo_pago'] = null;
        $row['referencia'] = null;
        $row['reverso'] = null;
        $row['monto_moneda'] = null;
        $row['moneda'] = null;
        $row['tasa_moneda'] = null;
        $row['monto_bs'] = null;
        $row['tasa_usd'] = null;
        $row['monto_usd'] = null;
        $row['saldo'] = null;
        $row['nota'] = null;
        $row['cobro_cliente_reverso'] = null;
        $row['username'] = null;
        $row['nota_recepcion'] = null;
        $row['id'] = null;
        $row['abono'] = null;
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
        if ($this->monto_moneda->FormValue == $this->monto_moneda->CurrentValue && is_numeric(ConvertToFloatString($this->monto_moneda->CurrentValue))) {
            $this->monto_moneda->CurrentValue = ConvertToFloatString($this->monto_moneda->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->monto_bs->FormValue == $this->monto_bs->CurrentValue && is_numeric(ConvertToFloatString($this->monto_bs->CurrentValue))) {
            $this->monto_bs->CurrentValue = ConvertToFloatString($this->monto_bs->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->tasa_usd->FormValue == $this->tasa_usd->CurrentValue && is_numeric(ConvertToFloatString($this->tasa_usd->CurrentValue))) {
            $this->tasa_usd->CurrentValue = ConvertToFloatString($this->tasa_usd->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->monto_usd->FormValue == $this->monto_usd->CurrentValue && is_numeric(ConvertToFloatString($this->monto_usd->CurrentValue))) {
            $this->monto_usd->CurrentValue = ConvertToFloatString($this->monto_usd->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->saldo->FormValue == $this->saldo->CurrentValue && is_numeric(ConvertToFloatString($this->saldo->CurrentValue))) {
            $this->saldo->CurrentValue = ConvertToFloatString($this->saldo->CurrentValue);
        }

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // nro_recibo

        // cliente

        // fecha

        // metodo_pago

        // referencia

        // reverso

        // monto_moneda

        // moneda

        // tasa_moneda

        // monto_bs

        // tasa_usd

        // monto_usd

        // saldo

        // nota

        // cobro_cliente_reverso

        // username

        // nota_recepcion

        // id

        // abono
        if ($this->RowType == ROWTYPE_VIEW) {
            // nro_recibo
            $this->nro_recibo->ViewValue = $this->nro_recibo->CurrentValue;
            $this->nro_recibo->ViewValue = FormatNumber($this->nro_recibo->ViewValue, 0, -2, -2, -2);
            $this->nro_recibo->ViewCustomAttributes = "";

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

            // fecha
            $this->fecha->ViewValue = $this->fecha->CurrentValue;
            $this->fecha->ViewValue = FormatDateTime($this->fecha->ViewValue, 7);
            $this->fecha->ViewCustomAttributes = "";

            // metodo_pago
            $curVal = trim(strval($this->metodo_pago->CurrentValue));
            if ($curVal != "") {
                $this->metodo_pago->ViewValue = $this->metodo_pago->lookupCacheOption($curVal);
                if ($this->metodo_pago->ViewValue === null) { // Lookup from database
                    $filterWrk = "`valor1`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $lookupFilter = function() {
                        return CurrentPageID() == "add" ? "`codigo` = '009' AND valor1 <> 'RC'" : "`codigo` = '009'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    $sqlWrk = $this->metodo_pago->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->metodo_pago->Lookup->renderViewRow($rswrk[0]);
                        $this->metodo_pago->ViewValue = $this->metodo_pago->displayValue($arwrk);
                    } else {
                        $this->metodo_pago->ViewValue = $this->metodo_pago->CurrentValue;
                    }
                }
            } else {
                $this->metodo_pago->ViewValue = null;
            }
            $this->metodo_pago->ViewCustomAttributes = "";

            // referencia
            $this->referencia->ViewValue = $this->referencia->CurrentValue;
            $this->referencia->ViewCustomAttributes = "";

            // reverso
            if (strval($this->reverso->CurrentValue) != "") {
                $this->reverso->ViewValue = $this->reverso->optionCaption($this->reverso->CurrentValue);
            } else {
                $this->reverso->ViewValue = null;
            }
            $this->reverso->ViewCustomAttributes = "";

            // monto_moneda
            $this->monto_moneda->ViewValue = $this->monto_moneda->CurrentValue;
            $this->monto_moneda->ViewValue = FormatNumber($this->monto_moneda->ViewValue, 2, -1, -2, -1);
            $this->monto_moneda->ViewCustomAttributes = "";

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

            // tasa_moneda
            $this->tasa_moneda->ViewValue = $this->tasa_moneda->CurrentValue;
            $this->tasa_moneda->ViewValue = FormatNumber($this->tasa_moneda->ViewValue, 2, -2, -2, -2);
            $this->tasa_moneda->ViewCustomAttributes = "";

            // monto_bs
            $this->monto_bs->ViewValue = $this->monto_bs->CurrentValue;
            $this->monto_bs->ViewValue = FormatNumber($this->monto_bs->ViewValue, 2, -2, -2, -2);
            $this->monto_bs->ViewCustomAttributes = "";

            // tasa_usd
            $this->tasa_usd->ViewValue = $this->tasa_usd->CurrentValue;
            $this->tasa_usd->ViewValue = FormatNumber($this->tasa_usd->ViewValue, 2, -2, -2, -2);
            $this->tasa_usd->ViewCustomAttributes = "";

            // monto_usd
            $this->monto_usd->ViewValue = $this->monto_usd->CurrentValue;
            $this->monto_usd->ViewValue = FormatNumber($this->monto_usd->ViewValue, 2, -1, -2, -1);
            $this->monto_usd->CssClass = "font-weight-bold";
            $this->monto_usd->ViewCustomAttributes = "";

            // saldo
            $this->saldo->ViewValue = $this->saldo->CurrentValue;
            $this->saldo->ViewValue = FormatNumber($this->saldo->ViewValue, 2, -1, -1, -1);
            $this->saldo->CssClass = "font-weight-bold font-italic";
            $this->saldo->ViewCustomAttributes = "";

            // nota
            $this->nota->ViewValue = $this->nota->CurrentValue;
            $this->nota->ViewCustomAttributes = "";

            // cobro_cliente_reverso
            $this->cobro_cliente_reverso->ViewValue = $this->cobro_cliente_reverso->CurrentValue;
            $this->cobro_cliente_reverso->ViewValue = FormatNumber($this->cobro_cliente_reverso->ViewValue, 0, -2, -2, -2);
            $this->cobro_cliente_reverso->ViewCustomAttributes = "";

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

            // nota_recepcion
            $this->nota_recepcion->ViewValue = $this->nota_recepcion->CurrentValue;
            $this->nota_recepcion->ViewValue = FormatNumber($this->nota_recepcion->ViewValue, 0, -2, -2, -2);
            $this->nota_recepcion->ViewCustomAttributes = "";

            // id
            $this->id->ViewValue = $this->id->CurrentValue;
            $this->id->ViewCustomAttributes = "";

            // abono
            $this->abono->ViewValue = $this->abono->CurrentValue;
            $this->abono->ViewValue = FormatNumber($this->abono->ViewValue, 0, -2, -2, -2);
            $this->abono->ViewCustomAttributes = "";

            // cliente
            $this->cliente->LinkCustomAttributes = "";
            $this->cliente->HrefValue = "";
            $this->cliente->TooltipValue = "";

            // fecha
            $this->fecha->LinkCustomAttributes = "";
            $this->fecha->HrefValue = "";
            $this->fecha->TooltipValue = "";

            // metodo_pago
            $this->metodo_pago->LinkCustomAttributes = "";
            $this->metodo_pago->HrefValue = "";
            $this->metodo_pago->TooltipValue = "";

            // referencia
            $this->referencia->LinkCustomAttributes = "";
            $this->referencia->HrefValue = "";
            $this->referencia->TooltipValue = "";

            // monto_moneda
            $this->monto_moneda->LinkCustomAttributes = "";
            $this->monto_moneda->HrefValue = "";
            $this->monto_moneda->TooltipValue = "";

            // moneda
            $this->moneda->LinkCustomAttributes = "";
            $this->moneda->HrefValue = "";
            $this->moneda->TooltipValue = "";

            // monto_bs
            $this->monto_bs->LinkCustomAttributes = "";
            $this->monto_bs->HrefValue = "";
            $this->monto_bs->TooltipValue = "";

            // tasa_usd
            $this->tasa_usd->LinkCustomAttributes = "";
            $this->tasa_usd->HrefValue = "";
            $this->tasa_usd->TooltipValue = "";

            // monto_usd
            $this->monto_usd->LinkCustomAttributes = "";
            $this->monto_usd->HrefValue = "";
            $this->monto_usd->TooltipValue = "";

            // saldo
            $this->saldo->LinkCustomAttributes = "";
            $this->saldo->HrefValue = "";
            $this->saldo->TooltipValue = "";

            // username
            $this->_username->LinkCustomAttributes = "";
            $this->_username->HrefValue = "";
            $this->_username->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_SEARCH) {
            // cliente
            $this->cliente->EditAttrs["class"] = "form-control";
            $this->cliente->EditCustomAttributes = "";
            $this->cliente->PlaceHolder = RemoveHtml($this->cliente->caption());

            // fecha
            $this->fecha->EditAttrs["class"] = "form-control";
            $this->fecha->EditCustomAttributes = "";
            $this->fecha->EditValue = HtmlEncode(FormatDateTime(UnFormatDateTime($this->fecha->AdvancedSearch->SearchValue, 7), 7));
            $this->fecha->PlaceHolder = RemoveHtml($this->fecha->caption());

            // metodo_pago
            $this->metodo_pago->EditAttrs["class"] = "form-control";
            $this->metodo_pago->EditCustomAttributes = "";
            $curVal = trim(strval($this->metodo_pago->AdvancedSearch->SearchValue));
            if ($curVal != "") {
                $this->metodo_pago->AdvancedSearch->ViewValue = $this->metodo_pago->lookupCacheOption($curVal);
            } else {
                $this->metodo_pago->AdvancedSearch->ViewValue = $this->metodo_pago->Lookup !== null && is_array($this->metodo_pago->Lookup->Options) ? $curVal : null;
            }
            if ($this->metodo_pago->AdvancedSearch->ViewValue !== null) { // Load from cache
                $this->metodo_pago->EditValue = array_values($this->metodo_pago->Lookup->Options);
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`valor1`" . SearchString("=", $this->metodo_pago->AdvancedSearch->SearchValue, DATATYPE_STRING, "");
                }
                $lookupFilter = function() {
                    return CurrentPageID() == "add" ? "`codigo` = '009' AND valor1 <> 'RC'" : "`codigo` = '009'";
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->metodo_pago->Lookup->getSql(true, $filterWrk, $lookupFilter, $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->metodo_pago->EditValue = $arwrk;
            }
            $this->metodo_pago->PlaceHolder = RemoveHtml($this->metodo_pago->caption());

            // referencia
            $this->referencia->EditAttrs["class"] = "form-control";
            $this->referencia->EditCustomAttributes = "";
            if (!$this->referencia->Raw) {
                $this->referencia->AdvancedSearch->SearchValue = HtmlDecode($this->referencia->AdvancedSearch->SearchValue);
            }
            $this->referencia->EditValue = HtmlEncode($this->referencia->AdvancedSearch->SearchValue);
            $this->referencia->PlaceHolder = RemoveHtml($this->referencia->caption());

            // monto_moneda
            $this->monto_moneda->EditAttrs["class"] = "form-control";
            $this->monto_moneda->EditCustomAttributes = "";
            $this->monto_moneda->EditValue = HtmlEncode($this->monto_moneda->AdvancedSearch->SearchValue);
            $this->monto_moneda->PlaceHolder = RemoveHtml($this->monto_moneda->caption());

            // moneda
            $this->moneda->EditAttrs["class"] = "form-control";
            $this->moneda->EditCustomAttributes = "";
            $curVal = trim(strval($this->moneda->AdvancedSearch->SearchValue));
            if ($curVal != "") {
                $this->moneda->AdvancedSearch->ViewValue = $this->moneda->lookupCacheOption($curVal);
            } else {
                $this->moneda->AdvancedSearch->ViewValue = $this->moneda->Lookup !== null && is_array($this->moneda->Lookup->Options) ? $curVal : null;
            }
            if ($this->moneda->AdvancedSearch->ViewValue !== null) { // Load from cache
                $this->moneda->EditValue = array_values($this->moneda->Lookup->Options);
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`valor1`" . SearchString("=", $this->moneda->AdvancedSearch->SearchValue, DATATYPE_STRING, "");
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

            // monto_bs
            $this->monto_bs->EditAttrs["class"] = "form-control";
            $this->monto_bs->EditCustomAttributes = "";
            $this->monto_bs->EditValue = HtmlEncode($this->monto_bs->AdvancedSearch->SearchValue);
            $this->monto_bs->PlaceHolder = RemoveHtml($this->monto_bs->caption());

            // tasa_usd
            $this->tasa_usd->EditAttrs["class"] = "form-control";
            $this->tasa_usd->EditCustomAttributes = "";
            $this->tasa_usd->EditValue = HtmlEncode($this->tasa_usd->AdvancedSearch->SearchValue);
            $this->tasa_usd->PlaceHolder = RemoveHtml($this->tasa_usd->caption());

            // monto_usd
            $this->monto_usd->EditAttrs["class"] = "form-control";
            $this->monto_usd->EditCustomAttributes = "";
            $this->monto_usd->EditValue = HtmlEncode($this->monto_usd->AdvancedSearch->SearchValue);
            $this->monto_usd->PlaceHolder = RemoveHtml($this->monto_usd->caption());

            // saldo
            $this->saldo->EditAttrs["class"] = "form-control";
            $this->saldo->EditCustomAttributes = "";
            $this->saldo->EditValue = HtmlEncode($this->saldo->AdvancedSearch->SearchValue);
            $this->saldo->PlaceHolder = RemoveHtml($this->saldo->caption());

            // username
            $this->_username->EditAttrs["class"] = "form-control";
            $this->_username->EditCustomAttributes = "";
            if (!$this->_username->Raw) {
                $this->_username->AdvancedSearch->SearchValue = HtmlDecode($this->_username->AdvancedSearch->SearchValue);
            }
            $this->_username->EditValue = HtmlEncode($this->_username->AdvancedSearch->SearchValue);
            $this->_username->PlaceHolder = RemoveHtml($this->_username->caption());
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
        $this->nro_recibo->AdvancedSearch->load();
        $this->cliente->AdvancedSearch->load();
        $this->fecha->AdvancedSearch->load();
        $this->metodo_pago->AdvancedSearch->load();
        $this->referencia->AdvancedSearch->load();
        $this->reverso->AdvancedSearch->load();
        $this->monto_moneda->AdvancedSearch->load();
        $this->moneda->AdvancedSearch->load();
        $this->tasa_moneda->AdvancedSearch->load();
        $this->monto_bs->AdvancedSearch->load();
        $this->tasa_usd->AdvancedSearch->load();
        $this->monto_usd->AdvancedSearch->load();
        $this->saldo->AdvancedSearch->load();
        $this->nota->AdvancedSearch->load();
        $this->cobro_cliente_reverso->AdvancedSearch->load();
        $this->_username->AdvancedSearch->load();
        $this->nota_recepcion->AdvancedSearch->load();
        $this->id->AdvancedSearch->load();
        $this->abono->AdvancedSearch->load();
    }

    // Get export HTML tag
    protected function getExportTag($type, $custom = false)
    {
        global $Language;
        $pageUrl = $this->pageUrl();
        if (SameText($type, "excel")) {
            if ($custom) {
                return "<a href=\"#\" class=\"ew-export-link ew-excel\" title=\"" . HtmlEncode($Language->phrase("ExportToExcelText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToExcelText")) . "\" onclick=\"return ew.export(document.frecargalist, '" . $this->ExportExcelUrl . "', 'excel', true);\">" . $Language->phrase("ExportToExcel") . "</a>";
            } else {
                return "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ew-export-link ew-excel\" title=\"" . HtmlEncode($Language->phrase("ExportToExcelText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToExcelText")) . "\">" . $Language->phrase("ExportToExcel") . "</a>";
            }
        } elseif (SameText($type, "word")) {
            if ($custom) {
                return "<a href=\"#\" class=\"ew-export-link ew-word\" title=\"" . HtmlEncode($Language->phrase("ExportToWordText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToWordText")) . "\" onclick=\"return ew.export(document.frecargalist, '" . $this->ExportWordUrl . "', 'word', true);\">" . $Language->phrase("ExportToWord") . "</a>";
            } else {
                return "<a href=\"" . $this->ExportWordUrl . "\" class=\"ew-export-link ew-word\" title=\"" . HtmlEncode($Language->phrase("ExportToWordText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToWordText")) . "\">" . $Language->phrase("ExportToWord") . "</a>";
            }
        } elseif (SameText($type, "pdf")) {
            if ($custom) {
                return "<a href=\"#\" class=\"ew-export-link ew-pdf\" title=\"" . HtmlEncode($Language->phrase("ExportToPDFText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToPDFText")) . "\" onclick=\"return ew.export(document.frecargalist, '" . $this->ExportPdfUrl . "', 'pdf', true);\">" . $Language->phrase("ExportToPDF") . "</a>";
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
            return '<button id="emf_recarga" class="ew-export-link ew-email" title="' . $Language->phrase("ExportToEmailText") . '" data-caption="' . $Language->phrase("ExportToEmailText") . '" onclick="ew.emailDialogShow({lnk:\'emf_recarga\', hdr:ew.language.phrase(\'ExportToEmailText\'), f:document.frecargalist, sel:false' . $url . '});">' . $Language->phrase("ExportToEmail") . '</button>';
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
        $item->Body = "<a class=\"btn btn-default ew-search-toggle" . $searchToggleClass . "\" href=\"#\" role=\"button\" title=\"" . $Language->phrase("SearchPanel") . "\" data-caption=\"" . $Language->phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"frecargalistsrch\" aria-pressed=\"" . ($searchToggleClass == " active" ? "true" : "false") . "\">" . $Language->phrase("SearchLink") . "</a>";
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

        // Export master record
        if (Config("EXPORT_MASTER_RECORD") && $this->getMasterFilter() != "" && $this->getCurrentMasterTable() == "abono") {
            $abono = Container("abono");
            $rsmaster = $abono->loadRs($this->DbMasterFilter); // Load master record
            if ($rsmaster) {
                $exportStyle = $doc->Style;
                $doc->setStyle("v"); // Change to vertical
                if (!$this->isExport("csv") || Config("EXPORT_MASTER_RECORD_FOR_CSV")) {
                    $doc->Table = $abono;
                    $abono->exportDocument($doc, new Recordset($rsmaster));
                    $doc->exportEmptyRow();
                    $doc->Table = &$this;
                }
                $doc->setStyle($exportStyle); // Restore
                $rsmaster->closeCursor();
            }
        }
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

    // Set up master/detail based on QueryString
    protected function setupMasterParms()
    {
        $validMaster = false;
        // Get the keys for master table
        if (($master = Get(Config("TABLE_SHOW_MASTER"), Get(Config("TABLE_MASTER")))) !== null) {
            $masterTblVar = $master;
            if ($masterTblVar == "") {
                $validMaster = true;
                $this->DbMasterFilter = "";
                $this->DbDetailFilter = "";
            }
            if ($masterTblVar == "abono") {
                $validMaster = true;
                $masterTbl = Container("abono");
                if (($parm = Get("fk_id", Get("abono"))) !== null) {
                    $masterTbl->id->setQueryStringValue($parm);
                    $this->abono->setQueryStringValue($masterTbl->id->QueryStringValue);
                    $this->abono->setSessionValue($this->abono->QueryStringValue);
                    if (!is_numeric($masterTbl->id->QueryStringValue)) {
                        $validMaster = false;
                    }
                } else {
                    $validMaster = false;
                }
            }
        } elseif (($master = Post(Config("TABLE_SHOW_MASTER"), Post(Config("TABLE_MASTER")))) !== null) {
            $masterTblVar = $master;
            if ($masterTblVar == "") {
                    $validMaster = true;
                    $this->DbMasterFilter = "";
                    $this->DbDetailFilter = "";
            }
            if ($masterTblVar == "abono") {
                $validMaster = true;
                $masterTbl = Container("abono");
                if (($parm = Post("fk_id", Post("abono"))) !== null) {
                    $masterTbl->id->setFormValue($parm);
                    $this->abono->setFormValue($masterTbl->id->FormValue);
                    $this->abono->setSessionValue($this->abono->FormValue);
                    if (!is_numeric($masterTbl->id->FormValue)) {
                        $validMaster = false;
                    }
                } else {
                    $validMaster = false;
                }
            }
        }
        if ($validMaster) {
            // Update URL
            $this->AddUrl = $this->addMasterUrl($this->AddUrl);
            $this->InlineAddUrl = $this->addMasterUrl($this->InlineAddUrl);
            $this->GridAddUrl = $this->addMasterUrl($this->GridAddUrl);
            $this->GridEditUrl = $this->addMasterUrl($this->GridEditUrl);

            // Save current master table
            $this->setCurrentMasterTable($masterTblVar);

            // Reset start record counter (new master key)
            if (!$this->isAddOrEdit()) {
                $this->StartRecord = 1;
                $this->setStartRecordNumber($this->StartRecord);
            }

            // Clear previous master key from Session
            if ($masterTblVar != "abono") {
                if ($this->abono->CurrentValue == "") {
                    $this->abono->setSessionValue("");
                }
            }
        }
        $this->DbMasterFilter = $this->getMasterFilter(); // Get master filter
        $this->DbDetailFilter = $this->getDetailFilter(); // Get detail filter
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
                    break;
                case "x_metodo_pago":
                    $lookupFilter = function () {
                        return CurrentPageID() == "add" ? "`codigo` = '009' AND valor1 <> 'RC'" : "`codigo` = '009'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    break;
                case "x_reverso":
                    break;
                case "x_moneda":
                    $lookupFilter = function () {
                        return "`codigo` = '006'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    break;
                case "x__username":
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
    public function pageDataRendering(&$header)
    {
        // Example:
        //$header = "your header";
    	if(CurrentPageID() == "list") { 
            $where = "1";
            if(isset($_REQUEST["x_fecha"]) and trim($_REQUEST["x_fecha"]) != "") {
                $arr = explode("/", $_REQUEST["x_fecha"]);
                $fd = $arr[2] . "-" . $arr[1] . "-" . $arr[0];
                $where .= " AND fecha <= '$fd'";
            }
            if(isset($_REQUEST["x_metodo_pago"]) AND trim($_REQUEST["x_metodo_pago"]) != "") {
                $where .= (trim($where) == "" ? "" : " AND ") . "metodo_pago = '" . $_REQUEST["x_metodo_pago"] . "'";
            }
            if(isset($_REQUEST["x_moneda"]) AND trim($_REQUEST["x_moneda"]) != "") {
            	$where .= (trim($where) == "" ? "" : " AND ") . "moneda = '" . $_REQUEST["x_moneda"] . "'";
            }
    		if(isset($_REQUEST["xCliente"]) and intval($_REQUEST["xCliente"]) > 0) {
    			$cliente = intval($_REQUEST["xCliente"]);
                $where .= (trim($where) == "" ? "" : " AND ") . "cliente = $cliente";
    			$sql = "SELECT IFNULL(SUM(monto_usd), 0) AS saldo FROM recarga
    					WHERE $where;"; 
    			$saldo = doubleval(ExecuteScalar($sql));
    			$sql = "SELECT nombre FROM cliente WHERE id = $cliente";
    			$NombreCliente = ExecuteScalar($sql);
    			if ($saldo >= 0) {
    				$header = '<div class="alert alert-success" role="alert">
    							Saldo (+) del cliente USD: ' . $NombreCliente . ': <strong><i>' . number_format($saldo, 2, ",", ".") . '</i></strong>
    						</div>';
    			}
    			else {
    				$header = '<div class="alert alert-danger" role="alert">
    							Saldo (-) del cliente USD: ' . $NombreCliente . ': <strong><i>' . number_format($saldo, 2, ",", ".") . '</i></strong>
    						</div>';
    			}
    		}
    		else {
    			$sql = "SELECT IFNULL(SUM(monto_usd), 0) AS saldo FROM recarga
    					WHERE $where;"; 
    			$saldo = doubleval(ExecuteScalar($sql));
    			if ($saldo >= 0) {
    				$header = '<div class="alert alert-success" role="alert">
    							Saldo (+) clientes USD: <strong><i>' . number_format($saldo, 2, ",", ".") . '</i></strong>
    						</div>';
    			}
    			else {
    				$header = '<div class="alert alert-danger" role="alert">
    							Saldo (-) clientes USD: <strong><i>' . number_format($saldo, 2, ",", ".") . '</i></strong>
    						</div>';
    			}
    		} 
    	}
    	/*else {
    		$header .= '<input type="text" name="x_fecha" value="' . $_REQUEST["x_fecha"] . '">';
    		$header .= '<input type="text" name="y_fecha" value="' . $_REQUEST["y_fecha"] . '">';
    		$header .= '<input type="text" name="x_metodo_pago" value="' . $_REQUEST["x_metodo_pago"] . '">';
    		$header .= '<input type="text" name="x_moneda" value="' . $_REQUEST["x_moneda"] . '">';
    		$header .= '<input type="text" name="x_cliente" value="' . $_REQUEST["x_cliente"] . '">';
    	}*/
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
    public function listOptionsLoad()
    {
        // Example:
        //$opt = &$this->ListOptions->Add("new");
        //$opt->Header = "xxx";
        //$opt->OnLeft = true; // Link on left
        //$opt->MoveTo(0); // Move to first column
    }

    // ListOptions Rendering event
    public function listOptionsRendering()
    {
        //Container("DetailTableGrid")->DetailAdd = (...condition...); // Set to true or false conditionally
        //Container("DetailTableGrid")->DetailEdit = (...condition...); // Set to true or false conditionally
        //Container("DetailTableGrid")->DetailView = (...condition...); // Set to true or false conditionally
    }

    // ListOptions Rendered event
    public function listOptionsRendered()
    {
        // Example:
        //$this->ListOptions["new"]->Body = "xxx";
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
