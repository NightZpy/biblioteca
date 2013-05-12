<?php
require_once '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'variables.php';
require_once SESION;
@Sesion::iniciarSesion();
include_once HEADER_LY;
?>
<h3 class="center">Agregar Persona</h3>
<hr class="alt2" />
<div class="col_5 column">
        <!-- things that need to be side-by-side go in "cells" -->
    <fieldset>
        <?php echo $label_nombre . $nombre?>
        <?php echo $label_apellido . $apellido?>           
        <?php echo $label_cedula . $cedula?>
        <div class="clear"></div> 
        <div class="cell">
            <?php echo $label_nacionalidad ?>        
            <div class="clear"></div> 
            <div class="cell"><?php echo $nacionalidad_v?></div>
            <div class="cell"><?php echo $label_nacionalidad_v?></div>
            <div class="clear"></div>
            <div class="cell"><?php echo $nacionalidad_e?></div>
            <div class="cell"><?php echo $label_nacionalidad_e?></div>
            <div class="clear"></div>        
        </div>
        <div class="clear"></div> 
        <?php echo $label_email . $email?>
        <?php echo $label_telefono . $telefono?>
        <?php echo $label_movil . $movil?>
    </fieldset>
</div>
<div class="col_7 columns">
    <fieldset>
        <?php echo $label_direccion . $direccion?>                       
        <?php echo $label_procedencia . $procedencia?>
        <?php echo $label_tipo . $tipo?>
        <!-- once we're done with "cells" we *must* place a "clear" div -->
        <div class="clear"></div>
        
            <!-- the submit button goes in the last row; also, notice the "last" class which
            removes the bottom border which is otherwise present for any row -->
            <div class="row even last"><?php echo $btnEnviar?></div>
        </div>
    </fieldset>
<?php include_once FOOTER_LY; ?>