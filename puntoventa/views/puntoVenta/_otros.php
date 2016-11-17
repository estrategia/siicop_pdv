<?php if ($active): ?>
    <?php foreach (Yii::app()->user->getFlashes() as $key => $message) { ?>
        <div class="alert alert-dismissable alert-<?php echo $key ?>">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <?php echo $message ?>
        </div>
    <?php } ?>
<?php endif; ?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'enableClientValidation' => true,
    //'enableAjaxValidation' => false,
    'htmlOptions' => array(
        //'class' => 'form-horizontal',
        //'role' => 'form',
        'id' => 'puntoventa-otros-form',
        'class' => 'form-inline'
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
        <h3 class="panel-title">Campos con <span class="required">*</span> son obligatorios.</h3>
    </div>
    <div class="panel-body">

        <fieldset>
            <legend>Datos Local</legend>
            <div class="row">
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'DimensionAncho', array('class' => 'control-label text-primary')); ?>
                    <div class="input-group input-group-sm" style="width: 30%">
                        <?php echo $form->textField($model, 'DimensionAncho', array('class' => 'form-control input-sm')); ?>
                        <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                    </div>
                    <?php echo $form->error($model, 'DimensionAncho', array('class' => 'text-left text-danger')); ?>

                </div>
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'DimensionFondo', array('class' => 'control-label text-primary')); ?>
                    <div class="input-group input-group-sm" style="width: 30%">
                        <?php echo $form->textField($model, 'DimensionFondo', array('class' => 'form-control input-sm')); ?>
                        <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                    </div>
                    <?php echo $form->error($model, 'DimensionFondo', array('class' => 'text-left text-danger')); ?>

                </div>
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'AreaLocal', array('class' => 'control-label text-primary')); ?>
                    <div class="input-group input-group-sm" style="width: 30%">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                        <?php echo $form->textField($model, 'AreaLocal', array('class' => 'form-control input-sm', 'disabled' => 'disabled')); ?>
                    </div>
                    <?php echo $form->error($model, 'AreaLocal', array('class' => 'text-left text-danger')); ?>
                </div>
            </div>
        </fieldset>

        <div class="space"></div>

        <fieldset>
            <legend>Datos T&eacute;nicos</legend>
            <div class="row">
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'IPCamara', array('class' => 'control-label text-primary')); ?>
                    <div class="input-group input-group-sm" style="width: 80%">
                        <?php echo $form->textField($model, 'IPCamara', array('class' => 'form-control input-sm', 'maxlength' => 20)); ?>
                        <?php if($consulta): ?>
                        <span class="input-group-addon"><?php echo CHtml::link('<i class="glyphicon glyphicon-facetime-video"></i>',  "http://$model->IPCamara", array('target'=>'_blank')); ?></span>
                        <?php else: ?>
                        <span class="input-group-addon"><i class="glyphicon glyphicon-facetime-video"></i></span>
                        <?php endif; ?>
                    </div>
                    <?php echo $form->error($model, 'IPCamara', array('class' => 'text-left text-danger')); ?>

                </div>
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'DireccionIPServidor', array('class' => 'control-label text-primary')); ?>
                    <div class="input-group input-group-sm" style="width: 80%">
                        <?php echo $form->textField($model, 'DireccionIPServidor', array('class' => 'form-control input-sm', 'maxlength' => 20)); ?>
                        <span class="input-group-addon"><i class="glyphicon glyphicon-hdd"></i></span>
                    </div>
                    <?php echo $form->error($model, 'DireccionIPServidor', array('class' => 'text-left text-danger')); ?>

                </div>
            </div>

        </fieldset>

        <div class="space"></div>

        <fieldset>
            <legend>Datos Geolocalizaci&oacute;n</legend>
            <div class="row">
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'DireccionGoogle', array('class' => 'control-label text-primary')); ?>
                    <div class="input-group input-group-sm">
                        <?php echo $form->textField($model, 'DireccionGoogle', array('class' => 'form-control input-sm', 'maxlength' => 255)); ?>
                        <span class="input-group-addon"><i class="glyphicon glyphicon-globe"></i></span>
                    </div>
                    <?php echo $form->error($model, 'DireccionGoogle', array('class' => 'text-left text-danger')); ?>
                </div>
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'LatitudGoogle', array('class' => 'control-label text-primary')); ?>
                    <div class="input-group input-group-sm">
                        <?php echo $form->textField($model, 'LatitudGoogle', array('class' => 'form-control input-sm')); ?>
                        <span class="input-group-addon"><i class="glyphicon glyphicon-map-marker"></i></span>
                    </div>
                    <?php echo $form->error($model, 'LatitudGoogle', array('class' => 'text-left text-danger')); ?>

                </div>
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'LongitudGoogle', array('class' => 'control-label text-primary')); ?>
                    <div class="input-group input-group-sm">
                        <?php echo $form->textField($model, 'LongitudGoogle', array('class' => 'form-control input-sm')); ?>
                        <span class="input-group-addon"><i class="glyphicon glyphicon-map-marker"></i></span>
                    </div>
                    <?php echo $form->error($model, 'LongitudGoogle'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'RutaImagenMapa', array('class' => 'control-label text-primary')); ?>
                    <div class="input-group input-group-sm">
                        <?php echo $form->textField($model, 'RutaImagenMapa', array('class' => 'form-control input-sm', 'maxlength' => 100)); ?>
                        <span class="input-group-addon"><i class="glyphicon glyphicon-picture"></i></span>
                    </div>
                    <?php echo $form->error($model, 'RutaImagenMapa', array('class' => 'text-left text-danger')); ?>
                </div>
            </div>
        </fieldset>

        <?php echo CHtml::hiddenField('info', Yii::app()->controller->module->infoOtros) ?>


        <!--
        <div class="form-group">
            <div class="col-lg-offset-5 col-lg-7">
        <?php echo CHtml::submitButton('Grabar', array('class' => 'btn btn-primary btn-lg')); ?>
            </div>
        </div>
        -->

    </div>
