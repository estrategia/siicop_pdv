<?php

/**
 * This is the model class for table "m_Activo".
 *
 * The followings are the available columns in table 'm_Activo':
 * The followings are the available columns in table 'm_Activo':
 * @property string $IdActivo
 * @property string $IdActivoCategoria
 * @property integer $Codigo
 * @property string $Referencia
 * @property string $DescripcionActivo
 * @property string $ObservacionActivo
 * @property integer $Estado
 *
 * The followings are the available model relations:
 * @property ActivoCategoria $categoria
 * @property ActivosPuntoVenta[] $listActivosPdv
 */
class Activo extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'm_Activo';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('IdActivo', 'required', 'on' => 'update', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('IdActivoCategoria, Codigo, Referencia, DescripcionActivo, ObservacionActivo', 'required', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('IdActivoCategoria, Codigo, Estado', 'numerical', 'integerOnly' => true),
            //array('IdActivoCategoria', 'length', 'max' => 11),
            array('Codigo','unique', 'message'=>'Elmento ya existe'),
            array('Referencia', 'length', 'max' => 10),
            array('DescripcionActivo', 'length', 'max' => 200),
            array('ObservacionActivo', 'length', 'max' => 100),
            
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('IdActivo, IdActivoCategoria, Codigo, Referencia, DescripcionActivo, ObservacionActivo, Estado', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'categoria' => array(self::BELONGS_TO, 'ActivoCategoria', 'IdActivoCategoria'),
            'listActivosPdv' => array(self::HAS_MANY, 'ActivosPuntoVenta', 'IdActivo'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'IdActivo' => 'Id Activo',
            'IdActivoCategoria' => 'Categoría',
            'Codigo' => 'Código',
            'Referencia' => 'Referencia',
            'DescripcionActivo' => 'Descripción',
            'ObservacionActivo' => 'Observación',
            'Estado' => 'Estado',
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

        $criteria->compare('IdActivo', $this->IdActivo);
        $criteria->compare('IdActivoCategoria', $this->IdActivoCategoria);
        $criteria->compare('DescripcionActivo', $this->DescripcionActivo, true);
        $criteria->compare('ObservacionActivo', $this->ObservacionActivo, true);
        $criteria->compare('Referencia', $this->Referencia, true);
        $criteria->compare('Estado', $this->Estado);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function searchAdmin() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
        $criteria->with = array("categoria");
        $criteria->compare('categoria.NombreCategoria', $this->IdActivoCategoria, true);

        $criteria->compare('IdActivo', $this->IdActivo);
        //$criteria->compare('IdActivoCategoria', $this->IdActivoCategoria);
        $criteria->compare('DescripcionActivo', $this->DescripcionActivo, true);
        $criteria->compare('ObservacionActivo', $this->ObservacionActivo, true);
        $criteria->compare('Referencia', $this->Referencia, true);
        $criteria->compare('Estado', $this->Estado);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'attributes' => array(
                    'IdActivoCategoria' => array(
                        'asc' => 'categoria.NombreCategoria',
                        'desc' => 'categoria.NombreCategoria DESC',
                    ),
                    '*',
                ),
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Activo the static model class
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
            ),
            'AuditFieldBehavior' => array(
                'class' => 'audit.components.AuditFieldBehavior'
            )
        );
    }

    public static function listData() {
        $models = Activo::model()->findAll(array(
            'condition' => 'Estado=:estado',
            'params' => array(
                ':estado' => 1
            )
        ));

        return CHtml::listData($models, 'IdActivo', 'DescripcionActivo');
    }

}
