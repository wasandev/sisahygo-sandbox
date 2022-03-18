@extends('layouts.form')


@section('content')

    @foreach ($carpayments as $carpayment)
        @include('partials.cardocheader')





        <table style="width: 100%">
            <tr>
                <td style="width: 100%;text-align: center;vertical-align:top">

                </td>
            </tr>
        </table>
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%;">
                    จ่ายให้ : {{ $carpayment->vendor->name }} <br />
                    ที่อยู่ : {{ $carpayment->vendor->address }}
                    @if ($carpayment->vendor->province === 'กรุงเทพมหานคร')
                        แขวง{{ $carpayment->vendor->sub_district }}
                    @else
                        ต.{{ $carpayment->vendor->sub_district }}
                    @endif

                    @if ($carpayment->vendor->province === 'กรุงเทพมหานคร')
                        เขต{{ $carpayment->vendor->district }}
                    @else
                        อ.{{ $carpayment->vendor->district }}
                    @endif

                    จ.{{ $carpayment->vendor->province . ' ' . $carpayment->vendor->postal_code }}
                    @isset($carpayment->vendor->phoneno)
                        Tel: {{ $carpayment->vendor->phoneno }}
                    @endisset
                    <br />

                    ทะเบียนรถ : {{ $carpayment->car->car_regist }}


                </td>
                <td style="width: 50%;text-align: right">
                    เลขที่: {{ $carpayment->payment_no }}<br />
                    วันที่: {{ $carpayment->payment_date->format('d/m/Y') }}<br />
                </td>
            </tr>
            <tr>
                <td colspan="3" style="width: 100%;">
                    @switch($carpayment->payment_by)
                        @case('H')
                            จ่ายโดย : เงินสด<br />
                        @break

                        @case('T')
                            จ่ายโดย : เงินโอน เข้าบัญชี {{ $carpayment->tobank->name }} เลขที่
                            {{ $carpayment->tobankaccount }}
                            ชื่อบัญชี {{ $carpayment->tobankaccountname }}
                            <br />
                        @break

                        @case('Q')
                            จ่ายโดย : เช็ค ธนาคาร
                            @isset($carpayment->chequebank->name)
                                {{ $carpayment->chequebank->name }}
                            @endisset
                            เลขที่ {{ $carpayment->chequeno }} ลงวันที่ {{ $carpayment->chequedate }}<br />
                        @break

                        @case('A')
                            จ่ายโดย : รายการตัดบัญชี <br />
                        @break
                    @endswitch
                </td>
            </tr>
        </table>

        <table style="width: 100%;border-top: 0.5px dotted black;">
            <tr style="vertical-align:top;">
                <td style="width: 70%;text-align: left">
                    รายการ
                </td>

                <td style="width: 30%;text-align: right">
                    จำนวนเงิน
                </td>
            </tr>
        </table>
        <table style="width: 100%;height: 4.0cm;border-top: 0.5px dotted black;">

            <tr style="vertical-align:top;height:14px">
                <td style="text-align: left">
                    1. {{ $carpayment->description }}

                </td>
                <td style="text-align: right">
                    {{ number_format($carpayment->amount, 2) }}
                </td>

            </tr>

        </table>
        <table style="width: 100%;border-top: 0.5px dotted black;">
            <tr style="vertical-align:top">
                <td style="width: 50%;text-align: center">
                    ({{ baht_text($carpayment->amount) }})
                </td>
                <td style="width: 50%;text-align: right">
                    {{ number_format($carpayment->amount, 2) }}


                </td>
            </tr>
        </table>
        <table style="width: 100%;">
            <tr style="vertical-align:middle">
                <td style="width: 50%;">
                    @if ($carpayment->tax_flag)
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                            <path class="heroicon-ui"
                                d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-2.3-8.7l1.3 1.29 3.3-3.3a1 1 0 0 1 1.4 1.42l-4 4a1 1 0 0 1-1.4 0l-2-2a1 1 0 0 1 1.4-1.42z" />
                        </svg>
                        ภาษีหัก ณ ที่จ่าย (1%) จำนวนเงินภาษีหัก ณ ที่จ่าย : {{ $carpayment->tax_amount }}
                    @endif
                </td>

                <td style="width: 50%;text-align: right">
                    คงเหลือจ่ายสุทธิ : {{ number_format($carpayment->amount - $carpayment->tax_amount, 2) }}

                </td>
            </tr>
        </table>
        <br />
        <br />
        <table style="width: 100%;border-top: .05px dotted black;height: 2.0cm;">
            <tr style="vertical-align:middle;">
                <td style="width: 33%;text-align: center">
                    ผู้อนุมัติ..........................................
                </td>
                <td style="width: 33%;text-align: center">
                    ผู้จ่าย............................................<br />
                    {{ $carpayment->user->name }}
                </td>
                <td style="width: 34%;text-align: center">
                    ผู้รับเงิน..........................................

                </td>
            </tr>
        </table>
    @endforeach
@endsection
