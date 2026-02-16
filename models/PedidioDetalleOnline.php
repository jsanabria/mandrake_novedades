<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for pedidio_detalle_online
 */
class PedidioDetalleOnline extends DbTable
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
    public $id_documento;
    public $fabricante;
    public $articulo;
    public $lote;
    public $fecha_vencimiento;
    public $almacen;
    public $cantidad_articulo;
    public $articulo_unidad_medida;
    public $cantidad_unidad_medida;
    public $cantidad_movimiento;
    public $costo_unidad;
    public $costo;
    public $precio_unidad;
    public $precio;
    public $id_compra;
    public $alicuota;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'pedidio_detalle_online';
        $this->TableName = 'pedidio_detalle_online';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`pedidio_detalle_online`";
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
        $this->id = new DbField('pedidio_detalle_online', 'pedidio_detalle_online', 'x_id', 'id', '`id`', '`id`', 21, 20, -1, false, '`id`', false, false, false, 'FORMATTED TEXT', 'NO');
        $this->id->IsAutoIncrement = true; // Autoincrement field
        $this->id->IsPrimaryKey = true; // Primary key field
        $this->id->Sortable = true; // Allow sort
        $this->id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->id->Param, "CustomMsg");
        $this->Fields['id'] = &$this->id;

        // tipo_documento
        $this->tipo_documento = new DbField('pedidio_detalle_online', 'pedidio_detalle_online', 'x_tipo_documento', 'tipo_documento', '`tipo_documento`', '`tipo_documento`', 200, 6, -1, false, '`tipo_documento`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->tipo_documento->IsForeignKey = true; // Foreign key field
        $this->tipo_documento->Sortable = true; // Allow sort
        $this->tipo_documento->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tipo_documento->Param, "CustomMsg");
        $this->Fields['tipo_documento'] = &$this->tipo_documento;

        // id_documento
        $this->id_documento = new DbField('pedidio_detalle_online', 'pedidio_detalle_online', 'x_id_documento', 'id_documento', '`id_documento`', '`id_documento`', 19, 10, -1, false, '`id_documento`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->id_documento->IsForeignKey = true; // Foreign key field
        $this->id_documento->Sortable = true; // Allow sort
        $this->id_documento->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id_documento->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->id_documento->Param, "CustomMsg");
        $this->Fields['id_documento'] = &$this->id_documento;

        // fabricante
        $this->fabricante = new DbField('pedidio_detalle_online', 'pedidio_detalle_online', 'x_fabricante', 'fabricante', '`fabricante`', '`fabricante`', 19, 10, -1, false, '`fabricante`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->fabricante->Sortable = true; // Allow sort
        $this->fabricante->Lookup = new Lookup('fabricante', 'fabricante', false, 'Id', ["nombre","","",""], [], ["x_articulo"], [], [], [], [], '', '');
        $this->fabricante->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->fabricante->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->fabricante->Param, "CustomMsg");
        $this->Fields['fabricante'] = &$this->fabricante;

        // articulo
        $this->articulo = new DbField('pedidio_detalle_online', 'pedidio_detalle_online', 'x_articulo', 'articulo', '`articulo`', '`articulo`', 19, 10, -1, false, '`articulo`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->articulo->Sortable = true; // Allow sort
        $this->articulo->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->articulo->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->articulo->Lookup = new Lookup('articulo', 'articulo', false, 'id', ["principio_activo","presentacion","nombre_comercial",""], ["x_fabricante"], ["x_articulo_unidad_medida"], ["fabricante"], ["x_fabricante"], [], [], '', '');
        $this->articulo->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->articulo->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->articulo->Param, "CustomMsg");
        $this->Fields['articulo'] = &$this->articulo;

        // lote
        $this->lote = new DbField('pedidio_detalle_online', 'pedidio_detalle_online', 'x_lote', 'lote', '`lote`', '`lote`', 200, 20, -1, false, '`lote`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->lote->Sortable = true; // Allow sort
        $this->lote->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->lote->Param, "CustomMsg");
        $this->Fields['lote'] = &$this->lote;

        // fecha_vencimiento
        $this->fecha_vencimiento = new DbField('pedidio_detalle_online', 'pedidio_detalle_online', 'x_fecha_vencimiento', 'fecha_vencimiento', '`fecha_vencimiento`', CastDateFieldForLike("`fecha_vencimiento`", 0, "DB"), 133, 10, 0, false, '`fecha_vencimiento`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->fecha_vencimiento->Sortable = true; // Allow sort
        $this->fecha_vencimiento->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->fecha_vencimiento->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->fecha_vencimiento->Param, "CustomMsg");
        $this->Fields['fecha_vencimiento'] = &$this->fecha_vencimiento;

        // almacen
        $this->almacen = new DbField('pedidio_detalle_online', 'pedidio_detalle_online', 'x_almacen', 'almacen', '`almacen`', '`almacen`', 200, 6, -1, false, '`almacen`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->almacen->Sortable = true; // Allow sort
        $this->almacen->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->almacen->Param, "CustomMsg");
        $this->Fields['almacen'] = &$this->almacen;

        // cantidad_articulo
        $this->cantidad_articulo = new DbField('pedidio_detalle_online', 'pedidio_detalle_online', 'x_cantidad_articulo', 'cantidad_articulo', '`cantidad_articulo`', '`cantidad_articulo`', 131, 10, -1, false, '`cantidad_articulo`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->cantidad_articulo->Sortable = true; // Allow sort
        $this->cantidad_articulo->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->cantidad_articulo->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->cantidad_articulo->Param, "CustomMsg");
        $this->Fields['cantidad_articulo'] = &$this->cantidad_articulo;

        // articulo_unidad_medida
        $this->articulo_unidad_medida = new DbField('pedidio_detalle_online', 'pedidio_detalle_online', 'x_articulo_unidad_medida', 'articulo_unidad_medida', '`articulo_unidad_medida`', '`articulo_unidad_medida`', 200, 6, -1, false, '`articulo_unidad_medida`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->articulo_unidad_medida->Sortable = true; // Allow sort
        $this->articulo_unidad_medida->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->articulo_unidad_medida->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->articulo_unidad_medida->Lookup = new Lookup('articulo_unidad_medida', 'view_unidad_medida', false, 'unidad_medida', ["descripcion_unidad_medida","cantidad_por_unidad_medida","",""], ["x_articulo"], [], ["articulo"], ["x_articulo"], [], [], '', '');
        $this->articulo_unidad_medida->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->articulo_unidad_medida->Param, "CustomMsg");
        $this->Fields['articulo_unidad_medida'] = &$this->articulo_unidad_medida;

        // cantidad_unidad_medida
        $this->cantidad_unidad_medida = new DbField('pedidio_detalle_online', 'pedidio_detalle_online', 'x_cantidad_unidad_medida', 'cantidad_unidad_medida', '`cantidad_unidad_medida`', '`cantidad_unidad_medida`', 131, 10, -1, false, '`cantidad_unidad_medida`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->cantidad_unidad_medida->Sortable = true; // Allow sort
        $this->cantidad_unidad_medida->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->cantidad_unidad_medida->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->cantidad_unidad_medida->Param, "CustomMsg");
        $this->Fields['cantidad_unidad_medida'] = &$this->cantidad_unidad_medida;

        // cantidad_movimiento
        $this->cantidad_movimiento = new DbField('pedidio_detalle_online', 'pedidio_detalle_online', 'x_cantidad_movimiento', 'cantidad_movimiento', '`cantidad_movimiento`', '`cantidad_movimiento`', 131, 10, -1, false, '`cantidad_movimiento`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->cantidad_movimiento->Sortable = true; // Allow sort
        $this->cantidad_movimiento->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->cantidad_movimiento->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->cantidad_movimiento->Param, "CustomMsg");
        $this->Fields['cantidad_movimiento'] = &$this->cantidad_movimiento;

        // costo_unidad
        $this->costo_unidad = new DbField('pedidio_detalle_online', 'pedidio_detalle_online', 'x_costo_unidad', 'costo_unidad', '`costo_unidad`', '`costo_unidad`', 131, 14, -1, false, '`costo_unidad`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->costo_unidad->Sortable = true; // Allow sort
        $this->costo_unidad->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->costo_unidad->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->costo_unidad->Param, "CustomMsg");
        $this->Fields['costo_unidad'] = &$this->costo_unidad;

        // costo
        $this->costo = new DbField('pedidio_detalle_online', 'pedidio_detalle_online', 'x_costo', 'costo', '`costo`', '`costo`', 131, 14, -1, false, '`costo`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->costo->Sortable = true; // Allow sort
        $this->costo->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->costo->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->costo->Param, "CustomMsg");
        $this->Fields['costo'] = &$this->costo;

        // precio_unidad
        $this->precio_unidad = new DbField('pedidio_detalle_online', 'pedidio_detalle_online', 'x_precio_unidad', 'precio_unidad', '`precio_unidad`', '`precio_unidad`', 131, 14, -1, false, '`precio_unidad`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->precio_unidad->Sortable = true; // Allow sort
        $this->precio_unidad->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->precio_unidad->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->precio_unidad->Param, "CustomMsg");
        $this->Fields['precio_unidad'] = &$this->precio_unidad;

        // precio
        $this->precio = new DbField('pedidio_detalle_online', 'pedidio_detalle_online', 'x_precio', 'precio', '`precio`', '`precio`', 131, 14, -1, false, '`precio`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->precio->Sortable = true; // Allow sort
        $this->precio->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->precio->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->precio->Param, "CustomMsg");
        $this->Fields['precio'] = &$this->precio;

        // id_compra
        $this->id_compra = new DbField('pedidio_detalle_online', 'pedidio_detalle_online', 'x_id_compra', 'id_compra', '`id_compra`', '`id_compra`', 21, 20, -1, false, '`id_compra`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->id_compra->Sortable = true; // Allow sort
        $this->id_compra->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id_compra->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->id_compra->Param, "CustomMsg");
        $this->Fields['id_compra'] = &$this->id_compra;

        // alicuota
        $this->alicuota = new DbField('pedidio_detalle_online', 'pedidio_detalle_online', 'x_alicuota', 'alicuota', '`alicuota`', '`alicuota`', 131, 5, -1, false, '`alicuota`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->alicuota->Sortable = true; // Allow sort
        $this->alicuota->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->alicuota->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->alicuota->Param, "CustomMsg");
        $this->Fields['alicuota'] = &$this->alicuota;
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
        if ($this->getCurrentMasterTable() == "pedido_online") {
            if ($this->id_documento->getSessionValue() != "") {
                $masterFilter .= "" . GetForeignKeySql("`id`", $this->id_documento->getSessionValue(), DATATYPE_NUMBER, "DB");
            } else {
                return "";
            }
            if ($this->tipo_documento->getSessionValue() != "") {
                $masterFilter .= " AND " . GetForeignKeySql("`tipo_documento`", $this->tipo_documento->getSessionValue(), DATATYPE_STRING, "DB");
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
        if ($this->getCurrentMasterTable() == "pedido_online") {
            if ($this->id_documento->getSessionValue() != "") {
                $detailFilter .= "" . GetForeignKeySql("`id_documento`", $this->id_documento->getSessionValue(), DATATYPE_NUMBER, "DB");
            } else {
                return "";
            }
            if ($this->tipo_documento->getSessionValue() != "") {
                $detailFilter .= " AND " . GetForeignKeySql("`tipo_documento`", $this->tipo_documento->getSessionValue(), DATATYPE_STRING, "DB");
            } else {
                return "";
            }
        }
        return $detailFilter;
    }

    // Master filter
    public function sqlMasterFilter_pedido_online()
    {
        return "`id`=@id@ AND `tipo_documento`='@tipo_documento@'";
    }
    // Detail filter
    public function sqlDetailFilter_pedido_online()
    {
        return "`id_documento`=@id_documento@ AND `tipo_documento`='@tipo_documento@'";
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`pedidio_detalle_online`";
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
        $this->tipo_documento->DbValue = $row['tipo_documento'];
        $this->id_documento->DbValue = $row['id_documento'];
        $this->fabricante->DbValue = $row['fabricante'];
        $this->articulo->DbValue = $row['articulo'];
        $this->lote->DbValue = $row['lote'];
        $this->fecha_vencimiento->DbValue = $row['fecha_vencimiento'];
        $this->almacen->DbValue = $row['almacen'];
        $this->cantidad_articulo->DbValue = $row['cantidad_articulo'];
        $this->articulo_unidad_medida->DbValue = $row['articulo_unidad_medida'];
        $this->cantidad_unidad_medida->DbValue = $row['cantidad_unidad_medida'];
        $this->cantidad_movimiento->DbValue = $row['cantidad_movimiento'];
        $this->costo_unidad->DbValue = $row['costo_unidad'];
        $this->costo->DbValue = $row['costo'];
        $this->precio_unidad->DbValue = $row['precio_unidad'];
        $this->precio->DbValue = $row['precio'];
        $this->id_compra->DbValue = $row['id_compra'];
        $this->alicuota->DbValue = $row['alicuota'];
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
        return $_SESSION[$name] ?? GetUrl("PedidioDetalleOnlineList");
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
        if ($pageName == "PedidioDetalleOnlineView") {
            return $Language->phrase("View");
        } elseif ($pageName == "PedidioDetalleOnlineEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "PedidioDetalleOnlineAdd") {
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
                return "PedidioDetalleOnlineView";
            case Config("API_ADD_ACTION"):
                return "PedidioDetalleOnlineAdd";
            case Config("API_EDIT_ACTION"):
                return "PedidioDetalleOnlineEdit";
            case Config("API_DELETE_ACTION"):
                return "PedidioDetalleOnlineDelete";
            case Config("API_LIST_ACTION"):
                return "PedidioDetalleOnlineList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "PedidioDetalleOnlineList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("PedidioDetalleOnlineView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("PedidioDetalleOnlineView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "PedidioDetalleOnlineAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "PedidioDetalleOnlineAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("PedidioDetalleOnlineEdit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("PedidioDetalleOnlineAdd", $this->getUrlParm($parm));
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
        return $this->keyUrl("PedidioDetalleOnlineDelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        if ($this->getCurrentMasterTable() == "pedido_online" && !ContainsString($url, Config("TABLE_SHOW_MASTER") . "=")) {
            $url .= (ContainsString($url, "?") ? "&" : "?") . Config("TABLE_SHOW_MASTER") . "=" . $this->getCurrentMasterTable();
            $url .= "&" . GetForeignKeyUrl("fk_id", $this->id_documento->CurrentValue ?? $this->id_documento->getSessionValue());
            $url .= "&" . GetForeignKeyUrl("fk_tipo_documento", $this->tipo_documento->CurrentValue ?? $this->tipo_documento->getSessionValue());
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
        $this->id_documento->setDbValue($row['id_documento']);
        $this->fabricante->setDbValue($row['fabricante']);
        $this->articulo->setDbValue($row['articulo']);
        $this->lote->setDbValue($row['lote']);
        $this->fecha_vencimiento->setDbValue($row['fecha_vencimiento']);
        $this->almacen->setDbValue($row['almacen']);
        $this->cantidad_articulo->setDbValue($row['cantidad_articulo']);
        $this->articulo_unidad_medida->setDbValue($row['articulo_unidad_medida']);
        $this->cantidad_unidad_medida->setDbValue($row['cantidad_unidad_medida']);
        $this->cantidad_movimiento->setDbValue($row['cantidad_movimiento']);
        $this->costo_unidad->setDbValue($row['costo_unidad']);
        $this->costo->setDbValue($row['costo']);
        $this->precio_unidad->setDbValue($row['precio_unidad']);
        $this->precio->setDbValue($row['precio']);
        $this->id_compra->setDbValue($row['id_compra']);
        $this->alicuota->setDbValue($row['alicuota']);
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

        // id_documento

        // fabricante

        // articulo

        // lote

        // fecha_vencimiento

        // almacen

        // cantidad_articulo

        // articulo_unidad_medida

        // cantidad_unidad_medida

        // cantidad_movimiento

        // costo_unidad

        // costo

        // precio_unidad

        // precio

        // id_compra

        // alicuota

        // id
        $this->id->ViewValue = $this->id->CurrentValue;
        $this->id->ViewCustomAttributes = "";

        // tipo_documento
        $this->tipo_documento->ViewValue = $this->tipo_documento->CurrentValue;
        $this->tipo_documento->ViewCustomAttributes = "";

        // id_documento
        $this->id_documento->ViewValue = $this->id_documento->CurrentValue;
        $this->id_documento->ViewCustomAttributes = "";

        // fabricante
        $this->fabricante->ViewValue = $this->fabricante->CurrentValue;
        $curVal = trim(strval($this->fabricante->CurrentValue));
        if ($curVal != "") {
            $this->fabricante->ViewValue = $this->fabricante->lookupCacheOption($curVal);
            if ($this->fabricante->ViewValue === null) { // Lookup from database
                $filterWrk = "`Id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $sqlWrk = $this->fabricante->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->fabricante->Lookup->renderViewRow($rswrk[0]);
                    $this->fabricante->ViewValue = $this->fabricante->displayValue($arwrk);
                } else {
                    $this->fabricante->ViewValue = $this->fabricante->CurrentValue;
                }
            }
        } else {
            $this->fabricante->ViewValue = null;
        }
        $this->fabricante->ViewCustomAttributes = "";

        // articulo
        $curVal = trim(strval($this->articulo->CurrentValue));
        if ($curVal != "") {
            $this->articulo->ViewValue = $this->articulo->lookupCacheOption($curVal);
            if ($this->articulo->ViewValue === null) { // Lookup from database
                $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $sqlWrk = $this->articulo->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->articulo->Lookup->renderViewRow($rswrk[0]);
                    $this->articulo->ViewValue = $this->articulo->displayValue($arwrk);
                } else {
                    $this->articulo->ViewValue = $this->articulo->CurrentValue;
                }
            }
        } else {
            $this->articulo->ViewValue = null;
        }
        $this->articulo->ViewCustomAttributes = "";

        // lote
        $this->lote->ViewValue = $this->lote->CurrentValue;
        $this->lote->ViewCustomAttributes = "";

        // fecha_vencimiento
        $this->fecha_vencimiento->ViewValue = $this->fecha_vencimiento->CurrentValue;
        $this->fecha_vencimiento->ViewValue = FormatDateTime($this->fecha_vencimiento->ViewValue, 0);
        $this->fecha_vencimiento->ViewCustomAttributes = "";

        // almacen
        $this->almacen->ViewValue = $this->almacen->CurrentValue;
        $this->almacen->ViewCustomAttributes = "";

        // cantidad_articulo
        $this->cantidad_articulo->ViewValue = $this->cantidad_articulo->CurrentValue;
        $this->cantidad_articulo->ViewValue = FormatNumber($this->cantidad_articulo->ViewValue, $this->cantidad_articulo->DefaultDecimalPrecision);
        $this->cantidad_articulo->ViewCustomAttributes = "";

        // articulo_unidad_medida
        $curVal = trim(strval($this->articulo_unidad_medida->CurrentValue));
        if ($curVal != "") {
            $this->articulo_unidad_medida->ViewValue = $this->articulo_unidad_medida->lookupCacheOption($curVal);
            if ($this->articulo_unidad_medida->ViewValue === null) { // Lookup from database
                $filterWrk = "`unidad_medida`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                $sqlWrk = $this->articulo_unidad_medida->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->articulo_unidad_medida->Lookup->renderViewRow($rswrk[0]);
                    $this->articulo_unidad_medida->ViewValue = $this->articulo_unidad_medida->displayValue($arwrk);
                } else {
                    $this->articulo_unidad_medida->ViewValue = $this->articulo_unidad_medida->CurrentValue;
                }
            }
        } else {
            $this->articulo_unidad_medida->ViewValue = null;
        }
        $this->articulo_unidad_medida->ViewCustomAttributes = "";

        // cantidad_unidad_medida
        $this->cantidad_unidad_medida->ViewValue = $this->cantidad_unidad_medida->CurrentValue;
        $this->cantidad_unidad_medida->ViewValue = FormatNumber($this->cantidad_unidad_medida->ViewValue, $this->cantidad_unidad_medida->DefaultDecimalPrecision);
        $this->cantidad_unidad_medida->ViewCustomAttributes = "";

        // cantidad_movimiento
        $this->cantidad_movimiento->ViewValue = $this->cantidad_movimiento->CurrentValue;
        $this->cantidad_movimiento->ViewValue = FormatNumber($this->cantidad_movimiento->ViewValue, $this->cantidad_movimiento->DefaultDecimalPrecision);
        $this->cantidad_movimiento->ViewCustomAttributes = "";

        // costo_unidad
        $this->costo_unidad->ViewValue = $this->costo_unidad->CurrentValue;
        $this->costo_unidad->ViewValue = FormatNumber($this->costo_unidad->ViewValue, $this->costo_unidad->DefaultDecimalPrecision);
        $this->costo_unidad->ViewCustomAttributes = "";

        // costo
        $this->costo->ViewValue = $this->costo->CurrentValue;
        $this->costo->ViewValue = FormatNumber($this->costo->ViewValue, $this->costo->DefaultDecimalPrecision);
        $this->costo->ViewCustomAttributes = "";

        // precio_unidad
        $this->precio_unidad->ViewValue = $this->precio_unidad->CurrentValue;
        $this->precio_unidad->ViewValue = FormatNumber($this->precio_unidad->ViewValue, $this->precio_unidad->DefaultDecimalPrecision);
        $this->precio_unidad->ViewCustomAttributes = "";

        // precio
        $this->precio->ViewValue = $this->precio->CurrentValue;
        $this->precio->ViewValue = FormatNumber($this->precio->ViewValue, $this->precio->DefaultDecimalPrecision);
        $this->precio->ViewCustomAttributes = "";

        // id_compra
        $this->id_compra->ViewValue = $this->id_compra->CurrentValue;
        $this->id_compra->ViewCustomAttributes = "";

        // alicuota
        $this->alicuota->ViewValue = $this->alicuota->CurrentValue;
        $this->alicuota->ViewValue = FormatNumber($this->alicuota->ViewValue, $this->alicuota->DefaultDecimalPrecision);
        $this->alicuota->ViewCustomAttributes = "";

        // id
        $this->id->LinkCustomAttributes = "";
        $this->id->HrefValue = "";
        $this->id->TooltipValue = "";

        // tipo_documento
        $this->tipo_documento->LinkCustomAttributes = "";
        $this->tipo_documento->HrefValue = "";
        $this->tipo_documento->TooltipValue = "";

        // id_documento
        $this->id_documento->LinkCustomAttributes = "";
        $this->id_documento->HrefValue = "";
        $this->id_documento->TooltipValue = "";

        // fabricante
        $this->fabricante->LinkCustomAttributes = "";
        $this->fabricante->HrefValue = "";
        $this->fabricante->TooltipValue = "";

        // articulo
        $this->articulo->LinkCustomAttributes = "";
        $this->articulo->HrefValue = "";
        $this->articulo->TooltipValue = "";

        // lote
        $this->lote->LinkCustomAttributes = "";
        $this->lote->HrefValue = "";
        $this->lote->TooltipValue = "";

        // fecha_vencimiento
        $this->fecha_vencimiento->LinkCustomAttributes = "";
        $this->fecha_vencimiento->HrefValue = "";
        $this->fecha_vencimiento->TooltipValue = "";

        // almacen
        $this->almacen->LinkCustomAttributes = "";
        $this->almacen->HrefValue = "";
        $this->almacen->TooltipValue = "";

        // cantidad_articulo
        $this->cantidad_articulo->LinkCustomAttributes = "";
        $this->cantidad_articulo->HrefValue = "";
        $this->cantidad_articulo->TooltipValue = "";

        // articulo_unidad_medida
        $this->articulo_unidad_medida->LinkCustomAttributes = "";
        $this->articulo_unidad_medida->HrefValue = "";
        $this->articulo_unidad_medida->TooltipValue = "";

        // cantidad_unidad_medida
        $this->cantidad_unidad_medida->LinkCustomAttributes = "";
        $this->cantidad_unidad_medida->HrefValue = "";
        $this->cantidad_unidad_medida->TooltipValue = "";

        // cantidad_movimiento
        $this->cantidad_movimiento->LinkCustomAttributes = "";
        $this->cantidad_movimiento->HrefValue = "";
        $this->cantidad_movimiento->TooltipValue = "";

        // costo_unidad
        $this->costo_unidad->LinkCustomAttributes = "";
        $this->costo_unidad->HrefValue = "";
        $this->costo_unidad->TooltipValue = "";

        // costo
        $this->costo->LinkCustomAttributes = "";
        $this->costo->HrefValue = "";
        $this->costo->TooltipValue = "";

        // precio_unidad
        $this->precio_unidad->LinkCustomAttributes = "";
        $this->precio_unidad->HrefValue = "";
        $this->precio_unidad->TooltipValue = "";

        // precio
        $this->precio->LinkCustomAttributes = "";
        $this->precio->HrefValue = "";
        $this->precio->TooltipValue = "";

        // id_compra
        $this->id_compra->LinkCustomAttributes = "";
        $this->id_compra->HrefValue = "";
        $this->id_compra->TooltipValue = "";

        // alicuota
        $this->alicuota->LinkCustomAttributes = "";
        $this->alicuota->HrefValue = "";
        $this->alicuota->TooltipValue = "";

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
        if ($this->tipo_documento->getSessionValue() != "") {
            $this->tipo_documento->CurrentValue = GetForeignKeyValue($this->tipo_documento->getSessionValue());
            $this->tipo_documento->ViewValue = $this->tipo_documento->CurrentValue;
            $this->tipo_documento->ViewCustomAttributes = "";
        } else {
            if (!$this->tipo_documento->Raw) {
                $this->tipo_documento->CurrentValue = HtmlDecode($this->tipo_documento->CurrentValue);
            }
            $this->tipo_documento->EditValue = $this->tipo_documento->CurrentValue;
            $this->tipo_documento->PlaceHolder = RemoveHtml($this->tipo_documento->caption());
        }

        // id_documento
        $this->id_documento->EditAttrs["class"] = "form-control";
        $this->id_documento->EditCustomAttributes = "";
        if ($this->id_documento->getSessionValue() != "") {
            $this->id_documento->CurrentValue = GetForeignKeyValue($this->id_documento->getSessionValue());
            $this->id_documento->ViewValue = $this->id_documento->CurrentValue;
            $this->id_documento->ViewCustomAttributes = "";
        } else {
            $this->id_documento->EditValue = $this->id_documento->CurrentValue;
            $this->id_documento->PlaceHolder = RemoveHtml($this->id_documento->caption());
        }

        // fabricante
        $this->fabricante->EditAttrs["class"] = "form-control";
        $this->fabricante->EditCustomAttributes = "";
        $this->fabricante->EditValue = $this->fabricante->CurrentValue;
        $this->fabricante->PlaceHolder = RemoveHtml($this->fabricante->caption());

        // articulo
        $this->articulo->EditAttrs["class"] = "form-control";
        $this->articulo->EditCustomAttributes = "";
        $this->articulo->PlaceHolder = RemoveHtml($this->articulo->caption());

        // lote
        $this->lote->EditAttrs["class"] = "form-control";
        $this->lote->EditCustomAttributes = "";
        if (!$this->lote->Raw) {
            $this->lote->CurrentValue = HtmlDecode($this->lote->CurrentValue);
        }
        $this->lote->EditValue = $this->lote->CurrentValue;
        $this->lote->PlaceHolder = RemoveHtml($this->lote->caption());

        // fecha_vencimiento
        $this->fecha_vencimiento->EditAttrs["class"] = "form-control";
        $this->fecha_vencimiento->EditCustomAttributes = "";
        $this->fecha_vencimiento->EditValue = FormatDateTime($this->fecha_vencimiento->CurrentValue, 8);
        $this->fecha_vencimiento->PlaceHolder = RemoveHtml($this->fecha_vencimiento->caption());

        // almacen
        $this->almacen->EditAttrs["class"] = "form-control";
        $this->almacen->EditCustomAttributes = "";
        if (!$this->almacen->Raw) {
            $this->almacen->CurrentValue = HtmlDecode($this->almacen->CurrentValue);
        }
        $this->almacen->EditValue = $this->almacen->CurrentValue;
        $this->almacen->PlaceHolder = RemoveHtml($this->almacen->caption());

        // cantidad_articulo
        $this->cantidad_articulo->EditAttrs["class"] = "form-control";
        $this->cantidad_articulo->EditCustomAttributes = "";
        $this->cantidad_articulo->EditValue = $this->cantidad_articulo->CurrentValue;
        $this->cantidad_articulo->PlaceHolder = RemoveHtml($this->cantidad_articulo->caption());
        if (strval($this->cantidad_articulo->EditValue) != "" && is_numeric($this->cantidad_articulo->EditValue)) {
            $this->cantidad_articulo->EditValue = FormatNumber($this->cantidad_articulo->EditValue, -2, -1, -2, 0);
        }

        // articulo_unidad_medida
        $this->articulo_unidad_medida->EditAttrs["class"] = "form-control";
        $this->articulo_unidad_medida->EditCustomAttributes = "";
        $this->articulo_unidad_medida->PlaceHolder = RemoveHtml($this->articulo_unidad_medida->caption());

        // cantidad_unidad_medida
        $this->cantidad_unidad_medida->EditAttrs["class"] = "form-control";
        $this->cantidad_unidad_medida->EditCustomAttributes = "";
        $this->cantidad_unidad_medida->EditValue = $this->cantidad_unidad_medida->CurrentValue;
        $this->cantidad_unidad_medida->PlaceHolder = RemoveHtml($this->cantidad_unidad_medida->caption());
        if (strval($this->cantidad_unidad_medida->EditValue) != "" && is_numeric($this->cantidad_unidad_medida->EditValue)) {
            $this->cantidad_unidad_medida->EditValue = FormatNumber($this->cantidad_unidad_medida->EditValue, -2, -1, -2, 0);
        }

        // cantidad_movimiento
        $this->cantidad_movimiento->EditAttrs["class"] = "form-control";
        $this->cantidad_movimiento->EditCustomAttributes = "";
        $this->cantidad_movimiento->EditValue = $this->cantidad_movimiento->CurrentValue;
        $this->cantidad_movimiento->PlaceHolder = RemoveHtml($this->cantidad_movimiento->caption());
        if (strval($this->cantidad_movimiento->EditValue) != "" && is_numeric($this->cantidad_movimiento->EditValue)) {
            $this->cantidad_movimiento->EditValue = FormatNumber($this->cantidad_movimiento->EditValue, -2, -1, -2, 0);
        }

        // costo_unidad
        $this->costo_unidad->EditAttrs["class"] = "form-control";
        $this->costo_unidad->EditCustomAttributes = "";
        $this->costo_unidad->EditValue = $this->costo_unidad->CurrentValue;
        $this->costo_unidad->PlaceHolder = RemoveHtml($this->costo_unidad->caption());
        if (strval($this->costo_unidad->EditValue) != "" && is_numeric($this->costo_unidad->EditValue)) {
            $this->costo_unidad->EditValue = FormatNumber($this->costo_unidad->EditValue, -2, -1, -2, 0);
        }

        // costo
        $this->costo->EditAttrs["class"] = "form-control";
        $this->costo->EditCustomAttributes = "";
        $this->costo->EditValue = $this->costo->CurrentValue;
        $this->costo->PlaceHolder = RemoveHtml($this->costo->caption());
        if (strval($this->costo->EditValue) != "" && is_numeric($this->costo->EditValue)) {
            $this->costo->EditValue = FormatNumber($this->costo->EditValue, -2, -1, -2, 0);
        }

        // precio_unidad
        $this->precio_unidad->EditAttrs["class"] = "form-control";
        $this->precio_unidad->EditCustomAttributes = "";
        $this->precio_unidad->EditValue = $this->precio_unidad->CurrentValue;
        $this->precio_unidad->PlaceHolder = RemoveHtml($this->precio_unidad->caption());
        if (strval($this->precio_unidad->EditValue) != "" && is_numeric($this->precio_unidad->EditValue)) {
            $this->precio_unidad->EditValue = FormatNumber($this->precio_unidad->EditValue, -2, -1, -2, 0);
        }

        // precio
        $this->precio->EditAttrs["class"] = "form-control";
        $this->precio->EditCustomAttributes = "";
        $this->precio->EditValue = $this->precio->CurrentValue;
        $this->precio->PlaceHolder = RemoveHtml($this->precio->caption());
        if (strval($this->precio->EditValue) != "" && is_numeric($this->precio->EditValue)) {
            $this->precio->EditValue = FormatNumber($this->precio->EditValue, -2, -1, -2, 0);
        }

        // id_compra
        $this->id_compra->EditAttrs["class"] = "form-control";
        $this->id_compra->EditCustomAttributes = "";
        $this->id_compra->EditValue = $this->id_compra->CurrentValue;
        $this->id_compra->PlaceHolder = RemoveHtml($this->id_compra->caption());

        // alicuota
        $this->alicuota->EditAttrs["class"] = "form-control";
        $this->alicuota->EditCustomAttributes = "";
        $this->alicuota->EditValue = $this->alicuota->CurrentValue;
        $this->alicuota->PlaceHolder = RemoveHtml($this->alicuota->caption());
        if (strval($this->alicuota->EditValue) != "" && is_numeric($this->alicuota->EditValue)) {
            $this->alicuota->EditValue = FormatNumber($this->alicuota->EditValue, -2, -1, -2, 0);
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
                    $doc->exportCaption($this->fabricante);
                    $doc->exportCaption($this->articulo);
                } else {
                    $doc->exportCaption($this->id);
                    $doc->exportCaption($this->tipo_documento);
                    $doc->exportCaption($this->id_documento);
                    $doc->exportCaption($this->fabricante);
                    $doc->exportCaption($this->articulo);
                    $doc->exportCaption($this->lote);
                    $doc->exportCaption($this->fecha_vencimiento);
                    $doc->exportCaption($this->almacen);
                    $doc->exportCaption($this->cantidad_articulo);
                    $doc->exportCaption($this->articulo_unidad_medida);
                    $doc->exportCaption($this->cantidad_unidad_medida);
                    $doc->exportCaption($this->cantidad_movimiento);
                    $doc->exportCaption($this->costo_unidad);
                    $doc->exportCaption($this->costo);
                    $doc->exportCaption($this->precio_unidad);
                    $doc->exportCaption($this->precio);
                    $doc->exportCaption($this->id_compra);
                    $doc->exportCaption($this->alicuota);
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
                        $doc->exportField($this->fabricante);
                        $doc->exportField($this->articulo);
                    } else {
                        $doc->exportField($this->id);
                        $doc->exportField($this->tipo_documento);
                        $doc->exportField($this->id_documento);
                        $doc->exportField($this->fabricante);
                        $doc->exportField($this->articulo);
                        $doc->exportField($this->lote);
                        $doc->exportField($this->fecha_vencimiento);
                        $doc->exportField($this->almacen);
                        $doc->exportField($this->cantidad_articulo);
                        $doc->exportField($this->articulo_unidad_medida);
                        $doc->exportField($this->cantidad_unidad_medida);
                        $doc->exportField($this->cantidad_movimiento);
                        $doc->exportField($this->costo_unidad);
                        $doc->exportField($this->costo);
                        $doc->exportField($this->precio_unidad);
                        $doc->exportField($this->precio);
                        $doc->exportField($this->id_compra);
                        $doc->exportField($this->alicuota);
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
        $table = 'pedidio_detalle_online';
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
        $table = 'pedidio_detalle_online';

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
        $table = 'pedidio_detalle_online';

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
        $table = 'pedidio_detalle_online';

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
