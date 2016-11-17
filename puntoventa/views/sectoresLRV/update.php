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
            { view:"template", template:"Modificar sector", type:"header" },
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
                    {stringResult:true,value:"<?php echo $nombreSector;?>", id:"nombreSector",invalidMessage: "El nombre de el sector no puede ser vacío",name:"nombreSector",view:"text", label:"Nombre:", width:350,tooltip:"Modifique el nombre de el sector."},                                   
                    {
                        view:"button",  value:"Submit",label:"Modificar Sector",height:50, width:200 , click:function()
                        {
                            var error="";
                            var mensaje="Por favor corrija los siguientes errores:</br><ul>";
                            if($$('nombreSector').getValue()===""||/^\s*$/.test($$('nombreSector').getValue()))
                            {   
                                error+="a";
                                mensaje+="<li>Por favor digite el nombre de el sector.</li>";
                                $$('nombreSector').focus();                                
                            }   
							nombreSector="<?php echo $nombreSector;?>";
							if($$('nombreSector').getValue()===nombreSector)
                            {   
                                error+="a";
                                mensaje+="<li>El nombre del sector no ha cambiado.</li>";
                                $$('nombreSector').focus();                                
                            }
							
                            
                            if(error=="")
                            {
                                var url = $("#base").val() + "/puntoventa/sectoresLRV/modificar";   
								id="<?php echo $id;?>";								
                                url=url+"?id="+id+"&nombreSector="+$$('nombreSector').getValue().toUpperCase();
                                webix.ajax(url,function(text)
                                {
                                    if(text=="c")
                                    {                                                        
                                        
                                        mensaje="Se modificó el sector exitosamente.";
                                        mostrarMensaje(mensaje);                                        
                                    }
                                    else
                                    {
                                        if(text=="e2")
                                        {                                           
                                            mensaje="Ya existe un sector con ese nombre.";
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
                    },{
						  view: "text",
						  template: "" // text content
						}                        
                ]                  
            },
            
        ]
    });
   
    


 

       </script>
       
        
</body>