<?php

namespace PHPMaker2021\mandrake;

// Page object
$CobrosClienteView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fcobros_clienteview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fcobros_clienteview = currentForm = new ew.Form("fcobros_clienteview", "view");
    loadjs.done("fcobros_clienteview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.cobros_cliente) ew.vars.tables.cobros_cliente = <?= JsonEncode(GetClientVar("tables", "cobros_cliente")) ?>;
</script>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php $Page->ExportOptions->render("body") ?>
<?php $Page->OtherOptions->render("body") ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fcobros_clienteview" id="fcobros_clienteview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cobros_cliente">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->cliente->Visible) { // cliente ?>
    <tr id="r_cliente">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cobros_cliente_cliente"><?= $Page->cliente->caption() ?></span></td>
        <td data-name="cliente" <?= $Page->cliente->cellAttributes() ?>>
<span id="el_cobros_cliente_cliente">
<span<?= $Page->cliente->viewAttributes() ?>>
<?= $Page->cliente->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->id_documento->Visible) { // id_documento ?>
    <tr id="r_id_documento">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cobros_cliente_id_documento"><?= $Page->id_documento->caption() ?></span></td>
        <td data-name="id_documento" <?= $Page->id_documento->cellAttributes() ?>>
<span id="el_cobros_cliente_id_documento">
<span<?= $Page->id_documento->viewAttributes() ?>>
<?= $Page->id_documento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <tr id="r_fecha">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cobros_cliente_fecha"><?= $Page->fecha->caption() ?></span></td>
        <td data-name="fecha" <?= $Page->fecha->cellAttributes() ?>>
<span id="el_cobros_cliente_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <tr id="r_moneda">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cobros_cliente_moneda"><?= $Page->moneda->caption() ?></span></td>
        <td data-name="moneda" <?= $Page->moneda->cellAttributes() ?>>
<span id="el_cobros_cliente_moneda">
<span<?= $Page->moneda->viewAttributes() ?>>
<?= $Page->moneda->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->pago->Visible) { // pago ?>
    <tr id="r_pago">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cobros_cliente_pago"><?= $Page->pago->caption() ?></span></td>
        <td data-name="pago" <?= $Page->pago->cellAttributes() ?>>
<span id="el_cobros_cliente_pago">
<span<?= $Page->pago->viewAttributes() ?>>
<?= $Page->pago->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
    <tr id="r_nota">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cobros_cliente_nota"><?= $Page->nota->caption() ?></span></td>
        <td data-name="nota" <?= $Page->nota->cellAttributes() ?>>
<span id="el_cobros_cliente_nota">
<span<?= $Page->nota->viewAttributes() ?>>
<?= $Page->nota->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha_registro->Visible) { // fecha_registro ?>
    <tr id="r_fecha_registro">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cobros_cliente_fecha_registro"><?= $Page->fecha_registro->caption() ?></span></td>
        <td data-name="fecha_registro" <?= $Page->fecha_registro->cellAttributes() ?>>
<span id="el_cobros_cliente_fecha_registro">
<span<?= $Page->fecha_registro->viewAttributes() ?>>
<?= $Page->fecha_registro->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
    <tr id="r__username">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cobros_cliente__username"><?= $Page->_username->caption() ?></span></td>
        <td data-name="_username" <?= $Page->_username->cellAttributes() ?>>
<span id="el_cobros_cliente__username">
<span<?= $Page->_username->viewAttributes() ?>>
<?= $Page->_username->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php if (!$Page->IsModal) { ?>
<?php if (!$Page->isExport()) { ?>
<?= $Page->Pager->render() ?>
<div class="clearfix"></div>
<?php } ?>
<?php } ?>
<?php
    if (in_array("cobros_cliente_detalle", explode(",", $Page->getCurrentDetailTable())) && $cobros_cliente_detalle->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("cobros_cliente_detalle", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "CobrosClienteDetalleGrid.php" ?>
<?php } ?>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
loadjs.ready("load", function () {
    // Startup script
    // Write your table-specific startup script here
    // document.write("page loaded");
    $("#cmbContab").click(function(){
       	var id = <?php echo CurrentPage()->id->CurrentValue; ?>;
    	// 23-02-2022 - Junior Sanabria
    	// Agrego tipo_documento para poder contabilizar.
    	//Se le agregó ese parámetro a la clase Generar_Comprobante_Contable.php
    	// en la carpeta include
    	var tipo_documento = "COBROS";
    	var username = "<?php echo CurrentUserName(); ?>";
    	if(confirm("Seguro de contabilizar este comprobante?")) {
    		$.ajax({
    		  url : "../include/Generar_Comprobante_Contable.php",
    		  type: "GET",
    		  data : {id: id, tipo_documento: tipo_documento, regla: 5, username: username},
    		  beforeSend: function(){
    		    $("#result").html("Por Favor Espere. . .");
    		  }
    		})
    		.done(function(data) {
    			//alert(data);
    			var rs = '';
    			if(data == "0")
    				rs = '<div class="alert alert-danger" role="alert">No se Gener&oacute; Comprobante Contable. Periodo contable cerrado o no hay reglas de contabilizaci&oacute;n definidas.</div>';
    			else 
    				rs = '<div class="alert alert-success" role="alert">Se Gener&oacute; Comprobante Contable # ' + data + '. </div>';
    			$("#result").html(rs);
    		})
    		.fail(function(data) {
    			alert( "error" + data );
    		})
    		.always(function(data) {
    			//alert( "complete" );
    			//$("#result").html("Espere. . . ");
    		});
    	}
    });
});
</script>
<?php } ?>
