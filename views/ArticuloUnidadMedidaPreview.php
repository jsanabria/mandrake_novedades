<?php

namespace PHPMaker2021\mandrake;

// Page object
$ArticuloUnidadMedidaPreview = &$Page;
?>
<script>
if (!ew.vars.tables.articulo_unidad_medida) ew.vars.tables.articulo_unidad_medida = <?= JsonEncode(GetClientVar("tables", "articulo_unidad_medida")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php if ($Page->TotalRecords > 0) { ?>
<div class="card ew-grid articulo_unidad_medida"><!-- .card -->
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
<?php if ($Page->unidad_medida->Visible) { // unidad_medida ?>
    <?php if ($Page->SortUrl($Page->unidad_medida) == "") { ?>
        <th class="<?= $Page->unidad_medida->headerCellClass() ?>"><?= $Page->unidad_medida->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->unidad_medida->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->unidad_medida->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->unidad_medida->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->unidad_medida->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->unidad_medida->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->unidad_medida->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
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
<?php if ($Page->unidad_medida->Visible) { // unidad_medida ?>
        <!-- unidad_medida -->
        <td<?= $Page->unidad_medida->cellAttributes() ?>>
<span<?= $Page->unidad_medida->viewAttributes() ?>>
<?= $Page->unidad_medida->getViewValue() ?></span>
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
