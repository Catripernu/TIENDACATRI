<?php 

session_start();
include '../../config.php';


$codigo = $_POST['codigo'];
$fecha = $_POST['fecha'];
$importe = $_POST['importe'];
$proveedor = $_POST['proveedor'];


$conexion->query("INSERT INTO cuentacorriente (codigo,fecha,proveedor,importe,estado) VALUES ('$codigo','$fecha','$proveedor','$importe',0)");

echo 1;

?>