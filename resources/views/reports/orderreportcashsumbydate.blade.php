@extends('layouts.docnojs')

@section('header')
    @include('partials.reportheader')

@endsection

@section('content')

<table style="width:100%;">

    <tr>
        <td style="width: 50%;text-align: left;border:0px">
            <strong>
            สาขา {{ $branchdata->name }}<br/>
            @if($cancelflag == 'true')
                **ไม่รวมรายการยกเลิก**
            @else
                **รวมรายการยกเลิก**
            @endif
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
            <th style="width: 15%;">วันที่</th>
            <th style="width: 15%;">จำนวนรายการ</th>
            <th style="width: 65%;text-align: right;">จำนวนเงิน</th>
        </tr>

    </thead>

    <tbody>
       @foreach ($order_date as $date_group => $order_groups)
            <tr>
                <td style="text-align: center">{{ $loop->iteration }}
                </td>
                <td style="text-align: center">
                    {{ date('d/m/Y',strtotime($date_group)) }}
                </td>

                <td style="text-align: center">
                    {{count($order_groups) }}
                </td>
                <td style="text-align: right">
                   {{ number_format($order_groups->sum('order_amount'),2,'.',',') }}
                </td>


            </tr>



        @endforeach
        <tr style="font-weight: bold;">
            <td colspan="2">
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

