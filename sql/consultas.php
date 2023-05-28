<?php 
// DATOS DE VENTASCLIENTE
function ventascliente($id){
  $consulta = $GLOBALS['conexion']->prepare("SELECT * FROM ventascliente WHERE ID = :id");
  $consulta->execute([":id" => $id]);
  return $consulta;
}
// FORMATO FECHA DD/MM/YYYY
function formato_fecha($fecha){
  return date("d/m/Y", strtotime($fecha));
}
// ESTADO (PENDIENTE,COMPLETO,CANCELADO) TEXTO
function ventascliente_estado_texto($estado){
  return ($estado) ? (($estado == 1) ? "COMPLETO" : "CANCELADO") : "PENDIENTE";
}
// CONSULTA PRODUCTOS_VENTAS
function productos_venta($id_venta){
  $consulta = $GLOBALS['conexion']->query("SELECT * FROM productos_venta WHERE id_venta = $id_venta");
  return $consulta;
}

// ELIMINAR ACENTOS DE CADENA DE TEXTO
function eliminar_acentos($cadena){
  //Reemplazamos la A y a
  $cadena = str_replace(
  array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
  array('A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A'),
  $cadena
  );
  //Reemplazamos la E y e
  $cadena = str_replace(
  array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
  array('E', 'E', 'E', 'E', 'E', 'E', 'E', 'E'),
  $cadena );
  //Reemplazamos la I y i
  $cadena = str_replace(
  array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
  array('I', 'I', 'I', 'I', 'I', 'I', 'I', 'I'),
  $cadena );
  //Reemplazamos la O y o
  $cadena = str_replace(
  array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
  array('O', 'O', 'O', 'O', 'O', 'O', 'O', 'O'),
  $cadena );
  //Reemplazamos la U y u
  $cadena = str_replace(
  array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
  array('U', 'U', 'U', 'U', 'U', 'U', 'U', 'U'),
  $cadena );
  //Reemplazamos la N, n, C y c
  $cadena = str_replace(
  array('Ç', 'ç'),
  array('C', 'C'),
  $cadena
  );
  return $cadena;
}
function consulta_seccionCategoria(){
  $consulta = $GLOBALS['conexion']->prepare("SELECT * FROM seccionCategoria WHERE visual = :active");
  $consulta->execute([':active' => 1]);
  return $consulta;
}
function seccionCategoria_colores($id){    
    $consultaColor = $GLOBALS['conexion']->prepare("SELECT color_fuente FROM seccionCategoria_colores WHERE id = :id");
    $consultaColor->execute([":id" => $id]);
    return $consultaColor->fetch(PDO::FETCH_ASSOC);
}
function datosSeccion($id){
    $datosSeccion = $GLOBALS['conexion']->prepare("SELECT nombre FROM seccionCategoria WHERE id = :id");
    $datosSeccion->execute([":id" => $id]);
    return $datosSeccion->fetch(PDO::FETCH_ASSOC);
}
function formato_precio($precio){
    return money_format('%(#10n', $precio);
}
function metodo_pago($pago){
  return ($pago == 1) ? "EFECTIVO" : "OTROS";
}
function obtener_resultados_productos($tabla, $resultados_por_pagina, $pagina_actual, $categoria, $admin) {  
  $where = ($admin == 1) ? "p_datos.categoria = :categoria AND p_precios.precio_v > 0" : "p_datos.categoria = :categoria AND p_datos.stock != 0 AND p_infoweb.dosxuno = 0 AND p_precios.precio_v > 0 AND p_infoweb.fecha_ultimo_precio != '0000-00-00'";
  // Determinar el número de resultados totales en la base de datos
  $total_resultados = $GLOBALS['conexion']->prepare("SELECT COUNT(*) FROM $tabla 
									INNER JOIN p_datos ON $tabla.id = p_datos.id_producto
									INNER JOIN p_infoweb ON $tabla.id = p_infoweb.id_producto
									INNER JOIN p_precios ON $tabla.id = p_precios.id_producto
									WHERE $where");
  $total_resultados->bindParam(':categoria', $categoria);
  $total_resultados->execute();
  // Determinar el número de páginas que se necesitan
  $num_paginas = ceil($total_resultados->fetchColumn() / $resultados_por_pagina);
  $pagina_actual = ($pagina_actual > $num_paginas) ? 1 : $pagina_actual;
  // Calcular el índice del primer resultado en la página actual
  $indice_primer_resultado = ($pagina_actual - 1) * $resultados_por_pagina;
  // Obtener los resultados para la página actual
  $resultados = $GLOBALS['conexion']->prepare('SELECT * FROM '.$tabla.'
								INNER JOIN p_datos ON '.$tabla.'.id = p_datos.id_producto
								INNER JOIN p_infoweb ON '.$tabla.'.id = p_infoweb.id_producto 
								INNER JOIN p_precios ON '.$tabla.'.id = p_precios.id_producto
								WHERE '.$where.' ORDER BY '.$tabla.'.nombre ASC LIMIT :indice_primer_resultado, :resultados_por_pagina');
  $resultados->bindParam(':indice_primer_resultado', $indice_primer_resultado, PDO::PARAM_INT);
  $resultados->bindParam(':resultados_por_pagina', $resultados_por_pagina, PDO::PARAM_INT);
	$resultados->bindParam(':categoria', $categoria);
  $resultados->execute();
  // Devolver los resultados
  return array(
    'resultados' => $resultados,
    'num_paginas' => $num_paginas
  );
}
// PRODUCTOS.PHP
function productos__opciones($rol,$nombre,$id){
  if($rol == 1){
    $opciones = "<a title='Editar: $nombre' href='admin/editar_producto.php?id=$id'><img class='agregar' src='images/btn_editarProducto.png'></a>
                <a class='btnEliminarPedido' title='Borrar: $nombre' data-id='$id' href='#'><img src='images/btn_borrarProducto.png'></a>";
  } else {
    $opciones = "<a title='Agregar al Carrito: $nombre' class='addCart' data-id='$id' href='#'><img class='agregar' src='images/btn_agregarCarrito.png'></a> <a title='Compartir: $nombre' href='whatsapp://send?text=".$GLOBALS['url_site']."compartir.php?producto=$id'><img src='images/whatsapp.png'></a>";
  }
  return $opciones;
}
// PAGINACION
function resultados_paginacion($resultados_por_pagina, $pagina_actual, $tabla, $where) {
  $indice_primer_resultado = ($pagina_actual - 1) * $resultados_por_pagina;
  $resultados = $GLOBALS['conexion']->query("SELECT * FROM $tabla WHERE $where LIMIT $indice_primer_resultado, $resultados_por_pagina");
  return $resultados;
}
function paginacion($section_productos, $pagina_actual, $total_paginas,$palabra){
    // VERIFICAMOS SI ES DE PRODUCTOS.PHP
    if($section_productos){
      $link_prev = "?section=".$section_productos."&inicio&pagina=".($pagina_actual-1);
      $link_next = "?section=".$section_productos."&inicio&pagina=".($pagina_actual+1);
      $link = "?section=$section_productos&inicio&";
    } else {
      if($palabra){
      $link_prev = "?buscar=$palabra&pagina=".($pagina_actual-1);
      $link_next = "?buscar=$palabra&pagina=".($pagina_actual+1);
      $link = "?buscar=$palabra&";
      } else {
        $link_prev = "?pagina=".($pagina_actual-1);
        $link_next = "?pagina=".($pagina_actual+1);
        $link = "?";
      }
    }
    echo "<div class='paginacion'>";
    echo ($pagina_actual > 1) ? "<a class='btn prev' href='$link_prev'><</a>" : "";
    for ($pagina = 1; $pagina <= $total_paginas; $pagina++) {
      echo ($pagina_actual == $pagina) ? '<a class="activo" href="">' . $pagina . '</a>' : '<a href="'.$link.'pagina='.$pagina.'">' . $pagina . '</a>';
    }
    echo ($pagina_actual < $total_paginas) ? "<a class='btn next' href='$link_next'>></a>" : "";
    echo '</div>';
  }

// CARRITO
function agregar_carrito($id,$cant,$arreglo){
	$cantidad = ADD_CANTIDAD($cant);
	$producto = producto($id);
	if($producto->rowCount()){
		$fila = $producto->fetch(PDO::FETCH_ASSOC);
		if(isset($_SESSION["loggedin"]) && $fila['oferta'] == 1 || $fila['dosxuno'] == 1){
			$precio = $fila['precio_o'];
		} else {
			$precio = $fila['precio_v'];
		}	
		if($arreglo){
			$arregloNuevo = array(
				'Id' => $id,
				'Nombre' => $fila['nombre'],
				'Precio' => $precio,
				'Cantidad' => $cantidad
			);
			array_push($arreglo, $arregloNuevo);
			$_SESSION['gastoTotal'] = ($precio * $cantidad) + $_SESSION['gastoTotal'];
		} else {
			$arreglo[] = array(
				'Id' => $_POST['id'],
				'Nombre' => $fila['nombre'],
				'Precio' => $precio,
				'Cantidad' => $cantidad
			);
			$_SESSION['comprobante'] = 1;
			$_SESSION['gastoTotal'] = $precio * $cantidad;	
		}		
		$_SESSION['carrito'] = $arreglo;		
	}
}
// BUSCA INFORMACION DEL PRODUCTO
function producto($id){
	$res = $GLOBALS['conexion']->prepare("SELECT productos.id,productos.nombre,p_precios.precio_v,p_precios.precio_o,p_infoweb.oferta,p_infoweb.dosxuno FROM productos INNER JOIN p_precios ON p_precios.id_producto=productos.id INNER JOIN p_infoweb ON p_infoweb.id_producto=productos.id WHERE productos.id = :id");
	$res->execute([":id" => $id]);
	return $res;
}
function ADD_CANTIDAD($valor){
	return $cantidad = (isset($valor) && $valor <= $GLOBALS['maximo_stock']) ? $valor : 1; 
}
/////////////////////////////////////////////////////////////
// SECTION CARRITO.PHP
function precio_compra($id){
  $dato_precio_compra = $GLOBALS['conexion']->query("SELECT precio_c FROM p_precios WHERE id_producto = '$id'")->fetch(PDO::FETCH_ASSOC);
  return $dato_precio_compra['precio_c'];
}
function send_mail($mail,$id,$nombre,$id_venta,$fecha,$vendedor){
  $to = $mail;
  $subject = ($id) ? ($vendedor) ? "VENDEDOR: $nombre" : "CLIENTE: $nombre" : "VISITANTE: $nombre";

  $contenido .= "LINK: https://tiendacatri.online/admin/ver_pedidos.php?verPedido=$id_venta\n\n";
  $contenido .= "FECHA: $fecha\n\n";

  $header = "From: pedidos@tiendacatri.com\nReply-To:$mail\n";
	$header .= "Mime-Version: 1.0\n";
	$header .= "Content-Type: text/plain";

  mail($to, $subject, $contenido ,$header);
  unset($_SESSION['carrito']);
	unset($_SESSION['gastoTotal']);
	$_SESSION['comprobante'] = 0;
}
/////////////////////////////////////////////////////////////
// SECTION USER/COMPRAS.PHP
function consulta_ventas_usuario($id){
  $consulta = $GLOBALS['conexion']->prepare("SELECT * FROM ventascliente WHERE id_usuario = :id ORDER BY fecha DESC");
  $consulta->execute([":id" => $id]);
  return $consulta;
}
function compras_detalles($id){
	$id_usuario = $_SESSION['id'];
	$pertenece = $GLOBALS['conexion']->query("SELECT * FROM ventascliente WHERE ID = $id AND id_usuario = $id_usuario")->rowCount();
	if($pertenece){
    $id_venta = $_GET['compra'];
    $datos_ventascliente = ventascliente($id_venta)->fetch(PDO::FETCH_ASSOC);
    $estado = $datos_ventascliente['estado'];
    $fecha = formato_fecha($datos_ventascliente['fecha_entrega']);
    $aviso_entrega = ($estado == 1) ? "<b>ENTREGADO:</b> ".$fecha : "";
    $ticket = ($estado != 2) ? "<div><a href='../ticket/index.php?ticket=".$id_venta."' target='_blank'>TICKET</a></div>" : "";
		echo '
    <div class="compras__ticket">
      <div class="compras__titulo b_superior">COMPRA #'.$id_venta.' ('.ventascliente_estado_texto($estado).')</div>
      <div class="informacion b_laterales center">
        <div class="detalles center">
          <div><b>PAGO:</b> '.metodo_pago($datos_ventascliente['metodo_pago']).'</div>
          '.$ticket.'
        </div>
        '.$aviso_entrega.'
      </div>
      <div class="compras__titulo footer b_inferior"></div>
      <div class="compras__titulo ticket b_superior">
        <div>PRODUCTO</div>
        <div>CANT</div>
        <div>PRECIO</div>
      </div>';				
		foreach(productos_venta($id_venta) as $p){
			$producto = producto($p['id_producto'])->fetch(PDO::FETCH_ASSOC); 
			echo "<div class='ticket informacion b_laterales center'>
              <div>".$producto["nombre"]."</div>
              <div>".$p['cantidad']."</div>
              <div>".formato_precio($p['subtotal'])."</div>
            </div>";
		}		
		echo "<div class='compras__titulo b_inferior'>TOTAL: ".formato_precio($datos_ventascliente['total'])."</div>
    </div>";
	} else {header("Location:compras.php");}
}
function borde_compras($color){return $borde = "style='box-shadow: 2px 3px ".$color."'";}
// SECTION BUSCADOR.PHP
function resultados_busqueda($palabra,$resultados_por_pagina, $pagina_actual, $admin) {
  $query = "SELECT p.id, p.nombre, pd.stock, pi.oferta, pi.dosxuno, pp.precio_v, pp.precio_o FROM productos p 
            INNER JOIN p_datos pd ON p.id = pd.id_producto
            INNER JOIN p_infoweb pi ON p.id = pi.id_producto
            INNER JOIN p_precios pp ON p.id = pp.id_producto
            WHERE (pd.palabrasClaves LIKE :palabra OR p.nombre LIKE :palabra)";
  $where = ($admin == 1) ? "" : "AND pd.stock > 0 AND pi.fecha_ultimo_precio != '0000-00-00' AND pp.precio_v > 0";
  $limit = "ORDER BY p.nombre ASC LIMIT :indice_primer_resultado, :resultados_por_pagina";
  
  // Determinar el número de resultados totales en la base de datos
  $parametro = "%$palabra%";
  $consulta_paginas = $GLOBALS['conexion']->prepare("$query $where");
  $consulta_paginas->execute([":palabra" => $parametro]);
  $num_paginas = ceil($consulta_paginas->rowCount() / $resultados_por_pagina);
  $pagina_actual = ($pagina_actual > $num_paginas) ? 1 : $pagina_actual;
  // Calcular el índice del primer resultado en la página actual

  $indice_primer_resultado = ($pagina_actual - 1) * $resultados_por_pagina;
  // Obtener los resultados para la página actual
  $resultados = $GLOBALS['conexion']->prepare("$query $where $limit");
  $resultados->bindParam(':indice_primer_resultado', $indice_primer_resultado, PDO::PARAM_INT);
  $resultados->bindParam(':resultados_por_pagina', $resultados_por_pagina, PDO::PARAM_INT);
  $resultados->bindParam(':palabra', $parametro);
  $resultados->execute();
  // Devolver los resultados
  return array(
    'resultados' => $resultados,
    'num_paginas' => $num_paginas
  );
}
// SECTION USER/VENTAS.PHP
function vendedor($id,$desde,$hasta){
	$where = ($desde) ? "AND (fecha >= '$desde 00:00:00' AND fecha <= '$hasta 23:59:59')" : "";
	$consulta = $GLOBALS['conexion']->query("SELECT * FROM ventascliente WHERE id_usuario = '$id' $where");
	return $consulta;
}
function comprador($id){
	$consulta = $GLOBALS['conexion']->prepare("SELECT * FROM datos_compradornr WHERE ID = '$id'");
	$consulta->execute([":id" => $id]);
	return $consulta;
}
function filtro_fechas($desde,$hasta,$opciones){
  $opciones = (isset($opciones)) ? $opciones : "";
	$filtro = "<form action='#' method='post'>
  <div class='filtro_fecha'>				
    <div>
      <input type='date' name='fecha_inicio' value='".$desde."'>
			<input type='date' name='fecha_fin' value='".$hasta."'>
			<input type='submit' class='btn_atras btn_morado' name='fecha_filtro' value='Filtrar'>
			<input type='submit' class='btn_atras btn_morado' name='fecha_all' value='Todas las ventas'>
    </div>
    <div class='btn_opciones'>
      $opciones
      <a href='../ticket/vendedor.php?desde=$desde&hasta=$hasta' class='btn_planilla' title='EXPORTAR PDF'><i class='fa fa-print fa-lg fa-fw'></i></a>
      <a href='../ticket/excel.php?desde=$desde&hasta=$hasta' class='btn_planilla' title='EXPORTAR EXCEL'><i class='fa fa-file-excel fa-lg fa-fw'></i></a>
    </div>    
</div>
</form>";
	return $filtro;
}
function detalles_venta($id_venta,$id_vendedor){
	$existencia = $GLOBALS['conexion']->query("SELECT * FROM ventascliente WHERE ID = $id_venta AND id_usuario = $id_vendedor");
	if($existencia->rowCount()){
		$datos_ventascliente = $existencia->fetch(PDO::FETCH_ASSOC);
		$datos_comprador = comprador($id_venta)->fetch(PDO::FETCH_ASSOC);
		$estado = $datos_ventascliente['estado'];
		$ticket = ($estado != 2) ? "<div><a href='../ticket/index.php?ticket=".$id_venta."' target='_blank'><i class='fa fa-list-alt fa-lg fa-fw'></i> TICKET <i class='fa fa-list-alt fa-lg fa-fw'></i></a></div>" : "";
		$aviso_entrega = ($estado == 1) ? "<b>ENTREGADO:</b> ".formato_fecha($datos_ventascliente['fecha_entrega'])." (".$datos_ventascliente['hora_entrega']." Hs)" : "";
		$tabla .= '	<div class="compras__ticket">
						<div class="compras__titulo b_superior">COMPRA #'.$id_venta.' ('.ventascliente_estado_texto($estado).')</div>
						<div class="informacion b_laterales center">
							<div class="detalles center">
								<div><b>NOMBRE</b>: '.$datos_comprador['nombre'].'</div>
								<div><b>APELLIDO</b>: '.$datos_comprador['apellido'].'</div>
								<div><b>TELEFONO</b>: <a href="https://wa.me/'.$datos_comprador['telefono'].'?text=Hola '.$datos_comprador['nombre'].'" target="_blank">'.$datos_comprador['telefono'].'</a></div>
								<div><b>DOMICILIO</b>: '.$datos_comprador['domicilio'].'</div>
								<div><b>METODO PAGO:</b> '.metodo_pago($datos_ventascliente['metodo_pago']).'</div>
								'.$ticket.'			
							</div>
							'.$aviso_entrega.'	
							</div>						
						<div class="compras__titulo footer b_inferior"></div>
						<div class="compras__titulo ticket b_superior">
							<div>PRODUCTO</div>
							<div>CANT</div>
							<div>PRECIO</div>
						</div>';
		foreach (productos_venta($id_venta) as $p){
			$producto = producto($p['id_producto'])->fetch(PDO::FETCH_ASSOC); 
			$tabla .= " <div class='ticket informacion b_laterales center'>
							<div>".$producto["nombre"]."</div>
							<div>".$p['cantidad']."</div>
							<div>".formato_precio($p['subtotal'])."</div>
						</div>";
		}
		$tabla .= '<div class="compras__titulo b_inferior">TOTAL: '.formato_precio($datos_ventascliente['total']).'</div>';
	} else {
		$tabla .= "NO ES TU VENTA";
	}
	echo $tabla;
}
?>