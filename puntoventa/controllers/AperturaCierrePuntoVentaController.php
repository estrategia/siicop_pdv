<?php

class AperturaCierrePuntoVentaController extends Controller {
    /**
     * @var string layout por defecto para las vistas
     */
    //public $layout = 'panel';

    /**
     * @var string accion por defecto del controlador
     */
    public $defaultAction = 'admin';

    public function actionTipoAutocomplete() {
        $term = null;

        if (Yii::app()->request->isPostRequest)
            $term = isset($_POST['term']) ? trim($_POST['term']) : null;
        else
            $term = isset($_GET['term']) ? trim($_GET['term']) : null;

        $results = array();

        if ($term !== null && trim($term) != '') {
            $term = trim($term);

            $criteria = new CDbCriteria;
            $criteria->order = 't.NombreTipoAperturaCierre';
            $criteria->condition = 't.NombreTipoAperturaCierre LIKE :term';
            $criteria->distinct = true;
            $criteria->params = array('term' => "%$term%");
            $criteria->limit = 20;

            $models = TipoAperturaCierre::model()->findAll($criteria);

            foreach ($models as $model) {
                $results[] = array(
                    'label' => $model->NombreTipoAperturaCierre,
                    'value' => $model->IDTipoAperturaCierre,
                );
            }
        }

        echo CJSON::encode($results);
        Yii::app()->end();
    }

