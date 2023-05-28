<?php
session_start();
include('../config.php');
include('./datosRelevantes.php');
$fecha = date('Y-m-d');





if (isset($_SESSION["rol"]) == 1) { ?>
	<!DOCTYPE html>
	<html>
	<?php include ('../links.php'); ?>
	<head>
	<title><?php echo $titulo; ?></title>
	<div id="fondo_head"> 
	<?php include('../header.php'); ?>
	<?php include('../menu/header.php'); ?>
	</div>
	</head>
	<body>
		<div id="contenido">
			<div id="cuentacorriente">
				<div class="opciones">
					<form method="post" action="#">
					  <div class="agregar">


					  	<button type="button" name="agregar" id="agregar" class="btn btn-success" data-toggle="modal" data-target="#loginModal">Login</button>


					  	<!-- <a href="./cuentacorriente.php" name="agregar" id="agregar" data-toggle="modal" data-target="#loginModal" class="fas fa-file-medical"></a>  -->


					  	<input class="buscador" type="text" name="buscador"> <input type="submit" value="BUSCAR" /></div>
					  <div class="filtrar">FILTRAR</div>
					</form>
				</div>
				<div class="tabla">
				  <div class="codigo">CODIGO</div>
				  <div class="fecha">FECHA</div>
				  <div class="importe">IMPORTE</div>
				  <div class="proveedor">PROVEEDOR</div>
				  <div class="estado">ESTADO</div>
				  <div class="opcion">OPCION</div>
				</div>
				<?php 
				if ($_POST) {
					if ($_POST['buscador']) {
						// INCLUIR php/buscador
						echo "PALABRA: ".$_POST['buscador'];
					}
				} else {
					$consulta = $conexion->query("SELECT * FROM cuentacorriente ORDER BY fecha DESC");
					while ($datos = $consulta->fetch_assoc()) { 
						$codigo = $datos['codigo'];
						$fecha = $datos['fecha'];
						$importe = $datos['importe'];
						$proveedor = $datos['proveedor'];
						if ($datos['estado'] == 0) {
							$estado = "DEUDA";
						} else {
							$estado = "PAGADO";
						}
					?>
					<div class="tabla_resultados">  
					    <div class="codigo">#<?php echo $codigo; ?></div>
					    <div class="fecha"><?php echo $fecha; ?></div>
					    <div class="importe"><?php echo $importe; ?></div>
					    <div class="proveedor"><?php echo $proveedor; ?></div>
					    <div class="estado"><?php echo $estado; ?></div>
					    <div class="opcion">X / tilde</div>
					</div>  
				<?php }} ?>
			</div>
		</div>
	</body>
	</html>
<?php } else {
	header("Location:../index.php");
} ?>



<div class="loginModal" id="loginModal" style="display:none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Agregar compra</h4>
			</div>
			<div class="modal-body">
				<label>Codigo Compra</label>
				<input type="text" name="codigo" id="codigo" class="form-control" >
				<label>Fecha</label>
				<input type="date" name="fecha" id="fecha" class="form-control" value="<?php echo $fecha ?>" >
				<label>Proveedor</label>
				<input type="text" name="proveedor" id="proveedor" class="form-control" >
				<label>Importe</label>
				<input type="text" name="importe" id="importe" class="form-control" >
				<button type="button" name="add_registro" id="add_registro" class="btn">Agregar cuenta</button>
			</div>
		</div>
	</div>
</div>


<script>
	$(document).ready(function(){
		$('#add_registro').click(function(){
			var codigo = $('#codigo').val();
			var fecha = $('#fecha').val();
			var proveedor = $('#proveedor').val();
			var importe = $('#importe').val();
			alert(importe);
			if (codigo != '' && importe != '') {
				$.ajax({
					url:"./php/add_cuentacorriente.php",
					method:"POST",
					data:{codigo:codigo,fecha:fecha,proveedor:proveedor,importe:importe},
					success:function(data){
						if (data == 1) {
							$('#loginModal').hide();
							location.reload();
						}
					}
				})
			} else {
				alert("Codigo o Importe vacio/s, por favor rellenar.");
			}
		})
	})
</script>