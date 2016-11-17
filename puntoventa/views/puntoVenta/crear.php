<?php

$this->pageTitle = Yii::app()->name . ' - Crear Puntos de Venta';

$this->breadcrumbs = array(
    'Inicio' => array('/puntoventa'),
    'Puntos de Venta' => array('/puntoventa/puntoVenta'),
    'Crear'
);
?>

<?php

Yii::app()->clientScript->registerScript('horarios-admin-crear', "pvFlagCrear = true;");
?>

<?php
$this->widget('zii.widgets.jui.CJuiTabs', array(
    'id' => 'puntoventa_tabs',
    'tabs' => array(
        'Info. B&aacute;sica' => array(
            'id' => 'puntoventa_tab_' . Yii::app()->controller->module->infoBasica,
            'content' => $this->renderPartial("_infobasica", array('model' => $model, 'consulta' => false, 'tab' => Yii::app()->controller->module->infoBasica, 'active' => true), true),
            'label' => 'Prueba2'
        ),
        'Directorio' => array(
            'id' => 'puntoventa_tab_' . Yii::app()->controller->module->infoContacto,
            'content' => '',
            'label' => 'Prueba2'
        ),
        'Otros' => array(
            'id' => 'puntoventa_tab_' . Yii::app()->controller->module->infoOtros,
            'content' => '',
            'label' => 'Prueba2'
        ),
        'Servicios' => array(
            'id' => 'puntoventa_tab_' . Yii::app()->controller->module->infoServicios,
            'content' => '',
            'label' => 'Prueba2'
            
        ),
        'Horarios' => array(
            'id' => 'puntoventa_tab_' . Yii::app()->controller->module->infoHorarios,
            'content' => '',
            'label' => 'Prueba2'
        ),
        'Empleados' => array(
            'id' => 'puntoventa_tab_' . Yii::app()->controller->module->infoEmpleados,
            'content' => '',
            'label' => 'Prueba2'
        ),
        
        'Influencia' => array(
            'id' => 'puntoventa_tab_' . Yii::app()->controller->module->infoInfluencia,
            'content' => '',
            'label' => 'Prueba2'
        ),
        'Competencia' => array(
            'id' => 'puntoventa_tab_' . Yii::app()->controller->module->infoCompetencia,
            'content' => '',
            'label' => 'Prueba2'
        ),
        'Im&aacute;genes' => array(
            'id' => 'puntoventa_tab_' . Yii::app()->controller->module->infoImagenes,
            'content' => '',
            'label' => 'Prueba2'
        ),
        
        'Historial' => array(
            'id' => 'puntoventa_tab_' . Yii::app()->controller->module->infoHistorial,
            'content' => '',
            'label' => 'Prueba2'
        ),
    ),
    'headerTemplate' => '<li><a href="{url}" title="{title}">{title}</a></li>',
    'options' => array(
        'collapsible' => false,
        'active' => 0,
    ),
));
