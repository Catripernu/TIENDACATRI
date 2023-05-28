<?php
require_once('clases/conect.php');
set_time_limit(0); //Establece el número de segundos que se permite la ejecución de un script.
$fecha_ac = isset($_POST['timestamp']) ? $_POST['timestamp']:0;

//$fecha_bd = $row['timestamp'];

$fecha_bd = '2019-01-01';

while( $fecha_bd <= $fecha_ac )
	{	
		$query3    = "SELECT fecha FROM ventascliente ORDER BY fecha DESC LIMIT 1";
		$con       = mysqli_query($dblink,$query3 );
		$ro        = mysqli_fetch_array($con);
		
		usleep(100);//anteriormente 10000
		clearstatcache();
		$fecha_bd  = strtotime($ro['fecha']);
	}

$query       = "SELECT * FROM ventascliente ORDER BY fecha DESC LIMIT 1";
$datos_query = mysqli_query($dblink,$query);
while($row = mysqli_fetch_array($datos_query))
{
	$ar["timestamp"]  = strtotime($row['fecha']);	
}
$dato_json   = json_encode($ar);
echo $dato_json;
?>