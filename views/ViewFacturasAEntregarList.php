<?php

namespace PHPMaker2021\mandrake;

// Page object
$ViewFacturasAEntregarList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fview_facturas_a_entregarlist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    fview_facturas_a_entregarlist = currentForm = new ew.Form("fview_facturas_a_entregarlist", "list");
    fview_facturas_a_entregarlist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';
    loadjs.done("fview_facturas_a_entregarlist");
});
var fview_facturas_a_entregarlistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    fview_facturas_a_entregarlistsrch = currentSearchForm = new ew.Form("fview_facturas_a_entregarlistsrch");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "view_facturas_a_entregar")) ?>,
        fields = currentTable.fields;
    fview_facturas_a_entregarlistsrch.addFields([
        ["tipo_documento", [], fields.tipo_documento.isInvalid],
        ["codcli", [], fields.codcli.isInvalid],
        ["nro_documento", [], fields.nro_documento.isInvalid],
        ["fecha", [], fields.fecha.isInvalid],
        ["total", [], fields.total.isInvalid],
        ["entregado", [], fields.entregado.isInvalid],
        ["fecha_entrega", [], fields.fecha_entrega.isInvalid],
        ["dias_credito", [], fields.dias_credito.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        fview_facturas_a_entregarlistsrch.setInvalid();
    });

    // Validate form
    fview_facturas_a_entregarlistsrch.validate = function () {
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
    fview_facturas_a_entregarlistsrch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fview_facturas_a_entregarlistsrch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fview_facturas_a_entregarlistsrch.lists.codcli = <?= $Page->codcli->toClientList($Page) ?>;
    fview_facturas_a_entregarlistsrch.lists.entregado = <?= $Page->entregado->toClientList($Page) ?>;

    // Filters
    fview_facturas_a_entregarlistsrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    fview_facturas_a_entregarlistsrch.initSearchPanel = true;
    loadjs.done("fview_facturas_a_entregarlistsrch");
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
    ew.PREVIEW_PLACEMENT = ew.CSS_FLIP ? "right" : "left";
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
<form name="fview_facturas_a_entregarlistsrch" id="fview_facturas_a_entregarlistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fview_facturas_a_entregarlistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="view_facturas_a_entregar">
    <div class="ew-extended-search">
<?php
// Render search row
$Page->RowType = ROWTYPE_SEARCH;
$Page->resetAttributes();
$Page->renderRow();
?>
<?php if ($Page->codcli->Visible) { // codcli ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_codcli" class="ew-cell form-group">
        <label for="x_codcli" class="ew-search-caption ew-label"><?= $Page->codcli->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_codcli" id="z_codcli" value="=">
</span>
        <span id="el_view_facturas_a_entregar_codcli" class="ew-search-field">
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x_codcli"><?= EmptyValue(strval($Page->codcli->AdvancedSearch->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->codcli->AdvancedSearch->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->codcli->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->codcli->ReadOnly || $Page->codcli->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x_codcli',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->codcli->getErrorMessage(false) ?></div>
<?= $Page->codcli->Lookup->getParamTag($Page, "p_x_codcli") ?>
<input type="hidden" is="selection-list" data-table="view_facturas_a_entregar" data-field="x_codcli" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->codcli->displayValueSeparatorAttribute() ?>" name="x_codcli" id="x_codcli" value="<?= $Page->codcli->AdvancedSearch->SearchValue ?>"<?= $Page->codcli->editAttributes() ?>>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
<?php if ($Page->entregado->Visible) { // entregado ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_entregado" class="ew-cell form-group">
        <label for="x_entregado" class="ew-search-caption ew-label"><?= $Page->entregado->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_entregado" id="z_entregado" value="=">
</span>
        <span id="el_view_facturas_a_entregar_entregado" class="ew-search-field">
    <select
        id="x_entregado"
        name="x_entregado"
        class="form-control ew-select<?= $Page->entregado->isInvalidClass() ?>"
        data-select2-id="view_facturas_a_entregar_x_entregado"
        data-table="view_facturas_a_entregar"
        data-field="x_entregado"
        data-value-separator="<?= $Page->entregado->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->entregado->getPlaceHolder()) ?>"
        <?= $Page->entregado->editAttributes() ?>>
        <?= $Page->entregado->selectOptionListHtml("x_entregado") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->entregado->getErrorMessage(false) ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='view_facturas_a_entregar_x_entregado']"),
        options = { name: "x_entregado", selectId: "view_facturas_a_entregar_x_entregado", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.view_facturas_a_entregar.fields.entregado.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.view_facturas_a_entregar.fields.entregado.selectOptions);
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> view_facturas_a_entregar">
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
<form name="fview_facturas_a_entregarlist" id="fview_facturas_a_entregarlist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="view_facturas_a_entregar">
<div id="gmp_view_facturas_a_entregar" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_view_facturas_a_entregarlist" class="table ew-table"><!-- .ew-table -->
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
        <th data-name="tipo_documento" class="<?= $Page->tipo_documento->headerCellClass() ?>"><div id="elh_view_facturas_a_entregar_tipo_documento" class="view_facturas_a_entregar_tipo_documento"><?= $Page->renderSort($Page->tipo_documento) ?></div></th>
<?php } ?>
<?php if ($Page->codcli->Visible) { // codcli ?>
        <th data-name="codcli" class="<?= $Page->codcli->headerCellClass() ?>"><div id="elh_view_facturas_a_entregar_codcli" class="view_facturas_a_entregar_codcli"><?= $Page->renderSort($Page->codcli) ?></div></th>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
        <th data-name="nro_documento" class="<?= $Page->nro_documento->headerCellClass() ?>"><div id="elh_view_facturas_a_entregar_nro_documento" class="view_facturas_a_entregar_nro_documento"><?= $Page->renderSort($Page->nro_documento) ?></div></th>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <th data-name="fecha" class="<?= $Page->fecha->headerCellClass() ?>"><div id="elh_view_facturas_a_entregar_fecha" class="view_facturas_a_entregar_fecha"><?= $Page->renderSort($Page->fecha) ?></div></th>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
        <th data-name="total" class="<?= $Page->total->headerCellClass() ?>"><div id="elh_view_facturas_a_entregar_total" class="view_facturas_a_entregar_total"><?= $Page->renderSort($Page->total) ?></div></th>
<?php } ?>
<?php if ($Page->entregado->Visible) { // entregado ?>
        <th data-name="entregado" class="<?= $Page->entregado->headerCellClass() ?>"><div id="elh_view_facturas_a_entregar_entregado" class="view_facturas_a_entregar_entregado"><?= $Page->renderSort($Page->entregado) ?></div></th>
<?php } ?>
<?php if ($Page->fecha_entrega->Visible) { // fecha_entrega ?>
        <th data-name="fecha_entrega" class="<?= $Page->fecha_entrega->headerCellClass() ?>"><div id="elh_view_facturas_a_entregar_fecha_entrega" class="view_facturas_a_entregar_fecha_entrega"><?= $Page->renderSort($Page->fecha_entrega) ?></div></th>
<?php } ?>
<?php if ($Page->dias_credito->Visible) { // dias_credito ?>
        <th data-name="dias_credito" class="<?= $Page->dias_credito->headerCellClass() ?>"><div id="elh_view_facturas_a_entregar_dias_credito" class="view_facturas_a_entregar_dias_credito"><?= $Page->renderSort($Page->dias_credito) ?></div></th>
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_view_facturas_a_entregar", "data-rowtype" => $Page->RowType]);

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
<span id="el<?= $Page->RowCount ?>_view_facturas_a_entregar_tipo_documento">
<span<?= $Page->tipo_documento->viewAttributes() ?>>
<?= $Page->tipo_documento->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->codcli->Visible) { // codcli ?>
        <td data-name="codcli" <?= $Page->codcli->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_facturas_a_entregar_codcli">
<span<?= $Page->codcli->viewAttributes() ?>>
<?= $Page->codcli->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->nro_documento->Visible) { // nro_documento ?>
        <td data-name="nro_documento" <?= $Page->nro_documento->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_facturas_a_entregar_nro_documento">
<span<?= $Page->nro_documento->viewAttributes() ?>>
<?= $Page->nro_documento->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->fecha->Visible) { // fecha ?>
        <td data-name="fecha" <?= $Page->fecha->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_facturas_a_entregar_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->total->Visible) { // total ?>
        <td data-name="total" <?= $Page->total->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_facturas_a_entregar_total">
<span<?= $Page->total->viewAttributes() ?>>
<?= $Page->total->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->entregado->Visible) { // entregado ?>
        <td data-name="entregado" <?= $Page->entregado->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_facturas_a_entregar_entregado">
<span<?= $Page->entregado->viewAttributes() ?>>
<?= $Page->entregado->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->fecha_entrega->Visible) { // fecha_entrega ?>
        <td data-name="fecha_entrega" <?= $Page->fecha_entrega->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_facturas_a_entregar_fecha_entrega">
<span<?= $Page->fecha_entrega->viewAttributes() ?>>
<?= $Page->fecha_entrega->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->dias_credito->Visible) { // dias_credito ?>
        <td data-name="dias_credito" <?= $Page->dias_credito->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_facturas_a_entregar_dias_credito">
<span<?= $Page->dias_credito->viewAttributes() ?>>
<?= $Page->dias_credito->getViewValue() ?></span>
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
    ew.addEventHandlers("view_facturas_a_entregar");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
