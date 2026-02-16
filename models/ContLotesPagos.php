<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for cont_lotes_pagos
 */
class ContLotesPagos extends DbTable
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
    public $fecha;
    public $banco;
    public $referencia;
    public $moneda;
    public $procesado;
    public $nota;
    public $fecha_registro;
    public $usuario;
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
        $this->TableVar = 'cont_lotes_pagos';
        $this->TableName = 'cont_lotes_pagos';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`cont_lotes_pagos`";
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
        $this->id = new DbField('cont_lotes_pagos', 'cont_lotes_pagos', 'x_id', 'id', '`id`', '`id`', 3, 11, -1, false, '`id`', false, false, false, 'FORMATTED TEXT', 'NO');
        $this->id->IsAutoIncrement = true; // Autoincrement field
        $this->id->IsPrimaryKey = true; // Primary key field
        $this->id->IsForeignKey = true; // Foreign key field
        $this->id->Sortable = true; // Allow sort
        $this->id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->id->Param, "CustomMsg");
        $this->Fields['id'] = &$this->id;

        // fecha
        $this->fecha = new DbField('cont_lotes_pagos', 'cont_lotes_pagos', 'x_fecha', 'fecha', '`fecha`', CastDateFieldForLike("`fecha`", 7, "DB"), 133, 10, 7, false, '`fecha`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->fecha->Required = true; // Required field
        $this->fecha->Sortable = true; // Allow sort
        $this->fecha->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_SEPARATOR"], $Language->phrase("IncorrectDateDMY"));
        $this->fecha->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->fecha->Param, "CustomMsg");
        $this->Fields['fecha'] = &$this->fecha;

        // banco
        $this->banco = new DbField('cont_lotes_pagos', 'cont_lotes_pagos', 'x_banco', 'banco', '`banco`', '`banco`', 3, 11, -1, false, '`banco`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->banco->Required = true; // Required field
        $this->banco->Sortable = true; // Allow sort
        $this->banco->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->banco->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->banco->Lookup = new Lookup('banco', 'view_banco', false, 'id', ["banco","numero","",""], [], [], [], [], [], [], '`id` ASC', '');
        $this->banco->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->banco->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->banco->Param, "CustomMsg");
        $this->Fields['banco'] = &$this->banco;

        // referencia
        $this->referencia = new DbField('cont_lotes_pagos', 'cont_lotes_pagos', 'x_referencia', 'referencia', '`referencia`', '`referencia`', 200, 50, -1, false, '`referencia`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->referencia->Required = true; // Required field
        $this->referencia->Sortable = true; // Allow sort
        $this->referencia->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->referencia->Param, "CustomMsg");
        $this->Fields['referencia'] = &$this->referencia;

        // moneda
        $this->moneda = new DbField('cont_lotes_pagos', 'cont_lotes_pagos', 'x_moneda', 'moneda', '`moneda`', '`moneda`', 200, 6, -1, false, '`moneda`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->moneda->Required = true; // Required field
        $this->moneda->Sortable = true; // Allow sort
        $this->moneda->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->moneda->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->moneda->Lookup = new Lookup('moneda', 'parametro', false, 'valor1', ["valor1","","",""], [], [], [], [], [], [], '`valor1` ASC', '');
        $this->moneda->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->moneda->Param, "CustomMsg");
        $this->Fields['moneda'] = &$this->moneda;

        // procesado
        $this->procesado = new DbField('cont_lotes_pagos', 'cont_lotes_pagos', 'x_procesado', 'procesado', '`procesado`', '`procesado`', 202, 1, -1, false, '`procesado`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->procesado->Required = true; // Required field
        $this->procesado->Sortable = true; // Allow sort
        $this->procesado->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->procesado->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->procesado->Lookup = new Lookup('procesado', 'cont_lotes_pagos', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->procesado->OptionCount = 2;
        $this->procesado->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->procesado->Param, "CustomMsg");
        $this->Fields['procesado'] = &$this->procesado;

        // nota
        $this->nota = new DbField('cont_lotes_pagos', 'cont_lotes_pagos', 'x_nota', 'nota', '`nota`', '`nota`', 200, 100, -1, false, '`nota`', false, false, false, 'FORMATTED TEXT', 'TEXTAREA');
        $this->nota->Sortable = true; // Allow sort
        $this->nota->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->nota->Param, "CustomMsg");
        $this->Fields['nota'] = &$this->nota;

        // fecha_registro
        $this->fecha_registro = new DbField('cont_lotes_pagos', 'cont_lotes_pagos', 'x_fecha_registro', 'fecha_registro', '`fecha_registro`', CastDateFieldForLike("`fecha_registro`", 0, "DB"), 135, 19, 0, false, '`fecha_registro`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->fecha_registro->Sortable = true; // Allow sort
        $this->fecha_registro->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->fecha_registro->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->fecha_registro->Param, "CustomMsg");
        $this->Fields['fecha_registro'] = &$this->fecha_registro;

        // usuario
        $this->usuario = new DbField('cont_lotes_pagos', 'cont_lotes_pagos', 'x_usuario', 'usuario', '`usuario`', '`usuario`', 200, 30, -1, false, '`usuario`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->usuario->Sortable = true; // Allow sort
        $this->usuario->Lookup = new Lookup('usuario', 'usuario', false, 'username', ["nombre","","",""], [], [], [], [], [], [], '', '');
        $this->usuario->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->usuario->Param, "CustomMsg");
        $this->Fields['usuario'] = &$this->usuario;

        // comprobante
        $this->comprobante = new DbField('cont_lotes_pagos', 'cont_lotes_pagos', 'x_comprobante', 'comprobante', '`comprobante`', '`comprobante`', 202, 1, -1, false, '`comprobante`', false, false, false, 'FORMATTED TEXT', 'RADIO');
        $this->comprobante->Sortable = true; // Allow sort
        $this->comprobante->Lookup = new Lookup('comprobante', 'cont_lotes_pagos', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->comprobante->OptionCount = 2;
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
        if ($this->getCurrentDetailTable() == "cont_lotes_pagos_detalle") {
            $detailUrl = Container("cont_lotes_pagos_detalle")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue);
        }
        if ($detailUrl == "") {
            $detailUrl = "ContLotesPagosList";
        }
        return $detailUrl;
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`cont_lotes_pagos`";
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

        // Cascade delete detail table 'cont_lotes_pagos_detalle'
        $dtlrows = Container("cont_lotes_pagos_detalle")->loadRs("`cont_lotes_pago` = " . QuotedValue($rs['id'], DATATYPE_NUMBER, "DB"))->fetchAll(\PDO::FETCH_ASSOC);
        // Call Row Deleting event
        foreach ($dtlrows as $dtlrow) {
            $success = Container("cont_lotes_pagos_detalle")->rowDeleting($dtlrow);
            if (!$success) {
                break;
            }
        }
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                $success = Container("cont_lotes_pagos_detalle")->delete($dtlrow); // Delete
                if (!$success) {
                    break;
                }
            }
        }
        // Call Row Deleted event
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                Container("cont_lotes_pagos_detalle")->rowDeleted($dtlrow);
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
        $this->fecha->DbValue = $row['fecha'];
        $this->banco->DbValue = $row['banco'];
        $this->referencia->DbValue = $row['referencia'];
        $this->moneda->DbValue = $row['moneda'];
        $this->procesado->DbValue = $row['procesado'];
        $this->nota->DbValue = $row['nota'];
        $this->fecha_registro->DbValue = $row['fecha_registro'];
        $this->usuario->DbValue = $row['usuario'];
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
        return $_SESSION[$name] ?? GetUrl("ContLotesPagosList");
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
        if ($pageName == "ContLotesPagosView") {
            return $Language->phrase("View");
        } elseif ($pageName == "ContLotesPagosEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "ContLotesPagosAdd") {
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
                return "ContLotesPagosView";
            case Config("API_ADD_ACTION"):
                return "ContLotesPagosAdd";
            case Config("API_EDIT_ACTION"):
                return "ContLotesPagosEdit";
            case Config("API_DELETE_ACTION"):
                return "ContLotesPagosDelete";
            case Config("API_LIST_ACTION"):
                return "ContLotesPagosList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "ContLotesPagosList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ContLotesPagosView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("ContLotesPagosView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "ContLotesPagosAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "ContLotesPagosAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ContLotesPagosEdit", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("ContLotesPagosEdit", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
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
            $url = $this->keyUrl("ContLotesPagosAdd", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("ContLotesPagosAdd", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
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
        return $this->keyUrl("ContLotesPagosDelete", $this->getUrlParm());
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
        $this->fecha->setDbValue($row['fecha']);
        $this->banco->setDbValue($row['banco']);
        $this->referencia->setDbValue($row['referencia']);
        $this->moneda->setDbValue($row['moneda']);
        $this->procesado->setDbValue($row['procesado']);
        $this->nota->setDbValue($row['nota']);
        $this->fecha_registro->setDbValue($row['fecha_registro']);
        $this->usuario->setDbValue($row['usuario']);
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

        // fecha

        // banco

        // referencia

        // moneda

        // procesado

        // nota

        // fecha_registro

        // usuario

        // comprobante

        // id
        $this->id->ViewValue = $this->id->CurrentValue;
        $this->id->ViewCustomAttributes = "";

        // fecha
        $this->fecha->ViewValue = $this->fecha->CurrentValue;
        $this->fecha->ViewValue = FormatDateTime($this->fecha->ViewValue, 7);
        $this->fecha->ViewCustomAttributes = "";

        // banco
        $curVal = trim(strval($this->banco->CurrentValue));
        if ($curVal != "") {
            $this->banco->ViewValue = $this->banco->lookupCacheOption($curVal);
            if ($this->banco->ViewValue === null) { // Lookup from database
                $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $lookupFilter = function() {
                    return "`pago_electronico` = 'S'";
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

        // referencia
        $this->referencia->ViewValue = $this->referencia->CurrentValue;
        $this->referencia->ViewCustomAttributes = "";

        // moneda
        $curVal = trim(strval($this->moneda->CurrentValue));
        if ($curVal != "") {
            $this->moneda->ViewValue = $this->moneda->lookupCacheOption($curVal);
            if ($this->moneda->ViewValue === null) { // Lookup from database
                $filterWrk = "`valor1`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                $lookupFilter = function() {
                    return "`codigo` = '006'";
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->moneda->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->moneda->Lookup->renderViewRow($rswrk[0]);
                    $this->moneda->ViewValue = $this->moneda->displayValue($arwrk);
                } else {
                    $this->moneda->ViewValue = $this->moneda->CurrentValue;
                }
            }
        } else {
            $this->moneda->ViewValue = null;
        }
        $this->moneda->ViewCustomAttributes = "";

        // procesado
        if (strval($this->procesado->CurrentValue) != "") {
            $this->procesado->ViewValue = $this->procesado->optionCaption($this->procesado->CurrentValue);
        } else {
            $this->procesado->ViewValue = null;
        }
        $this->procesado->ViewCustomAttributes = "";

        // nota
        $this->nota->ViewValue = $this->nota->CurrentValue;
        $this->nota->ViewCustomAttributes = "";

        // fecha_registro
        $this->fecha_registro->ViewValue = $this->fecha_registro->CurrentValue;
        $this->fecha_registro->ViewValue = FormatDateTime($this->fecha_registro->ViewValue, 0);
        $this->fecha_registro->ViewCustomAttributes = "";

        // usuario
        $this->usuario->ViewValue = $this->usuario->CurrentValue;
        $curVal = trim(strval($this->usuario->CurrentValue));
        if ($curVal != "") {
            $this->usuario->ViewValue = $this->usuario->lookupCacheOption($curVal);
            if ($this->usuario->ViewValue === null) { // Lookup from database
                $filterWrk = "`username`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                $sqlWrk = $this->usuario->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->usuario->Lookup->renderViewRow($rswrk[0]);
                    $this->usuario->ViewValue = $this->usuario->displayValue($arwrk);
                } else {
                    $this->usuario->ViewValue = $this->usuario->CurrentValue;
                }
            }
        } else {
            $this->usuario->ViewValue = null;
        }
        $this->usuario->ViewCustomAttributes = "";

        // comprobante
        if (strval($this->comprobante->CurrentValue) != "") {
            $this->comprobante->ViewValue = $this->comprobante->optionCaption($this->comprobante->CurrentValue);
        } else {
            $this->comprobante->ViewValue = null;
        }
        $this->comprobante->ViewCustomAttributes = "";

        // id
        $this->id->LinkCustomAttributes = "";
        $this->id->HrefValue = "";
        $this->id->TooltipValue = "";

        // fecha
        $this->fecha->LinkCustomAttributes = "";
        $this->fecha->HrefValue = "";
        $this->fecha->TooltipValue = "";

        // banco
        $this->banco->LinkCustomAttributes = "";
        $this->banco->HrefValue = "";
        $this->banco->TooltipValue = "";

        // referencia
        $this->referencia->LinkCustomAttributes = "";
        $this->referencia->HrefValue = "";
        $this->referencia->TooltipValue = "";

        // moneda
        $this->moneda->LinkCustomAttributes = "";
        $this->moneda->HrefValue = "";
        $this->moneda->TooltipValue = "";

        // procesado
        $this->procesado->LinkCustomAttributes = "";
        $this->procesado->HrefValue = "";
        $this->procesado->TooltipValue = "";

        // nota
        $this->nota->LinkCustomAttributes = "";
        $this->nota->HrefValue = "";
        $this->nota->TooltipValue = "";

        // fecha_registro
        $this->fecha_registro->LinkCustomAttributes = "";
        $this->fecha_registro->HrefValue = "";
        $this->fecha_registro->TooltipValue = "";

        // usuario
        $this->usuario->LinkCustomAttributes = "";
        $this->usuario->HrefValue = "";
        $this->usuario->TooltipValue = "";

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

        // fecha
        $this->fecha->EditAttrs["class"] = "form-control";
        $this->fecha->EditCustomAttributes = "";
        $this->fecha->EditValue = $this->fecha->CurrentValue;
        $this->fecha->EditValue = FormatDateTime($this->fecha->EditValue, 7);
        $this->fecha->ViewCustomAttributes = "";

        // banco
        $this->banco->EditAttrs["class"] = "form-control";
        $this->banco->EditCustomAttributes = "";
        $curVal = trim(strval($this->banco->CurrentValue));
        if ($curVal != "") {
            $this->banco->EditValue = $this->banco->lookupCacheOption($curVal);
            if ($this->banco->EditValue === null) { // Lookup from database
                $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $lookupFilter = function() {
                    return "`pago_electronico` = 'S'";
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->banco->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->banco->Lookup->renderViewRow($rswrk[0]);
                    $this->banco->EditValue = $this->banco->displayValue($arwrk);
                } else {
                    $this->banco->EditValue = $this->banco->CurrentValue;
                }
            }
        } else {
            $this->banco->EditValue = null;
        }
        $this->banco->ViewCustomAttributes = "";

        // referencia
        $this->referencia->EditAttrs["class"] = "form-control";
        $this->referencia->EditCustomAttributes = "";
        $this->referencia->EditValue = $this->referencia->CurrentValue;
        $this->referencia->ViewCustomAttributes = "";

        // moneda
        $this->moneda->EditAttrs["class"] = "form-control";
        $this->moneda->EditCustomAttributes = "";
        $curVal = trim(strval($this->moneda->CurrentValue));
        if ($curVal != "") {
            $this->moneda->EditValue = $this->moneda->lookupCacheOption($curVal);
            if ($this->moneda->EditValue === null) { // Lookup from database
                $filterWrk = "`valor1`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                $lookupFilter = function() {
                    return "`codigo` = '006'";
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->moneda->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->moneda->Lookup->renderViewRow($rswrk[0]);
                    $this->moneda->EditValue = $this->moneda->displayValue($arwrk);
                } else {
                    $this->moneda->EditValue = $this->moneda->CurrentValue;
                }
            }
        } else {
            $this->moneda->EditValue = null;
        }
        $this->moneda->ViewCustomAttributes = "";

        // procesado
        $this->procesado->EditAttrs["class"] = "form-control";
        $this->procesado->EditCustomAttributes = "";
        $this->procesado->EditValue = $this->procesado->options(true);
        $this->procesado->PlaceHolder = RemoveHtml($this->procesado->caption());

        // nota
        $this->nota->EditAttrs["class"] = "form-control";
        $this->nota->EditCustomAttributes = "";
        $this->nota->EditValue = $this->nota->CurrentValue;
        $this->nota->PlaceHolder = RemoveHtml($this->nota->caption());

        // fecha_registro
        $this->fecha_registro->EditAttrs["class"] = "form-control";
        $this->fecha_registro->EditCustomAttributes = "";
        $this->fecha_registro->EditValue = FormatDateTime($this->fecha_registro->CurrentValue, 8);
        $this->fecha_registro->PlaceHolder = RemoveHtml($this->fecha_registro->caption());

        // usuario
        $this->usuario->EditAttrs["class"] = "form-control";
        $this->usuario->EditCustomAttributes = "";
        if (!$this->usuario->Raw) {
            $this->usuario->CurrentValue = HtmlDecode($this->usuario->CurrentValue);
        }
        $this->usuario->EditValue = $this->usuario->CurrentValue;
        $this->usuario->PlaceHolder = RemoveHtml($this->usuario->caption());

        // comprobante
        $this->comprobante->EditCustomAttributes = "";
        $this->comprobante->EditValue = $this->comprobante->options(false);
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
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->banco);
                    $doc->exportCaption($this->referencia);
                    $doc->exportCaption($this->moneda);
                    $doc->exportCaption($this->procesado);
                    $doc->exportCaption($this->nota);
                    $doc->exportCaption($this->usuario);
                } else {
                    $doc->exportCaption($this->id);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->banco);
                    $doc->exportCaption($this->referencia);
                    $doc->exportCaption($this->moneda);
                    $doc->exportCaption($this->procesado);
                    $doc->exportCaption($this->nota);
                    $doc->exportCaption($this->fecha_registro);
                    $doc->exportCaption($this->usuario);
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
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->banco);
                        $doc->exportField($this->referencia);
                        $doc->exportField($this->moneda);
                        $doc->exportField($this->procesado);
                        $doc->exportField($this->nota);
                        $doc->exportField($this->usuario);
                    } else {
                        $doc->exportField($this->id);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->banco);
                        $doc->exportField($this->referencia);
                        $doc->exportField($this->moneda);
                        $doc->exportField($this->procesado);
                        $doc->exportField($this->nota);
                        $doc->exportField($this->fecha_registro);
                        $doc->exportField($this->usuario);
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

    // Write Audit Trail start/end for grid update
    public function writeAuditTrailDummy($typ)
    {
        $table = 'cont_lotes_pagos';
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
        $table = 'cont_lotes_pagos';

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
        $table = 'cont_lotes_pagos';

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
        $table = 'cont_lotes_pagos';

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

        /* Se comenta esta validación para que se puedan seguir montando lotes sin procesar los pendientes */
        $sql = "SELECT id FROM cont_lotes_pagos WHERE procesado = 'N';";
    	if($row = ExecuteRow($sql)) {
    		$this->CancelMessage = "Tiene pendiente por procesar un lote; proceselo o eliminelo antes de crear uno nuevo.";
    		return FALSE;
    	}
        $rsnew["fecha"] = date("Y-m-d H:i:s");
    	$rsnew["fecha_registro"] = date("Y-m-d H:i:s");
    	$rsnew["usuario"] = CurrentUserName();
    	$rsnew["procesado"] = "N";
    	return true;
    }

    // Row Inserted event
    public function rowInserted($rsold, &$rsnew)
    {
    	$sql = "INSERT INTO cont_lotes_pagos_detalle
    				(Id, cont_lotes_pago, id_documento, proveedor, tipo_documento, tipodoc, nro_documento, monto_a_pagar, monto_pagado, saldo, fecha)				
    			SELECT 
    					NULL AS id, " . $rsnew["id"] . " AS lote, 
    					aa.id_documento, aa.proveedor, aa.tipo_documento,
    					aa.tipodoc, aa.nro_documento, 
    					aa.monto_pagar, aa.monto_pagado,
    					(aa.monto_pagar-ABS(IFNULL(aa.monto_pagado, 0))) AS saldo, aa.fecha  
    			FROM	
    				(			
    				SELECT 
    					a.id AS id_documento, a.proveedor, a.tipo_documento,
    					b.descripcion, a.nro_documento, 
    					a.monto_pagar, a.monto_pagado, a.tipodoc, a.fecha  
    				FROM 
    					view_x_pagar AS a
    					JOIN cont_mes_contable AS b ON b.tipo_comprobante = a.tipo_documento 
    				WHERE 
    					IFNULL(a.monto_pagar, 0) > 0 
    					AND IFNULL(a.monto_pagar, 0) > IFNULL(a.monto_pagado, 0) 
    					AND a.fecha > '2021-07-31'
    				) AS aa 
    				LEFT OUTER JOIN 
    				cont_lotes_pagos_detalle AS bb ON bb.id_documento = aa.id_documento 
    										AND bb.tipo_documento = aa.tipo_documento
    										AND bb.monto_a_pagar = bb.monto_pagado 
    			WHERE bb.Id IS NULL;";
    	Execute($sql);
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
    	if($rsold["procesado"] == "S") {
    		$this->CancelMessage = "El lote est&aacute; procesado, no se puede modificar.";
    		return FALSE;
    	}
    	if($rsnew["procesado"] == "S") {
            $sql = "SELECT valor1 FROM parametro WHERE codigo = '027';";
            $CmpbISLRAuto = ExecuteScalar($sql);
            $sql = "SELECT id_documento, tipo_documento, tipodoc, Id  
            		FROM cont_lotes_pagos_detalle 
            		WHERE cont_lotes_pago = " . $rsold["id"] . ";";
            $rows = ExecuteRows($sql);
            foreach($rows as $key=>$value) {
            	$id_documento = $value["id_documento"];
            	$tipo_documento = $value["tipo_documento"];
            	$tipodoc = $value["tipodoc"];
            	$id = $value["Id"];
            	if($tipodoc != "RC") {
            		if($CmpbISLRAuto == "S") {
    					$sql = "SELECT valor1, valor2, valor3 FROM parametro WHERE codigo = '024';";
    					$row = ExecuteRow($sql);
    					$numero = intval($row["valor1"]) + 1;
    					$prefijo = trim($row["valor2"]);
    					$prefijo .= substr($rsold["fecha"], 0, 4) . substr($rsold["fecha"], 5, 2);
    					$padeo = intval($row["valor3"]);
    					$comprobante = $prefijo . str_pad($numero, $padeo, "0", STR_PAD_LEFT);
    					$sql = "UPDATE parametro SET valor1='$numero' 
    							WHERE codigo = '024';";
    					Execute($sql);
    					switch($tipo_documento) {
    					case "COMPRA":
    						$sql = "UPDATE compra SET ref_islr = '$comprobante' 
    								WHERE id = '$id_documento';";
    						break;
    					case "TDCFCC":
    						$sql = "UPDATE entradas SET ref_islr = '$comprobante' 
    								WHERE id = '$id_documento' AND tipo_documento = '$tipo_documento';";
    						break;
    					default:
    						die("No tipo documento ???");
    						break;
    					}
    					Execute($sql);
    				}
    			}
    			$sql = "UPDATE cont_lotes_pagos_detalle
    					SET monto_pagado = IFNULL(monto_pagado, 0) + saldo
    					WHERE Id = $id;";
    			Execute($sql);

    			// Se generan los encabezados y detalles de las tablas de pagos_proveedor
    			$sql = "INSERT INTO pagos_proveedor
    						(id, proveedor, tipo_pago, banco, fecha, referencia, 
    						monto_dado, monto, moneda, nota, fecha_registro, 
    						username, comprobante, pivote, cont_lotes)
    					SELECT 
    						NULL AS pagos_proveedor, a.proveedor, 'TR' AS tipo_pago, b.banco, CURDATE() AS fecha, b.referencia, 
    						a.saldo AS monto_dado, a.saldo AS monto, b.moneda, CONCAT('PAGO EN LOTE # ', b.id) AS nota, CURDATE() AS fecha_registro, 
    						'' AS username, NULL AS comprobante, NULL AS pivote, b.id AS cont_lotes 
    					FROM 
    						cont_lotes_pagos_detalle AS a 
    						JOIN cont_lotes_pagos AS b ON b.id = a.cont_lotes_pago 
    					WHERE a.id = $id;";
    			Execute($sql);
    			$sql = "SELECT LAST_INSERT_ID();";
    			$newid = ExecuteScalar($sql);
    			$sql = "INSERT INTO pagos_proveedor_factura
    						(id, pagos_proveedor, tipo_documento, id_documento, 
    						abono, monto, retiva, retivamonto, retislr, retislrmonto, comprobante)
    					SELECT 
    						NULL AS pagos_proveedor_factura, $newid AS pagos_proveedor,
    						a.tipo_documento, a.id_documento, 
    						IF(a.saldo < a.monto_a_pagar, 'S', 'N') AS abono, 
    						a.saldo AS monto, '', 0, '', 0, 0 
    					FROM 
    						cont_lotes_pagos_detalle AS a 
    						JOIN cont_lotes_pagos AS b ON b.id = a.cont_lotes_pago 
    					WHERE a.id = $id;";
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
    	if($rs["procesado"] == "S") {
    		$this->CancelMessage = "Lote procesado, no se puede eliminar.";
    		return FALSE;
    	}
        return true;
    }

    // Row Deleted event
    public function rowDeleted(&$rs)
    {
        //Log("Row Deleted");
        $sql = "DELETE 
        		FROM pagos_proveedor_factura
        		WHERE pagos_proveedor IN (SELECT id
        									FROM pagos_proveedor
        									WHERE cont_lotes = " . $rs["id"] . ");";
        Execute($sql);
        $sql = "DELETE FROM pagos_proveedor WHERE cont_lotes = " . $rs["id"] . ";";
        Execute($sql);
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
