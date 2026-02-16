<?php

namespace PHPMaker2021\mandrake;

// Page object
$ContLotesPagosDetalleList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fcont_lotes_pagos_detallelist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    fcont_lotes_pagos_detallelist = currentForm = new ew.Form("fcont_lotes_pagos_detallelist", "list");
    fcont_lotes_pagos_detallelist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "cont_lotes_pagos_detalle")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.cont_lotes_pagos_detalle)
        ew.vars.tables.cont_lotes_pagos_detalle = currentTable;
    fcont_lotes_pagos_detallelist.addFields([
        ["proveedor", [fields.proveedor.visible && fields.proveedor.required ? ew.Validators.required(fields.proveedor.caption) : null], fields.proveedor.isInvalid],
        ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null], fields.fecha.isInvalid],
        ["tipodoc", [fields.tipodoc.visible && fields.tipodoc.required ? ew.Validators.required(fields.tipodoc.caption) : null], fields.tipodoc.isInvalid],
        ["nro_documento", [fields.nro_documento.visible && fields.nro_documento.required ? ew.Validators.required(fields.nro_documento.caption) : null], fields.nro_documento.isInvalid],
        ["monto_a_pagar", [fields.monto_a_pagar.visible && fields.monto_a_pagar.required ? ew.Validators.required(fields.monto_a_pagar.caption) : null], fields.monto_a_pagar.isInvalid],
        ["monto_pagado", [fields.monto_pagado.visible && fields.monto_pagado.required ? ew.Validators.required(fields.monto_pagado.caption) : null], fields.monto_pagado.isInvalid],
        ["saldo", [fields.saldo.visible && fields.saldo.required ? ew.Validators.required(fields.saldo.caption) : null, ew.Validators.float], fields.saldo.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fcont_lotes_pagos_detallelist,
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
    fcont_lotes_pagos_detallelist.validate = function () {
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
    fcont_lotes_pagos_detallelist.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fcont_lotes_pagos_detallelist.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fcont_lotes_pagos_detallelist.lists.proveedor = <?= $Page->proveedor->toClientList($Page) ?>;
    fcont_lotes_pagos_detallelist.lists.tipodoc = <?= $Page->tipodoc->toClientList($Page) ?>;
    loadjs.done("fcont_lotes_pagos_detallelist");
});
var fcont_lotes_pagos_detallelistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    fcont_lotes_pagos_detallelistsrch = currentSearchForm = new ew.Form("fcont_lotes_pagos_detallelistsrch");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "cont_lotes_pagos_detalle")) ?>,
        fields = currentTable.fields;
    fcont_lotes_pagos_detallelistsrch.addFields([
        ["proveedor", [ew.Validators.integer], fields.proveedor.isInvalid],
        ["fecha", [], fields.fecha.isInvalid],
        ["tipodoc", [], fields.tipodoc.isInvalid],
        ["nro_documento", [], fields.nro_documento.isInvalid],
        ["monto_a_pagar", [], fields.monto_a_pagar.isInvalid],
        ["monto_pagado", [], fields.monto_pagado.isInvalid],
        ["saldo", [], fields.saldo.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        fcont_lotes_pagos_detallelistsrch.setInvalid();
    });

    // Validate form
    fcont_lotes_pagos_detallelistsrch.validate = function () {
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
    fcont_lotes_pagos_detallelistsrch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fcont_lotes_pagos_detallelistsrch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fcont_lotes_pagos_detallelistsrch.lists.proveedor = <?= $Page->proveedor->toClientList($Page) ?>;

    // Filters
    fcont_lotes_pagos_detallelistsrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    fcont_lotes_pagos_detallelistsrch.initSearchPanel = true;
    loadjs.done("fcont_lotes_pagos_detallelistsrch");
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
if ($Page->DbMasterFilter != "" && $Page->getCurrentMasterTable() == "cont_lotes_pagos") {
    if ($Page->MasterRecordExists) {
        include_once "views/ContLotesPagosMaster.php";
    }
}
?>
<?php } ?>
<?php
$Page->renderOtherOptions();
?>
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !$Page->CurrentAction) { ?>
<form name="fcont_lotes_pagos_detallelistsrch" id="fcont_lotes_pagos_detallelistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fcont_lotes_pagos_detallelistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="cont_lotes_pagos_detalle">
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
        <label class="ew-search-caption ew-label"><?= $Page->proveedor->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_proveedor" id="z_proveedor" value="=">
</span>
        <span id="el_cont_lotes_pagos_detalle_proveedor" class="ew-search-field">
<?php
$onchange = $Page->proveedor->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Page->proveedor->EditAttrs["onchange"] = "";
?>
<span id="as_x_proveedor" class="ew-auto-suggest">
    <div class="input-group flex-nowrap">
        <input type="<?= $Page->proveedor->getInputTextType() ?>" class="form-control" name="sv_x_proveedor" id="sv_x_proveedor" value="<?= RemoveHtml($Page->proveedor->EditValue) ?>" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->proveedor->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->proveedor->getPlaceHolder()) ?>"<?= $Page->proveedor->editAttributes() ?>>
        <div class="input-group-append">
            <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->proveedor->caption()), $Language->phrase("LookupLink", true))) ?>" onclick="ew.modalLookupShow({lnk:this,el:'x_proveedor',m:0,n:10,srch:true});" class="ew-lookup-btn btn btn-default"<?= ($Page->proveedor->ReadOnly || $Page->proveedor->Disabled) ? " disabled" : "" ?>><i class="fas fa-search ew-icon"></i></button>
        </div>
    </div>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="cont_lotes_pagos_detalle" data-field="x_proveedor" data-input="sv_x_proveedor" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->proveedor->displayValueSeparatorAttribute() ?>" name="x_proveedor" id="x_proveedor" value="<?= HtmlEncode($Page->proveedor->AdvancedSearch->SearchValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Page->proveedor->getErrorMessage(false) ?></div>
<script>
loadjs.ready(["fcont_lotes_pagos_detallelistsrch"], function() {
    fcont_lotes_pagos_detallelistsrch.createAutoSuggest(Object.assign({"id":"x_proveedor","forceSelect":false}, ew.vars.tables.cont_lotes_pagos_detalle.fields.proveedor.autoSuggestOptions));
});
</script>
<?= $Page->proveedor->Lookup->getParamTag($Page, "p_x_proveedor") ?>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> cont_lotes_pagos_detalle">
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
<form name="fcont_lotes_pagos_detallelist" id="fcont_lotes_pagos_detallelist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cont_lotes_pagos_detalle">
<?php if ($Page->getCurrentMasterTable() == "cont_lotes_pagos" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="cont_lotes_pagos">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->cont_lotes_pago->getSessionValue()) ?>">
<?php } ?>
<div id="gmp_cont_lotes_pagos_detalle" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_cont_lotes_pagos_detallelist" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Page->proveedor->Visible) { // proveedor ?>
        <th data-name="proveedor" class="<?= $Page->proveedor->headerCellClass() ?>"><div id="elh_cont_lotes_pagos_detalle_proveedor" class="cont_lotes_pagos_detalle_proveedor"><?= $Page->renderSort($Page->proveedor) ?></div></th>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <th data-name="fecha" class="<?= $Page->fecha->headerCellClass() ?>"><div id="elh_cont_lotes_pagos_detalle_fecha" class="cont_lotes_pagos_detalle_fecha"><?= $Page->renderSort($Page->fecha) ?></div></th>
