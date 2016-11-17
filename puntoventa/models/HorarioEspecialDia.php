<?php

/**
 * This is the model class for table "t_HorarioEspecialDia".
 *
 * The followings are the available columns in table 't_HorarioEspecialDia':
 * @property string $IdHorarioEspecialDia
 * @property string $Fecha
 * @property string $Es24Horas
 * @property string $HoraInicio
 * @property string $HoraFin
 * @property string $FechaCreacionRegistro
 * @property string $NumeroDocumento
 *
 * The followings are the available model relations:
 * @property HorarioEspecialPuntoVenta[] $listHorarioEspecialPdv
 */
class HorarioEspecialDia extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 't_HorarioEspecialDia';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('Fecha, NumeroDocumento', 'required', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('Fecha', 'validateDate'),
            array('Es24Horas', 'required', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('Es24Horas', 'length', 'max' => 2),
            array('HoraFin', 'validateHour'),
            array('HoraInicio, HoraFin, FechaCreacionRegistro', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('IdHorarioEspecialDia, Fecha, Es24Horas, HoraInicio, HoraFin, FechaCreacionRegistro, NumeroDocumento', 'safe', 'on' => 'search'),
        );
    }

    public function validateDate($attribute, $params) {
        if (!$this->hasErrors()) {
            $fechaHoy = new DateTime;
            $fechaHoy->modify("-1 day");
            $fechaHoy->setTime(23, 59, 0);
            $fechaDate = DateTime::createFromFormat('Y-m-d H:i:s', "$this->Fecha 00:00:00");

            /*if ($fechaHoy->format('Y-m-d H:i:s') == $fechaDate->format('Y-m-d H:i:s')) {
                $this->addError($attribute, $this->getAttributeLabel($attribute) . ' debe ser mayor a fecha actual.');
            }*/

            $diffFecha = $fechaHoy->diff($fechaDate);

            if ($diffFecha->invert == 1) {
                $this->addError($attribute, $this->getAttributeLabel($attribute) . ' debe ser mayor a fecha actual.');
            }
        }
    }

    public function validateHour($attribute, $params) {
        //verifica que sean horas validas
        if (!$this->hasErrors()) {
            if ($this->Es24Horas == "NO") {
                $fechaInicio = DateTime::createFromFormat('Y-m-d H:i:s', "$this->Fecha $this->HoraInicio:00");
                $fechaFin = DateTime::createFromFormat('Y-m-d H:i:s', "$this->Fecha $this->HoraFin:00");

                if ($fechaInicio->format('i') != '00' && $fechaInicio->format('i') != '30') {
                    $this->addError('HoraInicio', $this->getAttributeLabel('HoraInicio') . ' minutos debe ser 00 &oacute; 30.');
                }

                if (!($fechaFin->format('H') == '23' && $fechaFin->format('i') == '59')) {
                    if ($fechaFin->format('i') != '00' && $fechaFin->format('i') != '30') {
                        $this->addError('HoraFin', $this->getAttributeLabel($attribute) . ' debe ser 23:59 &oacute; minutos debe ser 00 &oacute; 30.');
                    }
                }

                if ($fechaInicio->format('Y-m-d H:i:s') == $fechaFin->format('Y-m-d H:i:s')) {
                    $this->addError('HoraFin', $this->getAttributeLabel('HoraFin') . ' debe ser mayor a hora inicio.');
                } else {
                    $diff = $fechaInicio->diff($fechaFin);

                    if ($diff->invert == 1) {
                        $this->addError($attribute, $this->getAttributeLabel($attribute) . ' debe ser mayor a hora inicio.');
                    }
                }
            } else {
                $this->HoraInicio = $this->HoraFin = null;
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
            'listHorarioEspecialPdv' => array(self::HAS_MANY, 'HorarioEspecialPuntoVenta', 'IdHorarioEspecialDia'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'IdHorarioEspecialDia' => 'Id Horario Especial Dia',
            'Fecha' => 'Fecha',
            'Es24Horas' => 'Es 24horas',
            'HoraInicio' => 'Hora Inicio',
            'HoraFin' => 'Hora Fin',
            'FechaCreacionRegistro' => 'Fecha Creaci&oacute;n Registro',
            'NumeroDocumento' => 'N&uacute;mero Documento',
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

        $criteria->compare('IdHorarioEspecialDia', $this->IdHorarioEspecialDia, true);
        $criteria->compare('Fecha', $this->Fecha, true);
        $criteria->compare('Es24Horas', $this->Es24Horas, true);
        $criteria->compare('HoraInicio', $this->HoraInicio, true);
        $criteria->compare('HoraFin', $this->HoraFin, true);
        $criteria->compare('FechaCreacionRegistro', $this->FechaCreacionRegistro, true);
        $criteria->compare('NumeroDocumento', $this->NumeroDocumento);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return HorarioEspecialDia the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function beforeSave() {
        if ($this->isNewRecord) {
            $this->FechaCreacionRegistro = date('Y-m-d H:i:s');
        }

        return parent::beforeSave();
    }

}
