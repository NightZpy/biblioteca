<?php
require_once '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'variables.php';
require_once SESION;
require_once CONEXION;
@Sesion::iniciarSesion();
require_once ZEBRA_FORM;

$conexion = new Conexion($database);
$si = false;
if(isset($_GET) && isset($_GET['id']) && is_numeric($_GET['id'])){
	$strQuery = 'SELECT id, nombres, apellidos, usuario, cedula, email, movil, password, direccion 
				FROM usuarios
				WHERE id='.$_GET['id'];
	$resultados = $conexion->seleccionarDatos($strQuery);
	
	if(count($resultados)>0){
		$usuario = $resultados[0];
		$si = true;
	}		
}

if($si){
	$id = $usuario['id'];
	$usuario_n = $usuario['usuario'];
	$password = $usuario['password'];	
	$cedula = $usuario['cedula'];
	$nombre = $usuario['nombres'];
	$apellido = $usuario['apellidos'];
	$email = $usuario['email'];
	$movil = $usuario['movil'];
	$direccion = $usuario['direccion'];
} else {
	$id = '';
	$usuario_n = '';
	$password = '';		
	$cedula = '';
	$nombre = '';
	$apellido = '';
	$email = '';
	$movil = '';
	$direccion = '';
}


$form = new Zebra_Form('form');

$form->add('hidden', 'id', $id);

// Agrego el cedula del usuario al formulario
$form->add('label', 'label_usuario', 'usuario', 'Usuario:');
$obj = $form->add('text', 'usuario', $usuario_n);
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

// Agrego el apellido del usuario al formulario
$form->add('label', 'label_apellido', 'apellido', 'Apellidos:');
$obj = $form->add('text', 'apellido', $apellido);
$obj->set_rule(array(
    'required'  =>  array('error', '¡El apellido de la usuario es obligatorio!'),
    'alphabet'  =>  array('error', '¡Sólo se permiten letras!'),
    'length' => [3, 50, 'error', 'La longitud mínima es de 3 y máxima es de 50 carácteres!', true]
));

// Agrego el título del usuario al formulario
$form->add('label', 'label_nombre', 'nombre', 'Nombres:');
$obj = $form->add('text', 'nombre', $nombre);
$obj->set_rule(array(
    'required'  =>  array('error', '¡El nombre de la usuario es obligatorio!'),
    'alphabet'  =>  array('error', '¡Sólo se permiten letras!'),
    'length' => [3, 50, 'error', 'La longitud mínima es de 3 y máxima es de 50 carácteres!', true]
));

$form->add('label', 'label_cedula', 'cedula', 'Cédula:');
$obj = $form->add('text', 'cedula', $cedula);
$obj->set_rule(array(
    'required'  =>  array('error', '¡La cédula de la usuario es obligatoria!'),
    'digits'  =>  array('error', '¡Sólo se permiten valores númericos!'),
    'length' => [4, 10, 'error', 'La longitud mínima es de 4 y máxima es de 10 carácteres!', true]
));

$form->add('label', 'label_movil', 'movil', 'Móvil:');
$date = $form->add('text', 'movil', $movil);
$obj->set_rule(array(
	'required'  =>  array('error', '¡El número de móvil o un teléfono es obligatorio!'),
    'digits'  =>  array('error', '¡Sólo se permiten valores númericos!'),
    'length' => [6, 15, 'error', '¡La longitud máxima es de 15 carácteres!', true]
));

// Agrego el email del persona al formulario
$form->add('label', 'label_email', 'email', 'Email:');
$obj = $form->add('text', 'email', $email);
$obj->set_rule(array(
    'required'  =>  array('error', '¡El email del persona es obligatorio!'),
    'email'  =>  array('error', '¡Debe ser una dirección de correo válida!'),
    'length' => [3, 60, 'error', 'La longitud mínima es de 5 y máxima es de 60 carácteres!', true]
));

$form->add('label', 'label_direccion', 'direccion', 'Dirección:');
$obj = $form->add('textarea', 'direccion', $direccion);
$obj->set_rule(array(
    'required'  =>  array('error', '¡La dirección es obligatoria!'),
    'length' => [5, 250, 'error', 'La longitud mínima es de 5 y máxima es de 250 carácteres!', true]
));
// "submit"
$form->add('submit', 'btnEnviar', 'Enviar');

if ($form->validate()) {
	if(Sesion::existe('usuario')){
		$conexion = new Conexion($database);
		$id = $_POST['id'];
		if(is_numeric($id)){	
			$strQuery = "UPDATE usuarios SET usuario='%s', password='%s',  cedula='%s', apellidos='%s', nombres='%s', email='%s', movil='%s', direccion='%s' WHERE id=$id";	
		} else {
			$strQuery = "INSERT INTO `usuarios`(`id`, usuario, password, `cedula`, `apellidos`, `nombres`, `email`, `movil`, direccion) 
					VALUES (default,'%s','%s','%s','%s','%s', '%s', '%s', '%s')";				
		}

		$strQuery = sprintf($strQuery, $_POST['usuario'], md5($_POST['password']), $_POST['cedula'], $_POST['apellido'], $_POST['nombre'], $_POST['email'], 
							$_POST['movil'], $_POST['direccion']);

		$conexion->agregarRegistro($strQuery);
		$conexion->cerrarConexion();
		Sesion::setValor('success', $warnings['CORRECTO']);
		header('Location: '.CONTROL_HTML.'/usuarios/agregar.php');	
	} else {
		Sesion::setValor('error', $warnings['SIN_PERMISOS']);
		header('Location: '.ROOT_HTML);	
	}
} else {
    $form->render(VISTAS.DS.'usuarios'.DS.'agregar.php');
}