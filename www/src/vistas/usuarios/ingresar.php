<?php
require_once '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'variables.php';
require_once SESION;
@Sesion::iniciarSesion();
if (Sesion::existe('usuario')) {
	header('Location: '.ROOT_HTML);
} else {
	$acerca = '../layouts/acerca.php';	
?>
<?php include_once HEADER_LY; ?>
<h3 class="center">Ingresar al Sistema</h3>
<hr class="alt2" />
	<div class="col_4"></div>
	<div class="col_4">
		<fieldset>
			<div class="row"><?php echo $label_usuario . $usuario?></div>
			<div class="row"><?php echo $label_password . $password?></div>

			<div class="clear"></div>
        	<div class="row even last"><?php echo $btnEnviar?></div>
		</fieldset>
	</div>
	<div class="col_4"></div>
</form>	
<?php include_once FOOTER_LY; ?>	
<?php	
}
?>