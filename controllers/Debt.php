<?php namespace Ocs\Collection\Controllers;

use BackendMenu;


class Debt extends \Ocs\Collection\Controllers\Main
{
    private $collectionID;

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

    public function index()
    {
        return \Backend::redirect("ocs/collection/collections#debts");
    }

    public function create($collection_id = null)
    {
        $this->pageTitle = 'Create';

        $this->collectionID = $collection_id ? : input('collection');
        
        $this->asExtension('FormController')->create();
    }

    public function create_onSave($context = null)
    { 
        if(input('close') AND input('collection'))
        {
            parent::create_onSave($context);
            return \Backend::redirect("ocs/collection/collections/update/".input('collection'));
        }

        return parent::create_onSave($context);
    }

    public function update($recordId, $context = null)
    {
        $this->pageTitle = 'Edit Debt';

        $this->asExtension('FormController')->update($recordId, $context);
    }

    public function update_onSave($context = null)
    { 
        parent::update_onSave($context);

        if(input('close') AND post('Debt.collection.id'))
        {
            return \Backend::redirect("ocs/collection/collections/update/".post('Debt.collection.id'));
        }
    }

    public function formExtendModel($model)
    {
        if($this->action == 'create')
        {
            $model->collection = \Ocs\Collection\Models\Collection::find($this->collectionID | post('Debt.collection.id'));
            $model->debtor = new \Ocs\Collection\Models\Debtor;
        }

        return $model;
    }

    public function relationExtendManageWidget($widget, $field, $model)
    {
        // Make sure the field is the expected one
        if ($field != 'payments')
            return; 
     
        
        // Remaining Balance: Dynamically add field on Popup relation
        $widget->bindEvent('form.extendFields', function () use($widget,$model) {
            $widget->addFields([
                '_prev_balance' => [
                    'label'     => 'Remaining Balance',
                    'type'      => 'text',
                    'span'      => 'right',
                    'cssClass'  => 'font-1',
                    'readOnly'  => true,
                    'default'   => $model->prev_balance
                ],
            ]);
        });
    }

    public function relationExtendRefreshResults($field)
    {
        // Make sure the field is the expected one
        if ($field != 'payments')
        return;

        return ['#Form-field-Debt-prev_balance-group' => '<label for="Form-field-Debt-prev_balance">Remaining Balance</label><input type="text" name="Debt[prev_balance]" id="Form-field-Debt-prev_balance" value="'.$this->vars['formModel']->prev_balance.'" class="form-control" autocomplete="off" maxlength="255" disabled="disabled"/>'];
    }

}