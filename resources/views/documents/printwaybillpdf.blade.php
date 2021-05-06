@extends('layouts.pdfdoc')

@section('header')
    @include('partials.docheaderpdf')

@endsection

@section('content')
<table style="width:100%;">
    <tr>
        <h2 style="text-align: center;font-size: 2.0em;">
            ใบกำกับสินค้า
        </h2>
    </tr>
</table>
<table>

    <tr>
        <td>
            เลขที่ใบกำกับสินค้า: {{ $waybill->waybill_no }}<br />
            ทะเบียนรถ: {{ $waybill->car->car_regist }}<br />
            เจ้าของรถ:
            @isset($waybill->car->owner->name)
                {{ $waybill->car->owner->name }}
            @endisset
        </td>
        <td style="width:50%;vertical-align: top;">
            วันที่ใบกำกับ:{{ $waybill->waybill_date }}<br />
            วันที่-เวลารถออกจากต้นทาง :{{ $waybill->departure_at }}<br />
            วันที่-เวลาถึงปลายทาง :{{ $waybill->arrival_at }}

        </td>
    </tr>

</table>


<table style="border-bottom: 1px solid black;border-top: 1px solid black;padding: 5px;" cellspacing="3" cellpadding="5">
    <thead>
        <tr>
            <th style="width: 5%">ลำดับ</th>
            <th style="width: 15%">ใบรับส่งสินค้า</th>
            <th style="text-align: left;width: 25%">ผู้ส่งสินค้า</th>
            <th style="text-align: left;width: 25%">ผู้รับสินค้า</th>
            <th style="width: 5%">จำนวน</th>
            <th style="width: 8%">เก็บสด</th>
            <th style="width: 7%">วางบิล</th>
            <th style="width: 10%">ปลายทาง</th>
        </tr>

    </thead>

    <tbody style="border-top: 1px solid black;vertical-align: top;">
        @foreach ($order_district as $district => $order_groups)
            <tr>
                <td colspan="8" style="border-bottom: 1px solid black;">
                    <strong>
                    {{$district}} จำนวนใบรับส่ง  {{count($order_groups)}} รายการ ยอดค่าขนส่ง = {{ number_format($order_groups->sum('order_amount'),2,'.',',')}}
                    </strong>
                </td>

            </tr>
            @foreach ($order_groups as $item )
            <tr>
                <td style="text-align: center">
                    {{ $loop->iteration }}
                </td>
                <td>
                    {{$item->order_header_no}}
                </td>
                <td style="word-wrap: break-word;">
                    {{$item->customer->name}}
                </td>
                <td style="word-wrap: break-word;">
                    {{$item->to_customer->name}}
                </td>
                <td style="text-align: center">
                    {{$item->order_details->sum('amount')}}
                </td>
                <td style="text-align: right">
                @if($item->paymenttype === 'H' || $item->paymenttype === 'T')
                    {{number_format($item->order_amount,2,'.',',') }}
                @endif
                </td>

                <td style="text-align: right">
                @if($item->paymenttype === 'F' || $item->paymenttype === 'L' )
                    {{number_format($item->order_amount,2,'.',',') }}
                    @endif
                </td>
                <td style="text-align: right">
                @if($item->paymenttype === 'E'  )
                    {{number_format($item->order_amount,2,'.',',') }}
                @endif
                </td>
            </tr>
            @endforeach
        @endforeach
        <tr style="padding-top: 20px;border-top: 1px solid black;vertical-align: top;font-size:18px;font-weight: bold;">

            <td colspan="5">รวมจำนวนใบรับส่งทั้งหมด {{count($order) }} รายการ</td>

            <td style="text-align: right">{{number_format($order->where('paymenttype','=','H')->sum('order_amount'),2,'.',',') }}</td>
            <td style="text-align: right">{{number_format($order->whereIn('paymenttype',['F','L'])->sum('order_amount'),2,'.',',') }}</td>
            <td style="text-align: right">{{number_format($order->where('paymenttype','=','E')->sum('order_amount'),2,'.',',') }}</td>

        </tr>
    </tbody>

</table>
<br>
<table style="width:100%;height:3.0cm;padding:20px;">
    <tr style="width:100%;">
        <td style="width:50%;vertical-align: top;font-weight: bold;text-align: left">
            ค่าระวาง: {{ number_format($waybill->waybill_amount,2,'.',',') }}<br />
            ค่าบรรทุก: {{ number_format($waybill->waybill_payable ,2,'.',',')}}<br />


        </td>
        <td style="width:50%;vertical-align: top;font-weight: bold;text-align: left">
            ผู้ส่งมอบสินค้า:........................................................<br />
            ผู้รับมอบสินค้า(สาขา):..................................................<br />
            พนักงานขับรถ:........................................................<br />

        </td>
    </tr>


</table>


@endsection
