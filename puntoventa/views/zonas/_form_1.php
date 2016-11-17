<?php
$form = $this->beginWidget('CActiveForm', array(
    'enableClientValidation' => true,
    'htmlOptions' => array(
        'class' => 'form-horizontal',
        'role' => 'form',
        'id' => 'zonas-form'
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

        <div class="form-group">
            <?php echo $form->labelEx($model, 'IDSede', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php echo $form->dropDownList($model, 'IDSede', Sede::listData(), array('class' => 'form-control input-sm')); ?>
                <?php echo $form->error($model, 'IDSede', array('class' => 'text-left text-danger')); ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'NombreZona', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php echo $form->textField($model, 'NombreZona', array('class' => 'form-control input-sm', 'maxlength' => 45)); ?>
                <?php echo $form->error($model, 'NombreZona', array('class' => 'text-left text-danger')); ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'DireccionZona', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php echo $form->textField($model, 'DireccionZona', array('class' => 'form-control input-sm', 'maxlength' => 45)); ?>
                <?php echo $form->error($model, 'DireccionZona', array('class' => 'text-left text-danger')); ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'TelefonoZona', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php echo $form->textField($model, 'TelefonoZona', array('class' => 'form-control input-sm', 'maxlength' => 45)); ?>
                <?php echo $form->error($model, 'TelefonoZona', array('class' => 'text-left text-danger')); ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'CelularZona', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php echo $form->textField($model, 'CelularZona', array('class' => 'form-control input-sm', 'maxlength' => 45)); ?>
                <?php echo $form->error($model, 'CelularZona', array('class' => 'text-left text-danger')); ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'eMailZona', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php echo $form->textField($model, 'eMailZona', array('class' => 'form-control input-sm', 'maxlength' => 100)); ?>
                <?php echo $form->error($model, 'eMailZona', array('class' => 'text-left text-danger')); ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'CedulaDirectorZona', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
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
                <div class="has-error">
                    <div class="help-block">
                        <?php echo $form->error($model, 'CedulaDirectorZona'); ?>
                    </div>
                </div>
            </div>
            <!--
            <div class="col-lg-4">
            <?php echo $model->NombreDirectorZona; ?>
            </div>
            -->

            <?php
            /*
              $this->widget('bootstrap.widgets.TbButton', array(
              'encodeLabel' => false,
              'label' => 'Ver nombre',
              'type' => 'link',
              'htmlOptions' => array('encode' => false, 'data-title' => 'Nombre', 'data-content' => $model->NombreDirectorZona, 'rel' => 'popover', 'data-placement' => 'left',),
              ));
             */
            ?>

        </div>

        <div class="form-group">
            <label class="col-lg-4 control-label">Nombre Director</label>            
            <div class="col-lg-7">
                <div class="input-prepend"><span class="add-on"><i class="icon-globe"></i></span><input type="text" maxlength="20" class="form-control input-sm" value="<?php echo $model->NombreDirectorZona; ?>" disabled="disabled"></div>                
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'eMailDirectorZona', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php echo $form->textField($model, 'eMailDirectorZona', array('class' => 'form-control input-sm', 'maxlength' => 100)); ?>
                <div class="has-error">
                    <div class="help-block">
                        <?php echo $form->error($model, 'eMailDirectorZona'); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'CedulaAuxiliarZona', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
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
                <div class="has-error">
                    <div class="help-block">
                        <?php echo $form->error($model, 'CedulaAuxiliarZona'); ?>
                    </div>
                </div>
            </div>
            <!--
            <div class="col-lg-4">
            <?php echo $model->NombreAuxiliarZona; ?>
            </div>
            -->

            <?php
            /*
              $this->widget('bootstrap.widgets.TbButton', array(
              'encodeLabel' => false,
              'label' => 'Ver nombre',
              'type' => 'link',
              'htmlOptions' => array('encode' => false, 'data-title' => 'Nombre', 'data-content' => $model->NombreAuxiliarZona, 'rel' => 'popover', 'data-placement' => 'left',),
              ));
             */
            ?>

        </div>

        <div class="form-group">
            <label class="col-lg-4 control-label">Nombre Auxiliar</label>            
            <div class="col-lg-7">
                <div class="input-prepend"><span class="add-on"><i class="icon-globe"></i></span><input type="text" maxlength="20" class="form-control input-sm" value="<?php echo $model->NombreAuxiliarZona; ?>" disabled="disabled"></div>                
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'IndicativoTelefonoZona', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php echo $form->textField($model, 'IndicativoTelefonoZona', array('class' => 'form-control input-sm', 'maxlength' => 45)); ?>
                <div class="has-error">
                    <div class="help-block">
                        <?php echo $form->error($model, 'IndicativoTelefonoZona'); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-lg-offset-5 col-lg-7">
                <?php
                echo CHtml::ajaxSubmitButton($model->isNewRecord ? 'Crear' : 'Actualizar', $model->isNewRecord ? Yii::app()->createUrl('/puntoventa/zonas/crear') : Yii::app()->createUrl('/puntoventa/zonas/actualizar'), array(
                    'beforeSend' => new CJavaScriptExpression("function(){Loading.show();}"),
                    'success' => new CJavaScriptExpression("function(data){
                obj = $.parseJSON(data);
                if(obj.result=='ok'){
                    $.fn.yiiGridView.update('zonas-grid');
                    $('#zona-form-modal').modal('hide');
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
            $('#zona-form-modal').modal('hide');
            bootbox.alert('Error, intente de nuevo');
        }")), array('id' => uniqid(), 'class' => 'btn btn-primary',));
                ?>
            </div>
        </div>

    </div>
</div>

<?php $this->endWidget(); ?>
