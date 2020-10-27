<?php

namespace App\Models;

use App\Nova\Quotation as AppQuotation;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{

    const OPEN_STATUS = 'Open';
    const EDIT_STSTUS = 'Edit';
    const COMFIRM_STATUS = 'Comfirm';
    const REJECT_STATUS = 'Reject';

    protected $fillable = [
        'active', 'status', 'quotation_no', 'quotation_date', 'branch_id', 'customer_id', 'paymenttype', 'terms', 'expiration_date', 'user_id', 'updated_by'

    ];

    protected $casts = [
        'quotation_date' => 'datetime',
        'expiration_date' => 'datetime'
    ];
    public function branch()
    {
        return $this->belongsTo('App\Models\Branch');
    }
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function charter_prices()
    {
        return $this->belongsToMany('App\Models\Charter_price', 'charter_price_quotation', 'quotation_id', 'charter_price_id');
    }

    public static function getStatuses()
    {
        return [
            self::OPEN_STATUS => self::OPEN_STATUS,
            self::EDIT_STATUS => self::EDIT_STATUS,
            self::CONFIRM_STATUS => self::CONFIRM_STATUS,
            self::REJECT_STATUS => self::REJECT_STATUS,

        ];
    }

    static function  nextQuotationNumber()
    {
        if (Quotation::count() == 0) {
            $nextQuotationNumber = 'Q' . date('Y') . '-000001';
        } else {

            //get last record
            $record = Quotation::latest()->first();

            $expNum = explode('-', $record->quotation_no);

            //check first day in a year
            if (date('z') === '0') {
                $nextQuotationNumber = 'Q' . date('Y') . '-000001';
            } else {
                //increase 1 with last tranjob number
                $nextQuotationNumber = $expNum[0] . '-' . sprintf('%06d', intval($expNum[1]) + 1);
            }
        }


        return  $nextQuotationNumber;
    }
}
