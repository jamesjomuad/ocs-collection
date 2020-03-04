<?php namespace Ocs\Collection\Controllers;

use BackendMenu;


class Payments extends \Ocs\Collection\Controllers\Main
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = [
        'payments' => 'config_payment_list.yaml',
        'bills' => 'config_bills_list.yaml'
    ];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Ocs.Collection', 'collection', 'payments');
    }
}
