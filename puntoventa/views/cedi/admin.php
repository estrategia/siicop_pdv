<?php
Yii::app()->clientScript->registerScript('cedi-admin', "
  $('#cedi-add-button').click(function(){
    $.ajax({
        type: 'POST',
        dataType : 'json',
        async: true,
        url: '" . Yii::app()->createUrl('/puntoventa/cedi/crear') . "',
        data: {render: true},
        beforeSend: function(){Loading.show();},
        success: function(data){
            Loading.hide();
            if(data.result=='ok'){
                $('#cedi-form-modal .modal-body').html(data.response.form);
                $('#cedi-form-modal .modal-header h4').html('Creaci&oacute;n de CEDI');
                $('#cedi-form-modal').modal({backdrop: 'static', show: true});
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

<div class="modal" id="cedi-form-modal">
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
$this->pageTitle = Yii::app()->name . ' - Puntos de Venta - CEDI';

$this->breadcrumbs = array(
    'Inicio' => array('/puntoventa'),
    'Admin' => array('/puntoventa/admin'),
    'Cedi'
);
?>

<div class="well" align="center">
    <?php echo CHtml::button('Crear CEDI', array('id' => 'cedi-add-button', 'class' => 'btn btn-primary btn-sm')); ?>
</div>


<?php
$optEmpleado = array(
    "showAnim" => 'fold',
    "minLength" => '2',
    "select" => 'function(event, ui) { $(\'#JefeAutoComplete\').val(ui.item.value);}',
    "source" => $this->createUrl("usuario/ajax"),
);
$optEmpleado = "$('#JefeAutoComplete').autocomplete(" . CJSON::encode($optEmpleado) . ");";

$optCosto = array(
    "showAnim" => 'fold',
    "minLength" => '2',
    "select" => 'function(event, ui) { $(\'#CcostoAutoComplete\').val(ui.item.value);}',
    "source" => $this->createUrl("ccostos/ajax"),
);
$optCosto = "$('#CcostoAutoComplete').autocomplete(" . CJSON::encode($optCosto) . ");";

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'cedi-grid',
    'beforeAjaxUpdate' => new CJavaScriptExpression("function() {Loading.show();}"),
    'afterAjaxUpdate' => new CJavaScriptExpression("function() {Loading.hide(); $optEmpleado $optCosto}"),
    'ajaxUpdateError' => new CJavaScriptExpression("function() {Loading.hide(); bootbox.alert('Error, intente de nuevo');}"),
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'NombreCEDI',
        array(
            'name' => 'IdCentroCostos',
            'value' => '$data->IdCentroCostos . \' - \' . $data->NombreCentroCostos',
            
            'filter' => $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                'model' => $model,
                'attribute' => 'IdCentroCostos',
                'source' => $this->createUrl("ccostos/ajax"),
                'options' => array(
                    'showAnim' => 'fold',
                    'minLength' => '2',
                    'select' => 'js:function(event, ui) {
                        $("#CcostoAutoComplete").val(ui.item.value);
                    }',
                ),
                'htmlOptions' => array('id' => 'CcostoAutoComplete')), true),
        ),
        array(
            'header' => 'Jefe',
            'name' => 'CedulaJefe',
            'value' => '$data->CedulaJefe . \' - \' . $data->NombreJefe',
            'filter' => $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                'model' => $model,
                'attribute' => 'CedulaJefe',
                'source' => $this->createUrl("usuario/ajax"),
                'options' => array(
                    'showAnim' => 'fold',
                    'minLength' => '2',
                    'select' => 'js:function(event, ui) {
                        $("#JefeAutoComplete").val(ui.item.value);
                    }',
                ),
                'htmlOptions' => array('id' => 'JefeAutoComplete')), true),
        ),
        'CelularJefe',
        'TelefonoCEDI',
        'IndicativoTelefonoCEDI',
        'DireccionCEDI',
        'CodigoEAN',
        'HorarioAperturaLunesAViernes',
        'HorarioCierreLunesAViernes',
        'HorarioAperturaSabado',
        'HorarioCierreSabado',
        array(
            'class' => 'CButtonColumn',
            'template' => '{update} {delete}',
            'buttons' => array(
                'delete' => array(
                    'label' => 'Eliminar',
                    'url' => '$data->IDCEDI',
                    'options' => array(
                        'confirm' => "¿Esta seguro de eliminar este registro?",
                        'ajax' => array(
                            'type' => 'POST',
                            'dataType' => 'json',
                            'async' => true,
                            'url' => Yii::app()->createUrl('/puntoventa/cedi/eliminar'),
                            'data' => array('cedi' => "js: $(this).attr('href')"),
                            'beforeSend' => new CJavaScriptExpression("function(){Loading.show();}"),
                            'success' => new CJavaScriptExpression("function(data){
                                Loading.hide();
                                if(data.result=='ok'){
                                    $.fn.yiiGridView.update('cedi-grid');
                                }else
                                    bootbox.alert(data.response);
                            }"),
                            'error' => new CJavaScriptExpression("function(data){
                                Loading.hide();
                                bootbox.alert('Error, intente de nuevo');
                            }")
                        )
                    )
                ),
                'update' => array(
                    'label' => 'Editar',
                    'click' => "function(){
                        $.ajax({
                            type: 'POST',
                            dataType : 'json',
                            async: true,
                            url: '" . Yii::app()->createUrl('/puntoventa/cedi/actualizar') . "',
                            data: {render: true, cedi: $(this).attr('href')},
                            beforeSend: function(){Loading.show();},
                            success: function(data){
                                Loading.hide();
                                if(data.result=='ok'){
                                    $('#cedi-form-modal .modal-body').html(data.response.form);
                                    $('#cedi-form-modal .modal-header h4').html('Actualizar datos de CEDI');
                                    $('#cedi-form-modal').modal({backdrop: 'static', show: true});
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
                    'url' => '$data->IDCEDI',
                ),
            )
        ),
    ),
));
?>
