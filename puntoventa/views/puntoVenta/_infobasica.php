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
        'id' => 'puntoventa-basica-form',
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

        <!--
        <div class="form-group">
            <div class="col-lg-3 control-label">
                <div>
                    <p class="note">Campos con <span class="required">*</span> son obligatorios.</p>
                </div>
            </div>
        </div>
        -->

        <?php /* echo $form->errorSummary($model, null, null, array('class' => 'alert alert-dismissable alert-danger')); */ ?>

        <fieldset>
            <legend>Datos PDV</legend>
            <div class="row">
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'NombrePuntoDeVenta', array('class' => 'control-label text-primary')); ?>
                    <div class="input-group-sm">
                        <?php echo $form->textField($model, 'NombrePuntoDeVenta', array('class' => 'form-control input-sm', 'maxlength' => 100)); ?>

                    </div>
                    <?php echo $form->error($model, 'NombrePuntoDeVenta', array('class' => 'text-left text-danger')); ?>
                </div>

                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'NombreCortoPuntoDeVenta', array('class' => 'control-label text-primary')); ?>
                    <div class="input-group-sm">
                        <?php echo $form->textField($model, 'NombreCortoPuntoDeVenta', array('class' => 'form-control input-sm', 'maxlength' => 100)); ?>

                    </div>
                    <?php echo $form->error($model, 'NombreCortoPuntoDeVenta', array('class' => 'text-left text-danger')); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'CodigoCiudad', array('class' => 'control-label text-primary')); ?>
                    <div class="input-group-sm"  style="width: 50%;">
                        <?php
                        $this->widget('bootstrap.widgets.TbTypeAhead', array(
                            'model' => $model,
                            'attribute' => 'CodigoCiudad',
                            'minLength' => 2,
                            'source' => new CJavaScriptExpression("
                                function (query, process) {
                                    $.ajax({
                                        type: 'POST',
                                        dataType : 'json',
                                        url: '" . $this->createUrl("ciudad/ajax") . "',
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
                                    $('#PuntoVenta_NombreCiudad').val(map[item].label);
                                    return map[item].value;
                                }
                            "),
                            'htmlOptions' => array(
                                'prepend' => TbHtml::icon(null),
                                'autocomplete' => 'off',
                                'placeholder' => 'Digite nombre',
                                'class' => 'form-control input-sm',
                            ),
                        ));
                        ?>

                    </div>
                    <?php echo $form->error($model, 'CodigoCiudad', array('class' => 'text-left text-danger')); ?>
                </div>

                <div class="col-md-4">
                    <label class="control-label text-primary" for="PuntoVenta_NombreCiudad">
                        Nombre Ciudad
                    </label>
                    <div class="input-group-sm">
                        <input id="PuntoVenta_NombreCiudad" class="form-control" type="text" value="<?php echo $model->NombreCiudad ?>" name="PuntoVenta_NombreCiudad" readonly="readonly">

                    </div>
                </div>

                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'BarrioConIndicaciones', array('class' => 'control-label text-primary')); ?>
                    <div class="input-group-sm">
                        <?php echo $form->textField($model, 'BarrioConIndicaciones', array('class' => 'form-control input-sm', 'maxlength' => 60)); ?>

                    </div>
                    <?php echo $form->error($model, 'BarrioConIndicaciones', array('class' => 'text-left text-danger')); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'IDSectorLRV', array('class' => 'control-label text-primary')); ?>
                    <div class="input-group-sm"  style="width: 50%;">
                        <?php
                        $this->widget('bootstrap.widgets.TbTypeAhead', array(
                            'model' => $model,
                            'attribute' => 'IDSectorLRV',
                            'minLength' => 2,
                            'source' => new CJavaScriptExpression("
                                function (query, process) {
                                    $.ajax({
                                        type: 'POST',
                                        dataType : 'json',
                                        url: '" . $this->createUrl("sectorlrvAutocomplete") . "',
                                        data: {term: query, ciudad: $('#PuntoVenta_CodigoCiudad').val()},
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
                                    $('#PuntoVenta_NombreSectorLRV').val(map[item].label);
                                    return map[item].value;
                                }
                            "),
                            'htmlOptions' => array(
                                'prepend' => TbHtml::icon(null),
                                'autocomplete' => 'off',
                                'placeholder' => 'Digite nombre',
                                'class' => 'form-control input-sm',
                            ),
                        ));
                        ?>

                    </div>
                    <?php echo $form->error($model, 'IDSectorLRV', array('class' => 'text-left text-danger')); ?>
                </div>
                <div class="col-md-4">
                    <label class="control-label text-primary" for="PuntoVenta_NombreSectorLRV">
                        Nombre Sector LRV
                    </label>
                    <div class="input-group-sm">
                        <input id="PuntoVenta_NombreSectorLRV" class="form-control" type="text" value="<?php echo $model->NombreSectorLRV ?>" name="PuntoVenta_NombreSectorLRV" readonly="readonly">

                    </div>
                </div>
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'CSC', array('class' => 'control-label text-primary')); ?>
                    <div class="input-group-sm" style="width: 30%;">
                            <?php echo $form->dropDownList($model, 'CSC', array('1' => 'SI', '0' => 'NO'), array('class' => 'form-control input-sm')); ?>
                    </div>
                    <?php echo $form->error($model, 'CSC', array('class' => 'text-left text-danger')); ?>
                </div>
            </div>


            <div class="row">
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'DireccionPuntoDeVenta', array('class' => 'control-label text-primary')); ?>
                    <div class="input-group-sm">
                        <?php echo $form->textField($model, 'DireccionPuntoDeVenta', array('class' => 'form-control input-sm', 'maxlength' => 100)); ?>

                    </div>
                    <?php echo $form->error($model, 'DireccionPuntoDeVenta', array('class' => 'text-left text-danger')); ?>
                </div>
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'IDUbicacion', array('class' => 'control-label text-primary')); ?>
                    <div class="input-group-sm"  style="width: 50%;">
                        <?php echo $form->dropDownList($model, 'IDUbicacion', UbicacionLocal::listData(), array('prompt' => 'Seleccione ...', 'class' => 'form-control input-sm')); ?>

                    </div>
                    <?php echo $form->error($model, 'IDUbicacion', array('class' => 'text-left text-danger')); ?>
                </div>
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'EstratoPuntoDeVenta', array('class' => 'control-label text-primary')); ?>
                    <div class="input-group-sm" style="width: 25%;">
                        <?php echo $form->textField($model, 'EstratoPuntoDeVenta', array('class' => 'form-control input-sm')); ?>

                    </div>
                    <?php echo $form->error($model, 'EstratoPuntoDeVenta', array('class' => 'text-left text-danger')); ?>

                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'IDZona', array('class' => 'control-label text-primary')); ?>
                    <div class="input-group-sm"  style="width: 60%;">
                        <?php echo $form->dropDownList($model, 'IDZona', Zona::listData(), array('prompt' => 'Seleccione ...', 'class' => 'form-control input-sm')); ?>
                    </div>
                    <?php echo $form->error($model, 'IDZona', array('class' => 'text-left text-danger')); ?>

                </div>
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'IDSector', array('class' => 'control-label text-primary')); ?>
                    <div class="input-group-sm" style="width: 60%;">
                        <?php echo $form->dropDownList($model, 'IDSector', Sector::listData(), array('prompt' => 'Seleccione ...', 'class' => 'form-control input-sm')); ?>

                    </div>
                    <?php echo $form->error($model, 'IDSector', array('class' => 'text-left text-danger')); ?>
                </div>
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'IDCEDI', array('class' => 'control-label text-primary')); ?>
                    <div class="input-group-sm" style="width: 60%;">
                        <?php echo $form->dropDownList($model, 'IDCEDI', Cedi::listData(), array('prompt' => 'Seleccione ...', 'class' => 'form-control input-sm')); ?>

                    </div>
                    <?php echo $form->error($model, 'IDCEDI', array('class' => 'text-left text-danger')); ?>

                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'IDTipoNegocio', array('class' => 'control-label text-primary')); ?>
                    <div class="input-group-sm" style="width: 60%;">
                        <?php echo $form->dropDownList($model, 'IDTipoNegocio', TipoNegocio::listData(), array('prompt' => 'Seleccione ...', 'class' => 'form-control input-sm')); ?>
                    </div>
                    <?php echo $form->error($model, 'IDTipoNegocio', array('class' => 'text-left text-danger')); ?>
                </div>
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'CallCenter', array('class' => 'control-label text-primary')); ?>
                    <div class="input-group-sm" style="width: 25%;">
                        <?php echo $form->dropDownList($model, 'CallCenter', array('NO' => 'NO', 'SI' => 'SI'), array('class' => 'form-control input-sm')); ?>
                    </div>
                    <?php echo $form->error($model, 'CallCenter', array('class' => 'text-left text-danger')); ?>
                </div>

                <?php if (!$model->isNewRecord): ?>
                    <div class="col-md-4">
                        <?php echo $form->labelEx($model, 'Estado', array('class' => 'control-label text-primary')); ?>
                        <div class="input-group-sm" style="width: 30%;">
                            <?php echo $form->dropDownList($model, 'Estado', array('1' => 'Activo', '0' => 'Inactivo'), array('class' => 'form-control input-sm')); ?>
                        </div>
                        <?php echo $form->error($model, 'Estado', array('class' => 'text-left text-danger')); ?>
                    </div>
                <?php endif; ?>
            </div>
        </fieldset>
        <div class="space"></div>
        <fieldset>
            <legend>Datos Admon</legend>
            <div class="row">
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'eMailPuntoDeVenta', array('class' => 'control-label text-primary')); ?>
                    <div class="input-group input-group-sm"> 
                        <span class="input-group-addon">@</span>
                        <?php echo $form->textField($model, 'eMailPuntoDeVenta', array('class' => 'form-control input-sm', 'maxlength' => 100)); ?>
                    </div>
                    <?php echo $form->error($model, 'eMailPuntoDeVenta', array('class' => 'text-left text-danger')); ?>

                </div>
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'CedulaAdministrador', array('class' => 'control-label text-primary')); ?>
                    <div class="input-group input-group-sm" style="width: 50%;">
                        <?php
                        $this->widget('bootstrap.widgets.TbTypeAhead', array(
                            'model' => $model,
                            'attribute' => 'CedulaAdministrador',
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
                                    $('#PuntoVenta_NombreAdministrador').val(map[item].label);
                                    return map[item].value;
                                }
                            "),
                            'htmlOptions' => array(
                                'prepend' => TbHtml::icon(null),
                                'autocomplete' => 'off',
                                'placeholder' => 'Digite nombre',
                                'class' => 'form-control input-sm',
                            ),
                        ));
                        ?>
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                    </div>
                    <?php echo $form->error($model, 'CedulaAdministrador', array('class' => 'text-left text-danger')); ?>

                </div>
                <div class="col-md-4">
                    <label class="control-label text-primary" for="PuntoVenta_NombreAdministrador">
                        Nombre Administrador
                    </label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input id="PuntoVenta_NombreAdministrador" class="form-control input-sm" type="text" value="<?php echo $model->NombreAdministrador ?>" name="PuntoVenta_NombreAdministrador" readonly="readonly">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'CedulaSubAdministrador', array('class' => 'control-label text-primary')); ?>
                    <div class="input-group input-group-sm"  style="width: 50%;">
                        <?php
                        $this->widget('bootstrap.widgets.TbTypeAhead', array(
                            'model' => $model,
                            'attribute' => 'CedulaSubAdministrador',
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
                                    $('#PuntoVenta_NombreSubAdministrador').val(map[item].label);
                                    return map[item].value;
                                }
                            "),
                            'htmlOptions' => array(
                                'prepend' => TbHtml::icon(null),
                                'autocomplete' => 'off',
                                'placeholder' => 'Digite nombre',
                                'class' => 'form-control input-sm',
                            ),
                        ));
                        ?>
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                    </div>
                    <?php echo $form->error($model, 'CedulaSubAdministrador', array('class' => 'text-left text-danger')); ?>

                </div>
                <div class="col-md-4">
                    <label class="control-label text-primary" for="PuntoVenta_NombreSubAdministrador">
                        Nombre SubAdministrador
                    </label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input id="PuntoVenta_NombreSubAdministrador" class="form-control input-sm" type="text" value="<?php echo $model->NombreSubAdministrador ?>" name="PuntoVenta_NombreSubAdministrador" readonly="readonly">
                    </div>
                </div>
            </div>
        </fieldset>
        <div class="space"></div>
        <fieldset>
            <legend>Informaci&oacute;n Contable</legend>
            <div class="row">
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'IDComercial', array('class' => 'control-label text-primary')); ?>
                    <div class="input-group-sm" style="width: 30%;">
                        <?php echo $form->textField($model, 'IDComercial', array('class' => 'form-control input-sm', 'maxlength' => 10)); ?>

                    </div>
                    <?php echo $form->error($model, 'IDComercial', array('class' => 'text-left text-danger')); ?>
                </div>
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'IdCentroCostos', array('class' => 'control-label text-primary')); ?>
                    <div class="input-group-sm" style="width: 40%;">
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
                                    $('#PuntoVenta_NombreCentroCostos').val(map[item].label);
                                    return map[item].value;
                                }
                            "),
                            'htmlOptions' => array(
                                'prepend' => TbHtml::icon(null),
                                'autocomplete' => 'off',
                                'placeholder' => 'Digite nombre',
                                'class' => 'form-control input-sm',
                            ),
                        ));
                        ?>

                    </div>
                    <?php echo $form->error($model, 'IdCentroCostos', array('class' => 'text-left text-danger')); ?>
                </div>
                <div class="col-md-4">
                    <label class="control-label text-primary" for="PuntoVenta_NombreCentroCostos">
                        Nombre Centro Costo
                    </label>
                    <div class="input-group-sm">
                        <input id="PuntoVenta_NombreCentroCostos" class="form-control input-sm" type="text" value="<?php echo $model->NombreCentroCostos ?>" name="PuntoVenta_NombreCentroCostos" readonly="readonly">

                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'CodigoContable', array('class' => 'control-label text-primary')); ?>
                    <div class="input-group-sm"  style="width: 30%;">
                        <?php echo $form->textField($model, 'CodigoContable', array('class' => 'form-control input-sm')); ?>

                    </div>
                    <?php echo $form->error($model, 'CodigoContable', array('class' => 'text-left text-danger')); ?>
                </div>
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'Resoluciones', array('class' => 'control-label text-primary')); ?>
                    <div class="input-group-sm">
                        <?php echo $form->textField($model, 'Resoluciones', array('class' => 'form-control input-sm', 'maxlength' => 255)); ?>
                    </div>
                    <?php echo $form->error($model, 'Resoluciones', array('class' => 'text-left text-danger')); ?>
                </div>
            </div>
        </fieldset>

        <?php echo CHtml::hiddenField('info', Yii::app()->controller->module->infoBasica) ?>
        <input type="hidden" name="tab_siguiente" value="<?php echo ($tab + 1) ?>" />

    </div>
</div>

<?php if ($consulta): ?>
    <div class="row">
        <div class="footer">
            <ul class="pager">
                <li class="next" id="id_infobasica_siguiente"><a href="#" class="siguiente">Siguiente →</a></li>
            </ul>
        </div>
    </div>

    <?php
    Yii::app()->clientScript->registerScript('infobasica-tab', "
    $(document).on('click', '#id_infobasica_siguiente', function(e) {
        $('#puntoventa_tabs').tabs({ active: " . ($tab + 1) . "});
    });");
    ?>

    <?php
    Yii::app()->clientScript->registerScript('infobasica-disabled', "
    $('#puntoventa-basica-form input').attr('disabled', 'disabled');
    $('#puntoventa-basica-form select').attr('disabled', 'disabled');");
    ?>
<?php else: ?>
    <div class="row">
        <div class="footer">
            <ul class="pager">
                <li class="next"><?php echo CHtml::linkButton('Siguiente →', array('class' => 'siguiente')); ?></li>
            </ul>
        </div>
    </div>
<?php endif; ?>

<?php $this->endWidget(); ?>
<div class="clear"></div>


