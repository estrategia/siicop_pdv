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
	 <input id="idEliminar" type="hidden" value="0">
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
                                    { id:"NombreFlota",header:["Flota", {content:"textFilter"}],width:200,sort:"int",fillspace:true,css:"zebra"},   
                                    { id:"NombreCiudad",header:["Ciudad", {content:"textFilter"}],width:200,sort:"int",fillspace:true,css:"zebra"},  
                                    { id:"NumeroPDVSAsignados",header:["Pdvs asignados", {content:"textFilter"}],width:200,sort:"int",fillspace:true,css:"zebra"},                                    
                                    { id:"", header:"Acciones",width:80,css:"zebra", template:function(obj)
                                        {    
                                            if (obj.NombreFlota === "No tiene flota asignada"||obj.NombreCiudad === "No tiene ciudad asignada")
                                            {
                                                return "";
                                            }
                                            else
                                            {
                                                if (obj.NumeroPDVSAsignados ==="No tiene pdvs asignados")
                                                {
                                                    return "<input title='Asignar Puntos de Venta' class='procesar' type=image src='/img/update.png'>&nbsp"+
													"<input title='Eliminar' class='eliminar' type=image src='/img/delete.png'>";
                                                    
                                                }
                                                else
                                                {
                                                    return "<input title='Asignar Puntos de Venta' class='procesar' type=image src='/img/update.png'>&nbsp"+
                                                    "<input title='Ver detalle' class='verDetalle' type=image src='/img/view.png'>";
                                                }
                                                
                                            }                            
                                        }
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
                                    "procesar":function(e, id, trg)
                                    {
                                        var item = this.getItem(id);
                                        procesarSector(item.IDSectorLRV,item.CodCiudad);
                                    },
                                    "verDetalle":function(e, id, trg)
                                    {
                                        var item = this.getItem(id);
                                        verSector(item.IDSectorLRV,item.CodCiudad);
                                    },
									"eliminar":function(e, id, trg)
                                    {										
										var item = $$("table").getItem(id);
										webix.confirm({                        
										ok:"Si", 
										cancel:"No",
										type:"confirm-error",
										text:"¿Esta Seguro Que Desea Eliminar El Sector "+item.NombreSector+" De La Ciudad "+item.NombreCiudad+"?",
										callback:function(result){ //setting callback
											if(result)
											{                                
												var item = $$("table").getItem(id);
												var url = $("#base").val() + "/puntoventa/sectorCiudadLRV/eliminarSector";
												url=url+"?id="+item.IDSectorLRV+"&codCiudad="+item.CodCiudad;

												webix.ajax().post(url, {id:item.IDSectorLRV, codCiudad:item.CodCiudad}, function(text){	
													if(text=="0")
													{
														webix.alert("La Acción Se Realizó Con Exito");
														$$("table").remove(id);
														$$("table").refresh();														
													}
													else
													{
														if(text=="1")
														{
															webix.alert("El Sector En Esa Ciudad No Existe.");
														}
														else
														{
															webix.alert("No Tienes Permisos Para Eliminar Un Sector.");
														}
																												
													}
												});								
												
												}                        
										   }
										});
                                    }
                                },

                                rowHeight:35,   
                                height: 590,        
                                data: <?php echo $jsonSectorCiudades?>
                            }
                        ]
                    }); 
					
               });
            </script>
        </div>		
        <div id="paginador" class="container-fluid"></div>
</body>