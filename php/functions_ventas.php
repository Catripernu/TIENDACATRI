<?php 
function fechas_search($fecha_all, $fecha_inicio, $fecha_fin){
    if(isset($fecha_all)){
        unset($_SESSION['fecha_inicio']);
        $_SESSION['fecha_fin'] = date("Y-m-d");
    } else {
        if(isset($fecha_inicio)){
            $_SESSION['fecha_inicio'] = $fecha_inicio;
            $_SESSION['fecha_fin'] = date("Y-m-d");
            if(isset($fecha_fin)){
                $_SESSION['fecha_fin'] = $fecha_fin;
            }
        } else {
            $_SESSION['fecha_fin'] = date("Y-m-d");
        }
    }
}
?>