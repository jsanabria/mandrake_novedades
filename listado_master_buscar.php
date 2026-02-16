<?php
session_start();

include 'include/connect.php';

// Mapeo de códigos a nombres de reportes para los archivos incluidos
// Este array es crucial para mantener la lógica clara y centralizada.
$reporte_archivos = [
    'CIMS' => 'include/clientes_ims.php',
    'AIMS' => 'include/articulos_ims.php',
    'FIMS' => 'include/facturas_ims.php',
    'LBCOMP' => 'include/libro_de_compra.php',
    'LBVENT' => 'include/libro_de_ventas.php',
    'VENFAB' => 'include/ventas_por_fabricante_rp.php',
    'VENART' => 'include/ventas_por_articulo.php',
    'VENCAN' => 'include/canjes_por_articulo.php',
    'VENARC' => 'include/ventas_por_articulo_cantidades.php',
    'SALGEN' => 'include/salidas_genreales_por_fabricante.php',
    'SALART' => 'include/salidas_genreales_por_articulo.php',
    'COMPRE' => 'include/clientes_con_compras_recientes.php',
    'COMSIN' => 'include/clientes_sin_compras_recientes.php',
    'FCCVSP' => 'include/factura_costo_vs_precio.php',
    'KARDEX' => 'include/kardex_de_inventario.php',
    'INVENT' => 'include/inventario_entre_fecha.php',
    'DEVOLU' => 'include/devoluciones_entre_fecha.php',
    'CONCLI' => 'include/consignacion_por_cliente.php',
    'FACCON' => 'include/facturas_por_consignacion.php',
    'VENCLI' => 'include/ventas_por_cliente.php',
    'DESCON' => 'include/descarga_entradas_consignacion.php',
    'REPX' => 'reportes/factura_fiscal_reportes.php',
    'REPZ' => 'reportes/factura_fiscal_reportes.php',
];

$id = $_GET["id"] ?? ''; // Usar null coalescing operator para evitar errores si 'id' no está definido
$fecha_desde = $_REQUEST["fecha_desde"];
$fecha_hasta = $_REQUEST["fecha_hasta"];
$tipo = $_REQUEST["tipo"] ?? "";
$cliente = $_REQUEST["cliente"] ?? "";
$asesor = $_REQUEST["asesor"] ?? "";

$out = '';
$sql_cia = "SELECT ci_rif, nombre FROM compania WHERE id = 1;";
$rs_cia = mysqli_query($link, $sql_cia);
$row_cia = mysqli_fetch_array($rs_cia);
$rif = $row_cia["ci_rif"];
$cia = $row_cia["nombre"];

$proveedor = intval($_REQUEST["proveedor"] ?? 0);

// Lógica de inclusión de archivos basada en el código del reporte
if (array_key_exists($id, $reporte_archivos)) {
    // Si el reporte es 'X' o 'Z', redirigimos.
    if ($id == 'REPX' || $id == 'REPZ') {
        $doc = ($id == 'REPX') ? 'RX' : 'RZ';
        header("Location: " . $reporte_archivos[$id] . "?documento=$doc&id=0&username=NA.NA");
        exit;
    }
    // Para cualquier otro reporte, incluimos el archivo de lógica
    include $reporte_archivos[$id];
} else {
    // Si el código no existe en el array, se muestra un error.
    die("El reporte no existe.");
}

// Botón de exportación, ahora usando los nuevos códigos y parámetros
$out .= '<button class="btn btn-primary" onclick="js:window.location.href = \'listado_master_buscar_excel.php?id=' . urlencode($id) . '&fd=' . urlencode($fecha_desde) . '&fh=' . urlencode($fecha_hasta) . '&tipo=' . urlencode($tipo) . '&cliente=' . urlencode($cliente) . '&asesor=' . urlencode($asesor) . '\'">Exportar a TXT/XLS</button>';

echo $out;
?>
