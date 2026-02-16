<?php

namespace PHPMaker2021\mandrake;

// Page object
$CobrosClienteEdit = &$Page;
?>
<script>
var currentForm, currentPageID;
var fcobros_clienteedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fcobros_clienteedit = currentForm = new ew.Form("fcobros_clienteedit", "edit");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "cobros_cliente")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.cobros_cliente)
        ew.vars.tables.cobros_cliente = currentTable;
    fcobros_clienteedit.addFields([
        ["cliente", [fields.cliente.visible && fields.cliente.required ? ew.Validators.required(fields.cliente.caption) : null], fields.cliente.isInvalid],
        ["id_documento", [fields.id_documento.visible && fields.id_documento.required ? ew.Validators.required(fields.id_documento.caption) : null], fields.id_documento.isInvalid],
        ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null], fields.fecha.isInvalid],
        ["moneda", [fields.moneda.visible && fields.moneda.required ? ew.Validators.required(fields.moneda.caption) : null], fields.moneda.isInvalid],
        ["nota", [fields.nota.visible && fields.nota.required ? ew.Validators.required(fields.nota.caption) : null], fields.nota.isInvalid],
        ["tipo_pago", [fields.tipo_pago.visible && fields.tipo_pago.required ? ew.Validators.required(fields.tipo_pago.caption) : null], fields.tipo_pago.isInvalid],
        ["pivote2", [fields.pivote2.visible && fields.pivote2.required ? ew.Validators.required(fields.pivote2.caption) : null], fields.pivote2.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fcobros_clienteedit,
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
    fcobros_clienteedit.validate = function () {
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
    fcobros_clienteedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fcobros_clienteedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fcobros_clienteedit.lists.tipo_pago = <?= $Page->tipo_pago->toClientList($Page) ?>;
    loadjs.done("fcobros_clienteedit");
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
<form name="fcobros_clienteedit" id="fcobros_clienteedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cobros_cliente">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->cliente->Visible) { // cliente ?>
    <div id="r_cliente" class="form-group row">
        <label id="elh_cobros_cliente_cliente" for="x_cliente" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cliente->caption() ?><?= $Page->cliente->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cliente->cellAttributes() ?>>
<span id="el_cobros_cliente_cliente">
<span<?= $Page->cliente->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->cliente->getDisplayValue($Page->cliente->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cobros_cliente" data-field="x_cliente" data-hidden="1" name="x_cliente" id="x_cliente" value="<?= HtmlEncode($Page->cliente->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->id_documento->Visible) { // id_documento ?>
    <div id="r_id_documento" class="form-group row">
        <label id="elh_cobros_cliente_id_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id_documento->caption() ?><?= $Page->id_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->id_documento->cellAttributes() ?>>
<span id="el_cobros_cliente_id_documento">
<span<?= $Page->id_documento->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->id_documento->getDisplayValue($Page->id_documento->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cobros_cliente" data-field="x_id_documento" data-hidden="1" name="x_id_documento" id="x_id_documento" value="<?= HtmlEncode($Page->id_documento->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <div id="r_fecha" class="form-group row">
        <label id="elh_cobros_cliente_fecha" for="x_fecha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha->caption() ?><?= $Page->fecha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->fecha->cellAttributes() ?>>
<span id="el_cobros_cliente_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->fecha->getDisplayValue($Page->fecha->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cobros_cliente" data-field="x_fecha" data-hidden="1" name="x_fecha" id="x_fecha" value="<?= HtmlEncode($Page->fecha->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <div id="r_moneda" class="form-group row">
        <label id="elh_cobros_cliente_moneda" for="x_moneda" class="<?= $Page->LeftColumnClass ?>"><?= $Page->moneda->caption() ?><?= $Page->moneda->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->moneda->cellAttributes() ?>>
<span id="el_cobros_cliente_moneda">
<span<?= $Page->moneda->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->moneda->getDisplayValue($Page->moneda->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cobros_cliente" data-field="x_moneda" data-hidden="1" name="x_moneda" id="x_moneda" value="<?= HtmlEncode($Page->moneda->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
    <div id="r_nota" class="form-group row">
        <label id="elh_cobros_cliente_nota" for="x_nota" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nota->caption() ?><?= $Page->nota->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nota->cellAttributes() ?>>
<span id="el_cobros_cliente_nota">
<textarea data-table="cobros_cliente" data-field="x_nota" name="x_nota" id="x_nota" cols="30" rows="3" placeholder="<?= HtmlEncode($Page->nota->getPlaceHolder()) ?>"<?= $Page->nota->editAttributes() ?> aria-describedby="x_nota_help"><?= $Page->nota->EditValue ?></textarea>
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
    <input type="hidden" data-table="cobros_cliente" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
<?php
    if (in_array("cobros_cliente_detalle", explode(",", $Page->getCurrentDetailTable())) && $cobros_cliente_detalle->DetailEdit) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("cobros_cliente_detalle", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "CobrosClienteDetalleGrid.php" ?>
<?php } ?>
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
    ew.addEventHandlers("cobros_cliente");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
