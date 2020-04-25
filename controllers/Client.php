<?php namespace Ocs\Collection\Controllers;

use BackendMenu;


class Client extends \Ocs\Collection\Controllers\Main
{
    public $requiredPermissions = ['ocs.collection.clients'];

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = [
        'clients' => '$/ocs/collection/controllers/client/config_list.yaml',
        'debtors' => '$/ocs/collection/controllers/debtor/config_list.yaml'
    ];
    public $relationConfig = 'config_relation.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Ocs.Collection', 'collection', 'client');
    }
}
