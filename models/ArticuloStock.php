<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for articulo_stock
 */
class ArticuloStock extends DbTable
{
    protected $SqlFrom = "";
    protected $SqlSelect = null;
    protected $SqlSelectList = null;
    protected $SqlWhere = "";
    protected $SqlGroupBy = "";
    protected $SqlHaving = "";
    protected $SqlOrderBy = "";
    public $UseSessionForListSql = true;

    // Column CSS classes
    public $LeftColumnClass = "col-sm-2 col-form-label ew-label";
    public $RightColumnClass = "col-sm-10";
    public $OffsetColumnClass = "col-sm-10 offset-sm-2";
    public $TableLeftColumnClass = "w-col-2";

    // Export
    public $ExportDoc;

    // Fields
    public $codigo;
    public $nombre;
    public $cantidad_en_mano;
    public $codigo_ims;
    public $codigo_ims2;
    public $costo;
    public $precio_full;
    public $precio_venta;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'articulo_stock';
        $this->TableName = 'articulo_stock';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`articulo_stock`";
        $this->Dbid = 'DB';
        $this->ExportAll = true;
        $this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
        $this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
        $this->ExportPageSize = "a4"; // Page size (PDF only)
        $this->ExportExcelPageOrientation = \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_DEFAULT; // Page orientation (PhpSpreadsheet only)
        $this->ExportExcelPageSize = \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4; // Page size (PhpSpreadsheet only)
        $this->ExportWordPageOrientation = "portrait"; // Page orientation (PHPWord only)
        $this->ExportWordColumnWidth = null; // Cell width (PHPWord only)
        $this->DetailAdd = false; // Allow detail add
        $this->DetailEdit = false; // Allow detail edit
        $this->DetailView = false; // Allow detail view
        $this->ShowMultipleDetails = false; // Show multiple details
        $this->GridAddRowCount = 5;
        $this->AllowAddDeleteRow = true; // Allow add/delete row
        $this->UserIDAllowSecurity = Config("DEFAULT_USER_ID_ALLOW_SECURITY"); // Default User ID allowed permissions
        $this->BasicSearch = new BasicSearch($this->TableVar);

        // codigo
        $this->codigo = new DbField('articulo_stock', 'articulo_stock', 'x_codigo', 'codigo', '`codigo`', '`codigo`', 200, 50, -1, false, '`codigo`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->codigo->Sortable = true; // Allow sort
        $this->codigo->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->codigo->Param, "CustomMsg");
        $this->Fields['codigo'] = &$this->codigo;

        // nombre
        $this->nombre = new DbField('articulo_stock', 'articulo_stock', 'x_nombre', 'nombre', '`nombre`', '`nombre`', 200, 100, -1, false, '`nombre`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->nombre->Sortable = true; // Allow sort
        $this->nombre->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->nombre->Param, "CustomMsg");
        $this->Fields['nombre'] = &$this->nombre;

        // cantidad_en_mano
        $this->cantidad_en_mano = new DbField('articulo_stock', 'articulo_stock', 'x_cantidad_en_mano', 'cantidad_en_mano', '`cantidad_en_mano`', '`cantidad_en_mano`', 131, 13, -1, false, '`cantidad_en_mano`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->cantidad_en_mano->Nullable = false; // NOT NULL field
        $this->cantidad_en_mano->Sortable = true; // Allow sort
        $this->cantidad_en_mano->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->cantidad_en_mano->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->cantidad_en_mano->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->cantidad_en_mano->Param, "CustomMsg");
        $this->Fields['cantidad_en_mano'] = &$this->cantidad_en_mano;

        // codigo_ims
        $this->codigo_ims = new DbField('articulo_stock', 'articulo_stock', 'x_codigo_ims', 'codigo_ims', '`codigo_ims`', '`codigo_ims`', 200, 50, -1, false, '`codigo_ims`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->codigo_ims->Sortable = true; // Allow sort
        $this->codigo_ims->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->codigo_ims->Param, "CustomMsg");
        $this->Fields['codigo_ims'] = &$this->codigo_ims;

        // codigo_ims2
        $this->codigo_ims2 = new DbField('articulo_stock', 'articulo_stock', 'x_codigo_ims2', 'codigo_ims2', '`codigo_ims2`', '`codigo_ims2`', 200, 50, -1, false, '`codigo_ims2`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->codigo_ims2->Sortable = true; // Allow sort
        $this->codigo_ims2->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->codigo_ims2->Param, "CustomMsg");
        $this->Fields['codigo_ims2'] = &$this->codigo_ims2;

        // costo
        $this->costo = new DbField('articulo_stock', 'articulo_stock', 'x_costo', 'costo', '`costo`', '`costo`', 131, 13, -1, false, '`costo`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->costo->Nullable = false; // NOT NULL field
        $this->costo->Sortable = true; // Allow sort
        $this->costo->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->costo->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->costo->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->costo->Param, "CustomMsg");
        $this->Fields['costo'] = &$this->costo;

