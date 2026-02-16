<?php

namespace PHPMaker2021\mandrake;

// Page object
$ViewArticulosOnhandList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fview_articulos_onhandlist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    fview_articulos_onhandlist = currentForm = new ew.Form("fview_articulos_onhandlist", "list");
    fview_articulos_onhandlist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';
    loadjs.done("fview_articulos_onhandlist");
});
var fview_articulos_onhandlistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    fview_articulos_onhandlistsrch = currentSearchForm = new ew.Form("fview_articulos_onhandlistsrch");

    // Dynamic selection lists

    // Filters
    fview_articulos_onhandlistsrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    fview_articulos_onhandlistsrch.initSearchPanel = true;
    loadjs.done("fview_articulos_onhandlistsrch");
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
<form name="fview_articulos_onhandlistsrch" id="fview_articulos_onhandlistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fview_articulos_onhandlistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="view_articulos_onhand">
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> view_articulos_onhand">
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
<form name="fview_articulos_onhandlist" id="fview_articulos_onhandlist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="view_articulos_onhand">
<div id="gmp_view_articulos_onhand" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_view_articulos_onhandlist" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Page->id->Visible) { // id ?>
        <th data-name="id" class="<?= $Page->id->headerCellClass() ?>"><div id="elh_view_articulos_onhand_id" class="view_articulos_onhand_id"><?= $Page->renderSort($Page->id) ?></div></th>
