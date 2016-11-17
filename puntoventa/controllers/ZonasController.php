<?php

class ZonasController extends Controller {

    /**
     * @var string accion por defecto del controlador
     */
    public $defaultAction = 'admin';

    /**
     * Crea un nuevo modelo.
     */
    public function actionCrear() {
        if (Yii::app()->request->isPostRequest) {

            if (Yii::app()->user->isGuest) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta usuario autenticado, por favor iniciar sesi&oacute;n'));
                Yii::app()->end();
            }

            if (!Yii::app()->user->checkAccess('PuntoDeVenta_Zonas_crear')) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'Error: 101 => ' . Yii::app()->params['accessError']));
                Yii::app()->end();
            }

            $render = Yii::app()->getRequest()->getPost('render', false);
            $model = new Zona;

            if ($render) {
                Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
                echo CJSON::encode(array(
                    'result' => 'ok',
                    'response' => array(
                        'msg' => 'Datos cargados para creaci&oacute;n',
                        'form' => $this->renderPartial('_form', array('model' => $model, 'telefonos' => array()), true, true)
                )));
                Yii::app()->end();
            } else if ($_POST['Zona']) {
                $model->attributes = $_POST['Zona'];

                if ($model->validate()) {

                    $transaction = Yii::app()->db->beginTransaction();

                    try {
                        $model->save();

                        if (isset($_POST['TelefonosZona'])) {
                            $telefonosForm = $_POST['TelefonosZona'];

                            foreach ($telefonosForm as $indice => $telefonoForm) {
                                $telefono = new TelefonosZona('Add');
                                $telefono->attributes = $telefonoForm;
                                $telefono->NumeroTelefono = trim($telefono->NumeroTelefono);
                                $telefono->IDZona = $model->IDZona;

                                if (!empty($telefono->IDTelefonoZona)) {
                                    $telefono->isNewRecord = false;
                                }

                                if ($telefono->NumeroTelefono == null || empty($telefono->NumeroTelefono)) {
                                    if (!$telefono->isNewRecord) {
                                        $telefono->delete();
                                    }
                                } else {
                                    if ($telefono->validate()) {
                                        $telefono->save();
                                    } else {
                                        $transaction->rollBack();
                                        echo CJSON::encode(array(
                                            'result' => 'invalid',
                                            'response' => array(
                                                'item' => $indice,
                                                'errors' => CActiveForm::validate($telefono)
                                        )));
                                        Yii::app()->end();
                                    }
                                }
                            }
                        }

                        $transaction->commit();
                        echo CJSON::encode(array('result' => 'ok', 'response' => 'Zona creada'));
                        Yii::app()->end();
                    } catch (Exception $exc) {
                        Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString() . '\n' . $exc->getMessage(), CLogger::LEVEL_ERROR, 'application');

                        try {
                            $transaction->rollBack();
                            echo CJSON::encode(array('result' => 'error', 'response' => 'Error [001] al crear Zona. [1]' . $exc->getMessage()));
                            Yii::app()->end();
                        } catch (Exception $txexc) {
                            Yii::log($txexc->getTraceAsString() . '\n' . $txexc->getMessage(), CLogger::LEVEL_ERROR, 'application');
                            echo CJSON::encode(array('result' => 'error', 'response' => 'Error [002] al crear Zona. [1] ' . $exc->getMessage() . ' [2] ' . $txexc->getMessage()));
                            Yii::app()->end();
                        }
                    }
                } else {
                    echo CActiveForm::validate($model);
                    Yii::app()->end();
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

    /**
     * Actualiza un modelo particular.
     */
    public function actionActualizar() {
        if (Yii::app()->request->isPostRequest) {

            if (Yii::app()->user->isGuest) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta usuario autenticado, por favor iniciar sesi&oacute;n'));
                Yii::app()->end();
            }

            if (!Yii::app()->user->checkAccess('PuntoDeVenta_Zonas_actualizar')) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'Error: 101 => ' . Yii::app()->params['accessError']));
                Yii::app()->end();
            }

            $render = Yii::app()->getRequest()->getPost('render', false);

            if ($render) {
                $pk = Yii::app()->getRequest()->getPost('zona', null);

                if ($pk === null) {
                    echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta zona a actualizar'));
                    Yii::app()->end();
                }

                try {
                    $model = Zona::model()->findByPk($pk);

                    if ($model === null) {
                        echo CJSON::encode(array('result' => 'error', 'response' => 'Zona a actualizar no existe'));
                        Yii::app()->end();
                    }

                    Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
                    echo CJSON::encode(array(
                        'result' => 'ok',
                        'response' => array(
                            'msg' => 'Datos de zona a actualizar cargados',
                            'form' => $this->renderPartial('_form', array('model' => $model, 'telefonos' => $model->telefonos), true, true)
                    )));
                    Yii::app()->end();
                } catch (Exception $exc) {
                    Yii::log($exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                    echo CJSON::encode(array('result' => 'error', 'response' => 'Error al cargar datos de zona'));
                    Yii::app()->end();
                }
            } else if ($_POST['Zona']) {
                $form = new Zona('update');
                $form->attributes = $_POST['Zona'];

                $transaction = Yii::app()->db->beginTransaction();
                try {
                    $model = Zona::model()->findByPk($form->IDZona);
                    $model->IDSede = $form->IDSede;
                    $model->NombreZona = $form->NombreZona;
                    $model->DireccionZona = $form->DireccionZona;
                    $model->CelularZona = $form->CelularZona;
                    $model->eMailZona = $form->eMailZona;
                    $model->CedulaDirectorZona = $form->CedulaDirectorZona;
                    $model->eMailDirectorZona = $form->eMailDirectorZona;
                    $model->CedulaAuxiliarZona = $form->CedulaAuxiliarZona;
                    $model->IndicativoTelefonoZona = $form->IndicativoTelefonoZona;

                    if (isset($_POST['TelefonosZona'])) {
                        $telefonosForm = $_POST['TelefonosZona'];

                        foreach ($telefonosForm as $indice => $telefonoForm) {
                            $telefono = new TelefonosZona('Add');
                            $telefono->attributes = $telefonoForm;
                            $telefono->NumeroTelefono = trim($telefono->NumeroTelefono);
                            $telefono->IDZona = $model->IDZona;

                            if (!empty($telefono->IDTelefonoZona)) {
                                $telefono->isNewRecord = false;
                            }

                            if ($telefono->NumeroTelefono == null || empty($telefono->NumeroTelefono)) {
                                if (!$telefono->isNewRecord) {
                                    $telefono->delete();
                                }
                            } else {
                                if ($telefono->validate()) {
                                    $telefono->save();
                                } else {
                                    $transaction->rollBack();
                                    echo CJSON::encode(array(
                                        'result' => 'invalid',
                                        'response' => array(
                                            'item' => $indice,
                                            'errors' => CActiveForm::validate($telefono)
                                    )));
                                    Yii::app()->end();
                                }
                            }
                        }
                    }

                    if ($model->validate()) {
                        $model->save();
                        $transaction->commit();
                        echo CJSON::encode(array('result' => 'ok', 'response' => 'Informaci&oacute;n de zona actualizada'));
                        Yii::app()->end();
                    } else {
                        echo CActiveForm::validate($model);
                        Yii::app()->end();
                    }
                } catch (Exception $exc) {
                    Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString() . '\n' . $exc->getMessage(), CLogger::LEVEL_ERROR, 'application');

                    try {
                        $transaction->rollBack();
                        echo CJSON::encode(array('result' => 'error', 'response' => 'Error [001] al actualizar Zona. [1]' . $exc->getMessage()));
                        Yii::app()->end();
                    } catch (Exception $txexc) {
                        Yii::log($txexc->getTraceAsString() . '\n' . $txexc->getMessage(), CLogger::LEVEL_ERROR, 'application');
                        echo CJSON::encode(array('result' => 'error', 'response' => 'Error [002] al actualizar Zona. [1] ' . $exc->getMessage() . ' [2] ' . $txexc->getMessage()));
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

    /**
     * Elimina un modelo en particular. 
     * Imprime JSON con resultado de peticion.
     */
    public function actionEliminar() {
        if (Yii::app()->request->isPostRequest) {

            if (Yii::app()->user->isGuest) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta usuario autenticado, por favor iniciar sesi&oacute;n'));
                Yii::app()->end();
            }

            if (!Yii::app()->user->checkAccess('PuntoDeVenta_Zonas_eliminar')) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'Error: 101 => ' . Yii::app()->params['accessError']));
                Yii::app()->end();
            }

            $pk = Yii::app()->getRequest()->getPost('zona', null);

            if ($pk === null) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta zona a eliminar'));
                Yii::app()->end();
            }

            try {
                $model = Zona::model()->findByPk($pk);

                if ($model === null) {
                    echo CJSON::encode(array('result' => 'error', 'response' => 'Zona a eliminar no existe'));
                    Yii::app()->end();
                }

                $model->delete();
                echo CJSON::encode(array('result' => 'ok', 'response' => 'Zona eliminada correctamente'));
                Yii::app()->end();
            } catch (CDbException $exc) {
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
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {

        if (Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->user->loginUrl);
            Yii::app()->end();
        }

        if (!Yii::app()->user->checkAccess('PuntoDeVenta_Zonas_admin')) {
            $this->render('//site/error', array('code' => '101', 'message' => Yii::app()->params['accessError']));
            Yii::app()->end();
        }

        $model = new Zona('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Zona']))
            $model->attributes = $_GET['Zona'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Zona::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'La página solicitada no existe.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'zonas-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
