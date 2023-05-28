<?php
include '../../config.php';
if (isset($_GET['cancelar'])) {
	$id = $_GET['cancelar'];
	mysqli_query($conexion, "UPDATE ventascliente SET estado=2 WHERE ID='$id'");
}
?>