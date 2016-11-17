<?php

/**
 * This is the model class for table "t_ImagenesPuntosDeVenta".
 *
 * The followings are the available columns in table 't_ImagenesPuntosDeVenta':
 * @property integer $IDImagenPuntoDeVenta
 * @property integer $IDPuntoDeVenta
 * @property string $NombreImagen
 * @property string $TituloImagen
 * @property string $RutaImagen
 * @property string $DescripcionImagen
 * @property integer $TipoImagen
 * @property integer $EstadoImagen
 */
class ImagenPuntoVenta extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 't_ImagenesPuntoVenta';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('IDImagenPuntoDeVenta', 'required', 'on' => 'update', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('IDPuntoDeVenta, NombreImagen, TituloImagen, DescripcionImagen, TipoImagen', 'required', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('IDPuntoDeVenta, TipoImagen, EstadoImagen', 'numerical', 'integerOnly' => true),
            array('NombreImagen, TituloImagen', 'length', 'max' => 45),
            array('RutaImagen, DescripcionImagen', 'length', 'max' => 100),
            array('RutaImagen', 'file', 'safe' => true, 'types' => 'jpg, jpeg, gif, png, bmp', 'maxSize' => 2 * 1024 * 1024, 'allowEmpty' => false, 'on' => 'create', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('RutaImagen', 'file', 'safe' => true, 'types' => 'jpg, jpeg, gif, png, bmp', 'maxSize' => 2 * 1024 * 1024, 'allowEmpty' => true, 'on' => 'update', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('IDImagenPuntoDeVenta, IDPuntoDeVenta, NombreImagen, TituloImagen, RutaImagen, DescripcionImagen, TipoImagen, EstadoImagen', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
            'puntoVenta' => array(self::BELONGS_TO, 'PuntoVenta', 'IDPuntoDeVenta'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'IDImagenPuntoDeVenta' => 'ID',
            'IDPuntoDeVenta' => 'Punto Venta',
            'NombreImagen' => 'Nombre',
            'TituloImagen' => 'T&iacute;tulo',
            'RutaImagen' => 'Ruta',
            'DescripcionImagen' => 'Descripci&oacute;n',
            'TipoImagen' => 'Tipo',
            'EstadoImagen' => 'Estado',
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

        $criteria->compare('IDImagenPuntoDeVenta', $this->IDImagenPuntoDeVenta);
        $criteria->compare('IDPuntoDeVenta', $this->IDPuntoDeVenta);
        $criteria->compare('NombreImagen', $this->NombreImagen, true);
        $criteria->compare('TituloImagen', $this->TituloImagen, true);
        $criteria->compare('RutaImagen', $this->RutaImagen, true);
        $criteria->compare('DescripcionImagen', $this->DescripcionImagen, true);
        $criteria->compare('TipoImagen', $this->TipoImagen);
        $criteria->compare('EstadoImagen', $this->EstadoImagen);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ImagenPuntoVenta the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
