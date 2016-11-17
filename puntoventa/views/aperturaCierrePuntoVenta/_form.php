<?php
$form = $this->beginWidget('CActiveForm', array(
    'enableClientValidation' => true,
    'enableAjaxValidation' => false,
    'htmlOptions' => array(
        'class' => 'form-horizontal',
        'role' => 'form',
        'id' => 'aperturaCierrePV-form'
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
            <?php echo $form->hiddenField($model, 'IDAperturaCierrePuntoDeVenta'); ?>
        <?php endif; ?>

        <?php echo $form->hiddenField($model, 'IDPuntoDeVenta'); ?>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'FechaAperturaCierre', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $model,
                    'attribute' => 'FechaAperturaCierre',
                    'language' => 'es',
                    'options' => array(
                        'showAnim' => 'slide',
                        'dateFormat' => 'yy-mm-dd',
                    ),
                    'htmlOptions' => array(
                        'class' => 'form-control input-sm',
                        'size' => '10',
                        'maxlength' => '10',
                        'placeholder' => 'yyyy-mm-dd'
                    ),
                ));
                ?>
                <span class="add-on"><icon class="icon-calendar"></icon></span>
                <?php echo $form->error($model, 'FechaAperturaCierre', array('class' => 'text-left text-danger')); ?>
            </div>
        </div>



        <div class="form-group">
            <?php echo $form->labelEx($model, 'IDTipoAperturaCierre', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php
                echo $form->DropDownList($model, 'IDTipoAperturaCierre', $listTipos, array(
                    'prompt' => 'Seleccione',
                    'class' => 'form-control input-sm',
                ));
                ?>
                <?php echo $form->error($model, 'IDTipoAperturaCierre', array('class' => 'text-left text-danger')); ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'ObservacionesAperturaCierre', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php echo $form->textArea($model, 'ObservacionesAperturaCierre', array('class' => 'form-control input-sm', 'rows' => 5, 'maxlength' => 255)); ?>
                <?php echo $form->error($model, 'ObservacionesAperturaCierre', array('class' => 'text-left text-danger')); ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-lg-offset-5 col-lg-7">
                <?php
                echo CHtml::ajaxSubmitButton($model->isNewRecord ? 'Crear' : 'Actualizar', $model->isNewRecord ? Yii::app()->createUrl('/puntoventa/aperturaCierrePuntoVenta/crear') : Yii::app()->createUrl('/puntoventa/aperturaCierrePuntoVenta/actualizar'), array(
                    'beforeSend' => new CJavaScriptExpression("function(){Loading.show(); $('#aperturaCierrePV-form input[type=submit]').attr('disabled', 'disabled');}"),
                    'success' => new CJavaScriptExpression("function(data){
                            obj = $.parseJSON(data);
                            if(obj.result=='ok'){
                                $.fn.yiiGridView.update('apertura-cierre-punto-venta-grid');
                                $('#historial-form-modal').modal('hide');
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
                    'complete' => new CJavaScriptExpression("function(){ $('#aperturaCierrePV-form input[type=submit]').removeAttr('disabled');}"),
                    'error' => new CJavaScriptExpression("function(data){
                            Loading.hide();
                        $('#historial-form-modal').modal('hide');
                        bootbox.alert('Error, intente de nuevo');
                    }")), array('id' => uniqid(), 'class' => 'btn btn-primary',));
                ?>
            </div>
        </div>

    </div>
</div>


<?php $this->endWidget(); ?>
