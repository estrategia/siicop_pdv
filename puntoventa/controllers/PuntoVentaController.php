<?php

class PuntoVentaController extends Controller {

    /**
     * @var string accion por defecto del controlador
     */
    public $defaultAction = 'admin';
    
    public function actionSectorlrvAutocomplete() {
        $term = null;
        $ciudad = null;

        if (Yii::app()->request->isPostRequest) {
            $term = (isset($_POST['term']) ? trim($_POST['term']) : null);
            $ciudad = (isset($_POST['ciudad']) ? trim($_POST['ciudad']) : null);
        } else {
            $term = (isset($_GET['term']) ? trim($_GET['term']) : null);
            $ciudad = (isset($_GET['ciudad']) ? trim($_GET['ciudad']) : null);
        }

        $results = array();

        if ($term !== null && $ciudad !== null) {
            $term = trim($term);

            /* $criteria = new CDbCriteria;
              $criteria->with = array('sectorlrv','ciudad');
              $criteria->order = 'ciudad.NombreCiudad, sectorlrv.NombreSector';
              $criteria->condition = 'ciudad.NombreCiudad LIKE :term';
              $criteria->params = array('term' => "%$term%");
              $criteria->limit = 20; */

            $criteria = new CDbCriteria;
            $criteria->with = array('sectorlrv');
            $criteria->order = 'sectorlrv.NombreSector';
            $criteria->condition = 't.CodCiudad=:ciudad AND sectorlrv.NombreSector LIKE :term';
            $criteria->params = array(
                ':ciudad' => $ciudad,
                ':term' => "%$term%"
            );
            $criteria->limit = 20;

            $models = SectorCiudadLRV::model()->findAll($criteria);

            foreach ($models as $model) {
                $results[] = array(
                    //'label' => $model->ciudad->NombreCiudad . " - " . $model->sectorlrv->NombreSector,
                    'label' => $model->sectorlrv->NombreSector,
                    'value' => $model->IDSectorLRV,
                );
            }
        }

        echo CJSON::encode($results);
        Yii::app()->end();
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCrear() {

        if (Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->user->loginUrl);
            Yii::app()->end();
        }

        if (!Yii::app()->user->checkAccess('PuntoDeVenta_PuntoDeVenta_crear')) {
            $this->render('//site/error', array('code' => '101', 'message' => Yii::app()->params['accessError']));
            Yii::app()->end();
        }

        $model = new PuntoVenta;

        if (isset($_POST['PuntoVenta'])) {
            $tabSiguiente = $_POST['tab_siguiente'];
            $model->attributes = $_POST['PuntoVenta'];
            $model->Estado = 1;
            $model->CodCiudadLRV = $model->CodigoCiudad;

            try {
                //2015-11-18 - Cuando se crea un PDV se ingresa el registro en m_Bodega automaticamente
                $bodegaTr = new Bodega;
                $transaction = $bodegaTr->dbConnection->beginTransaction();
                $error_bod = false;
                $msj_bod = "";

                $bodega = new Bodega;
                $bodega->Codigo = $model->IDComercial;
                $bodega->TipoBodega = "PDV";

                if ($bodega->save()) {
                    if ($model->save()) {
                        $transaction->commit();
                        Yii::app()->user->setFlash('success', "Informaci&oacute;n grabada!");
                        $this->redirect(array('actualizar', 'id' => $model->IDPuntoDeVenta, 'activeIni' => Yii::app()->controller->module->infoContacto));
                        Yii::app()->end();
                    } else {
                        $error_bod = true;
                        $msj_bod = "Informaci&oacute;n no grabada, verificar campos!";
                    }
                } else {
                    $error_bod = true;
                    $msj_bod = "Informaci&oacute;n no grabada, no se pudo crear la bodega relacionada!";
                }

                if ($error_bod) {
                    $transaction->rollback();
                    Yii::app()->user->setFlash('danger', $msj_bod);
                    $this->render('crear', array('model' => $model));
                    Yii::app()->end();
                }
                //2015-11-18 - Fin
            } catch (Exception $exc) {
                Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                Yii::app()->user->setFlash('danger', "Error al grabar informaci&oacute;n. Intente de nuevo!." . $exc->getMessage());
                $this->render('crear', array(
                    'model' => $model,
                ));
                Yii::app()->end();
            }
        } else {
            Yii::app()->user->setFlash('info', "Es requisito, primero diligenciar la informaci&oacute;n b&aacute;sica para modificar el resto de informaci&oacute;n.");
            $this->render('crear', array(
                'model' => $model,
            ));
            Yii::app()->end();
        }
    }

