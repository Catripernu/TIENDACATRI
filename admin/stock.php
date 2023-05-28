<?php 
session_start();
include('../config.php');
include('datosRelevantes.php');
setlocale(LC_MONETARY, 'en_US.UTF-8');
if ($_SESSION['rol'] == 1) {
?>
<!DOCTYPE html>
<html>
<?php include ('../links.php'); ?>
<head>
<title><?php echo $titulo; ?></title>
<div id="fondo_head"> 
<?php include('../header.php'); ?>
<?php include('../menu/header.php'); ?>
</div>
</head>
<style type="text/css">
.ADMINcuadroEstadisticas a{
  text-decoration: none;
  font-weight: bold;
  letter-spacing: 2px;
  color: #e67300;
}
.ADMINcuadroEstadisticas a:hover{
  color: #ff9933;
}
</style>
<body>
<div id="contenido">
	<div class="ADMINcuadroEstadisticas">
		<table border="0" cellpadding="0" cellspacing="0" align="center">
			<thead>
				<th>NOMBRE</th>
				<th>STOCK</th>
				<th>MOD</th>
			</thead>
		<?php 
		$consulta = mysqli_query($conexion,"SELECT * FROM p_datos INNER JOIN productos ON p_datos.id_producto=productos.id WHERE stock > '0' AND stock < '10'");
		$total = mysqli_num_rows($consulta);
		for ($i=0; $i < $total; $i++) { 
			$datos = mysqli_fetch_array($consulta);
			if ($i % 2 == 0) {
				$colorFondo = "#686868";
			} else {
				$colorFondo = "#5A5A5A";
			}
			echo '<tr style="background: '.$colorFondo.';">
				<td>'.$datos['nombre'].'</td>
				<td>'.$datos['stock'].'</td>
				<td><a href="editar_producto.php?id='.$datos['id'].'">EDITAR</a></td>
			</tr>';
		}	?>
		</table>
	</div>
</div>
</body>
</html>
<?php 
} else {
	header("Location: ../index.php");
}
?>