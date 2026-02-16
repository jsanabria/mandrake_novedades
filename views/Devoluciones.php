<?php

namespace PHPMaker2021\mandrake;

// Page object
$Devoluciones = &$Page;
?>
	<form id="frm" name="frm" method="post" action="DevolucionesBuscar">
<div class="row">
  <div class="col-lg-6">
    <div class="input-group">
      <input name="NotaEntrega" type="text" class="form-control" placeholder="Buscar Nota de Entrega...">
      <span class="input-group-btn">
        <input type="submit" id="Buscar" class="btn btn-default" type="button" value="Buscar!">
      </span>
    </div><!-- /input-group -->
  </div><!-- /.col-lg-6 -->
</div><!-- /.row -->
  	</form>
<?php
if (isset($_REQUEST["sw"])) {
	if($_REQUEST["sw"] == "1")	{
?>
	<br><br>
  	<div class="row">
      <div class="container">
      	<div class="alert alert-success" role="alert">
			Proceso Exitoso! Se ha generado un abono al cliente y el o los art&iacute;culos ingresaron al inventario con una Nota de Recepci&oacute;n. <br><br><b>Nota: </b>este proceso no anula la Nota de Recepci&oacute;n de origen y la misma no debe ser anulada.
		</div>
	  </div>
	 </div>
<?php
	}
}
?>




<?= GetDebugMessage() ?>
