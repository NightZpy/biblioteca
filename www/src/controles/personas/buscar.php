<?php
require_once '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'variables.php';
require_once SESION;
@Sesion::iniciarSesion();
require_once ZEBRA_FORM;

$form = new Zebra_Form('form', 'get');

// Agrego el cedula del usuario al formulario
$form->add('label', 'label_cedula', 'cedula', 'Cédula:');
$obj = $form->add('text', 'cedula');
$obj->set_rule(array(
    'required'  =>  array('error', '¡La cédula de la usuario es obligatoria!'),
    'digits'  =>  array('error', '¡Sólo se permiten valores númericos!'),
    'length' => [4, 10, 'error', 'La longitud mínima es de 4 y máxima es de 10 carácteres!', true]
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

// "submit"
$form->add('submit', 'btnEnviar', 'Enviar');

if ($form->validate()) {
	header('Location: '.CONTROL_HTML.'/personas/ver.php?cedula='.$_GET['cedula'].'&nacionalidad='.$_GET['nacionalidad']);	
} else {
    $form->render(VISTAS.DS.'personas'.DS.'buscar.php');
}