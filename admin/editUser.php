<?php 
session_start();
include('../config.php');
include('datosRelevantes.php');
if ($_SESSION['rol'] == 1) {
?>
<!DOCTYPE html>
<html>
<?php include ('../links.php'); ?>
<script src="peticiones.js"></script>
<head>
<title><?php echo $titulo; ?></title>
<style type="text/css">
a{
  color:#B01F00;
  text-decoration: none;
}
table {
  margin-top: 20px;
}
.bg-primary td{
  background: #737373;
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
input[type="text"], #formularioModif select{
  width: 50%;
  padding: 10px;
  font-size: 16px;
  text-align: center;
}
input[type="submit"]{
  margin: 10px;
  width: 200px;
  padding:20px;
}
.seleccion:hover td{
  background: #cccccc;
}
#formularioModif input[type="text"], #formularioModif select {
  margin: 5px;
  width: 50%;
}
#formularioModif p {
  text-transform: uppercase;
  margin: 10px 0 0 0;
}
#operaciones {
  margin: 0;
}
#operaciones input[type="text"]{
  width: 40px;
  height: 0px;
}
</style>
<div id="fondo_head"> 
<?php include('../header.php'); ?>
<?php include('../menu/header.php'); ?>
</div>
</head>
<body>
<div id="contenido">
<?php if (isset($_GET['id'])) {
  if (isset($_POST['modificar'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $domicilio = $_POST['domicilio'];
    $saldoFavor = $_POST['saldoFavor'];
    $t_user = $_POST['t_user'];
    if (isset($_POST['permiso_password'])) {
      $resetPassword = 1;
      $clave = password_hash("djdistribuciones", PASSWORD_DEFAULT);
    } else {
      $resetPassword = 0;
    }
    if (isset($clave)) {
      mysqli_query($conexion, "UPDATE users SET username = '$username', nombre = '$nombre', apellido = '$apellido', telefono = '$telefono', domicilio = '$domicilio', saldoFavor = '$saldoFavor', permiso_password = '$resetPassword', password='$clave', rol = '$t_user' WHERE id='$id'");
    } else {
      mysqli_query($conexion, "UPDATE users SET username = '$username', nombre = '$nombre', apellido = '$apellido', telefono = '$telefono', domicilio = '$domicilio', saldoFavor = '$saldoFavor', permiso_password = '$resetPassword', rol = '$t_user' WHERE id='$id'");
    }    
    echo "EDITADO $nombre, $apellido";
  } else {
  $id = $_GET['id'];
  $sql = "SELECT * FROM users WHERE id = '$id'";
  $resultado = mysqli_query($conexion,$sql);
  $datosUser = mysqli_fetch_array($resultado);
  $username = $datosUser['username'];
  $nombre = $datosUser['nombre'];
  $apellido = $datosUser['apellido'];
  $telefono = $datosUser['telefono'];
  $domicilio = $datosUser['domicilio'];
  $saldoFavor = $datosUser['saldoFavor'];
  $rol = $datosUser['rol'];
  switch ($rol) {
    case '0':
      $rol = "USUARIO";
      break;
    case '1':
      $rol = "ADMIN";
      break;
    case '2':
      $rol = "VENDEDOR";
      break;
  }
  ?>
<form action="" method="post" id="formularioModif">
  <p>Username</p>
  <input type="text" name="username" value="<?php echo $username; ?>" /><br />
  <p>Nombre</p>
  <input type="text" name="nombre" value="<?php echo $nombre; ?>"/><br />
  <p>Apellido</p>
  <input type="text" name="apellido" value="<?php echo $apellido; ?>"/><br />
  <p>telefono</p>
  <input type="text" name="telefono" value="<?php echo $telefono; ?>"/><br />
  <p>domicilio</p>
  <input type="text" name="domicilio" value="<?php echo $domicilio; ?>"/><br />
  <p>Tipo de usuario</p>
  <select name="t_user">
    <option value="<?php echo $datosUser['rol'] ?>"><?php echo $rol ?></option>
    <option value="0">Usuario</option>
   <option value="1">Admin</option> 
   <option value="2">Vendedor</option> 
  </select><br />
  <p>saldo a Favor / Saldo de Cuenta corriente</p>
  <input type="text" id="saldoFavor" name="saldoFavor" value="<?php echo $saldoFavor; ?>"/><br />
  <div id="operaciones">
  SUMAR <input type="text" id="suma" name="suma" oninput="cal()">
  <input type="text" id="resta" name="resta" oninput="cal()"> RESTAR
  </div>
  <?php 
    if($datosUser['permiso_password'] == 1){
      echo '<input type="checkbox" name="permiso_password" checked >PERMISO PASSWORD<br />';
    } else {
      echo '<input type="checkbox" name="permiso_password" >PERMISO PASSWORD<br />';
    }
  ?>
  <input type="hidden" name="id" value="<?php echo $id; ?>">
  <input type="submit" name="modificar" value="Modificar" />
</form>
<?php } 
}
else { ?>
<section>
  <input type="text" name="busqueda" id="busqueda" placeholder="Buscar...">
</section>
<section id="tabla_resultado">
</section>
<?php } ?>
</div>
</body>
</html>
<script type="text/javascript">
  function cal() {
  try {
    var d = parseFloat(document.getElementById("suma").value) || 0;
    var r = parseFloat(document.getElementById("resta").value) || 0;
    document.getElementById("saldoFavor").value = <?php echo $saldoFavor ?> + d - r ;    
  } catch (e) {}
}
</script>
<?php 
} else {
	header("Location: ../index.php");
}
?>