    /**
     * Crea un nuevo modelo.
     */
    public function actionCrear() {
        if (Yii::app()->request->isPostRequest) {

            if (Yii::app()->user->isGuest) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta usuario autenticado, por favor iniciar sesi&oacute;n'));
                Yii::app()->end();
            }

            if (!Yii::app()->user->checkAccess('PuntoDeVenta_AperturaCierrePuntoDeVenta_crear')) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'Error: 101 => ' . Yii::app()->params['accessError']));
                Yii::app()->end();
            }

            $render = Yii::app()->getRequest()->getPost('render', false);
            $model = new AperturaCierrePuntoVenta;

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

                $listTipos = TipoAperturaCierre::model()->findAll(array('order' => 'NombreTipoAperturaCierre'));
                $listTipos = CHtml::listData($listTipos, 'IDTipoAperturaCierre', 'NombreTipoAperturaCierre');

                //Yii::app()->clientScript->scriptMap['jquery.js'] = false;
                Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
                Yii::app()->clientScript->registerScript('prettyphoto', "$('" . Yii::app()->controller->module->elementPretty . "').prettyPhoto(" . CJSON::encode(Yii::app()->controller->module->optPretty) . ");", CClientScript::POS_END);
                echo CJSON::encode(array(
                    'result' => 'ok',
                    'response' => array(
                        'msg' => 'Datos cargados para historial',
                        'form' => $this->renderPartial('_form', array('model' => $model, 'listTipos' => $listTipos), true, true)
                )));
                Yii::app()->end();
            } else if ($_POST['AperturaCierrePuntoVenta']) {
                $model->attributes = $_POST['AperturaCierrePuntoVenta'];

                if ($model->validate()) {
                    try {
                        $model->save();
                        echo CJSON::encode(array('result' => 'ok', 'response' => 'Historial creado'));
                        Yii::app()->end();
                    } catch (Exception $exc) {
                        Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                        echo CJSON::encode(array('result' => 'error', 'response' => 'Error al crear historial.' . $exc->getMessage()));
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

            if (!Yii::app()->user->checkAccess('PuntoDeVenta_AperturaCierrePuntoDeVenta_actualizar')) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'Error: 101 => ' . Yii::app()->params['accessError']));
                Yii::app()->end();
            }

            $render = Yii::app()->getRequest()->getPost('render', false);

            if ($render) {
                $pk = Yii::app()->getRequest()->getPost('historial', null);

                if ($pk === null) {
                    echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta historial a actualizar'));
                    Yii::app()->end();
                }

                try {
                    $model = AperturaCierrePuntoVenta::model()->findByPk($pk);

                    if ($model === null) {
                        echo CJSON::encode(array('result' => 'error', 'response' => 'Historial a actualizar no existe'));
                        Yii::app()->end();
                    }

                    $listTipos = TipoAperturaCierre::model()->findAll(array('order' => 'NombreTipoAperturaCierre'));
                    $listTipos = CHtml::listData($listTipos, 'IDTipoAperturaCierre', 'NombreTipoAperturaCierre');


                    //Yii::app()->clientScript->scriptMap['jquery.js'] = false;
                    Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
                    Yii::app()->clientScript->registerScript('prettyphoto', "$('" . Yii::app()->controller->module->elementPretty . "').prettyPhoto(" . CJSON::encode(Yii::app()->controller->module->optPretty) . ");", CClientScript::POS_END);
                    echo CJSON::encode(array(
                        'result' => 'ok',
                        'response' => array(
                            'msg' => 'Datos de historial a actualizar cargados',
                            'form' => $this->renderPartial('_form', array('model' => $model, 'listTipos' => $listTipos), true, true)
                    )));
                    Yii::app()->end();
                } catch (Exception $exc) {
                    Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                    echo CJSON::encode(array('result' => 'error', 'response' => 'Error al cargar datos de historial.' . $exc->getMessage()));
                    Yii::app()->end();
                }
            } else if ($_POST['AperturaCierrePuntoVenta']) {
                $form = new AperturaCierrePuntoVenta('update');
                $form->attributes = $_POST['AperturaCierrePuntoVenta'];

                try {
                    $model = AperturaCierrePuntoVenta::model()->findByPk($form->IDAperturaCierrePuntoDeVenta);
                    $model->FechaAperturaCierre = $form->FechaAperturaCierre;
                    $model->FechaRegistroAperturaCierre = $form->FechaRegistroAperturaCierre;
                    $model->IDTipoAperturaCierre = $form->IDTipoAperturaCierre;
                    $model->ObservacionesAperturaCierre = $form->ObservacionesAperturaCierre;

                    if ($model->validate()) {
                        try {
                            $model->save();
                            echo CJSON::encode(array('result' => 'ok', 'response' => 'Informaci&oacute;n de historial actualizada'));
                            Yii::app()->end();
                        } catch (Exception $exc) {
                            Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                            echo CJSON::encode(array('result' => 'error', 'response' => 'Error al actualizar historial.' . $exc->getMessage()));
                            Yii::app()->end();
                        }
                    } else {
                        echo CActiveForm::validate($model);
                        Yii::app()->end();
                    }
                } catch (Exception $exc) {
                    Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                    echo CJSON::encode(array('result' => 'error', 'response' => 'Error al actualizar datos de historial.' . $exc->getMessage()));
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
     * Elimina un modelo particular.
     * Si eliminacion es exitosa, se redirecciona a la pagina de admin.
     * @param integer $id el ID del modelo a ser eliminado
     */
    /* public function actionEliminar() {
      if (Yii::app()->request->isPostRequest) {

      if (Yii::app()->user->isGuest) {
      echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta usuario autenticado, por favor iniciar sesi&oacute;n'));
      Yii::app()->end();
      }

      if (!Yii::app()->user->checkAccess('PuntoDeVenta_AperturaCierrePuntoDeVenta_eliminar')) {
      echo CJSON::encode(array('result' => 'error', 'response' => 'Error: 101 => ' . Yii::app()->params['accessError']));
      Yii::app()->end();
      }

      $pk = Yii::app()->getRequest()->getPost('historial', null);

      if ($pk === null) {
      echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta registro a eliminar'));
      Yii::app()->end();
      }

      try {
      $model = AperturaCierrePuntoVenta::model()->findByPk($pk);

      if ($model === null) {
      echo CJSON::encode(array('result' => 'error', 'response' => 'Registro a eliminar no existe'));
      Yii::app()->end();
      }

      $model->delete();
      echo CJSON::encode(array('result' => 'ok', 'response' => 'Registro eliminado correctamente'));
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
      } catch (Exception $exc) {
      Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
      echo CJSON::encode(array('result' => 'error', 'response' => 'Error al eliminar historial. ' . $exc->getMessage()));
      Yii::app()->end();
      }
      } else {
      echo CJSON::encode(array('result' => 'error', 'response' => 'Solicitud inv&aacute;lida'));
      Yii::app()->end();
      }
      } */

    public function actionEliminar($id) {
        if (Yii::app()->request->isPostRequest) {
            if (Yii::app()->user->isGuest) {
                throw new CHttpException(404, 'No se detecta usuario autenticado, por favor iniciar sesi&oacute;n.');
            }

            if (!Yii::app()->user->checkAccess('PuntoDeVenta_AperturaCierrePuntoDeVenta_eliminar')) {
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
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = AperturaCierrePuntoVenta::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'Historial no existe.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'aperturaCierrePV-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
