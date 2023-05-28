<?php
include '../../config.php';
if (isset($_POST['entregado'])) {
	$idVenta = $_POST['entregado'];
	$ganancia = $_POST['ganancia'];
	$id_usuario = $_POST['id_usuario'];
	// PARTE PRODUCTO
	$consultaPRODUCTOSVENTAVerPedido = mysqli_query($conexion,"SELECT * FROM productos_venta WHERE id_venta = '$idVenta'");
	$numero = mysqli_num_rows($consultaPRODUCTOSVENTAVerPedido);
			for ($i=0; $i < $numero; $i++) {
				$row = $consultaPRODUCTOSVENTAVerPedido->fetch_array();
				$id = $row['id_producto'];

				$consultaVENTATOTAL = mysqli_fetch_array(mysqli_query($conexion,"SELECT venta_total FROM p_infoweb WHERE id_producto='$id'"));
				$consultaSTOCK = mysqli_fetch_array(mysqli_query($conexion,"SELECT stock FROM p_datos WHERE id_producto='$id'"));

				// $ganancia = (($row['precio'] - $row['precio_v']) * $row['cantidad']) + $ganancia;
				$idProducto = $row['id_producto'];
				$totalCantidad = $consultaVENTATOTAL['venta_total'] + $row['cantidad'];
				$totalStock = $consultaSTOCK['stock'] - $row['cantidad'];
				mysqli_query($conexion, "UPDATE p_infoweb SET venta_total='$totalCantidad' WHERE id_producto='$idProducto'");
				mysqli_query($conexion, "UPDATE p_datos SET stock = '$totalStock' WHERE id_producto='$idProducto'");
			}

	// LA VENTA LA PONEMOS COMO COMPLETADA
	$fecha = date('Y-m-d');
	$hora = date('H:i:s');

	mysqli_query($conexion, "UPDATE ventascliente SET estado=1,ganancia='$ganancia', fecha_entrega= '$fecha', hora_entrega ='$hora' WHERE ID='$idVenta'");

	$res = mysqli_query($conexion, "SELECT id_usuario,total FROM ventascliente WHERE ID='$idVenta'");
	$dato = mysqli_fetch_array($res);
	$totalCompra = $dato['total'];

	if ($dato['id_usuario'] != 0) {
		$id = $dato['id_usuario'];
		$res = mysqli_query($conexion, "SELECT verificado,cant_compras,saldoFavor FROM users WHERE id='$id'");
		$dato = mysqli_fetch_array($res);

		//UTILIZAR SALDO FAVOR SI ES QUE TIENE EL USUARIO
		if ($_POST['saldoFavor'] == true) {
			$monto_saldoFavor = $_POST['montoSaldoFavor'];
			$saldo_actual = $dato['saldoFavor'];
			if ($saldo_actual > 0) {
				$saldoTotal = $saldo_actual - $monto_saldoFavor;
			} else {
				$saldoTotal = $saldo_actual + $monto_saldoFavor;
			}
			$conexion->query("UPDATE users SET saldoFavor='$saldoTotal' WHERE ID='$id'");
			$conexion->query("UPDATE ventascliente SET metodo_pago=3 WHERE ID='$idVenta'");
			$conexion->query("INSERT INTO cuentacorriente(codigo,id_usuario,fecha,importe,cc_total) VALUES ('$idVenta','$id','$fecha','$monto_saldoFavor','$saldo_actual')");
		}

		$total = $dato['cant_compras'] + 1;
		mysqli_query($conexion, "UPDATE users SET cant_compras='$total' WHERE ID='$id'");
		if ($total == 3) {
			mysqli_query($conexion, "UPDATE users SET verificado=1 WHERE ID='$id'");
		}
	}


}
?>
