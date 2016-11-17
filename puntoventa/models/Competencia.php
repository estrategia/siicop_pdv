<?php

/**
 * This is the model class for table "m_Competencia".
 *
 * The followings are the available columns in table 'm_Competencia':
 * @property integer $IDCompetencia
 * @property string $NombreCompetencia
 */
class Competencia extends CActiveRecord {
    public $puntoventa_search = null;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'm_Competencia';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('IDCompetencia', 'required', 'on' => 'update', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('NombreCompetencia', 'required', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('NombreCompetencia', 'length', 'max' => 200),
            array('NombreCompetencia', 'validateExist'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('IDCompetencia, NombreCompetencia', 'safe', 'on' => 'search'),
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
                    'condition' => "IDCompetencia<>:id AND $attribute=:value",
                    'params' => array(
                        'id' => $this->IDCompetencia,
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
            'puntosVenta' => array(self::MANY_MANY, 'PuntoVenta', 't_CompetenciaPuntoVenta(IDPuntoDeVenta, IDCompetencia)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'IDCompetencia' => 'ID Competencia',
            'NombreCompetencia' => 'Nombre Competencia',
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
            $criteria->join = 'INNER JOIN t_CompetenciaPuntoVenta as competencia ON (competencia.IDCompetencia = t.IDCompetencia)';
            $criteria->compare('competencia.IDPuntoDeVenta', $this->puntoventa_search);
        }
        
        $criteria->compare('IDCompetencia', $this->IDCompetencia);
        $criteria->compare('NombreCompetencia', $this->NombreCompetencia, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Competencia the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    /**
     * Retorna lista de tipos de servicios para lista desplegable
     * @return lista de tipos de servicios (id, nombre)
     */
    public static function listData() {
        $data = self::model()->findAll(array('order' => 'NombreCompetencia'));
        $lst = CHtml::listData($data, 'IDCompetencia', 'NombreCompetencia');
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
