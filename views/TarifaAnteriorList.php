<?php

namespace PHPMaker2021\mandrake;

// Page object
$TarifaAnteriorList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var ftarifa_anteriorlist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    ftarifa_anteriorlist = currentForm = new ew.Form("ftarifa_anteriorlist", "list");
    ftarifa_anteriorlist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';
    loadjs.done("ftarifa_anteriorlist");
});
var ftarifa_anteriorlistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    ftarifa_anteriorlistsrch = currentSearchForm = new ew.Form("ftarifa_anteriorlistsrch");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "tarifa_anterior")) ?>,
        fields = currentTable.fields;
    ftarifa_anteriorlistsrch.addFields([
        ["tarifa", [ew.Validators.integer], fields.tarifa.isInvalid],
        ["codigo", [], fields.codigo.isInvalid],
        ["fabricante", [ew.Validators.integer], fields.fabricante.isInvalid],
        ["articulo", [], fields.articulo.isInvalid],
        ["precio_anterior", [], fields.precio_anterior.isInvalid],
        ["precio_nuevo", [], fields.precio_nuevo.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        ftarifa_anteriorlistsrch.setInvalid();
    });

    // Validate form
    ftarifa_anteriorlistsrch.validate = function () {
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
    ftarifa_anteriorlistsrch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    ftarifa_anteriorlistsrch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    ftarifa_anteriorlistsrch.lists.tarifa = <?= $Page->tarifa->toClientList($Page) ?>;
    ftarifa_anteriorlistsrch.lists.fabricante = <?= $Page->fabricante->toClientList($Page) ?>;

    // Filters
    ftarifa_anteriorlistsrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    ftarifa_anteriorlistsrch.initSearchPanel = true;
    loadjs.done("ftarifa_anteriorlistsrch");
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
<form name="ftarifa_anteriorlistsrch" id="ftarifa_anteriorlistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="ftarifa_anteriorlistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="tarifa_anterior">
    <div class="ew-extended-search">
<?php
// Render search row
$Page->RowType = ROWTYPE_SEARCH;
$Page->resetAttributes();
$Page->renderRow();
?>
<?php if ($Page->tarifa->Visible) { // tarifa ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_tarifa" class="ew-cell form-group">
        <label class="ew-search-caption ew-label"><?= $Page->tarifa->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_tarifa" id="z_tarifa" value="=">
</span>
        <span id="el_tarifa_anterior_tarifa" class="ew-search-field">
<?php
$onchange = $Page->tarifa->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Page->tarifa->EditAttrs["onchange"] = "";
?>
<span id="as_x_tarifa" class="ew-auto-suggest">
    <input type="<?= $Page->tarifa->getInputTextType() ?>" class="form-control" name="sv_x_tarifa" id="sv_x_tarifa" value="<?= RemoveHtml($Page->tarifa->EditValue) ?>" size="30" placeholder="<?= HtmlEncode($Page->tarifa->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->tarifa->getPlaceHolder()) ?>"<?= $Page->tarifa->editAttributes() ?>>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="tarifa_anterior" data-field="x_tarifa" data-input="sv_x_tarifa" data-value-separator="<?= $Page->tarifa->displayValueSeparatorAttribute() ?>" name="x_tarifa" id="x_tarifa" value="<?= HtmlEncode($Page->tarifa->AdvancedSearch->SearchValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Page->tarifa->getErrorMessage(false) ?></div>
<script>
loadjs.ready(["ftarifa_anteriorlistsrch"], function() {
    ftarifa_anteriorlistsrch.createAutoSuggest(Object.assign({"id":"x_tarifa","forceSelect":false}, ew.vars.tables.tarifa_anterior.fields.tarifa.autoSuggestOptions));
});
</script>
<?= $Page->tarifa->Lookup->getParamTag($Page, "p_x_tarifa") ?>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
<?php if ($Page->fabricante->Visible) { // fabricante ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_fabricante" class="ew-cell form-group">
        <label class="ew-search-caption ew-label"><?= $Page->fabricante->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_fabricante" id="z_fabricante" value="=">
</span>
        <span id="el_tarifa_anterior_fabricante" class="ew-search-field">
<?php
$onchange = $Page->fabricante->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Page->fabricante->EditAttrs["onchange"] = "";
?>
<span id="as_x_fabricante" class="ew-auto-suggest">
    <input type="<?= $Page->fabricante->getInputTextType() ?>" class="form-control" name="sv_x_fabricante" id="sv_x_fabricante" value="<?= RemoveHtml($Page->fabricante->EditValue) ?>" size="30" placeholder="<?= HtmlEncode($Page->fabricante->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->fabricante->getPlaceHolder()) ?>"<?= $Page->fabricante->editAttributes() ?>>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="tarifa_anterior" data-field="x_fabricante" data-input="sv_x_fabricante" data-value-separator="<?= $Page->fabricante->displayValueSeparatorAttribute() ?>" name="x_fabricante" id="x_fabricante" value="<?= HtmlEncode($Page->fabricante->AdvancedSearch->SearchValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Page->fabricante->getErrorMessage(false) ?></div>
<script>
loadjs.ready(["ftarifa_anteriorlistsrch"], function() {
    ftarifa_anteriorlistsrch.createAutoSuggest(Object.assign({"id":"x_fabricante","forceSelect":false}, ew.vars.tables.tarifa_anterior.fields.fabricante.autoSuggestOptions));
});
</script>
<?= $Page->fabricante->Lookup->getParamTag($Page, "p_x_fabricante") ?>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> tarifa_anterior">
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
<form name="ftarifa_anteriorlist" id="ftarifa_anteriorlist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="tarifa_anterior">
<div id="gmp_tarifa_anterior" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_tarifa_anteriorlist" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Page->tarifa->Visible) { // tarifa ?>
        <th data-name="tarifa" class="<?= $Page->tarifa->headerCellClass() ?>"><div id="elh_tarifa_anterior_tarifa" class="tarifa_anterior_tarifa"><?= $Page->renderSort($Page->tarifa) ?></div></th>
<?php } ?>
<?php if ($Page->codigo->Visible) { // codigo ?>
        <th data-name="codigo" class="<?= $Page->codigo->headerCellClass() ?>"><div id="elh_tarifa_anterior_codigo" class="tarifa_anterior_codigo"><?= $Page->renderSort($Page->codigo) ?></div></th>
<?php } ?>
<?php if ($Page->fabricante->Visible) { // fabricante ?>
        <th data-name="fabricante" class="<?= $Page->fabricante->headerCellClass() ?>"><div id="elh_tarifa_anterior_fabricante" class="tarifa_anterior_fabricante"><?= $Page->renderSort($Page->fabricante) ?></div></th>
<?php } ?>
<?php if ($Page->articulo->Visible) { // articulo ?>
        <th data-name="articulo" class="<?= $Page->articulo->headerCellClass() ?>"><div id="elh_tarifa_anterior_articulo" class="tarifa_anterior_articulo"><?= $Page->renderSort($Page->articulo) ?></div></th>
<?php } ?>
<?php if ($Page->precio_anterior->Visible) { // precio_anterior ?>
        <th data-name="precio_anterior" class="<?= $Page->precio_anterior->headerCellClass() ?>"><div id="elh_tarifa_anterior_precio_anterior" class="tarifa_anterior_precio_anterior"><?= $Page->renderSort($Page->precio_anterior) ?></div></th>
<?php } ?>
<?php if ($Page->precio_nuevo->Visible) { // precio_nuevo ?>
        <th data-name="precio_nuevo" class="<?= $Page->precio_nuevo->headerCellClass() ?>"><div id="elh_tarifa_anterior_precio_nuevo" class="tarifa_anterior_precio_nuevo"><?= $Page->renderSort($Page->precio_nuevo) ?></div></th>
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_tarifa_anterior", "data-rowtype" => $Page->RowType]);

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
    <?php if ($Page->tarifa->Visible) { // tarifa ?>
        <td data-name="tarifa" <?= $Page->tarifa->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tarifa_anterior_tarifa">
<span<?= $Page->tarifa->viewAttributes() ?>>
<?= $Page->tarifa->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->codigo->Visible) { // codigo ?>
        <td data-name="codigo" <?= $Page->codigo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tarifa_anterior_codigo">
<span<?= $Page->codigo->viewAttributes() ?>>
<?= $Page->codigo->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->fabricante->Visible) { // fabricante ?>
        <td data-name="fabricante" <?= $Page->fabricante->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tarifa_anterior_fabricante">
<span<?= $Page->fabricante->viewAttributes() ?>>
<?= $Page->fabricante->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->articulo->Visible) { // articulo ?>
        <td data-name="articulo" <?= $Page->articulo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tarifa_anterior_articulo">
<span<?= $Page->articulo->viewAttributes() ?>>
<?= $Page->articulo->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->precio_anterior->Visible) { // precio_anterior ?>
        <td data-name="precio_anterior" <?= $Page->precio_anterior->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tarifa_anterior_precio_anterior">
<span<?= $Page->precio_anterior->viewAttributes() ?>>
<?= $Page->precio_anterior->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->precio_nuevo->Visible) { // precio_nuevo ?>
        <td data-name="precio_nuevo" <?= $Page->precio_nuevo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_tarifa_anterior_precio_nuevo">
<span<?= $Page->precio_nuevo->viewAttributes() ?>>
<?= $Page->precio_nuevo->getViewValue() ?></span>
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
    ew.addEventHandlers("tarifa_anterior");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
