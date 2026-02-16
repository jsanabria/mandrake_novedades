<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class ProveedorAddopt extends Proveedor
{
    use MessagesTrait;

    // Page ID
    public $PageID = "addopt";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'proveedor';

    // Page object name
    public $PageObjName = "ProveedorAddopt";

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

        // Table object (proveedor)
        if (!isset($GLOBALS["proveedor"]) || get_class($GLOBALS["proveedor"]) == PROJECT_NAMESPACE . "proveedor") {
            $GLOBALS["proveedor"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'proveedor');
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
                $doc = new $class(Container("proveedor"));
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
    public $IsModal = false;

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $CustomExportType, $ExportFileName, $UserProfile, $Language, $Security, $CurrentForm;

        // Create form object
        $CurrentForm = new HttpForm();
        $this->CurrentAction = Param("action"); // Set up current action
        $this->id->Visible = false;
        $this->ci_rif->setVisibility();
        $this->nombre->setVisibility();
        $this->ciudad->Visible = false;
        $this->direccion->setVisibility();
        $this->telefono1->setVisibility();
        $this->telefono2->Visible = false;
        $this->email1->Visible = false;
        $this->email2->Visible = false;
        $this->cuenta_auxiliar->Visible = false;
        $this->cuenta_gasto->Visible = false;
        $this->tipo_ret_iva->Visible = false;
        $this->tipo_ret_islr_concepto->Visible = false;
        $this->tipo_ret_islr->Visible = false;
        $this->tipo_ret_mun->Visible = false;
        $this->tipo_iva->Visible = false;
        $this->tipo_islr->Visible = false;
        $this->sustraendo->Visible = false;
        $this->tipo_impmun->Visible = false;
        $this->cta_bco->setVisibility();
        $this->activo->Visible = false;
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
        $this->setupLookupOptions($this->ciudad);
        $this->setupLookupOptions($this->cuenta_auxiliar);
        $this->setupLookupOptions($this->cuenta_gasto);
        $this->setupLookupOptions($this->tipo_ret_iva);
        $this->setupLookupOptions($this->tipo_ret_islr_concepto);
        $this->setupLookupOptions($this->tipo_ret_islr);
        $this->setupLookupOptions($this->tipo_ret_mun);
        $this->setupLookupOptions($this->tipo_iva);
        $this->setupLookupOptions($this->tipo_islr);
        $this->setupLookupOptions($this->sustraendo);
        $this->setupLookupOptions($this->tipo_impmun);

        // Set up Breadcrumb
        //$this->setupBreadcrumb(); // Not used
        $this->loadRowValues(); // Load default values

        // Render row
        $this->RowType = ROWTYPE_ADD; // Render add type
        $this->resetAttributes();
        $this->renderRow();

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
    }

    // Load default values
    protected function loadDefaultValues()
    {
        $this->id->CurrentValue = null;
        $this->id->OldValue = $this->id->CurrentValue;
        $this->ci_rif->CurrentValue = null;
        $this->ci_rif->OldValue = $this->ci_rif->CurrentValue;
        $this->nombre->CurrentValue = null;
        $this->nombre->OldValue = $this->nombre->CurrentValue;
        $this->ciudad->CurrentValue = null;
        $this->ciudad->OldValue = $this->ciudad->CurrentValue;
        $this->direccion->CurrentValue = null;
        $this->direccion->OldValue = $this->direccion->CurrentValue;
        $this->telefono1->CurrentValue = null;
        $this->telefono1->OldValue = $this->telefono1->CurrentValue;
        $this->telefono2->CurrentValue = null;
        $this->telefono2->OldValue = $this->telefono2->CurrentValue;
        $this->email1->CurrentValue = null;
        $this->email1->OldValue = $this->email1->CurrentValue;
        $this->email2->CurrentValue = null;
        $this->email2->OldValue = $this->email2->CurrentValue;
        $this->cuenta_auxiliar->CurrentValue = null;
        $this->cuenta_auxiliar->OldValue = $this->cuenta_auxiliar->CurrentValue;
        $this->cuenta_gasto->CurrentValue = null;
        $this->cuenta_gasto->OldValue = $this->cuenta_gasto->CurrentValue;
        $this->tipo_ret_iva->CurrentValue = null;
        $this->tipo_ret_iva->OldValue = $this->tipo_ret_iva->CurrentValue;
        $this->tipo_ret_islr_concepto->CurrentValue = null;
        $this->tipo_ret_islr_concepto->OldValue = $this->tipo_ret_islr_concepto->CurrentValue;
        $this->tipo_ret_islr->CurrentValue = null;
        $this->tipo_ret_islr->OldValue = $this->tipo_ret_islr->CurrentValue;
        $this->tipo_ret_mun->CurrentValue = null;
        $this->tipo_ret_mun->OldValue = $this->tipo_ret_mun->CurrentValue;
        $this->tipo_iva->CurrentValue = null;
        $this->tipo_iva->OldValue = $this->tipo_iva->CurrentValue;
        $this->tipo_islr->CurrentValue = null;
        $this->tipo_islr->OldValue = $this->tipo_islr->CurrentValue;
        $this->sustraendo->CurrentValue = null;
        $this->sustraendo->OldValue = $this->sustraendo->CurrentValue;
        $this->tipo_impmun->CurrentValue = null;
        $this->tipo_impmun->OldValue = $this->tipo_impmun->CurrentValue;
        $this->cta_bco->CurrentValue = null;
        $this->cta_bco->OldValue = $this->cta_bco->CurrentValue;
        $this->activo->CurrentValue = "S";
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;

        // Check field name 'ci_rif' first before field var 'x_ci_rif'
        $val = $CurrentForm->hasValue("ci_rif") ? $CurrentForm->getValue("ci_rif") : $CurrentForm->getValue("x_ci_rif");
        if (!$this->ci_rif->IsDetailKey) {
            $this->ci_rif->setFormValue(ConvertFromUtf8($val));
        }

        // Check field name 'nombre' first before field var 'x_nombre'
        $val = $CurrentForm->hasValue("nombre") ? $CurrentForm->getValue("nombre") : $CurrentForm->getValue("x_nombre");
        if (!$this->nombre->IsDetailKey) {
            $this->nombre->setFormValue(ConvertFromUtf8($val));
        }

        // Check field name 'direccion' first before field var 'x_direccion'
        $val = $CurrentForm->hasValue("direccion") ? $CurrentForm->getValue("direccion") : $CurrentForm->getValue("x_direccion");
        if (!$this->direccion->IsDetailKey) {
            $this->direccion->setFormValue(ConvertFromUtf8($val));
        }

        // Check field name 'telefono1' first before field var 'x_telefono1'
        $val = $CurrentForm->hasValue("telefono1") ? $CurrentForm->getValue("telefono1") : $CurrentForm->getValue("x_telefono1");
        if (!$this->telefono1->IsDetailKey) {
            $this->telefono1->setFormValue(ConvertFromUtf8($val));
        }

        // Check field name 'cta_bco' first before field var 'x_cta_bco'
        $val = $CurrentForm->hasValue("cta_bco") ? $CurrentForm->getValue("cta_bco") : $CurrentForm->getValue("x_cta_bco");
        if (!$this->cta_bco->IsDetailKey) {
            $this->cta_bco->setFormValue(ConvertFromUtf8($val));
        }

        // Check field name 'id' first before field var 'x_id'
        $val = $CurrentForm->hasValue("id") ? $CurrentForm->getValue("id") : $CurrentForm->getValue("x_id");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->ci_rif->CurrentValue = ConvertToUtf8($this->ci_rif->FormValue);
        $this->nombre->CurrentValue = ConvertToUtf8($this->nombre->FormValue);
        $this->direccion->CurrentValue = ConvertToUtf8($this->direccion->FormValue);
        $this->telefono1->CurrentValue = ConvertToUtf8($this->telefono1->FormValue);
        $this->cta_bco->CurrentValue = ConvertToUtf8($this->cta_bco->FormValue);
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
        $this->ci_rif->setDbValue($row['ci_rif']);
        $this->nombre->setDbValue($row['nombre']);
        $this->ciudad->setDbValue($row['ciudad']);
        $this->direccion->setDbValue($row['direccion']);
        $this->telefono1->setDbValue($row['telefono1']);
        $this->telefono2->setDbValue($row['telefono2']);
        $this->email1->setDbValue($row['email1']);
        $this->email2->setDbValue($row['email2']);
        $this->cuenta_auxiliar->setDbValue($row['cuenta_auxiliar']);
        $this->cuenta_gasto->setDbValue($row['cuenta_gasto']);
        $this->tipo_ret_iva->setDbValue($row['tipo_ret_iva']);
        $this->tipo_ret_islr_concepto->setDbValue($row['tipo_ret_islr_concepto']);
        $this->tipo_ret_islr->setDbValue($row['tipo_ret_islr']);
        $this->tipo_ret_mun->setDbValue($row['tipo_ret_mun']);
        $this->tipo_iva->setDbValue($row['tipo_iva']);
        $this->tipo_islr->setDbValue($row['tipo_islr']);
        $this->sustraendo->setDbValue($row['sustraendo']);
        $this->tipo_impmun->setDbValue($row['tipo_impmun']);
        $this->cta_bco->setDbValue($row['cta_bco']);
        $this->activo->setDbValue($row['activo']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $this->loadDefaultValues();
        $row = [];
        $row['id'] = $this->id->CurrentValue;
        $row['ci_rif'] = $this->ci_rif->CurrentValue;
        $row['nombre'] = $this->nombre->CurrentValue;
        $row['ciudad'] = $this->ciudad->CurrentValue;
        $row['direccion'] = $this->direccion->CurrentValue;
        $row['telefono1'] = $this->telefono1->CurrentValue;
        $row['telefono2'] = $this->telefono2->CurrentValue;
        $row['email1'] = $this->email1->CurrentValue;
        $row['email2'] = $this->email2->CurrentValue;
        $row['cuenta_auxiliar'] = $this->cuenta_auxiliar->CurrentValue;
        $row['cuenta_gasto'] = $this->cuenta_gasto->CurrentValue;
        $row['tipo_ret_iva'] = $this->tipo_ret_iva->CurrentValue;
        $row['tipo_ret_islr_concepto'] = $this->tipo_ret_islr_concepto->CurrentValue;
        $row['tipo_ret_islr'] = $this->tipo_ret_islr->CurrentValue;
        $row['tipo_ret_mun'] = $this->tipo_ret_mun->CurrentValue;
        $row['tipo_iva'] = $this->tipo_iva->CurrentValue;
        $row['tipo_islr'] = $this->tipo_islr->CurrentValue;
        $row['sustraendo'] = $this->sustraendo->CurrentValue;
        $row['tipo_impmun'] = $this->tipo_impmun->CurrentValue;
        $row['cta_bco'] = $this->cta_bco->CurrentValue;
        $row['activo'] = $this->activo->CurrentValue;
        return $row;
    }

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // id

        // ci_rif

        // nombre

        // ciudad

        // direccion

        // telefono1

        // telefono2

        // email1

        // email2

        // cuenta_auxiliar

        // cuenta_gasto

        // tipo_ret_iva

        // tipo_ret_islr_concepto

        // tipo_ret_islr

        // tipo_ret_mun

        // tipo_iva

        // tipo_islr

        // sustraendo

        // tipo_impmun

        // cta_bco

        // activo
        if ($this->RowType == ROWTYPE_VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;
            $this->id->ViewCustomAttributes = "";

            // ci_rif
            $this->ci_rif->ViewValue = $this->ci_rif->CurrentValue;
            $this->ci_rif->ViewCustomAttributes = "";

            // nombre
            $this->nombre->ViewValue = $this->nombre->CurrentValue;
            $this->nombre->ViewCustomAttributes = "";

            // ciudad
            $this->ciudad->ViewValue = $this->ciudad->CurrentValue;
            $curVal = trim(strval($this->ciudad->CurrentValue));
            if ($curVal != "") {
                $this->ciudad->ViewValue = $this->ciudad->lookupCacheOption($curVal);
                if ($this->ciudad->ViewValue === null) { // Lookup from database
                    $filterWrk = "`campo_codigo`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $lookupFilter = function() {
                        return "`tabla` = 'CIUDAD'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    $sqlWrk = $this->ciudad->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->ciudad->Lookup->renderViewRow($rswrk[0]);
                        $this->ciudad->ViewValue = $this->ciudad->displayValue($arwrk);
                    } else {
                        $this->ciudad->ViewValue = $this->ciudad->CurrentValue;
                    }
                }
            } else {
                $this->ciudad->ViewValue = null;
            }
            $this->ciudad->ViewCustomAttributes = "";

            // direccion
            $this->direccion->ViewValue = $this->direccion->CurrentValue;
            $this->direccion->ViewCustomAttributes = "";

            // telefono1
            $this->telefono1->ViewValue = $this->telefono1->CurrentValue;
            $this->telefono1->ViewCustomAttributes = "";

            // telefono2
            $this->telefono2->ViewValue = $this->telefono2->CurrentValue;
            $this->telefono2->ViewCustomAttributes = "";

            // email1
            $this->email1->ViewValue = $this->email1->CurrentValue;
            $this->email1->ViewCustomAttributes = "";

            // email2
            $this->email2->ViewValue = $this->email2->CurrentValue;
            $this->email2->ViewCustomAttributes = "";

            // cuenta_auxiliar
            $curVal = trim(strval($this->cuenta_auxiliar->CurrentValue));
            if ($curVal != "") {
                $this->cuenta_auxiliar->ViewValue = $this->cuenta_auxiliar->lookupCacheOption($curVal);
                if ($this->cuenta_auxiliar->ViewValue === null) { // Lookup from database
                    $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $lookupFilter = function() {
                        return "codigo LIKE CONCAT((SELECT valor2 FROM parametro WHERE codigo = '018' and valor1 = 'Proveedores'), '%')";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    $sqlWrk = $this->cuenta_auxiliar->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->cuenta_auxiliar->Lookup->renderViewRow($rswrk[0]);
                        $this->cuenta_auxiliar->ViewValue = $this->cuenta_auxiliar->displayValue($arwrk);
                    } else {
                        $this->cuenta_auxiliar->ViewValue = $this->cuenta_auxiliar->CurrentValue;
                    }
                }
            } else {
                $this->cuenta_auxiliar->ViewValue = null;
            }
            $this->cuenta_auxiliar->ViewCustomAttributes = "";

            // cuenta_gasto
            $curVal = trim(strval($this->cuenta_gasto->CurrentValue));
            if ($curVal != "") {
                $this->cuenta_gasto->ViewValue = $this->cuenta_gasto->lookupCacheOption($curVal);
                if ($this->cuenta_gasto->ViewValue === null) { // Lookup from database
                    $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $lookupFilter = function() {
                        return "codigo LIKE CONCAT((SELECT valor4 FROM parametro WHERE codigo = '018' and valor1 = 'Proveedores'), '%') OR codigo LIKE CONCAT((SELECT valor3 FROM parametro WHERE codigo = '018' and valor1 = 'Proveedores'), '%')";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    $sqlWrk = $this->cuenta_gasto->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->cuenta_gasto->Lookup->renderViewRow($rswrk[0]);
                        $this->cuenta_gasto->ViewValue = $this->cuenta_gasto->displayValue($arwrk);
                    } else {
                        $this->cuenta_gasto->ViewValue = $this->cuenta_gasto->CurrentValue;
                    }
                }
            } else {
                $this->cuenta_gasto->ViewValue = null;
            }
            $this->cuenta_gasto->ViewCustomAttributes = "";

            // tipo_ret_iva
            $curVal = trim(strval($this->tipo_ret_iva->CurrentValue));
            if ($curVal != "") {
                $this->tipo_ret_iva->ViewValue = $this->tipo_ret_iva->lookupCacheOption($curVal);
                if ($this->tipo_ret_iva->ViewValue === null) { // Lookup from database
                    $filterWrk = "`campo_codigo`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $lookupFilter = function() {
                        return "`tabla` = 'TABLA_RET_IVA'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    $sqlWrk = $this->tipo_ret_iva->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->tipo_ret_iva->Lookup->renderViewRow($rswrk[0]);
                        $this->tipo_ret_iva->ViewValue = $this->tipo_ret_iva->displayValue($arwrk);
                    } else {
                        $this->tipo_ret_iva->ViewValue = $this->tipo_ret_iva->CurrentValue;
                    }
                }
            } else {
                $this->tipo_ret_iva->ViewValue = null;
            }
            $this->tipo_ret_iva->ViewCustomAttributes = "";

            // tipo_ret_islr_concepto
            $curVal = trim(strval($this->tipo_ret_islr_concepto->CurrentValue));
            if ($curVal != "") {
                $this->tipo_ret_islr_concepto->ViewValue = $this->tipo_ret_islr_concepto->lookupCacheOption($curVal);
                if ($this->tipo_ret_islr_concepto->ViewValue === null) { // Lookup from database
                    $filterWrk = "`campo_codigo`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $lookupFilter = function() {
                        return "`tabla` = 'TABLA_RETENCIONES'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    $sqlWrk = $this->tipo_ret_islr_concepto->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->tipo_ret_islr_concepto->Lookup->renderViewRow($rswrk[0]);
                        $this->tipo_ret_islr_concepto->ViewValue = $this->tipo_ret_islr_concepto->displayValue($arwrk);
                    } else {
                        $this->tipo_ret_islr_concepto->ViewValue = $this->tipo_ret_islr_concepto->CurrentValue;
                    }
                }
            } else {
                $this->tipo_ret_islr_concepto->ViewValue = null;
            }
            $this->tipo_ret_islr_concepto->ViewCustomAttributes = "";

            // tipo_ret_islr
            $curVal = trim(strval($this->tipo_ret_islr->CurrentValue));
            if ($curVal != "") {
                $this->tipo_ret_islr->ViewValue = $this->tipo_ret_islr->lookupCacheOption($curVal);
                if ($this->tipo_ret_islr->ViewValue === null) { // Lookup from database
                    $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->tipo_ret_islr->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->tipo_ret_islr->Lookup->renderViewRow($rswrk[0]);
                        $this->tipo_ret_islr->ViewValue = $this->tipo_ret_islr->displayValue($arwrk);
                    } else {
                        $this->tipo_ret_islr->ViewValue = $this->tipo_ret_islr->CurrentValue;
                    }
                }
            } else {
                $this->tipo_ret_islr->ViewValue = null;
            }
            $this->tipo_ret_islr->ViewCustomAttributes = "";

            // tipo_ret_mun
            $curVal = trim(strval($this->tipo_ret_mun->CurrentValue));
            if ($curVal != "") {
                $this->tipo_ret_mun->ViewValue = $this->tipo_ret_mun->lookupCacheOption($curVal);
                if ($this->tipo_ret_mun->ViewValue === null) { // Lookup from database
                    $filterWrk = "`campo_codigo`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $lookupFilter = function() {
                        return "`tabla` = 'TABLA_RET_MUN'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    $sqlWrk = $this->tipo_ret_mun->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->tipo_ret_mun->Lookup->renderViewRow($rswrk[0]);
                        $this->tipo_ret_mun->ViewValue = $this->tipo_ret_mun->displayValue($arwrk);
                    } else {
                        $this->tipo_ret_mun->ViewValue = $this->tipo_ret_mun->CurrentValue;
                    }
                }
            } else {
                $this->tipo_ret_mun->ViewValue = null;
            }
            $this->tipo_ret_mun->ViewCustomAttributes = "";

            // tipo_iva
            $curVal = trim(strval($this->tipo_iva->CurrentValue));
            if ($curVal != "") {
                $this->tipo_iva->ViewValue = $this->tipo_iva->lookupCacheOption($curVal);
                if ($this->tipo_iva->ViewValue === null) { // Lookup from database
                    $filterWrk = "`valor2`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $lookupFilter = function() {
                        return "`codigo` = '021'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    $sqlWrk = $this->tipo_iva->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->tipo_iva->Lookup->renderViewRow($rswrk[0]);
                        $this->tipo_iva->ViewValue = $this->tipo_iva->displayValue($arwrk);
                    } else {
                        $this->tipo_iva->ViewValue = $this->tipo_iva->CurrentValue;
                    }
                }
            } else {
                $this->tipo_iva->ViewValue = null;
            }
            $this->tipo_iva->ViewCustomAttributes = "";

            // tipo_islr
            $curVal = trim(strval($this->tipo_islr->CurrentValue));
            if ($curVal != "") {
                $this->tipo_islr->ViewValue = $this->tipo_islr->lookupCacheOption($curVal);
                if ($this->tipo_islr->ViewValue === null) { // Lookup from database
                    $filterWrk = "`valor2`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $lookupFilter = function() {
                        return "`codigo` = '020'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    $sqlWrk = $this->tipo_islr->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->tipo_islr->Lookup->renderViewRow($rswrk[0]);
                        $this->tipo_islr->ViewValue = $this->tipo_islr->displayValue($arwrk);
                    } else {
                        $this->tipo_islr->ViewValue = $this->tipo_islr->CurrentValue;
                    }
                }
            } else {
                $this->tipo_islr->ViewValue = null;
            }
            $this->tipo_islr->ViewCustomAttributes = "";

            // sustraendo
            $curVal = trim(strval($this->sustraendo->CurrentValue));
            if ($curVal != "") {
                $this->sustraendo->ViewValue = $this->sustraendo->lookupCacheOption($curVal);
                if ($this->sustraendo->ViewValue === null) { // Lookup from database
                    $filterWrk = "`valor4`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $lookupFilter = function() {
                        return "`codigo` = '020'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    $sqlWrk = $this->sustraendo->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->sustraendo->Lookup->renderViewRow($rswrk[0]);
                        $this->sustraendo->ViewValue = $this->sustraendo->displayValue($arwrk);
                    } else {
                        $this->sustraendo->ViewValue = $this->sustraendo->CurrentValue;
                    }
                }
            } else {
                $this->sustraendo->ViewValue = null;
            }
            $this->sustraendo->ViewCustomAttributes = "";

            // tipo_impmun
            $curVal = trim(strval($this->tipo_impmun->CurrentValue));
            if ($curVal != "") {
                $this->tipo_impmun->ViewValue = $this->tipo_impmun->lookupCacheOption($curVal);
                if ($this->tipo_impmun->ViewValue === null) { // Lookup from database
                    $filterWrk = "`valor2`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $lookupFilter = function() {
                        return "`codigo` = '029'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    $sqlWrk = $this->tipo_impmun->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->tipo_impmun->Lookup->renderViewRow($rswrk[0]);
                        $this->tipo_impmun->ViewValue = $this->tipo_impmun->displayValue($arwrk);
                    } else {
                        $this->tipo_impmun->ViewValue = $this->tipo_impmun->CurrentValue;
                    }
                }
            } else {
                $this->tipo_impmun->ViewValue = null;
            }
            $this->tipo_impmun->ViewCustomAttributes = "";

            // cta_bco
            $this->cta_bco->ViewValue = $this->cta_bco->CurrentValue;
            $this->cta_bco->ViewCustomAttributes = "";

            // activo
            if (strval($this->activo->CurrentValue) != "") {
                $this->activo->ViewValue = $this->activo->optionCaption($this->activo->CurrentValue);
            } else {
                $this->activo->ViewValue = null;
            }
            $this->activo->ViewCustomAttributes = "";

            // ci_rif
            $this->ci_rif->LinkCustomAttributes = "";
            $this->ci_rif->HrefValue = "";
            $this->ci_rif->TooltipValue = "";

            // nombre
            $this->nombre->LinkCustomAttributes = "";
            $this->nombre->HrefValue = "";
            $this->nombre->TooltipValue = "";

            // direccion
            $this->direccion->LinkCustomAttributes = "";
            $this->direccion->HrefValue = "";
            $this->direccion->TooltipValue = "";

            // telefono1
            $this->telefono1->LinkCustomAttributes = "";
            $this->telefono1->HrefValue = "";
            $this->telefono1->TooltipValue = "";

            // cta_bco
            $this->cta_bco->LinkCustomAttributes = "";
            $this->cta_bco->HrefValue = "";
            $this->cta_bco->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // ci_rif
            $this->ci_rif->EditAttrs["class"] = "form-control";
            $this->ci_rif->EditCustomAttributes = "";
            if (!$this->ci_rif->Raw) {
                $this->ci_rif->CurrentValue = HtmlDecode($this->ci_rif->CurrentValue);
            }
            $this->ci_rif->EditValue = HtmlEncode($this->ci_rif->CurrentValue);
            $this->ci_rif->PlaceHolder = RemoveHtml($this->ci_rif->caption());

            // nombre
            $this->nombre->EditAttrs["class"] = "form-control";
            $this->nombre->EditCustomAttributes = "";
            if (!$this->nombre->Raw) {
                $this->nombre->CurrentValue = HtmlDecode($this->nombre->CurrentValue);
            }
            $this->nombre->EditValue = HtmlEncode($this->nombre->CurrentValue);
            $this->nombre->PlaceHolder = RemoveHtml($this->nombre->caption());

            // direccion
            $this->direccion->EditAttrs["class"] = "form-control";
            $this->direccion->EditCustomAttributes = "";
            $this->direccion->EditValue = HtmlEncode($this->direccion->CurrentValue);
            $this->direccion->PlaceHolder = RemoveHtml($this->direccion->caption());

            // telefono1
            $this->telefono1->EditAttrs["class"] = "form-control";
            $this->telefono1->EditCustomAttributes = "";
            if (!$this->telefono1->Raw) {
                $this->telefono1->CurrentValue = HtmlDecode($this->telefono1->CurrentValue);
            }
            $this->telefono1->EditValue = HtmlEncode($this->telefono1->CurrentValue);
            $this->telefono1->PlaceHolder = RemoveHtml($this->telefono1->caption());

            // cta_bco
            $this->cta_bco->EditAttrs["class"] = "form-control";
            $this->cta_bco->EditCustomAttributes = "";
            if (!$this->cta_bco->Raw) {
                $this->cta_bco->CurrentValue = HtmlDecode($this->cta_bco->CurrentValue);
            }
            $this->cta_bco->EditValue = HtmlEncode($this->cta_bco->CurrentValue);
            $this->cta_bco->PlaceHolder = RemoveHtml($this->cta_bco->caption());

            // Add refer script

            // ci_rif
            $this->ci_rif->LinkCustomAttributes = "";
            $this->ci_rif->HrefValue = "";

            // nombre
            $this->nombre->LinkCustomAttributes = "";
            $this->nombre->HrefValue = "";

            // direccion
            $this->direccion->LinkCustomAttributes = "";
            $this->direccion->HrefValue = "";

            // telefono1
            $this->telefono1->LinkCustomAttributes = "";
            $this->telefono1->HrefValue = "";

            // cta_bco
            $this->cta_bco->LinkCustomAttributes = "";
            $this->cta_bco->HrefValue = "";
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
        if ($this->ci_rif->Required) {
            if (!$this->ci_rif->IsDetailKey && EmptyValue($this->ci_rif->FormValue)) {
                $this->ci_rif->addErrorMessage(str_replace("%s", $this->ci_rif->caption(), $this->ci_rif->RequiredErrorMessage));
            }
        }
        if ($this->nombre->Required) {
            if (!$this->nombre->IsDetailKey && EmptyValue($this->nombre->FormValue)) {
                $this->nombre->addErrorMessage(str_replace("%s", $this->nombre->caption(), $this->nombre->RequiredErrorMessage));
            }
        }
        if ($this->direccion->Required) {
            if (!$this->direccion->IsDetailKey && EmptyValue($this->direccion->FormValue)) {
                $this->direccion->addErrorMessage(str_replace("%s", $this->direccion->caption(), $this->direccion->RequiredErrorMessage));
            }
        }
        if ($this->telefono1->Required) {
            if (!$this->telefono1->IsDetailKey && EmptyValue($this->telefono1->FormValue)) {
                $this->telefono1->addErrorMessage(str_replace("%s", $this->telefono1->caption(), $this->telefono1->RequiredErrorMessage));
            }
        }
        if ($this->cta_bco->Required) {
            if (!$this->cta_bco->IsDetailKey && EmptyValue($this->cta_bco->FormValue)) {
                $this->cta_bco->addErrorMessage(str_replace("%s", $this->cta_bco->caption(), $this->cta_bco->RequiredErrorMessage));
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

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("Home");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("ProveedorList"), "", $this->TableVar, true);
        $pageId = "addopt";
        $Breadcrumb->add("addopt", $pageId, $url);
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
                case "x_ciudad":
                    $lookupFilter = function () {
                        return "`tabla` = 'CIUDAD'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    break;
                case "x_cuenta_auxiliar":
                    $lookupFilter = function () {
                        return "codigo LIKE CONCAT((SELECT valor2 FROM parametro WHERE codigo = '018' and valor1 = 'Proveedores'), '%')";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    break;
                case "x_cuenta_gasto":
                    $lookupFilter = function () {
                        return "codigo LIKE CONCAT((SELECT valor4 FROM parametro WHERE codigo = '018' and valor1 = 'Proveedores'), '%') OR codigo LIKE CONCAT((SELECT valor3 FROM parametro WHERE codigo = '018' and valor1 = 'Proveedores'), '%')";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    break;
                case "x_tipo_ret_iva":
                    $lookupFilter = function () {
                        return "`tabla` = 'TABLA_RET_IVA'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    break;
                case "x_tipo_ret_islr_concepto":
                    $lookupFilter = function () {
                        return "`tabla` = 'TABLA_RETENCIONES'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    break;
                case "x_tipo_ret_islr":
                    break;
                case "x_tipo_ret_mun":
                    $lookupFilter = function () {
                        return "`tabla` = 'TABLA_RET_MUN'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    break;
                case "x_tipo_iva":
                    $lookupFilter = function () {
                        return "`codigo` = '021'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    break;
                case "x_tipo_islr":
                    $lookupFilter = function () {
                        return "`codigo` = '020'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    break;
                case "x_sustraendo":
                    $lookupFilter = function () {
                        return "`codigo` = '020'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    break;
                case "x_tipo_impmun":
                    $lookupFilter = function () {
                        return "`codigo` = '029'";
                    };
                    $lookupFilter = $lookupFilter->bindTo($this);
                    break;
                case "x_activo":
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
    }

    // Page Data Rendered event
    public function pageDataRendered(&$footer)
    {
        // Example:
        //$footer = "your footer";
    }
}
