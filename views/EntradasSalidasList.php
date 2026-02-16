<?php

namespace PHPMaker2021\mandrake;

// Page object
$EntradasSalidasList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fentradas_salidaslist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    fentradas_salidaslist = currentForm = new ew.Form("fentradas_salidaslist", "list");
    fentradas_salidaslist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "entradas_salidas")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.entradas_salidas)
        ew.vars.tables.entradas_salidas = currentTable;
    fentradas_salidaslist.addFields([
        ["articulo", [fields.articulo.visible && fields.articulo.required ? ew.Validators.required(fields.articulo.caption) : null, ew.Validators.integer], fields.articulo.isInvalid],
        ["cantidad_articulo", [fields.cantidad_articulo.visible && fields.cantidad_articulo.required ? ew.Validators.required(fields.cantidad_articulo.caption) : null, ew.Validators.float], fields.cantidad_articulo.isInvalid],
        ["precio_unidad_sin_desc", [fields.precio_unidad_sin_desc.visible && fields.precio_unidad_sin_desc.required ? ew.Validators.required(fields.precio_unidad_sin_desc.caption) : null, ew.Validators.float], fields.precio_unidad_sin_desc.isInvalid],
        ["descuento", [fields.descuento.visible && fields.descuento.required ? ew.Validators.required(fields.descuento.caption) : null, ew.Validators.float], fields.descuento.isInvalid],
        ["costo_unidad", [fields.costo_unidad.visible && fields.costo_unidad.required ? ew.Validators.required(fields.costo_unidad.caption) : null, ew.Validators.float], fields.costo_unidad.isInvalid],
        ["costo", [fields.costo.visible && fields.costo.required ? ew.Validators.required(fields.costo.caption) : null, ew.Validators.float], fields.costo.isInvalid],
        ["precio_unidad", [fields.precio_unidad.visible && fields.precio_unidad.required ? ew.Validators.required(fields.precio_unidad.caption) : null, ew.Validators.float], fields.precio_unidad.isInvalid],
        ["precio", [fields.precio.visible && fields.precio.required ? ew.Validators.required(fields.precio.caption) : null, ew.Validators.float], fields.precio.isInvalid],
        ["lote", [fields.lote.visible && fields.lote.required ? ew.Validators.required(fields.lote.caption) : null], fields.lote.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fentradas_salidaslist,
            fobj = f.getForm(),
            $fobj = $(fobj),
            $k = $fobj.find("#" + f.formKeyCountName), // Get key_count
            rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1,
            startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
        for (var i = startcnt; i <= rowcnt; i++) {
            var rowIndex = ($k[0]) ? String(i) : "";
            f.setInvalid(rowIndex);
        }
    });

    // Validate form
    fentradas_salidaslist.validate = function () {
        if (!this.validateRequired)
            return true; // Ignore validation
        var fobj = this.getForm(),
            $fobj = $(fobj);
        if ($fobj.find("#confirm").val() == "confirm")
            return true;
        var addcnt = 0,
            $k = $fobj.find("#" + this.formKeyCountName), // Get key_count
            rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1,
            startcnt = (rowcnt == 0) ? 0 : 1, // Check rowcnt == 0 => Inline-Add
            gridinsert = ["insert", "gridinsert"].includes($fobj.find("#action").val()) && $k[0];
        for (var i = startcnt; i <= rowcnt; i++) {
            var rowIndex = ($k[0]) ? String(i) : "";
            $fobj.data("rowindex", rowIndex);
            var checkrow = (gridinsert) ? !this.emptyRow(rowIndex) : true;
            if (checkrow) {
                addcnt++;

            // Validate fields
            if (!this.validateFields(rowIndex))
                return false;

            // Call Form_CustomValidate event
            if (!this.customValidate(fobj)) {
                this.focus();
                return false;
            }
            } // End Grid Add checking
        }
        if (gridinsert && addcnt == 0) { // No row added
            ew.alert(ew.language.phrase("NoAddRecord"));
            return false;
        }
        return true;
    }

    // Check empty row
    fentradas_salidaslist.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "articulo", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "cantidad_articulo", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "precio_unidad_sin_desc", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "descuento", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "costo_unidad", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "costo", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "precio_unidad", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "precio", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "lote", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    fentradas_salidaslist.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fentradas_salidaslist.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fentradas_salidaslist.lists.articulo = <?= $Page->articulo->toClientList($Page) ?>;
    loadjs.done("fentradas_salidaslist");
});
var fentradas_salidaslistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    fentradas_salidaslistsrch = currentSearchForm = new ew.Form("fentradas_salidaslistsrch");

    // Dynamic selection lists

    // Filters
    fentradas_salidaslistsrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    fentradas_salidaslistsrch.initSearchPanel = true;
    loadjs.done("fentradas_salidaslistsrch");
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
if ($Page->DbMasterFilter != "" && $Page->getCurrentMasterTable() == "entradas") {
    if ($Page->MasterRecordExists) {
        include_once "views/EntradasMaster.php";
    }
}
?>
<?php
if ($Page->DbMasterFilter != "" && $Page->getCurrentMasterTable() == "salidas") {
    if ($Page->MasterRecordExists) {
        include_once "views/SalidasMaster.php";
    }
}
?>
<?php } ?>
<?php
$Page->renderOtherOptions();
?>
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !$Page->CurrentAction) { ?>
<form name="fentradas_salidaslistsrch" id="fentradas_salidaslistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fentradas_salidaslistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="entradas_salidas">
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> entradas_salidas">
<form name="fentradas_salidaslist" id="fentradas_salidaslist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="entradas_salidas">
<?php if ($Page->getCurrentMasterTable() == "entradas" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="entradas">
<input type="hidden" name="fk_tipo_documento" value="<?= HtmlEncode($Page->tipo_documento->getSessionValue()) ?>">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->id_documento->getSessionValue()) ?>">
<?php } ?>
<?php if ($Page->getCurrentMasterTable() == "salidas" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="salidas">
<input type="hidden" name="fk_tipo_documento" value="<?= HtmlEncode($Page->tipo_documento->getSessionValue()) ?>">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->id_documento->getSessionValue()) ?>">
<?php } ?>
<div id="gmp_entradas_salidas" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isAdd() || $Page->isCopy() || $Page->isGridEdit()) { ?>
<table id="tbl_entradas_salidaslist" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Page->articulo->Visible) { // articulo ?>
        <th data-name="articulo" class="<?= $Page->articulo->headerCellClass() ?>"><div id="elh_entradas_salidas_articulo" class="entradas_salidas_articulo"><?= $Page->renderSort($Page->articulo) ?></div></th>
<?php } ?>
<?php if ($Page->cantidad_articulo->Visible) { // cantidad_articulo ?>
        <th data-name="cantidad_articulo" class="<?= $Page->cantidad_articulo->headerCellClass() ?>"><div id="elh_entradas_salidas_cantidad_articulo" class="entradas_salidas_cantidad_articulo"><?= $Page->renderSort($Page->cantidad_articulo) ?></div></th>
<?php } ?>
<?php if ($Page->precio_unidad_sin_desc->Visible) { // precio_unidad_sin_desc ?>
        <th data-name="precio_unidad_sin_desc" class="<?= $Page->precio_unidad_sin_desc->headerCellClass() ?>"><div id="elh_entradas_salidas_precio_unidad_sin_desc" class="entradas_salidas_precio_unidad_sin_desc"><?= $Page->renderSort($Page->precio_unidad_sin_desc) ?></div></th>
<?php } ?>
<?php if ($Page->descuento->Visible) { // descuento ?>
        <th data-name="descuento" class="<?= $Page->descuento->headerCellClass() ?>"><div id="elh_entradas_salidas_descuento" class="entradas_salidas_descuento"><?= $Page->renderSort($Page->descuento) ?></div></th>
<?php } ?>
<?php if ($Page->costo_unidad->Visible) { // costo_unidad ?>
        <th data-name="costo_unidad" class="<?= $Page->costo_unidad->headerCellClass() ?>"><div id="elh_entradas_salidas_costo_unidad" class="entradas_salidas_costo_unidad"><?= $Page->renderSort($Page->costo_unidad) ?></div></th>
<?php } ?>
<?php if ($Page->costo->Visible) { // costo ?>
        <th data-name="costo" class="<?= $Page->costo->headerCellClass() ?>"><div id="elh_entradas_salidas_costo" class="entradas_salidas_costo"><?= $Page->renderSort($Page->costo) ?></div></th>
<?php } ?>
<?php if ($Page->precio_unidad->Visible) { // precio_unidad ?>
        <th data-name="precio_unidad" class="<?= $Page->precio_unidad->headerCellClass() ?>"><div id="elh_entradas_salidas_precio_unidad" class="entradas_salidas_precio_unidad"><?= $Page->renderSort($Page->precio_unidad) ?></div></th>
<?php } ?>
<?php if ($Page->precio->Visible) { // precio ?>
        <th data-name="precio" class="<?= $Page->precio->headerCellClass() ?>"><div id="elh_entradas_salidas_precio" class="entradas_salidas_precio"><?= $Page->renderSort($Page->precio) ?></div></th>
<?php } ?>
<?php if ($Page->lote->Visible) { // lote ?>
        <th data-name="lote" class="<?= $Page->lote->headerCellClass() ?>"><div id="elh_entradas_salidas_lote" class="entradas_salidas_lote"><?= $Page->renderSort($Page->lote) ?></div></th>
<?php } ?>
<?php
// Render list options (header, right)
$Page->ListOptions->render("header", "right");
?>
    </tr>
