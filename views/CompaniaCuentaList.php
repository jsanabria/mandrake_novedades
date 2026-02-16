<?php

namespace PHPMaker2021\mandrake;

// Page object
$CompaniaCuentaList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fcompania_cuentalist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    fcompania_cuentalist = currentForm = new ew.Form("fcompania_cuentalist", "list");
    fcompania_cuentalist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';
    loadjs.done("fcompania_cuentalist");
});
var fcompania_cuentalistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    fcompania_cuentalistsrch = currentSearchForm = new ew.Form("fcompania_cuentalistsrch");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "compania_cuenta")) ?>,
        fields = currentTable.fields;
    fcompania_cuentalistsrch.addFields([
        ["banco", [], fields.banco.isInvalid],
        ["titular", [], fields.titular.isInvalid],
        ["tipo", [], fields.tipo.isInvalid],
        ["numero", [], fields.numero.isInvalid],
        ["mostrar", [], fields.mostrar.isInvalid],
        ["cuenta", [], fields.cuenta.isInvalid],
        ["pago_electronico", [], fields.pago_electronico.isInvalid],
        ["activo", [], fields.activo.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        fcompania_cuentalistsrch.setInvalid();
    });

    // Validate form
    fcompania_cuentalistsrch.validate = function () {
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
    fcompania_cuentalistsrch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fcompania_cuentalistsrch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fcompania_cuentalistsrch.lists.mostrar = <?= $Page->mostrar->toClientList($Page) ?>;
    fcompania_cuentalistsrch.lists.activo = <?= $Page->activo->toClientList($Page) ?>;

    // Filters
    fcompania_cuentalistsrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    fcompania_cuentalistsrch.initSearchPanel = true;
    loadjs.done("fcompania_cuentalistsrch");
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
if ($Page->DbMasterFilter != "" && $Page->getCurrentMasterTable() == "compania") {
    if ($Page->MasterRecordExists) {
        include_once "views/CompaniaMaster.php";
    }
}
?>
<?php } ?>
<?php
$Page->renderOtherOptions();
?>
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !$Page->CurrentAction) { ?>
<form name="fcompania_cuentalistsrch" id="fcompania_cuentalistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fcompania_cuentalistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="compania_cuenta">
    <div class="ew-extended-search">
<?php
// Render search row
$Page->RowType = ROWTYPE_SEARCH;
$Page->resetAttributes();
$Page->renderRow();
?>
<?php if ($Page->mostrar->Visible) { // mostrar ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_mostrar" class="ew-cell form-group">
        <label for="x_mostrar" class="ew-search-caption ew-label"><?= $Page->mostrar->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_mostrar" id="z_mostrar" value="=">
</span>
        <span id="el_compania_cuenta_mostrar" class="ew-search-field">
    <select
        id="x_mostrar"
        name="x_mostrar"
        class="form-control ew-select<?= $Page->mostrar->isInvalidClass() ?>"
        data-select2-id="compania_cuenta_x_mostrar"
        data-table="compania_cuenta"
        data-field="x_mostrar"
        data-value-separator="<?= $Page->mostrar->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->mostrar->getPlaceHolder()) ?>"
        <?= $Page->mostrar->editAttributes() ?>>
        <?= $Page->mostrar->selectOptionListHtml("x_mostrar") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->mostrar->getErrorMessage(false) ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='compania_cuenta_x_mostrar']"),
        options = { name: "x_mostrar", selectId: "compania_cuenta_x_mostrar", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.compania_cuenta.fields.mostrar.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.compania_cuenta.fields.mostrar.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_activo" class="ew-cell form-group">
        <label for="x_activo" class="ew-search-caption ew-label"><?= $Page->activo->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_activo" id="z_activo" value="=">
</span>
        <span id="el_compania_cuenta_activo" class="ew-search-field">
    <select
        id="x_activo"
        name="x_activo"
        class="form-control ew-select<?= $Page->activo->isInvalidClass() ?>"
        data-select2-id="compania_cuenta_x_activo"
        data-table="compania_cuenta"
        data-field="x_activo"
        data-value-separator="<?= $Page->activo->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->activo->getPlaceHolder()) ?>"
        <?= $Page->activo->editAttributes() ?>>
        <?= $Page->activo->selectOptionListHtml("x_activo") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->activo->getErrorMessage(false) ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='compania_cuenta_x_activo']"),
        options = { name: "x_activo", selectId: "compania_cuenta_x_activo", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.compania_cuenta.fields.activo.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.compania_cuenta.fields.activo.selectOptions);
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> compania_cuenta">
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
<form name="fcompania_cuentalist" id="fcompania_cuentalist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="compania_cuenta">
<?php if ($Page->getCurrentMasterTable() == "compania" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="compania">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->compania->getSessionValue()) ?>">
<?php } ?>
<div id="gmp_compania_cuenta" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_compania_cuentalist" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Page->banco->Visible) { // banco ?>
        <th data-name="banco" class="<?= $Page->banco->headerCellClass() ?>"><div id="elh_compania_cuenta_banco" class="compania_cuenta_banco"><?= $Page->renderSort($Page->banco) ?></div></th>
