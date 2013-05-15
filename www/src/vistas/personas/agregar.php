<?php
require_once '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'variables.php';
require_once SESION;
@Sesion::iniciarSesion();
include_once HEADER_LY;
?>
<script>
    jQuery(function($){
        $("#telefono").mask("(999)9999999");
        $("#movil").mask("(999)9999999");
    });  
</script>
<h3 class="center">Agregar Persona</h3>
<hr class="alt2" />
<div class="col_4 column">
        <!-- things that need to be side-by-side go in "cells" -->
    <fieldset>
        <div class="row"><?php echo $label_nombre . $nombre?></div>
        <div class="row"><?php echo $label_apellido . $apellido?></div>
        <div class="row"><?php echo $label_cedula . $cedula?></div>
         
        <div class="row">
            <?php echo $label_nacionalidad ?>        
            <div class="clear"></div> 
            <div class="cell"><?php echo $nacionalidad_v?></div>
            <div class="cell"><?php echo $label_nacionalidad_v?></div>
            <div class="clear"></div>
            <div class="cell"><?php echo $nacionalidad_e?></div>
            <div class="cell"><?php echo $label_nacionalidad_e?></div>
            <div class="clear"></div>        
        </div> 
        <div class="row"><?php echo $label_email . $email?></div>
        <div class="row"><?php echo $label_telefono . $telefono?></div>
        <div class="row"><?php echo $label_movil . $movil?></div>
    </fieldset>
</div>
<div class="col_2"></div>
<div class="col_6 column">
    <fieldset>
        <div class="row"><?php echo $label_direccion . $direccion?></div>
        <div class="row"><?php echo $label_procedencia . $procedencia?></div>
        <div class="row"><?php echo $label_tipo . $tipo?></div>
        <!-- once we're done with "cells" we *must* place a "clear" div -->
        <div class="clear"></div>
        
            <!-- the submit button goes in the last row; also, notice the "last" class which
            removes the bottom border which is otherwise present for any row -->
        <div class="row even last"><?php echo $btnEnviar?></div>
    </fieldset>
</div>      
<?php include_once FOOTER_LY; ?>