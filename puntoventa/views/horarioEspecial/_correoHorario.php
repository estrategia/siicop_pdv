<html>
    <head>
        <title>Registro Horario Especial</title>
    </head>
    <body>
        <div style="width: 90%; margin: 0 auto; padding: 10px; border: 1px solid #000000;">
            <div style="background-color:#088A08;">
                <p style="color:#FFFFFF; text-align:center; font-weight: bold;"><?php echo CHtml::encode(Yii::app()->name) . "<br/>Notificaci&oacute;n de Horario Especial" ?></p>
            </div>

            <?php echo $plantilla; ?>

            <?php if ($model instanceof HorarioEspecialDia): ?>
                <table align="center" border="1">
                    <tr>
                        <td style="text-align:center; font-weight: bold;">Fecha</td>
                        <td style="text-align:center; font-weight: bold;">Horario</td>
                    </tr>
                    <tr>
                        <td><?php echo $model->Fecha ?></td>

                        <?php if ($model->Es24Horas == "SI"): ?>
                            <td class="text-center">24 Horas</td>
                        <?php else: ?>
                            <td class="text-center"><?php echo $model->HoraInicio ?> - <?php echo $model->HoraFin ?> </td>
                        <?php endif; ?>
                    </tr>
                </table>
            <?php elseif ($model instanceof HorarioEspecialRango): ?>
                <table align="center" border="1">
                    <tr>
                        <td style="text-align:center; font-weight: bold;">Fecha Inicio</td>
                        <td style="text-align:center; font-weight: bold;">Fecha Fin</td>
                        <td style="text-align:center; font-weight: bold;">Lunes a Sabado</td>
                        <td style="text-align:center; font-weight: bold;">Domingo</td>
                        <td style="text-align:center; font-weight: bold;">Festivo</td>
                    </tr>
                    <tr>
                        <td><?php echo $model->FechaInicio ?></td>
                        <td><?php echo $model->FechaFin ?></td>

                        <?php if ($model->Es24HorasLunesASabado == "SI"): ?>
                            <td class="text-center">24 Horas</td>
                        <?php else: ?>
                            <td class="text-center"><?php echo "$model->HoraInicioLunesASabado - $model->HoraFinLunesASabado" ?></td>
                        <?php endif; ?>

                        <?php if ($model->Es24HorasDomingo == "SI"): ?>
                            <td class="text-center">24 Horas</td>
                        <?php else: ?>
                            <td class="text-center"><?php echo "$model->HoraInicioDomingo - $model->HoraFinDomingo" ?></td>
                        <?php endif; ?>

                        <?php if ($model->Es24HorasFestivo == "SI"): ?>
                            <td class="text-center">24 Horas</td>
                        <?php else: ?>
                            <td class="text-center"><?php echo "$model->HoraInicioFestivo - $model->HoraFinFestivo" ?></td>
                        <?php endif; ?>
                    </tr>
                </table>
            <?php endif; ?>
        </div>
    </body>
</html>