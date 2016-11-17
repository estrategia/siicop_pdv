<?php
Yii::app()->clientScript->registerScript('addSolicitudItem', "
$(document).on('click', 'button[data-role=\"btnaddsolicituditem\"]', function() {
    var element = $(this);
    $.ajax({
        type: 'POST',
        dataType : 'json',
        url: '" . Yii::app()->createUrl('/puntoventa/solicitudActivos/item') . "',
        data: {nitem: $('#input-nitem').val()},
        beforeSend: function(){ Loading.show(); $('button[data-role=\"btnaddsolicituditem\"]').attr('disabled','disabled');},
        success: function(data){
            if(data.result=='ok'){
                $('#div-solicitud-item').append(data.response.itemHTML);
                $('#input-nitem').val(data.response.nItem);
            }else{
                bootbox.alert(data.response);
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            element.val(element.attr('data-estado'));
            bootbox.alert('Error: ' + errorThrown);
        },
        complete: function(){Loading.hide();$('button[data-role=\"btnaddsolicituditem\"]').removeAttr('disabled');},
    });
});
", CClientScript::POS_END);
?>

<?php
$this->pageTitle = Yii::app()->name . ' - Puntos de Venta - Activos';

$this->breadcrumbs = array(
    'Inicio' => array('/'),
    'PDV - Solicitud Activos' => array('/puntoventa/solicitudActivos/'),
    'PDV - Solicitar Activos',
);
?>

<?php echo CHtml::beginForm('', 'post', array('id'=>'form-solicitar', 'class' => 'form-inline')); ?>
<?php echo CHtml::hiddenField('input-nitem', $n, array('id' => 'input-nitem')); ?>
<div id='div-solicitud-item'>
    <?php $this->renderPartial('_item', array('listData' => $listData, 'n' => $n)); ?>
</div>
<div style="display: block;clear: both; margin-top: 10px"></div>
<button data-role="btnaddsolicituditem" class="btn btn-default" type="button">+</button>
<?php
echo CHtml::ajaxSubmitButton('Solicitar', Yii::app()->createUrl('/puntoventa/solicitudActivos/solicitar'), array(
    'beforeSend' => new CJavaScriptExpression("function(){Loading.show(); $('#form-solicitar input[type=submit]').attr('disabled','disabled'); $('div[data-role=\"inputformerror\"]').html(''); $('div[data-role=\"inputformerror\"]').css('display','none');  }"),
    'complete' => new CJavaScriptExpression("function(){ $('#form-solicitar input[type=submit]').removeAttr('disabled');}"),
    'success' => new CJavaScriptExpression("function(data){
        obj = $.parseJSON(data);
        if(obj.result=='ok'){
            $('#div-solicitud-item').html(obj.response.itemHTML);
            $('#input-nitem').val(obj.response.nItem);
            bootbox.alert(obj.response.msg);
        }else if(obj.result=='error'){
            bootbox.alert(obj.response);
        }else{
            $.each(obj,function(idx,value){
                $.each($.parseJSON(value.errors),function(element,error){
                    $('#'+element+'_em_'+value.item).html(error);
                    $('#'+element+'_em_'+value.item).css('display','block');
                });
            });
        }
        Loading.hide();
    }"),
    'error' => new CJavaScriptExpression("function(jqXHR, textStatus, errorThrown){
        Loading.hide();
        bootbox.alert('Error: ' + errorThrown);
    }")), array('id' => uniqid(),'class' => 'btn btn-primary'));
?>

<?php echo CHtml::endForm(); ?>