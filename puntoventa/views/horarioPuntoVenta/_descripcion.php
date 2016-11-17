<div class="clear"></div>
<?php if ($model === null): ?>
    <div class="panel panel-primary">
        <div class="panel-heading block">
            <div class="block">
                <div class="left">
                    <h3 class="panel-title"><?php echo ($horario == Yii::app()->controller->module->horarioApertura ? "Horario de apertura" : "Horario de domicilios") ?></h3>
                </div>
                <div class="right">
                    <?php echo ($button ? CHtml::button('Adicionar horario', array('id' => 'horario-add-button-' . $horario, 'class' => 'btn btn-default btn-sm')) : "") ?>
                </div>
            </div>
        </div>
        <div class="panel-body">

        </div>
    </div>
<?php else: ?>
    <div class="panel panel-primary">
        <div class="panel-heading block">
            <div class="block">
                <div class="left">
                    <h3 class="panel-title"><?php echo ($horario == Yii::app()->controller->module->horarioApertura ? "Horario de apertura" : "Horario de domicilios") ?></h3>
                </div>
                <div class="right">
                    <?php echo ($button ? CHtml::button('Cambiar horario', array('id' => 'horario-edit-button-' . $horario, 'class' => 'btn btn-default btn-sm')) : "") ?>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Horario</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="odd">
                        <td>Lunes a s&aacute;bado</td>
                        <td><?php echo $model->HorarioInicioLunesASabado ?></td>
                        <td><?php echo $model->HorarioFinLunesASabado ?></td>
                    </tr>
                    <tr class="even">
                        <td>Domingos</td>
                        <td><?php echo $model->HorarioInicioDomingos ?></td>
                        <td><?php echo $model->HorarioFinDomingos ?></td>
                    </tr>
                    <tr class="odd">
                        <td>Festivos</td>
                        <td><?php echo $model->HorarioInicioFestivos ?></td>
                        <td><?php echo $model->HorarioFinFestivos ?></td>
                    </tr>
                    <tr class="even">
                        <td>Especial</td>
                        <td><?php echo $model->HorarioInicioEspecial ?></td>
                        <td><?php echo $model->HorarioFinEspecial ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

