<?php if ($dataProvider !== null): ?>
    <table border="1">
        <tr>
            <th style="background-color:#000;color: #FFF;">IDPuntoDeVenta</th>
            <th style="background-color:#000;color: #FFF;">IDSede</th>
            <th style="background-color:#000;color: #FFF;">IDZona</th>
            <th style="background-color:#000;color: #FFF;">IDCEDI</th>
            <th style="background-color:#000;color: #FFF;">IDSector</th>
            <th style="background-color:#000;color: #FFF;">IDTipoNegocio</th>
            <th style="background-color:#000;color: #FFF;">IDComercial</th>
            <th style="background-color:#000;color: #FFF;">CodigoContable</th>
            <th style="background-color:#000;color: #FFF;">NombrePuntoDeVenta</th>
            <th style="background-color:#000;color: #FFF;">NombreCortoPuntoDeVenta</th>
            <th style="background-color:#000;color: #FFF;">DireccionPuntoDeVenta</th>
            <th style="background-color:#000;color: #FFF;">BarrioConIndicaciones</th>
            <th style="background-color:#000;color: #FFF;">IDUbicacion</th>
            <th style="background-color:#000;color: #FFF;">CodigoCiudad</th>
            <th style="background-color:#000;color: #FFF;">Estado</th>
            <th style="background-color:#000;color: #FFF;">FechaCreacionRegistro</th>
            <th style="background-color:#000;color: #FFF;">Apertura LunesASabado Inicio</th>
            <th style="background-color:#000;color: #FFF;">Apertura LunesASabado Fin</th>
            <th style="background-color:#000;color: #FFF;">Apertura Domingo Inicio</th>
            <th style="background-color:#000;color: #FFF;">Apertura Domingo Fin</th>
            <th style="background-color:#000;color: #FFF;">Apertura Festivo Inicio</th>
            <th style="background-color:#000;color: #FFF;">Apertura Festivo Fin</th>
            <th style="background-color:#000;color: #FFF;">Apertura Especial Inicio</th>
            <th style="background-color:#000;color: #FFF;">Apertura Especial Fin</th>
            <th style="background-color:#000;color: #FFF;">Domicilio LunesASabado Inicio</th>
            <th style="background-color:#000;color: #FFF;">Domicilio LunesASabado Fin</th>
            <th style="background-color:#000;color: #FFF;">Domicilio Domingo Inicio</th>
            <th style="background-color:#000;color: #FFF;">Domicilio Domingo Fin</th>
            <th style="background-color:#000;color: #FFF;">Domicilio Festivo Inicio</th>
            <th style="background-color:#000;color: #FFF;">Domicilio Festivo Fin</th>
            <th style="background-color:#000;color: #FFF;">Domicilio Especial Inicio</th>
            <th style="background-color:#000;color: #FFF;">Domicilio Especial Fin</th>
            <th style="background-color:#000;color: #FFF;">eMailPuntoDeVenta</th>
            <th style="background-color:#000;color: #FFF;">EstratoPuntoDeVenta</th>
            <th style="background-color:#000;color: #FFF;">CedulaAdministrador</th>
            <th style="background-color:#000;color: #FFF;">CedulaSubAdministrador</th>
            <th style="background-color:#000;color: #FFF;">IPCamara</th>
            <th style="background-color:#000;color: #FFF;">DireccionIPServidor</th>
            <th style="background-color:#000;color: #FFF;">RutaImagenMapa</th>
            <th style="background-color:#000;color: #FFF;">DimensionFondo</th>
            <th style="background-color:#000;color: #FFF;">DimensionAncho</th>
            <th style="background-color:#000;color: #FFF;">AreaLocal</th>
            <th style="background-color:#000;color: #FFF;">Resoluciones</th>
            <th style="background-color:#000;color: #FFF;">DireccionGoogle</th>
            <th style="background-color:#000;color: #FFF;">LatitudGoogle</th>
            <th style="background-color:#000;color: #FFF;">LongitudGoogle</th>
        </tr>
        <?php foreach ($dataProvider->getData() as $key => $puntoventa) { ?>
            <tr>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo $puntoventa->IDPuntoDeVenta ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo $puntoventa->IDSede ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo $puntoventa->IDZona ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo $puntoventa->IDCEDI ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo $puntoventa->IDSector ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo $puntoventa->IDTipoNegocio ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo $puntoventa->IDComercial ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo $puntoventa->CodigoContable ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo $puntoventa->NombrePuntoDeVenta ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo $puntoventa->NombreCortoPuntoDeVenta ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo $puntoventa->DireccionPuntoDeVenta ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo $puntoventa->BarrioConIndicaciones ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo $puntoventa->IDUbicacion ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo $puntoventa->CodigoCiudad ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo $puntoventa->Estado ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo $puntoventa->FechaCreacionRegistro ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo ($puntoventa->HorarioAperturaLunesASabado == null ? "Sin Horario" : $puntoventa->horarioAperturaLunesASabado->HorarioInicio) ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo ($puntoventa->HorarioAperturaLunesASabado == null ? "Sin Horario" : $puntoventa->horarioAperturaLunesASabado->HorarioFin) ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo ($puntoventa->HorarioAperturaDomingo == null ? "Sin Horario" : $puntoventa->horarioAperturaDomingo->HorarioInicio) ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo ($puntoventa->HorarioAperturaDomingo == null ? "Sin Horario" : $puntoventa->horarioAperturaDomingo->HorarioFin) ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo ($puntoventa->HorarioAperturaFestivo == null ? "Sin Horario" : $puntoventa->horarioAperturaFestivo->HorarioInicio) ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo ($puntoventa->HorarioAperturaFestivo == null ? "Sin Horario" : $puntoventa->horarioAperturaFestivo->HorarioFin) ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo ($puntoventa->HorarioAperturaEspecial == null ? "Sin Horario" : $puntoventa->horarioAperturaEspecial->HorarioInicio) ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo ($puntoventa->HorarioAperturaEspecial == null ? "Sin Horario" : $puntoventa->horarioAperturaEspecial->HorarioFin) ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo ($puntoventa->HorarioDomicilioLunesASabado == null ? "Sin Horario" : $puntoventa->horarioDomicilioLunesASabado->HorarioInicio) ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo ($puntoventa->HorarioDomicilioLunesASabado == null ? "Sin Horario" : $puntoventa->horarioDomicilioLunesASabado->HorarioFin) ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo ($puntoventa->HorarioDomicilioDomingo == null ? "Sin Horario" : $puntoventa->horarioDomicilioDomingo->HorarioInicio) ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo ($puntoventa->HorarioDomicilioDomingo == null ? "Sin Horario" : $puntoventa->horarioDomicilioDomingo->HorarioFin) ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo ($puntoventa->HorarioDomicilioFestivo == null ? "Sin Horario" : $puntoventa->horarioDomicilioFestivo->HorarioInicio) ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo ($puntoventa->HorarioDomicilioFestivo == null ? "Sin Horario" : $puntoventa->horarioDomicilioFestivo->HorarioFin) ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo ($puntoventa->HorarioDomicilioEspecial == null ? "Sin Horario" : $puntoventa->horarioDomicilioEspecial->HorarioInicio) ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo ($puntoventa->HorarioDomicilioEspecial == null ? "Sin Horario" : $puntoventa->horarioDomicilioEspecial->HorarioFin) ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo $puntoventa->eMailPuntoDeVenta ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo $puntoventa->EstratoPuntoDeVenta ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo $puntoventa->CedulaAdministrador ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo $puntoventa->CedulaSubAdministrador ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo $puntoventa->IPCamara ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo $puntoventa->DireccionIPServidor ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo $puntoventa->RutaImagenMapa ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo $puntoventa->DimensionFondo ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo $puntoventa->DimensionAncho ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo $puntoventa->AreaLocal ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo $puntoventa->Resoluciones ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo $puntoventa->DireccionGoogle ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo $puntoventa->LatitudGoogle ?></td>
                <td <?php echo $key % 2 == 0 ? "style='background-color:#CCC;'" : ""; ?>><?php echo $puntoventa->LongitudGoogle ?></td>
            </tr>
        <?php } ?>
    </table>
<?php endif; ?>
