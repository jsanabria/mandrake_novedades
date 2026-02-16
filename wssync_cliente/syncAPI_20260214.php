<?php 
header('Content-type: application/json; charset=utf-8');

if(isset($_GET['dbName']))  
	$strcon = trim($_GET['dbName']); 
else 
	die("No Database");

$codigo = trim($_GET['codigo']); 

$mes = str_pad(trim($_GET['mes']), 2, "0", STR_PAD_LEFT);  
$anho = trim($_GET['anho']); 

$fecha_inicio = "$anho-$mes-01";
$fecha_fin = date("Y-m-d");

if(isset($_GET['user']) && intval($_GET['user'])) { 
	if(intval($_GET['user']) == 365) {
		if(isset($_GET['app'])) {
			switch(trim($_GET['app'])) { 
			case "tasa_usd":
				include("connect.php");

				$sql = "SELECT tasa FROM tasa_usd ORDER BY id DESC LIMIT 0, 1;";
				$rs = mysqli_query($link, $sql);

				$objTasa = new stdClass();
				$listaTasa = [];

				while($row = mysqli_fetch_array($rs)) {
					$Tasa = new stdClass();

					$Tasa->tasa_dia = $row["tasa"];

					$listaTasa[] = $Tasa;
				}

				mysqli_close($link);

				$objTasa->listaTasa = $listaTasa;
				echo json_encode($objTasa, JSON_UNESCAPED_UNICODE);


				break;
			case "ventas_diarias":
				include("connect.php");

				$sql = "SELECT 
							hd.fecha, hd.moneda, hd.costo, hd.precio,  
							1 AS documentos, 
							1 AS articulos, 
							1 AS tasa 
						FROM 
							(
							SELECT 
								DATE_FORMAT(a.fecha, '%Y-%m-%d') AS fecha, 
								a.moneda, a.tipo_documento,   
								SUM(IFNULL(b.costo, 0)) AS costo, 
								-- SUM(IFNULL(b.precio, 0)) AS precio 
								SUM(IFNULL(b.precio, 0)-(IFNULL(b.precio, 0)*(IFNULL(a.descuento, 0)/100))) AS precio 
							FROM 
								salidas AS a 
								JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento 
									AND b.id_documento = a.id 
								LEFT OUTER JOIN cliente AS c ON c.id = a.cliente 
								LEFT OUTER JOIN asesor AS d ON d.ci_rif = a.asesor 
							WHERE 
								a.tipo_documento = 'TDCNET' 
								AND a.fecha BETWEEN '$fecha_inicio 00:00:00' AND '$fecha_fin 23:59:59' 
								AND a.estatus = 'PROCESADO' 
							GROUP BY 
								date_format(a.fecha, '%Y-%m-%d'), a.moneda   
							) AS hd 
						ORDER BY hd.fecha ASC;"; 
				$rs = mysqli_query($link, $sql);

				$objVentasDiarias = new stdClass();
				$listaVentasDiarias = [];

				while($row = mysqli_fetch_array($rs)) {
					$VentasDiarias = new stdClass();

					$VentasDiarias->fecha = $row["fecha"];
					$VentasDiarias->moneda = $row["moneda"];
					$VentasDiarias->costo = $row["costo"];
					$VentasDiarias->precio = $row["precio"];
					$sql = "SELECT COUNT(nro_documento) AS documentos 
							FROM salidas 
							WHERE tipo_documento = 'TDCNET'
								AND DATE_FORMAT(fecha, '%Y-%m-%d') = '" . $row["fecha"] . "'  
								AND estatus = 'PROCESADO';"; 
					$rs2 = mysqli_query($link, $sql);
					$row2 = mysqli_fetch_array($rs2);
					$documentos = intval($row2["documentos"]);
					$VentasDiarias->documentos = $documentos;
					$sql = "SELECT ABS(SUM(y.cantidad_movimiento)) AS articulos 
							FROM salidas AS x 
								JOIN entradas_salidas AS y ON y.tipo_documento = x.tipo_documento AND y.id_documento = x.id 
							WHERE x.tipo_documento = 'TDCNET'
								AND DATE_FORMAT(x.fecha, '%Y-%m-%d') = '" . $row["fecha"] . "'  
								AND x.estatus = 'PROCESADO';";
					$rs2 = mysqli_query($link, $sql);
					$row2 = mysqli_fetch_array($rs2);
					$articulos = intval($row2["articulos"]);;
					$VentasDiarias->articulos = $articulos;
					$sql = "SELECT 
								bb.tasa_usd AS tasa  
							FROM 
								cobros_cliente AS aa 
								JOIN cobros_cliente_detalle AS bb ON bb.cobros_cliente = aa.id 
							WHERE 
								DATE_FORMAT(aa.fecha, '%Y-%m-%d') = '" . $row["fecha"] . "' 
							UNION SELECT 
								aa.tasa_usd AS tasa  
							FROM 
								recarga AS aa 
							WHERE 
								DATE_FORMAT(aa.fecha, '%Y-%m-%d') = '" . $row["fecha"] . "' LIMIT 0,1;";
					$rs2 = mysqli_query($link, $sql);
					$row2 = mysqli_fetch_array($rs2);
					$tasa = floatval($row2["tasa"]);;
					$VentasDiarias->tasa = $tasa;
					$VentasDiarias->tienda = $codigo;
					$VentasDiarias->id = NULL;

					$listaVentasDiarias[] = $VentasDiarias;
				}

				mysqli_close($link);

				$objVentasDiarias->listaVentasDiarias = $listaVentasDiarias;
				echo json_encode($objVentasDiarias, JSON_UNESCAPED_UNICODE);

				break;
			case "tipo_pago":
				include("connect.php");

				$sql = "SELECT 
							fecha, metodo_pago, SUM(monto_bs) AS monto_bs, SUM(monto_usd) AS monto_usd 
						FROM 
							(
								SELECT 
									aa.fecha, 
									aa.tipo, 
									CONCAT(bb.valor2, ' - ', aa.moneda) AS metodo_pago, 
									-- aa.doc, 
									aa.cliente, 
									aa.monto_bs AS monto_bs, 
									aa.monto_usd AS monto_usd, 
									aa.metodo_pago AS tipo_pago, aa.referencia  
								FROM 
									(
									SELECT 
										b.fecha, 
										'NOTA DE ENTREGA' AS tipo, 
										a.metodo_pago, 
										a.moneda, 
										-- c.nro_documento AS doc, 
										d.nombre AS cliente,  
										a.monto_bs AS monto_bs, a.monto_usd AS monto_usd, a.referencia  
									FROM 
										cobros_cliente_detalle AS a 
										JOIN cobros_cliente AS b ON b.id = a.cobros_cliente 
										LEFT OUTER JOIN salidas AS c ON c.id = b.id_documento 
										LEFT OUTER JOIN cliente AS d ON d.id = b.cliente 
									WHERE 
										a.metodo_pago NOT IN ('RC', 'RD', 'PF', 'PC', 'DV', 'NC', 'ND', 'SF', 'GN') 
										AND b.fecha BETWEEN '$fecha_inicio 00:00:00' AND '$fecha_fin 23:59:59' 
										AND c.estatus = 'PROCESADO' 
									UNION ALL 
									SELECT 
										a.fecha, 
										'RECIBO' AS TIPO, 
										a.metodo_pago, 
										a.moneda, 
										-- (SELECT LPAD(nro_recibo, 7, '0') FROM abono WHERE id = a.abono) AS doc, 
										b.nombre AS cliente, 
										a.monto_bs AS monto_bs, 
										a.monto_usd AS monto_usd, a.referencia  
									FROM 
										recarga AS a 
										LEFT OUTER JOIN cliente AS b ON b.id = a.cliente 
									WHERE 
										a.metodo_pago NOT IN ('RC', 'RD', 'PF', 'PC', 'DV', 'NC', 'ND', 'SF', 'GN') 
										AND a.fecha BETWEEN '$fecha_inicio 00:00:00' AND '$fecha_fin 23:59:59' 
										AND (a.monto_usd > 0 OR a.reverso = 'S') 
									UNION ALL 
									SELECT 
										a.fecha, 
										'RECIBO' AS TIPO, 
										a.metodo_pago, 
										a.moneda, 
										-- (SELECT LPAD(nro_recibo, 7, '0') FROM abono WHERE id = a.abono) AS doc, 
										b.nombre AS cliente, 
										a.monto_bs AS monto_bs, 
										a.monto_usd AS monto_usd, a.referencia  
									FROM 
										recarga2 AS a 
										LEFT OUTER JOIN cliente AS b ON b.id = a.cliente 
									WHERE 
										a.metodo_pago NOT IN ('RC', 'RD', 'PF', 'PC', 'DV', 'NC', 'ND', 'SF', 'GN') 
										AND a.fecha BETWEEN '$fecha_inicio 00:00:00' AND '$fecha_fin 23:59:59' 
										AND (a.monto_usd > 0 OR a.reverso = 'S') 
									) AS aa 
									LEFT OUTER JOIN parametro AS bb ON bb.valor1 = aa.metodo_pago 
									WHERE bb.codigo = '009' AND aa.metodo_pago NOT IN ('RC', 'RD', 'PF', 'PC', 'DV', 'NC', 'ND', 'SF', 'GN') 
							) AS mp 
						GROUP BY fecha, metodo_pago;";
				$rs = mysqli_query($link, $sql);

				$objTipoPago = new stdClass();
				$listaTipoPago = [];

				while($row = mysqli_fetch_array($rs)) {
					$tipopago = new stdClass();

					$tipopago->fecha = $row["fecha"];
					$tipopago->metodo_pago = $row["metodo_pago"];
					$tipopago->monto_bs = $row["monto_bs"];
					$tipopago->monto_usd = $row["monto_usd"];
					$tipopago->tienda = $codigo;
					$tipopago->id = NULL;

					$listaTipoPago[] = $tipopago;
				}

				mysqli_close($link);

				$objTipoPago->listaTipoPago = $listaTipoPago;
				echo json_encode($objTipoPago, JSON_UNESCAPED_UNICODE);


				break;
			case "ventas_articulo":
				include("connect.php");

				$sql = "SELECT 
							DATE_FORMAT(a.fecha, '%Y-%m-%d') AS fecha, 
							c.codigo_ims, 
							SUM(b.cantidad_articulo) AS cantidad_articulo, 
							(SUM(IFNULL(b.costo, 0))/SUM(b.cantidad_articulo)) AS costo, 
							-- (SUM(IFNULL(b.precio, 0))/SUM(b.cantidad_articulo)) AS precio 
							SUM(IFNULL(b.precio, 0)-(IFNULL(b.precio, 0)*(IFNULL(a.descuento, 0)/100))) AS precio 
						FROM 
							salidas AS a 
							JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento 
								AND b.id_documento = a.id 
							LEFT OUTER JOIN articulo AS c ON c.id = b.articulo 
						WHERE 
							a.tipo_documento = 'TDCNET' 
							AND a.fecha BETWEEN '$fecha_inicio 00:00:00' AND '$fecha_fin 23:59:59' 
							AND a.estatus = 'PROCESADO' 
						GROUP BY 
							date_format(a.fecha, '%Y-%m-%d'), c.codigo_ims;";
				$rs = mysqli_query($link, $sql);

				$objVentasArticulos = new stdClass();
				$listaVentasArticulos = [];

				while($row = mysqli_fetch_array($rs)) {
					$ventasarticulos = new stdClass();

					$ventasarticulos->fecha = $row["fecha"];
					$ventasarticulos->codigo_ims = $row["codigo_ims"];
					$ventasarticulos->cantidad_articulo = $row["cantidad_articulo"];
					$ventasarticulos->costo = $row["costo"];
					$ventasarticulos->precio = $row["precio"];
					$ventasarticulos->tienda = $codigo;
					$ventasarticulos->id = NULL;

					$listaVentasArticulos[] = $ventasarticulos;
				}

				mysqli_close($link);

				$objVentasArticulos->listaVentasArticulos = $listaVentasArticulos;
				echo json_encode($objVentasArticulos, JSON_UNESCAPED_UNICODE);

				break;
			case "inventario":
				include("connect.php");

				$sql = "SELECT 
							CURDATE() AS fecha, 
							art.id, art.codigo, art.codigo_ims, art.nombre AS fabricante, 
							'UNIDAD' AS unidad_medida, art.principio_activo, 
							art.presentacion, art.nombre_comercial, 
							IFNULL(dev.cantidad, 0) AS devoluciones, 
							IFNULL(ent.cantidad, 0) AS entradas, ABS(IFNULL(sal.cantidad, 0)) AS salidas, 
							(IFNULL(ent.cantidad, 0) - ABS(IFNULL(sal.cantidad, 0))) AS existencia, 
							(SELECT ultimo_costo FROM articulo WHERE codigo_ims = art.codigo_ims LIMIT 0, 1) AS costo_unidad, 
							(SELECT precio FROM articulo WHERE codigo_ims = art.codigo_ims LIMIT 0, 1) AS precio_unidad 
						FROM 
							(
								SELECT 
									a.id, a.codigo, a.codigo_ims, b.nombre, 
									'UNIDAD' AS unidad_medida, a.principio_activo, 
									a.presentacion, a.nombre_comercial 
								FROM 
									articulo AS a 
									LEFT OUTER JOIN fabricante AS b ON b.Id = a.fabricante  
								WHERE 
									1  
							) AS art 
							LEFT OUTER JOIN 
							(
								SELECT 
									a.articulo, SUM(a.cantidad_movimiento) AS cantidad  
								FROM 
									entradas_salidas AS a 
									JOIN salidas AS b ON
										b.tipo_documento = a.tipo_documento
										AND b.id = a.id_documento 
									JOIN almacen AS c ON
										c.codigo = a.almacen AND c.movimiento = 'S' 
								WHERE
									a.tipo_documento IN ('TDCNET', 'TDCASA') 
									AND b.estatus <> 'ANULADO' AND b.activo = 'S' AND 
									b.fecha < CONCAT(CURDATE(), ' 23:59:59') 
								GROUP BY a.articulo
							) AS sal ON sal.articulo = art.Id 
							LEFT OUTER JOIN 
							(
								SELECT 
									a.articulo, SUM(a.cantidad_movimiento) AS cantidad 
								FROM 
									entradas_salidas AS a 
									JOIN entradas AS b ON
										b.tipo_documento = a.tipo_documento
										AND b.id = a.id_documento 
									JOIN almacen AS c ON
										c.codigo = a.almacen AND c.movimiento = 'S'
								WHERE
									((a.tipo_documento IN ('TDCNRP', 'TDCAEN') 
									AND b.estatus = 'PROCESADO') OR 
									(a.tipo_documento IN ('TDCNRP', 'TDCAEN') 
									AND b.estatus <> 'ANULADO') AND b.consignacion = 'S') AND 
									b.fecha < CONCAT(CURDATE(), ' 23:59:59') AND IFNULL(b.nota, '') <> 'DEVOLUCION DE ARTICULO' AND IFNULL(cliente, 0) = 0 
								GROUP BY a.articulo
							) AS ent ON ent.articulo = art.Id 
							LEFT OUTER JOIN 
							(
								SELECT 
									a.articulo, SUM(a.cantidad_movimiento) AS cantidad 
								FROM 
									entradas_salidas AS a 
									JOIN entradas AS b ON
										b.tipo_documento = a.tipo_documento
										AND b.id = a.id_documento 
									JOIN almacen AS c ON
										c.codigo = a.almacen AND c.movimiento = 'S'
								WHERE
									((a.tipo_documento IN ('TDCNRP') 
									AND b.estatus = 'PROCESADO') OR 
									(a.tipo_documento IN ('TDCNRP') 
									AND b.estatus <> 'ANULADO') AND b.consignacion = 'S') AND 
									b.fecha < CONCAT(CURDATE(), ' 23:59:59') AND IFNULL(b.nota, '') = 'DEVOLUCION DE ARTICULO' 
								GROUP BY a.articulo
							) AS dev ON dev.articulo = art.Id 
						ORDER BY art.codigo_ims ASC;"; 
				$rs = mysqli_query($link, $sql);

				$objInventario = new stdClass();
				$listaInventario = [];

				while($row = mysqli_fetch_array($rs)) {
					$inventario = new stdClass();

					$inventario->fecha = $row["fecha"];
					$inventario->id = $row["id"];
					$inventario->codigo = $row["codigo"];
					$inventario->codigo_ims = $row["codigo_ims"];
					$inventario->fabricante = $row["fabricante"];
					$inventario->unidad_medida = $row["unidad_medida"];
					$inventario->principio_activo = $row["principio_activo"];
					$inventario->presentacion = $row["presentacion"];
					$inventario->nombre_comercial = $row["nombre_comercial"];
					$inventario->devoluciones = $row["devoluciones"];
					$inventario->entradas = $row["entradas"];
					$inventario->salidas = $row["salidas"];
					$inventario->existencia = $row["existencia"];
					$inventario->costo_unidad = $row["costo_unidad"];
					$inventario->precio_unidad = $row["precio_unidad"];
					$inventario->tienda = $codigo;
					$inventario->id = NULL;

					$listaInventario[] = $inventario;
				}

				mysqli_close($link);

				$objInventario->listaInventario = $listaInventario;
				echo json_encode($objInventario, JSON_UNESCAPED_UNICODE);

				break;				
			case "gastos":
				include("connect.php");

				$sql = "SELECT 
							NULL, a.fecha, b.nombre AS proveedor, a.documento, a.descripcion, 
							a.monto_exento, a.monto_gravado, a.alicuota, a.monto_iva, a.monto_total, 
							'000' AS tienda 
						FROM 
							compra AS a 
							LEFT OUTER JOIN proveedor AS b on b.id = a.proveedor 
						WHERE 
							a.anulado = 'N' AND a.fecha BETWEEN '$fecha_inicio 00:00:00' AND '$fecha_fin 23:59:59';"; 
				$rs = mysqli_query($link, $sql);

				$objGastos = new stdClass();
				$listaGastos = [];

				while($row = mysqli_fetch_array($rs)) {
					$gastos = new stdClass();

					$gastos->fecha = $row["fecha"];
					$gastos->proveedor = $row["proveedor"];
					$gastos->documento = $row["documento"];
					$gastos->descripcion = $row["descripcion"];
					$gastos->monto_exento = $row["monto_exento"];
					$gastos->monto_gravado = $row["monto_gravado"];
					$gastos->alicuota = $row["alicuota"];
					$gastos->monto_iva = $row["monto_iva"];
					$gastos->monto_total = $row["monto_total"];
					$gastos->tienda = $codigo;
					$gastos->id = NULL;

					$listaGastos[] = $gastos;
				}

				mysqli_close($link);

				$objGastos->gastos = $listaGastos;
				echo json_encode($objGastos, JSON_UNESCAPED_UNICODE);

				break;
			}
		}
	}
}
?>