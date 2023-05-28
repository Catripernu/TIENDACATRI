<?php 
session_start();
include('../config.php');
include('datosRelevantes.php');
setlocale(LC_MONETARY, 'en_US.UTF-8');
if ($_SESSION['rol'] == 1) {
$consulta = mysqli_query($conexion,"SELECT palabra, count(*) as total FROM palabrasBuscadas GROUP BY palabra HAVING COUNT(*)>0 ORDER BY total DESC");
?>
<!DOCTYPE html>
<html>
<?php include ('../links.php'); ?>
<style type="text/css">
	th {
		background: #404040;
		padding: 10px;
		color: white;
		letter-spacing: 3px;
	}
	th i {
		color:white;
	}
	td {
		background: #d9d9d9;
		padding: 1px;
		letter-spacing: 3px;
	}
	tr:hover td{
		background: #ffebcc;
	}
</style>
<head>
<title><?php echo $titulo; ?></title>
<div id="fondo_head"> 
<?php include('../header.php'); ?>
<?php include('../menu/header.php'); ?>
</div>
</head>
<body>
<div id="contenido">
<table align="center" border="0" bgcolor="black" cellpadding="1" cellspacing="1" width="70%">
	<thead>
		<th>PRODUCTOS MAS BUSCADOS</th>
		<th><a href="./REESTABLECER/eliminarPedido.php?palabras" title="ELIMINAR REGISTROS"><i class="fa fa-times-circle fa-lg fa-fw"></i></a></th>
	</thead>
<?php 
while ($datos = mysqli_fetch_array($consulta)) {
	echo '
	<tr>
		<td>'.$datos["palabra"].'</td>
		<td>'.$datos["total"].'</td>
	</tr>';
} ?>
</table>
</div>
</body>
</html>
<?php 
} else {
	header("Location: ../index.php");
}
?>