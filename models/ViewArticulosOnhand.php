<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for view_articulos_onhand
 */
class ViewArticulosOnhand extends DbTable
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
    public $id;
    public $codigo;
    public $fabricante;
    public $nombre_comercial;
    public $principio_activo;
    public $presentacion;
    public $cantidad_en_mano;
    public $ultimo_costo;
    public $fabricante_nombre;
    public $articulo;
    public $codigo_ims;
    public $cantidad_real;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'view_articulos_onhand';
        $this->TableName = 'view_articulos_onhand';
        $this->TableType = 'VIEW';

        // Update Table
        $this->UpdateTable = "`view_articulos_onhand`";
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

        // id
        $this->id = new DbField('view_articulos_onhand', 'view_articulos_onhand', 'x_id', 'id', '`id`', '`id`', 19, 11, -1, false, '`id`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->id->Nullable = false; // NOT NULL field
        $this->id->Sortable = true; // Allow sort
        $this->id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->id->Param, "CustomMsg");
        $this->Fields['id'] = &$this->id;

        // codigo
        $this->codigo = new DbField('view_articulos_onhand', 'view_articulos_onhand', 'x_codigo', 'codigo', '`codigo`', '`codigo`', 200, 50, -1, false, '`codigo`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->codigo->Sortable = true; // Allow sort
        $this->codigo->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->codigo->Param, "CustomMsg");
        $this->Fields['codigo'] = &$this->codigo;

        // fabricante
        $this->fabricante = new DbField('view_articulos_onhand', 'view_articulos_onhand', 'x_fabricante', 'fabricante', '`fabricante`', '`fabricante`', 19, 10, -1, false, '`fabricante`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->fabricante->Sortable = true; // Allow sort
        $this->fabricante->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->fabricante->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->fabricante->Param, "CustomMsg");
        $this->Fields['fabricante'] = &$this->fabricante;

        // nombre_comercial
        $this->nombre_comercial = new DbField('view_articulos_onhand', 'view_articulos_onhand', 'x_nombre_comercial', 'nombre_comercial', '`nombre_comercial`', '`nombre_comercial`', 200, 50, -1, false, '`nombre_comercial`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->nombre_comercial->Sortable = true; // Allow sort
        $this->nombre_comercial->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->nombre_comercial->Param, "CustomMsg");
        $this->Fields['nombre_comercial'] = &$this->nombre_comercial;

        // principio_activo
        $this->principio_activo = new DbField('view_articulos_onhand', 'view_articulos_onhand', 'x_principio_activo', 'principio_activo', '`principio_activo`', '`principio_activo`', 200, 100, -1, false, '`principio_activo`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->principio_activo->Sortable = true; // Allow sort
        $this->principio_activo->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->principio_activo->Param, "CustomMsg");
        $this->Fields['principio_activo'] = &$this->principio_activo;

        // presentacion
        $this->presentacion = new DbField('view_articulos_onhand', 'view_articulos_onhand', 'x_presentacion', 'presentacion', '`presentacion`', '`presentacion`', 200, 50, -1, false, '`presentacion`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->presentacion->Sortable = true; // Allow sort
        $this->presentacion->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->presentacion->Param, "CustomMsg");
        $this->Fields['presentacion'] = &$this->presentacion;

        // cantidad_en_mano
        $this->cantidad_en_mano = new DbField('view_articulos_onhand', 'view_articulos_onhand', 'x_cantidad_en_mano', 'cantidad_en_mano', '`cantidad_en_mano`', '`cantidad_en_mano`', 131, 9, -1, false, '`cantidad_en_mano`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->cantidad_en_mano->Nullable = false; // NOT NULL field
        $this->cantidad_en_mano->Sortable = true; // Allow sort
        $this->cantidad_en_mano->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->cantidad_en_mano->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->cantidad_en_mano->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->cantidad_en_mano->Param, "CustomMsg");
        $this->Fields['cantidad_en_mano'] = &$this->cantidad_en_mano;

        // ultimo_costo
        $this->ultimo_costo = new DbField('view_articulos_onhand', 'view_articulos_onhand', 'x_ultimo_costo', 'ultimo_costo', '`ultimo_costo`', '`ultimo_costo`', 131, 14, -1, false, '`ultimo_costo`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->ultimo_costo->Nullable = false; // NOT NULL field
        $this->ultimo_costo->Sortable = true; // Allow sort
        $this->ultimo_costo->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->ultimo_costo->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->ultimo_costo->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->ultimo_costo->Param, "CustomMsg");
        $this->Fields['ultimo_costo'] = &$this->ultimo_costo;

        // fabricante_nombre
        $this->fabricante_nombre = new DbField('view_articulos_onhand', 'view_articulos_onhand', 'x_fabricante_nombre', 'fabricante_nombre', '`fabricante_nombre`', '`fabricante_nombre`', 200, 80, -1, false, '`fabricante_nombre`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->fabricante_nombre->Sortable = true; // Allow sort
        $this->fabricante_nombre->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->fabricante_nombre->Param, "CustomMsg");
        $this->Fields['fabricante_nombre'] = &$this->fabricante_nombre;

        // articulo
        $this->articulo = new DbField('view_articulos_onhand', 'view_articulos_onhand', 'x_articulo', 'articulo', '`articulo`', '`articulo`', 19, 11, -1, false, '`articulo`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->articulo->Sortable = true; // Allow sort
        $this->articulo->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->articulo->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->articulo->Param, "CustomMsg");
        $this->Fields['articulo'] = &$this->articulo;

        // codigo_ims
        $this->codigo_ims = new DbField('view_articulos_onhand', 'view_articulos_onhand', 'x_codigo_ims', 'codigo_ims', '`codigo_ims`', '`codigo_ims`', 200, 50, -1, false, '`codigo_ims`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->codigo_ims->Sortable = true; // Allow sort
        $this->codigo_ims->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->codigo_ims->Param, "CustomMsg");
        $this->Fields['codigo_ims'] = &$this->codigo_ims;

        // cantidad_real
        $this->cantidad_real = new DbField('view_articulos_onhand', 'view_articulos_onhand', 'x_cantidad_real', 'cantidad_real', '`cantidad_real`', '`cantidad_real`', 131, 32, -1, false, '`cantidad_real`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->cantidad_real->Sortable = true; // Allow sort
        $this->cantidad_real->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->cantidad_real->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->cantidad_real->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->cantidad_real->Param, "CustomMsg");
        $this->Fields['cantidad_real'] = &$this->cantidad_real;
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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`view_articulos_onhand`";
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
        $this->id->DbValue = $row['id'];
        $this->codigo->DbValue = $row['codigo'];
        $this->fabricante->DbValue = $row['fabricante'];
        $this->nombre_comercial->DbValue = $row['nombre_comercial'];
        $this->principio_activo->DbValue = $row['principio_activo'];
        $this->presentacion->DbValue = $row['presentacion'];
        $this->cantidad_en_mano->DbValue = $row['cantidad_en_mano'];
        $this->ultimo_costo->DbValue = $row['ultimo_costo'];
        $this->fabricante_nombre->DbValue = $row['fabricante_nombre'];
        $this->articulo->DbValue = $row['articulo'];
        $this->codigo_ims->DbValue = $row['codigo_ims'];
        $this->cantidad_real->DbValue = $row['cantidad_real'];
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
        return $_SESSION[$name] ?? GetUrl("ViewArticulosOnhandList");
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
        if ($pageName == "ViewArticulosOnhandView") {
            return $Language->phrase("View");
        } elseif ($pageName == "ViewArticulosOnhandEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "ViewArticulosOnhandAdd") {
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
                return "ViewArticulosOnhandView";
            case Config("API_ADD_ACTION"):
                return "ViewArticulosOnhandAdd";
            case Config("API_EDIT_ACTION"):
                return "ViewArticulosOnhandEdit";
            case Config("API_DELETE_ACTION"):
                return "ViewArticulosOnhandDelete";
            case Config("API_LIST_ACTION"):
                return "ViewArticulosOnhandList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "ViewArticulosOnhandList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ViewArticulosOnhandView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("ViewArticulosOnhandView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "ViewArticulosOnhandAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "ViewArticulosOnhandAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("ViewArticulosOnhandEdit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("ViewArticulosOnhandAdd", $this->getUrlParm($parm));
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
        return $this->keyUrl("ViewArticulosOnhandDelete", $this->getUrlParm());
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
        $this->id->setDbValue($row['id']);
        $this->codigo->setDbValue($row['codigo']);
        $this->fabricante->setDbValue($row['fabricante']);
        $this->nombre_comercial->setDbValue($row['nombre_comercial']);
        $this->principio_activo->setDbValue($row['principio_activo']);
        $this->presentacion->setDbValue($row['presentacion']);
        $this->cantidad_en_mano->setDbValue($row['cantidad_en_mano']);
        $this->ultimo_costo->setDbValue($row['ultimo_costo']);
        $this->fabricante_nombre->setDbValue($row['fabricante_nombre']);
        $this->articulo->setDbValue($row['articulo']);
        $this->codigo_ims->setDbValue($row['codigo_ims']);
        $this->cantidad_real->setDbValue($row['cantidad_real']);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // id

        // codigo

        // fabricante

        // nombre_comercial

        // principio_activo

        // presentacion

        // cantidad_en_mano

        // ultimo_costo

        // fabricante_nombre

        // articulo

        // codigo_ims

        // cantidad_real

        // id
        $this->id->ViewValue = $this->id->CurrentValue;
        $this->id->ViewValue = FormatNumber($this->id->ViewValue, 0, -2, -2, -2);
        $this->id->ViewCustomAttributes = "";

        // codigo
        $this->codigo->ViewValue = $this->codigo->CurrentValue;
        $this->codigo->ViewCustomAttributes = "";

        // fabricante
        $this->fabricante->ViewValue = $this->fabricante->CurrentValue;
        $this->fabricante->ViewValue = FormatNumber($this->fabricante->ViewValue, 0, -2, -2, -2);
        $this->fabricante->ViewCustomAttributes = "";

        // nombre_comercial
        $this->nombre_comercial->ViewValue = $this->nombre_comercial->CurrentValue;
        $this->nombre_comercial->ViewCustomAttributes = "";

        // principio_activo
        $this->principio_activo->ViewValue = $this->principio_activo->CurrentValue;
        $this->principio_activo->ViewCustomAttributes = "";

        // presentacion
        $this->presentacion->ViewValue = $this->presentacion->CurrentValue;
        $this->presentacion->ViewCustomAttributes = "";

        // cantidad_en_mano
        $this->cantidad_en_mano->ViewValue = $this->cantidad_en_mano->CurrentValue;
        $this->cantidad_en_mano->ViewValue = FormatNumber($this->cantidad_en_mano->ViewValue, 2, -2, -2, -2);
        $this->cantidad_en_mano->ViewCustomAttributes = "";

        // ultimo_costo
        $this->ultimo_costo->ViewValue = $this->ultimo_costo->CurrentValue;
        $this->ultimo_costo->ViewValue = FormatNumber($this->ultimo_costo->ViewValue, 2, -2, -2, -2);
        $this->ultimo_costo->ViewCustomAttributes = "";

        // fabricante_nombre
        $this->fabricante_nombre->ViewValue = $this->fabricante_nombre->CurrentValue;
        $this->fabricante_nombre->ViewCustomAttributes = "";

        // articulo
        $this->articulo->ViewValue = $this->articulo->CurrentValue;
        $this->articulo->ViewValue = FormatNumber($this->articulo->ViewValue, 0, -2, -2, -2);
        $this->articulo->ViewCustomAttributes = "";

        // codigo_ims
        $this->codigo_ims->ViewValue = $this->codigo_ims->CurrentValue;
        $this->codigo_ims->ViewCustomAttributes = "";

        // cantidad_real
        $this->cantidad_real->ViewValue = $this->cantidad_real->CurrentValue;
        $this->cantidad_real->ViewValue = FormatNumber($this->cantidad_real->ViewValue, 2, -2, -2, -2);
        $this->cantidad_real->ViewCustomAttributes = "";

        // id
        $this->id->LinkCustomAttributes = "";
        $this->id->HrefValue = "";
        $this->id->TooltipValue = "";

        // codigo
        $this->codigo->LinkCustomAttributes = "";
        $this->codigo->HrefValue = "";
        $this->codigo->TooltipValue = "";

        // fabricante
        $this->fabricante->LinkCustomAttributes = "";
        $this->fabricante->HrefValue = "";
        $this->fabricante->TooltipValue = "";

        // nombre_comercial
        $this->nombre_comercial->LinkCustomAttributes = "";
        $this->nombre_comercial->HrefValue = "";
        $this->nombre_comercial->TooltipValue = "";

        // principio_activo
        $this->principio_activo->LinkCustomAttributes = "";
        $this->principio_activo->HrefValue = "";
        $this->principio_activo->TooltipValue = "";

        // presentacion
        $this->presentacion->LinkCustomAttributes = "";
        $this->presentacion->HrefValue = "";
        $this->presentacion->TooltipValue = "";

        // cantidad_en_mano
        $this->cantidad_en_mano->LinkCustomAttributes = "";
        $this->cantidad_en_mano->HrefValue = "";
        $this->cantidad_en_mano->TooltipValue = "";

        // ultimo_costo
        $this->ultimo_costo->LinkCustomAttributes = "";
        $this->ultimo_costo->HrefValue = "";
        $this->ultimo_costo->TooltipValue = "";

        // fabricante_nombre
        $this->fabricante_nombre->LinkCustomAttributes = "";
        $this->fabricante_nombre->HrefValue = "";
        $this->fabricante_nombre->TooltipValue = "";

        // articulo
        $this->articulo->LinkCustomAttributes = "";
        $this->articulo->HrefValue = "";
        $this->articulo->TooltipValue = "";

        // codigo_ims
        $this->codigo_ims->LinkCustomAttributes = "";
        $this->codigo_ims->HrefValue = "";
        $this->codigo_ims->TooltipValue = "";

        // cantidad_real
        $this->cantidad_real->LinkCustomAttributes = "";
        $this->cantidad_real->HrefValue = "";
        $this->cantidad_real->TooltipValue = "";

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

        // id
        $this->id->EditAttrs["class"] = "form-control";
        $this->id->EditCustomAttributes = "";
        $this->id->EditValue = $this->id->CurrentValue;
        $this->id->PlaceHolder = RemoveHtml($this->id->caption());

        // codigo
        $this->codigo->EditAttrs["class"] = "form-control";
        $this->codigo->EditCustomAttributes = "";
        if (!$this->codigo->Raw) {
            $this->codigo->CurrentValue = HtmlDecode($this->codigo->CurrentValue);
        }
        $this->codigo->EditValue = $this->codigo->CurrentValue;
        $this->codigo->PlaceHolder = RemoveHtml($this->codigo->caption());

        // fabricante
        $this->fabricante->EditAttrs["class"] = "form-control";
        $this->fabricante->EditCustomAttributes = "";
        $this->fabricante->EditValue = $this->fabricante->CurrentValue;
        $this->fabricante->PlaceHolder = RemoveHtml($this->fabricante->caption());

        // nombre_comercial
        $this->nombre_comercial->EditAttrs["class"] = "form-control";
        $this->nombre_comercial->EditCustomAttributes = "";
        if (!$this->nombre_comercial->Raw) {
            $this->nombre_comercial->CurrentValue = HtmlDecode($this->nombre_comercial->CurrentValue);
        }
        $this->nombre_comercial->EditValue = $this->nombre_comercial->CurrentValue;
        $this->nombre_comercial->PlaceHolder = RemoveHtml($this->nombre_comercial->caption());

        // principio_activo
        $this->principio_activo->EditAttrs["class"] = "form-control";
        $this->principio_activo->EditCustomAttributes = "";
        if (!$this->principio_activo->Raw) {
            $this->principio_activo->CurrentValue = HtmlDecode($this->principio_activo->CurrentValue);
        }
        $this->principio_activo->EditValue = $this->principio_activo->CurrentValue;
        $this->principio_activo->PlaceHolder = RemoveHtml($this->principio_activo->caption());

        // presentacion
        $this->presentacion->EditAttrs["class"] = "form-control";
        $this->presentacion->EditCustomAttributes = "";
        if (!$this->presentacion->Raw) {
            $this->presentacion->CurrentValue = HtmlDecode($this->presentacion->CurrentValue);
        }
        $this->presentacion->EditValue = $this->presentacion->CurrentValue;
        $this->presentacion->PlaceHolder = RemoveHtml($this->presentacion->caption());

        // cantidad_en_mano
        $this->cantidad_en_mano->EditAttrs["class"] = "form-control";
        $this->cantidad_en_mano->EditCustomAttributes = "";
        $this->cantidad_en_mano->EditValue = $this->cantidad_en_mano->CurrentValue;
        $this->cantidad_en_mano->PlaceHolder = RemoveHtml($this->cantidad_en_mano->caption());
        if (strval($this->cantidad_en_mano->EditValue) != "" && is_numeric($this->cantidad_en_mano->EditValue)) {
            $this->cantidad_en_mano->EditValue = FormatNumber($this->cantidad_en_mano->EditValue, -2, -2, -2, -2);
        }

        // ultimo_costo
        $this->ultimo_costo->EditAttrs["class"] = "form-control";
        $this->ultimo_costo->EditCustomAttributes = "";
        $this->ultimo_costo->EditValue = $this->ultimo_costo->CurrentValue;
        $this->ultimo_costo->PlaceHolder = RemoveHtml($this->ultimo_costo->caption());
        if (strval($this->ultimo_costo->EditValue) != "" && is_numeric($this->ultimo_costo->EditValue)) {
            $this->ultimo_costo->EditValue = FormatNumber($this->ultimo_costo->EditValue, -2, -2, -2, -2);
        }

        // fabricante_nombre
        $this->fabricante_nombre->EditAttrs["class"] = "form-control";
        $this->fabricante_nombre->EditCustomAttributes = "";
        if (!$this->fabricante_nombre->Raw) {
            $this->fabricante_nombre->CurrentValue = HtmlDecode($this->fabricante_nombre->CurrentValue);
        }
        $this->fabricante_nombre->EditValue = $this->fabricante_nombre->CurrentValue;
        $this->fabricante_nombre->PlaceHolder = RemoveHtml($this->fabricante_nombre->caption());

        // articulo
        $this->articulo->EditAttrs["class"] = "form-control";
        $this->articulo->EditCustomAttributes = "";
        $this->articulo->EditValue = $this->articulo->CurrentValue;
        $this->articulo->PlaceHolder = RemoveHtml($this->articulo->caption());

        // codigo_ims
        $this->codigo_ims->EditAttrs["class"] = "form-control";
        $this->codigo_ims->EditCustomAttributes = "";
        if (!$this->codigo_ims->Raw) {
            $this->codigo_ims->CurrentValue = HtmlDecode($this->codigo_ims->CurrentValue);
        }
        $this->codigo_ims->EditValue = $this->codigo_ims->CurrentValue;
        $this->codigo_ims->PlaceHolder = RemoveHtml($this->codigo_ims->caption());

        // cantidad_real
        $this->cantidad_real->EditAttrs["class"] = "form-control";
        $this->cantidad_real->EditCustomAttributes = "";
        $this->cantidad_real->EditValue = $this->cantidad_real->CurrentValue;
        $this->cantidad_real->PlaceHolder = RemoveHtml($this->cantidad_real->caption());
        if (strval($this->cantidad_real->EditValue) != "" && is_numeric($this->cantidad_real->EditValue)) {
            $this->cantidad_real->EditValue = FormatNumber($this->cantidad_real->EditValue, -2, -2, -2, -2);
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
                    $doc->exportCaption($this->id);
                    $doc->exportCaption($this->codigo);
                    $doc->exportCaption($this->fabricante);
                    $doc->exportCaption($this->nombre_comercial);
                    $doc->exportCaption($this->principio_activo);
                    $doc->exportCaption($this->presentacion);
                    $doc->exportCaption($this->cantidad_en_mano);
                    $doc->exportCaption($this->ultimo_costo);
                    $doc->exportCaption($this->fabricante_nombre);
                    $doc->exportCaption($this->articulo);
                    $doc->exportCaption($this->codigo_ims);
                    $doc->exportCaption($this->cantidad_real);
                } else {
                    $doc->exportCaption($this->id);
                    $doc->exportCaption($this->codigo);
                    $doc->exportCaption($this->fabricante);
                    $doc->exportCaption($this->nombre_comercial);
                    $doc->exportCaption($this->principio_activo);
                    $doc->exportCaption($this->presentacion);
                    $doc->exportCaption($this->cantidad_en_mano);
                    $doc->exportCaption($this->ultimo_costo);
                    $doc->exportCaption($this->fabricante_nombre);
                    $doc->exportCaption($this->articulo);
                    $doc->exportCaption($this->codigo_ims);
                    $doc->exportCaption($this->cantidad_real);
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
                        $doc->exportField($this->id);
                        $doc->exportField($this->codigo);
                        $doc->exportField($this->fabricante);
                        $doc->exportField($this->nombre_comercial);
                        $doc->exportField($this->principio_activo);
                        $doc->exportField($this->presentacion);
                        $doc->exportField($this->cantidad_en_mano);
                        $doc->exportField($this->ultimo_costo);
                        $doc->exportField($this->fabricante_nombre);
                        $doc->exportField($this->articulo);
                        $doc->exportField($this->codigo_ims);
                        $doc->exportField($this->cantidad_real);
                    } else {
                        $doc->exportField($this->id);
                        $doc->exportField($this->codigo);
                        $doc->exportField($this->fabricante);
                        $doc->exportField($this->nombre_comercial);
                        $doc->exportField($this->principio_activo);
                        $doc->exportField($this->presentacion);
                        $doc->exportField($this->cantidad_en_mano);
                        $doc->exportField($this->ultimo_costo);
                        $doc->exportField($this->fabricante_nombre);
                        $doc->exportField($this->articulo);
                        $doc->exportField($this->codigo_ims);
                        $doc->exportField($this->cantidad_real);
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
