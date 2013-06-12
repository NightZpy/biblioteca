<?php
require_once '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'variables.php';
require_once SESION;
@Sesion::iniciarSesion();

$error = false;
if(isset($_GET) and !empty($_GET)){
	$strQuery = 'SELECT l.id, l.titulo, l.autor, l.editorial, l.isbn, l.descripcion, l.fecha_ingreso, c.nombre AS categoria FROM libros l JOIN categorias c ON l.categoria_id=c.id WHERE l.id=';

	if(isset($_GET['libro_id']) and !empty($_GET['libro_id'])){
		require_once CONEXION;
		$strQuery .= $_GET['libro_id'];
		$conexion = new Conexion($database);
		$resultados = $conexion->seleccionarDatos($strQuery);				
		if(count($resultados)>0){
			$libro = $resultados[0];
			$resultados = $conexion->seleccionarDatos("SELECT COUNT(*) AS cantidad FROM ejemplares WHERE libro_id=".$_GET['libro_id']);
			if(count($resultados)>0)					
				$copias = $resultados[0]['cantidad'];
			else
				$copias = 0;
			$resultados = $conexion->seleccionarDatos("SELECT id, nombre FROM ejemplares WHERE disponible=1 AND libro_id=".$_GET['libro_id']);
			if(count($resultados)>0){					
				$ejemplaresDisponibles = count($resultados);
				$ejemplares = $resultados;
			} else {
				$ejemplaresDisponibles = 0;
				$ejemplares = false;					
			}
			$ejemplaresPrestadas = $copias - $ejemplaresDisponibles;
			include_once VISTAS.DS.'libros'.DS.'ver.php';
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
	header('Location: '.CONTROL_HTML.'/libros/buscar.php');
}