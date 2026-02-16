<?php

namespace PHPMaker2021\mandrake;

// Page object
$CompraView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fcompraview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fcompraview = currentForm = new ew.Form("fcompraview", "view");
    loadjs.done("fcompraview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.compra) ew.vars.tables.compra = <?= JsonEncode(GetClientVar("tables", "compra")) ?>;
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
<?php if (!$Page->IsModal) { ?>
<?php if (!$Page->isExport()) { ?>
<form name="ew-pager-form" class="form-inline ew-form ew-pager-form" action="<?= CurrentPageUrl(false) ?>">
<?= $Page->Pager->render() ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<?php } ?>
<form name="fcompraview" id="fcompraview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="compra">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (!$Page->isExport()) { ?>
<div class="ew-multi-page">
<div class="ew-nav-tabs" id="Page"><!-- multi-page tabs -->
    <ul class="<?= $Page->MultiPages->navStyle() ?>">
        <li class="nav-item"><a class="nav-link<?= $Page->MultiPages->pageStyle(1) ?>" href="#tab_compra1" data-toggle="tab"><?= $Page->pageCaption(1) ?></a></li>
        <li class="nav-item"><a class="nav-link<?= $Page->MultiPages->pageStyle(2) ?>" href="#tab_compra2" data-toggle="tab"><?= $Page->pageCaption(2) ?></a></li>
    </ul>
    <div class="tab-content">
<?php } ?>
<?php if (!$Page->isExport()) { ?>
        <div class="tab-pane<?= $Page->MultiPages->pageStyle(1) ?>" id="tab_compra1"><!-- multi-page .tab-pane -->
<?php } ?>
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->id->Visible) { // id ?>
    <tr id="r_id">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_compra_id"><?= $Page->id->caption() ?></span></td>
        <td data-name="id" <?= $Page->id->cellAttributes() ?>>
<span id="el_compra_id" data-page="1">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->proveedor->Visible) { // proveedor ?>
    <tr id="r_proveedor">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_compra_proveedor"><?= $Page->proveedor->caption() ?></span></td>
        <td data-name="proveedor" <?= $Page->proveedor->cellAttributes() ?>>
<span id="el_compra_proveedor" data-page="1">
<span<?= $Page->proveedor->viewAttributes() ?>>
<?= $Page->proveedor->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
    <tr id="r_tipo_documento">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_compra_tipo_documento"><?= $Page->tipo_documento->caption() ?></span></td>
        <td data-name="tipo_documento" <?= $Page->tipo_documento->cellAttributes() ?>>
<span id="el_compra_tipo_documento" data-page="1">
<span<?= $Page->tipo_documento->viewAttributes() ?>>
<?= $Page->tipo_documento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->doc_afectado->Visible) { // doc_afectado ?>
    <tr id="r_doc_afectado">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_compra_doc_afectado"><?= $Page->doc_afectado->caption() ?></span></td>
        <td data-name="doc_afectado" <?= $Page->doc_afectado->cellAttributes() ?>>
<span id="el_compra_doc_afectado" data-page="1">
<span<?= $Page->doc_afectado->viewAttributes() ?>>
<?= $Page->doc_afectado->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->documento->Visible) { // documento ?>
    <tr id="r_documento">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_compra_documento"><?= $Page->documento->caption() ?></span></td>
        <td data-name="documento" <?= $Page->documento->cellAttributes() ?>>
<span id="el_compra_documento" data-page="1">
<span<?= $Page->documento->viewAttributes() ?>>
<?= $Page->documento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nro_control->Visible) { // nro_control ?>
    <tr id="r_nro_control">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_compra_nro_control"><?= $Page->nro_control->caption() ?></span></td>
        <td data-name="nro_control" <?= $Page->nro_control->cellAttributes() ?>>
<span id="el_compra_nro_control" data-page="1">
<span<?= $Page->nro_control->viewAttributes() ?>>
<?= $Page->nro_control->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <tr id="r_fecha">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_compra_fecha"><?= $Page->fecha->caption() ?></span></td>
        <td data-name="fecha" <?= $Page->fecha->cellAttributes() ?>>
<span id="el_compra_fecha" data-page="1">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
    <tr id="r_descripcion">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_compra_descripcion"><?= $Page->descripcion->caption() ?></span></td>
        <td data-name="descripcion" <?= $Page->descripcion->cellAttributes() ?>>
<span id="el_compra_descripcion" data-page="1">
<span<?= $Page->descripcion->viewAttributes() ?>>
<?= $Page->descripcion->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha_registro->Visible) { // fecha_registro ?>
    <tr id="r_fecha_registro">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_compra_fecha_registro"><?= $Page->fecha_registro->caption() ?></span></td>
        <td data-name="fecha_registro" <?= $Page->fecha_registro->cellAttributes() ?>>
<span id="el_compra_fecha_registro" data-page="1">
<span<?= $Page->fecha_registro->viewAttributes() ?>>
<?= $Page->fecha_registro->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
    <tr id="r__username">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_compra__username"><?= $Page->_username->caption() ?></span></td>
        <td data-name="_username" <?= $Page->_username->cellAttributes() ?>>
<span id="el_compra__username" data-page="1">
<span<?= $Page->_username->viewAttributes() ?>>
<?= $Page->_username->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php if (!$Page->isExport()) { ?>
        </div>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
        <div class="tab-pane<?= $Page->MultiPages->pageStyle(2) ?>" id="tab_compra2"><!-- multi-page .tab-pane -->
<?php } ?>
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->monto_exento->Visible) { // monto_exento ?>
    <tr id="r_monto_exento">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_compra_monto_exento"><?= $Page->monto_exento->caption() ?></span></td>
        <td data-name="monto_exento" <?= $Page->monto_exento->cellAttributes() ?>>
<span id="el_compra_monto_exento" data-page="2">
<span<?= $Page->monto_exento->viewAttributes() ?>>
<?= $Page->monto_exento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_gravado->Visible) { // monto_gravado ?>
    <tr id="r_monto_gravado">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_compra_monto_gravado"><?= $Page->monto_gravado->caption() ?></span></td>
        <td data-name="monto_gravado" <?= $Page->monto_gravado->cellAttributes() ?>>
<span id="el_compra_monto_gravado" data-page="2">
<span<?= $Page->monto_gravado->viewAttributes() ?>>
<?= $Page->monto_gravado->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->alicuota->Visible) { // alicuota ?>
    <tr id="r_alicuota">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_compra_alicuota"><?= $Page->alicuota->caption() ?></span></td>
        <td data-name="alicuota" <?= $Page->alicuota->cellAttributes() ?>>
<span id="el_compra_alicuota" data-page="2">
<span<?= $Page->alicuota->viewAttributes() ?>>
<?= $Page->alicuota->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_iva->Visible) { // monto_iva ?>
    <tr id="r_monto_iva">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_compra_monto_iva"><?= $Page->monto_iva->caption() ?></span></td>
        <td data-name="monto_iva" <?= $Page->monto_iva->cellAttributes() ?>>
<span id="el_compra_monto_iva" data-page="2">
<span<?= $Page->monto_iva->viewAttributes() ?>>
<?= $Page->monto_iva->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_total->Visible) { // monto_total ?>
    <tr id="r_monto_total">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_compra_monto_total"><?= $Page->monto_total->caption() ?></span></td>
        <td data-name="monto_total" <?= $Page->monto_total->cellAttributes() ?>>
<span id="el_compra_monto_total" data-page="2">
<span<?= $Page->monto_total->viewAttributes() ?>>
<?= $Page->monto_total->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_pagar->Visible) { // monto_pagar ?>
    <tr id="r_monto_pagar">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_compra_monto_pagar"><?= $Page->monto_pagar->caption() ?></span></td>
        <td data-name="monto_pagar" <?= $Page->monto_pagar->cellAttributes() ?>>
<span id="el_compra_monto_pagar" data-page="2">
<span<?= $Page->monto_pagar->viewAttributes() ?>>
<?= $Page->monto_pagar->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php if (!$Page->isExport()) { ?>
        </div>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
    </div>
</div>
</div>
<?php } ?>
<?php if (!$Page->IsModal) { ?>
<?php if (!$Page->isExport()) { ?>
<?= $Page->Pager->render() ?>
<div class="clearfix"></div>
<?php } ?>
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
    	var tipo_documento = "COMPRA";
    	var username = "<?php echo CurrentUserName(); ?>";
    	var tipo_doc = $("#x_tipo_documento").val();
    	var regla = 1;
    	if(tipo_doc == "RC") regla = 6;
    	if(confirm("Seguro de contabilizar este comprobante?")) {
    		$.ajax({
    		  url : "../include/Generar_Comprobante_Contable.php",
    		  type: "GET",
    		  data : {id: id, tipo_documento: tipo_documento, regla: regla, username: username},
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
