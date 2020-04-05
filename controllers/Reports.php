<?php namespace Ocs\Collection\Controllers;

use BackendMenu;
use \Carbon\Carbon;
use Ocs\Collection\Models\Collection;
use Ocs\Collection\Models\Debt;


class Reports extends \Ocs\Collection\Controllers\Main
{
    public $debt;

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Ocs.Collection', 'collection', 'reports');

        $this->debt = new Debt;
    }

    public function index()
    {
        $this->pageTitle = 'Reports';

        $this->addCss($this->assetPath.'/css/tailwind.min.css');

        $this->vars['debt_volume_total'] = $this->debt->getVolumeTotal();

        $this->vars['debt_balance_total'] = $this->debt->getBalanceTotal();

        $this->vars['collectionCount'] = Collection::all();

        $this->vars['collectionToday'] = Collection::whereDate('created_at', Carbon::today())->get();

        $this->vars['collectionYesterday'] = Collection::whereDate('created_at', Carbon::yesterday())->get();
    }

}