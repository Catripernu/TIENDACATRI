<?php
include('../config.php');

$query = "SELECT DISTINCT grupo FROM p_precios WHERE grupo LIKE '%".$request."%'";
$result = mysqli_query($conexion,$query);
$data = array();
if (mysqli_num_rows($result) > 0) {
	while ($row = mysqli_fetch_assoc($result)) {
		$data[] = $row["grupo"];
	}
	echo json_encode($data);
}

?>