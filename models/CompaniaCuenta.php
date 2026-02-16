<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for compania_cuenta
 */
class CompaniaCuenta extends DbTable
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
    public $banco;
    public $titular;
    public $tipo;
    public $numero;
    public $mostrar;
    public $cuenta;
    public $pago_electronico;
    public $activo;
    public $compania;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'compania_cuenta';
        $this->TableName = 'compania_cuenta';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`compania_cuenta`";
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
        $this->id = new DbField('compania_cuenta', 'compania_cuenta', 'x_id', 'id', '`id`', '`id`', 19, 10, -1, false, '`id`', false, false, false, 'FORMATTED TEXT', 'NO');
        $this->id->IsAutoIncrement = true; // Autoincrement field
        $this->id->IsPrimaryKey = true; // Primary key field
        $this->id->Sortable = true; // Allow sort
        $this->id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->id->Param, "CustomMsg");
        $this->Fields['id'] = &$this->id;

        // banco
        $this->banco = new DbField('compania_cuenta', 'compania_cuenta', 'x_banco', 'banco', '`banco`', '`banco`', 200, 50, -1, false, '`banco`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->banco->Required = true; // Required field
        $this->banco->Sortable = true; // Allow sort
        $this->banco->Lookup = new Lookup('banco', 'tabla', false, 'campo_codigo', ["campo_descripcion","","",""], [], [], [], [], [], [], '`campo_descripcion`', '');
        $this->banco->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->banco->Param, "CustomMsg");
        $this->Fields['banco'] = &$this->banco;

        // titular
        $this->titular = new DbField('compania_cuenta', 'compania_cuenta', 'x_titular', 'titular', '`titular`', '`titular`', 200, 80, -1, false, '`titular`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->titular->Required = true; // Required field
        $this->titular->Sortable = true; // Allow sort
        $this->titular->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->titular->Param, "CustomMsg");
        $this->Fields['titular'] = &$this->titular;

        // tipo
        $this->tipo = new DbField('compania_cuenta', 'compania_cuenta', 'x_tipo', 'tipo', '`tipo`', '`tipo`', 200, 20, -1, false, '`tipo`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->tipo->Required = true; // Required field
        $this->tipo->Sortable = true; // Allow sort
        $this->tipo->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->tipo->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->tipo->Lookup = new Lookup('tipo', 'compania_cuenta', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->tipo->OptionCount = 2;
        $this->tipo->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tipo->Param, "CustomMsg");
        $this->Fields['tipo'] = &$this->tipo;

        // numero
        $this->numero = new DbField('compania_cuenta', 'compania_cuenta', 'x_numero', 'numero', '`numero`', '`numero`', 200, 40, -1, false, '`numero`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->numero->Required = true; // Required field
        $this->numero->Sortable = true; // Allow sort
        $this->numero->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->numero->Param, "CustomMsg");
        $this->Fields['numero'] = &$this->numero;

        // mostrar
        $this->mostrar = new DbField('compania_cuenta', 'compania_cuenta', 'x_mostrar', 'mostrar', '`mostrar`', '`mostrar`', 202, 1, -1, false, '`mostrar`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->mostrar->Sortable = true; // Allow sort
        $this->mostrar->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->mostrar->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->mostrar->Lookup = new Lookup('mostrar', 'compania_cuenta', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->mostrar->OptionCount = 2;
        $this->mostrar->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->mostrar->Param, "CustomMsg");
        $this->Fields['mostrar'] = &$this->mostrar;

        // cuenta
        $this->cuenta = new DbField('compania_cuenta', 'compania_cuenta', 'x_cuenta', 'cuenta', '`cuenta`', '`cuenta`', 19, 10, -1, false, '`cuenta`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->cuenta->Nullable = false; // NOT NULL field
        $this->cuenta->Sortable = true; // Allow sort
        $this->cuenta->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->cuenta->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->cuenta->Lookup = new Lookup('cuenta', 'view_plancta', false, 'id', ["codigo","descripcion","",""], [], [], [], [], [], [], '`descripcion`', '');
        $this->cuenta->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->cuenta->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->cuenta->Param, "CustomMsg");
        $this->Fields['cuenta'] = &$this->cuenta;

        // pago_electronico
        $this->pago_electronico = new DbField('compania_cuenta', 'compania_cuenta', 'x_pago_electronico', 'pago_electronico', '`pago_electronico`', '`pago_electronico`', 202, 1, -1, false, '`pago_electronico`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->pago_electronico->Nullable = false; // NOT NULL field
        $this->pago_electronico->Required = true; // Required field
        $this->pago_electronico->Sortable = true; // Allow sort
        $this->pago_electronico->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->pago_electronico->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->pago_electronico->Lookup = new Lookup('pago_electronico', 'compania_cuenta', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->pago_electronico->OptionCount = 2;
        $this->pago_electronico->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->pago_electronico->Param, "CustomMsg");
        $this->Fields['pago_electronico'] = &$this->pago_electronico;

        // activo
        $this->activo = new DbField('compania_cuenta', 'compania_cuenta', 'x_activo', 'activo', '`activo`', '`activo`', 202, 1, -1, false, '`activo`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->activo->Required = true; // Required field
        $this->activo->Sortable = true; // Allow sort
        $this->activo->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->activo->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->activo->Lookup = new Lookup('activo', 'compania_cuenta', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->activo->OptionCount = 2;
        $this->activo->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->activo->Param, "CustomMsg");
        $this->Fields['activo'] = &$this->activo;

        // compania
        $this->compania = new DbField('compania_cuenta', 'compania_cuenta', 'x_compania', 'compania', '`compania`', '`compania`', 19, 10, -1, false, '`compania`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->compania->IsForeignKey = true; // Foreign key field
        $this->compania->Nullable = false; // NOT NULL field
        $this->compania->Sortable = true; // Allow sort
        $this->compania->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->compania->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->compania->Param, "CustomMsg");
        $this->Fields['compania'] = &$this->compania;
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

    // Current master table name
    public function getCurrentMasterTable()
    {
        return Session(PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_MASTER_TABLE"));
    }

    public function setCurrentMasterTable($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_MASTER_TABLE")] = $v;
    }

    // Session master WHERE clause
    public function getMasterFilter()
    {
        // Master filter
        $masterFilter = "";
        if ($this->getCurrentMasterTable() == "compania") {
            if ($this->compania->getSessionValue() != "") {
                $masterFilter .= "" . GetForeignKeySql("`id`", $this->compania->getSessionValue(), DATATYPE_NUMBER, "DB");
            } else {
                return "";
            }
        }
        return $masterFilter;
    }

    // Session detail WHERE clause
    public function getDetailFilter()
    {
        // Detail filter
        $detailFilter = "";
        if ($this->getCurrentMasterTable() == "compania") {
            if ($this->compania->getSessionValue() != "") {
                $detailFilter .= "" . GetForeignKeySql("`compania`", $this->compania->getSessionValue(), DATATYPE_NUMBER, "DB");
            } else {
                return "";
            }
        }
        return $detailFilter;
    }

    // Master filter
    public function sqlMasterFilter_compania()
    {
        return "`id`=@id@";
    }
    // Detail filter
    public function sqlDetailFilter_compania()
    {
        return "`compania`=@compania@";
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`compania_cuenta`";
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
        $this->banco->DbValue = $row['banco'];
        $this->titular->DbValue = $row['titular'];
        $this->tipo->DbValue = $row['tipo'];
        $this->numero->DbValue = $row['numero'];
        $this->mostrar->DbValue = $row['mostrar'];
        $this->cuenta->DbValue = $row['cuenta'];
        $this->pago_electronico->DbValue = $row['pago_electronico'];
        $this->activo->DbValue = $row['activo'];
        $this->compania->DbValue = $row['compania'];
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
        return $_SESSION[$name] ?? GetUrl("CompaniaCuentaList");
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
        if ($pageName == "CompaniaCuentaView") {
            return $Language->phrase("View");
        } elseif ($pageName == "CompaniaCuentaEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "CompaniaCuentaAdd") {
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
                return "CompaniaCuentaView";
            case Config("API_ADD_ACTION"):
                return "CompaniaCuentaAdd";
            case Config("API_EDIT_ACTION"):
                return "CompaniaCuentaEdit";
            case Config("API_DELETE_ACTION"):
                return "CompaniaCuentaDelete";
            case Config("API_LIST_ACTION"):
                return "CompaniaCuentaList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "CompaniaCuentaList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("CompaniaCuentaView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("CompaniaCuentaView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "CompaniaCuentaAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "CompaniaCuentaAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("CompaniaCuentaEdit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("CompaniaCuentaAdd", $this->getUrlParm($parm));
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
        return $this->keyUrl("CompaniaCuentaDelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        if ($this->getCurrentMasterTable() == "compania" && !ContainsString($url, Config("TABLE_SHOW_MASTER") . "=")) {
            $url .= (ContainsString($url, "?") ? "&" : "?") . Config("TABLE_SHOW_MASTER") . "=" . $this->getCurrentMasterTable();
            $url .= "&" . GetForeignKeyUrl("fk_id", $this->compania->CurrentValue ?? $this->compania->getSessionValue());
        }
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
        $this->banco->setDbValue($row['banco']);
        $this->titular->setDbValue($row['titular']);
        $this->tipo->setDbValue($row['tipo']);
        $this->numero->setDbValue($row['numero']);
        $this->mostrar->setDbValue($row['mostrar']);
        $this->cuenta->setDbValue($row['cuenta']);
        $this->pago_electronico->setDbValue($row['pago_electronico']);
        $this->activo->setDbValue($row['activo']);
        $this->compania->setDbValue($row['compania']);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // id

        // banco

        // titular

        // tipo

        // numero

        // mostrar

        // cuenta

        // pago_electronico

        // activo

        // compania

        // id
        $this->id->ViewValue = $this->id->CurrentValue;
        $this->id->ViewCustomAttributes = "";

        // banco
        $this->banco->ViewValue = $this->banco->CurrentValue;
        $curVal = trim(strval($this->banco->CurrentValue));
        if ($curVal != "") {
            $this->banco->ViewValue = $this->banco->lookupCacheOption($curVal);
            if ($this->banco->ViewValue === null) { // Lookup from database
                $filterWrk = "`campo_codigo`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                $lookupFilter = function() {
                    return "`tabla` = 'BANCO'";
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->banco->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->banco->Lookup->renderViewRow($rswrk[0]);
                    $this->banco->ViewValue = $this->banco->displayValue($arwrk);
                } else {
                    $this->banco->ViewValue = $this->banco->CurrentValue;
                }
            }
        } else {
            $this->banco->ViewValue = null;
        }
        $this->banco->ViewCustomAttributes = "";

        // titular
        $this->titular->ViewValue = $this->titular->CurrentValue;
        $this->titular->ViewCustomAttributes = "";

        // tipo
        if (strval($this->tipo->CurrentValue) != "") {
            $this->tipo->ViewValue = $this->tipo->optionCaption($this->tipo->CurrentValue);
        } else {
            $this->tipo->ViewValue = null;
        }
        $this->tipo->ViewCustomAttributes = "";

        // numero
        $this->numero->ViewValue = $this->numero->CurrentValue;
        $this->numero->ViewCustomAttributes = "";

        // mostrar
        if (strval($this->mostrar->CurrentValue) != "") {
            $this->mostrar->ViewValue = $this->mostrar->optionCaption($this->mostrar->CurrentValue);
        } else {
            $this->mostrar->ViewValue = null;
        }
        $this->mostrar->ViewCustomAttributes = "";

        // cuenta
        $curVal = trim(strval($this->cuenta->CurrentValue));
        if ($curVal != "") {
            $this->cuenta->ViewValue = $this->cuenta->lookupCacheOption($curVal);
            if ($this->cuenta->ViewValue === null) { // Lookup from database
                $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $lookupFilter = function() {
                    return "codigo LIKE CONCAT((SELECT valor2 FROM parametro WHERE codigo = '018' and valor1 = 'Bancos'), '%')
        OR 
        	codigo LIKE CONCAT((SELECT valor2 FROM parametro WHERE codigo = '018' and valor1 = 'Caja'), '%') OR codigo LIKE CONCAT((SELECT valor3 FROM parametro WHERE codigo = '018' and valor1 = 'Bancos'), '%') OR 
        	codigo LIKE CONCAT((SELECT valor3 FROM parametro WHERE codigo = '018' and valor1 = 'Caja'), '%')";
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->cuenta->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->cuenta->Lookup->renderViewRow($rswrk[0]);
                    $this->cuenta->ViewValue = $this->cuenta->displayValue($arwrk);
                } else {
                    $this->cuenta->ViewValue = $this->cuenta->CurrentValue;
                }
            }
        } else {
            $this->cuenta->ViewValue = null;
        }
        $this->cuenta->ViewCustomAttributes = "";

        // pago_electronico
        if (strval($this->pago_electronico->CurrentValue) != "") {
            $this->pago_electronico->ViewValue = $this->pago_electronico->optionCaption($this->pago_electronico->CurrentValue);
        } else {
            $this->pago_electronico->ViewValue = null;
        }
        $this->pago_electronico->ViewCustomAttributes = "";

        // activo
        if (strval($this->activo->CurrentValue) != "") {
            $this->activo->ViewValue = $this->activo->optionCaption($this->activo->CurrentValue);
        } else {
            $this->activo->ViewValue = null;
        }
        $this->activo->ViewCustomAttributes = "";

        // compania
        $this->compania->ViewValue = $this->compania->CurrentValue;
        $this->compania->ViewCustomAttributes = "";

        // id
        $this->id->LinkCustomAttributes = "";
        $this->id->HrefValue = "";
        $this->id->TooltipValue = "";

        // banco
        $this->banco->LinkCustomAttributes = "";
        $this->banco->HrefValue = "";
        $this->banco->TooltipValue = "";

        // titular
        $this->titular->LinkCustomAttributes = "";
        $this->titular->HrefValue = "";
        $this->titular->TooltipValue = "";

        // tipo
        $this->tipo->LinkCustomAttributes = "";
        $this->tipo->HrefValue = "";
        $this->tipo->TooltipValue = "";

        // numero
        $this->numero->LinkCustomAttributes = "";
        $this->numero->HrefValue = "";
        $this->numero->TooltipValue = "";

        // mostrar
        $this->mostrar->LinkCustomAttributes = "";
        $this->mostrar->HrefValue = "";
        $this->mostrar->TooltipValue = "";

        // cuenta
        $this->cuenta->LinkCustomAttributes = "";
        $this->cuenta->HrefValue = "";
        $this->cuenta->TooltipValue = "";

        // pago_electronico
        $this->pago_electronico->LinkCustomAttributes = "";
        $this->pago_electronico->HrefValue = "";
        $this->pago_electronico->TooltipValue = "";

        // activo
        $this->activo->LinkCustomAttributes = "";
        $this->activo->HrefValue = "";
        $this->activo->TooltipValue = "";

        // compania
        $this->compania->LinkCustomAttributes = "";
        $this->compania->HrefValue = "";
        $this->compania->TooltipValue = "";

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

        // banco
        $this->banco->EditAttrs["class"] = "form-control";
        $this->banco->EditCustomAttributes = "";
        if (!$this->banco->Raw) {
            $this->banco->CurrentValue = HtmlDecode($this->banco->CurrentValue);
        }
        $this->banco->EditValue = $this->banco->CurrentValue;
        $this->banco->PlaceHolder = RemoveHtml($this->banco->caption());

        // titular
        $this->titular->EditAttrs["class"] = "form-control";
        $this->titular->EditCustomAttributes = "";
        if (!$this->titular->Raw) {
            $this->titular->CurrentValue = HtmlDecode($this->titular->CurrentValue);
        }
        $this->titular->EditValue = $this->titular->CurrentValue;
        $this->titular->PlaceHolder = RemoveHtml($this->titular->caption());

        // tipo
        $this->tipo->EditAttrs["class"] = "form-control";
        $this->tipo->EditCustomAttributes = "";
        $this->tipo->EditValue = $this->tipo->options(true);
        $this->tipo->PlaceHolder = RemoveHtml($this->tipo->caption());

        // numero
        $this->numero->EditAttrs["class"] = "form-control";
        $this->numero->EditCustomAttributes = "";
        if (!$this->numero->Raw) {
            $this->numero->CurrentValue = HtmlDecode($this->numero->CurrentValue);
        }
        $this->numero->EditValue = $this->numero->CurrentValue;
        $this->numero->PlaceHolder = RemoveHtml($this->numero->caption());

        // mostrar
        $this->mostrar->EditAttrs["class"] = "form-control";
        $this->mostrar->EditCustomAttributes = "";
        $this->mostrar->EditValue = $this->mostrar->options(true);
        $this->mostrar->PlaceHolder = RemoveHtml($this->mostrar->caption());

        // cuenta
        $this->cuenta->EditAttrs["class"] = "form-control";
        $this->cuenta->EditCustomAttributes = "";
        $this->cuenta->PlaceHolder = RemoveHtml($this->cuenta->caption());

        // pago_electronico
        $this->pago_electronico->EditAttrs["class"] = "form-control";
        $this->pago_electronico->EditCustomAttributes = "";
        $this->pago_electronico->EditValue = $this->pago_electronico->options(true);
        $this->pago_electronico->PlaceHolder = RemoveHtml($this->pago_electronico->caption());

        // activo
        $this->activo->EditAttrs["class"] = "form-control";
        $this->activo->EditCustomAttributes = "";
        $this->activo->EditValue = $this->activo->options(true);
        $this->activo->PlaceHolder = RemoveHtml($this->activo->caption());

        // compania
        $this->compania->EditAttrs["class"] = "form-control";
        $this->compania->EditCustomAttributes = "";
        if ($this->compania->getSessionValue() != "") {
            $this->compania->CurrentValue = GetForeignKeyValue($this->compania->getSessionValue());
            $this->compania->ViewValue = $this->compania->CurrentValue;
            $this->compania->ViewCustomAttributes = "";
        } else {
            $this->compania->EditValue = $this->compania->CurrentValue;
            $this->compania->PlaceHolder = RemoveHtml($this->compania->caption());
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
                    $doc->exportCaption($this->banco);
                    $doc->exportCaption($this->titular);
                    $doc->exportCaption($this->tipo);
                    $doc->exportCaption($this->numero);
                    $doc->exportCaption($this->mostrar);
                    $doc->exportCaption($this->cuenta);
                    $doc->exportCaption($this->pago_electronico);
                    $doc->exportCaption($this->activo);
                    $doc->exportCaption($this->compania);
                } else {
                    $doc->exportCaption($this->id);
                    $doc->exportCaption($this->banco);
                    $doc->exportCaption($this->titular);
                    $doc->exportCaption($this->tipo);
                    $doc->exportCaption($this->numero);
                    $doc->exportCaption($this->mostrar);
                    $doc->exportCaption($this->cuenta);
                    $doc->exportCaption($this->pago_electronico);
                    $doc->exportCaption($this->activo);
                    $doc->exportCaption($this->compania);
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
                        $doc->exportField($this->banco);
                        $doc->exportField($this->titular);
                        $doc->exportField($this->tipo);
                        $doc->exportField($this->numero);
                        $doc->exportField($this->mostrar);
                        $doc->exportField($this->cuenta);
                        $doc->exportField($this->pago_electronico);
                        $doc->exportField($this->activo);
                        $doc->exportField($this->compania);
                    } else {
                        $doc->exportField($this->id);
                        $doc->exportField($this->banco);
                        $doc->exportField($this->titular);
                        $doc->exportField($this->tipo);
                        $doc->exportField($this->numero);
                        $doc->exportField($this->mostrar);
                        $doc->exportField($this->cuenta);
                        $doc->exportField($this->pago_electronico);
                        $doc->exportField($this->activo);
                        $doc->exportField($this->compania);
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
        $table = 'compania_cuenta';
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
        $table = 'compania_cuenta';

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
        $table = 'compania_cuenta';

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
        $table = 'compania_cuenta';

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
