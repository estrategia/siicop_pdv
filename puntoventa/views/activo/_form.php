<?php
$form = $this->beginWidget('CActiveForm', array(
    'enableClientValidation' => true,
    'enableAjaxValidation' => false,
    'htmlOptions' => array(
        'class' => 'form-horizontal',
        'role' => 'form',
        'id' => 'activos-form'
    ),
    'errorMessageCssClass' => 'has-error',
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'errorCssClass' => 'has-error',
        'successCssClass' => 'has-success',
        'inputContainer' => '.form-group',
        'validateOnChange' => true
    ))
);
?>

<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Campos con <span class="required">*</span> son obligatorios.</h3>
    </div>
    <div class="panel-body">
        <?php if (!$model->isNewRecord): ?>
            <?php echo $form->hiddenField($model, 'IdActivo'); ?>
        <?php endif; ?>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'IdActivoCategoria', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php echo $form->dropDownList($model, 'IdActivoCategoria', ActivoCategoria::listData(), array('class' => 'form-control input-sm')); ?>
                <?php echo $form->error($model, 'IdActivoCategoria', array('class' => 'text-left text-danger')); ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'DescripcionActivo', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php echo $form->textArea($model, 'DescripcionActivo', array('class' => 'form-control input-sm', 'rows'=>6, 'maxlength' => 200)); ?>
                <?php echo $form->error($model, 'DescripcionActivo', array('class' => 'text-left text-danger')); ?>
            </div>
        </div>
        
        <div class="form-group">
            <?php echo $form->labelEx($model, 'ObservacionActivo', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php echo $form->textArea($model, 'ObservacionActivo', array('class' => 'form-control input-sm', 'rows'=>2, 'maxlength' => 100)); ?>
                <?php echo $form->error($model, 'ObservacionActivo', array('class' => 'text-left text-danger')); ?>
            </div>
        </div>
        
        <div class="form-group">
            <?php echo $form->labelEx($model, 'Codigo', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php echo $form->textField($model, 'Codigo', array('class' => 'form-control input-sm', 'maxlength' => 7)); ?>
                <?php echo $form->error($model, 'Codigo', array('class' => 'text-left text-danger')); ?>

            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'Referencia', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php echo $form->textField($model, 'Referencia', array('class' => 'form-control input-sm', 'maxlength' => 10)); ?>
                <?php echo $form->error($model, 'Referencia', array('class' => 'text-left text-danger')); ?>

            </div>
        </div>
        
        <?php if (!$model->isNewRecord): ?>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'Estado', array('class' => 'col-lg-4 control-label text-primary')); ?>
                <div class="col-lg-7">
                    <?php echo $form->dropDownList($model, 'Estado', Yii::app()->getModule("puntoventa")->listEstadosActivos, array('class' => 'form-control input-sm')); ?>
                    <?php echo $form->error($model, 'Estado', array('class' => 'text-left text-danger')); ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <div class="col-lg-offset-5 col-lg-7">
                <?php
                echo CHtml::ajaxSubmitButton($model->isNewRecord ? 'Crear' : 'Actualizar', $model->isNewRecord ? Yii::app()->createUrl('/puntoventa/activo/crear') : Yii::app()->createUrl('/puntoventa/activo/actualizar'), array(
                    'beforeSend' => new CJavaScriptExpression("function(){ $('#activos-form input[type=submit]').attr('disabled', 'disabled');Loading.show();}"),
                    'success' => new CJavaScriptExpression("function(data){
                        obj = $.parseJSON(data);
                        if(obj.result=='ok'){
                            $.fn.yiiGridView.update('activos-grid');
                            $('#activo-form-modal').modal('hide');
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
                    'complete' => new CJavaScriptExpression("function(){ $('#activos-form input[type=submit]').removeAttr('disabled');}"),
                    'error' => new CJavaScriptExpression("function(jqXHR, textStatus, errorThrown){
                        Loading.hide();
                        $('#activo-form-modal').modal('hide');
                        bootbox.alert('Error: ' + errorThrown);
                    }")), array('id' => uniqid(), 'class' => 'btn btn-primary',));
                ?>
            </div>
        </div>

    </div>
</div>

<?php $this->endWidget(); ?>
