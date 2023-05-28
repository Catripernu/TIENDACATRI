<?php

session_start();

include('../config.php');

include('datosRelevantes.php');

include('../php/funciones.php');

if ($_SESSION['rol'] == 1) {

setlocale(LC_MONETARY, 'en_US.UTF-8');

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

<body>

<?php

if (isset($_GET['verPedido'])) {

	$pedido = $_GET['verPedido'];

	if (isset($_GET['modif'])) {

		$pedido = $_GET['verPedido'];

		echo "PROXIMAMENTE PEDIDO $pedido...";

	} else {

	$datosVentasCliente = $conexion->query("SELECT * FROM ventascliente WHERE ID = $pedido")->fetch_array();

	$consultaPRODUCTOSVENTAVerPedido = $conexion->query("SELECT * FROM productos_venta WHERE id_venta = $pedido");

	// OBTENEMOS EL VALOR DEL COMPRADOR, REGISTRADO O NO

	if($datosVentasCliente['id_usuario'] != 0){

		$vendedor = $conexion->query("SELECT * FROM ventascliente vc INNER JOIN datos_compradornr dc ON vc.ID=dc.ID WHERE vc.ID = $pedido");

		$existencia_vendedor = $vendedor->num_rows;



		if ($existencia_vendedor) {

			$datosUSERS = $conexion->query("SELECT * FROM datos_compradornr WHERE ID = '$pedido'")->fetch_assoc();

		} else {

			$datosUSERS = $conexion->query("SELECT * FROM users WHERE id = '".$datosVentasCliente['id_usuario']."'")->fetch_array();

		}



		if (isset($datosUSERS['verificado']) == 1) {

			$verificado = '<font color="green">VERIFICADO</font>';

		} else {

			$verificado = 'NO VERIFICADO';

		}

		$comprasTotales = isset($datosUSERS['cant_compras']);

	} else {

		$datosUSERS = $conexion->query("SELECT * FROM datos_compradornr WHERE ID = '$pedido'")->fetch_assoc();

		$verificado = '<font color="red">C. FINAL</font>';

		$comprasTotales = 0;

	}

	// STRING METODO DE PAGO

	if ($datosVentasCliente['metodo_pago'] == 1) {

		$pago = 'EFECTIVO';

	} else {

		$pago = 'OTROS';

	}

?>

<style type="text/css">

#verPedidosTitulosTR {

<?php if ($datosVentasCliente['estado'] == 0 || $datosVentasCliente['estado'] == 3) {

	echo 'background-color: #24282b;';

} else if ($datosVentasCliente['estado'] == 1) {

	echo 'background-color: #057800;';

} else {

	echo 'background-color: #A51F27;';

}

?>

}

#detallePedidosAdmin {

  width: 50%;

}

@media (max-width: 1080px) {

#detallePedidosAdmin {

  width: 100%;

}

}

@media (max-width: 700px){

#detallePedidosAdmin{

  font-size: 10px;

}

.botones-verPedidos{

  margin:0;

  padding: 0;

  float: left;

}

}

#detallePedidosAdmin #verPedidosTitulosTR td{

  color: white;

  letter-spacing: 2px;

  font-weight: bold;

  padding: 10px 0;

}

#detallePedidosAdmin td {

	text-align: center;

  padding: 3px 0;

}

#detallePedidosAdmin .fondoDetallesAdmin{

	background: #e6e6e6;

}

#detallePedidosAdmin .datosCompraAdmin{

	text-align: left;

	padding-left: 10px;

}

#detallePedidosAdmin .linkWPP-verPedidos{

  text-decoration: none;

  font-weight: bold;

  color: green;

  letter-spacing: 1px;

}

.botones-verPedidos{

	margin: 20px;

  padding: 10px 30px 10px 30px;

  border-radius: 5px;

  font-weight: bold;

  cursor: pointer;

  text-decoration: none;

}

.btnVolverAtras-verPedidos {

  border: 2px solid #1f3d7a;

  color: #1f3d7a;

  background: #ebf0fa;

}

.btnVolverAtras-verPedidos:hover {

  background: #aec2ea;

}

.btnCancelar-verPedidos {

  border: 2px solid red;

  color: red;

  background: #ffcccc;

}

