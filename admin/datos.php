<?php
include('../config.php');

$seccion = $_POST['seccion'];

$sql = "SELECT id_seccion,nombre FROM categorias WHERE id_seccion = '$seccion'";
$resultado = mysqli_query($conexion,$sql);

$cadena = "<select id='lista2' style='width: 50%;' name='categorias'>";

if($seccion == 0){
    $cadena=$cadena.'<option>CATEGORIAS</option>';
} else {
    while ($ver = mysqli_fetch_array($resultado)){ 
        $cadena=$cadena.'<option value='.$ver[0].'>'.utf8_encode($ver[1]).'</option>';     
    }
}
echo $cadena."</select>";
?>