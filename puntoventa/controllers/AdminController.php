<?php

class AdminController extends Controller {

    /**
     * Accion por defecto 'index'. Visualiza panel principal
     */
    public function actionIndex() {

        if (Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->user->loginUrl);
            Yii::app()->end();
        }

        if (!Yii::app()->user->checkAccess('PuntoDeVenta_Admin_index')) {
            $this -> render( '//site/error', array( 'code' => '101' , 'message' => Yii::app()->params['accessError'] ) );
            Yii::app()->end();
        }
        
        $this->render('index');
    }
}