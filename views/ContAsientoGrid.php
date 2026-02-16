<?php

namespace PHPMaker2021\mandrake;

// Set up and run Grid object
$Grid = Container("ContAsientoGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fcont_asientogrid;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    fcont_asientogrid = new ew.Form("fcont_asientogrid", "grid");
    fcont_asientogrid.formKeyCountName = '<?= $Grid->FormKeyCountName ?>';

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "cont_asiento")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.cont_asiento)
        ew.vars.tables.cont_asiento = currentTable;
    fcont_asientogrid.addFields([
        ["cuenta", [fields.cuenta.visible && fields.cuenta.required ? ew.Validators.required(fields.cuenta.caption) : null], fields.cuenta.isInvalid],
        ["nota", [fields.nota.visible && fields.nota.required ? ew.Validators.required(fields.nota.caption) : null], fields.nota.isInvalid],
        ["referencia", [fields.referencia.visible && fields.referencia.required ? ew.Validators.required(fields.referencia.caption) : null], fields.referencia.isInvalid],
        ["debe", [fields.debe.visible && fields.debe.required ? ew.Validators.required(fields.debe.caption) : null, ew.Validators.float], fields.debe.isInvalid],
        ["haber", [fields.haber.visible && fields.haber.required ? ew.Validators.required(fields.haber.caption) : null, ew.Validators.float], fields.haber.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fcont_asientogrid,
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
    fcont_asientogrid.validate = function () {
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
    fcont_asientogrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "cuenta", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "nota", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "referencia", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "debe", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "haber", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    fcont_asientogrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fcont_asientogrid.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fcont_asientogrid.lists.cuenta = <?= $Grid->cuenta->toClientList($Grid) ?>;
    loadjs.done("fcont_asientogrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> cont_asiento">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="fcont_asientogrid" class="ew-form ew-list-form form-inline">
<div id="gmp_cont_asiento" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_cont_asientogrid" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Grid->cuenta->Visible) { // cuenta ?>
        <th data-name="cuenta" class="<?= $Grid->cuenta->headerCellClass() ?>"><div id="elh_cont_asiento_cuenta" class="cont_asiento_cuenta"><?= $Grid->renderSort($Grid->cuenta) ?></div></th>
<?php } ?>
<?php if ($Grid->nota->Visible) { // nota ?>
        <th data-name="nota" class="<?= $Grid->nota->headerCellClass() ?>"><div id="elh_cont_asiento_nota" class="cont_asiento_nota"><?= $Grid->renderSort($Grid->nota) ?></div></th>
<?php } ?>
<?php if ($Grid->referencia->Visible) { // referencia ?>
        <th data-name="referencia" class="<?= $Grid->referencia->headerCellClass() ?>"><div id="elh_cont_asiento_referencia" class="cont_asiento_referencia"><?= $Grid->renderSort($Grid->referencia) ?></div></th>
<?php } ?>
<?php if ($Grid->debe->Visible) { // debe ?>
        <th data-name="debe" class="<?= $Grid->debe->headerCellClass() ?>"><div id="elh_cont_asiento_debe" class="cont_asiento_debe"><?= $Grid->renderSort($Grid->debe) ?></div></th>
<?php } ?>
<?php if ($Grid->haber->Visible) { // haber ?>
        <th data-name="haber" class="<?= $Grid->haber->headerCellClass() ?>"><div id="elh_cont_asiento_haber" class="cont_asiento_haber"><?= $Grid->renderSort($Grid->haber) ?></div></th>
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowCount, "id" => "r" . $Grid->RowCount . "_cont_asiento", "data-rowtype" => $Grid->RowType]);

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
    <?php if ($Grid->cuenta->Visible) { // cuenta ?>
        <td data-name="cuenta" <?= $Grid->cuenta->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_cont_asiento_cuenta" class="form-group">
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Grid->RowIndex ?>_cuenta"><?= EmptyValue(strval($Grid->cuenta->ViewValue)) ? $Language->phrase("PleaseSelect") : $Grid->cuenta->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Grid->cuenta->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Grid->cuenta->ReadOnly || $Grid->cuenta->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_cuenta',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->cuenta->getErrorMessage() ?></div>
<?= $Grid->cuenta->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_cuenta") ?>
<input type="hidden" is="selection-list" data-table="cont_asiento" data-field="x_cuenta" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Grid->cuenta->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_cuenta" id="x<?= $Grid->RowIndex ?>_cuenta" value="<?= $Grid->cuenta->CurrentValue ?>"<?= $Grid->cuenta->editAttributes() ?>>
</span>
<input type="hidden" data-table="cont_asiento" data-field="x_cuenta" data-hidden="1" name="o<?= $Grid->RowIndex ?>_cuenta" id="o<?= $Grid->RowIndex ?>_cuenta" value="<?= HtmlEncode($Grid->cuenta->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_cont_asiento_cuenta" class="form-group">
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Grid->RowIndex ?>_cuenta"><?= EmptyValue(strval($Grid->cuenta->ViewValue)) ? $Language->phrase("PleaseSelect") : $Grid->cuenta->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Grid->cuenta->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Grid->cuenta->ReadOnly || $Grid->cuenta->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_cuenta',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->cuenta->getErrorMessage() ?></div>
<?= $Grid->cuenta->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_cuenta") ?>
<input type="hidden" is="selection-list" data-table="cont_asiento" data-field="x_cuenta" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Grid->cuenta->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_cuenta" id="x<?= $Grid->RowIndex ?>_cuenta" value="<?= $Grid->cuenta->CurrentValue ?>"<?= $Grid->cuenta->editAttributes() ?>>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_cont_asiento_cuenta">
<span<?= $Grid->cuenta->viewAttributes() ?>>
<?= $Grid->cuenta->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_asiento" data-field="x_cuenta" data-hidden="1" name="fcont_asientogrid$x<?= $Grid->RowIndex ?>_cuenta" id="fcont_asientogrid$x<?= $Grid->RowIndex ?>_cuenta" value="<?= HtmlEncode($Grid->cuenta->FormValue) ?>">
<input type="hidden" data-table="cont_asiento" data-field="x_cuenta" data-hidden="1" name="fcont_asientogrid$o<?= $Grid->RowIndex ?>_cuenta" id="fcont_asientogrid$o<?= $Grid->RowIndex ?>_cuenta" value="<?= HtmlEncode($Grid->cuenta->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->nota->Visible) { // nota ?>
        <td data-name="nota" <?= $Grid->nota->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_cont_asiento_nota" class="form-group">
<input type="<?= $Grid->nota->getInputTextType() ?>" data-table="cont_asiento" data-field="x_nota" name="x<?= $Grid->RowIndex ?>_nota" id="x<?= $Grid->RowIndex ?>_nota" size="10" placeholder="<?= HtmlEncode($Grid->nota->getPlaceHolder()) ?>" value="<?= $Grid->nota->EditValue ?>"<?= $Grid->nota->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->nota->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cont_asiento" data-field="x_nota" data-hidden="1" name="o<?= $Grid->RowIndex ?>_nota" id="o<?= $Grid->RowIndex ?>_nota" value="<?= HtmlEncode($Grid->nota->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_cont_asiento_nota" class="form-group">
<input type="<?= $Grid->nota->getInputTextType() ?>" data-table="cont_asiento" data-field="x_nota" name="x<?= $Grid->RowIndex ?>_nota" id="x<?= $Grid->RowIndex ?>_nota" size="10" placeholder="<?= HtmlEncode($Grid->nota->getPlaceHolder()) ?>" value="<?= $Grid->nota->EditValue ?>"<?= $Grid->nota->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->nota->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_cont_asiento_nota">
<span<?= $Grid->nota->viewAttributes() ?>>
<?= $Grid->nota->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_asiento" data-field="x_nota" data-hidden="1" name="fcont_asientogrid$x<?= $Grid->RowIndex ?>_nota" id="fcont_asientogrid$x<?= $Grid->RowIndex ?>_nota" value="<?= HtmlEncode($Grid->nota->FormValue) ?>">
<input type="hidden" data-table="cont_asiento" data-field="x_nota" data-hidden="1" name="fcont_asientogrid$o<?= $Grid->RowIndex ?>_nota" id="fcont_asientogrid$o<?= $Grid->RowIndex ?>_nota" value="<?= HtmlEncode($Grid->nota->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->referencia->Visible) { // referencia ?>
        <td data-name="referencia" <?= $Grid->referencia->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_cont_asiento_referencia" class="form-group">
<input type="<?= $Grid->referencia->getInputTextType() ?>" data-table="cont_asiento" data-field="x_referencia" name="x<?= $Grid->RowIndex ?>_referencia" id="x<?= $Grid->RowIndex ?>_referencia" size="10" maxlength="25" placeholder="<?= HtmlEncode($Grid->referencia->getPlaceHolder()) ?>" value="<?= $Grid->referencia->EditValue ?>"<?= $Grid->referencia->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->referencia->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cont_asiento" data-field="x_referencia" data-hidden="1" name="o<?= $Grid->RowIndex ?>_referencia" id="o<?= $Grid->RowIndex ?>_referencia" value="<?= HtmlEncode($Grid->referencia->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_cont_asiento_referencia" class="form-group">
<input type="<?= $Grid->referencia->getInputTextType() ?>" data-table="cont_asiento" data-field="x_referencia" name="x<?= $Grid->RowIndex ?>_referencia" id="x<?= $Grid->RowIndex ?>_referencia" size="10" maxlength="25" placeholder="<?= HtmlEncode($Grid->referencia->getPlaceHolder()) ?>" value="<?= $Grid->referencia->EditValue ?>"<?= $Grid->referencia->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->referencia->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_cont_asiento_referencia">
<span<?= $Grid->referencia->viewAttributes() ?>>
<?= $Grid->referencia->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_asiento" data-field="x_referencia" data-hidden="1" name="fcont_asientogrid$x<?= $Grid->RowIndex ?>_referencia" id="fcont_asientogrid$x<?= $Grid->RowIndex ?>_referencia" value="<?= HtmlEncode($Grid->referencia->FormValue) ?>">
<input type="hidden" data-table="cont_asiento" data-field="x_referencia" data-hidden="1" name="fcont_asientogrid$o<?= $Grid->RowIndex ?>_referencia" id="fcont_asientogrid$o<?= $Grid->RowIndex ?>_referencia" value="<?= HtmlEncode($Grid->referencia->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->debe->Visible) { // debe ?>
        <td data-name="debe" <?= $Grid->debe->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_cont_asiento_debe" class="form-group">
<input type="<?= $Grid->debe->getInputTextType() ?>" data-table="cont_asiento" data-field="x_debe" name="x<?= $Grid->RowIndex ?>_debe" id="x<?= $Grid->RowIndex ?>_debe" size="12" maxlength="16" placeholder="<?= HtmlEncode($Grid->debe->getPlaceHolder()) ?>" value="<?= $Grid->debe->EditValue ?>"<?= $Grid->debe->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->debe->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cont_asiento" data-field="x_debe" data-hidden="1" name="o<?= $Grid->RowIndex ?>_debe" id="o<?= $Grid->RowIndex ?>_debe" value="<?= HtmlEncode($Grid->debe->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_cont_asiento_debe" class="form-group">
<input type="<?= $Grid->debe->getInputTextType() ?>" data-table="cont_asiento" data-field="x_debe" name="x<?= $Grid->RowIndex ?>_debe" id="x<?= $Grid->RowIndex ?>_debe" size="12" maxlength="16" placeholder="<?= HtmlEncode($Grid->debe->getPlaceHolder()) ?>" value="<?= $Grid->debe->EditValue ?>"<?= $Grid->debe->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->debe->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_cont_asiento_debe">
<span<?= $Grid->debe->viewAttributes() ?>>
<?= $Grid->debe->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_asiento" data-field="x_debe" data-hidden="1" name="fcont_asientogrid$x<?= $Grid->RowIndex ?>_debe" id="fcont_asientogrid$x<?= $Grid->RowIndex ?>_debe" value="<?= HtmlEncode($Grid->debe->FormValue) ?>">
<input type="hidden" data-table="cont_asiento" data-field="x_debe" data-hidden="1" name="fcont_asientogrid$o<?= $Grid->RowIndex ?>_debe" id="fcont_asientogrid$o<?= $Grid->RowIndex ?>_debe" value="<?= HtmlEncode($Grid->debe->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->haber->Visible) { // haber ?>
        <td data-name="haber" <?= $Grid->haber->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_cont_asiento_haber" class="form-group">
<input type="<?= $Grid->haber->getInputTextType() ?>" data-table="cont_asiento" data-field="x_haber" name="x<?= $Grid->RowIndex ?>_haber" id="x<?= $Grid->RowIndex ?>_haber" size="12" maxlength="16" placeholder="<?= HtmlEncode($Grid->haber->getPlaceHolder()) ?>" value="<?= $Grid->haber->EditValue ?>"<?= $Grid->haber->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->haber->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cont_asiento" data-field="x_haber" data-hidden="1" name="o<?= $Grid->RowIndex ?>_haber" id="o<?= $Grid->RowIndex ?>_haber" value="<?= HtmlEncode($Grid->haber->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_cont_asiento_haber" class="form-group">
<input type="<?= $Grid->haber->getInputTextType() ?>" data-table="cont_asiento" data-field="x_haber" name="x<?= $Grid->RowIndex ?>_haber" id="x<?= $Grid->RowIndex ?>_haber" size="12" maxlength="16" placeholder="<?= HtmlEncode($Grid->haber->getPlaceHolder()) ?>" value="<?= $Grid->haber->EditValue ?>"<?= $Grid->haber->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->haber->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_cont_asiento_haber">
<span<?= $Grid->haber->viewAttributes() ?>>
<?= $Grid->haber->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_asiento" data-field="x_haber" data-hidden="1" name="fcont_asientogrid$x<?= $Grid->RowIndex ?>_haber" id="fcont_asientogrid$x<?= $Grid->RowIndex ?>_haber" value="<?= HtmlEncode($Grid->haber->FormValue) ?>">
<input type="hidden" data-table="cont_asiento" data-field="x_haber" data-hidden="1" name="fcont_asientogrid$o<?= $Grid->RowIndex ?>_haber" id="fcont_asientogrid$o<?= $Grid->RowIndex ?>_haber" value="<?= HtmlEncode($Grid->haber->OldValue) ?>">
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
loadjs.ready(["fcont_asientogrid","load"], function () {
    fcont_asientogrid.updateLists(<?= $Grid->RowIndex ?>);
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_cont_asiento", "data-rowtype" => ROWTYPE_ADD]);
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
    <?php if ($Grid->cuenta->Visible) { // cuenta ?>
        <td data-name="cuenta">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_cont_asiento_cuenta" class="form-group cont_asiento_cuenta">
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Grid->RowIndex ?>_cuenta"><?= EmptyValue(strval($Grid->cuenta->ViewValue)) ? $Language->phrase("PleaseSelect") : $Grid->cuenta->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Grid->cuenta->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Grid->cuenta->ReadOnly || $Grid->cuenta->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_cuenta',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->cuenta->getErrorMessage() ?></div>
<?= $Grid->cuenta->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_cuenta") ?>
<input type="hidden" is="selection-list" data-table="cont_asiento" data-field="x_cuenta" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Grid->cuenta->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_cuenta" id="x<?= $Grid->RowIndex ?>_cuenta" value="<?= $Grid->cuenta->CurrentValue ?>"<?= $Grid->cuenta->editAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_cont_asiento_cuenta" class="form-group cont_asiento_cuenta">
<span<?= $Grid->cuenta->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->cuenta->getDisplayValue($Grid->cuenta->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_asiento" data-field="x_cuenta" data-hidden="1" name="x<?= $Grid->RowIndex ?>_cuenta" id="x<?= $Grid->RowIndex ?>_cuenta" value="<?= HtmlEncode($Grid->cuenta->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="cont_asiento" data-field="x_cuenta" data-hidden="1" name="o<?= $Grid->RowIndex ?>_cuenta" id="o<?= $Grid->RowIndex ?>_cuenta" value="<?= HtmlEncode($Grid->cuenta->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->nota->Visible) { // nota ?>
        <td data-name="nota">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_cont_asiento_nota" class="form-group cont_asiento_nota">
<input type="<?= $Grid->nota->getInputTextType() ?>" data-table="cont_asiento" data-field="x_nota" name="x<?= $Grid->RowIndex ?>_nota" id="x<?= $Grid->RowIndex ?>_nota" size="10" placeholder="<?= HtmlEncode($Grid->nota->getPlaceHolder()) ?>" value="<?= $Grid->nota->EditValue ?>"<?= $Grid->nota->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->nota->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_cont_asiento_nota" class="form-group cont_asiento_nota">
<span<?= $Grid->nota->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->nota->getDisplayValue($Grid->nota->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_asiento" data-field="x_nota" data-hidden="1" name="x<?= $Grid->RowIndex ?>_nota" id="x<?= $Grid->RowIndex ?>_nota" value="<?= HtmlEncode($Grid->nota->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="cont_asiento" data-field="x_nota" data-hidden="1" name="o<?= $Grid->RowIndex ?>_nota" id="o<?= $Grid->RowIndex ?>_nota" value="<?= HtmlEncode($Grid->nota->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->referencia->Visible) { // referencia ?>
        <td data-name="referencia">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_cont_asiento_referencia" class="form-group cont_asiento_referencia">
<input type="<?= $Grid->referencia->getInputTextType() ?>" data-table="cont_asiento" data-field="x_referencia" name="x<?= $Grid->RowIndex ?>_referencia" id="x<?= $Grid->RowIndex ?>_referencia" size="10" maxlength="25" placeholder="<?= HtmlEncode($Grid->referencia->getPlaceHolder()) ?>" value="<?= $Grid->referencia->EditValue ?>"<?= $Grid->referencia->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->referencia->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_cont_asiento_referencia" class="form-group cont_asiento_referencia">
<span<?= $Grid->referencia->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->referencia->getDisplayValue($Grid->referencia->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_asiento" data-field="x_referencia" data-hidden="1" name="x<?= $Grid->RowIndex ?>_referencia" id="x<?= $Grid->RowIndex ?>_referencia" value="<?= HtmlEncode($Grid->referencia->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="cont_asiento" data-field="x_referencia" data-hidden="1" name="o<?= $Grid->RowIndex ?>_referencia" id="o<?= $Grid->RowIndex ?>_referencia" value="<?= HtmlEncode($Grid->referencia->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->debe->Visible) { // debe ?>
        <td data-name="debe">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_cont_asiento_debe" class="form-group cont_asiento_debe">
<input type="<?= $Grid->debe->getInputTextType() ?>" data-table="cont_asiento" data-field="x_debe" name="x<?= $Grid->RowIndex ?>_debe" id="x<?= $Grid->RowIndex ?>_debe" size="12" maxlength="16" placeholder="<?= HtmlEncode($Grid->debe->getPlaceHolder()) ?>" value="<?= $Grid->debe->EditValue ?>"<?= $Grid->debe->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->debe->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_cont_asiento_debe" class="form-group cont_asiento_debe">
<span<?= $Grid->debe->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->debe->getDisplayValue($Grid->debe->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_asiento" data-field="x_debe" data-hidden="1" name="x<?= $Grid->RowIndex ?>_debe" id="x<?= $Grid->RowIndex ?>_debe" value="<?= HtmlEncode($Grid->debe->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="cont_asiento" data-field="x_debe" data-hidden="1" name="o<?= $Grid->RowIndex ?>_debe" id="o<?= $Grid->RowIndex ?>_debe" value="<?= HtmlEncode($Grid->debe->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->haber->Visible) { // haber ?>
        <td data-name="haber">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_cont_asiento_haber" class="form-group cont_asiento_haber">
<input type="<?= $Grid->haber->getInputTextType() ?>" data-table="cont_asiento" data-field="x_haber" name="x<?= $Grid->RowIndex ?>_haber" id="x<?= $Grid->RowIndex ?>_haber" size="12" maxlength="16" placeholder="<?= HtmlEncode($Grid->haber->getPlaceHolder()) ?>" value="<?= $Grid->haber->EditValue ?>"<?= $Grid->haber->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->haber->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_cont_asiento_haber" class="form-group cont_asiento_haber">
<span<?= $Grid->haber->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->haber->getDisplayValue($Grid->haber->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_asiento" data-field="x_haber" data-hidden="1" name="x<?= $Grid->RowIndex ?>_haber" id="x<?= $Grid->RowIndex ?>_haber" value="<?= HtmlEncode($Grid->haber->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="cont_asiento" data-field="x_haber" data-hidden="1" name="o<?= $Grid->RowIndex ?>_haber" id="o<?= $Grid->RowIndex ?>_haber" value="<?= HtmlEncode($Grid->haber->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["fcont_asientogrid","load"], function() {
    fcont_asientogrid.updateLists(<?= $Grid->RowIndex ?>);
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
<input type="hidden" name="detailpage" value="fcont_asientogrid">
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
    ew.addEventHandlers("cont_asiento");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    // Write your table-specific startup script here
    // document.write("page loaded");
    $(document).ready(function() { 
    	var ButtonGroup = $('.ewButtonGroup'); 
    	ButtonGroup.hide(); 
    });
    $("#cmbContab").click(function(){
    	var id = <?php echo $_REQUEST["fk_id"]; ?>;
    	var username = "<?php echo CurrentUserName(); ?>";
    	if(confirm("Seguro de contabilizar este comprobante?")) {
    		$.ajax({
    		  url : "include/Contabilizar_Procesar.php",
    		  type: "GET",
    		  data : {id: id, username: username},
    		  beforeSend: function(){
    		    $("#result").html("Por Favor Espere. . .");
    		  }
    		})
    		.done(function(data) {
    			//alert(data);
    			var rs = '<div class="alert alert-success" role="alert">Este Comprobante se Contabiliz&oacute; Exitosamente.</div>';
    			$("#result").html(rs);
    		})
    		.fail(function(data) {
    			alert( "error" + data );
    		})
    		.always(function(data) {
    			//alert( "complete" );
    			//$("#result").html("Espere. . . ");
    		});
    	}
    });
});
</script>
<?php } ?>
