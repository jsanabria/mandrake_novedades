<?php 
header('Content-type: application/json; charset=utf-8');

// Validación de base de datos
if(isset($_GET['dbName']))  
    $strcon = trim($_GET['dbName']); 
else 
    die(json_encode(["error" => "No Database"]));

$codigo = trim($_GET['codigo'] ?? ''); 
$mes = str_pad(trim($_GET['mes'] ?? '01'), 2, "0", STR_PAD_LEFT);  
$anho = trim($_GET['anho'] ?? date("Y")); 

$fecha_inicio = "$anho-$mes-01";
$fecha_fin = date("Y-m-d");

if(isset($_GET['user']) && intval($_GET['user']) == 365) { 
    if(isset($_GET['app'])) {
        include("connect.php"); // Incluimos la conexión una sola vez aquí
        $app = trim($_GET['app']);

        switch($app) { 
            case "tasa_usd":
                $sql = "SELECT tasa FROM tasa_usd ORDER BY id DESC LIMIT 0, 1;";
                $rs = mysqli_query($link, $sql);
                $listaTasa = [];
                while($row = mysqli_fetch_assoc($rs)) {
                    $Tasa = new stdClass();
                    $Tasa->tasa_dia = $row["tasa"];
                    $listaTasa[] = $Tasa;
                }
                echo json_encode(["listaTasa" => $listaTasa], JSON_UNESCAPED_UNICODE);
                break;

            case "ventas_diarias":
                $sql = "SELECT DATE_FORMAT(a.fecha, '%Y-%m-%d') AS fecha, a.moneda, 
                               SUM(IFNULL(b.costo, 0)) AS costo, 
                               SUM(IFNULL(b.precio, 0)-(IFNULL(b.precio, 0)*(IFNULL(a.descuento, 0)/100))) AS precio 
                        FROM salidas AS a 
                        JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento = a.id 
                        WHERE a.tipo_documento = 'TDCNET' AND IFNULL(a.pago_premio, 'N') = 'N' 
                        AND a.fecha BETWEEN '$fecha_inicio 00:00:00' AND '$fecha_fin 23:59:59' 
                        AND a.estatus = 'PROCESADO' 
                        GROUP BY DATE_FORMAT(a.fecha, '%Y-%m-%d'), a.moneda 
                        ORDER BY fecha ASC;"; 
                
                $rs = mysqli_query($link, $sql);
                $listaVentasDiarias = [];
                while($row = mysqli_fetch_assoc($rs)) {
                    $item = new stdClass();
                    $item->fecha = $row["fecha"];
                    $item->moneda = $row["moneda"];
                    $item->costo = $row["costo"];
                    $item->precio = $row["precio"];
                    
                    // Sub-consultas optimizadas
                    $rs2 = mysqli_query($link, "SELECT COUNT(id) as docs FROM salidas WHERE tipo_documento = 'TDCNET' AND DATE_FORMAT(fecha, '%Y-%m-%d') = '{$row["fecha"]}' AND estatus = 'PROCESADO'");
                    $item->documentos = mysqli_fetch_assoc($rs2)["docs"] ?? 0;

                    $rs3 = mysqli_query($link, "SELECT ABS(SUM(y.cantidad_movimiento)) as arts FROM salidas x JOIN entradas_salidas y ON y.tipo_documento = x.tipo_documento AND y.id_documento = x.id WHERE x.tipo_documento = 'TDCNET' AND DATE_FORMAT(x.fecha, '%Y-%m-%d') = '{$row["fecha"]}' AND x.estatus = 'PROCESADO'");
                    $item->articulos = mysqli_fetch_assoc($rs3)["arts"] ?? 0;

                    $rs4 = mysqli_query($link, "SELECT tasa_usd FROM cobros_cliente_detalle bb JOIN cobros_cliente aa ON bb.cobros_cliente = aa.id WHERE DATE_FORMAT(aa.fecha, '%Y-%m-%d') = '{$row["fecha"]}' UNION SELECT tasa_usd FROM recarga WHERE DATE_FORMAT(fecha, '%Y-%m-%d') = '{$row["fecha"]}' LIMIT 1");
                    $item->tasa = mysqli_fetch_assoc($rs4)["tasa_usd"] ?? 0;
                    
                    $item->tienda = $codigo;
                    $item->id = null;
                    $listaVentasDiarias[] = $item;
                }
                echo json_encode(["listaVentasDiarias" => $listaVentasDiarias], JSON_UNESCAPED_UNICODE);
                break;

            case "canje_premios":
                $sql = "SELECT DATE_FORMAT(a.fecha, '%Y-%m-%d') AS fecha, a.moneda, 
                               SUM(IFNULL(b.costo, 0)) AS costo, 
                               SUM(IFNULL(b.precio, 0)-(IFNULL(b.precio, 0)*(IFNULL(a.descuento, 0)/100))) AS precio 
                        FROM salidas AS a 
                        JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento = a.id 
                        WHERE a.tipo_documento = 'TDCNET' AND IFNULL(a.pago_premio, 'N') = 'S' 
                        AND a.fecha BETWEEN '$fecha_inicio 00:00:00' AND '$fecha_fin 23:59:59' 
                        AND a.estatus = 'PROCESADO' 
                        GROUP BY DATE_FORMAT(a.fecha, '%Y-%m-%d'), a.moneda;";
                
                $rs = mysqli_query($link, $sql);
                $listaCanjePremios = [];
                while($row = mysqli_fetch_assoc($rs)) {
                    $item = new stdClass();
                    $item->fecha = $row["fecha"];
                    $item->moneda = $row["moneda"];
                    $item->costo = $row["costo"];
                    $item->precio = $row["precio"];
                    
                    $rs2 = mysqli_query($link, "SELECT COUNT(id) as docs FROM salidas WHERE tipo_documento = 'TDCNET' AND IFNULL(pago_premio, 'N') = 'S' AND DATE_FORMAT(fecha, '%Y-%m-%d') = '{$row["fecha"]}' AND estatus = 'PROCESADO'");
                    $item->documentos = mysqli_fetch_assoc($rs2)["docs"] ?? 0;

                    $rs3 = mysqli_query($link, "SELECT ABS(SUM(y.cantidad_movimiento)) as arts FROM salidas x JOIN entradas_salidas y ON y.tipo_documento = x.tipo_documento AND y.id_documento = x.id WHERE x.tipo_documento = 'TDCNET' AND IFNULL(x.pago_premio, 'N') = 'S' AND DATE_FORMAT(x.fecha, '%Y-%m-%d') = '{$row["fecha"]}' AND x.estatus = 'PROCESADO'");
                    $item->articulos = mysqli_fetch_assoc($rs3)["arts"] ?? 0;

                    $rs4 = mysqli_query($link, "SELECT tasa_usd FROM cobros_cliente_detalle bb JOIN cobros_cliente aa ON bb.cobros_cliente = aa.id WHERE DATE_FORMAT(aa.fecha, '%Y-%m-%d') = '{$row["fecha"]}' LIMIT 1");
                    $item->tasa = mysqli_fetch_assoc($rs4)["tasa_usd"] ?? 0;

                    $item->tienda = $codigo;
                    $item->id = null;
                    $listaCanjePremios[] = $item;
                }
                echo json_encode(["listaCanjePremios" => $listaCanjePremios], JSON_UNESCAPED_UNICODE);
                break;

            case "tipo_pago":
                $sql = "SELECT fecha, metodo_pago, SUM(monto_bs) AS monto_bs, SUM(monto_usd) AS monto_usd 
                        FROM (
                            SELECT b.fecha, CONCAT(IFNULL(param.valor2, 'OTRO'), ' - ', a.moneda) AS metodo_pago, a.monto_bs, a.monto_usd
                            FROM cobros_cliente_detalle AS a 
                            JOIN cobros_cliente AS b ON b.id = a.cobros_cliente 
                            LEFT OUTER JOIN salidas AS c ON c.id = b.id_documento 
                            LEFT OUTER JOIN parametro AS param ON param.valor1 = a.metodo_pago AND param.codigo = '009'
                            WHERE a.metodo_pago NOT IN ('RC', 'RD', 'PF', 'PC', 'DV', 'NC', 'ND', 'SF', 'GN') 
                            AND b.fecha BETWEEN '$fecha_inicio 00:00:00' AND '$fecha_fin 23:59:59' 
                            AND c.estatus = 'PROCESADO' AND IFNULL(c.pago_premio, 'N') = 'N'
                            UNION ALL 
                            SELECT a.fecha, CONCAT(IFNULL(param.valor2, 'RECIBO'), ' - ', a.moneda), a.monto_bs, a.monto_usd
                            FROM recarga AS a 
                            LEFT OUTER JOIN parametro AS param ON param.valor1 = a.metodo_pago AND param.codigo = '009'
                            WHERE a.metodo_pago NOT IN ('RC', 'RD', 'PF', 'PC', 'DV', 'NC', 'ND', 'SF', 'GN') 
                            AND a.fecha BETWEEN '$fecha_inicio 00:00:00' AND '$fecha_fin 23:59:59' 
                            AND (a.monto_usd > 0 OR a.reverso = 'S')
                        ) AS combined GROUP BY fecha, metodo_pago";
                $rs = mysqli_query($link, $sql);
                $listaTipoPago = [];
                while($row = mysqli_fetch_assoc($rs)) {
                    $row['tienda'] = $codigo;
                    $row['id'] = null;
                    $listaTipoPago[] = $row;
                }
                echo json_encode(["listaTipoPago" => $listaTipoPago], JSON_UNESCAPED_UNICODE);
                break;

            case "ventas_articulo":
                $sql = "SELECT DATE_FORMAT(a.fecha, '%Y-%m-%d') AS fecha, c.codigo_ims, SUM(b.cantidad_articulo) AS cantidad_articulo, (SUM(IFNULL(b.costo, 0))/SUM(b.cantidad_articulo)) AS costo, SUM(IFNULL(b.precio, 0)-(IFNULL(b.precio, 0)*(IFNULL(a.descuento, 0)/100))) AS precio 
                        FROM salidas AS a JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento = a.id LEFT OUTER JOIN articulo AS c ON c.id = b.articulo 
                        WHERE a.tipo_documento = 'TDCNET' AND IFNULL(a.pago_premio, 'N') = 'N' AND a.fecha BETWEEN '$fecha_inicio 00:00:00' AND '$fecha_fin 23:59:59' AND a.estatus = 'PROCESADO' 
                        GROUP BY DATE_FORMAT(a.fecha, '%Y-%m-%d'), c.codigo_ims";
                $rs = mysqli_query($link, $sql);
                $lista = [];
                while($row = mysqli_fetch_assoc($rs)) {
                    $row['tienda'] = $codigo;
                    $row['id'] = null;
                    $lista[] = $row;
                }
                echo json_encode(["listaVentasArticulos" => $lista], JSON_UNESCAPED_UNICODE);
                break;

            case "inventario":
                // Mantenemos tu consulta de inventario compleja pero aseguramos el JSON
                $sql = "SELECT CURDATE() AS fecha, art.id, art.codigo, art.codigo_ims, art.nombre AS fabricante, 'UNIDAD' AS unidad_medida, IFNULL(dev.cantidad, 0) AS devoluciones, IFNULL(ent.cantidad, 0) AS entradas, ABS(IFNULL(sal.cantidad, 0)) AS salidas, (IFNULL(ent.cantidad, 0) - ABS(IFNULL(sal.cantidad, 0))) AS existencia, (SELECT ultimo_costo FROM articulo WHERE codigo_ims = art.codigo_ims LIMIT 1) AS costo_unidad, (SELECT precio FROM articulo WHERE codigo_ims = art.codigo_ims LIMIT 1) AS precio_unidad FROM (SELECT a.id, a.codigo, a.codigo_ims, b.nombre FROM articulo AS a LEFT OUTER JOIN fabricante AS b ON b.Id = a.fabricante) AS art LEFT OUTER JOIN (SELECT a.articulo, SUM(a.cantidad_movimiento) AS cantidad FROM entradas_salidas AS a JOIN salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id = a.id_documento JOIN almacen AS c ON c.codigo = a.almacen AND c.movimiento = 'S' WHERE a.tipo_documento IN ('TDCNET', 'TDCASA') AND b.estatus <> 'ANULADO' AND b.activo = 'S' GROUP BY a.articulo) AS sal ON sal.articulo = art.Id LEFT OUTER JOIN (SELECT a.articulo, SUM(a.cantidad_movimiento) AS cantidad FROM entradas_salidas AS a JOIN entradas AS b ON b.tipo_documento = a.tipo_documento AND b.id = a.id_documento JOIN almacen AS c ON c.codigo = a.almacen AND c.movimiento = 'S' WHERE a.tipo_documento IN ('TDCNRP', 'TDCAEN') AND b.estatus <> 'ANULADO' GROUP BY a.articulo) AS ent ON ent.articulo = art.Id LEFT OUTER JOIN (SELECT a.articulo, SUM(a.cantidad_movimiento) AS cantidad FROM entradas_salidas AS a JOIN entradas AS b ON b.tipo_documento = a.tipo_documento AND b.id = a.id_documento JOIN almacen AS c ON c.codigo = a.almacen AND c.movimiento = 'S' WHERE a.tipo_documento IN ('TDCNRP') AND IFNULL(b.nota, '') = 'DEVOLUCION DE ARTICULO' GROUP BY a.articulo) AS dev ON dev.articulo = art.Id ORDER BY art.codigo_ims ASC";
                $rs = mysqli_query($link, $sql);
                $lista = [];
                while($row = mysqli_fetch_assoc($rs)) {
                    $row['tienda'] = $codigo;
                    $row['id'] = null;
                    $lista[] = $row;
                }
                echo json_encode(["listaInventario" => $lista], JSON_UNESCAPED_UNICODE);
                break;

            case "gastos":
                $sql = "SELECT a.fecha, b.nombre AS proveedor, a.documento, a.descripcion, a.monto_exento, a.monto_gravado, a.alicuota, a.monto_iva, a.monto_total 
                        FROM compra AS a LEFT OUTER JOIN proveedor AS b on b.id = a.proveedor 
                        WHERE a.anulado = 'N' AND a.fecha BETWEEN '$fecha_inicio 00:00:00' AND '$fecha_fin 23:59:59'";
                $rs = mysqli_query($link, $sql);
                $lista = [];
                while($row = mysqli_fetch_assoc($rs)) {
                    $row['tienda'] = $codigo;
                    $row['id'] = null;
                    $lista[] = $row;
                }
                echo json_encode(["gastos" => $lista], JSON_UNESCAPED_UNICODE);
                break;
        }
        mysqli_close($link);
    }
}
?>