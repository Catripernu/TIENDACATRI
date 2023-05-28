<?php 
//PRECIO DE ENVIO PARA USUARIO REGISTRADO O NO REGISTRADO
$envioConRegistro = 0;
$envioSinRegistro = 0;
//ENVIO CON MAS DE 2 PRODUCTOS EN EL CARRITO
$envioPocosProductos = 0;
$avisoPocosProductos = "ENVIO GRATIS";
//TEXTO AVISO DE LOS ENVIOS
$avisoEnvioConRegistro = "envio GRATIS";
$avisoEnvioSinRegistro = "envio GRATIS";

//PORCENTAJE PRECIOS DE VENTA EN LOS PRODUCTOS
$porInc100 = 0.10;
$porInc1000 = 0.05;

// MAXIMO STOCK PRODUCTOS
$resultados_por_pagina = 10;
$compras_resultados_por_pagina = 10;
$ventas_resultados_por_pagina = 10;
$maximo_stock = 15;


//TITULO DEL SITIO
if ($_SESSION['rol'] == 1) {
 	$titulo = "TIENDACATRI | Administracion";
 } else {
 	$titulo = "TIENDACATRI Digital Store";
 }

//TITULOS
$preTitulos = "TIENDACATRI | ";
$tituloProductos = "Productos";
$apk = "NOMBRE APK";
 
//CORREO ENVIO ALERTA DE COMPRA
$mail = "admin@tiendacatri.online";
$emailAvisoCarritoCompletado = "ccatrriel@gmail.com";

//URL SITIO
$url_site = "https://tiendacatri.online/";
?>