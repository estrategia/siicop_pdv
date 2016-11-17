<?php
$idDepartamento = 'departamento_list_id_' . uniqid();
$idCiudad = 'ciudad_list_id_' . uniqid();
$idBarrio = 'barrio_list_id_' . uniqid();

$form = $this->beginWidget('CActiveForm', array(
    'enableClientValidation' => true,
    'htmlOptions' => array(
        'class' => 'form-horizontal',
        'role' => 'form',
        'id' => 'influencias-form-'
    ),
    'errorMessageCssClass' => 'has-error',
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'errorCssClass' => 'has-error',
        'successCssClass' => 'has-success',
        'inputContainer' => '.form-group',
        'validateOnChange' => true
    ),
    'enableAjaxValidation' => false,
        ));
?>

<div class="row">
    <div class="col-md-4">
        <label class="control-label text-primary" for="departamento_list_id">
            Departamento
        </label>
        <div class="input-group-sm">
            <?php
            echo CHtml::dropDownList($idDepartamento, '', $listData, array(
                'prompt' => 'Seleccione',
                'class' => 'form-control input-sm',
                'ajax' => array(
                    'type' => 'POST',
                    'beforeSend' => new CJavaScriptExpression("function(){Loading.show();}"),
                    'error' => new CJavaScriptExpression("function(data){Loading.hide(); bootbox.alert('Error, intente de nuevo');}"),
                    'complete' => new CJavaScriptExpression("function(){Loading.hide();}"),
                    'data' => array('departamento' => new CJavaScriptExpression("$(this).val()")),
                    'url' => CController::createUrl('/puntoventa/influenciaPuntoVenta/ciudades'),
                    'update' => "#$idCiudad",
            )));
            ?>

        </div>
    </div>
    <div class="col-md-4">
        <label class="control-label text-primary" for="ciudad_list_id">
            Ciudad
        </label>
        <div class="input-group-sm">
            <?php
            echo CHtml::dropDownList($idCiudad, '', array(), array(
                'prompt' => 'Seleccione',
                'class' => 'form-control input-sm',
                'ajax' => array(
                    'type' => 'POST',
                    'beforeSend' => new CJavaScriptExpression("function(){Loading.show();}"),
                    'error' => new CJavaScriptExpression("function(data){Loading.hide(); bootbox.alert('Error, intente de nuevo');}"),
                    'complete' => new CJavaScriptExpression("function(){Loading.hide();}"),
                    'data' => array('ciudad' => new CJavaScriptExpression("$(this).val()")),
                    'url' => CController::createUrl('/puntoventa/influenciaPuntoVenta/barrios'),
                    'update' => "#$idBarrio",
            )));
            ?>

        </div>
    </div>
    <div class="col-md-4">
        <label class="control-label text-primary" for="barrio_list_id">
            Barrio
        </label>
        <div class="input-group-sm">
            <?php
            echo CHtml::dropDownList($idBarrio, '', array(), array(
                'prompt' => 'Seleccione',
                'class' => 'form-control input-sm',
            ));
            ?>
        </div>
    </div>
</div>

<div class="space"></div>

<div class="row">
    <div class="col-md-12">
        <div id="barrio_list_id_em_" class="text-left text-danger" style="text-align: center; display: none;"></div>
    </div>
</div>

<div class="space"></div>

<div class="form-group">
    <div class="col-lg-offset-5 col-lg-7">
        <?php
        echo CHtml::ajaxSubmitButton('Crear', Yii::app()->createUrl('/puntoventa/influenciaPuntoVenta/crear'), array(
            'beforeSend' => new CJavaScriptExpression("function(){ $('#barrio_list_id_em_').html(''); $('#barrio_list_id_em_').css('display','none'); Loading.show();}"),
            'data' => array('barrio' => new CJavaScriptExpression("$('#$idBarrio').val()"), 'puntoventa' => $puntoventa),
            'success' => new CJavaScriptExpression("function(data){
                obj = $.parseJSON(data);
                if(obj.result=='ok'){
                    $.fn.yiiGridView.update('influencia-grid');
                    $('#influencia-form-modal').modal('hide');
                    Loading.hide();
                }else if(obj.result=='error'){
                    Loading.hide();
                    $('#barrio_list_id_em_').html(obj.response);
                    $('#barrio_list_id_em_').css('display','block');
                }
            }"),
            'error' => new CJavaScriptExpression("function(data){
                Loading.hide();
            $('#cedi-form-modal').modal('hide');
            bootbox.alert('Error, intente de nuevo');
        }")), array('id' => uniqid(), 'class' => 'btn btn-primary',));
        ?>
    </div>
</div>

<?php $this->endWidget(); ?>