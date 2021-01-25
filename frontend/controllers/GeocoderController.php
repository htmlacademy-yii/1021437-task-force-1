<?php


namespace frontend\controllers;

use yii\web\Controller;

class GeocoderController extends Controller
{
    public function actionCoordinates(string $query): ?object
    {
        $geocoder = new GeocoderService();
        $data = $geocoder->getInfoFromQuery($query);
        return $this->asJson($data);
    }
}
