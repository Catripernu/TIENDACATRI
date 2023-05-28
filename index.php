<?php include_once("head.php"); ?>
<body>
<div id="contenido">
  <?php Apk(); ?>
  <!-- BUSCADOR -->
  <div id="search">
    <form action="./buscador.php" method="GET">
      <input type="text" required autocomplete="off" id="buscar" name="buscar" placeholder="¿Que producto estas buscando?" onfocus="if (this.placeholder=='¿Que producto estas buscando?') this.placeholder='';" onblur="if (this.placeholder=='') this.placeholder='¿Que producto estas buscando?';">
      <button class="fa fa-search fa-lg fa-fw" />
    </form>
  </div>
  <?php include("home_product.php"); ?>
  <span class="btn__ayuda"><a href="ayuda.php"></a></span>
</div>  
</body>
</html>
<?php 
// FUNCION PARA AVISO DE APK
 function Apk(){
  if(isset($_SESSION['apk']) || isset($_GET['apk'])):
    $_SESSION['apk'] = 1;
  else: 
    echo '<div class="Apk parpadea"><a href="./'.$GLOBALS["apk"].'.apk">¡DESCARGAR APLICACION PARA ANDROID!</a></div>'; 
  endif;
 }
?>