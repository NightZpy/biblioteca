<?php
require_once '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'variables.php';
require_once SESION;
@Sesion::iniciarSesion();
include_once HEADER_LY;
?>
<script>
	$(document).ready(function(){  	  
		$("#isbn").attr('disabled','disabled');
		$("#autor").attr('disabled','disabled');		
		$("#titulo").attr('disabled','disabled');
		$("#editorial").attr('disabled','disabled');

	    $("#chk_isbn").click(function() {  
	        if($("#chk_isbn").is(':checked')) {  
	            $("#isbn").removeAttr('disabled');
	        } else {  
	            $("#isbn").attr('disabled','disabled');
	        }  
	    });  

	    $("#chk_autor").click(function() {  
	        if($("#chk_autor").is(':checked')) {  
	            $("#autor").removeAttr('disabled');
	        } else {  
	            $("#autor").attr('disabled','disabled');
	        }  
	    }); 

	    $("#chk_titulo").click(function() {  
	        if($("#chk_titulo").is(':checked')) {  
	            $("#titulo").removeAttr('disabled');
	        } else {  
	            $("#titulo").attr('disabled','disabled');
	        }  
	    });

		$("#chk_editorial").click(function() {  
	        if($("#chk_editorial").is(':checked')) {  
	            $("#editorial").removeAttr('disabled');
	        } else {  
	            $("#editorial").attr('disabled','disabled');
	        }  
	    });	    

	    $("#frmBusqueda").submit(function() {
			if((!$("#chk_isbn").is(':checked') && !$("#chk_autor").is(':checked') && 
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
	<div class="col_2"></div>
	<div class="col_8">
			<div class="col_4 column">				
				<fieldset>
					<input id="chk_condicion" class="checkbox" type="checkbox" name="chk_condicion">
					<label class="inline" for="chk_condicion">¿Se deben cumplir todas las condiciones?</label>			
					<br>
					<input id="chk_isbn" class="checkbox" type="checkbox" name="chk_isbn">
					<label class="inline" for="chk_isbn">ISBN</label>
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
			<div class="col_2"></div>
			<div class="col_6">
		        <fieldset>
		        	<div class="row"><?php echo $label_isbn . $isbn?></div>
					<div class="row"><?php echo $label_titulo . $titulo?></div>
					<div class="row"><?php echo $label_autor . $autor?></div>
					<div class="row"><?php echo $label_editorial . $editorial?></div> 
					<div class="row even last"><?php echo $btnEnviar?></div>
		        </fieldset>
			</div>		
	</div>	
	<div class="col_2"></div>
<?php include_once FOOTER_LY; ?>