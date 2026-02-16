<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class CompraView extends Compra
{
    use MessagesTrait;

    // Page ID
    public $PageID = "view";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'compra';

    // Page object name
    public $PageObjName = "CompraView";

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

        // Table object (compra)
        if (!isset($GLOBALS["compra"]) || get_class($GLOBALS["compra"]) == PROJECT_NAMESPACE . "compra") {
            $GLOBALS["compra"] = &$this;
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

            // Handle modal response
            if ($this->IsModal) { // Show as modal
                $row = ["url" => GetUrl($url), "modal" => "1"];
                $pageName = GetPageName($url);
                if ($pageName != $this->getListUrl()) { // Not List page
                    $row["caption"] = $this->getModalCaption($pageName);
                    if ($pageName == "CompraView") {
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
    public $MultiPages; // Multi pages object

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
        $this->proveedor->setVisibility();
        $this->tipo_documento->setVisibility();
        $this->doc_afectado->setVisibility();
        $this->documento->setVisibility();
        $this->nro_control->setVisibility();
        $this->fecha->setVisibility();
        $this->descripcion->setVisibility();
        $this->aplica_retencion->setVisibility();
        $this->monto_exento->setVisibility();
        $this->monto_gravado->setVisibility();
        $this->alicuota->setVisibility();
        $this->monto_iva->setVisibility();
        $this->monto_total->setVisibility();
        $this->monto_pagar->setVisibility();
        $this->ret_iva->setVisibility();
        $this->ref_iva->setVisibility();
        $this->ret_islr->setVisibility();
        $this->ref_islr->setVisibility();
        $this->ret_municipal->setVisibility();
        $this->ref_municipal->setVisibility();
        $this->fecha_registro->setVisibility();
        $this->_username->setVisibility();
        $this->comprobante->setVisibility();
        $this->tipo_iva->setVisibility();
        $this->tipo_islr->setVisibility();
        $this->sustraendo->setVisibility();
        $this->tipo_municipal->setVisibility();
        $this->anulado->setVisibility();
        $this->hideFieldsForAddEdit();

        // Do not use lookup cache
        $this->setUseLookupCache(false);

        // Set up multi page object
        $this->setupMultiPages();

        // Global Page Loading event (in userfn*.php)
        Page_Loading();

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Set up lookup cache
        $this->setupLookupOptions($this->proveedor);
        $this->setupLookupOptions($this->_username);
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
                        $this->terminate("CompraList"); // Return to list page
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
                        $returnUrl = "CompraList"; // No matching record, return to list
                    } else {
                        $this->loadRowValues($this->Recordset); // Load row values
                    }
                    break;
            }
        } else {
            $returnUrl = "CompraList"; // Not page request, return to list
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
        if ($this->monto_exento->FormValue == $this->monto_exento->CurrentValue && is_numeric(ConvertToFloatString($this->monto_exento->CurrentValue))) {
            $this->monto_exento->CurrentValue = ConvertToFloatString($this->monto_exento->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->monto_gravado->FormValue == $this->monto_gravado->CurrentValue && is_numeric(ConvertToFloatString($this->monto_gravado->CurrentValue))) {
            $this->monto_gravado->CurrentValue = ConvertToFloatString($this->monto_gravado->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->alicuota->FormValue == $this->alicuota->CurrentValue && is_numeric(ConvertToFloatString($this->alicuota->CurrentValue))) {
            $this->alicuota->CurrentValue = ConvertToFloatString($this->alicuota->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->monto_iva->FormValue == $this->monto_iva->CurrentValue && is_numeric(ConvertToFloatString($this->monto_iva->CurrentValue))) {
            $this->monto_iva->CurrentValue = ConvertToFloatString($this->monto_iva->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->monto_total->FormValue == $this->monto_total->CurrentValue && is_numeric(ConvertToFloatString($this->monto_total->CurrentValue))) {
            $this->monto_total->CurrentValue = ConvertToFloatString($this->monto_total->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->monto_pagar->FormValue == $this->monto_pagar->CurrentValue && is_numeric(ConvertToFloatString($this->monto_pagar->CurrentValue))) {
            $this->monto_pagar->CurrentValue = ConvertToFloatString($this->monto_pagar->CurrentValue);
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

            // doc_afectado
            $this->doc_afectado->LinkCustomAttributes = "";
            $this->doc_afectado->HrefValue = "";
            $this->doc_afectado->TooltipValue = "";

            // documento
            $this->documento->LinkCustomAttributes = "";
            $this->documento->HrefValue = "";
            $this->documento->TooltipValue = "";

            // nro_control
            $this->nro_control->LinkCustomAttributes = "";
            $this->nro_control->HrefValue = "";
            $this->nro_control->TooltipValue = "";

            // fecha
            $this->fecha->LinkCustomAttributes = "";
            $this->fecha->HrefValue = "";
            $this->fecha->TooltipValue = "";

            // descripcion
            $this->descripcion->LinkCustomAttributes = "";
            $this->descripcion->HrefValue = "";
            $this->descripcion->TooltipValue = "";

            // monto_exento
            $this->monto_exento->LinkCustomAttributes = "";
            $this->monto_exento->HrefValue = "";
            $this->monto_exento->TooltipValue = "";

            // monto_gravado
            $this->monto_gravado->LinkCustomAttributes = "";
            $this->monto_gravado->HrefValue = "";
            $this->monto_gravado->TooltipValue = "";

            // alicuota
            $this->alicuota->LinkCustomAttributes = "";
            $this->alicuota->HrefValue = "";
            $this->alicuota->TooltipValue = "";

            // monto_iva
            $this->monto_iva->LinkCustomAttributes = "";
            $this->monto_iva->HrefValue = "";
            $this->monto_iva->TooltipValue = "";

            // monto_total
            $this->monto_total->LinkCustomAttributes = "";
            $this->monto_total->HrefValue = "";
            $this->monto_total->TooltipValue = "";

            // monto_pagar
            $this->monto_pagar->LinkCustomAttributes = "";
            $this->monto_pagar->HrefValue = "";
            $this->monto_pagar->TooltipValue = "";

            // fecha_registro
            $this->fecha_registro->LinkCustomAttributes = "";
            $this->fecha_registro->HrefValue = "";
            $this->fecha_registro->TooltipValue = "";

            // username
            $this->_username->LinkCustomAttributes = "";
            $this->_username->HrefValue = "";
            $this->_username->TooltipValue = "";
        }

        // Call Row Rendered event
        if ($this->RowType != ROWTYPE_AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("Home");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("CompraList"), "", $this->TableVar, true);
        $pageId = "view";
        $Breadcrumb->add("view", $pageId, $url);
    }

    // Set up multi pages
    protected function setupMultiPages()
    {
        $pages = new SubPages();
        $pages->Style = "tabs";
        $pages->add(0);
        $pages->add(1);
        $pages->add(2);
        $this->MultiPages = $pages;
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
    }

    // Page Data Rendering event
    public function pageDataRendering(&$header) {
    	// Example:
    	//$header = "your header";
    	if($this->comprobante->CurrentValue == "") {
    		if(VerificaFuncion('014')) {
    			$header = '<div id="result" class="container">
    					<buttom class="btn btn-success" id="cmbContab" name="cmbContab">Generar Comprobante Contable</buttom>
    					</div>';
    		}
    	}
    	else {
    		$header = '<div class="alert alert-success" role="alert">
    						Ya se le Gener&oacute; Comprobante Contable a este Documento
    					</div>';
    	}
    }

    // Page Data Rendered event
    public function pageDataRendered(&$footer)
    {
        // Example:
        //$footer = "your footer";
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
