<?php
$form = $this->beginWidget('CActiveForm', array(
    'htmlOptions' => array('class' => 'form-horizontal',
    //'role' => 'form',
    ),
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
        ));
?>

<div class="form-group">
    <?php echo $form->labelEx($model, 'IDPuntoDeVenta', array('class' => 'col-lg-2 ccontrol-label text-primary')); ?>
    <div class="col-lg-2">
        <?php echo $form->textField($model, 'IDPuntoDeVenta', array('class' => 'form-control input-sm',)); ?>
    </div>
    <?php echo $form->labelEx($model, 'IDSede', array('class' => 'col-lg-2 ccontrol-label text-primary')); ?>
    <div class="col-lg-2">
        <?php echo $form->dropDownList($model, 'IDSede', Sede::listData(), array('prompt' => 'Seleccione ...', 'class' => 'form-control input-sm')); ?>
    </div>
    <?php echo $form->labelEx($model, 'IDZona', array('class' => 'col-lg-2 ccontrol-label text-primary')); ?>
    <div class="col-lg-2">
        <?php echo $form->dropDownList($model, 'IDZona', Zona::listData(), array('prompt' => 'Seleccione ...', 'class' => 'form-control input-sm')); ?>
    </div>
</div>


<div class="form-group">
    <?php echo $form->labelEx($model, 'IDCEDI', array('class' => 'col-lg-2 ccontrol-label text-primary')); ?>
    <div class="col-lg-2">
        <?php echo $form->dropDownList($model, 'IDCEDI', Cedi::listData(), array('prompt' => 'Seleccione ...', 'class' => 'form-control input-sm')); ?>
    </div>
    <?php echo $form->labelEx($model, 'IDSector', array('class' => 'col-lg-2 ccontrol-label text-primary')); ?>
    <div class="col-lg-2">
        <?php echo $form->dropDownList($model, 'IDSector', Sector::listData(), array('prompt' => 'Seleccione ...', 'class' => 'form-control input-sm')); ?>
    </div>
        <?php echo $form->labelEx($model, 'IDTipoNegocio', array('class' => 'col-lg-2 ccontrol-label text-primary')); ?>
    <div class="col-lg-2">
        <?php echo $form->dropDownList($model, 'IDTipoNegocio', TipoNegocio::listData(), array('prompt' => 'Seleccione ...', 'class' => 'form-control input-sm')); ?>
    </div>
</div>


<div class="form-group">
    <?php echo $form->labelEx($model, 'IDComercial', array('class' => 'col-lg-2 ccontrol-label text-primary')); ?>
    <div class="col-lg-2">
        <?php echo $form->textField($model, 'IDComercial', array('class' => 'form-control input-sm',)); ?>
    </div>
    <?php echo $form->labelEx($model, 'CodigoContable', array('class' => 'col-lg-2 ccontrol-label text-primary')); ?>
    <div class="col-lg-2">
        <?php echo $form->textField($model, 'CodigoContable', array('class' => 'form-control input-sm', 'maxlength' => 10)); ?>
    </div>
        <?php echo $form->labelEx($model, 'NombrePuntoDeVenta', array('class' => 'col-lg-2 ccontrol-label text-primary')); ?>
    <div class="col-lg-2">
        <?php echo $form->textField($model, 'NombrePuntoDeVenta', array('class' => 'form-control input-sm', 'maxlength' => 100)); ?>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'NombreCortoPuntoDeVenta', array('class' => 'col-lg-2 ccontrol-label text-primary')); ?>
    <div class="col-lg-2">
        <?php echo $form->textField($model, 'NombreCortoPuntoDeVenta', array('class' => 'form-control input-sm', 'maxlength' => 100)); ?>
        <div class="help-block">
            <?php echo $form->error($model, 'NombreCortoPuntoDeVenta'); ?>
        </div>
    </div>
    <?php echo $form->labelEx($model, 'DireccionPuntoDeVenta', array('class' => 'col-lg-2 ccontrol-label text-primary')); ?>
    <div class="col-lg-2">
        <?php echo $form->textField($model, 'DireccionPuntoDeVenta', array('class' => 'form-control input-sm', 'maxlength' => 100)); ?>
        <div class="help-block">
            <?php echo $form->error($model, 'DireccionPuntoDeVenta'); ?>
        </div>
    </div>
    <?php echo $form->labelEx($model, 'BarrioConIndicaciones', array('class' => 'col-lg-2 ccontrol-label text-primary')); ?>
    <div class="col-lg-2">
        <?php echo $form->textField($model, 'BarrioConIndicaciones', array('class' => 'form-control input-sm', 'maxlength' => 60)); ?>
        <div class="help-block">
            <?php echo $form->error($model, 'BarrioConIndicaciones'); ?>
        </div>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'UbicacionLocal', array('class' => 'col-lg-2 ccontrol-label text-primary')); ?>
    <div class="col-lg-2">
        <?php echo $form->dropDownList($model, 'IDUbicacion', UbicacionLocal::listData(), array('prompt' => 'Seleccione ...', 'class' => 'form-control input-sm', 'maxlength' => 45)); ?>
    </div>
    <?php echo $form->labelEx($model, 'CodigoCiudad', array('class' => 'col-lg-2 ccontrol-label text-primary')); ?>
    <div class="col-lg-2">
        <?php echo $form->dropDownList($model, 'CodigoCiudad', Ciudad::listData(), array('prompt' => 'Seleccione ...', 'class' => 'form-control input-sm')); ?>
    </div>
    <?php echo $form->labelEx($model, 'Estado', array('class' => 'col-lg-2 ccontrol-label text-primary')); ?>
    <div class="col-lg-2">
        <?php echo $form->dropDownList($model, 'Estado', array('1' => 'Activo', '0' => 'Inactivo'), array('prompt' => 'Seleccione ...', 'class' => 'form-control input-sm')); ?>
    </div>
