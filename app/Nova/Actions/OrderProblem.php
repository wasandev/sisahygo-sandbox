<?php

namespace App\Nova\Actions;

use App\Models\Order_problem;
use App\Models\Order_problem_image;
use App\Models\Order_status;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;

class OrderProblem extends Action
{
    use InteractsWithQueue, Queueable;
    public function uriKey()
    {
        return 'order_problem';
    }
    public function name()
    {
        return 'แจ้งปัญหาการขนส่ง';
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


            $problem_no = IdGenerator::generate(['table' => 'order_problems', 'field' => 'problem_no', 'length' => 15, 'prefix' => 'QC' . date('Ymd')]);

            if ($model->order_status == 'problem') {
                return Action::danger('รายการนี้แจ้งปัญหาไปแล้ว');
            }
            if ($fields->problem_cust == 'S') {
                $customer_id = $model->customer_id;
            } else {
                $customer_id = $model->customer_rec_id;
            }
            $order_problem = Order_problem::create([
                'problem_no' => $problem_no,
                'customer_flag' => $fields->problem_cust,
                'problem_date' => today(),
                'order_header_id' => $model->id,
                'customer_id' => $customer_id,
                'status' => 'new',
                'problem_type' => $fields->problem_type,
                'problem_detail' => $fields->problem_detail,
            ]);
            $model->order_status = 'problem';
            $model->save();

            // Order_problem_image::create([
            //     'order_problem_id' => $order_problem->id,
            //     'problemimage' => $fields->problemimage,
            // ]);
            Order_status::create([
                'order_header_id' => $model->id,
                'status' => 'problem',
                'user_id' => auth()->user()->id,
            ]);
            return Action::message('แจ้งปัญหาเรียบร้อยแล้ว');
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Select::make('ผู้แจ้งเรื่อง', 'problem_cust')
                ->options([
                    'S' => 'ผู้ส่ง',
                    'R' => 'ผู้รับ'
                ])->displayUsingLabels(),
            Select::make('ประเภท', 'problem_type')
                ->options([
                    '1' => 'เสียหายทั้งหมด',
                    '2' => 'เสียหายบางส่วน',
                    '3' => 'สูญหายทั้งหมด',
                    '4' => 'สูญหายบางส่วน',
                    '0' => 'อื่นๆ'
                ])->displayUsingLabels(),
            Text::make('รายละเอียดปัญหา', 'problem_detail'),
            // Image::make('รูปสินค้ามีปัญหา', 'problemimage')
            //     ->rules("mimes:jpeg,bmp,png", "max:2048")
            //     ->help('ขนาดไฟล์ไม่เกิน 2 MB.'),

        ];
    }
}
