<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AprobacionController
 *
 * @author Miguel Angel Sanchez Montiel
 */
class ReporteHorariosController extends Controller {

    public function actionZonaList() {
        $model = new ReporteHorariosForm;

        if (isset($_POST['ReporteHorariosForm']))
            $model->attributes = $_POST['ReporteHorariosForm'];

        if ($model->IDSede == null || $model->IDSede == '') {
            echo CHtml::tag('option', array('value' => ''), CHtml::encode('Seleccione'), true);
            Yii::app()->end();
        }

        $zonas = Zona::model()->findAll(array(
            'order' => 'NombreZona',
            'condition' => 'IDSede=:sede',
            'params' => array(':sede' => $model->IDSede)
        ));

        echo CHtml::tag('option', array('value' => ''), CHtml::encode('Seleccione'), true);
        foreach ($zonas as $zona) {
            echo CHtml::tag('option', array('value' => $zona->IDZona), CHtml::encode($zona->NombreZona), true);
        }
    }

    public function actionPdvList() {
        $model = new ReporteHorariosForm;

        if (isset($_POST['ReporteHorariosForm']))
            $model->attributes = $_POST['ReporteHorariosForm'];

        if ($model->IDZona == null || $model->IDZona == '') {
            echo CHtml::tag('option', array('value' => ''), CHtml::encode('Seleccione'), true);
            Yii::app()->end();
        }

        $pdvs = PuntoVenta::model()->findAll(array(
            'order' => 'NombrePuntoDeVenta',
            'condition' => 'IDZona=:zona',
            'params' => array(':zona' => $model->IDZona)
        ));

        //$zonas = CHtml::listData($zonas, 'IDZona', 'NombreZona');

        echo CHtml::tag('option', array('value' => ''), CHtml::encode('Seleccione'), true);
        foreach ($pdvs as $pdv) {
            echo CHtml::tag('option', array('value' => $pdv->IDPuntoDeVenta), CHtml::encode($pdv->NombrePuntoDeVenta), true);
        }
    }

    public function actionIndex() {
        if (Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->user->loginUrl);
            Yii::app()->end();
        }

        /* if (!Yii::app()->user->checkAccess('Turnos_ReporteAnalista_index')) {
          $this->render('//site/error', array('code' => '101', 'message' => Yii::app()->params['accessError']));
          Yii::app()->end();
          } */

        $listSede = array();

        if (false) {
            $objEmpleado = Empleado::model()->find(array(
                'order' => 'idEmpleado DESC',
                'with' => array('estado'),
                'condition' => 't.NumeroDocumento=:cedula AND estado.Estado=:estado',
                'params' => array(':cedula' => Yii::app()->user->name, ':estado' => Yii::app()->controller->module->asocActivo)
            ));

            if ($objEmpleado === null) {
                throw new CHttpException(404, 'Empleado no existente o inactivo');
            }

            $objSede = Sede::model()->find(array(
                'condition' => 'CodigoSede=:sede',
                'params' => array(
                    ':sede' => str_replace("0", "", $objEmpleado->IdOperaciones)
                )
            ));

            if ($objSede !== null) {
                $listSede[] = $objSede;
            }
        } else {
            $listSede = Sede::model()->findAll(array('order' => 'NombreSede'));
        }

        $listSede = CHtml::listData($listSede, 'IDSede', 'NombreSede');

        $model = new ReporteHorariosForm;
        $model->unsetAttributes();

        if (isset($_POST['ReporteHorariosForm'])) {
            $model->attributes = $_POST['ReporteHorariosForm'];

            if ($model->validate()) {
                Yii::app()->session[Yii::app()->controller->module->session['modelReporteHorarioEspecial']] = $model;
                $this->generarReporte($model);
            }
        }

