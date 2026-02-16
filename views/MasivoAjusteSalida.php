<?php

namespace PHPMaker2021\mandrake;

// Page object
$MasivoAjusteSalida = &$Page;
?>
<?php
$id = 9753;
$cliente = 1;
//Abrimos nuestro archivo
$file01 = "C:\Users\Junior\Documents\Libro1.csv";
$archivo = fopen($file01, "r");
//Lo recorremos
$codigo = "";
$nombre = "";
$precio = 0.00;
// include_once("include/pdv_linea_guardar.class.php");
$insart = new PdvLineaGuardar();
while (($datos = fgetcsv($archivo, 0, ";")) == true) {
	$num = count($datos);
	//Recorremos las columnas de esa linea
	if($num == 3) {
		for($columna = 0; $columna < $num; $columna++) {
			switch($columna) {
			case 0:	
				$cod_articulo = $datos[$columna];
				break;
			case 1:	
				$nombre = $datos[$columna];
				break;
			case 2:	
				$cantidad = intval($datos[$columna]);
				break;
			}
		}
		$insart->insertar_articulo("TDCASA", $id, $cliente, $cod_articulo, $cantidad);
	}
	$codigo = "";
	$nombre = "";
	$precio = 0.00;
}
//Cerramos el archivo
fclose($archivo);
header("SalidasList?tipo=TDCASA");


class PdvLineaGuardar {
  var $tipo_documento;
  var $salida;
  var $cliente;
  var $cod_articulo;
  var $articulo;
  var $cantidad;
  var $descuento;
  var $cantidad_en_mano;
  var $cantidad_unidad;
  var $articulo_inventario;
  var $alicuota;
  var $almacen;
  var $costo;
  var $costo_full;
  var $link;
  var $existe_articulo;
  function __construct()
  {
  }
  function pedido_abierto()
  {
    $sql = "SELECT estatus FROM salidas WHERE tipo_documento = '" . $this->tipo_documento . "' AND id = '" . $this->salida . "';"; 
    $status_doc = ExecuteScalar($sql);
    if($status_doc == "NUEVO") 
      return true;
    else 
      return false;
  }
  function datos_articulo()
  {
  	$this->existe_articulo = false;
    $sql = "SELECT 
              IFNULL(descuento, 0) AS descuento, 
              (IFNULL(cantidad_en_mano, 0)+IFNULL(cantidad_en_pedido, 0))-IFNULL(cantidad_en_transito, 0) AS cantidad_en_mano, 
              unidad_medida_defecto AS unidad_medida, cantidad_por_unidad_medida, articulo_inventario, id, fabricante, ultimo_costo  
            FROM 
              articulo WHERE codigo_ims = '" . $this->cod_articulo . "';"; 
    if($row = ExecuteRow($sql)) 
    {
      $this->descuento = floatval($row["descuento"]);
      $this->cantidad_en_mano = floatval($row["cantidad_en_mano"]);
      $this->cantidad_unidad = 1; // floatval($row["cantidad_por_unidad_medida"]);
      $this->articulo_inventario = $row["articulo_inventario"];
      $this->unidad_medida = $row["unidad_medida"];
      $this->articulo = $row["id"];
      $this->fabricante = $row["fabricante"];
      $sql = "SELECT alicuota FROM articulo WHERE id = '" . $this->articulo . "';"; 
      $codigo_alicuota = ExecuteScalar($sql);
      $sql = "SELECT alicuota FROM alicuota
          WHERE codigo = '$codigo_alicuota' AND activo = 'S';";
      $this->alicuota = floatval(ExecuteScalar($sql));
      $sql = "SELECT valor1 AS almacen FROM parametro WHERE codigo = '002';";
      $this->almacen = ExecuteScalar($sql);
      $this->cantidad = $this->cantidad_unidad * $this->cantidad;
      $this->costo = $row["ultimo_costo"];
      $this->total = $this->costo * $this->cantidad;
      $this->cantidad_movimiento = $this->cantidad * (-1);
      $this->costo_full = $this->costo/(1-($this->descuento/100));
      $this->existe_articulo = true;
    }
  }
  // function insertar_articulo($tipo_documento="TDCPDC", $salida, $cliente, $cod_articulo, $cantidad)
  function insertar_articulo($tipo_documento, $salida, $cliente, $cod_articulo, $cantidad) 
  { 
    $this->tipo_documento = $tipo_documento;
    $this->salida = $salida;
    $this->cliente = $cliente;
    $this->cod_articulo = $cod_articulo;
    $this->cantidad = $cantidad;
    if($this->pedido_abierto()) 
    {
      $this->datos_articulo();
    } 
    else return false;
    $this->costo_full = round($this->costo_full, 2);
    
    if($this->existe_articulo) {
      $sql = "INSERT INTO entradas_salidas
            (id, tipo_documento, id_documento, 
            fabricante, articulo, almacen, 
            cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, cantidad_movimiento, 
            costo_unidad, costo, alicuota, descuento, precio_unidad_sin_desc)
          VALUES 
            (NULL, '$this->tipo_documento', $this->salida, 
            $this->fabricante, $this->articulo, '$this->almacen', 
            $this->cantidad, '$this->unidad_medida', $this->cantidad_unidad, (-1)*$this->cantidad, 
            $this->costo, $this->total, $this->alicuota, $this->descuento, $this->costo_full);
          ";
      Execute($sql);
      $sql = "SELECT COUNT(DISTINCT alicuota ) AS cantidad FROM entradas_salidas
              WHERE tipo_documento = '$this->tipo_documento' AND id_documento = '$this->salida';";
      if(intval(ExecuteScalar($sql)) > 1) $alicuota = 0;
      else {
        $sql = "SELECT DISTINCT alicuota FROM entradas_salidas
                WHERE tipo_documento = '$this->tipo_documento' AND id_documento = '$this->salida';";
        $alicuota = floatval(ExecuteScalar($sql));
      }

      // Se actualiza el encabezado del pedido de venta //
      $sql = "SELECT
                SUM(costo) AS costo, 
                SUM((costo * (alicuota/100))) AS iva, 
                SUM(costo) + SUM((costo * (alicuota/100))) AS total 
              FROM 
                entradas_salidas
              WHERE tipo_documento = '$this->tipo_documento' AND id_documento = '$this->salida'";
      $row = ExecuteRow($sql);
      $costo = floatval($row["costo"]);
      $iva = floatval($row["iva"]);
      $total = floatval($row["total"]);
      $sql = "UPDATE salidas 
              SET
                monto_total = $costo,
                alicuota_iva = $alicuota, 
                iva = $iva,
                total = $total
              WHERE tipo_documento = '$this->tipo_documento' AND id = '$this->salida'";
      Execute($sql);

      ActualizarExitenciaArticulo($this->articulo);
    }

    return true;
  }
}
?>

<?= GetDebugMessage() ?>
