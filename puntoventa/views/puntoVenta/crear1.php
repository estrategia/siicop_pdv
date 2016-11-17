<?php

$this->pageTitle = Yii::app()->name . ' - Crear Puntos de Venta';

$this->breadcrumbs = array(
    'Inicio' => array('/puntoventa'),
    'Puntos de Venta' => array('/puntoventa/puntoVenta'),
    'Crear'
);
?>

<?php
/*
$this->beginWidget('yiiwheels.widgets.box.WhBox', array(
    'title' => 'Crear Punto de Venta',
    'headerIcon' => 'icon-th-list',
    'htmlOptions' => array('class' => '')
));
 * 
 */
?>

<?php
$this->widget('zii.widgets.jui.CJuiTabs', array(
    'id' => 'puntoventa_tabs',
    'tabs' => array(
        'Informaci&oacute;n B&aacute;sica' => array(
            'id' => 'puntoventa_tab_' . Yii::app()->controller->module->infoBasica,
            'content' => $this->renderPartial("_infobasica", array('model' => $model, 'active' => $active == Yii::app()->controller->module->infoBasica ? true : false), true),
        ),
        'Contacto' => array(
            'id' => 'puntoventa_tab_' . Yii::app()->controller->module->infoContacto,
            'content' => $this->renderPartial("_contacto", array('model' => $model, 'telefonos' => $telefonos, 'active' => $active == Yii::app()->controller->module->infoContacto ? true : false), true),
        ),
        'Otros' => array(
            'id' => 'puntoventa_tab_' . Yii::app()->controller->module->infoOtros,
            'content' => $this->renderPartial("_otros", array('model' => $model, 'active' => $active == Yii::app()->controller->module->infoOtros ? true : false), true),
        ),
    ),
    'options' => array(
        'collapsible' => false,
        'active' => $active
    ),
));
