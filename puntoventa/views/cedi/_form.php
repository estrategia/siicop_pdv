<?php
$form = $this->beginWidget('CActiveForm', array(
    'enableClientValidation' => true,
    'enableAjaxValidation' => false,
    'htmlOptions' => array(
        'class' => 'form-horizontal',
        'role' => 'form',
        'id' => 'cedi-form'
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
        <?php if (!$model->isNewRecord): ?>
            <?php echo $form->hiddenField($model, 'IDCEDI'); ?>
        <?php endif; ?>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'NombreCEDI', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php echo $form->textField($model, 'NombreCEDI', array('class' => 'form-control input-sm', 'maxlength' => 45)); ?>
                <?php echo $form->error($model, 'NombreCEDI', array('class' => 'text-left text-danger')); ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'IdCentroCostos', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php
                $this->widget('bootstrap.widgets.TbTypeAhead', array(
                    'model' => $model,
                    'attribute' => 'IdCentroCostos',
                    'minLength' => 2,
                    'source' => new CJavaScriptExpression("
                        function (query, process) {
                            $.ajax({
                                type: 'POST',
                                dataType : 'json',
                                url: '" . $this->createUrl("ccostos/ajax") . "',
                                data: {term: query},
                                success: function(data){
                                    items = [];
                                    map = {};
                                    $.each(data, function (i, item) {
                                        map[item.label] = item;
                                        items.push(item.label);
                                    });

                                    process(items);
                                }
                            })
                        }"),
                    'updater' => new CJavaScriptExpression("
                        function (item) {
                            $('#CEDI_NombreCentroCostos').val(map[item].label);
                            return map[item].value;
                        }
                    "),
                    'htmlOptions' => array(
                        'prepend' => TbHtml::icon(TbHtml::ICON_GLOBE),
                        'autocomplete' => 'off',
                        'placeholder' => 'Digite nombre',
                        'class' => 'form-control input-sm',
                    ),
                ));
                ?>
                <?php echo $form->error($model, 'IdCentroCostos', array('class' => 'text-left text-danger')); ?>

            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-4 control-label">Nombre Centro Costos</label>            
            <div class="col-lg-7">
                <div class="input-prepend"><span class="add-on"><i class="icon-globe"></i></span><input id="CEDI_NombreCentroCostos" type="text" maxlength="20" class="form-control input-sm" value="<?php echo $model->NombreCentroCostos; ?>" disabled="disabled"></div>                
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'CedulaJefe', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php
                $this->widget('bootstrap.widgets.TbTypeAhead', array(
                    'model' => $model,
                    'attribute' => 'CedulaJefe',
                    'minLength' => 2,
                    'source' => new CJavaScriptExpression("
                    function (query, process) {
                        $.ajax({
                            type: 'POST',
                            dataType : 'json',
                            url: '" . $this->createUrl("usuario/ajax") . "',
                            data: {term: query, activo:true},
                            success: function(data){
                                items = [];
                                map = {};
                                $.each(data, function (i, item) {
                                    map[item.label] = item;
                                    items.push(item.label);
                                });

                                process(items);
                            }
                        })
                    }"),
                    'updater' => new CJavaScriptExpression("
                        function (item) {
                            $('#CEDI_NombreJefe').val(map[item].label);
                            return map[item].value;
                        }
                    "),
                    'htmlOptions' => array(
                        'prepend' => TbHtml::icon(TbHtml::ICON_GLOBE),
                        'autocomplete' => 'off',
                        'placeholder' => 'Digite nombre',
                        'class' => 'form-control input-sm',
                    ),
                ));
                ?>
                <?php echo $form->error($model, 'CedulaJefe', array('class' => 'text-left text-danger')); ?>
            </div>

            

        </div>

        <div class="form-group">
            <label class="col-lg-4 control-label">Nombre Jefe</label>            
            <div class="col-lg-7">
                <div class="input-prepend"><span class="add-on"><i class="icon-globe"></i></span><input id="CEDI_NombreJefe" type="text" maxlength="20" class="form-control input-sm" value="<?php echo $model->NombreJefe; ?>" disabled="disabled"></div>                
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'CelularJefe', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php echo $form->textField($model, 'CelularJefe', array('class' => 'form-control input-sm', 'maxlength' => 20)); ?>


                <?php echo $form->error($model, 'CelularJefe', array('class' => 'text-left text-danger')); ?>

            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'TelefonoCEDI', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php echo $form->textField($model, 'TelefonoCEDI', array('class' => 'form-control input-sm', 'maxlength' => 45)); ?>
                <?php echo $form->error($model, 'TelefonoCEDI', array('class' => 'text-left text-danger')); ?>

            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'IndicativoTelefonoCEDI', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php echo $form->textField($model, 'IndicativoTelefonoCEDI', array('class' => 'form-control input-sm', 'maxlength' => 45)); ?>
                <?php echo $form->error($model, 'IndicativoTelefonoCEDI', array('class' => 'text-left text-danger')); ?>

            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'DireccionCEDI', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php echo $form->textField($model, 'DireccionCEDI', array('class' => 'form-control input-sm', 'maxlength' => 100)); ?>
                <?php echo $form->error($model, 'DireccionCEDI', array('class' => 'text-left text-danger')); ?>

            </div>
        </div>
        
        <div class="form-group">
            <?php echo $form->labelEx($model, 'CodigoEAN', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php echo $form->textField($model, 'CodigoEAN', array('class' => 'form-control input-sm', 'maxlength' => 255)); ?>
                <?php echo $form->error($model, 'CodigoEAN', array('class' => 'text-left text-danger')); ?>

            </div>
        </div>
        
        <div class="form-group">
            <?php echo $form->labelEx($model, 'HorarioAperturaLunesAViernes', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-5">
                <?php
                $this->widget('yiiwheels.widgets.timepicker.WhTimePicker', array(
                    'model' => $model,
                    'attribute' => 'HorarioAperturaLunesAViernes',
                    'pluginOptions' => array(
                        'showMeridian' => false
                    ),
                    'htmlOptions' => array(
                        'class' => 'form-control input-sm'
                    )
                ));
                ?>
                <?php echo $form->error($model, 'HorarioAperturaLunesAViernes',  array('class' => 'text-left text-danger')); ?>

            </div>
        </div>
        
        <div class="form-group">
            <?php echo $form->labelEx($model, 'HorarioCierreLunesAViernes', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-5">
                <?php
                $this->widget('yiiwheels.widgets.timepicker.WhTimePicker', array(
                    'model' => $model,
                    'attribute' => 'HorarioCierreLunesAViernes',
                    'pluginOptions' => array(
                        'showMeridian' => false
                    ),
                    'htmlOptions' => array(
                        'class' => 'form-control input-sm'
                    )
                ));
                ?>
                <?php echo $form->error($model, 'HorarioCierreLunesAViernes',  array('class' => 'text-left text-danger')); ?>

            </div>
        </div>
        
        <div class="form-group">
            <?php echo $form->labelEx($model, 'HorarioAperturaSabado', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-5">
                <?php
                $this->widget('yiiwheels.widgets.timepicker.WhTimePicker', array(
                    'model' => $model,
                    'attribute' => 'HorarioAperturaSabado',
                    'pluginOptions' => array(
                        'showMeridian' => false
                    ),
                    'htmlOptions' => array(
                        'class' => 'form-control input-sm'
                    )
                ));
                ?>
                <?php echo $form->error($model, 'HorarioAperturaSabado',  array('class' => 'text-left text-danger')); ?>

            </div>
        </div>
        
        <div class="form-group">
            <?php echo $form->labelEx($model, 'HorarioCierreSabado', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-5">
                <?php
                $this->widget('yiiwheels.widgets.timepicker.WhTimePicker', array(
                    'model' => $model,
                    'attribute' => 'HorarioCierreSabado',
                    'pluginOptions' => array(
                        'showMeridian' => false
                    ),
                    'htmlOptions' => array(
                        'class' => 'form-control input-sm'
                    )
                ));
                ?>
                <?php echo $form->error($model, 'HorarioCierreSabado',  array('class' => 'text-left text-danger')); ?>

            </div>
        </div>

        <div class="form-group">
            <div class="col-lg-offset-5 col-lg-7">
                <?php
                echo CHtml::ajaxSubmitButton($model->isNewRecord ? 'Crear' : 'Actualizar', $model->isNewRecord ? Yii::app()->createUrl('/puntoventa/cedi/crear') : Yii::app()->createUrl('/puntoventa/cedi/actualizar'), array(
                    'beforeSend' => new CJavaScriptExpression("function(){Loading.show();}"),
                    'success' => new CJavaScriptExpression("function(data){
                obj = $.parseJSON(data);
                if(obj.result=='ok'){
                    $.fn.yiiGridView.update('cedi-grid');
                    $('#cedi-form-modal').modal('hide');
                }else if(obj.result=='error'){
                    bootbox.alert(obj.response);
                }else{
                    $.each(obj,function(element,error){
                        $('#'+element+'_em_').html(error);
                        $('#'+element+'_em_').css('display','block');
                    });
                }
                Loading.hide();
        }"),
                    'error' => new CJavaScriptExpression("function(data){
                Loading.hide();
            $('#cedi-form-modal').modal('hide');
            bootbox.alert('Error, intente de nuevo');
        }")), array('id' => uniqid(), 'class' => 'btn btn-primary',));
                ?>
            </div>
        </div>

    </div>
</div>

<?php $this->endWidget(); ?>
