@extends('layouts.doclandscapenojs')

{{-- @section('header')
    @include('partials.reportheader')

@endsection --}}



@section('content')

<table style="width:100%;">
     <tr>
        <td style="width: 50%;text-align: left;border:0px">

                {{-- ระหว่างวันที่: {{date("d-m-Y", strtotime($from))}}  ถึงวันที่: {{date("d-m-Y", strtotime($to))}} --}}

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



                        @foreach($waybill as $item)



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
                                    @if($item->waybill_amount > 0 )
                                        {{number_format(($item->waybill_income/$item->waybill_amount)*100,2,'.',',')}}
                                    @endif
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
    </tbody>
    <tr style="font-weight: bold;background-color:#c0c0c0">

                <td colspan="4">

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

                </td>

            </tr>
</table>

@endsection

