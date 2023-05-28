<?php include("../php/includes_user.php");
if(isset($_SESSION['loggedin'])){?>
<style>
  h3 {color:orange;}
</style>
<body>
<div id="contenido">
	<h3>>>> <i class="fas fa-fire"></i> OFERTAS <i class="fas fa-fire"></i> <<<</h3>
	<?php 
	$consulta = $conexion->query("SELECT productos.id,productos.nombre,p_datos.stock,p_infoweb.oferta,p_precios.precio_o FROM productos INNER JOIN p_datos ON p_datos.id_producto=productos.id INNER JOIN p_infoweb ON p_infoweb.id_producto=productos.id INNER JOIN p_precios ON p_precios.id_producto=productos.id WHERE p_datos.stock != 0 AND p_infoweb.oferta != 0 OR p_infoweb.dosxuno = '1' GROUP BY p_precios.precio_v DESC");
if ($consulta->rowCount()) {
	foreach ($consulta->fetchAll() as $dato){
		$id = $dato['id'];
		$nombre = $dato['nombre'];
		if($_SESSION['rol'] == 1){
			$opciones = "<a title='Editar: $nombre' href='admin/editar_producto.php?id=$id'><img class='agregar' src='".$url_site."images/btn_editarProducto.png'></a>
						<a class='btnEliminarPedido' title='Borrar: $nombre' data-id='$id' href='#'><img src='".$url_site."images/btn_borrarProducto.png'></a> 
						";
		} else {
			$opciones = "<a title='Agregar al Carrito: $nombre' class='addCart_ofert' data-id='$id' href='#'><img class='agregar' src='".$url_site."images/btn_agregarCarrito.png'></a> 
						<a title='Compartir: $nombre' href='whatsapp://send?text=".$url_site."compartir.php?producto=$id'><img src='".$url_site."images/whatsapp.png'></a>";
		}
		echo view_productos($id, $nombre, formato_precio($dato['precio_o']), $opciones);
	}
} else {
	echo "SIN OFERTAS MOMENTANEAMENTE.";
}
?>
</div>
</body>
</html>
<script>
	$(".addCart_ofert").click(function(event){
    event.preventDefault();
    var id = $(this).data("id");
    var cantidad = $("#cantidad"+id).val();
    var dataString = 'id='+id+'&cant='+cantidad;
    $.ajax({
      method:"POST",
      url:"../carrito.php",
      data: dataString,
      success:function(){
        location.reload();
      }      
    });
  });
</script>
<?php 
} else {
header("Location: ../index.php");
}
?>