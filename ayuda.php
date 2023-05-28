<?php include_once("head.php"); ?>
<body>
	<div id="contenido">
		<div id="ayuda">
		<p class="titulosPreguntas">Registro y primera compra</p>
		<p class="preguntas" onclick="mostrar(1)"><i class="fa fa-question fa-lg fa-fw"></i>¿Para realizar una compra debo estar registrado?</p>
		<p class="respuesta" id="1">No, no es necesario estar registrado para realizar una compra. Ahora, para visualizar los productos en ofertas y obtener descuentos debes estar registrado.</p>
		<p class="preguntas" onclick="mostrar(2)"><i class="fa fa-question fa-lg fa-fw"></i>¿Como me registro?</p>
		<p class="respuesta" id="2">Desde la pantalla de bienvenida, podras encontrar el boton de “Iniciar Sesion” en el margen superior derecho o en la franja roja tendremos el link “¡REGISTRARME PARA MAS DESCUENTOS!”. Luego tendras que completar los datos solicitados y ¡listo! <br><br>
			Link de registro: <a href="registrarme.php">CLICK AQUI</a></p>
		<p class="preguntas" onclick="mostrar(3)"><i class="fa fa-question fa-lg fa-fw"></i>¿Puedo modificar mis datos personales?</p>
		<p class="respuesta" id="3">Si, los datos personales pueden modificarse haciendo clic en tu nombre, desde el menu del usuario (dentro de la franja azul).</p>
		<p class="preguntas" onclick="mostrar(4)"><i class="fa fa-question fa-lg fa-fw"></i>¿Hay un monto minimo de compra?</p>
		<p class="respuesta" id="4">No, no existe monto minimo de compra, pero siendo usuario registrado obtenes descuentos por tu compra.</p>
		<p class="preguntas" onclick="mostrar(5)"><i class="fa fa-question fa-lg fa-fw"></i>¿Recibo alguna confirmacion luego de realizar el pedido?</p>
		<p class="respuesta" id="5">Luego de realizar el pedido, nos comunicamos via Whatsapp con usted para verificar el pedido y acordar horarios de entrega. (en caso de no tener Whatsapp, se realiza una llamada).</p>
		<p class="preguntas" onclick="mostrar(6)"><i class="fa fa-question fa-lg fa-fw"></i>¿Como visualizo los productos seleccionados hasta el momento?</p>
		<p class="respuesta" id="6">Podras visualizar el importe acumulado y los productos ingresando en el carrito el cual esta ubicado en el margen superior derecho. (Celular:<span class="fa fa-shopping-cart fa-lg fa-fw"></span>)</p>
		<p class="preguntas" onclick="mostrar(7)"><i class="fa fa-question fa-lg fa-fw"></i>¿Donde puedo ver las promociones y ofertas?</p>
		<p class="respuesta" id="7">Todas las promociones y ofertas podras verla dentro del menu usuario “OFERTAS”.</p>
		<p class="preguntas" onclick="mostrar(8)"><i class="fa fa-question fa-lg fa-fw"></i>¿Si abandono el sitio sin confirmar el pedido pierdo los productos cargados en el carrito?</p>
		<p class="respuesta" id="8">No, tu pedido sigue cargado en el sitio hasta que se reinicie tu computadora o celular.</p>
		<p class="preguntas" onclick="mostrar(9)"><i class="fa fa-question fa-lg fa-fw"></i>¿Que puedo hacer si al registrarme el sistema me informa que ya existe el usuario?</p>
		<p class="respuesta" id="9">Verifica que la información ingresada sea correcta, si te olvidaste tu contraseña, desde la sección: “Iniciar Sesión” encontraras el link: “Olvide mi contraseña”, el cual se te solicitara el número de teléfono que tienes registrado en la cuenta para así se te restaurara la contraseña.</p>
		<p class="preguntas" onclick="mostrar(10)"><i class="fa fa-question fa-lg fa-fw"></i>¿De que formas puedo agregar, modificar o quitar un producto del carrito de compras?</p>
		<p class="respuesta" id="10">Podrás agregar los productos desde el buscador con su cantidad, desde el carrito solo podrás eliminar el producto, eliminar el carrito o finalizar la compra.</p>
		
		<p class="titulosPreguntas">Sobre el envio</p>
		<p class="preguntas" onclick="mostrar(11)"><i class="fa fa-question fa-lg fa-fw"></i>¿Cuales son los metodos de envio disponibles?</p>
		<p class="respuesta" id="11">El unico metodo de envio disponible es a domicilio.</p>
		<p class="preguntas" onclick="mostrar(12)"><i class="fa fa-question fa-lg fa-fw"></i>¿Que necesito para recibir mi pedido?</p>
		<p class="respuesta" id="12">Solamente que tu direccion sea la correcta.</p>
		<p class="preguntas" onclick="mostrar(13)"><i class="fa fa-question fa-lg fa-fw"></i>¿Cual es el costo y tiempo de envio?</p>
		<p class="respuesta" id="13">Costos del envio:<br><br><b>GRATIS.</b><br>
			<b>Compra usuario Registrado: GRATIS.</b><br>
			<b>Compra usuario Invitado: GRATIS.</b><br><br>
			ENVIOS DE LUNES A SABADO.<br><br>
		<p class="preguntas" onclick="mostrar(14)"><i class="fa fa-question fa-lg fa-fw"></i>¿Cuales son los horarios de entrega de los envios a domicilio?</p>
		<p class="respuesta" id="14">Horarios de envio (Lunes a Sabado):<br><br>
			Por la mañana <b>de 00:00 a 00:00 hs</b><br>
			Por la tarde <b>de 00:00 a 00:00 hs</b></p></p>
		<p class="preguntas" onclick="mostrar(15)"><i class="fa fa-question fa-lg fa-fw"></i>¿Se puede cancelar un pedido?</p>
		<p class="respuesta" id="15">Se puede cancelar el pedido medidante nuestro Whatsapp con 1 (Una) hora de anticipacion.</p>
		<p class="preguntas" onclick="mostrar(16)"><i class="fa fa-question fa-lg fa-fw"></i>¿Que pasa si no hay nadie en el domicilio al momento de la entrega?</p>
		<p class="respuesta" id="16">Se volvera a realizar el envio dentro de 30 minutos o 1 hora.</p>
		<p class="preguntas" onclick="mostrar(17)"><i class="fa fa-question fa-lg fa-fw"></i>¿Puedo hacer un pedido y recibirlo el mismo dia?</p>
		<p class="respuesta" id="17">Si, podes realizar un pedido siempre y cuando sea antes de los horarios de envio, para recibirlo el mismo dia.</p>
		<p class="preguntas" onclick="mostrar(18)"><i class="fa fa-question fa-lg fa-fw"></i>¿Puedo dejar una persona encargada para recibir el pedido?</p>
		<p class="respuesta" id="18">Si, podes dejar una persona a encargada de recibir el pedido.</p>

		<p class="titulosPreguntas">Sobre el pago</p>
		<p class="preguntas" onclick="mostrar(19)"><i class="fa fa-question fa-lg fa-fw"></i>¿Como pago mi pedido?</p>
		<p class="respuesta" id="19">Finalizando el carrito, tenemos la opcion de pago, pago en “EFECTIVO” o “TARJETA”. Cualquiera sea la eleccion, se realiza desde tu domicilio, la opcion tarjeta te la cobramos mediante Point Mercadopago, la forma mas segura hasta el momento.</p>
		<p class="preguntas" onclick="mostrar(20)"><i class="fa fa-question fa-lg fa-fw"></i>¿Como identifico las ofertas y promociones en los productos?</p>
		<p class="respuesta" id="20">Dentro del menu usuario, en la opcion “OFERTAS”, podras encontrar las ofertas y promociones.</p>
		<p class="preguntas" onclick="mostrar(21)"><i class="fa fa-question fa-lg fa-fw"></i>¿Como recibo la factura?</p>
		<p class="respuesta" id="21">Tu comprobante se encuenta en la seccion “MIS COMPRAS”, que lo podes visualizar de 3 maneras (naranja = pedido pendiente) (verde = pedido entregado) (rojo = pedido cancelado).</p>
		<p class="preguntas" onclick="mostrar(22)"><i class="fa fa-question fa-lg fa-fw"></i>¿Que es el Saldo a Favor?</p>
		<p class="respuesta" id="22">En caso de que el cliente quiera dejar saldo a favor, se te habilita dentro de “MIS COMPRAS”, el cual sirve para tus futuras compras dentro del sitio.</p>
		</div>
	</div>
</body>
</html>

<script type="text/javascript">
	function mostrar(numero) {
  var x = document.getElementById(numero);
  if (x.style.display === 'block') {
      x.style.display = 'none';
  } else {
      x.style.display = 'block';
  }
}
</script>