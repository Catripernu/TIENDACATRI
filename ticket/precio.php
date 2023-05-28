<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');
session_start();
	include 'plantilla.php';
	require '../config.php';
    if(isset($_SESSION['rol']) && $_SESSION['rol'] != 0){
       
       $datos_precios = $GLOBALS['conexion']->query("SELECT p_datos.id_producto,p_datos.categoria,p_datos.stock,productos.id,productos.nombre,p_infoweb.oferta,p_precios.precio_o,p_precios.precio_v 
											FROM p_datos 
											INNER JOIN productos ON p_datos.id_producto=productos.id 
											INNER JOIN p_infoweb ON p_infoweb.id_producto=p_datos.id_producto 
											INNER JOIN p_precios ON p_precios.id_producto=p_datos.id_producto 
											WHERE p_datos.stock != 0 AND p_precios.precio_v > 0 AND p_infoweb.fecha_ultimo_precio != '0000-00-00'
											ORDER BY productos.nombre ASC");
        $total_productos = $datos_precios->num_rows;
    

	if ($existe = $datos_precios->num_rows) {
		$pdf = new PDF();
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$pdf->SetFillColor(232,232,232);
		$pdf->SetFont('Arial','B',10);

        $pdf->Cell(190,6,"TOTAL PRODUCTOS: $total_productos",1,1,"C",1);
        $pdf->Cell(20,6,'ID',1,0,'C',1);
        $pdf->Cell(140,6,'DETALLE',1, 0,'C',1);
        $pdf->Cell(30,6,'PRECIO',1, 1,'C',1);

        foreach ($datos_precios as $dato) {

            $pdf->Cell(20,6,"#".$dato['id'],1,0,'C');
            $pdf->Cell(140,6,$dato['nombre'],1, 0,'C');
            $pdf->Cell(30,6,"$".$dato['precio_v'],1, 1,'C');

        }	
        // $pdf->Output();
		$pdf->Output("agenda_". date('Y:m:d:H:i') .".pdf","D");
	} else {
	    header("Location: ../admin/precio.php");
	}
} else {
    header("Location: ../index.php");
}
?>