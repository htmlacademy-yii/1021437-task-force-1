<?php

namespace frontend\assets;

use yii\web\AssetBundle;
class MapAsset extends AssetBundle
{
    public $basePath = '@frontend';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        '//api-maps.yandex.ru/2.1/?apikey=e666f398-c983-4bde-8f14-e3fec900592a&lang=ru_RU',
        'js/ymaps.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
