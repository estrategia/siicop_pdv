<?php

/**
 * This is the model class for table "t_ActivosPuntoVenta".
 *
 * The followings are the available columns in table 't_ActivosPuntoVenta':
 * @property string $IdActivoPuntoVenta
 * @property integer $IdActivo
 * @property integer $IDPuntoDeVenta
 * @property integer $Cantidad
 * @property integer $Estado
 * @property string $IdentificacionSolicitante
 * @property string $ObservacionSolicitante
 * @property string $IdentificacionAprobador
 * @property string $ObservacionAprobador
 * @property string $FechaSolicitud
 *
 * The followings are the available model relations:
 * @property Activo $activo
 * @property PuntoVenta $puntoVenta
 * @property ActivosTrazabilidad[] $listActivosTraza
 */
class ActivosPuntoVenta extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 't_ActivosPuntoVenta';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('IdActivoPuntoVenta,ObservacionAprobador','required', 'on' => 'status', 'message'=>'{attribute} no puede estar vacío'),
            array('IdActivo, IDPuntoDeVenta, Cantidad, Estado, IdentificacionSolicitante, ObservacionSolicitante', 'required', 'message'=>'{attribute} no puede estar vacío'),
            array('IdActivo, IDPuntoDeVenta, Estado', 'numerical', 'integerOnly' => true),
            array('Cantidad', 'numerical', 'integerOnly' => true, 'min'=>1, 'tooSmall'=>'Debe solicitar al menos 1 elemento'),
            array('IdentificacionSolicitante, IdentificacionAprobador', 'length', 'max' => 20),
            array('ObservacionSolicitante, ObservacionAprobador', 'length', 'max' => 200),
            array('FechaSolicitud', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('IdActivoPuntoVenta, IdActivo, IDPuntoDeVenta, Cantidad, Estado, IdentificacionSolicitante, ObservacionSolicitante, IdentificacionAprobador, ObservacionAprobador, FechaSolicitud', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'activo' => array(self::BELONGS_TO, 'Activo', 'IdActivo'),
            'puntoVenta' => array(self::BELONGS_TO, 'PuntoVenta', 'IDPuntoDeVenta'),
            'listActivosTraza' => array(self::HAS_MANY, 'ActivosTrazabilidad', 'IdActivoPuntoVenta'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'IdActivoPuntoVenta' => 'Id Activo Punto Venta',
            'IdActivo' => 'Activo',
            'IDPuntoDeVenta' => 'Punto Venta',
            'Cantidad' => 'Cantidad',
            'Estado' => 'Estado',
            'IdentificacionSolicitante' => 'Solicitante',
            'ObservacionSolicitante' => 'Observación Solicitante',
            'IdentificacionAprobador' => 'Aprobador',
            'ObservacionAprobador' => 'Observación Aprobador',
            'FechaSolicitud' => 'Fecha Solicitud',
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

        $criteria->compare('IdActivoPuntoVenta', $this->IdActivoPuntoVenta, true);
        $criteria->compare('IdActivo', $this->IdActivo);
        $criteria->compare('IDPuntoDeVenta', $this->IDPuntoDeVenta);
        $criteria->compare('Cantidad', $this->Cantidad);
        $criteria->compare('Estado', $this->Estado);
        $criteria->compare('IdentificacionSolicitante', $this->IdentificacionSolicitante, true);
        $criteria->compare('ObservacionSolicitante', $this->ObservacionSolicitante, true);
        $criteria->compare('IdentificacionAprobador', $this->IdentificacionAprobador, true);
        $criteria->compare('ObservacionAprobador', $this->ObservacionAprobador, true);
        $criteria->compare('FechaSolicitud', $this->FechaSolicitud, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    
    public function searchSolicitados() {
        $criteria = new CDbCriteria;
        $criteria->order = "t.FechaSolicitud DESC";
        $criteria->with = array('activo');
        $criteria->compare('activo.DescripcionActivo', $this->IdActivo, true);
        //$criteria->with = array('puntoVenta');
        //$criteria->compare('puntoVenta.IDComercial', $this->IdActivoCategoria, true);

        $criteria->compare('t.IdActivoPuntoVenta', $this->IdActivoPuntoVenta, true);
        $criteria->compare('t.IDPuntoDeVenta', $this->IDPuntoDeVenta);
        $criteria->compare('t.Cantidad', $this->Cantidad);
        $criteria->compare('t.Estado', $this->Estado);
        $criteria->compare('t.IdentificacionSolicitante', $this->IdentificacionSolicitante, true);
        $criteria->compare('t.ObservacionSolicitante', $this->ObservacionSolicitante, true);
        $criteria->compare('t.IdentificacionAprobador', $this->IdentificacionAprobador, true);
        $criteria->compare('t.ObservacionAprobador', $this->ObservacionAprobador, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    
    public function searchAprobacion($zona) {
        $criteria = new CDbCriteria;
        $criteria->order = "t.Estado, t.FechaSolicitud DESC";
        $criteria->with = array('activo','puntoVenta'=>array('with'=>array('sede','zona')));
        $criteria->compare('activo.DescripcionActivo', $this->IdActivo, true);
        $criteria->compare('puntoVenta.IDComercial', $this->IDPuntoDeVenta);
        $criteria->compare('puntoVenta.IDZona', $zona->IDZona);
        
        
        $criteria->compare('t.FechaSolicitud', $this->FechaSolicitud, true);
        $criteria->compare('t.IdActivoPuntoVenta', $this->IdActivoPuntoVenta, true);
        $criteria->compare('t.Cantidad', $this->Cantidad);
        $criteria->compare('t.Estado', $this->Estado);
        $criteria->compare('t.IdentificacionSolicitante', $this->IdentificacionSolicitante, true);
        $criteria->compare('t.ObservacionSolicitante', $this->ObservacionSolicitante, true);
        $criteria->compare('t.IdentificacionAprobador', $this->IdentificacionAprobador, true);
        $criteria->compare('t.ObservacionAprobador', $this->ObservacionAprobador, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ActivosPuntoVenta the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public function beforeSave() {
        if ($this->isNewRecord) {
            $this->FechaSolicitud = new CDbExpression('NOW()');
        }
        return parent::beforeSave();
    }

}
