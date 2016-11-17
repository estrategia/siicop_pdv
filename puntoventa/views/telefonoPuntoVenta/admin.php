<?php if (!$consulta): ?>
    <?php
    Yii::app()->clientScript->registerScript('telefono-admin', "
    $('#telefono-add-button').click(function(){
      $.ajax({
          type: 'POST',
          dataType : 'json',
          async: true,
          url: '" . Yii::app()->createUrl('/puntoventa/telefonoPuntoVenta/crear') . "',
          data: {render: true, puntoventa: $model->IDPuntoDeVenta},
          beforeSend: function(){Loading.show();},
          success: function(data){
              Loading.hide();
              if(data.result=='ok'){
                  $('#telefono-form-modal .modal-body').html(data.response.form);
                  $('#telefono-form-modal .modal-header h4').html('Agregar tel&eacute;fono');
                  $('#telefono-form-modal').modal({backdrop: 'static', show: true});
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
    <div class="modal" id="telefono-form-modal">
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

    <?php echo CHtml::button('Agregar tel&eacute;fono', array('encode' => false, 'id' => 'telefono-add-button', 'class' => 'btn btn-primary btn-large')); ?>
<?php endif; ?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'telefonos-grid',
    'beforeAjaxUpdate' => new CJavaScriptExpression("function() {Loading.show();}"),
    'afterAjaxUpdate' => new CJavaScriptExpression("function() {Loading.hide();}"),
    'ajaxUpdateError' => new CJavaScriptExpression("function(xhr, textStatus, errorThrown, errorMessage) {Loading.hide(); bootbox.alert(errorMessage);}"),
    //'ajaxUrl' => $this->createUrl('/puntoventa/telefonoPuntoVenta/admin', array('pv'=>$model->IDPuntoDeVenta)),
    'ajaxUrl' => ($consulta ? $this->createUrl('/puntoventa/puntoVenta/ver', array('id'=>$model->IDPuntoDeVenta)) : $this->createUrl('/puntoventa/puntoVenta/actualizar', array('id'=>$model->IDPuntoDeVenta))),
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        //'IDTelefonoPuntoDeVenta',
        //'IDPuntoDeVenta',
        'IndicativoTelefono',
        'NumeroTelefono',
        array(
            'class' => 'CButtonColumn',
            'template' => ($consulta ? false : '{update} {delete}'),
            'buttons' =>
            array(
                'delete' => array(
                    'label' => 'Eliminar',
                    'url' => 'Yii::app()->createUrl("/puntoventa/telefonoPuntoVenta/eliminar", array("id"=>$data->IDTelefonoPuntoDeVenta))',
                ),
                'update' => array(
                    'label' => 'Editar',
                    'click' => "function(){
                        $.ajax({
                            type: 'POST',
                            dataType : 'json',
                            async: true,
                            url: '" . Yii::app()->createUrl('/puntoventa/telefonoPuntoVenta/actualizar') . "',
                            data: {render: true, telefono: $(this).attr('href')},
                            beforeSend: function(){Loading.show();},
                            success: function(data){
                                Loading.hide();
                                if(data.result=='ok'){
                                    $('#telefono-form-modal .modal-body').html(data.response.form);
                                    $('#telefono-form-modal .modal-header h4').html('Actualizar tel&eacute;fono');
                                    $('#telefono-form-modal').modal({backdrop: 'static', show: true});
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
                    'url' => '$data->IDTelefonoPuntoDeVenta',
                ),
            )
        ),
    ),
));
?>
