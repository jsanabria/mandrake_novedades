<?php

namespace PHPMaker2021\mandrake;

// Set up and run Grid object
$Grid = Container("EntradasSalidasGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fentradas_salidasgrid;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    fentradas_salidasgrid = new ew.Form("fentradas_salidasgrid", "grid");
    fentradas_salidasgrid.formKeyCountName = '<?= $Grid->FormKeyCountName ?>';

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "entradas_salidas")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.entradas_salidas)
        ew.vars.tables.entradas_salidas = currentTable;
    fentradas_salidasgrid.addFields([
        ["articulo", [fields.articulo.visible && fields.articulo.required ? ew.Validators.required(fields.articulo.caption) : null, ew.Validators.integer], fields.articulo.isInvalid],
        ["cantidad_articulo", [fields.cantidad_articulo.visible && fields.cantidad_articulo.required ? ew.Validators.required(fields.cantidad_articulo.caption) : null, ew.Validators.float], fields.cantidad_articulo.isInvalid],
        ["precio_unidad_sin_desc", [fields.precio_unidad_sin_desc.visible && fields.precio_unidad_sin_desc.required ? ew.Validators.required(fields.precio_unidad_sin_desc.caption) : null, ew.Validators.float], fields.precio_unidad_sin_desc.isInvalid],
        ["descuento", [fields.descuento.visible && fields.descuento.required ? ew.Validators.required(fields.descuento.caption) : null, ew.Validators.float], fields.descuento.isInvalid],
        ["costo_unidad", [fields.costo_unidad.visible && fields.costo_unidad.required ? ew.Validators.required(fields.costo_unidad.caption) : null, ew.Validators.float], fields.costo_unidad.isInvalid],
        ["costo", [fields.costo.visible && fields.costo.required ? ew.Validators.required(fields.costo.caption) : null, ew.Validators.float], fields.costo.isInvalid],
        ["precio_unidad", [fields.precio_unidad.visible && fields.precio_unidad.required ? ew.Validators.required(fields.precio_unidad.caption) : null, ew.Validators.float], fields.precio_unidad.isInvalid],
        ["precio", [fields.precio.visible && fields.precio.required ? ew.Validators.required(fields.precio.caption) : null, ew.Validators.float], fields.precio.isInvalid],
        ["lote", [fields.lote.visible && fields.lote.required ? ew.Validators.required(fields.lote.caption) : null], fields.lote.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fentradas_salidasgrid,
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
    fentradas_salidasgrid.validate = function () {
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
    fentradas_salidasgrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "articulo", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "cantidad_articulo", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "precio_unidad_sin_desc", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "descuento", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "costo_unidad", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "costo", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "precio_unidad", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "precio", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "lote", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    fentradas_salidasgrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fentradas_salidasgrid.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fentradas_salidasgrid.lists.articulo = <?= $Grid->articulo->toClientList($Grid) ?>;
    loadjs.done("fentradas_salidasgrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> entradas_salidas">
<div id="fentradas_salidasgrid" class="ew-form ew-list-form form-inline">
<div id="gmp_entradas_salidas" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_entradas_salidasgrid" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Grid->articulo->Visible) { // articulo ?>
        <th data-name="articulo" class="<?= $Grid->articulo->headerCellClass() ?>"><div id="elh_entradas_salidas_articulo" class="entradas_salidas_articulo"><?= $Grid->renderSort($Grid->articulo) ?></div></th>
<?php } ?>
<?php if ($Grid->cantidad_articulo->Visible) { // cantidad_articulo ?>
        <th data-name="cantidad_articulo" class="<?= $Grid->cantidad_articulo->headerCellClass() ?>"><div id="elh_entradas_salidas_cantidad_articulo" class="entradas_salidas_cantidad_articulo"><?= $Grid->renderSort($Grid->cantidad_articulo) ?></div></th>
<?php } ?>
<?php if ($Grid->precio_unidad_sin_desc->Visible) { // precio_unidad_sin_desc ?>
        <th data-name="precio_unidad_sin_desc" class="<?= $Grid->precio_unidad_sin_desc->headerCellClass() ?>"><div id="elh_entradas_salidas_precio_unidad_sin_desc" class="entradas_salidas_precio_unidad_sin_desc"><?= $Grid->renderSort($Grid->precio_unidad_sin_desc) ?></div></th>
<?php } ?>
<?php if ($Grid->descuento->Visible) { // descuento ?>
        <th data-name="descuento" class="<?= $Grid->descuento->headerCellClass() ?>"><div id="elh_entradas_salidas_descuento" class="entradas_salidas_descuento"><?= $Grid->renderSort($Grid->descuento) ?></div></th>
<?php } ?>
<?php if ($Grid->costo_unidad->Visible) { // costo_unidad ?>
        <th data-name="costo_unidad" class="<?= $Grid->costo_unidad->headerCellClass() ?>"><div id="elh_entradas_salidas_costo_unidad" class="entradas_salidas_costo_unidad"><?= $Grid->renderSort($Grid->costo_unidad) ?></div></th>
<?php } ?>
<?php if ($Grid->costo->Visible) { // costo ?>
        <th data-name="costo" class="<?= $Grid->costo->headerCellClass() ?>"><div id="elh_entradas_salidas_costo" class="entradas_salidas_costo"><?= $Grid->renderSort($Grid->costo) ?></div></th>
<?php } ?>
<?php if ($Grid->precio_unidad->Visible) { // precio_unidad ?>
        <th data-name="precio_unidad" class="<?= $Grid->precio_unidad->headerCellClass() ?>"><div id="elh_entradas_salidas_precio_unidad" class="entradas_salidas_precio_unidad"><?= $Grid->renderSort($Grid->precio_unidad) ?></div></th>
<?php } ?>
<?php if ($Grid->precio->Visible) { // precio ?>
        <th data-name="precio" class="<?= $Grid->precio->headerCellClass() ?>"><div id="elh_entradas_salidas_precio" class="entradas_salidas_precio"><?= $Grid->renderSort($Grid->precio) ?></div></th>
<?php } ?>
<?php if ($Grid->lote->Visible) { // lote ?>
        <th data-name="lote" class="<?= $Grid->lote->headerCellClass() ?>"><div id="elh_entradas_salidas_lote" class="entradas_salidas_lote"><?= $Grid->renderSort($Grid->lote) ?></div></th>
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowCount, "id" => "r" . $Grid->RowCount . "_entradas_salidas", "data-rowtype" => $Grid->RowType]);

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
    <?php if ($Grid->articulo->Visible) { // articulo ?>
        <td data-name="articulo" <?= $Grid->articulo->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_entradas_salidas_articulo" class="form-group">
<?php
$onchange = $Grid->articulo->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Grid->articulo->EditAttrs["onchange"] = "";
?>
<span id="as_x<?= $Grid->RowIndex ?>_articulo" class="ew-auto-suggest">
    <div class="input-group flex-nowrap">
        <input type="<?= $Grid->articulo->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_articulo" id="sv_x<?= $Grid->RowIndex ?>_articulo" value="<?= RemoveHtml($Grid->articulo->EditValue) ?>" size="30" placeholder="<?= HtmlEncode($Grid->articulo->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->articulo->getPlaceHolder()) ?>"<?= $Grid->articulo->editAttributes() ?>>
        <div class="input-group-append">
            <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Grid->articulo->caption()), $Language->phrase("LookupLink", true))) ?>" onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_articulo',m:0,n:10,srch:false});" class="ew-lookup-btn btn btn-default"<?= ($Grid->articulo->ReadOnly || $Grid->articulo->Disabled) ? " disabled" : "" ?>><i class="fas fa-search ew-icon"></i></button>
        </div>
    </div>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="entradas_salidas" data-field="x_articulo" data-input="sv_x<?= $Grid->RowIndex ?>_articulo" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Grid->articulo->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_articulo" id="x<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->CurrentValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Grid->articulo->getErrorMessage() ?></div>
<script>
loadjs.ready(["fentradas_salidasgrid"], function() {
    fentradas_salidasgrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_articulo","forceSelect":true}, ew.vars.tables.entradas_salidas.fields.articulo.autoSuggestOptions));
});
</script>
<?= $Grid->articulo->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_articulo") ?>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_articulo" data-hidden="1" name="o<?= $Grid->RowIndex ?>_articulo" id="o<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_entradas_salidas_articulo" class="form-group">
<?php
$onchange = $Grid->articulo->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Grid->articulo->EditAttrs["onchange"] = "";
?>
<span id="as_x<?= $Grid->RowIndex ?>_articulo" class="ew-auto-suggest">
    <div class="input-group flex-nowrap">
        <input type="<?= $Grid->articulo->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_articulo" id="sv_x<?= $Grid->RowIndex ?>_articulo" value="<?= RemoveHtml($Grid->articulo->EditValue) ?>" size="30" placeholder="<?= HtmlEncode($Grid->articulo->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->articulo->getPlaceHolder()) ?>"<?= $Grid->articulo->editAttributes() ?>>
        <div class="input-group-append">
            <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Grid->articulo->caption()), $Language->phrase("LookupLink", true))) ?>" onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_articulo',m:0,n:10,srch:false});" class="ew-lookup-btn btn btn-default"<?= ($Grid->articulo->ReadOnly || $Grid->articulo->Disabled) ? " disabled" : "" ?>><i class="fas fa-search ew-icon"></i></button>
        </div>
    </div>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="entradas_salidas" data-field="x_articulo" data-input="sv_x<?= $Grid->RowIndex ?>_articulo" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Grid->articulo->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_articulo" id="x<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->CurrentValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Grid->articulo->getErrorMessage() ?></div>
