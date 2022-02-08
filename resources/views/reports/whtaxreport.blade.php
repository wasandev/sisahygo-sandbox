@extends('layouts.docnojs')

@section('header')
    @include('partials.reportheader')

@endsection

@section('content')

<table style="width:100%;">

    <tr>
        <td style="width: 50%;text-align: left;border:0px">
            แบบ : ภ.ง.ด.{{ $type }}
        </td>
        <td style="width:50%;text-align: right;border:0px">
            <strong>
            ระหว่างวันที่: {{date("d-m-Y", strtotime($from))}}<br/> ถึงวันที่: {{date("d-m-Y", strtotime($to))}}<br/>
            </strong>
        </td>
    </tr>

</table>
<br/>

<table style="width: 100%;" >
    <thead>

        <tr>
            <th style="width: 10%;">ลำดับ</th>
            <th style="width: 10%;text-align: center;">เลขประจำตัวผู้เสียภาษี</th>
            <th style="width: 30%;text-align: center;">ชื่อผู้หักภาษี ณ ที่จ่าย</th>
            <th style="width: 20%;text-align: center;">ประเภทเงินได้ ที่จ่าย</th>
            <th style="width: 10%;text-align: center;">วัน เดือน ปี ที่จ่าย</th>
            <th style="width: 10%;text-align: center;">จำนวนเงิน</th>
            <th style="width: 10%;text-align: center;">ภาษี</th>

        </tr>

    </thead>

    <tbody>

                    @foreach ($whtaxes as $whtax )
                        <tr style="vertical-align: top">
                            <td style="text-align: center">
                                {{ $loop->iteration }}
                            </td>
                            <td style="text-align: center">
                                {{ $whtax->vendor->taxid }}
                            </td>
                            <td style="text-align: left">
                                 {{ $whtax->vendor->name }} <br/>
                                 {{ $whtax->vendor->address  . ' ' .  $whtax->vendor->sub_district . ' ' .  $whtax->vendor->district  . ' ' .  $whtax->vendor->province  . ' ' . $whtax->vendor->postal_code }}
                            </td>
                            <td style="text-align: left">
                                {{ $whtax->incometype->name }}

                            </td>
                            <td style="text-align: center">
                                {{ date("d-m-Y", strtotime($whtax->pay_date)) }}
                            </td>

                            <td style="text-align: right">
                                {{ number_format($whtax->pay_amount,2,'.',',') }}
                            </td>

                            <td style="text-align: right">
                                {{ number_format($whtax->tax_amount,2,'.',',') }}
                            </td>

                        </tr>
                    @endforeach

            <tr style="font-weight: bold;">

                <td  colspan="5" style="text-align: center">
                    <strong>
                        รวมทั้งหมด - {{$whtaxes->count()}} - รายการ
                    </strong>
                </td>

                <td style="text-align: right">
                    {{ number_format($whtaxes->sum('pay_amount'),2,'.',',') }}
                </td>
                <td style="text-align: right">
                    {{ number_format($whtaxes->sum('tax_amount'),2,'.',',') }}
                </td>

            </tr>
    </tbody>

</table>


@endsection

