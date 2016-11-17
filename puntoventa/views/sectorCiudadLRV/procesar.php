<div class="modal" id="sectorlrv-form-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<?php
    $this->pageTitle = Yii::app()->name . ' - Puntos de Venta - Sector LRV';

    $this->breadcrumbs = array(
        'Inicio' => array('/puntoventa'),
        'Admin' => array('admin'),
        'Sector LRV'
    );
?>

<div class="well" align="center">
    <?php echo CHtml::button('Listar', array('submit' => array('admin'),'encode'=>false, 'id' => '2', 'class' => 'btn btn-primary btn-sm')); ?>
    <?php echo CHtml::button('Crear', array('submit' => array('crear'),'encode'=>false, 'id' => '1', 'class' => 'btn btn-primary btn-sm')); ?>        
</div>

<?php 

$titulo="Procesar Inconsistencia";
?>
<body>

    <div id="mensaje" class="container-fluid" hidden="true" val=""></div>
    <input id="pdvsCargados" type="hidden" value="<?php echo $numeroPDVS ?>">
    <input id="flota" type="hidden" value="<?php echo $nombreFlota ?>">
    <input id="base" type="hidden" value="<?php echo Yii::app()->baseUrl ?>">
    <div id="dataDiv" class="container-fluid" style='width:100%;'>
        <script type="text/javascript" charset="utf-8">           
            webix.ready(function(){
            webix.ui({
            container: "dataDiv",
            padding:8,            
            id:"views",            
            rows: [
                { view:"template", template:"Asignación de puntos de venta", type:"header" },
                {view:"toolbar", 
                cols:[
                  { view:"label", label:"Sector: <?php echo ucwords(strtolower($nombreSector))?>"},
                  { view:"label", label:"" }
                ]},
                {view:"toolbar", 
                        cols:[     
                          { view:"label", label:"<?php echo "Puntos de venta de la flota: ".$nombreFlota?>" },
                          { view:"label", label:"<?php echo "Ciudad: ".$nombreCiudad?>"}
                ]},                           
            {id:"dt",                
            view:"treetable",
			type:{
				checkbox:function(obj, common, value, config)
				{
					
				  //console.log(obj)
				  var checked = (value == config.checkValue) ? 'checked="true"' : '';
				  //alert(obj.NombreSector);
				  if(obj.NombreSector=='<?php echo $nombreSector?>')
				  {
					  return "<input class='webix_table_checkbox' type='checkbox' "+'checked="true"'+""+">";
					  alert("asd");
				  }
				  else
				  {
					  return "<input class='webix_table_checkbox' type='checkbox' "+checked+""+">";
				  }
				  
				},
			  },
            columns:[                 
                { id:"ch1", header:{content:"masterCheckbox",contentId:"cm1"},  template:"{common.checkbox()}", width:40,css:"zebra"}, 
				{ id:"NombrePuntoDeVenta", header:["Punto de venta", {content:"textFilter"}],fillspace:true,sort:"string",css:"zebra",
                    footer:"<button  width:500px class='btn btn-primary agregarPDV'>Agregar PDV</button>"},
				{ id:"IDComercial", header:["Codigo Comercial", {content:"textFilter"}],fillspace:true,sort:"string",css:"zebra"},
                { id:"NombreSector", header:["Sector LRV asignado", {content:"textFilter"}],fillspace:true,sort:"string",css:"zebra"}            
        ],
        select:"row",
        hover:"myhover",
        onClick:{            
            "agregarPDV":function(e, id, trg){  
                Loading.show(); 
                var inconsistencias =[];
                $$("dt").filter("");
                this.eachRow(function(id)
                {
                    var item = this.getItem(id);
                    if(item.ch1 === 1)
                    {
                        inconsistencias.push(this.getItem(id).IDPuntoDeVenta);                         
                    }                                            
                });
                if (inconsistencias.length == 0)
                {
                    webix.alert({ type:"error", text:"No hay ningún punto de venta seleccionado"});             
                }
                else
                {                    
                    var ids=inconsistencias[0];                   
                    for(i=1;i<inconsistencias.length;i++)
                    {                        
                        ids=ids+","+inconsistencias[i];                        
                    }
                    var url = $("#base").val() + "/puntoventa/sectorCiudadLRV/agregar";
                                      
                    webix.ajax().post(url, {ids:ids, IdSector:$("#idSector").val()}, function(text){
					if(text=="S1")
					{
						$("#mensaje").html('<div class="alert alert-success fade in">El punto de venta fue agregado correctamente.</div>').show();
					}
					else
					{
						$("#mensaje").html('<div class="alert alert-success fade in">Los puntos de venta fueron agregados correctamente.</div>').show();                           
					}
					myVar = setTimeout(function(){ ocultarMensaje(); }, 5000);
                    });
                    
                    this.eachRow(function(id)
                    {
                        var item = this.getItem(id);
                        if(item.ch1 === 1)
                        {
                            item['NombreSector']="<?php echo $nombreSector ?>";
							item.ch1=1;					
                        }						
                                              
                    });
                    $$("dt").getHeaderContent("cm1").uncheck();
                    this.refresh();
					
                }                
                Loading.hide();
            }            
        },
        footer:true,
        rowHeight:35,   
        height: 500,
        data: <?php echo $jsonPDVS?>}          
        ]
    });
    
		
		
    
       });  
	   
            
        function recargar()
        {
            location.reload();
        }
        validarNumeroPDVS();
            
        </script>         
    </div>
    <input id="inconsistencias" type="hidden"  value="">
    <input id="idSector" type="hidden"  value="<?php echo $id?>">
    
</body>

