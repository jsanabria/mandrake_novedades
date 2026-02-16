<?php
// 1. Configuración de cabeceras
header('Content-type: application/vnd.ms-excel; charset=utf-8');
header("Content-Disposition: attachment; filename=IngresoCaja_" . date('Y-m-d') . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

include 'connect.php';

// 2. Validación de fechas
$fecha_d = $_REQUEST["xfecha"] ?? '';
$fecha_h = $_REQUEST["yfecha"] ?? '';

function normalizarFecha($fechaInput) {
    if (empty($fechaInput)) return date('Y-m-d');
    $fechaLimpia = str_replace('-', '/', $fechaInput);
    $partes = explode("/", $fechaLimpia);
    return (count($partes) === 3) ? "{$partes[2]}-{$partes[1]}-{$partes[0]}" : $fechaInput;
}

$fecha_desde = normalizarFecha($fecha_d) . " 00:00:00";
$fecha_hasta = normalizarFecha($fecha_h) . " 23:59:59";

// 3. Consulta SQL corregida
// Asegúrate de que los alias coincidan en ambos lados del UNION
$query = "SELECT 
            DATE_FORMAT(aa.fecha, '%Y/%m/%d') AS fecha_formateada, 
            CONCAT(bb.valor2, ' - ', aa.moneda) AS metodo_pago, 
            SUM(aa.monto_bs) AS monto_bs, 
            SUM(aa.monto_usd) AS monto_usd 
        FROM (
            SELECT 
                a.metodo_pago, a.moneda, a.monto_bs, a.monto_usd, c.fecha 
            FROM cobros_cliente_detalle AS a 
            JOIN cobros_cliente AS b ON b.id = a.cobros_cliente 
            LEFT JOIN salidas AS c ON c.id = b.id_documento 
            WHERE a.metodo_pago NOT IN ('RC', 'PF', 'PC', 'DV', 'NC', 'ND') 
              AND b.fecha BETWEEN ? AND ? 
              AND c.estatus = 'PROCESADO' AND IFNULL(c.pago_premio, 'N') = 'N' 
            
            UNION ALL 
            
            SELECT 
                a.metodo_pago, a.moneda, a.monto_bs, a.monto_usd, a.fecha 
            FROM recarga AS a 
            WHERE a.metodo_pago NOT IN ('RC', 'PF', 'PC', 'DV', 'NC', 'ND') 
              AND a.fecha BETWEEN ? AND ? 
              AND (a.monto_usd > 0 OR a.reverso = 'S')
        ) AS aa 
        LEFT JOIN parametro AS bb ON bb.valor1 = aa.metodo_pago 
        WHERE bb.codigo = '009' 
        GROUP BY 
            DATE_FORMAT(aa.fecha, '%Y/%m/%d'), 
            bb.valor2, 
            aa.moneda
        ORDER BY fecha_formateada ASC";

$stmt = mysqli_prepare($link, $query);

// --- DIAGNÓSTICO DE ERRORES ---
if (!$stmt) {
    die("Error en la preparación de la consulta: " . mysqli_error($link));
}
// ------------------------------

mysqli_stmt_bind_param($stmt, "ssss", $fecha_desde, $fecha_hasta, $fecha_desde, $fecha_hasta);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    die("Error al obtener resultados: " . mysqli_error($link));
}
?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<table border="1">
    <thead>
        <tr><th colspan="4">INGRESO DE CAJA (<?php echo $fecha_d; ?> al <?php echo $fecha_h; ?>)</th></tr>
        <tr style="background-color: #007bff; color: white;">
            <th>FECHA</th>
            <th>TIPO DE PAGO</th>
            <th>MONTO Bs.</th>
            <th>MONTO USD</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $totBs = 0; $totUsd = 0;
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)):
                $totBs += $row['monto_bs'];
                $totUsd += $row['monto_usd'];
                ?>
                <tr>
                    <td align="center"><?php echo $row['fecha_formateada']; ?></td>
                    <td><?php echo $row['metodo_pago']; ?></td>
                    <td><?php echo number_format($row['monto_bs'], 2, ',', '.'); ?></td>
                    <td><?php echo number_format($row['monto_usd'], 2, ',', '.'); ?></td>
                </tr>
                <?php 
            endwhile; 
        } else {
            echo "<tr><td colspan='4'>No hay datos en este rango de fechas</td></tr>";
        }
        ?>
    </tbody>
    <tfoot>
        <tr style="font-weight: bold; background-color: #f8f9fa;">
            <td colspan="2" align="right">TOTALES:</td>
            <td><?php echo number_format($totBs, 2, ',', '.'); ?></td>
            <td><?php echo number_format($totUsd, 2, ',', '.'); ?></td>
        </tr>
    </tfoot>
</table>