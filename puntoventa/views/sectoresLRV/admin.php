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
                            { view:"template",template:"Listado de sectores" , type:"header"},
                            {id:"table",               
                                view:"datatable",
                                columns:[                                                    
                                    //{ id:"IDSectorLRV",header:["Id Sector", {content:"textFilter"}], width:170,sort:"int",fillspace:true,css:"zebra"},
                                    { id:"NombreSector",header:["Nombre Sector", {content:"textFilter"}],width:200,sort:"int",fillspace:true,css:"zebra"},
									{ id:"", header:"Acciones",width:80,css:"zebra", template:"<input title='Procesar' class='update' type=image src='/img/update.png'>"
                                      
                                    }									
                                ],
                                pager:{
                                    template:"{common.first()} {common.prev()} {common.pages()} {common.next()} {common.last()}",
                                    container:"paginador",
                                    size:13,
                                    group:5
                                },
                                select:"row",
                                hover:"myhover",                               
								onClick:{ 
                                    "update":function(e, id, trg)
                                    {
                                        var item = this.getItem(id);
                                        actualizarSector(item.IDSectorLRV);
                                    },                                    
                                },
                                rowHeight:35,   
                                height: 590,        
                                data: <?php echo $jsonSectores?>
                            }
                        ]
                    }); 
					
               });
                
            </script> 
            
            
        </div>
        <div id="paginador" class="container-fluid"></div>   
        
</body>
