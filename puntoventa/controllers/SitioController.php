<?php

class SitioController extends Controller {

    /**
     * Accion por defecto 'index'. Visualiza panel principal
     */
    public function actionIndex() {

        if (Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->user->loginUrl);
            Yii::app()->end();
        }

        if (!Yii::app()->user->checkAccess('puntoventa_sitio_index')) {
            $this->render('/sitio/erroracceso', array('error' => Yii::app()->params->accessError));
            Yii::app()->end();
        }

        $this->render('index');
    }
    
    /**
     * Accion que visualiza panel para administracion de tablas
     * relacionadas de los puntos de venta.
     */
    public function actionTablas() {
        
        if (Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->user->loginUrl);
            Yii::app()->end();
        }

        if (!Yii::app()->user->checkAccess('puntoventa_sitio_tablas')) {
            $this->render('/sitio/erroracceso', array('error' => Yii::app()->params->accessError));
            Yii::app()->end();
        }
        
        $this->render('tablas');
    }

    /**
     * Accion que maneja excepciones externas, 
     * visualiza pagina indicando error.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

}