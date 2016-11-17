<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SwebController
 *
 * @author Miguel Angel Sanchez Montiel
 */
class SwebController extends CController {

    public function actions() {
        return array(
            'puntoventa' => array(
                'class' => 'CWebServiceAction',
            ),
            'zona' => array(
                'class' => 'CWebServiceAction',
            ),
            'sede' => array(
                'class' => 'CWebServiceAction',
            ),
        );
    }

    /**
     * @param array $conditions arreglo asociativo con condiciones para busqueda del punto de venta
     * @return array atributos del punto de venta en forma de array asociativo
     * @soap
     */
    public function getPuntoVenta($condiciones) {
        $result = array();

        try {
            $model = PuntoVenta::searchObject($condiciones);

            foreach ($model as $puntoventa) {
                $arr = array();

                //adiciona cada atributo de la clase punto de venta a un arreglo asociativo
                foreach ($puntoventa as $key => $value) {
                    $arr[$key] = $value;
                }

                //adiciona los nombres de las tablas asociadas (llaves foraneas)
                $arr['NombreCiudad'] = $puntoventa->ciudad->NombreCiudad;
                $arr['NombreSede'] = $puntoventa->sede->NombreSede;
                $arr['NombreZona'] = $puntoventa->zona->NombreZona;
                $arr['NombreCEDI'] = $puntoventa->cedi->NombreCEDI;
                $arr['NombreSector'] = $puntoventa->sector->NombreSector;
                $arr['NombreTipoNegocio'] = $puntoventa->tipoNegocio->NombreTipoNegocio;
                $arr['NombreUbicacion'] = $puntoventa->ubicacion !== null ? $puntoventa->ubicacion->NombreUbicacion : "";

                $result[] = $arr;
            }

            return $result;
        } catch (Exception $exc) {
            Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
            return array();
        }
    }

    public function actionConsumo() {
        ini_set("soap.wsdl_cache_enabled", 0);

        $condiciones = array();
        $condiciones['nomciudad'] = 'RIONEGRO';
        //$condiciones['codciudad'] = 76001;
        //$condiciones['codcomercialpv'] = '3B5';
        //$condiciones['idpv']['like'] = false;
        //$condiciones['ordenar'] = 'IDComercial';
        //$condiciones['idzona']['valor'] = 1;
        //$condiciones['nomzona']['like'] = true;
        //$condiciones['ordenar'] = 'IDComercial';

        $client = new SoapClient('http://localhost/copservir/puntoventa/sweb/puntoventa');
        $arr = $client->getPuntoVenta($condiciones);
        if ($arr === null) {
            echo "NULL ERROR";
        } else {
            //CVarDumper::dump($arr);
            echo "Cantidad: " . count($arr), "<br><br>";
        }

        foreach ($arr as $puntoventa) {
            //echo $puntoventa['IDPuntoDeVenta'] . " - " . $puntoventa['IDComercial'] . " - " . $puntoventa['NombrePuntoDeVenta'] . "<br/>";
            CVarDumper::dump($puntoventa, 10, true);
        }

        //$result = ($arr === null) ? "Error" : CJSON::encode($arr);
        //echo $result;
    }

    /**
     * @param array $condiciones arreglo asociativo con condiciones para busqueda de la sede
     * @return array atributos de la sede en forma de array asociativo
     * @soap
     */
    public function getSede($condiciones) {
        $result = array();
        try {
            $model = Sede::searchObject($condiciones);
            foreach ($model as $sede) {
                $arr = array();

                //adiciona cada atributo de la clase sede a un arreglo asociativo
                foreach ($sede as $key => $value) {
                    $arr[$key] = $value;
                }
                $result[] = $arr;
            }

            return $result;
        } catch (Exception $exc) {
            Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
            return array();
        }
    }

    public function actionConsumoSede() {

        ini_set("soap.wsdl_cache_enabled", 0);

        $condiciones = array();
        //$condiciones['idPuntoVenta']['valor'] = '119';
        //$condiciones['idSede']['valor'] = '6';
        //$condiciones['nombreSede']['valor'] = 'an';
        //$condiciones['nombreSede']['like'] = true;

        $client = new SoapClient('http://localhost/copservir/puntoventa/sweb/puntoventa');
        $arr = $client->getSede($condiciones);

        if ($arr === null) {
            echo "NULL ERROR";
        } else {
            //CVarDumper::dump($arr,10,true);
            echo "Cantidad: " . count($arr), "<br><br>";
        }

        foreach ($arr as $zona) {
            echo $zona['IDSede'] . " - " . $zona['NombreSede'];
            echo "<br>";
        }
    }

    /**
     * @param array $condiciones arreglo asociativo con condiciones para busqueda de la zona
     * @return array atributos de la zona en forma de array asociativo
     * @soap
     */
    public function getZona($condiciones) {
        $result = array();
        try {
            $model = Zona::searchObject($condiciones);
            foreach ($model as $zona) {
                $arr = array();

                //adiciona cada atributo de la clase zona a un arreglo asociativo
                foreach ($zona as $key => $value) {
                    $arr[$key] = $value;
                }
                $result[] = $arr;
            }
            return $result;
        } catch (Exception $exc) {
            Yii::log($exc->getMessage() . "\n" . $exc->getTraceAsString(), CLogger::LEVEL_ERROR, 'application');
            return array();
        }
    }

    /**
     * Ejemplo de consumo de servicio web
     */
    public function actionConsumoZona() {

        ini_set("soap.wsdl_cache_enabled", 0);

        $condiciones = array();
        //$condiciones['idSede']['valor'] = 2;
        //$condiciones['centroCosto']['valor'] = "003";
        //$condiciones['puntosVenta']['valor'] = "124";
        //$condiciones['puntosVenta']['condicional'] = 'OR';
        //$condiciones['nombreZona']['valor'] = 'cali';
        //$condiciones['nombreZona']['like'] = true;

        $client = new SoapClient('http://localhost/copservir/puntoventa/sweb/zona');
        $arr = $client->getZona($condiciones);

        if ($arr === null) {
            echo "NULL ERROR";
        } else {
            //CVarDumper::dump($arr,10,true);
            echo "Cantidad: " . count($arr), "<br><br>";
        }

        foreach ($arr as $zona) {
            echo $zona['IDZona'] . " - " . $zona['NombreZona'] . " - " . $zona['IDSede'];
            echo "<br>";
        }
    }

}

?>
