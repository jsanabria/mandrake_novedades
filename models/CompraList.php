<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class CompraList extends Compra
{
    use MessagesTrait;

    // Page ID
    public $PageID = "list";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'compra';

    // Page object name
    public $PageObjName = "CompraList";

    // Rendering View
    public $RenderingView = false;

    // Grid form hidden field names
    public $FormName = "fcompralist";
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

        // Table object (compra)
        if (!isset($GLOBALS["compra"]) || get_class($GLOBALS["compra"]) == PROJECT_NAMESPACE . "compra") {
            $GLOBALS["compra"] = &$this;
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
        $this->AddUrl = "CompraAdd";
        $this->InlineAddUrl = $pageUrl . "action=add";
        $this->GridAddUrl = $pageUrl . "action=gridadd";
        $this->GridEditUrl = $pageUrl . "action=gridedit";
        $this->MultiDeleteUrl = "CompraDelete";
        $this->MultiUpdateUrl = "CompraUpdate";

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
        $this->FilterOptions->TagClassName = "ew-filter-option fcompralistsrch";

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
        $this->id->setVisibility();
        $this->proveedor->setVisibility();
        $this->tipo_documento->setVisibility();
        $this->doc_afectado->Visible = false;
        $this->documento->setVisibility();
        $this->nro_control->Visible = false;
        $this->fecha->setVisibility();
        $this->descripcion->setVisibility();
        $this->aplica_retencion->Visible = false;
        $this->monto_exento->Visible = false;
        $this->monto_gravado->Visible = false;
        $this->alicuota->Visible = false;
        $this->monto_iva->Visible = false;
        $this->monto_total->setVisibility();
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
        $this->setupLookupOptions($this->proveedor);
        $this->setupLookupOptions($this->_username);
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
        $filterList = Concat($filterList, $this->proveedor->AdvancedSearch->toJson(), ","); // Field proveedor
        $filterList = Concat($filterList, $this->tipo_documento->AdvancedSearch->toJson(), ","); // Field tipo_documento
        $filterList = Concat($filterList, $this->doc_afectado->AdvancedSearch->toJson(), ","); // Field doc_afectado
        $filterList = Concat($filterList, $this->documento->AdvancedSearch->toJson(), ","); // Field documento
        $filterList = Concat($filterList, $this->nro_control->AdvancedSearch->toJson(), ","); // Field nro_control
        $filterList = Concat($filterList, $this->fecha->AdvancedSearch->toJson(), ","); // Field fecha
        $filterList = Concat($filterList, $this->descripcion->AdvancedSearch->toJson(), ","); // Field descripcion
        $filterList = Concat($filterList, $this->aplica_retencion->AdvancedSearch->toJson(), ","); // Field aplica_retencion
        $filterList = Concat($filterList, $this->monto_exento->AdvancedSearch->toJson(), ","); // Field monto_exento
        $filterList = Concat($filterList, $this->monto_gravado->AdvancedSearch->toJson(), ","); // Field monto_gravado
        $filterList = Concat($filterList, $this->alicuota->AdvancedSearch->toJson(), ","); // Field alicuota
        $filterList = Concat($filterList, $this->monto_iva->AdvancedSearch->toJson(), ","); // Field monto_iva
        $filterList = Concat($filterList, $this->monto_total->AdvancedSearch->toJson(), ","); // Field monto_total
        $filterList = Concat($filterList, $this->monto_pagar->AdvancedSearch->toJson(), ","); // Field monto_pagar
        $filterList = Concat($filterList, $this->ret_iva->AdvancedSearch->toJson(), ","); // Field ret_iva
        $filterList = Concat($filterList, $this->ref_iva->AdvancedSearch->toJson(), ","); // Field ref_iva
        $filterList = Concat($filterList, $this->ret_islr->AdvancedSearch->toJson(), ","); // Field ret_islr
        $filterList = Concat($filterList, $this->ref_islr->AdvancedSearch->toJson(), ","); // Field ref_islr
        $filterList = Concat($filterList, $this->ret_municipal->AdvancedSearch->toJson(), ","); // Field ret_municipal
        $filterList = Concat($filterList, $this->ref_municipal->AdvancedSearch->toJson(), ","); // Field ref_municipal
        $filterList = Concat($filterList, $this->fecha_registro->AdvancedSearch->toJson(), ","); // Field fecha_registro
        $filterList = Concat($filterList, $this->_username->AdvancedSearch->toJson(), ","); // Field username
        $filterList = Concat($filterList, $this->comprobante->AdvancedSearch->toJson(), ","); // Field comprobante
        $filterList = Concat($filterList, $this->tipo_iva->AdvancedSearch->toJson(), ","); // Field tipo_iva
        $filterList = Concat($filterList, $this->tipo_islr->AdvancedSearch->toJson(), ","); // Field tipo_islr
        $filterList = Concat($filterList, $this->sustraendo->AdvancedSearch->toJson(), ","); // Field sustraendo
        $filterList = Concat($filterList, $this->tipo_municipal->AdvancedSearch->toJson(), ","); // Field tipo_municipal
        $filterList = Concat($filterList, $this->anulado->AdvancedSearch->toJson(), ","); // Field anulado
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
            $UserProfile->setSearchFilters(CurrentUserName(), "fcompralistsrch", $filters);
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

        // Field proveedor
        $this->proveedor->AdvancedSearch->SearchValue = @$filter["x_proveedor"];
        $this->proveedor->AdvancedSearch->SearchOperator = @$filter["z_proveedor"];
        $this->proveedor->AdvancedSearch->SearchCondition = @$filter["v_proveedor"];
        $this->proveedor->AdvancedSearch->SearchValue2 = @$filter["y_proveedor"];
        $this->proveedor->AdvancedSearch->SearchOperator2 = @$filter["w_proveedor"];
        $this->proveedor->AdvancedSearch->save();

        // Field tipo_documento
        $this->tipo_documento->AdvancedSearch->SearchValue = @$filter["x_tipo_documento"];
        $this->tipo_documento->AdvancedSearch->SearchOperator = @$filter["z_tipo_documento"];
        $this->tipo_documento->AdvancedSearch->SearchCondition = @$filter["v_tipo_documento"];
        $this->tipo_documento->AdvancedSearch->SearchValue2 = @$filter["y_tipo_documento"];
        $this->tipo_documento->AdvancedSearch->SearchOperator2 = @$filter["w_tipo_documento"];
        $this->tipo_documento->AdvancedSearch->save();

        // Field doc_afectado
        $this->doc_afectado->AdvancedSearch->SearchValue = @$filter["x_doc_afectado"];
        $this->doc_afectado->AdvancedSearch->SearchOperator = @$filter["z_doc_afectado"];
        $this->doc_afectado->AdvancedSearch->SearchCondition = @$filter["v_doc_afectado"];
        $this->doc_afectado->AdvancedSearch->SearchValue2 = @$filter["y_doc_afectado"];
        $this->doc_afectado->AdvancedSearch->SearchOperator2 = @$filter["w_doc_afectado"];
        $this->doc_afectado->AdvancedSearch->save();

        // Field documento
        $this->documento->AdvancedSearch->SearchValue = @$filter["x_documento"];
        $this->documento->AdvancedSearch->SearchOperator = @$filter["z_documento"];
        $this->documento->AdvancedSearch->SearchCondition = @$filter["v_documento"];
        $this->documento->AdvancedSearch->SearchValue2 = @$filter["y_documento"];
        $this->documento->AdvancedSearch->SearchOperator2 = @$filter["w_documento"];
        $this->documento->AdvancedSearch->save();

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

        // Field descripcion
        $this->descripcion->AdvancedSearch->SearchValue = @$filter["x_descripcion"];
        $this->descripcion->AdvancedSearch->SearchOperator = @$filter["z_descripcion"];
        $this->descripcion->AdvancedSearch->SearchCondition = @$filter["v_descripcion"];
        $this->descripcion->AdvancedSearch->SearchValue2 = @$filter["y_descripcion"];
        $this->descripcion->AdvancedSearch->SearchOperator2 = @$filter["w_descripcion"];
        $this->descripcion->AdvancedSearch->save();

        // Field aplica_retencion
        $this->aplica_retencion->AdvancedSearch->SearchValue = @$filter["x_aplica_retencion"];
        $this->aplica_retencion->AdvancedSearch->SearchOperator = @$filter["z_aplica_retencion"];
        $this->aplica_retencion->AdvancedSearch->SearchCondition = @$filter["v_aplica_retencion"];
        $this->aplica_retencion->AdvancedSearch->SearchValue2 = @$filter["y_aplica_retencion"];
        $this->aplica_retencion->AdvancedSearch->SearchOperator2 = @$filter["w_aplica_retencion"];
        $this->aplica_retencion->AdvancedSearch->save();

        // Field monto_exento
        $this->monto_exento->AdvancedSearch->SearchValue = @$filter["x_monto_exento"];
        $this->monto_exento->AdvancedSearch->SearchOperator = @$filter["z_monto_exento"];
        $this->monto_exento->AdvancedSearch->SearchCondition = @$filter["v_monto_exento"];
        $this->monto_exento->AdvancedSearch->SearchValue2 = @$filter["y_monto_exento"];
        $this->monto_exento->AdvancedSearch->SearchOperator2 = @$filter["w_monto_exento"];
        $this->monto_exento->AdvancedSearch->save();

        // Field monto_gravado
        $this->monto_gravado->AdvancedSearch->SearchValue = @$filter["x_monto_gravado"];
        $this->monto_gravado->AdvancedSearch->SearchOperator = @$filter["z_monto_gravado"];
        $this->monto_gravado->AdvancedSearch->SearchCondition = @$filter["v_monto_gravado"];
        $this->monto_gravado->AdvancedSearch->SearchValue2 = @$filter["y_monto_gravado"];
        $this->monto_gravado->AdvancedSearch->SearchOperator2 = @$filter["w_monto_gravado"];
        $this->monto_gravado->AdvancedSearch->save();

        // Field alicuota
        $this->alicuota->AdvancedSearch->SearchValue = @$filter["x_alicuota"];
        $this->alicuota->AdvancedSearch->SearchOperator = @$filter["z_alicuota"];
        $this->alicuota->AdvancedSearch->SearchCondition = @$filter["v_alicuota"];
        $this->alicuota->AdvancedSearch->SearchValue2 = @$filter["y_alicuota"];
        $this->alicuota->AdvancedSearch->SearchOperator2 = @$filter["w_alicuota"];
        $this->alicuota->AdvancedSearch->save();

        // Field monto_iva
        $this->monto_iva->AdvancedSearch->SearchValue = @$filter["x_monto_iva"];
        $this->monto_iva->AdvancedSearch->SearchOperator = @$filter["z_monto_iva"];
        $this->monto_iva->AdvancedSearch->SearchCondition = @$filter["v_monto_iva"];
        $this->monto_iva->AdvancedSearch->SearchValue2 = @$filter["y_monto_iva"];
        $this->monto_iva->AdvancedSearch->SearchOperator2 = @$filter["w_monto_iva"];
        $this->monto_iva->AdvancedSearch->save();

        // Field monto_total
        $this->monto_total->AdvancedSearch->SearchValue = @$filter["x_monto_total"];
        $this->monto_total->AdvancedSearch->SearchOperator = @$filter["z_monto_total"];
        $this->monto_total->AdvancedSearch->SearchCondition = @$filter["v_monto_total"];
        $this->monto_total->AdvancedSearch->SearchValue2 = @$filter["y_monto_total"];
        $this->monto_total->AdvancedSearch->SearchOperator2 = @$filter["w_monto_total"];
        $this->monto_total->AdvancedSearch->save();

        // Field monto_pagar
        $this->monto_pagar->AdvancedSearch->SearchValue = @$filter["x_monto_pagar"];
        $this->monto_pagar->AdvancedSearch->SearchOperator = @$filter["z_monto_pagar"];
        $this->monto_pagar->AdvancedSearch->SearchCondition = @$filter["v_monto_pagar"];
        $this->monto_pagar->AdvancedSearch->SearchValue2 = @$filter["y_monto_pagar"];
        $this->monto_pagar->AdvancedSearch->SearchOperator2 = @$filter["w_monto_pagar"];
        $this->monto_pagar->AdvancedSearch->save();

        // Field ret_iva
        $this->ret_iva->AdvancedSearch->SearchValue = @$filter["x_ret_iva"];
        $this->ret_iva->AdvancedSearch->SearchOperator = @$filter["z_ret_iva"];
        $this->ret_iva->AdvancedSearch->SearchCondition = @$filter["v_ret_iva"];
        $this->ret_iva->AdvancedSearch->SearchValue2 = @$filter["y_ret_iva"];
        $this->ret_iva->AdvancedSearch->SearchOperator2 = @$filter["w_ret_iva"];
        $this->ret_iva->AdvancedSearch->save();

        // Field ref_iva
        $this->ref_iva->AdvancedSearch->SearchValue = @$filter["x_ref_iva"];
        $this->ref_iva->AdvancedSearch->SearchOperator = @$filter["z_ref_iva"];
        $this->ref_iva->AdvancedSearch->SearchCondition = @$filter["v_ref_iva"];
        $this->ref_iva->AdvancedSearch->SearchValue2 = @$filter["y_ref_iva"];
        $this->ref_iva->AdvancedSearch->SearchOperator2 = @$filter["w_ref_iva"];
        $this->ref_iva->AdvancedSearch->save();

        // Field ret_islr
        $this->ret_islr->AdvancedSearch->SearchValue = @$filter["x_ret_islr"];
        $this->ret_islr->AdvancedSearch->SearchOperator = @$filter["z_ret_islr"];
        $this->ret_islr->AdvancedSearch->SearchCondition = @$filter["v_ret_islr"];
        $this->ret_islr->AdvancedSearch->SearchValue2 = @$filter["y_ret_islr"];
        $this->ret_islr->AdvancedSearch->SearchOperator2 = @$filter["w_ret_islr"];
        $this->ret_islr->AdvancedSearch->save();

        // Field ref_islr
        $this->ref_islr->AdvancedSearch->SearchValue = @$filter["x_ref_islr"];
        $this->ref_islr->AdvancedSearch->SearchOperator = @$filter["z_ref_islr"];
        $this->ref_islr->AdvancedSearch->SearchCondition = @$filter["v_ref_islr"];
        $this->ref_islr->AdvancedSearch->SearchValue2 = @$filter["y_ref_islr"];
        $this->ref_islr->AdvancedSearch->SearchOperator2 = @$filter["w_ref_islr"];
        $this->ref_islr->AdvancedSearch->save();

        // Field ret_municipal
        $this->ret_municipal->AdvancedSearch->SearchValue = @$filter["x_ret_municipal"];
        $this->ret_municipal->AdvancedSearch->SearchOperator = @$filter["z_ret_municipal"];
        $this->ret_municipal->AdvancedSearch->SearchCondition = @$filter["v_ret_municipal"];
        $this->ret_municipal->AdvancedSearch->SearchValue2 = @$filter["y_ret_municipal"];
        $this->ret_municipal->AdvancedSearch->SearchOperator2 = @$filter["w_ret_municipal"];
        $this->ret_municipal->AdvancedSearch->save();

        // Field ref_municipal
        $this->ref_municipal->AdvancedSearch->SearchValue = @$filter["x_ref_municipal"];
        $this->ref_municipal->AdvancedSearch->SearchOperator = @$filter["z_ref_municipal"];
        $this->ref_municipal->AdvancedSearch->SearchCondition = @$filter["v_ref_municipal"];
        $this->ref_municipal->AdvancedSearch->SearchValue2 = @$filter["y_ref_municipal"];
        $this->ref_municipal->AdvancedSearch->SearchOperator2 = @$filter["w_ref_municipal"];
        $this->ref_municipal->AdvancedSearch->save();

        // Field fecha_registro
        $this->fecha_registro->AdvancedSearch->SearchValue = @$filter["x_fecha_registro"];
        $this->fecha_registro->AdvancedSearch->SearchOperator = @$filter["z_fecha_registro"];
        $this->fecha_registro->AdvancedSearch->SearchCondition = @$filter["v_fecha_registro"];
        $this->fecha_registro->AdvancedSearch->SearchValue2 = @$filter["y_fecha_registro"];
        $this->fecha_registro->AdvancedSearch->SearchOperator2 = @$filter["w_fecha_registro"];
        $this->fecha_registro->AdvancedSearch->save();

        // Field username
        $this->_username->AdvancedSearch->SearchValue = @$filter["x__username"];
        $this->_username->AdvancedSearch->SearchOperator = @$filter["z__username"];
        $this->_username->AdvancedSearch->SearchCondition = @$filter["v__username"];
        $this->_username->AdvancedSearch->SearchValue2 = @$filter["y__username"];
        $this->_username->AdvancedSearch->SearchOperator2 = @$filter["w__username"];
        $this->_username->AdvancedSearch->save();

        // Field comprobante
        $this->comprobante->AdvancedSearch->SearchValue = @$filter["x_comprobante"];
        $this->comprobante->AdvancedSearch->SearchOperator = @$filter["z_comprobante"];
        $this->comprobante->AdvancedSearch->SearchCondition = @$filter["v_comprobante"];
        $this->comprobante->AdvancedSearch->SearchValue2 = @$filter["y_comprobante"];
        $this->comprobante->AdvancedSearch->SearchOperator2 = @$filter["w_comprobante"];
        $this->comprobante->AdvancedSearch->save();

        // Field tipo_iva
        $this->tipo_iva->AdvancedSearch->SearchValue = @$filter["x_tipo_iva"];
        $this->tipo_iva->AdvancedSearch->SearchOperator = @$filter["z_tipo_iva"];
        $this->tipo_iva->AdvancedSearch->SearchCondition = @$filter["v_tipo_iva"];
        $this->tipo_iva->AdvancedSearch->SearchValue2 = @$filter["y_tipo_iva"];
        $this->tipo_iva->AdvancedSearch->SearchOperator2 = @$filter["w_tipo_iva"];
        $this->tipo_iva->AdvancedSearch->save();

        // Field tipo_islr
        $this->tipo_islr->AdvancedSearch->SearchValue = @$filter["x_tipo_islr"];
        $this->tipo_islr->AdvancedSearch->SearchOperator = @$filter["z_tipo_islr"];
        $this->tipo_islr->AdvancedSearch->SearchCondition = @$filter["v_tipo_islr"];
        $this->tipo_islr->AdvancedSearch->SearchValue2 = @$filter["y_tipo_islr"];
        $this->tipo_islr->AdvancedSearch->SearchOperator2 = @$filter["w_tipo_islr"];
        $this->tipo_islr->AdvancedSearch->save();

        // Field sustraendo
        $this->sustraendo->AdvancedSearch->SearchValue = @$filter["x_sustraendo"];
        $this->sustraendo->AdvancedSearch->SearchOperator = @$filter["z_sustraendo"];
        $this->sustraendo->AdvancedSearch->SearchCondition = @$filter["v_sustraendo"];
        $this->sustraendo->AdvancedSearch->SearchValue2 = @$filter["y_sustraendo"];
        $this->sustraendo->AdvancedSearch->SearchOperator2 = @$filter["w_sustraendo"];
        $this->sustraendo->AdvancedSearch->save();

        // Field tipo_municipal
        $this->tipo_municipal->AdvancedSearch->SearchValue = @$filter["x_tipo_municipal"];
        $this->tipo_municipal->AdvancedSearch->SearchOperator = @$filter["z_tipo_municipal"];
        $this->tipo_municipal->AdvancedSearch->SearchCondition = @$filter["v_tipo_municipal"];
        $this->tipo_municipal->AdvancedSearch->SearchValue2 = @$filter["y_tipo_municipal"];
        $this->tipo_municipal->AdvancedSearch->SearchOperator2 = @$filter["w_tipo_municipal"];
        $this->tipo_municipal->AdvancedSearch->save();

        // Field anulado
        $this->anulado->AdvancedSearch->SearchValue = @$filter["x_anulado"];
        $this->anulado->AdvancedSearch->SearchOperator = @$filter["z_anulado"];
        $this->anulado->AdvancedSearch->SearchCondition = @$filter["v_anulado"];
        $this->anulado->AdvancedSearch->SearchValue2 = @$filter["y_anulado"];
        $this->anulado->AdvancedSearch->SearchOperator2 = @$filter["w_anulado"];
        $this->anulado->AdvancedSearch->save();
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
        $this->buildSearchSql($where, $this->proveedor, $default, false); // proveedor
        $this->buildSearchSql($where, $this->tipo_documento, $default, false); // tipo_documento
        $this->buildSearchSql($where, $this->doc_afectado, $default, false); // doc_afectado
        $this->buildSearchSql($where, $this->documento, $default, false); // documento
        $this->buildSearchSql($where, $this->nro_control, $default, false); // nro_control
        $this->buildSearchSql($where, $this->fecha, $default, false); // fecha
        $this->buildSearchSql($where, $this->descripcion, $default, false); // descripcion
        $this->buildSearchSql($where, $this->aplica_retencion, $default, false); // aplica_retencion
        $this->buildSearchSql($where, $this->monto_exento, $default, false); // monto_exento
        $this->buildSearchSql($where, $this->monto_gravado, $default, false); // monto_gravado
        $this->buildSearchSql($where, $this->alicuota, $default, false); // alicuota
        $this->buildSearchSql($where, $this->monto_iva, $default, false); // monto_iva
        $this->buildSearchSql($where, $this->monto_total, $default, false); // monto_total
        $this->buildSearchSql($where, $this->monto_pagar, $default, false); // monto_pagar
        $this->buildSearchSql($where, $this->ret_iva, $default, false); // ret_iva
        $this->buildSearchSql($where, $this->ref_iva, $default, false); // ref_iva
        $this->buildSearchSql($where, $this->ret_islr, $default, false); // ret_islr
        $this->buildSearchSql($where, $this->ref_islr, $default, false); // ref_islr
        $this->buildSearchSql($where, $this->ret_municipal, $default, false); // ret_municipal
        $this->buildSearchSql($where, $this->ref_municipal, $default, false); // ref_municipal
        $this->buildSearchSql($where, $this->fecha_registro, $default, false); // fecha_registro
        $this->buildSearchSql($where, $this->_username, $default, false); // username
        $this->buildSearchSql($where, $this->comprobante, $default, false); // comprobante
        $this->buildSearchSql($where, $this->tipo_iva, $default, false); // tipo_iva
        $this->buildSearchSql($where, $this->tipo_islr, $default, false); // tipo_islr
        $this->buildSearchSql($where, $this->sustraendo, $default, false); // sustraendo
        $this->buildSearchSql($where, $this->tipo_municipal, $default, false); // tipo_municipal
        $this->buildSearchSql($where, $this->anulado, $default, false); // anulado

        // Set up search parm
        if (!$default && $where != "" && in_array($this->Command, ["", "reset", "resetall"])) {
            $this->Command = "search";
        }
        if (!$default && $this->Command == "search") {
            $this->id->AdvancedSearch->save(); // id
            $this->proveedor->AdvancedSearch->save(); // proveedor
            $this->tipo_documento->AdvancedSearch->save(); // tipo_documento
            $this->doc_afectado->AdvancedSearch->save(); // doc_afectado
            $this->documento->AdvancedSearch->save(); // documento
            $this->nro_control->AdvancedSearch->save(); // nro_control
            $this->fecha->AdvancedSearch->save(); // fecha
            $this->descripcion->AdvancedSearch->save(); // descripcion
            $this->aplica_retencion->AdvancedSearch->save(); // aplica_retencion
            $this->monto_exento->AdvancedSearch->save(); // monto_exento
            $this->monto_gravado->AdvancedSearch->save(); // monto_gravado
            $this->alicuota->AdvancedSearch->save(); // alicuota
            $this->monto_iva->AdvancedSearch->save(); // monto_iva
            $this->monto_total->AdvancedSearch->save(); // monto_total
            $this->monto_pagar->AdvancedSearch->save(); // monto_pagar
            $this->ret_iva->AdvancedSearch->save(); // ret_iva
            $this->ref_iva->AdvancedSearch->save(); // ref_iva
            $this->ret_islr->AdvancedSearch->save(); // ret_islr
            $this->ref_islr->AdvancedSearch->save(); // ref_islr
            $this->ret_municipal->AdvancedSearch->save(); // ret_municipal
            $this->ref_municipal->AdvancedSearch->save(); // ref_municipal
            $this->fecha_registro->AdvancedSearch->save(); // fecha_registro
            $this->_username->AdvancedSearch->save(); // username
            $this->comprobante->AdvancedSearch->save(); // comprobante
            $this->tipo_iva->AdvancedSearch->save(); // tipo_iva
            $this->tipo_islr->AdvancedSearch->save(); // tipo_islr
            $this->sustraendo->AdvancedSearch->save(); // sustraendo
            $this->tipo_municipal->AdvancedSearch->save(); // tipo_municipal
            $this->anulado->AdvancedSearch->save(); // anulado
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
        $this->buildBasicSearchSql($where, $this->doc_afectado, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->documento, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->nro_control, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->descripcion, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->ref_municipal, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->_username, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->tipo_iva, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->tipo_islr, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->tipo_municipal, $arKeywords, $type);
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
        if ($this->proveedor->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->tipo_documento->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->doc_afectado->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->documento->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->nro_control->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->fecha->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->descripcion->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->aplica_retencion->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->monto_exento->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->monto_gravado->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->alicuota->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->monto_iva->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->monto_total->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->monto_pagar->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->ret_iva->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->ref_iva->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->ret_islr->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->ref_islr->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->ret_municipal->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->ref_municipal->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->fecha_registro->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->_username->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->comprobante->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->tipo_iva->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->tipo_islr->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->sustraendo->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->tipo_municipal->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->anulado->AdvancedSearch->issetSession()) {
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
                $this->proveedor->AdvancedSearch->unsetSession();
                $this->tipo_documento->AdvancedSearch->unsetSession();
                $this->doc_afectado->AdvancedSearch->unsetSession();
                $this->documento->AdvancedSearch->unsetSession();
                $this->nro_control->AdvancedSearch->unsetSession();
                $this->fecha->AdvancedSearch->unsetSession();
                $this->descripcion->AdvancedSearch->unsetSession();
                $this->aplica_retencion->AdvancedSearch->unsetSession();
                $this->monto_exento->AdvancedSearch->unsetSession();
                $this->monto_gravado->AdvancedSearch->unsetSession();
                $this->alicuota->AdvancedSearch->unsetSession();
                $this->monto_iva->AdvancedSearch->unsetSession();
                $this->monto_total->AdvancedSearch->unsetSession();
                $this->monto_pagar->AdvancedSearch->unsetSession();
                $this->ret_iva->AdvancedSearch->unsetSession();
                $this->ref_iva->AdvancedSearch->unsetSession();
                $this->ret_islr->AdvancedSearch->unsetSession();
                $this->ref_islr->AdvancedSearch->unsetSession();
                $this->ret_municipal->AdvancedSearch->unsetSession();
                $this->ref_municipal->AdvancedSearch->unsetSession();
                $this->fecha_registro->AdvancedSearch->unsetSession();
                $this->_username->AdvancedSearch->unsetSession();
                $this->comprobante->AdvancedSearch->unsetSession();
                $this->tipo_iva->AdvancedSearch->unsetSession();
                $this->tipo_islr->AdvancedSearch->unsetSession();
                $this->sustraendo->AdvancedSearch->unsetSession();
                $this->tipo_municipal->AdvancedSearch->unsetSession();
                $this->anulado->AdvancedSearch->unsetSession();
    }

    // Restore all search parameters
    protected function restoreSearchParms()
    {
        $this->RestoreSearch = true;

        // Restore basic search values
        $this->BasicSearch->load();

        // Restore advanced search values
                $this->id->AdvancedSearch->load();
                $this->proveedor->AdvancedSearch->load();
                $this->tipo_documento->AdvancedSearch->load();
                $this->doc_afectado->AdvancedSearch->load();
                $this->documento->AdvancedSearch->load();
                $this->nro_control->AdvancedSearch->load();
                $this->fecha->AdvancedSearch->load();
                $this->descripcion->AdvancedSearch->load();
                $this->aplica_retencion->AdvancedSearch->load();
                $this->monto_exento->AdvancedSearch->load();
                $this->monto_gravado->AdvancedSearch->load();
                $this->alicuota->AdvancedSearch->load();
                $this->monto_iva->AdvancedSearch->load();
                $this->monto_total->AdvancedSearch->load();
                $this->monto_pagar->AdvancedSearch->load();
                $this->ret_iva->AdvancedSearch->load();
                $this->ref_iva->AdvancedSearch->load();
                $this->ret_islr->AdvancedSearch->load();
                $this->ref_islr->AdvancedSearch->load();
                $this->ret_municipal->AdvancedSearch->load();
                $this->ref_municipal->AdvancedSearch->load();
                $this->fecha_registro->AdvancedSearch->load();
                $this->_username->AdvancedSearch->load();
                $this->comprobante->AdvancedSearch->load();
                $this->tipo_iva->AdvancedSearch->load();
                $this->tipo_islr->AdvancedSearch->load();
                $this->sustraendo->AdvancedSearch->load();
                $this->tipo_municipal->AdvancedSearch->load();
                $this->anulado->AdvancedSearch->load();
    }

    // Set up sort parameters
    protected function setupSortOrder()
    {
        // Check for "order" parameter
        if (Get("order") !== null) {
            $this->CurrentOrder = Get("order");
            $this->CurrentOrderType = Get("ordertype", "");
            $this->updateSort($this->id); // id
            $this->updateSort($this->proveedor); // proveedor
            $this->updateSort($this->tipo_documento); // tipo_documento
            $this->updateSort($this->documento); // documento
            $this->updateSort($this->fecha); // fecha
            $this->updateSort($this->descripcion); // descripcion
            $this->updateSort($this->monto_total); // monto_total
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
                $this->proveedor->setSort("");
                $this->tipo_documento->setSort("");
                $this->doc_afectado->setSort("");
                $this->documento->setSort("");
                $this->nro_control->setSort("");
                $this->fecha->setSort("");
                $this->descripcion->setSort("");
                $this->aplica_retencion->setSort("");
                $this->monto_exento->setSort("");
                $this->monto_gravado->setSort("");
                $this->alicuota->setSort("");
                $this->monto_iva->setSort("");
                $this->monto_total->setSort("");
                $this->monto_pagar->setSort("");
                $this->ret_iva->setSort("");
                $this->ref_iva->setSort("");
                $this->ret_islr->setSort("");
                $this->ref_islr->setSort("");
                $this->ret_municipal->setSort("");
                $this->ref_municipal->setSort("");
                $this->fecha_registro->setSort("");
                $this->_username->setSort("");
                $this->comprobante->setSort("");
                $this->tipo_iva->setSort("");
                $this->tipo_islr->setSort("");
                $this->sustraendo->setSort("");
                $this->tipo_municipal->setSort("");
                $this->anulado->setSort("");
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
        $item->Body = "<a class=\"ew-save-filter\" data-form=\"fcompralistsrch\" href=\"#\" onclick=\"return false;\">" . $Language->phrase("SaveCurrentFilter") . "</a>";
        $item->Visible = true;
        $item = &$this->FilterOptions->add("deletefilter");
        $item->Body = "<a class=\"ew-delete-filter\" data-form=\"fcompralistsrch\" href=\"#\" onclick=\"return false;\">" . $Language->phrase("DeleteFilter") . "</a>";
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
                $item->Body = '<a class="ew-action ew-list-action" title="' . HtmlEncode($caption) . '" data-caption="' . HtmlEncode($caption) . '" href="#" onclick="return ew.submitAction(event,jQuery.extend({f:document.fcompralist},' . $listaction->toJson(true) . '));">' . $icon . '</a>';
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

        // id
        if (!$this->isAddOrEdit() && $this->id->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->id->AdvancedSearch->SearchValue != "" || $this->id->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // proveedor
        if (!$this->isAddOrEdit() && $this->proveedor->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->proveedor->AdvancedSearch->SearchValue != "" || $this->proveedor->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
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

        // doc_afectado
        if (!$this->isAddOrEdit() && $this->doc_afectado->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->doc_afectado->AdvancedSearch->SearchValue != "" || $this->doc_afectado->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
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

        // descripcion
        if (!$this->isAddOrEdit() && $this->descripcion->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->descripcion->AdvancedSearch->SearchValue != "" || $this->descripcion->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // aplica_retencion
        if (!$this->isAddOrEdit() && $this->aplica_retencion->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->aplica_retencion->AdvancedSearch->SearchValue != "" || $this->aplica_retencion->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // monto_exento
        if (!$this->isAddOrEdit() && $this->monto_exento->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->monto_exento->AdvancedSearch->SearchValue != "" || $this->monto_exento->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // monto_gravado
        if (!$this->isAddOrEdit() && $this->monto_gravado->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->monto_gravado->AdvancedSearch->SearchValue != "" || $this->monto_gravado->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
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

        // monto_iva
        if (!$this->isAddOrEdit() && $this->monto_iva->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->monto_iva->AdvancedSearch->SearchValue != "" || $this->monto_iva->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
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

        // monto_pagar
        if (!$this->isAddOrEdit() && $this->monto_pagar->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->monto_pagar->AdvancedSearch->SearchValue != "" || $this->monto_pagar->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // ret_iva
        if (!$this->isAddOrEdit() && $this->ret_iva->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->ret_iva->AdvancedSearch->SearchValue != "" || $this->ret_iva->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // ref_iva
        if (!$this->isAddOrEdit() && $this->ref_iva->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->ref_iva->AdvancedSearch->SearchValue != "" || $this->ref_iva->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // ret_islr
        if (!$this->isAddOrEdit() && $this->ret_islr->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->ret_islr->AdvancedSearch->SearchValue != "" || $this->ret_islr->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // ref_islr
        if (!$this->isAddOrEdit() && $this->ref_islr->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->ref_islr->AdvancedSearch->SearchValue != "" || $this->ref_islr->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // ret_municipal
        if (!$this->isAddOrEdit() && $this->ret_municipal->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->ret_municipal->AdvancedSearch->SearchValue != "" || $this->ret_municipal->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // ref_municipal
        if (!$this->isAddOrEdit() && $this->ref_municipal->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->ref_municipal->AdvancedSearch->SearchValue != "" || $this->ref_municipal->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // fecha_registro
        if (!$this->isAddOrEdit() && $this->fecha_registro->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->fecha_registro->AdvancedSearch->SearchValue != "" || $this->fecha_registro->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
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

        // comprobante
        if (!$this->isAddOrEdit() && $this->comprobante->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->comprobante->AdvancedSearch->SearchValue != "" || $this->comprobante->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // tipo_iva
        if (!$this->isAddOrEdit() && $this->tipo_iva->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->tipo_iva->AdvancedSearch->SearchValue != "" || $this->tipo_iva->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // tipo_islr
        if (!$this->isAddOrEdit() && $this->tipo_islr->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->tipo_islr->AdvancedSearch->SearchValue != "" || $this->tipo_islr->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // sustraendo
        if (!$this->isAddOrEdit() && $this->sustraendo->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->sustraendo->AdvancedSearch->SearchValue != "" || $this->sustraendo->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // tipo_municipal
        if (!$this->isAddOrEdit() && $this->tipo_municipal->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->tipo_municipal->AdvancedSearch->SearchValue != "" || $this->tipo_municipal->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // anulado
        if (!$this->isAddOrEdit() && $this->anulado->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->anulado->AdvancedSearch->SearchValue != "" || $this->anulado->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
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
        $row = [];
        $row['id'] = null;
        $row['proveedor'] = null;
        $row['tipo_documento'] = null;
        $row['doc_afectado'] = null;
        $row['documento'] = null;
        $row['nro_control'] = null;
        $row['fecha'] = null;
        $row['descripcion'] = null;
        $row['aplica_retencion'] = null;
        $row['monto_exento'] = null;
        $row['monto_gravado'] = null;
        $row['alicuota'] = null;
        $row['monto_iva'] = null;
        $row['monto_total'] = null;
        $row['monto_pagar'] = null;
        $row['ret_iva'] = null;
        $row['ref_iva'] = null;
        $row['ret_islr'] = null;
        $row['ref_islr'] = null;
        $row['ret_municipal'] = null;
        $row['ref_municipal'] = null;
        $row['fecha_registro'] = null;
        $row['username'] = null;
        $row['comprobante'] = null;
        $row['tipo_iva'] = null;
        $row['tipo_islr'] = null;
        $row['sustraendo'] = null;
        $row['tipo_municipal'] = null;
        $row['anulado'] = null;
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

            // id
            $this->id->LinkCustomAttributes = "";
            $this->id->HrefValue = "";
            $this->id->TooltipValue = "";

            // proveedor
            $this->proveedor->LinkCustomAttributes = "";
            $this->proveedor->HrefValue = "";
            $this->proveedor->TooltipValue = "";

            // tipo_documento
            $this->tipo_documento->LinkCustomAttributes = "";
            $this->tipo_documento->HrefValue = "";
            $this->tipo_documento->TooltipValue = "";

            // documento
            $this->documento->LinkCustomAttributes = "";
            $this->documento->HrefValue = "";
            $this->documento->TooltipValue = "";

            // fecha
            $this->fecha->LinkCustomAttributes = "";
            $this->fecha->HrefValue = "";
            $this->fecha->TooltipValue = "";

            // descripcion
            $this->descripcion->LinkCustomAttributes = "";
            $this->descripcion->HrefValue = "";
            $this->descripcion->TooltipValue = "";

            // monto_total
            $this->monto_total->LinkCustomAttributes = "";
            $this->monto_total->HrefValue = "";
            $this->monto_total->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_SEARCH) {
            // id
            $this->id->EditAttrs["class"] = "form-control";
            $this->id->EditCustomAttributes = "";
            $this->id->EditValue = HtmlEncode($this->id->AdvancedSearch->SearchValue);
            $this->id->PlaceHolder = RemoveHtml($this->id->caption());

            // proveedor
            $this->proveedor->EditAttrs["class"] = "form-control";
            $this->proveedor->EditCustomAttributes = "";
            $this->proveedor->PlaceHolder = RemoveHtml($this->proveedor->caption());

            // tipo_documento
            $this->tipo_documento->EditAttrs["class"] = "form-control";
            $this->tipo_documento->EditCustomAttributes = "";
            $this->tipo_documento->EditValue = $this->tipo_documento->options(true);
            $this->tipo_documento->PlaceHolder = RemoveHtml($this->tipo_documento->caption());

            // documento
            $this->documento->EditAttrs["class"] = "form-control";
            $this->documento->EditCustomAttributes = "";
            if (!$this->documento->Raw) {
                $this->documento->AdvancedSearch->SearchValue = HtmlDecode($this->documento->AdvancedSearch->SearchValue);
            }
            $this->documento->EditValue = HtmlEncode($this->documento->AdvancedSearch->SearchValue);
            $this->documento->PlaceHolder = RemoveHtml($this->documento->caption());

            // fecha
            $this->fecha->EditAttrs["class"] = "form-control";
            $this->fecha->EditCustomAttributes = "";
            $this->fecha->EditValue = HtmlEncode(FormatDateTime(UnFormatDateTime($this->fecha->AdvancedSearch->SearchValue, 7), 7));
            $this->fecha->PlaceHolder = RemoveHtml($this->fecha->caption());
            $this->fecha->EditAttrs["class"] = "form-control";
            $this->fecha->EditCustomAttributes = "";
            $this->fecha->EditValue2 = HtmlEncode(FormatDateTime(UnFormatDateTime($this->fecha->AdvancedSearch->SearchValue2, 7), 7));
            $this->fecha->PlaceHolder = RemoveHtml($this->fecha->caption());

            // descripcion
            $this->descripcion->EditAttrs["class"] = "form-control";
            $this->descripcion->EditCustomAttributes = "";
            $this->descripcion->EditValue = HtmlEncode($this->descripcion->AdvancedSearch->SearchValue);
            $this->descripcion->PlaceHolder = RemoveHtml($this->descripcion->caption());

            // monto_total
            $this->monto_total->EditAttrs["class"] = "form-control";
            $this->monto_total->EditCustomAttributes = "";
            $this->monto_total->EditValue = HtmlEncode($this->monto_total->AdvancedSearch->SearchValue);
            $this->monto_total->PlaceHolder = RemoveHtml($this->monto_total->caption());
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
        $this->proveedor->AdvancedSearch->load();
        $this->tipo_documento->AdvancedSearch->load();
        $this->doc_afectado->AdvancedSearch->load();
        $this->documento->AdvancedSearch->load();
        $this->nro_control->AdvancedSearch->load();
        $this->fecha->AdvancedSearch->load();
        $this->descripcion->AdvancedSearch->load();
        $this->aplica_retencion->AdvancedSearch->load();
        $this->monto_exento->AdvancedSearch->load();
        $this->monto_gravado->AdvancedSearch->load();
        $this->alicuota->AdvancedSearch->load();
        $this->monto_iva->AdvancedSearch->load();
        $this->monto_total->AdvancedSearch->load();
        $this->monto_pagar->AdvancedSearch->load();
        $this->ret_iva->AdvancedSearch->load();
        $this->ref_iva->AdvancedSearch->load();
        $this->ret_islr->AdvancedSearch->load();
        $this->ref_islr->AdvancedSearch->load();
        $this->ret_municipal->AdvancedSearch->load();
        $this->ref_municipal->AdvancedSearch->load();
        $this->fecha_registro->AdvancedSearch->load();
        $this->_username->AdvancedSearch->load();
        $this->comprobante->AdvancedSearch->load();
        $this->tipo_iva->AdvancedSearch->load();
        $this->tipo_islr->AdvancedSearch->load();
        $this->sustraendo->AdvancedSearch->load();
        $this->tipo_municipal->AdvancedSearch->load();
        $this->anulado->AdvancedSearch->load();
    }

    // Get export HTML tag
    protected function getExportTag($type, $custom = false)
    {
        global $Language;
        $pageUrl = $this->pageUrl();
        if (SameText($type, "excel")) {
            if ($custom) {
                return "<a href=\"#\" class=\"ew-export-link ew-excel\" title=\"" . HtmlEncode($Language->phrase("ExportToExcelText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToExcelText")) . "\" onclick=\"return ew.export(document.fcompralist, '" . $this->ExportExcelUrl . "', 'excel', true);\">" . $Language->phrase("ExportToExcel") . "</a>";
            } else {
                return "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ew-export-link ew-excel\" title=\"" . HtmlEncode($Language->phrase("ExportToExcelText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToExcelText")) . "\">" . $Language->phrase("ExportToExcel") . "</a>";
            }
        } elseif (SameText($type, "word")) {
            if ($custom) {
                return "<a href=\"#\" class=\"ew-export-link ew-word\" title=\"" . HtmlEncode($Language->phrase("ExportToWordText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToWordText")) . "\" onclick=\"return ew.export(document.fcompralist, '" . $this->ExportWordUrl . "', 'word', true);\">" . $Language->phrase("ExportToWord") . "</a>";
            } else {
                return "<a href=\"" . $this->ExportWordUrl . "\" class=\"ew-export-link ew-word\" title=\"" . HtmlEncode($Language->phrase("ExportToWordText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToWordText")) . "\">" . $Language->phrase("ExportToWord") . "</a>";
            }
        } elseif (SameText($type, "pdf")) {
            if ($custom) {
                return "<a href=\"#\" class=\"ew-export-link ew-pdf\" title=\"" . HtmlEncode($Language->phrase("ExportToPDFText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToPDFText")) . "\" onclick=\"return ew.export(document.fcompralist, '" . $this->ExportPdfUrl . "', 'pdf', true);\">" . $Language->phrase("ExportToPDF") . "</a>";
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
            return '<button id="emf_compra" class="ew-export-link ew-email" title="' . $Language->phrase("ExportToEmailText") . '" data-caption="' . $Language->phrase("ExportToEmailText") . '" onclick="ew.emailDialogShow({lnk:\'emf_compra\', hdr:ew.language.phrase(\'ExportToEmailText\'), f:document.fcompralist, sel:false' . $url . '});">' . $Language->phrase("ExportToEmail") . '</button>';
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
        $item->Body = "<a class=\"btn btn-default ew-search-toggle" . $searchToggleClass . "\" href=\"#\" role=\"button\" title=\"" . $Language->phrase("SearchPanel") . "\" data-caption=\"" . $Language->phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fcompralistsrch\" aria-pressed=\"" . ($searchToggleClass == " active" ? "true" : "false") . "\">" . $Language->phrase("SearchLink") . "</a>";
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
        $this->anulado->Visible = FALSE;
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
