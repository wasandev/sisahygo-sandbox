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
            <br/>
            @if ($artype == 'F')
                วางบิลต้นทาง
            @elseif ($artype =='L')
                วางบิลปลายทาง
            @elseif ($artype =='E')
                เก็บเงินปลายทาง
            @else
                ทุกประเภท
            @endif
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
            <th style="width: 15%;text-align: center;">เลขที่ใบรับส่ง</th>
            <th style="width: 35%;text-align: center;">ชื่อลูกค้า</th>
            <th style="width: 20%;text-align: right;">จำนวนเงิน</th>
            <th style="width: 15%;text-align: center;">หมายเหตุ</th>

        </tr>

    </thead>

    <tbody>
       @foreach ($order_date as $date_group => $order_groups)
            <tr style="font-weight: bold;background-color:#c0c0c0">

                <td colspan="2" style="text-align: center;">
                    {{ date('d/m/Y',strtotime($date_group)) }}
                </td>

                <td style="text-align: center">
                    {{count($order_groups) }} รายการ
                </td>

                <td style="text-align: center">
                    รวมจำนวนเงิน
                </td>
                <td style="text-align: right">
                   {{ number_format($order_groups->sum('order_amount'),2,'.',',') }}
                </td>
                <td>
                </td>


            </tr>
            @foreach ($order_groups as $item_cr )
                <tr>

                    <td colspan="2" style="text-align: center">{{ $loop->iteration }}
                    </td>

                    <td style="text-align: left">
                        {{$item_cr->order_header_no}}
                    </td>
                    <td style="text-align: left">
                        {{$item_cr->customer->name}}
                    </td>



                    <td style="text-align: right">
                        {{ number_format($item_cr->order_amount,2,'.',',') }}
                    </td>
                    <td>
                        @if ($item_cr->order_status == 'cancel')
                            ยกเลิก
                        @endif
                    </td>
                </tr>
            @endforeach


        @endforeach
        <tr style="font-weight: bold;background-color:#c0c0c0">
            <td colspan="3">
                รวมทั้งหมด {{count($order)}} รายการ
            </td>
            <td style="text-align: right">
                รวมจำนวนเงิน
            </td>
            <td style="text-align: right">
                {{ number_format($order->sum('order_amount'),2,'.',',') }}
            </td>

            <td></td>

        </tr>
    </tbody>

</table>



@endsection

