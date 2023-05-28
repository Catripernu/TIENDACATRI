<?php include_once("head.php"); ?>
<?php 
// CREA SESSION CARRITO O INCREMENTA EL PRODUCTO A LA SESSION
if(isset($_SESSION['carrito'])){
	if (isset($_POST['id'])) {
		$id = $_POST['id'];
		$arreglo =$_SESSION['carrito'];
		$encontro = false;
		$numero = 0;
		for ($i=0; $i < count($arreglo); $i++) { 
			if($arreglo[$i]['Id'] == $id){
				$encontro = true;
				$numero = $i;
			}
		}
		if($encontro){
			$cantidad = ADD_CANTIDAD($_POST['cant']);
			$arreglo[$numero]['Cantidad']=$arreglo[$numero]['Cantidad']+$cantidad;
			$_SESSION['carrito'] = $arreglo;
			$_SESSION['gastoTotal'] = ($cantidad*$arreglo[$numero]['Precio']) + $_SESSION['gastoTotal'];
		} else {
			agregar_carrito($id, $_POST['cant'],$arreglo);
		}
	}
} else {
	if (isset($_POST['id'])) {
		agregar_carrito($_POST['id'], $_POST['cant'],false);
	}
}
?>
<body>
<div id="contenido">
<?php 

//SECCION FINALIZAR CARRITO
if (isset($_GET['finalizar'])) {
	if ($_SESSION['comprobante'] == 1) {
		if (isset($_POST['pago']) == 1 || isset($_POST['pago']) == 2) {
			$pago = $_POST['pago'];
			$sugerencia = (isset($_POST['sugerencia'])) ? $_POST['sugerencia'] : "-";

			if(isset($_SESSION['loggedin'])){
				if($_SESSION['rol'] == 2){datosEnvio($pago,$sugerencia,"NECESITAMOS LOS DATOS DE TU CLIENTE.");} else {
					$arreglo = $_SESSION['carrito'];
					$id = $_SESSION['id'];
					$nombre = $_SESSION['nombre'];
					$total = $_SESSION['gastoTotal'];
					$fecha = date('Y-m-d H:i:s');

					if (count($arreglo) > 2) {
						$totalEnvio = $envioConRegistro;	// PRIVENIENTE DE DATOSRELEVANTES.PHP
					} else {
						$totalEnvio = $envioPocosProductos + $envioConRegistro;
					}
					$total = $total + $totalEnvio;

					// REGISTRAR VENTA
					$conexion -> query("INSERT INTO ventascliente(id_usuario,total,fecha,metodo_pago,estado,fecha_entrega,hora_entrega,ganancia,envio,sugerencia) values ('$id','$total','$fecha','$pago','0','0000-00-00','00:00:00','0','$totalEnvio','$sugerencia')");
					$id_venta = $conexion -> lastInsertId();

					// REGISTRAR PRODUCTOS EN PRODUCTOS_VENTAS
					foreach ($arreglo as $p){
						$conexion->query("INSERT INTO productos_venta(id_venta,cliente,id_producto,cantidad,precio,subtotal,precio_c) values ('$id_venta','$id','".$p['Id']."','".$p['Cantidad']."','".$p['Precio']."','".$p['Cantidad']*$p['Precio']."','".precio_compra($p['Id'])."')");
					}

					send_mail($mail,$id,$nombre,$id_venta,$fecha,false);
					mensajeFinishCart($nombre);
				}
			} else {datosEnvio($pago,$sugerencia,"NECESITAMOS TUS DATOS PARA COMUNICARNOS.");}
		} else {
			//SECCION SELECCIONAR METODO DE PAGO
			metodoDePago();
		}
	} else {
		header("location: index.php");
	}
} else {	
	if(isset($_SESSION['carrito'])){ 
		foreach ($_SESSION['carrito'] as $p){
			CARRITO($p['Id'],$p['Nombre'],$p['Precio'],$p['Cantidad'],($p['Precio']*$p['Cantidad']));
			$total += ($p['Precio']*$p['Cantidad']); 
			$cantidadProductos += $p['Cantidad'];
		}
		list($total,$aviso) = valorTotal($total,$envioConRegistro,$envioSinRegistro,$avisoEnvioConRegistro,$avisoEnvioSinRegistro,$envioPocosProductos,$avisoPocosProductos,$cantidadProductos);
?>
<div class="carrito__total_pagar">Total: <b><?=formato_precio($total); echo "<br>(".$aviso.")</b>"?></div>
<div class="btn__opciones">
	<a class="rojo btnEliminarCarrito" href="#">ELIMINAR CARRITO</a>
	<a class="verde" href="carrito.php?finalizar">FINALIZAR COMPRA</a>
</div>
<?php
	} else {
		echo '<b>CARRITO DE COMPRAS VACIO, <a class="link__search_product" href="./index.php">BUSCAR PRODUCTOS AQUI</a>.</b>';
	}
} ?>
</div>
</body>
</html>



