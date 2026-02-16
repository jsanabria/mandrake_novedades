<?php 
header('Content-type: application/json; charset=utf-8');

if(isset($_GET['dbName']))  
	$strcon = trim($_GET['dbName']); 
else 
	die("No Database");

if(isset($_GET['user']) && intval($_GET['user'])) { 
	if(intval($_GET['user']) == 365) {
		if(isset($_GET['app'])) {
			switch(trim($_GET['app'])) {
			case "tasa":
				include("connect.php");
				$sql = "SELECT moneda, tasa, fecha, hora FROM tasa_usd WHERE fecha = CURDATE() ORDER BY id DESC LIMIT 0, 1;";
				$rs = mysqli_query($link, $sql);

				$objTasa = new stdClass();
				$listaTasa = [];

				while($row = mysqli_fetch_array($rs)) {
					$tasa = new stdClass();
					$tasa->moneda = $row["moneda"];
					$tasa->tasa = $row["tasa"];
					$tasa->fecha = $row["fecha"];
					$tasa->hora = $row["hora"];
			
					$listaTasa[] = $tasa;
				}

				mysqli_close($link);

				$objTasa->listaTasa = $listaTasa;
				echo json_encode($objTasa, JSON_UNESCAPED_UNICODE);

				break;
			case "articulo":
				include("connect.php");

				$sql = "SELECT 
							a.id, a.codigo, 
							a.nombre_comercial, a.principio_activo, a.presentacion, a.fabricante, 
							a.codigo_de_barra, a.ultimo_costo, a.alicuota, a.articulo_inventario, 
							a.codigo_ims, a.activo, a.precio 
						FROM articulo AS a WHERE 1 ORDER BY a.id;";
				$rs = mysqli_query($link, $sql);

				$objArticulos = new stdClass();
				$listaArticulos = [];

				while($row = mysqli_fetch_array($rs)) {
					$articulos = new stdClass();
					$articulos->id = $row["id"];
					$articulos->codigo = $row["codigo"];
					$articulos->nombre_comercial = $row["nombre_comercial"];
					$articulos->principio_activo = $row["principio_activo"];
					$articulos->presentacion = $row["presentacion"];
					$articulos->fabricante = $row["fabricante"];
					$articulos->codigo_de_barra = $row["codigo_de_barra"];
					$articulos->ultimo_costo = $row["ultimo_costo"];
					$articulos->alicuota = $row["alicuota"];
					$articulos->articulo_inventario = $row["articulo_inventario"];
					$articulos->codigo_ims = $row["codigo_ims"];
					$articulos->activo = $row["activo"];
					$articulos->precio = $row["precio"];

					$listaArticulos[] = $articulos;
				}

				mysqli_close($link);

				$objArticulos->listaArticulos = $listaArticulos;
				echo json_encode($objArticulos, JSON_UNESCAPED_UNICODE);

				break;
			case "tarifa":
				include("connect.php");

				$sql = "SELECT 
							a.id, a.nombre, a.activo, a.patron, a.porcentaje  
						FROM tarifa AS a WHERE 1 ORDER BY id;";
				$rs = mysqli_query($link, $sql);

				$objTarifas = new stdClass();
				$listTarifas = [];

				while($row = mysqli_fetch_array($rs)) {
					$tarifas = new stdClass();
					$tarifas->id = $row["id"];
					$tarifas->nombre = $row["nombre"];
					$tarifas->activo = $row["activo"];
					$tarifas->patron = $row["patron"];
					$tarifas->porcentaje = $row["porcentaje"];

					$listaTarifas[] = $tarifas;
				}

				mysqli_close($link);

				$objTarifas->listaTarifas = $listaTarifas;
				echo json_encode($objTarifas, JSON_UNESCAPED_UNICODE);

				break;
			case "tarifa_articulo":
				include("connect.php");

				$sql = "SELECT 
							a.id, a.tarifa, a.fabricante, a.articulo, a.precio   
						FROM tarifa_articulo AS a WHERE 1 ORDER BY id;";
				$rs = mysqli_query($link, $sql);

				$objTarifas = new stdClass();
				$listTarifas = [];

				while($row = mysqli_fetch_array($rs)) {
					$tarifas = new stdClass();
					$tarifas->id = $row["id"];
					$tarifas->tarifa = $row["tarifa"];
					$tarifas->fabricante = $row["fabricante"];
					$tarifas->articulo = $row["articulo"];
					$tarifas->precio = $row["precio"];

					$listaTarifas[] = $tarifas;
				}

				mysqli_close($link);

				$objTarifas->listaTarifas = $listaTarifas;
				echo json_encode($objTarifas, JSON_UNESCAPED_UNICODE);

				break;
			case "compra":
				include("connect.php");

				$sql = "SELECT 
							id, tipo_documento, username, fecha, proveedor, nro_documento, 
							almacen, monto_total, alicuota_iva, iva, total, nota, estatus, 
							moneda, consignacion, consignacion_reportada, descuento 
						FROM entradas WHERE tipo_documento = 'TDCPDC' AND estatus = 'NUEVO' ORDER BY nro_documento;";
				$rs = mysqli_query($link, $sql);

				$objFacturas = new stdClass();
				$listaFacturas = [];

				while($row = mysqli_fetch_array($rs)) {
					$facturas = new stdClass();
					$facturas->id = $row["id"];
					$facturas->tipo_documento = $row["tipo_documento"];
					$facturas->username = $row["username"];
					$facturas->fecha = $row["fecha"];
					$facturas->proveedor = $row["proveedor"];
					$facturas->nro_documento = $row["nro_documento"];
					$facturas->almacen = $row["almacen"];
					$facturas->monto_total = $row["monto_total"];
					$facturas->alicuota_iva = $row["alicuota_iva"];
					$facturas->iva = $row["iva"];
					$facturas->total = $row["total"];
					$facturas->nota = $row["nota"];
					$facturas->estatus = $row["estatus"];
					$facturas->moneda = $row["moneda"];
					$facturas->consignacion = $row["consignacion"];
					$facturas->consignacion_reportada = $row["consignacion_reportada"];
					$facturas->descuento = $row["descuento"];


					$listaFacturas[] = $facturas;
				}

				mysqli_close($link);

				$objFacturas->listaFacturas = $listaFacturas;
				echo json_encode($objFacturas, JSON_UNESCAPED_UNICODE);

				break;
			case "compra_detalle":
				include("connect.php");

				if(isset($_GET['numero'])) { 
					$id = intval($_GET['numero']);

					$sql = "SELECT 
								id, tipo_documento, id_documento, fabricante, articulo, lote, fecha_vencimiento, 
								almacen, cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
								cantidad_movimiento, costo_unidad, costo, precio_unidad, precio, alicuota, descuento, 
								precio_unidad_sin_desc, check_ne 
							FROM entradas_salidas 
							WHERE tipo_documento = 'TDCPDC' AND id_documento = $id;";
					$rs = mysqli_query($link, $sql);

					$objFacturaDetalle = new stdClass();
					$listaFacturaDetalle = [];

					while($row = mysqli_fetch_array($rs)) {
						$facturasdetalle = new stdClass();
						$facturasdetalle->id = $row["id"];
						$facturasdetalle->tipo_documento = $row["tipo_documento"];
						$facturasdetalle->id_documento = $row["id_documento"];
						$facturasdetalle->fabricante = $row["fabricante"];
						$facturasdetalle->articulo = $row["articulo"];
						$facturasdetalle->lote = $row["lote"];
						$facturasdetalle->fecha_vencimiento = $row["fecha_vencimiento"];
						$facturasdetalle->almacen = $row["almacen"];
						$facturasdetalle->cantidad_articulo = $row["cantidad_articulo"];
						$facturasdetalle->articulo_unidad_medida = $row["articulo_unidad_medida"];
						$facturasdetalle->cantidad_unidad_medida = $row["cantidad_unidad_medida"];
						$facturasdetalle->cantidad_movimiento = $row["cantidad_movimiento"];
						$facturasdetalle->costo_unidad = $row["costo_unidad"];
						$facturasdetalle->costo = $row["costo"];
						$facturasdetalle->precio_unidad = $row["precio_unidad"];
						$facturasdetalle->precio = $row["precio"];
						$facturasdetalle->alicuota = $row["alicuota"];
						$facturasdetalle->descuento = $row["descuento"];
						$facturasdetalle->precio_unidad_sin_desc = $row["precio_unidad_sin_desc"];
						$facturasdetalle->check_ne = $row["check_ne"];

						$listaFacturaDetalle[] = $facturasdetalle;
					}

					mysqli_close($link);

					$objFacturaDetalle->listaFacturaDetalle = $listaFacturaDetalle;
					echo json_encode($objFacturaDetalle, JSON_UNESCAPED_UNICODE);

					break;
				}
			}
		}
	}
}
?>