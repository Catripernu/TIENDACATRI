<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<style>
    body{
        margin: 0;
        padding: 0;
    }
    .modal{
        position: fixed;
        width: 100%;
        height: 100vh;
        background: rgba(0, 0, 0, 0.81);
        display: block;
        text-align: center;
        padding: 100px 0 0 0;
    }
    .bodyModal {
        width: 300px;
        height: 50%;
        display: -webkit-inline-flex;
        display: -moz-inline-flex;
        display: -ms-inline-flex;
        display: -o-inline-flex;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        background: white;
    }
    .mas {
        font-size: 20px;
        text-decoration:none;
    }
</style>
<!-- MODAL DISEÑO -->
<div class="modal">
    <div class="bodyModal">
        <form action="" method="POST" name="form_add_product" id="form_add_product" onsubmit="event.preventDefault(); sendDataProduct();">
            <h1 class="accion">Agregar Producto</h1>
            <h2>MONITOR LCD 10</h2>
            <input type="text" placeholder="NOMBRE NUEVA SECCION"></input><br><br>
            <input type="submit" value="ACEPTAR" name="aceptar" id="aceptar"></input>
            <input type="submit" value="CANCELAR" onclick="closeModal();"></input>
        </form>
    </div>
</div>



<script>
$(document).ready(function(){
    $('.nuevaSeccion').click(function(e){
        e.preventDefault();
        var nueva = $(this).attr("add");
        var action = "nuevaSeccion";
        $('.accion').html(action);
        $('.modal').fadeIn();
    });
})
function closeModal(){
        $('.modal').fadeOut();
    }
</script>
<?php 
session_start();
include('../config.php');
if($_SESSION['rol'] == 1){

    if(isset($_POST['add'])){
        echo "HOla";
    } else { ?>
    
    <label>SECCION DE LOS PRODUCTOS</label><BR><BR>
    <select id="lista1" style="width: 50%;" name="seccion">
			<option value="0">ELIJA UNA SECCION</option>
		    <?php 
                $sql = "SELECT * FROM seccionCategoria group by nombre";
                $consulta = mysqli_query($conexion,$sql);

                while ($resultado = mysqli_fetch_array($consulta)) {
	                echo '<option value="'.$resultado['id'].'">'.$resultado['nombre'].'</option>';
                } 
            ?>
    </select>
    <a href="#" add="nuevaSeccion" class="mas nuevaSeccion">+</a>
    <BR><BR>
    <label>SECCION DE LOS PRODUCTOS</label><BR><BR>
    <div id="select2lista"></div>

    <script type="text/javascript">
        $(document).ready(function(){
            recargarLista();
            $('#lista1').change(function(){
                recargarLista();
            });
        })
    </script>
    <script type="text/javascript">
        function recargarLista(){
            $.ajax({
                type:"POST",
                url:"datos.php",
                data:"seccion=" + $('#lista1').val(),
                success:function(r){
                    $('#select2lista').html(r);
                }
            })
        }
    </script>

    <? }
    
} else {
    header("Location: ../index.php");
}
?>