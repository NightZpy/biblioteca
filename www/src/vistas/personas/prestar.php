<?php
if(Sesion::existe('usuario')):
	if(isset($libro)):
?>
<?php include_once HEADER_LY; ?>
<h3 class="center">Realizar Prestamo</h3>
<hr class="alt2" />
<div class="col_6">
	<h5 class="center"><?php echo $libro['titulo']; ?></h5>
	<p><strong>Código: </strong><em><?php echo $libro['codigo']; ?></em></p>
	<p><strong>Escrito por: </strong><em><?php echo $libro['autor']; ?></em></p>
</div>

<div class="col_6">
	<h5 class="center">Buscar Persona</h5>
	<form id="frmBusqueda" class="vertical" action="<?php echo CONTROL_HTML; ?>/personas/prestar.php" method="get">	
		<fieldset>
			<legend>Datos</legend>
			<label for="cedula_id">Cédula: </label>
			<input type="hidden" name="libro" value="<?php echo $libro['id']; ?>">
			<input type="text" name="cedula" id="cedula_id" placeholder="Ingrese la cédula">
			<button type="submit">Prestar</button>
		</fieldset>
	</form>
</div>
<?php include_once FOOTER_LY; ?>
<?php 
	else:
?>		
		<div id="error_busqueda" class="notice error" style="display: none;"><i class="icon-error-sign icon-large"></i>
			<strong>Error: </strong>
			debe seleccionar un libro para realizar el prestamo.
		<a href="#close" class="icon-remove"></a></div>		
<?php
	endif;
else:
?>		
	<div id="error_busqueda" class="notice error" style="display: none;"><i class="icon-error-sign icon-large"></i>
		<strong>Error: </strong>
		no tiene permisos para realizar este prestamo.
	<a href="#close" class="icon-remove"></a></div>		
<?php
endif;
?>