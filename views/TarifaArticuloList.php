<?php

namespace PHPMaker2021\mandrake;

// Page object
$TarifaArticuloList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var ftarifa_articulolist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    ftarifa_articulolist = currentForm = new ew.Form("ftarifa_articulolist", "list");
    ftarifa_articulolist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "tarifa_articulo")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.tarifa_articulo)
        ew.vars.tables.tarifa_articulo = currentTable;
    ftarifa_articulolist.addFields([
        ["tarifa", [fields.tarifa.visible && fields.tarifa.required ? ew.Validators.required(fields.tarifa.caption) : null], fields.tarifa.isInvalid],
        ["fabricante", [fields.fabricante.visible && fields.fabricante.required ? ew.Validators.required(fields.fabricante.caption) : null], fields.fabricante.isInvalid],
        ["articulo", [fields.articulo.visible && fields.articulo.required ? ew.Validators.required(fields.articulo.caption) : null], fields.articulo.isInvalid],
        ["precio", [fields.precio.visible && fields.precio.required ? ew.Validators.required(fields.precio.caption) : null, ew.Validators.float], fields.precio.isInvalid],
        ["precio2", [fields.precio2.visible && fields.precio2.required ? ew.Validators.required(fields.precio2.caption) : null, ew.Validators.float], fields.precio2.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = ftarifa_articulolist,
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
    ftarifa_articulolist.validate = function () {
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
    ftarifa_articulolist.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "tarifa", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "fabricante", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "articulo", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "precio", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "precio2", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    ftarifa_articulolist.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    ftarifa_articulolist.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    ftarifa_articulolist.lists.tarifa = <?= $Page->tarifa->toClientList($Page) ?>;
    ftarifa_articulolist.lists.fabricante = <?= $Page->fabricante->toClientList($Page) ?>;
    ftarifa_articulolist.lists.articulo = <?= $Page->articulo->toClientList($Page) ?>;
    loadjs.done("ftarifa_articulolist");
});
var ftarifa_articulolistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    ftarifa_articulolistsrch = currentSearchForm = new ew.Form("ftarifa_articulolistsrch");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "tarifa_articulo")) ?>,
        fields = currentTable.fields;
    ftarifa_articulolistsrch.addFields([
        ["tarifa", [], fields.tarifa.isInvalid],
        ["fabricante", [], fields.fabricante.isInvalid],
        ["articulo", [], fields.articulo.isInvalid],
        ["precio", [], fields.precio.isInvalid],
        ["precio2", [], fields.precio2.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        ftarifa_articulolistsrch.setInvalid();
    });

    // Validate form
    ftarifa_articulolistsrch.validate = function () {
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
    ftarifa_articulolistsrch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    ftarifa_articulolistsrch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    ftarifa_articulolistsrch.lists.tarifa = <?= $Page->tarifa->toClientList($Page) ?>;
    ftarifa_articulolistsrch.lists.fabricante = <?= $Page->fabricante->toClientList($Page) ?>;
    ftarifa_articulolistsrch.lists.articulo = <?= $Page->articulo->toClientList($Page) ?>;

    // Filters
    ftarifa_articulolistsrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    ftarifa_articulolistsrch.initSearchPanel = true;
    loadjs.done("ftarifa_articulolistsrch");
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
if ($Page->DbMasterFilter != "" && $Page->getCurrentMasterTable() == "tarifa") {
    if ($Page->MasterRecordExists) {
        include_once "views/TarifaMaster.php";
    }
}
?>
<?php } ?>
<?php
$Page->renderOtherOptions();
?>
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !$Page->CurrentAction) { ?>
<form name="ftarifa_articulolistsrch" id="ftarifa_articulolistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="ftarifa_articulolistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="tarifa_articulo">
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
        <label for="x_tarifa" class="ew-search-caption ew-label"><?= $Page->tarifa->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_tarifa" id="z_tarifa" value="=">
</span>
        <span id="el_tarifa_articulo_tarifa" class="ew-search-field">
    <select
        id="x_tarifa"
        name="x_tarifa"
        class="form-control ew-select<?= $Page->tarifa->isInvalidClass() ?>"
        data-select2-id="tarifa_articulo_x_tarifa"
        data-table="tarifa_articulo"
        data-field="x_tarifa"
        data-value-separator="<?= $Page->tarifa->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->tarifa->getPlaceHolder()) ?>"
        <?= $Page->tarifa->editAttributes() ?>>
        <?= $Page->tarifa->selectOptionListHtml("x_tarifa") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->tarifa->getErrorMessage(false) ?></div>
<?= $Page->tarifa->Lookup->getParamTag($Page, "p_x_tarifa") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='tarifa_articulo_x_tarifa']"),
        options = { name: "x_tarifa", selectId: "tarifa_articulo_x_tarifa", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.tarifa_articulo.fields.tarifa.selectOptions);
    ew.createSelect(options);
});
</script>
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
        <label for="x_fabricante" class="ew-search-caption ew-label"><?= $Page->fabricante->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_fabricante" id="z_fabricante" value="=">
