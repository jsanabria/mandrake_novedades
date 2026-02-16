<?php

namespace PHPMaker2021\mandrake;

// Set up and run Grid object
$Grid = Container("ContReglasGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fcont_reglasgrid;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    fcont_reglasgrid = new ew.Form("fcont_reglasgrid", "grid");
    fcont_reglasgrid.formKeyCountName = '<?= $Grid->FormKeyCountName ?>';

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "cont_reglas")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.cont_reglas)
        ew.vars.tables.cont_reglas = currentTable;
    fcont_reglasgrid.addFields([
        ["codigo", [fields.codigo.visible && fields.codigo.required ? ew.Validators.required(fields.codigo.caption) : null], fields.codigo.isInvalid],
        ["descripcion", [fields.descripcion.visible && fields.descripcion.required ? ew.Validators.required(fields.descripcion.caption) : null], fields.descripcion.isInvalid],
        ["cuenta", [fields.cuenta.visible && fields.cuenta.required ? ew.Validators.required(fields.cuenta.caption) : null], fields.cuenta.isInvalid],
        ["cargo", [fields.cargo.visible && fields.cargo.required ? ew.Validators.required(fields.cargo.caption) : null], fields.cargo.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fcont_reglasgrid,
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
    fcont_reglasgrid.validate = function () {
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
    fcont_reglasgrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "codigo", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "descripcion", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "cuenta", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "cargo", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    fcont_reglasgrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fcont_reglasgrid.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fcont_reglasgrid.lists.cuenta = <?= $Grid->cuenta->toClientList($Grid) ?>;
    fcont_reglasgrid.lists.cargo = <?= $Grid->cargo->toClientList($Grid) ?>;
    loadjs.done("fcont_reglasgrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> cont_reglas">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="fcont_reglasgrid" class="ew-form ew-list-form form-inline">
<div id="gmp_cont_reglas" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_cont_reglasgrid" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Grid->codigo->Visible) { // codigo ?>
        <th data-name="codigo" class="<?= $Grid->codigo->headerCellClass() ?>"><div id="elh_cont_reglas_codigo" class="cont_reglas_codigo"><?= $Grid->renderSort($Grid->codigo) ?></div></th>
<?php } ?>
<?php if ($Grid->descripcion->Visible) { // descripcion ?>
        <th data-name="descripcion" class="<?= $Grid->descripcion->headerCellClass() ?>"><div id="elh_cont_reglas_descripcion" class="cont_reglas_descripcion"><?= $Grid->renderSort($Grid->descripcion) ?></div></th>
<?php } ?>
<?php if ($Grid->cuenta->Visible) { // cuenta ?>
        <th data-name="cuenta" class="<?= $Grid->cuenta->headerCellClass() ?>"><div id="elh_cont_reglas_cuenta" class="cont_reglas_cuenta"><?= $Grid->renderSort($Grid->cuenta) ?></div></th>
<?php } ?>
<?php if ($Grid->cargo->Visible) { // cargo ?>
        <th data-name="cargo" class="<?= $Grid->cargo->headerCellClass() ?>"><div id="elh_cont_reglas_cargo" class="cont_reglas_cargo"><?= $Grid->renderSort($Grid->cargo) ?></div></th>
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowCount, "id" => "r" . $Grid->RowCount . "_cont_reglas", "data-rowtype" => $Grid->RowType]);

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
    <?php if ($Grid->codigo->Visible) { // codigo ?>
        <td data-name="codigo" <?= $Grid->codigo->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_cont_reglas_codigo" class="form-group">
<input type="<?= $Grid->codigo->getInputTextType() ?>" data-table="cont_reglas" data-field="x_codigo" name="x<?= $Grid->RowIndex ?>_codigo" id="x<?= $Grid->RowIndex ?>_codigo" size="30" maxlength="4" placeholder="<?= HtmlEncode($Grid->codigo->getPlaceHolder()) ?>" value="<?= $Grid->codigo->EditValue ?>"<?= $Grid->codigo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->codigo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cont_reglas" data-field="x_codigo" data-hidden="1" name="o<?= $Grid->RowIndex ?>_codigo" id="o<?= $Grid->RowIndex ?>_codigo" value="<?= HtmlEncode($Grid->codigo->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_cont_reglas_codigo" class="form-group">
<span<?= $Grid->codigo->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->codigo->getDisplayValue($Grid->codigo->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_reglas" data-field="x_codigo" data-hidden="1" name="x<?= $Grid->RowIndex ?>_codigo" id="x<?= $Grid->RowIndex ?>_codigo" value="<?= HtmlEncode($Grid->codigo->CurrentValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_cont_reglas_codigo">
<span<?= $Grid->codigo->viewAttributes() ?>>
<?= $Grid->codigo->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_reglas" data-field="x_codigo" data-hidden="1" name="fcont_reglasgrid$x<?= $Grid->RowIndex ?>_codigo" id="fcont_reglasgrid$x<?= $Grid->RowIndex ?>_codigo" value="<?= HtmlEncode($Grid->codigo->FormValue) ?>">
<input type="hidden" data-table="cont_reglas" data-field="x_codigo" data-hidden="1" name="fcont_reglasgrid$o<?= $Grid->RowIndex ?>_codigo" id="fcont_reglasgrid$o<?= $Grid->RowIndex ?>_codigo" value="<?= HtmlEncode($Grid->codigo->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->descripcion->Visible) { // descripcion ?>
        <td data-name="descripcion" <?= $Grid->descripcion->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_cont_reglas_descripcion" class="form-group">
<input type="<?= $Grid->descripcion->getInputTextType() ?>" data-table="cont_reglas" data-field="x_descripcion" name="x<?= $Grid->RowIndex ?>_descripcion" id="x<?= $Grid->RowIndex ?>_descripcion" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->descripcion->getPlaceHolder()) ?>" value="<?= $Grid->descripcion->EditValue ?>"<?= $Grid->descripcion->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->descripcion->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cont_reglas" data-field="x_descripcion" data-hidden="1" name="o<?= $Grid->RowIndex ?>_descripcion" id="o<?= $Grid->RowIndex ?>_descripcion" value="<?= HtmlEncode($Grid->descripcion->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_cont_reglas_descripcion" class="form-group">
<input type="<?= $Grid->descripcion->getInputTextType() ?>" data-table="cont_reglas" data-field="x_descripcion" name="x<?= $Grid->RowIndex ?>_descripcion" id="x<?= $Grid->RowIndex ?>_descripcion" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->descripcion->getPlaceHolder()) ?>" value="<?= $Grid->descripcion->EditValue ?>"<?= $Grid->descripcion->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->descripcion->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_cont_reglas_descripcion">
<span<?= $Grid->descripcion->viewAttributes() ?>>
<?= $Grid->descripcion->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_reglas" data-field="x_descripcion" data-hidden="1" name="fcont_reglasgrid$x<?= $Grid->RowIndex ?>_descripcion" id="fcont_reglasgrid$x<?= $Grid->RowIndex ?>_descripcion" value="<?= HtmlEncode($Grid->descripcion->FormValue) ?>">
<input type="hidden" data-table="cont_reglas" data-field="x_descripcion" data-hidden="1" name="fcont_reglasgrid$o<?= $Grid->RowIndex ?>_descripcion" id="fcont_reglasgrid$o<?= $Grid->RowIndex ?>_descripcion" value="<?= HtmlEncode($Grid->descripcion->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->cuenta->Visible) { // cuenta ?>
        <td data-name="cuenta" <?= $Grid->cuenta->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_cont_reglas_cuenta" class="form-group">
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Grid->RowIndex ?>_cuenta"><?= EmptyValue(strval($Grid->cuenta->ViewValue)) ? $Language->phrase("PleaseSelect") : $Grid->cuenta->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Grid->cuenta->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Grid->cuenta->ReadOnly || $Grid->cuenta->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_cuenta',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->cuenta->getErrorMessage() ?></div>
<?= $Grid->cuenta->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_cuenta") ?>
<input type="hidden" is="selection-list" data-table="cont_reglas" data-field="x_cuenta" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Grid->cuenta->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_cuenta" id="x<?= $Grid->RowIndex ?>_cuenta" value="<?= $Grid->cuenta->CurrentValue ?>"<?= $Grid->cuenta->editAttributes() ?>>
</span>
<input type="hidden" data-table="cont_reglas" data-field="x_cuenta" data-hidden="1" name="o<?= $Grid->RowIndex ?>_cuenta" id="o<?= $Grid->RowIndex ?>_cuenta" value="<?= HtmlEncode($Grid->cuenta->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_cont_reglas_cuenta" class="form-group">
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Grid->RowIndex ?>_cuenta"><?= EmptyValue(strval($Grid->cuenta->ViewValue)) ? $Language->phrase("PleaseSelect") : $Grid->cuenta->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Grid->cuenta->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Grid->cuenta->ReadOnly || $Grid->cuenta->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_cuenta',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->cuenta->getErrorMessage() ?></div>
<?= $Grid->cuenta->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_cuenta") ?>
<input type="hidden" is="selection-list" data-table="cont_reglas" data-field="x_cuenta" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Grid->cuenta->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_cuenta" id="x<?= $Grid->RowIndex ?>_cuenta" value="<?= $Grid->cuenta->CurrentValue ?>"<?= $Grid->cuenta->editAttributes() ?>>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_cont_reglas_cuenta">
<span<?= $Grid->cuenta->viewAttributes() ?>>
<?= $Grid->cuenta->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_reglas" data-field="x_cuenta" data-hidden="1" name="fcont_reglasgrid$x<?= $Grid->RowIndex ?>_cuenta" id="fcont_reglasgrid$x<?= $Grid->RowIndex ?>_cuenta" value="<?= HtmlEncode($Grid->cuenta->FormValue) ?>">
<input type="hidden" data-table="cont_reglas" data-field="x_cuenta" data-hidden="1" name="fcont_reglasgrid$o<?= $Grid->RowIndex ?>_cuenta" id="fcont_reglasgrid$o<?= $Grid->RowIndex ?>_cuenta" value="<?= HtmlEncode($Grid->cuenta->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->cargo->Visible) { // cargo ?>
        <td data-name="cargo" <?= $Grid->cargo->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_cont_reglas_cargo" class="form-group">
<template id="tp_x<?= $Grid->RowIndex ?>_cargo">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="cont_reglas" data-field="x_cargo" name="x<?= $Grid->RowIndex ?>_cargo" id="x<?= $Grid->RowIndex ?>_cargo"<?= $Grid->cargo->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x<?= $Grid->RowIndex ?>_cargo" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x<?= $Grid->RowIndex ?>_cargo"
    name="x<?= $Grid->RowIndex ?>_cargo"
    value="<?= HtmlEncode($Grid->cargo->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x<?= $Grid->RowIndex ?>_cargo"
    data-target="dsl_x<?= $Grid->RowIndex ?>_cargo"
    data-repeatcolumn="5"
    class="form-control<?= $Grid->cargo->isInvalidClass() ?>"
    data-table="cont_reglas"
    data-field="x_cargo"
    data-value-separator="<?= $Grid->cargo->displayValueSeparatorAttribute() ?>"
    <?= $Grid->cargo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->cargo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cont_reglas" data-field="x_cargo" data-hidden="1" name="o<?= $Grid->RowIndex ?>_cargo" id="o<?= $Grid->RowIndex ?>_cargo" value="<?= HtmlEncode($Grid->cargo->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_cont_reglas_cargo" class="form-group">
<template id="tp_x<?= $Grid->RowIndex ?>_cargo">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="cont_reglas" data-field="x_cargo" name="x<?= $Grid->RowIndex ?>_cargo" id="x<?= $Grid->RowIndex ?>_cargo"<?= $Grid->cargo->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x<?= $Grid->RowIndex ?>_cargo" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x<?= $Grid->RowIndex ?>_cargo"
    name="x<?= $Grid->RowIndex ?>_cargo"
    value="<?= HtmlEncode($Grid->cargo->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x<?= $Grid->RowIndex ?>_cargo"
    data-target="dsl_x<?= $Grid->RowIndex ?>_cargo"
    data-repeatcolumn="5"
    class="form-control<?= $Grid->cargo->isInvalidClass() ?>"
    data-table="cont_reglas"
    data-field="x_cargo"
    data-value-separator="<?= $Grid->cargo->displayValueSeparatorAttribute() ?>"
    <?= $Grid->cargo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->cargo->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_cont_reglas_cargo">
<span<?= $Grid->cargo->viewAttributes() ?>>
<?= $Grid->cargo->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_reglas" data-field="x_cargo" data-hidden="1" name="fcont_reglasgrid$x<?= $Grid->RowIndex ?>_cargo" id="fcont_reglasgrid$x<?= $Grid->RowIndex ?>_cargo" value="<?= HtmlEncode($Grid->cargo->FormValue) ?>">
<input type="hidden" data-table="cont_reglas" data-field="x_cargo" data-hidden="1" name="fcont_reglasgrid$o<?= $Grid->RowIndex ?>_cargo" id="fcont_reglasgrid$o<?= $Grid->RowIndex ?>_cargo" value="<?= HtmlEncode($Grid->cargo->OldValue) ?>">
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
loadjs.ready(["fcont_reglasgrid","load"], function () {
    fcont_reglasgrid.updateLists(<?= $Grid->RowIndex ?>);
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_cont_reglas", "data-rowtype" => ROWTYPE_ADD]);
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
    <?php if ($Grid->codigo->Visible) { // codigo ?>
        <td data-name="codigo">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_cont_reglas_codigo" class="form-group cont_reglas_codigo">
<input type="<?= $Grid->codigo->getInputTextType() ?>" data-table="cont_reglas" data-field="x_codigo" name="x<?= $Grid->RowIndex ?>_codigo" id="x<?= $Grid->RowIndex ?>_codigo" size="30" maxlength="4" placeholder="<?= HtmlEncode($Grid->codigo->getPlaceHolder()) ?>" value="<?= $Grid->codigo->EditValue ?>"<?= $Grid->codigo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->codigo->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_cont_reglas_codigo" class="form-group cont_reglas_codigo">
<span<?= $Grid->codigo->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->codigo->getDisplayValue($Grid->codigo->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_reglas" data-field="x_codigo" data-hidden="1" name="x<?= $Grid->RowIndex ?>_codigo" id="x<?= $Grid->RowIndex ?>_codigo" value="<?= HtmlEncode($Grid->codigo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="cont_reglas" data-field="x_codigo" data-hidden="1" name="o<?= $Grid->RowIndex ?>_codigo" id="o<?= $Grid->RowIndex ?>_codigo" value="<?= HtmlEncode($Grid->codigo->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->descripcion->Visible) { // descripcion ?>
        <td data-name="descripcion">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_cont_reglas_descripcion" class="form-group cont_reglas_descripcion">
<input type="<?= $Grid->descripcion->getInputTextType() ?>" data-table="cont_reglas" data-field="x_descripcion" name="x<?= $Grid->RowIndex ?>_descripcion" id="x<?= $Grid->RowIndex ?>_descripcion" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->descripcion->getPlaceHolder()) ?>" value="<?= $Grid->descripcion->EditValue ?>"<?= $Grid->descripcion->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->descripcion->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_cont_reglas_descripcion" class="form-group cont_reglas_descripcion">
<span<?= $Grid->descripcion->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->descripcion->getDisplayValue($Grid->descripcion->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_reglas" data-field="x_descripcion" data-hidden="1" name="x<?= $Grid->RowIndex ?>_descripcion" id="x<?= $Grid->RowIndex ?>_descripcion" value="<?= HtmlEncode($Grid->descripcion->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="cont_reglas" data-field="x_descripcion" data-hidden="1" name="o<?= $Grid->RowIndex ?>_descripcion" id="o<?= $Grid->RowIndex ?>_descripcion" value="<?= HtmlEncode($Grid->descripcion->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->cuenta->Visible) { // cuenta ?>
        <td data-name="cuenta">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_cont_reglas_cuenta" class="form-group cont_reglas_cuenta">
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Grid->RowIndex ?>_cuenta"><?= EmptyValue(strval($Grid->cuenta->ViewValue)) ? $Language->phrase("PleaseSelect") : $Grid->cuenta->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Grid->cuenta->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Grid->cuenta->ReadOnly || $Grid->cuenta->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_cuenta',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->cuenta->getErrorMessage() ?></div>
<?= $Grid->cuenta->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_cuenta") ?>
<input type="hidden" is="selection-list" data-table="cont_reglas" data-field="x_cuenta" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Grid->cuenta->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_cuenta" id="x<?= $Grid->RowIndex ?>_cuenta" value="<?= $Grid->cuenta->CurrentValue ?>"<?= $Grid->cuenta->editAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_cont_reglas_cuenta" class="form-group cont_reglas_cuenta">
<span<?= $Grid->cuenta->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->cuenta->getDisplayValue($Grid->cuenta->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_reglas" data-field="x_cuenta" data-hidden="1" name="x<?= $Grid->RowIndex ?>_cuenta" id="x<?= $Grid->RowIndex ?>_cuenta" value="<?= HtmlEncode($Grid->cuenta->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="cont_reglas" data-field="x_cuenta" data-hidden="1" name="o<?= $Grid->RowIndex ?>_cuenta" id="o<?= $Grid->RowIndex ?>_cuenta" value="<?= HtmlEncode($Grid->cuenta->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->cargo->Visible) { // cargo ?>
        <td data-name="cargo">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_cont_reglas_cargo" class="form-group cont_reglas_cargo">
<template id="tp_x<?= $Grid->RowIndex ?>_cargo">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="cont_reglas" data-field="x_cargo" name="x<?= $Grid->RowIndex ?>_cargo" id="x<?= $Grid->RowIndex ?>_cargo"<?= $Grid->cargo->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x<?= $Grid->RowIndex ?>_cargo" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x<?= $Grid->RowIndex ?>_cargo"
    name="x<?= $Grid->RowIndex ?>_cargo"
    value="<?= HtmlEncode($Grid->cargo->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x<?= $Grid->RowIndex ?>_cargo"
    data-target="dsl_x<?= $Grid->RowIndex ?>_cargo"
    data-repeatcolumn="5"
    class="form-control<?= $Grid->cargo->isInvalidClass() ?>"
    data-table="cont_reglas"
    data-field="x_cargo"
    data-value-separator="<?= $Grid->cargo->displayValueSeparatorAttribute() ?>"
    <?= $Grid->cargo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->cargo->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_cont_reglas_cargo" class="form-group cont_reglas_cargo">
<span<?= $Grid->cargo->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->cargo->getDisplayValue($Grid->cargo->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_reglas" data-field="x_cargo" data-hidden="1" name="x<?= $Grid->RowIndex ?>_cargo" id="x<?= $Grid->RowIndex ?>_cargo" value="<?= HtmlEncode($Grid->cargo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="cont_reglas" data-field="x_cargo" data-hidden="1" name="o<?= $Grid->RowIndex ?>_cargo" id="o<?= $Grid->RowIndex ?>_cargo" value="<?= HtmlEncode($Grid->cargo->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["fcont_reglasgrid","load"], function() {
    fcont_reglasgrid.updateLists(<?= $Grid->RowIndex ?>);
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
<input type="hidden" name="detailpage" value="fcont_reglasgrid">
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
    ew.addEventHandlers("cont_reglas");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
