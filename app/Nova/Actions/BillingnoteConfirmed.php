<?php

namespace App\Nova\Actions;

use App\Mail\BillingnoteSentMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use App\Notifications\BillingnoteTomail;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;

class BillingnoteConfirmed extends Action
{
    use InteractsWithQueue, Queueable;
    public function uriKey()
    {
        return 'billingnote_confirm';
    }
    public function name()
    {
        return 'ยืนยันวางบิลและส่งอีเมล';
    }
    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {
            $model->status = 'billed';
            $model->save();
            if ($model->status == 'billed' && $model->billing_by == '1') {

                Mail::to($model->ar_customer->email)->send(new BillingnoteSentMail($model));
            }
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }
}
