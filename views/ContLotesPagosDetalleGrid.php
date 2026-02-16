<?php

namespace PHPMaker2021\mandrake;

// Set up and run Grid object
$Grid = Container("ContLotesPagosDetalleGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fcont_lotes_pagos_detallegrid;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    fcont_lotes_pagos_detallegrid = new ew.Form("fcont_lotes_pagos_detallegrid", "grid");
    fcont_lotes_pagos_detallegrid.formKeyCountName = '<?= $Grid->FormKeyCountName ?>';

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "cont_lotes_pagos_detalle")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.cont_lotes_pagos_detalle)
        ew.vars.tables.cont_lotes_pagos_detalle = currentTable;
    fcont_lotes_pagos_detallegrid.addFields([
        ["proveedor", [fields.proveedor.visible && fields.proveedor.required ? ew.Validators.required(fields.proveedor.caption) : null], fields.proveedor.isInvalid],
        ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null], fields.fecha.isInvalid],
        ["tipodoc", [fields.tipodoc.visible && fields.tipodoc.required ? ew.Validators.required(fields.tipodoc.caption) : null], fields.tipodoc.isInvalid],
        ["nro_documento", [fields.nro_documento.visible && fields.nro_documento.required ? ew.Validators.required(fields.nro_documento.caption) : null], fields.nro_documento.isInvalid],
        ["monto_a_pagar", [fields.monto_a_pagar.visible && fields.monto_a_pagar.required ? ew.Validators.required(fields.monto_a_pagar.caption) : null], fields.monto_a_pagar.isInvalid],
        ["monto_pagado", [fields.monto_pagado.visible && fields.monto_pagado.required ? ew.Validators.required(fields.monto_pagado.caption) : null], fields.monto_pagado.isInvalid],
        ["saldo", [fields.saldo.visible && fields.saldo.required ? ew.Validators.required(fields.saldo.caption) : null, ew.Validators.float], fields.saldo.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fcont_lotes_pagos_detallegrid,
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
    fcont_lotes_pagos_detallegrid.validate = function () {
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
            var checkrow = (gridinsert) ? !this.emptyRow(rowIndex) : true;
            if (checkrow) {
                addcnt++;

            // Validate fields
            if (!this.validateFields(rowIndex))
                return false;

            // Call Form_CustomValidate event
            if (!this.customValidate(fobj)) {
                this.focus();
                return false;
            }
            } // End Grid Add checking
        }
        return true;
    }

    // Check empty row
    fcont_lotes_pagos_detallegrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "proveedor", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "fecha", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "tipodoc", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "nro_documento", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "monto_a_pagar", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "monto_pagado", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "saldo", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    fcont_lotes_pagos_detallegrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fcont_lotes_pagos_detallegrid.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fcont_lotes_pagos_detallegrid.lists.proveedor = <?= $Grid->proveedor->toClientList($Grid) ?>;
    fcont_lotes_pagos_detallegrid.lists.tipodoc = <?= $Grid->tipodoc->toClientList($Grid) ?>;
    loadjs.done("fcont_lotes_pagos_detallegrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> cont_lotes_pagos_detalle">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="fcont_lotes_pagos_detallegrid" class="ew-form ew-list-form form-inline">
<div id="gmp_cont_lotes_pagos_detalle" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_cont_lotes_pagos_detallegrid" class="table ew-table"><!-- .ew-table -->
<thead>
    <tr class="ew-table-header">
<?php
// Header row
$Grid->RowType = ROWTYPE_HEADER;

// Render list options
$Grid->renderListOptions();

// Render list options (header, left)
$Grid->ListOptions->render("header", "left");
?>
<?php if ($Grid->proveedor->Visible) { // proveedor ?>
        <th data-name="proveedor" class="<?= $Grid->proveedor->headerCellClass() ?>"><div id="elh_cont_lotes_pagos_detalle_proveedor" class="cont_lotes_pagos_detalle_proveedor"><?= $Grid->renderSort($Grid->proveedor) ?></div></th>
<?php } ?>
<?php if ($Grid->fecha->Visible) { // fecha ?>
        <th data-name="fecha" class="<?= $Grid->fecha->headerCellClass() ?>"><div id="elh_cont_lotes_pagos_detalle_fecha" class="cont_lotes_pagos_detalle_fecha"><?= $Grid->renderSort($Grid->fecha) ?></div></th>
<?php } ?>
<?php if ($Grid->tipodoc->Visible) { // tipodoc ?>
        <th data-name="tipodoc" class="<?= $Grid->tipodoc->headerCellClass() ?>"><div id="elh_cont_lotes_pagos_detalle_tipodoc" class="cont_lotes_pagos_detalle_tipodoc"><?= $Grid->renderSort($Grid->tipodoc) ?></div></th>
<?php } ?>
<?php if ($Grid->nro_documento->Visible) { // nro_documento ?>
        <th data-name="nro_documento" class="<?= $Grid->nro_documento->headerCellClass() ?>"><div id="elh_cont_lotes_pagos_detalle_nro_documento" class="cont_lotes_pagos_detalle_nro_documento"><?= $Grid->renderSort($Grid->nro_documento) ?></div></th>
<?php } ?>
<?php if ($Grid->monto_a_pagar->Visible) { // monto_a_pagar ?>
        <th data-name="monto_a_pagar" class="<?= $Grid->monto_a_pagar->headerCellClass() ?>"><div id="elh_cont_lotes_pagos_detalle_monto_a_pagar" class="cont_lotes_pagos_detalle_monto_a_pagar"><?= $Grid->renderSort($Grid->monto_a_pagar) ?></div></th>
<?php } ?>
<?php if ($Grid->monto_pagado->Visible) { // monto_pagado ?>
        <th data-name="monto_pagado" class="<?= $Grid->monto_pagado->headerCellClass() ?>"><div id="elh_cont_lotes_pagos_detalle_monto_pagado" class="cont_lotes_pagos_detalle_monto_pagado"><?= $Grid->renderSort($Grid->monto_pagado) ?></div></th>
<?php } ?>
<?php if ($Grid->saldo->Visible) { // saldo ?>
        <th data-name="saldo" class="<?= $Grid->saldo->headerCellClass() ?>"><div id="elh_cont_lotes_pagos_detalle_saldo" class="cont_lotes_pagos_detalle_saldo"><?= $Grid->renderSort($Grid->saldo) ?></div></th>
<?php } ?>
<?php
// Render list options (header, right)
$Grid->ListOptions->render("header", "right");
?>
    </tr>
</thead>
<tbody>
<?php
$Grid->StartRecord = 1;
$Grid->StopRecord = $Grid->TotalRecords; // Show all records

// Restore number of post back records
if ($CurrentForm && ($Grid->isConfirm() || $Grid->EventCancelled)) {
    $CurrentForm->Index = -1;
    if ($CurrentForm->hasValue($Grid->FormKeyCountName) && ($Grid->isGridAdd() || $Grid->isGridEdit() || $Grid->isConfirm())) {
        $Grid->KeyCount = $CurrentForm->getValue($Grid->FormKeyCountName);
        $Grid->StopRecord = $Grid->StartRecord + $Grid->KeyCount - 1;
    }
}
$Grid->RecordCount = $Grid->StartRecord - 1;
if ($Grid->Recordset && !$Grid->Recordset->EOF) {
    // Nothing to do
} elseif (!$Grid->AllowAddDeleteRow && $Grid->StopRecord == 0) {
    $Grid->StopRecord = $Grid->GridAddRowCount;
}

// Initialize aggregate
$Grid->RowType = ROWTYPE_AGGREGATEINIT;
$Grid->resetAttributes();
$Grid->renderRow();
if ($Grid->isGridAdd())
    $Grid->RowIndex = 0;
if ($Grid->isGridEdit())
    $Grid->RowIndex = 0;
while ($Grid->RecordCount < $Grid->StopRecord) {
    $Grid->RecordCount++;
    if ($Grid->RecordCount >= $Grid->StartRecord) {
        $Grid->RowCount++;
        if ($Grid->isGridAdd() || $Grid->isGridEdit() || $Grid->isConfirm()) {
            $Grid->RowIndex++;
            $CurrentForm->Index = $Grid->RowIndex;
            if ($CurrentForm->hasValue($Grid->FormActionName) && ($Grid->isConfirm() || $Grid->EventCancelled)) {
                $Grid->RowAction = strval($CurrentForm->getValue($Grid->FormActionName));
            } elseif ($Grid->isGridAdd()) {
                $Grid->RowAction = "insert";
            } else {
                $Grid->RowAction = "";
            }
        }

        // Set up key count
        $Grid->KeyCount = $Grid->RowIndex;

        // Init row class and style
        $Grid->resetAttributes();
        $Grid->CssClass = "";
        if ($Grid->isGridAdd()) {
            if ($Grid->CurrentMode == "copy") {
                $Grid->loadRowValues($Grid->Recordset); // Load row values
                $Grid->OldKey = $Grid->getKey(true); // Get from CurrentValue
            } else {
                $Grid->loadRowValues(); // Load default values
                $Grid->OldKey = "";
            }
        } else {
            $Grid->loadRowValues($Grid->Recordset); // Load row values
            $Grid->OldKey = $Grid->getKey(true); // Get from CurrentValue
        }
        $Grid->setKey($Grid->OldKey);
        $Grid->RowType = ROWTYPE_VIEW; // Render view
        if ($Grid->isGridAdd()) { // Grid add
            $Grid->RowType = ROWTYPE_ADD; // Render add
        }
        if ($Grid->isGridAdd() && $Grid->EventCancelled && !$CurrentForm->hasValue("k_blankrow")) { // Insert failed
            $Grid->restoreCurrentRowFormValues($Grid->RowIndex); // Restore form values
        }
        if ($Grid->isGridEdit()) { // Grid edit
            if ($Grid->EventCancelled) {
                $Grid->restoreCurrentRowFormValues($Grid->RowIndex); // Restore form values
            }
            if ($Grid->RowAction == "insert") {
                $Grid->RowType = ROWTYPE_ADD; // Render add
            } else {
                $Grid->RowType = ROWTYPE_EDIT; // Render edit
            }
        }
        if ($Grid->isGridEdit() && ($Grid->RowType == ROWTYPE_EDIT || $Grid->RowType == ROWTYPE_ADD) && $Grid->EventCancelled) { // Update failed
            $Grid->restoreCurrentRowFormValues($Grid->RowIndex); // Restore form values
        }
        if ($Grid->RowType == ROWTYPE_EDIT) { // Edit row
            $Grid->EditRowCount++;
        }
        if ($Grid->isConfirm()) { // Confirm row
            $Grid->restoreCurrentRowFormValues($Grid->RowIndex); // Restore form values
        }

        // Set up row id / data-rowindex
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowCount, "id" => "r" . $Grid->RowCount . "_cont_lotes_pagos_detalle", "data-rowtype" => $Grid->RowType]);

        // Render row
        $Grid->renderRow();

        // Render list options
        $Grid->renderListOptions();

        // Skip delete row / empty row for confirm page
        if ($Grid->RowAction != "delete" && $Grid->RowAction != "insertdelete" && !($Grid->RowAction == "insert" && $Grid->isConfirm() && $Grid->emptyRow())) {
?>
    <tr <?= $Grid->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Grid->ListOptions->render("body", "left", $Grid->RowCount);
?>
    <?php if ($Grid->proveedor->Visible) { // proveedor ?>
        <td data-name="proveedor" <?= $Grid->proveedor->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_cont_lotes_pagos_detalle_proveedor" class="form-group">
<?php
$onchange = $Grid->proveedor->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Grid->proveedor->EditAttrs["onchange"] = "";
?>
<span id="as_x<?= $Grid->RowIndex ?>_proveedor" class="ew-auto-suggest">
    <div class="input-group flex-nowrap">
        <input type="<?= $Grid->proveedor->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_proveedor" id="sv_x<?= $Grid->RowIndex ?>_proveedor" value="<?= RemoveHtml($Grid->proveedor->EditValue) ?>" size="30" maxlength="10" placeholder="<?= HtmlEncode($Grid->proveedor->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->proveedor->getPlaceHolder()) ?>"<?= $Grid->proveedor->editAttributes() ?>>
        <div class="input-group-append">
            <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Grid->proveedor->caption()), $Language->phrase("LookupLink", true))) ?>" onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_proveedor',m:0,n:10,srch:true});" class="ew-lookup-btn btn btn-default"<?= ($Grid->proveedor->ReadOnly || $Grid->proveedor->Disabled) ? " disabled" : "" ?>><i class="fas fa-search ew-icon"></i></button>
        </div>
    </div>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="cont_lotes_pagos_detalle" data-field="x_proveedor" data-input="sv_x<?= $Grid->RowIndex ?>_proveedor" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Grid->proveedor->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_proveedor" id="x<?= $Grid->RowIndex ?>_proveedor" value="<?= HtmlEncode($Grid->proveedor->CurrentValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Grid->proveedor->getErrorMessage() ?></div>
<script>
loadjs.ready(["fcont_lotes_pagos_detallegrid"], function() {
    fcont_lotes_pagos_detallegrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_proveedor","forceSelect":false}, ew.vars.tables.cont_lotes_pagos_detalle.fields.proveedor.autoSuggestOptions));
});
</script>
<?= $Grid->proveedor->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_proveedor") ?>
</span>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_proveedor" data-hidden="1" name="o<?= $Grid->RowIndex ?>_proveedor" id="o<?= $Grid->RowIndex ?>_proveedor" value="<?= HtmlEncode($Grid->proveedor->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_cont_lotes_pagos_detalle_proveedor" class="form-group">
<span<?= $Grid->proveedor->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->proveedor->getDisplayValue($Grid->proveedor->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_proveedor" data-hidden="1" name="x<?= $Grid->RowIndex ?>_proveedor" id="x<?= $Grid->RowIndex ?>_proveedor" value="<?= HtmlEncode($Grid->proveedor->CurrentValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_cont_lotes_pagos_detalle_proveedor">
<span<?= $Grid->proveedor->viewAttributes() ?>>
<?= $Grid->proveedor->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_proveedor" data-hidden="1" name="fcont_lotes_pagos_detallegrid$x<?= $Grid->RowIndex ?>_proveedor" id="fcont_lotes_pagos_detallegrid$x<?= $Grid->RowIndex ?>_proveedor" value="<?= HtmlEncode($Grid->proveedor->FormValue) ?>">
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_proveedor" data-hidden="1" name="fcont_lotes_pagos_detallegrid$o<?= $Grid->RowIndex ?>_proveedor" id="fcont_lotes_pagos_detallegrid$o<?= $Grid->RowIndex ?>_proveedor" value="<?= HtmlEncode($Grid->proveedor->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->fecha->Visible) { // fecha ?>
        <td data-name="fecha" <?= $Grid->fecha->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_cont_lotes_pagos_detalle_fecha" class="form-group">
<input type="<?= $Grid->fecha->getInputTextType() ?>" data-table="cont_lotes_pagos_detalle" data-field="x_fecha" data-format="7" name="x<?= $Grid->RowIndex ?>_fecha" id="x<?= $Grid->RowIndex ?>_fecha" maxlength="10" placeholder="<?= HtmlEncode($Grid->fecha->getPlaceHolder()) ?>" value="<?= $Grid->fecha->EditValue ?>"<?= $Grid->fecha->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->fecha->getErrorMessage() ?></div>
<?php if (!$Grid->fecha->ReadOnly && !$Grid->fecha->Disabled && !isset($Grid->fecha->EditAttrs["readonly"]) && !isset($Grid->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fcont_lotes_pagos_detallegrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fcont_lotes_pagos_detallegrid", "x<?= $Grid->RowIndex ?>_fecha", {"ignoreReadonly":true,"useCurrent":false,"format":7});
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_fecha" data-hidden="1" name="o<?= $Grid->RowIndex ?>_fecha" id="o<?= $Grid->RowIndex ?>_fecha" value="<?= HtmlEncode($Grid->fecha->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_cont_lotes_pagos_detalle_fecha" class="form-group">
<span<?= $Grid->fecha->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->fecha->getDisplayValue($Grid->fecha->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_fecha" data-hidden="1" name="x<?= $Grid->RowIndex ?>_fecha" id="x<?= $Grid->RowIndex ?>_fecha" value="<?= HtmlEncode($Grid->fecha->CurrentValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_cont_lotes_pagos_detalle_fecha">
<span<?= $Grid->fecha->viewAttributes() ?>>
<?= $Grid->fecha->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_fecha" data-hidden="1" name="fcont_lotes_pagos_detallegrid$x<?= $Grid->RowIndex ?>_fecha" id="fcont_lotes_pagos_detallegrid$x<?= $Grid->RowIndex ?>_fecha" value="<?= HtmlEncode($Grid->fecha->FormValue) ?>">
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_fecha" data-hidden="1" name="fcont_lotes_pagos_detallegrid$o<?= $Grid->RowIndex ?>_fecha" id="fcont_lotes_pagos_detallegrid$o<?= $Grid->RowIndex ?>_fecha" value="<?= HtmlEncode($Grid->fecha->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->tipodoc->Visible) { // tipodoc ?>
        <td data-name="tipodoc" <?= $Grid->tipodoc->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_cont_lotes_pagos_detalle_tipodoc" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_tipodoc"
        name="x<?= $Grid->RowIndex ?>_tipodoc"
        class="form-control ew-select<?= $Grid->tipodoc->isInvalidClass() ?>"
        data-select2-id="cont_lotes_pagos_detalle_x<?= $Grid->RowIndex ?>_tipodoc"
        data-table="cont_lotes_pagos_detalle"
        data-field="x_tipodoc"
        data-value-separator="<?= $Grid->tipodoc->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->tipodoc->getPlaceHolder()) ?>"
        <?= $Grid->tipodoc->editAttributes() ?>>
        <?= $Grid->tipodoc->selectOptionListHtml("x{$Grid->RowIndex}_tipodoc") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->tipodoc->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='cont_lotes_pagos_detalle_x<?= $Grid->RowIndex ?>_tipodoc']"),
        options = { name: "x<?= $Grid->RowIndex ?>_tipodoc", selectId: "cont_lotes_pagos_detalle_x<?= $Grid->RowIndex ?>_tipodoc", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.cont_lotes_pagos_detalle.fields.tipodoc.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.cont_lotes_pagos_detalle.fields.tipodoc.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_tipodoc" data-hidden="1" name="o<?= $Grid->RowIndex ?>_tipodoc" id="o<?= $Grid->RowIndex ?>_tipodoc" value="<?= HtmlEncode($Grid->tipodoc->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_cont_lotes_pagos_detalle_tipodoc" class="form-group">
<span<?= $Grid->tipodoc->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->tipodoc->getDisplayValue($Grid->tipodoc->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_tipodoc" data-hidden="1" name="x<?= $Grid->RowIndex ?>_tipodoc" id="x<?= $Grid->RowIndex ?>_tipodoc" value="<?= HtmlEncode($Grid->tipodoc->CurrentValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_cont_lotes_pagos_detalle_tipodoc">
<span<?= $Grid->tipodoc->viewAttributes() ?>>
<?= $Grid->tipodoc->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_tipodoc" data-hidden="1" name="fcont_lotes_pagos_detallegrid$x<?= $Grid->RowIndex ?>_tipodoc" id="fcont_lotes_pagos_detallegrid$x<?= $Grid->RowIndex ?>_tipodoc" value="<?= HtmlEncode($Grid->tipodoc->FormValue) ?>">
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_tipodoc" data-hidden="1" name="fcont_lotes_pagos_detallegrid$o<?= $Grid->RowIndex ?>_tipodoc" id="fcont_lotes_pagos_detallegrid$o<?= $Grid->RowIndex ?>_tipodoc" value="<?= HtmlEncode($Grid->tipodoc->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->nro_documento->Visible) { // nro_documento ?>
        <td data-name="nro_documento" <?= $Grid->nro_documento->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_cont_lotes_pagos_detalle_nro_documento" class="form-group">
<input type="<?= $Grid->nro_documento->getInputTextType() ?>" data-table="cont_lotes_pagos_detalle" data-field="x_nro_documento" name="x<?= $Grid->RowIndex ?>_nro_documento" id="x<?= $Grid->RowIndex ?>_nro_documento" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->nro_documento->getPlaceHolder()) ?>" value="<?= $Grid->nro_documento->EditValue ?>"<?= $Grid->nro_documento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->nro_documento->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_nro_documento" data-hidden="1" name="o<?= $Grid->RowIndex ?>_nro_documento" id="o<?= $Grid->RowIndex ?>_nro_documento" value="<?= HtmlEncode($Grid->nro_documento->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_cont_lotes_pagos_detalle_nro_documento" class="form-group">
<span<?= $Grid->nro_documento->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->nro_documento->getDisplayValue($Grid->nro_documento->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_nro_documento" data-hidden="1" name="x<?= $Grid->RowIndex ?>_nro_documento" id="x<?= $Grid->RowIndex ?>_nro_documento" value="<?= HtmlEncode($Grid->nro_documento->CurrentValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_cont_lotes_pagos_detalle_nro_documento">
<span<?= $Grid->nro_documento->viewAttributes() ?>>
<?= $Grid->nro_documento->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_nro_documento" data-hidden="1" name="fcont_lotes_pagos_detallegrid$x<?= $Grid->RowIndex ?>_nro_documento" id="fcont_lotes_pagos_detallegrid$x<?= $Grid->RowIndex ?>_nro_documento" value="<?= HtmlEncode($Grid->nro_documento->FormValue) ?>">
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_nro_documento" data-hidden="1" name="fcont_lotes_pagos_detallegrid$o<?= $Grid->RowIndex ?>_nro_documento" id="fcont_lotes_pagos_detallegrid$o<?= $Grid->RowIndex ?>_nro_documento" value="<?= HtmlEncode($Grid->nro_documento->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->monto_a_pagar->Visible) { // monto_a_pagar ?>
        <td data-name="monto_a_pagar" <?= $Grid->monto_a_pagar->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_cont_lotes_pagos_detalle_monto_a_pagar" class="form-group">
<input type="<?= $Grid->monto_a_pagar->getInputTextType() ?>" data-table="cont_lotes_pagos_detalle" data-field="x_monto_a_pagar" name="x<?= $Grid->RowIndex ?>_monto_a_pagar" id="x<?= $Grid->RowIndex ?>_monto_a_pagar" size="30" maxlength="14" placeholder="<?= HtmlEncode($Grid->monto_a_pagar->getPlaceHolder()) ?>" value="<?= $Grid->monto_a_pagar->EditValue ?>"<?= $Grid->monto_a_pagar->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto_a_pagar->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_monto_a_pagar" data-hidden="1" name="o<?= $Grid->RowIndex ?>_monto_a_pagar" id="o<?= $Grid->RowIndex ?>_monto_a_pagar" value="<?= HtmlEncode($Grid->monto_a_pagar->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_cont_lotes_pagos_detalle_monto_a_pagar" class="form-group">
<span<?= $Grid->monto_a_pagar->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->monto_a_pagar->getDisplayValue($Grid->monto_a_pagar->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_monto_a_pagar" data-hidden="1" name="x<?= $Grid->RowIndex ?>_monto_a_pagar" id="x<?= $Grid->RowIndex ?>_monto_a_pagar" value="<?= HtmlEncode($Grid->monto_a_pagar->CurrentValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_cont_lotes_pagos_detalle_monto_a_pagar">
<span<?= $Grid->monto_a_pagar->viewAttributes() ?>>
<?= $Grid->monto_a_pagar->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_monto_a_pagar" data-hidden="1" name="fcont_lotes_pagos_detallegrid$x<?= $Grid->RowIndex ?>_monto_a_pagar" id="fcont_lotes_pagos_detallegrid$x<?= $Grid->RowIndex ?>_monto_a_pagar" value="<?= HtmlEncode($Grid->monto_a_pagar->FormValue) ?>">
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_monto_a_pagar" data-hidden="1" name="fcont_lotes_pagos_detallegrid$o<?= $Grid->RowIndex ?>_monto_a_pagar" id="fcont_lotes_pagos_detallegrid$o<?= $Grid->RowIndex ?>_monto_a_pagar" value="<?= HtmlEncode($Grid->monto_a_pagar->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->monto_pagado->Visible) { // monto_pagado ?>
        <td data-name="monto_pagado" <?= $Grid->monto_pagado->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_cont_lotes_pagos_detalle_monto_pagado" class="form-group">
<input type="<?= $Grid->monto_pagado->getInputTextType() ?>" data-table="cont_lotes_pagos_detalle" data-field="x_monto_pagado" name="x<?= $Grid->RowIndex ?>_monto_pagado" id="x<?= $Grid->RowIndex ?>_monto_pagado" size="30" maxlength="14" placeholder="<?= HtmlEncode($Grid->monto_pagado->getPlaceHolder()) ?>" value="<?= $Grid->monto_pagado->EditValue ?>"<?= $Grid->monto_pagado->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto_pagado->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_monto_pagado" data-hidden="1" name="o<?= $Grid->RowIndex ?>_monto_pagado" id="o<?= $Grid->RowIndex ?>_monto_pagado" value="<?= HtmlEncode($Grid->monto_pagado->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_cont_lotes_pagos_detalle_monto_pagado" class="form-group">
<span<?= $Grid->monto_pagado->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->monto_pagado->getDisplayValue($Grid->monto_pagado->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_monto_pagado" data-hidden="1" name="x<?= $Grid->RowIndex ?>_monto_pagado" id="x<?= $Grid->RowIndex ?>_monto_pagado" value="<?= HtmlEncode($Grid->monto_pagado->CurrentValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_cont_lotes_pagos_detalle_monto_pagado">
<span<?= $Grid->monto_pagado->viewAttributes() ?>>
<?= $Grid->monto_pagado->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_monto_pagado" data-hidden="1" name="fcont_lotes_pagos_detallegrid$x<?= $Grid->RowIndex ?>_monto_pagado" id="fcont_lotes_pagos_detallegrid$x<?= $Grid->RowIndex ?>_monto_pagado" value="<?= HtmlEncode($Grid->monto_pagado->FormValue) ?>">
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_monto_pagado" data-hidden="1" name="fcont_lotes_pagos_detallegrid$o<?= $Grid->RowIndex ?>_monto_pagado" id="fcont_lotes_pagos_detallegrid$o<?= $Grid->RowIndex ?>_monto_pagado" value="<?= HtmlEncode($Grid->monto_pagado->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->saldo->Visible) { // saldo ?>
        <td data-name="saldo" <?= $Grid->saldo->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_cont_lotes_pagos_detalle_saldo" class="form-group">
<input type="<?= $Grid->saldo->getInputTextType() ?>" data-table="cont_lotes_pagos_detalle" data-field="x_saldo" name="x<?= $Grid->RowIndex ?>_saldo" id="x<?= $Grid->RowIndex ?>_saldo" size="10" maxlength="14" placeholder="<?= HtmlEncode($Grid->saldo->getPlaceHolder()) ?>" value="<?= $Grid->saldo->EditValue ?>"<?= $Grid->saldo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->saldo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_saldo" data-hidden="1" name="o<?= $Grid->RowIndex ?>_saldo" id="o<?= $Grid->RowIndex ?>_saldo" value="<?= HtmlEncode($Grid->saldo->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_cont_lotes_pagos_detalle_saldo" class="form-group">
<input type="<?= $Grid->saldo->getInputTextType() ?>" data-table="cont_lotes_pagos_detalle" data-field="x_saldo" name="x<?= $Grid->RowIndex ?>_saldo" id="x<?= $Grid->RowIndex ?>_saldo" size="10" maxlength="14" placeholder="<?= HtmlEncode($Grid->saldo->getPlaceHolder()) ?>" value="<?= $Grid->saldo->EditValue ?>"<?= $Grid->saldo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->saldo->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_cont_lotes_pagos_detalle_saldo">
<span<?= $Grid->saldo->viewAttributes() ?>>
<?= $Grid->saldo->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_saldo" data-hidden="1" name="fcont_lotes_pagos_detallegrid$x<?= $Grid->RowIndex ?>_saldo" id="fcont_lotes_pagos_detallegrid$x<?= $Grid->RowIndex ?>_saldo" value="<?= HtmlEncode($Grid->saldo->FormValue) ?>">
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_saldo" data-hidden="1" name="fcont_lotes_pagos_detallegrid$o<?= $Grid->RowIndex ?>_saldo" id="fcont_lotes_pagos_detallegrid$o<?= $Grid->RowIndex ?>_saldo" value="<?= HtmlEncode($Grid->saldo->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowCount);
?>
    </tr>
<?php if ($Grid->RowType == ROWTYPE_ADD || $Grid->RowType == ROWTYPE_EDIT) { ?>
<script>
loadjs.ready(["fcont_lotes_pagos_detallegrid","load"], function () {
    fcont_lotes_pagos_detallegrid.updateLists(<?= $Grid->RowIndex ?>);
});
</script>
<?php } ?>
<?php
    }
    } // End delete row checking
    if (!$Grid->isGridAdd() || $Grid->CurrentMode == "copy")
        if (!$Grid->Recordset->EOF) {
            $Grid->Recordset->moveNext();
        }
}
?>
<?php
    if ($Grid->CurrentMode == "add" || $Grid->CurrentMode == "copy" || $Grid->CurrentMode == "edit") {
        $Grid->RowIndex = '$rowindex$';
        $Grid->loadRowValues();

        // Set row properties
        $Grid->resetAttributes();
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_cont_lotes_pagos_detalle", "data-rowtype" => ROWTYPE_ADD]);
        $Grid->RowAttrs->appendClass("ew-template");
        $Grid->RowType = ROWTYPE_ADD;

        // Render row
        $Grid->renderRow();

        // Render list options
        $Grid->renderListOptions();
        $Grid->StartRowCount = 0;
?>
    <tr <?= $Grid->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Grid->ListOptions->render("body", "left", $Grid->RowIndex);
?>
    <?php if ($Grid->proveedor->Visible) { // proveedor ?>
        <td data-name="proveedor">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_cont_lotes_pagos_detalle_proveedor" class="form-group cont_lotes_pagos_detalle_proveedor">
<?php
$onchange = $Grid->proveedor->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Grid->proveedor->EditAttrs["onchange"] = "";
?>
<span id="as_x<?= $Grid->RowIndex ?>_proveedor" class="ew-auto-suggest">
    <div class="input-group flex-nowrap">
        <input type="<?= $Grid->proveedor->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_proveedor" id="sv_x<?= $Grid->RowIndex ?>_proveedor" value="<?= RemoveHtml($Grid->proveedor->EditValue) ?>" size="30" maxlength="10" placeholder="<?= HtmlEncode($Grid->proveedor->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->proveedor->getPlaceHolder()) ?>"<?= $Grid->proveedor->editAttributes() ?>>
        <div class="input-group-append">
            <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Grid->proveedor->caption()), $Language->phrase("LookupLink", true))) ?>" onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_proveedor',m:0,n:10,srch:true});" class="ew-lookup-btn btn btn-default"<?= ($Grid->proveedor->ReadOnly || $Grid->proveedor->Disabled) ? " disabled" : "" ?>><i class="fas fa-search ew-icon"></i></button>
        </div>
    </div>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="cont_lotes_pagos_detalle" data-field="x_proveedor" data-input="sv_x<?= $Grid->RowIndex ?>_proveedor" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Grid->proveedor->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_proveedor" id="x<?= $Grid->RowIndex ?>_proveedor" value="<?= HtmlEncode($Grid->proveedor->CurrentValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Grid->proveedor->getErrorMessage() ?></div>
<script>
loadjs.ready(["fcont_lotes_pagos_detallegrid"], function() {
    fcont_lotes_pagos_detallegrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_proveedor","forceSelect":false}, ew.vars.tables.cont_lotes_pagos_detalle.fields.proveedor.autoSuggestOptions));
});
</script>
<?= $Grid->proveedor->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_proveedor") ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_cont_lotes_pagos_detalle_proveedor" class="form-group cont_lotes_pagos_detalle_proveedor">
<span<?= $Grid->proveedor->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->proveedor->getDisplayValue($Grid->proveedor->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_proveedor" data-hidden="1" name="x<?= $Grid->RowIndex ?>_proveedor" id="x<?= $Grid->RowIndex ?>_proveedor" value="<?= HtmlEncode($Grid->proveedor->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_proveedor" data-hidden="1" name="o<?= $Grid->RowIndex ?>_proveedor" id="o<?= $Grid->RowIndex ?>_proveedor" value="<?= HtmlEncode($Grid->proveedor->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->fecha->Visible) { // fecha ?>
        <td data-name="fecha">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_cont_lotes_pagos_detalle_fecha" class="form-group cont_lotes_pagos_detalle_fecha">
<input type="<?= $Grid->fecha->getInputTextType() ?>" data-table="cont_lotes_pagos_detalle" data-field="x_fecha" data-format="7" name="x<?= $Grid->RowIndex ?>_fecha" id="x<?= $Grid->RowIndex ?>_fecha" maxlength="10" placeholder="<?= HtmlEncode($Grid->fecha->getPlaceHolder()) ?>" value="<?= $Grid->fecha->EditValue ?>"<?= $Grid->fecha->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->fecha->getErrorMessage() ?></div>
<?php if (!$Grid->fecha->ReadOnly && !$Grid->fecha->Disabled && !isset($Grid->fecha->EditAttrs["readonly"]) && !isset($Grid->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fcont_lotes_pagos_detallegrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fcont_lotes_pagos_detallegrid", "x<?= $Grid->RowIndex ?>_fecha", {"ignoreReadonly":true,"useCurrent":false,"format":7});
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_cont_lotes_pagos_detalle_fecha" class="form-group cont_lotes_pagos_detalle_fecha">
<span<?= $Grid->fecha->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->fecha->getDisplayValue($Grid->fecha->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_fecha" data-hidden="1" name="x<?= $Grid->RowIndex ?>_fecha" id="x<?= $Grid->RowIndex ?>_fecha" value="<?= HtmlEncode($Grid->fecha->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_fecha" data-hidden="1" name="o<?= $Grid->RowIndex ?>_fecha" id="o<?= $Grid->RowIndex ?>_fecha" value="<?= HtmlEncode($Grid->fecha->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->tipodoc->Visible) { // tipodoc ?>
        <td data-name="tipodoc">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_cont_lotes_pagos_detalle_tipodoc" class="form-group cont_lotes_pagos_detalle_tipodoc">
    <select
        id="x<?= $Grid->RowIndex ?>_tipodoc"
        name="x<?= $Grid->RowIndex ?>_tipodoc"
        class="form-control ew-select<?= $Grid->tipodoc->isInvalidClass() ?>"
        data-select2-id="cont_lotes_pagos_detalle_x<?= $Grid->RowIndex ?>_tipodoc"
        data-table="cont_lotes_pagos_detalle"
        data-field="x_tipodoc"
        data-value-separator="<?= $Grid->tipodoc->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->tipodoc->getPlaceHolder()) ?>"
        <?= $Grid->tipodoc->editAttributes() ?>>
        <?= $Grid->tipodoc->selectOptionListHtml("x{$Grid->RowIndex}_tipodoc") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->tipodoc->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='cont_lotes_pagos_detalle_x<?= $Grid->RowIndex ?>_tipodoc']"),
        options = { name: "x<?= $Grid->RowIndex ?>_tipodoc", selectId: "cont_lotes_pagos_detalle_x<?= $Grid->RowIndex ?>_tipodoc", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.cont_lotes_pagos_detalle.fields.tipodoc.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.cont_lotes_pagos_detalle.fields.tipodoc.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_cont_lotes_pagos_detalle_tipodoc" class="form-group cont_lotes_pagos_detalle_tipodoc">
<span<?= $Grid->tipodoc->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->tipodoc->getDisplayValue($Grid->tipodoc->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_tipodoc" data-hidden="1" name="x<?= $Grid->RowIndex ?>_tipodoc" id="x<?= $Grid->RowIndex ?>_tipodoc" value="<?= HtmlEncode($Grid->tipodoc->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_tipodoc" data-hidden="1" name="o<?= $Grid->RowIndex ?>_tipodoc" id="o<?= $Grid->RowIndex ?>_tipodoc" value="<?= HtmlEncode($Grid->tipodoc->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->nro_documento->Visible) { // nro_documento ?>
        <td data-name="nro_documento">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_cont_lotes_pagos_detalle_nro_documento" class="form-group cont_lotes_pagos_detalle_nro_documento">
<input type="<?= $Grid->nro_documento->getInputTextType() ?>" data-table="cont_lotes_pagos_detalle" data-field="x_nro_documento" name="x<?= $Grid->RowIndex ?>_nro_documento" id="x<?= $Grid->RowIndex ?>_nro_documento" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->nro_documento->getPlaceHolder()) ?>" value="<?= $Grid->nro_documento->EditValue ?>"<?= $Grid->nro_documento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->nro_documento->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_cont_lotes_pagos_detalle_nro_documento" class="form-group cont_lotes_pagos_detalle_nro_documento">
<span<?= $Grid->nro_documento->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->nro_documento->getDisplayValue($Grid->nro_documento->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_nro_documento" data-hidden="1" name="x<?= $Grid->RowIndex ?>_nro_documento" id="x<?= $Grid->RowIndex ?>_nro_documento" value="<?= HtmlEncode($Grid->nro_documento->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_nro_documento" data-hidden="1" name="o<?= $Grid->RowIndex ?>_nro_documento" id="o<?= $Grid->RowIndex ?>_nro_documento" value="<?= HtmlEncode($Grid->nro_documento->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->monto_a_pagar->Visible) { // monto_a_pagar ?>
        <td data-name="monto_a_pagar">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_cont_lotes_pagos_detalle_monto_a_pagar" class="form-group cont_lotes_pagos_detalle_monto_a_pagar">
<input type="<?= $Grid->monto_a_pagar->getInputTextType() ?>" data-table="cont_lotes_pagos_detalle" data-field="x_monto_a_pagar" name="x<?= $Grid->RowIndex ?>_monto_a_pagar" id="x<?= $Grid->RowIndex ?>_monto_a_pagar" size="30" maxlength="14" placeholder="<?= HtmlEncode($Grid->monto_a_pagar->getPlaceHolder()) ?>" value="<?= $Grid->monto_a_pagar->EditValue ?>"<?= $Grid->monto_a_pagar->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto_a_pagar->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_cont_lotes_pagos_detalle_monto_a_pagar" class="form-group cont_lotes_pagos_detalle_monto_a_pagar">
<span<?= $Grid->monto_a_pagar->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->monto_a_pagar->getDisplayValue($Grid->monto_a_pagar->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_monto_a_pagar" data-hidden="1" name="x<?= $Grid->RowIndex ?>_monto_a_pagar" id="x<?= $Grid->RowIndex ?>_monto_a_pagar" value="<?= HtmlEncode($Grid->monto_a_pagar->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_monto_a_pagar" data-hidden="1" name="o<?= $Grid->RowIndex ?>_monto_a_pagar" id="o<?= $Grid->RowIndex ?>_monto_a_pagar" value="<?= HtmlEncode($Grid->monto_a_pagar->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->monto_pagado->Visible) { // monto_pagado ?>
        <td data-name="monto_pagado">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_cont_lotes_pagos_detalle_monto_pagado" class="form-group cont_lotes_pagos_detalle_monto_pagado">
<input type="<?= $Grid->monto_pagado->getInputTextType() ?>" data-table="cont_lotes_pagos_detalle" data-field="x_monto_pagado" name="x<?= $Grid->RowIndex ?>_monto_pagado" id="x<?= $Grid->RowIndex ?>_monto_pagado" size="30" maxlength="14" placeholder="<?= HtmlEncode($Grid->monto_pagado->getPlaceHolder()) ?>" value="<?= $Grid->monto_pagado->EditValue ?>"<?= $Grid->monto_pagado->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto_pagado->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_cont_lotes_pagos_detalle_monto_pagado" class="form-group cont_lotes_pagos_detalle_monto_pagado">
<span<?= $Grid->monto_pagado->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->monto_pagado->getDisplayValue($Grid->monto_pagado->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_monto_pagado" data-hidden="1" name="x<?= $Grid->RowIndex ?>_monto_pagado" id="x<?= $Grid->RowIndex ?>_monto_pagado" value="<?= HtmlEncode($Grid->monto_pagado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_monto_pagado" data-hidden="1" name="o<?= $Grid->RowIndex ?>_monto_pagado" id="o<?= $Grid->RowIndex ?>_monto_pagado" value="<?= HtmlEncode($Grid->monto_pagado->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->saldo->Visible) { // saldo ?>
        <td data-name="saldo">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_cont_lotes_pagos_detalle_saldo" class="form-group cont_lotes_pagos_detalle_saldo">
<input type="<?= $Grid->saldo->getInputTextType() ?>" data-table="cont_lotes_pagos_detalle" data-field="x_saldo" name="x<?= $Grid->RowIndex ?>_saldo" id="x<?= $Grid->RowIndex ?>_saldo" size="10" maxlength="14" placeholder="<?= HtmlEncode($Grid->saldo->getPlaceHolder()) ?>" value="<?= $Grid->saldo->EditValue ?>"<?= $Grid->saldo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->saldo->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_cont_lotes_pagos_detalle_saldo" class="form-group cont_lotes_pagos_detalle_saldo">
<span<?= $Grid->saldo->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->saldo->getDisplayValue($Grid->saldo->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_saldo" data-hidden="1" name="x<?= $Grid->RowIndex ?>_saldo" id="x<?= $Grid->RowIndex ?>_saldo" value="<?= HtmlEncode($Grid->saldo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_saldo" data-hidden="1" name="o<?= $Grid->RowIndex ?>_saldo" id="o<?= $Grid->RowIndex ?>_saldo" value="<?= HtmlEncode($Grid->saldo->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["fcont_lotes_pagos_detallegrid","load"], function() {
    fcont_lotes_pagos_detallegrid.updateLists(<?= $Grid->RowIndex ?>);
});
</script>
    </tr>
<?php
    }
?>
</tbody>
</table><!-- /.ew-table -->
</div><!-- /.ew-grid-middle-panel -->
<?php if ($Grid->CurrentMode == "add" || $Grid->CurrentMode == "copy") { ?>
<input type="hidden" name="<?= $Grid->FormKeyCountName ?>" id="<?= $Grid->FormKeyCountName ?>" value="<?= $Grid->KeyCount ?>">
<?= $Grid->MultiSelectKey ?>
<?php } ?>
<?php if ($Grid->CurrentMode == "edit") { ?>
<input type="hidden" name="<?= $Grid->FormKeyCountName ?>" id="<?= $Grid->FormKeyCountName ?>" value="<?= $Grid->KeyCount ?>">
<?= $Grid->MultiSelectKey ?>
<?php } ?>
<?php if ($Grid->CurrentMode == "") { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fcont_lotes_pagos_detallegrid">
</div><!-- /.ew-list-form -->
<?php
// Close recordset
if ($Grid->Recordset) {
    $Grid->Recordset->close();
}
?>
</div><!-- /.ew-grid -->
<?php } ?>
<?php if ($Grid->TotalRecords == 0 && !$Grid->CurrentAction) { // Show other options ?>
<div class="ew-list-other-options">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if (!$Grid->isExport()) { ?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("cont_lotes_pagos_detalle");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
