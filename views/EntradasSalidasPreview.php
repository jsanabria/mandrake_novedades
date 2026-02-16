<?php

namespace PHPMaker2021\mandrake;

// Page object
$EntradasSalidasPreview = &$Page;
?>
<script>
if (!ew.vars.tables.entradas_salidas) ew.vars.tables.entradas_salidas = <?= JsonEncode(GetClientVar("tables", "entradas_salidas")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php if ($Page->TotalRecords > 0) { ?>
<div class="card ew-grid entradas_salidas"><!-- .card -->
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
<?php if ($Page->articulo->Visible) { // articulo ?>
    <?php if ($Page->SortUrl($Page->articulo) == "") { ?>
        <th class="<?= $Page->articulo->headerCellClass() ?>"><?= $Page->articulo->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->articulo->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->articulo->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->articulo->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->articulo->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->articulo->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->articulo->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
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
<?php if ($Page->precio_unidad_sin_desc->Visible) { // precio_unidad_sin_desc ?>
    <?php if ($Page->SortUrl($Page->precio_unidad_sin_desc) == "") { ?>
        <th class="<?= $Page->precio_unidad_sin_desc->headerCellClass() ?>"><?= $Page->precio_unidad_sin_desc->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->precio_unidad_sin_desc->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->precio_unidad_sin_desc->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->precio_unidad_sin_desc->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->precio_unidad_sin_desc->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->precio_unidad_sin_desc->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->precio_unidad_sin_desc->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->descuento->Visible) { // descuento ?>
    <?php if ($Page->SortUrl($Page->descuento) == "") { ?>
        <th class="<?= $Page->descuento->headerCellClass() ?>"><?= $Page->descuento->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->descuento->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->descuento->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->descuento->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->descuento->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->descuento->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->descuento->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->costo_unidad->Visible) { // costo_unidad ?>
    <?php if ($Page->SortUrl($Page->costo_unidad) == "") { ?>
        <th class="<?= $Page->costo_unidad->headerCellClass() ?>"><?= $Page->costo_unidad->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->costo_unidad->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->costo_unidad->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->costo_unidad->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->costo_unidad->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->costo_unidad->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->costo_unidad->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->costo->Visible) { // costo ?>
    <?php if ($Page->SortUrl($Page->costo) == "") { ?>
        <th class="<?= $Page->costo->headerCellClass() ?>"><?= $Page->costo->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->costo->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->costo->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->costo->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->costo->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->costo->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->costo->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->precio_unidad->Visible) { // precio_unidad ?>
    <?php if ($Page->SortUrl($Page->precio_unidad) == "") { ?>
        <th class="<?= $Page->precio_unidad->headerCellClass() ?>"><?= $Page->precio_unidad->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->precio_unidad->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->precio_unidad->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->precio_unidad->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->precio_unidad->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->precio_unidad->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->precio_unidad->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->precio->Visible) { // precio ?>
    <?php if ($Page->SortUrl($Page->precio) == "") { ?>
        <th class="<?= $Page->precio->headerCellClass() ?>"><?= $Page->precio->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->precio->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->precio->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->precio->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->precio->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->precio->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->precio->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
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
<?php if ($Page->articulo->Visible) { // articulo ?>
        <!-- articulo -->
        <td<?= $Page->articulo->cellAttributes() ?>>
<span<?= $Page->articulo->viewAttributes() ?>>
<?= $Page->articulo->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->cantidad_articulo->Visible) { // cantidad_articulo ?>
        <!-- cantidad_articulo -->
        <td<?= $Page->cantidad_articulo->cellAttributes() ?>>
<span<?= $Page->cantidad_articulo->viewAttributes() ?>>
<?= $Page->cantidad_articulo->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->precio_unidad_sin_desc->Visible) { // precio_unidad_sin_desc ?>
        <!-- precio_unidad_sin_desc -->
        <td<?= $Page->precio_unidad_sin_desc->cellAttributes() ?>>
<span<?= $Page->precio_unidad_sin_desc->viewAttributes() ?>>
<?= $Page->precio_unidad_sin_desc->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->descuento->Visible) { // descuento ?>
        <!-- descuento -->
        <td<?= $Page->descuento->cellAttributes() ?>>
<span<?= $Page->descuento->viewAttributes() ?>>
<?= $Page->descuento->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->costo_unidad->Visible) { // costo_unidad ?>
        <!-- costo_unidad -->
        <td<?= $Page->costo_unidad->cellAttributes() ?>>
<span<?= $Page->costo_unidad->viewAttributes() ?>>
<?= $Page->costo_unidad->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->costo->Visible) { // costo ?>
        <!-- costo -->
        <td<?= $Page->costo->cellAttributes() ?>>
<span<?= $Page->costo->viewAttributes() ?>>
<?= $Page->costo->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->precio_unidad->Visible) { // precio_unidad ?>
        <!-- precio_unidad -->
        <td<?= $Page->precio_unidad->cellAttributes() ?>>
<span<?= $Page->precio_unidad->viewAttributes() ?>>
<?= $Page->precio_unidad->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->precio->Visible) { // precio ?>
        <!-- precio -->
        <td<?= $Page->precio->cellAttributes() ?>>
<span<?= $Page->precio->viewAttributes() ?>>
<?= $Page->precio->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->lote->Visible) { // lote ?>
        <!-- lote -->
        <td<?= $Page->lote->cellAttributes() ?>>
<span<?= $Page->lote->viewAttributes() ?>>
<?= $Page->lote->getViewValue() ?></span>
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
