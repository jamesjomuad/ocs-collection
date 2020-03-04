<?php namespace Ocs\Collection\Controllers;

use BackendMenu;
use Ocs\Collection\Models\Collection;
use \Carbon\Carbon;

/**
 * Reports Back-end Controller
 */
class Reports extends \Ocs\Collection\Controllers\Main
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

        BackendMenu::setContext('Ocs.Collection', 'collection', 'reports');
    }

    public function index()
    {
        $this->pageTitle = 'Reports';

        $this->vars['collectionCount'] = Collection::all();

        $this->vars['collectionToday'] = Collection::whereDate('created_at', Carbon::today())->get();

        $this->vars['collectionYesterday'] = Collection::whereDate('created_at', Carbon::yesterday())->get();
    }

}
