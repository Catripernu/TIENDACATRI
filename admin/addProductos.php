<?php 
session_start();
include('../config.php');
include('datosRelevantes.php');
if ($_SESSION['rol'] == 1) {
?>
<!DOCTYPE html>
<html>
<?php include ('../links.php'); ?>
<head>
<title><?php echo $titulo; ?></title>
<div id="fondo_head"> 
<?php include('../header.php'); ?>
<?php include('../menu/header.php'); ?>
</div>
<script type="text/javascript">
function precioVenta() {
	var porcentaje = document.getElementById('porcentaje').value;
	var total = 0;
	$(".monto").each(function() {
	if (isNaN(parseInt(porcentaje))) {
		total += parseFloat($(this).val());
	} else {
		total += ((parseFloat($(this).val()) * porcentaje) / 100) + parseFloat($(this).val());
	}
	});
  total = parseFloat(total).toFixed(2);
  document.getElementById('precio_v').value = total;
}
</script>
</head>
<body>
	<?php 
// PROGRAMACION PARA AGREGAR EL PRODUCTO
if(!empty($_POST['nombre'])){

	if (isset($_POST['f_porcentaje'])) {
		$_SESSION['porcentaje'] = $_POST['porcentaje'];
	} else {
		unset($_SESSION['porcentaje']);
	}


	$nombre = mysqli_real_escape_string($conexion,$_POST['nombre']);
	$nombre = str_replace(
        array('á','é','í','ó','ú','Á','É','Í','Ó','Ú'),
        array('a','e','i','o','u','A','E','I','O','U'),
        $nombre
    );
  $nombre = strtoupper($nombre);
	$precio_c = mysqli_real_escape_string($conexion,$_POST['precio_c']);
	$precio_v = mysqli_real_escape_string($conexion,$_POST['precio_v']);
	$stock = mysqli_real_escape_string($conexion,$_POST['stock']);
	$f_vencimiento = $_POST['f_vencimiento'];
	$categoria = mysqli_real_escape_string($conexion,$_POST['categoria']);
	$palabrasClaves = mysqli_real_escape_string($conexion,$_POST['palabrasClaves']);
	if(isset($_POST['dosxuno'])){
		$dosxuno = 1;
	} else {
		$dosxuno = 0;
	}

	if (isset($_POST['fecha'])) {
		$fecha = date('Y-m-d H:i:s');
	} else {
		$fecha = "0000-00-00";
	}

	$sql=mysqli_query($conexion,"SELECT id FROM productos WHERE nombre='$nombre'");
	if($row=mysqli_fetch_array($sql)){
		echo 'NO SE PERMITEN DATOS DUPLICADOS EN LA BASE DE DATOS<BR>';
	} else {
		//REGISTRAMOS EL PRODUCTO
		mysqli_query($conexion,"INSERT INTO productos (nombre) VALUES ('$nombre')");
		$ss = mysqli_query($conexion,"SELECT  MAX(id) as id_maximo FROM productos");
		if($rr=mysqli_fetch_array($ss)){
			$id_maximo=$rr['id_maximo'];
		}
		//REGISTRAMOS LOS PRECIOS
		mysqli_query($conexion,"INSERT INTO p_precios (id_producto,precio_c,precio_v,precio_o,grupo) VALUES ('$id_maximo','$precio_c','$precio_v','0','')");
		//REGISTRAMOS LOS DATOS
		mysqli_query($conexion,"INSERT INTO p_datos (id_producto,stock,f_vencimiento,categoria,palabrasClaves) VALUES ('$id_maximo','$stock','$f_vencimiento','$categoria','$palabrasClaves')");
		//REGISTRAMOS INFOWEB
		mysqli_query($conexion,"INSERT INTO p_infoweb (id_producto,oferta,venta_total,dosxuno,fecha_ultimo_precio) VALUES ('$id_maximo','0','0','$dosxuno','$fecha')");

		$tmpimagen = $_FILES['imagen']['tmp_name'];
		$urlnueva = "../images/productos/ompick_".$id_maximo.".webp";
		if(is_uploaded_file($tmpimagen)){
			copy($tmpimagen, $urlnueva);
			echo '<div class="exitoADDPRODUCTO">PRODUCTO CARGADO CON EXITO</div>';
		} else {
			echo '<div class="errorADDPRODUCTO">ERROR AL CARGAR LA IMAGEN</div>';
		}
	}
}
// FIN PROGRAMACION DE AGREGAR EL PRODUCTO

?>
<form style="margin-top: 15px;" action="" method="post" enctype="multipart/form-data">
<table align="center">
	<tr>
		<td><strong>Nombre Producto</strong></td>
		<td><input type="text" name="nombre" id="nombre" style="text-transform: uppercase;" required></td>
	</tr>
	<tr>
		<td colspan="2"><b>Porcentaje</b> <input type="text" id="porcentaje" name="porcentaje" value="<?php echo $_SESSION['porcentaje']; ?>">
			<?php 
			if (isset($_SESSION['porcentaje'])) {
				echo '<input type="checkbox" name="f_porcentaje" checked>fijar</td>';
			} else {
				echo '<input type="checkbox" name="f_porcentaje">fijar</td>';
			}
			?>
	</tr>
	<tr>
		<td><strong>Precio Costo</strong></td>
		<td><input type="text" name="precio_c" class="monto" value="" onkeyup="precioVenta();" required></td>
	</tr>
	<tr>
		<td><strong>Precio Venta</strong></td>
		<td><input type="text" name="precio_v" id="precio_v" value="" required></td>
	</tr>
	<tr>
		<td><strong>Stock</strong></td>
		<td><input type="text" name="stock" value="1000" required></td>
	</tr>
	<tr>
		<td><strong>Fecha Vencimiento</strong></td>
		<td><input type="date" name="f_vencimiento" required></td>
	</tr>
	<tr>
		<td><strong>Categoria</strong></td>
		<td><select style="width: 80%;" name="categoria">
			<option value=""></option>
		<?php include 'categorias.php'; ?>
    	</select></td>
	</tr>
	<tr>
		<td><strong>Palabras Clave</strong></td>
		<td><input type="text" name="palabrasClaves" value=""></td>
	</tr>
	<tr>
		<td><strong>Foto <br> Tamaño: 255x255px</strong></td>
		<td><input type="file" name="imagen" id="imagen"></td>
	</tr>
	<tr>
		<td colspan="2" align="center" style="padding:10px;"><input type="checkbox" checked name="fecha"> FECHA REGISTRO</td>
	</tr>
	<tr>
		<td colspan="2" align="center" style="padding:0 0 10px 0;"><input type="checkbox" name="dosxuno"> OFERTA: 2x1</td>
	</tr>
	<tr>
		<td colspan="2" align="center"><button type="submit">Agregar Producto</button></td>
	</tr>

</table>
</form>
<br>
<br>
<br>
<table id="ultimosAgregados" align="center">
	<thead>
		<th colspan="2">ULTIMOS PRODUCTOS AGREGADOS</th>
	</thead>
	<?php 
	$query = mysqli_query($conexion,"SELECT * FROM productos ORDER BY id DESC LIMIT 15 ");
	while ($datos = mysqli_fetch_array($query)) {
		$id = $datos['id'];
		$producto = mysqli_fetch_array(mysqli_query($conexion,"SELECT precio_v FROM p_precios WHERE id_producto = '$id'"));
	?>
	<tr>
		<td><?php echo $datos['nombre']; ?></td>
		<td><?php echo $producto['precio_v']; ?></td>
	</tr>
	<?php } ?>
</table>
</body>
</html>
<?php 
} else {
header("Location: ../index.php");
}
?>