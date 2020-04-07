<?php namespace Ocs\Collection\Controllers;

use BackendMenu;
use \Carbon\Carbon;
use \Carbon\CarbonImmutable;
use Ocs\Collection\Models\Collection;
use Ocs\Collection\Models\Debt;
use Ocs\Collection\Models\Payment;


class Reports extends \Ocs\Collection\Controllers\Main
{
    public $collection;
    public $Debt;
    public $Payment;

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Ocs.Collection', 'collection', 'reports');

        $this->prepareModels();
    }

    public function prepareModels()
    {
        $this->Debt = new Debt;

        $this->Payment = new Payment;

        return $this;
    }

    public function index()
    {
        $this->pageTitle = 'Reports';

        $this->addCss($this->assetPath.'/css/tailwind.min.css');

        $this->vars['debt'] = $this->Debt;

        $this->vars['payments'] = $this->paymentChart();

        $this->vars['collection'] = [
            'total'     => Collection::all()->count(),
            'today'     => Collection::today()->get()->count(),
            'yesterday' => Collection::today()->get()->count()
        ];

        // dd(
        //     $this->paymentChart()
        // );

    }

    public function paymentChart()
    {
        $start_time = microtime(true); 

        $weekNames = [
            0 => 'SUN',
            1 => 'MON',
            2 => 'TUE',
            3 => 'WED',
            4 => 'THU',
            5 => 'FRI',
            6 => 'SAT',
        ];

        $data = [];

        foreach($weekNames as $k=>$week)
        {
            $carbon = Carbon::today();

            if($k==0)
            {
                $data[] = [
                    'date' => $weekNames[$carbon->dayOfWeek],
                    'value' => $this->Payment->whereDate('created_at', $carbon)->get()->count()
                ];
            }
            else
            {
                $data[] = [
                    'date' => $weekNames[$carbon->subDays($k)->dayOfWeek],
                    'value' => $this->Payment->whereDate('created_at', $carbon->subDays($k))->get()->count()
                ];
            }
        }

        return array_reverse($data);
    }

}