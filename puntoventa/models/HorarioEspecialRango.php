<?php

/**
 * This is the model class for table "t_HorarioEspecialRango".
 *
 * The followings are the available columns in table 't_HorarioEspecialRango':
 * @property string $IdHorarioEspecialRango
 * @property string $FechaInicio
 * @property string $FechaFin
 * @property string $Es24HorasLunesASabado
 * @property string $HoraInicioLunesASabado
 * @property string $HoraFinLunesASabado
 * @property string $Es24HorasDomingo
 * @property string $HoraInicioDomingo
 * @property string $HoraFinDomingo
 * @property string $Es24HorasFestivo
 * @property string $HoraInicioFestivo
 * @property string $HoraFinFestivo
 * @property string $FechaCreacionRegistro
 * @property string $NumeroDocumento
 *
 * The followings are the available model relations:
 * @property HorarioEspecialPuntoVenta[] $listHorarioEspecialPdv
 */
class HorarioEspecialRango extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 't_HorarioEspecialRango';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('FechaInicio, FechaFin, NumeroDocumento', 'required', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('FechaInicio, FechaFin', 'validateDate'),
            array('Es24HorasLunesASabado, Es24HorasDomingo, Es24HorasFestivo', 'required', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('Es24HorasLunesASabado, Es24HorasDomingo, Es24HorasFestivo', 'length', 'max' => 2),
            array('HoraFinLunesASabado', 'validateHour', 'Es24Horas' => 'Es24HorasLunesASabado', 'HoraInicio' => 'HoraInicioLunesASabado', 'HoraFin' => 'HoraFinLunesASabado'),
            array('HoraFinDomingo', 'validateHour', 'Es24Horas' => 'Es24HorasDomingo', 'HoraInicio' => 'HoraInicioDomingo', 'HoraFin' => 'HoraFinDomingo'),
            array('HoraFinFestivo', 'validateHour', 'Es24Horas' => 'Es24HorasFestivo', 'HoraInicio' => 'HoraInicioFestivo', 'HoraFin' => 'HoraFinFestivo'),
            array('HoraInicioLunesASabado, HoraFinLunesASabado, HoraInicioDomingo, HoraFinDomingo, HoraInicioFestivo, HoraFinFestivo, FechaCreacionRegistro', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('IdHorarioEspecialRango, FechaInicio, FechaFin, Es24HorasLunesASabado, HoraInicioLunesASabado, HoraFinLunesASabado, Es24HorasDomingo, HoraInicioDomingo, HoraFinDomingo, Es24HorasFestivo, HoraInicioFestivo, HoraFinFestivo, FechaCreacionRegistro, NumeroDocumento', 'safe', 'on' => 'search'),
        );
    }

    public function validateDate($attribute, $params) {
        if (!$this->hasErrors()) {
            if ($attribute == "FechaFin") {
                $fechaInicio = DateTime::createFromFormat('Y-m-d H:i:s', "$this->FechaInicio 00:00:00");
                $fechaFin = DateTime::createFromFormat('Y-m-d H:i:s', "$this->FechaFin 00:00:00");

                if ($fechaInicio->format('Y-m-d H:i:s') == $fechaFin->format('Y-m-d H:i:s')) {
                    $this->addError('FechaFin', $this->getAttributeLabel('FechaFin') . ' debe ser mayor a ' . $this->getAttributeLabel('FechaInicio'));
                } else {
                    $diff = $fechaInicio->diff($fechaFin);
                    if ($diff->invert == 1) {
                        $this->addError('FechaFin', $this->getAttributeLabel('FechaFin') . ' debe ser mayor a ' . $this->getAttributeLabel('FechaInicio'));
                    }
                }
            }

            $fechaHoy = new DateTime;
            $fechaHoy->modify("-1 day");
            $fechaHoy->setTime(23, 59, 0);
            $fechaDate = DateTime::createFromFormat('Y-m-d H:i:s', $this->$attribute . " 00:00:00");

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
            $es24Horas = $params['Es24Horas'];
            $horaInicio = $params['HoraInicio'];
            $horaFin = $params['HoraFin'];

            if ($this->$es24Horas == "NO") {
                $fechaInicio = DateTime::createFromFormat('Y-m-d H:i:s', "$this->FechaInicio " . $this->$horaInicio . ":00");
                $fechaFin = DateTime::createFromFormat('Y-m-d H:i:s', "$this->FechaFin " . $this->$horaFin . ":00");

                if ($fechaInicio->format('i') != '00' && $fechaInicio->format('i') != '30') {
                    $this->addError($horaInicio, $this->getAttributeLabel($horaInicio) . ' minutos debe ser 00 &oacute; 30.');
                }

                if (!($fechaFin->format('H') == '23' && $fechaFin->format('i') == '59')) {
                    if ($fechaFin->format('i') != '00' && $fechaFin->format('i') != '30') {
                        $this->addError($horaFin, $this->getAttributeLabel($horaFin) . ' debe ser 23:59 &oacute; minutos debe ser 00 &oacute; 30.');
                    }
                }

                $fechaInicio = DateTime::createFromFormat('Y-m-d H:i:s', "$this->FechaInicio " . $this->$horaInicio . ":00");
                $fechaFin = DateTime::createFromFormat('Y-m-d H:i:s', "$this->FechaInicio " . $this->$horaFin . ":00");

                if ($fechaInicio->format('Y-m-d H:i:s') == $fechaFin->format('Y-m-d H:i:s')) {
                    $this->addError($horaFin, $this->getAttributeLabel($horaFin) . ' debe ser mayor a ' . $this->getAttributeLabel($horaInicio));
                } else {
                    $diff = $fechaInicio->diff($fechaFin);

                    if ($diff->invert == 1) {
                        $this->addError($horaFin, $this->getAttributeLabel($horaFin) . ' debe ser mayor a ' . $this->getAttributeLabel($horaInicio));
                    }
                }
            } else {
                $this->$horaInicio = $this->$horaFin = null;
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
            'listHorarioEspecialPdv' => array(self::HAS_MANY, 'HorarioEspecialPuntoVenta', 'IdHorarioEspecialRango'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'IdHorarioEspecialRango' => 'Id Horario Especial Rango',
            'FechaInicio' => 'Fecha Inicio',
            'FechaFin' => 'Fecha Fin',
            'Es24HorasLunesASabado' => 'Es 24hrs Lunes-sabado',
            'HoraInicioLunesASabado' => 'Hora Inicio Lunes-sabado',
            'HoraFinLunesASabado' => 'Hora Fin Lunes-sabado',
            'Es24HorasDomingo' => 'Es 24hrs Domingo',
            'HoraInicioDomingo' => 'Hora Inicio Domingo',
            'HoraFinDomingo' => 'Hora Fin Domingo',
            'Es24HorasFestivo' => 'Es 24hrs Festivo',
            'HoraInicioFestivo' => 'Hora Inicio Festivo',
            'HoraFinFestivo' => 'Hora Fin Festivo',
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

        $criteria->compare('IdHorarioEspecialRango', $this->IdHorarioEspecialRango, true);
        $criteria->compare('FechaInicio', $this->FechaInicio, true);
        $criteria->compare('FechaFin', $this->FechaFin, true);
        $criteria->compare('Es24HorasLunesASabado', $this->Es24HorasLunesASabado, true);
        $criteria->compare('HoraInicioLunesASabado', $this->HoraInicioLunesASabado, true);
        $criteria->compare('HoraFinLunesASabado', $this->HoraFinLunesASabado, true);
        $criteria->compare('Es24HorasDomingo', $this->Es24HorasDomingo, true);
        $criteria->compare('HoraInicioDomingo', $this->HoraInicioDomingo, true);
        $criteria->compare('HoraFinDomingo', $this->HoraFinDomingo, true);
        $criteria->compare('Es24HorasFestivo', $this->Es24HorasFestivo, true);
        $criteria->compare('HoraInicioFestivo', $this->HoraInicioFestivo, true);
        $criteria->compare('HoraFinFestivo', $this->HoraFinFestivo, true);
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
     * @return HorarioEspecialRango the static model class
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
