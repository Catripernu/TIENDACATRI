<?php 
session_start();
include_once('../sql/config.php');
include_once('../sql/consultas.php');
include_once('../admin/datosRelevantes.php');

if (isset($_POST['finalizar'])) {

	$pago = $_POST['pago'];
	$arreglo = $_SESSION['carrito'];
    $sugerencia = eliminar_acentos($_POST['sugerencia']);
    $id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $total = $_SESSION['gastoTotal'];

	$nombre_vendedor = (isset($_SESSION['nombre'])) ? $_SESSION['nombre'] : "";
    $nombre = eliminar_acentos(strtoupper($_POST['nombre']));
    $apellido = eliminar_acentos(strtoupper($_POST['apellido']));
    $domicilio = eliminar_acentos(strtoupper($_POST['direccion']));
    $telefono = $_POST['telefono'];
    $ciudad = $_POST['ciudad'];
    $provincia = $_POST['provincia'];

	//DATOS PRIMER REGISTRO EN VENTAS (ID USUARIO, TOTAL A PAGAR, FECHA DE COMPRA)
	if (count($arreglo) > 2) {
		$totalEnvio = $envioSinRegistro;	// PRIVENIENTE DE DATOSRELEVANTES.PHP
	} else {
		$totalEnvio = $envioPocosProductos + $envioSinRegistro;
	}
	//SUMAMOS EL TOTAL DEL CARRITO + ENVIO
	$total = $total + $totalEnvio;
	///////////////// OBTENCION DE LA HORA /////////////////////
	date_default_timezone_set('America/Argentina/Buenos_Aires');
	$fecha = date('Y-m-d H:i:s');
	////////////////////////////////////////////////////////////
	///////////////// GUARDAMOS LOS DATOS DE LA VENTA /////////////////////
	$conexion->query("INSERT INTO ventascliente(id_usuario,total,fecha,metodo_pago,estado,fecha_entrega,hora_entrega,ganancia,envio,sugerencia) values ('$id','$total','$fecha','$pago','0','0000-00-00','00:00:00','0','$totalEnvio','$sugerencia')");
	$id_venta = $conexion->lastInsertId();
	///////////////// GUARDAMOS LOS DATOS DEL CLIENTE NO REGISTRADO /////////////////////
	$conexion->query("INSERT INTO datos_compradornr(ID,nombre,apellido,domicilio,telefono,ciudad,provincia) values ('$id_venta','$nombre','$apellido','$domicilio','$telefono','$ciudad','$provincia')");					
	///////////////// GUARDAMOS EL CARRITO DEL CLIENTE NO REGISTRADO /////////////////////
	foreach ($arreglo as $p){
        $conexion->query("INSERT INTO productos_venta(id_venta,cliente,id_producto,cantidad,precio,subtotal,precio_c) values ('$id_venta','$id','".$p['Id']."','".$p['Cantidad']."','".$p['Precio']."','".$p['Cantidad']*$p['Precio']."','".precio_compra($p['Id'])."')");
    }

	$nombre = (isset($nombre_vendedor)) ? $nombre_vendedor : $nombre;
	send_mail($mail,$id,$nombre,$id_venta,$fecha,(($id) ? true : false));
	
	if ($id != 0) {
		echo "RECIBIMOS TU VENTA CON EXITO, EN UNOS MOMENTOS NOS ESTAREMOS COMUNICANDO CON USTED ".strtoupper($nombre).", GRACIAS.";
	} else {
		echo "RECIBIMOS TU PEDIDO CON EXITO, EN UNOS MOMENTOS NOS ESTAREMOS COMUNICANDO CON USTED ".strtoupper($nombre)." VIA WHATSAPP, GRACIAS.";
	}
	} else {
        header("Location: ./../index.php");
    }
?>