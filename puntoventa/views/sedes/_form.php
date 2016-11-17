<?php
$form = $this->beginWidget('CActiveForm', array(
    'enableClientValidation' => true,
    'htmlOptions' => array(
        'class' => 'form-horizontal',
        'role' => 'form',
        'id' => 'sedes-form'
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
            <?php echo $form->hiddenField($model, 'IDSede'); ?>
        <?php endif; ?>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'CodigoSede', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php echo $form->textField($model, 'CodigoSede', array('class' => 'form-control input-sm')); ?>
                <?php echo $form->error($model, 'CodigoSede', array('class' => 'text-left text-danger')); ?>

            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'NombreSede', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php echo $form->textField($model, 'NombreSede', array('class' => 'form-control input-sm', 'maxlength' => 45)); ?>
                <?php echo $form->error($model, 'NombreSede', array('class' => 'text-left text-danger')); ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'CedulaGerenteOperativo', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php
                $this->widget('bootstrap.widgets.TbTypeAhead', array(
                    'model' => $model,
                    'attribute' => 'CedulaGerenteOperativo',
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
                <?php echo $form->error($model, 'CedulaGerenteOperativo', array('class' => 'text-left text-danger')); ?>
            </div>
        </div>
        <!--
        <div class="col-lg-4">
        <?php echo $model->NombreGerenteOperativo; ?>
        </div>
        -->

        <?php
        /*
          $this->widget('bootstrap.widgets.TbButton', array(
          'encodeLabel' => false,
          'label' => 'Ver nombre',
          'type' => 'link',
          'htmlOptions' => array('encode' => false, 'data-title' => 'Nombre', 'data-content' => $model->NombreGerenteOperativo, 'rel' => 'popover', 'data-placement' => 'left',),
          ));
         */
        ?>

    </div>

    <div class="form-group">
        <label class="col-lg-4 control-label">Nombre Gerente Operativo</label>            
        <div class="col-lg-7">
            <div class="input-prepend"><span class="add-on"><i class="icon-globe"></i></span><input type="text" maxlength="20" class="form-control input-sm" value="<?php echo $model->NombreGerenteOperativo; ?>" disabled="disabled"></div>                
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'CelularSede', array('class' => 'col-lg-4 control-label text-primary')); ?>
        <div class="col-lg-7">
            <?php echo $form->textField($model, 'CelularSede', array('class' => 'form-control input-sm', 'maxlength' => 45)); ?>
            <?php echo $form->error($model, 'CelularSede', array('class' => 'text-left text-danger')); ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'DireccionSede', array('class' => 'col-lg-4 control-label text-primary')); ?>
        <div class="col-lg-7">
            <?php echo $form->textField($model, 'DireccionSede', array('class' => 'form-control input-sm', 'maxlength' => 100)); ?>
            <?php echo $form->error($model, 'DireccionSede', array('class' => 'text-left text-danger')); ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'TelefonoSede', array('class' => 'col-lg-4 control-label text-primary')); ?>
        <div class="col-lg-7">
            <?php echo $form->textField($model, 'TelefonoSede', array('class' => 'form-control input-sm', 'maxlength' => 45)); ?>
            <?php echo $form->error($model, 'TelefonoSede', array('class' => 'text-left text-danger')); ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'IndicativoTelefonoSede', array('class' => 'col-lg-4 control-label text-primary')); ?>
        <div class="col-lg-7">
            <?php echo $form->textField($model, 'IndicativoTelefonoSede', array('class' => 'form-control input-sm')); ?>
            <?php echo $form->error($model, 'IndicativoTelefonoSede', array('class' => 'text-left text-danger')); ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-lg-offset-5 col-lg-7">
            <?php
            echo CHtml::ajaxSubmitButton($model->isNewRecord ? 'Crear' : 'Actualizar', $model->isNewRecord ? Yii::app()->createUrl('/puntoventa/sedes/crear') : Yii::app()->createUrl('/puntoventa/sedes/actualizar'), array(
                'beforeSend' => new CJavaScriptExpression("function(){Loading.show();}"),
                'success' => new CJavaScriptExpression("function(data){
                obj = $.parseJSON(data);
                if(obj.result=='ok'){
                    $.fn.yiiGridView.update('sedes-grid');
                    $('#sede-form-modal').modal('hide');
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
                $('#sede-form-modal').modal('hide');
                bootbox.alert('Error, intente de nuevo');
        }")), array('id' => uniqid(), 'class' => 'btn btn-primary',));
            ?>
        </div>
    </div>

</div>
</div>

<?php $this->endWidget(); ?>
