<?php

/**
 * This is the model class for table "m_PuntoVenta".
 *
 * The followings are the available columns in table 'm_PuntoVenta':
 * @property integer $IDPuntoDeVenta
 * @property integer $IDZona
 * @property integer $IDSede
 * @property integer $IDCEDI
 * @property integer $IDSector
 * @property integer $IDTipoNegocio
 * @property string $IDComercial
 * @property integer $CodigoContable
 * @property string $NombrePuntoDeVenta
 * @property string $IdCentroCostos
 * @property string $NombreCortoPuntoDeVenta
 * @property string $DireccionPuntoDeVenta
 * @property string $BarrioConIndicaciones
 * @property integer $IDUbicacion
 * @property integer $CodigoCiudad
 * @property string $Estado
 * @property string $FechaCreacionRegistro
 * @property string $FechaModificacionRegistro
 * @property string $eMailPuntoDeVenta
 * @property integer $EstratoPuntoDeVenta
 * @property string $CedulaAdministrador
 * @property string $CedulaSubAdministrador
 * @property string $IPCamara
 * @property string $DireccionIPServidor
 * @property string $RutaImagenMapa
 * @property double $DimensionFondo
 * @property double $DimensionAncho
 * @property double $AreaLocal
 * @property string $Resoluciones
 * @property string $DireccionGoogle
 * @property double $LatitudGoogle
 * @property double $LongitudGoogle
 * @property string $CallCenter
 * @property integer $HorarioAperturaLunesASabado
 * @property integer $HorarioAperturaDomingo
 * @property integer $HorarioAperturaFestivo
 * @property integer $HorarioAperturaEspecial
 * @property integer $HorarioDomicilioLunesASabado
 * @property integer $HorarioDomicilioDomingo
 * @property integer $HorarioDomicilioFestivo
 * @property integer $HorarioDomicilioEspecial
 * @property integer $CodCiudadLRV
 * @property integer $IDSectorLRV
 *
 * The followings are the available model relations:
 * @property Aperturascierrespuntosdeventa[] $tAperturascierrespuntosdeventas
 * @property Imagenespuntosdeventa[] $tImagenespuntosdeventas
 * @property Cedi $iDCEDI
 * 
 * @property Horariospuntosdeventa $horarioApertura
 * @property Horariospuntosdeventa $iDHorarioDomicilios
 * 
 * @property Sector $sector
 * @property Sede $sede
 * @property Tiponegocio $tipoNegocio
 * @property Zona $zona
 * @property Tiposservicios[] $tTiposservicioses
 * @property Telefonospuntodeventa[] $tTelefonospuntodeventas
 * @property UbicacionLocal $ubicacion
 * @property SectorCiudadLRV $sectorCiudadLRV
 */
class PuntoVenta extends CActiveRecord {

    public $search = null;
    
    private $_NombresRelaciones = array(
        'IdCentroCostos' => 'NA',
        'CodigoCiudad' => 'NA',
        'CedulaAdministrador' => 'NA',
        'CedulaSubAdministrador' => 'NA',
        'IDSectorLRV' => 'NA',
    );
    private $_horariosEspecialesDia = array();
    private $_horariosEspecialesRango = array();
    private $_oldAttributes = array();

    public function getNombreCentroCostos() {
        if (!$this->isNewRecord && $this->IdCentroCostos !== null && $this->_NombresRelaciones['IdCentroCostos'] == "NA") {
            try {
                $costos = CentroCostos::model()->findByPk($this->IdCentroCostos);

                if ($costos !== null) {
                    $this->_NombresRelaciones['IdCentroCostos'] = $costos->NombreCentroCostos;
                }
            } catch (Exception $exc) {
                Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
            }
        }

        return $this->_NombresRelaciones['IdCentroCostos'];
    }

    public function getNombreCiudad() {
        if (!$this->isNewRecord && $this->CodigoCiudad !== null && $this->_NombresRelaciones['CodigoCiudad'] == "NA") {
            try {
                $ciudad = Ciudad::model()->find(array(
                    'condition' => 'CodCiudad=:ciudad',
                    'params' => array('ciudad' => $this->CodigoCiudad)
                ));

                if ($ciudad !== null) {
                    $this->_NombresRelaciones['CodigoCiudad'] = $ciudad->NombreCiudad;
                }
            } catch (Exception $exc) {
                Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
            }
        }

        return $this->_NombresRelaciones['CodigoCiudad'];
    }

