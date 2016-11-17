<?php
Yii::app()->clientScript->registerScript('sede-admin', "
  $('#sede-add-button').click(function(){
    $.ajax({
        type: 'POST',
        dataType : 'json',
        async: true,
        url: '" . Yii::app()->createUrl('/puntoventa/sedes/crear') . "',
        data: {render: true},
        beforeSend: function(){Loading.show();},
        success: function(data){
            Loading.hide();
            if(data.result=='ok'){
                $('#sede-form-modal .modal-body').html(data.response.form);
                $('#sede-form-modal .modal-header h4').html('Creaci&oacute;n de Sede');
                $('#sede-form-modal').modal({backdrop: 'static', show: true});
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

<div class="modal" id="sede-form-modal">
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
$this->pageTitle = Yii::app()->name . ' - Puntos de Venta - Sedes';

$this->breadcrumbs = array(
    'Inicio' => array('/puntoventa'),
    'Admin' => array('/puntoventa/admin'),
    'Sedes'
);
?>

<div class="well" align="center">
<?php echo CHtml::button('Crear Sede', array('id' => 'sede-add-button', 'class' => 'btn btn-primary btn-sm')); ?>
</div>

<?php
$optEmpleado = array(
    "showAnim" => 'fold',
    "minLength" => '2',
    "select" => 'function(event, ui) { $(\'#GerenteAutoComplete\').val(ui.item.value);}',
    "source" => $this->createUrl("usuario/ajax"),
);

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'sedes-grid',
    'beforeAjaxUpdate' => new CJavaScriptExpression("function() {Loading.show();}"),
    'afterAjaxUpdate' => new CJavaScriptExpression("function() {Loading.hide(); $('#GerenteAutoComplete').autocomplete(" . CJSON::encode($optEmpleado) . ");}"),
    'ajaxUpdateError' => new CJavaScriptExpression("function() {Loading.hide(); bootbox.alert('Error, intente de nuevo');}"),
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'CodigoSede',
        'NombreSede',
        array(
            'header' => 'Gerente Operativo',
            'name' => 'CedulaGerenteOperativo',
            'value' => '$data->CedulaGerenteOperativo . \' - \' . $data->NombreGerenteOperativo',
            'filter' => $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                'model' => $model,
                'attribute' => 'CedulaGerenteOperativo',
                'source' => $this->createUrl("usuario/ajax"),
                'options' => array(
                    'showAnim' => 'fold',
                    'minLength' => '2',
                    'select' => 'js:function(event, ui) {
                        $("#GerenteAutoComplete").val(ui.item.value);
                    }',
                ),
                'htmlOptions' => array('id' => 'GerenteAutoComplete')), true),
        ),
        'CelularSede',
        'DireccionSede',
        'TelefonoSede',
        'IndicativoTelefonoSede',
        array(
            'class' => 'CButtonColumn',
            'template' => '{update} {delete}',
            'buttons' => array(
                'delete' => array(
                    'label' => 'Eliminar',
                    'url' => '$data->IDSede',
                    'options' => array(
                        'confirm' => "¿Esta seguro de eliminar este registro?",
                        'ajax' => array(
                            'type' => 'POST',
                            'dataType' => 'json',
                            'async' => true,
                            'url' => Yii::app()->createUrl('/puntoventa/sedes/eliminar'),
                            'data' => array('sede' => "js: $(this).attr('href')"),
                            'beforeSend' => new CJavaScriptExpression("function(){Loading.show();}"),
                            'success' => new CJavaScriptExpression("function(data){
                                Loading.hide();
                                if(data.result=='ok'){
                                    $.fn.yiiGridView.update('sedes-grid');
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
                            url: '" . Yii::app()->createUrl('/puntoventa/sedes/actualizar') . "',
                            data: {render: true, sede: $(this).attr('href')},
                            beforeSend: function(){Loading.show();},
                            success: function(data){
                                Loading.hide();
                                if(data.result=='ok'){
                                    $('#sede-form-modal .modal-body').html(data.response.form);
                                    $('#sede-form-modal .modal-header h4').html('Actualizar Sede');
                                    $('#sede-form-modal').modal({backdrop: 'static', show: true});
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
                    'url' => '$data->IDSede',
                ),
            )
        ),
    ),
));
?>
