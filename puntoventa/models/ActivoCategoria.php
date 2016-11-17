<?php

/**
 * This is the model class for table "m_ActivoCategoria".
 *
 * The followings are the available columns in table 'm_ActivoCategoria':
 * @property integer $IdActivoCategoria
 * @property string $NombreCategoria
 *
 * The followings are the available model relations:
 * @property MActivo[] $mActivos
 */
class ActivoCategoria extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'm_ActivoCategoria';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('NombreCategoria', 'required'),
            array('NombreCategoria', 'length', 'max' => 45),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('IdActivoCategoria, NombreCategoria', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'mActivos' => array(self::HAS_MANY, 'MActivo', 'IdActivoCategoria'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'IdActivoCategoria' => 'Id Activo Categoria',
            'NombreCategoria' => 'Nombre Categoria',
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

        $criteria->compare('IdActivoCategoria', $this->IdActivoCategoria);
        $criteria->compare('NombreCategoria', $this->NombreCategoria, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    
    public static function listData(){
        $models = self::model()->findAll(array('order'=>'NombreCategoria'));
        return CHtml::listData($models, 'IdActivoCategoria', 'NombreCategoria');
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ActivoCategoria the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
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

}
