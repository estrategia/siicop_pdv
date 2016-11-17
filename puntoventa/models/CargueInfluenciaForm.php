<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class CargueInfluenciaForm extends CFormModel {
    public $archivo;

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            // name, email, subject and body are required
            array('archivo', 'required', 'message' => '{attribute} no puede estar vac&iacute;o'),
            array('archivo', 'file', 'safe'=>true, 'types' => 'xls, xlsx', 'allowEmpty' => false),
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels() {
        return array(
            'archivo' => 'Archivo Excel',
        );
    }

}