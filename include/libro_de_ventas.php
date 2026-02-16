<?php

// Conexión a la base de datos y otras variables
// ...

// Prepara la cláusula WHERE de forma segura para evitar inyecciones SQL
$where = "";
if (trim($tipo) != "") {
    $where .= " AND a.documento = '" . mysqli_real_escape_string($link, $tipo) . "'";
}
if (trim($cliente) != "") {
    $where .= " AND a.cliente = '" . mysqli_real_escape_string($link, $cliente) . "'";
}
if (trim($asesor) != "") {
    $where .= " AND c.asesor = '" . mysqli_real_escape_string($link, $asesor) . "'";
}

// Consulta principal para obtener los registros
$sql = "SELECT
            a.id,
            a.`tipo_documento`,
            IF(a.documento = 'NC', '', IF(a.documento = 'ND', CONCAT('ND-', REPLACE(a.nro_documento, 'ND-', '')), a.nro_documento)) AS nro_documento,
            IF(a.documento = 'NC', a.nro_documento, '') AS nota_credito,
            REPLACE(a.doc_afectado, 'FACT-', '') AS afectado,
            a.`documento`,
            a.`nro_control`,
            b.`nombre` AS cliente,
            b.`ci_rif`,
            DATE_FORMAT(a.`fecha`, '%d/%m/%Y') AS fecha,
            a.`total`,
            a.`iva`,
            a.`estatus`,
            a.descuento,
            c.nombre AS usuario,
            d.nombre AS asesor,
            -- Subconsultas optimizadas para obtener los valores exenta y gravable directamente
            (SELECT SUM(IF(IFNULL(alicuota, 0)=0, precio, 0)) FROM entradas_salidas WHERE id_documento = a.id AND tipo_documento = a.tipo_documento) AS exenta,
            (SELECT SUM(IF(alicuota>0, precio, 0)) FROM entradas_salidas WHERE id_documento = a.id AND tipo_documento = a.tipo_documento) AS gravable,
            (SELECT MAX(alicuota) FROM entradas_salidas WHERE id_documento = a.id AND tipo_documento = a.tipo_documento) AS alicuota_iva
        FROM
            salidas AS a
            LEFT OUTER JOIN cliente AS b ON b.id = a.cliente
            LEFT OUTER JOIN usuario AS c ON c.username = a.asesor
            LEFT OUTER JOIN asesor AS d ON d.id = c.asesor
        WHERE
            a.tipo_documento = 'TDCFCV'
            AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
            $where ORDER BY a.nro_control
        LIMIT 20;"; // Se usa LIMIT para obtener solo 20 registros
$rs = mysqli_query($link, $sql);

$registros = [];
while ($row = mysqli_fetch_assoc($rs)) {
    // Aquí se pueden procesar o formatear los datos si es necesario
    $row['total_formateado'] = number_format((trim($row["estatus"]) == "ANULADO" ? 0 : $row["total"]), 2, ".", ",");
    $desc = floatval($row["descuento"]);
    $row['exenta_formateado'] = number_format((trim($row["estatus"]) == "ANULADO" ? 0 : $row["exenta"] - ($row["exenta"] * ($desc / 100))), 2, ".", ",");
    $row['gravable_formateado'] = number_format((trim($row["estatus"]) == "ANULADO" ? 0 : $row["gravable"] - ($row["gravable"] * ($desc / 100))), 2, ".", ",");
    $row['iva_formateado'] = number_format((trim($row["estatus"]) == "ANULADO" ? 0 : $row["iva"]), 2, ".", ",");
    $registros[] = $row;
}

$total_registros = count($registros);

// Incluye la plantilla de la vista para la presentación
include 'reporte_ventas_template.php';

?>