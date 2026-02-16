<?php

namespace PHPMaker2021\mandrake;

// Page object
$Home = &$Page;
?>
<?php
//ActualizarExitencia();
$sql = "SELECT valor1 FROM parametro WHERE codigo = '013';";
$bloquea = ExecuteScalar($sql);
if($bloquea == "SI") {
	$msbloquea = '<div class="alert alert-danger" role="alert">PROCESO DE PEDIDO DE VENTAS BLOQUEADO TEMPORALMENTE POR MANTENIMIENTO</div>';
}

$sql = "SELECT tipo_acceso FROM userlevels
		WHERE userlevelid = '" . CurrentUserLevel() . "';"; 
$grupo = trim(ExecuteScalar($sql));

$sql = "SELECT nombre, telefono, email, foto, asesor, cliente 
		FROM usuario
		WHERE username = '" . CurrentUserName() . "';";
if($row = ExecuteRow($sql)) {
	$nombre = $row["nombre"];
	$telefono = $row["telefono"];
	$email = $row["email"];
	$asesor = intval(trim($row["asesor"]));
	$cliente = intval(trim($row["cliente"]));
}
else {
	$nombre = "";
	$telefono = "";
	$email = "";
	$asesor = 0;
	$cliente = 0;
}
$foto = "carpetacarga/" . (!isset($row["foto"]) ? "silueta.jpg" : $row["foto"]);

$tarifas = "";
$where = "0=0";
if($asesor > 0) {
	$sql = "SELECT 
				COUNT(f.tarifa) AS cantidad 
			FROM 
				(SELECT 
					DISTINCT b.tarifa, c.nombre 
				FROM 
					asesor_cliente AS a 
					JOIN cliente AS b ON b.id = a.cliente 
					JOIN tarifa AS c ON c.id = b.tarifa 
				WHERE a.asesor = $asesor) AS f;";
	$cantidad = ExecuteScalar($sql);

	for($i=0; $i<$cantidad; $i++) {
		$sql = "SELECT 
					DISTINCT b.tarifa, c.nombre 
				FROM 
					asesor_cliente AS a 
					JOIN cliente AS b ON b.id = a.cliente 
					JOIN tarifa AS c ON c.id = b.tarifa 
				WHERE a.asesor = $asesor LIMIT $i, 1;";
		$row = ExecuteRow($sql);
		// href="reportes/listado_articulos_por_tarifa.php?codcliente=&tarifa=' . $row["tarifa"] . '"
		$tarifas .= '<hr><a class="btn btn-info" target="_blank" onclick="js:print_to(' . $row["tarifa"] . ');" >Articulos Tarifa ' . $row["nombre"] . '</a><hr> ';
	}

	$sql = "SELECT COUNT(cliente) AS cantidad FROM asesor_cliente
			WHERE asesor = '$asesor';";
	$cantidad = ExecuteScalar($sql);
	$clientes = "";
	for($i=0; $i<$cantidad; $i++) {
		$sql = "SELECT cliente FROM asesor_cliente
			WHERE asesor = '$asesor' LIMIT $i, 1;";
		$clientes .= ExecuteScalar($sql) . ",";
	}
	$clientes .= "0"; 
	$where = "codcli IN ($clientes)";
}

if($cliente > 0) {
	$sql = "SELECT 
				a.tarifa, b.nombre 
			FROM 
				cliente AS a  
				JOIN tarifa AS b ON b.id = a.tarifa 
			WHERE a.id = $cliente";
		$row = ExecuteRow($sql);
		$tarifas .= '<hr><a class="btn btn-info" target="_blank" href="reportes/listado_articulos_por_tarifa.php?codcliente=&tarifa=' . $row["tarifa"] . '">Articulos Tarifa ' . $row["nombre"] . '</a><hr> ';

	$where = "codcli=$cliente";
} 

$levelid = CurrentUserLevel();
if($levelid == -1) {
	$sql = "SELECT count(id) AS cantidad FROM tarifa WHERE activo = 'S';";
	$cantidad = ExecuteScalar($sql);

	for($i=0; $i<$cantidad; $i++) {
		$sql = "SELECT 
					id AS tarifa, nombre 
				FROM 
					tarifa WHERE activo = 'S' LIMIT $i, 1;";
		$row = ExecuteRow($sql);
		
		$tarifas .= '<hr><a class="btn btn-info" target="_blank" onclick="js:print_to(' . $row["tarifa"] . ');" >Articulos Tarifa ' . $row["nombre"] . '</a><hr> ';
	}
}

