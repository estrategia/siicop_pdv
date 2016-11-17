<?php

/**
 * This is the model class for table "t_cedi".
 *
 * The followings are the available columns in table 't_cedi':
 * @property integer $IDCEDI
 * @property string $NombreCEDI
 * @property string $CedulaJefe
 * @property string $CelularJefe
 * @property string $TelefonoCEDI
 * @property integer $IndicativoTelefonoCEDI
 * @property string $DireccionCEDI
 * @property string $IdCentroCostos
 * @property string $CodigoEAN
 * @property string $HorarioAperturaLunesAViernes
 * @property string $HorarioCierreLunesAViernes
 * @property string $HorarioAperturaSabado
 * @property string $HorarioCierreSabadoo
 * 
 * The followings are the available model relations:
 * @property PuntoVenta[] $puntosVenta
 */
class Cedi extends CActiveRecord {

    private $_NombreJefe = "NA";
    private $_NombreCentroCostos = "NA";

    public function getNombreCentroCostos() {
        return $this->_NombreCentroCostos;
    }

    public function getNombreJefe() {
        return $this->_NombreJefe;
    }

    private function auxiliarData() {
        if (!$this->isNewRecord) {
            try {
                $persona1 = Persona::model()->find(array(
                    'condition' => 'NumeroDocumento=:cedula',
                    'params' => array(
                        ':cedula' => $this->CedulaJefe
                )));

                if ($persona1 !== null) {
                    $this->_NombreJefe = $persona1->ApellidosNombres;
                }
            } catch (Exception $exc) {
                Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
            }


            if ($this->IdCentroCostos != null && !empty($this->IdCentroCostos)) {
                try {
                    $costos = CentroCostos::model()->findByPk($this->IdCentroCostos);

                    if ($costos !== null) {
                        $this->_NombreCentroCostos = $costos->NombreCentroCostos;
                    }
                } catch (Exception $exc) {
                    Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
                }
            }
        }
    }

    public function afterFind() {
        $this->auxiliarData();
        parent::afterFind();
    }

