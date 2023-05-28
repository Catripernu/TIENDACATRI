<?php 
session_start();
include('../config.php');
include('datosRelevantes.php');
include "./php/funciones_agenda.php";
if ($_SESSION['rol'] > 0) {
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
</head>
<style type="text/css">
tr:nth-child(odd) {
	background: #4d4d4d;
}
tr:nth-child(even) {
	background: #808080;
}
.margin10 {
	margin-bottom: 10px;
}
.add_cliente {
	background: #333333;
	padding: 5px 20px;
	border: 1px solid black;
	border-radius: 4px;
	color: white;
	cursor: pointer;
}
.add_cliente:active {background: #808080;}
.new:hover {background: #004d00;}
.delet:hover {background: #4d0000;}
.edit:hover {background: #660066;}
.modal_agenda{
	display: flex;
	justify-content: center;
	align-items: center;
	visibility: hidden;
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 100vh;
	background-color: rgba(0, 0, 0, 0.4);
}
.isVisible {visibility: visible;}
.message {
	padding-bottom: 10px;
	font-size: 16px;
	font-weight: bold;
}
.error {
	color: red;
}
.success {
	color: green;
}
#tablaCompras table thead th a i {
	font-size: 1.5rem;
	padding: 5px 10px;
}
#tablaCompras #buscador_agenda input {
	min-width: 70%;
	padding: 5px;
	text-align: center;
	font-size: 1rem;
	background: #404040;
	color: white;
	border: none;
	border-radius: 10px;
	float: left;
	margin-left: 20px;
}
</style>
<body>

<?php 
$message = "";
if (isset($_POST['registrar_cliente'])) {
	$telefono = $_POST['telefono_agenda'];
	$id_vendedor = $_SESSION['id'];
	$rol = $_SESSION['rol'];
	$existencia = $GLOBALS['conexion']->query("SELECT telefono_agenda FROM agenda WHERE telefono_agenda = $telefono")->num_rows;
	if($existencia){
		$message = "<b class='error'>YA EXISTE UN REGISTRO CON ESTE NUMERO DE TELEFONO.</b>";
	} else {
		$message = registrarClienteAgenda($_POST['nombre_agenda'],$_POST['apellido_agenda'],$_POST['direccion_agenda'],$telefono,date("Y-m-d"),$id_vendedor,$rol);
	}
	header("Refresh:2");
}
if (isset($_POST['actualizar_cliente'])) {
	$message = actualizarClienteAgenda($_POST['id'],$_POST['nombre_agenda'],$_POST['apellido_agenda'],$_POST['direccion_agenda'],$_POST['telefono_agenda'],date("Y-m-d"),$_SESSION['id'],$_SESSION['rol']);
	header("Refresh:2");
}

if(isset($_GET['clienteABuscar'])){
	$clienteABuscar = $_GET['clienteABuscar'];
	$print = "cliente=".$clienteABuscar;
	$excel = "cliente=".$clienteABuscar;
} else {
	$print = "";
	$excel = "";
}


?> 

<div id="contenido">
	<div><input type="button" class="add_cliente margin10 new" data-add name="add_cliente" value="Añadir Cliente"></div>
	<div class="message"><?=$message?></div>
	<?php 
	$id = $_SESSION['id'];
	$total_query_vendedor = $conexion->query("SELECT * FROM agenda WHERE id_vendedor = $id")->num_rows;
	if($total_query_vendedor || $_SESSION['rol'] == 1){
	?>
	<div id ="tablaCompras">	
		<table align="center" width="100%" cellspacing="0" cellpadding="0" >
			<thead>
				<th class ="Textmayus tituloCompras" colspan="7"><h2>PADRON CLIENTES</h2></th>
			</thead>	
			<thead id="buscador_agenda">
				<form action="" method="get">
					<th colspan="6"><input type="text" placeholder="INGRESE CLIENTE A BUSCAR" name="clienteABuscar"></th>
				</form>
				<th>
					<a target="_blank" href="../ticket/agenda.php?<?php echo $print ?>"><i class="fas fa-print"></i></a>
					<a target="_blank" title="Exportar a Excel" href="./excel.php?agenda&<?php echo $excel ?>"><i class="fas fa-file-excel"></i></a>
				</th>
			</thead>
			<?=vistas_registros_agendas($_SESSION['rol'],$_SESSION['id'],$_GET['clienteABuscar'])?>	
		</table>
	</div>
	<?php } else {
		echo "<b class='error'>NO TIENES CLIENTES REGISTRADOS.</b>";
	} ?>
</div>
</body>
</html>

<style type="text/css">
.modalAddClient__cuadro{
  background: #ccc;
  min-width: 500px;
  min-height: 350px;
  border-radius: 15px 15px 0 0;
  justify-self: center;
  }

.modalAddClient__opciones{
  display: flex;
  justify-content: space-between;
  padding: 15px;
  background: #322783;
  color: white;
  font-size: 18px;
  border-radius: 10px 10px 0 0;
}
.modalAddClient__close input{
  background: none;
  border: none;
  color: white;
  cursor: pointer;
  font-weight: bold;
}
.modalAddClient__conteiner {
	text-align: center;
	margin: 20px 0;
}
.modalAddClient__conteiner input, .modalAddClient__conteiner label {
	border: none;
	padding: 10px;
	margin: 10px;
	font-weight: bold;
}
.modalAddClient__conteiner input[type="submit"]{
	padding: 10px 50px;
	min-width: 80%;
	background: #c9c4ed;
	border: 1px solid #4b3bc4;
	cursor: pointer;
	text-transform: uppercase;
}
.modalAddClient__conteiner input[type="submit"]:hover{
	color: white;
	background: #4b3bc4;
	transition: all 1s ease;
}
</style>

<section id="modalAddClient" class="modal_agenda">
	<div class="modalAddClient__cuadro">
		<div class="modalAddClient__opciones">
			<div class="modalAddClient__title modalAddClient__centinela"></div>
			<div class="modalAddClient__close"><input type="button" data-close value="X"></div>
		</div>
		<div class="modalAddClient__conteiner">
			<div id="modal__add"><?php include "./models/agregar_agenda.php" ?></div>
			<div id="modal__edit"><?php include "./models/edit_agenda.php" ?></div>			
			<div id="modal__del"><?php include "./models/delet_agenda.php" ?></div>
		</div>
	</div>	
</section>


<script type="text/javascript">
	const modalAddClient = document.querySelectorAll("[data-add]");
	const modalEditClient = document.querySelectorAll("[data-edit]");
	const modalDeleteClient = document.querySelectorAll("[data-delet]");
	const closeModal = document.querySelectorAll("[data-close]");
	const isVisible = "isVisible";
	$(modalAddClient).click(function(e){
		$(".modalAddClient__centinela").html("Agregar Cliente");
	    document.getElementById("modal__add").style.display = "block";
	    document.getElementById("modal__edit").style.display = "none";
	    document.getElementById("modal__del").style.display = "none";
	    document.getElementById("modalAddClient").classList.add(isVisible);
	  })
	$(modalEditClient).click(function(e){
		$("#modal__id").val($(this).data("id"));
		$("#nombre_agenda").val($(this).data("nombre"));
		$("#apellido_agenda").val($(this).data("apellido"));
		$("#direccion_agenda").val($(this).data("direccion"));
		$("#telefono_agenda").val($(this).data("telefono"));
		$(".modalAddClient__centinela").html("Editar Cliente ");
	    document.getElementById("modal__add").style.display = "none";
	    document.getElementById("modal__edit").style.display = "block";
	    document.getElementById("modal__del").style.display = "none";
	    document.getElementById("modalAddClient").classList.add(isVisible);
	  })
	$(modalDeleteClient).click(function(e){
		var id = $(this).data("id");
	    event.preventDefault();
	    var mensaje = confirm("¿Eliminar cliente #" + id + "?");
	    if (mensaje) {
	      $.ajax({
	      method:"GET",  
	      url:'./models/eliminar_agenda.php?id='+id,
	      success:function(){   
	        alert("CLIENTE ELIMINADO EXITOSAMENTE");
	        window.location.reload();
	      } 
	      })
	  	}
	  })
	$(closeModal).click(function(e){
		document.getElementById("modalAddClient").classList.remove(isVisible);
	})
	document.addEventListener("keyup", e => {
    // if we press the ESC
    if (e.key == "Escape" && document.querySelector("#modalAddClient")) {
      document.querySelector("#modalAddClient").classList.remove(isVisible);
    }
  });
</script>
<?php 
} else {
	header("Location: ../index.php");
}
?>