<?php

class AprobacionActivosController extends Controller {
    /**
     * @var string accion por defecto del controlador
     */
    //public $defaultAction = 'admin';

    /**
     * Manages all models.
     */
    public function actionIndex() {

        if (Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->user->loginUrl);
            Yii::app()->end();
        }

        if (!Yii::app()->user->checkAccess('PuntoDeVenta_AprobacionActivos_index')) {
            $this->render('//site/error', array('code' => '101', 'message' => Yii::app()->params['accessError']));
            Yii::app()->end();
        }

        $modelReporte = new ReporteActivosForm;
        $zona = Zona::model()->find(array(
            'condition' => 'CedulaDirectorZona=:cedula OR CedulaAuxiliarZona=:cedula',
            'params' => array('cedula' => Yii::app()->user->name)
        ));

        if ($zona === null) {
            throw new CHttpException(403, 'Solicitud denegada. No hay zona asociada a usuario.');
        }

        if (isset($_POST['ReporteActivosForm'])) {
            $modelReporte->attributes = $_POST['ReporteActivosForm'];
            if ($modelReporte->validate()) {
                if (isset($_POST['excel'])) {
                    $this->exportarReporte($zona, $modelReporte);
                }
            }
        }

        $model = new ActivosPuntoVenta('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['ActivosPuntoVenta']))
            $model->attributes = $_GET['ActivosPuntoVenta'];

        $this->render('index', array(
            'zona' => $zona,
            'model' => $model,
            'modelReporte' => $modelReporte
        ));
    }

    private function exportarSiesa(Zona $zona, ReporteActivosForm $modelReporte) {
        $activosPdv = ActivosPuntoVenta::model()->findAll(array(
            'with' => array('activo', 'puntoVenta' => array('with' => array('sede'))),
            'condition' => 'puntoVenta.IDZona=:zona AND t.Estado=:estado AND t.FechaSolicitud>=:fechaini AND t.FechaSolicitud<=:fechafin',
            'params' => array(
                ':zona' => $zona->IDZona,
                ':estado' => Yii::app()->getModule("puntoventa")->estadoSolicitudActivo['Aprobado'],
                ':fechaini' => "$modelReporte->FechaInicio 00:00:00",
                ':fechafin' => "$modelReporte->FechaFin 23:59:59",
            )
        ));

        $fecha = new DateTime;

        $content = "000000100000001001\r\n";
        $content .= "00000020420000400101" . "905" . "SC 00000001" . $fecha->format('Ymd') . "40140140111" . str_pad("67003255", 15, " ", STR_PAD_RIGHT) . "                     1      00000000.0000   00000000.0000000.0000000.0000                                                                                                                                                                                                                                                               0                                                                                                                                                                                                                                                                                                                              X                             0\r\n";

        $contador = 3;
        foreach ($activosPdv as $idx => $activoPdv) {
            $content .= "000000" . $contador . "04210004001" . $activoPdv->puntoVenta->IdCentroCostos . "SC 000000010000000001                                                       99801401019                                   UND " . str_pad($activoPdv->Cantidad, 15, "0", STR_PAD_LEFT) . ".0000" . $fecha->format('Ymd') . "               000000000000000.0000000000000000000                                                                                                                                                                                                                                                0000                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        " . str_pad($activoPdv->activo->Codigo, 7, " ", STR_PAD_RIGHT) . "                                                                                                                                  000.0000\r\n";
            $contador++;
        }

        $content .= "000000" . $contador . "99990001001\r\n";

        Yii::app()->request->sendFile('BATCHSIESA_' . date('YmdHis') . '.txt', $content);
        Yii::app()->end();
    }

