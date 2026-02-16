<?php

namespace PHPMaker2021\mandrake;

// Page object
$ViewArticulosList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fview_articuloslist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    fview_articuloslist = currentForm = new ew.Form("fview_articuloslist", "list");
    fview_articuloslist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "view_articulos")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.view_articulos)
        ew.vars.tables.view_articulos = currentTable;
    fview_articuloslist.addFields([
        ["referencia", [fields.referencia.visible && fields.referencia.required ? ew.Validators.required(fields.referencia.caption) : null], fields.referencia.isInvalid],
        ["codigo", [fields.codigo.visible && fields.codigo.required ? ew.Validators.required(fields.codigo.caption) : null], fields.codigo.isInvalid],
        ["fabricante", [fields.fabricante.visible && fields.fabricante.required ? ew.Validators.required(fields.fabricante.caption) : null], fields.fabricante.isInvalid],
        ["nombre", [fields.nombre.visible && fields.nombre.required ? ew.Validators.required(fields.nombre.caption) : null], fields.nombre.isInvalid],
        ["cantidad_en_mano", [fields.cantidad_en_mano.visible && fields.cantidad_en_mano.required ? ew.Validators.required(fields.cantidad_en_mano.caption) : null], fields.cantidad_en_mano.isInvalid],
        ["ultimo_costo", [fields.ultimo_costo.visible && fields.ultimo_costo.required ? ew.Validators.required(fields.ultimo_costo.caption) : null, ew.Validators.float], fields.ultimo_costo.isInvalid],
        ["precio", [fields.precio.visible && fields.precio.required ? ew.Validators.required(fields.precio.caption) : null, ew.Validators.float], fields.precio.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fview_articuloslist,
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
    fview_articuloslist.validate = function () {
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

            // Validate fields
            if (!this.validateFields(rowIndex))
                return false;

            // Call Form_CustomValidate event
            if (!this.customValidate(fobj)) {
                this.focus();
                return false;
            }
        }
        return true;
    }

    // Form_CustomValidate
    fview_articuloslist.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fview_articuloslist.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fview_articuloslist.lists.fabricante = <?= $Page->fabricante->toClientList($Page) ?>;
    loadjs.done("fview_articuloslist");
});
var fview_articuloslistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    fview_articuloslistsrch = currentSearchForm = new ew.Form("fview_articuloslistsrch");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "view_articulos")) ?>,
        fields = currentTable.fields;
    fview_articuloslistsrch.addFields([
        ["referencia", [], fields.referencia.isInvalid],
        ["codigo", [], fields.codigo.isInvalid],
        ["fabricante", [], fields.fabricante.isInvalid],
        ["nombre", [], fields.nombre.isInvalid],
        ["cantidad_en_mano", [], fields.cantidad_en_mano.isInvalid],
        ["ultimo_costo", [], fields.ultimo_costo.isInvalid],
        ["precio", [], fields.precio.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        fview_articuloslistsrch.setInvalid();
    });

    // Validate form
    fview_articuloslistsrch.validate = function () {
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
    fview_articuloslistsrch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fview_articuloslistsrch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fview_articuloslistsrch.lists.fabricante = <?= $Page->fabricante->toClientList($Page) ?>;

    // Filters
    fview_articuloslistsrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    fview_articuloslistsrch.initSearchPanel = true;
    loadjs.done("fview_articuloslistsrch");
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
<form name="fview_articuloslistsrch" id="fview_articuloslistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fview_articuloslistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="view_articulos">
    <div class="ew-extended-search">
<?php
// Render search row
$Page->RowType = ROWTYPE_SEARCH;
$Page->resetAttributes();
$Page->renderRow();
?>
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
        <label for="x_fabricante" class="ew-search-caption ew-label"><?= $Page->fabricante->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_fabricante" id="z_fabricante" value="=">
</span>
        <span id="el_view_articulos_fabricante" class="ew-search-field">
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x_fabricante"><?= EmptyValue(strval($Page->fabricante->AdvancedSearch->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->fabricante->AdvancedSearch->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->fabricante->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->fabricante->ReadOnly || $Page->fabricante->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x_fabricante',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->fabricante->getErrorMessage(false) ?></div>
<?= $Page->fabricante->Lookup->getParamTag($Page, "p_x_fabricante") ?>
<input type="hidden" is="selection-list" data-table="view_articulos" data-field="x_fabricante" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->fabricante->displayValueSeparatorAttribute() ?>" name="x_fabricante" id="x_fabricante" value="<?= $Page->fabricante->AdvancedSearch->SearchValue ?>"<?= $Page->fabricante->editAttributes() ?>>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> view_articulos">
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
<form name="fview_articuloslist" id="fview_articuloslist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="view_articulos">
<div id="gmp_view_articulos" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_view_articuloslist" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Page->referencia->Visible) { // referencia ?>
        <th data-name="referencia" class="<?= $Page->referencia->headerCellClass() ?>"><div id="elh_view_articulos_referencia" class="view_articulos_referencia"><?= $Page->renderSort($Page->referencia) ?></div></th>
<?php } ?>
<?php if ($Page->codigo->Visible) { // codigo ?>
        <th data-name="codigo" class="<?= $Page->codigo->headerCellClass() ?>"><div id="elh_view_articulos_codigo" class="view_articulos_codigo"><?= $Page->renderSort($Page->codigo) ?></div></th>
<?php } ?>
<?php if ($Page->fabricante->Visible) { // fabricante ?>
        <th data-name="fabricante" class="<?= $Page->fabricante->headerCellClass() ?>"><div id="elh_view_articulos_fabricante" class="view_articulos_fabricante"><?= $Page->renderSort($Page->fabricante) ?></div></th>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
        <th data-name="nombre" class="<?= $Page->nombre->headerCellClass() ?>"><div id="elh_view_articulos_nombre" class="view_articulos_nombre"><?= $Page->renderSort($Page->nombre) ?></div></th>
<?php } ?>
<?php if ($Page->cantidad_en_mano->Visible) { // cantidad_en_mano ?>
        <th data-name="cantidad_en_mano" class="<?= $Page->cantidad_en_mano->headerCellClass() ?>"><div id="elh_view_articulos_cantidad_en_mano" class="view_articulos_cantidad_en_mano"><?= $Page->renderSort($Page->cantidad_en_mano) ?></div></th>
<?php } ?>
<?php if ($Page->ultimo_costo->Visible) { // ultimo_costo ?>
        <th data-name="ultimo_costo" class="<?= $Page->ultimo_costo->headerCellClass() ?>"><div id="elh_view_articulos_ultimo_costo" class="view_articulos_ultimo_costo"><?= $Page->renderSort($Page->ultimo_costo) ?></div></th>
<?php } ?>
<?php if ($Page->precio->Visible) { // precio ?>
        <th data-name="precio" class="<?= $Page->precio->headerCellClass() ?>"><div id="elh_view_articulos_precio" class="view_articulos_precio"><?= $Page->renderSort($Page->precio) ?></div></th>
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_view_articulos", "data-rowtype" => $Page->RowType]);

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
    <?php if ($Page->referencia->Visible) { // referencia ?>
        <td data-name="referencia" <?= $Page->referencia->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_view_articulos_referencia" class="form-group">
<input type="<?= $Page->referencia->getInputTextType() ?>" data-table="view_articulos" data-field="x_referencia" name="x<?= $Page->RowIndex ?>_referencia" id="x<?= $Page->RowIndex ?>_referencia" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->referencia->getPlaceHolder()) ?>" value="<?= $Page->referencia->EditValue ?>"<?= $Page->referencia->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->referencia->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_articulos" data-field="x_referencia" data-hidden="1" name="o<?= $Page->RowIndex ?>_referencia" id="o<?= $Page->RowIndex ?>_referencia" value="<?= HtmlEncode($Page->referencia->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_view_articulos_referencia" class="form-group">
<span<?= $Page->referencia->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->referencia->getDisplayValue($Page->referencia->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="view_articulos" data-field="x_referencia" data-hidden="1" name="x<?= $Page->RowIndex ?>_referencia" id="x<?= $Page->RowIndex ?>_referencia" value="<?= HtmlEncode($Page->referencia->CurrentValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_view_articulos_referencia">
<span<?= $Page->referencia->viewAttributes() ?>>
<?= $Page->referencia->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->codigo->Visible) { // codigo ?>
        <td data-name="codigo" <?= $Page->codigo->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_view_articulos_codigo" class="form-group">
<input type="<?= $Page->codigo->getInputTextType() ?>" data-table="view_articulos" data-field="x_codigo" name="x<?= $Page->RowIndex ?>_codigo" id="x<?= $Page->RowIndex ?>_codigo" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->codigo->getPlaceHolder()) ?>" value="<?= $Page->codigo->EditValue ?>"<?= $Page->codigo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->codigo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_articulos" data-field="x_codigo" data-hidden="1" name="o<?= $Page->RowIndex ?>_codigo" id="o<?= $Page->RowIndex ?>_codigo" value="<?= HtmlEncode($Page->codigo->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_view_articulos_codigo" class="form-group">
<span<?= $Page->codigo->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->codigo->getDisplayValue($Page->codigo->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="view_articulos" data-field="x_codigo" data-hidden="1" name="x<?= $Page->RowIndex ?>_codigo" id="x<?= $Page->RowIndex ?>_codigo" value="<?= HtmlEncode($Page->codigo->CurrentValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_view_articulos_codigo">
<span<?= $Page->codigo->viewAttributes() ?>>
<?= $Page->codigo->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->fabricante->Visible) { // fabricante ?>
        <td data-name="fabricante" <?= $Page->fabricante->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_view_articulos_fabricante" class="form-group">
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Page->RowIndex ?>_fabricante"><?= EmptyValue(strval($Page->fabricante->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->fabricante->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->fabricante->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->fabricante->ReadOnly || $Page->fabricante->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Page->RowIndex ?>_fabricante',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->fabricante->getErrorMessage() ?></div>
<?= $Page->fabricante->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_fabricante") ?>
<input type="hidden" is="selection-list" data-table="view_articulos" data-field="x_fabricante" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->fabricante->displayValueSeparatorAttribute() ?>" name="x<?= $Page->RowIndex ?>_fabricante" id="x<?= $Page->RowIndex ?>_fabricante" value="<?= $Page->fabricante->CurrentValue ?>"<?= $Page->fabricante->editAttributes() ?>>
</span>
<input type="hidden" data-table="view_articulos" data-field="x_fabricante" data-hidden="1" name="o<?= $Page->RowIndex ?>_fabricante" id="o<?= $Page->RowIndex ?>_fabricante" value="<?= HtmlEncode($Page->fabricante->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_view_articulos_fabricante" class="form-group">
<span<?= $Page->fabricante->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->fabricante->getDisplayValue($Page->fabricante->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="view_articulos" data-field="x_fabricante" data-hidden="1" name="x<?= $Page->RowIndex ?>_fabricante" id="x<?= $Page->RowIndex ?>_fabricante" value="<?= HtmlEncode($Page->fabricante->CurrentValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_view_articulos_fabricante">
<span<?= $Page->fabricante->viewAttributes() ?>>
<?= $Page->fabricante->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->nombre->Visible) { // nombre ?>
        <td data-name="nombre" <?= $Page->nombre->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_view_articulos_nombre" class="form-group">
<input type="<?= $Page->nombre->getInputTextType() ?>" data-table="view_articulos" data-field="x_nombre" name="x<?= $Page->RowIndex ?>_nombre" id="x<?= $Page->RowIndex ?>_nombre" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->nombre->getPlaceHolder()) ?>" value="<?= $Page->nombre->EditValue ?>"<?= $Page->nombre->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->nombre->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_articulos" data-field="x_nombre" data-hidden="1" name="o<?= $Page->RowIndex ?>_nombre" id="o<?= $Page->RowIndex ?>_nombre" value="<?= HtmlEncode($Page->nombre->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_view_articulos_nombre" class="form-group">
<span<?= $Page->nombre->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->nombre->getDisplayValue($Page->nombre->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="view_articulos" data-field="x_nombre" data-hidden="1" name="x<?= $Page->RowIndex ?>_nombre" id="x<?= $Page->RowIndex ?>_nombre" value="<?= HtmlEncode($Page->nombre->CurrentValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_view_articulos_nombre">
<span<?= $Page->nombre->viewAttributes() ?>>
<?= $Page->nombre->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->cantidad_en_mano->Visible) { // cantidad_en_mano ?>
        <td data-name="cantidad_en_mano" <?= $Page->cantidad_en_mano->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_view_articulos_cantidad_en_mano" class="form-group">
<input type="<?= $Page->cantidad_en_mano->getInputTextType() ?>" data-table="view_articulos" data-field="x_cantidad_en_mano" name="x<?= $Page->RowIndex ?>_cantidad_en_mano" id="x<?= $Page->RowIndex ?>_cantidad_en_mano" size="30" maxlength="9" placeholder="<?= HtmlEncode($Page->cantidad_en_mano->getPlaceHolder()) ?>" value="<?= $Page->cantidad_en_mano->EditValue ?>"<?= $Page->cantidad_en_mano->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->cantidad_en_mano->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_articulos" data-field="x_cantidad_en_mano" data-hidden="1" name="o<?= $Page->RowIndex ?>_cantidad_en_mano" id="o<?= $Page->RowIndex ?>_cantidad_en_mano" value="<?= HtmlEncode($Page->cantidad_en_mano->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_view_articulos_cantidad_en_mano" class="form-group">
<span<?= $Page->cantidad_en_mano->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->cantidad_en_mano->getDisplayValue($Page->cantidad_en_mano->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="view_articulos" data-field="x_cantidad_en_mano" data-hidden="1" name="x<?= $Page->RowIndex ?>_cantidad_en_mano" id="x<?= $Page->RowIndex ?>_cantidad_en_mano" value="<?= HtmlEncode($Page->cantidad_en_mano->CurrentValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_view_articulos_cantidad_en_mano">
<span<?= $Page->cantidad_en_mano->viewAttributes() ?>>
<?= $Page->cantidad_en_mano->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->ultimo_costo->Visible) { // ultimo_costo ?>
        <td data-name="ultimo_costo" <?= $Page->ultimo_costo->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_view_articulos_ultimo_costo" class="form-group">
<input type="<?= $Page->ultimo_costo->getInputTextType() ?>" data-table="view_articulos" data-field="x_ultimo_costo" name="x<?= $Page->RowIndex ?>_ultimo_costo" id="x<?= $Page->RowIndex ?>_ultimo_costo" size="10" maxlength="14" placeholder="<?= HtmlEncode($Page->ultimo_costo->getPlaceHolder()) ?>" value="<?= $Page->ultimo_costo->EditValue ?>"<?= $Page->ultimo_costo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ultimo_costo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_articulos" data-field="x_ultimo_costo" data-hidden="1" name="o<?= $Page->RowIndex ?>_ultimo_costo" id="o<?= $Page->RowIndex ?>_ultimo_costo" value="<?= HtmlEncode($Page->ultimo_costo->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_view_articulos_ultimo_costo" class="form-group">
<input type="<?= $Page->ultimo_costo->getInputTextType() ?>" data-table="view_articulos" data-field="x_ultimo_costo" name="x<?= $Page->RowIndex ?>_ultimo_costo" id="x<?= $Page->RowIndex ?>_ultimo_costo" size="10" maxlength="14" placeholder="<?= HtmlEncode($Page->ultimo_costo->getPlaceHolder()) ?>" value="<?= $Page->ultimo_costo->EditValue ?>"<?= $Page->ultimo_costo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ultimo_costo->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_view_articulos_ultimo_costo">
<span<?= $Page->ultimo_costo->viewAttributes() ?>>
<?= $Page->ultimo_costo->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->precio->Visible) { // precio ?>
        <td data-name="precio" <?= $Page->precio->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_view_articulos_precio" class="form-group">
<input type="<?= $Page->precio->getInputTextType() ?>" data-table="view_articulos" data-field="x_precio" name="x<?= $Page->RowIndex ?>_precio" id="x<?= $Page->RowIndex ?>_precio" size="10" maxlength="14" placeholder="<?= HtmlEncode($Page->precio->getPlaceHolder()) ?>" value="<?= $Page->precio->EditValue ?>"<?= $Page->precio->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->precio->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_articulos" data-field="x_precio" data-hidden="1" name="o<?= $Page->RowIndex ?>_precio" id="o<?= $Page->RowIndex ?>_precio" value="<?= HtmlEncode($Page->precio->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_view_articulos_precio" class="form-group">
<input type="<?= $Page->precio->getInputTextType() ?>" data-table="view_articulos" data-field="x_precio" name="x<?= $Page->RowIndex ?>_precio" id="x<?= $Page->RowIndex ?>_precio" size="10" maxlength="14" placeholder="<?= HtmlEncode($Page->precio->getPlaceHolder()) ?>" value="<?= $Page->precio->EditValue ?>"<?= $Page->precio->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->precio->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_view_articulos_precio">
<span<?= $Page->precio->viewAttributes() ?>>
<?= $Page->precio->getViewValue() ?></span>
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
loadjs.ready(["fview_articuloslist","load"], function () {
    fview_articuloslist.updateLists(<?= $Page->RowIndex ?>);
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowIndex, "id" => "r0_view_articulos", "data-rowtype" => ROWTYPE_ADD]);
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
    <?php if ($Page->referencia->Visible) { // referencia ?>
        <td data-name="referencia">
<span id="el$rowindex$_view_articulos_referencia" class="form-group view_articulos_referencia">
<input type="<?= $Page->referencia->getInputTextType() ?>" data-table="view_articulos" data-field="x_referencia" name="x<?= $Page->RowIndex ?>_referencia" id="x<?= $Page->RowIndex ?>_referencia" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->referencia->getPlaceHolder()) ?>" value="<?= $Page->referencia->EditValue ?>"<?= $Page->referencia->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->referencia->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_articulos" data-field="x_referencia" data-hidden="1" name="o<?= $Page->RowIndex ?>_referencia" id="o<?= $Page->RowIndex ?>_referencia" value="<?= HtmlEncode($Page->referencia->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->codigo->Visible) { // codigo ?>
        <td data-name="codigo">
<span id="el$rowindex$_view_articulos_codigo" class="form-group view_articulos_codigo">
<input type="<?= $Page->codigo->getInputTextType() ?>" data-table="view_articulos" data-field="x_codigo" name="x<?= $Page->RowIndex ?>_codigo" id="x<?= $Page->RowIndex ?>_codigo" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->codigo->getPlaceHolder()) ?>" value="<?= $Page->codigo->EditValue ?>"<?= $Page->codigo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->codigo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_articulos" data-field="x_codigo" data-hidden="1" name="o<?= $Page->RowIndex ?>_codigo" id="o<?= $Page->RowIndex ?>_codigo" value="<?= HtmlEncode($Page->codigo->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->fabricante->Visible) { // fabricante ?>
        <td data-name="fabricante">
<span id="el$rowindex$_view_articulos_fabricante" class="form-group view_articulos_fabricante">
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Page->RowIndex ?>_fabricante"><?= EmptyValue(strval($Page->fabricante->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->fabricante->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->fabricante->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->fabricante->ReadOnly || $Page->fabricante->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Page->RowIndex ?>_fabricante',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->fabricante->getErrorMessage() ?></div>
<?= $Page->fabricante->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_fabricante") ?>
<input type="hidden" is="selection-list" data-table="view_articulos" data-field="x_fabricante" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->fabricante->displayValueSeparatorAttribute() ?>" name="x<?= $Page->RowIndex ?>_fabricante" id="x<?= $Page->RowIndex ?>_fabricante" value="<?= $Page->fabricante->CurrentValue ?>"<?= $Page->fabricante->editAttributes() ?>>
</span>
<input type="hidden" data-table="view_articulos" data-field="x_fabricante" data-hidden="1" name="o<?= $Page->RowIndex ?>_fabricante" id="o<?= $Page->RowIndex ?>_fabricante" value="<?= HtmlEncode($Page->fabricante->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->nombre->Visible) { // nombre ?>
        <td data-name="nombre">
<span id="el$rowindex$_view_articulos_nombre" class="form-group view_articulos_nombre">
<input type="<?= $Page->nombre->getInputTextType() ?>" data-table="view_articulos" data-field="x_nombre" name="x<?= $Page->RowIndex ?>_nombre" id="x<?= $Page->RowIndex ?>_nombre" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->nombre->getPlaceHolder()) ?>" value="<?= $Page->nombre->EditValue ?>"<?= $Page->nombre->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->nombre->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_articulos" data-field="x_nombre" data-hidden="1" name="o<?= $Page->RowIndex ?>_nombre" id="o<?= $Page->RowIndex ?>_nombre" value="<?= HtmlEncode($Page->nombre->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->cantidad_en_mano->Visible) { // cantidad_en_mano ?>
        <td data-name="cantidad_en_mano">
<span id="el$rowindex$_view_articulos_cantidad_en_mano" class="form-group view_articulos_cantidad_en_mano">
<input type="<?= $Page->cantidad_en_mano->getInputTextType() ?>" data-table="view_articulos" data-field="x_cantidad_en_mano" name="x<?= $Page->RowIndex ?>_cantidad_en_mano" id="x<?= $Page->RowIndex ?>_cantidad_en_mano" size="30" maxlength="9" placeholder="<?= HtmlEncode($Page->cantidad_en_mano->getPlaceHolder()) ?>" value="<?= $Page->cantidad_en_mano->EditValue ?>"<?= $Page->cantidad_en_mano->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->cantidad_en_mano->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_articulos" data-field="x_cantidad_en_mano" data-hidden="1" name="o<?= $Page->RowIndex ?>_cantidad_en_mano" id="o<?= $Page->RowIndex ?>_cantidad_en_mano" value="<?= HtmlEncode($Page->cantidad_en_mano->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->ultimo_costo->Visible) { // ultimo_costo ?>
        <td data-name="ultimo_costo">
<span id="el$rowindex$_view_articulos_ultimo_costo" class="form-group view_articulos_ultimo_costo">
<input type="<?= $Page->ultimo_costo->getInputTextType() ?>" data-table="view_articulos" data-field="x_ultimo_costo" name="x<?= $Page->RowIndex ?>_ultimo_costo" id="x<?= $Page->RowIndex ?>_ultimo_costo" size="10" maxlength="14" placeholder="<?= HtmlEncode($Page->ultimo_costo->getPlaceHolder()) ?>" value="<?= $Page->ultimo_costo->EditValue ?>"<?= $Page->ultimo_costo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->ultimo_costo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_articulos" data-field="x_ultimo_costo" data-hidden="1" name="o<?= $Page->RowIndex ?>_ultimo_costo" id="o<?= $Page->RowIndex ?>_ultimo_costo" value="<?= HtmlEncode($Page->ultimo_costo->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->precio->Visible) { // precio ?>
        <td data-name="precio">
<span id="el$rowindex$_view_articulos_precio" class="form-group view_articulos_precio">
<input type="<?= $Page->precio->getInputTextType() ?>" data-table="view_articulos" data-field="x_precio" name="x<?= $Page->RowIndex ?>_precio" id="x<?= $Page->RowIndex ?>_precio" size="10" maxlength="14" placeholder="<?= HtmlEncode($Page->precio->getPlaceHolder()) ?>" value="<?= $Page->precio->EditValue ?>"<?= $Page->precio->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->precio->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="view_articulos" data-field="x_precio" data-hidden="1" name="o<?= $Page->RowIndex ?>_precio" id="o<?= $Page->RowIndex ?>_precio" value="<?= HtmlEncode($Page->precio->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowIndex);
?>
<script>
loadjs.ready(["fview_articuloslist","load"], function() {
    fview_articuloslist.updateLists(<?= $Page->RowIndex ?>);
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
    ew.addEventHandlers("view_articulos");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
