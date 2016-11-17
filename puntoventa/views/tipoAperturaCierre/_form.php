<?php
$form = $this->beginWidget('CActiveForm', array(
    'enableClientValidation' => true,
    'htmlOptions' => array(
        'class' => 'form-horizontal',
        'role' => 'form',
        'id' => 'taperturacierre-form'
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

<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Campos con <span class="required">*</span> son obligatorios.</h3>
    </div>
    <div class="panel-body">
        <!--
        <div class="form-group">
            <div class="col-lg-5 control-label">
                <div>
                    <p class="note">Campos con <span class="required">*</span> son obligatorios.</p>
                </div>
            </div>
        </div>
        -->

        <?php if (!$model->isNewRecord): ?>
            <?php echo $form->hiddenField($model, 'IDTipoAperturaCierre'); ?>
        <?php endif; ?>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'NombreTipoAperturaCierre', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php echo $form->textField($model, 'NombreTipoAperturaCierre', array('class' => 'form-control input-sm', 'maxlength' => 50)); ?>
                <?php echo $form->error($model, 'NombreTipoAperturaCierre', array('class' => 'text-left text-danger')); ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-lg-offset-5 col-lg-7">
                <?php
                echo CHtml::ajaxSubmitButton($model->isNewRecord ? 'Crear' : 'Actualizar', $model->isNewRecord ? Yii::app()->createUrl('/puntoventa/tipoAperturaCierre/crear') : Yii::app()->createUrl('/puntoventa/tipoAperturaCierre/actualizar'), array(
                    'beforeSend' => new CJavaScriptExpression("function(){Loading.show();}"),
                    'success' => new CJavaScriptExpression("function(data){
                obj = $.parseJSON(data);
                if(obj.result=='ok'){
                    $.fn.yiiGridView.update('taperturacierre-grid');
                    $('#taperturacierre-form-modal').modal('hide');
                }else if(obj.result=='error'){
                    bootbox.alert(obj.response);
                }else{
                    $.each(obj,function(element,error){
                        $('#'+element+'_em_').html(error);
                        $('#'+element+'_em_').css('display','block');
                    });
                }
                Loading.hide();
        }"),
                    'error' => new CJavaScriptExpression("function(data){
                Loading.hide();
            $('#taperturacierre-form-modal').modal('hide');
            bootbox.alert('Error, intente de nuevo');
        }")), array('id' => uniqid(), 'class' => 'btn btn-primary',));
                ?>
            </div>
        </div>

    </div>
</div>

<?php $this->endWidget(); ?>
