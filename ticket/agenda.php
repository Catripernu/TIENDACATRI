<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');
session_start();
	include 'plantilla.php';
	require '../config.php';


    if(isset($_SESSION['rol']) && $_SESSION['rol'] != 0){

    function function_consulta($rol,$id,$cliente){
        $sql = "SELECT * FROM agenda";
        if(isset($cliente)){
            if($rol == 1){
                $consulta_agenda = $GLOBALS['conexion']->query($sql." WHERE nombre_agenda LIKE '%$cliente%' or apellido_agenda LIKE '%$cliente%' or direccion_agenda LIKE '%$cliente%' ORDER BY id_agenda DESC");
            } else {
                $consulta_agenda = $GLOBALS['conexion']->query($sql." WHERE (id_vendedor = $id) AND (nombre_agenda LIKE '%$cliente%' or apellido_agenda LIKE '%$cliente%' or direccion_agenda LIKE '%$cliente%')");
            }
        } else {
            if($rol == 1){
                $consulta_agenda = $GLOBALS['conexion']->query($sql." ORDER BY id_agenda DESC");
            } else {
                $consulta_agenda = $GLOBALS['conexion']->query($sql." WHERE id_vendedor = $id ORDER BY id_agenda DESC");
            }
        }
        return $consulta_agenda;
    }
    
    $consulta_agenda = function_consulta($_SESSION['rol'],$_SESSION['id'],$_GET['cliente']);

	if ($existe = $consulta_agenda->num_rows) {
		$pdf = new PDF();
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$pdf->SetFillColor(232,232,232);
		$pdf->SetFont('Arial','B',10);

        foreach ($consulta_agenda as $dato) {
            $info_adm = false;
            if($_SESSION['rol'] == 1){
                $id_vendedor = $dato['id_vendedor'];
                $d_vendedor = $conexion->query("SELECT nombre FROM users WHERE id = $id_vendedor")->fetch_array();
                $info_adm = true;
            }

            $pdf->Cell(20,6,'ID',1,0,'C',1);
            $pdf->Cell(50,6,'FECHA',1, 0,'C',1);
            $pdf->Cell(50,6,'CELULAR',1, 0,'C',1);
            ($_SESSION['rol'] == 1 && $info_adm) ? $pdf->Cell(70,6,'VENDEDOR',1, 1,'C',1) : $pdf->Cell(70,6,'',1, 1,'C',1) ; 
            $pdf->Cell(20,6,'#'.$dato['id_agenda'],1,0,'C');   
            $pdf->Cell(50,6,$dato['fecha_agenda'],1,0,'C');
            $pdf->Cell(50,6,$dato['telefono_agenda'],1,0,'C'); 
            ($_SESSION['rol'] == 1 && $info_adm) ? $pdf->Cell(70,6,$d_vendedor['nombre'],1, 1,'C') : $pdf->Cell(70,6,"",1, 1,'C') ;       
            $pdf->Cell(90,6,'CLIENTE',1,0,'C',1);
            $pdf->Cell(0,6,'DIRECCION',1,1,'C',1);         
            $pdf->Cell(90,6,$dato['nombre_agenda'].", ".$dato['apellido_agenda'],1,0,'C'); 
            $pdf->Cell(0,6,$dato['direccion_agenda'],1,1,'C');
            $pdf->Cell(0,3,'',0,1,'C');
        }

		

        // $pdf->Output();

		$pdf->Output("agenda_". date('Y:m:d:H:i') .".pdf","D");
	} else {
	    header("Location: ../admin/agenda.php");
	}
} else {
    header("Location: ../index.php");
}
?>