<?php 
session_start();
include_once('../config.php');
include_once('../admin/datosRelevantes.php');

$id = $_SESSION['id'];
$dato = $conexion->query("SELECT nombre FROM users WHERE id = $id")->fetch_assoc();

?>
<!DOCTYPE html>
<html>
<?php include_once('../links.php'); ?>
<head>
<title><?php echo $titulo; ?></title>
<div id="fondo_head"> 
<?php 
include_once('../header.php');
include_once('../menu/header.php'); ?>
</div>
</head>
<body>
  <div id="contenido">
  	HISTORIAL DEL USUARIO <?php echo $dato['nombre'] ?>
  </div>
</body>
</html>