    public function afterSave() {
        $this->auxiliarData();
        parent::afterSave();
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'm_Cedi';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('IDCEDI', 'required', 'on' => 'update', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('NombreCEDI, CedulaJefe, IdCentroCostos, DireccionCEDI', 'required', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('HorarioAperturaLunesAViernes, HorarioCierreLunesAViernes, HorarioAperturaSabado, HorarioCierreSabado', 'required', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('HorarioAperturaLunesAViernes, HorarioCierreLunesAViernes, HorarioAperturaSabado, HorarioCierreSabado', 'date', 'format' => 'HH:mm'),
            array('HorarioCierreLunesAViernes, HorarioCierreSabado', 'validateHour'),
            array('TelefonoCEDI, IndicativoTelefonoCEDI', 'numerical', 'integerOnly' => true),
            array('NombreCEDI, TelefonoCEDI', 'length', 'max' => 45),
            array('CelularJefe, CedulaJefe', 'length', 'max' => 20),
            array('CelularJefe', 'default', 'value' => null),
            array('NombreCEDI', 'validateExist'),
            array('CedulaJefe', 'validateUserExist'),
            array('DireccionCEDI', 'length', 'max' => 100),
            array('IdCentroCostos, CodigoEAN', 'length', 'max' => 255),
            array('IdCentroCostos', 'validateCostosExist'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('IDCEDI, NombreCEDI, CelularJefe, TelefonoCEDI, IndicativoTelefonoCEDI, IdCentroCostos, DireccionCEDI, CodigoEAN, HorarioAperturaLunesAViernes, HorarioCierreLunesAViernes, HorarioAperturaSabado, HorarioCierreSabado', 'safe', 'on' => 'search'),
        );
    }

    public function validateHour($attribute, $params) {
        //if (!$this->hasErrors() && $this->getAttribute($attribute) != null) {

        $horaCierre = $attribute;

        if ($horaCierre == 'HorarioCierreLunesAViernes' || $horaCierre == 'HorarioCierreSabado') {
            $horaApertura = str_replace("Cierre", "Apertura", $horaCierre);
            if ($this->$horaApertura == $this->$horaCierre) {
                $this->addError($horaCierre, 'Hora de cierre debe ser mayor a hora de apertura');
            } else {
                $fecha = new DateTime();
                $dia = $fecha->format('Y-m-d');
                $fechaInicio = DateTime::createFromFormat('Y-m-d H:i:s', $dia . ' ' . $this->$horaApertura . ':00');
                $fechaFin = DateTime::createFromFormat('Y-m-d H:i:s', $dia . ' ' . $this->$horaCierre . ':00');

                if ($fechaInicio->format('i') != '00' && $fechaInicio->format('i') != '30') {
                    $this->addError($horaApertura, 'Minutos debe ser 00 &oacute; 30');
                }

                if ($fechaFin->format('i') != '00' && $fechaFin->format('i') != '30') {
                    $this->addError($horaCierre, 'Minutos debe ser 00 &oacute; 30');
                }

                $diff = $fechaInicio->diff($fechaFin);

                if ($diff->invert == 1) {
                    $this->addError($horaCierre, 'Hora cierre debe ser mayor a hora de apertura');
                }
            }
        } else {
            $this->addError($attribute, 'Validaci&oacute;n incorrecta.');
        }
        //}
    }

    /**
     * Valida que exista empleado con cedula indicada y que este activo.
     * Este es un validador declarado en rules().
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
                    'condition' => "IDCEDI<>:id AND $attribute=:value",
                    'params' => array(
                        'id' => $this->IDCEDI,
                        'value' => $this->getAttribute($attribute)
                    )
                ));
            }

            if ($model !== null)
                $this->addError($attribute, $this->getAttributeLabel($attribute) . ' ya existe.');
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
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'puntosVenta' => array(self::HAS_MANY, 'PuntoVenta', 'IDCEDI'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'IDCEDI' => 'Id CEDI',
            'NombreCEDI' => 'Nombre CEDI',
            'CedulaJefe' => 'C&eacute;dula Jefe',
            'CelularJefe' => 'Celular Jefe',
            'TelefonoCEDI' => 'Tel&eacute;fono CEDI',
            'IndicativoTelefonoCEDI' => 'Indicativo tel&eacute;fono',
            'IdCentroCostos' => 'Centro Costo',
            'DireccionCEDI' => 'Direcci&oacute;n',
            'HorarioAperturaLunesAViernes' => 'Apertura Lun-Vie',
            'HorarioCierreLunesAViernes' => 'Cierre Lun-Vie',
            'HorarioAperturaSabado' => 'Apertura S&aacute;bado',
            'HorarioCierreSabado' => 'Cierre S&aacute;bado',
            'CodigoEAN' => 'Código EAN',
            'HorarioAperturaLunesAViernes' => 'Apert. Lun-Vie',
            'HorarioCierreLunesAViernes' => 'Cierre Lun-Vie',
            'HorarioAperturaSabado' => 'Apert. Sab',
            'HorarioCierreSabado' => 'Cierre Sab',
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('IDCEDI', $this->IDCEDI);
        $criteria->compare('NombreCEDI', $this->NombreCEDI, true);
        $criteria->compare('CedulaJefe', $this->CedulaJefe, true);
        $criteria->compare('CelularJefe', $this->CelularJefe, true);
        $criteria->compare('TelefonoCEDI', $this->TelefonoCEDI, true);
        $criteria->compare('IndicativoTelefonoCEDI', $this->IndicativoTelefonoCEDI, true);
        $criteria->compare('IdCentroCostos', $this->IdCentroCostos, true);
        $criteria->compare('DireccionCEDI', $this->DireccionCEDI, true);
        $criteria->compare('CodigoEAN',$this->CodigoEAN,true);
        $criteria->compare('HorarioAperturaLunesAViernes', $this->HorarioAperturaLunesAViernes, true);
        $criteria->compare('HorarioCierreLunesAViernes', $this->HorarioCierreLunesAViernes, true);
        $criteria->compare('HorarioAperturaSabado', $this->HorarioAperturaSabado, true);
        $criteria->compare('HorarioCierreSabado', $this->HorarioCierreSabado, true);

        $criteria->join = 'INNER JOIN m_Persona as persona ON (persona.NumeroDocumento = t.CedulaJefe)';

        $dataprovider = new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));

        return $dataprovider;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Cedi the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * Retorna lista de sedes para lista desplegable
     * @return lista de sedes (id, nombre)
     */
    public static function listData() {
        $data = self::model()->findAll(array('order' => 'NombreCEDI'));
        $lst = CHtml::listData($data, 'IDCEDI', 'NombreCEDI');
        return $lst;
    }

}
