<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for cobros_cliente
 */
class CobrosCliente extends DbTable
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

    // Audit trail
    public $AuditTrailOnAdd = true;
    public $AuditTrailOnEdit = true;
    public $AuditTrailOnDelete = true;
    public $AuditTrailOnView = false;
    public $AuditTrailOnViewData = false;
    public $AuditTrailOnSearch = false;

    // Export
    public $ExportDoc;

    // Fields
    public $id;
    public $cliente;
    public $id_documento;
    public $pivote;
    public $fecha;
    public $moneda;
    public $pago;
    public $nota;
    public $fecha_registro;
    public $_username;
    public $comprobante;
    public $tipo_pago;
    public $pivote2;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'cobros_cliente';
        $this->TableName = 'cobros_cliente';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`cobros_cliente`";
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
        $this->id = new DbField('cobros_cliente', 'cobros_cliente', 'x_id', 'id', '`id`', '`id`', 19, 10, -1, false, '`id`', false, false, false, 'FORMATTED TEXT', 'NO');
        $this->id->IsAutoIncrement = true; // Autoincrement field
        $this->id->IsPrimaryKey = true; // Primary key field
        $this->id->IsForeignKey = true; // Foreign key field
        $this->id->Sortable = true; // Allow sort
        $this->id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->id->Param, "CustomMsg");
        $this->Fields['id'] = &$this->id;

        // cliente
        $this->cliente = new DbField('cobros_cliente', 'cobros_cliente', 'x_cliente', 'cliente', '`cliente`', '`cliente`', 19, 10, -1, false, '`cliente`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->cliente->Nullable = false; // NOT NULL field
        $this->cliente->Required = true; // Required field
        $this->cliente->Sortable = true; // Allow sort
        $this->cliente->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->cliente->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->cliente->Lookup = new Lookup('cliente', 'cliente', false, 'id', ["nombre","","",""], [], [], [], [], [], [], '`nombre`', '');
        $this->cliente->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->cliente->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->cliente->Param, "CustomMsg");
        $this->Fields['cliente'] = &$this->cliente;

        // id_documento
        $this->id_documento = new DbField('cobros_cliente', 'cobros_cliente', 'x_id_documento', 'id_documento', '`id_documento`', '`id_documento`', 19, 10, -1, false, '`id_documento`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->id_documento->Nullable = false; // NOT NULL field
        $this->id_documento->Required = true; // Required field
        $this->id_documento->Sortable = true; // Allow sort
        $this->id_documento->Lookup = new Lookup('id_documento', 'salidas', false, 'id', ["tipo_documento","nro_documento","",""], [], [], [], [], [], [], '', '');
        $this->id_documento->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id_documento->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->id_documento->Param, "CustomMsg");
        $this->Fields['id_documento'] = &$this->id_documento;

        // pivote
        $this->pivote = new DbField('cobros_cliente', 'cobros_cliente', 'x_pivote', 'pivote', '`pivote`', '`pivote`', 200, 1, -1, false, '`pivote`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->pivote->Sortable = true; // Allow sort
        $this->pivote->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->pivote->Param, "CustomMsg");
        $this->Fields['pivote'] = &$this->pivote;

        // fecha
        $this->fecha = new DbField('cobros_cliente', 'cobros_cliente', 'x_fecha', 'fecha', '`fecha`', CastDateFieldForLike("`fecha`", 7, "DB"), 133, 10, 7, false, '`fecha`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->fecha->Required = true; // Required field
        $this->fecha->Sortable = true; // Allow sort
        $this->fecha->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_SEPARATOR"], $Language->phrase("IncorrectDateDMY"));
        $this->fecha->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->fecha->Param, "CustomMsg");
        $this->Fields['fecha'] = &$this->fecha;

        // moneda
        $this->moneda = new DbField('cobros_cliente', 'cobros_cliente', 'x_moneda', 'moneda', '`moneda`', '`moneda`', 200, 6, -1, false, '`moneda`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->moneda->Sortable = true; // Allow sort
        $this->moneda->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->moneda->Param, "CustomMsg");
        $this->Fields['moneda'] = &$this->moneda;

        // pago
        $this->pago = new DbField('cobros_cliente', 'cobros_cliente', 'x_pago', 'pago', '`pago`', '`pago`', 131, 14, -1, false, '`pago`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->pago->Sortable = true; // Allow sort
        $this->pago->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->pago->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->pago->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->pago->Param, "CustomMsg");
        $this->Fields['pago'] = &$this->pago;

        // nota
        $this->nota = new DbField('cobros_cliente', 'cobros_cliente', 'x_nota', 'nota', '`nota`', '`nota`', 200, 255, -1, false, '`nota`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->nota->Sortable = true; // Allow sort
        $this->nota->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->nota->Param, "CustomMsg");
        $this->Fields['nota'] = &$this->nota;

        // fecha_registro
        $this->fecha_registro = new DbField('cobros_cliente', 'cobros_cliente', 'x_fecha_registro', 'fecha_registro', '`fecha_registro`', CastDateFieldForLike("`fecha_registro`", 7, "DB"), 133, 10, 7, false, '`fecha_registro`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->fecha_registro->Sortable = true; // Allow sort
        $this->fecha_registro->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_SEPARATOR"], $Language->phrase("IncorrectDateDMY"));
        $this->fecha_registro->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->fecha_registro->Param, "CustomMsg");
        $this->Fields['fecha_registro'] = &$this->fecha_registro;

        // username
        $this->_username = new DbField('cobros_cliente', 'cobros_cliente', 'x__username', 'username', '`username`', '`username`', 200, 30, -1, false, '`username`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->_username->Sortable = true; // Allow sort
        $this->_username->Lookup = new Lookup('username', 'usuario', false, 'username', ["nombre","","",""], [], [], [], [], [], [], '', '');
        $this->_username->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->_username->Param, "CustomMsg");
        $this->Fields['username'] = &$this->_username;

        // comprobante
        $this->comprobante = new DbField('cobros_cliente', 'cobros_cliente', 'x_comprobante', 'comprobante', '`comprobante`', '`comprobante`', 200, 1, -1, false, '`comprobante`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->comprobante->Sortable = true; // Allow sort
        $this->comprobante->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->comprobante->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->comprobante->Param, "CustomMsg");
        $this->Fields['comprobante'] = &$this->comprobante;

        // tipo_pago
        $this->tipo_pago = new DbField('cobros_cliente', 'cobros_cliente', 'x_tipo_pago', 'tipo_pago', '`tipo_pago`', '`tipo_pago`', 200, 2, -1, false, '`tipo_pago`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->tipo_pago->Sortable = true; // Allow sort
        $this->tipo_pago->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->tipo_pago->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->tipo_pago->Lookup = new Lookup('tipo_pago', 'parametro', false, 'valor1', ["valor2","","",""], [], [], [], [], [], [], '`valor2` ASC', '');
        $this->tipo_pago->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tipo_pago->Param, "CustomMsg");
        $this->Fields['tipo_pago'] = &$this->tipo_pago;

        // pivote2
        $this->pivote2 = new DbField('cobros_cliente', 'cobros_cliente', 'x_pivote2', 'pivote2', '`pivote2`', '`pivote2`', 200, 1, -1, false, '`pivote2`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->pivote2->Sortable = true; // Allow sort
        $this->pivote2->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->pivote2->Param, "CustomMsg");
        $this->Fields['pivote2'] = &$this->pivote2;
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
            $detailUrl = "CobrosClienteList";
        }
        return $detailUrl;
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`cobros_cliente`";
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
            if ($this->AuditTrailOnAdd) {
                $this->writeAuditTrailOnAdd($rs);
            }
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
        if ($success && $this->AuditTrailOnEdit && $rsold) {
            $rsaudit = $rs;
            $fldname = 'id';
            if (!array_key_exists($fldname, $rsaudit)) {
                $rsaudit[$fldname] = $rsold[$fldname];
            }
            $this->writeAuditTrailOnEdit($rsold, $rsaudit);
        }
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
        if ($success && $this->AuditTrailOnDelete) {
            $this->writeAuditTrailOnDelete($rs);
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
        $this->id_documento->DbValue = $row['id_documento'];
        $this->pivote->DbValue = $row['pivote'];
        $this->fecha->DbValue = $row['fecha'];
        $this->moneda->DbValue = $row['moneda'];
        $this->pago->DbValue = $row['pago'];
        $this->nota->DbValue = $row['nota'];
        $this->fecha_registro->DbValue = $row['fecha_registro'];
        $this->_username->DbValue = $row['username'];
        $this->comprobante->DbValue = $row['comprobante'];
        $this->tipo_pago->DbValue = $row['tipo_pago'];
        $this->pivote2->DbValue = $row['pivote2'];
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
        return $_SESSION[$name] ?? GetUrl("CobrosClienteList");
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
        if ($pageName == "CobrosClienteView") {
            return $Language->phrase("View");
        } elseif ($pageName == "CobrosClienteEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "CobrosClienteAdd") {
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
                return "CobrosClienteView";
            case Config("API_ADD_ACTION"):
                return "CobrosClienteAdd";
            case Config("API_EDIT_ACTION"):
                return "CobrosClienteEdit";
            case Config("API_DELETE_ACTION"):
                return "CobrosClienteDelete";
            case Config("API_LIST_ACTION"):
                return "CobrosClienteList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "CobrosClienteList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("CobrosClienteView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("CobrosClienteView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "CobrosClienteAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "CobrosClienteAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("CobrosClienteEdit", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("CobrosClienteEdit", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
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
            $url = $this->keyUrl("CobrosClienteAdd", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("CobrosClienteAdd", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
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
        return $this->keyUrl("CobrosClienteDelete", $this->getUrlParm());
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
        $this->id_documento->setDbValue($row['id_documento']);
        $this->pivote->setDbValue($row['pivote']);
        $this->fecha->setDbValue($row['fecha']);
        $this->moneda->setDbValue($row['moneda']);
        $this->pago->setDbValue($row['pago']);
        $this->nota->setDbValue($row['nota']);
        $this->fecha_registro->setDbValue($row['fecha_registro']);
        $this->_username->setDbValue($row['username']);
        $this->comprobante->setDbValue($row['comprobante']);
        $this->tipo_pago->setDbValue($row['tipo_pago']);
        $this->pivote2->setDbValue($row['pivote2']);
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

        // id_documento

        // pivote

        // fecha

        // moneda

        // pago

        // nota

        // fecha_registro

        // username

        // comprobante

        // tipo_pago

        // pivote2

        // id
        $this->id->ViewValue = $this->id->CurrentValue;
        $this->id->ViewCustomAttributes = "";

        // cliente
        $curVal = trim(strval($this->cliente->CurrentValue));
        if ($curVal != "") {
            $this->cliente->ViewValue = $this->cliente->lookupCacheOption($curVal);
            if ($this->cliente->ViewValue === null) { // Lookup from database
                $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $lookupFilter = function() {
                    return (CurrentPageID() == "add") ? 
        	"`id` IN 
        		(SELECT cliente 
        		FROM view_x_cobrar 
        		WHERE IFNULL(monto_pagar, 0) > 0 AND  
        		IFNULL(monto_pagar, 0) > (IFNULL(monto_pagado, 0) + IFNULL(retivamonto, 0) + IFNULL(retislrmonto, 0)) AND fecha > '2021-07-31')" : "";
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

        // id_documento
        $this->id_documento->ViewValue = $this->id_documento->CurrentValue;
        $curVal = trim(strval($this->id_documento->CurrentValue));
        if ($curVal != "") {
            $this->id_documento->ViewValue = $this->id_documento->lookupCacheOption($curVal);
            if ($this->id_documento->ViewValue === null) { // Lookup from database
                $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $sqlWrk = $this->id_documento->Lookup->getSql(false, $filterWrk, '', $this, true, true);
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

        // pivote
        $this->pivote->ViewValue = $this->pivote->CurrentValue;
        $this->pivote->ViewCustomAttributes = "";

        // fecha
        $this->fecha->ViewValue = $this->fecha->CurrentValue;
        $this->fecha->ViewValue = FormatDateTime($this->fecha->ViewValue, 7);
        $this->fecha->ViewCustomAttributes = "";

        // moneda
        $this->moneda->ViewValue = $this->moneda->CurrentValue;
        $this->moneda->ViewCustomAttributes = "";

        // pago
        $this->pago->ViewValue = $this->pago->CurrentValue;
        $this->pago->ViewValue = FormatNumber($this->pago->ViewValue, 2, -1, -1, -1);
        $this->pago->ViewCustomAttributes = "";

        // nota
        $this->nota->ViewValue = $this->nota->CurrentValue;
        $this->nota->ViewCustomAttributes = "";

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
        $this->comprobante->ViewValue = $this->comprobante->CurrentValue;
        $this->comprobante->ViewCustomAttributes = "";

        // tipo_pago
        $curVal = trim(strval($this->tipo_pago->CurrentValue));
        if ($curVal != "") {
            $this->tipo_pago->ViewValue = $this->tipo_pago->lookupCacheOption($curVal);
            if ($this->tipo_pago->ViewValue === null) { // Lookup from database
                $filterWrk = "`valor1`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                $lookupFilter = function() {
                    return ($_REQUEST["pago_divisa"] ?? "N" == "S") 
            ? "`codigo` = '009' AND valor1 IN ('EF','RD','ZL')" 
            : (isset($_REQUEST["dsc"]) 
                ? ($_REQUEST["dsc"] >= 25 
                    ? "`codigo` = '009' AND valor1 IN ('EF','RD')" 
                    : "`codigo` = '009' AND valor1 NOT IN ('PC','PF','DV','NC','ND')") 
                : "`codigo` = '009' AND valor1 NOT IN ('PC','PF','DV','NC','ND')");
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->tipo_pago->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->tipo_pago->Lookup->renderViewRow($rswrk[0]);
                    $this->tipo_pago->ViewValue = $this->tipo_pago->displayValue($arwrk);
                } else {
                    $this->tipo_pago->ViewValue = $this->tipo_pago->CurrentValue;
                }
            }
        } else {
            $this->tipo_pago->ViewValue = null;
        }
        $this->tipo_pago->ViewCustomAttributes = "";

        // pivote2
        $this->pivote2->ViewValue = $this->pivote2->CurrentValue;
        $this->pivote2->ViewCustomAttributes = "";

        // id
        $this->id->LinkCustomAttributes = "";
        $this->id->HrefValue = "";
        $this->id->TooltipValue = "";

        // cliente
        $this->cliente->LinkCustomAttributes = "";
        $this->cliente->HrefValue = "";
        $this->cliente->TooltipValue = "";

        // id_documento
        $this->id_documento->LinkCustomAttributes = "";
        $this->id_documento->HrefValue = "";
        $this->id_documento->TooltipValue = "";

        // pivote
        $this->pivote->LinkCustomAttributes = "";
        $this->pivote->HrefValue = "";
        $this->pivote->TooltipValue = "";

        // fecha
        $this->fecha->LinkCustomAttributes = "";
        $this->fecha->HrefValue = "";
        $this->fecha->TooltipValue = "";

        // moneda
        $this->moneda->LinkCustomAttributes = "";
        $this->moneda->HrefValue = "";
        $this->moneda->TooltipValue = "";

        // pago
        $this->pago->LinkCustomAttributes = "";
        $this->pago->HrefValue = "";
        $this->pago->TooltipValue = "";

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
        if (!EmptyValue($this->comprobante->CurrentValue)) {
            $this->comprobante->HrefValue = "ContAsientoList?showmaster=cont_comprobante&fk_id=" . $this->comprobante->CurrentValue; // Add prefix/suffix
            $this->comprobante->LinkAttrs["target"] = "_blank"; // Add target
            if ($this->isExport()) {
                $this->comprobante->HrefValue = FullUrl($this->comprobante->HrefValue, "href");
            }
        } else {
            $this->comprobante->HrefValue = "";
        }
        $this->comprobante->TooltipValue = "";

        // tipo_pago
        $this->tipo_pago->LinkCustomAttributes = "";
        $this->tipo_pago->HrefValue = "";
        $this->tipo_pago->TooltipValue = "";

        // pivote2
        $this->pivote2->LinkCustomAttributes = "";
        $this->pivote2->HrefValue = "";
        $this->pivote2->TooltipValue = "";

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
        $curVal = trim(strval($this->cliente->CurrentValue));
        if ($curVal != "") {
            $this->cliente->EditValue = $this->cliente->lookupCacheOption($curVal);
            if ($this->cliente->EditValue === null) { // Lookup from database
                $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $lookupFilter = function() {
                    return (CurrentPageID() == "add") ? 
        	"`id` IN 
        		(SELECT cliente 
        		FROM view_x_cobrar 
        		WHERE IFNULL(monto_pagar, 0) > 0 AND  
        		IFNULL(monto_pagar, 0) > (IFNULL(monto_pagado, 0) + IFNULL(retivamonto, 0) + IFNULL(retislrmonto, 0)) AND fecha > '2021-07-31')" : "";
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

        // id_documento
        $this->id_documento->EditAttrs["class"] = "form-control";
        $this->id_documento->EditCustomAttributes = "";
        $this->id_documento->EditValue = $this->id_documento->CurrentValue;
        $curVal = trim(strval($this->id_documento->CurrentValue));
        if ($curVal != "") {
            $this->id_documento->EditValue = $this->id_documento->lookupCacheOption($curVal);
            if ($this->id_documento->EditValue === null) { // Lookup from database
                $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $sqlWrk = $this->id_documento->Lookup->getSql(false, $filterWrk, '', $this, true, true);
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

        // pivote
        $this->pivote->EditAttrs["class"] = "form-control";
        $this->pivote->EditCustomAttributes = "";
        if (!$this->pivote->Raw) {
            $this->pivote->CurrentValue = HtmlDecode($this->pivote->CurrentValue);
        }
        $this->pivote->EditValue = $this->pivote->CurrentValue;
        $this->pivote->PlaceHolder = RemoveHtml($this->pivote->caption());

        // fecha
        $this->fecha->EditAttrs["class"] = "form-control";
        $this->fecha->EditCustomAttributes = "";
        $this->fecha->EditValue = $this->fecha->CurrentValue;
        $this->fecha->EditValue = FormatDateTime($this->fecha->EditValue, 7);
        $this->fecha->ViewCustomAttributes = "";

        // moneda
        $this->moneda->EditAttrs["class"] = "form-control";
        $this->moneda->EditCustomAttributes = "";
        $this->moneda->EditValue = $this->moneda->CurrentValue;
        $this->moneda->ViewCustomAttributes = "";

        // pago
        $this->pago->EditAttrs["class"] = "form-control";
        $this->pago->EditCustomAttributes = "";
        $this->pago->EditValue = $this->pago->CurrentValue;
        $this->pago->EditValue = FormatNumber($this->pago->EditValue, 2, -1, -1, -1);
        $this->pago->ViewCustomAttributes = "";

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
        $this->fecha_registro->EditValue = FormatDateTime($this->fecha_registro->CurrentValue, 7);
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

        // tipo_pago
        $this->tipo_pago->EditAttrs["class"] = "form-control";
        $this->tipo_pago->EditCustomAttributes = "";
        $this->tipo_pago->PlaceHolder = RemoveHtml($this->tipo_pago->caption());

        // pivote2
        $this->pivote2->EditAttrs["class"] = "form-control";
        $this->pivote2->EditCustomAttributes = "";
        if (!$this->pivote2->Raw) {
            $this->pivote2->CurrentValue = HtmlDecode($this->pivote2->CurrentValue);
        }
        $this->pivote2->EditValue = $this->pivote2->CurrentValue;
        $this->pivote2->PlaceHolder = RemoveHtml($this->pivote2->caption());

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
                    $doc->exportCaption($this->cliente);
                    $doc->exportCaption($this->id_documento);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->moneda);
                    $doc->exportCaption($this->pago);
                    $doc->exportCaption($this->nota);
                    $doc->exportCaption($this->fecha_registro);
                    $doc->exportCaption($this->_username);
                } else {
                    $doc->exportCaption($this->id);
                    $doc->exportCaption($this->cliente);
                    $doc->exportCaption($this->id_documento);
                    $doc->exportCaption($this->pivote);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->moneda);
                    $doc->exportCaption($this->pago);
                    $doc->exportCaption($this->nota);
                    $doc->exportCaption($this->fecha_registro);
                    $doc->exportCaption($this->_username);
                    $doc->exportCaption($this->comprobante);
                    $doc->exportCaption($this->tipo_pago);
                    $doc->exportCaption($this->pivote2);
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
                        $doc->exportField($this->cliente);
                        $doc->exportField($this->id_documento);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->moneda);
                        $doc->exportField($this->pago);
                        $doc->exportField($this->nota);
                        $doc->exportField($this->fecha_registro);
                        $doc->exportField($this->_username);
                    } else {
                        $doc->exportField($this->id);
                        $doc->exportField($this->cliente);
                        $doc->exportField($this->id_documento);
                        $doc->exportField($this->pivote);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->moneda);
                        $doc->exportField($this->pago);
                        $doc->exportField($this->nota);
                        $doc->exportField($this->fecha_registro);
                        $doc->exportField($this->_username);
                        $doc->exportField($this->comprobante);
                        $doc->exportField($this->tipo_pago);
                        $doc->exportField($this->pivote2);
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

    // Write Audit Trail start/end for grid update
    public function writeAuditTrailDummy($typ)
    {
        $table = 'cobros_cliente';
        $usr = CurrentUserName();
        WriteAuditLog($usr, $typ, $table, "", "", "", "");
    }

    // Write Audit Trail (add page)
    public function writeAuditTrailOnAdd(&$rs)
    {
        global $Language;
        if (!$this->AuditTrailOnAdd) {
            return;
        }
        $table = 'cobros_cliente';

        // Get key value
        $key = "";
        if ($key != "") {
            $key .= Config("COMPOSITE_KEY_SEPARATOR");
        }
        $key .= $rs['id'];

        // Write Audit Trail
        $usr = CurrentUserName();
        foreach (array_keys($rs) as $fldname) {
            if (array_key_exists($fldname, $this->Fields) && $this->Fields[$fldname]->DataType != DATATYPE_BLOB) { // Ignore BLOB fields
                if ($this->Fields[$fldname]->HtmlTag == "PASSWORD") {
                    $newvalue = $Language->phrase("PasswordMask"); // Password Field
                } elseif ($this->Fields[$fldname]->DataType == DATATYPE_MEMO) {
                    if (Config("AUDIT_TRAIL_TO_DATABASE")) {
                        $newvalue = $rs[$fldname];
                    } else {
                        $newvalue = "[MEMO]"; // Memo Field
                    }
                } elseif ($this->Fields[$fldname]->DataType == DATATYPE_XML) {
                    $newvalue = "[XML]"; // XML Field
                } else {
                    $newvalue = $rs[$fldname];
                }
                WriteAuditLog($usr, "A", $table, $fldname, $key, "", $newvalue);
            }
        }
    }

    // Write Audit Trail (edit page)
    public function writeAuditTrailOnEdit(&$rsold, &$rsnew)
    {
        global $Language;
        if (!$this->AuditTrailOnEdit) {
            return;
        }
        $table = 'cobros_cliente';

        // Get key value
        $key = "";
        if ($key != "") {
            $key .= Config("COMPOSITE_KEY_SEPARATOR");
        }
        $key .= $rsold['id'];

        // Write Audit Trail
        $usr = CurrentUserName();
        foreach (array_keys($rsnew) as $fldname) {
            if (array_key_exists($fldname, $this->Fields) && array_key_exists($fldname, $rsold) && $this->Fields[$fldname]->DataType != DATATYPE_BLOB) { // Ignore BLOB fields
                if ($this->Fields[$fldname]->DataType == DATATYPE_DATE) { // DateTime field
                    $modified = (FormatDateTime($rsold[$fldname], 0) != FormatDateTime($rsnew[$fldname], 0));
                } else {
                    $modified = !CompareValue($rsold[$fldname], $rsnew[$fldname]);
                }
                if ($modified) {
                    if ($this->Fields[$fldname]->HtmlTag == "PASSWORD") { // Password Field
                        $oldvalue = $Language->phrase("PasswordMask");
                        $newvalue = $Language->phrase("PasswordMask");
                    } elseif ($this->Fields[$fldname]->DataType == DATATYPE_MEMO) { // Memo field
                        if (Config("AUDIT_TRAIL_TO_DATABASE")) {
                            $oldvalue = $rsold[$fldname];
                            $newvalue = $rsnew[$fldname];
                        } else {
                            $oldvalue = "[MEMO]";
                            $newvalue = "[MEMO]";
                        }
                    } elseif ($this->Fields[$fldname]->DataType == DATATYPE_XML) { // XML field
                        $oldvalue = "[XML]";
                        $newvalue = "[XML]";
                    } else {
                        $oldvalue = $rsold[$fldname];
                        $newvalue = $rsnew[$fldname];
                    }
                    WriteAuditLog($usr, "U", $table, $fldname, $key, $oldvalue, $newvalue);
                }
            }
        }
    }

    // Write Audit Trail (delete page)
    public function writeAuditTrailOnDelete(&$rs)
    {
        global $Language;
        if (!$this->AuditTrailOnDelete) {
            return;
        }
        $table = 'cobros_cliente';

        // Get key value
        $key = "";
        if ($key != "") {
            $key .= Config("COMPOSITE_KEY_SEPARATOR");
        }
        $key .= $rs['id'];

        // Write Audit Trail
        $curUser = CurrentUserName();
        foreach (array_keys($rs) as $fldname) {
            if (array_key_exists($fldname, $this->Fields) && $this->Fields[$fldname]->DataType != DATATYPE_BLOB) { // Ignore BLOB fields
                if ($this->Fields[$fldname]->HtmlTag == "PASSWORD") {
                    $oldvalue = $Language->phrase("PasswordMask"); // Password Field
                } elseif ($this->Fields[$fldname]->DataType == DATATYPE_MEMO) {
                    if (Config("AUDIT_TRAIL_TO_DATABASE")) {
                        $oldvalue = $rs[$fldname];
                    } else {
                        $oldvalue = "[MEMO]"; // Memo field
                    }
                } elseif ($this->Fields[$fldname]->DataType == DATATYPE_XML) {
                    $oldvalue = "[XML]"; // XML field
                } else {
                    $oldvalue = $rs[$fldname];
                }
                WriteAuditLog($curUser, "D", $table, $fldname, $key, $oldvalue, "");
            }
        }
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
    	$fecha = date("Y-m-d");
    	$sql = "SELECT fecha FROM cierre_de_caja WHERE fecha = '$fecha';";
    	if($row = ExecuteRow($sql)) {
    		$this->CancelMessage = "El d&iacute;a " . date("d/m/Y") . " est&aacute; cerrado; no se puede agregar el pago. Verifique!";
    		return FALSE;
    	}
        $sw = false;
    	if(isset($_POST["xCantidad"])) {
    		$cnt = intval($_POST["xCantidad"]);
    		for($i=0; $i<$cnt; $i++) {
    			if(isset($_POST["x_id_$i"])) { 
                    $_id = explode("-", $_POST["x_id_$i"]);
                    $sw = true;
                    break;
    			}
    		}
    		if($sw == false) {
    			$this->CancelMessage = "Debe seleccionar la factura a cobrar.";
    			return FALSE;
    		}
            $rsnew["id_documento"] = $_id[0];
            $rsnew["tipo_pago"] = "";
            $pagos = $_POST["pagos"];
            $abono = floatval($_POST["abono"]);
            $monto = floatval($_POST["monto"]);
            $pago = floatval($rsnew["pago"]);

            // Valido que no se haya procesado el pago
            $sql = "SELECT estatus FROM salidas WHERE id = " . intval($rsnew["id_documento"]) . ";";
            $estatus = ExecuteScalar($sql);
            if($estatus != "NUEVO") {
    			$this->CancelMessage = "El documento tiene pagos asociados o est&aacute; anulado. Revise el cierre de caja.";
    			return FALSE;
    		}
           if($pago == 0.00 or $monto == 0.00) {
                // $this->CancelMessage = "No hay informaci&oacute;n de pago.";
                // return FALSE;
            }
            if(trim(str_replace(",-,", "", $pagos)) == "") {
                $this->CancelMessage = "No hay datos de pagos.";
                return FALSE;
            }            
            if($abono > 0.00) {
                $sql = "SELECT saldo FROM recarga WHERE cliente = " . $rsnew["cliente"] . " ORDER BY id DESC LIMIT 0, 1;";
                $saldo = floatval(ExecuteScalar($sql));
                $sql = "SELECT saldo FROM recarga2 WHERE cliente = " . $rsnew["cliente"] . " ORDER BY id DESC LIMIT 0, 1;";
                $saldo += floatval(ExecuteScalar($sql));
                if($abono > $saldo) {
                    $this->CancelMessage = "Sanara sin fondo suficiente.";
                    return FALSE;
                } 
            }
            if($pago < $monto) {
                $this->CancelMessage = "Pago incompleto.";
                return FALSE;
            }
            $rsnew["fecha"] = date("Y-m-d");
    		$rsnew["fecha_registro"] = date("Y-m-d");
    		$rsnew["username"] = CurrentUserName();
    	} 
    	else {
    		$this->CancelMessage = "No ha seleccionado facturas a cobrar.";
    		return FALSE;
    	}
    	return TRUE;
    }

    // Row Inserted event
    public function rowInserted($rsold, &$rsnew) {
    	//echo "Row Inserted"
    	$arr = explode(",-,", $_POST["pagos"]);
        $VueltosUSD = true;
      	foreach ($arr as $key => $value) {
      		if(trim($value) != "") {
      			$arr2 = explode("|", $value);	
      			if(count($arr2) > 2) {
      				$sql = "";
      				$documentos = "";
       				$sql = "SELECT nro_documento, tipo_documento 
       						FROM salidas 
       						WHERE id = " . $rsnew["id_documento"] . "";
       				$row = ExecuteRow($sql);
       				$documentos = ($row["tipo_documento"]=="TDCFCV" ? "FACT: " : "N. E.: ") . $row["nro_documento"] . "";
    		  		$monto_moneda = floatval($arr2[4]);
    				$sql = "SELECT tasa FROM tasa_usd
    						WHERE moneda = '" . $arr2[5] . "' ORDER BY id DESC LIMIT 0, 1;";
    				$tasa = ExecuteScalar($sql);
    				$monto_bs = $tasa * $monto_moneda;
    		    	$sql = "SELECT tasa FROM tasa_usd
    		    			WHERE moneda = 'USD' ORDER BY id DESC LIMIT 0, 1;";
    		    	$tasa_usd = ExecuteScalar($sql);
    		    	$monto_usd = $monto_bs / $tasa_usd;
    		    	$username = CurrentUserName();
    		  		$sql = "INSERT INTO cobros_cliente_detalle (
    		  					id,
    		  					cobros_cliente,
    		  					metodo_pago,
    		  					referencia,
    		  					monto_moneda,
    		  					moneda,
    		  					tasa_moneda,
    		  					monto_bs,
    		  					tasa_usd,
    		  					monto_usd,
    		  					banco)
    		  				VALUES (
    		  					NULL,
    		  					" . $rsnew["id"] . ",
    		  					'" . $arr2[1] . "',
    		  					'" . $arr2[2] . "',
    		  					$monto_moneda,
    		  					'" . $arr2[5] . "',
    		  					$tasa,
    		  					$monto_bs,
    		  					$tasa_usd,
    		  					$monto_usd,
    		  					NULL
    		  					)"; 
    		  		Execute($sql);
                    if(substr(trim(strtoupper($arr2[5])), 0, 2) == "BS") $VueltosUSD = false;
    		  		if($arr2[1] == "RC") {
    		  			$sql = "SELECT IFNULL(MAX(nro_recibo), 0)+1 AS nro FROM abono WHERE 1;";
    		  			$nro_recibo = 0; //ExecuteScalar($sql);
    		  			$sql = "INSERT INTO
    		  						abono 
    		  					SET 	
    		  						id = NULL,
    		  						cliente = " . $rsnew["cliente"] . ",
    		  						fecha = NOW(),
    		  						metodo_pago = NULL,
    		  						nro_recibo = $nro_recibo,
    		  						nota = 'REBAJA EN COBROS Documento: $documentos',
    		  						username = '" . CurrentUserName() . "';";
    					Execute($sql);
    					$sql = "SELECT LAST_INSERT_ID();";
    					$Abono = ExecuteScalar($sql);
    					$sql = "INSERT INTO recarga(
    								id,
    								cliente,
    								fecha,
    								metodo_pago,
    								monto_moneda,
    								moneda,
    								tasa_moneda,
    								monto_bs,
    								tasa_usd,
    								monto_usd,
    								saldo,
    								nota,
    								username, reverso, abono)
    							VALUES (
    								NULL,
    								" . $rsnew["cliente"] . ",
    								NOW(),
    								'" . $arr2[1] . "',
    								$monto_moneda,
    								'" . $arr2[5] . "',
    								$tasa,
    								$monto_bs,
    								$tasa_usd,
    								(-1)*$monto_usd,
    								0,
    								'Pago Documento(s): $documentos',
    								'$username', 'N', $Abono)";
    					Execute($sql);
    					$sql = "SELECT LAST_INSERT_ID();";
    					$id = ExecuteScalar($sql);
    					$sql = "SELECT IFNULL(SUM(monto_usd), 0) AS saldo FROM recarga
    			    			WHERE cliente = " . $rsnew["cliente"] . ";";
    			    	$saldo = ExecuteScalar($sql);
    			    	$sql = "UPDATE recarga SET saldo = $saldo WHERE id = $id;";
    			    	Execute($sql);
    			    	$sql = "SELECT SUM(monto_usd) AS pago FROM recarga WHERE abono = $Abono;";
    			    	$monto_abono = ExecuteScalar($sql);
    			    	$sql = "UPDATE abono SET pago = $monto_abono WHERE id = $Abono";
    			    	Execute($sql);
    		  		}

    		  		/////////////////////////////
    		  		if($arr2[1] == "RD") {
    		  			$sql = "SELECT IFNULL(MAX(nro_recibo), 0)+1 AS nro FROM abono2 WHERE 1;";
    		  			$nro_recibo = 0; //ExecuteScalar($sql);
    		  			$sql = "INSERT INTO
    		  						abono2 
    		  					SET 	
    		  						id = NULL,
    		  						cliente = " . $rsnew["cliente"] . ",
    		  						fecha = NOW(),
    		  						metodo_pago = NULL,
    		  						nro_recibo = $nro_recibo,
    		  						nota = 'REBAJA EN COBROS Documento: $documentos',
    		  						username = '" . CurrentUserName() . "';";
    					Execute($sql);
    					$sql = "SELECT LAST_INSERT_ID();";
    					$Abono = ExecuteScalar($sql);
    					$sql = "INSERT INTO recarga2(
    								id,
    								cliente,
    								fecha,
    								metodo_pago,
    								monto_moneda,
    								moneda,
    								tasa_moneda,
    								monto_bs,
    								tasa_usd,
    								monto_usd,
    								saldo,
    								nota,
    								username, reverso, abono)
    							VALUES (
    								NULL,
    								" . $rsnew["cliente"] . ",
    								NOW(),
    								'" . $arr2[1] . "',
    								$monto_moneda,
    								'" . $arr2[5] . "',
    								$tasa,
    								$monto_bs,
    								$tasa_usd,
    								(-1)*$monto_usd,
    								0,
    								'Pago Documento(s): $documentos',
    								'$username', 'N', $Abono)";
    					Execute($sql);
    					$sql = "SELECT LAST_INSERT_ID();";
    					$id = ExecuteScalar($sql);
    					$sql = "SELECT IFNULL(SUM(monto_usd), 0) AS saldo FROM recarga2
    			    			WHERE cliente = " . $rsnew["cliente"] . ";";
    			    	$saldo = ExecuteScalar($sql);
    			    	$sql = "UPDATE recarga2 SET saldo = $saldo WHERE id = $id;";
    			    	Execute($sql);
    			    	$sql = "SELECT SUM(monto_usd) AS pago FROM recarga2 WHERE abono = $Abono;";
    			    	$monto_abono = ExecuteScalar($sql);
    			    	$sql = "UPDATE abono2 SET pago = $monto_abono WHERE id = $Abono";
    			    	Execute($sql);
    		  		}
      			}
      		}
      	 } 
    	$sql = "UPDATE salidas SET estatus = 'PROCESADO'
    			WHERE id = " . $rsnew["id_documento"] . ";";
    	ExecuteRow($sql);
        if($VueltosUSD) {
        	// Si hay diferencia a favor, la agregó a recargas
        	// A recargas en Abonos en $ el día 01-04-2025
        	$monto = floatval($_POST["monto"]);
        	$pago = floatval($rsnew["pago"]);
            if($pago > $monto) {
        		$sql = "SELECT IFNULL(MAX(nro_recibo), 0)+1 AS nro FROM abono2 WHERE 1;";
        		$nro_recibo = ExecuteScalar($sql);
        		$sql = "INSERT INTO
        					abono2 
        				SET 	
        					id = NULL,
        					cliente = " . $rsnew["cliente"] . ",
        					fecha = NOW(),
        					metodo_pago = NULL,
        					nro_recibo = $nro_recibo,
        					nota = 'SOBRANTE EN COBROS Documento: $documentos',
        					username = '" . CurrentUserName() . "';";
        		Execute($sql);
        		$sql = "SELECT LAST_INSERT_ID();";
        		$Abono = ExecuteScalar($sql);
            	$sql = "SELECT IFNULL(MAX(nro_recibo), 0)+1 AS nro FROM recarga2 WHERE 1;";
           		$nro_recibo = ExecuteScalar($sql);
            	$monto_moneda = $pago - $monto;
            	$monto_bs = $tasa_usd * $monto_moneda;
            	$sql = "INSERT INTO recarga2(
        					id,
        					cliente,
        					fecha,
        					metodo_pago,
        					monto_moneda,
        					moneda,
        					tasa_moneda,
        					monto_bs,
        					tasa_usd,
        					monto_usd,
        					saldo,
        					nota,
        					username, cobro_cliente_reverso, nro_recibo, reverso, abono)
        				VALUES (
        					NULL,
        					" . $rsnew["cliente"] . ",
        					NOW(),
        					'RD',
        					$monto_moneda,
        					'" . $rsnew["moneda"] . "',
        					$tasa_usd,
        					$monto_bs,
        					$tasa_usd,
        					$monto_moneda,
        					0,
        					'Recarga por exedente en pago de documento(s): $documentos',
        					'$username', " . $rsnew["id"] . ", $nro_recibo, 'N', $Abono)";
        		Execute($sql);
        		$sql = "SELECT LAST_INSERT_ID();";
        		$id = ExecuteScalar($sql);
        		$sql = "SELECT IFNULL(SUM(monto_usd), 0) AS saldo FROM recarga2
            			WHERE cliente = " . $rsnew["cliente"] . ";";
               	$saldo = ExecuteScalar($sql);
        	   	$sql = "UPDATE recarga2 SET saldo = $saldo WHERE id = $id;";
        	   	Execute($sql);
            	$sql = "SELECT SUM(monto_usd) AS pago FROM recarga2 WHERE abono = $Abono;";
            	$monto_abono = ExecuteScalar($sql);
            	$sql = "UPDATE abono2 SET pago = $monto_abono WHERE id = $Abono";
            	Execute($sql);
            }
        } 
        else {
            // Si hay diferencia a favor, la agregó a recargas
            // A recargas en Abonos en Bs el día 05-04-2025
            $monto = floatval($_POST["monto"]);
            $pago = floatval($rsnew["pago"]);
            if($pago > $monto) {
                $sql = "SELECT IFNULL(MAX(nro_recibo), 0)+1 AS nro FROM abono WHERE 1;";
                $nro_recibo = ExecuteScalar($sql);
                $sql = "INSERT INTO
                            abono 
                        SET     
                            id = NULL,
                            cliente = " . $rsnew["cliente"] . ",
                            fecha = NOW(),
                            metodo_pago = NULL,
                            nro_recibo = $nro_recibo,
                            nota = 'SOBRANTE EN COBROS Documento: $documentos',
                            username = '" . CurrentUserName() . "';";
                Execute($sql);
                $sql = "SELECT LAST_INSERT_ID();";
                $Abono = ExecuteScalar($sql);
                $sql = "SELECT IFNULL(MAX(nro_recibo), 0)+1 AS nro FROM recarga WHERE 1;";
                $nro_recibo = ExecuteScalar($sql);
                $monto_moneda = $pago - $monto;
                $monto_bs = $tasa_usd * $monto_moneda;
                $sql = "INSERT INTO recarga(
                            id,
                            cliente,
                            fecha,
                            metodo_pago,
                            monto_moneda,
                            moneda,
                            tasa_moneda,
                            monto_bs,
                            tasa_usd,
                            monto_usd,
                            saldo,
                            nota,
                            username, cobro_cliente_reverso, nro_recibo, reverso, abono)
                        VALUES (
                            NULL,
                            " . $rsnew["cliente"] . ",
                            NOW(),
                            'RC',
                            $monto_moneda,
                            '" . $rsnew["moneda"] . "',
                            $tasa_usd,
                            $monto_bs,
                            $tasa_usd,
                            $monto_moneda,
                            0,
                            'Recarga por exedente en pago de documento(s): $documentos',
                            '$username', " . $rsnew["id"] . ", $nro_recibo, 'N', $Abono)";
                Execute($sql);
                $sql = "SELECT LAST_INSERT_ID();";
                $id = ExecuteScalar($sql);
                $sql = "SELECT IFNULL(SUM(monto_usd), 0) AS saldo FROM recarga
                        WHERE cliente = " . $rsnew["cliente"] . ";";
                $saldo = ExecuteScalar($sql);
                $sql = "UPDATE recarga SET saldo = $saldo WHERE id = $id;";
                Execute($sql);
                $sql = "SELECT SUM(monto_usd) AS pago FROM recarga WHERE abono = $Abono;";
                $monto_abono = ExecuteScalar($sql);
                $sql = "UPDATE abono SET pago = $monto_abono WHERE id = $Abono";
                Execute($sql);
            }
        }

    	/* ------- Actualizo cantidad en mano, en pedido y en transito  ------- */
    	$sql = "SELECT nro_documento, tipo_documento 
    			FROM salidas 
       			WHERE id = " . $rsnew["id_documento"] . "";
       	$row = ExecuteRow($sql);
       	$documentos = $row["tipo_documento"];
    	$sql = "SELECT COUNT(articulo) AS cantidad 
    			FROM entradas_salidas
    			WHERE tipo_documento = '$documentos'
    				AND id_documento = " . $rsnew["id_documento"] . ";";
    	$cantidad = ExecuteScalar($sql);
    	for($i = 0; $i < $cantidad; $i++) {
    		$sql = "SELECT articulo
    				FROM entradas_salidas
    				WHERE
    					tipo_documento = '$documentos'
    					AND id_documento = " . $rsnew["id_documento"] . " LIMIT $i, 1;";
    		$articulo = ExecuteScalar($sql);
    		ActualizarExitenciaArticulo($articulo);
    	}

    	/*** Se evalua si se cargan puntos por la venta de cada articulo en la nota ***/
    	$sql = "SELECT valor1 FROM parametro WHERE codigo = '055';";
    	$si_activo = ExecuteScalar($sql);
    	if($si_activo == "S") {
    		$sql = "SELECT nro_documento FROM salidas WHERE tipo_documento = 'TDCNET' AND id = " . $rsnew["id_documento"] . ";";
    		$nro_documento = ExecuteScalar($sql);
    		$sql = "SELECT 
    					a.articulo, 
    					b.codigo_ims,
    					ABS(a.cantidad_movimiento) AS cantidad_movimiento, 
    					b.puntos_ventas  
    				FROM 
    					entradas_salidas a 
    					JOIN articulo AS b ON b.id = a.articulo 
    				WHERE 
    					a.tipo_documento = 'TDCNET' AND a.id_documento = " . $rsnew["id_documento"] . " 
    					AND IFNULL(b.puntos_ventas, 0) > 0;";
    		$rows = ExecuteRows($sql);	
    		foreach ($rows as $key => $value) {
    			$cliente = $rsnew["cliente"];
    			$ref = $value["codigo_ims"];
    			$puntos = intval($value["puntos_ventas"])*intval($value["cantidad_movimiento"]);
    			$sql = "INSERT INTO puntos
    						(id, cliente, fecha, tipo, nro_documento, referencia, puntos, saldo, nota, username)
    					VALUES
    						(NULL, $cliente, '" . date("Y-m-d") . "', 'NE', '$nro_documento', '$ref', $puntos, 0, 'Puntos por Referencia " . intval($value["puntos_ventas"]) . " por Cantidad " . intval($value["cantidad_movimiento"]) . " igual a $puntos puntos', '" . CurrentUserName() . "')";
    			Execute($sql);
    			$sql = "SELECT IFNULL(SUM(puntos), 0) AS saldo FROM puntos
    					WHERE cliente = $cliente;";
    			$saldo = ExecuteScalar($sql);
    			$sql = "SELECT LAST_INSERT_ID() AS id;";
    			$id = ExecuteScalar($sql);
    			$sql = "UPDATE puntos SET saldo = $saldo WHERE id = $id;";
    			Execute($sql);		
    		}
    	}
    	////////////////////////////////////////////////////////////////////////////////

    	/*** Asigno puntos si es referido y no se le ha dado puntos antes por referido ***/
    	$cliente = $rsnew["cliente"];
    	$ref = "";
    	$sql = "SELECT
    				IFNULL(refiere, 0) AS refiere, IFNULL(puntos_refiere, 'N') AS puntos_refiere
    			FROM cliente WHERE id = $cliente;";
    	if($row = ExecuteRow($sql)) {
    		if($row["refiere"] > 0 and $row["puntos_refiere"] == "N") {
    			$pago = floatval($rsnew["pago"]);
    			if($pago >= 50) {
    				$referido = $row["refiere"];
    				$elQueCompra = ExecuteScalar("SELECT CONCAT(nombre, ' (', ci_rif, ')') AS cliente FROM cliente WHERE id = $cliente;");
    				$puntos = 10;
    				$sql = "SELECT nro_documento FROM salidas WHERE tipo_documento = 'TDCNET' AND id = " . $rsnew["id_documento"] . ";";
    				$nro_documento = ExecuteScalar($sql);
    				$sql = "INSERT INTO puntos
    						(id, cliente, fecha, tipo, nro_documento, referencia, puntos, saldo, nota, username)
    					VALUES
    						(NULL, $referido, '" . date("Y-m-d") . "', 'NE', '$nro_documento', '$ref', $puntos, 0, 'Puntos por Referir a $elQueCompra', '" . CurrentUserName() . "')";
    				Execute($sql);
    				$sql = "SELECT IFNULL(SUM(puntos), 0) AS saldo FROM puntos
    					WHERE cliente = $referido;";
    				$saldo = ExecuteScalar($sql);
    				$sql = "SELECT LAST_INSERT_ID() AS id;";
    				$id = ExecuteScalar($sql);
    				$sql = "UPDATE puntos SET saldo = $saldo WHERE id = $id;";
    				Execute($sql);
    				$sql = "UPDATE cliente SET puntos_refiere = 'S' WHERE id = $cliente;";
    				Execute($sql);
    			}
    		}
    	}
    	/////////

    	/*** Se hace uso del API para registrar las ventas en el servidor ***/
    	// Obtenemos los datos recién insertados en la tienda local
        $tienda = ExecuteScalar("SELECT valor2 AS tienda FROM parametro WHERE codigo = '048';"); 
        $fecha = $rsnew["fecha"];
    	$sql = "SELECT nro_documento, total, IFNULL(pago_premio, 'N') AS pago_premio FROM salidas WHERE tipo_documento = 'TDCNET' AND id = " . $rsnew["id_documento"] . ";";
    	$row = ExecuteRow($sql);
        $nro_doc = trim($tienda) . "-" . trim($row["nro_documento"]);
        $monto = ($row["pago_premio"] == 'S' ? 0 : $row["total"]);
        $sql = "SELECT ci_rif, nombre FROM cliente WHERE id = " . $rsnew["cliente"] . ";";
        $row = ExecuteRow($sql);
        $ci = $row["ci_rif"];
        $nombre_cliente = $row["nombre"];

        // Enviamos al API Central
        $resultado = enviarVentaAlCentral($tienda, $fecha, $nro_doc, $ci, $nombre_cliente, $monto, CurrentUserName());
        $id = $rsnew["id_documento"];
        // Opcional: Si quieres registrar el error en el log de PHPMaker si falla
        if ($resultado["status"] === "success") {
            // Solo si el API respondió OK, marcamos como Cerrado
            ExecuteUpdate("UPDATE salidas SET cerrado = 'S' WHERE id = " . $id);
        } else {
            // Si falló (por internet o error), se queda en 'N' para el barrido posterior
            Log("Sincronización pendiente para ID $id: " . $resultado["message"]);
        }
    	/////////
    }

    // Row Updating event
    public function rowUpdating($rsold, &$rsnew) {
    	// Enter your code here
    	// To cancel, set return value to FALSE
    	$fecha = date("Y-m-d");
    	$sql = "SELECT fecha FROM cierre_de_caja WHERE fecha = '$fecha';";
    	if($row = ExecuteRow($sql)) {
    		$this->CancelMessage = "El d&iacute;a " . date("d/m/Y") . " est&aacute; cerrado; no se puede modificar el pago. Verifique!";
    		return FALSE;
    	}
    	if($rsold["comprobante"] != "") {
    		$this->CancelMessage = "Este cobro est&aacute; contabilizado; no se puede modificar.";
    		return FALSE;
    	}

    	/*$sql = "SELECT monto FROM cobros_cliente WHERE id = " . $rsold["id"] . "";
    	$monto = floatval(ExecuteScalar($sql));
    	if(floatval($rsnew["monto_recibido"]) < $monto) {
    		$this->CancelMessage = "El monto recibido de la transacci&oacute;n no puede ser menor a los pagos de la(s) factura(s).";
    		return FALSE;
    	}*/
    	$rsnew["fecha_registro"] = date("Y-m-d");
    	$rsnew["username"] = CurrentUserName();
    	return TRUE;
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
       	$this->CancelMessage = "Nose puede borrar este movimiento; anule o borre la nota de entrega";
    	return FALSE;
    	$fecha = date("Y-m-d");
    	$sql = "SELECT fecha FROM cierre_de_caja WHERE fecha = '$fecha';";
    	if($row = ExecuteRow($sql)) {
    		$this->CancelMessage = "El d&iacute;a " . date("d/m/Y") . " est&aacute; cerrado; no se puede eliminar el pago. Verifique!";
    		return FALSE;
    	}
    	if($rs["comprobante"] != "") {
    		$this->CancelMessage = "Este cobro est&aacute; contabilizado; no se puede eliminar.";
    		return FALSE;
    	}
    	$sql = "SELECT
    				nro_documento, tipo_documento, cliente  
    			FROM salidas 
    			WHERE id = " . $rs["id_documento"] . ";";
    	$row = ExecuteRow($sql);
    	$cliente = $row["cliente"];
    	$nro_documento = $row["nro_documento"];
    	$tipo_documento = $row["tipo_documento"];

    	/*** Reverso Abonos en Bs ***/
    	$sql = "SELECT
    				id, cobros_cliente, metodo_pago,
    				referencia, monto_moneda, moneda,
    				tasa_moneda, monto_bs, tasa_usd,
    				monto_usd, banco
    			FROM cobros_cliente_detalle
    			WHERE cobros_cliente = " . $rs["id"] . " AND metodo_pago = 'RC';"; 
        $rows = ExecuteRows($sql);
        foreach ($rows as $key => $row) {
        	$sql = "SELECT IFNULL(MAX(nro_recibo), 0)+1 AS nro FROM abono WHERE 1;";
    		$nro_recibo = 0; // ExecuteScalar($sql);
    		$sql = "INSERT INTO
    					abono 
    				SET 	
    					id = NULL,
    					cliente = $cliente,
    					fecha = NOW(),
    					metodo_pago = NULL,
    					nro_recibo = $nro_recibo,
    					nota = 'POR ELIMINACION',
    		  			username = '" . CurrentUserName() . "';";
    		Execute($sql);
    		$sql = "SELECT LAST_INSERT_ID();";
    		$Abono = ExecuteScalar($sql);
            $id = $row["cobros_cliente"];
            $referencia = $row["referencia"];
            $metodo_pago = $row["metodo_pago"];
            $moneda = $row["moneda"];
            $monto_moneda = $row["monto_moneda"];
            $tasa = $row["tasa_moneda"];
            $monto_bs = $row["monto_bs"];
            $tasa_usd = $row["tasa_usd"];
            $monto_usd = $row["monto_usd"];
            $username = CurrentUserName();
            $sql = "INSERT INTO recarga(
                        id,
                        cliente,
                        fecha,
                        metodo_pago,
                        monto_moneda,
                        moneda,
                        tasa_moneda,
                        monto_bs,
                        tasa_usd,
                        monto_usd,
                        saldo,
                        nota,
                        username, reverso, abono)
                    VALUES (
                        NULL,
                        $cliente,
                        NOW(),
                        '$metodo_pago',
                        $monto_moneda,
                        '$moneda',
                        $tasa,
                        $monto_bs,
                        $tasa_usd,
                        $monto_usd,
                        0,
                        'Reverso por elminación de cobro Nro. $id, con referencia de recarga Nro. $referencia, Tipo documento $tipo_documento Nro. $nro_documento.',
                        '$username', 'S', $Abono)"; 
            Execute($sql);
            $sql = "SELECT LAST_INSERT_ID();";
            $id = ExecuteScalar($sql);
            $sql = "SELECT IFNULL(SUM(monto_usd), 0) AS saldo FROM recarga
                    WHERE cliente = $cliente;";
            $saldo = ExecuteScalar($sql);
            $sql = "UPDATE recarga SET saldo = $saldo WHERE id = $id;";
            Execute($sql);
        	$sql = "SELECT SUM(monto_usd) AS pago FROM recarga WHERE abono = $Abono;";
        	$monto_abono = ExecuteScalar($sql);
        	$sql = "UPDATE abono SET pago = $monto_abono WHERE id = $Abono";
        	Execute($sql);
        }
    	///

    	/*** Reverso Abonos en USD ***/
    	$sql = "SELECT
    				id, cobros_cliente, metodo_pago,
    				referencia, monto_moneda, moneda,
    				tasa_moneda, monto_bs, tasa_usd,
    				monto_usd, banco
    			FROM cobros_cliente_detalle
    			WHERE cobros_cliente = " . $rs["id"] . " AND metodo_pago = 'RD';"; 
        $rows = ExecuteRows($sql);
        foreach ($rows as $key => $row) {
        	$sql = "SELECT IFNULL(MAX(nro_recibo), 0)+1 AS nro FROM abono2 WHERE 1;";
    		$nro_recibo = 0; // ExecuteScalar($sql);
    		$sql = "INSERT INTO
    					abono2 
    				SET 	
    					id = NULL,
    					cliente = $cliente,
    					fecha = NOW(),
    					metodo_pago = NULL,
    					nro_recibo = $nro_recibo,
    					nota = 'POR ELIMINACION',
    		  			username = '" . CurrentUserName() . "';";
    		Execute($sql);
    		$sql = "SELECT LAST_INSERT_ID();";
    		$Abono = ExecuteScalar($sql);
            $id = $row["cobros_cliente"];
            $referencia = $row["referencia"];
            $metodo_pago = $row["metodo_pago"];
            $moneda = $row["moneda"];
            $monto_moneda = $row["monto_moneda"];
            $tasa = $row["tasa_moneda"];
            $monto_bs = $row["monto_bs"];
            $tasa_usd = $row["tasa_usd"];
            $monto_usd = $row["monto_usd"];
            $username = CurrentUserName();
            $sql = "INSERT INTO recarga2(
                        id,
                        cliente,
                        fecha,
                        metodo_pago,
                        monto_moneda,
                        moneda,
                        tasa_moneda,
                        monto_bs,
                        tasa_usd,
                        monto_usd,
                        saldo,
                        nota,
                        username, reverso, abono)
                    VALUES (
                        NULL,
                        $cliente,
                        NOW(),
                        '$metodo_pago',
                        $monto_moneda,
                        '$moneda',
                        $tasa,
                        $monto_bs,
                        $tasa_usd,
                        $monto_usd,
                        0,
                        'Reverso por elminación de cobro Nro. $id, con referencia de recarga Nro. $referencia, Tipo documento $tipo_documento Nro. $nro_documento.',
                        '$username', 'S', $Abono)"; 
            Execute($sql);
            $sql = "SELECT LAST_INSERT_ID();";
            $id = ExecuteScalar($sql);
            $sql = "SELECT IFNULL(SUM(monto_usd), 0) AS saldo FROM recarga2
                    WHERE cliente = $cliente;";
            $saldo = ExecuteScalar($sql);
            $sql = "UPDATE recarga2 SET saldo = $saldo WHERE id = $id;";
            Execute($sql);
        	$sql = "SELECT SUM(monto_usd) AS pago FROM recarga2 WHERE abono = $Abono;";
        	$monto_abono = ExecuteScalar($sql);
        	$sql = "UPDATE abono2 SET pago = $monto_abono WHERE id = $Abono";
        	Execute($sql);
        }
    	///

    	// *** Busca los excedentes *** //
    	$sql = "SELECT
    				id, cliente, CURDATE() AS fecha, metodo_pago,
    				id AS referencia, monto_moneda, moneda,
    				tasa_moneda, monto_bs, tasa_usd, monto_usd, saldo,
    				nota, username 
    			FROM recarga WHERE cobro_cliente_reverso = " . $rs["id"] . "";
        $rows = ExecuteRows($sql);
        foreach ($rows as $key => $row) {
        	$sql = "SELECT IFNULL(MAX(nro_recibo), 0)+1 AS nro FROM abono WHERE 1;";
    		$nro_recibo = 0; // ExecuteScalar($sql);
    		$sql = "INSERT INTO
    					abono 
    				SET 	
    					id = NULL,
    					cliente = $cliente,
    					fecha = NOW(),
    					metodo_pago = NULL,
    					nro_recibo = $nro_recibo,
    					nota = 'POR ELIMINACION',
    		  			username = '" . CurrentUserName() . "';";
    		Execute($sql);
    		$sql = "SELECT LAST_INSERT_ID();";
    		$Abono = ExecuteScalar($sql);
    		$id = $row["cobros_cliente"];
    		$referencia = $row["referencia"];
    		$metodo_pago = $row["metodo_pago"];
    		$moneda = $row["moneda"];
    		$monto_moneda = $row["monto_moneda"];
        	$tasa = $row["tasa_moneda"];
        	$monto_bs = $row["monto_bs"];
        	$tasa_usd = $row["tasa_usd"];
        	$monto_usd = $row["monto_usd"];
        	$username = CurrentUserName();
    		$sql = "INSERT INTO recarga(
    					id,
    					cliente,
    					fecha,
    					metodo_pago,
    					monto_moneda,
    					moneda,
    					tasa_moneda,
    					monto_bs,
    					tasa_usd,
    					monto_usd,
    					saldo,
    					nota,
    					username, reverso, abono)
    				VALUES (
    					NULL,
    					$cliente,
    					NOW(),
    					'$metodo_pago',
    					$monto_moneda,
    					'$moneda',
    					$tasa,
    					(-1)*$monto_bs,
    					$tasa_usd,
    					(-1)*$monto_usd,
    					0,
    					'Reverso de abono por elminación de cobro Nro. $id, con referencia de recarga Nro. $referencia, Tipo documento $tipo_documento Nro. $nro_documento.',
    					'$username', 'S', $Abono)";
    		Execute($sql);
    		$sql = "SELECT LAST_INSERT_ID();";
    		$id = ExecuteScalar($sql);
    		$sql = "SELECT IFNULL(SUM(monto_usd), 0) AS saldo FROM recarga
        			WHERE cliente = $cliente;";
        	$saldo = ExecuteScalar($sql);
        	$sql = "UPDATE recarga SET saldo = $saldo WHERE id = $id;";
        	Execute($sql);
        	$sql = "SELECT SUM(monto_usd) AS pago FROM recarga WHERE abono = $Abono;";
        	$monto_abono = ExecuteScalar($sql);
        	$sql = "UPDATE abono SET pago = $monto_abono WHERE id = $Abono";
        	Execute($sql);    	
    	}  	
    	///
    	return TRUE;
    }

    // Row Deleted event
    public function rowDeleted(&$rs)
    {
        //Log("Row Deleted");
    	$sql = "UPDATE salidas SET estatus = 'NUEVO'
    			WHERE id = " . $rs["id_documento"] . ";";
    	$row = ExecuteRow($sql);

    	/* ------- Actualizo cantidad en mano, en pedido y en transito  ------- */
    	$sql = "SELECT nro_documento, tipo_documento 
    			FROM salidas 
       			WHERE id = " . $rs["id_documento"] . "";
       	$row = ExecuteRow($sql);
       	$documentos = $row["tipo_documento"];
    	$sql = "SELECT COUNT(articulo) AS cantidad 
    			FROM entradas_salidas
    			WHERE tipo_documento = '$documentos'
    				AND id_documento = " . $rs["id_documento"] . ";";
    	$cantidad = ExecuteScalar($sql);
    	for($i = 0; $i < $cantidad; $i++) {
    		$sql = "SELECT articulo
    				FROM entradas_salidas
    				WHERE
    					tipo_documento = '$documentos'
    					AND id_documento = " . $rs["id_documento"] . " LIMIT $i, 1;";
    		$articulo = ExecuteScalar($sql);
    		ActualizarExitenciaArticulo($articulo);
    	}
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
    public function rowRendered() {
    	// To view properties of field class, use:
    	//var_dump($this-><FieldName>); 
    	if($this->comprobante->CurrentValue == "")
    		$this->RowAttrs["class"] = ""; 
    	else
    		$this->RowAttrs["class"] = "success";
    }

    // User ID Filtering event
    public function userIdFiltering(&$filter)
    {
        // Enter your code here
    }
}
