<?php

class InfluenciaPuntoVentaController extends Controller {
    
   public function actionCiudades() {
        $departamento = Yii::app()->getRequest()->getPost('departamento', '');
        $departamento = trim($departamento);

        if ($departamento == '') {
            echo CHtml::tag('option', array('value' => ''), CHtml::encode('Seleccione'), true);
            Yii::app()->end();
        }

        $data = Ciudad::model()->findAll('IdDepartamento=:departamento', array(':departamento' => $departamento));
        $data = CHtml::listData($data, 'IdCiudad', 'NombreCiudad');

        echo CHtml::tag('option', array('value' => ''), CHtml::encode('Seleccione'), true);
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
    }

    public function actionBarrios() {
        $ciudad = Yii::app()->getRequest()->getPost('ciudad', '');
        $ciudad = trim($ciudad);

        if ($ciudad == '') {
            echo CHtml::tag('option', array('value' => ''), CHtml::encode('Seleccione'), true);
            Yii::app()->end();
        }

        $data = Barrio::model()->findAll('IdCiudad=:ciudad', array(':ciudad' => $ciudad));
        $data = CHtml::listData($data, 'IdBarrio', 'NombreBarrio');

        echo CHtml::tag('option', array('value' => ''), CHtml::encode('Seleccione'), true);
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
    }

