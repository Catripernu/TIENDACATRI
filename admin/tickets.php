<?php
session_start();
include('../config.php');
include('datosRelevantes.php');
$total = 0;
$productos_por_pagina = 200;
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
<style type="text/css">
a{
  color:#322783;
  text-decoration: none;
}
a:hover{
	color: #8C51FF;
}
.todas_las_ventas {
	margin-right: 10px;
	padding: 5px 10px;
	border: 1px solid #E5E5E5;
	background: #E5E5E5;
	cursor: default;
	color: black;
	font-size: 14px;
	font-style: normal;
	font-weight: normal;
}
.todas_las_ventas:hover {
	color: black;
}
table {
  margin-top: 20px;
}
.bg-primary td{
  background: #322783;
  padding: 10px;
  font-weight: bold;
  color: white;
  font-style: italic;
}
td{
  text-align: center;
  padding: 5px;
  background: #f2f2f2;
}
input[type="text"], #formularioModif select{
  width: 50%;
  padding: 10px;
  font-size: 16px;
  text-align: center;
}
input[type="submit"]{
	width: 100px;
  padding: 5px 10px;
  cursor: pointer;
}
.seleccion:hover td{
  background: #cccccc;
}
#formularioModif input[type="text"], #formularioModif select {
  margin: 5px;
  width: 50%;
}
#formularioModif p {
  text-transform: uppercase;
  margin: 10px 0 0 0;
}
#operaciones {
  margin: 0;
}
#operaciones input[type="text"]{
  width: 40px;
  height: 0px;
}
/*PAGINACION*/
.paginacion {
	margin: 20px auto;
  display: inline-block;
  max-width: 80%;
}
.paginacion .active {
  background:green;
  color:white;
}
.paginacion a {
  text-decoration: none;
  padding:5px 10px;
  background:#4d4d4d;
  float: left;
  color: white;
}
.paginacion a:hover {
  background: #322783;
  transition: all 1s ease;
}
.paginacion .left {
  border-radius: 10px 0 0 10px;
}
.paginacion .right {
  border-radius: 0 10px 10px 0;
}
</style>
</head>
<body>
<div id="contenido">
	<div class="buscador_vendedor">
		<form method="get">
			<input type="text" name="palabra" id="palabra" value="<?php echo isset($_GET['palabra'])? $_GET['palabra'] : ""; ?>" placeholder="Ingresar codigo, nombre, apellido o celular del cliente...">
		</form>
	</div>
	<?php
  $busqueda = "";
  $palabra = (isset($_GET['palabra'])) ? $_GET['palabra'] : null;
  $fecha_start = (isset($_GET['start'])) ? $_GET['start'] : null;
  $fecha_end = (isset($_GET['end'])) ? $_GET['end'] : date("Y-m-d");
  $sql = "SELECT vc.ID,vc.id_usuario,vc.total,vc.fecha_entrega,COALESCE(dc.ID,u.id) as id,COALESCE(dc.nombre, u.nombre) as nombre,COALESCE(dc.apellido,u.apellido)as apellido FROM ventascliente vc LEFT JOIN datos_compradornr dc ON vc.ID=dc.ID LEFT JOIN users u ON vc.id_usuario=u.id WHERE vc.estado = 1 AND (dc.nombre LIKE '%".$palabra."%' OR u.nombre LIKE '%".$palabra."%' OR dc.apellido LIKE '%".$palabra."%' OR u.apellido LIKE '%".$palabra."%')";
  function consulta_ventas($palabra,$desde,$hasta,$start,$productos_por_pagina){
    if(isset($palabra)){
    	if (isset($desde)) {  
    		if (!empty($hasta)) {
    			$consulta_ventas = $GLOBALS['conexion']->query($GLOBALS['sql']." AND (vc.fecha_entrega >= '$desde' AND vc.fecha_entrega <= '$hasta') ORDER BY vc.fecha_entrega DESC LIMIT $start,$productos_por_pagina");
    			$print = '<a class="a_print" target="_blank" href="../ticket/vendedor.php?ticket='.$palabra.'&start='.$desde.'&end='.$hasta.'"><i class="fas fa-print"></i></a>';
    			$excel = '<a class="a_print" target="_blank" title="Exportar a Excel" href="./excel.php?ticket='.$palabra.'&start='.$desde.'&end='.$hasta.'"><i class="fas fa-file-excel"></i></a>';
    			$busqueda = $GLOBALS['conexion']->query($GLOBALS['sql']." AND (vc.fecha_entrega >= '$desde' AND vc.fecha_entrega <= '$hasta')");
    		} else {
    			$fechaEnd_default = date("Y-m-d");
    			$consulta_ventas = $GLOBALS['conexion']->query($GLOBALS['sql']." AND (vc.fecha_entrega >= '$desde' AND vc.fecha_entrega <= '$fecha_end') ORDER BY vc.fecha_entrega DESC LIMIT $start,$productos_por_pagina");
    			$print = '<a class="a_print" target="_blank" href="../ticket/vendedor.php?ticket='.$palabra.'&start='.$desde.'&end='.$fecha_end.'"><i class="fas fa-print"></i></a>';
    			$excel = '<a class="a_print" target="_blank" title="Exportar a Excel" href="./excel.php?ticket='.$palabra.'&start='.$desde.'&end='.$fecha_end.'"><i class="fas fa-file-excel"></i></a>';
    			$busqueda = $GLOBALS['conexion']->query($GLOBALS['sql']." AND (vc.fecha_entrega >= '$desde' AND vc.fecha_entrega <= '$fecha_end')");
    		}   
      } else {
        $consulta_ventas = $GLOBALS['conexion']->query($GLOBALS['sql']." ORDER BY vc.fecha_entrega DESC LIMIT $start,$productos_por_pagina");
        $print = '<a class="a_print" target="_blank" href="../ticket/vendedor.php?ticket='.$palabra.'"><i class="fas fa-print"></i></a>';
        $excel = '<a class="a_print" target="_blank" title="Exportar a Excel" href="./excel.php?ticket='.$palabra.'"><i class="fas fa-file-excel"></i></a>'; 
        $busqueda = $GLOBALS['conexion']->query($GLOBALS['sql']);
      }
    } else {
    	$sql_all = "SELECT * FROM ventascliente WHERE estado = 1 ORDER BY ID DESC";
      $consulta_ventas = $GLOBALS['conexion']->query($sql_all." LIMIT $start,$productos_por_pagina");
      $print = '<a class="a_print" target="_blank" href="../ticket/vendedor.php?ticket"><i class="fas fa-print"></i></a>';
      $excel = '<a class="a_print" target="_blank" title="Exportar a Excel" href="./excel.php?ticket"><i class="fas fa-file-excel"></i></a>';
      $busqueda = $GLOBALS['conexion']->query($sql_all);
    }
    return [$consulta_ventas,$print,$busqueda,$fechaEnd_default,$excel];
  }
  $busqueda = consulta_ventas($palabra,$fecha_start,$fecha_end,0,$productos_por_pagina);
  $todos_tickets_bd = $busqueda[2]->num_rows;
  $page = (isset($_GET['page'])) ? $_GET['page'] : 1;
  $total_pages = ceil($todos_tickets_bd / $productos_por_pagina);
  $start = ($page > $total_pages || $page < 0) ? 0 : ($page - 1) * $productos_por_pagina;
  list($consulta_ventas,$print) = consulta_ventas($palabra,$fecha_start,$fecha_end,$start,$productos_por_pagina);
  $excel = consulta_ventas($palabra,$fecha_start,$fecha_end,$start,$productos_por_pagina);
	$total_registros_encontrados = $busqueda[2]->num_rows;
  if($consulta_ventas->num_rows){
  	$tabla .= '
	<form action="#" method="get">
	<table id="seleccion" align="center" width="80%" border="0" cellpadding="0" cellspacing="0">
	<tr class="bg-primary">
			<td>FILTRAR POR FECHA:</td>
			<td>DESDE: <input type="date" id="start" name="start" value="'.$fecha_start.'" min="2021-11-01" max="2024-12-31"></td>
			<td>HASTA: <input type="date" id="end" name="end" value="'.$fecha_end.'" min="2021-11-01" max="2024-12-31"></td>
			<td><a class="todas_las_ventas" href="tickets.php">Todas</a><input type="submit" value="Filtrar">'.$print.$excel[4].'</td>
		</tr>
	</table>
	<input type="hidden" name="palabra" value="'.$palabra.'">
	</form>
	<table align="center" width="80%" border="0" cellpadding="0" cellspacing="0">
					<tr class="bg-primary">
						<td>#</td>
						<td>ID VENTA</td>
						<td>FECHA</td>
						<td>CLIENTE</td>
						<td>TOTAL</td>
					</tr>';
  foreach ($consulta_ventas as $n => $datos) {
  	$n++; 
    $id_venta = $datos['ID'];
		$id_usuario = $datos['id_usuario'];
		
		$existencia_vendedor = $conexion->query("SELECT * FROM datos_compradornr WHERE ID = $id_venta")->num_rows;

		if ($existencia_vendedor) {
			$datos_usuario = $conexion->query("SELECT * FROM datos_compradornr WHERE ID = $id_venta")->fetch_array();
			$datos_vendedor = $conexion->query("SELECT nombre,apellido FROM users WHERE id = $id_usuario")->fetch_array();
			if ($id_usuario) {
				$nombre_apellido = '<td>'.$datos_usuario['apellido'].', '.$datos_usuario['nombre'].' ('.$datos_usuario['telefono'].') ('.$datos_vendedor['nombre'].' '.$datos_vendedor['apellido'].')</td>';
			} else {
				$nombre_apellido = '<td><i class="fas fa-shopping-cart" title="Consumidor Final"></i> '.$datos_usuario['apellido'].', '.$datos_usuario['nombre'].' ('.$datos_usuario['telefono'].')</td>';
			}
		} else {
			$datos_usuario = $conexion->query("SELECT * FROM users WHERE id = $id_usuario")->fetch_array();
			$nombre_apellido = '<td><i class="fas fa-user" title="Usuario"></i> '.$datos_usuario['apellido'].', '.$datos_usuario['nombre'].'</td>';
		}
			$total = $total + $datos['total'];
			$tabla.=
					'<tr class="seleccion">
						<td>'.$n.'</td>
						<td>#'.$id_venta.'</td>
						<td><a href="./ver_pedidos.php?verPedido='.$id_venta.'&t">'.date("d/m/Y", strtotime($datos['fecha_entrega'])).'</a></td>
						'.$nombre_apellido.'
						<td>$'.money_format('%.2n', $datos['total']).'</td>
					 </tr>
					';
  }
	$tabla.='</table>';
} else {
	$tabla="<br>No se encontraron coincidencias con sus criterios de busqueda. <a href='./tickets.php'>VOLVER A TICKETS</a>";
}
	echo $tabla;
  function btn_paginacion($tipo,$page,$palabra,$desde,$hasta){
    $page = ($tipo) ? "?page=".($page+1) : "?page=".($page-1);
    $av_palabra = ($palabra || empty($palabra)) ? $page."&palabra=$palabra" : $page;
    $mostrar = ($desde) ? $av_palabra."&start=$desde&end=$hasta" : $av_palabra;
    return $mostrar;
  }
  $acpag = (isset($palabra)) ? "?palabra=$palabra&page=" : "?page=";
  $acpag = (isset($fecha_end)) ? "?palabra=$palabra&start=$fecha_start&end=$fecha_end&page=" : $acpag;
?>
	<div class="paginacion"><?php if($total_pages > 1){ if($page != 1) { ?>  
		<a class="left" href="<?=btn_paginacion(0,$page,$palabra,$fecha_start,$fecha_end)?>"><span>&laquo;</span></a><?php } for($i=1;$i<=$total_pages;$i++){ echo ($page == $i)? '
		<a class="active"><b>'.$page.'</b></a>': '
		<a href="'.$acpag.$i.'">'.$i.'</a>';
	    } if($page != $total_pages){ ?>
	  <a class="right" href="<?=btn_paginacion(1,$page,$palabra,$fecha_start,$fecha_end)?>"><span>&raquo;</span></a><?php } } ?>
	</div>
</div>
</body>
</html>
<?php
} else {
	header("Location: ../index.php");
}
?>