.btnCancelar-verPedidos:hover {

  background: #ff9999;

}

.btnEntregado-verPedidos {

  border: 2px solid green;

  color: green;

  background: #d9f2d9;

}

.btnEntregado-verPedidos:hover {

  background: #b3e6b3;

}

.btnCuentaCorriente-verPedidos {

border: 2px solid #7300e6;

color: #7300e6;

background: #f2e6ff;

}

.btnCuentaCorriente-verPedidos:hover {

background: #bd80ff;

}

.saldoFavor {

	color:green;

	font-size:22px;

	font-weight: bold;

	text-align: center;

	padding:10px;

}

.btnAgregar-verPedidos {

	border: 2px solid orange;

  color: orange;

  background: #ffedcc;

}

.btnAgregar-verPedidos:hover {

  background: #ffdb99;

}

#monto_saldoFavor td {

	text-align: right;

	padding-right: 20px;

	font-size: 12px;

	font-weight: bold;

}

#monto_saldoFavor td input[type="text"]{

	width: 80px;

	text-align: center;

	font-size: 12px;

	height: 14px;

	border-radius: 10px;

	background: #e6ffe6;

}

</style>

<?php

	if ($datosVentasCliente['estado'] == 3 || $datosVentasCliente['estado'] == 1) {

		$td_saldo_favor = false;

		$colspan = 3;

	} else {

		$td_saldo_favor = true;

		$colspan = 2;

	}

?>