    public function actionCrear() {
        if (Yii::app()->request->isPostRequest) {

            if (Yii::app()->user->isGuest) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta usuario autenticado, por favor iniciar sesi&oacute;n'));
                Yii::app()->end();
            }

            if (!Yii::app()->user->checkAccess('PuntoDeVenta_InfluenciaPuntoVenta_crear')) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'Error: 101 => ' . Yii::app()->params['accessError']));
                Yii::app()->end();
            }

            $render = Yii::app()->getRequest()->getPost('render', false);
            $model = new InfluenciaPuntoVenta;

            if ($render) {
                $pk = Yii::app()->getRequest()->getPost('puntoventa');

                if ($pk === null) {
                    echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta punto de venta'));
                    Yii::app()->end();
                }

                $puntoVenta = PuntoVenta::model()->findByPk($pk);

                if ($puntoVenta === null) {
                    echo CJSON::encode(array('result' => 'error', 'response' => 'Punto de venta no existe'));
                    Yii::app()->end();
                }

                $model->IDPuntoDeVenta = $puntoVenta->IDPuntoDeVenta;

                $listData = Departamento::model()->findAll(array('order' => 'NombreDepartamento'));
                $listData = CHtml::listData($listData, 'IdDepartamento', 'NombreDepartamento');

                Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
                Yii::app()->clientScript->registerScript('prettyphoto', "$('" . Yii::app()->controller->module->elementPretty . "').prettyPhoto(" . CJSON::encode(Yii::app()->controller->module->optPretty) . ");", CClientScript::POS_END);

                echo CJSON::encode(array(
                    'result' => 'ok',
                    'response' => array(
                        'msg' => 'Datos cargados para creaci&oacute;n',
                        'form' => $this->renderPartial('_form', array('model' => $model, 'listData' => $listData, 'puntoventa' => $puntoVenta->IDPuntoDeVenta), true, true)
                )));
                Yii::app()->end();
            } else if (isset($_POST['barrio'])) {
                $pk = Yii::app()->getRequest()->getPost('barrio');
                $puntoventa = Yii::app()->getRequest()->getPost('puntoventa');

                if ($pk === null) {
                    echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta barrio'));
                    Yii::app()->end();
                }

                $pk = trim($pk);

                if ($pk === '') {
                    echo CJSON::encode(array('result' => 'error', 'response' => 'Barrio no puede estar vac&iacute;o'));
                    Yii::app()->end();
                }

                $model = Barrio::model()->findByPk($pk);

                if ($model === null) {
                    echo CJSON::encode(array('result' => 'error', 'response' => 'Barrio no existe'));
                    Yii::app()->end();
                }

                $influencia = new InfluenciaPuntoVenta;
                $influencia->IDBarrio = $model->IdBarrio;
                $influencia->IDPuntoDeVenta = $puntoventa;

                try {
                    $influencia->save();

                    echo CJSON::encode(array(
                        'result' => 'ok',
                        'response' => 'ok'));
                    Yii::app()->end();
                } catch (Exception $exc) {
                    Yii::log($exc->getTraceAsString() . '\n' . $exc->getMessage(), CLogger::LEVEL_ERROR, 'application');

                    if ($exc->getCode() === 23000) {
                        echo CJSON::encode(array('result' => 'error', 'response' => "Error ( " . $exc->getCode() . "): Error por restricci&oacute;n de relaci&oacute;n"));
                        Yii::app()->end();
                    } else {
                        echo CJSON::encode(array('result' => 'error', 'response' => "Error ( " . $exc->getCode() . "): " . $exc->getMessage()));
                        Yii::app()->end();
                    }
                }
            } else {
                echo CJSON::encode(array('result' => 'error', 'response' => 'Solicitud inv&aacute;lida'));
                Yii::app()->end();
            }
        } else {
            echo CJSON::encode(array('result' => 'error', 'response' => 'Solicitud inv&aacute;lida'));
            Yii::app()->end();
        }
    }

    public function actionEliminar($idpdv, $idbarrio) {
        if (Yii::app()->request->isPostRequest) {
            if (Yii::app()->user->isGuest) {
                throw new CHttpException(404, 'No se detecta usuario autenticado, por favor iniciar sesi&oacute;n.');
            }

            if (!Yii::app()->user->checkAccess('PuntoDeVenta_InfluenciaPuntoVenta_eliminar')) {
                throw new CHttpException(101, Yii::app()->params['accessError']);
            }

            $model = $this->loadModel($idpdv, $idbarrio);
            $model->delete();

            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else {
            throw new CHttpException(400, 'Solicitud inv&aacute;lida.');
        }
    }

    public function actionIndex() {

        if (Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->user->loginUrl);
            Yii::app()->end();
        }

        if (!Yii::app()->user->checkAccess('PuntoDeVenta_InfluenciaPuntoVenta_cargar')) {
            $this->render('//site/error', array('code' => '101', 'message' => Yii::app()->params['accessError']));
            Yii::app()->end();
        }

        $model = new CargueInfluenciaForm;

        if (isset($_POST['CargueInfluenciaForm'])) {
            $model->attributes = $_POST['CargueInfluenciaForm'];

            $uploadedFile = CUploadedFile::getInstance($model, 'archivo');
            $directorio = Yii::getPathOfAlias('application.modules.puntoventa') . Yii::app()->controller->module->uploadMasivo;
            $fecha = new DateTime();
            $nombreArchivo = $uploadedFile->getName();
            $archivo = $fecha->format("YmdHis") . '_' . $nombreArchivo;
            
            $model->archivo = "$directorio/$archivo";

            if (!file_exists($directorio)) {
                mkdir($directorio, 0777, true);
            }
            
            if ($model->validate()) {
                if ($uploadedFile->saveAs($model->archivo)) {
                    $extension['xlsx'] = 'PHPExcel_Reader_Excel2007';
                    $extension['xls'] = 'PHPExcel_Reader_Excel5';
                    $objReader = new $extension[$uploadedFile->getExtensionName()];
                    $objPHPExcel = $objReader->load($model->archivo);

                    $idComercialArr = $objPHPExcel->getSheetNames();
                    $idComercialStr = "'" . implode("','", $idComercialArr) . "'";
                    $nHojas = $objPHPExcel->getSheetCount();

                    $transaction = Yii::app()->db->beginTransaction();
                    try {
                        //borrar influencias de pdvs a cargar
                        InfluenciaPuntoVenta::model()->deleteAll("IDPuntoDeVenta IN (SELECT IDPuntoDeVenta FROM m_PuntoVenta WHERE IDComercial IN ($idComercialStr))");

                        $warnings = "";
                        $arrTotal = array();
                        //leer cada barrio, crear objeto influencia y guardar
                        for ($idx = 0; $idx < $nHojas; $idx++) {
                            $objWorksheet = $objPHPExcel->getSheet($idx)->toArray(null, true, true, true);
                            $idComercial = trim($objPHPExcel->getSheet($idx)->getTitle());
                            $puntoventa = PuntoVenta::model()->find(array(
                                'condition' => 'IDComercial=:idcomercial',
                                'params' => array(
                                    ':idcomercial' => $idComercial
                                )
                            ));

                            if ($puntoventa == null) {
                                throw new CHttpException(404, "Punto de Venta con IDComercial $idComercial no existe.");
                            }
                            
                            $arrTotal[$idComercial] = array('total' => 0, 'cargado' => 0);
                            
                            
                            foreach ($objWorksheet as $registro => $objCelda) {
                                $arrTotal[$idComercial]['total'] = $arrTotal[$idComercial]['total'] +1;
                                $nombreBarrio = trim($objCelda['A']);

                                if (empty($nombreBarrio)) {
                                    $warnings .= "Punto de Venta con IDComercial $idComercial no tiene barrio asignado en registro $registro.<br/>";
                                } else {
                                    $barrio = Barrio::model()->find(array(
                                        'condition' => 'NombreBarrio=:barrio AND IdCiudad=:ciudad',
                                        'params' => array(
                                            ':barrio' => $nombreBarrio,
                                            ':ciudad' => $puntoventa->CodigoCiudad
                                        )
                                    ));

                                    if ($barrio === null) {
                                        $warnings .= "Punto de Venta con IDComercial $idComercial. No es posible asigar barrio de registro $registro, barrio $nombreBarrio no existe en ciudad $puntoventa->CodigoCiudad.<br/>";
                                    } else {
                                        $arrTotal[$idComercial]['cargado'] = $arrTotal[$idComercial]['cargado'] +1;
                                        $influencia = new InfluenciaPuntoVenta;
                                        $influencia->IDPuntoDeVenta = $puntoventa->IDPuntoDeVenta;
                                        $influencia->IDBarrio = $barrio->IdBarrio;
                                        $influencia->save();
                                    }
                                }
                            }
                        }

                        $transaction->commit();
                        //unlink($model->archivo);
                        
                        $result = "";
                        foreach ($arrTotal as $idComercial => $total) {
                            $result .= "En Punto de Venta con IDComercial $idComercial se cargaron " . $total['cargado'] . "/" . $total['total'] ." registro(s)<br/>";
                        }
                        
                        
                        Yii::app()->user->setFlash('success', "Archivo $nombreArchivo cargado. <br/>" . $result);

                        if (!empty($warnings)) {
                            Yii::app()->user->setFlash('warning', $warnings);
                        }
                    } catch (Exception $exc) {
                        try {
                            //unlink($model->archivo);
                            $transaction->rollBack();
                        } catch (Exception $txexc) {
                            Yii::log($txexc->getMessage() . "\n" . $txexc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                        }

                        Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                        Yii::app()->user->setFlash('danger', "Error al cargar archivo $nombreArchivo. " . $exc->getMessage());
                    }
                } else {
                    Yii::app()->user->setFlash('danger', "Error al guardar archivo $nombreArchivo.");
                }
            }
        }

        $this->render('cargar', array('model' => $model));
        Yii::app()->end();
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($idpuntoventa, $idbarrio) {
        $model = InfluenciaPuntoVenta::model()->findByPk(array('IDPuntoDeVenta' => $idpuntoventa, 'IDBarrio' => $idbarrio));

        if ($model === null)
            throw new CHttpException(404, 'La página solicitada no existe.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'telefonos-punto-venta-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
