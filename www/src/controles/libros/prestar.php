<?php
require_once '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'variables.php';
require_once SESION;
@Sesion::iniciarSesion();
require_once CONEXION;
if(Sesion::existe('usuario')){
	$error = false;
	if(isset($_GET) and !empty($_GET)){
		$strQuery = 'SELECT id, titulo, autor, codigo FROM libros WHERE id=';
		if(isset($_GET['id']) and !empty($_GET['id']))
			$strQuery .= $_GET['id'];

		$conexion = new Conexion($database);
		$resultados = $conexion->seleccionarDatos($strQuery);	
		$conexion->cerrarConexion();

		if($resultados and !empty($resultados)){
			$libro = $resultados[0];
			include_once VISTAS.DS.'personas'.DS.'prestar.php';
		} else {
			$error = true;
		}
	} else {
		$error = true;
	}	

	if($error){
		Sesion::setValor('error', $warnings['VACIO']);
		header('Location: '.VISTAS_HTML.'/libros/ver.php?id='.$_GET['id']);
	}
} else {
	Sesion::setValor('error', $warnings['SIN_PERMISOS']);
	header('Location: '.ROOT_HTML);	
}