@extends('layouts.doc')

@section('header')
    @include('partials.docheader')

@endsection

@section('content')
<table style="width:100%;padding: 0px;margin: 0px;">
    <tr>
        <p style="text-align: center;font-size: 1.5em;padding: 0px">
            ใบจัดส่งสินค้า
        </p>
    </tr>
</table>
<table>

    <tr>
        <td style="width: 50%">
            เลขที่ใบจัดส่ง: {{ $delivery->delivery_no }}<br />
            สาขา: {{$delivery->branch->name }}<br/>
            ทะเบียนรถ: {{ $delivery->car->car_regist }}<br />
            พนักงานขับรถ: {{ $delivery->driver->name}}<br/>
            จัดส่งโดย:
            @if($delivery->delivery_type === '1')
                รถบรรทุกจัดส่ง
            @else
                สาขาจัดส่ง
            @endif
        </td>
        <td style="width:50%;vertical-align: top;">
            วันที่: {{ $delivery->delivery_date }}<br />
            ยอดเก็บเงินปลายทาง: {{ number_format($delivery->receipt_amount,2,'.',',') }}<br />
            พนักงานจัดส่ง:
            @isset($delivery->sender->name)
                {{ $delivery->sender->name }}
            @endisset
            <br/>
            เส้นทางจัดส่ง: {{ $delivery->branch_route->name}}<br/>
            รายละเอียด/หมายเหตุ: {{$delivery->remark}}

        </td>


    </tr>


</table>


<table style="border-bottom: 1px solid black;border-top: 1px solid black;padding: 5px;" cellspacing="3" cellpadding="5">
    <thead>
        <tr>
            <th style="width: 5%";>ลำดับ</th>
            <th style="text-align: left;width: 10%;">อำเภอ</th>
            <th style="text-align: left;width: 25%">ผู้รับสินค้า</th>
            <th style="width: 10%;">จำนวนรายการ</th>
            <th style="text-align: left;width: 15%;">เลขที่ใบรับส่ง</th>
            <th style="width: 10%;">ยอดจัดเก็บ</th>
            <th style="width: 10%;">การจัดส่ง</th>
            <th style="width: 10%;">การเก็บเงิน</th>
        </tr>

    </thead>

    <tbody style="border-top: 1px solid black;vertical-align: top;">
        @foreach ($delivery_district as $district => $item_groups)
            <tr>
                <td style="border-bottom: 1px solid black;"></td>
                <td style="border-bottom: 1px solid black;">
                    <strong>
                    {{$district}}
                    </strong>
                </td>
                <td style="border-bottom: 1px solid black;"></td>
                <td style="text-align: center;border-bottom: 1px solid black;">
                    <strong>
                    {{count($item_groups)}} ผู้รับ
                    </strong>
                </td>
                <td style="border-bottom: 1px solid black;"></td>
                <td  style="text-align: right;border-bottom: 1px solid black;">
                    <strong>
                    {{ number_format($item_groups->sum('payment_amount'),2,'.',',')}}
                    </strong>
                </td>
                <td style="border-bottom: 1px solid black;"></td>
                <td style="border-bottom: 1px solid black;"></td>

            </tr>
            @foreach ($item_groups as $item )
            <tr style="border-bottom: 1px solid black;vertical-align: top;">
                <td style="text-align: center">
                    {{ $loop->iteration }}
                </td>
                <td>

                </td>

                <td style="word-wrap: break-word;">
                    {{$item->customer->name}}
                </td>
                 <td style="word-wrap: break-word;text-align: center">
                    {{count($item->delivery_details)}} ใบรับส่ง
                </td>
                <td>
                </td>
                <td style="text-align: right">
                    @if($item->payment_amount > 0)
                        {{number_format($item->payment_amount,2,'.',',')}}
                    @else
                    -
                    @endif
                </td>
                <td style="horizontal-align: center">

                    @if($item->delivery_status)
                        <input type="checkbox"  name="delivery_status" value="{{$item->delivery_status}}" checked>
                    @else
                        <input  type="checkbox"  name="delivery_status" value="{{$item->delivery_status}}">
                    @endif

                </td>
                 <td style="text-align: center">
                   @if($item->payment_status)
                        <input type="checkbox"  name="payment_status" value="{{$item->payment_status}}" checked>
                    @else
                        <input type="checkbox"  name="payment_status" value="{{$item->payment_status}}">
                    @endif

                </td>
            </tr>
                @foreach ($item->delivery_details as $detail )
                    <tr>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                            {{$detail->branchrec_order->order_header_no}}
                        </td>
                       <td style="text-align: right">
                            @if($detail->branchrec_order->paymenttype === "E")
                              {{number_format($detail->branchrec_order->order_amount,2,'.',',')}}
                            @else
                             -
                            @endif
                            </td>
                        <td>

                        </td>
                        <td>

                        </td>
                    </tr>
                @endforeach
            @endforeach
        @endforeach
        <tr style="padding-top: 20px;border-top: 1px solid black;vertical-align: middle;font-size:18px;font-weight: bold;">

            <td colspan="4">รวมรายการจัดส่งทั้งหมด {{count($delivery->delivery_items) }} รายการ</td>
            {{-- <td colspan="4">รวมรายการใบรับส่งทั้งหมด {{count($delivery->delivery_details) }} รายการ</td> --}}

        </tr>
    </tbody>

</table>
<br>
<table style="padding: 10px;" >
    <tr>
        <td style="width:50%;vertical-align: top;font-size:18px;font-weight: bold;">
            พนักงานขับรถ.......................................<br/>
            ผู้จัดการ............................................<br/>

        </td>
        <td style="width:50%;vertical-align: top;text-align: right;font-size:18px;font-weight: bold;">

            ผู้ทำรายการ.........................................<br/>
            พนักงานจัดส่ง.......................................<br/>


        </td>
    </tr>

</table>


@endsection
