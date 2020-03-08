<?php namespace Ocs\Collection\Models;

use Model;

/**
 * Payment Model
 */
class Payment extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'ocs_collection_payment';

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
        
    ];
    public $belongsTo = [
        'debt' => [
            \Ocs\Collection\Models\Debt::class
        ],
        'debtor' => [
            \Ocs\Collection\Models\Debtor::class
        ]
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public function filterFields($fields, $context = null)
    {
        if(isset($fields->debt) AND $fields->debt->value)
        {
            $debtorName = \Ocs\Collection\Models\Debtor::find($fields->debt->value);
            $fields->{'debtor[name]'}->value = $debtorName->name;
        }
    }

    public function getDebtorNameAttribute()
    {
        return $this->debt->debtor->name;
    }

    public function getBalanceAttribute($value)
    {
        $query = $this->previous()->get();

        if($query->isEmpty())
        {
            $balance = $this->debt->volume - $this->amount;
        }
        else
        {
            $balance = $query->first()->balance - $this->amount;
        }

        return $balance;
    }

    public function scopeNext($query)
    {
        // get next model
        $query->where('id', '>', $this->id)->orderBy('id','asc')->first();

        return $query;
    }

    public  function scopePrevious($query)
    {
        // get previous  model
        $query->where('id', '<', $this->id)->orderBy('id','desc')->first();
        return $query;
    }

}