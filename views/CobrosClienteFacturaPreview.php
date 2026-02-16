<?php

namespace PHPMaker2021\mandrake;

// Page object
$CobrosClienteFacturaPreview = &$Page;
?>
<script>
if (!ew.vars.tables.cobros_cliente_factura) ew.vars.tables.cobros_cliente_factura = <?= JsonEncode(GetClientVar("tables", "cobros_cliente_factura")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php if ($Page->TotalRecords > 0) { ?>
<div class="card ew-grid cobros_cliente_factura"><!-- .card -->
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
<?php if ($Page->retivamonto->Visible) { // retivamonto ?>
    <?php if ($Page->SortUrl($Page->retivamonto) == "") { ?>
        <th class="<?= $Page->retivamonto->headerCellClass() ?>"><?= $Page->retivamonto->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->retivamonto->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->retivamonto->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->retivamonto->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->retivamonto->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->retivamonto->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->retivamonto->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->retiva->Visible) { // retiva ?>
    <?php if ($Page->SortUrl($Page->retiva) == "") { ?>
        <th class="<?= $Page->retiva->headerCellClass() ?>"><?= $Page->retiva->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->retiva->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->retiva->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->retiva->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->retiva->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->retiva->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->retiva->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->retislrmonto->Visible) { // retislrmonto ?>
    <?php if ($Page->SortUrl($Page->retislrmonto) == "") { ?>
        <th class="<?= $Page->retislrmonto->headerCellClass() ?>"><?= $Page->retislrmonto->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->retislrmonto->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->retislrmonto->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->retislrmonto->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->retislrmonto->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->retislrmonto->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->retislrmonto->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->retislr->Visible) { // retislr ?>
    <?php if ($Page->SortUrl($Page->retislr) == "") { ?>
        <th class="<?= $Page->retislr->headerCellClass() ?>"><?= $Page->retislr->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->retislr->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->retislr->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->retislr->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->retislr->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->retislr->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->retislr->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
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
<?php if ($Page->retivamonto->Visible) { // retivamonto ?>
        <!-- retivamonto -->
        <td<?= $Page->retivamonto->cellAttributes() ?>>
<span<?= $Page->retivamonto->viewAttributes() ?>>
<?= $Page->retivamonto->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->retiva->Visible) { // retiva ?>
        <!-- retiva -->
        <td<?= $Page->retiva->cellAttributes() ?>>
<span<?= $Page->retiva->viewAttributes() ?>>
<?= $Page->retiva->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->retislrmonto->Visible) { // retislrmonto ?>
        <!-- retislrmonto -->
        <td<?= $Page->retislrmonto->cellAttributes() ?>>
<span<?= $Page->retislrmonto->viewAttributes() ?>>
<?= $Page->retislrmonto->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->retislr->Visible) { // retislr ?>
        <!-- retislr -->
        <td<?= $Page->retislr->cellAttributes() ?>>
<span<?= $Page->retislr->viewAttributes() ?>>
<?= $Page->retislr->getViewValue() ?></span>
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
