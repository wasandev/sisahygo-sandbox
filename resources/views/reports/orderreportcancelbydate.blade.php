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
            <th style="width: 10%;">วันที่ใบรับส่ง</th>
            <th style="width: 10%;text-align: center;">เลขที่ใบรับส่ง</th>
            <th style="width: 20%;text-align: center;">ชื่อผู้ส่งสินค้า</th>
            <th style="width: 20%;text-align: center;">ชื่อผู้รับสินค้า</th>
            <th style="width: 10%;text-align: center;">ยกเลิกโดย</th>
            <th style="width: 15%;text-align: center;">วันที่ยกเลิก</th>
            <th style="width: 10%;text-align: right;">จำนวนเงิน</th>
        </tr>

    </thead>

    <tbody>
       @foreach ($order_date as $type_group => $order_groups)
            <tr style="vertical-align: top;font-weight: bold">
                <td colspan="6">
                     @if($type_group == 'H')
                            เงินสดต้นทาง  -  {{count($order_groups) }} รายการ
                        @elseif($type_group == 'T')
                            เงินโอนต้นทาง - {{count($order_groups) }} รายการ
                        @elseif($type_group == 'E')
                            เงินสดปลายทาง - {{count($order_groups) }} รายการ
                        @elseif($type_group == 'F' )
                            วางบิลต้นทาง   - {{count($order_groups) }} รายการ
                        @elseif($type_group == 'L' )
                            วางบิลปลายทาง  - {{count($order_groups) }} รายการ

                        @endif

                </td>

                <td style="text-align: right">
                    รวมจำนวนเงิน
                </td>
                <td style="text-align: right">
                   {{ number_format($order_groups->sum('order_amount'),2,'.',',') }}
                </td>


            </tr>

                @foreach ($order_groups as $order_cancel)

                    <tr>
                        <td style="text-align: center">{{ $loop->iteration }}
                        </td>
                        <td style="text-align: left">
                            {{$order_cancel->order_header_date->format('d/m/Y')}}
                        </td>
                        <td style="text-align: left">
                            {{$order_cancel->order_header_no}}
                        </td>
                        <td style="text-align: left">
                            {{$order_cancel->customer->name}}
                        </td>
                        <td style="text-align: left">
                            {{$order_cancel->to_customer->name}}
                        </td>
                        <td style="text-align: left">
                            {{$order_cancel->user->name}}
                        </td>
                        <td style="text-align: left">
                            {{$order_cancel->created_at}}
                        </td>
                        <td style="text-align: right">
                            {{ number_format($order_cancel->order_amount,2,'.',',') }}
                        </td>
                    </tr>
                @endforeach



        @endforeach
        <tr style="font-weight: bold;">
            <td colspan="6">
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

