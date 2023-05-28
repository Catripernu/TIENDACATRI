<?php 

//QUERY PRODUCTOS
$consultaProducto = $conexion->query("SELECT * FROM productos WHERE id = $id");
$consultaInfo = $conexion->query("SELECT * FROM p_infoweb WHERE id_producto = $id");
$consultaDatos = $conexion->query("SELECT * FROM p_datos WHERE id_producto = $id");
$consultaPrecios = $conexion->query("SELECT * FROM p_precios WHERE id_producto = $id");

//FETCHS PRODUCTOS
$producto = $consultaProducto->fetch_assoc();
$p_infoweb = $consultaInfo->fetch_assoc();
$p_datos = $consultaDatos->fetch_assoc();
$p_precios = $consultaPrecios->fetch_assoc();

?>