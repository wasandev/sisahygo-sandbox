<?php

namespace App\Nova\Lenses\accounts;

use App\Nova\Actions\Accounts\PrintPaymentReportByDay;
use App\Nova\Actions\Accounts\PrintReceiveReportByDay;
use App\Nova\Filters\Lenses\ReceiveLensFromDate;
use App\Nova\Filters\Lenses\ReceiveLensToDate;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Lenses\Lens;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class CarreceiveReportByDay extends Lens
{
    /**
     * Get the query builder / paginator for the lens.
     *
     * @param  \Laravel\Nova\Http\Requests\LensRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return mixed
     */
    public static function query(LensRequest $request, $query)
    {
        return $request->withOrdering($request->withFilters(
            $query->select(self::columns())
                ->join('cars', 'cars.id', '=', 'carreceives.car_id')
                ->join('vendors', 'vendors.id', '=', 'carreceives.vendor_id')
                ->where('carreceives.status', true)
                ->orderBy('carreceives.receive_date', 'asc')
                ->orderBy('carreceives.id', 'asc')
        ));
    }
    /**
     * Get the columns that should be selected.
     *
     * @return array
     */
    protected static function columns()
    {
        return [
            'carreceives.id',
            'carreceives.receive_date',
            'carreceives.receive_no',
            'carreceives.vendor_id',
            'carreceives.car_id',
            'vendors.name as owner',
            'cars.car_regist',
            'carreceives.receive_by',
            'carreceives.amount',
        ];
    }
    /**
     * Get the fields available to the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            Date::make('วันที่', 'receive_date'),
            Text::make('เลขที่', 'receive_no'),
            BelongsTo::make('จ่ายให้', 'vendor', 'App\Nova\Vendor'),
            BelongsTo::make('ทะเบียนรถ', 'car', 'App\Nova\Car'),
            Currency::make('จำนวนเงิน', 'amount'),
            Select::make('จ่ายด้วย', 'receive_by')
                ->options([
                    "H" => "เงินสด",
                    'T' => "เงินโอน",
                    'Q' => "เช็ค",
                    'A' => "รายการตัดบัญชี"
                ])->displayUsingLabels()

        ];
    }

    /**
     * Get the cards available on the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            new ReceiveLensFromDate,
            new ReceiveLensToDate,
        ];
    }

    /**
     * Get the actions available on the lens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            (new PrintReceiveReportByDay($request->filters))
                ->standalone()
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('view car_receives');
                }),
            (new DownloadExcel)->allFields()->withHeadings()
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('view car_receives');
                }),
        ];
    }

    /**
     * Get the URI key for the lens.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'accounts-carreceive-report-by-day';
    }
    public function name()
    {
        return 'รายงานสรุปการรับเงินรถ';
    }
}
