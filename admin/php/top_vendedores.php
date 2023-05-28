<?php 
$consulta = $conexion->query("SELECT * FROM users WHERE rol = 2 ORDER BY cant_compras DESC LIMIT 20");
$cont = 1;
if ($existencia=$consulta->num_rows) { ?>
  <table align="center" width="80%" border="0" cellpadding="0" cellspacing="0">
    <tr class="bg-primary">
      <td>#</td>
      <td>VENDEDOR</td>
      <td>TOTAL VENTAS</td>
    </tr>
    <?php while($datos = $consulta->fetch_assoc()) { ?>
      <tr class="seleccion">
        <td><?php echo $cont; ?></td>
        <td><a href="./vendedores.php?vendedor=<?php echo $datos['id'] ?>"><?php echo $datos['nombre'].', '.$datos['apellido']; ?></a></td>
        <td><?php echo $datos['cant_compras']; ?></td>
      </tr>
    <? $cont++; } ?>
  </table>
<?
 } 
?>


  

  

