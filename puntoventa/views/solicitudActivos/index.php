<?php
$this->pageTitle = Yii::app()->name . ' - Puntos de Venta - Solicitud Activos';

$this->breadcrumbs = array(
    'Inicio' => array('/'),
    'PDV - Solicitud Activos',
);
?>

<div class="well" align="center">
    <?php echo CHtml::link('Solicitar activos', $this->createUrl('/puntoventa/solicitudActivos/solicitar'), array('class' => 'btn btn-primary btn-sm')); ?>
</div>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'activos-grid',
    'beforeAjaxUpdate' => new CJavaScriptExpression("function() {Loading.show();}"),
    'afterAjaxUpdate' => new CJavaScriptExpression("function() {Loading.hide();}"),
    'ajaxUpdateError' => new CJavaScriptExpression("function(xhr, textStatus, errorThrown, errorMessage) {Loading.hide(); bootbox.alert(errorMessage);}"),
    'dataProvider' => $model->searchSolicitados(),
    'ajaxUrl' => $this->createUrl('index'),
    'filter' => $model,
    'columns' => array(
        /*array(
            'name' => 'IDPuntoDeVenta',
            'value' => '$data->puntoVenta->IDComercial',
        ),*/
        array(
            'header' => 'Activo',
            'name' => 'IdActivo',
            'value' => '$data->activo->DescripcionActivo',
        ),
        'Cantidad',
        'ObservacionSolicitante',
        'ObservacionAprobador',
        array(
            'name' => 'Estado',
            'value' => 'Yii::app()->getModule("puntoventa")->estadoSolicitudActivo[$data->Estado]',
            'filter' => Yii::app()->getModule("puntoventa")->listEstadosSolicitudActivo,
        ),
        array(
            'name' => 'FechaSolicitud',
            'filter' => false,
        ),
    ),
));
?>
