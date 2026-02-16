<?php

namespace PHPMaker2021\mandrake;

// Page object
$ReimprimirFacturaBuscarListar = &$Page;
?>
<?php
$FacturaFiscal = $_POST["FacturaFiscal"];
$FacturaFiscal = str_pad($FacturaFiscal, 8, "0", STR_PAD_LEFT);
?>
	<form id="frm" name="frm" method="post" action="ReimprimirFacturaVer">
<div class="container">
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>
					&nbsp;
				</th>
				<th>
					Nro. Documento
				</th>
				<th>
					Tipo
				</th>
				<th>
					Fecha
				</th>
				<th>
					Cliente
				</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$sql = "SELECT a.id, 
					b.nombre, a.tipo_documento, a.nro_documento,
					date_format(a.fecha, '%d/%m/%Y') AS fecha,
					a.moneda, a.monto_total, a.documento 
				FROM 
					salidas AS a 
					JOIN cliente AS b ON b.id = a.cliente 
				WHERE
					a.tipo_documento = 'TDCFCV' AND a.nro_documento LIKE '%$FacturaFiscal'
				ORDER BY a.id DESC"; 
		$rows = ExecuteRows($sql);
		foreach ($rows as $key => $value) {
			?>
			<tr>
				<td>
					<input type="radio" name="xFactura" id="xFactura" value="<?php echo $value["id"]; ?>">
				</td>
				<td>
					<?php echo $value["nro_documento"]; ?>
				</td>
				<td>
					<?php echo $value["documento"]; ?>
				</td>
				<td>
					<?php echo $value["fecha"]; ?>
				</td>
				<td>
					<?php echo $value["nombre"]; ?>
				</td>
			</tr>
			<?php
		}
		?>
			<tr>
				<td class="text-center" colspan="5">
					<input type="submit" class="btn btn-default" type="button" value="Ver Factura">
					&nbsp;
					<input type="button" class="btn btn-default" type="button" value="Regresar" onclick="js: history.back();"> 
				</td>
			</tr>
		</tbody>
	</table>
</div>
	</form>

<?= GetDebugMessage() ?>
