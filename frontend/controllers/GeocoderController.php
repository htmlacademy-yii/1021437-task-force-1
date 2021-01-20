<?php


namespace frontend\controllers;

use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

class GeocoderController extends Controller
{
    public function actionCoordinates($query)
    {
        $api_key = Yii::getAlias('@api_yandex');
        $url= urlencode($query);
        $content = file_get_contents("https://geocode-maps.yandex.ru/1.x/?apikey=$api_key&format=json&geocode=$url");

        return $content;
    }
}
