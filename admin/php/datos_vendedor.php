<?php 

$id = $_GET['vendedor'];

$fecha = date('Y-m-d');

$fecha_end = date('Y-m-d');

$total = 0;

//calculamos si existe diferencia entre fechas

if (isset($_GET['start'])) {

	$fecha = $_GET['start'];

	$fecha_end = $_GET['end'];

	// $existe = dif_fechas($fecha,$fecha_start);

	$consulta_ventas = $conexion->query("SELECT * FROM ventascliente vc INNER JOIN datos_compradornr dc ON vc.ID=dc.ID WHERE vc.estado = 1 and vc.id_usuario = $id and (vc.fecha >= '$fecha 00:00:00' AND vc.fecha <= '$fecha_end 23:59:59') ORDER BY vc.fecha DESC");

	$imprimir = '<a class="a_print" target="_blank" href="../ticket/vendedor.php?vendedor='.$id.'&start='.$fecha.'&end='.$fecha_end.'"><i class="fas fa-print"></i></a>';
	$excel = '<a class="a_print" target="_blank" title="Exportar a Excel" href="./excel.php?vendedor='.$id.'&start='.$fecha.'&end='.$fecha_end.'"><i class="fas fa-file-excel"></i></a>';

} else {

	$consulta_ventas = $conexion->query("SELECT * FROM ventascliente vc INNER JOIN datos_compradornr dc ON vc.ID=dc.ID WHERE vc.estado = 1 and vc.id_usuario = $id ORDER BY vc.fecha DESC");

	$imprimir = '<a class="a_print" target="_blank" href="../ticket/vendedor.php?vendedor='.$id.'"><i class="fas fa-print"></i></a>';
	$excel = '<a class="a_print" target="_blank" title="Exportar a Excel" href="./excel.php?vendedor='.$id.'"><i class="fas fa-file-excel"></i></a>';

}



// if (isset($existe)) {

// 	$consulta_ventas = $conexion->query("SELECT * FROM ventascliente vc INNER JOIN datos_compradornr dc ON vc.ID=dc.ID WHERE vc.estado = 1 and vc.id_usuario = $id and (vc.fecha >= '$fecha_start 00:00:00' AND vc.fecha <= '$fecha_end 23:59:59') ORDER BY vc.fecha ASC");

// 	$imprimir = '<a target="_blank" href="../ticket/vendedor.php?vendedor='.$id.'&start='.$fecha_start.'&end='.$fecha_end.'">IMPRIMIR PLANILLA</a>';

// } else {

// 	$consulta_ventas = $conexion->query("SELECT * FROM ventascliente vc INNER JOIN datos_compradornr dc ON vc.ID=dc.ID WHERE vc.estado = 1 and vc.id_usuario = $id ORDER BY vc.fecha DESC");

// 	$imprimir = '<a target="_blank" href="../ticket/vendedor.php?vendedor='.$id.'">IMPRIMIR PLANILLA</a>';

// }



$vendedor = $conexion->query("SELECT * FROM users WHERE id = $id")->fetch_array();

if ($consulta_ventas->num_rows > 0){

	$cont = $consulta_ventas->num_rows;

	$tabla .= '<form action="#" method="get">

	<table id="seleccion" align="center" width="80%" border="0" cellpadding="0" cellspacing="0">

	<tr class="bg-primary">

			<td>FILTRAR POR FECHA:</td>

			<td>DESDE: <input type="date" id="start" name="start" value="'.$fecha.'" min="2021-11-01" max="2024-12-31"></td>

			<td>HASTA: <input type="date" id="end" name="end" value="'.$fecha_end.'" min="2021-11-01" max="2024-12-31"></td>

			<td><a class="todas_las_ventas" href="vendedores.php?vendedor='.$id.'">Todas</a><input type="submit" value="Filtrar">'.$imprimir.$excel.'</td>

		</tr>

	</table>

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

			<td><a href="./ver_pedidos.php?verPedido='.$datos['ID'].'">'.date("d/m/Y", strtotime($datos['fecha'])).'</a></td>

			<td>'.$datos['nombre'].', '.$datos['apellido'].' ('.$datos['telefono'].')</td>

			<td>$'.money_format('%.2n', $datos['total']).'</td>

		 </tr>

		';

		$cont--;

	}


	// PRUEBA DE XLS
	// $filename = "libros.xls";
 //    header("Content-Type: application/vnd.ms-excel");
 //    header("Content-Disposition: attachment; filename=".$filename);
 //    echo $tabla;






	$tabla .= '<tr class="bg-primary">

					<td>TOTAL</td>

					<td colspan="4">$'.money_format('%.2n', $total).'</td>

				</tr>';

	$tabla.='</table></form>';

} else {

	$tabla="No se encontraron coincidencias con sus criterios de búsqueda. <a href='vendedores.php?vendedor=".$id."'>VOLVER ATRAS.</a>";

}













// function dif_fechas($fecha,$end){

// 	$datetime1 = date_create($fecha);

// 	$datetime2 = date_create($end);

// 	$interval = date_diff($datetime1, $datetime2);

// 	return $interval->days;

// }

?>