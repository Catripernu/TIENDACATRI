<?php 
include("./sql/config.php");


if($_POST['agregar']){
  echo "NUEVO NOMBRE: ".$_POST['nombre'];
  $action = true;
}




$result = $conexion->query("SELECT * FROM users");

if(!$action){
foreach($result->fetchAll() as $user){
  echo "Nombre: ".$user['nombre']; ?>
  <a href="#" onclick="action(<?php echo $user['id']; ?>,'edit')">Editar</a><br>
<?php }} ?>



<!DOCTYPE html>
<html>
<head>
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
		}
	</style>
</head>
<body>
<a href="#" onclick="action(false,'add')">AGREGAR</a><br>  
	<div class="popup" id="popup">
		<div class="popup-content">
		</div>
	</div>
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
      xhr.open("GET", "prueba.php?edit&id=" + idUsuario, true);
    } else if (action == 'add') {
      xhr.open("GET", "add.php", true);
    }
    xhr.send();
}
</script>
</body>
</html>
