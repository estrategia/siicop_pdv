<?php echo CHtml::hiddenField("ServiciosPuntoVenta[$nItem][IDPuntoDeVenta]", $model->IDPuntoDeVenta); ?>
<div class="form-group">
    <label class="col-lg-4 control-label" for="<?php echo "ServiciosPuntoVenta_" . $nItem . "_IDTipoServicio" ?>">
        Servicio
        <span class="required">*</span>
    </label>
    <div class="col-lg-7">
        <?php echo CHtml::dropDownList("ServiciosPuntoVenta[$nItem][IDTipoServicio]", '', TipoServicio::listData(), array('prompt' => 'Seleccione ...', 'class' => 'form-control input-sm')); ?>
                <div id="<?php echo "ServiciosPuntoVenta_IDTipoServicio_em_$nItem" ?>" class="text-left text-danger" style="display:none"></div>
    </div>
</div>