<?php namespace Ocs\Collection\Models;

use Model;

/**
 * Debt Model
 */
class Debt extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'ocs_collection_debt';

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
        'payments' => [
            \Ocs\Collection\Models\Payment::class
        ]
    ];
    public $belongsTo = [
        'collection' => [
            \Ocs\Collection\Models\Collection::class
        ],
        'debtor' => [
            \Ocs\Collection\Models\Debtor::class
        ]
    ];



    # Fixes issue: when decimal field is empty
    public function beforeSave()
    {
        if(empty($this->volume))
        {
            $this->volume = 0;
        }
    }

    public function afterSave()
    {
        $this->collection()->touch(); //Updated parent updated_at
    }

    public function getClientAttribute()
    {
        if($this->collection)
        {
            return $this->collection->client->name;
        }
    }

    public function getNameAttribute()
    {
        return $this->debtor->name;
    }

    public function getVolumeCurrencyAttribute($value)
    {
        return $this->moneyFormat($this->volume);
    }

    public function getBalanceFormatAttribute()
    {
        return $this->moneyFormat($this->balance);
    }

    public function getBalanceAttribute()
    {
        if($this->payments->isEmpty())
        {
            return $this->volume;
        }

        return $this
            ->payments
            ->last()
            ->balance;
    }

    public function getStatusAttribute()
    {
        if( $this->payments )
        {
            if($this->payments->pluck('amount')->sum() == $this->volume AND (float)$this->volume!=0)
            {
                return 'Paid';
            }
            elseif((float)$this->volume==0)
            {
                return 'No Volume';
            }
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

    public function scopeDateBetween($query,$fieldName,$fromDate,$todate)
    {
        return $query->whereDate($fieldName,'>=',$fromDate)->whereDate($fieldName,'<=',$todate);
    }

    public function scopeCreatedBetween($query,$from,$to)
    {
        return $query->dateBetween('created_at', $from, $to);
    }


    #
    #   Helpers
    #
    public function moneyFormat($value)
    {
        return "₱" . number_format((float)$value, 2, '.', ',');
    }

    public function setStatus($status = null)
    {
        $this->status = $status;
        $this->save();
    }

    public function getVolumeTotal()
    {
        return $this
            ->all()
            ->pluck('volume')
            ->sum()
        ;
    }

    public function getBalanceTotal()
    {
        return $this
            ->all()
            ->pluck('balance')
            ->sum()
        ;
    }

}