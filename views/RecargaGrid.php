<?php

namespace PHPMaker2021\mandrake;

// Set up and run Grid object
$Grid = Container("RecargaGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var currentForm, currentPageID;
var frecargagrid;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    frecargagrid = new ew.Form("frecargagrid", "grid");
    frecargagrid.formKeyCountName = '<?= $Grid->FormKeyCountName ?>';

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "recarga")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.recarga)
        ew.vars.tables.recarga = currentTable;
    frecargagrid.addFields([
        ["cliente", [fields.cliente.visible && fields.cliente.required ? ew.Validators.required(fields.cliente.caption) : null], fields.cliente.isInvalid],
        ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null], fields.fecha.isInvalid],
        ["metodo_pago", [fields.metodo_pago.visible && fields.metodo_pago.required ? ew.Validators.required(fields.metodo_pago.caption) : null], fields.metodo_pago.isInvalid],
        ["referencia", [fields.referencia.visible && fields.referencia.required ? ew.Validators.required(fields.referencia.caption) : null], fields.referencia.isInvalid],
        ["monto_moneda", [fields.monto_moneda.visible && fields.monto_moneda.required ? ew.Validators.required(fields.monto_moneda.caption) : null, ew.Validators.float], fields.monto_moneda.isInvalid],
        ["moneda", [fields.moneda.visible && fields.moneda.required ? ew.Validators.required(fields.moneda.caption) : null], fields.moneda.isInvalid],
        ["monto_bs", [fields.monto_bs.visible && fields.monto_bs.required ? ew.Validators.required(fields.monto_bs.caption) : null, ew.Validators.float], fields.monto_bs.isInvalid],
        ["tasa_usd", [fields.tasa_usd.visible && fields.tasa_usd.required ? ew.Validators.required(fields.tasa_usd.caption) : null, ew.Validators.float], fields.tasa_usd.isInvalid],
        ["monto_usd", [fields.monto_usd.visible && fields.monto_usd.required ? ew.Validators.required(fields.monto_usd.caption) : null, ew.Validators.float], fields.monto_usd.isInvalid],
        ["saldo", [fields.saldo.visible && fields.saldo.required ? ew.Validators.required(fields.saldo.caption) : null, ew.Validators.float], fields.saldo.isInvalid],
        ["_username", [fields._username.visible && fields._username.required ? ew.Validators.required(fields._username.caption) : null], fields._username.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = frecargagrid,
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
    frecargagrid.validate = function () {
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
    frecargagrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "cliente", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "fecha", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "metodo_pago", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "referencia", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "monto_moneda", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "moneda", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "monto_bs", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "tasa_usd", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "monto_usd", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "saldo", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "_username", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    frecargagrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    frecargagrid.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    frecargagrid.lists.cliente = <?= $Grid->cliente->toClientList($Grid) ?>;
    frecargagrid.lists.metodo_pago = <?= $Grid->metodo_pago->toClientList($Grid) ?>;
    frecargagrid.lists.moneda = <?= $Grid->moneda->toClientList($Grid) ?>;
    frecargagrid.lists._username = <?= $Grid->_username->toClientList($Grid) ?>;
    loadjs.done("frecargagrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> recarga">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="frecargagrid" class="ew-form ew-list-form form-inline">
<div id="gmp_recarga" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_recargagrid" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Grid->cliente->Visible) { // cliente ?>
        <th data-name="cliente" class="<?= $Grid->cliente->headerCellClass() ?>"><div id="elh_recarga_cliente" class="recarga_cliente"><?= $Grid->renderSort($Grid->cliente) ?></div></th>
<?php } ?>
<?php if ($Grid->fecha->Visible) { // fecha ?>
        <th data-name="fecha" class="<?= $Grid->fecha->headerCellClass() ?>"><div id="elh_recarga_fecha" class="recarga_fecha"><?= $Grid->renderSort($Grid->fecha) ?></div></th>
<?php } ?>
<?php if ($Grid->metodo_pago->Visible) { // metodo_pago ?>
        <th data-name="metodo_pago" class="<?= $Grid->metodo_pago->headerCellClass() ?>"><div id="elh_recarga_metodo_pago" class="recarga_metodo_pago"><?= $Grid->renderSort($Grid->metodo_pago) ?></div></th>
<?php } ?>
<?php if ($Grid->referencia->Visible) { // referencia ?>
        <th data-name="referencia" class="<?= $Grid->referencia->headerCellClass() ?>"><div id="elh_recarga_referencia" class="recarga_referencia"><?= $Grid->renderSort($Grid->referencia) ?></div></th>
<?php } ?>
<?php if ($Grid->monto_moneda->Visible) { // monto_moneda ?>
        <th data-name="monto_moneda" class="<?= $Grid->monto_moneda->headerCellClass() ?>"><div id="elh_recarga_monto_moneda" class="recarga_monto_moneda"><?= $Grid->renderSort($Grid->monto_moneda) ?></div></th>
<?php } ?>
<?php if ($Grid->moneda->Visible) { // moneda ?>
        <th data-name="moneda" class="<?= $Grid->moneda->headerCellClass() ?>"><div id="elh_recarga_moneda" class="recarga_moneda"><?= $Grid->renderSort($Grid->moneda) ?></div></th>
<?php } ?>
<?php if ($Grid->monto_bs->Visible) { // monto_bs ?>
        <th data-name="monto_bs" class="<?= $Grid->monto_bs->headerCellClass() ?>"><div id="elh_recarga_monto_bs" class="recarga_monto_bs"><?= $Grid->renderSort($Grid->monto_bs) ?></div></th>
<?php } ?>
<?php if ($Grid->tasa_usd->Visible) { // tasa_usd ?>
        <th data-name="tasa_usd" class="<?= $Grid->tasa_usd->headerCellClass() ?>"><div id="elh_recarga_tasa_usd" class="recarga_tasa_usd"><?= $Grid->renderSort($Grid->tasa_usd) ?></div></th>
<?php } ?>
<?php if ($Grid->monto_usd->Visible) { // monto_usd ?>
        <th data-name="monto_usd" class="<?= $Grid->monto_usd->headerCellClass() ?>"><div id="elh_recarga_monto_usd" class="recarga_monto_usd"><?= $Grid->renderSort($Grid->monto_usd) ?></div></th>
<?php } ?>
<?php if ($Grid->saldo->Visible) { // saldo ?>
        <th data-name="saldo" class="<?= $Grid->saldo->headerCellClass() ?>"><div id="elh_recarga_saldo" class="recarga_saldo"><?= $Grid->renderSort($Grid->saldo) ?></div></th>
<?php } ?>
<?php if ($Grid->_username->Visible) { // username ?>
        <th data-name="_username" class="<?= $Grid->_username->headerCellClass() ?>"><div id="elh_recarga__username" class="recarga__username"><?= $Grid->renderSort($Grid->_username) ?></div></th>
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowCount, "id" => "r" . $Grid->RowCount . "_recarga", "data-rowtype" => $Grid->RowType]);

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
    <?php if ($Grid->cliente->Visible) { // cliente ?>
        <td data-name="cliente" <?= $Grid->cliente->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_recarga_cliente" class="form-group">
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Grid->RowIndex ?>_cliente"><?= EmptyValue(strval($Grid->cliente->ViewValue)) ? $Language->phrase("PleaseSelect") : $Grid->cliente->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Grid->cliente->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Grid->cliente->ReadOnly || $Grid->cliente->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_cliente',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
        <?php if (AllowAdd(CurrentProjectID() . "cliente") && !$Grid->cliente->ReadOnly) { ?>
        <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Grid->RowIndex ?>_cliente" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Grid->cliente->caption() ?>" data-title="<?= $Grid->cliente->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_cliente',url:'<?= GetUrl("ClienteAddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button>
        <?php } ?>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->cliente->getErrorMessage() ?></div>
<?= $Grid->cliente->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_cliente") ?>
<input type="hidden" is="selection-list" data-table="recarga" data-field="x_cliente" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Grid->cliente->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_cliente" id="x<?= $Grid->RowIndex ?>_cliente" value="<?= $Grid->cliente->CurrentValue ?>"<?= $Grid->cliente->editAttributes() ?>>
</span>
<input type="hidden" data-table="recarga" data-field="x_cliente" data-hidden="1" name="o<?= $Grid->RowIndex ?>_cliente" id="o<?= $Grid->RowIndex ?>_cliente" value="<?= HtmlEncode($Grid->cliente->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_recarga_cliente" class="form-group">
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Grid->RowIndex ?>_cliente"><?= EmptyValue(strval($Grid->cliente->ViewValue)) ? $Language->phrase("PleaseSelect") : $Grid->cliente->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Grid->cliente->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Grid->cliente->ReadOnly || $Grid->cliente->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_cliente',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
        <?php if (AllowAdd(CurrentProjectID() . "cliente") && !$Grid->cliente->ReadOnly) { ?>
        <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Grid->RowIndex ?>_cliente" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Grid->cliente->caption() ?>" data-title="<?= $Grid->cliente->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_cliente',url:'<?= GetUrl("ClienteAddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button>
        <?php } ?>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->cliente->getErrorMessage() ?></div>
<?= $Grid->cliente->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_cliente") ?>
<input type="hidden" is="selection-list" data-table="recarga" data-field="x_cliente" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Grid->cliente->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_cliente" id="x<?= $Grid->RowIndex ?>_cliente" value="<?= $Grid->cliente->CurrentValue ?>"<?= $Grid->cliente->editAttributes() ?>>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_recarga_cliente">
<span<?= $Grid->cliente->viewAttributes() ?>>
<?= $Grid->cliente->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="recarga" data-field="x_cliente" data-hidden="1" name="frecargagrid$x<?= $Grid->RowIndex ?>_cliente" id="frecargagrid$x<?= $Grid->RowIndex ?>_cliente" value="<?= HtmlEncode($Grid->cliente->FormValue) ?>">
<input type="hidden" data-table="recarga" data-field="x_cliente" data-hidden="1" name="frecargagrid$o<?= $Grid->RowIndex ?>_cliente" id="frecargagrid$o<?= $Grid->RowIndex ?>_cliente" value="<?= HtmlEncode($Grid->cliente->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->fecha->Visible) { // fecha ?>
        <td data-name="fecha" <?= $Grid->fecha->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_recarga_fecha" class="form-group">
<input type="<?= $Grid->fecha->getInputTextType() ?>" data-table="recarga" data-field="x_fecha" data-format="7" name="x<?= $Grid->RowIndex ?>_fecha" id="x<?= $Grid->RowIndex ?>_fecha" maxlength="10" placeholder="<?= HtmlEncode($Grid->fecha->getPlaceHolder()) ?>" value="<?= $Grid->fecha->EditValue ?>"<?= $Grid->fecha->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->fecha->getErrorMessage() ?></div>
<?php if (!$Grid->fecha->ReadOnly && !$Grid->fecha->Disabled && !isset($Grid->fecha->EditAttrs["readonly"]) && !isset($Grid->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["frecargagrid", "datetimepicker"], function() {
    ew.createDateTimePicker("frecargagrid", "x<?= $Grid->RowIndex ?>_fecha", {"ignoreReadonly":true,"useCurrent":false,"format":7});
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="recarga" data-field="x_fecha" data-hidden="1" name="o<?= $Grid->RowIndex ?>_fecha" id="o<?= $Grid->RowIndex ?>_fecha" value="<?= HtmlEncode($Grid->fecha->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_recarga_fecha" class="form-group">
<span<?= $Grid->fecha->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->fecha->getDisplayValue($Grid->fecha->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="recarga" data-field="x_fecha" data-hidden="1" name="x<?= $Grid->RowIndex ?>_fecha" id="x<?= $Grid->RowIndex ?>_fecha" value="<?= HtmlEncode($Grid->fecha->CurrentValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_recarga_fecha">
<span<?= $Grid->fecha->viewAttributes() ?>>
<?= $Grid->fecha->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="recarga" data-field="x_fecha" data-hidden="1" name="frecargagrid$x<?= $Grid->RowIndex ?>_fecha" id="frecargagrid$x<?= $Grid->RowIndex ?>_fecha" value="<?= HtmlEncode($Grid->fecha->FormValue) ?>">
<input type="hidden" data-table="recarga" data-field="x_fecha" data-hidden="1" name="frecargagrid$o<?= $Grid->RowIndex ?>_fecha" id="frecargagrid$o<?= $Grid->RowIndex ?>_fecha" value="<?= HtmlEncode($Grid->fecha->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->metodo_pago->Visible) { // metodo_pago ?>
        <td data-name="metodo_pago" <?= $Grid->metodo_pago->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_recarga_metodo_pago" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_metodo_pago"
        name="x<?= $Grid->RowIndex ?>_metodo_pago"
        class="form-control ew-select<?= $Grid->metodo_pago->isInvalidClass() ?>"
        data-select2-id="recarga_x<?= $Grid->RowIndex ?>_metodo_pago"
        data-table="recarga"
        data-field="x_metodo_pago"
        data-value-separator="<?= $Grid->metodo_pago->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->metodo_pago->getPlaceHolder()) ?>"
        <?= $Grid->metodo_pago->editAttributes() ?>>
        <?= $Grid->metodo_pago->selectOptionListHtml("x{$Grid->RowIndex}_metodo_pago") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->metodo_pago->getErrorMessage() ?></div>
<?= $Grid->metodo_pago->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_metodo_pago") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='recarga_x<?= $Grid->RowIndex ?>_metodo_pago']"),
        options = { name: "x<?= $Grid->RowIndex ?>_metodo_pago", selectId: "recarga_x<?= $Grid->RowIndex ?>_metodo_pago", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.recarga.fields.metodo_pago.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="recarga" data-field="x_metodo_pago" data-hidden="1" name="o<?= $Grid->RowIndex ?>_metodo_pago" id="o<?= $Grid->RowIndex ?>_metodo_pago" value="<?= HtmlEncode($Grid->metodo_pago->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_recarga_metodo_pago" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_metodo_pago"
        name="x<?= $Grid->RowIndex ?>_metodo_pago"
        class="form-control ew-select<?= $Grid->metodo_pago->isInvalidClass() ?>"
        data-select2-id="recarga_x<?= $Grid->RowIndex ?>_metodo_pago"
        data-table="recarga"
        data-field="x_metodo_pago"
        data-value-separator="<?= $Grid->metodo_pago->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->metodo_pago->getPlaceHolder()) ?>"
        <?= $Grid->metodo_pago->editAttributes() ?>>
        <?= $Grid->metodo_pago->selectOptionListHtml("x{$Grid->RowIndex}_metodo_pago") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->metodo_pago->getErrorMessage() ?></div>
<?= $Grid->metodo_pago->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_metodo_pago") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='recarga_x<?= $Grid->RowIndex ?>_metodo_pago']"),
        options = { name: "x<?= $Grid->RowIndex ?>_metodo_pago", selectId: "recarga_x<?= $Grid->RowIndex ?>_metodo_pago", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.recarga.fields.metodo_pago.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_recarga_metodo_pago">
<span<?= $Grid->metodo_pago->viewAttributes() ?>>
<?= $Grid->metodo_pago->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="recarga" data-field="x_metodo_pago" data-hidden="1" name="frecargagrid$x<?= $Grid->RowIndex ?>_metodo_pago" id="frecargagrid$x<?= $Grid->RowIndex ?>_metodo_pago" value="<?= HtmlEncode($Grid->metodo_pago->FormValue) ?>">
<input type="hidden" data-table="recarga" data-field="x_metodo_pago" data-hidden="1" name="frecargagrid$o<?= $Grid->RowIndex ?>_metodo_pago" id="frecargagrid$o<?= $Grid->RowIndex ?>_metodo_pago" value="<?= HtmlEncode($Grid->metodo_pago->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->referencia->Visible) { // referencia ?>
        <td data-name="referencia" <?= $Grid->referencia->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_recarga_referencia" class="form-group">
<input type="<?= $Grid->referencia->getInputTextType() ?>" data-table="recarga" data-field="x_referencia" name="x<?= $Grid->RowIndex ?>_referencia" id="x<?= $Grid->RowIndex ?>_referencia" size="30" maxlength="20" placeholder="<?= HtmlEncode($Grid->referencia->getPlaceHolder()) ?>" value="<?= $Grid->referencia->EditValue ?>"<?= $Grid->referencia->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->referencia->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="recarga" data-field="x_referencia" data-hidden="1" name="o<?= $Grid->RowIndex ?>_referencia" id="o<?= $Grid->RowIndex ?>_referencia" value="<?= HtmlEncode($Grid->referencia->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_recarga_referencia" class="form-group">
<input type="<?= $Grid->referencia->getInputTextType() ?>" data-table="recarga" data-field="x_referencia" name="x<?= $Grid->RowIndex ?>_referencia" id="x<?= $Grid->RowIndex ?>_referencia" size="30" maxlength="20" placeholder="<?= HtmlEncode($Grid->referencia->getPlaceHolder()) ?>" value="<?= $Grid->referencia->EditValue ?>"<?= $Grid->referencia->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->referencia->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_recarga_referencia">
<span<?= $Grid->referencia->viewAttributes() ?>>
<?= $Grid->referencia->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="recarga" data-field="x_referencia" data-hidden="1" name="frecargagrid$x<?= $Grid->RowIndex ?>_referencia" id="frecargagrid$x<?= $Grid->RowIndex ?>_referencia" value="<?= HtmlEncode($Grid->referencia->FormValue) ?>">
<input type="hidden" data-table="recarga" data-field="x_referencia" data-hidden="1" name="frecargagrid$o<?= $Grid->RowIndex ?>_referencia" id="frecargagrid$o<?= $Grid->RowIndex ?>_referencia" value="<?= HtmlEncode($Grid->referencia->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->monto_moneda->Visible) { // monto_moneda ?>
        <td data-name="monto_moneda" <?= $Grid->monto_moneda->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_recarga_monto_moneda" class="form-group">
<input type="<?= $Grid->monto_moneda->getInputTextType() ?>" data-table="recarga" data-field="x_monto_moneda" name="x<?= $Grid->RowIndex ?>_monto_moneda" id="x<?= $Grid->RowIndex ?>_monto_moneda" size="30" maxlength="14" placeholder="<?= HtmlEncode($Grid->monto_moneda->getPlaceHolder()) ?>" value="<?= $Grid->monto_moneda->EditValue ?>"<?= $Grid->monto_moneda->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto_moneda->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="recarga" data-field="x_monto_moneda" data-hidden="1" name="o<?= $Grid->RowIndex ?>_monto_moneda" id="o<?= $Grid->RowIndex ?>_monto_moneda" value="<?= HtmlEncode($Grid->monto_moneda->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_recarga_monto_moneda" class="form-group">
<input type="<?= $Grid->monto_moneda->getInputTextType() ?>" data-table="recarga" data-field="x_monto_moneda" name="x<?= $Grid->RowIndex ?>_monto_moneda" id="x<?= $Grid->RowIndex ?>_monto_moneda" size="30" maxlength="14" placeholder="<?= HtmlEncode($Grid->monto_moneda->getPlaceHolder()) ?>" value="<?= $Grid->monto_moneda->EditValue ?>"<?= $Grid->monto_moneda->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto_moneda->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_recarga_monto_moneda">
<span<?= $Grid->monto_moneda->viewAttributes() ?>>
<?= $Grid->monto_moneda->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="recarga" data-field="x_monto_moneda" data-hidden="1" name="frecargagrid$x<?= $Grid->RowIndex ?>_monto_moneda" id="frecargagrid$x<?= $Grid->RowIndex ?>_monto_moneda" value="<?= HtmlEncode($Grid->monto_moneda->FormValue) ?>">
<input type="hidden" data-table="recarga" data-field="x_monto_moneda" data-hidden="1" name="frecargagrid$o<?= $Grid->RowIndex ?>_monto_moneda" id="frecargagrid$o<?= $Grid->RowIndex ?>_monto_moneda" value="<?= HtmlEncode($Grid->monto_moneda->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->moneda->Visible) { // moneda ?>
        <td data-name="moneda" <?= $Grid->moneda->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_recarga_moneda" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_moneda"
        name="x<?= $Grid->RowIndex ?>_moneda"
        class="form-control ew-select<?= $Grid->moneda->isInvalidClass() ?>"
        data-select2-id="recarga_x<?= $Grid->RowIndex ?>_moneda"
        data-table="recarga"
        data-field="x_moneda"
        data-value-separator="<?= $Grid->moneda->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->moneda->getPlaceHolder()) ?>"
        <?= $Grid->moneda->editAttributes() ?>>
        <?= $Grid->moneda->selectOptionListHtml("x{$Grid->RowIndex}_moneda") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->moneda->getErrorMessage() ?></div>
<?= $Grid->moneda->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_moneda") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='recarga_x<?= $Grid->RowIndex ?>_moneda']"),
        options = { name: "x<?= $Grid->RowIndex ?>_moneda", selectId: "recarga_x<?= $Grid->RowIndex ?>_moneda", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.recarga.fields.moneda.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="recarga" data-field="x_moneda" data-hidden="1" name="o<?= $Grid->RowIndex ?>_moneda" id="o<?= $Grid->RowIndex ?>_moneda" value="<?= HtmlEncode($Grid->moneda->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_recarga_moneda" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_moneda"
        name="x<?= $Grid->RowIndex ?>_moneda"
        class="form-control ew-select<?= $Grid->moneda->isInvalidClass() ?>"
        data-select2-id="recarga_x<?= $Grid->RowIndex ?>_moneda"
        data-table="recarga"
        data-field="x_moneda"
        data-value-separator="<?= $Grid->moneda->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->moneda->getPlaceHolder()) ?>"
        <?= $Grid->moneda->editAttributes() ?>>
        <?= $Grid->moneda->selectOptionListHtml("x{$Grid->RowIndex}_moneda") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->moneda->getErrorMessage() ?></div>
<?= $Grid->moneda->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_moneda") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='recarga_x<?= $Grid->RowIndex ?>_moneda']"),
        options = { name: "x<?= $Grid->RowIndex ?>_moneda", selectId: "recarga_x<?= $Grid->RowIndex ?>_moneda", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.recarga.fields.moneda.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_recarga_moneda">
<span<?= $Grid->moneda->viewAttributes() ?>>
<?= $Grid->moneda->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="recarga" data-field="x_moneda" data-hidden="1" name="frecargagrid$x<?= $Grid->RowIndex ?>_moneda" id="frecargagrid$x<?= $Grid->RowIndex ?>_moneda" value="<?= HtmlEncode($Grid->moneda->FormValue) ?>">
<input type="hidden" data-table="recarga" data-field="x_moneda" data-hidden="1" name="frecargagrid$o<?= $Grid->RowIndex ?>_moneda" id="frecargagrid$o<?= $Grid->RowIndex ?>_moneda" value="<?= HtmlEncode($Grid->moneda->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->monto_bs->Visible) { // monto_bs ?>
        <td data-name="monto_bs" <?= $Grid->monto_bs->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_recarga_monto_bs" class="form-group">
<input type="<?= $Grid->monto_bs->getInputTextType() ?>" data-table="recarga" data-field="x_monto_bs" name="x<?= $Grid->RowIndex ?>_monto_bs" id="x<?= $Grid->RowIndex ?>_monto_bs" size="30" maxlength="14" placeholder="<?= HtmlEncode($Grid->monto_bs->getPlaceHolder()) ?>" value="<?= $Grid->monto_bs->EditValue ?>"<?= $Grid->monto_bs->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto_bs->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="recarga" data-field="x_monto_bs" data-hidden="1" name="o<?= $Grid->RowIndex ?>_monto_bs" id="o<?= $Grid->RowIndex ?>_monto_bs" value="<?= HtmlEncode($Grid->monto_bs->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_recarga_monto_bs" class="form-group">
<input type="<?= $Grid->monto_bs->getInputTextType() ?>" data-table="recarga" data-field="x_monto_bs" name="x<?= $Grid->RowIndex ?>_monto_bs" id="x<?= $Grid->RowIndex ?>_monto_bs" size="30" maxlength="14" placeholder="<?= HtmlEncode($Grid->monto_bs->getPlaceHolder()) ?>" value="<?= $Grid->monto_bs->EditValue ?>"<?= $Grid->monto_bs->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto_bs->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_recarga_monto_bs">
<span<?= $Grid->monto_bs->viewAttributes() ?>>
<?= $Grid->monto_bs->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="recarga" data-field="x_monto_bs" data-hidden="1" name="frecargagrid$x<?= $Grid->RowIndex ?>_monto_bs" id="frecargagrid$x<?= $Grid->RowIndex ?>_monto_bs" value="<?= HtmlEncode($Grid->monto_bs->FormValue) ?>">
<input type="hidden" data-table="recarga" data-field="x_monto_bs" data-hidden="1" name="frecargagrid$o<?= $Grid->RowIndex ?>_monto_bs" id="frecargagrid$o<?= $Grid->RowIndex ?>_monto_bs" value="<?= HtmlEncode($Grid->monto_bs->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->tasa_usd->Visible) { // tasa_usd ?>
        <td data-name="tasa_usd" <?= $Grid->tasa_usd->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_recarga_tasa_usd" class="form-group">
<input type="<?= $Grid->tasa_usd->getInputTextType() ?>" data-table="recarga" data-field="x_tasa_usd" name="x<?= $Grid->RowIndex ?>_tasa_usd" id="x<?= $Grid->RowIndex ?>_tasa_usd" size="30" maxlength="14" placeholder="<?= HtmlEncode($Grid->tasa_usd->getPlaceHolder()) ?>" value="<?= $Grid->tasa_usd->EditValue ?>"<?= $Grid->tasa_usd->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->tasa_usd->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="recarga" data-field="x_tasa_usd" data-hidden="1" name="o<?= $Grid->RowIndex ?>_tasa_usd" id="o<?= $Grid->RowIndex ?>_tasa_usd" value="<?= HtmlEncode($Grid->tasa_usd->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_recarga_tasa_usd" class="form-group">
<input type="<?= $Grid->tasa_usd->getInputTextType() ?>" data-table="recarga" data-field="x_tasa_usd" name="x<?= $Grid->RowIndex ?>_tasa_usd" id="x<?= $Grid->RowIndex ?>_tasa_usd" size="30" maxlength="14" placeholder="<?= HtmlEncode($Grid->tasa_usd->getPlaceHolder()) ?>" value="<?= $Grid->tasa_usd->EditValue ?>"<?= $Grid->tasa_usd->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->tasa_usd->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_recarga_tasa_usd">
<span<?= $Grid->tasa_usd->viewAttributes() ?>>
<?= $Grid->tasa_usd->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="recarga" data-field="x_tasa_usd" data-hidden="1" name="frecargagrid$x<?= $Grid->RowIndex ?>_tasa_usd" id="frecargagrid$x<?= $Grid->RowIndex ?>_tasa_usd" value="<?= HtmlEncode($Grid->tasa_usd->FormValue) ?>">
<input type="hidden" data-table="recarga" data-field="x_tasa_usd" data-hidden="1" name="frecargagrid$o<?= $Grid->RowIndex ?>_tasa_usd" id="frecargagrid$o<?= $Grid->RowIndex ?>_tasa_usd" value="<?= HtmlEncode($Grid->tasa_usd->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->monto_usd->Visible) { // monto_usd ?>
        <td data-name="monto_usd" <?= $Grid->monto_usd->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_recarga_monto_usd" class="form-group">
<input type="<?= $Grid->monto_usd->getInputTextType() ?>" data-table="recarga" data-field="x_monto_usd" name="x<?= $Grid->RowIndex ?>_monto_usd" id="x<?= $Grid->RowIndex ?>_monto_usd" size="30" maxlength="14" placeholder="<?= HtmlEncode($Grid->monto_usd->getPlaceHolder()) ?>" value="<?= $Grid->monto_usd->EditValue ?>"<?= $Grid->monto_usd->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto_usd->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="recarga" data-field="x_monto_usd" data-hidden="1" name="o<?= $Grid->RowIndex ?>_monto_usd" id="o<?= $Grid->RowIndex ?>_monto_usd" value="<?= HtmlEncode($Grid->monto_usd->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_recarga_monto_usd" class="form-group">
<input type="<?= $Grid->monto_usd->getInputTextType() ?>" data-table="recarga" data-field="x_monto_usd" name="x<?= $Grid->RowIndex ?>_monto_usd" id="x<?= $Grid->RowIndex ?>_monto_usd" size="30" maxlength="14" placeholder="<?= HtmlEncode($Grid->monto_usd->getPlaceHolder()) ?>" value="<?= $Grid->monto_usd->EditValue ?>"<?= $Grid->monto_usd->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto_usd->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_recarga_monto_usd">
<span<?= $Grid->monto_usd->viewAttributes() ?>>
<?= $Grid->monto_usd->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="recarga" data-field="x_monto_usd" data-hidden="1" name="frecargagrid$x<?= $Grid->RowIndex ?>_monto_usd" id="frecargagrid$x<?= $Grid->RowIndex ?>_monto_usd" value="<?= HtmlEncode($Grid->monto_usd->FormValue) ?>">
<input type="hidden" data-table="recarga" data-field="x_monto_usd" data-hidden="1" name="frecargagrid$o<?= $Grid->RowIndex ?>_monto_usd" id="frecargagrid$o<?= $Grid->RowIndex ?>_monto_usd" value="<?= HtmlEncode($Grid->monto_usd->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->saldo->Visible) { // saldo ?>
        <td data-name="saldo" <?= $Grid->saldo->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_recarga_saldo" class="form-group">
<input type="<?= $Grid->saldo->getInputTextType() ?>" data-table="recarga" data-field="x_saldo" name="x<?= $Grid->RowIndex ?>_saldo" id="x<?= $Grid->RowIndex ?>_saldo" size="30" maxlength="14" placeholder="<?= HtmlEncode($Grid->saldo->getPlaceHolder()) ?>" value="<?= $Grid->saldo->EditValue ?>"<?= $Grid->saldo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->saldo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="recarga" data-field="x_saldo" data-hidden="1" name="o<?= $Grid->RowIndex ?>_saldo" id="o<?= $Grid->RowIndex ?>_saldo" value="<?= HtmlEncode($Grid->saldo->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_recarga_saldo" class="form-group">
<input type="<?= $Grid->saldo->getInputTextType() ?>" data-table="recarga" data-field="x_saldo" name="x<?= $Grid->RowIndex ?>_saldo" id="x<?= $Grid->RowIndex ?>_saldo" size="30" maxlength="14" placeholder="<?= HtmlEncode($Grid->saldo->getPlaceHolder()) ?>" value="<?= $Grid->saldo->EditValue ?>"<?= $Grid->saldo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->saldo->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_recarga_saldo">
<span<?= $Grid->saldo->viewAttributes() ?>>
<?= $Grid->saldo->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="recarga" data-field="x_saldo" data-hidden="1" name="frecargagrid$x<?= $Grid->RowIndex ?>_saldo" id="frecargagrid$x<?= $Grid->RowIndex ?>_saldo" value="<?= HtmlEncode($Grid->saldo->FormValue) ?>">
<input type="hidden" data-table="recarga" data-field="x_saldo" data-hidden="1" name="frecargagrid$o<?= $Grid->RowIndex ?>_saldo" id="frecargagrid$o<?= $Grid->RowIndex ?>_saldo" value="<?= HtmlEncode($Grid->saldo->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->_username->Visible) { // username ?>
        <td data-name="_username" <?= $Grid->_username->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_recarga__username" class="form-group">
<?php
$onchange = $Grid->_username->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Grid->_username->EditAttrs["onchange"] = "";
?>
<span id="as_x<?= $Grid->RowIndex ?>__username" class="ew-auto-suggest">
    <input type="<?= $Grid->_username->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>__username" id="sv_x<?= $Grid->RowIndex ?>__username" value="<?= RemoveHtml($Grid->_username->EditValue) ?>" size="30" maxlength="30" placeholder="<?= HtmlEncode($Grid->_username->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->_username->getPlaceHolder()) ?>"<?= $Grid->_username->editAttributes() ?>>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="recarga" data-field="x__username" data-input="sv_x<?= $Grid->RowIndex ?>__username" data-value-separator="<?= $Grid->_username->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>__username" id="x<?= $Grid->RowIndex ?>__username" value="<?= HtmlEncode($Grid->_username->CurrentValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Grid->_username->getErrorMessage() ?></div>
<script>
loadjs.ready(["frecargagrid"], function() {
    frecargagrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>__username","forceSelect":false}, ew.vars.tables.recarga.fields._username.autoSuggestOptions));
});
</script>
<?= $Grid->_username->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "__username") ?>
</span>
<input type="hidden" data-table="recarga" data-field="x__username" data-hidden="1" name="o<?= $Grid->RowIndex ?>__username" id="o<?= $Grid->RowIndex ?>__username" value="<?= HtmlEncode($Grid->_username->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_recarga__username" class="form-group">
<?php
$onchange = $Grid->_username->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Grid->_username->EditAttrs["onchange"] = "";
?>
<span id="as_x<?= $Grid->RowIndex ?>__username" class="ew-auto-suggest">
    <input type="<?= $Grid->_username->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>__username" id="sv_x<?= $Grid->RowIndex ?>__username" value="<?= RemoveHtml($Grid->_username->EditValue) ?>" size="30" maxlength="30" placeholder="<?= HtmlEncode($Grid->_username->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->_username->getPlaceHolder()) ?>"<?= $Grid->_username->editAttributes() ?>>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="recarga" data-field="x__username" data-input="sv_x<?= $Grid->RowIndex ?>__username" data-value-separator="<?= $Grid->_username->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>__username" id="x<?= $Grid->RowIndex ?>__username" value="<?= HtmlEncode($Grid->_username->CurrentValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Grid->_username->getErrorMessage() ?></div>
<script>
loadjs.ready(["frecargagrid"], function() {
    frecargagrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>__username","forceSelect":false}, ew.vars.tables.recarga.fields._username.autoSuggestOptions));
});
</script>
<?= $Grid->_username->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "__username") ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_recarga__username">
<span<?= $Grid->_username->viewAttributes() ?>>
<?= $Grid->_username->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="recarga" data-field="x__username" data-hidden="1" name="frecargagrid$x<?= $Grid->RowIndex ?>__username" id="frecargagrid$x<?= $Grid->RowIndex ?>__username" value="<?= HtmlEncode($Grid->_username->FormValue) ?>">
<input type="hidden" data-table="recarga" data-field="x__username" data-hidden="1" name="frecargagrid$o<?= $Grid->RowIndex ?>__username" id="frecargagrid$o<?= $Grid->RowIndex ?>__username" value="<?= HtmlEncode($Grid->_username->OldValue) ?>">
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
loadjs.ready(["frecargagrid","load"], function () {
    frecargagrid.updateLists(<?= $Grid->RowIndex ?>);
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_recarga", "data-rowtype" => ROWTYPE_ADD]);
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
    <?php if ($Grid->cliente->Visible) { // cliente ?>
        <td data-name="cliente">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_recarga_cliente" class="form-group recarga_cliente">
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Grid->RowIndex ?>_cliente"><?= EmptyValue(strval($Grid->cliente->ViewValue)) ? $Language->phrase("PleaseSelect") : $Grid->cliente->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Grid->cliente->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Grid->cliente->ReadOnly || $Grid->cliente->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_cliente',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
        <?php if (AllowAdd(CurrentProjectID() . "cliente") && !$Grid->cliente->ReadOnly) { ?>
        <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Grid->RowIndex ?>_cliente" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Grid->cliente->caption() ?>" data-title="<?= $Grid->cliente->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_cliente',url:'<?= GetUrl("ClienteAddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button>
        <?php } ?>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->cliente->getErrorMessage() ?></div>
<?= $Grid->cliente->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_cliente") ?>
<input type="hidden" is="selection-list" data-table="recarga" data-field="x_cliente" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Grid->cliente->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_cliente" id="x<?= $Grid->RowIndex ?>_cliente" value="<?= $Grid->cliente->CurrentValue ?>"<?= $Grid->cliente->editAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_recarga_cliente" class="form-group recarga_cliente">
<span<?= $Grid->cliente->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->cliente->getDisplayValue($Grid->cliente->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="recarga" data-field="x_cliente" data-hidden="1" name="x<?= $Grid->RowIndex ?>_cliente" id="x<?= $Grid->RowIndex ?>_cliente" value="<?= HtmlEncode($Grid->cliente->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="recarga" data-field="x_cliente" data-hidden="1" name="o<?= $Grid->RowIndex ?>_cliente" id="o<?= $Grid->RowIndex ?>_cliente" value="<?= HtmlEncode($Grid->cliente->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->fecha->Visible) { // fecha ?>
        <td data-name="fecha">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_recarga_fecha" class="form-group recarga_fecha">
<input type="<?= $Grid->fecha->getInputTextType() ?>" data-table="recarga" data-field="x_fecha" data-format="7" name="x<?= $Grid->RowIndex ?>_fecha" id="x<?= $Grid->RowIndex ?>_fecha" maxlength="10" placeholder="<?= HtmlEncode($Grid->fecha->getPlaceHolder()) ?>" value="<?= $Grid->fecha->EditValue ?>"<?= $Grid->fecha->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->fecha->getErrorMessage() ?></div>
<?php if (!$Grid->fecha->ReadOnly && !$Grid->fecha->Disabled && !isset($Grid->fecha->EditAttrs["readonly"]) && !isset($Grid->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["frecargagrid", "datetimepicker"], function() {
    ew.createDateTimePicker("frecargagrid", "x<?= $Grid->RowIndex ?>_fecha", {"ignoreReadonly":true,"useCurrent":false,"format":7});
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_recarga_fecha" class="form-group recarga_fecha">
<span<?= $Grid->fecha->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->fecha->getDisplayValue($Grid->fecha->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="recarga" data-field="x_fecha" data-hidden="1" name="x<?= $Grid->RowIndex ?>_fecha" id="x<?= $Grid->RowIndex ?>_fecha" value="<?= HtmlEncode($Grid->fecha->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="recarga" data-field="x_fecha" data-hidden="1" name="o<?= $Grid->RowIndex ?>_fecha" id="o<?= $Grid->RowIndex ?>_fecha" value="<?= HtmlEncode($Grid->fecha->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->metodo_pago->Visible) { // metodo_pago ?>
        <td data-name="metodo_pago">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_recarga_metodo_pago" class="form-group recarga_metodo_pago">
    <select
        id="x<?= $Grid->RowIndex ?>_metodo_pago"
        name="x<?= $Grid->RowIndex ?>_metodo_pago"
        class="form-control ew-select<?= $Grid->metodo_pago->isInvalidClass() ?>"
        data-select2-id="recarga_x<?= $Grid->RowIndex ?>_metodo_pago"
        data-table="recarga"
        data-field="x_metodo_pago"
        data-value-separator="<?= $Grid->metodo_pago->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->metodo_pago->getPlaceHolder()) ?>"
        <?= $Grid->metodo_pago->editAttributes() ?>>
        <?= $Grid->metodo_pago->selectOptionListHtml("x{$Grid->RowIndex}_metodo_pago") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->metodo_pago->getErrorMessage() ?></div>
<?= $Grid->metodo_pago->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_metodo_pago") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='recarga_x<?= $Grid->RowIndex ?>_metodo_pago']"),
        options = { name: "x<?= $Grid->RowIndex ?>_metodo_pago", selectId: "recarga_x<?= $Grid->RowIndex ?>_metodo_pago", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.recarga.fields.metodo_pago.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_recarga_metodo_pago" class="form-group recarga_metodo_pago">
<span<?= $Grid->metodo_pago->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->metodo_pago->getDisplayValue($Grid->metodo_pago->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="recarga" data-field="x_metodo_pago" data-hidden="1" name="x<?= $Grid->RowIndex ?>_metodo_pago" id="x<?= $Grid->RowIndex ?>_metodo_pago" value="<?= HtmlEncode($Grid->metodo_pago->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="recarga" data-field="x_metodo_pago" data-hidden="1" name="o<?= $Grid->RowIndex ?>_metodo_pago" id="o<?= $Grid->RowIndex ?>_metodo_pago" value="<?= HtmlEncode($Grid->metodo_pago->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->referencia->Visible) { // referencia ?>
        <td data-name="referencia">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_recarga_referencia" class="form-group recarga_referencia">
<input type="<?= $Grid->referencia->getInputTextType() ?>" data-table="recarga" data-field="x_referencia" name="x<?= $Grid->RowIndex ?>_referencia" id="x<?= $Grid->RowIndex ?>_referencia" size="30" maxlength="20" placeholder="<?= HtmlEncode($Grid->referencia->getPlaceHolder()) ?>" value="<?= $Grid->referencia->EditValue ?>"<?= $Grid->referencia->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->referencia->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_recarga_referencia" class="form-group recarga_referencia">
<span<?= $Grid->referencia->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->referencia->getDisplayValue($Grid->referencia->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="recarga" data-field="x_referencia" data-hidden="1" name="x<?= $Grid->RowIndex ?>_referencia" id="x<?= $Grid->RowIndex ?>_referencia" value="<?= HtmlEncode($Grid->referencia->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="recarga" data-field="x_referencia" data-hidden="1" name="o<?= $Grid->RowIndex ?>_referencia" id="o<?= $Grid->RowIndex ?>_referencia" value="<?= HtmlEncode($Grid->referencia->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->monto_moneda->Visible) { // monto_moneda ?>
        <td data-name="monto_moneda">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_recarga_monto_moneda" class="form-group recarga_monto_moneda">
<input type="<?= $Grid->monto_moneda->getInputTextType() ?>" data-table="recarga" data-field="x_monto_moneda" name="x<?= $Grid->RowIndex ?>_monto_moneda" id="x<?= $Grid->RowIndex ?>_monto_moneda" size="30" maxlength="14" placeholder="<?= HtmlEncode($Grid->monto_moneda->getPlaceHolder()) ?>" value="<?= $Grid->monto_moneda->EditValue ?>"<?= $Grid->monto_moneda->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto_moneda->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_recarga_monto_moneda" class="form-group recarga_monto_moneda">
<span<?= $Grid->monto_moneda->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->monto_moneda->getDisplayValue($Grid->monto_moneda->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="recarga" data-field="x_monto_moneda" data-hidden="1" name="x<?= $Grid->RowIndex ?>_monto_moneda" id="x<?= $Grid->RowIndex ?>_monto_moneda" value="<?= HtmlEncode($Grid->monto_moneda->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="recarga" data-field="x_monto_moneda" data-hidden="1" name="o<?= $Grid->RowIndex ?>_monto_moneda" id="o<?= $Grid->RowIndex ?>_monto_moneda" value="<?= HtmlEncode($Grid->monto_moneda->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->moneda->Visible) { // moneda ?>
        <td data-name="moneda">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_recarga_moneda" class="form-group recarga_moneda">
    <select
        id="x<?= $Grid->RowIndex ?>_moneda"
        name="x<?= $Grid->RowIndex ?>_moneda"
        class="form-control ew-select<?= $Grid->moneda->isInvalidClass() ?>"
        data-select2-id="recarga_x<?= $Grid->RowIndex ?>_moneda"
        data-table="recarga"
        data-field="x_moneda"
        data-value-separator="<?= $Grid->moneda->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->moneda->getPlaceHolder()) ?>"
        <?= $Grid->moneda->editAttributes() ?>>
        <?= $Grid->moneda->selectOptionListHtml("x{$Grid->RowIndex}_moneda") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->moneda->getErrorMessage() ?></div>
<?= $Grid->moneda->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_moneda") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='recarga_x<?= $Grid->RowIndex ?>_moneda']"),
        options = { name: "x<?= $Grid->RowIndex ?>_moneda", selectId: "recarga_x<?= $Grid->RowIndex ?>_moneda", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.recarga.fields.moneda.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_recarga_moneda" class="form-group recarga_moneda">
<span<?= $Grid->moneda->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->moneda->getDisplayValue($Grid->moneda->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="recarga" data-field="x_moneda" data-hidden="1" name="x<?= $Grid->RowIndex ?>_moneda" id="x<?= $Grid->RowIndex ?>_moneda" value="<?= HtmlEncode($Grid->moneda->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="recarga" data-field="x_moneda" data-hidden="1" name="o<?= $Grid->RowIndex ?>_moneda" id="o<?= $Grid->RowIndex ?>_moneda" value="<?= HtmlEncode($Grid->moneda->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->monto_bs->Visible) { // monto_bs ?>
        <td data-name="monto_bs">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_recarga_monto_bs" class="form-group recarga_monto_bs">
<input type="<?= $Grid->monto_bs->getInputTextType() ?>" data-table="recarga" data-field="x_monto_bs" name="x<?= $Grid->RowIndex ?>_monto_bs" id="x<?= $Grid->RowIndex ?>_monto_bs" size="30" maxlength="14" placeholder="<?= HtmlEncode($Grid->monto_bs->getPlaceHolder()) ?>" value="<?= $Grid->monto_bs->EditValue ?>"<?= $Grid->monto_bs->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto_bs->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_recarga_monto_bs" class="form-group recarga_monto_bs">
<span<?= $Grid->monto_bs->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->monto_bs->getDisplayValue($Grid->monto_bs->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="recarga" data-field="x_monto_bs" data-hidden="1" name="x<?= $Grid->RowIndex ?>_monto_bs" id="x<?= $Grid->RowIndex ?>_monto_bs" value="<?= HtmlEncode($Grid->monto_bs->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="recarga" data-field="x_monto_bs" data-hidden="1" name="o<?= $Grid->RowIndex ?>_monto_bs" id="o<?= $Grid->RowIndex ?>_monto_bs" value="<?= HtmlEncode($Grid->monto_bs->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->tasa_usd->Visible) { // tasa_usd ?>
        <td data-name="tasa_usd">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_recarga_tasa_usd" class="form-group recarga_tasa_usd">
<input type="<?= $Grid->tasa_usd->getInputTextType() ?>" data-table="recarga" data-field="x_tasa_usd" name="x<?= $Grid->RowIndex ?>_tasa_usd" id="x<?= $Grid->RowIndex ?>_tasa_usd" size="30" maxlength="14" placeholder="<?= HtmlEncode($Grid->tasa_usd->getPlaceHolder()) ?>" value="<?= $Grid->tasa_usd->EditValue ?>"<?= $Grid->tasa_usd->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->tasa_usd->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_recarga_tasa_usd" class="form-group recarga_tasa_usd">
<span<?= $Grid->tasa_usd->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->tasa_usd->getDisplayValue($Grid->tasa_usd->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="recarga" data-field="x_tasa_usd" data-hidden="1" name="x<?= $Grid->RowIndex ?>_tasa_usd" id="x<?= $Grid->RowIndex ?>_tasa_usd" value="<?= HtmlEncode($Grid->tasa_usd->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="recarga" data-field="x_tasa_usd" data-hidden="1" name="o<?= $Grid->RowIndex ?>_tasa_usd" id="o<?= $Grid->RowIndex ?>_tasa_usd" value="<?= HtmlEncode($Grid->tasa_usd->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->monto_usd->Visible) { // monto_usd ?>
        <td data-name="monto_usd">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_recarga_monto_usd" class="form-group recarga_monto_usd">
<input type="<?= $Grid->monto_usd->getInputTextType() ?>" data-table="recarga" data-field="x_monto_usd" name="x<?= $Grid->RowIndex ?>_monto_usd" id="x<?= $Grid->RowIndex ?>_monto_usd" size="30" maxlength="14" placeholder="<?= HtmlEncode($Grid->monto_usd->getPlaceHolder()) ?>" value="<?= $Grid->monto_usd->EditValue ?>"<?= $Grid->monto_usd->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->monto_usd->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_recarga_monto_usd" class="form-group recarga_monto_usd">
<span<?= $Grid->monto_usd->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->monto_usd->getDisplayValue($Grid->monto_usd->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="recarga" data-field="x_monto_usd" data-hidden="1" name="x<?= $Grid->RowIndex ?>_monto_usd" id="x<?= $Grid->RowIndex ?>_monto_usd" value="<?= HtmlEncode($Grid->monto_usd->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="recarga" data-field="x_monto_usd" data-hidden="1" name="o<?= $Grid->RowIndex ?>_monto_usd" id="o<?= $Grid->RowIndex ?>_monto_usd" value="<?= HtmlEncode($Grid->monto_usd->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->saldo->Visible) { // saldo ?>
        <td data-name="saldo">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_recarga_saldo" class="form-group recarga_saldo">
<input type="<?= $Grid->saldo->getInputTextType() ?>" data-table="recarga" data-field="x_saldo" name="x<?= $Grid->RowIndex ?>_saldo" id="x<?= $Grid->RowIndex ?>_saldo" size="30" maxlength="14" placeholder="<?= HtmlEncode($Grid->saldo->getPlaceHolder()) ?>" value="<?= $Grid->saldo->EditValue ?>"<?= $Grid->saldo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->saldo->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_recarga_saldo" class="form-group recarga_saldo">
<span<?= $Grid->saldo->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->saldo->getDisplayValue($Grid->saldo->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="recarga" data-field="x_saldo" data-hidden="1" name="x<?= $Grid->RowIndex ?>_saldo" id="x<?= $Grid->RowIndex ?>_saldo" value="<?= HtmlEncode($Grid->saldo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="recarga" data-field="x_saldo" data-hidden="1" name="o<?= $Grid->RowIndex ?>_saldo" id="o<?= $Grid->RowIndex ?>_saldo" value="<?= HtmlEncode($Grid->saldo->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->_username->Visible) { // username ?>
        <td data-name="_username">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_recarga__username" class="form-group recarga__username">
<?php
$onchange = $Grid->_username->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Grid->_username->EditAttrs["onchange"] = "";
?>
<span id="as_x<?= $Grid->RowIndex ?>__username" class="ew-auto-suggest">
    <input type="<?= $Grid->_username->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>__username" id="sv_x<?= $Grid->RowIndex ?>__username" value="<?= RemoveHtml($Grid->_username->EditValue) ?>" size="30" maxlength="30" placeholder="<?= HtmlEncode($Grid->_username->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->_username->getPlaceHolder()) ?>"<?= $Grid->_username->editAttributes() ?>>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="recarga" data-field="x__username" data-input="sv_x<?= $Grid->RowIndex ?>__username" data-value-separator="<?= $Grid->_username->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>__username" id="x<?= $Grid->RowIndex ?>__username" value="<?= HtmlEncode($Grid->_username->CurrentValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Grid->_username->getErrorMessage() ?></div>
<script>
loadjs.ready(["frecargagrid"], function() {
    frecargagrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>__username","forceSelect":false}, ew.vars.tables.recarga.fields._username.autoSuggestOptions));
});
</script>
<?= $Grid->_username->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "__username") ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_recarga__username" class="form-group recarga__username">
<span<?= $Grid->_username->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->_username->getDisplayValue($Grid->_username->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="recarga" data-field="x__username" data-hidden="1" name="x<?= $Grid->RowIndex ?>__username" id="x<?= $Grid->RowIndex ?>__username" value="<?= HtmlEncode($Grid->_username->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="recarga" data-field="x__username" data-hidden="1" name="o<?= $Grid->RowIndex ?>__username" id="o<?= $Grid->RowIndex ?>__username" value="<?= HtmlEncode($Grid->_username->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["frecargagrid","load"], function() {
    frecargagrid.updateLists(<?= $Grid->RowIndex ?>);
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
<input type="hidden" name="detailpage" value="frecargagrid">
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
    ew.addEventHandlers("recarga");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
