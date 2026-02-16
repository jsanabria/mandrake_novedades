<?php

namespace PHPMaker2021\mandrake;

// Page object
$ProveedorArticuloList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fproveedor_articulolist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    fproveedor_articulolist = currentForm = new ew.Form("fproveedor_articulolist", "list");
    fproveedor_articulolist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "proveedor_articulo")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.proveedor_articulo)
        ew.vars.tables.proveedor_articulo = currentTable;
    fproveedor_articulolist.addFields([
        ["fabricante", [fields.fabricante.visible && fields.fabricante.required ? ew.Validators.required(fields.fabricante.caption) : null], fields.fabricante.isInvalid],
        ["articulo", [fields.articulo.visible && fields.articulo.required ? ew.Validators.required(fields.articulo.caption) : null], fields.articulo.isInvalid],
        ["codigo_proveedor", [fields.codigo_proveedor.visible && fields.codigo_proveedor.required ? ew.Validators.required(fields.codigo_proveedor.caption) : null], fields.codigo_proveedor.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fproveedor_articulolist,
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
    fproveedor_articulolist.validate = function () {
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
    fproveedor_articulolist.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "fabricante", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "articulo", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "codigo_proveedor", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    fproveedor_articulolist.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fproveedor_articulolist.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fproveedor_articulolist.lists.fabricante = <?= $Page->fabricante->toClientList($Page) ?>;
    fproveedor_articulolist.lists.articulo = <?= $Page->articulo->toClientList($Page) ?>;
    loadjs.done("fproveedor_articulolist");
});
var fproveedor_articulolistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    fproveedor_articulolistsrch = currentSearchForm = new ew.Form("fproveedor_articulolistsrch");

    // Dynamic selection lists

    // Filters
    fproveedor_articulolistsrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    fproveedor_articulolistsrch.initSearchPanel = true;
    loadjs.done("fproveedor_articulolistsrch");
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
if ($Page->DbMasterFilter != "" && $Page->getCurrentMasterTable() == "proveedor") {
    if ($Page->MasterRecordExists) {
        include_once "views/ProveedorMaster.php";
    }
}
?>
<?php } ?>
<?php
$Page->renderOtherOptions();
?>
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !$Page->CurrentAction) { ?>
<form name="fproveedor_articulolistsrch" id="fproveedor_articulolistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fproveedor_articulolistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="proveedor_articulo">
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> proveedor_articulo">
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
<form name="fproveedor_articulolist" id="fproveedor_articulolist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="proveedor_articulo">
<?php if ($Page->getCurrentMasterTable() == "proveedor" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="proveedor">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->proveedor->getSessionValue()) ?>">
<?php } ?>
<div id="gmp_proveedor_articulo" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_proveedor_articulolist" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Page->fabricante->Visible) { // fabricante ?>
        <th data-name="fabricante" class="<?= $Page->fabricante->headerCellClass() ?>"><div id="elh_proveedor_articulo_fabricante" class="proveedor_articulo_fabricante"><?= $Page->renderSort($Page->fabricante) ?></div></th>