    public function getNombreAdministrador() {
        if (!$this->isNewRecord && $this->CedulaAdministrador !== null && $this->_NombresRelaciones['CedulaAdministrador'] == "NA") {
            try {
                $persona1 = Persona::model()->find(array(
                    'condition' => 'NumeroDocumento=:cedula',
                    'params' => array(
                        ':cedula' => $this->CedulaAdministrador
                )));

                if ($persona1 !== null) {
                    $this->_NombresRelaciones['CedulaAdministrador'] = $persona1->ApellidosNombres;
                }
            } catch (Exception $exc) {
                Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
            }
        }
        return $this->_NombresRelaciones['CedulaAdministrador'];
    }

    public function getNombreSubAdministrador() {
        if (!$this->isNewRecord && $this->CedulaSubAdministrador !== null && $this->_NombresRelaciones['CedulaSubAdministrador'] == "NA") {
            try {
                $persona2 = Persona::model()->find(array(
                    'condition' => 'NumeroDocumento=:cedula',
                    'params' => array(
                        ':cedula' => $this->CedulaSubAdministrador
                )));

                if ($persona2 !== null) {
                    $this->_NombresRelaciones['CedulaSubAdministrador'] = $persona2->ApellidosNombres;
                }
            } catch (Exception $exc) {
                Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
            }
        }

        return $this->_NombresRelaciones['CedulaSubAdministrador'];
    }

    public function getNombreSectorLRV() {
        if (!$this->isNewRecord && $this->IDSectorLRV !== null && $this->_NombresRelaciones['IDSectorLRV'] == "NA") {
            try {
                $sector = SectorLRV::model()->find(array(
                    'condition' => 'IDSectorLRV=:sectorlrv',
                    'params' => array(':sectorlrv' => $this->IDSectorLRV)
                ));

                if ($sector !== null) {
                    $this->_NombresRelaciones['IDSectorLRV'] = $sector->NombreSector;
                }
            } catch (Exception $exc) {
                Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
            }
        }

        return $this->_NombresRelaciones['IDSectorLRV'];
    }

    private function resetAuxiliarData($oldAttributes, $newAttributes) {
        foreach ($this->_NombresRelaciones as $atributo => $nombre) {
            if ($oldAttributes[$atributo] != $newAttributes[$atributo]) {
                $this->_NombresRelaciones[$atributo] = "NA";
            }
        }
        $this->_oldAttributes = $newAttributes;
    }
    
    public function afterFind() {
        $this->_oldAttributes = $this->getAttributes();
        parent::afterFind();
    }

    public function afterSave() {
        if ($this->isNewRecord) {
            $this->_oldAttributes = $this->getAttributes();
        }

        $this->resetAuxiliarData($this->_oldAttributes, $this->getAttributes());
        parent::afterSave();
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'm_PuntoVenta';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            //array('IDPuntoDeVenta', 'required', 'on' => 'update', 'message' => '{attribute} no puede estar vac&iacute;o'),
            //array('IDZona, IDCEDI, IDSector, IDTipoNegocio, Estado, NombrePuntoDeVenta, NombreCortoPuntoDeVenta, CodigoCiudad', 'required'),
            array('Estado,CSC, NombrePuntoDeVenta, NombreCortoPuntoDeVenta, IDComercial, CodigoContable, IdCentroCostos, CallCenter', 'required', 'message' => '{attribute} no puede estar vac&iacute;o'),
            //array('HorarioAperturaLunesASabado', 'required', 'on' => 'horario'),
            array('IDZona, IDCEDI, IDSector, CodigoCiudad, CodCiudadLRV, IDSectorLRV', 'required', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('IDZona, IDSede, IDCEDI, IDSector, IDTipoNegocio, IDUbicacion, CodigoContable, CodigoCiudad, HorarioAperturaLunesASabado, HorarioAperturaDomingo, HorarioAperturaFestivo, HorarioAperturaEspecial, HorarioDomicilioLunesASabado, HorarioDomicilioDomingo, HorarioDomicilioFestivo, HorarioDomicilioEspecial, EstratoPuntoDeVenta, CodCiudadLRV, IDSectorLRV', 'numerical', 'integerOnly' => true),
            array('DimensionFondo, DimensionAncho, AreaLocal, LatitudGoogle, LongitudGoogle', 'numerical'),
            array('IDComercial', 'length', 'max' => 10),
            array('IDComercial, CodigoContable', 'validateExist'),
            array('NombrePuntoDeVenta, NombreCortoPuntoDeVenta, DireccionPuntoDeVenta, eMailPuntoDeVenta, RutaImagenMapa', 'length', 'max' => 100),
            array('eMailPuntoDeVenta', 'email', 'allowEmpty' => true),
            array('eMailPuntoDeVenta', 'default', 'value' => null),
            array('IdCentroCostos', 'length', 'max' => 255),
            array('CallCenter', 'length', 'max' => 2),
            array('IdCentroCostos', 'validateCostosExist'),
            array('IDZona', 'validateZonaExist'),
            array('BarrioConIndicaciones', 'length', 'max' => 60),
            array('Estado,CSC', 'numerical', 'integerOnly' => true),
            array('Estado,CSC', 'in', 'range' => array('0', '1'), 'allowEmpty' => false),
            array('CedulaAdministrador, CedulaSubAdministrador, IPCamara, DireccionIPServidor', 'length', 'max' => 20),
            array('CedulaAdministrador, CedulaSubAdministrador, IDZona, IDCEDI, IDSector, IDTipoNegocio, CodigoCiudad, DimensionFondo, DimensionAncho', 'default', 'value' => null),
            array('Resoluciones, DireccionGoogle', 'length', 'max' => 255),
            array('CodigoCiudad', 'validateCityExist'),
            array('IDSectorLRV', 'validateSectorLRV'),
            array('CedulaAdministrador, CedulaSubAdministrador', 'validateUserExist'),
            array('FechaCreacionRegistro, FechaModificacionRegistro', 'safe'),
            array('search, IdCentroCostos, IDPuntoDeVenta, IDZona, IDSede, IDCEDI, IDSector, IDTipoNegocio, IDComercial, IDUbicacion, CodigoContable, NombrePuntoDeVenta, NombreCortoPuntoDeVenta, DireccionPuntoDeVenta, BarrioConIndicaciones, CodigoCiudad, Estado,CSC, FechaCreacionRegistro, FechaModificacionRegistro, HorarioAperturaLunesASabado, HorarioAperturaDomingo, HorarioAperturaFestivo, HorarioAperturaEspecial, HorarioDomicilioLunesASabado, HorarioDomicilioDomingo, HorarioDomicilioFestivo, HorarioDomicilioEspecial, eMailPuntoDeVenta, EstratoPuntoDeVenta, CedulaAdministrador, CedulaSubAdministrador, IPCamara, DireccionIPServidor, RutaImagenMapa, DimensionFondo, DimensionAncho, AreaLocal, Resoluciones, DireccionGoogle, LatitudGoogle, LongitudGoogle, CodCiudadLRV, IDSectorLRV, CallCenter', 'safe', 'on' => 'search'),
        );
    }

