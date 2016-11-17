<?php if ($active): ?>
    <?php foreach (Yii::app()->user->getFlashes() as $key => $message) { ?>
        <div class="alert alert-dismissable alert-<?php echo $key ?>">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <?php echo $message ?>
        </div>
    <?php } ?>
<?php endif; ?>

<div class="clear"></div>

<?php $this->renderPartial('/telefonoPuntoVenta/admin', array('consulta' => $consulta, 'model' => $telefonos)); ?>

<div class="clear"></div>

<div class="row">
    <div class="footer">
        <ul class="pager">
            <li class="previous" id="id_contacto_anterior"><a href="#" class="anterior">← Anterior</a></li>
            <li class="next" id="id_contacto_siguiente"><a href="#" class="siguiente">Siguiente →</a></li>
        </ul>
    </div>
</div>


<?php
Yii::app()->clientScript->registerScript('contacto-tab', "
$(document).on('click', '#id_contacto_anterior', function(e) {
    $('#puntoventa_tabs').tabs({ active: ".($tab-1)."});
});
$(document).on('click', '#id_contacto_siguiente', function(e) {
    $('#puntoventa_tabs').tabs({ active: ".($tab+1)."});
});");
?>
