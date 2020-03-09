<?php namespace Ocs\Collection\Controllers;

use BackendMenu;


class Payments extends \Ocs\Collection\Controllers\Main
{
    public $parentId = null;

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = [
        'payments' => 'config_payment_list.yaml',
    ];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Ocs.Collection', 'collection', 'payments');
    }

    public function update($id, $parent=null, $parent_id=null)
    {
        $this->pageTitle = 'Payment';

        if($parent)
        {
            $this->parentId = $parent_id;
        }
        
        $this->asExtension('FormController')->update($id);
    }

    public function formExtendModel($model)
    {
        if($this->parentId)
        {
            $model->debt = \Ocs\Collection\Models\Debt::find($this->parentId);
            $model->debtor = $model->debt->debtor;
        }

        return $model;
    }

}
