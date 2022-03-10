@extends('layouts.form955')

@section('header')
    @include('partials.receiptheader')
@endsection
@section('content')
    <table style="width: 100%;border:0px">
        <tr>
            <td style="width: 100%;text-align: center;vertical-align:top;border:0px">
                <h2>ใบเสร็จรับเงิน</h2>
            </td>
        </tr>
    </table>

    <table style="width: 100%;">
        <tr>
            <td style="width: 70%;text-align: left;vertical-align:top;padding:10px">
                <strong>
                    ชื่อลูกค้า : {{ $receipt->customer->name }}<br />
                    @isset($receipt->customer->taxid)
                        Tax ID. {{ $receipt->customer->taxid }}
                    @endisset
                </strong>
                ที่อยู่ : {{ $receipt->customer->address }}
                @if ($receipt->customer->province === 'กรุงเทพมหานคร')
                    แขวง{{ $receipt->customer->sub_district }}
                @else
                    ต.{{ $receipt->customer->sub_district }}
                @endif

                @if ($receipt->customer->province === 'กรุงเทพมหานคร')
                    เขต{{ $receipt->customer->district }}
                @else
                    อ.{{ $receipt->customer->district }}
                @endif
                @if ($receipt->customer->province === 'กรุงเทพมหานคร')
                    {{ $receipt->customer->province }}
                @else
                    จ.{{ $receipt->customer->province }}
                @endif

                {{ $receipt->customer->postal_code }}<br />
                โทรศัพท์: {{ $receipt->customer->phoneno }}

            </td>
            <td style="width: 30%;text-align: left;vertical-align:top;padding:10px">
                เลขที่ : {{ $receipt->receipt_no }} <br />
                วันที่ : {{ $receipt->receipt_date->format('d/m/Y') }}<br />
            </td>
        </tr>
    </table>
    <br />

    <table style="width: 100%;border: 0.5px soild black;">
        <tr style="vertical-align:top;">
            <td style="width: 10%;text-align: center">
                ลำดับ
            </td>
            <td style="width: 70%;text-align: center">
                รายการ
            </td>
            <td style="width: 20%;text-align: center">
                จำนวนเงิน
            </td>

        </tr>


        <tr style="vertical-align:top;">
            <td style="text-align: center;border-top:0px;border-bottom: 0px;">
                1
            </td>
            <td style="border-top:0px;border-bottom: 0px;">
                ค่าขนส่งสินค้า
            </td>

            <td style="text-align: right;border-top:0px;border-bottom: 0px;">
                {{ number_format($receipt->total_amount, 2, '.', ',') }}

            </td>
        </tr>
        @for ($i = 1; $i <= 2; $i++)
            <tr style="vertical-align:top;height:1cm">
                <td style="text-align: center;border-top:0px;border-bottom: 0px;">

                </td>
                <td style="text-align: center;border-top:0px;border-bottom: 0px;">

                </td>
                <td style="text-align: center;border-top:0px;border-bottom: 0px;">

                </td>
            </tr>
        @endfor
        <tr style="font-weight: bold">
            <td colspan="2" style="text-align: right">
                ส่วนลด
            </td>

            <td style="text-align: right">
                {{ number_format($receipt->discount_amount, 2, '.', ',') }}
            </td>
        </tr>
        <tr style="font-weight: bold">
            <td style="text-align: center">
                รวมเงิน
            </td>
            <td style="text-align: center">
                ( {{ baht_text($receipt->total_amount - $receipt->discount_amount) }} )
            </td>
            <td style="text-align: right">
                {{ number_format($receipt->total_amount - $receipt->discount_amount, 2, '.', ',') }}
            </td>
        </tr>
    </table>
    <br />
    <table style="width: 100%;border-top: 0.5px solid black;">
        <tr style="vertical-align:top">
            <td style="width: 60%;padding:10px">
                ภาษีหัก ณ ที่จ่าย {{ number_format($receipt->tax_amount, 2, '.', ',') }}<br />
                @switch($receipt->branchpay_by)
                    @case('C')
                        เงินสด<br />
                    @break

                    @case('T')
                        เงินโอน เข้าบัญชี {{ $receipt->bankaccount->account_no }} <br />
                        ธนาคาร {{ $receipt->bankaccount->bank->name }}
                    @break

                    @case('Q')
                        เช็ค เลขที่ : {{ $receipt->chequeno }} ลงวันที่ {{ $receipt->chequedate }} <br />
                        ธนาคาร {{ $receipt->chequebank->name }} <br />
                    @break

                    @default
                        เงินสด
                @endswitch

            </td>
            <td style="width: 20%;text-align:center;padding:10px">
                ผู้รับเงิน <br /><br />
                ..................................<br /><br />
                ........./.........../............
            </td>

            <td style="width: 20%;text-align:center;padding:10px">
                ผู้จัดการ <br /><br />
                ..................................<br /><br />
                ........./.........../............
            </td>



        </tr>
    </table>
@endsection
