<?php
$form = $this->beginWidget('CActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
    'id' => 'search-form',
    'htmlOptions' => array(),
        ));
?>

<div class="input-group">
    <span class="input-group-addon"><i class="icon-search"></i></span>
    <?php echo $form->textField($model, 'search', array('class' => 'form-control', 'placeholder' => 'Buscar ...')); ?>
    <span class="input-group-btn">
        <button class="btn btn-default" type="submit">B&uacute;scar</button>
    </span>
</div>
<?php $this->endWidget(); ?>
<?php unset($form); ?>