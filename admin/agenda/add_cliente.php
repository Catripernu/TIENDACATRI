<?php 
session_start();
if(isset($_GET['add'])){
    $id_vendedor = $_SESSION['id'];
    $tabla = "<h3>Nuevo cliente</h3>
            <div class='formulario'>
                <form action='#' method='post'>
                <label>Nombre</label><input type='text' required name='nombre' /><br>
                <label>Apellido</label><input type='text' required name='apellido' /><br>
                <label>Domicilio</label><input type='text' required name='domicilio' /><br>
                <label>Telefono</label><input type='text' required autocomplete='off' name='telefono' maxlength='10' onkeypress='return validaNumericos(event)' /><br>
                <a href='#' class='exit' onclick=document.getElementById('popup').style.display='none'>Cerrar</a>
                <input type='submit' name='add_cliente' value='Registrar' />
                </form>
            </div>";
    echo $tabla;
}
?>