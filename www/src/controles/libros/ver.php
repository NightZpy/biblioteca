<?php
require_once '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'variables.php';
require_once SESION;
@Sesion::iniciarSesion();
require_once CONEXION;

$error = false;
if(isset($_GET) and !empty($_GET)){
	$strQuery = 'SELECT l.id, l.titulo, l.autor, l.editorial, l.codigo, l.ejemplar, l.descripcion, l.fecha_ingreso, c.nombre AS categoria FROM libros l JOIN categorias c ON l.categoria_id=c.id WHERE l.id=';

	if(isset($_GET['id']) and !empty($_GET['id']))
		$strQuery .= $_GET['id'];

	$conexion = new Conexion($database);
	$resultados = $conexion->seleccionarDatos($strQuery);	
	$conexion->cerrarConexion();

	if($resultados and !empty($resultados)){
		$libro = $resultados[0];
		include_once VISTAS.DS.'libros'.DS.'ver.php';
	} else {
		$error = true;
	}
} else {
	$error = true;
}	

if($error){
	Sesion::setValor('error', $warnings['VACIO']);
	header('Location: '.VISTAS_HTML.'/libros/buscar.php');
}