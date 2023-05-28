<?php 
include("../../config.php");
if (isset($_POST['id'])) {
$telefono = $_POST['id'];
$delete = "DELETE FROM olvPassword WHERE telefono = '$telefono'"; 	
 	mysqli_query($conexion, $delete);
} 
if (isset($_GET['palabras'])) {
	mysqli_query($conexion,"DELETE FROM palabrasBuscadas");
	header("Location: ../palabras.php");
}
?>