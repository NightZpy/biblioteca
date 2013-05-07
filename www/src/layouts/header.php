<?php
require_once SESION;
?>
<!doctype html>
<html lang="<?php echo IDIOMA; ?>">
	<head>
		<meta charset="UTF-8">	
		<title><?php echo TITULO; ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<meta name="description" content="" />
		<meta name="copyright" content="" />
		<link rel="stylesheet" type="text/css" href="<?php echo KICKSTART_CSS; ?>" media="all" />                  <!-- KICKSTART -->		
		<link rel="stylesheet" type="text/css" href="<?php echo STYLE_CSS; ?>" media="all" />                          <!-- CUSTOM STYLES -->
		<script type="text/javascript" src="<?php echo JQUERY; ?>"></script>
		<script type="text/javascript" src="<?php echo KICKSTART_JS; ?>"></script> 	
	</head>
<body>
<div class="col_3"></div>
<div class="col_6">
 	<ul class="menu center">
<?php
if (Sesion::existe('login')) {
?>	
		<li class="current"><a href="<?php echo ROOT_HTML; ?>"><i class="icon-search"></i>Buscar Libro</a></li>
		<li><a href="<?php echo $addLibro; ?>"><i class="icon-plus"></i>Agregar Libro</a></li>
		<li><a href="<?php echo $addEstudiante; ?>"><i class="icon-plus"></i>Agregar Estudiante</a></li>
		<li><a href="<?php echo $addUsuario; ?>"><i class="icon-plus"></i>Agregar Usuario</a></li>
		<li><a href="<?php echo $estadoEstudiante; ?>"><i class="icon-question-sign"></i>Estado Estudiante</a></li>
		<li><a href="<?php echo $salir; ?>"><i class="icon-signout"></i>Salir</a></li>
		<li><a href="<?php echo ACERCA_HTML; ?>"><i class="icon-info-sign"></i>Acerca</a></li> 
<?php
} else {
?>
		<li class="current"><a href="<?php echo ROOT_HTML; ?>"><i class="icon-search"></i>Buscar Libro</a></li>
		<li><a href="<?php echo $ingresar; ?>"><i class="icon-signin"></i>Ingresar</a></li>
		<li><a href="<?php echo $salir; ?>"><i class="icon-question-sign"></i>Estado Estudiante</a></li>
		<li><a href="<?php echo ACERCA_HTML; ?>"><i class="icon-info-sign"></i>Acerca</a></li>
	</ul>
</div>	
<?php
}
?>
	<div class="grid">
		<div class="col_2"></div>
		<div class="col_8">