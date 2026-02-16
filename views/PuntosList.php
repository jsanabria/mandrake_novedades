<?php

namespace PHPMaker2021\mandrake;

// Page object
$PuntosList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fpuntoslist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    fpuntoslist = currentForm = new ew.Form("fpuntoslist", "list");
    fpuntoslist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';
    loadjs.done("fpuntoslist");
});
var fpuntoslistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    fpuntoslistsrch = currentSearchForm = new ew.Form("fpuntoslistsrch");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "puntos")) ?>,
        fields = currentTable.fields;
    fpuntoslistsrch.addFields([
        ["id", [], fields.id.isInvalid],
        ["cliente", [ew.Validators.integer], fields.cliente.isInvalid],
        ["fecha", [], fields.fecha.isInvalid],
        ["tipo", [], fields.tipo.isInvalid],
        ["nro_documento", [], fields.nro_documento.isInvalid],
        ["referencia", [], fields.referencia.isInvalid],
        ["nota", [], fields.nota.isInvalid],
        ["puntos", [], fields.puntos.isInvalid],
        ["saldo", [], fields.saldo.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        fpuntoslistsrch.setInvalid();
    });

    // Validate form
    fpuntoslistsrch.validate = function () {
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
    fpuntoslistsrch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fpuntoslistsrch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fpuntoslistsrch.lists.cliente = <?= $Page->cliente->toClientList($Page) ?>;
    fpuntoslistsrch.lists.referencia = <?= $Page->referencia->toClientList($Page) ?>;

    // Filters
    fpuntoslistsrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    fpuntoslistsrch.initSearchPanel = true;
    loadjs.done("fpuntoslistsrch");
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
<form name="fpuntoslistsrch" id="fpuntoslistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fpuntoslistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="puntos">
    <div class="ew-extended-search">
<?php
// Render search row
$Page->RowType = ROWTYPE_SEARCH;
$Page->resetAttributes();
$Page->renderRow();
?>
<?php if ($Page->cliente->Visible) { // cliente ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_cliente" class="ew-cell form-group">
        <label class="ew-search-caption ew-label"><?= $Page->cliente->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_cliente" id="z_cliente" value="=">
</span>
        <span id="el_puntos_cliente" class="ew-search-field">
<?php
$onchange = $Page->cliente->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Page->cliente->EditAttrs["onchange"] = "";
?>
<span id="as_x_cliente" class="ew-auto-suggest">
    <div class="input-group flex-nowrap">
        <input type="<?= $Page->cliente->getInputTextType() ?>" class="form-control" name="sv_x_cliente" id="sv_x_cliente" value="<?= RemoveHtml($Page->cliente->EditValue) ?>" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->cliente->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->cliente->getPlaceHolder()) ?>"<?= $Page->cliente->editAttributes() ?>>
        <div class="input-group-append">
            <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->cliente->caption()), $Language->phrase("LookupLink", true))) ?>" onclick="ew.modalLookupShow({lnk:this,el:'x_cliente',m:0,n:10,srch:false});" class="ew-lookup-btn btn btn-default"<?= ($Page->cliente->ReadOnly || $Page->cliente->Disabled) ? " disabled" : "" ?>><i class="fas fa-search ew-icon"></i></button>
        </div>
    </div>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="puntos" data-field="x_cliente" data-input="sv_x_cliente" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->cliente->displayValueSeparatorAttribute() ?>" name="x_cliente" id="x_cliente" value="<?= HtmlEncode($Page->cliente->AdvancedSearch->SearchValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Page->cliente->getErrorMessage(false) ?></div>
<script>
loadjs.ready(["fpuntoslistsrch"], function() {
    fpuntoslistsrch.createAutoSuggest(Object.assign({"id":"x_cliente","forceSelect":true}, ew.vars.tables.puntos.fields.cliente.autoSuggestOptions));
});
</script>
<?= $Page->cliente->Lookup->getParamTag($Page, "p_x_cliente") ?>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_nro_documento" class="ew-cell form-group">
        <label for="x_nro_documento" class="ew-search-caption ew-label"><?= $Page->nro_documento->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_nro_documento" id="z_nro_documento" value="LIKE">
</span>
        <span id="el_puntos_nro_documento" class="ew-search-field">
<input type="<?= $Page->nro_documento->getInputTextType() ?>" data-table="puntos" data-field="x_nro_documento" name="x_nro_documento" id="x_nro_documento" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->nro_documento->getPlaceHolder()) ?>" value="<?= $Page->nro_documento->EditValue ?>"<?= $Page->nro_documento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->nro_documento->getErrorMessage(false) ?></div>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_referencia" class="ew-cell form-group">
        <label class="ew-search-caption ew-label"><?= $Page->referencia->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_referencia" id="z_referencia" value="LIKE">
</span>
        <span id="el_puntos_referencia" class="ew-search-field">
