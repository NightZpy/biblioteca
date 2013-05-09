<?php
require_once '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'variables.php';
require_once SESION;
@Sesion::iniciarSesion();
require_once CONEXION;

if(isset($_POST) and !empty($_POST)){	
	if((isset($_POST['usuario']) and !empty($_POST['usuario'])) and (isset($_POST['password']) and !empty($_POST['password']))){
		$strQuery = "SELECT * FROM usuarios WHERE usuario='%s' AND password='%s'";
		$strQuery = sprintf($strQuery, $_POST['usuario'], md5($_POST['password']));
		$conexion = new Conexion($database);
		$resultado = $conexion->seleccionarDatos($strQuery);
		$conexion->cerrarConexion();
		if(count($resultado) > 0){
			Sesion::setValor('usuario', $resultado[0]);
			header('Location: '.ROOT_HTML);
		} else {
			$error = true;
		}
	} else {
		$error = true;
	}
} else {
	$error = true;
}	
if($error) {
	Sesion::setValor('error', $warnings['USUARIO_INCORRECTO']);
	header('Location: '.VISTAS_HTML.'/usuarios/ingresar.php');
}
?>