<script type="text/javascript">
	$(function(){
  $('#finalizar').on('click', function(e){
      e.preventDefault();
      var nombre = $('#nombre').val();
      var apellido = $('#apellido').val();
      var direccion = $('#direccion').val();
      var telefono = $('#telefono').val();
      var ciudad = $('#ciudad').val();
      var provincia = $('#provincia').val();
      var pago = $('#pago').val();
      var sugerencia = $('#sugerencia').val();
      var id = $('#id').val();
      if(nombre == "" && apellido == "" && direccion == "" && telefono == ""){
          alert("ERROR: TODOS LOS CAMPOS ESTAN VACIOS.");
      } else {
          if(nombre == ""){
              alert("ERROR: NO INGRESO SU NOMBRE.");
          } else if(apellido == ""){
              alert("ERROR: NO INGRESO SU APELLIDO.");
          } else if(direccion == ""){
              alert("ERROR: NO INGRESO SU DIRECCION.");
          } else if(telefono == ""){
              alert("ERROR: NO INGRESO SU TELEFONO.");
          } else if(ciudad == ""){
              alert("ERROR: NO INGRESO SU CIUDAD.");
          } else if(provincia == ""){
              alert("ERROR: NO INGRESO SU PROVINCIA.");
          } else {
         		$.ajax({
              type: "POST",
              url: "./carrito/process_visit.php",
              data: ('finalizar&nombre='+nombre+'&apellido='+apellido+'&direccion='+direccion+'&telefono='+telefono+'&ciudad='+ciudad+'&provincia='+provincia+'&pago='+pago+'&sugerencia='+sugerencia+'&id='+id),
				  success:function(respuesta){    
                  alert(respuesta);
                  window.location="./index.php";
                  } 
              })
          }
      }
  })
})

</script>
<?php
function metodoDePago(){
	echo '<div class="carrito__metodopago">
			<p>Agregar una sugerencia/aclaración?</p>
  			<form align="center" method="post" action="carrito.php?finalizar">
				<label class="swtich-container">	
					<div class="slider">
						<input type="checkbox" id="switch" onchange="javascript:mostrarContenido()">
						<span class="on verde">SI</span> / <span class="off rojo">NO</span>
					</div>
					<div class="sugerencia" style="display:none" id="sugerencia">
						<input type="text" name="sugerencia">
					</div>
				</label>
				<p>COMO PAGARLO</p>
				<input class="btn__formapago btn__efectivo" type="submit" name="pago" value="1">
				<input class="btn__formapago btn__otros" type="submit" name="pago" value="2">
			</form>
		</div>';
}



