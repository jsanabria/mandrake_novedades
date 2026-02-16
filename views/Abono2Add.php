<?php

namespace PHPMaker2021\mandrake;

// Page object
$Abono2Add = &$Page;
?>
<script>
var currentForm, currentPageID;
var fabono2add;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    fabono2add = currentForm = new ew.Form("fabono2add", "add");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "abono2")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.abono2)
        ew.vars.tables.abono2 = currentTable;
    fabono2add.addFields([
        ["cliente", [fields.cliente.visible && fields.cliente.required ? ew.Validators.required(fields.cliente.caption) : null, ew.Validators.integer], fields.cliente.isInvalid],
        ["pago", [fields.pago.visible && fields.pago.required ? ew.Validators.required(fields.pago.caption) : null, ew.Validators.float], fields.pago.isInvalid],
        ["nota", [fields.nota.visible && fields.nota.required ? ew.Validators.required(fields.nota.caption) : null], fields.nota.isInvalid],
        ["metodo_pago", [fields.metodo_pago.visible && fields.metodo_pago.required ? ew.Validators.required(fields.metodo_pago.caption) : null], fields.metodo_pago.isInvalid],
        ["pivote", [fields.pivote.visible && fields.pivote.required ? ew.Validators.required(fields.pivote.caption) : null], fields.pivote.isInvalid],
        ["pivote2", [fields.pivote2.visible && fields.pivote2.required ? ew.Validators.required(fields.pivote2.caption) : null], fields.pivote2.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fabono2add,
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
    fabono2add.validate = function () {
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
    fabono2add.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fabono2add.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fabono2add.lists.cliente = <?= $Page->cliente->toClientList($Page) ?>;
    fabono2add.lists.metodo_pago = <?= $Page->metodo_pago->toClientList($Page) ?>;
    loadjs.done("fabono2add");
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
<form name="fabono2add" id="fabono2add" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="abono2">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->cliente->Visible) { // cliente ?>
    <div id="r_cliente" class="form-group row">
        <label id="elh_abono2_cliente" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cliente->caption() ?><?= $Page->cliente->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cliente->cellAttributes() ?>>
<span id="el_abono2_cliente">
<?php
$onchange = $Page->cliente->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Page->cliente->EditAttrs["onchange"] = "";
?>
<span id="as_x_cliente" class="ew-auto-suggest">
    <div class="input-group flex-nowrap">
        <input type="<?= $Page->cliente->getInputTextType() ?>" class="form-control" name="sv_x_cliente" id="sv_x_cliente" value="<?= RemoveHtml($Page->cliente->EditValue) ?>" size="30" maxlength="11" placeholder="<?= HtmlEncode($Page->cliente->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->cliente->getPlaceHolder()) ?>"<?= $Page->cliente->editAttributes() ?> aria-describedby="x_cliente_help">
        <div class="input-group-append">
            <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->cliente->caption()), $Language->phrase("LookupLink", true))) ?>" onclick="ew.modalLookupShow({lnk:this,el:'x_cliente',m:0,n:10,srch:false});" class="ew-lookup-btn btn btn-default"<?= ($Page->cliente->ReadOnly || $Page->cliente->Disabled) ? " disabled" : "" ?>><i class="fas fa-search ew-icon"></i></button>
            <?php if (AllowAdd(CurrentProjectID() . "cliente") && !$Page->cliente->ReadOnly) { ?>
            <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_cliente" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->cliente->caption() ?>" data-title="<?= $Page->cliente->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x_cliente',url:'<?= GetUrl("ClienteAddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button>
            <?php } ?>
        </div>
    </div>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="abono2" data-field="x_cliente" data-input="sv_x_cliente" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->cliente->displayValueSeparatorAttribute() ?>" name="x_cliente" id="x_cliente" value="<?= HtmlEncode($Page->cliente->CurrentValue) ?>"<?= $onchange ?>>
<?= $Page->cliente->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cliente->getErrorMessage() ?></div>
<script>
loadjs.ready(["fabono2add"], function() {
    fabono2add.createAutoSuggest(Object.assign({"id":"x_cliente","forceSelect":true}, ew.vars.tables.abono2.fields.cliente.autoSuggestOptions));
});
</script>
<?= $Page->cliente->Lookup->getParamTag($Page, "p_x_cliente") ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->pago->Visible) { // pago ?>
    <div id="r_pago" class="form-group row">
        <label id="elh_abono2_pago" for="x_pago" class="<?= $Page->LeftColumnClass ?>"><?= $Page->pago->caption() ?><?= $Page->pago->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->pago->cellAttributes() ?>>
<span id="el_abono2_pago">
<input type="<?= $Page->pago->getInputTextType() ?>" data-table="abono2" data-field="x_pago" name="x_pago" id="x_pago" size="30" maxlength="14" placeholder="<?= HtmlEncode($Page->pago->getPlaceHolder()) ?>" value="<?= $Page->pago->EditValue ?>"<?= $Page->pago->editAttributes() ?> aria-describedby="x_pago_help">
<?= $Page->pago->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->pago->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
    <div id="r_nota" class="form-group row">
        <label id="elh_abono2_nota" for="x_nota" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nota->caption() ?><?= $Page->nota->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nota->cellAttributes() ?>>
<span id="el_abono2_nota">
<input type="<?= $Page->nota->getInputTextType() ?>" data-table="abono2" data-field="x_nota" name="x_nota" id="x_nota" size="30" maxlength="250" placeholder="<?= HtmlEncode($Page->nota->getPlaceHolder()) ?>" value="<?= $Page->nota->EditValue ?>"<?= $Page->nota->editAttributes() ?> aria-describedby="x_nota_help">
<?= $Page->nota->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nota->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->metodo_pago->Visible) { // metodo_pago ?>
    <div id="r_metodo_pago" class="form-group row">
        <label id="elh_abono2_metodo_pago" for="x_metodo_pago" class="<?= $Page->LeftColumnClass ?>"><?= $Page->metodo_pago->caption() ?><?= $Page->metodo_pago->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->metodo_pago->cellAttributes() ?>>
<span id="el_abono2_metodo_pago">
    <select
        id="x_metodo_pago"
        name="x_metodo_pago"
        class="form-control ew-select<?= $Page->metodo_pago->isInvalidClass() ?>"
        data-select2-id="abono2_x_metodo_pago"
        data-table="abono2"
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
    var el = document.querySelector("select[data-select2-id='abono2_x_metodo_pago']"),
        options = { name: "x_metodo_pago", selectId: "abono2_x_metodo_pago", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.abono2.fields.metodo_pago.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->pivote->Visible) { // pivote ?>
    <div id="r_pivote" class="form-group row">
        <label id="elh_abono2_pivote" for="x_pivote" class="<?= $Page->LeftColumnClass ?>"><?= $Page->pivote->caption() ?><?= $Page->pivote->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->pivote->cellAttributes() ?>>
<span id="el_abono2_pivote">
<input type="<?= $Page->pivote->getInputTextType() ?>" data-table="abono2" data-field="x_pivote" name="x_pivote" id="x_pivote" size="30" maxlength="1" placeholder="<?= HtmlEncode($Page->pivote->getPlaceHolder()) ?>" value="<?= $Page->pivote->EditValue ?>"<?= $Page->pivote->editAttributes() ?> aria-describedby="x_pivote_help">
<?= $Page->pivote->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->pivote->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->pivote2->Visible) { // pivote2 ?>
    <div id="r_pivote2" class="form-group row">
        <label id="elh_abono2_pivote2" for="x_pivote2" class="<?= $Page->LeftColumnClass ?>"><?= $Page->pivote2->caption() ?><?= $Page->pivote2->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->pivote2->cellAttributes() ?>>
<span id="el_abono2_pivote2">
<input type="<?= $Page->pivote2->getInputTextType() ?>" data-table="abono2" data-field="x_pivote2" name="x_pivote2" id="x_pivote2" size="30" maxlength="1" placeholder="<?= HtmlEncode($Page->pivote2->getPlaceHolder()) ?>" value="<?= $Page->pivote2->EditValue ?>"<?= $Page->pivote2->editAttributes() ?> aria-describedby="x_pivote2_help">
<?= $Page->pivote2->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->pivote2->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php
    if (in_array("recarga2", explode(",", $Page->getCurrentDetailTable())) && $recarga2->DetailAdd) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("recarga2", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "Recarga2Grid.php" ?>
<?php } ?>
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
    ew.addEventHandlers("abono2");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    $("#x_pago").prop("readonly",!0),$("#r_pivote").hide(),$("#btn-action").prop("disabled",!0);var pivote2="<input type='hidden' id='pagos' name='pagos' value=''>";$("#r_pivote2").html(pivote2),$("#x_metodo_pago").change((function(){$("#r_pivote").show();var o=$("#x_cliente").val(),e=$("#x_metodo_pago").val(),t=$("#pagos").val();if($("#r_pivote").html(""),""==o)return alert("Seleccione un cliente"),location.reload(),!0;$.ajax({url:"include/Cliente_Tipo_Pago_Abonos_2.php",type:"GET",data:{cliente:o,tipo_pago:e,pagos:t},beforeSend:function(){$("#r_pivote").html("Por Favor Espere. . .")}}).done((function(o){var e;e=o,$("#r_pivote").html(e)})).fail((function(o){alert("error"+o)})).always((function(o){}))})),$("#x_cliente").change((function(){$("#btn-action").prop("disabled",!0)}));
});
</script>
