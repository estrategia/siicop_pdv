<?php
$form = $this->beginWidget('CActiveForm', array(
    'enableClientValidation' => true,
    'htmlOptions' => array(
        'class' => 'form-horizontal',
        'role' => 'form',
        'id' => 'tipo-servicio-form'
    ),
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

        <?php /* echo $form->errorSummary($model); */ ?>

        <?php if (!$model->isNewRecord): ?>
            <?php echo $form->hiddenField($model, 'IDTipoServicio'); ?>
        <?php endif; ?>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'IDCategoriaTipoServicio', array('class' => 'col-lg-3 control-label')); ?>
            <div class="col-lg-5">
                <?php echo $form->dropDownList($model, 'IDCategoriaTipoServicio', CategoriaTipoServicio::listData(), array('class' => 'form-control')); ?>
                <div class="help-block">
                    <?php echo $form->error($model, 'IDCategoriaTipoServicio'); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'NombreTipoServicio', array('class' => 'col-lg-3 control-label')); ?>
            <div class="col-lg-5">
                <?php echo $form->textField($model, 'NombreTipoServicio', array('class' => 'form-control', 'maxlength' => 100)); ?>
                <div class="help-block">
                    <?php echo $form->error($model, 'NombreTipoServicio'); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-lg-offset-3 col-lg-10">
                <?php
                echo CHtml::ajaxSubmitButton($model->isNewRecord ? 'Crear' : 'Actualizar', $model->isNewRecord ? Yii::app()->createUrl('/puntoventa/tipoServicio/crear') : Yii::app()->createUrl('/puntoventa/tipoServicio/actualizar'), array(
                    'beforeSend' => new CJavaScriptExpression("function(){Loading.show();}"),
                    'success' => new CJavaScriptExpression("function(data){
                obj = $.parseJSON(data);
                if(obj.result=='ok'){
                    $.fn.yiiGridView.update('tipo-servicio-grid');
                    $('#servicio-form-modal').modal('hide');
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
            $('#servicio-form-modal').modal('hide');
            bootbox.alert('Error, intente de nuevo');
        }")), array('id' => uniqid(), 'class' => 'btn btn-primary',));
                ?>
            </div>
        </div>

    </div>
</div>

<?php $this->endWidget(); ?>
