<?php

$this->pageTitle = Yii::app()->name . ' - Crear Puntos de Venta';

$this->breadcrumbs = array(
    'Inicio' => array('/puntoventa'),
    'Puntos de Venta' => array('/puntoventa/puntoVenta'),
    'Ver'
);
?>

<?php

Yii::app()->clientScript->registerScript('horarios-admin-ver', "pvFlagCambioTabs = pvFlagCambioConfirm = false; pvFlagCrear = false;");
?>

<?php
$this->widget('zii.widgets.jui.CJuiTabs', array(
    'id' => 'puntoventa_tabs',
    'tabs' => array(
        'Info. B&aacute;sica' => array(
            'id' => 'puntoventa_tab_' . Yii::app()->controller->module->infoBasica,
            'content' => $this->renderPartial("_infobasica", array('model' => $model, 'tab' => Yii::app()->controller->module->infoBasica, 'active' => $active == Yii::app()->controller->module->infoBasica ? true : false), true),
        ),
        'Directorio' => array(
            'id' => 'puntoventa_tab_' . Yii::app()->controller->module->infoContacto,
            'content' => $this->renderPartial("_contacto", array('model' => $model, 'tab' => Yii::app()->controller->module->infoContacto, 'telefonos' => $telefonos, 'active' => $active == Yii::app()->controller->module->infoContacto ? true : false), true),
        ),
        'Otros' => array(
            'id' => 'puntoventa_tab_' . Yii::app()->controller->module->infoOtros,
            'content' => $this->renderPartial("_otros", array('model' => $model, 'tab' => Yii::app()->controller->module->infoOtros, 'active' => $active == Yii::app()->controller->module->infoOtros ? true : false), true),
        ),
        /* 'Google Map' => array(
            'id' => 'puntoventa_tab_' . Yii::app()->controller->module->infoGmap,
            'content' => $this->renderPartial("_googlemap", array('model' => $model, 'active' => $active == Yii::app()->controller->module->infoOtros ? true : false), true),
        ),*/
        'Servicios' => array(
            'id' => 'puntoventa_tab_' . Yii::app()->controller->module->infoServicios,
            'content' => $this->renderPartial("/serviciosPuntoVenta/admin", array('model' => $servicios, 'tab' => Yii::app()->controller->module->infoServicios, 'active' => $active == Yii::app()->controller->module->infoServicios ? true : false), true),
        ),
        'Horarios' => array(
            'id' => 'puntoventa_tab_' . Yii::app()->controller->module->infoHorarios,
            'content' => $this->renderPartial("_horarios", array('model' => $model, 'tab' => Yii::app()->controller->module->infoHorarios, 'active' => $active == Yii::app()->controller->module->infoHorarios ? true : false), true),
        ),
        'Empleados' => array(
            'id' => 'puntoventa_tab_' . Yii::app()->controller->module->infoEmpleados,
            'content' => $this->renderPartial("/empleados/admin", array('model' => $empleados, 'tab' => Yii::app()->controller->module->infoEmpleados, 'empDataProvider' => $empDataProvider, 'active' => $active == Yii::app()->controller->module->infoEmpleados ? true : false), true),
        ),
        'Influencia' => array(
            'id' => 'puntoventa_tab_' . Yii::app()->controller->module->infoInfluencia,
            'content' => $this->renderPartial("/influenciaPuntoVenta/admin", array('model' => $barrio, 'dataProvider' => $barrioDataProvider, 'puntoventa' => $model->IDPuntoDeVenta, 'tab' => Yii::app()->controller->module->infoInfluencia, 'active' => $active == Yii::app()->controller->module->infoInfluencia ? true : false), true),
        ),
        'Competencia' => array(
            'id' => 'puntoventa_tab_' . Yii::app()->controller->module->infoCompetencia,
            'content' => $this->renderPartial("/competenciaPuntoVenta/admin", array('model' => $competencia, 'tab' => Yii::app()->controller->module->infoCompetencia, 'active' => $active == Yii::app()->controller->module->infoCompetencia ? true : false), true),
        ),
        'Im&aacute;genes' => array(
            'id' => 'puntoventa_tab_' . Yii::app()->controller->module->infoImagenes,
            'content' => $this->renderPartial("/imagenPuntoVenta/admin", array('model' => $imagenes, 'tab' => Yii::app()->controller->module->infoImagenes, 'active' => $active == Yii::app()->controller->module->infoImagenes ? true : false), true),
        ),
        'Historial' => array(
            'id' => 'puntoventa_tab_' . Yii::app()->controller->module->infoHistorial,
            'content' => $this->renderPartial("/aperturaCierrePuntoVenta/admin", array('model' => $historial, 'tab' => Yii::app()->controller->module->infoHistorial, 'active' => $active == Yii::app()->controller->module->infoHistorial ? true : false), true),
        ),
        
    ),
    'headerTemplate' => '<li><a href="{url}" title="{title}">{title}</a></li>',
    'options' => array(
        'collapsible' => false,
        'active' => $active,
    ),
));
