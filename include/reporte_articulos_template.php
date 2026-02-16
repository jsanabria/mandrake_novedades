<?php
// Mapeo de códigos de reportes para los títulos y lógicas específicas de la tabla
$report_config = [
    'VENART' => [
        'title' => 'Ventas por Artículo',
        'headers' => ['ARTICULO', 'DOCUMENTO', 'FECHA', 'CLIENTE', 'CANTIDAD', 'COSTO UND.', 'COSTO TOTAL', 'PRECIO UND.', 'PRECIO TOTAL'],
        'fields' => ['articulo', 'nro_documento', 'fecha', 'cliente', 'cantidad_movimiento', 'costo_unidad', 'costo', 'precio_unidad', 'precio'],
        'format' => ['text', 'text', 'text', 'text', 'number', 'number_2', 'number_2', 'number_2', 'number_2'],
        'totals' => true
    ],
    'SALART' => [
        'title' => 'Salidas Generales por Artículo',
        'headers' => ['TIPO', 'DOCUMENTO', 'FECHA', 'CLIENTE', 'ARTICULO', 'CANTIDAD'],
        'fields' => ['tipo', 'nro_documento', 'fecha', 'cliente', 'articulo', 'cantidad_movimiento'],
        'format' => ['text', 'text', 'text', 'text', 'text', 'number'],
        'totals' => true
    ],
    'VENCLI' => [
        'title' => 'Ventas por Cliente',
        'headers' => ['ARTICULO', 'DOCUMENTO', 'CLIENTE', 'CANTIDAD'],
        'fields' => ['articulo', 'nro_documento', 'cliente', 'cantidad_movimiento'],
        'format' => ['text', 'text', 'text', 'number'],
        'totals' => true
    ],
    'DEVOLU' => [
        'title' => 'Devoluciones entre Fecha',
        'headers' => ['TIPO', 'FECHA', 'DOCUMENTO', 'CLIENTE', 'ARTICULO', 'CANTIDAD'],
        'fields' => ['tipo', 'fecha', 'nro_documento', 'nomcli', 'nomart', 'cantidad'],
        'format' => ['text', 'text', 'text', 'text', 'text', 'number'],
        'totals' => false
    ],
];
?>

<?php if (isset($report_config[$reporte_id])) :
    $config = $report_config[$reporte_id];
?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Reporte de <?= $config['title'] ?></h4>
        <button class="btn btn-success" onclick="js:window.location.href = 'listado_master_buscar_excel.php?id=<?= $reporte_id ?>&fd=<?= $fecha_desde ?>&fh=<?= $fecha_hasta ?>&tipo=<?= $tipo ?>&codigo=<?= $codigo ?>'">
            <i class="fas fa-file-excel"></i> Exportar a XLS
        </button>
    </div>

    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <?php foreach ($config['headers'] as $header) : ?>
                    <th scope="col"><?= htmlspecialchars($header) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $total_cantidad = 0;
            $total_costo = 0;
            $total_precio = 0;
            foreach ($data as $row) :
                $total_cantidad += $row['cantidad_movimiento'] ?? $row['cantidad'] ?? 0;
                $total_costo += $row['costo'] ?? 0;
                $total_precio += $row['precio'] ?? 0;
            ?>
                <tr>
                    <?php foreach ($config['fields'] as $index => $field) : ?>
                        <?php
                        $value = $row[$field] ?? '';
                        $format = $config['format'][$index];
                        if ($format == 'number') {
                            $value = number_format($value, 0, ",", ".");
                        } elseif ($format == 'number_2') {
                            $value = number_format($value, 2, ",", ".");
                        }
                        ?>
                        <td class="<?= in_array($format, ['number', 'number_2']) ? 'text-right' : '' ?>">
                            <?= htmlspecialchars($value) ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>

            <?php if ($config['totals']) : ?>
                <tr>
                    <?php if ($reporte_id == 'VENART') : ?>
                        <th colspan="4" class="text-right">Total (<?= count($data) ?> Registros)</th>
                        <th class="text-right"><?= number_format($total_cantidad, 0, ",", ".") ?></th>
                        <th></th>
                        <th class="text-right"><?= number_format($total_costo, 2, ",", ".") ?></th>
                        <th></th>
                        <th class="text-right"><?= number_format($total_precio, 2, ",", ".") ?></th>
                    <?php elseif ($reporte_id == 'SALART') : ?>
                        <th colspan="4" class="text-right">Total (<?= count($data) ?> Registros)</th>
                        <th class="text-right"></th>
                        <th class="text-right"><?= number_format($total_cantidad, 0, ",", ".") ?></th>
                    <?php elseif ($reporte_id == 'VENCLI') : ?>
                        <th colspan="3" class="text-right">Artículos (<?= count($data) ?> Registros)</th>
                        <th class="text-right"><?= number_format($total_cantidad, 0, ",", ".") ?></th>
                    <?php endif; ?>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
<?php endif; ?>