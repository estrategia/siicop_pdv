<?php

class TelefonoPuntoVentaController extends Controller {

    /**
     * Crea un nuevo modelo.
     */
    public function actionCrear() {
        if (Yii::app()->request->isPostRequest) {

            if (Yii::app()->user->isGuest) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta usuario autenticado, por favor iniciar sesi&oacute;n'));
                Yii::app()->end();
            }

            if (!Yii::app()->user->checkAccess('PuntoDeVenta_TelefonoPuntoVenta_crear')) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'Error: 101 => ' . Yii::app()->params['accessError']));
                Yii::app()->end();
            }

            $render = Yii::app()->getRequest()->getPost('render', false);
            $model = new TelefonosPuntoVenta;

            if ($render) {
                $pk = Yii::app()->getRequest()->getPost('puntoventa');

                if ($pk === null) {
                    echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta punto de venta a adicionar tel&eacute;fono'));
                    Yii::app()->end();
                }

                $puntoVenta = PuntoVenta::model()->findByPk($pk);

                if ($puntoVenta === null) {
                    echo CJSON::encode(array('result' => 'error', 'response' => 'Punto de venta no existe'));
                    Yii::app()->end();
                }

                $model->IDPuntoDeVenta = $puntoVenta->IDPuntoDeVenta;

                Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
                Yii::app()->clientScript->registerScript('prettyphoto', "$('" . Yii::app()->controller->module->elementPretty . "').prettyPhoto(" . CJSON::encode(Yii::app()->controller->module->optPretty) . ");", CClientScript::POS_END);
                echo CJSON::encode(array(
                    'result' => 'ok',
                    'response' => array(
                        'msg' => 'Datos cargados para creaci&oacute;n',
                        'form' => $this->renderPartial('_form', array('model' => $model), true, true)
                )));
                Yii::app()->end();
            } else if ($_POST['TelefonosPuntoVenta']) {
                $model->attributes = $_POST['TelefonosPuntoVenta'];

                if ($model->validate()) {
                    try {
                        if ($model->save()) {
                            echo CJSON::encode(array('result' => 'ok', 'response' => 'Tel&eacute;fono creado'));
                            Yii::app()->end();
                        } else {
                            echo CActiveForm::validate($model);
                            Yii::app()->end();
                        }
                    } catch (Exception $exc) {
                        Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                        echo CJSON::encode(array('result' => 'error', 'response' => 'Error al crear tel&eacute;fono. ' . $exc->getMessage()));
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

            if (!Yii::app()->user->checkAccess('PuntoDeVenta_TelefonoPuntoVenta_actualizar')) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'Error: 101 => ' . Yii::app()->params['accessError']));
                Yii::app()->end();
            }

            $render = Yii::app()->getRequest()->getPost('render', false);

            if ($render) {
                $pk = Yii::app()->getRequest()->getPost('telefono', null);

                if ($pk === null) {
                    echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta tel&eacute;fono a actualizar'));
                    Yii::app()->end();
                }

                try {
                    $model = TelefonosPuntoVenta::model()->findByPk($pk);

                    if ($model === null) {
                        echo CJSON::encode(array('result' => 'error', 'response' => 'Tel&eacute;fono a actualizar no existe'));
                        Yii::app()->end();
                    }

                    Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
                    Yii::app()->clientScript->registerScript('prettyphoto', "$('" . Yii::app()->controller->module->elementPretty . "').prettyPhoto(" . CJSON::encode(Yii::app()->controller->module->optPretty) . ");", CClientScript::POS_END);
                    echo CJSON::encode(array(
                        'result' => 'ok',
                        'response' => array(
                            'msg' => 'Datos de tel&eacute;fono a actualizar cargados',
                            'form' => $this->renderPartial('_form', array('model' => $model), true, true)
                    )));
                    Yii::app()->end();
                } catch (Exception $exc) {
                    Yii::log($exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                    echo CJSON::encode(array('result' => 'error', 'response' => 'Error al cargar datos de tel&eacute;fono'));
                    Yii::app()->end();
                }
            } else if ($_POST['TelefonosPuntoVenta']) {
                $form = new TelefonosPuntoVenta('update');
                $form->attributes = $_POST['TelefonosPuntoVenta'];

                try {
                    $model = TelefonosPuntoVenta::model()->findByPk($form->IDTelefonoPuntoDeVenta);
                    $modelini = CJSON::encode($model);
                    $model->NumeroTelefono = $form->NumeroTelefono;
                    $model->IndicativoTelefono = $form->IndicativoTelefono;

                    if ($model->validate()) {

                        try {
                            if ($model->save()) {
                                echo CJSON::encode(array('result' => 'ok', 'response' => 'Informaci&oacute;n de tel&eacute;fono actualizada'));
                                Yii::app()->end();
                            } else {
                                echo CActiveForm::validate($model);
                                Yii::app()->end();
                            }
                        } catch (Exception $exc) {
                            Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                            echo CJSON::encode(array('result' => 'error', 'response' => 'Error al actualizar datos. ' . $exc->getMessage()));
                            Yii::app()->end();
                        }
                    } else {
                        echo CActiveForm::validate($model);
                        Yii::app()->end();
                    }
                } catch (Exception $exc) {
                    Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                    echo CJSON::encode(array('result' => 'error', 'response' => 'Error al extraer datos de tel&eacute;fono.' . $exc->getMessage()));
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
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    /*public function actionEliminar() {
        if (Yii::app()->request->isPostRequest) {

            if (Yii::app()->user->isGuest) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta usuario autenticado, por favor iniciar sesi&oacute;n'));
                Yii::app()->end();
            }

            if (!Yii::app()->user->checkAccess('PuntoDeVenta_TelefonoPuntoVenta_eliminar')) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'Error: 101 => ' . Yii::app()->params['accessError']));
                Yii::app()->end();
            }

            $pk = Yii::app()->getRequest()->getPost('telefono', null);

            if ($pk === null) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta registro a eliminar'));
                Yii::app()->end();
            }

            try {
                $model = TelefonosPuntoVenta::model()->findByPk($pk);

                if ($model === null) {
                    echo CJSON::encode(array('result' => 'error', 'response' => 'Registro a eliminar no existe'));
                    Yii::app()->end();
                }

                if ($model->delete()) {
                    echo CJSON::encode(array('result' => 'ok', 'response' => 'Registro eliminado correctamente'));
                    Yii::app()->end();
                } else {
                    echo CJSON::encode(array('result' => 'ok', 'response' => '[001] Error al eliminar registro'));
                    Yii::app()->end();
                }
            } catch (CDbException $exc) {
                Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                echo CJSON::encode(array('result' => 'ok', 'response' => '[011] Error al eliminar registro.' . $exc->getMessage()));
                Yii::app()->end();
            }
        } else {
            echo CJSON::encode(array('result' => 'error', 'response' => 'Solicitud inv&aacute;lida'));
            Yii::app()->end();
        }
    }*/
    
    public function actionEliminar($id) {
        if (Yii::app()->request->isPostRequest) {
            if (Yii::app()->user->isGuest) {
                throw new CHttpException(404, 'No se detecta usuario autenticado, por favor iniciar sesi&oacute;n.');
            }

            if (!Yii::app()->user->checkAccess('PuntoDeVenta_TelefonoPuntoVenta_eliminar')) {
                throw new CHttpException(101, Yii::app()->params['accessError']);
            }
            
            $model = $this->loadModel($id);
            $model->delete();

            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else {
            throw new CHttpException(400, 'Solicitud inv&aacute;lida.');
        }
    }

    /**
     * Manages all models.
     */
    /*public function actionAdmin($pv) {

        if (Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->user->loginUrl);
            Yii::app()->end();
        }

        $model = new TelefonosPuntoVenta('search');
        $model->unsetAttributes();
        $model->IDPuntoDeVenta = $pv;

        if (isset($_GET['TelefonosPuntoVenta']))
            $model->attributes = $_GET['TelefonosPuntoVenta'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }*/

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = TelefonosPuntoVenta::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'Télefono no existe.');
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
