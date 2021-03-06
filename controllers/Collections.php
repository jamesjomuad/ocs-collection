<?php namespace Ocs\Collection\Controllers;

use BackendMenu;
use October\Rain\Exception\ApplicationException;


class Collections extends \Ocs\Collection\Controllers\Main
{
    public $requiredPermissions = ['ocs.collection.collections'];
    
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = [
        'collections' => '$/ocs/collection/controllers/collections/config_list.yaml',
        'debt' => '$/ocs/collection/controllers/debt/config_list.yaml'
    ];
    public $relationConfig = 'config_relation.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Ocs.Collection', 'collection', 'collections');

        if(request()->header('x-october-request-handler') === 'onDelete' AND !$this->can('ocs.collection.delete'))
        {
            throw new ApplicationException('No Access permission!');
        }
    }

    public function index()
    {
        $this->pageTitle = 'Collections';

        // $this->addJs($this->assetPath . 'js/collection.js');

        $this->asExtension('ListController')->index();
    }

    public function formExtendModel($model)
    {
        if(empty($model->number))
        {
            $model->number = $model->generateNumber();
        }

        return $model;
    }

}