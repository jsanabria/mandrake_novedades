<?php

namespace PHPMaker2021\mandrake;

// Page object
$Recarga2List = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var frecarga2list;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    frecarga2list = currentForm = new ew.Form("frecarga2list", "list");
    frecarga2list.formKeyCountName = '<?= $Page->FormKeyCountName ?>';
    loadjs.done("frecarga2list");
});
var frecarga2listsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    frecarga2listsrch = currentSearchForm = new ew.Form("frecarga2listsrch");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "recarga2")) ?>,
        fields = currentTable.fields;
    frecarga2listsrch.addFields([
        ["cliente", [], fields.cliente.isInvalid],
        ["fecha", [ew.Validators.datetime(7)], fields.fecha.isInvalid],
        ["metodo_pago", [], fields.metodo_pago.isInvalid],
        ["referencia", [], fields.referencia.isInvalid],
        ["monto_moneda", [], fields.monto_moneda.isInvalid],
        ["moneda", [], fields.moneda.isInvalid],
        ["monto_bs", [], fields.monto_bs.isInvalid],
        ["tasa_usd", [], fields.tasa_usd.isInvalid],
        ["monto_usd", [], fields.monto_usd.isInvalid],
        ["saldo", [], fields.saldo.isInvalid],
        ["_username", [], fields._username.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        frecarga2listsrch.setInvalid();
    });

    // Validate form
    frecarga2listsrch.validate = function () {
        if (!this.validateRequired)
            return true; // Ignore validation
        var fobj = this.getForm(),
            $fobj = $(fobj),
            rowIndex = "";
        $fobj.data("rowindex", rowIndex);

        // Validate fields
        if (!this.validateFields(rowIndex))
            return false;

        // Call Form_CustomValidate event
        if (!this.customValidate(fobj)) {
            this.focus();
            return false;
        }
        return true;
    }

    // Form_CustomValidate
    frecarga2listsrch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    frecarga2listsrch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    frecarga2listsrch.lists.metodo_pago = <?= $Page->metodo_pago->toClientList($Page) ?>;
    frecarga2listsrch.lists.moneda = <?= $Page->moneda->toClientList($Page) ?>;

    // Filters
    frecarga2listsrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    frecarga2listsrch.initSearchPanel = true;
    loadjs.done("frecarga2listsrch");
});
</script>
<style>
.ew-table-preview-row { /* main table preview row color */
    background-color: #FFFFFF; /* preview row color */
}
.ew-table-preview-row .ew-grid {
    display: table;
}
</style>
<div id="ew-preview" class="d-none"><!-- preview -->
    <div class="ew-nav-tabs"><!-- .ew-nav-tabs -->
        <ul class="nav nav-tabs"></ul>
        <div class="tab-content"><!-- .tab-content -->
            <div class="tab-pane fade active show"></div>
        </div><!-- /.tab-content -->
    </div><!-- /.ew-nav-tabs -->
