<?php

namespace PHPMaker2021\mandrake;

// Page object
$ArticuloStockList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var farticulo_stocklist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    farticulo_stocklist = currentForm = new ew.Form("farticulo_stocklist", "list");
    farticulo_stocklist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';
    loadjs.done("farticulo_stocklist");
});
var farticulo_stocklistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    farticulo_stocklistsrch = currentSearchForm = new ew.Form("farticulo_stocklistsrch");

    // Dynamic selection lists

    // Filters
    farticulo_stocklistsrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    farticulo_stocklistsrch.initSearchPanel = true;
    loadjs.done("farticulo_stocklistsrch");
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
<form name="farticulo_stocklistsrch" id="farticulo_stocklistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="farticulo_stocklistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="articulo_stock">
    <div class="ew-extended-search">
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> articulo_stock">
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
<form name="farticulo_stocklist" id="farticulo_stocklist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="articulo_stock">
<div id="gmp_articulo_stock" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_articulo_stocklist" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Page->codigo->Visible) { // codigo ?>
        <th data-name="codigo" class="<?= $Page->codigo->headerCellClass() ?>"><div id="elh_articulo_stock_codigo" class="articulo_stock_codigo"><?= $Page->renderSort($Page->codigo) ?></div></th>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
        <th data-name="nombre" class="<?= $Page->nombre->headerCellClass() ?>"><div id="elh_articulo_stock_nombre" class="articulo_stock_nombre"><?= $Page->renderSort($Page->nombre) ?></div></th>
<?php } ?>
<?php if ($Page->cantidad_en_mano->Visible) { // cantidad_en_mano ?>
        <th data-name="cantidad_en_mano" class="<?= $Page->cantidad_en_mano->headerCellClass() ?>"><div id="elh_articulo_stock_cantidad_en_mano" class="articulo_stock_cantidad_en_mano"><?= $Page->renderSort($Page->cantidad_en_mano) ?></div></th>
<?php } ?>
<?php if ($Page->codigo_ims->Visible) { // codigo_ims ?>
        <th data-name="codigo_ims" class="<?= $Page->codigo_ims->headerCellClass() ?>"><div id="elh_articulo_stock_codigo_ims" class="articulo_stock_codigo_ims"><?= $Page->renderSort($Page->codigo_ims) ?></div></th>
<?php } ?>
<?php if ($Page->codigo_ims2->Visible) { // codigo_ims2 ?>
        <th data-name="codigo_ims2" class="<?= $Page->codigo_ims2->headerCellClass() ?>"><div id="elh_articulo_stock_codigo_ims2" class="articulo_stock_codigo_ims2"><?= $Page->renderSort($Page->codigo_ims2) ?></div></th>
<?php } ?>
<?php if ($Page->costo->Visible) { // costo ?>
        <th data-name="costo" class="<?= $Page->costo->headerCellClass() ?>"><div id="elh_articulo_stock_costo" class="articulo_stock_costo"><?= $Page->renderSort($Page->costo) ?></div></th>
<?php } ?>
<?php if ($Page->precio_full->Visible) { // precio_full ?>
        <th data-name="precio_full" class="<?= $Page->precio_full->headerCellClass() ?>"><div id="elh_articulo_stock_precio_full" class="articulo_stock_precio_full"><?= $Page->renderSort($Page->precio_full) ?></div></th>
<?php } ?>
<?php if ($Page->precio_venta->Visible) { // precio_venta ?>
        <th data-name="precio_venta" class="<?= $Page->precio_venta->headerCellClass() ?>"><div id="elh_articulo_stock_precio_venta" class="articulo_stock_precio_venta"><?= $Page->renderSort($Page->precio_venta) ?></div></th>
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_articulo_stock", "data-rowtype" => $Page->RowType]);

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
    <?php if ($Page->codigo->Visible) { // codigo ?>
        <td data-name="codigo" <?= $Page->codigo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_articulo_stock_codigo">
<span<?= $Page->codigo->viewAttributes() ?>>
<?= $Page->codigo->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->nombre->Visible) { // nombre ?>
        <td data-name="nombre" <?= $Page->nombre->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_articulo_stock_nombre">
<span<?= $Page->nombre->viewAttributes() ?>>
<?= $Page->nombre->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->cantidad_en_mano->Visible) { // cantidad_en_mano ?>
        <td data-name="cantidad_en_mano" <?= $Page->cantidad_en_mano->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_articulo_stock_cantidad_en_mano">
<span<?= $Page->cantidad_en_mano->viewAttributes() ?>>
<?= $Page->cantidad_en_mano->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->codigo_ims->Visible) { // codigo_ims ?>
        <td data-name="codigo_ims" <?= $Page->codigo_ims->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_articulo_stock_codigo_ims">
<span<?= $Page->codigo_ims->viewAttributes() ?>>
<?= $Page->codigo_ims->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->codigo_ims2->Visible) { // codigo_ims2 ?>
        <td data-name="codigo_ims2" <?= $Page->codigo_ims2->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_articulo_stock_codigo_ims2">
<span<?= $Page->codigo_ims2->viewAttributes() ?>>
<?= $Page->codigo_ims2->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->costo->Visible) { // costo ?>
        <td data-name="costo" <?= $Page->costo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_articulo_stock_costo">
<span<?= $Page->costo->viewAttributes() ?>>
<?= $Page->costo->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->precio_full->Visible) { // precio_full ?>
        <td data-name="precio_full" <?= $Page->precio_full->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_articulo_stock_precio_full">
<span<?= $Page->precio_full->viewAttributes() ?>>
<?= $Page->precio_full->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->precio_venta->Visible) { // precio_venta ?>
        <td data-name="precio_venta" <?= $Page->precio_venta->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_articulo_stock_precio_venta">
<span<?= $Page->precio_venta->viewAttributes() ?>>
<?= $Page->precio_venta->getViewValue() ?></span>
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
    ew.addEventHandlers("articulo_stock");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