    private function exportarReporte(Zona $zona, ReporteActivosForm $modelReporte) {
        $activosPdv = ActivosPuntoVenta::model()->findAll(array(
            'with' => array('activo', 'puntoVenta' => array('with' => array('sede'))),
            'condition' => 'puntoVenta.IDZona=:zona AND t.Estado=:estado AND t.FechaSolicitud>=:fechaini AND t.FechaSolicitud<=:fechafin',
            'params' => array(
                ':zona' => $zona->IDZona,
                ':estado' => Yii::app()->getModule("puntoventa")->estadoSolicitudActivo['Aprobado'],
                ':fechaini' => "$modelReporte->FechaInicio 00:00:00",
                ':fechafin' => "$modelReporte->FechaFin 23:59:59",
            )
        ));


        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("Reporte de activos");

        $objPHPExcel->setActiveSheetIndex(0);
        $objWorksheet = $objPHPExcel->getSheet(0);
        $objWorksheet->setTitle('Reporte Activos Aprobados');
        $objWorksheet->setCellValue('A1', 'COD ALF');
        $objWorksheet->setCellValue('B1', 'PUNTO DE VENTA');
        $objWorksheet->setCellValue('C1', 'SEDE');
        $objWorksheet->setCellValue('D1', 'ZONA');
        $objWorksheet->setCellValue('E1', 'No. ACTIVO');
        $objWorksheet->setCellValue('F1', 'DETALLE ACTIVO');
        $objWorksheet->setCellValue('G1', 'CANTIDAD');
        $objWorksheet->setCellValue('H1', 'OBSERVACION');
        $objWorksheet->setCellValue('I1', 'REFERENCIA ACTIVO');

        foreach ($activosPdv as $idx => $activoPdv) {
            $objWorksheet->setCellValueByColumnAndRow(0, $idx + 2, $activoPdv->puntoVenta->IDComercial);
            $objWorksheet->setCellValueByColumnAndRow(1, $idx + 2, $activoPdv->puntoVenta->NombrePuntoDeVenta);
            $objWorksheet->setCellValueByColumnAndRow(2, $idx + 2, $activoPdv->puntoVenta->sede->NombreSede);
            $objWorksheet->setCellValueByColumnAndRow(3, $idx + 2, $zona->NombreZona);
            $objWorksheet->setCellValueByColumnAndRow(4, $idx + 2, $activoPdv->activo->IdActivo);
            $objWorksheet->setCellValueByColumnAndRow(5, $idx + 2, $activoPdv->activo->DescripcionActivo);
            $objWorksheet->setCellValueByColumnAndRow(6, $idx + 2, $activoPdv->Cantidad);
            $objWorksheet->setCellValueByColumnAndRow(7, $idx + 2, $activoPdv->ObservacionSolicitante);
            $objWorksheet->setCellValueByColumnAndRow(8, $idx + 2, $activoPdv->activo->Referencia);
        }

        // Redirect output to a clients web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="reporteActivos_' . date('YmdHis') . '.xls"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    public function actionEstado() {
        if (!Yii::app()->request->isPostRequest) {
            echo CJSON::encode(array('result' => 'error', 'response' => 'Solicitud inválida'));
            Yii::app()->end();
        }

        if (!Yii::app()->user->checkAccess('PuntoDeVenta_AprobacionActivos_index')) {
            echo CJSON::encode(array('result' => 'error', 'response' => Yii::app()->params['accessError']));
            Yii::app()->end();
        }

        $render = Yii::app()->getRequest()->getPost('render', false);

        if ($render) {
            $pk = Yii::app()->getRequest()->getPost('solicitud');
            $estado = Yii::app()->getRequest()->getPost('estado');
            if ($pk === null || $estado === null) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'Solicitud inválida'));
                Yii::app()->end();
            }

            $model = ActivosPuntoVenta::model()->findByPk($pk);

            if ($model === null) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'Solicitud no existente'));
                Yii::app()->end();
            }

            if ($model->Estado == 1) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'Solicitud ya aprobada'));
                Yii::app()->end();
            }

            $model->setScenario('status');
            $model->ObservacionAprobador = null;
            $model->Estado = $estado;
            Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
            echo CJSON::encode(array(
                'result' => 'ok',
                'response' => array(
                    'msg' => 'Formulario cargado',
                    'form' => $this->renderPartial('_form', array('model' => $model), true, true)
            )));
            Yii::app()->end();
        } else if (isset($_POST['ActivosPuntoVenta'])) {
            $form = new ActivosPuntoVenta('status');
            $form->attributes = $_POST['ActivosPuntoVenta'];
            $model = ActivosPuntoVenta::model()->findByPk($form->IdActivoPuntoVenta)->with(array('puntoVenta'));

            if ($model === null) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'Solicitud no existente'));
                Yii::app()->end();
            }

            if ($model->Estado == 1) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'Solicitud ya aprobada'));
                Yii::app()->end();
            }

            $model->ObservacionAprobador = $form->ObservacionAprobador;
            $model->Estado = $form->Estado;
            $model->IdentificacionAprobador = Yii::app()->user->name;
            $model->setScenario('status');
            if ($model->validate()) {
                if($form->Estado==1){
                    $fecha = new DateTime;
                    $cadenaSiesa = "<Importar><NombreConexion>" . Yii::app()->params->siesa['conexion']['nombre'] . "</NombreConexion> <IdCia>" . Yii::app()->params->siesa['conexion']['idCia'] . "</IdCia> <Usuario>" . Yii::app()->params->siesa['conexion']['usuario'] . "</Usuario><Clave>" . Yii::app()->params->siesa['conexion']['clave'] . "</Clave>";
                    $cadenaSiesa .= "<Datos>";
                    $cadenaSiesa .= "<Linea>000000100000001001</Linea>";
                    $cadenaSiesa .= "<Linea>00000020420000400101" . str_pad($model->puntoVenta->IdCentroCostos, 3, "0", STR_PAD_LEFT) . "SC 00000001" . $fecha->format('Ymd') . "40140140111" . str_pad(Yii::app()->user->name, 15, " ", STR_PAD_RIGHT) . "                     1      00000000.0000   00000000.0000000.0000000.0000                                                                                                                                                                                                                                                               0                                                                                                                                                                                                                                                                                                                              X                             0</Linea>";
                    $cadenaSiesa .= "<Linea>000000304210004001" . str_pad($model->puntoVenta->IdCentroCostos, 3, "0", STR_PAD_LEFT) . "SC 000000010000000001                                                       99801401019                    0006           UND " . str_pad($model->Cantidad, 15, "0", STR_PAD_LEFT) . ".0000" . $fecha->format('Ymd') . "               000000000000000.0000000000000000000                                                                                                                                                                                                                                                0000                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        " . str_pad($model->activo->Codigo, 7, " ", STR_PAD_RIGHT) . "                                                                                                              01                  000.0000</Linea>";
                    $cadenaSiesa .= "<Linea>000000499990001001</Linea>";
                    $cadenaSiesa .= "</Datos>";
                    $cadenaSiesa .= "</Importar>";
                    
                    try {
                        $client = new SoapClient(Yii::app()->params->siesa['wsURL'], array(
                            "trace" => 1,
                            "exceptions" => 0,
                        ));

                        $parm = array();
                        $parm[] = new SoapVar($cadenaSiesa, XSD_STRING, null, null, 'ns1:pvstrDatos');
                        $result = $client->ImportarXML(new SoapVar($parm, SOAP_ENC_OBJECT));
                        if ($result->printTipoError != 0) {
                            if (isset(Yii::app()->params->siesa['errors'][$result->printTipoError])) {
                                Yii::log(Yii::app()->params->siesa['errors'][$result->printTipoError] . "\n" . $cadenaSiesa, CLogger::LEVEL_ERROR, 'application');
                                echo CJSON::encode(array('result' => 'error', 'response' => Yii::app()->params->siesa['errors'][$result->printTipoError]));
                                Yii::app()->end();
                            } else {
                                Yii::log(Yii::app()->params->siesa['errors']['error'] . "\n" . $cadenaSiesa, CLogger::LEVEL_ERROR, 'application');
                                echo CJSON::encode(array('result' => 'error', 'response' => Yii::app()->params->siesa['errors']['error']));
                                Yii::app()->end();
                            }
                        }
                    } catch (SoapFault $exsoap) {
                        Yii::log("SoapFault\n" . $exsoap->getMessage() . "\n" . $exsoap->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                        echo CJSON::encode(array('result' => 'error', 'response' => "SoapFault: " . $exsoap->getMessage()));
                        Yii::app()->end();
                    } catch (Exception $ex) {
                        Yii::log("Exception\n" . $ex->getMessage() . "\n" . $ex->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                        echo CJSON::encode(array('result' => 'error', 'response' => "Exception: " . $ex->getMessage()));
                        Yii::app()->end();
                    }
                }
                
                $transaction = Yii::app()->db->beginTransaction();
                try {
                    $model->save();
                    $traza = new ActivosTrazabilidad;
                    $traza->IdActivoPuntoVenta = $model->IdActivoPuntoVenta;
                    $traza->Estado = $model->Estado;
                    $traza->IdentificacionAprobador = Yii::app()->user->name;
                    $traza->ObservacionAprobador = $model->ObservacionAprobador;
                    $traza->save();
                    $transaction->commit();
                    echo CJSON::encode(array('result' => 'ok', 'response' => 'Estado actualizado'));
                    Yii::app()->end();
                } catch (CDbException $excdb) {
                    Yii::log($excdb->getMessage() . "\n" . $excdb->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                    try {
                        $transaction->rollBack();
                    } catch (Exception $txexc) {
                        Yii::log($txexc->getMessage() . '\n' . $txexc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                    }
                    echo CJSON::encode(array('result' => 'error', 'response' => "Error BD: " . $excdb->getMessage()));
                    Yii::app()->end();
                }

            } else {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
        } else {
            echo CJSON::encode(array('result' => 'error', 'response' => 'Solicitud inválida'));
            Yii::app()->end();
        }
    }

    public function sincronizarsiesa() {
        $models = ActivosPuntoVenta::model()->findAll(array(
            'with' => array('activo', 'puntoVenta', 'listActivosTraza'),
            'condition' => "listActivosTraza.Estado=:estado AND listActivosTraza.FechaRegistro<=:fecha AND listActivosTraza.IdentificacionAprobador!=:aprobador",
            'params' => array(
                ':estado' => 1,
                ':aprobador' => '67003255',
                ':fecha' => "2015-10-07 15:30:00"
            ),
        ));

        /* foreach ($models as $model){

          echo "$model->IdActivoPuntoVenta -- $model->IdActivo -- $model->IDPuntoDeVenta -- $model->Estado -- $model->FechaSolicitud <br/>";

          foreach ($model->listActivosTraza as $objTraza){
          echo " -- $objTraza->IdActivosTrazabilidad -- $objTraza->IdActivoPuntoVenta -- $objTraza->Estado -- $objTraza->IdentificacionAprobador -- $objTraza->FechaRegistro <br/>";
          }
          echo "<br/>";
          }

          exit(); */

        $fecha = new DateTime;

        try {
            //$archivo = fopen(Yii::app()->basePath . DS . 'runtime' . DS . '/sincrosiesa.log', 'a+');
            $archivo = null;
            $this->log($archivo, "Inicio sincronizacion con " . count($models) . " activos", "INFO");

            $client = new SoapClient(Yii::app()->params->siesa['wsURL'], array(
                "trace" => 1,
                //"exceptions" => 0,
            ));

            foreach ($models as $idx => $model) {
                $this->log($archivo, "Sincronizando $idx ::", "INFO");

                $cadenaSiesa = "<Importar><NombreConexion>" . Yii::app()->params->siesa['conexion']['nombre'] . "</NombreConexion> <IdCia>" . Yii::app()->params->siesa['conexion']['idCia'] . "</IdCia> <Usuario>" . Yii::app()->params->siesa['conexion']['usuario'] . "</Usuario><Clave>" . Yii::app()->params->siesa['conexion']['clave'] . "</Clave>";
                $cadenaSiesa .= "<Datos>";
                $cadenaSiesa .= "<Linea>000000100000001001</Linea>";
                $cadenaSiesa .= "<Linea>00000020420000400101" . str_pad($model->puntoVenta->IdCentroCostos, 3, "0", STR_PAD_LEFT) . "SC 00000001" . $fecha->format('Ymd') . "40140140111" . str_pad(Yii::app()->user->name, 15, " ", STR_PAD_RIGHT) . "                     1      00000000.0000   00000000.0000000.0000000.0000                                                                                                                                                                                                                                                               0                                                                                                                                                                                                                                                                                                                              X                             0</Linea>";
                $cadenaSiesa .= "<Linea>000000304210004001" . str_pad($model->puntoVenta->IdCentroCostos, 3, "0", STR_PAD_LEFT) . "SC 000000010000000001                                                       99801401019                    0006           UND " . str_pad($model->Cantidad, 15, "0", STR_PAD_LEFT) . ".0000" . $fecha->format('Ymd') . "               000000000000000.0000000000000000000                                                                                                                                                                                                                                                0000                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        " . str_pad($model->activo->Codigo, 7, " ", STR_PAD_RIGHT) . "                                                                                                                                  000.0000</Linea>";
                $cadenaSiesa .= "<Linea>000000499990001001</Linea>";
                $cadenaSiesa .= "</Datos>";
                $cadenaSiesa .= "</Importar>";

                try {
                    $parm = array();
                    $parm[] = new SoapVar($cadenaSiesa, XSD_STRING, null, null, 'ns1:pvstrDatos');
                    $result = $client->ImportarXML(new SoapVar($parm, SOAP_ENC_OBJECT));
                    if ($result->printTipoError == 0) {
                        $strSuccess = "$idx >>> id: $model->IdActivoPuntoVenta -- pdv:" . $model->puntoVenta->IDComercial . " -- activo: " . $model->activo->Codigo . "[" . $model->activo->Referencia . "]" . " -- estado: $model->Estado -- fechasolicitud: $model->FechaSolicitud";
                        $this->log($archivo, $strSuccess, "SUCCESS");
                    } else {
                        if (isset(Yii::app()->params->siesa['errors'][$result->printTipoError])) {
                            $this->log($archivo, "$idx >>> " . Yii::app()->params->siesa['errors'][$result->printTipoError] . "\n" . $cadenaSiesa, "ERROR");
                        } else {
                            $this->log($archivo, "$idx >>> " . Yii::app()->params->siesa['errors']['error'] . "\n" . $cadenaSiesa, "ERROR");
                        }
                    }
                } catch (SoapFault $exsoap) {
                    $this->log($archivo, "SoapFault: $idx >>> " . $exsoap->getMessage() . "\n" . $exsoap->getTraceAsString(), "ERROR");
                } catch (Exception $ex) {
                    $this->log($archivo, "Exception: $idx >>> " . $ex->getMessage() . "\n" . $ex->getTraceAsString(), "ERROR");
                }
            }
            $this->log($archivo, "Fin sincronizacion activos", "INFO");
            //fclose($archivo);
        } catch (Exception $ex2) {
            Yii::log("Exception\n" . $ex2->getMessage() . "\n" . $ex2->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
        }
    }

    protected function log($archivo, $cadena, $tipo) {
        if($archivo!=null){
            fwrite($archivo, "$tipo::\n" . $cadena . "\n");
            fwrite($archivo, date("Y-m-d H:i:s") . "\r\n");
        }
    }

    protected function gridEstado($data, $row) {
        return CHtml::dropDownList('select-estado-' . uniqid(), $data->Estado, Yii::app()->getModule("puntoventa")->listEstadosSolicitudActivo, array('data-role' => 'selectestadosolicitud', 'data-id' => $data->IdActivoPuntoVenta, 'data-estado' => $data->Estado, 'class' => 'form-control input-sm', 'style' => "text-align:center; width:120px;"));
    }

}