</div>


<div class="form-group">
    <?php echo $form->labelEx($model, 'FechaCreacionRegistro', array('class' => 'col-lg-2 ccontrol-label text-primary')); ?>
    <div class="col-lg-2">
        <?php echo $form->textField($model, 'FechaCreacionRegistro', array('class' => 'form-control input-sm')); ?>
    </div>
    <?php echo $form->labelEx($model, 'LatitudGoogle', array('class' => 'col-lg-2 ccontrol-label text-primary')); ?>
    <div class="col-lg-2">
        <?php echo $form->textField($model, 'LatitudGoogle', array('class' => 'form-control input-sm')); ?>
    </div>
    <?php echo $form->labelEx($model, 'LongitudGoogle', array('class' => 'col-lg-2 ccontrol-label text-primary')); ?>
    <div class="col-lg-2">
        <?php echo $form->textField($model, 'LongitudGoogle', array('class' => 'form-control input-sm')); ?>
    </div>
</div>


<div class="form-group">
    <?php echo $form->labelEx($model, 'eMailPuntoDeVenta', array('class' => 'col-lg-2 ccontrol-label text-primary')); ?>
    <div class="col-lg-2">
        <?php echo $form->textField($model, 'eMailPuntoDeVenta', array('class' => 'form-control input-sm', 'maxlength' => 100)); ?>
    </div>
    <?php echo $form->labelEx($model, 'EstratoPuntoDeVenta', array('class' => 'col-lg-2 ccontrol-label text-primary')); ?>
    <div class="col-lg-2">
        <?php echo $form->textField($model, 'EstratoPuntoDeVenta', array('class' => 'form-control input-sm')); ?>
    </div>
    <?php echo $form->labelEx($model, 'CedulaAdministrador', array('class' => 'col-lg-2 ccontrol-label text-primary')); ?>
    <div class="col-lg-2">
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
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'CedulaSubAdministrador', array('class' => 'col-lg-2 ccontrol-label text-primary')); ?>
    <div class="col-lg-2">
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
    <?php echo $form->labelEx($model, 'IPCamara', array('class' => 'col-lg-2 ccontrol-label text-primary')); ?>
    <div class="col-lg-2">
        <?php echo $form->textField($model, 'IPCamara', array('class' => 'form-control input-sm', 'maxlength' => 20)); ?>
    </div>
    <?php echo $form->labelEx($model, 'DireccionIPServidor', array('class' => 'col-lg-2 ccontrol-label text-primary')); ?>
    <div class="col-lg-2">
        <?php echo $form->textField($model, 'DireccionIPServidor', array('class' => 'form-control input-sm', 'maxlength' => 20)); ?>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'RutaImagenMapa', array('class' => 'col-lg-2 ccontrol-label text-primary')); ?>
    <div class="col-lg-2">
        <?php echo $form->textField($model, 'RutaImagenMapa', array('class' => 'form-control input-sm', 'maxlength' => 100)); ?>
    </div>
    <?php echo $form->labelEx($model, 'DimensionFondo', array('class' => 'col-lg-2 ccontrol-label text-primary')); ?>
    <div class="col-lg-2">
        <?php echo $form->textField($model, 'DimensionFondo', array('class' => 'form-control input-sm')); ?>
    </div>
    <?php echo $form->labelEx($model, 'DimensionAncho', array('class' => 'col-lg-2 ccontrol-label text-primary')); ?>
    <div class="col-lg-2">
        <?php echo $form->textField($model, 'DimensionAncho', array('class' => 'form-control input-sm')); ?>
    </div>
</div>

<div class="form-group">
    <?php echo $form->labelEx($model, 'AreaLocal', array('class' => 'col-lg-2 ccontrol-label text-primary')); ?>
    <div class="col-lg-2">
        <?php echo $form->textField($model, 'AreaLocal', array('class' => 'form-control input-sm')); ?>
    </div>
    <?php echo $form->labelEx($model, 'Resoluciones', array('class' => 'col-lg-2 ccontrol-label text-primary')); ?>
    <div class="col-lg-2">
        <?php echo $form->textField($model, 'Resoluciones', array('class' => 'form-control input-sm', 'maxlength' => 255)); ?>
    </div>
    <?php echo $form->labelEx($model, 'DireccionGoogle', array('class' => 'col-lg-2 ccontrol-label text-primary')); ?>
    <div class="col-lg-2">
        <?php echo $form->textField($model, 'DireccionGoogle', array('class' => 'form-control input-sm', 'maxlength' => 255)); ?>
    </div>
</div>
<div class="form-group">
	<?php echo $form->labelEx($model, 'CSC', array('class' => 'col-lg-2 ccontrol-label text-primary')); ?>

    <div class="col-lg-2">

        <?php echo $form->dropDownList($model, 'CSC', array('1' => 'SI', '0' => 'NO'), array('prompt' => 'Seleccione ...', 'class' => 'form-control input-sm')); ?>

    </div>
</div>

<?php
echo TbHtml::formActions(array(
    TbHtml::submitButton('B&uacute;scar', array('color' => TbHtml::BUTTON_COLOR_PRIMARY, 'onclick' => new CJavaScriptExpression("$('#advancedsearch-form').toggle();"))),
    TbHtml::resetButton('Reset', array('class' => 'btn btn-default')),
), array('class'=>'col-lg-offset-5 col-lg-7'));
?>

<?php $this->endWidget(); ?>


