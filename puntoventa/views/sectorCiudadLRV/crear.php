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
    $this->pageTitle = Yii::app()->name . ' - Puntos de Venta - Sector Ciudad LRV';

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

<body>
    <input id="base" type="hidden" value="<?php echo Yii::app()->baseUrl ?>">
    
    <div id="dataDiv" class="container-fluid" style='width:100%;'>
    </div>
    <div id="flotaCreada" class="display-none error-dev alert alert-dismissable alert-success">asdasd</div>
    <div id="flotaNoCreada" class="display-none error-dev alert alert-dismissable alert-danger"></div>
    <script type="text/javascript" charset="utf-8">  
        
        webix.ui({
        container: "dataDiv",
        padding:8,
        
        id:"views",            
        rows: [
            { view:"template", template:"Crear sector", type:"header" },
            {   
                view:"form",
                css:"toolbar",
                paddingY: 5,
                paddingX: 10,  
                rules:{
                    "nombreSector": webix.rules.isNotEmpty,
                    "flota": function(value){ return true;}
                },
                cols:[                    
                   // {stringResult:true, id:"nombreSector",invalidMessage: "El nombre de el sector no puede ser vacío",name:"nombreSector",view:"text", label:"Nombre:", width:350,tooltip:"Escriba el nombre de el sector."},                    
                    {view:"combo", id:"IdSector", name:"IdSector", invalidMessage: "Ciudad no puede ser vacio", label:"Sector:",tooltip:"Selecciona un Sector.",suggest:
                        {body:
                                {
                                yCount:5,
                            data:[ <?php echo $sectores; ?> ],
                        },
                        filter:function(item, value)
                        {
                                return (item.value.toString().toLowerCase().indexOf(value.toLowerCase()) !== -1);
                        }
                        }
                    },
					{view:"combo", id:"IdCiudad", name:"IdCiudad", invalidMessage: "Ciudad no puede ser vacio", label:"Ciudad:",tooltip:"Selecciona una Ciudad.",suggest:
                        {body:
                                {
                                yCount:5,
                            data:[ <?php echo $ciudades; ?> ],
                        },
                        filter:function(item, value)
                        {
                                return (item.value.toString().toLowerCase().indexOf(value.toLowerCase()) !== -1);
                        }
                        }
                    },
                    {id:"flota", name:"flota",invalidMessage: "Sector no puede ser vacio",view:"combo",disabled: true,width:450, label:"Flota:",tooltip:"Selecciona una flota.",suggest:
                        {
                            body:
                            {
                                yCount:5,
                                data:<?php echo $stringFlotas; ?>
                            },
                            filter:function(item, value)
                            {
                                return (item.value.toString().toLowerCase().indexOf(value.toLowerCase()) !== -1);
                            }
                        }
                    },                    
                    {
                        view:"button",  value: "Submit",label:"Crear",height:50, width:100 , click:function()
                        {
                            var error="";
                            var mensaje="Por favor corrija los siguientes errores:</br><ul>";
							var sector=$$('IdSector').getValue();
                            if(sector=="")
                            {   
                                error+="a";
                                mensaje+="<li>Por favor seleccione un sector sector.</li>";
                                $$('IdSector').focus();                                
                            } 
							var ciudad=$$('IdCiudad').getValue();
							if(ciudad=="")
                            {   
                                error+="b";
                                mensaje+="<li>Por favor seleccione una ciudad.</li>";
                                                               
                            }							
                            var flota=$$('flota').getValue();
                            if(flota=="")
                            {
                                error+="c";
                                mensaje+="<li>Por favor seleccione una flota.</li>";
                            }
                            if(error=="")
                            {
								
                                var url = $("#base").val() + "/puntoventa/sectorCiudadLRV/crearSector";                                  
                                url=url+"?sector="+$$('IdSector').getValue().toUpperCase()+"&flota="+flota+"&ciudad="+$$('IdCiudad').getValue();
                                webix.ajax(url,function(text)
                                {
                                    if(text=="c")
                                    {
                                        $$('IdSector').setValue('');
                                        $$('flota').disable(true);
                                        $$('IdCiudad').setValue('');
                                        
                                        mensaje="Se creó el sector exitosamente.";
                                        mostrarMensaje(mensaje);                                        
                                    }
                                    else
                                    {
                                        if(text=="e2")
                                        {
                                            error+="d";
                                            mensaje="Ya existe un sector con ese nombre en esa ciudad.";
                                            mostrarError(mensaje);
                                        }
										else
										{
											mensaje="Ocurrio un error en la creación de la ciudad sector LRV.";
                                            mostrarError(mensaje);
										}
                                    }                                    
                                });
								
                            }
                            else
                            {
                                mostrarError(mensaje);
                            }   
                        }
                    }                        
                ]                  
            },
            
        ]
    });
    
    $$("IdCiudad").attachEvent("onChange", function(id, oldv){ 
        Item = $$("flota");
        Item.define("options", "obtenerFlotas/IdCiudad/"+id);        
                      
        Item.refresh(); 
        Item.enable();
    });
    


 

       </script>
       
        
</body>