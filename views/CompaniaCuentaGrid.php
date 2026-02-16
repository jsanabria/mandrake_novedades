<?php

namespace PHPMaker2021\mandrake;

// Set up and run Grid object
$Grid = Container("CompaniaCuentaGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fcompania_cuentagrid;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    fcompania_cuentagrid = new ew.Form("fcompania_cuentagrid", "grid");
    fcompania_cuentagrid.formKeyCountName = '<?= $Grid->FormKeyCountName ?>';

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "compania_cuenta")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.compania_cuenta)
        ew.vars.tables.compania_cuenta = currentTable;
    fcompania_cuentagrid.addFields([
        ["banco", [fields.banco.visible && fields.banco.required ? ew.Validators.required(fields.banco.caption) : null], fields.banco.isInvalid],
        ["titular", [fields.titular.visible && fields.titular.required ? ew.Validators.required(fields.titular.caption) : null], fields.titular.isInvalid],
        ["tipo", [fields.tipo.visible && fields.tipo.required ? ew.Validators.required(fields.tipo.caption) : null], fields.tipo.isInvalid],
        ["numero", [fields.numero.visible && fields.numero.required ? ew.Validators.required(fields.numero.caption) : null], fields.numero.isInvalid],
        ["mostrar", [fields.mostrar.visible && fields.mostrar.required ? ew.Validators.required(fields.mostrar.caption) : null], fields.mostrar.isInvalid],
        ["cuenta", [fields.cuenta.visible && fields.cuenta.required ? ew.Validators.required(fields.cuenta.caption) : null], fields.cuenta.isInvalid],
        ["pago_electronico", [fields.pago_electronico.visible && fields.pago_electronico.required ? ew.Validators.required(fields.pago_electronico.caption) : null], fields.pago_electronico.isInvalid],
        ["activo", [fields.activo.visible && fields.activo.required ? ew.Validators.required(fields.activo.caption) : null], fields.activo.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fcompania_cuentagrid,
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
    fcompania_cuentagrid.validate = function () {
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
    fcompania_cuentagrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "banco", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "titular", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "tipo", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "numero", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "mostrar", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "cuenta", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "pago_electronico", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "activo", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    fcompania_cuentagrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fcompania_cuentagrid.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fcompania_cuentagrid.lists.banco = <?= $Grid->banco->toClientList($Grid) ?>;
    fcompania_cuentagrid.lists.tipo = <?= $Grid->tipo->toClientList($Grid) ?>;
    fcompania_cuentagrid.lists.mostrar = <?= $Grid->mostrar->toClientList($Grid) ?>;
    fcompania_cuentagrid.lists.cuenta = <?= $Grid->cuenta->toClientList($Grid) ?>;
    fcompania_cuentagrid.lists.pago_electronico = <?= $Grid->pago_electronico->toClientList($Grid) ?>;
    fcompania_cuentagrid.lists.activo = <?= $Grid->activo->toClientList($Grid) ?>;
    loadjs.done("fcompania_cuentagrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> compania_cuenta">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="fcompania_cuentagrid" class="ew-form ew-list-form form-inline">
<div id="gmp_compania_cuenta" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_compania_cuentagrid" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Grid->banco->Visible) { // banco ?>
        <th data-name="banco" class="<?= $Grid->banco->headerCellClass() ?>"><div id="elh_compania_cuenta_banco" class="compania_cuenta_banco"><?= $Grid->renderSort($Grid->banco) ?></div></th>
<?php } ?>
<?php if ($Grid->titular->Visible) { // titular ?>
        <th data-name="titular" class="<?= $Grid->titular->headerCellClass() ?>"><div id="elh_compania_cuenta_titular" class="compania_cuenta_titular"><?= $Grid->renderSort($Grid->titular) ?></div></th>
<?php } ?>
<?php if ($Grid->tipo->Visible) { // tipo ?>
        <th data-name="tipo" class="<?= $Grid->tipo->headerCellClass() ?>"><div id="elh_compania_cuenta_tipo" class="compania_cuenta_tipo"><?= $Grid->renderSort($Grid->tipo) ?></div></th>
<?php } ?>
<?php if ($Grid->numero->Visible) { // numero ?>
        <th data-name="numero" class="<?= $Grid->numero->headerCellClass() ?>"><div id="elh_compania_cuenta_numero" class="compania_cuenta_numero"><?= $Grid->renderSort($Grid->numero) ?></div></th>
<?php } ?>
<?php if ($Grid->mostrar->Visible) { // mostrar ?>
        <th data-name="mostrar" class="<?= $Grid->mostrar->headerCellClass() ?>"><div id="elh_compania_cuenta_mostrar" class="compania_cuenta_mostrar"><?= $Grid->renderSort($Grid->mostrar) ?></div></th>
<?php } ?>
<?php if ($Grid->cuenta->Visible) { // cuenta ?>
        <th data-name="cuenta" class="<?= $Grid->cuenta->headerCellClass() ?>"><div id="elh_compania_cuenta_cuenta" class="compania_cuenta_cuenta"><?= $Grid->renderSort($Grid->cuenta) ?></div></th>
<?php } ?>
<?php if ($Grid->pago_electronico->Visible) { // pago_electronico ?>
        <th data-name="pago_electronico" class="<?= $Grid->pago_electronico->headerCellClass() ?>"><div id="elh_compania_cuenta_pago_electronico" class="compania_cuenta_pago_electronico"><?= $Grid->renderSort($Grid->pago_electronico) ?></div></th>
<?php } ?>
<?php if ($Grid->activo->Visible) { // activo ?>
        <th data-name="activo" class="<?= $Grid->activo->headerCellClass() ?>"><div id="elh_compania_cuenta_activo" class="compania_cuenta_activo"><?= $Grid->renderSort($Grid->activo) ?></div></th>
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowCount, "id" => "r" . $Grid->RowCount . "_compania_cuenta", "data-rowtype" => $Grid->RowType]);

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
    <?php if ($Grid->banco->Visible) { // banco ?>
        <td data-name="banco" <?= $Grid->banco->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_compania_cuenta_banco" class="form-group">
<?php
$onchange = $Grid->banco->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Grid->banco->EditAttrs["onchange"] = "";
?>
<span id="as_x<?= $Grid->RowIndex ?>_banco" class="ew-auto-suggest">
    <div class="input-group flex-nowrap">
        <input type="<?= $Grid->banco->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_banco" id="sv_x<?= $Grid->RowIndex ?>_banco" value="<?= RemoveHtml($Grid->banco->EditValue) ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->banco->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->banco->getPlaceHolder()) ?>"<?= $Grid->banco->editAttributes() ?>>
        <div class="input-group-append">
            <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Grid->banco->caption()), $Language->phrase("LookupLink", true))) ?>" onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_banco',m:0,n:10,srch:false});" class="ew-lookup-btn btn btn-default"<?= ($Grid->banco->ReadOnly || $Grid->banco->Disabled) ? " disabled" : "" ?>><i class="fas fa-search ew-icon"></i></button>
        </div>
    </div>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="compania_cuenta" data-field="x_banco" data-input="sv_x<?= $Grid->RowIndex ?>_banco" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Grid->banco->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_banco" id="x<?= $Grid->RowIndex ?>_banco" value="<?= HtmlEncode($Grid->banco->CurrentValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Grid->banco->getErrorMessage() ?></div>
<script>
loadjs.ready(["fcompania_cuentagrid"], function() {
    fcompania_cuentagrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_banco","forceSelect":true}, ew.vars.tables.compania_cuenta.fields.banco.autoSuggestOptions));
});
</script>
<?= $Grid->banco->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_banco") ?>
</span>
<input type="hidden" data-table="compania_cuenta" data-field="x_banco" data-hidden="1" name="o<?= $Grid->RowIndex ?>_banco" id="o<?= $Grid->RowIndex ?>_banco" value="<?= HtmlEncode($Grid->banco->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_compania_cuenta_banco" class="form-group">
<?php
$onchange = $Grid->banco->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Grid->banco->EditAttrs["onchange"] = "";
?>
<span id="as_x<?= $Grid->RowIndex ?>_banco" class="ew-auto-suggest">
    <div class="input-group flex-nowrap">
        <input type="<?= $Grid->banco->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_banco" id="sv_x<?= $Grid->RowIndex ?>_banco" value="<?= RemoveHtml($Grid->banco->EditValue) ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->banco->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->banco->getPlaceHolder()) ?>"<?= $Grid->banco->editAttributes() ?>>
        <div class="input-group-append">
            <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Grid->banco->caption()), $Language->phrase("LookupLink", true))) ?>" onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_banco',m:0,n:10,srch:false});" class="ew-lookup-btn btn btn-default"<?= ($Grid->banco->ReadOnly || $Grid->banco->Disabled) ? " disabled" : "" ?>><i class="fas fa-search ew-icon"></i></button>
        </div>
    </div>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="compania_cuenta" data-field="x_banco" data-input="sv_x<?= $Grid->RowIndex ?>_banco" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Grid->banco->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_banco" id="x<?= $Grid->RowIndex ?>_banco" value="<?= HtmlEncode($Grid->banco->CurrentValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Grid->banco->getErrorMessage() ?></div>
<script>
loadjs.ready(["fcompania_cuentagrid"], function() {
    fcompania_cuentagrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_banco","forceSelect":true}, ew.vars.tables.compania_cuenta.fields.banco.autoSuggestOptions));
});
</script>
<?= $Grid->banco->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_banco") ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_compania_cuenta_banco">
<span<?= $Grid->banco->viewAttributes() ?>>
<?= $Grid->banco->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="compania_cuenta" data-field="x_banco" data-hidden="1" name="fcompania_cuentagrid$x<?= $Grid->RowIndex ?>_banco" id="fcompania_cuentagrid$x<?= $Grid->RowIndex ?>_banco" value="<?= HtmlEncode($Grid->banco->FormValue) ?>">
<input type="hidden" data-table="compania_cuenta" data-field="x_banco" data-hidden="1" name="fcompania_cuentagrid$o<?= $Grid->RowIndex ?>_banco" id="fcompania_cuentagrid$o<?= $Grid->RowIndex ?>_banco" value="<?= HtmlEncode($Grid->banco->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->titular->Visible) { // titular ?>
        <td data-name="titular" <?= $Grid->titular->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_compania_cuenta_titular" class="form-group">
<input type="<?= $Grid->titular->getInputTextType() ?>" data-table="compania_cuenta" data-field="x_titular" name="x<?= $Grid->RowIndex ?>_titular" id="x<?= $Grid->RowIndex ?>_titular" size="30" maxlength="80" placeholder="<?= HtmlEncode($Grid->titular->getPlaceHolder()) ?>" value="<?= $Grid->titular->EditValue ?>"<?= $Grid->titular->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->titular->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="compania_cuenta" data-field="x_titular" data-hidden="1" name="o<?= $Grid->RowIndex ?>_titular" id="o<?= $Grid->RowIndex ?>_titular" value="<?= HtmlEncode($Grid->titular->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_compania_cuenta_titular" class="form-group">
<input type="<?= $Grid->titular->getInputTextType() ?>" data-table="compania_cuenta" data-field="x_titular" name="x<?= $Grid->RowIndex ?>_titular" id="x<?= $Grid->RowIndex ?>_titular" size="30" maxlength="80" placeholder="<?= HtmlEncode($Grid->titular->getPlaceHolder()) ?>" value="<?= $Grid->titular->EditValue ?>"<?= $Grid->titular->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->titular->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_compania_cuenta_titular">
<span<?= $Grid->titular->viewAttributes() ?>>
<?= $Grid->titular->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="compania_cuenta" data-field="x_titular" data-hidden="1" name="fcompania_cuentagrid$x<?= $Grid->RowIndex ?>_titular" id="fcompania_cuentagrid$x<?= $Grid->RowIndex ?>_titular" value="<?= HtmlEncode($Grid->titular->FormValue) ?>">
<input type="hidden" data-table="compania_cuenta" data-field="x_titular" data-hidden="1" name="fcompania_cuentagrid$o<?= $Grid->RowIndex ?>_titular" id="fcompania_cuentagrid$o<?= $Grid->RowIndex ?>_titular" value="<?= HtmlEncode($Grid->titular->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->tipo->Visible) { // tipo ?>
        <td data-name="tipo" <?= $Grid->tipo->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_compania_cuenta_tipo" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_tipo"
        name="x<?= $Grid->RowIndex ?>_tipo"
        class="form-control ew-select<?= $Grid->tipo->isInvalidClass() ?>"
        data-select2-id="compania_cuenta_x<?= $Grid->RowIndex ?>_tipo"
        data-table="compania_cuenta"
        data-field="x_tipo"
        data-value-separator="<?= $Grid->tipo->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->tipo->getPlaceHolder()) ?>"
        <?= $Grid->tipo->editAttributes() ?>>
        <?= $Grid->tipo->selectOptionListHtml("x{$Grid->RowIndex}_tipo") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->tipo->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='compania_cuenta_x<?= $Grid->RowIndex ?>_tipo']"),
        options = { name: "x<?= $Grid->RowIndex ?>_tipo", selectId: "compania_cuenta_x<?= $Grid->RowIndex ?>_tipo", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.compania_cuenta.fields.tipo.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.compania_cuenta.fields.tipo.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="compania_cuenta" data-field="x_tipo" data-hidden="1" name="o<?= $Grid->RowIndex ?>_tipo" id="o<?= $Grid->RowIndex ?>_tipo" value="<?= HtmlEncode($Grid->tipo->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_compania_cuenta_tipo" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_tipo"
        name="x<?= $Grid->RowIndex ?>_tipo"
        class="form-control ew-select<?= $Grid->tipo->isInvalidClass() ?>"
        data-select2-id="compania_cuenta_x<?= $Grid->RowIndex ?>_tipo"
        data-table="compania_cuenta"
        data-field="x_tipo"
        data-value-separator="<?= $Grid->tipo->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->tipo->getPlaceHolder()) ?>"
        <?= $Grid->tipo->editAttributes() ?>>
        <?= $Grid->tipo->selectOptionListHtml("x{$Grid->RowIndex}_tipo") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->tipo->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='compania_cuenta_x<?= $Grid->RowIndex ?>_tipo']"),
        options = { name: "x<?= $Grid->RowIndex ?>_tipo", selectId: "compania_cuenta_x<?= $Grid->RowIndex ?>_tipo", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.compania_cuenta.fields.tipo.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.compania_cuenta.fields.tipo.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_compania_cuenta_tipo">
<span<?= $Grid->tipo->viewAttributes() ?>>
<?= $Grid->tipo->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="compania_cuenta" data-field="x_tipo" data-hidden="1" name="fcompania_cuentagrid$x<?= $Grid->RowIndex ?>_tipo" id="fcompania_cuentagrid$x<?= $Grid->RowIndex ?>_tipo" value="<?= HtmlEncode($Grid->tipo->FormValue) ?>">
<input type="hidden" data-table="compania_cuenta" data-field="x_tipo" data-hidden="1" name="fcompania_cuentagrid$o<?= $Grid->RowIndex ?>_tipo" id="fcompania_cuentagrid$o<?= $Grid->RowIndex ?>_tipo" value="<?= HtmlEncode($Grid->tipo->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->numero->Visible) { // numero ?>
        <td data-name="numero" <?= $Grid->numero->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_compania_cuenta_numero" class="form-group">
<input type="<?= $Grid->numero->getInputTextType() ?>" data-table="compania_cuenta" data-field="x_numero" name="x<?= $Grid->RowIndex ?>_numero" id="x<?= $Grid->RowIndex ?>_numero" size="30" maxlength="40" placeholder="<?= HtmlEncode($Grid->numero->getPlaceHolder()) ?>" value="<?= $Grid->numero->EditValue ?>"<?= $Grid->numero->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->numero->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="compania_cuenta" data-field="x_numero" data-hidden="1" name="o<?= $Grid->RowIndex ?>_numero" id="o<?= $Grid->RowIndex ?>_numero" value="<?= HtmlEncode($Grid->numero->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_compania_cuenta_numero" class="form-group">
<input type="<?= $Grid->numero->getInputTextType() ?>" data-table="compania_cuenta" data-field="x_numero" name="x<?= $Grid->RowIndex ?>_numero" id="x<?= $Grid->RowIndex ?>_numero" size="30" maxlength="40" placeholder="<?= HtmlEncode($Grid->numero->getPlaceHolder()) ?>" value="<?= $Grid->numero->EditValue ?>"<?= $Grid->numero->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->numero->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_compania_cuenta_numero">
<span<?= $Grid->numero->viewAttributes() ?>>
<?= $Grid->numero->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="compania_cuenta" data-field="x_numero" data-hidden="1" name="fcompania_cuentagrid$x<?= $Grid->RowIndex ?>_numero" id="fcompania_cuentagrid$x<?= $Grid->RowIndex ?>_numero" value="<?= HtmlEncode($Grid->numero->FormValue) ?>">
<input type="hidden" data-table="compania_cuenta" data-field="x_numero" data-hidden="1" name="fcompania_cuentagrid$o<?= $Grid->RowIndex ?>_numero" id="fcompania_cuentagrid$o<?= $Grid->RowIndex ?>_numero" value="<?= HtmlEncode($Grid->numero->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->mostrar->Visible) { // mostrar ?>
        <td data-name="mostrar" <?= $Grid->mostrar->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_compania_cuenta_mostrar" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_mostrar"
        name="x<?= $Grid->RowIndex ?>_mostrar"
        class="form-control ew-select<?= $Grid->mostrar->isInvalidClass() ?>"
        data-select2-id="compania_cuenta_x<?= $Grid->RowIndex ?>_mostrar"
        data-table="compania_cuenta"
        data-field="x_mostrar"
        data-value-separator="<?= $Grid->mostrar->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->mostrar->getPlaceHolder()) ?>"
        <?= $Grid->mostrar->editAttributes() ?>>
        <?= $Grid->mostrar->selectOptionListHtml("x{$Grid->RowIndex}_mostrar") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->mostrar->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='compania_cuenta_x<?= $Grid->RowIndex ?>_mostrar']"),
        options = { name: "x<?= $Grid->RowIndex ?>_mostrar", selectId: "compania_cuenta_x<?= $Grid->RowIndex ?>_mostrar", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.compania_cuenta.fields.mostrar.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.compania_cuenta.fields.mostrar.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="compania_cuenta" data-field="x_mostrar" data-hidden="1" name="o<?= $Grid->RowIndex ?>_mostrar" id="o<?= $Grid->RowIndex ?>_mostrar" value="<?= HtmlEncode($Grid->mostrar->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_compania_cuenta_mostrar" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_mostrar"
        name="x<?= $Grid->RowIndex ?>_mostrar"
        class="form-control ew-select<?= $Grid->mostrar->isInvalidClass() ?>"
        data-select2-id="compania_cuenta_x<?= $Grid->RowIndex ?>_mostrar"
        data-table="compania_cuenta"
        data-field="x_mostrar"
        data-value-separator="<?= $Grid->mostrar->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->mostrar->getPlaceHolder()) ?>"
        <?= $Grid->mostrar->editAttributes() ?>>
        <?= $Grid->mostrar->selectOptionListHtml("x{$Grid->RowIndex}_mostrar") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->mostrar->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='compania_cuenta_x<?= $Grid->RowIndex ?>_mostrar']"),
        options = { name: "x<?= $Grid->RowIndex ?>_mostrar", selectId: "compania_cuenta_x<?= $Grid->RowIndex ?>_mostrar", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.compania_cuenta.fields.mostrar.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.compania_cuenta.fields.mostrar.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_compania_cuenta_mostrar">
<span<?= $Grid->mostrar->viewAttributes() ?>>
<?= $Grid->mostrar->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="compania_cuenta" data-field="x_mostrar" data-hidden="1" name="fcompania_cuentagrid$x<?= $Grid->RowIndex ?>_mostrar" id="fcompania_cuentagrid$x<?= $Grid->RowIndex ?>_mostrar" value="<?= HtmlEncode($Grid->mostrar->FormValue) ?>">
<input type="hidden" data-table="compania_cuenta" data-field="x_mostrar" data-hidden="1" name="fcompania_cuentagrid$o<?= $Grid->RowIndex ?>_mostrar" id="fcompania_cuentagrid$o<?= $Grid->RowIndex ?>_mostrar" value="<?= HtmlEncode($Grid->mostrar->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->cuenta->Visible) { // cuenta ?>
        <td data-name="cuenta" <?= $Grid->cuenta->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_compania_cuenta_cuenta" class="form-group">
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Grid->RowIndex ?>_cuenta"><?= EmptyValue(strval($Grid->cuenta->ViewValue)) ? $Language->phrase("PleaseSelect") : $Grid->cuenta->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Grid->cuenta->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Grid->cuenta->ReadOnly || $Grid->cuenta->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_cuenta',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->cuenta->getErrorMessage() ?></div>
<?= $Grid->cuenta->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_cuenta") ?>
<input type="hidden" is="selection-list" data-table="compania_cuenta" data-field="x_cuenta" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Grid->cuenta->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_cuenta" id="x<?= $Grid->RowIndex ?>_cuenta" value="<?= $Grid->cuenta->CurrentValue ?>"<?= $Grid->cuenta->editAttributes() ?>>
</span>
<input type="hidden" data-table="compania_cuenta" data-field="x_cuenta" data-hidden="1" name="o<?= $Grid->RowIndex ?>_cuenta" id="o<?= $Grid->RowIndex ?>_cuenta" value="<?= HtmlEncode($Grid->cuenta->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_compania_cuenta_cuenta" class="form-group">
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Grid->RowIndex ?>_cuenta"><?= EmptyValue(strval($Grid->cuenta->ViewValue)) ? $Language->phrase("PleaseSelect") : $Grid->cuenta->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Grid->cuenta->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Grid->cuenta->ReadOnly || $Grid->cuenta->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_cuenta',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->cuenta->getErrorMessage() ?></div>
<?= $Grid->cuenta->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_cuenta") ?>
<input type="hidden" is="selection-list" data-table="compania_cuenta" data-field="x_cuenta" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Grid->cuenta->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_cuenta" id="x<?= $Grid->RowIndex ?>_cuenta" value="<?= $Grid->cuenta->CurrentValue ?>"<?= $Grid->cuenta->editAttributes() ?>>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_compania_cuenta_cuenta">
<span<?= $Grid->cuenta->viewAttributes() ?>>
<?= $Grid->cuenta->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="compania_cuenta" data-field="x_cuenta" data-hidden="1" name="fcompania_cuentagrid$x<?= $Grid->RowIndex ?>_cuenta" id="fcompania_cuentagrid$x<?= $Grid->RowIndex ?>_cuenta" value="<?= HtmlEncode($Grid->cuenta->FormValue) ?>">
<input type="hidden" data-table="compania_cuenta" data-field="x_cuenta" data-hidden="1" name="fcompania_cuentagrid$o<?= $Grid->RowIndex ?>_cuenta" id="fcompania_cuentagrid$o<?= $Grid->RowIndex ?>_cuenta" value="<?= HtmlEncode($Grid->cuenta->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->pago_electronico->Visible) { // pago_electronico ?>
        <td data-name="pago_electronico" <?= $Grid->pago_electronico->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_compania_cuenta_pago_electronico" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_pago_electronico"
        name="x<?= $Grid->RowIndex ?>_pago_electronico"
        class="form-control ew-select<?= $Grid->pago_electronico->isInvalidClass() ?>"
        data-select2-id="compania_cuenta_x<?= $Grid->RowIndex ?>_pago_electronico"
        data-table="compania_cuenta"
        data-field="x_pago_electronico"
        data-value-separator="<?= $Grid->pago_electronico->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->pago_electronico->getPlaceHolder()) ?>"
        <?= $Grid->pago_electronico->editAttributes() ?>>
        <?= $Grid->pago_electronico->selectOptionListHtml("x{$Grid->RowIndex}_pago_electronico") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->pago_electronico->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='compania_cuenta_x<?= $Grid->RowIndex ?>_pago_electronico']"),
        options = { name: "x<?= $Grid->RowIndex ?>_pago_electronico", selectId: "compania_cuenta_x<?= $Grid->RowIndex ?>_pago_electronico", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.compania_cuenta.fields.pago_electronico.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.compania_cuenta.fields.pago_electronico.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="compania_cuenta" data-field="x_pago_electronico" data-hidden="1" name="o<?= $Grid->RowIndex ?>_pago_electronico" id="o<?= $Grid->RowIndex ?>_pago_electronico" value="<?= HtmlEncode($Grid->pago_electronico->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_compania_cuenta_pago_electronico" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_pago_electronico"
        name="x<?= $Grid->RowIndex ?>_pago_electronico"
        class="form-control ew-select<?= $Grid->pago_electronico->isInvalidClass() ?>"
        data-select2-id="compania_cuenta_x<?= $Grid->RowIndex ?>_pago_electronico"
        data-table="compania_cuenta"
        data-field="x_pago_electronico"
        data-value-separator="<?= $Grid->pago_electronico->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->pago_electronico->getPlaceHolder()) ?>"
        <?= $Grid->pago_electronico->editAttributes() ?>>
        <?= $Grid->pago_electronico->selectOptionListHtml("x{$Grid->RowIndex}_pago_electronico") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->pago_electronico->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='compania_cuenta_x<?= $Grid->RowIndex ?>_pago_electronico']"),
        options = { name: "x<?= $Grid->RowIndex ?>_pago_electronico", selectId: "compania_cuenta_x<?= $Grid->RowIndex ?>_pago_electronico", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.compania_cuenta.fields.pago_electronico.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.compania_cuenta.fields.pago_electronico.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_compania_cuenta_pago_electronico">
<span<?= $Grid->pago_electronico->viewAttributes() ?>>
<?= $Grid->pago_electronico->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="compania_cuenta" data-field="x_pago_electronico" data-hidden="1" name="fcompania_cuentagrid$x<?= $Grid->RowIndex ?>_pago_electronico" id="fcompania_cuentagrid$x<?= $Grid->RowIndex ?>_pago_electronico" value="<?= HtmlEncode($Grid->pago_electronico->FormValue) ?>">
<input type="hidden" data-table="compania_cuenta" data-field="x_pago_electronico" data-hidden="1" name="fcompania_cuentagrid$o<?= $Grid->RowIndex ?>_pago_electronico" id="fcompania_cuentagrid$o<?= $Grid->RowIndex ?>_pago_electronico" value="<?= HtmlEncode($Grid->pago_electronico->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->activo->Visible) { // activo ?>
        <td data-name="activo" <?= $Grid->activo->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_compania_cuenta_activo" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_activo"
        name="x<?= $Grid->RowIndex ?>_activo"
        class="form-control ew-select<?= $Grid->activo->isInvalidClass() ?>"
        data-select2-id="compania_cuenta_x<?= $Grid->RowIndex ?>_activo"
        data-table="compania_cuenta"
        data-field="x_activo"
        data-value-separator="<?= $Grid->activo->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->activo->getPlaceHolder()) ?>"
        <?= $Grid->activo->editAttributes() ?>>
        <?= $Grid->activo->selectOptionListHtml("x{$Grid->RowIndex}_activo") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->activo->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='compania_cuenta_x<?= $Grid->RowIndex ?>_activo']"),
        options = { name: "x<?= $Grid->RowIndex ?>_activo", selectId: "compania_cuenta_x<?= $Grid->RowIndex ?>_activo", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.compania_cuenta.fields.activo.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.compania_cuenta.fields.activo.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="compania_cuenta" data-field="x_activo" data-hidden="1" name="o<?= $Grid->RowIndex ?>_activo" id="o<?= $Grid->RowIndex ?>_activo" value="<?= HtmlEncode($Grid->activo->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_compania_cuenta_activo" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_activo"
        name="x<?= $Grid->RowIndex ?>_activo"
        class="form-control ew-select<?= $Grid->activo->isInvalidClass() ?>"
        data-select2-id="compania_cuenta_x<?= $Grid->RowIndex ?>_activo"
        data-table="compania_cuenta"
        data-field="x_activo"
        data-value-separator="<?= $Grid->activo->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->activo->getPlaceHolder()) ?>"
        <?= $Grid->activo->editAttributes() ?>>
        <?= $Grid->activo->selectOptionListHtml("x{$Grid->RowIndex}_activo") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->activo->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='compania_cuenta_x<?= $Grid->RowIndex ?>_activo']"),
        options = { name: "x<?= $Grid->RowIndex ?>_activo", selectId: "compania_cuenta_x<?= $Grid->RowIndex ?>_activo", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.compania_cuenta.fields.activo.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.compania_cuenta.fields.activo.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_compania_cuenta_activo">
<span<?= $Grid->activo->viewAttributes() ?>>
<?= $Grid->activo->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="compania_cuenta" data-field="x_activo" data-hidden="1" name="fcompania_cuentagrid$x<?= $Grid->RowIndex ?>_activo" id="fcompania_cuentagrid$x<?= $Grid->RowIndex ?>_activo" value="<?= HtmlEncode($Grid->activo->FormValue) ?>">
<input type="hidden" data-table="compania_cuenta" data-field="x_activo" data-hidden="1" name="fcompania_cuentagrid$o<?= $Grid->RowIndex ?>_activo" id="fcompania_cuentagrid$o<?= $Grid->RowIndex ?>_activo" value="<?= HtmlEncode($Grid->activo->OldValue) ?>">
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
loadjs.ready(["fcompania_cuentagrid","load"], function () {
    fcompania_cuentagrid.updateLists(<?= $Grid->RowIndex ?>);
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_compania_cuenta", "data-rowtype" => ROWTYPE_ADD]);
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
    <?php if ($Grid->banco->Visible) { // banco ?>
        <td data-name="banco">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_compania_cuenta_banco" class="form-group compania_cuenta_banco">
<?php
$onchange = $Grid->banco->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Grid->banco->EditAttrs["onchange"] = "";
?>
<span id="as_x<?= $Grid->RowIndex ?>_banco" class="ew-auto-suggest">
    <div class="input-group flex-nowrap">
        <input type="<?= $Grid->banco->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_banco" id="sv_x<?= $Grid->RowIndex ?>_banco" value="<?= RemoveHtml($Grid->banco->EditValue) ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->banco->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->banco->getPlaceHolder()) ?>"<?= $Grid->banco->editAttributes() ?>>
        <div class="input-group-append">
            <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Grid->banco->caption()), $Language->phrase("LookupLink", true))) ?>" onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_banco',m:0,n:10,srch:false});" class="ew-lookup-btn btn btn-default"<?= ($Grid->banco->ReadOnly || $Grid->banco->Disabled) ? " disabled" : "" ?>><i class="fas fa-search ew-icon"></i></button>
        </div>
    </div>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="compania_cuenta" data-field="x_banco" data-input="sv_x<?= $Grid->RowIndex ?>_banco" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Grid->banco->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_banco" id="x<?= $Grid->RowIndex ?>_banco" value="<?= HtmlEncode($Grid->banco->CurrentValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Grid->banco->getErrorMessage() ?></div>
<script>
loadjs.ready(["fcompania_cuentagrid"], function() {
    fcompania_cuentagrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_banco","forceSelect":true}, ew.vars.tables.compania_cuenta.fields.banco.autoSuggestOptions));
});
</script>
<?= $Grid->banco->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_banco") ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_compania_cuenta_banco" class="form-group compania_cuenta_banco">
<span<?= $Grid->banco->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->banco->getDisplayValue($Grid->banco->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="compania_cuenta" data-field="x_banco" data-hidden="1" name="x<?= $Grid->RowIndex ?>_banco" id="x<?= $Grid->RowIndex ?>_banco" value="<?= HtmlEncode($Grid->banco->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="compania_cuenta" data-field="x_banco" data-hidden="1" name="o<?= $Grid->RowIndex ?>_banco" id="o<?= $Grid->RowIndex ?>_banco" value="<?= HtmlEncode($Grid->banco->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->titular->Visible) { // titular ?>
        <td data-name="titular">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_compania_cuenta_titular" class="form-group compania_cuenta_titular">
<input type="<?= $Grid->titular->getInputTextType() ?>" data-table="compania_cuenta" data-field="x_titular" name="x<?= $Grid->RowIndex ?>_titular" id="x<?= $Grid->RowIndex ?>_titular" size="30" maxlength="80" placeholder="<?= HtmlEncode($Grid->titular->getPlaceHolder()) ?>" value="<?= $Grid->titular->EditValue ?>"<?= $Grid->titular->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->titular->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_compania_cuenta_titular" class="form-group compania_cuenta_titular">
<span<?= $Grid->titular->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->titular->getDisplayValue($Grid->titular->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="compania_cuenta" data-field="x_titular" data-hidden="1" name="x<?= $Grid->RowIndex ?>_titular" id="x<?= $Grid->RowIndex ?>_titular" value="<?= HtmlEncode($Grid->titular->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="compania_cuenta" data-field="x_titular" data-hidden="1" name="o<?= $Grid->RowIndex ?>_titular" id="o<?= $Grid->RowIndex ?>_titular" value="<?= HtmlEncode($Grid->titular->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->tipo->Visible) { // tipo ?>
        <td data-name="tipo">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_compania_cuenta_tipo" class="form-group compania_cuenta_tipo">
    <select
        id="x<?= $Grid->RowIndex ?>_tipo"
        name="x<?= $Grid->RowIndex ?>_tipo"
        class="form-control ew-select<?= $Grid->tipo->isInvalidClass() ?>"
        data-select2-id="compania_cuenta_x<?= $Grid->RowIndex ?>_tipo"
        data-table="compania_cuenta"
        data-field="x_tipo"
        data-value-separator="<?= $Grid->tipo->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->tipo->getPlaceHolder()) ?>"
        <?= $Grid->tipo->editAttributes() ?>>
        <?= $Grid->tipo->selectOptionListHtml("x{$Grid->RowIndex}_tipo") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->tipo->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='compania_cuenta_x<?= $Grid->RowIndex ?>_tipo']"),
        options = { name: "x<?= $Grid->RowIndex ?>_tipo", selectId: "compania_cuenta_x<?= $Grid->RowIndex ?>_tipo", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.compania_cuenta.fields.tipo.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.compania_cuenta.fields.tipo.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_compania_cuenta_tipo" class="form-group compania_cuenta_tipo">
<span<?= $Grid->tipo->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->tipo->getDisplayValue($Grid->tipo->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="compania_cuenta" data-field="x_tipo" data-hidden="1" name="x<?= $Grid->RowIndex ?>_tipo" id="x<?= $Grid->RowIndex ?>_tipo" value="<?= HtmlEncode($Grid->tipo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="compania_cuenta" data-field="x_tipo" data-hidden="1" name="o<?= $Grid->RowIndex ?>_tipo" id="o<?= $Grid->RowIndex ?>_tipo" value="<?= HtmlEncode($Grid->tipo->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->numero->Visible) { // numero ?>
        <td data-name="numero">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_compania_cuenta_numero" class="form-group compania_cuenta_numero">
<input type="<?= $Grid->numero->getInputTextType() ?>" data-table="compania_cuenta" data-field="x_numero" name="x<?= $Grid->RowIndex ?>_numero" id="x<?= $Grid->RowIndex ?>_numero" size="30" maxlength="40" placeholder="<?= HtmlEncode($Grid->numero->getPlaceHolder()) ?>" value="<?= $Grid->numero->EditValue ?>"<?= $Grid->numero->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->numero->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_compania_cuenta_numero" class="form-group compania_cuenta_numero">
<span<?= $Grid->numero->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->numero->getDisplayValue($Grid->numero->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="compania_cuenta" data-field="x_numero" data-hidden="1" name="x<?= $Grid->RowIndex ?>_numero" id="x<?= $Grid->RowIndex ?>_numero" value="<?= HtmlEncode($Grid->numero->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="compania_cuenta" data-field="x_numero" data-hidden="1" name="o<?= $Grid->RowIndex ?>_numero" id="o<?= $Grid->RowIndex ?>_numero" value="<?= HtmlEncode($Grid->numero->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->mostrar->Visible) { // mostrar ?>
        <td data-name="mostrar">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_compania_cuenta_mostrar" class="form-group compania_cuenta_mostrar">
    <select
        id="x<?= $Grid->RowIndex ?>_mostrar"
        name="x<?= $Grid->RowIndex ?>_mostrar"
        class="form-control ew-select<?= $Grid->mostrar->isInvalidClass() ?>"
        data-select2-id="compania_cuenta_x<?= $Grid->RowIndex ?>_mostrar"
        data-table="compania_cuenta"
        data-field="x_mostrar"
        data-value-separator="<?= $Grid->mostrar->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->mostrar->getPlaceHolder()) ?>"
        <?= $Grid->mostrar->editAttributes() ?>>
        <?= $Grid->mostrar->selectOptionListHtml("x{$Grid->RowIndex}_mostrar") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->mostrar->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='compania_cuenta_x<?= $Grid->RowIndex ?>_mostrar']"),
        options = { name: "x<?= $Grid->RowIndex ?>_mostrar", selectId: "compania_cuenta_x<?= $Grid->RowIndex ?>_mostrar", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.compania_cuenta.fields.mostrar.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.compania_cuenta.fields.mostrar.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_compania_cuenta_mostrar" class="form-group compania_cuenta_mostrar">
<span<?= $Grid->mostrar->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->mostrar->getDisplayValue($Grid->mostrar->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="compania_cuenta" data-field="x_mostrar" data-hidden="1" name="x<?= $Grid->RowIndex ?>_mostrar" id="x<?= $Grid->RowIndex ?>_mostrar" value="<?= HtmlEncode($Grid->mostrar->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="compania_cuenta" data-field="x_mostrar" data-hidden="1" name="o<?= $Grid->RowIndex ?>_mostrar" id="o<?= $Grid->RowIndex ?>_mostrar" value="<?= HtmlEncode($Grid->mostrar->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->cuenta->Visible) { // cuenta ?>
        <td data-name="cuenta">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_compania_cuenta_cuenta" class="form-group compania_cuenta_cuenta">
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Grid->RowIndex ?>_cuenta"><?= EmptyValue(strval($Grid->cuenta->ViewValue)) ? $Language->phrase("PleaseSelect") : $Grid->cuenta->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Grid->cuenta->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Grid->cuenta->ReadOnly || $Grid->cuenta->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_cuenta',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->cuenta->getErrorMessage() ?></div>
<?= $Grid->cuenta->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_cuenta") ?>
<input type="hidden" is="selection-list" data-table="compania_cuenta" data-field="x_cuenta" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Grid->cuenta->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_cuenta" id="x<?= $Grid->RowIndex ?>_cuenta" value="<?= $Grid->cuenta->CurrentValue ?>"<?= $Grid->cuenta->editAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_compania_cuenta_cuenta" class="form-group compania_cuenta_cuenta">
<span<?= $Grid->cuenta->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->cuenta->getDisplayValue($Grid->cuenta->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="compania_cuenta" data-field="x_cuenta" data-hidden="1" name="x<?= $Grid->RowIndex ?>_cuenta" id="x<?= $Grid->RowIndex ?>_cuenta" value="<?= HtmlEncode($Grid->cuenta->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="compania_cuenta" data-field="x_cuenta" data-hidden="1" name="o<?= $Grid->RowIndex ?>_cuenta" id="o<?= $Grid->RowIndex ?>_cuenta" value="<?= HtmlEncode($Grid->cuenta->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->pago_electronico->Visible) { // pago_electronico ?>
        <td data-name="pago_electronico">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_compania_cuenta_pago_electronico" class="form-group compania_cuenta_pago_electronico">
    <select
        id="x<?= $Grid->RowIndex ?>_pago_electronico"
        name="x<?= $Grid->RowIndex ?>_pago_electronico"
        class="form-control ew-select<?= $Grid->pago_electronico->isInvalidClass() ?>"
        data-select2-id="compania_cuenta_x<?= $Grid->RowIndex ?>_pago_electronico"
        data-table="compania_cuenta"
        data-field="x_pago_electronico"
        data-value-separator="<?= $Grid->pago_electronico->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->pago_electronico->getPlaceHolder()) ?>"
        <?= $Grid->pago_electronico->editAttributes() ?>>
        <?= $Grid->pago_electronico->selectOptionListHtml("x{$Grid->RowIndex}_pago_electronico") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->pago_electronico->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='compania_cuenta_x<?= $Grid->RowIndex ?>_pago_electronico']"),
        options = { name: "x<?= $Grid->RowIndex ?>_pago_electronico", selectId: "compania_cuenta_x<?= $Grid->RowIndex ?>_pago_electronico", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.compania_cuenta.fields.pago_electronico.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.compania_cuenta.fields.pago_electronico.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_compania_cuenta_pago_electronico" class="form-group compania_cuenta_pago_electronico">
<span<?= $Grid->pago_electronico->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->pago_electronico->getDisplayValue($Grid->pago_electronico->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="compania_cuenta" data-field="x_pago_electronico" data-hidden="1" name="x<?= $Grid->RowIndex ?>_pago_electronico" id="x<?= $Grid->RowIndex ?>_pago_electronico" value="<?= HtmlEncode($Grid->pago_electronico->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="compania_cuenta" data-field="x_pago_electronico" data-hidden="1" name="o<?= $Grid->RowIndex ?>_pago_electronico" id="o<?= $Grid->RowIndex ?>_pago_electronico" value="<?= HtmlEncode($Grid->pago_electronico->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->activo->Visible) { // activo ?>
        <td data-name="activo">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_compania_cuenta_activo" class="form-group compania_cuenta_activo">
    <select
        id="x<?= $Grid->RowIndex ?>_activo"
        name="x<?= $Grid->RowIndex ?>_activo"
        class="form-control ew-select<?= $Grid->activo->isInvalidClass() ?>"
        data-select2-id="compania_cuenta_x<?= $Grid->RowIndex ?>_activo"
        data-table="compania_cuenta"
        data-field="x_activo"
        data-value-separator="<?= $Grid->activo->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->activo->getPlaceHolder()) ?>"
        <?= $Grid->activo->editAttributes() ?>>
        <?= $Grid->activo->selectOptionListHtml("x{$Grid->RowIndex}_activo") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->activo->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='compania_cuenta_x<?= $Grid->RowIndex ?>_activo']"),
        options = { name: "x<?= $Grid->RowIndex ?>_activo", selectId: "compania_cuenta_x<?= $Grid->RowIndex ?>_activo", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.compania_cuenta.fields.activo.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.compania_cuenta.fields.activo.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_compania_cuenta_activo" class="form-group compania_cuenta_activo">
<span<?= $Grid->activo->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->activo->getDisplayValue($Grid->activo->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="compania_cuenta" data-field="x_activo" data-hidden="1" name="x<?= $Grid->RowIndex ?>_activo" id="x<?= $Grid->RowIndex ?>_activo" value="<?= HtmlEncode($Grid->activo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="compania_cuenta" data-field="x_activo" data-hidden="1" name="o<?= $Grid->RowIndex ?>_activo" id="o<?= $Grid->RowIndex ?>_activo" value="<?= HtmlEncode($Grid->activo->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["fcompania_cuentagrid","load"], function() {
    fcompania_cuentagrid.updateLists(<?= $Grid->RowIndex ?>);
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
<input type="hidden" name="detailpage" value="fcompania_cuentagrid">
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
    ew.addEventHandlers("compania_cuenta");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
