<?php
require_once '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'variables.php';
require_once SESION;
@Sesion::iniciarSesion();

if(Sesion::existe('usuario')){	
	$error = false;
	if(isset($_GET) and !empty($_GET)){
		if(isset($_GET['id']) and !empty($_GET['id'])){
			require_once CONEXION;
			$strQuery = "SELECT id FROM libros WHERE id=".$_GET['id'];
			$conexion = new Conexion($database);	
			if(count($conexion->seleccionarDatos($strQuery)) > 0){
				$strQuery = sprintf("INSERT INTO cotas VALUES (default, %d, default)", $_GET['id']);
				if($conexion->agregarRegistro($strQuery))
					Sesion::setValor('success', $warnings['COTA_AGREGADA']);						
				else
					Sesion::setValor('error', $warnings['NO_AGREGADO']);
				header('Location: '.CONTROL_HTML.'/libros/ver.php?libro_id='.$_GET['id']);	
				$conexion->cerrarConexion();						
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
		header('Location: '.CONTROL_HTML.'/libros/ver.php?libro_id='.$_GET['id']);						
	}
} else {
	Sesion::setValors('error', $warnings['SIN_PERMISOS']);
	header('Location: '.ROOT_HTML);		
}