<?php } ?>
<?php if ($Page->titular->Visible) { // titular ?>
        <th data-name="titular" class="<?= $Page->titular->headerCellClass() ?>"><div id="elh_compania_cuenta_titular" class="compania_cuenta_titular"><?= $Page->renderSort($Page->titular) ?></div></th>
<?php } ?>
<?php if ($Page->tipo->Visible) { // tipo ?>
        <th data-name="tipo" class="<?= $Page->tipo->headerCellClass() ?>"><div id="elh_compania_cuenta_tipo" class="compania_cuenta_tipo"><?= $Page->renderSort($Page->tipo) ?></div></th>
<?php } ?>
<?php if ($Page->numero->Visible) { // numero ?>
        <th data-name="numero" class="<?= $Page->numero->headerCellClass() ?>"><div id="elh_compania_cuenta_numero" class="compania_cuenta_numero"><?= $Page->renderSort($Page->numero) ?></div></th>
<?php } ?>
<?php if ($Page->mostrar->Visible) { // mostrar ?>
        <th data-name="mostrar" class="<?= $Page->mostrar->headerCellClass() ?>"><div id="elh_compania_cuenta_mostrar" class="compania_cuenta_mostrar"><?= $Page->renderSort($Page->mostrar) ?></div></th>
<?php } ?>
<?php if ($Page->cuenta->Visible) { // cuenta ?>
        <th data-name="cuenta" class="<?= $Page->cuenta->headerCellClass() ?>"><div id="elh_compania_cuenta_cuenta" class="compania_cuenta_cuenta"><?= $Page->renderSort($Page->cuenta) ?></div></th>
<?php } ?>
<?php if ($Page->pago_electronico->Visible) { // pago_electronico ?>
        <th data-name="pago_electronico" class="<?= $Page->pago_electronico->headerCellClass() ?>"><div id="elh_compania_cuenta_pago_electronico" class="compania_cuenta_pago_electronico"><?= $Page->renderSort($Page->pago_electronico) ?></div></th>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
        <th data-name="activo" class="<?= $Page->activo->headerCellClass() ?>"><div id="elh_compania_cuenta_activo" class="compania_cuenta_activo"><?= $Page->renderSort($Page->activo) ?></div></th>
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_compania_cuenta", "data-rowtype" => $Page->RowType]);

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
    <?php if ($Page->banco->Visible) { // banco ?>
        <td data-name="banco" <?= $Page->banco->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_compania_cuenta_banco">
<span<?= $Page->banco->viewAttributes() ?>>
<?= $Page->banco->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->titular->Visible) { // titular ?>
        <td data-name="titular" <?= $Page->titular->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_compania_cuenta_titular">
<span<?= $Page->titular->viewAttributes() ?>>
<?= $Page->titular->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->tipo->Visible) { // tipo ?>
        <td data-name="tipo" <?= $Page->tipo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_compania_cuenta_tipo">
<span<?= $Page->tipo->viewAttributes() ?>>
<?= $Page->tipo->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->numero->Visible) { // numero ?>
        <td data-name="numero" <?= $Page->numero->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_compania_cuenta_numero">
<span<?= $Page->numero->viewAttributes() ?>>
<?= $Page->numero->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mostrar->Visible) { // mostrar ?>
        <td data-name="mostrar" <?= $Page->mostrar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_compania_cuenta_mostrar">
<span<?= $Page->mostrar->viewAttributes() ?>>
<?= $Page->mostrar->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->cuenta->Visible) { // cuenta ?>
        <td data-name="cuenta" <?= $Page->cuenta->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_compania_cuenta_cuenta">
<span<?= $Page->cuenta->viewAttributes() ?>>
<?= $Page->cuenta->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->pago_electronico->Visible) { // pago_electronico ?>
        <td data-name="pago_electronico" <?= $Page->pago_electronico->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_compania_cuenta_pago_electronico">
<span<?= $Page->pago_electronico->viewAttributes() ?>>
<?= $Page->pago_electronico->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->activo->Visible) { // activo ?>
        <td data-name="activo" <?= $Page->activo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_compania_cuenta_activo">
<span<?= $Page->activo->viewAttributes() ?>>
<?= $Page->activo->getViewValue() ?></span>
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
    ew.addEventHandlers("compania_cuenta");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
