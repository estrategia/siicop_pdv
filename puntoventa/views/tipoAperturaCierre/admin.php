<?php
Yii::app()->clientScript->registerScript('taperturacierre-admin', "
  $('#taperturacierre-add-button').click(function(){
    $.ajax({
        type: 'POST',
        dataType : 'json',
        async: true,
        url: '" . Yii::app()->createUrl('/puntoventa/tipoAperturaCierre/crear') . "',
        data: {render: true},
        beforeSend: function(){Loading.show();},
        success: function(data){
            Loading.hide();
            if(data.result=='ok'){
                $('#taperturacierre-form-modal .modal-body').html(data.response.form);
                $('#taperturacierre-form-modal .modal-header h4').html('Creaci&oacute;n Tipo Apertura Cierre');
                $('#taperturacierre-form-modal').modal({backdrop: 'static', show: true});
            }else
                bootbox.alert(data.response);
        },
        error: function(data){
            Loading.hide();
            bootbox.alert('Error, intente de nuevo');
        }
    });

    return false;
  });");
?>

<div class="modal" id="taperturacierre-form-modal">
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
$this->pageTitle = Yii::app()->name . ' - Puntos de Venta - Tipo Apertura Cierre';

$this->breadcrumbs = array(
    'Inicio' => array('/puntoventa'),
    'Admin' => array('/puntoventa/admin'),
    'Tipo Apertura Cierre'
);
?>

<div class="well" align="center">
    <?php echo CHtml::button('Crear Tipo Apertura Cierre', array('encode'=>false, 'id' => 'taperturacierre-add-button', 'class' => 'btn btn-primary btn-sm')); ?>
</div>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'taperturacierre-grid',
    'beforeAjaxUpdate' => new CJavaScriptExpression("function() {Loading.show();}"),
    'afterAjaxUpdate' => new CJavaScriptExpression("function() {Loading.hide();}"),
    'ajaxUpdateError' => new CJavaScriptExpression("function() {Loading.hide(); bootbox.alert('Error, intente de nuevo');}"),
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'IDTipoAperturaCierre',
        'NombreTipoAperturaCierre',
        array(
            'class' => 'CButtonColumn',
            'template' => '{update} {delete}',
            'buttons' => array(
                'delete' => array(
                    'label' => 'Eliminar',
                    'url' => 'Yii::app()->createUrl("/puntoventa/tipoAperturaCierre/eliminar", array("id"=>$data->IDTipoAperturaCierre))',
                ),
                'update' => array(
                    'label' => 'Editar',
                    'click' => "function(){
                        $.ajax({
                            type: 'POST',
                            dataType : 'json',
                            async: true,
                            url: '" . Yii::app()->createUrl('/puntoventa/tipoAperturaCierre/actualizar') . "',
                            data: {render: true, tipoac: $(this).attr('href')},
                            beforeSend: function(){Loading.show();},
                            success: function(data){
                                Loading.hide();
                                if(data.result=='ok'){
                                    $('#taperturacierre-form-modal .modal-body').html(data.response.form);
                                    $('#taperturacierre-form-modal .modal-header h4').html('Actualizar Tipo Apertura Cierre');
                                    $('#taperturacierre-form-modal').modal({backdrop: 'static', show: true});
                                }else
                                    bootbox.alert(data.response);
                            },
                            error: function(data){
                                Loading.hide();
                                bootbox.alert('Error, intente de nuevo');
                            }
                        });
                       
                        return false;
                    }",
                    'url' => '$data->IDTipoAperturaCierre',
                ),
            )
        ),
    ),
));
?>