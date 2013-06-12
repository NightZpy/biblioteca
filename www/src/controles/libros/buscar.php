<?php
require_once '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'variables.php';
require_once SESION;
@Sesion::iniciarSesion();
require_once ZEBRA_FORM;

$form = new Zebra_Form('frmBusqueda', 'get');

// Agrego el isbn del libro al formulario
$form->add('label', 'label_isbn', 'isbn', 'ISBN:');
$obj = $form->add('text', 'isbn');
$obj->set_rule(array(
    'alphanumeric' =>  array('error', '¡Sólo se permiten letras y números!')
));

// Agrego el título del libro al formulario
$form->add('label', 'label_titulo', 'titulo', 'Título:');
$obj = $form->add('text', 'titulo');

// Agrego el autor del libro al formulario
$form->add('label', 'label_autor', 'autor', 'Autor:');
$obj = $form->add('text', 'autor');

// Agrego el editorial del libro al formulario
$form->add('label', 'label_editorial', 'editorial', 'Editorial:');
$obj = $form->add('text', 'editorial');
// "submit"
$form->add('submit', 'btnEnviar', 'Buscar');

if ($form->validate()) {
	$error = false;
	if(isset($_GET) and !empty($_GET)){
		if(isset($_GET['chk_condicion']) and !empty($_GET['chk_condicion']) and $_GET['chk_condicion'] == 'on')
			$condicion = ' AND';
		else 
			$condicion = ' OR';

		$strQuery = 'SELECT * FROM libros WHERE ';

		if(isset($_GET['chk_isbn']) and !empty($_GET['chk_isbn']) and $_GET['chk_isbn'] == 'on')
			if(isset($_GET['isbn']) and !empty($_GET['isbn']))
				$strQuery .= "LOWER(isbn) LIKE LOWER('%".$_GET['isbn']."%')";

		if(isset($_GET['chk_autor']) and !empty($_GET['chk_autor']) and $_GET['chk_autor'] == 'on')
			if(isset($_GET['autor']) and !empty($_GET['autor']))
				if(isset($_GET['isbn']) and !empty($_GET['isbn']))
					$strQuery .= $condicion." LOWER(autor) LIKE LOWER('%".$_GET['autor']."%')";
				else
					$strQuery .= " LOWER(autor) LIKE LOWER('%".$_GET['autor']."%')";

		if(isset($_GET['chk_titulo']) and !empty($_GET['chk_titulo']) and $_GET['chk_titulo'] == 'on')	
			if(isset($_GET['titulo']) and !empty($_GET['titulo']))
				if((isset($_GET['autor']) AND !empty($_GET['autor'])) OR (isset($_GET['isbn']) AND !empty($_GET['isbn'])))
					$strQuery .= $condicion." LOWER(titulo) LIKE LOWER('%".$_GET['titulo']."%')";			
				else
					$strQuery .= " LOWER(titulo) LIKE LOWER('%".$_GET['titulo']."%')";			

		if(isset($_GET['chk_editorial']) and !empty($_GET['chk_editorial']) and $_GET['chk_editorial'] == 'on')		
			if((isset($_GET['editorial']) and !empty($_GET['editorial'])) OR (isset($_GET['autor']) AND !empty($_GET['autor'])) OR (isset($_GET['isbn']) AND !empty($_GET['isbn'])))	
				if(isset($_GET['titulo']) and !empty($_GET['titulo']))
					$strQuery .= $condicion." LOWER(editorial) LIKE LOWER('%".$_GET['editorial']."%')";
				else
					$strQuery .= " LOWER(editorial) LIKE LOWER('%".$_GET['editorial']."%')";

		require_once CONEXION;
		$conexion = new Conexion($database);
		$resultados = $conexion->seleccionarDatos($strQuery);	
		$conexion->cerrarConexion();

		if(count($resultados) > 0) {
			$form->assign('resultados', $resultados);
			$form->render(VISTAS.DS.'libros'.DS.'lista.php');
		} else {
			$error = true;
		}
	} else {
		$error = true;
	}

	if ($error){
		Sesion::setValor('error', $warnings['VACIO']);
		header('Location: '.CONTROL_HTML.'/libros/buscar.php');
	}
} else {
    $form->render(VISTAS.DS.'libros'.DS.'buscar.php');
}