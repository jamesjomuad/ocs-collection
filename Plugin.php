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
                'icon'        => 'icon-leaf',
                'permissions' => ['ocs.collection.*'],
                'order'       => 500,
                'sideMenu' => [
                    'customer' => [
                        'label'       => 'Collections',
                        'url'         => Backend::url('ocs/collection/collections'),
                        'icon'        => 'icon-leaf',
                        'permissions' => ['ocs.collection.*'],
                    ]
                ]
            ],
        ];
    }
}
