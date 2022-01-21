@extends('layouts.doclandscape')

@section('header')
    @include('partials.reportheader')

@endsection



@section('content')

<table style="width:100%;">
     <tr>
        <td style="width: 50%;text-align: left;border:0px">

               ถึงวันที่: {{date("d-m-Y", strtotime($to))}}

        </td>
        <td style="width:50%;text-align: right;border:0px">

                เรียงตามวันที่ สาขาปลายทาง

        </td>
    </tr>
</table>



<table style="padding: 5px;" cellspacing="3" cellpadding="5">
    <thead>
        <tr>
            <th style="width: 5%;text-align: center">ลำดับ</th>
            <th style="width: 15%;text-align: center">สาขาปลายทาง</th>
            <th style="width: 15%;text-align: center">อำเภอปลายทาง</th>
            <th style="width: 10%;text-align: center">วันที่ใบรับส่ง</th>
            <th style="width: 10%;text-align: center">เลขที่ใบรับส่ง</th>
            <th style="width: 15%;text-align: center">ผู้ส่ง</th>
            <th style="width: 15%;text-align: center">ผูรับ</th>
            <th style="width: 15%;text-align: center">ค่าขนส่ง</th>

        </tr>

    </thead>

    <tbody style="vertical-align: top;">

        @foreach ($order_groups as $branch_group => $branches)

            <tr style="font-weight: bold">

                <td colspan="7" >
                    {{ $branch_group }}
                    @foreach ($orders as $item )
                        @php
                            $order_count =  $item->where('branch_rec_id',$branch_group)
                                                ->where('order_status', '=','confirmed')
                                                ->count();
                        @endphp

                    @endforeach
                    - {{ $order_count}} ใบรับส่ง
                </td>
                <td  style="text-align: right;">

                    @foreach ($orders as $item )
                        @php
                            $sumbranch_amount =  $item->where('branch_rec_id','=',$branch_group)->sum('order_amount');
                        @endphp

                    @endforeach

                    {{ number_format($sumbranch_amount,2,'.',',') }}


                </td>


            </tr>
            {{-- @foreach ($branches as $branch => $types)
                <tr style="font-weight: bold">
                    <td colspan="4">
                        @php
                            $branchdata = \App\Models\Branch::find($branch);
                        @endphp

                        {{$branchdata->name}}
                        @foreach ($waybills as $item )
                            @php
                                $branch_count =  $item->whereDate('departure_at',$waybill_date)
                                                    ->where('branch_rec_id',$branch)
                                                    ->count();
                            @endphp

                        @endforeach
                        - {{ $branch_count}} เที่ยว
                    </td>
                    <td  style="text-align: right;">
                    @foreach ($waybills as $item )
                        @php
                            $sumbranch_amount =  $item->whereDate('departure_at',$waybill_date)
                                                      ->where('branch_rec_id',$branch)
                                                      ->sum('waybill_amount');
                        @endphp

                    @endforeach

                    {{ number_format($sumbranch_amount,2,'.',',') }}

                    </td>
                    <td style="text-align: right;">

                        @foreach ($waybills as $item )
                            @php
                                $sumbranch_payable =  $item->whereDate('departure_at',$waybill_date)
                                                        ->where('branch_rec_id',$branch)
                                                        ->sum('waybill_payable');
                            @endphp

                        @endforeach

                        {{ number_format($sumbranch_payable,2,'.',',') }}

                    </td>
                    <td style="text-align: right;">
                        @foreach ($waybills as $item )
                            @php
                                $sumbranch_income =  $item->whereDate('departure_at',$waybill_date)
                                                        ->where('branch_rec_id',$branch)
                                                        ->sum('waybill_income');
                            @endphp

                        @endforeach

                        {{ number_format($sumbranch_income,2,'.',',') }}
                    </td>
                    <td style="text-align: right;">

                        {{ number_format( ($sumbranch_income / $sumbranch_amount)  * 100 ,2,'.',',')}}

                    </td>
                    <td style="text-align: right;">
                        @php
                            $orderbranch_h = 0 ;
                        @endphp

                        @foreach ($waybills as $item)
                            @php
                                if($item->departure_at->format('Y-m-d') == $waybill_date && $item->branch_rec_id == $branch){
                                    $orderbranch_h +=  $item->order_loaders
                                                ->whereIn('paymenttype',['H','T'])
                                                ->sum('order_amount');
                                }
                            @endphp
                        @endforeach
                        {{ number_format($orderbranch_h,2,'.',',') }}
                    </td>
                    <td style="text-align: right;">
                        @php
                            $orderbranch_f = 0 ;
                        @endphp

                        @foreach ($waybills as $item)
                            @php
                                if($item->departure_at->format('Y-m-d') == $waybill_date && $item->branch_rec_id == $branch){
                                    $orderbranch_f +=  $item->order_loaders
                                                ->whereIn('paymenttype',['F','L'])
                                                ->sum('order_amount');
                                }
                            @endphp
                        @endforeach
                        {{ number_format($orderbranch_f,2,'.',',') }}
                    </td>
                    <td style="text-align: right;">
                        @php
                            $orderbranch_e = 0 ;
                        @endphp

                        @foreach ($waybills as $item)
                            @php
                                if($item->departure_at->format('Y-m-d') == $waybill_date && $item->branch_rec_id == $branch){
                                    $orderbranch_e +=  $item->order_loaders
                                                ->where('paymenttype','E')
                                                ->sum('order_amount');
                                }
                            @endphp
                        @endforeach
                        {{ number_format($orderbranch_e,2,'.',',') }}
                    </td>
                </tr>
                @foreach($types as $type => $waybill_items )
                <tr style="vertical-align: top;font-weight: bold">
                    <td colspan="4">
                        @if($type == 'general')
                            ทั่วไป
                        @elseif($type == 'charter')
                            เหมาคัน
                        @else
                            Express
                        @endif
                        @foreach ($waybills as $item )
                            @php
                                $type_count =  $item->whereDate('departure_at',$waybill_date)
                                                    ->where('branch_rec_id',$branch)
                                                    ->where('waybill_type',$type)
                                                    ->count();
                            @endphp

                        @endforeach
                        - {{ $type_count}} เที่ยว
                    </td>
                    <td style="text-align: right;">
                        @foreach ($waybills as $item )
                            @php
                                $sumtype_amount =  $item->whereDate('departure_at',$waybill_date)
                                                        ->where('branch_rec_id',$branch)
                                                        ->where('waybill_type',$type)
                                                        ->sum('waybill_amount');
                            @endphp
                        @endforeach
                        {{ number_format($sumtype_amount,2,'.',',') }}
                    </td>
                    <td style="text-align: right;">
                        @foreach ($waybills as $item )
                            @php
                                $sumtype_payable =  $item->whereDate('departure_at',$waybill_date)
                                                        ->where('branch_rec_id',$branch)
                                                        ->where('waybill_type',$type)
                                                        ->sum('waybill_payable');
                            @endphp
                        @endforeach
                        {{ number_format($sumtype_payable,2,'.',',') }}
                    </td>
                    <td style="text-align: right;">
                        @foreach ($waybills as $item )
                            @php
                                $sumtype_income =  $item->whereDate('departure_at',$waybill_date)
                                                        ->where('branch_rec_id',$branch)
                                                        ->where('waybill_type',$type)
                                                        ->sum('waybill_income');
                            @endphp
                        @endforeach
                        {{ number_format($sumtype_income,2,'.',',') }}
                    </td>
                    <td style="text-align: right;">
                        {{ number_format( ($sumtype_income / $sumtype_amount)  * 100 ,2,'.',',')}}

                    </td>
                    <td style="text-align: right;">
                        @php
                            $ordertype_h = 0 ;
                        @endphp

                        @foreach ($waybills as $item)
                            @php
                                if($item->departure_at->format('Y-m-d') == $waybill_date
                                    && $item->branch_rec_id == $branch && $item->waybill_type == $type){
                                    $ordertype_h +=  $item->order_loaders
                                                ->whereIn('paymenttype',['H','T'])
                                                ->sum('order_amount');
                                }
                            @endphp
                        @endforeach
                        {{ number_format($ordertype_h,2,'.',',') }}

                    </td>
                    <td style="text-align: right;">
                        @php
                            $ordertype_f = 0 ;
                        @endphp

                        @foreach ($waybills as $item)
                            @php
                                if($item->departure_at->format('Y-m-d') == $waybill_date
                                    && $item->branch_rec_id == $branch && $item->waybill_type == $type){
                                    $ordertype_f +=  $item->order_loaders
                                                ->whereIn('paymenttype',['F','L'])
                                                ->sum('order_amount');
                                }
                            @endphp
                        @endforeach
                        {{ number_format($ordertype_f,2,'.',',') }}
                    </td>

                    <td style="text-align: right;">
                        @php
                            $ordertype_e = 0 ;
                        @endphp

                        @foreach ($waybills as $item)
                            @php
                                if($item->departure_at->format('Y-m-d') == $waybill_date
                                    && $item->branch_rec_id == $branch && $item->waybill_type == $type){
                                    $ordertype_e +=  $item->order_loaders
                                                ->where('paymenttype','E')
                                                ->sum('order_amount');
                                }
                            @endphp
                        @endforeach
                        {{ number_format($ordertype_e,2,'.',',') }}
                    </td>

                </tr>

                    @foreach ($waybill_items as $item )

                    <tr style="vertical-align: top;">
                        <td style="text-align: center">
                            {{ $loop->iteration }}
                        </td>
                        <td>
                            {{$item->waybill_no}}
                        </td>

                        <td>
                            {{$item->car->car_regist}}
                        </td>
                        <td>
                            {{$item->car->cartype->name}}
                        </td>

                        <td style="text-align: right">
                            {{number_format($item->waybill_amount,2,'.',',')}}

                        </td>
                        <td style="text-align: right">
                            {{number_format($item->waybill_payable,2,'.',',')}}

                        </td>

                        <td style="text-align: right">
                            {{number_format($item->waybill_income,2,'.',',')}}
                        </td>
                        <td style="text-align: right">
                            {{number_format(($item->waybill_income/$item->waybill_amount)*100,2,'.',',')}}

                        </td>

                        <td style="text-align: right;">
                            {{ number_format($item->order_loaders->whereIn('paymenttype',['H','T'])->sum('order_amount'),2,'.',',') }}

                        </td>
                        <td style="text-align: right;">
                            {{ number_format($item->order_loaders->whereIn('paymenttype',['F','L'])->sum('order_amount'),2,'.',',') }}

                        </td>
                        <td style="text-align: right;">
                            {{ number_format($item->order_loaders->where('paymenttype','E')->sum('order_amount'),2,'.',',') }}


                        </td>
                    </tr>
                    @endforeach
                @endforeach
            @endforeach --}}
        @endforeach

    </tbody>
    <tr style="font-weight: bold">

                {{-- <td colspan="4">

                    รวมทั้งหมด - {{count($waybills)}} เที่ยว

                </td>
                <td  style="text-align: right;">

                    {{ number_format($waybills->sum('waybill_amount'),2,'.',',')}}

                </td>
                <td style="text-align: right;">

                    {{ number_format($waybills->sum('waybill_payable'),2,'.',',')}}

                </td>
                <td style="text-align: right;">

                    {{ number_format($waybills->sum('waybill_income'),2,'.',',')}}

                </td>
                <td style="text-align: right;">

                    {{ number_format( ($waybills->sum('waybill_income') / $waybills->sum('waybill_amount'))  * 100 ,2,'.',',')}}

                </td>

                <td style="text-align: right;">
                    @php
                        $orderall_h = 0 ;
                    @endphp

                    @foreach ($waybills as $item)
                    @php
                        $orderall_h += $item->order_loaders->whereIn('paymenttype',['H','E'])->sum('order_amount')
                    @endphp

                    @endforeach

                    {{ number_format($orderall_h,2,'.',',') }}

                </td>
                <td style="text-align: right;">
                    @php
                        $orderall_f = 0 ;
                    @endphp

                    @foreach ($waybills as $item)
                    @php
                        $orderall_f += $item->order_loaders->whereIn('paymenttype',['F','L'])->sum('order_amount')
                    @endphp

                    @endforeach

                    {{ number_format($orderall_f,2,'.',',') }}

                </td>
                <td style="text-align: right;">
                    @php
                        $orderall_e = 0 ;
                    @endphp

                    @foreach ($waybills as $item)
                    @php
                        $orderall_e +=  $item->order_loaders->where('paymenttype','E')->sum('order_amount')
                    @endphp

                    @endforeach

                    {{ number_format($orderall_e,2,'.',',') }}

                </td> --}}

            </tr>
</table>

@endsection
