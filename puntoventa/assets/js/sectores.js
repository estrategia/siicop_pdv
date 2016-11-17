function mostrarError(mensaje)
{   
    $("#flotaNoCreada").html(mensaje).show();
    myVar = setTimeout(function(){ ocultarError(); }, 5000);
}

function mostrarMensaje(mensaje)
{   
    $("#flotaCreada").html(mensaje).show();
    myVar = setTimeout(function(){ ocultarMensaje(); }, 5000);
}

function ocultarError()
{     
    $("#flotaNoCreada").hide();  
}

function ocultarMensaje()
{     
    $("#flotaCreada").hide(); 
    $("#mensaje").hide(); 
    
}
function procesarSector(id,cod)
{
    Loading.show();
    
    var url = $("#base").val() + "/puntoventa/sectorCiudadLRV/procesarSector";
    url=url+"?id="+id+"&cod="+cod;  
    window.location=url;
    
    Loading.hide();
}

function validarNumeroPDVS()
{
    if($("#pdvsCargados").val()==0)
    {
        $("#mensaje").html('<div class="alert alert-danger fade in">'+"No hay puntos de venta asignados a la flota: "+$("#flota").val()+"."+'</div>').show();        
    }
}

function verSector(id,cod)
{
    Loading.show();
    
    var url = $("#base").val() + "/puntoventa/sectorCiudadLRV/verSector";
    url=url+"?id="+id+"&cod="+cod;   
    window.location=url;    
    Loading.hide();
}
function actualizarSector(id)
{
    Loading.show();
    
    var url = $("#base").val() + "/puntoventa/sectoresLRV/update";
    url=url+"?id="+id;   
    window.location=url;    
    Loading.hide();
}

function eliminarSectorCiudad()
{
	id=$("#idEliminar").val();
	alert("este es el id="+id);
}


