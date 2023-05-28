<?php 
session_start();
include('../config.php');
include('datosRelevantes.php');
if ($_SESSION['rol'] == 1) {
$consulta = mysqli_query($conexion,"SELECT * FROM olvPassword");
?>
<!DOCTYPE html>
<html>
<?php include ('../links.php'); ?>
<head>
<style type="text/css">
	#tablaOlvPassword th{
		padding: 10px;
		background: #990099;
		color: white;
	}
	#tablaOlvPassword td{
		padding: 5px;
		background: #f2f2f2;
		border-top-style: solid;
	}
	#tablaOlvPassword a {
		text-decoration: none;
		color:black;
		padding: 5px;
	}
	#tablaOlvPassword .olvPassword:hover{
		color:green;
	}
	#tablaOlvPassword a:hover{
		color:red;
	}
</style>
<title><?php echo $titulo; ?></title>
<div id="fondo_head"> 
<?php include('../header.php'); ?>
<?php include('../menu/header.php'); ?>
</div>
</head>
<body>
<div id="contenido">
	<?php if (mysqli_num_rows($consulta)) { ?>
	<table align="center" border="0" cellspacing="0" cellpadding="0" width="60%" id="tablaOlvPassword">
		<thead>
			<th width="5%">#</th>
			<th width="20%">USERNAME</th>
			<th width="50%">NOMBRE</th>
			<th width="20%">TELEFONO</th>
			<th width="5%" colspan="2">OPCIONES</th>
		</thead>
		<?php
		$contador = 1;
		while ($dato = mysqli_fetch_array($consulta)) { 
			$telefono = $dato['telefono'];
			$userId = mysqli_query($conexion,"SELECT * FROM users WHERE telefono = '$telefono'");
			$id = mysqli_fetch_array($userId);
			$username = $id['username'];
			$nombre = $dato['nombre'];
			$apellido = $dato['apellido'];
			$id = $id['id'];			
		?>
		<tr>
			<td><?php echo $contador; ?></td>
			<td><?php echo $username; ?></td>
			<td><?php echo $nombre ?>, <?php echo $apellido ?></td>
			<td><?php echo $telefono ?></td>
			<td><a href="#" class="fa fa-check-circle fa-lg fa-fw olvPassword" data-id="<?php echo $id ?>"></a></td>
			<td><a class="fa fa-times-circle fa-lg fa-fw eliminarPedido" data-telefono="<?php echo $telefono ?>" href="#"></a></td>
		</tr>
		<?php 
		$contador = $contador +1;
		} ?>
	</table>
	<?php } else {
		echo "NO TIENE NINGUNA SOLICITUD PARA RECUPERAR UNA CUENTA.";
	}?>
</div>
</body>
</html>
<script>
	$(".olvPassword").click(function(e){
		e.preventDefault();
		var id = $(this).data('id');
		var mensaje = confirm("¿Seguro que desea reestablecer la clave?");
		if (mensaje) {
			$.ajax({
				method:'POST',
				url:'REESTABLECER/olvPassword.php',
				data:{
					id:id
				}
			}).done(function(respuesta){
				window.location.reload();
				alert("Contraseña reestablecida: djdistribuciones");
			});
		}			
	});
	$(".eliminarPedido").click(function(e){
		e.preventDefault();
		var id = $(this).data('telefono');
		var mensaje = confirm("¿Seguro que va a eliminar el pedido?");
		if (mensaje) {
			$.ajax({
				method:'POST',
				url:'REESTABLECER/eliminarPedido.php',
				data:{
					id:id
				}
			}).done(function(respuesta){
				window.location.reload();
				alert("Pedido eliminado exitosamente");
			});
		}
	})
</script>
<?php 
} else {
header("Location: ../index.php");
}
?>