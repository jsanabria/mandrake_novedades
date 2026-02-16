<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class ViewEntradasSalidasGrid extends ViewEntradasSalidas
{
    use MessagesTrait;

    // Page ID
    public $PageID = "grid";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'view_entradas_salidas';

    // Page object name
    public $PageObjName = "ViewEntradasSalidasGrid";

    // Rendering View
    public $RenderingView = false;

    // Grid form hidden field names
    public $FormName = "fview_entradas_salidasgrid";
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

        // Table object (view_entradas_salidas)
        if (!isset($GLOBALS["view_entradas_salidas"]) || get_class($GLOBALS["view_entradas_salidas"]) == PROJECT_NAMESPACE . "view_entradas_salidas") {
            $GLOBALS["view_entradas_salidas"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();
        $this->AddUrl = "ViewEntradasSalidasAdd";

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'view_entradas_salidas');
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
                $doc = new $class(Container("view_entradas_salidas"));
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

        // Get grid add count
        $gridaddcnt = Get(Config("TABLE_GRID_ADD_ROW_COUNT"), "");
        if (is_numeric($gridaddcnt) && $gridaddcnt > 0) {
            $this->GridAddRowCount = $gridaddcnt;
        }

        // Set up list options
        $this->setupListOptions();
        $this->id->Visible = false;
        $this->tipo_documento->Visible = false;
        $this->id_documento->Visible = false;
        $this->fabricante->setVisibility();
        $this->articulo->setVisibility();
        $this->lote->setVisibility();
        $this->fecha_vencimiento->setVisibility();
        $this->almacen->Visible = false;
        $this->cantidad_articulo->setVisibility();
        $this->articulo_unidad_medida->setVisibility();
        $this->cantidad_unidad_medida->Visible = false;
        $this->cantidad_movimiento->Visible = false;
        $this->costo_unidad->Visible = false;
        $this->costo->Visible = false;
        $this->precio_unidad->Visible = false;
        $this->precio->Visible = false;
        $this->id_compra->Visible = false;
        $this->alicuota->Visible = false;
        $this->cantidad_movimiento_consignacion->Visible = false;
        $this->id_consignacion->Visible = false;
        $this->descuento->Visible = false;
        $this->precio_unidad_sin_desc->Visible = false;
        $this->check_ne->Visible = false;
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
        $this->setupLookupOptions($this->fabricante);
        $this->setupLookupOptions($this->articulo);
        $this->setupLookupOptions($this->articulo_unidad_medida);

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
            $this->DisplayRecords = 20; // Load default
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
        if ($this->CurrentMode != "add" && $this->getMasterFilter() != "" && $this->getCurrentMasterTable() == "view_entradas") {
            $masterTbl = Container("view_entradas");
            $rsmaster = $masterTbl->loadRs($this->DbMasterFilter)->fetch(\PDO::FETCH_ASSOC);
            $this->MasterRecordExists = $rsmaster !== false;
            if (!$this->MasterRecordExists) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record found
                $this->terminate("ViewEntradasList"); // Return to master page
                return;
            } else {
                $masterTbl->loadListRowValues($rsmaster);
                $masterTbl->RowType = ROWTYPE_MASTER; // Master row
                $masterTbl->renderListRow();
            }
        }

        // Load master record
        if ($this->CurrentMode != "add" && $this->getMasterFilter() != "" && $this->getCurrentMasterTable() == "view_salidas") {
            $masterTbl = Container("view_salidas");
            $rsmaster = $masterTbl->loadRs($this->DbMasterFilter)->fetch(\PDO::FETCH_ASSOC);
            $this->MasterRecordExists = $rsmaster !== false;
            if (!$this->MasterRecordExists) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record found
                $this->terminate("ViewSalidasList"); // Return to master page
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
                    $this->DisplayRecords = 20; // Non-numeric, load default
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
        $this->cantidad_articulo->FormValue = ""; // Clear form value
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
            $this->clearInlineMode(); // Clear inline edit mode
        } else {
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
            $this->clearInlineMode(); // Clear grid add mode
        } else {
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
        if ($CurrentForm->hasValue("x_fabricante") && $CurrentForm->hasValue("o_fabricante") && $this->fabricante->CurrentValue != $this->fabricante->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_articulo") && $CurrentForm->hasValue("o_articulo") && $this->articulo->CurrentValue != $this->articulo->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_lote") && $CurrentForm->hasValue("o_lote") && $this->lote->CurrentValue != $this->lote->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_fecha_vencimiento") && $CurrentForm->hasValue("o_fecha_vencimiento") && $this->fecha_vencimiento->CurrentValue != $this->fecha_vencimiento->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_cantidad_articulo") && $CurrentForm->hasValue("o_cantidad_articulo") && $this->cantidad_articulo->CurrentValue != $this->cantidad_articulo->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_articulo_unidad_medida") && $CurrentForm->hasValue("o_articulo_unidad_medida") && $this->articulo_unidad_medida->CurrentValue != $this->articulo_unidad_medida->OldValue) {
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
        $this->fabricante->clearErrorMessage();
        $this->articulo->clearErrorMessage();
        $this->lote->clearErrorMessage();
        $this->fecha_vencimiento->clearErrorMessage();
        $this->cantidad_articulo->clearErrorMessage();
        $this->articulo_unidad_medida->clearErrorMessage();
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
            $this->DefaultSort = "";
            if ($this->getSqlOrderBy() != "") {
                $useDefaultSort = true;
                if ($useDefaultSort) {
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
                        $this->tipo_documento->setSessionValue("");
                        $this->id_documento->setSessionValue("");
                        $this->tipo_documento->setSessionValue("");
                        $this->id_documento->setSessionValue("");
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
        if ($this->CurrentMode == "view") { // View mode
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
        $this->tipo_documento->CurrentValue = null;
        $this->tipo_documento->OldValue = $this->tipo_documento->CurrentValue;
        $this->id_documento->CurrentValue = null;
        $this->id_documento->OldValue = $this->id_documento->CurrentValue;
        $this->fabricante->CurrentValue = null;
        $this->fabricante->OldValue = $this->fabricante->CurrentValue;
        $this->articulo->CurrentValue = null;
        $this->articulo->OldValue = $this->articulo->CurrentValue;
        $this->lote->CurrentValue = null;
        $this->lote->OldValue = $this->lote->CurrentValue;
        $this->fecha_vencimiento->CurrentValue = null;
        $this->fecha_vencimiento->OldValue = $this->fecha_vencimiento->CurrentValue;
        $this->almacen->CurrentValue = null;
        $this->almacen->OldValue = $this->almacen->CurrentValue;
        $this->cantidad_articulo->CurrentValue = null;
        $this->cantidad_articulo->OldValue = $this->cantidad_articulo->CurrentValue;
        $this->articulo_unidad_medida->CurrentValue = null;
        $this->articulo_unidad_medida->OldValue = $this->articulo_unidad_medida->CurrentValue;
        $this->cantidad_unidad_medida->CurrentValue = null;
        $this->cantidad_unidad_medida->OldValue = $this->cantidad_unidad_medida->CurrentValue;
        $this->cantidad_movimiento->CurrentValue = null;
        $this->cantidad_movimiento->OldValue = $this->cantidad_movimiento->CurrentValue;
        $this->costo_unidad->CurrentValue = null;
        $this->costo_unidad->OldValue = $this->costo_unidad->CurrentValue;
        $this->costo->CurrentValue = null;
        $this->costo->OldValue = $this->costo->CurrentValue;
        $this->precio_unidad->CurrentValue = null;
        $this->precio_unidad->OldValue = $this->precio_unidad->CurrentValue;
        $this->precio->CurrentValue = null;
        $this->precio->OldValue = $this->precio->CurrentValue;
        $this->id_compra->CurrentValue = null;
        $this->id_compra->OldValue = $this->id_compra->CurrentValue;
        $this->alicuota->CurrentValue = null;
        $this->alicuota->OldValue = $this->alicuota->CurrentValue;
        $this->cantidad_movimiento_consignacion->CurrentValue = null;
        $this->cantidad_movimiento_consignacion->OldValue = $this->cantidad_movimiento_consignacion->CurrentValue;
        $this->id_consignacion->CurrentValue = null;
        $this->id_consignacion->OldValue = $this->id_consignacion->CurrentValue;
        $this->descuento->CurrentValue = null;
        $this->descuento->OldValue = $this->descuento->CurrentValue;
        $this->precio_unidad_sin_desc->CurrentValue = null;
        $this->precio_unidad_sin_desc->OldValue = $this->precio_unidad_sin_desc->CurrentValue;
        $this->check_ne->CurrentValue = "N";
        $this->check_ne->OldValue = $this->check_ne->CurrentValue;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $CurrentForm->FormName = $this->FormName;

        // Check field name 'fabricante' first before field var 'x_fabricante'
        $val = $CurrentForm->hasValue("fabricante") ? $CurrentForm->getValue("fabricante") : $CurrentForm->getValue("x_fabricante");
        if (!$this->fabricante->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->fabricante->Visible = false; // Disable update for API request
            } else {
                $this->fabricante->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_fabricante")) {
            $this->fabricante->setOldValue($CurrentForm->getValue("o_fabricante"));
        }

        // Check field name 'articulo' first before field var 'x_articulo'
        $val = $CurrentForm->hasValue("articulo") ? $CurrentForm->getValue("articulo") : $CurrentForm->getValue("x_articulo");
        if (!$this->articulo->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->articulo->Visible = false; // Disable update for API request
            } else {
                $this->articulo->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_articulo")) {
            $this->articulo->setOldValue($CurrentForm->getValue("o_articulo"));
        }

        // Check field name 'lote' first before field var 'x_lote'
        $val = $CurrentForm->hasValue("lote") ? $CurrentForm->getValue("lote") : $CurrentForm->getValue("x_lote");
        if (!$this->lote->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->lote->Visible = false; // Disable update for API request
            } else {
                $this->lote->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_lote")) {
            $this->lote->setOldValue($CurrentForm->getValue("o_lote"));
        }

        // Check field name 'fecha_vencimiento' first before field var 'x_fecha_vencimiento'
        $val = $CurrentForm->hasValue("fecha_vencimiento") ? $CurrentForm->getValue("fecha_vencimiento") : $CurrentForm->getValue("x_fecha_vencimiento");
        if (!$this->fecha_vencimiento->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->fecha_vencimiento->Visible = false; // Disable update for API request
            } else {
                $this->fecha_vencimiento->setFormValue($val);
            }
            $this->fecha_vencimiento->CurrentValue = UnFormatDateTime($this->fecha_vencimiento->CurrentValue, 7);
        }
        if ($CurrentForm->hasValue("o_fecha_vencimiento")) {
            $this->fecha_vencimiento->setOldValue($CurrentForm->getValue("o_fecha_vencimiento"));
        }

        // Check field name 'cantidad_articulo' first before field var 'x_cantidad_articulo'
        $val = $CurrentForm->hasValue("cantidad_articulo") ? $CurrentForm->getValue("cantidad_articulo") : $CurrentForm->getValue("x_cantidad_articulo");
        if (!$this->cantidad_articulo->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cantidad_articulo->Visible = false; // Disable update for API request
            } else {
                $this->cantidad_articulo->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_cantidad_articulo")) {
            $this->cantidad_articulo->setOldValue($CurrentForm->getValue("o_cantidad_articulo"));
        }

        // Check field name 'articulo_unidad_medida' first before field var 'x_articulo_unidad_medida'
        $val = $CurrentForm->hasValue("articulo_unidad_medida") ? $CurrentForm->getValue("articulo_unidad_medida") : $CurrentForm->getValue("x_articulo_unidad_medida");
        if (!$this->articulo_unidad_medida->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->articulo_unidad_medida->Visible = false; // Disable update for API request
            } else {
                $this->articulo_unidad_medida->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_articulo_unidad_medida")) {
            $this->articulo_unidad_medida->setOldValue($CurrentForm->getValue("o_articulo_unidad_medida"));
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
        $this->fabricante->CurrentValue = $this->fabricante->FormValue;
        $this->articulo->CurrentValue = $this->articulo->FormValue;
        $this->lote->CurrentValue = $this->lote->FormValue;
        $this->fecha_vencimiento->CurrentValue = $this->fecha_vencimiento->FormValue;
        $this->fecha_vencimiento->CurrentValue = UnFormatDateTime($this->fecha_vencimiento->CurrentValue, 7);
        $this->cantidad_articulo->CurrentValue = $this->cantidad_articulo->FormValue;
        $this->articulo_unidad_medida->CurrentValue = $this->articulo_unidad_medida->FormValue;
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
        $this->id_documento->setDbValue($row['id_documento']);
        $this->fabricante->setDbValue($row['fabricante']);
        $this->articulo->setDbValue($row['articulo']);
        $this->lote->setDbValue($row['lote']);
        $this->fecha_vencimiento->setDbValue($row['fecha_vencimiento']);
        $this->almacen->setDbValue($row['almacen']);
        $this->cantidad_articulo->setDbValue($row['cantidad_articulo']);
        $this->articulo_unidad_medida->setDbValue($row['articulo_unidad_medida']);
        $this->cantidad_unidad_medida->setDbValue($row['cantidad_unidad_medida']);
        $this->cantidad_movimiento->setDbValue($row['cantidad_movimiento']);
        $this->costo_unidad->setDbValue($row['costo_unidad']);
        $this->costo->setDbValue($row['costo']);
        $this->precio_unidad->setDbValue($row['precio_unidad']);
        $this->precio->setDbValue($row['precio']);
        $this->id_compra->setDbValue($row['id_compra']);
        $this->alicuota->setDbValue($row['alicuota']);
        $this->cantidad_movimiento_consignacion->setDbValue($row['cantidad_movimiento_consignacion']);
        $this->id_consignacion->setDbValue($row['id_consignacion']);
        $this->descuento->setDbValue($row['descuento']);
        $this->precio_unidad_sin_desc->setDbValue($row['precio_unidad_sin_desc']);
        $this->check_ne->setDbValue($row['check_ne']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $this->loadDefaultValues();
        $row = [];
        $row['id'] = $this->id->CurrentValue;
        $row['tipo_documento'] = $this->tipo_documento->CurrentValue;
        $row['id_documento'] = $this->id_documento->CurrentValue;
        $row['fabricante'] = $this->fabricante->CurrentValue;
        $row['articulo'] = $this->articulo->CurrentValue;
        $row['lote'] = $this->lote->CurrentValue;
        $row['fecha_vencimiento'] = $this->fecha_vencimiento->CurrentValue;
        $row['almacen'] = $this->almacen->CurrentValue;
        $row['cantidad_articulo'] = $this->cantidad_articulo->CurrentValue;
        $row['articulo_unidad_medida'] = $this->articulo_unidad_medida->CurrentValue;
        $row['cantidad_unidad_medida'] = $this->cantidad_unidad_medida->CurrentValue;
        $row['cantidad_movimiento'] = $this->cantidad_movimiento->CurrentValue;
        $row['costo_unidad'] = $this->costo_unidad->CurrentValue;
        $row['costo'] = $this->costo->CurrentValue;
        $row['precio_unidad'] = $this->precio_unidad->CurrentValue;
        $row['precio'] = $this->precio->CurrentValue;
        $row['id_compra'] = $this->id_compra->CurrentValue;
        $row['alicuota'] = $this->alicuota->CurrentValue;
        $row['cantidad_movimiento_consignacion'] = $this->cantidad_movimiento_consignacion->CurrentValue;
        $row['id_consignacion'] = $this->id_consignacion->CurrentValue;
        $row['descuento'] = $this->descuento->CurrentValue;
        $row['precio_unidad_sin_desc'] = $this->precio_unidad_sin_desc->CurrentValue;
        $row['check_ne'] = $this->check_ne->CurrentValue;
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
        if ($this->cantidad_articulo->FormValue == $this->cantidad_articulo->CurrentValue && is_numeric(ConvertToFloatString($this->cantidad_articulo->CurrentValue))) {
            $this->cantidad_articulo->CurrentValue = ConvertToFloatString($this->cantidad_articulo->CurrentValue);
        }

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // id

        // tipo_documento

        // id_documento

        // fabricante

        // articulo

        // lote

        // fecha_vencimiento

        // almacen

        // cantidad_articulo

        // articulo_unidad_medida

        // cantidad_unidad_medida
        $this->cantidad_unidad_medida->CellCssStyle = "white-space: nowrap;";

        // cantidad_movimiento
        $this->cantidad_movimiento->CellCssStyle = "white-space: nowrap;";

        // costo_unidad
        $this->costo_unidad->CellCssStyle = "white-space: nowrap;";

        // costo
        $this->costo->CellCssStyle = "white-space: nowrap;";

        // precio_unidad
        $this->precio_unidad->CellCssStyle = "white-space: nowrap;";

        // precio
        $this->precio->CellCssStyle = "white-space: nowrap;";

        // id_compra
        $this->id_compra->CellCssStyle = "white-space: nowrap;";

        // alicuota

        // cantidad_movimiento_consignacion

        // id_consignacion

        // descuento

        // precio_unidad_sin_desc

        // check_ne
        if ($this->RowType == ROWTYPE_VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;
            $this->id->ViewCustomAttributes = "";

            // tipo_documento
            $this->tipo_documento->ViewValue = $this->tipo_documento->CurrentValue;
            $this->tipo_documento->ViewCustomAttributes = "";

            // id_documento
            $this->id_documento->ViewValue = $this->id_documento->CurrentValue;
            $this->id_documento->ViewCustomAttributes = "";

            // fabricante
            $this->fabricante->ViewValue = $this->fabricante->CurrentValue;
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

            // articulo
            $this->articulo->ViewValue = $this->articulo->CurrentValue;
            $curVal = trim(strval($this->articulo->CurrentValue));
            if ($curVal != "") {
                $this->articulo->ViewValue = $this->articulo->lookupCacheOption($curVal);
                if ($this->articulo->ViewValue === null) { // Lookup from database
                    $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->articulo->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->articulo->Lookup->renderViewRow($rswrk[0]);
                        $this->articulo->ViewValue = $this->articulo->displayValue($arwrk);
                    } else {
                        $this->articulo->ViewValue = $this->articulo->CurrentValue;
                    }
                }
            } else {
                $this->articulo->ViewValue = null;
            }
            $this->articulo->ViewCustomAttributes = "";

            // lote
            $this->lote->ViewValue = $this->lote->CurrentValue;
            $this->lote->ViewCustomAttributes = "";

            // fecha_vencimiento
            $this->fecha_vencimiento->ViewValue = $this->fecha_vencimiento->CurrentValue;
            $this->fecha_vencimiento->ViewValue = FormatDateTime($this->fecha_vencimiento->ViewValue, 7);
            $this->fecha_vencimiento->ViewCustomAttributes = "";

            // almacen
            $this->almacen->ViewValue = $this->almacen->CurrentValue;
            $this->almacen->ViewCustomAttributes = "";

            // cantidad_articulo
            $this->cantidad_articulo->ViewValue = $this->cantidad_articulo->CurrentValue;
            $this->cantidad_articulo->ViewValue = FormatNumber($this->cantidad_articulo->ViewValue, $this->cantidad_articulo->DefaultDecimalPrecision);
            $this->cantidad_articulo->ViewCustomAttributes = "";

            // articulo_unidad_medida
            $this->articulo_unidad_medida->ViewValue = $this->articulo_unidad_medida->CurrentValue;
            $curVal = trim(strval($this->articulo_unidad_medida->CurrentValue));
            if ($curVal != "") {
                $this->articulo_unidad_medida->ViewValue = $this->articulo_unidad_medida->lookupCacheOption($curVal);
                if ($this->articulo_unidad_medida->ViewValue === null) { // Lookup from database
                    $filterWrk = "`codigo`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $sqlWrk = $this->articulo_unidad_medida->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->articulo_unidad_medida->Lookup->renderViewRow($rswrk[0]);
                        $this->articulo_unidad_medida->ViewValue = $this->articulo_unidad_medida->displayValue($arwrk);
                    } else {
                        $this->articulo_unidad_medida->ViewValue = $this->articulo_unidad_medida->CurrentValue;
                    }
                }
            } else {
                $this->articulo_unidad_medida->ViewValue = null;
            }
            $this->articulo_unidad_medida->ViewCustomAttributes = "";

            // cantidad_unidad_medida
            $this->cantidad_unidad_medida->ViewValue = $this->cantidad_unidad_medida->CurrentValue;
            $this->cantidad_unidad_medida->ViewValue = FormatNumber($this->cantidad_unidad_medida->ViewValue, $this->cantidad_unidad_medida->DefaultDecimalPrecision);
            $this->cantidad_unidad_medida->ViewCustomAttributes = "";

            // cantidad_movimiento
            $this->cantidad_movimiento->ViewValue = $this->cantidad_movimiento->CurrentValue;
            $this->cantidad_movimiento->ViewValue = FormatNumber($this->cantidad_movimiento->ViewValue, $this->cantidad_movimiento->DefaultDecimalPrecision);
            $this->cantidad_movimiento->ViewCustomAttributes = "";

            // costo_unidad
            $this->costo_unidad->ViewValue = $this->costo_unidad->CurrentValue;
            $this->costo_unidad->ViewValue = FormatNumber($this->costo_unidad->ViewValue, $this->costo_unidad->DefaultDecimalPrecision);
            $this->costo_unidad->ViewCustomAttributes = "";

            // costo
            $this->costo->ViewValue = $this->costo->CurrentValue;
            $this->costo->ViewValue = FormatNumber($this->costo->ViewValue, $this->costo->DefaultDecimalPrecision);
            $this->costo->ViewCustomAttributes = "";

            // precio_unidad
            $this->precio_unidad->ViewValue = $this->precio_unidad->CurrentValue;
            $this->precio_unidad->ViewValue = FormatNumber($this->precio_unidad->ViewValue, $this->precio_unidad->DefaultDecimalPrecision);
            $this->precio_unidad->ViewCustomAttributes = "";

            // precio
            $this->precio->ViewValue = $this->precio->CurrentValue;
            $this->precio->ViewValue = FormatNumber($this->precio->ViewValue, $this->precio->DefaultDecimalPrecision);
            $this->precio->ViewCustomAttributes = "";

            // id_compra
            $this->id_compra->ViewValue = $this->id_compra->CurrentValue;
            $this->id_compra->ViewCustomAttributes = "";

            // alicuota
            $this->alicuota->ViewValue = $this->alicuota->CurrentValue;
            $this->alicuota->ViewValue = FormatNumber($this->alicuota->ViewValue, $this->alicuota->DefaultDecimalPrecision);
            $this->alicuota->ViewCustomAttributes = "";

            // cantidad_movimiento_consignacion
            $this->cantidad_movimiento_consignacion->ViewValue = $this->cantidad_movimiento_consignacion->CurrentValue;
            $this->cantidad_movimiento_consignacion->ViewValue = FormatNumber($this->cantidad_movimiento_consignacion->ViewValue, $this->cantidad_movimiento_consignacion->DefaultDecimalPrecision);
            $this->cantidad_movimiento_consignacion->ViewCustomAttributes = "";

            // id_consignacion
            $this->id_consignacion->ViewValue = $this->id_consignacion->CurrentValue;
            $this->id_consignacion->ViewCustomAttributes = "";

            // descuento
            $this->descuento->ViewValue = $this->descuento->CurrentValue;
            $this->descuento->ViewValue = FormatNumber($this->descuento->ViewValue, $this->descuento->DefaultDecimalPrecision);
            $this->descuento->ViewCustomAttributes = "";

            // precio_unidad_sin_desc
            $this->precio_unidad_sin_desc->ViewValue = $this->precio_unidad_sin_desc->CurrentValue;
            $this->precio_unidad_sin_desc->ViewValue = FormatNumber($this->precio_unidad_sin_desc->ViewValue, $this->precio_unidad_sin_desc->DefaultDecimalPrecision);
            $this->precio_unidad_sin_desc->ViewCustomAttributes = "";

            // check_ne
            if (strval($this->check_ne->CurrentValue) != "") {
                $this->check_ne->ViewValue = $this->check_ne->optionCaption($this->check_ne->CurrentValue);
            } else {
                $this->check_ne->ViewValue = null;
            }
            $this->check_ne->ViewCustomAttributes = "";

            // fabricante
            $this->fabricante->LinkCustomAttributes = "";
            $this->fabricante->HrefValue = "";
            $this->fabricante->TooltipValue = "";

            // articulo
            $this->articulo->LinkCustomAttributes = "";
            $this->articulo->HrefValue = "";
            $this->articulo->TooltipValue = "";

            // lote
            $this->lote->LinkCustomAttributes = "";
            $this->lote->HrefValue = "";
            $this->lote->TooltipValue = "";

            // fecha_vencimiento
            $this->fecha_vencimiento->LinkCustomAttributes = "";
            $this->fecha_vencimiento->HrefValue = "";
            $this->fecha_vencimiento->TooltipValue = "";

            // cantidad_articulo
            $this->cantidad_articulo->LinkCustomAttributes = "";
            $this->cantidad_articulo->HrefValue = "";
            $this->cantidad_articulo->TooltipValue = "";

            // articulo_unidad_medida
            $this->articulo_unidad_medida->LinkCustomAttributes = "";
            $this->articulo_unidad_medida->HrefValue = "";
            $this->articulo_unidad_medida->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // fabricante
            $this->fabricante->EditAttrs["class"] = "form-control";
            $this->fabricante->EditCustomAttributes = "";
            $this->fabricante->EditValue = HtmlEncode($this->fabricante->CurrentValue);
            $curVal = trim(strval($this->fabricante->CurrentValue));
            if ($curVal != "") {
                $this->fabricante->EditValue = $this->fabricante->lookupCacheOption($curVal);
                if ($this->fabricante->EditValue === null) { // Lookup from database
                    $filterWrk = "`Id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->fabricante->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->fabricante->Lookup->renderViewRow($rswrk[0]);
                        $this->fabricante->EditValue = $this->fabricante->displayValue($arwrk);
                    } else {
                        $this->fabricante->EditValue = HtmlEncode($this->fabricante->CurrentValue);
                    }
                }
            } else {
                $this->fabricante->EditValue = null;
            }
            $this->fabricante->PlaceHolder = RemoveHtml($this->fabricante->caption());

            // articulo
            $this->articulo->EditAttrs["class"] = "form-control";
            $this->articulo->EditCustomAttributes = "";
            $this->articulo->EditValue = HtmlEncode($this->articulo->CurrentValue);
            $curVal = trim(strval($this->articulo->CurrentValue));
            if ($curVal != "") {
                $this->articulo->EditValue = $this->articulo->lookupCacheOption($curVal);
                if ($this->articulo->EditValue === null) { // Lookup from database
                    $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->articulo->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->articulo->Lookup->renderViewRow($rswrk[0]);
                        $this->articulo->EditValue = $this->articulo->displayValue($arwrk);
                    } else {
                        $this->articulo->EditValue = HtmlEncode($this->articulo->CurrentValue);
                    }
                }
            } else {
                $this->articulo->EditValue = null;
            }
            $this->articulo->PlaceHolder = RemoveHtml($this->articulo->caption());

            // lote
            $this->lote->EditAttrs["class"] = "form-control";
            $this->lote->EditCustomAttributes = "";
            if (!$this->lote->Raw) {
                $this->lote->CurrentValue = HtmlDecode($this->lote->CurrentValue);
            }
            $this->lote->EditValue = HtmlEncode($this->lote->CurrentValue);
            $this->lote->PlaceHolder = RemoveHtml($this->lote->caption());

            // fecha_vencimiento
            $this->fecha_vencimiento->EditAttrs["class"] = "form-control";
            $this->fecha_vencimiento->EditCustomAttributes = "";
            $this->fecha_vencimiento->EditValue = HtmlEncode(FormatDateTime($this->fecha_vencimiento->CurrentValue, 7));
            $this->fecha_vencimiento->PlaceHolder = RemoveHtml($this->fecha_vencimiento->caption());

            // cantidad_articulo
            $this->cantidad_articulo->EditAttrs["class"] = "form-control";
            $this->cantidad_articulo->EditCustomAttributes = "";
            $this->cantidad_articulo->EditValue = HtmlEncode($this->cantidad_articulo->CurrentValue);
            $this->cantidad_articulo->PlaceHolder = RemoveHtml($this->cantidad_articulo->caption());
            if (strval($this->cantidad_articulo->EditValue) != "" && is_numeric($this->cantidad_articulo->EditValue)) {
                $this->cantidad_articulo->EditValue = FormatNumber($this->cantidad_articulo->EditValue, -2, -1, -2, 0);
                $this->cantidad_articulo->OldValue = $this->cantidad_articulo->EditValue;
            }

            // articulo_unidad_medida
            $this->articulo_unidad_medida->EditAttrs["class"] = "form-control";
            $this->articulo_unidad_medida->EditCustomAttributes = "";
            if (!$this->articulo_unidad_medida->Raw) {
                $this->articulo_unidad_medida->CurrentValue = HtmlDecode($this->articulo_unidad_medida->CurrentValue);
            }
            $this->articulo_unidad_medida->EditValue = HtmlEncode($this->articulo_unidad_medida->CurrentValue);
            $curVal = trim(strval($this->articulo_unidad_medida->CurrentValue));
            if ($curVal != "") {
                $this->articulo_unidad_medida->EditValue = $this->articulo_unidad_medida->lookupCacheOption($curVal);
                if ($this->articulo_unidad_medida->EditValue === null) { // Lookup from database
                    $filterWrk = "`codigo`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $sqlWrk = $this->articulo_unidad_medida->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->articulo_unidad_medida->Lookup->renderViewRow($rswrk[0]);
                        $this->articulo_unidad_medida->EditValue = $this->articulo_unidad_medida->displayValue($arwrk);
                    } else {
                        $this->articulo_unidad_medida->EditValue = HtmlEncode($this->articulo_unidad_medida->CurrentValue);
                    }
                }
            } else {
                $this->articulo_unidad_medida->EditValue = null;
            }
            $this->articulo_unidad_medida->PlaceHolder = RemoveHtml($this->articulo_unidad_medida->caption());

            // Add refer script

            // fabricante
            $this->fabricante->LinkCustomAttributes = "";
            $this->fabricante->HrefValue = "";

            // articulo
            $this->articulo->LinkCustomAttributes = "";
            $this->articulo->HrefValue = "";

            // lote
            $this->lote->LinkCustomAttributes = "";
            $this->lote->HrefValue = "";

            // fecha_vencimiento
            $this->fecha_vencimiento->LinkCustomAttributes = "";
            $this->fecha_vencimiento->HrefValue = "";

            // cantidad_articulo
            $this->cantidad_articulo->LinkCustomAttributes = "";
            $this->cantidad_articulo->HrefValue = "";

            // articulo_unidad_medida
            $this->articulo_unidad_medida->LinkCustomAttributes = "";
            $this->articulo_unidad_medida->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // fabricante
            $this->fabricante->EditAttrs["class"] = "form-control";
            $this->fabricante->EditCustomAttributes = "";
            $this->fabricante->EditValue = HtmlEncode($this->fabricante->CurrentValue);
            $curVal = trim(strval($this->fabricante->CurrentValue));
            if ($curVal != "") {
                $this->fabricante->EditValue = $this->fabricante->lookupCacheOption($curVal);
                if ($this->fabricante->EditValue === null) { // Lookup from database
                    $filterWrk = "`Id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->fabricante->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->fabricante->Lookup->renderViewRow($rswrk[0]);
                        $this->fabricante->EditValue = $this->fabricante->displayValue($arwrk);
                    } else {
                        $this->fabricante->EditValue = HtmlEncode($this->fabricante->CurrentValue);
                    }
                }
            } else {
                $this->fabricante->EditValue = null;
            }
            $this->fabricante->PlaceHolder = RemoveHtml($this->fabricante->caption());

            // articulo
            $this->articulo->EditAttrs["class"] = "form-control";
            $this->articulo->EditCustomAttributes = "";
            $this->articulo->EditValue = HtmlEncode($this->articulo->CurrentValue);
            $curVal = trim(strval($this->articulo->CurrentValue));
            if ($curVal != "") {
                $this->articulo->EditValue = $this->articulo->lookupCacheOption($curVal);
                if ($this->articulo->EditValue === null) { // Lookup from database
                    $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->articulo->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->articulo->Lookup->renderViewRow($rswrk[0]);
                        $this->articulo->EditValue = $this->articulo->displayValue($arwrk);
                    } else {
                        $this->articulo->EditValue = HtmlEncode($this->articulo->CurrentValue);
                    }
                }
            } else {
                $this->articulo->EditValue = null;
            }
            $this->articulo->PlaceHolder = RemoveHtml($this->articulo->caption());

            // lote
            $this->lote->EditAttrs["class"] = "form-control";
            $this->lote->EditCustomAttributes = "";
            if (!$this->lote->Raw) {
                $this->lote->CurrentValue = HtmlDecode($this->lote->CurrentValue);
            }
            $this->lote->EditValue = HtmlEncode($this->lote->CurrentValue);
            $this->lote->PlaceHolder = RemoveHtml($this->lote->caption());

            // fecha_vencimiento
            $this->fecha_vencimiento->EditAttrs["class"] = "form-control";
            $this->fecha_vencimiento->EditCustomAttributes = "";
            $this->fecha_vencimiento->EditValue = HtmlEncode(FormatDateTime($this->fecha_vencimiento->CurrentValue, 7));
            $this->fecha_vencimiento->PlaceHolder = RemoveHtml($this->fecha_vencimiento->caption());

            // cantidad_articulo
            $this->cantidad_articulo->EditAttrs["class"] = "form-control";
            $this->cantidad_articulo->EditCustomAttributes = "";
            $this->cantidad_articulo->EditValue = HtmlEncode($this->cantidad_articulo->CurrentValue);
            $this->cantidad_articulo->PlaceHolder = RemoveHtml($this->cantidad_articulo->caption());
            if (strval($this->cantidad_articulo->EditValue) != "" && is_numeric($this->cantidad_articulo->EditValue)) {
                $this->cantidad_articulo->EditValue = FormatNumber($this->cantidad_articulo->EditValue, -2, -1, -2, 0);
                $this->cantidad_articulo->OldValue = $this->cantidad_articulo->EditValue;
            }

            // articulo_unidad_medida
            $this->articulo_unidad_medida->EditAttrs["class"] = "form-control";
            $this->articulo_unidad_medida->EditCustomAttributes = "";
            if (!$this->articulo_unidad_medida->Raw) {
                $this->articulo_unidad_medida->CurrentValue = HtmlDecode($this->articulo_unidad_medida->CurrentValue);
            }
            $this->articulo_unidad_medida->EditValue = HtmlEncode($this->articulo_unidad_medida->CurrentValue);
            $curVal = trim(strval($this->articulo_unidad_medida->CurrentValue));
            if ($curVal != "") {
                $this->articulo_unidad_medida->EditValue = $this->articulo_unidad_medida->lookupCacheOption($curVal);
                if ($this->articulo_unidad_medida->EditValue === null) { // Lookup from database
                    $filterWrk = "`codigo`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $sqlWrk = $this->articulo_unidad_medida->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->articulo_unidad_medida->Lookup->renderViewRow($rswrk[0]);
                        $this->articulo_unidad_medida->EditValue = $this->articulo_unidad_medida->displayValue($arwrk);
                    } else {
                        $this->articulo_unidad_medida->EditValue = HtmlEncode($this->articulo_unidad_medida->CurrentValue);
                    }
                }
            } else {
                $this->articulo_unidad_medida->EditValue = null;
            }
            $this->articulo_unidad_medida->PlaceHolder = RemoveHtml($this->articulo_unidad_medida->caption());

            // Edit refer script

            // fabricante
            $this->fabricante->LinkCustomAttributes = "";
            $this->fabricante->HrefValue = "";

            // articulo
            $this->articulo->LinkCustomAttributes = "";
            $this->articulo->HrefValue = "";

            // lote
            $this->lote->LinkCustomAttributes = "";
            $this->lote->HrefValue = "";

            // fecha_vencimiento
            $this->fecha_vencimiento->LinkCustomAttributes = "";
            $this->fecha_vencimiento->HrefValue = "";

            // cantidad_articulo
            $this->cantidad_articulo->LinkCustomAttributes = "";
            $this->cantidad_articulo->HrefValue = "";

            // articulo_unidad_medida
            $this->articulo_unidad_medida->LinkCustomAttributes = "";
            $this->articulo_unidad_medida->HrefValue = "";
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
        if ($this->fabricante->Required) {
            if (!$this->fabricante->IsDetailKey && EmptyValue($this->fabricante->FormValue)) {
                $this->fabricante->addErrorMessage(str_replace("%s", $this->fabricante->caption(), $this->fabricante->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->fabricante->FormValue)) {
            $this->fabricante->addErrorMessage($this->fabricante->getErrorMessage(false));
        }
        if ($this->articulo->Required) {
            if (!$this->articulo->IsDetailKey && EmptyValue($this->articulo->FormValue)) {
                $this->articulo->addErrorMessage(str_replace("%s", $this->articulo->caption(), $this->articulo->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->articulo->FormValue)) {
            $this->articulo->addErrorMessage($this->articulo->getErrorMessage(false));
        }
        if ($this->lote->Required) {
            if (!$this->lote->IsDetailKey && EmptyValue($this->lote->FormValue)) {
                $this->lote->addErrorMessage(str_replace("%s", $this->lote->caption(), $this->lote->RequiredErrorMessage));
            }
        }
        if ($this->fecha_vencimiento->Required) {
            if (!$this->fecha_vencimiento->IsDetailKey && EmptyValue($this->fecha_vencimiento->FormValue)) {
                $this->fecha_vencimiento->addErrorMessage(str_replace("%s", $this->fecha_vencimiento->caption(), $this->fecha_vencimiento->RequiredErrorMessage));
            }
        }
        if (!CheckEuroDate($this->fecha_vencimiento->FormValue)) {
            $this->fecha_vencimiento->addErrorMessage($this->fecha_vencimiento->getErrorMessage(false));
        }
        if ($this->cantidad_articulo->Required) {
            if (!$this->cantidad_articulo->IsDetailKey && EmptyValue($this->cantidad_articulo->FormValue)) {
                $this->cantidad_articulo->addErrorMessage(str_replace("%s", $this->cantidad_articulo->caption(), $this->cantidad_articulo->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->cantidad_articulo->FormValue)) {
            $this->cantidad_articulo->addErrorMessage($this->cantidad_articulo->getErrorMessage(false));
        }
        if ($this->articulo_unidad_medida->Required) {
            if (!$this->articulo_unidad_medida->IsDetailKey && EmptyValue($this->articulo_unidad_medida->FormValue)) {
                $this->articulo_unidad_medida->addErrorMessage(str_replace("%s", $this->articulo_unidad_medida->caption(), $this->articulo_unidad_medida->RequiredErrorMessage));
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

            // fabricante
            $this->fabricante->setDbValueDef($rsnew, $this->fabricante->CurrentValue, null, $this->fabricante->ReadOnly);

            // articulo
            $this->articulo->setDbValueDef($rsnew, $this->articulo->CurrentValue, null, $this->articulo->ReadOnly);

            // lote
            $this->lote->setDbValueDef($rsnew, $this->lote->CurrentValue, null, $this->lote->ReadOnly);

            // fecha_vencimiento
            $this->fecha_vencimiento->setDbValueDef($rsnew, UnFormatDateTime($this->fecha_vencimiento->CurrentValue, 7), null, $this->fecha_vencimiento->ReadOnly);

            // cantidad_articulo
            $this->cantidad_articulo->setDbValueDef($rsnew, $this->cantidad_articulo->CurrentValue, null, $this->cantidad_articulo->ReadOnly);

            // articulo_unidad_medida
            $this->articulo_unidad_medida->setDbValueDef($rsnew, $this->articulo_unidad_medida->CurrentValue, null, $this->articulo_unidad_medida->ReadOnly);

            // Check referential integrity for master table 'view_entradas'
            $validMasterRecord = true;
            $masterFilter = $this->sqlMasterFilter_view_entradas();
            $keyValue = $rsnew['tipo_documento'] ?? $rsold['tipo_documento'];
            if (strval($keyValue) != "") {
                $masterFilter = str_replace("@tipo_documento@", AdjustSql($keyValue), $masterFilter);
            } else {
                $validMasterRecord = false;
            }
            $keyValue = $rsnew['id_documento'] ?? $rsold['id_documento'];
            if (strval($keyValue) != "") {
                $masterFilter = str_replace("@id@", AdjustSql($keyValue), $masterFilter);
            } else {
                $validMasterRecord = false;
            }
            if ($validMasterRecord) {
                $rsmaster = Container("view_entradas")->loadRs($masterFilter)->fetch();
                $validMasterRecord = $rsmaster !== false;
            }
            if (!$validMasterRecord) {
                $relatedRecordMsg = str_replace("%t", "view_entradas", $Language->phrase("RelatedRecordRequired"));
                $this->setFailureMessage($relatedRecordMsg);
                return false;
            }

            // Check referential integrity for master table 'view_salidas'
            $validMasterRecord = true;
            $masterFilter = $this->sqlMasterFilter_view_salidas();
            $keyValue = $rsnew['tipo_documento'] ?? $rsold['tipo_documento'];
            if (strval($keyValue) != "") {
                $masterFilter = str_replace("@tipo_documento@", AdjustSql($keyValue), $masterFilter);
            } else {
                $validMasterRecord = false;
            }
            $keyValue = $rsnew['id_documento'] ?? $rsold['id_documento'];
            if (strval($keyValue) != "") {
                $masterFilter = str_replace("@id@", AdjustSql($keyValue), $masterFilter);
            } else {
                $validMasterRecord = false;
            }
            if ($validMasterRecord) {
                $rsmaster = Container("view_salidas")->loadRs($masterFilter)->fetch();
                $validMasterRecord = $rsmaster !== false;
            }
            if (!$validMasterRecord) {
                $relatedRecordMsg = str_replace("%t", "view_salidas", $Language->phrase("RelatedRecordRequired"));
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
        if ($this->getCurrentMasterTable() == "view_entradas") {
            $this->tipo_documento->CurrentValue = $this->tipo_documento->getSessionValue();
            $this->id_documento->CurrentValue = $this->id_documento->getSessionValue();
        }
        if ($this->getCurrentMasterTable() == "view_salidas") {
            $this->tipo_documento->CurrentValue = $this->tipo_documento->getSessionValue();
            $this->id_documento->CurrentValue = $this->id_documento->getSessionValue();
        }

        // Check referential integrity for master table 'view_entradas_salidas'
        $validMasterRecord = true;
        $masterFilter = $this->sqlMasterFilter_view_entradas();
        if ($this->tipo_documento->getSessionValue() != "") {
        $masterFilter = str_replace("@tipo_documento@", AdjustSql($this->tipo_documento->getSessionValue(), "DB"), $masterFilter);
        } else {
            $validMasterRecord = false;
        }
        if ($this->id_documento->getSessionValue() != "") {
        $masterFilter = str_replace("@id@", AdjustSql($this->id_documento->getSessionValue(), "DB"), $masterFilter);
        } else {
            $validMasterRecord = false;
        }
        if ($validMasterRecord) {
            $rsmaster = Container("view_entradas")->loadRs($masterFilter)->fetch();
            $validMasterRecord = $rsmaster !== false;
        }
        if (!$validMasterRecord) {
            $relatedRecordMsg = str_replace("%t", "view_entradas", $Language->phrase("RelatedRecordRequired"));
            $this->setFailureMessage($relatedRecordMsg);
            return false;
        }

        // Check referential integrity for master table 'view_entradas_salidas'
        $validMasterRecord = true;
        $masterFilter = $this->sqlMasterFilter_view_salidas();
        if ($this->tipo_documento->getSessionValue() != "") {
        $masterFilter = str_replace("@tipo_documento@", AdjustSql($this->tipo_documento->getSessionValue(), "DB"), $masterFilter);
        } else {
            $validMasterRecord = false;
        }
        if ($this->id_documento->getSessionValue() != "") {
        $masterFilter = str_replace("@id@", AdjustSql($this->id_documento->getSessionValue(), "DB"), $masterFilter);
        } else {
            $validMasterRecord = false;
        }
        if ($validMasterRecord) {
            $rsmaster = Container("view_salidas")->loadRs($masterFilter)->fetch();
            $validMasterRecord = $rsmaster !== false;
        }
        if (!$validMasterRecord) {
            $relatedRecordMsg = str_replace("%t", "view_salidas", $Language->phrase("RelatedRecordRequired"));
            $this->setFailureMessage($relatedRecordMsg);
            return false;
        }
        $conn = $this->getConnection();

        // Load db values from rsold
        $this->loadDbValues($rsold);
        if ($rsold) {
        }
        $rsnew = [];

        // fabricante
        $this->fabricante->setDbValueDef($rsnew, $this->fabricante->CurrentValue, null, false);

        // articulo
        $this->articulo->setDbValueDef($rsnew, $this->articulo->CurrentValue, null, false);

        // lote
        $this->lote->setDbValueDef($rsnew, $this->lote->CurrentValue, null, false);

        // fecha_vencimiento
        $this->fecha_vencimiento->setDbValueDef($rsnew, UnFormatDateTime($this->fecha_vencimiento->CurrentValue, 7), null, false);

        // cantidad_articulo
        $this->cantidad_articulo->setDbValueDef($rsnew, $this->cantidad_articulo->CurrentValue, null, false);

        // articulo_unidad_medida
        $this->articulo_unidad_medida->setDbValueDef($rsnew, $this->articulo_unidad_medida->CurrentValue, null, false);

        // tipo_documento
        if ($this->tipo_documento->getSessionValue() != "") {
            $rsnew['tipo_documento'] = $this->tipo_documento->getSessionValue();
        }

        // id_documento
        if ($this->id_documento->getSessionValue() != "") {
            $rsnew['id_documento'] = $this->id_documento->getSessionValue();
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
        if ($masterTblVar == "view_entradas") {
            $masterTbl = Container("view_entradas");
            $this->tipo_documento->Visible = false;
            if ($masterTbl->EventCancelled) {
                $this->EventCancelled = true;
            }
            $this->id_documento->Visible = false;
            if ($masterTbl->EventCancelled) {
                $this->EventCancelled = true;
            }
        }
        if ($masterTblVar == "view_salidas") {
            $masterTbl = Container("view_salidas");
            $this->tipo_documento->Visible = false;
            if ($masterTbl->EventCancelled) {
                $this->EventCancelled = true;
            }
            $this->id_documento->Visible = false;
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
                case "x_fabricante":
                    break;
                case "x_articulo":
                    break;
                case "x_articulo_unidad_medida":
                    break;
                case "x_check_ne":
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
    public function pageDataRendering(&$header) {
    	// Example:
    	//$id = $this->id_documento->CurrentValue;
    	//$tipo = $this->tipo_documento->CurrentValue;

    /*	$sql = "SELECT tipo_documento FROM username_tipo_documento WHERE username = '" . CurrentUserName() . "';";
    	$tipo = ExecuteScalar($sql);
    	$crear = $_REQUEST["crear"];
    	$id = $this->id->CurrentValue;
    	$tipo = $this->tipo_documento->CurrentValue;
    	switch($crear) {
    	case "TDCNRP":
    		$url = "crear_nota_recepcion.php?id=$id";
    		break;
    	case "TDCFCC":
    		$url = "crear_factura_compra.php?id=$id";
    		break;
    	}
    	if($tipo == "TDCNRP") {
    		$url = "crear_factura_compra.php?id=$id";
    	}
    	else if($tipo == "TDCNET") {
    		$url = "crear_nota_entrega.php?id=$id";
    	}
    	else {
    		$url = "crear_nota_recepcion.php?id=$id";
    	}
    	$sql = "SELECT COUNT(*) AS cantidad FROM entradas_salidas WHERE tipo_documento = '$tipo' AND id_documento = '$id' AND IFNULL(cantidad_articulo, 0) > 0;";
    	if(ExecuteScalar($sql) > 0) {
    		$header = '<a class="btn btn-primary" onclick="js:if(!confirm(\'Seguro que desea procesar este documento?\')) return false;" href="' . $url . '"><span data-phrase="ProcessLink" class="glyphicon glyphicon-cog ewIcon" data-caption="Process"></span> Cargar Recepci&oacute;n</a>';
    	}
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
