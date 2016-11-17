<?php
$form = $this->beginWidget('CActiveForm', array(
    'enableClientValidation' => true,
    'htmlOptions' => array(
        'class' => 'form-horizontal',
        'role' => 'form',
        'id' => 'imagen-punto-venta-form'
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

<?php echo CHtml::label('Registro Inicio', 'RegistroActividad[RegistroInicio]', array()); ?>
<?php echo CHtml::textArea('RegistroActividad[RegistroInicio]', $model->RegistroInicio, array('class' => 'span5', 'rows' => 6, 'readOnly'=>'readOnly')); ?>
<?php echo CHtml::label('Registro Fin', 'RegistroActividad[RegistroFin]', array()); ?>
<?php echo CHtml::textArea('RegistroActividad[RegistroFin]', $model->RegistroFin, array('class' => 'span5', 'rows' => 6, 'readOnly'=>'readOnly')); ?>

<?php $this->endWidget(); ?>