</span>
        <span id="el_tarifa_articulo_fabricante" class="ew-search-field">
<?php $Page->fabricante->EditAttrs->prepend("onchange", "ew.updateOptions.call(this);"); ?>
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x_fabricante"><?= EmptyValue(strval($Page->fabricante->AdvancedSearch->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->fabricante->AdvancedSearch->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->fabricante->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->fabricante->ReadOnly || $Page->fabricante->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x_fabricante',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->fabricante->getErrorMessage(false) ?></div>
<?= $Page->fabricante->Lookup->getParamTag($Page, "p_x_fabricante") ?>
<input type="hidden" is="selection-list" data-table="tarifa_articulo" data-field="x_fabricante" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->fabricante->displayValueSeparatorAttribute() ?>" name="x_fabricante" id="x_fabricante" value="<?= $Page->fabricante->AdvancedSearch->SearchValue ?>"<?= $Page->fabricante->editAttributes() ?>>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
<?php if ($Page->articulo->Visible) { // articulo ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_articulo" class="ew-cell form-group">
        <label for="x_articulo" class="ew-search-caption ew-label"><?= $Page->articulo->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_articulo" id="z_articulo" value="=">
</span>
        <span id="el_tarifa_articulo_articulo" class="ew-search-field">
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x_articulo"><?= EmptyValue(strval($Page->articulo->AdvancedSearch->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->articulo->AdvancedSearch->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->articulo->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->articulo->ReadOnly || $Page->articulo->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x_articulo',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->articulo->getErrorMessage(false) ?></div>
<?= $Page->articulo->Lookup->getParamTag($Page, "p_x_articulo") ?>
<input type="hidden" is="selection-list" data-table="tarifa_articulo" data-field="x_articulo" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->articulo->displayValueSeparatorAttribute() ?>" name="x_articulo" id="x_articulo" value="<?= $Page->articulo->AdvancedSearch->SearchValue ?>"<?= $Page->articulo->editAttributes() ?>>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> tarifa_articulo">
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
<form name="ftarifa_articulolist" id="ftarifa_articulolist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="tarifa_articulo">
<?php if ($Page->getCurrentMasterTable() == "tarifa" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="tarifa">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->tarifa->getSessionValue()) ?>">
<?php } ?>
<div id="gmp_tarifa_articulo" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_tarifa_articulolist" class="table ew-table"><!-- .ew-table -->
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
        <th data-name="tarifa" class="<?= $Page->tarifa->headerCellClass() ?>"><div id="elh_tarifa_articulo_tarifa" class="tarifa_articulo_tarifa"><?= $Page->renderSort($Page->tarifa) ?></div></th>
