<?php

namespace PHPMaker2021\mandrake;

// Page object
$CobrosClienteDetallePreview = &$Page;
?>
<script>
if (!ew.vars.tables.cobros_cliente_detalle) ew.vars.tables.cobros_cliente_detalle = <?= JsonEncode(GetClientVar("tables", "cobros_cliente_detalle")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php if ($Page->TotalRecords > 0) { ?>
<div class="card ew-grid cobros_cliente_detalle"><!-- .card -->
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
<?php if ($Page->metodo_pago->Visible) { // metodo_pago ?>
    <?php if ($Page->SortUrl($Page->metodo_pago) == "") { ?>
        <th class="<?= $Page->metodo_pago->headerCellClass() ?>"><?= $Page->metodo_pago->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->metodo_pago->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->metodo_pago->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->metodo_pago->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->metodo_pago->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->metodo_pago->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->metodo_pago->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
    <?php if ($Page->SortUrl($Page->referencia) == "") { ?>
        <th class="<?= $Page->referencia->headerCellClass() ?>"><?= $Page->referencia->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->referencia->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->referencia->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->referencia->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->referencia->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->referencia->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->referencia->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->monto_moneda->Visible) { // monto_moneda ?>
    <?php if ($Page->SortUrl($Page->monto_moneda) == "") { ?>
        <th class="<?= $Page->monto_moneda->headerCellClass() ?>"><?= $Page->monto_moneda->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->monto_moneda->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->monto_moneda->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->monto_moneda->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->monto_moneda->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->monto_moneda->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->monto_moneda->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <?php if ($Page->SortUrl($Page->moneda) == "") { ?>
        <th class="<?= $Page->moneda->headerCellClass() ?>"><?= $Page->moneda->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->moneda->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->moneda->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->moneda->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->moneda->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->moneda->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->moneda->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->tasa_moneda->Visible) { // tasa_moneda ?>
    <?php if ($Page->SortUrl($Page->tasa_moneda) == "") { ?>
        <th class="<?= $Page->tasa_moneda->headerCellClass() ?>"><?= $Page->tasa_moneda->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->tasa_moneda->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->tasa_moneda->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->tasa_moneda->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->tasa_moneda->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->tasa_moneda->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->tasa_moneda->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->monto_bs->Visible) { // monto_bs ?>
    <?php if ($Page->SortUrl($Page->monto_bs) == "") { ?>
        <th class="<?= $Page->monto_bs->headerCellClass() ?>"><?= $Page->monto_bs->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->monto_bs->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->monto_bs->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->monto_bs->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->monto_bs->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->monto_bs->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->monto_bs->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->tasa_usd->Visible) { // tasa_usd ?>
    <?php if ($Page->SortUrl($Page->tasa_usd) == "") { ?>
        <th class="<?= $Page->tasa_usd->headerCellClass() ?>"><?= $Page->tasa_usd->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->tasa_usd->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->tasa_usd->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->tasa_usd->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->tasa_usd->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->tasa_usd->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->tasa_usd->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->monto_usd->Visible) { // monto_usd ?>
    <?php if ($Page->SortUrl($Page->monto_usd) == "") { ?>
        <th class="<?= $Page->monto_usd->headerCellClass() ?>"><?= $Page->monto_usd->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->monto_usd->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->monto_usd->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->monto_usd->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->monto_usd->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->monto_usd->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->monto_usd->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->banco->Visible) { // banco ?>
    <?php if ($Page->SortUrl($Page->banco) == "") { ?>
        <th class="<?= $Page->banco->headerCellClass() ?>"><?= $Page->banco->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->banco->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->banco->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->banco->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->banco->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->banco->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->banco->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
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
<?php if ($Page->metodo_pago->Visible) { // metodo_pago ?>
        <!-- metodo_pago -->
        <td<?= $Page->metodo_pago->cellAttributes() ?>>
<span<?= $Page->metodo_pago->viewAttributes() ?>>
<?= $Page->metodo_pago->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
        <!-- referencia -->
        <td<?= $Page->referencia->cellAttributes() ?>>
<span<?= $Page->referencia->viewAttributes() ?>>
<?= $Page->referencia->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->monto_moneda->Visible) { // monto_moneda ?>
        <!-- monto_moneda -->
        <td<?= $Page->monto_moneda->cellAttributes() ?>>
<span<?= $Page->monto_moneda->viewAttributes() ?>>
<?= $Page->monto_moneda->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
        <!-- moneda -->
        <td<?= $Page->moneda->cellAttributes() ?>>
<span<?= $Page->moneda->viewAttributes() ?>>
<?= $Page->moneda->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->tasa_moneda->Visible) { // tasa_moneda ?>
        <!-- tasa_moneda -->
        <td<?= $Page->tasa_moneda->cellAttributes() ?>>
<span<?= $Page->tasa_moneda->viewAttributes() ?>>
<?= $Page->tasa_moneda->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->monto_bs->Visible) { // monto_bs ?>
        <!-- monto_bs -->
        <td<?= $Page->monto_bs->cellAttributes() ?>>
<span<?= $Page->monto_bs->viewAttributes() ?>>
<?= $Page->monto_bs->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->tasa_usd->Visible) { // tasa_usd ?>
        <!-- tasa_usd -->
        <td<?= $Page->tasa_usd->cellAttributes() ?>>
<span<?= $Page->tasa_usd->viewAttributes() ?>>
<?= $Page->tasa_usd->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->monto_usd->Visible) { // monto_usd ?>
        <!-- monto_usd -->
        <td<?= $Page->monto_usd->cellAttributes() ?>>
<span<?= $Page->monto_usd->viewAttributes() ?>>
<?= $Page->monto_usd->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->banco->Visible) { // banco ?>
        <!-- banco -->
        <td<?= $Page->banco->cellAttributes() ?>>
<span<?= $Page->banco->viewAttributes() ?>>
<?= $Page->banco->getViewValue() ?></span>
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
