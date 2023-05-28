<?php include_once("head.php");
$palabra = (isset($_GET['buscar'])) ? $_GET['buscar'] : "";
?>
<body>
  <div id="contenido">
    <!-- BUSCADOR -->
    <div id="search">
      <form action="./buscador.php" method="GET">
        <input type="text" required autocomplete="off" value="<?=$palabra?>" id="buscar" name="buscar" placeholder="¿Que producto estas buscando?" onfocus="if (this.placeholder=='¿Que producto estas buscando?') this.placeholder='';" onblur="if (this.placeholder=='') this.placeholder='¿Que producto estas buscando?';">
        <button class="fa fa-search fa-lg fa-fw" />
      </form>
    </div>
    <?php if (strlen($palabra) > 2) {
      $pagina_actual = (isset($_GET['pagina']) && $_GET['pagina'] > 0) ? $_GET['pagina'] : 1; 
      $resultados = resultados_busqueda($palabra,$resultados_por_pagina,$pagina_actual,$_SESSION['rol']);      
      if($resultados['resultados']->rowCount()){
        // SI TIENE RESULTADOS MUESTRA LOS PRODUCTOS EN EL FOREACH
        foreach ($resultados['resultados'] as $datos){
          $id = $datos['id'];
          $nombre = $datos['nombre'];
          $precio = ($datos['oferta'] && isset($_SESSION['loggedin'])) ? $datos['precio_o'] : $datos['precio_v'];
          echo view_productos($id, $nombre, formato_precio($precio),productos__opciones($_SESSION['rol'],$nombre,$id));
        }
        paginacion(0,$pagina_actual,$resultados['num_paginas'],$palabra);
      } else { 
        $error = "<b>ERROR</b>: PRODUCTO NO ENCONTRADO O INEXISTENTE.";
        $conexion->query("INSERT INTO palabrasBuscadas(palabra) values ('$palabra')");
      }
      $resultados['resultados']->closeCursor();
    } else {
      $error = "<b>ERROR</b>: EL PRODUCTO A BUSCAR DEBE ESTAR COMPUESTO DE 3 O MAS CARACTERES DE LONGITUD.";
    }
    ?>
  <div class="rojo"><?=$error?></div>
</body>
</html>