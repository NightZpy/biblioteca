<?php
require_once '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'variables.php';
require_once SESION;
@Sesion::iniciarSesion();
require_once ZEBRA_FORM;

if(Sesion::existe('usuario')){	
	$error = false;
	if(isset($_GET) and !empty($_GET)){
		//me aseguro que los parametros necesarios llegan 
		if((isset($_GET['libro_id']) and !empty($_GET['libro_id'])) and (isset($_GET['ejemplar']) and !empty($_GET['ejemplar']))){
			$strQuery = sprintf('SELECT id FROM ejemplares WHERE id=%d and libro_id=%d and disponible=1', $_GET['ejemplar'], $_GET['libro_id']);
			require_once CONEXION;
			$conexion = new Conexion($database);			
			if(count($conexion->seleccionarDatos($strQuery)) > 0){								
				$form = new Zebra_Form('form', 'get');
				$libro = $conexion->seleccionarDatos("SELECT * FROM libros WHERE id=".$_GET['libro_id'])[0];

				$form->add('hidden', 'libro_id', $libro['id']);
				$form->assign('libro', $libro);
				$form->add('hidden', 'ejemplar', $_GET['ejemplar']);

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
				$form->add('submit', 'btnEnviar', 'Buscar');

				if ($form->validate()) {
					header('Location: '.CONTROL_HTML.'/personas/prestar.php?libro_id='.$_GET['libro_id'].'&ejemplar='.$_GET['ejemplar'].'&cedula='.$_GET['cedula']);	
				} else {					
				    $form->render(VISTAS.DS.'personas'.DS.'prestar.php');
				}
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
		Sesion::setValor('error', $warnings['NO_PRESTAMO']);
		header('Location: '.CONTROL_HTML.'/libros/ver.php?libro_id='.$_GET['libro_id']);			
	}

} else {
	Sesion::setValor('error', $warnings['SIN_PERMISOS']);
	header('Location: '.ROOT_HTML);	
}