<?php
$onchange = $Page->referencia->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Page->referencia->EditAttrs["onchange"] = "";
?>
<span id="as_x_referencia" class="ew-auto-suggest">
    <div class="input-group flex-nowrap">
        <input type="<?= $Page->referencia->getInputTextType() ?>" class="form-control" name="sv_x_referencia" id="sv_x_referencia" value="<?= RemoveHtml($Page->referencia->EditValue) ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->referencia->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->referencia->getPlaceHolder()) ?>"<?= $Page->referencia->editAttributes() ?>>
        <div class="input-group-append">
            <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->referencia->caption()), $Language->phrase("LookupLink", true))) ?>" onclick="ew.modalLookupShow({lnk:this,el:'x_referencia',m:0,n:10,srch:false});" class="ew-lookup-btn btn btn-default"<?= ($Page->referencia->ReadOnly || $Page->referencia->Disabled) ? " disabled" : "" ?>><i class="fas fa-search ew-icon"></i></button>
        </div>
    </div>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="puntos" data-field="x_referencia" data-input="sv_x_referencia" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->referencia->displayValueSeparatorAttribute() ?>" name="x_referencia" id="x_referencia" value="<?= HtmlEncode($Page->referencia->AdvancedSearch->SearchValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Page->referencia->getErrorMessage(false) ?></div>
<script>
loadjs.ready(["fpuntoslistsrch"], function() {
    fpuntoslistsrch.createAutoSuggest(Object.assign({"id":"x_referencia","forceSelect":true}, ew.vars.tables.puntos.fields.referencia.autoSuggestOptions));
});
</script>
<?= $Page->referencia->Lookup->getParamTag($Page, "p_x_referencia") ?>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> puntos">
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
<form name="fpuntoslist" id="fpuntoslist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="puntos">
<div id="gmp_puntos" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_puntoslist" class="table ew-table"><!-- .ew-table -->
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
        <th data-name="id" class="<?= $Page->id->headerCellClass() ?>"><div id="elh_puntos_id" class="puntos_id"><?= $Page->renderSort($Page->id) ?></div></th>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
        <th data-name="cliente" class="<?= $Page->cliente->headerCellClass() ?>"><div id="elh_puntos_cliente" class="puntos_cliente"><?= $Page->renderSort($Page->cliente) ?></div></th>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <th data-name="fecha" class="<?= $Page->fecha->headerCellClass() ?>"><div id="elh_puntos_fecha" class="puntos_fecha"><?= $Page->renderSort($Page->fecha) ?></div></th>
<?php } ?>
<?php if ($Page->tipo->Visible) { // tipo ?>
        <th data-name="tipo" class="<?= $Page->tipo->headerCellClass() ?>"><div id="elh_puntos_tipo" class="puntos_tipo"><?= $Page->renderSort($Page->tipo) ?></div></th>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
        <th data-name="nro_documento" class="<?= $Page->nro_documento->headerCellClass() ?>"><div id="elh_puntos_nro_documento" class="puntos_nro_documento"><?= $Page->renderSort($Page->nro_documento) ?></div></th>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
        <th data-name="referencia" class="<?= $Page->referencia->headerCellClass() ?>"><div id="elh_puntos_referencia" class="puntos_referencia"><?= $Page->renderSort($Page->referencia) ?></div></th>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
        <th data-name="nota" class="<?= $Page->nota->headerCellClass() ?>"><div id="elh_puntos_nota" class="puntos_nota"><?= $Page->renderSort($Page->nota) ?></div></th>
<?php } ?>
<?php if ($Page->puntos->Visible) { // puntos ?>
        <th data-name="puntos" class="<?= $Page->puntos->headerCellClass() ?>"><div id="elh_puntos_puntos" class="puntos_puntos"><?= $Page->renderSort($Page->puntos) ?></div></th>
<?php } ?>
<?php if ($Page->saldo->Visible) { // saldo ?>
        <th data-name="saldo" class="<?= $Page->saldo->headerCellClass() ?>"><div id="elh_puntos_saldo" class="puntos_saldo"><?= $Page->renderSort($Page->saldo) ?></div></th>
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_puntos", "data-rowtype" => $Page->RowType]);

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
<span id="el<?= $Page->RowCount ?>_puntos_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->cliente->Visible) { // cliente ?>
        <td data-name="cliente" <?= $Page->cliente->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_puntos_cliente">
<span<?= $Page->cliente->viewAttributes() ?>>
<?= $Page->cliente->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->fecha->Visible) { // fecha ?>
        <td data-name="fecha" <?= $Page->fecha->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_puntos_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->tipo->Visible) { // tipo ?>
        <td data-name="tipo" <?= $Page->tipo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_puntos_tipo">
<span<?= $Page->tipo->viewAttributes() ?>>
<?= $Page->tipo->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->nro_documento->Visible) { // nro_documento ?>
        <td data-name="nro_documento" <?= $Page->nro_documento->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_puntos_nro_documento">
<span<?= $Page->nro_documento->viewAttributes() ?>>
<?= $Page->nro_documento->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->referencia->Visible) { // referencia ?>
        <td data-name="referencia" <?= $Page->referencia->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_puntos_referencia">
<span<?= $Page->referencia->viewAttributes() ?>>
<?= $Page->referencia->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->nota->Visible) { // nota ?>
        <td data-name="nota" <?= $Page->nota->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_puntos_nota">
<span<?= $Page->nota->viewAttributes() ?>>
<?= $Page->nota->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->puntos->Visible) { // puntos ?>
        <td data-name="puntos" <?= $Page->puntos->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_puntos_puntos">
<span<?= $Page->puntos->viewAttributes() ?>>
<?= $Page->puntos->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->saldo->Visible) { // saldo ?>
        <td data-name="saldo" <?= $Page->saldo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_puntos_saldo">
<span<?= $Page->saldo->viewAttributes() ?>>
<?= $Page->saldo->getViewValue() ?></span>
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
    ew.addEventHandlers("puntos");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
