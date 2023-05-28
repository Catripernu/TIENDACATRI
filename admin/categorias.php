<?php 
include('../config.php');

$sql = "SELECT * FROM seccionCategoria WHERE visual = 1 group by nombre";
$consulta = mysqli_query($conexion,$sql);

while ($resultado = mysqli_fetch_array($consulta)) {
	echo '<option value="'.$resultado['id'].'">'.$resultado['nombre'].'</option>';
}

?>