<?php
Yii::app()->clientScript->registerScript('telefonos-zona-admin', "
    $('#telefono-zona-add').click(function(){
        var cant = Number($('#nTelefonos').val()) + 1;
        var html = \"<div class='row'><div class='col-md-4'><div class='input-group-sm' style='width: 80%'><input id='TelefonosZona_\" + cant + \"_NumeroTelefono' class='form-control input-sm' type='text' value='' name='TelefonosZona[\" + cant + \"][NumeroTelefono]' maxlength='45'><input id='TelefonosZona_\" + cant + \"_IDTelefonoZona' type='hidden' value='' name='TelefonosZona[\" + cant + \"][IDTelefonoZona]'></div><div id='TelefonosZona_NumeroTelefono_em_\" + cant + \"' class='text-left text-danger' style='display:none'></div></div></div><div class='space'></div>\";
        
        $('#id-telefonos-zona').append(html);
        $('#nTelefonos').val(cant);
        return false;
    });");
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'enableClientValidation' => true,
    'enableAjaxValidation' => false,
    'htmlOptions' => array(
        //'class' => 'form-horizontal',
        //'role' => 'form',
        'id' => 'zonas-form',
        'class' => 'form-inline'
    ),
    'errorMessageCssClass' => 'has-error',
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'errorCssClass' => 'has-error',
        'successCssClass' => 'has-success',
        //'inputContainer' => '.form-group',
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
            <?php echo $form->hiddenField($model, 'IDZona'); ?>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-4">
                <?php echo $form->labelEx($model, 'NombreZona', array('class' => 'control-label text-primary')); ?>
                <div class="input-group-sm">
                    <?php echo $form->textField($model, 'NombreZona', array('class' => 'form-control input-sm', 'maxlength' => 45)); ?>
                </div>
                <?php echo $form->error($model, 'NombreZona', array('class' => 'text-left text-danger')); ?>
            </div>
            <div class="col-md-4">
                <?php echo $form->labelEx($model, 'IDSede', array('class' => 'control-label text-primary')); ?>
                <div class="input-group-sm" style="width: 50%">
                    <?php echo $form->dropDownList($model, 'IDSede', Sede::listData(), array('class' => 'form-control input-sm')); ?>
                </div>
                <?php echo $form->error($model, 'IDSede', array('class' => 'text-left text-danger')); ?>
            </div>
            <div class="col-md-4">
                <?php echo $form->labelEx($model, 'DireccionZona', array('class' => 'control-label text-primary')); ?>
                <div class="input-group-sm">
                    <?php echo $form->textField($model, 'DireccionZona', array('class' => 'form-control input-sm', 'maxlength' => 45)); ?>
                </div>
                <?php echo $form->error($model, 'DireccionZona', array('class' => 'text-left text-danger')); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <?php echo $form->labelEx($model, 'CedulaDirectorZona', array('class' => 'control-label text-primary')); ?>
                <div class="input-group-sm" style="width: 50%">
                    <?php
                    $this->widget('bootstrap.widgets.TbTypeAhead', array(
                        'model' => $model,
                        'attribute' => 'CedulaDirectorZona',
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
                </div>
                <?php echo $form->error($model, 'CedulaDirectorZona', array('class' => 'text-left text-danger')); ?>
            </div>
            <div class="col-md-4">
                <label class="control-label text-primary" for="Zonas_NombreDirector">
                    Nombre Director
                </label>
                <div class="input-group-sm">
                    <input id="Zonas_NombreDirector" class="form-control" type="text" value="<?php echo $model->NombreDirectorZona ?>" name="Zonas_NombreDirector" readonly="readonly">

                </div>
            </div>
            <div class="col-md-4">
                <?php echo $form->labelEx($model, 'eMailDirectorZona', array('class' => 'control-label text-primary')); ?>
                <div class="input-group-sm">
                    <?php echo $form->textField($model, 'eMailDirectorZona', array('class' => 'form-control input-sm', 'maxlength' => 100)); ?>
                </div>
                <?php echo $form->error($model, 'eMailDirectorZona', array('class' => 'text-left text-danger')); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <?php echo $form->labelEx($model, 'CedulaAuxiliarZona', array('class' => 'control-label text-primary')); ?>
                <div class="input-group-sm" style="width: 50%">
                    <?php
                    $this->widget('bootstrap.widgets.TbTypeAhead', array(
                        'model' => $model,
                        'attribute' => 'CedulaAuxiliarZona',
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
                </div>
                <?php echo $form->error($model, 'CedulaAuxiliarZona', array('class' => 'text-left text-danger')); ?>
            </div>

            <div class="col-md-4">
                <label class="control-label text-primary" for="Zonas_NombreAuxiliar">
                    Nombre Auxiliar
                </label>
                <div class="input-group-sm">
                    <input id="Zonas_NombreAuxiliar" class="form-control" type="text" value="<?php echo $model->NombreAuxiliarZona ?>" name="Zonas_NombreAuxiliar" readonly="readonly">

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <?php echo $form->labelEx($model, 'eMailZona', array('class' => 'control-label text-primary')); ?>
                <div class="input-group-sm">
                    <?php echo $form->textField($model, 'eMailZona', array('class' => 'form-control input-sm', 'maxlength' => 100)); ?>
                </div>
                <?php echo $form->error($model, 'eMailZona', array('class' => 'text-left text-danger')); ?>
            </div>
            <div class="col-md-4">
                <?php echo $form->labelEx($model, 'CelularZona', array('class' => 'control-label text-primary')); ?>
                <div class="input-group-sm" style="width: 80%">
                    <?php echo $form->textField($model, 'CelularZona', array('class' => 'form-control input-sm', 'maxlength' => 45)); ?>
                </div>
                <?php echo $form->error($model, 'CelularZona', array('class' => 'text-left text-danger')); ?>
            </div>
            <div class="col-md-4">
                <?php echo $form->labelEx($model, 'IndicativoTelefonoZona', array('class' => 'control-label text-primary')); ?>
                <div class="input-group-sm" style="width: 40%">
                    <?php echo $form->textField($model, 'IndicativoTelefonoZona', array('class' => 'form-control input-sm', 'maxlength' => 45)); ?>
                </div>
                <?php echo $form->error($model, 'IndicativoTelefonoZona', array('class' => 'text-left text-danger')); ?>
            </div>
        </div>

        <div class="space"></div>

        <fieldset>
            <legend>Tel&eacute;fonos <?php echo CHtml::button('+', array('id' => 'telefono-zona-add', 'encode'=>false, 'class' => 'btn btn-primary btn-xs')); ?></legend>
            
            <div id="id-telefonos-zona">
                <?php if (empty($telefonos)): ?>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="input-group-sm" style="width: 80%">
                                <input id="TelefonosZona_0_NumeroTelefono" class="form-control input-sm" type="text" value="" name="TelefonosZona[0][NumeroTelefono]" maxlength="45">
                                <input id="TelefonosZona_0_IDTelefonoZona" type="hidden" value="" name="TelefonosZona[0][IDTelefonoZona]">
                            </div>
                            <div id="TelefonosZona_NumeroTelefono_em_0" class="text-left text-danger" style="display:none"></div>
                        </div>
                    </div>
                    <div class="space"></div>
                <?php else: ?>
                    <?php foreach ($telefonos as $indice => $telefono): ?>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group-sm" style="width: 80%">
                                    <input id="TelefonosZona_<?php echo $indice; ?>_NumeroTelefono" class="form-control input-sm" type="text" value="<?php echo $telefono->NumeroTelefono; ?>" name="TelefonosZona[<?php echo $indice; ?>][NumeroTelefono]" maxlength="45">
                                    <input id="TelefonosZona_<?php echo $indice; ?>_IDTelefonoZona" type="hidden" value="<?php echo $telefono->IDTelefonoZona; ?>" name="TelefonosZona[<?php echo $indice; ?>][IDTelefonoZona]">
                                </div>
                                <div id="TelefonosZona_NumeroTelefono_em_<?php echo $indice; ?>" class="text-left text-danger" style="display:none"></div>
                            </div>
                        </div>
                        <div class="space"></div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </fieldset>


        <input id="nTelefonos" type="hidden" value="<?php echo (empty($telefonos) ? 0 : count($telefonos)-1); ?>" name="nTelefonos">

        <!--
        <div class="row">
            <div class="col-md-4">
                <label class="control-label text-primary" for="TelefonosZona_0_NumeroTelefono">Tel&eacute;fono</label>
                <div class="input-group-sm" style="width: 80%">
                    <input id="TelefonosZona_0_NumeroTelefono" class="form-control input-sm" type="text" value="" name="TelefonosZona[0][NumeroTelefono]" maxlength="45">
                    <input id="TelefonosZona_0_IDTelefonoZona" type="hidden" value="" name="TelefonosZona[0][IDTelefonoZona]">
                </div>
                <div id="TelefonosZona_NumeroTelefono_em_0" class="text-left text-danger" style="display:none"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <label class="control-label text-primary" for="TelefonosZona_1_NumeroTelefono">Tel&eacute;fono</label>
                <div class="input-group-sm" style="width: 80%">
                    <input id="TelefonosZona_1_NumeroTelefono" class="form-control input-sm" type="text" value="" name="TelefonosZona[1][NumeroTelefono]" maxlength="45">
                    <input id="TelefonosZona_1_IDTelefonoZona" type="hidden" value="" name="TelefonosZona[1][IDTelefonoZona]">
                </div>
                <div id="TelefonosZona_NumeroTelefono_em_1" class="text-left text-danger" style="display:none"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <label class="control-label text-primary" for="TelefonosZona_2_NumeroTelefono">Tel&eacute;fono</label>
                <div class="input-group-sm" style="width: 80%">
                    <input id="TelefonosZona_2_NumeroTelefono" class="form-control input-sm" type="text" value="" name="TelefonosZona[2][NumeroTelefono]" maxlength="45">
                    <input id="TelefonosZona_2_IDTelefonoZona" type="hidden" value="" name="TelefonosZona[2][IDTelefonoZona]">
                </div>
                <div id="TelefonosZona_NumeroTelefono_em_2" class="text-left text-danger" style="display:none"></div>
            </div>
        </div>
        -->

        <div class="space"></div>
        <div class="row">
            <div class="col-md-7 col-md-offset-5">
                <?php
                echo CHtml::ajaxSubmitButton($model->isNewRecord ? 'Crear' : 'Actualizar', $model->isNewRecord ? Yii::app()->createUrl('/puntoventa/zonas/crear') : Yii::app()->createUrl('/puntoventa/zonas/actualizar'), array(
                    'beforeSend' => new CJavaScriptExpression("function(){Loading.show(); $('#zonas-form input[type=submit]').attr('disabled', 'disabled');}"),
                    'success' => new CJavaScriptExpression("function(data){
                        obj = $.parseJSON(data);
                        if(obj.result=='ok'){
                            $.fn.yiiGridView.update('zonas-grid');
                            $('#zona-form-modal').modal('hide');
                        }else if(obj.result=='error'){
                            bootbox.alert(obj.response);
                        }else if(obj.result=='invalid'){
                            $('div[id^=\'TelefonosZona_\']').html('');
                            $('div[id^=\'TelefonosZona_\']').css('display','none');
                            var item = obj.response.item;
                            var errors = obj = $.parseJSON(obj.response.errors);

                            $.each(errors,function(element,error){
                            console.log('Item: ' + item + ' Element: ' + element + ' Error: ' + error);
                                $('#'+element+'_em_'+item).html(error);
                                $('#'+element+'_em_'+item).css('display','block');
                            });
                        }else{
                            $.each(obj,function(element,error){
                                $('#'+element+'_em_').html(error);
                                $('#'+element+'_em_').css('display','block');
                            });
                        }
                        Loading.hide();
                    }"),
                    'complete' => new CJavaScriptExpression("function(){ $('#zonas-form input[type=submit]').removeAttr('disabled');}"),
                    'error' => new CJavaScriptExpression("function(data){
                        Loading.hide();
                        $('#zona-form-modal').modal('hide');
                        bootbox.alert('Error, intente de nuevo');
                    }")), array(
                    'id' => uniqid(),
                    'class' => 'btn btn-primary'
                ));
                ?>
            </div>
        </div>

    </div>
</div>

<?php $this->endWidget(); ?>