    /**
     * Valida si existe registro con atributo indicado.
     * Este es un validador declarado en rules().
     */
    public function validateExist($attribute, $params) {
        if (!$this->hasErrors()) {
            $model = null;

            if ($this->isNewRecord) {
                $model = self::model()->find(array(
                    'condition' => "$attribute=:value",
                    'params' => array(
                        'value' => $this->getAttribute($attribute)
                    )
                ));
            } else {
                $model = self::model()->find(array(
                    'condition' => "IDPuntoDeVenta<>:id AND $attribute=:value",
                    'params' => array(
                        'id' => $this->IDPuntoDeVenta,
                        'value' => $this->getAttribute($attribute)
                    )
                ));
            }

            if ($model !== null)
                $this->addError($attribute, $this->getAttributeLabel($attribute) . ' ya existe.');
        }
    }

    /**
     * Valida que exista centro de costos
     */
    public function validateCostosExist($attribute, $params) {
        if (!$this->hasErrors()) {
            $ccosto = CentroCostos::model()->find(array(
                'condition' => "IdCentroCostos=:costos",
                'params' => array(
                    'costos' => $this->IdCentroCostos
                )
            ));

            if ($ccosto == null)
                $this->addError($attribute, $this->getAttributeLabel($attribute) . ' No existe.');
        }
    }

    /**
     * Valida que exista centro de costos
     */
    public function validateZonaExist($attribute, $params) {
        if (!$this->hasErrors()) {
            $zona = Zona::model()->findByPk($this->IDZona);

            if ($zona == null) {
                $this->addError('IDZona', 'Zona no existe');
            } else {
                $this->IDSede = $zona->IDSede;
            }
        }
    }

    /**
     * Valida que exista ciudad
     */
    public function validateCityExist($attribute, $params) {
        if (!$this->hasErrors() && $this->CodigoCiudad != null) {
            $ciudad = Ciudad::model()->find(array(
                'condition' => "CodCiudad=:ciudad",
                'params' => array(
                    'ciudad' => $this->CodigoCiudad
                )
            ));

            if ($ciudad == null)
                $this->addError($attribute, $this->getAttributeLabel($attribute) . ' No existe.');
        }
    }

