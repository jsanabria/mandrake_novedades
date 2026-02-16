<?php

namespace PHPMaker2021\mandrake;

// Page object
$CobrosClienteAdd = &$Page;
?>
<script>
var currentForm, currentPageID;
var fcobros_clienteadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    fcobros_clienteadd = currentForm = new ew.Form("fcobros_clienteadd", "add");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "cobros_cliente")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.cobros_cliente)
        ew.vars.tables.cobros_cliente = currentTable;
    fcobros_clienteadd.addFields([
        ["cliente", [fields.cliente.visible && fields.cliente.required ? ew.Validators.required(fields.cliente.caption) : null], fields.cliente.isInvalid],
        ["pivote", [fields.pivote.visible && fields.pivote.required ? ew.Validators.required(fields.pivote.caption) : null], fields.pivote.isInvalid],
        ["moneda", [fields.moneda.visible && fields.moneda.required ? ew.Validators.required(fields.moneda.caption) : null], fields.moneda.isInvalid],
        ["pago", [fields.pago.visible && fields.pago.required ? ew.Validators.required(fields.pago.caption) : null, ew.Validators.float], fields.pago.isInvalid],
        ["nota", [fields.nota.visible && fields.nota.required ? ew.Validators.required(fields.nota.caption) : null], fields.nota.isInvalid],
        ["tipo_pago", [fields.tipo_pago.visible && fields.tipo_pago.required ? ew.Validators.required(fields.tipo_pago.caption) : null], fields.tipo_pago.isInvalid],
        ["pivote2", [fields.pivote2.visible && fields.pivote2.required ? ew.Validators.required(fields.pivote2.caption) : null], fields.pivote2.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fcobros_clienteadd,
            fobj = f.getForm(),
            $fobj = $(fobj),
            $k = $fobj.find("#" + f.formKeyCountName), // Get key_count
            rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1,
            startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
        for (var i = startcnt; i <= rowcnt; i++) {
            var rowIndex = ($k[0]) ? String(i) : "";
            f.setInvalid(rowIndex);
        }
    });

    // Validate form
    fcobros_clienteadd.validate = function () {
        if (!this.validateRequired)
            return true; // Ignore validation
        var fobj = this.getForm(),
            $fobj = $(fobj);
        if ($fobj.find("#confirm").val() == "confirm")
            return true;
        var addcnt = 0,
            $k = $fobj.find("#" + this.formKeyCountName), // Get key_count
            rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1,
            startcnt = (rowcnt == 0) ? 0 : 1, // Check rowcnt == 0 => Inline-Add
            gridinsert = ["insert", "gridinsert"].includes($fobj.find("#action").val()) && $k[0];
        for (var i = startcnt; i <= rowcnt; i++) {
            var rowIndex = ($k[0]) ? String(i) : "";
            $fobj.data("rowindex", rowIndex);

            // Validate fields
            if (!this.validateFields(rowIndex))
                return false;

            // Call Form_CustomValidate event
            if (!this.customValidate(fobj)) {
                this.focus();
                return false;
            }
        }

        // Process detail forms
        var dfs = $fobj.find("input[name='detailpage']").get();
        for (var i = 0; i < dfs.length; i++) {
            var df = dfs[i],
                val = df.value,
                frm = ew.forms.get(val);
            if (val && frm && !frm.validate())
                return false;
        }
        return true;
    }

    // Form_CustomValidate
    fcobros_clienteadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fcobros_clienteadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fcobros_clienteadd.lists.cliente = <?= $Page->cliente->toClientList($Page) ?>;
    fcobros_clienteadd.lists.tipo_pago = <?= $Page->tipo_pago->toClientList($Page) ?>;
    loadjs.done("fcobros_clienteadd");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fcobros_clienteadd" id="fcobros_clienteadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cobros_cliente">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->cliente->Visible) { // cliente ?>
    <div id="r_cliente" class="form-group row">
        <label id="elh_cobros_cliente_cliente" for="x_cliente" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cliente->caption() ?><?= $Page->cliente->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cliente->cellAttributes() ?>>
<span id="el_cobros_cliente_cliente">
<div class="input-group ew-lookup-list" aria-describedby="x_cliente_help">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x_cliente"><?= EmptyValue(strval($Page->cliente->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->cliente->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->cliente->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->cliente->ReadOnly || $Page->cliente->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x_cliente',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->cliente->getErrorMessage() ?></div>
<?= $Page->cliente->getCustomMessage() ?>
<?= $Page->cliente->Lookup->getParamTag($Page, "p_x_cliente") ?>
<input type="hidden" is="selection-list" data-table="cobros_cliente" data-field="x_cliente" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->cliente->displayValueSeparatorAttribute() ?>" name="x_cliente" id="x_cliente" value="<?= $Page->cliente->CurrentValue ?>"<?= $Page->cliente->editAttributes() ?>>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->pivote->Visible) { // pivote ?>
    <div id="r_pivote" class="form-group row">
        <label id="elh_cobros_cliente_pivote" for="x_pivote" class="<?= $Page->LeftColumnClass ?>"><?= $Page->pivote->caption() ?><?= $Page->pivote->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->pivote->cellAttributes() ?>>
<span id="el_cobros_cliente_pivote">
<input type="<?= $Page->pivote->getInputTextType() ?>" data-table="cobros_cliente" data-field="x_pivote" name="x_pivote" id="x_pivote" size="30" maxlength="1" placeholder="<?= HtmlEncode($Page->pivote->getPlaceHolder()) ?>" value="<?= $Page->pivote->EditValue ?>"<?= $Page->pivote->editAttributes() ?> aria-describedby="x_pivote_help">
<?= $Page->pivote->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->pivote->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <div id="r_moneda" class="form-group row">
        <label id="elh_cobros_cliente_moneda" for="x_moneda" class="<?= $Page->LeftColumnClass ?>"><?= $Page->moneda->caption() ?><?= $Page->moneda->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->moneda->cellAttributes() ?>>
<span id="el_cobros_cliente_moneda">
<input type="<?= $Page->moneda->getInputTextType() ?>" data-table="cobros_cliente" data-field="x_moneda" name="x_moneda" id="x_moneda" size="30" maxlength="6" placeholder="<?= HtmlEncode($Page->moneda->getPlaceHolder()) ?>" value="<?= $Page->moneda->EditValue ?>"<?= $Page->moneda->editAttributes() ?> aria-describedby="x_moneda_help">
<?= $Page->moneda->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->moneda->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->pago->Visible) { // pago ?>
    <div id="r_pago" class="form-group row">
        <label id="elh_cobros_cliente_pago" for="x_pago" class="<?= $Page->LeftColumnClass ?>"><?= $Page->pago->caption() ?><?= $Page->pago->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->pago->cellAttributes() ?>>
<span id="el_cobros_cliente_pago">
<input type="<?= $Page->pago->getInputTextType() ?>" data-table="cobros_cliente" data-field="x_pago" name="x_pago" id="x_pago" size="30" maxlength="14" placeholder="<?= HtmlEncode($Page->pago->getPlaceHolder()) ?>" value="<?= $Page->pago->EditValue ?>"<?= $Page->pago->editAttributes() ?> aria-describedby="x_pago_help">
<?= $Page->pago->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->pago->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
    <div id="r_nota" class="form-group row">
        <label id="elh_cobros_cliente_nota" for="x_nota" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nota->caption() ?><?= $Page->nota->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nota->cellAttributes() ?>>
<span id="el_cobros_cliente_nota">
<input type="<?= $Page->nota->getInputTextType() ?>" data-table="cobros_cliente" data-field="x_nota" name="x_nota" id="x_nota" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->nota->getPlaceHolder()) ?>" value="<?= $Page->nota->EditValue ?>"<?= $Page->nota->editAttributes() ?> aria-describedby="x_nota_help">
<?= $Page->nota->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nota->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tipo_pago->Visible) { // tipo_pago ?>
    <div id="r_tipo_pago" class="form-group row">
        <label id="elh_cobros_cliente_tipo_pago" for="x_tipo_pago" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo_pago->caption() ?><?= $Page->tipo_pago->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tipo_pago->cellAttributes() ?>>
<span id="el_cobros_cliente_tipo_pago">
    <select
        id="x_tipo_pago"
        name="x_tipo_pago"
        class="form-control ew-select<?= $Page->tipo_pago->isInvalidClass() ?>"
        data-select2-id="cobros_cliente_x_tipo_pago"
        data-table="cobros_cliente"
        data-field="x_tipo_pago"
        data-value-separator="<?= $Page->tipo_pago->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->tipo_pago->getPlaceHolder()) ?>"
        <?= $Page->tipo_pago->editAttributes() ?>>
        <?= $Page->tipo_pago->selectOptionListHtml("x_tipo_pago") ?>
    </select>
    <?= $Page->tipo_pago->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->tipo_pago->getErrorMessage() ?></div>
<?= $Page->tipo_pago->Lookup->getParamTag($Page, "p_x_tipo_pago") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='cobros_cliente_x_tipo_pago']"),
        options = { name: "x_tipo_pago", selectId: "cobros_cliente_x_tipo_pago", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.cobros_cliente.fields.tipo_pago.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->pivote2->Visible) { // pivote2 ?>
    <div id="r_pivote2" class="form-group row">
        <label id="elh_cobros_cliente_pivote2" for="x_pivote2" class="<?= $Page->LeftColumnClass ?>"><?= $Page->pivote2->caption() ?><?= $Page->pivote2->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->pivote2->cellAttributes() ?>>
<span id="el_cobros_cliente_pivote2">
<input type="<?= $Page->pivote2->getInputTextType() ?>" data-table="cobros_cliente" data-field="x_pivote2" name="x_pivote2" id="x_pivote2" size="30" maxlength="1" placeholder="<?= HtmlEncode($Page->pivote2->getPlaceHolder()) ?>" value="<?= $Page->pivote2->EditValue ?>"<?= $Page->pivote2->editAttributes() ?> aria-describedby="x_pivote2_help">
<?= $Page->pivote2->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->pivote2->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php
    if (in_array("cobros_cliente_detalle", explode(",", $Page->getCurrentDetailTable())) && $cobros_cliente_detalle->DetailAdd) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("cobros_cliente_detalle", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "CobrosClienteDetalleGrid.php" ?>
<?php } ?>
<?php if (!$Page->IsModal) { ?>
<div class="form-group row"><!-- buttons .form-group -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("AddBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
    </div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("cobros_cliente");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    // Write your table-specific startup script here
    // document.write("page loaded");
    $("#x_pago").prop('readonly', true);
    $("#r_pivote").hide();
    $("#r_pivote2").hide();
    $("#x_cliente").change(function(){
    	$("#r_pivote").show();
    	var cliente = $("#x_cliente").val();
    	$("#r_pivote").html("");
    	if(cliente == "") {
    		$("#r_pivote").hide();
    		$("#r_pivote2").hide();
    		return true;
    	}
    	$.ajax({
    	  url : "include/Cliente_Facturas_Buscar.php",
    	  type: "GET",
    	  data : {cliente: cliente},
    	  beforeSend: function(){
    	    $("#r_pivote").html("Por Favor Espere. . .");
    	    //////$("#monto").val(0.00);
    	  }
    	})
    	.done(function(data) {
    		//alert(data);
    		var rs = '';
    		if(data == "0")
    			rs = '<div class="container"><div class="alert alert-success" role="alert">No hay facturas pendientes por cobrar al cliente</div></div>';
    		else {
    			//$("#x_monto").prop('readonly', false);
    			$("#x_monto_recibido").prop('readonly', false);
    			rs = data;
    		}
    		$("#r_pivote").html(rs);
    	})
    	.fail(function(data) {
    		alert( "error" + data );
    	})
    	.always(function(data) {
    	    $("#btn-action").prop('disabled', true);
    		//alert( "complete" );
    		//$("#result").html("Espere. . . ");
    	});
    });
    $("#x_tipo_pago").change(function(){
    	$("#r_pivote2").show();
    	var cliente = $("#x_cliente").val();
    	var tipo_pago = $("#x_tipo_pago").val();
    	var pagos = $("#pagos").val();
    	var moneda = $("#x_moneda").val();
    	var dsc = <?= (isset($_REQUEST["dsc"]) ? intval($_REQUEST["dsc"]) : 0) ?>;
    	var puede = $("#puede").val();
    	$("#r_pivote2").html("");
    	if(cliente == "") {
    		//$("#r_pivote2").hide();
    		alert("Seleccione un cliente");
    		location.reload();
    		return true;
    	}
    	$.ajax({
    	  url : "include/Cliente_Tipo_Pago.php",
    	  type: "GET",
    	  data : {cliente: cliente, tipo_pago: tipo_pago, pagos: pagos, moneda: moneda, dsc: dsc, puede: puede},
    	  beforeSend: function(){
    	    $("#r_pivote2").html("Por Favor Espere. . .");
    	  }
    	})
    	.done(function(data) {
    		//alert(data);
    		var rs = '';
    		rs = data;
    		//$("#x_monto").prop('readonly', false);
    		$("#r_pivote2").html(rs);
    	})
    	.fail(function(data) {
    		alert( "error" + data );
    	})
    	.always(function(data) {
    	    $("#btn-action").prop('disabled', true);
    		//alert( "complete" );
    		//$("#result").html("Espere. . . ");
    	});
    });
    $(document).ready(function() {
    	//alert("<?php echo isset($_GET["id_compra"]) ? $_GET["id_compra"] : 0; ?>");
    	var id = <?php echo isset($_GET["id_compra"]) ? intval($_GET["id_compra"]) : 0; ?>;
    	if(id != 0) {
    		$("#r_pivote").show();
    		$.ajax({
    		  url : "include/buscar_factura_cliente.php",
    		  type: "GET",
    		  data : {id: id},
    		  beforeSend: function(){
    		  	$("#r_cliente").html("Por Favor Espere. . .");
    		    $("#r_pivote").html("Por Favor Espere. . .");
    		    //////$("#monto").val(0.00);
    		  }
    		})
    		.done(function(data) {
    			//alert(data);
    			var rs = data.split("|");
    			//$("#x_monto").prop('readonly', false);
    			$("#x_monto_recibido").prop('readonly', false);
    			$("#r_cliente").html(rs[0]);
    			$("#r_pivote").html(rs[1]);
    			//////$("#monto").val(rs[2]);
    		})
    		.fail(function(data) {
    			alert( "error" + data );
    		})
    		.always(function(data) {
    		    $("#btn-action").prop('disabled', true);
    			//alert( "complete" );
    			//$("#result").html("Espere. . . ");
    		});
    	}
    	<?php
    	$sql = "SELECT valor1 FROM parametro WHERE codigo = '006' AND valor2 = 'default';";
    	?>
    	$("#x_moneda").val("<?php echo ExecuteScalar($sql); ?>");
    	$("#x_moneda").prop('readonly', true);
    	$("#r_moneda").hide();
    });
});
</script>
