@extends('layouts.doclandscapenojs')

@section('header')
    @include('partials.reportheader')

@endsection

@section('content')

<table style="width:100%;">

    <tr>
        <td style="width: 50%;text-align: left;border:0px">
            <strong>
            สาขา {{ $branchdata->name }}
            @if($cancelflag == 'true')
                **ไม่รวมรายการยกเลิก**
            @else
                **รวมรายการยกเลิก**
            @endif
            </strong>
        </td>
        <td style="width:50%;text-align: right;border:0px">
            <strong>
            ระหว่างวันที่: {{date("d-m-Y", strtotime($from))}}<br/> ถึงวันที่: {{date("d-m-Y", strtotime($to))}}<br/>

            </strong>
        </td>
    </tr>

</table>
<br/>

<table style="width: 100%;" >
    <thead>
        <tr>

            <th style="width: 25%;text-align: center;">วันที่</th>
            <th style="width: 15%;text-align: right;">สดต้นทาง</th>
            <th style="width: 15%;text-align: right;">โอนต้นทาง</th>
            <th style="width: 15%;text-align: right;">วางบิลต้นทาง</th>
            <th style="width: 15%;text-align: right;">วางบิลปลายทาง</th>
            <th style="width: 15%;text-align: right;">เก็บปลายทาง</th>

        </tr>

    </thead>

    <tbody>
       @foreach ($order_date as $date_group => $order_groups)
            <tr style="font-weight: bold;">
                <td  style="text-align: left">
                    {{date("d-m-Y", strtotime($date_group))}} จำนวน: {{count($order_groups)}} รายการ
                </td>
                <td style="text-align: right">
                    {{ number_format($order_groups->where('paymenttype','H')->sum('order_amount'),2,'.',',') }}
                </td>
                <td style="text-align: right">
                    {{ number_format($order_groups->where('paymenttype','T')->sum('order_amount'),2,'.',',') }}
                </td>
                <td style="text-align: right">
                   {{ number_format($order_groups->where('paymenttype','F')->sum('order_amount'),2,'.',',') }}
                </td>
                <td style="text-align: right">
                    {{ number_format($order_groups->where('paymenttype','L')->sum('order_amount'),2,'.',',') }}
                </td>
                <td style="text-align: right">
                    {{ number_format($order_groups->where('paymenttype','E')->sum('order_amount'),2,'.',',') }}
                </td>


            </tr>

        @endforeach
        <tr style="font-weight: bold;background-color:#c0c0c0">
            <td>
                <strong>
                    รวมทั้งหมด - {{count($order)}} รายการ <br/>
                    รวมจำนวนเงินทั้งหมด  {{ number_format($order->sum('order_amount'),2,'.',',') }} </strong>

            </td>
            <td style="text-align: right">
                {{ number_format($order->where('paymenttype','H')->sum('order_amount'),2,'.',',') }}
            </td>
            <td style="text-align: right">
                {{ number_format($order->where('paymenttype','T')->sum('order_amount'),2,'.',',') }}
            </td>
            <td style="text-align: right">
                {{ number_format($order->where('paymenttype','F')->sum('order_amount'),2,'.',',') }}
            </td>
            <td style="text-align: right">
                {{ number_format($order->where('paymenttype','L')->sum('order_amount'),2,'.',',') }}
            </td>
            <td style="text-align: right">
                {{ number_format($order->where('paymenttype','E')->sum('order_amount'),2,'.',',') }}
            </td>



        </tr>
    </tbody>

</table>



@endsection

