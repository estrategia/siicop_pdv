<?php
Yii::app()->clientScript->registerScript('categoria-admin', "
  $('#categoria-add-button').click(function(){
    $.ajax({
        type: 'POST',
        dataType : 'json',
        async: true,
        url: '" . Yii::app()->createUrl('/puntoventa/categoriaTipoServicio/crear') . "',
        data: {render: true},
        beforeSend: function(){Loading.show();},
        success: function(data){
            Loading.hide();
            if(data.result=='ok'){
                $('#categoria-form-modal .modal-body').html(data.response.form);
                $('#categoria-form-modal .modal-header h4').html('Creaci&oacute;n de categor&iacute;a');
                $('#categoria-form-modal').modal({backdrop: 'static', show: true});
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

<div class="modal" id="categoria-form-modal">
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
$this->pageTitle = Yii::app()->name . ' - Puntos de Venta - Categor&iacute;as';

$this->breadcrumbs = array(
    'Inicio' => array('/puntoventa'),
    'Admin' => array('/puntoventa/admin'),
    'Categor&iacute;as'
);
?>

<div class="well" align="center">
<?php echo CHtml::button('Crear Categor&iacute;a', array('id' => 'categoria-add-button', 'class' => 'btn btn-primary btn-sm', 'encode'=>false)); ?>
</div>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'categoria-tipo-servicio-grid',
    'beforeAjaxUpdate' => new CJavaScriptExpression("function() {Loading.show();}"),
    'afterAjaxUpdate' => new CJavaScriptExpression("function() {Loading.hide();}"),
    'ajaxUpdateError' => new CJavaScriptExpression("function() {Loading.hide(); bootbox.alert('Error, intente de nuevo');}"),
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        //'IDCategoriaTipoServicio',
        'CategoriaTipoDeServicio',
        array(
            'class' => 'CButtonColumn',
            'template' => '{update} {delete}',
            'buttons' => array(
                'delete' => array(
                    'label' => 'Eliminar',
                    'url' => '$data->IDCategoriaTipoServicio',
                    'options' => array(
                        'confirm' => "¿Esta seguro de eliminar este registro?",
                        'ajax' => array(
                            'type' => 'POST',
                            'dataType' => 'json',
                            'async' => true,
                            'url' => Yii::app()->createUrl('/puntoventa/categoriaTipoServicio/eliminar'),
                            'data' => array('categoria' => "js: $(this).attr('href')"),
                            'beforeSend' => new CJavaScriptExpression("function(){Loading.show();}"),
                            'success' => new CJavaScriptExpression("function(data){
                                Loading.hide();
                                if(data.result=='ok'){
                                    $.fn.yiiGridView.update('categoria-tipo-servicio-grid');
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
                            url: '" . Yii::app()->createUrl('/puntoventa/categoriaTipoServicio/actualizar') . "',
                            data: {render: true, categoria: $(this).attr('href')},
                            beforeSend: function(){Loading.show();},
                            success: function(data){
                                Loading.hide();
                                if(data.result=='ok'){
                                    $('#categoria-form-modal .modal-body').html(data.response.form);
                                    $('#categoria-form-modal .modal-header h4').html('Actualizar datos de categor&iacute;a');
                                    $('#categoria-form-modal').modal({backdrop: 'static', show: true});
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
                    'url' => '$data->IDCategoriaTipoServicio',
                ),
            )
        ),
    ),
));
?>
