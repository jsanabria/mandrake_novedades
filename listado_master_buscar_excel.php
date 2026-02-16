<?php
session_start();

include 'include/connect.php';

// Map the report codes to the corresponding excel files.
$report_files_map = [
    'CIMS'   => 'include/clientes_ims_excel.php',
    'AIMS'   => 'include/articulos_ims_excel.php',
    'FIMS'   => 'include/facturas_ims_excel.php',
    'LBCOMP' => 'include/libro_de_compra_excel.php',
    'LBVENT' => 'include/libro_de_ventas_excel.php',
    'VENFAB' => 'include/ventas_por_fabricante_rp_excel.php',
    'VENART' => 'include/ventas_por_articulo_excel.php',
    'VENCAN' => 'include/canjes_por_articulo_excel.php',
    'VENARC' => 'include/ventas_por_articulo_cantidades_excel.php',
    'SALGEN' => 'include/salidas_genreales_por_fabricante_excel.php',
    'SALART' => 'include/salidas_genreales_por_articulo_excel.php',
    'COMPRE' => 'include/clientes_con_compras_recientes_excel.php',
    'COMSIN' => 'include/clientes_sin_compras_recientes_excel.php',
    'FCCVSP' => 'include/factura_costo_vs_precio_excel.php',
    'KARDEX' => 'include/kardex_de_inventario_excel.php',
    'INVENT' => 'include/inventario_entre_fecha_excel.php',
    'DEVOLU' => 'include/devoluciones_entre_fecha_excel.php',
    'CONCLI' => 'include/consignacion_por_cliente_excel.php',
    'FACCON' => 'include/facturas_por_consignacion_excel.php',
    'VENCLI' => 'include/ventas_por_cliente_excel.php',
    'DESCON' => 'include/descarga_entradas_consignacion_excel.php',
    // REPX and REPZ are not included here as they are handled in the main report page.
];

// Use the short code from the URL
$id = $_GET["id"] ?? '';
$fecha_desde = $_REQUEST["fd"] ?? '';
$fecha_hasta = $_REQUEST["fh"] ?? '';
$tipo = $_REQUEST["tipo"] ?? "";
$cliente = $_REQUEST["cliente"] ?? "";
$asesor = $_REQUEST["asesor"] ?? "";

$where = ""; // This variable will be defined in the included scripts if needed
$excel = true; // Default behavior is Excel export

if (array_key_exists($id, $report_files_map)) {
    // Include the correct script based on the ID.
    include $report_files_map[$id];
} else {
    // If the code doesn't exist, show an error.
    die("Report does not exist...");
}

// Set the correct headers for the Excel file.
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$filename");

// The variable $developer_records and $filename are expected to be set by the included script.
// If not set, you should handle the error gracefully here.
if (isset($developer_records)) {
    $show_coloumn = false;
    foreach($developer_records as $record) {
        if(!$show_coloumn) {
            // Display field/column names in the first row.
            echo implode("\t", array_keys($record)) . "\n";
            $show_coloumn = true;
        }
        // Output the data for each row.
        echo implode("\t", array_values($record)) . "\n";
    }
} else {
    echo "No data available for export.";
}

exit;

?>
