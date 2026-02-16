<?php

namespace PHPMaker2021\mandrake;

// Page object
$DevolucionesGuardar = &$Page;
?>
<?php
$id = $_POST["id"];
$items = $_POST["cantidad"];
$txtNota = isset($_POST["txtNota"]) ? trim($_POST["txtNota"]) : "";
$error = "";
$newid = 0;

if(strlen($txtNota) < 20) {
	echo '<h3>Debe indicar las razones por la cuales va a realizar esta devoluci&oacute;n. Coloque m&iacute;nimo 20 caracteres...</h3>';
  	echo '<button class="btn btn-default" onclick="$(location).attr(\'href\',\'Devoluciones\');">Haga click aquí para continuar</button>';
}
else {
  $id_old = $id;

  $sql = "SELECT cliente, IFNULL(descuento, 0) AS descuento, nro_documento FROM salidas WHERE id = $id;";
  $row = ExecuteRow($sql);
  $cliente = $row["cliente"];
  $descuento = $row["descuento"];
  $nro_documento = $row["nro_documento"];
		

  /**** Almacen por defecto ****/
  $sql = "SELECT valor1 AS almacen FROM parametro WHERE codigo = '002';";
  $almacen = ExecuteScalar($sql);

  /**** Moneda por defecto ****/
  $sql = "SELECT valor1 FROM parametro WHERE codigo = '006' AND valor2 = 'default';";
  $moneda = ExecuteScalar($sql);

  $proveedor = 1;
  $nota = $txtNota; // "DEVOLUCION DE ARTICULO";

  $sw = true;
  $sql = "SELECT 
			* 
		FROM 
			entradas 
		WHERE 
			tipo_documento = 'TDCNRP' AND 
			cliente = $cliente AND nota = '$nota' AND 
			DATE_FORMAT(fecha, '%Y-%m-%d') = DATE_FORMAT(NOW(), '%Y-%m-%d');";
  if($row = ExecuteRow($sql)) { 
    $sw = false;
    $error = "La devoluci&oacute;n se ejecut&oacute; pero hubo un intento de duplicaci&oacute;n de la misma. Por favor REVISE.";
  }
  
  if($sw) {
    $sql = "INSERT INTO entradas
    			(id, tipo_documento, username, fecha, 
    			proveedor, nro_documento, almacen, estatus, 
    			id_documento_padre, consignacion, cliente, moneda, nota, descuento)
    		VALUES 
    			(NULL, 'TDCNRP', '" . CurrentUserName() . "', NOW(), 
    			$proveedor, '', '$almacen', 'PROCESADO', 
    			$id_old, 'N', $cliente, '$moneda', '$nota', $descuento);";
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
        /*
        $sql = "SELECT IFNULL(ultimo_costo, 0) AS ultimo_costo FROM articulo WHERE id = '$articulo';";
        if($row = ExecuteRow($sql)) 
          $costo = floatval($row["ultimo_costo"]);
        else
    		  $costo = floatval($_POST[$control3]);
        */
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

    		ActualizarExitenciaArticulo($articulo);
    		
    		$sw = true;
    	}
    }
  }

  if($sw) {
        $sql = "SELECT IFNULL(SUM(costo), 0) AS costo
        		FROM entradas_salidas
        		WHERE
        			id_documento = $newid
        			AND tipo_documento = 'TDCNRP'";
        		$monto_moneda = floatval(ExecuteScalar($sql));
            $monto_moneda = ($monto_moneda - ($monto_moneda*($descuento/100)));

        $sql = "SELECT tasa FROM tasa_usd
                WHERE moneda = '$moneda' ORDER BY id DESC LIMIT 0, 1;";
        $tasa = ExecuteScalar($sql);

        $referencia = $newid;
        $metodo_pago = "DV";
        $monto_bs = $tasa*$monto_moneda;
        $tasa_usd = $tasa;
        $monto_usd = $monto_moneda;
        $username = CurrentUserName();

        if($descuento >= 25) {
            $sql = "SELECT IFNULL(MAX(nro_recibo), 0)+1 AS nro FROM abono2 WHERE 1;";
            $nro_recibo = ExecuteScalar($sql);

            $sql = "INSERT INTO
            	      	abono2 
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
                          
            $sql = "SELECT IFNULL(MAX(nro_recibo), 0)+1 AS nro FROM recarga2 WHERE 1;";
            $nro_recibo = ExecuteScalar($sql);

            //quedé aqui
            $sql = "INSERT INTO recarga2(
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
            $sql = "SELECT IFNULL(SUM(monto_usd), 0) AS saldo FROM recarga2
            		WHERE cliente = $cliente;";
            $saldo = ExecuteScalar($sql);
            $sql = "UPDATE recarga2 SET saldo = $saldo WHERE id = $id;";
            Execute($sql);

            $sql = "SELECT SUM(monto_usd) AS pago FROM recarga2 WHERE abono = $Abono;";
            $monto_abono = ExecuteScalar($sql);

            $sql = "UPDATE abono2 SET pago = $monto_abono WHERE id = $Abono";
            Execute($sql);
        }
        else {
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
        }


        $sql = "UPDATE entradas SET nro_documento = 'ABONO - $nro_recibo', tasa_dia = $tasa, monto_total = $monto_usd, total = $monto_usd, monto_pagar = $monto_usd, monto_usd = $monto_usd WHERE id = $referencia;";
        Execute($sql);

        $sql = "UPDATE salidas SET email = 'DEVOLUCION' WHERE id = $id_old;";
        Execute($sql);

				/*** Se evalua si se reversan puntos por la venta de cada articulo en la nota por la anulación de la misma ***/
				$sql = "SELECT 
							a.cantidad_movimiento, c.codigo_ims AS referencia, c.puntos_ventas 
						FROM 
							entradas_salidas AS a 
							JOIN entradas AS b ON b.id = a.id_documento AND b.tipo_documento = a.tipo_documento 
							JOIN articulo AS c ON c.id = a.articulo 
						WHERE
							a.id_documento = $newid
							AND a.tipo_documento = 'TDCNRP';";
				$rows = ExecuteRows($sql);	
				foreach ($rows as $key => $value) {
					$ref = $value["referencia"];
					$cantidad_movimiento = $value["cantidad_movimiento"];
					$puntos = (-1) * intval($value["puntos_ventas"]);

					$sql = "SELECT 
								tipo, nota
							FROM 
								puntos 
							WHERE 
								cliente = $cliente AND nro_documento = '$nro_documento' AND referencia = '$ref';";
					if($row = ExecuteRow($sql)) {
						$nota = "SE HACE DEVOLUCION DE NOTA DE ENTREGA # $nro_documento Ref # $ref";

						$sql = "INSERT INTO puntos
									(id, cliente, fecha, tipo, nro_documento, referencia, puntos, saldo, nota, username)
								VALUES
									(NULL, $cliente, '" . date("Y-m-d") . "', 'DV', '$nro_documento', '$ref', $puntos, 0, '$nota', '" . CurrentUserName() . "')";
						Execute($sql);
			
						$sql = "SELECT IFNULL(SUM(puntos), 0) AS saldo FROM puntos
								WHERE cliente = $cliente;";
						$saldo = ExecuteScalar($sql);

						$sql = "SELECT LAST_INSERT_ID() AS id;";
						$iddoc = ExecuteScalar($sql);

						$sql = "UPDATE puntos SET saldo = $saldo WHERE id = $iddoc;";
						Execute($sql);		
					}
				}
				////////////////////////////////////////////////////////////////////////////////

        
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
    if($error != "" AND $newid == 0) {
      echo '<h2>!!! ' . $error . ' !!!</h2>';
    } 
    else {
    	$sql = "DELETE FROM entradas WHERE id = $newid";
    	Execute($sql);

    	echo '<h2>No ha seleccionado art&iacute;culo para devoluci&oacute;n.</h2>';
    }
  	echo '<button class="btn btn-default" onclick="$(location).attr(\'href\',\'Devoluciones\');">Haga click aquí para continuar</button>';
  }
}
?>


<?= GetDebugMessage() ?>
