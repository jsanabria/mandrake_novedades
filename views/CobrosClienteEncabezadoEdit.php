<?php

namespace PHPMaker2021\mandrake;

// Page object
$CobrosClienteEncabezadoEdit = &$Page;
?>
<script>
var currentForm, currentPageID;
var fcobros_cliente_encabezadoedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fcobros_cliente_encabezadoedit = currentForm = new ew.Form("fcobros_cliente_encabezadoedit", "edit");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "cobros_cliente_encabezado")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.cobros_cliente_encabezado)
        ew.vars.tables.cobros_cliente_encabezado = currentTable;
    fcobros_cliente_encabezadoedit.addFields([
        ["id", [fields.id.visible && fields.id.required ? ew.Validators.required(fields.id.caption) : null], fields.id.isInvalid],
        ["cliente", [fields.cliente.visible && fields.cliente.required ? ew.Validators.required(fields.cliente.caption) : null], fields.cliente.isInvalid],
        ["id_documento", [fields.id_documento.visible && fields.id_documento.required ? ew.Validators.required(fields.id_documento.caption) : null], fields.id_documento.isInvalid],
        ["tipo_documento", [fields.tipo_documento.visible && fields.tipo_documento.required ? ew.Validators.required(fields.tipo_documento.caption) : null], fields.tipo_documento.isInvalid],
        ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null, ew.Validators.datetime(0)], fields.fecha.isInvalid],
        ["monto", [fields.monto.visible && fields.monto.required ? ew.Validators.required(fields.monto.caption) : null], fields.monto.isInvalid],
        ["moneda", [fields.moneda.visible && fields.moneda.required ? ew.Validators.required(fields.moneda.caption) : null], fields.moneda.isInvalid],
        ["nota", [fields.nota.visible && fields.nota.required ? ew.Validators.required(fields.nota.caption) : null], fields.nota.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fcobros_cliente_encabezadoedit,
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
    fcobros_cliente_encabezadoedit.validate = function () {
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
    fcobros_cliente_encabezadoedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fcobros_cliente_encabezadoedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("fcobros_cliente_encabezadoedit");
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
<form name="fcobros_cliente_encabezadoedit" id="fcobros_cliente_encabezadoedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cobros_cliente_encabezado">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->id->Visible) { // id ?>
    <div id="r_id" class="form-group row">
        <label id="elh_cobros_cliente_encabezado_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id->caption() ?><?= $Page->id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->id->cellAttributes() ?>>
<span id="el_cobros_cliente_encabezado_id">
<span<?= $Page->id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->id->getDisplayValue($Page->id->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cobros_cliente_encabezado" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
    <div id="r_cliente" class="form-group row">
        <label id="elh_cobros_cliente_encabezado_cliente" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cliente->caption() ?><?= $Page->cliente->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cliente->cellAttributes() ?>>
<span id="el_cobros_cliente_encabezado_cliente">
<span<?= $Page->cliente->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->cliente->getDisplayValue($Page->cliente->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cobros_cliente_encabezado" data-field="x_cliente" data-hidden="1" name="x_cliente" id="x_cliente" value="<?= HtmlEncode($Page->cliente->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->id_documento->Visible) { // id_documento ?>
    <div id="r_id_documento" class="form-group row">
        <label id="elh_cobros_cliente_encabezado_id_documento" for="x_id_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id_documento->caption() ?><?= $Page->id_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->id_documento->cellAttributes() ?>>
<span id="el_cobros_cliente_encabezado_id_documento">
<span<?= $Page->id_documento->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->id_documento->getDisplayValue($Page->id_documento->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cobros_cliente_encabezado" data-field="x_id_documento" data-hidden="1" name="x_id_documento" id="x_id_documento" value="<?= HtmlEncode($Page->id_documento->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
    <div id="r_tipo_documento" class="form-group row">
        <label id="elh_cobros_cliente_encabezado_tipo_documento" for="x_tipo_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo_documento->caption() ?><?= $Page->tipo_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tipo_documento->cellAttributes() ?>>
<span id="el_cobros_cliente_encabezado_tipo_documento">
<span<?= $Page->tipo_documento->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->tipo_documento->getDisplayValue($Page->tipo_documento->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cobros_cliente_encabezado" data-field="x_tipo_documento" data-hidden="1" name="x_tipo_documento" id="x_tipo_documento" value="<?= HtmlEncode($Page->tipo_documento->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <div id="r_fecha" class="form-group row">
        <label id="elh_cobros_cliente_encabezado_fecha" for="x_fecha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha->caption() ?><?= $Page->fecha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->fecha->cellAttributes() ?>>
<span id="el_cobros_cliente_encabezado_fecha">
<input type="<?= $Page->fecha->getInputTextType() ?>" data-table="cobros_cliente_encabezado" data-field="x_fecha" name="x_fecha" id="x_fecha" maxlength="10" placeholder="<?= HtmlEncode($Page->fecha->getPlaceHolder()) ?>" value="<?= $Page->fecha->EditValue ?>"<?= $Page->fecha->editAttributes() ?> aria-describedby="x_fecha_help">
<?= $Page->fecha->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fecha->getErrorMessage() ?></div>
<?php if (!$Page->fecha->ReadOnly && !$Page->fecha->Disabled && !isset($Page->fecha->EditAttrs["readonly"]) && !isset($Page->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fcobros_cliente_encabezadoedit", "datetimepicker"], function() {
    ew.createDateTimePicker("fcobros_cliente_encabezadoedit", "x_fecha", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->monto->Visible) { // monto ?>
    <div id="r_monto" class="form-group row">
        <label id="elh_cobros_cliente_encabezado_monto" for="x_monto" class="<?= $Page->LeftColumnClass ?>"><?= $Page->monto->caption() ?><?= $Page->monto->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->monto->cellAttributes() ?>>
<span id="el_cobros_cliente_encabezado_monto">
<span<?= $Page->monto->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->monto->getDisplayValue($Page->monto->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cobros_cliente_encabezado" data-field="x_monto" data-hidden="1" name="x_monto" id="x_monto" value="<?= HtmlEncode($Page->monto->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <div id="r_moneda" class="form-group row">
        <label id="elh_cobros_cliente_encabezado_moneda" for="x_moneda" class="<?= $Page->LeftColumnClass ?>"><?= $Page->moneda->caption() ?><?= $Page->moneda->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->moneda->cellAttributes() ?>>
<span id="el_cobros_cliente_encabezado_moneda">
<span<?= $Page->moneda->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->moneda->getDisplayValue($Page->moneda->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cobros_cliente_encabezado" data-field="x_moneda" data-hidden="1" name="x_moneda" id="x_moneda" value="<?= HtmlEncode($Page->moneda->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
    <div id="r_nota" class="form-group row">
        <label id="elh_cobros_cliente_encabezado_nota" for="x_nota" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nota->caption() ?><?= $Page->nota->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nota->cellAttributes() ?>>
<span id="el_cobros_cliente_encabezado_nota">
<input type="<?= $Page->nota->getInputTextType() ?>" data-table="cobros_cliente_encabezado" data-field="x_nota" name="x_nota" id="x_nota" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->nota->getPlaceHolder()) ?>" value="<?= $Page->nota->EditValue ?>"<?= $Page->nota->editAttributes() ?> aria-describedby="x_nota_help">
<?= $Page->nota->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nota->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
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
    ew.addEventHandlers("cobros_cliente_encabezado");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
