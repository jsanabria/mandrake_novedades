<?php

namespace PHPMaker2021\mandrake;

// Page object
$ContAsientoList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fcont_asientolist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    fcont_asientolist = currentForm = new ew.Form("fcont_asientolist", "list");
    fcont_asientolist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "cont_asiento")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.cont_asiento)
        ew.vars.tables.cont_asiento = currentTable;
    fcont_asientolist.addFields([
        ["cuenta", [fields.cuenta.visible && fields.cuenta.required ? ew.Validators.required(fields.cuenta.caption) : null], fields.cuenta.isInvalid],
        ["nota", [fields.nota.visible && fields.nota.required ? ew.Validators.required(fields.nota.caption) : null], fields.nota.isInvalid],
        ["referencia", [fields.referencia.visible && fields.referencia.required ? ew.Validators.required(fields.referencia.caption) : null], fields.referencia.isInvalid],
        ["debe", [fields.debe.visible && fields.debe.required ? ew.Validators.required(fields.debe.caption) : null, ew.Validators.float], fields.debe.isInvalid],
        ["haber", [fields.haber.visible && fields.haber.required ? ew.Validators.required(fields.haber.caption) : null, ew.Validators.float], fields.haber.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fcont_asientolist,
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
    fcont_asientolist.validate = function () {
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
    fcont_asientolist.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fcont_asientolist.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fcont_asientolist.lists.cuenta = <?= $Page->cuenta->toClientList($Page) ?>;
    loadjs.done("fcont_asientolist");
});
var fcont_asientolistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    fcont_asientolistsrch = currentSearchForm = new ew.Form("fcont_asientolistsrch");

    // Dynamic selection lists

    // Filters
    fcont_asientolistsrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    fcont_asientolistsrch.initSearchPanel = true;
    loadjs.done("fcont_asientolistsrch");
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
if ($Page->DbMasterFilter != "" && $Page->getCurrentMasterTable() == "cont_comprobante") {
    if ($Page->MasterRecordExists) {
        include_once "views/ContComprobanteMaster.php";
    }
}
?>
<?php } ?>
<?php
$Page->renderOtherOptions();
?>
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !$Page->CurrentAction) { ?>
<form name="fcont_asientolistsrch" id="fcont_asientolistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fcont_asientolistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="cont_asiento">
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> cont_asiento">
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
<form name="fcont_asientolist" id="fcont_asientolist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cont_asiento">
<?php if ($Page->getCurrentMasterTable() == "cont_comprobante" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="cont_comprobante">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->comprobante->getSessionValue()) ?>">
<?php } ?>
<div id="gmp_cont_asiento" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isAdd() || $Page->isCopy() || $Page->isGridEdit()) { ?>
<table id="tbl_cont_asientolist" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Page->cuenta->Visible) { // cuenta ?>
        <th data-name="cuenta" class="<?= $Page->cuenta->headerCellClass() ?>"><div id="elh_cont_asiento_cuenta" class="cont_asiento_cuenta"><?= $Page->renderSort($Page->cuenta) ?></div></th>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
        <th data-name="nota" class="<?= $Page->nota->headerCellClass() ?>"><div id="elh_cont_asiento_nota" class="cont_asiento_nota"><?= $Page->renderSort($Page->nota) ?></div></th>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
        <th data-name="referencia" class="<?= $Page->referencia->headerCellClass() ?>"><div id="elh_cont_asiento_referencia" class="cont_asiento_referencia"><?= $Page->renderSort($Page->referencia) ?></div></th>
<?php } ?>
<?php if ($Page->debe->Visible) { // debe ?>
        <th data-name="debe" class="<?= $Page->debe->headerCellClass() ?>"><div id="elh_cont_asiento_debe" class="cont_asiento_debe"><?= $Page->renderSort($Page->debe) ?></div></th>
<?php } ?>
<?php if ($Page->haber->Visible) { // haber ?>
        <th data-name="haber" class="<?= $Page->haber->headerCellClass() ?>"><div id="elh_cont_asiento_haber" class="cont_asiento_haber"><?= $Page->renderSort($Page->haber) ?></div></th>
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
        $Page->RowAttrs->merge(["data-rowindex" => 0, "id" => "r0_cont_asiento", "data-rowtype" => ROWTYPE_ADD]);
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
    <?php if ($Page->cuenta->Visible) { // cuenta ?>
        <td data-name="cuenta">
<span id="el<?= $Page->RowCount ?>_cont_asiento_cuenta" class="form-group cont_asiento_cuenta">
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x<?= $Page->RowIndex ?>_cuenta"><?= EmptyValue(strval($Page->cuenta->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->cuenta->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->cuenta->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->cuenta->ReadOnly || $Page->cuenta->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x<?= $Page->RowIndex ?>_cuenta',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->cuenta->getErrorMessage() ?></div>
<?= $Page->cuenta->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_cuenta") ?>
<input type="hidden" is="selection-list" data-table="cont_asiento" data-field="x_cuenta" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->cuenta->displayValueSeparatorAttribute() ?>" name="x<?= $Page->RowIndex ?>_cuenta" id="x<?= $Page->RowIndex ?>_cuenta" value="<?= $Page->cuenta->CurrentValue ?>"<?= $Page->cuenta->editAttributes() ?>>
</span>
<input type="hidden" data-table="cont_asiento" data-field="x_cuenta" data-hidden="1" name="o<?= $Page->RowIndex ?>_cuenta" id="o<?= $Page->RowIndex ?>_cuenta" value="<?= HtmlEncode($Page->cuenta->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->nota->Visible) { // nota ?>
        <td data-name="nota">
<span id="el<?= $Page->RowCount ?>_cont_asiento_nota" class="form-group cont_asiento_nota">
<input type="<?= $Page->nota->getInputTextType() ?>" data-table="cont_asiento" data-field="x_nota" name="x<?= $Page->RowIndex ?>_nota" id="x<?= $Page->RowIndex ?>_nota" size="10" placeholder="<?= HtmlEncode($Page->nota->getPlaceHolder()) ?>" value="<?= $Page->nota->EditValue ?>"<?= $Page->nota->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->nota->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cont_asiento" data-field="x_nota" data-hidden="1" name="o<?= $Page->RowIndex ?>_nota" id="o<?= $Page->RowIndex ?>_nota" value="<?= HtmlEncode($Page->nota->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->referencia->Visible) { // referencia ?>
        <td data-name="referencia">
<span id="el<?= $Page->RowCount ?>_cont_asiento_referencia" class="form-group cont_asiento_referencia">
<input type="<?= $Page->referencia->getInputTextType() ?>" data-table="cont_asiento" data-field="x_referencia" name="x<?= $Page->RowIndex ?>_referencia" id="x<?= $Page->RowIndex ?>_referencia" size="10" maxlength="25" placeholder="<?= HtmlEncode($Page->referencia->getPlaceHolder()) ?>" value="<?= $Page->referencia->EditValue ?>"<?= $Page->referencia->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->referencia->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cont_asiento" data-field="x_referencia" data-hidden="1" name="o<?= $Page->RowIndex ?>_referencia" id="o<?= $Page->RowIndex ?>_referencia" value="<?= HtmlEncode($Page->referencia->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->debe->Visible) { // debe ?>
        <td data-name="debe">
<span id="el<?= $Page->RowCount ?>_cont_asiento_debe" class="form-group cont_asiento_debe">
<input type="<?= $Page->debe->getInputTextType() ?>" data-table="cont_asiento" data-field="x_debe" name="x<?= $Page->RowIndex ?>_debe" id="x<?= $Page->RowIndex ?>_debe" size="12" maxlength="16" placeholder="<?= HtmlEncode($Page->debe->getPlaceHolder()) ?>" value="<?= $Page->debe->EditValue ?>"<?= $Page->debe->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->debe->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cont_asiento" data-field="x_debe" data-hidden="1" name="o<?= $Page->RowIndex ?>_debe" id="o<?= $Page->RowIndex ?>_debe" value="<?= HtmlEncode($Page->debe->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->haber->Visible) { // haber ?>
        <td data-name="haber">
<span id="el<?= $Page->RowCount ?>_cont_asiento_haber" class="form-group cont_asiento_haber">
<input type="<?= $Page->haber->getInputTextType() ?>" data-table="cont_asiento" data-field="x_haber" name="x<?= $Page->RowIndex ?>_haber" id="x<?= $Page->RowIndex ?>_haber" size="12" maxlength="16" placeholder="<?= HtmlEncode($Page->haber->getPlaceHolder()) ?>" value="<?= $Page->haber->EditValue ?>"<?= $Page->haber->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->haber->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cont_asiento" data-field="x_haber" data-hidden="1" name="o<?= $Page->RowIndex ?>_haber" id="o<?= $Page->RowIndex ?>_haber" value="<?= HtmlEncode($Page->haber->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowCount);
?>
<script>
loadjs.ready(["fcont_asientolist","load"], function() {
    fcont_asientolist.updateLists(<?= $Page->RowIndex ?>);
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_cont_asiento", "data-rowtype" => $Page->RowType]);

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
    <?php if ($Page->cuenta->Visible) { // cuenta ?>
        <td data-name="cuenta" <?= $Page->cuenta->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_asiento_cuenta">
<span<?= $Page->cuenta->viewAttributes() ?>>
<?= $Page->cuenta->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->nota->Visible) { // nota ?>
        <td data-name="nota" <?= $Page->nota->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_asiento_nota">
<span<?= $Page->nota->viewAttributes() ?>>
<?= $Page->nota->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->referencia->Visible) { // referencia ?>
        <td data-name="referencia" <?= $Page->referencia->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_asiento_referencia">
<span<?= $Page->referencia->viewAttributes() ?>>
<?= $Page->referencia->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->debe->Visible) { // debe ?>
        <td data-name="debe" <?= $Page->debe->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_asiento_debe">
<span<?= $Page->debe->viewAttributes() ?>>
<?= $Page->debe->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->haber->Visible) { // haber ?>
        <td data-name="haber" <?= $Page->haber->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_asiento_haber">
<span<?= $Page->haber->viewAttributes() ?>>
<?= $Page->haber->getViewValue() ?></span>
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
<?php if ($Page->isAdd() || $Page->isCopy()) { ?>
<input type="hidden" name="<?= $Page->FormKeyCountName ?>" id="<?= $Page->FormKeyCountName ?>" value="<?= $Page->KeyCount ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
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
    ew.addEventHandlers("cont_asiento");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    // Write your table-specific startup script here
    // document.write("page loaded");
    $(document).ready(function() { 
    	var ButtonGroup = $('.ewButtonGroup'); 
    	ButtonGroup.hide(); 
    });
    $("#cmbContab").click(function(){
    	var id = <?php echo $_REQUEST["fk_id"]; ?>;
    	var username = "<?php echo CurrentUserName(); ?>";
    	if(confirm("Seguro de contabilizar este comprobante?")) {
    		$.ajax({
    		  url : "include/Contabilizar_Procesar.php",
    		  type: "GET",
    		  data : {id: id, username: username},
    		  beforeSend: function(){
    		    $("#result").html("Por Favor Espere. . .");
    		  }
    		})
    		.done(function(data) {
    			//alert(data);
    			var rs = '<div class="alert alert-success" role="alert">Este Comprobante se Contabiliz&oacute; Exitosamente.</div>';
    			$("#result").html(rs);
    		})
    		.fail(function(data) {
    			alert( "error" + data );
    		})
    		.always(function(data) {
    			//alert( "complete" );
    			//$("#result").html("Espere. . . ");
    		});
    	}
    });
});
</script>
<?php } ?>
