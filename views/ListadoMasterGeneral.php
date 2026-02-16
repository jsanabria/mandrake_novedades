<?php

namespace PHPMaker2021\mandrake;

// Page object
$ListadoMasterGeneral = &$Page;
?>
<?php
// Incluye la conexión y otros archivos necesarios.
// session_start();
// include 'include/connect.php';

// Obtiene los parámetros de la URL
$reporte_id = $_GET["id"] ?? '';
$codigo = $_GET["codigo"] ?? '';
$fecha_desde = $_REQUEST["fecha_desde"] ?? '';
$fecha_hasta = $_REQUEST["fecha_hasta"] ?? '';

// Array de datos para la vista
$data = [];
$filename = '';

// Lógica para construir la consulta y obtener los datos
switch ($reporte_id) {
    case 'VENART': // Ventas por Articulo
        $where = ($codigo != "") ? "AND d.id IN ($codigo)" : "";
        $sql = "SELECT
                    d.id, DATE_FORMAT(a.fecha, '%d/%m/%Y') AS fecha,
                    CONCAT(IFNULL(d.principio_activo, ' '), ' ', IFNULL(d.presentacion, ' ')) AS articulo,
                    g.id AS codigo_cliente, a.nro_documento,
                    g.nombre AS cliente,
                    SUM(ABS(b.cantidad_movimiento)) AS cantidad_movimiento,
                    b.costo_unidad, SUM(b.costo) AS costo, b.precio_unidad, SUM(b.precio) AS precio
                FROM
                    salidas AS a
                    JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento= a.id
                    LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante
                    LEFT OUTER JOIN articulo AS d ON d.id = b.articulo
                    LEFT OUTER JOIN cliente AS g ON g.id = a.cliente
                WHERE
                    a.tipo_documento = 'TDCNET' AND a.estatus = 'PROCESADO'
                    AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
                    $where
                GROUP BY d.id, DATE_FORMAT(a.fecha, '%d/%m/%Y'), g.id, a.nro_documento, g.nombre, b.costo_unidad, b.precio_unidad
                ORDER BY cantidad_movimiento DESC;";
        $data = ExecuteRows($sql);
        break;

    case 'SALART': // Salidas Generales por Articulo
        $where = ($codigo != "") ? "AND d.id = '$codigo'" : "";
        $sql = "SELECT
                    a.id,
                    a.nro_documento,
                    date_format(a.fecha, '%d/%m/%Y') AS fecha,
                    g.id AS codigo_cliente,
                    g.nombre AS cliente,
                    SUM(ABS(b.cantidad_movimiento)) AS cantidad_movimiento,
                    (SELECT descripcion FROM tipo_documento WHERE codigo = a.tipo_documento) AS tipo,
                    CONCAT(IFNULL(d.nombre_comercial, ''), ' ', IFNULL(d.principio_activo, ''), ' ', IFNULL(d.presentacion, '')) AS articulo
                FROM
                    salidas AS a
                    JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento= a.id
                    LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante
                    LEFT OUTER JOIN articulo AS d ON d.id = b.articulo
                    LEFT OUTER JOIN cliente AS g ON g.id = a.cliente
                WHERE
                    ((a.tipo_documento = 'TDCNET' AND a.estatus = 'PROCESADO') OR (a.tipo_documento = 'TDCASA' AND a.estatus = 'PROCESADO'))
                    AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
                    $where
                GROUP BY a.id, a.nro_documento, a.fecha, g.id, g.nombre
                ORDER BY cantidad_movimiento DESC;";
        $data = ExecuteRows($sql);
        break;

    case 'VENCLI': // Ventas por Cliente
        $where = ($codigo != "") ? "AND g.id IN ($codigo)" : "";
        if ($fecha_desde == "" || $fecha_hasta == "") {
            $sql = "SELECT
                        d.id,
                        CONCAT(IFNULL(d.principio_activo, ' '), ' ', IFNULL(d.presentacion, ' ')) AS articulo,
                        g.id AS codigo_cliente, a.nro_documento,
                        g.nombre AS cliente,
                        SUM(ABS(b.cantidad_movimiento)) AS cantidad_movimiento
                    FROM
                        salidas AS a
                        JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento= a.id
                        LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante
                        LEFT OUTER JOIN articulo AS d ON d.id = b.articulo
                        LEFT OUTER JOIN cliente AS g ON g.id = a.cliente
                    WHERE
                        a.tipo_documento = 'TDCNET' AND a.estatus = 'PROCESADO'
                        $where
                    GROUP BY d.id, g.id, a.nro_documento, g.nombre
                    ORDER BY a.nro_documento DESC, articulo;";
        } else {
            $sql = "SELECT
                        d.id,
                        CONCAT(IFNULL(d.principio_activo, ' '), ' ', IFNULL(d.presentacion, ' ')) AS articulo,
                        g.id AS codigo_cliente, a.nro_documento,
                        g.nombre AS cliente,
                        SUM(ABS(b.cantidad_movimiento)) AS cantidad_movimiento
                    FROM
                        salidas AS a
                        JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento= a.id
                        LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante
                        LEFT OUTER JOIN articulo AS d ON d.id = b.articulo
                        LEFT OUTER JOIN cliente AS g ON g.id = a.cliente
                    WHERE
                        a.tipo_documento = 'TDCNET' AND a.estatus = 'PROCESADO'
                        AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
                        $where
                    GROUP BY d.id, g.id, a.nro_documento, g.nombre
                    ORDER BY a.nro_documento, articulo;";
        }
        $data = ExecuteRows($sql);
        break;

    case 'DEVOLU': // Devoluciones entre fecha
        $where = ($codigo != "") ? "AND d.id = '$codigo'" : "";
        $sql = "SELECT
                    b.nro_documento, DATE_FORMAT(b.fecha, '%d/%m/%Y') AS fecha,
                    f.nombre AS nomcli, d.principio_activo AS nomart,
                    b.cliente, a.articulo, a.cantidad_movimiento AS cantidad
                FROM
                    entradas_salidas AS a
                    JOIN entradas AS b ON b.tipo_documento = a.tipo_documento AND b.id = a.id_documento
                    JOIN almacen AS c ON c.codigo = a.almacen AND c.movimiento = 'S'
                    JOIN articulo AS d ON d.id = a.articulo
                    JOIN cliente AS f ON f.id = b.cliente
                WHERE
                    ((a.tipo_documento IN ('TDCNRP', 'TDCAEN') AND b.estatus = 'PROCESADO') OR
                    (a.tipo_documento IN ('TDCNRP', 'TDCAEN') AND b.estatus <> 'ANULADO') AND b.consignacion = 'S')
                    AND b.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
                    AND IFNULL(b.nota, '') = 'DEVOLUCION DE ARTICULO' AND IFNULL(cliente, 0) > 0
                    $where
                ORDER BY nomcli;";
        $data = ExecuteRows($sql);
        break;

    default:
        die("El reporte no existe...");
}

// Incluye la plantilla de la vista
include 'include/reporte_articulos_template.php';
?>

<?= GetDebugMessage() ?>
