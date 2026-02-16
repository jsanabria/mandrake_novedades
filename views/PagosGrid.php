<?php

namespace PHPMaker2021\mandrake;

// Set up and run Grid object
$Grid = Container("PagosGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fpagosgrid;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    fpagosgrid = new ew.Form("fpagosgrid", "grid");
    fpagosgrid.formKeyCountName = '<?= $Grid->FormKeyCountName ?>';

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "pagos")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.pagos)
        ew.vars.tables.pagos = currentTable;
    fpagosgrid.addFields([
        ["tipo_pago", [fields.tipo_pago.visible && fields.tipo_pago.required ? ew.Validators.required(fields.tipo_pago.caption) : null], fields.tipo_pago.isInvalid],
        ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null, ew.Validators.datetime(0)], fields.fecha.isInvalid],
        ["referencia", [fields.referencia.visible && fields.referencia.required ? ew.Validators.required(fields.referencia.caption) : null], fields.referencia.isInvalid],
        ["monto", [fields.monto.visible && fields.monto.required ? ew.Validators.required(fields.monto.caption) : null, ew.Validators.float], fields.monto.isInvalid],
        ["comprobante_pago", [fields.comprobante_pago.visible && fields.comprobante_pago.required ? ew.Validators.fileRequired(fields.comprobante_pago.caption) : null], fields.comprobante_pago.isInvalid],
        ["comprobante", [fields.comprobante.visible && fields.comprobante.required ? ew.Validators.required(fields.comprobante.caption) : null, ew.Validators.integer], fields.comprobante.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fpagosgrid,
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
    fpagosgrid.validate = function () {
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
    fpagosgrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "tipo_pago", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "fecha", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "referencia", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "monto", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "comprobante_pago", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "comprobante", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    fpagosgrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fpagosgrid.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fpagosgrid.lists.tipo_pago = <?= $Grid->tipo_pago->toClientList($Grid) ?>;
    loadjs.done("fpagosgrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> pagos">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="fpagosgrid" class="ew-form ew-list-form form-inline">
<div id="gmp_pagos" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_pagosgrid" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Grid->tipo_pago->Visible) { // tipo_pago ?>
        <th data-name="tipo_pago" class="<?= $Grid->tipo_pago->headerCellClass() ?>"><div id="elh_pagos_tipo_pago" class="pagos_tipo_pago"><?= $Grid->renderSort($Grid->tipo_pago) ?></div></th>
<?php } ?>
<?php if ($Grid->fecha->Visible) { // fecha ?>
        <th data-name="fecha" class="<?= $Grid->fecha->headerCellClass() ?>"><div id="elh_pagos_fecha" class="pagos_fecha"><?= $Grid->renderSort($Grid->fecha) ?></div></th>
<?php } ?>
<?php if ($Grid->referencia->Visible) { // referencia ?>
        <th data-name="referencia" class="<?= $Grid->referencia->headerCellClass() ?>"><div id="elh_pagos_referencia" class="pagos_referencia"><?= $Grid->renderSort($Grid->referencia) ?></div></th>
<?php } ?>
<?php if ($Grid->monto->Visible) { // monto ?>
        <th data-name="monto" class="<?= $Grid->monto->headerCellClass() ?>"><div id="elh_pagos_monto" class="pagos_monto"><?= $Grid->renderSort($Grid->monto) ?></div></th>
<?php } ?>
<?php if ($Grid->comprobante_pago->Visible) { // comprobante_pago ?>
        <th data-name="comprobante_pago" class="<?= $Grid->comprobante_pago->headerCellClass() ?>"><div id="elh_pagos_comprobante_pago" class="pagos_comprobante_pago"><?= $Grid->renderSort($Grid->comprobante_pago) ?></div></th>
<?php } ?>
<?php if ($Grid->comprobante->Visible) { // comprobante ?>
        <th data-name="comprobante" class="<?= $Grid->comprobante->headerCellClass() ?>"><div id="elh_pagos_comprobante" class="pagos_comprobante"><?= $Grid->renderSort($Grid->comprobante) ?></div></th>
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowCount, "id" => "r" . $Grid->RowCount . "_pagos", "data-rowtype" => $Grid->RowType]);

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
    <?php if ($Grid->tipo_pago->Visible) { // tipo_pago ?>
        <td data-name="tipo_pago" <?= $Grid->tipo_pago->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_pagos_tipo_pago" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_tipo_pago"
        name="x<?= $Grid->RowIndex ?>_tipo_pago"
        class="form-control ew-select<?= $Grid->tipo_pago->isInvalidClass() ?>"
        data-select2-id="pagos_x<?= $Grid->RowIndex ?>_tipo_pago"
        data-table="pagos"
        data-field="x_tipo_pago"
        data-value-separator="<?= $Grid->tipo_pago->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->tipo_pago->getPlaceHolder()) ?>"
        <?= $Grid->tipo_pago->editAttributes() ?>>
        <?= $Grid->tipo_pago->selectOptionListHtml("x{$Grid->RowIndex}_tipo_pago") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->tipo_pago->getErrorMessage() ?></div>
<?= $Grid->tipo_pago->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_tipo_pago") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='pagos_x<?= $Grid->RowIndex ?>_tipo_pago']"),
        options = { name: "x<?= $Grid->RowIndex ?>_tipo_pago", selectId: "pagos_x<?= $Grid->RowIndex ?>_tipo_pago", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.pagos.fields.tipo_pago.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="pagos" data-field="x_tipo_pago" data-hidden="1" name="o<?= $Grid->RowIndex ?>_tipo_pago" id="o<?= $Grid->RowIndex ?>_tipo_pago" value="<?= HtmlEncode($Grid->tipo_pago->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_pagos_tipo_pago" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_tipo_pago"
        name="x<?= $Grid->RowIndex ?>_tipo_pago"
        class="form-control ew-select<?= $Grid->tipo_pago->isInvalidClass() ?>"
        data-select2-id="pagos_x<?= $Grid->RowIndex ?>_tipo_pago"
        data-table="pagos"
        data-field="x_tipo_pago"
        data-value-separator="<?= $Grid->tipo_pago->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->tipo_pago->getPlaceHolder()) ?>"
        <?= $Grid->tipo_pago->editAttributes() ?>>
        <?= $Grid->tipo_pago->selectOptionListHtml("x{$Grid->RowIndex}_tipo_pago") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->tipo_pago->getErrorMessage() ?></div>
<?= $Grid->tipo_pago->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_tipo_pago") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='pagos_x<?= $Grid->RowIndex ?>_tipo_pago']"),
        options = { name: "x<?= $Grid->RowIndex ?>_tipo_pago", selectId: "pagos_x<?= $Grid->RowIndex ?>_tipo_pago", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.pagos.fields.tipo_pago.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_pagos_tipo_pago">
<span<?= $Grid->tipo_pago->viewAttributes() ?>>
<?= $Grid->tipo_pago->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="pagos" data-field="x_tipo_pago" data-hidden="1" name="fpagosgrid$x<?= $Grid->RowIndex ?>_tipo_pago" id="fpagosgrid$x<?= $Grid->RowIndex ?>_tipo_pago" value="<?= HtmlEncode($Grid->tipo_pago->FormValue) ?>">
<input type="hidden" data-table="pagos" data-field="x_tipo_pago" data-hidden="1" name="fpagosgrid$o<?= $Grid->RowIndex ?>_tipo_pago" id="fpagosgrid$o<?= $Grid->RowIndex ?>_tipo_pago" value="<?= HtmlEncode($Grid->tipo_pago->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->fecha->Visible) { // fecha ?>
        <td data-name="fecha" <?= $Grid->fecha->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_pagos_fecha" class="form-group">
<input type="<?= $Grid->fecha->getInputTextType() ?>" data-table="pagos" data-field="x_fecha" name="x<?= $Grid->RowIndex ?>_fecha" id="x<?= $Grid->RowIndex ?>_fecha" placeholder="<?= HtmlEncode($Grid->fecha->getPlaceHolder()) ?>" value="<?= $Grid->fecha->EditValue ?>"<?= $Grid->fecha->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->fecha->getErrorMessage() ?></div>
<?php if (!$Grid->fecha->ReadOnly && !$Grid->fecha->Disabled && !isset($Grid->fecha->EditAttrs["readonly"]) && !isset($Grid->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpagosgrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fpagosgrid", "x<?= $Grid->RowIndex ?>_fecha", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="pagos" data-field="x_fecha" data-hidden="1" name="o<?= $Grid->RowIndex ?>_fecha" id="o<?= $Grid->RowIndex ?>_fecha" value="<?= HtmlEncode($Grid->fecha->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_pagos_fecha" class="form-group">
<input type="<?= $Grid->fecha->getInputTextType() ?>" data-table="pagos" data-field="x_fecha" name="x<?= $Grid->RowIndex ?>_fecha" id="x<?= $Grid->RowIndex ?>_fecha" placeholder="<?= HtmlEncode($Grid->fecha->getPlaceHolder()) ?>" value="<?= $Grid->fecha->EditValue ?>"<?= $Grid->fecha->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->fecha->getErrorMessage() ?></div>
<?php if (!$Grid->fecha->ReadOnly && !$Grid->fecha->Disabled && !isset($Grid->fecha->EditAttrs["readonly"]) && !isset($Grid->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpagosgrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fpagosgrid", "x<?= $Grid->RowIndex ?>_fecha", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_pagos_fecha">
<span<?= $Grid->fecha->viewAttributes() ?>>
<?= $Grid->fecha->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="pagos" data-field="x_fecha" data-hidden="1" name="fpagosgrid$x<?= $Grid->RowIndex ?>_fecha" id="fpagosgrid$x<?= $Grid->RowIndex ?>_fecha" value="<?= HtmlEncode($Grid->fecha->FormValue) ?>">
<input type="hidden" data-table="pagos" data-field="x_fecha" data-hidden="1" name="fpagosgrid$o<?= $Grid->RowIndex ?>_fecha" id="fpagosgrid$o<?= $Grid->RowIndex ?>_fecha" value="<?= HtmlEncode($Grid->fecha->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->referencia->Visible) { // referencia ?>
        <td data-name="referencia" <?= $Grid->referencia->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_pagos_referencia" class="form-group">
<input type="<?= $Grid->referencia->getInputTextType() ?>" data-table="pagos" data-field="x_referencia" name="x<?= $Grid->RowIndex ?>_referencia" id="x<?= $Grid->RowIndex ?>_referencia" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->referencia->getPlaceHolder()) ?>" value="<?= $Grid->referencia->EditValue ?>"<?= $Grid->referencia->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->referencia->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="pagos" data-field="x_referencia" data-hidden="1" name="o<?= $Grid->RowIndex ?>_referencia" id="o<?= $Grid->RowIndex ?>_referencia" value="<?= HtmlEncode($Grid->referencia->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_pagos_referencia" class="form-group">
<input type="<?= $Grid->referencia->getInputTextType() ?>" data-table="pagos" data-field="x_referencia" name="x<?= $Grid->RowIndex ?>_referencia" id="x<?= $Grid->RowIndex ?>_referencia" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->referencia->getPlaceHolder()) ?>" value="<?= $Grid->referencia->EditValue ?>"<?= $Grid->referencia->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->referencia->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_pagos_referencia">
<span<?= $Grid->referencia->viewAttributes() ?>>
<?= $Grid->referencia->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="pagos" data-field="x_referencia" data-hidden="1" name="fpagosgrid$x<?= $Grid->RowIndex ?>_referencia" id="fpagosgrid$x<?= $Grid->RowIndex ?>_referencia" value="<?= HtmlEncode($Grid->referencia->FormValue) ?>">
<input type="hidden" data-table="pagos" data-field="x_referencia" data-hidden="1" name="fpagosgrid$o<?= $Grid->RowIndex ?>_referencia" id="fpagosgrid$o<?= $Grid->RowIndex ?>_referencia" value="<?= HtmlEncode($Grid->referencia->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->monto->Visible) { // monto ?>
        <td data-name="monto" <?= $Grid->monto->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_pagos_monto" class="form-group">
<input type="<?= $Grid->monto->getInputTextType() ?>" data-table="pagos" data-field="x_monto" name="x<?= $Grid->RowIndex ?>_monto" id="x<?= $Grid->RowIndex ?>_monto" size="30" maxlength="14" placeholder="<?= HtmlEncode($Grid->monto->getPlaceHolder()) ?>" value="<?= $Grid->monto->EditValue ?>"<?= $Grid->monto->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="pagos" data-field="x_monto" data-hidden="1" name="o<?= $Grid->RowIndex ?>_monto" id="o<?= $Grid->RowIndex ?>_monto" value="<?= HtmlEncode($Grid->monto->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_pagos_monto" class="form-group">
<input type="<?= $Grid->monto->getInputTextType() ?>" data-table="pagos" data-field="x_monto" name="x<?= $Grid->RowIndex ?>_monto" id="x<?= $Grid->RowIndex ?>_monto" size="30" maxlength="14" placeholder="<?= HtmlEncode($Grid->monto->getPlaceHolder()) ?>" value="<?= $Grid->monto->EditValue ?>"<?= $Grid->monto->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_pagos_monto">
<span<?= $Grid->monto->viewAttributes() ?>>
<?= $Grid->monto->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="pagos" data-field="x_monto" data-hidden="1" name="fpagosgrid$x<?= $Grid->RowIndex ?>_monto" id="fpagosgrid$x<?= $Grid->RowIndex ?>_monto" value="<?= HtmlEncode($Grid->monto->FormValue) ?>">
<input type="hidden" data-table="pagos" data-field="x_monto" data-hidden="1" name="fpagosgrid$o<?= $Grid->RowIndex ?>_monto" id="fpagosgrid$o<?= $Grid->RowIndex ?>_monto" value="<?= HtmlEncode($Grid->monto->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->comprobante_pago->Visible) { // comprobante_pago ?>
        <td data-name="comprobante_pago" <?= $Grid->comprobante_pago->cellAttributes() ?>>
<?php if ($Grid->RowAction == "insert") { // Add record ?>
<span id="el$rowindex$_pagos_comprobante_pago" class="form-group pagos_comprobante_pago">
<div id="fd_x<?= $Grid->RowIndex ?>_comprobante_pago">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Grid->comprobante_pago->title() ?>" data-table="pagos" data-field="x_comprobante_pago" name="x<?= $Grid->RowIndex ?>_comprobante_pago" id="x<?= $Grid->RowIndex ?>_comprobante_pago" lang="<?= CurrentLanguageID() ?>"<?= $Grid->comprobante_pago->editAttributes() ?><?= ($Grid->comprobante_pago->ReadOnly || $Grid->comprobante_pago->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Grid->RowIndex ?>_comprobante_pago"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->comprobante_pago->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_comprobante_pago" id= "fn_x<?= $Grid->RowIndex ?>_comprobante_pago" value="<?= $Grid->comprobante_pago->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_comprobante_pago" id= "fa_x<?= $Grid->RowIndex ?>_comprobante_pago" value="0">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_comprobante_pago" id= "fs_x<?= $Grid->RowIndex ?>_comprobante_pago" value="255">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_comprobante_pago" id= "fx_x<?= $Grid->RowIndex ?>_comprobante_pago" value="<?= $Grid->comprobante_pago->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_comprobante_pago" id= "fm_x<?= $Grid->RowIndex ?>_comprobante_pago" value="<?= $Grid->comprobante_pago->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Grid->RowIndex ?>_comprobante_pago" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="pagos" data-field="x_comprobante_pago" data-hidden="1" name="o<?= $Grid->RowIndex ?>_comprobante_pago" id="o<?= $Grid->RowIndex ?>_comprobante_pago" value="<?= HtmlEncode($Grid->comprobante_pago->OldValue) ?>">
<?php } elseif ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_pagos_comprobante_pago">
<span>
<?= GetFileViewTag($Grid->comprobante_pago, $Grid->comprobante_pago->getViewValue(), false) ?>
</span>
</span>
<?php } else  { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_pagos_comprobante_pago" class="form-group pagos_comprobante_pago">
<div id="fd_x<?= $Grid->RowIndex ?>_comprobante_pago">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Grid->comprobante_pago->title() ?>" data-table="pagos" data-field="x_comprobante_pago" name="x<?= $Grid->RowIndex ?>_comprobante_pago" id="x<?= $Grid->RowIndex ?>_comprobante_pago" lang="<?= CurrentLanguageID() ?>"<?= $Grid->comprobante_pago->editAttributes() ?><?= ($Grid->comprobante_pago->ReadOnly || $Grid->comprobante_pago->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Grid->RowIndex ?>_comprobante_pago"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->comprobante_pago->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_comprobante_pago" id= "fn_x<?= $Grid->RowIndex ?>_comprobante_pago" value="<?= $Grid->comprobante_pago->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_comprobante_pago" id= "fa_x<?= $Grid->RowIndex ?>_comprobante_pago" value="<?= (Post("fa_x<?= $Grid->RowIndex ?>_comprobante_pago") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_comprobante_pago" id= "fs_x<?= $Grid->RowIndex ?>_comprobante_pago" value="255">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_comprobante_pago" id= "fx_x<?= $Grid->RowIndex ?>_comprobante_pago" value="<?= $Grid->comprobante_pago->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_comprobante_pago" id= "fm_x<?= $Grid->RowIndex ?>_comprobante_pago" value="<?= $Grid->comprobante_pago->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Grid->RowIndex ?>_comprobante_pago" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->comprobante->Visible) { // comprobante ?>
        <td data-name="comprobante" <?= $Grid->comprobante->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_pagos_comprobante" class="form-group">
<input type="<?= $Grid->comprobante->getInputTextType() ?>" data-table="pagos" data-field="x_comprobante" name="x<?= $Grid->RowIndex ?>_comprobante" id="x<?= $Grid->RowIndex ?>_comprobante" size="30" placeholder="<?= HtmlEncode($Grid->comprobante->getPlaceHolder()) ?>" value="<?= $Grid->comprobante->EditValue ?>"<?= $Grid->comprobante->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->comprobante->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="pagos" data-field="x_comprobante" data-hidden="1" name="o<?= $Grid->RowIndex ?>_comprobante" id="o<?= $Grid->RowIndex ?>_comprobante" value="<?= HtmlEncode($Grid->comprobante->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_pagos_comprobante" class="form-group">
<input type="<?= $Grid->comprobante->getInputTextType() ?>" data-table="pagos" data-field="x_comprobante" name="x<?= $Grid->RowIndex ?>_comprobante" id="x<?= $Grid->RowIndex ?>_comprobante" size="30" placeholder="<?= HtmlEncode($Grid->comprobante->getPlaceHolder()) ?>" value="<?= $Grid->comprobante->EditValue ?>"<?= $Grid->comprobante->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->comprobante->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_pagos_comprobante">
<span<?= $Grid->comprobante->viewAttributes() ?>>
<?= $Grid->comprobante->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="pagos" data-field="x_comprobante" data-hidden="1" name="fpagosgrid$x<?= $Grid->RowIndex ?>_comprobante" id="fpagosgrid$x<?= $Grid->RowIndex ?>_comprobante" value="<?= HtmlEncode($Grid->comprobante->FormValue) ?>">
<input type="hidden" data-table="pagos" data-field="x_comprobante" data-hidden="1" name="fpagosgrid$o<?= $Grid->RowIndex ?>_comprobante" id="fpagosgrid$o<?= $Grid->RowIndex ?>_comprobante" value="<?= HtmlEncode($Grid->comprobante->OldValue) ?>">
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
loadjs.ready(["fpagosgrid","load"], function () {
    fpagosgrid.updateLists(<?= $Grid->RowIndex ?>);
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_pagos", "data-rowtype" => ROWTYPE_ADD]);
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
    <?php if ($Grid->tipo_pago->Visible) { // tipo_pago ?>
        <td data-name="tipo_pago">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_pagos_tipo_pago" class="form-group pagos_tipo_pago">
    <select
        id="x<?= $Grid->RowIndex ?>_tipo_pago"
        name="x<?= $Grid->RowIndex ?>_tipo_pago"
        class="form-control ew-select<?= $Grid->tipo_pago->isInvalidClass() ?>"
        data-select2-id="pagos_x<?= $Grid->RowIndex ?>_tipo_pago"
        data-table="pagos"
        data-field="x_tipo_pago"
        data-value-separator="<?= $Grid->tipo_pago->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->tipo_pago->getPlaceHolder()) ?>"
        <?= $Grid->tipo_pago->editAttributes() ?>>
        <?= $Grid->tipo_pago->selectOptionListHtml("x{$Grid->RowIndex}_tipo_pago") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->tipo_pago->getErrorMessage() ?></div>
<?= $Grid->tipo_pago->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_tipo_pago") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='pagos_x<?= $Grid->RowIndex ?>_tipo_pago']"),
        options = { name: "x<?= $Grid->RowIndex ?>_tipo_pago", selectId: "pagos_x<?= $Grid->RowIndex ?>_tipo_pago", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.pagos.fields.tipo_pago.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_pagos_tipo_pago" class="form-group pagos_tipo_pago">
<span<?= $Grid->tipo_pago->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->tipo_pago->getDisplayValue($Grid->tipo_pago->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="pagos" data-field="x_tipo_pago" data-hidden="1" name="x<?= $Grid->RowIndex ?>_tipo_pago" id="x<?= $Grid->RowIndex ?>_tipo_pago" value="<?= HtmlEncode($Grid->tipo_pago->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="pagos" data-field="x_tipo_pago" data-hidden="1" name="o<?= $Grid->RowIndex ?>_tipo_pago" id="o<?= $Grid->RowIndex ?>_tipo_pago" value="<?= HtmlEncode($Grid->tipo_pago->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->fecha->Visible) { // fecha ?>
        <td data-name="fecha">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_pagos_fecha" class="form-group pagos_fecha">
<input type="<?= $Grid->fecha->getInputTextType() ?>" data-table="pagos" data-field="x_fecha" name="x<?= $Grid->RowIndex ?>_fecha" id="x<?= $Grid->RowIndex ?>_fecha" placeholder="<?= HtmlEncode($Grid->fecha->getPlaceHolder()) ?>" value="<?= $Grid->fecha->EditValue ?>"<?= $Grid->fecha->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->fecha->getErrorMessage() ?></div>
<?php if (!$Grid->fecha->ReadOnly && !$Grid->fecha->Disabled && !isset($Grid->fecha->EditAttrs["readonly"]) && !isset($Grid->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpagosgrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fpagosgrid", "x<?= $Grid->RowIndex ?>_fecha", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_pagos_fecha" class="form-group pagos_fecha">
<span<?= $Grid->fecha->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->fecha->getDisplayValue($Grid->fecha->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="pagos" data-field="x_fecha" data-hidden="1" name="x<?= $Grid->RowIndex ?>_fecha" id="x<?= $Grid->RowIndex ?>_fecha" value="<?= HtmlEncode($Grid->fecha->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="pagos" data-field="x_fecha" data-hidden="1" name="o<?= $Grid->RowIndex ?>_fecha" id="o<?= $Grid->RowIndex ?>_fecha" value="<?= HtmlEncode($Grid->fecha->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->referencia->Visible) { // referencia ?>
        <td data-name="referencia">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_pagos_referencia" class="form-group pagos_referencia">
<input type="<?= $Grid->referencia->getInputTextType() ?>" data-table="pagos" data-field="x_referencia" name="x<?= $Grid->RowIndex ?>_referencia" id="x<?= $Grid->RowIndex ?>_referencia" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->referencia->getPlaceHolder()) ?>" value="<?= $Grid->referencia->EditValue ?>"<?= $Grid->referencia->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->referencia->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_pagos_referencia" class="form-group pagos_referencia">
<span<?= $Grid->referencia->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->referencia->getDisplayValue($Grid->referencia->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="pagos" data-field="x_referencia" data-hidden="1" name="x<?= $Grid->RowIndex ?>_referencia" id="x<?= $Grid->RowIndex ?>_referencia" value="<?= HtmlEncode($Grid->referencia->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="pagos" data-field="x_referencia" data-hidden="1" name="o<?= $Grid->RowIndex ?>_referencia" id="o<?= $Grid->RowIndex ?>_referencia" value="<?= HtmlEncode($Grid->referencia->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->monto->Visible) { // monto ?>
        <td data-name="monto">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_pagos_monto" class="form-group pagos_monto">
<input type="<?= $Grid->monto->getInputTextType() ?>" data-table="pagos" data-field="x_monto" name="x<?= $Grid->RowIndex ?>_monto" id="x<?= $Grid->RowIndex ?>_monto" size="30" maxlength="14" placeholder="<?= HtmlEncode($Grid->monto->getPlaceHolder()) ?>" value="<?= $Grid->monto->EditValue ?>"<?= $Grid->monto->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_pagos_monto" class="form-group pagos_monto">
<span<?= $Grid->monto->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->monto->getDisplayValue($Grid->monto->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="pagos" data-field="x_monto" data-hidden="1" name="x<?= $Grid->RowIndex ?>_monto" id="x<?= $Grid->RowIndex ?>_monto" value="<?= HtmlEncode($Grid->monto->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="pagos" data-field="x_monto" data-hidden="1" name="o<?= $Grid->RowIndex ?>_monto" id="o<?= $Grid->RowIndex ?>_monto" value="<?= HtmlEncode($Grid->monto->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->comprobante_pago->Visible) { // comprobante_pago ?>
        <td data-name="comprobante_pago">
<span id="el$rowindex$_pagos_comprobante_pago" class="form-group pagos_comprobante_pago">
<div id="fd_x<?= $Grid->RowIndex ?>_comprobante_pago">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Grid->comprobante_pago->title() ?>" data-table="pagos" data-field="x_comprobante_pago" name="x<?= $Grid->RowIndex ?>_comprobante_pago" id="x<?= $Grid->RowIndex ?>_comprobante_pago" lang="<?= CurrentLanguageID() ?>"<?= $Grid->comprobante_pago->editAttributes() ?><?= ($Grid->comprobante_pago->ReadOnly || $Grid->comprobante_pago->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Grid->RowIndex ?>_comprobante_pago"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->comprobante_pago->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_comprobante_pago" id= "fn_x<?= $Grid->RowIndex ?>_comprobante_pago" value="<?= $Grid->comprobante_pago->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_comprobante_pago" id= "fa_x<?= $Grid->RowIndex ?>_comprobante_pago" value="0">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_comprobante_pago" id= "fs_x<?= $Grid->RowIndex ?>_comprobante_pago" value="255">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_comprobante_pago" id= "fx_x<?= $Grid->RowIndex ?>_comprobante_pago" value="<?= $Grid->comprobante_pago->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_comprobante_pago" id= "fm_x<?= $Grid->RowIndex ?>_comprobante_pago" value="<?= $Grid->comprobante_pago->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Grid->RowIndex ?>_comprobante_pago" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="pagos" data-field="x_comprobante_pago" data-hidden="1" name="o<?= $Grid->RowIndex ?>_comprobante_pago" id="o<?= $Grid->RowIndex ?>_comprobante_pago" value="<?= HtmlEncode($Grid->comprobante_pago->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->comprobante->Visible) { // comprobante ?>
        <td data-name="comprobante">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_pagos_comprobante" class="form-group pagos_comprobante">
<input type="<?= $Grid->comprobante->getInputTextType() ?>" data-table="pagos" data-field="x_comprobante" name="x<?= $Grid->RowIndex ?>_comprobante" id="x<?= $Grid->RowIndex ?>_comprobante" size="30" placeholder="<?= HtmlEncode($Grid->comprobante->getPlaceHolder()) ?>" value="<?= $Grid->comprobante->EditValue ?>"<?= $Grid->comprobante->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->comprobante->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_pagos_comprobante" class="form-group pagos_comprobante">
<span<?= $Grid->comprobante->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->comprobante->getDisplayValue($Grid->comprobante->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="pagos" data-field="x_comprobante" data-hidden="1" name="x<?= $Grid->RowIndex ?>_comprobante" id="x<?= $Grid->RowIndex ?>_comprobante" value="<?= HtmlEncode($Grid->comprobante->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="pagos" data-field="x_comprobante" data-hidden="1" name="o<?= $Grid->RowIndex ?>_comprobante" id="o<?= $Grid->RowIndex ?>_comprobante" value="<?= HtmlEncode($Grid->comprobante->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["fpagosgrid","load"], function() {
    fpagosgrid.updateLists(<?= $Grid->RowIndex ?>);
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
<input type="hidden" name="detailpage" value="fpagosgrid">
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
    ew.addEventHandlers("pagos");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
