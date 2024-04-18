<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Laravel\Scout\Searchable;

class Productservice_newprice extends Model
{
    //use Searchable;
    protected $fillable = [
        'product_id', 'from_branch_id', 'unit_id', 'price',  'district', 'province', 'user_id', 'updated_by', 'branch_area_id'
    ];

    protected $table = 'productservice_newprice';

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }


    public function unit()
    {
        return $this->belongsTo('App\Models\Unit');
    }

    public function from_branch()
    {
        return $this->belongsTo('App\Models\Branch', 'from_branch_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function user_update()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }

    public function branch_area()
    {
        return $this->belongsTo('App\Models\Branch_area');
    }
   
    /**
     * Get the name of the index associated with the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'productservice_newprices';
    }

    public function district()
    {
        return $this->belongsTo('App\Models\District','name','district');
    }

}
