<?php

namespace PHPMaker2021\mandrake;

// Page object
$CobrosClienteList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fcobros_clientelist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    fcobros_clientelist = currentForm = new ew.Form("fcobros_clientelist", "list");
    fcobros_clientelist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';
    loadjs.done("fcobros_clientelist");
});
var fcobros_clientelistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    fcobros_clientelistsrch = currentSearchForm = new ew.Form("fcobros_clientelistsrch");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "cobros_cliente")) ?>,
        fields = currentTable.fields;
    fcobros_clientelistsrch.addFields([
        ["id", [], fields.id.isInvalid],
        ["cliente", [], fields.cliente.isInvalid],
        ["id_documento", [], fields.id_documento.isInvalid],
        ["fecha", [ew.Validators.datetime(7)], fields.fecha.isInvalid],
        ["y_fecha", [ew.Validators.between], false],
        ["moneda", [], fields.moneda.isInvalid],
        ["pago", [], fields.pago.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        fcobros_clientelistsrch.setInvalid();
    });

    // Validate form
    fcobros_clientelistsrch.validate = function () {
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
    fcobros_clientelistsrch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fcobros_clientelistsrch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fcobros_clientelistsrch.lists.cliente = <?= $Page->cliente->toClientList($Page) ?>;

    // Filters
    fcobros_clientelistsrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    fcobros_clientelistsrch.initSearchPanel = true;
    loadjs.done("fcobros_clientelistsrch");
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
<form name="fcobros_clientelistsrch" id="fcobros_clientelistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fcobros_clientelistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="cobros_cliente">
    <div class="ew-extended-search">
<?php
// Render search row
$Page->RowType = ROWTYPE_SEARCH;
$Page->resetAttributes();
$Page->renderRow();
?>
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
        <label for="x_cliente" class="ew-search-caption ew-label"><?= $Page->cliente->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_cliente" id="z_cliente" value="=">
</span>
        <span id="el_cobros_cliente_cliente" class="ew-search-field">
<div class="input-group ew-lookup-list">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x_cliente"><?= EmptyValue(strval($Page->cliente->AdvancedSearch->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->cliente->AdvancedSearch->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->cliente->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->cliente->ReadOnly || $Page->cliente->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x_cliente',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->cliente->getErrorMessage(false) ?></div>
<?= $Page->cliente->Lookup->getParamTag($Page, "p_x_cliente") ?>
<input type="hidden" is="selection-list" data-table="cobros_cliente" data-field="x_cliente" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->cliente->displayValueSeparatorAttribute() ?>" name="x_cliente" id="x_cliente" value="<?= $Page->cliente->AdvancedSearch->SearchValue ?>"<?= $Page->cliente->editAttributes() ?>>
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
        <span id="el_cobros_cliente_fecha" class="ew-search-field">
<input type="<?= $Page->fecha->getInputTextType() ?>" data-table="cobros_cliente" data-field="x_fecha" data-format="7" name="x_fecha" id="x_fecha" placeholder="<?= HtmlEncode($Page->fecha->getPlaceHolder()) ?>" value="<?= $Page->fecha->EditValue ?>"<?= $Page->fecha->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->fecha->getErrorMessage(false) ?></div>
<?php if (!$Page->fecha->ReadOnly && !$Page->fecha->Disabled && !isset($Page->fecha->EditAttrs["readonly"]) && !isset($Page->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fcobros_clientelistsrch", "datetimepicker"], function() {
    ew.createDateTimePicker("fcobros_clientelistsrch", "x_fecha", {"ignoreReadonly":true,"useCurrent":false,"format":7});
});
</script>
<?php } ?>
</span>
        <span class="ew-search-and"><label><?= $Language->phrase("AND") ?></label></span>
        <span id="el2_cobros_cliente_fecha" class="ew-search-field2">
<input type="<?= $Page->fecha->getInputTextType() ?>" data-table="cobros_cliente" data-field="x_fecha" data-format="7" name="y_fecha" id="y_fecha" placeholder="<?= HtmlEncode($Page->fecha->getPlaceHolder()) ?>" value="<?= $Page->fecha->EditValue2 ?>"<?= $Page->fecha->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->fecha->getErrorMessage(false) ?></div>
<?php if (!$Page->fecha->ReadOnly && !$Page->fecha->Disabled && !isset($Page->fecha->EditAttrs["readonly"]) && !isset($Page->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fcobros_clientelistsrch", "datetimepicker"], function() {
    ew.createDateTimePicker("fcobros_clientelistsrch", "y_fecha", {"ignoreReadonly":true,"useCurrent":false,"format":7});
});
</script>
<?php } ?>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> cobros_cliente">
<form name="fcobros_clientelist" id="fcobros_clientelist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cobros_cliente">
<div id="gmp_cobros_cliente" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_cobros_clientelist" class="table ew-table"><!-- .ew-table -->
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
        <th data-name="id" class="<?= $Page->id->headerCellClass() ?>"><div id="elh_cobros_cliente_id" class="cobros_cliente_id"><?= $Page->renderSort($Page->id) ?></div></th>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
        <th data-name="cliente" class="<?= $Page->cliente->headerCellClass() ?>"><div id="elh_cobros_cliente_cliente" class="cobros_cliente_cliente"><?= $Page->renderSort($Page->cliente) ?></div></th>
