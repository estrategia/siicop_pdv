<?php

/**
 * This is the model class for table "t_ActivosTrazabilidad".
 *
 * The followings are the available columns in table 't_ActivosTrazabilidad':
 * @property string $IdActivosTrazabilidad
 * @property string $IdActivoPuntoVenta
 * @property integer $Estado
 * @property string $IdentificacionAprobador
 * @property string $ObservacionAprobador
 * @property string $FechaRegistro
 *
 * The followings are the available model relations:
 * @property TActivosPuntoVenta $idActivoPuntoVenta
 */
class ActivosTrazabilidad extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 't_ActivosTrazabilidad';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('IdActivoPuntoVenta, Estado, IdentificacionAprobador, ObservacionAprobador', 'required'),
            array('Estado', 'numerical', 'integerOnly' => true),
            array('IdActivoPuntoVenta', 'length', 'max' => 10),
            array('IdentificacionAprobador', 'length', 'max' => 20),
            array('ObservacionAprobador', 'length', 'max' => 200),
            array('FechaRegistro','safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('IdActivosTrazabilidad, IdActivoPuntoVenta, Estado, IdentificacionAprobador, ObservacionAprobador, FechaRegistro', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'idActivoPuntoVenta' => array(self::BELONGS_TO, 'TActivosPuntoVenta', 'IdActivoPuntoVenta'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'IdActivosTrazabilidad' => 'Id Activos Trazabilidad',
            'IdActivoPuntoVenta' => 'Id Activo Punto Venta',
            'Estado' => 'Estado',
            'IdentificacionAprobador' => 'Identificacion Aprobador',
            'ObservacionAprobador' => 'Observacion Aprobador',
            'FechaRegistro' => 'Fecha Registro',
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

        $criteria->compare('IdActivosTrazabilidad', $this->IdActivosTrazabilidad, true);
        $criteria->compare('IdActivoPuntoVenta', $this->IdActivoPuntoVenta, true);
        $criteria->compare('Estado', $this->Estado);
        $criteria->compare('IdentificacionAprobador', $this->IdentificacionAprobador, true);
        $criteria->compare('ObservacionAprobador', $this->ObservacionAprobador, true);
        $criteria->compare('FechaRegistro', $this->FechaRegistro, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ActivosTrazabilidad the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public function beforeSave() {
        if ($this->isNewRecord) {
            $this->FechaRegistro = new CDbExpression('NOW()');
        }
        return parent::beforeSave();
    }

}