    /**
     * Actualiza un modelo en particular.
     * @param integer $id El ID del modelo a actualizar
     */
    public function actionActualizar($id, $activeIni = null) {

        if (Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->user->loginUrl);
            Yii::app()->end();
        }

        if (!Yii::app()->user->checkAccess('PuntoDeVenta_PuntoDeVenta_actualizar')) {
            $this->render('//site/error', array('code' => '101', 'message' => Yii::app()->params['accessError']));
            Yii::app()->end();
        }

        $model = $this->loadModel($id);
        
        $zona = Zona::model()->find(array(
            'with' => array('puntosVenta' => array('joinType' => 'INNER JOIN')),
            'condition' => '(CedulaDirectorZona=:cedula OR CedulaAuxiliarZona=:cedula) AND puntosVenta.IDPuntoDeVenta=:pdv',
            'params' => array(':cedula' => Yii::app()->user->name, ':pdv' => $id)
        ));

        $telefonos = new TelefonosPuntoVenta('search');
        $telefonos->unsetAttributes();
        $telefonos->IDPuntoDeVenta = $id;

        if (isset($_GET['TelefonosPuntoVenta']))
            $telefonos->attributes = $_GET['TelefonosPuntoVenta'];

        $empleados = new Empleado('search');
        $empleados->unsetAttributes();

        if (isset($_GET['Empleado']))
            $empleados->attributes = $_GET['Empleado'];

        $criteria = new CDbCriteria;
        $criteria->with = array('cargo', 'persona', 'estado');
        $criteria->condition = "(estado.Estado=:estado AND IdCentroCostos=:ccosto AND Supernumerario='No') OR (estado.Estado=:estado AND Supernumerario='Si' AND IdCentroCostos IN (SELECT IdCentroCostos FROM m_PuntoVenta WHERE IDZona=:zona)) ";
        $criteria->params = array(
            'ccosto' => $model->IdCentroCostos,
            'zona' => $model->IDZona,
            'estado' => Yii::app()->controller->module->asocActivo,
        );
        $criteria->compare('t.NumeroDocumento', $empleados->NumeroDocumento, true);
        $criteria->compare('t.IdCargo', $empleados->IdCargo, true);
        $criteria->compare('t.Supernumerario', $empleados->Supernumerario);

        $empDataProvider = new CActiveDataProvider($empleados, array('criteria' => $criteria));

        $servicios = new TipoServicio('search');
        $servicios->unsetAttributes();

        if (isset($_GET['TipoServicio'])) {
            $servicios->attributes = $_GET['TipoServicio'];
        }
        $servicios->puntoventa_search = $id;

        $barrio = new Barrio('search');
        $barrio->unsetAttributes();

        if (isset($_GET['Barrio'])) {
            $barrio->attributes = $_GET['Barrio'];
        }

        $criteriaBarrio = new CDbCriteria;
        $criteriaBarrio->join = 'INNER JOIN t_InfluenciaPuntoVenta as influencia ON (influencia.IDBarrio = t.IdBarrio)';
        $criteriaBarrio->with = array('ciudad');
        $criteriaBarrio->compare('influencia.IDPuntoDeVenta', $model->IDPuntoDeVenta);
        $criteriaBarrio->compare('t.IdBarrio', $barrio->IdBarrio);
        $criteriaBarrio->compare('t.NombreBarrio', $barrio->NombreBarrio, true);
        $criteriaBarrio->compare('t.IdCiudad', $barrio->IdCiudad);

