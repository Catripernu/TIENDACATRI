<?php include("../php/includes_user.php");
include("../php/functions_users.php");
if(isset($_SESSION['loggedin']) && ($_SESSION['rol'] == 0 || $_SESSION['rol'] == 2)){?>
<body>
<div id="contenido">
<?php 
$formulario = true;
$user = user($_SESSION['id'])->fetch(PDO::FETCH_ASSOC);
	if(isset($_POST['modificar'])){
	$domicilio = eliminar_acentos($_POST['domicilio']);
	$telefono = $_POST['telefono'];
	$id = $_SESSION['id'];
	if($user['permiso_password']){
		$new_password = (isset($_POST['password'])) ? $_POST['password'] : null;
		if(!empty($new_password)){
			if(strlen(trim($new_password)) >= 8){
				$password = password_hash($new_password, PASSWORD_DEFAULT);
				$conexion->query("UPDATE users SET password = '$password', permiso_password = 0 WHERE id='$id'");
				echo "<p><b class='verde'>SE ACTUALIZO LA NUEVA CONTRASEÑA</b></p>";
				echo "<meta http-equiv='refresh' content='3;URL=./compras.php' />";
				$formulario = false;
			} else {
				$error = "<p><b class=rojo>LA CONTRASEÑA DEBE TENER UNA LONGITUD DE 8 O MAS CARACTERES</b></p>";
			}
		} else {
			$error .= (empty($new_password)) ? "<p><b class=rojo>NUEVA CONTRASEÑA VACIA</b></p>" : "";
		}
	} else {
		if(!empty($domicilio) && !empty($telefono)){
				if($domicilio == $user['domicilio'] && $telefono == $user['telefono']){
					$error = "<p><b class=rojo>NO MODIFICASTE ALGUN DATO</b></p>";
				} else {
					$conexion->query("UPDATE users SET domicilio='$domicilio', telefono='$telefono' WHERE id = '$id'");
					echo "<p><b class='verde'>DATOS MODIFICADOS EXITOSAMENTE</b></p>";
					echo "<meta http-equiv='refresh' content='3;URL=./compras.php' />";
					$formulario = false;
				}
			} else {
				$error .= (empty($domicilio)) ? "<p><b class=rojo>DOMICILIO VACIO</b></p>" : "";
				$error .= (empty($telefono)) ? "<p><b class=rojo>TELEFONO VACIO</b></p>" : "";
			}
	}
}
	echo $error;
	if($formulario) {
?>
<form action="#" method="post">
	<div class="mod_datos">
		<div class="formulario">			
			<p>DOMICILIO</p><input type="text" name="domicilio" id="domicilio" required value="<?php echo $user['domicilio']; ?>" />
			<p>TELEFONO</p><input type="text" maxlength="10" id="telefono" required onkeypress="return validaNumericos(event)" name="telefono" value="<?php echo $user['telefono']; ?>" />
			<?php if ($user['permiso_password'] == 1) { ?>
		 	<p>NUEVA CONTRASEÑA</p><input type="password" name="password" id="password" required onclick="limpia(this)" value="" />
			<?php $aviso = "<h6>Requisito minimo: Longitud de al menos 8 caracteres.</h6>"; } ?>
		</div>
		<?=$aviso?>
		<input class="btn_finish btn_morado" type="submit" name="modificar" id="modificar" value="MODIFICAR">
	</div>
</form>
<?php } ?>
</div>	
</body>
</html>


<?php } else {header("location: ../index.php");} ?>