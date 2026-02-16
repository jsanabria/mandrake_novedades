<?php

namespace PHPMaker2021\mandrake;

// Page object
$AudittrailList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var faudittraillist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    faudittraillist = currentForm = new ew.Form("faudittraillist", "list");
    faudittraillist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';
    loadjs.done("faudittraillist");
});
var faudittraillistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    faudittraillistsrch = currentSearchForm = new ew.Form("faudittraillistsrch");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "audittrail")) ?>,
        fields = currentTable.fields;
    faudittraillistsrch.addFields([
        ["id", [], fields.id.isInvalid],
        ["datetime", [ew.Validators.datetime(11)], fields.datetime.isInvalid],
        ["y_datetime", [ew.Validators.between], false],
        ["script", [], fields.script.isInvalid],
        ["user", [], fields.user.isInvalid],
        ["_action", [], fields._action.isInvalid],
        ["_table", [], fields._table.isInvalid],
        ["field", [], fields.field.isInvalid],
        ["keyvalue", [], fields.keyvalue.isInvalid],
        ["oldvalue", [], fields.oldvalue.isInvalid],
        ["newvalue", [], fields.newvalue.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        faudittraillistsrch.setInvalid();
    });

    // Validate form
    faudittraillistsrch.validate = function () {
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
    faudittraillistsrch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    faudittraillistsrch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists

    // Filters
    faudittraillistsrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    faudittraillistsrch.initSearchPanel = true;
    loadjs.done("faudittraillistsrch");
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
<form name="faudittraillistsrch" id="faudittraillistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="faudittraillistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="audittrail">
    <div class="ew-extended-search">
<?php
// Render search row
$Page->RowType = ROWTYPE_SEARCH;
$Page->resetAttributes();
$Page->renderRow();
?>
<?php if ($Page->datetime->Visible) { // datetime ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_datetime" class="ew-cell form-group">
        <label for="x_datetime" class="ew-search-caption ew-label"><?= $Page->datetime->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("BETWEEN") ?>
<input type="hidden" name="z_datetime" id="z_datetime" value="BETWEEN">
</span>
        <span id="el_audittrail_datetime" class="ew-search-field">
<input type="<?= $Page->datetime->getInputTextType() ?>" data-table="audittrail" data-field="x_datetime" data-format="11" name="x_datetime" id="x_datetime" placeholder="<?= HtmlEncode($Page->datetime->getPlaceHolder()) ?>" value="<?= $Page->datetime->EditValue ?>"<?= $Page->datetime->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->datetime->getErrorMessage(false) ?></div>
<?php if (!$Page->datetime->ReadOnly && !$Page->datetime->Disabled && !isset($Page->datetime->EditAttrs["readonly"]) && !isset($Page->datetime->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["faudittraillistsrch", "datetimepicker"], function() {
    ew.createDateTimePicker("faudittraillistsrch", "x_datetime", {"ignoreReadonly":true,"useCurrent":false,"format":11});
});
</script>
<?php } ?>
</span>
        <span class="ew-search-and"><label><?= $Language->phrase("AND") ?></label></span>
        <span id="el2_audittrail_datetime" class="ew-search-field2">
<input type="<?= $Page->datetime->getInputTextType() ?>" data-table="audittrail" data-field="x_datetime" data-format="11" name="y_datetime" id="y_datetime" placeholder="<?= HtmlEncode($Page->datetime->getPlaceHolder()) ?>" value="<?= $Page->datetime->EditValue2 ?>"<?= $Page->datetime->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->datetime->getErrorMessage(false) ?></div>
<?php if (!$Page->datetime->ReadOnly && !$Page->datetime->Disabled && !isset($Page->datetime->EditAttrs["readonly"]) && !isset($Page->datetime->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["faudittraillistsrch", "datetimepicker"], function() {
    ew.createDateTimePicker("faudittraillistsrch", "y_datetime", {"ignoreReadonly":true,"useCurrent":false,"format":11});
});
</script>
<?php } ?>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
<?php if ($Page->script->Visible) { // script ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_script" class="ew-cell form-group">
        <label for="x_script" class="ew-search-caption ew-label"><?= $Page->script->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_script" id="z_script" value="LIKE">
</span>
        <span id="el_audittrail_script" class="ew-search-field">
<input type="<?= $Page->script->getInputTextType() ?>" data-table="audittrail" data-field="x_script" name="x_script" id="x_script" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->script->getPlaceHolder()) ?>" value="<?= $Page->script->EditValue ?>"<?= $Page->script->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->script->getErrorMessage(false) ?></div>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
<?php if ($Page->user->Visible) { // user ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_user" class="ew-cell form-group">
        <label for="x_user" class="ew-search-caption ew-label"><?= $Page->user->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_user" id="z_user" value="LIKE">
</span>
        <span id="el_audittrail_user" class="ew-search-field">
<input type="<?= $Page->user->getInputTextType() ?>" data-table="audittrail" data-field="x_user" name="x_user" id="x_user" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->user->getPlaceHolder()) ?>" value="<?= $Page->user->EditValue ?>"<?= $Page->user->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->user->getErrorMessage(false) ?></div>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
<?php if ($Page->_action->Visible) { // action ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc__action" class="ew-cell form-group">
        <label for="x__action" class="ew-search-caption ew-label"><?= $Page->_action->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z__action" id="z__action" value="LIKE">
</span>
        <span id="el_audittrail__action" class="ew-search-field">
<input type="<?= $Page->_action->getInputTextType() ?>" data-table="audittrail" data-field="x__action" name="x__action" id="x__action" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->_action->getPlaceHolder()) ?>" value="<?= $Page->_action->EditValue ?>"<?= $Page->_action->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->_action->getErrorMessage(false) ?></div>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
<?php if ($Page->_table->Visible) { // table ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc__table" class="ew-cell form-group">
        <label for="x__table" class="ew-search-caption ew-label"><?= $Page->_table->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z__table" id="z__table" value="LIKE">
</span>
        <span id="el_audittrail__table" class="ew-search-field">
<input type="<?= $Page->_table->getInputTextType() ?>" data-table="audittrail" data-field="x__table" name="x__table" id="x__table" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->_table->getPlaceHolder()) ?>" value="<?= $Page->_table->EditValue ?>"<?= $Page->_table->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->_table->getErrorMessage(false) ?></div>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
<?php if ($Page->keyvalue->Visible) { // keyvalue ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_keyvalue" class="ew-cell form-group">
        <label for="x_keyvalue" class="ew-search-caption ew-label"><?= $Page->keyvalue->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_keyvalue" id="z_keyvalue" value="LIKE">
</span>
        <span id="el_audittrail_keyvalue" class="ew-search-field">
<input type="<?= $Page->keyvalue->getInputTextType() ?>" data-table="audittrail" data-field="x_keyvalue" name="x_keyvalue" id="x_keyvalue" size="35" placeholder="<?= HtmlEncode($Page->keyvalue->getPlaceHolder()) ?>" value="<?= $Page->keyvalue->EditValue ?>"<?= $Page->keyvalue->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->keyvalue->getErrorMessage(false) ?></div>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
<?php if ($Page->oldvalue->Visible) { // oldvalue ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_oldvalue" class="ew-cell form-group">
        <label for="x_oldvalue" class="ew-search-caption ew-label"><?= $Page->oldvalue->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_oldvalue" id="z_oldvalue" value="LIKE">
</span>
        <span id="el_audittrail_oldvalue" class="ew-search-field">
<input type="<?= $Page->oldvalue->getInputTextType() ?>" data-table="audittrail" data-field="x_oldvalue" name="x_oldvalue" id="x_oldvalue" size="35" placeholder="<?= HtmlEncode($Page->oldvalue->getPlaceHolder()) ?>" value="<?= $Page->oldvalue->EditValue ?>"<?= $Page->oldvalue->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->oldvalue->getErrorMessage(false) ?></div>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
<?php if ($Page->newvalue->Visible) { // newvalue ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_newvalue" class="ew-cell form-group">
        <label for="x_newvalue" class="ew-search-caption ew-label"><?= $Page->newvalue->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_newvalue" id="z_newvalue" value="LIKE">
</span>
        <span id="el_audittrail_newvalue" class="ew-search-field">
<input type="<?= $Page->newvalue->getInputTextType() ?>" data-table="audittrail" data-field="x_newvalue" name="x_newvalue" id="x_newvalue" size="35" placeholder="<?= HtmlEncode($Page->newvalue->getPlaceHolder()) ?>" value="<?= $Page->newvalue->EditValue ?>"<?= $Page->newvalue->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->newvalue->getErrorMessage(false) ?></div>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> audittrail">
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
<form name="faudittraillist" id="faudittraillist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="audittrail">
<div id="gmp_audittrail" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_audittraillist" class="table ew-table"><!-- .ew-table -->
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
        <th data-name="id" class="<?= $Page->id->headerCellClass() ?>"><div id="elh_audittrail_id" class="audittrail_id"><?= $Page->renderSort($Page->id) ?></div></th>
<?php } ?>
<?php if ($Page->datetime->Visible) { // datetime ?>
        <th data-name="datetime" class="<?= $Page->datetime->headerCellClass() ?>"><div id="elh_audittrail_datetime" class="audittrail_datetime"><?= $Page->renderSort($Page->datetime) ?></div></th>
<?php } ?>
<?php if ($Page->script->Visible) { // script ?>
        <th data-name="script" class="<?= $Page->script->headerCellClass() ?>"><div id="elh_audittrail_script" class="audittrail_script"><?= $Page->renderSort($Page->script) ?></div></th>
<?php } ?>
<?php if ($Page->user->Visible) { // user ?>
        <th data-name="user" class="<?= $Page->user->headerCellClass() ?>"><div id="elh_audittrail_user" class="audittrail_user"><?= $Page->renderSort($Page->user) ?></div></th>
<?php } ?>
<?php if ($Page->_action->Visible) { // action ?>
        <th data-name="_action" class="<?= $Page->_action->headerCellClass() ?>"><div id="elh_audittrail__action" class="audittrail__action"><?= $Page->renderSort($Page->_action) ?></div></th>
<?php } ?>
<?php if ($Page->_table->Visible) { // table ?>
        <th data-name="_table" class="<?= $Page->_table->headerCellClass() ?>"><div id="elh_audittrail__table" class="audittrail__table"><?= $Page->renderSort($Page->_table) ?></div></th>
<?php } ?>
<?php if ($Page->field->Visible) { // field ?>
        <th data-name="field" class="<?= $Page->field->headerCellClass() ?>"><div id="elh_audittrail_field" class="audittrail_field"><?= $Page->renderSort($Page->field) ?></div></th>
<?php } ?>
<?php if ($Page->keyvalue->Visible) { // keyvalue ?>
        <th data-name="keyvalue" class="<?= $Page->keyvalue->headerCellClass() ?>"><div id="elh_audittrail_keyvalue" class="audittrail_keyvalue"><?= $Page->renderSort($Page->keyvalue) ?></div></th>
<?php } ?>
<?php if ($Page->oldvalue->Visible) { // oldvalue ?>
        <th data-name="oldvalue" class="<?= $Page->oldvalue->headerCellClass() ?>"><div id="elh_audittrail_oldvalue" class="audittrail_oldvalue"><?= $Page->renderSort($Page->oldvalue) ?></div></th>
<?php } ?>
<?php if ($Page->newvalue->Visible) { // newvalue ?>
        <th data-name="newvalue" class="<?= $Page->newvalue->headerCellClass() ?>"><div id="elh_audittrail_newvalue" class="audittrail_newvalue"><?= $Page->renderSort($Page->newvalue) ?></div></th>
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_audittrail", "data-rowtype" => $Page->RowType]);

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
<span id="el<?= $Page->RowCount ?>_audittrail_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->datetime->Visible) { // datetime ?>
        <td data-name="datetime" <?= $Page->datetime->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_audittrail_datetime">
<span<?= $Page->datetime->viewAttributes() ?>>
<?= $Page->datetime->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->script->Visible) { // script ?>
        <td data-name="script" <?= $Page->script->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_audittrail_script">
<span<?= $Page->script->viewAttributes() ?>>
<?= $Page->script->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->user->Visible) { // user ?>
        <td data-name="user" <?= $Page->user->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_audittrail_user">
<span<?= $Page->user->viewAttributes() ?>>
<?= $Page->user->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->_action->Visible) { // action ?>
        <td data-name="_action" <?= $Page->_action->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_audittrail__action">
<span<?= $Page->_action->viewAttributes() ?>>
<?= $Page->_action->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->_table->Visible) { // table ?>
        <td data-name="_table" <?= $Page->_table->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_audittrail__table">
<span<?= $Page->_table->viewAttributes() ?>>
<?= $Page->_table->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->field->Visible) { // field ?>
        <td data-name="field" <?= $Page->field->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_audittrail_field">
<span<?= $Page->field->viewAttributes() ?>>
<?= $Page->field->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->keyvalue->Visible) { // keyvalue ?>
        <td data-name="keyvalue" <?= $Page->keyvalue->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_audittrail_keyvalue">
<span<?= $Page->keyvalue->viewAttributes() ?>>
<?= $Page->keyvalue->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->oldvalue->Visible) { // oldvalue ?>
        <td data-name="oldvalue" <?= $Page->oldvalue->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_audittrail_oldvalue">
<span<?= $Page->oldvalue->viewAttributes() ?>>
<?= $Page->oldvalue->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->newvalue->Visible) { // newvalue ?>
        <td data-name="newvalue" <?= $Page->newvalue->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_audittrail_newvalue">
<span<?= $Page->newvalue->viewAttributes() ?>>
<?= $Page->newvalue->getViewValue() ?></span>
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
    ew.addEventHandlers("audittrail");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
