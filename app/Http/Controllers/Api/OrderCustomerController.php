<?php
   
namespace App\Http\Controllers\Api;
   
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use Validator;
use App\Models\Order_customer;
use App\Http\Resources\OrderCustomer as OrderCustomerResource;
   
class OrderCustomerController extends BaseController
{
    public function index()
    {
        
    }
    
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'customer_rec_id' => 'required',
            
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors());       
        }
        $order_customer = Order_customer::create($input);
        return $this->sendResponse(new OrderCustomerResource($order_customer), 'Customer Order created.');
    }
   
    public function show($id)
    {
        $order_customer = Order_customer::find($id);
        if (is_null($order_customer)) {
            return $this->sendError('Customer Order does not exist.');
        }
        return $this->sendResponse(new OrderCustomerResource($order_customer), 'Customer order fetched.');
    }
    
    public function update(Request $request, Order_customer $order_customer)
    {
        $input = $request->all();
        $validator = Validator::make($input, [            
            'customer_rec_id' => 'required'
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors());       
        }
        $order_customer->customer_rec_id = $input['customer_rec_id'];
        $order_customer->remark = $input['remark'];
        $order_customer->save();
        
        return $this->sendResponse(new OrderCustomerResource($order_customer), 'Customer order updated.');
    }
   
    public function destroy(Order_Customer $order_customer)
    {
       
    }
}