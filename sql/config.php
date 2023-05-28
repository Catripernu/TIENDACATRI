<?php  
// CONEXION A LA BASE DE DATOS
date_default_timezone_set('America/Argentina/Buenos_Aires');
try {
	$conexion = new PDO('mysql:host=localhost;dbname=proctorm_tiendacatri;charset=utf8mb4','proctorm_admin','Dni37427641');
} catch (PDOException $e){
	print "Error!".$e->getMessage();
	die();
}
?>