<?php 
$color_carrito = (isset($_SESSION['carrito'])) ? "style='color:yellow'" : "";  
$gastoTotal = (isset($_SESSION['gastoTotal'])) ? "Total: $".$_SESSION['gastoTotal'] : "<style>.header__gastoTotal{display:none}</style>";  
?>
<header>
  <a class="header" href="<?=$url_site?>">
    <img src="<?=$url_site?>images/logo.png" alt="<?php echo $titulo; ?>">
  </a>
  <div class="header__gastoTotal"><a href="<?=$url_site?>carrito.php"><?=$gastoTotal?></a></div>
  <nav>
    <ul>
        <li><a href="<?=$url_site?>index.php"><i class="fa fa-search fa-lg fa-fw"></i><p>BUSCAR</p></a></li>
        <li><a href="<?=$url_site?>productos.php"><i class="fa fa-box fa-lg fa-fw"></i><p>PRODUCTOS</p></a></li>
        <?php if($_SESSION['rol'] != 1){ ?>
        <li><a <?=$color_carrito?> href="<?=$url_site?>carrito.php"><i class="fa fa-shopping-cart fa-lg fa-fw"></i><p>CARRITO</p></a></li>
        <?php } ?>
      <?php if(isset($_SESSION['loggedin'])){ ?>
        <li><a href="#" id="boton" onclick="divSubMenu()"><i class="fa fa-list fa-lg fa-fw"></i><p>MENU</p></a></li>
        <li><a href="<?=$url_site?>user/logout.php"><i class="fa fa-user-times fa-lg fa-fw"></i><p>SALIR</p></a></li>
      <?php } else { ?>
        <li><a href="<?=$url_site?>registrarme.php"><i class="fa fa-user-plus fa-lg fa-fw"></i><p>REGISTRO</p></a></li>
        <li><a href="<?=$url_site?>login.php"><i class="fa fa-user fa-lg fa-fw"></i><p>INGRESAR</p></a></li>
      <?php } ?>    
      </ul>
</nav>

<nav id="submenu">
  <ul>
    <?php if($_SESSION['rol'] == 0) {?>
    <li><a href="<?=$url_site?>user/modificar_datos.php"><i class="fa fa-users-cog fa-lg fa-fw"></i><p>CUENTA</p></a></li>
    <li><a href="<?=$url_site?>user/compras.php"><i class="fa fa-shopping-basket fa-lg fa-fw"></i><p>COMPRAS</p></a></li>
    <li><a href="<?=$url_site?>user/ofertas.php"><i class="fa fa-tags fa-lg fa-fw"></i><p>OFERTAS</p></a></li>    
    <?php } 
    if($_SESSION['rol'] == 2) {?>
    <li><a href="<?=$url_site?>user/modificar_datos.php"><i class="fa fa-users-cog fa-lg fa-fw"></i><p>CUENTA</p></a></li>
    <li><a href="<?=$url_site?>user/ventas.php"><i class="fa fa-shopping-basket fa-lg fa-fw"></i><p>VENTAS</p></a></li>
    <li><a href="<?=$url_site?>admin/agenda.php"><i class="fa fa-th-list fa-lg fa-fw"></i><p>AGENDA</p></a></li>
    <li><a href="<?=$url_site?>user/ofertas.php"><i class="fa fa-tags fa-lg fa-fw"></i><p>OFERTAS</p></a></li>
    <?php } 
    if($_SESSION['rol'] == 1) {?>
    <li><a href="<?=$url_site?>index.php"><i class="fa fa-shipping-fast fa-lg fa-fw"></i><p>PEDIDOS</p></a></li>
    <li><a href="<?=$url_site?>index.php"><i class="fa fa-file-invoice-dollar fa-lg fa-fw"></i><p>TICKETS</p></a></li>
    <li><a href="<?=$url_site?>index.php"><i class="fa fa-user-tie fa-lg fa-fw"></i><p>VENDEDORES</p></a></li>
    <li><a href="<?=$url_site?>index.php"><i class="fa fa-th-list fa-lg fa-fw"></i><p>AGENDA</p></a></li>
    <li><a href="<?=$url_site?>index.php"><i class="fa fa-history fa-lg fa-fw"></i><p>CC</p></a></li>
    <li><a href="<?=$url_site?>index.php"><i class="fa fa-chart-line fa-lg fa-fw"></i><p>ESTADISTICAS</p></a></li>
    <li><a href="<?=$url_site?>index.php"><i class="fa fa-cart-plus fa-lg fa-fw"></i><p>PRODUCTOS</p></a></li>
    <li><a href="<?=$url_site?>index.php"><i class="fa fa-donate fa-lg fa-fw"></i><p>OFERTAS</p></a></li>
    <li><a href="<?=$url_site?>index.php"><i class="fa fa-user-edit fa-lg fa-fw"></i><p>USUARIOS</p></a></li>
    <li><a href="<?=$url_site?>index.php"><i class="fa fa-search-dollar fa-lg fa-fw"></i><p>PRECIOS</p></a></li>
    <li><a href="<?=$url_site?>index.php"><i class="fa fa-key fa-lg fa-fw"></i><p>PASSWORDS</p></a></li>
    <li><a href="<?=$url_site?>index.php"><i class="fa fa-cubes fa-lg fa-fw"></i><p>STOCKS</p></a></li>
    <?php } ?>
  </ul>
</nav>
</header>