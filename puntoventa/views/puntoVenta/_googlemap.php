<?php

Yii::import('ext.EGMap.*');

$gMap = new EGMap();
$gMap->setCenter($model->LatitudGoogle + 0.1, $model->LongitudGoogle - 0.1);
$marker = new EGMapMarker($model->LatitudGoogle, $model->LongitudGoogle);
$info_window = new EGMapInfoWindow($model->DireccionGoogle);
$marker->addHtmlInfoWindow($info_window);
$marker->setAnimation(EGMapMarker::BOUNCE);

/*
  $info_box = new EGMapInfoBox('<div style="color:#000;border: 1px solid #c0c0c0; margin-top: 8px; background: #c0c0c0; padding: 5px;">' .$model->DireccionGoogle . '</div>');
  $marker->addHtmlInfoBox($info_box);
 */

$gMap->addMarker($marker);
$gMap->zoom = 6;
$gMap->zoomControl = false;
$gMap->width = '100%';
//$gMap->height = '400px';
$gMap->renderMap();
?>