    /**
     * Valida que exista ciudad
     */
    public function validateSectorLRV($attribute, $params) {
        if (!$this->hasErrors()) {
            $sectorlrv = SectorCiudadLRV::model()->find(array(
                'condition' => 'CodCiudad=:ciudad AND IDSectorLRV=:sectorlrv AND Estado=:estado',
                'params' => array(
                    ':ciudad' => $this->CodigoCiudad,
                    ':sectorlrv' => $this->IDSectorLRV,
                    ':estado' => 1
                )
            ));

            if ($sectorlrv == null)
                $this->addError($attribute, $this->getAttributeLabel($attribute) . ' no existe en ciudad seleccionada.');
        }
    }

    /**
     * Valida que exista empleado con cedula indicada y que este activo.
     * Este es un validador declarado en rules().
     */
    public function validateUserExist($attribute, $params) {
        if (!$this->hasErrors() && $this->getAttribute($attribute) != null) {
            $empleado = Empleado::model()->find(array(
                'join' => 'INNER JOIN m_EstadoEmpleado as estado ON (estado.IdEstado = t.IdEstado)',
                'condition' => "estado.Estado=:estado AND NumeroDocumento=:cedula",
                'params' => array(
                    'estado' => Yii::app()->controller->module->asocActivo,
                    'cedula' => $this->getAttribute($attribute)
                )
            ));

            if ($empleado == null)
                $this->addError($attribute, $this->getAttributeLabel($attribute) . ' No existe / inactivo.');
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
            'ciudad' => array(self::BELONGS_TO, 'Ciudad', '', 'on' => 'ciudad.CodCiudad = t.CodigoCiudad'),
            'aperturascierres' => array(self::HAS_MANY, 'AperturaCierrePuntoVenta', 'IDPuntoDeVenta'),
            'imagenes' => array(self::HAS_MANY, 'ImagenPuntoVenta', 'IDPuntoDeVenta'),
            'cedi' => array(self::BELONGS_TO, 'Cedi', 'IDCEDI'),
            'horarioAperturaLunesASabado' => array(self::BELONGS_TO, 'HorarioPuntoVenta', 'HorarioAperturaLunesASabado'),
            'horarioAperturaDomingo' => array(self::BELONGS_TO, 'HorarioPuntoVenta', 'HorarioAperturaDomingo'),
            'horarioAperturaFestivo' => array(self::BELONGS_TO, 'HorarioPuntoVenta', 'HorarioAperturaFestivo'),
            'horarioAperturaEspecial' => array(self::BELONGS_TO, 'HorarioPuntoVenta', 'HorarioAperturaEspecial'),
            'horarioDomicilioLunesASabado' => array(self::BELONGS_TO, 'HorarioPuntoVenta', 'HorarioDomicilioLunesASabado'),
            'horarioDomicilioDomingo' => array(self::BELONGS_TO, 'HorarioPuntoVenta', 'HorarioDomicilioDomingo'),
            'horarioDomicilioFestivo' => array(self::BELONGS_TO, 'HorarioPuntoVenta', 'HorarioDomicilioFestivo'),
            'horarioDomicilioEspecial' => array(self::BELONGS_TO, 'HorarioPuntoVenta', 'HorarioDomicilioEspecial'),
            'horariosEspeciales' => array(self::HAS_MANY, 'HorarioEspecialPuntoVenta', 'IDPuntoDeVenta'),
            'sector' => array(self::BELONGS_TO, 'Sector', 'IDSector'),
            'sede' => array(self::BELONGS_TO, 'Sede', 'IDSede'),
            'tipoNegocio' => array(self::BELONGS_TO, 'TipoNegocio', 'IDTipoNegocio'),
            'zona' => array(self::BELONGS_TO, 'Zona', 'IDZona'),
            'ubicacion' => array(self::BELONGS_TO, 'UbicacionLocal', 'IDUbicacion'),
            'tiposServicio' => array(self::MANY_MANY, 'TipoServicio', 't_ServiciosPuntoVenta(IDPuntoDeVenta, IDTipoServicio)'),
            'telefonos' => array(self::HAS_MANY, 'TelefonosPuntoVenta', 'IDPuntoDeVenta'),
            'sectorCiudadLRV' => array(self::BELONGS_TO, 'SectorCiudadLRV', 'CodCiudadLRV, IDSectorLRV'),
                //'sectorCiudadLRV' => array(self::BELONGS_TO, 'SectorCiudadLRV', 'IDSectorCiudadLRV', 'condition'=>'IDSectorCiudadLRV<>0'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'IDPuntoDeVenta' => 'ID',
            'IDZona' => 'Zona',
            'IDSede' => 'Sede',
            'IDCEDI' => 'CEDI',
            'IDSector' => 'Sector',
            'IDTipoNegocio' => 'Tipo Negocio',
            'IDComercial' => 'ID Comercial',
            'CodigoContable' => 'C&oacute;digo Contable',
            'IdCentroCostos' => 'Centro Costo',
            'NombrePuntoDeVenta' => 'Nombre',
            'NombreCortoPuntoDeVenta' => 'Nombre Corto',
            'DireccionPuntoDeVenta' => 'Direcci&oacute;n',
            'BarrioConIndicaciones' => 'Barrio (indicaciones)',
            'IDUbicacion' => 'Ubicaci&oacute;n',
            'CodigoCiudad' => 'Ciudad',
            'Estado' => 'Estado',
            'CSC'=>'CSC',
            'FechaCreacionRegistro' => 'Fecha Creaci&oacute;n',
            'FechaModificacionRegistro' => 'Fecha Modificaci&oacute;n',
            'HorarioAperturaLunesASabado' => 'Horario Apertura Lunes-Sabado',
            'HorarioAperturaDomingo' => 'Horario Apertura Domingo',
            'HorarioAperturaFestivo' => 'Horario Apertura Festivo',
            'HorarioAperturaEspecial' => 'Horario Apertura Especial',
            'HorarioDomicilioLunesASabado' => 'Horario Domicilio Lunes-Sabado',
            'HorarioDomicilioDomingo' => 'Horario Domicilio Domingo',
            'HorarioDomicilioFestivo' => 'Horario Domicilio Festivo',
            'HorarioDomicilioEspecial' => 'Horario Domicilio Especial',
            'eMailPuntoDeVenta' => 'E-mail PDV',
            'EstratoPuntoDeVenta' => 'Estrato PDV',
            'CedulaAdministrador' => 'C&eacute;dula Administrador',
            'CedulaSubAdministrador' => 'C&eacute;dula SubAdministrador',
            'IPCamara' => 'IP Camara',
            'DireccionIPServidor' => 'IP Servidor',
            'RutaImagenMapa' => 'Ruta imagen mapa',
            'DimensionFondo' => 'Dimensi&oacute;n fondo',
            'DimensionAncho' => 'Dimensi&oacute;n ancho',
            'AreaLocal' => '&Aacute;rea local',
            'Resoluciones' => 'Resoluci&oacute;n',
            'DireccionGoogle' => 'Direcci&oacute;n Google',
            'LatitudGoogle' => 'Latitud Google',
            'LongitudGoogle' => 'Longitud Google',
            'CallCenter' => 'Call Center',
            'CodCiudadLRV' => 'Ciudad LRV',
            'IDSectorLRV' => 'Sector LRV',
        );
    }

