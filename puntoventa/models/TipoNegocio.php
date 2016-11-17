<?php

/**
 * This is the model class for table "t_tiponegocio".
 *
 * The followings are the available columns in table 't_tiponegocio':
 * @property integer $IDTipoNegocio
 * @property string $NombreTipoNegocio
 *
 * The followings are the available model relations:
 * @property PuntoVenta[] $puntosVenta
 */
class TipoNegocio extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'm_TipoNegocio';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('IDTipoNegocio', 'required', 'on' => 'update', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('NombreTipoNegocio', 'required', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('NombreTipoNegocio', 'length', 'max' => 45),
            array('NombreTipoNegocio', 'validateExist'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('IDTipoNegocio, NombreTipoNegocio', 'safe', 'on' => 'search'),
        );
    }

    /**
     * Valida si existe registro con atributo indicado.
     * Este es un validador declarado en rules().
     */
    public function validateExist($attribute, $params) {
        if (!$this->hasErrors()) {
            $model = null;

            if ($this->isNewRecord) {
                $model = self::model()->find(array(
                    'condition' => "$attribute=:value",
                    'params' => array(
                        'value' => $this->getAttribute($attribute)
                    )
                ));
            } else {
                $model = self::model()->find(array(
                    'condition' => "IDTipoNegocio<>:id AND $attribute=:value",
                    'params' => array(
                        'id' => $this->IDTipoNegocio,
                        'value' => $this->getAttribute($attribute)
                    )
                ));
            }

            if ($model !== null)
                $this->addError($attribute, $this->getAttributeLabel($attribute) . ' ya existe.');
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'puntosVenta' => array(self::HAS_MANY, 'PuntoVenta', 'IDTipoNegocio'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'IDTipoNegocio' => 'Idtipo Negocio',
            'NombreTipoNegocio' => 'Nombre Tipo Negocio',
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

        $criteria->compare('IDTipoNegocio', $this->IDTipoNegocio);
        $criteria->compare('NombreTipoNegocio', $this->NombreTipoNegocio, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return TipoNegocio the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * Retorna lista de sedes para lista desplegable
     * @return lista de sedes (id, nombre)
     */
    public static function listData() {
        $data = self::model()->findAll(array('order' => 'NombreTipoNegocio'));
        $lst = CHtml::listData($data, 'IDTipoNegocio', 'NombreTipoNegocio');
        return $lst;
    }

}
