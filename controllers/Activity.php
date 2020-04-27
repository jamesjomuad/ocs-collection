<?php namespace Ocs\Collection\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use \Ocs\Users\Models\User as UserModel;

/**
 * Activity Back-end Controller
 */
class Activity extends Controller
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

        BackendMenu::setContext('Ocs.Collection', 'collection', 'activity');
    }

    public function listExtendQuery($query)
    {
        $query->collector();

        return $query;
    }

    public function formBeforeSave($model)
    {
        $model->user = UserModel::find(input('User.id'));

        return $model;
    }

}