<script>
loadjs.ready(["fentradas_salidasgrid"], function() {
    fentradas_salidasgrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_articulo","forceSelect":true}, ew.vars.tables.entradas_salidas.fields.articulo.autoSuggestOptions));
});
</script>
<?= $Grid->articulo->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_articulo") ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_entradas_salidas_articulo">
<span<?= $Grid->articulo->viewAttributes() ?>>
<?= $Grid->articulo->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="entradas_salidas" data-field="x_articulo" data-hidden="1" name="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_articulo" id="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->FormValue) ?>">
<input type="hidden" data-table="entradas_salidas" data-field="x_articulo" data-hidden="1" name="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_articulo" id="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->cantidad_articulo->Visible) { // cantidad_articulo ?>
        <td data-name="cantidad_articulo" <?= $Grid->cantidad_articulo->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_entradas_salidas_cantidad_articulo" class="form-group">
<input type="<?= $Grid->cantidad_articulo->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_cantidad_articulo" name="x<?= $Grid->RowIndex ?>_cantidad_articulo" id="x<?= $Grid->RowIndex ?>_cantidad_articulo" size="6" maxlength="10" placeholder="<?= HtmlEncode($Grid->cantidad_articulo->getPlaceHolder()) ?>" value="<?= $Grid->cantidad_articulo->EditValue ?>"<?= $Grid->cantidad_articulo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->cantidad_articulo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_cantidad_articulo" data-hidden="1" name="o<?= $Grid->RowIndex ?>_cantidad_articulo" id="o<?= $Grid->RowIndex ?>_cantidad_articulo" value="<?= HtmlEncode($Grid->cantidad_articulo->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_entradas_salidas_cantidad_articulo" class="form-group">
<input type="<?= $Grid->cantidad_articulo->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_cantidad_articulo" name="x<?= $Grid->RowIndex ?>_cantidad_articulo" id="x<?= $Grid->RowIndex ?>_cantidad_articulo" size="6" maxlength="10" placeholder="<?= HtmlEncode($Grid->cantidad_articulo->getPlaceHolder()) ?>" value="<?= $Grid->cantidad_articulo->EditValue ?>"<?= $Grid->cantidad_articulo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->cantidad_articulo->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_entradas_salidas_cantidad_articulo">
<span<?= $Grid->cantidad_articulo->viewAttributes() ?>>
<?= $Grid->cantidad_articulo->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="entradas_salidas" data-field="x_cantidad_articulo" data-hidden="1" name="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_cantidad_articulo" id="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_cantidad_articulo" value="<?= HtmlEncode($Grid->cantidad_articulo->FormValue) ?>">
<input type="hidden" data-table="entradas_salidas" data-field="x_cantidad_articulo" data-hidden="1" name="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_cantidad_articulo" id="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_cantidad_articulo" value="<?= HtmlEncode($Grid->cantidad_articulo->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->precio_unidad_sin_desc->Visible) { // precio_unidad_sin_desc ?>
        <td data-name="precio_unidad_sin_desc" <?= $Grid->precio_unidad_sin_desc->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_entradas_salidas_precio_unidad_sin_desc" class="form-group">
<input type="<?= $Grid->precio_unidad_sin_desc->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_precio_unidad_sin_desc" name="x<?= $Grid->RowIndex ?>_precio_unidad_sin_desc" id="x<?= $Grid->RowIndex ?>_precio_unidad_sin_desc" size="10" maxlength="14" placeholder="<?= HtmlEncode($Grid->precio_unidad_sin_desc->getPlaceHolder()) ?>" value="<?= $Grid->precio_unidad_sin_desc->EditValue ?>"<?= $Grid->precio_unidad_sin_desc->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->precio_unidad_sin_desc->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_precio_unidad_sin_desc" data-hidden="1" name="o<?= $Grid->RowIndex ?>_precio_unidad_sin_desc" id="o<?= $Grid->RowIndex ?>_precio_unidad_sin_desc" value="<?= HtmlEncode($Grid->precio_unidad_sin_desc->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_entradas_salidas_precio_unidad_sin_desc" class="form-group">
<input type="<?= $Grid->precio_unidad_sin_desc->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_precio_unidad_sin_desc" name="x<?= $Grid->RowIndex ?>_precio_unidad_sin_desc" id="x<?= $Grid->RowIndex ?>_precio_unidad_sin_desc" size="10" maxlength="14" placeholder="<?= HtmlEncode($Grid->precio_unidad_sin_desc->getPlaceHolder()) ?>" value="<?= $Grid->precio_unidad_sin_desc->EditValue ?>"<?= $Grid->precio_unidad_sin_desc->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->precio_unidad_sin_desc->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_entradas_salidas_precio_unidad_sin_desc">
<span<?= $Grid->precio_unidad_sin_desc->viewAttributes() ?>>
<?= $Grid->precio_unidad_sin_desc->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="entradas_salidas" data-field="x_precio_unidad_sin_desc" data-hidden="1" name="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_precio_unidad_sin_desc" id="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_precio_unidad_sin_desc" value="<?= HtmlEncode($Grid->precio_unidad_sin_desc->FormValue) ?>">
<input type="hidden" data-table="entradas_salidas" data-field="x_precio_unidad_sin_desc" data-hidden="1" name="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_precio_unidad_sin_desc" id="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_precio_unidad_sin_desc" value="<?= HtmlEncode($Grid->precio_unidad_sin_desc->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->descuento->Visible) { // descuento ?>
        <td data-name="descuento" <?= $Grid->descuento->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_entradas_salidas_descuento" class="form-group">
<input type="<?= $Grid->descuento->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_descuento" name="x<?= $Grid->RowIndex ?>_descuento" id="x<?= $Grid->RowIndex ?>_descuento" size="6" maxlength="6" placeholder="<?= HtmlEncode($Grid->descuento->getPlaceHolder()) ?>" value="<?= $Grid->descuento->EditValue ?>"<?= $Grid->descuento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->descuento->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_descuento" data-hidden="1" name="o<?= $Grid->RowIndex ?>_descuento" id="o<?= $Grid->RowIndex ?>_descuento" value="<?= HtmlEncode($Grid->descuento->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_entradas_salidas_descuento" class="form-group">
<input type="<?= $Grid->descuento->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_descuento" name="x<?= $Grid->RowIndex ?>_descuento" id="x<?= $Grid->RowIndex ?>_descuento" size="6" maxlength="6" placeholder="<?= HtmlEncode($Grid->descuento->getPlaceHolder()) ?>" value="<?= $Grid->descuento->EditValue ?>"<?= $Grid->descuento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->descuento->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_entradas_salidas_descuento">
<span<?= $Grid->descuento->viewAttributes() ?>>
<?= $Grid->descuento->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="entradas_salidas" data-field="x_descuento" data-hidden="1" name="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_descuento" id="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_descuento" value="<?= HtmlEncode($Grid->descuento->FormValue) ?>">
<input type="hidden" data-table="entradas_salidas" data-field="x_descuento" data-hidden="1" name="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_descuento" id="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_descuento" value="<?= HtmlEncode($Grid->descuento->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->costo_unidad->Visible) { // costo_unidad ?>
        <td data-name="costo_unidad" <?= $Grid->costo_unidad->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_entradas_salidas_costo_unidad" class="form-group">
<input type="<?= $Grid->costo_unidad->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_costo_unidad" name="x<?= $Grid->RowIndex ?>_costo_unidad" id="x<?= $Grid->RowIndex ?>_costo_unidad" size="10" maxlength="14" placeholder="<?= HtmlEncode($Grid->costo_unidad->getPlaceHolder()) ?>" value="<?= $Grid->costo_unidad->EditValue ?>"<?= $Grid->costo_unidad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->costo_unidad->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_costo_unidad" data-hidden="1" name="o<?= $Grid->RowIndex ?>_costo_unidad" id="o<?= $Grid->RowIndex ?>_costo_unidad" value="<?= HtmlEncode($Grid->costo_unidad->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_entradas_salidas_costo_unidad" class="form-group">
<input type="<?= $Grid->costo_unidad->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_costo_unidad" name="x<?= $Grid->RowIndex ?>_costo_unidad" id="x<?= $Grid->RowIndex ?>_costo_unidad" size="10" maxlength="14" placeholder="<?= HtmlEncode($Grid->costo_unidad->getPlaceHolder()) ?>" value="<?= $Grid->costo_unidad->EditValue ?>"<?= $Grid->costo_unidad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->costo_unidad->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_entradas_salidas_costo_unidad">
<span<?= $Grid->costo_unidad->viewAttributes() ?>>
<?= $Grid->costo_unidad->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="entradas_salidas" data-field="x_costo_unidad" data-hidden="1" name="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_costo_unidad" id="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_costo_unidad" value="<?= HtmlEncode($Grid->costo_unidad->FormValue) ?>">
<input type="hidden" data-table="entradas_salidas" data-field="x_costo_unidad" data-hidden="1" name="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_costo_unidad" id="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_costo_unidad" value="<?= HtmlEncode($Grid->costo_unidad->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->costo->Visible) { // costo ?>
        <td data-name="costo" <?= $Grid->costo->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_entradas_salidas_costo" class="form-group">
<input type="<?= $Grid->costo->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_costo" name="x<?= $Grid->RowIndex ?>_costo" id="x<?= $Grid->RowIndex ?>_costo" size="10" maxlength="14" placeholder="<?= HtmlEncode($Grid->costo->getPlaceHolder()) ?>" value="<?= $Grid->costo->EditValue ?>"<?= $Grid->costo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->costo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_costo" data-hidden="1" name="o<?= $Grid->RowIndex ?>_costo" id="o<?= $Grid->RowIndex ?>_costo" value="<?= HtmlEncode($Grid->costo->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_entradas_salidas_costo" class="form-group">
<input type="<?= $Grid->costo->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_costo" name="x<?= $Grid->RowIndex ?>_costo" id="x<?= $Grid->RowIndex ?>_costo" size="10" maxlength="14" placeholder="<?= HtmlEncode($Grid->costo->getPlaceHolder()) ?>" value="<?= $Grid->costo->EditValue ?>"<?= $Grid->costo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->costo->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_entradas_salidas_costo">
<span<?= $Grid->costo->viewAttributes() ?>>
<?= $Grid->costo->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="entradas_salidas" data-field="x_costo" data-hidden="1" name="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_costo" id="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_costo" value="<?= HtmlEncode($Grid->costo->FormValue) ?>">
<input type="hidden" data-table="entradas_salidas" data-field="x_costo" data-hidden="1" name="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_costo" id="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_costo" value="<?= HtmlEncode($Grid->costo->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->precio_unidad->Visible) { // precio_unidad ?>
        <td data-name="precio_unidad" <?= $Grid->precio_unidad->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_entradas_salidas_precio_unidad" class="form-group">
<input type="<?= $Grid->precio_unidad->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_precio_unidad" name="x<?= $Grid->RowIndex ?>_precio_unidad" id="x<?= $Grid->RowIndex ?>_precio_unidad" size="6" maxlength="14" placeholder="<?= HtmlEncode($Grid->precio_unidad->getPlaceHolder()) ?>" value="<?= $Grid->precio_unidad->EditValue ?>"<?= $Grid->precio_unidad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->precio_unidad->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_precio_unidad" data-hidden="1" name="o<?= $Grid->RowIndex ?>_precio_unidad" id="o<?= $Grid->RowIndex ?>_precio_unidad" value="<?= HtmlEncode($Grid->precio_unidad->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_entradas_salidas_precio_unidad" class="form-group">
<input type="<?= $Grid->precio_unidad->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_precio_unidad" name="x<?= $Grid->RowIndex ?>_precio_unidad" id="x<?= $Grid->RowIndex ?>_precio_unidad" size="6" maxlength="14" placeholder="<?= HtmlEncode($Grid->precio_unidad->getPlaceHolder()) ?>" value="<?= $Grid->precio_unidad->EditValue ?>"<?= $Grid->precio_unidad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->precio_unidad->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_entradas_salidas_precio_unidad">
<span<?= $Grid->precio_unidad->viewAttributes() ?>>
<?= $Grid->precio_unidad->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="entradas_salidas" data-field="x_precio_unidad" data-hidden="1" name="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_precio_unidad" id="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_precio_unidad" value="<?= HtmlEncode($Grid->precio_unidad->FormValue) ?>">
<input type="hidden" data-table="entradas_salidas" data-field="x_precio_unidad" data-hidden="1" name="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_precio_unidad" id="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_precio_unidad" value="<?= HtmlEncode($Grid->precio_unidad->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->precio->Visible) { // precio ?>
        <td data-name="precio" <?= $Grid->precio->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_entradas_salidas_precio" class="form-group">
<input type="<?= $Grid->precio->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_precio" name="x<?= $Grid->RowIndex ?>_precio" id="x<?= $Grid->RowIndex ?>_precio" size="10" maxlength="14" placeholder="<?= HtmlEncode($Grid->precio->getPlaceHolder()) ?>" value="<?= $Grid->precio->EditValue ?>"<?= $Grid->precio->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->precio->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_precio" data-hidden="1" name="o<?= $Grid->RowIndex ?>_precio" id="o<?= $Grid->RowIndex ?>_precio" value="<?= HtmlEncode($Grid->precio->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_entradas_salidas_precio" class="form-group">
<input type="<?= $Grid->precio->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_precio" name="x<?= $Grid->RowIndex ?>_precio" id="x<?= $Grid->RowIndex ?>_precio" size="10" maxlength="14" placeholder="<?= HtmlEncode($Grid->precio->getPlaceHolder()) ?>" value="<?= $Grid->precio->EditValue ?>"<?= $Grid->precio->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->precio->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_entradas_salidas_precio">
<span<?= $Grid->precio->viewAttributes() ?>>
<?= $Grid->precio->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="entradas_salidas" data-field="x_precio" data-hidden="1" name="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_precio" id="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_precio" value="<?= HtmlEncode($Grid->precio->FormValue) ?>">
<input type="hidden" data-table="entradas_salidas" data-field="x_precio" data-hidden="1" name="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_precio" id="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_precio" value="<?= HtmlEncode($Grid->precio->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->lote->Visible) { // lote ?>
        <td data-name="lote" <?= $Grid->lote->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_entradas_salidas_lote" class="form-group">
<input type="<?= $Grid->lote->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_lote" name="x<?= $Grid->RowIndex ?>_lote" id="x<?= $Grid->RowIndex ?>_lote" size="6" maxlength="20" placeholder="<?= HtmlEncode($Grid->lote->getPlaceHolder()) ?>" value="<?= $Grid->lote->EditValue ?>"<?= $Grid->lote->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->lote->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_lote" data-hidden="1" name="o<?= $Grid->RowIndex ?>_lote" id="o<?= $Grid->RowIndex ?>_lote" value="<?= HtmlEncode($Grid->lote->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_entradas_salidas_lote" class="form-group">
<input type="<?= $Grid->lote->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_lote" name="x<?= $Grid->RowIndex ?>_lote" id="x<?= $Grid->RowIndex ?>_lote" size="6" maxlength="20" placeholder="<?= HtmlEncode($Grid->lote->getPlaceHolder()) ?>" value="<?= $Grid->lote->EditValue ?>"<?= $Grid->lote->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->lote->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_entradas_salidas_lote">
<span<?= $Grid->lote->viewAttributes() ?>>
<?= $Grid->lote->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="entradas_salidas" data-field="x_lote" data-hidden="1" name="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_lote" id="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_lote" value="<?= HtmlEncode($Grid->lote->FormValue) ?>">
<input type="hidden" data-table="entradas_salidas" data-field="x_lote" data-hidden="1" name="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_lote" id="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_lote" value="<?= HtmlEncode($Grid->lote->OldValue) ?>">
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
loadjs.ready(["fentradas_salidasgrid","load"], function () {
    fentradas_salidasgrid.updateLists(<?= $Grid->RowIndex ?>);
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_entradas_salidas", "data-rowtype" => ROWTYPE_ADD]);
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
    <?php if ($Grid->articulo->Visible) { // articulo ?>
        <td data-name="articulo">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_entradas_salidas_articulo" class="form-group entradas_salidas_articulo">
<?php
$onchange = $Grid->articulo->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Grid->articulo->EditAttrs["onchange"] = "";
?>
<span id="as_x<?= $Grid->RowIndex ?>_articulo" class="ew-auto-suggest">
    <div class="input-group flex-nowrap">
        <input type="<?= $Grid->articulo->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_articulo" id="sv_x<?= $Grid->RowIndex ?>_articulo" value="<?= RemoveHtml($Grid->articulo->EditValue) ?>" size="30" placeholder="<?= HtmlEncode($Grid->articulo->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->articulo->getPlaceHolder()) ?>"<?= $Grid->articulo->editAttributes() ?>>
        <div class="input-group-append">
            <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Grid->articulo->caption()), $Language->phrase("LookupLink", true))) ?>" onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_articulo',m:0,n:10,srch:false});" class="ew-lookup-btn btn btn-default"<?= ($Grid->articulo->ReadOnly || $Grid->articulo->Disabled) ? " disabled" : "" ?>><i class="fas fa-search ew-icon"></i></button>
        </div>
    </div>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="entradas_salidas" data-field="x_articulo" data-input="sv_x<?= $Grid->RowIndex ?>_articulo" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Grid->articulo->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_articulo" id="x<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->CurrentValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Grid->articulo->getErrorMessage() ?></div>
<script>
loadjs.ready(["fentradas_salidasgrid"], function() {
    fentradas_salidasgrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_articulo","forceSelect":true}, ew.vars.tables.entradas_salidas.fields.articulo.autoSuggestOptions));
});
</script>
<?= $Grid->articulo->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_articulo") ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_entradas_salidas_articulo" class="form-group entradas_salidas_articulo">
<span<?= $Grid->articulo->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->articulo->getDisplayValue($Grid->articulo->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_articulo" data-hidden="1" name="x<?= $Grid->RowIndex ?>_articulo" id="x<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="entradas_salidas" data-field="x_articulo" data-hidden="1" name="o<?= $Grid->RowIndex ?>_articulo" id="o<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->cantidad_articulo->Visible) { // cantidad_articulo ?>
        <td data-name="cantidad_articulo">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_entradas_salidas_cantidad_articulo" class="form-group entradas_salidas_cantidad_articulo">
<input type="<?= $Grid->cantidad_articulo->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_cantidad_articulo" name="x<?= $Grid->RowIndex ?>_cantidad_articulo" id="x<?= $Grid->RowIndex ?>_cantidad_articulo" size="6" maxlength="10" placeholder="<?= HtmlEncode($Grid->cantidad_articulo->getPlaceHolder()) ?>" value="<?= $Grid->cantidad_articulo->EditValue ?>"<?= $Grid->cantidad_articulo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->cantidad_articulo->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_entradas_salidas_cantidad_articulo" class="form-group entradas_salidas_cantidad_articulo">
<span<?= $Grid->cantidad_articulo->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->cantidad_articulo->getDisplayValue($Grid->cantidad_articulo->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_cantidad_articulo" data-hidden="1" name="x<?= $Grid->RowIndex ?>_cantidad_articulo" id="x<?= $Grid->RowIndex ?>_cantidad_articulo" value="<?= HtmlEncode($Grid->cantidad_articulo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="entradas_salidas" data-field="x_cantidad_articulo" data-hidden="1" name="o<?= $Grid->RowIndex ?>_cantidad_articulo" id="o<?= $Grid->RowIndex ?>_cantidad_articulo" value="<?= HtmlEncode($Grid->cantidad_articulo->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->precio_unidad_sin_desc->Visible) { // precio_unidad_sin_desc ?>
        <td data-name="precio_unidad_sin_desc">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_entradas_salidas_precio_unidad_sin_desc" class="form-group entradas_salidas_precio_unidad_sin_desc">
<input type="<?= $Grid->precio_unidad_sin_desc->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_precio_unidad_sin_desc" name="x<?= $Grid->RowIndex ?>_precio_unidad_sin_desc" id="x<?= $Grid->RowIndex ?>_precio_unidad_sin_desc" size="10" maxlength="14" placeholder="<?= HtmlEncode($Grid->precio_unidad_sin_desc->getPlaceHolder()) ?>" value="<?= $Grid->precio_unidad_sin_desc->EditValue ?>"<?= $Grid->precio_unidad_sin_desc->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->precio_unidad_sin_desc->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_entradas_salidas_precio_unidad_sin_desc" class="form-group entradas_salidas_precio_unidad_sin_desc">
<span<?= $Grid->precio_unidad_sin_desc->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->precio_unidad_sin_desc->getDisplayValue($Grid->precio_unidad_sin_desc->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_precio_unidad_sin_desc" data-hidden="1" name="x<?= $Grid->RowIndex ?>_precio_unidad_sin_desc" id="x<?= $Grid->RowIndex ?>_precio_unidad_sin_desc" value="<?= HtmlEncode($Grid->precio_unidad_sin_desc->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="entradas_salidas" data-field="x_precio_unidad_sin_desc" data-hidden="1" name="o<?= $Grid->RowIndex ?>_precio_unidad_sin_desc" id="o<?= $Grid->RowIndex ?>_precio_unidad_sin_desc" value="<?= HtmlEncode($Grid->precio_unidad_sin_desc->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->descuento->Visible) { // descuento ?>
        <td data-name="descuento">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_entradas_salidas_descuento" class="form-group entradas_salidas_descuento">
<input type="<?= $Grid->descuento->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_descuento" name="x<?= $Grid->RowIndex ?>_descuento" id="x<?= $Grid->RowIndex ?>_descuento" size="6" maxlength="6" placeholder="<?= HtmlEncode($Grid->descuento->getPlaceHolder()) ?>" value="<?= $Grid->descuento->EditValue ?>"<?= $Grid->descuento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->descuento->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_entradas_salidas_descuento" class="form-group entradas_salidas_descuento">
<span<?= $Grid->descuento->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->descuento->getDisplayValue($Grid->descuento->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_descuento" data-hidden="1" name="x<?= $Grid->RowIndex ?>_descuento" id="x<?= $Grid->RowIndex ?>_descuento" value="<?= HtmlEncode($Grid->descuento->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="entradas_salidas" data-field="x_descuento" data-hidden="1" name="o<?= $Grid->RowIndex ?>_descuento" id="o<?= $Grid->RowIndex ?>_descuento" value="<?= HtmlEncode($Grid->descuento->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->costo_unidad->Visible) { // costo_unidad ?>
        <td data-name="costo_unidad">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_entradas_salidas_costo_unidad" class="form-group entradas_salidas_costo_unidad">
<input type="<?= $Grid->costo_unidad->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_costo_unidad" name="x<?= $Grid->RowIndex ?>_costo_unidad" id="x<?= $Grid->RowIndex ?>_costo_unidad" size="10" maxlength="14" placeholder="<?= HtmlEncode($Grid->costo_unidad->getPlaceHolder()) ?>" value="<?= $Grid->costo_unidad->EditValue ?>"<?= $Grid->costo_unidad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->costo_unidad->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_entradas_salidas_costo_unidad" class="form-group entradas_salidas_costo_unidad">
<span<?= $Grid->costo_unidad->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->costo_unidad->getDisplayValue($Grid->costo_unidad->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_costo_unidad" data-hidden="1" name="x<?= $Grid->RowIndex ?>_costo_unidad" id="x<?= $Grid->RowIndex ?>_costo_unidad" value="<?= HtmlEncode($Grid->costo_unidad->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="entradas_salidas" data-field="x_costo_unidad" data-hidden="1" name="o<?= $Grid->RowIndex ?>_costo_unidad" id="o<?= $Grid->RowIndex ?>_costo_unidad" value="<?= HtmlEncode($Grid->costo_unidad->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->costo->Visible) { // costo ?>
        <td data-name="costo">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_entradas_salidas_costo" class="form-group entradas_salidas_costo">
<input type="<?= $Grid->costo->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_costo" name="x<?= $Grid->RowIndex ?>_costo" id="x<?= $Grid->RowIndex ?>_costo" size="10" maxlength="14" placeholder="<?= HtmlEncode($Grid->costo->getPlaceHolder()) ?>" value="<?= $Grid->costo->EditValue ?>"<?= $Grid->costo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->costo->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_entradas_salidas_costo" class="form-group entradas_salidas_costo">
<span<?= $Grid->costo->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->costo->getDisplayValue($Grid->costo->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_costo" data-hidden="1" name="x<?= $Grid->RowIndex ?>_costo" id="x<?= $Grid->RowIndex ?>_costo" value="<?= HtmlEncode($Grid->costo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="entradas_salidas" data-field="x_costo" data-hidden="1" name="o<?= $Grid->RowIndex ?>_costo" id="o<?= $Grid->RowIndex ?>_costo" value="<?= HtmlEncode($Grid->costo->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->precio_unidad->Visible) { // precio_unidad ?>
        <td data-name="precio_unidad">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_entradas_salidas_precio_unidad" class="form-group entradas_salidas_precio_unidad">
<input type="<?= $Grid->precio_unidad->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_precio_unidad" name="x<?= $Grid->RowIndex ?>_precio_unidad" id="x<?= $Grid->RowIndex ?>_precio_unidad" size="6" maxlength="14" placeholder="<?= HtmlEncode($Grid->precio_unidad->getPlaceHolder()) ?>" value="<?= $Grid->precio_unidad->EditValue ?>"<?= $Grid->precio_unidad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->precio_unidad->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_entradas_salidas_precio_unidad" class="form-group entradas_salidas_precio_unidad">
<span<?= $Grid->precio_unidad->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->precio_unidad->getDisplayValue($Grid->precio_unidad->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_precio_unidad" data-hidden="1" name="x<?= $Grid->RowIndex ?>_precio_unidad" id="x<?= $Grid->RowIndex ?>_precio_unidad" value="<?= HtmlEncode($Grid->precio_unidad->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="entradas_salidas" data-field="x_precio_unidad" data-hidden="1" name="o<?= $Grid->RowIndex ?>_precio_unidad" id="o<?= $Grid->RowIndex ?>_precio_unidad" value="<?= HtmlEncode($Grid->precio_unidad->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->precio->Visible) { // precio ?>
        <td data-name="precio">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_entradas_salidas_precio" class="form-group entradas_salidas_precio">
<input type="<?= $Grid->precio->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_precio" name="x<?= $Grid->RowIndex ?>_precio" id="x<?= $Grid->RowIndex ?>_precio" size="10" maxlength="14" placeholder="<?= HtmlEncode($Grid->precio->getPlaceHolder()) ?>" value="<?= $Grid->precio->EditValue ?>"<?= $Grid->precio->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->precio->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_entradas_salidas_precio" class="form-group entradas_salidas_precio">
<span<?= $Grid->precio->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->precio->getDisplayValue($Grid->precio->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_precio" data-hidden="1" name="x<?= $Grid->RowIndex ?>_precio" id="x<?= $Grid->RowIndex ?>_precio" value="<?= HtmlEncode($Grid->precio->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="entradas_salidas" data-field="x_precio" data-hidden="1" name="o<?= $Grid->RowIndex ?>_precio" id="o<?= $Grid->RowIndex ?>_precio" value="<?= HtmlEncode($Grid->precio->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->lote->Visible) { // lote ?>
        <td data-name="lote">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_entradas_salidas_lote" class="form-group entradas_salidas_lote">
<input type="<?= $Grid->lote->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_lote" name="x<?= $Grid->RowIndex ?>_lote" id="x<?= $Grid->RowIndex ?>_lote" size="6" maxlength="20" placeholder="<?= HtmlEncode($Grid->lote->getPlaceHolder()) ?>" value="<?= $Grid->lote->EditValue ?>"<?= $Grid->lote->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->lote->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_entradas_salidas_lote" class="form-group entradas_salidas_lote">
<span<?= $Grid->lote->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->lote->getDisplayValue($Grid->lote->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_lote" data-hidden="1" name="x<?= $Grid->RowIndex ?>_lote" id="x<?= $Grid->RowIndex ?>_lote" value="<?= HtmlEncode($Grid->lote->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="entradas_salidas" data-field="x_lote" data-hidden="1" name="o<?= $Grid->RowIndex ?>_lote" id="o<?= $Grid->RowIndex ?>_lote" value="<?= HtmlEncode($Grid->lote->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["fentradas_salidasgrid","load"], function() {
    fentradas_salidasgrid.updateLists(<?= $Grid->RowIndex ?>);
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
<input type="hidden" name="detailpage" value="fentradas_salidasgrid">
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
    ew.addEventHandlers("entradas_salidas");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
