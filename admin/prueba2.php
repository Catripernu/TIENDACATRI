<?php 
require("../config.php");

$id = $_GET['vendedor'];
$total = 0;

if (isset($_GET['start'])) {
	$fecha = $_GET['start'];
	$fecha_end = $_GET['end'];

	$consulta_ventas = $conexion->query("SELECT * FROM ventascliente vc INNER JOIN datos_compradornr dc ON vc.ID=dc.ID WHERE vc.estado = 1 and vc.id_usuario = $id and (vc.fecha >= '$fecha 00:00:00' AND vc.fecha <= '$fecha_end 23:59:59') ORDER BY vc.fecha DESC");
} else {
	$consulta_ventas = $conexion->query("SELECT * FROM ventascliente vc INNER JOIN datos_compradornr dc ON vc.ID=dc.ID WHERE vc.estado = 1 and vc.id_usuario = $id ORDER BY vc.fecha DESC");
}

$vendedor = $conexion->query("SELECT * FROM users WHERE id = $id")->fetch_array();


if ($consulta_ventas->num_rows > 0){

	$cont = $consulta_ventas->num_rows;

	$tabla .= '<form action="#" method="get">

	<table id="seleccion" align="center" width="80%" border="0" cellpadding="0" cellspacing="0">

		<input type="hidden" name="vendedor" value="'.$id.'">

		<tr class="bg-primary">

			<td colspan="2">VENDEDOR:</td>

			<td colspan="3">'.$vendedor['nombre'].', '.$vendedor['apellido'].'</td>

		</tr>

		<tr class="bg-primary">

			<td>#</td>

			<td>ID VENTA</td>

			<td>FECHA</td>

			<td>CLIENTE</td>

			<td>TOTAL</td>

		</tr>';

	while($datos = $consulta_ventas->fetch_assoc()){	

		$total = $datos['total'] + $total;	

		$tabla.=

		'<tr class="seleccion">

			<td>'.$cont.'</td>

			<td>#'.$datos['ID'].'</td>

			<td>'.date("d/m/Y", strtotime($datos['fecha'])).'</td>

			<td>'.$datos['nombre'].', '.$datos['apellido'].' ('.$datos['telefono'].')</td>

			<td>$'.money_format('%.2n', $datos['total']).'</td>

		 </tr>

		';

		$cont--;

	}


	$tabla .= '<tr class="bg-primary">

					<td>TOTAL</td>

					<td colspan="4">$'.money_format('%.2n', $total).'</td>

				</tr>';

	$tabla.='</table></form>';

} else {

	$tabla="No se encontraron coincidencias con sus criterios de búsqueda. <a href='vendedores.php?vendedor=".$id."'>VOLVER ATRAS.</a>";

}

header("Content-Type: application/xls");    
header("Content-Disposition: attachment; filename=documento_exportado_" . date('Y:m:d:m:s').".xls");
header("Pragma: no-cache"); 
header("Expires: 0");

echo $tabla;
?>