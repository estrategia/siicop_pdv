<?php foreach (Yii::app()->user->getFlashes() as $key => $message): ?>
    <div class="alert alert-dismissable alert-<?php echo $key ?>">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <?php echo $message ?>
    </div>
<?php endforeach; ?>

<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Asignar Puntos de Venta</h3>
    </div>
    <div class="panel-body">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'enableClientValidation' => true,
            'enableAjaxValidation' => false,
            'htmlOptions' => array(
                'class' => 'form-horizontal',
                'role' => 'form',
                'id' => 'form-horario-especial'
            ),
            'errorMessageCssClass' => 'has-error',
            'clientOptions' => array(
                'validateOnSubmit' => true,
                'errorCssClass' => 'has-error',
                'successCssClass' => 'has-success',
                'inputContainer' => '.form-group',
                'validateOnChange' => true
            ))
        );
        ?>
        <?php echo $form->hiddenField($model, 'paso'); ?>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'IDZona', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php echo $form->dropDownList($model, 'IDZona', CHtml::listData($model->getZonas(), 'IDZona', 'NombreZona'), array('class' => 'form-control input-sm')); ?>
                <?php echo $form->error($model, 'IDZona', array('class' => 'text-left text-danger')); ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'CodigoCiudad', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php //echo $form->textField($model, 'CodigoCiudad', array('class' => 'form-control input-sm')); ?>
                <?php
                $this->widget('bootstrap.widgets.TbTypeAhead', array(
                    'model' => $model,
                    'attribute' => 'CodigoCiudad',
                    'minLength' => 2,
                    'source' => new CJavaScriptExpression("
                                function (query, process) {
                                    $.ajax({
                                        type: 'POST',
                                        dataType : 'json',
                                        url: '" . $this->createUrl("/puntoventa/ciudad/ajax") . "',
                                        data: {term: query},
                                        success: function(data){
                                            items = [];
                                            map = {};
                                            $.each(data, function (i, item) {
                                                map[item.label] = item;
                                                items.push(item.label);
                                            });

                                            process(items);
                                        }
                                    })
                                }"),
                    'updater' => new CJavaScriptExpression("
                                function (item) {
                                    return map[item].value;
                                }
                            "),
                    'htmlOptions' => array(
                        'prepend' => TbHtml::icon(null),
                        'autocomplete' => 'off',
                        'placeholder' => 'Digite nombre ciudad',
                        'class' => 'form-control input-sm',
                    ),
                ));
                ?>


                <?php echo $form->error($model, 'CodigoCiudad', array('class' => 'text-left text-danger')); ?>
            </div>
        </div>


        <div class="form-group">
            <?php echo $form->labelEx($model, 'IDComercial', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php echo $form->textField($model, 'IDComercial', array('class' => 'form-control input-sm')); ?>
                <?php echo $form->error($model, 'IDComercial', array('class' => 'text-left text-danger')); ?>

            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'NombrePuntoVenta', array('class' => 'col-lg-4 control-label text-primary')); ?>
            <div class="col-lg-7">
                <?php echo $form->textField($model, 'NombrePuntoVenta', array('class' => 'form-control input-sm')); ?>
                <?php echo $form->error($model, 'NombrePuntoVenta', array('class' => 'text-left text-danger')); ?>

            </div>
        </div>

        <div class="form-group">
            <div class="col-lg-offset-4 col-lg-8">
                <?php echo CHtml::submitButton('Buscar', array('class' => "btn btn-primary")); ?>
            </div>
        </div>

        <?php $this->endWidget(); ?>
    </div>
</div>

<?php if ($model->paso == 2): ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Seleccionar Puntos de Venta</h3>
        </div>
        <div class="panel-body">
            <?php if (empty($model->listPuntoVenta)): ?>
                <p>No se encontraron puntos de venta</p>
            <?php else: ?>
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'enableClientValidation' => true,
                    'enableAjaxValidation' => false,
                    'htmlOptions' => array(
                        'class' => 'form-horizontal',
                        'role' => 'form',
                        'id' => 'form-horario-especial-pdv'
                    ),
                    'errorMessageCssClass' => 'has-error',
                    'clientOptions' => array(
                        'validateOnSubmit' => true,
                        'errorCssClass' => 'has-error',
                        'successCssClass' => 'has-success',
                        'inputContainer' => '.form-group',
                        'validateOnChange' => true
                    ))
                );
                ?>

                <?php echo $form->hiddenField($model, 'IDZona'); ?>
                <?php echo $form->hiddenField($model, 'CodigoCiudad'); ?>
                <?php echo $form->hiddenField($model, 'IDComercial'); ?>
                <?php echo $form->hiddenField($model, 'NombrePuntoVenta'); ?>
                <?php echo $form->hiddenField($model, 'paso'); ?>

                <?php echo $form->error($model, 'listPuntoVentaCheck', array('class' => 'text-left text-danger')); ?>
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center text-primary" style="font-size: 1.1em;">Cod PDV</th>
                            <th class="text-center text-primary" style="font-size: 1.1em;">Nombre PDV</th>
                            <th class="text-center text-primary" style="font-size: 1.1em;">Seleccionar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="background-color: grey">
                            <td class="text-center"><i>Todos</i></td>
                            <td class="text-center"><i>Seleccionar Todos</i></td>
                            <td class="text-center"><input type="checkbox" id="HorarioEspecialForm_listPuntoVentaCheck_todos" name="HorarioEspecialForm[listPuntoVentaCheck][]" value="*"></td>
                        </tr>
                        <?php foreach ($model->listPuntoVenta as $objPdv): ?>
                            <tr>
                                <td class="text-center"><?= $objPdv->IDComercial ?></td>
                                <td><?= $objPdv->NombrePuntoDeVenta ?></td>
                                <td class="text-center"><input type="checkbox" id="HorarioEspecialForm_listPuntoVentaCheck_<?= $objPdv->IDPuntoDeVenta ?>"  name="HorarioEspecialForm[listPuntoVentaCheck][]" value="<?= $objPdv->IDPuntoDeVenta ?>"></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>


                <div class="text-center">
                    <?php echo CHtml::submitButton('Seleccionar', array('class' => "btn btn-primary")); ?>
                </div>

                <?php $this->endWidget(); ?>
            <?php endif; ?>


        </div>
    </div>

    <?php
    Yii::app()->clientScript->registerScript('horario-especial-pdv', "
    $(document).on('change', 'input:checkbox[name=\"HorarioEspecialForm[listPuntoVentaCheck][]\"]', function(e) {
      if($(this).val()=='*'){    
        if($(this).is(':checked')){
          $('input:checkbox[name=\"HorarioEspecialForm[listPuntoVentaCheck][]\"]').prop('checked', true );
        }else{
            $('input:checkbox[name=\"HorarioEspecialForm[listPuntoVentaCheck][]\"]').prop('checked', false );
        }
      }else{
        $('#HorarioEspecialForm_listPuntoVentaCheck_todos').prop('checked', false );
      }

    });
  ");
    ?>

<?php endif; ?>