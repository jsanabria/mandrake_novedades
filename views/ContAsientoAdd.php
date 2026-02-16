<?php

namespace PHPMaker2021\mandrake;

// Page object
$ContAsientoAdd = &$Page;
?>
<script>
var currentForm, currentPageID;
var fcont_asientoadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    fcont_asientoadd = currentForm = new ew.Form("fcont_asientoadd", "add");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "cont_asiento")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.cont_asiento)
        ew.vars.tables.cont_asiento = currentTable;
    fcont_asientoadd.addFields([
        ["comprobante", [fields.comprobante.visible && fields.comprobante.required ? ew.Validators.required(fields.comprobante.caption) : null, ew.Validators.integer], fields.comprobante.isInvalid],
        ["cuenta", [fields.cuenta.visible && fields.cuenta.required ? ew.Validators.required(fields.cuenta.caption) : null], fields.cuenta.isInvalid],
        ["nota", [fields.nota.visible && fields.nota.required ? ew.Validators.required(fields.nota.caption) : null], fields.nota.isInvalid],
        ["referencia", [fields.referencia.visible && fields.referencia.required ? ew.Validators.required(fields.referencia.caption) : null], fields.referencia.isInvalid],
        ["debe", [fields.debe.visible && fields.debe.required ? ew.Validators.required(fields.debe.caption) : null, ew.Validators.float], fields.debe.isInvalid],
        ["haber", [fields.haber.visible && fields.haber.required ? ew.Validators.required(fields.haber.caption) : null, ew.Validators.float], fields.haber.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fcont_asientoadd,
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
    fcont_asientoadd.validate = function () {
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
    fcont_asientoadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fcont_asientoadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fcont_asientoadd.lists.cuenta = <?= $Page->cuenta->toClientList($Page) ?>;
    loadjs.done("fcont_asientoadd");
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
<form name="fcont_asientoadd" id="fcont_asientoadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cont_asiento">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "cont_comprobante") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="cont_comprobante">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->comprobante->getSessionValue()) ?>">
<?php } ?>
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->comprobante->Visible) { // comprobante ?>
    <div id="r_comprobante" class="form-group row">
        <label id="elh_cont_asiento_comprobante" for="x_comprobante" class="<?= $Page->LeftColumnClass ?>"><?= $Page->comprobante->caption() ?><?= $Page->comprobante->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->comprobante->cellAttributes() ?>>
<?php if ($Page->comprobante->getSessionValue() != "") { ?>
<span id="el_cont_asiento_comprobante">
<span<?= $Page->comprobante->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->comprobante->getDisplayValue($Page->comprobante->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x_comprobante" name="x_comprobante" value="<?= HtmlEncode($Page->comprobante->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el_cont_asiento_comprobante">
<input type="<?= $Page->comprobante->getInputTextType() ?>" data-table="cont_asiento" data-field="x_comprobante" name="x_comprobante" id="x_comprobante" size="30" placeholder="<?= HtmlEncode($Page->comprobante->getPlaceHolder()) ?>" value="<?= $Page->comprobante->EditValue ?>"<?= $Page->comprobante->editAttributes() ?> aria-describedby="x_comprobante_help">
<?= $Page->comprobante->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->comprobante->getErrorMessage() ?></div>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cuenta->Visible) { // cuenta ?>
    <div id="r_cuenta" class="form-group row">
        <label id="elh_cont_asiento_cuenta" for="x_cuenta" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cuenta->caption() ?><?= $Page->cuenta->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cuenta->cellAttributes() ?>>
<span id="el_cont_asiento_cuenta">
<div class="input-group ew-lookup-list" aria-describedby="x_cuenta_help">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x_cuenta"><?= EmptyValue(strval($Page->cuenta->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->cuenta->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->cuenta->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->cuenta->ReadOnly || $Page->cuenta->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x_cuenta',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->cuenta->getErrorMessage() ?></div>
<?= $Page->cuenta->getCustomMessage() ?>
<?= $Page->cuenta->Lookup->getParamTag($Page, "p_x_cuenta") ?>
<input type="hidden" is="selection-list" data-table="cont_asiento" data-field="x_cuenta" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->cuenta->displayValueSeparatorAttribute() ?>" name="x_cuenta" id="x_cuenta" value="<?= $Page->cuenta->CurrentValue ?>"<?= $Page->cuenta->editAttributes() ?>>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
    <div id="r_nota" class="form-group row">
        <label id="elh_cont_asiento_nota" for="x_nota" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nota->caption() ?><?= $Page->nota->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nota->cellAttributes() ?>>
<span id="el_cont_asiento_nota">
<input type="<?= $Page->nota->getInputTextType() ?>" data-table="cont_asiento" data-field="x_nota" name="x_nota" id="x_nota" size="10" placeholder="<?= HtmlEncode($Page->nota->getPlaceHolder()) ?>" value="<?= $Page->nota->EditValue ?>"<?= $Page->nota->editAttributes() ?> aria-describedby="x_nota_help">
<?= $Page->nota->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nota->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
    <div id="r_referencia" class="form-group row">
        <label id="elh_cont_asiento_referencia" for="x_referencia" class="<?= $Page->LeftColumnClass ?>"><?= $Page->referencia->caption() ?><?= $Page->referencia->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->referencia->cellAttributes() ?>>
<span id="el_cont_asiento_referencia">
<input type="<?= $Page->referencia->getInputTextType() ?>" data-table="cont_asiento" data-field="x_referencia" name="x_referencia" id="x_referencia" size="10" maxlength="25" placeholder="<?= HtmlEncode($Page->referencia->getPlaceHolder()) ?>" value="<?= $Page->referencia->EditValue ?>"<?= $Page->referencia->editAttributes() ?> aria-describedby="x_referencia_help">
<?= $Page->referencia->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->referencia->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->debe->Visible) { // debe ?>
    <div id="r_debe" class="form-group row">
        <label id="elh_cont_asiento_debe" for="x_debe" class="<?= $Page->LeftColumnClass ?>"><?= $Page->debe->caption() ?><?= $Page->debe->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->debe->cellAttributes() ?>>
<span id="el_cont_asiento_debe">
<input type="<?= $Page->debe->getInputTextType() ?>" data-table="cont_asiento" data-field="x_debe" name="x_debe" id="x_debe" size="12" maxlength="16" placeholder="<?= HtmlEncode($Page->debe->getPlaceHolder()) ?>" value="<?= $Page->debe->EditValue ?>"<?= $Page->debe->editAttributes() ?> aria-describedby="x_debe_help">
<?= $Page->debe->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->debe->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->haber->Visible) { // haber ?>
    <div id="r_haber" class="form-group row">
        <label id="elh_cont_asiento_haber" for="x_haber" class="<?= $Page->LeftColumnClass ?>"><?= $Page->haber->caption() ?><?= $Page->haber->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->haber->cellAttributes() ?>>
<span id="el_cont_asiento_haber">
<input type="<?= $Page->haber->getInputTextType() ?>" data-table="cont_asiento" data-field="x_haber" name="x_haber" id="x_haber" size="12" maxlength="16" placeholder="<?= HtmlEncode($Page->haber->getPlaceHolder()) ?>" value="<?= $Page->haber->EditValue ?>"<?= $Page->haber->editAttributes() ?> aria-describedby="x_haber_help">
<?= $Page->haber->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->haber->getErrorMessage() ?></div>
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
    ew.addEventHandlers("cont_asiento");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