<?php } ?>
<?php if ($Page->codigo->Visible) { // codigo ?>
        <th data-name="codigo" class="<?= $Page->codigo->headerCellClass() ?>"><div id="elh_view_articulos_onhand_codigo" class="view_articulos_onhand_codigo"><?= $Page->renderSort($Page->codigo) ?></div></th>
<?php } ?>
<?php if ($Page->fabricante->Visible) { // fabricante ?>
        <th data-name="fabricante" class="<?= $Page->fabricante->headerCellClass() ?>"><div id="elh_view_articulos_onhand_fabricante" class="view_articulos_onhand_fabricante"><?= $Page->renderSort($Page->fabricante) ?></div></th>
<?php } ?>
<?php if ($Page->nombre_comercial->Visible) { // nombre_comercial ?>
        <th data-name="nombre_comercial" class="<?= $Page->nombre_comercial->headerCellClass() ?>"><div id="elh_view_articulos_onhand_nombre_comercial" class="view_articulos_onhand_nombre_comercial"><?= $Page->renderSort($Page->nombre_comercial) ?></div></th>
<?php } ?>
<?php if ($Page->principio_activo->Visible) { // principio_activo ?>
        <th data-name="principio_activo" class="<?= $Page->principio_activo->headerCellClass() ?>"><div id="elh_view_articulos_onhand_principio_activo" class="view_articulos_onhand_principio_activo"><?= $Page->renderSort($Page->principio_activo) ?></div></th>
<?php } ?>
<?php if ($Page->presentacion->Visible) { // presentacion ?>
        <th data-name="presentacion" class="<?= $Page->presentacion->headerCellClass() ?>"><div id="elh_view_articulos_onhand_presentacion" class="view_articulos_onhand_presentacion"><?= $Page->renderSort($Page->presentacion) ?></div></th>
<?php } ?>
<?php if ($Page->cantidad_en_mano->Visible) { // cantidad_en_mano ?>
        <th data-name="cantidad_en_mano" class="<?= $Page->cantidad_en_mano->headerCellClass() ?>"><div id="elh_view_articulos_onhand_cantidad_en_mano" class="view_articulos_onhand_cantidad_en_mano"><?= $Page->renderSort($Page->cantidad_en_mano) ?></div></th>
<?php } ?>
<?php if ($Page->ultimo_costo->Visible) { // ultimo_costo ?>
        <th data-name="ultimo_costo" class="<?= $Page->ultimo_costo->headerCellClass() ?>"><div id="elh_view_articulos_onhand_ultimo_costo" class="view_articulos_onhand_ultimo_costo"><?= $Page->renderSort($Page->ultimo_costo) ?></div></th>
<?php } ?>
<?php if ($Page->fabricante_nombre->Visible) { // fabricante_nombre ?>
        <th data-name="fabricante_nombre" class="<?= $Page->fabricante_nombre->headerCellClass() ?>"><div id="elh_view_articulos_onhand_fabricante_nombre" class="view_articulos_onhand_fabricante_nombre"><?= $Page->renderSort($Page->fabricante_nombre) ?></div></th>
<?php } ?>
<?php if ($Page->articulo->Visible) { // articulo ?>
        <th data-name="articulo" class="<?= $Page->articulo->headerCellClass() ?>"><div id="elh_view_articulos_onhand_articulo" class="view_articulos_onhand_articulo"><?= $Page->renderSort($Page->articulo) ?></div></th>
<?php } ?>
<?php if ($Page->codigo_ims->Visible) { // codigo_ims ?>
        <th data-name="codigo_ims" class="<?= $Page->codigo_ims->headerCellClass() ?>"><div id="elh_view_articulos_onhand_codigo_ims" class="view_articulos_onhand_codigo_ims"><?= $Page->renderSort($Page->codigo_ims) ?></div></th>
<?php } ?>
<?php if ($Page->cantidad_real->Visible) { // cantidad_real ?>
        <th data-name="cantidad_real" class="<?= $Page->cantidad_real->headerCellClass() ?>"><div id="elh_view_articulos_onhand_cantidad_real" class="view_articulos_onhand_cantidad_real"><?= $Page->renderSort($Page->cantidad_real) ?></div></th>
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_view_articulos_onhand", "data-rowtype" => $Page->RowType]);

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
    <?php if ($Page->id->Visible) { // id ?>
        <td data-name="id" <?= $Page->id->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_articulos_onhand_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->codigo->Visible) { // codigo ?>
        <td data-name="codigo" <?= $Page->codigo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_articulos_onhand_codigo">
<span<?= $Page->codigo->viewAttributes() ?>>
<?= $Page->codigo->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->fabricante->Visible) { // fabricante ?>
        <td data-name="fabricante" <?= $Page->fabricante->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_articulos_onhand_fabricante">
<span<?= $Page->fabricante->viewAttributes() ?>>
<?= $Page->fabricante->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->nombre_comercial->Visible) { // nombre_comercial ?>
        <td data-name="nombre_comercial" <?= $Page->nombre_comercial->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_articulos_onhand_nombre_comercial">
<span<?= $Page->nombre_comercial->viewAttributes() ?>>
<?= $Page->nombre_comercial->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->principio_activo->Visible) { // principio_activo ?>
        <td data-name="principio_activo" <?= $Page->principio_activo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_articulos_onhand_principio_activo">
<span<?= $Page->principio_activo->viewAttributes() ?>>
<?= $Page->principio_activo->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->presentacion->Visible) { // presentacion ?>
        <td data-name="presentacion" <?= $Page->presentacion->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_articulos_onhand_presentacion">
<span<?= $Page->presentacion->viewAttributes() ?>>
<?= $Page->presentacion->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->cantidad_en_mano->Visible) { // cantidad_en_mano ?>
        <td data-name="cantidad_en_mano" <?= $Page->cantidad_en_mano->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_articulos_onhand_cantidad_en_mano">
<span<?= $Page->cantidad_en_mano->viewAttributes() ?>>
<?= $Page->cantidad_en_mano->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->ultimo_costo->Visible) { // ultimo_costo ?>
        <td data-name="ultimo_costo" <?= $Page->ultimo_costo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_articulos_onhand_ultimo_costo">
<span<?= $Page->ultimo_costo->viewAttributes() ?>>
<?= $Page->ultimo_costo->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->fabricante_nombre->Visible) { // fabricante_nombre ?>
        <td data-name="fabricante_nombre" <?= $Page->fabricante_nombre->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_articulos_onhand_fabricante_nombre">
<span<?= $Page->fabricante_nombre->viewAttributes() ?>>
<?= $Page->fabricante_nombre->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->articulo->Visible) { // articulo ?>
        <td data-name="articulo" <?= $Page->articulo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_articulos_onhand_articulo">
<span<?= $Page->articulo->viewAttributes() ?>>
<?= $Page->articulo->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->codigo_ims->Visible) { // codigo_ims ?>
        <td data-name="codigo_ims" <?= $Page->codigo_ims->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_articulos_onhand_codigo_ims">
<span<?= $Page->codigo_ims->viewAttributes() ?>>
<?= $Page->codigo_ims->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->cantidad_real->Visible) { // cantidad_real ?>
        <td data-name="cantidad_real" <?= $Page->cantidad_real->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_articulos_onhand_cantidad_real">
<span<?= $Page->cantidad_real->viewAttributes() ?>>
<?= $Page->cantidad_real->getViewValue() ?></span>
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
    ew.addEventHandlers("view_articulos_onhand");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
