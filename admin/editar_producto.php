<?php 
session_start();
include('../config.php');
include('datosRelevantes.php');
if (isset($_SESSION['rol'])) {
	if($_SESSION['rol'] === 1){
?>
<!DOCTYPE html>
<html>
<?php include ('../links.php'); ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://djdistribuciones.com.ar/js/js.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<head>
<title><?php echo $titulo; ?></title>
<div id="fondo_head"> 
<?php include('../header.php'); ?>
<?php include('../menu/header.php'); ?>
</div>
</head>
<body>
	<?php 
if(isset($_GET['id'])){

	$id = $_GET['id'];
	include('../sql/query.php');


	$categoriaID = $p_datos['categoria'];
	$consultaNombreSeccion = $conexion->query("SELECT nombre FROM seccionCategoria WHERE id = $categoriaID");
	$respuestaNombreSeccion = $consultaNombreSeccion->fetch_array();
	$categoria = $respuestaNombreSeccion['nombre'];

	// $nombreCategoria = $conexion->query("SELECT * FROM categorias WHERE id = '".$p_datos['categoria']."'");
	// $categoria = $nombreCategoria->fetch_assoc();

	if(isset($_POST['actualizar'])){
		$id = $_POST['id'];
		$nombre = strtoupper($_POST['nombre']);
		$precio_c = $_POST['precio_c'];
		$precio_v = $_POST['precio_v'];
		$precio_o = $_POST['precio_o'];
		$stock = $_POST['stock'];
		$f_vencimiento = $_POST['f_vencimiento'];
		$categoria = $_POST['categoria'];
		$palabrasClaves = $_POST['palabrasClaves'];
		$grupoMercaderia = $_POST['grupoMercaderia'];
		if (isset($_POST['aGrupo'])) {
		    if(!empty($grupoMercaderia)){
		        $aGrupo = 1;
			mysqli_query($conexion,"UPDATE p_precios SET precio_c='$precio_c',precio_v='$precio_v' WHERE grupo = '$grupoMercaderia'");
		    }
		}
		if (isset($_POST['oferta'])) {
			$oferta = 1;
		} else {
			$oferta = 0;
		}
		if (isset($_POST['dosxuno'])) {
			$dosxuno = 1;
		} else {
			$dosxuno = 0;
		}
		if (isset($_POST['precios'])) {
			if ($precio_c >= 100) {
				$precio_v = ($precio_c*$porInc1000)+$precio_c;
			} else {
				$precio_v = ($precio_c*$porInc100)+$precio_c;
			}
		}
		date_default_timezone_set('America/Argentina/Buenos_Aires');
		if (isset($_POST['fecha'])) {
			$fecha = date('Y-m-d H:i:s');
			$conexion->query("UPDATE p_infoweb SET oferta = '".$oferta."', dosxuno = '".$dosxuno."', fecha_ultimo_precio = '".$fecha."' WHERE id_producto = '".$id."'");
		} else {
			mysqli_query($conexion,"UPDATE p_infoweb SET oferta = '$oferta', dosxuno = '$dosxuno' WHERE id_producto = '$id'");
		}

		mysqli_query($conexion,"UPDATE productos SET nombre = '$nombre' WHERE id = '$id'");
		mysqli_query($conexion,"UPDATE p_precios SET precio_c='$precio_c',precio_v='$precio_v',precio_o='$precio_o',grupo='$grupoMercaderia' WHERE id_producto = '$id'");
		mysqli_query($conexion,"UPDATE p_datos SET stock='$stock',f_vencimiento='$f_vencimiento', categoria='$categoria', palabrasClaves='$palabrasClaves' WHERE id_producto = '$id'");
		

		if($_FILES['imagen']['name']){
			$nameimagen = $_FILES['imagen']['name'];
			$tmpimagen = $_FILES['imagen']['tmp_name'];
			$urlnueva = "../images/productos/ompick_".$id.".webp";
			if(is_uploaded_file($tmpimagen)){
				copy($tmpimagen, $urlnueva);
				echo '<div align=center style="margin-top:20px;"><b>PRODUCTO ACTUALIZADO CON EXITO (CON IMAGEN)</b></div>';
			} else {
				echo '<div align=center style="margin-top:20px;"><b>ERROR AL CARGAR LA IMAGEN</b></div>';
			}
		} else {
			echo '<div align=center style="margin-top:20px;"><b>PRODUCTO ACTUALIZADO CON EXITO (SIN IMAGEN)</b></div>';
		}	
		if (isset($_POST['p'])) {
				echo "<meta http-equiv='refresh' content='2;URL=precios.php' />";
			}	
}  else { ?>
	<style type="text/css">
		#tablaEditar input[type="text"], #tablaEditar input[type="date"]{
			width: 100%;
			padding: 10px 0 10px 10px;
			margin:5px 0;
		}
		#tablaEditar input[type="file"]{
			margin:10px 0;
			font-size: 17px;
		}
		#tablaEditar input[type="checkbox"]{
			margin:10px 20px;
		}
		#tablaEditar button[type="submit"]{
			margin:10px 0;
			padding:20px;
			width: 90%;
			font-size: 20px;
			background:#ffe6ff;
			border-radius: 10px;
			border:2px solid black;
			cursor: pointer;
		}
		#tablaEditar button[type="submit"]:hover{
			background: #800080;
			color: white;
		}
		#tablaEditar select{
			padding:10px;
		}
		#tablaEditar p{
  				position: absolute;
  				margin-left:16%;
  				padding-top: 17px;
			}
		@media (max-width: 700px) {
			#tablaEditar p{
  				margin-left:82%;
			}
		}
	</style>
	<div id="contenido">
		<img width="150" height="150" src="../images/productos/ompick_<?php echo $id ?>.webp">
	<form method="post" id="tablaEditar" enctype="multipart/form-data">
