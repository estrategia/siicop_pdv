<?php

class SolicitudActivosController extends Controller {
    /**
     * @var string accion por defecto del controlador
     */
    //public $defaultAction = 'admin';

    /**
     * Manages all models.
     */
    public function actionIndex() {

        if (Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->user->loginUrl);
            Yii::app()->end();
        }

        if (!Yii::app()->user->checkAccess('PuntoDeVenta_SolicitudActivos_index')) {
            $this->render('//site/error', array('code' => '101', 'message' => Yii::app()->params['accessError']));
            Yii::app()->end();
        }

        $puntoventa = PuntoVenta::model()->find(array(
            'condition' => 'Estado=:estado AND (CedulaAdministrador=:cedula OR CedulaSubAdministrador=:cedula)',
            'params' => array(':estado'=>1, ':cedula' => Yii::app()->user->name)
        ));

        if ($puntoventa == null) {
            throw new CHttpException(403, 'Solicitud denegada. No hay punto de venta asociado a usuario.');
        }

        $model = new ActivosPuntoVenta('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['ActivosPuntoVenta']))
            $model->attributes = $_GET['ActivosPuntoVenta'];

        $model->IDPuntoDeVenta = $puntoventa->IDPuntoDeVenta;

        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function actionSolicitar() {
        if (Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->user->loginUrl);
            Yii::app()->end();
        }

        if (!Yii::app()->user->checkAccess('PuntoDeVenta_SolicitudActivos_index')) {
            $this->render('//site/error', array('code' => '101', 'message' => Yii::app()->params['accessError']));
            Yii::app()->end();
        }
        
        $puntoventa = PuntoVenta::model()->find(array(
            'condition' => 'Estado=:estado AND (CedulaAdministrador=:cedula OR CedulaSubAdministrador=:cedula)',
            'params' => array(':estado'=>1, ':cedula' => Yii::app()->user->name)
        ));

        if ($puntoventa == null) {
            throw new CHttpException(403, 'Solicitud denegada. No hay punto de venta asociado a usuario.');
        }

        if (isset($_POST['SolicitudActivo'])) {
            $listActivos = $_POST['SolicitudActivo'];
            $models = array();
            $errors = array();

            foreach ($listActivos as $idx => $activoPdv) {
                $model = new ActivosPuntoVenta;
                $model->attributes = $activoPdv;
                $model->IdentificacionSolicitante = Yii::app()->user->name;
                $model->IDPuntoDeVenta = $puntoventa->IDPuntoDeVenta;
                $model->Estado = 0;
                $models[] = $model;

                if (!$model->validate()) {
                    $errors[] = array(
                        'item' => $idx,
                        'errors' => CActiveForm::validate($model)
                    );
                }
            }

            if (!empty($errors)) {
                echo CJSON::encode($errors);
                Yii::app()->end();
            }

            foreach ($models as $model) {
                $model->save();
            }

            //Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
            echo CJSON::encode(array('result' => 'ok', 'response' => array(
                'msg' => 'Solicitud realizada',
                'itemHTML' => $this->renderPartial('_item', array('listData' => Activo::listData(), 'n' => 0), true, false),
                'nItem' => 0)));
            Yii::app()->end();
        } else {
            $this->render('solicitar', array('listData' => Activo::listData(), 'n' => 0));
        }
    }

    public function actionItem() {
        if (!Yii::app()->request->isPostRequest) {
            echo CJSON::encode(array('result' => 'error', 'response' => 'Solicitud inválida'));
            Yii::app()->end();
        }

        if (Yii::app()->user->isGuest) {
            echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta usuario autenticado, por favor iniciar sesi&oacute;n'));
            Yii::app()->end();
        }

        $nItem = Yii::app()->getRequest()->getPost('nitem');

        if ($nItem == null) {
            echo CJSON::encode(array('result' => 'error', 'response' => 'Solicitud inválida'));
            Yii::app()->end();
        }
        $nItem++;

        //Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
        echo CJSON::encode(array('result' => 'ok', 'response' => array(
                'itemHTML' => $this->renderPartial('_item', array('listData' => Activo::listData(), 'n' => $nItem), true, false),
                'nItem' => $nItem)));
        Yii::app()->end();
    }

}