function datosEnvio($pago,$sugerencia,$texto){
	$id = (isset($_SESSION['id'])) ? $_SESSION['id'] : 0;
	echo '<div class="carrito__final">
		<h3 align="center">POR ULTIMO, '.$texto.'</h2>
		<form align="center" method="post" action="">
        	<input type="text" id="nombre" name="nombre" requerid placeholder="nombre"><br>
        	<input type="text" id="apellido" name="apellido" requerid placeholder="apellido"><br>
        	<input type="text" id="direccion" name="direccion" requerid placeholder="dirección"><br>
        	<input type="text" id="telefono" minlength="10" maxlength="10" requerid onkeypress="return validaNumericos(event)" name="telefono" placeholder="teléfono"><br>
        	<input type="text" id="ciudad" name="ciudad" requerid placeholder="ciudad"><br>
        	<input type="text" id="provincia" name="provincia" requerid placeholder="provincia"><br>
        	<input type="hidden" name="pago" id="pago" value="'.$pago.'">
        	<input type="hidden" name="sugerencia" id="sugerencia" value="'.$sugerencia.'">
        	<input type="hidden" name="id" id="id" value="'.$id.'">
			<input type="submit" class="btn_finish" name="finalizar" id="finalizar" value="Finalizar Compra">
		</form>
	</div>';
}

function CARRITO($id,$nombre,$precio,$cantidad,$subtotal){
	echo '
	<div class="view_items carrito">
		<div class="img"><img src="'.$url_site.'images/productos/ompick_'.$id.'.webp" alt="'.$nombre.'" /></div>
		<div class="producto">'.$nombre.'</div>
		<div class="precio">'.formato_precio($precio).'</div>
		<div class="cantidad">'.$cantidad.'  
		</div>
		<div class="eliminar"><a class="BotonBorrarCarrito btnEliminar" title="Eliminar '.$nombre.'" href="#" data-id="'.$id.'" data-nombre="'.$nombre.'"><i class="fas fa-times"></i></a></div>
		<div class="total">'.formato_precio($subtotal).'</div>
  	</div>
	';
}

function valorTotal($total,$envioConRegistro,$envioSinRegistro,$avisoEnvioConRegistro,$avisoEnvioSinRegistro,$envioPocosProductos,$avisoPocosProductos,$cantidadProductos){
	$n_total = 0;
	if ($cantidadProductos > 0) {
		if (isset($_SESSION['loggedin'])) {
			$total = $total + $envioConRegistro;
			$aviso = $avisoEnvioConRegistro;
			//DESCUENTO 1% CADA $1000
			// $n_total = intdiv($total, 1000);
			// if ($n_total < 10) {
			// 	$n_total = $n_total * 10;
			// 	$total = $total - $n_total;
			// 	$aviso = "DESCUENTO DEL 1% CADA $1000!";
			// }
		} else {
			$total = $total + $envioSinRegistro;
			$aviso = $avisoEnvioSinRegistro;
		}
	} else {
		$total = $total + $envioPocosProductos;
		$aviso = $avisoPocosProductos;
	}
	return array($total,$aviso);
}

function mensajeFinishCart($nombre){
	echo "<div id='computadora'><div class='avisoCompraFinalizadaRegistrado'><img src='./images/carritoFinalizarCompra.png'><br>RECIBIMOS TU PEDIDO CON <b style='color:green'>EXITO</b>, EN UNOS MOMENTOS NOS ESTAREMOS COMUNICANDO CON USTED <font>$nombre</font> PARA VALIDAR LA COMPRA, GRACIAS.<br><br><br><br>SERAS ENVIADO AL INICIO EN UNOS SEGUNDOS...</div></div>";
	echo "<div id='celular'><div class='avisoCompraFinalizadaRegistrado'><img src='./images/carritoFinalizarCompra.png'><br>RECIBIMOS TU PEDIDO CON <b style='color:green'>EXITO</b>, EN UNOS MOMENTOS NOS ESTAREMOS COMUNICANDO CON USTED <font>$nombre</font> PARA VALIDAR LA COMPRA, GRACIAS.<br><br>SERAS ENVIADO AL INICIO EN UNOS SEGUNDOS...</div></div>";
	echo "<meta http-equiv='refresh' content='7;URL=index.php' />";
}


?>