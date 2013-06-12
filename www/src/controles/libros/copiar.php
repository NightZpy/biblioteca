<?php
require_once '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'variables.php';
require_once SESION;
@Sesion::iniciarSesion();

if(Sesion::existe('usuario')){	
	$error = false;
	if(isset($_GET) and !empty($_GET)){
		if(isset($_GET['libro_id']) and !empty($_GET['libro_id'])){
			//verifico si el libro existe
			require_once CONEXION;
			$strQuery = "SELECT id FROM libros WHERE id=".$_GET['libro_id'];
			$conexion = new Conexion($database);	
			if(count($conexion->seleccionarDatos($strQuery)) > 0){
				// Verifico la ultima cota guardada de este libro
				$strQuery = "SELECT MAX(nombre) AS nombre FROM ejemplares WHERE libro_id=".$_GET['libro_id'];
				$resultados = $conexion->seleccionarDatos($strQuery);
				if(count($resultados) > 0){
					$ultimaCota = $resultados[0]['nombre'] + 1;
					//Guardo la nueva cota
					$strQuery = sprintf("INSERT INTO ejemplares VALUES (default, %d, %d, default)", $ultimaCota, $_GET['libro_id']);
					if($conexion->agregarRegistro($strQuery))
						Sesion::setValor('success', $warnings['COTA_AGREGADA']);						
					else
						Sesion::setValor('error', $warnings['NO_AGREGADO']);
					header('Location: '.CONTROL_HTML.'/libros/ver.php?libro_id='.$_GET['libro_id']);	
				} else {
					$error = true;
				}										
			} else {
				$error = true;
			}
			$conexion->cerrarConexion();		
		} else {
			$error = true;
		}
	} else {
		$error = true;
	}	

	if($error){
		Sesion::setValor('error', $warnings['VACIO']);
		header('Location: '.CONTROL_HTML.'/libros/ver.php?libro_id='.$_GET['libro_id']);						
	}
} else {
	Sesion::setValors('error', $warnings['SIN_PERMISOS']);
	header('Location: '.ROOT_HTML);		
}