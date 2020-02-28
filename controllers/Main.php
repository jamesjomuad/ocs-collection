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
}
