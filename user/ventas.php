<?php 
include_once("../php/includes_user.php");
include("../php/functions_ventas.php");
?>
<body>
<div id="contenido">
<?php
fechas_search($_POST['fecha_all'], $_POST['fecha_inicio'], $_POST['fecha_fin']);
$resultados_vendedor = ($_POST) ? (($_POST['fecha_all']) ? vendedor($_SESSION['id'],false,false) : vendedor($_SESSION['id'],$_SESSION['fecha_inicio'],$_SESSION['fecha_fin'])) : vendedor($_SESSION['id'],false,false);
$pagina = (isset($_GET['pagina'])) ? $_GET['pagina'] : 1;

if(isset($_SESSION['loggedin']) && $_SESSION['rol'] == 2){
	if(!isset($_GET['venta'])){
	echo filtro_fechas($_SESSION['fecha_inicio'],$_SESSION['fecha_fin'],false);
	if($resultados_vendedor->rowCount()){	
		$desde = $_SESSION['fecha_inicio'];
		$hasta = $_SESSION['fecha_fin'];
		$id = $_SESSION['id'];

		$res = resultados_paginacion($ventas_resultados_por_pagina, $pagina, "ventascliente", (($desde) ? "id_usuario = '$id' AND (fecha >= '$desde 00:00:00' AND fecha <= '$hasta 23:59:59')" : "id_usuario = '$id'"));
		$num_paginas = ceil($res['total_elementos']->rowCount() / $ventas_resultados_por_pagina);

		$tabla .= "<div class='view_vendedor__ventas'>
					<div class='titulos b_superior'>
						<p class='codigo'>#</p>
						<p>fecha</p>
						<p class='monto'>monto</p>
						<p>cliente</p>
						<p>pedido</p>
					</div>";
		
		foreach($res['elementos'] as $dato){
			$comprador = comprador($dato['ID'])->fetch(PDO::FETCH_ASSOC);
			$id_venta = $dato['ID'];
			$fecha = formato_fecha($dato['fecha']);
			$total = formato_precio($dato['total']);
			$nombre = $comprador['nombre'];
			$apellido = $comprador['apellido'];
			$telefono = $comprador['telefono'];
		
			$tabla .= "<div class='contenido b_laterales_primary'>
							<p class='codigo'>#$id_venta</p>
							<p>$fecha</p>
							<p class='monto'>$total</p>
							<p>$nombre <text class='apellido'>$apellido</text><a href='https://wa.me/".$telefono."?text=Hola $nombre' target='_blank'><i class='fa fa-whatsapp fa-lg fa-fw'></i></a></p>
							<p><a href='?venta=$id_venta' title='VER PEDIDO'><i class='fa fa-list-alt fa-lg fa-fw'></i></a></p>
						</div>";
		}
		$tabla .= "<div class='footer b_inferior'></div></div>";
		echo $tabla;		
	} else {
		echo "<p><b class='rojo'>SIN VENTAS</b></p>";
	}
	paginacion(false,$pagina,$num_paginas,false);
	} else {
		detalles_venta($_GET['venta'],$_SESSION['id']);
		echo '<p><input class="btn_atras btn_morado" onclick="history.back()" type="button" value="VOLVER ATRAS"></p>';	
	} 
} else {header("Location:../index.php");} ?>
</div>
</body>
</html>