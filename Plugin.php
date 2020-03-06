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
        return [
            'ocs.collection.collection' => [
                'tab' => 'Collections',
                'label' => 'Manage Collections'
            ],
            'ocs.collection.collection.delete' => [
                'tab' => 'Collections',
                'label' => 'Delete Collections'
            ],
            'ocs.collection.clients' => [
                'tab' => 'Collections',
                'label' => 'Manage Client'
            ],
            'ocs.collection.reports' => [
                'tab' => 'Collections',
                'label' => 'Manage Report'
            ],
            'ocs.collection.payments' => [
                'tab' => 'Collections',
                'label' => 'Manage Payments'
            ],
            'ocs.collection.activity' => [
                'tab' => 'Collections',
                'label' => 'Manage Activities'
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
                    'client' => [
                        'label'       => 'Clients',
                        'url'         => Backend::url('ocs/collection/client'),
                        'icon'        => 'icon-user-circle-o',
                        'permissions' => ['ocs.collection.clients'],
                    ],
                    'reports' => [
                        'label'       => 'Reports',
                        'url'         => Backend::url('ocs/collection/reports'),
                        'icon'        => 'icon-bar-chart',
                        'permissions' => ['ocs.collection.*'],
                    ],
                    'payments' => [
                        'label'       => 'Payments',
                        'url'         => Backend::url('ocs/collection/payments'),
                        'icon'        => 'icon-dollar',
                        'permissions' => ['ocs.collection.*'],
                    ],
                    'activity' => [
                        'label'       => 'Activity',
                        'url'         => Backend::url('ocs/collection/activity'),
                        'icon'        => 'icon-vcard',
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
            'tag'   => [$this, 'tagListColumn'],
            'currency' => [$this, 'currencyListColumn'],
            'url'   => [$this, 'urlListColumn'],
            'ol'    => [$this, 'olListColumn'],
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

    public function olListColumn($value, $column, $record)
    {
        if(is_array($value))
        {
            $htm = implode(' ',
                array_map(function($item){
                    return implode("",[
                        '<li>',
                        $item,
                        '</li>'
                    ]);
                },$value)
            );
            $htm = "<ol>".$htm."</ol>";
            return $htm;
        }
    }
}
