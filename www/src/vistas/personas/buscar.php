<?php
include_once HEADER_LY;
?>
<h3 class="center">Buscar persona</h3>
<hr class="alt2" />
<div class="col_4"></div>
<div class="col_4 column">
        <!-- things that need to be side-by-side go in "cells" -->
    <fieldset>      
        <?php echo $label_cedula . $cedula?>
        <div class="clear"></div>
        <div class="row even last"><?php echo $btnEnviar?></div>
    </fieldset>
</div>
<div class="col_4"></div>
<?php include_once FOOTER_LY; ?>