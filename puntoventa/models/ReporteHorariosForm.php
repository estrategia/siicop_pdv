<?php

/**
 * ReporteHorarios class.
 * ReporteHorarios is the data structure for keeping
 */
class ReporteHorariosForm extends CFormModel {
    public $IDSede;
    public $IDZona;
    public $IDPuntoDeVenta;
    public $FechaInicio;
    public $FechaFin;

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            // name, email, subject and body are required
            array('IDPuntoDeVenta', 'safe'),
            array('IDSede, IDZona, FechaInicio, FechaFin', 'required', 'message' => '{attribute} no puede estar vac&iacute;o'),
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
            'IDSede' => 'Gerencia Operativa',
            'IDZona' => 'Zona',
            'IDPuntoDeVenta' => 'Punto de Venta',
            'FechaInicio' => 'Fecha inicio',
            'FechaFin' => 'Fecha fin',
        );
    }

}