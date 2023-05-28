<?php include_once("head.php"); ?>
<body>
	<?php 
	if (isset($_POST['enviar'])) {
		if (!empty($_POST['telefono'])) {
		if (strlen($_POST['telefono']) === 10){
			$numero = $_POST['telefono'];
			$consulta = $conexion->query("SELECT nombre, apellido FROM users WHERE telefono = $numero");
			if ($consulta->rowCount()) {
				$dato = $consulta->fetch(PDO::FETCH_ASSOC);
				$nombre = $dato['nombre'];
				$apellido = $dato['apellido'];
				$consulta_solicitud = $conexion->query("SELECT * FROM olvPassword WHERE telefono = '$numero'");
				if ($consulta_solicitud->rowCount()) { $error = 'YA REGISTRAMOS TU SOLICITUD, NOS ESTAMOS PONIENDO EN CONTACTO CON USTED.'; } else {
					$fecha = date('Y-m-d');
					$conexion -> query("INSERT INTO olvPassword(nombre,apellido,telefono,fecha_pedido) values ('$nombre','$apellido','$numero','$fecha')");
					$exito = strtoupper($nombre)." ".strtoupper($apellido.", RECIBIMOS TU SOLICITUD PARA RECUPERAR TU CLAVE, EN UNOS MINUTOS NOS COMUNICAREMOS CON USTED. GRACIAS");
				}
			} else { $error = 'NO EXISTE USUARIO REGISTRADO CON ESE NUMERO.'; }			
		} else { $error = 'EL NUMERO DEBE TENER 10 DIGITOS.'; }
	} else { $error = 'NO INGRESO NINGUN NUMERO DE TELEFONO.'; }
	}
	?>
	<div id="contenido">
		<div class="olv_password">
			<form id="form_olvPassword" action="" method="post">
				<h4>INGRESA TU NUMERO DE CELULAR</h4>
				<input type="text" maxlength="10" name="telefono" />
				<h5>AVISO: El numero que ingrese debe ser el mismo que registro en su cuenta.</h5>
				<h4>
				<p><?php echo $error = ($error) ? "<div class='rojo'>".$error."</div>" : "<div class='verde'>".$exito."</div>"; ?></p>
				</h4>
				<input type="submit" class="btn_atras" name="enviar" value="Recuperar Contraseña" />
			</form>
		</div>
	</div>
</body>
</html>