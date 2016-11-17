<?php

/**
 * This is the model class for table "t_SectorCiudadLRV".
 *
 * The followings are the available columns in table 't_SectorCiudadLRV':
 * @property integer $CodCiudad
 * @property integer $IDSectorLRV
 * @property integer $Estado
 */
class SectorCiudadLRV extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 't_SectorCiudadLRV';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('CodCiudad,IdFlota, IDSectorLRV, Estado', 'required', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('CodCiudad,IdFlota, IDSectorLRV, Estado', 'numerical', 'integerOnly' => true),
            array('Estado', 'in', 'range' => array('0', '1'), 'allowEmpty' => false),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('CodCiudad, IDSectorLRV,IdFlota, Estado', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'ciudad' => array(self::BELONGS_TO, 'Ciudad', 'CodCiudad'),
            'sectorlrv' => array(self::BELONGS_TO, 'SectorLRV', 'IDSectorLRV'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
	{
        return array(
            'CodCiudad' => 'Ciudad',
            'IDSectorLRV' => 'Sector LRV',
			'IdFlota' => 'Id Flota',
            'Estado' => 'Estado'
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
        
        $criteria->with = array('ciudad','sectorlrv');
        $criteria->compare('t.CodCiudad', $this->CodCiudad);
        $criteria->compare('t.IDSectorLRV', $this->IDSectorLRV);
		$criteria->compare('t.IdFlota',$this->IdFlota);
        $criteria->compare('t.Estado', $this->Estado);
        

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return SectorCiudadLRV the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
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

}
