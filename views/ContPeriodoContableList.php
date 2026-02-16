<?php

namespace PHPMaker2021\mandrake;

// Page object
$ContPeriodoContableList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fcont_periodo_contablelist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    fcont_periodo_contablelist = currentForm = new ew.Form("fcont_periodo_contablelist", "list");
    fcont_periodo_contablelist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';
    loadjs.done("fcont_periodo_contablelist");
});
var fcont_periodo_contablelistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    fcont_periodo_contablelistsrch = currentSearchForm = new ew.Form("fcont_periodo_contablelistsrch");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "cont_periodo_contable")) ?>,
        fields = currentTable.fields;
    fcont_periodo_contablelistsrch.addFields([
        ["id", [], fields.id.isInvalid],
        ["fecha_inicio", [], fields.fecha_inicio.isInvalid],
        ["fecha_fin", [], fields.fecha_fin.isInvalid],
        ["cerrado", [], fields.cerrado.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        fcont_periodo_contablelistsrch.setInvalid();
    });

    // Validate form
    fcont_periodo_contablelistsrch.validate = function () {
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
    fcont_periodo_contablelistsrch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fcont_periodo_contablelistsrch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fcont_periodo_contablelistsrch.lists.cerrado = <?= $Page->cerrado->toClientList($Page) ?>;

    // Filters
    fcont_periodo_contablelistsrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    fcont_periodo_contablelistsrch.initSearchPanel = true;
    loadjs.done("fcont_periodo_contablelistsrch");
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
<form name="fcont_periodo_contablelistsrch" id="fcont_periodo_contablelistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fcont_periodo_contablelistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="cont_periodo_contable">
    <div class="ew-extended-search">
<?php
// Render search row
$Page->RowType = ROWTYPE_SEARCH;
$Page->resetAttributes();
$Page->renderRow();
?>
<?php if ($Page->cerrado->Visible) { // cerrado ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_cerrado" class="ew-cell form-group">
        <label for="x_cerrado" class="ew-search-caption ew-label"><?= $Page->cerrado->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_cerrado" id="z_cerrado" value="=">
</span>
        <span id="el_cont_periodo_contable_cerrado" class="ew-search-field">
    <select
        id="x_cerrado"
        name="x_cerrado"
        class="form-control ew-select<?= $Page->cerrado->isInvalidClass() ?>"
        data-select2-id="cont_periodo_contable_x_cerrado"
        data-table="cont_periodo_contable"
        data-field="x_cerrado"
        data-value-separator="<?= $Page->cerrado->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->cerrado->getPlaceHolder()) ?>"
        <?= $Page->cerrado->editAttributes() ?>>
        <?= $Page->cerrado->selectOptionListHtml("x_cerrado") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->cerrado->getErrorMessage(false) ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='cont_periodo_contable_x_cerrado']"),
        options = { name: "x_cerrado", selectId: "cont_periodo_contable_x_cerrado", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.cont_periodo_contable.fields.cerrado.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.cont_periodo_contable.fields.cerrado.selectOptions);
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
    <button class="btn btn-primary" name="btn-submit" id="btn-submit" type="submit"><?= $Language->phrase("SearchBtn") ?></button>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> cont_periodo_contable">
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
<form name="fcont_periodo_contablelist" id="fcont_periodo_contablelist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cont_periodo_contable">
<div id="gmp_cont_periodo_contable" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_cont_periodo_contablelist" class="table ew-table"><!-- .ew-table -->
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
        <th data-name="id" class="<?= $Page->id->headerCellClass() ?>"><div id="elh_cont_periodo_contable_id" class="cont_periodo_contable_id"><?= $Page->renderSort($Page->id) ?></div></th>
<?php } ?>
<?php if ($Page->fecha_inicio->Visible) { // fecha_inicio ?>
        <th data-name="fecha_inicio" class="<?= $Page->fecha_inicio->headerCellClass() ?>"><div id="elh_cont_periodo_contable_fecha_inicio" class="cont_periodo_contable_fecha_inicio"><?= $Page->renderSort($Page->fecha_inicio) ?></div></th>
<?php } ?>
<?php if ($Page->fecha_fin->Visible) { // fecha_fin ?>
        <th data-name="fecha_fin" class="<?= $Page->fecha_fin->headerCellClass() ?>"><div id="elh_cont_periodo_contable_fecha_fin" class="cont_periodo_contable_fecha_fin"><?= $Page->renderSort($Page->fecha_fin) ?></div></th>
<?php } ?>
<?php if ($Page->cerrado->Visible) { // cerrado ?>
        <th data-name="cerrado" class="<?= $Page->cerrado->headerCellClass() ?>"><div id="elh_cont_periodo_contable_cerrado" class="cont_periodo_contable_cerrado"><?= $Page->renderSort($Page->cerrado) ?></div></th>
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_cont_periodo_contable", "data-rowtype" => $Page->RowType]);

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
<span id="el<?= $Page->RowCount ?>_cont_periodo_contable_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->fecha_inicio->Visible) { // fecha_inicio ?>
        <td data-name="fecha_inicio" <?= $Page->fecha_inicio->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_periodo_contable_fecha_inicio">
<span<?= $Page->fecha_inicio->viewAttributes() ?>>
<?= $Page->fecha_inicio->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->fecha_fin->Visible) { // fecha_fin ?>
        <td data-name="fecha_fin" <?= $Page->fecha_fin->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_periodo_contable_fecha_fin">
<span<?= $Page->fecha_fin->viewAttributes() ?>>
<?= $Page->fecha_fin->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->cerrado->Visible) { // cerrado ?>
        <td data-name="cerrado" <?= $Page->cerrado->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_periodo_contable_cerrado">
<span<?= $Page->cerrado->viewAttributes() ?>>
<?= $Page->cerrado->getViewValue() ?></span>
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
    ew.addEventHandlers("cont_periodo_contable");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
