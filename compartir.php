<?php 
include('head.php');
if ($_GET) {
	$id = $_GET['producto'];
	if (is_numeric($id)) {
		$consulta = $conexion->prepare("SELECT p.id, p.nombre, pp.precio_v, pp.precio_o, pi.oferta, pi.dosxuno, pd.stock, pd.f_vencimiento FROM productos p
		INNER JOIN p_precios pp ON p.id=pp.id_producto
		INNER JOIN p_infoweb pi ON p.id=pi.id_producto
		INNER JOIN p_datos pd ON p.id=pd.id_producto
		WHERE pi.fecha_ultimo_precio != '00-00-0000'
		AND pp.precio_v > 0
		AND pd.stock > 0
		AND p.id = :id");
		$consulta->execute([":id" => $id]);
		if ($consulta->rowCount()) {
			$dato = $consulta->fetch(PDO::FETCH_ASSOC);
			$nombre = $dato['nombre'];
			$precio = $dato['precio_v'];
			$mensaje_exito = '
								<div class="compartir">
									<img width="170" src="./images/productos/ompick_'.$id.'.webp" alt="" />
									<p>'.$nombre.'</p>
									<p>$'.$precio.'</p>
									<br>
									<div class="btn">
										<div class="btn_cantidad">
											<button type="button" id="menos" onclick="contadormenos('.$id.')">-</button>
											<input id="cantidad'.$id.'" type="text" max="'.$GLOBALS['maximo_stock'].'" value="1">
											<button type="button" id="mas" onclick="contadormas('.$id.')">+</button>
											<input type="hidden" id="maximo_stock" value="'.$GLOBALS['maximo_stock'].'">
										</div>
										<a title="Agregar al Carrito: '.$nombre.'" class="addCart" data-id="'.$id.'" href="#""><img class="btn_addcart" width="45px" heigth="45px" src="./images/btn_agregarCarrito.png"></a>
									</div>
								</div>
			';
		} else {
			$mensaje_error = "NO EXISTE ESTE PRODUCTO.";
		}		
	} else {
		$mensaje_error = "PRODUCTO INVALIDO.";
	}
} else {
	$mensaje_error = "NO RECIBIO PRODUCTO.";
}

?>
<!DOCTYPE html>
<html>
<body>
  <div id="contenido">
  	<?php 
		if(isset($mensaje_exito)){
			echo $mensaje_exito;
		} else {
			echo $mensaje_error;
		}
  	 ?>
  </div>
</body>
</html>