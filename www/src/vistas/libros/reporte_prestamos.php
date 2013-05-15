<?php
require_once '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'variables.php';
require_once SESION;
@Sesion::iniciarSesion();
?>
<?php if (Sesion::existe('usuario')): ?>
<?php include_once HEADER_LY; ?>
<div class="col_4"></div>
<div class="col_4">
	<fieldset>
		<div class="row"><?php echo $label_fecha_inicio . $fecha_inicio?></div>
		<div class="row"><?php echo $label_fecha_fin . $fecha_fin?></div>

		<div class="clear"></div>
    	<div class="row even last"><?php echo $btnEnviar?></div>
	</fieldset>
</div>
<div class="col_4"></div>
<?php include_once FOOTER_LY; ?>	
<?php else: ?>
<?php	
	Sesion::setValor('error', $warnings['SIN_PERMISOS']);
	header('Location: '.ROOT_HTML);	
?>	
<?php endif; ?>
