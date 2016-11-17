<?php if ($active): ?>
    <?php foreach (Yii::app()->user->getFlashes() as $key => $message) { ?>
        <div class="alert alert-dismissable alert-<?php echo $key ?>">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <?php echo $message ?>
        </div>
    <?php } ?>
<?php endif; ?>

<?php

Yii::app()->clientScript->registerScript('search', "
$('#search-form').submit(function(){
    $.fn.yiiGridView.update('empleados-grid', {
        data: $(this).serialize()
    });
    return false;
});
");
?>

<?php

$optEmpleado = array(
    "showAnim" => 'fold',
    "minLength" => '2',
    "select" => 'function(event, ui) { $(\'#EmpleadoAutoComplete\').val(ui.item.value);}',
    "source" => $this->createUrl("usuario/ajax"),
);

$optCargo = array(
    "showAnim" => 'fold',
    "minLength" => '2',
    "select" => 'function(event, ui) { $(\'#CargoAutoComplete\').val(ui.item.value);}',
    "source" => $this->createUrl("cargo/ajax"),
);

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'empleados-grid',
    'beforeAjaxUpdate' => new CJavaScriptExpression("function() {Loading.show();}"),
    'afterAjaxUpdate' => new CJavaScriptExpression("function() {Loading.hide(); $('#EmpleadoAutoComplete').autocomplete(" . CJSON::encode($optEmpleado) . "); $('#CargoAutoComplete').autocomplete(" . CJSON::encode($optCargo) . ");}"),
    'ajaxUpdateError' => new CJavaScriptExpression("function() {Loading.hide(); bootbox.alert('Error, intente de nuevo');}"),
    'ajaxUrl' => ($consulta ? $this->createUrl('/puntoventa/puntoVenta/ver', array('id'=>$puntoventa)) : $this->createUrl('/puntoventa/puntoVenta/actualizar', array('id'=>$puntoventa))),
    'dataProvider' => $empDataProvider,
    'filter' => $model,
    'columns' => array(
        array(
            'filter' => false,
            'header' => 'N&uacute;mero Documento',
            'value' => '$data->NumeroDocumento',
            'visible'=> !$consulta
        ),
        array(
            'header' => 'Empleado',
            'name' => 'NumeroDocumento',
            'value' => '$data->persona->ApellidosNombres',
            'filter' => $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                'model' => $model,
                'attribute' => 'NumeroDocumento',
                'value'=>$model->NumeroDocumento,
                'source' => $this->createUrl("usuario/ajax"),
                'options' => array(
                    'showAnim' => 'fold',
                    'minLength' => '2',
                    'select' => 'js:function(event, ui) {
                        $("#EmpleadoAutoComplete").val(ui.item.value);
                    }',
                ),
                'htmlOptions' => array('id' => 'EmpleadoAutoComplete')), true),
        ),
        array(
            'filter' => false,
            'header' => 'Direcci&oacute;n',
            'value' => '$data->persona->Direccion',
            'visible'=> !$consulta
        ),
        array(
            'header' => 'Cargo',
            'name' => 'IdCargo',
            'value' => '$data->IdCargo . \' - \' . $data->cargo->NombreCargo',
            'filter' => $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                'model' => $model,
                'attribute' => 'IdCargo',
                'value'=>$model->IdCargo,
                'source' => $this->createUrl("cargo/ajax"),
                'options' => array(
                    'showAnim' => 'fold',
                    'minLength' => '2',
                    'select' => 'js:function(event, ui) {
                        $("#CargoAutoComplete").val(ui.item.value);
                    }',
                ),
                'htmlOptions' => array('id' => 'CargoAutoComplete')), true),
        ),
        array(
            'name' => 'Supernumerario',
            'filter' => CHtml::dropDownList('Empleado[Supernumerario]', $model->Supernumerario, array('SI' => 'SI', 'NO' => 'NO'), array('prompt' => 'Todo', 'style'=>'text-align: center;')),
            'value' => '$data->Supernumerario',
        ),
    ),
));
?>

<div class="row">
    <div class="footer">
        <ul class="pager">
            <li class="previous" id="id_empleado_anterior"><a href="#" class="anterior">← Anterior</a></li>
            <li class="next" id="id_empleado_siguiente"><a href="#" class="siguiente">Siguiente →</a></li>
        </ul>
    </div>
</div>

<?php
Yii::app()->clientScript->registerScript('empleado-tab', "
$(document).on('click', '#id_empleado_anterior', function(e) {
    $('#puntoventa_tabs').tabs({ active: ".($tab-1)."});
});
$(document).on('click', '#id_empleado_siguiente', function(e) {
    $('#puntoventa_tabs').tabs({ active: ".($tab+1)."});
});");
?>