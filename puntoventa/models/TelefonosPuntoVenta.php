<?php

/**
 * This is the model class for table "t_telefonospuntodeventa".
 *
 * The followings are the available columns in table 't_telefonospuntodeventa':
 * @property integer $IDTelefonoPuntoDeVenta
 * @property integer $IDPuntoDeVenta
 * @property string $NumeroTelefono
 * @property string $IndicativoTelefono
 *
 * The followings are the available model relations:
 * @property TPuntosdeventa $iDPuntoDeVenta
 */
class TelefonosPuntoVenta extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 't_TelefonosPuntoVenta';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('IDTelefonoPuntoDeVenta', 'required', 'on' => 'update', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('IDPuntoDeVenta', 'required'),
            array('IDPuntoDeVenta', 'numerical', 'integerOnly' => true),
            
            array('NumeroTelefono', 'numerical', 'integerOnly' => true, 'allowEmpty'=>false),
            array('IndicativoTelefono', 'numerical', 'integerOnly' => true, 'allowEmpty'=>true),
            
            array('NumeroTelefono', 'length', 'max' => 15),
            array('IndicativoTelefono', 'length', 'max' => 45),
            array('IndicativoTelefono', 'default', 'value' => null),

            array('IDTelefonoPuntoDeVenta, IDPuntoDeVenta, NumeroTelefono, IndicativoTelefono', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
            'puntoVenta' => array(self::BELONGS_TO, 'PuntoVenta', 'IDPuntoDeVenta'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'IDTelefonoPuntoDeVenta' => 'ID',
            'IDPuntoDeVenta' => 'Punto de Venta',
            'NumeroTelefono' => 'N&uacute;mero tel&eacute;fono',
            'IndicativoTelefono' => 'Indicativo tel&eacute;fono',
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

        $criteria->compare('IDTelefonoPuntoDeVenta', $this->IDTelefonoPuntoDeVenta);
        $criteria->compare('IDPuntoDeVenta', $this->IDPuntoDeVenta);
        $criteria->compare('NumeroTelefono', $this->NumeroTelefono, true);
        $criteria->compare('IndicativoTelefono', $this->IndicativoTelefono, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return TelefonosPuntoVenta the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
