<?php

class BarriosInfluenciaController extends Controller {

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
            
            if (!Yii::app()->user->checkAccess('PuntoDeVenta_BarriosInfluencia_crear')) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'Error: 101 => ' . Yii::app()->params['accessError']));
                Yii::app()->end();
            }

            $render = Yii::app()->getRequest()->getPost('render', false);
            $model = new BarrioInfluencia;

            if ($render) {
                Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
                echo CJSON::encode(array(
                    'result' => 'ok',
                    'response' => array(
                        'msg' => 'Datos cargados para creaci&oacute;n',
                        'form' => $this->renderPartial('_form', array('model' => $model), true, true)
                )));
                Yii::app()->end();
            } else if ($_POST['BarrioInfluencia']) {
                $model->attributes = $_POST['BarrioInfluencia'];

                if ($model->validate()) {
                    try {
                        $model->save();
                        echo CJSON::encode(array('result' => 'ok', 'response' => 'Barrio creado'));
                        Yii::app()->end();
                    } catch (Exception $exc) {
                        Yii::log($exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                        echo CJSON::encode(array('result' => 'error', 'response' => 'Error al crear Barrio. ' . $exc->getMessage()));
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
            
            if (!Yii::app()->user->checkAccess('PuntoDeVenta_BarriosInfluencia_actualizar')) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'Error: 101 => ' . Yii::app()->params['accessError']));
                Yii::app()->end();
            }

            $render = Yii::app()->getRequest()->getPost('render', false);

            if ($render) {
                $pk = Yii::app()->getRequest()->getPost('barrio', null);

                if ($pk === null) {
                    echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta barrio a actualizar'));
                    Yii::app()->end();
                }

                try {
                    $model = BarrioInfluencia::model()->findByPk($pk);

                    if ($model === null) {
                        echo CJSON::encode(array('result' => 'error', 'response' => 'Barrio a actualizar no existe'));
                        Yii::app()->end();
                    }

                    Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
                    echo CJSON::encode(array(
                        'result' => 'ok',
                        'response' => array(
                            'msg' => 'Datos de barrio a actualizar cargados',
                            'form' => $this->renderPartial('_form', array('model' => $model), true, true)
                    )));
                    Yii::app()->end();
                } catch (Exception $exc) {
                    Yii::log($exc->getMessage() . '\n' . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                    echo CJSON::encode(array('result' => 'error', 'response' => 'Error al cargar datos de barrio. ' . $exc->getMessage()));
                    Yii::app()->end();
                }
            } else if ($_POST['BarrioInfluencia']) {
                $form = new BarrioInfluencia('update');
                $form->attributes = $_POST['BarrioInfluencia'];

                try {
                    $model = BarrioInfluencia::model()->findByPk($form->IDBarrio);
                    $model->NombreBarrio = $form->NombreBarrio;

                    if ($model->validate()) {
                        $model->save();
                        echo CJSON::encode(array('result' => 'ok', 'response' => 'Informaci&oacute;n de barrio actualizada'));
                        Yii::app()->end();
                    } else {
                        echo CActiveForm::validate($model);
                        Yii::app()->end();
                    }
                } catch (Exception $exc) {
                    Yii::log($exc->getMessage() . '\n' . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                    echo CJSON::encode(array('result' => 'error', 'response' => 'Error al actualizar datos de barrio. ' . $exc->getMessage()));
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
     * Elimina un modelo en particular. 
     * Imprime JSON con resultado de peticion.
     */
    public function actionEliminar() {
        if (Yii::app()->request->isPostRequest) {

            if (Yii::app()->user->isGuest) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta usuario autenticado, por favor iniciar sesi&oacute;n'));
                Yii::app()->end();
            }
            
            if (!Yii::app()->user->checkAccess('PuntoDeVenta_BarriosInfluencia_eliminar')) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'Error: 101 => ' . Yii::app()->params['accessError']));
                Yii::app()->end();
            }

            $pk = Yii::app()->getRequest()->getPost('barrio', null);

            if ($pk === null) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta barrio a eliminar'));
                Yii::app()->end();
            }

            try {
                $model = BarrioInfluencia::model()->findByPk($pk);

                if ($model === null) {
                    echo CJSON::encode(array('result' => 'error', 'response' => 'Barrio a eliminar no existe'));
                    Yii::app()->end();
                }

                $model->delete();
                echo CJSON::encode(array('result' => 'ok', 'response' => 'Barrio eliminado correctamente'));
                Yii::app()->end();
            } catch (CDbException $exc) {
                Yii::log($exc->getTraceAsString() . '\n' . $exc->getMessage(), CLogger::LEVEL_ERROR, 'application');
                if ($exc->getCode() === 23000) {
                    echo CJSON::encode(array('result' => 'error', 'response' => "Error ( " . $exc->getCode() . "): Error por restricci&oacute;n de relacion"));
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

        if (!Yii::app()->user->checkAccess('PuntoDeVenta_BarriosInfluencia_admin')) {
            $this->render('//site/error', array('code' => '101', 'message' => Yii::app()->params['accessError']));
            Yii::app()->end();
        }

        $model = new BarrioInfluencia('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['BarrioInfluencia']))
            $model->attributes = $_GET['BarrioInfluencia'];

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
        $model = BarrioInfluencia::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'La página solicitada no existe.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'barrio-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
