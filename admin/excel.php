<?php 
session_start();
require("../config.php");
if($_GET['vendedor']){
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
	$tabla .= '
	<table>
		<tr>
			<td colspan="2">VENDEDOR:</td>
			<td colspan="3">'.$vendedor['nombre'].', '.$vendedor['apellido'].'</td>
		</tr>
		<tr>
			<td>#</td>
			<td>ID VENTA</td>
			<td>FECHA</td>
			<td>CLIENTE</td>
			<td>TOTAL</td>
		</tr>';
	while($datos = $consulta_ventas->fetch_assoc()){	
		$total = $datos['total'] + $total;	
		$tabla.=
		'<tr>
			<td>'.$cont.'</td>
			<td>#'.$datos['ID'].'</td>
			<td>'.date("d/m/Y", strtotime($datos['fecha'])).'</td>
			<td>'.$datos['nombre'].', '.$datos['apellido'].' ('.$datos['telefono'].')</td>
			<td>$'.money_format('%.2n', $datos['total']).'</td>
		 </tr>
		';
		$cont--;
	}
	$tabla .= '<tr>
					<td>TOTAL</td>
					<td colspan="4">$'.money_format('%.2n', $total).'</td>
				</tr>';
	$tabla.='</table>';
} else {
	$tabla="No se encontraron coincidencias con sus criterios de búsqueda. <a href='vendedores.php?vendedor=".$id."'>VOLVER ATRAS.</a>";
}
header("Content-Type: application/xls");    
header("Content-Disposition: attachment; filename=VENDEDOR___".$vendedor['nombre'].".".$vendedor['apellido']."____" . date('Y:m:d:H:i').".xls");
header("Pragma: no-cache"); 
header("Expires: 0");
echo $tabla;
}





