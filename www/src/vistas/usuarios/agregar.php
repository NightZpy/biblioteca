<?php
require_once '..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'variables.php';
require_once SESION;
@Sesion::iniciarSesion();
include_once HEADER_LY;
?>
<script>
    jQuery(function($){
        $("#movil").mask("(999)9999999");
    });  
</script>
<h3 class="center">Agregar Usuario</h3>
<hr class="alt2" />
<div class="col_5 column">
    <fieldset><!-- things that need to be side-by-side go in "cells" -->
        <?php echo $label_usuario . $usuario?>
        <?php echo $label_password . $password?> 
        <?php echo $label_cedula . $cedula?>
        <?php echo $label_nombre . $nombre?>
        <?php echo $label_apellido . $apellido?>                   
    </fieldset>
</div>
<div class="col_7 columns">
    <fieldset>
        <?php echo $label_email?>
        <br>
        <?php echo $email?>
        <br>        
        <?php echo $label_movil?>   
        <br>
        <?php echo $movil?> 
        <br>
        <?php echo $label_direccion . $direccion?>                           
        <!-- the submit button goes in the last row; also, notice the "last" class which
        removes the bottom border which is otherwise present for any row -->
        <div class="row even last"><?php echo $btnEnviar?></div>
    </fieldset>
</div>
<?php include_once FOOTER_LY; ?>s