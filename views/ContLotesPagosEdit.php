<?php

namespace PHPMaker2021\mandrake;

// Page object
$ContLotesPagosEdit = &$Page;
?>
<script>
var currentForm, currentPageID;
var fcont_lotes_pagosedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fcont_lotes_pagosedit = currentForm = new ew.Form("fcont_lotes_pagosedit", "edit");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "cont_lotes_pagos")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.cont_lotes_pagos)
        ew.vars.tables.cont_lotes_pagos = currentTable;
    fcont_lotes_pagosedit.addFields([
        ["id", [fields.id.visible && fields.id.required ? ew.Validators.required(fields.id.caption) : null], fields.id.isInvalid],
        ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null], fields.fecha.isInvalid],
        ["banco", [fields.banco.visible && fields.banco.required ? ew.Validators.required(fields.banco.caption) : null], fields.banco.isInvalid],
        ["referencia", [fields.referencia.visible && fields.referencia.required ? ew.Validators.required(fields.referencia.caption) : null], fields.referencia.isInvalid],
        ["moneda", [fields.moneda.visible && fields.moneda.required ? ew.Validators.required(fields.moneda.caption) : null], fields.moneda.isInvalid],
        ["procesado", [fields.procesado.visible && fields.procesado.required ? ew.Validators.required(fields.procesado.caption) : null], fields.procesado.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fcont_lotes_pagosedit,
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
    fcont_lotes_pagosedit.validate = function () {
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
    fcont_lotes_pagosedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fcont_lotes_pagosedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fcont_lotes_pagosedit.lists.procesado = <?= $Page->procesado->toClientList($Page) ?>;
    loadjs.done("fcont_lotes_pagosedit");
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
<form name="fcont_lotes_pagosedit" id="fcont_lotes_pagosedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cont_lotes_pagos">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->id->Visible) { // id ?>
    <div id="r_id" class="form-group row">
        <label id="elh_cont_lotes_pagos_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id->caption() ?><?= $Page->id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->id->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_id">
<span<?= $Page->id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->id->getDisplayValue($Page->id->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_lotes_pagos" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <div id="r_fecha" class="form-group row">
        <label id="elh_cont_lotes_pagos_fecha" for="x_fecha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha->caption() ?><?= $Page->fecha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->fecha->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->fecha->getDisplayValue($Page->fecha->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_lotes_pagos" data-field="x_fecha" data-hidden="1" name="x_fecha" id="x_fecha" value="<?= HtmlEncode($Page->fecha->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->banco->Visible) { // banco ?>
    <div id="r_banco" class="form-group row">
        <label id="elh_cont_lotes_pagos_banco" for="x_banco" class="<?= $Page->LeftColumnClass ?>"><?= $Page->banco->caption() ?><?= $Page->banco->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->banco->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_banco">
<span<?= $Page->banco->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->banco->getDisplayValue($Page->banco->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_lotes_pagos" data-field="x_banco" data-hidden="1" name="x_banco" id="x_banco" value="<?= HtmlEncode($Page->banco->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
    <div id="r_referencia" class="form-group row">
        <label id="elh_cont_lotes_pagos_referencia" for="x_referencia" class="<?= $Page->LeftColumnClass ?>"><?= $Page->referencia->caption() ?><?= $Page->referencia->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->referencia->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_referencia">
<span<?= $Page->referencia->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->referencia->getDisplayValue($Page->referencia->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_lotes_pagos" data-field="x_referencia" data-hidden="1" name="x_referencia" id="x_referencia" value="<?= HtmlEncode($Page->referencia->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <div id="r_moneda" class="form-group row">
        <label id="elh_cont_lotes_pagos_moneda" for="x_moneda" class="<?= $Page->LeftColumnClass ?>"><?= $Page->moneda->caption() ?><?= $Page->moneda->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->moneda->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_moneda">
<span<?= $Page->moneda->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->moneda->getDisplayValue($Page->moneda->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_lotes_pagos" data-field="x_moneda" data-hidden="1" name="x_moneda" id="x_moneda" value="<?= HtmlEncode($Page->moneda->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->procesado->Visible) { // procesado ?>
    <div id="r_procesado" class="form-group row">
        <label id="elh_cont_lotes_pagos_procesado" for="x_procesado" class="<?= $Page->LeftColumnClass ?>"><?= $Page->procesado->caption() ?><?= $Page->procesado->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->procesado->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_procesado">
    <select
        id="x_procesado"
        name="x_procesado"
        class="form-control ew-select<?= $Page->procesado->isInvalidClass() ?>"
        data-select2-id="cont_lotes_pagos_x_procesado"
        data-table="cont_lotes_pagos"
        data-field="x_procesado"
        data-value-separator="<?= $Page->procesado->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->procesado->getPlaceHolder()) ?>"
        <?= $Page->procesado->editAttributes() ?>>
        <?= $Page->procesado->selectOptionListHtml("x_procesado") ?>
    </select>
    <?= $Page->procesado->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->procesado->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='cont_lotes_pagos_x_procesado']"),
        options = { name: "x_procesado", selectId: "cont_lotes_pagos_x_procesado", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.cont_lotes_pagos.fields.procesado.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.cont_lotes_pagos.fields.procesado.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php
    if (in_array("cont_lotes_pagos_detalle", explode(",", $Page->getCurrentDetailTable())) && $cont_lotes_pagos_detalle->DetailEdit) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("cont_lotes_pagos_detalle", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "ContLotesPagosDetalleGrid.php" ?>
<?php } ?>
<?php if (!$Page->IsModal) { ?>
<div class="form-group row"><!-- buttons .form-group -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("SaveBtn") ?></button>
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
    ew.addEventHandlers("cont_lotes_pagos");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
