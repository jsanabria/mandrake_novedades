<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class Recarga2Grid extends Recarga2
{
    use MessagesTrait;

    // Page ID
    public $PageID = "grid";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'recarga2';

    // Page object name
    public $PageObjName = "Recarga2Grid";

    // Rendering View
    public $RenderingView = false;

    // Grid form hidden field names
    public $FormName = "frecarga2grid";
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
        $this->FormActionName .= "_" . $this->FormName;
        $this->OldKeyName .= "_" . $this->FormName;
        $this->FormBlankRowName .= "_" . $this->FormName;
        $this->FormKeyCountName .= "_" . $this->FormName;
        $GLOBALS["Grid"] = &$this;

        // Language object
        $Language = Container("language");

        // Parent constuctor
        parent::__construct();

        // Table object (recarga2)
        if (!isset($GLOBALS["recarga2"]) || get_class($GLOBALS["recarga2"]) == PROJECT_NAMESPACE . "recarga2") {
            $GLOBALS["recarga2"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();
        $this->AddUrl = "Recarga2Add";

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'recarga2');
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

        // Other options
        if (!$this->OtherOptions) {
            $this->OtherOptions = new ListOptionsArray();
        }
        $this->OtherOptions["addedit"] = new ListOptions("div");
        $this->OtherOptions["addedit"]->TagClassName = "ew-add-edit-option";
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

        // Export
        if ($this->CustomExport && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, Config("EXPORT_CLASSES"))) {
            $content = $this->getContents();
            if ($ExportFileName == "") {
                $ExportFileName = $this->TableVar;
            }
            $class = PROJECT_NAMESPACE . Config("EXPORT_CLASSES." . $this->CustomExport);
            if (class_exists($class)) {
                $doc = new $class(Container("recarga2"));
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
        unset($GLOBALS["Grid"]);
        if ($url === "") {
            return;
        }
        if (!IsApi() && method_exists($this, "pageRedirecting")) {
            $this->pageRedirecting($url);
        }

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
    public $ShowOtherOptions = false;
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

        // Get grid add count
        $gridaddcnt = Get(Config("TABLE_GRID_ADD_ROW_COUNT"), "");
        if (is_numeric($gridaddcnt) && $gridaddcnt > 0) {
            $this->GridAddRowCount = $gridaddcnt;
        }

        // Set up list options
        $this->setupListOptions();
        $this->id->Visible = false;
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
        $this->_username->setVisibility();
        $this->cobro_cliente_reverso->Visible = false;
        $this->nro_recibo->Visible = false;
        $this->nota_recepcion->Visible = false;
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
            // Set up records per page
            $this->setupDisplayRecords();

            // Handle reset command
            $this->resetCmd();

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

            // Show grid delete link for grid add / grid edit
            if ($this->AllowAddDeleteRow) {
                if ($this->isGridAdd() || $this->isGridEdit()) {
                    $item = $this->ListOptions["griddelete"];
                    if ($item) {
                        $item->Visible = true;
                    }
                }
            }

            // Set up sorting order
            $this->setupSortOrder();
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
        if ($this->CurrentMode != "add" && $this->getMasterFilter() != "" && $this->getCurrentMasterTable() == "abono2") {
            $masterTbl = Container("abono2");
            $rsmaster = $masterTbl->loadRs($this->DbMasterFilter)->fetch(\PDO::FETCH_ASSOC);
            $this->MasterRecordExists = $rsmaster !== false;
            if (!$this->MasterRecordExists) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record found
                $this->terminate("Abono2List"); // Return to master page
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
        if ($this->isGridAdd()) {
            if ($this->CurrentMode == "copy") {
                $this->TotalRecords = $this->listRecordCount();
                $this->StartRecord = 1;
                $this->DisplayRecords = $this->TotalRecords;
                $this->Recordset = $this->loadRecordset($this->StartRecord - 1, $this->DisplayRecords);
            } else {
                $this->CurrentFilter = "0=1";
                $this->StartRecord = 1;
                $this->DisplayRecords = $this->GridAddRowCount;
            }
            $this->TotalRecords = $this->DisplayRecords;
            $this->StopRecord = $this->DisplayRecords;
        } else {
            $this->TotalRecords = $this->listRecordCount();
            $this->StartRecord = 1;
            $this->DisplayRecords = $this->TotalRecords; // Display all records
            $this->Recordset = $this->loadRecordset($this->StartRecord - 1, $this->DisplayRecords);
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

    // Exit inline mode
    protected function clearInlineMode()
    {
        $this->monto_moneda->FormValue = ""; // Clear form value
        $this->monto_bs->FormValue = ""; // Clear form value
        $this->tasa_usd->FormValue = ""; // Clear form value
        $this->monto_usd->FormValue = ""; // Clear form value
        $this->saldo->FormValue = ""; // Clear form value
        $this->LastAction = $this->CurrentAction; // Save last action
        $this->CurrentAction = ""; // Clear action
        $_SESSION[SESSION_INLINE_MODE] = ""; // Clear inline mode
    }

    // Switch to Grid Add mode
    protected function gridAddMode()
    {
        $this->CurrentAction = "gridadd";
        $_SESSION[SESSION_INLINE_MODE] = "gridadd";
        $this->hideFieldsForAddEdit();
    }

    // Switch to Grid Edit mode
    protected function gridEditMode()
    {
        $this->CurrentAction = "gridedit";
        $_SESSION[SESSION_INLINE_MODE] = "gridedit";
        $this->hideFieldsForAddEdit();
    }

    // Perform update to grid
    public function gridUpdate()
    {
        global $Language, $CurrentForm;
        $gridUpdate = true;

        // Get old recordset
        $this->CurrentFilter = $this->buildKeyFilter();
        if ($this->CurrentFilter == "") {
            $this->CurrentFilter = "0=1";
        }
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        if ($rs = $conn->executeQuery($sql)) {
            $rsold = $rs->fetchAll();
            $rs->closeCursor();
        }

        // Call Grid Updating event
        if (!$this->gridUpdating($rsold)) {
            if ($this->getFailureMessage() == "") {
                $this->setFailureMessage($Language->phrase("GridEditCancelled")); // Set grid edit cancelled message
            }
            return false;
        }
        if ($this->AuditTrailOnEdit) {
            $this->writeAuditTrailDummy($Language->phrase("BatchUpdateBegin")); // Batch update begin
        }
        $key = "";

        // Update row index and get row key
        $CurrentForm->Index = -1;
        $rowcnt = strval($CurrentForm->getValue($this->FormKeyCountName));
        if ($rowcnt == "" || !is_numeric($rowcnt)) {
            $rowcnt = 0;
        }

        // Update all rows based on key
        for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
            $CurrentForm->Index = $rowindex;
            $this->setKey($CurrentForm->getValue($this->OldKeyName));
            $rowaction = strval($CurrentForm->getValue($this->FormActionName));

            // Load all values and keys
            if ($rowaction != "insertdelete") { // Skip insert then deleted rows
                $this->loadFormValues(); // Get form values
                if ($rowaction == "" || $rowaction == "edit" || $rowaction == "delete") {
                    $gridUpdate = $this->OldKey != ""; // Key must not be empty
                } else {
                    $gridUpdate = true;
                }

                // Skip empty row
                if ($rowaction == "insert" && $this->emptyRow()) {
                // Validate form and insert/update/delete record
                } elseif ($gridUpdate) {
                    if ($rowaction == "delete") {
                        $this->CurrentFilter = $this->getRecordFilter();
                        $gridUpdate = $this->deleteRows(); // Delete this row
                    //} elseif (!$this->validateForm()) { // Already done in validateGridForm
                    //    $gridUpdate = false; // Form error, reset action
                    } else {
                        if ($rowaction == "insert") {
                            $gridUpdate = $this->addRow(); // Insert this row
                        } else {
                            if ($this->OldKey != "") {
                                $this->SendEmail = false; // Do not send email on update success
                                $gridUpdate = $this->editRow(); // Update this row
                            }
                        } // End update
                    }
                }
                if ($gridUpdate) {
                    if ($key != "") {
                        $key .= ", ";
                    }
                    $key .= $this->OldKey;
                } else {
                    break;
                }
            }
        }
        if ($gridUpdate) {
            // Get new records
            $rsnew = $conn->fetchAll($sql);

            // Call Grid_Updated event
            $this->gridUpdated($rsold, $rsnew);
            if ($this->AuditTrailOnEdit) {
                $this->writeAuditTrailDummy($Language->phrase("BatchUpdateSuccess")); // Batch update success
            }
            $this->clearInlineMode(); // Clear inline edit mode
        } else {
            if ($this->AuditTrailOnEdit) {
                $this->writeAuditTrailDummy($Language->phrase("BatchUpdateRollback")); // Batch update rollback
            }
            if ($this->getFailureMessage() == "") {
                $this->setFailureMessage($Language->phrase("UpdateFailed")); // Set update failed message
            }
        }
        return $gridUpdate;
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

    // Perform Grid Add
    public function gridInsert()
    {
        global $Language, $CurrentForm;
        $rowindex = 1;
        $gridInsert = false;
        $conn = $this->getConnection();

        // Call Grid Inserting event
        if (!$this->gridInserting()) {
            if ($this->getFailureMessage() == "") {
                $this->setFailureMessage($Language->phrase("GridAddCancelled")); // Set grid add cancelled message
            }
            return false;
        }

        // Init key filter
        $wrkfilter = "";
        $addcnt = 0;
        if ($this->AuditTrailOnAdd) {
            $this->writeAuditTrailDummy($Language->phrase("BatchInsertBegin")); // Batch insert begin
        }
        $key = "";

        // Get row count
        $CurrentForm->Index = -1;
        $rowcnt = strval($CurrentForm->getValue($this->FormKeyCountName));
        if ($rowcnt == "" || !is_numeric($rowcnt)) {
            $rowcnt = 0;
        }

        // Insert all rows
        for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
            // Load current row values
            $CurrentForm->Index = $rowindex;
            $rowaction = strval($CurrentForm->getValue($this->FormActionName));
            if ($rowaction != "" && $rowaction != "insert") {
                continue; // Skip
            }
            if ($rowaction == "insert") {
                $this->OldKey = strval($CurrentForm->getValue($this->OldKeyName));
                $this->loadOldRecord(); // Load old record
            }
            $this->loadFormValues(); // Get form values
            if (!$this->emptyRow()) {
                $addcnt++;
                $this->SendEmail = false; // Do not send email on insert success

                // Validate form // Already done in validateGridForm
                //if (!$this->validateForm()) {
                //    $gridInsert = false; // Form error, reset action
                //} else {
                    $gridInsert = $this->addRow($this->OldRecordset); // Insert this row
                //}
                if ($gridInsert) {
                    if ($key != "") {
                        $key .= Config("COMPOSITE_KEY_SEPARATOR");
                    }
                    $key .= $this->id->CurrentValue;

                    // Add filter for this record
                    $filter = $this->getRecordFilter();
                    if ($wrkfilter != "") {
                        $wrkfilter .= " OR ";
                    }
                    $wrkfilter .= $filter;
                } else {
                    break;
                }
            }
        }
        if ($addcnt == 0) { // No record inserted
            $this->clearInlineMode(); // Clear grid add mode and return
            return true;
        }
        if ($gridInsert) {
            // Get new records
            $this->CurrentFilter = $wrkfilter;
            $sql = $this->getCurrentSql();
            $rsnew = $conn->fetchAll($sql);

            // Call Grid_Inserted event
            $this->gridInserted($rsnew);
            if ($this->AuditTrailOnAdd) {
                $this->writeAuditTrailDummy($Language->phrase("BatchInsertSuccess")); // Batch insert success
            }
            $this->clearInlineMode(); // Clear grid add mode
        } else {
            if ($this->AuditTrailOnAdd) {
                $this->writeAuditTrailDummy($Language->phrase("BatchInsertRollback")); // Batch insert rollback
            }
            if ($this->getFailureMessage() == "") {
                $this->setFailureMessage($Language->phrase("InsertFailed")); // Set insert failed message
            }
        }
        return $gridInsert;
    }

    // Check if empty row
    public function emptyRow()
    {
        global $CurrentForm;
        if ($CurrentForm->hasValue("x_cliente") && $CurrentForm->hasValue("o_cliente") && $this->cliente->CurrentValue != $this->cliente->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_fecha") && $CurrentForm->hasValue("o_fecha") && $this->fecha->CurrentValue != $this->fecha->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_metodo_pago") && $CurrentForm->hasValue("o_metodo_pago") && $this->metodo_pago->CurrentValue != $this->metodo_pago->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_referencia") && $CurrentForm->hasValue("o_referencia") && $this->referencia->CurrentValue != $this->referencia->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_monto_moneda") && $CurrentForm->hasValue("o_monto_moneda") && $this->monto_moneda->CurrentValue != $this->monto_moneda->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_moneda") && $CurrentForm->hasValue("o_moneda") && $this->moneda->CurrentValue != $this->moneda->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_monto_bs") && $CurrentForm->hasValue("o_monto_bs") && $this->monto_bs->CurrentValue != $this->monto_bs->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_tasa_usd") && $CurrentForm->hasValue("o_tasa_usd") && $this->tasa_usd->CurrentValue != $this->tasa_usd->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_monto_usd") && $CurrentForm->hasValue("o_monto_usd") && $this->monto_usd->CurrentValue != $this->monto_usd->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_saldo") && $CurrentForm->hasValue("o_saldo") && $this->saldo->CurrentValue != $this->saldo->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x__username") && $CurrentForm->hasValue("o__username") && $this->_username->CurrentValue != $this->_username->OldValue) {
            return false;
        }
        return true;
    }

    // Validate grid form
    public function validateGridForm()
    {
        global $CurrentForm;
        // Get row count
        $CurrentForm->Index = -1;
        $rowcnt = strval($CurrentForm->getValue($this->FormKeyCountName));
        if ($rowcnt == "" || !is_numeric($rowcnt)) {
            $rowcnt = 0;
        }

        // Validate all records
        for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
            // Load current row values
            $CurrentForm->Index = $rowindex;
            $rowaction = strval($CurrentForm->getValue($this->FormActionName));
            if ($rowaction != "delete" && $rowaction != "insertdelete") {
                $this->loadFormValues(); // Get form values
                if ($rowaction == "insert" && $this->emptyRow()) {
                    // Ignore
                } elseif (!$this->validateForm()) {
                    return false;
                }
            }
        }
        return true;
    }

    // Get all form values of the grid
    public function getGridFormValues()
    {
        global $CurrentForm;
        // Get row count
        $CurrentForm->Index = -1;
        $rowcnt = strval($CurrentForm->getValue($this->FormKeyCountName));
        if ($rowcnt == "" || !is_numeric($rowcnt)) {
            $rowcnt = 0;
        }
        $rows = [];

        // Loop through all records
        for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
            // Load current row values
            $CurrentForm->Index = $rowindex;
            $rowaction = strval($CurrentForm->getValue($this->FormActionName));
            if ($rowaction != "delete" && $rowaction != "insertdelete") {
                $this->loadFormValues(); // Get form values
                if ($rowaction == "insert" && $this->emptyRow()) {
                    // Ignore
                } else {
                    $rows[] = $this->getFieldValues("FormValue"); // Return row as array
                }
            }
        }
        return $rows; // Return as array of array
    }

    // Restore form values for current row
    public function restoreCurrentRowFormValues($idx)
    {
        global $CurrentForm;

        // Get row based on current index
        $CurrentForm->Index = $idx;
        $rowaction = strval($CurrentForm->getValue($this->FormActionName));
        $this->loadFormValues(); // Load form values
        // Set up invalid status correctly
        $this->resetFormError();
        if ($rowaction == "insert" && $this->emptyRow()) {
            // Ignore
        } else {
            $this->validateForm();
        }
    }

    // Reset form status
    public function resetFormError()
    {
        $this->cliente->clearErrorMessage();
        $this->fecha->clearErrorMessage();
        $this->metodo_pago->clearErrorMessage();
        $this->referencia->clearErrorMessage();
        $this->monto_moneda->clearErrorMessage();
        $this->moneda->clearErrorMessage();
        $this->monto_bs->clearErrorMessage();
        $this->tasa_usd->clearErrorMessage();
        $this->monto_usd->clearErrorMessage();
        $this->saldo->clearErrorMessage();
        $this->_username->clearErrorMessage();
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

        // "griddelete"
        if ($this->AllowAddDeleteRow) {
            $item = &$this->ListOptions->add("griddelete");
            $item->CssClass = "text-nowrap";
            $item->OnLeft = true;
            $item->Visible = false; // Default hidden
        }

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

        // Set up row action and key
        if ($CurrentForm && is_numeric($this->RowIndex) && $this->RowType != "view") {
            $CurrentForm->Index = $this->RowIndex;
            $actionName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormActionName);
            $oldKeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->OldKeyName);
            $blankRowName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormBlankRowName);
            if ($this->RowAction != "") {
                $this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $actionName . "\" id=\"" . $actionName . "\" value=\"" . $this->RowAction . "\">";
            }
            $oldKey = $this->getKey(false); // Get from OldValue
            if ($oldKeyName != "" && $oldKey != "") {
                $this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $oldKeyName . "\" id=\"" . $oldKeyName . "\" value=\"" . HtmlEncode($oldKey) . "\">";
            }
            if ($this->RowAction == "insert" && $this->isConfirm() && $this->emptyRow()) {
                $this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $blankRowName . "\" id=\"" . $blankRowName . "\" value=\"1\">";
            }
        }

        // "delete"
        if ($this->AllowAddDeleteRow) {
            if ($this->CurrentMode == "add" || $this->CurrentMode == "copy" || $this->CurrentMode == "edit") {
                $options = &$this->ListOptions;
                $options->UseButtonGroup = true; // Use button group for grid delete button
                $opt = $options["griddelete"];
                if (is_numeric($this->RowIndex) && ($this->RowAction == "" || $this->RowAction == "edit")) { // Do not allow delete existing record
                    $opt->Body = "&nbsp;";
                } else {
                    $opt->Body = "<a class=\"ew-grid-link ew-grid-delete\" title=\"" . HtmlTitle($Language->phrase("DeleteLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("DeleteLink")) . "\" onclick=\"return ew.deleteGridRow(this, " . $this->RowIndex . ");\">" . $Language->phrase("DeleteLink") . "</a>";
                }
            }
        }
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
        $this->renderListOptionsExt();

        // Call ListOptions_Rendered event
        $this->listOptionsRendered();
    }

    // Set up other options
    protected function setupOtherOptions()
    {
        global $Language, $Security;
        $option = $this->OtherOptions["addedit"];
        $option->UseDropDownButton = false;
        $option->DropDownButtonPhrase = $Language->phrase("ButtonAddEdit");
        $option->UseButtonGroup = true;
        //$option->ButtonClass = ""; // Class for button group
        $item = &$option->add($option->GroupOptionName);
        $item->Body = "";
        $item->Visible = false;
    }

    // Render other options
    public function renderOtherOptions()
    {
        global $Language, $Security;
        $options = &$this->OtherOptions;
        if (($this->CurrentMode == "add" || $this->CurrentMode == "copy" || $this->CurrentMode == "edit") && !$this->isConfirm()) { // Check add/copy/edit mode
            if ($this->AllowAddDeleteRow) {
                $option = $options["addedit"];
                $option->UseDropDownButton = false;
                $item = &$option->add("addblankrow");
                $item->Body = "<a class=\"ew-add-edit ew-add-blank-row\" title=\"" . HtmlTitle($Language->phrase("AddBlankRow")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("AddBlankRow")) . "\" href=\"#\" onclick=\"return ew.addGridRow(this);\">" . $Language->phrase("AddBlankRow") . "</a>";
                $item->Visible = false;
                $this->ShowOtherOptions = $item->Visible;
            }
        }
        if ($this->CurrentMode == "view") { // Check view mode
            $option = $options["addedit"];
            $item = $option["add"];
            $this->ShowOtherOptions = $item && $item->Visible;
        }
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
        $this->cliente->CurrentValue = null;
        $this->cliente->OldValue = $this->cliente->CurrentValue;
        $this->fecha->CurrentValue = null;
        $this->fecha->OldValue = $this->fecha->CurrentValue;
        $this->metodo_pago->CurrentValue = null;
        $this->metodo_pago->OldValue = $this->metodo_pago->CurrentValue;
        $this->referencia->CurrentValue = null;
        $this->referencia->OldValue = $this->referencia->CurrentValue;
        $this->reverso->CurrentValue = "N";
        $this->reverso->OldValue = $this->reverso->CurrentValue;
        $this->monto_moneda->CurrentValue = null;
        $this->monto_moneda->OldValue = $this->monto_moneda->CurrentValue;
        $this->moneda->CurrentValue = null;
        $this->moneda->OldValue = $this->moneda->CurrentValue;
        $this->tasa_moneda->CurrentValue = null;
        $this->tasa_moneda->OldValue = $this->tasa_moneda->CurrentValue;
        $this->monto_bs->CurrentValue = null;
        $this->monto_bs->OldValue = $this->monto_bs->CurrentValue;
        $this->tasa_usd->CurrentValue = null;
        $this->tasa_usd->OldValue = $this->tasa_usd->CurrentValue;
        $this->monto_usd->CurrentValue = null;
        $this->monto_usd->OldValue = $this->monto_usd->CurrentValue;
        $this->saldo->CurrentValue = null;
        $this->saldo->OldValue = $this->saldo->CurrentValue;
        $this->nota->CurrentValue = null;
        $this->nota->OldValue = $this->nota->CurrentValue;
        $this->_username->CurrentValue = null;
        $this->_username->OldValue = $this->_username->CurrentValue;
        $this->cobro_cliente_reverso->CurrentValue = 0;
        $this->cobro_cliente_reverso->OldValue = $this->cobro_cliente_reverso->CurrentValue;
        $this->nro_recibo->CurrentValue = 0;
        $this->nro_recibo->OldValue = $this->nro_recibo->CurrentValue;
        $this->nota_recepcion->CurrentValue = null;
        $this->nota_recepcion->OldValue = $this->nota_recepcion->CurrentValue;
        $this->abono->CurrentValue = 0;
        $this->abono->OldValue = $this->abono->CurrentValue;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $CurrentForm->FormName = $this->FormName;

        // Check field name 'cliente' first before field var 'x_cliente'
        $val = $CurrentForm->hasValue("cliente") ? $CurrentForm->getValue("cliente") : $CurrentForm->getValue("x_cliente");
        if (!$this->cliente->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cliente->Visible = false; // Disable update for API request
            } else {
                $this->cliente->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_cliente")) {
            $this->cliente->setOldValue($CurrentForm->getValue("o_cliente"));
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
        if ($CurrentForm->hasValue("o_fecha")) {
            $this->fecha->setOldValue($CurrentForm->getValue("o_fecha"));
        }

        // Check field name 'metodo_pago' first before field var 'x_metodo_pago'
        $val = $CurrentForm->hasValue("metodo_pago") ? $CurrentForm->getValue("metodo_pago") : $CurrentForm->getValue("x_metodo_pago");
        if (!$this->metodo_pago->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->metodo_pago->Visible = false; // Disable update for API request
            } else {
                $this->metodo_pago->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_metodo_pago")) {
            $this->metodo_pago->setOldValue($CurrentForm->getValue("o_metodo_pago"));
        }

        // Check field name 'referencia' first before field var 'x_referencia'
        $val = $CurrentForm->hasValue("referencia") ? $CurrentForm->getValue("referencia") : $CurrentForm->getValue("x_referencia");
        if (!$this->referencia->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->referencia->Visible = false; // Disable update for API request
            } else {
                $this->referencia->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_referencia")) {
            $this->referencia->setOldValue($CurrentForm->getValue("o_referencia"));
        }

        // Check field name 'monto_moneda' first before field var 'x_monto_moneda'
        $val = $CurrentForm->hasValue("monto_moneda") ? $CurrentForm->getValue("monto_moneda") : $CurrentForm->getValue("x_monto_moneda");
        if (!$this->monto_moneda->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->monto_moneda->Visible = false; // Disable update for API request
            } else {
                $this->monto_moneda->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_monto_moneda")) {
            $this->monto_moneda->setOldValue($CurrentForm->getValue("o_monto_moneda"));
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
        if ($CurrentForm->hasValue("o_moneda")) {
            $this->moneda->setOldValue($CurrentForm->getValue("o_moneda"));
        }

        // Check field name 'monto_bs' first before field var 'x_monto_bs'
        $val = $CurrentForm->hasValue("monto_bs") ? $CurrentForm->getValue("monto_bs") : $CurrentForm->getValue("x_monto_bs");
        if (!$this->monto_bs->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->monto_bs->Visible = false; // Disable update for API request
            } else {
                $this->monto_bs->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_monto_bs")) {
            $this->monto_bs->setOldValue($CurrentForm->getValue("o_monto_bs"));
        }

        // Check field name 'tasa_usd' first before field var 'x_tasa_usd'
        $val = $CurrentForm->hasValue("tasa_usd") ? $CurrentForm->getValue("tasa_usd") : $CurrentForm->getValue("x_tasa_usd");
        if (!$this->tasa_usd->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->tasa_usd->Visible = false; // Disable update for API request
            } else {
                $this->tasa_usd->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_tasa_usd")) {
            $this->tasa_usd->setOldValue($CurrentForm->getValue("o_tasa_usd"));
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
        if ($CurrentForm->hasValue("o_monto_usd")) {
            $this->monto_usd->setOldValue($CurrentForm->getValue("o_monto_usd"));
        }

        // Check field name 'saldo' first before field var 'x_saldo'
        $val = $CurrentForm->hasValue("saldo") ? $CurrentForm->getValue("saldo") : $CurrentForm->getValue("x_saldo");
        if (!$this->saldo->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->saldo->Visible = false; // Disable update for API request
            } else {
                $this->saldo->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_saldo")) {
            $this->saldo->setOldValue($CurrentForm->getValue("o_saldo"));
        }

        // Check field name 'username' first before field var 'x__username'
        $val = $CurrentForm->hasValue("username") ? $CurrentForm->getValue("username") : $CurrentForm->getValue("x__username");
        if (!$this->_username->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->_username->Visible = false; // Disable update for API request
            } else {
                $this->_username->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o__username")) {
            $this->_username->setOldValue($CurrentForm->getValue("o__username"));
        }

        // Check field name 'id' first before field var 'x_id'
        $val = $CurrentForm->hasValue("id") ? $CurrentForm->getValue("id") : $CurrentForm->getValue("x_id");
        if (!$this->id->IsDetailKey && !$this->isGridAdd() && !$this->isAdd()) {
            $this->id->setFormValue($val);
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        if (!$this->isGridAdd() && !$this->isAdd()) {
            $this->id->CurrentValue = $this->id->FormValue;
        }
        $this->cliente->CurrentValue = $this->cliente->FormValue;
        $this->fecha->CurrentValue = $this->fecha->FormValue;
        $this->fecha->CurrentValue = UnFormatDateTime($this->fecha->CurrentValue, 7);
        $this->metodo_pago->CurrentValue = $this->metodo_pago->FormValue;
        $this->referencia->CurrentValue = $this->referencia->FormValue;
        $this->monto_moneda->CurrentValue = $this->monto_moneda->FormValue;
        $this->moneda->CurrentValue = $this->moneda->FormValue;
        $this->monto_bs->CurrentValue = $this->monto_bs->FormValue;
        $this->tasa_usd->CurrentValue = $this->tasa_usd->FormValue;
        $this->monto_usd->CurrentValue = $this->monto_usd->FormValue;
        $this->saldo->CurrentValue = $this->saldo->FormValue;
        $this->_username->CurrentValue = $this->_username->FormValue;
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
        $this->_username->setDbValue($row['username']);
        $this->cobro_cliente_reverso->setDbValue($row['cobro_cliente_reverso']);
        $this->nro_recibo->setDbValue($row['nro_recibo']);
        $this->nota_recepcion->setDbValue($row['nota_recepcion']);
        $this->abono->setDbValue($row['abono']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $this->loadDefaultValues();
        $row = [];
        $row['id'] = $this->id->CurrentValue;
        $row['cliente'] = $this->cliente->CurrentValue;
        $row['fecha'] = $this->fecha->CurrentValue;
        $row['metodo_pago'] = $this->metodo_pago->CurrentValue;
        $row['referencia'] = $this->referencia->CurrentValue;
        $row['reverso'] = $this->reverso->CurrentValue;
        $row['monto_moneda'] = $this->monto_moneda->CurrentValue;
        $row['moneda'] = $this->moneda->CurrentValue;
        $row['tasa_moneda'] = $this->tasa_moneda->CurrentValue;
        $row['monto_bs'] = $this->monto_bs->CurrentValue;
        $row['tasa_usd'] = $this->tasa_usd->CurrentValue;
        $row['monto_usd'] = $this->monto_usd->CurrentValue;
        $row['saldo'] = $this->saldo->CurrentValue;
        $row['nota'] = $this->nota->CurrentValue;
        $row['username'] = $this->_username->CurrentValue;
        $row['cobro_cliente_reverso'] = $this->cobro_cliente_reverso->CurrentValue;
        $row['nro_recibo'] = $this->nro_recibo->CurrentValue;
        $row['nota_recepcion'] = $this->nota_recepcion->CurrentValue;
        $row['abono'] = $this->abono->CurrentValue;
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
        $this->CopyUrl = $this->getCopyUrl();
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

        // id

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

        // username

        // cobro_cliente_reverso

        // nro_recibo

        // nota_recepcion

        // abono
        if ($this->RowType == ROWTYPE_VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;
            $this->id->ViewCustomAttributes = "";

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

            // cobro_cliente_reverso
            $this->cobro_cliente_reverso->ViewValue = $this->cobro_cliente_reverso->CurrentValue;
            $this->cobro_cliente_reverso->ViewValue = FormatNumber($this->cobro_cliente_reverso->ViewValue, 0, -2, -2, -2);
            $this->cobro_cliente_reverso->ViewCustomAttributes = "";

            // nro_recibo
            $this->nro_recibo->ViewValue = $this->nro_recibo->CurrentValue;
            $this->nro_recibo->ViewValue = FormatNumber($this->nro_recibo->ViewValue, 0, -2, -2, -2);
            $this->nro_recibo->ViewCustomAttributes = "";

            // nota_recepcion
            $this->nota_recepcion->ViewValue = $this->nota_recepcion->CurrentValue;
            $this->nota_recepcion->ViewValue = FormatNumber($this->nota_recepcion->ViewValue, 0, -2, -2, -2);
            $this->nota_recepcion->ViewCustomAttributes = "";

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
        } elseif ($this->RowType == ROWTYPE_ADD) {
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

            // fecha
            $this->fecha->EditAttrs["class"] = "form-control";
            $this->fecha->EditCustomAttributes = "";
            $this->fecha->EditValue = HtmlEncode(FormatDateTime($this->fecha->CurrentValue, 7));
            $this->fecha->PlaceHolder = RemoveHtml($this->fecha->caption());

            // metodo_pago
            $this->metodo_pago->EditAttrs["class"] = "form-control";
            $this->metodo_pago->EditCustomAttributes = "";
            $curVal = trim(strval($this->metodo_pago->CurrentValue));
            if ($curVal != "") {
                $this->metodo_pago->ViewValue = $this->metodo_pago->lookupCacheOption($curVal);
            } else {
                $this->metodo_pago->ViewValue = $this->metodo_pago->Lookup !== null && is_array($this->metodo_pago->Lookup->Options) ? $curVal : null;
            }
            if ($this->metodo_pago->ViewValue !== null) { // Load from cache
                $this->metodo_pago->EditValue = array_values($this->metodo_pago->Lookup->Options);
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`valor1`" . SearchString("=", $this->metodo_pago->CurrentValue, DATATYPE_STRING, "");
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
                $this->referencia->CurrentValue = HtmlDecode($this->referencia->CurrentValue);
            }
            $this->referencia->EditValue = HtmlEncode($this->referencia->CurrentValue);
            $this->referencia->PlaceHolder = RemoveHtml($this->referencia->caption());

            // monto_moneda
            $this->monto_moneda->EditAttrs["class"] = "form-control";
            $this->monto_moneda->EditCustomAttributes = "";
            $this->monto_moneda->EditValue = HtmlEncode($this->monto_moneda->CurrentValue);
            $this->monto_moneda->PlaceHolder = RemoveHtml($this->monto_moneda->caption());
            if (strval($this->monto_moneda->EditValue) != "" && is_numeric($this->monto_moneda->EditValue)) {
                $this->monto_moneda->EditValue = FormatNumber($this->monto_moneda->EditValue, -2, -1, -2, -1);
                $this->monto_moneda->OldValue = $this->monto_moneda->EditValue;
            }

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

            // monto_bs
            $this->monto_bs->EditAttrs["class"] = "form-control";
            $this->monto_bs->EditCustomAttributes = "";
            $this->monto_bs->EditValue = HtmlEncode($this->monto_bs->CurrentValue);
            $this->monto_bs->PlaceHolder = RemoveHtml($this->monto_bs->caption());
            if (strval($this->monto_bs->EditValue) != "" && is_numeric($this->monto_bs->EditValue)) {
                $this->monto_bs->EditValue = FormatNumber($this->monto_bs->EditValue, -2, -2, -2, -2);
                $this->monto_bs->OldValue = $this->monto_bs->EditValue;
            }

            // tasa_usd
            $this->tasa_usd->EditAttrs["class"] = "form-control";
            $this->tasa_usd->EditCustomAttributes = "";
            $this->tasa_usd->EditValue = HtmlEncode($this->tasa_usd->CurrentValue);
            $this->tasa_usd->PlaceHolder = RemoveHtml($this->tasa_usd->caption());
            if (strval($this->tasa_usd->EditValue) != "" && is_numeric($this->tasa_usd->EditValue)) {
                $this->tasa_usd->EditValue = FormatNumber($this->tasa_usd->EditValue, -2, -2, -2, -2);
                $this->tasa_usd->OldValue = $this->tasa_usd->EditValue;
            }

            // monto_usd
            $this->monto_usd->EditAttrs["class"] = "form-control";
            $this->monto_usd->EditCustomAttributes = "";
            $this->monto_usd->EditValue = HtmlEncode($this->monto_usd->CurrentValue);
            $this->monto_usd->PlaceHolder = RemoveHtml($this->monto_usd->caption());
            if (strval($this->monto_usd->EditValue) != "" && is_numeric($this->monto_usd->EditValue)) {
                $this->monto_usd->EditValue = FormatNumber($this->monto_usd->EditValue, -2, -1, -2, -1);
                $this->monto_usd->OldValue = $this->monto_usd->EditValue;
            }

            // saldo
            $this->saldo->EditAttrs["class"] = "form-control";
            $this->saldo->EditCustomAttributes = "";
            $this->saldo->EditValue = HtmlEncode($this->saldo->CurrentValue);
            $this->saldo->PlaceHolder = RemoveHtml($this->saldo->caption());
            if (strval($this->saldo->EditValue) != "" && is_numeric($this->saldo->EditValue)) {
                $this->saldo->EditValue = FormatNumber($this->saldo->EditValue, -2, -1, -2, -1);
                $this->saldo->OldValue = $this->saldo->EditValue;
            }

            // username
            $this->_username->EditAttrs["class"] = "form-control";
            $this->_username->EditCustomAttributes = "";
            if (!$this->_username->Raw) {
                $this->_username->CurrentValue = HtmlDecode($this->_username->CurrentValue);
            }
            $this->_username->EditValue = HtmlEncode($this->_username->CurrentValue);
            $curVal = trim(strval($this->_username->CurrentValue));
            if ($curVal != "") {
                $this->_username->EditValue = $this->_username->lookupCacheOption($curVal);
                if ($this->_username->EditValue === null) { // Lookup from database
                    $filterWrk = "`username`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $sqlWrk = $this->_username->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->_username->Lookup->renderViewRow($rswrk[0]);
                        $this->_username->EditValue = $this->_username->displayValue($arwrk);
                    } else {
                        $this->_username->EditValue = HtmlEncode($this->_username->CurrentValue);
                    }
                }
            } else {
                $this->_username->EditValue = null;
            }
            $this->_username->PlaceHolder = RemoveHtml($this->_username->caption());

            // Add refer script

            // cliente
            $this->cliente->LinkCustomAttributes = "";
            $this->cliente->HrefValue = "";

            // fecha
            $this->fecha->LinkCustomAttributes = "";
            $this->fecha->HrefValue = "";

            // metodo_pago
            $this->metodo_pago->LinkCustomAttributes = "";
            $this->metodo_pago->HrefValue = "";

            // referencia
            $this->referencia->LinkCustomAttributes = "";
            $this->referencia->HrefValue = "";

            // monto_moneda
            $this->monto_moneda->LinkCustomAttributes = "";
            $this->monto_moneda->HrefValue = "";

            // moneda
            $this->moneda->LinkCustomAttributes = "";
            $this->moneda->HrefValue = "";

            // monto_bs
            $this->monto_bs->LinkCustomAttributes = "";
            $this->monto_bs->HrefValue = "";

            // tasa_usd
            $this->tasa_usd->LinkCustomAttributes = "";
            $this->tasa_usd->HrefValue = "";

            // monto_usd
            $this->monto_usd->LinkCustomAttributes = "";
            $this->monto_usd->HrefValue = "";

            // saldo
            $this->saldo->LinkCustomAttributes = "";
            $this->saldo->HrefValue = "";

            // username
            $this->_username->LinkCustomAttributes = "";
            $this->_username->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_EDIT) {
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

            // fecha
            $this->fecha->EditAttrs["class"] = "form-control";
            $this->fecha->EditCustomAttributes = "";
            $this->fecha->EditValue = $this->fecha->CurrentValue;
            $this->fecha->EditValue = FormatDateTime($this->fecha->EditValue, 7);
            $this->fecha->ViewCustomAttributes = "";

            // metodo_pago
            $this->metodo_pago->EditAttrs["class"] = "form-control";
            $this->metodo_pago->EditCustomAttributes = "";
            $curVal = trim(strval($this->metodo_pago->CurrentValue));
            if ($curVal != "") {
                $this->metodo_pago->ViewValue = $this->metodo_pago->lookupCacheOption($curVal);
            } else {
                $this->metodo_pago->ViewValue = $this->metodo_pago->Lookup !== null && is_array($this->metodo_pago->Lookup->Options) ? $curVal : null;
            }
            if ($this->metodo_pago->ViewValue !== null) { // Load from cache
                $this->metodo_pago->EditValue = array_values($this->metodo_pago->Lookup->Options);
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`valor1`" . SearchString("=", $this->metodo_pago->CurrentValue, DATATYPE_STRING, "");
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
                $this->referencia->CurrentValue = HtmlDecode($this->referencia->CurrentValue);
            }
            $this->referencia->EditValue = HtmlEncode($this->referencia->CurrentValue);
            $this->referencia->PlaceHolder = RemoveHtml($this->referencia->caption());

            // monto_moneda
            $this->monto_moneda->EditAttrs["class"] = "form-control";
            $this->monto_moneda->EditCustomAttributes = "";
            $this->monto_moneda->EditValue = HtmlEncode($this->monto_moneda->CurrentValue);
            $this->monto_moneda->PlaceHolder = RemoveHtml($this->monto_moneda->caption());
            if (strval($this->monto_moneda->EditValue) != "" && is_numeric($this->monto_moneda->EditValue)) {
                $this->monto_moneda->EditValue = FormatNumber($this->monto_moneda->EditValue, -2, -1, -2, -1);
                $this->monto_moneda->OldValue = $this->monto_moneda->EditValue;
            }

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

            // monto_bs
            $this->monto_bs->EditAttrs["class"] = "form-control";
            $this->monto_bs->EditCustomAttributes = "";
            $this->monto_bs->EditValue = HtmlEncode($this->monto_bs->CurrentValue);
            $this->monto_bs->PlaceHolder = RemoveHtml($this->monto_bs->caption());
            if (strval($this->monto_bs->EditValue) != "" && is_numeric($this->monto_bs->EditValue)) {
                $this->monto_bs->EditValue = FormatNumber($this->monto_bs->EditValue, -2, -2, -2, -2);
                $this->monto_bs->OldValue = $this->monto_bs->EditValue;
            }

            // tasa_usd
            $this->tasa_usd->EditAttrs["class"] = "form-control";
            $this->tasa_usd->EditCustomAttributes = "";
            $this->tasa_usd->EditValue = HtmlEncode($this->tasa_usd->CurrentValue);
            $this->tasa_usd->PlaceHolder = RemoveHtml($this->tasa_usd->caption());
            if (strval($this->tasa_usd->EditValue) != "" && is_numeric($this->tasa_usd->EditValue)) {
                $this->tasa_usd->EditValue = FormatNumber($this->tasa_usd->EditValue, -2, -2, -2, -2);
                $this->tasa_usd->OldValue = $this->tasa_usd->EditValue;
            }

            // monto_usd
            $this->monto_usd->EditAttrs["class"] = "form-control";
            $this->monto_usd->EditCustomAttributes = "";
            $this->monto_usd->EditValue = HtmlEncode($this->monto_usd->CurrentValue);
            $this->monto_usd->PlaceHolder = RemoveHtml($this->monto_usd->caption());
            if (strval($this->monto_usd->EditValue) != "" && is_numeric($this->monto_usd->EditValue)) {
                $this->monto_usd->EditValue = FormatNumber($this->monto_usd->EditValue, -2, -1, -2, -1);
                $this->monto_usd->OldValue = $this->monto_usd->EditValue;
            }

            // saldo
            $this->saldo->EditAttrs["class"] = "form-control";
            $this->saldo->EditCustomAttributes = "";
            $this->saldo->EditValue = HtmlEncode($this->saldo->CurrentValue);
            $this->saldo->PlaceHolder = RemoveHtml($this->saldo->caption());
            if (strval($this->saldo->EditValue) != "" && is_numeric($this->saldo->EditValue)) {
                $this->saldo->EditValue = FormatNumber($this->saldo->EditValue, -2, -1, -2, -1);
                $this->saldo->OldValue = $this->saldo->EditValue;
            }

            // username
            $this->_username->EditAttrs["class"] = "form-control";
            $this->_username->EditCustomAttributes = "";
            if (!$this->_username->Raw) {
                $this->_username->CurrentValue = HtmlDecode($this->_username->CurrentValue);
            }
            $this->_username->EditValue = HtmlEncode($this->_username->CurrentValue);
            $curVal = trim(strval($this->_username->CurrentValue));
            if ($curVal != "") {
                $this->_username->EditValue = $this->_username->lookupCacheOption($curVal);
                if ($this->_username->EditValue === null) { // Lookup from database
                    $filterWrk = "`username`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $sqlWrk = $this->_username->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->_username->Lookup->renderViewRow($rswrk[0]);
                        $this->_username->EditValue = $this->_username->displayValue($arwrk);
                    } else {
                        $this->_username->EditValue = HtmlEncode($this->_username->CurrentValue);
                    }
                }
            } else {
                $this->_username->EditValue = null;
            }
            $this->_username->PlaceHolder = RemoveHtml($this->_username->caption());

            // Edit refer script

            // cliente
            $this->cliente->LinkCustomAttributes = "";
            $this->cliente->HrefValue = "";

            // fecha
            $this->fecha->LinkCustomAttributes = "";
            $this->fecha->HrefValue = "";
            $this->fecha->TooltipValue = "";

            // metodo_pago
            $this->metodo_pago->LinkCustomAttributes = "";
            $this->metodo_pago->HrefValue = "";

            // referencia
            $this->referencia->LinkCustomAttributes = "";
            $this->referencia->HrefValue = "";

            // monto_moneda
            $this->monto_moneda->LinkCustomAttributes = "";
            $this->monto_moneda->HrefValue = "";

            // moneda
            $this->moneda->LinkCustomAttributes = "";
            $this->moneda->HrefValue = "";

            // monto_bs
            $this->monto_bs->LinkCustomAttributes = "";
            $this->monto_bs->HrefValue = "";

            // tasa_usd
            $this->tasa_usd->LinkCustomAttributes = "";
            $this->tasa_usd->HrefValue = "";

            // monto_usd
            $this->monto_usd->LinkCustomAttributes = "";
            $this->monto_usd->HrefValue = "";

            // saldo
            $this->saldo->LinkCustomAttributes = "";
            $this->saldo->HrefValue = "";

            // username
            $this->_username->LinkCustomAttributes = "";
            $this->_username->HrefValue = "";
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
        if ($this->cliente->Required) {
            if (!$this->cliente->IsDetailKey && EmptyValue($this->cliente->FormValue)) {
                $this->cliente->addErrorMessage(str_replace("%s", $this->cliente->caption(), $this->cliente->RequiredErrorMessage));
            }
        }
        if ($this->fecha->Required) {
            if (!$this->fecha->IsDetailKey && EmptyValue($this->fecha->FormValue)) {
                $this->fecha->addErrorMessage(str_replace("%s", $this->fecha->caption(), $this->fecha->RequiredErrorMessage));
            }
        }
        if ($this->metodo_pago->Required) {
            if (!$this->metodo_pago->IsDetailKey && EmptyValue($this->metodo_pago->FormValue)) {
                $this->metodo_pago->addErrorMessage(str_replace("%s", $this->metodo_pago->caption(), $this->metodo_pago->RequiredErrorMessage));
            }
        }
        if ($this->referencia->Required) {
            if (!$this->referencia->IsDetailKey && EmptyValue($this->referencia->FormValue)) {
                $this->referencia->addErrorMessage(str_replace("%s", $this->referencia->caption(), $this->referencia->RequiredErrorMessage));
            }
        }
        if ($this->monto_moneda->Required) {
            if (!$this->monto_moneda->IsDetailKey && EmptyValue($this->monto_moneda->FormValue)) {
                $this->monto_moneda->addErrorMessage(str_replace("%s", $this->monto_moneda->caption(), $this->monto_moneda->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->monto_moneda->FormValue)) {
            $this->monto_moneda->addErrorMessage($this->monto_moneda->getErrorMessage(false));
        }
        if ($this->moneda->Required) {
            if (!$this->moneda->IsDetailKey && EmptyValue($this->moneda->FormValue)) {
                $this->moneda->addErrorMessage(str_replace("%s", $this->moneda->caption(), $this->moneda->RequiredErrorMessage));
            }
        }
        if ($this->monto_bs->Required) {
            if (!$this->monto_bs->IsDetailKey && EmptyValue($this->monto_bs->FormValue)) {
                $this->monto_bs->addErrorMessage(str_replace("%s", $this->monto_bs->caption(), $this->monto_bs->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->monto_bs->FormValue)) {
            $this->monto_bs->addErrorMessage($this->monto_bs->getErrorMessage(false));
        }
        if ($this->tasa_usd->Required) {
            if (!$this->tasa_usd->IsDetailKey && EmptyValue($this->tasa_usd->FormValue)) {
                $this->tasa_usd->addErrorMessage(str_replace("%s", $this->tasa_usd->caption(), $this->tasa_usd->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->tasa_usd->FormValue)) {
            $this->tasa_usd->addErrorMessage($this->tasa_usd->getErrorMessage(false));
        }
        if ($this->monto_usd->Required) {
            if (!$this->monto_usd->IsDetailKey && EmptyValue($this->monto_usd->FormValue)) {
                $this->monto_usd->addErrorMessage(str_replace("%s", $this->monto_usd->caption(), $this->monto_usd->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->monto_usd->FormValue)) {
            $this->monto_usd->addErrorMessage($this->monto_usd->getErrorMessage(false));
        }
        if ($this->saldo->Required) {
            if (!$this->saldo->IsDetailKey && EmptyValue($this->saldo->FormValue)) {
                $this->saldo->addErrorMessage(str_replace("%s", $this->saldo->caption(), $this->saldo->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->saldo->FormValue)) {
            $this->saldo->addErrorMessage($this->saldo->getErrorMessage(false));
        }
        if ($this->_username->Required) {
            if (!$this->_username->IsDetailKey && EmptyValue($this->_username->FormValue)) {
                $this->_username->addErrorMessage(str_replace("%s", $this->_username->caption(), $this->_username->RequiredErrorMessage));
            }
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

    // Delete records based on current filter
    protected function deleteRows()
    {
        global $Language, $Security;
        if (!$Security->canDelete()) {
            $this->setFailureMessage($Language->phrase("NoDeletePermission")); // No delete permission
            return false;
        }
        $deleteRows = true;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $rows = $conn->fetchAll($sql);
        if (count($rows) == 0) {
            $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
            return false;
        }
        if ($this->AuditTrailOnDelete) {
            $this->writeAuditTrailDummy($Language->phrase("BatchDeleteBegin")); // Batch delete begin
        }

        // Clone old rows
        $rsold = $rows;

        // Call row deleting event
        if ($deleteRows) {
            foreach ($rsold as $row) {
                $deleteRows = $this->rowDeleting($row);
                if (!$deleteRows) {
                    break;
                }
            }
        }
        if ($deleteRows) {
            $key = "";
            foreach ($rsold as $row) {
                $thisKey = "";
                if ($thisKey != "") {
                    $thisKey .= Config("COMPOSITE_KEY_SEPARATOR");
                }
                $thisKey .= $row['id'];
                if (Config("DELETE_UPLOADED_FILES")) { // Delete old files
                    $this->deleteUploadedFiles($row);
                }
                $deleteRows = $this->delete($row); // Delete
                if ($deleteRows === false) {
                    break;
                }
                if ($key != "") {
                    $key .= ", ";
                }
                $key .= $thisKey;
            }
        }
        if (!$deleteRows) {
            // Set up error message
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("DeleteCancelled"));
            }
        }

        // Call Row Deleted event
        if ($deleteRows) {
            foreach ($rsold as $row) {
                $this->rowDeleted($row);
            }
        }

        // Write JSON for API request
        if (IsApi() && $deleteRows) {
            $row = $this->getRecordsFromRecordset($rsold);
            WriteJson(["success" => true, $this->TableVar => $row]);
        }
        return $deleteRows;
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
            // Save old values
            $this->loadDbValues($rsold);
            $rsnew = [];

            // cliente
            $this->cliente->setDbValueDef($rsnew, $this->cliente->CurrentValue, null, $this->cliente->ReadOnly);

            // metodo_pago
            $this->metodo_pago->setDbValueDef($rsnew, $this->metodo_pago->CurrentValue, null, $this->metodo_pago->ReadOnly);

            // referencia
            $this->referencia->setDbValueDef($rsnew, $this->referencia->CurrentValue, null, $this->referencia->ReadOnly);

            // monto_moneda
            $this->monto_moneda->setDbValueDef($rsnew, $this->monto_moneda->CurrentValue, null, $this->monto_moneda->ReadOnly);

            // moneda
            $this->moneda->setDbValueDef($rsnew, $this->moneda->CurrentValue, null, $this->moneda->ReadOnly);

            // monto_bs
            $this->monto_bs->setDbValueDef($rsnew, $this->monto_bs->CurrentValue, null, $this->monto_bs->ReadOnly);

            // tasa_usd
            $this->tasa_usd->setDbValueDef($rsnew, $this->tasa_usd->CurrentValue, null, $this->tasa_usd->ReadOnly);

            // monto_usd
            $this->monto_usd->setDbValueDef($rsnew, $this->monto_usd->CurrentValue, null, $this->monto_usd->ReadOnly);

            // saldo
            $this->saldo->setDbValueDef($rsnew, $this->saldo->CurrentValue, null, $this->saldo->ReadOnly);

            // username
            $this->_username->setDbValueDef($rsnew, $this->_username->CurrentValue, null, $this->_username->ReadOnly);

            // Check referential integrity for master table 'abono2'
            $validMasterRecord = true;
            $masterFilter = $this->sqlMasterFilter_abono2();
            $keyValue = $rsnew['abono'] ?? $rsold['abono'];
            if (strval($keyValue) != "") {
                $masterFilter = str_replace("@id@", AdjustSql($keyValue), $masterFilter);
            } else {
                $validMasterRecord = false;
            }
            if ($validMasterRecord) {
                $rsmaster = Container("abono2")->loadRs($masterFilter)->fetch();
                $validMasterRecord = $rsmaster !== false;
            }
            if (!$validMasterRecord) {
                $relatedRecordMsg = str_replace("%t", "abono2", $Language->phrase("RelatedRecordRequired"));
                $this->setFailureMessage($relatedRecordMsg);
                return false;
            }

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

    // Add record
    protected function addRow($rsold = null)
    {
        global $Language, $Security;

        // Set up foreign key field value from Session
        if ($this->getCurrentMasterTable() == "abono2") {
            $this->abono->CurrentValue = $this->abono->getSessionValue();
        }

        // Check referential integrity for master table 'recarga2'
        $validMasterRecord = true;
        $masterFilter = $this->sqlMasterFilter_abono2();
        if ($this->abono->getSessionValue() != "") {
        $masterFilter = str_replace("@id@", AdjustSql($this->abono->getSessionValue(), "DB"), $masterFilter);
        } else {
            $validMasterRecord = false;
        }
        if ($validMasterRecord) {
            $rsmaster = Container("abono2")->loadRs($masterFilter)->fetch();
            $validMasterRecord = $rsmaster !== false;
        }
        if (!$validMasterRecord) {
            $relatedRecordMsg = str_replace("%t", "abono2", $Language->phrase("RelatedRecordRequired"));
            $this->setFailureMessage($relatedRecordMsg);
            return false;
        }
        $conn = $this->getConnection();

        // Load db values from rsold
        $this->loadDbValues($rsold);
        if ($rsold) {
        }
        $rsnew = [];

        // cliente
        $this->cliente->setDbValueDef($rsnew, $this->cliente->CurrentValue, null, false);

        // fecha
        $this->fecha->setDbValueDef($rsnew, UnFormatDateTime($this->fecha->CurrentValue, 7), null, false);

        // metodo_pago
        $this->metodo_pago->setDbValueDef($rsnew, $this->metodo_pago->CurrentValue, null, false);

        // referencia
        $this->referencia->setDbValueDef($rsnew, $this->referencia->CurrentValue, null, false);

        // monto_moneda
        $this->monto_moneda->setDbValueDef($rsnew, $this->monto_moneda->CurrentValue, null, false);

        // moneda
        $this->moneda->setDbValueDef($rsnew, $this->moneda->CurrentValue, null, false);

        // monto_bs
        $this->monto_bs->setDbValueDef($rsnew, $this->monto_bs->CurrentValue, null, false);

        // tasa_usd
        $this->tasa_usd->setDbValueDef($rsnew, $this->tasa_usd->CurrentValue, null, false);

        // monto_usd
        $this->monto_usd->setDbValueDef($rsnew, $this->monto_usd->CurrentValue, null, false);

        // saldo
        $this->saldo->setDbValueDef($rsnew, $this->saldo->CurrentValue, null, false);

        // username
        $this->_username->setDbValueDef($rsnew, $this->_username->CurrentValue, null, false);

        // abono
        if ($this->abono->getSessionValue() != "") {
            $rsnew['abono'] = $this->abono->getSessionValue();
        }

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

    // Set up master/detail based on QueryString
    protected function setupMasterParms()
    {
        // Hide foreign keys
        $masterTblVar = $this->getCurrentMasterTable();
        if ($masterTblVar == "abono2") {
            $masterTbl = Container("abono2");
            $this->abono->Visible = false;
            if ($masterTbl->EventCancelled) {
                $this->EventCancelled = true;
            }
        }
        $this->DbMasterFilter = $this->getMasterFilter(); // Get master filter
        $this->DbDetailFilter = $this->getDetailFilter(); // Get detail filter
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
    			$sql = "SELECT IFNULL(SUM(monto_usd), 0) AS saldo FROM recarga2
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
    			$sql = "SELECT IFNULL(SUM(monto_usd), 0) AS saldo FROM recarga2
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
}
