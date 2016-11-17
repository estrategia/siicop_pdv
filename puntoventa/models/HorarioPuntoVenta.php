<?php

/**
 * This is the model class for table "t_horariospuntosdeventa".
 *
 * The followings are the available columns in table 't_horariospuntosdeventa':
 * @property integer $IDHorarioPuntoDeVenta
 * @property string $HorarioInicio
 * @property string $HorarioFin
 *
 * The followings are the available model relations:
 * @property PuntoVenta[] $puntosVentaApertura
 * @property PuntoVenta[] $puntosVentaFestivo
 * @property PuntoVenta[] $puntosVentaDomicilio
 */
class HorarioPuntoVenta extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 't_HorariosPuntoVenta';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('IDHorarioPuntoDeVenta', 'required', 'on' => 'update', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('HorarioInicio, HorarioFin', 'required', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('HorarioInicio, HorarioFin', 'date', 'format' => 'HH:mm'),
            array('HorarioFin', 'validateHour'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('HorarioInicio, HorarioFin', 'safe', 'on' => 'search'),
        );
    }

    public function validateHour($attribute, $params) {
        if (!$this->hasErrors() && $this->getAttribute($attribute) != null) {
            if ($attribute == 'HorarioFin') {
                if ($this->HorarioInicio == $this->HorarioFin) {
                    $this->addError($attribute, $this->getAttributeLabel($attribute) . ' : hora fin debe ser mayor a hora inicio.');
                } else {
                    $fecha = new DateTime();
                    $dia = $fecha->format('Y-m-d');
                    $fechaInicio = DateTime::createFromFormat('Y-m-d H:i:s', $dia . ' ' . $this->HorarioInicio . ':00');
                    $fechaFin = DateTime::createFromFormat('Y-m-d H:i:s', $dia . ' ' . $this->HorarioFin . ':00');

                    if ($fechaInicio->format('i') != '00' && $fechaInicio->format('i') != '30') {
                        $this->addError('HorarioInicio', $this->getAttributeLabel($attribute) . ' minutos debe ser 00 &oacute; 30.');
                    }

                    if (!($fechaFin->format('H') == '23' && $fechaFin->format('i') == '59')) {
                        if ($fechaFin->format('i') != '00' && $fechaFin->format('i') != '30') {
                            $this->addError('HorarioFin', $this->getAttributeLabel($attribute) . ' debe ser 23:59 o minutos debe ser 00 &oacute; 30.');
                        }
                    }

                    $diff = $fechaInicio->diff($fechaFin);

                    if ($diff->invert == 1) {
                        $this->addError($attribute, $this->getAttributeLabel($attribute) . ' : hora fin debe ser mayor a hora inicio.');
                    }
                }
            } else {
                $this->addError($attribute, $this->getAttributeLabel($attribute) . ' validaci&oacute;n incorrecta.');
            }
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            //'puntosVentaApertura' => array(self::HAS_MANY, 'PuntoVenta', 'IDHorarioDeApertura'),
            //'puntosVentaDomicilio' => array(self::HAS_MANY, 'PuntoVenta', 'IDHorarioDomicilios'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'IDHorarioPuntoDeVenta' => 'ID',
            'HorarioInicio' => 'Hora Inicio',
            'HorarioFin' => 'Hora Fin',
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

        $criteria->compare('IDHorarioPuntoDeVenta', $this->IDHorarioPuntoDeVenta);
        $criteria->compare('HorarioInicio', $this->HorarioInicio, true);
        $criteria->compare('HorarioFin', $this->HorarioFin, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return HorarioPuntoVenta the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * Retorna lista de horarios para lista desplegable
     * @return lista de horarios (id, nombre)
     */
    public static function listData() {
        $data = self::model()->findAll(array('order' => 'HorarioInicio, HorarioFin'));
        $lst = CHtml::listData($data, 'IDHorarioPuntoDeVenta', function($model) {return CHtml::encode($model->HorarioInicio) . " - " . CHtml::encode($model->HorarioFin);});
        return $lst;
    }

}
