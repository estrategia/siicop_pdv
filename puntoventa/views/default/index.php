<?php
$this->pageTitle = Yii::app()->name . ' - Puntos de Venta';
?>

<div class="row-fluid-panel">
    <a class="well top-block" href="#">
        <span class="icon32 icon-color icon-basket"></span>
        <div>Puntos de Venta</div>
        <div><?php echo PuntoVenta::getCantidad(); ?></div>
        <span class="notification"><?php echo PuntoVenta::getCantidad(0); ?></span>
    </a>
    <a class="well top-block" href="#">
        <span class="icon32 icon-color icon-cart"></span>
        <div>Minimarkets</div>
        <div><?php echo PuntoVenta::getCantidadTipoNegocio(1); ?></div>
        <span class="notification green"><?php echo PuntoVenta::getCantidadTipoNegocio(1, 0); ?></span>
    </a>
    <a class="well top-block" href="#">
        <span class="icon32 icon-color icon-clock"></span>
        <div>24/7 Horas</div>
        <div><?php echo PuntoVenta::getCantidadTipoServicio(1); ?></div>
        <span class="notification yellow"><?php echo PuntoVenta::getCantidadTipoServicio(1, 0); ?></span>
    </a>
    <a class="well top-block" href="#">
        <span class="icon32 icon-color icon-globe"></span>
        <div>Ciudades</div>
        <div><?php echo PuntoVenta::getCantidadCiudad(); ?></div>
        <span class="notification red"><?php echo PuntoVenta::getCantidadCiudad(0); ?></span>
    </a>
</div>
<div class="clear"></div>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Introducci&oacute;n</h3>
    </div>
    <div class="panel-body">
        <h1>
            Administrador de Puntos de Venta
            <small>Privilegios de Administrador.</small>
        </h1>
        <p>
            Bienvenido
            <b>Camilo Torres</b>
            , su última fecha de Ingresó fué:
            <b>2014-03-19 07:12:04</b>
            al Sistma de Administración Central de los Puntos de Venta de Copservir LTDA. Aquí podrá crear, modificar o desactivar información, de los puntos de venta
        </p>
        <p>Adicionalmente le permitirá consultar información de los puntos de venta de Copservir: </p>
        <ul>
            <li>Servicios</li>
            <li>Horarios</li>
            <li>Datos de Contacto</li>
            <li>Personal</li>
            <li>Fotos</li>
            <li>Mapas de Localización</li>
            <li>Búsquedas</li>
        </ul>
    </div>
</div>
<div class="clear"></div>