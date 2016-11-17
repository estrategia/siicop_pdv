<?php
$form = $this->beginWidget('CActiveForm', array(
    'enableClientValidation' => true,
    'enableAjaxValidation' => false,
    'htmlOptions' => array(
        'class' => 'form-horizontal',
        'role' => 'form',
        'id' => 'imagen-punto-venta-form',
        'enctype' => 'multipart/form-data'
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
            <?php echo $form->hiddenField($model, 'IDImagenPuntoDeVenta'); ?>
        <?php endif; ?>

        <?php echo $form->hiddenField($model, 'IDPuntoDeVenta'); ?>


        <div class="form-group">
            <?php echo $form->labelEx($model, 'NombreImagen', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php echo $form->textField($model, 'NombreImagen', array('class' => 'form-control input-sm', 'maxlength' => 45)); ?>
                <?php echo $form->error($model, 'NombreImagen', array('class' => 'text-left text-danger')); ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'TituloImagen', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php echo $form->textField($model, 'TituloImagen', array('class' => 'form-control input-sm', 'maxlength' => 45)); ?>
                <?php echo $form->error($model, 'TituloImagen', array('class' => 'text-left text-danger')); ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'RutaImagen', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php echo $form->fileField($model, 'RutaImagen'); ?>
                <?php echo $form->error($model, 'RutaImagen', array('class' => 'text-left text-danger')); ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'DescripcionImagen', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php echo $form->textField($model, 'DescripcionImagen', array('class' => 'form-control input-sm', 'maxlength' => 100)); ?>
                <?php echo $form->error($model, 'DescripcionImagen', array('class' => 'text-left text-danger')); ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'TipoImagen', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php echo $form->textField($model, 'TipoImagen', array('class' => 'form-control input-sm')); ?>
                <?php echo $form->error($model, 'TipoImagen', array('class' => 'text-left text-danger')); ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'EstadoImagen', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php echo $form->dropDownList($model, 'EstadoImagen', array('1' => 'Activo', '0' => 'Inactivo'), array('class' => 'form-control input-sm')); ?>
                <?php echo $form->error($model, 'EstadoImagen', array('class' => 'text-left text-danger')); ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-lg-offset-5 col-lg-7">
                <?php
                echo CHtml::ajaxButton($model->isNewRecord ? 'Agregar' : 'Actualizar', $model->isNewRecord ? Yii::app()->createUrl('/puntoventa/imagenPuntoVenta/crear') : Yii::app()->createUrl('/puntoventa/imagenPuntoVenta/actualizar'), array(
                    'beforeSend' => new CJavaScriptExpression("function(){Loading.show();}"),
                    'type' => 'POST',
                    'dataType' => 'json',
                    'processData' => false,
                    'contentType' => false,
                    'data' => 'js: new FormData( document.getElementById(\'imagen-punto-venta-form\') )',
                    'success' => new CJavaScriptExpression("function(data){
                 if(data.result=='ok'){
                    $.fn.yiiGridView.update('imagen-punto-venta-grid');
                    $('#imagenes-form-modal').modal('hide');
                }else if(data.result=='error'){
                    bootbox.alert(obj.response);
                }else{
                    $.each(data,function(element,error){
                        $('#'+element+'_em_').html(error);
                        $('#'+element+'_em_').css('display','block');
                    });
                }
                Loading.hide();
        }"),
                    'error' => new CJavaScriptExpression("function(data){
                Loading.hide();
            $('#imagenes-form-modal').modal('hide');
            bootbox.alert('Error, intente de nuevo');
        }")), array('id' => uniqid(), 'class' => 'btn btn-primary',));
                ?>
            </div>
        </div>

    </div>
</div>


<?php $this->endWidget(); ?>