<?php
require_once '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'variables.php';
require_once SESION;
@Sesion::iniciarSesion();
if(Sesion::existe('usuario')){
	$error = false;
	if(isset($_GET) and !empty($_GET)){
		if(isset($_GET['id']) && !empty($_GET['id'])){
			//Devuelvo el libro poniendo la fecha en que se entrego
			require_once CONEXION;
			$strQuery = sprintf('UPDATE prestamos SET fecha_entregado=CURDATE() WHERE id=%d AND fecha_entregado IS NULL', $_GET['id']);
			$conexion = new Conexion($database);
			$resultado = $conexion->actualizarDatos($strQuery);
			if($resultado){
				//verifico si entrego tarde y por tanto se suspende
				$ultimoID = $conexion->ultimoID();
				$strQuery = sprintf('SELECT * FROM prestamos WHERE id=%d AND fecha_entrega < fecha_entregado', $_GET['id']);
				$resultados = $conexion->seleccionarDatos($strQuery);
				if(count($resultados)>0){
					//se realiza la suspención
					$prestamo = $resultados[0];
					$strQuery = sprintf('INSERT INTO `suspendidos`(`id`, `cota_id`, `persona_id`, `desde`, `hasta`) 
										VALUES (default, %d, %d, CURDATE(), DATE_ADD(CURDATE(), INTERVAL %d DAY))', $prestamo['cota_id'], $prestamo['persona_id'], TIEMPO_SUSPENSION);									
					$resultado = $conexion->agregarRegistro($strQuery);
					if($resultado){
						Sesion::setValor('suspendido', $warnings['SUSPENDIDO']);	
						Sesion::setValor('success', $warnings['DEVUELTO']);	
					} else {
						Sesion::setValor('success', $warnings['DEVUELTO']);	
					}
				} else {
					Sesion::setValor('success', $warnings['DEVUELTO']);
				}
			} else {
				$error = true;
			}
		} else {
			$error = true;
		}
	} else {
		$error = true;
	}
	if($error){
		Sesion::setValor('error', $warnings['SIN_PRESTAMO']);		
	}			
	header('Location: '.CONTROL_HTML.'/personas/ver.php?cedula='.$_GET['cedula'].'&nacionalidad='.$_GET['nacionalidad']);
} else {
	Sesion::setValor('error', $warnings['SIN_PERMISOS']);
	header('Location: '.ROOT_HTML);	
}