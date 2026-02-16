<?php

namespace PHPMaker2021\mandrake;

// Page object
$TempConsignacionAdd = &$Page;
?>
<script>
var currentForm, currentPageID;
var ftemp_consignacionadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    ftemp_consignacionadd = currentForm = new ew.Form("ftemp_consignacionadd", "add");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "temp_consignacion")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.temp_consignacion)
        ew.vars.tables.temp_consignacion = currentTable;
    ftemp_consignacionadd.addFields([
        ["_username", [fields._username.visible && fields._username.required ? ew.Validators.required(fields._username.caption) : null], fields._username.isInvalid],
        ["nro_documento", [fields.nro_documento.visible && fields.nro_documento.required ? ew.Validators.required(fields.nro_documento.caption) : null], fields.nro_documento.isInvalid],
        ["id_documento", [fields.id_documento.visible && fields.id_documento.required ? ew.Validators.required(fields.id_documento.caption) : null, ew.Validators.integer], fields.id_documento.isInvalid],
        ["tipo_documento", [fields.tipo_documento.visible && fields.tipo_documento.required ? ew.Validators.required(fields.tipo_documento.caption) : null], fields.tipo_documento.isInvalid],
        ["fabricante", [fields.fabricante.visible && fields.fabricante.required ? ew.Validators.required(fields.fabricante.caption) : null, ew.Validators.integer], fields.fabricante.isInvalid],
        ["articulo", [fields.articulo.visible && fields.articulo.required ? ew.Validators.required(fields.articulo.caption) : null, ew.Validators.integer], fields.articulo.isInvalid],
        ["cantidad_movimiento", [fields.cantidad_movimiento.visible && fields.cantidad_movimiento.required ? ew.Validators.required(fields.cantidad_movimiento.caption) : null, ew.Validators.float], fields.cantidad_movimiento.isInvalid],
        ["cantidad_entre_fechas", [fields.cantidad_entre_fechas.visible && fields.cantidad_entre_fechas.required ? ew.Validators.required(fields.cantidad_entre_fechas.caption) : null, ew.Validators.float], fields.cantidad_entre_fechas.isInvalid],
        ["cantidad_acumulada", [fields.cantidad_acumulada.visible && fields.cantidad_acumulada.required ? ew.Validators.required(fields.cantidad_acumulada.caption) : null, ew.Validators.float], fields.cantidad_acumulada.isInvalid],
        ["cantidad_ajuste", [fields.cantidad_ajuste.visible && fields.cantidad_ajuste.required ? ew.Validators.required(fields.cantidad_ajuste.caption) : null, ew.Validators.float], fields.cantidad_ajuste.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = ftemp_consignacionadd,
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
    ftemp_consignacionadd.validate = function () {
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
    ftemp_consignacionadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    ftemp_consignacionadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("ftemp_consignacionadd");
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
<form name="ftemp_consignacionadd" id="ftemp_consignacionadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="temp_consignacion">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->_username->Visible) { // username ?>
    <div id="r__username" class="form-group row">
        <label id="elh_temp_consignacion__username" for="x__username" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_username->caption() ?><?= $Page->_username->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->_username->cellAttributes() ?>>
<span id="el_temp_consignacion__username">
<input type="<?= $Page->_username->getInputTextType() ?>" data-table="temp_consignacion" data-field="x__username" name="x__username" id="x__username" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->_username->getPlaceHolder()) ?>" value="<?= $Page->_username->EditValue ?>"<?= $Page->_username->editAttributes() ?> aria-describedby="x__username_help">
<?= $Page->_username->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_username->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
    <div id="r_nro_documento" class="form-group row">
        <label id="elh_temp_consignacion_nro_documento" for="x_nro_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nro_documento->caption() ?><?= $Page->nro_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nro_documento->cellAttributes() ?>>
<span id="el_temp_consignacion_nro_documento">
<input type="<?= $Page->nro_documento->getInputTextType() ?>" data-table="temp_consignacion" data-field="x_nro_documento" name="x_nro_documento" id="x_nro_documento" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->nro_documento->getPlaceHolder()) ?>" value="<?= $Page->nro_documento->EditValue ?>"<?= $Page->nro_documento->editAttributes() ?> aria-describedby="x_nro_documento_help">
<?= $Page->nro_documento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nro_documento->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->id_documento->Visible) { // id_documento ?>
    <div id="r_id_documento" class="form-group row">
        <label id="elh_temp_consignacion_id_documento" for="x_id_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id_documento->caption() ?><?= $Page->id_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->id_documento->cellAttributes() ?>>
<span id="el_temp_consignacion_id_documento">
<input type="<?= $Page->id_documento->getInputTextType() ?>" data-table="temp_consignacion" data-field="x_id_documento" name="x_id_documento" id="x_id_documento" size="30" placeholder="<?= HtmlEncode($Page->id_documento->getPlaceHolder()) ?>" value="<?= $Page->id_documento->EditValue ?>"<?= $Page->id_documento->editAttributes() ?> aria-describedby="x_id_documento_help">
<?= $Page->id_documento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->id_documento->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
    <div id="r_tipo_documento" class="form-group row">
        <label id="elh_temp_consignacion_tipo_documento" for="x_tipo_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo_documento->caption() ?><?= $Page->tipo_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tipo_documento->cellAttributes() ?>>
<span id="el_temp_consignacion_tipo_documento">
<input type="<?= $Page->tipo_documento->getInputTextType() ?>" data-table="temp_consignacion" data-field="x_tipo_documento" name="x_tipo_documento" id="x_tipo_documento" size="30" maxlength="6" placeholder="<?= HtmlEncode($Page->tipo_documento->getPlaceHolder()) ?>" value="<?= $Page->tipo_documento->EditValue ?>"<?= $Page->tipo_documento->editAttributes() ?> aria-describedby="x_tipo_documento_help">
<?= $Page->tipo_documento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->tipo_documento->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fabricante->Visible) { // fabricante ?>
    <div id="r_fabricante" class="form-group row">
        <label id="elh_temp_consignacion_fabricante" for="x_fabricante" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fabricante->caption() ?><?= $Page->fabricante->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->fabricante->cellAttributes() ?>>
<span id="el_temp_consignacion_fabricante">
<input type="<?= $Page->fabricante->getInputTextType() ?>" data-table="temp_consignacion" data-field="x_fabricante" name="x_fabricante" id="x_fabricante" size="30" placeholder="<?= HtmlEncode($Page->fabricante->getPlaceHolder()) ?>" value="<?= $Page->fabricante->EditValue ?>"<?= $Page->fabricante->editAttributes() ?> aria-describedby="x_fabricante_help">
<?= $Page->fabricante->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fabricante->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->articulo->Visible) { // articulo ?>
    <div id="r_articulo" class="form-group row">
        <label id="elh_temp_consignacion_articulo" for="x_articulo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->articulo->caption() ?><?= $Page->articulo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->articulo->cellAttributes() ?>>
<span id="el_temp_consignacion_articulo">
<input type="<?= $Page->articulo->getInputTextType() ?>" data-table="temp_consignacion" data-field="x_articulo" name="x_articulo" id="x_articulo" size="30" placeholder="<?= HtmlEncode($Page->articulo->getPlaceHolder()) ?>" value="<?= $Page->articulo->EditValue ?>"<?= $Page->articulo->editAttributes() ?> aria-describedby="x_articulo_help">
<?= $Page->articulo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->articulo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cantidad_movimiento->Visible) { // cantidad_movimiento ?>
    <div id="r_cantidad_movimiento" class="form-group row">
        <label id="elh_temp_consignacion_cantidad_movimiento" for="x_cantidad_movimiento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cantidad_movimiento->caption() ?><?= $Page->cantidad_movimiento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cantidad_movimiento->cellAttributes() ?>>
<span id="el_temp_consignacion_cantidad_movimiento">
<input type="<?= $Page->cantidad_movimiento->getInputTextType() ?>" data-table="temp_consignacion" data-field="x_cantidad_movimiento" name="x_cantidad_movimiento" id="x_cantidad_movimiento" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->cantidad_movimiento->getPlaceHolder()) ?>" value="<?= $Page->cantidad_movimiento->EditValue ?>"<?= $Page->cantidad_movimiento->editAttributes() ?> aria-describedby="x_cantidad_movimiento_help">
<?= $Page->cantidad_movimiento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cantidad_movimiento->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cantidad_entre_fechas->Visible) { // cantidad_entre_fechas ?>
    <div id="r_cantidad_entre_fechas" class="form-group row">
        <label id="elh_temp_consignacion_cantidad_entre_fechas" for="x_cantidad_entre_fechas" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cantidad_entre_fechas->caption() ?><?= $Page->cantidad_entre_fechas->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cantidad_entre_fechas->cellAttributes() ?>>
<span id="el_temp_consignacion_cantidad_entre_fechas">
<input type="<?= $Page->cantidad_entre_fechas->getInputTextType() ?>" data-table="temp_consignacion" data-field="x_cantidad_entre_fechas" name="x_cantidad_entre_fechas" id="x_cantidad_entre_fechas" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->cantidad_entre_fechas->getPlaceHolder()) ?>" value="<?= $Page->cantidad_entre_fechas->EditValue ?>"<?= $Page->cantidad_entre_fechas->editAttributes() ?> aria-describedby="x_cantidad_entre_fechas_help">
<?= $Page->cantidad_entre_fechas->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cantidad_entre_fechas->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cantidad_acumulada->Visible) { // cantidad_acumulada ?>
    <div id="r_cantidad_acumulada" class="form-group row">
        <label id="elh_temp_consignacion_cantidad_acumulada" for="x_cantidad_acumulada" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cantidad_acumulada->caption() ?><?= $Page->cantidad_acumulada->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cantidad_acumulada->cellAttributes() ?>>
<span id="el_temp_consignacion_cantidad_acumulada">
<input type="<?= $Page->cantidad_acumulada->getInputTextType() ?>" data-table="temp_consignacion" data-field="x_cantidad_acumulada" name="x_cantidad_acumulada" id="x_cantidad_acumulada" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->cantidad_acumulada->getPlaceHolder()) ?>" value="<?= $Page->cantidad_acumulada->EditValue ?>"<?= $Page->cantidad_acumulada->editAttributes() ?> aria-describedby="x_cantidad_acumulada_help">
<?= $Page->cantidad_acumulada->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cantidad_acumulada->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cantidad_ajuste->Visible) { // cantidad_ajuste ?>
    <div id="r_cantidad_ajuste" class="form-group row">
        <label id="elh_temp_consignacion_cantidad_ajuste" for="x_cantidad_ajuste" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cantidad_ajuste->caption() ?><?= $Page->cantidad_ajuste->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cantidad_ajuste->cellAttributes() ?>>
<span id="el_temp_consignacion_cantidad_ajuste">
<input type="<?= $Page->cantidad_ajuste->getInputTextType() ?>" data-table="temp_consignacion" data-field="x_cantidad_ajuste" name="x_cantidad_ajuste" id="x_cantidad_ajuste" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->cantidad_ajuste->getPlaceHolder()) ?>" value="<?= $Page->cantidad_ajuste->EditValue ?>"<?= $Page->cantidad_ajuste->editAttributes() ?> aria-describedby="x_cantidad_ajuste_help">
<?= $Page->cantidad_ajuste->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cantidad_ajuste->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$Page->IsModal) { ?>
<div class="form-group row"><!-- buttons .form-group -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("AddBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
    </div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("temp_consignacion");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
