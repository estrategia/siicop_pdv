<?php
$form = $this->beginWidget('CActiveForm', array(
    'enableClientValidation' => true,
    //'enableAjaxValidation' => false,
    'htmlOptions' => array(
        //'class' => 'form-inline',
        //'role' => 'form',
        'id' => 'reporte-analista-form',
    //"onsubmit" => new CJavaScriptExpression("$('#div-form-exportar').toggle();")
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

<div class="row">
    <div class="col-md-2">
        <?php echo $form->labelEx($model, 'FechaInicio', array('class' => 'control-label text-primary')); ?>
        <div class="input-group-sm">
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model' => $model,
                'attribute' => 'FechaInicio',
                'language' => 'es',
                'options' => array(
                    'showAnim' => 'slide',
                    'dateFormat' => 'yy-mm-dd',
                ),
                'htmlOptions' => array(
                    'class' => 'form-control input-sm',
                    'size' => '10',
                    'maxlength' => '10',
                    'placeholder' => 'yyyy-mm-dd',
                ),
            ));
            ?>
        </div>
        <?php echo $form->error($model, 'FechaInicio', array('class' => 'text-left text-danger')); ?>
    </div>

    <div class="col-md-2">
        <?php echo $form->labelEx($model, 'FechaFin', array('class' => 'control-label text-primary')); ?>
        <div class="input-group-sm">
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model' => $model,
                'attribute' => 'FechaFin',
                'language' => 'es',
                'options' => array(
                    'showAnim' => 'slide',
                    'dateFormat' => 'yy-mm-dd',
                ),
                'htmlOptions' => array(
                    'class' => 'form-control input-sm',
                    'size' => '10',
                    'maxlength' => '10',
                    'placeholder' => 'yyyy-mm-dd',
                ),
            ));
            ?>
        </div>
        <?php echo $form->error($model, 'FechaFin', array('class' => 'text-left text-danger')); ?>
    </div>
    <!--
    <div class="col-md-2">
        <br>
    <?php //echo CHtml::submitButton('Exportar', array('class' => 'btn btn-primary btn-sm')); ?>
    <?php //echo CHtml::resetButton('Reinicializar', array('class' => 'btn btn-default btn-sm')); ?>
    </div>
    -->
    <div class="col-md-2">
        <label class="control-label text-primary required">&nbsp;</label>
        <div class="input-group-sm">
            <?php echo CHtml::submitButton('Exportar Excel', array('class' => 'btn btn-primary btn-sm', 'name'=>'excel')); ?>
            <?php //echo CHtml::resetButton('Reinicializar', array('class' => 'btn btn-default btn-sm')); ?>
        </div>
    </div>
    
    <!--
    <div class="col-md-2">
        <label class="control-label text-primary required">&nbsp;</label>
        <div class="input-group-sm">
            <?php //echo CHtml::submitButton('Exportar Siesa', array('class' => 'btn btn-primary btn-sm', 'name'=>'siesa')); ?>
            <?php //echo CHtml::resetButton('Reinicializar', array('class' => 'btn btn-default btn-sm')); ?>
        </div>
    </div>
    -->
    
    
    <!--
    <div class="col-md-1">
        <label class="control-label text-primary required">&nbsp;&nbsp;&nbsp;</label>
    <?php //echo CHtml::resetButton('Reinicializar', array('class' => 'btn btn-default btn-sm')); ?>

    </div>
    -->
</div>

<?php $this->endWidget(); ?>
