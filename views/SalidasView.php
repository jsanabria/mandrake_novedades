<?php

namespace PHPMaker2021\mandrake;

// Page object
$SalidasView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fsalidasview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fsalidasview = currentForm = new ew.Form("fsalidasview", "view");
    loadjs.done("fsalidasview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.salidas) ew.vars.tables.salidas = <?= JsonEncode(GetClientVar("tables", "salidas")) ?>;
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
<form name="fsalidasview" id="fsalidasview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="salidas">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
    <tr id="r_tipo_documento">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_tipo_documento"><?= $Page->tipo_documento->caption() ?></span></td>
        <td data-name="tipo_documento" <?= $Page->tipo_documento->cellAttributes() ?>>
<span id="el_salidas_tipo_documento">
<span<?= $Page->tipo_documento->viewAttributes() ?>>
<?= $Page->tipo_documento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
    <tr id="r_nro_documento">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_nro_documento"><?= $Page->nro_documento->caption() ?></span></td>
        <td data-name="nro_documento" <?= $Page->nro_documento->cellAttributes() ?>>
<span id="el_salidas_nro_documento">
<span<?= $Page->nro_documento->viewAttributes() ?>>
<?= $Page->nro_documento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nro_control->Visible) { // nro_control ?>
    <tr id="r_nro_control">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_nro_control"><?= $Page->nro_control->caption() ?></span></td>
        <td data-name="nro_control" <?= $Page->nro_control->cellAttributes() ?>>
<span id="el_salidas_nro_control">
<span<?= $Page->nro_control->viewAttributes() ?>>
<?= $Page->nro_control->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <tr id="r_fecha">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_fecha"><?= $Page->fecha->caption() ?></span></td>
        <td data-name="fecha" <?= $Page->fecha->cellAttributes() ?>>
<span id="el_salidas_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
    <tr id="r_cliente">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_cliente"><?= $Page->cliente->caption() ?></span></td>
        <td data-name="cliente" <?= $Page->cliente->cellAttributes() ?>>
<span id="el_salidas_cliente">
<span<?= $Page->cliente->viewAttributes() ?>>
<?= $Page->cliente->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->documento->Visible) { // documento ?>
    <tr id="r_documento">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_documento"><?= $Page->documento->caption() ?></span></td>
        <td data-name="documento" <?= $Page->documento->cellAttributes() ?>>
<span id="el_salidas_documento">
<span<?= $Page->documento->viewAttributes() ?>>
<?= $Page->documento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->doc_afectado->Visible) { // doc_afectado ?>
    <tr id="r_doc_afectado">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_doc_afectado"><?= $Page->doc_afectado->caption() ?></span></td>
        <td data-name="doc_afectado" <?= $Page->doc_afectado->cellAttributes() ?>>
<span id="el_salidas_doc_afectado">
<span<?= $Page->doc_afectado->viewAttributes() ?>>
<?= $Page->doc_afectado->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <tr id="r_moneda">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_moneda"><?= $Page->moneda->caption() ?></span></td>
        <td data-name="moneda" <?= $Page->moneda->cellAttributes() ?>>
<span id="el_salidas_moneda">
<span<?= $Page->moneda->viewAttributes() ?>>
<?= $Page->moneda->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_total->Visible) { // monto_total ?>
    <tr id="r_monto_total">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_monto_total"><?= $Page->monto_total->caption() ?></span></td>
        <td data-name="monto_total" <?= $Page->monto_total->cellAttributes() ?>>
<span id="el_salidas_monto_total">
<span<?= $Page->monto_total->viewAttributes() ?>>
<?= $Page->monto_total->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->alicuota_iva->Visible) { // alicuota_iva ?>
    <tr id="r_alicuota_iva">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_alicuota_iva"><?= $Page->alicuota_iva->caption() ?></span></td>
        <td data-name="alicuota_iva" <?= $Page->alicuota_iva->cellAttributes() ?>>
<span id="el_salidas_alicuota_iva">
<span<?= $Page->alicuota_iva->viewAttributes() ?>>
<?= $Page->alicuota_iva->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->iva->Visible) { // iva ?>
    <tr id="r_iva">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_iva"><?= $Page->iva->caption() ?></span></td>
        <td data-name="iva" <?= $Page->iva->cellAttributes() ?>>
<span id="el_salidas_iva">
<span<?= $Page->iva->viewAttributes() ?>>
<?= $Page->iva->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
    <tr id="r_total">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_total"><?= $Page->total->caption() ?></span></td>
        <td data-name="total" <?= $Page->total->cellAttributes() ?>>
<span id="el_salidas_total">
<span<?= $Page->total->viewAttributes() ?>>
<?= $Page->total->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tasa_dia->Visible) { // tasa_dia ?>
    <tr id="r_tasa_dia">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_tasa_dia"><?= $Page->tasa_dia->caption() ?></span></td>
        <td data-name="tasa_dia" <?= $Page->tasa_dia->cellAttributes() ?>>
<span id="el_salidas_tasa_dia">
<span<?= $Page->tasa_dia->viewAttributes() ?>>
<?= $Page->tasa_dia->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_usd->Visible) { // monto_usd ?>
    <tr id="r_monto_usd">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_monto_usd"><?= $Page->monto_usd->caption() ?></span></td>
        <td data-name="monto_usd" <?= $Page->monto_usd->cellAttributes() ?>>
<span id="el_salidas_monto_usd">
<span<?= $Page->monto_usd->viewAttributes() ?>>
<?= $Page->monto_usd->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->lista_pedido->Visible) { // lista_pedido ?>
    <tr id="r_lista_pedido">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_lista_pedido"><?= $Page->lista_pedido->caption() ?></span></td>
        <td data-name="lista_pedido" <?= $Page->lista_pedido->cellAttributes() ?>>
<span id="el_salidas_lista_pedido">
<span<?= $Page->lista_pedido->viewAttributes() ?>>
<?= $Page->lista_pedido->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
    <tr id="r__username">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas__username"><?= $Page->_username->caption() ?></span></td>
        <td data-name="_username" <?= $Page->_username->cellAttributes() ?>>
<span id="el_salidas__username">
<span<?= $Page->_username->viewAttributes() ?>>
<?= $Page->_username->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->estatus->Visible) { // estatus ?>
    <tr id="r_estatus">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_estatus"><?= $Page->estatus->caption() ?></span></td>
        <td data-name="estatus" <?= $Page->estatus->cellAttributes() ?>>
<span id="el_salidas_estatus">
<span<?= $Page->estatus->viewAttributes() ?>>
<?= $Page->estatus->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->asesor->Visible) { // asesor ?>
    <tr id="r_asesor">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_asesor"><?= $Page->asesor->caption() ?></span></td>
        <td data-name="asesor" <?= $Page->asesor->cellAttributes() ?>>
<span id="el_salidas_asesor">
<span<?= $Page->asesor->viewAttributes() ?>>
<?= $Page->asesor->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->dias_credito->Visible) { // dias_credito ?>
    <tr id="r_dias_credito">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_dias_credito"><?= $Page->dias_credito->caption() ?></span></td>
        <td data-name="dias_credito" <?= $Page->dias_credito->cellAttributes() ?>>
<span id="el_salidas_dias_credito">
<span<?= $Page->dias_credito->viewAttributes() ?>>
<?= $Page->dias_credito->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->entregado->Visible) { // entregado ?>
    <tr id="r_entregado">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_entregado"><?= $Page->entregado->caption() ?></span></td>
        <td data-name="entregado" <?= $Page->entregado->cellAttributes() ?>>
<span id="el_salidas_entregado">
<span<?= $Page->entregado->viewAttributes() ?>>
<?= $Page->entregado->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha_entrega->Visible) { // fecha_entrega ?>
    <tr id="r_fecha_entrega">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_fecha_entrega"><?= $Page->fecha_entrega->caption() ?></span></td>
        <td data-name="fecha_entrega" <?= $Page->fecha_entrega->cellAttributes() ?>>
<span id="el_salidas_fecha_entrega">
<span<?= $Page->fecha_entrega->viewAttributes() ?>>
<?= $Page->fecha_entrega->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->pagado->Visible) { // pagado ?>
    <tr id="r_pagado">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_pagado"><?= $Page->pagado->caption() ?></span></td>
        <td data-name="pagado" <?= $Page->pagado->cellAttributes() ?>>
<span id="el_salidas_pagado">
<span<?= $Page->pagado->viewAttributes() ?>>
<?= $Page->pagado->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->descuento->Visible) { // descuento ?>
    <tr id="r_descuento">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_descuento"><?= $Page->descuento->caption() ?></span></td>
        <td data-name="descuento" <?= $Page->descuento->cellAttributes() ?>>
<span id="el_salidas_descuento">
<span<?= $Page->descuento->viewAttributes() ?>>
<?= $Page->descuento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ci_rif->Visible) { // ci_rif ?>
    <tr id="r_ci_rif">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_ci_rif"><?= $Page->ci_rif->caption() ?></span></td>
        <td data-name="ci_rif" <?= $Page->ci_rif->cellAttributes() ?>>
<span id="el_salidas_ci_rif">
<span<?= $Page->ci_rif->viewAttributes() ?>>
<?= $Page->ci_rif->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nro_despacho->Visible) { // nro_despacho ?>
    <tr id="r_nro_despacho">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_nro_despacho"><?= $Page->nro_despacho->caption() ?></span></td>
        <td data-name="nro_despacho" <?= $Page->nro_despacho->cellAttributes() ?>>
<span id="el_salidas_nro_despacho">
<span<?= $Page->nro_despacho->viewAttributes() ?>>
<?= $Page->nro_despacho->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->impreso->Visible) { // impreso ?>
    <tr id="r_impreso">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_impreso"><?= $Page->impreso->caption() ?></span></td>
        <td data-name="impreso" <?= $Page->impreso->cellAttributes() ?>>
<span id="el_salidas_impreso">
<span<?= $Page->impreso->viewAttributes() ?>>
<?= $Page->impreso->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->igtf->Visible) { // igtf ?>
    <tr id="r_igtf">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_igtf"><?= $Page->igtf->caption() ?></span></td>
        <td data-name="igtf" <?= $Page->igtf->cellAttributes() ?>>
<span id="el_salidas_igtf">
<span<?= $Page->igtf->viewAttributes() ?>>
<?= $Page->igtf->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_base_igtf->Visible) { // monto_base_igtf ?>
    <tr id="r_monto_base_igtf">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_monto_base_igtf"><?= $Page->monto_base_igtf->caption() ?></span></td>
        <td data-name="monto_base_igtf" <?= $Page->monto_base_igtf->cellAttributes() ?>>
<span id="el_salidas_monto_base_igtf">
<span<?= $Page->monto_base_igtf->viewAttributes() ?>>
<?= $Page->monto_base_igtf->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_igtf->Visible) { // monto_igtf ?>
    <tr id="r_monto_igtf">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_monto_igtf"><?= $Page->monto_igtf->caption() ?></span></td>
        <td data-name="monto_igtf" <?= $Page->monto_igtf->cellAttributes() ?>>
<span id="el_salidas_monto_igtf">
<span<?= $Page->monto_igtf->viewAttributes() ?>>
<?= $Page->monto_igtf->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->pago_premio->Visible) { // pago_premio ?>
    <tr id="r_pago_premio">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_pago_premio"><?= $Page->pago_premio->caption() ?></span></td>
        <td data-name="pago_premio" <?= $Page->pago_premio->cellAttributes() ?>>
<span id="el_salidas_pago_premio">
<span<?= $Page->pago_premio->viewAttributes() ?>>
<?= $Page->pago_premio->getViewValue() ?></span>
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
<?php
    if (in_array("pagos", explode(",", $Page->getCurrentDetailTable())) && $pagos->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("pagos", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "PagosGrid.php" ?>
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
    	//btn-toolbar ew-toolbar
    	$(".ew-toolbar").hide();
    });
    $("#codbar").change(function() {
    	var id = <?php echo CurrentPage()->id->CurrentValue; ?>;
    	var tipo_documento = "<?php echo CurrentPage()->tipo_documento->CurrentValue; ?>";
    	var codbar = $("#codbar").val();
    	$.ajax({
    	  url : "include/check_nota_entrega.php",
    	  type: "GET",
    	  data : {id: id, tipo_documento: tipo_documento, codbar: codbar},
    	  beforeSend: function(){
    	    //$("#result").html("Espere. . . ");
    	  }
    	})
    	.done(function(data) {
    		var resp = data;
    		if(resp == "N") {
    			alert("ARTICULO NO EXISTE EN LA NOTA DE ENTREGA");
    		}
    		else {
    			location.reload();
    		}
    	})
    	.fail(function(data) {
    		alert( "error" + data );
    	})
    	.always(function(data) {
    		//alert( "complete" );
    		//$("#result").html("Espere. . . ");
    	});
    });
    $("#cmbContab").click(function(){
    	var id = <?php echo CurrentPage()->id->CurrentValue; ?>;
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
    		  data : {id: id, tipo_documento: tipo_documento, regla: 4, username: username},
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
    $("#cmbImpFact").click(function(){
    	// Impresión a la impresora fiscal 16/07/2022
    	var id = <?php echo CurrentPage()->id->CurrentValue; ?>;
    	var tipo_documento = "<?php echo CurrentPage()->tipo_documento->CurrentValue; ?>";
    	var username = "<?php echo CurrentUserName(); ?>";
    	if(confirm("Seguro de Imprimir la Factura Fiscal???")) {
    		// var url = "<?php echo $RELATIVE_PATH; ?>reportes/factura_de_venta.php?id=" + id + "&tipo=" + tipo_documento + "&username=" + username;
    		var url = "reportes/factura_de_venta.php?id=" + id + "&tipo=" + tipo_documento + "&username=" + username;
    		window.open(url);
    		/*
    		if(checkUrl(url)) {
    			window.open(url);
    		}
    		else {
    			url = "../reportes/factura_de_venta.php?id=" + id + "&tipo=" + tipo_documento + "&username=" + username;
    			if(checkUrl(url)) {
    				window.open(url);
    			}
    		}
    		*/
    	}
    });

    function checkUrl(url) {
            var request = false;
            if (window.XMLHttpRequest) {
                    request = new XMLHttpRequest;
            } else if (window.ActiveXObject) {
                    request = new ActiveXObject("Microsoft.XMLHttp");
            }
            if (request) {
                    request.open("GET", url);
                    if (request.status == 200) { return true; }
            }
            return false;
    }
});
</script>
<?php } ?>
