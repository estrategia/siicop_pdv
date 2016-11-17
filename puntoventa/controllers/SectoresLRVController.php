<?php

class SectoresLRVController extends Controller {

    /**
     * @var string accion por defecto del controlador
     */
    public $defaultAction = 'admin';    

    /**
     * Crea un nuevo modelo.
     */    

    public function actionCrear() 
    {
        if (Yii::app()->user->isGuest) 
        {
                echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta usuario autenticado, por favor iniciar sesi&oacute;n'));
                Yii::app()->end();
        }            
        if (!Yii::app()->user->checkAccess('PuntoDeVenta_SectoresLRV_crear')) 
        {
                echo CJSON::encode(array('result' => 'error', 'response' => 'Error: 101 => ' . Yii::app()->params['accessError']));
                Yii::app()->end();
        } 
        
		
        $this->render('crear', array(
            
        ));
		
    }
	
	 public function actionUpdate() 
    {
        if (Yii::app()->user->isGuest) 
        {
                echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta usuario autenticado, por favor iniciar sesi&oacute;n'));
                Yii::app()->end();
        }            
        if (!Yii::app()->user->checkAccess('PuntoDeVenta_SectoresLRV_crear')) 
        {
                echo CJSON::encode(array('result' => 'error', 'response' => 'Error: 101 => ' . Yii::app()->params['accessError']));
                Yii::app()->end();
        } 
		
		$id=$_GET['id'];
		$sector = SectorLRV::model()->findByPk($id);
		$nombreSector = $sector->NombreSector;
        
		
        $this->render('update', array(
           'nombreSector'=>$nombreSector,'id'=>$id
        ));		
    }
	
	public function actionModificar() 
    {
        if (Yii::app()->user->isGuest) 
        {
                echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta usuario autenticado, por favor iniciar sesi&oacute;n'));
                Yii::app()->end();
        }            
        if (!Yii::app()->user->checkAccess('PuntoDeVenta_SectoresLRV_crear')) 
        {
                echo CJSON::encode(array('result' => 'error', 'response' => 'Error: 101 => ' . Yii::app()->params['accessError']));
                Yii::app()->end();
        } 
		
		$nombreSector=$_GET['nombreSector'];
		$id=$_GET['id'];
		$sector = SectorLRV::model()->findByAttributes(array("NombreSector"=>$nombreSector));
        if($sector==null)
        {            
            $sector = SectorLRV::model()->findByPk($id);
			$sector->NombreSector=$nombreSector;                
			$sector->update(array('NombreSector')); 
			echo "c";			           
        }
        else
        {
            echo "e2";
        }        	
    }
    
    public function actionCrearSector() 
    {
		if (!Yii::app()->user->checkAccess('PuntoDeVenta_SectoresLRV_crear')) 
        {
            $this->render('//site/error', array('code' => '101', 'message' => Yii::app()->params['accessError']));
            Yii::app()->end();
        } 
        $nombreSector=$_GET['nombreSector'];                    
        $sector = SectorLRV::model()->findByAttributes(array("NombreSector"=>$nombreSector));
        if($sector==null)
        {            
            $nuevoSector=new Sectorlrv();
            $nuevoSector->NombreSector = $nombreSector;
			if($nuevoSector->validate())
			{
				$nuevoSector->save();
				echo "c";
			}
			else
			{
				echo "e3";
			}            
        }
        else
        {
            echo "e2";
        }
    }

    public function actionAdmin() 
    {
        if (Yii::app()->user->isGuest) 
        {
            $this->redirect(Yii::app()->user->loginUrl);
            Yii::app()->end();
        }
        if (!Yii::app()->user->checkAccess('PuntoDeVenta_SectoresLRV_admin')) 
        {
            $this->render('//site/error', array('code' => '101', 'message' => Yii::app()->params['accessError']));
            Yii::app()->end();
        }        
        $model = SectorLRV::model()->findAll();
        $arraySectores=array();
        foreach ($model as $item)
        {                
            $arraySectores[]=$item->attributes;                                 
        }
        
        if(sizeof($arraySectores)>0)
        {
            foreach ($arraySectores as $clave => $fila)
            {
                $flota[$clave] = $fila['NombreFlota']; 
                $sector[$clave]=$fila['NombreSector']; 
            }          
            array_multisort($flota, SORT_ASC,$sector, SORT_ASC, $arraySectores);
        }
         
        $jsonSectores=  json_encode($arraySectores);
        $this->render('admin', array(
            'jsonSectores' => $jsonSectores,
        ));
    }

    public function loadModel($id) {
        $model = SectorLRV::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'Sector no existe.');
        return $model;
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'UbicacionLocal-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
