<?php
include '../../config.php';
if (isset($_GET['eliminar'])) {
	$id = $_GET['eliminar'];
	mysqli_query($conexion, "DELETE FROM users WHERE id='$id'");
}
?>