<?php

namespace PHPMaker2021\mandrake;

// Page object
$ProveedorArticuloPreview = &$Page;
?>
<script>
if (!ew.vars.tables.proveedor_articulo) ew.vars.tables.proveedor_articulo = <?= JsonEncode(GetClientVar("tables", "proveedor_articulo")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php if ($Page->TotalRecords > 0) { ?>
<div class="card ew-grid proveedor_articulo"><!-- .card -->
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
<?php if ($Page->codigo_proveedor->Visible) { // codigo_proveedor ?>
    <?php if ($Page->SortUrl($Page->codigo_proveedor) == "") { ?>
        <th class="<?= $Page->codigo_proveedor->headerCellClass() ?>"><?= $Page->codigo_proveedor->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->codigo_proveedor->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->codigo_proveedor->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->codigo_proveedor->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->codigo_proveedor->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->codigo_proveedor->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->codigo_proveedor->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
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
<?php if ($Page->codigo_proveedor->Visible) { // codigo_proveedor ?>
        <!-- codigo_proveedor -->
        <td<?= $Page->codigo_proveedor->cellAttributes() ?>>
<span<?= $Page->codigo_proveedor->viewAttributes() ?>>
<?= $Page->codigo_proveedor->getViewValue() ?></span>
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
