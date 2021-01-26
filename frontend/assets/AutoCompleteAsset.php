<?php

namespace frontend\assets;
use yii\web\AssetBundle;

class AutoCompleteAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '//cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@8.2.1/dist/css/autoComplete.min.css',
        'css/completeinput.css',
    ];

    public $js = [
        '//cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@8.2.1/dist/js/autoComplete.min.js',
        'js/initAutoComplete.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}
