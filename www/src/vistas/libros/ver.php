<?php
if(isset($libro)){
?>
<?php include_once HEADER_LY; ?>
<h3 class="center"><?php echo $libro['titulo']; ?></h3>
<hr class="alt2" />
<div class="col_6">
	<p><strong>Código: </strong><em><?php echo $libro['codigo']; ?></em></p>
	<p><strong>Escrito por: </strong><em><?php echo $libro['autor']; ?></em></p>
	<p><strong>Casa Editorial: </strong><em><?php echo $libro['editorial']; ?></em></p>
	<p><strong>Ingresó el: </strong><em><?php echo $libro['fecha_ingreso']; ?></em></p>
	<p><strong>Ejemplar: </strong><em><?php echo ($libro['ejemplar'] == 1 ? 'Si.' : 'No.'); ?></em></p>
	<p><strong>Categoria: </strong><em><?php echo $libro['categoria']; ?></em></p>
</div>

<div class="col_6">
	<h5>Descripción: </h5>
	<blockquote class="small">
	<p><?php echo $libro['descripcion']; ?></p>
	</blockquote>
<?php if(Sesion::existe('usuario')): ?>
	<a href="<?php echo CONTROL_HTML; ?>/libros/prestar.php?id=<?php echo $libro['id']; ?>">
		<button class="large"><i class="icon-briefcase"></i>Prestar</button>
	</a>
<?php endif; ?>
</div>
<?php include_once FOOTER_LY; ?>
<?php 
}
?>