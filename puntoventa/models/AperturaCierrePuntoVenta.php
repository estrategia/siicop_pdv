<?php

/**
 * This is the model class for table "t_aperturascierrespuntosdeventa".
 *
 * The followings are the available columns in table 't_aperturascierrespuntosdeventa':
 * @property integer $IDAperturaCierrePuntoDeVenta
 * @property integer $IDPuntoDeVenta
 * @property string $FechaAperturaCierre
 * @property string $FechaRegistroAperturaCierre
 * @property integer $IDTipoAperturaCierre
 * @property string $ObservacionesAperturaCierre
 *
 * The followings are the available model relations:
 * @property PuntoVenta $iDPuntoDeVenta
 * @property TipoAperturaCierre $tipoAperturaCierre
 */
class AperturaCierrePuntoVenta extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 't_AperturasCierresPuntoVenta';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('IDAperturaCierrePuntoDeVenta', 'required', 'on' => 'update', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('IDPuntoDeVenta, FechaAperturaCierre, IDTipoAperturaCierre, ObservacionesAperturaCierre', 'required', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('IDPuntoDeVenta, IDTipoAperturaCierre', 'numerical', 'integerOnly' => true),
            //array('IDTipoAperturaCierre', 'in', 'range' => array('0', '1'), 'allowEmpty' => false),
            array('ObservacionesAperturaCierre', 'length', 'max' => 255),
            array('FechaRegistroAperturaCierre', 'safe'),
            array('FechaAperturaCierre', 'date', 'format' => 'yyyy-MM-dd'),
            array('IDAperturaCierrePuntoDeVenta, IDPuntoDeVenta, FechaAperturaCierre, FechaRegistroAperturaCierre, IDTipoAperturaCierre, ObservacionesAperturaCierre', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
            'puntoventa' => array(self::BELONGS_TO, 'PuntoVenta', 'IDPuntoDeVenta'),
            'tipoAperturaCierre' => array(self::BELONGS_TO, 'TipoAperturaCierre', 'IDTipoAperturaCierre'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'IDAperturaCierrePuntoDeVenta' => 'ID',
            'IDPuntoDeVenta' => 'Punto Venta',
            'FechaAperturaCierre' => 'Fecha Evento',
            'FechaRegistroAperturaCierre' => 'Fecha Registro',
            'IDTipoAperturaCierre' => 'Tipo',
            'ObservacionesAperturaCierre' => 'Observaciones',
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

        $criteria->with = array('tipoAperturaCierre');
        $criteria->compare('t.IDAperturaCierrePuntoDeVenta', $this->IDAperturaCierrePuntoDeVenta);
        $criteria->compare('t.IDPuntoDeVenta', $this->IDPuntoDeVenta);
        $criteria->compare('t.FechaAperturaCierre', $this->FechaAperturaCierre, true);
        $criteria->compare('t.FechaRegistroAperturaCierre', $this->FechaRegistroAperturaCierre, true);
        $criteria->compare('t.IDTipoAperturaCierre', $this->IDTipoAperturaCierre);
        $criteria->compare('t.ObservacionesAperturaCierre', $this->ObservacionesAperturaCierre, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AperturaCierrePuntoVenta the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return CActiveRecord registro que se guarda en base de datos
     */
    public function beforeSave() {
        $this->FechaRegistroAperturaCierre = new CDbExpression('NOW()');

        return parent::beforeSave();
    }

}
