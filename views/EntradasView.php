<?php

namespace PHPMaker2021\mandrake;

// Page object
$EntradasView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fentradasview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fentradasview = currentForm = new ew.Form("fentradasview", "view");
    loadjs.done("fentradasview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.entradas) ew.vars.tables.entradas = <?= JsonEncode(GetClientVar("tables", "entradas")) ?>;
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
<form name="fentradasview" id="fentradasview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="entradas">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
    <tr id="r_tipo_documento">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_tipo_documento"><?= $Page->tipo_documento->caption() ?></span></td>
        <td data-name="tipo_documento" <?= $Page->tipo_documento->cellAttributes() ?>>
<span id="el_entradas_tipo_documento">
<span<?= $Page->tipo_documento->viewAttributes() ?>>
<?= $Page->tipo_documento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
    <tr id="r_nro_documento">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_nro_documento"><?= $Page->nro_documento->caption() ?></span></td>
        <td data-name="nro_documento" <?= $Page->nro_documento->cellAttributes() ?>>
<span id="el_entradas_nro_documento">
<span<?= $Page->nro_documento->viewAttributes() ?>>
<?= $Page->nro_documento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nro_control->Visible) { // nro_control ?>
    <tr id="r_nro_control">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_nro_control"><?= $Page->nro_control->caption() ?></span></td>
        <td data-name="nro_control" <?= $Page->nro_control->cellAttributes() ?>>
<span id="el_entradas_nro_control">
<span<?= $Page->nro_control->viewAttributes() ?>>
<?= $Page->nro_control->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <tr id="r_fecha">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_fecha"><?= $Page->fecha->caption() ?></span></td>
        <td data-name="fecha" <?= $Page->fecha->cellAttributes() ?>>
<span id="el_entradas_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->proveedor->Visible) { // proveedor ?>
    <tr id="r_proveedor">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_proveedor"><?= $Page->proveedor->caption() ?></span></td>
        <td data-name="proveedor" <?= $Page->proveedor->cellAttributes() ?>>
<span id="el_entradas_proveedor">
<span<?= $Page->proveedor->viewAttributes() ?>>
<?= $Page->proveedor->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_total->Visible) { // monto_total ?>
    <tr id="r_monto_total">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_monto_total"><?= $Page->monto_total->caption() ?></span></td>
        <td data-name="monto_total" <?= $Page->monto_total->cellAttributes() ?>>
<span id="el_entradas_monto_total">
<span<?= $Page->monto_total->viewAttributes() ?>>
<?= $Page->monto_total->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->alicuota_iva->Visible) { // alicuota_iva ?>
    <tr id="r_alicuota_iva">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_alicuota_iva"><?= $Page->alicuota_iva->caption() ?></span></td>
        <td data-name="alicuota_iva" <?= $Page->alicuota_iva->cellAttributes() ?>>
<span id="el_entradas_alicuota_iva">
<span<?= $Page->alicuota_iva->viewAttributes() ?>>
<?= $Page->alicuota_iva->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->iva->Visible) { // iva ?>
    <tr id="r_iva">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_iva"><?= $Page->iva->caption() ?></span></td>
        <td data-name="iva" <?= $Page->iva->cellAttributes() ?>>
<span id="el_entradas_iva">
<span<?= $Page->iva->viewAttributes() ?>>
<?= $Page->iva->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
    <tr id="r_total">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_total"><?= $Page->total->caption() ?></span></td>
        <td data-name="total" <?= $Page->total->cellAttributes() ?>>
<span id="el_entradas_total">
<span<?= $Page->total->viewAttributes() ?>>
<?= $Page->total->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->documento->Visible) { // documento ?>
    <tr id="r_documento">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_documento"><?= $Page->documento->caption() ?></span></td>
        <td data-name="documento" <?= $Page->documento->cellAttributes() ?>>
<span id="el_entradas_documento">
<span<?= $Page->documento->viewAttributes() ?>>
<?= $Page->documento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->doc_afectado->Visible) { // doc_afectado ?>
    <tr id="r_doc_afectado">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_doc_afectado"><?= $Page->doc_afectado->caption() ?></span></td>
        <td data-name="doc_afectado" <?= $Page->doc_afectado->cellAttributes() ?>>
<span id="el_entradas_doc_afectado">
<span<?= $Page->doc_afectado->viewAttributes() ?>>
<?= $Page->doc_afectado->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
    <tr id="r_nota">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_nota"><?= $Page->nota->caption() ?></span></td>
        <td data-name="nota" <?= $Page->nota->cellAttributes() ?>>
<span id="el_entradas_nota">
<span<?= $Page->nota->viewAttributes() ?>>
<?= $Page->nota->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->estatus->Visible) { // estatus ?>
    <tr id="r_estatus">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_estatus"><?= $Page->estatus->caption() ?></span></td>
        <td data-name="estatus" <?= $Page->estatus->cellAttributes() ?>>
<span id="el_entradas_estatus">
<span<?= $Page->estatus->viewAttributes() ?>>
<?= $Page->estatus->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
    <tr id="r__username">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas__username"><?= $Page->_username->caption() ?></span></td>
        <td data-name="_username" <?= $Page->_username->cellAttributes() ?>>
<span id="el_entradas__username">
<span<?= $Page->_username->viewAttributes() ?>>
<?= $Page->_username->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <tr id="r_moneda">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_moneda"><?= $Page->moneda->caption() ?></span></td>
        <td data-name="moneda" <?= $Page->moneda->cellAttributes() ?>>
<span id="el_entradas_moneda">
<span<?= $Page->moneda->viewAttributes() ?>>
<?= $Page->moneda->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->aplica_retencion->Visible) { // aplica_retencion ?>
    <tr id="r_aplica_retencion">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_aplica_retencion"><?= $Page->aplica_retencion->caption() ?></span></td>
        <td data-name="aplica_retencion" <?= $Page->aplica_retencion->cellAttributes() ?>>
<span id="el_entradas_aplica_retencion">
<span<?= $Page->aplica_retencion->viewAttributes() ?>>
<?= $Page->aplica_retencion->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ret_iva->Visible) { // ret_iva ?>
    <tr id="r_ret_iva">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_ret_iva"><?= $Page->ret_iva->caption() ?></span></td>
        <td data-name="ret_iva" <?= $Page->ret_iva->cellAttributes() ?>>
<span id="el_entradas_ret_iva">
<span<?= $Page->ret_iva->viewAttributes() ?>>
<?= $Page->ret_iva->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ref_iva->Visible) { // ref_iva ?>
    <tr id="r_ref_iva">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_ref_iva"><?= $Page->ref_iva->caption() ?></span></td>
        <td data-name="ref_iva" <?= $Page->ref_iva->cellAttributes() ?>>
<span id="el_entradas_ref_iva">
<span<?= $Page->ref_iva->viewAttributes() ?>>
<?php if (!EmptyString($Page->ref_iva->getViewValue()) && $Page->ref_iva->linkAttributes() != "") { ?>
<a<?= $Page->ref_iva->linkAttributes() ?>><?= $Page->ref_iva->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->ref_iva->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ret_islr->Visible) { // ret_islr ?>
    <tr id="r_ret_islr">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_ret_islr"><?= $Page->ret_islr->caption() ?></span></td>
        <td data-name="ret_islr" <?= $Page->ret_islr->cellAttributes() ?>>
<span id="el_entradas_ret_islr">
<span<?= $Page->ret_islr->viewAttributes() ?>>
<?= $Page->ret_islr->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ref_islr->Visible) { // ref_islr ?>
    <tr id="r_ref_islr">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_ref_islr"><?= $Page->ref_islr->caption() ?></span></td>
        <td data-name="ref_islr" <?= $Page->ref_islr->cellAttributes() ?>>
<span id="el_entradas_ref_islr">
<span<?= $Page->ref_islr->viewAttributes() ?>>
<?php if (!EmptyString($Page->ref_islr->getViewValue()) && $Page->ref_islr->linkAttributes() != "") { ?>
<a<?= $Page->ref_islr->linkAttributes() ?>><?= $Page->ref_islr->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->ref_islr->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ret_municipal->Visible) { // ret_municipal ?>
    <tr id="r_ret_municipal">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_ret_municipal"><?= $Page->ret_municipal->caption() ?></span></td>
        <td data-name="ret_municipal" <?= $Page->ret_municipal->cellAttributes() ?>>
<span id="el_entradas_ret_municipal">
<span<?= $Page->ret_municipal->viewAttributes() ?>>
<?= $Page->ret_municipal->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ref_municipal->Visible) { // ref_municipal ?>
    <tr id="r_ref_municipal">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_ref_municipal"><?= $Page->ref_municipal->caption() ?></span></td>
        <td data-name="ref_municipal" <?= $Page->ref_municipal->cellAttributes() ?>>
<span id="el_entradas_ref_municipal">
<span<?= $Page->ref_municipal->viewAttributes() ?>>
<?= $Page->ref_municipal->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_pagar->Visible) { // monto_pagar ?>
    <tr id="r_monto_pagar">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_monto_pagar"><?= $Page->monto_pagar->caption() ?></span></td>
        <td data-name="monto_pagar" <?= $Page->monto_pagar->cellAttributes() ?>>
<span id="el_entradas_monto_pagar">
<span<?= $Page->monto_pagar->viewAttributes() ?>>
<?= $Page->monto_pagar->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tipo_iva->Visible) { // tipo_iva ?>
    <tr id="r_tipo_iva">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_tipo_iva"><?= $Page->tipo_iva->caption() ?></span></td>
        <td data-name="tipo_iva" <?= $Page->tipo_iva->cellAttributes() ?>>
<span id="el_entradas_tipo_iva">
<span<?= $Page->tipo_iva->viewAttributes() ?>>
<?= $Page->tipo_iva->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tipo_islr->Visible) { // tipo_islr ?>
    <tr id="r_tipo_islr">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_tipo_islr"><?= $Page->tipo_islr->caption() ?></span></td>
        <td data-name="tipo_islr" <?= $Page->tipo_islr->cellAttributes() ?>>
<span id="el_entradas_tipo_islr">
<span<?= $Page->tipo_islr->viewAttributes() ?>>
<?= $Page->tipo_islr->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->sustraendo->Visible) { // sustraendo ?>
    <tr id="r_sustraendo">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_sustraendo"><?= $Page->sustraendo->caption() ?></span></td>
        <td data-name="sustraendo" <?= $Page->sustraendo->cellAttributes() ?>>
<span id="el_entradas_sustraendo">
<span<?= $Page->sustraendo->viewAttributes() ?>>
<?= $Page->sustraendo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tasa_dia->Visible) { // tasa_dia ?>
    <tr id="r_tasa_dia">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_tasa_dia"><?= $Page->tasa_dia->caption() ?></span></td>
        <td data-name="tasa_dia" <?= $Page->tasa_dia->cellAttributes() ?>>
<span id="el_entradas_tasa_dia">
<span<?= $Page->tasa_dia->viewAttributes() ?>>
<?= $Page->tasa_dia->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_usd->Visible) { // monto_usd ?>
    <tr id="r_monto_usd">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_monto_usd"><?= $Page->monto_usd->caption() ?></span></td>
        <td data-name="monto_usd" <?= $Page->monto_usd->cellAttributes() ?>>
<span id="el_entradas_monto_usd">
<span<?= $Page->monto_usd->viewAttributes() ?>>
<?= $Page->monto_usd->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tipo_municipal->Visible) { // tipo_municipal ?>
    <tr id="r_tipo_municipal">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_tipo_municipal"><?= $Page->tipo_municipal->caption() ?></span></td>
        <td data-name="tipo_municipal" <?= $Page->tipo_municipal->cellAttributes() ?>>
<span id="el_entradas_tipo_municipal">
<span<?= $Page->tipo_municipal->viewAttributes() ?>>
<?= $Page->tipo_municipal->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
    <tr id="r_cliente">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_cliente"><?= $Page->cliente->caption() ?></span></td>
        <td data-name="cliente" <?= $Page->cliente->cellAttributes() ?>>
<span id="el_entradas_cliente">
<span<?= $Page->cliente->viewAttributes() ?>>
<?= $Page->cliente->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->descuento->Visible) { // descuento ?>
    <tr id="r_descuento">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_descuento"><?= $Page->descuento->caption() ?></span></td>
        <td data-name="descuento" <?= $Page->descuento->cellAttributes() ?>>
<span id="el_entradas_descuento">
<span<?= $Page->descuento->viewAttributes() ?>>
<?= $Page->descuento->getViewValue() ?></span>
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
    if (in_array("entradas_salidas", explode(",", $Page->getCurrentDetailTable())) && $entradas_salidas->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("entradas_salidas", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "EntradasSalidasGrid.php" ?>
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
    $(document).ready(function() {
    	$(".ewActionOption").hide(); 
    });
    $("#cmbContab").click(function(){
        var id = <?php echo $_REQUEST["id"]; ?>;
    	// 23-02-2022 - Junior Sanabria
    	// Agrego tipo_documento para poder contabilizar.
    	//Se le agregó ese parámetro a la clase Generar_Comprobante_Contable.php
    	// en la carpeta include
    	var tipo_documento = "<?php echo CurrentPage()->tipo_documento->CurrentValue; ?>";
    	var username = "<?php echo CurrentUserName(); ?>";
    	if(confirm("Seguro de contabilizar este comprobante?")) {
    		$.ajax({
    		  url : "include/Generar_Comprobante_Contable.php",
    		  type: "GET",
    		  data : {id: id, tipo_documento: tipo_documento, regla: 2, username: username},
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
    				rs = '<div class="alert alert-success" role="alert">Se Gener&oacute; Comprobante Contable # ' + data + ' Exitosamente.</div>';
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
