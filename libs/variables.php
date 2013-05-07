<?php
//Constantes
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));
define('WWW', ROOT.DS.'www');
define('SRC', WWW.DS.'src');
define('FORMS', SRC.DS.'forms');
define('LAYOUTS', SRC.DS.'layouts');
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
define('AUTOR', 'Paúl Lenyn Cuao Alcántara');


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
define('LAYOUTS_HTML', SRC_HTML.'/layouts');
define('CONTROL_HTML', SRC_HTML.'/control');
define('FORMS_HTML', SRC_HTML.'/forms');
define('ACERCA_HTML', LAYOUTS_HTML.'/acerca.php');


//Variables
$database = [
	'usuario' => 'nightzpy',
	'password' => '18990567',
	'host' => 'localhost',
	'db' => 'biblioteca'
];


