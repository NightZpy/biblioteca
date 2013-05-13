<?php
require_once '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'variables.php';
require_once SESION;
require_once CONEXION;
@Sesion::iniciarSesion();
require_once ZEBRA_FORM;

$conexion = new Conexion($database);
$si = false;
if(isset($_GET) && isset($_GET['id']) && is_numeric($_GET['id'])){
	$strQuery = 'SELECT l.id, l.nombres, l.apellidos, l.nacionalidad, l.cedula, l.email, l.movil, l.telefono, l.direccion, l.procedencia, c.nombre AS tipo, c.id AS tipo_id 
				FROM personas l JOIN tipo_personas c ON l.tipo_persona_id=c.id
				WHERE l.id='.$_GET['id'];

	$resultados = $conexion->seleccionarDatos($strQuery);

	if(count($resultados)>0){
		$persona = $resultados[0];
		$si = true;
	}		
}

if($si){
	$id = $persona['id'];
	$cedula = $persona['cedula'];
	$nombre = $persona['nombres'];
	$apellido = $persona['apellidos'];
	$nacionalidad = $persona['nacionalidad'];
	$email = $persona['email'];
	$telefono = $persona['telefono'];
	$movil = $persona['movil'];
	$tipo = $persona['tipo'];	
	$tipo_id = $persona['tipo_id'];	
	$direccion = $persona['direccion'];
	$procedencia = $persona['procedencia'];	
} else {
	$id = '';
	$cedula = '';
	$nombre = '';
	$apellido = '';
	$nacionalidad = '';
	$email = '';
	$telefono = '';
	$movil = '';
	$tipo = '';
	$tipo_id = '';
	$direccion = '';
	$procedencia = '';	
}


$form = new Zebra_Form('form');

$form->add('hidden', 'id', $id);

// Agrego el cedula del persona al formulario
$form->add('label', 'label_cedula', 'cedula', 'Cédula:');
$obj = $form->add('text', 'cedula', $cedula);
$obj->set_rule(array(
    'required'  =>  array('error', '¡La cédula de la persona es obligatoria!'),
    'digits'  =>  array('error', '¡Sólo se permiten valores númericos!'),
    'length' => [4, 10, 'error', 'La longitud mínima es de 4 y máxima es de 10 carácteres!', true]
));

// Agrego el título del persona al formulario
$form->add('label', 'label_nombre', 'nombre', 'Nombres:');
$obj = $form->add('text', 'nombre', $nombre);
$obj->set_rule(array(
    'required'  =>  array('error', '¡El nombre de la persona es obligatorio!'),
    'length' => [3, 50, 'error', 'La longitud mínima es de 3 y máxima es de 50 carácteres!', true]
));

// Agrego el apellido del persona al formulario
$form->add('label', 'label_apellido', 'apellido', 'Apellidos:');
$obj = $form->add('text', 'apellido', $apellido);
$obj->set_rule(array(
    'required'  =>  array('error', '¡El apellido de la persona es obligatorio!'),
    'length' => [3, 50, 'error', 'La longitud mínima es de 3 y máxima es de 50 carácteres!', true]
));

// Agrego la descripción del persona al formulario
$form->add('label', 'label_nacionalidad', 'nacionalidad', 'Nacionalidad:');
$obj = $form->add('radios', 'nacionalidad', 
	[
		'v' => 'Venezolano',
		'e' => 'Extranjero'
	]
);
$obj->set_rule(array(
    'required'  =>  array('error', '¡La nacionalidad es obligatoria!'),
));

// Agrego el email del persona al formulario
$form->add('label', 'label_email', 'email', 'Email:');
$obj = $form->add('text', 'email', $email);
$obj->set_rule(array(
    'required'  =>  array('error', '¡El email del persona es obligatorio!'),
    'email'  =>  array('error', '¡Debe ser una dirección de correo válida!'),
    'length' => [3, 60, 'error', 'La longitud mínima es de 5 y máxima es de 60 carácteres!', true]
));

// Agrego la tipo_persona del persona al formulario
$conexion = new Conexion($database);
$tipo_personas = [];
$resultados = $conexion->seleccionarDatos('SELECT * FROM tipo_personas');
if(count($resultados)>0){
	foreach ($resultados as $rs) {
		$tipo_personas[$rs['id']] = $rs['nombre'];
	}
}
$conexion->cerrarConexion();

$form->add('label', 'label_tipo', 'tipo', 'Tipo de persona:');
$obj = $form->add('select', 'tipo', $tipo_id);
$obj->add_options($tipo_personas);
$obj->set_rule(array(
    'required'  =>  array('error', '¡El Tipo de Persona es obligatorio!'),
));

// Agrego el telefono del persona al formulario
$form->add('label', 'label_telefono', 'telefono', 'Teléfono:');
$date = $form->add('text', 'telefono', $telefono);
$obj->set_rule(array(
    'digits'  =>  array('error', '¡Sólo se permiten valores númericos!'),
    'length' => [0, 15, 'error', '¡La longitud máxima es de 15 carácteres!', true]
));

$form->add('label', 'label_movil', 'movil', 'Móvil:');
$date = $form->add('text', 'movil', $movil);
$obj->set_rule(array(
    'digits'  =>  array('error', '¡Sólo se permiten valores númericos!'),
    'length' => [0, 15, 'error', '¡La longitud máxima es de 15 carácteres!', true]
));

$form->add('label', 'label_procedencia', 'procedencia', 'Procedencia:');
$obj = $form->add('text', 'procedencia', $procedencia);
$obj->set_rule(array(
    'required'  =>  array('error', '¡La procedencia es obligatoria!'),
    'length' => [5, 120, 'error', 'La longitud mínima es de 5 y máxima es de 120 carácteres!', true]
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
			$strQuery = "UPDATE personas SET cedula='%s', apellidos='%s', nombres='%s', nacionalidad='%s', email='%s', telefono=%d, movil='%s', tipo_persona_id=%d, direccion='%s', procedencia='%s' WHERE id=$id";		
		} else {
			$strQuery = "INSERT INTO `personas`(`id`, `cedula`, `apellidos`, `nombres`, `nacionalidad`, `email`, `telefono`, `movil`, `tipo_persona_id`, direccion, procedencia) 
					VALUES (default,'%s','%s','%s','%s','%s', %d, '%s', %d, '%s', '%s')";				
		}

		$strQuery = sprintf($strQuery, $_POST['cedula'], $_POST['apellido'], $_POST['nombre'], $_POST['nacionalidad'], $_POST['email'], 
							$_POST['telefono'], $_POST['movil'], $_POST['tipo'], $_POST['direccion'], $_POST['procedencia']);

		$conexion->agregarRegistro($strQuery);
		$conexion->cerrarConexion();
		Sesion::setValor('success', $warnings['CORRECTO']);
		header('Location: '.CONTROL_HTML.'/personas/agregar.php');	
	} else {
		Sesion::setValor('error', $warnings['SIN_PERMISOS']);
		header('Location: '.ROOT_HTML);	
	}
} else {
    $form->render(VISTAS.DS.'personas'.DS.'agregar.php');
}