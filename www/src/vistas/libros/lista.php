<?php
if(isset($resultados)){
?>
<?php include_once HEADER_LY; ?>
<h3 class="center">Buscar Libro</h3>
<hr class="alt2" />
<table cellspacing="0" cellpadding="0" class="sortable">
	<thead>
		<tr>
			<th>Código</th>
			<th>Autor</th>
			<th>Título</th>
			<th>Descripción</th>
			<th>Editorial</th>
			<th>Acciones</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($resultados as $valor): ?>
		<tr>
			<td><?php echo $valor['codigo']; ?></td>
			<td><?php echo $valor['autor']; ?></td>
			<td><?php echo $valor['titulo']; ?></td>
			<td><?php echo $valor['descripcion']; ?></td>
			<td><?php echo $valor['editorial']; ?></td>	
			<td>
				<a href="<?php echo CONTROL_HTML.'/libros/ver.php?id='.$valor['id']; ?>">
					<span class="tooltip" title="Ver libro">
						<i class="icon-2x icon-eye-open"></i>
					</span>
				</a>
				<a href="<?php echo CONTROL_HTML.'/libros/editar.php?id='.$valor['id']; ?>">
					<span class="tooltip" title="Editar libro">
						<i class="icon-2x icon-pencil"></i>
					</span>
				</a>
				<a href="<?php echo CONTROL_HTML.'/libros/eliminar.php?id='.$valor['id']; ?>">
					<span class="tooltip" title="Eliminar libro">
						<i class="icon-2x icon-minus-sign"></i>
					</span>
				</a>															
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php include_once FOOTER_LY; ?>
<?php 
}
?>