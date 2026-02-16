<?php

namespace PHPMaker2021\mandrake;

// Set up and run Grid object
$Grid = Container("TarifaArticuloGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var currentForm, currentPageID;
var ftarifa_articulogrid;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    ftarifa_articulogrid = new ew.Form("ftarifa_articulogrid", "grid");
    ftarifa_articulogrid.formKeyCountName = '<?= $Grid->FormKeyCountName ?>';

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "tarifa_articulo")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.tarifa_articulo)
        ew.vars.tables.tarifa_articulo = currentTable;
    ftarifa_articulogrid.addFields([
        ["tarifa", [fields.tarifa.visible && fields.tarifa.required ? ew.Validators.required(fields.tarifa.caption) : null], fields.tarifa.isInvalid],
        ["fabricante", [fields.fabricante.visible && fields.fabricante.required ? ew.Validators.required(fields.fabricante.caption) : null], fields.fabricante.isInvalid],
        ["articulo", [fields.articulo.visible && fields.articulo.required ? ew.Validators.required(fields.articulo.caption) : null], fields.articulo.isInvalid],
        ["precio", [fields.precio.visible && fields.precio.required ? ew.Validators.required(fields.precio.caption) : null, ew.Validators.float], fields.precio.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = ftarifa_articulogrid,
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
    ftarifa_articulogrid.validate = function () {
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
    ftarifa_articulogrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "tarifa", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "fabricante", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "articulo", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "precio", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    ftarifa_articulogrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    ftarifa_articulogrid.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    ftarifa_articulogrid.lists.tarifa = <?= $Grid->tarifa->toClientList($Grid) ?>;
    ftarifa_articulogrid.lists.fabricante = <?= $Grid->fabricante->toClientList($Grid) ?>;
    ftarifa_articulogrid.lists.articulo = <?= $Grid->articulo->toClientList($Grid) ?>;
    loadjs.done("ftarifa_articulogrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> tarifa_articulo">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="ftarifa_articulogrid" class="ew-form ew-list-form form-inline">
<div id="gmp_tarifa_articulo" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_tarifa_articulogrid" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Grid->tarifa->Visible) { // tarifa ?>
        <th data-name="tarifa" class="<?= $Grid->tarifa->headerCellClass() ?>"><div id="elh_tarifa_articulo_tarifa" class="tarifa_articulo_tarifa"><?= $Grid->renderSort($Grid->tarifa) ?></div></th>
<?php } ?>
<?php if ($Grid->fabricante->Visible) { // fabricante ?>
        <th data-name="fabricante" class="<?= $Grid->fabricante->headerCellClass() ?>"><div id="elh_tarifa_articulo_fabricante" class="tarifa_articulo_fabricante"><?= $Grid->renderSort($Grid->fabricante) ?></div></th>
<?php } ?>
<?php if ($Grid->articulo->Visible) { // articulo ?>
        <th data-name="articulo" class="<?= $Grid->articulo->headerCellClass() ?>"><div id="elh_tarifa_articulo_articulo" class="tarifa_articulo_articulo"><?= $Grid->renderSort($Grid->articulo) ?></div></th>
<?php } ?>
<?php if ($Grid->precio->Visible) { // precio ?>
        <th data-name="precio" class="<?= $Grid->precio->headerCellClass() ?>"><div id="elh_tarifa_articulo_precio" class="tarifa_articulo_precio"><?= $Grid->renderSort($Grid->precio) ?></div></th>
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowCount, "id" => "r" . $Grid->RowCount . "_tarifa_articulo", "data-rowtype" => $Grid->RowType]);

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
    <?php if ($Grid->tarifa->Visible) { // tarifa ?>
        <td data-name="tarifa" <?= $Grid->tarifa->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if ($Grid->tarifa->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_tarifa_articulo_tarifa" class="form-group">
<span<?= $Grid->tarifa->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->tarifa->getDisplayValue($Grid->tarifa->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_tarifa" name="x<?= $Grid->RowIndex ?>_tarifa" value="<?= HtmlEncode($Grid->tarifa->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_tarifa_articulo_tarifa" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_tarifa"
        name="x<?= $Grid->RowIndex ?>_tarifa"
        class="form-control ew-select<?= $Grid->tarifa->isInvalidClass() ?>"
        data-select2-id="tarifa_articulo_x<?= $Grid->RowIndex ?>_tarifa"
        data-table="tarifa_articulo"
        data-field="x_tarifa"
        data-value-separator="<?= $Grid->tarifa->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->tarifa->getPlaceHolder()) ?>"
        <?= $Grid->tarifa->editAttributes() ?>>
        <?= $Grid->tarifa->selectOptionListHtml("x{$Grid->RowIndex}_tarifa") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->tarifa->getErrorMessage() ?></div>
<?= $Grid->tarifa->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_tarifa") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='tarifa_articulo_x<?= $Grid->RowIndex ?>_tarifa']"),
        options = { name: "x<?= $Grid->RowIndex ?>_tarifa", selectId: "tarifa_articulo_x<?= $Grid->RowIndex ?>_tarifa", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.tarifa_articulo.fields.tarifa.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="tarifa_articulo" data-field="x_tarifa" data-hidden="1" name="o<?= $Grid->RowIndex ?>_tarifa" id="o<?= $Grid->RowIndex ?>_tarifa" value="<?= HtmlEncode($Grid->tarifa->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Grid->tarifa->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_tarifa_articulo_tarifa" class="form-group">
<span<?= $Grid->tarifa->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->tarifa->getDisplayValue($Grid->tarifa->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_tarifa" name="x<?= $Grid->RowIndex ?>_tarifa" value="<?= HtmlEncode($Grid->tarifa->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_tarifa_articulo_tarifa" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_tarifa"
        name="x<?= $Grid->RowIndex ?>_tarifa"
        class="form-control ew-select<?= $Grid->tarifa->isInvalidClass() ?>"
        data-select2-id="tarifa_articulo_x<?= $Grid->RowIndex ?>_tarifa"
        data-table="tarifa_articulo"
        data-field="x_tarifa"
        data-value-separator="<?= $Grid->tarifa->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->tarifa->getPlaceHolder()) ?>"
        <?= $Grid->tarifa->editAttributes() ?>>
        <?= $Grid->tarifa->selectOptionListHtml("x{$Grid->RowIndex}_tarifa") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->tarifa->getErrorMessage() ?></div>
<?= $Grid->tarifa->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_tarifa") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='tarifa_articulo_x<?= $Grid->RowIndex ?>_tarifa']"),
        options = { name: "x<?= $Grid->RowIndex ?>_tarifa", selectId: "tarifa_articulo_x<?= $Grid->RowIndex ?>_tarifa", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.tarifa_articulo.fields.tarifa.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_tarifa_articulo_tarifa">
<span<?= $Grid->tarifa->viewAttributes() ?>>
<?= $Grid->tarifa->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="tarifa_articulo" data-field="x_tarifa" data-hidden="1" name="ftarifa_articulogrid$x<?= $Grid->RowIndex ?>_tarifa" id="ftarifa_articulogrid$x<?= $Grid->RowIndex ?>_tarifa" value="<?= HtmlEncode($Grid->tarifa->FormValue) ?>">
<input type="hidden" data-table="tarifa_articulo" data-field="x_tarifa" data-hidden="1" name="ftarifa_articulogrid$o<?= $Grid->RowIndex ?>_tarifa" id="ftarifa_articulogrid$o<?= $Grid->RowIndex ?>_tarifa" value="<?= HtmlEncode($Grid->tarifa->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->fabricante->Visible) { // fabricante ?>
        <td data-name="fabricante" <?= $Grid->fabricante->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_tarifa_articulo_fabricante" class="form-group">
<?php $Grid->fabricante->EditAttrs->prepend("onchange", "ew.updateOptions.call(this);"); ?>
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Grid->RowIndex ?>_fabricante"><?= EmptyValue(strval($Grid->fabricante->ViewValue)) ? $Language->phrase("PleaseSelect") : $Grid->fabricante->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Grid->fabricante->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Grid->fabricante->ReadOnly || $Grid->fabricante->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_fabricante',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->fabricante->getErrorMessage() ?></div>
<?= $Grid->fabricante->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_fabricante") ?>
<input type="hidden" is="selection-list" data-table="tarifa_articulo" data-field="x_fabricante" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Grid->fabricante->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_fabricante" id="x<?= $Grid->RowIndex ?>_fabricante" value="<?= $Grid->fabricante->CurrentValue ?>"<?= $Grid->fabricante->editAttributes() ?>>
</span>
<input type="hidden" data-table="tarifa_articulo" data-field="x_fabricante" data-hidden="1" name="o<?= $Grid->RowIndex ?>_fabricante" id="o<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_tarifa_articulo_fabricante" class="form-group">
<?php $Grid->fabricante->EditAttrs->prepend("onchange", "ew.updateOptions.call(this);"); ?>
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Grid->RowIndex ?>_fabricante"><?= EmptyValue(strval($Grid->fabricante->ViewValue)) ? $Language->phrase("PleaseSelect") : $Grid->fabricante->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Grid->fabricante->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Grid->fabricante->ReadOnly || $Grid->fabricante->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_fabricante',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->fabricante->getErrorMessage() ?></div>
<?= $Grid->fabricante->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_fabricante") ?>
<input type="hidden" is="selection-list" data-table="tarifa_articulo" data-field="x_fabricante" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Grid->fabricante->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_fabricante" id="x<?= $Grid->RowIndex ?>_fabricante" value="<?= $Grid->fabricante->CurrentValue ?>"<?= $Grid->fabricante->editAttributes() ?>>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_tarifa_articulo_fabricante">
<span<?= $Grid->fabricante->viewAttributes() ?>>
<?= $Grid->fabricante->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="tarifa_articulo" data-field="x_fabricante" data-hidden="1" name="ftarifa_articulogrid$x<?= $Grid->RowIndex ?>_fabricante" id="ftarifa_articulogrid$x<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->FormValue) ?>">
<input type="hidden" data-table="tarifa_articulo" data-field="x_fabricante" data-hidden="1" name="ftarifa_articulogrid$o<?= $Grid->RowIndex ?>_fabricante" id="ftarifa_articulogrid$o<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->articulo->Visible) { // articulo ?>
        <td data-name="articulo" <?= $Grid->articulo->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_tarifa_articulo_articulo" class="form-group">
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Grid->RowIndex ?>_articulo"><?= EmptyValue(strval($Grid->articulo->ViewValue)) ? $Language->phrase("PleaseSelect") : $Grid->articulo->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Grid->articulo->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Grid->articulo->ReadOnly || $Grid->articulo->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_articulo',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->articulo->getErrorMessage() ?></div>
<?= $Grid->articulo->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_articulo") ?>
<input type="hidden" is="selection-list" data-table="tarifa_articulo" data-field="x_articulo" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Grid->articulo->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_articulo" id="x<?= $Grid->RowIndex ?>_articulo" value="<?= $Grid->articulo->CurrentValue ?>"<?= $Grid->articulo->editAttributes() ?>>
</span>
<input type="hidden" data-table="tarifa_articulo" data-field="x_articulo" data-hidden="1" name="o<?= $Grid->RowIndex ?>_articulo" id="o<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_tarifa_articulo_articulo" class="form-group">
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Grid->RowIndex ?>_articulo"><?= EmptyValue(strval($Grid->articulo->ViewValue)) ? $Language->phrase("PleaseSelect") : $Grid->articulo->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Grid->articulo->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Grid->articulo->ReadOnly || $Grid->articulo->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_articulo',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->articulo->getErrorMessage() ?></div>
<?= $Grid->articulo->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_articulo") ?>
<input type="hidden" is="selection-list" data-table="tarifa_articulo" data-field="x_articulo" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Grid->articulo->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_articulo" id="x<?= $Grid->RowIndex ?>_articulo" value="<?= $Grid->articulo->CurrentValue ?>"<?= $Grid->articulo->editAttributes() ?>>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_tarifa_articulo_articulo">
<span<?= $Grid->articulo->viewAttributes() ?>>
<?= $Grid->articulo->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="tarifa_articulo" data-field="x_articulo" data-hidden="1" name="ftarifa_articulogrid$x<?= $Grid->RowIndex ?>_articulo" id="ftarifa_articulogrid$x<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->FormValue) ?>">
<input type="hidden" data-table="tarifa_articulo" data-field="x_articulo" data-hidden="1" name="ftarifa_articulogrid$o<?= $Grid->RowIndex ?>_articulo" id="ftarifa_articulogrid$o<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->precio->Visible) { // precio ?>
        <td data-name="precio" <?= $Grid->precio->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_tarifa_articulo_precio" class="form-group">
<input type="<?= $Grid->precio->getInputTextType() ?>" data-table="tarifa_articulo" data-field="x_precio" name="x<?= $Grid->RowIndex ?>_precio" id="x<?= $Grid->RowIndex ?>_precio" size="10" maxlength="13" placeholder="<?= HtmlEncode($Grid->precio->getPlaceHolder()) ?>" value="<?= $Grid->precio->EditValue ?>"<?= $Grid->precio->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->precio->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="tarifa_articulo" data-field="x_precio" data-hidden="1" name="o<?= $Grid->RowIndex ?>_precio" id="o<?= $Grid->RowIndex ?>_precio" value="<?= HtmlEncode($Grid->precio->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_tarifa_articulo_precio" class="form-group">
<input type="<?= $Grid->precio->getInputTextType() ?>" data-table="tarifa_articulo" data-field="x_precio" name="x<?= $Grid->RowIndex ?>_precio" id="x<?= $Grid->RowIndex ?>_precio" size="10" maxlength="13" placeholder="<?= HtmlEncode($Grid->precio->getPlaceHolder()) ?>" value="<?= $Grid->precio->EditValue ?>"<?= $Grid->precio->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->precio->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_tarifa_articulo_precio">
<span<?= $Grid->precio->viewAttributes() ?>>
<?= $Grid->precio->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="tarifa_articulo" data-field="x_precio" data-hidden="1" name="ftarifa_articulogrid$x<?= $Grid->RowIndex ?>_precio" id="ftarifa_articulogrid$x<?= $Grid->RowIndex ?>_precio" value="<?= HtmlEncode($Grid->precio->FormValue) ?>">
<input type="hidden" data-table="tarifa_articulo" data-field="x_precio" data-hidden="1" name="ftarifa_articulogrid$o<?= $Grid->RowIndex ?>_precio" id="ftarifa_articulogrid$o<?= $Grid->RowIndex ?>_precio" value="<?= HtmlEncode($Grid->precio->OldValue) ?>">
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
loadjs.ready(["ftarifa_articulogrid","load"], function () {
    ftarifa_articulogrid.updateLists(<?= $Grid->RowIndex ?>);
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_tarifa_articulo", "data-rowtype" => ROWTYPE_ADD]);
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
    <?php if ($Grid->tarifa->Visible) { // tarifa ?>
        <td data-name="tarifa">
<?php if (!$Grid->isConfirm()) { ?>
<?php if ($Grid->tarifa->getSessionValue() != "") { ?>
<span id="el$rowindex$_tarifa_articulo_tarifa" class="form-group tarifa_articulo_tarifa">
<span<?= $Grid->tarifa->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->tarifa->getDisplayValue($Grid->tarifa->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_tarifa" name="x<?= $Grid->RowIndex ?>_tarifa" value="<?= HtmlEncode($Grid->tarifa->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el$rowindex$_tarifa_articulo_tarifa" class="form-group tarifa_articulo_tarifa">
    <select
        id="x<?= $Grid->RowIndex ?>_tarifa"
        name="x<?= $Grid->RowIndex ?>_tarifa"
        class="form-control ew-select<?= $Grid->tarifa->isInvalidClass() ?>"
        data-select2-id="tarifa_articulo_x<?= $Grid->RowIndex ?>_tarifa"
        data-table="tarifa_articulo"
        data-field="x_tarifa"
        data-value-separator="<?= $Grid->tarifa->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->tarifa->getPlaceHolder()) ?>"
        <?= $Grid->tarifa->editAttributes() ?>>
        <?= $Grid->tarifa->selectOptionListHtml("x{$Grid->RowIndex}_tarifa") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->tarifa->getErrorMessage() ?></div>
<?= $Grid->tarifa->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_tarifa") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='tarifa_articulo_x<?= $Grid->RowIndex ?>_tarifa']"),
        options = { name: "x<?= $Grid->RowIndex ?>_tarifa", selectId: "tarifa_articulo_x<?= $Grid->RowIndex ?>_tarifa", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.tarifa_articulo.fields.tarifa.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_tarifa_articulo_tarifa" class="form-group tarifa_articulo_tarifa">
<span<?= $Grid->tarifa->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->tarifa->getDisplayValue($Grid->tarifa->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="tarifa_articulo" data-field="x_tarifa" data-hidden="1" name="x<?= $Grid->RowIndex ?>_tarifa" id="x<?= $Grid->RowIndex ?>_tarifa" value="<?= HtmlEncode($Grid->tarifa->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="tarifa_articulo" data-field="x_tarifa" data-hidden="1" name="o<?= $Grid->RowIndex ?>_tarifa" id="o<?= $Grid->RowIndex ?>_tarifa" value="<?= HtmlEncode($Grid->tarifa->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->fabricante->Visible) { // fabricante ?>
        <td data-name="fabricante">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_tarifa_articulo_fabricante" class="form-group tarifa_articulo_fabricante">
<?php $Grid->fabricante->EditAttrs->prepend("onchange", "ew.updateOptions.call(this);"); ?>
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Grid->RowIndex ?>_fabricante"><?= EmptyValue(strval($Grid->fabricante->ViewValue)) ? $Language->phrase("PleaseSelect") : $Grid->fabricante->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Grid->fabricante->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Grid->fabricante->ReadOnly || $Grid->fabricante->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_fabricante',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->fabricante->getErrorMessage() ?></div>
<?= $Grid->fabricante->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_fabricante") ?>
<input type="hidden" is="selection-list" data-table="tarifa_articulo" data-field="x_fabricante" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Grid->fabricante->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_fabricante" id="x<?= $Grid->RowIndex ?>_fabricante" value="<?= $Grid->fabricante->CurrentValue ?>"<?= $Grid->fabricante->editAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_tarifa_articulo_fabricante" class="form-group tarifa_articulo_fabricante">
<span<?= $Grid->fabricante->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->fabricante->getDisplayValue($Grid->fabricante->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="tarifa_articulo" data-field="x_fabricante" data-hidden="1" name="x<?= $Grid->RowIndex ?>_fabricante" id="x<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="tarifa_articulo" data-field="x_fabricante" data-hidden="1" name="o<?= $Grid->RowIndex ?>_fabricante" id="o<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->articulo->Visible) { // articulo ?>
        <td data-name="articulo">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_tarifa_articulo_articulo" class="form-group tarifa_articulo_articulo">
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Grid->RowIndex ?>_articulo"><?= EmptyValue(strval($Grid->articulo->ViewValue)) ? $Language->phrase("PleaseSelect") : $Grid->articulo->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Grid->articulo->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Grid->articulo->ReadOnly || $Grid->articulo->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_articulo',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->articulo->getErrorMessage() ?></div>
<?= $Grid->articulo->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_articulo") ?>
<input type="hidden" is="selection-list" data-table="tarifa_articulo" data-field="x_articulo" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Grid->articulo->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_articulo" id="x<?= $Grid->RowIndex ?>_articulo" value="<?= $Grid->articulo->CurrentValue ?>"<?= $Grid->articulo->editAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_tarifa_articulo_articulo" class="form-group tarifa_articulo_articulo">
<span<?= $Grid->articulo->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->articulo->getDisplayValue($Grid->articulo->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="tarifa_articulo" data-field="x_articulo" data-hidden="1" name="x<?= $Grid->RowIndex ?>_articulo" id="x<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="tarifa_articulo" data-field="x_articulo" data-hidden="1" name="o<?= $Grid->RowIndex ?>_articulo" id="o<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->precio->Visible) { // precio ?>
        <td data-name="precio">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_tarifa_articulo_precio" class="form-group tarifa_articulo_precio">
<input type="<?= $Grid->precio->getInputTextType() ?>" data-table="tarifa_articulo" data-field="x_precio" name="x<?= $Grid->RowIndex ?>_precio" id="x<?= $Grid->RowIndex ?>_precio" size="10" maxlength="13" placeholder="<?= HtmlEncode($Grid->precio->getPlaceHolder()) ?>" value="<?= $Grid->precio->EditValue ?>"<?= $Grid->precio->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->precio->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_tarifa_articulo_precio" class="form-group tarifa_articulo_precio">
<span<?= $Grid->precio->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->precio->getDisplayValue($Grid->precio->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="tarifa_articulo" data-field="x_precio" data-hidden="1" name="x<?= $Grid->RowIndex ?>_precio" id="x<?= $Grid->RowIndex ?>_precio" value="<?= HtmlEncode($Grid->precio->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="tarifa_articulo" data-field="x_precio" data-hidden="1" name="o<?= $Grid->RowIndex ?>_precio" id="o<?= $Grid->RowIndex ?>_precio" value="<?= HtmlEncode($Grid->precio->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["ftarifa_articulogrid","load"], function() {
    ftarifa_articulogrid.updateLists(<?= $Grid->RowIndex ?>);
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
<input type="hidden" name="detailpage" value="ftarifa_articulogrid">
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
    ew.addEventHandlers("tarifa_articulo");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
