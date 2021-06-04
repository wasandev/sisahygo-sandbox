@extends('layouts.form')

@section('header')
    @include('partials.cardocheader')
@endsection

@section('content')

<table style="width: 100%">
    <tr >
        <td style="width: 100%;text-align: center;vertical-align:top">

        </td>
    </tr>
</table>
<table style="width: 100%;">
    <tr>
        <td style="width: 50%;">
           รับเงินจาก : {{ $carreceive->vendor->name }} <br/>
           ที่อยู่ : {{ $carreceive->vendor->address }}
            @if ( $carreceive->vendor->province === "กรุงเทพมหานคร" )
                แขวง{{ $carreceive->vendor->sub_district}}
            @else
                ต.{{ $carreceive->vendor->sub_district}}
            @endif

            @if($carreceive->vendor->province === "กรุงเทพมหานคร")
                เขต{{$carreceive->vendor->district}}
            @else
                อ.{{ $carreceive->vendor->district}}
            @endif

            จ.{{$carreceive->vendor->province.' '.
            $carreceive->vendor->postal_code }}
            @isset($carreceive->vendor->phoneno)
                Tel: {{ $carreceive->vendor->phoneno }}
            @endisset
            <br/>

           ทะเบียนรถ : {{$carreceive->car->car_regist}}


        </td>
        <td style="width: 50%;text-align: right">
            เลขที่: {{ $carreceive->receive_no }}<br/>
            วันที่: {{ $carreceive->receive_date->format('d/m/Y') }}<br/>
        </td>
    </tr>
    <tr>
        <td colspan="3"  style="width: 100%;">
            @switch($carreceive->payment_by)
                @case('H')
                    รับโดย : เงินสด<br/>
                    @break

                @case('T')
                    รับโดย : เงินโอน เข้าบัญชี {{$carreceive->tobank->name }} เลขที่ {{$carreceive->tobankaccount }} ชื่อบัญชี {{tobankaccountname}}
                    <br/>

                    @break
                @case('Q')
                    รับโดย : เช็ค ธนาคาร {{$carreceive->chequebank->name}} เลขที่ {{ $carreceive->chequeno }} ลงวันที่ {{ $carreceive->chequedate }}<br/>
                    @break
                @case('A')
                    รับโดย : รายการตัดบัญชี <br/>
                    @break

            @endswitch
        </td>
    </tr>
</table>

<table  style="width: 100%;border-top: 0.5px dotted black;">
    <tr style="vertical-align:top;">
            <td  style="width: 70%;text-align: left">
                รายการ
            </td>

            <td style="width: 30%;text-align: right">
                จำนวนเงิน
            </td>
        </tr>
</table>
<table  style="width: 100%;height: 4.0cm;border-top: 0.5px dotted black;">

            <tr style="vertical-align:top;height:14px">
                <td  style="text-align: left">
                   1. {{ $carreceive->description }}

                </td>
                <td style="text-align: right">
                    {{number_format($carreceive->amount,2)}}
                </td>

            </tr>

</table>
<table  style="width: 100%;border-top: 0.5px dotted black;">
    <tr style="vertical-align:top">
        <td style="width: 50%;text-align: center">
            ( {{ baht_text($carreceive->amount)}} )


        </td>
        <td style="width: 50%;text-align: right">
             {{number_format($carreceive->amount,2)}}


        </td>
    </tr>
</table>
<br/>
<br/>
<table  style="width: 100%;border-top: .05px dotted black;height: 2.0cm;">
    <tr style="vertical-align:middle;">
        <td style="width: 33%;text-align: center">
        </td>
         <td style="width: 33%;text-align: center">
        </td>
         <td style="width: 34%;text-align: center">
            ผู้รับเงิน..........................................<br/>
            {{ $carreceive->user->name}}

        </td>
    </tr>
</table>

@endsection