<?php } ?>
<?php if ($Page->tipodoc->Visible) { // tipodoc ?>
        <th data-name="tipodoc" class="<?= $Page->tipodoc->headerCellClass() ?>"><div id="elh_cont_lotes_pagos_detalle_tipodoc" class="cont_lotes_pagos_detalle_tipodoc"><?= $Page->renderSort($Page->tipodoc) ?></div></th>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
        <th data-name="nro_documento" class="<?= $Page->nro_documento->headerCellClass() ?>"><div id="elh_cont_lotes_pagos_detalle_nro_documento" class="cont_lotes_pagos_detalle_nro_documento"><?= $Page->renderSort($Page->nro_documento) ?></div></th>
<?php } ?>
<?php if ($Page->monto_a_pagar->Visible) { // monto_a_pagar ?>
        <th data-name="monto_a_pagar" class="<?= $Page->monto_a_pagar->headerCellClass() ?>"><div id="elh_cont_lotes_pagos_detalle_monto_a_pagar" class="cont_lotes_pagos_detalle_monto_a_pagar"><?= $Page->renderSort($Page->monto_a_pagar) ?></div></th>
<?php } ?>
<?php if ($Page->monto_pagado->Visible) { // monto_pagado ?>
        <th data-name="monto_pagado" class="<?= $Page->monto_pagado->headerCellClass() ?>"><div id="elh_cont_lotes_pagos_detalle_monto_pagado" class="cont_lotes_pagos_detalle_monto_pagado"><?= $Page->renderSort($Page->monto_pagado) ?></div></th>
<?php } ?>
<?php if ($Page->saldo->Visible) { // saldo ?>
        <th data-name="saldo" class="<?= $Page->saldo->headerCellClass() ?>"><div id="elh_cont_lotes_pagos_detalle_saldo" class="cont_lotes_pagos_detalle_saldo"><?= $Page->renderSort($Page->saldo) ?></div></th>
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
$Page->EditRowCount = 0;
if ($Page->isEdit())
    $Page->RowIndex = 1;
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
        if ($Page->isEdit()) {
            if ($Page->checkInlineEditKey() && $Page->EditRowCount == 0) { // Inline edit
                $Page->RowType = ROWTYPE_EDIT; // Render edit
            }
        }
        if ($Page->isEdit() && $Page->RowType == ROWTYPE_EDIT && $Page->EventCancelled) { // Update failed
            $CurrentForm->Index = 1;
            $Page->restoreFormValues(); // Restore form values
        }
        if ($Page->RowType == ROWTYPE_EDIT) { // Edit row
            $Page->EditRowCount++;
        }

        // Set up row id / data-rowindex
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_cont_lotes_pagos_detalle", "data-rowtype" => $Page->RowType]);

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
    <?php if ($Page->proveedor->Visible) { // proveedor ?>
        <td data-name="proveedor" <?= $Page->proveedor->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_cont_lotes_pagos_detalle_proveedor" class="form-group">
<span<?= $Page->proveedor->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->proveedor->getDisplayValue($Page->proveedor->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_proveedor" data-hidden="1" name="x<?= $Page->RowIndex ?>_proveedor" id="x<?= $Page->RowIndex ?>_proveedor" value="<?= HtmlEncode($Page->proveedor->CurrentValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_cont_lotes_pagos_detalle_proveedor">
<span<?= $Page->proveedor->viewAttributes() ?>>
<?= $Page->proveedor->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->fecha->Visible) { // fecha ?>
        <td data-name="fecha" <?= $Page->fecha->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_cont_lotes_pagos_detalle_fecha" class="form-group">
<span<?= $Page->fecha->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->fecha->getDisplayValue($Page->fecha->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_fecha" data-hidden="1" name="x<?= $Page->RowIndex ?>_fecha" id="x<?= $Page->RowIndex ?>_fecha" value="<?= HtmlEncode($Page->fecha->CurrentValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_cont_lotes_pagos_detalle_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->tipodoc->Visible) { // tipodoc ?>
        <td data-name="tipodoc" <?= $Page->tipodoc->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_cont_lotes_pagos_detalle_tipodoc" class="form-group">
<span<?= $Page->tipodoc->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->tipodoc->getDisplayValue($Page->tipodoc->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_tipodoc" data-hidden="1" name="x<?= $Page->RowIndex ?>_tipodoc" id="x<?= $Page->RowIndex ?>_tipodoc" value="<?= HtmlEncode($Page->tipodoc->CurrentValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_cont_lotes_pagos_detalle_tipodoc">
<span<?= $Page->tipodoc->viewAttributes() ?>>
<?= $Page->tipodoc->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->nro_documento->Visible) { // nro_documento ?>
        <td data-name="nro_documento" <?= $Page->nro_documento->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_cont_lotes_pagos_detalle_nro_documento" class="form-group">
<span<?= $Page->nro_documento->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->nro_documento->getDisplayValue($Page->nro_documento->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_nro_documento" data-hidden="1" name="x<?= $Page->RowIndex ?>_nro_documento" id="x<?= $Page->RowIndex ?>_nro_documento" value="<?= HtmlEncode($Page->nro_documento->CurrentValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_cont_lotes_pagos_detalle_nro_documento">
<span<?= $Page->nro_documento->viewAttributes() ?>>
<?= $Page->nro_documento->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->monto_a_pagar->Visible) { // monto_a_pagar ?>
        <td data-name="monto_a_pagar" <?= $Page->monto_a_pagar->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_cont_lotes_pagos_detalle_monto_a_pagar" class="form-group">
<span<?= $Page->monto_a_pagar->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->monto_a_pagar->getDisplayValue($Page->monto_a_pagar->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_monto_a_pagar" data-hidden="1" name="x<?= $Page->RowIndex ?>_monto_a_pagar" id="x<?= $Page->RowIndex ?>_monto_a_pagar" value="<?= HtmlEncode($Page->monto_a_pagar->CurrentValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_cont_lotes_pagos_detalle_monto_a_pagar">
<span<?= $Page->monto_a_pagar->viewAttributes() ?>>
<?= $Page->monto_a_pagar->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->monto_pagado->Visible) { // monto_pagado ?>
        <td data-name="monto_pagado" <?= $Page->monto_pagado->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_cont_lotes_pagos_detalle_monto_pagado" class="form-group">
<span<?= $Page->monto_pagado->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->monto_pagado->getDisplayValue($Page->monto_pagado->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="cont_lotes_pagos_detalle" data-field="x_monto_pagado" data-hidden="1" name="x<?= $Page->RowIndex ?>_monto_pagado" id="x<?= $Page->RowIndex ?>_monto_pagado" value="<?= HtmlEncode($Page->monto_pagado->CurrentValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_cont_lotes_pagos_detalle_monto_pagado">
<span<?= $Page->monto_pagado->viewAttributes() ?>>
<?= $Page->monto_pagado->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->saldo->Visible) { // saldo ?>
        <td data-name="saldo" <?= $Page->saldo->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_cont_lotes_pagos_detalle_saldo" class="form-group">
<input type="<?= $Page->saldo->getInputTextType() ?>" data-table="cont_lotes_pagos_detalle" data-field="x_saldo" name="x<?= $Page->RowIndex ?>_saldo" id="x<?= $Page->RowIndex ?>_saldo" size="10" maxlength="14" placeholder="<?= HtmlEncode($Page->saldo->getPlaceHolder()) ?>" value="<?= $Page->saldo->EditValue ?>"<?= $Page->saldo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->saldo->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_cont_lotes_pagos_detalle_saldo">
<span<?= $Page->saldo->viewAttributes() ?>>
<?= $Page->saldo->getViewValue() ?></span>
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
loadjs.ready(["fcont_lotes_pagos_detallelist","load"], function () {
    fcont_lotes_pagos_detallelist.updateLists(<?= $Page->RowIndex ?>);
});
</script>
<?php } ?>
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
<?php if ($Page->isEdit()) { ?>
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
    ew.addEventHandlers("cont_lotes_pagos_detalle");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
