<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for cliente
 */
class Cliente extends DbTable
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
    public $ci_rif;
    public $nombre;
    public $sucursal;
    public $contacto;
    public $ciudad;
    public $direccion;
    public $telefono1;
    public $telefono2;
    public $email1;
    public $email2;
    public $codigo_ims;
    public $web;
    public $tipo_cliente;
    public $tarifa;
    public $cuenta;
    public $activo;
    public $consignacion;
    public $refiere;
    public $puntos_refiere;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'cliente';
        $this->TableName = 'cliente';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`cliente`";
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
        $this->id = new DbField('cliente', 'cliente', 'x_id', 'id', '`id`', '`id`', 19, 11, -1, false, '`id`', false, false, false, 'FORMATTED TEXT', 'NO');
        $this->id->IsAutoIncrement = true; // Autoincrement field
        $this->id->IsPrimaryKey = true; // Primary key field
        $this->id->IsForeignKey = true; // Foreign key field
        $this->id->Sortable = true; // Allow sort
        $this->id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->id->Param, "CustomMsg");
        $this->Fields['id'] = &$this->id;

        // codigo
        $this->codigo = new DbField('cliente', 'cliente', 'x_codigo', 'codigo', '`codigo`', '`codigo`', 200, 6, -1, false, '`codigo`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->codigo->Sortable = true; // Allow sort
        $this->codigo->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->codigo->Param, "CustomMsg");
        $this->Fields['codigo'] = &$this->codigo;

        // ci_rif
        $this->ci_rif = new DbField('cliente', 'cliente', 'x_ci_rif', 'ci_rif', '`ci_rif`', '`ci_rif`', 200, 30, -1, false, '`ci_rif`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->ci_rif->Required = true; // Required field
        $this->ci_rif->Sortable = true; // Allow sort
        $this->ci_rif->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->ci_rif->Param, "CustomMsg");
        $this->Fields['ci_rif'] = &$this->ci_rif;

        // nombre
        $this->nombre = new DbField('cliente', 'cliente', 'x_nombre', 'nombre', '`nombre`', '`nombre`', 200, 80, -1, false, '`nombre`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->nombre->Required = true; // Required field
        $this->nombre->Sortable = true; // Allow sort
        $this->nombre->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->nombre->Param, "CustomMsg");
        $this->Fields['nombre'] = &$this->nombre;

        // sucursal
        $this->sucursal = new DbField('cliente', 'cliente', 'x_sucursal', 'sucursal', '`sucursal`', '`sucursal`', 200, 80, -1, false, '`sucursal`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->sucursal->Sortable = true; // Allow sort
        $this->sucursal->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->sucursal->Param, "CustomMsg");
        $this->Fields['sucursal'] = &$this->sucursal;

        // contacto
        $this->contacto = new DbField('cliente', 'cliente', 'x_contacto', 'contacto', '`contacto`', '`contacto`', 200, 30, -1, false, '`contacto`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->contacto->Sortable = true; // Allow sort
        $this->contacto->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->contacto->Param, "CustomMsg");
        $this->Fields['contacto'] = &$this->contacto;

        // ciudad
        $this->ciudad = new DbField('cliente', 'cliente', 'x_ciudad', 'ciudad', '`ciudad`', '`ciudad`', 200, 6, -1, false, '`ciudad`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->ciudad->Sortable = true; // Allow sort
        $this->ciudad->Lookup = new Lookup('ciudad', 'tabla', false, 'campo_codigo', ["campo_descripcion","","",""], [], [], [], [], [], [], '`campo_descripcion`', '');
        $this->ciudad->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->ciudad->Param, "CustomMsg");
        $this->Fields['ciudad'] = &$this->ciudad;

        // direccion
        $this->direccion = new DbField('cliente', 'cliente', 'x_direccion', 'direccion', '`direccion`', '`direccion`', 200, 150, -1, false, '`direccion`', false, false, false, 'FORMATTED TEXT', 'TEXTAREA');
        $this->direccion->Required = true; // Required field
        $this->direccion->Sortable = true; // Allow sort
        $this->direccion->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->direccion->Param, "CustomMsg");
        $this->Fields['direccion'] = &$this->direccion;

        // telefono1
        $this->telefono1 = new DbField('cliente', 'cliente', 'x_telefono1', 'telefono1', '`telefono1`', '`telefono1`', 200, 20, -1, false, '`telefono1`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->telefono1->Required = true; // Required field
        $this->telefono1->Sortable = true; // Allow sort
        $this->telefono1->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->telefono1->Param, "CustomMsg");
        $this->Fields['telefono1'] = &$this->telefono1;

        // telefono2
        $this->telefono2 = new DbField('cliente', 'cliente', 'x_telefono2', 'telefono2', '`telefono2`', '`telefono2`', 200, 20, -1, false, '`telefono2`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->telefono2->Sortable = true; // Allow sort
        $this->telefono2->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->telefono2->Param, "CustomMsg");
        $this->Fields['telefono2'] = &$this->telefono2;

        // email1
        $this->email1 = new DbField('cliente', 'cliente', 'x_email1', 'email1', '`email1`', '`email1`', 200, 100, -1, false, '`email1`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->email1->Sortable = true; // Allow sort
        $this->email1->DefaultErrorMessage = $Language->phrase("IncorrectEmail");
        $this->email1->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->email1->Param, "CustomMsg");
        $this->Fields['email1'] = &$this->email1;

        // email2
        $this->email2 = new DbField('cliente', 'cliente', 'x_email2', 'email2', '`email2`', '`email2`', 200, 100, -1, false, '`email2`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->email2->Sortable = true; // Allow sort
        $this->email2->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->email2->Param, "CustomMsg");
        $this->Fields['email2'] = &$this->email2;

        // codigo_ims
        $this->codigo_ims = new DbField('cliente', 'cliente', 'x_codigo_ims', 'codigo_ims', '`codigo_ims`', '`codigo_ims`', 200, 15, -1, false, '`codigo_ims`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->codigo_ims->Sortable = true; // Allow sort
        $this->codigo_ims->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->codigo_ims->Param, "CustomMsg");
        $this->Fields['codigo_ims'] = &$this->codigo_ims;

        // web
        $this->web = new DbField('cliente', 'cliente', 'x_web', 'web', '`web`', '`web`', 200, 100, -1, false, '`web`', false, false, false, 'FORMATTED TEXT', 'RADIO');
        $this->web->Required = true; // Required field
        $this->web->Sortable = true; // Allow sort
        $this->web->Lookup = new Lookup('web', 'cliente', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->web->OptionCount = 2;
        $this->web->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->web->Param, "CustomMsg");
        $this->Fields['web'] = &$this->web;

        // tipo_cliente
        $this->tipo_cliente = new DbField('cliente', 'cliente', 'x_tipo_cliente', 'tipo_cliente', '`tipo_cliente`', '`tipo_cliente`', 200, 25, -1, false, '`tipo_cliente`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->tipo_cliente->Sortable = true; // Allow sort
        $this->tipo_cliente->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->tipo_cliente->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->tipo_cliente->Lookup = new Lookup('tipo_cliente', 'tabla', false, 'campo_codigo', ["campo_descripcion","","",""], [], [], [], [], [], [], '`campo_descripcion`', '');
        $this->tipo_cliente->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tipo_cliente->Param, "CustomMsg");
        $this->Fields['tipo_cliente'] = &$this->tipo_cliente;

        // tarifa
        $this->tarifa = new DbField('cliente', 'cliente', 'x_tarifa', 'tarifa', '`tarifa`', '`tarifa`', 19, 11, -1, false, '`tarifa`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->tarifa->Required = true; // Required field
        $this->tarifa->Sortable = true; // Allow sort
        $this->tarifa->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->tarifa->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->tarifa->Lookup = new Lookup('tarifa', 'tarifa', false, 'id', ["nombre","","",""], [], [], [], [], [], [], '`nombre`', '');
        $this->tarifa->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->tarifa->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tarifa->Param, "CustomMsg");
        $this->Fields['tarifa'] = &$this->tarifa;

        // cuenta
        $this->cuenta = new DbField('cliente', 'cliente', 'x_cuenta', 'cuenta', '`cuenta`', '`cuenta`', 19, 11, -1, false, '`cuenta`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->cuenta->Sortable = true; // Allow sort
        $this->cuenta->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->cuenta->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->cuenta->Lookup = new Lookup('cuenta', 'view_plancta', false, 'id', ["codigo","descripcion","",""], [], [], [], [], [], [], '`codigo`', '');
        $this->cuenta->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->cuenta->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->cuenta->Param, "CustomMsg");
        $this->Fields['cuenta'] = &$this->cuenta;

        // activo
        $this->activo = new DbField('cliente', 'cliente', 'x_activo', 'activo', '`activo`', '`activo`', 202, 1, -1, false, '`activo`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->activo->Required = true; // Required field
        $this->activo->Sortable = true; // Allow sort
        $this->activo->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->activo->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->activo->Lookup = new Lookup('activo', 'cliente', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->activo->OptionCount = 2;
        $this->activo->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->activo->Param, "CustomMsg");
        $this->Fields['activo'] = &$this->activo;

        // consignacion
        $this->consignacion = new DbField('cliente', 'cliente', 'x_consignacion', 'consignacion', '`consignacion`', '`consignacion`', 202, 1, -1, false, '`consignacion`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->consignacion->Sortable = true; // Allow sort
        $this->consignacion->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->consignacion->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->consignacion->Lookup = new Lookup('consignacion', 'cliente', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->consignacion->OptionCount = 2;
        $this->consignacion->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->consignacion->Param, "CustomMsg");
        $this->Fields['consignacion'] = &$this->consignacion;

        // refiere
        $this->refiere = new DbField('cliente', 'cliente', 'x_refiere', 'refiere', '`refiere`', '`refiere`', 3, 11, -1, false, '`refiere`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->refiere->Sortable = true; // Allow sort
        $this->refiere->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->refiere->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->refiere->Lookup = new Lookup('refiere', 'cliente', false, 'id', ["ci_rif","nombre","",""], [], [], [], [], [], [], '`nombre`', '');
        $this->refiere->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->refiere->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->refiere->Param, "CustomMsg");
        $this->Fields['refiere'] = &$this->refiere;

        // puntos_refiere
        $this->puntos_refiere = new DbField('cliente', 'cliente', 'x_puntos_refiere', 'puntos_refiere', '`puntos_refiere`', '`puntos_refiere`', 202, 1, -1, false, '`puntos_refiere`', false, false, false, 'FORMATTED TEXT', 'RADIO');
        $this->puntos_refiere->Sortable = true; // Allow sort
        $this->puntos_refiere->Lookup = new Lookup('puntos_refiere', 'cliente', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->puntos_refiere->OptionCount = 2;
        $this->puntos_refiere->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->puntos_refiere->Param, "CustomMsg");
        $this->Fields['puntos_refiere'] = &$this->puntos_refiere;
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
        if ($this->getCurrentDetailTable() == "adjunto") {
            $detailUrl = Container("adjunto")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue);
        }
        if ($detailUrl == "") {
            $detailUrl = "ClienteList";
        }
        return $detailUrl;
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`cliente`";
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
        // Cascade Update detail table 'adjunto'
        $cascadeUpdate = false;
        $rscascade = [];
        if ($rsold && (isset($rs['id']) && $rsold['id'] != $rs['id'])) { // Update detail field 'cliente'
            $cascadeUpdate = true;
            $rscascade['cliente'] = $rs['id'];
        }
        if ($cascadeUpdate) {
            $rswrk = Container("adjunto")->loadRs("`cliente` = " . QuotedValue($rsold['id'], DATATYPE_NUMBER, 'DB'))->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rswrk as $rsdtlold) {
                $rskey = [];
                $fldname = 'id';
                $rskey[$fldname] = $rsdtlold[$fldname];
                $rsdtlnew = array_merge($rsdtlold, $rscascade);
                // Call Row_Updating event
                $success = Container("adjunto")->rowUpdating($rsdtlold, $rsdtlnew);
                if ($success) {
                    $success = Container("adjunto")->update($rscascade, $rskey, $rsdtlold);
                }
                if (!$success) {
                    return false;
                }
                // Call Row_Updated event
                Container("adjunto")->rowUpdated($rsdtlold, $rsdtlnew);
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

        // Cascade delete detail table 'adjunto'
        $dtlrows = Container("adjunto")->loadRs("`cliente` = " . QuotedValue($rs['id'], DATATYPE_NUMBER, "DB"))->fetchAll(\PDO::FETCH_ASSOC);
        // Call Row Deleting event
        foreach ($dtlrows as $dtlrow) {
            $success = Container("adjunto")->rowDeleting($dtlrow);
            if (!$success) {
                break;
            }
        }
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                $success = Container("adjunto")->delete($dtlrow); // Delete
                if (!$success) {
                    break;
                }
            }
        }
        // Call Row Deleted event
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                Container("adjunto")->rowDeleted($dtlrow);
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
        $this->codigo->DbValue = $row['codigo'];
        $this->ci_rif->DbValue = $row['ci_rif'];
        $this->nombre->DbValue = $row['nombre'];
        $this->sucursal->DbValue = $row['sucursal'];
        $this->contacto->DbValue = $row['contacto'];
        $this->ciudad->DbValue = $row['ciudad'];
        $this->direccion->DbValue = $row['direccion'];
        $this->telefono1->DbValue = $row['telefono1'];
        $this->telefono2->DbValue = $row['telefono2'];
        $this->email1->DbValue = $row['email1'];
        $this->email2->DbValue = $row['email2'];
        $this->codigo_ims->DbValue = $row['codigo_ims'];
        $this->web->DbValue = $row['web'];
        $this->tipo_cliente->DbValue = $row['tipo_cliente'];
        $this->tarifa->DbValue = $row['tarifa'];
        $this->cuenta->DbValue = $row['cuenta'];
        $this->activo->DbValue = $row['activo'];
        $this->consignacion->DbValue = $row['consignacion'];
        $this->refiere->DbValue = $row['refiere'];
        $this->puntos_refiere->DbValue = $row['puntos_refiere'];
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
        return $_SESSION[$name] ?? GetUrl("ClienteList");
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
        if ($pageName == "ClienteView") {
            return $Language->phrase("View");
        } elseif ($pageName == "ClienteEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "ClienteAdd") {
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
                return "ClienteView";
            case Config("API_ADD_ACTION"):
                return "ClienteAdd";
            case Config("API_EDIT_ACTION"):
                return "ClienteEdit";
            case Config("API_DELETE_ACTION"):
                return "ClienteDelete";
            case Config("API_LIST_ACTION"):
                return "ClienteList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "ClienteList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ClienteView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("ClienteView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "ClienteAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "ClienteAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ClienteEdit", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("ClienteEdit", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
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
            $url = $this->keyUrl("ClienteAdd", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("ClienteAdd", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
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
        return $this->keyUrl("ClienteDelete", $this->getUrlParm());
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
        $this->ci_rif->setDbValue($row['ci_rif']);
        $this->nombre->setDbValue($row['nombre']);
        $this->sucursal->setDbValue($row['sucursal']);
        $this->contacto->setDbValue($row['contacto']);
        $this->ciudad->setDbValue($row['ciudad']);
        $this->direccion->setDbValue($row['direccion']);
        $this->telefono1->setDbValue($row['telefono1']);
        $this->telefono2->setDbValue($row['telefono2']);
        $this->email1->setDbValue($row['email1']);
        $this->email2->setDbValue($row['email2']);
        $this->codigo_ims->setDbValue($row['codigo_ims']);
        $this->web->setDbValue($row['web']);
        $this->tipo_cliente->setDbValue($row['tipo_cliente']);
        $this->tarifa->setDbValue($row['tarifa']);
        $this->cuenta->setDbValue($row['cuenta']);
        $this->activo->setDbValue($row['activo']);
        $this->consignacion->setDbValue($row['consignacion']);
        $this->refiere->setDbValue($row['refiere']);
        $this->puntos_refiere->setDbValue($row['puntos_refiere']);
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

        // ci_rif

        // nombre

        // sucursal

        // contacto

        // ciudad

        // direccion

        // telefono1

        // telefono2

        // email1

        // email2

        // codigo_ims

        // web

        // tipo_cliente

        // tarifa

        // cuenta

        // activo

        // consignacion

        // refiere

        // puntos_refiere

        // id
        $this->id->ViewValue = $this->id->CurrentValue;
        $this->id->ViewCustomAttributes = "";

        // codigo
        $this->codigo->ViewValue = $this->codigo->CurrentValue;
        $this->codigo->ViewCustomAttributes = "";

        // ci_rif
        $this->ci_rif->ViewValue = $this->ci_rif->CurrentValue;
        $this->ci_rif->ViewCustomAttributes = "";

        // nombre
        $this->nombre->ViewValue = $this->nombre->CurrentValue;
        $this->nombre->ViewCustomAttributes = "";

        // sucursal
        $this->sucursal->ViewValue = $this->sucursal->CurrentValue;
        $this->sucursal->ViewCustomAttributes = "";

        // contacto
        $this->contacto->ViewValue = $this->contacto->CurrentValue;
        $this->contacto->ViewCustomAttributes = "";

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

        // codigo_ims
        $this->codigo_ims->ViewValue = $this->codigo_ims->CurrentValue;
        $this->codigo_ims->ViewCustomAttributes = "";

        // web
        if (strval($this->web->CurrentValue) != "") {
            $this->web->ViewValue = $this->web->optionCaption($this->web->CurrentValue);
        } else {
            $this->web->ViewValue = null;
        }
        $this->web->ViewCustomAttributes = "";

        // tipo_cliente
        $curVal = trim(strval($this->tipo_cliente->CurrentValue));
        if ($curVal != "") {
            $this->tipo_cliente->ViewValue = $this->tipo_cliente->lookupCacheOption($curVal);
            if ($this->tipo_cliente->ViewValue === null) { // Lookup from database
                $filterWrk = "`campo_codigo`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                $lookupFilter = function() {
                    return "`tabla` = 'TIPO_CLIENTE'";
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->tipo_cliente->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->tipo_cliente->Lookup->renderViewRow($rswrk[0]);
                    $this->tipo_cliente->ViewValue = $this->tipo_cliente->displayValue($arwrk);
                } else {
                    $this->tipo_cliente->ViewValue = $this->tipo_cliente->CurrentValue;
                }
            }
        } else {
            $this->tipo_cliente->ViewValue = null;
        }
        $this->tipo_cliente->ViewCustomAttributes = "";

        // tarifa
        $curVal = trim(strval($this->tarifa->CurrentValue));
        if ($curVal != "") {
            $this->tarifa->ViewValue = $this->tarifa->lookupCacheOption($curVal);
            if ($this->tarifa->ViewValue === null) { // Lookup from database
                $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $lookupFilter = function() {
                    return "activo = 'S'";
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->tarifa->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->tarifa->Lookup->renderViewRow($rswrk[0]);
                    $this->tarifa->ViewValue = $this->tarifa->displayValue($arwrk);
                } else {
                    $this->tarifa->ViewValue = $this->tarifa->CurrentValue;
                }
            }
        } else {
            $this->tarifa->ViewValue = null;
        }
        $this->tarifa->ViewCustomAttributes = "";

        // cuenta
        $curVal = trim(strval($this->cuenta->CurrentValue));
        if ($curVal != "") {
            $this->cuenta->ViewValue = $this->cuenta->lookupCacheOption($curVal);
            if ($this->cuenta->ViewValue === null) { // Lookup from database
                $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $lookupFilter = function() {
                    return "codigo LIKE CONCAT((SELECT valor2 FROM parametro WHERE codigo = '018' and valor1 = 'Clientes'), '%')";
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->cuenta->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->cuenta->Lookup->renderViewRow($rswrk[0]);
                    $this->cuenta->ViewValue = $this->cuenta->displayValue($arwrk);
                } else {
                    $this->cuenta->ViewValue = $this->cuenta->CurrentValue;
                }
            }
        } else {
            $this->cuenta->ViewValue = null;
        }
        $this->cuenta->ViewCustomAttributes = "";

        // activo
        if (strval($this->activo->CurrentValue) != "") {
            $this->activo->ViewValue = $this->activo->optionCaption($this->activo->CurrentValue);
        } else {
            $this->activo->ViewValue = null;
        }
        $this->activo->ViewCustomAttributes = "";

        // consignacion
        if (strval($this->consignacion->CurrentValue) != "") {
            $this->consignacion->ViewValue = $this->consignacion->optionCaption($this->consignacion->CurrentValue);
        } else {
            $this->consignacion->ViewValue = null;
        }
        $this->consignacion->ViewCustomAttributes = "";

        // refiere
        $curVal = trim(strval($this->refiere->CurrentValue));
        if ($curVal != "") {
            $this->refiere->ViewValue = $this->refiere->lookupCacheOption($curVal);
            if ($this->refiere->ViewValue === null) { // Lookup from database
                $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $sqlWrk = $this->refiere->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->refiere->Lookup->renderViewRow($rswrk[0]);
                    $this->refiere->ViewValue = $this->refiere->displayValue($arwrk);
                } else {
                    $this->refiere->ViewValue = $this->refiere->CurrentValue;
                }
            }
        } else {
            $this->refiere->ViewValue = null;
        }
        $this->refiere->ViewCustomAttributes = "";

        // puntos_refiere
        if (strval($this->puntos_refiere->CurrentValue) != "") {
            $this->puntos_refiere->ViewValue = $this->puntos_refiere->optionCaption($this->puntos_refiere->CurrentValue);
        } else {
            $this->puntos_refiere->ViewValue = null;
        }
        $this->puntos_refiere->ViewCustomAttributes = "";

        // id
        $this->id->LinkCustomAttributes = "";
        $this->id->HrefValue = "";
        $this->id->TooltipValue = "";

        // codigo
        $this->codigo->LinkCustomAttributes = "";
        $this->codigo->HrefValue = "";
        $this->codigo->TooltipValue = "";

        // ci_rif
        $this->ci_rif->LinkCustomAttributes = "";
        $this->ci_rif->HrefValue = "";
        $this->ci_rif->TooltipValue = "";

        // nombre
        $this->nombre->LinkCustomAttributes = "";
        $this->nombre->HrefValue = "";
        $this->nombre->TooltipValue = "";

        // sucursal
        $this->sucursal->LinkCustomAttributes = "";
        $this->sucursal->HrefValue = "";
        $this->sucursal->TooltipValue = "";

        // contacto
        $this->contacto->LinkCustomAttributes = "";
        $this->contacto->HrefValue = "";
        $this->contacto->TooltipValue = "";

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

        // codigo_ims
        $this->codigo_ims->LinkCustomAttributes = "";
        $this->codigo_ims->HrefValue = "";
        $this->codigo_ims->TooltipValue = "";

        // web
        $this->web->LinkCustomAttributes = "";
        $this->web->HrefValue = "";
        $this->web->TooltipValue = "";

        // tipo_cliente
        $this->tipo_cliente->LinkCustomAttributes = "";
        $this->tipo_cliente->HrefValue = "";
        $this->tipo_cliente->TooltipValue = "";

        // tarifa
        $this->tarifa->LinkCustomAttributes = "";
        $this->tarifa->HrefValue = "";
        $this->tarifa->TooltipValue = "";

        // cuenta
        $this->cuenta->LinkCustomAttributes = "";
        $this->cuenta->HrefValue = "";
        $this->cuenta->TooltipValue = "";

        // activo
        $this->activo->LinkCustomAttributes = "";
        $this->activo->HrefValue = "";
        $this->activo->TooltipValue = "";

        // consignacion
        $this->consignacion->LinkCustomAttributes = "";
        $this->consignacion->HrefValue = "";
        $this->consignacion->TooltipValue = "";

        // refiere
        $this->refiere->LinkCustomAttributes = "";
        $this->refiere->HrefValue = "";
        $this->refiere->TooltipValue = "";

        // puntos_refiere
        $this->puntos_refiere->LinkCustomAttributes = "";
        $this->puntos_refiere->HrefValue = "";
        $this->puntos_refiere->TooltipValue = "";

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
        if (!$this->codigo->Raw) {
            $this->codigo->CurrentValue = HtmlDecode($this->codigo->CurrentValue);
        }
        $this->codigo->EditValue = $this->codigo->CurrentValue;
        $this->codigo->PlaceHolder = RemoveHtml($this->codigo->caption());

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

        // sucursal
        $this->sucursal->EditAttrs["class"] = "form-control";
        $this->sucursal->EditCustomAttributes = "";
        if (!$this->sucursal->Raw) {
            $this->sucursal->CurrentValue = HtmlDecode($this->sucursal->CurrentValue);
        }
        $this->sucursal->EditValue = $this->sucursal->CurrentValue;
        $this->sucursal->PlaceHolder = RemoveHtml($this->sucursal->caption());

        // contacto
        $this->contacto->EditAttrs["class"] = "form-control";
        $this->contacto->EditCustomAttributes = "";
        if (!$this->contacto->Raw) {
            $this->contacto->CurrentValue = HtmlDecode($this->contacto->CurrentValue);
        }
        $this->contacto->EditValue = $this->contacto->CurrentValue;
        $this->contacto->PlaceHolder = RemoveHtml($this->contacto->caption());

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

        // codigo_ims
        $this->codigo_ims->EditAttrs["class"] = "form-control";
        $this->codigo_ims->EditCustomAttributes = "";
        if (!$this->codigo_ims->Raw) {
            $this->codigo_ims->CurrentValue = HtmlDecode($this->codigo_ims->CurrentValue);
        }
        $this->codigo_ims->EditValue = $this->codigo_ims->CurrentValue;
        $this->codigo_ims->PlaceHolder = RemoveHtml($this->codigo_ims->caption());

        // web
        $this->web->EditCustomAttributes = "";
        $this->web->EditValue = $this->web->options(false);
        $this->web->PlaceHolder = RemoveHtml($this->web->caption());

        // tipo_cliente
        $this->tipo_cliente->EditAttrs["class"] = "form-control";
        $this->tipo_cliente->EditCustomAttributes = "";
        $this->tipo_cliente->PlaceHolder = RemoveHtml($this->tipo_cliente->caption());

        // tarifa
        $this->tarifa->EditAttrs["class"] = "form-control";
        $this->tarifa->EditCustomAttributes = "";
        $this->tarifa->PlaceHolder = RemoveHtml($this->tarifa->caption());

        // cuenta
        $this->cuenta->EditAttrs["class"] = "form-control";
        $this->cuenta->EditCustomAttributes = "";
        $this->cuenta->PlaceHolder = RemoveHtml($this->cuenta->caption());

        // activo
        $this->activo->EditAttrs["class"] = "form-control";
        $this->activo->EditCustomAttributes = "";
        $this->activo->EditValue = $this->activo->options(true);
        $this->activo->PlaceHolder = RemoveHtml($this->activo->caption());

        // consignacion
        $this->consignacion->EditAttrs["class"] = "form-control";
        $this->consignacion->EditCustomAttributes = "";
        $this->consignacion->EditValue = $this->consignacion->options(true);
        $this->consignacion->PlaceHolder = RemoveHtml($this->consignacion->caption());

        // refiere
        $this->refiere->EditAttrs["class"] = "form-control";
        $this->refiere->EditCustomAttributes = "";
        $this->refiere->PlaceHolder = RemoveHtml($this->refiere->caption());

        // puntos_refiere
        $this->puntos_refiere->EditCustomAttributes = "";
        $this->puntos_refiere->EditValue = $this->puntos_refiere->options(false);
        $this->puntos_refiere->PlaceHolder = RemoveHtml($this->puntos_refiere->caption());

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
                    $doc->exportCaption($this->ci_rif);
                    $doc->exportCaption($this->nombre);
                    $doc->exportCaption($this->sucursal);
                    $doc->exportCaption($this->contacto);
                    $doc->exportCaption($this->ciudad);
                    $doc->exportCaption($this->direccion);
                    $doc->exportCaption($this->telefono1);
                    $doc->exportCaption($this->telefono2);
                    $doc->exportCaption($this->email1);
                    $doc->exportCaption($this->email2);
                    $doc->exportCaption($this->web);
                    $doc->exportCaption($this->tipo_cliente);
                    $doc->exportCaption($this->tarifa);
                    $doc->exportCaption($this->cuenta);
                    $doc->exportCaption($this->activo);
                    $doc->exportCaption($this->consignacion);
                    $doc->exportCaption($this->refiere);
                    $doc->exportCaption($this->puntos_refiere);
                } else {
                    $doc->exportCaption($this->id);
                    $doc->exportCaption($this->codigo);
                    $doc->exportCaption($this->ci_rif);
                    $doc->exportCaption($this->nombre);
                    $doc->exportCaption($this->sucursal);
                    $doc->exportCaption($this->contacto);
                    $doc->exportCaption($this->ciudad);
                    $doc->exportCaption($this->direccion);
                    $doc->exportCaption($this->telefono1);
                    $doc->exportCaption($this->telefono2);
                    $doc->exportCaption($this->email1);
                    $doc->exportCaption($this->email2);
                    $doc->exportCaption($this->codigo_ims);
                    $doc->exportCaption($this->web);
                    $doc->exportCaption($this->tipo_cliente);
                    $doc->exportCaption($this->tarifa);
                    $doc->exportCaption($this->cuenta);
                    $doc->exportCaption($this->activo);
                    $doc->exportCaption($this->consignacion);
                    $doc->exportCaption($this->refiere);
                    $doc->exportCaption($this->puntos_refiere);
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
                        $doc->exportField($this->ci_rif);
                        $doc->exportField($this->nombre);
                        $doc->exportField($this->sucursal);
                        $doc->exportField($this->contacto);
                        $doc->exportField($this->ciudad);
                        $doc->exportField($this->direccion);
                        $doc->exportField($this->telefono1);
                        $doc->exportField($this->telefono2);
                        $doc->exportField($this->email1);
                        $doc->exportField($this->email2);
                        $doc->exportField($this->web);
                        $doc->exportField($this->tipo_cliente);
                        $doc->exportField($this->tarifa);
                        $doc->exportField($this->cuenta);
                        $doc->exportField($this->activo);
                        $doc->exportField($this->consignacion);
                        $doc->exportField($this->refiere);
                        $doc->exportField($this->puntos_refiere);
                    } else {
                        $doc->exportField($this->id);
                        $doc->exportField($this->codigo);
                        $doc->exportField($this->ci_rif);
                        $doc->exportField($this->nombre);
                        $doc->exportField($this->sucursal);
                        $doc->exportField($this->contacto);
                        $doc->exportField($this->ciudad);
                        $doc->exportField($this->direccion);
                        $doc->exportField($this->telefono1);
                        $doc->exportField($this->telefono2);
                        $doc->exportField($this->email1);
                        $doc->exportField($this->email2);
                        $doc->exportField($this->codigo_ims);
                        $doc->exportField($this->web);
                        $doc->exportField($this->tipo_cliente);
                        $doc->exportField($this->tarifa);
                        $doc->exportField($this->cuenta);
                        $doc->exportField($this->activo);
                        $doc->exportField($this->consignacion);
                        $doc->exportField($this->refiere);
                        $doc->exportField($this->puntos_refiere);
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
        $table = 'cliente';
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
        $table = 'cliente';

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
        $table = 'cliente';

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
        $table = 'cliente';

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
    	$rsnew["nombre"] = strtoupper($rsnew["nombre"]);
    	$rsnew["ci_rif"] = trim(strtoupper($rsnew["ci_rif"]));
    	if(trim($rsnew["ci_rif"]) != "") {
    		$sql = "SELECT COUNT(ci_rif) AS cantidad FROM cliente WHERE ci_rif = '" . $rsnew["ci_rif"] . "';";
    		$cantidad = ExecuteScalar($sql);
    		if($cantidad > 0) {
    			$this->CancelMessage = "RIF / CI \"" . $rsnew["ci_rif"] . "\" ya existe.";
    			return FALSE;
    		}
    	}
    	$rsnew["activo"] = "S";

    	/*
    	$sql = "SELECT nombre FROM tarifa WHERE id = " . $rsnew["tarifa"] . ";";
    	if(trim(ExecuteScalar($sql)) == "SANERA") {
    		$sql = "SELECT (MAX(CAST(IFNULL(codigo, 0) AS UNSIGNED)) + 1) AS codigo FROM cliente WHERE 1;";
    		$consecutivo = ExecuteScalar($sql);
    		$rsnew["codigo"] = str_pad($consecutivo, 6, "0", STR_PAD_LEFT);
    	}
    	else $rsnew["codigo"] = "000000";
    	*/
    	if($rsnew["web"] == "S") {
    		$sql = "SELECT (MAX(CAST(IFNULL(codigo, 0) AS UNSIGNED)) + 1) AS codigo FROM cliente WHERE 1;";
    		$consecutivo = ExecuteScalar($sql);
    		$rsnew["codigo"] = str_pad($consecutivo, 6, "0", STR_PAD_LEFT);
    	}
    	else $rsnew["codigo"] = "000000";
    	return TRUE;
    }

    // Row Inserted event
    public function rowInserted($rsold, &$rsnew) {
    	//echo "Row Inserted"
    	$sql = "SELECT valor1 AS moneda FROM parametro WHERE codigo = '006' AND valor2 = 'default';";
    	$moneda = ExecuteScalar($sql);
    	$sql = "SELECT valor2 AS cuenta FROM parametro WHERE codigo = '018' AND valor1 = 'Clientes';";
    	$cuenta = explode("-", ExecuteScalar($sql));
    	if(count($cuenta) > 2) {
    		$clase = trim($cuenta["0"]);
    		$grupo = trim($cuenta["1"]);
    		$cuenta = trim($cuenta["2"]);
    		$subcuenta = str_pad(intval($rsnew["id"]), 4, '0', STR_PAD_LEFT);
    		$where = "LTRIM(RTRIM(clase)) = '$clase' ";
    		$where .= "AND LTRIM(RTRIM(grupo)) = '$grupo' ";
    		$where .= "AND LTRIM(RTRIM(cuenta)) = '$cuenta' ";
    		$where .= "AND LTRIM(RTRIM(subcuenta)) = '$subcuenta' ";
    		$sql = "SELECT 
    					COUNT(descripcion) AS cantidad 
    				FROM 
    					cont_plancta 
    				WHERE 
    					$where;";
    		$cantidad = intval(ExecuteScalar($sql));
    		if($cantidad == 0) {
    			$sql = "INSERT INTO cont_plancta
    					(clase, 
    					grupo, 
    					cuenta, 
    					subcuenta, 
    					descripcion, 
    					clasificacion, 
    					moneda, 
    					activa)
    				VALUES
    					(
    					'$clase', 
    					'$grupo', 
    					'$cuenta', 
    					'$subcuenta', 
    					'" . $rsnew["nombre"] . "',
    					'ACTIVO',
    					'$moneda', 
    					'S'
    					)";
    			Execute($sql);
    			$sql = "SELECT LAST_INSERT_ID();";
    			$idCTA = ExecuteScalar($sql);
    			$sql = "UPDATE cliente SET cuenta = $idCTA WHERE id = '" . $rsnew["id"] . "'";
    			Execute($sql);
    		}
    	}
    }

    // Row Updating event
    public function rowUpdating($rsold, &$rsnew) {
    	// Enter your code here
    	// To cancel, set return value to FALSE
    	$rsnew["nombre"] = strtoupper($rsnew["nombre"]);
    	$rsnew["ci_rif"] = trim(strtoupper($rsnew["ci_rif"]));
    	$rsnew["email2"] = trim($rsnew["email2"]);
    	$rsnew["web"] = trim(strtoupper($rsnew["web"]));
    	if(trim($rsnew["ci_rif"]) != "" and $rsold["ci_rif"] <> $rsnew["ci_rif"]) {
    		$sql = "SELECT COUNT(ci_rif) AS cantidad FROM cliente WHERE ci_rif = '" . $rsnew["ci_rif"] . "';";
    		$cantidad = ExecuteScalar($sql);
    		if($cantidad > 0) {
    			$this->CancelMessage = "RF /CI \"" . $rsnew["ci_rif"] . "\" ya existe.";
    			return FALSE;
    		}
    	}
    	if(trim($rsnew["cuenta"]) != "" and $rsnew["cuenta"] != $rsold["cuenta"]) {
    		$sql = "SELECT COUNT(id) AS cantidad FROM cliente WHERE cuenta = " . $rsnew["cuenta"] . "";
    		$cantidad = ExecuteScalar($sql);
    		if($cantidad > 0) {
    			$this->CancelMessage = "La cuenta seleccionda ya se est&aacute; usando con otro Cliente; verifique";
    			return FALSE;
    		}
    	}
    	if($rsnew["web"] == "S") {
    		if($rsold["codigo"] == "000000" or $rsold["codigo"] == "") {
    			$sql = "SELECT (MAX(CAST(IFNULL(codigo, 0) AS UNSIGNED)) + 1) AS codigo FROM cliente WHERE 1;";
    			$consecutivo = ExecuteScalar($sql);
    			$rsnew["codigo"] = str_pad($consecutivo, 6, "0", STR_PAD_LEFT);
    		}
    	}
    	return TRUE;
    }

    // Row Updated event
    public function rowUpdated($rsold, &$rsnew)
    {
        //Log("Row Updated");
        if($rsold["cuenta"] == "" and $rsnew["cuenta"] == "") {
    		$sql = "SELECT valor2 AS cuenta FROM parametro WHERE codigo = '018' AND valor1 = 'Clientes';";
    		$cuenta = explode("-", ExecuteScalar($sql));
    		if(count($cuenta) > 2) {
    			$clase = trim($cuenta["0"]);
    			$grupo = trim($cuenta["1"]);
    			$cuenta = trim($cuenta["2"]);
    			$subcuenta = str_pad(intval($rsold["id"]), 4, '0', STR_PAD_LEFT);
    			$where = "LTRIM(RTRIM(clase)) = '$clase' ";
    			$where .= "AND LTRIM(RTRIM(grupo)) = '$grupo' ";
    			$where .= "AND LTRIM(RTRIM(cuenta)) = '$cuenta' ";
    			$where .= "AND LTRIM(RTRIM(subcuenta)) = '$subcuenta' ";
    			$sql = "SELECT 
    						COUNT(descripcion) AS cantidad 
    					FROM 
    						cont_plancta 
    					WHERE 
    						$where;";
    			$cantidad = intval(ExecuteScalar($sql));
    			if($cantidad == 0) {
    				$sql = "INSERT INTO cont_plancta
    						(clase, 
    						grupo, 
    						cuenta, 
    						subcuenta, 
    						descripcion, 
    						clasificacion, 
    						activa)
    					VALUES
    						(
    						'$clase', 
    						'$grupo', 
    						'$cuenta', 
    						'$subcuenta', 
    						'" . $rsnew["nombre"] . "',
    						'ACTIVO', 
    						'S'
    						)";
    				Execute($sql);
    				$sql = "SELECT LAST_INSERT_ID();";
    				$idCTA = ExecuteScalar($sql);
    			}
    			else {
    				$sql = "SELECT 
    						id  
    					FROM 
    						cont_plancta 
    					WHERE 
    						$where;";
    				$idCTA = intval(ExecuteScalar($sql));
    			}
    			$sql = "UPDATE cliente SET cuenta = $idCTA WHERE id = '" . $rsold["id"] . "'";
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
    public function rowDeleting(&$rs) {
    	// Enter your code here
    	// To cancel, set return value to False
    	$sql = "SELECT COUNT(id) AS cantidad FROM salidas WHERE cliente = '" . $rs["id"] . "';"; 
    	$cantidad = intval(ExecuteScalar($sql));
    	if($cantidad > 0) {
    		$this->CancelMessage = "Este cliente no se puede eliminar porque tiene movimientos asociados.";
    		return FALSE;
    	}
    	$sql = "SELECT id FROM cont_plancta WHERE id = " . ($rs["cuenta"] == "" ? 0 : $rs["cuenta"]) . ";";
    	if($row = ExecuteRow($sql)) {
    		$this->CancelMessage = "El cliente no se puede eliminar porque tiene un auxiliar contable asociado; !Verifique!";
    		return FALSE;
    	}
    	return TRUE;
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
            if ($this->PageID == "list" || $this->PageID == "view") {
                if(trim($this->tipo_cliente->CurrentValue) != "") {
                	$sql = "SELECT campo_dato AS color FROM tabla WHERE tabla = 'tipo_cliente' AND campo_codigo = '" . $this->tipo_cliente->CurrentValue . "';"; 
                	$valor = trim(ExecuteScalar($sql)); 
                	if ($valor != "") {
                		$color = explode(";", $valor);
                		$valor = 'background-color: ' . $color[0] . '; color: ' . $color[1] . ';';
                        $this->tipo_cliente->CellAttrs["style"] = $valor;
                    } 
                }
            }
    }

    // User ID Filtering event
    public function userIdFiltering(&$filter)
    {
        // Enter your code here
    }
}
