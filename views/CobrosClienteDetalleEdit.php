<?php

namespace PHPMaker2021\mandrake;

// Page object
$CobrosClienteDetalleEdit = &$Page;
?>
<script>
var currentForm, currentPageID;
var fcobros_cliente_detalleedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fcobros_cliente_detalleedit = currentForm = new ew.Form("fcobros_cliente_detalleedit", "edit");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "cobros_cliente_detalle")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.cobros_cliente_detalle)
        ew.vars.tables.cobros_cliente_detalle = currentTable;
    fcobros_cliente_detalleedit.addFields([
        ["id", [fields.id.visible && fields.id.required ? ew.Validators.required(fields.id.caption) : null], fields.id.isInvalid],
        ["cobros_cliente", [fields.cobros_cliente.visible && fields.cobros_cliente.required ? ew.Validators.required(fields.cobros_cliente.caption) : null], fields.cobros_cliente.isInvalid],
        ["metodo_pago", [fields.metodo_pago.visible && fields.metodo_pago.required ? ew.Validators.required(fields.metodo_pago.caption) : null], fields.metodo_pago.isInvalid],
        ["referencia", [fields.referencia.visible && fields.referencia.required ? ew.Validators.required(fields.referencia.caption) : null], fields.referencia.isInvalid],
        ["monto_moneda", [fields.monto_moneda.visible && fields.monto_moneda.required ? ew.Validators.required(fields.monto_moneda.caption) : null], fields.monto_moneda.isInvalid],
        ["moneda", [fields.moneda.visible && fields.moneda.required ? ew.Validators.required(fields.moneda.caption) : null], fields.moneda.isInvalid],
        ["monto_bs", [fields.monto_bs.visible && fields.monto_bs.required ? ew.Validators.required(fields.monto_bs.caption) : null, ew.Validators.float], fields.monto_bs.isInvalid],
        ["tasa_usd", [fields.tasa_usd.visible && fields.tasa_usd.required ? ew.Validators.required(fields.tasa_usd.caption) : null, ew.Validators.float], fields.tasa_usd.isInvalid],
        ["monto_usd", [fields.monto_usd.visible && fields.monto_usd.required ? ew.Validators.required(fields.monto_usd.caption) : null, ew.Validators.float], fields.monto_usd.isInvalid],
        ["banco", [fields.banco.visible && fields.banco.required ? ew.Validators.required(fields.banco.caption) : null], fields.banco.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fcobros_cliente_detalleedit,
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
    fcobros_cliente_detalleedit.validate = function () {
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
    fcobros_cliente_detalleedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fcobros_cliente_detalleedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fcobros_cliente_detalleedit.lists.moneda = <?= $Page->moneda->toClientList($Page) ?>;
    fcobros_cliente_detalleedit.lists.banco = <?= $Page->banco->toClientList($Page) ?>;
    loadjs.done("fcobros_cliente_detalleedit");
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
<?php if (!$Page->IsModal) { ?>
<form name="ew-pager-form" class="form-inline ew-form ew-pager-form" action="<?= CurrentPageUrl(false) ?>">
<?= $Page->Pager->render() ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="fcobros_cliente_detalleedit" id="fcobros_cliente_detalleedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cobros_cliente_detalle">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "cobros_cliente") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="cobros_cliente">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->cobros_cliente->getSessionValue()) ?>">
<?php } ?>
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->id->Visible) { // id ?>
    <div id="r_id" class="form-group row">
        <label id="elh_cobros_cliente_detalle_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id->caption() ?><?= $Page->id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->id->cellAttributes() ?>>
<span id="el_cobros_cliente_detalle_id">
<span<?= $Page->id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->id->getDisplayValue($Page->id->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cobros_cliente_detalle" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cobros_cliente->Visible) { // cobros_cliente ?>
    <div id="r_cobros_cliente" class="form-group row">
        <label id="elh_cobros_cliente_detalle_cobros_cliente" for="x_cobros_cliente" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cobros_cliente->caption() ?><?= $Page->cobros_cliente->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cobros_cliente->cellAttributes() ?>>
<span id="el_cobros_cliente_detalle_cobros_cliente">
<span<?= $Page->cobros_cliente->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->cobros_cliente->getDisplayValue($Page->cobros_cliente->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cobros_cliente_detalle" data-field="x_cobros_cliente" data-hidden="1" name="x_cobros_cliente" id="x_cobros_cliente" value="<?= HtmlEncode($Page->cobros_cliente->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->metodo_pago->Visible) { // metodo_pago ?>
    <div id="r_metodo_pago" class="form-group row">
        <label id="elh_cobros_cliente_detalle_metodo_pago" for="x_metodo_pago" class="<?= $Page->LeftColumnClass ?>"><?= $Page->metodo_pago->caption() ?><?= $Page->metodo_pago->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->metodo_pago->cellAttributes() ?>>
<span id="el_cobros_cliente_detalle_metodo_pago">
<span<?= $Page->metodo_pago->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->metodo_pago->getDisplayValue($Page->metodo_pago->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cobros_cliente_detalle" data-field="x_metodo_pago" data-hidden="1" name="x_metodo_pago" id="x_metodo_pago" value="<?= HtmlEncode($Page->metodo_pago->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
    <div id="r_referencia" class="form-group row">
        <label id="elh_cobros_cliente_detalle_referencia" class="<?= $Page->LeftColumnClass ?>"><?= $Page->referencia->caption() ?><?= $Page->referencia->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->referencia->cellAttributes() ?>>
<span id="el_cobros_cliente_detalle_referencia">
<span<?= $Page->referencia->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->referencia->getDisplayValue($Page->referencia->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cobros_cliente_detalle" data-field="x_referencia" data-hidden="1" name="x_referencia" id="x_referencia" value="<?= HtmlEncode($Page->referencia->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->monto_moneda->Visible) { // monto_moneda ?>
    <div id="r_monto_moneda" class="form-group row">
        <label id="elh_cobros_cliente_detalle_monto_moneda" for="x_monto_moneda" class="<?= $Page->LeftColumnClass ?>"><?= $Page->monto_moneda->caption() ?><?= $Page->monto_moneda->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->monto_moneda->cellAttributes() ?>>
<span id="el_cobros_cliente_detalle_monto_moneda">
<span<?= $Page->monto_moneda->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->monto_moneda->getDisplayValue($Page->monto_moneda->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cobros_cliente_detalle" data-field="x_monto_moneda" data-hidden="1" name="x_monto_moneda" id="x_monto_moneda" value="<?= HtmlEncode($Page->monto_moneda->CurrentValue) ?>">
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
<?php if ($Page->monto_bs->Visible) { // monto_bs ?>
    <div id="r_monto_bs" class="form-group row">
        <label id="elh_cobros_cliente_detalle_monto_bs" for="x_monto_bs" class="<?= $Page->LeftColumnClass ?>"><?= $Page->monto_bs->caption() ?><?= $Page->monto_bs->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->monto_bs->cellAttributes() ?>>
<span id="el_cobros_cliente_detalle_monto_bs">
<input type="<?= $Page->monto_bs->getInputTextType() ?>" data-table="cobros_cliente_detalle" data-field="x_monto_bs" name="x_monto_bs" id="x_monto_bs" size="30" maxlength="14" placeholder="<?= HtmlEncode($Page->monto_bs->getPlaceHolder()) ?>" value="<?= $Page->monto_bs->EditValue ?>"<?= $Page->monto_bs->editAttributes() ?> aria-describedby="x_monto_bs_help">
<?= $Page->monto_bs->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->monto_bs->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tasa_usd->Visible) { // tasa_usd ?>
    <div id="r_tasa_usd" class="form-group row">
        <label id="elh_cobros_cliente_detalle_tasa_usd" for="x_tasa_usd" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tasa_usd->caption() ?><?= $Page->tasa_usd->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tasa_usd->cellAttributes() ?>>
<span id="el_cobros_cliente_detalle_tasa_usd">
<input type="<?= $Page->tasa_usd->getInputTextType() ?>" data-table="cobros_cliente_detalle" data-field="x_tasa_usd" name="x_tasa_usd" id="x_tasa_usd" size="30" maxlength="14" placeholder="<?= HtmlEncode($Page->tasa_usd->getPlaceHolder()) ?>" value="<?= $Page->tasa_usd->EditValue ?>"<?= $Page->tasa_usd->editAttributes() ?> aria-describedby="x_tasa_usd_help">
<?= $Page->tasa_usd->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->tasa_usd->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->monto_usd->Visible) { // monto_usd ?>
    <div id="r_monto_usd" class="form-group row">
        <label id="elh_cobros_cliente_detalle_monto_usd" for="x_monto_usd" class="<?= $Page->LeftColumnClass ?>"><?= $Page->monto_usd->caption() ?><?= $Page->monto_usd->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->monto_usd->cellAttributes() ?>>
<span id="el_cobros_cliente_detalle_monto_usd">
<input type="<?= $Page->monto_usd->getInputTextType() ?>" data-table="cobros_cliente_detalle" data-field="x_monto_usd" name="x_monto_usd" id="x_monto_usd" size="30" maxlength="14" placeholder="<?= HtmlEncode($Page->monto_usd->getPlaceHolder()) ?>" value="<?= $Page->monto_usd->EditValue ?>"<?= $Page->monto_usd->editAttributes() ?> aria-describedby="x_monto_usd_help">
<?= $Page->monto_usd->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->monto_usd->getErrorMessage() ?></div>
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
<?php if (!$Page->IsModal) { ?>
<div class="form-group row"><!-- buttons .form-group -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("SaveBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
    </div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<div class="clearfix"></div>
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
