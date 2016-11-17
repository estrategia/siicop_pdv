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
                    {stringResult:true, id:"nombreSector",invalidMessage: "El nombre de el sector no puede ser vacío",name:"nombreSector",view:"text", label:"Nombre:", width:350,tooltip:"Escriba el nombre de el sector."},                                   
                    {
                        view:"button",  value: "Submit",label:"Crear Sector",height:50, width:100 , click:function()
                        {
                            var error="";
                            var mensaje="Por favor corrija los siguientes errores:</br><ul>";
                            if($$('nombreSector').getValue()===""||/^\s*$/.test($$('nombreSector').getValue()))
                            {   
                                error+="a";
                                mensaje+="<li>Por favor digite el nombre de el sector.</li>";
                                $$('nombreSector').focus();                                
                            }            
                            
                            if(error=="")
                            {
                                var url = $("#base").val() + "/puntoventa/sectoresLRV/crearSector";                                  
                                url=url+"?nombreSector="+$$('nombreSector').getValue().toUpperCase();
                                webix.ajax(url,function(text)
                                {
                                    if(text=="c")
                                    {
                                        $$('nombreSector').setValue("");                                       
                                        
                                        mensaje="Se creó el sector exitosamente.";
                                        mostrarMensaje(mensaje);                                        
                                    }
                                    else
                                    {
                                        if(text=="e2")
                                        {
                                            error+="d";
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