        // precio_full
        $this->precio_full = new DbField('articulo_stock', 'articulo_stock', 'x_precio_full', 'precio_full', '`precio_full`', '`precio_full`', 131, 13, -1, false, '`precio_full`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->precio_full->Nullable = false; // NOT NULL field
        $this->precio_full->Sortable = true; // Allow sort
        $this->precio_full->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->precio_full->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->precio_full->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->precio_full->Param, "CustomMsg");
        $this->Fields['precio_full'] = &$this->precio_full;

        // precio_venta
        $this->precio_venta = new DbField('articulo_stock', 'articulo_stock', 'x_precio_venta', 'precio_venta', '`precio_venta`', '`precio_venta`', 131, 13, -1, false, '`precio_venta`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->precio_venta->Nullable = false; // NOT NULL field
        $this->precio_venta->Sortable = true; // Allow sort
        $this->precio_venta->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->precio_venta->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->precio_venta->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->precio_venta->Param, "CustomMsg");
        $this->Fields['precio_venta'] = &$this->precio_venta;
    }

    // Field Visibility
    public function getFieldVisibility($fldParm)
    {
        global $Security;
        return $this->$fldParm->Visible; // Returns original value
    }

    // Set left column class (must be predefined col-*-* classes of Bootstrap grid system)
    public function setLeftColumnClass($class)
    {
        if (preg_match('/^col\-(\w+)\-(\d+)$/', $class, $match)) {
            $this->LeftColumnClass = $class . " col-form-label ew-label";
            $this->RightColumnClass = "col-" . $match[1] . "-" . strval(12 - (int)$match[2]);
            $this->OffsetColumnClass = $this->RightColumnClass . " " . str_replace("col-", "offset-", $class);
            $this->TableLeftColumnClass = preg_replace('/^col-\w+-(\d+)$/', "w-col-$1", $class); // Change to w-col-*
        }
    }

    // Single column sort
    public function updateSort(&$fld)
    {
        if ($this->CurrentOrder == $fld->Name) {
            $sortField = $fld->Expression;
            $lastSort = $fld->getSort();
            if (in_array($this->CurrentOrderType, ["ASC", "DESC", "NO"])) {
                $curSort = $this->CurrentOrderType;
            } else {
                $curSort = $lastSort;
            }
            $fld->setSort($curSort);
            $orderBy = in_array($curSort, ["ASC", "DESC"]) ? $sortField . " " . $curSort : "";
            $this->setSessionOrderBy($orderBy); // Save to Session
        } else {
            $fld->setSort("");
        }
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`articulo_stock`";
    }

    public function sqlFrom() // For backward compatibility
    {
        return $this->getSqlFrom();
    }

    public function setSqlFrom($v)
    {
        $this->SqlFrom = $v;
    }

    public function getSqlSelect() // Select
    {
        return $this->SqlSelect ?? $this->getQueryBuilder()->select("*");
    }

    public function sqlSelect() // For backward compatibility
    {
        return $this->getSqlSelect();
    }

    public function setSqlSelect($v)
    {
        $this->SqlSelect = $v;
    }

    public function getSqlWhere() // Where
    {
        $where = ($this->SqlWhere != "") ? $this->SqlWhere : "";
        $this->DefaultFilter = "";
        AddFilter($where, $this->DefaultFilter);
        return $where;
    }

    public function sqlWhere() // For backward compatibility
    {
        return $this->getSqlWhere();
    }

    public function setSqlWhere($v)
    {
        $this->SqlWhere = $v;
    }

    public function getSqlGroupBy() // Group By
    {
        return ($this->SqlGroupBy != "") ? $this->SqlGroupBy : "";
    }

    public function sqlGroupBy() // For backward compatibility
    {
        return $this->getSqlGroupBy();
    }

    public function setSqlGroupBy($v)
    {
        $this->SqlGroupBy = $v;
    }

    public function getSqlHaving() // Having
    {
        return ($this->SqlHaving != "") ? $this->SqlHaving : "";
    }

    public function sqlHaving() // For backward compatibility
    {
        return $this->getSqlHaving();
    }

    public function setSqlHaving($v)
    {
        $this->SqlHaving = $v;
    }

    public function getSqlOrderBy() // Order By
    {
        return ($this->SqlOrderBy != "") ? $this->SqlOrderBy : $this->DefaultSort;
    }

    public function sqlOrderBy() // For backward compatibility
    {
        return $this->getSqlOrderBy();
    }

    public function setSqlOrderBy($v)
    {
        $this->SqlOrderBy = $v;
    }

    // Apply User ID filters
    public function applyUserIDFilters($filter)
    {
        return $filter;
    }

    // Check if User ID security allows view all
    public function userIDAllow($id = "")
    {
        $allow = $this->UserIDAllowSecurity;
        switch ($id) {
            case "add":
            case "copy":
            case "gridadd":
            case "register":
            case "addopt":
                return (($allow & 1) == 1);
            case "edit":
            case "gridedit":
            case "update":
            case "changepassword":
            case "resetpassword":
                return (($allow & 4) == 4);
            case "delete":
                return (($allow & 2) == 2);
            case "view":
                return (($allow & 32) == 32);
            case "search":
                return (($allow & 64) == 64);
            default:
                return (($allow & 8) == 8);
        }
    }

    /**
     * Get record count
     *
     * @param string|QueryBuilder $sql SQL or QueryBuilder
     * @param mixed $c Connection
     * @return int
     */
    public function getRecordCount($sql, $c = null)
    {
        $cnt = -1;
        $rs = null;
        if ($sql instanceof \Doctrine\DBAL\Query\QueryBuilder) { // Query builder
            $sqlwrk = clone $sql;
            $sqlwrk = $sqlwrk->resetQueryPart("orderBy")->getSQL();
        } else {
            $sqlwrk = $sql;
        }
        $pattern = '/^SELECT\s([\s\S]+)\sFROM\s/i';
        // Skip Custom View / SubQuery / SELECT DISTINCT / ORDER BY
        if (
            ($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') &&
            preg_match($pattern, $sqlwrk) && !preg_match('/\(\s*(SELECT[^)]+)\)/i', $sqlwrk) &&
            !preg_match('/^\s*select\s+distinct\s+/i', $sqlwrk) && !preg_match('/\s+order\s+by\s+/i', $sqlwrk)
        ) {
            $sqlwrk = "SELECT COUNT(*) FROM " . preg_replace($pattern, "", $sqlwrk);
        } else {
            $sqlwrk = "SELECT COUNT(*) FROM (" . $sqlwrk . ") COUNT_TABLE";
        }
        $conn = $c ?? $this->getConnection();
        $rs = $conn->executeQuery($sqlwrk);
        $cnt = $rs->fetchColumn();
        if ($cnt !== false) {
            return (int)$cnt;
        }

        // Unable to get count by SELECT COUNT(*), execute the SQL to get record count directly
        return ExecuteRecordCount($sql, $conn);
    }

    // Get SQL
    public function getSql($where, $orderBy = "")
    {
        return $this->buildSelectSql(
            $this->getSqlSelect(),
            $this->getSqlFrom(),
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $where,
            $orderBy
        )->getSQL();
    }

    // Table SQL
    public function getCurrentSql()
    {
        $filter = $this->CurrentFilter;
        $filter = $this->applyUserIDFilters($filter);
        $sort = $this->getSessionOrderBy();
        return $this->getSql($filter, $sort);
    }

    /**
     * Table SQL with List page filter
     *
     * @return QueryBuilder
     */
    public function getListSql()
    {
        $filter = $this->UseSessionForListSql ? $this->getSessionWhere() : "";
        AddFilter($filter, $this->CurrentFilter);
        $filter = $this->applyUserIDFilters($filter);
        $this->recordsetSelecting($filter);
        $select = $this->getSqlSelect();
        $from = $this->getSqlFrom();
        $sort = $this->UseSessionForListSql ? $this->getSessionOrderBy() : "";
        $this->Sort = $sort;
        return $this->buildSelectSql(
            $select,
            $from,
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $filter,
            $sort
        );
    }

    // Get ORDER BY clause
    public function getOrderBy()
    {
        $orderBy = $this->getSqlOrderBy();
        $sort = $this->getSessionOrderBy();
        if ($orderBy != "" && $sort != "") {
            $orderBy .= ", " . $sort;
        } elseif ($sort != "") {
            $orderBy = $sort;
        }
        return $orderBy;
    }

    // Get record count based on filter (for detail record count in master table pages)
    public function loadRecordCount($filter)
    {
        $origFilter = $this->CurrentFilter;
        $this->CurrentFilter = $filter;
        $this->recordsetSelecting($this->CurrentFilter);
        $select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : $this->getQueryBuilder()->select("*");
        $groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
        $having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
        $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $this->CurrentFilter, "");
        $cnt = $this->getRecordCount($sql);
        $this->CurrentFilter = $origFilter;
        return $cnt;
    }

    // Get record count (for current List page)
    public function listRecordCount()
    {
        $filter = $this->getSessionWhere();
        AddFilter($filter, $this->CurrentFilter);
        $filter = $this->applyUserIDFilters($filter);
        $this->recordsetSelecting($filter);
        $select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : $this->getQueryBuilder()->select("*");
        $groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
        $having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
        $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
        $cnt = $this->getRecordCount($sql);
        return $cnt;
    }

    /**
     * INSERT statement
     *
     * @param mixed $rs
     * @return QueryBuilder
     */
    protected function insertSql(&$rs)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->insert($this->UpdateTable);
        foreach ($rs as $name => $value) {
            if (!isset($this->Fields[$name]) || $this->Fields[$name]->IsCustom) {
                continue;
            }
            $type = GetParameterType($this->Fields[$name], $value, $this->Dbid);
            $queryBuilder->setValue($this->Fields[$name]->Expression, $queryBuilder->createPositionalParameter($value, $type));
        }
        return $queryBuilder;
    }

    // Insert
    public function insert(&$rs)
    {
        $conn = $this->getConnection();
        $success = $this->insertSql($rs)->execute();
        if ($success) {
        }
        return $success;
    }

    /**
     * UPDATE statement
     *
     * @param array $rs Data to be updated
     * @param string|array $where WHERE clause
     * @param string $curfilter Filter
     * @return QueryBuilder
     */
    protected function updateSql(&$rs, $where = "", $curfilter = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->update($this->UpdateTable);
        foreach ($rs as $name => $value) {
            if (!isset($this->Fields[$name]) || $this->Fields[$name]->IsCustom || $this->Fields[$name]->IsAutoIncrement) {
                continue;
            }
            $type = GetParameterType($this->Fields[$name], $value, $this->Dbid);
            $queryBuilder->set($this->Fields[$name]->Expression, $queryBuilder->createPositionalParameter($value, $type));
        }
        $filter = ($curfilter) ? $this->CurrentFilter : "";
        if (is_array($where)) {
            $where = $this->arrayToFilter($where);
        }
        AddFilter($filter, $where);
        if ($filter != "") {
            $queryBuilder->where($filter);
        }
        return $queryBuilder;
    }

    // Update
    public function update(&$rs, $where = "", $rsold = null, $curfilter = true)
    {
        // If no field is updated, execute may return 0. Treat as success
        $success = $this->updateSql($rs, $where, $curfilter)->execute();
        $success = ($success > 0) ? $success : true;
        return $success;
    }

    /**
     * DELETE statement
     *
     * @param array $rs Key values
     * @param string|array $where WHERE clause
     * @param string $curfilter Filter
     * @return QueryBuilder
     */
    protected function deleteSql(&$rs, $where = "", $curfilter = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->delete($this->UpdateTable);
        if (is_array($where)) {
            $where = $this->arrayToFilter($where);
        }
        if ($rs) {
        }
        $filter = ($curfilter) ? $this->CurrentFilter : "";
        AddFilter($filter, $where);
        return $queryBuilder->where($filter != "" ? $filter : "0=1");
    }

    // Delete
    public function delete(&$rs, $where = "", $curfilter = false)
    {
        $success = true;
        if ($success) {
            $success = $this->deleteSql($rs, $where, $curfilter)->execute();
        }
        return $success;
    }

    // Load DbValue from recordset or array
    protected function loadDbValues($row)
    {
        if (!is_array($row)) {
            return;
        }
        $this->codigo->DbValue = $row['codigo'];
        $this->nombre->DbValue = $row['nombre'];
        $this->cantidad_en_mano->DbValue = $row['cantidad_en_mano'];
        $this->codigo_ims->DbValue = $row['codigo_ims'];
        $this->codigo_ims2->DbValue = $row['codigo_ims2'];
        $this->costo->DbValue = $row['costo'];
        $this->precio_full->DbValue = $row['precio_full'];
        $this->precio_venta->DbValue = $row['precio_venta'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        return implode(Config("COMPOSITE_KEY_SEPARATOR"), $keys);
    }

    // Set Key
    public function setKey($key, $current = false)
    {
        $this->OldKey = strval($key);
        $keys = explode(Config("COMPOSITE_KEY_SEPARATOR"), $this->OldKey);
        if (count($keys) == 0) {
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        return $keyFilter;
    }

    // Return page URL
    public function getReturnUrl()
    {
        $referUrl = ReferUrl();
        $referPageName = ReferPageName();
        $name = PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL");
        // Get referer URL automatically
        if ($referUrl != "" && $referPageName != CurrentPageName() && $referPageName != "login") { // Referer not same page or login page
            $_SESSION[$name] = $referUrl; // Save to Session
        }
        return $_SESSION[$name] ?? GetUrl("ArticuloStockList");
    }

    // Set return page URL
    public function setReturnUrl($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL")] = $v;
    }

    // Get modal caption
    public function getModalCaption($pageName)
    {
        global $Language;
        if ($pageName == "ArticuloStockView") {
            return $Language->phrase("View");
        } elseif ($pageName == "ArticuloStockEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "ArticuloStockAdd") {
            return $Language->phrase("Add");
        } else {
            return "";
        }
    }

    // API page name
    public function getApiPageName($action)
    {
        switch (strtolower($action)) {
            case Config("API_VIEW_ACTION"):
                return "ArticuloStockView";
            case Config("API_ADD_ACTION"):
                return "ArticuloStockAdd";
            case Config("API_EDIT_ACTION"):
                return "ArticuloStockEdit";
            case Config("API_DELETE_ACTION"):
                return "ArticuloStockDelete";
            case Config("API_LIST_ACTION"):
                return "ArticuloStockList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "ArticuloStockList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ArticuloStockView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("ArticuloStockView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "ArticuloStockAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "ArticuloStockAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("ArticuloStockEdit", $this->getUrlParm($parm));
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl(CurrentPageName(), $this->getUrlParm("action=edit"));
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        $url = $this->keyUrl("ArticuloStockAdd", $this->getUrlParm($parm));
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl(CurrentPageName(), $this->getUrlParm("action=copy"));
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl()
    {
        return $this->keyUrl("ArticuloStockDelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($parm != "") {
            $url .= "?" . $parm;
        }
        return $url;
    }

    // Render sort
    public function renderSort($fld)
    {
        $classId = $fld->TableVar . "_" . $fld->Param;
        $scriptId = str_replace("%id%", $classId, "tpc_%id%");
        $scriptStart = $this->UseCustomTemplate ? "<template id=\"" . $scriptId . "\">" : "";
        $scriptEnd = $this->UseCustomTemplate ? "</template>" : "";
        $jsSort = " class=\"ew-pointer\" onclick=\"ew.sort(event, '" . $this->sortUrl($fld) . "', 1);\"";
        if ($this->sortUrl($fld) == "") {
            $html = <<<NOSORTHTML
{$scriptStart}<div class="ew-table-header-caption">{$fld->caption()}</div>{$scriptEnd}
NOSORTHTML;
        } else {
            if ($fld->getSort() == "ASC") {
                $sortIcon = '<i class="fas fa-sort-up"></i>';
            } elseif ($fld->getSort() == "DESC") {
                $sortIcon = '<i class="fas fa-sort-down"></i>';
            } else {
                $sortIcon = '';
            }
            $html = <<<SORTHTML
{$scriptStart}<div{$jsSort}><div class="ew-table-header-btn"><span class="ew-table-header-caption">{$fld->caption()}</span><span class="ew-table-header-sort">{$sortIcon}</span></div></div>{$scriptEnd}
SORTHTML;
        }
        return $html;
    }

    // Sort URL
    public function sortUrl($fld)
    {
        if (
            $this->CurrentAction || $this->isExport() ||
            in_array($fld->Type, [128, 204, 205])
        ) { // Unsortable data type
                return "";
        } elseif ($fld->Sortable) {
            $urlParm = $this->getUrlParm("order=" . urlencode($fld->Name) . "&amp;ordertype=" . $fld->getNextSort());
            return $this->addMasterUrl(CurrentPageName() . "?" . $urlParm);
        } else {
            return "";
        }
    }

    // Get record keys from Post/Get/Session
    public function getRecordKeys()
    {
        $arKeys = [];
        $arKey = [];
        if (Param("key_m") !== null) {
            $arKeys = Param("key_m");
            $cnt = count($arKeys);
        } else {
            //return $arKeys; // Do not return yet, so the values will also be checked by the following code
        }
        // Check keys
        $ar = [];
        if (is_array($arKeys)) {
            foreach ($arKeys as $key) {
                $ar[] = $key;
            }
        }
        return $ar;
    }

    // Get filter from record keys
    public function getFilterFromRecordKeys($setCurrent = true)
    {
        $arKeys = $this->getRecordKeys();
        $keyFilter = "";
        foreach ($arKeys as $key) {
            if ($keyFilter != "") {
                $keyFilter .= " OR ";
            }
            $keyFilter .= "(" . $this->getRecordFilter() . ")";
        }
        return $keyFilter;
    }

    // Load recordset based on filter
    public function &loadRs($filter)
    {
        $sql = $this->getSql($filter); // Set up filter (WHERE Clause)
        $conn = $this->getConnection();
        $stmt = $conn->executeQuery($sql);
        return $stmt;
    }

    // Load row values from record
    public function loadListRowValues(&$rs)
    {
        if (is_array($rs)) {
            $row = $rs;
        } elseif ($rs && property_exists($rs, "fields")) { // Recordset
            $row = $rs->fields;
        } else {
            return;
        }
        $this->codigo->setDbValue($row['codigo']);
        $this->nombre->setDbValue($row['nombre']);
        $this->cantidad_en_mano->setDbValue($row['cantidad_en_mano']);
        $this->codigo_ims->setDbValue($row['codigo_ims']);
        $this->codigo_ims2->setDbValue($row['codigo_ims2']);
        $this->costo->setDbValue($row['costo']);
        $this->precio_full->setDbValue($row['precio_full']);
        $this->precio_venta->setDbValue($row['precio_venta']);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // codigo

        // nombre

        // cantidad_en_mano

        // codigo_ims

        // codigo_ims2

        // costo

        // precio_full

        // precio_venta

        // codigo
        $this->codigo->ViewValue = $this->codigo->CurrentValue;
        $this->codigo->ViewCustomAttributes = "";

        // nombre
        $this->nombre->ViewValue = $this->nombre->CurrentValue;
        $this->nombre->ViewCustomAttributes = "";

        // cantidad_en_mano
        $this->cantidad_en_mano->ViewValue = $this->cantidad_en_mano->CurrentValue;
        $this->cantidad_en_mano->ViewValue = FormatNumber($this->cantidad_en_mano->ViewValue, 2, -2, -2, -2);
        $this->cantidad_en_mano->ViewCustomAttributes = "";

        // codigo_ims
        $this->codigo_ims->ViewValue = $this->codigo_ims->CurrentValue;
        $this->codigo_ims->ViewCustomAttributes = "";

        // codigo_ims2
        $this->codigo_ims2->ViewValue = $this->codigo_ims2->CurrentValue;
        $this->codigo_ims2->ViewCustomAttributes = "";

        // costo
        $this->costo->ViewValue = $this->costo->CurrentValue;
        $this->costo->ViewValue = FormatNumber($this->costo->ViewValue, 2, -2, -2, -2);
        $this->costo->ViewCustomAttributes = "";

        // precio_full
        $this->precio_full->ViewValue = $this->precio_full->CurrentValue;
        $this->precio_full->ViewValue = FormatNumber($this->precio_full->ViewValue, 2, -2, -2, -2);
        $this->precio_full->ViewCustomAttributes = "";

        // precio_venta
        $this->precio_venta->ViewValue = $this->precio_venta->CurrentValue;
        $this->precio_venta->ViewValue = FormatNumber($this->precio_venta->ViewValue, 2, -2, -2, -2);
        $this->precio_venta->ViewCustomAttributes = "";

        // codigo
        $this->codigo->LinkCustomAttributes = "";
        $this->codigo->HrefValue = "";
        $this->codigo->TooltipValue = "";

        // nombre
        $this->nombre->LinkCustomAttributes = "";
        $this->nombre->HrefValue = "";
        $this->nombre->TooltipValue = "";

        // cantidad_en_mano
        $this->cantidad_en_mano->LinkCustomAttributes = "";
        $this->cantidad_en_mano->HrefValue = "";
        $this->cantidad_en_mano->TooltipValue = "";

        // codigo_ims
        $this->codigo_ims->LinkCustomAttributes = "";
        $this->codigo_ims->HrefValue = "";
        $this->codigo_ims->TooltipValue = "";

        // codigo_ims2
        $this->codigo_ims2->LinkCustomAttributes = "";
        $this->codigo_ims2->HrefValue = "";
        $this->codigo_ims2->TooltipValue = "";

        // costo
        $this->costo->LinkCustomAttributes = "";
        $this->costo->HrefValue = "";
        $this->costo->TooltipValue = "";

        // precio_full
        $this->precio_full->LinkCustomAttributes = "";
        $this->precio_full->HrefValue = "";
        $this->precio_full->TooltipValue = "";

        // precio_venta
        $this->precio_venta->LinkCustomAttributes = "";
        $this->precio_venta->HrefValue = "";
        $this->precio_venta->TooltipValue = "";

        // Call Row Rendered event
        $this->rowRendered();

        // Save data for Custom Template
        $this->Rows[] = $this->customTemplateFieldValues();
    }

    // Render edit row values
    public function renderEditRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // codigo
        $this->codigo->EditAttrs["class"] = "form-control";
        $this->codigo->EditCustomAttributes = "";
        if (!$this->codigo->Raw) {
            $this->codigo->CurrentValue = HtmlDecode($this->codigo->CurrentValue);
        }
        $this->codigo->EditValue = $this->codigo->CurrentValue;
        $this->codigo->PlaceHolder = RemoveHtml($this->codigo->caption());

        // nombre
        $this->nombre->EditAttrs["class"] = "form-control";
        $this->nombre->EditCustomAttributes = "";
        if (!$this->nombre->Raw) {
            $this->nombre->CurrentValue = HtmlDecode($this->nombre->CurrentValue);
        }
        $this->nombre->EditValue = $this->nombre->CurrentValue;
        $this->nombre->PlaceHolder = RemoveHtml($this->nombre->caption());

        // cantidad_en_mano
        $this->cantidad_en_mano->EditAttrs["class"] = "form-control";
        $this->cantidad_en_mano->EditCustomAttributes = "";
        $this->cantidad_en_mano->EditValue = $this->cantidad_en_mano->CurrentValue;
        $this->cantidad_en_mano->PlaceHolder = RemoveHtml($this->cantidad_en_mano->caption());
        if (strval($this->cantidad_en_mano->EditValue) != "" && is_numeric($this->cantidad_en_mano->EditValue)) {
            $this->cantidad_en_mano->EditValue = FormatNumber($this->cantidad_en_mano->EditValue, -2, -2, -2, -2);
        }

        // codigo_ims
        $this->codigo_ims->EditAttrs["class"] = "form-control";
        $this->codigo_ims->EditCustomAttributes = "";
        if (!$this->codigo_ims->Raw) {
            $this->codigo_ims->CurrentValue = HtmlDecode($this->codigo_ims->CurrentValue);
        }
        $this->codigo_ims->EditValue = $this->codigo_ims->CurrentValue;
        $this->codigo_ims->PlaceHolder = RemoveHtml($this->codigo_ims->caption());

        // codigo_ims2
        $this->codigo_ims2->EditAttrs["class"] = "form-control";
        $this->codigo_ims2->EditCustomAttributes = "";
        if (!$this->codigo_ims2->Raw) {
            $this->codigo_ims2->CurrentValue = HtmlDecode($this->codigo_ims2->CurrentValue);
        }
        $this->codigo_ims2->EditValue = $this->codigo_ims2->CurrentValue;
        $this->codigo_ims2->PlaceHolder = RemoveHtml($this->codigo_ims2->caption());

        // costo
        $this->costo->EditAttrs["class"] = "form-control";
        $this->costo->EditCustomAttributes = "";
        $this->costo->EditValue = $this->costo->CurrentValue;
        $this->costo->PlaceHolder = RemoveHtml($this->costo->caption());
        if (strval($this->costo->EditValue) != "" && is_numeric($this->costo->EditValue)) {
            $this->costo->EditValue = FormatNumber($this->costo->EditValue, -2, -2, -2, -2);
        }

        // precio_full
        $this->precio_full->EditAttrs["class"] = "form-control";
        $this->precio_full->EditCustomAttributes = "";
        $this->precio_full->EditValue = $this->precio_full->CurrentValue;
        $this->precio_full->PlaceHolder = RemoveHtml($this->precio_full->caption());
        if (strval($this->precio_full->EditValue) != "" && is_numeric($this->precio_full->EditValue)) {
            $this->precio_full->EditValue = FormatNumber($this->precio_full->EditValue, -2, -2, -2, -2);
        }

        // precio_venta
        $this->precio_venta->EditAttrs["class"] = "form-control";
        $this->precio_venta->EditCustomAttributes = "";
        $this->precio_venta->EditValue = $this->precio_venta->CurrentValue;
        $this->precio_venta->PlaceHolder = RemoveHtml($this->precio_venta->caption());
        if (strval($this->precio_venta->EditValue) != "" && is_numeric($this->precio_venta->EditValue)) {
            $this->precio_venta->EditValue = FormatNumber($this->precio_venta->EditValue, -2, -2, -2, -2);
        }

        // Call Row Rendered event
        $this->rowRendered();
    }

    // Aggregate list row values
    public function aggregateListRowValues()
    {
    }

    // Aggregate list row (for rendering)
    public function aggregateListRow()
    {
        // Call Row Rendered event
        $this->rowRendered();
    }

    // Export data in HTML/CSV/Word/Excel/Email/PDF format
    public function exportDocument($doc, $recordset, $startRec = 1, $stopRec = 1, $exportPageType = "")
    {
        if (!$recordset || !$doc) {
            return;
        }
        if (!$doc->ExportCustom) {
            // Write header
            $doc->exportTableHeader();
            if ($doc->Horizontal) { // Horizontal format, write header
                $doc->beginExportRow();
                if ($exportPageType == "view") {
                    $doc->exportCaption($this->codigo);
                    $doc->exportCaption($this->nombre);
                    $doc->exportCaption($this->cantidad_en_mano);
                    $doc->exportCaption($this->codigo_ims);
                    $doc->exportCaption($this->codigo_ims2);
                    $doc->exportCaption($this->costo);
                    $doc->exportCaption($this->precio_full);
                    $doc->exportCaption($this->precio_venta);
                } else {
                    $doc->exportCaption($this->codigo);
                    $doc->exportCaption($this->nombre);
                    $doc->exportCaption($this->cantidad_en_mano);
                    $doc->exportCaption($this->codigo_ims);
                    $doc->exportCaption($this->codigo_ims2);
                    $doc->exportCaption($this->costo);
                    $doc->exportCaption($this->precio_full);
                    $doc->exportCaption($this->precio_venta);
                }
                $doc->endExportRow();
            }
        }

        // Move to first record
        $recCnt = $startRec - 1;
        $stopRec = ($stopRec > 0) ? $stopRec : PHP_INT_MAX;
        while (!$recordset->EOF && $recCnt < $stopRec) {
            $row = $recordset->fields;
            $recCnt++;
            if ($recCnt >= $startRec) {
                $rowCnt = $recCnt - $startRec + 1;

                // Page break
                if ($this->ExportPageBreakCount > 0) {
                    if ($rowCnt > 1 && ($rowCnt - 1) % $this->ExportPageBreakCount == 0) {
                        $doc->exportPageBreak();
                    }
                }
                $this->loadListRowValues($row);

                // Render row
                $this->RowType = ROWTYPE_VIEW; // Render view
                $this->resetAttributes();
                $this->renderListRow();
                if (!$doc->ExportCustom) {
                    $doc->beginExportRow($rowCnt); // Allow CSS styles if enabled
                    if ($exportPageType == "view") {
                        $doc->exportField($this->codigo);
                        $doc->exportField($this->nombre);
                        $doc->exportField($this->cantidad_en_mano);
                        $doc->exportField($this->codigo_ims);
                        $doc->exportField($this->codigo_ims2);
                        $doc->exportField($this->costo);
                        $doc->exportField($this->precio_full);
                        $doc->exportField($this->precio_venta);
                    } else {
                        $doc->exportField($this->codigo);
                        $doc->exportField($this->nombre);
                        $doc->exportField($this->cantidad_en_mano);
                        $doc->exportField($this->codigo_ims);
                        $doc->exportField($this->codigo_ims2);
                        $doc->exportField($this->costo);
                        $doc->exportField($this->precio_full);
                        $doc->exportField($this->precio_venta);
                    }
                    $doc->endExportRow($rowCnt);
                }
            }

            // Call Row Export server event
            if ($doc->ExportCustom) {
                $this->rowExport($row);
            }
            $recordset->moveNext();
        }
        if (!$doc->ExportCustom) {
            $doc->exportTableFooter();
        }
    }

    // Get file data
    public function getFileData($fldparm, $key, $resize, $width = 0, $height = 0, $plugins = [])
    {
        // No binary fields
        return false;
    }

    // Table level events

    // Recordset Selecting event
    public function recordsetSelecting(&$filter)
    {
        // Enter your code here
    }

    // Recordset Selected event
    public function recordsetSelected(&$rs)
    {
        //Log("Recordset Selected");
    }

    // Recordset Search Validated event
    public function recordsetSearchValidated()
    {
        // Example:
        //$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value
    }

    // Recordset Searching event
    public function recordsetSearching(&$filter)
    {
        // Enter your code here
    }

    // Row_Selecting event
    public function rowSelecting(&$filter)
    {
        // Enter your code here
    }

    // Row Selected event
    public function rowSelected(&$rs)
    {
        //Log("Row Selected");
    }

    // Row Inserting event
    public function rowInserting($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    // Row Inserted event
    public function rowInserted($rsold, &$rsnew)
    {
        //Log("Row Inserted");
    }

    // Row Updating event
    public function rowUpdating($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    // Row Updated event
    public function rowUpdated($rsold, &$rsnew)
    {
        //Log("Row Updated");
    }

    // Row Update Conflict event
    public function rowUpdateConflict($rsold, &$rsnew)
    {
        // Enter your code here
        // To ignore conflict, set return value to false
        return true;
    }

    // Grid Inserting event
    public function gridInserting()
    {
        // Enter your code here
        // To reject grid insert, set return value to false
        return true;
    }

    // Grid Inserted event
    public function gridInserted($rsnew)
    {
        //Log("Grid Inserted");
    }

    // Grid Updating event
    public function gridUpdating($rsold)
    {
        // Enter your code here
        // To reject grid update, set return value to false
        return true;
    }

    // Grid Updated event
    public function gridUpdated($rsold, $rsnew)
    {
        //Log("Grid Updated");
    }

    // Row Deleting event
    public function rowDeleting(&$rs)
    {
        // Enter your code here
        // To cancel, set return value to False
        return true;
    }

    // Row Deleted event
    public function rowDeleted(&$rs)
    {
        //Log("Row Deleted");
    }

    // Email Sending event
    public function emailSending($email, &$args)
    {
        //var_dump($email); var_dump($args); exit();
        return true;
    }

    // Lookup Selecting event
    public function lookupSelecting($fld, &$filter)
    {
        //var_dump($fld->Name, $fld->Lookup, $filter); // Uncomment to view the filter
        // Enter your code here
    }

    // Row Rendering event
    public function rowRendering()
    {
        // Enter your code here
    }

    // Row Rendered event
    public function rowRendered()
    {
        // To view properties of field class, use:
        //var_dump($this-><FieldName>);
    }

    // User ID Filtering event
    public function userIdFiltering(&$filter)
    {
        // Enter your code here
    }
}