    /**
     * Metodo que hereda comportamiento de ValidateModelBehavior
     * @return void
     */
    public function behaviors() {
        return array(
            'ValidateModelBehavior' => array(
                'class' => 'application.components.behaviors.ValidateModelBehavior',
                'model' => $this
            ),
            'AuditFieldBehavior' => array(
                'class' => 'audit.components.AuditFieldBehavior'
            )
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search($todo = false) {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        if ($this->search !== null && !empty($this->search)) {
            $palabras = explode(Yii::app()->params->searchSeparator, $this->search);

            foreach ($palabras as $palabra) {
                $criteria->compare('IDComercial', $palabra, true, 'OR');
                //$criteria->compare('CodigoContable', $palabra, true);
                //$cirteria->compare('IdCentroCostos', $palabre, true);
                $criteria->compare('NombrePuntoDeVenta', $palabra, true, 'OR');
                $criteria->compare('NombreCortoPuntoDeVenta', $palabra, true, 'OR');
                $criteria->compare('DireccionPuntoDeVenta', $palabra, true, 'OR');
                $criteria->compare('BarrioConIndicaciones', $palabra, true, 'OR');
                $criteria->compare('CodigoCiudad', $palabra, true, 'OR');
                //$criteria->compare('Estado', $palabra);
                //$criteria->compare('FechaCreacionRegistro', $palabra, true);

                $criteria->compare('eMailPuntoDeVenta', $palabra, true, 'OR');
                //$criteria->compare('EstratoPuntoDeVenta', $palabra, true);
                $criteria->compare('CedulaAdministrador', $palabra, true, 'OR');
                $criteria->compare('CedulaSubAdministrador', $palabra, true, 'OR');
                $criteria->compare('IPCamara', $palabra, true, 'OR');
                $criteria->compare('DireccionIPServidor', $palabra, true, 'OR');
                $criteria->compare('RutaImagenMapa', $palabra, true, 'OR');
                //$criteria->compare('DimensionFondo', $palabra, true);
                //$criteria->compare('DimensionAncho', $palabra, true);
                //$criteria->compare('AreaLocal', $palabra, true);
                $criteria->compare('Resoluciones', $palabra, true, 'OR');
                $criteria->compare('DireccionGoogle', $palabra, true, 'OR');
                //$criteria->compare('LatitudGoogle', $palabra, true);
                //$criteria->compare('LongitudGoogle', $palabra, true);
            }
        } else {

            //2015-11-26 - Se busca por el barrio de influencia
            $ciudad = Yii::app()->getSession()->get('BusquedaCiudadPDV');
            $barrio = Yii::app()->getSession()->get('BusquedaBarrioPDV');

            if (!is_null($ciudad) && $ciudad != "" && !is_null($barrio) && $barrio != "") {
                $criteria->distinct = true;
                $criteria->join = 'INNER JOIN t_InfluenciaPuntoVenta i ON t.IdPuntoDeVenta = i.IdPuntoDeVenta 
					INNER JOIN m_Barrio b ON i.IdBarrio = b.IdBarrio';
                $criteria->addCondition('CodigoCiudad=:CodigoCiudad');
                $criteria->params[':CodigoCiudad'] = $ciudad;
                $criteria->addSearchCondition('b.NombreBarrio', $barrio);
            }
            //2015-11-26 - Fin

            $criteria->compare('IDPuntoDeVenta', $this->IDPuntoDeVenta);
            $criteria->compare('IDZona', $this->IDZona);
            $criteria->compare('IDSede', $this->IDSede);
            $criteria->compare('IDCEDI', $this->IDCEDI);
            $criteria->compare('IDSector', $this->IDSector);
            $criteria->compare('IDTipoNegocio', $this->IDTipoNegocio);
            $criteria->compare('IDComercial', $this->IDComercial, true);
            $criteria->compare('CodigoContable', $this->CodigoContable);
            $criteria->compare('IdCentroCostos', $this->IdCentroCostos, true);
            $criteria->compare('NombrePuntoDeVenta', $this->NombrePuntoDeVenta, true);
            $criteria->compare('NombreCortoPuntoDeVenta', $this->NombreCortoPuntoDeVenta, true);
            $criteria->compare('DireccionPuntoDeVenta', $this->DireccionPuntoDeVenta, true);
            $criteria->compare('BarrioConIndicaciones', $this->BarrioConIndicaciones, true);
            $criteria->compare('CodigoCiudad', $this->CodigoCiudad);
            $criteria->compare('Estado', $this->Estado);
            $criteria->compare('CSC', $this->CSC);
            $criteria->compare('FechaCreacionRegistro', $this->FechaCreacionRegistro, true);
            $criteria->compare('FechaModificacionRegistro', $this->FechaModificacionRegistro, true);

            $criteria->compare('HorarioAperturaLunesASabado', $this->HorarioAperturaLunesASabado);
            $criteria->compare('HorarioAperturaDomingo', $this->HorarioAperturaDomingo);
            $criteria->compare('HorarioAperturaFestivo', $this->HorarioAperturaFestivo);
            $criteria->compare('HorarioAperturaEspecial', $this->HorarioAperturaEspecial);
            $criteria->compare('HorarioDomicilioLunesASabado', $this->HorarioDomicilioLunesASabado);
            $criteria->compare('HorarioDomicilioDomingo', $this->HorarioDomicilioDomingo);
            $criteria->compare('HorarioDomicilioFestivo', $this->HorarioDomicilioFestivo);
            $criteria->compare('HorarioDomicilioEspecial', $this->HorarioDomicilioEspecial);

            $criteria->compare('eMailPuntoDeVenta', $this->eMailPuntoDeVenta, true);
            $criteria->compare('EstratoPuntoDeVenta', $this->EstratoPuntoDeVenta);
            $criteria->compare('CedulaAdministrador', $this->CedulaAdministrador, true);
            $criteria->compare('CedulaSubAdministrador', $this->CedulaSubAdministrador, true);
            $criteria->compare('IPCamara', $this->IPCamara, true);
            $criteria->compare('DireccionIPServidor', $this->DireccionIPServidor, true);
            $criteria->compare('RutaImagenMapa', $this->RutaImagenMapa, true);
            $criteria->compare('DimensionFondo', $this->DimensionFondo, true);
            $criteria->compare('DimensionAncho', $this->DimensionAncho, true);
            $criteria->compare('AreaLocal', $this->AreaLocal, true);
            $criteria->compare('Resoluciones', $this->Resoluciones, true);
            $criteria->compare('DireccionGoogle', $this->DireccionGoogle, true);
            $criteria->compare('LatitudGoogle', $this->LatitudGoogle, true);
            $criteria->compare('LongitudGoogle', $this->LongitudGoogle, true);

            $criteria->compare('CallCenter', $this->CallCenter, true);
            $criteria->compare('CodCiudadLRV', $this->CodCiudadLRV);
            $criteria->compare('IDSectorLRV', $this->IDSectorLRV);
        }

        if ($todo) {
            $criteria->with = array(
                'horarioAperturaLunesASabado',
                'horarioAperturaDomingo',
                'horarioAperturaFestivo',
                'horarioAperturaEspecial',
                'horarioDomicilioLunesASabado',
                'horarioDomicilioDomingo',
                'horarioDomicilioFestivo',
                'horarioDomicilioEspecial');

            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
                'pagination' => false
            ));
        } else {

            return new CActiveDataProvider($this, array(
                'criteria' => $criteria
            ));
        }
    }

