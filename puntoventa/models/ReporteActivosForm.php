<?php

/**
 * ReporteActivosForm class.
 */
class ReporteActivosForm extends CFormModel {

    public $FechaInicio;
    public $FechaFin;
   // public $IDZona;

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            //array('IDZona', 'safe'),
            array('FechaInicio, FechaFin', 'required', 'message' => '{attribute} no puede estar vacío'),
            array('FechaInicio, FechaFin', 'date', 'format' => 'yyyy-M-d'),
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels() {
        return array(
            'FechaInicio' => 'Fecha inicio',
            'FechaFin' => 'Fecha fin',
            //'IDZona' => 'Zona'
        );
    }

}
