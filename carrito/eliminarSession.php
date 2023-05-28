<?php
session_start();
if(isset($_GET['eliminar'])){
    unset($_SESSION['carrito']);
    unset($_SESSION['gastoTotal']);
} else {
    header("Location: ../index.php");
}
?>