<?php

namespace PHPMaker2021\mandrake;

// Page object
$RecargaAdd = &$Page;
?>
<script>
var currentForm, currentPageID;
var frecargaadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    frecargaadd = currentForm = new ew.Form("frecargaadd", "add");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "recarga")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.recarga)
        ew.vars.tables.recarga = currentTable;
    frecargaadd.addFields([
        ["cliente", [fields.cliente.visible && fields.cliente.required ? ew.Validators.required(fields.cliente.caption) : null], fields.cliente.isInvalid],
        ["metodo_pago", [fields.metodo_pago.visible && fields.metodo_pago.required ? ew.Validators.required(fields.metodo_pago.caption) : null], fields.metodo_pago.isInvalid],
        ["referencia", [fields.referencia.visible && fields.referencia.required ? ew.Validators.required(fields.referencia.caption) : null], fields.referencia.isInvalid],
        ["reverso", [fields.reverso.visible && fields.reverso.required ? ew.Validators.required(fields.reverso.caption) : null], fields.reverso.isInvalid],
        ["monto_moneda", [fields.monto_moneda.visible && fields.monto_moneda.required ? ew.Validators.required(fields.monto_moneda.caption) : null, ew.Validators.float], fields.monto_moneda.isInvalid],
        ["moneda", [fields.moneda.visible && fields.moneda.required ? ew.Validators.required(fields.moneda.caption) : null], fields.moneda.isInvalid],
        ["nota", [fields.nota.visible && fields.nota.required ? ew.Validators.required(fields.nota.caption) : null], fields.nota.isInvalid],
        ["abono", [fields.abono.visible && fields.abono.required ? ew.Validators.required(fields.abono.caption) : null, ew.Validators.integer], fields.abono.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = frecargaadd,
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
    frecargaadd.validate = function () {
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
    frecargaadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    frecargaadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    frecargaadd.lists.cliente = <?= $Page->cliente->toClientList($Page) ?>;
    frecargaadd.lists.metodo_pago = <?= $Page->metodo_pago->toClientList($Page) ?>;
    frecargaadd.lists.reverso = <?= $Page->reverso->toClientList($Page) ?>;
    frecargaadd.lists.moneda = <?= $Page->moneda->toClientList($Page) ?>;
    loadjs.done("frecargaadd");
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
<form name="frecargaadd" id="frecargaadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="recarga">
<?php if ($Page->isConfirm()) { // Confirm page ?>
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="confirm" id="confirm" value="confirm">
<?php } else { ?>
<input type="hidden" name="action" id="action" value="confirm">
<?php } ?>
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "abono") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="abono">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->abono->getSessionValue()) ?>">
<?php } ?>
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->cliente->Visible) { // cliente ?>
    <div id="r_cliente" class="form-group row">
        <label id="elh_recarga_cliente" for="x_cliente" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cliente->caption() ?><?= $Page->cliente->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cliente->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_recarga_cliente">
<div class="input-group ew-lookup-list" aria-describedby="x_cliente_help">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x_cliente"><?= EmptyValue(strval($Page->cliente->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->cliente->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->cliente->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->cliente->ReadOnly || $Page->cliente->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x_cliente',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
        <?php if (AllowAdd(CurrentProjectID() . "cliente") && !$Page->cliente->ReadOnly) { ?>
        <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_cliente" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->cliente->caption() ?>" data-title="<?= $Page->cliente->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x_cliente',url:'<?= GetUrl("ClienteAddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button>
        <?php } ?>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->cliente->getErrorMessage() ?></div>
<?= $Page->cliente->getCustomMessage() ?>
<?= $Page->cliente->Lookup->getParamTag($Page, "p_x_cliente") ?>
<input type="hidden" is="selection-list" data-table="recarga" data-field="x_cliente" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->cliente->displayValueSeparatorAttribute() ?>" name="x_cliente" id="x_cliente" value="<?= $Page->cliente->CurrentValue ?>"<?= $Page->cliente->editAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_recarga_cliente">
<span<?= $Page->cliente->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->cliente->getDisplayValue($Page->cliente->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="recarga" data-field="x_cliente" data-hidden="1" name="x_cliente" id="x_cliente" value="<?= HtmlEncode($Page->cliente->FormValue) ?>">
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->metodo_pago->Visible) { // metodo_pago ?>
    <div id="r_metodo_pago" class="form-group row">
        <label id="elh_recarga_metodo_pago" for="x_metodo_pago" class="<?= $Page->LeftColumnClass ?>"><?= $Page->metodo_pago->caption() ?><?= $Page->metodo_pago->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->metodo_pago->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_recarga_metodo_pago">
    <select
        id="x_metodo_pago"
        name="x_metodo_pago"
        class="form-control ew-select<?= $Page->metodo_pago->isInvalidClass() ?>"
        data-select2-id="recarga_x_metodo_pago"
        data-table="recarga"
        data-field="x_metodo_pago"
        data-value-separator="<?= $Page->metodo_pago->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->metodo_pago->getPlaceHolder()) ?>"
        <?= $Page->metodo_pago->editAttributes() ?>>
        <?= $Page->metodo_pago->selectOptionListHtml("x_metodo_pago") ?>
    </select>
    <?= $Page->metodo_pago->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->metodo_pago->getErrorMessage() ?></div>
<?= $Page->metodo_pago->Lookup->getParamTag($Page, "p_x_metodo_pago") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='recarga_x_metodo_pago']"),
        options = { name: "x_metodo_pago", selectId: "recarga_x_metodo_pago", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.recarga.fields.metodo_pago.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } else { ?>
<span id="el_recarga_metodo_pago">
<span<?= $Page->metodo_pago->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->metodo_pago->getDisplayValue($Page->metodo_pago->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="recarga" data-field="x_metodo_pago" data-hidden="1" name="x_metodo_pago" id="x_metodo_pago" value="<?= HtmlEncode($Page->metodo_pago->FormValue) ?>">
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
    <div id="r_referencia" class="form-group row">
        <label id="elh_recarga_referencia" for="x_referencia" class="<?= $Page->LeftColumnClass ?>"><?= $Page->referencia->caption() ?><?= $Page->referencia->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->referencia->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_recarga_referencia">
<input type="<?= $Page->referencia->getInputTextType() ?>" data-table="recarga" data-field="x_referencia" name="x_referencia" id="x_referencia" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->referencia->getPlaceHolder()) ?>" value="<?= $Page->referencia->EditValue ?>"<?= $Page->referencia->editAttributes() ?> aria-describedby="x_referencia_help">
<?= $Page->referencia->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->referencia->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_recarga_referencia">
<span<?= $Page->referencia->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->referencia->getDisplayValue($Page->referencia->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="recarga" data-field="x_referencia" data-hidden="1" name="x_referencia" id="x_referencia" value="<?= HtmlEncode($Page->referencia->FormValue) ?>">
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->reverso->Visible) { // reverso ?>
    <div id="r_reverso" class="form-group row">
        <label id="elh_recarga_reverso" for="x_reverso" class="<?= $Page->LeftColumnClass ?>"><?= $Page->reverso->caption() ?><?= $Page->reverso->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->reverso->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_recarga_reverso">
    <select
        id="x_reverso"
        name="x_reverso"
        class="form-control ew-select<?= $Page->reverso->isInvalidClass() ?>"
        data-select2-id="recarga_x_reverso"
        data-table="recarga"
        data-field="x_reverso"
        data-value-separator="<?= $Page->reverso->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->reverso->getPlaceHolder()) ?>"
        <?= $Page->reverso->editAttributes() ?>>
        <?= $Page->reverso->selectOptionListHtml("x_reverso") ?>
    </select>
    <?= $Page->reverso->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->reverso->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='recarga_x_reverso']"),
        options = { name: "x_reverso", selectId: "recarga_x_reverso", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.recarga.fields.reverso.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.recarga.fields.reverso.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } else { ?>
<span id="el_recarga_reverso">
<span<?= $Page->reverso->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->reverso->getDisplayValue($Page->reverso->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="recarga" data-field="x_reverso" data-hidden="1" name="x_reverso" id="x_reverso" value="<?= HtmlEncode($Page->reverso->FormValue) ?>">
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->monto_moneda->Visible) { // monto_moneda ?>
    <div id="r_monto_moneda" class="form-group row">
        <label id="elh_recarga_monto_moneda" for="x_monto_moneda" class="<?= $Page->LeftColumnClass ?>"><?= $Page->monto_moneda->caption() ?><?= $Page->monto_moneda->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->monto_moneda->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_recarga_monto_moneda">
<input type="<?= $Page->monto_moneda->getInputTextType() ?>" data-table="recarga" data-field="x_monto_moneda" name="x_monto_moneda" id="x_monto_moneda" size="30" maxlength="14" placeholder="<?= HtmlEncode($Page->monto_moneda->getPlaceHolder()) ?>" value="<?= $Page->monto_moneda->EditValue ?>"<?= $Page->monto_moneda->editAttributes() ?> aria-describedby="x_monto_moneda_help">
<?= $Page->monto_moneda->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->monto_moneda->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_recarga_monto_moneda">
<span<?= $Page->monto_moneda->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->monto_moneda->getDisplayValue($Page->monto_moneda->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="recarga" data-field="x_monto_moneda" data-hidden="1" name="x_monto_moneda" id="x_monto_moneda" value="<?= HtmlEncode($Page->monto_moneda->FormValue) ?>">
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <div id="r_moneda" class="form-group row">
        <label id="elh_recarga_moneda" for="x_moneda" class="<?= $Page->LeftColumnClass ?>"><?= $Page->moneda->caption() ?><?= $Page->moneda->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->moneda->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_recarga_moneda">
    <select
        id="x_moneda"
        name="x_moneda"
        class="form-control ew-select<?= $Page->moneda->isInvalidClass() ?>"
        data-select2-id="recarga_x_moneda"
        data-table="recarga"
        data-field="x_moneda"
        data-value-separator="<?= $Page->moneda->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->moneda->getPlaceHolder()) ?>"
        <?= $Page->moneda->editAttributes() ?>>
        <?= $Page->moneda->selectOptionListHtml("x_moneda") ?>
    </select>
    <?= $Page->moneda->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->moneda->getErrorMessage() ?></div>
<?= $Page->moneda->Lookup->getParamTag($Page, "p_x_moneda") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='recarga_x_moneda']"),
        options = { name: "x_moneda", selectId: "recarga_x_moneda", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.recarga.fields.moneda.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } else { ?>
<span id="el_recarga_moneda">
<span<?= $Page->moneda->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->moneda->getDisplayValue($Page->moneda->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="recarga" data-field="x_moneda" data-hidden="1" name="x_moneda" id="x_moneda" value="<?= HtmlEncode($Page->moneda->FormValue) ?>">
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
    <div id="r_nota" class="form-group row">
        <label id="elh_recarga_nota" for="x_nota" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nota->caption() ?><?= $Page->nota->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nota->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_recarga_nota">
<textarea data-table="recarga" data-field="x_nota" name="x_nota" id="x_nota" cols="35" rows="3" placeholder="<?= HtmlEncode($Page->nota->getPlaceHolder()) ?>"<?= $Page->nota->editAttributes() ?> aria-describedby="x_nota_help"><?= $Page->nota->EditValue ?></textarea>
<?= $Page->nota->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nota->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_recarga_nota">
<span<?= $Page->nota->viewAttributes() ?>>
<?= $Page->nota->ViewValue ?></span>
</span>
<input type="hidden" data-table="recarga" data-field="x_nota" data-hidden="1" name="x_nota" id="x_nota" value="<?= HtmlEncode($Page->nota->FormValue) ?>">
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->abono->Visible) { // abono ?>
    <div id="r_abono" class="form-group row">
        <label id="elh_recarga_abono" for="x_abono" class="<?= $Page->LeftColumnClass ?>"><?= $Page->abono->caption() ?><?= $Page->abono->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->abono->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<?php if ($Page->abono->getSessionValue() != "") { ?>
<span id="el_recarga_abono">
<span<?= $Page->abono->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->abono->getDisplayValue($Page->abono->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x_abono" name="x_abono" value="<?= HtmlEncode($Page->abono->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el_recarga_abono">
<input type="<?= $Page->abono->getInputTextType() ?>" data-table="recarga" data-field="x_abono" name="x_abono" id="x_abono" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->abono->getPlaceHolder()) ?>" value="<?= $Page->abono->EditValue ?>"<?= $Page->abono->editAttributes() ?> aria-describedby="x_abono_help">
<?= $Page->abono->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->abono->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php } else { ?>
<span id="el_recarga_abono">
<span<?= $Page->abono->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->abono->getDisplayValue($Page->abono->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="recarga" data-field="x_abono" data-hidden="1" name="x_abono" id="x_abono" value="<?= HtmlEncode($Page->abono->FormValue) ?>">
<?php } ?>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$Page->IsModal) { ?>
<div class="form-group row"><!-- buttons .form-group -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<?php if (!$Page->isConfirm()) { // Confirm page ?>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" onclick="this.form.action.value='confirm';"><?= $Language->phrase("AddBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("ConfirmBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="submit" onclick="this.form.action.value='cancel';"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
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
    ew.addEventHandlers("recarga");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
