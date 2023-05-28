<?php 

function datoVendedor($vendedor){
	$dato = $GLOBALS['conexion']->query("SELECT nombre, apellido FROM users WHERE id = $vendedor")->fetch_array();
	return $dato['nombre']; 
}

function vistas_registros_agendas($rol,$id,$palabra){
	if(isset($palabra) && !empty($palabra)){
		$query_admin = $GLOBALS['conexion']->query("SELECT * FROM agenda WHERE nombre_agenda LIKE '%$palabra%' or apellido_agenda LIKE '%$palabra%' or direccion_agenda LIKE '%$palabra%' ORDER BY id_agenda DESC");
		$query_vendedor = $GLOBALS['conexion']->query("SELECT * FROM agenda WHERE id_vendedor = $id and nombre_agenda LIKE '%$palabra%' or apellido_agenda LIKE '%$palabra%' or direccion_agenda LIKE '%$palabra%'");
	} else {
		$query_admin = $GLOBALS['conexion']->query("SELECT * FROM agenda ORDER BY id_agenda DESC");
		$query_vendedor = $GLOBALS['conexion']->query("SELECT * FROM agenda WHERE id_vendedor = $id");
	}
	$vendedor = $GLOBALS['conexion']->query("SELECT * FROM users WHERE id = '$id' and rol = '2'")->num_rows;
		if($vendedor){
				echo "<thead>
					<th>ID</th>
					<th>CLIENTE</th>
					<th colspan='2'>DOMICILIO</th>
					<th>TELEFONO</th>
					<th>FECHA</th>
					<th>OPCIONES</th>
				</thead>";
				foreach ($query_vendedor as $dato) {
					echo $vista ="<tr><td>#".$dato['id_agenda']."</td>
							<td>".$dato['nombre_agenda']." ".$dato['apellido_agenda']."</td>
							<td colspan='2'>".$dato['direccion_agenda']."</td>
							<td><a href='https://api.whatsapp.com/send?phone=54".$dato['telefono_agenda']."&text=Hola!%20me%20comunico%20con%20".$dato['nombre_agenda'].",%20somos%20DjDistribuciones.com.ar'>".$dato['telefono_agenda']."</a></td>
							<td>".$dato['fecha_agenda']."</td>
							<td><button data-edit class='add_cliente edit' data-id='".$dato['id_agenda']."' data-nombre='".$dato['nombre_agenda']."' data-apellido='".$dato['apellido_agenda']."' data-direccion='".$dato['direccion_agenda']."' data-telefono='".$dato['telefono_agenda']."'>Edit</button>
							<button data-delet data-id='".$dato['id_agenda']."' class='add_cliente delet'>X</button></td>
							</tr>";
				}
		} else {
			echo "<thead>
					<th>ID</th>
					<th>CLIENTE</th>
					<th>DOMICILIO</th>
					<th>TELEFONO</th>
					<th>FECHA</th>
					<th>VENDEDOR</th>
					<th>OPCIONES</th>
				</thead>";
			foreach ($query_admin as $dato) {	
				echo "<tr><td>#".$dato['id_agenda']."</td>
						<td>".$dato['nombre_agenda']." ".$dato['apellido_agenda']."</td>
						<td>".$dato['direccion_agenda']."</td>
						<td><a href='https://api.whatsapp.com/send?phone=54".$dato['telefono_agenda']."&text=Hola!%20me%20comunico%20con%20".$dato['nombre_agenda'].",%20somos%20DjDistribuciones.com.ar'>".$dato['telefono_agenda']."</a></td>
						<td>".$dato['fecha_agenda']."</td>
						<td>".datoVendedor($dato['id_vendedor'])."</td>
						<td><button data-edit class='add_cliente edit' data-id='".$dato['id_agenda']."' data-nombre='".$dato['nombre_agenda']."' data-apellido='".$dato['apellido_agenda']."' data-direccion='".$dato['direccion_agenda']."' data-telefono='".$dato['telefono_agenda']."'>Edit</button>
						<button data-delet data-id='".$dato['id_agenda']."' class='add_cliente delet'>X</button></td>
						</tr>";	
			}
		}
}


function registrarClienteAgenda($nombre,$apellido,$direccion,$telefono,$fecha,$id_vendedor,$rol){
	if ($rol == 2) {
		$sql = "INSERT INTO agenda (id_agenda,nombre_agenda,apellido_agenda,telefono_agenda,direccion_agenda,fecha_agenda,id_vendedor) VALUES ('','$nombre','$apellido','$telefono','$direccion','$fecha',$id_vendedor)";
	} else {
		$sql = "INSERT INTO agenda (id_agenda,nombre_agenda,apellido_agenda,telefono_agenda,direccion_agenda,fecha_agenda,id_vendedor) VALUES ('','$nombre','$apellido','$telefono','$direccion','$fecha',0)";
	}
	if(strlen($nombre) > 3){
			if ($GLOBALS['conexion']->query($sql) === TRUE) {
			  $message = "<b class='success'>CLIENTE REGISTRADO EXITOSAMENTE</b>";
			} else {
			  $message = "Error: " . $sql . "<br>" . $GLOBALS['conexion']->error;
			}			
		} else {
			$message = "<b class='error'>NOMBRE DEBE CONTAR CON MAS DE 3 CARACTERES.</b>";
		}
		return $message;
}

function actualizarClienteAgenda($id,$nombre,$apellido,$direccion,$telefono,$fecha,$id_vendedor,$rol){
	$vendedor = $GLOBALS['conexion']->query("SELECT id_vendedor FROM agenda WHERE id_agenda = $id and id_vendedor = $id_vendedor")->num_rows;
	if ($vendedor || $rol == 1) {
		$dato = $GLOBALS['conexion']->query("SELECT * FROM agenda WHERE id_agenda = $id")->fetch_assoc();
		if ($nombre == $dato['nombre_agenda'] && 
			$apellido == $dato['apellido_agenda'] &&
			$direccion == $dato['direccion_agenda'] &&
			$telefono == $dato['telefono_agenda']
			) {
				$message = "<b class='error'>NO SE MODIFICO NINGUN PARAMETRO.</b>";
		} else {
			if(strlen($nombre) > 3){
				$sql = "UPDATE agenda SET nombre_agenda= '$nombre',apellido_agenda='$apellido',direccion_agenda='$direccion',telefono_agenda= '$telefono',fecha_agenda= '$fecha' WHERE id_agenda = '$id'";

				if ($GLOBALS['conexion']->query($sql) === TRUE) {
					  $message = "<b class='success'>CLIENTE ACTUALIZADO EXITOSAMENTE</b>";
					} else {
					  $message = "Error: " . $sql . "<br>" . $GLOBALS['conexion']->error;
					}		
			} else {
				$message = "<b class='error'>NOMBRE DEBE CONTAR CON MAS DE 3 CARACTERES.</b>";
			}
		}
	} else {
		$message = "<b class='error'>ESTE CLIENTE NO EXISTE EN TU LISTA.</b>";
	}
	return $message;
}