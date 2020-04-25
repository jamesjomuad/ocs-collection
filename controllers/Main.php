<?php namespace Ocs\Collection\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

class Main extends Controller
{
    public $parentId = null;

    public $formRenderFieldResult = [];

    public $assetPath = "/plugins/ocs/collection/assets/";

    public function __construct()
    {
        parent::__construct();

        $this->addCss($this->assetPath . "css/main.css");
        $this->addJs($this->assetPath . "js/main.js");
    }

    public function can($perm)
    {
        return $this->user->hasAccess($perm);
    }

    public function getReferer($name = null)
    {
        $referer = request()->header('referer');

        $parsed = collect(explode('/',$referer))->reverse()->values()->take(5);

        $array = [
            'id' => $parsed[0],
            'action' => $parsed[1],
            'plugin' => $parsed[2],
            'author' => $parsed[3],
        ];

        if($name)
        {
            return $array[$name];
        }

        return $array;
    }

    public function _renderField(String $name)
    {
        $className = substr(strrchr(__CLASS__, "\\"), 1);
        $this->formRenderFieldResult['#Form-field-Debt-'.$name.'-group'] = $this->formRenderField(
            $name, 
            ['useContainer' => false]
        );
        
        return $this->formRenderFieldResult;
    }

    // public function onRelationManagePivotUpdate() {}
    // public function relationExtendViewWidget() {}
    // public function relationExtendManageWidget() {}
    // public function relationExtendPivotWidget() {}
    // public function relationExtendRefreshResults() {}
    
}
