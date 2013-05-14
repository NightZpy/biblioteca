<?php include_once HEADER_LY; ?>
<?php if(Sesion::existe('suspendido')): ?>
<div class="notice warning"><i class="icon-warning-sign icon-large"></i>
	<strong><?php echo Sesion::getValor('suspendido')['titulo']; ?>: </strong>
	<?php echo Sesion::getValor('suspendido')['descripcion']; ?>
<a href="#close" class="icon-remove"></a></div>
<?php Sesion::eliminar('suspendido') ?>
<?php endif; ?>
<?php if(isset($persona)): ?>
<h3 class="center"><?php echo $persona['nombres'].' '.$persona['apellidos']?></h3>
<hr class="alt2" />
<legend>
	<p><strong>Nacionalidad: </strong><em><?php echo $persona['nacionalidad']; ?></em></p>
	<p><strong>Cédula: </strong><em><?php echo $persona['cedula']; ?></em></p>
	<p><strong>Email: </strong><em><?php echo ($persona['email'] == 1 ? 'Si.' : 'No.'); ?></em></p>
	<p><strong>Teléfono: </strong><em><?php echo $persona['telefono']; ?></em></p>
	<p><strong>Móvil: </strong><em><?php echo $persona['movil']; ?></em></p>
	<p><strong>Dirección: </strong><em><?php echo $persona['direccion']; ?></em></p>
	<p><strong>Procedencia: </strong><em><?php echo $persona['procedencia']; ?></em></p>
	<p><strong>Tipo: </strong><em><?php echo $persona['tipo']; ?></em></p>

	<?php if($prestamos AND isset($prestamos) AND is_array($prestamos) AND count($prestamos)>0): ?>
	<h5 class="left">Prestamos realizados: </h5>	
	<table cellspacing="0" cellpadding="0" class="sortable">
		<thead>
			<tr>
				<th>Código</th>
				<th>Título</th>
				<th>Cota</th>
				<th>Fecha de prestamo</th>
				<th>Fecha de entrega</th>
				<th>Se entrego el</th>
				<?php if(Sesion::existe('usuario')): ?>	
				<th>Acciones</th>
				<?php endif; ?>	
			</tr>
		</thead>
		<tbody>
			<?php foreach ($prestamos as $prestamo): ?>
			<tr>
				<td><?php echo $prestamo['codigo']; ?></td>
				<td><?php echo $prestamo['titulo']; ?></td>
				<td>C-<?php echo $prestamo['cota']; ?></td>
				<td><?php echo $prestamo['fecha_prestamo']; ?></td>
				<td><?php echo $prestamo['fecha_entrega']; ?></td>
				<th><?php echo ($prestamo['fecha_entregado'] == '' ? '¡No se ha entregado!' : $prestamo['fecha_entregado']); ?></th>
				<?php if(Sesion::existe('usuario')): ?>	
				<td>
				<?php if($prestamo['fecha_entregado'] != ''): ?>
					<strong>Entregado</strong>
				<?php else: ?>								
					<a href="<?php echo CONTROL_HTML.'/personas/devolver.php?id='.$prestamo['id'].'&cedula='.$persona['cedula'].'&nacionalidad='.$persona['nacionalidad']?>">
						<span class="tooltip" title="Devolver libro">
							<i class="icon-2x icon-exchange"></i>
						</span>
					</a>	
				<?php endif; ?>														
				</td>
				<?php endif; ?>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php endif; ?>

	<?php if($suspendidos AND isset($suspendidos) AND is_array($suspendidos) AND count($suspendidos)>0): ?>
	<h5 class="left">Veces suspendido: </h5>	
	<table cellspacing="0" cellpadding="0" class="sortable">
		<thead>
			<tr>
				<th>Título</th>
				<th>Desde</th>
				<th>Hasta</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($suspendidos as $suspendido): ?>
			<tr>
				<td><?php echo $suspendido['titulo'];?></td>
				<td><?php echo $suspendido['desde']; ?></td>
				<td><?php echo $suspendido['hasta']; ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php endif; ?>	
</legend>
<?php endif; ?>
<?php include_once FOOTER_LY; ?>
