<?php

class CiudadController extends Controller {

    /**
     * Consulta cedula de usuario.
     */
    public function actionAjax() {
        $term = null;

        if (Yii::app()->request->isPostRequest)
            $term = $_POST['term'];
        else
            $term = $_GET['term'];

        $results = array();

        if ($term !== null && trim($term) != '') {
            $term = trim($term);

            $criteria = new CDbCriteria;
            $criteria->order = 't.NombreCiudad';
            $criteria->condition = 't.NombreCiudad LIKE :term';
            $criteria->params = array('term' => "%$term%");
            $criteria->limit = 20;

            $models = Ciudad::model()->findAll($criteria);

            foreach ($models as $model) {
                $results[] = array(
                    'label' => $model->NombreCiudad,
                    'value' => $model->CodCiudad,
                );
            }
        }

        echo CJSON::encode($results);
        Yii::app()->end();
    }
}