    /*
      public static function searchObject($condiciones) {
      $campos = array(
      'idpv' => 'IDPuntoDeVenta',
      'codcomercialpv' => 'IDComercial',
      'codcontablepv' => 'CodigoContable',
      'centrocostopv' => 'IdCentroCostos',
      'nompv' => 'NombrePuntoDeVenta',
      'nomcortopv' => 'NombreCortoPuntoDeVenta',
      'estadopv' => 'Estado',
      'mailpv' => 'eMailPuntoDeVenta',
      'estratopv' => 'EstratoPuntoDeVenta',
      'cedulaadminpv' => 'CedulaAdministrador',
      'cedulasubadminpv' => 'CedulaSubAdministrador',
      'ipcamarapv' => 'IPCamara',
      'ipservidorpv' => 'DireccionIPServidor',
      'resolucionpv' => 'Resoluciones',
      'codciudad' => 'ciudad.CodCiudad',
      'nomciudad' => 'ciudad.NombreCiudad',
      'codsede' => 'sede.CodigoSede',
      'nomsede' => 'sede.NombreSede',
      'nomzona' => 'zona.NombreZona',
      'nomcedi' => 'cedi.NombreCEDI',
      'codsector' => 'sector.CodigoSector',
      'nomsector' => 'sector.NombreSector',
      'idnegocio' => 'tipoNegocio.IDTipoNegocio',
      'nomnegocio' => 'tipoNegocio.NombreTipoNegocio',
      'nomubicacion' => 'ubicacion.NombreTipoNegocio'
      );

      $criteria = new CDbCriteria;
      $criteria->join = 'INNER JOIN m_Ciudad as ciudad ON (t.CodigoCiudad = ciudad.CodCiudad)';
      $criteria->with = array('sede', 'zona', 'cedi', 'sector', 'ubicacion', 'tipoNegocio'); //faltan servicios

      foreach ($condiciones as $key => $value) {
      if (isset($campos[$key])) {
      $criteria->compare($campos[$key], $value, true);
      }
      }

      return PuntoVenta::model()->findAll($criteria);
      }
     */

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PuntoVenta the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function beforeSave() {
        if ($this->isNewRecord) {
            $this->FechaCreacionRegistro = new CDbExpression('NOW()');
        }
        $this->FechaModificacionRegistro = new CDbExpression('NOW()');
        return parent::beforeSave();
    }

