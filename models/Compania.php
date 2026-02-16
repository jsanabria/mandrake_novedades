<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for compania
 */
class Compania extends DbTable
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
    public $ci_rif;
    public $nombre;
    public $ciudad;
    public $direccion;
    public $telefono1;
    public $telefono2;
    public $email1;
    public $email2;
    public $agente_retencion;
    public $logo;
    public $activo;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'compania';
        $this->TableName = 'compania';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`compania`";
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
        $this->id = new DbField('compania', 'compania', 'x_id', 'id', '`id`', '`id`', 19, 10, -1, false, '`id`', false, false, false, 'FORMATTED TEXT', 'NO');
        $this->id->IsAutoIncrement = true; // Autoincrement field
        $this->id->IsPrimaryKey = true; // Primary key field
        $this->id->IsForeignKey = true; // Foreign key field
        $this->id->Sortable = true; // Allow sort
        $this->id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->id->Param, "CustomMsg");
        $this->Fields['id'] = &$this->id;

        // ci_rif
        $this->ci_rif = new DbField('compania', 'compania', 'x_ci_rif', 'ci_rif', '`ci_rif`', '`ci_rif`', 200, 30, -1, false, '`ci_rif`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->ci_rif->Sortable = true; // Allow sort
        $this->ci_rif->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->ci_rif->Param, "CustomMsg");
        $this->Fields['ci_rif'] = &$this->ci_rif;

        // nombre
        $this->nombre = new DbField('compania', 'compania', 'x_nombre', 'nombre', '`nombre`', '`nombre`', 200, 80, -1, false, '`nombre`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->nombre->Sortable = true; // Allow sort
        $this->nombre->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->nombre->Param, "CustomMsg");
        $this->Fields['nombre'] = &$this->nombre;

        // ciudad
        $this->ciudad = new DbField('compania', 'compania', 'x_ciudad', 'ciudad', '`ciudad`', '`ciudad`', 200, 6, -1, false, '`ciudad`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->ciudad->Sortable = true; // Allow sort
        $this->ciudad->Lookup = new Lookup('ciudad', 'tabla', false, 'campo_codigo', ["campo_descripcion","","",""], [], [], [], [], [], [], '`campo_descripcion`', '');
        $this->ciudad->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->ciudad->Param, "CustomMsg");
        $this->Fields['ciudad'] = &$this->ciudad;

        // direccion
        $this->direccion = new DbField('compania', 'compania', 'x_direccion', 'direccion', '`direccion`', '`direccion`', 200, 150, -1, false, '`direccion`', false, false, false, 'FORMATTED TEXT', 'TEXTAREA');
        $this->direccion->Sortable = true; // Allow sort
        $this->direccion->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->direccion->Param, "CustomMsg");
        $this->Fields['direccion'] = &$this->direccion;

        // telefono1
        $this->telefono1 = new DbField('compania', 'compania', 'x_telefono1', 'telefono1', '`telefono1`', '`telefono1`', 200, 20, -1, false, '`telefono1`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->telefono1->Sortable = true; // Allow sort
        $this->telefono1->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->telefono1->Param, "CustomMsg");
        $this->Fields['telefono1'] = &$this->telefono1;

        // telefono2
        $this->telefono2 = new DbField('compania', 'compania', 'x_telefono2', 'telefono2', '`telefono2`', '`telefono2`', 200, 20, -1, false, '`telefono2`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->telefono2->Sortable = true; // Allow sort
        $this->telefono2->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->telefono2->Param, "CustomMsg");
        $this->Fields['telefono2'] = &$this->telefono2;

        // email1
        $this->email1 = new DbField('compania', 'compania', 'x_email1', 'email1', '`email1`', '`email1`', 200, 100, -1, false, '`email1`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->email1->Sortable = true; // Allow sort
        $this->email1->DefaultErrorMessage = $Language->phrase("IncorrectEmail");
        $this->email1->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->email1->Param, "CustomMsg");
        $this->Fields['email1'] = &$this->email1;

        // email2
        $this->email2 = new DbField('compania', 'compania', 'x_email2', 'email2', '`email2`', '`email2`', 200, 100, -1, false, '`email2`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->email2->Sortable = true; // Allow sort
        $this->email2->DefaultErrorMessage = $Language->phrase("IncorrectEmail");
        $this->email2->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->email2->Param, "CustomMsg");
        $this->Fields['email2'] = &$this->email2;

        // agente_retencion
        $this->agente_retencion = new DbField('compania', 'compania', 'x_agente_retencion', 'agente_retencion', '`agente_retencion`', '`agente_retencion`', 202, 1, -1, false, '`agente_retencion`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->agente_retencion->Required = true; // Required field
        $this->agente_retencion->Sortable = true; // Allow sort
        $this->agente_retencion->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->agente_retencion->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->agente_retencion->Lookup = new Lookup('agente_retencion', 'compania', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->agente_retencion->OptionCount = 2;
        $this->agente_retencion->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->agente_retencion->Param, "CustomMsg");
        $this->Fields['agente_retencion'] = &$this->agente_retencion;

        // logo
        $this->logo = new DbField('compania', 'compania', 'x_logo', 'logo', '`logo`', '`logo`', 200, 250, -1, true, '`logo`', false, false, false, 'IMAGE', 'FILE');
        $this->logo->Sortable = true; // Allow sort
        $this->logo->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->logo->Param, "CustomMsg");
        $this->Fields['logo'] = &$this->logo;

        // activo
        $this->activo = new DbField('compania', 'compania', 'x_activo', 'activo', '`activo`', '`activo`', 202, 1, -1, false, '`activo`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->activo->Required = true; // Required field
        $this->activo->Sortable = true; // Allow sort
        $this->activo->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->activo->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->activo->Lookup = new Lookup('activo', 'compania', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->activo->OptionCount = 2;
        $this->activo->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->activo->Param, "CustomMsg");
        $this->Fields['activo'] = &$this->activo;
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
        if ($this->getCurrentDetailTable() == "compania_cuenta") {
            $detailUrl = Container("compania_cuenta")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue);
        }
        if ($detailUrl == "") {
            $detailUrl = "CompaniaList";
        }
        return $detailUrl;
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`compania`";
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
        $this->ci_rif->DbValue = $row['ci_rif'];
        $this->nombre->DbValue = $row['nombre'];
        $this->ciudad->DbValue = $row['ciudad'];
        $this->direccion->DbValue = $row['direccion'];
        $this->telefono1->DbValue = $row['telefono1'];
        $this->telefono2->DbValue = $row['telefono2'];
        $this->email1->DbValue = $row['email1'];
        $this->email2->DbValue = $row['email2'];
        $this->agente_retencion->DbValue = $row['agente_retencion'];
        $this->logo->Upload->DbValue = $row['logo'];
        $this->activo->DbValue = $row['activo'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
        $oldFiles = EmptyValue($row['logo']) ? [] : [$row['logo']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->logo->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->logo->oldPhysicalUploadPath() . $oldFile);
            }
        }
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
        return $_SESSION[$name] ?? GetUrl("CompaniaList");
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
        if ($pageName == "CompaniaView") {
            return $Language->phrase("View");
        } elseif ($pageName == "CompaniaEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "CompaniaAdd") {
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
                return "CompaniaView";
            case Config("API_ADD_ACTION"):
                return "CompaniaAdd";
            case Config("API_EDIT_ACTION"):
                return "CompaniaEdit";
            case Config("API_DELETE_ACTION"):
                return "CompaniaDelete";
            case Config("API_LIST_ACTION"):
                return "CompaniaList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "CompaniaList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("CompaniaView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("CompaniaView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "CompaniaAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "CompaniaAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("CompaniaEdit", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("CompaniaEdit", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
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
            $url = $this->keyUrl("CompaniaAdd", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("CompaniaAdd", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
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
        return $this->keyUrl("CompaniaDelete", $this->getUrlParm());
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
        $this->ci_rif->setDbValue($row['ci_rif']);
        $this->nombre->setDbValue($row['nombre']);
        $this->ciudad->setDbValue($row['ciudad']);
        $this->direccion->setDbValue($row['direccion']);
        $this->telefono1->setDbValue($row['telefono1']);
        $this->telefono2->setDbValue($row['telefono2']);
        $this->email1->setDbValue($row['email1']);
        $this->email2->setDbValue($row['email2']);
        $this->agente_retencion->setDbValue($row['agente_retencion']);
        $this->logo->Upload->DbValue = $row['logo'];
        $this->logo->setDbValue($this->logo->Upload->DbValue);
        $this->activo->setDbValue($row['activo']);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // id

        // ci_rif

        // nombre

        // ciudad

        // direccion

        // telefono1

        // telefono2

        // email1

        // email2

        // agente_retencion

        // logo

        // activo

        // id
        $this->id->ViewValue = $this->id->CurrentValue;
        $this->id->ViewCustomAttributes = "";

        // ci_rif
        $this->ci_rif->ViewValue = $this->ci_rif->CurrentValue;
        $this->ci_rif->ViewCustomAttributes = "";

        // nombre
        $this->nombre->ViewValue = $this->nombre->CurrentValue;
        $this->nombre->ViewCustomAttributes = "";

        // ciudad
        $this->ciudad->ViewValue = $this->ciudad->CurrentValue;
        $curVal = trim(strval($this->ciudad->CurrentValue));
        if ($curVal != "") {
            $this->ciudad->ViewValue = $this->ciudad->lookupCacheOption($curVal);
            if ($this->ciudad->ViewValue === null) { // Lookup from database
                $filterWrk = "`campo_codigo`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                $lookupFilter = function() {
                    return "`tabla` = 'CIUDAD'";
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->ciudad->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->ciudad->Lookup->renderViewRow($rswrk[0]);
                    $this->ciudad->ViewValue = $this->ciudad->displayValue($arwrk);
                } else {
                    $this->ciudad->ViewValue = $this->ciudad->CurrentValue;
                }
            }
        } else {
            $this->ciudad->ViewValue = null;
        }
        $this->ciudad->ViewCustomAttributes = "";

        // direccion
        $this->direccion->ViewValue = $this->direccion->CurrentValue;
        $this->direccion->ViewCustomAttributes = "";

        // telefono1
        $this->telefono1->ViewValue = $this->telefono1->CurrentValue;
        $this->telefono1->ViewCustomAttributes = "";

        // telefono2
        $this->telefono2->ViewValue = $this->telefono2->CurrentValue;
        $this->telefono2->ViewCustomAttributes = "";

        // email1
        $this->email1->ViewValue = $this->email1->CurrentValue;
        $this->email1->ViewCustomAttributes = "";

        // email2
        $this->email2->ViewValue = $this->email2->CurrentValue;
        $this->email2->ViewCustomAttributes = "";

        // agente_retencion
        if (strval($this->agente_retencion->CurrentValue) != "") {
            $this->agente_retencion->ViewValue = $this->agente_retencion->optionCaption($this->agente_retencion->CurrentValue);
        } else {
            $this->agente_retencion->ViewValue = null;
        }
        $this->agente_retencion->ViewCustomAttributes = "";

        // logo
        if (!EmptyValue($this->logo->Upload->DbValue)) {
            $this->logo->ImageWidth = 120;
            $this->logo->ImageHeight = 120;
            $this->logo->ImageAlt = $this->logo->alt();
            $this->logo->ViewValue = $this->logo->Upload->DbValue;
        } else {
            $this->logo->ViewValue = "";
        }
        $this->logo->ViewCustomAttributes = "";

        // activo
        if (strval($this->activo->CurrentValue) != "") {
            $this->activo->ViewValue = $this->activo->optionCaption($this->activo->CurrentValue);
        } else {
            $this->activo->ViewValue = null;
        }
        $this->activo->ViewCustomAttributes = "";

        // id
        $this->id->LinkCustomAttributes = "";
        $this->id->HrefValue = "";
        $this->id->TooltipValue = "";

        // ci_rif
        $this->ci_rif->LinkCustomAttributes = "";
        $this->ci_rif->HrefValue = "";
        $this->ci_rif->TooltipValue = "";

        // nombre
        $this->nombre->LinkCustomAttributes = "";
        $this->nombre->HrefValue = "";
        $this->nombre->TooltipValue = "";

        // ciudad
        $this->ciudad->LinkCustomAttributes = "";
        $this->ciudad->HrefValue = "";
        $this->ciudad->TooltipValue = "";

        // direccion
        $this->direccion->LinkCustomAttributes = "";
        $this->direccion->HrefValue = "";
        $this->direccion->TooltipValue = "";

        // telefono1
        $this->telefono1->LinkCustomAttributes = "";
        $this->telefono1->HrefValue = "";
        $this->telefono1->TooltipValue = "";

        // telefono2
        $this->telefono2->LinkCustomAttributes = "";
        $this->telefono2->HrefValue = "";
        $this->telefono2->TooltipValue = "";

        // email1
        $this->email1->LinkCustomAttributes = "";
        $this->email1->HrefValue = "";
        $this->email1->TooltipValue = "";

        // email2
        $this->email2->LinkCustomAttributes = "";
        $this->email2->HrefValue = "";
        $this->email2->TooltipValue = "";

        // agente_retencion
        $this->agente_retencion->LinkCustomAttributes = "";
        $this->agente_retencion->HrefValue = "";
        $this->agente_retencion->TooltipValue = "";

        // logo
        $this->logo->LinkCustomAttributes = "";
        if (!EmptyValue($this->logo->Upload->DbValue)) {
            $this->logo->HrefValue = GetFileUploadUrl($this->logo, $this->logo->htmlDecode($this->logo->Upload->DbValue)); // Add prefix/suffix
            $this->logo->LinkAttrs["target"] = "_blank"; // Add target
            if ($this->isExport()) {
                $this->logo->HrefValue = FullUrl($this->logo->HrefValue, "href");
            }
        } else {
            $this->logo->HrefValue = "";
        }
        $this->logo->ExportHrefValue = $this->logo->UploadPath . $this->logo->Upload->DbValue;
        $this->logo->TooltipValue = "";
        if ($this->logo->UseColorbox) {
            if (EmptyValue($this->logo->TooltipValue)) {
                $this->logo->LinkAttrs["title"] = $Language->phrase("ViewImageGallery");
            }
            $this->logo->LinkAttrs["data-rel"] = "compania_x_logo";
            $this->logo->LinkAttrs->appendClass("ew-lightbox");
        }

        // activo
        $this->activo->LinkCustomAttributes = "";
        $this->activo->HrefValue = "";
        $this->activo->TooltipValue = "";

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

        // ci_rif
        $this->ci_rif->EditAttrs["class"] = "form-control";
        $this->ci_rif->EditCustomAttributes = "";
        if (!$this->ci_rif->Raw) {
            $this->ci_rif->CurrentValue = HtmlDecode($this->ci_rif->CurrentValue);
        }
        $this->ci_rif->EditValue = $this->ci_rif->CurrentValue;
        $this->ci_rif->PlaceHolder = RemoveHtml($this->ci_rif->caption());

        // nombre
        $this->nombre->EditAttrs["class"] = "form-control";
        $this->nombre->EditCustomAttributes = "";
        if (!$this->nombre->Raw) {
            $this->nombre->CurrentValue = HtmlDecode($this->nombre->CurrentValue);
        }
        $this->nombre->EditValue = $this->nombre->CurrentValue;
        $this->nombre->PlaceHolder = RemoveHtml($this->nombre->caption());

        // ciudad
        $this->ciudad->EditAttrs["class"] = "form-control";
        $this->ciudad->EditCustomAttributes = "";
        if (!$this->ciudad->Raw) {
            $this->ciudad->CurrentValue = HtmlDecode($this->ciudad->CurrentValue);
        }
        $this->ciudad->EditValue = $this->ciudad->CurrentValue;
        $this->ciudad->PlaceHolder = RemoveHtml($this->ciudad->caption());

        // direccion
        $this->direccion->EditAttrs["class"] = "form-control";
        $this->direccion->EditCustomAttributes = "";
        $this->direccion->EditValue = $this->direccion->CurrentValue;
        $this->direccion->PlaceHolder = RemoveHtml($this->direccion->caption());

        // telefono1
        $this->telefono1->EditAttrs["class"] = "form-control";
        $this->telefono1->EditCustomAttributes = "";
        if (!$this->telefono1->Raw) {
            $this->telefono1->CurrentValue = HtmlDecode($this->telefono1->CurrentValue);
        }
        $this->telefono1->EditValue = $this->telefono1->CurrentValue;
        $this->telefono1->PlaceHolder = RemoveHtml($this->telefono1->caption());

        // telefono2
        $this->telefono2->EditAttrs["class"] = "form-control";
        $this->telefono2->EditCustomAttributes = "";
        if (!$this->telefono2->Raw) {
            $this->telefono2->CurrentValue = HtmlDecode($this->telefono2->CurrentValue);
        }
        $this->telefono2->EditValue = $this->telefono2->CurrentValue;
        $this->telefono2->PlaceHolder = RemoveHtml($this->telefono2->caption());

        // email1
        $this->email1->EditAttrs["class"] = "form-control";
        $this->email1->EditCustomAttributes = "";
        if (!$this->email1->Raw) {
            $this->email1->CurrentValue = HtmlDecode($this->email1->CurrentValue);
        }
        $this->email1->EditValue = $this->email1->CurrentValue;
        $this->email1->PlaceHolder = RemoveHtml($this->email1->caption());

        // email2
        $this->email2->EditAttrs["class"] = "form-control";
        $this->email2->EditCustomAttributes = "";
        if (!$this->email2->Raw) {
            $this->email2->CurrentValue = HtmlDecode($this->email2->CurrentValue);
        }
        $this->email2->EditValue = $this->email2->CurrentValue;
        $this->email2->PlaceHolder = RemoveHtml($this->email2->caption());

        // agente_retencion
        $this->agente_retencion->EditAttrs["class"] = "form-control";
        $this->agente_retencion->EditCustomAttributes = "";
        $this->agente_retencion->EditValue = $this->agente_retencion->options(true);
        $this->agente_retencion->PlaceHolder = RemoveHtml($this->agente_retencion->caption());

        // logo
        $this->logo->EditAttrs["class"] = "form-control";
        $this->logo->EditCustomAttributes = "";
        if (!EmptyValue($this->logo->Upload->DbValue)) {
            $this->logo->ImageWidth = 120;
            $this->logo->ImageHeight = 120;
            $this->logo->ImageAlt = $this->logo->alt();
            $this->logo->EditValue = $this->logo->Upload->DbValue;
        } else {
            $this->logo->EditValue = "";
        }
        if (!EmptyValue($this->logo->CurrentValue)) {
            $this->logo->Upload->FileName = $this->logo->CurrentValue;
        }

        // activo
        $this->activo->EditAttrs["class"] = "form-control";
        $this->activo->EditCustomAttributes = "";
        $this->activo->EditValue = $this->activo->options(true);
        $this->activo->PlaceHolder = RemoveHtml($this->activo->caption());

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
                    $doc->exportCaption($this->ci_rif);
                    $doc->exportCaption($this->nombre);
                    $doc->exportCaption($this->ciudad);
                    $doc->exportCaption($this->direccion);
                    $doc->exportCaption($this->telefono1);
                    $doc->exportCaption($this->telefono2);
                    $doc->exportCaption($this->email1);
                    $doc->exportCaption($this->email2);
                    $doc->exportCaption($this->agente_retencion);
                    $doc->exportCaption($this->logo);
                } else {
                    $doc->exportCaption($this->id);
                    $doc->exportCaption($this->ci_rif);
                    $doc->exportCaption($this->nombre);
                    $doc->exportCaption($this->ciudad);
                    $doc->exportCaption($this->direccion);
                    $doc->exportCaption($this->telefono1);
                    $doc->exportCaption($this->telefono2);
                    $doc->exportCaption($this->email1);
                    $doc->exportCaption($this->email2);
                    $doc->exportCaption($this->agente_retencion);
                    $doc->exportCaption($this->logo);
                    $doc->exportCaption($this->activo);
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
                        $doc->exportField($this->ci_rif);
                        $doc->exportField($this->nombre);
                        $doc->exportField($this->ciudad);
                        $doc->exportField($this->direccion);
                        $doc->exportField($this->telefono1);
                        $doc->exportField($this->telefono2);
                        $doc->exportField($this->email1);
                        $doc->exportField($this->email2);
                        $doc->exportField($this->agente_retencion);
                        $doc->exportField($this->logo);
                    } else {
                        $doc->exportField($this->id);
                        $doc->exportField($this->ci_rif);
                        $doc->exportField($this->nombre);
                        $doc->exportField($this->ciudad);
                        $doc->exportField($this->direccion);
                        $doc->exportField($this->telefono1);
                        $doc->exportField($this->telefono2);
                        $doc->exportField($this->email1);
                        $doc->exportField($this->email2);
                        $doc->exportField($this->agente_retencion);
                        $doc->exportField($this->logo);
                        $doc->exportField($this->activo);
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
        $width = ($width > 0) ? $width : Config("THUMBNAIL_DEFAULT_WIDTH");
        $height = ($height > 0) ? $height : Config("THUMBNAIL_DEFAULT_HEIGHT");

        // Set up field name / file name field / file type field
        $fldName = "";
        $fileNameFld = "";
        $fileTypeFld = "";
        if ($fldparm == 'logo') {
            $fldName = "logo";
            $fileNameFld = "logo";
        } else {
            return false; // Incorrect field
        }

        // Set up key values
        $ar = explode(Config("COMPOSITE_KEY_SEPARATOR"), $key);
        if (count($ar) == 1) {
            $this->id->CurrentValue = $ar[0];
        } else {
            return false; // Incorrect key
        }

        // Set up filter (WHERE Clause)
        $filter = $this->getRecordFilter();
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $dbtype = GetConnectionType($this->Dbid);
        if ($row = $conn->fetchAssoc($sql)) {
            $val = $row[$fldName];
            if (!EmptyValue($val)) {
                $fld = $this->Fields[$fldName];

                // Binary data
                if ($fld->DataType == DATATYPE_BLOB) {
                    if ($dbtype != "MYSQL") {
                        if (is_resource($val) && get_resource_type($val) == "stream") { // Byte array
                            $val = stream_get_contents($val);
                        }
                    }
                    if ($resize) {
                        ResizeBinary($val, $width, $height, 100, $plugins);
                    }

                    // Write file type
                    if ($fileTypeFld != "" && !EmptyValue($row[$fileTypeFld])) {
                        AddHeader("Content-type", $row[$fileTypeFld]);
                    } else {
                        AddHeader("Content-type", ContentType($val));
                    }

                    // Write file name
                    $downloadPdf = !Config("EMBED_PDF") && Config("DOWNLOAD_PDF_FILE");
                    if ($fileNameFld != "" && !EmptyValue($row[$fileNameFld])) {
                        $fileName = $row[$fileNameFld];
                        $pathinfo = pathinfo($fileName);
                        $ext = strtolower(@$pathinfo["extension"]);
                        $isPdf = SameText($ext, "pdf");
                        if ($downloadPdf || !$isPdf) { // Skip header if not download PDF
                            AddHeader("Content-Disposition", "attachment; filename=\"" . $fileName . "\"");
                        }
                    } else {
                        $ext = ContentExtension($val);
                        $isPdf = SameText($ext, ".pdf");
                        if ($isPdf && $downloadPdf) { // Add header if download PDF
                            AddHeader("Content-Disposition", "attachment; filename=\"" . $fileName . "\"");
                        }
                    }

                    // Write file data
                    if (
                        StartsString("PK", $val) &&
                        ContainsString($val, "[Content_Types].xml") &&
                        ContainsString($val, "_rels") &&
                        ContainsString($val, "docProps")
                    ) { // Fix Office 2007 documents
                        if (!EndsString("\0\0\0", $val)) { // Not ends with 3 or 4 \0
                            $val .= "\0\0\0\0";
                        }
                    }

                    // Clear any debug message
                    if (ob_get_length()) {
                        ob_end_clean();
                    }

                    // Write binary data
                    Write($val);

                // Upload to folder
                } else {
                    if ($fld->UploadMultiple) {
                        $files = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $val);
                    } else {
                        $files = [$val];
                    }
                    $data = [];
                    $ar = [];
                    foreach ($files as $file) {
                        if (!EmptyValue($file)) {
                            if (Config("ENCRYPT_FILE_PATH")) {
                                $ar[$file] = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $this->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $file)));
                            } else {
                                $ar[$file] = FullUrl($fld->hrefPath() . $file);
                            }
                        }
                    }
                    $data[$fld->Param] = $ar;
                    WriteJson($data);
                }
            }
            return true;
        }
        return false;
    }

    // Write Audit Trail start/end for grid update
    public function writeAuditTrailDummy($typ)
    {
        $table = 'compania';
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
        $table = 'compania';

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
        $table = 'compania';

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
        $table = 'compania';

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
    	$rsnew["ci_rif"] = strtoupper($rsnew["ci_rif"]);
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
    	$rsnew["ci_rif"] = strtoupper($rsnew["ci_rif"]);
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
