<?php
require_once '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'variables.php';
require_once SESION;
@Sesion::iniciarSesion();
require_once ZEBRA_FORM;

if(Sesion::existe('usuario')){
	$form = new Zebra_Form('frmConsultar', 'get');

	 // "date"
	$form->add('label', 'label_fecha_inicio', 'fecha_inicio', 'Fecha de Inicio:');
	$date = $form->add('date', 'fecha_inicio');
	$date->set_rule(array(
	    'required'      =>  array('error', '¡La fecha inicial es obligatoria!'),
	    'date'          =>  array('error', '¡La fecha es inválida!')
	));
	// date format
	// don't forget to use $date->get_date() if the form is valid to get the date in YYYY-MM-DD format ready to be used
	// in a database or with PHP's strtotime function!
	$date->format('Y-m-d');
	// selectable dates are starting with the current day
	$date->direction(0);
	$form->add('note', 'note_date', 'date', 'El formato de la fecha es Y-m-d');
	// "submit"
	$form->add('submit', 'btnEnviar', 'Agregar');

	 // "date"
	$form->add('label', 'label_fecha_fin', 'fecha_fin', 'Fecha Final:');
	$date = $form->add('date', 'fecha_fin');
	$date->set_rule(array(
	    'required'      =>  array('error', '¡La fecha  final es obligatoria!'),
	    'date'          =>  array('error', '¡La fecha es inválida!')
	));
	// date format
	// don't forget to use $date->get_date() if the form is valid to get the date in YYYY-MM-DD format ready to be used
	// in a database or with PHP's strtotime function!
	$date->format('Y-m-d');
	// selectable dates are starting with the current day
	$date->direction(0);
	$form->add('note', 'note_date', 'date', 'El formato de la fecha es Y-m-d');

	// "submit"
	$form->add('submit', 'btnEnviar', 'Consultar');

	if ($form->validate()) {
		require_once CONEXION;
		$strQuery = sprintf("SELECT pr.fecha_entrega, pr.fecha_prestamo, pr.fecha_entregado, CONCAT(u.nombres, '  ', u.apellidos) AS usuario, CONCAT(p.nombres, '  ', p.apellidos) AS persona, c.nombre AS ejemplar, l.autor, l.titulo, l.isbn 
							FROM prestamos pr 
							JOIN personas p ON pr.persona_id=p.id 
							JOIN usuarios u ON pr.usuario_id=u.id 
							JOIN ejemplares c ON pr.ejemplar_id=c.id
							JOIN libros l ON c.libro_id=l.id
							WHERE pr.fecha_prestamo BETWEEN '%s' AND '%s' ORDER BY pr.fecha_prestamo ASC", $_GET['fecha_inicio'], $_GET['fecha_fin']);
		$conexion = new Conexion($database);
		$resultados = $conexion->seleccionarDatos($strQuery);
		$conexion->cerrarConexion();
		if(count($resultados) > 0){
			$prestamos = $resultados;
			include_once(VISTAS.DS.'libros'.DS.'lista_prestamos.php');
		} else {
			Sesion::setValor('error', $warnings['VACIO']);
			header('Location: '.CONTROL_HTML.'/libros/reporte_prestamos.php');
		}	
	} else {
	    $form->render(VISTAS.DS.'libros'.DS.'reporte_prestamos.php');
	}
} else {
	Sesion::setValor('error', $warnings['SIN_PERMISOS']);
	header('Location: '.ROOT_HTML);
}