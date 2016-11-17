<?php

/**
 * This is the model class for table "t_tiposservicios".
 *
 * The followings are the available columns in table 't_tiposservicios':
 * @property integer $IDTipoServicio
 * @property integer $IDCategoriaTipoServicio
 * @property string $NombreTipoServicio
 *
 * The followings are the available model relations:
 * @property PuntoVenta[] $puntosVenta
 * @property CategoriaTipoServicio $categoriaTipoServicioo
 */
class TipoServicio extends CActiveRecord {

    public $categoria_search;
    public $puntoventa_search = null;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'm_TipoServicio';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('IDTipoServicio', 'required', 'on' => 'update', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('IDCategoriaTipoServicio, NombreTipoServicio', 'required'),
            array('IDCategoriaTipoServicio', 'numerical', 'integerOnly' => true),
            array('NombreTipoServicio', 'length', 'max' => 100),
            array('NombreTipoServicio', 'validateExist'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('categoria_search, IDTipoServicio, IDCategoriaTipoServicio, NombreTipoServicio', 'safe', 'on' => 'search'),
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
                    'condition' => "IDTipoServicio<>:id AND $attribute=:value",
                    'params' => array(
                        'id' => $this->IDTipoServicio,
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
            'puntosVenta' => array(self::MANY_MANY, 'PuntoVenta', 't_ServiciosPuntoVenta(IDTipoServicio, IDPuntoDeVenta)'),
            'categoriaTipoServicio' => array(self::BELONGS_TO, 'CategoriaTipoServicio', 'IDCategoriaTipoServicio'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'IDTipoServicio' => 'Tipo Servicio',
            'IDCategoriaTipoServicio' => 'Categor&iacute;a',
            'NombreTipoServicio' => 'Servicio',
            'categoria_search' => 'Categor&iacute;a'
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

        if ($this->puntoventa_search !== null) {
            $criteria->join = 'INNER JOIN t_ServiciosPuntoVenta as serviciopuntos ON (serviciopuntos.IDTipoServicio = t.IDTipoServicio)';
            $criteria->compare('serviciopuntos.IDPuntoDeVenta', $this->puntoventa_search);
        }

        $criteria->with = array('categoriaTipoServicio');
        $criteria->compare('categoriaTipoServicio.CategoriaTipoDeServicio', $this->categoria_search, true);
        $criteria->compare('IDTipoServicio', $this->IDTipoServicio);
        $criteria->compare('IDCategoriaTipoServicio', $this->IDCategoriaTipoServicio);
        $criteria->compare('NombreTipoServicio', $this->NombreTipoServicio, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'attributes' => array(
                    'categoria_search' => array(
                        'asc' => 'categoriaTipoServicio.CategoriaTipoDeServicio',
                        'desc' => 'categoriaTipoServicio.CategoriaTipoDeServicio  DESC',
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
     * @return TipoServicio the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * Retorna lista de tipos de servicios para lista desplegable
     * @return lista de tipos de servicios (id, nombre)
     */
    public static function listData() {
        $data = self::model()->findAll(array('order' => 'NombreTipoServicio'));
        $lst = CHtml::listData($data, 'IDTipoServicio', 'NombreTipoServicio');
        return $lst;
    }

}
