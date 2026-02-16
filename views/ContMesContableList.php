<?php

namespace PHPMaker2021\mandrake;

// Page object
$ContMesContableList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fcont_mes_contablelist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    fcont_mes_contablelist = currentForm = new ew.Form("fcont_mes_contablelist", "list");
    fcont_mes_contablelist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';
    loadjs.done("fcont_mes_contablelist");
});
var fcont_mes_contablelistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    fcont_mes_contablelistsrch = currentSearchForm = new ew.Form("fcont_mes_contablelistsrch");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "cont_mes_contable")) ?>,
        fields = currentTable.fields;
    fcont_mes_contablelistsrch.addFields([
        ["descripcion", [], fields.descripcion.isInvalid],
        ["M01", [], fields.M01.isInvalid],
        ["M02", [], fields.M02.isInvalid],
        ["M03", [], fields.M03.isInvalid],
        ["M04", [], fields.M04.isInvalid],
        ["M05", [], fields.M05.isInvalid],
        ["M06", [], fields.M06.isInvalid],
        ["M07", [], fields.M07.isInvalid],
        ["M08", [], fields.M08.isInvalid],
        ["M09", [], fields.M09.isInvalid],
        ["M10", [], fields.M10.isInvalid],
        ["M11", [], fields.M11.isInvalid],
        ["M12", [], fields.M12.isInvalid],
        ["activo", [], fields.activo.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        fcont_mes_contablelistsrch.setInvalid();
    });

    // Validate form
    fcont_mes_contablelistsrch.validate = function () {
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
    fcont_mes_contablelistsrch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fcont_mes_contablelistsrch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fcont_mes_contablelistsrch.lists.M01 = <?= $Page->M01->toClientList($Page) ?>;
    fcont_mes_contablelistsrch.lists.M02 = <?= $Page->M02->toClientList($Page) ?>;
    fcont_mes_contablelistsrch.lists.M03 = <?= $Page->M03->toClientList($Page) ?>;
    fcont_mes_contablelistsrch.lists.M04 = <?= $Page->M04->toClientList($Page) ?>;
    fcont_mes_contablelistsrch.lists.M05 = <?= $Page->M05->toClientList($Page) ?>;
    fcont_mes_contablelistsrch.lists.M06 = <?= $Page->M06->toClientList($Page) ?>;
    fcont_mes_contablelistsrch.lists.M07 = <?= $Page->M07->toClientList($Page) ?>;
    fcont_mes_contablelistsrch.lists.M08 = <?= $Page->M08->toClientList($Page) ?>;
    fcont_mes_contablelistsrch.lists.M09 = <?= $Page->M09->toClientList($Page) ?>;
    fcont_mes_contablelistsrch.lists.M10 = <?= $Page->M10->toClientList($Page) ?>;
    fcont_mes_contablelistsrch.lists.M11 = <?= $Page->M11->toClientList($Page) ?>;
    fcont_mes_contablelistsrch.lists.M12 = <?= $Page->M12->toClientList($Page) ?>;
    fcont_mes_contablelistsrch.lists.activo = <?= $Page->activo->toClientList($Page) ?>;

    // Filters
    fcont_mes_contablelistsrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    fcont_mes_contablelistsrch.initSearchPanel = true;
    loadjs.done("fcont_mes_contablelistsrch");
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
<form name="fcont_mes_contablelistsrch" id="fcont_mes_contablelistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fcont_mes_contablelistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="cont_mes_contable">
    <div class="ew-extended-search">
<?php
// Render search row
$Page->RowType = ROWTYPE_SEARCH;
$Page->resetAttributes();
$Page->renderRow();
?>
<?php if ($Page->M01->Visible) { // M01 ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_M01" class="ew-cell form-group">
        <label class="ew-search-caption ew-label"><?= $Page->M01->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_M01" id="z_M01" value="=">
</span>
        <span id="el_cont_mes_contable_M01" class="ew-search-field">
<template id="tp_x_M01">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="cont_mes_contable" data-field="x_M01" name="x_M01" id="x_M01"<?= $Page->M01->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x_M01" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x_M01"
    name="x_M01"
    value="<?= HtmlEncode($Page->M01->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_M01"
    data-target="dsl_x_M01"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M01->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_M01"
    data-value-separator="<?= $Page->M01->displayValueSeparatorAttribute() ?>"
    <?= $Page->M01->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->M01->getErrorMessage(false) ?></div>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
<?php if ($Page->M02->Visible) { // M02 ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_M02" class="ew-cell form-group">
        <label class="ew-search-caption ew-label"><?= $Page->M02->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_M02" id="z_M02" value="=">
</span>
        <span id="el_cont_mes_contable_M02" class="ew-search-field">
<template id="tp_x_M02">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="cont_mes_contable" data-field="x_M02" name="x_M02" id="x_M02"<?= $Page->M02->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x_M02" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x_M02"
    name="x_M02"
    value="<?= HtmlEncode($Page->M02->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_M02"
    data-target="dsl_x_M02"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M02->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_M02"
    data-value-separator="<?= $Page->M02->displayValueSeparatorAttribute() ?>"
    <?= $Page->M02->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->M02->getErrorMessage(false) ?></div>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
<?php if ($Page->M03->Visible) { // M03 ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_M03" class="ew-cell form-group">
        <label class="ew-search-caption ew-label"><?= $Page->M03->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_M03" id="z_M03" value="=">
</span>
        <span id="el_cont_mes_contable_M03" class="ew-search-field">
<template id="tp_x_M03">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="cont_mes_contable" data-field="x_M03" name="x_M03" id="x_M03"<?= $Page->M03->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x_M03" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x_M03"
    name="x_M03"
    value="<?= HtmlEncode($Page->M03->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_M03"
    data-target="dsl_x_M03"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M03->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_M03"
    data-value-separator="<?= $Page->M03->displayValueSeparatorAttribute() ?>"
    <?= $Page->M03->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->M03->getErrorMessage(false) ?></div>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
<?php if ($Page->M04->Visible) { // M04 ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_M04" class="ew-cell form-group">
        <label class="ew-search-caption ew-label"><?= $Page->M04->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_M04" id="z_M04" value="=">
</span>
        <span id="el_cont_mes_contable_M04" class="ew-search-field">
<template id="tp_x_M04">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="cont_mes_contable" data-field="x_M04" name="x_M04" id="x_M04"<?= $Page->M04->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x_M04" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x_M04"
    name="x_M04"
    value="<?= HtmlEncode($Page->M04->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_M04"
    data-target="dsl_x_M04"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M04->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_M04"
    data-value-separator="<?= $Page->M04->displayValueSeparatorAttribute() ?>"
    <?= $Page->M04->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->M04->getErrorMessage(false) ?></div>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
<?php if ($Page->M05->Visible) { // M05 ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_M05" class="ew-cell form-group">
        <label class="ew-search-caption ew-label"><?= $Page->M05->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_M05" id="z_M05" value="=">
</span>
        <span id="el_cont_mes_contable_M05" class="ew-search-field">
<template id="tp_x_M05">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="cont_mes_contable" data-field="x_M05" name="x_M05" id="x_M05"<?= $Page->M05->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x_M05" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x_M05"
    name="x_M05"
    value="<?= HtmlEncode($Page->M05->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_M05"
    data-target="dsl_x_M05"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M05->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_M05"
    data-value-separator="<?= $Page->M05->displayValueSeparatorAttribute() ?>"
    <?= $Page->M05->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->M05->getErrorMessage(false) ?></div>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
<?php if ($Page->M06->Visible) { // M06 ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_M06" class="ew-cell form-group">
        <label class="ew-search-caption ew-label"><?= $Page->M06->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_M06" id="z_M06" value="=">
</span>
        <span id="el_cont_mes_contable_M06" class="ew-search-field">
<template id="tp_x_M06">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="cont_mes_contable" data-field="x_M06" name="x_M06" id="x_M06"<?= $Page->M06->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x_M06" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x_M06"
    name="x_M06"
    value="<?= HtmlEncode($Page->M06->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_M06"
    data-target="dsl_x_M06"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M06->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_M06"
    data-value-separator="<?= $Page->M06->displayValueSeparatorAttribute() ?>"
    <?= $Page->M06->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->M06->getErrorMessage(false) ?></div>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
<?php if ($Page->M07->Visible) { // M07 ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_M07" class="ew-cell form-group">
        <label class="ew-search-caption ew-label"><?= $Page->M07->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_M07" id="z_M07" value="=">
</span>
        <span id="el_cont_mes_contable_M07" class="ew-search-field">
<template id="tp_x_M07">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="cont_mes_contable" data-field="x_M07" name="x_M07" id="x_M07"<?= $Page->M07->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x_M07" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x_M07"
    name="x_M07"
    value="<?= HtmlEncode($Page->M07->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_M07"
    data-target="dsl_x_M07"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M07->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_M07"
    data-value-separator="<?= $Page->M07->displayValueSeparatorAttribute() ?>"
    <?= $Page->M07->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->M07->getErrorMessage(false) ?></div>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
<?php if ($Page->M08->Visible) { // M08 ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_M08" class="ew-cell form-group">
        <label class="ew-search-caption ew-label"><?= $Page->M08->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_M08" id="z_M08" value="=">
</span>
        <span id="el_cont_mes_contable_M08" class="ew-search-field">
<template id="tp_x_M08">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="cont_mes_contable" data-field="x_M08" name="x_M08" id="x_M08"<?= $Page->M08->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x_M08" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x_M08"
    name="x_M08"
    value="<?= HtmlEncode($Page->M08->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_M08"
    data-target="dsl_x_M08"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M08->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_M08"
    data-value-separator="<?= $Page->M08->displayValueSeparatorAttribute() ?>"
    <?= $Page->M08->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->M08->getErrorMessage(false) ?></div>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
<?php if ($Page->M09->Visible) { // M09 ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_M09" class="ew-cell form-group">
        <label class="ew-search-caption ew-label"><?= $Page->M09->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_M09" id="z_M09" value="=">
</span>
        <span id="el_cont_mes_contable_M09" class="ew-search-field">
<template id="tp_x_M09">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="cont_mes_contable" data-field="x_M09" name="x_M09" id="x_M09"<?= $Page->M09->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x_M09" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x_M09"
    name="x_M09"
    value="<?= HtmlEncode($Page->M09->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_M09"
    data-target="dsl_x_M09"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M09->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_M09"
    data-value-separator="<?= $Page->M09->displayValueSeparatorAttribute() ?>"
    <?= $Page->M09->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->M09->getErrorMessage(false) ?></div>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
<?php if ($Page->M10->Visible) { // M10 ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_M10" class="ew-cell form-group">
        <label class="ew-search-caption ew-label"><?= $Page->M10->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_M10" id="z_M10" value="=">
</span>
        <span id="el_cont_mes_contable_M10" class="ew-search-field">
<template id="tp_x_M10">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="cont_mes_contable" data-field="x_M10" name="x_M10" id="x_M10"<?= $Page->M10->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x_M10" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x_M10"
    name="x_M10"
    value="<?= HtmlEncode($Page->M10->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_M10"
    data-target="dsl_x_M10"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M10->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_M10"
    data-value-separator="<?= $Page->M10->displayValueSeparatorAttribute() ?>"
    <?= $Page->M10->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->M10->getErrorMessage(false) ?></div>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
<?php if ($Page->M11->Visible) { // M11 ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_M11" class="ew-cell form-group">
        <label class="ew-search-caption ew-label"><?= $Page->M11->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_M11" id="z_M11" value="=">
</span>
        <span id="el_cont_mes_contable_M11" class="ew-search-field">
<template id="tp_x_M11">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="cont_mes_contable" data-field="x_M11" name="x_M11" id="x_M11"<?= $Page->M11->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x_M11" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x_M11"
    name="x_M11"
    value="<?= HtmlEncode($Page->M11->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_M11"
    data-target="dsl_x_M11"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M11->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_M11"
    data-value-separator="<?= $Page->M11->displayValueSeparatorAttribute() ?>"
    <?= $Page->M11->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->M11->getErrorMessage(false) ?></div>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
<?php if ($Page->M12->Visible) { // M12 ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_M12" class="ew-cell form-group">
        <label class="ew-search-caption ew-label"><?= $Page->M12->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_M12" id="z_M12" value="=">
</span>
        <span id="el_cont_mes_contable_M12" class="ew-search-field">
<template id="tp_x_M12">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="cont_mes_contable" data-field="x_M12" name="x_M12" id="x_M12"<?= $Page->M12->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x_M12" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x_M12"
    name="x_M12"
    value="<?= HtmlEncode($Page->M12->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_M12"
    data-target="dsl_x_M12"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M12->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_M12"
    data-value-separator="<?= $Page->M12->displayValueSeparatorAttribute() ?>"
    <?= $Page->M12->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->M12->getErrorMessage(false) ?></div>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_activo" class="ew-cell form-group">
        <label class="ew-search-caption ew-label"><?= $Page->activo->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_activo" id="z_activo" value="=">
</span>
        <span id="el_cont_mes_contable_activo" class="ew-search-field">
<template id="tp_x_activo">
    <div class="custom-control custom-radio">
        <input type="radio" class="custom-control-input" data-table="cont_mes_contable" data-field="x_activo" name="x_activo" id="x_activo"<?= $Page->activo->editAttributes() ?>>
        <label class="custom-control-label"></label>
    </div>
</template>
<div id="dsl_x_activo" class="ew-item-list"></div>
<input type="hidden"
    is="selection-list"
    id="x_activo"
    name="x_activo"
    value="<?= HtmlEncode($Page->activo->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_activo"
    data-target="dsl_x_activo"
    data-repeatcolumn="5"
    class="form-control<?= $Page->activo->isInvalidClass() ?>"
    data-table="cont_mes_contable"
    data-field="x_activo"
    data-value-separator="<?= $Page->activo->displayValueSeparatorAttribute() ?>"
    <?= $Page->activo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->activo->getErrorMessage(false) ?></div>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> cont_mes_contable">
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
<form name="fcont_mes_contablelist" id="fcont_mes_contablelist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cont_mes_contable">
<div id="gmp_cont_mes_contable" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_cont_mes_contablelist" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Page->descripcion->Visible) { // descripcion ?>
        <th data-name="descripcion" class="<?= $Page->descripcion->headerCellClass() ?>"><div id="elh_cont_mes_contable_descripcion" class="cont_mes_contable_descripcion"><?= $Page->renderSort($Page->descripcion) ?></div></th>
<?php } ?>
<?php if ($Page->M01->Visible) { // M01 ?>
        <th data-name="M01" class="<?= $Page->M01->headerCellClass() ?>"><div id="elh_cont_mes_contable_M01" class="cont_mes_contable_M01"><?= $Page->renderSort($Page->M01) ?></div></th>
<?php } ?>
<?php if ($Page->M02->Visible) { // M02 ?>
        <th data-name="M02" class="<?= $Page->M02->headerCellClass() ?>"><div id="elh_cont_mes_contable_M02" class="cont_mes_contable_M02"><?= $Page->renderSort($Page->M02) ?></div></th>
<?php } ?>
<?php if ($Page->M03->Visible) { // M03 ?>
        <th data-name="M03" class="<?= $Page->M03->headerCellClass() ?>"><div id="elh_cont_mes_contable_M03" class="cont_mes_contable_M03"><?= $Page->renderSort($Page->M03) ?></div></th>
<?php } ?>
<?php if ($Page->M04->Visible) { // M04 ?>
        <th data-name="M04" class="<?= $Page->M04->headerCellClass() ?>"><div id="elh_cont_mes_contable_M04" class="cont_mes_contable_M04"><?= $Page->renderSort($Page->M04) ?></div></th>
<?php } ?>
<?php if ($Page->M05->Visible) { // M05 ?>
        <th data-name="M05" class="<?= $Page->M05->headerCellClass() ?>"><div id="elh_cont_mes_contable_M05" class="cont_mes_contable_M05"><?= $Page->renderSort($Page->M05) ?></div></th>
<?php } ?>
<?php if ($Page->M06->Visible) { // M06 ?>
        <th data-name="M06" class="<?= $Page->M06->headerCellClass() ?>"><div id="elh_cont_mes_contable_M06" class="cont_mes_contable_M06"><?= $Page->renderSort($Page->M06) ?></div></th>
<?php } ?>
<?php if ($Page->M07->Visible) { // M07 ?>
        <th data-name="M07" class="<?= $Page->M07->headerCellClass() ?>"><div id="elh_cont_mes_contable_M07" class="cont_mes_contable_M07"><?= $Page->renderSort($Page->M07) ?></div></th>
<?php } ?>
<?php if ($Page->M08->Visible) { // M08 ?>
        <th data-name="M08" class="<?= $Page->M08->headerCellClass() ?>"><div id="elh_cont_mes_contable_M08" class="cont_mes_contable_M08"><?= $Page->renderSort($Page->M08) ?></div></th>
<?php } ?>
<?php if ($Page->M09->Visible) { // M09 ?>
        <th data-name="M09" class="<?= $Page->M09->headerCellClass() ?>"><div id="elh_cont_mes_contable_M09" class="cont_mes_contable_M09"><?= $Page->renderSort($Page->M09) ?></div></th>
<?php } ?>
<?php if ($Page->M10->Visible) { // M10 ?>
        <th data-name="M10" class="<?= $Page->M10->headerCellClass() ?>"><div id="elh_cont_mes_contable_M10" class="cont_mes_contable_M10"><?= $Page->renderSort($Page->M10) ?></div></th>
<?php } ?>
<?php if ($Page->M11->Visible) { // M11 ?>
        <th data-name="M11" class="<?= $Page->M11->headerCellClass() ?>"><div id="elh_cont_mes_contable_M11" class="cont_mes_contable_M11"><?= $Page->renderSort($Page->M11) ?></div></th>
<?php } ?>
<?php if ($Page->M12->Visible) { // M12 ?>
        <th data-name="M12" class="<?= $Page->M12->headerCellClass() ?>"><div id="elh_cont_mes_contable_M12" class="cont_mes_contable_M12"><?= $Page->renderSort($Page->M12) ?></div></th>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
        <th data-name="activo" class="<?= $Page->activo->headerCellClass() ?>"><div id="elh_cont_mes_contable_activo" class="cont_mes_contable_activo"><?= $Page->renderSort($Page->activo) ?></div></th>
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_cont_mes_contable", "data-rowtype" => $Page->RowType]);

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
    <?php if ($Page->descripcion->Visible) { // descripcion ?>
        <td data-name="descripcion" <?= $Page->descripcion->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_mes_contable_descripcion">
<span<?= $Page->descripcion->viewAttributes() ?>>
<?= $Page->descripcion->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->M01->Visible) { // M01 ?>
        <td data-name="M01" <?= $Page->M01->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_mes_contable_M01">
<span<?= $Page->M01->viewAttributes() ?>>
<?= $Page->M01->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->M02->Visible) { // M02 ?>
        <td data-name="M02" <?= $Page->M02->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_mes_contable_M02">
<span<?= $Page->M02->viewAttributes() ?>>
<?= $Page->M02->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->M03->Visible) { // M03 ?>
        <td data-name="M03" <?= $Page->M03->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_mes_contable_M03">
<span<?= $Page->M03->viewAttributes() ?>>
<?= $Page->M03->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->M04->Visible) { // M04 ?>
        <td data-name="M04" <?= $Page->M04->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_mes_contable_M04">
<span<?= $Page->M04->viewAttributes() ?>>
<?= $Page->M04->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->M05->Visible) { // M05 ?>
        <td data-name="M05" <?= $Page->M05->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_mes_contable_M05">
<span<?= $Page->M05->viewAttributes() ?>>
<?= $Page->M05->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->M06->Visible) { // M06 ?>
        <td data-name="M06" <?= $Page->M06->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_mes_contable_M06">
<span<?= $Page->M06->viewAttributes() ?>>
<?= $Page->M06->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->M07->Visible) { // M07 ?>
        <td data-name="M07" <?= $Page->M07->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_mes_contable_M07">
<span<?= $Page->M07->viewAttributes() ?>>
<?= $Page->M07->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->M08->Visible) { // M08 ?>
        <td data-name="M08" <?= $Page->M08->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_mes_contable_M08">
<span<?= $Page->M08->viewAttributes() ?>>
<?= $Page->M08->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->M09->Visible) { // M09 ?>
        <td data-name="M09" <?= $Page->M09->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_mes_contable_M09">
<span<?= $Page->M09->viewAttributes() ?>>
<?= $Page->M09->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->M10->Visible) { // M10 ?>
        <td data-name="M10" <?= $Page->M10->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_mes_contable_M10">
<span<?= $Page->M10->viewAttributes() ?>>
<?= $Page->M10->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->M11->Visible) { // M11 ?>
        <td data-name="M11" <?= $Page->M11->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_mes_contable_M11">
<span<?= $Page->M11->viewAttributes() ?>>
<?= $Page->M11->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->M12->Visible) { // M12 ?>
        <td data-name="M12" <?= $Page->M12->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_mes_contable_M12">
<span<?= $Page->M12->viewAttributes() ?>>
<?= $Page->M12->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->activo->Visible) { // activo ?>
        <td data-name="activo" <?= $Page->activo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cont_mes_contable_activo">
<span<?= $Page->activo->viewAttributes() ?>>
<?= $Page->activo->getViewValue() ?></span>
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
    ew.addEventHandlers("cont_mes_contable");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
