<?php
require_once '..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'variables.php';
$ingresar = FORMS_HTML.'/login.php';
include_once HEADER_LY;
?>
<h3 class="center">Buscar Libro</h3>
<hr class="alt2" />
<form class="vertical" action="prueba.php" method="post">		
	<div class="col_3 column">
		<fieldset>
			<legend class="large"><i class="icon-signout"></i>Filtro</legend>
			<input id="chk_codigo" class="checkbox" type="checkbox" name="chk_filtro[]">
			<label class="inline" for="chk_codigo">Código</label>
			<br>		
			<input id="chk_autor" class="checkbox" type="checkbox" name="chk_filtro[]">
			<label class="inline" for="chk_autor">Autor</label>
			<br>
			<input id="chk_titulo" class="checkbox" type="checkbox" name="chk_filtro[]">
			<label class="inline" for="chk_titulo">Título</label>
			<br>
			<input id="chk_editorial" class="checkbox" type="checkbox" name="chk_filtro[]">
			<label class="inline" for="chk_editorial">Editorial</label>
		</fieldset>
	</div>
	<div class="col_1"></div>
	<div class="col_8">
		<fieldset>
			<legend>Valores</legend>
			<label for="codigo" class="disabled">Código: </label>
			<input type="text" name="codigo" id="codigo_id" placeholder="Ingrese el código del libro" disabled="disabled">
			<br>		
			<label for="codigo" class="disabled">Autor: </label>
			<input type="text" name="codigo" id="codigo_id" placeholder="Ingrese el autor del libro" disabled="disabled">
			<br>
			<label for="codigo" class="disabled">Título: </label>
			<input type="text" name="codigo" id="codigo_id" placeholder="Ingrese el titulo del libro" disabled="disabled">
			<br>
			<label for="codigo" class="disabled">Editorial: </label>
			<input type="text" name="codigo" id="codigo_id" placeholder="Ingrese la editorial del libro" disabled="disabled">
		</fieldset>
		<button type="submit" style="float:right;">Buscar</button>	
	</div>
</form>
<?php	
include_once FOOTER_LY;
?>