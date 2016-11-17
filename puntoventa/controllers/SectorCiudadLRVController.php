<?php

class SectorCiudadLRVController extends Controller 
{

    /**
     * @var string accion por defecto del controlador
     */
    public $defaultAction = 'admin';

    public function actionSectorAutocomplete() {
        $term = null;

        if (Yii::app()->request->isPostRequest)
            $term = $_POST['term'];
        else
            $term = $_GET['term'];

        $results = array();

        if ($term !== null && trim($term) != '') {
            $term = trim($term);

            $criteria = new CDbCriteria;
            $criteria->order = 't.NombreSector';
            $criteria->join = 'INNER JOIN t_SectorCiudadLRV as sectorlrv ON (sectorlrv.IDSectorLRV = t.IDSectorLRV)';
            $criteria->condition = 'sectorlrv.Estado=:estado AND t.NombreSector LIKE :term';
            $criteria->params = array(':estado' => 1, ':term' => "%$term%");
            $criteria->distinct = true;
            $criteria->limit = 20;

            $models = SectorLRV::model()->findAll($criteria);

            foreach ($models as $model) {
                $results[] = array(
                    'label' => $model->NombreSector,
                    'value' => $model->IDSectorLRV,
                );
            }
        }

        echo CJSON::encode($results);
        Yii::app()->end();
    }


    /**
     * Crea un nuevo modelo.
     */
    public function actionCrear(){
        

            if (Yii::app()->user->isGuest) {
                echo CJSON::encode(array('result' => 'error', 'response' => 'No se detecta usuario autenticado, por favor iniciar sesi&oacute;n'));
                Yii::app()->end();
            }

            if (!Yii::app()->user->checkAccess('PuntoDeVenta_SectorCiudadLRV_crear')) 
			{
				echo CJSON::encode(array('result' => 'error', 'response' => 'Error: 101 => ' . Yii::app()->params['accessError']));
				Yii::app()->end();
			}
			
			$model = Flota::model()->findAll();
			$arrayFlotas=array();
			foreach ($model as $item)
			{                
				$arrayFlotas[]=$item->attributes;                                 
			}        
			$contador=1;      
			$arrayFlotasString="[";
			for($i=0;$i<sizeof($model);$i++)
			{    
				$ciudad=  Ciudad::model()->findByPk($arrayFlotas[$i]['IdCiudad']);            
				$arrayFlotasString=$arrayFlotasString."{ value:'".$ciudad->NombreCiudad." ".$model[$i]['NombreFlota']."', id:'".$model[$i]['IdFlota']."'},";
				$contador+=1;
			}        
			$arrayFlotasString=$arrayFlotasString."]"; 
			
			$arrayCiudades = "";
			$ConsultaCiudades = "SELECT DISTINCT f.IdCiudad,c.NombreCiudad FROM m_Flota f,m_Ciudad c where c.IdCiudad = f.IdCiudad and f.IdEstadoRegistro;";

			$command = Yii::app()->db->createCommand($ConsultaCiudades);
			$Ciudades = $command->queryAll();

			foreach ($Ciudades as $Posicion => $Ciudad)
			{
				$arrayCiudades .= "{ value:'".$Ciudad['NombreCiudad']."', id:'".$Ciudad['IdCiudad']."'},";
			}	

			$sectores = SectorLRV::model()->findAll();
			$arraySectores="";
			foreach($sectores as $item)
			{
				$arraySectores.= "{ value:'".$item->NombreSector."', id:'".$item->IDSectorLRV."'},";
			}
				
			$this->render('crear', array(
				'stringFlotas' => $arrayFlotasString,'ciudades'=>$arrayCiudades,'sectores'=>$arraySectores
			)); 
    }  

