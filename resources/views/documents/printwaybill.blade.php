@extends('layouts.doc')

@section('header')
    @include('partials.docheader')
@endsection

@section('content')
    <table style="width:100%;">
        <tr>
            <h2 style="text-align: center;font-size: 1.0em;">
                ใบกำกับสินค้า
            </h2>
        </tr>
    </table>

    <table style="width:100%;border:0px">

        <tr>
            <td style="width: 50%;text-align: left;border:0px">
                เลขที่ใบกำกับสินค้า: {{ $waybill->waybill_no }} สาขาปลายทาง: {{ $waybill->to_branch->name }} <br />
                ทะเบียนรถ: {{ $waybill->car->car_regist }}<br />
                เจ้าของรถ:
                @isset($waybill->car->owner->name)
                    {{ $waybill->car->owner->name }}
                @endisset
                พนักงานขับรถ:
                @isset($waybill->driver->name)
                    {{ $waybill->driver->name }}
                @endisset

            </td>
            <td style="width:50%;vertical-align: middle;border:0px">
                วันที่ใบกำกับ:{{ $waybill->waybill_date }}<br />
                วันที่-เวลารถออกจากต้นทาง :{{ $waybill->departure_at }}<br />
                วันที่-เวลาถึงปลายทาง :{{ $waybill->arrival_at }}

            </td>
        </tr>

    </table>
    <br />

    <table style="width: 100%">
        <thead>
            <tr>
                <th style="width: 5%;">ลำดับ</th>
                <th style="width: 15%;">ใบรับส่งสินค้า</th>
                <th style="text-align: left;width: 25%;">ผู้ส่งสินค้า</th>
                <th style="text-align: left;width: 25%;">ผู้รับสินค้า</th>
                <th style="width: 5%;">จำนวน</th>
                <th style="text-align: right;width: 8%;">เก็บสด</th>
                <th style="text-align: right;width: 7%;">วางบิล</th>
                <th style="text-align: right;width: 10%;">ปลายทาง</th>
            </tr>

        </thead>

        <tbody>
            @foreach ($order_district as $district => $order_groups)
                <tr>
                    <td colspan="8" style="border-top: 1.0px solid black;">

                        {{ $district }} จำนวนใบรับส่ง {{ count($order_groups) }} รายการ ยอดค่าขนส่ง =
                        {{ number_format($order_groups->sum('order_amount'), 2, '.', ',') }}

                    </td>

                </tr>
                @foreach ($order_groups as $item)
                    <tr>
                        <td style="text-align: center">
                            {{ $loop->iteration }}
                        </td>
                        <td>
                            {{ $item->order_header_no }}
                        </td>
                        <td style="word-wrap: break-word;">
                            @isset($item->customer)
                                {{ $item->customer->name }}
                            @endisset
                        </td>
                        <td style="word-wrap: break-word;">
                            @isset($item->to_customer)
                                {{ $item->to_customer->name }}
                            @endisset
                        </td>
                        <td style="text-align: center">
                            {{ $item->order_details->sum('amount') }}
                        </td>
                        <td style="text-align: right">
                            @if ($item->paymenttype === 'H' || $item->paymenttype === 'T')
                                {{ number_format($item->order_amount, 2, '.', ',') }}
                            @endif
                        </td>

                        <td style="text-align: right">
                            @if ($item->paymenttype === 'F' || $item->paymenttype === 'L')
                                {{ number_format($item->order_amount, 2, '.', ',') }}
                            @endif
                        </td>
                        <td style="text-align: right">
                            @if ($item->paymenttype === 'E')
                                {{ number_format($item->order_amount, 2, '.', ',') }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endforeach
            <tr>

                <td colspan="5">รวมจำนวนใบรับส่งทั้งหมด {{ count($order) }} รายการ</td>

                <td style="text-align: right">
                    {{ number_format($order->whereIn('paymenttype', ['H', 'T'])->sum('order_amount'), 2, '.', ',') }}</td>
                <td style="text-align: right">
                    {{ number_format($order->whereIn('paymenttype', ['F', 'L'])->sum('order_amount'), 2, '.', ',') }}</td>
                <td style="text-align: right">
                    {{ number_format($order->where('paymenttype', '=', 'E')->sum('order_amount'), 2, '.', ',') }}</td>

            </tr>
        </tbody>

    </table>
    <br>
    <table>
        <tr>
            <td style="width: 25%">
                ค่าระวาง:
            </td>
            <td style="width: 25%;text-align: right">
                {{ number_format($waybill->waybill_amount, 2, '.', ',') }} บาท

            </td>
            <td style="width: 25%;text-align: right">
                ผู้ส่งมอบสินค้า:

            </td>
            <td style="width: 25%;text-align: right">

            </td>
        </tr>
        <tr>
            <td style="width: 25%">
                ค่าบรรทุก:
            </td>
            <td style="width: 25%;text-align: right">
                {{ number_format($waybill->waybill_payable, 2, '.', ',') }} บาท

            </td>
            <td style="width: 25%;text-align: right">
                พนักงานขับรถ:

            </td>
            <td style="width: 25%;text-align: right">

            </td>
        </tr>

        <tr>
            <td style="width: 25%">

            </td>
            <td style="width: 25%;text-align: right">


            </td>
            <td style="width: 25%;text-align: right">
                ผู้รับมอบสินค้า(สาขา):

            </td>
            <td style="width: 25%;text-align: right">

            </td>
        </tr>
        <tr>
            <td colspan="4" style="width: 100">
                เวลาพิมพ์ใบกำกับ : {{ date('d-m-Y H:i:s') }}
            </td>
        </tr>

    </table>
@endsection
