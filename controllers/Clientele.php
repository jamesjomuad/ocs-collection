<?php namespace Ocs\Collection\Controllers;

use BackendMenu;

/**
 * Clientele Back-end Controller
 */
class Clientele extends \Ocs\Collection\Controllers\Main
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Ocs.Collection', 'collection', 'clientele');
    }
}
