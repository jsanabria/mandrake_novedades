<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for notificaciones
 */
class Notificaciones extends DbTable
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
    public $Nnotificaciones;
    public $tipo;
    public $notificar;
    public $asunto;
    public $notificacion;
    public $notificados;
    public $notificados_efectivos;
    public $_username;
    public $fecha;
    public $enviado;
    public $adjunto;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'notificaciones';
        $this->TableName = 'notificaciones';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`notificaciones`";
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

        // Nnotificaciones
        $this->Nnotificaciones = new DbField('notificaciones', 'notificaciones', 'x_Nnotificaciones', 'Nnotificaciones', '`Nnotificaciones`', '`Nnotificaciones`', 19, 10, -1, false, '`Nnotificaciones`', false, false, false, 'FORMATTED TEXT', 'NO');
        $this->Nnotificaciones->IsAutoIncrement = true; // Autoincrement field
        $this->Nnotificaciones->IsPrimaryKey = true; // Primary key field
        $this->Nnotificaciones->Sortable = true; // Allow sort
        $this->Nnotificaciones->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Nnotificaciones->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->Nnotificaciones->Param, "CustomMsg");
        $this->Fields['Nnotificaciones'] = &$this->Nnotificaciones;

        // tipo
        $this->tipo = new DbField('notificaciones', 'notificaciones', 'x_tipo', 'tipo', '`tipo`', '`tipo`', 200, 50, -1, false, '`tipo`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->tipo->Sortable = true; // Allow sort
        $this->tipo->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->tipo->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->tipo->Lookup = new Lookup('tipo', 'parametro', false, 'valor1', ["valor2","","",""], [], [], [], [], [], [], '`valor1`', '');
        $this->tipo->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tipo->Param, "CustomMsg");
        $this->Fields['tipo'] = &$this->tipo;

        // notificar
        $this->notificar = new DbField('notificaciones', 'notificaciones', 'x_notificar', 'notificar', '`notificar`', '`notificar`', 200, 50, -1, false, '`notificar`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->notificar->Sortable = true; // Allow sort
        $this->notificar->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->notificar->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->notificar->Lookup = new Lookup('notificar', 'parametro', false, 'valor1', ["valor2","","",""], [], [], [], [], [], [], '`valor1`', '');
        $this->notificar->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->notificar->Param, "CustomMsg");
        $this->Fields['notificar'] = &$this->notificar;

        // asunto
        $this->asunto = new DbField('notificaciones', 'notificaciones', 'x_asunto', 'asunto', '`asunto`', '`asunto`', 200, 255, -1, false, '`asunto`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->asunto->Sortable = true; // Allow sort
        $this->asunto->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->asunto->Param, "CustomMsg");
        $this->Fields['asunto'] = &$this->asunto;

        // notificacion
        $this->notificacion = new DbField('notificaciones', 'notificaciones', 'x_notificacion', 'notificacion', '`notificacion`', '`notificacion`', 201, -1, -1, false, '`notificacion`', false, false, false, 'FORMATTED TEXT', 'TEXTAREA');
        $this->notificacion->Sortable = true; // Allow sort
        $this->notificacion->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->notificacion->Param, "CustomMsg");
        $this->Fields['notificacion'] = &$this->notificacion;

        // notificados
        $this->notificados = new DbField('notificaciones', 'notificaciones', 'x_notificados', 'notificados', '`notificados`', '`notificados`', 201, -1, -1, false, '`notificados`', false, false, false, 'FORMATTED TEXT', 'TEXTAREA');
        $this->notificados->Sortable = true; // Allow sort
        $this->notificados->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->notificados->Param, "CustomMsg");
        $this->Fields['notificados'] = &$this->notificados;

        // notificados_efectivos
        $this->notificados_efectivos = new DbField('notificaciones', 'notificaciones', 'x_notificados_efectivos', 'notificados_efectivos', '`notificados_efectivos`', '`notificados_efectivos`', 201, -1, -1, false, '`notificados_efectivos`', false, false, false, 'FORMATTED TEXT', 'TEXTAREA');
        $this->notificados_efectivos->Sortable = true; // Allow sort
        $this->notificados_efectivos->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->notificados_efectivos->Param, "CustomMsg");
        $this->Fields['notificados_efectivos'] = &$this->notificados_efectivos;

        // username
        $this->_username = new DbField('notificaciones', 'notificaciones', 'x__username', 'username', '`username`', '`username`', 200, 25, -1, false, '`username`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->_username->Sortable = true; // Allow sort
        $this->_username->Lookup = new Lookup('username', 'usuario', false, 'username', ["nombre","","",""], [], [], [], [], [], [], '', '');
        $this->_username->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->_username->Param, "CustomMsg");
        $this->Fields['username'] = &$this->_username;

        // fecha
        $this->fecha = new DbField('notificaciones', 'notificaciones', 'x_fecha', 'fecha', '`fecha`', CastDateFieldForLike("`fecha`", 7, "DB"), 135, 19, 7, false, '`fecha`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->fecha->Sortable = true; // Allow sort
        $this->fecha->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_SEPARATOR"], $Language->phrase("IncorrectDateDMY"));
        $this->fecha->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->fecha->Param, "CustomMsg");
        $this->Fields['fecha'] = &$this->fecha;

        // enviado
        $this->enviado = new DbField('notificaciones', 'notificaciones', 'x_enviado', 'enviado', '`enviado`', '`enviado`', 200, 1, -1, false, '`enviado`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->enviado->Sortable = true; // Allow sort
        $this->enviado->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->enviado->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->enviado->Lookup = new Lookup('enviado', 'notificaciones', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->enviado->OptionCount = 2;
        $this->enviado->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->enviado->Param, "CustomMsg");
        $this->Fields['enviado'] = &$this->enviado;

        // adjunto
        $this->adjunto = new DbField('notificaciones', 'notificaciones', 'x_adjunto', 'adjunto', '`adjunto`', '`adjunto`', 200, 255, -1, true, '`adjunto`', false, false, false, 'IMAGE', 'FILE');
        $this->adjunto->Sortable = true; // Allow sort
        $this->adjunto->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->adjunto->Param, "CustomMsg");
        $this->Fields['adjunto'] = &$this->adjunto;
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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`notificaciones`";
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
            $this->Nnotificaciones->setDbValue($conn->lastInsertId());
            $rs['Nnotificaciones'] = $this->Nnotificaciones->DbValue;
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
            $fldname = 'Nnotificaciones';
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
            if (array_key_exists('Nnotificaciones', $rs)) {
                AddFilter($where, QuotedName('Nnotificaciones', $this->Dbid) . '=' . QuotedValue($rs['Nnotificaciones'], $this->Nnotificaciones->DataType, $this->Dbid));
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
        $this->Nnotificaciones->DbValue = $row['Nnotificaciones'];
        $this->tipo->DbValue = $row['tipo'];
        $this->notificar->DbValue = $row['notificar'];
        $this->asunto->DbValue = $row['asunto'];
        $this->notificacion->DbValue = $row['notificacion'];
        $this->notificados->DbValue = $row['notificados'];
        $this->notificados_efectivos->DbValue = $row['notificados_efectivos'];
        $this->_username->DbValue = $row['username'];
        $this->fecha->DbValue = $row['fecha'];
        $this->enviado->DbValue = $row['enviado'];
        $this->adjunto->Upload->DbValue = $row['adjunto'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
        $oldFiles = EmptyValue($row['adjunto']) ? [] : [$row['adjunto']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->adjunto->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->adjunto->oldPhysicalUploadPath() . $oldFile);
            }
        }
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`Nnotificaciones` = @Nnotificaciones@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->Nnotificaciones->CurrentValue : $this->Nnotificaciones->OldValue;
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
                $this->Nnotificaciones->CurrentValue = $keys[0];
            } else {
                $this->Nnotificaciones->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('Nnotificaciones', $row) ? $row['Nnotificaciones'] : null;
        } else {
            $val = $this->Nnotificaciones->OldValue !== null ? $this->Nnotificaciones->OldValue : $this->Nnotificaciones->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@Nnotificaciones@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
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
        return $_SESSION[$name] ?? GetUrl("NotificacionesList");
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
        if ($pageName == "NotificacionesView") {
            return $Language->phrase("View");
        } elseif ($pageName == "NotificacionesEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "NotificacionesAdd") {
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
                return "NotificacionesView";
            case Config("API_ADD_ACTION"):
                return "NotificacionesAdd";
            case Config("API_EDIT_ACTION"):
                return "NotificacionesEdit";
            case Config("API_DELETE_ACTION"):
                return "NotificacionesDelete";
            case Config("API_LIST_ACTION"):
                return "NotificacionesList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "NotificacionesList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("NotificacionesView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("NotificacionesView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "NotificacionesAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "NotificacionesAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("NotificacionesEdit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("NotificacionesAdd", $this->getUrlParm($parm));
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
        return $this->keyUrl("NotificacionesDelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "Nnotificaciones:" . JsonEncode($this->Nnotificaciones->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->Nnotificaciones->CurrentValue !== null) {
            $url .= "/" . rawurlencode($this->Nnotificaciones->CurrentValue);
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
            if (($keyValue = Param("Nnotificaciones") ?? Route("Nnotificaciones")) !== null) {
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
                $this->Nnotificaciones->CurrentValue = $key;
            } else {
                $this->Nnotificaciones->OldValue = $key;
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
        $this->Nnotificaciones->setDbValue($row['Nnotificaciones']);
        $this->tipo->setDbValue($row['tipo']);
        $this->notificar->setDbValue($row['notificar']);
        $this->asunto->setDbValue($row['asunto']);
        $this->notificacion->setDbValue($row['notificacion']);
        $this->notificados->setDbValue($row['notificados']);
        $this->notificados_efectivos->setDbValue($row['notificados_efectivos']);
        $this->_username->setDbValue($row['username']);
        $this->fecha->setDbValue($row['fecha']);
        $this->enviado->setDbValue($row['enviado']);
        $this->adjunto->Upload->DbValue = $row['adjunto'];
        $this->adjunto->setDbValue($this->adjunto->Upload->DbValue);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // Nnotificaciones

        // tipo

        // notificar

        // asunto

        // notificacion

        // notificados

        // notificados_efectivos

        // username

        // fecha

        // enviado

        // adjunto

        // Nnotificaciones
        $this->Nnotificaciones->ViewValue = $this->Nnotificaciones->CurrentValue;
        $this->Nnotificaciones->ViewCustomAttributes = "";

        // tipo
        $curVal = trim(strval($this->tipo->CurrentValue));
        if ($curVal != "") {
            $this->tipo->ViewValue = $this->tipo->lookupCacheOption($curVal);
            if ($this->tipo->ViewValue === null) { // Lookup from database
                $filterWrk = "`valor1`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                $lookupFilter = function() {
                    return "`codigo` = '015'";
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

        // notificar
        $curVal = trim(strval($this->notificar->CurrentValue));
        if ($curVal != "") {
            $this->notificar->ViewValue = $this->notificar->lookupCacheOption($curVal);
            if ($this->notificar->ViewValue === null) { // Lookup from database
                $filterWrk = "`valor1`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                $lookupFilter = function() {
                    return "`codigo` = '016'";
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->notificar->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->notificar->Lookup->renderViewRow($rswrk[0]);
                    $this->notificar->ViewValue = $this->notificar->displayValue($arwrk);
                } else {
                    $this->notificar->ViewValue = $this->notificar->CurrentValue;
                }
            }
        } else {
            $this->notificar->ViewValue = null;
        }
        $this->notificar->ViewCustomAttributes = "";

        // asunto
        $this->asunto->ViewValue = $this->asunto->CurrentValue;
        $this->asunto->ViewCustomAttributes = "";

        // notificacion
        $this->notificacion->ViewValue = $this->notificacion->CurrentValue;
        $this->notificacion->ViewCustomAttributes = "";

        // notificados
        $this->notificados->ViewValue = $this->notificados->CurrentValue;
        $this->notificados->ViewCustomAttributes = "";

        // notificados_efectivos
        $this->notificados_efectivos->ViewValue = $this->notificados_efectivos->CurrentValue;
        $this->notificados_efectivos->ViewCustomAttributes = "";

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

        // fecha
        $this->fecha->ViewValue = $this->fecha->CurrentValue;
        $this->fecha->ViewValue = FormatDateTime($this->fecha->ViewValue, 7);
        $this->fecha->ViewCustomAttributes = "";

        // enviado
        if (strval($this->enviado->CurrentValue) != "") {
            $this->enviado->ViewValue = $this->enviado->optionCaption($this->enviado->CurrentValue);
        } else {
            $this->enviado->ViewValue = null;
        }
        $this->enviado->ViewCustomAttributes = "";

        // adjunto
        if (!EmptyValue($this->adjunto->Upload->DbValue)) {
            $this->adjunto->ImageWidth = 120;
            $this->adjunto->ImageHeight = 120;
            $this->adjunto->ImageAlt = $this->adjunto->alt();
            $this->adjunto->ViewValue = $this->adjunto->Upload->DbValue;
        } else {
            $this->adjunto->ViewValue = "";
        }
        $this->adjunto->ViewCustomAttributes = "";

        // Nnotificaciones
        $this->Nnotificaciones->LinkCustomAttributes = "";
        $this->Nnotificaciones->HrefValue = "";
        $this->Nnotificaciones->TooltipValue = "";

        // tipo
        $this->tipo->LinkCustomAttributes = "";
        $this->tipo->HrefValue = "";
        $this->tipo->TooltipValue = "";

        // notificar
        $this->notificar->LinkCustomAttributes = "";
        $this->notificar->HrefValue = "";
        $this->notificar->TooltipValue = "";

        // asunto
        $this->asunto->LinkCustomAttributes = "";
        $this->asunto->HrefValue = "";
        $this->asunto->TooltipValue = "";

        // notificacion
        $this->notificacion->LinkCustomAttributes = "";
        $this->notificacion->HrefValue = "";
        $this->notificacion->TooltipValue = "";

        // notificados
        $this->notificados->LinkCustomAttributes = "";
        $this->notificados->HrefValue = "";
        $this->notificados->TooltipValue = "";

        // notificados_efectivos
        $this->notificados_efectivos->LinkCustomAttributes = "";
        $this->notificados_efectivos->HrefValue = "";
        $this->notificados_efectivos->TooltipValue = "";

        // username
        $this->_username->LinkCustomAttributes = "";
        $this->_username->HrefValue = "";
        $this->_username->TooltipValue = "";

        // fecha
        $this->fecha->LinkCustomAttributes = "";
        $this->fecha->HrefValue = "";
        $this->fecha->TooltipValue = "";

        // enviado
        $this->enviado->LinkCustomAttributes = "";
        $this->enviado->HrefValue = "";
        $this->enviado->TooltipValue = "";

        // adjunto
        $this->adjunto->LinkCustomAttributes = "";
        if (!EmptyValue($this->adjunto->Upload->DbValue)) {
            $this->adjunto->HrefValue = GetFileUploadUrl($this->adjunto, $this->adjunto->htmlDecode($this->adjunto->Upload->DbValue)); // Add prefix/suffix
            $this->adjunto->LinkAttrs["target"] = "_self"; // Add target
            if ($this->isExport()) {
                $this->adjunto->HrefValue = FullUrl($this->adjunto->HrefValue, "href");
            }
        } else {
            $this->adjunto->HrefValue = "";
        }
        $this->adjunto->ExportHrefValue = $this->adjunto->UploadPath . $this->adjunto->Upload->DbValue;
        $this->adjunto->TooltipValue = "";
        if ($this->adjunto->UseColorbox) {
            if (EmptyValue($this->adjunto->TooltipValue)) {
                $this->adjunto->LinkAttrs["title"] = $Language->phrase("ViewImageGallery");
            }
            $this->adjunto->LinkAttrs["data-rel"] = "notificaciones_x_adjunto";
            $this->adjunto->LinkAttrs->appendClass("ew-lightbox");
        }

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

        // Nnotificaciones
        $this->Nnotificaciones->EditAttrs["class"] = "form-control";
        $this->Nnotificaciones->EditCustomAttributes = "";
        $this->Nnotificaciones->EditValue = $this->Nnotificaciones->CurrentValue;
        $this->Nnotificaciones->ViewCustomAttributes = "";

        // tipo
        $this->tipo->EditAttrs["class"] = "form-control";
        $this->tipo->EditCustomAttributes = "";
        $this->tipo->PlaceHolder = RemoveHtml($this->tipo->caption());

        // notificar
        $this->notificar->EditAttrs["class"] = "form-control";
        $this->notificar->EditCustomAttributes = "";
        $curVal = trim(strval($this->notificar->CurrentValue));
        if ($curVal != "") {
            $this->notificar->EditValue = $this->notificar->lookupCacheOption($curVal);
            if ($this->notificar->EditValue === null) { // Lookup from database
                $filterWrk = "`valor1`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                $lookupFilter = function() {
                    return "`codigo` = '016'";
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->notificar->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->notificar->Lookup->renderViewRow($rswrk[0]);
                    $this->notificar->EditValue = $this->notificar->displayValue($arwrk);
                } else {
                    $this->notificar->EditValue = $this->notificar->CurrentValue;
                }
            }
        } else {
            $this->notificar->EditValue = null;
        }
        $this->notificar->ViewCustomAttributes = "";

        // asunto
        $this->asunto->EditAttrs["class"] = "form-control";
        $this->asunto->EditCustomAttributes = "";
        if (!$this->asunto->Raw) {
            $this->asunto->CurrentValue = HtmlDecode($this->asunto->CurrentValue);
        }
        $this->asunto->EditValue = $this->asunto->CurrentValue;
        $this->asunto->PlaceHolder = RemoveHtml($this->asunto->caption());

        // notificacion
        $this->notificacion->EditAttrs["class"] = "form-control";
        $this->notificacion->EditCustomAttributes = "";
        $this->notificacion->EditValue = $this->notificacion->CurrentValue;
        $this->notificacion->PlaceHolder = RemoveHtml($this->notificacion->caption());

        // notificados
        $this->notificados->EditAttrs["class"] = "form-control";
        $this->notificados->EditCustomAttributes = "";
        $this->notificados->EditValue = $this->notificados->CurrentValue;
        $this->notificados->PlaceHolder = RemoveHtml($this->notificados->caption());

        // notificados_efectivos
        $this->notificados_efectivos->EditAttrs["class"] = "form-control";
        $this->notificados_efectivos->EditCustomAttributes = "";
        $this->notificados_efectivos->EditValue = $this->notificados_efectivos->CurrentValue;
        $this->notificados_efectivos->PlaceHolder = RemoveHtml($this->notificados_efectivos->caption());

        // username
        $this->_username->EditAttrs["class"] = "form-control";
        $this->_username->EditCustomAttributes = "";
        $this->_username->EditValue = $this->_username->CurrentValue;
        $curVal = trim(strval($this->_username->CurrentValue));
        if ($curVal != "") {
            $this->_username->EditValue = $this->_username->lookupCacheOption($curVal);
            if ($this->_username->EditValue === null) { // Lookup from database
                $filterWrk = "`username`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                $sqlWrk = $this->_username->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->_username->Lookup->renderViewRow($rswrk[0]);
                    $this->_username->EditValue = $this->_username->displayValue($arwrk);
                } else {
                    $this->_username->EditValue = $this->_username->CurrentValue;
                }
            }
        } else {
            $this->_username->EditValue = null;
        }
        $this->_username->ViewCustomAttributes = "";

        // fecha
        $this->fecha->EditAttrs["class"] = "form-control";
        $this->fecha->EditCustomAttributes = "";
        $this->fecha->EditValue = $this->fecha->CurrentValue;
        $this->fecha->EditValue = FormatDateTime($this->fecha->EditValue, 7);
        $this->fecha->ViewCustomAttributes = "";

        // enviado
        $this->enviado->EditAttrs["class"] = "form-control";
        $this->enviado->EditCustomAttributes = "";
        if (strval($this->enviado->CurrentValue) != "") {
            $this->enviado->EditValue = $this->enviado->optionCaption($this->enviado->CurrentValue);
        } else {
            $this->enviado->EditValue = null;
        }
        $this->enviado->ViewCustomAttributes = "";

        // adjunto
        $this->adjunto->EditAttrs["class"] = "form-control";
        $this->adjunto->EditCustomAttributes = "";
        if (!EmptyValue($this->adjunto->Upload->DbValue)) {
            $this->adjunto->ImageWidth = 120;
            $this->adjunto->ImageHeight = 120;
            $this->adjunto->ImageAlt = $this->adjunto->alt();
            $this->adjunto->EditValue = $this->adjunto->Upload->DbValue;
        } else {
            $this->adjunto->EditValue = "";
        }
        $this->adjunto->ViewCustomAttributes = "";

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
                    $doc->exportCaption($this->Nnotificaciones);
                    $doc->exportCaption($this->notificar);
                    $doc->exportCaption($this->asunto);
                    $doc->exportCaption($this->notificacion);
                    $doc->exportCaption($this->notificados);
                    $doc->exportCaption($this->notificados_efectivos);
                    $doc->exportCaption($this->_username);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->enviado);
                    $doc->exportCaption($this->adjunto);
                } else {
                    $doc->exportCaption($this->Nnotificaciones);
                    $doc->exportCaption($this->tipo);
                    $doc->exportCaption($this->notificar);
                    $doc->exportCaption($this->asunto);
                    $doc->exportCaption($this->_username);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->enviado);
                    $doc->exportCaption($this->adjunto);
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
                        $doc->exportField($this->Nnotificaciones);
                        $doc->exportField($this->notificar);
                        $doc->exportField($this->asunto);
                        $doc->exportField($this->notificacion);
                        $doc->exportField($this->notificados);
                        $doc->exportField($this->notificados_efectivos);
                        $doc->exportField($this->_username);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->enviado);
                        $doc->exportField($this->adjunto);
                    } else {
                        $doc->exportField($this->Nnotificaciones);
                        $doc->exportField($this->tipo);
                        $doc->exportField($this->notificar);
                        $doc->exportField($this->asunto);
                        $doc->exportField($this->_username);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->enviado);
                        $doc->exportField($this->adjunto);
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
        if ($fldparm == 'adjunto') {
            $fldName = "adjunto";
            $fileNameFld = "adjunto";
        } else {
            return false; // Incorrect field
        }

        // Set up key values
        $ar = explode(Config("COMPOSITE_KEY_SEPARATOR"), $key);
        if (count($ar) == 1) {
            $this->Nnotificaciones->CurrentValue = $ar[0];
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
        $table = 'notificaciones';
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
        $table = 'notificaciones';

        // Get key value
        $key = "";
        if ($key != "") {
            $key .= Config("COMPOSITE_KEY_SEPARATOR");
        }
        $key .= $rs['Nnotificaciones'];

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
        $table = 'notificaciones';

        // Get key value
        $key = "";
        if ($key != "") {
            $key .= Config("COMPOSITE_KEY_SEPARATOR");
        }
        $key .= $rsold['Nnotificaciones'];

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
        $table = 'notificaciones';

        // Get key value
        $key = "";
        if ($key != "") {
            $key .= Config("COMPOSITE_KEY_SEPARATOR");
        }
        $key .= $rs['Nnotificaciones'];

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
