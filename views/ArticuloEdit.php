<?php

namespace PHPMaker2021\mandrake;

// Page object
$ArticuloEdit = &$Page;
?>
<script>
var currentForm, currentPageID;
var farticuloedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    farticuloedit = currentForm = new ew.Form("farticuloedit", "edit");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "articulo")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.articulo)
        ew.vars.tables.articulo = currentTable;
    farticuloedit.addFields([
        ["codigo_ims", [fields.codigo_ims.visible && fields.codigo_ims.required ? ew.Validators.required(fields.codigo_ims.caption) : null], fields.codigo_ims.isInvalid],
        ["codigo", [fields.codigo.visible && fields.codigo.required ? ew.Validators.required(fields.codigo.caption) : null], fields.codigo.isInvalid],
        ["principio_activo", [fields.principio_activo.visible && fields.principio_activo.required ? ew.Validators.required(fields.principio_activo.caption) : null], fields.principio_activo.isInvalid],
        ["fabricante", [fields.fabricante.visible && fields.fabricante.required ? ew.Validators.required(fields.fabricante.caption) : null], fields.fabricante.isInvalid],
        ["codigo_de_barra", [fields.codigo_de_barra.visible && fields.codigo_de_barra.required ? ew.Validators.required(fields.codigo_de_barra.caption) : null], fields.codigo_de_barra.isInvalid],
        ["unidad_medida_defecto", [fields.unidad_medida_defecto.visible && fields.unidad_medida_defecto.required ? ew.Validators.required(fields.unidad_medida_defecto.caption) : null], fields.unidad_medida_defecto.isInvalid],
        ["cantidad_por_unidad_medida", [fields.cantidad_por_unidad_medida.visible && fields.cantidad_por_unidad_medida.required ? ew.Validators.required(fields.cantidad_por_unidad_medida.caption) : null], fields.cantidad_por_unidad_medida.isInvalid],
        ["foto", [fields.foto.visible && fields.foto.required ? ew.Validators.fileRequired(fields.foto.caption) : null], fields.foto.isInvalid],
        ["cantidad_minima", [fields.cantidad_minima.visible && fields.cantidad_minima.required ? ew.Validators.required(fields.cantidad_minima.caption) : null, ew.Validators.float], fields.cantidad_minima.isInvalid],
        ["cantidad_maxima", [fields.cantidad_maxima.visible && fields.cantidad_maxima.required ? ew.Validators.required(fields.cantidad_maxima.caption) : null, ew.Validators.float], fields.cantidad_maxima.isInvalid],
        ["ultimo_costo", [fields.ultimo_costo.visible && fields.ultimo_costo.required ? ew.Validators.required(fields.ultimo_costo.caption) : null, ew.Validators.float], fields.ultimo_costo.isInvalid],
        ["descuento", [fields.descuento.visible && fields.descuento.required ? ew.Validators.required(fields.descuento.caption) : null, ew.Validators.float], fields.descuento.isInvalid],
        ["alicuota", [fields.alicuota.visible && fields.alicuota.required ? ew.Validators.required(fields.alicuota.caption) : null], fields.alicuota.isInvalid],
        ["articulo_inventario", [fields.articulo_inventario.visible && fields.articulo_inventario.required ? ew.Validators.required(fields.articulo_inventario.caption) : null], fields.articulo_inventario.isInvalid],
        ["activo", [fields.activo.visible && fields.activo.required ? ew.Validators.required(fields.activo.caption) : null], fields.activo.isInvalid],
        ["puntos_ventas", [fields.puntos_ventas.visible && fields.puntos_ventas.required ? ew.Validators.required(fields.puntos_ventas.caption) : null, ew.Validators.integer], fields.puntos_ventas.isInvalid],
        ["puntos_premio", [fields.puntos_premio.visible && fields.puntos_premio.required ? ew.Validators.required(fields.puntos_premio.caption) : null, ew.Validators.integer], fields.puntos_premio.isInvalid],
        ["sincroniza", [fields.sincroniza.visible && fields.sincroniza.required ? ew.Validators.required(fields.sincroniza.caption) : null], fields.sincroniza.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = farticuloedit,
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
    farticuloedit.validate = function () {
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

        // Process detail forms
        var dfs = $fobj.find("input[name='detailpage']").get();
        for (var i = 0; i < dfs.length; i++) {
            var df = dfs[i],
                val = df.value,
                frm = ew.forms.get(val);
            if (val && frm && !frm.validate())
                return false;
        }
        return true;
    }

    // Form_CustomValidate
    farticuloedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    farticuloedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    farticuloedit.lists.fabricante = <?= $Page->fabricante->toClientList($Page) ?>;
    farticuloedit.lists.unidad_medida_defecto = <?= $Page->unidad_medida_defecto->toClientList($Page) ?>;
    farticuloedit.lists.cantidad_por_unidad_medida = <?= $Page->cantidad_por_unidad_medida->toClientList($Page) ?>;
    farticuloedit.lists.alicuota = <?= $Page->alicuota->toClientList($Page) ?>;
    farticuloedit.lists.articulo_inventario = <?= $Page->articulo_inventario->toClientList($Page) ?>;
    farticuloedit.lists.activo = <?= $Page->activo->toClientList($Page) ?>;
    farticuloedit.lists.sincroniza = <?= $Page->sincroniza->toClientList($Page) ?>;
    loadjs.done("farticuloedit");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<?php if (!$Page->IsModal) { ?>
<form name="ew-pager-form" class="form-inline ew-form ew-pager-form" action="<?= CurrentPageUrl(false) ?>">
<?= $Page->Pager->render() ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="farticuloedit" id="farticuloedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="articulo">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->codigo_ims->Visible) { // codigo_ims ?>
    <div id="r_codigo_ims" class="form-group row">
        <label id="elh_articulo_codigo_ims" for="x_codigo_ims" class="<?= $Page->LeftColumnClass ?>"><?= $Page->codigo_ims->caption() ?><?= $Page->codigo_ims->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->codigo_ims->cellAttributes() ?>>
<span id="el_articulo_codigo_ims">
<input type="<?= $Page->codigo_ims->getInputTextType() ?>" data-table="articulo" data-field="x_codigo_ims" name="x_codigo_ims" id="x_codigo_ims" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->codigo_ims->getPlaceHolder()) ?>" value="<?= $Page->codigo_ims->EditValue ?>"<?= $Page->codigo_ims->editAttributes() ?> aria-describedby="x_codigo_ims_help">
<?= $Page->codigo_ims->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->codigo_ims->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->codigo->Visible) { // codigo ?>
    <div id="r_codigo" class="form-group row">
        <label id="elh_articulo_codigo" for="x_codigo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->codigo->caption() ?><?= $Page->codigo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->codigo->cellAttributes() ?>>
<span id="el_articulo_codigo">
<input type="<?= $Page->codigo->getInputTextType() ?>" data-table="articulo" data-field="x_codigo" name="x_codigo" id="x_codigo" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->codigo->getPlaceHolder()) ?>" value="<?= $Page->codigo->EditValue ?>"<?= $Page->codigo->editAttributes() ?> aria-describedby="x_codigo_help">
<?= $Page->codigo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->codigo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->principio_activo->Visible) { // principio_activo ?>
    <div id="r_principio_activo" class="form-group row">
        <label id="elh_articulo_principio_activo" for="x_principio_activo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->principio_activo->caption() ?><?= $Page->principio_activo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->principio_activo->cellAttributes() ?>>
<span id="el_articulo_principio_activo">
<input type="<?= $Page->principio_activo->getInputTextType() ?>" data-table="articulo" data-field="x_principio_activo" name="x_principio_activo" id="x_principio_activo" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->principio_activo->getPlaceHolder()) ?>" value="<?= $Page->principio_activo->EditValue ?>"<?= $Page->principio_activo->editAttributes() ?> aria-describedby="x_principio_activo_help">
<?= $Page->principio_activo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->principio_activo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fabricante->Visible) { // fabricante ?>
    <div id="r_fabricante" class="form-group row">
        <label id="elh_articulo_fabricante" for="x_fabricante" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fabricante->caption() ?><?= $Page->fabricante->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->fabricante->cellAttributes() ?>>
<span id="el_articulo_fabricante">
<div class="input-group ew-lookup-list" aria-describedby="x_fabricante_help">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x_fabricante"><?= EmptyValue(strval($Page->fabricante->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->fabricante->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->fabricante->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->fabricante->ReadOnly || $Page->fabricante->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x_fabricante',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->fabricante->getErrorMessage() ?></div>
<?= $Page->fabricante->getCustomMessage() ?>
<?= $Page->fabricante->Lookup->getParamTag($Page, "p_x_fabricante") ?>
<input type="hidden" is="selection-list" data-table="articulo" data-field="x_fabricante" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->fabricante->displayValueSeparatorAttribute() ?>" name="x_fabricante" id="x_fabricante" value="<?= $Page->fabricante->CurrentValue ?>"<?= $Page->fabricante->editAttributes() ?>>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->codigo_de_barra->Visible) { // codigo_de_barra ?>
    <div id="r_codigo_de_barra" class="form-group row">
        <label id="elh_articulo_codigo_de_barra" for="x_codigo_de_barra" class="<?= $Page->LeftColumnClass ?>"><?= $Page->codigo_de_barra->caption() ?><?= $Page->codigo_de_barra->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->codigo_de_barra->cellAttributes() ?>>
<span id="el_articulo_codigo_de_barra">
<input type="<?= $Page->codigo_de_barra->getInputTextType() ?>" data-table="articulo" data-field="x_codigo_de_barra" name="x_codigo_de_barra" id="x_codigo_de_barra" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->codigo_de_barra->getPlaceHolder()) ?>" value="<?= $Page->codigo_de_barra->EditValue ?>"<?= $Page->codigo_de_barra->editAttributes() ?> aria-describedby="x_codigo_de_barra_help">
<?= $Page->codigo_de_barra->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->codigo_de_barra->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->unidad_medida_defecto->Visible) { // unidad_medida_defecto ?>
    <div id="r_unidad_medida_defecto" class="form-group row">
        <label id="elh_articulo_unidad_medida_defecto" for="x_unidad_medida_defecto" class="<?= $Page->LeftColumnClass ?>"><?= $Page->unidad_medida_defecto->caption() ?><?= $Page->unidad_medida_defecto->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->unidad_medida_defecto->cellAttributes() ?>>
<span id="el_articulo_unidad_medida_defecto">
<?php $Page->unidad_medida_defecto->EditAttrs->prepend("onchange", "ew.updateOptions.call(this);"); ?>
    <select
        id="x_unidad_medida_defecto"
        name="x_unidad_medida_defecto"
        class="form-control ew-select<?= $Page->unidad_medida_defecto->isInvalidClass() ?>"
        data-select2-id="articulo_x_unidad_medida_defecto"
        data-table="articulo"
        data-field="x_unidad_medida_defecto"
        data-value-separator="<?= $Page->unidad_medida_defecto->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->unidad_medida_defecto->getPlaceHolder()) ?>"
        <?= $Page->unidad_medida_defecto->editAttributes() ?>>
        <?= $Page->unidad_medida_defecto->selectOptionListHtml("x_unidad_medida_defecto") ?>
    </select>
    <?= $Page->unidad_medida_defecto->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->unidad_medida_defecto->getErrorMessage() ?></div>
<?= $Page->unidad_medida_defecto->Lookup->getParamTag($Page, "p_x_unidad_medida_defecto") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='articulo_x_unidad_medida_defecto']"),
        options = { name: "x_unidad_medida_defecto", selectId: "articulo_x_unidad_medida_defecto", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.articulo.fields.unidad_medida_defecto.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cantidad_por_unidad_medida->Visible) { // cantidad_por_unidad_medida ?>
    <div id="r_cantidad_por_unidad_medida" class="form-group row">
        <label id="elh_articulo_cantidad_por_unidad_medida" for="x_cantidad_por_unidad_medida" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cantidad_por_unidad_medida->caption() ?><?= $Page->cantidad_por_unidad_medida->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cantidad_por_unidad_medida->cellAttributes() ?>>
<span id="el_articulo_cantidad_por_unidad_medida">
    <select
        id="x_cantidad_por_unidad_medida"
        name="x_cantidad_por_unidad_medida"
        class="form-control ew-select<?= $Page->cantidad_por_unidad_medida->isInvalidClass() ?>"
        data-select2-id="articulo_x_cantidad_por_unidad_medida"
        data-table="articulo"
        data-field="x_cantidad_por_unidad_medida"
        data-value-separator="<?= $Page->cantidad_por_unidad_medida->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->cantidad_por_unidad_medida->getPlaceHolder()) ?>"
        <?= $Page->cantidad_por_unidad_medida->editAttributes() ?>>
        <?= $Page->cantidad_por_unidad_medida->selectOptionListHtml("x_cantidad_por_unidad_medida") ?>
    </select>
    <?= $Page->cantidad_por_unidad_medida->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->cantidad_por_unidad_medida->getErrorMessage() ?></div>
<?= $Page->cantidad_por_unidad_medida->Lookup->getParamTag($Page, "p_x_cantidad_por_unidad_medida") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='articulo_x_cantidad_por_unidad_medida']"),
        options = { name: "x_cantidad_por_unidad_medida", selectId: "articulo_x_cantidad_por_unidad_medida", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.articulo.fields.cantidad_por_unidad_medida.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->foto->Visible) { // foto ?>
    <div id="r_foto" class="form-group row">
        <label id="elh_articulo_foto" class="<?= $Page->LeftColumnClass ?>"><?= $Page->foto->caption() ?><?= $Page->foto->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->foto->cellAttributes() ?>>
<span id="el_articulo_foto">
<div id="fd_x_foto">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->foto->title() ?>" data-table="articulo" data-field="x_foto" name="x_foto" id="x_foto" lang="<?= CurrentLanguageID() ?>"<?= $Page->foto->editAttributes() ?><?= ($Page->foto->ReadOnly || $Page->foto->Disabled) ? " disabled" : "" ?> aria-describedby="x_foto_help">
        <label class="custom-file-label ew-file-label" for="x_foto"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->foto->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->foto->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_foto" id= "fn_x_foto" value="<?= $Page->foto->Upload->FileName ?>">
<input type="hidden" name="fa_x_foto" id= "fa_x_foto" value="<?= (Post("fa_x_foto") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_foto" id= "fs_x_foto" value="250">
<input type="hidden" name="fx_x_foto" id= "fx_x_foto" value="<?= $Page->foto->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_foto" id= "fm_x_foto" value="<?= $Page->foto->UploadMaxFileSize ?>">
</div>
<table id="ft_x_foto" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cantidad_minima->Visible) { // cantidad_minima ?>
    <div id="r_cantidad_minima" class="form-group row">
        <label id="elh_articulo_cantidad_minima" for="x_cantidad_minima" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cantidad_minima->caption() ?><?= $Page->cantidad_minima->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cantidad_minima->cellAttributes() ?>>
<span id="el_articulo_cantidad_minima">
<input type="<?= $Page->cantidad_minima->getInputTextType() ?>" data-table="articulo" data-field="x_cantidad_minima" name="x_cantidad_minima" id="x_cantidad_minima" size="30" maxlength="9" placeholder="<?= HtmlEncode($Page->cantidad_minima->getPlaceHolder()) ?>" value="<?= $Page->cantidad_minima->EditValue ?>"<?= $Page->cantidad_minima->editAttributes() ?> aria-describedby="x_cantidad_minima_help">
<?= $Page->cantidad_minima->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cantidad_minima->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cantidad_maxima->Visible) { // cantidad_maxima ?>
    <div id="r_cantidad_maxima" class="form-group row">
        <label id="elh_articulo_cantidad_maxima" for="x_cantidad_maxima" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cantidad_maxima->caption() ?><?= $Page->cantidad_maxima->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cantidad_maxima->cellAttributes() ?>>
<span id="el_articulo_cantidad_maxima">
<input type="<?= $Page->cantidad_maxima->getInputTextType() ?>" data-table="articulo" data-field="x_cantidad_maxima" name="x_cantidad_maxima" id="x_cantidad_maxima" size="30" maxlength="9" placeholder="<?= HtmlEncode($Page->cantidad_maxima->getPlaceHolder()) ?>" value="<?= $Page->cantidad_maxima->EditValue ?>"<?= $Page->cantidad_maxima->editAttributes() ?> aria-describedby="x_cantidad_maxima_help">
<?= $Page->cantidad_maxima->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cantidad_maxima->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ultimo_costo->Visible) { // ultimo_costo ?>
    <div id="r_ultimo_costo" class="form-group row">
        <label id="elh_articulo_ultimo_costo" for="x_ultimo_costo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ultimo_costo->caption() ?><?= $Page->ultimo_costo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->ultimo_costo->cellAttributes() ?>>
<span id="el_articulo_ultimo_costo">
<input type="<?= $Page->ultimo_costo->getInputTextType() ?>" data-table="articulo" data-field="x_ultimo_costo" name="x_ultimo_costo" id="x_ultimo_costo" size="30" maxlength="14" placeholder="<?= HtmlEncode($Page->ultimo_costo->getPlaceHolder()) ?>" value="<?= $Page->ultimo_costo->EditValue ?>"<?= $Page->ultimo_costo->editAttributes() ?> aria-describedby="x_ultimo_costo_help">
<?= $Page->ultimo_costo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ultimo_costo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->descuento->Visible) { // descuento ?>
    <div id="r_descuento" class="form-group row">
        <label id="elh_articulo_descuento" for="x_descuento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->descuento->caption() ?><?= $Page->descuento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->descuento->cellAttributes() ?>>
<span id="el_articulo_descuento">
<input type="<?= $Page->descuento->getInputTextType() ?>" data-table="articulo" data-field="x_descuento" name="x_descuento" id="x_descuento" size="6" maxlength="6" placeholder="<?= HtmlEncode($Page->descuento->getPlaceHolder()) ?>" value="<?= $Page->descuento->EditValue ?>"<?= $Page->descuento->editAttributes() ?> aria-describedby="x_descuento_help">
<?= $Page->descuento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->descuento->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->alicuota->Visible) { // alicuota ?>
    <div id="r_alicuota" class="form-group row">
        <label id="elh_articulo_alicuota" for="x_alicuota" class="<?= $Page->LeftColumnClass ?>"><?= $Page->alicuota->caption() ?><?= $Page->alicuota->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->alicuota->cellAttributes() ?>>
<span id="el_articulo_alicuota">
    <select
        id="x_alicuota"
        name="x_alicuota"
        class="form-control ew-select<?= $Page->alicuota->isInvalidClass() ?>"
        data-select2-id="articulo_x_alicuota"
        data-table="articulo"
        data-field="x_alicuota"
        data-value-separator="<?= $Page->alicuota->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->alicuota->getPlaceHolder()) ?>"
        <?= $Page->alicuota->editAttributes() ?>>
        <?= $Page->alicuota->selectOptionListHtml("x_alicuota") ?>
    </select>
    <?= $Page->alicuota->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->alicuota->getErrorMessage() ?></div>
<?= $Page->alicuota->Lookup->getParamTag($Page, "p_x_alicuota") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='articulo_x_alicuota']"),
        options = { name: "x_alicuota", selectId: "articulo_x_alicuota", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.articulo.fields.alicuota.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->articulo_inventario->Visible) { // articulo_inventario ?>
    <div id="r_articulo_inventario" class="form-group row">
        <label id="elh_articulo_articulo_inventario" for="x_articulo_inventario" class="<?= $Page->LeftColumnClass ?>"><?= $Page->articulo_inventario->caption() ?><?= $Page->articulo_inventario->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->articulo_inventario->cellAttributes() ?>>
<span id="el_articulo_articulo_inventario">
    <select
        id="x_articulo_inventario"
        name="x_articulo_inventario"
        class="form-control ew-select<?= $Page->articulo_inventario->isInvalidClass() ?>"
        data-select2-id="articulo_x_articulo_inventario"
        data-table="articulo"
        data-field="x_articulo_inventario"
        data-value-separator="<?= $Page->articulo_inventario->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->articulo_inventario->getPlaceHolder()) ?>"
        <?= $Page->articulo_inventario->editAttributes() ?>>
        <?= $Page->articulo_inventario->selectOptionListHtml("x_articulo_inventario") ?>
    </select>
    <?= $Page->articulo_inventario->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->articulo_inventario->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='articulo_x_articulo_inventario']"),
        options = { name: "x_articulo_inventario", selectId: "articulo_x_articulo_inventario", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.articulo.fields.articulo_inventario.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.articulo.fields.articulo_inventario.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
    <div id="r_activo" class="form-group row">
        <label id="elh_articulo_activo" for="x_activo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->activo->caption() ?><?= $Page->activo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->activo->cellAttributes() ?>>
<span id="el_articulo_activo">
    <select
        id="x_activo"
        name="x_activo"
        class="form-control ew-select<?= $Page->activo->isInvalidClass() ?>"
        data-select2-id="articulo_x_activo"
        data-table="articulo"
        data-field="x_activo"
        data-value-separator="<?= $Page->activo->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->activo->getPlaceHolder()) ?>"
        <?= $Page->activo->editAttributes() ?>>
        <?= $Page->activo->selectOptionListHtml("x_activo") ?>
    </select>
    <?= $Page->activo->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->activo->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='articulo_x_activo']"),
        options = { name: "x_activo", selectId: "articulo_x_activo", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.articulo.fields.activo.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.articulo.fields.activo.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->puntos_ventas->Visible) { // puntos_ventas ?>
    <div id="r_puntos_ventas" class="form-group row">
        <label id="elh_articulo_puntos_ventas" for="x_puntos_ventas" class="<?= $Page->LeftColumnClass ?>"><?= $Page->puntos_ventas->caption() ?><?= $Page->puntos_ventas->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->puntos_ventas->cellAttributes() ?>>
<span id="el_articulo_puntos_ventas">
<input type="<?= $Page->puntos_ventas->getInputTextType() ?>" data-table="articulo" data-field="x_puntos_ventas" name="x_puntos_ventas" id="x_puntos_ventas" size="30" maxlength="11" placeholder="<?= HtmlEncode($Page->puntos_ventas->getPlaceHolder()) ?>" value="<?= $Page->puntos_ventas->EditValue ?>"<?= $Page->puntos_ventas->editAttributes() ?> aria-describedby="x_puntos_ventas_help">
<?= $Page->puntos_ventas->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->puntos_ventas->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->puntos_premio->Visible) { // puntos_premio ?>
    <div id="r_puntos_premio" class="form-group row">
        <label id="elh_articulo_puntos_premio" for="x_puntos_premio" class="<?= $Page->LeftColumnClass ?>"><?= $Page->puntos_premio->caption() ?><?= $Page->puntos_premio->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->puntos_premio->cellAttributes() ?>>
<span id="el_articulo_puntos_premio">
<input type="<?= $Page->puntos_premio->getInputTextType() ?>" data-table="articulo" data-field="x_puntos_premio" name="x_puntos_premio" id="x_puntos_premio" size="30" maxlength="11" placeholder="<?= HtmlEncode($Page->puntos_premio->getPlaceHolder()) ?>" value="<?= $Page->puntos_premio->EditValue ?>"<?= $Page->puntos_premio->editAttributes() ?> aria-describedby="x_puntos_premio_help">
<?= $Page->puntos_premio->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->puntos_premio->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->sincroniza->Visible) { // sincroniza ?>
    <div id="r_sincroniza" class="form-group row">
        <label id="elh_articulo_sincroniza" for="x_sincroniza" class="<?= $Page->LeftColumnClass ?>"><?= $Page->sincroniza->caption() ?><?= $Page->sincroniza->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->sincroniza->cellAttributes() ?>>
<span id="el_articulo_sincroniza">
    <select
        id="x_sincroniza"
        name="x_sincroniza"
        class="form-control ew-select<?= $Page->sincroniza->isInvalidClass() ?>"
        data-select2-id="articulo_x_sincroniza"
        data-table="articulo"
        data-field="x_sincroniza"
        data-value-separator="<?= $Page->sincroniza->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->sincroniza->getPlaceHolder()) ?>"
        <?= $Page->sincroniza->editAttributes() ?>>
        <?= $Page->sincroniza->selectOptionListHtml("x_sincroniza") ?>
    </select>
    <?= $Page->sincroniza->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->sincroniza->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='articulo_x_sincroniza']"),
        options = { name: "x_sincroniza", selectId: "articulo_x_sincroniza", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.articulo.fields.sincroniza.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.articulo.fields.sincroniza.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="articulo" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
<?php
    if (in_array("articulo_unidad_medida", explode(",", $Page->getCurrentDetailTable())) && $articulo_unidad_medida->DetailEdit) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("articulo_unidad_medida", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "ArticuloUnidadMedidaGrid.php" ?>
<?php } ?>
<?php
    if (in_array("adjunto", explode(",", $Page->getCurrentDetailTable())) && $adjunto->DetailEdit) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("adjunto", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "AdjuntoGrid.php" ?>
<?php } ?>
<?php if (!$Page->IsModal) { ?>
<div class="form-group row"><!-- buttons .form-group -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("SaveBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
    </div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<div class="clearfix"></div>
<?php } ?>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("articulo");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    function validad_codigo_proveedor(){if(""==$("#x_codigo_proveedor").val().trim())return!0;var o={codigo:$("#x_codigo_proveedor").val(),laboratorio:$("#x_laboratorio").val(),accion:"U"};$.ajax({data:o,url:"codigo_proveedor_buscar.php",type:"get",beforeSend:function(){},success:function(o){return"1"!=$(o).find("#outtext").text()||(alert('Código Proveedor "'+$("#x_codigo_proveedor").val()+'" ya existe para el laboratorio "'+$("#x_laboratorio").val()+'"'),$("#x_codigo_proveedor").val(""),$("#x_codigo_proveedor").focus(),!1)}})}$("#x_codigo").change((function(){if(""==$("#x_codigo").val().trim())return!0;var o={codigo:$("#x_codigo").val(),accion:"U"};$.ajax({data:o,url:"codigo_buscar.php",type:"get",beforeSend:function(){},success:function(o){return"1"!=$(o).find("#outtext").text()||(alert('Código "'+$("#x_codigo").val()+'" ya existe.'),$("#x_codigo").val(""),$("#x_codigo").focus(),!1)}})})),$("#x_codigo_proveedor").change((function(){validad_codigo_proveedor()})),$("#x_laboratorio").change((function(){validad_codigo_proveedor()}));
});
</script>
