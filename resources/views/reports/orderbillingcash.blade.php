@extends('layouts.docnojs')

@section('header')
    @include('partials.reportheader')

@endsection

@section('content')
<table style="width:100%;">
    <tr>
        <h2 style="text-align: center;font-size: 1.0em;">

        </h2>
    </tr>
</table>
<table style="width:100%;">

    <tr>
        <td style="width: 50%;text-align: left;vertical-align:top">
            <strong>
            สาขา {{ $branchdata->name }}
            </strong>
        </td>
        <td style="width:50%;text-align: right;vertical-align: middle;">
            <strong>
            วันที่: {{ date("d-m-Y", strtotime($orderdate))}}<br/>
            </strong>
        </td>
    </tr>

</table>


<table style="width:100%;border-bottom:1px solid black;border-top:1px solid black" >
    <thead>
        <tr style="border-bottom:1px solid black">
            <th style="width: 5%;">ลำดับ</th>
            <th style="width: 20%;text-align: left ">ชื่อพนักงาน</th>
            <th style="width: 10%;">จำนวนใบรับส่งเงินสด</th>
            <th style="text-align: right;width: 15%;">จำนวนเงิน</th>
            <th style="text-align: right;width: 15%;">จำนวนเงินยกเลิก</th>
            <th style="text-align: right;width: 15%;">จำนวนเงินสดรับ</th>

            <th style="text-align: center;width: 20%;">ลงชื่อพนักงาน</th>
        </tr>

    </thead>

    <tbody style="vertical-align: middle;">
       @foreach ($order_user as $user_cash => $order_groups)
            <tr style="height: 1cm">
                <td style="text-align: center">
                    {{ $loop->iteration }}
                </td>
                <td style="text-align: left">
                    {{$user_cash}}
                </td>
                <td style="text-align: center">
                    {{count($order_groups)}}
                </td>
                <td style="text-align: right">
                     {{ number_format($order_groups->sum('order_amount'),2,'.',',')}}

                </td>
                <td style="text-align: right">
                        @php
                            $cancel_amount =  $order_groups->where('order_status','cancel')->sum('order_amount')
                        @endphp
                     {{ number_format($cancel_amount,2,'.',',')}}

                </td>
                <td style="text-align: right">

                     {{ number_format($order_groups->sum('order_amount')-$cancel_amount,2,'.',',')}}

                </td>

                <td style="text-align: center">
                     .............................................
                </td>

            </tr>

        @endforeach
        <tr style="padding-top: 10px;border-top: 1px solid black;vertical-align: middle;">
            <td colspan="2">
                    รวม
            </td>

             <td style="text-align: center">
                {{count($order) }}
            </td>

            <td style="text-align: right">
                {{number_format($order->sum('order_amount'),2,'.',',') }}
            </td>
            <td style="text-align: right">
                 @php
                    $sumcancel_amount =  $order->where('order_status','cancel')->sum('order_amount')
                @endphp
                {{number_format($sumcancel_amount,2,'.',',') }}
            </td>
            <td style="text-align: right">

                {{number_format($order->sum('order_amount') - $sumcancel_amount,2,'.',',') }}
            </td>
            <td>
            </td>
        </tr>
    </tbody>

</table>
<br>
<table style="width: 100%;height:2cm;border: 1px solid black;padding: 10px">
    <tr>
        <td style="width: 50%">
            1. รวมจำนวนเงินสดรับ: {{number_format($order->sum('order_amount') - $sumcancel_amount ,2,'.',',') }} บาท<br/>
            2. รวมภาษีหัก ณ ที่จ่าย: ............................................<br/>
            3. รวมเงินสดรับสุทธิ (1-2) ...........................................<br/>
        </td>
        <td style="width: 50%;text-align: center">
            .......................................<br/>
                            ฝ่ายการเงิน   <br/>
                ......../........./............<br/>


        </td>

    </tr>

</table>


@endsection