$sql = "SELECT tasa FROM tasa_usd ORDER BY id DESC LIMIT 0, 1;";
$tasa = '<hr><b>TASA DEL DIA <br>1 USD <br>' . number_format(ExecuteScalar($sql), 2, ",", ".") . " Bs.<br><hr></b>";

//////////// Activo alerta ////////////
$sql = "SELECT 
			COUNT(nro_documento) AS dias 
		FROM 
			view_facturas_a_entregar 
		WHERE 
			$where;";
$facturas_a_entregar = intval(ExecuteScalar($sql));

$sql = "SELECT 
			COUNT(nro_documento) AS dias 
		FROM 
			view_facturas_vencidas  
		WHERE 
			$where;";
$facturas_vencidas = intval(ExecuteScalar($sql));
//////////// ------------- ////////////

?>
<div class="card">
	<div class="card-header text-center">
		Sistema de Facturaci&oacute;n y Control de Inventarios
		<?php
		if($grupo != "PROVEEDOR") {
		?>
		  <?php if($facturas_a_entregar>0) { ?><a href="ViewFacturasAEntregarList" class="btn btn-primary"><i class="fa fa-clock"></i> <?php echo $facturas_a_entregar; ?></a><?php } ?>
		  <?php if($facturas_vencidas>0) { ?><a href="ViewFacturasVencidasList" class="btn btn-primary"><i class="fa fa-bell"></i> <?php echo $facturas_vencidas; ?></a><?php } ?>
		<?php
		}
		?>
	</div>

	<div class="container">
		<div class="card-body">
			<div class="row">
				<div class="col-sm-4 text-center">
					<a class="btn btn-primary" href="SalidasList?tipo=TDCNET"><i class="fa fa-comment-dots"></i> Notas de Entrega</a>
				</div>
				<div class="col-sm-4 text-center">
					<a class="btn btn-primary" href="CobrosClienteList"><i class="fa fa-lock"></i> Cierre de Caja</a>
				</div>
				<div class="col-sm-4 text-center">
					<a class="btn btn-primary" href="RecargaList"><i class="fa fa-piggy-bank"></i> Cargar Abonos</a>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-sm-4 text-center">
					<a class="btn btn-primary" href="NotaDeEntregaBuscar"><i class="fa fa-comment-dots"></i> Crear Factura</a>
				</div>
				<div class="col-sm-4 text-center">
					<a class="btn btn-primary" href="SalidasList?tipo=TDCFCV"><i class="fa fa-comment-dots"></i> Listar Factura</a>
				</div>
				<div class="col-sm-4 text-center">
					<a class="btn btn-primary" href="Devoluciones"><i class="fa fa-undo"></i> Devoluciones</a>
				</div>
			</div>
		</div>
		<div class="card-body">
			<?php
			$row = ExecuteRow("SELECT nombre, logo FROM compania LIMIT 0,1;");
			$cia = $row["nombre"];
			$logo = $row["logo"];
			?>
			<h1 class="text-center"><?php echo $cia; ?></h1>
			<center><img src="carpetacarga/<?php echo $logo; ?>" width="350" class="img-rounded img-responsive center-block" alt="DroPharma"></center>
			<?php
			$db = ExecuteScalar("SELECT DATABASE();");
			?>
			<h4 class="text-center"><strong><i><?php echo "Base de Datos: " . $db; ?><i></strong></h4>
		</div>
		<?php
		if($bloquea == "SI") echo $msbloquea;
		?>
	</div>
