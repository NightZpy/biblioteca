<?php
if(Sesion::existe('usuario')): ?>
<?php
include_once HEADER_LY;
?>
<div class="col_4"></div>
<div class="col_4 column">
        <!-- things that need to be side-by-side go in "cells" -->
    <fieldset>     
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
        <?php echo $label_cedula . $cedula?>        
        <div class="row even last"><?php echo $btnEnviar?></div>
    </fieldset>
</div>
<div class="col_4"></div>
<?php include_once FOOTER_LY; ?>
<?php else: ?>		
	<div id="error_busqueda" class="notice error" style="display: none;"><i class="icon-error-sign icon-large"></i>
		<strong>Error: </strong>
		no tiene permisos para realizar este prestamo.
	<a href="#close" class="icon-remove"></a></div>		
<?php
endif;
?>