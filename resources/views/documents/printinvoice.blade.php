<title>{{ $invoice->invoice_no }}</title>
@extends('layouts.forma4')

@section('header')
    @include('partials.docheader')
@endsection
@section('content')
    <br />
    <table style="width: 100%;border: 0px;">
        <tr>
            <td style="width: 100%;text-align: center;vertical-align:top;border:0px;">
                <h2>ใบแจ้งหนี้</h2>
            </td>
        </tr>
    </table>
    <br />
    <table style="width: 100%;border-top: 0.5px dotted black;">

        <tr>
            <td style="width: 70%;vertical-align:top;padding: 10px;">
                <strong>ชื่อลูกค้า: </strong> {{ $invoice->ar_customer->name }}<br />
                @isset($invoice->ar_customer->taxid)
                    Tax ID. {{ $invoice->ar_customer->taxid }}
                @endisset
                <strong>ที่อยู่ : </strong>{{ $invoice->ar_customer->address }}
                @if ($invoice->ar_customer->province === 'กรุงเทพมหานคร')
                    แขวง{{ $invoice->ar_customer->sub_district }}
                @else
                    ต.{{ $invoice->ar_customer->sub_district }}
                @endif

                @if ($invoice->ar_customer->province === 'กรุงเทพมหานคร')
                    เขต{{ $invoice->ar_customer->district }}
                @else
                    อ.{{ $invoice->ar_customer->district }}
                @endif

                จ.{{ $invoice->ar_customer->province . ' ' . $invoice->ar_customer->postal_code }}<br />
                <strong>Tel: {{ $invoice->ar_customer->phoneno }}</strong>

            </td>
            <td style="width: 30%;vertical-align:top;padding: 10px;">
                <strong>เลขที่ : </strong> {{ $invoice->invoice_no }}<br />
                <strong>วันที่แจ้งหนี้ : </strong> {{ $invoice->invoice_date->format('d/m/Y') }}<br />
                <strong>วันครบกำหนด : </strong> {{ $invoice->due_date->format('d/m/Y') }}<br />
            </td>
        </tr>


    </table>
    <br />
    บริษัทฯ ขอแจ้งหนี้ค่าขนส่งสินค้า ตามรายการดังนี้ :-
    <br />
    <table style="width: 100%;border: 0.5px soild black;">
        <tr style="vertical-align:middle;font-weight: bold;height:1cm">
            <td style="width: 5%;text-align: center">
                ลำดับ
            </td>
            <td style="width: 15%;text-align: center">
                วันที่
            </td>
            <td style="width: 15%;text-align: center">
                ใบรับส่งสินค้า
            </td>
            <td style="width: 45%;text-align:center">
                รายละเอียด
            </td>
            <td style="width: 20%;text-align: center">
                จำนวนเงิน
            </td>
        </tr>

        @foreach ($invoice->ar_balances as $item)
            <tr style="vertical-align:top;">
                <td style="text-align: center;border-top:0px;border-bottom: 0px;">
                    {{ $loop->iteration }}
                </td>
                <td style="text-align: center;border-top:0px;border-bottom: 0px;">
                    {{ $item->order_header->order_header_date->format('d/m/Y') }}
                </td>
                <td style="text-align: center;border-top:0px;border-bottom: 0px;">
                    {{ $item->order_header->order_header_no }}
                </td>
                <td style="border-top:0px;border-bottom: 0px;">
                    @if ($item->order_header->car_id)
                        @php
                            $item_desc = 'ค่าขนส่งสินค้า -' . $item->order_header->car->car_regist;
                        @endphp
                    @else
                        @php
                            $item_desc = 'ค่าขนส่งสินค้า -';

                        @endphp
                    @endif
                    @foreach ($item->order_header->order_details as $detail)
                        @php

                            $item_desc = $item_desc . $detail->product->name;

                        @endphp
                    @endforeach
                    {{ $item_desc }}
                </td>
                <td style="text-align: right;border-top:0px;border-bottom: 0px;">
                    {{ number_format($item->ar_amount, 2) }}

                </td>
            </tr>
        @endforeach

        @if (count($invoice->ar_balances) < 13)
            @for ($i = 1; $i <= 8 - count($invoice->ar_balances); $i++)
                <tr style="vertical-align:top;height:1cm">
                    <td style="text-align: center;border-top:0px;border-bottom: 0px;">

                    </td>
                    <td style="text-align: center;border-top:0px;border-bottom: 0px;">

                    </td>
                    <td style="text-align: center;border-top:0px;border-bottom: 0px;">

                    </td>
                    <td style="text-align: center;border-top:0px;border-bottom: 0px;">

                    </td>
                    <td style="text-align: center;border-top:0px;border-bottom: 0px;">

                    </td>

                </tr>
            @endfor
        @endif
        <tr style="font-weight: bold">
            <td colspan="3" style="text-align: center">
                รวมเงิน
            </td>
            <td style="text-align: center">
                ( {{ baht_text($invoice->ar_balances->sum('ar_amount')) }} )
            </td>
            <td style="text-align: right">
                {{ number_format($invoice->ar_balances->sum('ar_amount'), 2, '.', ',') }}
            </td>
        </tr>
    </table>
    <br>
    <table style="width: 100%;border-top: 0.5px dotted black;">
        <tr style="vertical-align:top">
            <td style="width: 60%;padding:10px">
                <strong>หมายเหตุ : </strong> {{ $invoice->description }}

            </td>
            <td style="width: 40%;padding:10px">
                <strong>เอกสารที่แนบมาด้วย</strong><br />
                1).......................................................................<br />
                2).......................................................................<br />
                3).......................................................................<br />


            </td>



        </tr>
    </table>
    <br>
    <table style="width: 100%;border-top: .05px dotted black;">
        <tr style="vertical-align:top;">
            <td style="width: 20%;text-align:center;padding:10px">
                วันที่นัดชำระเงิน<br /><br />
                ........./.........../............

            </td>
            <td style="width: 20%;text-align:center;padding:10px">
                ผู้รับวางบิล <br /><br />
                ..................................<br />
                (.................................)<br />
                ........./.........../............
            </td>
            <td style="width: 20%;text-align:center;padding:10px">
                ฝ่ายเร่งรัดหนี้สิน <br /><br />
                ..................................<br />
                (.................................)<br />
                ........./.........../............
            </td>
            <td style="width: 20%;text-align:center;padding:10px">
                ผู้จัดการ <br /><br />
                ..................................<br />
                (.................................)<br />
                ........./.........../............
            </td>
        </tr>
    </table>
    <br>
    <table style="width: 100%;border-top: .05px dotted black;">
        <tr style="vertical-align:top;">
            <td style="width: 50%;text-align:center;padding:10px">
                การชำระเงิน<br />
                โอนเข้าบัญชี ธ.กสิกรไทย<br>
                บจก.สี่สหายขนส่ง(1988) สาขา บางมด<br />
                เลขที่บัญชี <strong>0901004102</strong>




            </td>
            <td style="width: 50%;text-align:center;padding:10px">
                สแกนจ่าย QR Code ได้ทุกธนาคาร <br />

                <img src="{{ url('storage/images/siskbqrpay.jpg') }}" alt="Qr จ่ายเงินสี่สหายขนส่ง" height="100">
            </td>

            </td>

        </tr>
    </table>

@endsection
