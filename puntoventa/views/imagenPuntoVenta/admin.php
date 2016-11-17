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
    Yii::app()->clientScript->registerScript('imagenes-admin', "
    $('#imagenes-add-button').click(function(){
      $.ajax({
          type: 'POST',
          dataType : 'json',
          async: true,
          url: '" . Yii::app()->createUrl('/puntoventa/imagenPuntoVenta/crear') . "',
          data: {render: true, puntoventa: $model->IDPuntoDeVenta},
          beforeSend: function(){Loading.show();},
          success: function(data){
              Loading.hide();
              if(data.result=='ok'){
                  $('#imagenes-form-modal .modal-body').html(data.response.form);
                  $('#imagenes-form-modal .modal-header h4').html('Agregar imagen');
                  $('#imagenes-form-modal').modal({backdrop: 'static', show: true});
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

    <div class="modal" id="imagenes-form-modal">
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

    <?php echo CHtml::button('Agregar imagen', array('id' => 'imagenes-add-button', 'class' => 'btn btn-primary btn-large')); ?>
<?php endif; ?>

<?php
Yii::app()->clientScript->registerScript('prettyphoto', "$('" . Yii::app()->controller->module->elementPretty . "').prettyPhoto(" . CJSON::encode(Yii::app()->controller->module->optPretty) . ");", CClientScript::POS_END);
?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'imagen-punto-venta-grid',
    'beforeAjaxUpdate' => new CJavaScriptExpression("function() {Loading.show();}"),
    'afterAjaxUpdate' => new CJavaScriptExpression("function() { $('" . Yii::app()->controller->module->elementPretty . "').prettyPhoto(" . CJSON::encode(Yii::app()->controller->module->optPretty) . "); Loading.hide();}"),
    'ajaxUpdateError' => new CJavaScriptExpression("function(xhr, textStatus, errorThrown, errorMessage) {Loading.hide(); bootbox.alert(errorMessage);}"),
    'ajaxUrl' => ($consulta ? $this->createUrl('/puntoventa/puntoVenta/ver', array('id'=>$model->IDPuntoDeVenta)) : $this->createUrl('/puntoventa/puntoVenta/actualizar', array('id'=>$model->IDPuntoDeVenta))),
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        //'IDImagenPuntoDeVenta',
        //'IDPuntoDeVenta',
        'NombreImagen',
        'TituloImagen',
        'RutaImagen',
        'DescripcionImagen',
        'TipoImagen',
        //'EstadoImagen',
        array(
            'filter' => array('1' => 'Activa', '0' => 'Inactiva'),
            'name' => 'EstadoImagen',
            'value' => ' $data->EstadoImagen==1 ? "Activa" : "Inactiva"'
        ),
        array(
            'type' => 'raw',
            'header' => 'Im&aacute;genes',
            'filter' => false,
            'value' => ' \'<div class="gallery"><a href="\' . Yii::app()->getBaseUrl() . $data->RutaImagen . \'" rel="prettyPhoto" title="\' . $data->DescripcionImagen . \'"><img src="\' . Yii::app()->controller->module->assetsUrl . Yii::app()->controller->module->iconImg . \'" alt="\' . $data->TituloImagen . \'" title="Ver imagen"/></a></div>\' '
        ),
        array(
            'class' => 'CButtonColumn',
            'template' => ($consulta ? false : '{update} {delete}'),
            'buttons' => array(
                'delete' => array(
                    'label' => 'Eliminar',
                    'url' => 'Yii::app()->createUrl("/puntoventa/imagenPuntoVenta/eliminar", array("id"=>$data->IDImagenPuntoDeVenta))',
                ),
                'update' => array(
                    'label' => 'Editar',
                    'click' => "function(){
                        $.ajax({
                            type: 'POST',
                            dataType : 'json',
                            async: true,
                            url: '" . Yii::app()->createUrl('/puntoventa/imagenPuntoVenta/actualizar') . "',
                            data: {render: true, imagen: $(this).attr('href')},
                            beforeSend: function(){Loading.show();},
                            success: function(data){
                                Loading.hide();
                                if(data.result=='ok'){
                                    $('#imagenes-form-modal .modal-body').html(data.response.form);
                                    $('#imagenes-form-modal .modal-header h4').html('Actualizar imagen');
                                    $('#imagenes-form-modal').modal({backdrop: 'static', show: true});
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
                    'url' => '$data->IDImagenPuntoDeVenta',
                ),
            )
        ),
    ),
));
?>

<div class="row">
    <div class="footer">
        <ul class="pager">
            <li class="previous" id="id_imagenes_anterior"><a href="#" class="anterior">← Anterior</a></li>
            <li class="next" id="id_imagenes_siguiente"><a href="#" class="siguiente">Siguiente →</a></li>
        </ul>
    </div>
</div>

<?php
Yii::app()->clientScript->registerScript('imagenes-tab', "
$(document).on('click', '#id_imagenes_anterior', function(e) {
    $('#puntoventa_tabs').tabs({ active: " . ($tab - 1) . "});
});
$(document).on('click', '#id_imagenes_siguiente', function(e) {
    $('#puntoventa_tabs').tabs({ active: " . ($tab + 1) . "});
});");
?>