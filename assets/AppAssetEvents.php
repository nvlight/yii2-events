<?php
/**
 * Created by PhpStorm.
 * User: lght
 * Date: 13.10.2017
 * Time: 19:45
 */

namespace app\assets;

use yii\web\AssetBundle;

class AppAssetEvents extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/style.css',
        'css/fonts.css',
        'css/media.css',
        'css/font-awesome/css/font-awesome.css',
//        'https://fonts.googleapis.com/css?family=Ubuntu:400,500,700|Montserrat:400,700',
    ];
    public $js = [
        //'js/common.js',
        'js/bootstrap-select.min.js',
        //'js/aslan_main.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset', // подлючает и ксс и джс бутстрапа
    ];
//    public $jsOptions = [
//        'condition' => 'lte IE9',
//        'position' => \yii\web\view::POS_HEAD
//    ];
}