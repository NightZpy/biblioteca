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
<form id="frmLogin" class="vertical" action="<?php echo CONTROL_HTML; ?>/usuarios/verificar.php" method="post">
	<div class="col_4"></div>
	<div class="col_4">
		<label for="usuario">Usuario: </label>
		<input type="text" name="usuario" id="usuario_id" placeholder="Ingrese el usuario">
		<label for="usuario">Password: </label>
		<input type="password" name="password" id="password_id">
		<button type="submit">Entrar</button>
	</div>
	<div class="col_4"></div>
</form>	
<?php include_once FOOTER_LY; ?>	
<?php	
}
?>