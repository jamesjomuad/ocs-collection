<?php namespace Ocs\Collection\Controllers;

use BackendMenu;


class Collections extends \Ocs\Collection\Controllers\Main
{
    
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_relation.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Ocs.Collection', 'collection', 'collections');
    }

    public function formExtendModel($model)
    {
        if(empty($model->number))
        {
            $model->number = $model->generateNumber();
        }
    }

    public function relationExtendManageWidget($widget, $field, $model)
    {
        // dump(
        //     $widget
        // );
    }

}
