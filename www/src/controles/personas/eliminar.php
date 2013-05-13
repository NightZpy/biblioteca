<?php
require_once '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'variables.php';
require_once SESION;
@Sesion::iniciarSesion();
if(Sesion::existe('usuario')){
	require_once CONEXION;
	$conexion = new Conexion($database);
	$id = $_GET['id'];
	if(is_numeric($id)){	
		$strQuery = "DELETE FROM personas WHERE id=$id";		
		$resultado = $conexion->agregarRegistro($strQuery);
		$conexion->cerrarConexion();
		if($resultado)
			Sesion::setValor('success', $warnings['ELIMINADO']);
		else 
			Sesion::setValor('error', $warnings['NO_ELIMINADO']);
		header('Location: '.VISTAS_HTML.'/personas/buscar.php');		
	}
} else {
	Sesion::setValor('error', $warnings['SIN_PERMISOS']);
	header('Location: '.ROOT_HTML);	
}