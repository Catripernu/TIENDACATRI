		
<?php
include("./sql/config.php");
if(isset($_GET['edit'])){
  $id = $_GET['id'];
  $user = $conexion->query("SELECT * FROM users WHERE id = $id")->fetch(PDO::FETCH_ASSOC);
  
  ?>
    <h2>EDITAR USUARIO <?=$id?></h2>
			<form action="#" method="POST">
				<label for="nombre">Nombre:</label>
				<input type="text" id="nombre" name="nombre" value="<?=$user['nombre']?>"><br><br>
				<label for="telefono">Telefono:</label>
				<input type="text" id="telefono" name="telefono" value="<?=$user['telefono']?>"><br><br>
				<label for="password">Contraseña:</label>
				<input type="password" id="password" name="password" value="<?=$user['password']?>"><br><br>
				<input type="submit" name="agregar" value="Agregar">
        <a href="#" onclick="document.getElementById('popup').style.display='none'">Cerrar</a>
			</form>
<?php } ?>