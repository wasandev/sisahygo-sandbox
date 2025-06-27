@extends('layouts.form')

@section('header')
    @include('partials.orderheader_nohead')
@endsection
@section('content')

    <table style="width: 100%;margin-top: -10px">
        <tr>

            <td style="width: 100%;text-align: right;vertical-align:top">
                @if ($order->paymenttype == 'H' || $order->paymenttype == 'T')
                    <h3>ใบเสร็จรับเงิน - จ่ายเงินแล้ว</h3>
                @elseif($order->paymenttype == 'E')
                    <h3>เก็บเงินปลายทาง</h3>
                @else
                    <h3>วางบิล</h3>
                @endif
            </td>
        </tr>
    </table>

    <table style="width: 100%;">
        <tr>
            <td style="width: 68%;text-align: left;">
                Ref ID: {{ $order->id }} &nbsp;

                @switch($order->branch_rec_id)
                    @case(5)
                        ไทยพานิชย์ หจก.บุญณรงค์รุ่งเรืองทรัพย์ เลขที่:699-2-39128-8
                    @break

                    @case(7)
                        ไทยพานิชย์ หจก.สี่สหายขนส่ง(บ้านนาสาร)2016 เลขที่:898-2-29369-6
                    @break

                    @case(11)
                        ไทยพานิชย์ หจก.สี่สหายขนส่ง(บ้านนาสาร) 2016 เลขที่:898-2-29369-6
                    @break

                    @case(12)
                        ไทยพานิชย์ หจก.บุญณรงค์รุ่งเรืองทรัพย์ เลขที่:699-2-39128-8
                    @break

                    @case(13)
                        กสิกรไทย สาขาตรัง หจก.สี่สหายขนส่งตรัง-พัทลุง เลขที่:054-1-87635-9
                    @break

                    @default
                        กสิกรไทย สาขาบางมด บจก.สี่สหายขนส่ง(1988) เลขที่:090-1-00410-2
                @endswitch

            </td>
            <td style="width: 1%;text-align: center;">
            </td>
            <td style="width: 1%;text-align: center;">
            </td>
            <td style="width: 10%;text-align: right;">
                @switch($order->order_type)
                    @case('general')
                        ทั่วไป
                    @break

                    @case('express')
                        Express
                    @break

                    @case('charter')
                        เหมาคัน
                    @break
                @endswitch
            </td>
            <td style="width: 20%;text-align: center;vertical-align:middle">
                @if ($order->waybill_id != '')
                    {{ $order->waybill->waybill_no }}
                @endif


            </td>
        </tr>
    </table>
    <table style="width: 100%;margin-top: 4px;">

        <tr>
            <td style="width: 20%;text-align: center;vertical-align:middle">
                &nbsp;
            </td>
            <td style="width: 20%;text-align: center;vertical-align:middle">
                &nbsp;
            </td>
            <td style="width: 20%;text-align: center;vertical-align:middle">
                &nbsp;
            </td>
            <td style="width: 20%;text-align: center;vertical-align:middle">

            </td>
            <td style="width: 20%;text-align: center;vertical-align:middle">
                &nbsp;
            </td>
        </tr>
        <tr style="margin-top: 8px">
            <td style="width: 20%;text-align: center;vertical-align:middle">
                {{ $order->order_header_no }}
            </td>
            <td style="width: 20%;text-align: center;vertical-align:middle">
                {{ $order->created_at }}
            </td>
            <td style="width: 20%;text-align: center;vertical-align:middle">
                @switch($order->paymenttype)
                    @case('H')
                        เงินสดต้นทาง
                    @break

                    @case('T')
                        เงินโอนต้นทาง<br />
                    @break

                    @case('E')
                        เก็บเงินปลายทาง<br />
                    @break

                    @case('F')
                        วางบิลต้นทาง<br />
                    @break

                    @case('L')
                        วางบิลปลายทาง
                    @break
                @endswitch
            </td>
            <td style="width: 20%;text-align: center;vertical-align:middle;">
                @switch($order->trantype)
                    @case(1)
                        จัดส่ง<br />
                    @break

                    @case(0)
                        รับเอง<br />
                    @break
                @endswitch
            </td>
            <td style="width: 20%;text-align: center;vertical-align:middle;">
                @if ($order->waybill_id != '')
                    {{ $order->waybill->car->car_regist }}
                @endif
            </td>
        </tr>

    </table>
    <table style="width: 100%;">

        <tr style="height: 1.9cm;vertical-align:top;padding-top: 2px;">
            <td style="width: 50%;text-indent: 75px;vertical-align:top;padding-left: 25px;">

                @isset($order->customer->name)
                    {{ $order->customer->name }}
                @endisset
                @isset($order->customer->address)
                    &nbsp;
                    {{ $order->customer->address }}
                @endisset
                @isset($order->customer->province)
                    @if ($order->customer->province === 'กรุงเทพมหานคร')
                        แขวง{{ $order->customer->sub_district }}
                    @else
                        ต.{{ $order->customer->sub_district }}
                    @endif
                @endisset
                @isset($order->customer->province)
                    @if ($order->customer->province === 'กรุงเทพมหานคร')
                        เขต{{ $order->customer->district }}
                    @else
                        อ.{{ $order->customer->district }}
                    @endif
                @endisset
                @isset($order->customer->province)
                    @if ($order->customer->province === 'กรุงเทพมหานคร')
                        {{ $order->customer->province }}
                    @else
                        จ.
                        {{ $order->customer->province }}
                    @endif
                @endisset
                @isset($order->customer->postal_code)
                    {{ $order->customer->postal_code }}<br />
                @endisset
                @isset($order->customer->taxid)
                    Tax ID. {{ $order->customer->taxid }}
                @endisset

            </td>
            <td style="width: 50%;text-indent: 90px;vertical-align:top;padding-left: 30px;">
                @isset($order->to_customer->name)
                    {{ $order->to_customer->name }}
                @endisset
                {{ $order->to_customer->address }} &nbsp;
                @if ($order->to_customer->province === 'กรุงเทพมหานคร')
                    แขวง{{ $order->to_customer->sub_district }}
                @else
                    ต.{{ $order->to_customer->sub_district }}
                @endif


                @if ($order->to_customer->province === 'กรุงเทพมหานคร')
                    เขต{{ $order->to_customer->district }}
                @else
                    อ.{{ $order->to_customer->district }}
                @endif
                @if ($order->to_customer->province === 'กรุงเทพมหานคร')
                    {{ $order->to_customer->province }}
                @else
                    จ.{{ $order->to_customer->province }}
                @endif
                {{ $order->to_customer->postal_code }}<br />

                @if ($order->to_customer->taxid != '')
                    Tax ID. {{ $order->to_customer->taxid }}
                @endif

            </td>
        </tr>
        <tr>
            <td style="width: 50%;">
                @isset($order->customer->phoneno)
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <strong>{{ $order->customer->phoneno }}</strong>
                @endisset
            </td>
            <td style="width: 50%;">

                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <strong>{{ $order->to_customer->phoneno }}</strong>
            </td>
        </tr>


    </table>

    <table style="width: 100%;magin-top: 10px">
        <tr style="vertical-align:top;">
            <td style="width: 40%;text-align: left">
                &nbsp;
            </td>
            <td style="width: 11%;text-align: right">

            </td>
            <td style="width: 9%;text-align: center">

            </td>
            <td style="width: 15%;text-align: right">

            </td>
            <td style="width: 20%;text-align: right">
                &nbsp;
            </td>
        </tr>
    </table>
    <table style="width: 100%;magin-left: 10px;height: 2.5cm;">

        @foreach ($order->order_details as $item)
            <tr style="vertical-align:top;height:13px">
                <td style="width: 45%;text-align: left">
                    {{ $loop->iteration }}.{{ $item->product->name }}
                    @isset($item->remark)
                        ({{ $item->remark }})
                    @endisset
                </td>
                <td style="width: 11%;text-align: right">
                    {{ number_format($item->amount, 2) }}
                </td>
                <td style="width: 9%;text-align: center">
                    {{ $item->unit->name }}
                </td>
                <td style="width: 15%;text-align: right">
                    {{ number_format($item->price, 2) }}
                </td>
                <td style="width: 20%;text-align: right">
                    {{ number_format($item->price * $item->amount, 2) }}

                </td>
            </tr>
        @endforeach
        @if (count($order->order_details) < 5)
            @for ($i = 1; $i <= 5 - count($order->order_details); $i++)
                <tr style="vertical-align:top;height:14px">
                    <td style="width: 45%;text-align: left">

                    </td>
                    <td style="width: 11%;text-align: right">

                    </td>
                    <td style="width: 9%;text-align: center">

                    </td>
                    <td style="width: 15%;text-align: right">

                    </td>
                    <td style="width: 20%;text-align: right">

                    </td>

                </tr>
            @endfor
        @endif
    </table>
    <table style="width: 100%;margin-top: 9px;">
        <tr style="vertical-align:top;height:14px;">
            <td style="width: 55%;text-align: left">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $order->remark }}


            </td>
            <td style="width: 20%;text-align: left">

                {{ $order->order_details->where('unit_id', '<>', 10)->sum('amount') + $order->order_details->where('unit_id', '=', 10)->count('amount') }}
                ชิ้น
            </td>

            <td style="width: 25%;text-align: right">
                <strong>{{ number_format($order->order_amount, 2) }}</strong><br />
                {{ baht_text($order->order_amount) }}
            </td>

        </tr>
    </table>


    <table style="width: 100%;;magin-left: 10px;">
        <tr style="vertical-align:top">

            <td style="width: 22%;">

            </td>
            <td style="width: 22%;">

            </td>
            <td style="width: 22%;">

            </td>
            <td style="width: 34%;text-align: right">
                {{ $order->to_branch->name }}
            </td>


        </tr>
        <tr style="vertical-align:top">
            <td style="width: 22%;">

                @isset($order->checker->name)
                    {{ $order->checker->name }}<br />
                @endisset
            </td>
            <td style="width: 22%;">

                @isset($order->user->name)
                    {{ $order->user->name }}<br />
                @endisset
            </td>
            <td style="width: 22%;">
                @isset($order->loader->name)
                    {{ $order->loader->name }}<br />
                @endisset
            </td>
            <td style="width: 34%;text-align: center">
                <strong>
                    T. {{ $order->to_branch->phoneno }}

                </strong>
            </td>







        </tr>
    </table>


@endsection
