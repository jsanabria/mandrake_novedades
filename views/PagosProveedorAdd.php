<?php

namespace PHPMaker2021\mandrake;

// Page object
$PagosProveedorAdd = &$Page;
?>
<script>
var currentForm, currentPageID;
var fpagos_proveedoradd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    fpagos_proveedoradd = currentForm = new ew.Form("fpagos_proveedoradd", "add");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "pagos_proveedor")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.pagos_proveedor)
        ew.vars.tables.pagos_proveedor = currentTable;
    fpagos_proveedoradd.addFields([
        ["proveedor", [fields.proveedor.visible && fields.proveedor.required ? ew.Validators.required(fields.proveedor.caption) : null], fields.proveedor.isInvalid],
        ["pivote", [fields.pivote.visible && fields.pivote.required ? ew.Validators.required(fields.pivote.caption) : null], fields.pivote.isInvalid],
        ["tipo_pago", [fields.tipo_pago.visible && fields.tipo_pago.required ? ew.Validators.required(fields.tipo_pago.caption) : null], fields.tipo_pago.isInvalid],
        ["banco", [fields.banco.visible && fields.banco.required ? ew.Validators.required(fields.banco.caption) : null], fields.banco.isInvalid],
        ["referencia", [fields.referencia.visible && fields.referencia.required ? ew.Validators.required(fields.referencia.caption) : null], fields.referencia.isInvalid],
        ["moneda", [fields.moneda.visible && fields.moneda.required ? ew.Validators.required(fields.moneda.caption) : null], fields.moneda.isInvalid],
        ["monto_dado", [fields.monto_dado.visible && fields.monto_dado.required ? ew.Validators.required(fields.monto_dado.caption) : null, ew.Validators.float], fields.monto_dado.isInvalid],
        ["monto", [fields.monto.visible && fields.monto.required ? ew.Validators.required(fields.monto.caption) : null, ew.Validators.float], fields.monto.isInvalid],
        ["nota", [fields.nota.visible && fields.nota.required ? ew.Validators.required(fields.nota.caption) : null], fields.nota.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fpagos_proveedoradd,
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
    fpagos_proveedoradd.validate = function () {
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
    fpagos_proveedoradd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fpagos_proveedoradd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fpagos_proveedoradd.lists.proveedor = <?= $Page->proveedor->toClientList($Page) ?>;
    fpagos_proveedoradd.lists.tipo_pago = <?= $Page->tipo_pago->toClientList($Page) ?>;
    fpagos_proveedoradd.lists.banco = <?= $Page->banco->toClientList($Page) ?>;
    fpagos_proveedoradd.lists.moneda = <?= $Page->moneda->toClientList($Page) ?>;
    loadjs.done("fpagos_proveedoradd");
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
<form name="fpagos_proveedoradd" id="fpagos_proveedoradd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="pagos_proveedor">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->proveedor->Visible) { // proveedor ?>
    <div id="r_proveedor" class="form-group row">
        <label id="elh_pagos_proveedor_proveedor" for="x_proveedor" class="<?= $Page->LeftColumnClass ?>"><?= $Page->proveedor->caption() ?><?= $Page->proveedor->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->proveedor->cellAttributes() ?>>
<span id="el_pagos_proveedor_proveedor">
<div class="input-group ew-lookup-list" aria-describedby="x_proveedor_help">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x_proveedor"><?= EmptyValue(strval($Page->proveedor->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->proveedor->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->proveedor->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->proveedor->ReadOnly || $Page->proveedor->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x_proveedor',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->proveedor->getErrorMessage() ?></div>
<?= $Page->proveedor->getCustomMessage() ?>
<?= $Page->proveedor->Lookup->getParamTag($Page, "p_x_proveedor") ?>
<input type="hidden" is="selection-list" data-table="pagos_proveedor" data-field="x_proveedor" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->proveedor->displayValueSeparatorAttribute() ?>" name="x_proveedor" id="x_proveedor" value="<?= $Page->proveedor->CurrentValue ?>"<?= $Page->proveedor->editAttributes() ?>>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->pivote->Visible) { // pivote ?>
    <div id="r_pivote" class="form-group row">
        <label id="elh_pagos_proveedor_pivote" for="x_pivote" class="<?= $Page->LeftColumnClass ?>"><?= $Page->pivote->caption() ?><?= $Page->pivote->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->pivote->cellAttributes() ?>>
<span id="el_pagos_proveedor_pivote">
<input type="<?= $Page->pivote->getInputTextType() ?>" data-table="pagos_proveedor" data-field="x_pivote" name="x_pivote" id="x_pivote" size="30" maxlength="1" placeholder="<?= HtmlEncode($Page->pivote->getPlaceHolder()) ?>" value="<?= $Page->pivote->EditValue ?>"<?= $Page->pivote->editAttributes() ?> aria-describedby="x_pivote_help">
<?= $Page->pivote->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->pivote->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tipo_pago->Visible) { // tipo_pago ?>
    <div id="r_tipo_pago" class="form-group row">
        <label id="elh_pagos_proveedor_tipo_pago" for="x_tipo_pago" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo_pago->caption() ?><?= $Page->tipo_pago->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tipo_pago->cellAttributes() ?>>
<span id="el_pagos_proveedor_tipo_pago">
    <select
        id="x_tipo_pago"
        name="x_tipo_pago"
        class="form-control ew-select<?= $Page->tipo_pago->isInvalidClass() ?>"
        data-select2-id="pagos_proveedor_x_tipo_pago"
        data-table="pagos_proveedor"
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
    var el = document.querySelector("select[data-select2-id='pagos_proveedor_x_tipo_pago']"),
        options = { name: "x_tipo_pago", selectId: "pagos_proveedor_x_tipo_pago", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.pagos_proveedor.fields.tipo_pago.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->banco->Visible) { // banco ?>
    <div id="r_banco" class="form-group row">
        <label id="elh_pagos_proveedor_banco" for="x_banco" class="<?= $Page->LeftColumnClass ?>"><?= $Page->banco->caption() ?><?= $Page->banco->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->banco->cellAttributes() ?>>
<span id="el_pagos_proveedor_banco">
    <select
        id="x_banco"
        name="x_banco"
        class="form-control ew-select<?= $Page->banco->isInvalidClass() ?>"
        data-select2-id="pagos_proveedor_x_banco"
        data-table="pagos_proveedor"
        data-field="x_banco"
        data-value-separator="<?= $Page->banco->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->banco->getPlaceHolder()) ?>"
        <?= $Page->banco->editAttributes() ?>>
        <?= $Page->banco->selectOptionListHtml("x_banco") ?>
    </select>
    <?= $Page->banco->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->banco->getErrorMessage() ?></div>
<?= $Page->banco->Lookup->getParamTag($Page, "p_x_banco") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='pagos_proveedor_x_banco']"),
        options = { name: "x_banco", selectId: "pagos_proveedor_x_banco", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.pagos_proveedor.fields.banco.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
    <div id="r_referencia" class="form-group row">
        <label id="elh_pagos_proveedor_referencia" for="x_referencia" class="<?= $Page->LeftColumnClass ?>"><?= $Page->referencia->caption() ?><?= $Page->referencia->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->referencia->cellAttributes() ?>>
<span id="el_pagos_proveedor_referencia">
<input type="<?= $Page->referencia->getInputTextType() ?>" data-table="pagos_proveedor" data-field="x_referencia" name="x_referencia" id="x_referencia" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->referencia->getPlaceHolder()) ?>" value="<?= $Page->referencia->EditValue ?>"<?= $Page->referencia->editAttributes() ?> aria-describedby="x_referencia_help">
<?= $Page->referencia->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->referencia->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <div id="r_moneda" class="form-group row">
        <label id="elh_pagos_proveedor_moneda" for="x_moneda" class="<?= $Page->LeftColumnClass ?>"><?= $Page->moneda->caption() ?><?= $Page->moneda->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->moneda->cellAttributes() ?>>
<span id="el_pagos_proveedor_moneda">
    <select
        id="x_moneda"
        name="x_moneda"
        class="form-control ew-select<?= $Page->moneda->isInvalidClass() ?>"
        data-select2-id="pagos_proveedor_x_moneda"
        data-table="pagos_proveedor"
        data-field="x_moneda"
        data-value-separator="<?= $Page->moneda->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->moneda->getPlaceHolder()) ?>"
        <?= $Page->moneda->editAttributes() ?>>
        <?= $Page->moneda->selectOptionListHtml("x_moneda") ?>
    </select>
    <?= $Page->moneda->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->moneda->getErrorMessage() ?></div>
<?= $Page->moneda->Lookup->getParamTag($Page, "p_x_moneda") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='pagos_proveedor_x_moneda']"),
        options = { name: "x_moneda", selectId: "pagos_proveedor_x_moneda", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.pagos_proveedor.fields.moneda.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->monto_dado->Visible) { // monto_dado ?>
    <div id="r_monto_dado" class="form-group row">
        <label id="elh_pagos_proveedor_monto_dado" for="x_monto_dado" class="<?= $Page->LeftColumnClass ?>"><?= $Page->monto_dado->caption() ?><?= $Page->monto_dado->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->monto_dado->cellAttributes() ?>>
<span id="el_pagos_proveedor_monto_dado">
<input type="<?= $Page->monto_dado->getInputTextType() ?>" data-table="pagos_proveedor" data-field="x_monto_dado" name="x_monto_dado" id="x_monto_dado" size="30" maxlength="14" placeholder="<?= HtmlEncode($Page->monto_dado->getPlaceHolder()) ?>" value="<?= $Page->monto_dado->EditValue ?>"<?= $Page->monto_dado->editAttributes() ?> aria-describedby="x_monto_dado_help">
<?= $Page->monto_dado->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->monto_dado->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->monto->Visible) { // monto ?>
    <div id="r_monto" class="form-group row">
        <label id="elh_pagos_proveedor_monto" for="x_monto" class="<?= $Page->LeftColumnClass ?>"><?= $Page->monto->caption() ?><?= $Page->monto->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->monto->cellAttributes() ?>>
<span id="el_pagos_proveedor_monto">
<input type="<?= $Page->monto->getInputTextType() ?>" data-table="pagos_proveedor" data-field="x_monto" name="x_monto" id="x_monto" size="30" maxlength="14" placeholder="<?= HtmlEncode($Page->monto->getPlaceHolder()) ?>" value="<?= $Page->monto->EditValue ?>"<?= $Page->monto->editAttributes() ?> aria-describedby="x_monto_help">
<?= $Page->monto->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->monto->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
    <div id="r_nota" class="form-group row">
        <label id="elh_pagos_proveedor_nota" for="x_nota" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nota->caption() ?><?= $Page->nota->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nota->cellAttributes() ?>>
<span id="el_pagos_proveedor_nota">
<textarea data-table="pagos_proveedor" data-field="x_nota" name="x_nota" id="x_nota" cols="30" rows="3" placeholder="<?= HtmlEncode($Page->nota->getPlaceHolder()) ?>"<?= $Page->nota->editAttributes() ?> aria-describedby="x_nota_help"><?= $Page->nota->EditValue ?></textarea>
<?= $Page->nota->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nota->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php
    if (in_array("pagos_proveedor_factura", explode(",", $Page->getCurrentDetailTable())) && $pagos_proveedor_factura->DetailAdd) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("pagos_proveedor_factura", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "PagosProveedorFacturaGrid.php" ?>
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
    ew.addEventHandlers("pagos_proveedor");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    $("#x_monto").prop("readonly",!0),$("#x_proveedor").change((function(){var r=$("#x_proveedor").val();$("#r_pivote").html("Hello World"),$.ajax({url:"include/Proveedor_Facturas_Buscar.php",type:"GET",data:{proveedor:r},beforeSend:function(){$("#r_pivote").html("Por Favor Espere. . .")}}).done((function(r){var e="";e="0"==r?'<div class="container"><div class="alert alert-success" role="alert">No hay facturas pendientes por pagar al proveedor</div></div>':r,$("#r_pivote").html(e)})).fail((function(r){alert("error"+r)})).always((function(r){}))}));
});
</script>
