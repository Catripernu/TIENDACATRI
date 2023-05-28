<style type="text/css">
	.tablaVerPedidos td{
		padding: 10px;
	}
	.tablaVerPedidosTR {
		font-weight: bold;
		text-transform: uppercase;
		cursor:pointer;
		font-size: 18px;
		color: white;
	}
	.tablaPendientes {
		background-color: #F0802E;
	}
</style>

<?php
include("../../config.php");
include("../../php/funciones.php");

$res = $conexion->query("SELECT * FROM ventascliente WHERE estado = 0 ORDER BY fecha ASC");
$contador = 0;
while($timi = $res->fetch_array()){
$id_venta = $timi['ID'];
$id_usuario = $timi['id_usuario'];
$fecha = $timi['fecha'];
$total = $timi['total'];


	$existencia_vendedor = $conexion->query("SELECT ID FROM datos_compradornr WHERE ID = $id_venta")->num_rows;
	if ($existencia_vendedor && $id_usuario) {
		// VENDEDOR
		$dato_vendedor = $conexion->query("SELECT nombre FROM users WHERE id = $id_usuario")->fetch_array();
		$dato_comprador = $conexion->query("SELECT nombre,apellido,telefono FROM datos_compradornr WHERE ID = $id_venta")->fetch_array();
		$nombre = $dato_comprador['nombre']." ".$dato_comprador['apellido']." (".$dato_vendedor['nombre'].")";
		$telefono = "VENDEDOR/A";
	} else {
		if ($id_usuario) {
			// USUARIO
			$usuario = $conexion->query("SELECT nombre,apellido,telefono FROM users WHERE id = $id_usuario")->fetch_array();
			$nombre = "<font title='USUARIO REGISTRADO'>".$usuario['nombre']." ".$usuario['apellido']." (R)</font>";
			$telefono = $usuario['telefono'];
		} else {
			// VISITANTE
			$visitante = $conexion->query("SELECT nombre,apellido,telefono FROM datos_compradornr WHERE ID = $id_venta")->fetch_array();
			$nombre = "<font color='black' title='CONSUMIDOR FINAL'>".$visitante['nombre']." ".$visitante['apellido']." (C.F)</font>";
			$telefono = $visitante['telefono'];
		}
	}

	$contador = $contador +1;

	?>
	<table cellspacing="0" align="center" cellpadding="0" width="80%">
		<tr class="tablaVerPedidosTR tablaPendientes" onMouseup="window.location='ver_pedidos.php?verPedido=<?php echo $id_venta ?>'">
			<td>#<?php echo $contador; ?></td>
			<td width="40%" align="left"> <?php echo $nombre ?></td>
			<td><?php echo $telefono ?></td>
			<td><?php echo $fecha ?></td>
			<td>TOTAL: <?php precio($total) ?></td>
		</tr>
		<tr class="break" />
	</table>
	<?php
}
?>