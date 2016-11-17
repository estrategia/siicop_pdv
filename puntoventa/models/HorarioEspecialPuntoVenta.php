<?php

/**
 * This is the model class for table "t_HorarioEspecialPuntoVenta".
 *
 * The followings are the available columns in table 't_HorarioEspecialPuntoVenta':
 * @property string $IdHorarioEspecialPuntoVenta
 * @property integer $IDPuntoDeVenta
 * @property string $IdHorarioEspecialRango
 * @property string $IdHorarioEspecialDia
 *
 * The followings are the available model relations:
 * @property PuntoVenta $objPuntoVenta
 * @property HorarioEspecialDia $objHorarioEspecialDia
 * @property HorarioEspecialRango $objHorarioEspecialRango
 */
class HorarioEspecialPuntoVenta extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 't_HorarioEspecialPuntoVenta';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('IDPuntoDeVenta', 'required'),
            array('IDPuntoDeVenta', 'numerical', 'integerOnly' => true),
            array('IdHorarioEspecialRango, IdHorarioEspecialDia', 'length', 'max' => 10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('IdHorarioEspecialPuntoVenta, IDPuntoDeVenta, IdHorarioEspecialRango, IdHorarioEspecialDia', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'objPuntoVenta' => array(self::BELONGS_TO, 'PuntoVenta', 'IDPuntoDeVenta'),
            'objHorarioEspecialDia' => array(self::BELONGS_TO, 'HorarioEspecialDia', 'IdHorarioEspecialDia'),
            'objHorarioEspecialRango' => array(self::BELONGS_TO, 'HorarioEspecialRango', 'IdHorarioEspecialRango'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'IdHorarioEspecialPuntoVenta' => 'Id Horario Especial Punto Venta',
            'IDPuntoDeVenta' => 'Idpunto De Venta',
            'IdHorarioEspecialRango' => 'Id Horario Especial Rango',
            'IdHorarioEspecialDia' => 'Id Horario Especial Dia',
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

        $criteria->compare('IdHorarioEspecialPuntoVenta', $this->IdHorarioEspecialPuntoVenta, true);
        $criteria->compare('IDPuntoDeVenta', $this->IDPuntoDeVenta);
        $criteria->compare('IdHorarioEspecialRango', $this->IdHorarioEspecialRango, true);
        $criteria->compare('IdHorarioEspecialDia', $this->IdHorarioEspecialDia, true);
        
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return HorarioEspecialPuntoVenta the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
