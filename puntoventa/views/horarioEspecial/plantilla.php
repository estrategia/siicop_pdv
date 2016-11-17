<?php
/* @var $this PlantillaCorreoController */
/* @var $model PlantillaCorreo */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        //'enableClientValidation' => true,
        'htmlOptions' => array(
            'class' => 'form-horizontal',
            //'role' => 'form',
            'id' => 'plantilla-form'
        ),
        /*'clientOptions' => array(
            'validateOnSubmit' => true,
            'errorCssClass' => 'has-error',
            'successCssClass' => 'has-success',
            'inputContainer' => '.form-group',
            'validateOnChange' => true
        ),*/
        'enableAjaxValidation' => false,
    ));
    ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="col-md-12">
            <?php //echo $form->labelEx($model, 'plantilla', array('class' => 'control-label text-primary')); ?>
            <?php echo $form->textArea($model, 'plantilla', array('class' => 'form-control', 'rows' => 6, 'cols' => 50)); ?>
            <?php echo $form->error($model, 'plantilla', array('class' => 'text-left text-danger')); ?>
        </div>
    </div>
    <div style="height: 10px;"></div>
    <div class="row buttons">
        <?php echo CHtml::submitButton('Guardar', array('class'=>'btn btn-primary')); ?>
    </div>

    

    <?php $this->endWidget(); ?>

</div><!-- form -->

<?php
Yii::app()->clientScript->registerScript('contenido-editor', "
    CKEDITOR.replace('PlantillaCorreo_plantilla', {
            skin: 'office2013',
            removePlugins: 'save,font,forms,flash,horizontalrule,iframe,smiley,about,image,link,templates',
            removeButtons: 'Anchor,Underline,Strike,Subscript,Superscript,Source',
            
        });
    ", CClientScript::POS_END);
?>