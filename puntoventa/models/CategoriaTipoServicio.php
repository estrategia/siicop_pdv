<?php

/**
 * This is the model class for table "t_categoriastiposservicios".
 *
 * The followings are the available columns in table 't_categoriastiposservicios':
 * @property integer $IDCategoriaTipoServicio
 * @property string $CategoriaTipoDeServicio
 *
 * The followings are the available model relations:
 * @property TipoServicio[] $tiposServicios
 */
class CategoriaTipoServicio extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'm_CategoriaTipoServicio';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('IDCategoriaTipoServicio', 'required', 'on' => 'update', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('CategoriaTipoDeServicio', 'required', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('CategoriaTipoDeServicio', 'length', 'max' => 80),
            array('CategoriaTipoDeServicio', 'validateExist'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('IDCategoriaTipoServicio, CategoriaTipoDeServicio', 'safe', 'on' => 'search'),
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
                    'condition' => "IDCategoriaTipoServicio<>:id AND $attribute=:value",
                    'params' => array(
                        'id' => $this->IDCategoriaTipoServicio,
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
            'tiposServicios' => array(self::HAS_MANY, 'TipoServicio', 'IDCategoriaTipoServicio'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'IDCategoriaTipoServicio' => 'Idcategoria Tipo Servicio',
            'CategoriaTipoDeServicio' => 'Categor&iacute;a',
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

        $criteria->compare('IDCategoriaTipoServicio', $this->IDCategoriaTipoServicio);
        $criteria->compare('CategoriaTipoDeServicio', $this->CategoriaTipoDeServicio, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return CategoriaTipoServicio the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * Retorna lista de categorias para lista desplegable
     * @return lista de categorias (id, nombre)
     */
    public static function listData() {
        $data = self::model()->findAll(array('order' => 'CategoriaTipoDeServicio'));
        $lst = CHtml::listData($data, 'IDCategoriaTipoServicio', 'CategoriaTipoDeServicio');
        return $lst;
    }

}
