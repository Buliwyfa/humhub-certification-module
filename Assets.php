<?php

namespace humhub\modules\certified;

use yii\web\AssetBundle;
 
class Assets extends AssetBundle
{
    public $publishOptions = [
        'forceCopy' => true
    ];
    public $css = [
        'certified.css',
    ];
    public function init()
    {
        $this->sourcePath = dirname(__FILE__) . '/assets';
        parent::init();
    }
}
