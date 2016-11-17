/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
    $('a[rel="tooltip"]').tooltip();
    $('a[rel="popover"]').popover();
});

$(document).on('hidden.bs.modal', '#categoria-form-modal', function() {
    $('#categoria-form-modal .modal-body').html("");
    $('#categoria-form-modal .modal-header h4').html("");
});

$(document).on('hidden.bs.modal', '#servicio-form-modal', function() {
    $('#servicio-form-modal .modal-body').html("");
    $('#servicio-form-modal .modal-header h4').html("");
});

$(document).on('hidden.bs.modal', '#cedi-form-modal', function() {
    $('#cedi-form-modal .modal-body').html("");
    $('#cedi-form-modal .modal-header h4').html("");
});

$(document).on('hidden.bs.modal', '#sector-form-modal', function() {
    $('#sector-form-modal .modal-body').html("");
    $('#sector-form-modal .modal-header h4').html("");
});

$(document).on('hidden.bs.modal', '#negocio-form-modal', function() {
    $('#negocio-form-modal .modal-body').html("");
    $('#negocio-form-modal .modal-header h4').html("");
});

$(document).on('hidden.bs.modal', '#sede-form-modal', function() {
    $('#sede-form-modal .modal-body').html("");
    $('#sede-form-modal .modal-header h4').html("");
});

$(document).on('hidden.bs.modal', '#zona-form-modal', function() {
    $('#zona-form-modal .modal-body').html("");
    $('#zona-form-modal .modal-header h4').html("");
});

$(document).on('hidden.bs.modal', '#telefono-form-modal', function() {
    $('#telefono-form-modal .modal-body').html("");
    $('#telefono-form-modal .modal-header h4').html("");
});

$(document).on('hidden.bs.modal', '#historial-form-modal', function() {
    $('#historial-form-modal .modal-body').html("");
    $('#historial-form-modal .modal-header h4').html("");
});

$(document).on('hidden.bs.modal', '#horarios-form-modal', function() {
    $('#horarios-form-modal .modal-body').html("");
    $('#horarios-form-modal .modal-header h4').html("");
});

$(document).on('hidden.bs.modal', '#registro-form-modal', function() {
    $('#registro-form-modal .modal-body').html("");
});

$(document).on('hidden.bs.modal', '#ubicacion-form-modal', function() {
    $('#ubicacion-form-modal .modal-body').html("");
    $('#ubicacion-form-modal .modal-header h4').html("");
});

$(document).on('hidden.bs.modal', '#barrio-form-modal', function() {
    $('#barrio-form-modal .modal-body').html("");
    $('#barrio-form-modal .modal-header h4').html("");
});

$(document).on('hidden.bs.modal', '#competencia-form-modal', function() {
    $('#competencia-form-modal .modal-body').html("");
    $('#competencia-form-modal .modal-header h4').html("");
});

$(document).on('hidden.bs.modal', '#influencia-form-modal', function() {
    $('#influencia-form-modal .modal-body').html("");
    $('#influencia-form-modal .modal-header h4').html("");
});

$(document).on('hidden.bs.modal', '#competencia-form-modal', function() {
    $('#competencia-form-modal .modal-body').html("");
    $('#competencia-form-modal .modal-header h4').html("");
});

pvFlagCambioTabs = false;
pvFlagCrear = false;

$(document).on('change', '#puntoventa-basica-form, #puntoventa-contacto-form, #puntoventa-otros-form input,select[id^=\'PuntoVenta_\']', function(e) {
    pvFlagCambioTabs = true;
});

$("#puntoventa_tabs").on("tabsbeforeactivate", function(event, ui) {
    if (pvFlagCrear) {
        event.preventDefault();
        $("#puntoventa_tabs").tabs({active: 0});
        return false;
    } else {
        var pvTabFin = ui.newTab.index();
        var pvTabInicio = ui.oldTab.index();
        if (pvTabInicio !== pvTabFin) {
            if (pvFlagCambioTabs) {
                if (confirm('Tiene cambios sin guardar. ¿Desea continuar?')) {
                    pvFlagCambioTabs = false;
                } else {
                    event.preventDefault();
                    $("#puntoventa_tabs").tabs({active: pvTabInicio});
                    return false;
                }
            }
        }
    }
});
