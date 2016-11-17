<?php echo CHtml::hiddenField("CompetenciasPuntoVenta[$nItem][IDPuntoDeVenta]", $model->IDPuntoDeVenta); ?>
<div class="form-group">
    <label class="col-lg-4 control-label" for="<?php echo "CompetenciasPuntoVenta_" . $nItem . "IDCompetencia" ?>">
        Competencia
        <span class="required">*</span>
    </label>
    <div class="col-lg-7">
        <?php echo CHtml::dropDownList("CompetenciasPuntoVenta[$nItem][IDCompetencia]", '', Competencia::listData(), array('prompt' => 'Seleccione ...', 'class' => 'form-control input-sm')); ?>
                <div id="<?php echo "CompetenciasPuntoVenta_IDCompetencia_em_$nItem" ?>" class="text-left text-danger" style="display:none"></div>
    </div>
</div>