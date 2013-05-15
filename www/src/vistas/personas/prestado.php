<?php
	if(Sesion::existe('usuario')): ?>
<?php include_once HEADER_LY; ?>
	<h3 class="center">Prestamo Realizado</h3>
	<hr class="alt2" />
	<fieldset>
		<div class="col_8" style="border: 2px solid black">
			<div class="col_12"></div>
			<div class="clear"></div>
			<div class="col_12"><h6 class="center" style="background-color: black;"><strong style="color: white;">SOLICITUD DE OBRAS</strong></h6></div>
			<div class="clear"></div>
			<div class="col_12">
			<?php if($tipoPersonas): ?>
				<?php foreach ($tipoPersonas as $tipo): ?>
					<strong><?php echo $tipo['nombre'] ?></strong>  
					<?php if ($persona['tipo'] == $tipo['nombre']): ?> <i class="icon-check"></i> <?php else: ?> <i class="icon-check-empty"></i> <?php endif; ?>
				<?php endforeach; ?>				
			<?php endif; ?>
			</div>
			<div class="clear"></div>
		</div>
		<div class="col_4">
			<div class="row" style="border-bottom: 1px solid black">
				<p><strong>Control Nro.: </strong><em><?php echo $prestamo['id']; ?></em></p>				
			</div>
			<div class="row" style="border-bottom: 1px solid black">
				<p><strong>Realizado él: </strong><em><?php echo $prestamo['fecha_prestamo']; ?></em></p>
			</div>
			<div class="row" style="border-bottom: 1px solid black">
				<p><strong>Hasta él: </strong><em><?php echo $prestamo['fecha_entrega']; ?></em></p>
			</div>
			<div class="row" style="border-bottom: 1px solid black">
				<p><strong>Cédula: </strong><em><?php echo $persona['cedula']; ?></em></p>
			</div>					
		</div>
		<div class="clear"></div>
		<div class="col_12">
			<div class="row" style="border-bottom: 1px solid black">
				<p><strong>Apellidos y Nombres: </strong><em><?php echo $persona['apellidos'].' '.$persona['nombres'] ?></em></p>
			</div>
			<div class="row" style="border-bottom: 1px solid black">
				<p><strong>Procedencia: </strong><em><?php echo $persona['procedencia'] ?></em></p>
			</div>
		</div>
		<div class="clear"></div>
		<div class="col_12">
			<div class="col_4" style="border: 2px solid black">
				<h4 class="center"><strong>COTA</strong></h4>
				<h5 class="center"><em>C-<?php echo $prestamo['cota'] ?></em></h5>
			</div>
			<div class="col_8">
				<div class="row" style="border-bottom: 1px solid black">
					<p><strong>Autor: </strong><em><?php echo $libro['autor']; ?></em></p>				
				</div>				
				<div class="row" style="border-bottom: 1px solid black">
					<p><strong>Título: </strong><em><?php echo $libro['titulo']; ?></em></p>				
				</div>
				<div class="row" style="border-bottom: 1px solid black">
					<p><strong>Código: </strong><em><?php echo $libro['codigo']; ?></em></p>				
				</div>
				<div class="row" style="border-bottom: 1px solid black">
					<p><strong>Autorizado por: </strong><em><?php echo Sesion::getValor('usuario')['nombres'].' '.Sesion::getValor('usuario')['apellidos'] ?></em></p>
				</div>
			</div>
		</div>
	</fieldset>
<?php include_once FOOTER_LY; ?>
<?php else: ?>		
	<div id="error_busqueda" class="notice error" style="display: none;"><i class="icon-error-sign icon-large"></i>
		<strong>Error: </strong>
		no tiene permisos para realizar este prestamo.
	<a href="#close" class="icon-remove"></a></div>		
<?php endif; ?>