<?php

namespace PHPMaker2021\mandrake;

// Page object
$CompaniaList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fcompanialist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    fcompanialist = currentForm = new ew.Form("fcompanialist", "list");
    fcompanialist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';
    loadjs.done("fcompanialist");
});
var fcompanialistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    fcompanialistsrch = currentSearchForm = new ew.Form("fcompanialistsrch");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "compania")) ?>,
        fields = currentTable.fields;
    fcompanialistsrch.addFields([
        ["ci_rif", [], fields.ci_rif.isInvalid],
        ["nombre", [], fields.nombre.isInvalid],
        ["ciudad", [], fields.ciudad.isInvalid],
        ["email1", [], fields.email1.isInvalid],
        ["agente_retencion", [], fields.agente_retencion.isInvalid],
        ["logo", [], fields.logo.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        fcompanialistsrch.setInvalid();
    });

    // Validate form
    fcompanialistsrch.validate = function () {
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
    fcompanialistsrch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fcompanialistsrch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fcompanialistsrch.lists.agente_retencion = <?= $Page->agente_retencion->toClientList($Page) ?>;

    // Filters
    fcompanialistsrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    fcompanialistsrch.initSearchPanel = true;
    loadjs.done("fcompanialistsrch");
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
<form name="fcompanialistsrch" id="fcompanialistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fcompanialistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="compania">
    <div class="ew-extended-search">
<?php
// Render search row
$Page->RowType = ROWTYPE_SEARCH;
$Page->resetAttributes();
$Page->renderRow();
?>
<?php if ($Page->agente_retencion->Visible) { // agente_retencion ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_agente_retencion" class="ew-cell form-group">
        <label for="x_agente_retencion" class="ew-search-caption ew-label"><?= $Page->agente_retencion->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_agente_retencion" id="z_agente_retencion" value="=">
</span>
        <span id="el_compania_agente_retencion" class="ew-search-field">
    <select
        id="x_agente_retencion"
        name="x_agente_retencion"
        class="form-control ew-select<?= $Page->agente_retencion->isInvalidClass() ?>"
        data-select2-id="compania_x_agente_retencion"
        data-table="compania"
        data-field="x_agente_retencion"
        data-value-separator="<?= $Page->agente_retencion->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->agente_retencion->getPlaceHolder()) ?>"
        <?= $Page->agente_retencion->editAttributes() ?>>
        <?= $Page->agente_retencion->selectOptionListHtml("x_agente_retencion") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->agente_retencion->getErrorMessage(false) ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='compania_x_agente_retencion']"),
        options = { name: "x_agente_retencion", selectId: "compania_x_agente_retencion", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.compania.fields.agente_retencion.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.compania.fields.agente_retencion.selectOptions);
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> compania">
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
<form name="fcompanialist" id="fcompanialist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="compania">
<div id="gmp_compania" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_companialist" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Page->ci_rif->Visible) { // ci_rif ?>
        <th data-name="ci_rif" class="<?= $Page->ci_rif->headerCellClass() ?>"><div id="elh_compania_ci_rif" class="compania_ci_rif"><?= $Page->renderSort($Page->ci_rif) ?></div></th>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
        <th data-name="nombre" class="<?= $Page->nombre->headerCellClass() ?>"><div id="elh_compania_nombre" class="compania_nombre"><?= $Page->renderSort($Page->nombre) ?></div></th>
<?php } ?>
<?php if ($Page->ciudad->Visible) { // ciudad ?>
        <th data-name="ciudad" class="<?= $Page->ciudad->headerCellClass() ?>"><div id="elh_compania_ciudad" class="compania_ciudad"><?= $Page->renderSort($Page->ciudad) ?></div></th>
<?php } ?>
<?php if ($Page->email1->Visible) { // email1 ?>
        <th data-name="email1" class="<?= $Page->email1->headerCellClass() ?>"><div id="elh_compania_email1" class="compania_email1"><?= $Page->renderSort($Page->email1) ?></div></th>
<?php } ?>
<?php if ($Page->agente_retencion->Visible) { // agente_retencion ?>
        <th data-name="agente_retencion" class="<?= $Page->agente_retencion->headerCellClass() ?>"><div id="elh_compania_agente_retencion" class="compania_agente_retencion"><?= $Page->renderSort($Page->agente_retencion) ?></div></th>
<?php } ?>
<?php if ($Page->logo->Visible) { // logo ?>
        <th data-name="logo" class="<?= $Page->logo->headerCellClass() ?>"><div id="elh_compania_logo" class="compania_logo"><?= $Page->renderSort($Page->logo) ?></div></th>
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_compania", "data-rowtype" => $Page->RowType]);

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
    <?php if ($Page->ci_rif->Visible) { // ci_rif ?>
        <td data-name="ci_rif" <?= $Page->ci_rif->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_compania_ci_rif">
<span<?= $Page->ci_rif->viewAttributes() ?>>
<?= $Page->ci_rif->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->nombre->Visible) { // nombre ?>
        <td data-name="nombre" <?= $Page->nombre->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_compania_nombre">
<span<?= $Page->nombre->viewAttributes() ?>>
<?= $Page->nombre->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->ciudad->Visible) { // ciudad ?>
        <td data-name="ciudad" <?= $Page->ciudad->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_compania_ciudad">
<span<?= $Page->ciudad->viewAttributes() ?>>
<?= $Page->ciudad->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->email1->Visible) { // email1 ?>
        <td data-name="email1" <?= $Page->email1->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_compania_email1">
<span<?= $Page->email1->viewAttributes() ?>>
<?= $Page->email1->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->agente_retencion->Visible) { // agente_retencion ?>
        <td data-name="agente_retencion" <?= $Page->agente_retencion->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_compania_agente_retencion">
<span<?= $Page->agente_retencion->viewAttributes() ?>>
<?= $Page->agente_retencion->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->logo->Visible) { // logo ?>
        <td data-name="logo" <?= $Page->logo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_compania_logo">
<span>
<?= GetFileViewTag($Page->logo, $Page->logo->getViewValue(), false) ?>
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
    ew.addEventHandlers("compania");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
