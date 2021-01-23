<?php


namespace frontend\controllers;

use yii\web\Controller;

class GeocoderController extends Controller
{
    public function actionCoordinates($query)
    {
        $geocoder = new GeocoderService();
        $data = $geocoder->getInfoFromQuery($query);
        return $this->asJson($data);
    }
}
