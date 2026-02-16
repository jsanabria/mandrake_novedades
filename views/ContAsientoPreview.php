<?php

namespace PHPMaker2021\mandrake;

// Page object
$ContAsientoPreview = &$Page;
?>
<script>
if (!ew.vars.tables.cont_asiento) ew.vars.tables.cont_asiento = <?= JsonEncode(GetClientVar("tables", "cont_asiento")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php if ($Page->TotalRecords > 0) { ?>
<div class="card ew-grid cont_asiento"><!-- .card -->
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
<?php if ($Page->cuenta->Visible) { // cuenta ?>
    <?php if ($Page->SortUrl($Page->cuenta) == "") { ?>
        <th class="<?= $Page->cuenta->headerCellClass() ?>"><?= $Page->cuenta->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->cuenta->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->cuenta->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->cuenta->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->cuenta->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->cuenta->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->cuenta->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
    <?php if ($Page->SortUrl($Page->nota) == "") { ?>
        <th class="<?= $Page->nota->headerCellClass() ?>"><?= $Page->nota->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->nota->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->nota->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->nota->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->nota->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->nota->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->nota->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
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
<?php if ($Page->debe->Visible) { // debe ?>
    <?php if ($Page->SortUrl($Page->debe) == "") { ?>
        <th class="<?= $Page->debe->headerCellClass() ?>"><?= $Page->debe->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->debe->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->debe->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->debe->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->debe->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->debe->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->debe->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->haber->Visible) { // haber ?>
    <?php if ($Page->SortUrl($Page->haber) == "") { ?>
        <th class="<?= $Page->haber->headerCellClass() ?>"><?= $Page->haber->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->haber->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->haber->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->haber->getNextSort() ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->haber->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->haber->getSort() == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->haber->getSort() == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?></span>
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
<?php if ($Page->cuenta->Visible) { // cuenta ?>
        <!-- cuenta -->
        <td<?= $Page->cuenta->cellAttributes() ?>>
<span<?= $Page->cuenta->viewAttributes() ?>>
<?= $Page->cuenta->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
        <!-- nota -->
        <td<?= $Page->nota->cellAttributes() ?>>
<span<?= $Page->nota->viewAttributes() ?>>
<?= $Page->nota->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
        <!-- referencia -->
        <td<?= $Page->referencia->cellAttributes() ?>>
<span<?= $Page->referencia->viewAttributes() ?>>
<?= $Page->referencia->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->debe->Visible) { // debe ?>
        <!-- debe -->
        <td<?= $Page->debe->cellAttributes() ?>>
<span<?= $Page->debe->viewAttributes() ?>>
<?= $Page->debe->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->haber->Visible) { // haber ?>
        <!-- haber -->
        <td<?= $Page->haber->cellAttributes() ?>>
<span<?= $Page->haber->viewAttributes() ?>>
<?= $Page->haber->getViewValue() ?></span>
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
