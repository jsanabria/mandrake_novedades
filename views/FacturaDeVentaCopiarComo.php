<?php

namespace PHPMaker2021\mandrake;

// Page object
$FacturaDeVentaCopiarComo = &$Page;
?>
<?php
$id = $_REQUEST["id"];
$tipo_documento = $_REQUEST["tipo"];
$documento = "";

$doc = ExecuteScalar("SELECT nro_documento FROM salidas WHERE id = $id;");
?>

<div class="container">
  <div class="row">
      <h2>Copiar Documento # <?php echo $doc; ?> como</h2><br>
      <h4>Proceso de copiado de documento tipo factura</h4><br>
  </div>
  <form name="frm" method="post" class="form-inline" action="FacturaDeVentaDetalleCopia">
    <div class="row text-aling-center">
      <div class="list-group mx-0">
        <label class="list-group-item d-flex gap-2">
          <input class="form-check-input flex-shrink-0" type="radio" id="documento" name="documento" value="FC003" checked>
          <span>
            Factura de Venta
            <small class="d-block text-muted">Crea una Copia de la Factura de Ventas</small>
          </span>
        </label>
        <label class="list-group-item d-flex gap-2">
          <input class="form-check-input flex-shrink-0" type="radio" id="documento" name="documento" value="NC010">
          <span>
            Nota de Cr&eacute;dito
            <small class="d-block text-muted">Crea una Copia de la Factura de ventas a una Nota de Cr&eacute;dito</small>
          </span>
        </label>
        <label class="list-group-item d-flex gap-2">
          <input class="form-check-input flex-shrink-0" type="radio" id="documento" name="documento" value="ND011">
          <span>
            Nota de D&eacute;bito
            <small class="d-block text-muted">Crea una Copia de la Factura de Ventas a una Nota de D&eacute;bito</small>
          </span>
        </label>
      </div>

        <br>
        <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $id; ?>">
        <input type="hidden" class="form-control" id="tipo_documento" name="tipo_documento" value="<?php echo $tipo_documento; ?>">
        <button type="submit" class="btn btn-primary">Realizar Copia</button>
    </div>
  </form>

</div>


<?= GetDebugMessage() ?>
