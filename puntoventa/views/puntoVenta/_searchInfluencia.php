<?php
$form = $this->beginWidget('CActiveForm', array(
    'htmlOptions' => array(
		'class' => 'form-horizontal',
		//'role' => 'form',
    ),
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
));
?>

<div class="form-group">
    <?php echo CHtml::label('Ciudad', 'PuntoVenta_Ciudad', array('class' => 'col-lg-2 ccontrol-label text-primary')); ?>
    <div class="col-lg-3">
		<?php
        $this->widget('bootstrap.widgets.TbTypeAhead', array(
            'name' => 'PuntoVenta_Ciudad',
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
						return map[item].value;
					}
				"),
            'htmlOptions' => array(
                'prepend' => TbHtml::icon(TbHtml::ICON_GLOBE),
                'autocomplete' => 'off',
                'placeholder' => 'Digite la Ciudad',
                'class' => 'form-control input-sm',
            ),
        ));
        ?>
    </div>
    <?php echo CHtml::label('Barrio', 'PuntoVenta_Barrio', array('class' => 'col-lg-2 ccontrol-label text-primary')); ?>
    <div class="col-lg-3">
        <?php echo CHtml::textField('PuntoVenta_Barrio', '', array('class' => 'form-control input-sm')); ?>
    </div>
	<?php echo CHtml::hiddenField('PuntoVenta_BusquedaZona', 0, array('id' => 'PuntoVenta_BusquedaZona')); ?>
</div>

<?php
echo TbHtml::formActions(array(
    TbHtml::submitButton('B&uacute;scar', array('color' => TbHtml::BUTTON_COLOR_PRIMARY, 'onclick' => new CJavaScriptExpression("$('#PuntoVenta_BusquedaZona').val(1); $('#barrioinfluencia-form').toggle();"))),
    TbHtml::resetButton('Reset', array('class' => 'btn btn-default')),
), array('class'=>'col-lg-offset-5 col-lg-7'));
?>

<?php $this->endWidget(); ?>


