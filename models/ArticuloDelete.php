<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class ArticuloDelete extends Articulo
{
    use MessagesTrait;

    // Page ID
    public $PageID = "delete";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'articulo';

    // Page object name
    public $PageObjName = "ArticuloDelete";

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

        // Table object (articulo)
        if (!isset($GLOBALS["articulo"]) || get_class($GLOBALS["articulo"]) == PROJECT_NAMESPACE . "articulo") {
            $GLOBALS["articulo"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

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
    public $DbMasterFilter = "";
    public $DbDetailFilter = "";
    public $StartRecord;
    public $TotalRecords = 0;
    public $RecordCount;
    public $RecKeys = [];
    public $StartRowCount = 1;
    public $RowCount = 0;

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $CustomExportType, $ExportFileName, $UserProfile, $Language, $Security, $CurrentForm;
        $this->CurrentAction = Param("action"); // Set up current action
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

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Load key parameters
        $this->RecKeys = $this->getRecordKeys(); // Load record keys
        $filter = $this->getFilterFromRecordKeys();
        if ($filter == "") {
            $this->terminate("ArticuloList"); // Prevent SQL injection, return to list
            return;
        }

        // Set up filter (WHERE Clause)
        $this->CurrentFilter = $filter;

        // Get action
        if (IsApi()) {
            $this->CurrentAction = "delete"; // Delete record directly
        } elseif (Post("action") !== null) {
            $this->CurrentAction = Post("action");
        } elseif (Get("action") == "1") {
            $this->CurrentAction = "delete"; // Delete record directly
        } else {
            $this->CurrentAction = "show"; // Display record
        }
        if ($this->isDelete()) {
            $this->SendEmail = true; // Send email on delete success
            if ($this->deleteRows()) { // Delete rows
                if ($this->getSuccessMessage() == "") {
                    $this->setSuccessMessage($Language->phrase("DeleteSuccess")); // Set up success message
                }
                if (IsApi()) {
                    $this->terminate(true);
                    return;
                } else {
                    $this->terminate($this->getReturnUrl()); // Return to caller
                    return;
                }
            } else { // Delete failed
                if (IsApi()) {
                    $this->terminate();
                    return;
                }
                $this->CurrentAction = "show"; // Display record
            }
        }
        if ($this->isShow()) { // Load records for display
            if ($this->Recordset = $this->loadRecordset()) {
                $this->TotalRecords = $this->Recordset->recordCount(); // Get record count
            }
            if ($this->TotalRecords <= 0) { // No record found, exit
                if ($this->Recordset) {
                    $this->Recordset->close();
                }
                $this->terminate("ArticuloList"); // Return to list
                return;
            }
        }

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

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs

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
        }

        // Call Row Rendered event
        if ($this->RowType != ROWTYPE_AGGREGATEINIT) {
            $this->rowRendered();
        }
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
        $conn->beginTransaction();
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
        if ($deleteRows) {
            $conn->commit(); // Commit the changes
            if ($this->AuditTrailOnDelete) {
                $this->writeAuditTrailDummy($Language->phrase("BatchDeleteSuccess")); // Batch delete success
            }
        } else {
            $conn->rollback(); // Rollback changes
            if ($this->AuditTrailOnDelete) {
                $this->writeAuditTrailDummy($Language->phrase("BatchDeleteRollback")); // Batch delete rollback
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

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("Home");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("ArticuloList"), "", $this->TableVar, true);
        $pageId = "delete";
        $Breadcrumb->add("delete", $pageId, $url);
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
