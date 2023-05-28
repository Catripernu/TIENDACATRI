<div id="elementos">
  <table align="center" border="0" cellpadding="0" cellspacing="0">
    <thead>
      <th colspan="4"><i class="fas fa-star"></i> LO MAS VENDIDO <i class="fas fa-star"></i></th>
    </thead>
    <?php 
    function limitar_cadena($cadena, $limite, $sufijo){
      if(strlen($cadena) > $limite){
        return substr($cadena, 0, $limite) . $sufijo;
      }
    return $cadena;
    }
    $cont = 1;
    while($datos= $index_top10->fetch_assoc()) {
      $cadena_nombre = limitar_cadena($datos['nombre'], 30, '...');
      if ($datos['oferta'] == 1 && $_SESSION['loggedin']) {
        $precio = "<font title=OFERTA color=#66ff33>".money_format('%.2n', $datos['precio_o'])."!</font>";
      } else {
        $precio = sprintf('%01.2f', $datos['precio_v']);
      }   
    if($cont % 2 == 0){ 
      topProductos("par",$cont,$datos['nombre'],$cadena_nombre,$precio,$datos['id']);
    } else { 
      topProductos("impar",$cont,$datos['nombre'],$cadena_nombre,$precio,$datos['id']);
    }
    $cont = $cont + 1;
}

function topProductos ($clase,$cont,$nombre,$cadena_nombre,$precio,$id){
    $tabla = "<tr class='$clase'>
        <td>#$cont</td>
        <td title='$nombre'>$cadena_nombre</td>
        <td>$$precio</td>
        <td><a class='linkAddProduct' onclick='topProductoAdd($id)' href='#'><i title='Añadir $nombre al carrito' class='fa fa-plus-square fa-lg fa-fw' aria-hidden='true'></i></a></td>
      </tr>";
    echo $tabla;
  }


mysqli_free_result($resultadoProductos);
mysqli_close($conexion);
 ?>
  </table>
</div>