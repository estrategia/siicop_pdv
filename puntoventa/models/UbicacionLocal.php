<?php

/**
 * This is the model class for table "t_ubicacionlocal".
 *
 * The followings are the available columns in table 't_ubicacionlocal':
 * @property integer $IDUbicacion
 * @property string $NombreUbicacion
 *
 * The followings are the available model relations:
 * @property Puntosdeventa[] $puntosVentas
 */
class UbicacionLocal extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 't_UbicacionLocal';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('IDUbicacion', 'required', 'on' => 'update', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('NombreUbicacion', 'required', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('NombreUbicacion', 'length', 'max' => 45),
            array('NombreUbicacion', 'validateExist'),
            array('IDUbicacion, NombreUbicacion', 'safe', 'on' => 'search'),
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
                    'condition' => "IDUbicacion<>:id AND $attribute=:value",
                    'params' => array(
                        'id' => $this->IDUbicacion,
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
        return array(
            'puntosVenta' => array(self::HAS_MANY, 'PuntosVenta', 'IDUbicacion'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'IDUbicacion' => 'ID',
            'NombreUbicacion' => 'Ubicaci&oacute;n',
        );
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

        $criteria->compare('IDUbicacion', $this->IDUbicacion);
        $criteria->compare('NombreUbicacion', $this->NombreUbicacion, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return UbicacionLocal the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    /**
     * Retorna lista de ubicaciones para lista desplegable
     * @return lista de ubicaciones (id, nombre)
     */
    public static function listData() {
        $data = self::model()->findAll(array('order' => 'NombreUbicacion'));
        $lst = CHtml::listData($data, 'IDUbicacion', 'NombreUbicacion');
        return $lst;
    }

}
