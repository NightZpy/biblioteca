<?php include_once HEADER_LY; ?>
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

	<?php if(isset($prestamos) AND count($prestamos)>0): ?>
	<h5 class="left">Prestamos realizados: </h5>	
	<table cellspacing="0" cellpadding="0" class="sortable">
		<thead>
			<tr>
				<th>Código</th>
				<th>Título</th>
				<th>Fecha de prestamo</th>
				<th>Fecha de entrega</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($prestamos as $prestamo): ?>
			<tr>
				<td><?php echo $prestamo['codigo']; ?></td>
				<td><?php echo $prestamo['titulo']; ?></td>
				<td><?php echo $prestamo['fecha_prestamo']; ?></td>
				<td><?php echo $prestamo['fecha_entrega']; ?></td>					
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php endif; ?>
</legend>
<?php endif; ?>
<?php include_once FOOTER_LY; ?>
