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
<div class="col_4 column">
    <fieldset><!-- things that need to be side-by-side go in "cells" -->
        <div class="row"><?php echo $label_usuario . $usuario?></div>
        <div class="row"><?php echo $label_password . $password?></div>
        <div class="row"><?php echo $label_cedula . $cedula?></div>
        <div class="row"><?php echo $label_nombre . $nombre?></div>
        <div class="row"><?php echo $label_apellido . $apellido?></div>
        <div class="row"><?php echo $label_tipo_usuario . $tipo_usuario?></div>
    </fieldset>
</div>
<div class="col_2"></div>
<div class="col_6 column">
    <fieldset>
        <div class="row"><?php echo $label_email . $email?></div>
        <div class="row"><?php echo $label_movil . $movil?></div>
        <div class="row"><?php echo $label_direccion . $direccion?></div>
        <!-- the submit button goes in the last row; also, notice the "last" class which
        removes the bottom border which is otherwise present for any row -->
        <div class="clear"></div>
        <div class="row even last"><?php echo $btnEnviar?></div>
    </fieldset>
</div>
<?php include_once FOOTER_LY ?>