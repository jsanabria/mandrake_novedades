<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for puntos
 */
class Puntos extends DbTable
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
    public $cliente;
    public $fecha;
    public $tipo;
    public $nro_documento;
    public $referencia;
    public $nota;
    public $puntos;
    public $saldo;
    public $_username;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'puntos';
        $this->TableName = 'puntos';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`puntos`";
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
        $this->id = new DbField('puntos', 'puntos', 'x_id', 'id', '`id`', '`id`', 21, 20, -1, false, '`id`', false, false, false, 'FORMATTED TEXT', 'NO');
        $this->id->IsAutoIncrement = true; // Autoincrement field
        $this->id->IsPrimaryKey = true; // Primary key field
        $this->id->Sortable = true; // Allow sort
        $this->id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->id->Param, "CustomMsg");
        $this->Fields['id'] = &$this->id;

        // cliente
        $this->cliente = new DbField('puntos', 'puntos', 'x_cliente', 'cliente', '`cliente`', '`cliente`', 19, 10, -1, false, '`cliente`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->cliente->Required = true; // Required field
        $this->cliente->Sortable = true; // Allow sort
        $this->cliente->Lookup = new Lookup('cliente', 'cliente', false, 'id', ["ci_rif","nombre","",""], [], [], [], [], [], [], '`nombre`', '');
        $this->cliente->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->cliente->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->cliente->Param, "CustomMsg");
        $this->Fields['cliente'] = &$this->cliente;

        // fecha
        $this->fecha = new DbField('puntos', 'puntos', 'x_fecha', 'fecha', '`fecha`', CastDateFieldForLike("`fecha`", 7, "DB"), 133, 10, 7, false, '`fecha`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->fecha->Required = true; // Required field
        $this->fecha->Sortable = true; // Allow sort
        $this->fecha->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_SEPARATOR"], $Language->phrase("IncorrectDateDMY"));
        $this->fecha->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->fecha->Param, "CustomMsg");
        $this->Fields['fecha'] = &$this->fecha;

        // tipo
        $this->tipo = new DbField('puntos', 'puntos', 'x_tipo', 'tipo', '`tipo`', '`tipo`', 202, 2, -1, false, '`tipo`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->tipo->Required = true; // Required field
        $this->tipo->Sortable = true; // Allow sort
        $this->tipo->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->tipo->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->tipo->Lookup = new Lookup('tipo', 'parametro', false, 'valor1', ["valor2","","",""], [], [], [], [], [], [], '`id`', '');
        $this->tipo->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tipo->Param, "CustomMsg");
        $this->Fields['tipo'] = &$this->tipo;

        // nro_documento
        $this->nro_documento = new DbField('puntos', 'puntos', 'x_nro_documento', 'nro_documento', '`nro_documento`', '`nro_documento`', 200, 20, -1, false, '`nro_documento`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->nro_documento->Sortable = true; // Allow sort
        $this->nro_documento->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->nro_documento->Param, "CustomMsg");
        $this->Fields['nro_documento'] = &$this->nro_documento;

        // referencia
        $this->referencia = new DbField('puntos', 'puntos', 'x_referencia', 'referencia', '`referencia`', '`referencia`', 200, 50, -1, false, '`referencia`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->referencia->Required = true; // Required field
        $this->referencia->Sortable = true; // Allow sort
        $this->referencia->Lookup = new Lookup('referencia', 'articulo', false, 'codigo_ims', ["codigo_ims","principio_activo","",""], [], [], [], [], [], [], '`codigo_ims`', '');
        $this->referencia->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->referencia->Param, "CustomMsg");
        $this->Fields['referencia'] = &$this->referencia;

        // nota
        $this->nota = new DbField('puntos', 'puntos', 'x_nota', 'nota', '`nota`', '`nota`', 200, 250, -1, false, '`nota`', false, false, false, 'FORMATTED TEXT', 'TEXTAREA');
        $this->nota->Sortable = true; // Allow sort
        $this->nota->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->nota->Param, "CustomMsg");
        $this->Fields['nota'] = &$this->nota;

        // puntos
        $this->puntos = new DbField('puntos', 'puntos', 'x_puntos', 'puntos', '`puntos`', '`puntos`', 3, 11, -1, false, '`puntos`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->puntos->Sortable = true; // Allow sort
        $this->puntos->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->puntos->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->puntos->Param, "CustomMsg");
        $this->Fields['puntos'] = &$this->puntos;

        // saldo
        $this->saldo = new DbField('puntos', 'puntos', 'x_saldo', 'saldo', '`saldo`', '`saldo`', 3, 11, -1, false, '`saldo`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->saldo->Sortable = true; // Allow sort
        $this->saldo->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->saldo->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->saldo->Param, "CustomMsg");
        $this->Fields['saldo'] = &$this->saldo;

        // username
        $this->_username = new DbField('puntos', 'puntos', 'x__username', 'username', '`username`', '`username`', 200, 30, -1, false, '`username`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->_username->Sortable = true; // Allow sort
        $this->_username->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->_username->Param, "CustomMsg");
        $this->Fields['username'] = &$this->_username;
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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`puntos`";
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
            // Get insert id if necessary
            $this->id->setDbValue($conn->lastInsertId());
            $rs['id'] = $this->id->DbValue;
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
            if (array_key_exists('id', $rs)) {
                AddFilter($where, QuotedName('id', $this->Dbid) . '=' . QuotedValue($rs['id'], $this->id->DataType, $this->Dbid));
            }
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
        $this->cliente->DbValue = $row['cliente'];
        $this->fecha->DbValue = $row['fecha'];
        $this->tipo->DbValue = $row['tipo'];
        $this->nro_documento->DbValue = $row['nro_documento'];
        $this->referencia->DbValue = $row['referencia'];
        $this->nota->DbValue = $row['nota'];
        $this->puntos->DbValue = $row['puntos'];
        $this->saldo->DbValue = $row['saldo'];
        $this->_username->DbValue = $row['username'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`id` = @id@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->id->CurrentValue : $this->id->OldValue;
        if (EmptyValue($val)) {
            return "";
        } else {
            $keys[] = $val;
        }
        return implode(Config("COMPOSITE_KEY_SEPARATOR"), $keys);
    }

    // Set Key
    public function setKey($key, $current = false)
    {
        $this->OldKey = strval($key);
        $keys = explode(Config("COMPOSITE_KEY_SEPARATOR"), $this->OldKey);
        if (count($keys) == 1) {
            if ($current) {
                $this->id->CurrentValue = $keys[0];
            } else {
                $this->id->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('id', $row) ? $row['id'] : null;
        } else {
            $val = $this->id->OldValue !== null ? $this->id->OldValue : $this->id->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@id@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
        }
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
        return $_SESSION[$name] ?? GetUrl("PuntosList");
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
        if ($pageName == "PuntosView") {
            return $Language->phrase("View");
        } elseif ($pageName == "PuntosEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "PuntosAdd") {
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
                return "PuntosView";
            case Config("API_ADD_ACTION"):
                return "PuntosAdd";
            case Config("API_EDIT_ACTION"):
                return "PuntosEdit";
            case Config("API_DELETE_ACTION"):
                return "PuntosDelete";
            case Config("API_LIST_ACTION"):
                return "PuntosList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "PuntosList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("PuntosView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("PuntosView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "PuntosAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "PuntosAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("PuntosEdit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("PuntosAdd", $this->getUrlParm($parm));
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
        return $this->keyUrl("PuntosDelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "id:" . JsonEncode($this->id->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->id->CurrentValue !== null) {
            $url .= "/" . rawurlencode($this->id->CurrentValue);
        } else {
            return "javascript:ew.alert(ew.language.phrase('InvalidRecord'));";
        }
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
            if (($keyValue = Param("id") ?? Route("id")) !== null) {
                $arKeys[] = $keyValue;
            } elseif (IsApi() && (($keyValue = Key(0) ?? Route(2)) !== null)) {
                $arKeys[] = $keyValue;
            } else {
                $arKeys = null; // Do not setup
            }

            //return $arKeys; // Do not return yet, so the values will also be checked by the following code
        }
        // Check keys
        $ar = [];
        if (is_array($arKeys)) {
            foreach ($arKeys as $key) {
                if (!is_numeric($key)) {
                    continue;
                }
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
            if ($setCurrent) {
                $this->id->CurrentValue = $key;
            } else {
                $this->id->OldValue = $key;
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
        $this->cliente->setDbValue($row['cliente']);
        $this->fecha->setDbValue($row['fecha']);
        $this->tipo->setDbValue($row['tipo']);
        $this->nro_documento->setDbValue($row['nro_documento']);
        $this->referencia->setDbValue($row['referencia']);
        $this->nota->setDbValue($row['nota']);
        $this->puntos->setDbValue($row['puntos']);
        $this->saldo->setDbValue($row['saldo']);
        $this->_username->setDbValue($row['username']);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // id

        // cliente

        // fecha

        // tipo

        // nro_documento

        // referencia

        // nota

        // puntos

        // saldo

        // username

        // id
        $this->id->ViewValue = $this->id->CurrentValue;
        $this->id->ViewCustomAttributes = "";

        // cliente
        $this->cliente->ViewValue = $this->cliente->CurrentValue;
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

        // tipo
        $curVal = trim(strval($this->tipo->CurrentValue));
        if ($curVal != "") {
            $this->tipo->ViewValue = $this->tipo->lookupCacheOption($curVal);
            if ($this->tipo->ViewValue === null) { // Lookup from database
                $filterWrk = "`valor1`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                $lookupFilter = function() {
                    return (CurrentPageID() == "add" ? ($_SESSION["FACTURACION"] == "S" ? "`codigo` = '070' AND valor1 = 'PP'" : "`codigo` = '070'") : "`codigo` = '070'");
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

        // nro_documento
        $this->nro_documento->ViewValue = $this->nro_documento->CurrentValue;
        $this->nro_documento->ViewCustomAttributes = "";

        // referencia
        $this->referencia->ViewValue = $this->referencia->CurrentValue;
        $curVal = trim(strval($this->referencia->CurrentValue));
        if ($curVal != "") {
            $this->referencia->ViewValue = $this->referencia->lookupCacheOption($curVal);
            if ($this->referencia->ViewValue === null) { // Lookup from database
                $filterWrk = "`codigo_ims`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                $sqlWrk = $this->referencia->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->referencia->Lookup->renderViewRow($rswrk[0]);
                    $this->referencia->ViewValue = $this->referencia->displayValue($arwrk);
                } else {
                    $this->referencia->ViewValue = $this->referencia->CurrentValue;
                }
            }
        } else {
            $this->referencia->ViewValue = null;
        }
        $this->referencia->ViewCustomAttributes = "";

        // nota
        $this->nota->ViewValue = $this->nota->CurrentValue;
        $this->nota->ViewCustomAttributes = "";

        // puntos
        $this->puntos->ViewValue = $this->puntos->CurrentValue;
        $this->puntos->ViewValue = FormatNumber($this->puntos->ViewValue, 0, -2, -2, -2);
        $this->puntos->ViewCustomAttributes = "";

        // saldo
        $this->saldo->ViewValue = $this->saldo->CurrentValue;
        $this->saldo->ViewValue = FormatNumber($this->saldo->ViewValue, 0, -2, -2, -2);
        $this->saldo->ViewCustomAttributes = "";

        // username
        $this->_username->ViewValue = $this->_username->CurrentValue;
        $this->_username->ViewCustomAttributes = "";

        // id
        $this->id->LinkCustomAttributes = "";
        $this->id->HrefValue = "";
        $this->id->TooltipValue = "";

        // cliente
        $this->cliente->LinkCustomAttributes = "";
        $this->cliente->HrefValue = "";
        $this->cliente->TooltipValue = "";

        // fecha
        $this->fecha->LinkCustomAttributes = "";
        $this->fecha->HrefValue = "";
        $this->fecha->TooltipValue = "";

        // tipo
        $this->tipo->LinkCustomAttributes = "";
        $this->tipo->HrefValue = "";
        $this->tipo->TooltipValue = "";

        // nro_documento
        $this->nro_documento->LinkCustomAttributes = "";
        $this->nro_documento->HrefValue = "";
        $this->nro_documento->TooltipValue = "";

        // referencia
        $this->referencia->LinkCustomAttributes = "";
        $this->referencia->HrefValue = "";
        $this->referencia->TooltipValue = "";

        // nota
        $this->nota->LinkCustomAttributes = "";
        $this->nota->HrefValue = "";
        $this->nota->TooltipValue = "";

        // puntos
        $this->puntos->LinkCustomAttributes = "";
        $this->puntos->HrefValue = "";
        $this->puntos->TooltipValue = "";

        // saldo
        $this->saldo->LinkCustomAttributes = "";
        $this->saldo->HrefValue = "";
        $this->saldo->TooltipValue = "";

        // username
        $this->_username->LinkCustomAttributes = "";
        $this->_username->HrefValue = "";
        $this->_username->TooltipValue = "";

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
        $this->id->ViewCustomAttributes = "";

        // cliente
        $this->cliente->EditAttrs["class"] = "form-control";
        $this->cliente->EditCustomAttributes = "";
        $this->cliente->EditValue = $this->cliente->CurrentValue;
        $this->cliente->PlaceHolder = RemoveHtml($this->cliente->caption());

        // fecha
        $this->fecha->EditAttrs["class"] = "form-control";
        $this->fecha->EditCustomAttributes = "";
        $this->fecha->EditValue = FormatDateTime($this->fecha->CurrentValue, 7);
        $this->fecha->PlaceHolder = RemoveHtml($this->fecha->caption());

        // tipo
        $this->tipo->EditAttrs["class"] = "form-control";
        $this->tipo->EditCustomAttributes = "";
        $this->tipo->PlaceHolder = RemoveHtml($this->tipo->caption());

        // nro_documento
        $this->nro_documento->EditAttrs["class"] = "form-control";
        $this->nro_documento->EditCustomAttributes = "";
        if (!$this->nro_documento->Raw) {
            $this->nro_documento->CurrentValue = HtmlDecode($this->nro_documento->CurrentValue);
        }
        $this->nro_documento->EditValue = $this->nro_documento->CurrentValue;
        $this->nro_documento->PlaceHolder = RemoveHtml($this->nro_documento->caption());

        // referencia
        $this->referencia->EditAttrs["class"] = "form-control";
        $this->referencia->EditCustomAttributes = "";
        if (!$this->referencia->Raw) {
            $this->referencia->CurrentValue = HtmlDecode($this->referencia->CurrentValue);
        }
        $this->referencia->EditValue = $this->referencia->CurrentValue;
        $this->referencia->PlaceHolder = RemoveHtml($this->referencia->caption());

        // nota
        $this->nota->EditAttrs["class"] = "form-control";
        $this->nota->EditCustomAttributes = "";
        $this->nota->EditValue = $this->nota->CurrentValue;
        $this->nota->PlaceHolder = RemoveHtml($this->nota->caption());

        // puntos
        $this->puntos->EditAttrs["class"] = "form-control";
        $this->puntos->EditCustomAttributes = "";
        $this->puntos->EditValue = $this->puntos->CurrentValue;
        $this->puntos->PlaceHolder = RemoveHtml($this->puntos->caption());

        // saldo
        $this->saldo->EditAttrs["class"] = "form-control";
        $this->saldo->EditCustomAttributes = "";
        $this->saldo->EditValue = $this->saldo->CurrentValue;
        $this->saldo->PlaceHolder = RemoveHtml($this->saldo->caption());

        // username
        $this->_username->EditAttrs["class"] = "form-control";
        $this->_username->EditCustomAttributes = "";
        if (!$this->_username->Raw) {
            $this->_username->CurrentValue = HtmlDecode($this->_username->CurrentValue);
        }
        $this->_username->EditValue = $this->_username->CurrentValue;
        $this->_username->PlaceHolder = RemoveHtml($this->_username->caption());

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
                    $doc->exportCaption($this->cliente);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->tipo);
                    $doc->exportCaption($this->nro_documento);
                    $doc->exportCaption($this->referencia);
                    $doc->exportCaption($this->nota);
                    $doc->exportCaption($this->puntos);
                    $doc->exportCaption($this->saldo);
                    $doc->exportCaption($this->_username);
                } else {
                    $doc->exportCaption($this->id);
                    $doc->exportCaption($this->cliente);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->tipo);
                    $doc->exportCaption($this->nro_documento);
                    $doc->exportCaption($this->referencia);
                    $doc->exportCaption($this->nota);
                    $doc->exportCaption($this->puntos);
                    $doc->exportCaption($this->saldo);
                    $doc->exportCaption($this->_username);
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
                        $doc->exportField($this->cliente);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->tipo);
                        $doc->exportField($this->nro_documento);
                        $doc->exportField($this->referencia);
                        $doc->exportField($this->nota);
                        $doc->exportField($this->puntos);
                        $doc->exportField($this->saldo);
                        $doc->exportField($this->_username);
                    } else {
                        $doc->exportField($this->id);
                        $doc->exportField($this->cliente);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->tipo);
                        $doc->exportField($this->nro_documento);
                        $doc->exportField($this->referencia);
                        $doc->exportField($this->nota);
                        $doc->exportField($this->puntos);
                        $doc->exportField($this->saldo);
                        $doc->exportField($this->_username);
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
        $_SESSION["FACTURACION"] = "N";
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

        // Guardo los puntos que de forma manual tipee el usuario
        $puntos_manuales = intval($rsnew["puntos"]);

        // Se valida que el tipo sea carga manual o pago de premio
     	if($rsnew["tipo"] != 'CM' AND $rsnew["tipo"] != 'PP') {
    		$this->CancelMessage = "El tipo debe ser Carga Manual o Pago de Premio.";
    		return FALSE;
    	}
    	$puntos = 0;
    	if($rsnew["tipo"] == "PP") {
    	    $cliente = $rsnew["cliente"]; 
    	    $meses = intval(ExecuteScalar("SELECT valor1 FROM parametro WHERE codigo = '071'"));
    	    if ($meses <= 0) $meses = 3; 
    	    $compras = intval(ExecuteScalar("SELECT valor1 FROM parametro WHERE codigo = '072'"));
    	    if ($compras <= 0) $compras = 5;
    	    $sql = "SELECT SUM(b.cantidad_articulo) 
    	    		FROM salidas AS a 
    	    			INNER JOIN
    	    			entradas_salidas AS b
    	    			ON b.id_documento = a.id AND b.tipo_documento = a.tipo_documento 
    	    		WHERE a.cliente = '$cliente' 
    	    			AND a.tipo_documento = 'TDCNET' 
    	    			AND a.estatus = 'PROCESADO'
    	    			AND a.fecha >= DATE_SUB(NOW(), INTERVAL $meses MONTH) 
    	    			AND b.articulo NOT IN (SELECT id FROM articulo WHERE codigo_ims LIKE 'CAT-%')";
            $total_compras_reales = intval(ExecuteScalar($sql));
            if($total_compras_reales < $compras) {
            	$this->CancelMessage = "El cliente tiene solo $total_compras_reales art&iacute;culos comprados (mínimo requerido: $compras) en los últimos $meses meses; no puede cobrar premio.";
            	return FALSE;
            }   
            // Fin validación
        }
        $sql_art = "SELECT puntos_premio AS puntos, cantidad_en_mano FROM articulo WHERE codigo_ims = '" . $rsnew["referencia"] . "';";
        $movimiento = -1;
    	if(!$row = ExecuteRow($sql_art)) {
    		$this->CancelMessage = "El n&uacute;mero de referencia no existe.";
    		return FALSE;
    	}
    	$puntos = intval($row["puntos"]) * $movimiento;
    	$cantidad_en_mano = intval($row["cantidad_en_mano"]);
    	$rsnew["puntos"] = $puntos;

    	/*** Valido si tiene puntos suficientes para darle el premio y si los punto por premino no estan en cero y si hay existencia ***/
    	if($rsnew["tipo"] == "PP") {
    		if($cantidad_en_mano <= 0) {
    			$this->CancelMessage = "No hay existencia para entregar el premio.";
    			return FALSE;
    		}
    		if(abs($puntos) <= 0) {
    			$this->CancelMessage = "El art&iacute;culo para premio no tienes puntos asignados.";
    			return FALSE;
    		}
    		$sql = "SELECT saldo FROM puntos WHERE cliente = " . $rsnew["cliente"] . " ORDER BY id DESC LIMIT 0, 1;";
    		if($row = ExecuteRow($sql)) {
    			if(intval($row["saldo"]) < abs($puntos)) {
    				$this->CancelMessage = "La cantidad de puntos del cliente es inferior a los puntos del premio.";
    				return FALSE;
    			}
    		}
    		else {
    			$this->CancelMessage = "El cliente no tiene puntos acumulados.";
    			return FALSE;
    		}
    	}

    	// Si la carga es manual y si los puntos puntos manuales son mayores a cero pongo la referencias en vacío
     	if($rsnew["tipo"] == 'CM') { // and $puntos_manuales > 0) {
     		$rsnew["puntos"] = $puntos_manuales;
     		$rsnew["referencia"] = "";
    	}
        $rsnew["fecha"] = date("Y-m-d");
        $rsnew["username"] = CurrentUserName();
        return true;
    }

    // Row Inserted event
    public function rowInserted($rsold, &$rsnew)
    {
        //Log("Row Inserted");
        $cliente = $rsnew["cliente"];
        $sql = "SELECT IFNULL(SUM(puntos), 0) AS saldo FROM puntos
        		WHERE cliente = $cliente;";
        $saldo = ExecuteScalar($sql);

     	// Obtengo el nombre del cliente
     	$sql = "SELECT nombre FROM cliente WHERE id = $cliente;";
        $nombre_cliente = ExecuteScalar($sql);
        $id = $rsnew["id"];
        $sql = "UPDATE puntos SET saldo = $saldo WHERE id = $id;";
        Execute($sql);

        /*** Si es Pago de Premio se hace una Nota de Entrega de la referencia seleccionada ***/
     	if($rsnew["tipo"] == 'PP') {
     		$sql = "SELECT MAX(CAST(IFNULL(nro_documento, 0) AS UNSIGNED)) AS consecutivo FROM salidas WHERE tipo_documento = 'TDCNET';";
     		$row = ExecuteRow($sql);
     		$consecutivo = intval($row["consecutivo"]) + 1; 
     		$nro_documento = str_pad($consecutivo, 7, "0", STR_PAD_LEFT);
    	    $sql = "SELECT tasa FROM tasa_usd WHERE moneda = 'USD' ORDER BY id DESC LIMIT 0, 1;";
       		$tasa = floatval(ExecuteScalar($sql));
    	    $sql = "SELECT id, ultimo_costo, precio FROM articulo WHERE codigo_ims = '" . $rsnew["referencia"] . "';";
    	    $row = ExecuteRow($sql);
       		$articulo = intval($row["id"]);
       		$ultimo_costo = floatval($row["ultimo_costo"]);
       		// $precio = floatval($row["precio"]);
       		$precio = floatval($row["ultimo_costo"]);
        	$sql = "INSERT INTO salidas
        			SET
        				id = NULL,
        				tipo_documento = 'TDCNET',
        				username = '" . CurrentUserName() . "',
        				fecha = '" . date("Y-m-d") . "',
        				cliente = " . $rsnew["cliente"] . ",
        				nro_documento = '$nro_documento',
        				monto_total = $precio,
        				alicuota_iva = 0,
        				iva = 0,
        				total = $precio,
        				nota = 'ENTREGA DE PREMIO SEGUN TRANSACCION PUNTOS Nro. " . $rsnew["id"] . "',
        				estatus = 'PROCESADO',
        				moneda = 'USD',
        				tasa_dia = $tasa,
        				monto_sin_descuento = 0,
        				unidades = 1,
        				factura = 'N',
        				activo = 'S',
        				cerrado = 'N', pago_premio = 'S';";
        	Execute($sql);
        	$newid = ExecuteScalar("SELECT LAST_INSERT_ID();");
        	$sql = "INSERT INTO entradas_salidas
        			SET 
        				id = NULL,
        				tipo_documento = 'TDCNET',
        				id_documento = $newid,
        				fabricante = 1,
        				articulo = $articulo,
        				lote = '" . $rsnew["referencia"] . "',
        				almacen = 'ALM001',
        				cantidad_articulo = 1,
        				articulo_unidad_medida = 'UDM001',
        				cantidad_unidad_medida = 1,
        				cantidad_movimiento = -1,
        				costo_unidad = $ultimo_costo,
        				costo = $ultimo_costo,
        				precio_unidad = $precio,
        				precio = $precio,
        				alicuota = 0,
        				check_ne = 'N';";
        	Execute($sql);
       		ActualizarExitenciaArticulo($articulo);
       		$cliente = $rsnew["cliente"];
       		$fecha = date("Y-m-d");
       		$user = CurrentUserName();
       		$sql = "INSERT INTO cobros_cliente
       					(id, cliente, id_documento, fecha, pago, moneda, fecha_registro, username)
       				VALUES
       					(NULL, $cliente, $newid, '$fecha', $precio, 'USD', '$fecha', '$user')";
       		Execute($sql);
       		$newcobro = ExecuteScalar("SELECT LAST_INSERT_ID();");
       		$monto_bs = $precio*$tasa;
       		/*
       		$sql = "INSERT INTO cobros_cliente_detalle
       					(id, cobros_cliente, metodo_pago, referencia, monto_moneda, moneda, tasa_moneda, monto_bs, tasa_usd, monto_usd, banco)
       				VALUES
       					(NULL, $newcobro, 'EF', '', $precio, 'USD', $tasa, $monto_bs, $tasa, $precio, NULL)";
       		Execute($sql);
       		*/
      		$sql = "INSERT INTO cobros_cliente_detalle
       					(id, cobros_cliente, metodo_pago, referencia, monto_moneda, moneda, tasa_moneda, monto_bs, tasa_usd, monto_usd, banco)
       				VALUES
       					(NULL, $newcobro, 'EF', '', $monto_bs, 'Bs.', 1.00, $monto_bs, $tasa, $precio, NULL)";
       		Execute($sql);

       		/*** Inserto el Gasto por el Pago del Premio ***/
       		$nota = "CANJE DE PREMIO A CLIENTE $nombre_cliente BAJO NOTA DE ENTREGA # $nro_documento POR UN MONTO DE USD " . number_format($precio, 2, ".", ",");
       		$sql = "INSERT INTO compra
       					(id, proveedor, documento, nro_control, fecha, descripcion, 
       					aplica_retencion, monto_exento, monto_gravado, alicuota, monto_iva, monto_total, 
       					fecha_registro, username, anulado, tipo_documento)
       				VALUES
       					(NULL, 1, '$nro_documento', '$nro_documento', '" . date("Y-m-d") . "', '$nota',
       					'N', 0, $precio, 0, 0, $precio,
       					'" . date("Y-m-d H:i:s") . "', '" . CurrentUserName() . "', 'N', 'NE');";
       		Execute($sql);

       		/*** Obtengo el nro de la nota de entrega para actualizarlos en la tabal de puntos ***/
       		$sql = "SELECT nro_documento FROM salidas WHERE id = $newid;";
       		$nro_documento = ExecuteScalar($sql);
       		$sql = "UPDATE puntos SET nro_documento='$nro_documento' WHERE id = $id;";
       		Execute($sql);
     	}
    	/*** ***/
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
