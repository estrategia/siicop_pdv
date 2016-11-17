<?php

class ActivoController extends Controller {

    /**
     * @var string accion por defecto del controlador
     */
    public $defaultAction = 'admin';

    /**
     * Crea un nuevo modelo.
     */
    public function actionCrear() {
        if (!Yii::app()->request->isPostRequest) {
            echo CJSON::encode(array('result' => 'error', 'response' => 'Solicitud inv&aacute;lida'));
            Yii::app()->end();
        }

        if (Yii::app()->user->isGuest) {
            echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta usuario autenticado, por favor iniciar sesi&oacute;n'));
            Yii::app()->end();
        }

        if (!Yii::app()->user->checkAccess('PuntoDeVenta_Activo_crear')) {
            echo CJSON::encode(array('result' => 'error', 'response' => 'Error: 101 => ' . Yii::app()->params['accessError']));
            Yii::app()->end();
        }

        $render = Yii::app()->getRequest()->getPost('render', false);
        $model = new Activo;

        if ($render) {
            //Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
            echo CJSON::encode(array(
                'result' => 'ok',
                'response' => array(
                    'msg' => 'Datos cargados para creaci&oacute;n',
                    'form' => $this->renderPartial('_form', array('model' => $model), true, true)
            )));
            Yii::app()->end();
        } else if (isset($_POST['Activo'])) {
            $model->attributes = $_POST['Activo'];
            $model->Estado = Yii::app()->getModule('puntoventa')->estadoActivos['Activo'];

            if ($model->validate()) {
                try {
                    $model->save();
                    echo CJSON::encode(array('result' => 'ok', 'response' => 'Registro creado'));
                    Yii::app()->end();
                } catch (Exception $exc) {
                    Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                    echo CJSON::encode(array('result' => 'error', 'response' => 'Error al crear registro. ' . $exc->getMessage()));
                    Yii::app()->end();
                }
            } else {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
        } else {
            echo CJSON::encode(array('result' => 'error', 'response' => 'Solicitud inv&aacute;lida'));
            Yii::app()->end();
        }
    }

    public function actionEstado() {
        if (!Yii::app()->request->isPostRequest) {
            echo CJSON::encode(array('result' => 'error', 'response' => 'Solicitud inválida'));
            Yii::app()->end();
        }

        $idActivo = Yii::app()->getRequest()->getPost('activo');
        $estado = Yii::app()->getRequest()->getPost('estado');

        if ($idActivo == null || $estado == null) {
            echo CJSON::encode(array('result' => 'error', 'response' => 'Solicitud inválida'));
            Yii::app()->end();
        }

        if (!isset(Yii::app()->getModule("puntoventa")->listEstadosActivos[$estado])) {
            echo CJSON::encode(array('result' => 'error', 'response' => 'Solicitud inválida'));
            Yii::app()->end();
        }

        $objActivo = Activo::model()->findByPk($idActivo);

        if ($objActivo === null) {
            echo CJSON::encode(array('result' => 'error', 'response' => 'Activo no existe'));
            Yii::app()->end();
        }

        $objActivo->Estado = $estado;
        if ($objActivo->save()) {
            echo CJSON::encode(array('result' => 'ok', 'response' => 'Estado actualizado'));
            Yii::app()->end();
        } else {
            echo CJSON::encode(array('result' => 'error', 'response' => $objActivo->validateErrorsResponse()));
            Yii::app()->end();
        }
    }

    /**
     * Actualiza un modelo particular.
     */
    public function actionActualizar() {
        if (!Yii::app()->request->isPostRequest) {
            echo CJSON::encode(array('result' => 'error', 'response' => 'Solicitud inválida'));
            Yii::app()->end();
        }

        if (Yii::app()->user->isGuest) {
            echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta usuario autenticado, por favor iniciar sesi&oacute;n'));
            Yii::app()->end();
        }

        if (!Yii::app()->user->checkAccess('PuntoDeVenta_Activo_actualizar')) {
            echo CJSON::encode(array('result' => 'error', 'response' => 'Error: 101 => ' . Yii::app()->params['accessError']));
            Yii::app()->end();
        }

        $render = Yii::app()->getRequest()->getPost('render', false);

        if ($render) {
            $pk = Yii::app()->getRequest()->getPost('activo', null);

            if ($pk === null) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'Solicitud inválida'));
                Yii::app()->end();
            }

