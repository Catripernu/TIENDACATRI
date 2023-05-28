<?php include_once("head.php"); ?>
<?php 
$id = (isset($_GET['section'])) ? $_GET['section'] : 0;
//SELECCIONAMOS COLOR DE LA CATEGORIA ELEGIDA
if($_GET['section']){
    $color = seccionCategoria_colores($_GET['section']);
}
?>
<style>
.productos:hover{
  box-shadow: 0 0 5px 1px <?php echo $color['color_fuente']; ?>;  
}
</style>
<body>
  <span id="ir-arriba" class="ir-arriba fa fa-angle-up fa-lg fa-fw"></span>
  <div id="contenido">
<?php 
if (isset($_GET['section'])) {
  $existencia_section = $conexion->query("SELECT * FROM seccionCategoria WHERE id = ".$_GET['section']."");  
  if (isset($_GET['inicio']) && $existencia_section->rowCount()) {
    $section = $_GET['section'];
    // PAGINA ACTUAL
    $pagina_actual = (isset($_GET['pagina']) && $_GET['pagina'] > 0) ? $_GET['pagina'] : 1;
    $resultados_paginados = obtener_resultados_productos("productos", $resultados_por_pagina, $pagina_actual, $section, $_SESSION['rol']);   
    if($resultados_paginados['resultados']->rowCount()){
      // echo http_build_query($_GET);
      $seccion = datosSeccion($id);
      echo "<div class='productos__indice'><a href='productos.php'>PRODUCTOS</a> <span class='fa fa-angle-double-right fa-lg fa-lw'></span> ".$seccion['nombre']."</div>"; 
      // Mostrar los resultados
      foreach ($resultados_paginados['resultados'] as $fila) {
        $id = $fila['id'];
        $nombre = $fila['nombre'];
        $stock = $fila['stock'];
        // PRECIO DEL PRODUCTO: OFERTA - VENTA
        $precio = ($fila['oferta'] && isset($_SESSION['loggedin'])) ? $fila['precio_o'] : $fila['precio_v'];
        // OPCIONES PARA ADMIN: EDITAR - ELIMINAR / OPCIONES PARA USER-VISITANTE: AGREGAR - COMPARTIR
        if($_SESSION['rol'] == 1){
          $opciones = "<a title='Editar: $nombre' href='admin/editar_producto.php?id=$id'><img class='agregar' src='images/btn_editarProducto.png'></a>
                      <a class='btnEliminarPedido' title='Borrar: $nombre' data-id='$id' href='#'><img src='images/btn_borrarProducto.png'></a> 
                      ";
          if($stock > 0 && $stock < 10){
            echo "<script>
                  var r = confirm('$nombre LE QUEDAN SOLO $stock UNIDADES.');
                  if (r == true) {
                    window.location='admin/editar_producto.php?id=$id';
                  }
                  </script>";
          }
        } else {
          $opciones = "<a title='Agregar al Carrito: $nombre' class='addCart' data-id='$id' href='#'><img class='agregar' src='images/btn_agregarCarrito.png'></a> <a title='Compartir: $nombre' href='whatsapp://send?text=".$url_site."compartir.php?producto=$id'><img src='images/whatsapp.png'></a>";
        }
        echo view_productos($id, $nombre, formato_precio($precio), $opciones);
      }
      paginacion($section,$pagina_actual,$resultados_paginados['num_paginas'],false);
    } else {
      echo "<b class='msg_error'>ERROR:</b> NO SE ENCONTRARON PRODUCTOS.";
      header("Refresh: 4; url=".$url_site."productos.php");
      exit;
    }
  } else {
    include('home_product.php');
  } 
} else {
  include('home_product.php');
} 
?>
  </div>
</body>
</html>