<?php } ?>
<?php if ($Page->id_documento->Visible) { // id_documento ?>
        <th data-name="id_documento" class="<?= $Page->id_documento->headerCellClass() ?>"><div id="elh_cobros_cliente_id_documento" class="cobros_cliente_id_documento"><?= $Page->renderSort($Page->id_documento) ?></div></th>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <th data-name="fecha" class="<?= $Page->fecha->headerCellClass() ?>"><div id="elh_cobros_cliente_fecha" class="cobros_cliente_fecha"><?= $Page->renderSort($Page->fecha) ?></div></th>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
        <th data-name="moneda" class="<?= $Page->moneda->headerCellClass() ?>"><div id="elh_cobros_cliente_moneda" class="cobros_cliente_moneda"><?= $Page->renderSort($Page->moneda) ?></div></th>
<?php } ?>
<?php if ($Page->pago->Visible) { // pago ?>
        <th data-name="pago" class="<?= $Page->pago->headerCellClass() ?>"><div id="elh_cobros_cliente_pago" class="cobros_cliente_pago"><?= $Page->renderSort($Page->pago) ?></div></th>
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_cobros_cliente", "data-rowtype" => $Page->RowType]);

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
<span id="el<?= $Page->RowCount ?>_cobros_cliente_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->cliente->Visible) { // cliente ?>
        <td data-name="cliente" <?= $Page->cliente->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cobros_cliente_cliente">
<span<?= $Page->cliente->viewAttributes() ?>>
<?= $Page->cliente->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->id_documento->Visible) { // id_documento ?>
        <td data-name="id_documento" <?= $Page->id_documento->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cobros_cliente_id_documento">
<span<?= $Page->id_documento->viewAttributes() ?>>
<?= $Page->id_documento->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->fecha->Visible) { // fecha ?>
        <td data-name="fecha" <?= $Page->fecha->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cobros_cliente_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->moneda->Visible) { // moneda ?>
        <td data-name="moneda" <?= $Page->moneda->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cobros_cliente_moneda">
<span<?= $Page->moneda->viewAttributes() ?>>
<?= $Page->moneda->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->pago->Visible) { // pago ?>
        <td data-name="pago" <?= $Page->pago->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_cobros_cliente_pago">
<span<?= $Page->pago->viewAttributes() ?>>
<?= $Page->pago->getViewValue() ?></span>
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
    ew.addEventHandlers("cobros_cliente");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    // Write your table-specific startup script here, no need to add script tags.
    $(document).ready(function() {
    	/*var d = new Date();
    	var month = d.getMonth()+1;
    	var day = d.getDate();
    	var output = d.getFullYear() + '/' + (month<10 ? '0' : '') + month + '/' + (day<10 ? '0' : '') + day;
    	var output = (day<10 ? '0' : '') + day + '/' + (month<10 ? '0' : '') + month + '/' + d.getFullYear();
        $("#FechaCierre").val(output);*/
    });
    $("#btnCierreDeCaja").click(function(){
    	var url = "";
    	var cerrado = "S";
    	var titulo = "";
    	var username = "<?php echo CurrentUserName(); ?>";

    	/*
    	var d = new Date();
    	var month = d.getMonth()+1;
    	var day = d.getDate();
    	var fecha = d.getFullYear() + '-' + (month<10 ? '0' : '') + month + '-' + (day<10 ? '0' : '') + day;
    	var fechashow = (day<10 ? '0' : '') + day + '/' + (month<10 ? '0' : '') + month + '/' + d.getFullYear();
    	*/
    	var fecha = $("#FechaCierre").val();
    	var fechashow = $("#FechaCierre").val();
    	var fecha2 = $("#FechaCierre2").val();
    	//url = "reportes/cierre_de_caja.php?xtitulo=CIERRE DE CAJA&fecha=" + fecha;

    	// if(confirm("Emitir cierre de caja para todos los usuarios?; De lo contrario será para el usuario actual...")) {
    		username = "";
    	// }
    	url = "reportes/cierre_de_caja_detalle.php?xtitulo=CIERRE DE CAJA&fecha=" + fecha + "&username=" + username + "&fecha2=" + fecha2;
    	var arregloFecha = fecha.split("-");
    	var anio = arregloFecha[0];
    	var mes = formatted_string('00',(arregloFecha[1] - 1),'l');
    	var dia = arregloFecha[2];
    	var ffecha = dia + "/" + mes + "/" + anio;
    	if(fecha == "") {
    		alert("Debe indicar fecha para el reporte");
    		return false;
    	}
    	$.ajax({url: "include/cierre_de_caja.php", data: { consulta: 'S', fecha: fecha, abrir: 'N', username: username }, success: function(result){
    		cerrado = result;
           	if(cerrado == "N") {
           		if(confirm("Desea cerrar la caja para la fecha " + fecha + "?")) {
           			$.ajax({url: "include/cierre_de_caja.php", data: { consulta: 'N', fecha: fecha, abrir: 'N', username: username },  success: function(result){
           				alert(result);
           				$("#xCaja").html('<button class="btn btn-warning">Caja Cerrada para el d&iacute;a ' + ffecha + '</button>');
           				location.reload();
           			}});
           		}
       			window.open(url, 'Cierre');
           	}
           	else window.open(url, 'Cierre');
    	}});
    });
    $("#btnEnviarData").click(function(){
    	var url = "";
    	var servidor = "";
    	var codigo = "";
    	<?php
    	$sql = "SELECT valor1 AS servidor, valor2 AS codigo FROM parametro WHERE codigo = '048';";
    	$row = ExecuteRow($sql);
    	$servidor = $row["servidor"];
    	if(substr($servidor, strlen($servidor)-1, strlen($servidor)) != "/") $servidor .= "/";
    	$codigo = $row["codigo"];
    	echo "servidor = '$servidor';";
    	echo "codigo = '$codigo';";
    	?>
    	url = servidor + "SincronizarTienda?codigo=" + codigo;
    	// alert(url);
    	window.open(url, 'Sincronizar');
    	//$.ajax({url: url, data: { codigo: codigo }, success: function(result){
    		// alert(result);
    		//window.open(url, 'Sincronizar');
    	//}});
    });
    $("#btnDetalleDeCaja").click(function(){
    	var url = "";
    	var cerrado = "S";
    	var titulo = "";
    	var fecha = $("#FechaCierre").val();
    	var fechashow = $("#FechaCierre").val();
    	url = "reportes/cierre_de_caja_detalle.php?xtitulo=CIERRE DE CAJA&fecha=" + fecha;
    	if(fecha == "") {
    		alert("Debe indicar fecha para el reporte");
    		return false;
    	}
    	window.open(url, 'Cierre');
    });
    $("#btnAperturaDeCaja").click(function(){
    	var fecha = $("#FechaCierre").val();
    	if(confirm("Desea aperturar la caja para la fecha " + fecha + "?")) {
        	$.ajax({url: "include/cierre_de_caja.php", data: { consulta: 'N', fecha: fecha, abrir: 'S' },  success: function(result){
            	alert(result);
            	$("#xCaja").html('');
            }});
        }
    });
    $("#FechaCierre").change(function(){
    	var fecha = $("#FechaCierre").val();
    	var arregloFecha = fecha.split("-");
    	var anio = arregloFecha[0];
    	var mes = formatted_string('00',arregloFecha[1],'l');
    	var dia = arregloFecha[2];
    	var ffecha = dia + "/" + mes + "/" + anio;
    	url = "CobrosClienteList?FechaCierre=" + fecha;
    	$(location).attr('href',url);
    });
    $("#btnResumenVentas").click(function(){
    	var xboton = "RESUMEN DE VENTAS";
    	var fecha = $("#FechaCierre").val();
    	var url = "";
    	var arregloFecha = fecha.split("-");
    	var anio = arregloFecha[0];
    	var mes = formatted_string('00',arregloFecha[1],'l');
    	var dia = arregloFecha[2];
    	var ffecha = dia + "/" + mes + "/" + anio;
    	fecha = $("#FechaCierre2").val();
    	var arregloFecha = fecha.split("-");
    	var anio = arregloFecha[0];
    	var mes = formatted_string('00',arregloFecha[1],'l');
    	var dia = arregloFecha[2];
    	var f2fecha = dia + "/" + mes + "/" + anio;
    	url = "reportes/resumen_de_notas_de_entrega.php?xtitulo=" + xboton + "&xcliente=&xasesor=&xfecha=" + ffecha + "&yfecha=" + f2fecha;
    	if(fecha == "") {
    	alert("Debe indicar fecha de cierre de caja");
    	return false;
    	}
    	window.open(url, 'Resumen');
    });

    function formatted_string(pad, user_str, pad_pos)
    {
      if (typeof user_str === 'undefined') 
        return pad;
      if (pad_pos == 'l')
         {
         return (pad + user_str).slice(-pad.length);
         }
      else 
        {
        return (user_str + pad).substring(0, pad.length);
        }
    }
});
</script>
<?php } ?>
