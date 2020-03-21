<?php namespace Ocs\Collection\Controllers;

use BackendMenu;
use October\Rain\Exception\ApplicationException;


class Collections extends \Ocs\Collection\Controllers\Main
{
    public $requiredPermissions = ['ocs.collection.collection'];
    
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

        if(request()->header('x-october-request-handler') === 'onDelete' AND !$this->canDelete())
        {
            throw new ApplicationException('No Access permission!');
        }

        $this->addJs($this->assetPath . "js/collection.js");
    }

    public function test($id)
    {
        parent::update($id);

        $collection = $this->vars['formModel'];

        dd(
            // $collection->debt->pluck('isFullPaid')
            $collection->isPaid
        );
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