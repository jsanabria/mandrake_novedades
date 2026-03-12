<?php
session_start();
include "connect.php";

$id_articulo = $_GET["id"] ?? "";

if ($id_articulo != "") {
    // 1. Sanitizar el ID: Asegúrate de que sea un número para evitar errores de sintaxis
    $id_seguro = intval($id_articulo);

    // 2. Ejecutar consulta y capturar el resultado
    $sql = "SELECT 
                ROUND((precio - (precio * (descuento/100))), 2) AS precio, 
                precio2 FROM articulo WHERE id = " . $id_seguro;
    $rs = mysqli_query($link, $sql);

    // 3. Validar: ¿La consulta fue exitosa?
    if ($rs) {
        $row = mysqli_fetch_array($rs, MYSQLI_ASSOC); // Usamos ASSOC para nombres de columna claros
        echo json_encode($row ?: ["precio" => 0, "precio2" => 0]);
    } else {
        // Si hay error en la consulta, imprime el error de MySQL para saber qué pasa
        echo json_encode(["error" => mysqli_error($link)]);
    }
}
?>