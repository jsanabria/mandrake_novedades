<?php

namespace PHPMaker2021\mandrake;

// Page object
$ContReglasPreview = &$Page;
?>
<script>
if (!ew.vars.tables.cont_reglas) ew.vars.tables.cont_reglas = <?= JsonEncode(GetClientVar("tables", "cont_reglas")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php if ($Page->TotalRecords > 0) { ?>
<div class="card ew-grid cont_reglas"><!-- .card -->
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
<?php if ($Page->codigo->Visible) { // codigo ?>
    <?php if ($Page->SortUrl($Page->codigo) == "") { ?>
        <th class="<?= $Page->codigo->headerCellClass() ?>"><?= $Page->codigo->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->codigo->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->codigo->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->codigo->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->codigo->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->codigo->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->codigo->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
    <?php if ($Page->SortUrl($Page->descripcion) == "") { ?>
        <th class="<?= $Page->descripcion->headerCellClass() ?>"><?= $Page->descripcion->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->descripcion->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->descripcion->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->descripcion->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->descripcion->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->descripcion->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->descripcion->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
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
<?php if ($Page->cargo->Visible) { // cargo ?>
    <?php if ($Page->SortUrl($Page->cargo) == "") { ?>
        <th class="<?= $Page->cargo->headerCellClass() ?>"><?= $Page->cargo->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->cargo->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->cargo->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->cargo->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->cargo->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->cargo->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->cargo->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
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
<?php if ($Page->codigo->Visible) { // codigo ?>
        <!-- codigo -->
        <td<?= $Page->codigo->cellAttributes() ?>>
<span<?= $Page->codigo->viewAttributes() ?>>
<?= $Page->codigo->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
        <!-- descripcion -->
        <td<?= $Page->descripcion->cellAttributes() ?>>
<span<?= $Page->descripcion->viewAttributes() ?>>
<?= $Page->descripcion->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->cuenta->Visible) { // cuenta ?>
        <!-- cuenta -->
        <td<?= $Page->cuenta->cellAttributes() ?>>
<span<?= $Page->cuenta->viewAttributes() ?>>
<?= $Page->cuenta->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->cargo->Visible) { // cargo ?>
        <!-- cargo -->
        <td<?= $Page->cargo->cellAttributes() ?>>
<span<?= $Page->cargo->viewAttributes() ?>>
<?= $Page->cargo->getViewValue() ?></span>
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
