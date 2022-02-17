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
            <th style="width: 5%;">ลำดับ</th>
            <th style="width: 10%;text-align: left;">เลขที่ใบรับส่ง</th>
            <th style="width: 15%;text-align: left;">ผู้ส่ง</th>
            <th style="width: 15%;text-align: left;">ผู้รับ</th>
            <th style="width: 10%;text-align: right;">สดต้นทาง</th>
            <th style="width: 10%;text-align: right;">โอนต้นทาง</th>
            <th style="width: 10%;text-align: right;">วางบิลต้นทาง</th>
            <th style="width: 10%;text-align: right;">วางบิลปลายทาง</th>
            <th style="width: 10%;text-align: right;">เก็บปลายทาง</th>
            <th style="width: 5%;text-align: center;">สถานะ</th>
        </tr>

    </thead>

    <tbody>
       @foreach ($order_date as $date_group => $order_groups)
            <tr style="font-weight: bold;background-color:#c0c0c0">
                <td colspan="4" style="text-align: left">
                    วันที่ : {{ $date_group }} จำนวน: {{count($order_groups)}} รายการ รวมจำนเงิน
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
                <td>
                </td>

            </tr>
            @foreach($order_groups->chunk(10) as $chunk)
            @foreach ($chunk as $item )
            <tr style="vertical-align: top">
                <td style="text-align: center">{{ $loop->iteration }}</td>
                <td>{{ $item->order_header_no}}</td>
                <td>{{ $item->customer->name }}</td>

                <td>
                    @isset($item->to_customer->name)
                        {{ $item->to_customer->name }}
                    @endisset

                </td>
                <td style="text-align: right">
                    @if($item->paymenttype == 'H')
                    {{ number_format($item->order_amount,2,'.',',') }}
                    @endif
                </td>
                <td style="text-align: right">
                    @if($item->paymenttype == 'T')
                    {{ number_format($item->order_amount,2,'.',',') }}
                    @endif
                </td>
                <td style="text-align: right">
                    @if($item->paymenttype == 'F')
                    {{ number_format($item->order_amount,2,'.',',') }}
                    @endif
                </td>
                <td style="text-align: right">
                    @if($item->paymenttype == 'L')
                    {{ number_format($item->order_amount,2,'.',',') }}
                    @endif
                </td>
                <td style="text-align: right">
                    @if($item->paymenttype == 'E')
                    {{ number_format($item->order_amount,2,'.',',') }}
                    @endif
                </td>
                <td style="text-align: center">
                    @if($cancelflag == 'false' && $item->order_status == 'cancel')
                            ยกเลิก
                    @endif

                </td>

            </tr>
            @endforeach
            @endforeach
        @endforeach
        <tr style="font-weight: bold;background-color:#c0c0c0">
            <td colspan="2">
                <strong>
                    รวมทั้งหมด - {{count($order)}} รายการ
                </strong>
            </td>
            <td style="text-align: right;font-weight:bold">
                รวมจำนวนเงินทั้งหมด
            </td>
            <td style="text-align: right;font-weight:bold">
                 {{ number_format($order->sum('order_amount'),2,'.',',') }}
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
            <td>
            </td>


        </tr>
    </tbody>

</table>



@endsection

