<?php echo CHtml::hiddenField("InfluenciasPuntoVenta[$nItem][IDPuntoDeVenta]", $model->IDPuntoDeVenta); ?>
<div class="form-group">
    <label class="col-lg-4 control-label" for="<?php echo "InfluenciasPuntoVenta_" . $nItem . "IDBarrio" ?>">
        Barrio
        <span class="required">*</span>
    </label>
    <div class="col-lg-7">
        <?php echo CHtml::dropDownList("InfluenciasPuntoVenta[$nItem][IDBarrio]", '', BarrioInfluencia::listData(), array('prompt' => 'Seleccione ...', 'class' => 'form-control input-sm')); ?>
                <div id="<?php echo "InfluenciasPuntoVenta_IDBarrio_em_$nItem" ?>" class="text-left text-danger" style="display:none"></div>
    </div>
</div>