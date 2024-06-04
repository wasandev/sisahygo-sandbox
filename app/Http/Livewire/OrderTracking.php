<?php

namespace App\Http\Livewire;

use App\Models\Order_status;
use App\Models\Order_header;
use Livewire\WithPagination;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class OrderTracking extends Component
{
    
    public $tracking = '';
    //public $order_statuses ;
    
    
    protected $queryString = ['tracking'];
    
    public function trackingOrder()
    
    {
       
       
        
      
    }
    
    
    public function render(){

        $now = date('Y-m-d');
        $back = date('Y-m-d', strtotime($now.' - 30 days'));
        $orderStatus = Order_status::with('order_header')
                                    ->where('order_header_id',$this->tracking)
                                    ->whereHas('order_header',fn(Builder $query) => $query->whereDate('created_at','>=',$back))
                                    ->get();
                                
        
        return view('livewire.order-tracking',['order_statuses' => $orderStatus 
                ]) ;
        
    }
}
