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

        $this->addCss($this->assetPath.'/css/reports.css');

        $this->addJs($this->assetPath.'/js/Chart.min.js');

        $this->addJs($this->assetPath.'/js/reports.js');

        $this->vars['debt'] = $this->Debt;

        $this->vars['payments'] = $this->paymentCount();

        $this->vars['collection'] = [
            'total'     => Collection::all()->count(),
            'today'     => Collection::today()->get()->count(),
            'yesterday' => Collection::today()->get()->count()
        ];
    }

    public function test()
    {
        // foreach(range(1,Carbon::now()->daysInMonth as $days)
        // {
        //     dump($days);
        // }
        dd(
            
            // range(1,Carbon::now()->endOfMonth()->format('d'))
            Carbon::now()->daysInMonth
        );
    }

    #
    #   Ajax Handlers
    #
    public function onGetPaymentCount()
    {
        return $this->paymentCount(input('mode'));
    }

    #
    #   Data Structures
    #
    public function paymentCount($mode='daily')
    {
        $carbon = Carbon::today();

        $data = [];

        switch($mode)
        {
            case 'daily':
                $days = [
                    Carbon::today()->subDays(6)->englishDayOfWeek,
                    Carbon::today()->subDays(5)->englishDayOfWeek,
                    Carbon::today()->subDays(4)->englishDayOfWeek,
                    Carbon::today()->subDays(3)->englishDayOfWeek,
                    Carbon::today()->subDays(2)->englishDayOfWeek,
                    Carbon::today()->subDays(1)->englishDayOfWeek,
                    Carbon::today()->englishDayOfWeek
                ];

                $result = Payment::select('id','amount','created_at')
                ->dateBetween('created_at', Carbon::today()->subWeek(), Carbon::today())
                ->get()
                ->groupBy(function($model) {
                    return Carbon::parse($model->created_at)->englishDayOfWeek;
                })
                ->map(function($item){return $item->count();})
                ->toArray();

                foreach($days as $k=>$day)
                {
                    $data['label'][] = $day;
                    $data['value'][] = $result[$day] ?? 0;
                }

            break;

            case 'weekly':
                
                return [
                    'label' => ['a','b','c','d','e','f','g','h','i'],
                    'value' => [10,20,10,20,10,20,10,20,10]
                ];
            break;

            case 'monthly': 
                return [
                    'label' => ['a','b','c','d','e','f','g','h','i','x','y','z'],
                    'value' => [10,15,20,15,10,15,10,15,20,30,14,50]
                ];
            break;

            default:
                $data = [];
            break;
        }

        return $data;
    }

    #
    #   Helpers
    #
    
}