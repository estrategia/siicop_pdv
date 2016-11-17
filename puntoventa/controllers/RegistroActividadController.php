<?php

class RegistroActividadController extends Controller {

    /**
     * @var string accion por defecto del controlador
     */
    public $defaultAction = 'admin';

    /**
     * Crea un nuevo modelo.
     */
    public function actionVer() {
        if (Yii::app()->request->isPostRequest) {
            
            if (Yii::app()->user->isGuest) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta usuario autenticado, por favor iniciar sesi&oacute;n'));
                Yii::app()->end();
            }
            
            if (!Yii::app()->user->checkAccess('PuntoDeVenta_RegistroActividad_ver')) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'Error: 101 => ' . Yii::app()->params['accessError']));
                Yii::app()->end();
            }
            
            $pk = Yii::app()->getRequest()->getPost('registro', null);

            if ($pk === null) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta registro a visualizar'));
                Yii::app()->end();
            }

            try {

                $model = LogPuntoVenta::model()->findByPk($pk);

                if ($model === null) {
                    echo CJSON::encode(array('result' => 'error', 'response' => 'Registro a visualizar no existe'));
                    Yii::app()->end();
                }

                //Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
                echo CJSON::encode(array(
                    'result' => 'ok',
                    'response' => array(
                        'msg' => 'Registro a visualizar cargado',
                        'form' => $this->renderPartial('_formcambios', array('model' => $model), true, true)
                )));
                Yii::app()->end();
            } catch (Exception $exc) {
                Yii::log($exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                echo CJSON::encode(array('result' => 'error', 'response' => 'Error al consultar registro a visualizar'));
                Yii::app()->end();
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

        if (!Yii::app()->user->checkAccess('PuntoDeVenta_RegistroActividad_admin')) {
            $this -> render( '//site/error', array( 'code' => '101' , 'message' => Yii::app()->params['accessError'] ) );
            Yii::app()->end();
        }
        
        $model = new LogPuntoVenta('search');
        $model->unsetAttributes();
        if (isset($_GET['RegistroActividad']))
            $model->attributes = $_GET['RegistroActividad'];
        
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
        $model = LogPuntoVenta::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'La página solicitada no existe.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'registro-actividad-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
