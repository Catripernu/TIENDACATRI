<?php 
include("../../config.php");
if (isset($_POST['id'])) {
	$id = $_POST['id'];
	$clave = password_hash("djdistribuciones", PASSWORD_DEFAULT);
	$consulta = "SELECT telefono FROM users WHERE id = '$id'";
	$update = "UPDATE users SET password = '$clave', permiso_password = '1' WHERE id='$id'"; 	
 	$query = mysqli_query($conexion,$consulta);
 	$datos = mysqli_fetch_array($query);   
 	$telefono = $datos['telefono']; 
 	echo $telefono;
 	$delete = "DELETE FROM olvPassword WHERE telefono = '$telefono'"; 	
 	mysqli_query($conexion, $update);
 	mysqli_query($conexion, $delete);
 } ?>