<?php

Yii::app()->clientScript->registerScript('search', "
$('#search-form').submit(function(){
    $.fn.yiiGridView.update('registro-actividad-grid', {
        data: $(this).serialize()
    });
    return false;
});
");
?>

<div class="modal" id="registro-form-modal">
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

$this->pageTitle = Yii::app()->name . ' - Puntos de Venta';

$this->breadcrumbs = array(
    'Inicio' => array('/puntoventa'),
    'Actividades'
);

?>

<?php

$this->renderPartial('_search', array(
    'model' => $model,
));
?>

<?php

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'registro-actividad-grid',
    'beforeAjaxUpdate' => new CJavaScriptExpression("function() {Loading.show();}"),
    'afterAjaxUpdate' => new CJavaScriptExpression("function() { Loading.hide();}"),
    'ajaxUpdateError' => new CJavaScriptExpression("function() {Loading.hide(); bootbox.alert('Error, intente de nuevo');}"),
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        //'IDRegistro',
        'CedulaFuncionario',
        'Tabla',
        'Accion',
        'FechaRegistro',
        array(
            'class' => 'CButtonColumn',
            'template' => '{view}',
            'buttons' => array(
                'view' => array(
                    'label' => 'Ver cambios',
                    'click' => "function(){
                        $.ajax({
                            type: 'POST',
                            dataType : 'json',
                            async: true,
                            url: '" . Yii::app()->createUrl('/puntoventa/registroActividad/ver') . "',
                            data: {registro: $(this).attr('href')},
                            beforeSend: function(){Loading.show();},
                            success: function(data){
                                Loading.hide();
                                if(data.result=='ok'){
                                    $('#registro-form-modal .modal-body').html(data.response.form);
                                    $('#registro-form-modal').modal({backdrop: 'static', show: true});
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
                    'url' => '$data->IDRegistro',
                ),
            )
        ),
    ),
));
?>
