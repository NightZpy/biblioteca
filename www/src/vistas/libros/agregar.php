<?php
require_once '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'variables.php';
require_once SESION;
@Sesion::iniciarSesion();
include_once HEADER_LY;
?>
    <div class="col_4 column">
        <!-- things that need to be side-by-side go in "cells" -->
        <fieldset>
            <div class="row"><?php echo $label_codigo . $codigo?></div>
            <div class="row"><?php echo $label_titulo . $titulo?></div>
            <div class="row"><?php echo $label_autor . $autor?></div>
            <div class="row"><?php echo $label_editorial . $editorial?></div>
            <div class="row"><?php echo $label_categoria . $categoria?></div>
        </fieldset>
    </div> 
    <div class="col_2"></div>   
    <div class="col_6 column">
        <fieldset>
            <div class="row"><?php echo $label_descripcion . $descripcion?></div>
            <div class="row"><?php echo $label_fecha . $fecha . $note_date?></div> <!-- once we're done with "cells" we *must* place a "clear" div -->
            <div class="clear"></div>        
            <!-- the submit button goes in the last row; also, notice the "last" class which
            removes the bottom border which is otherwise present for any row -->
            <div class="row even last"><?php echo $btnEnviar?></div>
        </fieldset>
    </div>
<?php include_once FOOTER_LY; ?>