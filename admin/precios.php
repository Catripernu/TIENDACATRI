<?php 
session_start();
include('../config.php');
include('datosRelevantes.php');
if ($_SESSION['rol'] == 1) {
  $datos_precios = $conexion->query("SELECT p_datos.id_producto,p_datos.categoria,p_datos.stock,productos.id,productos.nombre,p_infoweb.oferta,p_precios.precio_o,p_precios.precio_v 
FROM p_datos 
INNER JOIN productos ON p_datos.id_producto=productos.id 
INNER JOIN p_infoweb ON p_infoweb.id_producto=p_datos.id_producto 
INNER JOIN p_precios ON p_precios.id_producto=p_datos.id_producto 
WHERE p_datos.stock != 0 AND p_precios.precio_v > 0 AND p_infoweb.fecha_ultimo_precio != '0000-00-00'
ORDER BY productos.nombre ASC");
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
<style>
  #contenido {
    margin:auto;
    margin-bottom: 20px;
    max-width: 1024px;
  }
  .total {
    display:flex;
    justify-content: space-between;
    align-items: center;
  text-align: center;
  padding: 10px;
  font-size: 1rem;
}
.total i {
  color:black;
  font-size: 1.5rem;
}
.total i:hover {
  color:#322783;
}
.encabezado {
  display: flex;
  justify-content: space-around;
  padding: 10px 0;
  background: #322783;
  color: white;
  font-weight: bold;
  border-radius: 10px 10px 0 0;
}
.detalle_pro {
  display: flex;
  justify-content: space-around;
  gap: 0px;
  padding: 10px 0;
}
.detalle__nombre {
  width: 70%;
}
.detalle_pro:nth-child(odd) { background: #f1f2f3;}
.detalle_pro:nth-child(even) { background: #e3e6e8;}
</style>
<body>
<div id="contenido">
  <div class="total">
    <div><?php echo "Total de productos: ".$datos_precios->num_rows ?></div>
    <div>
      <a target="_blank" href="../ticket/precio.php"><i class="fas fa-print"></i></a>
      <a target="_blank" href="./excel.php?precios"><i class="fas fa-file-excel"></i></a>
    </div>
  </div>
  <div class="encabezado">
    <div>ID</div>
    <div class="detalle__nombre">Producto</div>
    <div>Precio</div>
  </div>
  <?php foreach ($datos_precios as $dato) { ?>
  <div class="detalle_pro">
    <div>#<?=$dato['id']?></div>
    <div class="detalle__nombre"><?=$dato['nombre']?></div>
    <div>$<?=$dato['precio_v']?></div>
  </div>
  <?php } ?>
</div>
</body>
</html>
<?php 
} else {
header("Location: ../index.php");
}
?>