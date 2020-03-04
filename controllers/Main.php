<?php namespace Ocs\Collection\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

class Main extends Controller
{
    public $assetPath = "/plugins/ocs/collection/assets/";

    public function __construct()
    {
        parent::__construct();

        $this->addCss($this->assetPath . "css/main.css");
    }

    public function canCreate()
    {
        return true;
    }
    
    public function canRead()
    {
        return true;
    }

    public function canUpdate()
    {
        return true;
    }

    public function canDelete()
    {
        return $this->user->hasAccess('ocs.collection.collection.delete');
    }
    
}
