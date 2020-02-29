<?php namespace Ocs\Collection\Models;

use Model;

/**
 * Collection Model
 */
class Collection extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'ocs_collections';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [];

    /**
     * @var array Attributes to be cast to JSON
     */
    protected $jsonable = [];

    /**
     * @var array Attributes to be appended to the API representation of the model (ex. toArray())
     */
    protected $appends = [];

    /**
     * @var array Attributes to be removed from the API representation of the model (ex. toArray())
     */
    protected $hidden = [];

    /**
     * @var array Attributes to be cast to Argon (Carbon) instances
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [
        'client' => [
            \Ocs\Collection\Models\Client::class
        ]
    ];
    public $hasMany = [
        'clienteles' => [
            \Ocs\Collection\Models\Clientele::class,
            'key' => 'client_id'
        ],
    ];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];


    public function getClienteleListAttribute($value) : STRING
    {
        if($this->clienteles)
        {
           return $this->clienteles->implode('name', ', '); 
        }
    }

    /*
    *   Generate number during create form
    */
    public function generateNumber() : STRING
    {
        $date = new \Carbon\Carbon;

        if($this->all()->last()===null)
        {
            return "DC" . $date->format("Y-md-") . "0001";
        }
        else
        {
            return "DC" . $date->format("Y-md-") . str_pad($this->max('id') + 1, 5, '0', STR_PAD_LEFT);
        }
        
    }

}
