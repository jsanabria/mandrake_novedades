<?php
include 'connect.php';

// 1. Captura de variables y normalización de fechas
$f_desde = $fecha_desde ?? '';
$f_hasta = $fecha_hasta ?? '';
$tipo    = $tipo ?? '';

// Función para asegurar formato YYYY-MM-DD
function limpiarFecha($f) {
    if (empty($f)) return date('Y-m-d');
    $f = str_replace('-', '/', $f);
    $p = explode("/", $f);
    return (count($p) === 3) ? "{$p[2]}-{$p[1]}-{$p[0]}" : $f;
}

// $f_desde = limpiarFecha($fecha_d);
// $f_hasta = limpiarFecha($fecha_h);

// 2. Consulta SQL
$where = "";
if (!empty($tipo)) {
    $tipo_esc = mysqli_real_escape_string($link, $tipo);
    $where = " AND d.codigo_ims IN ('$tipo_esc') ";
}

$sql = "SELECT 
            d.id,
            d.codigo_ims AS codigo, 
            TRIM(SUBSTRING(CONCAT(IFNULL(d.principio_activo, ''), ' ', IFNULL(d.presentacion, '')), 1, 32)) AS nombre, 
            SUM(ABS(b.cantidad_movimiento)) AS cantidad_movimiento, 
            d.ultimo_costo AS costo_unidad, 
            SUM(b.cantidad_articulo * d.ultimo_costo) AS costo_total, 
            (b.precio_unidad - (b.precio_unidad * (IFNULL(a.descuento, 0) / 100))) AS precio_unidad_neto, 
            SUM(IFNULL(b.precio, 0) - (IFNULL(b.precio, 0) * (IFNULL(a.descuento, 0) / 100))) AS precio_total_neto, 
            (( (b.precio_unidad - (b.precio_unidad * (IFNULL(a.descuento, 0) / 100))) - d.ultimo_costo ) / 
               NULLIF((b.precio_unidad - (b.precio_unidad * (IFNULL(a.descuento, 0) / 100))), 0) ) * 100 AS utilidad 
        FROM 
            salidas AS a 
            JOIN entradas_salidas AS b ON b.id_documento = a.id 
            LEFT JOIN articulo AS d ON d.id = b.articulo 
        WHERE 
            b.tipo_documento IN ('TDCNET') 
            AND a.estatus = 'PROCESADO' 
            AND IFNULL(a.pago_premio, 'N') = 'S' 
            AND a.fecha BETWEEN '$f_desde 00:00:00' AND '$f_hasta 23:59:59'
            $where 
        GROUP BY 
            d.id, d.codigo_ims, d.principio_activo, d.presentacion, d.ultimo_costo, b.precio_unidad, a.descuento 
        ORDER BY d.codigo_ims ASC"; 

$rs = mysqli_query($link, $sql);

// 3. Forzar descarga de Excel solo si hay datos o para evitar el archivo .php
$filename = "VENTAS_ARTICULO_" . date('Ymd') . ".xls";

header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
    /* mso-number-format:"\#\,\#\#0\.00" es una máscara especial:
       Le dice a Excel: "Usa el separador de miles y decimales que tenga configurado esta PC"
    */
    .num_contable { mso-number-format: "\#\,\#\#0\.00"; }
    .num_entero { mso-number-format: "0"; }
    /* Evita que códigos largos se conviertan a notación científica */
    .texto { mso-number-format: "\@"; } 
</style>

<table border="1">
    <thead>
        <tr style="background-color: #01579b; color: white; font-weight: bold;">
            <th>ID</th>
            <th>CODIGO</th>
            <th>ARTICULO</th>
            <th>CANT.</th>
            <th>COSTO UNIT.</th>
            <th>COSTO TOTAL</th>
            <th>PRECIO UNIT.</th>
            <th>PRECIO TOTAL</th>
            <th>UTILIDAD (%)</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $totalCant = 0; $totalCosto = 0; $totalPrecio = 0; $totalArt = 0;

        while ($row = mysqli_fetch_assoc($rs)) { 
            $totalArt++;
            $totalCant   += $row['cantidad_movimiento'];
            $totalCosto  += $row['costo_total'];
            $totalPrecio += $row['precio_total_neto'];

            /* CLAVE: No usamos number_format() aquí. 
               Enviamos el valor tal cual sale de la base de datos (ej: 150.50).
            */
            ?>
            <tr>
                <td class="num_entero"><?php echo $row['id']; ?></td>
                <td class="texto"><?php echo $row['codigo']; ?></td>
                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                <td class="num_entero"><?php echo $row['cantidad_movimiento']; ?></td>
                <td class="num_contable"><?php echo $row['costo_unidad']; ?></td>
                <td class="num_contable"><?php echo $row['costo_total']; ?></td>
                <td class="num_contable"><?php echo $row['precio_unidad_neto']; ?></td>
                <td class="num_contable"><?php echo $row['precio_total_neto']; ?></td>
                <td class="num_contable"><?php echo $row['utilidad']; ?></td>
            </tr>
        <?php } ?>
    </tbody>
    <tfoot>
        <tr style="font-weight: bold; background-color: #f2f2f2;">
            <td colspan="3" align="right">Artículos: <?php echo $totalArt; ?> - Total Unidades <?php echo $totalCant; ?></td>
            <td class="num_entero"><?php echo $totalCant; ?></td>
            <td></td>
            <td class="num_contable"><?php echo $totalCosto; ?></td>
            <td></td>
            <td class="num_contable"><?php echo $totalPrecio; ?></td>
            <td></td>
        </tr>
    </tfoot>
</table>
<?php exit(); ?>