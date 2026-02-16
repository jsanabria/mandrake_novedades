<?php

namespace PHPMaker2021\mandrake;

// Page object
$CobrosClienteFacturaList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fcobros_cliente_facturalist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    fcobros_cliente_facturalist = currentForm = new ew.Form("fcobros_cliente_facturalist", "list");
    fcobros_cliente_facturalist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';
    loadjs.done("fcobros_cliente_facturalist");
});
var fcobros_cliente_facturalistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    fcobros_cliente_facturalistsrch = currentSearchForm = new ew.Form("fcobros_cliente_facturalistsrch");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "cobros_cliente_factura")) ?>,
        fields = currentTable.fields;
    fcobros_cliente_facturalistsrch.addFields([
        ["tipo_documento", [], fields.tipo_documento.isInvalid],
        ["abono", [], fields.abono.isInvalid],
        ["monto", [], fields.monto.isInvalid],
        ["retivamonto", [], fields.retivamonto.isInvalid],
        ["retiva", [], fields.retiva.isInvalid],
        ["retislrmonto", [], fields.retislrmonto.isInvalid],
        ["retislr", [], fields.retislr.isInvalid],
        ["comprobante", [], fields.comprobante.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        fcobros_cliente_facturalistsrch.setInvalid();
    });

    // Validate form
    fcobros_cliente_facturalistsrch.validate = function () {
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
    fcobros_cliente_facturalistsrch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fcobros_cliente_facturalistsrch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fcobros_cliente_facturalistsrch.lists.abono = <?= $Page->abono->toClientList($Page) ?>;

    // Filters
    fcobros_cliente_facturalistsrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    fcobros_cliente_facturalistsrch.initSearchPanel = true;
    loadjs.done("fcobros_cliente_facturalistsrch");
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
<?php
$Page->renderOtherOptions();
?>
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !$Page->CurrentAction) { ?>
<form name="fcobros_cliente_facturalistsrch" id="fcobros_cliente_facturalistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fcobros_cliente_facturalistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="cobros_cliente_factura">
    <div class="ew-extended-search">
<?php
// Render search row
$Page->RowType = ROWTYPE_SEARCH;
$Page->resetAttributes();
$Page->renderRow();
?>
<?php if ($Page->abono->Visible) { // abono ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_abono" class="ew-cell form-group">
        <label class="ew-search-caption ew-label"><?= $Page->abono->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_abono" id="z_abono" value="=">
</span>
        <span id="el_cobros_cliente_factura_abono" class="ew-search-field">
<template id="tp_x_abono">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="cobros_cliente_factura" data-field="x_abono" name="x_abono" id="x_abono"<?= $Page->abono->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x_abono" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x_abono"
    name="x_abono"
    value="<?= HtmlEncode($Page->abono->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_abono"
    data-target="dsl_x_abono"
    data-repeatcolumn="5"
    class="form-control<?= $Page->abono->isInvalidClass() ?>"
    data-table="cobros_cliente_factura"
    data-field="x_abono"
    data-value-separator="<?= $Page->abono->displayValueSeparatorAttribute() ?>"
    <?= $Page->abono->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->abono->getErrorMessage(false) ?></div>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> cobros_cliente_factura">
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
<form name="fcobros_cliente_facturalist" id="fcobros_cliente_facturalist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cobros_cliente_factura">
<div id="gmp_cobros_cliente_factura" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_cobros_cliente_facturalist" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
        <th data-name="tipo_documento" class="<?= $Page->tipo_documento->headerCellClass() ?>"><div id="elh_cobros_cliente_factura_tipo_documento" class="cobros_cliente_factura_tipo_documento"><?= $Page->renderSort($Page->tipo_documento) ?></div></th>
<?php } ?>
<?php if ($Page->abono->Visible) { // abono ?>
        <th data-name="abono" class="<?= $Page->abono->headerCellClass() ?>"><div id="elh_cobros_cliente_factura_abono" class="cobros_cliente_factura_abono"><?= $Page->renderSort($Page->abono) ?></div></th>
<?php } ?>
<?php if ($Page->monto->Visible) { // monto ?>
        <th data-name="monto" class="<?= $Page->monto->headerCellClass() ?>"><div id="elh_cobros_cliente_factura_monto" class="cobros_cliente_factura_monto"><?= $Page->renderSort($Page->monto) ?></div></th>
<?php } ?>
<?php if ($Page->retivamonto->Visible) { // retivamonto ?>
        <th data-name="retivamonto" class="<?= $Page->retivamonto->headerCellClass() ?>"><div id="elh_cobros_cliente_factura_retivamonto" class="cobros_cliente_factura_retivamonto"><?= $Page->renderSort($Page->retivamonto) ?></div></th>
<?php } ?>
<?php if ($Page->retiva->Visible) { // retiva ?>
        <th data-name="retiva" class="<?= $Page->retiva->headerCellClass() ?>"><div id="elh_cobros_cliente_factura_retiva" class="cobros_cliente_factura_retiva"><?= $Page->renderSort($Page->retiva) ?></div></th>
<?php } ?>
<?php if ($Page->retislrmonto->Visible) { // retislrmonto ?>
        <th data-name="retislrmonto" class="<?= $Page->retislrmonto->headerCellClass() ?>"><div id="elh_cobros_cliente_factura_retislrmonto" class="cobros_cliente_factura_retislrmonto"><?= $Page->renderSort($Page->retislrmonto) ?></div></th>
<?php } ?>
<?php if ($Page->retislr->Visible) { // retislr ?>
        <th data-name="retislr" class="<?= $Page->retislr->headerCellClass() ?>"><div id="elh_cobros_cliente_factura_retislr" class="cobros_cliente_factura_retislr"><?= $Page->renderSort($Page->retislr) ?></div></th>
<?php } ?>
<?php if ($Page->comprobante->Visible) { // comprobante ?>
        <th data-name="comprobante" class="<?= $Page->comprobante->headerCellClass() ?>"><div id="elh_cobros_cliente_factura_comprobante" class="cobros_cliente_factura_comprobante"><?= $Page->renderSort($Page->comprobante) ?></div></th>
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_cobros_cliente_factura", "data-rowtype" => $Page->RowType]);

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
    <?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
        <td data-name="tipo_documento" <?= $Page->tipo_documento->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cobros_cliente_factura_tipo_documento">
<span<?= $Page->tipo_documento->viewAttributes() ?>>
<?= $Page->tipo_documento->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->abono->Visible) { // abono ?>
        <td data-name="abono" <?= $Page->abono->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cobros_cliente_factura_abono">
<span<?= $Page->abono->viewAttributes() ?>>
<?= $Page->abono->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->monto->Visible) { // monto ?>
        <td data-name="monto" <?= $Page->monto->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cobros_cliente_factura_monto">
<span<?= $Page->monto->viewAttributes() ?>>
<?= $Page->monto->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->retivamonto->Visible) { // retivamonto ?>
        <td data-name="retivamonto" <?= $Page->retivamonto->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cobros_cliente_factura_retivamonto">
<span<?= $Page->retivamonto->viewAttributes() ?>>
<?= $Page->retivamonto->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->retiva->Visible) { // retiva ?>
        <td data-name="retiva" <?= $Page->retiva->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cobros_cliente_factura_retiva">
<span<?= $Page->retiva->viewAttributes() ?>>
<?= $Page->retiva->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->retislrmonto->Visible) { // retislrmonto ?>
        <td data-name="retislrmonto" <?= $Page->retislrmonto->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cobros_cliente_factura_retislrmonto">
<span<?= $Page->retislrmonto->viewAttributes() ?>>
<?= $Page->retislrmonto->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->retislr->Visible) { // retislr ?>
        <td data-name="retislr" <?= $Page->retislr->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cobros_cliente_factura_retislr">
<span<?= $Page->retislr->viewAttributes() ?>>
<?= $Page->retislr->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->comprobante->Visible) { // comprobante ?>
        <td data-name="comprobante" <?= $Page->comprobante->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cobros_cliente_factura_comprobante">
<span<?= $Page->comprobante->viewAttributes() ?>>
<?php if (!EmptyString($Page->comprobante->getViewValue()) && $Page->comprobante->linkAttributes() != "") { ?>
<a<?= $Page->comprobante->linkAttributes() ?>><?= $Page->comprobante->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->comprobante->getViewValue() ?>
<?php } ?>
</span>
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
    ew.addEventHandlers("cobros_cliente_factura");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
