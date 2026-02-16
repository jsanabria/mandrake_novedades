<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for view_x_pagar
 */
class ViewXPagar extends DbTable
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
    public $proveedor;
    public $nro_documento;
    public $monto_pagar;
    public $monto_pagado;
    public $total;
    public $ret_iva;
    public $ref_iva;
    public $ret_islr;
    public $ref_islr;
    public $comprobante;
    public $tipodoc;
    public $fecha;
    public $tipo_iva;
    public $tipo_islr;
    public $tipo_municipal;
    public $sustraendo;
    public $ret_municipal;
    public $ref_municipal;
    public $gravado;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'view_x_pagar';
        $this->TableName = 'view_x_pagar';
        $this->TableType = 'VIEW';

        // Update Table
        $this->UpdateTable = "`view_x_pagar`";
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
        $this->id = new DbField('view_x_pagar', 'view_x_pagar', 'x_id', 'id', '`id`', '`id`', 19, 10, -1, false, '`id`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->id->Nullable = false; // NOT NULL field
        $this->id->Required = true; // Required field
        $this->id->Sortable = true; // Allow sort
        $this->id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->id->Param, "CustomMsg");
        $this->Fields['id'] = &$this->id;

        // tipo_documento
        $this->tipo_documento = new DbField('view_x_pagar', 'view_x_pagar', 'x_tipo_documento', 'tipo_documento', '`tipo_documento`', '`tipo_documento`', 200, 6, -1, false, '`tipo_documento`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->tipo_documento->Sortable = true; // Allow sort
        $this->tipo_documento->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tipo_documento->Param, "CustomMsg");
        $this->Fields['tipo_documento'] = &$this->tipo_documento;

        // proveedor
        $this->proveedor = new DbField('view_x_pagar', 'view_x_pagar', 'x_proveedor', 'proveedor', '`proveedor`', '`proveedor`', 20, 11, -1, false, '`proveedor`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->proveedor->Sortable = true; // Allow sort
        $this->proveedor->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->proveedor->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->proveedor->Param, "CustomMsg");
        $this->Fields['proveedor'] = &$this->proveedor;

        // nro_documento
        $this->nro_documento = new DbField('view_x_pagar', 'view_x_pagar', 'x_nro_documento', 'nro_documento', '`nro_documento`', '`nro_documento`', 200, 20, -1, false, '`nro_documento`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->nro_documento->Sortable = true; // Allow sort
        $this->nro_documento->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->nro_documento->Param, "CustomMsg");
        $this->Fields['nro_documento'] = &$this->nro_documento;

        // monto_pagar
        $this->monto_pagar = new DbField('view_x_pagar', 'view_x_pagar', 'x_monto_pagar', 'monto_pagar', '`monto_pagar`', '`monto_pagar`', 131, 14, -1, false, '`monto_pagar`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->monto_pagar->Sortable = true; // Allow sort
        $this->monto_pagar->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_pagar->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->monto_pagar->Param, "CustomMsg");
        $this->Fields['monto_pagar'] = &$this->monto_pagar;

        // monto_pagado
        $this->monto_pagado = new DbField('view_x_pagar', 'view_x_pagar', 'x_monto_pagado', 'monto_pagado', '`monto_pagado`', '`monto_pagado`', 131, 36, -1, false, '`monto_pagado`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->monto_pagado->Sortable = true; // Allow sort
        $this->monto_pagado->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_pagado->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->monto_pagado->Param, "CustomMsg");
        $this->Fields['monto_pagado'] = &$this->monto_pagado;

        // total
        $this->total = new DbField('view_x_pagar', 'view_x_pagar', 'x_total', 'total', '`total`', '`total`', 131, 14, -1, false, '`total`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->total->Sortable = true; // Allow sort
        $this->total->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->total->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->total->Param, "CustomMsg");
        $this->Fields['total'] = &$this->total;

        // ret_iva
        $this->ret_iva = new DbField('view_x_pagar', 'view_x_pagar', 'x_ret_iva', 'ret_iva', '`ret_iva`', '`ret_iva`', 131, 14, -1, false, '`ret_iva`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->ret_iva->Sortable = true; // Allow sort
        $this->ret_iva->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->ret_iva->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->ret_iva->Param, "CustomMsg");
        $this->Fields['ret_iva'] = &$this->ret_iva;

        // ref_iva
        $this->ref_iva = new DbField('view_x_pagar', 'view_x_pagar', 'x_ref_iva', 'ref_iva', '`ref_iva`', '`ref_iva`', 200, 30, -1, false, '`ref_iva`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->ref_iva->Sortable = true; // Allow sort
        $this->ref_iva->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->ref_iva->Param, "CustomMsg");
        $this->Fields['ref_iva'] = &$this->ref_iva;

        // ret_islr
        $this->ret_islr = new DbField('view_x_pagar', 'view_x_pagar', 'x_ret_islr', 'ret_islr', '`ret_islr`', '`ret_islr`', 131, 14, -1, false, '`ret_islr`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->ret_islr->Sortable = true; // Allow sort
        $this->ret_islr->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->ret_islr->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->ret_islr->Param, "CustomMsg");
        $this->Fields['ret_islr'] = &$this->ret_islr;

        // ref_islr
        $this->ref_islr = new DbField('view_x_pagar', 'view_x_pagar', 'x_ref_islr', 'ref_islr', '`ref_islr`', '`ref_islr`', 200, 30, -1, false, '`ref_islr`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->ref_islr->Sortable = true; // Allow sort
        $this->ref_islr->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->ref_islr->Param, "CustomMsg");
        $this->Fields['ref_islr'] = &$this->ref_islr;

        // comprobante
        $this->comprobante = new DbField('view_x_pagar', 'view_x_pagar', 'x_comprobante', 'comprobante', '`comprobante`', '`comprobante`', 19, 10, -1, false, '`comprobante`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->comprobante->Sortable = true; // Allow sort
        $this->comprobante->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->comprobante->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->comprobante->Param, "CustomMsg");
        $this->Fields['comprobante'] = &$this->comprobante;

        // tipodoc
        $this->tipodoc = new DbField('view_x_pagar', 'view_x_pagar', 'x_tipodoc', 'tipodoc', '`tipodoc`', '`tipodoc`', 200, 2, -1, false, '`tipodoc`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->tipodoc->Sortable = true; // Allow sort
        $this->tipodoc->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tipodoc->Param, "CustomMsg");
        $this->Fields['tipodoc'] = &$this->tipodoc;

        // fecha
        $this->fecha = new DbField('view_x_pagar', 'view_x_pagar', 'x_fecha', 'fecha', '`fecha`', CastDateFieldForLike("`fecha`", 0, "DB"), 135, 19, 0, false, '`fecha`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->fecha->Sortable = true; // Allow sort
        $this->fecha->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->fecha->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->fecha->Param, "CustomMsg");
        $this->Fields['fecha'] = &$this->fecha;

        // tipo_iva
        $this->tipo_iva = new DbField('view_x_pagar', 'view_x_pagar', 'x_tipo_iva', 'tipo_iva', '`tipo_iva`', '`tipo_iva`', 200, 4, -1, false, '`tipo_iva`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->tipo_iva->Sortable = true; // Allow sort
        $this->tipo_iva->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tipo_iva->Param, "CustomMsg");
        $this->Fields['tipo_iva'] = &$this->tipo_iva;

        // tipo_islr
        $this->tipo_islr = new DbField('view_x_pagar', 'view_x_pagar', 'x_tipo_islr', 'tipo_islr', '`tipo_islr`', '`tipo_islr`', 200, 4, -1, false, '`tipo_islr`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->tipo_islr->Sortable = true; // Allow sort
        $this->tipo_islr->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tipo_islr->Param, "CustomMsg");
        $this->Fields['tipo_islr'] = &$this->tipo_islr;

        // tipo_municipal
        $this->tipo_municipal = new DbField('view_x_pagar', 'view_x_pagar', 'x_tipo_municipal', 'tipo_municipal', '`tipo_municipal`', '`tipo_municipal`', 200, 4, -1, false, '`tipo_municipal`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->tipo_municipal->Sortable = true; // Allow sort
        $this->tipo_municipal->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tipo_municipal->Param, "CustomMsg");
        $this->Fields['tipo_municipal'] = &$this->tipo_municipal;

        // sustraendo
        $this->sustraendo = new DbField('view_x_pagar', 'view_x_pagar', 'x_sustraendo', 'sustraendo', '`sustraendo`', '`sustraendo`', 131, 14, -1, false, '`sustraendo`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->sustraendo->Sortable = true; // Allow sort
        $this->sustraendo->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->sustraendo->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->sustraendo->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->sustraendo->Param, "CustomMsg");
        $this->Fields['sustraendo'] = &$this->sustraendo;

        // ret_municipal
        $this->ret_municipal = new DbField('view_x_pagar', 'view_x_pagar', 'x_ret_municipal', 'ret_municipal', '`ret_municipal`', '`ret_municipal`', 131, 14, -1, false, '`ret_municipal`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->ret_municipal->Sortable = true; // Allow sort
        $this->ret_municipal->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->ret_municipal->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->ret_municipal->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->ret_municipal->Param, "CustomMsg");
        $this->Fields['ret_municipal'] = &$this->ret_municipal;

        // ref_municipal
        $this->ref_municipal = new DbField('view_x_pagar', 'view_x_pagar', 'x_ref_municipal', 'ref_municipal', '`ref_municipal`', '`ref_municipal`', 200, 30, -1, false, '`ref_municipal`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->ref_municipal->Sortable = true; // Allow sort
        $this->ref_municipal->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->ref_municipal->Param, "CustomMsg");
        $this->Fields['ref_municipal'] = &$this->ref_municipal;

        // gravado
        $this->gravado = new DbField('view_x_pagar', 'view_x_pagar', 'x_gravado', 'gravado', '`gravado`', '`gravado`', 131, 36, -1, false, '`gravado`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->gravado->Sortable = true; // Allow sort
        $this->gravado->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->gravado->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->gravado->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->gravado->Param, "CustomMsg");
        $this->Fields['gravado'] = &$this->gravado;
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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`view_x_pagar`";
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
        $this->proveedor->DbValue = $row['proveedor'];
        $this->nro_documento->DbValue = $row['nro_documento'];
        $this->monto_pagar->DbValue = $row['monto_pagar'];
        $this->monto_pagado->DbValue = $row['monto_pagado'];
        $this->total->DbValue = $row['total'];
        $this->ret_iva->DbValue = $row['ret_iva'];
        $this->ref_iva->DbValue = $row['ref_iva'];
        $this->ret_islr->DbValue = $row['ret_islr'];
        $this->ref_islr->DbValue = $row['ref_islr'];
        $this->comprobante->DbValue = $row['comprobante'];
        $this->tipodoc->DbValue = $row['tipodoc'];
        $this->fecha->DbValue = $row['fecha'];
        $this->tipo_iva->DbValue = $row['tipo_iva'];
        $this->tipo_islr->DbValue = $row['tipo_islr'];
        $this->tipo_municipal->DbValue = $row['tipo_municipal'];
        $this->sustraendo->DbValue = $row['sustraendo'];
        $this->ret_municipal->DbValue = $row['ret_municipal'];
        $this->ref_municipal->DbValue = $row['ref_municipal'];
        $this->gravado->DbValue = $row['gravado'];
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
        return $_SESSION[$name] ?? GetUrl("ViewXPagarList");
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
        if ($pageName == "ViewXPagarView") {
            return $Language->phrase("View");
        } elseif ($pageName == "ViewXPagarEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "ViewXPagarAdd") {
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
                return "ViewXPagarView";
            case Config("API_ADD_ACTION"):
                return "ViewXPagarAdd";
            case Config("API_EDIT_ACTION"):
                return "ViewXPagarEdit";
            case Config("API_DELETE_ACTION"):
                return "ViewXPagarDelete";
            case Config("API_LIST_ACTION"):
                return "ViewXPagarList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "ViewXPagarList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ViewXPagarView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("ViewXPagarView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "ViewXPagarAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "ViewXPagarAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("ViewXPagarEdit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("ViewXPagarAdd", $this->getUrlParm($parm));
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
        return $this->keyUrl("ViewXPagarDelete", $this->getUrlParm());
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
        $this->proveedor->setDbValue($row['proveedor']);
        $this->nro_documento->setDbValue($row['nro_documento']);
        $this->monto_pagar->setDbValue($row['monto_pagar']);
        $this->monto_pagado->setDbValue($row['monto_pagado']);
        $this->total->setDbValue($row['total']);
        $this->ret_iva->setDbValue($row['ret_iva']);
        $this->ref_iva->setDbValue($row['ref_iva']);
        $this->ret_islr->setDbValue($row['ret_islr']);
        $this->ref_islr->setDbValue($row['ref_islr']);
        $this->comprobante->setDbValue($row['comprobante']);
        $this->tipodoc->setDbValue($row['tipodoc']);
        $this->fecha->setDbValue($row['fecha']);
        $this->tipo_iva->setDbValue($row['tipo_iva']);
        $this->tipo_islr->setDbValue($row['tipo_islr']);
        $this->tipo_municipal->setDbValue($row['tipo_municipal']);
        $this->sustraendo->setDbValue($row['sustraendo']);
        $this->ret_municipal->setDbValue($row['ret_municipal']);
        $this->ref_municipal->setDbValue($row['ref_municipal']);
        $this->gravado->setDbValue($row['gravado']);
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

        // proveedor

        // nro_documento

        // monto_pagar

        // monto_pagado

        // total

        // ret_iva

        // ref_iva

        // ret_islr

        // ref_islr

        // comprobante

        // tipodoc

        // fecha

        // tipo_iva

        // tipo_islr

        // tipo_municipal

        // sustraendo

        // ret_municipal

        // ref_municipal

        // gravado

        // id
        $this->id->ViewValue = $this->id->CurrentValue;
        $this->id->ViewCustomAttributes = "";

        // tipo_documento
        $this->tipo_documento->ViewValue = $this->tipo_documento->CurrentValue;
        $this->tipo_documento->ViewCustomAttributes = "";

        // proveedor
        $this->proveedor->ViewValue = $this->proveedor->CurrentValue;
        $this->proveedor->ViewCustomAttributes = "";

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

        // total
        $this->total->ViewValue = $this->total->CurrentValue;
        $this->total->ViewValue = FormatNumber($this->total->ViewValue, $this->total->DefaultDecimalPrecision);
        $this->total->ViewCustomAttributes = "";

        // ret_iva
        $this->ret_iva->ViewValue = $this->ret_iva->CurrentValue;
        $this->ret_iva->ViewValue = FormatNumber($this->ret_iva->ViewValue, $this->ret_iva->DefaultDecimalPrecision);
        $this->ret_iva->ViewCustomAttributes = "";

        // ref_iva
        $this->ref_iva->ViewValue = $this->ref_iva->CurrentValue;
        $this->ref_iva->ViewCustomAttributes = "";

        // ret_islr
        $this->ret_islr->ViewValue = $this->ret_islr->CurrentValue;
        $this->ret_islr->ViewValue = FormatNumber($this->ret_islr->ViewValue, $this->ret_islr->DefaultDecimalPrecision);
        $this->ret_islr->ViewCustomAttributes = "";

        // ref_islr
        $this->ref_islr->ViewValue = $this->ref_islr->CurrentValue;
        $this->ref_islr->ViewCustomAttributes = "";

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

        // tipo_iva
        $this->tipo_iva->ViewValue = $this->tipo_iva->CurrentValue;
        $this->tipo_iva->ViewCustomAttributes = "";

        // tipo_islr
        $this->tipo_islr->ViewValue = $this->tipo_islr->CurrentValue;
        $this->tipo_islr->ViewCustomAttributes = "";

        // tipo_municipal
        $this->tipo_municipal->ViewValue = $this->tipo_municipal->CurrentValue;
        $this->tipo_municipal->ViewCustomAttributes = "";

        // sustraendo
        $this->sustraendo->ViewValue = $this->sustraendo->CurrentValue;
        $this->sustraendo->ViewValue = FormatNumber($this->sustraendo->ViewValue, 2, -2, -2, -2);
        $this->sustraendo->ViewCustomAttributes = "";

        // ret_municipal
        $this->ret_municipal->ViewValue = $this->ret_municipal->CurrentValue;
        $this->ret_municipal->ViewValue = FormatNumber($this->ret_municipal->ViewValue, 2, -2, -2, -2);
        $this->ret_municipal->ViewCustomAttributes = "";

        // ref_municipal
        $this->ref_municipal->ViewValue = $this->ref_municipal->CurrentValue;
        $this->ref_municipal->ViewCustomAttributes = "";

        // gravado
        $this->gravado->ViewValue = $this->gravado->CurrentValue;
        $this->gravado->ViewValue = FormatNumber($this->gravado->ViewValue, 2, -2, -2, -2);
        $this->gravado->ViewCustomAttributes = "";

        // id
        $this->id->LinkCustomAttributes = "";
        $this->id->HrefValue = "";
        $this->id->TooltipValue = "";

        // tipo_documento
        $this->tipo_documento->LinkCustomAttributes = "";
        $this->tipo_documento->HrefValue = "";
        $this->tipo_documento->TooltipValue = "";

        // proveedor
        $this->proveedor->LinkCustomAttributes = "";
        $this->proveedor->HrefValue = "";
        $this->proveedor->TooltipValue = "";

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

        // total
        $this->total->LinkCustomAttributes = "";
        $this->total->HrefValue = "";
        $this->total->TooltipValue = "";

        // ret_iva
        $this->ret_iva->LinkCustomAttributes = "";
        $this->ret_iva->HrefValue = "";
        $this->ret_iva->TooltipValue = "";

        // ref_iva
        $this->ref_iva->LinkCustomAttributes = "";
        $this->ref_iva->HrefValue = "";
        $this->ref_iva->TooltipValue = "";

        // ret_islr
        $this->ret_islr->LinkCustomAttributes = "";
        $this->ret_islr->HrefValue = "";
        $this->ret_islr->TooltipValue = "";

        // ref_islr
        $this->ref_islr->LinkCustomAttributes = "";
        $this->ref_islr->HrefValue = "";
        $this->ref_islr->TooltipValue = "";

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

        // tipo_iva
        $this->tipo_iva->LinkCustomAttributes = "";
        $this->tipo_iva->HrefValue = "";
        $this->tipo_iva->TooltipValue = "";

        // tipo_islr
        $this->tipo_islr->LinkCustomAttributes = "";
        $this->tipo_islr->HrefValue = "";
        $this->tipo_islr->TooltipValue = "";

        // tipo_municipal
        $this->tipo_municipal->LinkCustomAttributes = "";
        $this->tipo_municipal->HrefValue = "";
        $this->tipo_municipal->TooltipValue = "";

        // sustraendo
        $this->sustraendo->LinkCustomAttributes = "";
        $this->sustraendo->HrefValue = "";
        $this->sustraendo->TooltipValue = "";

        // ret_municipal
        $this->ret_municipal->LinkCustomAttributes = "";
        $this->ret_municipal->HrefValue = "";
        $this->ret_municipal->TooltipValue = "";

        // ref_municipal
        $this->ref_municipal->LinkCustomAttributes = "";
        $this->ref_municipal->HrefValue = "";
        $this->ref_municipal->TooltipValue = "";

        // gravado
        $this->gravado->LinkCustomAttributes = "";
        $this->gravado->HrefValue = "";
        $this->gravado->TooltipValue = "";

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

        // proveedor
        $this->proveedor->EditAttrs["class"] = "form-control";
        $this->proveedor->EditCustomAttributes = "";
        $this->proveedor->EditValue = $this->proveedor->CurrentValue;
        $this->proveedor->PlaceHolder = RemoveHtml($this->proveedor->caption());

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

        // total
        $this->total->EditAttrs["class"] = "form-control";
        $this->total->EditCustomAttributes = "";
        $this->total->EditValue = $this->total->CurrentValue;
        $this->total->PlaceHolder = RemoveHtml($this->total->caption());
        if (strval($this->total->EditValue) != "" && is_numeric($this->total->EditValue)) {
            $this->total->EditValue = FormatNumber($this->total->EditValue, -2, -1, -2, 0);
        }

        // ret_iva
        $this->ret_iva->EditAttrs["class"] = "form-control";
        $this->ret_iva->EditCustomAttributes = "";
        $this->ret_iva->EditValue = $this->ret_iva->CurrentValue;
        $this->ret_iva->PlaceHolder = RemoveHtml($this->ret_iva->caption());
        if (strval($this->ret_iva->EditValue) != "" && is_numeric($this->ret_iva->EditValue)) {
            $this->ret_iva->EditValue = FormatNumber($this->ret_iva->EditValue, -2, -1, -2, 0);
        }

        // ref_iva
        $this->ref_iva->EditAttrs["class"] = "form-control";
        $this->ref_iva->EditCustomAttributes = "";
        if (!$this->ref_iva->Raw) {
            $this->ref_iva->CurrentValue = HtmlDecode($this->ref_iva->CurrentValue);
        }
        $this->ref_iva->EditValue = $this->ref_iva->CurrentValue;
        $this->ref_iva->PlaceHolder = RemoveHtml($this->ref_iva->caption());

        // ret_islr
        $this->ret_islr->EditAttrs["class"] = "form-control";
        $this->ret_islr->EditCustomAttributes = "";
        $this->ret_islr->EditValue = $this->ret_islr->CurrentValue;
        $this->ret_islr->PlaceHolder = RemoveHtml($this->ret_islr->caption());
        if (strval($this->ret_islr->EditValue) != "" && is_numeric($this->ret_islr->EditValue)) {
            $this->ret_islr->EditValue = FormatNumber($this->ret_islr->EditValue, -2, -1, -2, 0);
        }

        // ref_islr
        $this->ref_islr->EditAttrs["class"] = "form-control";
        $this->ref_islr->EditCustomAttributes = "";
        if (!$this->ref_islr->Raw) {
            $this->ref_islr->CurrentValue = HtmlDecode($this->ref_islr->CurrentValue);
        }
        $this->ref_islr->EditValue = $this->ref_islr->CurrentValue;
        $this->ref_islr->PlaceHolder = RemoveHtml($this->ref_islr->caption());

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

        // tipo_iva
        $this->tipo_iva->EditAttrs["class"] = "form-control";
        $this->tipo_iva->EditCustomAttributes = "";
        if (!$this->tipo_iva->Raw) {
            $this->tipo_iva->CurrentValue = HtmlDecode($this->tipo_iva->CurrentValue);
        }
        $this->tipo_iva->EditValue = $this->tipo_iva->CurrentValue;
        $this->tipo_iva->PlaceHolder = RemoveHtml($this->tipo_iva->caption());

        // tipo_islr
        $this->tipo_islr->EditAttrs["class"] = "form-control";
        $this->tipo_islr->EditCustomAttributes = "";
        if (!$this->tipo_islr->Raw) {
            $this->tipo_islr->CurrentValue = HtmlDecode($this->tipo_islr->CurrentValue);
        }
        $this->tipo_islr->EditValue = $this->tipo_islr->CurrentValue;
        $this->tipo_islr->PlaceHolder = RemoveHtml($this->tipo_islr->caption());

        // tipo_municipal
        $this->tipo_municipal->EditAttrs["class"] = "form-control";
        $this->tipo_municipal->EditCustomAttributes = "";
        if (!$this->tipo_municipal->Raw) {
            $this->tipo_municipal->CurrentValue = HtmlDecode($this->tipo_municipal->CurrentValue);
        }
        $this->tipo_municipal->EditValue = $this->tipo_municipal->CurrentValue;
        $this->tipo_municipal->PlaceHolder = RemoveHtml($this->tipo_municipal->caption());

        // sustraendo
        $this->sustraendo->EditAttrs["class"] = "form-control";
        $this->sustraendo->EditCustomAttributes = "";
        $this->sustraendo->EditValue = $this->sustraendo->CurrentValue;
        $this->sustraendo->PlaceHolder = RemoveHtml($this->sustraendo->caption());
        if (strval($this->sustraendo->EditValue) != "" && is_numeric($this->sustraendo->EditValue)) {
            $this->sustraendo->EditValue = FormatNumber($this->sustraendo->EditValue, -2, -2, -2, -2);
        }

        // ret_municipal
        $this->ret_municipal->EditAttrs["class"] = "form-control";
        $this->ret_municipal->EditCustomAttributes = "";
        $this->ret_municipal->EditValue = $this->ret_municipal->CurrentValue;
        $this->ret_municipal->PlaceHolder = RemoveHtml($this->ret_municipal->caption());
        if (strval($this->ret_municipal->EditValue) != "" && is_numeric($this->ret_municipal->EditValue)) {
            $this->ret_municipal->EditValue = FormatNumber($this->ret_municipal->EditValue, -2, -2, -2, -2);
        }

        // ref_municipal
        $this->ref_municipal->EditAttrs["class"] = "form-control";
        $this->ref_municipal->EditCustomAttributes = "";
        if (!$this->ref_municipal->Raw) {
            $this->ref_municipal->CurrentValue = HtmlDecode($this->ref_municipal->CurrentValue);
        }
        $this->ref_municipal->EditValue = $this->ref_municipal->CurrentValue;
        $this->ref_municipal->PlaceHolder = RemoveHtml($this->ref_municipal->caption());

        // gravado
        $this->gravado->EditAttrs["class"] = "form-control";
        $this->gravado->EditCustomAttributes = "";
        $this->gravado->EditValue = $this->gravado->CurrentValue;
        $this->gravado->PlaceHolder = RemoveHtml($this->gravado->caption());
        if (strval($this->gravado->EditValue) != "" && is_numeric($this->gravado->EditValue)) {
            $this->gravado->EditValue = FormatNumber($this->gravado->EditValue, -2, -2, -2, -2);
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
                    $doc->exportCaption($this->tipo_documento);
                    $doc->exportCaption($this->proveedor);
                    $doc->exportCaption($this->nro_documento);
                    $doc->exportCaption($this->monto_pagar);
                    $doc->exportCaption($this->monto_pagado);
                    $doc->exportCaption($this->total);
                    $doc->exportCaption($this->ret_iva);
                    $doc->exportCaption($this->ref_iva);
                    $doc->exportCaption($this->ret_islr);
                    $doc->exportCaption($this->ref_islr);
                    $doc->exportCaption($this->comprobante);
                    $doc->exportCaption($this->tipodoc);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->tipo_iva);
                    $doc->exportCaption($this->tipo_islr);
                    $doc->exportCaption($this->tipo_municipal);
                    $doc->exportCaption($this->sustraendo);
                    $doc->exportCaption($this->ret_municipal);
                    $doc->exportCaption($this->ref_municipal);
                    $doc->exportCaption($this->gravado);
                } else {
                    $doc->exportCaption($this->id);
                    $doc->exportCaption($this->tipo_documento);
                    $doc->exportCaption($this->proveedor);
                    $doc->exportCaption($this->nro_documento);
                    $doc->exportCaption($this->monto_pagar);
                    $doc->exportCaption($this->monto_pagado);
                    $doc->exportCaption($this->total);
                    $doc->exportCaption($this->ret_iva);
                    $doc->exportCaption($this->ref_iva);
                    $doc->exportCaption($this->ret_islr);
                    $doc->exportCaption($this->ref_islr);
                    $doc->exportCaption($this->comprobante);
                    $doc->exportCaption($this->tipodoc);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->tipo_iva);
                    $doc->exportCaption($this->tipo_islr);
                    $doc->exportCaption($this->tipo_municipal);
                    $doc->exportCaption($this->sustraendo);
                    $doc->exportCaption($this->ret_municipal);
                    $doc->exportCaption($this->ref_municipal);
                    $doc->exportCaption($this->gravado);
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
                        $doc->exportField($this->proveedor);
                        $doc->exportField($this->nro_documento);
                        $doc->exportField($this->monto_pagar);
                        $doc->exportField($this->monto_pagado);
                        $doc->exportField($this->total);
                        $doc->exportField($this->ret_iva);
                        $doc->exportField($this->ref_iva);
                        $doc->exportField($this->ret_islr);
                        $doc->exportField($this->ref_islr);
                        $doc->exportField($this->comprobante);
                        $doc->exportField($this->tipodoc);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->tipo_iva);
                        $doc->exportField($this->tipo_islr);
                        $doc->exportField($this->tipo_municipal);
                        $doc->exportField($this->sustraendo);
                        $doc->exportField($this->ret_municipal);
                        $doc->exportField($this->ref_municipal);
                        $doc->exportField($this->gravado);
                    } else {
                        $doc->exportField($this->id);
                        $doc->exportField($this->tipo_documento);
                        $doc->exportField($this->proveedor);
                        $doc->exportField($this->nro_documento);
                        $doc->exportField($this->monto_pagar);
                        $doc->exportField($this->monto_pagado);
                        $doc->exportField($this->total);
                        $doc->exportField($this->ret_iva);
                        $doc->exportField($this->ref_iva);
                        $doc->exportField($this->ret_islr);
                        $doc->exportField($this->ref_islr);
                        $doc->exportField($this->comprobante);
                        $doc->exportField($this->tipodoc);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->tipo_iva);
                        $doc->exportField($this->tipo_islr);
                        $doc->exportField($this->tipo_municipal);
                        $doc->exportField($this->sustraendo);
                        $doc->exportField($this->ret_municipal);
                        $doc->exportField($this->ref_municipal);
                        $doc->exportField($this->gravado);
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
