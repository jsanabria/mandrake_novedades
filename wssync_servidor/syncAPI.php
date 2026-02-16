<?php 
header('Content-type: application/json; charset=utf-8');

$strcon = "adm_novedades"; 
if(isset($_GET['dbName']))  
	$dbName = trim($_GET["dbName"]);
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
							a.codigo_ims, a.activo, a.precio, a.descuento  
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
					$articulos->descuento = $row["descuento"];

					$listaArticulos[] = $articulos;
				}

				mysqli_close($link);

				$objArticulos->listaArticulos = $listaArticulos;
				echo json_encode($objArticulos, JSON_UNESCAPED_UNICODE);

				break;
			case "bitacora": 
				$nota = trim($_GET["_nota"]);
				$usuario = trim($_GET["usuario"]);
				include("connect.php");
				$sql = "INSERT INTO bitacoras_ync
							(id, tienda, fecha_hora, tipo, nota, usuario)
						VALUES 
							(NULL, '$dbName', NOW(), 'B', '$nota', '$usuario')"; 
				mysqli_query($link, $sql);

				$objBitacora = new stdClass();
				$listaBitacora = [];

				$listaBitacora[] = "Registro en BITACORA....";

				mysqli_close($link);

				$objBitacora->listaBitacora = $listaBitacora;
				echo json_encode($objBitacora, JSON_UNESCAPED_UNICODE);

				break;
			}
		}
	}
}
?>