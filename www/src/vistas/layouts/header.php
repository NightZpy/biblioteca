<?php
@Sesion::iniciarSesion();
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
		<style>
			button { float: right;}
		</style>

		<script type="text/javascript" src="<?php echo JQUERY; ?>"></script>
		<script type="text/javascript" src="<?php echo KICKSTART_JS; ?>"></script> 	
	</head>
<body>
	<div class="col_3"></div>
	<div class="col_6">
	 	<ul class="menu center">
<?php
if (Sesion::existe('usuario')) {
?>	
			<li class="current"><a href="<?php echo ROOT_HTML; ?>"><i class="icon-search"></i>Buscar Libro</a></li>
			<li><a href="#"><i class="icon-plus"></i>Agregar</a>
				<ul>
					<li><a href="<?php echo VISTAS_HTML.'/libros/agregar.php'; ?>"><i class="icon-plus"></i>Agregar Libro</a></li>
					<li><a href="<?php echo VISTAS_HTML.'/personas/agregar.php'; ?>"><i class="icon-plus"></i>Agregar Persona</a></li>
					<li><a href="<?php echo VISTAS_HTML.'/usuarios/agregar.php'; ?>"><i class="icon-plus"></i>Agregar Usuario</a></li>
				</ul>
			</li>
			<li><a href="<?php echo VISTAS_HTML.'/personas/estado.php'; ?>"><i class="icon-question-sign"></i>Estado Persona</a></li>
			<li><a href="<?php echo CONTROL_HTML.'/usuarios/salir.php'; ?>"><i class="icon-signout"></i>Salir</a></li>
			<li><a href="<?php echo ACERCA_HTML; ?>"><i class="icon-info-sign"></i>Acerca</a></li> 
<?php
} else {
?>			
			<li><a href="<?php echo VISTAS_HTML; ?>/usuarios/ingresar.php"><i class="icon-signin"></i>Ingresar</a></li>
			<li class="current"><a href="<?php echo VISTAS_HTML.'/libros/buscar.php'; ?>"><i class="icon-search"></i>Buscar Libro</a></li>
			<li><a href="<?php echo VISTAS_HTML.'/personas/estado.php'; ?>"><i class="icon-question-sign"></i>Estado Persona</a></li>
			<li><a href="<?php echo ACERCA_HTML; ?>"><i class="icon-info-sign"></i>Acerca</a></li>
<?php
}
?>
		</ul>
	</div>
	<div class="col_3"></div>	
<div class="grid">
	<div class="col_3"></div>
	<div class="col_6">
<?php
if(Sesion::existe('error')){
?>
<div class="notice warning"><i class="icon-warning-sign icon-large"></i>
	<strong><?php echo Sesion::getValor('error')['titulo']; ?>: </strong>
	<?php echo Sesion::getValor('error')['descripcion']; ?>
<a href="#close" class="icon-remove"></a></div>
<?php
	Sesion::eliminar('error');
}
?>		