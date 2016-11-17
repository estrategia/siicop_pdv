<?php
    $this->pageTitle = Yii::app()->name . ' - Puntos de Venta - Sector LRV';

    $this->breadcrumbs = array(
        'Inicio' => array('admin'),
        'Admin' => array('admin'),
        'Sector LRV'
    );
?>

<div class="well" align="center">
    <?php echo CHtml::button('Listar', array('submit' => array('admin'),'encode'=>false, 'id' => '2', 'class' => 'btn btn-primary btn-sm')); ?>
    <?php echo CHtml::button('Crear', array('submit' => array('crear'),'encode'=>false, 'id' => '1', 'class' => 'btn btn-primary btn-sm')); ?>        
</div>

<body>
    <input id="base" type="hidden" value="<?php echo Yii::app()->baseUrl ?>">
    <input id="sectoresCargados" type="hidden" value="<?php //echo $inconsistenciasCargadas ?>"> 
    <div id="inconsistencias-cargadas" class="display-none error-dev alert alert-dismissable alert-success"></div>
    <div id="no-inconsistencias-cargadas" class="display-none error-dev alert alert-dismissable alert-warning"></div>
    <div id="dataDiv" class="container-fluid" style='width:100%;'>       
        <script type="text/javascript" charset="utf-8">            
                webix.ready(function()
                {                    
                    webix.ui(
                    {
                        container: "dataDiv",
                        padding:8,                        
                        id:"views",        
                        rows: [
                            { view:"template",template:"Detalle sector" , type:"header"},
                            {view:"toolbar", 
                            cols:[
                              { view:"label", label:"Sector: <?php echo ucwords(strtolower($nombreSector))?>"},
                              { view:"label", label:"" }
                            ]},
                            {view:"toolbar", 
                                cols:[     
                                  { view:"label", label:"<?php echo "Flota: ".$nombreFlota?>" },
                                  { view:"label", label:"<?php echo "Ciudad: ".$nombreCiudad?>" }

                            ]},
                            {id:"table",               
                                view:"datatable",
                                columns:[                                                    
                                    //{ id:"IDSectorLRV",header:["Id Sector", {content:"textFilter"}], width:170,sort:"int",fillspace:true,css:"zebra"},
                                    { id:"IDComercial",header:["Código Comercial", {content:"textFilter"}],width:200,sort:"int",css:"zebra"},   
                                    { id:"NombrePuntoDeVenta",header:["Nombre PDV", {content:"textFilter"}],width:200,sort:"int",fillspace:true,css:"zebra"},                                    
                                ],
                                pager:{
                                    template:"{common.prev()} {common.pages()} {common.next()}",
                                    container:"paginador",
                                    size:13,
                                    group:5
                                },
                                select:"row",
                                hover:"myhover",
                                rowHeight:35,   
                                height: 520,        
                                data: <?php echo $jsonPDVS?>
                            }
                        ]
                    });        
               });            
                
                
            </script> 
            
            
        </div>
        <div id="paginador" class="container-fluid"></div>   
        
</body>