<?php } ?>
<?php if ($Page->fabricante->Visible) { // fabricante ?>
        <th data-name="fabricante" class="<?= $Page->fabricante->headerCellClass() ?>"><div id="elh_tarifa_articulo_fabricante" class="tarifa_articulo_fabricante"><?= $Page->renderSort($Page->fabricante) ?></div></th>
<?php } ?>
<?php if ($Page->articulo->Visible) { // articulo ?>
        <th data-name="articulo" class="<?= $Page->articulo->headerCellClass() ?>"><div id="elh_tarifa_articulo_articulo" class="tarifa_articulo_articulo"><?= $Page->renderSort($Page->articulo) ?></div></th>
<?php } ?>
<?php if ($Page->precio->Visible) { // precio ?>
        <th data-name="precio" class="<?= $Page->precio->headerCellClass() ?>"><div id="elh_tarifa_articulo_precio" class="tarifa_articulo_precio"><?= $Page->renderSort($Page->precio) ?></div></th>
<?php } ?>
<?php if ($Page->precio2->Visible) { // precio2 ?>
        <th data-name="precio2" class="<?= $Page->precio2->headerCellClass() ?>"><div id="elh_tarifa_articulo_precio2" class="tarifa_articulo_precio2"><?= $Page->renderSort($Page->precio2) ?></div></th>
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_tarifa_articulo", "data-rowtype" => $Page->RowType]);

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
    <?php if ($Page->tarifa->Visible) { // tarifa ?>
        <td data-name="tarifa" <?= $Page->tarifa->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if ($Page->tarifa->getSessionValue() != "") { ?>
<span id="el<?= $Page->RowCount ?>_tarifa_articulo_tarifa" class="form-group">
<span<?= $Page->tarifa->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->tarifa->getDisplayValue($Page->tarifa->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Page->RowIndex ?>_tarifa" name="x<?= $Page->RowIndex ?>_tarifa" value="<?= HtmlEncode($Page->tarifa->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Page->RowCount ?>_tarifa_articulo_tarifa" class="form-group">
    <select
        id="x<?= $Page->RowIndex ?>_tarifa"
        name="x<?= $Page->RowIndex ?>_tarifa"
        class="form-control ew-select<?= $Page->tarifa->isInvalidClass() ?>"
        data-select2-id="tarifa_articulo_x<?= $Page->RowIndex ?>_tarifa"
        data-table="tarifa_articulo"
        data-field="x_tarifa"
        data-value-separator="<?= $Page->tarifa->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->tarifa->getPlaceHolder()) ?>"
        <?= $Page->tarifa->editAttributes() ?>>
        <?= $Page->tarifa->selectOptionListHtml("x{$Page->RowIndex}_tarifa") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->tarifa->getErrorMessage() ?></div>
<?= $Page->tarifa->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_tarifa") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='tarifa_articulo_x<?= $Page->RowIndex ?>_tarifa']"),
        options = { name: "x<?= $Page->RowIndex ?>_tarifa", selectId: "tarifa_articulo_x<?= $Page->RowIndex ?>_tarifa", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.tarifa_articulo.fields.tarifa.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="tarifa_articulo" data-field="x_tarifa" data-hidden="1" name="o<?= $Page->RowIndex ?>_tarifa" id="o<?= $Page->RowIndex ?>_tarifa" value="<?= HtmlEncode($Page->tarifa->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Page->tarifa->getSessionValue() != "") { ?>
<span id="el<?= $Page->RowCount ?>_tarifa_articulo_tarifa" class="form-group">
<span<?= $Page->tarifa->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->tarifa->getDisplayValue($Page->tarifa->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Page->RowIndex ?>_tarifa" name="x<?= $Page->RowIndex ?>_tarifa" value="<?= HtmlEncode($Page->tarifa->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Page->RowCount ?>_tarifa_articulo_tarifa" class="form-group">
    <select
        id="x<?= $Page->RowIndex ?>_tarifa"
        name="x<?= $Page->RowIndex ?>_tarifa"
        class="form-control ew-select<?= $Page->tarifa->isInvalidClass() ?>"
        data-select2-id="tarifa_articulo_x<?= $Page->RowIndex ?>_tarifa"
        data-table="tarifa_articulo"
        data-field="x_tarifa"
        data-value-separator="<?= $Page->tarifa->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->tarifa->getPlaceHolder()) ?>"
        <?= $Page->tarifa->editAttributes() ?>>
        <?= $Page->tarifa->selectOptionListHtml("x{$Page->RowIndex}_tarifa") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->tarifa->getErrorMessage() ?></div>
<?= $Page->tarifa->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_tarifa") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='tarifa_articulo_x<?= $Page->RowIndex ?>_tarifa']"),
        options = { name: "x<?= $Page->RowIndex ?>_tarifa", selectId: "tarifa_articulo_x<?= $Page->RowIndex ?>_tarifa", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.tarifa_articulo.fields.tarifa.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_tarifa_articulo_tarifa">
<span<?= $Page->tarifa->viewAttributes() ?>>
<?= $Page->tarifa->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->fabricante->Visible) { // fabricante ?>
        <td data-name="fabricante" <?= $Page->fabricante->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_tarifa_articulo_fabricante" class="form-group">
<?php $Page->fabricante->EditAttrs->prepend("onchange", "ew.updateOptions.call(this);"); ?>
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Page->RowIndex ?>_fabricante"><?= EmptyValue(strval($Page->fabricante->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->fabricante->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->fabricante->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->fabricante->ReadOnly || $Page->fabricante->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Page->RowIndex ?>_fabricante',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->fabricante->getErrorMessage() ?></div>
<?= $Page->fabricante->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_fabricante") ?>
<input type="hidden" is="selection-list" data-table="tarifa_articulo" data-field="x_fabricante" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->fabricante->displayValueSeparatorAttribute() ?>" name="x<?= $Page->RowIndex ?>_fabricante" id="x<?= $Page->RowIndex ?>_fabricante" value="<?= $Page->fabricante->CurrentValue ?>"<?= $Page->fabricante->editAttributes() ?>>
</span>
<input type="hidden" data-table="tarifa_articulo" data-field="x_fabricante" data-hidden="1" name="o<?= $Page->RowIndex ?>_fabricante" id="o<?= $Page->RowIndex ?>_fabricante" value="<?= HtmlEncode($Page->fabricante->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_tarifa_articulo_fabricante" class="form-group">
<?php $Page->fabricante->EditAttrs->prepend("onchange", "ew.updateOptions.call(this);"); ?>
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Page->RowIndex ?>_fabricante"><?= EmptyValue(strval($Page->fabricante->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->fabricante->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->fabricante->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->fabricante->ReadOnly || $Page->fabricante->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Page->RowIndex ?>_fabricante',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->fabricante->getErrorMessage() ?></div>
<?= $Page->fabricante->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_fabricante") ?>
<input type="hidden" is="selection-list" data-table="tarifa_articulo" data-field="x_fabricante" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->fabricante->displayValueSeparatorAttribute() ?>" name="x<?= $Page->RowIndex ?>_fabricante" id="x<?= $Page->RowIndex ?>_fabricante" value="<?= $Page->fabricante->CurrentValue ?>"<?= $Page->fabricante->editAttributes() ?>>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_tarifa_articulo_fabricante">
<span<?= $Page->fabricante->viewAttributes() ?>>
<?= $Page->fabricante->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->articulo->Visible) { // articulo ?>
        <td data-name="articulo" <?= $Page->articulo->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_tarifa_articulo_articulo" class="form-group">
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Page->RowIndex ?>_articulo"><?= EmptyValue(strval($Page->articulo->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->articulo->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->articulo->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->articulo->ReadOnly || $Page->articulo->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Page->RowIndex ?>_articulo',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->articulo->getErrorMessage() ?></div>
<?= $Page->articulo->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_articulo") ?>
<input type="hidden" is="selection-list" data-table="tarifa_articulo" data-field="x_articulo" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->articulo->displayValueSeparatorAttribute() ?>" name="x<?= $Page->RowIndex ?>_articulo" id="x<?= $Page->RowIndex ?>_articulo" value="<?= $Page->articulo->CurrentValue ?>"<?= $Page->articulo->editAttributes() ?>>
</span>
<input type="hidden" data-table="tarifa_articulo" data-field="x_articulo" data-hidden="1" name="o<?= $Page->RowIndex ?>_articulo" id="o<?= $Page->RowIndex ?>_articulo" value="<?= HtmlEncode($Page->articulo->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_tarifa_articulo_articulo" class="form-group">
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Page->RowIndex ?>_articulo"><?= EmptyValue(strval($Page->articulo->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->articulo->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->articulo->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->articulo->ReadOnly || $Page->articulo->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Page->RowIndex ?>_articulo',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->articulo->getErrorMessage() ?></div>
<?= $Page->articulo->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_articulo") ?>
<input type="hidden" is="selection-list" data-table="tarifa_articulo" data-field="x_articulo" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->articulo->displayValueSeparatorAttribute() ?>" name="x<?= $Page->RowIndex ?>_articulo" id="x<?= $Page->RowIndex ?>_articulo" value="<?= $Page->articulo->CurrentValue ?>"<?= $Page->articulo->editAttributes() ?>>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_tarifa_articulo_articulo">
<span<?= $Page->articulo->viewAttributes() ?>>
<?= $Page->articulo->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->precio->Visible) { // precio ?>
        <td data-name="precio" <?= $Page->precio->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_tarifa_articulo_precio" class="form-group">
<input type="<?= $Page->precio->getInputTextType() ?>" data-table="tarifa_articulo" data-field="x_precio" name="x<?= $Page->RowIndex ?>_precio" id="x<?= $Page->RowIndex ?>_precio" size="10" maxlength="13" placeholder="<?= HtmlEncode($Page->precio->getPlaceHolder()) ?>" value="<?= $Page->precio->EditValue ?>"<?= $Page->precio->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->precio->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="tarifa_articulo" data-field="x_precio" data-hidden="1" name="o<?= $Page->RowIndex ?>_precio" id="o<?= $Page->RowIndex ?>_precio" value="<?= HtmlEncode($Page->precio->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_tarifa_articulo_precio" class="form-group">
<input type="<?= $Page->precio->getInputTextType() ?>" data-table="tarifa_articulo" data-field="x_precio" name="x<?= $Page->RowIndex ?>_precio" id="x<?= $Page->RowIndex ?>_precio" size="10" maxlength="13" placeholder="<?= HtmlEncode($Page->precio->getPlaceHolder()) ?>" value="<?= $Page->precio->EditValue ?>"<?= $Page->precio->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->precio->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_tarifa_articulo_precio">
<span<?= $Page->precio->viewAttributes() ?>>
<?= $Page->precio->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->precio2->Visible) { // precio2 ?>
        <td data-name="precio2" <?= $Page->precio2->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_tarifa_articulo_precio2" class="form-group">
<input type="<?= $Page->precio2->getInputTextType() ?>" data-table="tarifa_articulo" data-field="x_precio2" name="x<?= $Page->RowIndex ?>_precio2" id="x<?= $Page->RowIndex ?>_precio2" size="30" maxlength="13" placeholder="<?= HtmlEncode($Page->precio2->getPlaceHolder()) ?>" value="<?= $Page->precio2->EditValue ?>"<?= $Page->precio2->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->precio2->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="tarifa_articulo" data-field="x_precio2" data-hidden="1" name="o<?= $Page->RowIndex ?>_precio2" id="o<?= $Page->RowIndex ?>_precio2" value="<?= HtmlEncode($Page->precio2->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_tarifa_articulo_precio2" class="form-group">
<input type="<?= $Page->precio2->getInputTextType() ?>" data-table="tarifa_articulo" data-field="x_precio2" name="x<?= $Page->RowIndex ?>_precio2" id="x<?= $Page->RowIndex ?>_precio2" size="30" maxlength="13" placeholder="<?= HtmlEncode($Page->precio2->getPlaceHolder()) ?>" value="<?= $Page->precio2->EditValue ?>"<?= $Page->precio2->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->precio2->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_tarifa_articulo_precio2">
<span<?= $Page->precio2->viewAttributes() ?>>
<?= $Page->precio2->getViewValue() ?></span>
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
loadjs.ready(["ftarifa_articulolist","load"], function () {
    ftarifa_articulolist.updateLists(<?= $Page->RowIndex ?>);
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowIndex, "id" => "r0_tarifa_articulo", "data-rowtype" => ROWTYPE_ADD]);
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
    <?php if ($Page->tarifa->Visible) { // tarifa ?>
        <td data-name="tarifa">
<?php if ($Page->tarifa->getSessionValue() != "") { ?>
<span id="el$rowindex$_tarifa_articulo_tarifa" class="form-group tarifa_articulo_tarifa">
<span<?= $Page->tarifa->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->tarifa->getDisplayValue($Page->tarifa->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Page->RowIndex ?>_tarifa" name="x<?= $Page->RowIndex ?>_tarifa" value="<?= HtmlEncode($Page->tarifa->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el$rowindex$_tarifa_articulo_tarifa" class="form-group tarifa_articulo_tarifa">
    <select
        id="x<?= $Page->RowIndex ?>_tarifa"
        name="x<?= $Page->RowIndex ?>_tarifa"
        class="form-control ew-select<?= $Page->tarifa->isInvalidClass() ?>"
        data-select2-id="tarifa_articulo_x<?= $Page->RowIndex ?>_tarifa"
        data-table="tarifa_articulo"
        data-field="x_tarifa"
        data-value-separator="<?= $Page->tarifa->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->tarifa->getPlaceHolder()) ?>"
        <?= $Page->tarifa->editAttributes() ?>>
        <?= $Page->tarifa->selectOptionListHtml("x{$Page->RowIndex}_tarifa") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->tarifa->getErrorMessage() ?></div>
<?= $Page->tarifa->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_tarifa") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='tarifa_articulo_x<?= $Page->RowIndex ?>_tarifa']"),
        options = { name: "x<?= $Page->RowIndex ?>_tarifa", selectId: "tarifa_articulo_x<?= $Page->RowIndex ?>_tarifa", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.tarifa_articulo.fields.tarifa.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="tarifa_articulo" data-field="x_tarifa" data-hidden="1" name="o<?= $Page->RowIndex ?>_tarifa" id="o<?= $Page->RowIndex ?>_tarifa" value="<?= HtmlEncode($Page->tarifa->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->fabricante->Visible) { // fabricante ?>
        <td data-name="fabricante">
<span id="el$rowindex$_tarifa_articulo_fabricante" class="form-group tarifa_articulo_fabricante">
<?php $Page->fabricante->EditAttrs->prepend("onchange", "ew.updateOptions.call(this);"); ?>
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Page->RowIndex ?>_fabricante"><?= EmptyValue(strval($Page->fabricante->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->fabricante->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->fabricante->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->fabricante->ReadOnly || $Page->fabricante->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Page->RowIndex ?>_fabricante',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->fabricante->getErrorMessage() ?></div>
<?= $Page->fabricante->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_fabricante") ?>
<input type="hidden" is="selection-list" data-table="tarifa_articulo" data-field="x_fabricante" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->fabricante->displayValueSeparatorAttribute() ?>" name="x<?= $Page->RowIndex ?>_fabricante" id="x<?= $Page->RowIndex ?>_fabricante" value="<?= $Page->fabricante->CurrentValue ?>"<?= $Page->fabricante->editAttributes() ?>>
</span>
<input type="hidden" data-table="tarifa_articulo" data-field="x_fabricante" data-hidden="1" name="o<?= $Page->RowIndex ?>_fabricante" id="o<?= $Page->RowIndex ?>_fabricante" value="<?= HtmlEncode($Page->fabricante->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->articulo->Visible) { // articulo ?>
        <td data-name="articulo">
<span id="el$rowindex$_tarifa_articulo_articulo" class="form-group tarifa_articulo_articulo">
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Page->RowIndex ?>_articulo"><?= EmptyValue(strval($Page->articulo->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->articulo->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->articulo->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->articulo->ReadOnly || $Page->articulo->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Page->RowIndex ?>_articulo',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->articulo->getErrorMessage() ?></div>
<?= $Page->articulo->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_articulo") ?>
<input type="hidden" is="selection-list" data-table="tarifa_articulo" data-field="x_articulo" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->articulo->displayValueSeparatorAttribute() ?>" name="x<?= $Page->RowIndex ?>_articulo" id="x<?= $Page->RowIndex ?>_articulo" value="<?= $Page->articulo->CurrentValue ?>"<?= $Page->articulo->editAttributes() ?>>
</span>
<input type="hidden" data-table="tarifa_articulo" data-field="x_articulo" data-hidden="1" name="o<?= $Page->RowIndex ?>_articulo" id="o<?= $Page->RowIndex ?>_articulo" value="<?= HtmlEncode($Page->articulo->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->precio->Visible) { // precio ?>
        <td data-name="precio">
<span id="el$rowindex$_tarifa_articulo_precio" class="form-group tarifa_articulo_precio">
<input type="<?= $Page->precio->getInputTextType() ?>" data-table="tarifa_articulo" data-field="x_precio" name="x<?= $Page->RowIndex ?>_precio" id="x<?= $Page->RowIndex ?>_precio" size="10" maxlength="13" placeholder="<?= HtmlEncode($Page->precio->getPlaceHolder()) ?>" value="<?= $Page->precio->EditValue ?>"<?= $Page->precio->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->precio->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="tarifa_articulo" data-field="x_precio" data-hidden="1" name="o<?= $Page->RowIndex ?>_precio" id="o<?= $Page->RowIndex ?>_precio" value="<?= HtmlEncode($Page->precio->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->precio2->Visible) { // precio2 ?>
        <td data-name="precio2">
<span id="el$rowindex$_tarifa_articulo_precio2" class="form-group tarifa_articulo_precio2">
<input type="<?= $Page->precio2->getInputTextType() ?>" data-table="tarifa_articulo" data-field="x_precio2" name="x<?= $Page->RowIndex ?>_precio2" id="x<?= $Page->RowIndex ?>_precio2" size="30" maxlength="13" placeholder="<?= HtmlEncode($Page->precio2->getPlaceHolder()) ?>" value="<?= $Page->precio2->EditValue ?>"<?= $Page->precio2->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->precio2->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="tarifa_articulo" data-field="x_precio2" data-hidden="1" name="o<?= $Page->RowIndex ?>_precio2" id="o<?= $Page->RowIndex ?>_precio2" value="<?= HtmlEncode($Page->precio2->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowIndex);
?>
<script>
loadjs.ready(["ftarifa_articulolist","load"], function() {
    ftarifa_articulolist.updateLists(<?= $Page->RowIndex ?>);
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
    ew.addEventHandlers("tarifa_articulo");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
