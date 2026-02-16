<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for tipo_documento
 */
class TipoDocumento extends DbTable
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
    public $codigo;
    public $descripcion;
    public $tipo;
    public $M01;
    public $M02;
    public $M03;
    public $M04;
    public $M05;
    public $M06;
    public $M07;
    public $M08;
    public $M09;
    public $M10;
    public $M11;
    public $M12;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'tipo_documento';
        $this->TableName = 'tipo_documento';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`tipo_documento`";
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
        $this->id = new DbField('tipo_documento', 'tipo_documento', 'x_id', 'id', '`id`', '`id`', 19, 10, -1, false, '`id`', false, false, false, 'FORMATTED TEXT', 'NO');
        $this->id->IsAutoIncrement = true; // Autoincrement field
        $this->id->IsPrimaryKey = true; // Primary key field
        $this->id->Sortable = true; // Allow sort
        $this->id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->id->Param, "CustomMsg");
        $this->Fields['id'] = &$this->id;

        // codigo
        $this->codigo = new DbField('tipo_documento', 'tipo_documento', 'x_codigo', 'codigo', '`codigo`', '`codigo`', 200, 6, -1, false, '`codigo`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->codigo->Required = true; // Required field
        $this->codigo->Sortable = true; // Allow sort
        $this->codigo->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->codigo->Param, "CustomMsg");
        $this->Fields['codigo'] = &$this->codigo;

        // descripcion
        $this->descripcion = new DbField('tipo_documento', 'tipo_documento', 'x_descripcion', 'descripcion', '`descripcion`', '`descripcion`', 200, 30, -1, false, '`descripcion`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->descripcion->Required = true; // Required field
        $this->descripcion->Sortable = true; // Allow sort
        $this->descripcion->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->descripcion->Param, "CustomMsg");
        $this->Fields['descripcion'] = &$this->descripcion;

        // tipo
        $this->tipo = new DbField('tipo_documento', 'tipo_documento', 'x_tipo', 'tipo', '`tipo`', '`tipo`', 202, 9, -1, false, '`tipo`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->tipo->Required = true; // Required field
        $this->tipo->Sortable = true; // Allow sort
        $this->tipo->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->tipo->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->tipo->Lookup = new Lookup('tipo', 'tipo_documento', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->tipo->OptionCount = 2;
        $this->tipo->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tipo->Param, "CustomMsg");
        $this->Fields['tipo'] = &$this->tipo;

        // M01
        $this->M01 = new DbField('tipo_documento', 'tipo_documento', 'x_M01', 'M01', '`M01`', '`M01`', 202, 1, -1, false, '`M01`', false, false, false, 'FORMATTED TEXT', 'RADIO');
        $this->M01->Required = true; // Required field
        $this->M01->Sortable = true; // Allow sort
        $this->M01->Lookup = new Lookup('M01', 'tipo_documento', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->M01->OptionCount = 2;
        $this->M01->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->M01->Param, "CustomMsg");
        $this->Fields['M01'] = &$this->M01;

        // M02
        $this->M02 = new DbField('tipo_documento', 'tipo_documento', 'x_M02', 'M02', '`M02`', '`M02`', 202, 1, -1, false, '`M02`', false, false, false, 'FORMATTED TEXT', 'RADIO');
        $this->M02->Required = true; // Required field
        $this->M02->Sortable = true; // Allow sort
        $this->M02->Lookup = new Lookup('M02', 'tipo_documento', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->M02->OptionCount = 2;
        $this->M02->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->M02->Param, "CustomMsg");
        $this->Fields['M02'] = &$this->M02;

        // M03
        $this->M03 = new DbField('tipo_documento', 'tipo_documento', 'x_M03', 'M03', '`M03`', '`M03`', 202, 1, -1, false, '`M03`', false, false, false, 'FORMATTED TEXT', 'RADIO');
        $this->M03->Required = true; // Required field
        $this->M03->Sortable = true; // Allow sort
        $this->M03->Lookup = new Lookup('M03', 'tipo_documento', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->M03->OptionCount = 2;
        $this->M03->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->M03->Param, "CustomMsg");
        $this->Fields['M03'] = &$this->M03;

        // M04
        $this->M04 = new DbField('tipo_documento', 'tipo_documento', 'x_M04', 'M04', '`M04`', '`M04`', 202, 1, -1, false, '`M04`', false, false, false, 'FORMATTED TEXT', 'RADIO');
        $this->M04->Required = true; // Required field
        $this->M04->Sortable = true; // Allow sort
        $this->M04->Lookup = new Lookup('M04', 'tipo_documento', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->M04->OptionCount = 2;
        $this->M04->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->M04->Param, "CustomMsg");
        $this->Fields['M04'] = &$this->M04;

        // M05
        $this->M05 = new DbField('tipo_documento', 'tipo_documento', 'x_M05', 'M05', '`M05`', '`M05`', 202, 1, -1, false, '`M05`', false, false, false, 'FORMATTED TEXT', 'RADIO');
        $this->M05->Required = true; // Required field
        $this->M05->Sortable = true; // Allow sort
        $this->M05->Lookup = new Lookup('M05', 'tipo_documento', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->M05->OptionCount = 2;
        $this->M05->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->M05->Param, "CustomMsg");
        $this->Fields['M05'] = &$this->M05;

        // M06
        $this->M06 = new DbField('tipo_documento', 'tipo_documento', 'x_M06', 'M06', '`M06`', '`M06`', 202, 1, -1, false, '`M06`', false, false, false, 'FORMATTED TEXT', 'RADIO');
        $this->M06->Required = true; // Required field
        $this->M06->Sortable = true; // Allow sort
        $this->M06->Lookup = new Lookup('M06', 'tipo_documento', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->M06->OptionCount = 2;
        $this->M06->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->M06->Param, "CustomMsg");
        $this->Fields['M06'] = &$this->M06;

        // M07
        $this->M07 = new DbField('tipo_documento', 'tipo_documento', 'x_M07', 'M07', '`M07`', '`M07`', 202, 1, -1, false, '`M07`', false, false, false, 'FORMATTED TEXT', 'RADIO');
        $this->M07->Required = true; // Required field
        $this->M07->Sortable = true; // Allow sort
        $this->M07->Lookup = new Lookup('M07', 'tipo_documento', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->M07->OptionCount = 2;
        $this->M07->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->M07->Param, "CustomMsg");
        $this->Fields['M07'] = &$this->M07;

        // M08
        $this->M08 = new DbField('tipo_documento', 'tipo_documento', 'x_M08', 'M08', '`M08`', '`M08`', 202, 1, -1, false, '`M08`', false, false, false, 'FORMATTED TEXT', 'RADIO');
        $this->M08->Required = true; // Required field
        $this->M08->Sortable = true; // Allow sort
        $this->M08->Lookup = new Lookup('M08', 'tipo_documento', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->M08->OptionCount = 2;
        $this->M08->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->M08->Param, "CustomMsg");
        $this->Fields['M08'] = &$this->M08;

        // M09
        $this->M09 = new DbField('tipo_documento', 'tipo_documento', 'x_M09', 'M09', '`M09`', '`M09`', 202, 1, -1, false, '`M09`', false, false, false, 'FORMATTED TEXT', 'RADIO');
        $this->M09->Required = true; // Required field
        $this->M09->Sortable = true; // Allow sort
        $this->M09->Lookup = new Lookup('M09', 'tipo_documento', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->M09->OptionCount = 2;
        $this->M09->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->M09->Param, "CustomMsg");
        $this->Fields['M09'] = &$this->M09;

        // M10
        $this->M10 = new DbField('tipo_documento', 'tipo_documento', 'x_M10', 'M10', '`M10`', '`M10`', 202, 1, -1, false, '`M10`', false, false, false, 'FORMATTED TEXT', 'RADIO');
        $this->M10->Required = true; // Required field
        $this->M10->Sortable = true; // Allow sort
        $this->M10->Lookup = new Lookup('M10', 'tipo_documento', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->M10->OptionCount = 2;
        $this->M10->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->M10->Param, "CustomMsg");
        $this->Fields['M10'] = &$this->M10;

        // M11
        $this->M11 = new DbField('tipo_documento', 'tipo_documento', 'x_M11', 'M11', '`M11`', '`M11`', 202, 1, -1, false, '`M11`', false, false, false, 'FORMATTED TEXT', 'RADIO');
        $this->M11->Required = true; // Required field
        $this->M11->Sortable = true; // Allow sort
        $this->M11->Lookup = new Lookup('M11', 'tipo_documento', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->M11->OptionCount = 2;
        $this->M11->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->M11->Param, "CustomMsg");
        $this->Fields['M11'] = &$this->M11;

        // M12
        $this->M12 = new DbField('tipo_documento', 'tipo_documento', 'x_M12', 'M12', '`M12`', '`M12`', 202, 1, -1, false, '`M12`', false, false, false, 'FORMATTED TEXT', 'RADIO');
        $this->M12->Required = true; // Required field
        $this->M12->Sortable = true; // Allow sort
        $this->M12->Lookup = new Lookup('M12', 'tipo_documento', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->M12->OptionCount = 2;
        $this->M12->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->M12->Param, "CustomMsg");
        $this->Fields['M12'] = &$this->M12;
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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`tipo_documento`";
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
        $this->codigo->DbValue = $row['codigo'];
        $this->descripcion->DbValue = $row['descripcion'];
        $this->tipo->DbValue = $row['tipo'];
        $this->M01->DbValue = $row['M01'];
        $this->M02->DbValue = $row['M02'];
        $this->M03->DbValue = $row['M03'];
        $this->M04->DbValue = $row['M04'];
        $this->M05->DbValue = $row['M05'];
        $this->M06->DbValue = $row['M06'];
        $this->M07->DbValue = $row['M07'];
        $this->M08->DbValue = $row['M08'];
        $this->M09->DbValue = $row['M09'];
        $this->M10->DbValue = $row['M10'];
        $this->M11->DbValue = $row['M11'];
        $this->M12->DbValue = $row['M12'];
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
        return $_SESSION[$name] ?? GetUrl("TipoDocumentoList");
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
        if ($pageName == "TipoDocumentoView") {
            return $Language->phrase("View");
        } elseif ($pageName == "TipoDocumentoEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "TipoDocumentoAdd") {
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
                return "TipoDocumentoView";
            case Config("API_ADD_ACTION"):
                return "TipoDocumentoAdd";
            case Config("API_EDIT_ACTION"):
                return "TipoDocumentoEdit";
            case Config("API_DELETE_ACTION"):
                return "TipoDocumentoDelete";
            case Config("API_LIST_ACTION"):
                return "TipoDocumentoList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "TipoDocumentoList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("TipoDocumentoView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("TipoDocumentoView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "TipoDocumentoAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "TipoDocumentoAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("TipoDocumentoEdit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("TipoDocumentoAdd", $this->getUrlParm($parm));
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
        return $this->keyUrl("TipoDocumentoDelete", $this->getUrlParm());
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
        $this->codigo->setDbValue($row['codigo']);
        $this->descripcion->setDbValue($row['descripcion']);
        $this->tipo->setDbValue($row['tipo']);
        $this->M01->setDbValue($row['M01']);
        $this->M02->setDbValue($row['M02']);
        $this->M03->setDbValue($row['M03']);
        $this->M04->setDbValue($row['M04']);
        $this->M05->setDbValue($row['M05']);
        $this->M06->setDbValue($row['M06']);
        $this->M07->setDbValue($row['M07']);
        $this->M08->setDbValue($row['M08']);
        $this->M09->setDbValue($row['M09']);
        $this->M10->setDbValue($row['M10']);
        $this->M11->setDbValue($row['M11']);
        $this->M12->setDbValue($row['M12']);
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

        // descripcion

        // tipo

        // M01

        // M02

        // M03

        // M04

        // M05

        // M06

        // M07

        // M08

        // M09

        // M10

        // M11

        // M12

        // id
        $this->id->ViewValue = $this->id->CurrentValue;
        $this->id->ViewCustomAttributes = "";

        // codigo
        $this->codigo->ViewValue = $this->codigo->CurrentValue;
        $this->codigo->ViewCustomAttributes = "";

        // descripcion
        $this->descripcion->ViewValue = $this->descripcion->CurrentValue;
        $this->descripcion->ViewCustomAttributes = "";

        // tipo
        if (strval($this->tipo->CurrentValue) != "") {
            $this->tipo->ViewValue = $this->tipo->optionCaption($this->tipo->CurrentValue);
        } else {
            $this->tipo->ViewValue = null;
        }
        $this->tipo->ViewCustomAttributes = "";

        // M01
        if (strval($this->M01->CurrentValue) != "") {
            $this->M01->ViewValue = $this->M01->optionCaption($this->M01->CurrentValue);
        } else {
            $this->M01->ViewValue = null;
        }
        $this->M01->ViewCustomAttributes = "";

        // M02
        if (strval($this->M02->CurrentValue) != "") {
            $this->M02->ViewValue = $this->M02->optionCaption($this->M02->CurrentValue);
        } else {
            $this->M02->ViewValue = null;
        }
        $this->M02->ViewCustomAttributes = "";

        // M03
        if (strval($this->M03->CurrentValue) != "") {
            $this->M03->ViewValue = $this->M03->optionCaption($this->M03->CurrentValue);
        } else {
            $this->M03->ViewValue = null;
        }
        $this->M03->ViewCustomAttributes = "";

        // M04
        if (strval($this->M04->CurrentValue) != "") {
            $this->M04->ViewValue = $this->M04->optionCaption($this->M04->CurrentValue);
        } else {
            $this->M04->ViewValue = null;
        }
        $this->M04->ViewCustomAttributes = "";

        // M05
        if (strval($this->M05->CurrentValue) != "") {
            $this->M05->ViewValue = $this->M05->optionCaption($this->M05->CurrentValue);
        } else {
            $this->M05->ViewValue = null;
        }
        $this->M05->ViewCustomAttributes = "";

        // M06
        if (strval($this->M06->CurrentValue) != "") {
            $this->M06->ViewValue = $this->M06->optionCaption($this->M06->CurrentValue);
        } else {
            $this->M06->ViewValue = null;
        }
        $this->M06->ViewCustomAttributes = "";

        // M07
        if (strval($this->M07->CurrentValue) != "") {
            $this->M07->ViewValue = $this->M07->optionCaption($this->M07->CurrentValue);
        } else {
            $this->M07->ViewValue = null;
        }
        $this->M07->ViewCustomAttributes = "";

        // M08
        if (strval($this->M08->CurrentValue) != "") {
            $this->M08->ViewValue = $this->M08->optionCaption($this->M08->CurrentValue);
        } else {
            $this->M08->ViewValue = null;
        }
        $this->M08->ViewCustomAttributes = "";

        // M09
        if (strval($this->M09->CurrentValue) != "") {
            $this->M09->ViewValue = $this->M09->optionCaption($this->M09->CurrentValue);
        } else {
            $this->M09->ViewValue = null;
        }
        $this->M09->ViewCustomAttributes = "";

        // M10
        if (strval($this->M10->CurrentValue) != "") {
            $this->M10->ViewValue = $this->M10->optionCaption($this->M10->CurrentValue);
        } else {
            $this->M10->ViewValue = null;
        }
        $this->M10->ViewCustomAttributes = "";

        // M11
        if (strval($this->M11->CurrentValue) != "") {
            $this->M11->ViewValue = $this->M11->optionCaption($this->M11->CurrentValue);
        } else {
            $this->M11->ViewValue = null;
        }
        $this->M11->ViewCustomAttributes = "";

        // M12
        if (strval($this->M12->CurrentValue) != "") {
            $this->M12->ViewValue = $this->M12->optionCaption($this->M12->CurrentValue);
        } else {
            $this->M12->ViewValue = null;
        }
        $this->M12->ViewCustomAttributes = "";

        // id
        $this->id->LinkCustomAttributes = "";
        $this->id->HrefValue = "";
        $this->id->TooltipValue = "";

        // codigo
        $this->codigo->LinkCustomAttributes = "";
        $this->codigo->HrefValue = "";
        $this->codigo->TooltipValue = "";

        // descripcion
        $this->descripcion->LinkCustomAttributes = "";
        $this->descripcion->HrefValue = "";
        $this->descripcion->TooltipValue = "";

        // tipo
        $this->tipo->LinkCustomAttributes = "";
        $this->tipo->HrefValue = "";
        $this->tipo->TooltipValue = "";

        // M01
        $this->M01->LinkCustomAttributes = "";
        $this->M01->HrefValue = "";
        $this->M01->TooltipValue = "";

        // M02
        $this->M02->LinkCustomAttributes = "";
        $this->M02->HrefValue = "";
        $this->M02->TooltipValue = "";

        // M03
        $this->M03->LinkCustomAttributes = "";
        $this->M03->HrefValue = "";
        $this->M03->TooltipValue = "";

        // M04
        $this->M04->LinkCustomAttributes = "";
        $this->M04->HrefValue = "";
        $this->M04->TooltipValue = "";

        // M05
        $this->M05->LinkCustomAttributes = "";
        $this->M05->HrefValue = "";
        $this->M05->TooltipValue = "";

        // M06
        $this->M06->LinkCustomAttributes = "";
        $this->M06->HrefValue = "";
        $this->M06->TooltipValue = "";

        // M07
        $this->M07->LinkCustomAttributes = "";
        $this->M07->HrefValue = "";
        $this->M07->TooltipValue = "";

        // M08
        $this->M08->LinkCustomAttributes = "";
        $this->M08->HrefValue = "";
        $this->M08->TooltipValue = "";

        // M09
        $this->M09->LinkCustomAttributes = "";
        $this->M09->HrefValue = "";
        $this->M09->TooltipValue = "";

        // M10
        $this->M10->LinkCustomAttributes = "";
        $this->M10->HrefValue = "";
        $this->M10->TooltipValue = "";

        // M11
        $this->M11->LinkCustomAttributes = "";
        $this->M11->HrefValue = "";
        $this->M11->TooltipValue = "";

        // M12
        $this->M12->LinkCustomAttributes = "";
        $this->M12->HrefValue = "";
        $this->M12->TooltipValue = "";

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

        // codigo
        $this->codigo->EditAttrs["class"] = "form-control";
        $this->codigo->EditCustomAttributes = "";
        $this->codigo->EditValue = $this->codigo->CurrentValue;
        $this->codigo->ViewCustomAttributes = "";

        // descripcion
        $this->descripcion->EditAttrs["class"] = "form-control";
        $this->descripcion->EditCustomAttributes = "";
        $this->descripcion->EditValue = $this->descripcion->CurrentValue;
        $this->descripcion->ViewCustomAttributes = "";

        // tipo
        $this->tipo->EditAttrs["class"] = "form-control";
        $this->tipo->EditCustomAttributes = "";
        if (strval($this->tipo->CurrentValue) != "") {
            $this->tipo->EditValue = $this->tipo->optionCaption($this->tipo->CurrentValue);
        } else {
            $this->tipo->EditValue = null;
        }
        $this->tipo->ViewCustomAttributes = "";

        // M01
        $this->M01->EditCustomAttributes = "";
        $this->M01->EditValue = $this->M01->options(false);
        $this->M01->PlaceHolder = RemoveHtml($this->M01->caption());

        // M02
        $this->M02->EditCustomAttributes = "";
        $this->M02->EditValue = $this->M02->options(false);
        $this->M02->PlaceHolder = RemoveHtml($this->M02->caption());

        // M03
        $this->M03->EditCustomAttributes = "";
        $this->M03->EditValue = $this->M03->options(false);
        $this->M03->PlaceHolder = RemoveHtml($this->M03->caption());

        // M04
        $this->M04->EditCustomAttributes = "";
        $this->M04->EditValue = $this->M04->options(false);
        $this->M04->PlaceHolder = RemoveHtml($this->M04->caption());

        // M05
        $this->M05->EditCustomAttributes = "";
        $this->M05->EditValue = $this->M05->options(false);
        $this->M05->PlaceHolder = RemoveHtml($this->M05->caption());

        // M06
        $this->M06->EditCustomAttributes = "";
        $this->M06->EditValue = $this->M06->options(false);
        $this->M06->PlaceHolder = RemoveHtml($this->M06->caption());

        // M07
        $this->M07->EditCustomAttributes = "";
        $this->M07->EditValue = $this->M07->options(false);
        $this->M07->PlaceHolder = RemoveHtml($this->M07->caption());

        // M08
        $this->M08->EditCustomAttributes = "";
        $this->M08->EditValue = $this->M08->options(false);
        $this->M08->PlaceHolder = RemoveHtml($this->M08->caption());

        // M09
        $this->M09->EditCustomAttributes = "";
        $this->M09->EditValue = $this->M09->options(false);
        $this->M09->PlaceHolder = RemoveHtml($this->M09->caption());

        // M10
        $this->M10->EditCustomAttributes = "";
        $this->M10->EditValue = $this->M10->options(false);
        $this->M10->PlaceHolder = RemoveHtml($this->M10->caption());

        // M11
        $this->M11->EditCustomAttributes = "";
        $this->M11->EditValue = $this->M11->options(false);
        $this->M11->PlaceHolder = RemoveHtml($this->M11->caption());

        // M12
        $this->M12->EditCustomAttributes = "";
        $this->M12->EditValue = $this->M12->options(false);
        $this->M12->PlaceHolder = RemoveHtml($this->M12->caption());

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
                    $doc->exportCaption($this->descripcion);
                    $doc->exportCaption($this->tipo);
                    $doc->exportCaption($this->M01);
                    $doc->exportCaption($this->M02);
                    $doc->exportCaption($this->M03);
                    $doc->exportCaption($this->M04);
                    $doc->exportCaption($this->M05);
                    $doc->exportCaption($this->M06);
                    $doc->exportCaption($this->M07);
                    $doc->exportCaption($this->M08);
                    $doc->exportCaption($this->M09);
                    $doc->exportCaption($this->M10);
                    $doc->exportCaption($this->M11);
                    $doc->exportCaption($this->M12);
                } else {
                    $doc->exportCaption($this->id);
                    $doc->exportCaption($this->codigo);
                    $doc->exportCaption($this->descripcion);
                    $doc->exportCaption($this->tipo);
                    $doc->exportCaption($this->M01);
                    $doc->exportCaption($this->M02);
                    $doc->exportCaption($this->M03);
                    $doc->exportCaption($this->M04);
                    $doc->exportCaption($this->M05);
                    $doc->exportCaption($this->M06);
                    $doc->exportCaption($this->M07);
                    $doc->exportCaption($this->M08);
                    $doc->exportCaption($this->M09);
                    $doc->exportCaption($this->M10);
                    $doc->exportCaption($this->M11);
                    $doc->exportCaption($this->M12);
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
                        $doc->exportField($this->descripcion);
                        $doc->exportField($this->tipo);
                        $doc->exportField($this->M01);
                        $doc->exportField($this->M02);
                        $doc->exportField($this->M03);
                        $doc->exportField($this->M04);
                        $doc->exportField($this->M05);
                        $doc->exportField($this->M06);
                        $doc->exportField($this->M07);
                        $doc->exportField($this->M08);
                        $doc->exportField($this->M09);
                        $doc->exportField($this->M10);
                        $doc->exportField($this->M11);
                        $doc->exportField($this->M12);
                    } else {
                        $doc->exportField($this->id);
                        $doc->exportField($this->codigo);
                        $doc->exportField($this->descripcion);
                        $doc->exportField($this->tipo);
                        $doc->exportField($this->M01);
                        $doc->exportField($this->M02);
                        $doc->exportField($this->M03);
                        $doc->exportField($this->M04);
                        $doc->exportField($this->M05);
                        $doc->exportField($this->M06);
                        $doc->exportField($this->M07);
                        $doc->exportField($this->M08);
                        $doc->exportField($this->M09);
                        $doc->exportField($this->M10);
                        $doc->exportField($this->M11);
                        $doc->exportField($this->M12);
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
        $table = 'tipo_documento';
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
        $table = 'tipo_documento';

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
        $table = 'tipo_documento';

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
        $table = 'tipo_documento';

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
        $codigo = $rsold["codigo"];
        if($codigo != "TDCFCC" and $codigo != "TDCFCV") {
    		$this->CancelMessage = "Proceso aplica s&oacute;lo a facturas de compra y venta.";
    		return FALSE;
       }
        return true;
    }

    // Row Updated event
    public function rowUpdated($rsold, &$rsnew)
    {
        //Log("Row Updated");
        $cerrado = "";
        $mes = 0;
        $anho = 0;
        $codigo = $rsold["codigo"];
        if($codigo == "TDCFCC") {
        	for($i=1; $i<=12; $i++) {
        		$cerrado = $rsnew["M" . str_pad($i, 2, "0", STR_PAD_LEFT)];
        		$mes = $i;
        		$anho = intval(date("m"))<$i ? intval(date("Y"))-1 : intval(date("Y"));
        		$sql = "UPDATE entradas SET cerrado = '$cerrado'
        				WHERE MONTH(fecha) = $mes AND YEAR(fecha) = $anho";
        		Execute($sql);
        	}
        }
        if($codigo == "TDCFCV") {
        	for($i=1; $i<=12; $i++) {
        		$cerrado = $rsnew["M" . str_pad($i, 2, "0", STR_PAD_LEFT)];
        		$mes = $i;
        		$anho = intval(date("m"))<$i ? intval(date("Y"))-1 : intval(date("Y"));
        		$sql = "UPDATE salidas SET cerrado = '$cerrado'
        				WHERE MONTH(fecha) = $mes AND YEAR(fecha) = $anho";
        		Execute($sql);
        	}
        }
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
