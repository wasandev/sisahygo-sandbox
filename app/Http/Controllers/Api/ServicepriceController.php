<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\Productservice_newprice;
use App\Models\PostalCode;
use App\Models\District;
use Validator;
use App\Http\Resources\ServicepriceResource;
use Illuminate\Support\Arr;

class ServicepriceController extends BaseController
{
    public function index($product_id,$district)
    {
        
        
    }

    public function show($product_id,$qty,$postal_code)
    {
        $district = PostalCode::where('code',$postal_code)->first();
        
        $district_name = District::where('id',$district->district_id)
                                  ->where('province_id',$district->province_id)
                                  ->first();
        
        $service_price  = Productservice_newprice::with('product')
         ->with('unit')
         ->where('product_id',$product_id)
         ->where('district',$district_name->name)
         ->first();
        $service_amount = Arr::add($service_price, 'qty', $qty);
        if (is_null($service_amount)) {
            return $this->sendError('Product price not found.');
        }
   
        return $this->sendResponse(new ServicepriceResource($service_amount), 'Product price retrieved successfully.');
    }


    
}