<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ArchivosController
 *
 * @author Miguel Angel Sanchez Montiel
 */
class ArchivosController extends CController {

    public function actions() {
        return array(
            'connector' => array(
                'class' => 'ext.elFinder.ElFinderConnectorAction',
                'settings' => array(
                    'root' => Yii::getPathOfAlias('webroot') . Yii::app()->controller->module->uploadImg,
                    'URL' => Yii::app()->baseUrl . Yii::app()->controller->module->uploadImg,
                    'rootAlias' => 'Inicio',
                    'lang' => 'es',
                    'mimeDetect' => 'none',
                    'uploadDeny' => array('application/x-msdownload', 'application/x-ms-application')
                )
            ),
        );
    }

    public function actionIndex() {
        /*
        if (Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->user->loginUrl);
            Yii::app()->end();
        }

        if (!Yii::app()->user->checkAccess('index')) {
            $this -> render( '//site/error', array( 'code' => '101' , 'message' => Yii::app()->params['accessError'] ) );
            Yii::app()->end();
        }
         * 
         */
        Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
        $this->render("index");
    }

}

?>
