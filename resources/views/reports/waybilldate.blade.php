@extends('layouts.doclandscapenojs')

@section('header')
    @include('partials.reportheader')
@endsection



@section('content')
    <table style="width:100%;">
        <tr>
            <td style="width: 50%;text-align: left;border:0px">

                ระหว่างวันที่: {{ date('d-m-Y', strtotime($from)) }} ถึงวันที่: {{ date('d-m-Y', strtotime($to)) }}

            </td>
            <td style="width:50%;text-align: right;border:0px">

                เรียงตามวันที่ ประเภท สาขาปลายทาง

            </td>
        </tr>
    </table>



    <table style="padding: 5px;" cellspacing="3" cellpadding="5">
        <thead>
            <tr>
                <th style="width: 5%;text-align: center">ลำดับ</th>
                <th style="width: 5%;text-align: center">เลขที่ใบกำกับ</th>
                <th style="width: 10%;text-align: center">ทะเบียนรถ</th>
                <th style="width: 10%;text-align: center">ประเภทรถ</th>
                <th style="width: 10%;text-align: center">ค่าระวาง</th>
                <th style="width: 10%;text-align: center">ค่าบรรทุก</th>
                <th style="width: 10%;text-align: center">รายได้</th>
                <th style="width: 10%;text-align: center">%รายได้</th>
                <th style="width: 10%;text-align: center">ต้นทาง</th>
                <th style="width: 10%;text-align: center">วางบิล</th>
                <th style="width: 10%;text-align: center">ปลายทาง</th>

            </tr>

        </thead>

        <tbody style="vertical-align: top;">

            @foreach ($waybill_groups as $waybill_date => $branches)
                <tr style="font-weight: bold;background-color:#c0c0c0">
                    @php
                        $orderdate_h = 0;
                        $orderdate_f = 0;
                        $orderdate_e = 0;
                    @endphp
                    @foreach ($waybills as $item)
                        @php
                            $date_count = $item->whereDate('departure_at', $waybill_date)->count();
                            if ($item->departure_at->format('Y-m-d') == $waybill_date) {
                                $orderdate_h += $item->order_loaders->whereIn('paymenttype', ['H', 'T'])->sum('order_amount');
                            }
                            if ($item->departure_at->format('Y-m-d') == $waybill_date) {
                                $orderdate_f += $item->order_loaders->whereIn('paymenttype', ['F', 'L'])->sum('order_amount');
                            }
                            if ($item->departure_at->format('Y-m-d') == $waybill_date) {
                                $orderdate_e += $item->order_loaders->where('paymenttype', 'E')->sum('order_amount');
                            }
                        @endphp
                    @endforeach
                    <td colspan="4">
                        วันที่ : {{ date('d/m/Y', strtotime($waybill_date)) }}
                        - {{ $date_count }} เที่ยว
                    </td>
                    @php
                        $sumdate_amount = $branches->sum(function ($group1) {
                            return $group1->sum(function ($group2) {
                                return $group2->sum('waybill_amount');
                            });
                        });
                        
                        $sumdate_payable = $branches->sum(function ($group1) {
                            return $group1->sum(function ($group2) {
                                return $group2->sum('waybill_payable');
                            });
                        });
                        
                        $sumdate_income = $branches->sum(function ($group1) {
                            return $group1->sum(function ($group2) {
                                return $group2->sum('waybill_income');
                            });
                        });
                        
                    @endphp


                    <td style="text-align: right;">
                        {{ number_format($sumdate_amount, 2, '.', ',') }}
                    </td>
                    <td style="text-align: right;">
                        {{ number_format($sumdate_payable, 2, '.', ',') }}
                    </td>

                    <td style="text-align: right;">
                        {{ number_format($sumdate_income, 2, '.', ',') }}
                    </td>
                    <td style="text-align: right;">
                        {{ number_format(($sumdate_income / $sumdate_amount) * 100, 2, '.', ',') }}
                    </td>
                    <td style="text-align: right;">
                        {{ number_format($orderdate_h, 2, '.', ',') }}
                    </td>
                    <td style="text-align: right;">

                        {{ number_format($orderdate_f, 2, '.', ',') }}
                    </td>
                    <td style="text-align: right;">

                        {{ number_format($orderdate_e, 2, '.', ',') }}
                    </td>

                </tr>
                @foreach ($branches as $branch => $types)
                    @php
                        $orderbranch_h = 0;
                        $orderbranch_f = 0;
                        $orderbranch_e = 0;
                        
                    @endphp
                    @foreach ($waybills as $item)
                        @php
                            
                            $branch_count = $item
                                ->whereDate('departure_at', $waybill_date)
                                ->where('branch_rec_id', $branch)
                                ->count();
                            if ($item->departure_at->format('Y-m-d') == $waybill_date && $item->branch_rec_id == $branch) {
                                $orderbranch_h += $item->order_loaders->whereIn('paymenttype', ['H', 'T'])->sum('order_amount');
                            }
                            if ($item->departure_at->format('Y-m-d') == $waybill_date && $item->branch_rec_id == $branch) {
                                $orderbranch_f += $item->order_loaders->whereIn('paymenttype', ['F', 'L'])->sum('order_amount');
                            }
                            if ($item->departure_at->format('Y-m-d') == $waybill_date && $item->branch_rec_id == $branch) {
                                $orderbranch_e += $item->order_loaders->where('paymenttype', 'E')->sum('order_amount');
                            }
                        @endphp
                    @endforeach
                    <tr style="font-weight: bold">
                        <td colspan="4">
                            @php
                                $branchdata = \App\Models\Branch::find($branch);
                            @endphp

                            {{ $branchdata->name }}

                            - {{ $branch_count }} เที่ยว
                        </td>
                        @php
                            $sumbranch_amount = $types->sum(function ($group1) {
                                return $group1->sum('waybill_amount');
                            });
                            $sumbranch_payable = $types->sum(function ($group1) {
                                return $group1->sum('waybill_payable');
                            });
                            $sumbranch_income = $types->sum(function ($group1) {
                                return $group1->sum('waybill_income');
                            });
                        @endphp
                        <td style="text-align: right;">

                            {{ number_format($sumbranch_amount, 2, '.', ',') }}

                        </td>
                        <td style="text-align: right;">



                            {{ number_format($sumbranch_payable, 2, '.', ',') }}

                        </td>
                        <td style="text-align: right;">


                            {{ number_format($sumbranch_income, 2, '.', ',') }}
                        </td>
                        <td style="text-align: right;">
                            @if ($sumbranch_amount > 0)
                                {{ number_format(($sumbranch_income / $sumbranch_amount) * 100, 2, '.', ',') }}
                            @endif

                        </td>
                        <td style="text-align: right;">

                            {{ number_format($orderbranch_h, 2, '.', ',') }}
                        </td>
                        <td style="text-align: right;">

                            {{ number_format($orderbranch_f, 2, '.', ',') }}
                        </td>
                        <td style="text-align: right;">


                            {{ number_format($orderbranch_e, 2, '.', ',') }}
                        </td>
                    </tr>
                    @foreach ($types as $type => $waybill_items)
                        @php
                            $ordertype_h = 0;
                            $ordertype_f = 0;
                            $ordertype_e = 0;
                            $sumtype_amount = $waybill_items->sum('waybill_amount');
                            $sumtype_payable = $waybill_items->sum('waybill_payable');
                            $sumtype_income = $waybill_items->sum('waybill_income');
                        @endphp
                        @foreach ($waybills as $item)
                            @php
                                $type_count = $item
                                    ->whereDate('departure_at', $waybill_date)
                                    ->where('branch_rec_id', $branch)
                                    ->where('waybill_type', $type)
                                    ->count();
                                
                                if ($item->departure_at->format('Y-m-d') == $waybill_date && $item->branch_rec_id == $branch && $item->waybill_type == $type) {
                                    $ordertype_h += $item->order_loaders->whereIn('paymenttype', ['H', 'T'])->sum('order_amount');
                                }
                                if ($item->departure_at->format('Y-m-d') == $waybill_date && $item->branch_rec_id == $branch && $item->waybill_type == $type) {
                                    $ordertype_f += $item->order_loaders->whereIn('paymenttype', ['F', 'L'])->sum('order_amount');
                                }
                                if ($item->departure_at->format('Y-m-d') == $waybill_date && $item->branch_rec_id == $branch && $item->waybill_type == $type) {
                                    $ordertype_e += $item->order_loaders->where('paymenttype', 'E')->sum('order_amount');
                                }
                            @endphp
                        @endforeach
                        <tr style="vertical-align: top;font-weight: bold">
                            <td colspan="4">
                                @if ($type == 'general')
                                    ทั่วไป
                                @elseif($type == 'charter')
                                    เหมาคัน
                                @else
                                    Express
                                @endif

                                - {{ $type_count }} เที่ยว
                            </td>

                            <td style="text-align: right;">
                                {{ number_format($sumtype_amount, 2, '.', ',') }}
                            </td>
                            <td style="text-align: right;">

                                {{ number_format($sumtype_payable, 2, '.', ',') }}
                            </td>
                            <td style="text-align: right;">

                                {{ number_format($sumtype_income, 2, '.', ',') }}
                            </td>
                            <td style="text-align: right;">
                                @if ($sumtype_amount > 0)
                                    {{ number_format(($sumtype_income / $sumtype_amount) * 100, 2, '.', ',') }}
                                @endif
                            </td>
                            <td style="text-align: right;">

                                {{ number_format($ordertype_h, 2, '.', ',') }}

                            </td>
                            <td style="text-align: right;">

                                {{ number_format($ordertype_f, 2, '.', ',') }}
                            </td>

                            <td style="text-align: right;">

                                {{ number_format($ordertype_e, 2, '.', ',') }}
                            </td>

                        </tr>
                        @foreach ($waybill_items->chunk(20) as $chunk)
                            @foreach ($chunk as $item)
                                <tr style="vertical-align: top;">
                                    <td style="text-align: center">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        {{ $item->waybill_no }}
                                    </td>

                                    <td>
                                        {{ $item->car->car_regist }}
                                    </td>
                                    <td>
                                        {{ $item->car->cartype->name }}
                                    </td>

                                    <td style="text-align: right">
                                        {{ number_format($item->waybill_amount, 2, '.', ',') }}

                                    </td>
                                    <td style="text-align: right">
                                        {{ number_format($item->waybill_payable, 2, '.', ',') }}

                                    </td>

                                    <td style="text-align: right">
                                        {{ number_format($item->waybill_income, 2, '.', ',') }}
                                    </td>
                                    <td style="text-align: right">
                                        @if ($item->waybill_amount > 0)
                                            {{ number_format(($item->waybill_income / $item->waybill_amount) * 100, 2, '.', ',') }}
                                        @endif
                                    </td>

                                    <td style="text-align: right;">
                                        {{ number_format($item->order_loaders->whereIn('paymenttype', ['H', 'T'])->sum('order_amount'), 2, '.', ',') }}

                                    </td>
                                    <td style="text-align: right;">
                                        {{ number_format($item->order_loaders->whereIn('paymenttype', ['F', 'L'])->sum('order_amount'), 2, '.', ',') }}

                                    </td>
                                    <td style="text-align: right;">
                                        {{ number_format($item->order_loaders->where('paymenttype', 'E')->sum('order_amount'), 2, '.', ',') }}


                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                @endforeach
            @endforeach

        </tbody>
        <tr style="font-weight: bold;background-color:#c0c0c0">
            @php
                $orderall_h = 0;
                $orderall_f = 0;
                $orderall_e = 0;
            @endphp

            @foreach ($waybills as $item)
                @php
                    $orderall_h += $item->order_loaders->whereIn('paymenttype', ['H', 'T'])->sum('order_amount');
                    $orderall_f += $item->order_loaders->whereIn('paymenttype', ['F', 'L'])->sum('order_amount');
                    $orderall_e += $item->order_loaders->where('paymenttype', 'E')->sum('order_amount');
                    
                @endphp
            @endforeach
            <td colspan="4">

                รวมทั้งหมด - {{ count($waybills) }} เที่ยว

            </td>
            <td style="text-align: right;">

                {{ number_format($waybills->sum('waybill_amount'), 2, '.', ',') }}

            </td>
            <td style="text-align: right;">

                {{ number_format($waybills->sum('waybill_payable'), 2, '.', ',') }}

            </td>
            <td style="text-align: right;">

                {{ number_format($waybills->sum('waybill_income'), 2, '.', ',') }}

            </td>
            <td style="text-align: right;">

                {{ number_format(($waybills->sum('waybill_income') / $waybills->sum('waybill_amount')) * 100, 2, '.', ',') }}

            </td>

            <td style="text-align: right;">

                {{ number_format($orderall_h, 2, '.', ',') }}

            </td>
            <td style="text-align: right;">


                {{ number_format($orderall_f, 2, '.', ',') }}

            </td>
            <td style="text-align: right;">


                {{ number_format($orderall_e, 2, '.', ',') }}

            </td>

        </tr>
    </table>
@endsection
@section('footer')
    {{-- <div class="d-flex justify-content-center">
    {!! $waybills->links() !!}
</div> --}}
@endsection
