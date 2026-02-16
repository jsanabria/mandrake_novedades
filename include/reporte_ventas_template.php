<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Libro de Ventas</h4>
    <div>
        <a href="reportes/libro_de_ventas_fiscal.php?xfecha=<?= $fecha_desde ?>&yfecha=<?= $fecha_hasta ?>" class="btn btn-info me-2" target="_blank">
            <i class="fas fa-print"></i> Imprimir Libro de Ventas Fiscal
        </a>
        <a href="reportes/libro_de_ventas.php?xfecha=<?= $fecha_desde ?>&yfecha=<?= $fecha_hasta ?>" class="btn btn-info me-2" target="_blank">
            <i class="fas fa-print"></i> Imprimir Libro de Ventas
        </a>
        <a href="listado_master_buscar_excel.php?id=<?= $id ?>&fd=<?= $fecha_desde ?>&fh=<?= $fecha_hasta ?>&tipo=<?= $tipo ?>" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Exportar
        </a>
    </div>
</div>

<table class="table table-hover table-bordered">
    <thead>
        <tr>
            <th scope="col">FACTURA</th>
            <th scope="col">NOTA CREDITO</th>
            <th scope="col">NRO CONTROL</th>
            <th scope="col">FECHA</th>
            <th scope="col">NOMBRE O RAZON SOCIAL</th>
            <th scope="col">RIF NRO</th>
            <th scope="col">TOTAL VENTAS</th>
            <th scope="col">VENTAS EXENTAS</th>
            <th scope="col">BASE</th>
            <th scope="col">%</th>
            <th scope="col">IMPUESTO</th>
            <th scope="col">IVA RETENIDO 75%</th>
            <th scope="col">ASESOR</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($registros as $row) : ?>
            <tr class="<?= trim($row['estatus']) == 'ANULADO' ? 'text-muted table-danger' : '' ?>">
                <td><?= htmlspecialchars($row["nro_documento"]) ?></td>
                <td><?= htmlspecialchars($row["nota_credito"]) ?></td>
                <td><?= htmlspecialchars($row["nro_control"]) ?></td>
                <td><?= htmlspecialchars($row["fecha"]) ?></td>
                <td><?= htmlspecialchars(trim($row["estatus"]) == "ANULADO" ? "ANULADA" : $row["cliente"]) ?></td>
                <td><?= htmlspecialchars(trim($row["estatus"]) == "ANULADO" ? "" : $row["ci_rif"]) ?></td>
                <td><?= htmlspecialchars($row["total_formateado"]) ?></td>
                <td><?= htmlspecialchars($row["exenta_formateado"]) ?></td>
                <td><?= htmlspecialchars($row["gravable_formateado"]) ?></td>
                <td><?= htmlspecialchars($row["alicuota_iva"]) ?></td>
                <td><?= htmlspecialchars($row["iva_formateado"]) ?></td>
                <td>0.00</td>
                <td><?= htmlspecialchars($row["usuario"]) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="d-flex justify-content-between align-items-center mt-3">
    <div>
        <a href="reportes/libro_de_ventas_fiscal.php?xfecha=<?= $fecha_desde ?>&yfecha=<?= $fecha_hasta ?>" class="btn btn-info me-2" target="_blank">
            <i class="fas fa-print"></i> Imprimir Libro de ventas Fiscal
        </a>
        <a href="reportes/libro_de_ventas.php?xfecha=<?= $fecha_desde ?>&yfecha=<?= $fecha_hasta ?>" class="btn btn-info me-2" target="_blank">
            <i class="fas fa-print"></i> Imprimir Libro de ventas
        </a>
    </div>
    <span class="text-muted">Items: <?= number_format($total_registros, 0, "", ".") ?></span>
</div>