</div>

<input type="hidden" id="otros_tab_siguiente" name="tab_siguiente" value="<?php echo ($tab + 1) ?>" />

<?php if ($consulta): ?>
    <div class="row">
        <div class="footer">
            <ul class="pager">
                <li class="previous" id="id_otros_anterior"><a href="#" class="anterior">← Anterior</a></li>
                <li class="next" id="id_otros_siguiente"><a href="#" class="siguiente">Siguiente →</a></li>
            </ul>
        </div>
    </div>


    <?php
    Yii::app()->clientScript->registerScript('otros-tab', "
        $(document).on('click', '#id_otros_anterior', function(e) {
            $('#puntoventa_tabs').tabs({ active: " . ($tab - 1) . "});
        });
        $(document).on('click', '#id_otros_siguiente', function(e) {
            $('#puntoventa_tabs').tabs({ active: " . ($tab + 1) . "});
        });");
    ?>

    <?php
    Yii::app()->clientScript->registerScript('otros-disabled', "
    $('#puntoventa-otros-form input').attr('disabled', 'disabled');
    $('#puntoventa-otros-form select').attr('disabled', 'disabled');");
    ?>
<?php else: ?>
    <div class="row">
        <div class="footer">
            <ul class="pager">
                <li class="previous" id="id_otros_anterior"><?php echo CHtml::linkButton('← Anterior', array('class' => 'anterior')); ?></li>
                <li class="next" id="id_otros_siguiente"><?php echo CHtml::linkButton('Siguiente →', array('class' => 'siguiente')); ?></li>
            </ul>
        </div>
    </div>
    <?php
    Yii::app()->clientScript->registerScript('otros-tab', "
        $(document).on('mouseenter', '#id_otros_anterior', function(e) {
            $('#otros_tab_siguiente').val(" . ($tab - 1) . ");
        });
        $(document).on('mouseenter', '#id_otros_siguiente', function(e) {
            $('#otros_tab_siguiente').val(" . ($tab + 1) . ");
        });
        ");
    ?>
<?php endif; ?>





<?php $this->endWidget(); ?>

<div class="clear"></div>

