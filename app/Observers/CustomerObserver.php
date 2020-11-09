<?php

namespace App\Observers;

use App\Models\Customer;
use Haruncpi\LaravelIdGenerator\IdGenerator;


class CustomerObserver
{



    /**
     * Handle the retailer "creating" event.
     *
     * @param  \App\Customer  $customer
     * @return void
     */
    public function creating(Customer $customer)
    {
        //$customer_code = IdGenerator::generate(['table' => 'customers', 'field' => 'customer_code', 'length' => 10, 'prefix' => 'S']);
        $customer->user_id = auth()->user()->id;
        // $customer->customer_code = $customer_code;
        $customer->country = 'thailand';
    }

    public function updating(Customer $customer)
    {
        $customer->updated_by = auth()->user()->id;
        $customer->country = 'thailand';
    }

    /**
     * Handle the retailer "created" event.
     *
     * @param  \App\Customer  $customer
     * @return void
     */
    public function created(Customer $customer)
    {
        //
    }
    /**
     * Handle the retailer "updated" event.
     *
     * @param  \App\Customer  $customer
     * @return void
     */
    public function updated(Customer $customer)
    {
        //
    }

    /**
     * Handle the retailer "deleted" event.
     *
     * @param  \App\Customer  $customer
     * @return void
     */
    public function deleted(Customer $customer)
    {
        //
    }

    /**
     * Handle the retailer "restored" event.
     *
     * @param  \App\Customer  $customer
     * @return void
     */
    public function restored(Customer $customer)
    {
        //
    }

    /**
     * Handle the retailer "force deleted" event.
     *
     * @param  \App\Customer  $customer
     * @return void
     */
    public function forceDeleted(Customer $customer)
    {
        //
    }
}
