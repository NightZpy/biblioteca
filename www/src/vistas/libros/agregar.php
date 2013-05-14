<?php
require_once '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'variables.php';
require_once SESION;
@Sesion::iniciarSesion();
include_once HEADER_LY;
?>
<h3 class="center">Agregar Libro</h3>
<hr class="alt2" />
<div class="col_5 column">
    <!-- things that need to be side-by-side go in "cells" -->
    <fieldset>
        <?php echo $label_codigo . $codigo?>
        <?php echo $label_titulo . $titulo?>           
        <?php echo $label_autor . $autor?>
        <?php echo $label_editorial . $editorial?>
        <br><br>
        <?php echo $label_categoria . $categoria?>        
    </fieldset>
</div>    
<div class="col_7 columns">
    <fieldset>
        <?php echo $label_descripcion . $descripcion?>                                   
        <div class="clear"></div>
        <?php echo $label_fecha . $fecha . $note_date?>
        <!-- once we're done with "cells" we *must* place a "clear" div -->
        <div class="clear"></div>
        
        <!-- the submit button goes in the last row; also, notice the "last" class which
        removes the bottom border which is otherwise present for any row -->
        <div class="row even last"><?php echo $btnEnviar?></div>
    </fieldset>
</div>
<?php include_once FOOTER_LY; ?>