<?php } ?>
<?php if ($Page->articulo->Visible) { // articulo ?>
        <th data-name="articulo" class="<?= $Page->articulo->headerCellClass() ?>"><div id="elh_proveedor_articulo_articulo" class="proveedor_articulo_articulo"><?= $Page->renderSort($Page->articulo) ?></div></th>
<?php } ?>
<?php if ($Page->codigo_proveedor->Visible) { // codigo_proveedor ?>
        <th data-name="codigo_proveedor" class="<?= $Page->codigo_proveedor->headerCellClass() ?>"><div id="elh_proveedor_articulo_codigo_proveedor" class="proveedor_articulo_codigo_proveedor"><?= $Page->renderSort($Page->codigo_proveedor) ?></div></th>
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_proveedor_articulo", "data-rowtype" => $Page->RowType]);

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
    <?php if ($Page->fabricante->Visible) { // fabricante ?>
        <td data-name="fabricante" <?= $Page->fabricante->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_proveedor_articulo_fabricante" class="form-group">
<?php $Page->fabricante->EditAttrs->prepend("onchange", "ew.updateOptions.call(this);"); ?>
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Page->RowIndex ?>_fabricante"><?= EmptyValue(strval($Page->fabricante->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->fabricante->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->fabricante->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->fabricante->ReadOnly || $Page->fabricante->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Page->RowIndex ?>_fabricante',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->fabricante->getErrorMessage() ?></div>
<?= $Page->fabricante->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_fabricante") ?>
<input type="hidden" is="selection-list" data-table="proveedor_articulo" data-field="x_fabricante" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->fabricante->displayValueSeparatorAttribute() ?>" name="x<?= $Page->RowIndex ?>_fabricante" id="x<?= $Page->RowIndex ?>_fabricante" value="<?= $Page->fabricante->CurrentValue ?>"<?= $Page->fabricante->editAttributes() ?>>
</span>
<input type="hidden" data-table="proveedor_articulo" data-field="x_fabricante" data-hidden="1" name="o<?= $Page->RowIndex ?>_fabricante" id="o<?= $Page->RowIndex ?>_fabricante" value="<?= HtmlEncode($Page->fabricante->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_proveedor_articulo_fabricante" class="form-group">
<?php $Page->fabricante->EditAttrs->prepend("onchange", "ew.updateOptions.call(this);"); ?>
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Page->RowIndex ?>_fabricante"><?= EmptyValue(strval($Page->fabricante->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->fabricante->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->fabricante->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->fabricante->ReadOnly || $Page->fabricante->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Page->RowIndex ?>_fabricante',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->fabricante->getErrorMessage() ?></div>
<?= $Page->fabricante->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_fabricante") ?>
<input type="hidden" is="selection-list" data-table="proveedor_articulo" data-field="x_fabricante" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->fabricante->displayValueSeparatorAttribute() ?>" name="x<?= $Page->RowIndex ?>_fabricante" id="x<?= $Page->RowIndex ?>_fabricante" value="<?= $Page->fabricante->CurrentValue ?>"<?= $Page->fabricante->editAttributes() ?>>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_proveedor_articulo_fabricante">
<span<?= $Page->fabricante->viewAttributes() ?>>
<?= $Page->fabricante->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->articulo->Visible) { // articulo ?>
        <td data-name="articulo" <?= $Page->articulo->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_proveedor_articulo_articulo" class="form-group">
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Page->RowIndex ?>_articulo"><?= EmptyValue(strval($Page->articulo->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->articulo->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->articulo->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->articulo->ReadOnly || $Page->articulo->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Page->RowIndex ?>_articulo',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->articulo->getErrorMessage() ?></div>
<?= $Page->articulo->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_articulo") ?>
<input type="hidden" is="selection-list" data-table="proveedor_articulo" data-field="x_articulo" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->articulo->displayValueSeparatorAttribute() ?>" name="x<?= $Page->RowIndex ?>_articulo" id="x<?= $Page->RowIndex ?>_articulo" value="<?= $Page->articulo->CurrentValue ?>"<?= $Page->articulo->editAttributes() ?>>
</span>
<input type="hidden" data-table="proveedor_articulo" data-field="x_articulo" data-hidden="1" name="o<?= $Page->RowIndex ?>_articulo" id="o<?= $Page->RowIndex ?>_articulo" value="<?= HtmlEncode($Page->articulo->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_proveedor_articulo_articulo" class="form-group">
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Page->RowIndex ?>_articulo"><?= EmptyValue(strval($Page->articulo->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->articulo->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->articulo->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->articulo->ReadOnly || $Page->articulo->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Page->RowIndex ?>_articulo',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->articulo->getErrorMessage() ?></div>
<?= $Page->articulo->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_articulo") ?>
<input type="hidden" is="selection-list" data-table="proveedor_articulo" data-field="x_articulo" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->articulo->displayValueSeparatorAttribute() ?>" name="x<?= $Page->RowIndex ?>_articulo" id="x<?= $Page->RowIndex ?>_articulo" value="<?= $Page->articulo->CurrentValue ?>"<?= $Page->articulo->editAttributes() ?>>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_proveedor_articulo_articulo">
<span<?= $Page->articulo->viewAttributes() ?>>
<?= $Page->articulo->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->codigo_proveedor->Visible) { // codigo_proveedor ?>
        <td data-name="codigo_proveedor" <?= $Page->codigo_proveedor->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_proveedor_articulo_codigo_proveedor" class="form-group">
<input type="<?= $Page->codigo_proveedor->getInputTextType() ?>" data-table="proveedor_articulo" data-field="x_codigo_proveedor" name="x<?= $Page->RowIndex ?>_codigo_proveedor" id="x<?= $Page->RowIndex ?>_codigo_proveedor" size="10" maxlength="30" placeholder="<?= HtmlEncode($Page->codigo_proveedor->getPlaceHolder()) ?>" value="<?= $Page->codigo_proveedor->EditValue ?>"<?= $Page->codigo_proveedor->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->codigo_proveedor->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="proveedor_articulo" data-field="x_codigo_proveedor" data-hidden="1" name="o<?= $Page->RowIndex ?>_codigo_proveedor" id="o<?= $Page->RowIndex ?>_codigo_proveedor" value="<?= HtmlEncode($Page->codigo_proveedor->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_proveedor_articulo_codigo_proveedor" class="form-group">
<input type="<?= $Page->codigo_proveedor->getInputTextType() ?>" data-table="proveedor_articulo" data-field="x_codigo_proveedor" name="x<?= $Page->RowIndex ?>_codigo_proveedor" id="x<?= $Page->RowIndex ?>_codigo_proveedor" size="10" maxlength="30" placeholder="<?= HtmlEncode($Page->codigo_proveedor->getPlaceHolder()) ?>" value="<?= $Page->codigo_proveedor->EditValue ?>"<?= $Page->codigo_proveedor->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->codigo_proveedor->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_proveedor_articulo_codigo_proveedor">
<span<?= $Page->codigo_proveedor->viewAttributes() ?>>
<?= $Page->codigo_proveedor->getViewValue() ?></span>
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
loadjs.ready(["fproveedor_articulolist","load"], function () {
    fproveedor_articulolist.updateLists(<?= $Page->RowIndex ?>);
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowIndex, "id" => "r0_proveedor_articulo", "data-rowtype" => ROWTYPE_ADD]);
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
    <?php if ($Page->fabricante->Visible) { // fabricante ?>
        <td data-name="fabricante">
<span id="el$rowindex$_proveedor_articulo_fabricante" class="form-group proveedor_articulo_fabricante">
<?php $Page->fabricante->EditAttrs->prepend("onchange", "ew.updateOptions.call(this);"); ?>
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Page->RowIndex ?>_fabricante"><?= EmptyValue(strval($Page->fabricante->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->fabricante->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->fabricante->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->fabricante->ReadOnly || $Page->fabricante->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Page->RowIndex ?>_fabricante',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->fabricante->getErrorMessage() ?></div>
<?= $Page->fabricante->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_fabricante") ?>
<input type="hidden" is="selection-list" data-table="proveedor_articulo" data-field="x_fabricante" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->fabricante->displayValueSeparatorAttribute() ?>" name="x<?= $Page->RowIndex ?>_fabricante" id="x<?= $Page->RowIndex ?>_fabricante" value="<?= $Page->fabricante->CurrentValue ?>"<?= $Page->fabricante->editAttributes() ?>>
</span>
<input type="hidden" data-table="proveedor_articulo" data-field="x_fabricante" data-hidden="1" name="o<?= $Page->RowIndex ?>_fabricante" id="o<?= $Page->RowIndex ?>_fabricante" value="<?= HtmlEncode($Page->fabricante->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->articulo->Visible) { // articulo ?>
        <td data-name="articulo">
<span id="el$rowindex$_proveedor_articulo_articulo" class="form-group proveedor_articulo_articulo">
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Page->RowIndex ?>_articulo"><?= EmptyValue(strval($Page->articulo->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->articulo->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->articulo->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->articulo->ReadOnly || $Page->articulo->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Page->RowIndex ?>_articulo',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->articulo->getErrorMessage() ?></div>
<?= $Page->articulo->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_articulo") ?>
<input type="hidden" is="selection-list" data-table="proveedor_articulo" data-field="x_articulo" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->articulo->displayValueSeparatorAttribute() ?>" name="x<?= $Page->RowIndex ?>_articulo" id="x<?= $Page->RowIndex ?>_articulo" value="<?= $Page->articulo->CurrentValue ?>"<?= $Page->articulo->editAttributes() ?>>
</span>
<input type="hidden" data-table="proveedor_articulo" data-field="x_articulo" data-hidden="1" name="o<?= $Page->RowIndex ?>_articulo" id="o<?= $Page->RowIndex ?>_articulo" value="<?= HtmlEncode($Page->articulo->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->codigo_proveedor->Visible) { // codigo_proveedor ?>
        <td data-name="codigo_proveedor">
<span id="el$rowindex$_proveedor_articulo_codigo_proveedor" class="form-group proveedor_articulo_codigo_proveedor">
<input type="<?= $Page->codigo_proveedor->getInputTextType() ?>" data-table="proveedor_articulo" data-field="x_codigo_proveedor" name="x<?= $Page->RowIndex ?>_codigo_proveedor" id="x<?= $Page->RowIndex ?>_codigo_proveedor" size="10" maxlength="30" placeholder="<?= HtmlEncode($Page->codigo_proveedor->getPlaceHolder()) ?>" value="<?= $Page->codigo_proveedor->EditValue ?>"<?= $Page->codigo_proveedor->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->codigo_proveedor->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="proveedor_articulo" data-field="x_codigo_proveedor" data-hidden="1" name="o<?= $Page->RowIndex ?>_codigo_proveedor" id="o<?= $Page->RowIndex ?>_codigo_proveedor" value="<?= HtmlEncode($Page->codigo_proveedor->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowIndex);
?>
<script>
loadjs.ready(["fproveedor_articulolist","load"], function() {
    fproveedor_articulolist.updateLists(<?= $Page->RowIndex ?>);
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
    ew.addEventHandlers("proveedor_articulo");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
