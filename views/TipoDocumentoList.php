<?php

namespace PHPMaker2021\mandrake;

// Page object
$TipoDocumentoList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var ftipo_documentolist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    ftipo_documentolist = currentForm = new ew.Form("ftipo_documentolist", "list");
    ftipo_documentolist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';
    loadjs.done("ftipo_documentolist");
});
var ftipo_documentolistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    ftipo_documentolistsrch = currentSearchForm = new ew.Form("ftipo_documentolistsrch");

    // Dynamic selection lists

    // Filters
    ftipo_documentolistsrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    ftipo_documentolistsrch.initSearchPanel = true;
    loadjs.done("ftipo_documentolistsrch");
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
<form name="ftipo_documentolistsrch" id="ftipo_documentolistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="ftipo_documentolistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="tipo_documento">
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> tipo_documento">
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
<form name="ftipo_documentolist" id="ftipo_documentolist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="tipo_documento">
<div id="gmp_tipo_documento" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_tipo_documentolist" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Page->descripcion->Visible) { // descripcion ?>
        <th data-name="descripcion" class="<?= $Page->descripcion->headerCellClass() ?>"><div id="elh_tipo_documento_descripcion" class="tipo_documento_descripcion"><?= $Page->renderSort($Page->descripcion) ?></div></th>
