<?php

namespace PHPMaker2021\mandrake;

// Page object
$MainReport = &$Page;
?>
<?php
// Define el arreglo asociativo con los códigos y los nombres de los reportes
// Esto debe ir al inicio de tu script PHP, antes del HTML.
$reportes = [
    'ims' => [
        'heading' => 'Reportes IMS',
        'items' => [
            'CIMS' => 'EXPORTAR CLIENTES IMS',
            'AIMS' => 'EXPORTAR ARTICULOS IMS',
            'FIMS' => 'EXPORTAR FACTURAS IMS',
        ],
    ],
    'libros' => [
        'heading' => 'Libros y Reportes Financieros',
        'items' => [
            'LBCOMP' => 'LIBRO DE COMPRAS',
            'LBVENT' => 'LIBRO DE VENTAS',
            'FCCVSP' => 'FACTURAS COSTOS VS PRECIO',
            'KARDEX' => 'KARDEX DE INVENTARIO',
        ],
    ],
    'ventas' => [
        'heading' => 'Reportes de Ventas',
        'items' => [
            'VENFAB' => 'VENTAS POR FABRICANTE',
            'VENART' => 'VENTAS POR ARTICULO (NOTAS DE ENTREGA)',
            'VENCAN' => 'CANJES POR PREMIO (NOTAS DE ENTREGA)',
            'VENARC' => 'VENTAS POR ARTICULO (SOLO CANTIDADES)',
            'SALGEN' => 'SALIDAS GENERALES POR FABRICANTE',
            'SALART' => 'SALIDAS GENERALES POR ARTICULO',
            'VENCLI' => 'VENTAS POR CLIENTE',
        ],
    ],
    'consignaciones' => [
        'heading' => 'Consignaciones',
        'items' => [
            'CONCLI' => 'CONSIGNACIONES POR CLIENTE',
            'FACCON' => 'FACTURAS POR CONSIGNACION',
        ],
    ],
    'clientes' => [
        'heading' => 'Reportes de Clientes',
        'items' => [
            'COMPRE' => 'CLIENTES CON COMPRAS RECIENTES',
            'COMSIN' => 'CLIENTES SIN COMPRAS RECIENTES',
        ],
    ],
    'varios' => [
        'heading' => 'Varios',
        'items' => [
            'INVENT' => 'INVENTARIO ENTRE FECHA',
            'DEVOLU' => 'DEVOLUCIONES ENTRE FECHA',
            'DESCON' => 'DESCARGA ENTRADAS A CONSIGNACION',
        ],
    ],
    'cierre' => [
        'heading' => 'Cierres Fiscales',
        'items' => [
            'REPX' => 'REPORTE X',
            'REPZ' => 'REPORTE Z',
        ],
    ],
];
?>

<div class="panel panel-default">
    <div class="panel-heading">Seleccione un Reporte</div>
    <div class="panel-body">
        <form method="get" action="ListadoMaster">
            <?php foreach ($reportes as $grupo) : ?>
                <div class="form-group">
                    <label class="fw-bold fs-5 text-primary"><?= $grupo['heading'] ?></label>
                    <div class="list-group">
                        <?php foreach ($grupo['items'] as $codigo => $nombre) : ?>
                            <label class="list-group-item">
                                <input type="radio" name="id" value="<?= $codigo ?>" <?= $codigo == 'CIMS' ? 'checked="checked"' : '' ?>>
                                &nbsp; <?= $nombre ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <hr>
            <?php endforeach; ?>
            <input type="submit" class="btn btn-primary" value="Generar">
        </form>
    </div>
</div>

<?= GetDebugMessage() ?>