            try {
                $model = Activo::model()->findByPk($pk);

                if ($model === null) {
                    echo CJSON::encode(array('result' => 'error', 'response' => 'Activo no existe'));
                    Yii::app()->end();
                }

                Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
                echo CJSON::encode(array(
                    'result' => 'ok',
                    'response' => array(
                        'msg' => 'Datos cargados para actualizar',
                        'form' => $this->renderPartial('_form', array('model' => $model), true, true)
                )));
                Yii::app()->end();
            } catch (Exception $exc) {
                Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                echo CJSON::encode(array('result' => 'error', 'response' => 'Error al cargar registro.' . $exc->getMessage()));
                Yii::app()->end();
            }
        } else if (isset($_POST['Activo'])) {
            $form = new Activo('update');
            $form->attributes = $_POST['Activo'];

            try {
                $model = Activo::model()->findByPk($form->IdActivo);
                $model->Codigo = $form->Codigo;
                $model->Referencia = $form->Referencia;
                $model->DescripcionActivo = $form->DescripcionActivo;
                $model->Estado = $form->Estado;
                $model->IdActivoCategoria = $form->IdActivoCategoria;

                if ($model->validate()) {
                    $model->save();
                    echo CJSON::encode(array('result' => 'ok', 'response' => 'Informaci&oacute;n actualizada'));
                    Yii::app()->end();
                } else {
                    echo CActiveForm::validate($model);
                    Yii::app()->end();
                }
            } catch (Exception $exc) {
                Yii::log($exc->getMessage() . '\n' . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                echo CJSON::encode(array('result' => 'error', 'response' => 'Error al actualizar datos. ' . $exc->getMessage()));
                Yii::app()->end();
            }
        } else {
            echo CJSON::encode(array('result' => 'error', 'response' => 'Solicitud inválida'));
            Yii::app()->end();
        }
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {

        if (Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->user->loginUrl);
            Yii::app()->end();
        }

        if (!Yii::app()->user->checkAccess('PuntoDeVenta_Activo_admin')) {
            $this->render('//site/error', array('code' => '101', 'message' => Yii::app()->params['accessError']));
            Yii::app()->end();
        }

        $model = new Activo('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Activo']))
            $model->attributes = $_GET['Activo'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function actionCargar() {

        if (Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->user->loginUrl);
            Yii::app()->end();
        }

        if (!Yii::app()->user->checkAccess('PuntoDeVenta_Activo_cargar')) {
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
            $archivo = "activos_" . $fecha->format("YmdHis");
            $model->archivo = "$directorio/$archivo." . $uploadedFile->getExtensionName();

            if (!file_exists($directorio)) {
                mkdir($directorio, 0777, true);
            }

            $total['cargado'] = 0;
            $total['total'] = 0;
            $total['creados'] = 0;
            $total['actualizados'] = 0;

            $warnings = "";

            if ($model->validate()) {
                if ($uploadedFile->saveAs($model->archivo)) {
                    $extension['xlsx'] = 'PHPExcel_Reader_Excel2007';
                    $extension['xls'] = 'PHPExcel_Reader_Excel5';
                    $objReader = new $extension[$uploadedFile->getExtensionName()];
                    $objPHPExcel = $objReader->load($model->archivo);
                    $objWorksheet = $objPHPExcel->getSheet(0)->toArray(null, true, true, true);

                    $transaction = Yii::app()->db->beginTransaction();
                    try {
                        foreach ($objWorksheet as $registro => $objCelda) {
                            if ($registro == 1) {
                                continue;
                            }

                            $total['total'] ++;
                            $codigo = trim($objCelda['A']);
                            $referencia = trim($objCelda['B']);
                            $descripcion = trim($objCelda['C']);
                            $observacion = trim($objCelda['D']);
                            $categoria = trim($objCelda['E']);
                            $estado = trim($objCelda['F']);

                            if (!isset(Yii::app()->getModule("puntoventa")->listEstadosActivos[$estado])) {
                                $warnings .= "Error: $registro tiene estado inválido [$estado]<br/>";
                                continue;
                            }

                            $objActivo = Activo::model()->find(array(
                                'condition' => 'Codigo=:codigo',
                                'params' => array(
                                    ':codigo' => $codigo,
                                )
                            ));

                            if ($objActivo === null) {
                                $total['creados'] ++;
                                $objActivo = new Activo;
                                $objActivo->Codigo = $codigo;
                            } else {
                                $total['actualizados'] ++;
                            }

                            $objActivo->IdActivoCategoria = $categoria;
                            $objActivo->Referencia = $referencia;
                            $objActivo->DescripcionActivo = $descripcion;
                            $objActivo->ObservacionActivo = $observacion;
                            $objActivo->Estado = $estado;

                            if ($objActivo->save()) {
                                $total['cargado'] ++;
                            } else {
                                $warnings .= "Error $registro: " . CVarDumper::dumpAsString($objActivo->getErrors()) . "<br/>";
                            }
                        }

                        $transaction->commit();
                        $result = "Archivo $nombreArchivo cargado. <br/>";
                        $result .= "Se cargaron " . $total['cargado'] . "/" . $total['total'] . " registro(s)<br/>";
                        $result .= "Creados: " . $total['creados'] . " Actualizados: " . $total['actualizados'] . "<br/>";

                        Yii::app()->user->setFlash('success', $result);

                        if (!empty($warnings)) {
                            Yii::app()->user->setFlash('warning', $warnings);
                        }
                    } catch (Exception $exc) {
                        try {
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
    public function loadModel($id) {
        $model = Activo::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'La página solicitada no existe.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'cedi-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    protected function gridEstadoActivo($data, $row) {
        return CHtml::dropDownList('select-estado-' . uniqid(), $data->Estado, Yii::app()->getModule("puntoventa")->listEstadosActivos, array('data-role' => 'selectestadoactivo', 'data-id' => $data->IdActivo, 'data-estado' => $data->Estado, 'class' => 'form-control input-sm', 'style' => "text-align:center; width:100px"));
    }

    protected function gridBtnActualizar($data, $row) {
        return CHtml::htmlButton('Actualizar', array('id' => 'btn-update-' . uniqid(), 'data-role' => 'btnactualizaractivo', 'data-id' => $data->IdActivo, 'class' => 'btn btn-primary btn-sm'));
    }

}
