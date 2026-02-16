<?php

namespace PHPMaker2021\mandrake;

// Page object
$PagosPreview = &$Page;
?>
<script>
if (!ew.vars.tables.pagos) ew.vars.tables.pagos = <?= JsonEncode(GetClientVar("tables", "pagos")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php if ($Page->TotalRecords > 0) { ?>
<div class="card ew-grid pagos"><!-- .card -->
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
<?php if ($Page->tipo_pago->Visible) { // tipo_pago ?>
    <?php if ($Page->SortUrl($Page->tipo_pago) == "") { ?>
        <th class="<?= $Page->tipo_pago->headerCellClass() ?>"><?= $Page->tipo_pago->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->tipo_pago->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->tipo_pago->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->tipo_pago->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->tipo_pago->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->tipo_pago->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->tipo_pago->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <?php if ($Page->SortUrl($Page->fecha) == "") { ?>
        <th class="<?= $Page->fecha->headerCellClass() ?>"><?= $Page->fecha->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->fecha->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->fecha->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->fecha->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->fecha->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->fecha->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->fecha->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
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
<?php if ($Page->monto->Visible) { // monto ?>
    <?php if ($Page->SortUrl($Page->monto) == "") { ?>
        <th class="<?= $Page->monto->headerCellClass() ?>"><?= $Page->monto->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->monto->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->monto->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->monto->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->monto->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->monto->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->monto->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->comprobante_pago->Visible) { // comprobante_pago ?>
    <?php if ($Page->SortUrl($Page->comprobante_pago) == "") { ?>
        <th class="<?= $Page->comprobante_pago->headerCellClass() ?>"><?= $Page->comprobante_pago->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->comprobante_pago->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->comprobante_pago->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->comprobante_pago->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->comprobante_pago->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->comprobante_pago->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->comprobante_pago->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
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
<?php if ($Page->tipo_pago->Visible) { // tipo_pago ?>
        <!-- tipo_pago -->
        <td<?= $Page->tipo_pago->cellAttributes() ?>>
<span<?= $Page->tipo_pago->viewAttributes() ?>>
<?= $Page->tipo_pago->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <!-- fecha -->
        <td<?= $Page->fecha->cellAttributes() ?>>
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
        <!-- referencia -->
        <td<?= $Page->referencia->cellAttributes() ?>>
<span<?= $Page->referencia->viewAttributes() ?>>
<?= $Page->referencia->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->monto->Visible) { // monto ?>
        <!-- monto -->
        <td<?= $Page->monto->cellAttributes() ?>>
<span<?= $Page->monto->viewAttributes() ?>>
<?= $Page->monto->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->comprobante_pago->Visible) { // comprobante_pago ?>
        <!-- comprobante_pago -->
        <td<?= $Page->comprobante_pago->cellAttributes() ?>>
<span>
<?= GetFileViewTag($Page->comprobante_pago, $Page->comprobante_pago->getViewValue(), false) ?>
</span>
</td>
<?php } ?>
<?php if ($Page->comprobante->Visible) { // comprobante ?>
        <!-- comprobante -->
        <td<?= $Page->comprobante->cellAttributes() ?>>
<span<?= $Page->comprobante->viewAttributes() ?>>
<?= $Page->comprobante->getViewValue() ?></span>
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
