<?php $tipoHorario = isset($tipoHorario) ? $tipoHorario : -1; ?>

<?php foreach (Yii::app()->user->getFlashes() as $key => $message): ?>
    <div class="alert alert-dismissable alert-<?php echo $key ?>">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <?php echo $message ?>
    </div>
<?php endforeach; ?>


<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Campos con <span class="required">*</span> son obligatorios.</h3>
    </div>
    <div class="panel-body">


        <form id="form-tipo-horario">
            <div class="form-group">
                <div class="col-lg-offset-4 col-lg-8">
                    <label class="radio-inline">
                        <input type="radio" id="tipoHorario_1" name="tipoHorario" value="1" <?php echo ($tipoHorario == 1 ? "checked" : "") ?> /> D&iacute;a espec&iacute;fico
                    </label> 
                    <label class="radio-inline">
                        <input type="radio" id="tipoHorario_2" name="tipoHorario" value="2" <?php echo ($tipoHorario == 2 ? "checked" : "") ?>/> Rango de fecha
                    </label>
                </div>
            </div>
        </form>

        <div style="height: 50px;"></div>

        <div id="div-horario-dia" style="<?php echo ($tipoHorario == 1 ? "display:block" : "display:none") ?>">
            <?php
            $formDia = $this->beginWidget('CActiveForm', array(
                'enableClientValidation' => true,
                'enableAjaxValidation' => false,
                'htmlOptions' => array(
                    'class' => 'form-horizontal',
                    'role' => 'form',
                    'id' => 'form-horario-especial-dia'
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
            <div class="form-group">
                <?php echo $formDia->labelEx($modelDia, 'Fecha', array('class' => 'col-lg-4 control-label text-primary')); ?>
                <div class="col-lg-7">
                    <?php
                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'model' => $modelDia,
                        'attribute' => 'Fecha',
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
                    <?php echo $formDia->error($modelDia, 'Fecha', array('class' => 'text-left text-danger')); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $formDia->labelEx($modelDia, 'Es24Horas', array('class' => 'col-lg-4 control-label text-primary')); ?>
                <div class="col-lg-7">
                    <?php
                    echo $formDia->radioButtonList($modelDia, 'Es24Horas', array('SI' => 'SI', 'NO' => 'NO'), array('labelOptions' => array('style' => 'display:inline'),
                        'separator' => '  ',
                    ));
                    ?>
                    <?php echo $formDia->error($modelDia, 'Es24Horas', array('class' => 'text-left text-danger')); ?>
                </div>
            </div>
            <div id="div-Es24Horas" style="display:none">
                <div class="form-group">
                    <?php echo $formDia->labelEx($modelDia, 'HoraInicio', array('class' => 'col-lg-4 control-label text-primary')); ?>
                    <div class="col-lg-7">
                        <?php
                        $this->widget('yiiwheels.widgets.timepicker.WhTimePicker', array(
                            'model' => $modelDia,
                            'attribute' => 'HoraInicio',
                            'pluginOptions' => array(
                                'showMeridian' => false
                            ),
                            'htmlOptions' => array(
                                'class' => 'form-control',
                            )
                        ));
                        ?>
                        <?php echo $formDia->error($modelDia, 'HoraInicio', array('class' => 'text-left text-danger')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $formDia->labelEx($modelDia, 'HoraFin', array('class' => 'col-lg-4 control-label text-primary')); ?>
                    <div class="col-lg-7">
                        <?php
                        $this->widget('yiiwheels.widgets.timepicker.WhTimePicker', array(
                            'model' => $modelDia,
                            'attribute' => 'HoraFin',
                            'pluginOptions' => array(
                                'showMeridian' => false
                            ),
                            'htmlOptions' => array(
                                'class' => 'form-control',
                            )
                        ));
                        ?>
                        <?php echo $formDia->error($modelDia, 'HoraFin', array('class' => 'text-left text-danger')); ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-offset-4 col-lg-8">
                    <?php echo CHtml::submitButton('Guardar', array('class' => "btn btn-primary")); ?>
                </div>
            </div>
            <?php $this->endWidget(); ?>
        </div>

        <div id="div-horario-rango" style="<?php echo ($tipoHorario == 2 ? "display:block" : "display:none") ?>">
            <?php
            $formRango = $this->beginWidget('CActiveForm', array(
                'enableClientValidation' => true,
                'enableAjaxValidation' => false,
                'htmlOptions' => array(
                    'class' => 'form-horizontal',
                    'role' => 'form',
                    'id' => 'form-horario-especial-rango'
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

            <div class="form-group">
                <?php echo $formRango->labelEx($modelRango, 'FechaInicio', array('class' => 'col-lg-4 control-label text-primary')); ?>
                <div class="col-lg-7">
                    <?php
                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'model' => $modelRango,
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
                    <?php echo $formRango->error($modelRango, 'FechaInicio', array('class' => 'text-left text-danger')); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $formRango->labelEx($modelRango, 'FechaFin', array('class' => 'col-lg-4 control-label text-primary')); ?>
                <div class="col-lg-7">
                    <?php
                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'model' => $modelRango,
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
                    <?php echo $formRango->error($modelRango, 'FechaFin', array('class' => 'text-left text-danger')); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $formRango->labelEx($modelRango, 'Es24HorasLunesASabado', array('class' => 'col-lg-4 control-label text-primary')); ?>
                <div class="col-lg-7">
                    <?php
                    echo $formRango->radioButtonList($modelRango, 'Es24HorasLunesASabado', array('SI' => 'SI', 'NO' => 'NO'), array('labelOptions' => array('style' => 'display:inline'),
                        'separator' => '  ',
                    ));
                    ?>
                    <?php echo $formRango->error($modelRango, 'Es24HorasLunesASabado', array('class' => 'text-left text-danger')); ?>
                </div>
            </div>

            <div id="div-Es24HorasLunesASabado" style="display:none">
                <div class="form-group">
                    <?php echo $formRango->labelEx($modelRango, 'HoraInicioLunesASabado', array('class' => 'col-lg-4 control-label text-primary')); ?>
                    <div class="col-lg-7">
                        <?php
                        $this->widget('yiiwheels.widgets.timepicker.WhTimePicker', array(
                            'model' => $modelRango,
                            'attribute' => 'HoraInicioLunesASabado',
                            'pluginOptions' => array(
                                'showMeridian' => false
                            ),
                            'htmlOptions' => array(
                                'class' => 'form-control',
                            )
                        ));
                        ?>
                        <div class="help-block">
                            <?php echo $formRango->error($modelRango, 'HoraInicioLunesASabado', array('class' => 'text-left text-danger')); ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <?php echo $formRango->labelEx($modelRango, 'HoraFinLunesASabado', array('class' => 'col-lg-4 control-label text-primary')); ?>
                    <div class="col-lg-7">
                        <?php
                        $this->widget('yiiwheels.widgets.timepicker.WhTimePicker', array(
                            'model' => $modelRango,
                            'attribute' => 'HoraFinLunesASabado',
                            'pluginOptions' => array(
                                'showMeridian' => false
                            ),
                            'htmlOptions' => array(
                                'class' => 'form-control',
                            )
                        ));
                        ?>
                        <?php echo $formRango->error($modelRango, 'HoraFinLunesASabado', array('class' => 'text-left text-danger')); ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <?php echo $formRango->labelEx($modelRango, 'Es24HorasDomingo', array('class' => 'col-lg-4 control-label text-primary')); ?>
                <div class="col-lg-7">
                    <?php
                    echo $formRango->radioButtonList($modelRango, 'Es24HorasDomingo', array('SI' => 'SI', 'NO' => 'NO'), array('labelOptions' => array('style' => 'display:inline'),
                        'separator' => '  ',
                    ));
                    ?>
                    <?php echo $formRango->error($modelRango, 'Es24HorasDomingo', array('class' => 'text-left text-danger')); ?>
                </div>
            </div>

            <div id="div-Es24HorasDomingo" style="display:none">
                <div id="div-">
                    <div class="form-group">
                        <?php echo $formRango->labelEx($modelRango, 'HoraInicioDomingo', array('class' => 'col-lg-4 control-label text-primary')); ?>
                        <div class="col-lg-7">
                            <?php
                            $this->widget('yiiwheels.widgets.timepicker.WhTimePicker', array(
                                'model' => $modelRango,
                                'attribute' => 'HoraInicioDomingo',
                                'pluginOptions' => array(
                                    'showMeridian' => false
                                ),
                                'htmlOptions' => array(
                                    'class' => 'form-control',
                                )
                            ));
                            ?>
                            <?php echo $formRango->error($modelRango, 'HoraInicioDomingo', array('class' => 'text-left text-danger')); ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $formRango->labelEx($modelRango, 'HoraFinDomingo', array('class' => 'col-lg-4 control-label text-primary')); ?>
                    <div class="col-lg-7">
                        <?php
                        $this->widget('yiiwheels.widgets.timepicker.WhTimePicker', array(
                            'model' => $modelRango,
                            'attribute' => 'HoraFinDomingo',
                            'pluginOptions' => array(
                                'showMeridian' => false
                            ),
                            'htmlOptions' => array(
                                'class' => 'form-control',
                            )
                        ));
                        ?>
                        <?php echo $formRango->error($modelRango, 'HoraFinDomingo', array('class' => 'text-left text-danger')); ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <?php echo $formRango->labelEx($modelRango, 'Es24HorasFestivo', array('class' => 'col-lg-4 control-label text-primary')); ?>
                <div class="col-lg-7">
                    <?php
                    echo $formRango->radioButtonList($modelRango, 'Es24HorasFestivo', array('SI' => 'SI', 'NO' => 'NO'), array('labelOptions' => array('style' => 'display:inline'),
                        'separator' => '  ',
                    ));
                    ?>
                    <?php echo $formRango->error($modelRango, 'Es24HorasFestivo', array('class' => 'text-left text-danger')); ?>
                </div>
            </div>

            <div id="div-Es24HorasFestivo" style="display:none">
                <div class="form-group">
                    <?php echo $formRango->labelEx($modelRango, 'HoraInicioFestivo', array('class' => 'col-lg-4 control-label text-primary')); ?>
                    <div class="col-lg-7">
                        <?php
                        $this->widget('yiiwheels.widgets.timepicker.WhTimePicker', array(
                            'model' => $modelRango,
                            'attribute' => 'HoraInicioFestivo',
                            'pluginOptions' => array(
                                'showMeridian' => false
                            ),
                            'htmlOptions' => array(
                                'class' => 'form-control',
                            )
                        ));
                        ?>
                        <?php echo $formRango->error($modelRango, 'HoraInicioFestivo', array('class' => 'text-left text-danger')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $formRango->labelEx($modelRango, 'HoraFinFestivo', array('class' => 'col-lg-4 control-label text-primary')); ?>
                    <div class="col-lg-7">
                        <?php
                        $this->widget('yiiwheels.widgets.timepicker.WhTimePicker', array(
                            'model' => $modelRango,
                            'attribute' => 'HoraFinFestivo',
                            'pluginOptions' => array(
                                'showMeridian' => false
                            ),
                            'htmlOptions' => array(
                                'class' => 'form-control',
                            )
                        ));
                        ?>
                        <?php echo $formRango->error($modelRango, 'HoraFinFestivo', array('class' => 'text-left text-danger')); ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-lg-offset-4 col-lg-8">
                    <?php echo CHtml::submitButton('Guardar', array('class' => "btn btn-primary")); ?>
                </div>
            </div>
            <?php $this->endWidget(); ?>
        </div>

    </div>
</div>

<?php
Yii::app()->clientScript->registerScript('horario-especial-form', "
    $(document).on('change', 'input[name=tipoHorario]:radio', function(e) {
        var tipo = $('input[name=tipoHorario]:checked').val();
        $('#HorarioEspecialGuardarForm_TipoHorario').val(tipo);
        if (tipo==1) {
          $('div[id=\'div-horario-dia\'] input').removeAttr('disabled');
          $('#div-horario-dia').css('display','block');
          $('#div-horario-rango').css('display','none');
          //$('div[id=\'div-horario-rango\'] input[type=\'text\']').val('');
          $('div[id=\'div-horario-rango\'] input').attr('disabled', 'disabled');
        }else if(tipo==2){
          $('div[id=\'div-horario-rango\'] input').removeAttr('disabled');
          $('#div-horario-rango').css('display','block');   
          $('#div-horario-dia').css('display','none');
          //$('div[id=\'div-horario-dia\'] input[type=\'text\']').val('');
          $('div[id=\'div-horario-dia\'] input').attr('disabled', 'disabled');
        }
    });
  ");
?>

<?php
Yii::app()->clientScript->registerScript('horario-especial-24hrs', "
    function validar24hrs(name, tipo){
        var val =$('input[name=\"'+name+'['+tipo+']\"]:checked').val();
        if(val){
          if(val=='SI'){
            $('#div-'+tipo).css('display','none');
          }else{
            $('#div-'+tipo).css('display','block');
          }
        }
    }
    
    validar24hrs('HorarioEspecialDia','Es24Horas');
    validar24hrs('HorarioEspecialRango','Es24HorasLunesASabado');
    validar24hrs('HorarioEspecialRango','Es24HorasDomingo');
    validar24hrs('HorarioEspecialRango','Es24HorasFestivo');
    
    $(document).on('change', 'input[name=\"HorarioEspecialDia[Es24Horas]\"]:radio', function(e) {
        validar24hrs('HorarioEspecialDia','Es24Horas');
    });
    
    $(document).on('change', 'input[name=\"HorarioEspecialRango[Es24HorasLunesASabado]\"]:radio', function(e) {
        validar24hrs('HorarioEspecialRango','Es24HorasLunesASabado');
    });
    
    $(document).on('change', 'input[name=\"HorarioEspecialRango[Es24HorasDomingo]\"]:radio', function(e) {
        validar24hrs('HorarioEspecialRango','Es24HorasDomingo');
    });
    
    $(document).on('change', 'input[name=\"HorarioEspecialRango[Es24HorasFestivo]\"]:radio', function(e) {
        validar24hrs('HorarioEspecialRango','Es24HorasFestivo');
    });
  ");
?>