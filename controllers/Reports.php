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
        dd(
            $this->paymentAmount('monthly')
        );
    }

    #
    #   Ajax Handlers
    #
    public function onGetPaymentCount()
    {
        return $this->paymentCount(input('mode'));
    }

    public function onGetPaymentAmount()
    {
        return $this->paymentAmount(input('mode'));
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

                return $data;
            break;

            case 'weekly':
                $results = Payment::select('id','amount','created_at')
                ->whereDate('created_at', '>', Carbon::today()->subDays(30))
                ->get()
                ->groupBy(function($date) { return Carbon::parse($date->created_at)->format('W'); })
                ->map(function($item){return $item->count();})
                ->toArray();

                ksort($results);

                foreach($results as $k=>$week)
                {
                    $data['label'][] = "Week {$k}";
                    $data['value'][] = $week;
                }

                return $data;
            break;

            case 'monthly':
                $results = Payment::select('id','amount','created_at')
                ->whereDate('created_at', '>', Carbon::today()->subMonths(12))
                ->get()
                ->groupBy(function($date) { return Carbon::parse($date->created_at)->format('m-M'); })
                ->map(function($item){return $item->count();})
                ->reverse()
                ->toArray();

                foreach($results as $k=>$month)
                {
                    $data['label'][] = explode('-',$k)[1];
                    $data['value'][] = $month;
                }

                return $data;
            break;

            default:
                $data = [];
            break;
        }
    }

    public function paymentAmount($mode='daily')
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
                ->map(function($item){return $item->sum('amount');})
                ->toArray();

                foreach($days as $k=>$day)
                {
                    $data['label'][] = $day;
                    $data['value'][] = $result[$day] ?? 0;
                }

                return $data;
            break;

            case 'weekly':
                $results = Payment::select('id','amount','created_at')
                ->whereDate('created_at', '>', Carbon::today()->subDays(30))
                ->get()
                ->groupBy(function($date) { return Carbon::parse($date->created_at)->format('W'); })
                ->map(function($item){return $item->sum('amount');})
                ->toArray();

                ksort($results);

                foreach($results as $k=>$week)
                {
                    $data['label'][] = "Week {$k}";
                    $data['value'][] = $week;
                }

                return $data;
            break;

            case 'monthly':
                $results = Payment::select('id','amount','created_at')
                ->whereDate('created_at', '>', Carbon::today()->subMonths(12))
                ->get()
                ->groupBy(function($date) { return Carbon::parse($date->created_at)->format('m-M'); })
                ->map(function($item){return $item->sum('amount');})
                ->reverse()
                ->toArray();

                foreach($results as $k=>$month)
                {
                    $data['label'][] = explode('-',$k)[1];
                    $data['value'][] = $month;
                }

                return $data;
            break;

            default:
                $data = [];
            break;
        }
    }

    #
    #   Helpers
    #
    
}