<?
/////// CONEXIÓN A LA BASE DE DATOS /////////
include '../config.php';

//////////////// VALORES INICIALES ///////////////////////

$tabla="";
$query="SELECT * FROM users ORDER BY saldoFavor DESC";

///////// LO QUE OCURRE AL TECLEAR SOBRE EL INPUT DE BUSQUEDA ////////////
if(isset($_POST['alumnos']))
{
	$q=$conexion->real_escape_string($_POST['alumnos']);
	$query="SELECT * FROM users WHERE 
		nombre LIKE '%".$q."%' OR
		username LIKE '%".$q."%' OR
		domicilio LIKE '%".$q."%'";
}

$buscarUser=$conexion->query($query);
if ($buscarUser->num_rows > 0)
{
	$cont = 0;
	$tabla.= 
	'<table align="center" border="0" cellpadding="0" cellspacing="0">
		<tr class="bg-primary">
			<td>#</td>
			<td>USERNAME</td>
			<td>NOMBRE</td>
			<td>APELLIDO</td>
			<td>TELEFONO</td>
			<td>DOMICILIO</td>
			<td>CANT COMPRAS</td>
			<td>SALDO FAVOR</td>
			<td>TIEMPO REGISTRO</td>
			<td>OPCIONES</td>
		</tr>';
	while($filaUser= $buscarUser->fetch_assoc())
	{
		$cont = $cont +1;
		$tabla.=
		'<tr class="seleccion">
			<td>'.$cont.'</td>
			<td>'.$filaUser['username'].'</td>
			<td>'.$filaUser['nombre'].'</td>
			<td>'.$filaUser['apellido'].'</td>
			<td>'.$filaUser['telefono'].'</td>
			<td>'.$filaUser['domicilio'].'</td>
			<td>'.$filaUser['cant_compras'].'</td>
			<td>'.$filaUser['saldoFavor'].'</td>
			<td>'.$filaUser['fechaRegistro'].'</td>
			<td><a class="fa fa-edit fa-lg fa-fw" href="editUser.php?id='.$filaUser['id'].'"></a> <a class="fa fa-times-circle fa-lg fa-fw btnEliminarUsuario" data-id="'.$filaUser['id'].'" href="#"></a></td>
		 </tr>
		';
	}

	$tabla.='</table>';
} else
	{
		$tabla="No se encontraron coincidencias con sus criterios de búsqueda.";
	}


echo $tabla;
?>
<script>
      $(".btnEliminarUsuario").click(function(event){
        var id = $(this).data("id"); 
        event.preventDefault();
        var mensaje = confirm("¿Seguro que quiere eliminar este usuario?");
        if (mensaje) {
          $.ajax({
          method:"GET",  
          url:'user/eliminar.php?eliminar='+id,
          success:function(){ 
          	window.location.reload();  
            alert("USUARIO ELIMINADO EXITOSAMENTE");
          } 
        });
        }
      });
</script>