    public static function getCantidad($estado = null) {
        $params = array();
        $condition = "";

        if ($estado !== null) {
            $params = array(':estado' => $estado);
            $condition = "WHERE estado=:estado";
        }

        $cantidad = Yii::app()->db->createCommand("SELECT COUNT(*) FROM m_PuntoVenta $condition")->queryScalar($params);
        return $cantidad;
    }

    public static function getCantidadTipoNegocio($tipo, $estado = null) {
        $params = array(':tipo' => $tipo);
        $condition = "WHERE IDTipoNegocio=:tipo";

        if ($estado !== null) {
            $params[':estado'] = $estado;
            $condition .= " AND estado=:estado";
        }

        $cantidad = Yii::app()->db->createCommand("SELECT COUNT(*) FROM m_PuntoVenta $condition")->queryScalar($params);
        return $cantidad;
    }

    public static function getCantidadTipoServicio($tipo, $estado = null) {
        $params = array(':tipo' => $tipo);
        $condition = "WHERE spv.IDTipoServicio=:tipo";

        if ($estado !== null) {
            $params[':estado'] = $estado;
            $condition .= " AND pv.Estado=:estado";
        }

        $cantidad = Yii::app()->db->createCommand("SELECT COUNT(DISTINCT pv.IDPuntoDeVenta) FROM m_PuntoVenta AS pv JOIN t_ServiciosPuntoVenta AS spv ON (pv.IDPuntoDeVenta = spv.IDPuntoDeVenta)  $condition")->queryScalar($params);
        return $cantidad;
    }

