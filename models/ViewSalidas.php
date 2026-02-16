<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for view_salidas
 */
class ViewSalidas extends DbTable
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
    public $tipo_documento;
    public $nombre_documento;
    public $cliente;
    public $nombre_cliente;
    public $nro_documento;
    public $fecha;
    public $estatus;
    public $_username;
    public $nota;
    public $consignacion;
    public $cerrado;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'view_salidas';
        $this->TableName = 'view_salidas';
        $this->TableType = 'VIEW';

        // Update Table
        $this->UpdateTable = "`view_salidas`";
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
        $this->id = new DbField('view_salidas', 'view_salidas', 'x_id', 'id', '`id`', '`id`', 19, 10, -1, false, '`id`', false, false, false, 'FORMATTED TEXT', 'NO');
        $this->id->IsAutoIncrement = true; // Autoincrement field
        $this->id->IsPrimaryKey = true; // Primary key field
        $this->id->IsForeignKey = true; // Foreign key field
        $this->id->Sortable = true; // Allow sort
        $this->id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->id->Param, "CustomMsg");
        $this->Fields['id'] = &$this->id;

        // tipo_documento
        $this->tipo_documento = new DbField('view_salidas', 'view_salidas', 'x_tipo_documento', 'tipo_documento', '`tipo_documento`', '`tipo_documento`', 200, 6, -1, false, '`tipo_documento`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->tipo_documento->IsForeignKey = true; // Foreign key field
        $this->tipo_documento->Sortable = true; // Allow sort
        $this->tipo_documento->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tipo_documento->Param, "CustomMsg");
        $this->Fields['tipo_documento'] = &$this->tipo_documento;

        // nombre_documento
        $this->nombre_documento = new DbField('view_salidas', 'view_salidas', 'x_nombre_documento', 'nombre_documento', '`nombre_documento`', '`nombre_documento`', 200, 30, -1, false, '`nombre_documento`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->nombre_documento->Sortable = true; // Allow sort
        $this->nombre_documento->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->nombre_documento->Param, "CustomMsg");
        $this->Fields['nombre_documento'] = &$this->nombre_documento;

        // cliente
        $this->cliente = new DbField('view_salidas', 'view_salidas', 'x_cliente', 'cliente', '`cliente`', '`cliente`', 3, 11, -1, false, '`cliente`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->cliente->Sortable = true; // Allow sort
        $this->cliente->Lookup = new Lookup('cliente', 'cliente', false, 'id', ["nombre","","",""], [], [], [], [], [], [], '', '');
        $this->cliente->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->cliente->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->cliente->Param, "CustomMsg");
        $this->Fields['cliente'] = &$this->cliente;

        // nombre_cliente
        $this->nombre_cliente = new DbField('view_salidas', 'view_salidas', 'x_nombre_cliente', 'nombre_cliente', '`nombre_cliente`', '`nombre_cliente`', 200, 80, -1, false, '`nombre_cliente`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->nombre_cliente->Sortable = true; // Allow sort
        $this->nombre_cliente->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->nombre_cliente->Param, "CustomMsg");
        $this->Fields['nombre_cliente'] = &$this->nombre_cliente;

        // nro_documento
        $this->nro_documento = new DbField('view_salidas', 'view_salidas', 'x_nro_documento', 'nro_documento', '`nro_documento`', '`nro_documento`', 200, 20, -1, false, '`nro_documento`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->nro_documento->Sortable = true; // Allow sort
        $this->nro_documento->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->nro_documento->Param, "CustomMsg");
        $this->Fields['nro_documento'] = &$this->nro_documento;

        // fecha
        $this->fecha = new DbField('view_salidas', 'view_salidas', 'x_fecha', 'fecha', '`fecha`', '`fecha`', 200, 10, 7, false, '`fecha`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->fecha->Sortable = true; // Allow sort
        $this->fecha->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->fecha->Param, "CustomMsg");
        $this->Fields['fecha'] = &$this->fecha;

        // estatus
        $this->estatus = new DbField('view_salidas', 'view_salidas', 'x_estatus', 'estatus', '`estatus`', '`estatus`', 200, 10, -1, false, '`estatus`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->estatus->Sortable = true; // Allow sort
        $this->estatus->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->estatus->Param, "CustomMsg");
        $this->Fields['estatus'] = &$this->estatus;

        // username
        $this->_username = new DbField('view_salidas', 'view_salidas', 'x__username', 'username', '`username`', '`username`', 200, 30, -1, false, '`username`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->_username->Sortable = true; // Allow sort
        $this->_username->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->_username->Param, "CustomMsg");
        $this->Fields['username'] = &$this->_username;

        // nota
        $this->nota = new DbField('view_salidas', 'view_salidas', 'x_nota', 'nota', '`nota`', '`nota`', 201, 65535, -1, false, '`nota`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->nota->Sortable = true; // Allow sort
        $this->nota->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->nota->Param, "CustomMsg");
        $this->Fields['nota'] = &$this->nota;

        // consignacion
        $this->consignacion = new DbField('view_salidas', 'view_salidas', 'x_consignacion', 'consignacion', '`consignacion`', '`consignacion`', 202, 1, -1, false, '`consignacion`', false, false, false, 'FORMATTED TEXT', 'RADIO');
        $this->consignacion->Sortable = true; // Allow sort
        $this->consignacion->Lookup = new Lookup('consignacion', 'view_salidas', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->consignacion->OptionCount = 2;
        $this->consignacion->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->consignacion->Param, "CustomMsg");
        $this->Fields['consignacion'] = &$this->consignacion;

        // cerrado
        $this->cerrado = new DbField('view_salidas', 'view_salidas', 'x_cerrado', 'cerrado', '`cerrado`', '`cerrado`', 200, 80, -1, false, '`cerrado`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->cerrado->Nullable = false; // NOT NULL field
        $this->cerrado->Required = true; // Required field
        $this->cerrado->Sortable = true; // Allow sort
        $this->cerrado->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->cerrado->Param, "CustomMsg");
        $this->Fields['cerrado'] = &$this->cerrado;
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

    // Current detail table name
    public function getCurrentDetailTable()
    {
        return Session(PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_DETAIL_TABLE"));
    }

    public function setCurrentDetailTable($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_DETAIL_TABLE")] = $v;
    }

    // Get detail url
    public function getDetailUrl()
    {
        // Detail url
        $detailUrl = "";
        if ($this->getCurrentDetailTable() == "view_entradas_salidas") {
            $detailUrl = Container("view_entradas_salidas")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_tipo_documento", $this->tipo_documento->CurrentValue);
            $detailUrl .= "&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue);
        }
        if ($detailUrl == "") {
            $detailUrl = "ViewSalidasList";
        }
        return $detailUrl;
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`view_salidas`";
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
        // Cascade Update detail table 'view_entradas_salidas'
        $cascadeUpdate = false;
        $rscascade = [];
        if ($rsold && (isset($rs['tipo_documento']) && $rsold['tipo_documento'] != $rs['tipo_documento'])) { // Update detail field 'tipo_documento'
            $cascadeUpdate = true;
            $rscascade['tipo_documento'] = $rs['tipo_documento'];
        }
        if ($rsold && (isset($rs['id']) && $rsold['id'] != $rs['id'])) { // Update detail field 'id_documento'
            $cascadeUpdate = true;
            $rscascade['id_documento'] = $rs['id'];
        }
        if ($cascadeUpdate) {
            $rswrk = Container("view_entradas_salidas")->loadRs("`tipo_documento` = " . QuotedValue($rsold['tipo_documento'], DATATYPE_STRING, 'DB') . " AND " . "`id_documento` = " . QuotedValue($rsold['id'], DATATYPE_NUMBER, 'DB'))->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rswrk as $rsdtlold) {
                $rskey = [];
                $fldname = 'id';
                $rskey[$fldname] = $rsdtlold[$fldname];
                $rsdtlnew = array_merge($rsdtlold, $rscascade);
                // Call Row_Updating event
                $success = Container("view_entradas_salidas")->rowUpdating($rsdtlold, $rsdtlnew);
                if ($success) {
                    $success = Container("view_entradas_salidas")->update($rscascade, $rskey, $rsdtlold);
                }
                if (!$success) {
                    return false;
                }
                // Call Row_Updated event
                Container("view_entradas_salidas")->rowUpdated($rsdtlold, $rsdtlnew);
            }
        }

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

        // Cascade delete detail table 'view_entradas_salidas'
        $dtlrows = Container("view_entradas_salidas")->loadRs("`tipo_documento` = " . QuotedValue($rs['tipo_documento'], DATATYPE_STRING, "DB") . " AND " . "`id_documento` = " . QuotedValue($rs['id'], DATATYPE_NUMBER, "DB"))->fetchAll(\PDO::FETCH_ASSOC);
        // Call Row Deleting event
        foreach ($dtlrows as $dtlrow) {
            $success = Container("view_entradas_salidas")->rowDeleting($dtlrow);
            if (!$success) {
                break;
            }
        }
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                $success = Container("view_entradas_salidas")->delete($dtlrow); // Delete
                if (!$success) {
                    break;
                }
            }
        }
        // Call Row Deleted event
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                Container("view_entradas_salidas")->rowDeleted($dtlrow);
            }
        }
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
        $this->tipo_documento->DbValue = $row['tipo_documento'];
        $this->nombre_documento->DbValue = $row['nombre_documento'];
        $this->cliente->DbValue = $row['cliente'];
        $this->nombre_cliente->DbValue = $row['nombre_cliente'];
        $this->nro_documento->DbValue = $row['nro_documento'];
        $this->fecha->DbValue = $row['fecha'];
        $this->estatus->DbValue = $row['estatus'];
        $this->_username->DbValue = $row['username'];
        $this->nota->DbValue = $row['nota'];
        $this->consignacion->DbValue = $row['consignacion'];
        $this->cerrado->DbValue = $row['cerrado'];
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
        return $_SESSION[$name] ?? GetUrl("ViewSalidasList");
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
        if ($pageName == "ViewSalidasView") {
            return $Language->phrase("View");
        } elseif ($pageName == "ViewSalidasEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "ViewSalidasAdd") {
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
                return "ViewSalidasView";
            case Config("API_ADD_ACTION"):
                return "ViewSalidasAdd";
            case Config("API_EDIT_ACTION"):
                return "ViewSalidasEdit";
            case Config("API_DELETE_ACTION"):
                return "ViewSalidasDelete";
            case Config("API_LIST_ACTION"):
                return "ViewSalidasList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "ViewSalidasList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ViewSalidasView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("ViewSalidasView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "ViewSalidasAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "ViewSalidasAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ViewSalidasEdit", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("ViewSalidasEdit", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
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
        if ($parm != "") {
            $url = $this->keyUrl("ViewSalidasAdd", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("ViewSalidasAdd", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
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
        return $this->keyUrl("ViewSalidasDelete", $this->getUrlParm());
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
        $this->tipo_documento->setDbValue($row['tipo_documento']);
        $this->nombre_documento->setDbValue($row['nombre_documento']);
        $this->cliente->setDbValue($row['cliente']);
        $this->nombre_cliente->setDbValue($row['nombre_cliente']);
        $this->nro_documento->setDbValue($row['nro_documento']);
        $this->fecha->setDbValue($row['fecha']);
        $this->estatus->setDbValue($row['estatus']);
        $this->_username->setDbValue($row['username']);
        $this->nota->setDbValue($row['nota']);
        $this->consignacion->setDbValue($row['consignacion']);
        $this->cerrado->setDbValue($row['cerrado']);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // id

        // tipo_documento

        // nombre_documento

        // cliente

        // nombre_cliente

        // nro_documento

        // fecha

        // estatus

        // username

        // nota

        // consignacion

        // cerrado

        // id
        $this->id->ViewValue = $this->id->CurrentValue;
        $this->id->ViewCustomAttributes = "";

        // tipo_documento
        $this->tipo_documento->ViewValue = $this->tipo_documento->CurrentValue;
        $this->tipo_documento->ViewCustomAttributes = "";

        // nombre_documento
        $this->nombre_documento->ViewValue = $this->nombre_documento->CurrentValue;
        $this->nombre_documento->ViewCustomAttributes = "";

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

        // nombre_cliente
        $this->nombre_cliente->ViewValue = $this->nombre_cliente->CurrentValue;
        $this->nombre_cliente->ViewCustomAttributes = "";

        // nro_documento
        $this->nro_documento->ViewValue = $this->nro_documento->CurrentValue;
        $this->nro_documento->ViewCustomAttributes = "";

        // fecha
        $this->fecha->ViewValue = $this->fecha->CurrentValue;
        $this->fecha->ViewCustomAttributes = "";

        // estatus
        $this->estatus->ViewValue = $this->estatus->CurrentValue;
        $this->estatus->ViewCustomAttributes = "";

        // username
        $this->_username->ViewValue = $this->_username->CurrentValue;
        $this->_username->ViewCustomAttributes = "";

        // nota
        $this->nota->ViewValue = $this->nota->CurrentValue;
        $this->nota->ViewCustomAttributes = "";

        // consignacion
        if (strval($this->consignacion->CurrentValue) != "") {
            $this->consignacion->ViewValue = $this->consignacion->optionCaption($this->consignacion->CurrentValue);
        } else {
            $this->consignacion->ViewValue = null;
        }
        $this->consignacion->ViewCustomAttributes = "";

        // cerrado
        $this->cerrado->ViewValue = $this->cerrado->CurrentValue;
        $this->cerrado->ViewCustomAttributes = "";

        // id
        $this->id->LinkCustomAttributes = "";
        $this->id->HrefValue = "";
        $this->id->TooltipValue = "";

        // tipo_documento
        $this->tipo_documento->LinkCustomAttributes = "";
        $this->tipo_documento->HrefValue = "";
        $this->tipo_documento->TooltipValue = "";

        // nombre_documento
        $this->nombre_documento->LinkCustomAttributes = "";
        $this->nombre_documento->HrefValue = "";
        $this->nombre_documento->TooltipValue = "";

        // cliente
        $this->cliente->LinkCustomAttributes = "";
        $this->cliente->HrefValue = "";
        $this->cliente->TooltipValue = "";

        // nombre_cliente
        $this->nombre_cliente->LinkCustomAttributes = "";
        $this->nombre_cliente->HrefValue = "";
        $this->nombre_cliente->TooltipValue = "";

        // nro_documento
        $this->nro_documento->LinkCustomAttributes = "";
        $this->nro_documento->HrefValue = "";
        $this->nro_documento->TooltipValue = "";

        // fecha
        $this->fecha->LinkCustomAttributes = "";
        $this->fecha->HrefValue = "";
        $this->fecha->TooltipValue = "";

        // estatus
        $this->estatus->LinkCustomAttributes = "";
        $this->estatus->HrefValue = "";
        $this->estatus->TooltipValue = "";

        // username
        $this->_username->LinkCustomAttributes = "";
        $this->_username->HrefValue = "";
        $this->_username->TooltipValue = "";

        // nota
        $this->nota->LinkCustomAttributes = "";
        $this->nota->HrefValue = "";
        $this->nota->TooltipValue = "";

        // consignacion
        $this->consignacion->LinkCustomAttributes = "";
        $this->consignacion->HrefValue = "";
        $this->consignacion->TooltipValue = "";

        // cerrado
        $this->cerrado->LinkCustomAttributes = "";
        $this->cerrado->HrefValue = "";
        $this->cerrado->TooltipValue = "";

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

        // tipo_documento
        $this->tipo_documento->EditAttrs["class"] = "form-control";
        $this->tipo_documento->EditCustomAttributes = "";
        if (!$this->tipo_documento->Raw) {
            $this->tipo_documento->CurrentValue = HtmlDecode($this->tipo_documento->CurrentValue);
        }
        $this->tipo_documento->EditValue = $this->tipo_documento->CurrentValue;
        $this->tipo_documento->PlaceHolder = RemoveHtml($this->tipo_documento->caption());

        // nombre_documento
        $this->nombre_documento->EditAttrs["class"] = "form-control";
        $this->nombre_documento->EditCustomAttributes = "";
        if (!$this->nombre_documento->Raw) {
            $this->nombre_documento->CurrentValue = HtmlDecode($this->nombre_documento->CurrentValue);
        }
        $this->nombre_documento->EditValue = $this->nombre_documento->CurrentValue;
        $this->nombre_documento->PlaceHolder = RemoveHtml($this->nombre_documento->caption());

        // cliente
        $this->cliente->EditAttrs["class"] = "form-control";
        $this->cliente->EditCustomAttributes = "";
        $this->cliente->EditValue = $this->cliente->CurrentValue;
        $this->cliente->PlaceHolder = RemoveHtml($this->cliente->caption());

        // nombre_cliente
        $this->nombre_cliente->EditAttrs["class"] = "form-control";
        $this->nombre_cliente->EditCustomAttributes = "";
        if (!$this->nombre_cliente->Raw) {
            $this->nombre_cliente->CurrentValue = HtmlDecode($this->nombre_cliente->CurrentValue);
        }
        $this->nombre_cliente->EditValue = $this->nombre_cliente->CurrentValue;
        $this->nombre_cliente->PlaceHolder = RemoveHtml($this->nombre_cliente->caption());

        // nro_documento
        $this->nro_documento->EditAttrs["class"] = "form-control";
        $this->nro_documento->EditCustomAttributes = "";
        if (!$this->nro_documento->Raw) {
            $this->nro_documento->CurrentValue = HtmlDecode($this->nro_documento->CurrentValue);
        }
        $this->nro_documento->EditValue = $this->nro_documento->CurrentValue;
        $this->nro_documento->PlaceHolder = RemoveHtml($this->nro_documento->caption());

        // fecha
        $this->fecha->EditAttrs["class"] = "form-control";
        $this->fecha->EditCustomAttributes = "";
        if (!$this->fecha->Raw) {
            $this->fecha->CurrentValue = HtmlDecode($this->fecha->CurrentValue);
        }
        $this->fecha->EditValue = $this->fecha->CurrentValue;
        $this->fecha->PlaceHolder = RemoveHtml($this->fecha->caption());

        // estatus
        $this->estatus->EditAttrs["class"] = "form-control";
        $this->estatus->EditCustomAttributes = "";
        if (!$this->estatus->Raw) {
            $this->estatus->CurrentValue = HtmlDecode($this->estatus->CurrentValue);
        }
        $this->estatus->EditValue = $this->estatus->CurrentValue;
        $this->estatus->PlaceHolder = RemoveHtml($this->estatus->caption());

        // username
        $this->_username->EditAttrs["class"] = "form-control";
        $this->_username->EditCustomAttributes = "";
        if (!$this->_username->Raw) {
            $this->_username->CurrentValue = HtmlDecode($this->_username->CurrentValue);
        }
        $this->_username->EditValue = $this->_username->CurrentValue;
        $this->_username->PlaceHolder = RemoveHtml($this->_username->caption());

        // nota
        $this->nota->EditAttrs["class"] = "form-control";
        $this->nota->EditCustomAttributes = "";
        if (!$this->nota->Raw) {
            $this->nota->CurrentValue = HtmlDecode($this->nota->CurrentValue);
        }
        $this->nota->EditValue = $this->nota->CurrentValue;
        $this->nota->PlaceHolder = RemoveHtml($this->nota->caption());

        // consignacion
        $this->consignacion->EditCustomAttributes = "";
        $this->consignacion->EditValue = $this->consignacion->options(false);
        $this->consignacion->PlaceHolder = RemoveHtml($this->consignacion->caption());

        // cerrado
        $this->cerrado->EditAttrs["class"] = "form-control";
        $this->cerrado->EditCustomAttributes = "";
        if (!$this->cerrado->Raw) {
            $this->cerrado->CurrentValue = HtmlDecode($this->cerrado->CurrentValue);
        }
        $this->cerrado->EditValue = $this->cerrado->CurrentValue;
        $this->cerrado->PlaceHolder = RemoveHtml($this->cerrado->caption());

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
                    $doc->exportCaption($this->nombre_documento);
                    $doc->exportCaption($this->cliente);
                    $doc->exportCaption($this->nro_documento);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->nota);
                    $doc->exportCaption($this->consignacion);
                } else {
                    $doc->exportCaption($this->id);
                    $doc->exportCaption($this->tipo_documento);
                    $doc->exportCaption($this->nombre_documento);
                    $doc->exportCaption($this->cliente);
                    $doc->exportCaption($this->nombre_cliente);
                    $doc->exportCaption($this->nro_documento);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->estatus);
                    $doc->exportCaption($this->_username);
                    $doc->exportCaption($this->nota);
                    $doc->exportCaption($this->consignacion);
                    $doc->exportCaption($this->cerrado);
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
                        $doc->exportField($this->nombre_documento);
                        $doc->exportField($this->cliente);
                        $doc->exportField($this->nro_documento);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->nota);
                        $doc->exportField($this->consignacion);
                    } else {
                        $doc->exportField($this->id);
                        $doc->exportField($this->tipo_documento);
                        $doc->exportField($this->nombre_documento);
                        $doc->exportField($this->cliente);
                        $doc->exportField($this->nombre_cliente);
                        $doc->exportField($this->nro_documento);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->estatus);
                        $doc->exportField($this->_username);
                        $doc->exportField($this->nota);
                        $doc->exportField($this->consignacion);
                        $doc->exportField($this->cerrado);
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
    public function recordsetSelecting(&$filter) {
    	// Enter your code here	
    	// Enter your code here
    	if(isset($_REQUEST["consig"])) {
    		$consig = intval($_REQUEST["consig"]);
    	}
    	$tipo = $_REQUEST["tipo"];
    	switch($tipo) {
    	case "TDCNET":
    		AddFilter($filter, "tipo_documento IN ('TDCPDV', 'TDCFCV')");
    		AddFilter($filter, "IF(tipo_documento = 'TDCPDV', cerrado <> '', 1)");
    		AddFilter($filter, "estatus = 'NUEVO'");
    		break;
    	case "TDCFCV":
    		AddFilter($filter, "tipo_documento IN ('TDCNET')");
    		// Lo agrego para no facturar directamente las consignaciones si no por la nueva interfaz nueva 2//12/2020 Junior Sanabria
    		/*
    		if($consig == 1) {
    			AddFilter($filter, "consignacion = 'S'"); 
    		}
    		else {
    			AddFilter($filter, "consignacion = 'N'"); 
    		}
    		*/
    		AddFilter($filter, "estatus = 'PROCESADO'");
    		break;
    	default:
    		AddFilter($filter, "tipo_documento IN ('N/A')");
    	}
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
