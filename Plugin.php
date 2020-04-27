<?php namespace Ocs\Collection;

use Backend;
use System\Classes\PluginBase;
use \Ocs\Users\Models\User as UserModel;


/**
 * collection Plugin Information File
 */
class Plugin extends PluginBase
{

    public $elevated = true;

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
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        // Avoid run on artisan or cron
        if(!app()->runningInBackend()){
            return;
        }
        
        // Extend user 
        UserModel::extend(function($model){
            # Extend Relations
            $model->hasOne['activity']  = [
                \Ocs\Collection\Models\Activity::class
            ];

            # Extend Mehod
            $model->addDynamicMethod('scopeCollector',function($query) use($model) {
                return $query->whereHas('role',function($q) {
                    $q->where('code','collector');
                });
            });

            return $model;
        });
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
            'ocs.collection.collections' => [
                'tab' => 'Collections',
                'label' => 'Manage Collections',
                'order' => 200,
            ],
            'ocs.collection.create' => [
                'tab' => 'Collections',
                'label' => 'Manage Collections create',
                'order' => 201,
            ],
            'ocs.collection.update' => [
                'tab' => 'Collections',
                'label' => 'Manage Collections update',
                'order' => 202,
            ],
            'ocs.collection.delete' => [
                'tab' => 'Collections',
                'label' => 'Manage Collections delete',
                'order' => 203,
            ],

            // Clients
            'ocs.collection.clients' => [
                'tab' => 'Collections',
                'label' => 'Manage Clients panel',
                'order' => 211
            ],
            'ocs.collection.clients.create' => [
                'tab' => 'Collections',
                'label' => 'Manage Client Create',
                'order' => 212
            ],
            'ocs.collection.clients.delete' => [
                'tab' => 'Collections',
                'label' => 'Manage Client delete',
                'order' => 213
            ],
            
            // Payments
            'ocs.collection.payments' => [
                'tab' => 'Collections',
                'label' => 'Manage Payments',
                'order' => 221
            ],
            'ocs.collection.payments.create' => [
                'tab' => 'Collections',
                'label' => 'Can create Payments',
                'order' => 222
            ],
            'ocs.collection.payments.update' => [
                'tab' => 'Collections',
                'label' => 'Can update Payments',
                'order' => 223
            ],
            'ocs.collection.payments.delete' => [
                'tab' => 'Collections',
                'label' => 'Can delete Payments',
                'order' => 224
            ],

            // Activity
            'ocs.collection.activity' => [
                'tab' => 'Collections',
                'label' => 'Manage Activities',
                'order' => 231
            ],

            // Reports
            'ocs.collection.reports' => [
                'tab' => 'Collections',
                'label' => 'Manage Report',
                'order' => 232
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
        $navs = [
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
                        'permissions' => ['ocs.collection.collections'],
                    ],
                    'clients' => [
                        'label'       => 'Clients',
                        'url'         => Backend::url('ocs/collection/client'),
                        'icon'        => 'icon-user-circle-o',
                        'permissions' => ['ocs.collection.clients'],
                    ],
                    'payments' => [
                        'label'       => 'Payments',
                        'url'         => Backend::url('ocs/collection/payments'),
                        'icon'        => 'icon-dollar',
                        'permissions' => ['ocs.collection.payments'],
                    ],
                    'activity' => [
                        'label'       => 'Activity',
                        'url'         => Backend::url('ocs/collection/activity'),
                        'icon'        => 'icon-vcard',
                        'permissions' => ['ocs.collection.activity'],
                    ],
                    'reports' => [
                        'label'       => 'Reports',
                        'url'         => Backend::url('ocs/collection/reports'),
                        'icon'        => 'icon-bar-chart',
                        'permissions' => ['ocs.collection.reports'],
                    ],
                ]
            ],
        ];

        // return $navs;

        return $this->setDefaultNav($navs,'ocs.collection');
    }

    public function registerReportWidgets()
    {
        return [
            'Ocs\Collection\ReportWidgets\OcsCollectionVolume' => [
                'label'   => 'OCS Collection Volume',
                'context' => 'dashboard'
            ]
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

    /*
    *   Helpers
    */
    private function setDefaultNav($navs)
    {
        foreach($navs as $k=>$nav){
            foreach($navs[$k]['sideMenu'] as $key=>$val){
                if(\BackendAuth::getUser()->hasPermission('ocs.collection'.'.'.$key)){
                    $navs[$k]['url'] = $val['url'];
                    break;
                }
            }
        }

        return $navs;
    }
}
