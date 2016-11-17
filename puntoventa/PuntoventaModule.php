<?php

class PuntoventaModule extends CWebModule {

    public $uploadImg = '/images/puntosventa/';
    public $iconImg = '/images/image_sticker.png';
    public $modelImg = '/images/modeloPuntosDeVenta.png';
    public $sessionModelPVExport = 'puntoventaexport';
    public $uploadMasivo = '/cargueInfluencia/';
    public $optPretty = array(
        "slideshow" => 5000,
        "autoplay_slideshow" => false,
        "show_title" => true,
        "theme" => "facebook",
        "social_tools" => "",
        "deeplinking" => false,
    );
    public $elementPretty = "div.gallery > a[rel^=\'prettyPhoto\']";
    private $_assetsUrl;
    public $asocActivo = 'Activo';
    public $infoBasica = 0;
    public $infoContacto = 1;
    public $infoOtros = 2;
    //public $infoGmap = 3;
    public $infoServicios = 3;
    public $infoHorarios = 4;
    public $infoEmpleados = 5;
    public $infoInfluencia = 6;
    public $infoCompetencia = 7;
    public $infoImagenes = 8;
    public $infoHistorial = 9;
    public $estadoActivos = array(
        1 => 'Activo',
        0 => 'Inactivo',
        'Activo' => 1,
        'Inactivo' => 0,
    );
    
    public $listEstadosActivos = array(
        1 => 'Activo',
        0 => 'Inactivo',
    );
    
    public $estadoSolicitudActivo = array(
        0 => 'Pendiente',
        1 => 'Aprobado',
        2 => 'Negado',
        'Pendiente' => 0,
        'Aprobado' => 1,
        'Negado' => 2,
    );
    
    public $listEstadosSolicitudActivo = array(
        0 => 'Pendiente',
        1 => 'Aprobado',
        2 => 'Negado',
    );

    public $horarios = array(
        'HorarioAperturaLunesASabado' => 1, '1' => array('atributo' => 'HorarioAperturaLunesASabado' , 'foranea' => 'horarioAperturaLunesASabado'),
        'HorarioAperturaDomingo' => 2, '2' => array('atributo' => 'HorarioAperturaDomingo', 'foranea' => 'horarioAperturaDomingo'),
        'HorarioAperturaFestivo' => 3, '3' => array('atributo' => 'HorarioAperturaFestivo', 'foranea' => 'horarioAperturaFestivo'),
        'HorarioAperturaEspecial' => 4, '4' => array('atributo' => 'HorarioAperturaEspecial', 'foranea' => 'horarioAperturaEspecial'),
        'HorarioDomicilioLunesASabado' => 5, '5' => array('atributo' => 'HorarioDomicilioLunesASabado', 'foranea' => 'horarioDomicilioLunesASabado'),
        'HorarioDomicilioDomingo' => 6, '6' => array('atributo' => 'HorarioDomicilioDomingo', 'foranea' => 'horarioDomicilioDomingo'),
        'HorarioDomicilioFestivo' => 7, '7' => array('atributo' => 'HorarioDomicilioFestivo', 'foranea' => 'horarioDomicilioFestivo'),
        'HorarioDomicilioEspecial' => 8, '8' => array('atributo' => 'HorarioDomicilioEspecial', 'foranea' => 'horarioDomicilioEspecial'),
    );

    public function getAssetsUrl() {
        if ($this->_assetsUrl === null) {
            $this->_assetsUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.puntoventa.assets'), false, -1, true);
        }
        return $this->_assetsUrl;
    }

    public function init() {
        $this->getAssetsUrl();

        Yii::app()->clientScript
                ->registerCssFile($this->_assetsUrl . '/css/charisma-app.css')
                ->registerCssFile($this->_assetsUrl . '/css/opa-icons.css')
                ->registerCssFile($this->_assetsUrl . '/css/prettyPhoto.css')
                ->registerCssFile($this->_assetsUrl . '/css/puntoventa.css')
                //->registerCssFile($this->_assetsUrl . '/libs/jquery-timepicker-1.3.2/jquery.timepicker.css')
                ->registerScriptFile($this->_assetsUrl . '/js/jquery.prettyPhoto.js', CClientScript::POS_END)
                ->registerScriptFile($this->_assetsUrl . '/js/bootstrap-typeahead.js', CClientScript::POS_END)
                //->registerScriptFile($this->_assetsUrl . '/libs/jquery-timepicker-1.3.2/jquery.timepicker.js', CClientScript::POS_END)
                ->registerScriptFile($this->_assetsUrl . '/js/puntoventa.js', CClientScript::POS_END);

		Yii::app()->clientScript->registerCssFile($this->_assetsUrl .'/css/sectores.css');
				Yii::app()->clientScript->registerScriptFile($this->_assetsUrl .'/js/sectores.js', CClientScript::POS_BEGIN);
				
        $this->setImport(array(
            'application.models.*',
            'application.components.*',
            'puntoventa.models.*',
            'puntoventa.components.*',
            'gestionhumana.models.*',
            'ProgramacionFlotas.models.*',
        ));
    }

    public function beforeControllerAction($controller, $action) {
        if (parent::beforeControllerAction($controller, $action)) {
            return true;
        }
        else
            return false;
    }

}

