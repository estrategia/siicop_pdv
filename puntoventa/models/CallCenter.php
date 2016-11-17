<?php

/**
 * This is the model class for table "m_CallCenter".
 *
 * The followings are the available columns in table 'm_CallCenter':
 * @property integer $IDPuntoDeVenta
 * @property string $CedulaJefe
 * @property string $CedulaGerenteVentas
 *
 * The followings are the available model relations:
 * @property PuntoVenta $puntoVenta
 */
class CallCenter extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'm_CallCenter';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('IDPuntoDeVenta, CedulaJefe, CedulaGerenteVentas', 'required'),
            array('IDPuntoDeVenta', 'numerical', 'integerOnly' => true),
            array('CedulaJefe, CedulaGerenteVentas', 'length', 'max' => 20),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('IDPuntoDeVenta, CedulaJefe, CedulaGerenteVentas', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'puntoVenta' => array(self::BELONGS_TO, 'PuntoVenta', 'IDPuntoDeVenta'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'IDPuntoDeVenta' => 'Idpunto De Venta',
            'CedulaJefe' => 'Cedula Jefe',
            'CedulaGerenteVentas' => 'Cedula Gerente Ventas',
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

        $criteria->compare('IDPuntoDeVenta', $this->IDPuntoDeVenta);
        $criteria->compare('CedulaJefe', $this->CedulaJefe, true);
        $criteria->compare('CedulaGerenteVentas', $this->CedulaGerenteVentas, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return CallCenter the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
