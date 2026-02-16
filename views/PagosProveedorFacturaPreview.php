<?php

namespace PHPMaker2021\mandrake;

// Page object
$PagosProveedorFacturaPreview = &$Page;
?>
<script>
if (!ew.vars.tables.pagos_proveedor_factura) ew.vars.tables.pagos_proveedor_factura = <?= JsonEncode(GetClientVar("tables", "pagos_proveedor_factura")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php if ($Page->TotalRecords > 0) { ?>
<div class="card ew-grid pagos_proveedor_factura"><!-- .card -->
<div class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel ew-preview-middle-panel"><!-- .table-responsive -->
<table class="table ew-table ew-preview-table"><!-- .table -->
    <thead><!-- Table header -->
        <tr class="ew-table-header">
<?php
// Render list options
$Page->renderListOptions();

// Render list options (header, left)
$Page->ListOptions->render("header", "left");
?>
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
    <?php if ($Page->SortUrl($Page->tipo_documento) == "") { ?>
        <th class="<?= $Page->tipo_documento->headerCellClass() ?>"><?= $Page->tipo_documento->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->tipo_documento->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->tipo_documento->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->tipo_documento->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->tipo_documento->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->tipo_documento->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->tipo_documento->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->abono->Visible) { // abono ?>
    <?php if ($Page->SortUrl($Page->abono) == "") { ?>
        <th class="<?= $Page->abono->headerCellClass() ?>"><?= $Page->abono->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->abono->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->abono->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->abono->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->abono->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->abono->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->abono->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->monto->Visible) { // monto ?>
    <?php if ($Page->SortUrl($Page->monto) == "") { ?>
        <th class="<?= $Page->monto->headerCellClass() ?>"><?= $Page->monto->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->monto->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->monto->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->monto->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->monto->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->monto->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->monto->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->comprobante->Visible) { // comprobante ?>
    <?php if ($Page->SortUrl($Page->comprobante) == "") { ?>
        <th class="<?= $Page->comprobante->headerCellClass() ?>"><?= $Page->comprobante->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->comprobante->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->comprobante->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->comprobante->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->comprobante->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->comprobante->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->comprobante->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php
// Render list options (header, right)
$Page->ListOptions->render("header", "right");
?>
        </tr>
    </thead>
    <tbody><!-- Table body -->
<?php
$Page->RecCount = 0;
$Page->RowCount = 0;
while ($Page->Recordset && !$Page->Recordset->EOF) {
    // Init row class and style
    $Page->RecCount++;
    $Page->RowCount++;
    $Page->CssStyle = "";
    $Page->loadListRowValues($Page->Recordset);

    // Render row
    $Page->RowType = ROWTYPE_PREVIEW; // Preview record
    $Page->resetAttributes();
    $Page->renderListRow();

    // Render list options
    $Page->renderListOptions();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Page->ListOptions->render("body", "left", $Page->RowCount);
?>
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
        <!-- tipo_documento -->
        <td<?= $Page->tipo_documento->cellAttributes() ?>>
<span<?= $Page->tipo_documento->viewAttributes() ?>>
<?= $Page->tipo_documento->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->abono->Visible) { // abono ?>
        <!-- abono -->
        <td<?= $Page->abono->cellAttributes() ?>>
<span<?= $Page->abono->viewAttributes() ?>>
<?= $Page->abono->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->monto->Visible) { // monto ?>
        <!-- monto -->
        <td<?= $Page->monto->cellAttributes() ?>>
<span<?= $Page->monto->viewAttributes() ?>>
<?= $Page->monto->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->comprobante->Visible) { // comprobante ?>
        <!-- comprobante -->
        <td<?= $Page->comprobante->cellAttributes() ?>>
<span<?= $Page->comprobante->viewAttributes() ?>>
<?php if (!EmptyString($Page->comprobante->getViewValue()) && $Page->comprobante->linkAttributes() != "") { ?>
<a<?= $Page->comprobante->linkAttributes() ?>><?= $Page->comprobante->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->comprobante->getViewValue() ?>
<?php } ?>
</span>
</td>
<?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowCount);
?>
    </tr>
<?php
    $Page->Recordset->moveNext();
} // while
?>
    </tbody>
</table><!-- /.table -->
</div><!-- /.table-responsive -->
<div class="card-footer ew-grid-lower-panel ew-preview-lower-panel"><!-- .card-footer -->
<?= $Page->Pager->render() ?>
<?php } else { // No record ?>
<div class="card no-border">
<div class="ew-detail-count"><?= $Language->phrase("NoRecord") ?></div>
<?php } ?>
<div class="ew-preview-other-options">
<?php
    foreach ($Page->OtherOptions as $option)
        $option->render("body");
?>
</div>
<?php if ($Page->TotalRecords > 0) { ?>
<div class="clearfix"></div>
</div><!-- /.card-footer -->
<?php } ?>
</div><!-- /.card -->
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php
if ($Page->Recordset) {
    $Page->Recordset->close();
}
?>
