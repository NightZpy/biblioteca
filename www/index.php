<?php
require_once '..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'variables.php';
require_once SESION;
Sesion::iniciarSesion();
//include_once HEADER_LY;
header('Location: '.CONTROL_HTML.'/libros/buscar.php');
?>