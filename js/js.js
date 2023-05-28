$(document).ready(function(){  
  $('.zoom').hover(function() {
    $(this).addClass('transition');
  }, function() {
      $(this).removeClass('transition');
     });
});
// BUSCADOR - FLECHA VOLVER A HEAD
$(document).ready(function(){
	$('.ir-arriba').hide();
	$('.ir-arriba').click(function(){
		$('body,html').animate({
			scrollTop:0
		},1000)
	});
	$(window).scroll(function () {
    let ancho = $(window).width();
		if ($(this).scrollTop() > 200) {
			$('.ir-arriba').fadeIn();
      // $('header').hide();
		}
		else {
      if($(this).scrollTop() > 200){
        $('.ir-arriba').fadeIn();
      } else {
        $('.ir-arriba').fadeOut();
      }
      // $('header').show();
		}
	});
});
// BUSCADOR - FUNCION SUMA O RESTAR CANTIDAD
function contadormas(n){
  var input = document.getElementById("cantidad"+n);
  var cant = $("#cantidad"+n).val();
  var maximo_stock = $("#maximo_stock").val();
  i = parseInt(cant);
  if (i < maximo_stock) {
    i = i + 1; 
  }
  input.value = i;
}
function contadormenos(n){
  var cant = $("#cantidad"+n).val();
  i = parseInt(cant);
    if(i>=2){
        i = i - 1; 
        var input = document.getElementById("cantidad"+n); 
        input.value = i;
    }
}
// ELIMINAR PRODUCTO DESDE EL PANEL ADMIN
$(document).ready(function(){
  $(".btnEliminarPedido").click(function(event){
    var id = $(this).data("id");
    event.preventDefault();
    var mensaje = confirm("¿Seguro que quiere eliminar este producto?");
    if (mensaje) {
      $.ajax({
      method:"GET",  
      url:'./admin/eliminar_producto.php?eliminar='+id,
      success:function(){   
        alert("PRODUCTO ELIMINADO EXITOSAMENTE");
        window.location.reload();
      } 
      });
    }
  });
// ADDCART PRODUCTOS.PHP
  $(".addCart").click(function(event){
    event.preventDefault();
    var id = $(this).data("id");
    var cantidad = $("#cantidad"+id).val();
    var bolean = 1;
    var dataString = 'id='+id+'&cant='+cantidad;
    $.ajax({
      method:"POST",
      url:"carrito.php",
      data: dataString,
      success:function(){
        location.reload();
      }      
    });
  });
});


$(document).ready(function(){
	$(".btnEliminar").click(function(e){
		e.preventDefault();
		var nombre = $(this).data('nombre');
    var id = $(this).data('id');
		var mensaje = confirm("¿Seguro que quiere eliminar "+ nombre +" de su carrito?");
		if (mensaje) {
			var boton = $(this);
			$.ajax({
				method:'POST',
				url:'../carrito/eliminar_producto_carrito.php',
				data:{id:id}
			}).done(function(respuesta){
				boton.parent('td').parent('tr').remove();
				window.location.reload();
				alert("Producto Eliminado Exitosamente");
			});
		}			
	});

	$(".btnEliminarCarrito").click(function(e){
		e.preventDefault();
		var mensaje = confirm("¿Seguro que quiere eliminar su carrito?");
		if (mensaje) {
		$.ajax({
			method:"GET",  
			url:'../carrito/eliminarSession.php?eliminar',
			success:function(){   
                window.location="index.php";
            } 
		});
		}
	});
});

// FUNCION SOLO NUMEROS EN INPUT TELEFONO
function validaNumericos(event) {
    if(event.charCode >= 48 && event.charCode <= 57){
      return true;
     }
     return false;        
}

// FUNCION PARA SUBMENU ADMIN DE USUARIO
var clic = 1;
function divSubMenu(){ 
   if(clic==1){
   document.getElementById("submenu").style.display = "block";
   clic++;
   } else{
       document.getElementById("submenu").style.display = "none"; 
    clic = 1;
   }   
}

function limpia(elemento){
  elemento.value = "";
}


$(function(){
  $('#js_register').on('click', function(e){
      e.preventDefault();

      var usuario = $('#telefono').val();
      var nombre = $('#nombre').val();
      var apellido = $('#apellido').val();
      var password = $('#password').val();
      var conf_password = $('#confirm_password').val();
      var domicilio = $('#domicilio').val();
      var ciudad = $('#ciudad').val();
      var provincia = $('#provincia').val();

      if(usuario == "" && nombre == "" && apellido == "" && password == "" && conf_password == "" && domicilio == "" && ciudad == "" && provincia == ""){
          alert("ERROR: TODOS LOS CAMPOS ESTAN VACIOS.");
      } else {
          if(usuario == ""){
              alert("ERROR: NO INGRESO SU NUMERO DE CELULAR.");
          } else if(nombre == ""){
              alert("ERROR: NO INGRESO SU NOMBRE.");
          } else if(apellido == ""){
              alert("ERROR: NO INGRESO SU APELLIDO.");
          } else if(password == ""){
              alert("ERROR: NO INGRESO SU CONTRASEÑA.");
          } else if(conf_password == ""){
              alert("ERROR: NO INGRESO LA CONFIRMACION DE CONTRASEÑA.");
          } else if(domicilio == ""){
              alert("ERROR: NO INGRESO SU DOMICILIO.");
          } else if(ciudad == ""){
              alert("ERROR: NO INGRESO SU CIUDAD.");
          } else if(provincia == ""){
              alert("ERROR: NO INGRESO SU PROVINCIA.");
          } else {
              $.ajax({
              type: "POST",
              url: "./php/registro.php",
              data: ('user='+usuario+'&nombre='+nombre+'&apellido='+apellido+'&password='+password+'&conf_password='+conf_password+'&domicilio='+domicilio+'&ciudad='+ciudad+'&provincia='+provincia),
                  success:function(respuesta){                       
                      if(respuesta == 1){
                        alert("BIENVENIDO/A, HA SIDO REGISTRADO/A EXITOSAMENTE!");
                        window.location="login.php";
                      } else {
                        alert(respuesta);
                      }                  
                  } 
              })
          }
      }
  })
})
// CHEACK DE SUGERENCIA EN FINALIZAR CARRITO
function mostrarContenido() {
  element = document.getElementById("sugerencia");
  check = document.getElementById("switch");
  if (check.checked) {
    element.style.display='block';
  } else {
    element.style.display='none';
  }
}