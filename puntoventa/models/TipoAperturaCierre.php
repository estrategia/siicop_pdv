<?php

/**
 * This is the model class for table "m_TipoAperturaCierre".
 *
 * The followings are the available columns in table 'm_TipoAperturaCierre':
 * @property integer $IDTipoAperturaCierre
 * @property string $NombreTipoAperturaCierre
 */
class TipoAperturaCierre extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'm_TipoAperturaCierre';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('IDTipoAperturaCierre', 'required', 'on'=>'update', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('NombreTipoAperturaCierre', 'required', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('NombreTipoAperturaCierre', 'length', 'max' => 50),
            array('NombreTipoAperturaCierre', 'validateExist'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('IDTipoAperturaCierre, NombreTipoAperturaCierre', 'safe', 'on' => 'search'),
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
                    'condition' => "IDTipoAperturaCierre<>:id AND $attribute=:value",
                    'params' => array(
                        'id' => $this->IDTipoAperturaCierre,
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
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'IDTipoAperturaCierre' => 'Idtipo Apertura Cierre',
            'NombreTipoAperturaCierre' => 'Nombre Tipo Apertura Cierre',
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

        $criteria->compare('IDTipoAperturaCierre', $this->IDTipoAperturaCierre);
        $criteria->compare('NombreTipoAperturaCierre', $this->NombreTipoAperturaCierre, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return TipoAperturaCierre the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
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
