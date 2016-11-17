<div class="form-group">
    <div class="col-lg-6">
        <?php
        $idList = 'listhorario_' . uniqid();
        echo CHtml::dropDownList('horario-selector', $model === null ? '' : $model->IDHorarioPuntoDeVenta, HorarioPuntoVenta::model()->listData(), array(
            'id' => $idList,
            'class' => 'form-control',
            'prompt' => 'Seleccionar horario',
            'onChange' => new CJavaScriptExpression("$('#horario-modal-selection').html($('#$idList  option:selected').text());"),
        ));
        ?>
    </div>
</div>

<div class="clear"></div>
<br/>

<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Horario seleccionado.</h3>
    </div>
    <div class="panel-body">
        <div id="horario-modal-selection" style="text-align: center;">
            <?php echo ($model == null ? "Seleccionar horario" : "$model->HorarioInicio - $model->HorarioFin") ?>
        </div>

    </div>
</div>

<div class="clear"></div>

<div class="form-group">
    <div class="col-lg-offset-5 col-lg-7">
        <?php
        echo CHtml::ajaxButton('Guardar', Yii::app()->createUrl('/puntoventa/puntoVenta/horario'), array(
            'type' => 'POST',
            'dataType' => 'json',
            'data' => array('id' => 'js: $("select[name=\'horario-selector\']").val()', 'horario' => $horario, 'puntoventa' => $puntoventa),
            'beforeSend' => new CJavaScriptExpression("function(){Loading.show();}"),
            'success' => new CJavaScriptExpression("function(data){
            if(data.result=='ok'){
                $('#horarios-form-modal').modal('hide');
                $('#puntoventa_tab_" . Yii::app()->controller->module->infoHorarios . "').html(data.response.tab);
                Loading.hide();
            }else{
                Loading.hide();
                bootbox.alert(data.response);
            }
        }"),
            'error' => new CJavaScriptExpression("function(data){
            $('#horarios-form-modal').modal('hide');
            Loading.hide();
            bootbox.alert('Error, intente de nuevo');
        }")), array('id' => 'buttonhorario_' . uniqid(), 'class' => 'btn btn-primary',));
        ?>
    </div>
</div>
<div class="clear"></div>