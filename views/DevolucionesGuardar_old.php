<?php

namespace PHPMaker2021\mandrake;

// Page object
$DevolucionesGuardar = &$Page;
?>
<?php
$id = $_POST["id"];
$items = $_POST["cantidad"];
$txtNota = isset($_POST["txtNota"]) ? trim($_POST["txtNota"]) : "";

if(strlen($txtNota) < 20) {
	echo '<h3>Debe indicar las razones por la cuales va a realizar esta devoluci&oacute;n. Coloque m&iacute;nimo 20 caracteres...</h3>';
  	echo '<button class="btn btn-default" onclick="$(location).attr(\'href\',\'Devoluciones\');">Haga click aquí para continuar</button>';
}
else {
  $id_old = $id;

  $sql = "SELECT cliente FROM salidas WHERE id = $id";
  $cliente = ExecuteScalar($sql);

  /**** Almacen por defecto ****/
  $sql = "SELECT valor1 AS almacen FROM parametro WHERE codigo = '002';";
  $almacen = ExecuteScalar($sql);

  /**** Moneda por defecto ****/
  $sql = "SELECT valor1 FROM parametro WHERE codigo = '006' AND valor2 = 'default';";
  $moneda = ExecuteScalar($sql);

  $proveedor = 1;

  $nota = $txtNota; // "DEVOLUCION DE ARTICULO";
  $sql = "INSERT INTO entradas
  			(id, tipo_documento, username, fecha, 
  			proveedor, nro_documento, almacen, estatus, 
  			id_documento_padre, consignacion, cliente, moneda, nota)
  		VALUES 
  			(NULL, 'TDCNRP', '" . CurrentUserName() . "', NOW(), 
  			$proveedor, '', '$almacen', 'PROCESADO', 
  			NULL, 'N', $cliente, '$moneda', '$nota');";
  Execute($sql);

  $newid = ExecuteScalar("SELECT LAST_INSERT_ID();");

  $control1 = "";
  $control2 = "";
  $control3 = "";
  $articulo = 0;
  $cantidad = 0;
  $costo = 0.00;
  $costo_total = 0.00;
  $sw = false;
  for($i=1; $i<=intval($_POST["cantidad"]); $i++) {
  	$control1 = "x" . $i . "_Articulo";
  	$control2 = "x" . $i . "_Cantidad";
  	$control3 = "x" . $i . "_Costo";
  	if(isset($_POST[$control1])) {
  		$articulo = floatval($_POST[$control1]);
  		$cantidad = floatval($_POST[$control2]);
  		$costo = floatval($_POST[$control3]);
  		$costo_total = $cantidad*$costo;

  		$sql = "INSERT INTO entradas_salidas
  				(id, tipo_documento, id_documento, 
  				fabricante, articulo, almacen, 
  				cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
  				cantidad_movimiento, costo_unidad, costo)
  			VALUES(NULL, 'TDCNRP', $newid,
  				1, $articulo, '$almacen',
  				$cantidad, 'UDM001', 1,
  				$cantidad, $costo, $costo_total);";
  		Execute($sql);

  		$sw = true;
  	}
  }

  if($sw) {
        $sql = "SELECT IFNULL(SUM(costo), 0) AS costo
        		FROM entradas_salidas
        		WHERE
        			id_documento = $newid
        			AND tipo_documento = 'TDCNRP'";
        		$monto_moneda = floatval(ExecuteScalar($sql));

        $sql = "SELECT tasa FROM tasa_usd
                WHERE moneda = '$moneda' ORDER BY id DESC LIMIT 0, 1;";
        $tasa = ExecuteScalar($sql);

        $referencia = $newid;
        $metodo_pago = "DV";
        $monto_bs = $tasa*$monto_moneda;
        $tasa_usd = $tasa;
        $monto_usd = $monto_moneda;
        $username = CurrentUserName();

        $sql = "SELECT IFNULL(MAX(nro_recibo), 0)+1 AS nro FROM abono WHERE 1;";
        $nro_recibo = ExecuteScalar($sql);

        $sql = "INSERT INTO
        	      	abono 
                SET 	
                  id = NULL,
                  cliente = $cliente,
                  fecha = NOW(),
                  metodo_pago = 'IMPRIMIR',
                  nro_recibo = $nro_recibo,
                  nota = 'POR DEVOLUCION | $txtNota',
                  username = '" . CurrentUserName() . "';";
        Execute($sql);
        $sql = "SELECT LAST_INSERT_ID();";
        $Abono = ExecuteScalar($sql);
                      
        $sql = "SELECT IFNULL(MAX(nro_recibo), 0)+1 AS nro FROM recarga WHERE 1;";
        $nro_recibo = ExecuteScalar($sql);

        //quedé aqui
        $sql = "INSERT INTO recarga(
        			id,
                    cliente,
                    fecha,
                    metodo_pago,
                    monto_moneda,
                    moneda,
                    tasa_moneda,
                    monto_bs,
                    tasa_usd,
                    monto_usd,
                    saldo,
                    nota,
                    username, reverso, nota_recepcion, nro_recibo, abono)
                VALUES (
                    NULL,
                    $cliente,
                    NOW(),
                    '$metodo_pago',
                    $monto_moneda,
                    '$moneda',
                    $tasa,
                    $monto_bs,
                    $tasa_usd,
                    $monto_usd,
                    0,
                    'Devolución de Nota de Recepción según nota: $txtNota',
                    '$username', 'N', $referencia, '$nro_recibo', $Abono)"; 
        Execute($sql);
        $sql = "SELECT LAST_INSERT_ID();";
        $id = ExecuteScalar($sql);
        $sql = "SELECT IFNULL(SUM(monto_usd), 0) AS saldo FROM recarga
        		WHERE cliente = $cliente;";
        $saldo = ExecuteScalar($sql);
        $sql = "UPDATE recarga SET saldo = $saldo WHERE id = $id;";
        Execute($sql);

        $sql = "SELECT SUM(monto_usd) AS pago FROM recarga WHERE abono = $Abono;";
        $monto_abono = ExecuteScalar($sql);

        $sql = "UPDATE abono SET pago = $monto_abono WHERE id = $Abono";
        Execute($sql);


        $sql = "UPDATE entradas SET nro_documento = 'ABONO - $nro_recibo', tasa_dia = $tasa, monto_total = $monto_usd, total = $monto_usd, monto_pagar = $monto_usd, monto_usd = $monto_usd WHERE id = $referencia;";
        Execute($sql);

        $sql = "UPDATE salidas SET email = 'DEVOLUCION' WHERE id = $id_old;";
        Execute($sql);

        /*$url = "EntradasView/$newid?showdetail=entradas_salidas";
        header("Location: $url");
        die();*/
        ?>
  	  <script>
  	  	setInterval(window.location.href = "Devoluciones?sw=1", 1000);
  	  </script>
        <?php
        // echo '<h2>Proceso Exitoso.</h2>';
        // echo '<button class="btn btn-primary" onclick="$(location).attr(\'href\',\'' . $url . '\');">Haga click aquí para ver nota de recepci&oacute;n</button>';
  }
  else {
  	$sql = "DELETE FROM entradas WHERE id = $newid";
  	Execute($sql);

  	echo '<h2>No ha seleccionado art&iacute;culo para devoluci&oacute;n.</h2>';
  	echo '<button class="btn btn-default" onclick="$(location).attr(\'href\',\'Devoluciones\');">Haga click aquí para continuar</button>';
  }
}
?>

<?= GetDebugMessage() ?>
