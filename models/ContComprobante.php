<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for cont_comprobante
 */
class ContComprobante extends DbTable
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
    public $tipo;
    public $fecha;
    public $descripcion;
    public $contabilizacion;
    public $registra;
    public $fecha_registro;
    public $contabiliza;
    public $fecha_contabiliza;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'cont_comprobante';
        $this->TableName = 'cont_comprobante';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`cont_comprobante`";
        $this->Dbid = 'DB';
        $this->ExportAll = true;
        $this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
        $this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
        $this->ExportPageSize = "a4"; // Page size (PDF only)
        $this->ExportExcelPageOrientation = ""; // Page orientation (PhpSpreadsheet only)
        $this->ExportExcelPageSize = ""; // Page size (PhpSpreadsheet only)
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
        $this->id = new DbField('cont_comprobante', 'cont_comprobante', 'x_id', 'id', '`id`', '`id`', 3, 11, -1, false, '`id`', false, false, false, 'FORMATTED TEXT', 'NO');
        $this->id->IsAutoIncrement = true; // Autoincrement field
        $this->id->IsPrimaryKey = true; // Primary key field
        $this->id->IsForeignKey = true; // Foreign key field
        $this->id->Sortable = true; // Allow sort
        $this->id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->id->Param, "CustomMsg");
        $this->Fields['id'] = &$this->id;

        // tipo
        $this->tipo = new DbField('cont_comprobante', 'cont_comprobante', 'x_tipo', 'tipo', '`tipo`', '`tipo`', 200, 6, -1, false, '`tipo`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->tipo->Required = true; // Required field
        $this->tipo->Sortable = true; // Allow sort
        $this->tipo->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->tipo->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->tipo->Lookup = new Lookup('tipo', 'cont_mes_contable', false, 'tipo_comprobante', ["descripcion","","",""], [], [], [], [], [], [], '`descripcion`', '');
        $this->tipo->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tipo->Param, "CustomMsg");
        $this->Fields['tipo'] = &$this->tipo;

        // fecha
        $this->fecha = new DbField('cont_comprobante', 'cont_comprobante', 'x_fecha', 'fecha', '`fecha`', CastDateFieldForLike("`fecha`", 7, "DB"), 133, 10, 7, false, '`fecha`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->fecha->Required = true; // Required field
        $this->fecha->Sortable = true; // Allow sort
        $this->fecha->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_SEPARATOR"], $Language->phrase("IncorrectDateDMY"));
        $this->fecha->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->fecha->Param, "CustomMsg");
        $this->Fields['fecha'] = &$this->fecha;

        // descripcion
        $this->descripcion = new DbField('cont_comprobante', 'cont_comprobante', 'x_descripcion', 'descripcion', '`descripcion`', '`descripcion`', 200, 255, -1, false, '`descripcion`', false, false, false, 'FORMATTED TEXT', 'TEXTAREA');
        $this->descripcion->Sortable = true; // Allow sort
        $this->descripcion->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->descripcion->Param, "CustomMsg");
        $this->Fields['descripcion'] = &$this->descripcion;

        // contabilizacion
        $this->contabilizacion = new DbField('cont_comprobante', 'cont_comprobante', 'x_contabilizacion', 'contabilizacion', '`contabilizacion`', CastDateFieldForLike("`contabilizacion`", 7, "DB"), 133, 10, 7, false, '`contabilizacion`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->contabilizacion->Sortable = true; // Allow sort
        $this->contabilizacion->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_SEPARATOR"], $Language->phrase("IncorrectDateDMY"));
        $this->contabilizacion->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->contabilizacion->Param, "CustomMsg");
        $this->Fields['contabilizacion'] = &$this->contabilizacion;

        // registra
        $this->registra = new DbField('cont_comprobante', 'cont_comprobante', 'x_registra', 'registra', '`registra`', '`registra`', 200, 25, -1, false, '`registra`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->registra->Sortable = true; // Allow sort
        $this->registra->Lookup = new Lookup('registra', 'usuario', false, 'username', ["nombre","","",""], [], [], [], [], [], [], '', '');
        $this->registra->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->registra->Param, "CustomMsg");
        $this->Fields['registra'] = &$this->registra;

        // fecha_registro
        $this->fecha_registro = new DbField('cont_comprobante', 'cont_comprobante', 'x_fecha_registro', 'fecha_registro', '`fecha_registro`', CastDateFieldForLike("`fecha_registro`", 7, "DB"), 133, 10, 7, false, '`fecha_registro`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->fecha_registro->Sortable = true; // Allow sort
        $this->fecha_registro->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_SEPARATOR"], $Language->phrase("IncorrectDateDMY"));
        $this->fecha_registro->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->fecha_registro->Param, "CustomMsg");
        $this->Fields['fecha_registro'] = &$this->fecha_registro;

        // contabiliza
        $this->contabiliza = new DbField('cont_comprobante', 'cont_comprobante', 'x_contabiliza', 'contabiliza', '`contabiliza`', '`contabiliza`', 200, 25, -1, false, '`contabiliza`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->contabiliza->Sortable = true; // Allow sort
        $this->contabiliza->Lookup = new Lookup('contabiliza', 'usuario', false, 'username', ["nombre","","",""], [], [], [], [], [], [], '', '');
        $this->contabiliza->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->contabiliza->Param, "CustomMsg");
        $this->Fields['contabiliza'] = &$this->contabiliza;

        // fecha_contabiliza
        $this->fecha_contabiliza = new DbField('cont_comprobante', 'cont_comprobante', 'x_fecha_contabiliza', 'fecha_contabiliza', '`fecha_contabiliza`', CastDateFieldForLike("`fecha_contabiliza`", 7, "DB"), 133, 10, 7, false, '`fecha_contabiliza`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->fecha_contabiliza->Sortable = true; // Allow sort
        $this->fecha_contabiliza->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_SEPARATOR"], $Language->phrase("IncorrectDateDMY"));
        $this->fecha_contabiliza->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->fecha_contabiliza->Param, "CustomMsg");
        $this->Fields['fecha_contabiliza'] = &$this->fecha_contabiliza;
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
        if ($this->getCurrentDetailTable() == "cont_asiento") {
            $detailUrl = Container("cont_asiento")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue);
        }
        if ($detailUrl == "") {
            $detailUrl = "ContComprobanteList";
        }
        return $detailUrl;
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`cont_comprobante`";
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
        // Cascade Update detail table 'cont_asiento'
        $cascadeUpdate = false;
        $rscascade = [];
        if ($rsold && (isset($rs['id']) && $rsold['id'] != $rs['id'])) { // Update detail field 'comprobante'
            $cascadeUpdate = true;
            $rscascade['comprobante'] = $rs['id'];
        }
        if ($cascadeUpdate) {
            $rswrk = Container("cont_asiento")->loadRs("`comprobante` = " . QuotedValue($rsold['id'], DATATYPE_NUMBER, 'DB'))->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rswrk as $rsdtlold) {
                $rskey = [];
                $fldname = 'id';
                $rskey[$fldname] = $rsdtlold[$fldname];
                $rsdtlnew = array_merge($rsdtlold, $rscascade);
                // Call Row_Updating event
                $success = Container("cont_asiento")->rowUpdating($rsdtlold, $rsdtlnew);
                if ($success) {
                    $success = Container("cont_asiento")->update($rscascade, $rskey, $rsdtlold);
                }
                if (!$success) {
                    return false;
                }
                // Call Row_Updated event
                Container("cont_asiento")->rowUpdated($rsdtlold, $rsdtlnew);
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

        // Cascade delete detail table 'cont_asiento'
        $dtlrows = Container("cont_asiento")->loadRs("`comprobante` = " . QuotedValue($rs['id'], DATATYPE_NUMBER, "DB"))->fetchAll(\PDO::FETCH_ASSOC);
        // Call Row Deleting event
        foreach ($dtlrows as $dtlrow) {
            $success = Container("cont_asiento")->rowDeleting($dtlrow);
            if (!$success) {
                break;
            }
        }
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                $success = Container("cont_asiento")->delete($dtlrow); // Delete
                if (!$success) {
                    break;
                }
            }
        }
        // Call Row Deleted event
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                Container("cont_asiento")->rowDeleted($dtlrow);
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
        $this->tipo->DbValue = $row['tipo'];
        $this->fecha->DbValue = $row['fecha'];
        $this->descripcion->DbValue = $row['descripcion'];
        $this->contabilizacion->DbValue = $row['contabilizacion'];
        $this->registra->DbValue = $row['registra'];
        $this->fecha_registro->DbValue = $row['fecha_registro'];
        $this->contabiliza->DbValue = $row['contabiliza'];
        $this->fecha_contabiliza->DbValue = $row['fecha_contabiliza'];
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
        return $_SESSION[$name] ?? GetUrl("ContComprobanteList");
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
        if ($pageName == "ContComprobanteView") {
            return $Language->phrase("View");
        } elseif ($pageName == "ContComprobanteEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "ContComprobanteAdd") {
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
                return "ContComprobanteView";
            case Config("API_ADD_ACTION"):
                return "ContComprobanteAdd";
            case Config("API_EDIT_ACTION"):
                return "ContComprobanteEdit";
            case Config("API_DELETE_ACTION"):
                return "ContComprobanteDelete";
            case Config("API_LIST_ACTION"):
                return "ContComprobanteList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "ContComprobanteList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ContComprobanteView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("ContComprobanteView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "ContComprobanteAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "ContComprobanteAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ContComprobanteEdit", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("ContComprobanteEdit", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
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
            $url = $this->keyUrl("ContComprobanteAdd", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("ContComprobanteAdd", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
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
        return $this->keyUrl("ContComprobanteDelete", $this->getUrlParm());
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
        $this->tipo->setDbValue($row['tipo']);
        $this->fecha->setDbValue($row['fecha']);
        $this->descripcion->setDbValue($row['descripcion']);
        $this->contabilizacion->setDbValue($row['contabilizacion']);
        $this->registra->setDbValue($row['registra']);
        $this->fecha_registro->setDbValue($row['fecha_registro']);
        $this->contabiliza->setDbValue($row['contabiliza']);
        $this->fecha_contabiliza->setDbValue($row['fecha_contabiliza']);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // id

        // tipo

        // fecha

        // descripcion

        // contabilizacion

        // registra

        // fecha_registro

        // contabiliza

        // fecha_contabiliza

        // id
        $this->id->ViewValue = $this->id->CurrentValue;
        $this->id->ViewCustomAttributes = "";

        // tipo
        $curVal = trim(strval($this->tipo->CurrentValue));
        if ($curVal != "") {
            $this->tipo->ViewValue = $this->tipo->lookupCacheOption($curVal);
            if ($this->tipo->ViewValue === null) { // Lookup from database
                $filterWrk = "`tipo_comprobante`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                $lookupFilter = function() {
                    return "`activo` = 'S'";
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

        // fecha
        $this->fecha->ViewValue = $this->fecha->CurrentValue;
        $this->fecha->ViewValue = FormatDateTime($this->fecha->ViewValue, 7);
        $this->fecha->ViewCustomAttributes = "";

        // descripcion
        $this->descripcion->ViewValue = $this->descripcion->CurrentValue;
        $this->descripcion->ViewCustomAttributes = "";

        // contabilizacion
        $this->contabilizacion->ViewValue = $this->contabilizacion->CurrentValue;
        $this->contabilizacion->ViewValue = FormatDateTime($this->contabilizacion->ViewValue, 7);
        $this->contabilizacion->ViewCustomAttributes = "";

        // registra
        $this->registra->ViewValue = $this->registra->CurrentValue;
        $curVal = trim(strval($this->registra->CurrentValue));
        if ($curVal != "") {
            $this->registra->ViewValue = $this->registra->lookupCacheOption($curVal);
            if ($this->registra->ViewValue === null) { // Lookup from database
                $filterWrk = "`username`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                $sqlWrk = $this->registra->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->registra->Lookup->renderViewRow($rswrk[0]);
                    $this->registra->ViewValue = $this->registra->displayValue($arwrk);
                } else {
                    $this->registra->ViewValue = $this->registra->CurrentValue;
                }
            }
        } else {
            $this->registra->ViewValue = null;
        }
        $this->registra->ViewCustomAttributes = "";

        // fecha_registro
        $this->fecha_registro->ViewValue = $this->fecha_registro->CurrentValue;
        $this->fecha_registro->ViewValue = FormatDateTime($this->fecha_registro->ViewValue, 7);
        $this->fecha_registro->ViewCustomAttributes = "";

        // contabiliza
        $this->contabiliza->ViewValue = $this->contabiliza->CurrentValue;
        $curVal = trim(strval($this->contabiliza->CurrentValue));
        if ($curVal != "") {
            $this->contabiliza->ViewValue = $this->contabiliza->lookupCacheOption($curVal);
            if ($this->contabiliza->ViewValue === null) { // Lookup from database
                $filterWrk = "`username`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                $sqlWrk = $this->contabiliza->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->contabiliza->Lookup->renderViewRow($rswrk[0]);
                    $this->contabiliza->ViewValue = $this->contabiliza->displayValue($arwrk);
                } else {
                    $this->contabiliza->ViewValue = $this->contabiliza->CurrentValue;
                }
            }
        } else {
            $this->contabiliza->ViewValue = null;
        }
        $this->contabiliza->ViewCustomAttributes = "";

        // fecha_contabiliza
        $this->fecha_contabiliza->ViewValue = $this->fecha_contabiliza->CurrentValue;
        $this->fecha_contabiliza->ViewValue = FormatDateTime($this->fecha_contabiliza->ViewValue, 7);
        $this->fecha_contabiliza->ViewCustomAttributes = "";

        // id
        $this->id->LinkCustomAttributes = "";
        $this->id->HrefValue = "";
        $this->id->TooltipValue = "";

        // tipo
        $this->tipo->LinkCustomAttributes = "";
        $this->tipo->HrefValue = "";
        $this->tipo->TooltipValue = "";

        // fecha
        $this->fecha->LinkCustomAttributes = "";
        $this->fecha->HrefValue = "";
        $this->fecha->TooltipValue = "";

        // descripcion
        $this->descripcion->LinkCustomAttributes = "";
        $this->descripcion->HrefValue = "";
        $this->descripcion->TooltipValue = "";

        // contabilizacion
        $this->contabilizacion->LinkCustomAttributes = "";
        $this->contabilizacion->HrefValue = "";
        $this->contabilizacion->TooltipValue = "";

        // registra
        $this->registra->LinkCustomAttributes = "";
        $this->registra->HrefValue = "";
        $this->registra->TooltipValue = "";

        // fecha_registro
        $this->fecha_registro->LinkCustomAttributes = "";
        $this->fecha_registro->HrefValue = "";
        $this->fecha_registro->TooltipValue = "";

        // contabiliza
        $this->contabiliza->LinkCustomAttributes = "";
        $this->contabiliza->HrefValue = "";
        $this->contabiliza->TooltipValue = "";

        // fecha_contabiliza
        $this->fecha_contabiliza->LinkCustomAttributes = "";
        $this->fecha_contabiliza->HrefValue = "";
        $this->fecha_contabiliza->TooltipValue = "";

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

        // tipo
        $this->tipo->EditAttrs["class"] = "form-control";
        $this->tipo->EditCustomAttributes = "";
        $curVal = trim(strval($this->tipo->CurrentValue));
        if ($curVal != "") {
            $this->tipo->EditValue = $this->tipo->lookupCacheOption($curVal);
            if ($this->tipo->EditValue === null) { // Lookup from database
                $filterWrk = "`tipo_comprobante`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                $lookupFilter = function() {
                    return "`activo` = 'S'";
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->tipo->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->tipo->Lookup->renderViewRow($rswrk[0]);
                    $this->tipo->EditValue = $this->tipo->displayValue($arwrk);
                } else {
                    $this->tipo->EditValue = $this->tipo->CurrentValue;
                }
            }
        } else {
            $this->tipo->EditValue = null;
        }
        $this->tipo->ViewCustomAttributes = "";

        // fecha
        $this->fecha->EditAttrs["class"] = "form-control";
        $this->fecha->EditCustomAttributes = "";
        $this->fecha->EditValue = FormatDateTime($this->fecha->CurrentValue, 7);
        $this->fecha->PlaceHolder = RemoveHtml($this->fecha->caption());

        // descripcion
        $this->descripcion->EditAttrs["class"] = "form-control";
        $this->descripcion->EditCustomAttributes = "";
        $this->descripcion->EditValue = $this->descripcion->CurrentValue;
        $this->descripcion->PlaceHolder = RemoveHtml($this->descripcion->caption());

        // contabilizacion
        $this->contabilizacion->EditAttrs["class"] = "form-control";
        $this->contabilizacion->EditCustomAttributes = "";
        $this->contabilizacion->EditValue = $this->contabilizacion->CurrentValue;
        $this->contabilizacion->EditValue = FormatDateTime($this->contabilizacion->EditValue, 7);
        $this->contabilizacion->ViewCustomAttributes = "";

        // registra
        $this->registra->EditAttrs["class"] = "form-control";
        $this->registra->EditCustomAttributes = "";
        if (!$this->registra->Raw) {
            $this->registra->CurrentValue = HtmlDecode($this->registra->CurrentValue);
        }
        $this->registra->EditValue = $this->registra->CurrentValue;
        $this->registra->PlaceHolder = RemoveHtml($this->registra->caption());

        // fecha_registro
        $this->fecha_registro->EditAttrs["class"] = "form-control";
        $this->fecha_registro->EditCustomAttributes = "";
        $this->fecha_registro->EditValue = FormatDateTime($this->fecha_registro->CurrentValue, 7);
        $this->fecha_registro->PlaceHolder = RemoveHtml($this->fecha_registro->caption());

        // contabiliza
        $this->contabiliza->EditAttrs["class"] = "form-control";
        $this->contabiliza->EditCustomAttributes = "";
        if (!$this->contabiliza->Raw) {
            $this->contabiliza->CurrentValue = HtmlDecode($this->contabiliza->CurrentValue);
        }
        $this->contabiliza->EditValue = $this->contabiliza->CurrentValue;
        $this->contabiliza->PlaceHolder = RemoveHtml($this->contabiliza->caption());

        // fecha_contabiliza
        $this->fecha_contabiliza->EditAttrs["class"] = "form-control";
        $this->fecha_contabiliza->EditCustomAttributes = "";
        $this->fecha_contabiliza->EditValue = FormatDateTime($this->fecha_contabiliza->CurrentValue, 7);
        $this->fecha_contabiliza->PlaceHolder = RemoveHtml($this->fecha_contabiliza->caption());

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
                    $doc->exportCaption($this->tipo);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->descripcion);
                    $doc->exportCaption($this->contabilizacion);
                    $doc->exportCaption($this->registra);
                    $doc->exportCaption($this->fecha_registro);
                    $doc->exportCaption($this->contabiliza);
                    $doc->exportCaption($this->fecha_contabiliza);
                } else {
                    $doc->exportCaption($this->id);
                    $doc->exportCaption($this->tipo);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->descripcion);
                    $doc->exportCaption($this->contabilizacion);
                    $doc->exportCaption($this->registra);
                    $doc->exportCaption($this->fecha_registro);
                    $doc->exportCaption($this->contabiliza);
                    $doc->exportCaption($this->fecha_contabiliza);
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
                        $doc->exportField($this->tipo);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->descripcion);
                        $doc->exportField($this->contabilizacion);
                        $doc->exportField($this->registra);
                        $doc->exportField($this->fecha_registro);
                        $doc->exportField($this->contabiliza);
                        $doc->exportField($this->fecha_contabiliza);
                    } else {
                        $doc->exportField($this->id);
                        $doc->exportField($this->tipo);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->descripcion);
                        $doc->exportField($this->contabilizacion);
                        $doc->exportField($this->registra);
                        $doc->exportField($this->fecha_registro);
                        $doc->exportField($this->contabiliza);
                        $doc->exportField($this->fecha_contabiliza);
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
        $table = 'cont_comprobante';
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
        $table = 'cont_comprobante';

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
        $table = 'cont_comprobante';

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
        $table = 'cont_comprobante';

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
    public function rowInserting($rsold, &$rsnew) {
    	// Enter your code here
    	// To cancel, set return value to FALSE
    		$fecha = $rsnew["fecha"];
    		$sql = "SELECT 
    					cerrado 
    				FROM 
    					cont_periodo_contable 
    				WHERE 
    					'$fecha' BETWEEN fecha_inicio AND fecha_fin;"; 
    		if(!$row = ExecuteRow($sql)) {
    			$this->CancelMessage = "El periodo contable no existe; verifique.";
    			return FALSE;
    		}
    		else { 
    			if($row["cerrado"] == "S") {
    				$this->CancelMessage = "El periodo contable est&aacute; cerrado; verifique.";
    				return FALSE;
    			}
    		}
    		$fc = explode("-", $fecha);
    		$mes = "M" . str_pad($fc["1"], 2, "0", STR_PAD_LEFT);
    		$sql = "SELECT 
    					id 
    				FROM 
    					cont_mes_contable 
    				WHERE 
    					tipo_comprobante = '" . $rsnew["tipo"] . "' 
    					AND $mes = 'S';";
    		if($row = ExecuteRow($sql)) {
    			$this->CancelMessage = "El mes contable est&aacute; cerrado para el tipo de comprobante; verifique.";
    			return FALSE;
    		}
    		$rsnew["registra"] = CurrentUserName();
    		$rsnew["fecha_registro"] = date("Y-m-d");
    		return TRUE;
    }

    // Row Inserted event
    public function rowInserted($rsold, &$rsnew)
    {
        //Log("Row Inserted");
    }

    // Row Updating event
    public function rowUpdating($rsold, &$rsnew) {
    	// Enter your code here
    	// To cancel, set return value to FALSE
    	if($rsold["contabilizacion"] != "") {
    		$this->CancelMessage = "Este comprobante est&aacute; contabilizado; no se puede modificar.";
    		return FALSE;
    	}
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
    public function rowDeleting(&$rs) {
    	// Enter your code here
    	// To cancel, set return value to False
    	if($rs["contabilizacion"] != "") {
    		$this->CancelMessage = "Este comprobante est&aacute; contabilizado; no se puede eliminar.";
    		return FALSE;
    	}
    	return TRUE;
    }

    // Row Deleted event
    public function rowDeleted(&$rs) {
    	//echo "Row Deleted";
    	switch($rs["tipo"]) {
    	case "COMPRA":
    		$sql = "UPDATE compra SET comprobante = NULL WHERE comprobante = " . $rs["id"];
    		Execute($sql);
    		break;
    	case "TDCFCC":
    		$sql = "UPDATE entradas SET comprobante = NULL WHERE comprobante = " . $rs["id"];
    		Execute($sql);
    		break;
    	case "EGRESO":
    		$sql = "SELECT pagos_proveedor FROM pagos_proveedor_factura WHERE comprobante = " . $rs["id"];
    		$pagos_proveedor = ExecuteScalar($sql);
    		$sql = "UPDATE pagos_proveedor_factura SET comprobante = NULL WHERE comprobante = " . $rs["id"];
    		Execute($sql);
    		$sql = "SELECT COUNT(comprobante) AS cantidad FROM pagos_proveedor_factura WHERE pagos_proveedor = " . $pagos_proveedor;
    		if(intval(ExecuteScalar($sql)) == 0) {
    			$sql = "UPDATE pagos_proveedor SET comprobante = NULL WHERE id = " . $pagos_proveedor;
    			Execute($sql);
    		}
    		break;
    	case "TDCFCV":
    		$sql = "UPDATE salidas SET comprobante = NULL WHERE comprobante = " . $rs["id"];
    		Execute($sql);
    		$sql = "UPDATE cont_lotes SET comprobante = NULL WHERE comprobante = " . $rs["id"];
    		Execute($sql);
    		break;
    	case "INGRES":
    		$sql = "SELECT cobros_cliente FROM cobros_cliente_factura WHERE comprobante = " . $rs["id"];
    		$cobros_cliente = ExecuteScalar($sql);
    		$sql = "UPDATE cobros_cliente_factura SET comprobante = NULL WHERE comprobante = " . $rs["id"];
    		Execute($sql);
    		$sql = "SELECT COUNT(comprobante) AS cantidad FROM cobros_cliente_factura WHERE cobros_cliente = " . $cobros_cliente;
    		if(intval(ExecuteScalar($sql)) == 0) {
    			$sql = "UPDATE cobros_cliente SET comprobante = NULL WHERE id = " . $cobros_cliente;
    			Execute($sql);
    		}
    		break;
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
    	if (trim($this->id->CurrentValue) != "") {
    		$sql = "SELECT SUM(debe) AS debe, SUM(haber) AS haber 
    				FROM cont_asiento 
    				WHERE comprobante = '" . $this->id->CurrentValue . "'"; 
    		$row = ExecuteRow($sql);
    		$debe = $row["debe"];
    		$haber = $row["haber"];
    		if ($debe != $haber) { 
    			$color = "background-color: red; color: #ffffff;";
    			$this->contabilizacion->CellAttrs["style"] = $color;
    			$this->RowAttrs["class"] = "danger";
    		}
    		if($this->contabilizacion->CurrentValue == "") {
    			$color = "";
    			$this->contabilizacion->CellAttrs["style"] = $color;
    		}
    		else {
    			$color = "background-color: green; color: white;";
    			$this->contabilizacion->CellAttrs["style"] = $color;
    			$this->RowAttrs["class"] = "success";
    		}
    	}
    }

    // User ID Filtering event
    public function userIdFiltering(&$filter)
    {
        // Enter your code here
    }
}
