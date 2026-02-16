<?php

namespace PHPMaker2021\mandrake;

// Set up and run Grid object
$Grid = Container("CobrosClienteFacturaGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fcobros_cliente_facturagrid;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    fcobros_cliente_facturagrid = new ew.Form("fcobros_cliente_facturagrid", "grid");
    fcobros_cliente_facturagrid.formKeyCountName = '<?= $Grid->FormKeyCountName ?>';

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "cobros_cliente_factura")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.cobros_cliente_factura)
        ew.vars.tables.cobros_cliente_factura = currentTable;
    fcobros_cliente_facturagrid.addFields([
        ["tipo_documento", [fields.tipo_documento.visible && fields.tipo_documento.required ? ew.Validators.required(fields.tipo_documento.caption) : null], fields.tipo_documento.isInvalid],
        ["abono", [fields.abono.visible && fields.abono.required ? ew.Validators.required(fields.abono.caption) : null], fields.abono.isInvalid],
        ["monto", [fields.monto.visible && fields.monto.required ? ew.Validators.required(fields.monto.caption) : null], fields.monto.isInvalid],
        ["retivamonto", [fields.retivamonto.visible && fields.retivamonto.required ? ew.Validators.required(fields.retivamonto.caption) : null], fields.retivamonto.isInvalid],
        ["retiva", [fields.retiva.visible && fields.retiva.required ? ew.Validators.required(fields.retiva.caption) : null], fields.retiva.isInvalid],
        ["retislrmonto", [fields.retislrmonto.visible && fields.retislrmonto.required ? ew.Validators.required(fields.retislrmonto.caption) : null], fields.retislrmonto.isInvalid],
        ["retislr", [fields.retislr.visible && fields.retislr.required ? ew.Validators.required(fields.retislr.caption) : null], fields.retislr.isInvalid],
        ["comprobante", [fields.comprobante.visible && fields.comprobante.required ? ew.Validators.required(fields.comprobante.caption) : null], fields.comprobante.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fcobros_cliente_facturagrid,
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
    fcobros_cliente_facturagrid.validate = function () {
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
    fcobros_cliente_facturagrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "tipo_documento", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "abono", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "monto", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "retivamonto", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "retiva", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "retislrmonto", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "retislr", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "comprobante", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    fcobros_cliente_facturagrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fcobros_cliente_facturagrid.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fcobros_cliente_facturagrid.lists.tipo_documento = <?= $Grid->tipo_documento->toClientList($Grid) ?>;
    fcobros_cliente_facturagrid.lists.abono = <?= $Grid->abono->toClientList($Grid) ?>;
    fcobros_cliente_facturagrid.lists.comprobante = <?= $Grid->comprobante->toClientList($Grid) ?>;
    loadjs.done("fcobros_cliente_facturagrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> cobros_cliente_factura">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="fcobros_cliente_facturagrid" class="ew-form ew-list-form form-inline">
<div id="gmp_cobros_cliente_factura" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_cobros_cliente_facturagrid" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Grid->tipo_documento->Visible) { // tipo_documento ?>
        <th data-name="tipo_documento" class="<?= $Grid->tipo_documento->headerCellClass() ?>"><div id="elh_cobros_cliente_factura_tipo_documento" class="cobros_cliente_factura_tipo_documento"><?= $Grid->renderSort($Grid->tipo_documento) ?></div></th>
<?php } ?>
<?php if ($Grid->abono->Visible) { // abono ?>
        <th data-name="abono" class="<?= $Grid->abono->headerCellClass() ?>"><div id="elh_cobros_cliente_factura_abono" class="cobros_cliente_factura_abono"><?= $Grid->renderSort($Grid->abono) ?></div></th>
<?php } ?>
<?php if ($Grid->monto->Visible) { // monto ?>
        <th data-name="monto" class="<?= $Grid->monto->headerCellClass() ?>"><div id="elh_cobros_cliente_factura_monto" class="cobros_cliente_factura_monto"><?= $Grid->renderSort($Grid->monto) ?></div></th>
<?php } ?>
<?php if ($Grid->retivamonto->Visible) { // retivamonto ?>
        <th data-name="retivamonto" class="<?= $Grid->retivamonto->headerCellClass() ?>"><div id="elh_cobros_cliente_factura_retivamonto" class="cobros_cliente_factura_retivamonto"><?= $Grid->renderSort($Grid->retivamonto) ?></div></th>
<?php } ?>
<?php if ($Grid->retiva->Visible) { // retiva ?>
        <th data-name="retiva" class="<?= $Grid->retiva->headerCellClass() ?>"><div id="elh_cobros_cliente_factura_retiva" class="cobros_cliente_factura_retiva"><?= $Grid->renderSort($Grid->retiva) ?></div></th>
<?php } ?>
<?php if ($Grid->retislrmonto->Visible) { // retislrmonto ?>
        <th data-name="retislrmonto" class="<?= $Grid->retislrmonto->headerCellClass() ?>"><div id="elh_cobros_cliente_factura_retislrmonto" class="cobros_cliente_factura_retislrmonto"><?= $Grid->renderSort($Grid->retislrmonto) ?></div></th>
<?php } ?>
<?php if ($Grid->retislr->Visible) { // retislr ?>
        <th data-name="retislr" class="<?= $Grid->retislr->headerCellClass() ?>"><div id="elh_cobros_cliente_factura_retislr" class="cobros_cliente_factura_retislr"><?= $Grid->renderSort($Grid->retislr) ?></div></th>
<?php } ?>
<?php if ($Grid->comprobante->Visible) { // comprobante ?>
        <th data-name="comprobante" class="<?= $Grid->comprobante->headerCellClass() ?>"><div id="elh_cobros_cliente_factura_comprobante" class="cobros_cliente_factura_comprobante"><?= $Grid->renderSort($Grid->comprobante) ?></div></th>
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowCount, "id" => "r" . $Grid->RowCount . "_cobros_cliente_factura", "data-rowtype" => $Grid->RowType]);

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
    <?php if ($Grid->tipo_documento->Visible) { // tipo_documento ?>
        <td data-name="tipo_documento" <?= $Grid->tipo_documento->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_cobros_cliente_factura_tipo_documento" class="form-group">
<?php
$onchange = $Grid->tipo_documento->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Grid->tipo_documento->EditAttrs["onchange"] = "";
?>
<span id="as_x<?= $Grid->RowIndex ?>_tipo_documento" class="ew-auto-suggest">
    <input type="<?= $Grid->tipo_documento->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_tipo_documento" id="sv_x<?= $Grid->RowIndex ?>_tipo_documento" value="<?= RemoveHtml($Grid->tipo_documento->EditValue) ?>" size="30" maxlength="6" placeholder="<?= HtmlEncode($Grid->tipo_documento->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->tipo_documento->getPlaceHolder()) ?>"<?= $Grid->tipo_documento->editAttributes() ?>>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="cobros_cliente_factura" data-field="x_tipo_documento" data-input="sv_x<?= $Grid->RowIndex ?>_tipo_documento" data-value-separator="<?= $Grid->tipo_documento->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_tipo_documento" id="x<?= $Grid->RowIndex ?>_tipo_documento" value="<?= HtmlEncode($Grid->tipo_documento->CurrentValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Grid->tipo_documento->getErrorMessage() ?></div>
<script>
loadjs.ready(["fcobros_cliente_facturagrid"], function() {
    fcobros_cliente_facturagrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_tipo_documento","forceSelect":false}, ew.vars.tables.cobros_cliente_factura.fields.tipo_documento.autoSuggestOptions));
});
</script>
<?= $Grid->tipo_documento->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_tipo_documento") ?>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_tipo_documento" data-hidden="1" name="o<?= $Grid->RowIndex ?>_tipo_documento" id="o<?= $Grid->RowIndex ?>_tipo_documento" value="<?= HtmlEncode($Grid->tipo_documento->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_cobros_cliente_factura_tipo_documento" class="form-group">
<span<?= $Grid->tipo_documento->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->tipo_documento->getDisplayValue($Grid->tipo_documento->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_tipo_documento" data-hidden="1" name="x<?= $Grid->RowIndex ?>_tipo_documento" id="x<?= $Grid->RowIndex ?>_tipo_documento" value="<?= HtmlEncode($Grid->tipo_documento->CurrentValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_cobros_cliente_factura_tipo_documento">
<span<?= $Grid->tipo_documento->viewAttributes() ?>>
<?= $Grid->tipo_documento->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_tipo_documento" data-hidden="1" name="fcobros_cliente_facturagrid$x<?= $Grid->RowIndex ?>_tipo_documento" id="fcobros_cliente_facturagrid$x<?= $Grid->RowIndex ?>_tipo_documento" value="<?= HtmlEncode($Grid->tipo_documento->FormValue) ?>">
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_tipo_documento" data-hidden="1" name="fcobros_cliente_facturagrid$o<?= $Grid->RowIndex ?>_tipo_documento" id="fcobros_cliente_facturagrid$o<?= $Grid->RowIndex ?>_tipo_documento" value="<?= HtmlEncode($Grid->tipo_documento->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->abono->Visible) { // abono ?>
        <td data-name="abono" <?= $Grid->abono->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_cobros_cliente_factura_abono" class="form-group">
<template id="tp_x<?= $Grid->RowIndex ?>_abono">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="cobros_cliente_factura" data-field="x_abono" name="x<?= $Grid->RowIndex ?>_abono" id="x<?= $Grid->RowIndex ?>_abono"<?= $Grid->abono->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x<?= $Grid->RowIndex ?>_abono" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x<?= $Grid->RowIndex ?>_abono"
    name="x<?= $Grid->RowIndex ?>_abono"
    value="<?= HtmlEncode($Grid->abono->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x<?= $Grid->RowIndex ?>_abono"
    data-target="dsl_x<?= $Grid->RowIndex ?>_abono"
    data-repeatcolumn="5"
    class="form-control<?= $Grid->abono->isInvalidClass() ?>"
    data-table="cobros_cliente_factura"
    data-field="x_abono"
    data-value-separator="<?= $Grid->abono->displayValueSeparatorAttribute() ?>"
    <?= $Grid->abono->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->abono->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_abono" data-hidden="1" name="o<?= $Grid->RowIndex ?>_abono" id="o<?= $Grid->RowIndex ?>_abono" value="<?= HtmlEncode($Grid->abono->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_cobros_cliente_factura_abono" class="form-group">
<span<?= $Grid->abono->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->abono->getDisplayValue($Grid->abono->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_abono" data-hidden="1" name="x<?= $Grid->RowIndex ?>_abono" id="x<?= $Grid->RowIndex ?>_abono" value="<?= HtmlEncode($Grid->abono->CurrentValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_cobros_cliente_factura_abono">
<span<?= $Grid->abono->viewAttributes() ?>>
<?= $Grid->abono->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_abono" data-hidden="1" name="fcobros_cliente_facturagrid$x<?= $Grid->RowIndex ?>_abono" id="fcobros_cliente_facturagrid$x<?= $Grid->RowIndex ?>_abono" value="<?= HtmlEncode($Grid->abono->FormValue) ?>">
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_abono" data-hidden="1" name="fcobros_cliente_facturagrid$o<?= $Grid->RowIndex ?>_abono" id="fcobros_cliente_facturagrid$o<?= $Grid->RowIndex ?>_abono" value="<?= HtmlEncode($Grid->abono->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->monto->Visible) { // monto ?>
        <td data-name="monto" <?= $Grid->monto->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_cobros_cliente_factura_monto" class="form-group">
<input type="<?= $Grid->monto->getInputTextType() ?>" data-table="cobros_cliente_factura" data-field="x_monto" name="x<?= $Grid->RowIndex ?>_monto" id="x<?= $Grid->RowIndex ?>_monto" size="30" placeholder="<?= HtmlEncode($Grid->monto->getPlaceHolder()) ?>" value="<?= $Grid->monto->EditValue ?>"<?= $Grid->monto->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_monto" data-hidden="1" name="o<?= $Grid->RowIndex ?>_monto" id="o<?= $Grid->RowIndex ?>_monto" value="<?= HtmlEncode($Grid->monto->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_cobros_cliente_factura_monto" class="form-group">
<span<?= $Grid->monto->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->monto->getDisplayValue($Grid->monto->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_monto" data-hidden="1" name="x<?= $Grid->RowIndex ?>_monto" id="x<?= $Grid->RowIndex ?>_monto" value="<?= HtmlEncode($Grid->monto->CurrentValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_cobros_cliente_factura_monto">
<span<?= $Grid->monto->viewAttributes() ?>>
<?= $Grid->monto->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_monto" data-hidden="1" name="fcobros_cliente_facturagrid$x<?= $Grid->RowIndex ?>_monto" id="fcobros_cliente_facturagrid$x<?= $Grid->RowIndex ?>_monto" value="<?= HtmlEncode($Grid->monto->FormValue) ?>">
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_monto" data-hidden="1" name="fcobros_cliente_facturagrid$o<?= $Grid->RowIndex ?>_monto" id="fcobros_cliente_facturagrid$o<?= $Grid->RowIndex ?>_monto" value="<?= HtmlEncode($Grid->monto->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->retivamonto->Visible) { // retivamonto ?>
        <td data-name="retivamonto" <?= $Grid->retivamonto->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_cobros_cliente_factura_retivamonto" class="form-group">
<input type="<?= $Grid->retivamonto->getInputTextType() ?>" data-table="cobros_cliente_factura" data-field="x_retivamonto" name="x<?= $Grid->RowIndex ?>_retivamonto" id="x<?= $Grid->RowIndex ?>_retivamonto" size="30" placeholder="<?= HtmlEncode($Grid->retivamonto->getPlaceHolder()) ?>" value="<?= $Grid->retivamonto->EditValue ?>"<?= $Grid->retivamonto->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->retivamonto->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retivamonto" data-hidden="1" name="o<?= $Grid->RowIndex ?>_retivamonto" id="o<?= $Grid->RowIndex ?>_retivamonto" value="<?= HtmlEncode($Grid->retivamonto->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_cobros_cliente_factura_retivamonto" class="form-group">
<span<?= $Grid->retivamonto->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->retivamonto->getDisplayValue($Grid->retivamonto->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retivamonto" data-hidden="1" name="x<?= $Grid->RowIndex ?>_retivamonto" id="x<?= $Grid->RowIndex ?>_retivamonto" value="<?= HtmlEncode($Grid->retivamonto->CurrentValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_cobros_cliente_factura_retivamonto">
<span<?= $Grid->retivamonto->viewAttributes() ?>>
<?= $Grid->retivamonto->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retivamonto" data-hidden="1" name="fcobros_cliente_facturagrid$x<?= $Grid->RowIndex ?>_retivamonto" id="fcobros_cliente_facturagrid$x<?= $Grid->RowIndex ?>_retivamonto" value="<?= HtmlEncode($Grid->retivamonto->FormValue) ?>">
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retivamonto" data-hidden="1" name="fcobros_cliente_facturagrid$o<?= $Grid->RowIndex ?>_retivamonto" id="fcobros_cliente_facturagrid$o<?= $Grid->RowIndex ?>_retivamonto" value="<?= HtmlEncode($Grid->retivamonto->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->retiva->Visible) { // retiva ?>
        <td data-name="retiva" <?= $Grid->retiva->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_cobros_cliente_factura_retiva" class="form-group">
<input type="<?= $Grid->retiva->getInputTextType() ?>" data-table="cobros_cliente_factura" data-field="x_retiva" name="x<?= $Grid->RowIndex ?>_retiva" id="x<?= $Grid->RowIndex ?>_retiva" size="30" maxlength="15" placeholder="<?= HtmlEncode($Grid->retiva->getPlaceHolder()) ?>" value="<?= $Grid->retiva->EditValue ?>"<?= $Grid->retiva->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->retiva->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retiva" data-hidden="1" name="o<?= $Grid->RowIndex ?>_retiva" id="o<?= $Grid->RowIndex ?>_retiva" value="<?= HtmlEncode($Grid->retiva->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_cobros_cliente_factura_retiva" class="form-group">
<input type="<?= $Grid->retiva->getInputTextType() ?>" data-table="cobros_cliente_factura" data-field="x_retiva" name="x<?= $Grid->RowIndex ?>_retiva" id="x<?= $Grid->RowIndex ?>_retiva" size="30" maxlength="15" placeholder="<?= HtmlEncode($Grid->retiva->getPlaceHolder()) ?>" value="<?= $Grid->retiva->EditValue ?>"<?= $Grid->retiva->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->retiva->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_cobros_cliente_factura_retiva">
<span<?= $Grid->retiva->viewAttributes() ?>>
<?= $Grid->retiva->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retiva" data-hidden="1" name="fcobros_cliente_facturagrid$x<?= $Grid->RowIndex ?>_retiva" id="fcobros_cliente_facturagrid$x<?= $Grid->RowIndex ?>_retiva" value="<?= HtmlEncode($Grid->retiva->FormValue) ?>">
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retiva" data-hidden="1" name="fcobros_cliente_facturagrid$o<?= $Grid->RowIndex ?>_retiva" id="fcobros_cliente_facturagrid$o<?= $Grid->RowIndex ?>_retiva" value="<?= HtmlEncode($Grid->retiva->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->retislrmonto->Visible) { // retislrmonto ?>
        <td data-name="retislrmonto" <?= $Grid->retislrmonto->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_cobros_cliente_factura_retislrmonto" class="form-group">
<input type="<?= $Grid->retislrmonto->getInputTextType() ?>" data-table="cobros_cliente_factura" data-field="x_retislrmonto" name="x<?= $Grid->RowIndex ?>_retislrmonto" id="x<?= $Grid->RowIndex ?>_retislrmonto" size="30" placeholder="<?= HtmlEncode($Grid->retislrmonto->getPlaceHolder()) ?>" value="<?= $Grid->retislrmonto->EditValue ?>"<?= $Grid->retislrmonto->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->retislrmonto->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retislrmonto" data-hidden="1" name="o<?= $Grid->RowIndex ?>_retislrmonto" id="o<?= $Grid->RowIndex ?>_retislrmonto" value="<?= HtmlEncode($Grid->retislrmonto->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_cobros_cliente_factura_retislrmonto" class="form-group">
<span<?= $Grid->retislrmonto->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->retislrmonto->getDisplayValue($Grid->retislrmonto->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retislrmonto" data-hidden="1" name="x<?= $Grid->RowIndex ?>_retislrmonto" id="x<?= $Grid->RowIndex ?>_retislrmonto" value="<?= HtmlEncode($Grid->retislrmonto->CurrentValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_cobros_cliente_factura_retislrmonto">
<span<?= $Grid->retislrmonto->viewAttributes() ?>>
<?= $Grid->retislrmonto->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retislrmonto" data-hidden="1" name="fcobros_cliente_facturagrid$x<?= $Grid->RowIndex ?>_retislrmonto" id="fcobros_cliente_facturagrid$x<?= $Grid->RowIndex ?>_retislrmonto" value="<?= HtmlEncode($Grid->retislrmonto->FormValue) ?>">
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retislrmonto" data-hidden="1" name="fcobros_cliente_facturagrid$o<?= $Grid->RowIndex ?>_retislrmonto" id="fcobros_cliente_facturagrid$o<?= $Grid->RowIndex ?>_retislrmonto" value="<?= HtmlEncode($Grid->retislrmonto->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->retislr->Visible) { // retislr ?>
        <td data-name="retislr" <?= $Grid->retislr->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_cobros_cliente_factura_retislr" class="form-group">
<input type="<?= $Grid->retislr->getInputTextType() ?>" data-table="cobros_cliente_factura" data-field="x_retislr" name="x<?= $Grid->RowIndex ?>_retislr" id="x<?= $Grid->RowIndex ?>_retislr" size="30" maxlength="15" placeholder="<?= HtmlEncode($Grid->retislr->getPlaceHolder()) ?>" value="<?= $Grid->retislr->EditValue ?>"<?= $Grid->retislr->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->retislr->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retislr" data-hidden="1" name="o<?= $Grid->RowIndex ?>_retislr" id="o<?= $Grid->RowIndex ?>_retislr" value="<?= HtmlEncode($Grid->retislr->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_cobros_cliente_factura_retislr" class="form-group">
<input type="<?= $Grid->retislr->getInputTextType() ?>" data-table="cobros_cliente_factura" data-field="x_retislr" name="x<?= $Grid->RowIndex ?>_retislr" id="x<?= $Grid->RowIndex ?>_retislr" size="30" maxlength="15" placeholder="<?= HtmlEncode($Grid->retislr->getPlaceHolder()) ?>" value="<?= $Grid->retislr->EditValue ?>"<?= $Grid->retislr->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->retislr->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_cobros_cliente_factura_retislr">
<span<?= $Grid->retislr->viewAttributes() ?>>
<?= $Grid->retislr->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retislr" data-hidden="1" name="fcobros_cliente_facturagrid$x<?= $Grid->RowIndex ?>_retislr" id="fcobros_cliente_facturagrid$x<?= $Grid->RowIndex ?>_retislr" value="<?= HtmlEncode($Grid->retislr->FormValue) ?>">
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retislr" data-hidden="1" name="fcobros_cliente_facturagrid$o<?= $Grid->RowIndex ?>_retislr" id="fcobros_cliente_facturagrid$o<?= $Grid->RowIndex ?>_retislr" value="<?= HtmlEncode($Grid->retislr->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->comprobante->Visible) { // comprobante ?>
        <td data-name="comprobante" <?= $Grid->comprobante->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_cobros_cliente_factura_comprobante" class="form-group">
<?php
$onchange = $Grid->comprobante->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Grid->comprobante->EditAttrs["onchange"] = "";
?>
<span id="as_x<?= $Grid->RowIndex ?>_comprobante" class="ew-auto-suggest">
    <input type="<?= $Grid->comprobante->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_comprobante" id="sv_x<?= $Grid->RowIndex ?>_comprobante" value="<?= RemoveHtml($Grid->comprobante->EditValue) ?>" size="30" placeholder="<?= HtmlEncode($Grid->comprobante->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->comprobante->getPlaceHolder()) ?>"<?= $Grid->comprobante->editAttributes() ?>>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="cobros_cliente_factura" data-field="x_comprobante" data-input="sv_x<?= $Grid->RowIndex ?>_comprobante" data-value-separator="<?= $Grid->comprobante->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_comprobante" id="x<?= $Grid->RowIndex ?>_comprobante" value="<?= HtmlEncode($Grid->comprobante->CurrentValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Grid->comprobante->getErrorMessage() ?></div>
<script>
loadjs.ready(["fcobros_cliente_facturagrid"], function() {
    fcobros_cliente_facturagrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_comprobante","forceSelect":false}, ew.vars.tables.cobros_cliente_factura.fields.comprobante.autoSuggestOptions));
});
</script>
<?= $Grid->comprobante->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_comprobante") ?>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_comprobante" data-hidden="1" name="o<?= $Grid->RowIndex ?>_comprobante" id="o<?= $Grid->RowIndex ?>_comprobante" value="<?= HtmlEncode($Grid->comprobante->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_cobros_cliente_factura_comprobante" class="form-group">
<span<?= $Grid->comprobante->viewAttributes() ?>>
<?php if (!EmptyString($Grid->comprobante->EditValue) && $Grid->comprobante->linkAttributes() != "") { ?>
<a<?= $Grid->comprobante->linkAttributes() ?>><input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->comprobante->getDisplayValue($Grid->comprobante->EditValue))) ?>"></a>
<?php } else { ?>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->comprobante->getDisplayValue($Grid->comprobante->EditValue))) ?>">
<?php } ?>
</span>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_comprobante" data-hidden="1" name="x<?= $Grid->RowIndex ?>_comprobante" id="x<?= $Grid->RowIndex ?>_comprobante" value="<?= HtmlEncode($Grid->comprobante->CurrentValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_cobros_cliente_factura_comprobante">
<span<?= $Grid->comprobante->viewAttributes() ?>>
<?php if (!EmptyString($Grid->comprobante->getViewValue()) && $Grid->comprobante->linkAttributes() != "") { ?>
<a<?= $Grid->comprobante->linkAttributes() ?>><?= $Grid->comprobante->getViewValue() ?></a>
<?php } else { ?>
<?= $Grid->comprobante->getViewValue() ?>
<?php } ?>
</span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_comprobante" data-hidden="1" name="fcobros_cliente_facturagrid$x<?= $Grid->RowIndex ?>_comprobante" id="fcobros_cliente_facturagrid$x<?= $Grid->RowIndex ?>_comprobante" value="<?= HtmlEncode($Grid->comprobante->FormValue) ?>">
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_comprobante" data-hidden="1" name="fcobros_cliente_facturagrid$o<?= $Grid->RowIndex ?>_comprobante" id="fcobros_cliente_facturagrid$o<?= $Grid->RowIndex ?>_comprobante" value="<?= HtmlEncode($Grid->comprobante->OldValue) ?>">
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
loadjs.ready(["fcobros_cliente_facturagrid","load"], function () {
    fcobros_cliente_facturagrid.updateLists(<?= $Grid->RowIndex ?>);
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_cobros_cliente_factura", "data-rowtype" => ROWTYPE_ADD]);
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
    <?php if ($Grid->tipo_documento->Visible) { // tipo_documento ?>
        <td data-name="tipo_documento">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_cobros_cliente_factura_tipo_documento" class="form-group cobros_cliente_factura_tipo_documento">
<?php
$onchange = $Grid->tipo_documento->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Grid->tipo_documento->EditAttrs["onchange"] = "";
?>
<span id="as_x<?= $Grid->RowIndex ?>_tipo_documento" class="ew-auto-suggest">
    <input type="<?= $Grid->tipo_documento->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_tipo_documento" id="sv_x<?= $Grid->RowIndex ?>_tipo_documento" value="<?= RemoveHtml($Grid->tipo_documento->EditValue) ?>" size="30" maxlength="6" placeholder="<?= HtmlEncode($Grid->tipo_documento->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->tipo_documento->getPlaceHolder()) ?>"<?= $Grid->tipo_documento->editAttributes() ?>>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="cobros_cliente_factura" data-field="x_tipo_documento" data-input="sv_x<?= $Grid->RowIndex ?>_tipo_documento" data-value-separator="<?= $Grid->tipo_documento->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_tipo_documento" id="x<?= $Grid->RowIndex ?>_tipo_documento" value="<?= HtmlEncode($Grid->tipo_documento->CurrentValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Grid->tipo_documento->getErrorMessage() ?></div>
<script>
loadjs.ready(["fcobros_cliente_facturagrid"], function() {
    fcobros_cliente_facturagrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_tipo_documento","forceSelect":false}, ew.vars.tables.cobros_cliente_factura.fields.tipo_documento.autoSuggestOptions));
});
</script>
<?= $Grid->tipo_documento->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_tipo_documento") ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_cobros_cliente_factura_tipo_documento" class="form-group cobros_cliente_factura_tipo_documento">
<span<?= $Grid->tipo_documento->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->tipo_documento->getDisplayValue($Grid->tipo_documento->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_tipo_documento" data-hidden="1" name="x<?= $Grid->RowIndex ?>_tipo_documento" id="x<?= $Grid->RowIndex ?>_tipo_documento" value="<?= HtmlEncode($Grid->tipo_documento->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_tipo_documento" data-hidden="1" name="o<?= $Grid->RowIndex ?>_tipo_documento" id="o<?= $Grid->RowIndex ?>_tipo_documento" value="<?= HtmlEncode($Grid->tipo_documento->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->abono->Visible) { // abono ?>
        <td data-name="abono">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_cobros_cliente_factura_abono" class="form-group cobros_cliente_factura_abono">
<template id="tp_x<?= $Grid->RowIndex ?>_abono">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="cobros_cliente_factura" data-field="x_abono" name="x<?= $Grid->RowIndex ?>_abono" id="x<?= $Grid->RowIndex ?>_abono"<?= $Grid->abono->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x<?= $Grid->RowIndex ?>_abono" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x<?= $Grid->RowIndex ?>_abono"
    name="x<?= $Grid->RowIndex ?>_abono"
    value="<?= HtmlEncode($Grid->abono->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x<?= $Grid->RowIndex ?>_abono"
    data-target="dsl_x<?= $Grid->RowIndex ?>_abono"
    data-repeatcolumn="5"
    class="form-control<?= $Grid->abono->isInvalidClass() ?>"
    data-table="cobros_cliente_factura"
    data-field="x_abono"
    data-value-separator="<?= $Grid->abono->displayValueSeparatorAttribute() ?>"
    <?= $Grid->abono->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->abono->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_cobros_cliente_factura_abono" class="form-group cobros_cliente_factura_abono">
<span<?= $Grid->abono->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->abono->getDisplayValue($Grid->abono->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_abono" data-hidden="1" name="x<?= $Grid->RowIndex ?>_abono" id="x<?= $Grid->RowIndex ?>_abono" value="<?= HtmlEncode($Grid->abono->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_abono" data-hidden="1" name="o<?= $Grid->RowIndex ?>_abono" id="o<?= $Grid->RowIndex ?>_abono" value="<?= HtmlEncode($Grid->abono->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->monto->Visible) { // monto ?>
        <td data-name="monto">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_cobros_cliente_factura_monto" class="form-group cobros_cliente_factura_monto">
<input type="<?= $Grid->monto->getInputTextType() ?>" data-table="cobros_cliente_factura" data-field="x_monto" name="x<?= $Grid->RowIndex ?>_monto" id="x<?= $Grid->RowIndex ?>_monto" size="30" placeholder="<?= HtmlEncode($Grid->monto->getPlaceHolder()) ?>" value="<?= $Grid->monto->EditValue ?>"<?= $Grid->monto->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_cobros_cliente_factura_monto" class="form-group cobros_cliente_factura_monto">
<span<?= $Grid->monto->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->monto->getDisplayValue($Grid->monto->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_monto" data-hidden="1" name="x<?= $Grid->RowIndex ?>_monto" id="x<?= $Grid->RowIndex ?>_monto" value="<?= HtmlEncode($Grid->monto->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_monto" data-hidden="1" name="o<?= $Grid->RowIndex ?>_monto" id="o<?= $Grid->RowIndex ?>_monto" value="<?= HtmlEncode($Grid->monto->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->retivamonto->Visible) { // retivamonto ?>
        <td data-name="retivamonto">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_cobros_cliente_factura_retivamonto" class="form-group cobros_cliente_factura_retivamonto">
<input type="<?= $Grid->retivamonto->getInputTextType() ?>" data-table="cobros_cliente_factura" data-field="x_retivamonto" name="x<?= $Grid->RowIndex ?>_retivamonto" id="x<?= $Grid->RowIndex ?>_retivamonto" size="30" placeholder="<?= HtmlEncode($Grid->retivamonto->getPlaceHolder()) ?>" value="<?= $Grid->retivamonto->EditValue ?>"<?= $Grid->retivamonto->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->retivamonto->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_cobros_cliente_factura_retivamonto" class="form-group cobros_cliente_factura_retivamonto">
<span<?= $Grid->retivamonto->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->retivamonto->getDisplayValue($Grid->retivamonto->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retivamonto" data-hidden="1" name="x<?= $Grid->RowIndex ?>_retivamonto" id="x<?= $Grid->RowIndex ?>_retivamonto" value="<?= HtmlEncode($Grid->retivamonto->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retivamonto" data-hidden="1" name="o<?= $Grid->RowIndex ?>_retivamonto" id="o<?= $Grid->RowIndex ?>_retivamonto" value="<?= HtmlEncode($Grid->retivamonto->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->retiva->Visible) { // retiva ?>
        <td data-name="retiva">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_cobros_cliente_factura_retiva" class="form-group cobros_cliente_factura_retiva">
<input type="<?= $Grid->retiva->getInputTextType() ?>" data-table="cobros_cliente_factura" data-field="x_retiva" name="x<?= $Grid->RowIndex ?>_retiva" id="x<?= $Grid->RowIndex ?>_retiva" size="30" maxlength="15" placeholder="<?= HtmlEncode($Grid->retiva->getPlaceHolder()) ?>" value="<?= $Grid->retiva->EditValue ?>"<?= $Grid->retiva->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->retiva->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_cobros_cliente_factura_retiva" class="form-group cobros_cliente_factura_retiva">
<span<?= $Grid->retiva->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->retiva->getDisplayValue($Grid->retiva->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retiva" data-hidden="1" name="x<?= $Grid->RowIndex ?>_retiva" id="x<?= $Grid->RowIndex ?>_retiva" value="<?= HtmlEncode($Grid->retiva->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retiva" data-hidden="1" name="o<?= $Grid->RowIndex ?>_retiva" id="o<?= $Grid->RowIndex ?>_retiva" value="<?= HtmlEncode($Grid->retiva->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->retislrmonto->Visible) { // retislrmonto ?>
        <td data-name="retislrmonto">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_cobros_cliente_factura_retislrmonto" class="form-group cobros_cliente_factura_retislrmonto">
<input type="<?= $Grid->retislrmonto->getInputTextType() ?>" data-table="cobros_cliente_factura" data-field="x_retislrmonto" name="x<?= $Grid->RowIndex ?>_retislrmonto" id="x<?= $Grid->RowIndex ?>_retislrmonto" size="30" placeholder="<?= HtmlEncode($Grid->retislrmonto->getPlaceHolder()) ?>" value="<?= $Grid->retislrmonto->EditValue ?>"<?= $Grid->retislrmonto->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->retislrmonto->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_cobros_cliente_factura_retislrmonto" class="form-group cobros_cliente_factura_retislrmonto">
<span<?= $Grid->retislrmonto->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->retislrmonto->getDisplayValue($Grid->retislrmonto->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retislrmonto" data-hidden="1" name="x<?= $Grid->RowIndex ?>_retislrmonto" id="x<?= $Grid->RowIndex ?>_retislrmonto" value="<?= HtmlEncode($Grid->retislrmonto->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retislrmonto" data-hidden="1" name="o<?= $Grid->RowIndex ?>_retislrmonto" id="o<?= $Grid->RowIndex ?>_retislrmonto" value="<?= HtmlEncode($Grid->retislrmonto->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->retislr->Visible) { // retislr ?>
        <td data-name="retislr">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_cobros_cliente_factura_retislr" class="form-group cobros_cliente_factura_retislr">
<input type="<?= $Grid->retislr->getInputTextType() ?>" data-table="cobros_cliente_factura" data-field="x_retislr" name="x<?= $Grid->RowIndex ?>_retislr" id="x<?= $Grid->RowIndex ?>_retislr" size="30" maxlength="15" placeholder="<?= HtmlEncode($Grid->retislr->getPlaceHolder()) ?>" value="<?= $Grid->retislr->EditValue ?>"<?= $Grid->retislr->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->retislr->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_cobros_cliente_factura_retislr" class="form-group cobros_cliente_factura_retislr">
<span<?= $Grid->retislr->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->retislr->getDisplayValue($Grid->retislr->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retislr" data-hidden="1" name="x<?= $Grid->RowIndex ?>_retislr" id="x<?= $Grid->RowIndex ?>_retislr" value="<?= HtmlEncode($Grid->retislr->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_retislr" data-hidden="1" name="o<?= $Grid->RowIndex ?>_retislr" id="o<?= $Grid->RowIndex ?>_retislr" value="<?= HtmlEncode($Grid->retislr->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->comprobante->Visible) { // comprobante ?>
        <td data-name="comprobante">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_cobros_cliente_factura_comprobante" class="form-group cobros_cliente_factura_comprobante">
<?php
$onchange = $Grid->comprobante->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Grid->comprobante->EditAttrs["onchange"] = "";
?>
<span id="as_x<?= $Grid->RowIndex ?>_comprobante" class="ew-auto-suggest">
    <input type="<?= $Grid->comprobante->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_comprobante" id="sv_x<?= $Grid->RowIndex ?>_comprobante" value="<?= RemoveHtml($Grid->comprobante->EditValue) ?>" size="30" placeholder="<?= HtmlEncode($Grid->comprobante->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->comprobante->getPlaceHolder()) ?>"<?= $Grid->comprobante->editAttributes() ?>>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="cobros_cliente_factura" data-field="x_comprobante" data-input="sv_x<?= $Grid->RowIndex ?>_comprobante" data-value-separator="<?= $Grid->comprobante->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_comprobante" id="x<?= $Grid->RowIndex ?>_comprobante" value="<?= HtmlEncode($Grid->comprobante->CurrentValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Grid->comprobante->getErrorMessage() ?></div>
<script>
loadjs.ready(["fcobros_cliente_facturagrid"], function() {
    fcobros_cliente_facturagrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_comprobante","forceSelect":false}, ew.vars.tables.cobros_cliente_factura.fields.comprobante.autoSuggestOptions));
});
</script>
<?= $Grid->comprobante->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_comprobante") ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_cobros_cliente_factura_comprobante" class="form-group cobros_cliente_factura_comprobante">
<span<?= $Grid->comprobante->viewAttributes() ?>>
<?php if (!EmptyString($Grid->comprobante->ViewValue) && $Grid->comprobante->linkAttributes() != "") { ?>
<a<?= $Grid->comprobante->linkAttributes() ?>><input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->comprobante->getDisplayValue($Grid->comprobante->ViewValue))) ?>"></a>
<?php } else { ?>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->comprobante->getDisplayValue($Grid->comprobante->ViewValue))) ?>">
<?php } ?>
</span>
</span>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_comprobante" data-hidden="1" name="x<?= $Grid->RowIndex ?>_comprobante" id="x<?= $Grid->RowIndex ?>_comprobante" value="<?= HtmlEncode($Grid->comprobante->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="cobros_cliente_factura" data-field="x_comprobante" data-hidden="1" name="o<?= $Grid->RowIndex ?>_comprobante" id="o<?= $Grid->RowIndex ?>_comprobante" value="<?= HtmlEncode($Grid->comprobante->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["fcobros_cliente_facturagrid","load"], function() {
    fcobros_cliente_facturagrid.updateLists(<?= $Grid->RowIndex ?>);
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
<input type="hidden" name="detailpage" value="fcobros_cliente_facturagrid">
</div><!-- /.ew-list-form -->
<?php
// Close recordset
if ($Grid->Recordset) {
    $Grid->Recordset->close();
}
?>
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php $Grid->OtherOptions->render("body", "bottom") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
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
    ew.addEventHandlers("cobros_cliente_factura");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
