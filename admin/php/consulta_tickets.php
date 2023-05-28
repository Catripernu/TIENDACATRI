<?
/////// CONEXIÓN A LA BASE DE DATOS /////////
include '../../config.php';

//////////////// VALORES INICIALES ///////////////////////

$tabla="";

///////// LO QUE OCURRE AL TECLEAR SOBRE EL INPUT DE BUSQUEDA ////////////
if(isset($_POST['ticket']))
{
	$q=$conexion->real_escape_string($_POST['ticket']);
	// $consulta_ventas = $conexion->query("SELECT vc.ID,vc.id_usuario FROM ventascliente vc INNER JOIN datos_compradornr dc ON vc.ID=dc.ID WHERE vc.estado = 1 AND (vc.ID LIKE '%".$q."%' OR dc.nombre LIKE '%".$q."%' OR dc.apellido LIKE '%".$q."%' OR dc.telefono LIKE '%".$q."%') ORDER BY vc.ID DESC");


	$consulta_ventas = $conexion->query("SELECT * FROM ventascliente vc INNER JOIN datos_compradornr dc ON vc.ID=dc.ID WHERE vc.estado = 1 AND (dc.nombre LIKE '%".$q."%' OR dc.apellido LIKE '%".$q."%' OR dc.telefono LIKE '%".$q."%' OR vc.ID LIKE '%".$q."%')");
} else {
	$consulta_ventas = $conexion->query("SELECT * FROM ventascliente WHERE estado = 1 ORDER BY ID DESC");
}
if ($consulta_ventas->num_rows > 0){

	while ($datos = $consulta_ventas->fetch_assoc()) {
		$id_venta = $datos['ID'];
		$id_usuario = $datos['id_usuario'];

		$consulta_vendedor = $conexion->query("SELECT * FROM datos_compradornr WHERE ID = $id_venta");
		$existencia_vendedor = $consulta_vendedor->num_rows;

		if ($id_usuario != 0) {
			$datos_usuario = $conexion->query("SELECT * FROM users WHERE id = $id_usuario")->fetch_array(); 
		} else {
			$datos_usuario = $consulta_vendedor->fetch_assoc();
		}

		if ((!$existencia_vendedor && $id_usuario != 0) || $id_usuario == 0) {
			echo "#".$id_venta." - ".$datos_usuario['nombre']."<br>";
		}


		
	}








	// $tabla .= '<table align="center" width="80%" border="0" cellpadding="0" cellspacing="0">
	// 				<tr class="bg-primary">
	// 					<td>ID VENTA</td>
	// 					<td>FECHA</td>
	// 					<td>VENDEDOR</td>
	// 					<td>CLIENTE</td>
	// 					<td>TOTAL</td>
	// 				</tr>';
	// while($datos = $consulta_ventas->fetch_assoc()){
	// 	$id_vendedor = $datos['id_usuario'];
	// 	$vendedor = $conexion->query("SELECT * FROM users WHERE id = $id_vendedor")->fetch_array();
	// 	$tabla.=
	// 	'<tr class="seleccion">
	// 		<td>#'.$datos['ID'].'</td>
	// 		<td><a href="./ver_pedidos.php?verPedido='.$datos['ID'].'">'.date("d/M/Y", strtotime($datos['fecha'])).'</a></td>
	// 		<td>'.$vendedor['apellido'].', '.$vendedor['nombre'].'</td>
	// 		<td>'.$datos['nombre'].', '.$datos['apellido'].'</td>
	// 		<td>$'.money_format('%.2n', $datos['total']).'</td>
	// 	 </tr>
	// 	';
	// }

	$tabla.='</table>';
} else {
		$tabla="No se encontraron coincidencias con sus criterios de búsqueda.";
	}


echo $tabla;
?>