<div id="contenido">

	<table id="detallePedidosAdmin" align="center" border="0" cellpadding="0" cellspacing="0">

		<tr id="verPedidosTitulosTR">

			<td colspan="3">DATOS COMPRADOR #<?php echo $pedido ?></td>

		</tr>

		<tr class="fondoDetallesAdmin">

			<td width="70%" class="datosCompraAdmin"><b>CLIENTE: </b><?php echo $datosUSERS['nombre']." ".$datosUSERS['apellido']; ?></td>

			<td align="center" colspan="2"><b><?php echo $verificado; ?></b></td>

		</tr>

		<tr class="fondoDetallesAdmin">

			<td class="datosCompraAdmin"><b>PAGO:</b> <?php echo $pago; ?></td>

			<td colspan="2" align="center">

				<?php if ($comprasTotales != 0) {

					echo '<b title="COMPRAS TOTALES">CT:</b> '.$comprasTotales;

				} ?>

			</td>

		</tr class="fondoDetallesAdmin">

			<tr class="fondoDetallesAdmin"><td colspan="1" class="datosCompraAdmin"><b>DOMICILIO:</b> <?php echo $datosUSERS['domicilio']; ?></td>

				<td colspan="2"><a target="_blank" href="../ticket/index.php?ticket=<?php echo $pedido; ?>" class="LinkTicket" style="color:green; font-size: 20px;"><i class="fas fa-print"></i> IMPRIMIR</a></td>

			</tr>

			<tr class="fondoDetallesAdmin"><td class="datosCompraAdmin"><b>CONTACTO:</b> <?php echo $datosUSERS['telefono']; ?></td>

			<td colspan="2" align="center">

				<?php if ($datosVentasCliente['estado'] == 0) { ?>

					<a target="blank" class="linkWPP-verPedidos" href="https://api.whatsapp.com/send?phone=54<?php echo $datosUSERS['telefono']; ?>&text=Hola!%20me%20comunico%20con%20usted%20por%20la%20compra%20que%20realizo%20en%20DjDistribuciones.com.ar">ENVIAR WPP</a>

				<?php } ?>

			</td></tr>

			<tr class="fondoDetallesAdmin">

				<td colspan="3" class="datosCompraAdmin"><b>CIUDAD:</b> <?php echo $datosUSERS['ciudad']; ?></td>

			</tr>

			<tr class="fondoDetallesAdmin">

				<td colspan="3" class="datosCompraAdmin"><b>PROVINCIA:</b> <?php echo $datosUSERS['provincia']; ?></td>

			</tr>

			<?php if ($datosVentasCliente['sugerencia']) { ?>

			<tr class="fondoDetallesAdmin">

				<td colspan="3" class="datosCompraAdmin"><b>SUGERENCIA:</b> <?php echo $datosVentasCliente['sugerencia']; ?></td>

			</tr>

			<?php }

				if(isset($datosUSERS['saldoFavor']) != 0 && $td_saldo_favor) { ?>

				<tr class="fondoDetallesAdmin">

				<td colspan="<?php echo $colspan ?>" class="saldoFavor">SALDO A FAVOR: $<?php echo $datosUSERS['saldoFavor']; ?></td>

				<?php if ($td_saldo_favor) { ?>

				<td><input type="checkbox" name="utilizarSaldoFavor" id="utilizarSaldoFavor" /> UTILIZAR SALDO</td>

				<?php } ?>

				</tr>

				<tr id="monto_saldoFavor" style="display:none;" class="fondoDetallesAdmin">

					<td colspan="3">MONTO A USAR: <input class="monto_saldoFavor" name="monto_saldoFavor" type="text" value="<?php echo $datosUSERS['saldoFavor'] ?>"></td>

				</tr>

			<?php

			} if($datosVentasCliente['estado'] == 1){ ?>

				<tr class="fondoDetallesAdmin">

					<td colspan="3"><b>FECHA ENTREGA:</b> <?php echo $datosVentasCliente['fecha_entrega']; ?> - <?php echo $datosVentasCliente['hora_entrega']; ?></td>

				</tr>

			<?php } ?>

			<tr id="verPedidosTitulosTR">

				<td>PRODUCTO</td>

				<td>CANT</td>

				<td>PREC</td>

			</tr>

			<?php

			$numero = $consultaPRODUCTOSVENTAVerPedido->num_rows;

			if ($numero > 2) {

				if ($datosVentasCliente['id_usuario'] != 0) {

					$total = $envioConRegistro;

					$avisoEnvio = $envioConRegistro;

				} else {

					$total = $envioSinRegistro;

					$avisoEnvio = $envioSinRegistro;

				}

			} else {

				if ($datosVentasCliente['id_usuario'] != 0) {

					$total = $envioPocosProductos + $envioConRegistro;

					$avisoEnvio = $envioPocosProductos + $envioConRegistro;

				} else {

					$total = $envioPocosProductos + $envioSinRegistro;

					$avisoEnvio = $envioPocosProductos + $envioSinRegistro;

				}

			}

			$ganancia = 0;

			for ($i=0; $i < $numero; $i++) {

				$row = $consultaPRODUCTOSVENTAVerPedido->fetch_array();

				$ganancia = (($row['precio'] - $row['precio_v']) * $row['cantidad']) + $ganancia;

				$total = $total + $row['subtotal'];

				$datosPRODUCTO = $conexion->query("SELECT * FROM productos WHERE id = '".$row['id_producto']."'")->fetch_array();

				echo '<tr class="fondoDetallesAdmin"><td>'.$datosPRODUCTO["nombre"].' ('.$row["precio"].')</td><td align="center">'.$row['cantidad'].'</td><td align="center">$'.number_format($row['subtotal'], 2, '.','').'</td></tr>';

			}

?>

		<tr class="fondoDetallesAdmin">

			<td>ENVIO</td>

			<td colspan="2">$<?php echo $avisoEnvio; ?></td>

		</tr>

		<tr class="fondoDetallesAdmin">

			<td>GANACIA TOTAL</td>

			<td colspan="2"><?php

			if ($datosVentasCliente['total'] > 1000 && $datosVentasCliente['id_usuario'] != 0) {

				$descuento = intdiv($datosVentasCliente['total'], 1000) * 10;

				$ganancia = $ganancia - $descuento;

			}

			if ($avisoEnvio == 0) {

				// echo money_format('%.2n', $ganancia);

				echo $ganancia;

			} else {

				$gananciaTotal = $avisoEnvio + $ganancia;

				echo money_format('%.2n', $gananciaTotal)." (".$ganancia.")";

			} ?></td>

			<input type="hidden" id="ganancia" value="<?php echo $ganancia ?>">

		</tr>

		<tr id="verPedidosTitulosTR">

			<td>TOTAL</td>

			<td colspan="2" align="center">$<?php echo number_format($datosVentasCliente['total'], 2, '.',''); ?></td>

		</tr>

	</table>

	<br>

	<?php if (isset($_GET['t'])) {

		$link_volveratras = "href=./tickets.php";

	} else {

		$link_volveratras = 'href="javascript:history.back()"';

	} ?>

