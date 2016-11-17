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
    Yii::app()->clientScript->registerScript('servicios-admin', "
  $('#servicios-add-button').click(function(){
    $.ajax({
        type: 'POST',
        dataType : 'json',
        async: true,
        url: '" . Yii::app()->createUrl('/puntoventa/serviciosPuntoVenta/crear') . "',
        data: {render: true, puntoventa: $model->puntoventa_search},
        beforeSend: function(){Loading.show();},
        success: function(data){
            if(data.result=='ok'){
                $('#servicios-form-modal .modal-body').html(data.response.form);
                $('#servicios-form-modal .modal-header h4').html('Agregar servicio');
                $('#servicios-form-modal').modal({backdrop: 'static', show: true});
                Loading.hide();
            }else{
                bootbox.alert(data.response);
                Loading.hide();
            }
        },
        error: function(data){
            Loading.hide();
            bootbox.alert('Error, intente de nuevo');
        }
    });

    return false;
  });");
    ?>

    <div class="modal" id="servicios-form-modal">
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

    <?php echo CHtml::button('Agregar servicio', array('id' => 'servicios-add-button', 'class' => 'btn btn-primary btn-large')); ?>
<?php endif; ?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'servicios-grid',
    'beforeAjaxUpdate' => new CJavaScriptExpression("function() {Loading.show();}"),
    'afterAjaxUpdate' => new CJavaScriptExpression("function() {Loading.hide();}"),
    'ajaxUpdateError' => new CJavaScriptExpression("function() {Loading.hide(); bootbox.alert('Error, intente de nuevo');}"),
    'ajaxUrl' => ($consulta ? $this->createUrl('/puntoventa/puntoVenta/ver', array('id'=>$model->puntoventa_search)) : $this->createUrl('/puntoventa/puntoVenta/actualizar', array('id'=>$model->puntoventa_search))),
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array(
            'name' => 'categoria_search',
            'value' => '$data->categoriaTipoServicio->CategoriaTipoDeServicio'
        ),
        //'IDTipoServicio',
        //'IDCategoriaTipoServicio',
        'NombreTipoServicio',
        array(
            'class' => 'CButtonColumn',
            'template' => ($consulta ? false : '{delete}'),
            'buttons' => array(
                'delete' => array(
                    'label' => 'Eliminar',
                    'url' => 'Yii::app()->createUrl("/puntoventa/serviciosPuntoVenta/eliminar", array("id"=>$data->IDTipoServicio, "idpv"=>' . $model->puntoventa_search . '))',
                )
            )
        ),
    ),
));
?>

<div class="row">
    <div class="footer">
        <ul class="pager">
            <li class="previous" id="id_servicio_anterior"><a href="#" class="anterior">← Anterior</a></li>
            <li class="next" id="id_servicio_siguiente"><a href="#" class="siguiente">Siguiente →</a></li>
        </ul>
    </div>
</div>

<?php
Yii::app()->clientScript->registerScript('servicios-tab', "
$(document).on('click', '#id_servicio_anterior', function(e) {
    $('#puntoventa_tabs').tabs({ active: " . ($tab - 1) . "});
});
$(document).on('click', '#id_servicio_siguiente', function(e) {
    $('#puntoventa_tabs').tabs({ active: " . ($tab + 1) . "});
});");
?>



