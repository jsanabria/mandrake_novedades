<?php

namespace PHPMaker2021\mandrake;

// Page object
$PagosProveedorList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fpagos_proveedorlist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    fpagos_proveedorlist = currentForm = new ew.Form("fpagos_proveedorlist", "list");
    fpagos_proveedorlist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';
    loadjs.done("fpagos_proveedorlist");
});
var fpagos_proveedorlistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    fpagos_proveedorlistsrch = currentSearchForm = new ew.Form("fpagos_proveedorlistsrch");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "pagos_proveedor")) ?>,
        fields = currentTable.fields;
    fpagos_proveedorlistsrch.addFields([
        ["id", [], fields.id.isInvalid],
        ["proveedor", [], fields.proveedor.isInvalid],
        ["tipo_pago", [], fields.tipo_pago.isInvalid],
        ["banco", [], fields.banco.isInvalid],
        ["fecha", [], fields.fecha.isInvalid],
        ["referencia", [], fields.referencia.isInvalid],
        ["moneda", [], fields.moneda.isInvalid],
        ["monto_dado", [], fields.monto_dado.isInvalid],
        ["monto", [], fields.monto.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        fpagos_proveedorlistsrch.setInvalid();
    });

    // Validate form
    fpagos_proveedorlistsrch.validate = function () {
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
    fpagos_proveedorlistsrch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fpagos_proveedorlistsrch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fpagos_proveedorlistsrch.lists.proveedor = <?= $Page->proveedor->toClientList($Page) ?>;

    // Filters
    fpagos_proveedorlistsrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    fpagos_proveedorlistsrch.initSearchPanel = true;
    loadjs.done("fpagos_proveedorlistsrch");
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
<form name="fpagos_proveedorlistsrch" id="fpagos_proveedorlistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fpagos_proveedorlistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="pagos_proveedor">
    <div class="ew-extended-search">
<?php
// Render search row
$Page->RowType = ROWTYPE_SEARCH;
$Page->resetAttributes();
$Page->renderRow();
?>
<?php if ($Page->proveedor->Visible) { // proveedor ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_proveedor" class="ew-cell form-group">
        <label for="x_proveedor" class="ew-search-caption ew-label"><?= $Page->proveedor->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_proveedor" id="z_proveedor" value="=">
</span>
        <span id="el_pagos_proveedor_proveedor" class="ew-search-field">
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x_proveedor"><?= EmptyValue(strval($Page->proveedor->AdvancedSearch->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->proveedor->AdvancedSearch->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->proveedor->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->proveedor->ReadOnly || $Page->proveedor->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x_proveedor',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->proveedor->getErrorMessage(false) ?></div>
<?= $Page->proveedor->Lookup->getParamTag($Page, "p_x_proveedor") ?>
<input type="hidden" is="selection-list" data-table="pagos_proveedor" data-field="x_proveedor" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->proveedor->displayValueSeparatorAttribute() ?>" name="x_proveedor" id="x_proveedor" value="<?= $Page->proveedor->AdvancedSearch->SearchValue ?>"<?= $Page->proveedor->editAttributes() ?>>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> pagos_proveedor">
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
<form name="fpagos_proveedorlist" id="fpagos_proveedorlist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="pagos_proveedor">
<div id="gmp_pagos_proveedor" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_pagos_proveedorlist" class="table ew-table"><!-- .ew-table -->
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
        <th data-name="id" class="<?= $Page->id->headerCellClass() ?>"><div id="elh_pagos_proveedor_id" class="pagos_proveedor_id"><?= $Page->renderSort($Page->id) ?></div></th>
<?php } ?>
<?php if ($Page->proveedor->Visible) { // proveedor ?>
        <th data-name="proveedor" class="<?= $Page->proveedor->headerCellClass() ?>"><div id="elh_pagos_proveedor_proveedor" class="pagos_proveedor_proveedor"><?= $Page->renderSort($Page->proveedor) ?></div></th>
<?php } ?>
<?php if ($Page->tipo_pago->Visible) { // tipo_pago ?>
        <th data-name="tipo_pago" class="<?= $Page->tipo_pago->headerCellClass() ?>"><div id="elh_pagos_proveedor_tipo_pago" class="pagos_proveedor_tipo_pago"><?= $Page->renderSort($Page->tipo_pago) ?></div></th>
<?php } ?>
<?php if ($Page->banco->Visible) { // banco ?>
        <th data-name="banco" class="<?= $Page->banco->headerCellClass() ?>"><div id="elh_pagos_proveedor_banco" class="pagos_proveedor_banco"><?= $Page->renderSort($Page->banco) ?></div></th>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <th data-name="fecha" class="<?= $Page->fecha->headerCellClass() ?>"><div id="elh_pagos_proveedor_fecha" class="pagos_proveedor_fecha"><?= $Page->renderSort($Page->fecha) ?></div></th>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
        <th data-name="referencia" class="<?= $Page->referencia->headerCellClass() ?>"><div id="elh_pagos_proveedor_referencia" class="pagos_proveedor_referencia"><?= $Page->renderSort($Page->referencia) ?></div></th>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
        <th data-name="moneda" class="<?= $Page->moneda->headerCellClass() ?>"><div id="elh_pagos_proveedor_moneda" class="pagos_proveedor_moneda"><?= $Page->renderSort($Page->moneda) ?></div></th>
<?php } ?>
<?php if ($Page->monto_dado->Visible) { // monto_dado ?>
        <th data-name="monto_dado" class="<?= $Page->monto_dado->headerCellClass() ?>"><div id="elh_pagos_proveedor_monto_dado" class="pagos_proveedor_monto_dado"><?= $Page->renderSort($Page->monto_dado) ?></div></th>
<?php } ?>
<?php if ($Page->monto->Visible) { // monto ?>
        <th data-name="monto" class="<?= $Page->monto->headerCellClass() ?>"><div id="elh_pagos_proveedor_monto" class="pagos_proveedor_monto"><?= $Page->renderSort($Page->monto) ?></div></th>
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_pagos_proveedor", "data-rowtype" => $Page->RowType]);

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
<span id="el<?= $Page->RowCount ?>_pagos_proveedor_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->proveedor->Visible) { // proveedor ?>
        <td data-name="proveedor" <?= $Page->proveedor->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pagos_proveedor_proveedor">
<span<?= $Page->proveedor->viewAttributes() ?>>
<?= $Page->proveedor->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->tipo_pago->Visible) { // tipo_pago ?>
        <td data-name="tipo_pago" <?= $Page->tipo_pago->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pagos_proveedor_tipo_pago">
<span<?= $Page->tipo_pago->viewAttributes() ?>>
<?= $Page->tipo_pago->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->banco->Visible) { // banco ?>
        <td data-name="banco" <?= $Page->banco->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pagos_proveedor_banco">
<span<?= $Page->banco->viewAttributes() ?>>
<?= $Page->banco->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->fecha->Visible) { // fecha ?>
        <td data-name="fecha" <?= $Page->fecha->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pagos_proveedor_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->referencia->Visible) { // referencia ?>
        <td data-name="referencia" <?= $Page->referencia->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pagos_proveedor_referencia">
<span<?= $Page->referencia->viewAttributes() ?>>
<?= $Page->referencia->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->moneda->Visible) { // moneda ?>
        <td data-name="moneda" <?= $Page->moneda->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pagos_proveedor_moneda">
<span<?= $Page->moneda->viewAttributes() ?>>
<?= $Page->moneda->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->monto_dado->Visible) { // monto_dado ?>
        <td data-name="monto_dado" <?= $Page->monto_dado->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pagos_proveedor_monto_dado">
<span<?= $Page->monto_dado->viewAttributes() ?>>
<?= $Page->monto_dado->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->monto->Visible) { // monto ?>
        <td data-name="monto" <?= $Page->monto->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pagos_proveedor_monto">
<span<?= $Page->monto->viewAttributes() ?>>
<?= $Page->monto->getViewValue() ?></span>
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
    ew.addEventHandlers("pagos_proveedor");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
