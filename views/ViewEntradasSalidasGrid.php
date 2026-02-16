<?php

namespace PHPMaker2021\mandrake;

// Set up and run Grid object
$Grid = Container("ViewEntradasSalidasGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fview_entradas_salidasgrid;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    fview_entradas_salidasgrid = new ew.Form("fview_entradas_salidasgrid", "grid");
    fview_entradas_salidasgrid.formKeyCountName = '<?= $Grid->FormKeyCountName ?>';

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "view_entradas_salidas")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.view_entradas_salidas)
        ew.vars.tables.view_entradas_salidas = currentTable;
    fview_entradas_salidasgrid.addFields([
        ["fabricante", [fields.fabricante.visible && fields.fabricante.required ? ew.Validators.required(fields.fabricante.caption) : null, ew.Validators.integer], fields.fabricante.isInvalid],
        ["articulo", [fields.articulo.visible && fields.articulo.required ? ew.Validators.required(fields.articulo.caption) : null, ew.Validators.integer], fields.articulo.isInvalid],
        ["lote", [fields.lote.visible && fields.lote.required ? ew.Validators.required(fields.lote.caption) : null], fields.lote.isInvalid],
        ["fecha_vencimiento", [fields.fecha_vencimiento.visible && fields.fecha_vencimiento.required ? ew.Validators.required(fields.fecha_vencimiento.caption) : null, ew.Validators.datetime(7)], fields.fecha_vencimiento.isInvalid],
        ["cantidad_articulo", [fields.cantidad_articulo.visible && fields.cantidad_articulo.required ? ew.Validators.required(fields.cantidad_articulo.caption) : null, ew.Validators.float], fields.cantidad_articulo.isInvalid],
        ["articulo_unidad_medida", [fields.articulo_unidad_medida.visible && fields.articulo_unidad_medida.required ? ew.Validators.required(fields.articulo_unidad_medida.caption) : null], fields.articulo_unidad_medida.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fview_entradas_salidasgrid,
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
    fview_entradas_salidasgrid.validate = function () {
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
    fview_entradas_salidasgrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "fabricante", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "articulo", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "lote", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "fecha_vencimiento", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "cantidad_articulo", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "articulo_unidad_medida", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    fview_entradas_salidasgrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fview_entradas_salidasgrid.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fview_entradas_salidasgrid.lists.fabricante = <?= $Grid->fabricante->toClientList($Grid) ?>;
    fview_entradas_salidasgrid.lists.articulo = <?= $Grid->articulo->toClientList($Grid) ?>;
    fview_entradas_salidasgrid.lists.articulo_unidad_medida = <?= $Grid->articulo_unidad_medida->toClientList($Grid) ?>;
    loadjs.done("fview_entradas_salidasgrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> view_entradas_salidas">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="fview_entradas_salidasgrid" class="ew-form ew-list-form form-inline">
<div id="gmp_view_entradas_salidas" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_view_entradas_salidasgrid" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Grid->fabricante->Visible) { // fabricante ?>
        <th data-name="fabricante" class="<?= $Grid->fabricante->headerCellClass() ?>"><div id="elh_view_entradas_salidas_fabricante" class="view_entradas_salidas_fabricante"><?= $Grid->renderSort($Grid->fabricante) ?></div></th>
<?php } ?>
<?php if ($Grid->articulo->Visible) { // articulo ?>
        <th data-name="articulo" class="<?= $Grid->articulo->headerCellClass() ?>"><div id="elh_view_entradas_salidas_articulo" class="view_entradas_salidas_articulo"><?= $Grid->renderSort($Grid->articulo) ?></div></th>
<?php } ?>
<?php if ($Grid->lote->Visible) { // lote ?>
        <th data-name="lote" class="<?= $Grid->lote->headerCellClass() ?>"><div id="elh_view_entradas_salidas_lote" class="view_entradas_salidas_lote"><?= $Grid->renderSort($Grid->lote) ?></div></th>
<?php } ?>
<?php if ($Grid->fecha_vencimiento->Visible) { // fecha_vencimiento ?>
        <th data-name="fecha_vencimiento" class="<?= $Grid->fecha_vencimiento->headerCellClass() ?>"><div id="elh_view_entradas_salidas_fecha_vencimiento" class="view_entradas_salidas_fecha_vencimiento"><?= $Grid->renderSort($Grid->fecha_vencimiento) ?></div></th>
<?php } ?>
<?php if ($Grid->cantidad_articulo->Visible) { // cantidad_articulo ?>
        <th data-name="cantidad_articulo" class="<?= $Grid->cantidad_articulo->headerCellClass() ?>"><div id="elh_view_entradas_salidas_cantidad_articulo" class="view_entradas_salidas_cantidad_articulo"><?= $Grid->renderSort($Grid->cantidad_articulo) ?></div></th>
<?php } ?>
<?php if ($Grid->articulo_unidad_medida->Visible) { // articulo_unidad_medida ?>
        <th data-name="articulo_unidad_medida" class="<?= $Grid->articulo_unidad_medida->headerCellClass() ?>"><div id="elh_view_entradas_salidas_articulo_unidad_medida" class="view_entradas_salidas_articulo_unidad_medida"><?= $Grid->renderSort($Grid->articulo_unidad_medida) ?></div></th>
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowCount, "id" => "r" . $Grid->RowCount . "_view_entradas_salidas", "data-rowtype" => $Grid->RowType]);

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
    <?php if ($Grid->fabricante->Visible) { // fabricante ?>
        <td data-name="fabricante" <?= $Grid->fabricante->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_view_entradas_salidas_fabricante" class="form-group">
<?php
$onchange = $Grid->fabricante->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Grid->fabricante->EditAttrs["onchange"] = "";
?>
<span id="as_x<?= $Grid->RowIndex ?>_fabricante" class="ew-auto-suggest">
    <input type="<?= $Grid->fabricante->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_fabricante" id="sv_x<?= $Grid->RowIndex ?>_fabricante" value="<?= RemoveHtml($Grid->fabricante->EditValue) ?>" size="30" placeholder="<?= HtmlEncode($Grid->fabricante->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->fabricante->getPlaceHolder()) ?>"<?= $Grid->fabricante->editAttributes() ?>>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="view_entradas_salidas" data-field="x_fabricante" data-input="sv_x<?= $Grid->RowIndex ?>_fabricante" data-value-separator="<?= $Grid->fabricante->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_fabricante" id="x<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->CurrentValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Grid->fabricante->getErrorMessage() ?></div>
<script>
loadjs.ready(["fview_entradas_salidasgrid"], function() {
    fview_entradas_salidasgrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_fabricante","forceSelect":false}, ew.vars.tables.view_entradas_salidas.fields.fabricante.autoSuggestOptions));
});
</script>
<?= $Grid->fabricante->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_fabricante") ?>
</span>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_fabricante" data-hidden="1" name="o<?= $Grid->RowIndex ?>_fabricante" id="o<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_view_entradas_salidas_fabricante" class="form-group">
<?php
$onchange = $Grid->fabricante->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Grid->fabricante->EditAttrs["onchange"] = "";
?>
<span id="as_x<?= $Grid->RowIndex ?>_fabricante" class="ew-auto-suggest">
    <input type="<?= $Grid->fabricante->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_fabricante" id="sv_x<?= $Grid->RowIndex ?>_fabricante" value="<?= RemoveHtml($Grid->fabricante->EditValue) ?>" size="30" placeholder="<?= HtmlEncode($Grid->fabricante->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->fabricante->getPlaceHolder()) ?>"<?= $Grid->fabricante->editAttributes() ?>>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="view_entradas_salidas" data-field="x_fabricante" data-input="sv_x<?= $Grid->RowIndex ?>_fabricante" data-value-separator="<?= $Grid->fabricante->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_fabricante" id="x<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->CurrentValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Grid->fabricante->getErrorMessage() ?></div>
<script>
loadjs.ready(["fview_entradas_salidasgrid"], function() {
    fview_entradas_salidasgrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_fabricante","forceSelect":false}, ew.vars.tables.view_entradas_salidas.fields.fabricante.autoSuggestOptions));
});
</script>
<?= $Grid->fabricante->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_fabricante") ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_view_entradas_salidas_fabricante">
<span<?= $Grid->fabricante->viewAttributes() ?>>
<?= $Grid->fabricante->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_fabricante" data-hidden="1" name="fview_entradas_salidasgrid$x<?= $Grid->RowIndex ?>_fabricante" id="fview_entradas_salidasgrid$x<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->FormValue) ?>">
<input type="hidden" data-table="view_entradas_salidas" data-field="x_fabricante" data-hidden="1" name="fview_entradas_salidasgrid$o<?= $Grid->RowIndex ?>_fabricante" id="fview_entradas_salidasgrid$o<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->articulo->Visible) { // articulo ?>
        <td data-name="articulo" <?= $Grid->articulo->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_view_entradas_salidas_articulo" class="form-group">
<?php
$onchange = $Grid->articulo->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Grid->articulo->EditAttrs["onchange"] = "";
?>
<span id="as_x<?= $Grid->RowIndex ?>_articulo" class="ew-auto-suggest">
    <input type="<?= $Grid->articulo->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_articulo" id="sv_x<?= $Grid->RowIndex ?>_articulo" value="<?= RemoveHtml($Grid->articulo->EditValue) ?>" size="30" placeholder="<?= HtmlEncode($Grid->articulo->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->articulo->getPlaceHolder()) ?>"<?= $Grid->articulo->editAttributes() ?>>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="view_entradas_salidas" data-field="x_articulo" data-input="sv_x<?= $Grid->RowIndex ?>_articulo" data-value-separator="<?= $Grid->articulo->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_articulo" id="x<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->CurrentValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Grid->articulo->getErrorMessage() ?></div>
<script>
loadjs.ready(["fview_entradas_salidasgrid"], function() {
    fview_entradas_salidasgrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_articulo","forceSelect":false}, ew.vars.tables.view_entradas_salidas.fields.articulo.autoSuggestOptions));
});
</script>
<?= $Grid->articulo->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_articulo") ?>
</span>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_articulo" data-hidden="1" name="o<?= $Grid->RowIndex ?>_articulo" id="o<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_view_entradas_salidas_articulo" class="form-group">
<?php
$onchange = $Grid->articulo->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Grid->articulo->EditAttrs["onchange"] = "";
?>
<span id="as_x<?= $Grid->RowIndex ?>_articulo" class="ew-auto-suggest">
    <input type="<?= $Grid->articulo->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_articulo" id="sv_x<?= $Grid->RowIndex ?>_articulo" value="<?= RemoveHtml($Grid->articulo->EditValue) ?>" size="30" placeholder="<?= HtmlEncode($Grid->articulo->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->articulo->getPlaceHolder()) ?>"<?= $Grid->articulo->editAttributes() ?>>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="view_entradas_salidas" data-field="x_articulo" data-input="sv_x<?= $Grid->RowIndex ?>_articulo" data-value-separator="<?= $Grid->articulo->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_articulo" id="x<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->CurrentValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Grid->articulo->getErrorMessage() ?></div>
<script>
loadjs.ready(["fview_entradas_salidasgrid"], function() {
    fview_entradas_salidasgrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_articulo","forceSelect":false}, ew.vars.tables.view_entradas_salidas.fields.articulo.autoSuggestOptions));
});
</script>
<?= $Grid->articulo->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_articulo") ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_view_entradas_salidas_articulo">
<span<?= $Grid->articulo->viewAttributes() ?>>
<?= $Grid->articulo->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_articulo" data-hidden="1" name="fview_entradas_salidasgrid$x<?= $Grid->RowIndex ?>_articulo" id="fview_entradas_salidasgrid$x<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->FormValue) ?>">
<input type="hidden" data-table="view_entradas_salidas" data-field="x_articulo" data-hidden="1" name="fview_entradas_salidasgrid$o<?= $Grid->RowIndex ?>_articulo" id="fview_entradas_salidasgrid$o<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->lote->Visible) { // lote ?>
        <td data-name="lote" <?= $Grid->lote->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_view_entradas_salidas_lote" class="form-group">
<input type="<?= $Grid->lote->getInputTextType() ?>" data-table="view_entradas_salidas" data-field="x_lote" name="x<?= $Grid->RowIndex ?>_lote" id="x<?= $Grid->RowIndex ?>_lote" size="30" maxlength="20" placeholder="<?= HtmlEncode($Grid->lote->getPlaceHolder()) ?>" value="<?= $Grid->lote->EditValue ?>"<?= $Grid->lote->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->lote->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_lote" data-hidden="1" name="o<?= $Grid->RowIndex ?>_lote" id="o<?= $Grid->RowIndex ?>_lote" value="<?= HtmlEncode($Grid->lote->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_view_entradas_salidas_lote" class="form-group">
<input type="<?= $Grid->lote->getInputTextType() ?>" data-table="view_entradas_salidas" data-field="x_lote" name="x<?= $Grid->RowIndex ?>_lote" id="x<?= $Grid->RowIndex ?>_lote" size="30" maxlength="20" placeholder="<?= HtmlEncode($Grid->lote->getPlaceHolder()) ?>" value="<?= $Grid->lote->EditValue ?>"<?= $Grid->lote->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->lote->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_view_entradas_salidas_lote">
<span<?= $Grid->lote->viewAttributes() ?>>
<?= $Grid->lote->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_lote" data-hidden="1" name="fview_entradas_salidasgrid$x<?= $Grid->RowIndex ?>_lote" id="fview_entradas_salidasgrid$x<?= $Grid->RowIndex ?>_lote" value="<?= HtmlEncode($Grid->lote->FormValue) ?>">
<input type="hidden" data-table="view_entradas_salidas" data-field="x_lote" data-hidden="1" name="fview_entradas_salidasgrid$o<?= $Grid->RowIndex ?>_lote" id="fview_entradas_salidasgrid$o<?= $Grid->RowIndex ?>_lote" value="<?= HtmlEncode($Grid->lote->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->fecha_vencimiento->Visible) { // fecha_vencimiento ?>
        <td data-name="fecha_vencimiento" <?= $Grid->fecha_vencimiento->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_view_entradas_salidas_fecha_vencimiento" class="form-group">
<input type="<?= $Grid->fecha_vencimiento->getInputTextType() ?>" data-table="view_entradas_salidas" data-field="x_fecha_vencimiento" data-format="7" name="x<?= $Grid->RowIndex ?>_fecha_vencimiento" id="x<?= $Grid->RowIndex ?>_fecha_vencimiento" placeholder="<?= HtmlEncode($Grid->fecha_vencimiento->getPlaceHolder()) ?>" value="<?= $Grid->fecha_vencimiento->EditValue ?>"<?= $Grid->fecha_vencimiento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->fecha_vencimiento->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_fecha_vencimiento" data-hidden="1" name="o<?= $Grid->RowIndex ?>_fecha_vencimiento" id="o<?= $Grid->RowIndex ?>_fecha_vencimiento" value="<?= HtmlEncode($Grid->fecha_vencimiento->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_view_entradas_salidas_fecha_vencimiento" class="form-group">
<input type="<?= $Grid->fecha_vencimiento->getInputTextType() ?>" data-table="view_entradas_salidas" data-field="x_fecha_vencimiento" data-format="7" name="x<?= $Grid->RowIndex ?>_fecha_vencimiento" id="x<?= $Grid->RowIndex ?>_fecha_vencimiento" placeholder="<?= HtmlEncode($Grid->fecha_vencimiento->getPlaceHolder()) ?>" value="<?= $Grid->fecha_vencimiento->EditValue ?>"<?= $Grid->fecha_vencimiento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->fecha_vencimiento->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_view_entradas_salidas_fecha_vencimiento">
<span<?= $Grid->fecha_vencimiento->viewAttributes() ?>>
<?= $Grid->fecha_vencimiento->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_fecha_vencimiento" data-hidden="1" name="fview_entradas_salidasgrid$x<?= $Grid->RowIndex ?>_fecha_vencimiento" id="fview_entradas_salidasgrid$x<?= $Grid->RowIndex ?>_fecha_vencimiento" value="<?= HtmlEncode($Grid->fecha_vencimiento->FormValue) ?>">
<input type="hidden" data-table="view_entradas_salidas" data-field="x_fecha_vencimiento" data-hidden="1" name="fview_entradas_salidasgrid$o<?= $Grid->RowIndex ?>_fecha_vencimiento" id="fview_entradas_salidasgrid$o<?= $Grid->RowIndex ?>_fecha_vencimiento" value="<?= HtmlEncode($Grid->fecha_vencimiento->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->cantidad_articulo->Visible) { // cantidad_articulo ?>
        <td data-name="cantidad_articulo" <?= $Grid->cantidad_articulo->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_view_entradas_salidas_cantidad_articulo" class="form-group">
<input type="<?= $Grid->cantidad_articulo->getInputTextType() ?>" data-table="view_entradas_salidas" data-field="x_cantidad_articulo" name="x<?= $Grid->RowIndex ?>_cantidad_articulo" id="x<?= $Grid->RowIndex ?>_cantidad_articulo" size="30" maxlength="10" placeholder="<?= HtmlEncode($Grid->cantidad_articulo->getPlaceHolder()) ?>" value="<?= $Grid->cantidad_articulo->EditValue ?>"<?= $Grid->cantidad_articulo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->cantidad_articulo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_cantidad_articulo" data-hidden="1" name="o<?= $Grid->RowIndex ?>_cantidad_articulo" id="o<?= $Grid->RowIndex ?>_cantidad_articulo" value="<?= HtmlEncode($Grid->cantidad_articulo->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_view_entradas_salidas_cantidad_articulo" class="form-group">
<input type="<?= $Grid->cantidad_articulo->getInputTextType() ?>" data-table="view_entradas_salidas" data-field="x_cantidad_articulo" name="x<?= $Grid->RowIndex ?>_cantidad_articulo" id="x<?= $Grid->RowIndex ?>_cantidad_articulo" size="30" maxlength="10" placeholder="<?= HtmlEncode($Grid->cantidad_articulo->getPlaceHolder()) ?>" value="<?= $Grid->cantidad_articulo->EditValue ?>"<?= $Grid->cantidad_articulo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->cantidad_articulo->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_view_entradas_salidas_cantidad_articulo">
<span<?= $Grid->cantidad_articulo->viewAttributes() ?>>
<?= $Grid->cantidad_articulo->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_cantidad_articulo" data-hidden="1" name="fview_entradas_salidasgrid$x<?= $Grid->RowIndex ?>_cantidad_articulo" id="fview_entradas_salidasgrid$x<?= $Grid->RowIndex ?>_cantidad_articulo" value="<?= HtmlEncode($Grid->cantidad_articulo->FormValue) ?>">
<input type="hidden" data-table="view_entradas_salidas" data-field="x_cantidad_articulo" data-hidden="1" name="fview_entradas_salidasgrid$o<?= $Grid->RowIndex ?>_cantidad_articulo" id="fview_entradas_salidasgrid$o<?= $Grid->RowIndex ?>_cantidad_articulo" value="<?= HtmlEncode($Grid->cantidad_articulo->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->articulo_unidad_medida->Visible) { // articulo_unidad_medida ?>
        <td data-name="articulo_unidad_medida" <?= $Grid->articulo_unidad_medida->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_view_entradas_salidas_articulo_unidad_medida" class="form-group">
<?php
$onchange = $Grid->articulo_unidad_medida->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Grid->articulo_unidad_medida->EditAttrs["onchange"] = "";
?>
<span id="as_x<?= $Grid->RowIndex ?>_articulo_unidad_medida" class="ew-auto-suggest">
    <input type="<?= $Grid->articulo_unidad_medida->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_articulo_unidad_medida" id="sv_x<?= $Grid->RowIndex ?>_articulo_unidad_medida" value="<?= RemoveHtml($Grid->articulo_unidad_medida->EditValue) ?>" size="30" maxlength="6" placeholder="<?= HtmlEncode($Grid->articulo_unidad_medida->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->articulo_unidad_medida->getPlaceHolder()) ?>"<?= $Grid->articulo_unidad_medida->editAttributes() ?>>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="view_entradas_salidas" data-field="x_articulo_unidad_medida" data-input="sv_x<?= $Grid->RowIndex ?>_articulo_unidad_medida" data-value-separator="<?= $Grid->articulo_unidad_medida->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_articulo_unidad_medida" id="x<?= $Grid->RowIndex ?>_articulo_unidad_medida" value="<?= HtmlEncode($Grid->articulo_unidad_medida->CurrentValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Grid->articulo_unidad_medida->getErrorMessage() ?></div>
<script>
loadjs.ready(["fview_entradas_salidasgrid"], function() {
    fview_entradas_salidasgrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_articulo_unidad_medida","forceSelect":false}, ew.vars.tables.view_entradas_salidas.fields.articulo_unidad_medida.autoSuggestOptions));
});
</script>
<?= $Grid->articulo_unidad_medida->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_articulo_unidad_medida") ?>
</span>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_articulo_unidad_medida" data-hidden="1" name="o<?= $Grid->RowIndex ?>_articulo_unidad_medida" id="o<?= $Grid->RowIndex ?>_articulo_unidad_medida" value="<?= HtmlEncode($Grid->articulo_unidad_medida->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_view_entradas_salidas_articulo_unidad_medida" class="form-group">
<?php
$onchange = $Grid->articulo_unidad_medida->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Grid->articulo_unidad_medida->EditAttrs["onchange"] = "";
?>
<span id="as_x<?= $Grid->RowIndex ?>_articulo_unidad_medida" class="ew-auto-suggest">
    <input type="<?= $Grid->articulo_unidad_medida->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_articulo_unidad_medida" id="sv_x<?= $Grid->RowIndex ?>_articulo_unidad_medida" value="<?= RemoveHtml($Grid->articulo_unidad_medida->EditValue) ?>" size="30" maxlength="6" placeholder="<?= HtmlEncode($Grid->articulo_unidad_medida->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->articulo_unidad_medida->getPlaceHolder()) ?>"<?= $Grid->articulo_unidad_medida->editAttributes() ?>>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="view_entradas_salidas" data-field="x_articulo_unidad_medida" data-input="sv_x<?= $Grid->RowIndex ?>_articulo_unidad_medida" data-value-separator="<?= $Grid->articulo_unidad_medida->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_articulo_unidad_medida" id="x<?= $Grid->RowIndex ?>_articulo_unidad_medida" value="<?= HtmlEncode($Grid->articulo_unidad_medida->CurrentValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Grid->articulo_unidad_medida->getErrorMessage() ?></div>
<script>
loadjs.ready(["fview_entradas_salidasgrid"], function() {
    fview_entradas_salidasgrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_articulo_unidad_medida","forceSelect":false}, ew.vars.tables.view_entradas_salidas.fields.articulo_unidad_medida.autoSuggestOptions));
});
</script>
<?= $Grid->articulo_unidad_medida->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_articulo_unidad_medida") ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_view_entradas_salidas_articulo_unidad_medida">
<span<?= $Grid->articulo_unidad_medida->viewAttributes() ?>>
<?= $Grid->articulo_unidad_medida->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_articulo_unidad_medida" data-hidden="1" name="fview_entradas_salidasgrid$x<?= $Grid->RowIndex ?>_articulo_unidad_medida" id="fview_entradas_salidasgrid$x<?= $Grid->RowIndex ?>_articulo_unidad_medida" value="<?= HtmlEncode($Grid->articulo_unidad_medida->FormValue) ?>">
<input type="hidden" data-table="view_entradas_salidas" data-field="x_articulo_unidad_medida" data-hidden="1" name="fview_entradas_salidasgrid$o<?= $Grid->RowIndex ?>_articulo_unidad_medida" id="fview_entradas_salidasgrid$o<?= $Grid->RowIndex ?>_articulo_unidad_medida" value="<?= HtmlEncode($Grid->articulo_unidad_medida->OldValue) ?>">
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
loadjs.ready(["fview_entradas_salidasgrid","load"], function () {
    fview_entradas_salidasgrid.updateLists(<?= $Grid->RowIndex ?>);
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_view_entradas_salidas", "data-rowtype" => ROWTYPE_ADD]);
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
    <?php if ($Grid->fabricante->Visible) { // fabricante ?>
        <td data-name="fabricante">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_view_entradas_salidas_fabricante" class="form-group view_entradas_salidas_fabricante">
<?php
$onchange = $Grid->fabricante->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Grid->fabricante->EditAttrs["onchange"] = "";
?>
<span id="as_x<?= $Grid->RowIndex ?>_fabricante" class="ew-auto-suggest">
    <input type="<?= $Grid->fabricante->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_fabricante" id="sv_x<?= $Grid->RowIndex ?>_fabricante" value="<?= RemoveHtml($Grid->fabricante->EditValue) ?>" size="30" placeholder="<?= HtmlEncode($Grid->fabricante->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->fabricante->getPlaceHolder()) ?>"<?= $Grid->fabricante->editAttributes() ?>>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="view_entradas_salidas" data-field="x_fabricante" data-input="sv_x<?= $Grid->RowIndex ?>_fabricante" data-value-separator="<?= $Grid->fabricante->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_fabricante" id="x<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->CurrentValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Grid->fabricante->getErrorMessage() ?></div>
<script>
loadjs.ready(["fview_entradas_salidasgrid"], function() {
    fview_entradas_salidasgrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_fabricante","forceSelect":false}, ew.vars.tables.view_entradas_salidas.fields.fabricante.autoSuggestOptions));
});
</script>
<?= $Grid->fabricante->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_fabricante") ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_view_entradas_salidas_fabricante" class="form-group view_entradas_salidas_fabricante">
<span<?= $Grid->fabricante->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->fabricante->getDisplayValue($Grid->fabricante->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_fabricante" data-hidden="1" name="x<?= $Grid->RowIndex ?>_fabricante" id="x<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_fabricante" data-hidden="1" name="o<?= $Grid->RowIndex ?>_fabricante" id="o<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->articulo->Visible) { // articulo ?>
        <td data-name="articulo">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_view_entradas_salidas_articulo" class="form-group view_entradas_salidas_articulo">
<?php
$onchange = $Grid->articulo->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Grid->articulo->EditAttrs["onchange"] = "";
?>
<span id="as_x<?= $Grid->RowIndex ?>_articulo" class="ew-auto-suggest">
    <input type="<?= $Grid->articulo->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_articulo" id="sv_x<?= $Grid->RowIndex ?>_articulo" value="<?= RemoveHtml($Grid->articulo->EditValue) ?>" size="30" placeholder="<?= HtmlEncode($Grid->articulo->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->articulo->getPlaceHolder()) ?>"<?= $Grid->articulo->editAttributes() ?>>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="view_entradas_salidas" data-field="x_articulo" data-input="sv_x<?= $Grid->RowIndex ?>_articulo" data-value-separator="<?= $Grid->articulo->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_articulo" id="x<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->CurrentValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Grid->articulo->getErrorMessage() ?></div>
<script>
loadjs.ready(["fview_entradas_salidasgrid"], function() {
    fview_entradas_salidasgrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_articulo","forceSelect":false}, ew.vars.tables.view_entradas_salidas.fields.articulo.autoSuggestOptions));
});
</script>
<?= $Grid->articulo->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_articulo") ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_view_entradas_salidas_articulo" class="form-group view_entradas_salidas_articulo">
<span<?= $Grid->articulo->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->articulo->getDisplayValue($Grid->articulo->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_articulo" data-hidden="1" name="x<?= $Grid->RowIndex ?>_articulo" id="x<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_articulo" data-hidden="1" name="o<?= $Grid->RowIndex ?>_articulo" id="o<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->lote->Visible) { // lote ?>
        <td data-name="lote">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_view_entradas_salidas_lote" class="form-group view_entradas_salidas_lote">
<input type="<?= $Grid->lote->getInputTextType() ?>" data-table="view_entradas_salidas" data-field="x_lote" name="x<?= $Grid->RowIndex ?>_lote" id="x<?= $Grid->RowIndex ?>_lote" size="30" maxlength="20" placeholder="<?= HtmlEncode($Grid->lote->getPlaceHolder()) ?>" value="<?= $Grid->lote->EditValue ?>"<?= $Grid->lote->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->lote->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_view_entradas_salidas_lote" class="form-group view_entradas_salidas_lote">
<span<?= $Grid->lote->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->lote->getDisplayValue($Grid->lote->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_lote" data-hidden="1" name="x<?= $Grid->RowIndex ?>_lote" id="x<?= $Grid->RowIndex ?>_lote" value="<?= HtmlEncode($Grid->lote->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_lote" data-hidden="1" name="o<?= $Grid->RowIndex ?>_lote" id="o<?= $Grid->RowIndex ?>_lote" value="<?= HtmlEncode($Grid->lote->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->fecha_vencimiento->Visible) { // fecha_vencimiento ?>
        <td data-name="fecha_vencimiento">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_view_entradas_salidas_fecha_vencimiento" class="form-group view_entradas_salidas_fecha_vencimiento">
<input type="<?= $Grid->fecha_vencimiento->getInputTextType() ?>" data-table="view_entradas_salidas" data-field="x_fecha_vencimiento" data-format="7" name="x<?= $Grid->RowIndex ?>_fecha_vencimiento" id="x<?= $Grid->RowIndex ?>_fecha_vencimiento" placeholder="<?= HtmlEncode($Grid->fecha_vencimiento->getPlaceHolder()) ?>" value="<?= $Grid->fecha_vencimiento->EditValue ?>"<?= $Grid->fecha_vencimiento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->fecha_vencimiento->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_view_entradas_salidas_fecha_vencimiento" class="form-group view_entradas_salidas_fecha_vencimiento">
<span<?= $Grid->fecha_vencimiento->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->fecha_vencimiento->getDisplayValue($Grid->fecha_vencimiento->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_fecha_vencimiento" data-hidden="1" name="x<?= $Grid->RowIndex ?>_fecha_vencimiento" id="x<?= $Grid->RowIndex ?>_fecha_vencimiento" value="<?= HtmlEncode($Grid->fecha_vencimiento->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_fecha_vencimiento" data-hidden="1" name="o<?= $Grid->RowIndex ?>_fecha_vencimiento" id="o<?= $Grid->RowIndex ?>_fecha_vencimiento" value="<?= HtmlEncode($Grid->fecha_vencimiento->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->cantidad_articulo->Visible) { // cantidad_articulo ?>
        <td data-name="cantidad_articulo">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_view_entradas_salidas_cantidad_articulo" class="form-group view_entradas_salidas_cantidad_articulo">
<input type="<?= $Grid->cantidad_articulo->getInputTextType() ?>" data-table="view_entradas_salidas" data-field="x_cantidad_articulo" name="x<?= $Grid->RowIndex ?>_cantidad_articulo" id="x<?= $Grid->RowIndex ?>_cantidad_articulo" size="30" maxlength="10" placeholder="<?= HtmlEncode($Grid->cantidad_articulo->getPlaceHolder()) ?>" value="<?= $Grid->cantidad_articulo->EditValue ?>"<?= $Grid->cantidad_articulo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->cantidad_articulo->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_view_entradas_salidas_cantidad_articulo" class="form-group view_entradas_salidas_cantidad_articulo">
<span<?= $Grid->cantidad_articulo->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->cantidad_articulo->getDisplayValue($Grid->cantidad_articulo->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_cantidad_articulo" data-hidden="1" name="x<?= $Grid->RowIndex ?>_cantidad_articulo" id="x<?= $Grid->RowIndex ?>_cantidad_articulo" value="<?= HtmlEncode($Grid->cantidad_articulo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_cantidad_articulo" data-hidden="1" name="o<?= $Grid->RowIndex ?>_cantidad_articulo" id="o<?= $Grid->RowIndex ?>_cantidad_articulo" value="<?= HtmlEncode($Grid->cantidad_articulo->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->articulo_unidad_medida->Visible) { // articulo_unidad_medida ?>
        <td data-name="articulo_unidad_medida">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_view_entradas_salidas_articulo_unidad_medida" class="form-group view_entradas_salidas_articulo_unidad_medida">
<?php
$onchange = $Grid->articulo_unidad_medida->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Grid->articulo_unidad_medida->EditAttrs["onchange"] = "";
?>
<span id="as_x<?= $Grid->RowIndex ?>_articulo_unidad_medida" class="ew-auto-suggest">
    <input type="<?= $Grid->articulo_unidad_medida->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_articulo_unidad_medida" id="sv_x<?= $Grid->RowIndex ?>_articulo_unidad_medida" value="<?= RemoveHtml($Grid->articulo_unidad_medida->EditValue) ?>" size="30" maxlength="6" placeholder="<?= HtmlEncode($Grid->articulo_unidad_medida->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->articulo_unidad_medida->getPlaceHolder()) ?>"<?= $Grid->articulo_unidad_medida->editAttributes() ?>>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="view_entradas_salidas" data-field="x_articulo_unidad_medida" data-input="sv_x<?= $Grid->RowIndex ?>_articulo_unidad_medida" data-value-separator="<?= $Grid->articulo_unidad_medida->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_articulo_unidad_medida" id="x<?= $Grid->RowIndex ?>_articulo_unidad_medida" value="<?= HtmlEncode($Grid->articulo_unidad_medida->CurrentValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Grid->articulo_unidad_medida->getErrorMessage() ?></div>
<script>
loadjs.ready(["fview_entradas_salidasgrid"], function() {
    fview_entradas_salidasgrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_articulo_unidad_medida","forceSelect":false}, ew.vars.tables.view_entradas_salidas.fields.articulo_unidad_medida.autoSuggestOptions));
});
</script>
<?= $Grid->articulo_unidad_medida->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_articulo_unidad_medida") ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_view_entradas_salidas_articulo_unidad_medida" class="form-group view_entradas_salidas_articulo_unidad_medida">
<span<?= $Grid->articulo_unidad_medida->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->articulo_unidad_medida->getDisplayValue($Grid->articulo_unidad_medida->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_articulo_unidad_medida" data-hidden="1" name="x<?= $Grid->RowIndex ?>_articulo_unidad_medida" id="x<?= $Grid->RowIndex ?>_articulo_unidad_medida" value="<?= HtmlEncode($Grid->articulo_unidad_medida->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="view_entradas_salidas" data-field="x_articulo_unidad_medida" data-hidden="1" name="o<?= $Grid->RowIndex ?>_articulo_unidad_medida" id="o<?= $Grid->RowIndex ?>_articulo_unidad_medida" value="<?= HtmlEncode($Grid->articulo_unidad_medida->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["fview_entradas_salidasgrid","load"], function() {
    fview_entradas_salidasgrid.updateLists(<?= $Grid->RowIndex ?>);
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
<input type="hidden" name="detailpage" value="fview_entradas_salidasgrid">
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
    ew.addEventHandlers("view_entradas_salidas");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
