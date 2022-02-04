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
            <th style="width: 5%;">ลำดับ</th>
            <th style="width: 10%;">วันที่</th>
            <th style="width: 20%;text-align: right;">ทั่วไป</th>
            <th style="width: 20%;text-align: right;">Express</th>
            <th style="width: 20%;text-align: right;">เหมาคัน</th>
            <th style="width: 25%;text-align: right;">รวมจำนวนเงิน</th>
        </tr>

    </thead>

    <tbody>
       @foreach ($order_date as $date_group => $order_groups)
            <tr>
                <td style="text-align: center">{{ $loop->iteration }}
                </td>
                <td style="text-align: left">
                    {{ $date_group}}
                </td>
                <td style="text-align: right">
                    {{ number_format($order_groups->where('order_type','general')->sum('order_amount'),2,'.',',') }}
                </td>
                <td style="text-align: right">
                   {{ number_format($order_groups->where('order_type','express')->sum('order_amount'),2,'.',',') }}
                </td>
                <td style="text-align: right">
                    {{ number_format($order_groups->where('order_type','charter')->sum('order_amount'),2,'.',',') }}
                </td>
                <td style="text-align: right">
                    {{ number_format($order_groups->sum('order_amount'),2,'.',',') }}
                </td>


            </tr>


        @endforeach
        <tr style="font-weight: bold;">
            <td colspan="2">
                    <strong>
                    รวมทั้งหมด
                    </strong>
            </td>

            <td style="text-align: right">
                {{ number_format($order->where('order_type','general')->sum('order_amount'),2,'.',',') }}
            </td>
            <td style="text-align: right">
                {{ number_format($order->where('order_type','express')->sum('order_amount'),2,'.',',') }}
            </td>
            <td style="text-align: right">
                {{ number_format($order->where('order_type','charter')->sum('order_amount'),2,'.',',') }}
            </td>
            <td style="text-align: right">
                {{ number_format($order->sum('order_amount'),2,'.',',') }}
            </td>



        </tr>
    </tbody>

</table>



@endsection