    /**
     * Manages all models.
     */
    public function actionAdmin() 
	{
        if (Yii::app()->user->isGuest) 
		{
            $this->redirect(Yii::app()->user->loginUrl);
            Yii::app()->end();
        }

        if (!Yii::app()->user->checkAccess('PuntoDeVenta_SectorCiudadLRV_admin')) 
		{
			$this->render('//site/error', array('code' => '101', 'message' => Yii::app()->params['accessError']));
			Yii::app()->end();
		}

		
		
		
        $model = SectorCiudadLRV::model()->findAllByAttributes(array("Estado"=>1));
		$arraySectorCiudades=array();
        foreach ($model as $item)
        {                
            $arraySectorCiudades[]=$item->attributes;                                 
        }
		
		for($i=0;$i<sizeof($arraySectorCiudades);$i++)
		{
			$PDVSAsignados = PuntoVenta::model()->findAllByAttributes(array("IDSectorLRV"=>$arraySectorCiudades[$i]['IDSectorLRV'],"CodigoCiudad"=>$arraySectorCiudades[$i]['CodCiudad']));
            if(sizeof($PDVSAsignados)>0)
            {
                $numeroPDVSAsignados =  sizeof($PDVSAsignados);
            }
            else
            {
                $numeroPDVSAsignados = "No tiene pdvs asignados";
            }
			$NombreSector = SectorLRV::model()->findByPk($arraySectorCiudades[$i]['IDSectorLRV']);
			$ciudad = Ciudad::model()->findByAttributes(array("CodCiudad"=>$arraySectorCiudades[$i]['CodCiudad']));
			
			$flota = Flota::model()->findByPk($arraySectorCiudades[$i]['IdFlota']);			 
			
			if($flota==null)
			{
				$nombreFlota="No tiene flota asignada";
				$idFlota=0;
			}
			else
			{
				$nombreFlota=$flota->NombreFlota;
				$idFlota=$flota->IdFlota;
			}
			
			$array1=array("NombreSector"=>$NombreSector->NombreSector,"NombreCiudad"=>$ciudad->NombreCiudad,"CodCiudad"=>$arraySectorCiudades[$i]['CodCiudad'],"NumeroPDVSAsignados"=>$numeroPDVSAsignados,"NombreFlota"=>$nombreFlota,"IdFlota"=>$idFlota);
            $arraySectorCiudades[$i]=array_merge($array1,$arraySectorCiudades[$i]);
		}
		if(sizeof($arraySectorCiudades)>0)
		{
			foreach ($arraySectorCiudades as $clave => $fila) 
			{
				$ciudadOrdenar[$clave] = $fila['NombreCiudad'];                 
			}
			array_multisort($ciudadOrdenar, SORT_ASC, $arraySectorCiudades);
		} 
		
        $jsonSectorCiudades = json_encode($arraySectorCiudades);			
		
        $this->render('admin', array(
            'model' => $model,'jsonSectorCiudades'=>$jsonSectorCiudades
        ));		
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($idciudad, $idsector) {
        $model = SectorCiudadLRV::model()->findByPk(array('CodCiudad' => $idciudad, 'IDSectorLRV' => $idsector));

        if ($model === null)
            throw new CHttpException(404, 'Sector no existe.');
        return $model;
    }
	

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'UbicacionLocal-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
	
	public function actionObtenerFlotas($IdCiudad)
    {
        if (Yii::app()->user->checkAccess('PuntoDeVenta_SectorCiudadLRV_crear'))
        {
            $condicionFiltro="";
            if(isset($_GET['filter']))
            {
                $condicionFiltro=" and f.NombreFlota like '%".$_GET['filter']['value']."%'";
            }
            $flotas = Flota::model()->findAllbysql("SELECT f.IdFlota,f.NombreFlota From m_Flota f where f.IdCiudad=".$IdCiudad.$condicionFiltro.";");   
            $arrayFlotas=array();
            foreach ($flotas as $item)
            {
                array_push($arrayFlotas, array('id'=>$item->IdFlota, 'value'=>$item->NombreFlota));
            } 
            echo json_encode($arrayFlotas);
        }
        else
        {
            $this->render( '//site/error', array( 'code' => '101' , 'message' => Yii::app()->params['accessError'] ) );
        }
    }
	
	 public function actionCrearSector() 
    {
		if (!Yii::app()->user->checkAccess('PuntoDeVenta_SectorCiudadLRV_procesar')) 
        {
            $this->render('//site/error', array('code' => '101', 'message' => Yii::app()->params['accessError']));
            Yii::app()->end();
        } 
        $Idsector=$_GET['sector'];
        $flota=$_GET['flota'];            
		$ciudad = Ciudad::model()->findByPk($_GET['ciudad']);
        $sector = SectorCiudadLRV::model()->findAllbysql("SELECT * FROM t_SectorCiudadLRV where IDSectorLRV=".$Idsector." and CodCiudad='".$ciudad->CodCiudad."' and Estado = 1;");
        if(sizeof($sector)==0)
        {
			$nuevoSectorCiudadLRV = new SectorCiudadLRV();
			$nuevoSectorCiudadLRV->CodCiudad=$ciudad->CodCiudad;
			$nuevoSectorCiudadLRV->IDSectorLRV=$Idsector;
			$nuevoSectorCiudadLRV->IdFlota = $flota;
			$nuevoSectorCiudadLRV->Estado=1;
			if($nuevoSectorCiudadLRV->validate())
			{
				$nuevoSectorCiudadLRV->save();
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
	
	public function actionProcesarSector() 
    {
        if (Yii::app()->user->checkAccess('PuntoDeVenta_SectorCiudadLRV_procesar'))
        {                  
            $id = $_GET['id'];
			
			$cod = $_GET['cod']; 
						
            $sector =  SectorLRV::model()->findByPk($id);
            $nombreSector=$sector->NombreSector;  
			$sectorCiudad = SectorCiudadLRV::model()->findbysql("SELECT * FROM t_SectorCiudadLRV where IDSectorLRV=".$id." and CodCiudad='".$cod."';");	
			
            $flotaPuntoDeVenta = FlotaPuntoVenta::model()->findAllByAttributes(array("IdFlota"=>$sectorCiudad->IdFlota)); 
            $flota=  Flota::model()->findByPk($sectorCiudad->IdFlota);         
            
            $ciudadLRV = SectorCiudadLRV::model()->findByAttributes(array("IDSectorLRV"=>$sector->IDSectorLRV));
            $ciudad = Ciudad::model()->findByAttributes(array("CodCiudad"=>$sectorCiudad->CodCiudad));
               
            $nombreCiudad=$ciudad['NombreCiudad'];
            $nombreFlota=$flota->NombreFlota;
			
				$arrayIdsPDVS=array();
				foreach ($flotaPuntoDeVenta as $item)
				{                
					$arrayIdsPDVS[]=$item->IDPuntoDeVenta;                                 
				}                      
				$pdvs = PuntoVenta::model()->findAllByAttributes(array("IDPuntoDeVenta"=>$arrayIdsPDVS));
				$arraypdvs=array();
				foreach ($pdvs as $item)
				{                
					$arraypdvs[]=$item->attributes;                                 
				}
			
             
            for($i=0;$i<sizeof($arraypdvs);$i++)
            {
                $sectorLRV = SectorLRV::model()->findByPk($arraypdvs[$i]['IDSectorLRV']);
                if($sectorLRV!=null)
                {
					if($nombreSector==$sectorLRV->NombreSector)
					{
						$array1=array("NombreSector"=>$sectorLRV->NombreSector,"ch1"=>1);
					}
					else
					{
						$array1=array("NombreSector"=>$sectorLRV->NombreSector,"ch1"=>0);
					}
                }
                else
                {
                    $array1=array("NombreSector"=>"No tiene sector asignado","ch1"=>0);
                }
                $arraypdvs[$i]=array_merge($array1,$arraypdvs[$i]);                
            } 
            if(sizeof($arraypdvs)>0)
            {
                foreach ($arraypdvs as $clave => $fila) 
                {
                    $sectorOrdenar[$clave] = $fila['NombreSector'];                 
                }
                array_multisort($sectorOrdenar, SORT_ASC, $arraypdvs);
            }            
            $jsonPDVS =  json_encode($arraypdvs);
			
            $this->render('procesar', array(
                'id'=>$id,'jsonPDVS'=>$jsonPDVS,'nombreSector'=>$nombreSector,'nombreFlota'=>$nombreFlota,"nombreCiudad"=>$nombreCiudad,'numeroPDVS'=>  sizeof($arraypdvs)
            ));       
		
        }
        else
        {
            $this->render( '//site/error', array( 'code' => '101' , 'message' => Yii::app()->params['accessError'] ) );
        }
    }
	
	public function actionAgregar()
    {
        if (Yii::app()->user->checkAccess('PuntoDeVenta_SectorCiudadLRV_procesar'))
        {            
            $id=$_POST["IdSector"];
            $ids=$_POST["ids"];  
            $idspdvs = explode(",",$ids);            
            for($i=0;$i<sizeof($idspdvs);$i++)
            {
                $pdv = PuntoVenta::model()->findByPk($idspdvs[$i]);
                $pdv->IDSectorLRV=$id;                
                $pdv->update(array('IDSectorLRV'));                 
            }  
            if(sizeof($idspdvs)<2)
            {
                echo "S1";
            }
            else
            {
                echo "S2";
            }           
        }
        else
        {
            echo "E1";  
        }
    } 
	
	public function actionVerSector() 
    {        
	
		if (!Yii::app()->user->checkAccess('PuntoDeVenta_SectorCiudadLRV_procesar')) 
		{
			echo CJSON::encode(array('result' => 'error', 'response' => 'Error: 101 => ' . Yii::app()->params['accessError']));
			Yii::app()->end();
		}
        $idSector=$_GET['id'];
		$id = $_GET['id'];			
		$cod = $_GET['cod'];
        $sector =  SectorLRV::model()->findByPk($idSector);
		$sectorCiudad = SectorCiudadLRV::model()->findbysql("SELECT * FROM t_SectorCiudadLRV where IDSectorLRV=".$id." and CodCiudad='".$cod."' and Estado = 1;");	
        $nombreSector=$sector->NombreSector;
        $flota=  Flota::model()->findByPk($sectorCiudad->IdFlota);
        $nombreFlota=$flota->NombreFlota;      
        $ciudad = Ciudad::model()->findByAttributes(array("CodCiudad"=>$sectorCiudad->CodCiudad));
        $nombreCiudad=$ciudad['NombreCiudad'];
        $PDVSAsignados = PuntoVenta::model()->findAllByAttributes(array("IDSectorLRV"=>$idSector,"CodigoCiudad"=>$sectorCiudad->CodCiudad));
        $arrayPDVS=array();
        foreach ($PDVSAsignados as $item)
        {                
            $arrayPDVS[]=$item->attributes;                                 
        } 
        if(sizeof($arrayPDVS)>0)
        {
            foreach ($arrayPDVS as $clave => $fila) 
            {
                $nombrePDVOrdenar[$clave] = $fila['NombrePuntoDeVenta'];                
            }
            array_multisort($nombrePDVOrdenar, SORT_ASC, $arrayPDVS);            
        }
        $jsonPDVS=  json_encode($arrayPDVS);      
		
        $this->render('consultar', array(
            'jsonPDVS'=>$jsonPDVS,'nombreSector'=>$nombreSector,'nombreFlota'=>$nombreFlota,'nombreCiudad'=>$nombreCiudad
        ));    
			
    }
	
	public function actionEliminarSector()
	{
		if (Yii::app()->user->checkAccess('PuntoDeVenta_SectorCiudadLRV_procesar'))
        { 
			$id=$_POST["id"];
			$codCiudad=$_POST["codCiudad"];
			$sectorCiudad = SectorCiudadLRV::model()->findbysql("SELECT * FROM t_SectorCiudadLRV where IDSectorLRV=".$id." and CodCiudad='".$codCiudad."' and Estado = 1;");
			if($sectorCiudad!=null)
			{
				$sectorCiudad->Estado=0;                
				$sectorCiudad->update(array('Estado'));
				echo "0";
			}
			else
			{
				echo "1";
			}			
		}
		else
		{
			echo "2";
		}
		
		
	}

}
