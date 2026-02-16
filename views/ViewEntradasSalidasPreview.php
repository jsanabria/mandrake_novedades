<?php

namespace PHPMaker2021\mandrake;

// Page object
$ViewEntradasSalidasPreview = &$Page;
?>
<script>
if (!ew.vars.tables.view_entradas_salidas) ew.vars.tables.view_entradas_salidas = <?= JsonEncode(GetClientVar("tables", "view_entradas_salidas")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php if ($Page->TotalRecords > 0) { ?>
<div class="card ew-grid view_entradas_salidas"><!-- .card -->
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
<?php if ($Page->fabricante->Visible) { // fabricante ?>
    <?php if ($Page->SortUrl($Page->fabricante) == "") { ?>
        <th class="<?= $Page->fabricante->headerCellClass() ?>"><?= $Page->fabricante->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->fabricante->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->fabricante->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->fabricante->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->fabricante->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->fabricante->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->fabricante->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->articulo->Visible) { // articulo ?>
    <?php if ($Page->SortUrl($Page->articulo) == "") { ?>
        <th class="<?= $Page->articulo->headerCellClass() ?>"><?= $Page->articulo->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->articulo->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->articulo->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->articulo->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->articulo->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->articulo->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->articulo->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->lote->Visible) { // lote ?>
    <?php if ($Page->SortUrl($Page->lote) == "") { ?>
        <th class="<?= $Page->lote->headerCellClass() ?>"><?= $Page->lote->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->lote->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->lote->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->lote->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->lote->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->lote->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->lote->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->fecha_vencimiento->Visible) { // fecha_vencimiento ?>
    <?php if ($Page->SortUrl($Page->fecha_vencimiento) == "") { ?>
        <th class="<?= $Page->fecha_vencimiento->headerCellClass() ?>"><?= $Page->fecha_vencimiento->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->fecha_vencimiento->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->fecha_vencimiento->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->fecha_vencimiento->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->fecha_vencimiento->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->fecha_vencimiento->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->fecha_vencimiento->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->cantidad_articulo->Visible) { // cantidad_articulo ?>
    <?php if ($Page->SortUrl($Page->cantidad_articulo) == "") { ?>
        <th class="<?= $Page->cantidad_articulo->headerCellClass() ?>"><?= $Page->cantidad_articulo->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->cantidad_articulo->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->cantidad_articulo->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->cantidad_articulo->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->cantidad_articulo->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->cantidad_articulo->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->cantidad_articulo->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->articulo_unidad_medida->Visible) { // articulo_unidad_medida ?>
    <?php if ($Page->SortUrl($Page->articulo_unidad_medida) == "") { ?>
        <th class="<?= $Page->articulo_unidad_medida->headerCellClass() ?>"><?= $Page->articulo_unidad_medida->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->articulo_unidad_medida->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->articulo_unidad_medida->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->articulo_unidad_medida->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->articulo_unidad_medida->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->articulo_unidad_medida->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->articulo_unidad_medida->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
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
<?php if ($Page->fabricante->Visible) { // fabricante ?>
        <!-- fabricante -->
        <td<?= $Page->fabricante->cellAttributes() ?>>
<span<?= $Page->fabricante->viewAttributes() ?>>
<?= $Page->fabricante->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->articulo->Visible) { // articulo ?>
        <!-- articulo -->
        <td<?= $Page->articulo->cellAttributes() ?>>
<span<?= $Page->articulo->viewAttributes() ?>>
<?= $Page->articulo->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->lote->Visible) { // lote ?>
        <!-- lote -->
        <td<?= $Page->lote->cellAttributes() ?>>
<span<?= $Page->lote->viewAttributes() ?>>
<?= $Page->lote->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->fecha_vencimiento->Visible) { // fecha_vencimiento ?>
        <!-- fecha_vencimiento -->
        <td<?= $Page->fecha_vencimiento->cellAttributes() ?>>
<span<?= $Page->fecha_vencimiento->viewAttributes() ?>>
<?= $Page->fecha_vencimiento->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->cantidad_articulo->Visible) { // cantidad_articulo ?>
        <!-- cantidad_articulo -->
        <td<?= $Page->cantidad_articulo->cellAttributes() ?>>
<span<?= $Page->cantidad_articulo->viewAttributes() ?>>
<?= $Page->cantidad_articulo->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->articulo_unidad_medida->Visible) { // articulo_unidad_medida ?>
        <!-- articulo_unidad_medida -->
        <td<?= $Page->articulo_unidad_medida->cellAttributes() ?>>
<span<?= $Page->articulo_unidad_medida->viewAttributes() ?>>
<?= $Page->articulo_unidad_medida->getViewValue() ?></span>
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
