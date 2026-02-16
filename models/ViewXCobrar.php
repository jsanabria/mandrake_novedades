<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for view_x_cobrar
 */
class ViewXCobrar extends DbTable
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
    public $cliente;
    public $nro_documento;
    public $monto_pagar;
    public $monto_pagado;
    public $retivamonto;
    public $retiva;
    public $retislrmonto;
    public $retislr;
    public $comprobante;
    public $tipodoc;
    public $fecha;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'view_x_cobrar';
        $this->TableName = 'view_x_cobrar';
        $this->TableType = 'VIEW';

        // Update Table
        $this->UpdateTable = "`view_x_cobrar`";
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
        $this->id = new DbField('view_x_cobrar', 'view_x_cobrar', 'x_id', 'id', '`id`', '`id`', 19, 10, -1, false, '`id`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->id->Sortable = true; // Allow sort
        $this->id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->id->Param, "CustomMsg");
        $this->Fields['id'] = &$this->id;

        // tipo_documento
        $this->tipo_documento = new DbField('view_x_cobrar', 'view_x_cobrar', 'x_tipo_documento', 'tipo_documento', '`tipo_documento`', '`tipo_documento`', 200, 6, -1, false, '`tipo_documento`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->tipo_documento->Sortable = true; // Allow sort
        $this->tipo_documento->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tipo_documento->Param, "CustomMsg");
        $this->Fields['tipo_documento'] = &$this->tipo_documento;

        // cliente
        $this->cliente = new DbField('view_x_cobrar', 'view_x_cobrar', 'x_cliente', 'cliente', '`cliente`', '`cliente`', 3, 11, -1, false, '`cliente`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->cliente->Sortable = true; // Allow sort
        $this->cliente->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->cliente->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->cliente->Param, "CustomMsg");
        $this->Fields['cliente'] = &$this->cliente;

        // nro_documento
        $this->nro_documento = new DbField('view_x_cobrar', 'view_x_cobrar', 'x_nro_documento', 'nro_documento', '`nro_documento`', '`nro_documento`', 200, 20, -1, false, '`nro_documento`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->nro_documento->Sortable = true; // Allow sort
        $this->nro_documento->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->nro_documento->Param, "CustomMsg");
        $this->Fields['nro_documento'] = &$this->nro_documento;

        // monto_pagar
        $this->monto_pagar = new DbField('view_x_cobrar', 'view_x_cobrar', 'x_monto_pagar', 'monto_pagar', '`monto_pagar`', '`monto_pagar`', 131, 14, -1, false, '`monto_pagar`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->monto_pagar->Sortable = true; // Allow sort
        $this->monto_pagar->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_pagar->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->monto_pagar->Param, "CustomMsg");
        $this->Fields['monto_pagar'] = &$this->monto_pagar;

        // monto_pagado
        $this->monto_pagado = new DbField('view_x_cobrar', 'view_x_cobrar', 'x_monto_pagado', 'monto_pagado', '`monto_pagado`', '`monto_pagado`', 131, 36, -1, false, '`monto_pagado`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->monto_pagado->Sortable = true; // Allow sort
        $this->monto_pagado->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_pagado->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->monto_pagado->Param, "CustomMsg");
        $this->Fields['monto_pagado'] = &$this->monto_pagado;

        // retivamonto
        $this->retivamonto = new DbField('view_x_cobrar', 'view_x_cobrar', 'x_retivamonto', 'retivamonto', '`retivamonto`', '`retivamonto`', 131, 36, -1, false, '`retivamonto`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->retivamonto->Sortable = true; // Allow sort
        $this->retivamonto->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->retivamonto->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->retivamonto->Param, "CustomMsg");
        $this->Fields['retivamonto'] = &$this->retivamonto;

        // retiva
        $this->retiva = new DbField('view_x_cobrar', 'view_x_cobrar', 'x_retiva', 'retiva', '`retiva`', '`retiva`', 201, 65535, -1, false, '`retiva`', false, false, false, 'FORMATTED TEXT', 'TEXTAREA');
        $this->retiva->Sortable = true; // Allow sort
        $this->retiva->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->retiva->Param, "CustomMsg");
        $this->Fields['retiva'] = &$this->retiva;

        // retislrmonto
        $this->retislrmonto = new DbField('view_x_cobrar', 'view_x_cobrar', 'x_retislrmonto', 'retislrmonto', '`retislrmonto`', '`retislrmonto`', 131, 36, -1, false, '`retislrmonto`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->retislrmonto->Sortable = true; // Allow sort
        $this->retislrmonto->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->retislrmonto->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->retislrmonto->Param, "CustomMsg");
        $this->Fields['retislrmonto'] = &$this->retislrmonto;

        // retislr
        $this->retislr = new DbField('view_x_cobrar', 'view_x_cobrar', 'x_retislr', 'retislr', '`retislr`', '`retislr`', 201, 65535, -1, false, '`retislr`', false, false, false, 'FORMATTED TEXT', 'TEXTAREA');
        $this->retislr->Sortable = true; // Allow sort
        $this->retislr->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->retislr->Param, "CustomMsg");
        $this->Fields['retislr'] = &$this->retislr;

        // comprobante
        $this->comprobante = new DbField('view_x_cobrar', 'view_x_cobrar', 'x_comprobante', 'comprobante', '`comprobante`', '`comprobante`', 19, 10, -1, false, '`comprobante`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->comprobante->Sortable = true; // Allow sort
        $this->comprobante->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->comprobante->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->comprobante->Param, "CustomMsg");
        $this->Fields['comprobante'] = &$this->comprobante;

        // tipodoc
        $this->tipodoc = new DbField('view_x_cobrar', 'view_x_cobrar', 'x_tipodoc', 'tipodoc', '`tipodoc`', '`tipodoc`', 200, 2, -1, false, '`tipodoc`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->tipodoc->Sortable = true; // Allow sort
        $this->tipodoc->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tipodoc->Param, "CustomMsg");
        $this->Fields['tipodoc'] = &$this->tipodoc;

        // fecha
        $this->fecha = new DbField('view_x_cobrar', 'view_x_cobrar', 'x_fecha', 'fecha', '`fecha`', CastDateFieldForLike("`fecha`", 0, "DB"), 135, 19, 0, false, '`fecha`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->fecha->Sortable = true; // Allow sort
        $this->fecha->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->fecha->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->fecha->Param, "CustomMsg");
        $this->Fields['fecha'] = &$this->fecha;
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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`view_x_cobrar`";
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
        $this->cliente->DbValue = $row['cliente'];
        $this->nro_documento->DbValue = $row['nro_documento'];
        $this->monto_pagar->DbValue = $row['monto_pagar'];
        $this->monto_pagado->DbValue = $row['monto_pagado'];
        $this->retivamonto->DbValue = $row['retivamonto'];
        $this->retiva->DbValue = $row['retiva'];
        $this->retislrmonto->DbValue = $row['retislrmonto'];
        $this->retislr->DbValue = $row['retislr'];
        $this->comprobante->DbValue = $row['comprobante'];
        $this->tipodoc->DbValue = $row['tipodoc'];
        $this->fecha->DbValue = $row['fecha'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        return implode(Config("COMPOSITE_KEY_SEPARATOR"), $keys);
    }

    // Set Key
    public function setKey($key, $current = false)
    {
        $this->OldKey = strval($key);
        $keys = explode(Config("COMPOSITE_KEY_SEPARATOR"), $this->OldKey);
        if (count($keys) == 0) {
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
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
        return $_SESSION[$name] ?? GetUrl("ViewXCobrarList");
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
        if ($pageName == "ViewXCobrarView") {
            return $Language->phrase("View");
        } elseif ($pageName == "ViewXCobrarEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "ViewXCobrarAdd") {
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
                return "ViewXCobrarView";
            case Config("API_ADD_ACTION"):
                return "ViewXCobrarAdd";
            case Config("API_EDIT_ACTION"):
                return "ViewXCobrarEdit";
            case Config("API_DELETE_ACTION"):
                return "ViewXCobrarDelete";
            case Config("API_LIST_ACTION"):
                return "ViewXCobrarList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "ViewXCobrarList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ViewXCobrarView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("ViewXCobrarView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "ViewXCobrarAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "ViewXCobrarAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("ViewXCobrarEdit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("ViewXCobrarAdd", $this->getUrlParm($parm));
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
        return $this->keyUrl("ViewXCobrarDelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
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
            //return $arKeys; // Do not return yet, so the values will also be checked by the following code
        }
        // Check keys
        $ar = [];
        if (is_array($arKeys)) {
            foreach ($arKeys as $key) {
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
        $this->cliente->setDbValue($row['cliente']);
        $this->nro_documento->setDbValue($row['nro_documento']);
        $this->monto_pagar->setDbValue($row['monto_pagar']);
        $this->monto_pagado->setDbValue($row['monto_pagado']);
        $this->retivamonto->setDbValue($row['retivamonto']);
        $this->retiva->setDbValue($row['retiva']);
        $this->retislrmonto->setDbValue($row['retislrmonto']);
        $this->retislr->setDbValue($row['retislr']);
        $this->comprobante->setDbValue($row['comprobante']);
        $this->tipodoc->setDbValue($row['tipodoc']);
        $this->fecha->setDbValue($row['fecha']);
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

        // cliente

        // nro_documento

        // monto_pagar

        // monto_pagado

        // retivamonto

        // retiva

        // retislrmonto

        // retislr

        // comprobante

        // tipodoc

        // fecha

        // id
        $this->id->ViewValue = $this->id->CurrentValue;
        $this->id->ViewCustomAttributes = "";

        // tipo_documento
        $this->tipo_documento->ViewValue = $this->tipo_documento->CurrentValue;
        $this->tipo_documento->ViewCustomAttributes = "";

        // cliente
        $this->cliente->ViewValue = $this->cliente->CurrentValue;
        $this->cliente->ViewCustomAttributes = "";

        // nro_documento
        $this->nro_documento->ViewValue = $this->nro_documento->CurrentValue;
        $this->nro_documento->ViewCustomAttributes = "";

        // monto_pagar
        $this->monto_pagar->ViewValue = $this->monto_pagar->CurrentValue;
        $this->monto_pagar->ViewValue = FormatNumber($this->monto_pagar->ViewValue, $this->monto_pagar->DefaultDecimalPrecision);
        $this->monto_pagar->ViewCustomAttributes = "";

        // monto_pagado
        $this->monto_pagado->ViewValue = $this->monto_pagado->CurrentValue;
        $this->monto_pagado->ViewValue = FormatNumber($this->monto_pagado->ViewValue, $this->monto_pagado->DefaultDecimalPrecision);
        $this->monto_pagado->ViewCustomAttributes = "";

        // retivamonto
        $this->retivamonto->ViewValue = $this->retivamonto->CurrentValue;
        $this->retivamonto->ViewValue = FormatNumber($this->retivamonto->ViewValue, $this->retivamonto->DefaultDecimalPrecision);
        $this->retivamonto->ViewCustomAttributes = "";

        // retiva
        $this->retiva->ViewValue = $this->retiva->CurrentValue;
        $this->retiva->ViewCustomAttributes = "";

        // retislrmonto
        $this->retislrmonto->ViewValue = $this->retislrmonto->CurrentValue;
        $this->retislrmonto->ViewValue = FormatNumber($this->retislrmonto->ViewValue, $this->retislrmonto->DefaultDecimalPrecision);
        $this->retislrmonto->ViewCustomAttributes = "";

        // retislr
        $this->retislr->ViewValue = $this->retislr->CurrentValue;
        $this->retislr->ViewCustomAttributes = "";

        // comprobante
        $this->comprobante->ViewValue = $this->comprobante->CurrentValue;
        $this->comprobante->ViewCustomAttributes = "";

        // tipodoc
        $this->tipodoc->ViewValue = $this->tipodoc->CurrentValue;
        $this->tipodoc->ViewCustomAttributes = "";

        // fecha
        $this->fecha->ViewValue = $this->fecha->CurrentValue;
        $this->fecha->ViewValue = FormatDateTime($this->fecha->ViewValue, 0);
        $this->fecha->ViewCustomAttributes = "";

        // id
        $this->id->LinkCustomAttributes = "";
        $this->id->HrefValue = "";
        $this->id->TooltipValue = "";

        // tipo_documento
        $this->tipo_documento->LinkCustomAttributes = "";
        $this->tipo_documento->HrefValue = "";
        $this->tipo_documento->TooltipValue = "";

        // cliente
        $this->cliente->LinkCustomAttributes = "";
        $this->cliente->HrefValue = "";
        $this->cliente->TooltipValue = "";

        // nro_documento
        $this->nro_documento->LinkCustomAttributes = "";
        $this->nro_documento->HrefValue = "";
        $this->nro_documento->TooltipValue = "";

        // monto_pagar
        $this->monto_pagar->LinkCustomAttributes = "";
        $this->monto_pagar->HrefValue = "";
        $this->monto_pagar->TooltipValue = "";

        // monto_pagado
        $this->monto_pagado->LinkCustomAttributes = "";
        $this->monto_pagado->HrefValue = "";
        $this->monto_pagado->TooltipValue = "";

        // retivamonto
        $this->retivamonto->LinkCustomAttributes = "";
        $this->retivamonto->HrefValue = "";
        $this->retivamonto->TooltipValue = "";

        // retiva
        $this->retiva->LinkCustomAttributes = "";
        $this->retiva->HrefValue = "";
        $this->retiva->TooltipValue = "";

        // retislrmonto
        $this->retislrmonto->LinkCustomAttributes = "";
        $this->retislrmonto->HrefValue = "";
        $this->retislrmonto->TooltipValue = "";

        // retislr
        $this->retislr->LinkCustomAttributes = "";
        $this->retislr->HrefValue = "";
        $this->retislr->TooltipValue = "";

        // comprobante
        $this->comprobante->LinkCustomAttributes = "";
        $this->comprobante->HrefValue = "";
        $this->comprobante->TooltipValue = "";

        // tipodoc
        $this->tipodoc->LinkCustomAttributes = "";
        $this->tipodoc->HrefValue = "";
        $this->tipodoc->TooltipValue = "";

        // fecha
        $this->fecha->LinkCustomAttributes = "";
        $this->fecha->HrefValue = "";
        $this->fecha->TooltipValue = "";

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
        $this->id->PlaceHolder = RemoveHtml($this->id->caption());

        // tipo_documento
        $this->tipo_documento->EditAttrs["class"] = "form-control";
        $this->tipo_documento->EditCustomAttributes = "";
        if (!$this->tipo_documento->Raw) {
            $this->tipo_documento->CurrentValue = HtmlDecode($this->tipo_documento->CurrentValue);
        }
        $this->tipo_documento->EditValue = $this->tipo_documento->CurrentValue;
        $this->tipo_documento->PlaceHolder = RemoveHtml($this->tipo_documento->caption());

        // cliente
        $this->cliente->EditAttrs["class"] = "form-control";
        $this->cliente->EditCustomAttributes = "";
        $this->cliente->EditValue = $this->cliente->CurrentValue;
        $this->cliente->PlaceHolder = RemoveHtml($this->cliente->caption());

        // nro_documento
        $this->nro_documento->EditAttrs["class"] = "form-control";
        $this->nro_documento->EditCustomAttributes = "";
        if (!$this->nro_documento->Raw) {
            $this->nro_documento->CurrentValue = HtmlDecode($this->nro_documento->CurrentValue);
        }
        $this->nro_documento->EditValue = $this->nro_documento->CurrentValue;
        $this->nro_documento->PlaceHolder = RemoveHtml($this->nro_documento->caption());

        // monto_pagar
        $this->monto_pagar->EditAttrs["class"] = "form-control";
        $this->monto_pagar->EditCustomAttributes = "";
        $this->monto_pagar->EditValue = $this->monto_pagar->CurrentValue;
        $this->monto_pagar->PlaceHolder = RemoveHtml($this->monto_pagar->caption());
        if (strval($this->monto_pagar->EditValue) != "" && is_numeric($this->monto_pagar->EditValue)) {
            $this->monto_pagar->EditValue = FormatNumber($this->monto_pagar->EditValue, -2, -1, -2, 0);
        }

        // monto_pagado
        $this->monto_pagado->EditAttrs["class"] = "form-control";
        $this->monto_pagado->EditCustomAttributes = "";
        $this->monto_pagado->EditValue = $this->monto_pagado->CurrentValue;
        $this->monto_pagado->PlaceHolder = RemoveHtml($this->monto_pagado->caption());
        if (strval($this->monto_pagado->EditValue) != "" && is_numeric($this->monto_pagado->EditValue)) {
            $this->monto_pagado->EditValue = FormatNumber($this->monto_pagado->EditValue, -2, -1, -2, 0);
        }

        // retivamonto
        $this->retivamonto->EditAttrs["class"] = "form-control";
        $this->retivamonto->EditCustomAttributes = "";
        $this->retivamonto->EditValue = $this->retivamonto->CurrentValue;
        $this->retivamonto->PlaceHolder = RemoveHtml($this->retivamonto->caption());
        if (strval($this->retivamonto->EditValue) != "" && is_numeric($this->retivamonto->EditValue)) {
            $this->retivamonto->EditValue = FormatNumber($this->retivamonto->EditValue, -2, -1, -2, 0);
        }

        // retiva
        $this->retiva->EditAttrs["class"] = "form-control";
        $this->retiva->EditCustomAttributes = "";
        $this->retiva->EditValue = $this->retiva->CurrentValue;
        $this->retiva->PlaceHolder = RemoveHtml($this->retiva->caption());

        // retislrmonto
        $this->retislrmonto->EditAttrs["class"] = "form-control";
        $this->retislrmonto->EditCustomAttributes = "";
        $this->retislrmonto->EditValue = $this->retislrmonto->CurrentValue;
        $this->retislrmonto->PlaceHolder = RemoveHtml($this->retislrmonto->caption());
        if (strval($this->retislrmonto->EditValue) != "" && is_numeric($this->retislrmonto->EditValue)) {
            $this->retislrmonto->EditValue = FormatNumber($this->retislrmonto->EditValue, -2, -1, -2, 0);
        }

        // retislr
        $this->retislr->EditAttrs["class"] = "form-control";
        $this->retislr->EditCustomAttributes = "";
        $this->retislr->EditValue = $this->retislr->CurrentValue;
        $this->retislr->PlaceHolder = RemoveHtml($this->retislr->caption());

        // comprobante
        $this->comprobante->EditAttrs["class"] = "form-control";
        $this->comprobante->EditCustomAttributes = "";
        $this->comprobante->EditValue = $this->comprobante->CurrentValue;
        $this->comprobante->PlaceHolder = RemoveHtml($this->comprobante->caption());

        // tipodoc
        $this->tipodoc->EditAttrs["class"] = "form-control";
        $this->tipodoc->EditCustomAttributes = "";
        if (!$this->tipodoc->Raw) {
            $this->tipodoc->CurrentValue = HtmlDecode($this->tipodoc->CurrentValue);
        }
        $this->tipodoc->EditValue = $this->tipodoc->CurrentValue;
        $this->tipodoc->PlaceHolder = RemoveHtml($this->tipodoc->caption());

        // fecha
        $this->fecha->EditAttrs["class"] = "form-control";
        $this->fecha->EditCustomAttributes = "";
        $this->fecha->EditValue = FormatDateTime($this->fecha->CurrentValue, 8);
        $this->fecha->PlaceHolder = RemoveHtml($this->fecha->caption());

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
                    $doc->exportCaption($this->tipo_documento);
                    $doc->exportCaption($this->cliente);
                    $doc->exportCaption($this->nro_documento);
                    $doc->exportCaption($this->monto_pagar);
                    $doc->exportCaption($this->monto_pagado);
                    $doc->exportCaption($this->retivamonto);
                    $doc->exportCaption($this->retiva);
                    $doc->exportCaption($this->retislrmonto);
                    $doc->exportCaption($this->retislr);
                    $doc->exportCaption($this->comprobante);
                    $doc->exportCaption($this->tipodoc);
                    $doc->exportCaption($this->fecha);
                } else {
                    $doc->exportCaption($this->id);
                    $doc->exportCaption($this->tipo_documento);
                    $doc->exportCaption($this->cliente);
                    $doc->exportCaption($this->nro_documento);
                    $doc->exportCaption($this->monto_pagar);
                    $doc->exportCaption($this->monto_pagado);
                    $doc->exportCaption($this->retivamonto);
                    $doc->exportCaption($this->retislrmonto);
                    $doc->exportCaption($this->comprobante);
                    $doc->exportCaption($this->tipodoc);
                    $doc->exportCaption($this->fecha);
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
                        $doc->exportField($this->tipo_documento);
                        $doc->exportField($this->cliente);
                        $doc->exportField($this->nro_documento);
                        $doc->exportField($this->monto_pagar);
                        $doc->exportField($this->monto_pagado);
                        $doc->exportField($this->retivamonto);
                        $doc->exportField($this->retiva);
                        $doc->exportField($this->retislrmonto);
                        $doc->exportField($this->retislr);
                        $doc->exportField($this->comprobante);
                        $doc->exportField($this->tipodoc);
                        $doc->exportField($this->fecha);
                    } else {
                        $doc->exportField($this->id);
                        $doc->exportField($this->tipo_documento);
                        $doc->exportField($this->cliente);
                        $doc->exportField($this->nro_documento);
                        $doc->exportField($this->monto_pagar);
                        $doc->exportField($this->monto_pagado);
                        $doc->exportField($this->retivamonto);
                        $doc->exportField($this->retislrmonto);
                        $doc->exportField($this->comprobante);
                        $doc->exportField($this->tipodoc);
                        $doc->exportField($this->fecha);
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
    public function rowInserted($rsold, &$rsnew) {
    	//echo "Row Inserted"
    	$sql = "SELECT valor1 AS centro_costo, valor3 AS segmentos FROM parametro WHERE codigo = '019';";
    	$segmentos = intval(ExecuteScalar($sql));
    	if($segmentos != 6 and $segmentos != 4) $segmentos = 4;
    	$sql = "SELECT valor2 AS cuenta FROM parametro WHERE codigo = '018' AND valor1 = 'Clientes';";
    	$cuenta = explode(".", ExecuteScalar($sql));
    	if(count($cuenta) > 2) {
    		if($segmentos == 6) { 
    			$sql = "INSERT INTO cont_plancta
    					(clase, 
    					grupo, 
    					cuenta, 
    					subcuenta, 
    					centro_costo, 
    					auxiliar, 
    					descripcion, 
    					clasificacion, 
    					activa)
    				VALUES
    					(
    					'" . $cuenta["0"] . "', 
    					'" . $cuenta["1"] . "', 
    					'" . $cuenta["2"] . "', 
    					'" . $cuenta["3"] . "', 
    					NULL, 
    					" . $rsnew["id"] . ", 
    					'" . $rsnew["nombre"] . "',
    					'ACTIVO', 
    					'S'
    					)";
    		}
    		else {
    			$sql = "INSERT INTO cont_plancta
    					(clase, 
    					grupo, 
    					cuenta, 
    					subcuenta, 
    					centro_costo, 
    					auxiliar, 
    					descripcion, 
    					clasificacion, 
    					activa)
    				VALUES
    					(
    					'" . $cuenta["0"] . "', 
    					'" . $cuenta["1"] . "', 
    					'" . $cuenta["2"] . "', 
    					'" . str_pad(intval($rsnew["id"]), 4, '0', STR_PAD_LEFT) . "', 
    					NULL, 
    					NULL, 
    					'" . $rsnew["nombre"] . "',
    					'ACTIVO', 
    					'S'
    					)";
    		}
    		Execute($sql);
    		$sql = "SELECT LAST_INSERT_ID();";
    		$idCTA = ExecuteScalar($sql);
    		$sql = "UPDATE cliente SET cuenta = $idCTA WHERE id = '" . $rsnew["id"] . "'";
    		Execute($sql);
    	}
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
