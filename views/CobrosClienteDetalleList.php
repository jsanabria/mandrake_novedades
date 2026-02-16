<?php

namespace PHPMaker2021\mandrake;

// Page object
$CobrosClienteDetalleList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fcobros_cliente_detallelist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    fcobros_cliente_detallelist = currentForm = new ew.Form("fcobros_cliente_detallelist", "list");
    fcobros_cliente_detallelist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';
    loadjs.done("fcobros_cliente_detallelist");
});
var fcobros_cliente_detallelistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    fcobros_cliente_detallelistsrch = currentSearchForm = new ew.Form("fcobros_cliente_detallelistsrch");

    // Dynamic selection lists

    // Filters
    fcobros_cliente_detallelistsrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    fcobros_cliente_detallelistsrch.initSearchPanel = true;
    loadjs.done("fcobros_cliente_detallelistsrch");
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
if ($Page->DbMasterFilter != "" && $Page->getCurrentMasterTable() == "cobros_cliente") {
    if ($Page->MasterRecordExists) {
        include_once "views/CobrosClienteMaster.php";
    }
}
?>
<?php } ?>
<?php
$Page->renderOtherOptions();
?>
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !$Page->CurrentAction) { ?>
<form name="fcobros_cliente_detallelistsrch" id="fcobros_cliente_detallelistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fcobros_cliente_detallelistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="cobros_cliente_detalle">
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> cobros_cliente_detalle">
<form name="fcobros_cliente_detallelist" id="fcobros_cliente_detallelist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cobros_cliente_detalle">
<?php if ($Page->getCurrentMasterTable() == "cobros_cliente" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="cobros_cliente">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->cobros_cliente->getSessionValue()) ?>">
<?php } ?>
<div id="gmp_cobros_cliente_detalle" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_cobros_cliente_detallelist" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Page->metodo_pago->Visible) { // metodo_pago ?>
        <th data-name="metodo_pago" class="<?= $Page->metodo_pago->headerCellClass() ?>"><div id="elh_cobros_cliente_detalle_metodo_pago" class="cobros_cliente_detalle_metodo_pago"><?= $Page->renderSort($Page->metodo_pago) ?></div></th>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
        <th data-name="referencia" class="<?= $Page->referencia->headerCellClass() ?>"><div id="elh_cobros_cliente_detalle_referencia" class="cobros_cliente_detalle_referencia"><?= $Page->renderSort($Page->referencia) ?></div></th>
<?php } ?>
<?php if ($Page->monto_moneda->Visible) { // monto_moneda ?>
        <th data-name="monto_moneda" class="<?= $Page->monto_moneda->headerCellClass() ?>"><div id="elh_cobros_cliente_detalle_monto_moneda" class="cobros_cliente_detalle_monto_moneda"><?= $Page->renderSort($Page->monto_moneda) ?></div></th>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
        <th data-name="moneda" class="<?= $Page->moneda->headerCellClass() ?>"><div id="elh_cobros_cliente_detalle_moneda" class="cobros_cliente_detalle_moneda"><?= $Page->renderSort($Page->moneda) ?></div></th>
<?php } ?>
<?php if ($Page->tasa_moneda->Visible) { // tasa_moneda ?>
        <th data-name="tasa_moneda" class="<?= $Page->tasa_moneda->headerCellClass() ?>"><div id="elh_cobros_cliente_detalle_tasa_moneda" class="cobros_cliente_detalle_tasa_moneda"><?= $Page->renderSort($Page->tasa_moneda) ?></div></th>
<?php } ?>
<?php if ($Page->monto_bs->Visible) { // monto_bs ?>
        <th data-name="monto_bs" class="<?= $Page->monto_bs->headerCellClass() ?>"><div id="elh_cobros_cliente_detalle_monto_bs" class="cobros_cliente_detalle_monto_bs"><?= $Page->renderSort($Page->monto_bs) ?></div></th>
<?php } ?>
<?php if ($Page->tasa_usd->Visible) { // tasa_usd ?>
        <th data-name="tasa_usd" class="<?= $Page->tasa_usd->headerCellClass() ?>"><div id="elh_cobros_cliente_detalle_tasa_usd" class="cobros_cliente_detalle_tasa_usd"><?= $Page->renderSort($Page->tasa_usd) ?></div></th>
<?php } ?>
<?php if ($Page->monto_usd->Visible) { // monto_usd ?>
        <th data-name="monto_usd" class="<?= $Page->monto_usd->headerCellClass() ?>"><div id="elh_cobros_cliente_detalle_monto_usd" class="cobros_cliente_detalle_monto_usd"><?= $Page->renderSort($Page->monto_usd) ?></div></th>
<?php } ?>
<?php if ($Page->banco->Visible) { // banco ?>
        <th data-name="banco" class="<?= $Page->banco->headerCellClass() ?>"><div id="elh_cobros_cliente_detalle_banco" class="cobros_cliente_detalle_banco"><?= $Page->renderSort($Page->banco) ?></div></th>
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_cobros_cliente_detalle", "data-rowtype" => $Page->RowType]);

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
    <?php if ($Page->metodo_pago->Visible) { // metodo_pago ?>
        <td data-name="metodo_pago" <?= $Page->metodo_pago->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cobros_cliente_detalle_metodo_pago">
<span<?= $Page->metodo_pago->viewAttributes() ?>>
<?= $Page->metodo_pago->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->referencia->Visible) { // referencia ?>
        <td data-name="referencia" <?= $Page->referencia->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cobros_cliente_detalle_referencia">
<span<?= $Page->referencia->viewAttributes() ?>>
<?= $Page->referencia->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->monto_moneda->Visible) { // monto_moneda ?>
        <td data-name="monto_moneda" <?= $Page->monto_moneda->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cobros_cliente_detalle_monto_moneda">
<span<?= $Page->monto_moneda->viewAttributes() ?>>
<?= $Page->monto_moneda->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->moneda->Visible) { // moneda ?>
        <td data-name="moneda" <?= $Page->moneda->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cobros_cliente_detalle_moneda">
<span<?= $Page->moneda->viewAttributes() ?>>
<?= $Page->moneda->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->tasa_moneda->Visible) { // tasa_moneda ?>
        <td data-name="tasa_moneda" <?= $Page->tasa_moneda->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cobros_cliente_detalle_tasa_moneda">
<span<?= $Page->tasa_moneda->viewAttributes() ?>>
<?= $Page->tasa_moneda->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->monto_bs->Visible) { // monto_bs ?>
        <td data-name="monto_bs" <?= $Page->monto_bs->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cobros_cliente_detalle_monto_bs">
<span<?= $Page->monto_bs->viewAttributes() ?>>
<?= $Page->monto_bs->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->tasa_usd->Visible) { // tasa_usd ?>
        <td data-name="tasa_usd" <?= $Page->tasa_usd->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cobros_cliente_detalle_tasa_usd">
<span<?= $Page->tasa_usd->viewAttributes() ?>>
<?= $Page->tasa_usd->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->monto_usd->Visible) { // monto_usd ?>
        <td data-name="monto_usd" <?= $Page->monto_usd->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cobros_cliente_detalle_monto_usd">
<span<?= $Page->monto_usd->viewAttributes() ?>>
<?= $Page->monto_usd->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->banco->Visible) { // banco ?>
        <td data-name="banco" <?= $Page->banco->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cobros_cliente_detalle_banco">
<span<?= $Page->banco->viewAttributes() ?>>
<?= $Page->banco->getViewValue() ?></span>
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
    ew.addEventHandlers("cobros_cliente_detalle");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
