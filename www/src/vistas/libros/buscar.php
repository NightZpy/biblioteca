<?php
require_once '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'variables.php';
require_once SESION;
@Sesion::iniciarSesion();
include_once HEADER_LY;
?>
<script>
	$(document).ready(function(){  	  
		$("#codigo_id").attr('disabled','disabled');
		$("#autor_id").attr('disabled','disabled');		
		$("#titulo_id").attr('disabled','disabled');
		$("#editorial_id").attr('disabled','disabled');

	    $("#chk_codigo").click(function() {  
	        if($("#chk_codigo").is(':checked')) {  
	            $("#codigo_id").removeAttr('disabled');
	        } else {  
	            $("#codigo_id").attr('disabled','disabled');
	        }  
	    });  

	    $("#chk_autor").click(function() {  
	        if($("#chk_autor").is(':checked')) {  
	            $("#autor_id").removeAttr('disabled');
	        } else {  
	            $("#autor_id").attr('disabled','disabled');
	        }  
	    }); 

	    $("#chk_titulo").click(function() {  
	        if($("#chk_titulo").is(':checked')) {  
	            $("#titulo_id").removeAttr('disabled');
	        } else {  
	            $("#titulo_id").attr('disabled','disabled');
	        }  
	    });

		$("#chk_editorial").click(function() {  
	        if($("#chk_editorial").is(':checked')) {  
	            $("#editorial_id").removeAttr('disabled');
	        } else {  
	            $("#editorial_id").attr('disabled','disabled');
	        }  
	    });	    

	    $("#frmBusqueda").submit(function() {
			if((!$("#chk_codigo").is(':checked') && !$("#chk_autor").is(':checked') && 
				!$("#chk_titulo").is(':checked') && !$("#chk_editorial").is(':checked'))){
					$("#error_busqueda").show("slow");
					return false;
			} else {
				$("#form").submit();
			}
        });	  
	}); 	
</script>
<div id="error_busqueda" class="notice error" style="display: none;"><i class="icon-error-sign icon-large"></i>
	<strong>Error enviando: </strong>
	debe seleccionar aunque sea una opción de búsqueda.
<a href="#close" class="icon-remove"></a></div>
<h3 class="center">Buscar Libro</h3>
<hr class="alt2" />
<form id="frmBusqueda" class="vertical" action="<?php echo CONTROL_HTML; ?>/libros/buscar.php" method="get">		
	<div class="col_4 column">
		<fieldset>
			<legend class="large"><i class="icon-2x icon-filter"></i>Filtro</legend>
			<input id="chk_condicion" class="checkbox" type="checkbox" name="chk_condicion">
			<label class="inline" for="chk_condicion">¿Se deben cumplir todas las condiciones?</label>			
			<br>
			<input id="chk_codigo" class="checkbox" type="checkbox" name="chk_codigo">
			<label class="inline" for="chk_codigo">Código</label>
			<br>		
			<input id="chk_autor" class="checkbox" type="checkbox" name="chk_autor">
			<label class="inline" for="chk_autor">Autor</label>
			<br>
			<input id="chk_titulo" class="checkbox" type="checkbox" name="chk_titulo">
			<label class="inline" for="chk_titulo">Título</label>
			<br>
			<input id="chk_editorial" class="checkbox" type="checkbox" name="chk_editorial">
			<label class="inline" for="chk_editorial">Editorial</label>
		</fieldset>
	</div>
	<div class="col_1"></div>
	<div class="col_7">
		<fieldset>
			<legend>Valores</legend>
			<label for="codigo_id">Código: </label>
			<input type="text" name="codigo" id="codigo_id" placeholder="Ingrese el código del libro">
			<br>		
			<label for="autor_id">Autor: </label>
			<input type="text" name="autor" id="autor_id" placeholder="Ingrese el autor del libro">
			<br>
			<label for="titulo_id">Título: </label>
			<input type="text" name="titulo" id="titulo_id" placeholder="Ingrese el título del libro">
			<br>
			<label for="editorial_id">Editorial: </label>
			<input type="text" name="editorial" id="editorial_id" placeholder="Ingrese la editorial del libro">
		</fieldset>
		<button type="submit">Buscar</button>	
	</div>
</form>
<?php	
include_once FOOTER_LY;
?>