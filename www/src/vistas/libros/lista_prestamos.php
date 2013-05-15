<?php
require_once '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'variables.php';
require_once SESION;
@Sesion::iniciarSesion();
?>
<?php if (Sesion::existe('usuario')): ?>
<?php include_once HEADER_LY; ?>	
<style type="text/css" title="currentStyle">
		@import "<?php echo CSS ?>/demo_table.css";
</style>
<script src="<?php echo JS ?>/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf-8">	
	$(document).ready(function() {
		// asigno la configuración inicial del objeto DataTable
		$('#data').dataTable({
	        "oLanguage": {
	            "sLengthMenu": "Mostrando _MENU_ registros por página",
	            "sZeroRecords": "No se encontraron registros - dísculpa",
	            "sInfo": "Mostrando _START_ hasta _END_ de _TOTAL_ registros",
	            "sInfoEmpty": "Mostrando 0 hasta 0 de 0 registros",
	            "sInfoFiltered": "(De _MAX_ registros totales)",
	            "sSearch": "Buscar"
	        },
	        "aaSorting": [[ 3, "desc" ], [0, 'desc']],
	        "iDisplayLength": <?php echo count($prestamos); ?>,
	        "aLengthMenu": [[10, 20, 50, -1], [10, 20, 50, "All"]]
		});
	});
</script>
<fieldset>
	<table id="data" cellpadding="0" cellspacing="0" border="0" class="display">
		<thead>
			<tr>
				<th>Código</th>
				<th>Autor</th>
				<th>Título</th>
				<th>Cota</th>
				<th>Autorizado por</th>
				<th>Prestado por</th>
				<th>Fecha Prestamo</th>
				<th>Fecha a entregar</th>
				<th>Fecha entregado</th>				
			</tr>
		</thead>
		<tbody class="odd gradeX">
			<?php foreach ($prestamos as $prestamo): ?>
			<tr>
				<td><?php echo $prestamo['codigo']; ?></td>
				<td><?php echo $prestamo['autor']; ?></td>
				<td><?php echo $prestamo['titulo']; ?></td>
				<td>C-<?php echo $prestamo['cota']; ?></td>
				<td><?php echo $prestamo['usuario']; ?></td>
				<td><?php echo $prestamo['persona']; ?></td>	
				<td><?php echo $prestamo['fecha_prestamo']; ?></td>
				<td><?php echo $prestamo['fecha_entrega']; ?></td>
				<td><?php echo ($prestamo['fecha_entregado'] != '' ? $prestamo['fecha_entregado'] : 'Sin entregar'); ?></td>									
			</tr>
			<?php endforeach; ?>
		</tbody>
		<tfood>
			<tr>
				<th>Código</th>
				<th>Autor</th>
				<th>Título</th>
				<th>Cota</th>
				<th>Autorizado por</th>
				<th>Prestado por</th>
				<th>Fecha Prestamo</th>
				<th>Fecha a entregar</th>
				<th>Fecha entregado</th>				
			</tr>
		</tfood>		
	</table>
</fieldset>
<?php include_once FOOTER_LY; ?>	
<?php else: ?>
<?php	
	Sesion::setValor('error', $warnings['SIN_PERMISOS']);
	header('Location: '.ROOT_HTML);	
?>	
<?php endif; ?>