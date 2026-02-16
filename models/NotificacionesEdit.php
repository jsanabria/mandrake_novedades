<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class NotificacionesEdit extends Notificaciones
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'notificaciones';

    // Page object name
    public $PageObjName = "NotificacionesEdit";

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

        // Table object (notificaciones)
        if (!isset($GLOBALS["notificaciones"]) || get_class($GLOBALS["notificaciones"]) == PROJECT_NAMESPACE . "notificaciones") {
            $GLOBALS["notificaciones"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'notificaciones');
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
                $doc = new $class(Container("notificaciones"));
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
                    if ($pageName == "NotificacionesView") {
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
            $key .= @$ar['Nnotificaciones'];
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
            $this->Nnotificaciones->Visible = false;
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
        $this->Nnotificaciones->Visible = false;
        $this->tipo->Visible = false;
        $this->notificar->setVisibility();
        $this->asunto->setVisibility();
        $this->notificacion->setVisibility();
        $this->notificados->setVisibility();
        $this->notificados_efectivos->setVisibility();
        $this->_username->setVisibility();
        $this->fecha->setVisibility();
        $this->enviado->setVisibility();
        $this->adjunto->setVisibility();
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
        $this->setupLookupOptions($this->tipo);
        $this->setupLookupOptions($this->notificar);
        $this->setupLookupOptions($this->_username);

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        $this->FormClassName = "ew-form ew-edit-form ew-horizontal";

        // Load record by position
        $loadByPosition = false;
        $loaded = false;
        $postBack = false;

        // Set up current action and primary key
        if (IsApi()) {
            // Load key values
            $loaded = true;
            if (($keyValue = Get("Nnotificaciones") ?? Key(0) ?? Route(2)) !== null) {
                $this->Nnotificaciones->setQueryStringValue($keyValue);
                $this->Nnotificaciones->setOldValue($this->Nnotificaciones->QueryStringValue);
            } elseif (Post("Nnotificaciones") !== null) {
                $this->Nnotificaciones->setFormValue(Post("Nnotificaciones"));
                $this->Nnotificaciones->setOldValue($this->Nnotificaciones->FormValue);
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
                if (($keyValue = Get("Nnotificaciones") ?? Route("Nnotificaciones")) !== null) {
                    $this->Nnotificaciones->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->Nnotificaciones->CurrentValue = null;
                }
                if (!$loadByQuery) {
                    $loadByPosition = true;
                }
            }

            // Load recordset
            if ($this->isShow()) {
                $this->StartRecord = 1; // Initialize start position
                if ($rs = $this->loadRecordset()) { // Load records
                    $this->TotalRecords = $rs->recordCount(); // Get record count
                }
                if ($this->TotalRecords <= 0) { // No record found
                    if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "") {
                        $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                    }
                    $this->terminate("NotificacionesList"); // Return to list page
                    return;
                } elseif ($loadByPosition) { // Load record by position
                    $this->setupStartRecord(); // Set up start record position
                    // Point to current record
                    if ($this->StartRecord <= $this->TotalRecords) {
                        $rs->move($this->StartRecord - 1);
                        $loaded = true;
                    }
                } else { // Match key values
                    if ($this->Nnotificaciones->CurrentValue != null) {
                        while (!$rs->EOF) {
                            if (SameString($this->Nnotificaciones->CurrentValue, $rs->fields['Nnotificaciones'])) {
                                $this->setStartRecordNumber($this->StartRecord); // Save record position
                                $loaded = true;
                                break;
                            } else {
                                $this->StartRecord++;
                                $rs->moveNext();
                            }
                        }
                    }
                }

                // Load current row values
                if ($loaded) {
                    $this->loadRowValues($rs);
                }
                $this->OldKey = $loaded ? $this->getKey(true) : ""; // Get from CurrentValue
            }
        }

        // Process form if post back
        if ($postBack) {
            $this->loadFormValues(); // Get form values
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
                if (!$loaded) {
                    if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "") {
                        $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                    }
                    $this->terminate("NotificacionesList"); // Return to list page
                    return;
                } else {
                }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "NotificacionesList") {
                    $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                }
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
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render the record
        $this->RowType = ROWTYPE_EDIT; // Render as Edit
        $this->resetAttributes();
        $this->renderRow();
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

    // Get upload files
    protected function getUploadFiles()
    {
        global $CurrentForm, $Language;
        $this->adjunto->Upload->Index = $CurrentForm->Index;
        $this->adjunto->Upload->uploadFile();
        $this->adjunto->CurrentValue = $this->adjunto->Upload->FileName;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;

        // Check field name 'notificar' first before field var 'x_notificar'
        $val = $CurrentForm->hasValue("notificar") ? $CurrentForm->getValue("notificar") : $CurrentForm->getValue("x_notificar");
        if (!$this->notificar->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->notificar->Visible = false; // Disable update for API request
            } else {
                $this->notificar->setFormValue($val);
            }
        }

        // Check field name 'asunto' first before field var 'x_asunto'
        $val = $CurrentForm->hasValue("asunto") ? $CurrentForm->getValue("asunto") : $CurrentForm->getValue("x_asunto");
        if (!$this->asunto->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->asunto->Visible = false; // Disable update for API request
            } else {
                $this->asunto->setFormValue($val);
            }
        }

        // Check field name 'notificacion' first before field var 'x_notificacion'
        $val = $CurrentForm->hasValue("notificacion") ? $CurrentForm->getValue("notificacion") : $CurrentForm->getValue("x_notificacion");
        if (!$this->notificacion->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->notificacion->Visible = false; // Disable update for API request
            } else {
                $this->notificacion->setFormValue($val);
            }
        }

        // Check field name 'notificados' first before field var 'x_notificados'
        $val = $CurrentForm->hasValue("notificados") ? $CurrentForm->getValue("notificados") : $CurrentForm->getValue("x_notificados");
        if (!$this->notificados->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->notificados->Visible = false; // Disable update for API request
            } else {
                $this->notificados->setFormValue($val);
            }
        }

        // Check field name 'notificados_efectivos' first before field var 'x_notificados_efectivos'
        $val = $CurrentForm->hasValue("notificados_efectivos") ? $CurrentForm->getValue("notificados_efectivos") : $CurrentForm->getValue("x_notificados_efectivos");
        if (!$this->notificados_efectivos->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->notificados_efectivos->Visible = false; // Disable update for API request
            } else {
                $this->notificados_efectivos->setFormValue($val);
            }
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

        // Check field name 'enviado' first before field var 'x_enviado'
        $val = $CurrentForm->hasValue("enviado") ? $CurrentForm->getValue("enviado") : $CurrentForm->getValue("x_enviado");
        if (!$this->enviado->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->enviado->Visible = false; // Disable update for API request
            } else {
                $this->enviado->setFormValue($val);
            }
        }

        // Check field name 'Nnotificaciones' first before field var 'x_Nnotificaciones'
        $val = $CurrentForm->hasValue("Nnotificaciones") ? $CurrentForm->getValue("Nnotificaciones") : $CurrentForm->getValue("x_Nnotificaciones");
        if (!$this->Nnotificaciones->IsDetailKey) {
            $this->Nnotificaciones->setFormValue($val);
        }
        $this->getUploadFiles(); // Get upload files
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->Nnotificaciones->CurrentValue = $this->Nnotificaciones->FormValue;
        $this->notificar->CurrentValue = $this->notificar->FormValue;
        $this->asunto->CurrentValue = $this->asunto->FormValue;
        $this->notificacion->CurrentValue = $this->notificacion->FormValue;
        $this->notificados->CurrentValue = $this->notificados->FormValue;
        $this->notificados_efectivos->CurrentValue = $this->notificados_efectivos->FormValue;
        $this->_username->CurrentValue = $this->_username->FormValue;
        $this->fecha->CurrentValue = $this->fecha->FormValue;
        $this->fecha->CurrentValue = UnFormatDateTime($this->fecha->CurrentValue, 7);
        $this->enviado->CurrentValue = $this->enviado->FormValue;
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
        $this->Nnotificaciones->setDbValue($row['Nnotificaciones']);
        $this->tipo->setDbValue($row['tipo']);
        $this->notificar->setDbValue($row['notificar']);
        $this->asunto->setDbValue($row['asunto']);
        $this->notificacion->setDbValue($row['notificacion']);
        $this->notificados->setDbValue($row['notificados']);
        $this->notificados_efectivos->setDbValue($row['notificados_efectivos']);
        $this->_username->setDbValue($row['username']);
        $this->fecha->setDbValue($row['fecha']);
        $this->enviado->setDbValue($row['enviado']);
        $this->adjunto->Upload->DbValue = $row['adjunto'];
        $this->adjunto->setDbValue($this->adjunto->Upload->DbValue);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['Nnotificaciones'] = null;
        $row['tipo'] = null;
        $row['notificar'] = null;
        $row['asunto'] = null;
        $row['notificacion'] = null;
        $row['notificados'] = null;
        $row['notificados_efectivos'] = null;
        $row['username'] = null;
        $row['fecha'] = null;
        $row['enviado'] = null;
        $row['adjunto'] = null;
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

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // Nnotificaciones

        // tipo

        // notificar

        // asunto

        // notificacion

        // notificados

        // notificados_efectivos

        // username

        // fecha

        // enviado

        // adjunto
        if ($this->RowType == ROWTYPE_VIEW) {
            // Nnotificaciones
            $this->Nnotificaciones->ViewValue = $this->Nnotificaciones->CurrentValue;
            $this->Nnotificaciones->ViewCustomAttributes = "";

            // tipo
            $curVal = trim(strval($this->tipo->CurrentValue));
            if ($curVal != "") {
                $this->tipo->ViewValue = $this->tipo->lookupCacheOption($curVal);
                if ($this->tipo->ViewValue === null) { // Lookup from database
                    $filterWrk = "`valor1`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $lookupFilter = function() {
                        return "`codigo` = '015'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    $sqlWrk = $this->tipo->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->tipo->Lookup->renderViewRow($rswrk[0]);
                        $this->tipo->ViewValue = $this->tipo->displayValue($arwrk);
                    } else {
                        $this->tipo->ViewValue = $this->tipo->CurrentValue;
                    }
                }
            } else {
                $this->tipo->ViewValue = null;
            }
            $this->tipo->ViewCustomAttributes = "";

            // notificar
            $curVal = trim(strval($this->notificar->CurrentValue));
            if ($curVal != "") {
                $this->notificar->ViewValue = $this->notificar->lookupCacheOption($curVal);
                if ($this->notificar->ViewValue === null) { // Lookup from database
                    $filterWrk = "`valor1`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $lookupFilter = function() {
                        return "`codigo` = '016'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    $sqlWrk = $this->notificar->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->notificar->Lookup->renderViewRow($rswrk[0]);
                        $this->notificar->ViewValue = $this->notificar->displayValue($arwrk);
                    } else {
                        $this->notificar->ViewValue = $this->notificar->CurrentValue;
                    }
                }
            } else {
                $this->notificar->ViewValue = null;
            }
            $this->notificar->ViewCustomAttributes = "";

            // asunto
            $this->asunto->ViewValue = $this->asunto->CurrentValue;
            $this->asunto->ViewCustomAttributes = "";

            // notificacion
            $this->notificacion->ViewValue = $this->notificacion->CurrentValue;
            $this->notificacion->ViewCustomAttributes = "";

            // notificados
            $this->notificados->ViewValue = $this->notificados->CurrentValue;
            $this->notificados->ViewCustomAttributes = "";

            // notificados_efectivos
            $this->notificados_efectivos->ViewValue = $this->notificados_efectivos->CurrentValue;
            $this->notificados_efectivos->ViewCustomAttributes = "";

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

            // fecha
            $this->fecha->ViewValue = $this->fecha->CurrentValue;
            $this->fecha->ViewValue = FormatDateTime($this->fecha->ViewValue, 7);
            $this->fecha->ViewCustomAttributes = "";

            // enviado
            if (strval($this->enviado->CurrentValue) != "") {
                $this->enviado->ViewValue = $this->enviado->optionCaption($this->enviado->CurrentValue);
            } else {
                $this->enviado->ViewValue = null;
            }
            $this->enviado->ViewCustomAttributes = "";

            // adjunto
            if (!EmptyValue($this->adjunto->Upload->DbValue)) {
                $this->adjunto->ImageWidth = 120;
                $this->adjunto->ImageHeight = 120;
                $this->adjunto->ImageAlt = $this->adjunto->alt();
                $this->adjunto->ViewValue = $this->adjunto->Upload->DbValue;
            } else {
                $this->adjunto->ViewValue = "";
            }
            $this->adjunto->ViewCustomAttributes = "";

            // notificar
            $this->notificar->LinkCustomAttributes = "";
            $this->notificar->HrefValue = "";
            $this->notificar->TooltipValue = "";

            // asunto
            $this->asunto->LinkCustomAttributes = "";
            $this->asunto->HrefValue = "";
            $this->asunto->TooltipValue = "";

            // notificacion
            $this->notificacion->LinkCustomAttributes = "";
            $this->notificacion->HrefValue = "";
            $this->notificacion->TooltipValue = "";

            // notificados
            $this->notificados->LinkCustomAttributes = "";
            $this->notificados->HrefValue = "";
            $this->notificados->TooltipValue = "";

            // notificados_efectivos
            $this->notificados_efectivos->LinkCustomAttributes = "";
            $this->notificados_efectivos->HrefValue = "";
            $this->notificados_efectivos->TooltipValue = "";

            // username
            $this->_username->LinkCustomAttributes = "";
            $this->_username->HrefValue = "";
            $this->_username->TooltipValue = "";

            // fecha
            $this->fecha->LinkCustomAttributes = "";
            $this->fecha->HrefValue = "";
            $this->fecha->TooltipValue = "";

            // enviado
            $this->enviado->LinkCustomAttributes = "";
            $this->enviado->HrefValue = "";
            $this->enviado->TooltipValue = "";

            // adjunto
            $this->adjunto->LinkCustomAttributes = "";
            if (!EmptyValue($this->adjunto->Upload->DbValue)) {
                $this->adjunto->HrefValue = GetFileUploadUrl($this->adjunto, $this->adjunto->htmlDecode($this->adjunto->Upload->DbValue)); // Add prefix/suffix
                $this->adjunto->LinkAttrs["target"] = "_self"; // Add target
                if ($this->isExport()) {
                    $this->adjunto->HrefValue = FullUrl($this->adjunto->HrefValue, "href");
                }
            } else {
                $this->adjunto->HrefValue = "";
            }
            $this->adjunto->ExportHrefValue = $this->adjunto->UploadPath . $this->adjunto->Upload->DbValue;
            $this->adjunto->TooltipValue = "";
            if ($this->adjunto->UseColorbox) {
                if (EmptyValue($this->adjunto->TooltipValue)) {
                    $this->adjunto->LinkAttrs["title"] = $Language->phrase("ViewImageGallery");
                }
                $this->adjunto->LinkAttrs["data-rel"] = "notificaciones_x_adjunto";
                $this->adjunto->LinkAttrs->appendClass("ew-lightbox");
            }
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // notificar
            $this->notificar->EditAttrs["class"] = "form-control";
            $this->notificar->EditCustomAttributes = "";
            $curVal = trim(strval($this->notificar->CurrentValue));
            if ($curVal != "") {
                $this->notificar->EditValue = $this->notificar->lookupCacheOption($curVal);
                if ($this->notificar->EditValue === null) { // Lookup from database
                    $filterWrk = "`valor1`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $lookupFilter = function() {
                        return "`codigo` = '016'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    $sqlWrk = $this->notificar->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->notificar->Lookup->renderViewRow($rswrk[0]);
                        $this->notificar->EditValue = $this->notificar->displayValue($arwrk);
                    } else {
                        $this->notificar->EditValue = $this->notificar->CurrentValue;
                    }
                }
            } else {
                $this->notificar->EditValue = null;
            }
            $this->notificar->ViewCustomAttributes = "";

            // asunto
            $this->asunto->EditAttrs["class"] = "form-control";
            $this->asunto->EditCustomAttributes = "";
            if (!$this->asunto->Raw) {
                $this->asunto->CurrentValue = HtmlDecode($this->asunto->CurrentValue);
            }
            $this->asunto->EditValue = HtmlEncode($this->asunto->CurrentValue);
            $this->asunto->PlaceHolder = RemoveHtml($this->asunto->caption());

            // notificacion
            $this->notificacion->EditAttrs["class"] = "form-control";
            $this->notificacion->EditCustomAttributes = "";
            $this->notificacion->EditValue = HtmlEncode($this->notificacion->CurrentValue);
            $this->notificacion->PlaceHolder = RemoveHtml($this->notificacion->caption());

            // notificados
            $this->notificados->EditAttrs["class"] = "form-control";
            $this->notificados->EditCustomAttributes = "";
            $this->notificados->EditValue = HtmlEncode($this->notificados->CurrentValue);
            $this->notificados->PlaceHolder = RemoveHtml($this->notificados->caption());

            // notificados_efectivos
            $this->notificados_efectivos->EditAttrs["class"] = "form-control";
            $this->notificados_efectivos->EditCustomAttributes = "";
            $this->notificados_efectivos->EditValue = HtmlEncode($this->notificados_efectivos->CurrentValue);
            $this->notificados_efectivos->PlaceHolder = RemoveHtml($this->notificados_efectivos->caption());

            // username
            $this->_username->EditAttrs["class"] = "form-control";
            $this->_username->EditCustomAttributes = "";
            $this->_username->EditValue = $this->_username->CurrentValue;
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
                        $this->_username->EditValue = $this->_username->CurrentValue;
                    }
                }
            } else {
                $this->_username->EditValue = null;
            }
            $this->_username->ViewCustomAttributes = "";

            // fecha
            $this->fecha->EditAttrs["class"] = "form-control";
            $this->fecha->EditCustomAttributes = "";
            $this->fecha->EditValue = $this->fecha->CurrentValue;
            $this->fecha->EditValue = FormatDateTime($this->fecha->EditValue, 7);
            $this->fecha->ViewCustomAttributes = "";

            // enviado
            $this->enviado->EditAttrs["class"] = "form-control";
            $this->enviado->EditCustomAttributes = "";
            if (strval($this->enviado->CurrentValue) != "") {
                $this->enviado->EditValue = $this->enviado->optionCaption($this->enviado->CurrentValue);
            } else {
                $this->enviado->EditValue = null;
            }
            $this->enviado->ViewCustomAttributes = "";

            // adjunto
            $this->adjunto->EditAttrs["class"] = "form-control";
            $this->adjunto->EditCustomAttributes = "";
            if (!EmptyValue($this->adjunto->Upload->DbValue)) {
                $this->adjunto->ImageWidth = 120;
                $this->adjunto->ImageHeight = 120;
                $this->adjunto->ImageAlt = $this->adjunto->alt();
                $this->adjunto->EditValue = $this->adjunto->Upload->DbValue;
            } else {
                $this->adjunto->EditValue = "";
            }
            $this->adjunto->ViewCustomAttributes = "";

            // Edit refer script

            // notificar
            $this->notificar->LinkCustomAttributes = "";
            $this->notificar->HrefValue = "";
            $this->notificar->TooltipValue = "";

            // asunto
            $this->asunto->LinkCustomAttributes = "";
            $this->asunto->HrefValue = "";

            // notificacion
            $this->notificacion->LinkCustomAttributes = "";
            $this->notificacion->HrefValue = "";

            // notificados
            $this->notificados->LinkCustomAttributes = "";
            $this->notificados->HrefValue = "";

            // notificados_efectivos
            $this->notificados_efectivos->LinkCustomAttributes = "";
            $this->notificados_efectivos->HrefValue = "";

            // username
            $this->_username->LinkCustomAttributes = "";
            $this->_username->HrefValue = "";
            $this->_username->TooltipValue = "";

            // fecha
            $this->fecha->LinkCustomAttributes = "";
            $this->fecha->HrefValue = "";
            $this->fecha->TooltipValue = "";

            // enviado
            $this->enviado->LinkCustomAttributes = "";
            $this->enviado->HrefValue = "";
            $this->enviado->TooltipValue = "";

            // adjunto
            $this->adjunto->LinkCustomAttributes = "";
            if (!EmptyValue($this->adjunto->Upload->DbValue)) {
                $this->adjunto->HrefValue = GetFileUploadUrl($this->adjunto, $this->adjunto->htmlDecode($this->adjunto->Upload->DbValue)); // Add prefix/suffix
                $this->adjunto->LinkAttrs["target"] = "_self"; // Add target
                if ($this->isExport()) {
                    $this->adjunto->HrefValue = FullUrl($this->adjunto->HrefValue, "href");
                }
            } else {
                $this->adjunto->HrefValue = "";
            }
            $this->adjunto->ExportHrefValue = $this->adjunto->UploadPath . $this->adjunto->Upload->DbValue;
            $this->adjunto->TooltipValue = "";
            if ($this->adjunto->UseColorbox) {
                if (EmptyValue($this->adjunto->TooltipValue)) {
                    $this->adjunto->LinkAttrs["title"] = $Language->phrase("ViewImageGallery");
                }
                $this->adjunto->LinkAttrs["data-rel"] = "notificaciones_x_adjunto";
                $this->adjunto->LinkAttrs->appendClass("ew-lightbox");
            }
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
        if ($this->notificar->Required) {
            if (!$this->notificar->IsDetailKey && EmptyValue($this->notificar->FormValue)) {
                $this->notificar->addErrorMessage(str_replace("%s", $this->notificar->caption(), $this->notificar->RequiredErrorMessage));
            }
        }
        if ($this->asunto->Required) {
            if (!$this->asunto->IsDetailKey && EmptyValue($this->asunto->FormValue)) {
                $this->asunto->addErrorMessage(str_replace("%s", $this->asunto->caption(), $this->asunto->RequiredErrorMessage));
            }
        }
        if ($this->notificacion->Required) {
            if (!$this->notificacion->IsDetailKey && EmptyValue($this->notificacion->FormValue)) {
                $this->notificacion->addErrorMessage(str_replace("%s", $this->notificacion->caption(), $this->notificacion->RequiredErrorMessage));
            }
        }
        if ($this->notificados->Required) {
            if (!$this->notificados->IsDetailKey && EmptyValue($this->notificados->FormValue)) {
                $this->notificados->addErrorMessage(str_replace("%s", $this->notificados->caption(), $this->notificados->RequiredErrorMessage));
            }
        }
        if ($this->notificados_efectivos->Required) {
            if (!$this->notificados_efectivos->IsDetailKey && EmptyValue($this->notificados_efectivos->FormValue)) {
                $this->notificados_efectivos->addErrorMessage(str_replace("%s", $this->notificados_efectivos->caption(), $this->notificados_efectivos->RequiredErrorMessage));
            }
        }
        if ($this->_username->Required) {
            if (!$this->_username->IsDetailKey && EmptyValue($this->_username->FormValue)) {
                $this->_username->addErrorMessage(str_replace("%s", $this->_username->caption(), $this->_username->RequiredErrorMessage));
            }
        }
        if ($this->fecha->Required) {
            if (!$this->fecha->IsDetailKey && EmptyValue($this->fecha->FormValue)) {
                $this->fecha->addErrorMessage(str_replace("%s", $this->fecha->caption(), $this->fecha->RequiredErrorMessage));
            }
        }
        if ($this->enviado->Required) {
            if (!$this->enviado->IsDetailKey && EmptyValue($this->enviado->FormValue)) {
                $this->enviado->addErrorMessage(str_replace("%s", $this->enviado->caption(), $this->enviado->RequiredErrorMessage));
            }
        }
        if ($this->adjunto->Required) {
            if ($this->adjunto->Upload->FileName == "" && !$this->adjunto->Upload->KeepFile) {
                $this->adjunto->addErrorMessage(str_replace("%s", $this->adjunto->caption(), $this->adjunto->RequiredErrorMessage));
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

            // asunto
            $this->asunto->setDbValueDef($rsnew, $this->asunto->CurrentValue, null, $this->asunto->ReadOnly);

            // notificacion
            $this->notificacion->setDbValueDef($rsnew, $this->notificacion->CurrentValue, null, $this->notificacion->ReadOnly);

            // notificados
            $this->notificados->setDbValueDef($rsnew, $this->notificados->CurrentValue, null, $this->notificados->ReadOnly);

            // notificados_efectivos
            $this->notificados_efectivos->setDbValueDef($rsnew, $this->notificados_efectivos->CurrentValue, null, $this->notificados_efectivos->ReadOnly);

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

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("Home");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("NotificacionesList"), "", $this->TableVar, true);
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
                case "x_tipo":
                    $lookupFilter = function () {
                        return "`codigo` = '015'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    break;
                case "x_notificar":
                    $lookupFilter = function () {
                        return "`codigo` = '016'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    break;
                case "x__username":
                    break;
                case "x_enviado":
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
    public function pageLoad() {
    	//echo "Page Load";
    	header("Location: dashboard/emails_masivos/update_email.php?id=".$_GET["Nnotificaciones"]."&username=".CurrentUserName());
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
