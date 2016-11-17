<?php

/**
 * This is the model class for table "t_RegistroActividad".
 *
 * The followings are the available columns in table 't_RegistroActividad':
 * @property integer $IDRegistro
 * @property integer $CedulaFuncionario
 * @property string $Tabla
 * @property string $Accion
 * @property string $FechaRegistro
 * @property string $RegistroInicio
 * @property string $RegistroFin
 */
class LogPuntoVenta extends CActiveRecord {
    public $search = null;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 't_LogPuntoVenta';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {

        return array(
            array('CedulaFuncionario, Tabla, Accion, RegistroInicio, RegistroFin', 'required'),
            array('FechaRegistro', 'safe'),
            array('CedulaFuncionario', 'length', 'max' => 20),
            array('Tabla, Accion', 'length', 'max' => 45),
            array('search, IDRegistro, CedulaFuncionario, Tabla, Accion, FechaRegistro, RegistroInicio, RegistroFin', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
            
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'IDRegistro' => 'ID',
            'CedulaFuncionario' => 'C&eacute;dula Funcionario',
            'Tabla' => 'Tabla',
            'Accion' => 'Acci&oacute;n',
            'FechaRegistro' => 'Fecha Registro',
            'RegistroInicio' => 'Registro Inicio',
            'RegistroFin' => 'Registro Fin'
        );
    }

    /*
     * @return CActiveRecord registro que se guarda en base de datos
     */

    public function beforeSave() {
        if ($this->isNewRecord) {
            $this->FechaRegistro = new CDbExpression('NOW()');
        }
        return parent::beforeSave();
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
        $criteria = new CDbCriteria;

        if ($this->search !== null && !empty($this->search)) {
            $palabras = explode(Yii::app()->params->searchSeparator, $this->search);
            
            foreach ($palabras as $palabra) {
                //$criteria->compare('IDRegistro', $this->IDRegistro);
                $criteria->compare('CedulaFuncionario', $palabra, true, 'OR');
                $criteria->compare('Tabla', $palabra, true, 'OR');
                $criteria->compare('Accion', $palabra, true, 'OR');
                $criteria->compare('FechaRegistro', $palabra, true, 'OR');
                $criteria->compare('RegistroInicio', $palabra, true, 'OR');
                $criteria->compare('RegistroFin', $palabra, true, 'OR');
            }
        } else {

            $criteria->compare('IDRegistro', $this->IDRegistro);
            $criteria->compare('CedulaFuncionario', $this->CedulaFuncionario);
            $criteria->compare('Tabla', $this->Tabla, true);
            $criteria->compare('Accion', $this->Accion, true);
            $criteria->compare('FechaRegistro', $this->FechaRegistro, true);
            $criteria->compare('RegistroInicio', $this->RegistroInicio, true);
            $criteria->compare('RegistroFin', $this->RegistroFin, true);
        }
        
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return LogPuntoVenta the static model class
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
        ));
    }

    public static function registrar($empleado, $accion, $tabla, $modeloini, $modelofin = null) {
        $log = new LogPuntoVenta;
        $log->CedulaFuncionario = $empleado;
        $log->Tabla = $tabla; //$modeloini->tableName();
        $log->Accion = $accion;
        $log->RegistroInicio = $modeloini; //CJSON::encode($modeloini);

        if ($modelofin === null) {
            $log->RegistroFin = "[No Aplica]";
        } else {
            $log->RegistroFin = $modelofin; //CJSON::encode($modelofin);
        }

       if (!$log->save()){
            throw new Exception('Error al guardar registro. ' . $log->validateErrorsResponse());
        }  else {
        }
    }

}
