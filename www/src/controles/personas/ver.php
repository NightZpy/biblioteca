<?php
require_once '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'variables.php';
require_once SESION;
@Sesion::iniciarSesion();
require_once CONEXION;

$error = false;
if(isset($_GET) and !empty($_GET)){
	$strQuery = 'SELECT l.id, l.nombres, l.apellidos, l.nacionalidad, l.cedula, l.email, l.movil, l.telefono, l.direccion, l.procedencia, c.nombre AS tipo
				FROM personas l JOIN tipo_personas c ON l.tipo_persona_id=c.id
				WHERE '; 	
	if(isset($_GET['id']) and !empty($_GET['id'])){
		$strQuery .= 'l.id='.$_GET['id'];
	} elseif ((isset($_GET['cedula']) and !empty($_GET['cedula'])) and (isset($_GET['nacionalidad']) and !empty($_GET['nacionalidad']))) {
		$strQuery .= 'l.nacionalidad="'.$_GET['nacionalidad'].'"';
		$strQuery .= ' AND l.cedula='.$_GET['cedula'];
	} else {
		$error = true;
	}

	if(!$error) {
		$conexion = new Conexion($database);
		$resultados = $conexion->seleccionarDatos($strQuery);	
		if(count($resultados)>0){
			$persona = $resultados[0];
			$strQuery = 'SELECT pr.id, pr.fecha_entrega, pr.fecha_prestamo, pr.fecha_entregado, l.titulo, l.codigo, c.id AS cota
						FROM prestamos pr
						JOIN cotas c ON pr.cota_id = c.id
						JOIN libros l ON c.libro_id = l.id
						WHERE pr.persona_id =';
			$strQuery .= $persona['id'];
			$suspendidos=false;
			$resultados = $conexion->seleccionarDatos($strQuery);
			if(count($resultados)>0){
				$prestamos = $resultados;
				$strQuery = sprintf("SELECT l.titulo, s.desde, s.hasta
									FROM personas p
									JOIN suspendidos s ON p.id = s.persona_id
									JOIN cotas c ON s.cota_id = c.id
									JOIN libros l ON c.libro_id = l.id
									WHERE p.id =1
									AND s.hasta > CURDATE( )", $persona['id']);
				$resultados = $conexion->seleccionarDatos($strQuery);				
				if(count($resultados)>0)
					$suspendidos = $resultados;
				else 
					$suspendidos = false;
			} else {
				$prestamos = false;
			}
			$conexion->cerrarConexion();
			include_once VISTAS.DS.'personas'.DS.'ver.php';	
		} else {
			$error = true;
		}
	}		
} else {
	$error = true;
}	

if($error){
	Sesion::setValor('error', $warnings['VACIO']);
	header('Location: '.CONTROL_HTML.'/personas/buscar.php');
}