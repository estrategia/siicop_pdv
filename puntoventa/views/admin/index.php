<?php

$this->pageTitle = Yii::app()->name . ' - Puntos de Venta - Admin';

$this->breadcrumbs = array(
    'Inicio' => array('/puntoventa'),
    'Admin'
);
?>

<div class="well" align="center">
    <?php echo CHtml::button('CEDI', array('class' => 'btn btn-primary btn-sm', 'submit' => array('/puntoventa/cedi'))); ?>
    <?php echo CHtml::button('Sectores', array('class' => 'btn btn-primary btn-sm', 'submit' => array('/puntoventa/sectores'))); ?>
    <?php echo CHtml::button('Sedes', array('class' => 'btn btn-primary btn-sm', 'submit' => array('/puntoventa/sedes'))); ?>
    <?php echo CHtml::button('Zonas', array('class' => 'btn btn-primary btn-sm', 'submit' => array('/puntoventa/zonas'))); ?>
    <?php echo CHtml::button('Tipo Negocio', array('class' => 'btn btn-primary btn-sm', 'submit' => array('/puntoventa/tipoNegocio'))); ?>
    <?php echo CHtml::button('Categor&iacute;as Servicios', array('encode'=>false, 'class' => 'btn btn-primary btn-sm', 'submit' => array('/puntoventa/categoriaTipoServicio'))); ?>
    <?php echo CHtml::button('Servicios', array('class' => 'btn btn-primary btn-sm', 'submit' => array('/puntoventa/tipoServicio'))); ?>
    <?php echo CHtml::button('Ubicaci&oacute;n Local', array('encode'=>false, 'class' => 'btn btn-primary btn-sm', 'submit' => array('/puntoventa/ubicacionLocal'))); ?>
    <?php echo CHtml::button('Horarios', array('class' => 'btn btn-primary btn-sm', 'submit' => array('/puntoventa/horarioPuntoVenta'))); ?>
</div>