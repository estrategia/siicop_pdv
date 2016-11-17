<?php

/**
 * Modelo creado para la tabla: "m_sectorlrv".
 *
 * Las siguientes son las colunas de la tabla:  'm_sectorlrv':
 * @property integer $IDSectorLRV
 * @property string $NombreSector
 * @property integer $IdFlota
 * @property integer $EstadoSector
 *
 * The followings are the available model relations:
 * @property MCiudad[] $mCiudads
 */
class SectorLRV extends CActiveRecord
{

        /**
        * Funcion que reescribe el metodo behaviors de CActiveRecord para el registro en Audit Module
        * @return type
        */
       public function behaviors() {
           return array(
               'AuditFieldBehavior' => array(
                   // Path to AuditFieldBehavior class.
                   'class' => 'audit.components.AuditFieldBehavior'
               )
           );
       }

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'm_SectorLRV';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('NombreSector', 'required'),			
			array('NombreSector', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('IDSectorLRV, NombreSector', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'mCiudads' => array(self::MANY_MANY, 'MCiudad', 't_sectorciudadlrv(IDSectorLRV, CodCiudad)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'IDSectorLRV' => 'Idsector Lrv',
			'NombreSector' => 'Nombre Sector',			
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('IDSectorLRV',$this->IDSectorLRV);
		$criteria->compare('NombreSector',$this->NombreSector,true);
		
		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SectorLRV the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
