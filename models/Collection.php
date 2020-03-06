<?php namespace Ocs\Collection\Models;

use Model;

/**
 * Collection Model
 */
class Collection extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'ocs_collection';

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
    public $hasOne = [];
    public $hasMany = [
        'debt' => [
            \Ocs\Collection\Models\Debt::class,
            'delete' => true
        ]
    ];
    public $belongsTo = [
        'client' => [
            \Ocs\Collection\Models\Client::class,
        ]
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public function getDebtorsAttribute($value)
    {
        if($this->debt->isNotEmpty())
        {
            $mapped = $this->debt->take(4)->map(function($item, $key){
                return $item->debtor->name;
            });
            
            if($this->debt->count()>4)
            {
                $mapped->put('name','...');
            }
            
            return $mapped->toArray();
        }
    }

    public function getVolumeTotalAttribute()
    {
        $debt = $this->debt;

        $total = $debt->sum(function($amount){
            return $amount['volume'];
        });

        return "₱" . number_format($total, 2, '.', ',');
    }


    #
    #  Generate number during create form
    #
    public function generateNumber() : STRING
    {
        $date = new \Carbon\Carbon;

        $code = "D" . $date->format("Y-");

        if($this->all()->last()===null)
        {
            return $code . "00001";
        }
        else
        {
            return $code . str_pad($this->withTrashed()->max('id') + 1, 5, '0', STR_PAD_LEFT);
        }
        
    }

}
