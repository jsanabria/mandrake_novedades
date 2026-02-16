<?php

namespace PHPMaker2021\mandrake;

// Page object
$ConfirmPage = &$Page;
?>
<?php
$page = isset($_REQUEST["page"]) ? $_REQUEST["page"] : "";
$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : 0;
$patron = isset($_REQUEST["patron"]) ? $_REQUEST["patron"] : 0;

//die("$page -- $id -- $patron");

switch($page) {
case "ActTarifa":
	$sql = "SELECT nombre FROM tarifa WHERE id = $id;";
	$tarifa_actualizar = ExecuteScalar($sql);

	$sql = "SELECT id, nombre FROM tarifa WHERE patron = 'S' LIMIT 0,1;";
	$row = ExecuteRow($sql);
	$tarifa = $row["nombre"];
	?>
	  <section class="py-5 text-center container">
	    <div class="row py-lg-5">
	      <div class="col-lg-6 col-md-8 mx-auto">
	        <h1 class="fw-light">Actualizar Tarifa</h1>
	        <p class="lead text-muted">Este es un proceso irreversible; &iquest;Sequro que desea Actualizar la Tarifa <?php echo $tarifa_actualizar; ?> tomando como patr&oacute;n <?php echo $tarifa; ?>?</p>
	        <p>
	          <a href="<?php echo "ActualizarTarifaPatron?id=" . $id . "&patron=" . $patron; ?>" class="btn btn-primary my-2">Aceptar</a>
	          <a href="<?php echo "TarifaList"; ?>" class="btn btn-secondary my-2">Cancelar</a>
	        </p>
	      </div>
	    </div>
	  </section>
	<?php
	break;
case "SyncTarifa":
	$sql = "SELECT nombre FROM tarifa WHERE id = $id;";
	$tarifa_actualizar = ExecuteScalar($sql);

	?>
	  <section class="py-5 text-center container">
	    <div class="row py-lg-5">
	      <div class="col-lg-6 col-md-8 mx-auto">
	        <h1 class="fw-light">Sincronizar Art&iacute;culos a Tarifa</h1>
	        <p class="lead text-muted">Este es un proceso irreversible; &iquest;Est&aacute; seguro de agregar articulos nuevos a esta tarifa <?php echo $tarifa_actualizar; ?>?</p>
	        <p>
	          <a href="<?php echo "SyncItem?id=" . $id; ?>" class="btn btn-primary my-2">Aceptar</a>
	          <a href="<?php echo "TarifaList"; ?>" class="btn btn-secondary my-2">Cancelar</a>
	        </p>
	      </div>
	    </div>
	  </section>
	<?php
	break;
case "TDCNRP":
	$sql = "SELECT descripcion FROM tipo_documento WHERE codigo = 'TDCNRP';";
	$documento = ExecuteScalar($sql);

	?>
	  <section class="py-5 text-center container">
	    <div class="row py-lg-5">
	      <div class="col-lg-6 col-md-8 mx-auto">
	        <h1 class="fw-light">Desea Procesar el Documento <?php echo $documento; ?></h1>
	        <p class="lead text-muted">Este es un proceso irreversible; &iquest;Est&aacute; seguro de procear este documento?</p>
	        <p>
	          <a href="<?php echo "CrearNotaRecepcion?id=" . $id; ?>" class="btn btn-primary my-2">Aceptar</a>
	          <a href="<?php echo "ViewEntradasList?crear=TDCNRP"; ?>" class="btn btn-secondary my-2">Cancelar</a>
	        </p>
	      </div>
	    </div>
	  </section>
	<?php
	break;
case "TDCFCC":
	$sql = "SELECT descripcion FROM tipo_documento WHERE codigo = 'TDCFCC';";
	$documento = ExecuteScalar($sql);

	?>
	  <section class="py-5 text-center container">
	    <div class="row py-lg-5">
	      <div class="col-lg-6 col-md-8 mx-auto">
	        <h1 class="fw-light">Desea Procesar el Documento <?php echo $documento; ?></h1>
	        <p class="lead text-muted">Este es un proceso irreversible; &iquest;Est&aacute; seguro de procear este documento?</p>
	        <p>
	          <a href="<?php echo "CrearFacturaCompra?id=" . $id; ?>" class="btn btn-primary my-2">Aceptar</a>
	          <a href="<?php echo "ViewEntradasList?crear=TDCFCC"; ?>" class="btn btn-secondary my-2">Cancelar</a>
	        </p>
	      </div>
	    </div>
	  </section>
	<?php
	break;
case "TDCNET":
	$sql = "SELECT descripcion FROM tipo_documento WHERE codigo = 'TDCNET';";
	$documento = ExecuteScalar($sql);

	?>
	  <section class="py-5 text-center container">
	    <div class="row py-lg-5">
	      <div class="col-lg-6 col-md-8 mx-auto">
	        <h1 class="fw-light">Desea Procesar el Documento <?php echo $documento; ?></h1>
	        <p class="lead text-muted">Este es un proceso irreversible; &iquest;Est&aacute; seguro de procear este documento?</p>
	        <p>
	          <a href="<?php echo "CrearNotaEntrega?id=" . $id; ?>" class="btn btn-primary my-2">Aceptar</a>
	          <a href="<?php echo "ViewEntradasList?crear=TDCNET"; ?>" class="btn btn-secondary my-2">Cancelar</a>
	        </p>
	      </div>
	    </div>
	  </section>
	<?php
	break;
case "TDCFCV":
	$sql = "SELECT descripcion FROM tipo_documento WHERE codigo = 'TDCFCV';";
	$documento = ExecuteScalar($sql);

	?>
	  <section class="py-5 text-center container">
	    <div class="row py-lg-5">
	      <div class="col-lg-6 col-md-8 mx-auto">
	        <h1 class="fw-light">Desea Procesar el Documento <?php echo $documento; ?></h1>
	        <p class="lead text-muted">Este es un proceso irreversible; &iquest;Est&aacute; seguro de procear este documento?</p>
	        <p>
	          <a href="<?php echo "CrearFacturaVenta?id=" . $id; ?>" class="btn btn-primary my-2">Aceptar</a>
	          <a href="<?php echo "ViewEntradasList?crear=TDCFCV"; ?>" class="btn btn-secondary my-2">Cancelar</a>
	        </p>
	      </div>
	    </div>
	  </section>
	<?php
	break;
case "TDCPDC":
	$sql = "SELECT descripcion FROM tipo_documento WHERE codigo = 'TDCPDC';";
	$documento = ExecuteScalar($sql);

	?>
	  <section class="py-5 text-center container">
	    <div class="row py-lg-5">
	      <div class="col-lg-6 col-md-8 mx-auto">
	        <h1 class="fw-light">Desea Procesar el Documento <?php echo $documento; ?></h1>
	        <p class="lead text-muted">Este es un proceso irreversible; &iquest;Est&aacute; seguro de procear este documento?</p>
	        <p>
	          <a href="<?php echo "CrearFacturaCompra?id=" . $id; ?>" class="btn btn-primary my-2">Aceptar</a>
	          <a href="<?php echo "EntradasList"; ?>" class="btn btn-secondary my-2">Cancelar</a>
	        </p>
	      </div>
	    </div>
	  </section>
	<?php
	break;
case "TDCPDV":
	$sql = "SELECT descripcion FROM tipo_documento WHERE codigo = 'TDCPDV';";
	$documento = ExecuteScalar($sql);

	?>
	  <section class="py-5 text-center container">
	    <div class="row py-lg-5">
	      <div class="col-lg-6 col-md-8 mx-auto">
	        <h1 class="fw-light">Desea Procesar el Documento <?php echo $documento; ?></h1>
	        <p class="lead text-muted">Este es un proceso irreversible; &iquest;Est&aacute; seguro de procear este documento?</p>
	        <p>
	          <a href="<?php echo "CrearFacturaVenta?id=" . $id; ?>" class="btn btn-primary my-2">Aceptar</a>
	          <a href="<?php echo "SalidasList"; ?>" class="btn btn-secondary my-2">Cancelar</a>
	        </p>
	      </div>
	    </div>
	  </section>
	<?php
	break;
case "TDCNET2":
	$sql = "SELECT descripcion FROM tipo_documento WHERE codigo = 'TDCNET';";
	$documento = ExecuteScalar($sql);

	?>
	  <section class="py-5 text-center container">
	    <div class="row py-lg-5">
	      <div class="col-lg-6 col-md-8 mx-auto">
	        <h1 class="fw-light">Desea Procesar el Documento <?php echo $documento; ?></h1>
	        <p class="lead text-muted">Este es un proceso irreversible; &iquest;Est&aacute; seguro de procear este documento?</p>
	        <p>
	          <a href="<?php echo "CrearNotaEntradaUpdate?id=" . $id; ?>" class="btn btn-primary my-2">Aceptar</a>
	          <a href="<?php echo "SalidasList"; ?>" class="btn btn-secondary my-2">Cancelar</a>
	        </p>
	      </div>
	    </div>
	  </section>
	<?php
	break;
case "REVERSO":
	$documento = "REVERSO DE ABONO EN Bs.";
	$sql = "SELECT 
	 			b.nombre AS cliente, 
	 			(SELECT valor2 FROM parametro WHERE valor1 = a.metodo_pago) AS tipo_pago, 
	 			a.monto_moneda AS monto, a.moneda, a.tasa_moneda AS tasa 
	 		FROM 
	 			recarga AS a 
	 			JOIN cliente AS b ON b.id = a.cliente 
	 		WHERE a.id =$id";
	$row = ExecuteRow($sql);
	$cliente = $row["cliente"];
	$tipo_pago = $row["tipo_pago"];
	$tasa = number_format($row["tasa"], 2, ".", ",");
	$monto = $row["moneda"] . " " . number_format($row["monto"], 2, ".", ",");
	$documento .= "<br> <b>Cliente:</b> $cliente";
	$documento .= "<br> <b>Tipo de Pago:</b> $tipo_pago";
	$documento .= "<br> <b>Tasa:</b> $tasa";
	$documento .= "<br> <b>Monto de:</b> $monto";

	?>
	  <section class="py-5 text-center container">
	    <div class="row py-lg-5">
	      <div class="col-lg-6 col-md-8 mx-auto">
	        <h2 class="fw-light">Desea Generar el <?php echo $documento; ?></h2>
	        <p class="lead text-muted">Este es un proceso irreversible; &iquest;Est&aacute; seguro de procear este reverso?</p>
			<div id="charNum"></div>
	        <div class="text-right"><textarea class="form-control-plaintext" rows="3" id="txtNota" name="txtNota" placeholder="Indique por qu&eacute; est&aacute; realizando esta reverso. M&iacute;nimo 20 caracteres..." onkeyup="countChars(this);" style="border-color:red;"></textarea></div>
	        </p>
	        <p>
	          <!--<a href="<?php echo "include/ProcesarReverso.php?id=" . $id; ?>" class="btn btn-primary my-2">Aceptar</a>-->
	           <button class="btn btn-primary my-2" onclick="js:EnviarReverso(<?php echo $id ?>)">Aceptar</button>
	          <a href="<?php echo "AbonoList"; ?>" class="btn btn-secondary my-2">Cancelar</a>
	        </p>
	      </div>
	    </div>
	  </section>
	<?php
	break;
case "REVERSO2":
	$documento = "REVERSO DE ABONO EN USD";
	$sql = "SELECT 
	 			b.nombre AS cliente, 
	 			(SELECT valor2 FROM parametro WHERE valor1 = a.metodo_pago) AS tipo_pago, 
	 			a.monto_moneda AS monto, a.moneda, a.tasa_moneda AS tasa 
	 		FROM 
	 			recarga2 AS a 
	 			JOIN cliente AS b ON b.id = a.cliente 
	 		WHERE a.id =$id";
	$row = ExecuteRow($sql);
	$cliente = $row["cliente"];
	$tipo_pago = $row["tipo_pago"];
	$tasa = number_format($row["tasa"], 2, ".", ",");
	$monto = $row["moneda"] . " " . number_format($row["monto"], 2, ".", ",");
	$documento .= "<br> <b>Cliente:</b> $cliente";
	$documento .= "<br> <b>Tipo de Pago:</b> $tipo_pago";
	$documento .= "<br> <b>Tasa:</b> $tasa";
	$documento .= "<br> <b>Monto de:</b> $monto";

	?>
	  <section class="py-5 text-center container">
	    <div class="row py-lg-5">
	      <div class="col-lg-6 col-md-8 mx-auto">
	        <h2 class="fw-light">Desea Generar el <?php echo $documento; ?></h2>
	        <p class="lead text-muted">Este es un proceso irreversible; &iquest;Est&aacute; seguro de procear este reverso?</p>
			<div id="charNum"></div>
	        <div class="text-right"><textarea class="form-control-plaintext" rows="3" id="txtNota" name="txtNota" placeholder="Indique por qu&eacute; est&aacute; realizando esta reverso. M&iacute;nimo 20 caracteres..." onkeyup="countChars(this);" style="border-color:red;"></textarea></div>
	        </p>
	        <p>
	          <!--<a href="<?php echo "include/ProcesarReverso.php?id=" . $id; ?>" class="btn btn-primary my-2">Aceptar</a>-->
	           <button class="btn btn-primary my-2" onclick="js:EnviarReverso2(<?php echo $id ?>)">Aceptar</button>
	          <a href="<?php echo "Abono2List"; ?>" class="btn btn-secondary my-2">Cancelar</a>
	        </p>
	      </div>
	    </div>
	  </section>
	<?php
	break;
}

?>
<script>
function countChars(obj){
	if(obj.value.length < 20)
		document.getElementById("charNum").innerHTML = '<b><font color="red">' + obj.value.length+' caracteres</font></b>';
	else
		document.getElementById("charNum").innerHTML = '<b><font color="green">' + obj.value.length+' caracteres</font></b>';	
}

function EnviarReverso(id) {
	// alert("include/ProcesarReverso.php?id=" + id + "&txtNota=" + document.getElementById("txtNota").value);
	window.location = "include/ProcesarReverso.php?id=" + id + "&txtNota=" + document.getElementById("txtNota").value;
}

function EnviarReverso2(id) {
	// alert("include/ProcesarReverso.php?id=" + id + "&txtNota=" + document.getElementById("txtNota").value);
	window.location = "include/ProcesarReverso2.php?id=" + id + "&txtNota=" + document.getElementById("txtNota").value;
}
</script>

<?= GetDebugMessage() ?>
