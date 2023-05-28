<?php 
$ip_actual = $_SERVER['REMOTE_ADDR'];

// CONSULTAS SQL
$rs_visita_real = $conexion->query("SELECT * FROM visitaIp WHERE ip='$ip_actual'")->num_rows;



if ($rs_visita_real === 0 && empty($_SESSION['loggedin'])) { 
    echo '<link rel="stylesheet" type="text/css" href="css/alertify.css">
  <link rel="stylesheet" type="text/css" href="css/themes/default.css">
  <script src="js/alertify.js"></script>';
  $link = '<a href="ayuda.php">PREGUNTAS FRECUENTES</a>';
   echo "<script>
    $(document).ready(function(){
        alertify.alert('<center><h3>Hola, es tu primera visita al sitio, te recomiendo dar una vuelta por las Preguntas Frecuentes, para asi poder despejar tus dudas.</h3><br><br>".$link."</center>');
    });
  </script>";
    $insert_real = "INSERT INTO visitaIp (ip) VALUES ('".$_SERVER['REMOTE_ADDR']."')";
    mysqli_query($conexion,$insert_real);
  }


?>