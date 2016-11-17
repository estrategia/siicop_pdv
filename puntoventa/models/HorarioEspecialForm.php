<?php

/**
 * HorarioEspecialForm class.
 */
class HorarioEspecialForm extends CFormModel {

    public $IDSede;
    public $IDZona;
    public $CodigoCiudad;
    public $IDComercial;
    public $NombrePuntoVenta;
    public $listPuntoVenta = array();
    public $listPuntoVentaCheck = array();
    public $paso = 1;
    public $listZonas = array();

    /**
     * Declares the validation rules.
     */
    public function rules() {
        $rules = array();
        $rules[] = array('IDSede', 'validarSede');

        if ($this->paso == 2) {
            $rules[] = array('listPuntoVentaCheck', 'required');
        } else {
            $rules[] = array('listPuntoVentaCheck', 'safe');
        }

        $rules[] = array('IDZona, CodigoCiudad, IDComercial, NombrePuntoVenta, paso', 'safe');
        return $rules;
    }

    public function validarSede() {
        if (empty($this->IDSede)) {
            $this->addError('IDZona', 'Identificar sede');
        }
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
            'CodigoCiudad' => 'Ciudad',
            'IDComercial' => 'C&oacute;digo Comercial PDV',
            'NombrePuntoVenta' => 'Nombre PDV',
        );
    }

    public function getZonas() {
        if (!empty($this->IDSede)) {
            return $this->listZonas = Zona::model()->findAll(array(
                'order' => 'NombreZona',
                'condition' => 'IDSede=:sede',
                'params' => array(
                    ':sede' => $this->IDSede
                )
            ));
        }

        return array();
    }

    /*
     * @return array
     */

    public function getPuntosVenta() {
        $this->listPuntoVenta = array();

        $conditions = "t.Estado=:estado";
        $params = array(':estado' => 1);

        if (!empty($this->IDSede)) {
            $conditions .= " AND t.IDSede=:sede";
            $params[':sede'] = $this->IDSede;
        }

        if (!empty($this->IDZona)) {
            $conditions .= " AND t.IDZona=:zona";
            $params[':zona'] = $this->IDZona;
        }

        if (!empty($this->CodigoCiudad)) {
            $conditions .= " AND t.CodigoCiudad=:ciudad";
            $params[':ciudad'] = $this->CodigoCiudad;
        }

        if (!empty($this->IDComercial)) {
            $conditions .= " AND IDComercial=:codigopdv";
            $params[':codigopdv'] = $this->IDComercial;
        }

        if (!empty($this->NombrePuntoVenta)) {
            $conditions .= " AND NombrePuntoDeVenta LIKE :nombre";
            $params[':nombre'] = "%$this->NombrePuntoVenta%";
        }

        $this->listPuntoVenta = PuntoVenta::model()->findAll(array(
            'condition' => $conditions,
            'params' => $params,
        ));

        //return $listPuntoVenta;
    }

    public function getPuntosVentaCheck() {
        $this->listPuntoVenta = array();

        if (!empty($this->listPuntoVentaCheck)) {
            $codPdv = implode("','", $this->listPuntoVentaCheck);
            $this->listPuntoVenta = PuntoVenta::model()->findAll(array(
                'condition' => "t.Estado=:estado AND t.IDPuntoDeVenta IN ('$codPdv')",
                'params' =>  array(':estado' => 1),
            ));
        }
        //return $listPuntoVenta;
    }
    
    public function getPuntosVentaCheckCorreo(){
        $correos = array();

        foreach ($this->listPuntoVenta as $objPuntoVenta) {
            $correos[] = $objPuntoVenta->eMailPuntoDeVenta;
        }
        
        $correos = implode(",", $correos);
        
        return $correos;
    }
    
    
    

}
