<?php //Yii::import('ext.select2.Select2');?>
<div class='row'>
    <div class='col-md-4'>
        <?php echo CHtml::label('Activo', 'SolicitudActivo_'.$n.'_IdActivo', array('class' => 'control-label text-primary')); ?>
        <div class='input-group-sm'>
            <?php echo CHtml::dropDownList('SolicitudActivo['.$n.'][IdActivo]', '', $listData, array('id' => 'SolicitudActivo_'.$n.'_IdActivo', 'class' => 'form-control', 'prompt' => 'Seleccione ...')) ?>
        </div>
        <div id='ActivosPuntoVenta_IdActivo_em_<?php echo $n?>' data-role="inputformerror" class='text-left text-danger' style='display:none'></div>
    </div>

    <div class='col-md-1'>
        <?php echo CHtml::label('Cantidad', 'SolicitudActivo_'.$n.'_Cantidad', array('class' => 'control-label text-primary')); ?>
        <div class='input-group-sm'>
            <?php echo CHtml::textField('SolicitudActivo['.$n.'][Cantidad]', '', array('id' => 'SolicitudActivo_'.$n.'_Cantidad', 'class' => 'form-control')) ?>
        </div>
        <div id='ActivosPuntoVenta_Cantidad_em_<?php echo $n?>' data-role="inputformerror" class='text-left text-danger' style='display:none'>adfdf</div>
    </div>

    <div class='col-md-7'>
        <?php echo CHtml::label('ObservacionSolicitante', 'SolicitudActivo_'.$n.'_ObservacionSolicitante', array('class' => 'control-label text-primary')); ?>
        <div class='input-group-sm'>
            <?php echo CHtml::textArea('SolicitudActivo['.$n.'][ObservacionSolicitante]', '', array('id' => 'SolicitudActivo_'.$n.'_ObservacionSolicitante', 'class' => 'form-control')) ?>
        </div>
        <div id='ActivosPuntoVenta_ObservacionSolicitante_em_<?php echo $n?>' data-role="inputformerror" class='text-left text-danger' style='display:none'></div>
    </div>
</div>