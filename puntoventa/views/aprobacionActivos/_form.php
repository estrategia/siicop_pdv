<?php
$form = $this->beginWidget('CActiveForm', array(
    'enableClientValidation' => true,
    'enableAjaxValidation' => false,
    'htmlOptions' => array(
        'class' => 'form-inline',
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
        <?php echo $form->hiddenField($model, 'IdActivoPuntoVenta'); ?>
        <?php echo $form->hiddenField($model, 'Estado'); ?>

        <div class='row'>
            <div class='col-md-12'>
                <?php echo $form->labelEx($model, 'ObservacionAprobador', array('class' => 'col-lg-12 control-label text-primary')); ?>
                <div class='input-group-lg'>
                    <?php echo $form->textArea($model, 'ObservacionAprobador', array('class' => 'form-control', 'rows' => 10)); ?>
                </div>
                <?php echo $form->error($model, 'ObservacionAprobador', array('class' => 'text-left text-danger')); ?>
            </div>
        </div>
        <br/>
        <div class="form-group">
            <div class="">
                <?php
                echo CHtml::ajaxSubmitButton('Actualizar', Yii::app()->createUrl('/puntoventa/aprobacionActivos/estado'), array(
                    'type' => 'POST',
                    'beforeSend' => new CJavaScriptExpression("function(){ $('#activos-form input[type=submit]').attr('disabled', 'disabled');Loading.show();}"),
                    'success' => new CJavaScriptExpression("function(data){
                        obj = $.parseJSON(data);
                        if(obj.result=='ok'){
                            
                            $('#estado-form-modal').modal('hide');
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
                        $('#estado-form-modal').modal('hide');
                        bootbox.alert('Error: ' + errorThrown);
                    }")), array('id' => uniqid(), 'class' => 'btn btn-primary',));
                ?>
            </div>
        </div>

    </div>
</div>

<?php $this->endWidget(); ?>

