<?php
	if(Sesion::existe('usuario')): ?>
<?php include_once HEADER_LY; ?>
		<h3 class="center">Prestamo Realizado</h3>
		<hr class="alt2" />
		<div class="col_3"></div>
		<div class="col_6">
			<p><strong>Número de Control: </strong><em><?php echo $prestamo['id']; ?></em></p>
			<p><strong>Realizado él: </strong><em><?php echo $prestamo['fecha_prestamo']; ?></em></p>
			<p><strong>Hasta él: </strong><em><?php echo $prestamo['fecha_entrega']; ?></em></p>
			<p><strong>Título del Libro: </strong><em><?php echo $libro['titulo']; ?></em></p>
			<p><strong>Código: </strong><em><?php echo $libro['codigo']; ?></em></p>
			<p><strong>Escrito por: </strong><em><?php echo $libro['autor']; ?></em></p>
			<p><strong>Prestado por: </strong><em><?php echo $persona['nombres'].' '.$persona['apellidos']; ?></em></p>
			<p><strong>Cédula: </strong><em><?php echo $persona['cedula']; ?></em></p>
		</div>
		<div class="col_3"></div>
<?php include_once FOOTER_LY; ?>
<?php else: ?>		
	<div id="error_busqueda" class="notice error" style="display: none;"><i class="icon-error-sign icon-large"></i>
		<strong>Error: </strong>
		no tiene permisos para realizar este prestamo.
	<a href="#close" class="icon-remove"></a></div>		
<?php endif; ?>