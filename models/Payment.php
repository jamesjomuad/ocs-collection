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

        if(isset($fields->debt) AND $fields->debt->value AND $context=='create')
        {
            $debt = \Ocs\Collection\Models\Debt::find($fields->debt->value);
            $fields->{'debt[volume]'}->value = $this->moneyFormat($debt->volume);

            if(($fields->amount->value > 0) AND isset($fields->{'_preview_balance'}))
            {
                // $fields->{'_last_balance'}->value = $this->moneyFormat($this->last_balance);
                $fields->{'_preview_balance'}->value = $this->moneyFormat($this->calcBalance());
            }
        }

    }

    public function getDebtorNameAttribute()
    {
        return $this->debt->debtor->name;
    }

    public function getLastBalanceAttribute()
    {
        return $this->moneyFormat($this->prevBalance());
    }

    public function setBalanceAttribute($value)
    {
        $this->attributes['balance'] = (float)$value;
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

    #
    #   Helpers
    #
    public function moneyFormat($value)
    {
        return "₱" . number_format($value, 2, '.', ',');
    }

    public function calcBalance() : float
    {
        $balance = $this->isEmptyPayments()
            ? (float)$this->debt->volume - (float)$this->amount
            : (float)$this->debt->payments->last()->balance - (float)$this->amount
        ;

        return $balance;
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

    #
    #   Events
    #
    public function beforeCreate()
    {

        /*
        *   Compute Balance
        */
        if(empty($this->balance))
        {
            $this->balance = $this->calcBalance();
        }

        
    }

}