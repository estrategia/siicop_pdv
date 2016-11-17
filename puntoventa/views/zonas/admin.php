<?php
Yii::app()->clientScript->registerScript('zona-admin', "
  $('#zona-add-button').click(function(){
    $.ajax({
        type: 'POST',
        dataType : 'json',
        async: true,
        url: '" . Yii::app()->createUrl('/puntoventa/zonas/crear') . "',
        data: {render: true},
        beforeSend: function(){Loading.show();},
        success: function(data){
            Loading.hide();
            if(data.result=='ok'){
                $('#zona-form-modal .modal-body').html(data.response.form);
                $('#zona-form-modal .modal-header h4').html('Creaci&oacute;n de Zona');
                $('#zona-form-modal').modal({backdrop: 'static', show: true});
                $('#zona-form-modal .modal-dialog').css('width','50%');
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

<div class="modal" id="zona-form-modal">
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
$this->pageTitle = Yii::app()->name . ' - Puntos de Venta - Zonas';

$this->breadcrumbs = array(
    'Inicio' => array('/puntoventa'),
    'Admin' => array('/puntoventa/admin'),
    'Zonas'
);
?>

<div class="well" align="center">
    <?php echo CHtml::button('Crear Zona', array('id' => 'zona-add-button', 'class' => 'btn btn-primary btn-sm')); ?>
</div>

<?php
$optDir = array(
    "showAnim" => 'fold',
    "minLength" => '2',
    "select" => 'function(event, ui) { $(\'#DirZonaAutoComplete\').val(ui.item.value);}',
    "source" => $this->createUrl("usuario/ajax"),
);

$optAux = array(
    "showAnim" => 'fold',
    "minLength" => '2',
    "select" => 'function(event, ui) { $(\'#AuxZonaAutoComplete\').val(ui.item.value);}',
    "source" => $this->createUrl("usuario/ajax"),
);

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'zonas-grid',
    'beforeAjaxUpdate' => new CJavaScriptExpression("function() {Loading.show();}"),
    'afterAjaxUpdate' => new CJavaScriptExpression("function() {Loading.hide(); $('#DirZonaAutoComplete').autocomplete(" . CJSON::encode($optDir) . "); $('#AuxZonaAutoComplete').autocomplete(" . CJSON::encode($optAux) . ");}"),
    'ajaxUpdateError' => new CJavaScriptExpression("function() {Loading.hide(); bootbox.alert('Error, intente de nuevo');}"),
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        //'IDZona',
        //'IDSede',
        array(
            'name' => 'sede_search',
            'value' => '$data->sede->NombreSede'
        ),
        'NombreZona',
        'DireccionZona',
        //'TelefonoZona',
        array(
            'header' => 'Tel&eacute;fonos',
            'value' => '$data->telefonostxt'
        ),
        
        
        'CelularZona',
        'eMailZona',
        array(
            'header' => 'Director Zona',
            'name' => 'CedulaDirectorZona',
            'value' => '$data->CedulaDirectorZona . \' - \' . $data->NombreDirectorZona',
            'filter' => $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                'model' => $model,
                'attribute' => 'CedulaDirectorZona',
                'source' => $this->createUrl("usuario/ajax"),
                'options' => array(
                    'showAnim' => 'fold',
                    'minLength' => '2',
                    'select' => 'js:function(event, ui) {
                        $("#DirZonaAutoComplete").val(ui.item.value);
                    }',
                ),
                'htmlOptions' => array('id' => 'DirZonaAutoComplete')), true),
        ),
        'eMailDirectorZona',
        array(
            'header' => 'Auxiliar Zona',
            'name' => 'CedulaAuxiliarZona',
            'value' => '$data->CedulaAuxiliarZona . \' - \' . $data->NombreAuxiliarZona',
            'filter' => $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                'model' => $model,
                'attribute' => 'CedulaAuxiliarZona',
                'source' => $this->createUrl("usuario/ajax"),
                'options' => array(
                    'showAnim' => 'fold',
                    'minLength' => '2',
                    'select' => 'js:function(event, ui) {
                        $("#AuxZonaAutoComplete").val(ui.item.value);
                    }',
                ),
                'htmlOptions' => array('id' => 'AuxZonaAutoComplete')), true),
        ),
        'IndicativoTelefonoZona',
        array(
            'class' => 'CButtonColumn',
            'template' => '{update} {delete}',
            'buttons' => array(
                'delete' => array(
                    'label' => 'Eliminar',
                    'url' => '$data->IDZona',
                    'options' => array(
                        'confirm' => "¿Esta seguro de eliminar este registro?",
                        'ajax' => array(
                            'type' => 'POST',
                            'dataType' => 'json',
                            'async' => true,
                            'url' => Yii::app()->createUrl('/puntoventa/zonas/eliminar'),
                            'data' => array('zona' => "js: $(this).attr('href')"),
                            'beforeSend' => new CJavaScriptExpression("function(){Loading.show();}"),
                            'success' => new CJavaScriptExpression("function(data){
                                Loading.hide();
                                if(data.result=='ok'){
                                    $.fn.yiiGridView.update('zonas-grid');
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
                            url: '" . Yii::app()->createUrl('/puntoventa/zonas/actualizar') . "',
                            data: {render: true, zona: $(this).attr('href')},
                            beforeSend: function(){Loading.show();},
                            success: function(data){
                                Loading.hide();
                                if(data.result=='ok'){
                                    $('#zona-form-modal .modal-body').html(data.response.form);
                                    $('#zona-form-modal .modal-header h4').html('Actualizar Zona');
                                    $('#zona-form-modal').modal({backdrop: 'static', show: true});
                                    $('#zona-form-modal .modal-dialog').css('width','50%');
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
                    'url' => '$data->IDZona',
                ),
            )
        ),
    ),
));
?>
