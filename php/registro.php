<?php 
require_once('../sql/config.php');

//DATOS RECIBIDOS
$usuario = trim($_POST['user']);
$nombre = trim(strtoupper(eliminar_acentos($_POST['nombre'])));
$apellido = trim(strtoupper(eliminar_acentos($_POST['apellido'])));
$password = trim($_POST['password']);
$conf_password = trim($_POST['conf_password']);
$domicilio = trim(eliminar_acentos($_POST['domicilio']));
$ciudad = trim($_POST['ciudad']);
$provincia = trim($_POST['provincia']);
$telefono = trim($_POST['user']);

//DEFAULT
$fecha_registro = date('Y-m-d H:i:s');

//VERIFICAMOS LONGITUD DEL USUARIO
if(strlen($usuario) == 10){
	//VERIFICAMOS SI EXISTE EL USUARIO EN LA BASE DE DATOS
    $verificarUsuarioBD = $conexion->prepare("SELECT username FROM users WHERE username = :usuario");
    $verificarUsuarioBD->execute([":usuario" => $usuario]);
	//PREGUNTAMOS DE SU EXISTENCIA
	if($verificarUsuarioBD->rowCount() == 0){
		//VERIFICAMOS QUE LA CONTRASEÑA SEA DE 8 O MAS DIGITOS
		if(strlen($password) >= 8){
			if($password === $conf_password){
				$password = password_hash($password, PASSWORD_DEFAULT);
				$sql = "INSERT INTO users (username, password, nombre, apellido, telefono, ciudad, domicilio, fechaRegistro, provincia, rol, verificado, permiso_password, cant_compras, saldoFavor) VALUES (:usuario, :password, :nombre, :apellido, :telefono, :ciudad, :domicilio, :fecha_registro, :provincia, 0, 0, 0, 0, 0)";
				$stmt = $conexion->prepare($sql);
			    $stmt->bindParam(":usuario", $usuario, PDO::PARAM_STR);
                $stmt->bindParam(":password", $password, PDO::PARAM_STR);
                $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
                $stmt->bindParam(":apellido", $apellido, PDO::PARAM_STR);
                $stmt->bindParam(":telefono", $telefono, PDO::PARAM_INT);
                $stmt->bindParam(":ciudad", $ciudad, PDO::PARAM_STR);
                $stmt->bindParam(":domicilio", $domicilio, PDO::PARAM_STR);
                $stmt->bindParam(":fecha_registro", $fecha_registro);
                $stmt->bindParam(":provincia", $provincia, PDO::PARAM_STR);
                $stmt->execute();
				echo 1;
			} else {
				echo "ERROR: LAS CONTRASEÑAS INGRESADAS NO COINCIDEN."; //CONTRASEÑAS NO COINCIDEN
			}
		} else {
			echo "ERROR: LA CONTRASEÑA DEBE TENER 8 O MAS DIGITOS DE LARGO."; //CONTRASEÑA NO CUENTA CON 8 O MAS DIGITOS DE LONGITUD.
		}
	} else {
		echo "ERROR: YA SE ENCUENTRA REGISTRADO ESE NUMERO DE TELEFONO."; // 1 = EXISTE NUMERO DE USUARIO REGISTRADO EN LA BD
	}
} else {
	echo "ERROR: EL USUARIO DEBE TENER 10 DIGITOS DE LONGITUD."; //USUARIO DEBE TENER 10 DIGITOS DE LONGITUD.
}

function eliminar_acentos($cadena){
		
		//Reemplazamos la A y a
		$cadena = str_replace(
		array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
		array('A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A'),
		$cadena
		);

		//Reemplazamos la E y e
		$cadena = str_replace(
		array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
		array('E', 'E', 'E', 'E', 'E', 'E', 'E', 'E'),
		$cadena );

		//Reemplazamos la I y i
		$cadena = str_replace(
		array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
		array('I', 'I', 'I', 'I', 'I', 'I', 'I', 'I'),
		$cadena );

		//Reemplazamos la O y o
		$cadena = str_replace(
		array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
		array('O', 'O', 'O', 'O', 'O', 'O', 'O', 'O'),
		$cadena );

		//Reemplazamos la U y u
		$cadena = str_replace(
		array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
		array('U', 'U', 'U', 'U', 'U', 'U', 'U', 'U'),
		$cadena );

		//Reemplazamos la N, n, C y c
		$cadena = str_replace(
		array('Ç', 'ç'),
		array('C', 'C'),
		$cadena
		);
		
		return $cadena;
	}
?>