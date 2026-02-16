<?php

namespace PHPMaker2021\mandrake;

// Page object
$SalidasList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fsalidaslist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    fsalidaslist = currentForm = new ew.Form("fsalidaslist", "list");
    fsalidaslist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';
    loadjs.done("fsalidaslist");
});
var fsalidaslistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    fsalidaslistsrch = currentSearchForm = new ew.Form("fsalidaslistsrch");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "salidas")) ?>,
        fields = currentTable.fields;
    fsalidaslistsrch.addFields([
        ["tipo_documento", [], fields.tipo_documento.isInvalid],
        ["nro_documento", [], fields.nro_documento.isInvalid],
        ["fecha", [ew.Validators.datetime(7)], fields.fecha.isInvalid],
        ["y_fecha", [ew.Validators.between], false],
        ["cliente", [ew.Validators.integer], fields.cliente.isInvalid],
        ["documento", [], fields.documento.isInvalid],
        ["doc_afectado", [], fields.doc_afectado.isInvalid],
        ["monto_total", [], fields.monto_total.isInvalid],
        ["alicuota_iva", [], fields.alicuota_iva.isInvalid],
        ["iva", [], fields.iva.isInvalid],
        ["total", [], fields.total.isInvalid],
        ["lista_pedido", [], fields.lista_pedido.isInvalid],
        ["_username", [], fields._username.isInvalid],
        ["estatus", [], fields.estatus.isInvalid],
        ["asesor", [], fields.asesor.isInvalid],
        ["unidades", [], fields.unidades.isInvalid],
        ["nro_despacho", [], fields.nro_despacho.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        fsalidaslistsrch.setInvalid();
    });

    // Validate form
    fsalidaslistsrch.validate = function () {
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
    fsalidaslistsrch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fsalidaslistsrch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fsalidaslistsrch.lists.cliente = <?= $Page->cliente->toClientList($Page) ?>;
    fsalidaslistsrch.lists.lista_pedido = <?= $Page->lista_pedido->toClientList($Page) ?>;
    fsalidaslistsrch.lists._username = <?= $Page->_username->toClientList($Page) ?>;
    fsalidaslistsrch.lists.estatus = <?= $Page->estatus->toClientList($Page) ?>;
    fsalidaslistsrch.lists.asesor = <?= $Page->asesor->toClientList($Page) ?>;

    // Filters
    fsalidaslistsrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    fsalidaslistsrch.initSearchPanel = true;
    loadjs.done("fsalidaslistsrch");
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
    // Client script
    function convertir(e){var r="convertir_a_factura.php?id="+e;return!!confirm("Está seguro que desear procesar este pedido?")&&(window.location.href=r,!0)}function verificar(e){var r="verificar_venta.php?id="+e;return!!confirm("Valida que este pedido de venta está correcto?")&&(window.location.href=r,!0)}function anular(e){var r="anular_venta.php?id="+e;return!!confirm("Esta seguro que desea anular este pedido de venta?")&&(window.location.href=r,!0)}
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
<form name="fsalidaslistsrch" id="fsalidaslistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fsalidaslistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="salidas">
    <div class="ew-extended-search">
<?php
// Render search row
$Page->RowType = ROWTYPE_SEARCH;
$Page->resetAttributes();
$Page->renderRow();
?>
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
        <span id="el_salidas_nro_documento" class="ew-search-field">
<input type="<?= $Page->nro_documento->getInputTextType() ?>" data-table="salidas" data-field="x_nro_documento" name="x_nro_documento" id="x_nro_documento" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->nro_documento->getPlaceHolder()) ?>" value="<?= $Page->nro_documento->EditValue ?>"<?= $Page->nro_documento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->nro_documento->getErrorMessage(false) ?></div>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_fecha" class="ew-cell form-group">
        <label for="x_fecha" class="ew-search-caption ew-label"><?= $Page->fecha->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("BETWEEN") ?>
<input type="hidden" name="z_fecha" id="z_fecha" value="BETWEEN">
</span>
        <span id="el_salidas_fecha" class="ew-search-field">
<input type="<?= $Page->fecha->getInputTextType() ?>" data-table="salidas" data-field="x_fecha" data-format="7" name="x_fecha" id="x_fecha" placeholder="<?= HtmlEncode($Page->fecha->getPlaceHolder()) ?>" value="<?= $Page->fecha->EditValue ?>"<?= $Page->fecha->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->fecha->getErrorMessage(false) ?></div>
<?php if (!$Page->fecha->ReadOnly && !$Page->fecha->Disabled && !isset($Page->fecha->EditAttrs["readonly"]) && !isset($Page->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fsalidaslistsrch", "datetimepicker"], function() {
    ew.createDateTimePicker("fsalidaslistsrch", "x_fecha", {"ignoreReadonly":true,"useCurrent":false,"format":7});
});
</script>
<?php } ?>
</span>
        <span class="ew-search-and"><label><?= $Language->phrase("AND") ?></label></span>
        <span id="el2_salidas_fecha" class="ew-search-field2">
