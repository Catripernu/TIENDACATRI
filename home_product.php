<div class="section_product">
<?php 
foreach (consulta_seccionCategoria() as $c) {
  echo "<a href='productos.php?section=".$c['id']."&inicio'><img width='350' src='images/categorias/".$c['id'].".webp' /></a>";
}
?>
</div>