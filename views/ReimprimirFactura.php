<?php

namespace PHPMaker2021\mandrake;

// Page object
$ReimprimirFactura = &$Page;
?>
	<form id="frm" name="frm" method="post" action="ReimprimirFacturaBuscarListar">
<div class="row">
  <div class="col-lg-6">
    <div class="input-group">
      <input name="FacturaFiscal" type="text" class="form-control" placeholder="Buscar Factura Fiscal...">
      <span class="input-group-btn">
        <input type="submit" id="Buscar" class="btn btn-default" type="button" value="Buscar!">
      </span>
    </div><!-- /input-group -->
  </div><!-- /.col-lg-6 -->
</div><!-- /.row -->
  	</form>

<?= GetDebugMessage() ?>
