<?php 
$to = "admin@djdistribuciones.com.ar";
$subject = "AVISO NUEVO PEDIDO! (USUARIO $usuarioEMAIL)";
$contenido .= "LINK: https://djdistribuciones.com.ar/admin/ver_pedidos.php?verPedido=$id_venta\n\n";
$contenido .= "FECHA: ".$fecha."\n\n";
$header = "From: no-reply@c1810164.ferozo.com\nReply-To:admin@djdistribuciones.com.ar\n";
$header .= "Mime-Version: 1.0\n";
mail($to, $subject, $contenido ,$header);
?>