<?php
$form = $this->beginWidget('CActiveForm', array(
    'enableClientValidation' => true,
    'htmlOptions' => array(
        'id' => 'reporte-horario-form'
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

<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Seleccionar filtros.</h3>
    </div>
    <div class="panel-body">

        <div class="row">
            <div class="col-md-3">
                <?php echo $form->labelEx($model, 'IDSede', array('class' => 'control-label text-primary')); ?>
                <div class="input-group-sm">
                    <?php
                    echo $form->DropDownList($model, 'IDSede', $listSede, array(
                        'prompt' => 'Seleccione',
                        'class' => 'form-control input-sm',
                        'ajax' => array(
                            'type' => 'POST',
                            'beforeSend' => new CJavaScriptExpression("function(){Loading.show();}"),
                            'error' => new CJavaScriptExpression("function(jqXHR, textStatus, errorThrown){Loading.hide(); bootbox.alert('Error: ' + errorThrown);}"),
                            'success' => new CJavaScriptExpression("function(data){ $('#ReporteHorariosForm_IDZona').html(data); $('#ReporteHorariosForm_IDPuntoDeVenta').html(\"<option value=''>Seleccione</option>\"); }"),
                            'complete' => new CJavaScriptExpression("function(){Loading.hide();}"),
                            'url' => $this->createUrl("zonaList"),
                        ))
                    );
                    ?>
                </div>
                <?php echo $form->error($model, 'IDSede', array('class' => 'text-left text-danger')); ?>
            </div>
            <div class="col-md-3">
                <?php echo $form->labelEx($model, 'IDZona', array('class' => 'control-label text-primary')); ?>
                <div class="input-group-sm">
                    <?php
                    echo $form->DropDownList($model, 'IDZona', array(), array(
                        'prompt' => 'Seleccione',
                        'class' => 'form-control input-sm',
                        'ajax' => array(
                            'type' => 'POST',
                            'beforeSend' => new CJavaScriptExpression("function(){Loading.show();}"),
                            'error' => new CJavaScriptExpression("function(jqXHR, textStatus, errorThrown){Loading.hide(); bootbox.alert('Error: ' + errorThrown);}"),
                            'success' => new CJavaScriptExpression("function(data){ $('#ReporteHorariosForm_IDPuntoDeVenta').html(data); }"),
                            'complete' => new CJavaScriptExpression("function(){Loading.hide();}"),
                            'url' => $this->createUrl("pdvList"),
                        ))
                    );
                    ?>

                </div>
                <?php echo $form->error($model, 'IDZona', array('class' => 'text-left text-danger')); ?>
            </div>
            <div class="col-md-5">
                <?php echo $form->labelEx($model, 'IDPuntoDeVenta', array('class' => 'control-label text-primary')); ?>
                <div class="input-group-sm">
                    <?php
                    echo $form->DropDownList($model, 'IDPuntoDeVenta', array(), array(
                        'prompt' => 'Seleccione',
                        'class' => 'form-control input-sm',
                    ));
                    ?>

                </div>
                <?php echo $form->error($model, 'IDPuntoDeVenta', array('class' => 'text-left text-danger')); ?>
            </div>
        </div>

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
            <div class="col-md-1">
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

            <div class="col-md-4">

            </div>
        </div>

        <div class="space"></div>

        <div class="row">
            <div class="col-md-offset-5 col-md-2">
                <?php echo CHtml::submitButton('Exportar', array('class' => 'btn btn-primary btn-sm')); ?>
            </div>
        </div>
    </div>
</div>

<?php $this->endWidget(); ?>
