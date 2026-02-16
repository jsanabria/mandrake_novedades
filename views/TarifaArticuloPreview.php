<?php

namespace PHPMaker2021\mandrake;

// Page object
$TarifaArticuloPreview = &$Page;
?>
<script>
if (!ew.vars.tables.tarifa_articulo) ew.vars.tables.tarifa_articulo = <?= JsonEncode(GetClientVar("tables", "tarifa_articulo")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php if ($Page->TotalRecords > 0) { ?>
<div class="card ew-grid tarifa_articulo"><!-- .card -->
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
<?php if ($Page->tarifa->Visible) { // tarifa ?>
    <?php if ($Page->SortUrl($Page->tarifa) == "") { ?>
        <th class="<?= $Page->tarifa->headerCellClass() ?>"><?= $Page->tarifa->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->tarifa->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->tarifa->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->tarifa->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->tarifa->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->tarifa->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->tarifa->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
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
<?php if ($Page->precio->Visible) { // precio ?>
    <?php if ($Page->SortUrl($Page->precio) == "") { ?>
        <th class="<?= $Page->precio->headerCellClass() ?>"><?= $Page->precio->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->precio->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->precio->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->precio->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->precio->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->precio->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->precio->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
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
<?php if ($Page->tarifa->Visible) { // tarifa ?>
        <!-- tarifa -->
        <td<?= $Page->tarifa->cellAttributes() ?>>
<span<?= $Page->tarifa->viewAttributes() ?>>
<?= $Page->tarifa->getViewValue() ?></span>
</td>
<?php } ?>
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
<?php if ($Page->precio->Visible) { // precio ?>
        <!-- precio -->
        <td<?= $Page->precio->cellAttributes() ?>>
<span<?= $Page->precio->viewAttributes() ?>>
<?= $Page->precio->getViewValue() ?></span>
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
