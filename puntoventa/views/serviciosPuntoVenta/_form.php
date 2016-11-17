<?php
Yii::app()->clientScript->registerScript('servicios-admin', "
  $('#servicios-item-add').click(function(){
    $.ajax({
        type: 'POST',
        dataType : 'json',
        async: true,
        url: '" . Yii::app()->createUrl('/puntoventa/serviciosPuntoVenta/formItem') . "',
        data: {items: $('#nItem').val(), puntoventa: $model->IDPuntoDeVenta},
        beforeSend: function(){Loading.show();},
        success: function(data){
            if(data.result=='ok'){
                $('#servicios-items').append(data.response.form);
                $('#nItem').val(data.response.nItem);
                Loading.hide();
            }else{
                bootbox.alert(data.response);
                Loading.hide();
            }
        },
        error: function(data){
            bootbox.alert('Error, intente de nuevo');
            Loading.hide();
        }
    });

    return false;
  });");
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'enableClientValidation' => true,
    'htmlOptions' => array(
        'class' => 'form-horizontal',
        'role' => 'form',
        'id' => 'servicios-form'
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
            <div class="col-lg-6 control-label">
                <div>
                    <p class="note">Campos con <span class="required">*</span> son obligatorios.</p>
                </div>
            </div>
        </div>
        -->

        <?php echo CHtml::button('Agregar servicio', array('id' => 'servicios-item-add', 'class' => 'btn btn-primary btn-large')); ?>

        <?php echo CHtml::hiddenField('nItem', $nItem, array('id' => 'nItem')); ?>

        <div id="servicios-items">
            <?php $this->renderPartial('_formitem', array('model' => $model, 'nItem' => $nItem)); ?>
        </div>

        <div class="form-group">
            <div class="col-lg-offset-5 col-lg-7">
                <?php
                echo CHtml::ajaxSubmitButton('Adicionar', Yii::app()->createUrl('/puntoventa/serviciosPuntoVenta/crear'), array(
                    'beforeSend' => new CJavaScriptExpression("function(){Loading.show();}"),
                    'success' => new CJavaScriptExpression("function(data){
                obj = $.parseJSON(data);
                if(obj.result=='ok'){
                    $.fn.yiiGridView.update('servicios-grid');
                    $('#servicios-form-modal').modal('hide');
                }else if(obj.result=='error'){
                    bootbox.alert(obj.response);
                }else if(obj.result=='invalid'){
                    $('div[id^=\'ServiciosPuntoVenta_\']').html('');
                    $('div[id^=\'ServiciosPuntoVenta_\']').css('display','none');
                    var item = obj.response.item;
                    var errors = obj = $.parseJSON(obj.response.errors);
                    
                    $.each(errors,function(element,error){
                        $('#'+element+'_em_'+item).html(error);
                        $('#'+element+'_em_'+item).css('display','block');
                    });
                }
                Loading.hide();
        }"),
                    'error' => new CJavaScriptExpression("function(data){
                Loading.hide();
                $('#servicios-form-modal').modal('hide');
                bootbox.alert('Error, intente de nuevo');
        }")), array('id' => uniqid(), 'class' => 'btn btn-primary',));
                ?>
            </div>
        </div>

    </div>
</div>

<?php $this->endWidget(); ?>