</div><!-- /preview -->
<script>
loadjs.ready("head", function() {
    ew.PREVIEW_PLACEMENT = ew.CSS_FLIP ? "left" : "right";
    ew.PREVIEW_SINGLE_ROW = false;
    ew.PREVIEW_OVERLAY = false;
    loadjs(ew.PATH_BASE + "js/ewpreview.js", "preview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php if ($Page->TotalRecords > 0 && $Page->ExportOptions->visible()) { ?>
<?php $Page->ExportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->ImportOptions->visible()) { ?>
<?php $Page->ImportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->SearchOptions->visible()) { ?>
<?php $Page->SearchOptions->render("body") ?>
<?php } ?>
<?php if ($Page->FilterOptions->visible()) { ?>
<?php $Page->FilterOptions->render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php if (!$Page->isExport() || Config("EXPORT_MASTER_RECORD") && $Page->isExport("print")) { ?>
<?php
if ($Page->DbMasterFilter != "" && $Page->getCurrentMasterTable() == "abono2") {
    if ($Page->MasterRecordExists) {
        include_once "views/Abono2Master.php";
    }
}
?>
<?php } ?>
<?php
$Page->renderOtherOptions();
?>
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !$Page->CurrentAction) { ?>
<form name="frecarga2listsrch" id="frecarga2listsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="frecarga2listsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="recarga2">
    <div class="ew-extended-search">
<?php
// Render search row
$Page->RowType = ROWTYPE_SEARCH;
$Page->resetAttributes();
$Page->renderRow();
?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_fecha" class="ew-cell form-group">
        <label for="x_fecha" class="ew-search-caption ew-label"><?= $Page->fecha->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("<=") ?>
<input type="hidden" name="z_fecha" id="z_fecha" value="<=">
</span>
        <span id="el_recarga2_fecha" class="ew-search-field">
<input type="<?= $Page->fecha->getInputTextType() ?>" data-table="recarga2" data-field="x_fecha" data-format="7" name="x_fecha" id="x_fecha" maxlength="10" placeholder="<?= HtmlEncode($Page->fecha->getPlaceHolder()) ?>" value="<?= $Page->fecha->EditValue ?>"<?= $Page->fecha->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->fecha->getErrorMessage(false) ?></div>
<?php if (!$Page->fecha->ReadOnly && !$Page->fecha->Disabled && !isset($Page->fecha->EditAttrs["readonly"]) && !isset($Page->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["frecarga2listsrch", "datetimepicker"], function() {
    ew.createDateTimePicker("frecarga2listsrch", "x_fecha", {"ignoreReadonly":true,"useCurrent":false,"format":7});
});
</script>
<?php } ?>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
<?php if ($Page->metodo_pago->Visible) { // metodo_pago ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_metodo_pago" class="ew-cell form-group">
        <label for="x_metodo_pago" class="ew-search-caption ew-label"><?= $Page->metodo_pago->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_metodo_pago" id="z_metodo_pago" value="LIKE">
</span>
        <span id="el_recarga2_metodo_pago" class="ew-search-field">
    <select
        id="x_metodo_pago"
        name="x_metodo_pago"
        class="form-control ew-select<?= $Page->metodo_pago->isInvalidClass() ?>"
        data-select2-id="recarga2_x_metodo_pago"
        data-table="recarga2"
        data-field="x_metodo_pago"
        data-value-separator="<?= $Page->metodo_pago->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->metodo_pago->getPlaceHolder()) ?>"
        <?= $Page->metodo_pago->editAttributes() ?>>
        <?= $Page->metodo_pago->selectOptionListHtml("x_metodo_pago") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->metodo_pago->getErrorMessage(false) ?></div>
<?= $Page->metodo_pago->Lookup->getParamTag($Page, "p_x_metodo_pago") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='recarga2_x_metodo_pago']"),
        options = { name: "x_metodo_pago", selectId: "recarga2_x_metodo_pago", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.recarga2.fields.metodo_pago.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_moneda" class="ew-cell form-group">
        <label for="x_moneda" class="ew-search-caption ew-label"><?= $Page->moneda->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_moneda" id="z_moneda" value="LIKE">
</span>
        <span id="el_recarga2_moneda" class="ew-search-field">
    <select
        id="x_moneda"
        name="x_moneda"
        class="form-control ew-select<?= $Page->moneda->isInvalidClass() ?>"
        data-select2-id="recarga2_x_moneda"
        data-table="recarga2"
        data-field="x_moneda"
        data-value-separator="<?= $Page->moneda->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->moneda->getPlaceHolder()) ?>"
        <?= $Page->moneda->editAttributes() ?>>
        <?= $Page->moneda->selectOptionListHtml("x_moneda") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->moneda->getErrorMessage(false) ?></div>
<?= $Page->moneda->Lookup->getParamTag($Page, "p_x_moneda") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='recarga2_x_moneda']"),
        options = { name: "x_moneda", selectId: "recarga2_x_moneda", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.recarga2.fields.moneda.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow > 0) { ?>
</div>
    <?php } ?>
<div id="xsr_<?= $Page->SearchRowCount + 1 ?>" class="ew-row d-sm-flex">
    <div class="ew-quick-search input-group">
        <input type="text" name="<?= Config("TABLE_BASIC_SEARCH") ?>" id="<?= Config("TABLE_BASIC_SEARCH") ?>" class="form-control" value="<?= HtmlEncode($Page->BasicSearch->getKeyword()) ?>" placeholder="<?= HtmlEncode($Language->phrase("Search")) ?>">
        <input type="hidden" name="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" id="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" value="<?= HtmlEncode($Page->BasicSearch->getType()) ?>">
        <div class="input-group-append">
            <button class="btn btn-primary" name="btn-submit" id="btn-submit" type="submit"><?= $Language->phrase("SearchBtn") ?></button>
            <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle dropdown-toggle-split" aria-haspopup="true" aria-expanded="false"><span id="searchtype"><?= $Page->BasicSearch->getTypeNameShort() ?></span></button>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item<?php if ($Page->BasicSearch->getType() == "") { ?> active<?php } ?>" href="#" onclick="return ew.setSearchType(this);"><?= $Language->phrase("QuickSearchAuto") ?></a>
                <a class="dropdown-item<?php if ($Page->BasicSearch->getType() == "=") { ?> active<?php } ?>" href="#" onclick="return ew.setSearchType(this, '=');"><?= $Language->phrase("QuickSearchExact") ?></a>
                <a class="dropdown-item<?php if ($Page->BasicSearch->getType() == "AND") { ?> active<?php } ?>" href="#" onclick="return ew.setSearchType(this, 'AND');"><?= $Language->phrase("QuickSearchAll") ?></a>
                <a class="dropdown-item<?php if ($Page->BasicSearch->getType() == "OR") { ?> active<?php } ?>" href="#" onclick="return ew.setSearchType(this, 'OR');"><?= $Language->phrase("QuickSearchAny") ?></a>
            </div>
        </div>
    </div>
</div>
    </div><!-- /.ew-extended-search -->
</div><!-- /.ew-search-panel -->
</form>
<?php } ?>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<?php if ($Page->TotalRecords > 0 || $Page->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> recarga2">
<?php if (!$Page->isExport()) { ?>
<div class="card-header ew-grid-upper-panel">
<?php if (!$Page->isGridAdd()) { ?>
<form name="ew-pager-form" class="form-inline ew-form ew-pager-form" action="<?= CurrentPageUrl(false) ?>">
<?= $Page->Pager->render() ?>
</form>
<?php } ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="frecarga2list" id="frecarga2list" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="recarga2">
<?php if ($Page->getCurrentMasterTable() == "abono2" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="abono2">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->abono->getSessionValue()) ?>">
<?php } ?>
<div id="gmp_recarga2" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_recarga2list" class="table ew-table"><!-- .ew-table -->
<thead>
    <tr class="ew-table-header">
<?php
// Header row
$Page->RowType = ROWTYPE_HEADER;

// Render list options
$Page->renderListOptions();

// Render list options (header, left)
$Page->ListOptions->render("header", "left");
?>
<?php if ($Page->cliente->Visible) { // cliente ?>
        <th data-name="cliente" class="<?= $Page->cliente->headerCellClass() ?>"><div id="elh_recarga2_cliente" class="recarga2_cliente"><?= $Page->renderSort($Page->cliente) ?></div></th>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <th data-name="fecha" class="<?= $Page->fecha->headerCellClass() ?>"><div id="elh_recarga2_fecha" class="recarga2_fecha"><?= $Page->renderSort($Page->fecha) ?></div></th>
<?php } ?>
<?php if ($Page->metodo_pago->Visible) { // metodo_pago ?>
        <th data-name="metodo_pago" class="<?= $Page->metodo_pago->headerCellClass() ?>"><div id="elh_recarga2_metodo_pago" class="recarga2_metodo_pago"><?= $Page->renderSort($Page->metodo_pago) ?></div></th>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
        <th data-name="referencia" class="<?= $Page->referencia->headerCellClass() ?>"><div id="elh_recarga2_referencia" class="recarga2_referencia"><?= $Page->renderSort($Page->referencia) ?></div></th>
<?php } ?>
<?php if ($Page->monto_moneda->Visible) { // monto_moneda ?>
        <th data-name="monto_moneda" class="<?= $Page->monto_moneda->headerCellClass() ?>"><div id="elh_recarga2_monto_moneda" class="recarga2_monto_moneda"><?= $Page->renderSort($Page->monto_moneda) ?></div></th>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
        <th data-name="moneda" class="<?= $Page->moneda->headerCellClass() ?>"><div id="elh_recarga2_moneda" class="recarga2_moneda"><?= $Page->renderSort($Page->moneda) ?></div></th>
<?php } ?>
<?php if ($Page->monto_bs->Visible) { // monto_bs ?>
        <th data-name="monto_bs" class="<?= $Page->monto_bs->headerCellClass() ?>"><div id="elh_recarga2_monto_bs" class="recarga2_monto_bs"><?= $Page->renderSort($Page->monto_bs) ?></div></th>
<?php } ?>
<?php if ($Page->tasa_usd->Visible) { // tasa_usd ?>
        <th data-name="tasa_usd" class="<?= $Page->tasa_usd->headerCellClass() ?>"><div id="elh_recarga2_tasa_usd" class="recarga2_tasa_usd"><?= $Page->renderSort($Page->tasa_usd) ?></div></th>
<?php } ?>
<?php if ($Page->monto_usd->Visible) { // monto_usd ?>
        <th data-name="monto_usd" class="<?= $Page->monto_usd->headerCellClass() ?>"><div id="elh_recarga2_monto_usd" class="recarga2_monto_usd"><?= $Page->renderSort($Page->monto_usd) ?></div></th>
<?php } ?>
<?php if ($Page->saldo->Visible) { // saldo ?>
        <th data-name="saldo" class="<?= $Page->saldo->headerCellClass() ?>"><div id="elh_recarga2_saldo" class="recarga2_saldo"><?= $Page->renderSort($Page->saldo) ?></div></th>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
        <th data-name="_username" class="<?= $Page->_username->headerCellClass() ?>"><div id="elh_recarga2__username" class="recarga2__username"><?= $Page->renderSort($Page->_username) ?></div></th>
<?php } ?>
<?php
// Render list options (header, right)
$Page->ListOptions->render("header", "right");
?>
    </tr>
</thead>
<tbody>
<?php
if ($Page->ExportAll && $Page->isExport()) {
    $Page->StopRecord = $Page->TotalRecords;
} else {
    // Set the last record to display
    if ($Page->TotalRecords > $Page->StartRecord + $Page->DisplayRecords - 1) {
        $Page->StopRecord = $Page->StartRecord + $Page->DisplayRecords - 1;
    } else {
        $Page->StopRecord = $Page->TotalRecords;
    }
}
$Page->RecordCount = $Page->StartRecord - 1;
if ($Page->Recordset && !$Page->Recordset->EOF) {
    // Nothing to do
} elseif (!$Page->AllowAddDeleteRow && $Page->StopRecord == 0) {
    $Page->StopRecord = $Page->GridAddRowCount;
}

// Initialize aggregate
$Page->RowType = ROWTYPE_AGGREGATEINIT;
$Page->resetAttributes();
$Page->renderRow();
while ($Page->RecordCount < $Page->StopRecord) {
    $Page->RecordCount++;
    if ($Page->RecordCount >= $Page->StartRecord) {
        $Page->RowCount++;

        // Set up key count
        $Page->KeyCount = $Page->RowIndex;

        // Init row class and style
        $Page->resetAttributes();
        $Page->CssClass = "";
        if ($Page->isGridAdd()) {
            $Page->loadRowValues(); // Load default values
            $Page->OldKey = "";
            $Page->setKey($Page->OldKey);
        } else {
            $Page->loadRowValues($Page->Recordset); // Load row values
            if ($Page->isGridEdit()) {
                $Page->OldKey = $Page->getKey(true); // Get from CurrentValue
                $Page->setKey($Page->OldKey);
            }
        }
        $Page->RowType = ROWTYPE_VIEW; // Render view

        // Set up row id / data-rowindex
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_recarga2", "data-rowtype" => $Page->RowType]);

        // Render row
        $Page->renderRow();

        // Render list options
        $Page->renderListOptions();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Page->ListOptions->render("body", "left", $Page->RowCount);
?>
    <?php if ($Page->cliente->Visible) { // cliente ?>
        <td data-name="cliente" <?= $Page->cliente->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_recarga2_cliente">
<span<?= $Page->cliente->viewAttributes() ?>>
<?= $Page->cliente->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->fecha->Visible) { // fecha ?>
        <td data-name="fecha" <?= $Page->fecha->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_recarga2_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->metodo_pago->Visible) { // metodo_pago ?>
        <td data-name="metodo_pago" <?= $Page->metodo_pago->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_recarga2_metodo_pago">
<span<?= $Page->metodo_pago->viewAttributes() ?>>
<?= $Page->metodo_pago->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->referencia->Visible) { // referencia ?>
        <td data-name="referencia" <?= $Page->referencia->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_recarga2_referencia">
<span<?= $Page->referencia->viewAttributes() ?>>
<?= $Page->referencia->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->monto_moneda->Visible) { // monto_moneda ?>
        <td data-name="monto_moneda" <?= $Page->monto_moneda->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_recarga2_monto_moneda">
<span<?= $Page->monto_moneda->viewAttributes() ?>>
<?= $Page->monto_moneda->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->moneda->Visible) { // moneda ?>
        <td data-name="moneda" <?= $Page->moneda->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_recarga2_moneda">
<span<?= $Page->moneda->viewAttributes() ?>>
<?= $Page->moneda->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->monto_bs->Visible) { // monto_bs ?>
        <td data-name="monto_bs" <?= $Page->monto_bs->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_recarga2_monto_bs">
<span<?= $Page->monto_bs->viewAttributes() ?>>
<?= $Page->monto_bs->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->tasa_usd->Visible) { // tasa_usd ?>
        <td data-name="tasa_usd" <?= $Page->tasa_usd->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_recarga2_tasa_usd">
<span<?= $Page->tasa_usd->viewAttributes() ?>>
<?= $Page->tasa_usd->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->monto_usd->Visible) { // monto_usd ?>
        <td data-name="monto_usd" <?= $Page->monto_usd->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_recarga2_monto_usd">
<span<?= $Page->monto_usd->viewAttributes() ?>>
<?= $Page->monto_usd->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->saldo->Visible) { // saldo ?>
        <td data-name="saldo" <?= $Page->saldo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_recarga2_saldo">
<span<?= $Page->saldo->viewAttributes() ?>>
<?= $Page->saldo->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->_username->Visible) { // username ?>
        <td data-name="_username" <?= $Page->_username->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_recarga2__username">
<span<?= $Page->_username->viewAttributes() ?>>
<?= $Page->_username->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowCount);
?>
    </tr>
<?php
    }
    if (!$Page->isGridAdd()) {
        $Page->Recordset->moveNext();
    }
}
?>
</tbody>
</table><!-- /.ew-table -->
<?php } ?>
</div><!-- /.ew-grid-middle-panel -->
<?php if (!$Page->CurrentAction) { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
</form><!-- /.ew-list-form -->
<?php
// Close recordset
if ($Page->Recordset) {
    $Page->Recordset->close();
}
?>
<?php if (!$Page->isExport()) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php if (!$Page->isGridAdd()) { ?>
<form name="ew-pager-form" class="form-inline ew-form ew-pager-form" action="<?= CurrentPageUrl(false) ?>">
<?= $Page->Pager->render() ?>
</form>
<?php } ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body", "bottom") ?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div><!-- /.ew-grid -->
<?php } ?>
<?php if ($Page->TotalRecords == 0 && !$Page->CurrentAction) { // Show other options ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("recarga2");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
