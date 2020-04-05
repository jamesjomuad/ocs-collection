<?php namespace Ocs\Collection\ReportWidgets;

use Backend\Classes\ReportWidgetBase;
use Backend\Models\AccessLog;
use Backend\Models\BrandSetting;
use Exception;


class OcsCollectionVolume extends ReportWidgetBase
{
    public $model;

    protected $defaultAlias = 'collection_volume';

    public function defineProperties()
    {
        return [
            'title' => [
                'title'             => 'Collection Volumes',
                'default'           => 'Collection Volumes',
                'type'              => 'string',
                'validationPattern' => '^.+$',
                'validationMessage' => 'Validation Error',
            ]
        ];
    }

    public function render()
    {
        try {
            $this->prepareVars();
        }
        catch (Exception $ex) {
            $this->vars['error'] = $ex->getMessage();
        }

        return $this->makePartial('widget');
    }

    protected function loadAssets()
    {
        $this->addCss('css/collection-volume.css');
    }

    private function prepareVars()
    {
        $debt = new \Ocs\Collection\Models\Debt;

        $this->vars['title'] = $this->properties['title'];
        $this->vars['volume_total'] = $debt->getVolumeTotal();
        $this->vars['payment_total'] = $debt->getVolumeTotal() - $debt->getBalanceTotal();
    }

    public function moneyFormat($value)
    {
        return "₱" . number_format((float)$value, 2, '.', ',');
    }

}