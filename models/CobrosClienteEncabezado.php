<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for cobros_cliente_encabezado
 */
class CobrosClienteEncabezado extends DbTable
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
    public $pivote;
    public $id_documento;
    public $tipo_documento;
    public $fecha;
    public $monto;
    public $moneda;
    public $nota;
    public $fecha_registro;
    public $_username;
    public $comprobante;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'cobros_cliente_encabezado';
        $this->TableName = 'cobros_cliente_encabezado';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`cobros_cliente_encabezado`";
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
        $this->id = new DbField('cobros_cliente_encabezado', 'cobros_cliente_encabezado', 'x_id', 'id', '`id`', '`id`', 19, 10, -1, false, '`id`', false, false, false, 'FORMATTED TEXT', 'NO');
        $this->id->IsAutoIncrement = true; // Autoincrement field
        $this->id->IsPrimaryKey = true; // Primary key field
        $this->id->IsForeignKey = true; // Foreign key field
        $this->id->Sortable = true; // Allow sort
        $this->id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->id->Param, "CustomMsg");
        $this->Fields['id'] = &$this->id;

        // cliente
        $this->cliente = new DbField('cobros_cliente_encabezado', 'cobros_cliente_encabezado', 'x_cliente', 'cliente', '`cliente`', '`cliente`', 19, 10, -1, false, '`cliente`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->cliente->Nullable = false; // NOT NULL field
        $this->cliente->Required = true; // Required field
        $this->cliente->Sortable = true; // Allow sort
        $this->cliente->Lookup = new Lookup('cliente', 'cliente', false, 'id', ["nombre","","",""], [], ["x_id_documento"], [], [], [], [], '`nombre`', '');
        $this->cliente->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->cliente->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->cliente->Param, "CustomMsg");
        $this->Fields['cliente'] = &$this->cliente;

        // pivote
        $this->pivote = new DbField('cobros_cliente_encabezado', 'cobros_cliente_encabezado', 'x_pivote', 'pivote', '`pivote`', '`pivote`', 200, 1, -1, false, '`pivote`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->pivote->Sortable = true; // Allow sort
        $this->pivote->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->pivote->Param, "CustomMsg");
        $this->Fields['pivote'] = &$this->pivote;

        // id_documento
        $this->id_documento = new DbField('cobros_cliente_encabezado', 'cobros_cliente_encabezado', 'x_id_documento', 'id_documento', '`id_documento`', '`id_documento`', 3, 11, -1, false, '`id_documento`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->id_documento->Required = true; // Required field
        $this->id_documento->Sortable = true; // Allow sort
        $this->id_documento->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->id_documento->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->id_documento->Lookup = new Lookup('id_documento', 'salidas', false, 'id', ["nro_documento","","",""], ["x_cliente"], [], ["cliente"], ["x_cliente"], [], [], '`id` DESC', '');
        $this->id_documento->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id_documento->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->id_documento->Param, "CustomMsg");
        $this->Fields['id_documento'] = &$this->id_documento;

        // tipo_documento
        $this->tipo_documento = new DbField('cobros_cliente_encabezado', 'cobros_cliente_encabezado', 'x_tipo_documento', 'tipo_documento', '`tipo_documento`', '`tipo_documento`', 200, 6, -1, false, '`tipo_documento`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->tipo_documento->Required = true; // Required field
        $this->tipo_documento->Sortable = true; // Allow sort
        $this->tipo_documento->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->tipo_documento->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->tipo_documento->Lookup = new Lookup('tipo_documento', 'tipo_documento', false, 'codigo', ["descripcion","","",""], [], [], [], [], [], [], '', '');
        $this->tipo_documento->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tipo_documento->Param, "CustomMsg");
        $this->Fields['tipo_documento'] = &$this->tipo_documento;

        // fecha
        $this->fecha = new DbField('cobros_cliente_encabezado', 'cobros_cliente_encabezado', 'x_fecha', 'fecha', '`fecha`', CastDateFieldForLike("`fecha`", 0, "DB"), 133, 10, 0, false, '`fecha`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->fecha->Sortable = true; // Allow sort
        $this->fecha->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->fecha->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->fecha->Param, "CustomMsg");
        $this->Fields['fecha'] = &$this->fecha;

        // monto
        $this->monto = new DbField('cobros_cliente_encabezado', 'cobros_cliente_encabezado', 'x_monto', 'monto', '`monto`', '`monto`', 131, 14, -1, false, '`monto`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->monto->Sortable = true; // Allow sort
        $this->monto->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->monto->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->monto->Param, "CustomMsg");
        $this->Fields['monto'] = &$this->monto;

        // moneda
        $this->moneda = new DbField('cobros_cliente_encabezado', 'cobros_cliente_encabezado', 'x_moneda', 'moneda', '`moneda`', '`moneda`', 200, 6, -1, false, '`moneda`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->moneda->Sortable = true; // Allow sort
        $this->moneda->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->moneda->Param, "CustomMsg");
        $this->Fields['moneda'] = &$this->moneda;

        // nota
        $this->nota = new DbField('cobros_cliente_encabezado', 'cobros_cliente_encabezado', 'x_nota', 'nota', '`nota`', '`nota`', 200, 255, -1, false, '`nota`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->nota->Sortable = true; // Allow sort
        $this->nota->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->nota->Param, "CustomMsg");
        $this->Fields['nota'] = &$this->nota;

        // fecha_registro
        $this->fecha_registro = new DbField('cobros_cliente_encabezado', 'cobros_cliente_encabezado', 'x_fecha_registro', 'fecha_registro', '`fecha_registro`', CastDateFieldForLike("`fecha_registro`", 0, "DB"), 133, 10, 0, false, '`fecha_registro`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->fecha_registro->Sortable = true; // Allow sort
        $this->fecha_registro->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->fecha_registro->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->fecha_registro->Param, "CustomMsg");
        $this->Fields['fecha_registro'] = &$this->fecha_registro;

        // username
        $this->_username = new DbField('cobros_cliente_encabezado', 'cobros_cliente_encabezado', 'x__username', 'username', '`username`', '`username`', 200, 30, -1, false, '`username`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->_username->Sortable = true; // Allow sort
        $this->_username->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->_username->Param, "CustomMsg");
        $this->Fields['username'] = &$this->_username;

        // comprobante
        $this->comprobante = new DbField('cobros_cliente_encabezado', 'cobros_cliente_encabezado', 'x_comprobante', 'comprobante', '`comprobante`', '`comprobante`', 200, 1, -1, false, '`comprobante`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->comprobante->Sortable = true; // Allow sort
        $this->comprobante->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->comprobante->Param, "CustomMsg");
        $this->Fields['comprobante'] = &$this->comprobante;
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
        if ($this->getCurrentDetailTable() == "cobros_cliente_detalle") {
            $detailUrl = Container("cobros_cliente_detalle")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue);
        }
        if ($detailUrl == "") {
            $detailUrl = "CobrosClienteEncabezadoList";
        }
        return $detailUrl;
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`cobros_cliente_encabezado`";
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
        // Cascade Update detail table 'cobros_cliente_detalle'
        $cascadeUpdate = false;
        $rscascade = [];
        if ($rsold && (isset($rs['id']) && $rsold['id'] != $rs['id'])) { // Update detail field 'cobros_cliente'
            $cascadeUpdate = true;
            $rscascade['cobros_cliente'] = $rs['id'];
        }
        if ($cascadeUpdate) {
            $rswrk = Container("cobros_cliente_detalle")->loadRs("`cobros_cliente` = " . QuotedValue($rsold['id'], DATATYPE_NUMBER, 'DB'))->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rswrk as $rsdtlold) {
                $rskey = [];
                $fldname = 'id';
                $rskey[$fldname] = $rsdtlold[$fldname];
                $rsdtlnew = array_merge($rsdtlold, $rscascade);
                // Call Row_Updating event
                $success = Container("cobros_cliente_detalle")->rowUpdating($rsdtlold, $rsdtlnew);
                if ($success) {
                    $success = Container("cobros_cliente_detalle")->update($rscascade, $rskey, $rsdtlold);
                }
                if (!$success) {
                    return false;
                }
                // Call Row_Updated event
                Container("cobros_cliente_detalle")->rowUpdated($rsdtlold, $rsdtlnew);
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

        // Cascade delete detail table 'cobros_cliente_detalle'
        $dtlrows = Container("cobros_cliente_detalle")->loadRs("`cobros_cliente` = " . QuotedValue($rs['id'], DATATYPE_NUMBER, "DB"))->fetchAll(\PDO::FETCH_ASSOC);
        // Call Row Deleting event
        foreach ($dtlrows as $dtlrow) {
            $success = Container("cobros_cliente_detalle")->rowDeleting($dtlrow);
            if (!$success) {
                break;
            }
        }
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                $success = Container("cobros_cliente_detalle")->delete($dtlrow); // Delete
                if (!$success) {
                    break;
                }
            }
        }
        // Call Row Deleted event
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                Container("cobros_cliente_detalle")->rowDeleted($dtlrow);
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
        $this->cliente->DbValue = $row['cliente'];
        $this->pivote->DbValue = $row['pivote'];
        $this->id_documento->DbValue = $row['id_documento'];
        $this->tipo_documento->DbValue = $row['tipo_documento'];
        $this->fecha->DbValue = $row['fecha'];
        $this->monto->DbValue = $row['monto'];
        $this->moneda->DbValue = $row['moneda'];
        $this->nota->DbValue = $row['nota'];
        $this->fecha_registro->DbValue = $row['fecha_registro'];
        $this->_username->DbValue = $row['username'];
        $this->comprobante->DbValue = $row['comprobante'];
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
        return $_SESSION[$name] ?? GetUrl("CobrosClienteEncabezadoList");
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
        if ($pageName == "CobrosClienteEncabezadoView") {
            return $Language->phrase("View");
        } elseif ($pageName == "CobrosClienteEncabezadoEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "CobrosClienteEncabezadoAdd") {
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
                return "CobrosClienteEncabezadoView";
            case Config("API_ADD_ACTION"):
                return "CobrosClienteEncabezadoAdd";
            case Config("API_EDIT_ACTION"):
                return "CobrosClienteEncabezadoEdit";
            case Config("API_DELETE_ACTION"):
                return "CobrosClienteEncabezadoDelete";
            case Config("API_LIST_ACTION"):
                return "CobrosClienteEncabezadoList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "CobrosClienteEncabezadoList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("CobrosClienteEncabezadoView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("CobrosClienteEncabezadoView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "CobrosClienteEncabezadoAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "CobrosClienteEncabezadoAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("CobrosClienteEncabezadoEdit", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("CobrosClienteEncabezadoEdit", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
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
            $url = $this->keyUrl("CobrosClienteEncabezadoAdd", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("CobrosClienteEncabezadoAdd", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
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
        return $this->keyUrl("CobrosClienteEncabezadoDelete", $this->getUrlParm());
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
        $this->pivote->setDbValue($row['pivote']);
        $this->id_documento->setDbValue($row['id_documento']);
        $this->tipo_documento->setDbValue($row['tipo_documento']);
        $this->fecha->setDbValue($row['fecha']);
        $this->monto->setDbValue($row['monto']);
        $this->moneda->setDbValue($row['moneda']);
        $this->nota->setDbValue($row['nota']);
        $this->fecha_registro->setDbValue($row['fecha_registro']);
        $this->_username->setDbValue($row['username']);
        $this->comprobante->setDbValue($row['comprobante']);
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

        // pivote

        // id_documento

        // tipo_documento

        // fecha

        // monto

        // moneda

        // nota

        // fecha_registro

        // username

        // comprobante

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
                $lookupFilter = function() {
                    return (CurrentPageID() == "add") ? 
        	"`id` IN 
        		(SELECT cliente 
        		FROM salidas  
        		WHERE tipo_documento IN ('TDCFCV', 'TDCNET') AND estatus = 'NUEVO')" : "";
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->cliente->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
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

        // pivote
        $this->pivote->ViewValue = $this->pivote->CurrentValue;
        $this->pivote->ViewCustomAttributes = "";

        // id_documento
        $curVal = trim(strval($this->id_documento->CurrentValue));
        if ($curVal != "") {
            $this->id_documento->ViewValue = $this->id_documento->lookupCacheOption($curVal);
            if ($this->id_documento->ViewValue === null) { // Lookup from database
                $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $lookupFilter = function() {
                    return "tipo_documento IN ('TDCFCV', 'TDCNET') AND estatus = 'NUEVO'";
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->id_documento->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->id_documento->Lookup->renderViewRow($rswrk[0]);
                    $this->id_documento->ViewValue = $this->id_documento->displayValue($arwrk);
                } else {
                    $this->id_documento->ViewValue = $this->id_documento->CurrentValue;
                }
            }
        } else {
            $this->id_documento->ViewValue = null;
        }
        $this->id_documento->ViewCustomAttributes = "";

        // tipo_documento
        $curVal = trim(strval($this->tipo_documento->CurrentValue));
        if ($curVal != "") {
            $this->tipo_documento->ViewValue = $this->tipo_documento->lookupCacheOption($curVal);
            if ($this->tipo_documento->ViewValue === null) { // Lookup from database
                $filterWrk = "`codigo`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                $lookupFilter = function() {
                    return "`codigo` IN ('TDCFCV', 'TDCNET')";
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->tipo_documento->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->tipo_documento->Lookup->renderViewRow($rswrk[0]);
                    $this->tipo_documento->ViewValue = $this->tipo_documento->displayValue($arwrk);
                } else {
                    $this->tipo_documento->ViewValue = $this->tipo_documento->CurrentValue;
                }
            }
        } else {
            $this->tipo_documento->ViewValue = null;
        }
        $this->tipo_documento->ViewCustomAttributes = "";

        // fecha
        $this->fecha->ViewValue = $this->fecha->CurrentValue;
        $this->fecha->ViewValue = FormatDateTime($this->fecha->ViewValue, 0);
        $this->fecha->ViewCustomAttributes = "";

        // monto
        $this->monto->ViewValue = $this->monto->CurrentValue;
        $this->monto->ViewValue = FormatNumber($this->monto->ViewValue, 2, -2, -2, -2);
        $this->monto->ViewCustomAttributes = "";

        // moneda
        $this->moneda->ViewValue = $this->moneda->CurrentValue;
        $this->moneda->ViewCustomAttributes = "";

        // nota
        $this->nota->ViewValue = $this->nota->CurrentValue;
        $this->nota->ViewCustomAttributes = "";

        // fecha_registro
        $this->fecha_registro->ViewValue = $this->fecha_registro->CurrentValue;
        $this->fecha_registro->ViewValue = FormatDateTime($this->fecha_registro->ViewValue, 0);
        $this->fecha_registro->ViewCustomAttributes = "";

        // username
        $this->_username->ViewValue = $this->_username->CurrentValue;
        $this->_username->ViewCustomAttributes = "";

        // comprobante
        $this->comprobante->ViewValue = $this->comprobante->CurrentValue;
        $this->comprobante->ViewCustomAttributes = "";

        // id
        $this->id->LinkCustomAttributes = "";
        $this->id->HrefValue = "";
        $this->id->TooltipValue = "";

        // cliente
        $this->cliente->LinkCustomAttributes = "";
        $this->cliente->HrefValue = "";
        $this->cliente->TooltipValue = "";

        // pivote
        $this->pivote->LinkCustomAttributes = "";
        $this->pivote->HrefValue = "";
        $this->pivote->TooltipValue = "";

        // id_documento
        $this->id_documento->LinkCustomAttributes = "";
        $this->id_documento->HrefValue = "";
        $this->id_documento->TooltipValue = "";

        // tipo_documento
        $this->tipo_documento->LinkCustomAttributes = "";
        $this->tipo_documento->HrefValue = "";
        $this->tipo_documento->TooltipValue = "";

        // fecha
        $this->fecha->LinkCustomAttributes = "";
        $this->fecha->HrefValue = "";
        $this->fecha->TooltipValue = "";

        // monto
        $this->monto->LinkCustomAttributes = "";
        $this->monto->HrefValue = "";
        $this->monto->TooltipValue = "";

        // moneda
        $this->moneda->LinkCustomAttributes = "";
        $this->moneda->HrefValue = "";
        $this->moneda->TooltipValue = "";

        // nota
        $this->nota->LinkCustomAttributes = "";
        $this->nota->HrefValue = "";
        $this->nota->TooltipValue = "";

        // fecha_registro
        $this->fecha_registro->LinkCustomAttributes = "";
        $this->fecha_registro->HrefValue = "";
        $this->fecha_registro->TooltipValue = "";

        // username
        $this->_username->LinkCustomAttributes = "";
        $this->_username->HrefValue = "";
        $this->_username->TooltipValue = "";

        // comprobante
        $this->comprobante->LinkCustomAttributes = "";
        $this->comprobante->HrefValue = "";
        $this->comprobante->TooltipValue = "";

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
        $curVal = trim(strval($this->cliente->CurrentValue));
        if ($curVal != "") {
            $this->cliente->EditValue = $this->cliente->lookupCacheOption($curVal);
            if ($this->cliente->EditValue === null) { // Lookup from database
                $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $lookupFilter = function() {
                    return (CurrentPageID() == "add") ? 
        	"`id` IN 
        		(SELECT cliente 
        		FROM salidas  
        		WHERE tipo_documento IN ('TDCFCV', 'TDCNET') AND estatus = 'NUEVO')" : "";
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->cliente->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->cliente->Lookup->renderViewRow($rswrk[0]);
                    $this->cliente->EditValue = $this->cliente->displayValue($arwrk);
                } else {
                    $this->cliente->EditValue = $this->cliente->CurrentValue;
                }
            }
        } else {
            $this->cliente->EditValue = null;
        }
        $this->cliente->ViewCustomAttributes = "";

        // pivote
        $this->pivote->EditAttrs["class"] = "form-control";
        $this->pivote->EditCustomAttributes = "";
        if (!$this->pivote->Raw) {
            $this->pivote->CurrentValue = HtmlDecode($this->pivote->CurrentValue);
        }
        $this->pivote->EditValue = $this->pivote->CurrentValue;
        $this->pivote->PlaceHolder = RemoveHtml($this->pivote->caption());

        // id_documento
        $this->id_documento->EditAttrs["class"] = "form-control";
        $this->id_documento->EditCustomAttributes = "";
        $curVal = trim(strval($this->id_documento->CurrentValue));
        if ($curVal != "") {
            $this->id_documento->EditValue = $this->id_documento->lookupCacheOption($curVal);
            if ($this->id_documento->EditValue === null) { // Lookup from database
                $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $lookupFilter = function() {
                    return "tipo_documento IN ('TDCFCV', 'TDCNET') AND estatus = 'NUEVO'";
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->id_documento->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->id_documento->Lookup->renderViewRow($rswrk[0]);
                    $this->id_documento->EditValue = $this->id_documento->displayValue($arwrk);
                } else {
                    $this->id_documento->EditValue = $this->id_documento->CurrentValue;
                }
            }
        } else {
            $this->id_documento->EditValue = null;
        }
        $this->id_documento->ViewCustomAttributes = "";

        // tipo_documento
        $this->tipo_documento->EditAttrs["class"] = "form-control";
        $this->tipo_documento->EditCustomAttributes = "";
        $curVal = trim(strval($this->tipo_documento->CurrentValue));
        if ($curVal != "") {
            $this->tipo_documento->EditValue = $this->tipo_documento->lookupCacheOption($curVal);
            if ($this->tipo_documento->EditValue === null) { // Lookup from database
                $filterWrk = "`codigo`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                $lookupFilter = function() {
                    return "`codigo` IN ('TDCFCV', 'TDCNET')";
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->tipo_documento->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->tipo_documento->Lookup->renderViewRow($rswrk[0]);
                    $this->tipo_documento->EditValue = $this->tipo_documento->displayValue($arwrk);
                } else {
                    $this->tipo_documento->EditValue = $this->tipo_documento->CurrentValue;
                }
            }
        } else {
            $this->tipo_documento->EditValue = null;
        }
        $this->tipo_documento->ViewCustomAttributes = "";

        // fecha
        $this->fecha->EditAttrs["class"] = "form-control";
        $this->fecha->EditCustomAttributes = "";
        $this->fecha->EditValue = FormatDateTime($this->fecha->CurrentValue, 8);
        $this->fecha->PlaceHolder = RemoveHtml($this->fecha->caption());

        // monto
        $this->monto->EditAttrs["class"] = "form-control";
        $this->monto->EditCustomAttributes = "";
        $this->monto->EditValue = $this->monto->CurrentValue;
        $this->monto->EditValue = FormatNumber($this->monto->EditValue, 2, -2, -2, -2);
        $this->monto->ViewCustomAttributes = "";

        // moneda
        $this->moneda->EditAttrs["class"] = "form-control";
        $this->moneda->EditCustomAttributes = "";
        $this->moneda->EditValue = $this->moneda->CurrentValue;
        $this->moneda->ViewCustomAttributes = "";

        // nota
        $this->nota->EditAttrs["class"] = "form-control";
        $this->nota->EditCustomAttributes = "";
        if (!$this->nota->Raw) {
            $this->nota->CurrentValue = HtmlDecode($this->nota->CurrentValue);
        }
        $this->nota->EditValue = $this->nota->CurrentValue;
        $this->nota->PlaceHolder = RemoveHtml($this->nota->caption());

        // fecha_registro
        $this->fecha_registro->EditAttrs["class"] = "form-control";
        $this->fecha_registro->EditCustomAttributes = "";
        $this->fecha_registro->EditValue = FormatDateTime($this->fecha_registro->CurrentValue, 8);
        $this->fecha_registro->PlaceHolder = RemoveHtml($this->fecha_registro->caption());

        // username
        $this->_username->EditAttrs["class"] = "form-control";
        $this->_username->EditCustomAttributes = "";
        if (!$this->_username->Raw) {
            $this->_username->CurrentValue = HtmlDecode($this->_username->CurrentValue);
        }
        $this->_username->EditValue = $this->_username->CurrentValue;
        $this->_username->PlaceHolder = RemoveHtml($this->_username->caption());

        // comprobante
        $this->comprobante->EditAttrs["class"] = "form-control";
        $this->comprobante->EditCustomAttributes = "";
        if (!$this->comprobante->Raw) {
            $this->comprobante->CurrentValue = HtmlDecode($this->comprobante->CurrentValue);
        }
        $this->comprobante->EditValue = $this->comprobante->CurrentValue;
        $this->comprobante->PlaceHolder = RemoveHtml($this->comprobante->caption());

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
                    $doc->exportCaption($this->id_documento);
                    $doc->exportCaption($this->tipo_documento);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->monto);
                    $doc->exportCaption($this->moneda);
                    $doc->exportCaption($this->nota);
                    $doc->exportCaption($this->fecha_registro);
                    $doc->exportCaption($this->_username);
                    $doc->exportCaption($this->comprobante);
                } else {
                    $doc->exportCaption($this->id);
                    $doc->exportCaption($this->cliente);
                    $doc->exportCaption($this->pivote);
                    $doc->exportCaption($this->id_documento);
                    $doc->exportCaption($this->tipo_documento);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->monto);
                    $doc->exportCaption($this->moneda);
                    $doc->exportCaption($this->nota);
                    $doc->exportCaption($this->fecha_registro);
                    $doc->exportCaption($this->_username);
                    $doc->exportCaption($this->comprobante);
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
                        $doc->exportField($this->id_documento);
                        $doc->exportField($this->tipo_documento);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->monto);
                        $doc->exportField($this->moneda);
                        $doc->exportField($this->nota);
                        $doc->exportField($this->fecha_registro);
                        $doc->exportField($this->_username);
                        $doc->exportField($this->comprobante);
                    } else {
                        $doc->exportField($this->id);
                        $doc->exportField($this->cliente);
                        $doc->exportField($this->pivote);
                        $doc->exportField($this->id_documento);
                        $doc->exportField($this->tipo_documento);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->monto);
                        $doc->exportField($this->moneda);
                        $doc->exportField($this->nota);
                        $doc->exportField($this->fecha_registro);
                        $doc->exportField($this->_username);
                        $doc->exportField($this->comprobante);
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
