<?php 
session_start();
$arreglo = $_SESSION['carrito'];
unset($_SESSION['gastoTotal']);
foreach ($_SESSION['carrito'] as $c){
    if($c['Id'] != $_POST['id']){
      $arregloNuevo[] = array(  'Id' => $c['Id'],
                                'Nombre' => $c['Nombre'],
                                'Precio' => $c['Precio'],
                                'Cantidad' => $c['Cantidad']);
      $_SESSION['gastoTotal'] = $_SESSION['gastoTotal'] + ($c['Precio'] * $c['Cantidad']);
    }
  }
if (isset($arregloNuevo)) {
	$_SESSION['carrito'] = $arregloNuevo;
} else {
	unset($_SESSION['carrito']);
	unset($_SESSION['gastoTotal']);
}
?>