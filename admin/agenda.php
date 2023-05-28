<?php include("../php/includes_user.php");
include("../php/functions_ventas.php");
if(isset($_SESSION['loggedin']) && ($_SESSION['rol'] == 2 || $_SESSION['rol'] == 1)){
	$id = $_SESSION['id'];
	$link_buscador = "";
	if(isset($_POST['buscar']) && !empty($_POST['buscar'])){
		$cliente = $_POST['buscar'];
		$buscador = "AND (nombre_agenda LIKE '%$cliente%' OR apellido_agenda LIKE '%$cliente%')";
		$link_buscador = "?cliente=$cliente";
	}
	?>

<?php
// if(isset($_POST['add_cliente']) || isset($_GET['delet'])){
// 	if($_POST['add_cliente']){
// 		$nombre = $_POST['nombre'];
// 		$apellido = $_POST['apellido'];
// 		$domicilio = $_POST['domicilio'];
// 		$telefono = $_POST['telefono'];
	
// 		if(!empty($nombre) && !empty($apellido) && !empty($domicilio) && !empty($telefono)){
// 			$msj_opc = "AÑADIMOS CLIENTE: $nombre";
// 		} else {
// 			$msj_opc  = "Alguno o todos los campos vacios.";
// 		}
// 	}
	if($_GET['delet']){
		$id_delet = $_GET['delet'];
		$msj_opc = "vas a eliminar id: $id_delet";
	}
// }
?>

<style>
.popup {
	display: none;
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background-color: rgba(0,0,0,0.5);
	z-index: 9999;
}
.popup-content {
	position: absolute;
	top: 50%;
	left: 50%;
	transform: translate(-50%, -50%);
	background-color: #fff;
	padding: 20px;
	border-radius: 5px;
	text-align:center;
	& h3 {
		margin:0 0 10px 0;
	}
}
.formulario {
	display:flex;
	flex-direction: column;
	flex-wrap: wrap;
	padding: 0 20px;
	& .exit, input[type='submit'] {
		color:black;
		background: #e8e8e8;
		border:1px solid;
		padding: 2px 10px;
		border-radius:5px;
		font-size:12px;
		font-family: Arial, Helvetica, sans-serif;
		text-transform: uppercase;
		cursor:pointer;
	}
	& .exit:hover {border: 1px solid red; box-shadow: 1px 1px 3px red;}
	& input[type='submit']:hover {border: 1px solid green; box-shadow: 1px 1px 3px green;}
	& label {
		font-size:0.8rem; padding-right:10px;
	}
	& input {
		width:60%;
		margin:10px 0;
		padding:5px;
		border: 1px solid;
		border-radius:5px;
	}
}

@media (max-width:450px) {
	.popup-content{width:100%}
}
</style>

<body>
	AGENDA
<div id="contenido">
    <?php 
	$consulta_agenda = $conexion->query("SELECT * FROM agenda WHERE id_vendedor = $id");
	$pagina = (isset($_GET['pagina'])) ? $_GET['pagina'] : 1;

	echo (isset($msj_opc)) ? $msj_opc : "";
	?>
	<div class='filtro_fecha'>
		<div>
			<form action="#" method="post">
				<input class="input_buscar" type="text" name="buscar" placeholder="Cliente a buscar..">
			</form>
		</div>
		<div class="btn_opciones">
			<a href='#' class='btn_planilla' onclick='action(false,"add")' title='ADD CLIENTE'><i class='fa fa-user-plus fa-lg fa-fw'></i></a>
			<a href='../ticket/vendedor.php<?=$link_buscador?>' class='btn_planilla' title='EXPORTAR PDF'><i class='fa fa-print fa-lg fa-fw'></i></a>
      		<a href='../ticket/excel.php<?=$link_buscador?>' class='btn_planilla' title='EXPORTAR EXCEL'><i class='fa fa-file-excel fa-lg fa-fw'></i></a>
    	</div>
	</div>

	<?php if($consulta_agenda->rowCount()){
		$res = resultados_paginacion($agenda_resultados_por_pagina, $pagina, "agenda", (($buscador) ? "id_vendedor = '$id' $buscador" : "id_vendedor = '$id'"));
		$num_paginas = ceil($res['total_elementos']->rowCount() / $agenda_resultados_por_pagina);

		if($res['total_elementos']->rowCount()){
			$tabla .= "<div class='view_vendedor__ventas'>
							<div class='titulos b_superior'>
								<p>cliente</p>
								<p class='domicilio'>domicilio</p>
								<p class='telefono'>telefono</p>
								<p class='fecha'>fecha</p>
								<p>opciones</p>
							</div>";
			


			foreach($res['elementos'] as $row){
			// foreach($consulta_agenda as $row){
				$id = $row['id_agenda'];
				$cliente = $row['nombre_agenda']." ".$row['apellido_agenda'];
				$domicilio = $row['direccion_agenda'];
				$telefono = $row['telefono_agenda'];
				$fecha_registro = formato_fecha($row['fecha_agenda']);
				$tabla .= "<div class='contenido b_laterales_primary'>
							<p>$cliente</p>
							<p class='domicilio'>$domicilio</p>
							<p class='telefono'>$telefono<a href='https://wa.me/".$telefono."?text=Hola $cliente' target='_blank'><i class='fa fa-whatsapp fa-lg fa-fw'></i></a></p>
							<p class='fecha'>$fecha_registro</p>
							<p>
								<a href='#' title='EDITAR CLIENTE' onclick=action($id,'edit') ><i class='fa fa-user-edit fa-lg fa-fw'></i></a>
								<a href='?delet=$id' title='ELIMINAR CLIENTE'><i class='fa fa-times fa-lg fa-fw'></i></a>
							</p>
						</div>";
			}

			$tabla .= "<div class='footer b_inferior'></div></div>";
			
			echo $tabla;
		} else {
			echo "<p><b class='rojo'>NO TENES CLIENTES CON ESE NOMBRE O APELLIDO</b></p>";
		}
	} else {
		echo "<p><b class='rojo'>SIN CLIENTES REGISTRADOS</b></p>";
	}
	paginacion(false,$pagina,$num_paginas,false);
    ?>
</div>


<div class="popup" id="popup">
	<div class="popup-content"></div>
</div>


</body>
</html>



<script>
function action(idUsuario,action) {
    var popup = document.getElementById("popup");
    popup.style.display = "block";
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var popupContent = document.getElementsByClassName("popup-content")[0];
            popupContent.innerHTML = this.responseText;
        }
    };
    if(action == 'edit'){
      xhr.open("GET", "./agenda/edit_cliente.php?edit&id=" + idUsuario);
    } else if (action == 'add') {
      xhr.open("GET", "./agenda/add_cliente.php?add");
    }
    xhr.send();
}
</script>

<?php } else {header("location: ../index.php");} ?>