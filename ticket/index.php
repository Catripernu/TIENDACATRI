<?php
session_start();
	include 'plantilla.php';
	include '../sql/consultas.php';
	require '../sql/config.php';
if (isset($_GET['ticket']) && isset($_SESSION['loggedin'])) {
		$id_user = $_SESSION['id'];
		$id_ticket = $_GET['ticket'];

		if($_SESSION['rol'] == 1){
			$resultado = $conexion->query("SELECT * FROM ventascliente WHERE ID = $id_ticket");
		} else {
			$consulta_ventascliente = $conexion->query("SELECT * FROM ventascliente WHERE id_usuario = $id_user AND ID = $id_ticket");
			$datos_usuario = $conexion->query("SELECT * FROM users WHERE id = $id_user")->fetch(PDO::FETCH_ASSOC);
			$existencia_vendedor = $conexion->query("SELECT * FROM datos_compradornr WHERE ID = $id_ticket")->rowCount();
		}

		if ($consulta_ventascliente->rowCount()){
			$ventascliente = $consulta_ventascliente->fetch(PDO::FETCH_ASSOC);
			$resultado = $conexion->query("SELECT * FROM productos_venta WHERE id_venta = $id_ticket");
			$estado = ($ventascliente['estado']) ? (($ventascliente['estado'] == 2) ? "X" : "C") : "P"; 

	
			$pdf = new PDF();
			$pdf->AliasNbPages();
			$pdf->AddPage();
	
			$pdf->SetFillColor(232,232,232);

			$pdf->SetFont('Arial','B',10);
			$pdf->Cell(150,5,'',0,0,'A',0);
			$pdf->Cell(40,5,'VENTA: #'.$id_ticket.'('.$estado.')',1,1,'C',1);

			$pdf->SetFont('Arial','B',12);
			
			$pdf->Cell(50,6,'FECHA DE COMPRA',1,0,'A',1);
			$pdf->Cell(140,6,$ventascliente['fecha'],1,1,'A',1);

			// $tipo_usuario = ($existencia_vendedor) ? "VENDEDOR" : "CLIENTE";
			// $pdf->Cell(50,6,$tipo_usuario,1,0,'A',1);
			// $pdf->Cell(140,6,$datos_usuario['telefono'],1,1,'A',1);

			$pdf->Cell(50,6,'CLIENTE',1,0,'A',1);
			$pdf->Cell(140,6,utf8_decode($datos_usuario['nombre']).", ".utf8_decode($datos_usuario['apellido']),1,1,'A',1);

			// $pdf->Cell(50,6,'DOMICILIO',1,0,'A',1);
			// $pdf->MultiCell(140,6,utf8_decode($datos_usuario['domicilio']),1,1,'A',1);

			$pdf->Cell(140,6,'DETALLE',1,0,'C',1);
			$pdf->Cell(10,6,'C',1,0,'C',1);
			$pdf->Cell(20,6,'P/U',1,0,'C',1);
			$pdf->Cell(20,6,'TOTAL',1,1,'C',1);
	
			$pdf->SetFont('Arial','',10);

			foreach($resultado as $row){
				$dato = producto($row['id_producto'])->fetch(PDO::FETCH_ASSOC);
				$pdf->Cell(140,6,utf8_decode($dato['nombre']),1,0,'A');
				$pdf->Cell(10,6,utf8_decode($row['cantidad']),1,0,'C');
				$pdf->Cell(20,6,formato_precio($row['precio']),1,0,'C');
				$pdf->Cell(20,6,formato_precio($row['subtotal']),1,1,'C');
			}			

			$pdf->Cell(170,6,'TOTAL',1,0,'C');
			$pdf->Cell(20,6,formato_precio($ventascliente['total']),1,1,'C');

			// if ($existencia_vendedor) {
			// 	$pdf->SetFont('Arial','B',10);
			// 	$pdf->Cell(190,10,'',0,1,'C');
			// 	$pdf->Cell(50,6,'VENDEDOR',1,0,'C',1);
			// 	$pdf->Cell(140,6,utf8_decode($vendedor),1,1,'C',1);
			// }
			if ($ventascliente['sugerencia']) {
				$pdf->SetFont('Arial','B',16);
				$sugerencia = utf8_decode($ventascliente['sugerencia']);
				$pdf->MultiCell(190,10,"SUGERENCIA: ".$sugerencia,1,1,'L',0);
			}
			$pdf->Output();
		} else {
			header("Location: ../user/compras.php");
		}





		// $dato_consulta = $resultado->fetch_assoc();	
		// // CONSULTAMOS SI ES VENDEDOR
		// if ($dato_consulta['id_usuario'] != 0) {
		// 	$id_vendedor = $dato_consulta['id_usuario'];
		// 	$vendedor = $conexion->query("SELECT nombre,apellido,telefono FROM users WHERE id = $id_vendedor")->fetch_assoc();
		// 	$existencia_vendedor = $conexion->query("SELECT * FROM datos_compradornr WHERE ID = $idTicket")->num_rows;
		// }
		
		// if ($existencia_vendedor) {
		// 	$vendedor = $vendedor['nombre'].", ".$vendedor['apellido']." (".$vendedor['telefono'].")";
		// }
		
		// if ($dato_consulta['id_usuario'] != 0 && $existencia_vendedor == 0) {
		// 	$datos_usuario = $conexion->query("SELECT * FROM users WHERE id = ".$dato_consulta['id_usuario']."")->fetch_array();
		// } else {
		// 	$datos_usuario = $conexion->query("SELECT * FROM datos_compradornr WHERE ID = $idTicket")->fetch_array();
		// }
		
		
		
		// if ($resultado != 0) {
			
			
		// } 
} else {
	header("Location: ../index.php");
}
?>