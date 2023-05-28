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
<style type="text/css">
a{
  color:#322783;
  text-decoration: none;
}
a:hover{
	color: #8C51FF;
}
table {
  margin-top: 20px;
}
.bg-primary td{
  background: #322783;
  padding: 10px;
  font-weight: bold;
  color: white;
  font-style: italic;
}
td{
  text-align: center;
  padding: 5px;
  background: #f2f2f2;
}
input[type="text"]{
  width: 50%;
  padding: 10px;
  font-size: 16px;
  text-align: center;
}
input[type="submit"]{
  width: 100px;
  padding: 5px 0;
}
.seleccion:hover td{
  background: #cccccc;
}

.todas_las_ventas {
	margin-right: 10px;
	padding: 5px 10px;
	border: 1px solid #E5E5E5;
	background: #E5E5E5;
	cursor: default;
	color: black;
	font-size: 14px;
	font-style: normal;
	font-weight: normal;
}
.todas_las_ventas:hover {
	color: black;
}
</style>
</head>
<body>
<div id="contenido">
	<?php if (isset($_GET['vendedor'])) {
		include('./php/datos_vendedor.php');
		echo $tabla;
	} else { ?>
	<div class="buscador_vendedor"><input type="text" name="vendedor" id="vendedor" placeholder="Ingresar codigo, nombre, apellido o celular del vendedor..."></div>
	<div class="cuadro_top_vendedor"><?php include_once('./php/top_vendedores.php') ?></div>
	<section id="tabla_resultado"></section>
</div>
</body>
</html>
<?php 
}
} else {
	header("Location: ../index.php");
}
?>
<script type="text/javascript">
$(obtener_registros());
function obtener_registros(vendedor)
{
	$.ajax({
		url : './php/consulta_vendedores.php',
		type : 'POST',
		dataType : 'html',
		data : { vendedor: vendedor },
		})

	.done(function(resultado){
		$("#tabla_resultado").html(resultado);
		$("#tabla_resultado").css("display","block");
	})
}
$(document).on('keyup', '#vendedor', function()
{
	var valorBusqueda=$(this).val();
	if (valorBusqueda!="")
	{
		obtener_registros(valorBusqueda);
	}
	else
		{
			obtener_registros();
		}
});
</script>