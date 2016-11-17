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
Yii::app()->clientScript->registerScript('influencia-admin', "
  $('#influencia-add-button').click(function(){
    $.ajax({
        type: 'POST',
        dataType : 'json',
        async: true,
        url: '" . Yii::app()->createUrl('/puntoventa/influenciaPuntoVenta/crear') . "',
        data: {render: true, puntoventa: $puntoventa},
        beforeSend: function(){Loading.show();},
        success: function(data){
            if(data.result=='ok'){
                $('#influencia-form-modal .modal-body').html(data.response.form);
                $('#influencia-form-modal .modal-header h4').html('Agregar barrio');
                $('#influencia-form-modal').modal({backdrop: 'static', show: true});
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

<div class="modal" id="influencia-form-modal">
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


<?php echo CHtml::button('Agregar Barrio', array('id' => 'influencia-add-button', 'class' => 'btn btn-primary btn-large')); ?>
<?php endif; ?>

<?php
$optCiudad = array(
    "showAnim" => 'fold',
    "minLength" => '2',
    "select" => 'function(event, ui) { $(\'#CiudadAutoComplete\').val(ui.item.value);}',
    "source" => $this->createUrl("ciudad/ajax"),
);
$optCiudad = "$('#CiudadAutoComplete').autocomplete(" . CJSON::encode($optCiudad) . ");";

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'influencia-grid',
    'beforeAjaxUpdate' => new CJavaScriptExpression("function() {Loading.show();}"),
    'afterAjaxUpdate' => new CJavaScriptExpression("function() {Loading.hide(); $optCiudad}"),
    'ajaxUpdateError' => new CJavaScriptExpression("function(xhr, textStatus, errorThrown, errorMessage) {Loading.hide(); bootbox.alert(errorMessage);}"),
    'ajaxUrl' => ($consulta ? $this->createUrl('/puntoventa/puntoVenta/ver', array('id'=>$puntoventa)) : $this->createUrl('/puntoventa/puntoVenta/actualizar', array('id'=>$puntoventa))),
    'dataProvider' => $dataProvider,
    'filter' => $model,
    'columns' => array(
        array(
            'header' => 'Ciudad',
            'value' => '$data->ciudad->NombreCiudad',
            'filter' => $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                //'name' => 'Barrio_CodCiudad',
                'model' => $model,
                'attribute' => 'IdCiudad',
                'value'=>$model->IdCiudad,
                'source' => $this->createUrl("ciudad/ajax"),
                'options' => array(
                    'showAnim' => 'fold',
                    'minLength' => '2',
                    'select' => 'js:function(event, ui) {
                        $("#CiudadAutoComplete").val(ui.item.value);
                    }',
                ),
                'htmlOptions' => array('id' => 'CiudadAutoComplete')), true),
        ),
        'NombreBarrio',
        array(
            'class' => 'CButtonColumn',
            'template' => ($consulta ? false : '{delete}'),
            'buttons' => array(
                'delete' => array(
                    'label' => 'Eliminar',
                    'url' => 'Yii::app()->createUrl("/puntoventa/influenciaPuntoVenta/eliminar", array("idpdv"=>'.$puntoventa.', "idbarrio"=>$data->IdBarrio))',
                )
            )
        ),
    ),
));
?>

<div class="row">
    <div class="footer">
        <ul class="pager">
            <li class="previous" id="id_influencia_anterior"><a href="#" class="anterior">← Anterior</a></li>
            <li class="next" id="id_influencia_siguiente"><a href="#" class="siguiente">Siguiente →</a></li>
        </ul>
    </div>
</div>

<?php
Yii::app()->clientScript->registerScript('influencia-tab', "
$(document).on('click', '#id_influencia_anterior', function(e) {
    $('#puntoventa_tabs').tabs({ active: " . ($tab - 1) . "});
});
$(document).on('click', '#id_influencia_siguiente', function(e) {
    $('#puntoventa_tabs').tabs({ active: " . ($tab + 1) . "});
});");
?>