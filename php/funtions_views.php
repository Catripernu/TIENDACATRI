<?php 
function view_productos($id,$nombre,$precio,$opciones){
  echo "<div class='view_items productos'>
    <div class='img'><img class='zoom' src='".$url_site."/images/productos/ompick_$id.webp' alt='' /></div>
    <div class='producto'>$nombre</div>
    <div class='precio'>$precio</div>
    <div class='cantidad'>
      <div class='cantidad__op'>
        <button type='button' id='menos' onclick='contadormenos($id)'>-</button>
        <input id='cantidad$id' readonly type='text' max='".$GLOBALS['maximo_stock']."' value='1'>
        <button type='button' id='mas' onclick='contadormas($id)'>+</button>
        <input type='hidden' id='maximo_stock' value='".$GLOBALS['maximo_stock']."'>
      </div>    
    </div>
    <div class='opciones'>
      $opciones
    </div>
  </div>";
}
?>