<?php namespace Ocs\Collection;

use Backend;
use System\Classes\PluginBase;

/**
 * collection Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'collection',
            'description' => 'No description provided yet...',
            'author'      => 'ocs',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {

    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'Ocs\Collection\Components\MyComponent' => 'myComponent',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'ocs.collection.some_permission' => [
                'tab' => 'collection',
                'label' => 'Some permission'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return [
            'collection' => [
                'label'       => 'Collections',
                'url'         => Backend::url('ocs/collection/collections'),
                'icon'        => 'icon-list',
                'permissions' => ['ocs.collection.*'],
                'order'       => 500,
                'sideMenu' => [
                    'collections' => [
                        'label'       => 'Collections',
                        'url'         => Backend::url('ocs/collection/collections'),
                        'icon'        => 'icon-list',
                        'permissions' => ['ocs.collection.*'],
                    ],
                    'activity' => [
                        'label'       => 'Activity',
                        'url'         => Backend::url('ocs/collection/activity'),
                        'icon'        => 'icon-vcard',
                        'permissions' => ['ocs.collection.*'],
                    ],
                    'reports' => [
                        'label'       => 'Reports',
                        'url'         => Backend::url('ocs/collection/reports'),
                        'icon'        => 'icon-bar-chart',
                        'permissions' => ['ocs.collection.*'],
                    ],
                    'payment' => [
                        'label'       => 'Payments',
                        'url'         => Backend::url('ocs/collection/payments'),
                        'icon'        => 'icon-dollar',
                        'permissions' => ['ocs.collection.*'],
                    ],
                    'debt' => [
                        'label'       => 'Debt',
                        'url'         => Backend::url('ocs/collection/debt'),
                        'icon'        => 'icon-snowflake-o',
                        'permissions' => ['ocs.collection.*'],
                    ]
                ]
            ],
        ];
    }

    public function registerListColumnTypes()
    {
        return [
            'tag' => [$this, 'tagListColumn'],
            'currency' => [$this, 'currencyListColumn'],
            'url' => [$this, 'urlListColumn'],
        ];
    }

    public function tagListColumn($value, $column, $record)
    {
        if(is_array($value))
        {
            return implode(' ',
                array_map(function($item){
                    return implode("",[
                        '<button type="button" class="btn btn-secondary btn-xs">',
                        $item,
                        '</button>'
                    ]);
                },$value)
            );
        }
    }

    public function currencyListColumn($value, $column, $record)
    {
        return "₱" . number_format($value, 2, '.', ',');
    }

    public function urlListColumn($value, $column, $record)
    {
        return '<a href="'.$value.'" target="_blank" rel="noopener noreferrer">'.$value.'</a>';
    }
}
