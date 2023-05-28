<?php 
session_start();
include_once('./sql/config.php');
include_once('./admin/datosRelevantes.php');
include_once('./sql/consultas.php');
include_once('./sql/consulta_ip_visita.php');
include_once('./php/funtions_views.php');
setlocale(LC_MONETARY, 'en_US.UTF-8');
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $titulo; ?></title>
<?php include_once('./links.php'); ?>
</head>
<?php include_once('./menu/header.php'); ?>