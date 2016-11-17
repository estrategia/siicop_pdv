<?php foreach (Yii::app()->user->getFlashes() as $key => $message): ?>
    <div class="alert alert-dismissable alert-<?php echo $key ?>">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <?php echo $message ?>
    </div>
<?php endforeach; ?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'enableClientValidation' => true,
    //'enableAjaxValidation' => false,
    'htmlOptions' => array(
        'class' => 'influencia-upload-form', //'form-inline'
        'enctype' => 'multipart/form-data',
        'id' => 'puntoventa-basica-form',
    ),
    'errorMessageCssClass' => 'has-error',
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'validateOnChange' => true,
        'errorCssClass' => 'has-error',
        'successCssClass' => 'has-success',
    ))
);
?>



<p class="help-block">Los campos con <span class="required">*</span> son obligatorios.</p>
<div class="form-group">
    <?php echo $form->labelEx($model, 'archivo', array('class' => 'col-lg-2 control-label text-primary')); ?>
    <?php echo CHtml::activeFileField($model, 'archivo'); ?>
    <div class="col-lg-10">
        <?php echo $form->error($model, 'archivo', array('class' => 'text-left text-danger')); ?>
    </div>
</div>


<div class="form-group">
    <?php echo CHtml::submitButton('Cargar influencias', array('class' => 'btn btn-primary btn-large')); ?>
</div>

<?php $this->endWidget(); ?>



