<?php

namespace PHPMaker2021\mandrake;

// Page object
$ContLotesPagosDetallePreview = &$Page;
?>
<script>
if (!ew.vars.tables.cont_lotes_pagos_detalle) ew.vars.tables.cont_lotes_pagos_detalle = <?= JsonEncode(GetClientVar("tables", "cont_lotes_pagos_detalle")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php if ($Page->TotalRecords > 0) { ?>
<div class="card ew-grid cont_lotes_pagos_detalle"><!-- .card -->
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
<?php if ($Page->proveedor->Visible) { // proveedor ?>
    <?php if ($Page->SortUrl($Page->proveedor) == "") { ?>
        <th class="<?= $Page->proveedor->headerCellClass() ?>"><?= $Page->proveedor->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->proveedor->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->proveedor->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->proveedor->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->proveedor->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->proveedor->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->proveedor->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
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
<?php if ($Page->tipodoc->Visible) { // tipodoc ?>
    <?php if ($Page->SortUrl($Page->tipodoc) == "") { ?>
        <th class="<?= $Page->tipodoc->headerCellClass() ?>"><?= $Page->tipodoc->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->tipodoc->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->tipodoc->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->tipodoc->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->tipodoc->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->tipodoc->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->tipodoc->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
    <?php if ($Page->SortUrl($Page->nro_documento) == "") { ?>
        <th class="<?= $Page->nro_documento->headerCellClass() ?>"><?= $Page->nro_documento->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->nro_documento->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->nro_documento->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->nro_documento->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->nro_documento->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->nro_documento->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->nro_documento->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->monto_a_pagar->Visible) { // monto_a_pagar ?>
    <?php if ($Page->SortUrl($Page->monto_a_pagar) == "") { ?>
        <th class="<?= $Page->monto_a_pagar->headerCellClass() ?>"><?= $Page->monto_a_pagar->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->monto_a_pagar->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->monto_a_pagar->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->monto_a_pagar->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->monto_a_pagar->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->monto_a_pagar->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->monto_a_pagar->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->monto_pagado->Visible) { // monto_pagado ?>
    <?php if ($Page->SortUrl($Page->monto_pagado) == "") { ?>
        <th class="<?= $Page->monto_pagado->headerCellClass() ?>"><?= $Page->monto_pagado->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->monto_pagado->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->monto_pagado->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->monto_pagado->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->monto_pagado->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->monto_pagado->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->monto_pagado->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->saldo->Visible) { // saldo ?>
    <?php if ($Page->SortUrl($Page->saldo) == "") { ?>
        <th class="<?= $Page->saldo->headerCellClass() ?>"><?= $Page->saldo->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->saldo->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->saldo->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->saldo->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->saldo->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->saldo->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->saldo->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
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
<?php if ($Page->proveedor->Visible) { // proveedor ?>
        <!-- proveedor -->
        <td<?= $Page->proveedor->cellAttributes() ?>>
<span<?= $Page->proveedor->viewAttributes() ?>>
<?= $Page->proveedor->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <!-- fecha -->
        <td<?= $Page->fecha->cellAttributes() ?>>
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->tipodoc->Visible) { // tipodoc ?>
        <!-- tipodoc -->
        <td<?= $Page->tipodoc->cellAttributes() ?>>
<span<?= $Page->tipodoc->viewAttributes() ?>>
<?= $Page->tipodoc->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
        <!-- nro_documento -->
        <td<?= $Page->nro_documento->cellAttributes() ?>>
<span<?= $Page->nro_documento->viewAttributes() ?>>
<?= $Page->nro_documento->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->monto_a_pagar->Visible) { // monto_a_pagar ?>
        <!-- monto_a_pagar -->
        <td<?= $Page->monto_a_pagar->cellAttributes() ?>>
<span<?= $Page->monto_a_pagar->viewAttributes() ?>>
<?= $Page->monto_a_pagar->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->monto_pagado->Visible) { // monto_pagado ?>
        <!-- monto_pagado -->
        <td<?= $Page->monto_pagado->cellAttributes() ?>>
<span<?= $Page->monto_pagado->viewAttributes() ?>>
<?= $Page->monto_pagado->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->saldo->Visible) { // saldo ?>
        <!-- saldo -->
        <td<?= $Page->saldo->cellAttributes() ?>>
<span<?= $Page->saldo->viewAttributes() ?>>
<?= $Page->saldo->getViewValue() ?></span>
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