<input type="<?= $Page->fecha->getInputTextType() ?>" data-table="salidas" data-field="x_fecha" data-format="7" name="y_fecha" id="y_fecha" placeholder="<?= HtmlEncode($Page->fecha->getPlaceHolder()) ?>" value="<?= $Page->fecha->EditValue2 ?>"<?= $Page->fecha->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->fecha->getErrorMessage(false) ?></div>
<?php if (!$Page->fecha->ReadOnly && !$Page->fecha->Disabled && !isset($Page->fecha->EditAttrs["readonly"]) && !isset($Page->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fsalidaslistsrch", "datetimepicker"], function() {
    ew.createDateTimePicker("fsalidaslistsrch", "y_fecha", {"ignoreReadonly":true,"useCurrent":false,"format":7});
});
</script>
<?php } ?>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
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
        <span id="el_salidas_cliente" class="ew-search-field">
<?php
$onchange = $Page->cliente->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Page->cliente->EditAttrs["onchange"] = "";
?>
<span id="as_x_cliente" class="ew-auto-suggest">
    <div class="input-group flex-nowrap">
        <input type="<?= $Page->cliente->getInputTextType() ?>" class="form-control" name="sv_x_cliente" id="sv_x_cliente" value="<?= RemoveHtml($Page->cliente->EditValue) ?>" size="30" placeholder="<?= HtmlEncode($Page->cliente->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->cliente->getPlaceHolder()) ?>"<?= $Page->cliente->editAttributes() ?>>
        <div class="input-group-append">
            <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->cliente->caption()), $Language->phrase("LookupLink", true))) ?>" onclick="ew.modalLookupShow({lnk:this,el:'x_cliente',m:0,n:10,srch:false});" class="ew-lookup-btn btn btn-default"<?= ($Page->cliente->ReadOnly || $Page->cliente->Disabled) ? " disabled" : "" ?>><i class="fas fa-search ew-icon"></i></button>
        </div>
    </div>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="salidas" data-field="x_cliente" data-input="sv_x_cliente" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->cliente->displayValueSeparatorAttribute() ?>" name="x_cliente" id="x_cliente" value="<?= HtmlEncode($Page->cliente->AdvancedSearch->SearchValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Page->cliente->getErrorMessage(false) ?></div>
<script>
loadjs.ready(["fsalidaslistsrch"], function() {
    fsalidaslistsrch.createAutoSuggest(Object.assign({"id":"x_cliente","forceSelect":true}, ew.vars.tables.salidas.fields.cliente.autoSuggestOptions));
});
</script>
<?= $Page->cliente->Lookup->getParamTag($Page, "p_x_cliente") ?>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
<?php if ($Page->lista_pedido->Visible) { // lista_pedido ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_lista_pedido" class="ew-cell form-group">
        <label for="x_lista_pedido" class="ew-search-caption ew-label"><?= $Page->lista_pedido->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_lista_pedido" id="z_lista_pedido" value="=">
</span>
        <span id="el_salidas_lista_pedido" class="ew-search-field">
    <select
        id="x_lista_pedido"
        name="x_lista_pedido"
        class="form-control ew-select<?= $Page->lista_pedido->isInvalidClass() ?>"
        data-select2-id="salidas_x_lista_pedido"
        data-table="salidas"
        data-field="x_lista_pedido"
        data-value-separator="<?= $Page->lista_pedido->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->lista_pedido->getPlaceHolder()) ?>"
        <?= $Page->lista_pedido->editAttributes() ?>>
        <?= $Page->lista_pedido->selectOptionListHtml("x_lista_pedido") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->lista_pedido->getErrorMessage(false) ?></div>
<?= $Page->lista_pedido->Lookup->getParamTag($Page, "p_x_lista_pedido") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='salidas_x_lista_pedido']"),
        options = { name: "x_lista_pedido", selectId: "salidas_x_lista_pedido", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.salidas.fields.lista_pedido.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc__username" class="ew-cell form-group">
        <label for="x__username" class="ew-search-caption ew-label"><?= $Page->_username->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z__username" id="z__username" value="=">
</span>
        <span id="el_salidas__username" class="ew-search-field">
    <select
        id="x__username"
        name="x__username"
        class="form-control ew-select<?= $Page->_username->isInvalidClass() ?>"
        data-select2-id="salidas_x__username"
        data-table="salidas"
        data-field="x__username"
        data-value-separator="<?= $Page->_username->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->_username->getPlaceHolder()) ?>"
        <?= $Page->_username->editAttributes() ?>>
        <?= $Page->_username->selectOptionListHtml("x__username") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->_username->getErrorMessage(false) ?></div>
<?= $Page->_username->Lookup->getParamTag($Page, "p_x__username") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='salidas_x__username']"),
        options = { name: "x__username", selectId: "salidas_x__username", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.salidas.fields._username.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
<?php if ($Page->estatus->Visible) { // estatus ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_estatus" class="ew-cell form-group">
        <label for="x_estatus" class="ew-search-caption ew-label"><?= $Page->estatus->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_estatus" id="z_estatus" value="LIKE">
</span>
        <span id="el_salidas_estatus" class="ew-search-field">
    <select
        id="x_estatus"
        name="x_estatus"
        class="form-control ew-select<?= $Page->estatus->isInvalidClass() ?>"
        data-select2-id="salidas_x_estatus"
        data-table="salidas"
        data-field="x_estatus"
        data-value-separator="<?= $Page->estatus->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->estatus->getPlaceHolder()) ?>"
        <?= $Page->estatus->editAttributes() ?>>
        <?= $Page->estatus->selectOptionListHtml("x_estatus") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->estatus->getErrorMessage(false) ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='salidas_x_estatus']"),
        options = { name: "x_estatus", selectId: "salidas_x_estatus", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.salidas.fields.estatus.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.salidas.fields.estatus.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
<?php if ($Page->asesor->Visible) { // asesor ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_asesor" class="ew-cell form-group">
        <label for="x_asesor" class="ew-search-caption ew-label"><?= $Page->asesor->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_asesor" id="z_asesor" value="=">
</span>
        <span id="el_salidas_asesor" class="ew-search-field">
    <select
        id="x_asesor"
        name="x_asesor"
        class="form-control ew-select<?= $Page->asesor->isInvalidClass() ?>"
        data-select2-id="salidas_x_asesor"
        data-table="salidas"
        data-field="x_asesor"
        data-value-separator="<?= $Page->asesor->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->asesor->getPlaceHolder()) ?>"
        <?= $Page->asesor->editAttributes() ?>>
        <?= $Page->asesor->selectOptionListHtml("x_asesor") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->asesor->getErrorMessage(false) ?></div>
<?= $Page->asesor->Lookup->getParamTag($Page, "p_x_asesor") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='salidas_x_asesor']"),
        options = { name: "x_asesor", selectId: "salidas_x_asesor", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.salidas.fields.asesor.selectOptions);
    ew.createSelect(options);
});
</script>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> salidas">
<form name="fsalidaslist" id="fsalidaslist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="salidas">
<div id="gmp_salidas" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_salidaslist" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
        <th data-name="tipo_documento" class="<?= $Page->tipo_documento->headerCellClass() ?>"><div id="elh_salidas_tipo_documento" class="salidas_tipo_documento"><?= $Page->renderSort($Page->tipo_documento) ?></div></th>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
        <th data-name="nro_documento" class="<?= $Page->nro_documento->headerCellClass() ?>"><div id="elh_salidas_nro_documento" class="salidas_nro_documento"><?= $Page->renderSort($Page->nro_documento) ?></div></th>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <th data-name="fecha" class="<?= $Page->fecha->headerCellClass() ?>"><div id="elh_salidas_fecha" class="salidas_fecha"><?= $Page->renderSort($Page->fecha) ?></div></th>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
        <th data-name="cliente" class="<?= $Page->cliente->headerCellClass() ?>"><div id="elh_salidas_cliente" class="salidas_cliente"><?= $Page->renderSort($Page->cliente) ?></div></th>
<?php } ?>
<?php if ($Page->documento->Visible) { // documento ?>
        <th data-name="documento" class="<?= $Page->documento->headerCellClass() ?>"><div id="elh_salidas_documento" class="salidas_documento"><?= $Page->renderSort($Page->documento) ?></div></th>
<?php } ?>
<?php if ($Page->doc_afectado->Visible) { // doc_afectado ?>
        <th data-name="doc_afectado" class="<?= $Page->doc_afectado->headerCellClass() ?>"><div id="elh_salidas_doc_afectado" class="salidas_doc_afectado"><?= $Page->renderSort($Page->doc_afectado) ?></div></th>
<?php } ?>
<?php if ($Page->monto_total->Visible) { // monto_total ?>
        <th data-name="monto_total" class="<?= $Page->monto_total->headerCellClass() ?>"><div id="elh_salidas_monto_total" class="salidas_monto_total"><?= $Page->renderSort($Page->monto_total) ?></div></th>
<?php } ?>
<?php if ($Page->alicuota_iva->Visible) { // alicuota_iva ?>
        <th data-name="alicuota_iva" class="<?= $Page->alicuota_iva->headerCellClass() ?>"><div id="elh_salidas_alicuota_iva" class="salidas_alicuota_iva"><?= $Page->renderSort($Page->alicuota_iva) ?></div></th>
<?php } ?>
<?php if ($Page->iva->Visible) { // iva ?>
        <th data-name="iva" class="<?= $Page->iva->headerCellClass() ?>"><div id="elh_salidas_iva" class="salidas_iva"><?= $Page->renderSort($Page->iva) ?></div></th>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
        <th data-name="total" class="<?= $Page->total->headerCellClass() ?>"><div id="elh_salidas_total" class="salidas_total"><?= $Page->renderSort($Page->total) ?></div></th>
<?php } ?>
<?php if ($Page->lista_pedido->Visible) { // lista_pedido ?>
        <th data-name="lista_pedido" class="<?= $Page->lista_pedido->headerCellClass() ?>"><div id="elh_salidas_lista_pedido" class="salidas_lista_pedido"><?= $Page->renderSort($Page->lista_pedido) ?></div></th>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
        <th data-name="_username" class="<?= $Page->_username->headerCellClass() ?>"><div id="elh_salidas__username" class="salidas__username"><?= $Page->renderSort($Page->_username) ?></div></th>
<?php } ?>
<?php if ($Page->estatus->Visible) { // estatus ?>
        <th data-name="estatus" class="<?= $Page->estatus->headerCellClass() ?>"><div id="elh_salidas_estatus" class="salidas_estatus"><?= $Page->renderSort($Page->estatus) ?></div></th>
<?php } ?>
<?php if ($Page->asesor->Visible) { // asesor ?>
        <th data-name="asesor" class="<?= $Page->asesor->headerCellClass() ?>"><div id="elh_salidas_asesor" class="salidas_asesor"><?= $Page->renderSort($Page->asesor) ?></div></th>
<?php } ?>
<?php if ($Page->unidades->Visible) { // unidades ?>
        <th data-name="unidades" class="<?= $Page->unidades->headerCellClass() ?>"><div id="elh_salidas_unidades" class="salidas_unidades"><?= $Page->renderSort($Page->unidades) ?></div></th>
<?php } ?>
<?php if ($Page->nro_despacho->Visible) { // nro_despacho ?>
        <th data-name="nro_despacho" class="<?= $Page->nro_despacho->headerCellClass() ?>"><div id="elh_salidas_nro_despacho" class="salidas_nro_despacho"><?= $Page->renderSort($Page->nro_despacho) ?></div></th>
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_salidas", "data-rowtype" => $Page->RowType]);

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
    <?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
        <td data-name="tipo_documento" <?= $Page->tipo_documento->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_salidas_tipo_documento">
<span<?= $Page->tipo_documento->viewAttributes() ?>>
<?= $Page->tipo_documento->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->nro_documento->Visible) { // nro_documento ?>
        <td data-name="nro_documento" <?= $Page->nro_documento->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_salidas_nro_documento">
<span<?= $Page->nro_documento->viewAttributes() ?>>
<?= $Page->nro_documento->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->fecha->Visible) { // fecha ?>
        <td data-name="fecha" <?= $Page->fecha->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_salidas_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->cliente->Visible) { // cliente ?>
        <td data-name="cliente" <?= $Page->cliente->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_salidas_cliente">
<span<?= $Page->cliente->viewAttributes() ?>>
<?= $Page->cliente->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->documento->Visible) { // documento ?>
        <td data-name="documento" <?= $Page->documento->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_salidas_documento">
<span<?= $Page->documento->viewAttributes() ?>>
<?= $Page->documento->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->doc_afectado->Visible) { // doc_afectado ?>
        <td data-name="doc_afectado" <?= $Page->doc_afectado->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_salidas_doc_afectado">
<span<?= $Page->doc_afectado->viewAttributes() ?>>
<?= $Page->doc_afectado->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->monto_total->Visible) { // monto_total ?>
        <td data-name="monto_total" <?= $Page->monto_total->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_salidas_monto_total">
<span<?= $Page->monto_total->viewAttributes() ?>>
<?= $Page->monto_total->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->alicuota_iva->Visible) { // alicuota_iva ?>
        <td data-name="alicuota_iva" <?= $Page->alicuota_iva->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_salidas_alicuota_iva">
<span<?= $Page->alicuota_iva->viewAttributes() ?>>
<?= $Page->alicuota_iva->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->iva->Visible) { // iva ?>
        <td data-name="iva" <?= $Page->iva->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_salidas_iva">
<span<?= $Page->iva->viewAttributes() ?>>
<?= $Page->iva->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->total->Visible) { // total ?>
        <td data-name="total" <?= $Page->total->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_salidas_total">
<span<?= $Page->total->viewAttributes() ?>>
<?= $Page->total->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->lista_pedido->Visible) { // lista_pedido ?>
        <td data-name="lista_pedido" <?= $Page->lista_pedido->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_salidas_lista_pedido">
<span<?= $Page->lista_pedido->viewAttributes() ?>>
<?= $Page->lista_pedido->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->_username->Visible) { // username ?>
        <td data-name="_username" <?= $Page->_username->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_salidas__username">
<span<?= $Page->_username->viewAttributes() ?>>
<?= $Page->_username->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->estatus->Visible) { // estatus ?>
        <td data-name="estatus" <?= $Page->estatus->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_salidas_estatus">
<span<?= $Page->estatus->viewAttributes() ?>>
<?= $Page->estatus->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->asesor->Visible) { // asesor ?>
        <td data-name="asesor" <?= $Page->asesor->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_salidas_asesor">
<span<?= $Page->asesor->viewAttributes() ?>>
<?= $Page->asesor->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->unidades->Visible) { // unidades ?>
        <td data-name="unidades" <?= $Page->unidades->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_salidas_unidades">
<span<?= $Page->unidades->viewAttributes() ?>>
<?= $Page->unidades->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->nro_despacho->Visible) { // nro_despacho ?>
        <td data-name="nro_despacho" <?= $Page->nro_despacho->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_salidas_nro_despacho">
<span<?= $Page->nro_despacho->viewAttributes() ?>>
<?= $Page->nro_despacho->getViewValue() ?></span>
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
    ew.addEventHandlers("salidas");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    $(document).ready((function(){$(".ew-detail-add").hide()})),$("#btnResumen").click((function(){var e=$("#x_fecha").val(),a=$("#y_fecha").val(),n=$("#x_cliente").val(),t=$("#x_asesor").val(),c=$("#btnResumen").text(),r="";if(r="Resumen de Venta"==c?"reportes/resumen_de_venta.php?xtitulo="+c+"&xcliente="+n+"&xasesor="+t+"&xfecha="+e+"&yfecha="+a:"reportes/resumen_de_facturacion.php?xtitulo="+c+"&xcliente="+n+"&xasesor="+t+"&xfecha="+e+"&yfecha="+a,""==e||""==a)return alert("Debe indicar fecha desde y hasta"),!1;window.open(r,"Resumen")})),$("#btnGanancia").click((function(){var e,a=$("#x_fecha").val(),n=$("#y_fecha").val(),t=$("#x_cliente").val(),c=$("#x_asesor").val();if(e="reportes/resumen_de_ganancia_ultimo_precio.php?xtitulo="+$("#btnResumen").text()+"&xcliente="+t+"&xasesor="+c+"&xfecha="+a+"&yfecha="+n,""==a||""==n)return alert("Debe indicar fecha desde y hasta"),!1;window.open(e,"Resumen")})),$("#btnResumen2").click((function(){var e,a=$("#x_fecha").val(),n=$("#y_fecha").val(),t=$("#x_cliente").val(),c=$("#x_asesor").val();if(e="reportes/resumen_de_facturacion2.php?xtitulo="+$("#btnResumen2").text()+"&xcliente="+t+"&xasesor="+c+"&xfecha="+a+"&yfecha="+n,""==a||""==n)return alert("Debe indicar fecha desde y hasta"),!1;window.open(e,"Resumen")})),$("#btnResumenVentas").click((function(){var e,a=$("#x_fecha").val(),n=$("#y_fecha").val(),t=$("#x_cliente").val(),c=$("#x_asesor").val();$("#btnResumen").text();if(e="reportes/resumen_de_notas_de_entrega.php?xtitulo=RESUMEN VENTAS&xcliente="+t+"&xasesor="+c+"&xfecha="+a+"&yfecha="+n+"&username="+$("#x__username").val(),""==a||""==n)return alert("Debe indicar fecha desde y hasta"),!1;window.open(e,"Resumen")})),$("#btnIngresoCaja").click((function(){var e=$("#x_fecha").val(),a=$("#y_fecha").val();if(url="include/ingreso_caja.php?xfecha="+e+"&yfecha="+a,""==e||""==a)return alert("Debe indicar fecha desde y hasta"),!1;window.open(url,"Resumen")}));
});
</script>
<?php } ?>
