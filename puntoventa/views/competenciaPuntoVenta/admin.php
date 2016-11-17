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
Yii::app()->clientScript->registerScript('competencia-admin', "
  $('#competencia-add-button').click(function(){
    $.ajax({
        type: 'POST',
        dataType : 'json',
        async: true,
        url: '" . Yii::app()->createUrl('/puntoventa/competenciaPuntoVenta/crear') . "',
        data: {render: true, puntoventa: $model->puntoventa_search},
        beforeSend: function(){Loading.show();},
        success: function(data){
            if(data.result=='ok'){
                $('#competencia-form-modal .modal-body').html(data.response.form);
                $('#competencia-form-modal .modal-header h4').html('Agregar competencia');
                $('#competencia-form-modal').modal({backdrop: 'static', show: true});
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

<div class="modal" id="competencia-form-modal">
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

<?php echo CHtml::button('Agregar Competencia', array('id' => 'competencia-add-button', 'class' => 'btn btn-primary btn-large')); ?>
<?php endif; ?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'competencia-grid',
    'beforeAjaxUpdate' => new CJavaScriptExpression("function() {Loading.show();}"),
    'afterAjaxUpdate' => new CJavaScriptExpression("function() {Loading.hide();}"),
    'ajaxUpdateError' => new CJavaScriptExpression("function(xhr, textStatus, errorThrown, errorMessage) {Loading.hide(); bootbox.alert(errorMessage);}"),
    'ajaxUrl' => ($consulta ? $this->createUrl('/puntoventa/puntoVenta/ver', array('id'=>$model->puntoventa_search)) : $this->createUrl('/puntoventa/puntoVenta/actualizar', array('id'=>$model->puntoventa_search))),
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'NombreCompetencia',
        array(
            'class' => 'CButtonColumn',
            'template' => ($consulta ? false : '{delete}'),
            'buttons' => array(
                'delete' => array(
                    'label' => 'Eliminar',
                    'url' => 'Yii::app()->createUrl("/puntoventa/competenciaPuntoVenta/eliminar", array("id"=>$data->IDCompetencia, "idpv"=>'.$model->puntoventa_search.'))',
                )
            )
        ),
    ),
));
?>

<div class="row">
    <div class="footer">
        <ul class="pager">
            <li class="previous" id="id_competencia_anterior"><a href="#" class="anterior">← Anterior</a></li>
            <li class="next" id="id_competencia_siguiente"><a href="#" class="siguiente">Siguiente →</a></li>
        </ul>
    </div>
</div>

<?php
Yii::app()->clientScript->registerScript('competencia-tab', "
$(document).on('click', '#id_competencia_anterior', function(e) {
    $('#puntoventa_tabs').tabs({ active: ".($tab-1)."});
});
$(document).on('click', '#id_competencia_siguiente', function(e) {
    $('#puntoventa_tabs').tabs({ active: ".($tab+1)."});
});");
?>