        $this->render('index', array(
            'model' => $model,
            'listSede' => $listSede
        ));
    }

    public function actionTest() {


        /*$listPuntosVenta = PuntoVenta::model()->findAll(array(
            'with' => array('zona', 'sede', 'horariosEspeciales' => array('with' => array('objHorarioEspecialDia', 'objHorarioEspecialRango'))),
            'condition' => '',
        ));*/
        
        $dia = '1';
        
        $es24Hrs = Yii::app()->getModule('turnos')->horariosDiaSemanaEspecial['rango'][$dia]['24hr'];
        
        CVarDumper::dump($es24Hrs, 10, true);
        
        /*$horaInicio = Yii::app()->controller->module->horariosDiaSemanaEspecial['rango'][$dia]['inicio'];
        $horaFin = Yii::app()->controller->module->horariosDiaSemanaEspecial['rango'][$dia]['fin'];*/
        
        
                        
    }
    

    private function generarReporte(ReporteHorariosForm $model) {

        /* if (!Yii::app()->user->checkAccess('Turnos_ReporteAnalista_index')) {
          $this->render('//site/error', array('code' => '101', 'message' => Yii::app()->params['accessError']));
          Yii::app()->end();
          } */

        $condition = "1=1";

        if ($model->IDSede != null && !empty($model->IDSede)) {
            $condition .= " AND t.IDSede = '$model->IDSede'";
        }

        if ($model->IDZona != null && !empty($model->IDZona)) {
            $condition .= " AND t.IDZona = '$model->IDZona'";
        }

        if ($model->IDPuntoDeVenta != null && !empty($model->IDPuntoDeVenta)) {
            $condition .= " AND t.IDPuntoDeVenta = '$model->IDPuntoDeVenta'";
        }

        if ($model->FechaInicio != null && !empty($model->FechaInicio) && $model->FechaFin != null && !empty($model->FechaFin)) {
            $condition .= " AND ((objHorarioEspecialDia.Fecha>='$model->FechaInicio' AND objHorarioEspecialDia.Fecha<= '$model->FechaFin') OR 
                        ((objHorarioEspecialRango.FechaInicio<='$model->FechaInicio' AND objHorarioEspecialRango.FechaFin>'$model->FechaInicio') OR
                        (objHorarioEspecialRango.FechaInicio<'$model->FechaFin' AND objHorarioEspecialRango.FechaFin>='$model->FechaFin') OR
                        (objHorarioEspecialRango.FechaInicio>='$model->FechaInicio' AND objHorarioEspecialRango.FechaFin<='$model->FechaFin')))";
        }

        $listPuntosVenta = PuntoVenta::model()->findAll(array(
            'with' => array('zona', 'sede', 'horariosEspeciales' => array('with' => array('objHorarioEspecialDia', 'objHorarioEspecialRango'))),
            'condition' => $condition,
            'order' => 't.IDComercial, ',
        ));
        
        
        
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("Reporte de activos");

        $objPHPExcel->setActiveSheetIndex(0);
        $objWorksheet = $objPHPExcel->getSheet(0);
        $objWorksheet->setTitle('Reporte Activos Aprobados');
        
        $objWorksheet->setCellValue('A1', 'SEDE');
        $objWorksheet->setCellValue('B1', 'ZONA');
        $objWorksheet->setCellValue('C1', 'COD ALF');
        $objWorksheet->setCellValue('D1', 'PUNTO DE VENTA');
        
        $objWorksheet->setCellValue('E1', 'No. ACTIVO');
        $objWorksheet->setCellValue('F1', 'DETALLE ACTIVO');
        $objWorksheet->setCellValue('G1', 'CANTIDAD');
        $objWorksheet->setCellValue('H1', 'OBSERVACION');
        $objWorksheet->setCellValue('I1', 'REFERENCIA ACTIVO');
        
        
        $arregloReporte = array();
        //$content = "";

        foreach ($listPuntosVenta as $objPuntoVenta) {
            $coberturaDia = array();
            $horarioEspcialDia = array();//guarda los dias q existen horario especial por dia, para tenerlos en cuenta en los rangos como prioridad
            $coberturaRango = array();
            
            //$arregloReporte[$objPuntoVenta->IDPuntoDeVenta]['objPuntoVenta'] = $objPuntoVenta;
        
            //$content .= str_pad($objPuntoVenta->IdCentroCostos, 10, " ", STR_PAD_RIGHT) . str_pad($objPuntoVenta->IDPuntoDeVenta, 10, " ", STR_PAD_RIGHT) . "\n";

            foreach ($objPuntoVenta->horariosEspeciales as $idx => $objHorarioEspecial) {
                if($objHorarioEspecial->objHorarioEspecialDia !== null){
                    $coberturaDia[$idx] = "SI";
                    $objHorarioDia = $objHorarioEspecial->objHorarioEspecialDia;

                    $horarioEspcialDia[$objHorarioDia->Fecha] = 1;
                    $fechaDia = DateTime::createFromFormat('Y-m-d H:i:s', "$objHorarioDia->Fecha 00:00:00");
                    $horarioIni = null;
                    $horarioFin = null;

                    if ($objHorarioDia->Es24Horas == "SI") {
                        $horarioIni = DateTime::createFromFormat('Y-m-d H:i:s', $fechaDia->format('Y-m-d') . ' 00:00:00');
                        $horarioFin = DateTime::createFromFormat('Y-m-d H:i:s', $fechaDia->format('Y-m-d') . ' 23:59:00');
                    } else {
                        $horarioIni = DateTime::createFromFormat('Y-m-d H:i:s', $fechaDia->format('Y-m-d') . ' ' . $objHorarioDia->HoraInicio);
                        $horarioFin = DateTime::createFromFormat('Y-m-d H:i:s', $fechaDia->format('Y-m-d') . ' ' . $objHorarioDia->HoraFin);
                    }
                    
                    //se recorre todo el dia, en el rando de horario de apertura del punto de venta
                    while (true) {
                        $diffDia = $horarioIni->diff($horarioFin);

                        if ($diffDia->invert == 1)
                            break;

                        $turno = AsignacionTurnos::model()->find(array(
                            'condition' => 'IDPuntoDeVenta=:puntoventa AND FechaInicioAsignacion<=:fecha AND FechaFinAsignacion>=:fecha',
                            'params' => array(
                                'puntoventa' => $objPuntoVenta->IDPuntoDeVenta,
                                'fecha' => $horarioIni->format('Y-m-d H:i:s')
                            )
                        ));

                        if ($turno === null) {
                            $coberturaDia[$idx] = "NO";
                            break;
                        }

                        $horarioIni = DateTime::createFromFormat('Y-m-d H:i:s', $turno->FechaFinAsignacion);
                        $horarioIni->modify('+15 minutes');
                    }
                }else if($objHorarioEspecial->objHorarioEspecialRango !== null){
                    $objHorarioRango = $objHorarioEspecial->objHorarioEspecialRango;
                    $coberturaRango[$idx] = array('HoraInicioLunesASabado' => "SI", 'HoraInicioDomingo' => "SI", 'HoraInicioFestivo' => "SI",);
                    
                    $fechaDia = DateTime::createFromFormat('Y-m-d H:i:s', "$objHorarioRango->FechaInicio 00:00:00");
                    $fechaFinMes = DateTime::createFromFormat('Y-m-d H:i:s', "$objHorarioRango->FechaFin 00:00:00");
                    
                    while (true) {
                        if (isset($horarioEspcialDia[$fechaDia->format('Y-m-d')])) {
                            $fechaDia->modify('+1 day');
                            continue;
                        }

                        $diffMes = $fechaDia->diff($fechaFinMes);

                        if ($diffMes->invert == 1) {
                            break;
                        }

                        $dia = 'festivo';
                        //si no es festivo, se verifica el dia de la semana
                        if (DiasFestivos::esFestivo($fechaDia) == 0) {
                            $dia = $fechaDia->format('w');
                        }

                        $es24Hrs = Yii::app()->getModule('turnos')->horariosDiaSemanaEspecial['rango'][$dia]['24hr'];
                        $horaInicio = Yii::app()->getModule('turnos')->horariosDiaSemanaEspecial['rango'][$dia]['inicio'];
                        $horaFin = Yii::app()->getModule('turnos')->horariosDiaSemanaEspecial['rango'][$dia]['fin'];
                        $horarioIni = null;
                        $horarioFin = null;

                        if ($objHorarioRango->$es24Hrs == "NO") {
                            $horarioIni = DateTime::createFromFormat('Y-m-d H:i:s', $fechaDia->format('Y-m-d') . ' ' . $objHorarioRango->$horaInicio);
                            $horarioFin = DateTime::createFromFormat('Y-m-d H:i:s', $fechaDia->format('Y-m-d') . ' ' . $objHorarioRango->$horaFin);
                        } else {
                            $horarioIni = DateTime::createFromFormat('Y-m-d H:i:s', $fechaDia->format('Y-m-d') . ' 00:00:00');
                            $horarioFin = DateTime::createFromFormat('Y-m-d H:i:s', $fechaDia->format('Y-m-d') . ' 23:59:00');
                        }

                        $loopDia = true;
                        //se recorre todo el dia, en el rando de horario de apertura del punto de venta
                        while ($loopDia) {
                            $diffDia = $horarioIni->diff($horarioFin);

                            if ($diffDia->invert == 1) {
                                $loopDia = false;
                            } else {
                                $turno = AsignacionTurnos::model()->find(array(
                                    'condition' => 'IDPuntoDeVenta=:puntoventa AND FechaInicioAsignacion<=:fecha AND FechaFinAsignacion>=:fecha',
                                    'params' => array(
                                        'puntoventa' => $objPuntoVenta->IDPuntoDeVenta,
                                        'fecha' => $horarioIni->format('Y-m-d H:i:s')
                                    )
                                ));

                                if ($turno == null) {
                                    $coberturaRango[$idx][$horaInicio] = "NO";
                                    $loopDia = false;
                                } else {
                                    $horarioIni = DateTime::createFromFormat('Y-m-d H:i:s', $turno->FechaFinAsignacion);
                                    $horarioIni->modify('+15 minutes');
                                }
                            }
                        }

                        $fechaDia->modify('+1 day');
                    }
                }else{
                    //error
                }
                
                //$content .= "*****     " . str_pad($objHorarioEspecial->IdHorarioEspecialPuntoVenta, 5, " ", 1) . str_pad($objHorarioEspecial->IdHorarioEspecialRango, 5, " ", 1) . str_pad($objHorarioEspecial->IdHorarioEspecialDia, 5, " ", 1) . "\n";
            }
        }

        //Yii::app()->request->sendFile('horarios_' . date('YmdHis') . '.txt', $content);
        //Yii::app()->end();


        /* $content = $this->renderPartial('_excelAlertas', array('dataProvider' => $dataProvider), true);
          Yii::app()->request->sendFile('ReporteAlertas_' . date('YmdHis') . '.xls', $content);
          Yii::app()->end(); */
        
        
        // Redirect output to a clients web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="horariosEspeciales_' . date('YmdHis') . '.xls"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

}
