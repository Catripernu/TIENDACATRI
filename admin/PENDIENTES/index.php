<script language="javascript" src="./PENDIENTES/js/jquery-1.7.2.min.js"></script>
<script language="javascript">
	function tiempoReal(){
		var tabla = $.ajax({
			url:'./PENDIENTES/mensajes.php',
			dataType:"text",
			async:false
		}).responseText;
		document.getElementById("div1").innerHTML = tabla;
	}
	setInterval(tiempoReal,1000);
</script>

<div id="div1" style="width:100%; float:left;">
</div>