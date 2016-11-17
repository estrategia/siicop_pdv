<?php if ($active): ?>
    <?php foreach (Yii::app()->user->getFlashes() as $key => $message) { ?>
        <div class="alert alert-dismissable alert-<?php echo $key ?>">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <?php echo $message ?>
        </div>
    <?php } ?>
<?php endif; ?>

<?php if (!$consulta): ?>
    <?php
    Yii::app()->clientScript->registerScript('historial-admin', "
  $('#historial-add-button').click(function(){
    $.ajax({
        type: 'POST',
        dataType : 'json',
        async: true,
        url: '" . Yii::app()->createUrl('/puntoventa/aperturaCierrePuntoVenta/crear') . "',
        data: {render: true, puntoventa: $model->IDPuntoDeVenta},
        beforeSend: function(){Loading.show();},
        success: function(data){
            Loading.hide();
            if(data.result=='ok'){
                $('#historial-form-modal .modal-body').html(data.response.form);
                $('#historial-form-modal .modal-header h4').html('Creaci&oacute;n de historial');
                $('#historial-form-modal').modal({backdrop: 'static', show: true});
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

    <div class="modal" id="historial-form-modal">
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

    <?php echo CHtml::button('Crear historial', array('id' => 'historial-add-button', 'class' => 'btn btn-primary btn-large')); ?>
<?php endif; ?>

<?php
$optTipo = array(
    "showAnim" => 'fold',
    "minLength" => '2',
    "select" => 'function(event, ui) { $(\'#TipoAutoComplete\').val(ui.item.value);}',
    "source" => $this->createUrl("/puntoventa/aperturaCierrePuntoVenta/tipoAutocomplete"),
);
$optTipo = "$('#TipoAutoComplete').autocomplete(" . CJSON::encode($optTipo) . ");";

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'apertura-cierre-punto-venta-grid',
    'beforeAjaxUpdate' => new CJavaScriptExpression("function() {Loading.show();}"),
    'afterAjaxUpdate' => new CJavaScriptExpression("function() { Loading.hide(); $optTipo}"),
    'ajaxUpdateError' => new CJavaScriptExpression("function(xhr, textStatus, errorThrown, errorMessage) {Loading.hide(); bootbox.alert(errorMessage);}"),
    'ajaxUrl' => ($consulta ? $this->createUrl('/puntoventa/puntoVenta/ver', array('id'=>$model->IDPuntoDeVenta)) : $this->createUrl('/puntoventa/puntoVenta/actualizar', array('id'=>$model->IDPuntoDeVenta))),
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        //'IDAperturaCierrePuntoDeVenta',
        //'IDPuntoDeVenta',
        'FechaAperturaCierre',
        //'TipoAperturaCierre',
        array(
            'name' => 'IDTipoAperturaCierre',
            'value' => '$data->tipoAperturaCierre->NombreTipoAperturaCierre',
            'filter' => $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                'model' => $model,
                'attribute' => 'IDTipoAperturaCierre',
                'source' => $this->createUrl("/puntoventa/aperturaCierrePuntoVenta/tipoAutocomplete"),
                'options' => array(
                    'showAnim' => 'fold',
                    'minLength' => '2',
                    'select' => 'js:function(event, ui) {
                        $("#TipoAutoComplete").val(ui.item.value);
                    }',
                ),
                'htmlOptions' => array('id' => 'TipoAutoComplete')), true),
        ),
        /* array(
          'filter' => array('1' => 'Activo', '0' => 'Inactivo'),
          'type' => 'raw',
          'name' => 'TipoAperturaCierre',
          'value' => '($data->TipoAperturaCierre==1 ? \'<span title="Activo" alt="Activo" class="icon icon-green icon-folder-open"></span>\' : \'<span title="Inactivo" alt="Inactivo" class="icon icon-red icon-folder-collapsed"></span>\') '
          ), */
        'FechaRegistroAperturaCierre',
        'ObservacionesAperturaCierre',
        array(
            'class' => 'CButtonColumn',
            'template' => ($consulta ? false : '{update} {delete}'),
            'buttons' => array(
                'delete' => array(
                    'label' => 'Eliminar',
                    'url' => 'Yii::app()->createUrl("/puntoventa/aperturaCierrePuntoVenta/eliminar", array("id"=>$data->IDAperturaCierrePuntoDeVenta))',
                ),
                'update' => array(
                    'label' => 'Editar',
                    'click' => "function(){
                        $.ajax({
                            type: 'POST',
                            dataType : 'json',
                            async: true,
                            url: '" . Yii::app()->createUrl('/puntoventa/aperturaCierrePuntoVenta/actualizar') . "',
                            data: {render: true, historial: $(this).attr('href')},
                            beforeSend: function(){Loading.show();},
                            success: function(data){
                                Loading.hide();
                                if(data.result=='ok'){
                                    $('#historial-form-modal .modal-body').html(data.response.form);
                                    $('#historial-form-modal .modal-header h4').html('Actualizar historial');
                                    $('#historial-form-modal').modal({backdrop: 'static', show: true});
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
                    'url' => '$data->IDAperturaCierrePuntoDeVenta',
                ),
            )
        ),
    ),
));
?>

<div class="row">
    <div class="footer">
        <ul class="pager">
            <li class="previous" id="id_historial_anterior"><a href="#" class="anterior">← Anterior</a></li>
        </ul>
    </div>
</div>

<?php
Yii::app()->clientScript->registerScript('historial-tab', "
$(document).on('click', '#id_historial_anterior', function(e) {
    $('#puntoventa_tabs').tabs({ active: " . ($tab - 1) . "});
});
");
?>