<?php } ?>
<?php if ($Page->M01->Visible) { // M01 ?>
        <th data-name="M01" class="<?= $Page->M01->headerCellClass() ?>"><div id="elh_tipo_documento_M01" class="tipo_documento_M01"><?= $Page->renderSort($Page->M01) ?></div></th>
<?php } ?>
<?php if ($Page->M02->Visible) { // M02 ?>
        <th data-name="M02" class="<?= $Page->M02->headerCellClass() ?>"><div id="elh_tipo_documento_M02" class="tipo_documento_M02"><?= $Page->renderSort($Page->M02) ?></div></th>
<?php } ?>
<?php if ($Page->M03->Visible) { // M03 ?>
        <th data-name="M03" class="<?= $Page->M03->headerCellClass() ?>"><div id="elh_tipo_documento_M03" class="tipo_documento_M03"><?= $Page->renderSort($Page->M03) ?></div></th>
<?php } ?>
<?php if ($Page->M04->Visible) { // M04 ?>
        <th data-name="M04" class="<?= $Page->M04->headerCellClass() ?>"><div id="elh_tipo_documento_M04" class="tipo_documento_M04"><?= $Page->renderSort($Page->M04) ?></div></th>
<?php } ?>
<?php if ($Page->M05->Visible) { // M05 ?>
        <th data-name="M05" class="<?= $Page->M05->headerCellClass() ?>"><div id="elh_tipo_documento_M05" class="tipo_documento_M05"><?= $Page->renderSort($Page->M05) ?></div></th>
<?php } ?>
<?php if ($Page->M06->Visible) { // M06 ?>
        <th data-name="M06" class="<?= $Page->M06->headerCellClass() ?>"><div id="elh_tipo_documento_M06" class="tipo_documento_M06"><?= $Page->renderSort($Page->M06) ?></div></th>
<?php } ?>
<?php if ($Page->M07->Visible) { // M07 ?>
        <th data-name="M07" class="<?= $Page->M07->headerCellClass() ?>"><div id="elh_tipo_documento_M07" class="tipo_documento_M07"><?= $Page->renderSort($Page->M07) ?></div></th>
<?php } ?>
<?php if ($Page->M08->Visible) { // M08 ?>
        <th data-name="M08" class="<?= $Page->M08->headerCellClass() ?>"><div id="elh_tipo_documento_M08" class="tipo_documento_M08"><?= $Page->renderSort($Page->M08) ?></div></th>
<?php } ?>
<?php if ($Page->M09->Visible) { // M09 ?>
        <th data-name="M09" class="<?= $Page->M09->headerCellClass() ?>"><div id="elh_tipo_documento_M09" class="tipo_documento_M09"><?= $Page->renderSort($Page->M09) ?></div></th>
<?php } ?>
<?php if ($Page->M10->Visible) { // M10 ?>
        <th data-name="M10" class="<?= $Page->M10->headerCellClass() ?>"><div id="elh_tipo_documento_M10" class="tipo_documento_M10"><?= $Page->renderSort($Page->M10) ?></div></th>
<?php } ?>
<?php if ($Page->M11->Visible) { // M11 ?>
        <th data-name="M11" class="<?= $Page->M11->headerCellClass() ?>"><div id="elh_tipo_documento_M11" class="tipo_documento_M11"><?= $Page->renderSort($Page->M11) ?></div></th>
<?php } ?>
<?php if ($Page->M12->Visible) { // M12 ?>
        <th data-name="M12" class="<?= $Page->M12->headerCellClass() ?>"><div id="elh_tipo_documento_M12" class="tipo_documento_M12"><?= $Page->renderSort($Page->M12) ?></div></th>
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_tipo_documento", "data-rowtype" => $Page->RowType]);

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
    <?php if ($Page->descripcion->Visible) { // descripcion ?>
        <td data-name="descripcion" <?= $Page->descripcion->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tipo_documento_descripcion">
<span<?= $Page->descripcion->viewAttributes() ?>>
<?= $Page->descripcion->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->M01->Visible) { // M01 ?>
        <td data-name="M01" <?= $Page->M01->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tipo_documento_M01">
<span<?= $Page->M01->viewAttributes() ?>>
<?= $Page->M01->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->M02->Visible) { // M02 ?>
        <td data-name="M02" <?= $Page->M02->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tipo_documento_M02">
<span<?= $Page->M02->viewAttributes() ?>>
<?= $Page->M02->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->M03->Visible) { // M03 ?>
        <td data-name="M03" <?= $Page->M03->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tipo_documento_M03">
<span<?= $Page->M03->viewAttributes() ?>>
<?= $Page->M03->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->M04->Visible) { // M04 ?>
        <td data-name="M04" <?= $Page->M04->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tipo_documento_M04">
<span<?= $Page->M04->viewAttributes() ?>>
<?= $Page->M04->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->M05->Visible) { // M05 ?>
        <td data-name="M05" <?= $Page->M05->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tipo_documento_M05">
<span<?= $Page->M05->viewAttributes() ?>>
<?= $Page->M05->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->M06->Visible) { // M06 ?>
        <td data-name="M06" <?= $Page->M06->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tipo_documento_M06">
<span<?= $Page->M06->viewAttributes() ?>>
<?= $Page->M06->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->M07->Visible) { // M07 ?>
        <td data-name="M07" <?= $Page->M07->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tipo_documento_M07">
<span<?= $Page->M07->viewAttributes() ?>>
<?= $Page->M07->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->M08->Visible) { // M08 ?>
        <td data-name="M08" <?= $Page->M08->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tipo_documento_M08">
<span<?= $Page->M08->viewAttributes() ?>>
<?= $Page->M08->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->M09->Visible) { // M09 ?>
        <td data-name="M09" <?= $Page->M09->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tipo_documento_M09">
<span<?= $Page->M09->viewAttributes() ?>>
<?= $Page->M09->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->M10->Visible) { // M10 ?>
        <td data-name="M10" <?= $Page->M10->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tipo_documento_M10">
<span<?= $Page->M10->viewAttributes() ?>>
<?= $Page->M10->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->M11->Visible) { // M11 ?>
        <td data-name="M11" <?= $Page->M11->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tipo_documento_M11">
<span<?= $Page->M11->viewAttributes() ?>>
<?= $Page->M11->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->M12->Visible) { // M12 ?>
        <td data-name="M12" <?= $Page->M12->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tipo_documento_M12">
<span<?= $Page->M12->viewAttributes() ?>>
<?= $Page->M12->getViewValue() ?></span>
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
    ew.addEventHandlers("tipo_documento");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
