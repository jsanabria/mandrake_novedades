<?php

namespace PHPMaker2021\mandrake;

// Set up and run Grid object
$Grid = Container("ProveedorArticuloGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fproveedor_articulogrid;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    fproveedor_articulogrid = new ew.Form("fproveedor_articulogrid", "grid");
    fproveedor_articulogrid.formKeyCountName = '<?= $Grid->FormKeyCountName ?>';

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "proveedor_articulo")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.proveedor_articulo)
        ew.vars.tables.proveedor_articulo = currentTable;
    fproveedor_articulogrid.addFields([
        ["fabricante", [fields.fabricante.visible && fields.fabricante.required ? ew.Validators.required(fields.fabricante.caption) : null], fields.fabricante.isInvalid],
        ["articulo", [fields.articulo.visible && fields.articulo.required ? ew.Validators.required(fields.articulo.caption) : null], fields.articulo.isInvalid],
        ["codigo_proveedor", [fields.codigo_proveedor.visible && fields.codigo_proveedor.required ? ew.Validators.required(fields.codigo_proveedor.caption) : null], fields.codigo_proveedor.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fproveedor_articulogrid,
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
    fproveedor_articulogrid.validate = function () {
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
    fproveedor_articulogrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "fabricante", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "articulo", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "codigo_proveedor", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    fproveedor_articulogrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fproveedor_articulogrid.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fproveedor_articulogrid.lists.fabricante = <?= $Grid->fabricante->toClientList($Grid) ?>;
    fproveedor_articulogrid.lists.articulo = <?= $Grid->articulo->toClientList($Grid) ?>;
    loadjs.done("fproveedor_articulogrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> proveedor_articulo">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="fproveedor_articulogrid" class="ew-form ew-list-form form-inline">
<div id="gmp_proveedor_articulo" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_proveedor_articulogrid" class="table ew-table"><!-- .ew-table -->
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
        <th data-name="fabricante" class="<?= $Grid->fabricante->headerCellClass() ?>"><div id="elh_proveedor_articulo_fabricante" class="proveedor_articulo_fabricante"><?= $Grid->renderSort($Grid->fabricante) ?></div></th>
<?php } ?>
<?php if ($Grid->articulo->Visible) { // articulo ?>
        <th data-name="articulo" class="<?= $Grid->articulo->headerCellClass() ?>"><div id="elh_proveedor_articulo_articulo" class="proveedor_articulo_articulo"><?= $Grid->renderSort($Grid->articulo) ?></div></th>
<?php } ?>
<?php if ($Grid->codigo_proveedor->Visible) { // codigo_proveedor ?>
        <th data-name="codigo_proveedor" class="<?= $Grid->codigo_proveedor->headerCellClass() ?>"><div id="elh_proveedor_articulo_codigo_proveedor" class="proveedor_articulo_codigo_proveedor"><?= $Grid->renderSort($Grid->codigo_proveedor) ?></div></th>
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowCount, "id" => "r" . $Grid->RowCount . "_proveedor_articulo", "data-rowtype" => $Grid->RowType]);

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
<span id="el<?= $Grid->RowCount ?>_proveedor_articulo_fabricante" class="form-group">
<?php $Grid->fabricante->EditAttrs->prepend("onchange", "ew.updateOptions.call(this);"); ?>
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Grid->RowIndex ?>_fabricante"><?= EmptyValue(strval($Grid->fabricante->ViewValue)) ? $Language->phrase("PleaseSelect") : $Grid->fabricante->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Grid->fabricante->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Grid->fabricante->ReadOnly || $Grid->fabricante->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_fabricante',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->fabricante->getErrorMessage() ?></div>
<?= $Grid->fabricante->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_fabricante") ?>
<input type="hidden" is="selection-list" data-table="proveedor_articulo" data-field="x_fabricante" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Grid->fabricante->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_fabricante" id="x<?= $Grid->RowIndex ?>_fabricante" value="<?= $Grid->fabricante->CurrentValue ?>"<?= $Grid->fabricante->editAttributes() ?>>
</span>
<input type="hidden" data-table="proveedor_articulo" data-field="x_fabricante" data-hidden="1" name="o<?= $Grid->RowIndex ?>_fabricante" id="o<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_proveedor_articulo_fabricante" class="form-group">
<?php $Grid->fabricante->EditAttrs->prepend("onchange", "ew.updateOptions.call(this);"); ?>
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Grid->RowIndex ?>_fabricante"><?= EmptyValue(strval($Grid->fabricante->ViewValue)) ? $Language->phrase("PleaseSelect") : $Grid->fabricante->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Grid->fabricante->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Grid->fabricante->ReadOnly || $Grid->fabricante->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_fabricante',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->fabricante->getErrorMessage() ?></div>
<?= $Grid->fabricante->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_fabricante") ?>
<input type="hidden" is="selection-list" data-table="proveedor_articulo" data-field="x_fabricante" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Grid->fabricante->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_fabricante" id="x<?= $Grid->RowIndex ?>_fabricante" value="<?= $Grid->fabricante->CurrentValue ?>"<?= $Grid->fabricante->editAttributes() ?>>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_proveedor_articulo_fabricante">
<span<?= $Grid->fabricante->viewAttributes() ?>>
<?= $Grid->fabricante->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="proveedor_articulo" data-field="x_fabricante" data-hidden="1" name="fproveedor_articulogrid$x<?= $Grid->RowIndex ?>_fabricante" id="fproveedor_articulogrid$x<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->FormValue) ?>">
<input type="hidden" data-table="proveedor_articulo" data-field="x_fabricante" data-hidden="1" name="fproveedor_articulogrid$o<?= $Grid->RowIndex ?>_fabricante" id="fproveedor_articulogrid$o<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->articulo->Visible) { // articulo ?>
        <td data-name="articulo" <?= $Grid->articulo->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_proveedor_articulo_articulo" class="form-group">
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Grid->RowIndex ?>_articulo"><?= EmptyValue(strval($Grid->articulo->ViewValue)) ? $Language->phrase("PleaseSelect") : $Grid->articulo->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Grid->articulo->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Grid->articulo->ReadOnly || $Grid->articulo->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_articulo',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->articulo->getErrorMessage() ?></div>
<?= $Grid->articulo->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_articulo") ?>
<input type="hidden" is="selection-list" data-table="proveedor_articulo" data-field="x_articulo" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Grid->articulo->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_articulo" id="x<?= $Grid->RowIndex ?>_articulo" value="<?= $Grid->articulo->CurrentValue ?>"<?= $Grid->articulo->editAttributes() ?>>
</span>
<input type="hidden" data-table="proveedor_articulo" data-field="x_articulo" data-hidden="1" name="o<?= $Grid->RowIndex ?>_articulo" id="o<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_proveedor_articulo_articulo" class="form-group">
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Grid->RowIndex ?>_articulo"><?= EmptyValue(strval($Grid->articulo->ViewValue)) ? $Language->phrase("PleaseSelect") : $Grid->articulo->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Grid->articulo->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Grid->articulo->ReadOnly || $Grid->articulo->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_articulo',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->articulo->getErrorMessage() ?></div>
<?= $Grid->articulo->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_articulo") ?>
<input type="hidden" is="selection-list" data-table="proveedor_articulo" data-field="x_articulo" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Grid->articulo->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_articulo" id="x<?= $Grid->RowIndex ?>_articulo" value="<?= $Grid->articulo->CurrentValue ?>"<?= $Grid->articulo->editAttributes() ?>>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_proveedor_articulo_articulo">
<span<?= $Grid->articulo->viewAttributes() ?>>
<?= $Grid->articulo->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="proveedor_articulo" data-field="x_articulo" data-hidden="1" name="fproveedor_articulogrid$x<?= $Grid->RowIndex ?>_articulo" id="fproveedor_articulogrid$x<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->FormValue) ?>">
<input type="hidden" data-table="proveedor_articulo" data-field="x_articulo" data-hidden="1" name="fproveedor_articulogrid$o<?= $Grid->RowIndex ?>_articulo" id="fproveedor_articulogrid$o<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->codigo_proveedor->Visible) { // codigo_proveedor ?>
        <td data-name="codigo_proveedor" <?= $Grid->codigo_proveedor->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_proveedor_articulo_codigo_proveedor" class="form-group">
<input type="<?= $Grid->codigo_proveedor->getInputTextType() ?>" data-table="proveedor_articulo" data-field="x_codigo_proveedor" name="x<?= $Grid->RowIndex ?>_codigo_proveedor" id="x<?= $Grid->RowIndex ?>_codigo_proveedor" size="10" maxlength="30" placeholder="<?= HtmlEncode($Grid->codigo_proveedor->getPlaceHolder()) ?>" value="<?= $Grid->codigo_proveedor->EditValue ?>"<?= $Grid->codigo_proveedor->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->codigo_proveedor->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="proveedor_articulo" data-field="x_codigo_proveedor" data-hidden="1" name="o<?= $Grid->RowIndex ?>_codigo_proveedor" id="o<?= $Grid->RowIndex ?>_codigo_proveedor" value="<?= HtmlEncode($Grid->codigo_proveedor->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_proveedor_articulo_codigo_proveedor" class="form-group">
<input type="<?= $Grid->codigo_proveedor->getInputTextType() ?>" data-table="proveedor_articulo" data-field="x_codigo_proveedor" name="x<?= $Grid->RowIndex ?>_codigo_proveedor" id="x<?= $Grid->RowIndex ?>_codigo_proveedor" size="10" maxlength="30" placeholder="<?= HtmlEncode($Grid->codigo_proveedor->getPlaceHolder()) ?>" value="<?= $Grid->codigo_proveedor->EditValue ?>"<?= $Grid->codigo_proveedor->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->codigo_proveedor->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_proveedor_articulo_codigo_proveedor">
<span<?= $Grid->codigo_proveedor->viewAttributes() ?>>
<?= $Grid->codigo_proveedor->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="proveedor_articulo" data-field="x_codigo_proveedor" data-hidden="1" name="fproveedor_articulogrid$x<?= $Grid->RowIndex ?>_codigo_proveedor" id="fproveedor_articulogrid$x<?= $Grid->RowIndex ?>_codigo_proveedor" value="<?= HtmlEncode($Grid->codigo_proveedor->FormValue) ?>">
<input type="hidden" data-table="proveedor_articulo" data-field="x_codigo_proveedor" data-hidden="1" name="fproveedor_articulogrid$o<?= $Grid->RowIndex ?>_codigo_proveedor" id="fproveedor_articulogrid$o<?= $Grid->RowIndex ?>_codigo_proveedor" value="<?= HtmlEncode($Grid->codigo_proveedor->OldValue) ?>">
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
loadjs.ready(["fproveedor_articulogrid","load"], function () {
    fproveedor_articulogrid.updateLists(<?= $Grid->RowIndex ?>);
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_proveedor_articulo", "data-rowtype" => ROWTYPE_ADD]);
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
<span id="el$rowindex$_proveedor_articulo_fabricante" class="form-group proveedor_articulo_fabricante">
<?php $Grid->fabricante->EditAttrs->prepend("onchange", "ew.updateOptions.call(this);"); ?>
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Grid->RowIndex ?>_fabricante"><?= EmptyValue(strval($Grid->fabricante->ViewValue)) ? $Language->phrase("PleaseSelect") : $Grid->fabricante->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Grid->fabricante->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Grid->fabricante->ReadOnly || $Grid->fabricante->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_fabricante',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->fabricante->getErrorMessage() ?></div>
<?= $Grid->fabricante->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_fabricante") ?>
<input type="hidden" is="selection-list" data-table="proveedor_articulo" data-field="x_fabricante" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Grid->fabricante->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_fabricante" id="x<?= $Grid->RowIndex ?>_fabricante" value="<?= $Grid->fabricante->CurrentValue ?>"<?= $Grid->fabricante->editAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_proveedor_articulo_fabricante" class="form-group proveedor_articulo_fabricante">
<span<?= $Grid->fabricante->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->fabricante->getDisplayValue($Grid->fabricante->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="proveedor_articulo" data-field="x_fabricante" data-hidden="1" name="x<?= $Grid->RowIndex ?>_fabricante" id="x<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="proveedor_articulo" data-field="x_fabricante" data-hidden="1" name="o<?= $Grid->RowIndex ?>_fabricante" id="o<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->articulo->Visible) { // articulo ?>
        <td data-name="articulo">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_proveedor_articulo_articulo" class="form-group proveedor_articulo_articulo">
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Grid->RowIndex ?>_articulo"><?= EmptyValue(strval($Grid->articulo->ViewValue)) ? $Language->phrase("PleaseSelect") : $Grid->articulo->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Grid->articulo->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Grid->articulo->ReadOnly || $Grid->articulo->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_articulo',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->articulo->getErrorMessage() ?></div>
<?= $Grid->articulo->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_articulo") ?>
<input type="hidden" is="selection-list" data-table="proveedor_articulo" data-field="x_articulo" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Grid->articulo->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_articulo" id="x<?= $Grid->RowIndex ?>_articulo" value="<?= $Grid->articulo->CurrentValue ?>"<?= $Grid->articulo->editAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_proveedor_articulo_articulo" class="form-group proveedor_articulo_articulo">
<span<?= $Grid->articulo->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->articulo->getDisplayValue($Grid->articulo->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="proveedor_articulo" data-field="x_articulo" data-hidden="1" name="x<?= $Grid->RowIndex ?>_articulo" id="x<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="proveedor_articulo" data-field="x_articulo" data-hidden="1" name="o<?= $Grid->RowIndex ?>_articulo" id="o<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->codigo_proveedor->Visible) { // codigo_proveedor ?>
        <td data-name="codigo_proveedor">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_proveedor_articulo_codigo_proveedor" class="form-group proveedor_articulo_codigo_proveedor">
<input type="<?= $Grid->codigo_proveedor->getInputTextType() ?>" data-table="proveedor_articulo" data-field="x_codigo_proveedor" name="x<?= $Grid->RowIndex ?>_codigo_proveedor" id="x<?= $Grid->RowIndex ?>_codigo_proveedor" size="10" maxlength="30" placeholder="<?= HtmlEncode($Grid->codigo_proveedor->getPlaceHolder()) ?>" value="<?= $Grid->codigo_proveedor->EditValue ?>"<?= $Grid->codigo_proveedor->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->codigo_proveedor->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_proveedor_articulo_codigo_proveedor" class="form-group proveedor_articulo_codigo_proveedor">
<span<?= $Grid->codigo_proveedor->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->codigo_proveedor->getDisplayValue($Grid->codigo_proveedor->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="proveedor_articulo" data-field="x_codigo_proveedor" data-hidden="1" name="x<?= $Grid->RowIndex ?>_codigo_proveedor" id="x<?= $Grid->RowIndex ?>_codigo_proveedor" value="<?= HtmlEncode($Grid->codigo_proveedor->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="proveedor_articulo" data-field="x_codigo_proveedor" data-hidden="1" name="o<?= $Grid->RowIndex ?>_codigo_proveedor" id="o<?= $Grid->RowIndex ?>_codigo_proveedor" value="<?= HtmlEncode($Grid->codigo_proveedor->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["fproveedor_articulogrid","load"], function() {
    fproveedor_articulogrid.updateLists(<?= $Grid->RowIndex ?>);
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
<input type="hidden" name="detailpage" value="fproveedor_articulogrid">
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
    ew.addEventHandlers("proveedor_articulo");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
