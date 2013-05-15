<?php if(isset($libro)): ?>
	<?php include_once HEADER_LY; ?>
	<h3 class="center"><?php echo $libro['titulo']; ?></h3>
	<hr class="alt2" />
	<div class="col_6">
		<p><strong>Código: </strong><em><?php echo $libro['codigo']; ?></em></p>
		<p><strong>Escrito por: </strong><em><?php echo $libro['autor']; ?></em></p>
		<p><strong>Casa Editorial: </strong><em><?php echo $libro['editorial']; ?></em></p>
		<p><strong>Ingresó el: </strong><em><?php echo $libro['fecha_ingreso']; ?></em></p>
		<p><strong>Categoria: </strong><em><?php echo $libro['categoria']; ?></em></p>
		<p><strong>Cantidad de copias: </strong><em><?php echo $copias; ?></em></p>
		<p><strong>Copias disponibles: </strong><em><?php echo $copiasDisponibles; ?></em></p>
		<p><strong>Copias prestadas: </strong><em><?php echo $copiasPrestadas; ?></em></p>
	</div>

	<div class="col_6">
		<h5>Descripción: </h5>
		<blockquote class="small">
		<p><?php echo $libro['descripcion']; ?></p>
		</blockquote>
	<?php if(Sesion::existe('usuario')): ?>
		<?php if(!$cotas): ?>
			<strong>No se puede prestar, no hay copias disponibles.</strong>
		<?php else: ?>
			<form action="<?php echo CONTROL_HTML; ?>/libros/prestar.php" method="get">
				<fieldset>
					<input type="hidden" name="libro_id" value="<?php echo $libro['id'] ?>">
					<p><strong>Seleccione la cota a prestar: </strong>
					<select name="cota" id="cota">
					<?php foreach ($cotas as $cota) : ?>
						<option value="<?php echo $cota['id'] ?>">C-<?php echo $cota['nombre'] ?></option>
					<?php endforeach; ?>		
					</select>
					</p>
					<button type="submit" class="large"><i class="icon-briefcase"></i>Prestar</button>
				</fieldset>
			</form>
		<?php endif; ?>
		<div class="clear"></div>
		<br>
		<a href="<?php echo CONTROL_HTML; ?>/libros/copiar.php?libro_id=<?php echo $libro['id']; ?>">
			<button class="large"><i class="icon-book"></i>Agregar Copia</button>
		</a>	
	<?php endif; ?>
	</div>
	<?php include_once FOOTER_LY; ?>
<?php endif; ?>