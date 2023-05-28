<?

/////// CONEXIÓN A LA BASE DE DATOS /////////

include '../../config.php';



//////////////// VALORES INICIALES ///////////////////////



$tabla="";



///////// LO QUE OCURRE AL TECLEAR SOBRE EL INPUT DE BUSQUEDA ////////////

if(isset($_POST['vendedor']))

{

	$q=$conexion->real_escape_string($_POST['vendedor']);

	$consulta_ventas = $conexion->query("SELECT vc.id_usuario,vc.ID,vc.fecha,vc.total,dc.nombre,dc.apellido,dc.telefono FROM ventascliente vc INNER JOIN datos_compradornr dc ON vc.ID=dc.ID INNER JOIN users u ON vc.id_usuario=u.id WHERE (vc.id_usuario != 0 AND estado = 1) AND (u.nombre LIKE '%".$q."%' OR u.username LIKE '%".$q."%' OR u.apellido LIKE '%".$q."%' OR vc.ID LIKE '%".$q."%') ORDER BY vc.ID DESC");

} else {

	$consulta_ventas = $conexion->query("SELECT * FROM ventascliente vc INNER JOIN datos_compradornr dc ON vc.ID=dc.ID WHERE vc.id_usuario != 0 AND vc.estado = 1 ORDER BY vc.ID DESC");

}

if ($consulta_ventas->num_rows > 0){

	$tabla .= '<table align="center" width="80%" border="0" cellpadding="0" cellspacing="0">

					<tr class="bg-primary">

						<td>ID VENTA</td>

						<td>FECHA</td>

						<td>VENDEDOR</td>

						<td>CLIENTE</td>

						<td>TOTAL</td>

					</tr>';

	while($datos = $consulta_ventas->fetch_assoc()){

		$id_vendedor = $datos['id_usuario'];

		$vendedor = $conexion->query("SELECT * FROM users WHERE id = $id_vendedor")->fetch_array();

		$tabla.=

		'<tr class="seleccion">

			<td>#'.$datos['ID'].'</td>

			<td><a href="./ver_pedidos.php?verPedido='.$datos['ID'].'">'.date("d/m/Y", strtotime($datos['fecha'])).'</a></td>

			<td>'.$vendedor['apellido'].', '.$vendedor['nombre'].'</td>

			<td>'.$datos['nombre'].', '.$datos['apellido'].' ('.$datos['telefono'].')</td>

			<td>$'.money_format('%.2n', $datos['total']).'</td>

		 </tr>

		';

	}



	$tabla.='</table>';

} else {

		$tabla="No se encontraron coincidencias con sus criterios de búsqueda.";

	}





echo $tabla;

?>