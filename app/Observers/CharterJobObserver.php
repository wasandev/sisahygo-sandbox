<?php

namespace App\Observers;

use App\Models\Charter_job;
use App\Models\Charter_job_status;
use App\Models\Charter_price;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Carbon;


class CharterJobObserver
{
    public function creating(Charter_job $charter_job)
    {

        $charter_price = Charter_price::find($charter_job->charter_price_id);

        $charter_job->sub_total = $charter_price->price;
        $charter_job->total = $charter_price->price - $charter_job->discount;
        $charter_job->user_id = auth()->user()->id;
        $charter_job->status = 'new';
        $charter_job->branch_id = auth()->user()->branch_id;
        $job_no = IdGenerator::generate(['table' => 'quotations', 'field' => 'quotation_no', 'length' => 15, 'prefix' => 'J' . date('Ymd')]);

        $charter_job->job_no = $job_no;
        $charter_job->job_date = Carbon::now()->toDateTimeString();
    }

    public function created(Charter_job $charter_job)
    {
        $charter_price = Charter_price::find($charter_job->charter_price_id);

        $charter_job->sub_total = $charter_price->price;
        $charter_job->total = $charter_price->price - $charter_job->discount;
    }


    public function updating(Charter_job $charter_job)
    {
        $charter_price = Charter_price::find($charter_job->charter_price_id);

        $charter_job->sub_total = $charter_price->price;
        $charter_job->total = $charter_price->price - $charter_job->discount;
        $charter_job->updated_by = auth()->user()->id;
    }
    public function updated(Charter_job $charter_job)
    {
        $charter_price = Charter_price::find($charter_job->charter_price_id);

        $charter_job->sub_total = $charter_price->price;
        $charter_job->total = $charter_price->price - $charter_job->discount;
        if ($charter_job->status == 'active') {
            Charter_job_status::create([
                'charter_job_id' => $charter_job->id,
                'status' => 'open',
                'user_id' => auth()->user()->id,
            ]);
        }
    }
}
