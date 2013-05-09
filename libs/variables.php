<?php
//Constantes
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));
define('WWW', ROOT.DS.'www');
define('SRC', WWW.DS.'src');
define('VISTAS', SRC.DS.'vistas');
define('LAYOUTS', VISTAS.DS.'layouts');
define('ERROR_LY', LAYOUTS.DS.'error.php');
define('MAIN_LY', LAYOUTS.DS.'main.php');
define('HEADER_LY', LAYOUTS.DS.'header.php');
define('FOOTER_LY', LAYOUTS.DS.'footer.php');
define('LIBS', ROOT.DS.'libs');
define('CONEXION', LIBS.DS.'conexion.php');
define('ARRAY_CONTROL', LIBS.DS.'array_control.php');
define('ARREGLO', LIBS.DS.'arreglo.php');
define('FECHA', LIBS.DS.'fecha.php');
define('SESION', LIBS.DS.'sesion.php');
define('VARIABLES', LIBS.DS.'variables.php');

//Detalles aplicacion
define('TITULO', 'Administración Bibliotecaria');
define('IDIOMA', 'es');
define('AUTOR', '');
define('TIEMPO_PRESTAMO', 1);


//Para enlaces y recursos del lado cliente
define('ROOT_HTML', '/biblioteca');
define('CSS', ROOT_HTML.'/css');
define('JS', ROOT_HTML.'/js');
define('IMAGES', ROOT_HTML.'/images');
define('KICKSTART_CSS', CSS.'/kickstart.css');
define('STYLE_CSS', CSS.'/style.css');
define('KICKSTART_JS', JS.'/kickstart.js');
define('JQUERY', JS.'/jquery-1.9.1.min.js');
define('SRC_HTML', ROOT_HTML.'/src');
define('CONTROL_HTML', SRC_HTML.'/controles');
define('VISTAS_HTML', SRC_HTML.'/vistas');
define('LAYOUTS_HTML', VISTAS_HTML.'/layouts');
define('ACERCA_HTML', LAYOUTS_HTML.'/acerca.php');

//Mensajes de error
$warnings = [
	'VACIO' => ['titulo' => 'Sin coincidencias', 'descripcion' => '¡La búsqueda no tuvo resultados!'],
	'USUARIO_INCORRECTO' => ['titulo' => 'Datos incorrectos', 'descripcion' => '¡El usuario o la contraseña son incorrectas!'],
	'CEDULA_NO_ENCONTRADA' => ['titulo' => 'Sin coincidencias', 'descripcion' => '¡La cédula no se encuentra registrada, debe estar registrado para poder realizar prestamos!'],
	'SUSPENDIDO' => ['titulo' => 'Suspendido', 'descripcion' => '¡No puede realizar prestamos, se encuentra suspendido aún!']
];


//Variables
$database = [
	'usuario' => 'root',
	'password' => '18990567',
	'host' => 'localhost',
	'db' => 'biblioteca'
];


