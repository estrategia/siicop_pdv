<?php

/**
 * This is the model class for table "m_BarriosInfluencia".
 *
 * The followings are the available columns in table 'm_BarriosInfluencia':
 * @property integer $IDBarrio
 * @property string $NombreBarrio
 */
class BarrioInfluencia extends CActiveRecord {
    public $puntoventa_search = null;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'm_BarriosInfluencia';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('IDBarrio', 'required', 'on' => 'update', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('NombreBarrio', 'required', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('NombreBarrio', 'length', 'max' => 200),
            array('NombreBarrio', 'validateExist'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('IDBarrio, NombreBarrio', 'safe', 'on' => 'search'),
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
                    'condition' => "IDBarrio<>:id AND $attribute=:value",
                    'params' => array(
                        'id' => $this->IDBarrio,
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
            'puntosVenta' => array(self::MANY_MANY, 'PuntoVenta', 't_InfluenciaPuntoVenta(IDPuntoDeVenta, IDBarrio)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'IDBarrio' => 'ID Barrio',
            'NombreBarrio' => 'Nombre Barrio',
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
        
        if ($this->puntoventa_search !== null) {
            $criteria->join = 'INNER JOIN t_InfluenciaPuntoVenta as influencia ON (influencia.IDBarrio = t.IDBarrio)';
            $criteria->compare('influencia.IDPuntoDeVenta', $this->puntoventa_search);
        }

        $criteria->compare('IDBarrio', $this->IDBarrio);
        $criteria->compare('NombreBarrio', $this->NombreBarrio, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return BarrioInfluencia the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    /**
     * Retorna lista de tipos de servicios para lista desplegable
     * @return lista de tipos de servicios (id, nombre)
     */
    public static function listData() {
        $data = self::model()->findAll(array('order' => 'NombreBarrio'));
        $lst = CHtml::listData($data, 'IDBarrio', 'NombreBarrio');
        return $lst;
    }
    
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

}
