<?php

class HorarioEspecialController extends Controller {

    public function actionIndex() {

        if (!Yii::app()->user->checkAccess('PuntoDeVenta_HorarioEspecial_index')) {
            echo CJSON::encode(array('result' => 'error', 'response' => 'Error: 101 => ' . Yii::app()->params['accessError']));
            Yii::app()->end();
        }

        $objEmpleado = Empleado::model()->find(array(
            'order' => 'idEmpleado DESC',
            'with' => array('estado'),
            'condition' => 't.NumeroDocumento=:cedula AND estado.Estado=:estado',
            'params' => array(':cedula' => Yii::app()->user->name, ':estado' => Yii::app()->controller->module->asocActivo)
        ));

        if ($objEmpleado === null) {
            throw new CHttpException(404, 'Empleado no existente o inactivo');
        }

        $objSede = Sede::model()->find(array(
            'condition' => 'CodigoSede=:sede',
            'params' => array(
                ':sede' => str_replace("0", "", $objEmpleado->IdOperaciones)
            )
        ));

        if ($objSede == null) {
            throw new CHttpException(404, 'Empleado no asociado a Sede');
        }

        $model = new HorarioEspecialForm;
        $model->IDSede = $objSede->IDSede;

        if (isset($_POST['HorarioEspecialForm'])) {
            $model->attributes = $_POST['HorarioEspecialForm'];

            /* empty($model->listPuntoVentaCheck */
            if ($model->paso == 1) {
                $model->getPuntosVenta();
                $model->paso = 2;
            } else if (empty($model->listPuntoVentaCheck)) {
                $model->getPuntosVenta();
                $model->addError('listPuntoVentaCheck', 'Seleccionar puntos de venta');
            } else {
                $model->getPuntosVentaCheck();
                Yii::app()->session["pdv.online.horarioespecial.modelespecialform"] = $model;
                $modelDia = new HorarioEspecialDia;
                $modelDia->Es24Horas = null;
                $modelRango = new HorarioEspecialRango;
                $modelRango->Es24HorasLunesASabado = null;
                $modelRango->Es24HorasDomingo = null;
                $modelRango->Es24HorasFestivo = null;
                $this->render('horario', array('modelDia' => $modelDia, 'modelRango' => $modelRango));
                Yii::app()->end();
            }

            //CVarDumper::dump($model,10,true);
        } else if (isset($_POST['HorarioEspecialDia']) || isset($_POST['HorarioEspecialRango'])) {
            $model = null;

            if (isset(Yii::app()->session["pdv.online.horarioespecial.modelespecialform"]) && Yii::app()->session["pdv.online.horarioespecial.modelespecialform"] != null)
                $model = Yii::app()->session["pdv.online.horarioespecial.modelespecialform"];

            if ($model == null) {
                Yii::app()->user->setFlash('warning', "Error al recuperar puntos de venta seleccionados.");
                $this->redirect($this->createUrl('index'));
            }

            if (isset($_POST['HorarioEspecialDia'])) {
                $transaction = Yii::app()->db->beginTransaction();
                try {
                    $modelDia = new HorarioEspecialDia;
                    $modelDia->NumeroDocumento = Yii::app()->user->name;
                    $modelDia->attributes = $_POST['HorarioEspecialDia'];

                    if ($modelDia->validate()) {
                        $modelDia->save();

                        $correosPDv = array();
                        $correosZona = array();
                        foreach ($model->listPuntoVenta as $objPuntoVenta) {
                            //if ($idPdv != '*') {
                            $correosPDv[] = $objPuntoVenta->eMailPuntoDeVenta;
                            $correosZona[$objPuntoVenta->IDZona] = $objPuntoVenta->zona->eMailDirectorZona;
                            $objHorarioPdv = new HorarioEspecialPuntoVenta;
                            $objHorarioPdv->IDPuntoDeVenta = $objPuntoVenta->IDPuntoDeVenta;
                            $objHorarioPdv->IdHorarioEspecialDia = $modelDia->IdHorarioEspecialDia;
                            $objHorarioPdv->save();
                            //}
                        }

                        Yii::app()->user->setFlash('success', "Correos:" . CVarDumper::dumpAsString($correosPDv));
                        $this->correo($correosPDv, $correosZona, $modelDia);

                        $transaction->commit();
                        Yii::app()->user->setFlash('success', "Horario especial guardado correctamente.");
                        $this->redirect($this->createUrl('index'));
                    } else {
                        $modelRango = new HorarioEspecialRango;
                        $modelRango->Es24HorasLunesASabado = null;
                        $modelRango->Es24HorasDomingo = null;
                        $modelRango->Es24HorasFestivo = null;
                        $this->render('horario', array('modelDia' => $modelDia, 'modelRango' => $modelRango, 'tipoHorario' => 1));
                    }
                } catch (Exception $exc) {
                    Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                    try {
                        $transaction->rollBack();
                    } catch (Exception $txexc) {
                        Yii::log($txexc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                    }

                    Yii::app()->user->setFlash('danger', "Error al guardar horario especial: " . $exc->getMessage());
                    $this->redirect($this->createUrl('index'));
                    Yii::app()->end();
                }
            } else if (isset($_POST['HorarioEspecialRango'])) {
                $transaction = Yii::app()->db->beginTransaction();
                try {
                    $modelRango = new HorarioEspecialRango;
                    $modelRango->NumeroDocumento = Yii::app()->user->name;
                    $modelRango->attributes = $_POST['HorarioEspecialRango'];

                    if ($modelRango->validate()) {
                        $modelRango->save();

                        $correosPDv = array();
                        $correosZona = array();
                        foreach ($model->listPuntoVenta as $objPuntoVenta) {
                            //if ($idPdv != '*') {
                            $correosPDv[] = $objPuntoVenta->eMailPuntoDeVenta;
                            $correosZona[$objPuntoVenta->IDZona] = $objPuntoVenta->zona->eMailDirectorZona;
                            $objHorarioPdv = new HorarioEspecialPuntoVenta;
                            $objHorarioPdv->IDPuntoDeVenta = $objPuntoVenta->IDPuntoDeVenta;
                            $objHorarioPdv->IdHorarioEspecialRango = $modelRango->IdHorarioEspecialRango;
                            $objHorarioPdv->save();
                            //}
                        }

                        Yii::app()->user->setFlash('success', "Correos:" . CVarDumper::dumpAsString($correosPDv));
                        $this->correo($correosPDv, $correosZona, $modelRango);

                        $transaction->commit();
                        Yii::app()->user->setFlash('success', "Horario especial guardado correctamente.");
                        $this->redirect($this->createUrl('index'));
                    } else {
                        $modelDia = new HorarioEspecialDia;
                        $modelDia->Es24Horas = null;
                        $this->render('horario', array('modelDia' => $modelDia, 'modelRango' => $modelRango, 'tipoHorario' => 2));
                    }
                } catch (Exception $exc) {
                    Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                    try {
                        $transaction->rollBack();
                    } catch (Exception $txexc) {
                        Yii::log($txexc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                    }

                    Yii::app()->user->setFlash('danger', "Error al guardar horario especial: " . $exc->getMessage());
                    $this->redirect($this->createUrl('index'));
                    Yii::app()->end();
                }
            }

            Yii::app()->end();
        }

        Yii::app()->session["pdv.online.horarioespecial.modelespecialform"] = null;
        $this->render('index', array('model' => $model));
    }

    private function correo($address, $ccAddress, $model) {
        if (isset(Yii::app()->params->mailUsernameMASM) && isset(Yii::app()->params->mailPasswordMASM)) {
            Yii::import('application.extensions.phpmailer.JPhpMailer');
            $mail = new JPhpMailer;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 25;
            $mail->SMTPAuth = true;
            $mail->Username = Yii::app()->params->mailUsernameMASM;
            $mail->Password = Yii::app()->params->mailPasswordMASM;
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->isHTML(true);
        } else {
            ini_set("SMTP", "mailserver.copservir.com");
            ini_set("smtp_port", "25");
            ini_set("sendmail_from", "mailserver.copservir.com");
            Yii::import('application.extensions.phpmailer.JPhpMailer');
            $mail = new JPhpMailer;
            $mail->isSMTP();
            $mail->Host = 'mailserver.copservir.com';
            $mail->Port = 25;
            $mail->SMTPAuth = false;
            $mail->isHTML(true);
        }

        $mail->From = "empleados@copservir.com";
        $mail->FromName = "SIICOP - Punto Venta";
        $mail->Subject = "SIICOP - Horarios especiales";

        foreach ($address as $to) {
            $mail->addAddress($to);
        }
        
        foreach ($ccAddress as $cc) {
            $mail->addCC($cc);
        }
        
        $objPlantilla = PlantillaCorreo::model()->find(array(
            'condition' => 'funcionalidadPlantilla=:funcionalidad',
            'params' => array(
                ':funcionalidad' => 'puntoventa_horarioespecial'
            )
        ));
        
        $plantilla = "";
        
        if($objPlantilla!==null){
            $plantilla = $objPlantilla->plantilla;
        }

        $cuerpo = $this->renderPartial('_correoHorario', array('model' => $model, 'plantilla' => $plantilla), true, true);
        $mail->Body = $cuerpo;

        if (!$mail->send()) {
            Yii::log("Message could not be sent. " . $mail->ErrorInfo, CLogger::LEVEL_ERROR, 'application');
        }
    }

    /**
     * Consulta cedula de usuario.
     */
    public function actionAjax() {
        $term = null;

        if (Yii::app()->request->isPostRequest)
            $term = $_POST['term'];
        else
            $term = $_GET['term'];

        $results = array();

        if ($term !== null && trim($term) != '') {
            $term = trim($term);

            $criteria = new CDbCriteria;
            $criteria->order = 't.NombreCiudad';
            $criteria->condition = 't.NombreCiudad LIKE :term';
            $criteria->params = array('term' => "%$term%");
            $criteria->limit = 20;

            $models = Ciudad::model()->findAll($criteria);

            foreach ($models as $model) {
                $results[] = array(
                    'label' => $model->NombreCiudad,
                    'value' => $model->CodCiudad,
                );
            }
        }

        echo CJSON::encode($results);
        Yii::app()->end();
    }
    
    public function actionPlantilla(){
        $objPlantilla = PlantillaCorreo::model()->find(array(
            'condition' => 'funcionalidadPlantilla=:funcionalidad',
            'params' => array(
                ':funcionalidad' => 'puntoventa_horarioespecial'
            )
        ));
        
        $model = new PlantillaCorreo;
        
        if($objPlantilla===null){
            $objPlantilla = new PlantillaCorreo;
            $objPlantilla->funcionalidadPlantilla = "puntoventa_horarioespecial";
        }
        
        if(isset($_POST['PlantillaCorreo'])){
            $origPost = Yii::app()->input->getOriginalPost('PlantillaCorreo');
            $model->attributes = $origPost;
            $objPlantilla->plantilla = $model->plantilla;
            
            if($objPlantilla->save()){
                Yii::app()->user->setFlash('success', "Plantilla guardada correctamente");
            }
        }else{
            if($objPlantilla!==null){
                $model->plantilla = $objPlantilla->plantilla;
            }
        }
        
        $this->render('plantilla', array('model'=>$model));
    }

}
