<?php include("../php/includes_user.php");
if(isset($_SESSION['loggedin']) && $_SESSION['rol'] == 0){
	$id = $_SESSION['id'];
	?>
<body>
<div id="contenido">
		<?php
		$num_paginas = ceil(consulta_ventas_usuario($_SESSION['id'])->rowCount() / $compras_resultados_por_pagina);
		$pagina = (isset($_GET['pagina'])) ? $_GET['pagina'] : 1 ;
		if(!isset($_GET['compra'])){
			echo '<div class="user_compras">';
			foreach(resultados_paginacion($compras_resultados_por_pagina, $pagina, "ventascliente", "id_usuario = $id ORDER BY fecha DESC") as $d){
				$fecha = formato_fecha($d['fecha']);
				$estado = ($d['estado'] == 0) ? "orange" : (($d['estado'] == 1) ? "green" : "red"); ?>
				<div class="compras" <?=borde_compras($estado)?>>
					<p title="FECHA DE LA COMPRA"><?=$fecha?></p>
					<p title="MONTO TOTAL"><?=formato_precio($d['total'])?></p>
					<a href="compras.php?compra=<?=$d['ID']?>" title="VER PEDIDO"><i class="fa fa-list-alt fa-lg fa-fw"></i></a>
				</div>
			<?php }
			echo '</div>';
			paginacion(false,$pagina,$num_paginas,false);
		} else {
			compras_detalles($_GET['compra']); ?>
			<input class="btn_atras btn_morado" onclick="history.back()" type="button" value="VOLVER ATRAS">
		<?php } ?>
</div>  
</body>
</html>
<?php } else {header("location: ../index.php");} ?>