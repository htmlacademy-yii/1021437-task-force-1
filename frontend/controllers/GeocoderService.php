<?php

namespace frontend\controllers;

use Yii;

class GeocoderService
{

    private function getValueForQuery(string $query): string
    {
        return urlencode($query);
    }

    public function getCityFromProfile(): string
    {
        return Yii::$app->user->getIdentity()->city->city;
    }

    public function getInfoFromQuery(string $query): array
    {
        $apiKey = Yii::getAlias('@api_yandex');

        $userCity = $this->getCityFromProfile();
        $url = $this->getValueForQuery($query);

        $content = file_get_contents("https://geocode-maps.yandex.ru/1.x/?apikey=$apiKey&format=json&geocode=$url");
        $data = json_decode($content, true);

        $cityList = [];

        foreach ($data["response"]['GeoObjectCollection']['featureMember'] as $key => $item) {
            if (isset($item['GeoObject']['metaDataProperty']['GeocoderMetaData']['Address']['Components'][4]['kind'])
                && $item['GeoObject']['metaDataProperty']['GeocoderMetaData']['Address']['Components'][4]['kind'] === 'locality'
            && $item['GeoObject']['metaDataProperty']['GeocoderMetaData']['Address']['Components'][4]["name"] === $userCity) {
                $coordinates = explode(" ", $item['GeoObject']['Point']['pos']);
                array_push($cityList, [
                    'text' => $item['GeoObject']['metaDataProperty']['GeocoderMetaData']['text'],
                    'latitude' => $coordinates[1],
                    'longitude' => $coordinates[0],
                    'city' => Yii::$app->user->identity->city_id
                ]);
            }
        }
        return $cityList;
    }
}
