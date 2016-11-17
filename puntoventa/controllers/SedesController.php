<?php

class SedesController extends Controller {

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
            
            if (!Yii::app()->user->checkAccess('PuntoDeVenta_Sedes_crear')) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'Error: 101 => ' . Yii::app()->params['accessError']));
                Yii::app()->end();
            }

            $render = Yii::app()->getRequest()->getPost('render', false);
            $model = new Sede;

            if ($render) {
                Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
                echo CJSON::encode(array(
                    'result' => 'ok',
                    'response' => array(
                        'msg' => 'Datos cargados para sede',
                        'form' => $this->renderPartial('_form', array('model' => $model), true, true)
                )));
                Yii::app()->end();
            } else if ($_POST['Sede']) {
                $model->attributes = $_POST['Sede'];

                if ($model->validate()) {
                    try {
                        $model->save();
                        echo CJSON::encode(array('result' => 'ok', 'response' => 'Sede creada'));
                        Yii::app()->end();
                    } catch (CDbException $exc) {
                        Yii::log($exc->getTraceAsString() . '\n' . $exc->getMessage(), CLogger::LEVEL_ERROR, 'application');
                        if ($exc->getCode() === 23000) {
                            echo CJSON::encode(array('result' => 'error', 'response' => "Error ( " . $exc->getCode() . "): Error por restricci&oacute;n de unicidad de relaci&oacute;n"));
                            Yii::app()->end();
                        } else {
                            echo CJSON::encode(array('result' => 'error', 'response' => "Error ( " . $exc->getCode() . "): " . $exc->getMessage()));
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
            
            if (!Yii::app()->user->checkAccess('PuntoDeVenta_Sedes_actualizar')) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'Error: 101 => ' . Yii::app()->params['accessError']));
                Yii::app()->end();
            }

            $render = Yii::app()->getRequest()->getPost('render', false);

            if ($render) {
                $pk = Yii::app()->getRequest()->getPost('sede', null);

                if ($pk === null) {
                    echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta tipo negocio a actualizar'));
                    Yii::app()->end();
                }

                try {
                    $model = Sede::model()->findByPk($pk);

                    if ($model === null) {
                        echo CJSON::encode(array('result' => 'error', 'response' => 'Sede a actualizar no existe'));
                        Yii::app()->end();
                    }

                    //Yii::app()->clientScript->scriptMap['jquery.js'] = false;
                    Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
                    echo CJSON::encode(array(
                        'result' => 'ok',
                        'response' => array(
                            'msg' => 'Datos de sede a actualizar cargados',
                            'form' => $this->renderPartial('_form', array('model' => $model), true, true)
                    )));
                    Yii::app()->end();
                } catch (Exception $exc) {
                    Yii::log($exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                    echo CJSON::encode(array('result' => 'error', 'response' => 'Error al cargar datos de sede'));
                    Yii::app()->end();
                }
            } else if ($_POST['Sede']) {
                $form = new Sede('update');
                $form->attributes = $_POST['Sede'];

                try {
                    $model = Sede::model()->findByPk($form->IDSede);
                    $model->CodigoSede = $form->CodigoSede;
                    $model->NombreSede = $form->NombreSede;
                    $model->CedulaGerenteOperativo = $form->CedulaGerenteOperativo;
                    $model->CelularSede = $form->CelularSede;
                    $model->DireccionSede = $form->DireccionSede;
                    $model->TelefonoSede = $form->TelefonoSede;
                    $model->IndicativoTelefonoSede = $form->IndicativoTelefonoSede;

                    if ($model->validate()) {
                        $model->save();
                        echo CJSON::encode(array('result' => 'ok', 'response' => 'Informaci&oacute;n de sede actualizada'));
                        Yii::app()->end();
                    } else {
                        echo CActiveForm::validate($model);
                        Yii::app()->end();
                    }
                } catch (CDbException $exc) {
                    Yii::log($exc->getTraceAsString() . '\n' . $exc->getMessage(), CLogger::LEVEL_ERROR, 'application');
                    if ($exc->getCode() === 23000) {
                        echo CJSON::encode(array('result' => 'error', 'response' => "Error ( " . $exc->getCode() . "): Error por restricci&oacute;n de unicidad de relaci&oacute;n"));
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
            
            if (!Yii::app()->user->checkAccess('PuntoDeVenta_Sedes_eliminar')) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'Error: 101 => ' . Yii::app()->params['accessError']));
                Yii::app()->end();
            }

            $pk = Yii::app()->getRequest()->getPost('sede', null);

            if ($pk === null) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta sede a eliminar'));
                Yii::app()->end();
            }

            try {
                $model = Sede::model()->findByPk($pk);

                if ($model === null) {
                    echo CJSON::encode(array('result' => 'error', 'response' => 'Sede a eliminar no existe'));
                    Yii::app()->end();
                }

                $model->delete();
                echo CJSON::encode(array('result' => 'ok', 'response' => 'Sede eliminado correctamente'));
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

        if (!Yii::app()->user->checkAccess('PuntoDeVenta_Sedes_admin')) {
            $this->render('//site/error', array('code' => '101', 'message' => Yii::app()->params['accessError']));
            Yii::app()->end();
        }

        $model = new Sede('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Sede']))
            $model->attributes = $_GET['Sede'];

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
        $model = Sede::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'La página solicitada no existe.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'sedes-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
