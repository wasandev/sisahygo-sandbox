@extends('layouts.forma4')

@section('header')
    @include('partials.reportheader')
@endsection

@section('content')
    {{-- <table style="width: 100%;border: 0px;">
    <tr>
       <td style="width: 100%;text-align: center;vertical-align:top;border:0px;">
              <h2>ใบจัดส่งสินค้า</h2>
        </td>
    </tr>
</table> --}}
    <br />
    <table style="width: 100%">

        <tr>
            <td style="width: 50%;vertical-align:top;padding: 10px;">

                สาขา: {{ $delivery->branch->name }}<br />
                จัดส่งโดย:
                @if ($delivery->delivery_type === '1')
                    รถบรรทุกจัดส่ง
                @else
                    สาขาจัดส่ง
                @endif
                <br />
                ทะเบียนรถ: {{ $delivery->car->car_regist }}<br />
                พนักงานขับรถ: {{ $delivery->driver->name }}<br />
                พนักงานจัดส่ง:
                @isset($delivery->sender->name)
                    {{ $delivery->sender->name }}
                @endisset
                <br />


            </td>
            <td style="width: 50%;vertical-align:top;padding: 10px;">
                เลขที่ใบจัดส่ง: {{ $delivery->id }}<br />
                วันที่: {{ $delivery->delivery_date->format('d/m/Y') }}<br />
                เส้นทางจัดส่ง: {{ $delivery->branch_route->name }}<br />
                รายละเอียด/หมายเหตุ: {{ $delivery->remark }}<br />
                <strong>ยอดเก็บเงินปลายทาง: {{ number_format($delivery->receipt_amount, 2, '.', ',') }}</strong><br />

            </td>


        </tr>


    </table>
    <br />

    <table style="width: 100%;border: 0.5px soild black;">
        <thead>
            <tr style="vertical-align:middle;font-weight: bold;height:1cm">
                <td style="width: 5%;text-align:center;">ลำดับ</td>
                <td style="width: 15%;text-align: center;">อำเภอ</td>
                <td style="width: 25%;text-align: center;">ผู้รับสินค้า</td>
                <td style="width: 10%;text-align: center">จำนวนรายการ</td>
                <td style="width: 10%;text-align: center;">เลขที่ใบรับส่ง</td>
                <td style="width: 10%;text-align: center;">ค่าขนส่ง</td>
                <td style="width: 15%;text-align: center;">ยอดจัดเก็บ</td>
                <td style="width: 5%;text-align: center;">การจัดส่ง</td>
                <td style="width: 5%;text-align: center;">การเก็บเงิน</td>
            </tr>
        </thead>
        @foreach ($delivery_district as $district => $item_groups)
            <tr style="vertical-align:top;font-weight:bold">
                <td></td>
                <td style="text-align:left">
                    {{ $district }}
                </td>
                <td></td>
                <td style="text-align: center">
                    {{ count($item_groups) }} ผู้รับ
                </td>
                <td></td>
                <td style="text-align: right;">
                    {{ number_format($item_groups->sum('payment_amount'), 2, '.', ',') }}
                </td>
                <td></td>
                <td></td>
                <td></td>

            </tr>
            @foreach ($item_groups as $item)
                <tr>
                    <td style="text-align: center">
                        {{ $loop->iteration }}
                    </td>
                    <td>

                    </td>

                    <td>
                        {{ $item->customer->name }}
                    </td>
                    <td style="text-align: center">
                        {{ count($item->delivery_details) }} ใบรับส่ง
                    </td>
                    <td>
                    </td>
                    <td style="text-align: right">
                        @if ($item->payment_amount > 0)
                            {{ number_format($item->payment_amount, 2, '.', ',') }}
                        @else
                            -
                        @endif
                    </td>

                    <td>

                    </td>
                    <td style="text-align: center">

                        @if ($item->delivery_status)
                            <input type="checkbox" name="delivery_status" value="{{ $item->delivery_status }}" checked>
                        @else
                            <input type="checkbox" name="delivery_status" value="{{ $item->delivery_status }}">
                        @endif

                    </td>
                    <td style="text-align: center">
                        @if ($item->payment_amount > 0)
                            @if ($item->payment_status)
                                <input type="checkbox" name="payment_status" value="{{ $item->payment_status }}" checked>
                            @else
                                <input type="checkbox" name="payment_status" value="{{ $item->payment_status }}">
                            @endif
                        @endif

                    </td>
                </tr>
                @foreach ($item->delivery_details as $detail)
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
                            {{ $detail->branchrec_order->order_header_no }}
                        </td>
                        <td style="text-align: right">
                            @if ($detail->branchrec_order->paymenttype === 'E')
                                {{ number_format($detail->branchrec_order->order_amount, 2, '.', ',') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>


                        </td>
                        <td>

                        </td>
                        <td>

                        </td>
                    </tr>
                @endforeach
            @endforeach
        @endforeach
        <tr style="font-weight: bold">

            <td colspan="5" style="text-align: center">รวมรายการจัดส่งทั้งหมด {{ count($delivery->delivery_items) }}
                รายการ ยอดเก็บปลายทาง รวม</td>

            <td style="text-align: right">{{ number_format($delivery->receipt_amount, 2, '.', ',') }}</td>
            <td></td>
            <td></td>
            <td></td>

        </tr>


    </table>
    <br />
    <table style="width: 100%">
        <tr>
            <td style="width:25%;vertical-align: top;text-align:center;padding: 10px;">
                <br />
                .......................................<br />
                ผู้ทำรายการ<br />


            </td>
            <td style="width:25%;vertical-align: top;text-align:center;padding: 10px;">

                <br />
                .......................................<br />
                พนักงานขับรถ<br />


            </td>
            <td style="width:25%;vertical-align: top;text-align:center;padding: 10px;">

                <br />
                .......................................<br />
                พนักงานจัดส่งสินค้า<br />


            </td>
            <td style="width:25%;vertical-align: top;text-align:center;padding: 10px;">

                <br />
                .......................................<br />
                ผู้จัดการสาขา<br />

            </td>
        </tr>
        <tr>
            <td style="width:25%;vertical-align: top;text-align:center;padding: 10px;">
                <br />
                @if ($delivery->mile_start_number > 0)
                    {{ $delivery->mile_start_number }} <br />
                    เลขไมล์เริ่มต้น
                @else
                    .......................................<br />
                    เลขไมล์เริ่มต้น
                @endif

            </td>
            <td style="width:25%;vertical-align: top;text-align:center;padding: 10px;">

                <br />
                @if ($delivery->mile_end_number > 0)
                    {{ $delivery->mile_end_number }} <br />
                    เลขไมล์สิ้นสุด
                @else
                    .......................................<br />
                    เลขไมล์สิ้นสุด
                @endif


            </td>
            <td style="width:25%;vertical-align: top;text-align:center;padding: 10px;">

                <br />
                @if ($delivery->delivery_mile > 0)
                    {{ $delivery->delivery_mile }} <br />
                    รวมระยะทาง
                @else
                    .......................................<br />
                    รวมระยะทาง
                @endif


            </td>
            <td style="width:25%;vertical-align: top;text-align:center;padding: 10px;">




            </td>
        </tr>

    </table>
@endsection
