<?php

class CompetenciaPuntoVentaController extends Controller {

    /**
     * Crea un nuevo modelo.
     */
    public function actionCrear() {
        if (Yii::app()->request->isPostRequest) {

            if (Yii::app()->user->isGuest) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta usuario autenticado, por favor iniciar sesi&oacute;n'));
                Yii::app()->end();
            }

            if (!Yii::app()->user->checkAccess('PuntoDeVenta_CompetenciaPuntoVenta_crear')) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'Error: 101 => ' . Yii::app()->params['accessError']));
                Yii::app()->end();
            }

            $render = Yii::app()->getRequest()->getPost('render', false);
            $model = new CompetenciaPuntoVenta;

            if ($render) {
                $pk = Yii::app()->getRequest()->getPost('puntoventa');

                if ($pk === null) {
                    echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta punto de venta'));
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
                        'form' => $this->renderPartial('_form', array('model' => $model, 'nItem' => 0), true, true)
                )));
                Yii::app()->end();
            } else if (isset($_POST['CompetenciasPuntoVenta'])) {
                $competencias = $_POST['CompetenciasPuntoVenta'];
                $models = array();

                foreach ($competencias as $indice => $competencia) {
                    $model = new CompetenciaPuntoVenta;
                    $model->attributes = $competencia;

                    if (!$model->validate()) {
                        echo CJSON::encode(array(
                            'result' => 'invalid',
                            'response' => array(
                                'item' => $indice,
                                'errors' => CActiveForm::validate($model)
                        )));
                        Yii::app()->end();
                    }

                    $models[] = $model;
                }
                
                $transaction = Yii::app()->db->beginTransaction();

                try {
                    foreach ($models as $indice => $model) {
                        if (!$model->save()) {
                            $transaction->rollBack();
                            echo CJSON::encode(array(
                                'result' => 'invalid',
                                'response' => array(
                                    'item' => $indice,
                                    'errors' => CActiveForm::validate($model)
                            )));
                            Yii::app()->end();
                        }
                    }

                    $transaction->commit();
                    echo CJSON::encode(array('result' => 'ok', 'response' => 'Creado OK'));
                    Yii::app()->end();
                } catch (CDbException $exc) {
                    Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString() . '\n' . $exc->getMessage(), CLogger::LEVEL_ERROR, 'application');

                    try {
                        $transaction->rollBack();
                    } catch (Exception $txexc) {
                        Yii::log($txexc->getTraceAsString() . '\n' . $txexc->getMessage(), CLogger::LEVEL_ERROR, 'application');
                    }

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
        } else {
            echo CJSON::encode(array('result' => 'error', 'response' => 'Solicitud inv&aacute;lida'));
            Yii::app()->end();
        }
    }

    public function actionFormItem() {
        if (Yii::app()->request->isPostRequest) {
            $pk = Yii::app()->getRequest()->getPost('puntoventa');

            if ($pk === null) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta punto de venta'));
                Yii::app()->end();
            }

            $puntoVenta = PuntoVenta::model()->findByPk($pk);

            if ($puntoVenta === null) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'Punto de venta no existe'));
                Yii::app()->end();
            }

            $items = Yii::app()->getRequest()->getPost('items', null);

            if ($items === null) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta numero de items'));
                Yii::app()->end();
            }

            $items++;

            $model = new CompetenciaPuntoVenta;
            $model->IDPuntoDeVenta = $puntoVenta->IDPuntoDeVenta;

            //Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
            //Yii::app()->clientScript->registerScript('prettyphoto', "$('" . Yii::app()->controller->module->elementPretty . "').prettyPhoto(" . CJSON::encode(Yii::app()->controller->module->optPretty) . ");", CClientScript::POS_END);
            echo CJSON::encode(array(
                'result' => 'ok',
                'response' => array(
                    'msg' => 'Datos cargados para creaci&oacute;n',
                    'nItem' => $items,
                    'form' => $this->renderPartial('_formitem', array('model' => $model, 'nItem' => $items), true)
            )));
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

            if (!Yii::app()->user->checkAccess('PuntoDeVenta_CompetenciaPuntoVenta_eliminar')) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'Error: 101 => ' . Yii::app()->params['accessError']));
                Yii::app()->end();
            }

            $puntoventa = Yii::app()->getRequest()->getPost('puntoventa', null);

            if ($puntoventa === null || empty($puntoventa)) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta punto de venta'));
                Yii::app()->end();
            }

            $competencia = Yii::app()->getRequest()->getPost('competencia', null);

            if ($competencia === null || empty($competencia)) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta competencia'));
                Yii::app()->end();
            }

            try {
                $model = $this->loadModel($puntoventa, $competencia);

                if ($model === null) {
                    echo CJSON::encode(array('result' => 'error', 'response' => 'Registro a eliminar no existe.'));
                    Yii::app()->end();
                }

                if ($model->delete()) {
                    echo CJSON::encode(array('result' => 'ok', 'response' => 'Registro eliminado correctamente'));
                    Yii::app()->end();
                } else {
                    echo CJSON::encode(array('result' => 'ok', 'response' => '[001] Error al eliminar registro'));
                    Yii::app()->end();
                }
            } catch (Exception $exc) {
                Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');

                echo CJSON::encode(array('result' => 'ok', 'response' => '[011] Error al eliminar registro.' . $exc->getMessage()));
                Yii::app()->end();
            }
        } else {
            echo CJSON::encode(array('result' => 'error', 'response' => 'Solicitud inv&aacute;lida'));
            Yii::app()->end();
        }
    }*/
    
    public function actionEliminar($id, $idpv) {
        if (Yii::app()->request->isPostRequest) {
            if (Yii::app()->user->isGuest) {
                throw new CHttpException(404, 'No se detecta usuario autenticado, por favor iniciar sesi&oacute;n.');
            }

            if (!Yii::app()->user->checkAccess('PuntoDeVenta_CompetenciaPuntoVenta_eliminar')) {
                throw new CHttpException(101, Yii::app()->params['accessError']);
            }
            
            $model = $this->loadModel($idpv, $id);
            $model->delete();

            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else {
            throw new CHttpException(400, 'Solicitud inv&aacute;lida.');
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($idpuntoventa, $idcompetencia) {
        $model = CompetenciaPuntoVenta::model()->findByPk(array('IDPuntoDeVenta' => $idpuntoventa, 'IDCompetencia' => $idcompetencia));

        if ($model === null)
            throw new CHttpException(404, 'La página solicitada no existe.');
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
