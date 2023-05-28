<?php 
session_start();
include('../config.php');
include('datosRelevantes.php');
setlocale(LC_MONETARY, 'en_US.UTF-8');
if ($_SESSION['rol'] == 1) {
$consultaVentas = mysqli_query($conexion,"SELECT * FROM p_infoweb WHERE venta_total > 0 ORDER BY venta_total DESC LIMIT 10 ");
$consultaRecientes = mysqli_query($conexion,"SELECT * FROM productos ORDER BY id DESC LIMIT 15 ");
$consultaUsuario = mysqli_query($conexion,"SELECT * FROM users WHERE cant_compras > 0 AND rol = 0 ORDER BY cant_compras DESC LIMIT 10");
$consultaUsuariosTotales = mysqli_query($conexion,"SELECT * FROM users");
$consultaGanancia = mysqli_query($conexion,"SELECT ganancia,envio FROM ventascliente WHERE estado = 1");
$consultaPrecios = mysqli_query($conexion,"SELECT id FROM productos p INNER JOIN p_precios pp ON p.id=pp.id_producto INNER JOIN p_infoweb pin ON p.id=pin.id_producto INNER JOIN p_datos pd ON p.id=pd.id_producto WHERE pp.precio_v > 0 AND pin.fecha_ultimo_precio != '0000-00-00' AND pd.stock > 0");
$consultaVisitasSitio = $conexion->query("SELECT * FROM visitaIp")->num_rows;
while ($resultado = mysqli_fetch_array($consultaGanancia)) {
	$ganancia = $ganancia + ($resultado['ganancia'] + $resultado['envio']);
}
// VENTAS SEMANALES Y DIARIAS
$fechaHoyCons = date("Y-m-d");
$fechaHoy = date("Y-m-d 23-59-59");
$diaHoy = date('d');
$fechaSemana = $diaHoy - 7;
$fechaSemana = date("Y-m-$fechaSemana 00-00-00");
$consultaVentasDia = mysqli_num_rows(mysqli_query($conexion,"SELECT * FROM ventascliente WHERE fecha LIKE '%".$fechaHoyCons."%' AND estado = 1"));
$consultaVentasSemanales = mysqli_num_rows(mysqli_query($conexion,"SELECT * FROM ventascliente WHERE fecha >= '$fechaSemana' AND fecha <= '$fechaHoy' AND estado = 1"));
$totalVentas = $conexion->query("SELECT estado FROM ventascliente WHERE estado = 1")->num_rows;
?>
<!DOCTYPE html>
<html>
<?php include ('../links.php'); ?>
<head>
<title><?php echo $titulo; ?></title>
<style type="text/css">
.linkWPP-verPedidos{
	color:white;
	text-decoration: none;
}
.linkWPP-verPedidos:hover{
	color:#ffff00;
}
.titulosEstadisticas{
	text-align: left;
}
.fondoPar_estadisticas{
	background: #5A5A5A;
}
.fondoImpar_estadisticas{
	background: #686868;
}
</style>
<div id="fondo_head"> 
<?php include('../header.php'); ?>
<?php include('../menu/header.php'); ?>
</div>
</head>
<body>
<div id="contenido">
	<div class="ADMINcuadroEstadisticas">
		<table border="0" cellpadding="0" cellspacing="0" align="center">
			<th colspan="2">ESTADISTICAS</th>
			<?php 
			$totalU = mysqli_num_rows($consultaUsuariosTotales);
			$precios = mysqli_num_rows($consultaPrecios);
			 ?>
			<tr class="fondoPar_estadisticas">
				<td class="titulosEstadisticas">TOTAL PRODUCTOS</td>
				<td><?php echo $precios; ?></td>
			</tr>
			<tr class="fondoImpar_estadisticas">
				<td class="titulosEstadisticas">CLIENTES REGISTRADOS</td>
				<td><?php echo $totalU; ?></td>
			</tr>
			<tr class="fondoPar_estadisticas">
				<td class="titulosEstadisticas">VENTAS TOTALES</td>
				<td><?php echo $totalVentas; ?></td>
			</tr>
			<tr class="fondoImpar_estadisticas">
				<td class="titulosEstadisticas">GANACIAS</td>
				<td><?php echo money_format('%.2n', $ganancia); ?></td>
			</tr>
			<tr class="fondoPar_estadisticas">
				<td class="titulosEstadisticas">VENTAS DE HOY</td>
				<td><?php echo $consultaVentasDia; ?></td>
			</tr>
			<tr class="fondoImpar_estadisticas">
				<td class="titulosEstadisticas">VENTAS SEMANALES</td>
				<td><?php echo $consultaVentasSemanales; ?></td>
			</tr>
			<tr class="fondoPar_estadisticas">
				<td class="titulosEstadisticas">VISITAS</td>
				<td><?php echo $consultaVisitasSitio; ?></td>
			</tr>
		</table>
	<br>
		<table border="0" cellpadding="0" cellspacing="0" align="center">
			<th colspan="3" style="background: #00997a;">PRODUCTOS MAS VENDIDOS</th>
			<tr style="background: #32C4A5;">
				<td>#</td>
				<td>NOMBRE PRODUCTO</td>
				<td>VENTAS</td>
			</tr>
			<?php 
			$posicion = 1;
			while ($dato = mysqli_fetch_array($consultaVentas)) {
				$id = $dato['id_producto'];
				$producto = mysqli_fetch_array(mysqli_query($conexion,"SELECT nombre FROM productos WHERE id = '$id'"));
				if ($posicion % 2 == 0) { ?>
					<tr style="background: #517870;">
						<td><?php echo $posicion; ?></td>
						<td><?php echo $producto['nombre']; ?></td>
						<td><?php echo $dato['venta_total']; ?></td>
					</tr>
					<?php } else { ?>
					<tr style="background: #92B9B1;">
						<td><?php echo $posicion; ?></td>
						<td><?php echo $producto['nombre']; ?></td>
						<td><?php echo $dato['venta_total']; ?></td>
					</tr>
			<?php
				}	
			$posicion++;
			}
			?>
		</table>
	<br>
		<table border="0" cellpadding="0" cellspacing="0" align="center">
			<th colspan="3" style="background: #663300;">PRODUCTOS AGREGADOS RECIENTEMENTE</th>
			<tr style="background: #804000;">
				<td>#</td>
				<td>NOMBRE PRODUCTO</td>
				<td>PRECIO</td>
			</tr>
			<?php 
			$posicion = 1;
			while ($dato = mysqli_fetch_array($consultaRecientes)) {
				$id = $dato['id'];
				$precio = mysqli_fetch_array(mysqli_query($conexion,"SELECT * FROM p_precios WHERE id_producto = '$id'"));
				if ($posicion % 2 == 0) { ?>
					<tr style="background: #804000;">
						<td><?php echo $posicion; ?></td>
						<td><?php echo $dato['nombre']; ?></td>
						<td>$<?php echo $precio['precio_v']; ?></td>
					</tr>
					<?php } else { ?>
					<tr style="background: #b35900;">
						<td><?php echo $posicion; ?></td>
						<td><?php echo $dato['nombre']; ?></td>
						<td>$<?php echo $precio['precio_v']; ?></td>
					</tr>
			<?php
				}	
			$posicion++;
			}
			?>
		</table>
	<br>
		<table border="0" cellpadding="0" cellspacing="0" align="center">
			<th colspan="3" style="background: #7A1048;">USUARIOS DESTACADOS</th>
			<tr style="background: #901154;">
				<td>#</td>
				<td>USUARIO</td>
				<td>COMPRAS</td>
			</tr>
			<?php 
			$posicion = 1;
			while ($dato = mysqli_fetch_array($consultaUsuario)) {
				$nombre = $dato['nombre'];
				$apellido = $dato['apellido'];
				$compras = $dato['cant_compras'];
				$telefono = $dato['telefono'];
				if ($posicion % 2 == 0) { ?>
					<tr style="background: #A24E7A;">
					<td><?php echo $posicion; ?></td>
					<td><?php echo $nombre; ?>, <?php echo $apellido; ?> (<a target="blank" class="linkWPP-verPedidos" href="https://api.whatsapp.com/send?phone=54<?php echo $telefono; ?>&text=Hola%2C%20me%20comunico%20con%20usted%20por%20la%20compra%20que%20realizo%20en%20DjDistribuciones.com.ar"><?php echo $telefono; ?></a>)</td>
					<td><?php echo $compras; ?></td>
					</tr>
					<?php } else { ?>
					<tr style="background: #862A5A;">
					<td><?php echo $posicion; ?></td>
					<td><?php echo $nombre; ?>, <?php echo $apellido; ?> (<a target="blank" class="linkWPP-verPedidos" href="https://api.whatsapp.com/send?phone=54<?php echo $telefono; ?>&text=Hola%2C%20me%20comunico%20con%20usted%20por%20la%20compra%20que%20realizo%20en%20DjDistribuciones.com.ar"><?php echo $telefono; ?></a>)</td>
					<td><?php echo $compras; ?></td>
					</tr>
			<?php
				}	
			$posicion++;
			}
			?>
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