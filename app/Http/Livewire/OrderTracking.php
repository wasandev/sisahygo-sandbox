<?php

namespace App\Http\Livewire;

use App\Models\Order_status;
use App\Models\Order_header;
use Livewire\WithPagination;
use Livewire\Component;

class OrderTracking extends Component
{
    
    public $tracking = '';
    //public $order_statuses ;
   
    
    protected $queryString = ['tracking'];
    
    public function trackingOrder()
    
    {
        sleep(1);       
        
        
      
    }
    
    
    public function render(){
        
        return view('livewire.order-tracking',['order_statuses' => Order_status::where('order_header_id',$this->tracking)->get(),]) ;
        
    }
}
