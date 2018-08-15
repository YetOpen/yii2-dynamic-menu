<?php

namespace esempla\dynamicmenu;

use yii\web\AssetBundle;
use Yii;

/**
 * Dynamic Menu backend application asset bundle.
 */
class DynamicMenuAsset extends AssetBundle
{
    public $sourcePath = '@vendor/esempla/yii2-dynamic-menu/src/assets/sidebar-editor/assets';

    public $css = [
        'bs-iconpicker/css/bootstrap-iconpicker.min.css',
        'css/bootstrap-toggle.min.css',
    ];

    public $js = [

        'jquery-menu-editor.js',
        'bs-iconpicker/js/iconset/iconset-fontawesome-4.7.0.min.js',
        'bs-iconpicker/js/bootstrap-iconpicker.js',
        'custom.js',
    ];

    public $depends = [
        'yii\jui\JuiAsset',
    ];
}
