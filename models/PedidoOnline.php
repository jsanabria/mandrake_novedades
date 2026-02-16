<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for pedido_online
 */
class PedidoOnline extends DbTable
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
    public $tipo_documento;
    public $asesor;
    public $cliente;
    public $_username;
    public $fecha;
    public $nro_documento;
    public $nro_control;
    public $monto_total;
    public $alicuota_iva;
    public $iva;
    public $total;
    public $nota;
    public $estatus;
    public $id_documento_padre;
    public $moneda;
    public $documento;
    public $tasa_dia;
    public $monto_usd;
    public $dias_credito;
    public $entregado;
    public $fecha_entrega;
    public $pagado;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'pedido_online';
        $this->TableName = 'pedido_online';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`pedido_online`";
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
        $this->id = new DbField('pedido_online', 'pedido_online', 'x_id', 'id', '`id`', '`id`', 19, 10, -1, false, '`id`', false, false, false, 'FORMATTED TEXT', 'NO');
        $this->id->IsAutoIncrement = true; // Autoincrement field
        $this->id->IsPrimaryKey = true; // Primary key field
        $this->id->IsForeignKey = true; // Foreign key field
        $this->id->Sortable = true; // Allow sort
        $this->id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->id->Param, "CustomMsg");
        $this->Fields['id'] = &$this->id;

        // tipo_documento
        $this->tipo_documento = new DbField('pedido_online', 'pedido_online', 'x_tipo_documento', 'tipo_documento', '`tipo_documento`', '`tipo_documento`', 200, 6, -1, false, '`tipo_documento`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->tipo_documento->IsForeignKey = true; // Foreign key field
        $this->tipo_documento->Sortable = true; // Allow sort
        $this->tipo_documento->Lookup = new Lookup('tipo_documento', 'tipo_documento', false, 'codigo', ["descripcion","","",""], [], [], [], [], [], [], '', '');
        $this->tipo_documento->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tipo_documento->Param, "CustomMsg");
        $this->Fields['tipo_documento'] = &$this->tipo_documento;

        // asesor
        $this->asesor = new DbField('pedido_online', 'pedido_online', 'x_asesor', 'asesor', '`asesor`', '`asesor`', 200, 30, -1, false, '`asesor`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->asesor->Sortable = true; // Allow sort
        $this->asesor->Lookup = new Lookup('asesor', 'asesor', false, 'id', ["nombre","","",""], [], [], [], [], [], [], '', '');
        $this->asesor->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->asesor->Param, "CustomMsg");
        $this->Fields['asesor'] = &$this->asesor;

        // cliente
        $this->cliente = new DbField('pedido_online', 'pedido_online', 'x_cliente', 'cliente', '`cliente`', '`cliente`', 3, 11, -1, false, '`cliente`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->cliente->Sortable = true; // Allow sort
        $this->cliente->Lookup = new Lookup('cliente', 'cliente', false, 'id', ["nombre","","",""], [], [], [], [], [], [], '', '');
        $this->cliente->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->cliente->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->cliente->Param, "CustomMsg");
        $this->Fields['cliente'] = &$this->cliente;

        // username
        $this->_username = new DbField('pedido_online', 'pedido_online', 'x__username', 'username', '`username`', '`username`', 200, 30, -1, false, '`username`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->_username->Sortable = true; // Allow sort
        $this->_username->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->_username->Param, "CustomMsg");
        $this->Fields['username'] = &$this->_username;

        // fecha
        $this->fecha = new DbField('pedido_online', 'pedido_online', 'x_fecha', 'fecha', '`fecha`', CastDateFieldForLike("`fecha`", 7, "DB"), 135, 19, 7, false, '`fecha`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->fecha->Sortable = true; // Allow sort
        $this->fecha->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_SEPARATOR"], $Language->phrase("IncorrectDateDMY"));
        $this->fecha->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->fecha->Param, "CustomMsg");
        $this->Fields['fecha'] = &$this->fecha;

        // nro_documento
        $this->nro_documento = new DbField('pedido_online', 'pedido_online', 'x_nro_documento', 'nro_documento', '`nro_documento`', '`nro_documento`', 200, 20, -1, false, '`nro_documento`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->nro_documento->Sortable = true; // Allow sort
        $this->nro_documento->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->nro_documento->Param, "CustomMsg");
        $this->Fields['nro_documento'] = &$this->nro_documento;

        // nro_control
        $this->nro_control = new DbField('pedido_online', 'pedido_online', 'x_nro_control', 'nro_control', '`nro_control`', '`nro_control`', 200, 100, -1, false, '`nro_control`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->nro_control->Sortable = true; // Allow sort
        $this->nro_control->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->nro_control->Param, "CustomMsg");
        $this->Fields['nro_control'] = &$this->nro_control;

        // monto_total
        $this->monto_total = new DbField('pedido_online', 'pedido_online', 'x_monto_total', 'monto_total', '`monto_total`', '`monto_total`', 131, 14, -1, false, '`monto_total`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->monto_total->Sortable = true; // Allow sort
        $this->monto_total->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->monto_total->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_total->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->monto_total->Param, "CustomMsg");
        $this->Fields['monto_total'] = &$this->monto_total;

        // alicuota_iva
        $this->alicuota_iva = new DbField('pedido_online', 'pedido_online', 'x_alicuota_iva', 'alicuota_iva', '`alicuota_iva`', '`alicuota_iva`', 131, 14, -1, false, '`alicuota_iva`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->alicuota_iva->Sortable = true; // Allow sort
        $this->alicuota_iva->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->alicuota_iva->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->alicuota_iva->Param, "CustomMsg");
        $this->Fields['alicuota_iva'] = &$this->alicuota_iva;

        // iva
        $this->iva = new DbField('pedido_online', 'pedido_online', 'x_iva', 'iva', '`iva`', '`iva`', 131, 14, -1, false, '`iva`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->iva->Sortable = true; // Allow sort
        $this->iva->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->iva->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->iva->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->iva->Param, "CustomMsg");
        $this->Fields['iva'] = &$this->iva;

        // total
        $this->total = new DbField('pedido_online', 'pedido_online', 'x_total', 'total', '`total`', '`total`', 131, 14, -1, false, '`total`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->total->Sortable = true; // Allow sort
        $this->total->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->total->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->total->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->total->Param, "CustomMsg");
        $this->Fields['total'] = &$this->total;

        // nota
        $this->nota = new DbField('pedido_online', 'pedido_online', 'x_nota', 'nota', '`nota`', '`nota`', 200, 60, -1, false, '`nota`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->nota->Sortable = true; // Allow sort
        $this->nota->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->nota->Param, "CustomMsg");
        $this->Fields['nota'] = &$this->nota;

        // estatus
        $this->estatus = new DbField('pedido_online', 'pedido_online', 'x_estatus', 'estatus', '`estatus`', '`estatus`', 200, 10, -1, false, '`estatus`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->estatus->Sortable = true; // Allow sort
        $this->estatus->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->estatus->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->estatus->Lookup = new Lookup('estatus', 'pedido_online', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->estatus->OptionCount = 2;
        $this->estatus->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->estatus->Param, "CustomMsg");
        $this->Fields['estatus'] = &$this->estatus;

        // id_documento_padre
        $this->id_documento_padre = new DbField('pedido_online', 'pedido_online', 'x_id_documento_padre', 'id_documento_padre', '`id_documento_padre`', '`id_documento_padre`', 19, 10, -1, false, '`id_documento_padre`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->id_documento_padre->Sortable = true; // Allow sort
        $this->id_documento_padre->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id_documento_padre->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->id_documento_padre->Param, "CustomMsg");
        $this->Fields['id_documento_padre'] = &$this->id_documento_padre;

        // moneda
        $this->moneda = new DbField('pedido_online', 'pedido_online', 'x_moneda', 'moneda', '`moneda`', '`moneda`', 200, 6, -1, false, '`moneda`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->moneda->Sortable = true; // Allow sort
        $this->moneda->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->moneda->Param, "CustomMsg");
        $this->Fields['moneda'] = &$this->moneda;

        // documento
        $this->documento = new DbField('pedido_online', 'pedido_online', 'x_documento', 'documento', '`documento`', '`documento`', 200, 2, -1, false, '`documento`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->documento->Sortable = true; // Allow sort
        $this->documento->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->documento->Param, "CustomMsg");
        $this->Fields['documento'] = &$this->documento;

        // tasa_dia
        $this->tasa_dia = new DbField('pedido_online', 'pedido_online', 'x_tasa_dia', 'tasa_dia', '`tasa_dia`', '`tasa_dia`', 131, 14, -1, false, '`tasa_dia`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->tasa_dia->Sortable = true; // Allow sort
        $this->tasa_dia->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->tasa_dia->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tasa_dia->Param, "CustomMsg");
        $this->Fields['tasa_dia'] = &$this->tasa_dia;

        // monto_usd
        $this->monto_usd = new DbField('pedido_online', 'pedido_online', 'x_monto_usd', 'monto_usd', '`monto_usd`', '`monto_usd`', 131, 14, -1, false, '`monto_usd`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->monto_usd->Sortable = true; // Allow sort
        $this->monto_usd->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_usd->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->monto_usd->Param, "CustomMsg");
        $this->Fields['monto_usd'] = &$this->monto_usd;

        // dias_credito
        $this->dias_credito = new DbField('pedido_online', 'pedido_online', 'x_dias_credito', 'dias_credito', '`dias_credito`', '`dias_credito`', 16, 4, -1, false, '`dias_credito`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->dias_credito->Sortable = true; // Allow sort
        $this->dias_credito->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->dias_credito->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->dias_credito->Param, "CustomMsg");
        $this->Fields['dias_credito'] = &$this->dias_credito;

        // entregado
        $this->entregado = new DbField('pedido_online', 'pedido_online', 'x_entregado', 'entregado', '`entregado`', '`entregado`', 202, 1, -1, false, '`entregado`', false, false, false, 'FORMATTED TEXT', 'RADIO');
        $this->entregado->Sortable = true; // Allow sort
        $this->entregado->Lookup = new Lookup('entregado', 'pedido_online', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->entregado->OptionCount = 2;
        $this->entregado->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->entregado->Param, "CustomMsg");
        $this->Fields['entregado'] = &$this->entregado;

        // fecha_entrega
        $this->fecha_entrega = new DbField('pedido_online', 'pedido_online', 'x_fecha_entrega', 'fecha_entrega', '`fecha_entrega`', CastDateFieldForLike("`fecha_entrega`", 0, "DB"), 133, 10, 0, false, '`fecha_entrega`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->fecha_entrega->Sortable = true; // Allow sort
        $this->fecha_entrega->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->fecha_entrega->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->fecha_entrega->Param, "CustomMsg");
        $this->Fields['fecha_entrega'] = &$this->fecha_entrega;

        // pagado
        $this->pagado = new DbField('pedido_online', 'pedido_online', 'x_pagado', 'pagado', '`pagado`', '`pagado`', 202, 1, -1, false, '`pagado`', false, false, false, 'FORMATTED TEXT', 'RADIO');
        $this->pagado->Sortable = true; // Allow sort
        $this->pagado->Lookup = new Lookup('pagado', 'pedido_online', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->pagado->OptionCount = 2;
        $this->pagado->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->pagado->Param, "CustomMsg");
        $this->Fields['pagado'] = &$this->pagado;
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
        if ($this->getCurrentDetailTable() == "pedidio_detalle_online") {
            $detailUrl = Container("pedidio_detalle_online")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue);
            $detailUrl .= "&" . GetForeignKeyUrl("fk_tipo_documento", $this->tipo_documento->CurrentValue);
        }
        if ($detailUrl == "") {
            $detailUrl = "PedidoOnlineList";
        }
        return $detailUrl;
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`pedido_online`";
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
        // Cascade Update detail table 'pedidio_detalle_online'
        $cascadeUpdate = false;
        $rscascade = [];
        if ($rsold && (isset($rs['id']) && $rsold['id'] != $rs['id'])) { // Update detail field 'id_documento'
            $cascadeUpdate = true;
            $rscascade['id_documento'] = $rs['id'];
        }
        if ($rsold && (isset($rs['tipo_documento']) && $rsold['tipo_documento'] != $rs['tipo_documento'])) { // Update detail field 'tipo_documento'
            $cascadeUpdate = true;
            $rscascade['tipo_documento'] = $rs['tipo_documento'];
        }
        if ($cascadeUpdate) {
            $rswrk = Container("pedidio_detalle_online")->loadRs("`id_documento` = " . QuotedValue($rsold['id'], DATATYPE_NUMBER, 'DB') . " AND " . "`tipo_documento` = " . QuotedValue($rsold['tipo_documento'], DATATYPE_STRING, 'DB'))->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rswrk as $rsdtlold) {
                $rskey = [];
                $fldname = 'id';
                $rskey[$fldname] = $rsdtlold[$fldname];
                $rsdtlnew = array_merge($rsdtlold, $rscascade);
                // Call Row_Updating event
                $success = Container("pedidio_detalle_online")->rowUpdating($rsdtlold, $rsdtlnew);
                if ($success) {
                    $success = Container("pedidio_detalle_online")->update($rscascade, $rskey, $rsdtlold);
                }
                if (!$success) {
                    return false;
                }
                // Call Row_Updated event
                Container("pedidio_detalle_online")->rowUpdated($rsdtlold, $rsdtlnew);
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

        // Cascade delete detail table 'pedidio_detalle_online'
        $dtlrows = Container("pedidio_detalle_online")->loadRs("`id_documento` = " . QuotedValue($rs['id'], DATATYPE_NUMBER, "DB") . " AND " . "`tipo_documento` = " . QuotedValue($rs['tipo_documento'], DATATYPE_STRING, "DB"))->fetchAll(\PDO::FETCH_ASSOC);
        // Call Row Deleting event
        foreach ($dtlrows as $dtlrow) {
            $success = Container("pedidio_detalle_online")->rowDeleting($dtlrow);
            if (!$success) {
                break;
            }
        }
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                $success = Container("pedidio_detalle_online")->delete($dtlrow); // Delete
                if (!$success) {
                    break;
                }
            }
        }
        // Call Row Deleted event
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                Container("pedidio_detalle_online")->rowDeleted($dtlrow);
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
        $this->tipo_documento->DbValue = $row['tipo_documento'];
        $this->asesor->DbValue = $row['asesor'];
        $this->cliente->DbValue = $row['cliente'];
        $this->_username->DbValue = $row['username'];
        $this->fecha->DbValue = $row['fecha'];
        $this->nro_documento->DbValue = $row['nro_documento'];
        $this->nro_control->DbValue = $row['nro_control'];
        $this->monto_total->DbValue = $row['monto_total'];
        $this->alicuota_iva->DbValue = $row['alicuota_iva'];
        $this->iva->DbValue = $row['iva'];
        $this->total->DbValue = $row['total'];
        $this->nota->DbValue = $row['nota'];
        $this->estatus->DbValue = $row['estatus'];
        $this->id_documento_padre->DbValue = $row['id_documento_padre'];
        $this->moneda->DbValue = $row['moneda'];
        $this->documento->DbValue = $row['documento'];
        $this->tasa_dia->DbValue = $row['tasa_dia'];
        $this->monto_usd->DbValue = $row['monto_usd'];
        $this->dias_credito->DbValue = $row['dias_credito'];
        $this->entregado->DbValue = $row['entregado'];
        $this->fecha_entrega->DbValue = $row['fecha_entrega'];
        $this->pagado->DbValue = $row['pagado'];
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
        return $_SESSION[$name] ?? GetUrl("PedidoOnlineList");
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
        if ($pageName == "PedidoOnlineView") {
            return $Language->phrase("View");
        } elseif ($pageName == "PedidoOnlineEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "PedidoOnlineAdd") {
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
                return "PedidoOnlineView";
            case Config("API_ADD_ACTION"):
                return "PedidoOnlineAdd";
            case Config("API_EDIT_ACTION"):
                return "PedidoOnlineEdit";
            case Config("API_DELETE_ACTION"):
                return "PedidoOnlineDelete";
            case Config("API_LIST_ACTION"):
                return "PedidoOnlineList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "PedidoOnlineList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("PedidoOnlineView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("PedidoOnlineView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "PedidoOnlineAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "PedidoOnlineAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("PedidoOnlineEdit", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("PedidoOnlineEdit", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
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
            $url = $this->keyUrl("PedidoOnlineAdd", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("PedidoOnlineAdd", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
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
        return $this->keyUrl("PedidoOnlineDelete", $this->getUrlParm());
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
        $this->tipo_documento->setDbValue($row['tipo_documento']);
        $this->asesor->setDbValue($row['asesor']);
        $this->cliente->setDbValue($row['cliente']);
        $this->_username->setDbValue($row['username']);
        $this->fecha->setDbValue($row['fecha']);
        $this->nro_documento->setDbValue($row['nro_documento']);
        $this->nro_control->setDbValue($row['nro_control']);
        $this->monto_total->setDbValue($row['monto_total']);
        $this->alicuota_iva->setDbValue($row['alicuota_iva']);
        $this->iva->setDbValue($row['iva']);
        $this->total->setDbValue($row['total']);
        $this->nota->setDbValue($row['nota']);
        $this->estatus->setDbValue($row['estatus']);
        $this->id_documento_padre->setDbValue($row['id_documento_padre']);
        $this->moneda->setDbValue($row['moneda']);
        $this->documento->setDbValue($row['documento']);
        $this->tasa_dia->setDbValue($row['tasa_dia']);
        $this->monto_usd->setDbValue($row['monto_usd']);
        $this->dias_credito->setDbValue($row['dias_credito']);
        $this->entregado->setDbValue($row['entregado']);
        $this->fecha_entrega->setDbValue($row['fecha_entrega']);
        $this->pagado->setDbValue($row['pagado']);
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

        // asesor

        // cliente

        // username

        // fecha

        // nro_documento

        // nro_control

        // monto_total

        // alicuota_iva

        // iva

        // total

        // nota

        // estatus

        // id_documento_padre

        // moneda

        // documento

        // tasa_dia

        // monto_usd

        // dias_credito

        // entregado

        // fecha_entrega

        // pagado

        // id
        $this->id->ViewValue = $this->id->CurrentValue;
        $this->id->ViewCustomAttributes = "";

        // tipo_documento
        $this->tipo_documento->ViewValue = $this->tipo_documento->CurrentValue;
        $curVal = trim(strval($this->tipo_documento->CurrentValue));
        if ($curVal != "") {
            $this->tipo_documento->ViewValue = $this->tipo_documento->lookupCacheOption($curVal);
            if ($this->tipo_documento->ViewValue === null) { // Lookup from database
                $filterWrk = "`codigo`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                $sqlWrk = $this->tipo_documento->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->tipo_documento->Lookup->renderViewRow($rswrk[0]);
                    $this->tipo_documento->ViewValue = $this->tipo_documento->displayValue($arwrk);
                } else {
                    $this->tipo_documento->ViewValue = $this->tipo_documento->CurrentValue;
                }
            }
        } else {
            $this->tipo_documento->ViewValue = null;
        }
        $this->tipo_documento->ViewCustomAttributes = "";

        // asesor
        $this->asesor->ViewValue = $this->asesor->CurrentValue;
        $curVal = trim(strval($this->asesor->CurrentValue));
        if ($curVal != "") {
            $this->asesor->ViewValue = $this->asesor->lookupCacheOption($curVal);
            if ($this->asesor->ViewValue === null) { // Lookup from database
                $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $sqlWrk = $this->asesor->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->asesor->Lookup->renderViewRow($rswrk[0]);
                    $this->asesor->ViewValue = $this->asesor->displayValue($arwrk);
                } else {
                    $this->asesor->ViewValue = $this->asesor->CurrentValue;
                }
            }
        } else {
            $this->asesor->ViewValue = null;
        }
        $this->asesor->CssClass = "font-weight-bold font-italic";
        $this->asesor->ViewCustomAttributes = "";

        // cliente
        $this->cliente->ViewValue = $this->cliente->CurrentValue;
        $curVal = trim(strval($this->cliente->CurrentValue));
        if ($curVal != "") {
            $this->cliente->ViewValue = $this->cliente->lookupCacheOption($curVal);
            if ($this->cliente->ViewValue === null) { // Lookup from database
                $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $sqlWrk = $this->cliente->Lookup->getSql(false, $filterWrk, '', $this, true, true);
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
        $this->cliente->CssClass = "font-weight-bold font-italic";
        $this->cliente->ViewCustomAttributes = "";

        // username
        $this->_username->ViewValue = $this->_username->CurrentValue;
        $this->_username->ViewCustomAttributes = "";

        // fecha
        $this->fecha->ViewValue = $this->fecha->CurrentValue;
        $this->fecha->ViewValue = FormatDateTime($this->fecha->ViewValue, 7);
        $this->fecha->ViewCustomAttributes = "";

        // nro_documento
        $this->nro_documento->ViewValue = $this->nro_documento->CurrentValue;
        $this->nro_documento->ViewCustomAttributes = "";

        // nro_control
        $this->nro_control->ViewValue = $this->nro_control->CurrentValue;
        $this->nro_control->ViewCustomAttributes = "";

        // monto_total
        $this->monto_total->ViewValue = $this->monto_total->CurrentValue;
        $this->monto_total->ViewValue = FormatNumber($this->monto_total->ViewValue, 2, -1, -1, -1);
        $this->monto_total->ViewCustomAttributes = "";

        // alicuota_iva
        $this->alicuota_iva->ViewValue = $this->alicuota_iva->CurrentValue;
        $this->alicuota_iva->ViewValue = FormatNumber($this->alicuota_iva->ViewValue, $this->alicuota_iva->DefaultDecimalPrecision);
        $this->alicuota_iva->ViewCustomAttributes = "";

        // iva
        $this->iva->ViewValue = $this->iva->CurrentValue;
        $this->iva->ViewValue = FormatNumber($this->iva->ViewValue, 2, -1, -1, -1);
        $this->iva->ViewCustomAttributes = "";

        // total
        $this->total->ViewValue = $this->total->CurrentValue;
        $this->total->ViewValue = FormatNumber($this->total->ViewValue, 2, -1, -1, -1);
        $this->total->ViewCustomAttributes = "";

        // nota
        $this->nota->ViewValue = $this->nota->CurrentValue;
        $this->nota->ViewCustomAttributes = "";

        // estatus
        if (strval($this->estatus->CurrentValue) != "") {
            $this->estatus->ViewValue = $this->estatus->optionCaption($this->estatus->CurrentValue);
        } else {
            $this->estatus->ViewValue = null;
        }
        $this->estatus->ViewCustomAttributes = "";

        // id_documento_padre
        $this->id_documento_padre->ViewValue = $this->id_documento_padre->CurrentValue;
        $this->id_documento_padre->ViewCustomAttributes = "";

        // moneda
        $this->moneda->ViewValue = $this->moneda->CurrentValue;
        $this->moneda->ViewCustomAttributes = "";

        // documento
        $this->documento->ViewValue = $this->documento->CurrentValue;
        $this->documento->ViewCustomAttributes = "";

        // tasa_dia
        $this->tasa_dia->ViewValue = $this->tasa_dia->CurrentValue;
        $this->tasa_dia->ViewValue = FormatNumber($this->tasa_dia->ViewValue, $this->tasa_dia->DefaultDecimalPrecision);
        $this->tasa_dia->ViewCustomAttributes = "";

        // monto_usd
        $this->monto_usd->ViewValue = $this->monto_usd->CurrentValue;
        $this->monto_usd->ViewValue = FormatNumber($this->monto_usd->ViewValue, $this->monto_usd->DefaultDecimalPrecision);
        $this->monto_usd->ViewCustomAttributes = "";

        // dias_credito
        $this->dias_credito->ViewValue = $this->dias_credito->CurrentValue;
        $this->dias_credito->ViewCustomAttributes = "";

        // entregado
        if (strval($this->entregado->CurrentValue) != "") {
            $this->entregado->ViewValue = $this->entregado->optionCaption($this->entregado->CurrentValue);
        } else {
            $this->entregado->ViewValue = null;
        }
        $this->entregado->ViewCustomAttributes = "";

        // fecha_entrega
        $this->fecha_entrega->ViewValue = $this->fecha_entrega->CurrentValue;
        $this->fecha_entrega->ViewValue = FormatDateTime($this->fecha_entrega->ViewValue, 0);
        $this->fecha_entrega->ViewCustomAttributes = "";

        // pagado
        if (strval($this->pagado->CurrentValue) != "") {
            $this->pagado->ViewValue = $this->pagado->optionCaption($this->pagado->CurrentValue);
        } else {
            $this->pagado->ViewValue = null;
        }
        $this->pagado->ViewCustomAttributes = "";

        // id
        $this->id->LinkCustomAttributes = "";
        $this->id->HrefValue = "";
        $this->id->TooltipValue = "";

        // tipo_documento
        $this->tipo_documento->LinkCustomAttributes = "";
        $this->tipo_documento->HrefValue = "";
        $this->tipo_documento->TooltipValue = "";

        // asesor
        $this->asesor->LinkCustomAttributes = "";
        $this->asesor->HrefValue = "";
        $this->asesor->TooltipValue = "";

        // cliente
        $this->cliente->LinkCustomAttributes = "";
        $this->cliente->HrefValue = "";
        $this->cliente->TooltipValue = "";

        // username
        $this->_username->LinkCustomAttributes = "";
        $this->_username->HrefValue = "";
        $this->_username->TooltipValue = "";

        // fecha
        $this->fecha->LinkCustomAttributes = "";
        $this->fecha->HrefValue = "";
        $this->fecha->TooltipValue = "";

        // nro_documento
        $this->nro_documento->LinkCustomAttributes = "";
        $this->nro_documento->HrefValue = "";
        $this->nro_documento->TooltipValue = "";

        // nro_control
        $this->nro_control->LinkCustomAttributes = "";
        $this->nro_control->HrefValue = "";
        $this->nro_control->TooltipValue = "";

        // monto_total
        $this->monto_total->LinkCustomAttributes = "";
        $this->monto_total->HrefValue = "";
        $this->monto_total->TooltipValue = "";

        // alicuota_iva
        $this->alicuota_iva->LinkCustomAttributes = "";
        $this->alicuota_iva->HrefValue = "";
        $this->alicuota_iva->TooltipValue = "";

        // iva
        $this->iva->LinkCustomAttributes = "";
        $this->iva->HrefValue = "";
        $this->iva->TooltipValue = "";

        // total
        $this->total->LinkCustomAttributes = "";
        $this->total->HrefValue = "";
        $this->total->TooltipValue = "";

        // nota
        $this->nota->LinkCustomAttributes = "";
        $this->nota->HrefValue = "";
        $this->nota->TooltipValue = "";

        // estatus
        $this->estatus->LinkCustomAttributes = "";
        $this->estatus->HrefValue = "";
        $this->estatus->TooltipValue = "";

        // id_documento_padre
        $this->id_documento_padre->LinkCustomAttributes = "";
        $this->id_documento_padre->HrefValue = "";
        $this->id_documento_padre->TooltipValue = "";

        // moneda
        $this->moneda->LinkCustomAttributes = "";
        $this->moneda->HrefValue = "";
        $this->moneda->TooltipValue = "";

        // documento
        $this->documento->LinkCustomAttributes = "";
        $this->documento->HrefValue = "";
        $this->documento->TooltipValue = "";

        // tasa_dia
        $this->tasa_dia->LinkCustomAttributes = "";
        $this->tasa_dia->HrefValue = "";
        $this->tasa_dia->TooltipValue = "";

        // monto_usd
        $this->monto_usd->LinkCustomAttributes = "";
        $this->monto_usd->HrefValue = "";
        $this->monto_usd->TooltipValue = "";

        // dias_credito
        $this->dias_credito->LinkCustomAttributes = "";
        $this->dias_credito->HrefValue = "";
        $this->dias_credito->TooltipValue = "";

        // entregado
        $this->entregado->LinkCustomAttributes = "";
        $this->entregado->HrefValue = "";
        $this->entregado->TooltipValue = "";

        // fecha_entrega
        $this->fecha_entrega->LinkCustomAttributes = "";
        $this->fecha_entrega->HrefValue = "";
        $this->fecha_entrega->TooltipValue = "";

        // pagado
        $this->pagado->LinkCustomAttributes = "";
        $this->pagado->HrefValue = "";
        $this->pagado->TooltipValue = "";

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

        // tipo_documento
        $this->tipo_documento->EditAttrs["class"] = "form-control";
        $this->tipo_documento->EditCustomAttributes = "";
        if (!$this->tipo_documento->Raw) {
            $this->tipo_documento->CurrentValue = HtmlDecode($this->tipo_documento->CurrentValue);
        }
        $this->tipo_documento->EditValue = $this->tipo_documento->CurrentValue;
        $this->tipo_documento->PlaceHolder = RemoveHtml($this->tipo_documento->caption());

        // asesor
        $this->asesor->EditAttrs["class"] = "form-control";
        $this->asesor->EditCustomAttributes = "";
        if (!$this->asesor->Raw) {
            $this->asesor->CurrentValue = HtmlDecode($this->asesor->CurrentValue);
        }
        $this->asesor->EditValue = $this->asesor->CurrentValue;
        $this->asesor->PlaceHolder = RemoveHtml($this->asesor->caption());

        // cliente
        $this->cliente->EditAttrs["class"] = "form-control";
        $this->cliente->EditCustomAttributes = "";
        $this->cliente->EditValue = $this->cliente->CurrentValue;
        $this->cliente->PlaceHolder = RemoveHtml($this->cliente->caption());

        // username
        $this->_username->EditAttrs["class"] = "form-control";
        $this->_username->EditCustomAttributes = "";
        if (!$this->_username->Raw) {
            $this->_username->CurrentValue = HtmlDecode($this->_username->CurrentValue);
        }
        $this->_username->EditValue = $this->_username->CurrentValue;
        $this->_username->PlaceHolder = RemoveHtml($this->_username->caption());

        // fecha
        $this->fecha->EditAttrs["class"] = "form-control";
        $this->fecha->EditCustomAttributes = "";
        $this->fecha->EditValue = FormatDateTime($this->fecha->CurrentValue, 7);
        $this->fecha->PlaceHolder = RemoveHtml($this->fecha->caption());

        // nro_documento
        $this->nro_documento->EditAttrs["class"] = "form-control";
        $this->nro_documento->EditCustomAttributes = "";
        if (!$this->nro_documento->Raw) {
            $this->nro_documento->CurrentValue = HtmlDecode($this->nro_documento->CurrentValue);
        }
        $this->nro_documento->EditValue = $this->nro_documento->CurrentValue;
        $this->nro_documento->PlaceHolder = RemoveHtml($this->nro_documento->caption());

        // nro_control
        $this->nro_control->EditAttrs["class"] = "form-control";
        $this->nro_control->EditCustomAttributes = "";
        if (!$this->nro_control->Raw) {
            $this->nro_control->CurrentValue = HtmlDecode($this->nro_control->CurrentValue);
        }
        $this->nro_control->EditValue = $this->nro_control->CurrentValue;
        $this->nro_control->PlaceHolder = RemoveHtml($this->nro_control->caption());

        // monto_total
        $this->monto_total->EditAttrs["class"] = "form-control";
        $this->monto_total->EditCustomAttributes = "";
        $this->monto_total->EditValue = $this->monto_total->CurrentValue;
        $this->monto_total->PlaceHolder = RemoveHtml($this->monto_total->caption());
        if (strval($this->monto_total->EditValue) != "" && is_numeric($this->monto_total->EditValue)) {
            $this->monto_total->EditValue = FormatNumber($this->monto_total->EditValue, -2, -1, -2, -1);
        }

        // alicuota_iva
        $this->alicuota_iva->EditAttrs["class"] = "form-control";
        $this->alicuota_iva->EditCustomAttributes = "";
        $this->alicuota_iva->EditValue = $this->alicuota_iva->CurrentValue;
        $this->alicuota_iva->PlaceHolder = RemoveHtml($this->alicuota_iva->caption());
        if (strval($this->alicuota_iva->EditValue) != "" && is_numeric($this->alicuota_iva->EditValue)) {
            $this->alicuota_iva->EditValue = FormatNumber($this->alicuota_iva->EditValue, -2, -1, -2, 0);
        }

        // iva
        $this->iva->EditAttrs["class"] = "form-control";
        $this->iva->EditCustomAttributes = "";
        $this->iva->EditValue = $this->iva->CurrentValue;
        $this->iva->PlaceHolder = RemoveHtml($this->iva->caption());
        if (strval($this->iva->EditValue) != "" && is_numeric($this->iva->EditValue)) {
            $this->iva->EditValue = FormatNumber($this->iva->EditValue, -2, -1, -2, -1);
        }

        // total
        $this->total->EditAttrs["class"] = "form-control";
        $this->total->EditCustomAttributes = "";
        $this->total->EditValue = $this->total->CurrentValue;
        $this->total->PlaceHolder = RemoveHtml($this->total->caption());
        if (strval($this->total->EditValue) != "" && is_numeric($this->total->EditValue)) {
            $this->total->EditValue = FormatNumber($this->total->EditValue, -2, -1, -2, -1);
        }

        // nota
        $this->nota->EditAttrs["class"] = "form-control";
        $this->nota->EditCustomAttributes = "";
        if (!$this->nota->Raw) {
            $this->nota->CurrentValue = HtmlDecode($this->nota->CurrentValue);
        }
        $this->nota->EditValue = $this->nota->CurrentValue;
        $this->nota->PlaceHolder = RemoveHtml($this->nota->caption());

        // estatus
        $this->estatus->EditAttrs["class"] = "form-control";
        $this->estatus->EditCustomAttributes = "";
        $this->estatus->EditValue = $this->estatus->options(true);
        $this->estatus->PlaceHolder = RemoveHtml($this->estatus->caption());

        // id_documento_padre
        $this->id_documento_padre->EditAttrs["class"] = "form-control";
        $this->id_documento_padre->EditCustomAttributes = "";
        $this->id_documento_padre->EditValue = $this->id_documento_padre->CurrentValue;
        $this->id_documento_padre->PlaceHolder = RemoveHtml($this->id_documento_padre->caption());

        // moneda
        $this->moneda->EditAttrs["class"] = "form-control";
        $this->moneda->EditCustomAttributes = "";
        if (!$this->moneda->Raw) {
            $this->moneda->CurrentValue = HtmlDecode($this->moneda->CurrentValue);
        }
        $this->moneda->EditValue = $this->moneda->CurrentValue;
        $this->moneda->PlaceHolder = RemoveHtml($this->moneda->caption());

        // documento
        $this->documento->EditAttrs["class"] = "form-control";
        $this->documento->EditCustomAttributes = "";
        if (!$this->documento->Raw) {
            $this->documento->CurrentValue = HtmlDecode($this->documento->CurrentValue);
        }
        $this->documento->EditValue = $this->documento->CurrentValue;
        $this->documento->PlaceHolder = RemoveHtml($this->documento->caption());

        // tasa_dia
        $this->tasa_dia->EditAttrs["class"] = "form-control";
        $this->tasa_dia->EditCustomAttributes = "";
        $this->tasa_dia->EditValue = $this->tasa_dia->CurrentValue;
        $this->tasa_dia->PlaceHolder = RemoveHtml($this->tasa_dia->caption());
        if (strval($this->tasa_dia->EditValue) != "" && is_numeric($this->tasa_dia->EditValue)) {
            $this->tasa_dia->EditValue = FormatNumber($this->tasa_dia->EditValue, -2, -1, -2, 0);
        }

        // monto_usd
        $this->monto_usd->EditAttrs["class"] = "form-control";
        $this->monto_usd->EditCustomAttributes = "";
        $this->monto_usd->EditValue = $this->monto_usd->CurrentValue;
        $this->monto_usd->PlaceHolder = RemoveHtml($this->monto_usd->caption());
        if (strval($this->monto_usd->EditValue) != "" && is_numeric($this->monto_usd->EditValue)) {
            $this->monto_usd->EditValue = FormatNumber($this->monto_usd->EditValue, -2, -1, -2, 0);
        }

        // dias_credito
        $this->dias_credito->EditAttrs["class"] = "form-control";
        $this->dias_credito->EditCustomAttributes = "";
        $this->dias_credito->EditValue = $this->dias_credito->CurrentValue;
        $this->dias_credito->PlaceHolder = RemoveHtml($this->dias_credito->caption());

        // entregado
        $this->entregado->EditCustomAttributes = "";
        $this->entregado->EditValue = $this->entregado->options(false);
        $this->entregado->PlaceHolder = RemoveHtml($this->entregado->caption());

        // fecha_entrega
        $this->fecha_entrega->EditAttrs["class"] = "form-control";
        $this->fecha_entrega->EditCustomAttributes = "";
        $this->fecha_entrega->EditValue = FormatDateTime($this->fecha_entrega->CurrentValue, 8);
        $this->fecha_entrega->PlaceHolder = RemoveHtml($this->fecha_entrega->caption());

        // pagado
        $this->pagado->EditCustomAttributes = "";
        $this->pagado->EditValue = $this->pagado->options(false);
        $this->pagado->PlaceHolder = RemoveHtml($this->pagado->caption());

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
                    $doc->exportCaption($this->asesor);
                    $doc->exportCaption($this->cliente);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->monto_total);
                    $doc->exportCaption($this->iva);
                    $doc->exportCaption($this->total);
                    $doc->exportCaption($this->nota);
                    $doc->exportCaption($this->estatus);
                } else {
                    $doc->exportCaption($this->id);
                    $doc->exportCaption($this->tipo_documento);
                    $doc->exportCaption($this->asesor);
                    $doc->exportCaption($this->cliente);
                    $doc->exportCaption($this->_username);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->nro_documento);
                    $doc->exportCaption($this->nro_control);
                    $doc->exportCaption($this->monto_total);
                    $doc->exportCaption($this->alicuota_iva);
                    $doc->exportCaption($this->iva);
                    $doc->exportCaption($this->total);
                    $doc->exportCaption($this->nota);
                    $doc->exportCaption($this->estatus);
                    $doc->exportCaption($this->id_documento_padre);
                    $doc->exportCaption($this->moneda);
                    $doc->exportCaption($this->documento);
                    $doc->exportCaption($this->tasa_dia);
                    $doc->exportCaption($this->monto_usd);
                    $doc->exportCaption($this->dias_credito);
                    $doc->exportCaption($this->entregado);
                    $doc->exportCaption($this->fecha_entrega);
                    $doc->exportCaption($this->pagado);
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
                        $doc->exportField($this->asesor);
                        $doc->exportField($this->cliente);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->monto_total);
                        $doc->exportField($this->iva);
                        $doc->exportField($this->total);
                        $doc->exportField($this->nota);
                        $doc->exportField($this->estatus);
                    } else {
                        $doc->exportField($this->id);
                        $doc->exportField($this->tipo_documento);
                        $doc->exportField($this->asesor);
                        $doc->exportField($this->cliente);
                        $doc->exportField($this->_username);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->nro_documento);
                        $doc->exportField($this->nro_control);
                        $doc->exportField($this->monto_total);
                        $doc->exportField($this->alicuota_iva);
                        $doc->exportField($this->iva);
                        $doc->exportField($this->total);
                        $doc->exportField($this->nota);
                        $doc->exportField($this->estatus);
                        $doc->exportField($this->id_documento_padre);
                        $doc->exportField($this->moneda);
                        $doc->exportField($this->documento);
                        $doc->exportField($this->tasa_dia);
                        $doc->exportField($this->monto_usd);
                        $doc->exportField($this->dias_credito);
                        $doc->exportField($this->entregado);
                        $doc->exportField($this->fecha_entrega);
                        $doc->exportField($this->pagado);
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
        $table = 'pedido_online';
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
        $table = 'pedido_online';

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
        $table = 'pedido_online';

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
        $table = 'pedido_online';

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
    public function recordsetSelecting(&$filter) {
    	// Enter your code here	
    	$sql = "SELECT tipo_acceso FROM userlevels
    			WHERE userlevelid = '" . CurrentUserLevel() . "';"; 
    	$grupo = trim(ExecuteScalar($sql));
    	if($grupo == "CLIENTE") {
    		$sql = "SELECT asesor, cliente FROM usuario
    				WHERE username = '" . CurrentUserName() . "';";
    		$row = ExecuteRow($sql);
    		$asesor = intval($row["asesor"]);
    		$cliente = intval($row["cliente"]);
    		if($asesor > 0) {
    			$sql = "SELECT COUNT(cliente) AS cantidad FROM asesor_cliente
    					WHERE asesor = '$asesor';";
    			$cantidad = ExecuteScalar($sql);
    			$clientes = "";
    			for($i=0; $i<$cantidad; $i++) {
    				$sql = "SELECT cliente FROM asesor_cliente
    					WHERE asesor = '$asesor' LIMIT $i, 1;";
    				$clientes .= ExecuteScalar($sql) . ",";
    			}
    			$clientes .= "0"; 
    			AddFilter($filter, "cliente IN ($clientes)");
    		}
    		else if($cliente > 0) {
    			AddFilter($filter, "cliente = '$cliente'");
    		}
    	}

    	/*if(!isset($_REQUEST["tipo"]) or trim($_REQUEST["tipo"]) == "") {
    		$sql = "SELECT tipo_documento FROM username_tipo_documento WHERE username = '" . CurrentUserName() . "';";
    		$tipo = ExecuteScalar($sql);
    	}
    	else $tipo = $_REQUEST["tipo"];
    	AddFilter($filter, "tipo_documento = '$tipo'");*/
    	AddFilter($filter, "tipo_documento = 'TDCPDV'");	

    	/*$sql = "DELETE FROM username_tipo_documento WHERE username = '" . CurrentUserName() . "';";
    	Execute($sql);
    	$sql = "INSERT INTO username_tipo_documento
    				(id, username, tipo_documento)
    			VALUES (NULL, '" . CurrentUserName() . "', '$tipo');";
    	Execute($sql);
    	if(trim($tipo) == "") {
    		header("Location: error_page.php");
    		die();
    	}*/
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
