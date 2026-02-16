<?php

namespace PHPMaker2021\mandrake;

// Page object
$ContComprobanteView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fcont_comprobanteview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fcont_comprobanteview = currentForm = new ew.Form("fcont_comprobanteview", "view");
    loadjs.done("fcont_comprobanteview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.cont_comprobante) ew.vars.tables.cont_comprobante = <?= JsonEncode(GetClientVar("tables", "cont_comprobante")) ?>;
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
<form name="fcont_comprobanteview" id="fcont_comprobanteview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cont_comprobante">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->id->Visible) { // id ?>
    <tr id="r_id">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_comprobante_id"><?= $Page->id->caption() ?></span></td>
        <td data-name="id" <?= $Page->id->cellAttributes() ?>>
<span id="el_cont_comprobante_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tipo->Visible) { // tipo ?>
    <tr id="r_tipo">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_comprobante_tipo"><?= $Page->tipo->caption() ?></span></td>
        <td data-name="tipo" <?= $Page->tipo->cellAttributes() ?>>
<span id="el_cont_comprobante_tipo">
<span<?= $Page->tipo->viewAttributes() ?>>
<?= $Page->tipo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <tr id="r_fecha">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_comprobante_fecha"><?= $Page->fecha->caption() ?></span></td>
        <td data-name="fecha" <?= $Page->fecha->cellAttributes() ?>>
<span id="el_cont_comprobante_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
    <tr id="r_descripcion">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_comprobante_descripcion"><?= $Page->descripcion->caption() ?></span></td>
        <td data-name="descripcion" <?= $Page->descripcion->cellAttributes() ?>>
<span id="el_cont_comprobante_descripcion">
<span<?= $Page->descripcion->viewAttributes() ?>>
<?= $Page->descripcion->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->contabilizacion->Visible) { // contabilizacion ?>
    <tr id="r_contabilizacion">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_comprobante_contabilizacion"><?= $Page->contabilizacion->caption() ?></span></td>
        <td data-name="contabilizacion" <?= $Page->contabilizacion->cellAttributes() ?>>
<span id="el_cont_comprobante_contabilizacion">
<span<?= $Page->contabilizacion->viewAttributes() ?>>
<?= $Page->contabilizacion->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->registra->Visible) { // registra ?>
    <tr id="r_registra">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_comprobante_registra"><?= $Page->registra->caption() ?></span></td>
        <td data-name="registra" <?= $Page->registra->cellAttributes() ?>>
<span id="el_cont_comprobante_registra">
<span<?= $Page->registra->viewAttributes() ?>>
<?= $Page->registra->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha_registro->Visible) { // fecha_registro ?>
    <tr id="r_fecha_registro">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_comprobante_fecha_registro"><?= $Page->fecha_registro->caption() ?></span></td>
        <td data-name="fecha_registro" <?= $Page->fecha_registro->cellAttributes() ?>>
<span id="el_cont_comprobante_fecha_registro">
<span<?= $Page->fecha_registro->viewAttributes() ?>>
<?= $Page->fecha_registro->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->contabiliza->Visible) { // contabiliza ?>
    <tr id="r_contabiliza">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_comprobante_contabiliza"><?= $Page->contabiliza->caption() ?></span></td>
        <td data-name="contabiliza" <?= $Page->contabiliza->cellAttributes() ?>>
<span id="el_cont_comprobante_contabiliza">
<span<?= $Page->contabiliza->viewAttributes() ?>>
<?= $Page->contabiliza->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha_contabiliza->Visible) { // fecha_contabiliza ?>
    <tr id="r_fecha_contabiliza">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_comprobante_fecha_contabiliza"><?= $Page->fecha_contabiliza->caption() ?></span></td>
        <td data-name="fecha_contabiliza" <?= $Page->fecha_contabiliza->cellAttributes() ?>>
<span id="el_cont_comprobante_fecha_contabiliza">
<span<?= $Page->fecha_contabiliza->viewAttributes() ?>>
<?= $Page->fecha_contabiliza->getViewValue() ?></span>
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
    if (in_array("cont_asiento", explode(",", $Page->getCurrentDetailTable())) && $cont_asiento->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("cont_asiento", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "ContAsientoGrid.php" ?>
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
    	var username = "<?php echo CurrentUserName(); ?>";
    	if(confirm("Seguro de contabilizar este comprobante?")) {
    		$.ajax({
    		  url : "../include/Contabilizar_Procesar.php",
    		  type: "GET",
    		  data : {id: id, username: username},
    		  beforeSend: function(){
    		    $("#result").html("Por Favor Espere. . .");
    		  }
    		})
    		.done(function(data) {
    			//alert(data);
    			var rs = '<div class="alert alert-success" role="alert">Este Comprobante est&aacute; Contabilizado</div>';
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
    $("#btnDescont").click(function(){
        var id = <?php echo CurrentPage()->id->CurrentValue; ?>;
    	var username = "<?php echo CurrentUserName(); ?>";
    	if(confirm("Seguro que desea descontabilizar este comprobante?")) {
    		$.ajax({
    		  url : "../include/Descontabilizar_Procesar.php",
    		  type: "GET",
    		  data : {id: id, username: username},
    		  beforeSend: function(){
    		    $("#result").html("Por Favor Espere. . .");
    		  }
    		})
    		.done(function(data) {
    			//alert(data);
    			if(data == 1) {
    				alert("Documento descontabilizado !!! EXITOSAMENTE !!!");
    				location.reload();
    			}
    			else {
    				alert("Periodo o mes contable !!! CERRADO !!! no se puede realizar la acción");
    			}
    			//$("#result").html(rs);
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
