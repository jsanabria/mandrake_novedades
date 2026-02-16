<?php

namespace PHPMaker2021\mandrake;

// Set up and run Grid object
$Grid = Container("ArticuloUnidadMedidaGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var currentForm, currentPageID;
var farticulo_unidad_medidagrid;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    farticulo_unidad_medidagrid = new ew.Form("farticulo_unidad_medidagrid", "grid");
    farticulo_unidad_medidagrid.formKeyCountName = '<?= $Grid->FormKeyCountName ?>';

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "articulo_unidad_medida")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.articulo_unidad_medida)
        ew.vars.tables.articulo_unidad_medida = currentTable;
    farticulo_unidad_medidagrid.addFields([
        ["unidad_medida", [fields.unidad_medida.visible && fields.unidad_medida.required ? ew.Validators.required(fields.unidad_medida.caption) : null], fields.unidad_medida.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = farticulo_unidad_medidagrid,
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
    farticulo_unidad_medidagrid.validate = function () {
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
    farticulo_unidad_medidagrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "unidad_medida", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    farticulo_unidad_medidagrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    farticulo_unidad_medidagrid.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    farticulo_unidad_medidagrid.lists.unidad_medida = <?= $Grid->unidad_medida->toClientList($Grid) ?>;
    loadjs.done("farticulo_unidad_medidagrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> articulo_unidad_medida">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="farticulo_unidad_medidagrid" class="ew-form ew-list-form form-inline">
<div id="gmp_articulo_unidad_medida" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_articulo_unidad_medidagrid" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Grid->unidad_medida->Visible) { // unidad_medida ?>
        <th data-name="unidad_medida" class="<?= $Grid->unidad_medida->headerCellClass() ?>"><div id="elh_articulo_unidad_medida_unidad_medida" class="articulo_unidad_medida_unidad_medida"><?= $Grid->renderSort($Grid->unidad_medida) ?></div></th>
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowCount, "id" => "r" . $Grid->RowCount . "_articulo_unidad_medida", "data-rowtype" => $Grid->RowType]);

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
    <?php if ($Grid->unidad_medida->Visible) { // unidad_medida ?>
        <td data-name="unidad_medida" <?= $Grid->unidad_medida->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_articulo_unidad_medida_unidad_medida" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_unidad_medida"
        name="x<?= $Grid->RowIndex ?>_unidad_medida"
        class="form-control ew-select<?= $Grid->unidad_medida->isInvalidClass() ?>"
        data-select2-id="articulo_unidad_medida_x<?= $Grid->RowIndex ?>_unidad_medida"
        data-table="articulo_unidad_medida"
        data-field="x_unidad_medida"
        data-value-separator="<?= $Grid->unidad_medida->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->unidad_medida->getPlaceHolder()) ?>"
        <?= $Grid->unidad_medida->editAttributes() ?>>
        <?= $Grid->unidad_medida->selectOptionListHtml("x{$Grid->RowIndex}_unidad_medida") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->unidad_medida->getErrorMessage() ?></div>
<?= $Grid->unidad_medida->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_unidad_medida") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='articulo_unidad_medida_x<?= $Grid->RowIndex ?>_unidad_medida']"),
        options = { name: "x<?= $Grid->RowIndex ?>_unidad_medida", selectId: "articulo_unidad_medida_x<?= $Grid->RowIndex ?>_unidad_medida", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.articulo_unidad_medida.fields.unidad_medida.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="articulo_unidad_medida" data-field="x_unidad_medida" data-hidden="1" name="o<?= $Grid->RowIndex ?>_unidad_medida" id="o<?= $Grid->RowIndex ?>_unidad_medida" value="<?= HtmlEncode($Grid->unidad_medida->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_articulo_unidad_medida_unidad_medida" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_unidad_medida"
        name="x<?= $Grid->RowIndex ?>_unidad_medida"
        class="form-control ew-select<?= $Grid->unidad_medida->isInvalidClass() ?>"
        data-select2-id="articulo_unidad_medida_x<?= $Grid->RowIndex ?>_unidad_medida"
        data-table="articulo_unidad_medida"
        data-field="x_unidad_medida"
        data-value-separator="<?= $Grid->unidad_medida->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->unidad_medida->getPlaceHolder()) ?>"
        <?= $Grid->unidad_medida->editAttributes() ?>>
        <?= $Grid->unidad_medida->selectOptionListHtml("x{$Grid->RowIndex}_unidad_medida") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->unidad_medida->getErrorMessage() ?></div>
<?= $Grid->unidad_medida->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_unidad_medida") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='articulo_unidad_medida_x<?= $Grid->RowIndex ?>_unidad_medida']"),
        options = { name: "x<?= $Grid->RowIndex ?>_unidad_medida", selectId: "articulo_unidad_medida_x<?= $Grid->RowIndex ?>_unidad_medida", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.articulo_unidad_medida.fields.unidad_medida.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_articulo_unidad_medida_unidad_medida">
<span<?= $Grid->unidad_medida->viewAttributes() ?>>
<?= $Grid->unidad_medida->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="articulo_unidad_medida" data-field="x_unidad_medida" data-hidden="1" name="farticulo_unidad_medidagrid$x<?= $Grid->RowIndex ?>_unidad_medida" id="farticulo_unidad_medidagrid$x<?= $Grid->RowIndex ?>_unidad_medida" value="<?= HtmlEncode($Grid->unidad_medida->FormValue) ?>">
<input type="hidden" data-table="articulo_unidad_medida" data-field="x_unidad_medida" data-hidden="1" name="farticulo_unidad_medidagrid$o<?= $Grid->RowIndex ?>_unidad_medida" id="farticulo_unidad_medidagrid$o<?= $Grid->RowIndex ?>_unidad_medida" value="<?= HtmlEncode($Grid->unidad_medida->OldValue) ?>">
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
loadjs.ready(["farticulo_unidad_medidagrid","load"], function () {
    farticulo_unidad_medidagrid.updateLists(<?= $Grid->RowIndex ?>);
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_articulo_unidad_medida", "data-rowtype" => ROWTYPE_ADD]);
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
    <?php if ($Grid->unidad_medida->Visible) { // unidad_medida ?>
        <td data-name="unidad_medida">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_articulo_unidad_medida_unidad_medida" class="form-group articulo_unidad_medida_unidad_medida">
    <select
        id="x<?= $Grid->RowIndex ?>_unidad_medida"
        name="x<?= $Grid->RowIndex ?>_unidad_medida"
        class="form-control ew-select<?= $Grid->unidad_medida->isInvalidClass() ?>"
        data-select2-id="articulo_unidad_medida_x<?= $Grid->RowIndex ?>_unidad_medida"
        data-table="articulo_unidad_medida"
        data-field="x_unidad_medida"
        data-value-separator="<?= $Grid->unidad_medida->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->unidad_medida->getPlaceHolder()) ?>"
        <?= $Grid->unidad_medida->editAttributes() ?>>
        <?= $Grid->unidad_medida->selectOptionListHtml("x{$Grid->RowIndex}_unidad_medida") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->unidad_medida->getErrorMessage() ?></div>
<?= $Grid->unidad_medida->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_unidad_medida") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='articulo_unidad_medida_x<?= $Grid->RowIndex ?>_unidad_medida']"),
        options = { name: "x<?= $Grid->RowIndex ?>_unidad_medida", selectId: "articulo_unidad_medida_x<?= $Grid->RowIndex ?>_unidad_medida", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.articulo_unidad_medida.fields.unidad_medida.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_articulo_unidad_medida_unidad_medida" class="form-group articulo_unidad_medida_unidad_medida">
<span<?= $Grid->unidad_medida->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->unidad_medida->getDisplayValue($Grid->unidad_medida->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="articulo_unidad_medida" data-field="x_unidad_medida" data-hidden="1" name="x<?= $Grid->RowIndex ?>_unidad_medida" id="x<?= $Grid->RowIndex ?>_unidad_medida" value="<?= HtmlEncode($Grid->unidad_medida->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="articulo_unidad_medida" data-field="x_unidad_medida" data-hidden="1" name="o<?= $Grid->RowIndex ?>_unidad_medida" id="o<?= $Grid->RowIndex ?>_unidad_medida" value="<?= HtmlEncode($Grid->unidad_medida->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["farticulo_unidad_medidagrid","load"], function() {
    farticulo_unidad_medidagrid.updateLists(<?= $Grid->RowIndex ?>);
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
<input type="hidden" name="detailpage" value="farticulo_unidad_medidagrid">
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
    ew.addEventHandlers("articulo_unidad_medida");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
