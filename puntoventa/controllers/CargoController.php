<?php

class CargoController extends Controller {

    /**
     * Consulta id cargo.
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
            $criteria->order = 't.NombreCargo';
            $criteria->condition = 't.NombreCargo LIKE :term';
            $criteria->params = array('term' => "%$term%");
            $criteria->limit = 20;

            $models = Cargo::model()->findAll($criteria);

            foreach ($models as $model) {
                $results[] = array(
                    'label' => $model->NombreCargo,
                    'value' => $model->IdCargo,
                );
            }
        }

        echo CJSON::encode($results);
        Yii::app()->end();
    }
}
