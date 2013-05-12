<?php
require_once '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'variables.php';
require_once SESION;
require_once SESION;
@Sesion::iniciarSesion();
require_once CONEXION;

$error = false;
if(isset($_GET) and !empty($_GET)){
	if((isset($_GET['libro']) && !empty($_GET['libro'])) && (isset($_GET['cedula']) && !empty($_GET['cedula']))){
		$strQuery = sprintf("SELECT id FROM personas WHERE cedula='%s'", $_GET['cedula']);	
		$conexion = new Conexion($database);
		$resultados = $conexion->seleccionarDatos($strQuery);	
		if(count($resultados) > 0){
			$persona = $resultados[0];
			$strQuery = sprintf("SELECT p.id FROM personas p JOIN suspendidos s ON p.id=s.persona_id WHERE p.cedula='%s' AND s.hasta > CURDATE()", 
				$_GET['cedula']);	
			$conexion = new Conexion($database);
			$resultados = $conexion->seleccionarDatos($strQuery);	
			if(count($resultados) > 0){			
				Sesion::setValor('error', $warnings['SUSPENDIDO']);
				header('Location: '.VISTAS_HTML.'/libros/ver.php?id='.$_GET['libro']);
			}
			$strQuery = sprintf("INSERT INTO prestamos VALUES (default, %d, %d, CURDATE(), DATE_ADD(CURDATE(), INTERVAL %d DAY))", 
								$persona, $_GET['libro'], TIEMPO_PRESTAMO);	
			$resultados = $conexion->agregarRegistro($strQuery);		;
			$prestamo = $conexion->ultimoID();
			if($resultados and !empty($resultados)){
				$strQuery = sprintf("SELECT * FROM prestamos WHERE id=%d", $prestamo);
				$resultados = $conexion->seleccionarDatos($strQuery);				
				$prestamo = $resultados[0];
				$strQuery = sprintf("SELECT * FROM libros WHERE id=%d", $prestamo['libro_id']);
				$resultados = $conexion->seleccionarDatos($strQuery);
				$libro = $resultados[0];
				$strQuery = sprintf("SELECT * FROM personas WHERE id=%d", $prestamo['persona_id']);
				$resultados = $conexion->seleccionarDatos($strQuery);
				$persona = $resultados[0];

				include_once VISTAS.DS.'personas'.DS.'prestado.php';
			} 
			$conexion->cerrarConexion();				
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
	Sesion::setValor('error', $warnings['CEDULA_NO_ENCONTRADA']);
	header('Location: '.CONTROL_HTML.'/libros/prestar.php?id='.$_GET['libro']);
}