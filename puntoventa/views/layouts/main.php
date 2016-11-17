<div class="main">
    <!--
    <div class="well" align="center">
        <?php echo CHtml::button('Panel de control', array('class' => 'btn btn-primary btn-sm', 'submit' => CController::createUrl('/puntoventa'))); ?>
        <?php echo CHtml::button('Puntos de Venta', array('class' => 'btn btn-primary btn-sm', 'submit' => CController::createUrl('/puntoventa/puntoVenta'))); ?>
        <?php echo CHtml::button('Administrador', array('class' => 'btn btn-primary btn-sm', 'submit' => CController::createUrl('/puntoventa/admin'))); ?>
    </div>
    -->
    <?php if (isset($this->breadcrumbs)): ?>
        <?php
        $this->widget('zii.widgets.CBreadcrumbs', array(
            'links' => $this->breadcrumbs,
            'encodeLabel' => false,
            'homeLink' => false,
            'tagName' => 'ul',
            'separator' => '',
            'activeLinkTemplate' => '<li><a href="{url}">{label}</a></li>',
            'inactiveLinkTemplate' => '<li class="active">{label}</li>',
            'htmlOptions' => array('class' => 'breadcrumb')
        ));
        ?>
    <?php endif ?>

    <?php echo $content; ?>
</div>