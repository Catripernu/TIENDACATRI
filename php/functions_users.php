<?php 
function user($id){
    return $consulta = $GLOBALS['conexion']->query("SELECT * FROM users WHERE id = '$id'");
}

?>