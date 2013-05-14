<?php
require_once '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'variables.php';
require_once SESION;
require_once CONEXION;
@Sesion::iniciarSesion();
require_once ZEBRA_FORM;

$conexion = new Conexion($database);
$si = false;
if(isset($_GET) && isset($_GET['id']) && is_numeric($_GET['id'])){
	$resultados = $conexion->seleccionarDatos('SELECT l.id, l.titulo, l.autor, l.descripcion, l.codigo, 
												l.editorial, l.fecha_ingreso, c.nombre AS categoria, c.id AS categoria_id
												FROM libros l JOIN categorias c ON l.categoria_id=c.id
												WHERE l.id='.$_GET['id']);
	if(count($resultados>0)){
		$libro = $resultados[0];
		$si = true;
	}		
}

if($si){
	$id = $libro['id'];
	$codigo = $libro['codigo'];
	$titulo = $libro['titulo'];
	$autor = $libro['autor'];
	$descripcion = $libro['descripcion'];
	$editorial = $libro['editorial'];
	$fecha = $libro['fecha_ingreso'];
	$categoria = $libro['categoria'];	
	$categoria_id = $libro['categoria_id'];
} else {
	$id = '';
	$codigo = '';
	$titulo = '';
	$autor = '';
	$descripcion = '';
	$editorial = '';
	$fecha = '';
	$categoria = '';
	$categoria_id = '';	
}

$form = new Zebra_Form('form');

$form->add('hidden', 'id', $id);

// Agrego el codigo del libro al formulario
$form->add('label', 'label_codigo', 'codigo', 'Código:');
$obj = $form->add('text', 'codigo', $codigo);
$obj->set_rule(array(
    'required'  =>  array('error', '¡El código del Libro es obligatorio!'),
));

// Agrego el título del libro al formulario
$form->add('label', 'label_titulo', 'titulo', 'Título:');
$obj = $form->add('text', 'titulo', $titulo);
$obj->set_rule(array(
    'required'  =>  array('error', '¡El Título del Libro es obligatorio!'),
));

// Agrego el autor del libro al formulario
$form->add('label', 'label_autor', 'autor', 'Autor:');
$obj = $form->add('text', 'autor', $autor);
$obj->set_rule(array(
    'required'  =>  array('error', '¡El autor del Libro es obligatorio!'),
));

// Agrego la descripción del libro al formulario
$form->add('label', 'label_descripcion', 'descripcion', 'Descripción:');
$obj = $form->add('textarea', 'descripcion', $descripcion);
$obj->set_rule(array(
    'required'  =>  array('error', '¡El Descripción del Libro es obligatorio!'),
     'length' => [10, 250, 'error', 'La longitud máxima es de 250 carácteres!', true]
));

// Agrego el editorial del libro al formulario
$form->add('label', 'label_editorial', 'editorial', 'Editorial:');
$obj = $form->add('text', 'editorial', $editorial);
$obj->set_rule(array(
    'required'  =>  array('error', '¡El Editorial del Libro es obligatorio!'),
));

// Agrego la categoria del libro al formulario
$conexion = new Conexion($database);
$resultados = $conexion->seleccionarDatos('SELECT * FROM categorias');
foreach ($resultados as $rs) {
	$categorias[$rs['id']] = $rs['nombre'];
}
$conexion->cerrarConexion();

$form->add('label', 'label_categoria', 'categoria', 'Categoria:');
$obj = $form->add('select', 'categoria', $categoria_id);
$obj->add_options($categorias);
$obj->set_rule(array(
    'required'  =>  array('error', '¡El categoria del Libro es obligatorio!'),
));

 // "date"
$form->add('label', 'label_fecha', 'fecha', 'Fecha de Ingreso:');
$date = $form->add('date', 'fecha', $fecha);
$date->set_rule(array(
    'required'      =>  array('error', 'Date is required!'),
    'date'          =>  array('error', 'Date is invalid!'),
));
// date format
// don't forget to use $date->get_date() if the form is valid to get the date in YYYY-MM-DD format ready to be used
// in a database or with PHP's strtotime function!
$date->format('Y-m-d');
// selectable dates are starting with the current day
$date->direction(1);
$form->add('note', 'note_date', 'date', 'El formato de la fecha es Y-m-d');
// "submit"
$form->add('submit', 'btnEnviar', 'Enviar');

if ($form->validate()) {
	if(Sesion::existe('usuario')){
		$conexion = new Conexion($database);
		$id = $_POST['id'];
		if(is_numeric($id)){	
			$strQuery = "UPDATE libros SET codigo='%s', autor='%s', titulo='%s', descripcion='%s', editorial='%s', fecha_ingreso='%s', categoria_id=%d WHERE id=$id";	
		} else {
			$strQuery = "SELECT id FROM libros WHERE codigo='%s'";
			$strQuery = sprintf($strQuery, $_POST['codigo']);			
			if(count($conexion->seleccionarDatos($strQuery)) > 0){
				Sesion::setValor('error', $warnings['EXISTE']);
				header('Location: '.CONTROL_HTML.'/libros/agregar.php');				
			}
			$strQuery = "INSERT INTO `libros`(`id`, `codigo`, `autor`, `titulo`, `descripcion`, `editorial`, `fecha_ingreso`, `categoria_id`) 
					VALUES (default,'%s','%s','%s','%s','%s', '%s', %d)";				
		}
		$strQuery = sprintf($strQuery, $_POST['codigo'], $_POST['autor'], $_POST['titulo'], $_POST['descripcion'], $_POST['editorial'], $_POST['fecha'], $_POST['categoria']);
		if($conexion->agregarRegistro($strQuery))
			Sesion::setValor('success', $warnings['CORRECTO']);
		else 
			Sesion::setValor('error', $warnings['NO_AGREGADO']);
		$conexion->cerrarConexion();				
		header('Location: '.CONTROL_HTML.'/libros/agregar.php');	
	} else {
		Sesion::setValor('error', $warnings['SIN_PERMISOS']);
		header('Location: '.ROOT_HTML);	
	}
} else {
    $form->render(VISTAS.DS.'libros'.DS.'agregar.php');
}