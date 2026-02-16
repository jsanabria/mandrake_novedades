<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for recarga2
 */
class Recarga2 extends DbTable
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
    public $cliente;
    public $fecha;
    public $metodo_pago;
    public $referencia;
    public $reverso;
    public $monto_moneda;
    public $moneda;
    public $tasa_moneda;
    public $monto_bs;
    public $tasa_usd;
    public $monto_usd;
    public $saldo;
    public $nota;
    public $_username;
    public $cobro_cliente_reverso;
    public $nro_recibo;
    public $nota_recepcion;
    public $abono;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'recarga2';
        $this->TableName = 'recarga2';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`recarga2`";
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
        $this->id = new DbField('recarga2', 'recarga2', 'x_id', 'id', '`id`', '`id`', 21, 20, -1, false, '`id`', false, false, false, 'FORMATTED TEXT', 'NO');
        $this->id->IsAutoIncrement = true; // Autoincrement field
        $this->id->IsPrimaryKey = true; // Primary key field
        $this->id->Sortable = true; // Allow sort
        $this->id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->id->Param, "CustomMsg");
        $this->Fields['id'] = &$this->id;

        // cliente
        $this->cliente = new DbField('recarga2', 'recarga2', 'x_cliente', 'cliente', '`cliente`', '`cliente`', 19, 10, -1, false, '`cliente`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->cliente->Required = true; // Required field
        $this->cliente->Sortable = true; // Allow sort
        $this->cliente->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->cliente->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->cliente->Lookup = new Lookup('cliente', 'cliente', false, 'id', ["nombre","ci_rif","codigo",""], [], [], [], [], [], [], '`nombre`', '');
        $this->cliente->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->cliente->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->cliente->Param, "CustomMsg");
        $this->Fields['cliente'] = &$this->cliente;

        // fecha
        $this->fecha = new DbField('recarga2', 'recarga2', 'x_fecha', 'fecha', '`fecha`', CastDateFieldForLike("`fecha`", 7, "DB"), 133, 10, 7, false, '`fecha`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->fecha->Required = true; // Required field
        $this->fecha->Sortable = true; // Allow sort
        $this->fecha->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_SEPARATOR"], $Language->phrase("IncorrectDateDMY"));
        $this->fecha->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->fecha->Param, "CustomMsg");
        $this->Fields['fecha'] = &$this->fecha;

        // metodo_pago
        $this->metodo_pago = new DbField('recarga2', 'recarga2', 'x_metodo_pago', 'metodo_pago', '`metodo_pago`', '`metodo_pago`', 200, 10, -1, false, '`metodo_pago`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->metodo_pago->Required = true; // Required field
        $this->metodo_pago->Sortable = true; // Allow sort
        $this->metodo_pago->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->metodo_pago->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->metodo_pago->Lookup = new Lookup('metodo_pago', 'parametro', false, 'valor1', ["valor2","","",""], [], [], [], [], [], [], '`valor2` DESC', '');
        $this->metodo_pago->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->metodo_pago->Param, "CustomMsg");
        $this->Fields['metodo_pago'] = &$this->metodo_pago;

        // referencia
        $this->referencia = new DbField('recarga2', 'recarga2', 'x_referencia', 'referencia', '`referencia`', '`referencia`', 200, 20, -1, false, '`referencia`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->referencia->Sortable = true; // Allow sort
        $this->referencia->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->referencia->Param, "CustomMsg");
        $this->Fields['referencia'] = &$this->referencia;

        // reverso
        $this->reverso = new DbField('recarga2', 'recarga2', 'x_reverso', 'reverso', '`reverso`', '`reverso`', 202, 1, -1, false, '`reverso`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->reverso->Required = true; // Required field
        $this->reverso->Sortable = true; // Allow sort
        $this->reverso->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->reverso->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->reverso->Lookup = new Lookup('reverso', 'recarga2', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->reverso->OptionCount = 2;
        $this->reverso->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->reverso->Param, "CustomMsg");
        $this->Fields['reverso'] = &$this->reverso;

        // monto_moneda
        $this->monto_moneda = new DbField('recarga2', 'recarga2', 'x_monto_moneda', 'monto_moneda', '`monto_moneda`', '`monto_moneda`', 131, 14, -1, false, '`monto_moneda`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->monto_moneda->Required = true; // Required field
        $this->monto_moneda->Sortable = true; // Allow sort
        $this->monto_moneda->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->monto_moneda->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_moneda->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->monto_moneda->Param, "CustomMsg");
        $this->Fields['monto_moneda'] = &$this->monto_moneda;

        // moneda
        $this->moneda = new DbField('recarga2', 'recarga2', 'x_moneda', 'moneda', '`moneda`', '`moneda`', 200, 6, -1, false, '`moneda`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->moneda->Required = true; // Required field
        $this->moneda->Sortable = true; // Allow sort
        $this->moneda->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->moneda->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->moneda->Lookup = new Lookup('moneda', 'parametro', false, 'valor1', ["valor1","","",""], [], [], [], [], [], [], '`valor1`', '');
        $this->moneda->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->moneda->Param, "CustomMsg");
        $this->Fields['moneda'] = &$this->moneda;

        // tasa_moneda
        $this->tasa_moneda = new DbField('recarga2', 'recarga2', 'x_tasa_moneda', 'tasa_moneda', '`tasa_moneda`', '`tasa_moneda`', 131, 14, -1, false, '`tasa_moneda`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->tasa_moneda->Sortable = true; // Allow sort
        $this->tasa_moneda->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->tasa_moneda->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->tasa_moneda->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tasa_moneda->Param, "CustomMsg");
        $this->Fields['tasa_moneda'] = &$this->tasa_moneda;

        // monto_bs
        $this->monto_bs = new DbField('recarga2', 'recarga2', 'x_monto_bs', 'monto_bs', '`monto_bs`', '`monto_bs`', 131, 14, -1, false, '`monto_bs`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->monto_bs->Sortable = true; // Allow sort
        $this->monto_bs->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->monto_bs->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_bs->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->monto_bs->Param, "CustomMsg");
        $this->Fields['monto_bs'] = &$this->monto_bs;

        // tasa_usd
        $this->tasa_usd = new DbField('recarga2', 'recarga2', 'x_tasa_usd', 'tasa_usd', '`tasa_usd`', '`tasa_usd`', 131, 14, -1, false, '`tasa_usd`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->tasa_usd->Sortable = true; // Allow sort
        $this->tasa_usd->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->tasa_usd->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->tasa_usd->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tasa_usd->Param, "CustomMsg");
        $this->Fields['tasa_usd'] = &$this->tasa_usd;

        // monto_usd
        $this->monto_usd = new DbField('recarga2', 'recarga2', 'x_monto_usd', 'monto_usd', '`monto_usd`', '`monto_usd`', 131, 14, -1, false, '`monto_usd`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->monto_usd->Required = true; // Required field
        $this->monto_usd->Sortable = true; // Allow sort
        $this->monto_usd->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->monto_usd->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_usd->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->monto_usd->Param, "CustomMsg");
        $this->Fields['monto_usd'] = &$this->monto_usd;

        // saldo
        $this->saldo = new DbField('recarga2', 'recarga2', 'x_saldo', 'saldo', '`saldo`', '`saldo`', 131, 14, -1, false, '`saldo`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->saldo->Sortable = true; // Allow sort
        $this->saldo->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->saldo->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->saldo->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->saldo->Param, "CustomMsg");
        $this->Fields['saldo'] = &$this->saldo;

        // nota
        $this->nota = new DbField('recarga2', 'recarga2', 'x_nota', 'nota', '`nota`', '`nota`', 200, 250, -1, false, '`nota`', false, false, false, 'FORMATTED TEXT', 'TEXTAREA');
        $this->nota->Sortable = true; // Allow sort
        $this->nota->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->nota->Param, "CustomMsg");
        $this->Fields['nota'] = &$this->nota;

        // username
        $this->_username = new DbField('recarga2', 'recarga2', 'x__username', 'username', '`username`', '`username`', 200, 30, -1, false, '`username`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->_username->Required = true; // Required field
        $this->_username->Sortable = true; // Allow sort
        $this->_username->Lookup = new Lookup('username', 'usuario', false, 'username', ["nombre","","",""], [], [], [], [], [], [], '', '');
        $this->_username->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->_username->Param, "CustomMsg");
        $this->Fields['username'] = &$this->_username;

        // cobro_cliente_reverso
        $this->cobro_cliente_reverso = new DbField('recarga2', 'recarga2', 'x_cobro_cliente_reverso', 'cobro_cliente_reverso', '`cobro_cliente_reverso`', '`cobro_cliente_reverso`', 19, 10, -1, false, '`cobro_cliente_reverso`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->cobro_cliente_reverso->Nullable = false; // NOT NULL field
        $this->cobro_cliente_reverso->Sortable = true; // Allow sort
        $this->cobro_cliente_reverso->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->cobro_cliente_reverso->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->cobro_cliente_reverso->Param, "CustomMsg");
        $this->Fields['cobro_cliente_reverso'] = &$this->cobro_cliente_reverso;

        // nro_recibo
        $this->nro_recibo = new DbField('recarga2', 'recarga2', 'x_nro_recibo', 'nro_recibo', '`nro_recibo`', '`nro_recibo`', 19, 10, -1, false, '`nro_recibo`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->nro_recibo->Nullable = false; // NOT NULL field
        $this->nro_recibo->Sortable = true; // Allow sort
        $this->nro_recibo->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->nro_recibo->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->nro_recibo->Param, "CustomMsg");
        $this->Fields['nro_recibo'] = &$this->nro_recibo;

        // nota_recepcion
        $this->nota_recepcion = new DbField('recarga2', 'recarga2', 'x_nota_recepcion', 'nota_recepcion', '`nota_recepcion`', '`nota_recepcion`', 19, 10, -1, false, '`nota_recepcion`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->nota_recepcion->Sortable = true; // Allow sort
        $this->nota_recepcion->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->nota_recepcion->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->nota_recepcion->Param, "CustomMsg");
        $this->Fields['nota_recepcion'] = &$this->nota_recepcion;

        // abono
        $this->abono = new DbField('recarga2', 'recarga2', 'x_abono', 'abono', '`abono`', '`abono`', 21, 20, -1, false, '`abono`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->abono->IsForeignKey = true; // Foreign key field
        $this->abono->Nullable = false; // NOT NULL field
        $this->abono->Sortable = true; // Allow sort
        $this->abono->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->abono->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->abono->Param, "CustomMsg");
        $this->Fields['abono'] = &$this->abono;
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
        if ($this->getCurrentMasterTable() == "abono2") {
            if ($this->abono->getSessionValue() != "") {
                $masterFilter .= "" . GetForeignKeySql("`id`", $this->abono->getSessionValue(), DATATYPE_NUMBER, "DB");
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
        if ($this->getCurrentMasterTable() == "abono2") {
            if ($this->abono->getSessionValue() != "") {
                $detailFilter .= "" . GetForeignKeySql("`abono`", $this->abono->getSessionValue(), DATATYPE_NUMBER, "DB");
            } else {
                return "";
            }
        }
        return $detailFilter;
    }

    // Master filter
    public function sqlMasterFilter_abono2()
    {
        return "`id`=@id@";
    }
    // Detail filter
    public function sqlDetailFilter_abono2()
    {
        return "`abono`=@abono@";
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`recarga2`";
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
        $this->cliente->DbValue = $row['cliente'];
        $this->fecha->DbValue = $row['fecha'];
        $this->metodo_pago->DbValue = $row['metodo_pago'];
        $this->referencia->DbValue = $row['referencia'];
        $this->reverso->DbValue = $row['reverso'];
        $this->monto_moneda->DbValue = $row['monto_moneda'];
        $this->moneda->DbValue = $row['moneda'];
        $this->tasa_moneda->DbValue = $row['tasa_moneda'];
        $this->monto_bs->DbValue = $row['monto_bs'];
        $this->tasa_usd->DbValue = $row['tasa_usd'];
        $this->monto_usd->DbValue = $row['monto_usd'];
        $this->saldo->DbValue = $row['saldo'];
        $this->nota->DbValue = $row['nota'];
        $this->_username->DbValue = $row['username'];
        $this->cobro_cliente_reverso->DbValue = $row['cobro_cliente_reverso'];
        $this->nro_recibo->DbValue = $row['nro_recibo'];
        $this->nota_recepcion->DbValue = $row['nota_recepcion'];
        $this->abono->DbValue = $row['abono'];
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
        return $_SESSION[$name] ?? GetUrl("Recarga2List");
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
        if ($pageName == "Recarga2View") {
            return $Language->phrase("View");
        } elseif ($pageName == "Recarga2Edit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "Recarga2Add") {
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
                return "Recarga2View";
            case Config("API_ADD_ACTION"):
                return "Recarga2Add";
            case Config("API_EDIT_ACTION"):
                return "Recarga2Edit";
            case Config("API_DELETE_ACTION"):
                return "Recarga2Delete";
            case Config("API_LIST_ACTION"):
                return "Recarga2List";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "Recarga2List";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("Recarga2View", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("Recarga2View", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "Recarga2Add?" . $this->getUrlParm($parm);
        } else {
            $url = "Recarga2Add";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("Recarga2Edit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("Recarga2Add", $this->getUrlParm($parm));
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
        return $this->keyUrl("Recarga2Delete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        if ($this->getCurrentMasterTable() == "abono2" && !ContainsString($url, Config("TABLE_SHOW_MASTER") . "=")) {
            $url .= (ContainsString($url, "?") ? "&" : "?") . Config("TABLE_SHOW_MASTER") . "=" . $this->getCurrentMasterTable();
            $url .= "&" . GetForeignKeyUrl("fk_id", $this->abono->CurrentValue ?? $this->abono->getSessionValue());
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
        $jsSort = "";
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
        return "";
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
        $this->cliente->setDbValue($row['cliente']);
        $this->fecha->setDbValue($row['fecha']);
        $this->metodo_pago->setDbValue($row['metodo_pago']);
        $this->referencia->setDbValue($row['referencia']);
        $this->reverso->setDbValue($row['reverso']);
        $this->monto_moneda->setDbValue($row['monto_moneda']);
        $this->moneda->setDbValue($row['moneda']);
        $this->tasa_moneda->setDbValue($row['tasa_moneda']);
        $this->monto_bs->setDbValue($row['monto_bs']);
        $this->tasa_usd->setDbValue($row['tasa_usd']);
        $this->monto_usd->setDbValue($row['monto_usd']);
        $this->saldo->setDbValue($row['saldo']);
        $this->nota->setDbValue($row['nota']);
        $this->_username->setDbValue($row['username']);
        $this->cobro_cliente_reverso->setDbValue($row['cobro_cliente_reverso']);
        $this->nro_recibo->setDbValue($row['nro_recibo']);
        $this->nota_recepcion->setDbValue($row['nota_recepcion']);
        $this->abono->setDbValue($row['abono']);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // id

        // cliente

        // fecha

        // metodo_pago

        // referencia

        // reverso

        // monto_moneda

        // moneda

        // tasa_moneda

        // monto_bs

        // tasa_usd

        // monto_usd

        // saldo

        // nota

        // username

        // cobro_cliente_reverso

        // nro_recibo

        // nota_recepcion

        // abono

        // id
        $this->id->ViewValue = $this->id->CurrentValue;
        $this->id->ViewCustomAttributes = "";

        // cliente
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
        $this->cliente->ViewCustomAttributes = "";

        // fecha
        $this->fecha->ViewValue = $this->fecha->CurrentValue;
        $this->fecha->ViewValue = FormatDateTime($this->fecha->ViewValue, 7);
        $this->fecha->ViewCustomAttributes = "";

        // metodo_pago
        $curVal = trim(strval($this->metodo_pago->CurrentValue));
        if ($curVal != "") {
            $this->metodo_pago->ViewValue = $this->metodo_pago->lookupCacheOption($curVal);
            if ($this->metodo_pago->ViewValue === null) { // Lookup from database
                $filterWrk = "`valor1`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                $lookupFilter = function() {
                    return CurrentPageID() == "add" ? "`codigo` = '009' AND valor1 <> 'RC'" : "`codigo` = '009'";
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->metodo_pago->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->metodo_pago->Lookup->renderViewRow($rswrk[0]);
                    $this->metodo_pago->ViewValue = $this->metodo_pago->displayValue($arwrk);
                } else {
                    $this->metodo_pago->ViewValue = $this->metodo_pago->CurrentValue;
                }
            }
        } else {
            $this->metodo_pago->ViewValue = null;
        }
        $this->metodo_pago->ViewCustomAttributes = "";

        // referencia
        $this->referencia->ViewValue = $this->referencia->CurrentValue;
        $this->referencia->ViewCustomAttributes = "";

        // reverso
        if (strval($this->reverso->CurrentValue) != "") {
            $this->reverso->ViewValue = $this->reverso->optionCaption($this->reverso->CurrentValue);
        } else {
            $this->reverso->ViewValue = null;
        }
        $this->reverso->ViewCustomAttributes = "";

        // monto_moneda
        $this->monto_moneda->ViewValue = $this->monto_moneda->CurrentValue;
        $this->monto_moneda->ViewValue = FormatNumber($this->monto_moneda->ViewValue, 2, -1, -2, -1);
        $this->monto_moneda->ViewCustomAttributes = "";

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

        // tasa_moneda
        $this->tasa_moneda->ViewValue = $this->tasa_moneda->CurrentValue;
        $this->tasa_moneda->ViewValue = FormatNumber($this->tasa_moneda->ViewValue, 2, -2, -2, -2);
        $this->tasa_moneda->ViewCustomAttributes = "";

        // monto_bs
        $this->monto_bs->ViewValue = $this->monto_bs->CurrentValue;
        $this->monto_bs->ViewValue = FormatNumber($this->monto_bs->ViewValue, 2, -2, -2, -2);
        $this->monto_bs->ViewCustomAttributes = "";

        // tasa_usd
        $this->tasa_usd->ViewValue = $this->tasa_usd->CurrentValue;
        $this->tasa_usd->ViewValue = FormatNumber($this->tasa_usd->ViewValue, 2, -2, -2, -2);
        $this->tasa_usd->ViewCustomAttributes = "";

        // monto_usd
        $this->monto_usd->ViewValue = $this->monto_usd->CurrentValue;
        $this->monto_usd->ViewValue = FormatNumber($this->monto_usd->ViewValue, 2, -1, -2, -1);
        $this->monto_usd->CssClass = "font-weight-bold";
        $this->monto_usd->ViewCustomAttributes = "";

        // saldo
        $this->saldo->ViewValue = $this->saldo->CurrentValue;
        $this->saldo->ViewValue = FormatNumber($this->saldo->ViewValue, 2, -1, -1, -1);
        $this->saldo->CssClass = "font-weight-bold font-italic";
        $this->saldo->ViewCustomAttributes = "";

        // nota
        $this->nota->ViewValue = $this->nota->CurrentValue;
        $this->nota->ViewCustomAttributes = "";

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

        // cobro_cliente_reverso
        $this->cobro_cliente_reverso->ViewValue = $this->cobro_cliente_reverso->CurrentValue;
        $this->cobro_cliente_reverso->ViewValue = FormatNumber($this->cobro_cliente_reverso->ViewValue, 0, -2, -2, -2);
        $this->cobro_cliente_reverso->ViewCustomAttributes = "";

        // nro_recibo
        $this->nro_recibo->ViewValue = $this->nro_recibo->CurrentValue;
        $this->nro_recibo->ViewValue = FormatNumber($this->nro_recibo->ViewValue, 0, -2, -2, -2);
        $this->nro_recibo->ViewCustomAttributes = "";

        // nota_recepcion
        $this->nota_recepcion->ViewValue = $this->nota_recepcion->CurrentValue;
        $this->nota_recepcion->ViewValue = FormatNumber($this->nota_recepcion->ViewValue, 0, -2, -2, -2);
        $this->nota_recepcion->ViewCustomAttributes = "";

        // abono
        $this->abono->ViewValue = $this->abono->CurrentValue;
        $this->abono->ViewValue = FormatNumber($this->abono->ViewValue, 0, -2, -2, -2);
        $this->abono->ViewCustomAttributes = "";

        // id
        $this->id->LinkCustomAttributes = "";
        $this->id->HrefValue = "";
        $this->id->TooltipValue = "";

        // cliente
        $this->cliente->LinkCustomAttributes = "";
        $this->cliente->HrefValue = "";
        $this->cliente->TooltipValue = "";

        // fecha
        $this->fecha->LinkCustomAttributes = "";
        $this->fecha->HrefValue = "";
        $this->fecha->TooltipValue = "";

        // metodo_pago
        $this->metodo_pago->LinkCustomAttributes = "";
        $this->metodo_pago->HrefValue = "";
        $this->metodo_pago->TooltipValue = "";

        // referencia
        $this->referencia->LinkCustomAttributes = "";
        $this->referencia->HrefValue = "";
        $this->referencia->TooltipValue = "";

        // reverso
        $this->reverso->LinkCustomAttributes = "";
        $this->reverso->HrefValue = "";
        $this->reverso->TooltipValue = "";

        // monto_moneda
        $this->monto_moneda->LinkCustomAttributes = "";
        $this->monto_moneda->HrefValue = "";
        $this->monto_moneda->TooltipValue = "";

        // moneda
        $this->moneda->LinkCustomAttributes = "";
        $this->moneda->HrefValue = "";
        $this->moneda->TooltipValue = "";

        // tasa_moneda
        $this->tasa_moneda->LinkCustomAttributes = "";
        $this->tasa_moneda->HrefValue = "";
        $this->tasa_moneda->TooltipValue = "";

        // monto_bs
        $this->monto_bs->LinkCustomAttributes = "";
        $this->monto_bs->HrefValue = "";
        $this->monto_bs->TooltipValue = "";

        // tasa_usd
        $this->tasa_usd->LinkCustomAttributes = "";
        $this->tasa_usd->HrefValue = "";
        $this->tasa_usd->TooltipValue = "";

        // monto_usd
        $this->monto_usd->LinkCustomAttributes = "";
        $this->monto_usd->HrefValue = "";
        $this->monto_usd->TooltipValue = "";

        // saldo
        $this->saldo->LinkCustomAttributes = "";
        $this->saldo->HrefValue = "";
        $this->saldo->TooltipValue = "";

        // nota
        $this->nota->LinkCustomAttributes = "";
        $this->nota->HrefValue = "";
        $this->nota->TooltipValue = "";

        // username
        $this->_username->LinkCustomAttributes = "";
        $this->_username->HrefValue = "";
        $this->_username->TooltipValue = "";

        // cobro_cliente_reverso
        $this->cobro_cliente_reverso->LinkCustomAttributes = "";
        $this->cobro_cliente_reverso->HrefValue = "";
        $this->cobro_cliente_reverso->TooltipValue = "";

        // nro_recibo
        $this->nro_recibo->LinkCustomAttributes = "";
        $this->nro_recibo->HrefValue = "";
        $this->nro_recibo->TooltipValue = "";

        // nota_recepcion
        $this->nota_recepcion->LinkCustomAttributes = "";
        $this->nota_recepcion->HrefValue = "";
        $this->nota_recepcion->TooltipValue = "";

        // abono
        $this->abono->LinkCustomAttributes = "";
        $this->abono->HrefValue = "";
        $this->abono->TooltipValue = "";

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

        // cliente
        $this->cliente->EditAttrs["class"] = "form-control";
        $this->cliente->EditCustomAttributes = "";
        $this->cliente->PlaceHolder = RemoveHtml($this->cliente->caption());

        // fecha
        $this->fecha->EditAttrs["class"] = "form-control";
        $this->fecha->EditCustomAttributes = "";
        $this->fecha->EditValue = $this->fecha->CurrentValue;
        $this->fecha->EditValue = FormatDateTime($this->fecha->EditValue, 7);
        $this->fecha->ViewCustomAttributes = "";

        // metodo_pago
        $this->metodo_pago->EditAttrs["class"] = "form-control";
        $this->metodo_pago->EditCustomAttributes = "";
        $this->metodo_pago->PlaceHolder = RemoveHtml($this->metodo_pago->caption());

        // referencia
        $this->referencia->EditAttrs["class"] = "form-control";
        $this->referencia->EditCustomAttributes = "";
        if (!$this->referencia->Raw) {
            $this->referencia->CurrentValue = HtmlDecode($this->referencia->CurrentValue);
        }
        $this->referencia->EditValue = $this->referencia->CurrentValue;
        $this->referencia->PlaceHolder = RemoveHtml($this->referencia->caption());

        // reverso
        $this->reverso->EditAttrs["class"] = "form-control";
        $this->reverso->EditCustomAttributes = "";
        $this->reverso->EditValue = $this->reverso->options(true);
        $this->reverso->PlaceHolder = RemoveHtml($this->reverso->caption());

        // monto_moneda
        $this->monto_moneda->EditAttrs["class"] = "form-control";
        $this->monto_moneda->EditCustomAttributes = "";
        $this->monto_moneda->EditValue = $this->monto_moneda->CurrentValue;
        $this->monto_moneda->PlaceHolder = RemoveHtml($this->monto_moneda->caption());
        if (strval($this->monto_moneda->EditValue) != "" && is_numeric($this->monto_moneda->EditValue)) {
            $this->monto_moneda->EditValue = FormatNumber($this->monto_moneda->EditValue, -2, -1, -2, -1);
        }

        // moneda
        $this->moneda->EditAttrs["class"] = "form-control";
        $this->moneda->EditCustomAttributes = "";
        $this->moneda->PlaceHolder = RemoveHtml($this->moneda->caption());

        // tasa_moneda
        $this->tasa_moneda->EditAttrs["class"] = "form-control";
        $this->tasa_moneda->EditCustomAttributes = "";
        $this->tasa_moneda->EditValue = $this->tasa_moneda->CurrentValue;
        $this->tasa_moneda->PlaceHolder = RemoveHtml($this->tasa_moneda->caption());
        if (strval($this->tasa_moneda->EditValue) != "" && is_numeric($this->tasa_moneda->EditValue)) {
            $this->tasa_moneda->EditValue = FormatNumber($this->tasa_moneda->EditValue, -2, -2, -2, -2);
        }

        // monto_bs
        $this->monto_bs->EditAttrs["class"] = "form-control";
        $this->monto_bs->EditCustomAttributes = "";
        $this->monto_bs->EditValue = $this->monto_bs->CurrentValue;
        $this->monto_bs->PlaceHolder = RemoveHtml($this->monto_bs->caption());
        if (strval($this->monto_bs->EditValue) != "" && is_numeric($this->monto_bs->EditValue)) {
            $this->monto_bs->EditValue = FormatNumber($this->monto_bs->EditValue, -2, -2, -2, -2);
        }

        // tasa_usd
        $this->tasa_usd->EditAttrs["class"] = "form-control";
        $this->tasa_usd->EditCustomAttributes = "";
        $this->tasa_usd->EditValue = $this->tasa_usd->CurrentValue;
        $this->tasa_usd->PlaceHolder = RemoveHtml($this->tasa_usd->caption());
        if (strval($this->tasa_usd->EditValue) != "" && is_numeric($this->tasa_usd->EditValue)) {
            $this->tasa_usd->EditValue = FormatNumber($this->tasa_usd->EditValue, -2, -2, -2, -2);
        }

        // monto_usd
        $this->monto_usd->EditAttrs["class"] = "form-control";
        $this->monto_usd->EditCustomAttributes = "";
        $this->monto_usd->EditValue = $this->monto_usd->CurrentValue;
        $this->monto_usd->PlaceHolder = RemoveHtml($this->monto_usd->caption());
        if (strval($this->monto_usd->EditValue) != "" && is_numeric($this->monto_usd->EditValue)) {
            $this->monto_usd->EditValue = FormatNumber($this->monto_usd->EditValue, -2, -1, -2, -1);
        }

        // saldo
        $this->saldo->EditAttrs["class"] = "form-control";
        $this->saldo->EditCustomAttributes = "";
        $this->saldo->EditValue = $this->saldo->CurrentValue;
        $this->saldo->PlaceHolder = RemoveHtml($this->saldo->caption());
        if (strval($this->saldo->EditValue) != "" && is_numeric($this->saldo->EditValue)) {
            $this->saldo->EditValue = FormatNumber($this->saldo->EditValue, -2, -1, -2, -1);
        }

        // nota
        $this->nota->EditAttrs["class"] = "form-control";
        $this->nota->EditCustomAttributes = "";
        $this->nota->EditValue = $this->nota->CurrentValue;
        $this->nota->PlaceHolder = RemoveHtml($this->nota->caption());

        // username
        $this->_username->EditAttrs["class"] = "form-control";
        $this->_username->EditCustomAttributes = "";
        if (!$this->_username->Raw) {
            $this->_username->CurrentValue = HtmlDecode($this->_username->CurrentValue);
        }
        $this->_username->EditValue = $this->_username->CurrentValue;
        $this->_username->PlaceHolder = RemoveHtml($this->_username->caption());

        // cobro_cliente_reverso
        $this->cobro_cliente_reverso->EditAttrs["class"] = "form-control";
        $this->cobro_cliente_reverso->EditCustomAttributes = "";
        $this->cobro_cliente_reverso->EditValue = $this->cobro_cliente_reverso->CurrentValue;
        $this->cobro_cliente_reverso->PlaceHolder = RemoveHtml($this->cobro_cliente_reverso->caption());

        // nro_recibo
        $this->nro_recibo->EditAttrs["class"] = "form-control";
        $this->nro_recibo->EditCustomAttributes = "";
        $this->nro_recibo->EditValue = $this->nro_recibo->CurrentValue;
        $this->nro_recibo->PlaceHolder = RemoveHtml($this->nro_recibo->caption());

        // nota_recepcion
        $this->nota_recepcion->EditAttrs["class"] = "form-control";
        $this->nota_recepcion->EditCustomAttributes = "";
        $this->nota_recepcion->EditValue = $this->nota_recepcion->CurrentValue;
        $this->nota_recepcion->PlaceHolder = RemoveHtml($this->nota_recepcion->caption());

        // abono
        $this->abono->EditAttrs["class"] = "form-control";
        $this->abono->EditCustomAttributes = "";
        if ($this->abono->getSessionValue() != "") {
            $this->abono->CurrentValue = GetForeignKeyValue($this->abono->getSessionValue());
            $this->abono->ViewValue = $this->abono->CurrentValue;
            $this->abono->ViewValue = FormatNumber($this->abono->ViewValue, 0, -2, -2, -2);
            $this->abono->ViewCustomAttributes = "";
        } else {
            $this->abono->EditValue = $this->abono->CurrentValue;
            $this->abono->PlaceHolder = RemoveHtml($this->abono->caption());
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
                    $doc->exportCaption($this->cliente);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->metodo_pago);
                    $doc->exportCaption($this->referencia);
                    $doc->exportCaption($this->reverso);
                    $doc->exportCaption($this->monto_moneda);
                    $doc->exportCaption($this->moneda);
                    $doc->exportCaption($this->tasa_moneda);
                    $doc->exportCaption($this->monto_bs);
                    $doc->exportCaption($this->tasa_usd);
                    $doc->exportCaption($this->monto_usd);
                    $doc->exportCaption($this->saldo);
                    $doc->exportCaption($this->nota);
                    $doc->exportCaption($this->_username);
                    $doc->exportCaption($this->nro_recibo);
                } else {
                    $doc->exportCaption($this->id);
                    $doc->exportCaption($this->cliente);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->metodo_pago);
                    $doc->exportCaption($this->referencia);
                    $doc->exportCaption($this->reverso);
                    $doc->exportCaption($this->monto_moneda);
                    $doc->exportCaption($this->moneda);
                    $doc->exportCaption($this->tasa_moneda);
                    $doc->exportCaption($this->monto_bs);
                    $doc->exportCaption($this->tasa_usd);
                    $doc->exportCaption($this->monto_usd);
                    $doc->exportCaption($this->saldo);
                    $doc->exportCaption($this->nota);
                    $doc->exportCaption($this->_username);
                    $doc->exportCaption($this->cobro_cliente_reverso);
                    $doc->exportCaption($this->nro_recibo);
                    $doc->exportCaption($this->nota_recepcion);
                    $doc->exportCaption($this->abono);
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
                        $doc->exportField($this->cliente);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->metodo_pago);
                        $doc->exportField($this->referencia);
                        $doc->exportField($this->reverso);
                        $doc->exportField($this->monto_moneda);
                        $doc->exportField($this->moneda);
                        $doc->exportField($this->tasa_moneda);
                        $doc->exportField($this->monto_bs);
                        $doc->exportField($this->tasa_usd);
                        $doc->exportField($this->monto_usd);
                        $doc->exportField($this->saldo);
                        $doc->exportField($this->nota);
                        $doc->exportField($this->_username);
                        $doc->exportField($this->nro_recibo);
                    } else {
                        $doc->exportField($this->id);
                        $doc->exportField($this->cliente);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->metodo_pago);
                        $doc->exportField($this->referencia);
                        $doc->exportField($this->reverso);
                        $doc->exportField($this->monto_moneda);
                        $doc->exportField($this->moneda);
                        $doc->exportField($this->tasa_moneda);
                        $doc->exportField($this->monto_bs);
                        $doc->exportField($this->tasa_usd);
                        $doc->exportField($this->monto_usd);
                        $doc->exportField($this->saldo);
                        $doc->exportField($this->nota);
                        $doc->exportField($this->_username);
                        $doc->exportField($this->cobro_cliente_reverso);
                        $doc->exportField($this->nro_recibo);
                        $doc->exportField($this->nota_recepcion);
                        $doc->exportField($this->abono);
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
        $table = 'recarga2';
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
        $table = 'recarga2';

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
        $table = 'recarga2';

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
        $table = 'recarga2';

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
        if(isset($_GET["xCliente"])) {
        	AddFilter($filter, "cliente = " . $_GET["xCliente"] . "");
        }
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
        $moneda = $rsnew["moneda"];
        $monto_moneda = $rsnew["monto_moneda"];
        if($rsnew["reverso"] == "S") $monto_moneda = (-1)*$monto_moneda;
        $sql = "SELECT tasa FROM tasa_usd
        		WHERE moneda = '$moneda' ORDER BY id DESC LIMIT 0, 1;";
        $tasa = ExecuteScalar($sql);
        $rsnew["tasa_moneda"] = $tasa;
        $monto_bs = $tasa * $monto_moneda;
        $rsnew["monto_bs"] = $monto_bs;
        $sql = "SELECT tasa FROM tasa_usd
        		WHERE moneda = 'USD' ORDER BY id DESC LIMIT 0, 1;";
        $tasa_usd = ExecuteScalar($sql);
        $rsnew["tasa_usd"] = $tasa_usd;
        $rsnew["monto_usd"] = $monto_bs / $tasa_usd;
        $rsnew["fecha"] = date("Y-m-d");
        $rsnew["username"] = CurrentUserName();
        $sql = "SELECT IFNULL(MAX(nro_recibo), 0)+1 AS nro FROM recarga2 WHERE 1;";
        $rsnew["nro_recibo"] = ExecuteScalar($sql);
        return true;
    }

    // Row Inserted event
    public function rowInserted($rsold, &$rsnew)
    {
        //Log("Row Inserted");
        $cliente = $rsnew["cliente"];
        $sql = "SELECT IFNULL(SUM(monto_usd), 0) AS saldo FROM recarga
        		WHERE cliente = $cliente;";
        $saldo = ExecuteScalar($sql);
        $id = $rsnew["id"];
        $sql = "UPDATE recarga2 SET saldo = $saldo WHERE id = $id;";
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
        if ($this->PageID == "list" || $this->PageID == "view") { // List/View page only
            if ($this->reverso->CurrentValue == 'S') {
                $this->cliente->CellAttrs["style"] = "background-color: red";
                $this->fecha->CellAttrs["style"] = "background-color: red";
                $this->moneda->CellAttrs["style"] = "background-color: red";
                $this->monto_moneda->CellAttrs["style"] = "background-color: red";
                $this->tasa_usd->CellAttrs["style"] = "background-color: red";
                $this->monto_usd->CellAttrs["style"] = "background-color: red";
                $this->saldo->CellAttrs["style"] = "background-color: red";
            }
        }
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
