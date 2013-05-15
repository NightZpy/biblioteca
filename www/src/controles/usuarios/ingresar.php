<?php
require_once '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'variables.php';
require_once SESION;
@Sesion::iniciarSesion();
require_once ZEBRA_FORM;

if(!Sesion::existe('usuario')){
	$form = new Zebra_Form('frmIngresar', 'POST');

	// Agrego el cedula del usuario al formulario
	$form->add('label', 'label_usuario', 'usuario', 'Usuario:');
	$obj = $form->add('text', 'usuario');
	$obj->set_rule(array(
	    'required'  =>  array('error', '¡El usuario es obligatorio!'),
	    'length' => [3, 50, 'error', 'La longitud mínima es de 3 y máxima es de 50 carácteres!', true]
	));

	// Agrego la contraseña del usuario al formulario
	$form->add('label', 'label_password', 'password', 'Contraseña:');
	$obj = $form->add('password', 'password');
	$obj->set_rule(array(
	    'required'  =>  array('error', '¡La contraseña es obligatoria!'),
	    'length' => [3, 200, 'error', '¡La longitud mínima es de 6 y máxima es de 200 carácteres!', true]
	));

	// "submit"
	$form->add('submit', 'btnEnviar', 'Ingresar');

	if ($form->validate()) {
		require_once CONEXION;
		$strQuery = "SELECT * FROM usuarios WHERE usuario='%s' AND password='%s'";
		$strQuery = sprintf($strQuery, $_POST['usuario'], md5($_POST['password']));
		$conexion = new Conexion($database);
		$resultado = $conexion->seleccionarDatos($strQuery);
		$conexion->cerrarConexion();
		if(count($resultado) > 0){
			Sesion::setValor('usuario', $resultado[0]);
			header('Location: '.ROOT_HTML);
		} else {
			Sesion::setValor('error', $warnings['USUARIO_INCORRECTO']);
			header('Location: '.CONTROL_HTML.'/usuarios/ingresar.php');
		}	
	} else {
	    $form->render(VISTAS.DS.'usuarios'.DS.'ingresar.php');
	}
} else {
	header('Location: '.ROOT_HTML);
}