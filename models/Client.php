<?php namespace Ocs\Collection\Models;

use Model;

/**
 * Client Model
 */
class Client extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'ocs_collection_clients';

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
        'name' => 'required|unique:ocs_collection_clients,name',
        'email' => 'email|unique:ocs_collection_clients,email'
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
        'collection' => '\Ocs\Collection\Models\Collection'
    ];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [
        // 'debt' => ['\Ocs\Collection\Models\Debt', 'name' => 'debt']
    ];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];


    #
    #   Events
    #
    public function afterDelete()
    {
        $this->debt()->delete();
    }

    #
    #   Custom Functions
    #
    public function getDebtCountAttribute()
    {
        return $this->debt()->count();
    }

}
