<?php

namespace PHPMaker2021\mandrake;

// Page object
$CobrosClienteDetalleAdd = &$Page;
?>
<script>
var currentForm, currentPageID;
var fcobros_cliente_detalleadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    fcobros_cliente_detalleadd = currentForm = new ew.Form("fcobros_cliente_detalleadd", "add");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "cobros_cliente_detalle")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.cobros_cliente_detalle)
        ew.vars.tables.cobros_cliente_detalle = currentTable;
    fcobros_cliente_detalleadd.addFields([
        ["metodo_pago", [fields.metodo_pago.visible && fields.metodo_pago.required ? ew.Validators.required(fields.metodo_pago.caption) : null], fields.metodo_pago.isInvalid],
        ["referencia", [fields.referencia.visible && fields.referencia.required ? ew.Validators.required(fields.referencia.caption) : null], fields.referencia.isInvalid],
        ["monto_moneda", [fields.monto_moneda.visible && fields.monto_moneda.required ? ew.Validators.required(fields.monto_moneda.caption) : null, ew.Validators.float], fields.monto_moneda.isInvalid],
        ["moneda", [fields.moneda.visible && fields.moneda.required ? ew.Validators.required(fields.moneda.caption) : null], fields.moneda.isInvalid],
        ["banco", [fields.banco.visible && fields.banco.required ? ew.Validators.required(fields.banco.caption) : null], fields.banco.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fcobros_cliente_detalleadd,
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
    fcobros_cliente_detalleadd.validate = function () {
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
    fcobros_cliente_detalleadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fcobros_cliente_detalleadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fcobros_cliente_detalleadd.lists.metodo_pago = <?= $Page->metodo_pago->toClientList($Page) ?>;
    fcobros_cliente_detalleadd.lists.moneda = <?= $Page->moneda->toClientList($Page) ?>;
    fcobros_cliente_detalleadd.lists.banco = <?= $Page->banco->toClientList($Page) ?>;
    loadjs.done("fcobros_cliente_detalleadd");
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
<form name="fcobros_cliente_detalleadd" id="fcobros_cliente_detalleadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cobros_cliente_detalle">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "cobros_cliente") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="cobros_cliente">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->cobros_cliente->getSessionValue()) ?>">
<?php } ?>
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->metodo_pago->Visible) { // metodo_pago ?>
    <div id="r_metodo_pago" class="form-group row">
        <label id="elh_cobros_cliente_detalle_metodo_pago" for="x_metodo_pago" class="<?= $Page->LeftColumnClass ?>"><?= $Page->metodo_pago->caption() ?><?= $Page->metodo_pago->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->metodo_pago->cellAttributes() ?>>
<span id="el_cobros_cliente_detalle_metodo_pago">
<div class="input-group ew-lookup-list" aria-describedby="x_metodo_pago_help">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x_metodo_pago"><?= EmptyValue(strval($Page->metodo_pago->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->metodo_pago->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->metodo_pago->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->metodo_pago->ReadOnly || $Page->metodo_pago->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x_metodo_pago',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->metodo_pago->getErrorMessage() ?></div>
<?= $Page->metodo_pago->getCustomMessage() ?>
<?= $Page->metodo_pago->Lookup->getParamTag($Page, "p_x_metodo_pago") ?>
<input type="hidden" is="selection-list" data-table="cobros_cliente_detalle" data-field="x_metodo_pago" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->metodo_pago->displayValueSeparatorAttribute() ?>" name="x_metodo_pago" id="x_metodo_pago" value="<?= $Page->metodo_pago->CurrentValue ?>"<?= $Page->metodo_pago->editAttributes() ?>>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
    <div id="r_referencia" class="form-group row">
        <label id="elh_cobros_cliente_detalle_referencia" for="x_referencia" class="<?= $Page->LeftColumnClass ?>"><?= $Page->referencia->caption() ?><?= $Page->referencia->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->referencia->cellAttributes() ?>>
<span id="el_cobros_cliente_detalle_referencia">
<input type="<?= $Page->referencia->getInputTextType() ?>" data-table="cobros_cliente_detalle" data-field="x_referencia" name="x_referencia" id="x_referencia" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->referencia->getPlaceHolder()) ?>" value="<?= $Page->referencia->EditValue ?>"<?= $Page->referencia->editAttributes() ?> aria-describedby="x_referencia_help">
<?= $Page->referencia->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->referencia->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->monto_moneda->Visible) { // monto_moneda ?>
    <div id="r_monto_moneda" class="form-group row">
        <label id="elh_cobros_cliente_detalle_monto_moneda" for="x_monto_moneda" class="<?= $Page->LeftColumnClass ?>"><?= $Page->monto_moneda->caption() ?><?= $Page->monto_moneda->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->monto_moneda->cellAttributes() ?>>
<span id="el_cobros_cliente_detalle_monto_moneda">
<input type="<?= $Page->monto_moneda->getInputTextType() ?>" data-table="cobros_cliente_detalle" data-field="x_monto_moneda" name="x_monto_moneda" id="x_monto_moneda" size="30" maxlength="14" placeholder="<?= HtmlEncode($Page->monto_moneda->getPlaceHolder()) ?>" value="<?= $Page->monto_moneda->EditValue ?>"<?= $Page->monto_moneda->editAttributes() ?> aria-describedby="x_monto_moneda_help">
<?= $Page->monto_moneda->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->monto_moneda->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <div id="r_moneda" class="form-group row">
        <label id="elh_cobros_cliente_detalle_moneda" for="x_moneda" class="<?= $Page->LeftColumnClass ?>"><?= $Page->moneda->caption() ?><?= $Page->moneda->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->moneda->cellAttributes() ?>>
<span id="el_cobros_cliente_detalle_moneda">
    <select
        id="x_moneda"
        name="x_moneda"
        class="form-control ew-select<?= $Page->moneda->isInvalidClass() ?>"
        data-select2-id="cobros_cliente_detalle_x_moneda"
        data-table="cobros_cliente_detalle"
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
    var el = document.querySelector("select[data-select2-id='cobros_cliente_detalle_x_moneda']"),
        options = { name: "x_moneda", selectId: "cobros_cliente_detalle_x_moneda", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.cobros_cliente_detalle.fields.moneda.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->banco->Visible) { // banco ?>
    <div id="r_banco" class="form-group row">
        <label id="elh_cobros_cliente_detalle_banco" for="x_banco" class="<?= $Page->LeftColumnClass ?>"><?= $Page->banco->caption() ?><?= $Page->banco->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->banco->cellAttributes() ?>>
<span id="el_cobros_cliente_detalle_banco">
    <select
        id="x_banco"
        name="x_banco"
        class="form-control ew-select<?= $Page->banco->isInvalidClass() ?>"
        data-select2-id="cobros_cliente_detalle_x_banco"
        data-table="cobros_cliente_detalle"
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
    var el = document.querySelector("select[data-select2-id='cobros_cliente_detalle_x_banco']"),
        options = { name: "x_banco", selectId: "cobros_cliente_detalle_x_banco", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.cobros_cliente_detalle.fields.banco.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <?php if (strval($Page->cobros_cliente->getSessionValue()) != "") { ?>
    <input type="hidden" name="x_cobros_cliente" id="x_cobros_cliente" value="<?= HtmlEncode(strval($Page->cobros_cliente->getSessionValue())) ?>">
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
    ew.addEventHandlers("cobros_cliente_detalle");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
