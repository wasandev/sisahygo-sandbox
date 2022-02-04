@extends('layouts.docnojs')

@section('header')
    @include('partials.reportheader')

@endsection

@section('content')

<table style="width:100%;">

    <tr>
        <td style="width: 50%;text-align: left;border:0px">
            <strong>
            สาขา {{ $branchdata->name }}
            </strong>
        </td>
        <td style="width:50%;text-align: right;border:0px">
            <strong>
            ระหว่างวันที่: {{date("d-m-Y", strtotime($from))}}<br/> ถึงวันที่: {{date("d-m-Y", strtotime($to))}}

            </strong>
        </td>
    </tr>

</table>
<br/>

<table style="width: 100%;" >
    <thead>
        <tr>
            <th style="width: 10%;">วันที่</th>
            <th style="width: 5%;">ลำดับ</th>
            <th style="width: 10%;text-align: center;">เลขที่ใบรับส่ง</th>
            <th style="width: 35%;text-align: center;">ชื่อลูกค้า</th>
            <th style="width: 15%;text-align: center;">ประเภท</th>
            <th style="width: 25%;text-align: right;">จำนวนเงิน</th>
        </tr>

    </thead>

    <tbody>
       @foreach ($order_date as $date_group => $order_groups)
            <tr style="font-weight:bold;">

                <td style="text-align: center;">
                    {{ date('d/m/Y',strtotime($date_group)) }}
                </td>
                <td></td>
                <td style="text-align: center">
                    {{count($order_groups) }} รายการ
                </td>
                <td></td>
                <td style="text-align: center">
                    รวมจำนวนเงิน
                </td>
                <td style="text-align: right">
                   {{ number_format($order_groups->sum('order_amount'),2,'.',',') }}
                </td>


            </tr>
            @foreach ($order_groups as $item_cr )
                <tr>
                    <td style="text-align: left">
                        {{-- {{$item_cr->order_header_date->format('d/m/Y')}} --}}
                    </td>
                    <td style="text-align: center">{{ $loop->iteration }}
                    </td>

                    <td style="text-align: left">
                        {{$item_cr->order_header_no}}
                    </td>
                    <td style="text-align: left">
                        {{$item_cr->customer->name}}
                    </td>

                    <td style="text-align: center">
                        @if($item_cr->paymenttype == 'F' ||$item_cr->paymenttype == 'L' )
                            วางบิล
                        @elseif($item_cr->paymenttype == 'E')
                            เก็บเงินปลายทาง

                        @endif
                    </td>

                    <td style="text-align: right">
                        {{ number_format($item_cr->order_amount,2,'.',',') }}
                    </td>
                </tr>
            @endforeach


        @endforeach
        <tr style="font-weight: bold;">
            <td colspan="4">
                รวมทั้งหมด {{count($order)}} รายการ
            </td>
            <td style="text-align: right">
                รวมจำนวนเงิน
            </td>
            <td style="text-align: right">
                {{ number_format($order->sum('order_amount'),2,'.',',') }}
            </td>



        </tr>
    </tbody>

</table>



@endsection

