<?php

class ImagenPuntoVentaController extends Controller {

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

            if (!Yii::app()->user->checkAccess('PuntoDeVenta_ImagenPuntoVenta_crear')) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'Error: 101 => ' . Yii::app()->params['accessError']));
                Yii::app()->end();
            }

            $render = Yii::app()->getRequest()->getPost('render', false);
            $model = new ImagenPuntoVenta('create');

            if ($render) {
                $pk = Yii::app()->getRequest()->getPost('puntoventa');

                if ($pk === null) {
                    echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta punto de venta a adicionar tel&eacute;fono'));
                    Yii::app()->end();
                }

                $puntoVenta = PuntoVenta::model()->findByPk($pk);

                if ($puntoVenta === null) {
                    echo CJSON::encode(array('result' => 'error', 'response' => 'Punto de venta no existe'));
                    Yii::app()->end();
                }

                $model->IDPuntoDeVenta = $puntoVenta->IDPuntoDeVenta;

                //Yii::app()->clientScript->scriptMap['jquery.js'] = false;
                Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
                Yii::app()->clientScript->registerScript('prettyphoto', "$('" . Yii::app()->controller->module->elementPretty . "').prettyPhoto(" . CJSON::encode(Yii::app()->controller->module->optPretty) . ");", CClientScript::POS_END);
                echo CJSON::encode(array(
                    'result' => 'ok',
                    'response' => array(
                        'msg' => 'Datos cargados para creaci&oacute;n',
                        'form' => $this->renderPartial('_form', array('model' => $model), true, true)
                )));
                Yii::app()->end();
            } else if ($_POST['ImagenPuntoVenta']) {
                $model->attributes = $_POST['ImagenPuntoVenta'];

                $uploadedFile = CUploadedFile::getInstance($model, 'RutaImagen');

                if ($uploadedFile !== null) {
                    $directorio = Yii::getPathOfAlias('webroot') . Yii::app()->controller->module->uploadImg . $model->IDPuntoDeVenta;
                    $fecha = new DateTime();
                    $archivo = $fecha->format("YmdHis") . '.' . $uploadedFile->getExtensionName();
                    $model->RutaImagen = Yii::app()->controller->module->uploadImg . $model->IDPuntoDeVenta . "/" . $archivo;

                    if (!file_exists($directorio)) {
                        mkdir($directorio, 0777, true);
                    }
                }

                if ($model->validate()) {
                    try {
                        $uploadedFile->saveAs("$directorio/$archivo");
                        if ($model->save()) {
                            echo CJSON::encode(array('result' => 'ok', 'response' => 'Imagen creada'));
                            Yii::app()->end();
                        } else {
                            echo CActiveForm::validate($model);
                            Yii::app()->end();
                        }
                    } catch (Exception $exc) {
                        Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                        echo CJSON::encode(array('result' => 'error', 'response' => 'Error al crear imagen'));
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

            if (!Yii::app()->user->checkAccess('PuntoDeVenta_ImagenPuntoVenta_actualizar')) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'Error: 101 => ' . Yii::app()->params['accessError']));
                Yii::app()->end();
            }

            $render = Yii::app()->getRequest()->getPost('render', false);

            if ($render) {
                $pk = Yii::app()->getRequest()->getPost('imagen', null);

                if ($pk === null) {
                    echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta imagen a actualizar'));
                    Yii::app()->end();
                }

                try {
                    $model = ImagenPuntoVenta::model()->findByPk($pk);

                    if ($model === null) {
                        echo CJSON::encode(array('result' => 'error', 'response' => 'Imagen a actualizar no existe'));
                        Yii::app()->end();
                    }

                    //Yii::app()->clientScript->scriptMap['jquery.js'] = false;
                    Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
                    Yii::app()->clientScript->registerScript('prettyphoto', "$('" . Yii::app()->controller->module->elementPretty . "').prettyPhoto(" . CJSON::encode(Yii::app()->controller->module->optPretty) . ");", CClientScript::POS_END);
                    echo CJSON::encode(array(
                        'result' => 'ok',
                        'response' => array(
                            'msg' => 'Datos de imagen cargados',
                            'form' => $this->renderPartial('_form', array('model' => $model), true, true)
                    )));
                    Yii::app()->end();
                } catch (Exception $exc) {
                    Yii::log($exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                    echo CJSON::encode(array('result' => 'error', 'response' => 'Error al cargar datos de imagen'));
                    Yii::app()->end();
                }
            } else if ($_POST['ImagenPuntoVenta']) {
                $form = new ImagenPuntoVenta('update');
                $form->attributes = $_POST['ImagenPuntoVenta'];

                try {
                    $model = ImagenPuntoVenta::model()->findByPk($form->IDImagenPuntoDeVenta);
                    $modelini = CJSON::encode($model);
                    $model->NombreImagen = $form->NombreImagen;
                    $model->TituloImagen = $form->TituloImagen;
                    $model->DescripcionImagen = $form->DescripcionImagen;
                    $model->TipoImagen = $form->TipoImagen;
                    $model->EstadoImagen = $form->EstadoImagen;

                    $uploadedFile = CUploadedFile::getInstance($form, 'RutaImagen');
                    $imagenAnterior = null;

                    if ($uploadedFile !== null) {
                        $imagenAnterior = $directorio = Yii::getPathOfAlias('webroot') . $model->RutaImagen;
                        $directorio = Yii::getPathOfAlias('webroot') . Yii::app()->controller->module->uploadImg . $model->IDPuntoDeVenta;
                        $fecha = new DateTime();
                        $archivo = $fecha->format("YmdHis") . '.' . $uploadedFile->getExtensionName();
                        $model->RutaImagen = Yii::app()->controller->module->uploadImg . $model->IDPuntoDeVenta . "/" . $archivo;

                        if (!file_exists($directorio)) {
                            mkdir($directorio, 0777, true);
                        }
                    }

                    if ($model->validate()) {
                        try {
                            if ($model->save()) {
                                if ($imagenAnterior !== null)
                                    unlink($imagenAnterior);

                                if ($uploadedFile !== null)
                                    $uploadedFile->saveAs("$directorio/$archivo");

                                //Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
                                echo CJSON::encode(array('result' => 'ok', 'response' => 'Informaci&oacute;n de imagen actualizada'));
                                Yii::app()->end();
                            }else {
                                echo CActiveForm::validate($form);
                                Yii::app()->end();
                            }
                        } catch (Exception $exc) {
                            Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                            echo CJSON::encode(array('result' => 'error', 'response' => 'Error al actualizar imagen.' . $exc->getMessage()));
                            Yii::app()->end();
                        }
                    } else {
                        echo CActiveForm::validate($form);
                        Yii::app()->end();
                    }
                } catch (Exception $exc) {
                    Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                    echo CJSON::encode(array('result' => 'error', 'response' => 'Error al actualizar datos de imagen.' . $exc->getMessage()));
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

            if (!Yii::app()->user->checkAccess('PuntoDeVenta_ImagenPuntoVenta_eliminar')) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'Error: 101 => ' . Yii::app()->params['accessError']));
                Yii::app()->end();
            }

            $pk = Yii::app()->getRequest()->getPost('imagen', null);

            if ($pk === null) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta registro a eliminar'));
                Yii::app()->end();
            }

            try {
                $model = ImagenPuntoVenta::model()->findByPk($pk);

                if ($model === null) {
                    echo CJSON::encode(array('result' => 'error', 'response' => 'Registro a eliminar no existe'));
                    Yii::app()->end();
                }

                $ruta = $model->RutaImagen;
                $directorio = Yii::getPathOfAlias('webroot');

                if ($model->delete()) {
                    unlink($directorio . $ruta);
                    echo CJSON::encode(array('result' => 'ok', 'response' => 'Registro eliminado correctamente'));
                    Yii::app()->end();
                } else {
                    echo CJSON::encode(array('result' => 'error', 'response' => '[0001] Error al eliminar registro'));
                    Yii::app()->end();
                }
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
    }*/
    
    public function actionEliminar($id) {
        if (Yii::app()->request->isPostRequest) {
            if (Yii::app()->user->isGuest) {
                throw new CHttpException(404, 'No se detecta usuario autenticado, por favor iniciar sesi&oacute;n.');
            }

            if (!Yii::app()->user->checkAccess('PuntoDeVenta_ImagenPuntoVenta_eliminar')) {
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
        $model = ImagenPuntoVenta::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'Imágen no existe.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'imagen-punto-venta-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