if(isset($_GET['ticket'])){
	$sql = "SELECT vc.ID,vc.id_usuario,vc.total,vc.fecha_entrega,COALESCE(dc.ID,u.id) as id,COALESCE(dc.nombre, u.nombre) as nombre,COALESCE(dc.apellido,u.apellido)as apellido FROM ventascliente vc LEFT JOIN datos_compradornr dc ON vc.ID=dc.ID LEFT JOIN users u ON vc.id_usuario=u.id WHERE vc.estado = 1";

	function existeVendedor($id_venta,$id_usuario){
		$existe = $GLOBALS['conexion']->query("SELECT * FROM datos_compradornr WHERE ID = $id_venta")->num_rows;
		if($existe){
			$datos_usuario = $GLOBALS['conexion']->query("SELECT * FROM datos_compradornr WHERE ID = $id_venta")->fetch_array();
			$datos_vendedor = $GLOBALS['conexion']->query("SELECT nombre,apellido FROM users WHERE id = $id_usuario")->fetch_array();
			$nombre = ($id_usuario) ? $datos_usuario['apellido'].', '.$datos_usuario['nombre'].' ('.$datos_usuario['telefono'].') ('.$datos_vendedor['nombre'].' '.$datos_vendedor['apellido'].')' : $datos_usuario['apellido'].', '.$datos_usuario['nombre'].' ('.$datos_usuario['telefono'].')' ;
		} else {
			$datos_usuario = $GLOBALS['conexion']->query("SELECT * FROM users WHERE id = $id_usuario")->fetch_array();
			$nombre = $datos_usuario['apellido'].', '.$datos_usuario['nombre'];
		}
		return $nombre;
	}

	function consulta_ventas($palabra,$desde,$hasta){
		if (!empty($palabra) || isset($desde)) {
			if(isset($desde)){
				if(isset($hasta)){
					$datos = $GLOBALS['conexion']->query($GLOBALS['sql']." AND (dc.nombre LIKE '%".$palabra."%' OR u.nombre LIKE '%".$palabra."%' OR dc.apellido LIKE '%".$palabra."%' OR u.apellido LIKE '%".$palabra."%') AND (vc.fecha_entrega >= '$desde' AND vc.fecha_entrega <= '$hasta') ORDER BY vc.fecha_entrega DESC");
				} else {
					$hasta = date("Y-m-d");
					$datos = $GLOBALS['conexion']->query($GLOBALS['sql']." AND (dc.nombre LIKE '%".$palabra."%' OR u.nombre LIKE '%".$palabra."%' OR dc.apellido LIKE '%".$palabra."%' OR u.apellido LIKE '%".$palabra."%') AND (vc.fecha_entrega >= '$desde' AND vc.fecha_entrega <= '$hasta') ORDER BY vc.fecha_entrega DESC");
				}			
			} else {
				$datos = $GLOBALS['conexion']->query($GLOBALS['sql']." AND (dc.nombre LIKE '%".$palabra."%' OR u.nombre LIKE '%".$palabra."%' OR dc.apellido LIKE '%".$palabra."%' OR u.apellido LIKE '%".$palabra."%') ORDER BY vc.fecha_entrega DESC");
			}		
		} else {
			$datos = $GLOBALS['conexion']->query($GLOBALS['sql']." ORDER BY vc.fecha_entrega DESC");
		}
		return $datos;
	}
	$palabra = $_GET['ticket'];
	$datos = consulta_ventas($palabra,$_GET['start'],$_GET['end']);
	$tabla = '<table>
				<tr>
					<td>ID VENTA</td>
					<td>FECHA</td>
					<td>CLIENTE</td>
					<td>TOTAL</td>						
				</tr>';
	foreach ($datos as $n => $dato) {
		$fecha_entrega = $dato['fecha_entrega'];
		$total = $dato['total'];
		$nombre = existeVendedor($dato['ID'],$dato['id_usuario']);
		$n++;
		$tabla.='<tr>
					<td>'.$n.'</td>
					<td>'.$fecha_entrega.'</td>
					<td>'.$nombre.'</td>
					<td>'.$total.'</td>
				 </tr>';
	}
	$tabla.='</table>';
	header("Content-Type: application/xls");    
	header("Content-Disposition: attachment; filename=TICKETS___" . date('Y:m:d:H:i').".xls");
	header("Pragma: no-cache"); 
	header("Expires: 0");
	echo $tabla;
}
if(isset($_GET['agenda'])){
	function function_consulta($rol,$id,$cliente){
        $sql = "SELECT * FROM agenda";
        if(isset($cliente)){
            if($rol == 1){
                $consulta_agenda = $GLOBALS['conexion']->query($sql." WHERE nombre_agenda LIKE '%$cliente%' or apellido_agenda LIKE '%$cliente%' or direccion_agenda LIKE '%$cliente%' ORDER BY id_agenda DESC");
			} else {
                $consulta_agenda = $GLOBALS['conexion']->query($sql." WHERE (id_vendedor = $id) AND (nombre_agenda LIKE '%$cliente%' or apellido_agenda LIKE '%$cliente%' or direccion_agenda LIKE '%$cliente%')");
            }
        } else {
            if($rol == 1){
                $consulta_agenda = $GLOBALS['conexion']->query($sql." ORDER BY id_agenda DESC");
            } else {
                $consulta_agenda = $GLOBALS['conexion']->query($sql." WHERE id_vendedor = $id ORDER BY id_agenda DESC");
            }
        }
		$admin = ($rol == 1)? true: false;
        return array($consulta_agenda,$admin);
    }    
    list($consulta_agenda,$admin) = function_consulta($_SESSION['rol'],$_SESSION['id'],$_GET['cliente']);

	function vista_tabla($admin){
		return ($admin) ? "<td>VENDEDOR</td>" : "";
	}
	$tabla = '<table>
				<tr>
					<td>ID</td>
					<td>NOMBRE</td>
					<td>APELLIDO</td>
					<td>DIRECCION</td>
					<td>FECHA</td>
					<td>CELULAR</td>
					'.vista_tabla($admin).'					
				</tr>';
	foreach ($consulta_agenda as $dato) {
		if($admin){
			if($dato['id_vendedor']){
				$id_vendedor = $dato['id_vendedor'];
				$d_vendedor = $conexion->query("SELECT nombre FROM users WHERE id = $id_vendedor")->fetch_array();
				$d_vendedor = "<td>".$d_vendedor['nombre']."</td>";
			} else {
				$d_vendedor = "<td> ADMIN </td>";
			}
		} else {
			$d_vendedor = "";
		}
		$tabla .= "<tr>
					<td>#".$dato['id_agenda']."</td>
					<td>".$dato['nombre_agenda']."</td>
					<td>".$dato['apellido_agenda']."</td>
					<td>".$dato['direccion_agenda']."</td>
					<td>".$dato['fecha_agenda']."</td>
					<td>".$dato['telefono_agenda']."</td>
					".$d_vendedor."
				   </tr> ";	}
	$tabla .= "</table>";
	header("Content-Type: application/xls");    
	$add = ($_GET['cliente'])?"BUSQUEDA: ".$_GET['cliente'] : "TODOS";
	header("Content-Disposition: attachment; filename=AGENDA__".$add."__" . date('Y:m:d:H:i').".xls");
	header("Pragma: no-cache"); 
	header("Expires: 0");
	echo $tabla;
}

if(isset($_GET['precios'])){
	if($_SESSION['rol'] == 1){
		$datos_precios = $GLOBALS['conexion']->query("SELECT p_datos.id_producto,p_datos.categoria,p_datos.stock,productos.id,productos.nombre,p_infoweb.oferta,p_precios.precio_o,p_precios.precio_v 
											FROM p_datos 
											INNER JOIN productos ON p_datos.id_producto=productos.id 
											INNER JOIN p_infoweb ON p_infoweb.id_producto=p_datos.id_producto 
											INNER JOIN p_precios ON p_precios.id_producto=p_datos.id_producto 
											WHERE p_datos.stock != 0 AND p_precios.precio_v > 0 AND p_infoweb.fecha_ultimo_precio != '0000-00-00'
											ORDER BY productos.nombre ASC");
		$tabla = '<table>
		<tr>
			<td>ID</td>
			<td>PRODUCTO</td>
			<td>PRECIO</td>			
		</tr>';
		foreach($datos_precios as $dato){
			$tabla .= "<tr>
						<td>#".$dato['id']."</td>
						<td>".$dato['nombre']."</td>
						<td>$".$dato['precio_v']."</td>
				   	   </tr> ";
		}
		$tabla .= "</tabla>";
		header("Content-Type: application/xls");    
		header("Content-Disposition: attachment; filename=PRECIOS___" . date('Y:m:d:H:i').".xls");
		header("Pragma: no-cache"); 
		header("Expires: 0");
		echo $tabla;
	}
}
?>