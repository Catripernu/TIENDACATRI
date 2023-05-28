<?php 
session_start();
include '../../config.php';

$id_vendedor = $_SESSION['id'];
$rol = $_SESSION['rol'];

echo $id_vendedor."<br>".$rol;
$id = $_GET['id'];
if ($rol == 1) {
	$accion = $GLOBALS['conexion']->query("DELETE FROM agenda WHERE id_agenda = $id");
} else if ($rol == 2){
	$accion = $GLOBALS['conexion']->query("DELETE FROM agenda WHERE (id_agenda = $id and id_vendedor = $id_vendedor)");	
}

 ?>