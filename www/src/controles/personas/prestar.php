<?php
require_once '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'variables.php';
require_once SESION;
@Sesion::iniciarSesion();
if(Sesion::existe('usuario')){
	$error = false;
	if(isset($_GET) and !empty($_GET)){
		if((isset($_GET['libro_id']) and !empty($_GET['libro_id'])) and (isset($_GET['cedula']) and !empty($_GET['cedula'])) and (isset($_GET['ejemplar']) and !empty($_GET['ejemplar']))){
			require_once CONEXION;
			//verifico si este prestamo ya se realizo y no se ha devuelto
			$strQuery = sprintf("SELECT pr.id FROM prestamos pr JOIN personas p ON pr.persona_id=p.id WHERE pr.fecha_entregado IS NULL AND p.cedula='%s' AND pr.ejemplar_id=%d",$_GET['cedula'], $_GET['ejemplar']);
			$conexion = new Conexion($database);
			$resultados = $conexion->seleccionarDatos($strQuery);
			if(!(count($resultados) > 0)){
				$strQuery = sprintf("SELECT id FROM personas WHERE cedula='%s'", $_GET['cedula']);				
				$resultados = $conexion->seleccionarDatos($strQuery);	
				if(count($resultados) > 0){
					$persona = $resultados[0]['id'];
					//verifico si se encuentra suspendido
					$strQuery = sprintf("SELECT p.id FROM personas p JOIN suspendidos s ON p.id=s.persona_id WHERE p.cedula='%s' AND s.hasta > CURDATE()", 
						$_GET['cedula']);				
					$resultados = $conexion->seleccionarDatos($strQuery);	
					if(count($resultados) > 0){	
						//Se redirige a la vista del libro sin realizar prestamo debido a la suspención.
						Sesion::setValor('error', $warnings['SUSPENDIDO']);
						header('Location: '.CONTROL_HTML.'/libros/ver.php?libro_id='.$_GET['libro_id']);
					} else {
						//actualizo la lista de ejemplares
						$strQuery = sprintf('UPDATE ejemplares SET disponible=0 WHERE id=%d and libro_id=%d and disponible=1', $_GET['ejemplar'], $_GET['libro_id']);
						if($conexion->actualizarDatos($strQuery)){
							$strQuery = sprintf("INSERT INTO prestamos VALUES (default, %d, %d, %d, CURDATE(), DATE_ADD(CURDATE(), INTERVAL %d DAY), NULL)", 
												$persona, Sesion::getValor('usuario')['id'], $_GET['ejemplar'], TIEMPO_PRESTAMO);					
							if($conexion->agregarRegistro($strQuery)){
								$prestamo = $conexion->ultimoID();
								$strQuery = sprintf("SELECT p.*, u.nombres AS nombre_usuario, u.apellidos AS apellido_usuario, c.nombre AS ejemplar FROM prestamos p JOIN ejemplares c ON p.ejemplar_id=c.id JOIN usuarios u ON p.usuario_id=u.id WHERE p.id=%d", $prestamo);
								$resultados = $conexion->seleccionarDatos($strQuery);				
								$prestamo = $resultados[0];
								$strQuery = sprintf("SELECT l.* FROM libros l JOIN ejemplares c ON l.id=c.libro_id WHERE c.id=%d", $prestamo['ejemplar_id']);
								$resultados = $conexion->seleccionarDatos($strQuery);
								$libro = $resultados[0];
								$strQuery = sprintf("SELECT p.*, t.nombre AS tipo FROM personas p JOIN tipo_personas t ON p.tipo_persona_id=t.id WHERE p.id=%d", $prestamo['persona_id']);
								$resultados = $conexion->seleccionarDatos($strQuery);
								$persona = $resultados[0];

								$strQuery = "SELECT nombre FROM tipo_personas";
								$resultados = $conexion->seleccionarDatos($strQuery);
								if(count($resultados) > 0)
									$tipoPersonas = $resultados;
								else 
									$tipoPersonas = false;
								include_once VISTAS.DS.'personas'.DS.'prestado.php';
							} else {
								$error = true;
							}
						} else {
							$error = true;								
						}
						$conexion->cerrarConexion();	
					}			
					if($error){
						Sesion::setValor('error', $warnings['NO_PRESTAMO']);
						header('Location: '.CONTROL_HTML.'/libros/prestar.php?libro_id='.$_GET['libro_id']);									
					}
				} else {		
					$error = true;
				}
			} else {
				Sesion::setValor('error', $warnings['NO_PRESTAMO']);
				header('Location: '.CONTROL_HTML.'/libros/prestar.php?libro_id='.$_GET['libro_id']);				
			}		
		} else {
			$error = true;		
		}
	} else {
		$error = true;
	}

	if($error){
		Sesion::setValor('error', $warnings['CEDULA_NO_ENCONTRADA']);
		header('Location: '.CONTROL_HTML.'/libros/prestar.php?libro_id='.$_GET['libro_id'].'&ejemplar='.$_GET['ejemplar']);
	}

} else {
	Sesion::setValor('error', $warnings['SIN_PERMISOS']);
	header('Location: '.ROOT_HTML);	
}