<?php

/**
 * This is the model class for table "t_TelefonosZona".
 *
 * The followings are the available columns in table 't_TelefonosZona':
 * @property integer $IDTelefonoZona
 * @property integer $IDZona
 * @property string $NumeroTelefono
 *
 * The followings are the available model relations:
 * @property MZona $iDZona
 */
class TelefonosZona extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 't_TelefonosZona';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('IDTelefonoZona', 'numerical', 'on' => 'Add', 'message' => '{attribute} debe ser num&eacute;rico'),
            array('IDZona, NumeroTelefono', 'required'),
            array('IDZona', 'numerical', 'integerOnly' => true),
            array('NumeroTelefono', 'numerical', 'integerOnly' => true, 'allowEmpty'=>false),
            array('NumeroTelefono', 'length', 'max' => 45),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('IDTelefonoZona, IDZona, NumeroTelefono', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'zona' => array(self::BELONGS_TO, 'Zona', 'IDZona'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'IDTelefonoZona' => 'ID Telefono',
            'IDZona' => 'Idzona',
            'NumeroTelefono' => 'N&uacute;mero Tel&eacute;fono',
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

        $criteria->compare('IDTelefonoZona', $this->IDTelefonoZona);
        $criteria->compare('IDZona', $this->IDZona);
        $criteria->compare('NumeroTelefono', $this->NumeroTelefono, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return TelefonoZona the static model class
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
