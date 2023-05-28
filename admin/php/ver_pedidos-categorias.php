<?php
for ($i=1; $i <= $consulta_pedidos->num_rows; $i++) { 
		 	$datos_venta = $consulta_pedidos->fetch_array();
		 	$id_venta = $datos_venta['ID'];
		 	$id_usuario = $datos_venta['id_usuario'];
		 	$fecha = $datos_venta['fecha'];
		 	$total = $datos_venta['total'];

		 	$consulta_vendedor = $conexion->query("SELECT ID FROM datos_compradornr WHERE ID = $id_venta")->num_rows;
		 	// EXISTENCIA DE VENTA REGISTRADA EN DATOS_COMPRADORNR
		 	if ($consulta_vendedor && $id_usuario != 0) {
		 		// VENDEDOR
		 		$datos_vendedor = $conexion->query("SELECT nombre, apellido FROM users WHERE id = $id_usuario")->fetch_array();
		 		$datos_usuario = $conexion->query("SELECT nombre,apellido,telefono FROM datos_compradornr WHERE ID = $id_venta")->fetch_array();
		 		$mostrar_datos = $datos_usuario['nombre'].", ".$datos_usuario['apellido']." (".$datos_vendedor['nombre'].", ".$datos_vendedor['apellido'].")";
		 		$telefono = "VENDEDOR/A";
		 	} else {
		 		if (!$consulta_vendedor && $id_usuario != 0) {
		 			// REGISTRADO
		 			$datos_usuario = $conexion->query("SELECT nombre, apellido, telefono FROM users WHERE id = $id_usuario")->fetch_array();
		 			$mostrar_datos = $datos_usuario['nombre'].", ".$datos_usuario['apellido']." (R)";
		 			$telefono = $datos_usuario['telefono'];
		 		} else {
		 			// VISITANTE
		 			$datos_usuario = $conexion->query("SELECT nombre, apellido, telefono FROM datos_compradornr WHERE ID = $id_venta")->fetch_array();
		 			$mostrar_datos = $datos_usuario['nombre'].", ".$datos_usuario['apellido']." (C.F)";
		 			$telefono = $datos_usuario['telefono'];
		 		}		 		
		 	}
?>
<tr onMouseup="window.location='ver_pedidos.php?verPedido=<?php echo $id_venta ?>'">
 	<td>#<?php echo $i; ?></td>
	<td align="left"><?php echo $mostrar_datos; ?></td>
	<td><?php echo $fecha; ?></td>
	<td><?php echo $telefono; ?></td>
	<td><?php precio($total); ?></td>
</tr>
<tr class="break" />
<?php } ?>