<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for entradas
 */
class Entradas extends DbTable
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
    public $nro_documento;
    public $nro_control;
    public $fecha;
    public $proveedor;
    public $almacen;
    public $monto_total;
    public $alicuota_iva;
    public $iva;
    public $total;
    public $documento;
    public $doc_afectado;
    public $nota;
    public $estatus;
    public $_username;
    public $id_documento_padre;
    public $moneda;
    public $consignacion;
    public $consignacion_reportada;
    public $aplica_retencion;
    public $ret_iva;
    public $ref_iva;
    public $ret_islr;
    public $ref_islr;
    public $ret_municipal;
    public $ref_municipal;
    public $monto_pagar;
    public $comprobante;
    public $tipo_iva;
    public $tipo_islr;
    public $sustraendo;
    public $fecha_registro_retenciones;
    public $tasa_dia;
    public $monto_usd;
    public $fecha_libro_compra;
    public $tipo_municipal;
    public $cerrado;
    public $cliente;
    public $descuento;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'entradas';
        $this->TableName = 'entradas';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`entradas`";
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
        $this->id = new DbField('entradas', 'entradas', 'x_id', 'id', '`id`', '`id`', 19, 10, -1, false, '`id`', false, false, false, 'FORMATTED TEXT', 'NO');
        $this->id->IsAutoIncrement = true; // Autoincrement field
        $this->id->IsPrimaryKey = true; // Primary key field
        $this->id->IsForeignKey = true; // Foreign key field
        $this->id->Sortable = true; // Allow sort
        $this->id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->id->Param, "CustomMsg");
        $this->Fields['id'] = &$this->id;

        // tipo_documento
        $this->tipo_documento = new DbField('entradas', 'entradas', 'x_tipo_documento', 'tipo_documento', '`tipo_documento`', '`tipo_documento`', 200, 6, -1, false, '`tipo_documento`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->tipo_documento->IsForeignKey = true; // Foreign key field
        $this->tipo_documento->Required = true; // Required field
        $this->tipo_documento->Sortable = true; // Allow sort
        $this->tipo_documento->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tipo_documento->Param, "CustomMsg");
        $this->Fields['tipo_documento'] = &$this->tipo_documento;

        // nro_documento
        $this->nro_documento = new DbField('entradas', 'entradas', 'x_nro_documento', 'nro_documento', '`nro_documento`', '`nro_documento`', 200, 20, -1, false, '`nro_documento`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->nro_documento->Required = true; // Required field
        $this->nro_documento->Sortable = true; // Allow sort
        $this->nro_documento->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->nro_documento->Param, "CustomMsg");
        $this->Fields['nro_documento'] = &$this->nro_documento;

        // nro_control
        $this->nro_control = new DbField('entradas', 'entradas', 'x_nro_control', 'nro_control', '`nro_control`', '`nro_control`', 200, 30, -1, false, '`nro_control`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->nro_control->Required = true; // Required field
        $this->nro_control->Sortable = true; // Allow sort
        $this->nro_control->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->nro_control->Param, "CustomMsg");
        $this->Fields['nro_control'] = &$this->nro_control;

        // fecha
        $this->fecha = new DbField('entradas', 'entradas', 'x_fecha', 'fecha', '`fecha`', CastDateFieldForLike("`fecha`", 7, "DB"), 135, 19, 7, false, '`fecha`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->fecha->Required = true; // Required field
        $this->fecha->Sortable = true; // Allow sort
        $this->fecha->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_SEPARATOR"], $Language->phrase("IncorrectDateDMY"));
        $this->fecha->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->fecha->Param, "CustomMsg");
        $this->Fields['fecha'] = &$this->fecha;

        // proveedor
        $this->proveedor = new DbField('entradas', 'entradas', 'x_proveedor', 'proveedor', '`proveedor`', '`proveedor`', 3, 11, -1, false, '`proveedor`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->proveedor->Required = true; // Required field
        $this->proveedor->Sortable = true; // Allow sort
        $this->proveedor->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->proveedor->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->proveedor->Lookup = new Lookup('proveedor', 'proveedor', false, 'id', ["nombre","","",""], [], ["x_id_documento_padre"], [], [], [], [], '`nombre`', '');
        $this->proveedor->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->proveedor->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->proveedor->Param, "CustomMsg");
        $this->Fields['proveedor'] = &$this->proveedor;

        // almacen
        $this->almacen = new DbField('entradas', 'entradas', 'x_almacen', 'almacen', '`almacen`', '`almacen`', 200, 6, -1, false, '`almacen`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->almacen->Required = true; // Required field
        $this->almacen->Sortable = true; // Allow sort
        $this->almacen->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->almacen->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->almacen->Lookup = new Lookup('almacen', 'almacen', false, 'codigo', ["descripcion","","",""], [], [], [], [], [], [], '`descripcion`', '');
        $this->almacen->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->almacen->Param, "CustomMsg");
        $this->Fields['almacen'] = &$this->almacen;

        // monto_total
        $this->monto_total = new DbField('entradas', 'entradas', 'x_monto_total', 'monto_total', '`monto_total`', '`monto_total`', 131, 14, -1, false, '`monto_total`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->monto_total->Sortable = true; // Allow sort
        $this->monto_total->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->monto_total->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_total->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->monto_total->Param, "CustomMsg");
        $this->Fields['monto_total'] = &$this->monto_total;

        // alicuota_iva
        $this->alicuota_iva = new DbField('entradas', 'entradas', 'x_alicuota_iva', 'alicuota_iva', '`alicuota_iva`', '`alicuota_iva`', 131, 14, -1, false, '`alicuota_iva`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->alicuota_iva->Sortable = true; // Allow sort
        $this->alicuota_iva->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->alicuota_iva->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->alicuota_iva->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->alicuota_iva->Param, "CustomMsg");
        $this->Fields['alicuota_iva'] = &$this->alicuota_iva;

        // iva
        $this->iva = new DbField('entradas', 'entradas', 'x_iva', 'iva', '`iva`', '`iva`', 131, 14, -1, false, '`iva`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->iva->Sortable = true; // Allow sort
        $this->iva->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->iva->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->iva->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->iva->Param, "CustomMsg");
        $this->Fields['iva'] = &$this->iva;

        // total
        $this->total = new DbField('entradas', 'entradas', 'x_total', 'total', '`total`', '`total`', 131, 14, -1, false, '`total`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->total->Sortable = true; // Allow sort
        $this->total->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->total->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->total->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->total->Param, "CustomMsg");
        $this->Fields['total'] = &$this->total;

        // documento
        $this->documento = new DbField('entradas', 'entradas', 'x_documento', 'documento', '`documento`', '`documento`', 200, 2, 7, false, '`documento`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->documento->Required = true; // Required field
        $this->documento->Sortable = true; // Allow sort
        $this->documento->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->documento->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->documento->Lookup = new Lookup('documento', 'entradas', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->documento->OptionCount = 3;
        $this->documento->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->documento->Param, "CustomMsg");
        $this->Fields['documento'] = &$this->documento;

        // doc_afectado
        $this->doc_afectado = new DbField('entradas', 'entradas', 'x_doc_afectado', 'doc_afectado', '`doc_afectado`', '`doc_afectado`', 200, 20, -1, false, '`doc_afectado`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->doc_afectado->Sortable = true; // Allow sort
        $this->doc_afectado->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->doc_afectado->Param, "CustomMsg");
        $this->Fields['doc_afectado'] = &$this->doc_afectado;

        // nota
        $this->nota = new DbField('entradas', 'entradas', 'x_nota', 'nota', '`nota`', '`nota`', 201, 65535, -1, false, '`nota`', false, false, false, 'FORMATTED TEXT', 'TEXTAREA');
        $this->nota->Sortable = true; // Allow sort
        $this->nota->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->nota->Param, "CustomMsg");
        $this->Fields['nota'] = &$this->nota;

        // estatus
        $this->estatus = new DbField('entradas', 'entradas', 'x_estatus', 'estatus', '`estatus`', '`estatus`', 200, 10, -1, false, '`estatus`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->estatus->Required = true; // Required field
        $this->estatus->Sortable = true; // Allow sort
        $this->estatus->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->estatus->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->estatus->Lookup = new Lookup('estatus', 'entradas', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->estatus->OptionCount = 3;
        $this->estatus->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->estatus->Param, "CustomMsg");
        $this->Fields['estatus'] = &$this->estatus;

        // username
        $this->_username = new DbField('entradas', 'entradas', 'x__username', 'username', '`username`', '`username`', 200, 30, -1, false, '`username`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->_username->Sortable = true; // Allow sort
        $this->_username->Lookup = new Lookup('username', 'usuario', false, 'username', ["nombre","","",""], [], [], [], [], [], [], '', '');
        $this->_username->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->_username->Param, "CustomMsg");
        $this->Fields['username'] = &$this->_username;

        // id_documento_padre
        $this->id_documento_padre = new DbField('entradas', 'entradas', 'x_id_documento_padre', 'id_documento_padre', '`id_documento_padre`', '`id_documento_padre`', 19, 10, -1, false, '`id_documento_padre`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->id_documento_padre->Required = true; // Required field
        $this->id_documento_padre->Sortable = true; // Allow sort
        $this->id_documento_padre->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id_documento_padre->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->id_documento_padre->Param, "CustomMsg");
        $this->Fields['id_documento_padre'] = &$this->id_documento_padre;

        // moneda
        $this->moneda = new DbField('entradas', 'entradas', 'x_moneda', 'moneda', '`moneda`', '`moneda`', 200, 6, -1, false, '`moneda`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->moneda->Required = true; // Required field
        $this->moneda->Sortable = true; // Allow sort
        $this->moneda->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->moneda->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->moneda->Lookup = new Lookup('moneda', 'parametro', false, 'valor1', ["valor1","","",""], [], [], [], [], [], [], '`valor2` DESC', '');
        $this->moneda->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->moneda->Param, "CustomMsg");
        $this->Fields['moneda'] = &$this->moneda;

        // consignacion
        $this->consignacion = new DbField('entradas', 'entradas', 'x_consignacion', 'consignacion', '`consignacion`', '`consignacion`', 202, 1, -1, false, '`consignacion`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->consignacion->Sortable = true; // Allow sort
        $this->consignacion->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->consignacion->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->consignacion->Lookup = new Lookup('consignacion', 'entradas', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->consignacion->OptionCount = 2;
        $this->consignacion->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->consignacion->Param, "CustomMsg");
        $this->Fields['consignacion'] = &$this->consignacion;

        // consignacion_reportada
        $this->consignacion_reportada = new DbField('entradas', 'entradas', 'x_consignacion_reportada', 'consignacion_reportada', '`consignacion_reportada`', '`consignacion_reportada`', 202, 1, -1, false, '`consignacion_reportada`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->consignacion_reportada->Sortable = true; // Allow sort
        $this->consignacion_reportada->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->consignacion_reportada->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->consignacion_reportada->Lookup = new Lookup('consignacion_reportada', 'entradas', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->consignacion_reportada->OptionCount = 2;
        $this->consignacion_reportada->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->consignacion_reportada->Param, "CustomMsg");
        $this->Fields['consignacion_reportada'] = &$this->consignacion_reportada;

        // aplica_retencion
        $this->aplica_retencion = new DbField('entradas', 'entradas', 'x_aplica_retencion', 'aplica_retencion', '`aplica_retencion`', '`aplica_retencion`', 202, 1, -1, false, '`aplica_retencion`', false, false, false, 'FORMATTED TEXT', 'RADIO');
        $this->aplica_retencion->Required = true; // Required field
        $this->aplica_retencion->Sortable = true; // Allow sort
        $this->aplica_retencion->Lookup = new Lookup('aplica_retencion', 'entradas', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->aplica_retencion->OptionCount = 2;
        $this->aplica_retencion->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->aplica_retencion->Param, "CustomMsg");
        $this->Fields['aplica_retencion'] = &$this->aplica_retencion;

        // ret_iva
        $this->ret_iva = new DbField('entradas', 'entradas', 'x_ret_iva', 'ret_iva', '`ret_iva`', '`ret_iva`', 131, 14, -1, false, '`ret_iva`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->ret_iva->Sortable = true; // Allow sort
        $this->ret_iva->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->ret_iva->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->ret_iva->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->ret_iva->Param, "CustomMsg");
        $this->Fields['ret_iva'] = &$this->ret_iva;

        // ref_iva
        $this->ref_iva = new DbField('entradas', 'entradas', 'x_ref_iva', 'ref_iva', '`ref_iva`', '`ref_iva`', 200, 30, -1, false, '`ref_iva`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->ref_iva->Sortable = true; // Allow sort
        $this->ref_iva->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->ref_iva->Param, "CustomMsg");
        $this->Fields['ref_iva'] = &$this->ref_iva;

        // ret_islr
        $this->ret_islr = new DbField('entradas', 'entradas', 'x_ret_islr', 'ret_islr', '`ret_islr`', '`ret_islr`', 131, 14, -1, false, '`ret_islr`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->ret_islr->Sortable = true; // Allow sort
        $this->ret_islr->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->ret_islr->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->ret_islr->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->ret_islr->Param, "CustomMsg");
        $this->Fields['ret_islr'] = &$this->ret_islr;

        // ref_islr
        $this->ref_islr = new DbField('entradas', 'entradas', 'x_ref_islr', 'ref_islr', '`ref_islr`', '`ref_islr`', 200, 30, -1, false, '`ref_islr`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->ref_islr->Sortable = true; // Allow sort
        $this->ref_islr->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->ref_islr->Param, "CustomMsg");
        $this->Fields['ref_islr'] = &$this->ref_islr;

        // ret_municipal
        $this->ret_municipal = new DbField('entradas', 'entradas', 'x_ret_municipal', 'ret_municipal', '`ret_municipal`', '`ret_municipal`', 131, 14, -1, false, '`ret_municipal`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->ret_municipal->Sortable = true; // Allow sort
        $this->ret_municipal->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->ret_municipal->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->ret_municipal->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->ret_municipal->Param, "CustomMsg");
        $this->Fields['ret_municipal'] = &$this->ret_municipal;

        // ref_municipal
        $this->ref_municipal = new DbField('entradas', 'entradas', 'x_ref_municipal', 'ref_municipal', '`ref_municipal`', '`ref_municipal`', 200, 30, -1, false, '`ref_municipal`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->ref_municipal->Sortable = true; // Allow sort
        $this->ref_municipal->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->ref_municipal->Param, "CustomMsg");
        $this->Fields['ref_municipal'] = &$this->ref_municipal;

        // monto_pagar
        $this->monto_pagar = new DbField('entradas', 'entradas', 'x_monto_pagar', 'monto_pagar', '`monto_pagar`', '`monto_pagar`', 131, 14, -1, false, '`monto_pagar`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->monto_pagar->Sortable = true; // Allow sort
        $this->monto_pagar->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->monto_pagar->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_pagar->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->monto_pagar->Param, "CustomMsg");
        $this->Fields['monto_pagar'] = &$this->monto_pagar;

        // comprobante
        $this->comprobante = new DbField('entradas', 'entradas', 'x_comprobante', 'comprobante', '`comprobante`', '`comprobante`', 19, 10, -1, false, '`comprobante`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->comprobante->Sortable = true; // Allow sort
        $this->comprobante->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->comprobante->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->comprobante->Lookup = new Lookup('comprobante', 'cont_comprobante', false, 'id', ["id","descripcion","",""], [], [], [], [], [], [], '', '');
        $this->comprobante->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->comprobante->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->comprobante->Param, "CustomMsg");
        $this->Fields['comprobante'] = &$this->comprobante;

        // tipo_iva
        $this->tipo_iva = new DbField('entradas', 'entradas', 'x_tipo_iva', 'tipo_iva', '`tipo_iva`', '`tipo_iva`', 200, 4, -1, false, '`tipo_iva`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->tipo_iva->Sortable = true; // Allow sort
        $this->tipo_iva->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tipo_iva->Param, "CustomMsg");
        $this->Fields['tipo_iva'] = &$this->tipo_iva;

        // tipo_islr
        $this->tipo_islr = new DbField('entradas', 'entradas', 'x_tipo_islr', 'tipo_islr', '`tipo_islr`', '`tipo_islr`', 200, 4, -1, false, '`tipo_islr`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->tipo_islr->Sortable = true; // Allow sort
        $this->tipo_islr->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tipo_islr->Param, "CustomMsg");
        $this->Fields['tipo_islr'] = &$this->tipo_islr;

        // sustraendo
        $this->sustraendo = new DbField('entradas', 'entradas', 'x_sustraendo', 'sustraendo', '`sustraendo`', '`sustraendo`', 131, 14, -1, false, '`sustraendo`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->sustraendo->Sortable = true; // Allow sort
        $this->sustraendo->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->sustraendo->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->sustraendo->Param, "CustomMsg");
        $this->Fields['sustraendo'] = &$this->sustraendo;

        // fecha_registro_retenciones
        $this->fecha_registro_retenciones = new DbField('entradas', 'entradas', 'x_fecha_registro_retenciones', 'fecha_registro_retenciones', '`fecha_registro_retenciones`', CastDateFieldForLike("`fecha_registro_retenciones`", 0, "DB"), 133, 10, 0, false, '`fecha_registro_retenciones`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->fecha_registro_retenciones->Sortable = true; // Allow sort
        $this->fecha_registro_retenciones->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->fecha_registro_retenciones->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->fecha_registro_retenciones->Param, "CustomMsg");
        $this->Fields['fecha_registro_retenciones'] = &$this->fecha_registro_retenciones;

        // tasa_dia
        $this->tasa_dia = new DbField('entradas', 'entradas', 'x_tasa_dia', 'tasa_dia', '`tasa_dia`', '`tasa_dia`', 131, 14, -1, false, '`tasa_dia`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->tasa_dia->Sortable = true; // Allow sort
        $this->tasa_dia->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->tasa_dia->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tasa_dia->Param, "CustomMsg");
        $this->Fields['tasa_dia'] = &$this->tasa_dia;

        // monto_usd
        $this->monto_usd = new DbField('entradas', 'entradas', 'x_monto_usd', 'monto_usd', '`monto_usd`', '`monto_usd`', 131, 14, -1, false, '`monto_usd`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->monto_usd->Sortable = true; // Allow sort
        $this->monto_usd->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_usd->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->monto_usd->Param, "CustomMsg");
        $this->Fields['monto_usd'] = &$this->monto_usd;

        // fecha_libro_compra
        $this->fecha_libro_compra = new DbField('entradas', 'entradas', 'x_fecha_libro_compra', 'fecha_libro_compra', '`fecha_libro_compra`', CastDateFieldForLike("`fecha_libro_compra`", 7, "DB"), 133, 10, 7, false, '`fecha_libro_compra`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->fecha_libro_compra->Sortable = true; // Allow sort
        $this->fecha_libro_compra->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_SEPARATOR"], $Language->phrase("IncorrectDateDMY"));
        $this->fecha_libro_compra->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->fecha_libro_compra->Param, "CustomMsg");
        $this->Fields['fecha_libro_compra'] = &$this->fecha_libro_compra;

        // tipo_municipal
        $this->tipo_municipal = new DbField('entradas', 'entradas', 'x_tipo_municipal', 'tipo_municipal', '`tipo_municipal`', '`tipo_municipal`', 200, 4, -1, false, '`tipo_municipal`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->tipo_municipal->Sortable = true; // Allow sort
        $this->tipo_municipal->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tipo_municipal->Param, "CustomMsg");
        $this->Fields['tipo_municipal'] = &$this->tipo_municipal;

        // cerrado
        $this->cerrado = new DbField('entradas', 'entradas', 'x_cerrado', 'cerrado', '`cerrado`', '`cerrado`', 202, 1, -1, false, '`cerrado`', false, false, false, 'FORMATTED TEXT', 'RADIO');
        $this->cerrado->Sortable = true; // Allow sort
        $this->cerrado->Lookup = new Lookup('cerrado', 'entradas', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->cerrado->OptionCount = 2;
        $this->cerrado->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->cerrado->Param, "CustomMsg");
        $this->Fields['cerrado'] = &$this->cerrado;

        // cliente
        $this->cliente = new DbField('entradas', 'entradas', 'x_cliente', 'cliente', '`cliente`', '`cliente`', 3, 11, -1, false, '`cliente`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->cliente->Sortable = true; // Allow sort
        $this->cliente->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->cliente->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->cliente->Lookup = new Lookup('cliente', 'cliente', false, 'id', ["nombre","codigo","ci_rif",""], [], [], [], [], [], [], '`nombre` ASC', '');
        $this->cliente->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->cliente->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->cliente->Param, "CustomMsg");
        $this->Fields['cliente'] = &$this->cliente;

        // descuento
        $this->descuento = new DbField('entradas', 'entradas', 'x_descuento', 'descuento', '`descuento`', '`descuento`', 131, 6, -1, false, '`descuento`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->descuento->Sortable = true; // Allow sort
        $this->descuento->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->descuento->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->descuento->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->descuento->Param, "CustomMsg");
        $this->Fields['descuento'] = &$this->descuento;
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
        if ($this->getCurrentDetailTable() == "entradas_salidas") {
            $detailUrl = Container("entradas_salidas")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_tipo_documento", $this->tipo_documento->CurrentValue);
            $detailUrl .= "&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue);
        }
        if ($detailUrl == "") {
            $detailUrl = "EntradasList";
        }
        return $detailUrl;
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`entradas`";
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
        $this->nro_documento->DbValue = $row['nro_documento'];
        $this->nro_control->DbValue = $row['nro_control'];
        $this->fecha->DbValue = $row['fecha'];
        $this->proveedor->DbValue = $row['proveedor'];
        $this->almacen->DbValue = $row['almacen'];
        $this->monto_total->DbValue = $row['monto_total'];
        $this->alicuota_iva->DbValue = $row['alicuota_iva'];
        $this->iva->DbValue = $row['iva'];
        $this->total->DbValue = $row['total'];
        $this->documento->DbValue = $row['documento'];
        $this->doc_afectado->DbValue = $row['doc_afectado'];
        $this->nota->DbValue = $row['nota'];
        $this->estatus->DbValue = $row['estatus'];
        $this->_username->DbValue = $row['username'];
        $this->id_documento_padre->DbValue = $row['id_documento_padre'];
        $this->moneda->DbValue = $row['moneda'];
        $this->consignacion->DbValue = $row['consignacion'];
        $this->consignacion_reportada->DbValue = $row['consignacion_reportada'];
        $this->aplica_retencion->DbValue = $row['aplica_retencion'];
        $this->ret_iva->DbValue = $row['ret_iva'];
        $this->ref_iva->DbValue = $row['ref_iva'];
        $this->ret_islr->DbValue = $row['ret_islr'];
        $this->ref_islr->DbValue = $row['ref_islr'];
        $this->ret_municipal->DbValue = $row['ret_municipal'];
        $this->ref_municipal->DbValue = $row['ref_municipal'];
        $this->monto_pagar->DbValue = $row['monto_pagar'];
        $this->comprobante->DbValue = $row['comprobante'];
        $this->tipo_iva->DbValue = $row['tipo_iva'];
        $this->tipo_islr->DbValue = $row['tipo_islr'];
        $this->sustraendo->DbValue = $row['sustraendo'];
        $this->fecha_registro_retenciones->DbValue = $row['fecha_registro_retenciones'];
        $this->tasa_dia->DbValue = $row['tasa_dia'];
        $this->monto_usd->DbValue = $row['monto_usd'];
        $this->fecha_libro_compra->DbValue = $row['fecha_libro_compra'];
        $this->tipo_municipal->DbValue = $row['tipo_municipal'];
        $this->cerrado->DbValue = $row['cerrado'];
        $this->cliente->DbValue = $row['cliente'];
        $this->descuento->DbValue = $row['descuento'];
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
        return $_SESSION[$name] ?? GetUrl("EntradasList");
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
        if ($pageName == "EntradasView") {
            return $Language->phrase("View");
        } elseif ($pageName == "EntradasEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "EntradasAdd") {
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
                return "EntradasView";
            case Config("API_ADD_ACTION"):
                return "EntradasAdd";
            case Config("API_EDIT_ACTION"):
                return "EntradasEdit";
            case Config("API_DELETE_ACTION"):
                return "EntradasDelete";
            case Config("API_LIST_ACTION"):
                return "EntradasList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "EntradasList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("EntradasView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("EntradasView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "EntradasAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "EntradasAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("EntradasEdit", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("EntradasEdit", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
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
            $url = $this->keyUrl("EntradasAdd", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("EntradasAdd", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
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
        return $this->keyUrl("EntradasDelete", $this->getUrlParm());
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
        $this->nro_documento->setDbValue($row['nro_documento']);
        $this->nro_control->setDbValue($row['nro_control']);
        $this->fecha->setDbValue($row['fecha']);
        $this->proveedor->setDbValue($row['proveedor']);
        $this->almacen->setDbValue($row['almacen']);
        $this->monto_total->setDbValue($row['monto_total']);
        $this->alicuota_iva->setDbValue($row['alicuota_iva']);
        $this->iva->setDbValue($row['iva']);
        $this->total->setDbValue($row['total']);
        $this->documento->setDbValue($row['documento']);
        $this->doc_afectado->setDbValue($row['doc_afectado']);
        $this->nota->setDbValue($row['nota']);
        $this->estatus->setDbValue($row['estatus']);
        $this->_username->setDbValue($row['username']);
        $this->id_documento_padre->setDbValue($row['id_documento_padre']);
        $this->moneda->setDbValue($row['moneda']);
        $this->consignacion->setDbValue($row['consignacion']);
        $this->consignacion_reportada->setDbValue($row['consignacion_reportada']);
        $this->aplica_retencion->setDbValue($row['aplica_retencion']);
        $this->ret_iva->setDbValue($row['ret_iva']);
        $this->ref_iva->setDbValue($row['ref_iva']);
        $this->ret_islr->setDbValue($row['ret_islr']);
        $this->ref_islr->setDbValue($row['ref_islr']);
        $this->ret_municipal->setDbValue($row['ret_municipal']);
        $this->ref_municipal->setDbValue($row['ref_municipal']);
        $this->monto_pagar->setDbValue($row['monto_pagar']);
        $this->comprobante->setDbValue($row['comprobante']);
        $this->tipo_iva->setDbValue($row['tipo_iva']);
        $this->tipo_islr->setDbValue($row['tipo_islr']);
        $this->sustraendo->setDbValue($row['sustraendo']);
        $this->fecha_registro_retenciones->setDbValue($row['fecha_registro_retenciones']);
        $this->tasa_dia->setDbValue($row['tasa_dia']);
        $this->monto_usd->setDbValue($row['monto_usd']);
        $this->fecha_libro_compra->setDbValue($row['fecha_libro_compra']);
        $this->tipo_municipal->setDbValue($row['tipo_municipal']);
        $this->cerrado->setDbValue($row['cerrado']);
        $this->cliente->setDbValue($row['cliente']);
        $this->descuento->setDbValue($row['descuento']);
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

        // nro_documento

        // nro_control

        // fecha

        // proveedor

        // almacen

        // monto_total

        // alicuota_iva

        // iva

        // total

        // documento

        // doc_afectado

        // nota

        // estatus

        // username

        // id_documento_padre

        // moneda

        // consignacion

        // consignacion_reportada

        // aplica_retencion

        // ret_iva

        // ref_iva

        // ret_islr

        // ref_islr

        // ret_municipal

        // ref_municipal

        // monto_pagar

        // comprobante

        // tipo_iva

        // tipo_islr

        // sustraendo

        // fecha_registro_retenciones

        // tasa_dia

        // monto_usd

        // fecha_libro_compra

        // tipo_municipal

        // cerrado

        // cliente

        // descuento

        // id
        $this->id->ViewValue = $this->id->CurrentValue;
        $this->id->ViewCustomAttributes = "";

        // tipo_documento
        $this->tipo_documento->ViewValue = $this->tipo_documento->CurrentValue;
        $this->tipo_documento->ViewCustomAttributes = "";

        // nro_documento
        $this->nro_documento->ViewValue = $this->nro_documento->CurrentValue;
        $this->nro_documento->ViewCustomAttributes = "";

        // nro_control
        $this->nro_control->ViewValue = $this->nro_control->CurrentValue;
        $this->nro_control->ViewCustomAttributes = "";

        // fecha
        $this->fecha->ViewValue = $this->fecha->CurrentValue;
        $this->fecha->ViewValue = FormatDateTime($this->fecha->ViewValue, 7);
        $this->fecha->ViewCustomAttributes = "";

        // proveedor
        $curVal = trim(strval($this->proveedor->CurrentValue));
        if ($curVal != "") {
            $this->proveedor->ViewValue = $this->proveedor->lookupCacheOption($curVal);
            if ($this->proveedor->ViewValue === null) { // Lookup from database
                $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $sqlWrk = $this->proveedor->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->proveedor->Lookup->renderViewRow($rswrk[0]);
                    $this->proveedor->ViewValue = $this->proveedor->displayValue($arwrk);
                } else {
                    $this->proveedor->ViewValue = $this->proveedor->CurrentValue;
                }
            }
        } else {
            $this->proveedor->ViewValue = null;
        }
        $this->proveedor->ViewCustomAttributes = "";

        // almacen
        $curVal = trim(strval($this->almacen->CurrentValue));
        if ($curVal != "") {
            $this->almacen->ViewValue = $this->almacen->lookupCacheOption($curVal);
            if ($this->almacen->ViewValue === null) { // Lookup from database
                $filterWrk = "`codigo`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                $sqlWrk = $this->almacen->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->almacen->Lookup->renderViewRow($rswrk[0]);
                    $this->almacen->ViewValue = $this->almacen->displayValue($arwrk);
                } else {
                    $this->almacen->ViewValue = $this->almacen->CurrentValue;
                }
            }
        } else {
            $this->almacen->ViewValue = null;
        }
        $this->almacen->ViewCustomAttributes = "";

        // monto_total
        $this->monto_total->ViewValue = $this->monto_total->CurrentValue;
        $this->monto_total->ViewValue = FormatNumber($this->monto_total->ViewValue, 2, -1, -1, -1);
        $this->monto_total->ViewCustomAttributes = "";

        // alicuota_iva
        $this->alicuota_iva->ViewValue = $this->alicuota_iva->CurrentValue;
        $this->alicuota_iva->ViewValue = FormatNumber($this->alicuota_iva->ViewValue, 2, -1, -1, -1);
        $this->alicuota_iva->ViewCustomAttributes = "";

        // iva
        $this->iva->ViewValue = $this->iva->CurrentValue;
        $this->iva->ViewValue = FormatNumber($this->iva->ViewValue, 2, -1, -1, -1);
        $this->iva->ViewCustomAttributes = "";

        // total
        $this->total->ViewValue = $this->total->CurrentValue;
        $this->total->ViewValue = FormatNumber($this->total->ViewValue, 2, -1, -1, -1);
        $this->total->ViewCustomAttributes = "";

        // documento
        if (strval($this->documento->CurrentValue) != "") {
            $this->documento->ViewValue = $this->documento->optionCaption($this->documento->CurrentValue);
        } else {
            $this->documento->ViewValue = null;
        }
        $this->documento->ViewCustomAttributes = "";

        // doc_afectado
        $this->doc_afectado->ViewValue = $this->doc_afectado->CurrentValue;
        $this->doc_afectado->ViewCustomAttributes = "";

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

        // id_documento_padre
        $this->id_documento_padre->ViewValue = $this->id_documento_padre->CurrentValue;
        $this->id_documento_padre->ViewCustomAttributes = "";

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

        // consignacion
        if (strval($this->consignacion->CurrentValue) != "") {
            $this->consignacion->ViewValue = $this->consignacion->optionCaption($this->consignacion->CurrentValue);
        } else {
            $this->consignacion->ViewValue = null;
        }
        $this->consignacion->ViewCustomAttributes = "";

        // consignacion_reportada
        if (strval($this->consignacion_reportada->CurrentValue) != "") {
            $this->consignacion_reportada->ViewValue = $this->consignacion_reportada->optionCaption($this->consignacion_reportada->CurrentValue);
        } else {
            $this->consignacion_reportada->ViewValue = null;
        }
        $this->consignacion_reportada->ViewCustomAttributes = "";

        // aplica_retencion
        if (strval($this->aplica_retencion->CurrentValue) != "") {
            $this->aplica_retencion->ViewValue = $this->aplica_retencion->optionCaption($this->aplica_retencion->CurrentValue);
        } else {
            $this->aplica_retencion->ViewValue = null;
        }
        $this->aplica_retencion->ViewCustomAttributes = "";

        // ret_iva
        $this->ret_iva->ViewValue = $this->ret_iva->CurrentValue;
        $this->ret_iva->ViewValue = FormatNumber($this->ret_iva->ViewValue, 2, -1, -1, -1);
        $this->ret_iva->ViewCustomAttributes = "";

        // ref_iva
        $this->ref_iva->ViewValue = $this->ref_iva->CurrentValue;
        $this->ref_iva->ViewCustomAttributes = "";

        // ret_islr
        $this->ret_islr->ViewValue = $this->ret_islr->CurrentValue;
        $this->ret_islr->ViewValue = FormatNumber($this->ret_islr->ViewValue, 2, -1, -1, -1);
        $this->ret_islr->ViewCustomAttributes = "";

        // ref_islr
        $this->ref_islr->ViewValue = $this->ref_islr->CurrentValue;
        $this->ref_islr->ViewCustomAttributes = "";

        // ret_municipal
        $this->ret_municipal->ViewValue = $this->ret_municipal->CurrentValue;
        $this->ret_municipal->ViewValue = FormatNumber($this->ret_municipal->ViewValue, 2, -2, -2, -2);
        $this->ret_municipal->ViewCustomAttributes = "";

        // ref_municipal
        $this->ref_municipal->ViewValue = $this->ref_municipal->CurrentValue;
        $this->ref_municipal->ViewCustomAttributes = "";

        // monto_pagar
        $this->monto_pagar->ViewValue = $this->monto_pagar->CurrentValue;
        $this->monto_pagar->ViewValue = FormatNumber($this->monto_pagar->ViewValue, 2, -1, -1, -1);
        $this->monto_pagar->ViewCustomAttributes = "";

        // comprobante
        $curVal = trim(strval($this->comprobante->CurrentValue));
        if ($curVal != "") {
            $this->comprobante->ViewValue = $this->comprobante->lookupCacheOption($curVal);
            if ($this->comprobante->ViewValue === null) { // Lookup from database
                $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $sqlWrk = $this->comprobante->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->comprobante->Lookup->renderViewRow($rswrk[0]);
                    $this->comprobante->ViewValue = $this->comprobante->displayValue($arwrk);
                } else {
                    $this->comprobante->ViewValue = $this->comprobante->CurrentValue;
                }
            }
        } else {
            $this->comprobante->ViewValue = null;
        }
        $this->comprobante->ViewCustomAttributes = "";

        // tipo_iva
        $this->tipo_iva->ViewValue = $this->tipo_iva->CurrentValue;
        $this->tipo_iva->ViewCustomAttributes = "";

        // tipo_islr
        $this->tipo_islr->ViewValue = $this->tipo_islr->CurrentValue;
        $this->tipo_islr->ViewCustomAttributes = "";

        // sustraendo
        $this->sustraendo->ViewValue = $this->sustraendo->CurrentValue;
        $this->sustraendo->ViewValue = FormatNumber($this->sustraendo->ViewValue, $this->sustraendo->DefaultDecimalPrecision);
        $this->sustraendo->ViewCustomAttributes = "";

        // fecha_registro_retenciones
        $this->fecha_registro_retenciones->ViewValue = $this->fecha_registro_retenciones->CurrentValue;
        $this->fecha_registro_retenciones->ViewValue = FormatDateTime($this->fecha_registro_retenciones->ViewValue, 0);
        $this->fecha_registro_retenciones->ViewCustomAttributes = "";

        // tasa_dia
        $this->tasa_dia->ViewValue = $this->tasa_dia->CurrentValue;
        $this->tasa_dia->ViewValue = FormatNumber($this->tasa_dia->ViewValue, $this->tasa_dia->DefaultDecimalPrecision);
        $this->tasa_dia->ViewCustomAttributes = "";

        // monto_usd
        $this->monto_usd->ViewValue = $this->monto_usd->CurrentValue;
        $this->monto_usd->ViewValue = FormatNumber($this->monto_usd->ViewValue, $this->monto_usd->DefaultDecimalPrecision);
        $this->monto_usd->ViewCustomAttributes = "";

        // fecha_libro_compra
        $this->fecha_libro_compra->ViewValue = $this->fecha_libro_compra->CurrentValue;
        $this->fecha_libro_compra->ViewValue = FormatDateTime($this->fecha_libro_compra->ViewValue, 7);
        $this->fecha_libro_compra->ViewCustomAttributes = "";

        // tipo_municipal
        $this->tipo_municipal->ViewValue = $this->tipo_municipal->CurrentValue;
        $this->tipo_municipal->ViewCustomAttributes = "";

        // cerrado
        if (strval($this->cerrado->CurrentValue) != "") {
            $this->cerrado->ViewValue = $this->cerrado->optionCaption($this->cerrado->CurrentValue);
        } else {
            $this->cerrado->ViewValue = null;
        }
        $this->cerrado->ViewCustomAttributes = "";

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

        // descuento
        $this->descuento->ViewValue = $this->descuento->CurrentValue;
        $this->descuento->ViewValue = FormatNumber($this->descuento->ViewValue, 2, -2, -2, -2);
        $this->descuento->ViewCustomAttributes = "";

        // id
        $this->id->LinkCustomAttributes = "";
        $this->id->HrefValue = "";
        $this->id->TooltipValue = "";

        // tipo_documento
        $this->tipo_documento->LinkCustomAttributes = "";
        $this->tipo_documento->HrefValue = "";
        $this->tipo_documento->TooltipValue = "";

        // nro_documento
        $this->nro_documento->LinkCustomAttributes = "";
        $this->nro_documento->HrefValue = "";
        $this->nro_documento->TooltipValue = "";

        // nro_control
        $this->nro_control->LinkCustomAttributes = "";
        $this->nro_control->HrefValue = "";
        $this->nro_control->TooltipValue = "";

        // fecha
        $this->fecha->LinkCustomAttributes = "";
        $this->fecha->HrefValue = "";
        $this->fecha->TooltipValue = "";

        // proveedor
        $this->proveedor->LinkCustomAttributes = "";
        $this->proveedor->HrefValue = "";
        $this->proveedor->TooltipValue = "";

        // almacen
        $this->almacen->LinkCustomAttributes = "";
        $this->almacen->HrefValue = "";
        $this->almacen->TooltipValue = "";

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

        // documento
        $this->documento->LinkCustomAttributes = "";
        $this->documento->HrefValue = "";
        $this->documento->TooltipValue = "";

        // doc_afectado
        $this->doc_afectado->LinkCustomAttributes = "";
        $this->doc_afectado->HrefValue = "";
        $this->doc_afectado->TooltipValue = "";

        // nota
        $this->nota->LinkCustomAttributes = "";
        $this->nota->HrefValue = "";
        $this->nota->TooltipValue = "";

        // estatus
        $this->estatus->LinkCustomAttributes = "";
        $this->estatus->HrefValue = "";
        $this->estatus->TooltipValue = "";

        // username
        $this->_username->LinkCustomAttributes = "";
        $this->_username->HrefValue = "";
        $this->_username->TooltipValue = "";

        // id_documento_padre
        $this->id_documento_padre->LinkCustomAttributes = "";
        $this->id_documento_padre->HrefValue = "";
        $this->id_documento_padre->TooltipValue = "";

        // moneda
        $this->moneda->LinkCustomAttributes = "";
        $this->moneda->HrefValue = "";
        $this->moneda->TooltipValue = "";

        // consignacion
        $this->consignacion->LinkCustomAttributes = "";
        $this->consignacion->HrefValue = "";
        $this->consignacion->TooltipValue = "";

        // consignacion_reportada
        $this->consignacion_reportada->LinkCustomAttributes = "";
        $this->consignacion_reportada->HrefValue = "";
        $this->consignacion_reportada->TooltipValue = "";

        // aplica_retencion
        $this->aplica_retencion->LinkCustomAttributes = "";
        $this->aplica_retencion->HrefValue = "";
        $this->aplica_retencion->TooltipValue = "";

        // ret_iva
        $this->ret_iva->LinkCustomAttributes = "";
        $this->ret_iva->HrefValue = "";
        $this->ret_iva->TooltipValue = "";

        // ref_iva
        $this->ref_iva->LinkCustomAttributes = "";
        if (!EmptyValue($this->id->CurrentValue)) {
            $this->ref_iva->HrefValue = "reportes/rptRetencionE.php?Nretencion=" . $this->id->CurrentValue; // Add prefix/suffix
            $this->ref_iva->LinkAttrs["target"] = "_blank"; // Add target
            if ($this->isExport()) {
                $this->ref_iva->HrefValue = FullUrl($this->ref_iva->HrefValue, "href");
            }
        } else {
            $this->ref_iva->HrefValue = "";
        }
        $this->ref_iva->TooltipValue = "";

        // ret_islr
        $this->ret_islr->LinkCustomAttributes = "";
        $this->ret_islr->HrefValue = "";
        $this->ret_islr->TooltipValue = "";

        // ref_islr
        $this->ref_islr->LinkCustomAttributes = "";
        if (!EmptyValue($this->id->CurrentValue)) {
            $this->ref_islr->HrefValue = "reportes/rptRetencionE2.php?Nretencion=" . $this->id->CurrentValue; // Add prefix/suffix
            $this->ref_islr->LinkAttrs["target"] = "_blank"; // Add target
            if ($this->isExport()) {
                $this->ref_islr->HrefValue = FullUrl($this->ref_islr->HrefValue, "href");
            }
        } else {
            $this->ref_islr->HrefValue = "";
        }
        $this->ref_islr->TooltipValue = "";

        // ret_municipal
        $this->ret_municipal->LinkCustomAttributes = "";
        $this->ret_municipal->HrefValue = "";
        $this->ret_municipal->TooltipValue = "";

        // ref_municipal
        $this->ref_municipal->LinkCustomAttributes = "";
        $this->ref_municipal->HrefValue = "";
        $this->ref_municipal->TooltipValue = "";

        // monto_pagar
        $this->monto_pagar->LinkCustomAttributes = "";
        $this->monto_pagar->HrefValue = "";
        $this->monto_pagar->TooltipValue = "";

        // comprobante
        $this->comprobante->LinkCustomAttributes = "";
        if (!EmptyValue($this->comprobante->CurrentValue)) {
            $this->comprobante->HrefValue = "ContAsientoList?showmaster=cont_comprobante&fk_id=" . $this->comprobante->CurrentValue; // Add prefix/suffix
            $this->comprobante->LinkAttrs["target"] = "_blank"; // Add target
            if ($this->isExport()) {
                $this->comprobante->HrefValue = FullUrl($this->comprobante->HrefValue, "href");
            }
        } else {
            $this->comprobante->HrefValue = "";
        }
        $this->comprobante->TooltipValue = "";

        // tipo_iva
        $this->tipo_iva->LinkCustomAttributes = "";
        $this->tipo_iva->HrefValue = "";
        $this->tipo_iva->TooltipValue = "";

        // tipo_islr
        $this->tipo_islr->LinkCustomAttributes = "";
        $this->tipo_islr->HrefValue = "";
        $this->tipo_islr->TooltipValue = "";

        // sustraendo
        $this->sustraendo->LinkCustomAttributes = "";
        $this->sustraendo->HrefValue = "";
        $this->sustraendo->TooltipValue = "";

        // fecha_registro_retenciones
        $this->fecha_registro_retenciones->LinkCustomAttributes = "";
        $this->fecha_registro_retenciones->HrefValue = "";
        $this->fecha_registro_retenciones->TooltipValue = "";

        // tasa_dia
        $this->tasa_dia->LinkCustomAttributes = "";
        $this->tasa_dia->HrefValue = "";
        $this->tasa_dia->TooltipValue = "";

        // monto_usd
        $this->monto_usd->LinkCustomAttributes = "";
        $this->monto_usd->HrefValue = "";
        $this->monto_usd->TooltipValue = "";

        // fecha_libro_compra
        $this->fecha_libro_compra->LinkCustomAttributes = "";
        $this->fecha_libro_compra->HrefValue = "";
        $this->fecha_libro_compra->TooltipValue = "";

        // tipo_municipal
        $this->tipo_municipal->LinkCustomAttributes = "";
        $this->tipo_municipal->HrefValue = "";
        $this->tipo_municipal->TooltipValue = "";

        // cerrado
        $this->cerrado->LinkCustomAttributes = "";
        $this->cerrado->HrefValue = "";
        $this->cerrado->TooltipValue = "";

        // cliente
        $this->cliente->LinkCustomAttributes = "";
        $this->cliente->HrefValue = "";
        $this->cliente->TooltipValue = "";

        // descuento
        $this->descuento->LinkCustomAttributes = "";
        $this->descuento->HrefValue = "";
        $this->descuento->TooltipValue = "";

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
        $this->tipo_documento->EditValue = $this->tipo_documento->CurrentValue;
        $this->tipo_documento->ViewCustomAttributes = "";

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

        // fecha
        $this->fecha->EditAttrs["class"] = "form-control";
        $this->fecha->EditCustomAttributes = "";
        $this->fecha->EditValue = FormatDateTime($this->fecha->CurrentValue, 7);
        $this->fecha->PlaceHolder = RemoveHtml($this->fecha->caption());

        // proveedor
        $this->proveedor->EditAttrs["class"] = "form-control";
        $this->proveedor->EditCustomAttributes = "";
        $curVal = trim(strval($this->proveedor->CurrentValue));
        if ($curVal != "") {
            $this->proveedor->EditValue = $this->proveedor->lookupCacheOption($curVal);
            if ($this->proveedor->EditValue === null) { // Lookup from database
                $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $sqlWrk = $this->proveedor->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->proveedor->Lookup->renderViewRow($rswrk[0]);
                    $this->proveedor->EditValue = $this->proveedor->displayValue($arwrk);
                } else {
                    $this->proveedor->EditValue = $this->proveedor->CurrentValue;
                }
            }
        } else {
            $this->proveedor->EditValue = null;
        }
        $this->proveedor->ViewCustomAttributes = "";

        // almacen
        $this->almacen->EditAttrs["class"] = "form-control";
        $this->almacen->EditCustomAttributes = "";
        $this->almacen->PlaceHolder = RemoveHtml($this->almacen->caption());

        // monto_total
        $this->monto_total->EditAttrs["class"] = "form-control";
        $this->monto_total->EditCustomAttributes = "";
        $this->monto_total->EditValue = $this->monto_total->CurrentValue;
        $this->monto_total->EditValue = FormatNumber($this->monto_total->EditValue, 2, -1, -1, -1);
        $this->monto_total->ViewCustomAttributes = "";

        // alicuota_iva
        $this->alicuota_iva->EditAttrs["class"] = "form-control";
        $this->alicuota_iva->EditCustomAttributes = "";
        $this->alicuota_iva->EditValue = $this->alicuota_iva->CurrentValue;
        $this->alicuota_iva->EditValue = FormatNumber($this->alicuota_iva->EditValue, 2, -1, -1, -1);
        $this->alicuota_iva->ViewCustomAttributes = "";

        // iva
        $this->iva->EditAttrs["class"] = "form-control";
        $this->iva->EditCustomAttributes = "";
        $this->iva->EditValue = $this->iva->CurrentValue;
        $this->iva->EditValue = FormatNumber($this->iva->EditValue, 2, -1, -1, -1);
        $this->iva->ViewCustomAttributes = "";

        // total
        $this->total->EditAttrs["class"] = "form-control";
        $this->total->EditCustomAttributes = "";
        $this->total->EditValue = $this->total->CurrentValue;
        $this->total->EditValue = FormatNumber($this->total->EditValue, 2, -1, -1, -1);
        $this->total->ViewCustomAttributes = "";

        // documento
        $this->documento->EditAttrs["class"] = "form-control";
        $this->documento->EditCustomAttributes = "";
        $this->documento->EditValue = $this->documento->options(true);
        $this->documento->PlaceHolder = RemoveHtml($this->documento->caption());

        // doc_afectado
        $this->doc_afectado->EditAttrs["class"] = "form-control";
        $this->doc_afectado->EditCustomAttributes = "";
        if (!$this->doc_afectado->Raw) {
            $this->doc_afectado->CurrentValue = HtmlDecode($this->doc_afectado->CurrentValue);
        }
        $this->doc_afectado->EditValue = $this->doc_afectado->CurrentValue;
        $this->doc_afectado->PlaceHolder = RemoveHtml($this->doc_afectado->caption());

        // nota
        $this->nota->EditAttrs["class"] = "form-control";
        $this->nota->EditCustomAttributes = "";
        $this->nota->EditValue = $this->nota->CurrentValue;
        $this->nota->PlaceHolder = RemoveHtml($this->nota->caption());

        // estatus
        $this->estatus->EditAttrs["class"] = "form-control";
        $this->estatus->EditCustomAttributes = "";
        $this->estatus->EditValue = $this->estatus->options(true);
        $this->estatus->PlaceHolder = RemoveHtml($this->estatus->caption());

        // username
        $this->_username->EditAttrs["class"] = "form-control";
        $this->_username->EditCustomAttributes = "";
        if (!$this->_username->Raw) {
            $this->_username->CurrentValue = HtmlDecode($this->_username->CurrentValue);
        }
        $this->_username->EditValue = $this->_username->CurrentValue;
        $this->_username->PlaceHolder = RemoveHtml($this->_username->caption());

        // id_documento_padre
        $this->id_documento_padre->EditAttrs["class"] = "form-control";
        $this->id_documento_padre->EditCustomAttributes = "";
        $this->id_documento_padre->EditValue = $this->id_documento_padre->CurrentValue;
        $this->id_documento_padre->PlaceHolder = RemoveHtml($this->id_documento_padre->caption());

        // moneda
        $this->moneda->EditAttrs["class"] = "form-control";
        $this->moneda->EditCustomAttributes = "";
        $this->moneda->PlaceHolder = RemoveHtml($this->moneda->caption());

        // consignacion
        $this->consignacion->EditAttrs["class"] = "form-control";
        $this->consignacion->EditCustomAttributes = "";
        $this->consignacion->EditValue = $this->consignacion->options(true);
        $this->consignacion->PlaceHolder = RemoveHtml($this->consignacion->caption());

        // consignacion_reportada
        $this->consignacion_reportada->EditAttrs["class"] = "form-control";
        $this->consignacion_reportada->EditCustomAttributes = "";
        $this->consignacion_reportada->EditValue = $this->consignacion_reportada->options(true);
        $this->consignacion_reportada->PlaceHolder = RemoveHtml($this->consignacion_reportada->caption());

        // aplica_retencion
        $this->aplica_retencion->EditCustomAttributes = "";
        $this->aplica_retencion->EditValue = $this->aplica_retencion->options(false);
        $this->aplica_retencion->PlaceHolder = RemoveHtml($this->aplica_retencion->caption());

        // ret_iva
        $this->ret_iva->EditAttrs["class"] = "form-control";
        $this->ret_iva->EditCustomAttributes = "";
        $this->ret_iva->EditValue = $this->ret_iva->CurrentValue;
        $this->ret_iva->PlaceHolder = RemoveHtml($this->ret_iva->caption());
        if (strval($this->ret_iva->EditValue) != "" && is_numeric($this->ret_iva->EditValue)) {
            $this->ret_iva->EditValue = FormatNumber($this->ret_iva->EditValue, -2, -1, -2, -1);
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
            $this->ret_islr->EditValue = FormatNumber($this->ret_islr->EditValue, -2, -1, -2, -1);
        }

        // ref_islr
        $this->ref_islr->EditAttrs["class"] = "form-control";
        $this->ref_islr->EditCustomAttributes = "";
        if (!$this->ref_islr->Raw) {
            $this->ref_islr->CurrentValue = HtmlDecode($this->ref_islr->CurrentValue);
        }
        $this->ref_islr->EditValue = $this->ref_islr->CurrentValue;
        $this->ref_islr->PlaceHolder = RemoveHtml($this->ref_islr->caption());

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

        // monto_pagar
        $this->monto_pagar->EditAttrs["class"] = "form-control";
        $this->monto_pagar->EditCustomAttributes = "";
        $this->monto_pagar->EditValue = $this->monto_pagar->CurrentValue;
        $this->monto_pagar->PlaceHolder = RemoveHtml($this->monto_pagar->caption());
        if (strval($this->monto_pagar->EditValue) != "" && is_numeric($this->monto_pagar->EditValue)) {
            $this->monto_pagar->EditValue = FormatNumber($this->monto_pagar->EditValue, -2, -1, -2, -1);
        }

        // comprobante
        $this->comprobante->EditAttrs["class"] = "form-control";
        $this->comprobante->EditCustomAttributes = "";
        $this->comprobante->PlaceHolder = RemoveHtml($this->comprobante->caption());

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

        // sustraendo
        $this->sustraendo->EditAttrs["class"] = "form-control";
        $this->sustraendo->EditCustomAttributes = "";
        $this->sustraendo->EditValue = $this->sustraendo->CurrentValue;
        $this->sustraendo->PlaceHolder = RemoveHtml($this->sustraendo->caption());
        if (strval($this->sustraendo->EditValue) != "" && is_numeric($this->sustraendo->EditValue)) {
            $this->sustraendo->EditValue = FormatNumber($this->sustraendo->EditValue, -2, -1, -2, 0);
        }

        // fecha_registro_retenciones
        $this->fecha_registro_retenciones->EditAttrs["class"] = "form-control";
        $this->fecha_registro_retenciones->EditCustomAttributes = "";
        $this->fecha_registro_retenciones->EditValue = FormatDateTime($this->fecha_registro_retenciones->CurrentValue, 8);
        $this->fecha_registro_retenciones->PlaceHolder = RemoveHtml($this->fecha_registro_retenciones->caption());

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

        // fecha_libro_compra
        $this->fecha_libro_compra->EditAttrs["class"] = "form-control";
        $this->fecha_libro_compra->EditCustomAttributes = "";
        $this->fecha_libro_compra->EditValue = FormatDateTime($this->fecha_libro_compra->CurrentValue, 7);
        $this->fecha_libro_compra->PlaceHolder = RemoveHtml($this->fecha_libro_compra->caption());

        // tipo_municipal
        $this->tipo_municipal->EditAttrs["class"] = "form-control";
        $this->tipo_municipal->EditCustomAttributes = "";
        if (!$this->tipo_municipal->Raw) {
            $this->tipo_municipal->CurrentValue = HtmlDecode($this->tipo_municipal->CurrentValue);
        }
        $this->tipo_municipal->EditValue = $this->tipo_municipal->CurrentValue;
        $this->tipo_municipal->PlaceHolder = RemoveHtml($this->tipo_municipal->caption());

        // cerrado
        $this->cerrado->EditCustomAttributes = "";
        $this->cerrado->EditValue = $this->cerrado->options(false);
        $this->cerrado->PlaceHolder = RemoveHtml($this->cerrado->caption());

        // cliente
        $this->cliente->EditAttrs["class"] = "form-control";
        $this->cliente->EditCustomAttributes = "";
        $this->cliente->PlaceHolder = RemoveHtml($this->cliente->caption());

        // descuento
        $this->descuento->EditAttrs["class"] = "form-control";
        $this->descuento->EditCustomAttributes = "";
        $this->descuento->EditValue = $this->descuento->CurrentValue;
        $this->descuento->PlaceHolder = RemoveHtml($this->descuento->caption());
        if (strval($this->descuento->EditValue) != "" && is_numeric($this->descuento->EditValue)) {
            $this->descuento->EditValue = FormatNumber($this->descuento->EditValue, -2, -2, -2, -2);
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
                    $doc->exportCaption($this->tipo_documento);
                    $doc->exportCaption($this->nro_documento);
                    $doc->exportCaption($this->nro_control);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->proveedor);
                    $doc->exportCaption($this->monto_total);
                    $doc->exportCaption($this->alicuota_iva);
                    $doc->exportCaption($this->iva);
                    $doc->exportCaption($this->total);
                    $doc->exportCaption($this->documento);
                    $doc->exportCaption($this->doc_afectado);
                    $doc->exportCaption($this->nota);
                    $doc->exportCaption($this->estatus);
                    $doc->exportCaption($this->_username);
                    $doc->exportCaption($this->moneda);
                    $doc->exportCaption($this->aplica_retencion);
                    $doc->exportCaption($this->ret_iva);
                    $doc->exportCaption($this->ref_iva);
                    $doc->exportCaption($this->ret_islr);
                    $doc->exportCaption($this->ref_islr);
                    $doc->exportCaption($this->ret_municipal);
                    $doc->exportCaption($this->ref_municipal);
                    $doc->exportCaption($this->monto_pagar);
                    $doc->exportCaption($this->tipo_iva);
                    $doc->exportCaption($this->tipo_islr);
                    $doc->exportCaption($this->sustraendo);
                    $doc->exportCaption($this->tasa_dia);
                    $doc->exportCaption($this->monto_usd);
                    $doc->exportCaption($this->tipo_municipal);
                    $doc->exportCaption($this->cliente);
                    $doc->exportCaption($this->descuento);
                } else {
                    $doc->exportCaption($this->tipo_documento);
                    $doc->exportCaption($this->nro_documento);
                    $doc->exportCaption($this->nro_control);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->proveedor);
                    $doc->exportCaption($this->monto_total);
                    $doc->exportCaption($this->alicuota_iva);
                    $doc->exportCaption($this->iva);
                    $doc->exportCaption($this->total);
                    $doc->exportCaption($this->documento);
                    $doc->exportCaption($this->doc_afectado);
                    $doc->exportCaption($this->estatus);
                    $doc->exportCaption($this->_username);
                    $doc->exportCaption($this->moneda);
                    $doc->exportCaption($this->descuento);
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
                        $doc->exportField($this->tipo_documento);
                        $doc->exportField($this->nro_documento);
                        $doc->exportField($this->nro_control);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->proveedor);
                        $doc->exportField($this->monto_total);
                        $doc->exportField($this->alicuota_iva);
                        $doc->exportField($this->iva);
                        $doc->exportField($this->total);
                        $doc->exportField($this->documento);
                        $doc->exportField($this->doc_afectado);
                        $doc->exportField($this->nota);
                        $doc->exportField($this->estatus);
                        $doc->exportField($this->_username);
                        $doc->exportField($this->moneda);
                        $doc->exportField($this->aplica_retencion);
                        $doc->exportField($this->ret_iva);
                        $doc->exportField($this->ref_iva);
                        $doc->exportField($this->ret_islr);
                        $doc->exportField($this->ref_islr);
                        $doc->exportField($this->ret_municipal);
                        $doc->exportField($this->ref_municipal);
                        $doc->exportField($this->monto_pagar);
                        $doc->exportField($this->tipo_iva);
                        $doc->exportField($this->tipo_islr);
                        $doc->exportField($this->sustraendo);
                        $doc->exportField($this->tasa_dia);
                        $doc->exportField($this->monto_usd);
                        $doc->exportField($this->tipo_municipal);
                        $doc->exportField($this->cliente);
                        $doc->exportField($this->descuento);
                    } else {
                        $doc->exportField($this->tipo_documento);
                        $doc->exportField($this->nro_documento);
                        $doc->exportField($this->nro_control);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->proveedor);
                        $doc->exportField($this->monto_total);
                        $doc->exportField($this->alicuota_iva);
                        $doc->exportField($this->iva);
                        $doc->exportField($this->total);
                        $doc->exportField($this->documento);
                        $doc->exportField($this->doc_afectado);
                        $doc->exportField($this->estatus);
                        $doc->exportField($this->_username);
                        $doc->exportField($this->moneda);
                        $doc->exportField($this->descuento);
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
        $table = 'entradas';
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
        $table = 'entradas';

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
        $table = 'entradas';

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
        $table = 'entradas';

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
    	if(isset($_REQUEST["tipo"])) {
       		$tipo = $_REQUEST["tipo"];
    	}
    	elseif(isset($_REQUEST["x_tipo_documento"])) {
       		$tipo = $_REQUEST["x_tipo_documento"];
    	}
    	else {
    		$sql = "SELECT tipo_documento FROM username_tipo_documento WHERE username = '" . CurrentUserName() . "';";
        	$tipo = ExecuteScalar($sql);
        	if($tipo == "") {
    			header("Location: Home");
    			die();
    		}
    	}

    	/* *** BIEN *** */
    	$sql = "UPDATE  
    				entradas_salidas AS a 
    				JOIN articulo AS b ON b.id = a.articulo 
    			SET 
    				a.lote = b.codigo_ims 
    			WHERE a.lote IS NULL;";
    	Execute($sql);
    	/* ****** */

    	/* *** BIEN *** */
    	$sql = "SELECT tipo_acceso FROM userlevels WHERE userlevelid = '" . CurrentUserLevel() . "';";
    	$grupo = trim(ExecuteScalar($sql));
    	$proveedor = 0;
    	if($grupo == "PROVEEDOR") {
    		$sql = "SELECT proveedor FROM usuario WHERE username = '" . CurrentUserName() . "';";
    		$proveedor = trim(ExecuteScalar($sql));
    		AddFilter($filter, "proveedor = $proveedor AND tipo_documento = 'TDCFCC'");
    	}
    	else {
    		AddFilter($filter, "tipo_documento = '$tipo'");
    	}
    	/* ****** */

    	/* *** Registro último tipo de entrada usuado *** */
    	$sql = "DELETE FROM username_tipo_documento WHERE username = '" . CurrentUserName() . "';";
        Execute($sql);
        $sql = "INSERT INTO username_tipo_documento
        				(id, username, tipo_documento)
        				VALUES (NULL, '" . CurrentUserName() . "', '$tipo');";
        Execute($sql);
    	/* ****** */
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
    	$sql = "SELECT valor1 FROM parametro WHERE codigo = '006' AND valor2 = 'default';";
    	$moneda = ExecuteScalar($sql);
    	$rsnew["moneda"] = $moneda;
    	$tipo = $rsnew["tipo_documento"];

    	// Se obtiene el consecutivo del tipo de documento
    	$sql = "SELECT MAX(CAST(IFNULL(nro_documento, 0) AS UNSIGNED)) AS cosecutivo FROM entradas WHERE tipo_documento = '$tipo';";
    	$consecutivo = intval(ExecuteScalar($sql)) + 1;
    	switch($tipo) {
    	case "TDCPDC":
    		$rsnew["nro_documento"] = str_pad($consecutivo, 7, "0", STR_PAD_LEFT);
    		$rsnew["fecha"] = date("Y-m-d");
    		break;
    	case "TDCNRP":
    		$rsnew["fecha"] = date("Y-m-d");
    		break;
    	case "TDCFCC":
    		// Valido cierre de facturación
    		$sql = "SELECT DISTINCT cerrado AS cerrado FROM entradas WHERE YEAR(fecha) = " . intval(substr($rsnew["fecha"], 0, 4)) . " AND MONTH(fecha) = " . intval(substr($rsnew["fecha"], 5, 2)) . ";";
    		if($row = ExecuteRow($sql)) {
    			if($row["cerrado"] == "S") {
    				$this->CancelMessage = "El mes en el que se va a crear la factura est&aacute; cerrado. Verifique!";
    				return FALSE;
    			}
    		}

    		// Valido que ya no se haya registrado el número de factura 
    		$proveedor = $rsnew["proveedor"];
    		$rsnew["nro_documento"] = trim($rsnew["nro_documento"]);
    		$documento = $rsnew["nro_documento"];
    		$sql = "SELECT documento FROM entradas WHERE tipo_documento = 'TDCFCC' AND proveedor = $proveedor AND nro_documento = '$documento';";
    		if($row = ExecuteRow($sql)){
    			$this->CancelMessage = "El n&uacute;mero de factura ya est&aacute; registrado para el proveedor; verifique.";
    			return FALSE;
    		}
    		break;
    	case "TDCAEN":
    		$rsnew["nro_documento"] = str_pad($consecutivo, 7, "0", STR_PAD_LEFT);
    		$rsnew["fecha"] = date("Y-m-d");
    		break;
    	case "TDCASA":
    		$rsnew["nro_documento"] = str_pad($consecutivo, 7, "0", STR_PAD_LEFT);
    		$rsnew["fecha"] = date("Y-m-d");
    		break;
    	}

    	// Se establecen valores por defecto a varibles bitácora y estatus
    	$rsnew["username"] = CurrentUserName();
    	$rsnew["estatus"] = "NUEVO";
    	if($tipo=="TDCNRP") {
    		if(trim($rsnew["cliente"]) != "") $rsnew["estatus"] = "PROCESADO";
    	}
    	$rsnew["fecha_libro_compra"] = $rsnew["fecha"];
    	if($rsnew["documento"] == "FC" or trim($rsnew["documento"]) == "") {
    		$rsnew["doc_afectado"] == ""; 
    	} 
    	else {
    		if($rsnew["doc_afectado"] == "") {
    			$this->CancelMessage = "Debe colocar n&uacute;mero de documento afectado.";
    			return FALSE;
    		}
    	}
    	if(isset($rsnew["consignacion"])) {
    		if(trim($rsnew["consignacion"]) != "S") $rsnew["consignacion"] = "N";
    	}
    	return TRUE;
    }

    // Row Inserted event
    public function rowInserted($rsold, &$rsnew) {
    	//echo "Row Inserted"
    	$tipo = $rsnew["tipo_documento"];
    	switch($tipo) {
    	case "TDCPDC":
    		/**** Almacen por defecto ****/
    		$sql = "SELECT valor1 AS almacen FROM parametro WHERE codigo = '002';";
    		$almacen = ExecuteScalar($sql);

    		// Asigna a la compra detalles proveniente de los articulos que por defecto vende el proveedor
    		$sql = "SELECT COUNT(id) AS cantidad
    				FROM entradas_salidas
    				WHERE id_documento = '" . $rsnew["id"] . "'
    					AND tipo_documento = '" . $rsnew["tipo_documento"] . "';";
    		$cantidad = ExecuteScalar($sql);
    		if($cantidad == 0) {
    			$sql = "INSERT INTO entradas_salidas
    				(id, tipo_documento, id_documento, fabricante, 
    				articulo, articulo_unidad_medida, 
    				cantidad_unidad_medida, almacen, cantidad_articulo)
    			SELECT 
    				NULL, 'TDCPDC', '" . $rsnew["id"] . "', a.fabricante,
    				a.articulo, b.unidad_medida_defecto, 
    				b.cantidad_por_unidad_medida, '$almacen', 0 
    			FROM 
    				proveedor_articulo AS a 
    				JOIN articulo AS b ON b.id = a.articulo 
    			WHERE 
    				a.proveedor = '" . $rsnew["proveedor"] . "';"; 
    			Execute($sql);
    		}
    		break;
    	case "TDCNRP":
            $cliente = $rsnew["cliente"];
            if($cliente != "") {
    			$sql = "SELECT IFNULL(SUM(costo), 0) AS costo
    					FROM entradas_salidas
    					WHERE
    						id_documento = " . $rsnew["id"] . "
    						AND tipo_documento = '" . $rsnew["tipo_documento"] . "'";
    			$monto_moneda = floatval(ExecuteScalar($sql));
                $sql = "SELECT valor1 FROM parametro WHERE codigo = '006' AND valor2 = 'default';";
                $moneda = ExecuteScalar($sql);
                $sql = "SELECT tasa FROM tasa_usd
                        WHERE moneda = '$moneda' ORDER BY id DESC LIMIT 0, 1;";
                $tasa = ExecuteScalar($sql);
                $nota = $rsnew["nota"];
                $referencia = $rsnew["id"];
                $metodo_pago = "DV";
                $monto_bs = $tasa*$monto_moneda;
                $tasa_usd = $tasa;
                $monto_usd = $monto_moneda;
                $username = CurrentUserName();
                $sql = "SELECT IFNULL(MAX(nro_recibo), 0)+1 AS nro FROM recarga WHERE 1;";
                $nro_recibo = ExecuteScalar($sql);
                $sql = "INSERT INTO recarga(
                            id,
                            cliente,
                            fecha,
                            metodo_pago,
                            monto_moneda,
                            moneda,
                            tasa_moneda,
                            monto_bs,
                            tasa_usd,
                            monto_usd,
                            saldo,
                            nota,
                            username, reverso, nota_recepcion, nro_recibo)
                        VALUES (
                            NULL,
                            $cliente,
                            NOW(),
                            '$metodo_pago',
                            $monto_moneda,
                            '$moneda',
                            $tasa,
                            $monto_bs,
                            $tasa_usd,
                            $monto_usd,
                            0,
                            'Devolución de Nota de Recepción según nota: $nota',
                            '$username', 'N', $referencia, '$nro_recibo')"; 
                Execute($sql);
                $sql = "SELECT LAST_INSERT_ID();";
                $id = ExecuteScalar($sql);
                $sql = "SELECT IFNULL(SUM(monto_usd), 0) AS saldo FROM recarga
                        WHERE cliente = $cliente;";
                $saldo = ExecuteScalar($sql);
                $sql = "UPDATE recarga SET saldo = $saldo WHERE id = $id;";
                Execute($sql);
                $sql = "UPDATE entradas SET nro_documento = 'ABONO - $nro_recibo' WHERE id = $referencia;";
                Execute($sql);
            }
            break;
    	case "TDCFCC":
    		$sql = "SELECT valor1 FROM parametro WHERE codigo = '026';";
    		$CmpbIVAAuto = ExecuteScalar($sql);
    		if($CmpbIVAAuto == "S") {
    			$sql = "SELECT valor1, valor2, valor3 FROM parametro WHERE codigo = '023';";
    			$row = ExecuteRow($sql);
        		$numero = intval($row["valor1"]) + 1;
    			$prefijo = trim($row["valor2"]);
    			$prefijo .= substr($rsnew["fecha"], 0, 4) . substr($rsnew["fecha"], 5, 2);
    			$padeo = intval($row["valor3"]);
    			$comprobante = $prefijo . str_pad($numero, $padeo, "0", STR_PAD_LEFT);
    			$sql = "UPDATE parametro SET valor1='$numero' 
    					WHERE codigo = '023';";
    			Execute($sql);
    			$sql = "UPDATE entradas SET ref_iva = '$comprobante' 
    					WHERE id = '" . $rsnew["id"] . "';";
    			Execute($sql);
    		}
            $sql = "SELECT valor1 FROM parametro WHERE codigo = '028';";
            $CmpbMuniAuto = ExecuteScalar($sql);
    		if($CmpbMuniAuto == "S") {
    			$sql = "SELECT valor1, valor2, valor3 FROM parametro WHERE codigo = '025';";
    			$row = ExecuteRow($sql);
    			$numero = intval($row["valor1"]) + 1;
    			$prefijo = trim($row["valor2"]);
    			$prefijo .= substr($rsold["fecha"], 0, 4) . substr($rsnew["fecha"], 5, 2);
    			$padeo = intval($row["valor3"]);
    			$comprobante = $prefijo . str_pad($numero, $padeo, "0", STR_PAD_LEFT);
    			$sql = "UPDATE parametro SET valor1='$numero' 
    					WHERE codigo = '025';";
    			Execute($sql);
    			$sql = "UPDATE entradas SET ref_municipal = '$comprobante' 
    					WHERE id = '" . $rsnew["id"] . "';";
    			Execute($sql);
    		}
    		break;
    	case "TDCAEN":
    		break;
    	}
    }

    // Row Updating event
    public function rowUpdating($rsold, &$rsnew) {
    	// Enter your code here
    	// To cancel, set return value to FALSE
    	if($rsold["estatus"] == "ANULADO") {
    		$this->CancelMessage = "Este documento est&aacute; anulado; no se puede modificar.";
    		return FALSE;
    	}
    	if(CurrentUserLevel() != -1) {
    	// if($rsnew["estatus"] != "ANULADO") {
    		if($rsold["estatus"] != "NUEVO") {
    			$this->CancelMessage = "Este documento est&aacute; procesado o anulado; no se puede modificar.";
    			return FALSE;
    		}
    	// }
    	}
    	if($rsold["tipo_documento"] == "TDCFCC") {
    		// Valido cierre de facturación
    		if($rsold["cerrado"] == "S") {
    			$this->CancelMessage = "El mes en el que est&aacute; la factura est&aacute; cerrado. Verifique!";
    			return FALSE;
    		}
    	}
    	if(($rsnew["fecha_registro_retenciones"] == "" or $rsnew["fecha_registro_retenciones"] == "0000-00-00") and (trim($rsold["ref_iva"]) == "" or trim($rsold["ref_islr"] == ""))) {
    		$rsnew["fecha_registro_retenciones"] = date("Y-m-d");
    	}
    	if($rsnew["documento"] == "FC" or trim($rsnew["documento"]) == "") {
    		$rsnew["doc_afectado"] == ""; 
    	} 
    	else {
    		if($rsnew["doc_afectado"] == "") {
    			$this->CancelMessage = "Debe colocar n&uacute;mero de documento afectado.";
    			return FALSE;
    		}
    	}
    	if($rsold["tipo_documento"] == "TDCFCC") {
    		if($rsold["ref_iva"] != "") {
    			if($rsold["ref_iva"] != $rsnew["ref_iva"]) {
    				if(!VerificaFuncion('016')) {
    					$this->CancelMessage = "No est&aacute; autorizado para cambiar n&uacute;mero de comprobante de IVA; verifique.";
    					return FALSE;
    				}
    			}
    		}
    		if($rsold["ref_islr"] != "") {
    			if($rsold["ref_islr"] != $rsnew["ref_islr"]) {
    				if(!VerificaFuncion('017')) {
    					$this->CancelMessage = "No est&aacute; autorizado para cambiar n&uacute;mero de comprobante de ISLR; verifique.";
    					return FALSE;
    				}
    			}
    		}
    		if($rsold["ref_municipal"] != "") {
    			if($rsold["ref_municipal"] != $rsnew["ref_municipal"]) {
    				if(!VerificaFuncion('018')) {
    					$this->CancelMessage = "No est&aacute; autorizado para cambiar n&uacute;mero de comprobante de Impuesto Municipal; verifique.";
    					return FALSE;
    				}
    			}
    		}
    		if($rsold["comprobante"] != "") {
    			$this->CancelMessage = "Este documento est&aacute; contabilizado; no se puede modificar.";
    			return FALSE;
    		}
    	}
    	if($rsold["tipo_documento"] == "TDCNRP") {
    		if($rsold["nro_documento"] != $rsnew["nro_documento"]) {
    			$sql = "SELECT nro_documento
    					FROM entradas
    					WHERE tipo_documento = '" . $rsold["tipo_documento"] . "'
    						AND RTRIM(nro_documento) = '" . trim($rsnew["nro_documento"]) . "';";
    			if($row = ExecuteRow($sql)) {
    				$this->CancelMessage = "Nro. de documento " . trim($rsnew["nro_documento"]) . " ya existe; verifique.";
    				return FALSE;
    			}
    		}
    	}
    	return TRUE;
    }

    // Row Updated event
    public function rowUpdated($rsold, &$rsnew) {
    	//echo "Row Updated";
    	if($rsold["consignacion"] != $rsnew["consignacion"]) {
    		if($rsnew["consignacion"] == "S") {
    			$sql = "SELECT valor1 AS almacen FROM parametro WHERE codigo = '014';";
    			$almacen = ExecuteScalar($sql);
    		}
    		else {
    			$sql = "SELECT valor1 AS almacen FROM parametro WHERE codigo = '002';";
    			$almacen = ExecuteScalar($sql);
    		}
    		$sql = "UPDATE entradas_salidas
    				SET
    					almacen='$almacen' 
    				WHERE
    					tipo_documento = '" . $rsold["tipo_documento"] . "' AND id_documento = " . $rsold["id"] . "";
    		Execute($sql);
    	}

    	/* ------- Actualizo cantidad en mano, en pedido y en transito  ------- */
    	$sql = "SELECT COUNT(articulo) AS cantidad 
    			FROM entradas_salidas
    			WHERE tipo_documento = '" . $rsold["tipo_documento"] . "'
    				AND id_documento = " . $rsold["id"] . ";";
    	$cantidad = ExecuteScalar($sql);
    	for($i = 0; $i < $cantidad; $i++) {
    		$sql = "SELECT articulo
    				FROM entradas_salidas
    				WHERE
    					tipo_documento = '" . $rsold["tipo_documento"] . "'
    					AND id_documento = " . $rsold["id"] . " LIMIT $i, 1;";
    		$articulo = ExecuteScalar($sql);
    		ActualizarExitenciaArticulo($articulo);
    	}
    	if($rsold["tipo_documento"] == "TDCFCC") {
    		CalcularRetenciones($rsold["id"], $rsold["tipo_documento"]);
    	}

    	/* Actualizo Monto de compra en los casos de pedido de compra y notas de recepción */
    	$tipo = $rsold["tipo_documento"];
    	if($tipo == "TDCPDC" or $tipo == "TDCNRP") {
    		$sql = "SELECT moneda FROM entradas WHERE id = '" . $rsold["id"]. "'";
    		$moneda = ExecuteScalar($sql);
    		if(substr(strtoupper($moneda), 0, 2) == "BS") {
    			$alicuota = floatval($rsold["alicuota_iva"]);
    			$sql = "SELECT 
    					SUM(costo) AS costo, 
    					SUM((costo * (alicuota/100))) AS iva, 
    					SUM(costo) + SUM((costo * (alicuota/100))) AS total 
    				FROM 
    					entradas_salidas
    				WHERE tipo_documento = '" . $rsold["tipo_documento"]. "' AND 
    					id_documento = '" . $rsold["id"]. "'";
    		}
    		else {
    			$alicuota = 0;
    			$sql = "SELECT 
    					SUM(costo) AS costo,
    					0 AS iva,
    					SUM(costo) AS total 
    				FROM 
    					entradas_salidas
    				WHERE tipo_documento = '" . $rsold["tipo_documento"]. "' AND 
    					id_documento = '" . $rsold["id"]. "'";
    		}
    		$row = ExecuteRow($sql);
    		$costo = floatval($row["costo"]);
    		$iva = floatval($row["iva"]);
    		$total = floatval($row["total"]);
    		$sql = "UPDATE entradas 
    				SET
    					monto_total = $costo,
    					alicuota_iva = $alicuota, 
    					iva = $iva,
    					total = $total
    				WHERE tipo_documento = '" . $rsold["tipo_documento"]. "' AND
    					id = '" . $rsold["id"] . "';"; 
    		Execute($sql);
    	}
    	elseif($tipo == "TDCFCV") {
    		ActualizarTotalFacturaVenta($rsnew["id_documento"], $rsnew["tipo_documento"]);
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

       	/////////////////
       	if($rs["tipo_documento"] == "TDCNRP") {
    		if(!VerificaFuncion('039')) {
    			$this->CancelMessage = "No est&aacute; autorizado para eliminar la Nota de Recepci&oacute;n.";
    			return FALSE;
    		}
    	}
    	/////////////////
    	if(intval($rs["cliente"]) > 0) {
    		$this->CancelMessage = "Este documento tiene abonos asociados; no se puede eliminar.";
    		return FALSE;
    	}
    	if(CurrentUserLevel() != -1) {
    		if($rs["estatus"] != "NUEVO") {
    			$this->CancelMessage = "Este documento est&aacute; procesado o anulado; no se puede eliminar.";
    			return FALSE;
    		}
    	}
    	if($rs["tipo_documento"] == "TDCFCC") {
    		// Valido cierre de facturación
    		/*
    		if($rs["cerrado"] == "S") {
    			$this->CancelMessage = "El mes en el que est&aacute; la factura est&aacute; cerrado. Verifique!";
    			return FALSE;
    		}
    		if($rs["ref_iva"] != "") {
    			$this->CancelMessage = "El documento tiene n&uacute;mero de comprobante de IVA asociado; no se puede eliminar.";
    			return FALSE;
    		}
    		if($rs["ref_islr"] != "") {
    			$this->CancelMessage = "El documento tiene n&uacute;mero de comprobante de ISLR asociado; no se puede eliminar.";
    			return FALSE;
    		}
    		if($rs["ref_municipal"] != "") {
    			$this->CancelMessage = "El documento tiene n&uacute;mero de comprobante de Impuesto Municipal asociado; no se puede eliminar.";
    			return FALSE;
    		}
    		if($rs["comprobante"] != "") {
    			$this->CancelMessage = "Este documento est&aacute; contabilizado; no se puede eliminar.";
    			return FALSE;
    		}
    		*/
    	}
    	$sql = "DELETE FROM entradas_salidas WHERE tipo_documento = '" . $rs["tipo_documento"] . "' AND id_documento = '" . $rs["id"] . "';";
    	Execute($sql);
    	return TRUE;
    }

    // Row Deleted event
    public function rowDeleted(&$rs) {
    	//echo "Row Deleted";
    	/* ------- Actualizo cantidad en mano, en pedido y en transito  ------- */
    	/*$sql = "SELECT COUNT(articulo) AS cantidad 
    			FROM entradas_salidas
    			WHERE
    				tipo_documento = '" . $rs["tipo_documento"] . "'
    				AND id_documento = " . $rs["id"] . ";";
    	$cantidad = ExecuteScalar($sql);
    	for($i = 0; $i < $cantidad; $i++) {
    		$sql = "SELECT articulo
    				FROM entradas_salidas
    				WHERE
    					tipo_documento = '" . $rs["tipo_documento"] . "'
    					AND id_documento = " . $rs["id"] . " LIMIT $i, 1;";
    		$articulo = ExecuteScalar($sql);
    		ActualizarExitenciaArticulo($articulo);
    	}*/
    	ActualizarExitencia();
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
    	$tipo = $this->tipo_documento->CurrentValue;
    	$color = "";
    	if ($this->PageID == "list" || $this->PageID == "view") {
    		if ($this->estatus->ViewValue == "NUEVO") { 
    			$color = "background-color: #eda135; color: #ffffff;";
    		} elseif ($this->estatus->ViewValue == "PROCESADO") {
    			$color = "background-color: #51aa51; color: #ffffff;";
    		} elseif ($this->estatus->ViewValue == "ANULADO") {
    			$color = "background-color: #cc3f3b; color: #ffffff;";
    		}
    		$this->tipo_documento->CellAttrs["style"] = $color;
    		$this->nro_documento->CellAttrs["style"] = $color;
    		$this->estatus->CellAttrs["style"] = $color;
    		$this->nro_control->CellAttrs["style"] = $color;
    		$this->fecha->CellAttrs["style"] = $color;
    		$this->total->CellAttrs["style"] = $color;
    	}
    }

    // User ID Filtering event
    public function userIdFiltering(&$filter)
    {
        // Enter your code here
    }
}
