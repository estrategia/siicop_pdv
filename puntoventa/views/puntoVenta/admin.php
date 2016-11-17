<?php
Yii::app()->clientScript->registerScript('search', "
$('#search-form').submit(function(){
    $('#advancedsearch-form').hide();
    $('#barrioinfluencia-form').hide();
	$('#punto-venta-influencia-grid').hide();
	$('#punto-venta-grid').show();
	
    $.fn.yiiGridView.update('punto-venta-grid', {
        data: $(this).serialize()
    });
    return false;
});

$('#advancedsearch-btn').click(function(){
    $('#barrioinfluencia-form').hide();
	$('#punto-venta-influencia-grid').hide();
	$('#punto-venta-grid').show();
	
    $('#advancedsearch-form').toggle();
        return false;
    });
    $('#advancedsearch-form form').submit(function(){
        $.fn.yiiGridView.update('punto-venta-grid', {
        data: $(this).serialize()
    });
    return false;
});

$('#punto-venta-influencia-grid').hide();

$('#barrioinfluencia-btn').click(function()
{
	$('#advancedsearch-form').hide();
    $('#barrioinfluencia-form').toggle();
    return false;
});

$('#barrioinfluencia-form form').submit(function()
{
	if($.trim($('#PuntoVenta_Ciudad').val()) != '' && $.trim($('#PuntoVenta_Barrio').val()) != '')
	{
		$.fn.yiiGridView.update('punto-venta-influencia-grid', {
			data: $(this).serialize(),
			complete: function(jqXHR, status) {
				if (status == 'success')
				{
					$('#punto-venta-grid').hide();
					$('#punto-venta-influencia-grid').show();
				}
			},
		});
	}
	else
	{
		$.fn.yiiGridView.update('punto-venta-grid', {
			data: $(this).serialize()
		});
	}
	
	$('#PuntoVenta_BusquedaZona').val(0);
	return false;
});
");
?>


<?php
$this->pageTitle = Yii::app()->name . ' - Puntos de Venta';

$this->breadcrumbs = array(
    'Inicio' => array('/puntoventa'),
    'Puntos de Venta'
);
?>

<div class="well" align="center">
    <?php echo CHtml::button('Crear Punto Venta', array('class' => 'btn btn-primary btn-sm', 'submit' => CController::createUrl('crear'))); ?>
    <?php echo CHtml::button('Exportar Excel', array('class' => 'btn btn-primary btn-sm', 'submit' => CController::createUrl('exportar', array('excel' => true)))); ?>
</div>

<?php
$this->renderPartial('_search', array(
    'model' => $model,
));
?>

<br/>

<?php echo CHtml::link('B&uacute;squeda avanzada', '#', array('class' => 'btn btn-primary', 'id' => 'advancedsearch-btn')); ?>
&nbsp;
<?php echo CHtml::link('B&uacute;squeda por Barrio de Influencia', '#', array('class' => 'btn btn-primary', 'id' => 'barrioinfluencia-btn')); ?>

<div id="advancedsearch-form" style="display:none">
    <?php $this->renderPartial('_searchAdvanced', array('model' => $model)); ?>
</div>

<div id="barrioinfluencia-form" style="display:none">
	<br />
    <?php $this->renderPartial('_searchInfluencia', array('model' => $model)); ?>
</div>

<?php
$options = array(
    "showAnim" => 'fold',
    "minLength" => '2',
    "select" => 'function(event, ui) { $(\'#CiudadAutoComplete\').val(ui.item.value);}',
    "source" => $this->createUrl("ciudad/ajax")
);
$options = "$('#CiudadAutoComplete').autocomplete(" . CJSON::encode($options) . ");";

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'punto-venta-grid',
    'beforeAjaxUpdate' => new CJavaScriptExpression("function() {Loading.show();}"),
    'afterAjaxUpdate' => new CJavaScriptExpression("function() {Loading.hide(); $options}"),
    'ajaxUpdateError' => new CJavaScriptExpression("function() {Loading.hide(); bootbox.alert('Error, intente de nuevo');}"),
    'dataProvider' => $model->search(),
    'ajaxUrl' => $this->createUrl('admin'),
    'filter' => $model,
    'columns' => array(
        'IDPuntoDeVenta',
        array(
            'type' => 'raw',
            'name' => 'IDComercial',
            'value' => '$data->IDComercial . ($data->Estado==1 ? \'<span title="Activo" alt="Activo" class="icon icon-blue icon-unlocked"></span>\' : \'<span title="Inactivo" alt="Inactivo" class="icon icon-red icon-locked"></span>\') '
        ),
        'NombrePuntoDeVenta',
        'NombreCortoPuntoDeVenta',
        'DireccionPuntoDeVenta',
        array(
            'name' => 'CodigoCiudad',
            'value' => '$data->NombreCiudad',
            'filter' => $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                'model' => $model,
                'attribute' => 'CodigoCiudad',
                'value'=>$model->CodigoCiudad,
                'source' => $this->createUrl("ciudad/ajax"),
                'options' => array(
                    'showAnim' => 'fold',
                    'minLength' => '2',
                    'select' => 'js:function(event, ui) {
                        $("#CiudadAutoComplete").val(ui.item.value);
                    }',
                ),
                'htmlOptions' => array('id' => 'CiudadAutoComplete')), true),
        ),
        'CallCenter',
        array(            
            'name' => 'CSC',
            'value' => '($data->CSC==1 ? \'Si\' : \'No\') ',
			'filter'=>array('1'=>'SI','0'=>'NO'),
        ),
        array(
            'class' => 'CButtonColumn',
            'template' => '{view} {delete} ',
            'buttons' => array(
                'view' => array(
                    'label' => 'Actualizar',
                    'url' => 'Yii::app()->createUrl("/puntoventa/puntoVenta/actualizar", array("id"=>$data->IDPuntoDeVenta))',
                ),
                'delete' => array(
                    //'options' => array('title' => Yii::t('app', 'Activar/Inactivar')),
                    //'label' => '<i class="icon icon-blue icon-edit"></i>',
                    //'imageUrl' => false,
                    'label' => 'Activar/Inactivar',
                    'url' => '$data->IDPuntoDeVenta',
                    'options' => array(
                        'confirm' => "¿Esta seguro que desea activar/inactivar este punto de venta?",
                        'ajax' => array(
                            'type' => 'POST',
                            'dataType' => 'json',
                            'async' => true,
                            'url' => Yii::app()->createUrl('/puntoventa/puntoVenta/activacion'),
                            'data' => array('puntoventa' => "js: $(this).attr('href')"),
                            'beforeSend' => new CJavaScriptExpression("function(){Loading.show();}"),
                            'success' => new CJavaScriptExpression("function(data){
                                if(data.result=='ok'){
                                    $.fn.yiiGridView.update('punto-venta-grid');
                                }else{
                                    Loading.hide();
                                    bootbox.alert(data.response);
                                }
                            }"),
                            'error' => new CJavaScriptExpression("function(data){
                                Loading.hide();
                                bootbox.alert('Error, intente de nuevo');
                            }")
                        )
                    )
                ),
            )
        ),
    ),
));

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'punto-venta-influencia-grid',
    'beforeAjaxUpdate' => new CJavaScriptExpression("function() {Loading.show();}"),
    'afterAjaxUpdate' => new CJavaScriptExpression("function() {Loading.hide(); }"),
    'ajaxUpdateError' => new CJavaScriptExpression("function() {Loading.hide(); bootbox.alert('Error, intente de nuevo');}"),
    'dataProvider' => $model->search(),
    'ajaxUrl' => $this->createUrl('admin'),
	'selectableRows' => 0,
    'columns' => array(
        array(
            'type' => 'raw',
            'name' => 'IDComercial',
			'value'	 => array($this, 'obtenerDatoGridZonaInfluencia'),
        ),
		array(
			'name'  => 'NombrePuntoDeVenta',
			'value' => '$data->NombrePuntoDeVenta',
		),
		array(
			'name'  => 'DireccionPuntoDeVenta',
			'value' => '$data->DireccionPuntoDeVenta',
		),
		array(
            'type' => 'raw',
			'name'  => 'Telefonos',
			'header' => 'Teléfonos',
			'value'	 => array($this, 'obtenerDatoGridZonaInfluencia'),
		),
		array(
            'type' => 'raw',
			'name'  => 'Servicios',
			'value'	 => array($this, 'obtenerDatoGridZonaInfluencia'),
			'htmlOptions' => array('style' => 'font-size: 11px;'),
		),
		array(
            'type' => 'raw',
			'name'  => 'Horarios',
			'value'	 => array($this, 'obtenerDatoGridZonaInfluencia'),
		),
    ),
));
?>