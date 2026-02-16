<?php

namespace PHPMaker2021\mandrake;

// Set up and run Grid object
$Grid = Container("PedidioDetalleOnlineGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fpedidio_detalle_onlinegrid;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    fpedidio_detalle_onlinegrid = new ew.Form("fpedidio_detalle_onlinegrid", "grid");
    fpedidio_detalle_onlinegrid.formKeyCountName = '<?= $Grid->FormKeyCountName ?>';

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "pedidio_detalle_online")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.pedidio_detalle_online)
        ew.vars.tables.pedidio_detalle_online = currentTable;
    fpedidio_detalle_onlinegrid.addFields([
        ["fabricante", [fields.fabricante.visible && fields.fabricante.required ? ew.Validators.required(fields.fabricante.caption) : null, ew.Validators.integer], fields.fabricante.isInvalid],
        ["articulo", [fields.articulo.visible && fields.articulo.required ? ew.Validators.required(fields.articulo.caption) : null], fields.articulo.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fpedidio_detalle_onlinegrid,
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
    fpedidio_detalle_onlinegrid.validate = function () {
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
    fpedidio_detalle_onlinegrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "fabricante", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "articulo", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    fpedidio_detalle_onlinegrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fpedidio_detalle_onlinegrid.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fpedidio_detalle_onlinegrid.lists.fabricante = <?= $Grid->fabricante->toClientList($Grid) ?>;
    fpedidio_detalle_onlinegrid.lists.articulo = <?= $Grid->articulo->toClientList($Grid) ?>;
    loadjs.done("fpedidio_detalle_onlinegrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> pedidio_detalle_online">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="fpedidio_detalle_onlinegrid" class="ew-form ew-list-form form-inline">
<div id="gmp_pedidio_detalle_online" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_pedidio_detalle_onlinegrid" class="table ew-table"><!-- .ew-table -->
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
        <th data-name="fabricante" class="<?= $Grid->fabricante->headerCellClass() ?>"><div id="elh_pedidio_detalle_online_fabricante" class="pedidio_detalle_online_fabricante"><?= $Grid->renderSort($Grid->fabricante) ?></div></th>
<?php } ?>
<?php if ($Grid->articulo->Visible) { // articulo ?>
        <th data-name="articulo" class="<?= $Grid->articulo->headerCellClass() ?>"><div id="elh_pedidio_detalle_online_articulo" class="pedidio_detalle_online_articulo"><?= $Grid->renderSort($Grid->articulo) ?></div></th>
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowCount, "id" => "r" . $Grid->RowCount . "_pedidio_detalle_online", "data-rowtype" => $Grid->RowType]);

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
<span id="el<?= $Grid->RowCount ?>_pedidio_detalle_online_fabricante" class="form-group">
<?php
$onchange = $Grid->fabricante->EditAttrs->prepend("onchange", "ew.updateOptions.call(this);");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Grid->fabricante->EditAttrs["onchange"] = "";
?>
<span id="as_x<?= $Grid->RowIndex ?>_fabricante" class="ew-auto-suggest">
    <input type="<?= $Grid->fabricante->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_fabricante" id="sv_x<?= $Grid->RowIndex ?>_fabricante" value="<?= RemoveHtml($Grid->fabricante->EditValue) ?>" size="30" placeholder="<?= HtmlEncode($Grid->fabricante->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->fabricante->getPlaceHolder()) ?>"<?= $Grid->fabricante->editAttributes() ?>>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="pedidio_detalle_online" data-field="x_fabricante" data-input="sv_x<?= $Grid->RowIndex ?>_fabricante" data-value-separator="<?= $Grid->fabricante->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_fabricante" id="x<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->CurrentValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Grid->fabricante->getErrorMessage() ?></div>
<script>
loadjs.ready(["fpedidio_detalle_onlinegrid"], function() {
    fpedidio_detalle_onlinegrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_fabricante","forceSelect":false}, ew.vars.tables.pedidio_detalle_online.fields.fabricante.autoSuggestOptions));
});
</script>
<?= $Grid->fabricante->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_fabricante") ?>
</span>
<input type="hidden" data-table="pedidio_detalle_online" data-field="x_fabricante" data-hidden="1" name="o<?= $Grid->RowIndex ?>_fabricante" id="o<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_pedidio_detalle_online_fabricante" class="form-group">
<?php
$onchange = $Grid->fabricante->EditAttrs->prepend("onchange", "ew.updateOptions.call(this);");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Grid->fabricante->EditAttrs["onchange"] = "";
?>
<span id="as_x<?= $Grid->RowIndex ?>_fabricante" class="ew-auto-suggest">
    <input type="<?= $Grid->fabricante->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_fabricante" id="sv_x<?= $Grid->RowIndex ?>_fabricante" value="<?= RemoveHtml($Grid->fabricante->EditValue) ?>" size="30" placeholder="<?= HtmlEncode($Grid->fabricante->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->fabricante->getPlaceHolder()) ?>"<?= $Grid->fabricante->editAttributes() ?>>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="pedidio_detalle_online" data-field="x_fabricante" data-input="sv_x<?= $Grid->RowIndex ?>_fabricante" data-value-separator="<?= $Grid->fabricante->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_fabricante" id="x<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->CurrentValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Grid->fabricante->getErrorMessage() ?></div>
<script>
loadjs.ready(["fpedidio_detalle_onlinegrid"], function() {
    fpedidio_detalle_onlinegrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_fabricante","forceSelect":false}, ew.vars.tables.pedidio_detalle_online.fields.fabricante.autoSuggestOptions));
});
</script>
<?= $Grid->fabricante->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_fabricante") ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_pedidio_detalle_online_fabricante">
<span<?= $Grid->fabricante->viewAttributes() ?>>
<?= $Grid->fabricante->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="pedidio_detalle_online" data-field="x_fabricante" data-hidden="1" name="fpedidio_detalle_onlinegrid$x<?= $Grid->RowIndex ?>_fabricante" id="fpedidio_detalle_onlinegrid$x<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->FormValue) ?>">
<input type="hidden" data-table="pedidio_detalle_online" data-field="x_fabricante" data-hidden="1" name="fpedidio_detalle_onlinegrid$o<?= $Grid->RowIndex ?>_fabricante" id="fpedidio_detalle_onlinegrid$o<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->articulo->Visible) { // articulo ?>
        <td data-name="articulo" <?= $Grid->articulo->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_pedidio_detalle_online_articulo" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_articulo"
        name="x<?= $Grid->RowIndex ?>_articulo"
        class="form-control ew-select<?= $Grid->articulo->isInvalidClass() ?>"
        data-select2-id="pedidio_detalle_online_x<?= $Grid->RowIndex ?>_articulo"
        data-table="pedidio_detalle_online"
        data-field="x_articulo"
        data-value-separator="<?= $Grid->articulo->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->articulo->getPlaceHolder()) ?>"
        <?= $Grid->articulo->editAttributes() ?>>
        <?= $Grid->articulo->selectOptionListHtml("x{$Grid->RowIndex}_articulo") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->articulo->getErrorMessage() ?></div>
<?= $Grid->articulo->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_articulo") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='pedidio_detalle_online_x<?= $Grid->RowIndex ?>_articulo']"),
        options = { name: "x<?= $Grid->RowIndex ?>_articulo", selectId: "pedidio_detalle_online_x<?= $Grid->RowIndex ?>_articulo", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.pedidio_detalle_online.fields.articulo.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="pedidio_detalle_online" data-field="x_articulo" data-hidden="1" name="o<?= $Grid->RowIndex ?>_articulo" id="o<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_pedidio_detalle_online_articulo" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_articulo"
        name="x<?= $Grid->RowIndex ?>_articulo"
        class="form-control ew-select<?= $Grid->articulo->isInvalidClass() ?>"
        data-select2-id="pedidio_detalle_online_x<?= $Grid->RowIndex ?>_articulo"
        data-table="pedidio_detalle_online"
        data-field="x_articulo"
        data-value-separator="<?= $Grid->articulo->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->articulo->getPlaceHolder()) ?>"
        <?= $Grid->articulo->editAttributes() ?>>
        <?= $Grid->articulo->selectOptionListHtml("x{$Grid->RowIndex}_articulo") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->articulo->getErrorMessage() ?></div>
<?= $Grid->articulo->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_articulo") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='pedidio_detalle_online_x<?= $Grid->RowIndex ?>_articulo']"),
        options = { name: "x<?= $Grid->RowIndex ?>_articulo", selectId: "pedidio_detalle_online_x<?= $Grid->RowIndex ?>_articulo", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.pedidio_detalle_online.fields.articulo.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_pedidio_detalle_online_articulo">
<span<?= $Grid->articulo->viewAttributes() ?>>
<?= $Grid->articulo->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="pedidio_detalle_online" data-field="x_articulo" data-hidden="1" name="fpedidio_detalle_onlinegrid$x<?= $Grid->RowIndex ?>_articulo" id="fpedidio_detalle_onlinegrid$x<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->FormValue) ?>">
<input type="hidden" data-table="pedidio_detalle_online" data-field="x_articulo" data-hidden="1" name="fpedidio_detalle_onlinegrid$o<?= $Grid->RowIndex ?>_articulo" id="fpedidio_detalle_onlinegrid$o<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->OldValue) ?>">
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
loadjs.ready(["fpedidio_detalle_onlinegrid","load"], function () {
    fpedidio_detalle_onlinegrid.updateLists(<?= $Grid->RowIndex ?>);
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_pedidio_detalle_online", "data-rowtype" => ROWTYPE_ADD]);
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
<span id="el$rowindex$_pedidio_detalle_online_fabricante" class="form-group pedidio_detalle_online_fabricante">
<?php
$onchange = $Grid->fabricante->EditAttrs->prepend("onchange", "ew.updateOptions.call(this);");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Grid->fabricante->EditAttrs["onchange"] = "";
?>
<span id="as_x<?= $Grid->RowIndex ?>_fabricante" class="ew-auto-suggest">
    <input type="<?= $Grid->fabricante->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_fabricante" id="sv_x<?= $Grid->RowIndex ?>_fabricante" value="<?= RemoveHtml($Grid->fabricante->EditValue) ?>" size="30" placeholder="<?= HtmlEncode($Grid->fabricante->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->fabricante->getPlaceHolder()) ?>"<?= $Grid->fabricante->editAttributes() ?>>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="pedidio_detalle_online" data-field="x_fabricante" data-input="sv_x<?= $Grid->RowIndex ?>_fabricante" data-value-separator="<?= $Grid->fabricante->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_fabricante" id="x<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->CurrentValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Grid->fabricante->getErrorMessage() ?></div>
<script>
loadjs.ready(["fpedidio_detalle_onlinegrid"], function() {
    fpedidio_detalle_onlinegrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_fabricante","forceSelect":false}, ew.vars.tables.pedidio_detalle_online.fields.fabricante.autoSuggestOptions));
});
</script>
<?= $Grid->fabricante->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_fabricante") ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_pedidio_detalle_online_fabricante" class="form-group pedidio_detalle_online_fabricante">
<span<?= $Grid->fabricante->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->fabricante->getDisplayValue($Grid->fabricante->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="pedidio_detalle_online" data-field="x_fabricante" data-hidden="1" name="x<?= $Grid->RowIndex ?>_fabricante" id="x<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="pedidio_detalle_online" data-field="x_fabricante" data-hidden="1" name="o<?= $Grid->RowIndex ?>_fabricante" id="o<?= $Grid->RowIndex ?>_fabricante" value="<?= HtmlEncode($Grid->fabricante->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->articulo->Visible) { // articulo ?>
        <td data-name="articulo">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_pedidio_detalle_online_articulo" class="form-group pedidio_detalle_online_articulo">
    <select
        id="x<?= $Grid->RowIndex ?>_articulo"
        name="x<?= $Grid->RowIndex ?>_articulo"
        class="form-control ew-select<?= $Grid->articulo->isInvalidClass() ?>"
        data-select2-id="pedidio_detalle_online_x<?= $Grid->RowIndex ?>_articulo"
        data-table="pedidio_detalle_online"
        data-field="x_articulo"
        data-value-separator="<?= $Grid->articulo->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->articulo->getPlaceHolder()) ?>"
        <?= $Grid->articulo->editAttributes() ?>>
        <?= $Grid->articulo->selectOptionListHtml("x{$Grid->RowIndex}_articulo") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->articulo->getErrorMessage() ?></div>
<?= $Grid->articulo->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_articulo") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='pedidio_detalle_online_x<?= $Grid->RowIndex ?>_articulo']"),
        options = { name: "x<?= $Grid->RowIndex ?>_articulo", selectId: "pedidio_detalle_online_x<?= $Grid->RowIndex ?>_articulo", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.pedidio_detalle_online.fields.articulo.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_pedidio_detalle_online_articulo" class="form-group pedidio_detalle_online_articulo">
<span<?= $Grid->articulo->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->articulo->getDisplayValue($Grid->articulo->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="pedidio_detalle_online" data-field="x_articulo" data-hidden="1" name="x<?= $Grid->RowIndex ?>_articulo" id="x<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="pedidio_detalle_online" data-field="x_articulo" data-hidden="1" name="o<?= $Grid->RowIndex ?>_articulo" id="o<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["fpedidio_detalle_onlinegrid","load"], function() {
    fpedidio_detalle_onlinegrid.updateLists(<?= $Grid->RowIndex ?>);
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
<input type="hidden" name="detailpage" value="fpedidio_detalle_onlinegrid">
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
    ew.addEventHandlers("pedidio_detalle_online");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
