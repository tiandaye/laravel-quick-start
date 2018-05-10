<?php

namespace App\Models\Mall\Product;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Category
 * @package App\Models\Mall\Product
 * @version November 17, 2017, 7:13 pm CST
 *
 * @property \App\Models\Mall\Product\Category category
 * @property string name
 * @property string logo
 * @property integer parent_id
 * @property integer order
 * @property string description
 */
class Category extends Model
{
    use SoftDeletes;

    public $table = 'categories';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'name',
        'logo',
        'parent_id',
        'order',
        'status',
        'description',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name'        => 'string',
        'logo'        => 'string',
        'parent_id'   => 'integer',
        'status'      => 'integer',
        'order'       => 'integer',
        'description' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|max:191',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function category()
    {
        return $this->hasOne(\App\Models\Mall\Product\Category::class, 'id', 'parent_id');
    }
}
