<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for cont_lotes_pagos_detalle
 */
class ContLotesPagosDetalle extends DbTable
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
    public $Id;
    public $cont_lotes_pago;
    public $id_documento;
    public $proveedor;
    public $tipo_documento;
    public $fecha;
    public $tipodoc;
    public $nro_documento;
    public $monto_a_pagar;
    public $monto_pagado;
    public $saldo;
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
        $this->TableVar = 'cont_lotes_pagos_detalle';
        $this->TableName = 'cont_lotes_pagos_detalle';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`cont_lotes_pagos_detalle`";
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

        // Id
        $this->Id = new DbField('cont_lotes_pagos_detalle', 'cont_lotes_pagos_detalle', 'x_Id', 'Id', '`Id`', '`Id`', 19, 10, -1, false, '`Id`', false, false, false, 'FORMATTED TEXT', 'NO');
        $this->Id->IsAutoIncrement = true; // Autoincrement field
        $this->Id->IsPrimaryKey = true; // Primary key field
        $this->Id->Sortable = true; // Allow sort
        $this->Id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Id->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->Id->Param, "CustomMsg");
        $this->Fields['Id'] = &$this->Id;

        // cont_lotes_pago
        $this->cont_lotes_pago = new DbField('cont_lotes_pagos_detalle', 'cont_lotes_pagos_detalle', 'x_cont_lotes_pago', 'cont_lotes_pago', '`cont_lotes_pago`', '`cont_lotes_pago`', 19, 10, -1, false, '`cont_lotes_pago`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->cont_lotes_pago->IsForeignKey = true; // Foreign key field
        $this->cont_lotes_pago->Sortable = true; // Allow sort
        $this->cont_lotes_pago->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->cont_lotes_pago->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->cont_lotes_pago->Param, "CustomMsg");
        $this->Fields['cont_lotes_pago'] = &$this->cont_lotes_pago;

        // id_documento
        $this->id_documento = new DbField('cont_lotes_pagos_detalle', 'cont_lotes_pagos_detalle', 'x_id_documento', 'id_documento', '`id_documento`', '`id_documento`', 19, 10, -1, false, '`id_documento`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->id_documento->Sortable = true; // Allow sort
        $this->id_documento->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id_documento->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->id_documento->Param, "CustomMsg");
        $this->Fields['id_documento'] = &$this->id_documento;

        // proveedor
        $this->proveedor = new DbField('cont_lotes_pagos_detalle', 'cont_lotes_pagos_detalle', 'x_proveedor', 'proveedor', '`proveedor`', '`proveedor`', 19, 10, -1, false, '`proveedor`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->proveedor->Sortable = true; // Allow sort
        $this->proveedor->Lookup = new Lookup('proveedor', 'proveedor', false, 'id', ["nombre","","",""], [], [], [], [], [], [], '`nombre`', '');
        $this->proveedor->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->proveedor->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->proveedor->Param, "CustomMsg");
        $this->Fields['proveedor'] = &$this->proveedor;

        // tipo_documento
        $this->tipo_documento = new DbField('cont_lotes_pagos_detalle', 'cont_lotes_pagos_detalle', 'x_tipo_documento', 'tipo_documento', '`tipo_documento`', '`tipo_documento`', 200, 50, -1, false, '`tipo_documento`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->tipo_documento->Sortable = true; // Allow sort
        $this->tipo_documento->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tipo_documento->Param, "CustomMsg");
        $this->Fields['tipo_documento'] = &$this->tipo_documento;

        // fecha
        $this->fecha = new DbField('cont_lotes_pagos_detalle', 'cont_lotes_pagos_detalle', 'x_fecha', 'fecha', '`fecha`', CastDateFieldForLike("`fecha`", 7, "DB"), 133, 10, 7, false, '`fecha`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->fecha->Sortable = true; // Allow sort
        $this->fecha->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_SEPARATOR"], $Language->phrase("IncorrectDateDMY"));
        $this->fecha->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->fecha->Param, "CustomMsg");
        $this->Fields['fecha'] = &$this->fecha;

        // tipodoc
        $this->tipodoc = new DbField('cont_lotes_pagos_detalle', 'cont_lotes_pagos_detalle', 'x_tipodoc', 'tipodoc', '`tipodoc`', '`tipodoc`', 200, 50, -1, false, '`tipodoc`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->tipodoc->Sortable = true; // Allow sort
        $this->tipodoc->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->tipodoc->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->tipodoc->Lookup = new Lookup('tipodoc', 'cont_lotes_pagos_detalle', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->tipodoc->OptionCount = 4;
        $this->tipodoc->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tipodoc->Param, "CustomMsg");
        $this->Fields['tipodoc'] = &$this->tipodoc;

        // nro_documento
        $this->nro_documento = new DbField('cont_lotes_pagos_detalle', 'cont_lotes_pagos_detalle', 'x_nro_documento', 'nro_documento', '`nro_documento`', '`nro_documento`', 200, 50, -1, false, '`nro_documento`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->nro_documento->Sortable = true; // Allow sort
        $this->nro_documento->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->nro_documento->Param, "CustomMsg");
        $this->Fields['nro_documento'] = &$this->nro_documento;

        // monto_a_pagar
        $this->monto_a_pagar = new DbField('cont_lotes_pagos_detalle', 'cont_lotes_pagos_detalle', 'x_monto_a_pagar', 'monto_a_pagar', '`monto_a_pagar`', '`monto_a_pagar`', 131, 14, -1, false, '`monto_a_pagar`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->monto_a_pagar->Sortable = true; // Allow sort
        $this->monto_a_pagar->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->monto_a_pagar->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_a_pagar->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->monto_a_pagar->Param, "CustomMsg");
        $this->Fields['monto_a_pagar'] = &$this->monto_a_pagar;

        // monto_pagado
        $this->monto_pagado = new DbField('cont_lotes_pagos_detalle', 'cont_lotes_pagos_detalle', 'x_monto_pagado', 'monto_pagado', '`monto_pagado`', '`monto_pagado`', 131, 14, -1, false, '`monto_pagado`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->monto_pagado->Sortable = true; // Allow sort
        $this->monto_pagado->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->monto_pagado->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_pagado->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->monto_pagado->Param, "CustomMsg");
        $this->Fields['monto_pagado'] = &$this->monto_pagado;

        // saldo
        $this->saldo = new DbField('cont_lotes_pagos_detalle', 'cont_lotes_pagos_detalle', 'x_saldo', 'saldo', '`saldo`', '`saldo`', 131, 14, -1, false, '`saldo`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->saldo->Sortable = true; // Allow sort
        $this->saldo->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->saldo->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->saldo->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->saldo->Param, "CustomMsg");
        $this->Fields['saldo'] = &$this->saldo;

        // comprobante
        $this->comprobante = new DbField('cont_lotes_pagos_detalle', 'cont_lotes_pagos_detalle', 'x_comprobante', 'comprobante', '`comprobante`', '`comprobante`', 3, 11, -1, false, '`comprobante`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->comprobante->Sortable = true; // Allow sort
        $this->comprobante->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
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
        if ($this->getCurrentMasterTable() == "cont_lotes_pagos") {
            if ($this->cont_lotes_pago->getSessionValue() != "") {
                $masterFilter .= "" . GetForeignKeySql("`id`", $this->cont_lotes_pago->getSessionValue(), DATATYPE_NUMBER, "DB");
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
        if ($this->getCurrentMasterTable() == "cont_lotes_pagos") {
            if ($this->cont_lotes_pago->getSessionValue() != "") {
                $detailFilter .= "" . GetForeignKeySql("`cont_lotes_pago`", $this->cont_lotes_pago->getSessionValue(), DATATYPE_NUMBER, "DB");
            } else {
                return "";
            }
        }
        return $detailFilter;
    }

    // Master filter
    public function sqlMasterFilter_cont_lotes_pagos()
    {
        return "`id`=@id@";
    }
    // Detail filter
    public function sqlDetailFilter_cont_lotes_pagos()
    {
        return "`cont_lotes_pago`=@cont_lotes_pago@";
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`cont_lotes_pagos_detalle`";
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
            $this->Id->setDbValue($conn->lastInsertId());
            $rs['Id'] = $this->Id->DbValue;
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
            $fldname = 'Id';
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
            if (array_key_exists('Id', $rs)) {
                AddFilter($where, QuotedName('Id', $this->Dbid) . '=' . QuotedValue($rs['Id'], $this->Id->DataType, $this->Dbid));
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
        $this->Id->DbValue = $row['Id'];
        $this->cont_lotes_pago->DbValue = $row['cont_lotes_pago'];
        $this->id_documento->DbValue = $row['id_documento'];
        $this->proveedor->DbValue = $row['proveedor'];
        $this->tipo_documento->DbValue = $row['tipo_documento'];
        $this->fecha->DbValue = $row['fecha'];
        $this->tipodoc->DbValue = $row['tipodoc'];
        $this->nro_documento->DbValue = $row['nro_documento'];
        $this->monto_a_pagar->DbValue = $row['monto_a_pagar'];
        $this->monto_pagado->DbValue = $row['monto_pagado'];
        $this->saldo->DbValue = $row['saldo'];
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
        return "`Id` = @Id@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->Id->CurrentValue : $this->Id->OldValue;
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
                $this->Id->CurrentValue = $keys[0];
            } else {
                $this->Id->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('Id', $row) ? $row['Id'] : null;
        } else {
            $val = $this->Id->OldValue !== null ? $this->Id->OldValue : $this->Id->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@Id@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
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
        return $_SESSION[$name] ?? GetUrl("ContLotesPagosDetalleList");
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
        if ($pageName == "ContLotesPagosDetalleView") {
            return $Language->phrase("View");
        } elseif ($pageName == "ContLotesPagosDetalleEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "ContLotesPagosDetalleAdd") {
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
                return "ContLotesPagosDetalleView";
            case Config("API_ADD_ACTION"):
                return "ContLotesPagosDetalleAdd";
            case Config("API_EDIT_ACTION"):
                return "ContLotesPagosDetalleEdit";
            case Config("API_DELETE_ACTION"):
                return "ContLotesPagosDetalleDelete";
            case Config("API_LIST_ACTION"):
                return "ContLotesPagosDetalleList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "ContLotesPagosDetalleList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ContLotesPagosDetalleView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("ContLotesPagosDetalleView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "ContLotesPagosDetalleAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "ContLotesPagosDetalleAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("ContLotesPagosDetalleEdit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("ContLotesPagosDetalleAdd", $this->getUrlParm($parm));
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
        return $this->keyUrl("ContLotesPagosDetalleDelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        if ($this->getCurrentMasterTable() == "cont_lotes_pagos" && !ContainsString($url, Config("TABLE_SHOW_MASTER") . "=")) {
            $url .= (ContainsString($url, "?") ? "&" : "?") . Config("TABLE_SHOW_MASTER") . "=" . $this->getCurrentMasterTable();
            $url .= "&" . GetForeignKeyUrl("fk_id", $this->cont_lotes_pago->CurrentValue ?? $this->cont_lotes_pago->getSessionValue());
        }
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "Id:" . JsonEncode($this->Id->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->Id->CurrentValue !== null) {
            $url .= "/" . rawurlencode($this->Id->CurrentValue);
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
            if (($keyValue = Param("Id") ?? Route("Id")) !== null) {
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
                $this->Id->CurrentValue = $key;
            } else {
                $this->Id->OldValue = $key;
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
        $this->Id->setDbValue($row['Id']);
        $this->cont_lotes_pago->setDbValue($row['cont_lotes_pago']);
        $this->id_documento->setDbValue($row['id_documento']);
        $this->proveedor->setDbValue($row['proveedor']);
        $this->tipo_documento->setDbValue($row['tipo_documento']);
        $this->fecha->setDbValue($row['fecha']);
        $this->tipodoc->setDbValue($row['tipodoc']);
        $this->nro_documento->setDbValue($row['nro_documento']);
        $this->monto_a_pagar->setDbValue($row['monto_a_pagar']);
        $this->monto_pagado->setDbValue($row['monto_pagado']);
        $this->saldo->setDbValue($row['saldo']);
        $this->comprobante->setDbValue($row['comprobante']);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // Id

        // cont_lotes_pago

        // id_documento

        // proveedor

        // tipo_documento

        // fecha

        // tipodoc

        // nro_documento

        // monto_a_pagar

        // monto_pagado

        // saldo

        // comprobante

        // Id
        $this->Id->ViewValue = $this->Id->CurrentValue;
        $this->Id->ViewCustomAttributes = "";

        // cont_lotes_pago
        $this->cont_lotes_pago->ViewValue = $this->cont_lotes_pago->CurrentValue;
        $this->cont_lotes_pago->ViewValue = FormatNumber($this->cont_lotes_pago->ViewValue, 0, -2, -2, -2);
        $this->cont_lotes_pago->ViewCustomAttributes = "";

        // id_documento
        $this->id_documento->ViewValue = $this->id_documento->CurrentValue;
        $this->id_documento->ViewValue = FormatNumber($this->id_documento->ViewValue, 0, -2, -2, -2);
        $this->id_documento->ViewCustomAttributes = "";

        // proveedor
        $this->proveedor->ViewValue = $this->proveedor->CurrentValue;
        $curVal = trim(strval($this->proveedor->CurrentValue));
        if ($curVal != "") {
            $this->proveedor->ViewValue = $this->proveedor->lookupCacheOption($curVal);
            if ($this->proveedor->ViewValue === null) { // Lookup from database
                $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $sqlWrk = $this->proveedor->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->proveedor->Lookup->renderViewRow($rswrk[0]);
                    $this->proveedor->ViewValue = $this->proveedor->displayValue($arwrk);
                } else {
                    $this->proveedor->ViewValue = $this->proveedor->CurrentValue;
                }
            }
        } else {
            $this->proveedor->ViewValue = null;
        }
        $this->proveedor->ViewCustomAttributes = "";

        // tipo_documento
        $this->tipo_documento->ViewValue = $this->tipo_documento->CurrentValue;
        $this->tipo_documento->ViewCustomAttributes = "";

        // fecha
        $this->fecha->ViewValue = $this->fecha->CurrentValue;
        $this->fecha->ViewValue = FormatDateTime($this->fecha->ViewValue, 7);
        $this->fecha->ViewCustomAttributes = "";

        // tipodoc
        if (strval($this->tipodoc->CurrentValue) != "") {
            $this->tipodoc->ViewValue = $this->tipodoc->optionCaption($this->tipodoc->CurrentValue);
        } else {
            $this->tipodoc->ViewValue = null;
        }
        $this->tipodoc->ViewCustomAttributes = "";

        // nro_documento
        $this->nro_documento->ViewValue = $this->nro_documento->CurrentValue;
        $this->nro_documento->ViewCustomAttributes = "";

        // monto_a_pagar
        $this->monto_a_pagar->ViewValue = $this->monto_a_pagar->CurrentValue;
        $this->monto_a_pagar->ViewValue = FormatNumber($this->monto_a_pagar->ViewValue, 2, -1, -1, -1);
        $this->monto_a_pagar->ViewCustomAttributes = "";

        // monto_pagado
        $this->monto_pagado->ViewValue = $this->monto_pagado->CurrentValue;
        $this->monto_pagado->ViewValue = FormatNumber($this->monto_pagado->ViewValue, 2, -1, -1, -1);
        $this->monto_pagado->ViewCustomAttributes = "";

        // saldo
        $this->saldo->ViewValue = $this->saldo->CurrentValue;
        $this->saldo->ViewValue = FormatNumber($this->saldo->ViewValue, 2, -1, -1, -1);
        $this->saldo->ViewCustomAttributes = "";

        // comprobante
        $this->comprobante->ViewValue = $this->comprobante->CurrentValue;
        $this->comprobante->ViewValue = FormatNumber($this->comprobante->ViewValue, 0, -2, -2, -2);
        $this->comprobante->ViewCustomAttributes = "";

        // Id
        $this->Id->LinkCustomAttributes = "";
        $this->Id->HrefValue = "";
        $this->Id->TooltipValue = "";

        // cont_lotes_pago
        $this->cont_lotes_pago->LinkCustomAttributes = "";
        $this->cont_lotes_pago->HrefValue = "";
        $this->cont_lotes_pago->TooltipValue = "";

        // id_documento
        $this->id_documento->LinkCustomAttributes = "";
        $this->id_documento->HrefValue = "";
        $this->id_documento->TooltipValue = "";

        // proveedor
        $this->proveedor->LinkCustomAttributes = "";
        $this->proveedor->HrefValue = "";
        $this->proveedor->TooltipValue = "";

        // tipo_documento
        $this->tipo_documento->LinkCustomAttributes = "";
        $this->tipo_documento->HrefValue = "";
        $this->tipo_documento->TooltipValue = "";

        // fecha
        $this->fecha->LinkCustomAttributes = "";
        $this->fecha->HrefValue = "";
        $this->fecha->TooltipValue = "";

        // tipodoc
        $this->tipodoc->LinkCustomAttributes = "";
        $this->tipodoc->HrefValue = "";
        $this->tipodoc->TooltipValue = "";

        // nro_documento
        $this->nro_documento->LinkCustomAttributes = "";
        $this->nro_documento->HrefValue = "";
        $this->nro_documento->TooltipValue = "";

        // monto_a_pagar
        $this->monto_a_pagar->LinkCustomAttributes = "";
        $this->monto_a_pagar->HrefValue = "";
        $this->monto_a_pagar->TooltipValue = "";

        // monto_pagado
        $this->monto_pagado->LinkCustomAttributes = "";
        $this->monto_pagado->HrefValue = "";
        $this->monto_pagado->TooltipValue = "";

        // saldo
        $this->saldo->LinkCustomAttributes = "";
        $this->saldo->HrefValue = "";
        $this->saldo->TooltipValue = "";

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

        // Id
        $this->Id->EditAttrs["class"] = "form-control";
        $this->Id->EditCustomAttributes = "";
        $this->Id->EditValue = $this->Id->CurrentValue;
        $this->Id->ViewCustomAttributes = "";

        // cont_lotes_pago
        $this->cont_lotes_pago->EditAttrs["class"] = "form-control";
        $this->cont_lotes_pago->EditCustomAttributes = "";
        if ($this->cont_lotes_pago->getSessionValue() != "") {
            $this->cont_lotes_pago->CurrentValue = GetForeignKeyValue($this->cont_lotes_pago->getSessionValue());
            $this->cont_lotes_pago->ViewValue = $this->cont_lotes_pago->CurrentValue;
            $this->cont_lotes_pago->ViewValue = FormatNumber($this->cont_lotes_pago->ViewValue, 0, -2, -2, -2);
            $this->cont_lotes_pago->ViewCustomAttributes = "";
        } else {
            $this->cont_lotes_pago->EditValue = $this->cont_lotes_pago->CurrentValue;
            $this->cont_lotes_pago->PlaceHolder = RemoveHtml($this->cont_lotes_pago->caption());
        }

        // id_documento
        $this->id_documento->EditAttrs["class"] = "form-control";
        $this->id_documento->EditCustomAttributes = "";
        $this->id_documento->EditValue = $this->id_documento->CurrentValue;
        $this->id_documento->PlaceHolder = RemoveHtml($this->id_documento->caption());

        // proveedor
        $this->proveedor->EditAttrs["class"] = "form-control";
        $this->proveedor->EditCustomAttributes = "";
        $this->proveedor->EditValue = $this->proveedor->CurrentValue;
        $curVal = trim(strval($this->proveedor->CurrentValue));
        if ($curVal != "") {
            $this->proveedor->EditValue = $this->proveedor->lookupCacheOption($curVal);
            if ($this->proveedor->EditValue === null) { // Lookup from database
                $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $sqlWrk = $this->proveedor->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->proveedor->Lookup->renderViewRow($rswrk[0]);
                    $this->proveedor->EditValue = $this->proveedor->displayValue($arwrk);
                } else {
                    $this->proveedor->EditValue = $this->proveedor->CurrentValue;
                }
            }
        } else {
            $this->proveedor->EditValue = null;
        }
        $this->proveedor->ViewCustomAttributes = "";

        // tipo_documento
        $this->tipo_documento->EditAttrs["class"] = "form-control";
        $this->tipo_documento->EditCustomAttributes = "";
        if (!$this->tipo_documento->Raw) {
            $this->tipo_documento->CurrentValue = HtmlDecode($this->tipo_documento->CurrentValue);
        }
        $this->tipo_documento->EditValue = $this->tipo_documento->CurrentValue;
        $this->tipo_documento->PlaceHolder = RemoveHtml($this->tipo_documento->caption());

        // fecha
        $this->fecha->EditAttrs["class"] = "form-control";
        $this->fecha->EditCustomAttributes = "";
        $this->fecha->EditValue = $this->fecha->CurrentValue;
        $this->fecha->EditValue = FormatDateTime($this->fecha->EditValue, 7);
        $this->fecha->ViewCustomAttributes = "";

        // tipodoc
        $this->tipodoc->EditAttrs["class"] = "form-control";
        $this->tipodoc->EditCustomAttributes = "";
        if (strval($this->tipodoc->CurrentValue) != "") {
            $this->tipodoc->EditValue = $this->tipodoc->optionCaption($this->tipodoc->CurrentValue);
        } else {
            $this->tipodoc->EditValue = null;
        }
        $this->tipodoc->ViewCustomAttributes = "";

        // nro_documento
        $this->nro_documento->EditAttrs["class"] = "form-control";
        $this->nro_documento->EditCustomAttributes = "";
        $this->nro_documento->EditValue = $this->nro_documento->CurrentValue;
        $this->nro_documento->ViewCustomAttributes = "";

        // monto_a_pagar
        $this->monto_a_pagar->EditAttrs["class"] = "form-control";
        $this->monto_a_pagar->EditCustomAttributes = "";
        $this->monto_a_pagar->EditValue = $this->monto_a_pagar->CurrentValue;
        $this->monto_a_pagar->EditValue = FormatNumber($this->monto_a_pagar->EditValue, 2, -1, -1, -1);
        $this->monto_a_pagar->ViewCustomAttributes = "";

        // monto_pagado
        $this->monto_pagado->EditAttrs["class"] = "form-control";
        $this->monto_pagado->EditCustomAttributes = "";
        $this->monto_pagado->EditValue = $this->monto_pagado->CurrentValue;
        $this->monto_pagado->EditValue = FormatNumber($this->monto_pagado->EditValue, 2, -1, -1, -1);
        $this->monto_pagado->ViewCustomAttributes = "";

        // saldo
        $this->saldo->EditAttrs["class"] = "form-control";
        $this->saldo->EditCustomAttributes = "";
        $this->saldo->EditValue = $this->saldo->CurrentValue;
        $this->saldo->PlaceHolder = RemoveHtml($this->saldo->caption());
        if (strval($this->saldo->EditValue) != "" && is_numeric($this->saldo->EditValue)) {
            $this->saldo->EditValue = FormatNumber($this->saldo->EditValue, -2, -1, -2, -1);
        }

        // comprobante
        $this->comprobante->EditAttrs["class"] = "form-control";
        $this->comprobante->EditCustomAttributes = "";
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
                    $doc->exportCaption($this->proveedor);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->tipodoc);
                    $doc->exportCaption($this->nro_documento);
                    $doc->exportCaption($this->monto_a_pagar);
                    $doc->exportCaption($this->monto_pagado);
                    $doc->exportCaption($this->saldo);
                    $doc->exportCaption($this->comprobante);
                } else {
                    $doc->exportCaption($this->Id);
                    $doc->exportCaption($this->cont_lotes_pago);
                    $doc->exportCaption($this->id_documento);
                    $doc->exportCaption($this->proveedor);
                    $doc->exportCaption($this->tipo_documento);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->tipodoc);
                    $doc->exportCaption($this->nro_documento);
                    $doc->exportCaption($this->monto_a_pagar);
                    $doc->exportCaption($this->monto_pagado);
                    $doc->exportCaption($this->saldo);
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
                        $doc->exportField($this->proveedor);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->tipodoc);
                        $doc->exportField($this->nro_documento);
                        $doc->exportField($this->monto_a_pagar);
                        $doc->exportField($this->monto_pagado);
                        $doc->exportField($this->saldo);
                        $doc->exportField($this->comprobante);
                    } else {
                        $doc->exportField($this->Id);
                        $doc->exportField($this->cont_lotes_pago);
                        $doc->exportField($this->id_documento);
                        $doc->exportField($this->proveedor);
                        $doc->exportField($this->tipo_documento);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->tipodoc);
                        $doc->exportField($this->nro_documento);
                        $doc->exportField($this->monto_a_pagar);
                        $doc->exportField($this->monto_pagado);
                        $doc->exportField($this->saldo);
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
        $table = 'cont_lotes_pagos_detalle';
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
        $table = 'cont_lotes_pagos_detalle';

        // Get key value
        $key = "";
        if ($key != "") {
            $key .= Config("COMPOSITE_KEY_SEPARATOR");
        }
        $key .= $rs['Id'];

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
        $table = 'cont_lotes_pagos_detalle';

        // Get key value
        $key = "";
        if ($key != "") {
            $key .= Config("COMPOSITE_KEY_SEPARATOR");
        }
        $key .= $rsold['Id'];

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
        $table = 'cont_lotes_pagos_detalle';

        // Get key value
        $key = "";
        if ($key != "") {
            $key .= Config("COMPOSITE_KEY_SEPARATOR");
        }
        $key .= $rs['Id'];

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
        $sql = "SELECT procesado FROM cont_lotes_pagos WHERE id = " . $rsold["cont_lotes_pago"] . ";";
    	if(ExecuteScalar($sql) == "S") {
    		$this->CancelMessage = "El lote est&aacute; procesado, no se puede modificar pagos a facturas del lote.";
    		return FALSE;
    	}
        $saldo = floatval($rsnew["saldo"]);
        $abonos = floatval($rsold["monto_pagado"]);
        $monto = floatval($rsold["monto_a_pagar"]);
    	if(($saldo+$abonos) > $monto) {
    		$this->CancelMessage = "El monto a pagar y/o abonar es mayor al monto a pagar.";
    		return FALSE;
    	}
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
        $sql = "SELECT procesado FROM cont_lotes_pagos WHERE id = " . $rs["cont_lotes_pago"] . ";";
    	if(ExecuteScalar($sql) == "S") {
    		$this->CancelMessage = "El lote est&aacute; procesado, no se puede eliminar facturas del lote.";
    		return FALSE;
    	}
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
