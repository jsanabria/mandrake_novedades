<?php

namespace PHPMaker2021\mandrake;

// Page object
$ContMesContableEdit = &$Page;
?>
<script>
var currentForm, currentPageID;
var fcont_mes_contableedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fcont_mes_contableedit = currentForm = new ew.Form("fcont_mes_contableedit", "edit");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "cont_mes_contable")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.cont_mes_contable)
        ew.vars.tables.cont_mes_contable = currentTable;
    fcont_mes_contableedit.addFields([
        ["tipo_comprobante", [fields.tipo_comprobante.visible && fields.tipo_comprobante.required ? ew.Validators.required(fields.tipo_comprobante.caption) : null], fields.tipo_comprobante.isInvalid],
        ["descripcion", [fields.descripcion.visible && fields.descripcion.required ? ew.Validators.required(fields.descripcion.caption) : null], fields.descripcion.isInvalid],
        ["M01", [fields.M01.visible && fields.M01.required ? ew.Validators.required(fields.M01.caption) : null], fields.M01.isInvalid],
        ["M02", [fields.M02.visible && fields.M02.required ? ew.Validators.required(fields.M02.caption) : null], fields.M02.isInvalid],
        ["M03", [fields.M03.visible && fields.M03.required ? ew.Validators.required(fields.M03.caption) : null], fields.M03.isInvalid],
        ["M04", [fields.M04.visible && fields.M04.required ? ew.Validators.required(fields.M04.caption) : null], fields.M04.isInvalid],
        ["M05", [fields.M05.visible && fields.M05.required ? ew.Validators.required(fields.M05.caption) : null], fields.M05.isInvalid],
        ["M06", [fields.M06.visible && fields.M06.required ? ew.Validators.required(fields.M06.caption) : null], fields.M06.isInvalid],
        ["M07", [fields.M07.visible && fields.M07.required ? ew.Validators.required(fields.M07.caption) : null], fields.M07.isInvalid],
        ["M08", [fields.M08.visible && fields.M08.required ? ew.Validators.required(fields.M08.caption) : null], fields.M08.isInvalid],
        ["M09", [fields.M09.visible && fields.M09.required ? ew.Validators.required(fields.M09.caption) : null], fields.M09.isInvalid],
        ["M10", [fields.M10.visible && fields.M10.required ? ew.Validators.required(fields.M10.caption) : null], fields.M10.isInvalid],
        ["M11", [fields.M11.visible && fields.M11.required ? ew.Validators.required(fields.M11.caption) : null], fields.M11.isInvalid],
        ["M12", [fields.M12.visible && fields.M12.required ? ew.Validators.required(fields.M12.caption) : null], fields.M12.isInvalid],
        ["activo", [fields.activo.visible && fields.activo.required ? ew.Validators.required(fields.activo.caption) : null], fields.activo.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fcont_mes_contableedit,
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
    fcont_mes_contableedit.validate = function () {
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
    fcont_mes_contableedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fcont_mes_contableedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fcont_mes_contableedit.lists.M01 = <?= $Page->M01->toClientList($Page) ?>;
    fcont_mes_contableedit.lists.M02 = <?= $Page->M02->toClientList($Page) ?>;
    fcont_mes_contableedit.lists.M03 = <?= $Page->M03->toClientList($Page) ?>;
    fcont_mes_contableedit.lists.M04 = <?= $Page->M04->toClientList($Page) ?>;
    fcont_mes_contableedit.lists.M05 = <?= $Page->M05->toClientList($Page) ?>;
    fcont_mes_contableedit.lists.M06 = <?= $Page->M06->toClientList($Page) ?>;
    fcont_mes_contableedit.lists.M07 = <?= $Page->M07->toClientList($Page) ?>;
    fcont_mes_contableedit.lists.M08 = <?= $Page->M08->toClientList($Page) ?>;
    fcont_mes_contableedit.lists.M09 = <?= $Page->M09->toClientList($Page) ?>;
    fcont_mes_contableedit.lists.M10 = <?= $Page->M10->toClientList($Page) ?>;
    fcont_mes_contableedit.lists.M11 = <?= $Page->M11->toClientList($Page) ?>;
    fcont_mes_contableedit.lists.M12 = <?= $Page->M12->toClientList($Page) ?>;
    fcont_mes_contableedit.lists.activo = <?= $Page->activo->toClientList($Page) ?>;
    loadjs.done("fcont_mes_contableedit");
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
<form name="fcont_mes_contableedit" id="fcont_mes_contableedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cont_mes_contable">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->tipo_comprobante->Visible) { // tipo_comprobante ?>
    <div id="r_tipo_comprobante" class="form-group row">
        <label id="elh_cont_mes_contable_tipo_comprobante" for="x_tipo_comprobante" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo_comprobante->caption() ?><?= $Page->tipo_comprobante->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tipo_comprobante->cellAttributes() ?>>
<span id="el_cont_mes_contable_tipo_comprobante">
<span<?= $Page->tipo_comprobante->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->tipo_comprobante->getDisplayValue($Page->tipo_comprobante->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_mes_contable" data-field="x_tipo_comprobante" data-hidden="1" name="x_tipo_comprobante" id="x_tipo_comprobante" value="<?= HtmlEncode($Page->tipo_comprobante->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
    <div id="r_descripcion" class="form-group row">
        <label id="elh_cont_mes_contable_descripcion" for="x_descripcion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->descripcion->caption() ?><?= $Page->descripcion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->descripcion->cellAttributes() ?>>
<span id="el_cont_mes_contable_descripcion">
<span<?= $Page->descripcion->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->descripcion->getDisplayValue($Page->descripcion->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_mes_contable" data-field="x_descripcion" data-hidden="1" name="x_descripcion" id="x_descripcion" value="<?= HtmlEncode($Page->descripcion->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->M01->Visible) { // M01 ?>
    <div id="r_M01" class="form-group row">
        <label id="elh_cont_mes_contable_M01" class="<?= $Page->LeftColumnClass ?>"><?= $Page->M01->caption() ?><?= $Page->M01->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->M01->cellAttributes() ?>>
<span id="el_cont_mes_contable_M01">
<template id="tp_x_M01">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="cont_mes_contable" data-field="x_M01" name="x_M01" id="x_M01"<?= $Page->M01->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x_M01" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x_M01"
    name="x_M01"
    value="<?= HtmlEncode($Page->M01->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_M01"
    data-target="dsl_x_M01"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M01->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_M01"
    data-value-separator="<?= $Page->M01->displayValueSeparatorAttribute() ?>"
    <?= $Page->M01->editAttributes() ?>>
<?= $Page->M01->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->M01->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->M02->Visible) { // M02 ?>
    <div id="r_M02" class="form-group row">
        <label id="elh_cont_mes_contable_M02" class="<?= $Page->LeftColumnClass ?>"><?= $Page->M02->caption() ?><?= $Page->M02->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->M02->cellAttributes() ?>>
<span id="el_cont_mes_contable_M02">
<template id="tp_x_M02">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="cont_mes_contable" data-field="x_M02" name="x_M02" id="x_M02"<?= $Page->M02->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x_M02" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x_M02"
    name="x_M02"
    value="<?= HtmlEncode($Page->M02->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_M02"
    data-target="dsl_x_M02"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M02->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_M02"
    data-value-separator="<?= $Page->M02->displayValueSeparatorAttribute() ?>"
    <?= $Page->M02->editAttributes() ?>>
<?= $Page->M02->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->M02->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->M03->Visible) { // M03 ?>
    <div id="r_M03" class="form-group row">
        <label id="elh_cont_mes_contable_M03" class="<?= $Page->LeftColumnClass ?>"><?= $Page->M03->caption() ?><?= $Page->M03->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->M03->cellAttributes() ?>>
<span id="el_cont_mes_contable_M03">
<template id="tp_x_M03">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="cont_mes_contable" data-field="x_M03" name="x_M03" id="x_M03"<?= $Page->M03->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x_M03" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x_M03"
    name="x_M03"
    value="<?= HtmlEncode($Page->M03->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_M03"
    data-target="dsl_x_M03"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M03->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_M03"
    data-value-separator="<?= $Page->M03->displayValueSeparatorAttribute() ?>"
    <?= $Page->M03->editAttributes() ?>>
<?= $Page->M03->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->M03->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->M04->Visible) { // M04 ?>
    <div id="r_M04" class="form-group row">
        <label id="elh_cont_mes_contable_M04" class="<?= $Page->LeftColumnClass ?>"><?= $Page->M04->caption() ?><?= $Page->M04->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->M04->cellAttributes() ?>>
<span id="el_cont_mes_contable_M04">
<template id="tp_x_M04">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="cont_mes_contable" data-field="x_M04" name="x_M04" id="x_M04"<?= $Page->M04->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x_M04" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x_M04"
    name="x_M04"
    value="<?= HtmlEncode($Page->M04->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_M04"
    data-target="dsl_x_M04"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M04->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_M04"
    data-value-separator="<?= $Page->M04->displayValueSeparatorAttribute() ?>"
    <?= $Page->M04->editAttributes() ?>>
<?= $Page->M04->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->M04->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->M05->Visible) { // M05 ?>
    <div id="r_M05" class="form-group row">
        <label id="elh_cont_mes_contable_M05" class="<?= $Page->LeftColumnClass ?>"><?= $Page->M05->caption() ?><?= $Page->M05->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->M05->cellAttributes() ?>>
<span id="el_cont_mes_contable_M05">
<template id="tp_x_M05">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="cont_mes_contable" data-field="x_M05" name="x_M05" id="x_M05"<?= $Page->M05->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x_M05" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x_M05"
    name="x_M05"
    value="<?= HtmlEncode($Page->M05->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_M05"
    data-target="dsl_x_M05"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M05->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_M05"
    data-value-separator="<?= $Page->M05->displayValueSeparatorAttribute() ?>"
    <?= $Page->M05->editAttributes() ?>>
<?= $Page->M05->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->M05->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->M06->Visible) { // M06 ?>
    <div id="r_M06" class="form-group row">
        <label id="elh_cont_mes_contable_M06" class="<?= $Page->LeftColumnClass ?>"><?= $Page->M06->caption() ?><?= $Page->M06->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->M06->cellAttributes() ?>>
<span id="el_cont_mes_contable_M06">
<template id="tp_x_M06">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="cont_mes_contable" data-field="x_M06" name="x_M06" id="x_M06"<?= $Page->M06->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x_M06" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x_M06"
    name="x_M06"
    value="<?= HtmlEncode($Page->M06->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_M06"
    data-target="dsl_x_M06"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M06->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_M06"
    data-value-separator="<?= $Page->M06->displayValueSeparatorAttribute() ?>"
    <?= $Page->M06->editAttributes() ?>>
<?= $Page->M06->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->M06->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->M07->Visible) { // M07 ?>
    <div id="r_M07" class="form-group row">
        <label id="elh_cont_mes_contable_M07" class="<?= $Page->LeftColumnClass ?>"><?= $Page->M07->caption() ?><?= $Page->M07->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->M07->cellAttributes() ?>>
<span id="el_cont_mes_contable_M07">
<template id="tp_x_M07">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="cont_mes_contable" data-field="x_M07" name="x_M07" id="x_M07"<?= $Page->M07->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x_M07" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x_M07"
    name="x_M07"
    value="<?= HtmlEncode($Page->M07->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_M07"
    data-target="dsl_x_M07"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M07->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_M07"
    data-value-separator="<?= $Page->M07->displayValueSeparatorAttribute() ?>"
    <?= $Page->M07->editAttributes() ?>>
<?= $Page->M07->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->M07->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->M08->Visible) { // M08 ?>
    <div id="r_M08" class="form-group row">
        <label id="elh_cont_mes_contable_M08" class="<?= $Page->LeftColumnClass ?>"><?= $Page->M08->caption() ?><?= $Page->M08->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->M08->cellAttributes() ?>>
<span id="el_cont_mes_contable_M08">
<template id="tp_x_M08">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="cont_mes_contable" data-field="x_M08" name="x_M08" id="x_M08"<?= $Page->M08->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x_M08" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x_M08"
    name="x_M08"
    value="<?= HtmlEncode($Page->M08->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_M08"
    data-target="dsl_x_M08"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M08->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_M08"
    data-value-separator="<?= $Page->M08->displayValueSeparatorAttribute() ?>"
    <?= $Page->M08->editAttributes() ?>>
<?= $Page->M08->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->M08->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->M09->Visible) { // M09 ?>
    <div id="r_M09" class="form-group row">
        <label id="elh_cont_mes_contable_M09" class="<?= $Page->LeftColumnClass ?>"><?= $Page->M09->caption() ?><?= $Page->M09->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->M09->cellAttributes() ?>>
<span id="el_cont_mes_contable_M09">
<template id="tp_x_M09">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="cont_mes_contable" data-field="x_M09" name="x_M09" id="x_M09"<?= $Page->M09->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x_M09" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x_M09"
    name="x_M09"
    value="<?= HtmlEncode($Page->M09->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_M09"
    data-target="dsl_x_M09"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M09->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_M09"
    data-value-separator="<?= $Page->M09->displayValueSeparatorAttribute() ?>"
    <?= $Page->M09->editAttributes() ?>>
<?= $Page->M09->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->M09->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->M10->Visible) { // M10 ?>
    <div id="r_M10" class="form-group row">
        <label id="elh_cont_mes_contable_M10" class="<?= $Page->LeftColumnClass ?>"><?= $Page->M10->caption() ?><?= $Page->M10->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->M10->cellAttributes() ?>>
<span id="el_cont_mes_contable_M10">
<template id="tp_x_M10">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="cont_mes_contable" data-field="x_M10" name="x_M10" id="x_M10"<?= $Page->M10->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x_M10" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x_M10"
    name="x_M10"
    value="<?= HtmlEncode($Page->M10->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_M10"
    data-target="dsl_x_M10"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M10->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_M10"
    data-value-separator="<?= $Page->M10->displayValueSeparatorAttribute() ?>"
    <?= $Page->M10->editAttributes() ?>>
<?= $Page->M10->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->M10->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->M11->Visible) { // M11 ?>
    <div id="r_M11" class="form-group row">
        <label id="elh_cont_mes_contable_M11" class="<?= $Page->LeftColumnClass ?>"><?= $Page->M11->caption() ?><?= $Page->M11->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->M11->cellAttributes() ?>>
<span id="el_cont_mes_contable_M11">
<template id="tp_x_M11">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="cont_mes_contable" data-field="x_M11" name="x_M11" id="x_M11"<?= $Page->M11->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x_M11" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x_M11"
    name="x_M11"
    value="<?= HtmlEncode($Page->M11->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_M11"
    data-target="dsl_x_M11"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M11->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_M11"
    data-value-separator="<?= $Page->M11->displayValueSeparatorAttribute() ?>"
    <?= $Page->M11->editAttributes() ?>>
<?= $Page->M11->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->M11->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->M12->Visible) { // M12 ?>
    <div id="r_M12" class="form-group row">
        <label id="elh_cont_mes_contable_M12" class="<?= $Page->LeftColumnClass ?>"><?= $Page->M12->caption() ?><?= $Page->M12->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->M12->cellAttributes() ?>>
<span id="el_cont_mes_contable_M12">
<template id="tp_x_M12">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="cont_mes_contable" data-field="x_M12" name="x_M12" id="x_M12"<?= $Page->M12->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x_M12" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x_M12"
    name="x_M12"
    value="<?= HtmlEncode($Page->M12->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_M12"
    data-target="dsl_x_M12"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M12->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_M12"
    data-value-separator="<?= $Page->M12->displayValueSeparatorAttribute() ?>"
    <?= $Page->M12->editAttributes() ?>>
<?= $Page->M12->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->M12->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
    <div id="r_activo" class="form-group row">
        <label id="elh_cont_mes_contable_activo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->activo->caption() ?><?= $Page->activo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->activo->cellAttributes() ?>>
<span id="el_cont_mes_contable_activo">
<template id="tp_x_activo">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="cont_mes_contable" data-field="x_activo" name="x_activo" id="x_activo"<?= $Page->activo->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x_activo" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x_activo"
    name="x_activo"
    value="<?= HtmlEncode($Page->activo->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_activo"
    data-target="dsl_x_activo"
    data-repeatcolumn="5"
    class="form-control<?= $Page->activo->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_activo"
    data-value-separator="<?= $Page->activo->displayValueSeparatorAttribute() ?>"
    <?= $Page->activo->editAttributes() ?>>
<?= $Page->activo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->activo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="cont_mes_contable" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
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
    ew.addEventHandlers("cont_mes_contable");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
