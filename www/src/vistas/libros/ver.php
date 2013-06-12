<?php if(isset($libro)): ?>
	<?php include_once HEADER_LY; ?>
	<h3 class="center"><?php echo $libro['titulo']; ?></h3>
	<hr class="alt2" />
	<div class="col_6">	
		<p><strong>ISBN: </strong><em><?php echo $libro['isbn']; ?></em></p>
		<p><strong>Escrito por: </strong><em><?php echo $libro['autor']; ?></em></p>
		<p><strong>Casa Editorial: </strong><em><?php echo $libro['editorial']; ?></em></p>
	<?php if(Sesion::existe('usuario')): ?>		
		<p><strong>Ingresó el: </strong><em><?php echo $libro['fecha_ingreso']; ?></em></p>
	<?php endif; ?>		
		<p><strong>Categoria: </strong><em><?php echo $libro['categoria']; ?></em></p>
	<?php if(Sesion::existe('usuario')): ?>	
		<p><strong>Cantidad de ejemplares: </strong><em><?php echo $copias; ?></em></p>
		<p><strong>Ejemplares disponibles: </strong><em><?php echo $ejemplaresDisponibles; ?></em></p>		
		<p><strong>Ejemplares prestadas: </strong><em><?php echo $ejemplaresPrestadas; ?></em></p>
	<?php else: ?>
		<?php if($ejemplaresDisponibles > 0): ?>	
			<p><strong>¡Disponible!</strong></p>		
		<?php else: ?>
			<p><strong>¡No disponible!</strong></p>
		<?php endif; ?>
	<?php endif; ?>
	</div>

	<div class="col_6">
		<h5>Descripción: </h5>
		<blockquote class="small">
		<p><?php echo $libro['descripcion']; ?></p>
		</blockquote>
	<?php if(Sesion::existe('usuario')): ?>
		<?php if(!$ejemplares): ?>
			<strong>No se puede prestar, no hay ejemplares disponibles.</strong>
		<?php else: ?>
			<form action="<?php echo CONTROL_HTML; ?>/libros/prestar.php" method="get">
				<fieldset>
					<input type="hidden" name="libro_id" value="<?php echo $libro['id'] ?>">
					<strong>Seleccione el ejemplar a prestar: </strong>
					<select name="ejemplar" id="ejemplar">
					<?php foreach ($ejemplares as $ejemplar) : ?>
						<option value="<?php echo $ejemplar['id'] ?>">E-<?php echo $ejemplar['nombre'] ?></option>
					<?php endforeach; ?>		
					</select>
					<button type="submit" class="large"><i class="icon-briefcase"></i>Prestar</button>
				</fieldset>
			</form>
		<?php endif; ?>
		<div class="clear"></div>
		<?php if(Sesion::getValor('usuario')['tipo_usuario'] === 'Admin'): ?>
		<br>
		<a href="<?php echo CONTROL_HTML; ?>/libros/copiar.php?libro_id=<?php echo $libro['id']; ?>">
			<button class="large"><i class="icon-book"></i>Agregar Ejemplar</button>
		</a>
		<?php endif; ?>	
	<?php endif; ?>
	</div>
	<?php include_once FOOTER_LY; ?>
<?php endif; ?>