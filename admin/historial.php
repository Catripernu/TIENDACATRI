<?php 
session_start();
include_once('../config.php');
include_once('../admin/datosRelevantes.php');

if (isset($_GET['start'])) {
  $fecha_start = $_GET['start'];
  $fecha_end = $_GET['end'];
}
if (isset($_GET['user'])) {
  $user = $_GET['user'];
}


$ventas_por_pagina = 50;
$iniciar = ($_GET['p']-1)*$ventas_por_pagina;

if (isset($_GET['start'])) {
  $total_ventas = $conexion->query("SELECT * FROM cuentacorriente WHERE (fecha >= '$fecha_start' AND fecha <= '$fecha_end')")->num_rows;
  $consulta = $conexion->query("SELECT * FROM cuentacorriente WHERE (fecha >= '$fecha_start' AND fecha <= '$fecha_end') ORDER BY id DESC LIMIT $iniciar,$ventas_por_pagina");
  $fecha_p = '&start='.$_GET['start'].'&end='.$_GET['end'];
} else {
  $total_ventas = $conexion->query("SELECT * FROM cuentacorriente")->num_rows;
  $fecha_start = date('Y-m-d');
  $fecha_end = date('Y-m-d');
  $consulta = $conexion->query("SELECT * FROM cuentacorriente ORDER BY id DESC LIMIT $iniciar,$ventas_por_pagina");
  $fecha_p = '';
}


if (isset($_GET['user']) && isset($_GET['start'])) {
    if (!empty($_GET['user'])) {
      // CODIGO USUARIO Y FECHA
      $total_ventas = $conexion->query("SELECT * FROM cuentacorriente WHERE (fecha >= '$fecha_start' AND fecha <= '$fecha_end')")->num_rows;
    } else {
    }
  } else {
    if (isset($_GET['user'])) {
      // CODIGO USUARIO
    } else if (isset($_GET['start'])) {
      // CODIGO FECHA
    } else {
      // CODIGO TODO
    }
  }



if ($total_ventas == 0) {
  $total_ventas = 1;
}


// CALCULA CANTIDAD DE PAGINAS
$paginas = $total_ventas / $ventas_por_pagina;
$paginas = ceil($paginas);



if ($_GET['p'] > $paginas || $_GET['p'] < 1) {
  if (isset($_GET['user']) && isset($_GET['start'])) {
    if (!empty($_GET['user'])) {
      // CODIGO USUARIO Y FECHA
      header("Location: ?p=1&user=".$user."&start=".$fecha_start."&end=".$fecha_end);
    } else {
      header("Location: ?p=1&start=".$fecha_start."&end=".$fecha_end);
    }
  } else {
    if (isset($_GET['user'])) {
      // CODIGO USUARIO
      header("Location: ?p=1&user=".$user);
    } else if (isset($_GET['start'])) {
      // CODIGO FECHA
      header("Location: ?p=1&start=".$fecha_start."&end=".$fecha_end);
    } else {
      // CODIGO TODO
      header('Location: ?p=1');
    }
  }
}

if (isset($_SESSION['rol']) == 1) { ?>
<!DOCTYPE html>
<html>
<?php include_once('../links.php'); ?>
<head>
<title><?php echo $titulo; ?></title>
<div id="fondo_head"> 
<?php 
include_once('../header.php');
include_once('../menu/header.php'); ?>
</div>
<style type="text/css">
#tabla .titulos, #tabla .lista {
  display: grid;
  grid-template-columns: 20% 40% 20% 20%;
  justify-content: space-around;
  padding: 10px 0;
}
#tabla .titulos {
  font-weight: bold;
  background: #322783;
  color: white;
  text-transform: uppercase;
  border-radius: 5px 5px 0 0;
}
#tabla .lista:nth-child(even) {
  background: #f2f2f2;
  padding: 5px 0;
}
#tabla .lista:nth-child(odd) {
  background: white;
  border-top:1px solid;
  border-bottom: 1px solid;
  padding: 5px 0;
}
#tabla .lista:hover {
  background: #bfbfbf;
}
a {
  text-decoration: none;
  color: black;
}
a:hover {
  color: #322783;
}
</style>
</head>
<body>
  <div id="contenido">
    <div id="contenido_tablas">
    <div class="filtro">
      <form action="#" method="GET">
        <div class="titulo_filtrar">FILTRAR POR FECHA:</div>
        <div>DESDE <input type="date" id="start" name="start" value="<?php echo $fecha_start ?>" min="2021-11-01" max="2024-12-31"></div>
        <div>HASTA <input type="date" id="end" name="end" value="<?php echo $fecha_end ?>" min="2021-11-01" max="2024-12-31"></div>
        <div><a href="historial.php">Todas</a></div>
        <div>
          <input type="submit" value="Filtrar">
          <input type="hidden" name="user" value="<?php echo $_GET['user'] ?>">
        </div>
    </div>
      </form>
      <form action="#" method="GET">
        <div class="search">
            <div><input type="text" value="<?php echo $_GET['user'] ?>" name="user" placeholder="Ingresar nombre del cliente..."></div>      
        </div>
      </form>


    <div id="tabla">
      <div class="titulos">
        <div>factura</div>
        <div>Cliente</div>
        <div>Fecha</div>
        <div>Importe</div>
      </div>
      <?php 
        foreach($consulta as $dato){ 
          $fecha = date("d/m/Y", strtotime($dato['fecha'])); 
          $id_usuario = $dato['id_usuario'];
          $dato_usuario = $conexion->query("SELECT nombre,apellido,username FROM users WHERE id = $id_usuario")->fetch_assoc();
          $saldo_total = $dato['cc_total'] - $dato['importe'];
          ?>
          <div class="lista">
            <div><a href="./ver_pedidos.php?verPedido=<?php echo $dato['codigo'] ?>">#<?php echo $dato['codigo'] ?></a></div>
            <div><?php echo "<a href='editUser.php?id=".$id_usuario."'>".$dato_usuario['nombre'].", ".$dato_usuario['apellido']."</a> (<a target='blank' href='https://api.whatsapp.com/send?phone=54".$dato_usuario['username']."'>".$dato_usuario['username']."</a>)" ?></div>
            <div><?php echo $fecha ?></div>
            <div><?php echo number_format($dato['importe'], 2, '.','')."(".$saldo_total.")" ?></div>
          </div>
        <?php } ?>
        </div>
        <div class="contenedor_paginacion">
          <nav aria-label="Page navigation example">
            <ul class="pagination">    
              <li class=" page-item <?php echo $_GET['p'] <= 1 ? 'disabled':'' ?>"><a class="page-link btn_paginacion_ant" href="?p=<?php echo $_GET['p']-1..$fecha_p ?>">Anterior</a></li>
              <?php for ($i=0; $i < $paginas; $i++): ?>
              <li class="page-item <?php echo $_GET['p'] == $i+1 ? 'active' :  '' ?>"><a class="page-link" href="?p=<?php echo $i+1..$fecha_p ?>"><?php echo $i+1 ?></a></li>
            <?php endfor ?>    
            <li class="page-item <?php echo $_GET['p'] >= $paginas ? 'disabled':'' ?>"><a class="page-link btn_paginacion_sig" href="?p=<?php echo $_GET['p']+1..$fecha_p ?>">Siguiente</a></li>
              </li>
            </ul>
          </nav>
        </div>      
      </div>
    </div>
  </body>
  </html>
<?php } else {
  header("Location: ../index.php");
}
?>