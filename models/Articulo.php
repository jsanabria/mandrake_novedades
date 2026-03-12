<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for articulo
 */
class Articulo extends DbTable
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
    public $codigo_ims;
    public $codigo;
    public $nombre_comercial;
    public $principio_activo;
    public $presentacion;
    public $fabricante;
    public $codigo_de_barra;
    public $categoria;
    public $lista_pedido;
    public $unidad_medida_defecto;
    public $cantidad_por_unidad_medida;
    public $foto;
    public $cantidad_minima;
    public $cantidad_maxima;
    public $cantidad_en_mano;
    public $cantidad_en_pedido;
    public $cantidad_en_transito;
    public $ultimo_costo;
    public $descuento;
    public $precio;
    public $precio2;
    public $alicuota;
    public $articulo_inventario;
    public $activo;
    public $puntos_ventas;
    public $puntos_premio;
    public $sincroniza;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'articulo';
        $this->TableName = 'articulo';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`articulo`";
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
        $this->id = new DbField('articulo', 'articulo', 'x_id', 'id', '`id`', '`id`', 19, 11, -1, false, '`id`', false, false, false, 'FORMATTED TEXT', 'NO');
        $this->id->IsAutoIncrement = true; // Autoincrement field
        $this->id->IsPrimaryKey = true; // Primary key field
        $this->id->IsForeignKey = true; // Foreign key field
        $this->id->Sortable = true; // Allow sort
        $this->id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->id->Param, "CustomMsg");
        $this->Fields['id'] = &$this->id;

        // codigo_ims
        $this->codigo_ims = new DbField('articulo', 'articulo', 'x_codigo_ims', 'codigo_ims', '`codigo_ims`', '`codigo_ims`', 200, 50, -1, false, '`codigo_ims`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->codigo_ims->Required = true; // Required field
        $this->codigo_ims->Sortable = true; // Allow sort
        $this->codigo_ims->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->codigo_ims->Param, "CustomMsg");
        $this->Fields['codigo_ims'] = &$this->codigo_ims;

        // codigo
        $this->codigo = new DbField('articulo', 'articulo', 'x_codigo', 'codigo', '`codigo`', '`codigo`', 200, 50, -1, false, '`codigo`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->codigo->Sortable = true; // Allow sort
        $this->codigo->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->codigo->Param, "CustomMsg");
        $this->Fields['codigo'] = &$this->codigo;

        // nombre_comercial
        $this->nombre_comercial = new DbField('articulo', 'articulo', 'x_nombre_comercial', 'nombre_comercial', '`nombre_comercial`', '`nombre_comercial`', 200, 50, -1, false, '`nombre_comercial`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->nombre_comercial->Sortable = true; // Allow sort
        $this->nombre_comercial->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->nombre_comercial->Param, "CustomMsg");
        $this->Fields['nombre_comercial'] = &$this->nombre_comercial;

        // principio_activo
        $this->principio_activo = new DbField('articulo', 'articulo', 'x_principio_activo', 'principio_activo', '`principio_activo`', '`principio_activo`', 200, 100, -1, false, '`principio_activo`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->principio_activo->Required = true; // Required field
        $this->principio_activo->Sortable = true; // Allow sort
        $this->principio_activo->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->principio_activo->Param, "CustomMsg");
        $this->Fields['principio_activo'] = &$this->principio_activo;

        // presentacion
        $this->presentacion = new DbField('articulo', 'articulo', 'x_presentacion', 'presentacion', '`presentacion`', '`presentacion`', 200, 50, -1, false, '`presentacion`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->presentacion->Required = true; // Required field
        $this->presentacion->Sortable = true; // Allow sort
        $this->presentacion->Lookup = new Lookup('presentacion', 'articulo', true, 'presentacion', ["presentacion","","",""], [], [], [], [], [], [], '`presentacion`', '');
        $this->presentacion->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->presentacion->Param, "CustomMsg");
        $this->Fields['presentacion'] = &$this->presentacion;

        // fabricante
        $this->fabricante = new DbField('articulo', 'articulo', 'x_fabricante', 'fabricante', '`fabricante`', '`fabricante`', 19, 10, -1, false, '`fabricante`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->fabricante->Required = true; // Required field
        $this->fabricante->Sortable = true; // Allow sort
        $this->fabricante->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->fabricante->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->fabricante->Lookup = new Lookup('fabricante', 'fabricante', false, 'Id', ["nombre","","",""], [], [], [], [], [], [], '', '');
        $this->fabricante->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->fabricante->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->fabricante->Param, "CustomMsg");
        $this->Fields['fabricante'] = &$this->fabricante;

        // codigo_de_barra
        $this->codigo_de_barra = new DbField('articulo', 'articulo', 'x_codigo_de_barra', 'codigo_de_barra', '`codigo_de_barra`', '`codigo_de_barra`', 200, 50, -1, false, '`codigo_de_barra`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->codigo_de_barra->Sortable = true; // Allow sort
        $this->codigo_de_barra->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->codigo_de_barra->Param, "CustomMsg");
        $this->Fields['codigo_de_barra'] = &$this->codigo_de_barra;

        // categoria
        $this->categoria = new DbField('articulo', 'articulo', 'x_categoria', 'categoria', '`categoria`', '`categoria`', 200, 6, -1, false, '`categoria`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->categoria->Sortable = true; // Allow sort
        $this->categoria->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->categoria->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->categoria->Lookup = new Lookup('categoria', 'tabla', false, 'campo_codigo', ["campo_descripcion","","",""], [], [], [], [], [], [], '`campo_descripcion`', '');
        $this->categoria->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->categoria->Param, "CustomMsg");
        $this->Fields['categoria'] = &$this->categoria;

        // lista_pedido
        $this->lista_pedido = new DbField('articulo', 'articulo', 'x_lista_pedido', 'lista_pedido', '`lista_pedido`', '`lista_pedido`', 200, 6, -1, false, '`lista_pedido`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->lista_pedido->Required = true; // Required field
        $this->lista_pedido->Sortable = true; // Allow sort
        $this->lista_pedido->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->lista_pedido->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->lista_pedido->Lookup = new Lookup('lista_pedido', 'tabla', false, 'campo_codigo', ["campo_descripcion","","",""], [], [], [], [], [], [], '`campo_descripcion` ASC', '');
        $this->lista_pedido->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->lista_pedido->Param, "CustomMsg");
        $this->Fields['lista_pedido'] = &$this->lista_pedido;

        // unidad_medida_defecto
        $this->unidad_medida_defecto = new DbField('articulo', 'articulo', 'x_unidad_medida_defecto', 'unidad_medida_defecto', '`unidad_medida_defecto`', '`unidad_medida_defecto`', 200, 6, -1, false, '`unidad_medida_defecto`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->unidad_medida_defecto->Required = true; // Required field
        $this->unidad_medida_defecto->Sortable = true; // Allow sort
        $this->unidad_medida_defecto->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->unidad_medida_defecto->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->unidad_medida_defecto->Lookup = new Lookup('unidad_medida_defecto', 'unidad_medida', false, 'codigo', ["descripcion","","",""], [], ["x_cantidad_por_unidad_medida"], [], [], [], [], '`descripcion`', '');
        $this->unidad_medida_defecto->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->unidad_medida_defecto->Param, "CustomMsg");
        $this->Fields['unidad_medida_defecto'] = &$this->unidad_medida_defecto;

        // cantidad_por_unidad_medida
        $this->cantidad_por_unidad_medida = new DbField('articulo', 'articulo', 'x_cantidad_por_unidad_medida', 'cantidad_por_unidad_medida', '`cantidad_por_unidad_medida`', '`cantidad_por_unidad_medida`', 131, 10, -1, false, '`cantidad_por_unidad_medida`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->cantidad_por_unidad_medida->Required = true; // Required field
        $this->cantidad_por_unidad_medida->Sortable = true; // Allow sort
        $this->cantidad_por_unidad_medida->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->cantidad_por_unidad_medida->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->cantidad_por_unidad_medida->Lookup = new Lookup('cantidad_por_unidad_medida', 'unidad_medida', false, 'cantidad', ["cantidad","","",""], ["x_unidad_medida_defecto"], [], ["codigo"], ["x_codigo"], [], [], '', '');
        $this->cantidad_por_unidad_medida->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->cantidad_por_unidad_medida->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->cantidad_por_unidad_medida->Param, "CustomMsg");
        $this->Fields['cantidad_por_unidad_medida'] = &$this->cantidad_por_unidad_medida;

        // foto
        $this->foto = new DbField('articulo', 'articulo', 'x_foto', 'foto', '`foto`', '`foto`', 200, 250, -1, true, '`foto`', false, false, false, 'IMAGE', 'FILE');
        $this->foto->Sortable = true; // Allow sort
        $this->foto->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->foto->Param, "CustomMsg");
        $this->Fields['foto'] = &$this->foto;

        // cantidad_minima
        $this->cantidad_minima = new DbField('articulo', 'articulo', 'x_cantidad_minima', 'cantidad_minima', '`cantidad_minima`', '`cantidad_minima`', 131, 9, -1, false, '`cantidad_minima`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->cantidad_minima->Nullable = false; // NOT NULL field
        $this->cantidad_minima->Sortable = true; // Allow sort
        $this->cantidad_minima->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->cantidad_minima->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->cantidad_minima->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->cantidad_minima->Param, "CustomMsg");
        $this->Fields['cantidad_minima'] = &$this->cantidad_minima;

        // cantidad_maxima
        $this->cantidad_maxima = new DbField('articulo', 'articulo', 'x_cantidad_maxima', 'cantidad_maxima', '`cantidad_maxima`', '`cantidad_maxima`', 131, 9, -1, false, '`cantidad_maxima`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->cantidad_maxima->Nullable = false; // NOT NULL field
        $this->cantidad_maxima->Sortable = true; // Allow sort
        $this->cantidad_maxima->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->cantidad_maxima->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->cantidad_maxima->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->cantidad_maxima->Param, "CustomMsg");
        $this->Fields['cantidad_maxima'] = &$this->cantidad_maxima;

        // cantidad_en_mano
        $this->cantidad_en_mano = new DbField('articulo', 'articulo', 'x_cantidad_en_mano', 'cantidad_en_mano', '`cantidad_en_mano`', '`cantidad_en_mano`', 131, 9, -1, false, '`cantidad_en_mano`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->cantidad_en_mano->Nullable = false; // NOT NULL field
        $this->cantidad_en_mano->Sortable = true; // Allow sort
        $this->cantidad_en_mano->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->cantidad_en_mano->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->cantidad_en_mano->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->cantidad_en_mano->Param, "CustomMsg");
        $this->Fields['cantidad_en_mano'] = &$this->cantidad_en_mano;

        // cantidad_en_pedido
        $this->cantidad_en_pedido = new DbField('articulo', 'articulo', 'x_cantidad_en_pedido', 'cantidad_en_pedido', '`cantidad_en_pedido`', '`cantidad_en_pedido`', 131, 9, -1, false, '`cantidad_en_pedido`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->cantidad_en_pedido->Nullable = false; // NOT NULL field
        $this->cantidad_en_pedido->Sortable = true; // Allow sort
        $this->cantidad_en_pedido->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->cantidad_en_pedido->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->cantidad_en_pedido->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->cantidad_en_pedido->Param, "CustomMsg");
        $this->Fields['cantidad_en_pedido'] = &$this->cantidad_en_pedido;

        // cantidad_en_transito
        $this->cantidad_en_transito = new DbField('articulo', 'articulo', 'x_cantidad_en_transito', 'cantidad_en_transito', '`cantidad_en_transito`', '`cantidad_en_transito`', 131, 9, -1, false, '`cantidad_en_transito`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->cantidad_en_transito->Nullable = false; // NOT NULL field
        $this->cantidad_en_transito->Sortable = true; // Allow sort
        $this->cantidad_en_transito->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->cantidad_en_transito->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->cantidad_en_transito->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->cantidad_en_transito->Param, "CustomMsg");
        $this->Fields['cantidad_en_transito'] = &$this->cantidad_en_transito;

        // ultimo_costo
        $this->ultimo_costo = new DbField('articulo', 'articulo', 'x_ultimo_costo', 'ultimo_costo', '`ultimo_costo`', '`ultimo_costo`', 131, 14, -1, false, '`ultimo_costo`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->ultimo_costo->Nullable = false; // NOT NULL field
        $this->ultimo_costo->Sortable = true; // Allow sort
        $this->ultimo_costo->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->ultimo_costo->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->ultimo_costo->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->ultimo_costo->Param, "CustomMsg");
        $this->Fields['ultimo_costo'] = &$this->ultimo_costo;

        // descuento
        $this->descuento = new DbField('articulo', 'articulo', 'x_descuento', 'descuento', '`descuento`', '`descuento`', 131, 6, -1, false, '`descuento`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->descuento->Nullable = false; // NOT NULL field
        $this->descuento->Sortable = true; // Allow sort
        $this->descuento->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->descuento->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->descuento->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->descuento->Param, "CustomMsg");
        $this->Fields['descuento'] = &$this->descuento;

        // precio
        $this->precio = new DbField('articulo', 'articulo', 'x_precio', 'precio', '`precio`', '`precio`', 131, 14, -1, false, '`precio`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->precio->Nullable = false; // NOT NULL field
        $this->precio->Sortable = true; // Allow sort
        $this->precio->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->precio->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->precio->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->precio->Param, "CustomMsg");
        $this->Fields['precio'] = &$this->precio;

        // precio2
        $this->precio2 = new DbField('articulo', 'articulo', 'x_precio2', 'precio2', '`precio2`', '`precio2`', 131, 14, -1, false, '`precio2`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->precio2->Nullable = false; // NOT NULL field
        $this->precio2->Sortable = true; // Allow sort
        $this->precio2->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->precio2->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->precio2->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->precio2->Param, "CustomMsg");
        $this->Fields['precio2'] = &$this->precio2;

        // alicuota
        $this->alicuota = new DbField('articulo', 'articulo', 'x_alicuota', 'alicuota', '`alicuota`', '`alicuota`', 200, 3, -1, false, '`alicuota`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->alicuota->Required = true; // Required field
        $this->alicuota->Sortable = true; // Allow sort
        $this->alicuota->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->alicuota->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->alicuota->Lookup = new Lookup('alicuota', 'alicuota', false, 'codigo', ["nombre","alicuota","",""], [], [], [], [], [], [], '', '');
        $this->alicuota->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->alicuota->Param, "CustomMsg");
        $this->Fields['alicuota'] = &$this->alicuota;

        // articulo_inventario
        $this->articulo_inventario = new DbField('articulo', 'articulo', 'x_articulo_inventario', 'articulo_inventario', '`articulo_inventario`', '`articulo_inventario`', 202, 1, -1, false, '`articulo_inventario`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->articulo_inventario->Required = true; // Required field
        $this->articulo_inventario->Sortable = true; // Allow sort
        $this->articulo_inventario->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->articulo_inventario->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->articulo_inventario->Lookup = new Lookup('articulo_inventario', 'articulo', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->articulo_inventario->OptionCount = 2;
        $this->articulo_inventario->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->articulo_inventario->Param, "CustomMsg");
        $this->Fields['articulo_inventario'] = &$this->articulo_inventario;

        // activo
        $this->activo = new DbField('articulo', 'articulo', 'x_activo', 'activo', '`activo`', '`activo`', 202, 1, -1, false, '`activo`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->activo->Required = true; // Required field
        $this->activo->Sortable = true; // Allow sort
        $this->activo->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->activo->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->activo->Lookup = new Lookup('activo', 'articulo', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->activo->OptionCount = 2;
        $this->activo->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->activo->Param, "CustomMsg");
        $this->Fields['activo'] = &$this->activo;

        // puntos_ventas
        $this->puntos_ventas = new DbField('articulo', 'articulo', 'x_puntos_ventas', 'puntos_ventas', '`puntos_ventas`', '`puntos_ventas`', 3, 11, -1, false, '`puntos_ventas`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->puntos_ventas->Nullable = false; // NOT NULL field
        $this->puntos_ventas->Sortable = true; // Allow sort
        $this->puntos_ventas->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->puntos_ventas->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->puntos_ventas->Param, "CustomMsg");
        $this->Fields['puntos_ventas'] = &$this->puntos_ventas;

        // puntos_premio
        $this->puntos_premio = new DbField('articulo', 'articulo', 'x_puntos_premio', 'puntos_premio', '`puntos_premio`', '`puntos_premio`', 3, 11, -1, false, '`puntos_premio`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->puntos_premio->Nullable = false; // NOT NULL field
        $this->puntos_premio->Sortable = true; // Allow sort
        $this->puntos_premio->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->puntos_premio->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->puntos_premio->Param, "CustomMsg");
        $this->Fields['puntos_premio'] = &$this->puntos_premio;

        // sincroniza
        $this->sincroniza = new DbField('articulo', 'articulo', 'x_sincroniza', 'sincroniza', '`sincroniza`', '`sincroniza`', 202, 1, -1, false, '`sincroniza`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->sincroniza->Required = true; // Required field
        $this->sincroniza->Sortable = true; // Allow sort
        $this->sincroniza->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->sincroniza->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->sincroniza->Lookup = new Lookup('sincroniza', 'articulo', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->sincroniza->OptionCount = 2;
        $this->sincroniza->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->sincroniza->Param, "CustomMsg");
        $this->Fields['sincroniza'] = &$this->sincroniza;
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
        if ($this->getCurrentDetailTable() == "articulo_unidad_medida") {
            $detailUrl = Container("articulo_unidad_medida")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue);
        }
        if ($this->getCurrentDetailTable() == "adjunto") {
            $detailUrl = Container("adjunto")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue);
        }
        if ($detailUrl == "") {
            $detailUrl = "ArticuloList";
        }
        return $detailUrl;
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`articulo`";
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
        // Cascade Update detail table 'articulo_unidad_medida'
        $cascadeUpdate = false;
        $rscascade = [];
        if ($rsold && (isset($rs['id']) && $rsold['id'] != $rs['id'])) { // Update detail field 'articulo'
            $cascadeUpdate = true;
            $rscascade['articulo'] = $rs['id'];
        }
        if ($cascadeUpdate) {
            $rswrk = Container("articulo_unidad_medida")->loadRs("`articulo` = " . QuotedValue($rsold['id'], DATATYPE_NUMBER, 'DB'))->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rswrk as $rsdtlold) {
                $rskey = [];
                $fldname = 'id';
                $rskey[$fldname] = $rsdtlold[$fldname];
                $rsdtlnew = array_merge($rsdtlold, $rscascade);
                // Call Row_Updating event
                $success = Container("articulo_unidad_medida")->rowUpdating($rsdtlold, $rsdtlnew);
                if ($success) {
                    $success = Container("articulo_unidad_medida")->update($rscascade, $rskey, $rsdtlold);
                }
                if (!$success) {
                    return false;
                }
                // Call Row_Updated event
                Container("articulo_unidad_medida")->rowUpdated($rsdtlold, $rsdtlnew);
            }
        }

        // Cascade Update detail table 'adjunto'
        $cascadeUpdate = false;
        $rscascade = [];
        if ($rsold && (isset($rs['id']) && $rsold['id'] != $rs['id'])) { // Update detail field 'articulo'
            $cascadeUpdate = true;
            $rscascade['articulo'] = $rs['id'];
        }
        if ($cascadeUpdate) {
            $rswrk = Container("adjunto")->loadRs("`articulo` = " . QuotedValue($rsold['id'], DATATYPE_NUMBER, 'DB'))->fetchAll(\PDO::FETCH_ASSOC);
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

        // Cascade delete detail table 'articulo_unidad_medida'
        $dtlrows = Container("articulo_unidad_medida")->loadRs("`articulo` = " . QuotedValue($rs['id'], DATATYPE_NUMBER, "DB"))->fetchAll(\PDO::FETCH_ASSOC);
        // Call Row Deleting event
        foreach ($dtlrows as $dtlrow) {
            $success = Container("articulo_unidad_medida")->rowDeleting($dtlrow);
            if (!$success) {
                break;
            }
        }
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                $success = Container("articulo_unidad_medida")->delete($dtlrow); // Delete
                if (!$success) {
                    break;
                }
            }
        }
        // Call Row Deleted event
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                Container("articulo_unidad_medida")->rowDeleted($dtlrow);
            }
        }

        // Cascade delete detail table 'adjunto'
        $dtlrows = Container("adjunto")->loadRs("`articulo` = " . QuotedValue($rs['id'], DATATYPE_NUMBER, "DB"))->fetchAll(\PDO::FETCH_ASSOC);
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
        $this->codigo_ims->DbValue = $row['codigo_ims'];
        $this->codigo->DbValue = $row['codigo'];
        $this->nombre_comercial->DbValue = $row['nombre_comercial'];
        $this->principio_activo->DbValue = $row['principio_activo'];
        $this->presentacion->DbValue = $row['presentacion'];
        $this->fabricante->DbValue = $row['fabricante'];
        $this->codigo_de_barra->DbValue = $row['codigo_de_barra'];
        $this->categoria->DbValue = $row['categoria'];
        $this->lista_pedido->DbValue = $row['lista_pedido'];
        $this->unidad_medida_defecto->DbValue = $row['unidad_medida_defecto'];
        $this->cantidad_por_unidad_medida->DbValue = $row['cantidad_por_unidad_medida'];
        $this->foto->Upload->DbValue = $row['foto'];
        $this->cantidad_minima->DbValue = $row['cantidad_minima'];
        $this->cantidad_maxima->DbValue = $row['cantidad_maxima'];
        $this->cantidad_en_mano->DbValue = $row['cantidad_en_mano'];
        $this->cantidad_en_pedido->DbValue = $row['cantidad_en_pedido'];
        $this->cantidad_en_transito->DbValue = $row['cantidad_en_transito'];
        $this->ultimo_costo->DbValue = $row['ultimo_costo'];
        $this->descuento->DbValue = $row['descuento'];
        $this->precio->DbValue = $row['precio'];
        $this->precio2->DbValue = $row['precio2'];
        $this->alicuota->DbValue = $row['alicuota'];
        $this->articulo_inventario->DbValue = $row['articulo_inventario'];
        $this->activo->DbValue = $row['activo'];
        $this->puntos_ventas->DbValue = $row['puntos_ventas'];
        $this->puntos_premio->DbValue = $row['puntos_premio'];
        $this->sincroniza->DbValue = $row['sincroniza'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
        $oldFiles = EmptyValue($row['foto']) ? [] : [$row['foto']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->foto->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->foto->oldPhysicalUploadPath() . $oldFile);
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
        return $_SESSION[$name] ?? GetUrl("ArticuloList");
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
        if ($pageName == "ArticuloView") {
            return $Language->phrase("View");
        } elseif ($pageName == "ArticuloEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "ArticuloAdd") {
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
                return "ArticuloView";
            case Config("API_ADD_ACTION"):
                return "ArticuloAdd";
            case Config("API_EDIT_ACTION"):
                return "ArticuloEdit";
            case Config("API_DELETE_ACTION"):
                return "ArticuloDelete";
            case Config("API_LIST_ACTION"):
                return "ArticuloList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "ArticuloList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ArticuloView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("ArticuloView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "ArticuloAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "ArticuloAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ArticuloEdit", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("ArticuloEdit", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
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
            $url = $this->keyUrl("ArticuloAdd", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("ArticuloAdd", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
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
        return $this->keyUrl("ArticuloDelete", $this->getUrlParm());
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
        $this->codigo_ims->setDbValue($row['codigo_ims']);
        $this->codigo->setDbValue($row['codigo']);
        $this->nombre_comercial->setDbValue($row['nombre_comercial']);
        $this->principio_activo->setDbValue($row['principio_activo']);
        $this->presentacion->setDbValue($row['presentacion']);
        $this->fabricante->setDbValue($row['fabricante']);
        $this->codigo_de_barra->setDbValue($row['codigo_de_barra']);
        $this->categoria->setDbValue($row['categoria']);
        $this->lista_pedido->setDbValue($row['lista_pedido']);
        $this->unidad_medida_defecto->setDbValue($row['unidad_medida_defecto']);
        $this->cantidad_por_unidad_medida->setDbValue($row['cantidad_por_unidad_medida']);
        $this->foto->Upload->DbValue = $row['foto'];
        $this->foto->setDbValue($this->foto->Upload->DbValue);
        $this->cantidad_minima->setDbValue($row['cantidad_minima']);
        $this->cantidad_maxima->setDbValue($row['cantidad_maxima']);
        $this->cantidad_en_mano->setDbValue($row['cantidad_en_mano']);
        $this->cantidad_en_pedido->setDbValue($row['cantidad_en_pedido']);
        $this->cantidad_en_transito->setDbValue($row['cantidad_en_transito']);
        $this->ultimo_costo->setDbValue($row['ultimo_costo']);
        $this->descuento->setDbValue($row['descuento']);
        $this->precio->setDbValue($row['precio']);
        $this->precio2->setDbValue($row['precio2']);
        $this->alicuota->setDbValue($row['alicuota']);
        $this->articulo_inventario->setDbValue($row['articulo_inventario']);
        $this->activo->setDbValue($row['activo']);
        $this->puntos_ventas->setDbValue($row['puntos_ventas']);
        $this->puntos_premio->setDbValue($row['puntos_premio']);
        $this->sincroniza->setDbValue($row['sincroniza']);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // id

        // codigo_ims

        // codigo

        // nombre_comercial

        // principio_activo

        // presentacion

        // fabricante

        // codigo_de_barra

        // categoria

        // lista_pedido

        // unidad_medida_defecto

        // cantidad_por_unidad_medida

        // foto

        // cantidad_minima

        // cantidad_maxima

        // cantidad_en_mano

        // cantidad_en_pedido

        // cantidad_en_transito

        // ultimo_costo

        // descuento

        // precio

        // precio2

        // alicuota

        // articulo_inventario

        // activo

        // puntos_ventas

        // puntos_premio

        // sincroniza

        // id
        $this->id->ViewValue = $this->id->CurrentValue;
        $this->id->ViewCustomAttributes = "";

        // codigo_ims
        $this->codigo_ims->ViewValue = $this->codigo_ims->CurrentValue;
        $this->codigo_ims->CssClass = "font-weight-bold font-italic";
        $this->codigo_ims->ViewCustomAttributes = "";

        // codigo
        $this->codigo->ViewValue = $this->codigo->CurrentValue;
        $this->codigo->ViewCustomAttributes = "";

        // nombre_comercial
        $this->nombre_comercial->ViewValue = $this->nombre_comercial->CurrentValue;
        $this->nombre_comercial->ViewCustomAttributes = "";

        // principio_activo
        $this->principio_activo->ViewValue = $this->principio_activo->CurrentValue;
        $this->principio_activo->ViewCustomAttributes = "";

        // presentacion
        $this->presentacion->ViewValue = $this->presentacion->CurrentValue;
        $this->presentacion->ViewCustomAttributes = "";

        // fabricante
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

        // codigo_de_barra
        $this->codigo_de_barra->ViewValue = $this->codigo_de_barra->CurrentValue;
        $this->codigo_de_barra->ViewCustomAttributes = "";

        // categoria
        $curVal = trim(strval($this->categoria->CurrentValue));
        if ($curVal != "") {
            $this->categoria->ViewValue = $this->categoria->lookupCacheOption($curVal);
            if ($this->categoria->ViewValue === null) { // Lookup from database
                $filterWrk = "`campo_codigo`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                $lookupFilter = function() {
                    return "`tabla` = 'CATEGORIA'";
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->categoria->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->categoria->Lookup->renderViewRow($rswrk[0]);
                    $this->categoria->ViewValue = $this->categoria->displayValue($arwrk);
                } else {
                    $this->categoria->ViewValue = $this->categoria->CurrentValue;
                }
            }
        } else {
            $this->categoria->ViewValue = null;
        }
        $this->categoria->ViewCustomAttributes = "";

        // lista_pedido
        $curVal = trim(strval($this->lista_pedido->CurrentValue));
        if ($curVal != "") {
            $this->lista_pedido->ViewValue = $this->lista_pedido->lookupCacheOption($curVal);
            if ($this->lista_pedido->ViewValue === null) { // Lookup from database
                $filterWrk = "`campo_codigo`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                $lookupFilter = function() {
                    return "`tabla` = 'LISTA_PEDIDO'";
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->lista_pedido->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->lista_pedido->Lookup->renderViewRow($rswrk[0]);
                    $this->lista_pedido->ViewValue = $this->lista_pedido->displayValue($arwrk);
                } else {
                    $this->lista_pedido->ViewValue = $this->lista_pedido->CurrentValue;
                }
            }
        } else {
            $this->lista_pedido->ViewValue = null;
        }
        $this->lista_pedido->ViewCustomAttributes = "";

        // unidad_medida_defecto
        $curVal = trim(strval($this->unidad_medida_defecto->CurrentValue));
        if ($curVal != "") {
            $this->unidad_medida_defecto->ViewValue = $this->unidad_medida_defecto->lookupCacheOption($curVal);
            if ($this->unidad_medida_defecto->ViewValue === null) { // Lookup from database
                $filterWrk = "`codigo`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                $lookupFilter = function() {
                    return "`codigo` = 'UDM001'";
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->unidad_medida_defecto->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->unidad_medida_defecto->Lookup->renderViewRow($rswrk[0]);
                    $this->unidad_medida_defecto->ViewValue = $this->unidad_medida_defecto->displayValue($arwrk);
                } else {
                    $this->unidad_medida_defecto->ViewValue = $this->unidad_medida_defecto->CurrentValue;
                }
            }
        } else {
            $this->unidad_medida_defecto->ViewValue = null;
        }
        $this->unidad_medida_defecto->ViewCustomAttributes = "";

        // cantidad_por_unidad_medida
        $curVal = trim(strval($this->cantidad_por_unidad_medida->CurrentValue));
        if ($curVal != "") {
            $this->cantidad_por_unidad_medida->ViewValue = $this->cantidad_por_unidad_medida->lookupCacheOption($curVal);
            if ($this->cantidad_por_unidad_medida->ViewValue === null) { // Lookup from database
                $filterWrk = "`cantidad`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $sqlWrk = $this->cantidad_por_unidad_medida->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->cantidad_por_unidad_medida->Lookup->renderViewRow($rswrk[0]);
                    $this->cantidad_por_unidad_medida->ViewValue = $this->cantidad_por_unidad_medida->displayValue($arwrk);
                } else {
                    $this->cantidad_por_unidad_medida->ViewValue = $this->cantidad_por_unidad_medida->CurrentValue;
                }
            }
        } else {
            $this->cantidad_por_unidad_medida->ViewValue = null;
        }
        $this->cantidad_por_unidad_medida->ViewCustomAttributes = "";

        // foto
        if (!EmptyValue($this->foto->Upload->DbValue)) {
            $this->foto->ImageWidth = 120;
            $this->foto->ImageHeight = 120;
            $this->foto->ImageAlt = $this->foto->alt();
            $this->foto->ViewValue = $this->foto->Upload->DbValue;
        } else {
            $this->foto->ViewValue = "";
        }
        $this->foto->ViewCustomAttributes = "";

        // cantidad_minima
        $this->cantidad_minima->ViewValue = $this->cantidad_minima->CurrentValue;
        $this->cantidad_minima->ViewValue = FormatNumber($this->cantidad_minima->ViewValue, 2, -1, -1, -1);
        $this->cantidad_minima->ViewCustomAttributes = "";

        // cantidad_maxima
        $this->cantidad_maxima->ViewValue = $this->cantidad_maxima->CurrentValue;
        $this->cantidad_maxima->ViewValue = FormatNumber($this->cantidad_maxima->ViewValue, 2, -1, -1, -1);
        $this->cantidad_maxima->ViewCustomAttributes = "";

        // cantidad_en_mano
        $this->cantidad_en_mano->ViewValue = $this->cantidad_en_mano->CurrentValue;
        $this->cantidad_en_mano->ViewValue = FormatNumber($this->cantidad_en_mano->ViewValue, 2, -1, -1, -1);
        $this->cantidad_en_mano->ViewCustomAttributes = "";

        // cantidad_en_pedido
        $this->cantidad_en_pedido->ViewValue = $this->cantidad_en_pedido->CurrentValue;
        $this->cantidad_en_pedido->ViewValue = FormatNumber($this->cantidad_en_pedido->ViewValue, 2, -1, -1, -1);
        $this->cantidad_en_pedido->ViewCustomAttributes = "";

        // cantidad_en_transito
        $this->cantidad_en_transito->ViewValue = $this->cantidad_en_transito->CurrentValue;
        $this->cantidad_en_transito->ViewValue = FormatNumber($this->cantidad_en_transito->ViewValue, 2, -1, -1, -1);
        $this->cantidad_en_transito->ViewCustomAttributes = "";

        // ultimo_costo
        $this->ultimo_costo->ViewValue = $this->ultimo_costo->CurrentValue;
        $this->ultimo_costo->ViewValue = FormatNumber($this->ultimo_costo->ViewValue, 2, -1, -1, -1);
        $this->ultimo_costo->ViewCustomAttributes = "";

        // descuento
        $this->descuento->ViewValue = $this->descuento->CurrentValue;
        $this->descuento->ViewValue = FormatNumber($this->descuento->ViewValue, 2, -1, -1, -1);
        $this->descuento->ViewCustomAttributes = "";

        // precio
        $this->precio->ViewValue = $this->precio->CurrentValue;
        $this->precio->ViewValue = FormatNumber($this->precio->ViewValue, 2, -1, -1, -1);
        $this->precio->ViewCustomAttributes = "";

        // precio2
        $this->precio2->ViewValue = $this->precio2->CurrentValue;
        $this->precio2->ViewValue = FormatNumber($this->precio2->ViewValue, 2, -2, -2, -2);
        $this->precio2->ViewCustomAttributes = "";

        // alicuota
        $curVal = trim(strval($this->alicuota->CurrentValue));
        if ($curVal != "") {
            $this->alicuota->ViewValue = $this->alicuota->lookupCacheOption($curVal);
            if ($this->alicuota->ViewValue === null) { // Lookup from database
                $filterWrk = "`codigo`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                $lookupFilter = function() {
                    return "`activo` = 'S'";
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->alicuota->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->alicuota->Lookup->renderViewRow($rswrk[0]);
                    $this->alicuota->ViewValue = $this->alicuota->displayValue($arwrk);
                } else {
                    $this->alicuota->ViewValue = $this->alicuota->CurrentValue;
                }
            }
        } else {
            $this->alicuota->ViewValue = null;
        }
        $this->alicuota->ViewCustomAttributes = "";

        // articulo_inventario
        if (strval($this->articulo_inventario->CurrentValue) != "") {
            $this->articulo_inventario->ViewValue = $this->articulo_inventario->optionCaption($this->articulo_inventario->CurrentValue);
        } else {
            $this->articulo_inventario->ViewValue = null;
        }
        $this->articulo_inventario->ViewCustomAttributes = "";

        // activo
        if (strval($this->activo->CurrentValue) != "") {
            $this->activo->ViewValue = $this->activo->optionCaption($this->activo->CurrentValue);
        } else {
            $this->activo->ViewValue = null;
        }
        $this->activo->ViewCustomAttributes = "";

        // puntos_ventas
        $this->puntos_ventas->ViewValue = $this->puntos_ventas->CurrentValue;
        $this->puntos_ventas->ViewValue = FormatNumber($this->puntos_ventas->ViewValue, 0, -2, -2, -2);
        $this->puntos_ventas->ViewCustomAttributes = "";

        // puntos_premio
        $this->puntos_premio->ViewValue = $this->puntos_premio->CurrentValue;
        $this->puntos_premio->ViewValue = FormatNumber($this->puntos_premio->ViewValue, 0, -2, -2, -2);
        $this->puntos_premio->ViewCustomAttributes = "";

        // sincroniza
        if (strval($this->sincroniza->CurrentValue) != "") {
            $this->sincroniza->ViewValue = $this->sincroniza->optionCaption($this->sincroniza->CurrentValue);
        } else {
            $this->sincroniza->ViewValue = null;
        }
        $this->sincroniza->ViewCustomAttributes = "";

        // id
        $this->id->LinkCustomAttributes = "";
        $this->id->HrefValue = "";
        $this->id->TooltipValue = "";

        // codigo_ims
        $this->codigo_ims->LinkCustomAttributes = "";
        $this->codigo_ims->HrefValue = "";
        $this->codigo_ims->TooltipValue = "";

        // codigo
        $this->codigo->LinkCustomAttributes = "";
        $this->codigo->HrefValue = "";
        $this->codigo->TooltipValue = "";

        // nombre_comercial
        $this->nombre_comercial->LinkCustomAttributes = "";
        $this->nombre_comercial->HrefValue = "";
        $this->nombre_comercial->TooltipValue = "";

        // principio_activo
        $this->principio_activo->LinkCustomAttributes = "";
        $this->principio_activo->HrefValue = "";
        $this->principio_activo->TooltipValue = "";

        // presentacion
        $this->presentacion->LinkCustomAttributes = "";
        $this->presentacion->HrefValue = "";
        $this->presentacion->TooltipValue = "";

        // fabricante
        $this->fabricante->LinkCustomAttributes = "";
        $this->fabricante->HrefValue = "";
        $this->fabricante->TooltipValue = "";

        // codigo_de_barra
        $this->codigo_de_barra->LinkCustomAttributes = "";
        $this->codigo_de_barra->HrefValue = "";
        $this->codigo_de_barra->TooltipValue = "";

        // categoria
        $this->categoria->LinkCustomAttributes = "";
        $this->categoria->HrefValue = "";
        $this->categoria->TooltipValue = "";

        // lista_pedido
        $this->lista_pedido->LinkCustomAttributes = "";
        $this->lista_pedido->HrefValue = "";
        $this->lista_pedido->TooltipValue = "";

        // unidad_medida_defecto
        $this->unidad_medida_defecto->LinkCustomAttributes = "";
        $this->unidad_medida_defecto->HrefValue = "";
        $this->unidad_medida_defecto->TooltipValue = "";

        // cantidad_por_unidad_medida
        $this->cantidad_por_unidad_medida->LinkCustomAttributes = "";
        $this->cantidad_por_unidad_medida->HrefValue = "";
        $this->cantidad_por_unidad_medida->TooltipValue = "";

        // foto
        $this->foto->LinkCustomAttributes = "";
        if (!EmptyValue($this->foto->Upload->DbValue)) {
            $this->foto->HrefValue = GetFileUploadUrl($this->foto, $this->foto->htmlDecode($this->foto->Upload->DbValue)); // Add prefix/suffix
            $this->foto->LinkAttrs["target"] = "_blank"; // Add target
            if ($this->isExport()) {
                $this->foto->HrefValue = FullUrl($this->foto->HrefValue, "href");
            }
        } else {
            $this->foto->HrefValue = "";
        }
        $this->foto->ExportHrefValue = $this->foto->UploadPath . $this->foto->Upload->DbValue;
        $this->foto->TooltipValue = "";
        if ($this->foto->UseColorbox) {
            if (EmptyValue($this->foto->TooltipValue)) {
                $this->foto->LinkAttrs["title"] = $Language->phrase("ViewImageGallery");
            }
            $this->foto->LinkAttrs["data-rel"] = "articulo_x_foto";
            $this->foto->LinkAttrs->appendClass("ew-lightbox");
        }

        // cantidad_minima
        $this->cantidad_minima->LinkCustomAttributes = "";
        $this->cantidad_minima->HrefValue = "";
        $this->cantidad_minima->TooltipValue = "";

        // cantidad_maxima
        $this->cantidad_maxima->LinkCustomAttributes = "";
        $this->cantidad_maxima->HrefValue = "";
        $this->cantidad_maxima->TooltipValue = "";

        // cantidad_en_mano
        $this->cantidad_en_mano->LinkCustomAttributes = "";
        $this->cantidad_en_mano->HrefValue = "";
        $this->cantidad_en_mano->TooltipValue = "";

        // cantidad_en_pedido
        $this->cantidad_en_pedido->LinkCustomAttributes = "";
        $this->cantidad_en_pedido->HrefValue = "";
        $this->cantidad_en_pedido->TooltipValue = "";

        // cantidad_en_transito
        $this->cantidad_en_transito->LinkCustomAttributes = "";
        $this->cantidad_en_transito->HrefValue = "";
        $this->cantidad_en_transito->TooltipValue = "";

        // ultimo_costo
        $this->ultimo_costo->LinkCustomAttributes = "";
        $this->ultimo_costo->HrefValue = "";
        $this->ultimo_costo->TooltipValue = "";

        // descuento
        $this->descuento->LinkCustomAttributes = "";
        $this->descuento->HrefValue = "";
        $this->descuento->TooltipValue = "";

        // precio
        $this->precio->LinkCustomAttributes = "";
        $this->precio->HrefValue = "";
        $this->precio->TooltipValue = "";

        // precio2
        $this->precio2->LinkCustomAttributes = "";
        $this->precio2->HrefValue = "";
        $this->precio2->TooltipValue = "";

        // alicuota
        $this->alicuota->LinkCustomAttributes = "";
        $this->alicuota->HrefValue = "";
        $this->alicuota->TooltipValue = "";

        // articulo_inventario
        $this->articulo_inventario->LinkCustomAttributes = "";
        $this->articulo_inventario->HrefValue = "";
        $this->articulo_inventario->TooltipValue = "";

        // activo
        $this->activo->LinkCustomAttributes = "";
        $this->activo->HrefValue = "";
        $this->activo->TooltipValue = "";

        // puntos_ventas
        $this->puntos_ventas->LinkCustomAttributes = "";
        $this->puntos_ventas->HrefValue = "";
        $this->puntos_ventas->TooltipValue = "";

        // puntos_premio
        $this->puntos_premio->LinkCustomAttributes = "";
        $this->puntos_premio->HrefValue = "";
        $this->puntos_premio->TooltipValue = "";

        // sincroniza
        $this->sincroniza->LinkCustomAttributes = "";
        $this->sincroniza->HrefValue = "";
        $this->sincroniza->TooltipValue = "";

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

        // codigo_ims
        $this->codigo_ims->EditAttrs["class"] = "form-control";
        $this->codigo_ims->EditCustomAttributes = "";
        if (!$this->codigo_ims->Raw) {
            $this->codigo_ims->CurrentValue = HtmlDecode($this->codigo_ims->CurrentValue);
        }
        $this->codigo_ims->EditValue = $this->codigo_ims->CurrentValue;
        $this->codigo_ims->PlaceHolder = RemoveHtml($this->codigo_ims->caption());

        // codigo
        $this->codigo->EditAttrs["class"] = "form-control";
        $this->codigo->EditCustomAttributes = "";
        if (!$this->codigo->Raw) {
            $this->codigo->CurrentValue = HtmlDecode($this->codigo->CurrentValue);
        }
        $this->codigo->EditValue = $this->codigo->CurrentValue;
        $this->codigo->PlaceHolder = RemoveHtml($this->codigo->caption());

        // nombre_comercial
        $this->nombre_comercial->EditAttrs["class"] = "form-control";
        $this->nombre_comercial->EditCustomAttributes = "";
        if (!$this->nombre_comercial->Raw) {
            $this->nombre_comercial->CurrentValue = HtmlDecode($this->nombre_comercial->CurrentValue);
        }
        $this->nombre_comercial->EditValue = $this->nombre_comercial->CurrentValue;
        $this->nombre_comercial->PlaceHolder = RemoveHtml($this->nombre_comercial->caption());

        // principio_activo
        $this->principio_activo->EditAttrs["class"] = "form-control";
        $this->principio_activo->EditCustomAttributes = "";
        if (!$this->principio_activo->Raw) {
            $this->principio_activo->CurrentValue = HtmlDecode($this->principio_activo->CurrentValue);
        }
        $this->principio_activo->EditValue = $this->principio_activo->CurrentValue;
        $this->principio_activo->PlaceHolder = RemoveHtml($this->principio_activo->caption());

        // presentacion
        $this->presentacion->EditAttrs["class"] = "form-control";
        $this->presentacion->EditCustomAttributes = "";
        if (!$this->presentacion->Raw) {
            $this->presentacion->CurrentValue = HtmlDecode($this->presentacion->CurrentValue);
        }
        $this->presentacion->EditValue = $this->presentacion->CurrentValue;
        $this->presentacion->PlaceHolder = RemoveHtml($this->presentacion->caption());

        // fabricante
        $this->fabricante->EditAttrs["class"] = "form-control";
        $this->fabricante->EditCustomAttributes = "";
        $this->fabricante->PlaceHolder = RemoveHtml($this->fabricante->caption());

        // codigo_de_barra
        $this->codigo_de_barra->EditAttrs["class"] = "form-control";
        $this->codigo_de_barra->EditCustomAttributes = "";
        if (!$this->codigo_de_barra->Raw) {
            $this->codigo_de_barra->CurrentValue = HtmlDecode($this->codigo_de_barra->CurrentValue);
        }
        $this->codigo_de_barra->EditValue = $this->codigo_de_barra->CurrentValue;
        $this->codigo_de_barra->PlaceHolder = RemoveHtml($this->codigo_de_barra->caption());

        // categoria
        $this->categoria->EditAttrs["class"] = "form-control";
        $this->categoria->EditCustomAttributes = "";
        $this->categoria->PlaceHolder = RemoveHtml($this->categoria->caption());

        // lista_pedido
        $this->lista_pedido->EditAttrs["class"] = "form-control";
        $this->lista_pedido->EditCustomAttributes = "";
        $this->lista_pedido->PlaceHolder = RemoveHtml($this->lista_pedido->caption());

        // unidad_medida_defecto
        $this->unidad_medida_defecto->EditAttrs["class"] = "form-control";
        $this->unidad_medida_defecto->EditCustomAttributes = "";
        $this->unidad_medida_defecto->PlaceHolder = RemoveHtml($this->unidad_medida_defecto->caption());

        // cantidad_por_unidad_medida
        $this->cantidad_por_unidad_medida->EditAttrs["class"] = "form-control";
        $this->cantidad_por_unidad_medida->EditCustomAttributes = "";
        $this->cantidad_por_unidad_medida->PlaceHolder = RemoveHtml($this->cantidad_por_unidad_medida->caption());

        // foto
        $this->foto->EditAttrs["class"] = "form-control";
        $this->foto->EditCustomAttributes = "";
        if (!EmptyValue($this->foto->Upload->DbValue)) {
            $this->foto->ImageWidth = 120;
            $this->foto->ImageHeight = 120;
            $this->foto->ImageAlt = $this->foto->alt();
            $this->foto->EditValue = $this->foto->Upload->DbValue;
        } else {
            $this->foto->EditValue = "";
        }
        if (!EmptyValue($this->foto->CurrentValue)) {
            $this->foto->Upload->FileName = $this->foto->CurrentValue;
        }

        // cantidad_minima
        $this->cantidad_minima->EditAttrs["class"] = "form-control";
        $this->cantidad_minima->EditCustomAttributes = "";
        $this->cantidad_minima->EditValue = $this->cantidad_minima->CurrentValue;
        $this->cantidad_minima->PlaceHolder = RemoveHtml($this->cantidad_minima->caption());
        if (strval($this->cantidad_minima->EditValue) != "" && is_numeric($this->cantidad_minima->EditValue)) {
            $this->cantidad_minima->EditValue = FormatNumber($this->cantidad_minima->EditValue, -2, -1, -2, -1);
        }

        // cantidad_maxima
        $this->cantidad_maxima->EditAttrs["class"] = "form-control";
        $this->cantidad_maxima->EditCustomAttributes = "";
        $this->cantidad_maxima->EditValue = $this->cantidad_maxima->CurrentValue;
        $this->cantidad_maxima->PlaceHolder = RemoveHtml($this->cantidad_maxima->caption());
        if (strval($this->cantidad_maxima->EditValue) != "" && is_numeric($this->cantidad_maxima->EditValue)) {
            $this->cantidad_maxima->EditValue = FormatNumber($this->cantidad_maxima->EditValue, -2, -1, -2, -1);
        }

        // cantidad_en_mano
        $this->cantidad_en_mano->EditAttrs["class"] = "form-control";
        $this->cantidad_en_mano->EditCustomAttributes = "";
        $this->cantidad_en_mano->EditValue = $this->cantidad_en_mano->CurrentValue;
        $this->cantidad_en_mano->PlaceHolder = RemoveHtml($this->cantidad_en_mano->caption());
        if (strval($this->cantidad_en_mano->EditValue) != "" && is_numeric($this->cantidad_en_mano->EditValue)) {
            $this->cantidad_en_mano->EditValue = FormatNumber($this->cantidad_en_mano->EditValue, -2, -1, -2, -1);
        }

        // cantidad_en_pedido
        $this->cantidad_en_pedido->EditAttrs["class"] = "form-control";
        $this->cantidad_en_pedido->EditCustomAttributes = "";
        $this->cantidad_en_pedido->EditValue = $this->cantidad_en_pedido->CurrentValue;
        $this->cantidad_en_pedido->PlaceHolder = RemoveHtml($this->cantidad_en_pedido->caption());
        if (strval($this->cantidad_en_pedido->EditValue) != "" && is_numeric($this->cantidad_en_pedido->EditValue)) {
            $this->cantidad_en_pedido->EditValue = FormatNumber($this->cantidad_en_pedido->EditValue, -2, -1, -2, -1);
        }

        // cantidad_en_transito
        $this->cantidad_en_transito->EditAttrs["class"] = "form-control";
        $this->cantidad_en_transito->EditCustomAttributes = "";
        $this->cantidad_en_transito->EditValue = $this->cantidad_en_transito->CurrentValue;
        $this->cantidad_en_transito->PlaceHolder = RemoveHtml($this->cantidad_en_transito->caption());
        if (strval($this->cantidad_en_transito->EditValue) != "" && is_numeric($this->cantidad_en_transito->EditValue)) {
            $this->cantidad_en_transito->EditValue = FormatNumber($this->cantidad_en_transito->EditValue, -2, -1, -2, -1);
        }

        // ultimo_costo
        $this->ultimo_costo->EditAttrs["class"] = "form-control";
        $this->ultimo_costo->EditCustomAttributes = "";
        $this->ultimo_costo->EditValue = $this->ultimo_costo->CurrentValue;
        $this->ultimo_costo->PlaceHolder = RemoveHtml($this->ultimo_costo->caption());
        if (strval($this->ultimo_costo->EditValue) != "" && is_numeric($this->ultimo_costo->EditValue)) {
            $this->ultimo_costo->EditValue = FormatNumber($this->ultimo_costo->EditValue, -2, -1, -2, -1);
        }

        // descuento
        $this->descuento->EditAttrs["class"] = "form-control";
        $this->descuento->EditCustomAttributes = "";
        $this->descuento->EditValue = $this->descuento->CurrentValue;
        $this->descuento->PlaceHolder = RemoveHtml($this->descuento->caption());
        if (strval($this->descuento->EditValue) != "" && is_numeric($this->descuento->EditValue)) {
            $this->descuento->EditValue = FormatNumber($this->descuento->EditValue, -2, -1, -2, -1);
        }

        // precio
        $this->precio->EditAttrs["class"] = "form-control";
        $this->precio->EditCustomAttributes = "";
        $this->precio->EditValue = $this->precio->CurrentValue;
        $this->precio->PlaceHolder = RemoveHtml($this->precio->caption());
        if (strval($this->precio->EditValue) != "" && is_numeric($this->precio->EditValue)) {
            $this->precio->EditValue = FormatNumber($this->precio->EditValue, -2, -1, -2, -1);
        }

        // precio2
        $this->precio2->EditAttrs["class"] = "form-control";
        $this->precio2->EditCustomAttributes = "";
        $this->precio2->EditValue = $this->precio2->CurrentValue;
        $this->precio2->PlaceHolder = RemoveHtml($this->precio2->caption());
        if (strval($this->precio2->EditValue) != "" && is_numeric($this->precio2->EditValue)) {
            $this->precio2->EditValue = FormatNumber($this->precio2->EditValue, -2, -2, -2, -2);
        }

        // alicuota
        $this->alicuota->EditAttrs["class"] = "form-control";
        $this->alicuota->EditCustomAttributes = "";
        $this->alicuota->PlaceHolder = RemoveHtml($this->alicuota->caption());

        // articulo_inventario
        $this->articulo_inventario->EditAttrs["class"] = "form-control";
        $this->articulo_inventario->EditCustomAttributes = "";
        $this->articulo_inventario->EditValue = $this->articulo_inventario->options(true);
        $this->articulo_inventario->PlaceHolder = RemoveHtml($this->articulo_inventario->caption());

        // activo
        $this->activo->EditAttrs["class"] = "form-control";
        $this->activo->EditCustomAttributes = "";
        $this->activo->EditValue = $this->activo->options(true);
        $this->activo->PlaceHolder = RemoveHtml($this->activo->caption());

        // puntos_ventas
        $this->puntos_ventas->EditAttrs["class"] = "form-control";
        $this->puntos_ventas->EditCustomAttributes = "";
        $this->puntos_ventas->EditValue = $this->puntos_ventas->CurrentValue;
        $this->puntos_ventas->PlaceHolder = RemoveHtml($this->puntos_ventas->caption());

        // puntos_premio
        $this->puntos_premio->EditAttrs["class"] = "form-control";
        $this->puntos_premio->EditCustomAttributes = "";
        $this->puntos_premio->EditValue = $this->puntos_premio->CurrentValue;
        $this->puntos_premio->PlaceHolder = RemoveHtml($this->puntos_premio->caption());

        // sincroniza
        $this->sincroniza->EditAttrs["class"] = "form-control";
        $this->sincroniza->EditCustomAttributes = "";
        $this->sincroniza->EditValue = $this->sincroniza->options(true);
        $this->sincroniza->PlaceHolder = RemoveHtml($this->sincroniza->caption());

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
                    $doc->exportCaption($this->principio_activo);
                    $doc->exportCaption($this->fabricante);
                    $doc->exportCaption($this->codigo_de_barra);
                    $doc->exportCaption($this->unidad_medida_defecto);
                    $doc->exportCaption($this->cantidad_por_unidad_medida);
                    $doc->exportCaption($this->foto);
                    $doc->exportCaption($this->cantidad_minima);
                    $doc->exportCaption($this->cantidad_maxima);
                    $doc->exportCaption($this->cantidad_en_mano);
                    $doc->exportCaption($this->cantidad_en_pedido);
                    $doc->exportCaption($this->cantidad_en_transito);
                    $doc->exportCaption($this->ultimo_costo);
                    $doc->exportCaption($this->descuento);
                    $doc->exportCaption($this->precio);
                    $doc->exportCaption($this->precio2);
                    $doc->exportCaption($this->alicuota);
                    $doc->exportCaption($this->articulo_inventario);
                    $doc->exportCaption($this->activo);
                    $doc->exportCaption($this->puntos_ventas);
                    $doc->exportCaption($this->puntos_premio);
                    $doc->exportCaption($this->sincroniza);
                } else {
                    $doc->exportCaption($this->id);
                    $doc->exportCaption($this->codigo_ims);
                    $doc->exportCaption($this->codigo);
                    $doc->exportCaption($this->nombre_comercial);
                    $doc->exportCaption($this->principio_activo);
                    $doc->exportCaption($this->presentacion);
                    $doc->exportCaption($this->fabricante);
                    $doc->exportCaption($this->codigo_de_barra);
                    $doc->exportCaption($this->categoria);
                    $doc->exportCaption($this->lista_pedido);
                    $doc->exportCaption($this->unidad_medida_defecto);
                    $doc->exportCaption($this->cantidad_por_unidad_medida);
                    $doc->exportCaption($this->cantidad_minima);
                    $doc->exportCaption($this->cantidad_maxima);
                    $doc->exportCaption($this->cantidad_en_mano);
                    $doc->exportCaption($this->cantidad_en_pedido);
                    $doc->exportCaption($this->cantidad_en_transito);
                    $doc->exportCaption($this->ultimo_costo);
                    $doc->exportCaption($this->descuento);
                    $doc->exportCaption($this->precio);
                    $doc->exportCaption($this->precio2);
                    $doc->exportCaption($this->alicuota);
                    $doc->exportCaption($this->articulo_inventario);
                    $doc->exportCaption($this->activo);
                    $doc->exportCaption($this->puntos_ventas);
                    $doc->exportCaption($this->puntos_premio);
                    $doc->exportCaption($this->sincroniza);
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
                        $doc->exportField($this->principio_activo);
                        $doc->exportField($this->fabricante);
                        $doc->exportField($this->codigo_de_barra);
                        $doc->exportField($this->unidad_medida_defecto);
                        $doc->exportField($this->cantidad_por_unidad_medida);
                        $doc->exportField($this->foto);
                        $doc->exportField($this->cantidad_minima);
                        $doc->exportField($this->cantidad_maxima);
                        $doc->exportField($this->cantidad_en_mano);
                        $doc->exportField($this->cantidad_en_pedido);
                        $doc->exportField($this->cantidad_en_transito);
                        $doc->exportField($this->ultimo_costo);
                        $doc->exportField($this->descuento);
                        $doc->exportField($this->precio);
                        $doc->exportField($this->precio2);
                        $doc->exportField($this->alicuota);
                        $doc->exportField($this->articulo_inventario);
                        $doc->exportField($this->activo);
                        $doc->exportField($this->puntos_ventas);
                        $doc->exportField($this->puntos_premio);
                        $doc->exportField($this->sincroniza);
                    } else {
                        $doc->exportField($this->id);
                        $doc->exportField($this->codigo_ims);
                        $doc->exportField($this->codigo);
                        $doc->exportField($this->nombre_comercial);
                        $doc->exportField($this->principio_activo);
                        $doc->exportField($this->presentacion);
                        $doc->exportField($this->fabricante);
                        $doc->exportField($this->codigo_de_barra);
                        $doc->exportField($this->categoria);
                        $doc->exportField($this->lista_pedido);
                        $doc->exportField($this->unidad_medida_defecto);
                        $doc->exportField($this->cantidad_por_unidad_medida);
                        $doc->exportField($this->cantidad_minima);
                        $doc->exportField($this->cantidad_maxima);
                        $doc->exportField($this->cantidad_en_mano);
                        $doc->exportField($this->cantidad_en_pedido);
                        $doc->exportField($this->cantidad_en_transito);
                        $doc->exportField($this->ultimo_costo);
                        $doc->exportField($this->descuento);
                        $doc->exportField($this->precio);
                        $doc->exportField($this->precio2);
                        $doc->exportField($this->alicuota);
                        $doc->exportField($this->articulo_inventario);
                        $doc->exportField($this->activo);
                        $doc->exportField($this->puntos_ventas);
                        $doc->exportField($this->puntos_premio);
                        $doc->exportField($this->sincroniza);
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
        if ($fldparm == 'foto') {
            $fldName = "foto";
            $fileNameFld = "foto";
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
        $table = 'articulo';
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
        $table = 'articulo';

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
        $table = 'articulo';

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
        $table = 'articulo';

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
    	$rsnew["principio_activo"] = strtoupper($rsnew["principio_activo"]);
    	if(trim($rsnew["codigo"]) != "") {
    		$sql = "SELECT COUNT(codigo) AS cantidad FROM articulo WHERE codigo = '" . $rsnew["codigo"] . "';";
    		$cantidad = ExecuteScalar($sql);
    		if($cantidad > 0) {
    			$this->CancelMessage = "C&oacute;digo \"" . $rsnew["codigo"] . "\" ya existe.";
    			return FALSE;
    		}
    	}
    	if(trim($rsnew["codigo_de_barra"]) != "") {
    		$sql = "SELECT COUNT(codigo_de_barra) AS cantidad FROM articulo WHERE codigo_de_barra = '" . $rsnew["codigo_de_barra"] . "';";
    		$cantidad = ExecuteScalar($sql);
    		if($cantidad > 0) {
    			$this->CancelMessage = "C&oacute;digo de barra \"" . $rsnew["codigo_de_barra"] . "\" ya existe.";
    			return FALSE;
    		}
    	}
    	if(trim($rsnew["codigo_ims"]) != "") {
    		$sql = "SELECT COUNT(codigo_ims) AS cantidad FROM articulo WHERE codigo_ims = '" . $rsnew["codigo_ims"] . "';";
    		$cantidad = ExecuteScalar($sql);
    		if($cantidad > 0) {
    			$this->CancelMessage = "C&oacute;digo Ref# \"" . $rsnew["codigo_ims"] . "\" ya existe.";
    			return FALSE;
    		}
    	}
    	return TRUE;
    }

    // Row Inserted event
    public function rowInserted($rsold, &$rsnew) {
    	//echo "Row Inserted"
    	$sql = "INSERT INTO articulo_unidad_medida
    				(id, articulo, unidad_medida)
    			SELECT
    				NULL, id, unidad_medida_defecto 
    			FROM articulo WHERE id = '" . $rsnew["id"] . "';";
    	Execute($sql);
    }

    // Row Updating event
    public function rowUpdating($rsold, &$rsnew) {
    	// Enter your code here
    	// To cancel, set return value to FALSE
    	$rsnew["principio_activo"] = strtoupper($rsnew["principio_activo"]);
    	if(trim($rsnew["codigo"]) != "" and $rsold["codigo"] <> $rsnew["codigo"]) {
    		$sql = "SELECT COUNT(codigo) AS cantidad FROM articulo WHERE codigo = '" . $rsnew["codigo"] . "' AND id <> " . $rsold["id"] . ";";
    		$cantidad = ExecuteScalar($sql);
    		if($cantidad > 0) {
    			$this->CancelMessage = "C&oacute;digo \"" . $rsnew["codigo"] . "\" ya existe.";
    			return FALSE;
    		}
    	}
    	if(trim($rsnew["codigo_de_barra"]) != "" and $rsold["codigo_de_barra"] <> $rsnew["codigo_de_barra"]) {
    		$sql = "SELECT COUNT(codigo_de_barra) AS cantidad FROM articulo WHERE codigo_de_barra = '" . $rsnew["codigo_de_barra"] . "' AND id <> " . $rsold["id"] . ";";
    		$cantidad = ExecuteScalar($sql);
    		if($cantidad > 0) {
    			$this->CancelMessage = "C&oacute;digo de barra \"" . $rsnew["codigo_de_barra"] . "\" ya existe.";
    			return FALSE;
    		}
    	}
    	if(trim($rsnew["codigo_ims"]) != "" and $rsold["codigo_ims"] <> $rsnew["codigo_ims"]) {
    		$sql = "SELECT COUNT(codigo_ims) AS cantidad FROM articulo WHERE codigo = '" . $rsnew["codigo"] . "' AND id <> " . $rsold["id"] . ";";
    		$cantidad = ExecuteScalar($sql);
    		if($cantidad > 0) {
    			$this->CancelMessage = "C&oacute;digo Ref \"" . $rsnew["codigo_ims"] . "\" ya existe.";
    			return FALSE;
    		}
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
    	$sql = "SELECT COUNT(id) AS cantidad FROM entradas_salidas WHERE articulo = '" . $rs["id"] . "';"; 
    	$cantidad = intval(ExecuteScalar($sql));
    	if($cantidad > 0) {
    		$this->CancelMessage = "Este art&iacute;culo no se puede eliminar porque tiene movimientos asociados.";
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
    public function rowRendered() {
    	// To view properties of field class, use:
    	//var_dump($this-><FieldName>);
    	if (intval($this->cantidad_en_mano->CurrentValue) <= 0) { // List page only
    		$this->nombre_comercial->CellAttrs["style"] = "color:#222; background-color:#8ad3d3;";
    		$this->principio_activo->CellAttrs["style"] = "color:#222; background-color:#8ad3d3;";
    		$this->presentacion->CellAttrs["style"] = "color:#222; background-color:#8ad3d3;";
    		$this->fabricante->CellAttrs["style"] = "color:#222; background-color:#8ad3d3;";
    	}
    	if ($this->activo->CurrentValue == "N") { // List page only
    		$this->nombre_comercial->CellAttrs["style"] = "background-color: #ffcccc";
    		$this->principio_activo->CellAttrs["style"] = "background-color: #ffcccc";
    		$this->presentacion->CellAttrs["style"] = "background-color: #ffcccc";
    		$this->fabricante->CellAttrs["style"] = "background-color: #ffcccc";
    	}
    	/*
    	 // Change the table cell color
    	if ($this->PageID == "list" || $this->PageID == "view") { // List/View page only
    		 if ($this->Cyl->CurrentValue == 4) {
    			 $this->Cyl->CellAttrs["style"] = "background-color: #ffcccc";
    		 } elseif ($this->Cyl->CurrentValue == 6) {
    			 $this->Cyl->CellAttrs["style"] = "background-color: #ffcc99";
    		 } elseif ($this->Cyl->CurrentValue == 8) {
    			 $this->Cyl->CellAttrs["style"] = "background-color: #ffccff";
    		 }
    	 }
    	 */

    	// Change text style by Bootstrap classes
    	/* if (intval($this->cantidad_en_mano->CurrentValue) == "SPORTS")
    		 $this->Category->ViewAttrs["class"] = "bg-warning text-warning";
    	*/
    }

    // User ID Filtering event
    public function userIdFiltering(&$filter)
    {
        // Enter your code here
    }
}
