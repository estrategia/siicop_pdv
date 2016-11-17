<?php

/**
 * This is the model class for table "t_sectores".
 *
 * The followings are the available columns in table 't_sectores':
 * @property integer $IDSector
 * @property integer $CodigoSector
 * @property string $NombreSector
 *
 * The followings are the available model relations:
 * @property PuntoVenta[] $puntosVenta
 */
class Sector extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'm_Sector';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('IDSector', 'required', 'on' => 'update', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('CodigoSector, NombreSector', 'required', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('CodigoSector', 'numerical', 'integerOnly' => true),
            array('NombreSector', 'length', 'max' => 45),
            array('CodigoSector, NombreSector', 'validateExist'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('IDSector, CodigoSector, NombreSector', 'safe', 'on' => 'search'),
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
                    'condition' => "IDSector<>:id AND $attribute=:value",
                    'params' => array(
                        'id' => "$this->IDSector",
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
            'puntosVenta' => array(self::HAS_MANY, 'PuntoVenta', 'IDSector'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'IDSector' => 'Id Sector',
            'CodigoSector' => 'C&oacute;digo Sector',
            'NombreSector' => 'Nombre Sector',
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

        $criteria->compare('IDSector', $this->IDSector);
        $criteria->compare('CodigoSector', $this->CodigoSector, true);
        $criteria->compare('NombreSector', $this->NombreSector, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Sector the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * Retorna lista de sedes para lista desplegable
     * @return lista de sedes (id, nombre)
     */
    public static function listData() {
        $data = self::model()->findAll(array('order' => 'NombreSector'));
        $lst = CHtml::listData($data, 'IDSector', 'NombreSector');
        return $lst;
    }

}
