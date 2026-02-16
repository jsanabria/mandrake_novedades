<?php

namespace PHPMaker2021\mandrake;

// Page object
$TablaRetencionesList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var ftabla_retencioneslist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    ftabla_retencioneslist = currentForm = new ew.Form("ftabla_retencioneslist", "list");
    ftabla_retencioneslist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';
    loadjs.done("ftabla_retencioneslist");
});
var ftabla_retencioneslistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    ftabla_retencioneslistsrch = currentSearchForm = new ew.Form("ftabla_retencioneslistsrch");

    // Dynamic selection lists

    // Filters
    ftabla_retencioneslistsrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    ftabla_retencioneslistsrch.initSearchPanel = true;
    loadjs.done("ftabla_retencioneslistsrch");
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
<form name="ftabla_retencioneslistsrch" id="ftabla_retencioneslistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="ftabla_retencioneslistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="tabla_retenciones">
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> tabla_retenciones">
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
<form name="ftabla_retencioneslist" id="ftabla_retencioneslist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="tabla_retenciones">
<div id="gmp_tabla_retenciones" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_tabla_retencioneslist" class="table ew-table"><!-- .ew-table -->
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
        <th data-name="id" class="<?= $Page->id->headerCellClass() ?>"><div id="elh_tabla_retenciones_id" class="tabla_retenciones_id"><?= $Page->renderSort($Page->id) ?></div></th>
<?php } ?>
<?php if ($Page->codigo->Visible) { // codigo ?>
        <th data-name="codigo" class="<?= $Page->codigo->headerCellClass() ?>"><div id="elh_tabla_retenciones_codigo" class="tabla_retenciones_codigo"><?= $Page->renderSort($Page->codigo) ?></div></th>
<?php } ?>
<?php if ($Page->tipo->Visible) { // tipo ?>
        <th data-name="tipo" class="<?= $Page->tipo->headerCellClass() ?>"><div id="elh_tabla_retenciones_tipo" class="tabla_retenciones_tipo"><?= $Page->renderSort($Page->tipo) ?></div></th>
<?php } ?>
<?php if ($Page->base_imponible->Visible) { // base_imponible ?>
        <th data-name="base_imponible" class="<?= $Page->base_imponible->headerCellClass() ?>"><div id="elh_tabla_retenciones_base_imponible" class="tabla_retenciones_base_imponible"><?= $Page->renderSort($Page->base_imponible) ?></div></th>
<?php } ?>
<?php if ($Page->tarifa->Visible) { // tarifa ?>
        <th data-name="tarifa" class="<?= $Page->tarifa->headerCellClass() ?>"><div id="elh_tabla_retenciones_tarifa" class="tabla_retenciones_tarifa"><?= $Page->renderSort($Page->tarifa) ?></div></th>
<?php } ?>
<?php if ($Page->sustraendo->Visible) { // sustraendo ?>
        <th data-name="sustraendo" class="<?= $Page->sustraendo->headerCellClass() ?>"><div id="elh_tabla_retenciones_sustraendo" class="tabla_retenciones_sustraendo"><?= $Page->renderSort($Page->sustraendo) ?></div></th>
<?php } ?>
<?php if ($Page->pagos_mayores->Visible) { // pagos_mayores ?>
        <th data-name="pagos_mayores" class="<?= $Page->pagos_mayores->headerCellClass() ?>"><div id="elh_tabla_retenciones_pagos_mayores" class="tabla_retenciones_pagos_mayores"><?= $Page->renderSort($Page->pagos_mayores) ?></div></th>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
        <th data-name="activo" class="<?= $Page->activo->headerCellClass() ?>"><div id="elh_tabla_retenciones_activo" class="tabla_retenciones_activo"><?= $Page->renderSort($Page->activo) ?></div></th>
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_tabla_retenciones", "data-rowtype" => $Page->RowType]);

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
<span id="el<?= $Page->RowCount ?>_tabla_retenciones_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->codigo->Visible) { // codigo ?>
        <td data-name="codigo" <?= $Page->codigo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tabla_retenciones_codigo">
<span<?= $Page->codigo->viewAttributes() ?>>
<?= $Page->codigo->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->tipo->Visible) { // tipo ?>
        <td data-name="tipo" <?= $Page->tipo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tabla_retenciones_tipo">
<span<?= $Page->tipo->viewAttributes() ?>>
<?= $Page->tipo->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->base_imponible->Visible) { // base_imponible ?>
        <td data-name="base_imponible" <?= $Page->base_imponible->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tabla_retenciones_base_imponible">
<span<?= $Page->base_imponible->viewAttributes() ?>>
<?= $Page->base_imponible->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->tarifa->Visible) { // tarifa ?>
        <td data-name="tarifa" <?= $Page->tarifa->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tabla_retenciones_tarifa">
<span<?= $Page->tarifa->viewAttributes() ?>>
<?= $Page->tarifa->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->sustraendo->Visible) { // sustraendo ?>
        <td data-name="sustraendo" <?= $Page->sustraendo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tabla_retenciones_sustraendo">
<span<?= $Page->sustraendo->viewAttributes() ?>>
<?= $Page->sustraendo->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->pagos_mayores->Visible) { // pagos_mayores ?>
        <td data-name="pagos_mayores" <?= $Page->pagos_mayores->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tabla_retenciones_pagos_mayores">
<span<?= $Page->pagos_mayores->viewAttributes() ?>>
<?= $Page->pagos_mayores->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->activo->Visible) { // activo ?>
        <td data-name="activo" <?= $Page->activo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tabla_retenciones_activo">
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
    ew.addEventHandlers("tabla_retenciones");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