<a class="botones-verPedidos btnVolverAtras-verPedidos" <?php echo $link_volveratras ?>><i class="fas fa-chevron-circle-left"></i></a>

<?php

if ($datosVentasCliente['estado'] == 0) {

?>

	<a class="botones-verPedidos btnCancelar-verPedidos btnCancelarPedido" data-id="<?php echo $pedido; ?>" href="#">CANCELAR <i class="fas fa-times-circle"></i></a>

	<a class="botones-verPedidos btnEntregado-verPedidos btnEntregadoPedido" data-id="<?php echo $pedido; ?>" href="#">ENTREGADO <i class="fas fa-check-circle"></i></a>

	<!-- <a class="botones-verPedidos btnCuentaCorriente-verPedidos" data-id="<?php echo $pedido; ?>" href="#">CUENTA CORRIENTE <i class="fas fa-file-invoice-dollar"></i></a> -->

	<!-- <a class="botones-verPedidos btnAgregar-verPedidos" href="?verPedido=<?php echo $pedido; ?>&modif">MODIF VENTA <i class="fas fa-notes-medical"></i></a> -->

<?php

}

?>

</div>

<?php

}

} else {

?>

<style type="text/css">

.btnOpcionesVerPedidos {

  margin: 0 20px 20px 20px;

  padding: 10px 30px;

  border:2px solid #322783;

  border-radius: 5px;

  cursor: pointer;

  font-weight: bold;

  letter-spacing: 2px;

  color: #322783;

}

.PENDIENTES:hover{

  background: #ffd1b3;

  color: #ff6600;

  border: 2px solid #ff6600;

}

.COMPLETOS:hover{

  background: #e6ffe6;

  color: #006600;

  border: 2px solid #006600;

}

.CANCELADOS:hover{

  background: #ffcccc;

  color: #cc0000;

  border: 2px solid #cc0000;

}

.CUENTACORRIENTE:hover{

background: #f2e6ff;

color: #7300e6;

border: 2px solid #7300e6;

}

.estilosTablaTD {

  padding: 10px 10px 10px 20px;

  color: white;

  font-weight: bold;

  cursor: pointer;

  text-transform: uppercase;

  font-size:18px;

}

#contenido_completos td{

  background: green;

}

#contenido_cancelados td{

  background: #b30000;

}

#contenido_cuenta_corriente td {
	background: #7300e6;
}

.break {

  height: 4px;

}

</style>

<div id="contenido">

	<button onclick="show('pendientes');" class="btnOpcionesVerPedidos PENDIENTES"><i class="fas fa-clock"></i> PENDIENTES</button>

	<button onclick="show('completos');" class="btnOpcionesVerPedidos COMPLETOS"><i class="fas fa-check-circle"></i> COMPLETOS</button>

	<button onclick="show('cancelados');" class="btnOpcionesVerPedidos CANCELADOS"><i class="fas fa-times-circle"></i> CANCELADOS</button>

	<!-- <button onclick="show('cuenta_corriente');" class="btnOpcionesVerPedidos CUENTACORRIENTE"><i class="fas fa-file-invoice-dollar"></i> CUENTA CORRIENTE</button> -->

<!-- DIV PENDIENTES -->

<div id="contenido_pendientes" style="display: block;" class="tablaVerPedidos">

<?php include './PENDIENTES/index.php'; ?>

</div>

<!-- FIN DIV PENDIENTES -->

<!-- DIV COMPLETOS -->

<div id="contenido_completos" style="display: none;" class="tablaVerPedidos estilosTablaTD">

	<table align="center" width="80%" cellspacing="0" cellpadding="0">

		<?php

		$consulta_pedidos = $conexion->query("SELECT * FROM ventascliente WHERE estado = '1' ORDER BY fecha DESC");

		include('./php/ver_pedidos-categorias.php');

		?>

	</table>

</div>

<!-- FIN DIV COMPLETOS -->

<!-- DIV CANCELADOS -->

<div id="contenido_cancelados" style="display: none;" class="tablaVerPedidos estilosTablaTD">

	<table align="center" width="80%" cellspacing="0" cellpadding="0">

	<?php

		$consulta_pedidos = $conexion->query("SELECT * FROM ventascliente WHERE estado = '2' ORDER BY fecha DESC");

		include('./php/ver_pedidos-categorias.php');

		?>

	</table>

