<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for salidas
 */
class Salidas extends DbTable
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
    public $cliente;
    public $documento;
    public $doc_afectado;
    public $moneda;
    public $monto_total;
    public $alicuota_iva;
    public $iva;
    public $total;
    public $tasa_dia;
    public $monto_usd;
    public $lista_pedido;
    public $nota;
    public $_username;
    public $estatus;
    public $id_documento_padre;
    public $asesor;
    public $dias_credito;
    public $entregado;
    public $fecha_entrega;
    public $pagado;
    public $bultos;
    public $fecha_bultos;
    public $user_bultos;
    public $fecha_despacho;
    public $user_despacho;
    public $consignacion;
    public $unidades;
    public $descuento;
    public $monto_sin_descuento;
    public $factura;
    public $ci_rif;
    public $nombre;
    public $direccion;
    public $telefono;
    public $_email;
    public $activo;
    public $comprobante;
    public $nro_despacho;
    public $cerrado;
    public $impreso;
    public $igtf;
    public $monto_base_igtf;
    public $monto_igtf;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'salidas';
        $this->TableName = 'salidas';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`salidas`";
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
        $this->id = new DbField('salidas', 'salidas', 'x_id', 'id', '`id`', '`id`', 19, 10, -1, false, '`id`', false, false, false, 'FORMATTED TEXT', 'NO');
        $this->id->IsAutoIncrement = true; // Autoincrement field
        $this->id->IsPrimaryKey = true; // Primary key field
        $this->id->IsForeignKey = true; // Foreign key field
        $this->id->Sortable = true; // Allow sort
        $this->id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->id->Param, "CustomMsg");
        $this->Fields['id'] = &$this->id;

        // tipo_documento
        $this->tipo_documento = new DbField('salidas', 'salidas', 'x_tipo_documento', 'tipo_documento', '`tipo_documento`', '`tipo_documento`', 200, 6, -1, false, '`tipo_documento`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->tipo_documento->IsForeignKey = true; // Foreign key field
        $this->tipo_documento->Required = true; // Required field
        $this->tipo_documento->Sortable = true; // Allow sort
        $this->tipo_documento->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tipo_documento->Param, "CustomMsg");
        $this->Fields['tipo_documento'] = &$this->tipo_documento;

        // nro_documento
        $this->nro_documento = new DbField('salidas', 'salidas', 'x_nro_documento', 'nro_documento', '`nro_documento`', '`nro_documento`', 200, 20, -1, false, '`nro_documento`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->nro_documento->Sortable = true; // Allow sort
        $this->nro_documento->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->nro_documento->Param, "CustomMsg");
        $this->Fields['nro_documento'] = &$this->nro_documento;

        // nro_control
        $this->nro_control = new DbField('salidas', 'salidas', 'x_nro_control', 'nro_control', '`nro_control`', '`nro_control`', 200, 100, -1, false, '`nro_control`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->nro_control->Sortable = true; // Allow sort
        $this->nro_control->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->nro_control->Param, "CustomMsg");
        $this->Fields['nro_control'] = &$this->nro_control;

        // fecha
        $this->fecha = new DbField('salidas', 'salidas', 'x_fecha', 'fecha', '`fecha`', CastDateFieldForLike("`fecha`", 7, "DB"), 135, 19, 7, false, '`fecha`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->fecha->Required = true; // Required field
        $this->fecha->Sortable = true; // Allow sort
        $this->fecha->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_SEPARATOR"], $Language->phrase("IncorrectDateDMY"));
        $this->fecha->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->fecha->Param, "CustomMsg");
        $this->Fields['fecha'] = &$this->fecha;

        // cliente
        $this->cliente = new DbField('salidas', 'salidas', 'x_cliente', 'cliente', '`cliente`', '`cliente`', 3, 11, -1, false, '`cliente`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->cliente->Required = true; // Required field
        $this->cliente->Sortable = true; // Allow sort
        $this->cliente->Lookup = new Lookup('cliente', 'cliente', false, 'id', ["ci_rif","nombre","codigo","web"], [], [], [], [], [], [], '', '');
        $this->cliente->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->cliente->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->cliente->Param, "CustomMsg");
        $this->Fields['cliente'] = &$this->cliente;

        // documento
        $this->documento = new DbField('salidas', 'salidas', 'x_documento', 'documento', '`documento`', '`documento`', 200, 2, -1, false, '`documento`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->documento->Required = true; // Required field
        $this->documento->Sortable = true; // Allow sort
        $this->documento->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->documento->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->documento->Lookup = new Lookup('documento', 'salidas', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->documento->OptionCount = 3;
        $this->documento->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->documento->Param, "CustomMsg");
        $this->Fields['documento'] = &$this->documento;

        // doc_afectado
        $this->doc_afectado = new DbField('salidas', 'salidas', 'x_doc_afectado', 'doc_afectado', '`doc_afectado`', '`doc_afectado`', 200, 20, -1, false, '`doc_afectado`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->doc_afectado->Sortable = true; // Allow sort
        $this->doc_afectado->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->doc_afectado->Param, "CustomMsg");
        $this->Fields['doc_afectado'] = &$this->doc_afectado;

        // moneda
        $this->moneda = new DbField('salidas', 'salidas', 'x_moneda', 'moneda', '`moneda`', '`moneda`', 200, 6, -1, false, '`moneda`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->moneda->Required = true; // Required field
        $this->moneda->Sortable = true; // Allow sort
        $this->moneda->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->moneda->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->moneda->Lookup = new Lookup('moneda', 'parametro', false, 'valor1', ["valor1","","",""], [], [], [], [], [], [], '`valor2` DESC', '');
        $this->moneda->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->moneda->Param, "CustomMsg");
        $this->Fields['moneda'] = &$this->moneda;

        // monto_total
        $this->monto_total = new DbField('salidas', 'salidas', 'x_monto_total', 'monto_total', '`monto_total`', '`monto_total`', 131, 14, -1, false, '`monto_total`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->monto_total->Sortable = true; // Allow sort
        $this->monto_total->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->monto_total->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_total->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->monto_total->Param, "CustomMsg");
        $this->Fields['monto_total'] = &$this->monto_total;

        // alicuota_iva
        $this->alicuota_iva = new DbField('salidas', 'salidas', 'x_alicuota_iva', 'alicuota_iva', '`alicuota_iva`', '`alicuota_iva`', 131, 14, -1, false, '`alicuota_iva`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->alicuota_iva->Sortable = true; // Allow sort
        $this->alicuota_iva->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->alicuota_iva->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->alicuota_iva->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->alicuota_iva->Param, "CustomMsg");
        $this->Fields['alicuota_iva'] = &$this->alicuota_iva;

        // iva
        $this->iva = new DbField('salidas', 'salidas', 'x_iva', 'iva', '`iva`', '`iva`', 131, 14, -1, false, '`iva`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->iva->Sortable = true; // Allow sort
        $this->iva->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->iva->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->iva->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->iva->Param, "CustomMsg");
        $this->Fields['iva'] = &$this->iva;

        // total
        $this->total = new DbField('salidas', 'salidas', 'x_total', 'total', '`total`', '`total`', 131, 14, -1, false, '`total`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->total->Sortable = true; // Allow sort
        $this->total->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->total->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->total->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->total->Param, "CustomMsg");
        $this->Fields['total'] = &$this->total;

        // tasa_dia
        $this->tasa_dia = new DbField('salidas', 'salidas', 'x_tasa_dia', 'tasa_dia', '`tasa_dia`', '`tasa_dia`', 131, 14, -1, false, '`tasa_dia`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->tasa_dia->Sortable = true; // Allow sort
        $this->tasa_dia->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->tasa_dia->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->tasa_dia->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tasa_dia->Param, "CustomMsg");
        $this->Fields['tasa_dia'] = &$this->tasa_dia;

        // monto_usd
        $this->monto_usd = new DbField('salidas', 'salidas', 'x_monto_usd', 'monto_usd', '`monto_usd`', '`monto_usd`', 131, 14, -1, false, '`monto_usd`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->monto_usd->Sortable = true; // Allow sort
        $this->monto_usd->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->monto_usd->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_usd->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->monto_usd->Param, "CustomMsg");
        $this->Fields['monto_usd'] = &$this->monto_usd;

        // lista_pedido
        $this->lista_pedido = new DbField('salidas', 'salidas', 'x_lista_pedido', 'lista_pedido', '`lista_pedido`', '`lista_pedido`', 200, 6, -1, false, '`lista_pedido`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->lista_pedido->Required = true; // Required field
        $this->lista_pedido->Sortable = true; // Allow sort
        $this->lista_pedido->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->lista_pedido->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->lista_pedido->Lookup = new Lookup('lista_pedido', 'tabla', false, 'campo_codigo', ["campo_descripcion","","",""], [], [], [], [], [], [], '`campo_descripcion` ASC', '');
        $this->lista_pedido->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->lista_pedido->Param, "CustomMsg");
        $this->Fields['lista_pedido'] = &$this->lista_pedido;

        // nota
        $this->nota = new DbField('salidas', 'salidas', 'x_nota', 'nota', '`nota`', '`nota`', 201, 65535, -1, false, '`nota`', false, false, false, 'FORMATTED TEXT', 'TEXTAREA');
        $this->nota->Sortable = true; // Allow sort
        $this->nota->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->nota->Param, "CustomMsg");
        $this->Fields['nota'] = &$this->nota;

        // username
        $this->_username = new DbField('salidas', 'salidas', 'x__username', 'username', '`username`', '`username`', 200, 30, -1, false, '`username`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->_username->Sortable = true; // Allow sort
        $this->_username->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->_username->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->_username->Lookup = new Lookup('username', 'usuario', false, 'username', ["nombre","","",""], [], [], [], [], [], [], '', '');
        $this->_username->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->_username->Param, "CustomMsg");
        $this->Fields['username'] = &$this->_username;

        // estatus
        $this->estatus = new DbField('salidas', 'salidas', 'x_estatus', 'estatus', '`estatus`', '`estatus`', 200, 10, -1, false, '`estatus`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->estatus->Required = true; // Required field
        $this->estatus->Sortable = true; // Allow sort
        $this->estatus->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->estatus->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->estatus->Lookup = new Lookup('estatus', 'salidas', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->estatus->OptionCount = 3;
        $this->estatus->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->estatus->Param, "CustomMsg");
        $this->Fields['estatus'] = &$this->estatus;

        // id_documento_padre
        $this->id_documento_padre = new DbField('salidas', 'salidas', 'x_id_documento_padre', 'id_documento_padre', '`id_documento_padre`', '`id_documento_padre`', 19, 10, -1, false, '`id_documento_padre`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->id_documento_padre->Sortable = true; // Allow sort
        $this->id_documento_padre->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id_documento_padre->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->id_documento_padre->Param, "CustomMsg");
        $this->Fields['id_documento_padre'] = &$this->id_documento_padre;

        // asesor
        $this->asesor = new DbField('salidas', 'salidas', 'x_asesor', 'asesor', '`asesor`', '`asesor`', 200, 30, -1, false, '`asesor`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->asesor->Required = true; // Required field
        $this->asesor->Sortable = true; // Allow sort
        $this->asesor->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->asesor->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->asesor->Lookup = new Lookup('asesor', 'asesor', false, 'ci_rif', ["nombre","","",""], [], [], [], [], [], [], '`nombre`', '');
        $this->asesor->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->asesor->Param, "CustomMsg");
        $this->Fields['asesor'] = &$this->asesor;

        // dias_credito
        $this->dias_credito = new DbField('salidas', 'salidas', 'x_dias_credito', 'dias_credito', '`dias_credito`', '`dias_credito`', 16, 4, -1, false, '`dias_credito`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->dias_credito->Sortable = true; // Allow sort
        $this->dias_credito->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->dias_credito->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->dias_credito->Param, "CustomMsg");
        $this->Fields['dias_credito'] = &$this->dias_credito;

        // entregado
        $this->entregado = new DbField('salidas', 'salidas', 'x_entregado', 'entregado', '`entregado`', '`entregado`', 202, 1, -1, false, '`entregado`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->entregado->Required = true; // Required field
        $this->entregado->Sortable = true; // Allow sort
        $this->entregado->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->entregado->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->entregado->Lookup = new Lookup('entregado', 'salidas', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->entregado->OptionCount = 2;
        $this->entregado->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->entregado->Param, "CustomMsg");
        $this->Fields['entregado'] = &$this->entregado;

        // fecha_entrega
        $this->fecha_entrega = new DbField('salidas', 'salidas', 'x_fecha_entrega', 'fecha_entrega', '`fecha_entrega`', CastDateFieldForLike("`fecha_entrega`", 7, "DB"), 133, 10, 7, false, '`fecha_entrega`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->fecha_entrega->Sortable = true; // Allow sort
        $this->fecha_entrega->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_SEPARATOR"], $Language->phrase("IncorrectDateDMY"));
        $this->fecha_entrega->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->fecha_entrega->Param, "CustomMsg");
        $this->Fields['fecha_entrega'] = &$this->fecha_entrega;

        // pagado
        $this->pagado = new DbField('salidas', 'salidas', 'x_pagado', 'pagado', '`pagado`', '`pagado`', 202, 1, -1, false, '`pagado`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->pagado->Required = true; // Required field
        $this->pagado->Sortable = false; // Allow sort
        $this->pagado->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->pagado->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->pagado->Lookup = new Lookup('pagado', 'salidas', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->pagado->OptionCount = 2;
        $this->pagado->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->pagado->Param, "CustomMsg");
        $this->Fields['pagado'] = &$this->pagado;

        // bultos
        $this->bultos = new DbField('salidas', 'salidas', 'x_bultos', 'bultos', '`bultos`', '`bultos`', 16, 4, -1, false, '`bultos`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->bultos->Sortable = true; // Allow sort
        $this->bultos->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->bultos->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->bultos->Param, "CustomMsg");
        $this->Fields['bultos'] = &$this->bultos;

        // fecha_bultos
        $this->fecha_bultos = new DbField('salidas', 'salidas', 'x_fecha_bultos', 'fecha_bultos', '`fecha_bultos`', CastDateFieldForLike("`fecha_bultos`", 7, "DB"), 135, 19, 7, false, '`fecha_bultos`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->fecha_bultos->Sortable = true; // Allow sort
        $this->fecha_bultos->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_SEPARATOR"], $Language->phrase("IncorrectDateDMY"));
        $this->fecha_bultos->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->fecha_bultos->Param, "CustomMsg");
        $this->Fields['fecha_bultos'] = &$this->fecha_bultos;

        // user_bultos
        $this->user_bultos = new DbField('salidas', 'salidas', 'x_user_bultos', 'user_bultos', '`user_bultos`', '`user_bultos`', 200, 30, -1, false, '`user_bultos`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->user_bultos->Sortable = true; // Allow sort
        $this->user_bultos->Lookup = new Lookup('user_bultos', 'usuario', false, 'username', ["nombre","","",""], [], [], [], [], [], [], '', '');
        $this->user_bultos->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->user_bultos->Param, "CustomMsg");
        $this->Fields['user_bultos'] = &$this->user_bultos;

        // fecha_despacho
        $this->fecha_despacho = new DbField('salidas', 'salidas', 'x_fecha_despacho', 'fecha_despacho', '`fecha_despacho`', CastDateFieldForLike("`fecha_despacho`", 7, "DB"), 135, 19, 7, false, '`fecha_despacho`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->fecha_despacho->Sortable = true; // Allow sort
        $this->fecha_despacho->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_SEPARATOR"], $Language->phrase("IncorrectDateDMY"));
        $this->fecha_despacho->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->fecha_despacho->Param, "CustomMsg");
        $this->Fields['fecha_despacho'] = &$this->fecha_despacho;

        // user_despacho
        $this->user_despacho = new DbField('salidas', 'salidas', 'x_user_despacho', 'user_despacho', '`user_despacho`', '`user_despacho`', 200, 30, -1, false, '`user_despacho`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->user_despacho->Sortable = true; // Allow sort
        $this->user_despacho->Lookup = new Lookup('user_despacho', 'usuario', false, 'username', ["nombre","","",""], [], [], [], [], [], [], '', '');
        $this->user_despacho->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->user_despacho->Param, "CustomMsg");
        $this->Fields['user_despacho'] = &$this->user_despacho;

        // consignacion
        $this->consignacion = new DbField('salidas', 'salidas', 'x_consignacion', 'consignacion', '`consignacion`', '`consignacion`', 202, 1, -1, false, '`consignacion`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->consignacion->Sortable = true; // Allow sort
        $this->consignacion->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->consignacion->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->consignacion->Lookup = new Lookup('consignacion', 'salidas', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->consignacion->OptionCount = 2;
        $this->consignacion->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->consignacion->Param, "CustomMsg");
        $this->Fields['consignacion'] = &$this->consignacion;

        // unidades
        $this->unidades = new DbField('salidas', 'salidas', 'x_unidades', 'unidades', '`unidades`', '`unidades`', 3, 11, -1, false, '`unidades`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->unidades->Sortable = true; // Allow sort
        $this->unidades->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->unidades->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->unidades->Param, "CustomMsg");
        $this->Fields['unidades'] = &$this->unidades;

        // descuento
        $this->descuento = new DbField('salidas', 'salidas', 'x_descuento', 'descuento', '`descuento`', '`descuento`', 131, 6, -1, false, '`descuento`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->descuento->Sortable = true; // Allow sort
        $this->descuento->Lookup = new Lookup('descuento', 'parametro', false, 'valor1', ["valor1","","",""], [], [], [], [], [], [], '`id` ASC', '');
        $this->descuento->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->descuento->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->descuento->Param, "CustomMsg");
        $this->descuento->AdvancedSearch->SearchValueDefault = 0;
        $this->descuento->AdvancedSearch->SearchOperatorDefault = "=";
        $this->descuento->AdvancedSearch->SearchOperatorDefault2 = "";
        $this->descuento->AdvancedSearch->SearchConditionDefault = "AND";
        $this->Fields['descuento'] = &$this->descuento;

        // monto_sin_descuento
        $this->monto_sin_descuento = new DbField('salidas', 'salidas', 'x_monto_sin_descuento', 'monto_sin_descuento', '`monto_sin_descuento`', '`monto_sin_descuento`', 131, 14, -1, false, '`monto_sin_descuento`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->monto_sin_descuento->Sortable = true; // Allow sort
        $this->monto_sin_descuento->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_sin_descuento->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->monto_sin_descuento->Param, "CustomMsg");
        $this->Fields['monto_sin_descuento'] = &$this->monto_sin_descuento;

        // factura
        $this->factura = new DbField('salidas', 'salidas', 'x_factura', 'factura', '`factura`', '`factura`', 202, 1, -1, false, '`factura`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->factura->Required = true; // Required field
        $this->factura->Sortable = true; // Allow sort
        $this->factura->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->factura->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->factura->Lookup = new Lookup('factura', 'salidas', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->factura->OptionCount = 2;
        $this->factura->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->factura->Param, "CustomMsg");
        $this->Fields['factura'] = &$this->factura;

        // ci_rif
        $this->ci_rif = new DbField('salidas', 'salidas', 'x_ci_rif', 'ci_rif', '`ci_rif`', '`ci_rif`', 200, 30, -1, false, '`ci_rif`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->ci_rif->Sortable = true; // Allow sort
        $this->ci_rif->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->ci_rif->Param, "CustomMsg");
        $this->Fields['ci_rif'] = &$this->ci_rif;

        // nombre
        $this->nombre = new DbField('salidas', 'salidas', 'x_nombre', 'nombre', '`nombre`', '`nombre`', 200, 80, -1, false, '`nombre`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->nombre->Sortable = true; // Allow sort
        $this->nombre->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->nombre->Param, "CustomMsg");
        $this->Fields['nombre'] = &$this->nombre;

        // direccion
        $this->direccion = new DbField('salidas', 'salidas', 'x_direccion', 'direccion', '`direccion`', '`direccion`', 200, 150, -1, false, '`direccion`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->direccion->Sortable = true; // Allow sort
        $this->direccion->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->direccion->Param, "CustomMsg");
        $this->Fields['direccion'] = &$this->direccion;

        // telefono
        $this->telefono = new DbField('salidas', 'salidas', 'x_telefono', 'telefono', '`telefono`', '`telefono`', 200, 20, -1, false, '`telefono`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->telefono->Sortable = true; // Allow sort
        $this->telefono->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->telefono->Param, "CustomMsg");
        $this->Fields['telefono'] = &$this->telefono;

        // email
        $this->_email = new DbField('salidas', 'salidas', 'x__email', 'email', '`email`', '`email`', 200, 100, -1, false, '`email`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->_email->Sortable = true; // Allow sort
        $this->_email->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->_email->Param, "CustomMsg");
        $this->Fields['email'] = &$this->_email;

        // activo
        $this->activo = new DbField('salidas', 'salidas', 'x_activo', 'activo', '`activo`', '`activo`', 202, 1, -1, false, '`activo`', false, false, false, 'FORMATTED TEXT', 'RADIO');
        $this->activo->Sortable = true; // Allow sort
        $this->activo->Lookup = new Lookup('activo', 'salidas', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->activo->OptionCount = 2;
        $this->activo->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->activo->Param, "CustomMsg");
        $this->Fields['activo'] = &$this->activo;

        // comprobante
        $this->comprobante = new DbField('salidas', 'salidas', 'x_comprobante', 'comprobante', '`comprobante`', '`comprobante`', 19, 11, -1, false, '`comprobante`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->comprobante->Sortable = true; // Allow sort
        $this->comprobante->Lookup = new Lookup('comprobante', 'cont_comprobante', false, 'id', ["id","descripcion","",""], [], [], [], [], [], [], '', '');
        $this->comprobante->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->comprobante->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->comprobante->Param, "CustomMsg");
        $this->Fields['comprobante'] = &$this->comprobante;

        // nro_despacho
        $this->nro_despacho = new DbField('salidas', 'salidas', 'x_nro_despacho', 'nro_despacho', '`nro_despacho`', '`nro_despacho`', 200, 20, -1, false, '`nro_despacho`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->nro_despacho->Sortable = true; // Allow sort
        $this->nro_despacho->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->nro_despacho->Param, "CustomMsg");
        $this->Fields['nro_despacho'] = &$this->nro_despacho;

        // cerrado
        $this->cerrado = new DbField('salidas', 'salidas', 'x_cerrado', 'cerrado', '`cerrado`', '`cerrado`', 202, 1, -1, false, '`cerrado`', false, false, false, 'FORMATTED TEXT', 'RADIO');
        $this->cerrado->Sortable = true; // Allow sort
        $this->cerrado->Lookup = new Lookup('cerrado', 'salidas', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->cerrado->OptionCount = 2;
        $this->cerrado->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->cerrado->Param, "CustomMsg");
        $this->Fields['cerrado'] = &$this->cerrado;

        // impreso
        $this->impreso = new DbField('salidas', 'salidas', 'x_impreso', 'impreso', '`impreso`', '`impreso`', 202, 1, -1, false, '`impreso`', false, false, false, 'FORMATTED TEXT', 'RADIO');
        $this->impreso->Sortable = true; // Allow sort
        $this->impreso->Lookup = new Lookup('impreso', 'salidas', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->impreso->OptionCount = 2;
        $this->impreso->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->impreso->Param, "CustomMsg");
        $this->Fields['impreso'] = &$this->impreso;

        // igtf
        $this->igtf = new DbField('salidas', 'salidas', 'x_igtf', 'igtf', '`igtf`', '`igtf`', 202, 1, -1, false, '`igtf`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->igtf->Required = true; // Required field
        $this->igtf->Sortable = true; // Allow sort
        $this->igtf->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->igtf->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->igtf->Lookup = new Lookup('igtf', 'salidas', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->igtf->OptionCount = 2;
        $this->igtf->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->igtf->Param, "CustomMsg");
        $this->Fields['igtf'] = &$this->igtf;

        // monto_base_igtf
        $this->monto_base_igtf = new DbField('salidas', 'salidas', 'x_monto_base_igtf', 'monto_base_igtf', '`monto_base_igtf`', '`monto_base_igtf`', 131, 14, -1, false, '`monto_base_igtf`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->monto_base_igtf->Sortable = true; // Allow sort
        $this->monto_base_igtf->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->monto_base_igtf->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_base_igtf->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->monto_base_igtf->Param, "CustomMsg");
        $this->Fields['monto_base_igtf'] = &$this->monto_base_igtf;

        // monto_igtf
        $this->monto_igtf = new DbField('salidas', 'salidas', 'x_monto_igtf', 'monto_igtf', '`monto_igtf`', '`monto_igtf`', 131, 14, -1, false, '`monto_igtf`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->monto_igtf->Sortable = true; // Allow sort
        $this->monto_igtf->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->monto_igtf->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_igtf->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->monto_igtf->Param, "CustomMsg");
        $this->Fields['monto_igtf'] = &$this->monto_igtf;
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
        if ($this->getCurrentDetailTable() == "pagos") {
            $detailUrl = Container("pagos")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue);
            $detailUrl .= "&" . GetForeignKeyUrl("fk_tipo_documento", $this->tipo_documento->CurrentValue);
        }
        if ($detailUrl == "") {
            $detailUrl = "SalidasList";
        }
        return $detailUrl;
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`salidas`";
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
        $this->cliente->DbValue = $row['cliente'];
        $this->documento->DbValue = $row['documento'];
        $this->doc_afectado->DbValue = $row['doc_afectado'];
        $this->moneda->DbValue = $row['moneda'];
        $this->monto_total->DbValue = $row['monto_total'];
        $this->alicuota_iva->DbValue = $row['alicuota_iva'];
        $this->iva->DbValue = $row['iva'];
        $this->total->DbValue = $row['total'];
        $this->tasa_dia->DbValue = $row['tasa_dia'];
        $this->monto_usd->DbValue = $row['monto_usd'];
        $this->lista_pedido->DbValue = $row['lista_pedido'];
        $this->nota->DbValue = $row['nota'];
        $this->_username->DbValue = $row['username'];
        $this->estatus->DbValue = $row['estatus'];
        $this->id_documento_padre->DbValue = $row['id_documento_padre'];
        $this->asesor->DbValue = $row['asesor'];
        $this->dias_credito->DbValue = $row['dias_credito'];
        $this->entregado->DbValue = $row['entregado'];
        $this->fecha_entrega->DbValue = $row['fecha_entrega'];
        $this->pagado->DbValue = $row['pagado'];
        $this->bultos->DbValue = $row['bultos'];
        $this->fecha_bultos->DbValue = $row['fecha_bultos'];
        $this->user_bultos->DbValue = $row['user_bultos'];
        $this->fecha_despacho->DbValue = $row['fecha_despacho'];
        $this->user_despacho->DbValue = $row['user_despacho'];
        $this->consignacion->DbValue = $row['consignacion'];
        $this->unidades->DbValue = $row['unidades'];
        $this->descuento->DbValue = $row['descuento'];
        $this->monto_sin_descuento->DbValue = $row['monto_sin_descuento'];
        $this->factura->DbValue = $row['factura'];
        $this->ci_rif->DbValue = $row['ci_rif'];
        $this->nombre->DbValue = $row['nombre'];
        $this->direccion->DbValue = $row['direccion'];
        $this->telefono->DbValue = $row['telefono'];
        $this->_email->DbValue = $row['email'];
        $this->activo->DbValue = $row['activo'];
        $this->comprobante->DbValue = $row['comprobante'];
        $this->nro_despacho->DbValue = $row['nro_despacho'];
        $this->cerrado->DbValue = $row['cerrado'];
        $this->impreso->DbValue = $row['impreso'];
        $this->igtf->DbValue = $row['igtf'];
        $this->monto_base_igtf->DbValue = $row['monto_base_igtf'];
        $this->monto_igtf->DbValue = $row['monto_igtf'];
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
        return $_SESSION[$name] ?? GetUrl("SalidasList");
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
        if ($pageName == "SalidasView") {
            return $Language->phrase("View");
        } elseif ($pageName == "SalidasEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "SalidasAdd") {
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
                return "SalidasView";
            case Config("API_ADD_ACTION"):
                return "SalidasAdd";
            case Config("API_EDIT_ACTION"):
                return "SalidasEdit";
            case Config("API_DELETE_ACTION"):
                return "SalidasDelete";
            case Config("API_LIST_ACTION"):
                return "SalidasList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "SalidasList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("SalidasView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("SalidasView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "SalidasAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "SalidasAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("SalidasEdit", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("SalidasEdit", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
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
            $url = $this->keyUrl("SalidasAdd", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("SalidasAdd", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
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
        return $this->keyUrl("SalidasDelete", $this->getUrlParm());
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
        $this->cliente->setDbValue($row['cliente']);
        $this->documento->setDbValue($row['documento']);
        $this->doc_afectado->setDbValue($row['doc_afectado']);
        $this->moneda->setDbValue($row['moneda']);
        $this->monto_total->setDbValue($row['monto_total']);
        $this->alicuota_iva->setDbValue($row['alicuota_iva']);
        $this->iva->setDbValue($row['iva']);
        $this->total->setDbValue($row['total']);
        $this->tasa_dia->setDbValue($row['tasa_dia']);
        $this->monto_usd->setDbValue($row['monto_usd']);
        $this->lista_pedido->setDbValue($row['lista_pedido']);
        $this->nota->setDbValue($row['nota']);
        $this->_username->setDbValue($row['username']);
        $this->estatus->setDbValue($row['estatus']);
        $this->id_documento_padre->setDbValue($row['id_documento_padre']);
        $this->asesor->setDbValue($row['asesor']);
        $this->dias_credito->setDbValue($row['dias_credito']);
        $this->entregado->setDbValue($row['entregado']);
        $this->fecha_entrega->setDbValue($row['fecha_entrega']);
        $this->pagado->setDbValue($row['pagado']);
        $this->bultos->setDbValue($row['bultos']);
        $this->fecha_bultos->setDbValue($row['fecha_bultos']);
        $this->user_bultos->setDbValue($row['user_bultos']);
        $this->fecha_despacho->setDbValue($row['fecha_despacho']);
        $this->user_despacho->setDbValue($row['user_despacho']);
        $this->consignacion->setDbValue($row['consignacion']);
        $this->unidades->setDbValue($row['unidades']);
        $this->descuento->setDbValue($row['descuento']);
        $this->monto_sin_descuento->setDbValue($row['monto_sin_descuento']);
        $this->factura->setDbValue($row['factura']);
        $this->ci_rif->setDbValue($row['ci_rif']);
        $this->nombre->setDbValue($row['nombre']);
        $this->direccion->setDbValue($row['direccion']);
        $this->telefono->setDbValue($row['telefono']);
        $this->_email->setDbValue($row['email']);
        $this->activo->setDbValue($row['activo']);
        $this->comprobante->setDbValue($row['comprobante']);
        $this->nro_despacho->setDbValue($row['nro_despacho']);
        $this->cerrado->setDbValue($row['cerrado']);
        $this->impreso->setDbValue($row['impreso']);
        $this->igtf->setDbValue($row['igtf']);
        $this->monto_base_igtf->setDbValue($row['monto_base_igtf']);
        $this->monto_igtf->setDbValue($row['monto_igtf']);
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

        // cliente

        // documento

        // doc_afectado

        // moneda

        // monto_total

        // alicuota_iva

        // iva

        // total

        // tasa_dia

        // monto_usd

        // lista_pedido

        // nota

        // username

        // estatus

        // id_documento_padre

        // asesor

        // dias_credito

        // entregado

        // fecha_entrega

        // pagado

        // bultos

        // fecha_bultos

        // user_bultos

        // fecha_despacho

        // user_despacho

        // consignacion

        // unidades

        // descuento

        // monto_sin_descuento

        // factura

        // ci_rif

        // nombre

        // direccion

        // telefono

        // email

        // activo

        // comprobante

        // nro_despacho

        // cerrado

        // impreso

        // igtf

        // monto_base_igtf

        // monto_igtf

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

        // cliente
        $this->cliente->ViewValue = $this->cliente->CurrentValue;
        $curVal = trim(strval($this->cliente->CurrentValue));
        if ($curVal != "") {
            $this->cliente->ViewValue = $this->cliente->lookupCacheOption($curVal);
            if ($this->cliente->ViewValue === null) { // Lookup from database
                $filterWrk = "`id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $lookupFilter = function() {
                    return FiltraClientes();
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->cliente->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
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

        // tasa_dia
        $this->tasa_dia->ViewValue = $this->tasa_dia->CurrentValue;
        $this->tasa_dia->ViewValue = FormatNumber($this->tasa_dia->ViewValue, 2, -1, -1, -1);
        $this->tasa_dia->ViewCustomAttributes = "";

        // monto_usd
        $this->monto_usd->ViewValue = $this->monto_usd->CurrentValue;
        $this->monto_usd->ViewValue = FormatNumber($this->monto_usd->ViewValue, 2, -1, -1, -1);
        $this->monto_usd->ViewCustomAttributes = "";

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

        // nota
        $this->nota->ViewValue = $this->nota->CurrentValue;
        $this->nota->ViewCustomAttributes = "";

        // username
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

        // asesor
        $curVal = trim(strval($this->asesor->CurrentValue));
        if ($curVal != "") {
            $this->asesor->ViewValue = $this->asesor->lookupCacheOption($curVal);
            if ($this->asesor->ViewValue === null) { // Lookup from database
                $filterWrk = "`ci_rif`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                $lookupFilter = function() {
                    return ($this->PageID == "add" OR $this->PageID == "edit") ? "activo = 'S'" : "";
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->asesor->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
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
        $this->asesor->ViewCustomAttributes = "";

        // dias_credito
        $this->dias_credito->ViewValue = $this->dias_credito->CurrentValue;
        $this->dias_credito->ViewCustomAttributes = "";

        // entregado
        if (strval($this->entregado->CurrentValue) != "") {
            $this->entregado->ViewValue = $this->entregado->optionCaption($this->entregado->CurrentValue);
        } else {
            $this->entregado->ViewValue = null;
        }
        $this->entregado->CssClass = "font-weight-bold font-italic";
        $this->entregado->ViewCustomAttributes = "";

        // fecha_entrega
        $this->fecha_entrega->ViewValue = $this->fecha_entrega->CurrentValue;
        $this->fecha_entrega->ViewValue = FormatDateTime($this->fecha_entrega->ViewValue, 7);
        $this->fecha_entrega->ViewCustomAttributes = "";

        // pagado
        if (strval($this->pagado->CurrentValue) != "") {
            $this->pagado->ViewValue = $this->pagado->optionCaption($this->pagado->CurrentValue);
        } else {
            $this->pagado->ViewValue = null;
        }
        $this->pagado->CssClass = "font-weight-bold font-italic";
        $this->pagado->ViewCustomAttributes = "";

        // bultos
        $this->bultos->ViewValue = $this->bultos->CurrentValue;
        $this->bultos->ViewCustomAttributes = "";

        // fecha_bultos
        $this->fecha_bultos->ViewValue = $this->fecha_bultos->CurrentValue;
        $this->fecha_bultos->ViewValue = FormatDateTime($this->fecha_bultos->ViewValue, 7);
        $this->fecha_bultos->ViewCustomAttributes = "";

        // user_bultos
        $this->user_bultos->ViewValue = $this->user_bultos->CurrentValue;
        $curVal = trim(strval($this->user_bultos->CurrentValue));
        if ($curVal != "") {
            $this->user_bultos->ViewValue = $this->user_bultos->lookupCacheOption($curVal);
            if ($this->user_bultos->ViewValue === null) { // Lookup from database
                $filterWrk = "`username`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                $sqlWrk = $this->user_bultos->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->user_bultos->Lookup->renderViewRow($rswrk[0]);
                    $this->user_bultos->ViewValue = $this->user_bultos->displayValue($arwrk);
                } else {
                    $this->user_bultos->ViewValue = $this->user_bultos->CurrentValue;
                }
            }
        } else {
            $this->user_bultos->ViewValue = null;
        }
        $this->user_bultos->ViewCustomAttributes = "";

        // fecha_despacho
        $this->fecha_despacho->ViewValue = $this->fecha_despacho->CurrentValue;
        $this->fecha_despacho->ViewValue = FormatDateTime($this->fecha_despacho->ViewValue, 7);
        $this->fecha_despacho->ViewCustomAttributes = "";

        // user_despacho
        $this->user_despacho->ViewValue = $this->user_despacho->CurrentValue;
        $curVal = trim(strval($this->user_despacho->CurrentValue));
        if ($curVal != "") {
            $this->user_despacho->ViewValue = $this->user_despacho->lookupCacheOption($curVal);
            if ($this->user_despacho->ViewValue === null) { // Lookup from database
                $filterWrk = "`username`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                $sqlWrk = $this->user_despacho->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->user_despacho->Lookup->renderViewRow($rswrk[0]);
                    $this->user_despacho->ViewValue = $this->user_despacho->displayValue($arwrk);
                } else {
                    $this->user_despacho->ViewValue = $this->user_despacho->CurrentValue;
                }
            }
        } else {
            $this->user_despacho->ViewValue = null;
        }
        $this->user_despacho->ViewCustomAttributes = "";

        // consignacion
        if (strval($this->consignacion->CurrentValue) != "") {
            $this->consignacion->ViewValue = $this->consignacion->optionCaption($this->consignacion->CurrentValue);
        } else {
            $this->consignacion->ViewValue = null;
        }
        $this->consignacion->ViewCustomAttributes = "";

        // unidades
        $this->unidades->ViewValue = $this->unidades->CurrentValue;
        $this->unidades->ViewCustomAttributes = "";

        // descuento
        $this->descuento->ViewValue = $this->descuento->CurrentValue;
        $curVal = trim(strval($this->descuento->CurrentValue));
        if ($curVal != "") {
            $this->descuento->ViewValue = $this->descuento->lookupCacheOption($curVal);
            if ($this->descuento->ViewValue === null) { // Lookup from database
                $filterWrk = "`valor1`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                $lookupFilter = function() {
                    return "`codigo` = '047'";
                };
                $lookupFilter = $lookupFilter->bindTo($this);
                $sqlWrk = $this->descuento->Lookup->getSql(false, $filterWrk, $lookupFilter, $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->descuento->Lookup->renderViewRow($rswrk[0]);
                    $this->descuento->ViewValue = $this->descuento->displayValue($arwrk);
                } else {
                    $this->descuento->ViewValue = $this->descuento->CurrentValue;
                }
            }
        } else {
            $this->descuento->ViewValue = null;
        }
        $this->descuento->CssClass = "font-weight-bold";
        $this->descuento->ViewCustomAttributes = "";

        // monto_sin_descuento
        $this->monto_sin_descuento->ViewValue = $this->monto_sin_descuento->CurrentValue;
        $this->monto_sin_descuento->ViewValue = FormatNumber($this->monto_sin_descuento->ViewValue, $this->monto_sin_descuento->DefaultDecimalPrecision);
        $this->monto_sin_descuento->ViewCustomAttributes = "";

        // factura
        if (strval($this->factura->CurrentValue) != "") {
            $this->factura->ViewValue = $this->factura->optionCaption($this->factura->CurrentValue);
        } else {
            $this->factura->ViewValue = null;
        }
        $this->factura->ViewCustomAttributes = "";

        // ci_rif
        $this->ci_rif->ViewValue = $this->ci_rif->CurrentValue;
        $this->ci_rif->ViewCustomAttributes = "";

        // nombre
        $this->nombre->ViewValue = $this->nombre->CurrentValue;
        $this->nombre->ViewCustomAttributes = "";

        // direccion
        $this->direccion->ViewValue = $this->direccion->CurrentValue;
        $this->direccion->ViewCustomAttributes = "";

        // telefono
        $this->telefono->ViewValue = $this->telefono->CurrentValue;
        $this->telefono->ViewCustomAttributes = "";

        // email
        $this->_email->ViewValue = $this->_email->CurrentValue;
        $this->_email->ViewCustomAttributes = "";

        // activo
        if (strval($this->activo->CurrentValue) != "") {
            $this->activo->ViewValue = $this->activo->optionCaption($this->activo->CurrentValue);
        } else {
            $this->activo->ViewValue = null;
        }
        $this->activo->ViewCustomAttributes = "";

        // comprobante
        $this->comprobante->ViewValue = $this->comprobante->CurrentValue;
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

        // nro_despacho
        $this->nro_despacho->ViewValue = $this->nro_despacho->CurrentValue;
        $this->nro_despacho->ViewCustomAttributes = "";

        // cerrado
        if (strval($this->cerrado->CurrentValue) != "") {
            $this->cerrado->ViewValue = $this->cerrado->optionCaption($this->cerrado->CurrentValue);
        } else {
            $this->cerrado->ViewValue = null;
        }
        $this->cerrado->ViewCustomAttributes = "";

        // impreso
        if (strval($this->impreso->CurrentValue) != "") {
            $this->impreso->ViewValue = $this->impreso->optionCaption($this->impreso->CurrentValue);
        } else {
            $this->impreso->ViewValue = null;
        }
        $this->impreso->ViewCustomAttributes = "";

        // igtf
        if (strval($this->igtf->CurrentValue) != "") {
            $this->igtf->ViewValue = $this->igtf->optionCaption($this->igtf->CurrentValue);
        } else {
            $this->igtf->ViewValue = null;
        }
        $this->igtf->ViewCustomAttributes = "";

        // monto_base_igtf
        $this->monto_base_igtf->ViewValue = $this->monto_base_igtf->CurrentValue;
        $this->monto_base_igtf->ViewValue = FormatNumber($this->monto_base_igtf->ViewValue, 2, -2, -2, -2);
        $this->monto_base_igtf->ViewCustomAttributes = "";

        // monto_igtf
        $this->monto_igtf->ViewValue = $this->monto_igtf->CurrentValue;
        $this->monto_igtf->ViewValue = FormatNumber($this->monto_igtf->ViewValue, 2, -2, -2, -2);
        $this->monto_igtf->ViewCustomAttributes = "";

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

        // cliente
        $this->cliente->LinkCustomAttributes = "";
        $this->cliente->HrefValue = "";
        $this->cliente->TooltipValue = "";

        // documento
        $this->documento->LinkCustomAttributes = "";
        $this->documento->HrefValue = "";
        $this->documento->TooltipValue = "";

        // doc_afectado
        $this->doc_afectado->LinkCustomAttributes = "";
        $this->doc_afectado->HrefValue = "";
        $this->doc_afectado->TooltipValue = "";

        // moneda
        $this->moneda->LinkCustomAttributes = "";
        $this->moneda->HrefValue = "";
        $this->moneda->TooltipValue = "";

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

        // tasa_dia
        $this->tasa_dia->LinkCustomAttributes = "";
        $this->tasa_dia->HrefValue = "";
        $this->tasa_dia->TooltipValue = "";

        // monto_usd
        $this->monto_usd->LinkCustomAttributes = "";
        $this->monto_usd->HrefValue = "";
        $this->monto_usd->TooltipValue = "";

        // lista_pedido
        $this->lista_pedido->LinkCustomAttributes = "";
        $this->lista_pedido->HrefValue = "";
        $this->lista_pedido->TooltipValue = "";

        // nota
        $this->nota->LinkCustomAttributes = "";
        $this->nota->HrefValue = "";
        $this->nota->TooltipValue = "";

        // username
        $this->_username->LinkCustomAttributes = "";
        $this->_username->HrefValue = "";
        $this->_username->TooltipValue = "";

        // estatus
        $this->estatus->LinkCustomAttributes = "";
        $this->estatus->HrefValue = "";
        $this->estatus->TooltipValue = "";

        // id_documento_padre
        $this->id_documento_padre->LinkCustomAttributes = "";
        $this->id_documento_padre->HrefValue = "";
        $this->id_documento_padre->TooltipValue = "";

        // asesor
        $this->asesor->LinkCustomAttributes = "";
        $this->asesor->HrefValue = "";
        $this->asesor->TooltipValue = "";

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

        // bultos
        $this->bultos->LinkCustomAttributes = "";
        $this->bultos->HrefValue = "";
        $this->bultos->TooltipValue = "";

        // fecha_bultos
        $this->fecha_bultos->LinkCustomAttributes = "";
        $this->fecha_bultos->HrefValue = "";
        $this->fecha_bultos->TooltipValue = "";

        // user_bultos
        $this->user_bultos->LinkCustomAttributes = "";
        $this->user_bultos->HrefValue = "";
        $this->user_bultos->TooltipValue = "";

        // fecha_despacho
        $this->fecha_despacho->LinkCustomAttributes = "";
        $this->fecha_despacho->HrefValue = "";
        $this->fecha_despacho->TooltipValue = "";

        // user_despacho
        $this->user_despacho->LinkCustomAttributes = "";
        $this->user_despacho->HrefValue = "";
        $this->user_despacho->TooltipValue = "";

        // consignacion
        $this->consignacion->LinkCustomAttributes = "";
        $this->consignacion->HrefValue = "";
        $this->consignacion->TooltipValue = "";

        // unidades
        $this->unidades->LinkCustomAttributes = "";
        $this->unidades->HrefValue = "";
        $this->unidades->TooltipValue = "";

        // descuento
        $this->descuento->LinkCustomAttributes = "";
        $this->descuento->HrefValue = "";
        $this->descuento->TooltipValue = "";

        // monto_sin_descuento
        $this->monto_sin_descuento->LinkCustomAttributes = "";
        $this->monto_sin_descuento->HrefValue = "";
        $this->monto_sin_descuento->TooltipValue = "";

        // factura
        $this->factura->LinkCustomAttributes = "";
        $this->factura->HrefValue = "";
        $this->factura->TooltipValue = "";

        // ci_rif
        $this->ci_rif->LinkCustomAttributes = "";
        $this->ci_rif->HrefValue = "";
        $this->ci_rif->TooltipValue = "";

        // nombre
        $this->nombre->LinkCustomAttributes = "";
        $this->nombre->HrefValue = "";
        $this->nombre->TooltipValue = "";

        // direccion
        $this->direccion->LinkCustomAttributes = "";
        $this->direccion->HrefValue = "";
        $this->direccion->TooltipValue = "";

        // telefono
        $this->telefono->LinkCustomAttributes = "";
        $this->telefono->HrefValue = "";
        $this->telefono->TooltipValue = "";

        // email
        $this->_email->LinkCustomAttributes = "";
        $this->_email->HrefValue = "";
        $this->_email->TooltipValue = "";

        // activo
        $this->activo->LinkCustomAttributes = "";
        $this->activo->HrefValue = "";
        $this->activo->TooltipValue = "";

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

        // nro_despacho
        $this->nro_despacho->LinkCustomAttributes = "";
        $this->nro_despacho->HrefValue = "";
        $this->nro_despacho->TooltipValue = "";

        // cerrado
        $this->cerrado->LinkCustomAttributes = "";
        $this->cerrado->HrefValue = "";
        $this->cerrado->TooltipValue = "";

        // impreso
        $this->impreso->LinkCustomAttributes = "";
        $this->impreso->HrefValue = "";
        $this->impreso->TooltipValue = "";

        // igtf
        $this->igtf->LinkCustomAttributes = "";
        $this->igtf->HrefValue = "";
        $this->igtf->TooltipValue = "";

        // monto_base_igtf
        $this->monto_base_igtf->LinkCustomAttributes = "";
        $this->monto_base_igtf->HrefValue = "";
        $this->monto_base_igtf->TooltipValue = "";

        // monto_igtf
        $this->monto_igtf->LinkCustomAttributes = "";
        $this->monto_igtf->HrefValue = "";
        $this->monto_igtf->TooltipValue = "";

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

        // cliente
        $this->cliente->EditAttrs["class"] = "form-control";
        $this->cliente->EditCustomAttributes = "";
        $this->cliente->EditValue = $this->cliente->CurrentValue;
        $this->cliente->PlaceHolder = RemoveHtml($this->cliente->caption());

        // documento
        $this->documento->EditAttrs["class"] = "form-control";
        $this->documento->EditCustomAttributes = "";
        if (strval($this->documento->CurrentValue) != "") {
            $this->documento->EditValue = $this->documento->optionCaption($this->documento->CurrentValue);
        } else {
            $this->documento->EditValue = null;
        }
        $this->documento->ViewCustomAttributes = "";

        // doc_afectado
        $this->doc_afectado->EditAttrs["class"] = "form-control";
        $this->doc_afectado->EditCustomAttributes = "";
        if (!$this->doc_afectado->Raw) {
            $this->doc_afectado->CurrentValue = HtmlDecode($this->doc_afectado->CurrentValue);
        }
        $this->doc_afectado->EditValue = $this->doc_afectado->CurrentValue;
        $this->doc_afectado->PlaceHolder = RemoveHtml($this->doc_afectado->caption());

        // moneda
        $this->moneda->EditAttrs["class"] = "form-control";
        $this->moneda->EditCustomAttributes = "";
        $this->moneda->PlaceHolder = RemoveHtml($this->moneda->caption());

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

        // tasa_dia
        $this->tasa_dia->EditAttrs["class"] = "form-control";
        $this->tasa_dia->EditCustomAttributes = "";
        $this->tasa_dia->EditValue = $this->tasa_dia->CurrentValue;
        $this->tasa_dia->EditValue = FormatNumber($this->tasa_dia->EditValue, 2, -1, -1, -1);
        $this->tasa_dia->ViewCustomAttributes = "";

        // monto_usd
        $this->monto_usd->EditAttrs["class"] = "form-control";
        $this->monto_usd->EditCustomAttributes = "";
        $this->monto_usd->EditValue = $this->monto_usd->CurrentValue;
        $this->monto_usd->PlaceHolder = RemoveHtml($this->monto_usd->caption());
        if (strval($this->monto_usd->EditValue) != "" && is_numeric($this->monto_usd->EditValue)) {
            $this->monto_usd->EditValue = FormatNumber($this->monto_usd->EditValue, -2, -1, -2, -1);
        }

        // lista_pedido
        $this->lista_pedido->EditAttrs["class"] = "form-control";
        $this->lista_pedido->EditCustomAttributes = "";
        $this->lista_pedido->PlaceHolder = RemoveHtml($this->lista_pedido->caption());

        // nota
        $this->nota->EditAttrs["class"] = "form-control";
        $this->nota->EditCustomAttributes = "";
        $this->nota->EditValue = $this->nota->CurrentValue;
        $this->nota->PlaceHolder = RemoveHtml($this->nota->caption());

        // username
        $this->_username->EditAttrs["class"] = "form-control";
        $this->_username->EditCustomAttributes = "";
        $this->_username->PlaceHolder = RemoveHtml($this->_username->caption());

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

        // asesor
        $this->asesor->EditAttrs["class"] = "form-control";
        $this->asesor->EditCustomAttributes = "";
        $this->asesor->PlaceHolder = RemoveHtml($this->asesor->caption());

        // dias_credito
        $this->dias_credito->EditAttrs["class"] = "form-control";
        $this->dias_credito->EditCustomAttributes = "";
        $this->dias_credito->EditValue = $this->dias_credito->CurrentValue;
        $this->dias_credito->PlaceHolder = RemoveHtml($this->dias_credito->caption());

        // entregado
        $this->entregado->EditAttrs["class"] = "form-control";
        $this->entregado->EditCustomAttributes = "";
        $this->entregado->EditValue = $this->entregado->options(true);
        $this->entregado->PlaceHolder = RemoveHtml($this->entregado->caption());

        // fecha_entrega
        $this->fecha_entrega->EditAttrs["class"] = "form-control";
        $this->fecha_entrega->EditCustomAttributes = "";
        $this->fecha_entrega->EditValue = FormatDateTime($this->fecha_entrega->CurrentValue, 7);
        $this->fecha_entrega->PlaceHolder = RemoveHtml($this->fecha_entrega->caption());

        // pagado
        $this->pagado->EditAttrs["class"] = "form-control";
        $this->pagado->EditCustomAttributes = "";
        $this->pagado->EditValue = $this->pagado->options(true);
        $this->pagado->PlaceHolder = RemoveHtml($this->pagado->caption());

        // bultos
        $this->bultos->EditAttrs["class"] = "form-control";
        $this->bultos->EditCustomAttributes = "";
        $this->bultos->EditValue = $this->bultos->CurrentValue;
        $this->bultos->PlaceHolder = RemoveHtml($this->bultos->caption());

        // fecha_bultos
        $this->fecha_bultos->EditAttrs["class"] = "form-control";
        $this->fecha_bultos->EditCustomAttributes = "";
        $this->fecha_bultos->EditValue = FormatDateTime($this->fecha_bultos->CurrentValue, 7);
        $this->fecha_bultos->PlaceHolder = RemoveHtml($this->fecha_bultos->caption());

        // user_bultos
        $this->user_bultos->EditAttrs["class"] = "form-control";
        $this->user_bultos->EditCustomAttributes = "";
        if (!$this->user_bultos->Raw) {
            $this->user_bultos->CurrentValue = HtmlDecode($this->user_bultos->CurrentValue);
        }
        $this->user_bultos->EditValue = $this->user_bultos->CurrentValue;
        $this->user_bultos->PlaceHolder = RemoveHtml($this->user_bultos->caption());

        // fecha_despacho
        $this->fecha_despacho->EditAttrs["class"] = "form-control";
        $this->fecha_despacho->EditCustomAttributes = "";
        $this->fecha_despacho->EditValue = FormatDateTime($this->fecha_despacho->CurrentValue, 7);
        $this->fecha_despacho->PlaceHolder = RemoveHtml($this->fecha_despacho->caption());

        // user_despacho
        $this->user_despacho->EditAttrs["class"] = "form-control";
        $this->user_despacho->EditCustomAttributes = "";
        if (!$this->user_despacho->Raw) {
            $this->user_despacho->CurrentValue = HtmlDecode($this->user_despacho->CurrentValue);
        }
        $this->user_despacho->EditValue = $this->user_despacho->CurrentValue;
        $this->user_despacho->PlaceHolder = RemoveHtml($this->user_despacho->caption());

        // consignacion
        $this->consignacion->EditAttrs["class"] = "form-control";
        $this->consignacion->EditCustomAttributes = "";
        $this->consignacion->EditValue = $this->consignacion->options(true);
        $this->consignacion->PlaceHolder = RemoveHtml($this->consignacion->caption());

        // unidades
        $this->unidades->EditAttrs["class"] = "form-control";
        $this->unidades->EditCustomAttributes = "";
        $this->unidades->EditValue = $this->unidades->CurrentValue;
        $this->unidades->PlaceHolder = RemoveHtml($this->unidades->caption());

        // descuento
        $this->descuento->EditAttrs["class"] = "form-control";
        $this->descuento->EditCustomAttributes = "";
        $this->descuento->EditValue = $this->descuento->CurrentValue;
        $this->descuento->PlaceHolder = RemoveHtml($this->descuento->caption());

        // monto_sin_descuento
        $this->monto_sin_descuento->EditAttrs["class"] = "form-control";
        $this->monto_sin_descuento->EditCustomAttributes = "";
        $this->monto_sin_descuento->EditValue = $this->monto_sin_descuento->CurrentValue;
        $this->monto_sin_descuento->PlaceHolder = RemoveHtml($this->monto_sin_descuento->caption());
        if (strval($this->monto_sin_descuento->EditValue) != "" && is_numeric($this->monto_sin_descuento->EditValue)) {
            $this->monto_sin_descuento->EditValue = FormatNumber($this->monto_sin_descuento->EditValue, -2, -1, -2, 0);
        }

        // factura
        $this->factura->EditAttrs["class"] = "form-control";
        $this->factura->EditCustomAttributes = "";
        $this->factura->EditValue = $this->factura->options(true);
        $this->factura->PlaceHolder = RemoveHtml($this->factura->caption());

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

        // direccion
        $this->direccion->EditAttrs["class"] = "form-control";
        $this->direccion->EditCustomAttributes = "";
        if (!$this->direccion->Raw) {
            $this->direccion->CurrentValue = HtmlDecode($this->direccion->CurrentValue);
        }
        $this->direccion->EditValue = $this->direccion->CurrentValue;
        $this->direccion->PlaceHolder = RemoveHtml($this->direccion->caption());

        // telefono
        $this->telefono->EditAttrs["class"] = "form-control";
        $this->telefono->EditCustomAttributes = "";
        if (!$this->telefono->Raw) {
            $this->telefono->CurrentValue = HtmlDecode($this->telefono->CurrentValue);
        }
        $this->telefono->EditValue = $this->telefono->CurrentValue;
        $this->telefono->PlaceHolder = RemoveHtml($this->telefono->caption());

        // email
        $this->_email->EditAttrs["class"] = "form-control";
        $this->_email->EditCustomAttributes = "";
        if (!$this->_email->Raw) {
            $this->_email->CurrentValue = HtmlDecode($this->_email->CurrentValue);
        }
        $this->_email->EditValue = $this->_email->CurrentValue;
        $this->_email->PlaceHolder = RemoveHtml($this->_email->caption());

        // activo
        $this->activo->EditCustomAttributes = "";
        $this->activo->EditValue = $this->activo->options(false);
        $this->activo->PlaceHolder = RemoveHtml($this->activo->caption());

        // comprobante
        $this->comprobante->EditAttrs["class"] = "form-control";
        $this->comprobante->EditCustomAttributes = "";
        $this->comprobante->EditValue = $this->comprobante->CurrentValue;
        $this->comprobante->PlaceHolder = RemoveHtml($this->comprobante->caption());

        // nro_despacho
        $this->nro_despacho->EditAttrs["class"] = "form-control";
        $this->nro_despacho->EditCustomAttributes = "";
        if (!$this->nro_despacho->Raw) {
            $this->nro_despacho->CurrentValue = HtmlDecode($this->nro_despacho->CurrentValue);
        }
        $this->nro_despacho->EditValue = $this->nro_despacho->CurrentValue;
        $this->nro_despacho->PlaceHolder = RemoveHtml($this->nro_despacho->caption());

        // cerrado
        $this->cerrado->EditCustomAttributes = "";
        $this->cerrado->EditValue = $this->cerrado->options(false);
        $this->cerrado->PlaceHolder = RemoveHtml($this->cerrado->caption());

        // impreso
        $this->impreso->EditAttrs["class"] = "form-control";
        $this->impreso->EditCustomAttributes = "";
        if (strval($this->impreso->CurrentValue) != "") {
            $this->impreso->EditValue = $this->impreso->optionCaption($this->impreso->CurrentValue);
        } else {
            $this->impreso->EditValue = null;
        }
        $this->impreso->ViewCustomAttributes = "";

        // igtf
        $this->igtf->EditAttrs["class"] = "form-control";
        $this->igtf->EditCustomAttributes = "";
        $this->igtf->EditValue = $this->igtf->options(true);
        $this->igtf->PlaceHolder = RemoveHtml($this->igtf->caption());

        // monto_base_igtf
        $this->monto_base_igtf->EditAttrs["class"] = "form-control";
        $this->monto_base_igtf->EditCustomAttributes = "";
        $this->monto_base_igtf->EditValue = $this->monto_base_igtf->CurrentValue;
        $this->monto_base_igtf->PlaceHolder = RemoveHtml($this->monto_base_igtf->caption());
        if (strval($this->monto_base_igtf->EditValue) != "" && is_numeric($this->monto_base_igtf->EditValue)) {
            $this->monto_base_igtf->EditValue = FormatNumber($this->monto_base_igtf->EditValue, -2, -2, -2, -2);
        }

        // monto_igtf
        $this->monto_igtf->EditAttrs["class"] = "form-control";
        $this->monto_igtf->EditCustomAttributes = "";
        $this->monto_igtf->EditValue = $this->monto_igtf->CurrentValue;
        $this->monto_igtf->PlaceHolder = RemoveHtml($this->monto_igtf->caption());
        if (strval($this->monto_igtf->EditValue) != "" && is_numeric($this->monto_igtf->EditValue)) {
            $this->monto_igtf->EditValue = FormatNumber($this->monto_igtf->EditValue, -2, -2, -2, -2);
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
                    $doc->exportCaption($this->cliente);
                    $doc->exportCaption($this->documento);
                    $doc->exportCaption($this->doc_afectado);
                    $doc->exportCaption($this->moneda);
                    $doc->exportCaption($this->monto_total);
                    $doc->exportCaption($this->alicuota_iva);
                    $doc->exportCaption($this->iva);
                    $doc->exportCaption($this->total);
                    $doc->exportCaption($this->tasa_dia);
                    $doc->exportCaption($this->monto_usd);
                    $doc->exportCaption($this->lista_pedido);
                    $doc->exportCaption($this->_username);
                    $doc->exportCaption($this->estatus);
                    $doc->exportCaption($this->asesor);
                    $doc->exportCaption($this->dias_credito);
                    $doc->exportCaption($this->entregado);
                    $doc->exportCaption($this->fecha_entrega);
                    $doc->exportCaption($this->pagado);
                    $doc->exportCaption($this->descuento);
                    $doc->exportCaption($this->nro_despacho);
                    $doc->exportCaption($this->impreso);
                    $doc->exportCaption($this->igtf);
                    $doc->exportCaption($this->monto_base_igtf);
                    $doc->exportCaption($this->monto_igtf);
                } else {
                    $doc->exportCaption($this->tipo_documento);
                    $doc->exportCaption($this->nro_documento);
                    $doc->exportCaption($this->nro_control);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->cliente);
                    $doc->exportCaption($this->documento);
                    $doc->exportCaption($this->doc_afectado);
                    $doc->exportCaption($this->moneda);
                    $doc->exportCaption($this->monto_total);
                    $doc->exportCaption($this->alicuota_iva);
                    $doc->exportCaption($this->iva);
                    $doc->exportCaption($this->total);
                    $doc->exportCaption($this->tasa_dia);
                    $doc->exportCaption($this->_username);
                    $doc->exportCaption($this->estatus);
                    $doc->exportCaption($this->unidades);
                    $doc->exportCaption($this->monto_base_igtf);
                    $doc->exportCaption($this->monto_igtf);
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
                        $doc->exportField($this->cliente);
                        $doc->exportField($this->documento);
                        $doc->exportField($this->doc_afectado);
                        $doc->exportField($this->moneda);
                        $doc->exportField($this->monto_total);
                        $doc->exportField($this->alicuota_iva);
                        $doc->exportField($this->iva);
                        $doc->exportField($this->total);
                        $doc->exportField($this->tasa_dia);
                        $doc->exportField($this->monto_usd);
                        $doc->exportField($this->lista_pedido);
                        $doc->exportField($this->_username);
                        $doc->exportField($this->estatus);
                        $doc->exportField($this->asesor);
                        $doc->exportField($this->dias_credito);
                        $doc->exportField($this->entregado);
                        $doc->exportField($this->fecha_entrega);
                        $doc->exportField($this->pagado);
                        $doc->exportField($this->descuento);
                        $doc->exportField($this->nro_despacho);
                        $doc->exportField($this->impreso);
                        $doc->exportField($this->igtf);
                        $doc->exportField($this->monto_base_igtf);
                        $doc->exportField($this->monto_igtf);
                    } else {
                        $doc->exportField($this->tipo_documento);
                        $doc->exportField($this->nro_documento);
                        $doc->exportField($this->nro_control);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->cliente);
                        $doc->exportField($this->documento);
                        $doc->exportField($this->doc_afectado);
                        $doc->exportField($this->moneda);
                        $doc->exportField($this->monto_total);
                        $doc->exportField($this->alicuota_iva);
                        $doc->exportField($this->iva);
                        $doc->exportField($this->total);
                        $doc->exportField($this->tasa_dia);
                        $doc->exportField($this->_username);
                        $doc->exportField($this->estatus);
                        $doc->exportField($this->unidades);
                        $doc->exportField($this->monto_base_igtf);
                        $doc->exportField($this->monto_igtf);
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
        $table = 'salidas';
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
        $table = 'salidas';

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
        $table = 'salidas';

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
        $table = 'salidas';

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
    	/* ****** */
    	AddFilter($filter, "tipo_documento = '$tipo'");
    	if(trim($tipo) == "") {
    		header("Location: Home");
    		die();
    	}

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
        $sql = "SELECT tasa FROM tasa_usd
        		WHERE moneda = '$moneda' ORDER BY id DESC LIMIT 0, 1;";
        $tasa = ExecuteScalar($sql);
        $rsnew["tasa_dia"] = $tasa;
    	$fecha = date("Y-m-d");
    	$sql = "SELECT fecha FROM cierre_de_caja WHERE fecha = '$fecha';";
    	if($row = ExecuteRow($sql)) {
    		$this->CancelMessage = "El d&iacute;a " . date("d/m/Y") . " est&aacute; cerrado; no se puede generar el documento. Verifique!";
    		return FALSE;
    	}
    	$tipo = $rsnew["tipo_documento"];

    	// Se obtiene el consecutivo del tipo de documento
    	$sql = "SELECT MAX(CAST(IFNULL(nro_documento, 0) AS UNSIGNED)) AS cosecutivo FROM salidas WHERE tipo_documento = '$tipo';";
    	$consecutivo = intval(ExecuteScalar($sql)) + 1;
    	switch($tipo) {
    	case "TDCPDV":
    		$rsnew["nro_documento"] = str_pad($consecutivo, 7, "0", STR_PAD_LEFT);
    		$sql = "SELECT consignacion FROM cliente WHERE id = " . intval($rsnew["cliente"]) . ";";
    		$rsnew["consignacion"] = ExecuteScalar($sql);
    		break;
    	case "TDCNET":
    		// Se agrega la siguiente línea el 26/12/2020 Junior Sanabria
    		$rsnew["nro_documento"] = str_pad($consecutivo, 7, "0", STR_PAD_LEFT);
    		break;
    	case "TDCFCV":
    		// Valido cierre de facturación
    		$sql = "SELECT DISTINCT cerrado AS cerrado FROM salidas WHERE YEAR(fecha) = " . intval(date("Y")) . " AND MONTH(fecha) = " . intval(date("m")) . ";";
    		if($row = ExecuteRow($sql)) {
    			if($row["cerrado"] == "S") {
    				$this->CancelMessage = "El mes en el que se va a crear la factura est&aacute; cerrado. Verifique!";
    				return FALSE;
    			}
    		}

    		// Tomo el número de días de crédito por defecto
    		$sql = "SELECT valor1, valor2, valor3 FROM parametro WHERE codigo = '007';";
    		$row = ExecuteRow($sql);
    		$nota = "Crédito a " . $row["valor1"] . " " . $row["valor2"];
    		$rsnew["nota"] = $nota;

    		/*
    		$sql = "SELECT valor1 FROM parametro WHERE codigo = '006';";
    		$moneda = ExecuteScalar($sql);
    		$rsnew["moneda"] = $moneda;
    		*/
    		break;
    	case "TDCASA":
    		$rsnew["nro_documento"] = str_pad($consecutivo, 7, "0", STR_PAD_LEFT);
    		break;
    	}

    	// Se establecen valores por defecto a varibles bitácora y estatus
    	$rsnew["fecha"] = date("Y-m-d H:i:s");
    	$rsnew["username"] = CurrentUserName();
    	// $rsnew["asesor"] = CurrentUserName();
    	$rsnew["estatus"] = "NUEVO";
    	$rsnew["fecha_bultos"] = NULL;
    	$rsnew["fecha_despacho"] = NULL;
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
    	case "TDCPDV":
    		break;
    	case "TDCNET":
    		$sql = "SELECT valor1 AS aplica FROM parametro WHERE codigo = '050';";
    		if($row = ExecuteRow($sql)) {
    			if($row["aplica"] == "S") {
    				Aplicar3x2($rsnew["id"], "TDCNET");
    			}
    		}
    		ActualizarTotalFacturaVenta($rsnew["id"], $rsnew["tipo_documento"]);
    		break;
    	case "TDCFCV":
    		switch($rsnew["documento"]) {
    		case "FC":
    			$codigo = "003";
    			break;
    		case "NC":
    			$codigo = "010";
    			break;
    		case "ND":
    			$codigo = "011";
    			break;
    		}
    		$sql = "SELECT valor1, valor2, valor3 FROM parametro WHERE codigo = '$codigo';";
    		$row = ExecuteRow($sql);
    		$numero = intval($row["valor1"]) + 1;
    		$prefijo = trim($row["valor2"]);
    		$padeo = intval($row["valor3"]);
    		$factura = $prefijo . str_pad($numero, $padeo, "0", STR_PAD_LEFT); 
    		$sql = "UPDATE parametro SET valor1='$numero' 
    			WHERE codigo = '$codigo';";
    		Execute($sql);

    		//// Nro Ctrol ////
    		// Tomo el siguiente número de control de factura
    		// Pregunto si el consecutivo del Nro de Control de factura es el mismo
    		// Para Notas de Débito y Nota de Crédito
    		$sql = "SELECT valor1 FROM parametro WHERE codigo = '035';";
    		if(ExecuteScalar($sql) == "S") {
    			$codigoCRTL = "030";
    		}
    		else {
    			switch($codigo) {
    			case "003":
    				$codigoCRTL = "030";
    				break;
    			case "010":
    				$codigoCRTL = "031";
    				break;
    			case "011":
    				$codigoCRTL = "032";
    				break;
    			}
    		}
    		$sql = "SELECT valor1, valor2, valor3 FROM parametro WHERE codigo = '$codigoCRTL';";
    		$row = ExecuteRow($sql);
    		$numero = intval($row["valor1"]) + 1;
    		$prefijo = trim($row["valor2"]);
    		$padeo = intval($row["valor3"]);
    		$facturaCTRL = $prefijo . str_pad($numero, $padeo, "0", STR_PAD_LEFT); 
    		$sql = "UPDATE parametro SET valor1='$numero' 
    				WHERE codigo = '$codigoCRTL';";
    				Execute($sql);
    		///////////////////
    		$sql = "UPDATE salidas SET nro_documento='$factura', nro_control = '$facturaCTRL'  
    				WHERE id = '" . $rsnew["id"] . "';";
    		Execute($sql);
    		break;
    	case "TDCASA":
    		break;
    	}
    }

    // Row Updating event
    public function rowUpdating($rsold, &$rsnew) {
    	// Enter your code here
    	// To cancel, set return value to FALSE
    	$rsnew["moneda"] = $rsold["moneda"];
    	if(CurrentUserLevel() != -1) {
    		if($rsold["estatus"] == "ANULADO") {
    			$this->CancelMessage = "Este documento est&aacute; procesado o anulado; no se puede modificar.";
    			return FALSE;
    		}
    		if($rsold["estatus"] == "PROCESADO" and $rsnew["estatus"] != "ANULADO") {
    			$this->CancelMessage = "Este documento no se puede modificar.";
    			return FALSE;
    		}
    	}
    	$fecha = date("Y-m-d");
    	$sql = "SELECT fecha FROM cierre_de_caja WHERE fecha = '$fecha';";
    	if($row = ExecuteRow($sql)) {
    		$this->CancelMessage = "El d&iacute;a " . date("d/m/Y") . " est&aacute; cerrado; no se puede modificar el documento. Verifique!";
    		return FALSE;
    	}
    	if($rsold["tipo_documento"] == "TDCFCC") {
    		// Valido cierre de facturación
    		if($rsold["cerrado"] == "S") {
    			$this->CancelMessage = "El mes en el que est&aacute; la factura est&aacute; cerrado. Verifique!";
    			return FALSE;
    		}
    		if($rsold["comprobante"] != "") {
    			$this->CancelMessage = "Este documento est&aacute; contabilizado; no se puede modificar.";
    			return FALSE;
    		}
    	}
    	elseif($rsold["tipo_documento"] == "TDCFCV") {
    		if($rsold["estatus"] != $rsnew["estatus"]) {
    			$sql = "SELECT id, id_documento FROM cobros_cliente WHERE id_documento = " . $rsold["id"] . ";";
    			if($row = ExecuteRow($sql)) $hay_pagos = true;
    			else $hay_pagos = false;
    			if($rsnew["estatus"] == "ANULADO" and $hay_pagos) {
    				$this->CancelMessage = "Para anular el documento debe eliminar los pagos asociados al mismo. Verifique Cobro Cliente # " . $row["id"] . "!";
    				return FALSE;
    			}
    			if($rsnew["estatus"] == "PROCESADO" and $hay_pagos == false) {
    				$this->CancelMessage = "No hay pasgos asociados; no se puede procesar el documento. Verifique!";
    				return FALSE;
    			}
    		}
    		if($rsnew["igtf"] == "S") {
    			if(floatval($rsnew["monto_base_igtf"]) > 0) {
    				$sql = "SELECT valor1 FROM parametro WHERE codigo = '037';";
    				$igtf = floatval(ExecuteScalar($sql));
    				// $rsnew["monto_igtf"] = $rsnew["monto_base_igtf"] + $rsnew["monto_base_igtf"]*(3/100);
    				$rsnew["monto_igtf"] = $rsnew["monto_base_igtf"]*($igtf/100);
    				if(floatval($rsnew["monto_base_igtf"]) > floatval($rsold["total"])) {
    					$this->CancelMessage = "El moto para aplicar IGTF debe ser menor o igual al monto total de la factura.";
    					return FALSE;
    				}
    			}
    			else {
    				$this->CancelMessage = "El moto para aplicar IGTF debe ser mayor a 0.";
    				return FALSE;
    			}
    		}
    		else {
    			$rsnew["monto_igtf"] = 0.00;
    			$rsnew["monto_base_igtf"] = 0.00; 
    		}
    	}
    	if($rsold["tipo_documento"] == "TDCNET") {
    		if($rsold["estatus"] != $rsnew["estatus"]) {
    			if($rsnew["estatus"] == "ANULADO") {
    				// Junior 14/07/2023
    				if(CurrentUserLevel() != -1) {
    					$fecha = date("Y-m-d");
        				$sql = "SELECT fecha FROM cierre_de_caja WHERE fecha = '$fecha';";
        				if($row = ExecuteRow($sql)) {
        					$this->CancelMessage = "El d&iacute;a " . date("d/m/Y") . " est&aacute; cerrado; no se puede anular el documento. Verifique!";
        					return FALSE;
        				}
    					if(substr($rsold["fecha"], 0, 10) != date("Y-m-d")) {
    						$this->CancelMessage = "Esta nota est&aacute; procesada y no es del d&iacute;a actual, no se puede anular.";
    						return false;
    					}
    				}
    				// 
    				if(strlen(trim($rsnew["nota"])) < 10) {
    					$this->CancelMessage = "Debe indicar por qu&eacute est&aacute; anulando esta Nota de Entrega. M&iacute;nimo colocar 10 caracteres en la nota.";
    					return false;
    				}
    				$cliente = $rsold["cliente"];
    				$nro_documento = $rsold["nro_documento"];
    				$tipo_documento = $rsold["tipo_documento"];
    				$sql = "SELECT id FROM cobros_cliente WHERE id_documento = " . $rsold["id"] . ";";
    				$rowsPagos = ExecuteRows($sql);
    				foreach ($rowsPagos as $keyPago => $rowPago) {
    					$idPago = $rowPago["id"];

    					/*** Reverso cobros en Bs ***/
    					$sql = "SELECT
    								id, cobros_cliente, metodo_pago,
    								referencia, monto_moneda, moneda,
    								tasa_moneda, monto_bs, tasa_usd,
    								monto_usd, banco
    							FROM cobros_cliente_detalle
    							WHERE cobros_cliente = $idPago AND metodo_pago = 'RC';"; 
    					$rows = ExecuteRows($sql);
                        foreach ($rows as $key => $row) {
                        	$sql = "SELECT IFNULL(MAX(nro_recibo), 0)+1 AS nro FROM abono WHERE 1;";
                        	$nro_recibo = 0; // ExecuteScalar($sql);
                        	$sql = "INSERT INTO
                        		abono 
                        	SET 	
                        		id = NULL,
                        		cliente = $cliente,
                        		fecha = NOW(),
                        		metodo_pago = NULL,
                        		nro_recibo = $nro_recibo,
                        		nota = 'POR ANULACION',
                        		username = '" . CurrentUserName() . "';";
                        	Execute($sql);
                        	$sql = "SELECT LAST_INSERT_ID();";
                        	$Abono = ExecuteScalar($sql);
                            $id = $row["cobros_cliente"];
                            $referencia = $row["referencia"];
                            $metodo_pago = 'DV'; // $row["metodo_pago"];
                            $moneda = $row["moneda"];
                            $monto_moneda = $row["monto_moneda"];
                            $tasa = $row["tasa_moneda"];
                            $monto_bs = $row["monto_bs"];
                            $tasa_usd = $row["tasa_usd"];
                            $monto_usd = $row["monto_usd"];
                            $username = CurrentUserName();
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
                                        username, reverso, abono)
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
                                        'Reverso por elminación de cobro Nro. $id, con referencia de recarga Nro. $referencia, Tipo documento $tipo_documento Nro. $nro_documento.',
                                        '$username', 'S', $Abono)"; 
                            Execute($sql);
                            $sql = "SELECT LAST_INSERT_ID();";
                            $id = ExecuteScalar($sql);
                            $sql = "SELECT IFNULL(SUM(monto_usd), 0) AS saldo FROM recarga
                                    WHERE cliente = $cliente;";
                            $saldo = ExecuteScalar($sql);
                            $sql = "UPDATE recarga SET saldo = $saldo WHERE id = $id;";
                            Execute($sql);
                            $sql = "SELECT SUM(monto_usd) AS pago FROM recarga WHERE abono = $Abono;";
                            $monto_abono = ExecuteScalar($sql);
                            $sql = "UPDATE abono SET pago = $monto_abono WHERE id = $Abono";
                            Execute($sql);
                        }
                        ///////////////////////

    					/*** Reverso cobros en USD ***/
    					$sql = "SELECT
    								id, cobros_cliente, metodo_pago,
    								referencia, monto_moneda, moneda,
    								tasa_moneda, monto_bs, tasa_usd,
    								monto_usd, banco
    							FROM cobros_cliente_detalle
    							WHERE cobros_cliente = $idPago AND metodo_pago = 'RD';"; 
    					$rows = ExecuteRows($sql);
                        foreach ($rows as $key => $row) {
                        	$sql = "SELECT IFNULL(MAX(nro_recibo), 0)+1 AS nro FROM abono2 WHERE 1;";
                        	$nro_recibo = 0; // ExecuteScalar($sql);
                        	$sql = "INSERT INTO
                        		abono2 
                        	SET 	
                        		id = NULL,
                        		cliente = $cliente,
                        		fecha = NOW(),
                        		metodo_pago = NULL,
                        		nro_recibo = $nro_recibo,
                        		nota = 'POR ANULACION',
                        		username = '" . CurrentUserName() . "';";
                        	Execute($sql);
                        	$sql = "SELECT LAST_INSERT_ID();";
                        	$Abono = ExecuteScalar($sql);
                            $id = $row["cobros_cliente"];
                            $referencia = $row["referencia"];
                            $metodo_pago = 'DV'; // $row["metodo_pago"];
                            $moneda = $row["moneda"];
                            $monto_moneda = $row["monto_moneda"];
                            $tasa = $row["tasa_moneda"];
                            $monto_bs = $row["monto_bs"];
                            $tasa_usd = $row["tasa_usd"];
                            $monto_usd = $row["monto_usd"];
                            $username = CurrentUserName();
                            $sql = "INSERT INTO recarga2(
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
                                        username, reverso, abono)
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
                                        'Reverso por elminación de cobro Nro. $id, con referencia de recarga Nro. $referencia, Tipo documento $tipo_documento Nro. $nro_documento.',
                                        '$username', 'S', $Abono)"; 
                            Execute($sql);
                            $sql = "SELECT LAST_INSERT_ID();";
                            $id = ExecuteScalar($sql);
                            $sql = "SELECT IFNULL(SUM(monto_usd), 0) AS saldo FROM recarga2
                                    WHERE cliente = $cliente;";
                            $saldo = ExecuteScalar($sql);
                            $sql = "UPDATE recarga2 SET saldo = $saldo WHERE id = $id;";
                            Execute($sql);
                            $sql = "SELECT SUM(monto_usd) AS pago FROM recarga2 WHERE abono = $Abono;";
                            $monto_abono = ExecuteScalar($sql);
                            $sql = "UPDATE abono2 SET pago = $monto_abono WHERE id = $Abono";
                            Execute($sql);
                        }

                        // *** Busca los excedentes *** //
                        // *** En Abonos en Dolares *** //
                        $sql = "SELECT
                                    id, cliente, CURDATE() AS fecha, metodo_pago,
                                    id AS referencia, monto_moneda, moneda,
                                    tasa_moneda, monto_bs, tasa_usd, monto_usd, saldo,
                                    nota, username 
                                FROM recarga2 WHERE cobro_cliente_reverso = $idPago";
                        $rows = ExecuteRows($sql);
                        foreach ($rows as $key => $row) {
                        	$sql = "SELECT IFNULL(MAX(nro_recibo), 0)+1 AS nro FROM abono2 WHERE 1;";
                        	$nro_recibo = 0; // ExecuteScalar($sql);
                        	$sql = "INSERT INTO
                        		abono2 
                        	SET 	
                        		id = NULL,
                        		cliente = $cliente,
                        		fecha = NOW(),
                        		metodo_pago = NULL,
                        		nro_recibo = $nro_recibo,
                        		nota = 'POR ANULACION',
                        		username = '" . CurrentUserName() . "';";
                        	Execute($sql);
                        	$sql = "SELECT LAST_INSERT_ID();";
                        	$Abono = ExecuteScalar($sql);
                            $id = $row["cobros_cliente"];
                            $referencia = $row["referencia"];
                            $metodo_pago = $row["metodo_pago"];
                            $moneda = $row["moneda"];
                            $monto_moneda = $row["monto_moneda"];
                            $tasa = $row["tasa_moneda"];
                            $monto_bs = $row["monto_bs"];
                            $tasa_usd = $row["tasa_usd"];
                            $monto_usd = $row["monto_usd"];
                            $username = CurrentUserName();
                            $sql = "INSERT INTO recarga2(
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
                                        username, reverso, abono)
                                    VALUES (
                                        NULL,
                                        $cliente,
                                        NOW(),
                                        '$metodo_pago',
                                        $monto_moneda,
                                        '$moneda',
                                        $tasa,
                                        (-1)*$monto_bs,
                                        $tasa_usd,
                                        (-1)*$monto_usd,
                                        0,
                                        'Reverso de abono por elminación de cobro Nro. $id, con referencia de recarga Nro. $referencia, Tipo documento $tipo_documento Nro. $nro_documento.',
                                        '$username', 'S', $Abono)";
                            Execute($sql);
                            $sql = "SELECT LAST_INSERT_ID();";
                            $id = ExecuteScalar($sql);
                            $sql = "SELECT IFNULL(SUM(monto_usd), 0) AS saldo2 FROM recarga2 
                                    WHERE cliente = $cliente;";
                            $saldo = ExecuteScalar($sql);
                            $sql = "UPDATE recarga2 SET saldo = $saldo WHERE id = $id;";
                            Execute($sql);
                            $sql = "SELECT SUM(monto_usd) AS pago FROM recarga2 WHERE abono = $Abono;";
                            $monto_abono = ExecuteScalar($sql);
                            $sql = "UPDATE abono2 SET pago = $monto_abono WHERE id = $Abono";
                            Execute($sql);
                        }
                        $sql = "DELETE FROM cobros_cliente_detalle WHERE cobros_cliente = $idPago;";
                        Execute($sql);
                        $sql = "DELETE FROM cobros_cliente WHERE id = $idPago;";
                        Execute($sql);
    				}
    			}
    			$sql = "SELECT id FROM cobros_cliente WHERE id_documento = " . $rsold["id"] . ";";
    			if($row = ExecuteRow($sql)) $hay_pagos = true;
    			else $hay_pagos = false;
    			if($rsnew["estatus"] == "PROCESADO" and $hay_pagos == false) {
    				$this->CancelMessage = "No hay pasgos asociados; no se puede procesar el documento. Verifique!";
    				return FALSE;
    			}
    		}
    	}
    	return TRUE;
    }

    // Row Updated event
    public function rowUpdated($rsold, &$rsnew) {
    	//echo "Row Updated";
    	if($rsold["tipo_documento"] == "TDCFCV") {
    		/*if($rsold["tasa_dia"] != $rsnew["tasa_dia"])
    			$tasa = floatval($rsnew["tasa_dia"]);
    		else
    			$tasa = floatval($rsold["tasa_dia"]);*/
    		ActualizarTotalFacturaVenta($rsold["id"], $rsold["tipo_documento"]);
    		if($rsold["impreso"]=="S" and $rsnew["impreso"]=="N") {
    			$id = $rsold["id"];

    			// Tomo el siguiente número de factura
    			$sql = "SELECT valor1, valor2, valor3 FROM parametro WHERE codigo = '003';";
    			$row = ExecuteRow($sql);
    			$numero = intval($row["valor1"]) + 1;
    			$prefijo = trim($row["valor2"]);
    			$padeo = intval($row["valor3"]);
    			$factura = $prefijo . str_pad($numero, $padeo, "0", STR_PAD_LEFT); 
    			$sql = "UPDATE parametro SET valor1='$numero' 
    					WHERE codigo = '003';";
    			Execute($sql);

    			// Tomo el siguiente número de control de factura
    			$sql = "SELECT valor1, valor2, valor3 FROM parametro WHERE codigo = '030';";
    			$row = ExecuteRow($sql);
    			$numero = intval($row["valor1"]) + 1;
    			$prefijo = trim($row["valor2"]);
    			$padeo = intval($row["valor3"]);
    			$facturaCTRL = $prefijo . str_pad($numero, $padeo, "0", STR_PAD_LEFT); 
    			$sql = "UPDATE parametro SET valor1='$numero' 
    					WHERE codigo = '030';";
    			Execute($sql);

    			/**********************/
    			// Inserto el encabezado de la factura
    			// VIENE DE LA FACTURA AREIMPRIMIR
    			$sql = "INSERT INTO salidas
    				(id, tipo_documento, username, fecha, cliente, nro_documento, nro_control, monto_total, alicuota_iva, iva, total, lista_pedido, nota, estatus, id_documento_padre, moneda, asesor, documento, tasa_dia, monto_usd, dias_credito, entregado, fecha_entrega, pagado, bultos, fecha_bultos, user_bultos, fecha_despacho, user_despacho, consignacion, unidades, descuento, monto_sin_descuento, factura, ci_rif, nombre, direccion, telefono, email, activo, comprobante, doc_afectado, nro_despacho, cerrado, impreso)
    			SELECT 
    				NULL, tipo_documento, '" . CurrentUserName() . "', NOW(), cliente, '$factura' AS nro_documento, '$facturaCTRL' AS nro_control, monto_total, alicuota_iva, iva, total, lista_pedido, CONCAT('NUEVA FACTURA POR REEMPRESION DE FACT # ', nro_documento) AS nota, estatus, id_documento_padre, moneda, asesor, documento, tasa_dia, monto_usd, dias_credito, entregado, fecha_entrega, pagado, bultos, fecha_bultos, user_bultos, fecha_despacho, user_despacho, consignacion, unidades, descuento, monto_sin_descuento, factura, ci_rif, nombre, direccion, telefono, email, activo, comprobante, doc_afectado, nro_despacho, cerrado, 'N' AS impreso
    			FROM salidas WHERE id = $id;";
    			ExecuteScalar($sql);

    			// Obtengo el id de la nueva factura
    			$factura_id = ExecuteScalar("SELECT LAST_INSERT_ID();");
    			$tipo_documento = ExecuteScalar("SELECT tipo_documento FROM salidas WHERE id = $factura_id;");

    			// Poblo el detalle de la factura
    			$sql = "INSERT INTO entradas_salidas
    						(id, tipo_documento, id_documento, fabricante, articulo, lote, fecha_vencimiento, almacen, cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, cantidad_movimiento, costo_unidad, costo, precio_unidad, precio, id_compra, alicuota, cantidad_movimiento_consignacion, id_consignacion, descuento, precio_unidad_sin_desc, check_ne)
    					SELECT 
    						NULL, '$tipo_documento' AS tipo_documento, $factura_id AS id_documento, fabricante, articulo, lote, fecha_vencimiento, almacen, cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, cantidad_movimiento, costo_unidad, costo, precio_unidad, precio, id_compra, alicuota, cantidad_movimiento_consignacion, id_consignacion, descuento, precio_unidad_sin_desc, check_ne
    					FROM entradas_salidas WHERE id_documento = $id AND tipo_documento = '$tipo_documento';";
    			Execute($sql);
    			$sql = "UPDATE salidas SET estatus = 'ANULADO', nota = 'SE ANULA POR REEMPRESION EN IMPRESORA FISCAL.' WHERE id = '$id'";
    			Execute($sql);

    			/**************/
    		}
    	}
    	if($rsold["tipo_documento"] == "TDCNET") {
    		$sql = "SELECT valor1 AS aplica FROM parametro WHERE codigo = '050';";
    		if($row = ExecuteRow($sql)) {
    			if($row["aplica"] == "S") {
    				Aplicar3x2($rsold["id"], "TDCNET");
    			}
    		}
    		ActualizarTotalFacturaVenta($rsold["id"], $rsold["tipo_documento"]);
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
    	if(CurrentUserLevel() != -1) {
    		if($rs["estatus"] != "NUEVO") {
    			$this->CancelMessage = "Este documento est&aacute; procesado o anulada; no se puede eliminar.";
    			return FALSE;
    		}
    	}
    	$fecha = date("Y-m-d");
    	$sql = "SELECT fecha FROM cierre_de_caja WHERE fecha = '$fecha';";
    	if($row = ExecuteRow($sql)) {
    		$this->CancelMessage = "El d&iacute;a " . date("d/m/Y") . " est&aacute; cerrado; no se puede eliminar el documento. Verifique!";
    		return FALSE;
    	}
    	$sql = "SELECT id FROM cobros_cliente WHERE id_documento = '". $rs["id"] . "';";
    	if($row = ExecuteRow($sql)) {
    		$this->CancelMessage = "El documento tiene pagos registrados; no se puede eliminar. Verifique!";
    		return FALSE;
    	}
    	if($rs["tipo_documento"] == "TDCFCV") {
    		if($rs["comprobante"] != "") {
    			$this->CancelMessage = "Este documento est&aacute; contabilizado; no se puede eliminar.";
    			return FALSE;
    		}

    		// Valido cierre de facturación
    		if($rs["cerrado"] == "S") {
    			$this->CancelMessage = "El mes en el que est&aacute; la factura est&aacute; cerrado. Verifique!";
    			return FALSE;
    		}
    	}
    	$sql = "DELETE FROM entradas_salidas WHERE tipo_documento = '" . $rs["tipo_documento"] . "' AND id_documento = '" . $rs["id"] . "';";
    	Execute($sql);
    	return TRUE;
    }

    // Row Deleted event
    public function rowDeleted(&$rs) {
    	//echo "Row Deleted";
    	if($rs["tipo_documento"] != "TDCFCV") {
    		ActualizarExitencia();
    	}
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
    	$color = "";
    	$color2 = "";
    	$tipo = $this->tipo_documento->CurrentValue;
    	if ($this->PageID == "list" || $this->PageID == "view") {
    		if ($this->estatus->ViewValue == "NUEVO") { 
    			$color = "background-color: #eda135; color: #ffffff;";
    			if($tipo=="TDCPDV") {
    				if(trim($this->nombre->CurrentValue) == "") $color2 = "background-color: #f8040f; color: #ffffff;";
    				$this->id->CellAttrs["style"] = $color2;
    			}
    		}
    		elseif ($this->estatus->ViewValue == "PROCESADO") {
    			if($tipo=="TDCPDV") {
    				// Si está facturado marco el estatus facturado de lo contrario queda como recibido
    				$sql = "SELECT 
    						(SELECT COUNT(id) FROM salidas WHERE id_documento_padre = a.id) AS cantidad 
    					FROM 
    						salidas AS a 
    					WHERE a.id_documento_padre = " . $this->id->CurrentValue . " AND a.estatus <> 'ANULADO';";
    				$cantidad = ExecuteScalar($sql);
    				if($cantidad > 0)
    					$color = "background-color: #51aa51; color: #ffffff;";
    				else
    					$color = "background-color: #66B2FF; color: #ffffff;";
    			}
    			elseif($tipo=="TDCNET") {
    				if(intval($this->bultos->CurrentValue) > 0) {
    					if(trim($this->user_despacho->CurrentValue) == "")
    						$color = "background-color: #6c757d; color: #ffffff;";
    					else
    						$color = "background-color: #343a40; color: #ffffff;";					
    				}
    				else {
    					$color = "background-color: #51aa51; color: #ffffff;";
    				}
    			}
    			else $color = "background-color: #51aa51; color: #ffffff;";
    		}
    		elseif ($this->estatus->ViewValue == "ANULADO") {
    			$color = "background-color: #cc3f3b; color: #ffffff;";
    		}
    		$sql = "SELECT COUNT(id) AS cantidad FROM pagos
    				WHERE tipo_documento = '" . $this->tipo_documento->CurrentValue . "' AND id_documento = '" . $this->id->CurrentValue . "';";
    		$cantidad = ExecuteScalar($sql);
    		if($cantidad > 0) {
    			$this->estatus->CellAttrs["style"] = $color;
    			$this->RowAttrs["class"] = "info";
    			//$this->cliente->CellAttrs["style"] = "background-color: #66B2FF; color: #ffffff;";
    			$color = "";
    		}
    		else $this->estatus->CellAttrs["style"] = $color;
    		if($this->pagado->CurrentValue == "S") {
    			$this->cliente->CellAttrs["style"] = "background-color: #66B2FF; color: #ffffff;";
    		}
    		$this->tipo_documento->CellAttrs["style"] = $color;
    		$this->nro_documento->CellAttrs["style"] = $color;
    		$this->nro_control->CellAttrs["style"] = $color;
    		$this->fecha->CellAttrs["style"] = $color;
    		$this->total->CellAttrs["style"] = $color;
    		$this->cliente->CellAttrs["style"] = $color;

    		/* 02-12-2020 Alerta si hay una cantidad distinta de ITEMS que el pedido o documento origen */
    		if($tipo=="TDCNET" or $tipo=="TDCFCV") {
    			$id = $this->id->CurrentValue;
    			$id_documento_padre = $this->id_documento_padre->CurrentValue;
    			$sql = "SELECT COUNT(articulo) 
    					FROM entradas_salidas 
    					WHERE tipo_documento IN (SELECT codigo FROM tipo_documento WHERE tipo = 'CLIENTE') 
    						AND id_documento = '$id';";
    			$cnt1 = ExecuteScalar($sql);
    			$da = ExecuteScalar("SELECT descripcion FROM tipo_documento WHERE codigo = '$tipo'");
    			$sql = "SELECT COUNT(articulo) 
    					FROM entradas_salidas 
    					WHERE tipo_documento IN (SELECT codigo FROM tipo_documento WHERE tipo = 'CLIENTE') 
    						AND id_documento = '$id_documento_padre';";
    			$cnt2 = ExecuteScalar($sql);
    			$sql = "SELECT id_documento_padre, tipo_documento  
    					FROM salidas 
    					WHERE id = '$id_documento_padre';";
    			if($row = ExecuteRow($sql)) {
    				$do = $row["tipo_documento"];
    				$do_padre = $row["id_documento_padre"];
    				$do = ExecuteScalar("SELECT descripcion FROM tipo_documento WHERE codigo = '$do'");
    			}
    			else {
    				$do = "";
    				$do_padre = "";
    			}
    			$coletilla = "";
    			$cnt3 = 0;
    		}
    		if($tipo=="TDCPDV" and $this->id->CurrentValue != "") {
            	$levelid = CurrentUserLevel();
            	$sql = "SELECT tipo_acceso FROM userlevels WHERE userlevelid = $levelid";
            	$tipo_acceso = ExecuteScalar($sql);
            	if($tipo_acceso != "CLIENTE") {
            		$sql = "SELECT tipo_cliente FROM cliente WHERE id = " . $this->cliente->CurrentValue . ""; 
            		$tipo_cliente = ExecuteScalar($sql);
            		$sql = "SELECT campo_dato AS color FROM tabla
            				WHERE tabla = 'tipo_cliente' AND campo_codigo = '" . $tipo_cliente . "';";
            		if($row = ExecuteRow($sql)) {
            		  $color = explode(";", $row["color"]);
    				  $this->cliente->CellAttrs["style"] = "background-color: " . $color[0] . "; color: " . $color[1] . ";";
                    }
    			}
    		}

    		/* Marca con negro el numero de documento si fue entregada la mercancia */
            if (trim($this->id->CurrentValue) != "") {
                if($this->tipo_documento->CurrentValue == "TDCPDV") {
                    $sql = "SELECT 
                                IFNULL(a.entregado, 'N') AS entregado, a.tipo_documento  
                            FROM 
                                salidas AS a  
                            WHERE a.id_documento_padre = " . $this->id->CurrentValue . ";"; 
                    if($row = ExecuteRow($sql)) {
                        if($row["tipo_documento"] == 'TDCNET' AND $row["entregado"] == "S") {
                            $this->nro_documento->CellAttrs["style"] = "background-color: #343a40; color: #ffffff;";
                        }
                    }
                }
                else {
                    if($this->tipo_documento->CurrentValue == "TDCFCV") {
                        $sql = "SELECT 
                                    IFNULL(a.entregado, 'N') AS entregado, a.tipo_documento  
                                FROM 
                                    salidas AS a  
                                WHERE a.id = '" . $this->id_documento_padre->CurrentValue . "';"; 
                        if($row = ExecuteRow($sql)) {
                            if($row["tipo_documento"] == 'TDCNET' AND $row["entregado"] == "S") {
                                $this->nro_documento->CellAttrs["style"] = "background-color: #343a40; color: #ffffff;";
                            }
                        }
                    }
                }
            }
    		/* -------------- */
    	}
    }

    // User ID Filtering event
    public function userIdFiltering(&$filter)
    {
        // Enter your code here
    }
}
