<?php 

include '../../config.php';

if (isset($_POST)) {
	if ($_POST['action'] == "agregarNota") {
		$id_pedido = $_POST['pedido'];
		
		echo "AGREGAR NOTA";
		exit;
	}
}
exit;
?>