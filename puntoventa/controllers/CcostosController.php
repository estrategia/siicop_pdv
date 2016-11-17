<?php

class CcostosController extends Controller {

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
            $criteria->order = 't.NombreCentroCostos';
            $criteria->condition = 't.NombreCentroCostos LIKE :term';
            $criteria->params = array('term' => "%$term%");
            $criteria->limit = 20;

            $models = CentroCostos::model()->findAll($criteria);

            foreach ($models as $model) {
                $results[] = array(
                    'label' => $model->NombreCentroCostos,
                    'value' => $model->IdCentroCostos,
                );
            }
        }

        echo CJSON::encode($results);
        Yii::app()->end();
    }
}
