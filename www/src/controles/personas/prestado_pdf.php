<?php  
require_once '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'variables.php';
require_once SESION;
Sesion::iniciarSesion();
if(Sesion::existe('usuario')) {
	$error = false;
	if(isset($_GET) AND isset($_GET['prestamo_id']) AND !empty($_GET['prestamo_id'])) {
		$strQuery = 'SELECT pr.id, pr.fecha_prestamo AS desde, pr.fecha_entrega AS hasta, p.cedula, tp.nombre AS tipo, 
					 CONCAT(p.apellidos, " ", p.nombres) AS persona, CONCAT(u.apellidos, " ", u.nombres) AS usuario, 
					 p.procedencia, l.autor, l.titulo, l.codigo, c.nombre AS cota
					 FROM prestamos pr 
					JOIN personas p ON pr.persona_id=p.id 
					JOIN usuarios u ON pr.usuario_id=u.id 
					JOIN cotas c ON pr.cota_id=c.id
					JOIN libros l ON c.libro_id=l.id
					JOIN tipo_personas tp ON tp.id = p.tipo_persona_id
					WHERE pr.id='.$_GET['prestamo_id'];

		require_once CONEXION;
		$conexion = new Conexion($database);
		$resultados = $conexion->seleccionarDatos($strQuery);
		$conexion->cerrarConexion();
		if(count($resultados) > 0) {
			$prestamo = $resultados[0];
			$content = '
				<div style="position: relative; float: left; width: 120%; margin-left: 15%;">
					<div><h1 class="center" align="center" style="position: relative; float: left; width: 60%; background-color: black;"><strong style="color: white;">SOLICITUD DE OBRAS</strong></h1></div>
					<div align="center" style="position: relative; float: left; width: 60%; border-bottom: 1px solid black;"><strong>Control Nro.: </strong><em>'.$prestamo['id'].'</em>   <pre>   </pre>   <strong>Desde el: </strong><em>'.$prestamo['desde'].'</em>   <pre>   </pre>   <strong>Hasta el: </strong><em>'.$prestamo['hasta'].'</em> </div><br>
					<div align="center" style="position: relative; float: left; width: 60%; border-bottom: 1px solid black;"><strong>Cedula: </strong><em>'.$prestamo['cedula'].'</em>   <pre>   </pre>   <strong>Prestado por: </strong><em>'.$prestamo['persona'].'</em></div><br>
					<div align="center" style="position: relative; float: left; width: 60%; border-bottom: 1px solid black;"><strong>Procedencia: </strong><em>'.$prestamo['procedencia'].'</em>   <pre>   </pre>   <strong>Tipo: </strong><em>'.$prestamo['tipo'].'</em></div><br>
					<div align="center" style="position: relative; float: left; width: 60%; border-bottom: 1px solid black;"><strong>Autorizado por: </strong><em>'.$prestamo['usuario'].'</em></div><br>   
					<div align="center" style="position: relative; float: left; width: 60%; border-bottom: 1px solid black;"><strong>Codigo: </strong><em>'.$prestamo['codigo'].'</em>   <pre>   </pre>   <strong>Cota: </strong><em>'.$prestamo['cota'].'</em>   <pre>   </pre>   <strong>Autor: </strong><em>'.$prestamo['autor'].'</em>   <pre>   </pre>   <strong>Titulo: </strong><em>'.$prestamo['titulo'].'</em></div><br>
				</div>
			';

			require_once DOMPDF_CLASS;

			function doPDF($path='',$content='',$body=false,$style='',$mode=false,$paper_1='a4',$paper_2='portrait') 
			{     
			    if( $body!=true and $body!=false ) $body=false; 
			    if( $mode!=true and $mode!=false ) $mode=false; 
			     
			    if( $body == true ) 
			    { 
			        $content=' 
			        <!doctype html> 
			        <html lang="'.IDIOMA.'
			        ">
			        <head>
						<meta charset="UTF-8">	
						<title>Administración Bibliotecaria</title>
						<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
						<meta name="description" content="" />
						<meta name="copyright" content="" />
						<link rel="stylesheet" type="text/css" href="/biblioteca/css/kickstart.css" media="all" />                  <!-- KICKSTART -->		
						<link rel="stylesheet" type="text/css" href="/biblioteca/css/style.css" media="all" />                          <!-- CUSTOM STYLES -->
			        </head> 
			        <body>' 
			            .$content. 
			        '</body> 
			        </html>'; 
			    } 
			     
			    if( $content!='' ) 
			    {         
			        //Añadimos la extensión del archivo. Si está vacío el nombre lo creamos 
			        $path!='' ? $path .='.pdf' : $path = crearNombre(10);   

			        //Las opciones del papel del PDF. Si no existen se asignan las siguientes:[*] 
			        if( $paper_1=='' ) $paper_1='a4'; 
			        if( $paper_2=='' ) $paper_2='portrait'; 
			             
			        $dompdf =  new DOMPDF(); 
			        $dompdf -> set_paper($paper_1,$paper_2); 
			        $dompdf -> load_html(utf8_encode($content)); 
			        //ini_set("memory_limit","32M"); //opcional  
			        $dompdf -> render(); 
			         
			        //Creamos el pdf 
			        if($mode==false) 
			            $dompdf->stream($path); 
			             
			        //Lo guardamos en un directorio y lo mostramos 
			        if($mode==true) 
			            if( file_put_contents($path, $dompdf->output()) ) header('Location: '.$path); 
			    } 
			} 

			function crearNombre($length) 
			{ 
			    if( ! isset($length) or ! is_numeric($length) ) $length=6; 
			     
			    $str  = "0123456789abcdefghijklmnopqrstuvwxyz"; 
			    $path = ''; 
			     
			    for($i=1 ; $i<$length ; $i++) 
			      $path .= $str{rand(0,strlen($str)-1)}; 

			    return $path.'_'.date("d-m-Y_H-i-s").'.pdf';     
			} 
			doPDF('ejemplo',$content,true,'',true,'letter','landscape');	
		} else {
			$error = true;	
		}
	} else {
		$error = true;
	}

	if($error){
		Sesion::setValor('error', $warnings['NO_PDF']);
		header('Location: '.CONTROL_HTML.'/libros/prestar.php?libro_id='.$_GET['libro_id'].'&cota='.$_GET['cota']);				
	}	
} else {
	Sesion::setValor('error', $warnings['SIN_PERMISOS']);
	header('Location: '.ROOT_HTML);	
}	
    
?>