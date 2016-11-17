<?php
Yii::app()->clientScript->registerScript('activo-admin', "
  $('#activo-add-button').click(function(){
    $.ajax({
        type: 'POST',
        dataType : 'json',
        async: true,
        url: '" . Yii::app()->createUrl('/puntoventa/activo/crear') . "',
        data: {render: true},
        beforeSend: function(){Loading.show();},
        success: function(data){
            Loading.hide();
            if(data.result=='ok'){
                $('#activo-form-modal .modal-body').html(data.response.form);
                $('#activo-form-modal .modal-header h4').html('Creaci&oacute;n de Activo');
                $('#activo-form-modal').modal({backdrop: 'static', show: true});
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

<?php 
Yii::app()->clientScript->registerScript('activoEstadoActualizar', "
$(document).on('change', 'select[data-role=\"selectestadoactivo\"]', function() {
    var element = $(this);
    $.ajax({
        type: 'POST',
        dataType : 'json',
        url: '" . Yii::app()->createUrl('/puntoventa/activo/estado') . "',
        data: {activo: $(this).attr('data-id'), estado: $(this).val()},
        beforeSend: function(){ Loading.show();},
        success: function(data){
            if(data.result!='ok'){
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
Yii::app()->clientScript->registerScript('activoActualizar', "
$(document).on('click', 'button[data-role=\"btnactualizaractivo\"]', function() {
    var element = $(this);
    $.ajax({
        type: 'POST',
        dataType : 'json',
        url: '" . Yii::app()->createUrl('/puntoventa/activo/actualizar') . "',
        data: {render: true, activo: $(this).attr('data-id')},
        beforeSend: function(){ Loading.show();},
        success: function(data){
            if(data.result=='ok'){
                $('#activo-form-modal .modal-body').html(data.response.form);
                $('#activo-form-modal .modal-header h4').html('Actualizar datos de Activo');
                $('#activo-form-modal').modal({backdrop: 'static', show: true});
            }else
                bootbox.alert(data.response); 
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

<div class="modal" id="activo-form-modal">
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
$this->pageTitle = Yii::app()->name . ' - Puntos de Venta - Activos';

$this->breadcrumbs = array(
    'Inicio' => array('/'),
    'PDV - Activos',
);
?>

<div class="well" align="center">
    <?php echo CHtml::button('Crear Activo', array('id' => 'activo-add-button', 'class' => 'btn btn-primary btn-sm')); ?>
    <?php echo CHtml::link('Cargar Excel', $this->createUrl('/puntoventa/activo/cargar'), array('id' => 'activo-add-button', 'class' => 'btn btn-primary btn-sm')); ?>
</div>


<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'activos-grid',
    'beforeAjaxUpdate' => new CJavaScriptExpression("function() {Loading.show();}"),
    'afterAjaxUpdate' => new CJavaScriptExpression("function() {Loading.hide();}"),
    'ajaxUpdateError' => new CJavaScriptExpression("function(xhr, textStatus, errorThrown, errorMessage) {Loading.hide(); bootbox.alert(errorMessage);}"),
    'dataProvider' => $model->searchAdmin(),
    'ajaxUrl' => $this->createUrl('admin'),
    'filter' => $model,
    'columns' => array(
        'Codigo',
        'Referencia',
        'DescripcionActivo',
        'ObservacionActivo',
        array(
            'name' => 'IdActivoCategoria',
            'value' => '$data->categoria->NombreCategoria',
        ),
        //Yii::app()->controller->module->infoInfluencia
        array(
            'name' => 'Estado',
            //'value' => 'Yii::app()->getModule("puntoventa")->estadoActivos[$data->Estado]', 
            'filter' => Yii::app()->getModule("puntoventa")->listEstadosActivos,
            'type' => 'raw',
            /*'value' => 'CHtml::dropDownList(\'estadoactivo_\'.$data->IdActivo,
                $data->Estado,
                array(\'1\'=>\'Activo\', \'0\'=>\'Inactivo\'),
                array(\'onChange\'=>\'boletinActualizar(this.id,this.value);\')
             )',
            'htmlOptions' => array('width' => '50px', 'style' => "text-align:center;"),*/
            'value' => array($this, 'gridEstadoActivo'),
        ),
        array(
            'filter' => null,
            'type' => 'raw',
            'value' => array($this, 'gridBtnActualizar'),
        ),
    ),
));
?>
