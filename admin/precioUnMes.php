<?php
session_start(); 
include_once("../config.php");
include_once("datosRelevantes.php");
if ($_SESSION['rol'] === 1) {
	if (isset($_POST['actualizar'])) {

		$id = $_POST['id'];
		$precio_c = $_POST['precio_c'];
		date_default_timezone_set('America/Argentina/Buenos_Aires');
		$fecha = date('Y-m-d H:i:s');

		if ($precio_c >= 100) {
			$precio_v = ($precio_c*$porInc1000)+$precio_c;
		} else {
			$precio_v = ($precio_c*$porInc100)+$precio_c;
		}

		$conexion->query("UPDATE p_precios SET precio_c='$precio_c', precio_v='$precio_v' WHERE id_producto='$id'");
		$conexion->query("UPDATE p_infoweb SET fecha_ultimo_precio = '$fecha' WHERE id_producto='$id'");
		$conexion->query("UPDATE p_datos SET stock = '100' WHERE id_producto='$id'");

		header("Location: precios.php");
	}
} else {
	header("Location: ../index.php");
}
?>