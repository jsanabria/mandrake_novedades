<?php

namespace PHPMaker2021\mandrake;

// Page object
$CompaniaCuentaPreview = &$Page;
?>
<script>
if (!ew.vars.tables.compania_cuenta) ew.vars.tables.compania_cuenta = <?= JsonEncode(GetClientVar("tables", "compania_cuenta")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php if ($Page->TotalRecords > 0) { ?>
<div class="card ew-grid compania_cuenta"><!-- .card -->
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
<?php if ($Page->banco->Visible) { // banco ?>
    <?php if ($Page->SortUrl($Page->banco) == "") { ?>
        <th class="<?= $Page->banco->headerCellClass() ?>"><?= $Page->banco->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->banco->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->banco->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->banco->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->banco->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->banco->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->banco->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->titular->Visible) { // titular ?>
    <?php if ($Page->SortUrl($Page->titular) == "") { ?>
        <th class="<?= $Page->titular->headerCellClass() ?>"><?= $Page->titular->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->titular->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->titular->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->titular->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->titular->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->titular->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->titular->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->tipo->Visible) { // tipo ?>
    <?php if ($Page->SortUrl($Page->tipo) == "") { ?>
        <th class="<?= $Page->tipo->headerCellClass() ?>"><?= $Page->tipo->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->tipo->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->tipo->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->tipo->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->tipo->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->tipo->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->tipo->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->numero->Visible) { // numero ?>
    <?php if ($Page->SortUrl($Page->numero) == "") { ?>
        <th class="<?= $Page->numero->headerCellClass() ?>"><?= $Page->numero->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->numero->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->numero->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->numero->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->numero->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->numero->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->numero->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->mostrar->Visible) { // mostrar ?>
    <?php if ($Page->SortUrl($Page->mostrar) == "") { ?>
        <th class="<?= $Page->mostrar->headerCellClass() ?>"><?= $Page->mostrar->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->mostrar->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->mostrar->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->mostrar->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->mostrar->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->mostrar->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->mostrar->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->cuenta->Visible) { // cuenta ?>
    <?php if ($Page->SortUrl($Page->cuenta) == "") { ?>
        <th class="<?= $Page->cuenta->headerCellClass() ?>"><?= $Page->cuenta->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->cuenta->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->cuenta->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->cuenta->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->cuenta->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->cuenta->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->cuenta->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->pago_electronico->Visible) { // pago_electronico ?>
    <?php if ($Page->SortUrl($Page->pago_electronico) == "") { ?>
        <th class="<?= $Page->pago_electronico->headerCellClass() ?>"><?= $Page->pago_electronico->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->pago_electronico->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->pago_electronico->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->pago_electronico->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->pago_electronico->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->pago_electronico->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->pago_electronico->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
    <?php if ($Page->SortUrl($Page->activo) == "") { ?>
        <th class="<?= $Page->activo->headerCellClass() ?>"><?= $Page->activo->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->activo->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->activo->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->activo->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->activo->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->activo->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->activo->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
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
<?php if ($Page->banco->Visible) { // banco ?>
        <!-- banco -->
        <td<?= $Page->banco->cellAttributes() ?>>
<span<?= $Page->banco->viewAttributes() ?>>
<?= $Page->banco->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->titular->Visible) { // titular ?>
        <!-- titular -->
        <td<?= $Page->titular->cellAttributes() ?>>
<span<?= $Page->titular->viewAttributes() ?>>
<?= $Page->titular->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->tipo->Visible) { // tipo ?>
        <!-- tipo -->
        <td<?= $Page->tipo->cellAttributes() ?>>
<span<?= $Page->tipo->viewAttributes() ?>>
<?= $Page->tipo->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->numero->Visible) { // numero ?>
        <!-- numero -->
        <td<?= $Page->numero->cellAttributes() ?>>
<span<?= $Page->numero->viewAttributes() ?>>
<?= $Page->numero->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->mostrar->Visible) { // mostrar ?>
        <!-- mostrar -->
        <td<?= $Page->mostrar->cellAttributes() ?>>
<span<?= $Page->mostrar->viewAttributes() ?>>
<?= $Page->mostrar->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->cuenta->Visible) { // cuenta ?>
        <!-- cuenta -->
        <td<?= $Page->cuenta->cellAttributes() ?>>
<span<?= $Page->cuenta->viewAttributes() ?>>
<?= $Page->cuenta->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->pago_electronico->Visible) { // pago_electronico ?>
        <!-- pago_electronico -->
        <td<?= $Page->pago_electronico->cellAttributes() ?>>
<span<?= $Page->pago_electronico->viewAttributes() ?>>
<?= $Page->pago_electronico->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
        <!-- activo -->
        <td<?= $Page->activo->cellAttributes() ?>>
<span<?= $Page->activo->viewAttributes() ?>>
<?= $Page->activo->getViewValue() ?></span>
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
