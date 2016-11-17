<?php

class UsuarioController extends Controller {

    /**
     * Consulta cedula de usuario.
     */
    public function actionAjax() {
        $term = null;
        $activo = false;

        if (Yii::app()->request->isPostRequest) {
            $term = $_POST['term'];
            $activo = isset($_POST['activo']) ? $_POST['activo'] : false;
        } else {
            $term = $_GET['term'];
            $activo = isset($_GET['activo']) ? $_GET['activo'] : false;
        }

        $results = array();

        if ($term !== null && trim($term) != '') {
            $term = trim($term);

            $criteria = new CDbCriteria;

            if ($activo)
                $criteria->join = 'INNER JOIN m_Empleado as empleado ON (empleado.NumeroDocumento = t.NumeroDocumento) INNER JOIN m_EstadoEmpleado as estado ON (estado.idEstado = empleado.idEstado)';

            $criteria->order = 't.ApellidosNombres';

            if ($activo)
                $criteria->condition = 'estado.Estado=:estado AND t.ApellidosNombres LIKE :term';
            else
                $criteria->condition = 't.ApellidosNombres LIKE :term';

            if ($activo)
                $criteria->params = array('estado' => Yii::app()->controller->module->asocActivo, 'term' => "%$term%");
            else
                $criteria->params = array('term' => "%$term%");

            $criteria->limit = 20;

            $models = Persona::model()->findAll($criteria);

            foreach ($models as $model) {
                $results[] = array(
                    'label' => $model->ApellidosNombres,
                    'value' => $model->NumeroDocumento,
                );
            }
        }

        echo CJSON::encode($results);
        Yii::app()->end();
    }

}