</div>

<!-- FIN DIV CANCELADOS -->

<!-- DIV CUENTA CORRIENTE -->

<!-- <div id="contenido_cuenta_corriente" style="display: none;" class="tablaVerPedidos estilosTablaTD">

	<table align="center" width="80%" cellspacing="0" cellpadding="0">

	<?php

		// $consulta_pedidos = $conexion->query("SELECT * FROM ventascliente WHERE estado = '3' ORDER BY fecha DESC");

		// include('./php/ver_pedidos-categorias.php');

		?>

	</table>

</div> -->

<!-- FIN DIV CUENTA CORRIENTE -->

</div>

<?php

}

?>

</body>

</html>

<script>

//TOGGLE SALDO FAVOR SELECCIONAR MONTO A GASTAR

var seleccionados = 0

$("#utilizarSaldoFavor").change(function() {

seleccionados = $("#utilizarSaldoFavor:checked").length

$("#monto_saldoFavor").toggle(seleccionados > 0)})



$(document).ready(function(){

	$(".btnCancelarPedido").click(function(e){

		var id = $(this).data("id");

		e.preventDefault();

		var mensaje = confirm("¿Seguro que quiere cancelar este pedido?");

		if (mensaje) {

			$.ajax({

				method:"GET",

				url:'./PEDIDOS/cancelarpedido.php?cancelar='+id,

				success:function(){

					alert("PEDIDO CANCELADO EXITOSAMENTE");

	            	window.location="ver_pedidos.php";

	            }

			});

		}

	});

});

$(document).ready(function(){

	$(".btnEntregadoPedido").click(function(event){

		var id = $(this).data("id");

		var saldoFavor = [];

		var monto_saldoFavor = [];

		var ganancia = $("#ganancia").val();

		event.preventDefault();

		$(":checkbox[name=utilizarSaldoFavor]").each(function() {

      		if (this.checked) {

        		saldoFavor.push($(this).val());

						monto_saldoFavor.push($(".monto_saldoFavor").val());

      		}

    	});

		var mensaje = confirm("¿Seguro que entrego este pedido?");

		if (mensaje) {

			$.ajax({

			method:"POST",

			url:'./PEDIDOS/finalizarpedido.php',

			data: ('entregado='+id+'&saldoFavor='+saldoFavor+'&montoSaldoFavor='+monto_saldoFavor+'&ganancia='+ganancia),

			success:function(){

				alert("PEDIDO ENTREGADO EXITOSAMENTE");

	            window.location="ver_pedidos.php";

	        }

			});

		}

	});

});

function show(elemento){

	pendientes = document.getElementById('contenido_pendientes');

	cancelados = document.getElementById('contenido_cancelados');

	completos = document.getElementById('contenido_completos');

	cuenta_corriente = document.getElementById('contenido_cuenta_corriente');

	switch(elemento){

		case 'pendientes':

			pendientes.style.display = 'block';

			cancelados.style.display = 'none';

			completos.style.display = 'none';

			cuenta_corriente.style.display = 'none';

			break;

		case 'completos':

			if (completos.style.display == 'block') {

				completos.style.display = 'none';

				pendientes.style.display = 'block';

			} else {

				completos.style.display = 'block';

				cancelados.style.display = 'none';

				pendientes.style.display = 'none';
				cuenta_corriente.style.display = 'none';

			}

			break;
		case 'cuenta_corriente':
			if (cuenta_corriente.style.display == 'block') {
				cuenta_corriente.style.display = 'none';
				pendientes.style.display = 'block';
			} else {
				cuenta_corriente.style.display = 'block';
				completos.style.display = 'none';
				cancelados.style.display = 'none';
				pendientes.style.display = 'none';
			}
		break;
		case 'cancelados':

			if (cancelados.style.display == 'block') {

				cancelados.style.display = 'none';

				pendientes.style.display = 'block';

			} else {

				cancelados.style.display = 'block';

				pendientes.style.display = 'none';

				completos.style.display = 'none';

				cuenta_corriente.style.display = 'none';

			}

			break;

	}

}

</script>

<?php

} else {

	header("Location: ../index.php");

}

?>

