<?php

namespace PHPMaker2021\mandrake;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for compra
 */
class Compra extends DbTable
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
    public $proveedor;
    public $tipo_documento;
    public $doc_afectado;
    public $documento;
    public $nro_control;
    public $fecha;
    public $descripcion;
    public $aplica_retencion;
    public $monto_exento;
    public $monto_gravado;
    public $alicuota;
    public $monto_iva;
    public $monto_total;
    public $monto_pagar;
    public $ret_iva;
    public $ref_iva;
    public $ret_islr;
    public $ref_islr;
    public $ret_municipal;
    public $ref_municipal;
    public $fecha_registro;
    public $_username;
    public $comprobante;
    public $tipo_iva;
    public $tipo_islr;
    public $sustraendo;
    public $tipo_municipal;
    public $anulado;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'compra';
        $this->TableName = 'compra';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`compra`";
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
        $this->id = new DbField('compra', 'compra', 'x_id', 'id', '`id`', '`id`', 19, 10, -1, false, '`id`', false, false, false, 'FORMATTED TEXT', 'NO');
        $this->id->IsAutoIncrement = true; // Autoincrement field
        $this->id->IsPrimaryKey = true; // Primary key field
        $this->id->Sortable = true; // Allow sort
        $this->id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->id->Param, "CustomMsg");
        $this->Fields['id'] = &$this->id;

        // proveedor
        $this->proveedor = new DbField('compra', 'compra', 'x_proveedor', 'proveedor', '`proveedor`', '`proveedor`', 19, 10, -1, false, '`proveedor`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->proveedor->Required = true; // Required field
        $this->proveedor->Sortable = true; // Allow sort
        $this->proveedor->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->proveedor->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->proveedor->Lookup = new Lookup('proveedor', 'proveedor', false, 'id', ["nombre","","",""], [], [], [], [], [], [], '`nombre`', '');
        $this->proveedor->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->proveedor->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->proveedor->Param, "CustomMsg");
        $this->Fields['proveedor'] = &$this->proveedor;

        // tipo_documento
        $this->tipo_documento = new DbField('compra', 'compra', 'x_tipo_documento', 'tipo_documento', '`tipo_documento`', '`tipo_documento`', 200, 2, -1, false, '`tipo_documento`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->tipo_documento->Required = true; // Required field
        $this->tipo_documento->Sortable = true; // Allow sort
        $this->tipo_documento->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->tipo_documento->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->tipo_documento->Lookup = new Lookup('tipo_documento', 'compra', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->tipo_documento->OptionCount = 5;
        $this->tipo_documento->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tipo_documento->Param, "CustomMsg");
        $this->Fields['tipo_documento'] = &$this->tipo_documento;

        // doc_afectado
        $this->doc_afectado = new DbField('compra', 'compra', 'x_doc_afectado', 'doc_afectado', '`doc_afectado`', '`doc_afectado`', 200, 20, -1, false, '`doc_afectado`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->doc_afectado->Sortable = true; // Allow sort
        $this->doc_afectado->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->doc_afectado->Param, "CustomMsg");
        $this->Fields['doc_afectado'] = &$this->doc_afectado;

        // documento
        $this->documento = new DbField('compra', 'compra', 'x_documento', 'documento', '`documento`', '`documento`', 200, 20, -1, false, '`documento`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->documento->Required = true; // Required field
        $this->documento->Sortable = true; // Allow sort
        $this->documento->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->documento->Param, "CustomMsg");
        $this->Fields['documento'] = &$this->documento;

        // nro_control
        $this->nro_control = new DbField('compra', 'compra', 'x_nro_control', 'nro_control', '`nro_control`', '`nro_control`', 200, 20, -1, false, '`nro_control`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->nro_control->Sortable = true; // Allow sort
        $this->nro_control->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->nro_control->Param, "CustomMsg");
        $this->Fields['nro_control'] = &$this->nro_control;

        // fecha
        $this->fecha = new DbField('compra', 'compra', 'x_fecha', 'fecha', '`fecha`', CastDateFieldForLike("`fecha`", 7, "DB"), 133, 10, 7, false, '`fecha`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->fecha->Required = true; // Required field
        $this->fecha->Sortable = true; // Allow sort
        $this->fecha->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_SEPARATOR"], $Language->phrase("IncorrectDateDMY"));
        $this->fecha->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->fecha->Param, "CustomMsg");
        $this->Fields['fecha'] = &$this->fecha;

        // descripcion
        $this->descripcion = new DbField('compra', 'compra', 'x_descripcion', 'descripcion', '`descripcion`', '`descripcion`', 200, 150, -1, false, '`descripcion`', false, false, false, 'FORMATTED TEXT', 'TEXTAREA');
        $this->descripcion->Required = true; // Required field
        $this->descripcion->Sortable = true; // Allow sort
        $this->descripcion->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->descripcion->Param, "CustomMsg");
        $this->Fields['descripcion'] = &$this->descripcion;

        // aplica_retencion
        $this->aplica_retencion = new DbField('compra', 'compra', 'x_aplica_retencion', 'aplica_retencion', '`aplica_retencion`', '`aplica_retencion`', 202, 1, -1, false, '`aplica_retencion`', false, false, false, 'FORMATTED TEXT', 'RADIO');
        $this->aplica_retencion->Required = true; // Required field
        $this->aplica_retencion->Sortable = true; // Allow sort
        $this->aplica_retencion->Lookup = new Lookup('aplica_retencion', 'compra', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->aplica_retencion->OptionCount = 2;
        $this->aplica_retencion->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->aplica_retencion->Param, "CustomMsg");
        $this->Fields['aplica_retencion'] = &$this->aplica_retencion;

        // monto_exento
        $this->monto_exento = new DbField('compra', 'compra', 'x_monto_exento', 'monto_exento', '`monto_exento`', '`monto_exento`', 131, 14, -1, false, '`monto_exento`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->monto_exento->Sortable = true; // Allow sort
        $this->monto_exento->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->monto_exento->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_exento->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->monto_exento->Param, "CustomMsg");
        $this->Fields['monto_exento'] = &$this->monto_exento;

        // monto_gravado
        $this->monto_gravado = new DbField('compra', 'compra', 'x_monto_gravado', 'monto_gravado', '`monto_gravado`', '`monto_gravado`', 131, 14, -1, false, '`monto_gravado`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->monto_gravado->Sortable = true; // Allow sort
        $this->monto_gravado->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->monto_gravado->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_gravado->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->monto_gravado->Param, "CustomMsg");
        $this->Fields['monto_gravado'] = &$this->monto_gravado;

        // alicuota
        $this->alicuota = new DbField('compra', 'compra', 'x_alicuota', 'alicuota', '`alicuota`', '`alicuota`', 131, 14, -1, false, '`alicuota`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->alicuota->Sortable = true; // Allow sort
        $this->alicuota->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->alicuota->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->alicuota->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->alicuota->Param, "CustomMsg");
        $this->Fields['alicuota'] = &$this->alicuota;

        // monto_iva
        $this->monto_iva = new DbField('compra', 'compra', 'x_monto_iva', 'monto_iva', '`monto_iva`', '`monto_iva`', 131, 14, -1, false, '`monto_iva`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->monto_iva->Sortable = true; // Allow sort
        $this->monto_iva->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->monto_iva->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_iva->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->monto_iva->Param, "CustomMsg");
        $this->Fields['monto_iva'] = &$this->monto_iva;

        // monto_total
        $this->monto_total = new DbField('compra', 'compra', 'x_monto_total', 'monto_total', '`monto_total`', '`monto_total`', 131, 14, -1, false, '`monto_total`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->monto_total->Sortable = true; // Allow sort
        $this->monto_total->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->monto_total->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_total->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->monto_total->Param, "CustomMsg");
        $this->Fields['monto_total'] = &$this->monto_total;

        // monto_pagar
        $this->monto_pagar = new DbField('compra', 'compra', 'x_monto_pagar', 'monto_pagar', '`monto_pagar`', '`monto_pagar`', 131, 14, -1, false, '`monto_pagar`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->monto_pagar->Sortable = true; // Allow sort
        $this->monto_pagar->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->monto_pagar->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->monto_pagar->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->monto_pagar->Param, "CustomMsg");
        $this->Fields['monto_pagar'] = &$this->monto_pagar;

        // ret_iva
        $this->ret_iva = new DbField('compra', 'compra', 'x_ret_iva', 'ret_iva', '`ret_iva`', '`ret_iva`', 131, 14, -1, false, '`ret_iva`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->ret_iva->Sortable = true; // Allow sort
        $this->ret_iva->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->ret_iva->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->ret_iva->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->ret_iva->Param, "CustomMsg");
        $this->Fields['ret_iva'] = &$this->ret_iva;

        // ref_iva
        $this->ref_iva = new DbField('compra', 'compra', 'x_ref_iva', 'ref_iva', '`ref_iva`', '`ref_iva`', 200, 30, -1, false, '`ref_iva`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->ref_iva->Sortable = true; // Allow sort
        $this->ref_iva->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->ref_iva->Param, "CustomMsg");
        $this->Fields['ref_iva'] = &$this->ref_iva;

        // ret_islr
        $this->ret_islr = new DbField('compra', 'compra', 'x_ret_islr', 'ret_islr', '`ret_islr`', '`ret_islr`', 131, 14, -1, false, '`ret_islr`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->ret_islr->Sortable = true; // Allow sort
        $this->ret_islr->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->ret_islr->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->ret_islr->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->ret_islr->Param, "CustomMsg");
        $this->Fields['ret_islr'] = &$this->ret_islr;

        // ref_islr
        $this->ref_islr = new DbField('compra', 'compra', 'x_ref_islr', 'ref_islr', '`ref_islr`', '`ref_islr`', 200, 30, -1, false, '`ref_islr`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->ref_islr->Sortable = true; // Allow sort
        $this->ref_islr->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->ref_islr->Param, "CustomMsg");
        $this->Fields['ref_islr'] = &$this->ref_islr;

        // ret_municipal
        $this->ret_municipal = new DbField('compra', 'compra', 'x_ret_municipal', 'ret_municipal', '`ret_municipal`', '`ret_municipal`', 131, 14, -1, false, '`ret_municipal`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->ret_municipal->Sortable = true; // Allow sort
        $this->ret_municipal->DefaultDecimalPrecision = 2; // Default decimal precision
        $this->ret_municipal->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->ret_municipal->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->ret_municipal->Param, "CustomMsg");
        $this->Fields['ret_municipal'] = &$this->ret_municipal;

        // ref_municipal
        $this->ref_municipal = new DbField('compra', 'compra', 'x_ref_municipal', 'ref_municipal', '`ref_municipal`', '`ref_municipal`', 200, 30, -1, false, '`ref_municipal`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->ref_municipal->Sortable = true; // Allow sort
        $this->ref_municipal->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->ref_municipal->Param, "CustomMsg");
        $this->Fields['ref_municipal'] = &$this->ref_municipal;

        // fecha_registro
        $this->fecha_registro = new DbField('compra', 'compra', 'x_fecha_registro', 'fecha_registro', '`fecha_registro`', CastDateFieldForLike("`fecha_registro`", 7, "DB"), 133, 10, 7, false, '`fecha_registro`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->fecha_registro->Required = true; // Required field
        $this->fecha_registro->Sortable = true; // Allow sort
        $this->fecha_registro->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_SEPARATOR"], $Language->phrase("IncorrectDateDMY"));
        $this->fecha_registro->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->fecha_registro->Param, "CustomMsg");
        $this->Fields['fecha_registro'] = &$this->fecha_registro;

        // username
        $this->_username = new DbField('compra', 'compra', 'x__username', 'username', '`username`', '`username`', 200, 30, -1, false, '`username`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->_username->Sortable = true; // Allow sort
        $this->_username->Lookup = new Lookup('username', 'usuario', false, 'username', ["nombre","","",""], [], [], [], [], [], [], '', '');
        $this->_username->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->_username->Param, "CustomMsg");
        $this->Fields['username'] = &$this->_username;

        // comprobante
        $this->comprobante = new DbField('compra', 'compra', 'x_comprobante', 'comprobante', '`comprobante`', '`comprobante`', 19, 10, -1, false, '`comprobante`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->comprobante->Sortable = true; // Allow sort
        $this->comprobante->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->comprobante->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->comprobante->Lookup = new Lookup('comprobante', 'cont_comprobante', false, 'id', ["id","descripcion","",""], [], [], [], [], [], [], '', '');
        $this->comprobante->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->comprobante->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->comprobante->Param, "CustomMsg");
        $this->Fields['comprobante'] = &$this->comprobante;

        // tipo_iva
        $this->tipo_iva = new DbField('compra', 'compra', 'x_tipo_iva', 'tipo_iva', '`tipo_iva`', '`tipo_iva`', 200, 4, -1, false, '`tipo_iva`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->tipo_iva->Sortable = true; // Allow sort
        $this->tipo_iva->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tipo_iva->Param, "CustomMsg");
        $this->Fields['tipo_iva'] = &$this->tipo_iva;

        // tipo_islr
        $this->tipo_islr = new DbField('compra', 'compra', 'x_tipo_islr', 'tipo_islr', '`tipo_islr`', '`tipo_islr`', 200, 4, -1, false, '`tipo_islr`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->tipo_islr->Sortable = true; // Allow sort
        $this->tipo_islr->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tipo_islr->Param, "CustomMsg");
        $this->Fields['tipo_islr'] = &$this->tipo_islr;

        // sustraendo
        $this->sustraendo = new DbField('compra', 'compra', 'x_sustraendo', 'sustraendo', '`sustraendo`', '`sustraendo`', 131, 14, -1, false, '`sustraendo`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->sustraendo->Sortable = true; // Allow sort
        $this->sustraendo->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->sustraendo->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->sustraendo->Param, "CustomMsg");
        $this->Fields['sustraendo'] = &$this->sustraendo;

        // tipo_municipal
        $this->tipo_municipal = new DbField('compra', 'compra', 'x_tipo_municipal', 'tipo_municipal', '`tipo_municipal`', '`tipo_municipal`', 200, 4, -1, false, '`tipo_municipal`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->tipo_municipal->Sortable = true; // Allow sort
        $this->tipo_municipal->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tipo_municipal->Param, "CustomMsg");
        $this->Fields['tipo_municipal'] = &$this->tipo_municipal;

        // anulado
        $this->anulado = new DbField('compra', 'compra', 'x_anulado', 'anulado', '`anulado`', '`anulado`', 202, 1, -1, false, '`anulado`', false, false, false, 'FORMATTED TEXT', 'RADIO');
        $this->anulado->Required = true; // Required field
        $this->anulado->Sortable = true; // Allow sort
        $this->anulado->Lookup = new Lookup('anulado', 'compra', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->anulado->OptionCount = 2;
        $this->anulado->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->anulado->Param, "CustomMsg");
        $this->Fields['anulado'] = &$this->anulado;
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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`compra`";
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
        $this->proveedor->DbValue = $row['proveedor'];
        $this->tipo_documento->DbValue = $row['tipo_documento'];
        $this->doc_afectado->DbValue = $row['doc_afectado'];
        $this->documento->DbValue = $row['documento'];
        $this->nro_control->DbValue = $row['nro_control'];
        $this->fecha->DbValue = $row['fecha'];
        $this->descripcion->DbValue = $row['descripcion'];
        $this->aplica_retencion->DbValue = $row['aplica_retencion'];
        $this->monto_exento->DbValue = $row['monto_exento'];
        $this->monto_gravado->DbValue = $row['monto_gravado'];
        $this->alicuota->DbValue = $row['alicuota'];
        $this->monto_iva->DbValue = $row['monto_iva'];
        $this->monto_total->DbValue = $row['monto_total'];
        $this->monto_pagar->DbValue = $row['monto_pagar'];
        $this->ret_iva->DbValue = $row['ret_iva'];
        $this->ref_iva->DbValue = $row['ref_iva'];
        $this->ret_islr->DbValue = $row['ret_islr'];
        $this->ref_islr->DbValue = $row['ref_islr'];
        $this->ret_municipal->DbValue = $row['ret_municipal'];
        $this->ref_municipal->DbValue = $row['ref_municipal'];
        $this->fecha_registro->DbValue = $row['fecha_registro'];
        $this->_username->DbValue = $row['username'];
        $this->comprobante->DbValue = $row['comprobante'];
        $this->tipo_iva->DbValue = $row['tipo_iva'];
        $this->tipo_islr->DbValue = $row['tipo_islr'];
        $this->sustraendo->DbValue = $row['sustraendo'];
        $this->tipo_municipal->DbValue = $row['tipo_municipal'];
        $this->anulado->DbValue = $row['anulado'];
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
        return $_SESSION[$name] ?? GetUrl("CompraList");
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
        if ($pageName == "CompraView") {
            return $Language->phrase("View");
        } elseif ($pageName == "CompraEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "CompraAdd") {
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
                return "CompraView";
            case Config("API_ADD_ACTION"):
                return "CompraAdd";
            case Config("API_EDIT_ACTION"):
                return "CompraEdit";
            case Config("API_DELETE_ACTION"):
                return "CompraDelete";
            case Config("API_LIST_ACTION"):
                return "CompraList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "CompraList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("CompraView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("CompraView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "CompraAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "CompraAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("CompraEdit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("CompraAdd", $this->getUrlParm($parm));
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
        return $this->keyUrl("CompraDelete", $this->getUrlParm());
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
        $this->proveedor->setDbValue($row['proveedor']);
        $this->tipo_documento->setDbValue($row['tipo_documento']);
        $this->doc_afectado->setDbValue($row['doc_afectado']);
        $this->documento->setDbValue($row['documento']);
        $this->nro_control->setDbValue($row['nro_control']);
        $this->fecha->setDbValue($row['fecha']);
        $this->descripcion->setDbValue($row['descripcion']);
        $this->aplica_retencion->setDbValue($row['aplica_retencion']);
        $this->monto_exento->setDbValue($row['monto_exento']);
        $this->monto_gravado->setDbValue($row['monto_gravado']);
        $this->alicuota->setDbValue($row['alicuota']);
        $this->monto_iva->setDbValue($row['monto_iva']);
        $this->monto_total->setDbValue($row['monto_total']);
        $this->monto_pagar->setDbValue($row['monto_pagar']);
        $this->ret_iva->setDbValue($row['ret_iva']);
        $this->ref_iva->setDbValue($row['ref_iva']);
        $this->ret_islr->setDbValue($row['ret_islr']);
        $this->ref_islr->setDbValue($row['ref_islr']);
        $this->ret_municipal->setDbValue($row['ret_municipal']);
        $this->ref_municipal->setDbValue($row['ref_municipal']);
        $this->fecha_registro->setDbValue($row['fecha_registro']);
        $this->_username->setDbValue($row['username']);
        $this->comprobante->setDbValue($row['comprobante']);
        $this->tipo_iva->setDbValue($row['tipo_iva']);
        $this->tipo_islr->setDbValue($row['tipo_islr']);
        $this->sustraendo->setDbValue($row['sustraendo']);
        $this->tipo_municipal->setDbValue($row['tipo_municipal']);
        $this->anulado->setDbValue($row['anulado']);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // id

        // proveedor

        // tipo_documento

        // doc_afectado

        // documento

        // nro_control

        // fecha

        // descripcion

        // aplica_retencion

        // monto_exento

        // monto_gravado

        // alicuota

        // monto_iva

        // monto_total

        // monto_pagar

        // ret_iva

        // ref_iva

        // ret_islr

        // ref_islr

        // ret_municipal

        // ref_municipal

        // fecha_registro

        // username

        // comprobante

        // tipo_iva

        // tipo_islr

        // sustraendo

        // tipo_municipal

        // anulado

        // id
        $this->id->ViewValue = $this->id->CurrentValue;
        $this->id->ViewCustomAttributes = "";

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

        // tipo_documento
        if (strval($this->tipo_documento->CurrentValue) != "") {
            $this->tipo_documento->ViewValue = $this->tipo_documento->optionCaption($this->tipo_documento->CurrentValue);
        } else {
            $this->tipo_documento->ViewValue = null;
        }
        $this->tipo_documento->ViewCustomAttributes = "";

        // doc_afectado
        $this->doc_afectado->ViewValue = $this->doc_afectado->CurrentValue;
        $this->doc_afectado->ViewCustomAttributes = "";

        // documento
        $this->documento->ViewValue = $this->documento->CurrentValue;
        $this->documento->ViewCustomAttributes = "";

        // nro_control
        $this->nro_control->ViewValue = $this->nro_control->CurrentValue;
        $this->nro_control->ViewCustomAttributes = "";

        // fecha
        $this->fecha->ViewValue = $this->fecha->CurrentValue;
        $this->fecha->ViewValue = FormatDateTime($this->fecha->ViewValue, 7);
        $this->fecha->ViewCustomAttributes = "";

        // descripcion
        $this->descripcion->ViewValue = $this->descripcion->CurrentValue;
        $this->descripcion->ViewCustomAttributes = "";

        // aplica_retencion
        if (strval($this->aplica_retencion->CurrentValue) != "") {
            $this->aplica_retencion->ViewValue = $this->aplica_retencion->optionCaption($this->aplica_retencion->CurrentValue);
        } else {
            $this->aplica_retencion->ViewValue = null;
        }
        $this->aplica_retencion->ViewCustomAttributes = "";

        // monto_exento
        $this->monto_exento->ViewValue = $this->monto_exento->CurrentValue;
        $this->monto_exento->ViewValue = FormatNumber($this->monto_exento->ViewValue, 2, -1, -1, -1);
        $this->monto_exento->ViewCustomAttributes = "";

        // monto_gravado
        $this->monto_gravado->ViewValue = $this->monto_gravado->CurrentValue;
        $this->monto_gravado->ViewValue = FormatNumber($this->monto_gravado->ViewValue, 2, -1, -1, -1);
        $this->monto_gravado->ViewCustomAttributes = "";

        // alicuota
        $this->alicuota->ViewValue = $this->alicuota->CurrentValue;
        $this->alicuota->ViewValue = FormatNumber($this->alicuota->ViewValue, 2, -1, -1, -1);
        $this->alicuota->ViewCustomAttributes = "";

        // monto_iva
        $this->monto_iva->ViewValue = $this->monto_iva->CurrentValue;
        $this->monto_iva->ViewValue = FormatNumber($this->monto_iva->ViewValue, 2, -1, -1, -1);
        $this->monto_iva->ViewCustomAttributes = "";

        // monto_total
        $this->monto_total->ViewValue = $this->monto_total->CurrentValue;
        $this->monto_total->ViewValue = FormatNumber($this->monto_total->ViewValue, 2, -1, -1, -1);
        $this->monto_total->ViewCustomAttributes = "";

        // monto_pagar
        $this->monto_pagar->ViewValue = $this->monto_pagar->CurrentValue;
        $this->monto_pagar->ViewValue = FormatNumber($this->monto_pagar->ViewValue, 2, -1, -1, -1);
        $this->monto_pagar->CssClass = "font-weight-bold";
        $this->monto_pagar->ViewCustomAttributes = "";

        // ret_iva
        $this->ret_iva->ViewValue = $this->ret_iva->CurrentValue;
        $this->ret_iva->ViewValue = FormatNumber($this->ret_iva->ViewValue, 2, -1, -1, -1);
        $this->ret_iva->CssClass = "font-weight-bold";
        $this->ret_iva->ViewCustomAttributes = "";

        // ref_iva
        $this->ref_iva->ViewValue = $this->ref_iva->CurrentValue;
        $this->ref_iva->ViewCustomAttributes = "";

        // ret_islr
        $this->ret_islr->ViewValue = $this->ret_islr->CurrentValue;
        $this->ret_islr->ViewValue = FormatNumber($this->ret_islr->ViewValue, 2, -1, -1, -1);
        $this->ret_islr->CssClass = "font-weight-bold";
        $this->ret_islr->ViewCustomAttributes = "";

        // ref_islr
        $this->ref_islr->ViewValue = $this->ref_islr->CurrentValue;
        $this->ref_islr->ViewCustomAttributes = "";

        // ret_municipal
        $this->ret_municipal->ViewValue = $this->ret_municipal->CurrentValue;
        $this->ret_municipal->ViewValue = FormatNumber($this->ret_municipal->ViewValue, 2, -2, -2, -2);
        $this->ret_municipal->CssClass = "font-weight-bold";
        $this->ret_municipal->ViewCustomAttributes = "";

        // ref_municipal
        $this->ref_municipal->ViewValue = $this->ref_municipal->CurrentValue;
        $this->ref_municipal->ViewCustomAttributes = "";

        // fecha_registro
        $this->fecha_registro->ViewValue = $this->fecha_registro->CurrentValue;
        $this->fecha_registro->ViewValue = FormatDateTime($this->fecha_registro->ViewValue, 7);
        $this->fecha_registro->ViewCustomAttributes = "";

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

        // tipo_municipal
        $this->tipo_municipal->ViewValue = $this->tipo_municipal->CurrentValue;
        $this->tipo_municipal->ViewCustomAttributes = "";

        // anulado
        if (strval($this->anulado->CurrentValue) != "") {
            $this->anulado->ViewValue = $this->anulado->optionCaption($this->anulado->CurrentValue);
        } else {
            $this->anulado->ViewValue = null;
        }
        $this->anulado->ViewCustomAttributes = "";

        // id
        $this->id->LinkCustomAttributes = "";
        $this->id->HrefValue = "";
        $this->id->TooltipValue = "";

        // proveedor
        $this->proveedor->LinkCustomAttributes = "";
        $this->proveedor->HrefValue = "";
        $this->proveedor->TooltipValue = "";

        // tipo_documento
        $this->tipo_documento->LinkCustomAttributes = "";
        $this->tipo_documento->HrefValue = "";
        $this->tipo_documento->TooltipValue = "";

        // doc_afectado
        $this->doc_afectado->LinkCustomAttributes = "";
        $this->doc_afectado->HrefValue = "";
        $this->doc_afectado->TooltipValue = "";

        // documento
        $this->documento->LinkCustomAttributes = "";
        $this->documento->HrefValue = "";
        $this->documento->TooltipValue = "";

        // nro_control
        $this->nro_control->LinkCustomAttributes = "";
        $this->nro_control->HrefValue = "";
        $this->nro_control->TooltipValue = "";

        // fecha
        $this->fecha->LinkCustomAttributes = "";
        $this->fecha->HrefValue = "";
        $this->fecha->TooltipValue = "";

        // descripcion
        $this->descripcion->LinkCustomAttributes = "";
        $this->descripcion->HrefValue = "";
        $this->descripcion->TooltipValue = "";

        // aplica_retencion
        $this->aplica_retencion->LinkCustomAttributes = "";
        $this->aplica_retencion->HrefValue = "";
        $this->aplica_retencion->TooltipValue = "";

        // monto_exento
        $this->monto_exento->LinkCustomAttributes = "";
        $this->monto_exento->HrefValue = "";
        $this->monto_exento->TooltipValue = "";

        // monto_gravado
        $this->monto_gravado->LinkCustomAttributes = "";
        $this->monto_gravado->HrefValue = "";
        $this->monto_gravado->TooltipValue = "";

        // alicuota
        $this->alicuota->LinkCustomAttributes = "";
        $this->alicuota->HrefValue = "";
        $this->alicuota->TooltipValue = "";

        // monto_iva
        $this->monto_iva->LinkCustomAttributes = "";
        $this->monto_iva->HrefValue = "";
        $this->monto_iva->TooltipValue = "";

        // monto_total
        $this->monto_total->LinkCustomAttributes = "";
        $this->monto_total->HrefValue = "";
        $this->monto_total->TooltipValue = "";

        // monto_pagar
        $this->monto_pagar->LinkCustomAttributes = "";
        $this->monto_pagar->HrefValue = "";
        $this->monto_pagar->TooltipValue = "";

        // ret_iva
        $this->ret_iva->LinkCustomAttributes = "";
        $this->ret_iva->HrefValue = "";
        $this->ret_iva->TooltipValue = "";

        // ref_iva
        $this->ref_iva->LinkCustomAttributes = "";
        if (!EmptyValue($this->id->CurrentValue)) {
            $this->ref_iva->HrefValue = "../reportes/rptRetencion.php?Nretencion=" . $this->id->CurrentValue; // Add prefix/suffix
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
            $this->ref_islr->HrefValue = "../reportes/rptRetencion2.php?Nretencion=" . $this->id->CurrentValue; // Add prefix/suffix
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
        if (!EmptyValue($this->id->CurrentValue)) {
            $this->ref_municipal->HrefValue = "../reportes/rptRetencion3.php?Nretencion=" . $this->id->CurrentValue; // Add prefix/suffix
            $this->ref_municipal->LinkAttrs["target"] = "_blank"; // Add target
            if ($this->isExport()) {
                $this->ref_municipal->HrefValue = FullUrl($this->ref_municipal->HrefValue, "href");
            }
        } else {
            $this->ref_municipal->HrefValue = "";
        }
        $this->ref_municipal->TooltipValue = "";

        // fecha_registro
        $this->fecha_registro->LinkCustomAttributes = "";
        $this->fecha_registro->HrefValue = "";
        $this->fecha_registro->TooltipValue = "";

        // username
        $this->_username->LinkCustomAttributes = "";
        $this->_username->HrefValue = "";
        $this->_username->TooltipValue = "";

        // comprobante
        $this->comprobante->LinkCustomAttributes = "";
        if (!EmptyValue($this->comprobante->CurrentValue)) {
            $this->comprobante->HrefValue = "../ContAsientoList?showmaster=cont_comprobante&fk_id=" . $this->comprobante->CurrentValue; // Add prefix/suffix
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

        // tipo_municipal
        $this->tipo_municipal->LinkCustomAttributes = "";
        $this->tipo_municipal->HrefValue = "";
        $this->tipo_municipal->TooltipValue = "";

        // anulado
        $this->anulado->LinkCustomAttributes = "";
        $this->anulado->HrefValue = "";
        $this->anulado->TooltipValue = "";

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

        // proveedor
        $this->proveedor->EditAttrs["class"] = "form-control";
        $this->proveedor->EditCustomAttributes = "";
        $this->proveedor->PlaceHolder = RemoveHtml($this->proveedor->caption());

        // tipo_documento
        $this->tipo_documento->EditAttrs["class"] = "form-control";
        $this->tipo_documento->EditCustomAttributes = "";
        $this->tipo_documento->EditValue = $this->tipo_documento->options(true);
        $this->tipo_documento->PlaceHolder = RemoveHtml($this->tipo_documento->caption());

        // doc_afectado
        $this->doc_afectado->EditAttrs["class"] = "form-control";
        $this->doc_afectado->EditCustomAttributes = "";
        if (!$this->doc_afectado->Raw) {
            $this->doc_afectado->CurrentValue = HtmlDecode($this->doc_afectado->CurrentValue);
        }
        $this->doc_afectado->EditValue = $this->doc_afectado->CurrentValue;
        $this->doc_afectado->PlaceHolder = RemoveHtml($this->doc_afectado->caption());

        // documento
        $this->documento->EditAttrs["class"] = "form-control";
        $this->documento->EditCustomAttributes = "";
        if (!$this->documento->Raw) {
            $this->documento->CurrentValue = HtmlDecode($this->documento->CurrentValue);
        }
        $this->documento->EditValue = $this->documento->CurrentValue;
        $this->documento->PlaceHolder = RemoveHtml($this->documento->caption());

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

        // descripcion
        $this->descripcion->EditAttrs["class"] = "form-control";
        $this->descripcion->EditCustomAttributes = "";
        $this->descripcion->EditValue = $this->descripcion->CurrentValue;
        $this->descripcion->PlaceHolder = RemoveHtml($this->descripcion->caption());

        // aplica_retencion
        $this->aplica_retencion->EditCustomAttributes = "";
        $this->aplica_retencion->EditValue = $this->aplica_retencion->options(false);
        $this->aplica_retencion->PlaceHolder = RemoveHtml($this->aplica_retencion->caption());

        // monto_exento
        $this->monto_exento->EditAttrs["class"] = "form-control";
        $this->monto_exento->EditCustomAttributes = "";
        $this->monto_exento->EditValue = $this->monto_exento->CurrentValue;
        $this->monto_exento->PlaceHolder = RemoveHtml($this->monto_exento->caption());
        if (strval($this->monto_exento->EditValue) != "" && is_numeric($this->monto_exento->EditValue)) {
            $this->monto_exento->EditValue = FormatNumber($this->monto_exento->EditValue, -2, -1, -2, -1);
        }

        // monto_gravado
        $this->monto_gravado->EditAttrs["class"] = "form-control";
        $this->monto_gravado->EditCustomAttributes = "";
        $this->monto_gravado->EditValue = $this->monto_gravado->CurrentValue;
        $this->monto_gravado->PlaceHolder = RemoveHtml($this->monto_gravado->caption());
        if (strval($this->monto_gravado->EditValue) != "" && is_numeric($this->monto_gravado->EditValue)) {
            $this->monto_gravado->EditValue = FormatNumber($this->monto_gravado->EditValue, -2, -1, -2, -1);
        }

        // alicuota
        $this->alicuota->EditAttrs["class"] = "form-control";
        $this->alicuota->EditCustomAttributes = "";
        $this->alicuota->EditValue = $this->alicuota->CurrentValue;
        $this->alicuota->PlaceHolder = RemoveHtml($this->alicuota->caption());
        if (strval($this->alicuota->EditValue) != "" && is_numeric($this->alicuota->EditValue)) {
            $this->alicuota->EditValue = FormatNumber($this->alicuota->EditValue, -2, -1, -2, -1);
        }

        // monto_iva
        $this->monto_iva->EditAttrs["class"] = "form-control";
        $this->monto_iva->EditCustomAttributes = "";
        $this->monto_iva->EditValue = $this->monto_iva->CurrentValue;
        $this->monto_iva->PlaceHolder = RemoveHtml($this->monto_iva->caption());
        if (strval($this->monto_iva->EditValue) != "" && is_numeric($this->monto_iva->EditValue)) {
            $this->monto_iva->EditValue = FormatNumber($this->monto_iva->EditValue, -2, -1, -2, -1);
        }

        // monto_total
        $this->monto_total->EditAttrs["class"] = "form-control";
        $this->monto_total->EditCustomAttributes = "";
        $this->monto_total->EditValue = $this->monto_total->CurrentValue;
        $this->monto_total->PlaceHolder = RemoveHtml($this->monto_total->caption());
        if (strval($this->monto_total->EditValue) != "" && is_numeric($this->monto_total->EditValue)) {
            $this->monto_total->EditValue = FormatNumber($this->monto_total->EditValue, -2, -1, -2, -1);
        }

        // monto_pagar
        $this->monto_pagar->EditAttrs["class"] = "form-control";
        $this->monto_pagar->EditCustomAttributes = "";
        $this->monto_pagar->EditValue = $this->monto_pagar->CurrentValue;
        $this->monto_pagar->PlaceHolder = RemoveHtml($this->monto_pagar->caption());
        if (strval($this->monto_pagar->EditValue) != "" && is_numeric($this->monto_pagar->EditValue)) {
            $this->monto_pagar->EditValue = FormatNumber($this->monto_pagar->EditValue, -2, -1, -2, -1);
        }

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

        // fecha_registro
        $this->fecha_registro->EditAttrs["class"] = "form-control";
        $this->fecha_registro->EditCustomAttributes = "";
        $this->fecha_registro->EditValue = FormatDateTime($this->fecha_registro->CurrentValue, 7);
        $this->fecha_registro->PlaceHolder = RemoveHtml($this->fecha_registro->caption());

        // username
        $this->_username->EditAttrs["class"] = "form-control";
        $this->_username->EditCustomAttributes = "";
        if (!$this->_username->Raw) {
            $this->_username->CurrentValue = HtmlDecode($this->_username->CurrentValue);
        }
        $this->_username->EditValue = $this->_username->CurrentValue;
        $this->_username->PlaceHolder = RemoveHtml($this->_username->caption());

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

        // tipo_municipal
        $this->tipo_municipal->EditAttrs["class"] = "form-control";
        $this->tipo_municipal->EditCustomAttributes = "";
        if (!$this->tipo_municipal->Raw) {
            $this->tipo_municipal->CurrentValue = HtmlDecode($this->tipo_municipal->CurrentValue);
        }
        $this->tipo_municipal->EditValue = $this->tipo_municipal->CurrentValue;
        $this->tipo_municipal->PlaceHolder = RemoveHtml($this->tipo_municipal->caption());

        // anulado
        $this->anulado->EditCustomAttributes = "";
        $this->anulado->EditValue = $this->anulado->options(false);
        $this->anulado->PlaceHolder = RemoveHtml($this->anulado->caption());

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
                    $doc->exportCaption($this->proveedor);
                    $doc->exportCaption($this->tipo_documento);
                    $doc->exportCaption($this->doc_afectado);
                    $doc->exportCaption($this->documento);
                    $doc->exportCaption($this->nro_control);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->descripcion);
                    $doc->exportCaption($this->monto_exento);
                    $doc->exportCaption($this->monto_gravado);
                    $doc->exportCaption($this->alicuota);
                    $doc->exportCaption($this->monto_iva);
                    $doc->exportCaption($this->monto_total);
                    $doc->exportCaption($this->monto_pagar);
                    $doc->exportCaption($this->fecha_registro);
                    $doc->exportCaption($this->_username);
                } else {
                    $doc->exportCaption($this->id);
                    $doc->exportCaption($this->proveedor);
                    $doc->exportCaption($this->tipo_documento);
                    $doc->exportCaption($this->doc_afectado);
                    $doc->exportCaption($this->documento);
                    $doc->exportCaption($this->nro_control);
                    $doc->exportCaption($this->fecha);
                    $doc->exportCaption($this->descripcion);
                    $doc->exportCaption($this->aplica_retencion);
                    $doc->exportCaption($this->monto_exento);
                    $doc->exportCaption($this->monto_gravado);
                    $doc->exportCaption($this->alicuota);
                    $doc->exportCaption($this->monto_iva);
                    $doc->exportCaption($this->monto_total);
                    $doc->exportCaption($this->monto_pagar);
                    $doc->exportCaption($this->ret_iva);
                    $doc->exportCaption($this->ref_iva);
                    $doc->exportCaption($this->ret_islr);
                    $doc->exportCaption($this->ref_islr);
                    $doc->exportCaption($this->ret_municipal);
                    $doc->exportCaption($this->ref_municipal);
                    $doc->exportCaption($this->fecha_registro);
                    $doc->exportCaption($this->_username);
                    $doc->exportCaption($this->comprobante);
                    $doc->exportCaption($this->tipo_iva);
                    $doc->exportCaption($this->tipo_islr);
                    $doc->exportCaption($this->sustraendo);
                    $doc->exportCaption($this->tipo_municipal);
                    $doc->exportCaption($this->anulado);
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
                        $doc->exportField($this->proveedor);
                        $doc->exportField($this->tipo_documento);
                        $doc->exportField($this->doc_afectado);
                        $doc->exportField($this->documento);
                        $doc->exportField($this->nro_control);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->descripcion);
                        $doc->exportField($this->monto_exento);
                        $doc->exportField($this->monto_gravado);
                        $doc->exportField($this->alicuota);
                        $doc->exportField($this->monto_iva);
                        $doc->exportField($this->monto_total);
                        $doc->exportField($this->monto_pagar);
                        $doc->exportField($this->fecha_registro);
                        $doc->exportField($this->_username);
                    } else {
                        $doc->exportField($this->id);
                        $doc->exportField($this->proveedor);
                        $doc->exportField($this->tipo_documento);
                        $doc->exportField($this->doc_afectado);
                        $doc->exportField($this->documento);
                        $doc->exportField($this->nro_control);
                        $doc->exportField($this->fecha);
                        $doc->exportField($this->descripcion);
                        $doc->exportField($this->aplica_retencion);
                        $doc->exportField($this->monto_exento);
                        $doc->exportField($this->monto_gravado);
                        $doc->exportField($this->alicuota);
                        $doc->exportField($this->monto_iva);
                        $doc->exportField($this->monto_total);
                        $doc->exportField($this->monto_pagar);
                        $doc->exportField($this->ret_iva);
                        $doc->exportField($this->ref_iva);
                        $doc->exportField($this->ret_islr);
                        $doc->exportField($this->ref_islr);
                        $doc->exportField($this->ret_municipal);
                        $doc->exportField($this->ref_municipal);
                        $doc->exportField($this->fecha_registro);
                        $doc->exportField($this->_username);
                        $doc->exportField($this->comprobante);
                        $doc->exportField($this->tipo_iva);
                        $doc->exportField($this->tipo_islr);
                        $doc->exportField($this->sustraendo);
                        $doc->exportField($this->tipo_municipal);
                        $doc->exportField($this->anulado);
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
        $table = 'compra';
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
        $table = 'compra';

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
        $table = 'compra';

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
        $table = 'compra';

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
    	// Valido que ya no se haya registrado el número de factura 
    	$proveedor = $rsnew["proveedor"];
    	$rsnew["documento"] = trim($rsnew["documento"]);
    	$tipo_documento = $rsnew["tipo_documento"];
    	$documento = $rsnew["documento"];
    	$sql = "SELECT documento FROM compra WHERE proveedor = $proveedor AND tipo_documento='$tipo_documento' AND documento = '$documento';";
    	if($row = ExecuteRow($sql)){
    		$this->CancelMessage = "El n&uacute;mero de documento ya est&aacute; registrado para el proveedor; verifique.";
    		return FALSE;
    	}
    	if($rsnew["tipo_documento"] == "FC" or trim($rsnew["tipo_documento"]) == "" or trim($rsnew["tipo_documento"]) == "RC") {
    		$rsnew["doc_afectado"] == ""; 
    	} 
    	else {
    		if($rsnew["doc_afectado"] == "") {
    			$this->CancelMessage = "Debe colocar n&uacute;mero de documento afectado.";
    			return FALSE;
    		}
    	}
    	if($rsnew["tipo_documento"] == "FC") {
    		if(trim($rsnew["nro_control"]) == "") {
    			$this->CancelMessage = "Debe colocar n&uacute;mero de control.";
    			return FALSE;
    		}
    	}

    	/*if($tipo_documento == "RC") {
    		$alicuota = 0.00;
    		$rsnew["alicuota"] = $alicuota;
    		$rsnew["aplica_retencion"] = "N";
    	}*/
    	$rsnew["aplica_retencion"] = "N";
    	$alicuota = floatval($rsnew["alicuota"]);
    	$monto_exento = floatval($rsnew["monto_exento"]);
    	$monto_gravado = floatval($rsnew["monto_gravado"]);
    	$monto_iva = $monto_gravado * ($alicuota/100);
    	$monto_total = $monto_exento + $monto_gravado + $monto_iva;
    	$monto_pagar = $monto_total;
    	if($rsnew["aplica_retencion"] == "S") {
    		//$sql = "SELECT ci_rif AS rif, tipo_iva, tipo_islr, sustraendo, tipo_impmun FROM proveedor WHERE id = " . $rsnew["proveedor"] . ";";
    		$sql = "SELECT 
    					a.ci_rif AS rif, 
    					(SELECT campo_descripcion FROM tabla WHERE campo_codigo = a.tipo_ret_iva) AS tipo_iva, 
    					(SELECT tarifa FROM tabla_retenciones WHERE id = a.tipo_ret_islr) AS tipo_islr, 
    					(SELECT sustraendo FROM tabla_retenciones WHERE id = a.tipo_ret_islr) AS sustraendo, 
    					(SELECT campo_descripcion FROM tabla WHERE campo_codigo = a.tipo_ret_mun) AS tipo_impmun 
    				FROM
    					proveedor AS a
    				WHERE a.id = " . $rsnew["proveedor"] . ";";
    		$row = ExecuteRow($sql);
    		$retIVA = floatval($row["tipo_iva"]);
    		$retISLR = floatval($row["tipo_islr"]);
    		$sustraendo = floatval($row["sustraendo"]);
    		$retMuni = floatval($row["tipo_impmun"]);
    		$rif = trim($row["rif"]);
    		$MretIVA = $monto_iva * ($retIVA/100);
    		$MretSLR = (($monto_gravado) * ($retISLR/100)) - $sustraendo;
    		$MretMUNI = $monto_gravado * ($retMuni/100);
    		$monto_pagar = $monto_total - ($MretIVA+$MretSLR+$MretMUNI);
    		$rsnew["ret_iva"] = $MretIVA;
    		$rsnew["ret_islr"] = $MretSLR;
    		$rsnew["ret_municipal"] = $MretMUNI;
    	}
    	else {
    		$retIVA = 0.00;
    		$retISLR = 0.00;
    		$sustraendo = 0.00;
    		$retMuni = 0.00;
    		$rsnew["ret_iva"] = $retIVA;
    		$rsnew["ret_islr"] = $retISLR;
    		$rsnew["ret_municipal"] = $retMuni;
    	}
    	$rsnew["monto_iva"] = $monto_iva;
    	$rsnew["monto_total"] = $monto_total;
    	$rsnew["monto_pagar"] = $monto_pagar;
    	$rsnew["fecha_registro"] = $rsnew["fecha"];
    	$rsnew["username"] = CurrentUserName();
    	$rsnew["tipo_iva"] = strval($retIVA);
    	$rsnew["tipo_islr"] = strval($retISLR);
    	$rsnew["sustraendo"] = $sustraendo;
    	$rsnew["tipo_municipal"] = strval($retMuni);
    	return TRUE;
    }

    // Row Inserted event
    public function rowInserted($rsold, &$rsnew)
    {
        //Log("Row Inserted");
        if($rsnew["tipo_documento"] == "FC") {
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
    			$sql = "UPDATE compra SET ref_iva = '$comprobante' 
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
    			$sql = "UPDATE compra SET ref_municipal = '$comprobante' 
    					WHERE id = '" . $rsnew["id"] . "';";
    			Execute($sql);
    		}
        }
    }

    // Row Updating event
    public function rowUpdating($rsold, &$rsnew) {
    	// Enter your code here
    	// To cancel, set return value to FALSE
    	if($rsold["tipo_documento"] != $rsnew["tipo_documento"]) {
    		$this->CancelMessage = "No se puede cambiar el tipo de documento; verifique.";
    		return FALSE;
    	}
    	$tipo_documento = $rsnew["tipo_documento"];
    	if($rsold["anulado"] == "S" and $rsnew["anulado"] == "N") { 
    		if(!VerificaFuncion('019')) {
    			$this->CancelMessage = "No est&aacute; autorizado para cambiar a estatus activo; verifique.";
    			return FALSE;
    		}
    	}

    ///////////////////
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
    ///////////////////
    	if($rsnew["tipo_documento"] == "FC" or trim($rsnew["tipo_documento"]) == "" or trim($rsnew["tipo_documento"]) == "RC") {
    		$rsnew["doc_afectado"] == ""; 
    	} 
    	else {
    		if($rsnew["doc_afectado"] == "") {
    			$this->CancelMessage = "Debe colocar n&uacute;mero de documento afectado.";
    			return FALSE;
    		}
    	}
    	if($rsnew["tipo_documento"] == "FC") {
    		if(trim($rsnew["nro_control"]) == "") {
    			$this->CancelMessage = "Debe colocar n&uacute;mero de control.";
    			return FALSE;
    		}
    	}
    	if($tipo_documento == "RC") {
    		$alicuota = 0.00;
    		$rsnew["alicuota"] = $alicuota;
    		$rsnew["aplica_retencion"] = "N";
    	}
    	$alicuota = floatval($rsnew["alicuota"]);
    	$monto_exento = floatval($rsnew["monto_exento"]);
    	$monto_gravado = floatval($rsnew["monto_gravado"]);
    	$monto_iva = $monto_gravado * ($alicuota/100);
    	$monto_total = $monto_exento + $monto_gravado + $monto_iva;
    	$monto_pagar = $monto_total;
    	if($rsnew["aplica_retencion"] == "S") {
    		//$sql = "SELECT ci_rif AS rif, tipo_iva, tipo_islr, sustraendo, tipo_impmun FROM proveedor WHERE id = " . $rsnew["proveedor"] . ";";
    		$sql = "SELECT 
    					a.ci_rif AS rif, 
    					(SELECT campo_descripcion FROM tabla WHERE campo_codigo = a.tipo_ret_iva) AS tipo_iva, 
    					(SELECT tarifa FROM tabla_retenciones WHERE id = a.tipo_ret_islr) AS tipo_islr, 
    					(SELECT sustraendo FROM tabla_retenciones WHERE id = a.tipo_ret_islr) AS sustraendo, 
    					(SELECT campo_descripcion FROM tabla WHERE campo_codigo = a.tipo_ret_mun) AS tipo_impmun 
    				FROM
    					proveedor AS a
    				WHERE a.id = " . $rsnew["proveedor"] . ";";
    		$row = ExecuteRow($sql);
    		$retIVA = floatval($row["tipo_iva"]);
    		$retISLR = floatval($row["tipo_islr"]);
    		$sustraendo = floatval($row["sustraendo"]);
    		$retMuni = floatval($row["tipo_impmun"]);
    		$rif = trim($row["rif"]);
    		$MretIVA = $monto_iva * ($retIVA/100);
    		$MretSLR = (($monto_gravado) * ($retISLR/100)) - $sustraendo;
    		$MretMUNI = $monto_gravado * ($retMuni/100);
    		$monto_pagar = $monto_total - ($MretIVA+$MretSLR+$MretMUNI);
    		$rsnew["ret_iva"] = $MretIVA;
    		$rsnew["ret_islr"] = $MretSLR;
    		$rsnew["ret_municipal"] = $MretMUNI;
    	}
    	else {
    		$retIVA = 0.00;
    		$retISLR = 0.00;
    		$sustraendo = 0.00;
    		$retMuni = 0.00;
    		$rsnew["ret_iva"] = $retIVA;
    		$rsnew["ret_islr"] = $retISLR;
    		$rsnew["ret_municipal"] = $retMuni;
    	}
    	$rsnew["monto_iva"] = $monto_iva;
    	$rsnew["monto_total"] = $monto_total;
    	$rsnew["monto_pagar"] = $monto_pagar;
    	$rsnew["username"] = CurrentUserName();
    	$rsnew["tipo_iva"] = strval($retIVA);
    	$rsnew["tipo_islr"] = strval($retISLR);
    	$rsnew["sustraendo"] = $sustraendo;
    	$rsnew["tipo_municipal"] = strval($retMuni);
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
    	if($rs["ref_iva"] != "") {
    		$this->CancelMessage = "El documento tiene n&uacute;mero de comprobante de IVA asociado; no se puede eliminar.";
    		return FALSE;
    	}
    	if($rs["comprobante"] != "") {
    		$this->CancelMessage = "Este movimiento tiene un asiento contable; no se puede eliminar.";
    		return FALSE;
    	}
    	if(!VerificaFuncion('020')) {
    		$this->CancelMessage = "No est&aacute; autorizado para eliminar compras administrativas; verifique.";
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
    	if($this->comprobante->CurrentValue == "")
    		$this->RowAttrs["class"] = ""; 
    	else
    		$this->RowAttrs["class"] = "success";
    	$color = "";
    	if ($this->PageID == "list" || $this->PageID == "view") {
    		if ($this->anulado->CurrentValue == "S") { 
    			$color = "background-color: #000000; color: #ffffff;";
    			$this->proveedor->CellAttrs["style"] = $color;
    			$this->documento->CellAttrs["style"] = $color;
    			$this->aplica_retencion->CellAttrs["style"] = $color;
    			$this->monto_pagar->CellAttrs["style"] = $color;
    			$this->anulado->CellAttrs["style"] = $color;
    		}
    	}
    }

    // User ID Filtering event
    public function userIdFiltering(&$filter)
    {
        // Enter your code here
    }
}