</thead>
<tbody>
<?php
    if ($Page->isAdd() || $Page->isCopy()) {
        $Page->RowIndex = 0;
        $Page->KeyCount = $Page->RowIndex;
        if ($Page->isAdd())
            $Page->loadRowValues();
        if ($Page->EventCancelled) // Insert failed
            $Page->restoreFormValues(); // Restore form values

        // Set row properties
        $Page->resetAttributes();
        $Page->RowAttrs->merge(["data-rowindex" => 0, "id" => "r0_entradas_salidas", "data-rowtype" => ROWTYPE_ADD]);
        $Page->RowType = ROWTYPE_ADD;

        // Render row
        $Page->renderRow();

        // Render list options
        $Page->renderListOptions();
        $Page->StartRowCount = 0;
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Page->ListOptions->render("body", "left", $Page->RowCount);
?>
    <?php if ($Page->articulo->Visible) { // articulo ?>
        <td data-name="articulo">
<span id="el<?= $Page->RowCount ?>_entradas_salidas_articulo" class="form-group entradas_salidas_articulo">
<?php
$onchange = $Page->articulo->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Page->articulo->EditAttrs["onchange"] = "";
?>
<span id="as_x<?= $Page->RowIndex ?>_articulo" class="ew-auto-suggest">
    <div class="input-group flex-nowrap">
        <input type="<?= $Page->articulo->getInputTextType() ?>" class="form-control" name="sv_x<?= $Page->RowIndex ?>_articulo" id="sv_x<?= $Page->RowIndex ?>_articulo" value="<?= RemoveHtml($Page->articulo->EditValue) ?>" size="30" placeholder="<?= HtmlEncode($Page->articulo->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->articulo->getPlaceHolder()) ?>"<?= $Page->articulo->editAttributes() ?>>
        <div class="input-group-append">
            <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->articulo->caption()), $Language->phrase("LookupLink", true))) ?>" onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Page->RowIndex ?>_articulo',m:0,n:10,srch:false});" class="ew-lookup-btn btn btn-default"<?= ($Page->articulo->ReadOnly || $Page->articulo->Disabled) ? " disabled" : "" ?>><i class="fas fa-search ew-icon"></i></button>
        </div>
    </div>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="entradas_salidas" data-field="x_articulo" data-input="sv_x<?= $Page->RowIndex ?>_articulo" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->articulo->displayValueSeparatorAttribute() ?>" name="x<?= $Page->RowIndex ?>_articulo" id="x<?= $Page->RowIndex ?>_articulo" value="<?= HtmlEncode($Page->articulo->CurrentValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Page->articulo->getErrorMessage() ?></div>
<script>
loadjs.ready(["fentradas_salidaslist"], function() {
    fentradas_salidaslist.createAutoSuggest(Object.assign({"id":"x<?= $Page->RowIndex ?>_articulo","forceSelect":true}, ew.vars.tables.entradas_salidas.fields.articulo.autoSuggestOptions));
});
</script>
<?= $Page->articulo->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_articulo") ?>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_articulo" data-hidden="1" name="o<?= $Page->RowIndex ?>_articulo" id="o<?= $Page->RowIndex ?>_articulo" value="<?= HtmlEncode($Page->articulo->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->cantidad_articulo->Visible) { // cantidad_articulo ?>
        <td data-name="cantidad_articulo">
<span id="el<?= $Page->RowCount ?>_entradas_salidas_cantidad_articulo" class="form-group entradas_salidas_cantidad_articulo">
<input type="<?= $Page->cantidad_articulo->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_cantidad_articulo" name="x<?= $Page->RowIndex ?>_cantidad_articulo" id="x<?= $Page->RowIndex ?>_cantidad_articulo" size="6" maxlength="10" placeholder="<?= HtmlEncode($Page->cantidad_articulo->getPlaceHolder()) ?>" value="<?= $Page->cantidad_articulo->EditValue ?>"<?= $Page->cantidad_articulo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->cantidad_articulo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_cantidad_articulo" data-hidden="1" name="o<?= $Page->RowIndex ?>_cantidad_articulo" id="o<?= $Page->RowIndex ?>_cantidad_articulo" value="<?= HtmlEncode($Page->cantidad_articulo->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->precio_unidad_sin_desc->Visible) { // precio_unidad_sin_desc ?>
        <td data-name="precio_unidad_sin_desc">
<span id="el<?= $Page->RowCount ?>_entradas_salidas_precio_unidad_sin_desc" class="form-group entradas_salidas_precio_unidad_sin_desc">
<input type="<?= $Page->precio_unidad_sin_desc->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_precio_unidad_sin_desc" name="x<?= $Page->RowIndex ?>_precio_unidad_sin_desc" id="x<?= $Page->RowIndex ?>_precio_unidad_sin_desc" size="10" maxlength="14" placeholder="<?= HtmlEncode($Page->precio_unidad_sin_desc->getPlaceHolder()) ?>" value="<?= $Page->precio_unidad_sin_desc->EditValue ?>"<?= $Page->precio_unidad_sin_desc->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->precio_unidad_sin_desc->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_precio_unidad_sin_desc" data-hidden="1" name="o<?= $Page->RowIndex ?>_precio_unidad_sin_desc" id="o<?= $Page->RowIndex ?>_precio_unidad_sin_desc" value="<?= HtmlEncode($Page->precio_unidad_sin_desc->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->descuento->Visible) { // descuento ?>
        <td data-name="descuento">
<span id="el<?= $Page->RowCount ?>_entradas_salidas_descuento" class="form-group entradas_salidas_descuento">
<input type="<?= $Page->descuento->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_descuento" name="x<?= $Page->RowIndex ?>_descuento" id="x<?= $Page->RowIndex ?>_descuento" size="6" maxlength="6" placeholder="<?= HtmlEncode($Page->descuento->getPlaceHolder()) ?>" value="<?= $Page->descuento->EditValue ?>"<?= $Page->descuento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->descuento->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_descuento" data-hidden="1" name="o<?= $Page->RowIndex ?>_descuento" id="o<?= $Page->RowIndex ?>_descuento" value="<?= HtmlEncode($Page->descuento->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->costo_unidad->Visible) { // costo_unidad ?>
        <td data-name="costo_unidad">
<span id="el<?= $Page->RowCount ?>_entradas_salidas_costo_unidad" class="form-group entradas_salidas_costo_unidad">
<input type="<?= $Page->costo_unidad->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_costo_unidad" name="x<?= $Page->RowIndex ?>_costo_unidad" id="x<?= $Page->RowIndex ?>_costo_unidad" size="10" maxlength="14" placeholder="<?= HtmlEncode($Page->costo_unidad->getPlaceHolder()) ?>" value="<?= $Page->costo_unidad->EditValue ?>"<?= $Page->costo_unidad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->costo_unidad->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_costo_unidad" data-hidden="1" name="o<?= $Page->RowIndex ?>_costo_unidad" id="o<?= $Page->RowIndex ?>_costo_unidad" value="<?= HtmlEncode($Page->costo_unidad->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->costo->Visible) { // costo ?>
        <td data-name="costo">
<span id="el<?= $Page->RowCount ?>_entradas_salidas_costo" class="form-group entradas_salidas_costo">
<input type="<?= $Page->costo->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_costo" name="x<?= $Page->RowIndex ?>_costo" id="x<?= $Page->RowIndex ?>_costo" size="10" maxlength="14" placeholder="<?= HtmlEncode($Page->costo->getPlaceHolder()) ?>" value="<?= $Page->costo->EditValue ?>"<?= $Page->costo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->costo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_costo" data-hidden="1" name="o<?= $Page->RowIndex ?>_costo" id="o<?= $Page->RowIndex ?>_costo" value="<?= HtmlEncode($Page->costo->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->precio_unidad->Visible) { // precio_unidad ?>
        <td data-name="precio_unidad">
<span id="el<?= $Page->RowCount ?>_entradas_salidas_precio_unidad" class="form-group entradas_salidas_precio_unidad">
<input type="<?= $Page->precio_unidad->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_precio_unidad" name="x<?= $Page->RowIndex ?>_precio_unidad" id="x<?= $Page->RowIndex ?>_precio_unidad" size="6" maxlength="14" placeholder="<?= HtmlEncode($Page->precio_unidad->getPlaceHolder()) ?>" value="<?= $Page->precio_unidad->EditValue ?>"<?= $Page->precio_unidad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->precio_unidad->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_precio_unidad" data-hidden="1" name="o<?= $Page->RowIndex ?>_precio_unidad" id="o<?= $Page->RowIndex ?>_precio_unidad" value="<?= HtmlEncode($Page->precio_unidad->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->precio->Visible) { // precio ?>
        <td data-name="precio">
<span id="el<?= $Page->RowCount ?>_entradas_salidas_precio" class="form-group entradas_salidas_precio">
<input type="<?= $Page->precio->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_precio" name="x<?= $Page->RowIndex ?>_precio" id="x<?= $Page->RowIndex ?>_precio" size="10" maxlength="14" placeholder="<?= HtmlEncode($Page->precio->getPlaceHolder()) ?>" value="<?= $Page->precio->EditValue ?>"<?= $Page->precio->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->precio->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_precio" data-hidden="1" name="o<?= $Page->RowIndex ?>_precio" id="o<?= $Page->RowIndex ?>_precio" value="<?= HtmlEncode($Page->precio->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->lote->Visible) { // lote ?>
        <td data-name="lote">
<span id="el<?= $Page->RowCount ?>_entradas_salidas_lote" class="form-group entradas_salidas_lote">
<input type="<?= $Page->lote->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_lote" name="x<?= $Page->RowIndex ?>_lote" id="x<?= $Page->RowIndex ?>_lote" size="6" maxlength="20" placeholder="<?= HtmlEncode($Page->lote->getPlaceHolder()) ?>" value="<?= $Page->lote->EditValue ?>"<?= $Page->lote->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->lote->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_lote" data-hidden="1" name="o<?= $Page->RowIndex ?>_lote" id="o<?= $Page->RowIndex ?>_lote" value="<?= HtmlEncode($Page->lote->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowCount);
?>
<script>
loadjs.ready(["fentradas_salidaslist","load"], function() {
    fentradas_salidaslist.updateLists(<?= $Page->RowIndex ?>);
});
</script>
    </tr>
<?php
    }
?>
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

// Restore number of post back records
if ($CurrentForm && ($Page->isConfirm() || $Page->EventCancelled)) {
    $CurrentForm->Index = -1;
    if ($CurrentForm->hasValue($Page->FormKeyCountName) && ($Page->isGridAdd() || $Page->isGridEdit() || $Page->isConfirm())) {
        $Page->KeyCount = $CurrentForm->getValue($Page->FormKeyCountName);
        $Page->StopRecord = $Page->StartRecord + $Page->KeyCount - 1;
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
if ($Page->isGridAdd())
    $Page->RowIndex = 0;
if ($Page->isGridEdit())
    $Page->RowIndex = 0;
while ($Page->RecordCount < $Page->StopRecord) {
    $Page->RecordCount++;
    if ($Page->RecordCount >= $Page->StartRecord) {
        $Page->RowCount++;
        if ($Page->isGridAdd() || $Page->isGridEdit() || $Page->isConfirm()) {
            $Page->RowIndex++;
            $CurrentForm->Index = $Page->RowIndex;
            if ($CurrentForm->hasValue($Page->FormActionName) && ($Page->isConfirm() || $Page->EventCancelled)) {
                $Page->RowAction = strval($CurrentForm->getValue($Page->FormActionName));
            } elseif ($Page->isGridAdd()) {
                $Page->RowAction = "insert";
            } else {
                $Page->RowAction = "";
            }
        }

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
        if ($Page->isGridAdd()) { // Grid add
            $Page->RowType = ROWTYPE_ADD; // Render add
        }
        if ($Page->isGridAdd() && $Page->EventCancelled && !$CurrentForm->hasValue("k_blankrow")) { // Insert failed
            $Page->restoreCurrentRowFormValues($Page->RowIndex); // Restore form values
        }
        if ($Page->isGridEdit()) { // Grid edit
            if ($Page->EventCancelled) {
                $Page->restoreCurrentRowFormValues($Page->RowIndex); // Restore form values
            }
            if ($Page->RowAction == "insert") {
                $Page->RowType = ROWTYPE_ADD; // Render add
            } else {
                $Page->RowType = ROWTYPE_EDIT; // Render edit
            }
        }
        if ($Page->isGridEdit() && ($Page->RowType == ROWTYPE_EDIT || $Page->RowType == ROWTYPE_ADD) && $Page->EventCancelled) { // Update failed
            $Page->restoreCurrentRowFormValues($Page->RowIndex); // Restore form values
        }
        if ($Page->RowType == ROWTYPE_EDIT) { // Edit row
            $Page->EditRowCount++;
        }

        // Set up row id / data-rowindex
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_entradas_salidas", "data-rowtype" => $Page->RowType]);

        // Render row
        $Page->renderRow();

        // Render list options
        $Page->renderListOptions();

        // Skip delete row / empty row for confirm page
        if ($Page->RowAction != "delete" && $Page->RowAction != "insertdelete" && !($Page->RowAction == "insert" && $Page->isConfirm() && $Page->emptyRow())) {
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Page->ListOptions->render("body", "left", $Page->RowCount);
?>
    <?php if ($Page->articulo->Visible) { // articulo ?>
        <td data-name="articulo" <?= $Page->articulo->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_entradas_salidas_articulo" class="form-group">
<?php
$onchange = $Page->articulo->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Page->articulo->EditAttrs["onchange"] = "";
?>
<span id="as_x<?= $Page->RowIndex ?>_articulo" class="ew-auto-suggest">
    <div class="input-group flex-nowrap">
        <input type="<?= $Page->articulo->getInputTextType() ?>" class="form-control" name="sv_x<?= $Page->RowIndex ?>_articulo" id="sv_x<?= $Page->RowIndex ?>_articulo" value="<?= RemoveHtml($Page->articulo->EditValue) ?>" size="30" placeholder="<?= HtmlEncode($Page->articulo->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->articulo->getPlaceHolder()) ?>"<?= $Page->articulo->editAttributes() ?>>
        <div class="input-group-append">
            <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->articulo->caption()), $Language->phrase("LookupLink", true))) ?>" onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Page->RowIndex ?>_articulo',m:0,n:10,srch:false});" class="ew-lookup-btn btn btn-default"<?= ($Page->articulo->ReadOnly || $Page->articulo->Disabled) ? " disabled" : "" ?>><i class="fas fa-search ew-icon"></i></button>
        </div>
    </div>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="entradas_salidas" data-field="x_articulo" data-input="sv_x<?= $Page->RowIndex ?>_articulo" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->articulo->displayValueSeparatorAttribute() ?>" name="x<?= $Page->RowIndex ?>_articulo" id="x<?= $Page->RowIndex ?>_articulo" value="<?= HtmlEncode($Page->articulo->CurrentValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Page->articulo->getErrorMessage() ?></div>
<script>
loadjs.ready(["fentradas_salidaslist"], function() {
    fentradas_salidaslist.createAutoSuggest(Object.assign({"id":"x<?= $Page->RowIndex ?>_articulo","forceSelect":true}, ew.vars.tables.entradas_salidas.fields.articulo.autoSuggestOptions));
});
</script>
<?= $Page->articulo->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_articulo") ?>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_articulo" data-hidden="1" name="o<?= $Page->RowIndex ?>_articulo" id="o<?= $Page->RowIndex ?>_articulo" value="<?= HtmlEncode($Page->articulo->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_entradas_salidas_articulo" class="form-group">
<?php
$onchange = $Page->articulo->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Page->articulo->EditAttrs["onchange"] = "";
?>
<span id="as_x<?= $Page->RowIndex ?>_articulo" class="ew-auto-suggest">
    <div class="input-group flex-nowrap">
        <input type="<?= $Page->articulo->getInputTextType() ?>" class="form-control" name="sv_x<?= $Page->RowIndex ?>_articulo" id="sv_x<?= $Page->RowIndex ?>_articulo" value="<?= RemoveHtml($Page->articulo->EditValue) ?>" size="30" placeholder="<?= HtmlEncode($Page->articulo->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->articulo->getPlaceHolder()) ?>"<?= $Page->articulo->editAttributes() ?>>
        <div class="input-group-append">
            <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->articulo->caption()), $Language->phrase("LookupLink", true))) ?>" onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Page->RowIndex ?>_articulo',m:0,n:10,srch:false});" class="ew-lookup-btn btn btn-default"<?= ($Page->articulo->ReadOnly || $Page->articulo->Disabled) ? " disabled" : "" ?>><i class="fas fa-search ew-icon"></i></button>
        </div>
    </div>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="entradas_salidas" data-field="x_articulo" data-input="sv_x<?= $Page->RowIndex ?>_articulo" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->articulo->displayValueSeparatorAttribute() ?>" name="x<?= $Page->RowIndex ?>_articulo" id="x<?= $Page->RowIndex ?>_articulo" value="<?= HtmlEncode($Page->articulo->CurrentValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Page->articulo->getErrorMessage() ?></div>
<script>
loadjs.ready(["fentradas_salidaslist"], function() {
    fentradas_salidaslist.createAutoSuggest(Object.assign({"id":"x<?= $Page->RowIndex ?>_articulo","forceSelect":true}, ew.vars.tables.entradas_salidas.fields.articulo.autoSuggestOptions));
});
</script>
<?= $Page->articulo->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_articulo") ?>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_entradas_salidas_articulo">
<span<?= $Page->articulo->viewAttributes() ?>>
<?= $Page->articulo->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->cantidad_articulo->Visible) { // cantidad_articulo ?>
        <td data-name="cantidad_articulo" <?= $Page->cantidad_articulo->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_entradas_salidas_cantidad_articulo" class="form-group">
<input type="<?= $Page->cantidad_articulo->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_cantidad_articulo" name="x<?= $Page->RowIndex ?>_cantidad_articulo" id="x<?= $Page->RowIndex ?>_cantidad_articulo" size="6" maxlength="10" placeholder="<?= HtmlEncode($Page->cantidad_articulo->getPlaceHolder()) ?>" value="<?= $Page->cantidad_articulo->EditValue ?>"<?= $Page->cantidad_articulo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->cantidad_articulo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_cantidad_articulo" data-hidden="1" name="o<?= $Page->RowIndex ?>_cantidad_articulo" id="o<?= $Page->RowIndex ?>_cantidad_articulo" value="<?= HtmlEncode($Page->cantidad_articulo->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_entradas_salidas_cantidad_articulo" class="form-group">
<input type="<?= $Page->cantidad_articulo->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_cantidad_articulo" name="x<?= $Page->RowIndex ?>_cantidad_articulo" id="x<?= $Page->RowIndex ?>_cantidad_articulo" size="6" maxlength="10" placeholder="<?= HtmlEncode($Page->cantidad_articulo->getPlaceHolder()) ?>" value="<?= $Page->cantidad_articulo->EditValue ?>"<?= $Page->cantidad_articulo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->cantidad_articulo->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_entradas_salidas_cantidad_articulo">
<span<?= $Page->cantidad_articulo->viewAttributes() ?>>
<?= $Page->cantidad_articulo->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->precio_unidad_sin_desc->Visible) { // precio_unidad_sin_desc ?>
        <td data-name="precio_unidad_sin_desc" <?= $Page->precio_unidad_sin_desc->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_entradas_salidas_precio_unidad_sin_desc" class="form-group">
<input type="<?= $Page->precio_unidad_sin_desc->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_precio_unidad_sin_desc" name="x<?= $Page->RowIndex ?>_precio_unidad_sin_desc" id="x<?= $Page->RowIndex ?>_precio_unidad_sin_desc" size="10" maxlength="14" placeholder="<?= HtmlEncode($Page->precio_unidad_sin_desc->getPlaceHolder()) ?>" value="<?= $Page->precio_unidad_sin_desc->EditValue ?>"<?= $Page->precio_unidad_sin_desc->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->precio_unidad_sin_desc->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_precio_unidad_sin_desc" data-hidden="1" name="o<?= $Page->RowIndex ?>_precio_unidad_sin_desc" id="o<?= $Page->RowIndex ?>_precio_unidad_sin_desc" value="<?= HtmlEncode($Page->precio_unidad_sin_desc->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_entradas_salidas_precio_unidad_sin_desc" class="form-group">
<input type="<?= $Page->precio_unidad_sin_desc->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_precio_unidad_sin_desc" name="x<?= $Page->RowIndex ?>_precio_unidad_sin_desc" id="x<?= $Page->RowIndex ?>_precio_unidad_sin_desc" size="10" maxlength="14" placeholder="<?= HtmlEncode($Page->precio_unidad_sin_desc->getPlaceHolder()) ?>" value="<?= $Page->precio_unidad_sin_desc->EditValue ?>"<?= $Page->precio_unidad_sin_desc->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->precio_unidad_sin_desc->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_entradas_salidas_precio_unidad_sin_desc">
<span<?= $Page->precio_unidad_sin_desc->viewAttributes() ?>>
<?= $Page->precio_unidad_sin_desc->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->descuento->Visible) { // descuento ?>
        <td data-name="descuento" <?= $Page->descuento->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_entradas_salidas_descuento" class="form-group">
<input type="<?= $Page->descuento->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_descuento" name="x<?= $Page->RowIndex ?>_descuento" id="x<?= $Page->RowIndex ?>_descuento" size="6" maxlength="6" placeholder="<?= HtmlEncode($Page->descuento->getPlaceHolder()) ?>" value="<?= $Page->descuento->EditValue ?>"<?= $Page->descuento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->descuento->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_descuento" data-hidden="1" name="o<?= $Page->RowIndex ?>_descuento" id="o<?= $Page->RowIndex ?>_descuento" value="<?= HtmlEncode($Page->descuento->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_entradas_salidas_descuento" class="form-group">
<input type="<?= $Page->descuento->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_descuento" name="x<?= $Page->RowIndex ?>_descuento" id="x<?= $Page->RowIndex ?>_descuento" size="6" maxlength="6" placeholder="<?= HtmlEncode($Page->descuento->getPlaceHolder()) ?>" value="<?= $Page->descuento->EditValue ?>"<?= $Page->descuento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->descuento->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_entradas_salidas_descuento">
<span<?= $Page->descuento->viewAttributes() ?>>
<?= $Page->descuento->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->costo_unidad->Visible) { // costo_unidad ?>
        <td data-name="costo_unidad" <?= $Page->costo_unidad->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_entradas_salidas_costo_unidad" class="form-group">
<input type="<?= $Page->costo_unidad->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_costo_unidad" name="x<?= $Page->RowIndex ?>_costo_unidad" id="x<?= $Page->RowIndex ?>_costo_unidad" size="10" maxlength="14" placeholder="<?= HtmlEncode($Page->costo_unidad->getPlaceHolder()) ?>" value="<?= $Page->costo_unidad->EditValue ?>"<?= $Page->costo_unidad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->costo_unidad->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_costo_unidad" data-hidden="1" name="o<?= $Page->RowIndex ?>_costo_unidad" id="o<?= $Page->RowIndex ?>_costo_unidad" value="<?= HtmlEncode($Page->costo_unidad->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_entradas_salidas_costo_unidad" class="form-group">
<input type="<?= $Page->costo_unidad->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_costo_unidad" name="x<?= $Page->RowIndex ?>_costo_unidad" id="x<?= $Page->RowIndex ?>_costo_unidad" size="10" maxlength="14" placeholder="<?= HtmlEncode($Page->costo_unidad->getPlaceHolder()) ?>" value="<?= $Page->costo_unidad->EditValue ?>"<?= $Page->costo_unidad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->costo_unidad->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_entradas_salidas_costo_unidad">
<span<?= $Page->costo_unidad->viewAttributes() ?>>
<?= $Page->costo_unidad->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->costo->Visible) { // costo ?>
        <td data-name="costo" <?= $Page->costo->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_entradas_salidas_costo" class="form-group">
<input type="<?= $Page->costo->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_costo" name="x<?= $Page->RowIndex ?>_costo" id="x<?= $Page->RowIndex ?>_costo" size="10" maxlength="14" placeholder="<?= HtmlEncode($Page->costo->getPlaceHolder()) ?>" value="<?= $Page->costo->EditValue ?>"<?= $Page->costo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->costo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_costo" data-hidden="1" name="o<?= $Page->RowIndex ?>_costo" id="o<?= $Page->RowIndex ?>_costo" value="<?= HtmlEncode($Page->costo->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_entradas_salidas_costo" class="form-group">
<input type="<?= $Page->costo->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_costo" name="x<?= $Page->RowIndex ?>_costo" id="x<?= $Page->RowIndex ?>_costo" size="10" maxlength="14" placeholder="<?= HtmlEncode($Page->costo->getPlaceHolder()) ?>" value="<?= $Page->costo->EditValue ?>"<?= $Page->costo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->costo->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_entradas_salidas_costo">
<span<?= $Page->costo->viewAttributes() ?>>
<?= $Page->costo->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->precio_unidad->Visible) { // precio_unidad ?>
        <td data-name="precio_unidad" <?= $Page->precio_unidad->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_entradas_salidas_precio_unidad" class="form-group">
<input type="<?= $Page->precio_unidad->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_precio_unidad" name="x<?= $Page->RowIndex ?>_precio_unidad" id="x<?= $Page->RowIndex ?>_precio_unidad" size="6" maxlength="14" placeholder="<?= HtmlEncode($Page->precio_unidad->getPlaceHolder()) ?>" value="<?= $Page->precio_unidad->EditValue ?>"<?= $Page->precio_unidad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->precio_unidad->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_precio_unidad" data-hidden="1" name="o<?= $Page->RowIndex ?>_precio_unidad" id="o<?= $Page->RowIndex ?>_precio_unidad" value="<?= HtmlEncode($Page->precio_unidad->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_entradas_salidas_precio_unidad" class="form-group">
<input type="<?= $Page->precio_unidad->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_precio_unidad" name="x<?= $Page->RowIndex ?>_precio_unidad" id="x<?= $Page->RowIndex ?>_precio_unidad" size="6" maxlength="14" placeholder="<?= HtmlEncode($Page->precio_unidad->getPlaceHolder()) ?>" value="<?= $Page->precio_unidad->EditValue ?>"<?= $Page->precio_unidad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->precio_unidad->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_entradas_salidas_precio_unidad">
<span<?= $Page->precio_unidad->viewAttributes() ?>>
<?= $Page->precio_unidad->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->precio->Visible) { // precio ?>
        <td data-name="precio" <?= $Page->precio->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_entradas_salidas_precio" class="form-group">
<input type="<?= $Page->precio->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_precio" name="x<?= $Page->RowIndex ?>_precio" id="x<?= $Page->RowIndex ?>_precio" size="10" maxlength="14" placeholder="<?= HtmlEncode($Page->precio->getPlaceHolder()) ?>" value="<?= $Page->precio->EditValue ?>"<?= $Page->precio->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->precio->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_precio" data-hidden="1" name="o<?= $Page->RowIndex ?>_precio" id="o<?= $Page->RowIndex ?>_precio" value="<?= HtmlEncode($Page->precio->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_entradas_salidas_precio" class="form-group">
<input type="<?= $Page->precio->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_precio" name="x<?= $Page->RowIndex ?>_precio" id="x<?= $Page->RowIndex ?>_precio" size="10" maxlength="14" placeholder="<?= HtmlEncode($Page->precio->getPlaceHolder()) ?>" value="<?= $Page->precio->EditValue ?>"<?= $Page->precio->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->precio->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_entradas_salidas_precio">
<span<?= $Page->precio->viewAttributes() ?>>
<?= $Page->precio->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->lote->Visible) { // lote ?>
        <td data-name="lote" <?= $Page->lote->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_entradas_salidas_lote" class="form-group">
<input type="<?= $Page->lote->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_lote" name="x<?= $Page->RowIndex ?>_lote" id="x<?= $Page->RowIndex ?>_lote" size="6" maxlength="20" placeholder="<?= HtmlEncode($Page->lote->getPlaceHolder()) ?>" value="<?= $Page->lote->EditValue ?>"<?= $Page->lote->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->lote->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_lote" data-hidden="1" name="o<?= $Page->RowIndex ?>_lote" id="o<?= $Page->RowIndex ?>_lote" value="<?= HtmlEncode($Page->lote->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_entradas_salidas_lote" class="form-group">
<input type="<?= $Page->lote->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_lote" name="x<?= $Page->RowIndex ?>_lote" id="x<?= $Page->RowIndex ?>_lote" size="6" maxlength="20" placeholder="<?= HtmlEncode($Page->lote->getPlaceHolder()) ?>" value="<?= $Page->lote->EditValue ?>"<?= $Page->lote->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->lote->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_entradas_salidas_lote">
<span<?= $Page->lote->viewAttributes() ?>>
<?= $Page->lote->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowCount);
?>
    </tr>
<?php if ($Page->RowType == ROWTYPE_ADD || $Page->RowType == ROWTYPE_EDIT) { ?>
<script>
loadjs.ready(["fentradas_salidaslist","load"], function () {
    fentradas_salidaslist.updateLists(<?= $Page->RowIndex ?>);
});
</script>
<?php } ?>
<?php
    }
    } // End delete row checking
    if (!$Page->isGridAdd())
        if (!$Page->Recordset->EOF) {
            $Page->Recordset->moveNext();
        }
}
?>
<?php
    if ($Page->isGridAdd() || $Page->isGridEdit()) {
        $Page->RowIndex = '$rowindex$';
        $Page->loadRowValues();

        // Set row properties
        $Page->resetAttributes();
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowIndex, "id" => "r0_entradas_salidas", "data-rowtype" => ROWTYPE_ADD]);
        $Page->RowAttrs->appendClass("ew-template");
        $Page->RowType = ROWTYPE_ADD;

        // Render row
        $Page->renderRow();

        // Render list options
        $Page->renderListOptions();
        $Page->StartRowCount = 0;
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Page->ListOptions->render("body", "left", $Page->RowIndex);
?>
    <?php if ($Page->articulo->Visible) { // articulo ?>
        <td data-name="articulo">
<span id="el$rowindex$_entradas_salidas_articulo" class="form-group entradas_salidas_articulo">
<?php
$onchange = $Page->articulo->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Page->articulo->EditAttrs["onchange"] = "";
?>
<span id="as_x<?= $Page->RowIndex ?>_articulo" class="ew-auto-suggest">
    <div class="input-group flex-nowrap">
        <input type="<?= $Page->articulo->getInputTextType() ?>" class="form-control" name="sv_x<?= $Page->RowIndex ?>_articulo" id="sv_x<?= $Page->RowIndex ?>_articulo" value="<?= RemoveHtml($Page->articulo->EditValue) ?>" size="30" placeholder="<?= HtmlEncode($Page->articulo->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->articulo->getPlaceHolder()) ?>"<?= $Page->articulo->editAttributes() ?>>
        <div class="input-group-append">
            <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->articulo->caption()), $Language->phrase("LookupLink", true))) ?>" onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Page->RowIndex ?>_articulo',m:0,n:10,srch:false});" class="ew-lookup-btn btn btn-default"<?= ($Page->articulo->ReadOnly || $Page->articulo->Disabled) ? " disabled" : "" ?>><i class="fas fa-search ew-icon"></i></button>
        </div>
    </div>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="entradas_salidas" data-field="x_articulo" data-input="sv_x<?= $Page->RowIndex ?>_articulo" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->articulo->displayValueSeparatorAttribute() ?>" name="x<?= $Page->RowIndex ?>_articulo" id="x<?= $Page->RowIndex ?>_articulo" value="<?= HtmlEncode($Page->articulo->CurrentValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Page->articulo->getErrorMessage() ?></div>
<script>
loadjs.ready(["fentradas_salidaslist"], function() {
    fentradas_salidaslist.createAutoSuggest(Object.assign({"id":"x<?= $Page->RowIndex ?>_articulo","forceSelect":true}, ew.vars.tables.entradas_salidas.fields.articulo.autoSuggestOptions));
});
</script>
<?= $Page->articulo->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_articulo") ?>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_articulo" data-hidden="1" name="o<?= $Page->RowIndex ?>_articulo" id="o<?= $Page->RowIndex ?>_articulo" value="<?= HtmlEncode($Page->articulo->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->cantidad_articulo->Visible) { // cantidad_articulo ?>
        <td data-name="cantidad_articulo">
<span id="el$rowindex$_entradas_salidas_cantidad_articulo" class="form-group entradas_salidas_cantidad_articulo">
<input type="<?= $Page->cantidad_articulo->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_cantidad_articulo" name="x<?= $Page->RowIndex ?>_cantidad_articulo" id="x<?= $Page->RowIndex ?>_cantidad_articulo" size="6" maxlength="10" placeholder="<?= HtmlEncode($Page->cantidad_articulo->getPlaceHolder()) ?>" value="<?= $Page->cantidad_articulo->EditValue ?>"<?= $Page->cantidad_articulo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->cantidad_articulo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_cantidad_articulo" data-hidden="1" name="o<?= $Page->RowIndex ?>_cantidad_articulo" id="o<?= $Page->RowIndex ?>_cantidad_articulo" value="<?= HtmlEncode($Page->cantidad_articulo->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->precio_unidad_sin_desc->Visible) { // precio_unidad_sin_desc ?>
        <td data-name="precio_unidad_sin_desc">
<span id="el$rowindex$_entradas_salidas_precio_unidad_sin_desc" class="form-group entradas_salidas_precio_unidad_sin_desc">
<input type="<?= $Page->precio_unidad_sin_desc->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_precio_unidad_sin_desc" name="x<?= $Page->RowIndex ?>_precio_unidad_sin_desc" id="x<?= $Page->RowIndex ?>_precio_unidad_sin_desc" size="10" maxlength="14" placeholder="<?= HtmlEncode($Page->precio_unidad_sin_desc->getPlaceHolder()) ?>" value="<?= $Page->precio_unidad_sin_desc->EditValue ?>"<?= $Page->precio_unidad_sin_desc->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->precio_unidad_sin_desc->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_precio_unidad_sin_desc" data-hidden="1" name="o<?= $Page->RowIndex ?>_precio_unidad_sin_desc" id="o<?= $Page->RowIndex ?>_precio_unidad_sin_desc" value="<?= HtmlEncode($Page->precio_unidad_sin_desc->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->descuento->Visible) { // descuento ?>
        <td data-name="descuento">
<span id="el$rowindex$_entradas_salidas_descuento" class="form-group entradas_salidas_descuento">
<input type="<?= $Page->descuento->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_descuento" name="x<?= $Page->RowIndex ?>_descuento" id="x<?= $Page->RowIndex ?>_descuento" size="6" maxlength="6" placeholder="<?= HtmlEncode($Page->descuento->getPlaceHolder()) ?>" value="<?= $Page->descuento->EditValue ?>"<?= $Page->descuento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->descuento->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_descuento" data-hidden="1" name="o<?= $Page->RowIndex ?>_descuento" id="o<?= $Page->RowIndex ?>_descuento" value="<?= HtmlEncode($Page->descuento->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->costo_unidad->Visible) { // costo_unidad ?>
        <td data-name="costo_unidad">
<span id="el$rowindex$_entradas_salidas_costo_unidad" class="form-group entradas_salidas_costo_unidad">
<input type="<?= $Page->costo_unidad->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_costo_unidad" name="x<?= $Page->RowIndex ?>_costo_unidad" id="x<?= $Page->RowIndex ?>_costo_unidad" size="10" maxlength="14" placeholder="<?= HtmlEncode($Page->costo_unidad->getPlaceHolder()) ?>" value="<?= $Page->costo_unidad->EditValue ?>"<?= $Page->costo_unidad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->costo_unidad->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_costo_unidad" data-hidden="1" name="o<?= $Page->RowIndex ?>_costo_unidad" id="o<?= $Page->RowIndex ?>_costo_unidad" value="<?= HtmlEncode($Page->costo_unidad->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->costo->Visible) { // costo ?>
        <td data-name="costo">
<span id="el$rowindex$_entradas_salidas_costo" class="form-group entradas_salidas_costo">
<input type="<?= $Page->costo->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_costo" name="x<?= $Page->RowIndex ?>_costo" id="x<?= $Page->RowIndex ?>_costo" size="10" maxlength="14" placeholder="<?= HtmlEncode($Page->costo->getPlaceHolder()) ?>" value="<?= $Page->costo->EditValue ?>"<?= $Page->costo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->costo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_costo" data-hidden="1" name="o<?= $Page->RowIndex ?>_costo" id="o<?= $Page->RowIndex ?>_costo" value="<?= HtmlEncode($Page->costo->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->precio_unidad->Visible) { // precio_unidad ?>
        <td data-name="precio_unidad">
<span id="el$rowindex$_entradas_salidas_precio_unidad" class="form-group entradas_salidas_precio_unidad">
<input type="<?= $Page->precio_unidad->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_precio_unidad" name="x<?= $Page->RowIndex ?>_precio_unidad" id="x<?= $Page->RowIndex ?>_precio_unidad" size="6" maxlength="14" placeholder="<?= HtmlEncode($Page->precio_unidad->getPlaceHolder()) ?>" value="<?= $Page->precio_unidad->EditValue ?>"<?= $Page->precio_unidad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->precio_unidad->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_precio_unidad" data-hidden="1" name="o<?= $Page->RowIndex ?>_precio_unidad" id="o<?= $Page->RowIndex ?>_precio_unidad" value="<?= HtmlEncode($Page->precio_unidad->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->precio->Visible) { // precio ?>
        <td data-name="precio">
<span id="el$rowindex$_entradas_salidas_precio" class="form-group entradas_salidas_precio">
<input type="<?= $Page->precio->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_precio" name="x<?= $Page->RowIndex ?>_precio" id="x<?= $Page->RowIndex ?>_precio" size="10" maxlength="14" placeholder="<?= HtmlEncode($Page->precio->getPlaceHolder()) ?>" value="<?= $Page->precio->EditValue ?>"<?= $Page->precio->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->precio->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_precio" data-hidden="1" name="o<?= $Page->RowIndex ?>_precio" id="o<?= $Page->RowIndex ?>_precio" value="<?= HtmlEncode($Page->precio->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->lote->Visible) { // lote ?>
        <td data-name="lote">
<span id="el$rowindex$_entradas_salidas_lote" class="form-group entradas_salidas_lote">
<input type="<?= $Page->lote->getInputTextType() ?>" data-table="entradas_salidas" data-field="x_lote" name="x<?= $Page->RowIndex ?>_lote" id="x<?= $Page->RowIndex ?>_lote" size="6" maxlength="20" placeholder="<?= HtmlEncode($Page->lote->getPlaceHolder()) ?>" value="<?= $Page->lote->EditValue ?>"<?= $Page->lote->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->lote->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_lote" data-hidden="1" name="o<?= $Page->RowIndex ?>_lote" id="o<?= $Page->RowIndex ?>_lote" value="<?= HtmlEncode($Page->lote->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowIndex);
?>
<script>
loadjs.ready(["fentradas_salidaslist","load"], function() {
    fentradas_salidaslist.updateLists(<?= $Page->RowIndex ?>);
});
</script>
    </tr>
<?php
    }
?>
</tbody>
</table><!-- /.ew-table -->
<?php } ?>
</div><!-- /.ew-grid-middle-panel -->
<?php if ($Page->isAdd() || $Page->isCopy()) { ?>
<input type="hidden" name="<?= $Page->FormKeyCountName ?>" id="<?= $Page->FormKeyCountName ?>" value="<?= $Page->KeyCount ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php } ?>
<?php if ($Page->isGridAdd()) { ?>
<input type="hidden" name="action" id="action" value="gridinsert">
<input type="hidden" name="<?= $Page->FormKeyCountName ?>" id="<?= $Page->FormKeyCountName ?>" value="<?= $Page->KeyCount ?>">
<?= $Page->MultiSelectKey ?>
<?php } ?>
<?php if ($Page->isGridEdit()) { ?>
<input type="hidden" name="action" id="action" value="gridupdate">
<input type="hidden" name="<?= $Page->FormKeyCountName ?>" id="<?= $Page->FormKeyCountName ?>" value="<?= $Page->KeyCount ?>">
<?= $Page->MultiSelectKey ?>
<?php } ?>
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
    ew.addEventHandlers("entradas_salidas");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
