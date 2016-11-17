<?php

/**
 * HorarioEspecialForm class.
 */
class HorarioEspecialGuardarForm extends CFormModel {
    public $TipoHorario;
    
    public $FechaDia;
    public $HoraInicioDia;
    public $HoraFinDia;
    
    public $FechaInicioRango;
    public $FechaFinRango;
    public $HoraIncioLunesSabado;
    public $HoraFinLunesSabado;
    public $HoraInicioDomingo;
    public $HoraFinDomingo;
    public $HoraInicioFestivo;
    public $HoraFinFestivo;
    
    public $IDComercial = array();
    
    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            // name, email, subject and body are required
            array('IDSede', 'validarSede'),
            array('IDZona, CodigoCiudad, IDComercial, NombrePuntoVenta, listPuntoVentaCheck', 'safe'),
        );
    }
    
    public function validarSede() {
        if(empty($this->IDSede)){
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
            'IDComercial' => 'C&oacute;digo PDV',
            'NombrePuntoVenta' => 'Nombre PDV',
        );
    }
    
    public function getZonas(){
        if(!empty($this->IDSede)){
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
    public function getPuntosVenta(){
        $this->listPuntoVenta = array();
        
        $conditions = "t.Estado=:estado";
        $params = array(':estado'=>1);
        
        if(!empty($this->IDSede)){
            $conditions .= " AND t.IDSede=:sede";
            $params[':sede'] = $this->IDSede;
        }
        
        if(!empty($this->IDZona)){
            $conditions .= " AND t.IDZona=:zona";
            $params[':zona'] = $this->IDZona;
        }
        
        if(!empty($this->CodigoCiudad)){
            $conditions .= " AND t.CodigoCiudad=:ciudad";
            $params[':ciudad'] = $this->CodigoCiudad;
        }
        
        if(!empty($this->IDComercial)){
            $conditions .= " AND IDComercial=:codigopdv";
            $params[':codigopdv'] = $this->IDComercial;
        }
        
        if(!empty($this->NombrePuntoVenta)){
            $conditions .= " AND NombrePuntoDeVenta LIKE :nombre";
            $params[':nombre'] = "%$this->NombrePuntoVenta%";
        }
        
        $this->listPuntoVenta = PuntoVenta::model()->findAll(array(
            'condition' => $conditions,
            'params' => $params,
        ));
        
        //return $listPuntoVenta;
    }

}