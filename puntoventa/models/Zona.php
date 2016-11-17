<?php

/**
 * This is the model class for table "t_zonas".
 *
 * The followings are the available columns in table 't_zonas':
 * @property integer $IDZona
 * @property integer $IDSede
 * @property string $NombreZona
 * @property string $DireccionZona
 * @property string $CelularZona
 * @property string $eMailZona
 * @property string $CedulaDirectorZona
 * @property string $eMailDirectorZona
 * @property string $CedulaAuxiliarZona
 * @property integer $IndicativoTelefonoZona
 *
 * The followings are the available model relations:
 * @property PuntoVenta[] $puntosVenta
 * @property Sede $sede
 */
class Zona extends CActiveRecord {

    public $sede_search;
    private $_NombreDirectorZona = "NA";
    private $_NombreAuxiliarZona = "NA";
    public $telefonostxt = "";

    public function getNombreDirectorZona() {
        return $this->_NombreDirectorZona;
    }

    public function getNombreAuxiliarZona() {
        return $this->_NombreAuxiliarZona;
    }

    private function auxiliarData() {
        if (!$this->isNewRecord) {
            try {
                $persona1 = Persona::model()->find(array(
                    'condition' => 'NumeroDocumento=:cedula',
                    'params' => array(
                        ':cedula' => $this->CedulaDirectorZona
                )));

                if ($persona1 !== null) {
                    $this->_NombreDirectorZona = $persona1->ApellidosNombres;
                }
            } catch (Exception $exc) {
                Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
            }

            try {
                $persona2 = Persona::model()->find(array(
                    'condition' => 'NumeroDocumento=:cedula',
                    'params' => array(
                        ':cedula' => $this->CedulaAuxiliarZona
                )));

                if ($persona2 !== null) {
                    $this->_NombreAuxiliarZona = $persona2->ApellidosNombres;
                }
            } catch (Exception $exc) {
                Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
            }

            $telefonos = $this->telefonos;
            $this->telefonostxt = "";

            foreach ($telefonos as $indice => $telefono) {
                if ($indice > 0) {
                    $this->telefonostxt .= " - $telefono->NumeroTelefono";
                } else {
                    $this->telefonostxt .= "$telefono->NumeroTelefono";
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
        return 'm_Zona';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('IDZona', 'required', 'on' => 'update', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('IDSede, NombreZona, CedulaDirectorZona, CedulaAuxiliarZona', 'required', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('IDSede, CelularZona, IndicativoTelefonoZona', 'numerical', 'integerOnly' => true),
            array('NombreZona, CelularZona', 'length', 'max' => 45),
            array('DireccionZona', 'length', 'max' => 255),
            array('NombreZona', 'validateExist'),
            array('eMailZona, eMailDirectorZona', 'email'),
            array('eMailZona, eMailDirectorZona', 'length', 'max' => 100),
            array('eMailZona, eMailDirectorZona', 'email', 'allowEmpty' => true),
            array('eMailZona, eMailDirectorZona', 'default', 'value' => null),
            array('CedulaDirectorZona, CedulaAuxiliarZona', 'length', 'max' => 20),
            array('CedulaDirectorZona, CedulaAuxiliarZona', 'validateUserExist'),
            //array('CedulaDirectorZona, CedulaAuxiliarZona', 'default', 'value'=>null),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('sede_search, IDZona, IDSede, NombreZona, DireccionZona, CelularZona, eMailZona, CedulaDirectorZona, eMailDirectorZona, CedulaAuxiliarZona, IndicativoTelefonoZona', 'safe', 'on' => 'search'),
        );
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
                    'condition' => "IDZona<>:id AND $attribute=:value",
                    'params' => array(
                        'id' => $this->IDZona,
                        'value' => $this->getAttribute($attribute)
                    )
                ));
            }

            if ($model !== null)
                $this->addError($attribute, $this->getAttributeLabel($attribute) . ' ya existe.');
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'puntosVenta' => array(self::HAS_MANY, 'PuntoVenta', 'IDZona'),
            'sede' => array(self::BELONGS_TO, 'Sede', 'IDSede'),
            'telefonos' => array(self::HAS_MANY, 'TelefonosZona', 'IDZona'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'IDZona' => 'ID Zona',
            'IDSede' => 'Sede',
            'NombreZona' => 'Nombre Zona',
            'DireccionZona' => 'Direcci&oacute;n',
            'CelularZona' => 'Celular',
            'eMailZona' => 'Correo',
            'CedulaDirectorZona' => 'C&eacute;dula Director',
            'eMailDirectorZona' => 'Correo Director',
            'CedulaAuxiliarZona' => 'C&eacute;dula Auxiliar',
            'IndicativoTelefonoZona' => 'Indicativo tel.',
            'sede_search' => 'Sede'
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
        $criteria->with = array('sede', 'telefonos');
        $criteria->compare('sede.NombreSede', $this->sede_search, true);

        $criteria->compare('IDZona', $this->IDZona);
        $criteria->compare('IDSede', $this->IDSede);
        $criteria->compare('NombreZona', $this->NombreZona, true);
        $criteria->compare('DireccionZona', $this->DireccionZona, true);
        $criteria->compare('CelularZona', $this->CelularZona, true);
        $criteria->compare('eMailZona', $this->eMailZona, true);
        $criteria->compare('CedulaDirectorZona', $this->CedulaDirectorZona, true);
        $criteria->compare('eMailDirectorZona', $this->eMailDirectorZona, true);
        $criteria->compare('CedulaAuxiliarZona', $this->CedulaAuxiliarZona, true);
        $criteria->compare('IndicativoTelefonoZona', $this->IndicativoTelefonoZona, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'attributes' => array(
                    'sede_search' => array(
                        'asc' => 'sede.NombreSede',
                        'desc' => 'sede.NombreSede  DESC',
                    ),
                    '*',
                ),
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Zona the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * Retorna lista de zonas para lista desplegable
     * @return lista de zonas (id, nombre)
     */
    public static function listData() {
        $data = self::model()->findAll(array('order' => 'NombreZona'));
        $lst = CHtml::listData($data, 'IDZona', 'NombreZona');
        return $lst;
    }


    public static function searchObject($condiciones) {
        $campos = array(
            'idZona' => 'IDZona',
            'nombreZona' => 'NombreZona',
            'idSede' => 'IDSede',
            'direccionZona' => 'DireccionZona',
            'celularZona' => 'CelularZona',
            'eMailZona' => 'eMailZona',
            "puntosVenta" => 'IDComercial',
            "centroCosto" => 'IdCentroCostos',
            'ordenar' => null
        );

        if(empty($condiciones))
        {
            return Zona::model()->findAll();
        }
        else
        {
            $criteria = new CDbCriteria;
            //$criteria->join = 'JOIN m_Ciudad as ciudad ON (t.CodigoCiudad = ciudad.CodCiudad)';
            $criteria->with = array('sede', 'puntosVenta');

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
                    if($campos[$key] == "IDZona" || $campos[$key] == "IDSede")
                    {
                        $comparacion = "t.".$campos[$key];
                    }

                    $criteria->compare($comparacion, $valor, $like, $condicional);
                }
            }
            //print_r($criteria);
            if(isset($condiciones['ordenar']))
                $criteria->order = $condiciones['ordenar'];
            else
                $criteria->order = 't.IDZona';

            return Zona::model()->findAll($criteria);
        }
    }
}
