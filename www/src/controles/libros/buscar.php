<?php
require_once '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'variables.php';
require_once SESION;
@Sesion::iniciarSesion();
require_once CONEXION;
$error = false;
if(isset($_GET) and !empty($_GET)){
	if(isset($_GET['chk_condicion']) and !empty($_GET['chk_condicion']) and $_GET['chk_condicion'] == 'on')
		$condicion = ' AND';
	else 
		$condicion = ' OR';

	$strQuery = 'SELECT * FROM libros WHERE ';

	if(isset($_GET['chk_codigo']) and !empty($_GET['chk_codigo']) and $_GET['chk_codigo'] == 'on')
		if(isset($_GET['codigo']) and !empty($_GET['codigo']))
			$strQuery .= "LOWER(codigo) LIKE LOWER('%".$_GET['codigo']."%')";

	if(isset($_GET['chk_autor']) and !empty($_GET['chk_autor']) and $_GET['chk_autor'] == 'on')
		if(isset($_GET['autor']) and !empty($_GET['autor']))
			if(isset($_GET['codigo']) and !empty($_GET['codigo']))
				$strQuery .= $condicion." LOWER(autor) LIKE LOWER('%".$_GET['autor']."%')";
			else
				$strQuery .= "LOWER(autor) LIKE LOWER('%".$_GET['autor']."%')";

	if(isset($_GET['chk_titulo']) and !empty($_GET['chk_titulo']) and $_GET['chk_titulo'] == 'on')	
		if(isset($_GET['titulo']) and !empty($_GET['titulo']))
			if(isset($_GET['autor']) and !empty($_GET['autor']))
				$strQuery .= $condicion." LOWER(titulo) LIKE LOWER('%".$_GET['titulo']."%')";			
			else
				$strQuery .= "LOWER(titulo) LIKE LOWER('%".$_GET['titulo']."%')";			

	if(isset($_GET['chk_editorial']) and !empty($_GET['chk_editorial']) and $_GET['chk_editorial'] == 'on')		
		if(isset($_GET['editorial']) and !empty($_GET['editorial']))	
			if(isset($_GET['titulo']) and !empty($_GET['titulo']))
				$strQuery .= $condicion." LOWER(editorial) LIKE LOWER('%".$_GET['editorial']."%')";
			else
				$strQuery .= "LOWER(editorial) LIKE LOWER('%".$_GET['editorial']."%')";

	$conexion = new Conexion($database);
	$resultados = $conexion->seleccionarDatos($strQuery);	
	$conexion->cerrarConexion();

	if($resultados and !empty($resultados))
		include_once VISTAS.DS.'libros'.DS.'lista.php';
	else 
		$error = true;

} else {
	$error = true;
}

if ($error){
	Sesion::setValor('error', $warnings['VACIO']);
	header('Location: '.VISTAS_HTML.'/libros/buscar.php');
}