<table align="center">
	<tr>
		<?php if ($p_infoweb['fecha_ultimo_precio'] != "0000-00-00") {
			$fechaPrecio = "<font color='green' size='+2'><b>".$p_infoweb['fecha_ultimo_precio']."</b></font>";
		} else {
			$fechaPrecio = "<font color='red' size='+2'><b>".$p_infoweb['fecha_ultimo_precio']."</b></font>";
		} ?>
		<td>FECHA ACTUALIZADA: <?php echo $fechaPrecio; ?></td>
	</tr>
	<tr>
		<td><p>N.P</p><input type="text" name="nombre" value="<?php echo $producto["nombre"] ?>"></td>
	</tr>
	<tr>
		<td><p>%</p> <input type="text" id="porcentaje" name="porcentaje" value=""></td>
	</tr>
	<tr>
		<td><p>P.C</p><input type="text" name="precio_c" class="monto" onkeyup="precioVenta();" value="<?php echo $p_precios['precio_c'] ?>" onclick="limpia(this)"></td>
	</tr>
	<tr>
		<td><p>P.V</p><input type="text" name="precio_v" id="precio_v" value="<?php echo $p_precios['precio_v'] ?>"></td>
	</tr>
	<tr>
		<td><p>P.O</p><input type="text" name="precio_o" value="<?php echo $p_precios['precio_o'] ?>"></td>
	</tr>
	<tr>
		<td><p>S.P</p><input type="text" name="stock" value="<?php echo $p_datos['stock'] ?>"></td>
	</tr>
	<tr>
		<td><p>V.P</p><input type="date" name="f_vencimiento" value="<?php echo $p_datos['f_vencimiento'] ?>"></td>
	</tr>
	<tr>
		<td><select name="categoria"> 
			<option value="<?php echo $categoriaID; ?>" selected><?php echo $categoria; ?></option>
		<?php include 'categorias.php'; ?>
    	</select></td>
	</tr>
	<tr>
		<td><p>P.C</p><input type="text" name="palabrasClaves" placeholder="Palabras Claves" value="<?php echo $p_datos['palabrasClaves'] ?>" ></td>
	</tr>
	<tr>
		<td><p>GRUPO</p><input type="text" id="grupoMercaderia" name="grupoMercaderia" placeholder="Grupo Mercaderia" value="<?php echo $p_precios['grupo'] ?>" /></td>
	</tr>
	<tr>
		<td>
			Precio Grupo<input type="checkbox" name="aGrupo">Si / No
		</td>
	</tr>
	<tr>
		<td><input type="file" name="imagen" id="imagen"></td>
	</tr>
	<tr>
		<td>
			<?php 
			if($p_infoweb['oferta'] == 1){
				echo '(Oferta)<input type="checkbox" name="oferta" checked>Si (marcado) / No';
			} else {
				echo '(Oferta)<input type="checkbox" name="oferta">Si (marcado) / No';
			}
			?>
		</td>
	</tr>
	<tr>
		<td>
			(Fecha Precio)<input type="checkbox" name="fecha" checked>Si (marcado) / No
		</td>
	</tr>
	<tr>
		<td>
			<?php 
			if($p_infoweb['dosxuno'] == 1){
				echo '(2x1)<input type="checkbox" name="dosxuno" checked>Si (marcado) / No';
			} else {
				echo '(2x1)<input type="checkbox" name="dosxuno">Si (marcado) / No';
			}
			?>
		</td>
	</tr>
	<tr>
		<td align="center"><button type="submit" name="actualizar">Actualizar Producto</button></td>
	</tr>
	<tr>
		<td align="center"><input type="hidden" name="id" value="<?php echo $producto['id'] ?>"></td>
	</tr>
</table>
<?php 
if (isset($_GET['p'])) { ?>
	<input type="hidden" name="p" value="on">
<?php } ?>
</form>
</div>

<script type="text/javascript">
// function precioVenta() {
//   var total = 0;
//   $(".monto").each(function() {
//     if (isNaN(parseFloat($(this).val()))) {
//       total += 0;
//     } else {
//     	if (parseFloat($(this).val()) > 100) {
//     		total += (parseFloat($(this).val()) * <?php echo $porInc1000; ?>) + parseFloat($(this).val());
//     	} else {
//     		total += (parseFloat($(this).val()) * <?php echo $porInc100; ?>) + parseFloat($(this).val());
//     	}      
//     }
//   });
//   //alert(total);
//   total = parseFloat(total).toFixed(2);
//   document.getElementById('spTotal').value = total;
// }
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
$(document).ready(function(){
		$('#grupoMercaderia').typeahead({
			source: function(query, result){
				$.ajax({
					url:"accion.php",
					method:"POST",
					data:{query:query},
					dataType:"json",
					success:function(data){
						result($.map(data, function(item){
							return item;
						}))
					}
				})
			}
		})
	});
</script>
<?php
} }
?>	
</body>
</html>
<?php } else {
	header("Location: ../index.php");
}
} else {
header("Location: ../index.php");
}
?>