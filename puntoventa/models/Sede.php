<?php

/**
 * This is the model class for table "t_sedes".
 *
 * The followings are the available columns in table 't_sedes':
 * @property integer $IDSede
 * @property integer $CodigoSede
 * @property string $NombreSede
 * @property string $CedulaGerenteOperativo
 * @property string $CelularSede
 * @property string $DireccionSede
 * @property string $TelefonoSede
 * @property integer $IndicativoTelefonoSede
 *
 * The followings are the available model relations:
 * @property PuntoVenta[] $puntosVenta
 * @property Zona[] $zonas
 */
class Sede extends CActiveRecord {

    private $_NombreGerenteOperativo = "NA";

    public function getNombreGerenteOperativo() {
        return $this->_NombreGerenteOperativo;
    }

    private function auxiliarData() {
        if (!$this->isNewRecord) {
            try {
                $persona1 = Persona::model()->find(array(
                    'condition' => 'NumeroDocumento=:cedula',
                    'params' => array(
                        ':cedula' => $this->CedulaGerenteOperativo
                )));

                if ($persona1 !== null) {
                    $this->_NombreGerenteOperativo = $persona1->ApellidosNombres;
                }
            } catch (Exception $exc) {
                Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
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
        return 'm_Sede';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('IDSede', 'required', 'on' => 'update', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('CodigoSede, NombreSede, CedulaGerenteOperativo', 'required', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('CodigoSede, TelefonoSede, CelularSede, IndicativoTelefonoSede', 'numerical', 'integerOnly' => true),
            array('NombreSede, CelularSede, TelefonoSede', 'length', 'max' => 45),
            array('CedulaGerenteOperativo', 'length', 'max' => 20),
            // array('CedulaGerenteOperativo', 'default', 'value'=>null),
            array('DireccionSede', 'length', 'max' => 100),
            array('CedulaGerenteOperativo', 'validateUserExist'),
            array('NombreSede', 'validateExist'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('IDSede, CodigoSede, NombreSede, CedulaGerenteOperativo, CelularSede, DireccionSede, TelefonoSede, IndicativoTelefonoSede', 'safe', 'on' => 'search'),
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
                    'condition' => "IDSede<>:id AND $attribute=:value",
                    'params' => array(
                        'id' => $this->IDSede,
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
            'puntosVenta' => array(self::HAS_MANY, 'PuntoVenta', 'IDSede'),
            'zonas' => array(self::HAS_MANY, 'Zona', 'IDSede'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'IDSede' => 'ID Sede',
            'CodigoSede' => 'C&oacute;digo Sede',
            'NombreSede' => 'Nombre Sede',
            'CedulaGerenteOperativo' => 'C&eacute;dula Gerente Operativo',
            'CelularSede' => 'Celular',
            'DireccionSede' => 'Direcci&oacute;n',
            'TelefonoSede' => 'Tel&eacute;fono',
            'IndicativoTelefonoSede' => 'Indicativo tel&eacute;fono',
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

        $criteria->compare('IDSede', $this->IDSede);
        $criteria->compare('CodigoSede', $this->CodigoSede, true);
        $criteria->compare('NombreSede', $this->NombreSede, true);
        $criteria->compare('CedulaGerenteOperativo', $this->CedulaGerenteOperativo, true);
        $criteria->compare('CelularSede', $this->CelularSede, true);
        $criteria->compare('DireccionSede', $this->DireccionSede, true);
        $criteria->compare('TelefonoSede', $this->TelefonoSede, true);
        $criteria->compare('IndicativoTelefonoSede', $this->IndicativoTelefonoSede, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Sede the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * Retorna lista de sedes para lista desplegable
     * @return lista de sedes (id, nombre)
     */
    public static function listData() {
        $data = self::model()->findAll(array('order' => 'NombreSede'));
        $lst = CHtml::listData($data, 'IDSede', 'NombreSede');
        return $lst;
    }


    public static function searchObject($condiciones) {
        $campos = array(
            'idSede' => 'IDSede',
            'nombreSede' => 'NombreSede',
            'cedulaGerenteOperativo' => 'CedulaGerenteOperativo',
            'celularSede' => 'CelularSede',
            'telefonoSede' => 'TelefonoSede',
            'idPuntoVenta' => 'puntosVenta.IDComercial',
            'ordenar' => null
        );

        if(empty($condiciones))
        {
            return Sede::model()->findAll();
        }
        else
        {
            $criteria = new CDbCriteria;
            //$criteria->join = 'JOIN m_Ciudad as ciudad ON (t.CodigoCiudad = ciudad.CodCiudad)';
            $criteria->with = array('puntosVenta');

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
                    
                    $comparacion = $campos[$key];
                    if($campos[$key] == "IDSede")
                    {
                        $comparacion = "t.".$campos[$key];
                    }

                    $criteria->compare($comparacion, $valor, $like, $condicional);
                }
            }
            print_r($criteria);
            if(isset($condiciones['ordenar']))
                $criteria->order = $condiciones['ordenar'];
            else
                $criteria->order = 't.IDSede';

            return Sede::model()->findAll($criteria);
        }
    }

}
