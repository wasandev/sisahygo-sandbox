<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{

    protected $fillable = [
        'company_name',
        'taxid',
        'address',
        'sub_district',
        'district',
        'province',
        'postal_code',
        'country',
        'description',
        'imagefile',
        'email',
        'logofile',
        'phoneno',
        'weburl',
        'facebook',
        'line',
        'location_lat',
        'location_lng',
        'user_id',
        'updated_by',
        'orderprint_option'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
