<?php

session_start();

	include 'plantilla.php';

	require '../config.php';

if (isset($_GET['vendedor']) || isset($_GET['ticket'])) {

	if ($_SESSION['rol'] === 1) {



		$cuadro_vendedor = true;

		$buscar_usuario = false;

		$fecha = false;

		$suma_total = 0;

		



		if (isset($_GET['start'])) {

			$fecha_start = $_GET['start'];

			$fecha_end = $_GET['end'];

			$fecha = true;

		}



		if (isset($_GET['ticket'])) {	

			$palabra = $_GET['ticket'];

			if (empty($palabra)) {

				// mostramos todas las ventas realizadas que muestran en tickets		

				$consulta_ventas = $conexion->query("SELECT * FROM ventascliente WHERE estado = 1 ORDER BY fecha DESC");

				$cuadro_vendedor = false;	

				$buscar_usuario = true;

				if ($fecha) {

					$consulta_ventas = $conexion->query("SELECT * FROM ventascliente WHERE estado = 1 AND (fecha >= '$fecha_start 00:00:00' AND fecha <= '$fecha_end 23:59:59') ORDER BY fecha DESC");

				}

			} else {

				if ($fecha) {

					$consulta_ventas = $conexion->query("SELECT 

														vc.ID, 

														vc.id_usuario,

														vc.total, 

														vc.fecha,

														COALESCE(dc.ID,u.id) as id, 

														COALESCE(dc.nombre, u.nombre) as nombre,

														COALESCE(dc.apellido,u.apellido)as apellido

													FROM ventascliente vc 

													LEFT JOIN datos_compradornr dc

													ON vc.ID=dc.ID

													LEFT JOIN users u

													ON vc.id_usuario=u.id

													WHERE vc.estado = 1 AND (dc.nombre LIKE '%".$palabra."%' OR u.nombre LIKE '%".$palabra."%' OR dc.apellido LIKE '%".$palabra."%' OR u.apellido LIKE '%".$palabra."%') AND (vc.fecha >= '$fecha_start 00:00:00' AND vc.fecha <= '$fecha_end 23:59:59') ORDER BY vc.fecha DESC");

				} else {

					$consulta_ventas = $conexion->query("SELECT 

														vc.ID, 

														vc.id_usuario,

														vc.total, 

														vc.fecha,

														COALESCE(dc.ID,u.id) as id, 

														COALESCE(dc.nombre, u.nombre) as nombre,

														COALESCE(dc.apellido,u.apellido)as apellido

													FROM ventascliente vc 

													LEFT JOIN datos_compradornr dc

													ON vc.ID=dc.ID

													LEFT JOIN users u

													ON vc.id_usuario=u.id

													WHERE vc.estado = 1 AND (dc.nombre LIKE '%".$palabra."%' OR u.nombre LIKE '%".$palabra."%' OR dc.apellido LIKE '%".$palabra."%' OR u.apellido LIKE '%".$palabra."%') ORDER BY vc.fecha DESC");

				}

				

				$cuadro_vendedor = false;

				$buscar_usuario = true;

			}

		}



		if (isset($_GET['vendedor'])) {

			$id = $_GET['vendedor'];

			$vendedor = $conexion->query("SELECT * FROM users WHERE id = $id")->fetch_array();

			if ($fecha) {

				$consulta_ventas = $conexion->query("SELECT * FROM ventascliente vc INNER JOIN datos_compradornr dc ON vc.ID=dc.ID WHERE vc.estado = 1 AND vc.id_usuario = '$id' AND (vc.fecha >= '$fecha_start 00:00:00' AND vc.fecha <= '$fecha_end 23:59:59') ORDER BY vc.fecha DESC");

			} else {

				$consulta_ventas = $conexion->query("SELECT * FROM ventascliente WHERE estado = 1 AND id_usuario = '$id' ORDER BY fecha DESC");

			}

		}		



		

	if ($existe = $consulta_ventas->num_rows) {

			

			$pdf = new PDF();

			$pdf->AliasNbPages();

			$pdf->AddPage();

			

			$pdf->SetFillColor(232,232,232);

			$pdf->SetFont('Arial','B',12);



			if ($cuadro_vendedor) {	

				$pdf->Cell(50,10,'VENDEDOR',1,0,'C',1);

				$pdf->Cell(140,10,$vendedor['nombre'].", ".$vendedor['apellido'],1,1,'C',1);

			}				

			

			$pdf->Cell(12,6,'#',1,0,'C',1);

			$pdf->Cell(15,6,'ID',1,0,'C',1);

			$pdf->Cell(20,6,'FECHA',1,0,'C',1);

			$pdf->Cell(123,6,'CLIENTE',1,0,'C',1);

			$pdf->Cell(20,6,'TOTAL',1,1,'C',1);

	

			$pdf->SetFont('Arial','',10);

		

			while($row = $consulta_ventas->fetch_assoc()){

				$id_venta = $row['ID'];

				$id_usuario = $row['id_usuario'];

				$total = $row['total'];

				$suma_total = $total + $suma_total;

				$fecha = date("d/m/Y", strtotime($row['fecha']));

				$muestra_vendedor = false;

				$cliente_vendedor = false;

				$consumidor_final = false;



				if ($buscar_usuario) {

					$consulta_existencia = $conexion->query("SELECT * FROM datos_compradornr WHERE ID = $id_venta")->num_rows;

					if ($consulta_existencia) {

						//VISITANTE

						$cliente = $conexion->query("SELECT nombre, apellido FROM datos_compradornr WHERE ID = $id_venta")->fetch_array();		

						$consumidor_final = true;				

						if ($id_usuario) {

							//VENDEDOR

							$vendedor = $conexion->query("SELECT nombre, apellido FROM users WHERE id = $id_usuario")->fetch_array();

							$muestra_vendedor = true;

						}

					} else {

						//CLIENTE

						$cliente = $conexion->query("SELECT nombre, apellido FROM users WHERE id = $id_usuario")->fetch_array();

					}

				} else {

					//CLIENTE DEL VENDEDOR

					$cliente_vendedor = true;

					$cliente = $conexion->query("SELECT nombre, apellido, telefono FROM datos_compradornr WHERE ID = $id_venta")->fetch_array();

				}

				

				$pdf->cell(12,6,$existe,1,0,'C');

				$pdf->Cell(15,6,"#".$id_venta,1,0,'C');

				$pdf->Cell(20,6,$fecha,1,0,'C');

				if ($muestra_vendedor) {

					$pdf->Cell(123,6,utf8_decode($cliente['nombre']).", ".utf8_decode($cliente['apellido']." (".$vendedor['nombre'].", ".$vendedor['apellido'].")"),1,0,'C');

				} else {

					if ($consumidor_final) {

						$pdf->Cell(123,6,utf8_decode($cliente['nombre']).", ".utf8_decode($cliente['apellido'])." (C.F)",1,0,'C');

					} else {

						if ($cliente_vendedor) {

							$pdf->Cell(123,6,utf8_decode($cliente['nombre']).", ".utf8_decode($cliente['apellido'])." (".utf8_decode($cliente['telefono']).")",1,0,'C');

						} else {

							$pdf->Cell(123,6,utf8_decode($cliente['nombre']).", ".utf8_decode($cliente['apellido'])." (R)",1,0,'C');

						}						

					}

					

				}

				$pdf->Cell(20,6,"$".$total,1,1,'C');

				$existe--;

			}

			$pdf->SetFont('Arial','',14);

			$pdf->cell(190,10,"TOTAL EN VENTAS: $".$suma_total,1,0,'C',1);



			$pdf->Output();









	} else {

		header("Location: ../admin/vendedores.php");

	}

	} else {

		header("Location: ../index.php");

	}

} else {

	header("Location: ../index.php");

}

?>