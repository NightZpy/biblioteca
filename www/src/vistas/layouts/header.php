<?php @Sesion::iniciarSesion(); ?>
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
		<link rel="stylesheet" type="text/css" href="<?php echo ZEBRA_CSS; ?>/zebra_form.css" media="all" />
		<style>
			button { float: right;}
			h3 { 
				color: white;
				font-style: oblique;
			}
			.background {
				background: url("<?php echo IMAGES; ?>/fondo.jpg");
			}
		</style>

		<script type="text/javascript" src="<?php echo JQUERY; ?>"></script>
		<script type="text/javascript" src="<?php echo KICKSTART_JS; ?>"></script> 	
		<script src="<?php echo ZEBRA_JS; ?>/zebra_form.js"></script>
		<script type="text/javascript" src="<?php echo MASKED_JS; ?>"></script> 
	</head>
	<body class="background">
		<div class="col_3"></div>
		<div class="col_6">
		 	<ul class="menu center">
				<li><a href="#"><i class="icon-2x icon-search"></i>Buscar</a>
					<ul>
						<li><a href="<?php echo CONTROL_HTML; ?>/libros/buscar.php"><i class="icon-search"></i>Libro</a></li>
						<li><a href="<?php echo CONTROL_HTML.'/personas/buscar.php'; ?>"><i class="icon-search"></i>Persona</a></li>
					</ul>
				</li>	 		
	<?php
	if (Sesion::existe('usuario')) :
	?>	
				<li><a href="#"><i class="icon-2x icon-plus"></i>Agregar</a>
					<ul>
						<li><a href="<?php echo CONTROL_HTML.'/libros/agregar.php'; ?>"><i class="icon-plus"></i>Agregar Libro</a></li>
						<li><a href="<?php echo CONTROL_HTML.'/personas/agregar.php'; ?>"><i class="icon-plus"></i>Agregar Persona</a></li>
						<li><a href="<?php echo CONTROL_HTML.'/usuarios/agregar.php'; ?>"><i class="icon-plus"></i>Agregar Usuario</a></li>
					</ul>
				</li>			
				<li><a href="<?php echo CONTROL_HTML.'/usuarios/salir.php'; ?>"><i class="icon-2x icon-signout"></i>Salir</a></li>
	<?php
	else :
	?>			
				<li><a href="<?php echo CONTROL_HTML; ?>/usuarios/ingresar.php"><i class="icon-2x icon-signin"></i>Ingresar</a></li>
	<?php endif; ?>
				<!--<li><a href="<?php echo ACERCA_HTML; ?>"><i class="icon-2x icon-info-sign"></i>Acerca</a></li>-->
			</ul>
		</div>
		<div class="col_3"></div>	
	<div class="grid">
		<div class="col_3"></div>
		<div class="col_6">
	<?php if(Sesion::existe('error')): ?>
	<div class="notice warning"><i class="icon-warning-sign icon-large"></i>
		<strong><?php echo Sesion::getValor('error')['titulo']; ?>: </strong>
		<?php echo Sesion::getValor('error')['descripcion']; ?>
	<a href="#close" class="icon-remove"></a></div>
	<?php Sesion::eliminar('error');  ?>
	<?php endif;  ?>

	<?php if(Sesion::existe('success')): ?>
	<div class="notice success"><i class="icon-ok icon-large"></i>
		<strong><?php echo Sesion::getValor('success')['titulo']; ?>: </strong>
		<?php echo Sesion::getValor('success')['descripcion']; ?>
	<a href="#close" class="icon-remove"></a></div>
	<?php Sesion::eliminar('success'); ?>
	<?php endif;  ?>		