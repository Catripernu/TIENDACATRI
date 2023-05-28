<?php
include '../sql/config.php';
if (isset($_GET['eliminar'])) {
	$id = $_GET['eliminar'];
	$sql = $conexion->query("DELETE FROM productos WHERE id = $id");
	$sql = $conexion->query("DELETE FROM p_datos WHERE id_producto = $id");
	$sql = $conexion->query("DELETE FROM p_infoweb WHERE id_producto = $id");
	$sql = $conexion->query("DELETE FROM p_precios WHERE id_producto = $id");
	unlink("../images/productos/ompick_$id.webp");
}
?>