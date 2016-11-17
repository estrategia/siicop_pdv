<?php if (!$consulta): ?>
    <?php
    Yii::app()->clientScript->registerScript('horarios-admin', "
        function editarHorario(puntoventa,horario){
            $.ajax({
                type: 'POST',
                dataType : 'json',
                async: true,
                url: '" . Yii::app()->createUrl('/puntoventa/horarioPuntoVenta/actualizarPuntoVenta') . "',
                data: {horario: horario, puntoventa: puntoventa},
                beforeSend: function(){Loading.show();},
                success: function(data){
                    if(data.result=='ok'){
                        $('#horarios-form-modal .modal-body').html(data.response.form);
                        $('#horarios-form-modal .modal-header h4').html('Cambiar horario');
                        $('#horarios-form-modal').modal({backdrop: 'static', show: true});
                        Loading.hide();
                    }else{
                        Loading.hide();
                        bootbox.alert(data.response);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown ){
                    Loading.hide();
                    bootbox.alert('Error: ' + errorThrown);
                }
            });
            return false;
        }", CClientScript::POS_END);
    ?>

    <div class="modal" id="horarios-form-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="space"></div>
<div class="grid-view">
    <table class="items">
        <thead>
            <tr>
                <th>Horario</th>
                <th>Inicio (24hrs)</th>
                <th>Fin (24hrs)</th>
                <?php if (!$consulta): ?><th class="button-column">&nbsp;</th><?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <tr class="odd">
                <td><strong>Apertura Lunes a Sabado</strong></td>
                <td class="text-center"><?php echo ($model->HorarioAperturaLunesASabado == null ? "Sin Horario" : $model->horarioAperturaLunesASabado->HorarioInicio) ?></td>
                <td class="text-center"><?php echo ($model->HorarioAperturaLunesASabado == null ? "Sin Horario" : $model->horarioAperturaLunesASabado->HorarioFin) ?></td>
                <?php if (!$consulta): ?><td><?php echo CHtml::button('Actualizar', array('id' => uniqid(), 'onClick' => "js:editarHorario($model->IDPuntoDeVenta," . Yii::app()->controller->module->horarios['HorarioAperturaLunesASabado'] . ");", 'class' => 'btn btn-primary btn-xs')) ?></td><?php endif; ?>
            </tr>
            <tr class="even">
                <td><strong>Apertura Domingo</strong></td>
                <td class="text-center"><?php echo ($model->HorarioAperturaDomingo == null ? "Sin Horario" : $model->horarioAperturaDomingo->HorarioInicio) ?></td>
                <td class="text-center"><?php echo ($model->HorarioAperturaDomingo == null ? "Sin Horario" : $model->horarioAperturaDomingo->HorarioFin) ?></td>
                <?php if (!$consulta): ?><td><?php echo CHtml::button('Actualizar', array('id' => uniqid(), 'onClick' => "js:editarHorario($model->IDPuntoDeVenta," . Yii::app()->controller->module->horarios['HorarioAperturaDomingo'] . ");", 'class' => 'btn btn-primary btn-xs')) ?></td><?php endif; ?>
            </tr>
            <tr class="odd">
                <td><strong>Apertura Festivo</strong></td>
                <td class="text-center"><?php echo ($model->HorarioAperturaFestivo == null ? "Sin Horario" : $model->horarioAperturaFestivo->HorarioInicio) ?></td>
                <td class="text-center"><?php echo ($model->HorarioAperturaFestivo == null ? "Sin Horario" : $model->horarioAperturaFestivo->HorarioFin) ?></td>
                <?php if (!$consulta): ?><td><?php echo CHtml::button('Actualizar', array('id' => uniqid(), 'onClick' => "js:editarHorario($model->IDPuntoDeVenta," . Yii::app()->controller->module->horarios['HorarioAperturaFestivo'] . ");", 'class' => 'btn btn-primary btn-xs')) ?></td><?php endif; ?>
            </tr>
            <tr class="even">
                <td><strong>Apertura Especial</strong></td>
                <td class="text-center"><?php echo ($model->HorarioAperturaEspecial == null ? "Sin Horario" : $model->horarioAperturaEspecial->HorarioInicio) ?></td>
                <td class="text-center"><?php echo ($model->HorarioAperturaEspecial == null ? "Sin Horario" : $model->horarioAperturaEspecial->HorarioFin) ?></td>
                <?php if (!$consulta): ?><td><?php echo CHtml::button('Actualizar', array('id' => uniqid(), 'onClick' => "js:editarHorario($model->IDPuntoDeVenta," . Yii::app()->controller->module->horarios['HorarioAperturaEspecial'] . ");", 'class' => 'btn btn-primary btn-xs')) ?></td><?php endif; ?>
            </tr>
            <tr class="odd">
                <td><strong>Domicilio Lunes a Sabado</strong></td>
                <td class="text-center"><?php echo ($model->HorarioDomicilioLunesASabado == null ? "Sin Horario" : $model->horarioDomicilioLunesASabado->HorarioInicio) ?></td>
                <td class="text-center"><?php echo ($model->HorarioDomicilioLunesASabado == null ? "Sin Horario" : $model->horarioDomicilioLunesASabado->HorarioFin) ?></td>
                <?php if (!$consulta): ?><td><?php echo CHtml::button('Actualizar', array('id' => uniqid(), 'onClick' => "js:editarHorario($model->IDPuntoDeVenta," . Yii::app()->controller->module->horarios['HorarioDomicilioLunesASabado'] . ");", 'class' => 'btn btn-primary btn-xs')) ?></td><?php endif; ?>
            </tr>
            <tr class="even">
                <td><strong>Domicilio Domingo</strong></td>
                <td class="text-center"><?php echo ($model->HorarioDomicilioDomingo == null ? "Sin Horario" : $model->horarioDomicilioDomingo->HorarioInicio) ?></td>
                <td class="text-center"><?php echo ($model->HorarioDomicilioDomingo == null ? "Sin Horario" : $model->horarioDomicilioDomingo->HorarioFin) ?></td>
                <?php if (!$consulta): ?><td><?php echo CHtml::button('Actualizar', array('id' => uniqid(), 'onClick' => "js:editarHorario($model->IDPuntoDeVenta," . Yii::app()->controller->module->horarios['HorarioDomicilioDomingo'] . ");", 'class' => 'btn btn-primary btn-xs')) ?></td><?php endif; ?>
            </tr>
            <tr class="odd">
                <td><strong>Domicilio Festivo</strong></td>
                <td class="text-center"><?php echo ($model->HorarioDomicilioFestivo == null ? "Sin Horario" : $model->horarioDomicilioFestivo->HorarioInicio) ?></td>
                <td class="text-center"><?php echo ($model->HorarioDomicilioFestivo == null ? "Sin Horario" : $model->horarioDomicilioFestivo->HorarioFin) ?></td>
                <?php if (!$consulta): ?><td><?php echo CHtml::button('Actualizar', array('id' => uniqid(), 'onClick' => "js:editarHorario($model->IDPuntoDeVenta," . Yii::app()->controller->module->horarios['HorarioDomicilioFestivo'] . ");", 'class' => 'btn btn-primary btn-xs')) ?></td><?php endif; ?>
            </tr>
            <tr class="even">
                <td><strong>Domicilio Especial</strong></td>
                <td class="text-center"><?php echo ($model->HorarioDomicilioEspecial == null ? "Sin Horario" : $model->horarioDomicilioEspecial->HorarioInicio) ?></td>
                <td class="text-center"><?php echo ($model->HorarioDomicilioEspecial == null ? "Sin Horario" : $model->horarioDomicilioEspecial->HorarioFin) ?></td>
                <?php if (!$consulta): ?><td><?php echo CHtml::button('Actualizar', array('id' => uniqid(), 'onClick' => "js:editarHorario($model->IDPuntoDeVenta," . Yii::app()->controller->module->horarios['HorarioDomicilioEspecial'] . ");", 'class' => 'btn btn-primary btn-xs')) ?></td><?php endif; ?>
            </tr>
        </tbody>
    </table>
</div>

<?php if (!empty($model->getHorariosEspecialesDia())): ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title text-center">Horarios especiales d&iacute;as espec&iacute;ficos</h3>
        </div>
        <div class="panel-body">
            <div class="grid-view">
                <table class="items">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Inicio (24hrs)</th>
                            <th>Fin (24hrs)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($model->getHorariosEspecialesDia() as $idx => $objHorarioDia): ?>
                            <tr class="<?php echo ($idx % 2 == 0) ? "odd" : "even" ?>">
                                <td><?php echo $objHorarioDia->Fecha ?></td>
                                <?php if ($objHorarioDia->Es24Horas == "SI"): ?>
                                    <td class="text-center">24 Horas</td>
                                    <td class="text-center">24 Horas</td>
                                <?php else: ?>
                                    <td class="text-center"><?php echo $objHorarioDia->HoraInicio ?></td>
                                    <td class="text-center"><?php echo $objHorarioDia->HoraFin ?></td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (!empty($model->getHorariosEspecialesRango())): ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title text-center">Horarios especiales rango de fecha</h3>
        </div>
        <div class="panel-body">
            <div class="grid-view">
                <table class="items">
                    <thead>
                        <tr>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Inicio Lunes a Sabado</th>
                            <th>Fin Lunes a Sabado</th>
                            <th>Inicio Domingo</th>
                            <th>Fin Domingo</th>                    
                            <th>Inicio Festivo</th>
                            <th>Fin Festivo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($model->getHorariosEspecialesRango() as $idx => $objHorarioRango): ?>
                            <tr class="<?php echo ($idx % 2 == 0) ? "odd" : "even" ?>">
                                <td><?php echo $objHorarioRango->FechaInicio ?></td>
                                <td><?php echo $objHorarioRango->FechaFin ?></td>

                                <?php if ($objHorarioRango->Es24HorasLunesASabado == "SI"): ?>
                                    <td class="text-center">24 Horas</td>
                                    <td class="text-center">24 Horas</td>
                                <?php else: ?>
                                    <td class="text-center"><?php echo $objHorarioRango->HoraInicioLunesASabado ?></td>
                                    <td class="text-center"><?php echo $objHorarioRango->HoraFinLunesASabado ?></td>
                                <?php endif; ?>

                                <?php if ($objHorarioRango->Es24HorasDomingo == "SI"): ?>
                                    <td class="text-center">24 Horas</td>
                                    <td class="text-center">24 Horas</td>
                                <?php else: ?>
                                    <td class="text-center"><?php echo $objHorarioRango->HoraInicioDomingo ?></td>
                                    <td class="text-center"><?php echo $objHorarioRango->HoraFinDomingo ?></td>
                                <?php endif; ?>

                                <?php if ($objHorarioRango->Es24HorasFestivo == "SI"): ?>
                                    <td class="text-center">24 Horas</td>
                                    <td class="text-center">24 Horas</td>
                                <?php else: ?>
                                    <td class="text-center"><?php echo $objHorarioRango->HoraInicioFestivo ?></td>
                                    <td class="text-center"><?php echo $objHorarioRango->HoraFinFestivo ?></td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="row">
    <div class="footer">
        <ul class="pager">
            <li class="previous" id="id_horarios_anterior"><a href="#" class="anterior">← Anterior</a></li>
            <li class="next" id="id_horarios_siguiente"><a href="#" class="siguiente">Siguiente →</a></li>
        </ul>
    </div>
</div>

<?php
Yii::app()->clientScript->registerScript('horarios-tab', "
$(document).on('click', '#id_horarios_anterior', function(e) {
    $('#puntoventa_tabs').tabs({ active: " . ($tab - 1) . "});
});
$(document).on('click', '#id_horarios_siguiente', function(e) {
    $('#puntoventa_tabs').tabs({ active: " . ($tab + 1) . "});
});");
?>