        if (isset($_GET['Barrio_CodCiudad'])) {
            $criteriaBarrio->compare('ciudad.CodCiudad', $_GET['Barrio_CodCiudad']);
        }

        $barrioDataProvider = new CActiveDataProvider($barrio, array('criteria' => $criteriaBarrio));

        $competencia = new Competencia('search');
        $competencia->unsetAttributes();

        if (isset($_GET['Competencia']))
            $competencia->attributes = $_GET['Competencia'];
        $competencia->puntoventa_search = $id;

        $historial = new AperturaCierrePuntoVenta('search');
        $historial->unsetAttributes();

        if (isset($_GET['AperturaCierrePuntoVenta']))
            $historial->attributes = $_GET['AperturaCierrePuntoVenta'];
        $historial->IDPuntoDeVenta = $id;

        $imagenes = new ImagenPuntoVenta('search');
        $imagenes->unsetAttributes();

        if (isset($_GET['ImagenPuntoVenta']))
            $imagenes->attributes = $_GET['ImagenPuntoVenta'];
        $imagenes->IDPuntoDeVenta = $id;


        if (isset($_POST['PuntoVenta'])) {
            $form = new PuntoVenta;
            $form->attributes = $_POST['PuntoVenta'];

            if (isset($_POST['info'])) {
                $info = $_POST['info'];
                $tabSiguiente = $_POST['tab_siguiente'];

                if ($info == Yii::app()->controller->module->infoBasica) {
                    $model->IDZona = $form->IDZona;
                    $model->IDCEDI = $form->IDCEDI;
                    $model->IDSector = $form->IDSector;
                    $model->IDTipoNegocio = $form->IDTipoNegocio;
                    $model->IDComercial = $form->IDComercial;
                    $model->CodigoContable = $form->CodigoContable;
                    $model->IdCentroCostos = $form->IdCentroCostos;
                    $model->NombrePuntoDeVenta = $form->NombrePuntoDeVenta;
                    $model->NombreCortoPuntoDeVenta = $form->NombreCortoPuntoDeVenta;
                    $model->DireccionPuntoDeVenta = $form->DireccionPuntoDeVenta;
                    $model->BarrioConIndicaciones = $form->BarrioConIndicaciones;
                    $model->IDUbicacion = $form->IDUbicacion;
                    $model->CodigoCiudad = $form->CodigoCiudad;
                    $model->Estado = $form->Estado;
                    $model->CSC = $form->CSC;

                    $model->eMailPuntoDeVenta = $form->eMailPuntoDeVenta;
                    $model->EstratoPuntoDeVenta = $form->EstratoPuntoDeVenta;
                    $model->CedulaAdministrador = $form->CedulaAdministrador;
                    $model->CedulaSubAdministrador = $form->CedulaSubAdministrador;

                    $model->Resoluciones = $form->Resoluciones;
                    $model->CodCiudadLRV = $model->CodigoCiudad;
                    $model->IDSectorLRV = $form->IDSectorLRV;
                } else if ($info == Yii::app()->controller->module->infoOtros) {
                    $model->IPCamara = $form->IPCamara;
                    $model->DireccionIPServidor = $form->DireccionIPServidor;
                    $model->RutaImagenMapa = $form->RutaImagenMapa;
                    $model->DimensionFondo = $form->DimensionFondo;
                    $model->DimensionAncho = $form->DimensionAncho;
                    $model->DireccionGoogle = $form->DireccionGoogle;
                    $model->LatitudGoogle = $form->LatitudGoogle;
                    $model->LongitudGoogle = $form->LongitudGoogle;
                }

                try {
                    if ($model->validate()) {
                        if ($model->DimensionFondo != null && $model->DimensionAncho != null && !empty($model->DimensionFondo) && !empty($model->DimensionAncho)) {
                            $model->AreaLocal = $model->DimensionFondo * $model->DimensionAncho;
                        }

                        $model->save();
                        Yii::app()->user->setFlash('success', "Informaci&oacute;n actualizada!");

                        $this->render('actualizar', array(
                            'model' => $model,
                            'telefonos' => $telefonos,
                            'empleados' => $empleados,
                            'empDataProvider' => $empDataProvider,
                            'servicios' => $servicios,
                            'historial' => $historial,
                            'imagenes' => $imagenes,
                            'barrio' => $barrio,
                            'barrioDataProvider' => $barrioDataProvider,
                            'competencia' => $competencia,
                            'consulta' => false,
                            'active' => $tabSiguiente,
                            'zona' => $zona
                        ));
                        Yii::app()->end();
                    } else {
                        Yii::app()->user->setFlash('danger', "Informaci&oacute;n no actualizada, verificar campos!");
                        $this->render('actualizar', array(
                            'model' => $model,
                            'telefonos' => $telefonos,
                            'empleados' => $empleados,
                            'empDataProvider' => $empDataProvider,
                            'servicios' => $servicios,
                            'historial' => $historial,
                            'imagenes' => $imagenes,
                            'barrio' => $barrio,
                            'barrioDataProvider' => $barrioDataProvider,
                            'competencia' => $competencia,
                            'consulta' => false,
                            'active' => $info,
                            'zona' => $zona
                        ));
                        Yii::app()->end();
                    }
                } catch (Exception $exc) {
                    Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');

                    Yii::app()->user->setFlash('danger', "Error al actualizar informaci&oacute;n. Intente de nuevo!. " . $exc->getMessage());
                    $this->render('actualizar', array(
                        'model' => $model,
                        'telefonos' => $telefonos,
                        'empleados' => $empleados,
                        'empDataProvider' => $empDataProvider,
                        'servicios' => $servicios,
                        'historial' => $historial,
                        'imagenes' => $imagenes,
                        'barrio' => $barrio,
                        'barrioDataProvider' => $barrioDataProvider,
                        'competencia' => $competencia,
                        'consulta' => false,
                        'active' => $info,
                        'zona' => $zona
                    ));
                    Yii::app()->end();
                }
            } else {
                $this->render('actualizar', array(
                    'model' => $model,
                    'telefonos' => $telefonos,
                    'empleados' => $empleados,
                    'empDataProvider' => $empDataProvider,
                    'servicios' => $servicios,
                    'historial' => $historial,
                    'imagenes' => $imagenes,
                    'barrio' => $barrio,
                    'barrioDataProvider' => $barrioDataProvider,
                    'competencia' => $competencia,
                    'consulta' => false,
                    'active' => Yii::app()->controller->module->infoBasica,
                    'zona' => $zona
                ));
            }
        } else {

            if ($activeIni == null) {
                $activeIni = Yii::app()->controller->module->infoBasica;
            }

            $this->render('actualizar', array(
                'model' => $model,
                'telefonos' => $telefonos,
                'empleados' => $empleados,
                'empDataProvider' => $empDataProvider,
                'servicios' => $servicios,
                'historial' => $historial,
                'imagenes' => $imagenes,
                'barrio' => $barrio,
                'barrioDataProvider' => $barrioDataProvider,
                'competencia' => $competencia,
                'consulta' => false,
                'active' => $activeIni,
                'zona' => $zona
            ));
        }
    }

    private function verpdvadmin() {
        $model = new PuntoVenta('search');
        $model->unsetAttributes();
        if (isset($_GET['PuntoVenta']))
            $model->attributes = $_GET['PuntoVenta'];
			
		//2015-11-26 - Se busca por el barrio de influencia
		if (isset($_GET['PuntoVenta_BusquedaZona']) && $_GET['PuntoVenta_BusquedaZona'] == 1)
		{
			Yii::app()->getSession()->add("BusquedaCiudadPDV", $_GET['PuntoVenta_Ciudad']);
			Yii::app()->getSession()->add("BusquedaBarrioPDV", $_GET['PuntoVenta_Barrio']);
		}
		else
		{
			Yii::app()->getSession()->remove('BusquedaCiudadPDV');
			Yii::app()->getSession()->remove('BusquedaBarrioPDV');
		}
		//2015-11-26 - Fin

        $this->render('adminver', array(
            'model' => $model,
        ));
        Yii::app()->end();
    }

    private function verpdv($id) {
        $model = $this->loadModel($id);

        $telefonos = new TelefonosPuntoVenta('search');
        $telefonos->unsetAttributes();
        $telefonos->IDPuntoDeVenta = $id;

        if (isset($_GET['TelefonosPuntoVenta']))
            $telefonos->attributes = $_GET['TelefonosPuntoVenta'];

        $empleados = new Empleado('search');
        $empleados->unsetAttributes();

        if (isset($_GET['Empleado']))
            $empleados->attributes = $_GET['Empleado'];

        $criteria = new CDbCriteria;
        $criteria->with = array('cargo', 'persona', 'estado');
        $criteria->condition = "(estado.Estado=:estado AND IdCentroCostos=:ccosto AND Supernumerario='No') OR (estado.Estado=:estado AND Supernumerario='Si' AND IdCentroCostos IN (SELECT IdCentroCostos FROM m_PuntoVenta WHERE IDZona=:zona)) ";
        $criteria->params = array(
            'ccosto' => $model->IdCentroCostos,
            'zona' => $model->IDZona,
            'estado' => Yii::app()->controller->module->asocActivo,
        );
        $criteria->compare('t.NumeroDocumento', $empleados->NumeroDocumento, true);
        $criteria->compare('t.IdCargo', $empleados->IdCargo, true);
        $criteria->compare('t.Supernumerario', $empleados->Supernumerario);

        $empDataProvider = new CActiveDataProvider($empleados, array('criteria' => $criteria));

        $servicios = new TipoServicio('search');
        $servicios->unsetAttributes();

        if (isset($_GET['TipoServicio'])) {
            $servicios->attributes = $_GET['TipoServicio'];
        }
        $servicios->puntoventa_search = $id;

        $barrio = new Barrio('search');
        $barrio->unsetAttributes();

        if (isset($_GET['Barrio'])) {
            $barrio->attributes = $_GET['Barrio'];
        }

        $criteriaBarrio = new CDbCriteria;
        $criteriaBarrio->join = 'INNER JOIN t_InfluenciaPuntoVenta as influencia ON (influencia.IDBarrio = t.IdBarrio)';
        $criteriaBarrio->with = array('ciudad');
        $criteriaBarrio->compare('influencia.IDPuntoDeVenta', $model->IDPuntoDeVenta);
        $criteriaBarrio->compare('t.IdBarrio', $barrio->IdBarrio);
        $criteriaBarrio->compare('t.NombreBarrio', $barrio->NombreBarrio, true);
        $criteriaBarrio->compare('t.IdCiudad', $barrio->IdCiudad);

        if (isset($_GET['Barrio_CodCiudad'])) {
            $criteriaBarrio->compare('ciudad.CodCiudad', $_GET['Barrio_CodCiudad']);
        }

        $barrioDataProvider = new CActiveDataProvider($barrio, array('criteria' => $criteriaBarrio));

        $competencia = new Competencia('search');
        $competencia->unsetAttributes();

        if (isset($_GET['Competencia']))
            $competencia->attributes = $_GET['Competencia'];
        $competencia->puntoventa_search = $id;

        $historial = new AperturaCierrePuntoVenta('search');
        $historial->unsetAttributes();

        if (isset($_GET['AperturaCierrePuntoVenta']))
            $historial->attributes = $_GET['AperturaCierrePuntoVenta'];
        $historial->IDPuntoDeVenta = $id;

        $imagenes = new ImagenPuntoVenta('search');
        $imagenes->unsetAttributes();

        if (isset($_GET['ImagenPuntoVenta']))
            $imagenes->attributes = $_GET['ImagenPuntoVenta'];
        $imagenes->IDPuntoDeVenta = $id;

        $tabSiguiente = Yii::app()->controller->module->infoBasica;

        if (isset($_POST['tab_siguiente']))
            $tabSiguiente = $_POST['tab_siguiente'];
        
        $this->render('actualizar', array(
            'model' => $model,
            'telefonos' => $telefonos,
            'empleados' => $empleados,
            'empDataProvider' => $empDataProvider,
            'servicios' => $servicios,
            'historial' => $historial,
            'imagenes' => $imagenes,
            'barrio' => $barrio,
            'barrioDataProvider' => $barrioDataProvider,
            'competencia' => $competencia,
            'consulta' => true,
            'active' => $tabSiguiente,
            'zona' => null,
        ));
    }
    
    /**
     * Visualiza y administra un modelo en particular.
     * @param integer $id El ID del modelo a ser visualizado
     */
    public function actionVer($id = null) {
        if ($id == null) {
            $this->verpdvadmin();
        } else {
            $this->verpdv($id);
        }
    }

    /**
     * Activa/Inactiva un modelo particular, dado un id
     */
    public function actionActivacion() {
        if (Yii::app()->request->isPostRequest) {

            if (Yii::app()->user->isGuest) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta usuario autenticado, por favor iniciar sesi&oacute;n'));
                Yii::app()->end();
            }

            $pk = Yii::app()->getRequest()->getPost('puntoventa', null);

            if ($pk === null) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta Punto de Venta'));
                Yii::app()->end();
            }

            try {
                $model = PuntoVenta::model()->findByPk($pk);

                if ($model === null) {
                    echo CJSON::encode(array('result' => 'error', 'response' => 'Punto de Venta no existe'));
                    Yii::app()->end();
                }

                if ($model->Estado == 1) {
                    $model->Estado = 0;
                } else {
                    $model->Estado = 1;
                }

                try {
                    if ($model->validate()) {
                        $model->save();
                        echo CJSON::encode(array('result' => 'ok', 'response' => 'Punto de Venta actualizado correctamente'));
                        Yii::app()->end();
                    } else {
                        echo CJSON::encode(array('result' => 'error', 'response' => $model->validateErrorsResponse()));
                        Yii::app()->end();
                    }
                } catch (Exception $exc) {
                    Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                    echo CJSON::encode(array('result' => 'error', 'response' => 'Error al actualizar Punto de Venta. ' . $exc->getMessage()));
                    Yii::app()->end();
                }
            } catch (CHttpException $exc) {
                Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                echo CJSON::encode(array('result' => 'error', 'response' => 'Error al consultar Punto de Venta. ' . $exc->getMessage()));
                Yii::app()->end();
            } catch (Exception $exc) {
                Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                echo CJSON::encode(array('result' => 'error', 'response' => 'Error al consultar Punto de Venta. ' . $exc->getMessage()));
                Yii::app()->end();
            }
        } else {
            echo CJSON::encode(array('result' => 'error', 'response' => 'Solicitud inv&aacute;lida'));
            Yii::app()->end();
        }
    }

    public function actionHorario() {
        if (Yii::app()->request->isPostRequest) {
            if (Yii::app()->user->isGuest) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta usuario autenticado, por favor iniciar sesi&oacute;n'));
                Yii::app()->end();
            }

            $puntoVentaId = Yii::app()->getRequest()->getPost('puntoventa', null);
            $horario = Yii::app()->getRequest()->getPost('horario', null);
            $horarioId = Yii::app()->getRequest()->getPost('id', null);

            if ($horarioId === null || empty($horarioId)) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta horario seleccionado'));
                Yii::app()->end();
            }

            if ($puntoVentaId === null) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta punto venta'));
                Yii::app()->end();
            }

            if ($horario === null) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta tipo de horario a actualizar'));
                Yii::app()->end();
            }

            if (!isset(Yii::app()->controller->module->horarios[$horario])) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'Tipo de horario no v&aacute;lido'));
                Yii::app()->end();
            }

            $atributoHorario = Yii::app()->controller->module->horarios[$horario]['atributo'];

            try {
                $model = PuntoVenta::model()->findByPk($puntoVentaId);

                if ($model === null) {
                    echo CJSON::encode(array('result' => 'error', 'response' => 'Punto de venta no existe'));
                    Yii::app()->end();
                }

                $modelHorario = HorarioPuntoVenta::model()->findByPk($horarioId);

                if ($modelHorario === null) {
                    echo CJSON::encode(array('result' => 'error', 'response' => 'Horario seleccionado no existe'));
                    Yii::app()->end();
                }

                $model->$atributoHorario = $modelHorario->IDHorarioPuntoDeVenta;

                try {
                    if ($model->save()) {
                        Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
                        Yii::app()->clientScript->registerScript('prettyphoto', "$('" . Yii::app()->controller->module->elementPretty . "').prettyPhoto(" . CJSON::encode(Yii::app()->controller->module->optPretty) . ");", CClientScript::POS_END);
                        echo CJSON::encode(array(
                            'result' => 'ok',
                            'response' => array(
                                'msg' => 'Horario actualizado',
                                'tab' => $this->renderPartial('_horarios', array('model' => $model, 'consulta' => false, 'tab' => Yii::app()->controller->module->infoHorarios, 'active' => true), true, true)
                            )
                        ));
                        Yii::app()->end();
                    } else {
                        echo CJSON::encode(array('result' => 'error', 'response' => 'Error al actualizar horario. ' . $model->validateErrorsResponse()));
                        Yii::app()->end();
                    }
                } catch (Exception $exc) {
                    Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                    echo CJSON::encode(array('result' => 'error', 'response' => 'Error al actualizar horario. ' . $exc->getMessage()));
                    Yii::app()->end();
                }
            } catch (Exception $exc) {
                Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                echo CJSON::encode(array('result' => 'error', 'response' => 'Error al seleccionar horario o punto de venta. ' . $exc->getMessage()));
                Yii::app()->end();
            }
        } else {
            echo CJSON::encode(array('result' => 'error', 'response' => 'Solicitud inv&aacute;lida'));
            Yii::app()->end();
        }
    }

    public function actionExportar() {
        if (Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->user->loginUrl);
            Yii::app()->end();
        }

        if (isset($_GET['excel'])) {
            $model = null;

            if (Yii::app()->session[Yii::app()->controller->module->session['modelPdvExport']] !== null)
                $model = Yii::app()->session[Yii::app()->controller->module->session['modelPdvExport']];

            $dataProvider = null;

            if ($model !== null && get_class($model) === 'PuntoVenta') {
                $dataProvider = $model->search(true);
            }

            $content = $this->renderPartial('excel', array('dataProvider' => $dataProvider), true);
            Yii::app()->request->sendFile('puntosventa_' . date('YmdHis') . '.xls', $content); //"application/vnd.ms-excel"
        } else {
            $this->redirect(array('/puntoVenta'));
        }
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        if (Yii::app()->user->checkAccess('PuntoDeVenta_PuntoDeVenta_admin')) {
            $model = new PuntoVenta('search');
            $model->unsetAttributes();
            
            if (isset($_GET['PuntoVenta']))
                $model->attributes = $_GET['PuntoVenta'];
			
			//2015-11-26 - Se busca por el barrio de influencia
			if (isset($_GET['PuntoVenta_BusquedaZona']) && $_GET['PuntoVenta_BusquedaZona'] == 1)
			{
				Yii::app()->getSession()->add("BusquedaCiudadPDV", $_GET['PuntoVenta_Ciudad']);
				Yii::app()->getSession()->add("BusquedaBarrioPDV", $_GET['PuntoVenta_Barrio']);
			}
			else
			{
				Yii::app()->getSession()->remove('BusquedaCiudadPDV');
				Yii::app()->getSession()->remove('BusquedaBarrioPDV');
			}
			//2015-11-26 - Fin

            Yii::app()->session[Yii::app()->controller->module->session['modelPdvExport']] = $model;
            $this->render('admin', array(
                'model' => $model,
            ));
            Yii::app()->end();
        }else{
            $this->actionVer();
            Yii::app()->end();
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = PuntoVenta::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'La página solicitada no existe.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'punto-venta-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
	
    /**
     * Funcion para mostrar el campo en el gridview
     * @param PuntoVenta $data
     * @param object $row
     * @param object $dataColumn
     * @return string
     */
    protected function obtenerDatoGridZonaInfluencia($data, $row, $dataColumn)
    {
        $dato = "";
        
        switch($dataColumn->name)
        {
            case 'IDComercial':
				$dato = $data->IDComercial;
				
				if($data->Estado == 1) 
					$dato .= ' <span title="Activo" alt="Activo" class="icon icon-blue icon-unlocked"></span>';
				else
					$dato .= ' <span title="Inactivo" alt="Inactivo" class="icon icon-red icon-locked"></span>';
                break;
			case 'Telefonos':
				$criteria = new CDbCriteria();
				$criteria->condition = "IDPuntoDeVenta=:IDPuntoDeVenta";
				$criteria->params = array(':IDPuntoDeVenta' => $data->IDPuntoDeVenta);
				$telefonos = TelefonosPuntoVenta::model()->findAll($criteria);

				foreach($telefonos as $tel)
				{
					if(!is_null($tel->IndicativoTelefono))
						$dato .= '('.$tel->IndicativoTelefono.') ';
					
					$dato .= $tel->NumeroTelefono.'<br />';
				}
				break;
			case 'Servicios':
				$criteria = new CDbCriteria();
				$criteria->join = "INNER JOIN t_ServiciosPuntoVenta s ON s.IDTipoServicio = t.IDTipoServicio";
				$criteria->condition = "s.IDPuntoDeVenta=:IDPuntoDeVenta";
				$criteria->params = array(':IDPuntoDeVenta' => $data->IDPuntoDeVenta);
				$criteria->order = 'NombreTipoServicio ASC';
				$servicios = TipoServicio::model()->findAll($criteria);

				foreach($servicios as $ser)
					$dato .= $ser->NombreTipoServicio.'<br />';
				
				if($dato != '')
				{
					$dato = '<div class="accordion" id="accordionServicios">
						<div class="accordion-group">
							<div id="servicios'.$data->IDPuntoDeVenta.'" class="accordion-body collapse">
								'.$dato.'
							</div>
							<center><button class="btn btn-xs btn-info" data-toggle="collapse" href="#servicios'.$data->IDPuntoDeVenta.'">Ver Servicios</button></center>
						</div>
					</div>';
				}
				break;
			case 'Horarios':
				$arr_hor = array(
					'HorarioAperturaLunesASabado' => 'Lunes a Sábado', 
					'HorarioAperturaDomingo' => 'Domingos',
					'HorarioAperturaFestivo' => 'Festivos',
					'HorarioAperturaEspecial' => 'Especial',
					'HorarioDomicilioLunesASabado' => 'Domicilios Lun a Sab',
					'HorarioDomicilioDomingo' => 'Domicilios Dom',
					'HorarioDomicilioFestivo' => 'Domicilios Fest',
					'HorarioDomicilioEspecial' => 'Domicilios Especial',
				);
				
				foreach($arr_hor as $hor => $col)
				{
					if(!is_null($data->{$hor}))
					{
						$horario = HorarioPuntoVenta::model()->findByPk($data->{$hor});
						$hora_ini = date("h:i A", strtotime($horario->HorarioInicio));
						$hora_fin = date("h:i A", strtotime($horario->HorarioFin));
						
						$dato .= '<tr><td style="border: none; padding: 2px 4px;"><b>'.$col.':</b></td><td style="border: none; padding: 2px 4px;">'.$hora_ini.' a '.$hora_fin.'</td></tr>';
					}
				}
				
				if($dato != '')
				{
					$dato = '<div class="accordion" id="accordionHorarios">
						<div class="accordion-group">
							<div id="horarios'.$data->IDPuntoDeVenta.'" class="accordion-body collapse">
								<table border="0">'.$dato.'</table>
							</div>
							<center><button class="btn btn-xs btn-info" data-toggle="collapse" href="#horarios'.$data->IDPuntoDeVenta.'">Ver Horarios</button></center>
						</div>
					</div>';
				}

				break;
        }
        
        return $dato;
    }
}
