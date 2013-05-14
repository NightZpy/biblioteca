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
define('TMP', ROOT.DS.'tmp');
define('SADO_CACHE', TMP.DS.'sado'.DS.'cache');
define('CONEXION', LIBS.DS.'conexion.php');
define('ARRAY_CONTROL', LIBS.DS.'array_control.php');
define('ARREGLO', LIBS.DS.'arreglo.php');
define('FECHA', LIBS.DS.'fecha.php');
define('SESION', LIBS.DS.'sesion.php');
define('VARIABLES', LIBS.DS.'variables.php');
define('SADO_BOOTSTRAP', LIBS.DS.'Sado'.DS.'sado.bootstrap.php');
define('ZEBRA', LIBS.DS.'Zebra');
define('ZEBRA_FORM', LIBS.DS.'Zebra'.DS.'Zebra_Form.php');

//Detalles aplicacion
define('TITULO', 'Administración Bibliotecaria');
define('IDIOMA', 'es');
define('AUTOR', '');
define('TIEMPO_PRESTAMO', 1);
define('TIEMPO_SUSPENSION', 30);


//Para enlaces y recursos del lado cliente
define('ROOT_HTML', '/biblioteca');
define('CSS', ROOT_HTML.'/css');
define('ZEBRA_HTML', ROOT_HTML.'/Zebra');
define('ZEBRA_CSS', ZEBRA_HTML.'/css');
define('ZEBRA_JS', ZEBRA_HTML.'/js');
define('JS', ROOT_HTML.'/js');
define('IMAGES', ROOT_HTML.'/images');
define('KICKSTART_CSS', CSS.'/kickstart.css');
define('STYLE_CSS', CSS.'/style.css');
define('KICKSTART_JS', JS.'/kickstart.js');
define('MASKED_JS', JS.'/jquery.maskedinput.min.js');
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
	'SUSPENDIDO' => ['titulo' => 'Suspendido', 'descripcion' => '¡Usted ha sido suspendido por entrega tardía!'],
	'SUSPENSION' => ['titulo' => 'Suspendido', 'descripcion' => '¡No puede realizar prestamos, se encuentra suspendido aún!'],
	'CORRECTO' => ['titulo' => 'Correcto', 'descripcion' => '¡Datos agregados correctamente!'],
	'DEVUELTO' => ['titulo' => 'Correcto', 'descripcion' => '¡El libro ha sido devuelto correctamente!'],
	'SIN_PRESTAMO' => ['titulo' => 'Prestamo incorrecto', 'descripcion' => '¡El prestamo no se encuentra registrado!'],
	'NO_PRESTAMO' => ['titulo' => 'No se puede prestar', 'descripcion' => '¡El prestamo no se puede realizar!'],
	'SIN_PERMISOS' => ['titulo' => 'Sin permisos', 'descripcion' => '¡No tiene los permisos suficientes para realizar está acción!'],
	'NO_ELIMINADO' => ['titulo' => 'Imposible eliminar', 'descripcion' => '¡No fue posible eliminar el libro de la base de datos!'],
	'ELIMINADO' => ['titulo' => 'Eliminado', 'descripcion' => '¡Eliminado de la base de datos con éxito!'],
	'EXISTE' => ['titulo' => 'Ya existe', 'descripcion' => '¡Este título ya se encuentra registrado!'],
	'NO_AGREGADO' => ['titulo' => 'Error registrando', 'descripcion' => '¡Imposible registrar!'],
	'COTA_AGREGADA' => ['titulo' => 'Copia realizada', 'descripcion' => '¡La copia ha sido registrada correctamente!'],
];


//Variables
$database = [
	'usuario' => 'root',
	'password' => '18990567',
	'host' => 'localhost',
	'db' => 'biblioteca'
];