</div>
<div class="card">
	<div class="card-body">
		<div class="row">
			<div class="col-md-2">
				<img src="<?php echo $foto; ?>" class="img-responsive img-thumbnail" alt="Cinque Terre" width="150">
			</div>
			<div class="col-md-3">
				<h4><?php echo $nombre; ?></h4>
				<h4><?php echo "$telefono / $email"; ?></h4>
			</div>
			<div class="col-md-4">
				<?php
					if(CurrentUserLevel() == -1) {
						?>
							<p><a href="Sesiones" target="_blank"><strong>Ultimos Inicios de Sesi&oacute;n <?php echo date("d/m/Y"); ?></strong></a></p>
							<table class="table table-condensed table-hover">
								<!--<thead>
									<tr>
										<th>Usuario</th>
										<th>Fecha</th>
									</tr>
								</thead>-->
								<tbody>
									<?php
										for($i=0; $i<7; $i++) {
											$sql = "SELECT 
														IFNULL(b.nombre, a.user) AS usuario, 
														DATE_FORMAT(a.datetime, '%h:%i:%s %p') AS fecha, 
														a.action 
													FROM 
														audittrail AS a  
														LEFT OUTER JOIN usuario AS b ON b.username = a.user 
													WHERE 
														DATE_FORMAT(a.datetime, '%d/%m/%Y') = DATE_FORMAT(NOW(), '%d/%m/%Y') 
														AND a.action IN ('login', 'logout') AND a.user <> '-1' 
													ORDER BY a.datetime DESC LIMIT $i, 1;";
											if($row = ExecuteRow($sql)) {
												echo '<tr>';
													echo '<td>' . $row["usuario"] . '</td>';
													echo '<td>' . $row["fecha"] . '</td>';
													echo '<td>' . $row["action"] . '</td>';
												echo '</tr>';
											}
										}
									?>
								</tbody>
							</table>
						<?php
						echo $tarifas;
					}
					else echo $tarifas; ?>
			</div>
			<div class="col-md-3">
				<?php echo $tasa; ?>
				<?php
					//if(CurrentUserLevel() == -1) {
						echo '<h1><a href="Indicadores" target="_blank"><span class="fa fa-signal"></span></a></h1>';
					//}
				?>
			</div>
		</div>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<div class="row">
			<div class="col-md-12">
<div class="dropdown">
  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Saneras con Ventas mayores o iguales a:
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    <a class="dropdown-item" onclick="js: saneras(1); ">1 Venta</a>
    <a class="dropdown-item" onclick="js: saneras(2); ">2 Venta</a>
    <a class="dropdown-item" onclick="js: saneras(3); ">3 Venta</a>
    <a class="dropdown-item" onclick="js: saneras(4); ">4 Venta</a>
    <a class="dropdown-item" onclick="js: saneras(5); ">5 Venta</a>
    <a class="dropdown-item" onclick="js: saneras(6); ">6 Venta</a>
    <a class="dropdown-item" onclick="js: saneras(7); ">7 Venta</a>
    <a class="dropdown-item" onclick="js: saneras(8); ">8 Venta</a>
    <a class="dropdown-item" onclick="js: saneras(8); ">9 Venta</a>
    <a class="dropdown-item" onclick="js: saneras(10); ">10 Venta</a>
  </div>

    <select class="form-control" id="xTarifa" onchange="js: tarifas();">
    	<?php 
    	$sql = "SELECT id, nombre FROM tarifa WHERE 1 ORDER BY nombre DESC;";
    	$rows = ExecuteRows($sql);
    	foreach ($rows as $key => $value) {
	    	echo '<option value="' . $value["id"] . '">' . $value["nombre"] . '</option>';
    	}
    	?>
    </select>
    <input type="date" class="form-control" id="xFecha" value="<?php echo date("d/m/Y"); ?>">
</div>
			</div>
			<div class="col-md-12" id="xSaneras">
				
			</div>
		</div>
	</div>
</div>

<script>
	function print_to(tarifa) {
		if(confirm("Desea Enviar a Excel?")) {
			var url = "print_tarifa.php?codcliente=&tarifa=" + tarifa + "";
			window.open(url, '_blank');
		}
		else {
			var url = "reportes/listado_articulos_por_tarifa.php?codcliente=&tarifa=" + tarifa + "";
			window.open(url, '_blank');
		}
	}

	function saneras(x) {
		var tarifa = $("#xTarifa").val();
		var fecha = $("#xFecha").val();
		
        $.ajax({
          url : "include/Buscar_Saneras.php",
          type: "GET",
          data : {cantidad: x, tarifa: tarifa, fecha: fecha},
          beforeSend: function(){
          }
        })
        .done(function(MyResult) {
        	$("#xSaneras").html(MyResult);
        })
        .fail(function(data) {
          alert( "error" + data );
        })
        .always(function(data) {
        	// $("#xSaneras").html("...");
        });


	}

	function tarifas() {
	  	saneras(1);
	}
</script>

<?= GetDebugMessage() ?>
