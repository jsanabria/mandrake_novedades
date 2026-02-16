<?php

namespace PHPMaker2021\mandrake;

// Page object
$PagosEdit = &$Page;
?>
<script>
var currentForm, currentPageID;
var fpagosedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fpagosedit = currentForm = new ew.Form("fpagosedit", "edit");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "pagos")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.pagos)
        ew.vars.tables.pagos = currentTable;
    fpagosedit.addFields([
        ["tipo_pago", [fields.tipo_pago.visible && fields.tipo_pago.required ? ew.Validators.required(fields.tipo_pago.caption) : null], fields.tipo_pago.isInvalid],
        ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null, ew.Validators.datetime(0)], fields.fecha.isInvalid],
        ["referencia", [fields.referencia.visible && fields.referencia.required ? ew.Validators.required(fields.referencia.caption) : null], fields.referencia.isInvalid],
        ["monto", [fields.monto.visible && fields.monto.required ? ew.Validators.required(fields.monto.caption) : null, ew.Validators.float], fields.monto.isInvalid],
        ["nota", [fields.nota.visible && fields.nota.required ? ew.Validators.required(fields.nota.caption) : null], fields.nota.isInvalid],
        ["comprobante_pago", [fields.comprobante_pago.visible && fields.comprobante_pago.required ? ew.Validators.fileRequired(fields.comprobante_pago.caption) : null], fields.comprobante_pago.isInvalid],
        ["comprobante", [fields.comprobante.visible && fields.comprobante.required ? ew.Validators.required(fields.comprobante.caption) : null, ew.Validators.integer], fields.comprobante.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fpagosedit,
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
    fpagosedit.validate = function () {
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
    fpagosedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fpagosedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fpagosedit.lists.tipo_pago = <?= $Page->tipo_pago->toClientList($Page) ?>;
    loadjs.done("fpagosedit");
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
<form name="fpagosedit" id="fpagosedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="pagos">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "salidas") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="salidas">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->id_documento->getSessionValue()) ?>">
<input type="hidden" name="fk_tipo_documento" value="<?= HtmlEncode($Page->tipo_documento->getSessionValue()) ?>">
<?php } ?>
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->tipo_pago->Visible) { // tipo_pago ?>
    <div id="r_tipo_pago" class="form-group row">
        <label id="elh_pagos_tipo_pago" for="x_tipo_pago" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo_pago->caption() ?><?= $Page->tipo_pago->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tipo_pago->cellAttributes() ?>>
<span id="el_pagos_tipo_pago">
    <select
        id="x_tipo_pago"
        name="x_tipo_pago"
        class="form-control ew-select<?= $Page->tipo_pago->isInvalidClass() ?>"
        data-select2-id="pagos_x_tipo_pago"
        data-table="pagos"
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
    var el = document.querySelector("select[data-select2-id='pagos_x_tipo_pago']"),
        options = { name: "x_tipo_pago", selectId: "pagos_x_tipo_pago", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.pagos.fields.tipo_pago.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <div id="r_fecha" class="form-group row">
        <label id="elh_pagos_fecha" for="x_fecha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha->caption() ?><?= $Page->fecha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->fecha->cellAttributes() ?>>
<span id="el_pagos_fecha">
<input type="<?= $Page->fecha->getInputTextType() ?>" data-table="pagos" data-field="x_fecha" name="x_fecha" id="x_fecha" placeholder="<?= HtmlEncode($Page->fecha->getPlaceHolder()) ?>" value="<?= $Page->fecha->EditValue ?>"<?= $Page->fecha->editAttributes() ?> aria-describedby="x_fecha_help">
<?= $Page->fecha->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fecha->getErrorMessage() ?></div>
<?php if (!$Page->fecha->ReadOnly && !$Page->fecha->Disabled && !isset($Page->fecha->EditAttrs["readonly"]) && !isset($Page->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpagosedit", "datetimepicker"], function() {
    ew.createDateTimePicker("fpagosedit", "x_fecha", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
    <div id="r_referencia" class="form-group row">
        <label id="elh_pagos_referencia" for="x_referencia" class="<?= $Page->LeftColumnClass ?>"><?= $Page->referencia->caption() ?><?= $Page->referencia->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->referencia->cellAttributes() ?>>
<span id="el_pagos_referencia">
<input type="<?= $Page->referencia->getInputTextType() ?>" data-table="pagos" data-field="x_referencia" name="x_referencia" id="x_referencia" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->referencia->getPlaceHolder()) ?>" value="<?= $Page->referencia->EditValue ?>"<?= $Page->referencia->editAttributes() ?> aria-describedby="x_referencia_help">
<?= $Page->referencia->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->referencia->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->monto->Visible) { // monto ?>
    <div id="r_monto" class="form-group row">
        <label id="elh_pagos_monto" for="x_monto" class="<?= $Page->LeftColumnClass ?>"><?= $Page->monto->caption() ?><?= $Page->monto->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->monto->cellAttributes() ?>>
<span id="el_pagos_monto">
<input type="<?= $Page->monto->getInputTextType() ?>" data-table="pagos" data-field="x_monto" name="x_monto" id="x_monto" size="30" maxlength="14" placeholder="<?= HtmlEncode($Page->monto->getPlaceHolder()) ?>" value="<?= $Page->monto->EditValue ?>"<?= $Page->monto->editAttributes() ?> aria-describedby="x_monto_help">
<?= $Page->monto->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->monto->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
    <div id="r_nota" class="form-group row">
        <label id="elh_pagos_nota" for="x_nota" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nota->caption() ?><?= $Page->nota->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nota->cellAttributes() ?>>
<span id="el_pagos_nota">
<input type="<?= $Page->nota->getInputTextType() ?>" data-table="pagos" data-field="x_nota" name="x_nota" id="x_nota" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->nota->getPlaceHolder()) ?>" value="<?= $Page->nota->EditValue ?>"<?= $Page->nota->editAttributes() ?> aria-describedby="x_nota_help">
<?= $Page->nota->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nota->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->comprobante_pago->Visible) { // comprobante_pago ?>
    <div id="r_comprobante_pago" class="form-group row">
        <label id="elh_pagos_comprobante_pago" class="<?= $Page->LeftColumnClass ?>"><?= $Page->comprobante_pago->caption() ?><?= $Page->comprobante_pago->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->comprobante_pago->cellAttributes() ?>>
<span id="el_pagos_comprobante_pago">
<div id="fd_x_comprobante_pago">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->comprobante_pago->title() ?>" data-table="pagos" data-field="x_comprobante_pago" name="x_comprobante_pago" id="x_comprobante_pago" lang="<?= CurrentLanguageID() ?>"<?= $Page->comprobante_pago->editAttributes() ?><?= ($Page->comprobante_pago->ReadOnly || $Page->comprobante_pago->Disabled) ? " disabled" : "" ?> aria-describedby="x_comprobante_pago_help">
        <label class="custom-file-label ew-file-label" for="x_comprobante_pago"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->comprobante_pago->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->comprobante_pago->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_comprobante_pago" id= "fn_x_comprobante_pago" value="<?= $Page->comprobante_pago->Upload->FileName ?>">
<input type="hidden" name="fa_x_comprobante_pago" id= "fa_x_comprobante_pago" value="<?= (Post("fa_x_comprobante_pago") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_comprobante_pago" id= "fs_x_comprobante_pago" value="255">
<input type="hidden" name="fx_x_comprobante_pago" id= "fx_x_comprobante_pago" value="<?= $Page->comprobante_pago->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_comprobante_pago" id= "fm_x_comprobante_pago" value="<?= $Page->comprobante_pago->UploadMaxFileSize ?>">
</div>
<table id="ft_x_comprobante_pago" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->comprobante->Visible) { // comprobante ?>
    <div id="r_comprobante" class="form-group row">
        <label id="elh_pagos_comprobante" for="x_comprobante" class="<?= $Page->LeftColumnClass ?>"><?= $Page->comprobante->caption() ?><?= $Page->comprobante->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->comprobante->cellAttributes() ?>>
<span id="el_pagos_comprobante">
<input type="<?= $Page->comprobante->getInputTextType() ?>" data-table="pagos" data-field="x_comprobante" name="x_comprobante" id="x_comprobante" size="30" placeholder="<?= HtmlEncode($Page->comprobante->getPlaceHolder()) ?>" value="<?= $Page->comprobante->EditValue ?>"<?= $Page->comprobante->editAttributes() ?> aria-describedby="x_comprobante_help">
<?= $Page->comprobante->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->comprobante->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="pagos" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
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
    ew.addEventHandlers("pagos");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
