
<?php 
Yii::app()->clientScript->registerScript('activoEstadoActualizarSolicitud', "
$(document).on('hidden.bs.modal', '#estado-form-modal', function() {
    $.fn.yiiGridView.update('solicitudes-grid');
    $('#estado-form-modal .modal-body').html('');
});

$(document).on('change', 'select[data-role=\"selectestadosolicitud\"]', function() {
    var element = $(this);
    $.ajax({
        type: 'POST',
        dataType : 'json',
        url: '" . Yii::app()->createUrl('/puntoventa/aprobacionActivos/estado') . "',
        data: {render: true, solicitud: $(this).attr('data-id'), estado: $(this).val()},
        beforeSend: function(){ Loading.show();},
        success: function(data){
            if(data.result=='ok'){
                $('#estado-form-modal .modal-body').html(data.response.form);
                $('#estado-form-modal').modal('show');
            }else{
                element.val(element.attr('data-estado'));
                bootbox.alert(data.response);
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            element.val(element.attr('data-estado'));
            bootbox.alert('Error: ' + errorThrown);
        },
        complete: function(){Loading.hide();},
    });
});
", CClientScript::POS_END); 
?>

<?php
Yii::app()->clientScript->registerScript('exportarActivos', "
$('#btn-repactivos').click(function(){
    $('#div-form-exportar').toggle();
        return false;
});", CClientScript::POS_END);
?>

<div class="modal" id="estado-form-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<?php
$this->pageTitle = Yii::app()->name . ' - Puntos de Venta - Aprobación Activos';

$this->breadcrumbs = array(
    'Inicio' => array('/'),
    'PDV - Aprobación Activos',
);
?>

<div class="well" align="center">
    <?php echo CHtml::link('Reporte activos', '#', array('class' => 'btn btn-primary btn-sm', 'id' => 'btn-repactivos')); ?>
</div>

<div id="div-form-exportar" style="display:<?php echo ($modelReporte->hasErrors() ? "block" : "none")  ?>">
    <?php $this->renderPartial('_formExportar', array('model'=> $modelReporte)); ?>
</div>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'solicitudes-grid',
    'beforeAjaxUpdate' => new CJavaScriptExpression("function() {Loading.show();}"),
    'afterAjaxUpdate' => new CJavaScriptExpression("function() {Loading.hide();}"),
    'ajaxUpdateError' => new CJavaScriptExpression("function(xhr, textStatus, errorThrown, errorMessage) {Loading.hide(); bootbox.alert(errorMessage);}"),
    'dataProvider' => $model->searchAprobacion($zona),
    'ajaxUrl' => $this->createUrl('index'),
    'filter' => $model,
    'columns' => array(
        array(
            'header'=> 'COD ALF',
            'name' => 'IDPuntoDeVenta',
            'value' => '$data->puntoVenta->IDComercial',
        ),
        array(
            'header'=> 'Punto Venta',
            'filter' => false,
            'value' => '$data->puntoVenta->NombrePuntoDeVenta',
        ),
        array(
            'header'=> 'Sede',
            'filter' => false,
            'value' => '$data->puntoVenta->sede->NombreSede',
        ),
        array(
            'header'=> 'Zona',
            'filter' => false,
            'value' => '$data->puntoVenta->zona->NombreZona',
        ),
        array(
            'name' => 'IdActivo',
            'value' => '$data->IdActivo ." - ". $data->activo->DescripcionActivo',
        ),
        array(
            'name' => 'Cantidad',
            'filter' => false,
            'value' => '$data->Cantidad',
        ),
        'ObservacionSolicitante',
        'ObservacionAprobador',
        'FechaSolicitud',
        array(
            'name' => 'Estado',
            'type' => 'raw',
            'value' => array($this, 'gridEstado'),
            'filter' => Yii::app()->getModule("puntoventa")->listEstadosSolicitudActivo,
        ),
    ),
));
?>
