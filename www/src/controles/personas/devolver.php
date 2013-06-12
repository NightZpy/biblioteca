<?php
require_once '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'variables.php';
require_once SESION;
@Sesion::iniciarSesion();
if(Sesion::existe('usuario')){
	$error = false;
	if(isset($_GET) and !empty($_GET)){
		if(isset($_GET['prestamo_id']) && !empty($_GET['prestamo_id'])){
			//Devuelvo el libro poniendo la fecha en que se entrego
			require_once CONEXION;
			$strQuery = sprintf('UPDATE prestamos SET fecha_entregado=CURDATE() WHERE id=%d AND fecha_entregado IS NULL', $_GET['prestamo_id']);
			$conexion = new Conexion($database);
			$resultado = $conexion->actualizarDatos($strQuery);
			if($resultado){
				//Activo de nuevo la ejemplar (copia del libro), para que pueda ser prestada de nuevo.
				$strQuery = sprintf("UPDATE ejemplares SET disponible=1 WHERE id=%d", $_GET['ejemplar_id']);
				$conexion->actualizarDatos($strQuery);				

				//verifico si entrego tarde y por tanto se suspende
				/*$strQuery = sprintf('SELECT * FROM prestamos WHERE id=%d AND fecha_entrega < fecha_entregado', $_GET['prestamo_id']);
				$resultados = $conexion->seleccionarDatos($strQuery);
				if(count($resultados)>0){
					//se realiza la suspención
					$prestamo = $resultados[0];
					$strQuery = sprintf('INSERT INTO `suspendidos`(`id`, `ejemplar_id`, `persona_id`, `desde`, `hasta`) 
										VALUES (default, %d, %d, CURDATE(), DATE_ADD(CURDATE(), INTERVAL %d DAY))', $prestamo['ejemplar_id'], $prestamo['persona_id'], TIEMPO_SUSPENSION);									
					$resultado = $conexion->agregarRegistro($strQuery);
					if($resultado){
						Sesion::setValor('suspendido', $warnings['SUSPENDIDO']);	
						Sesion::setValor('success', $warnings['DEVUELTO']);	
					} else {
						Sesion::setValor('success', $warnings['DEVUELTO']);	
					}
				} else {
					Sesion::setValor('success', $warnings['DEVUELTO']);
				}*/
				Sesion::setValor('success', $warnings['DEVUELTO']);
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