    public static function getCantidadCiudad($estado = null) {
        $params = array();
        $condition = "";

        if ($estado !== null) {
            $params = array(':estado' => $estado);
            $condition = "WHERE estado=:estado";
        }

        $cantidad = Yii::app()->db->createCommand("SELECT COUNT(DISTINCT CodigoCiudad) FROM m_PuntoVenta $condition")->queryScalar($params);
        return $cantidad;
    }

    public static function getNombre($id) {
        if ($id === null)
            return "";

        $model = self::model()->findByPk($id);

        if ($model !== null)
            return $model->NombrePuntoDeVenta;
        else
            return "";
    }

    public static function searchObject($condiciones) {
        $campos = array(
            'idpv' => 'IDPuntoDeVenta',
            'codcomercialpv' => 'IDComercial',
            'codcontablepv' => 'CodigoContable',
            'centrocostopv' => 'IdCentroCostos',
            'nompv' => 'NombrePuntoDeVenta',
            'nomcortopv' => 'NombreCortoPuntoDeVenta',
            'estadopv' => 'Estado',
            'CSC'=>'CSC',
            'mailpv' => 'eMailPuntoDeVenta',
            'estratopv' => 'EstratoPuntoDeVenta',
            'cedulaadminpv' => 'CedulaAdministrador',
            'cedulasubadminpv' => 'CedulaSubAdministrador',
            'ipcamarapv' => 'IPCamara',
            'ipservidorpv' => 'DireccionIPServidor',
            'resolucionpv' => 'Resoluciones',
            'codciudad' => 'ciudad.CodCiudad',
            'nomciudad' => 'ciudad.NombreCiudad',
            'codsede' => 'sede.CodigoSede',
            'nomsede' => 'sede.NombreSede',
            'nomzona' => 'zona.NombreZona',
            'idzona' => 'zona.IDZona',
            'nomcedi' => 'cedi.NombreCEDI',
            'codsector' => 'sector.CodigoSector',
            'nomsector' => 'sector.NombreSector',
            'idnegocio' => 'tipoNegocio.IDTipoNegocio',
            'nomnegocio' => 'tipoNegocio.NombreTipoNegocio',
            'nomubicacion' => 'ubicacion.NombreTipoNegocio',
            'ordenar' => null
        );

        if (empty($condiciones)) {
            return PuntoVenta::model()->findAll(array('with' => 'ciudad'));
        } else {

            $criteria = new CDbCriteria;
            //$criteria->join = 'JOIN m_Ciudad as ciudad ON (t.CodigoCiudad = ciudad.CodCiudad)';
            $criteria->with = array('ciudad', 'sede', 'zona', 'cedi', 'sector', 'ubicacion', 'tipoNegocio'); //faltan servicios

            foreach ($condiciones as $key => $value) {
                if (isset($campos[$key]) && $campos[$key] !== null) {
                    $like = false;
                    $valor = "";
                    $condicional = "AND";

                    if (is_array($value) && isset($value['valor'])) {
                        $valor = $value['valor'];
                        $like = isset($value['like']) ? $value['like'] : false;
                        $condicional = isset($value['condicional']) ? $value['condicional'] : "AND";
                    } else {
                        $valor = $value;
                    }

                    $criteria->compare($campos[$key], $valor, $like, $condicional);
                }
            }
            if (isset($condiciones['ordenar']))
                $criteria->order = $condiciones['ordenar'];
            else
                $criteria->order = 'IDPuntoDeVenta';

            return PuntoVenta::model()->findAll($criteria);
        }
    }

    public function getHorariosEspecialesDia() {
        if (empty($this->_horariosEspecialesDia)) {
            $this->horariosEspeciales();
        }
        
        return $this->_horariosEspecialesDia;
    }

    public function getHorariosEspecialesRango() {
        if (empty($this->_horariosEspecialesRango)) {
            $this->horariosEspeciales();
        }
        
        return $this->_horariosEspecialesRango;
    }

    private function horariosEspeciales() {
        $this->_horariosEspecialesDia = array();
        $this->_horariosEspecialesRango = array();
        
        foreach ($this->horariosEspeciales as $objHorarioPdv) {
            if($objHorarioPdv->IdHorarioEspecialDia!=null){
                $this->_horariosEspecialesDia[] = $objHorarioPdv->objHorarioEspecialDia;
            }
            
            if($objHorarioPdv->IdHorarioEspecialRango!=null){
                $this->_horariosEspecialesRango[] = $objHorarioPdv->objHorarioEspecialRango;
            }
        }
    }

}
