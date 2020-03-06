<?php namespace Ocs\Collection\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Debtor Back-end Controller
 */
class Debtor extends Controller
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

        BackendMenu::setContext('Ocs.Collection', 'collection', 'debtor');
    }
}
