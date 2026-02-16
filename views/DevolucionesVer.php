<?php

namespace PHPMaker2021\mandrake;

// Page object
$DevolucionesVer = &$Page;
?>
<?php
$id = isset($_POST["xNota"]) ? $_POST["xNota"] : "";

if($id == "") {
?>
<div class="container">
	<div class="alert alert-danger" role="alert">
		Debe seleccionar una nota de entrega para procesar la devoluci&oacute;n!
  	</div>
	<a href="Devoluciones" class="btn btn-default">Regresar</a>
</div>
<?php
}
else {

$sql = "SELECT a.id, 
			b.nombre, a.tipo_documento, a.nro_documento,
			date_format(a.fecha, '%d/%m/%Y') AS fecha,
			a.moneda, a.monto_total, a.email  
		FROM 
			salidas AS a 
			JOIN cliente AS b ON b.id = a.cliente 
		WHERE
			a.id = $id;";
$row = ExecuteRow($sql);
$id = $row["id"];
$tipo_documento = $row["tipo_documento"];
$cliente = $row["nombre"];
$nro_documento = $row["nro_documento"];
$fecha = $row["fecha"];
$moneda = $row["moneda"];
$monto_total = number_format($row["monto_total"], 2, ".", ",");
$devolucion = $row["email"];
if($devolucion == "DEVOLUCION") {
?>
	<script>
		alert("Alerta; esta nota de entrega se ha usado para realizar devoluciones. !Verifique para estar seguro de proceder!");
	</script>
<?php
}
?>
	<form id="frm" name="frm" method="post" action="DevolucionesGuardar">
<div class="container">
  <div class="mb-3 row">
  	<input type="hidden" name="id" value="<?php echo $id; ?>">
    <label for="staticEmail" class="col-sm-2 col-form-label">Cliente</label>
    <div class="col-sm-10">
      <input type="text" readonly class="form-control-plaintext" value="<?php echo $cliente; ?>">
    </div>
  </div>
  <div class="mb-3 row">
    <label for="" class="col-sm-2 col-form-label">Nro. Documento</label>
    <div class="col-sm-10">
      <input type="text" readonly class="form-control-plaintext" value="<?php echo $tipo_documento . "; " . $nro_documento; ?>">
    </div>
  </div>
  <div class="mb-3 row">
    <label for="" class="col-sm-2 col-form-label">Fecha</label>
    <div class="col-sm-10">
      <input type="text" readonly class="form-control-plaintext" value="<?php echo $fecha; ?>">
    </div>
  </div>
  <div class="mb-3 row">
    <label for="" class="col-sm-2 col-form-label">Moneda</label>
    <div class="col-sm-10">
      <input type="text" readonly class="form-control-plaintext" value="<?php echo $moneda; ?>">
    </div>
  </div>
  <div class="mb-3 row">
    <label for="" class="col-sm-2 col-form-label">Monto</label>
    <div class="col-sm-10">
      <input type="text" readonly class="form-control-plaintext" value="<?php echo $monto_total; ?>">
    </div>
  </div>


	<table class="table table-bordered">
		<thead>
			<tr>
				<th width="10%">
					&nbsp;
				</th>
				<th width="50%">
					Art&iacute;culo
				</th>
				<th width="10%">
					Cantidad
				</th>
				<th width="15%">
					Precio Unidad
				</th>
				<th width="15%">
					Precio
				</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$sql = "SELECT
					a.id,
					a.articulo, 
					b.principio_activo,
					ABS(a.cantidad_movimiento) AS cantidad_movimiento,
					a.precio_unidad, a.precio   
				FROM 
					entradas_salidas AS a 
					JOIN articulo AS b ON b.id = a.articulo  
				WHERE a.tipo_documento = 'TDCNET' AND a.id_documento = $id
				ORDER BY a.articulo DESC;";
		$rows = ExecuteRows($sql);
		$i = 1;
		$xCant = 0;
		foreach ($rows as $key => $value) {
			//////////
			$sql = "SELECT 
						SUM(IFNULL(a.cantidad_movimiento, 0)) AS cantidad 
					FROM 
						entradas_salidas AS a 
						JOIN entradas AS b ON b.id = a.id_documento AND b.tipo_documento = a.tipo_documento 
					WHERE 
						b.id_documento_padre = $id AND b.tipo_documento = 'TDCNRP' AND a.articulo = " . $value["articulo"] . ";";
			$xCant = intval($value["cantidad_movimiento"]) - intval(ExecuteScalar($sql));
			//////////
			?>
			<tr>
				<td>
					<input type="checkbox" name="x<?php echo $i; ?>_Articulo" id="x<?php echo $i; ?>_Articulo" value="<?php echo $value["articulo"]; ?>" <?= ($xCant<=0?'disabled="disabled"':'') ?>>
				</td>
				<td>
					<?php echo $value["principio_activo"]; ?>
				</td>
				<td>
					<input type="number" class="form-control" name="x<?php echo $i; ?>_Cantidad" id="x<?php echo $i; ?>_Cantidad" size="6" value="<?php echo $xCant; ?>" onkeyup="js: ValCant(this.value, <?= $xCant ?>, <?= $i ?>)">
				</td>
				<td>
					<input type="text" readonly class="form-control" name="x<?php echo $i; ?>_Costo" id="x<?php echo $i; ?>_Costo" size="6" value="<?php echo number_format($value["precio_unidad"], 2, ".", ","); ?>">
				</td>
				<td>
					<?php echo number_format($value["precio"], 2, ".", ","); ?>
				</td>
			</tr>
			<?php
			$i++;
		}
		$i = count($rows);
		?>
			<tr>
				<td class="text-center">
					<div id="charNum"></div>
				</td>
				<td class="text-center">
					<div class="text-right"><b><font color="red">* </font>Nota:</b></div>
				</td>
				<td class="text-center" colspan="3">
					<textarea class="form-control-plaintext" rows="3" id="txtNota" name="txtNota" placeholder="Indique por qu&eacute; est&aacute; realizando esta devoluci&oacute;n. M&iacute;nimo 20 caracteres..." onkeyup="countChars(this);" style="border-color:red;"></textarea>					
				</td>
			</tr>
			<tr>
				<td class="text-center" colspan="5">
					<input type="hidden" name="cantidad" value="<?php echo $i; ?>">
					<input type="submit" class="btn btn-default" type="button" id="procesar" value="Procesar Devoluci&oacute;n">
					&nbsp;
					<a href="Devoluciones" class="btn btn-default">Regresar</a>
				</td>
			</tr>
		</tbody>
	</table>
</div>
	</form>
<?php
}
?>
<script>
function countChars(obj){
	if(obj.value.length < 20)
		document.getElementById("charNum").innerHTML = '<b><font color="red">' + obj.value.length+' caracteres</font></b>';
	else
		document.getElementById("charNum").innerHTML = '<b><font color="green">' + obj.value.length+' caracteres</font></b>';	
}

function ValCant(i, j, index) {
	if(i>j) {
		alert("La cantidad es mayor a lo entregado!");
		$("#x" + index + "_Cantidad").value(j);
	}
}
</script>


<?= GetDebugMessage() ?>
