<?php namespace Ocs\Collection\Models;

use Model;
use \Carbon\Carbon;

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
    public $rules = [
        'debt' => 'required'
    ];

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


    public function filterFields($fields, $context = null)
    {

        if(isset($fields->debt) AND $fields->debt->value AND $context=='create')
        {
            $debt = \Ocs\Collection\Models\Debt::find($fields->debt->value);
            $fields->{'debt[volume]'}->value = $this->moneyFormat($debt->volume);

            if(($fields->amount->value > 0) AND isset($fields->{'_preview_balance'}))
            {
                $fields->{'_preview_balance'}->value = $this->moneyFormat($this->calcBalance());
            }
        }

    }

    #
    #   Accessor & Mutator
    #
    public function setBalanceAttribute($value)
    {
        $this->attributes['balance'] = (float)$this->new_balance;
    }

    public function getCollectionNumberAttribute()
    {
        if($this->debt)
        return $this->debt->collection->number;
    }

    public function getDebtorNameAttribute()
    {
        if($this->debt)
        return $this->debt->debtor->name;
    }

    public function getLastBalanceAttribute()
    {
        return $this->moneyFormat($this->prevBalance());
    }

    public function getPreviewBalanceAttribute()
    {
        return $this->moneyFormat($this->calcBalance());
    }

    public function getPayableBalanceAttribute()
    {
        if($this->debt AND $this->previous())
        {
            return $this->previous()->balance;
        }else
        {
            return $this->debt->volume;
        }
        return null;
    }

    public function getNewBalanceAttribute()
    {
        if( $this->payable_balance )
        {
            return (float)$this->payable_balance - $this->amount;
        }
    
        return null;
    }

    #
    #   Scopes
    #
    public function scopeAsc($query)
    {
        $query->orderBy('created_at','asc');
        return $query;
    }

    public function scopeDesc($query)
    {
        $query->orderBy('created_at','desc');
        return $query;
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', Carbon::today());
    }

    public function scopeDateBetween($query,$fieldName,$fromDate,$todate)
    {
        return $query->whereDate($fieldName,'>=',$fromDate)->whereDate($fieldName,'<=',$todate);
    }

    #
    #   Helpers
    #
    public function moneyFormat($value)
    {
        return "₱" . number_format($value, 2, '.', ',');
    }

    public function calcBalance() : float
    {
        if($this->debt)
        {
            $calc = $this->isEmptyPayments()
                ? (float)$this->debt->volume - (float)$this->amount
                : (float)$this->debt->payments->last()->balance - (float)$this->amount
            ;
            return $calc;
        }
        return 0;
    }

    public function prevBalance()
    {
        if($this->debt AND $this->debt->payments->isNotEmpty())
            return (float)$this->debt->payments->last()->balance;
        return 0;
    }

    public function isEmptyPayments()
    {
        return ($this->debt AND $this->debt->payments->isEmpty());
    }

    public function previous()
    {
        if($this->debt AND $this->id)  // for update
        {
            return $this->find(($this->id)-1);
        }
        else  // for create
        {
            return $this->debt->payments->last();
        }

        return null;
    }

    public function next()
    {
        if($this->debt AND $this->id)
        {
            return $this->find(($this->id)+1);
        }
        return null;
    }

    #
    #   Events
    #
    public function beforeCreate()
    {
        /*
        *   Compute Balance
        */
        $this->balance = $this->new_balance;
    }

    public function beforeUpdate()
    {
        $this->balance = (float)$this->new_balance;
    }

}