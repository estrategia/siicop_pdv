<?php

class DefaultController extends Controller {

    /**
     * Accion por defecto 'index'. Visualiza panel principal
     */
    public function actionIndex() {

        if (Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->user->loginUrl);
            Yii::app()->end();
        }

        if (!Yii::app()->user->checkAccess('PuntoDeVenta_Default_index')) {
            $this -> render( '//site/error', array( 'code' => '101' , 'message' => Yii::app()->params['accessError'] ) );
            Yii::app()->end();
        }

        $this->render('index');
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