@extends('layouts.docnojs')

@section('header')
    @include('partials.reportheader')

@endsection

@section('content')

<table style="width:100%;">

    <tr>
        <td style="width: 50%;text-align: left;border:0px">
            ทะเบียนรถ: {{ $cardata->car_regist }} <br/>
            เจ้าของรถ: {{ $cardata->owner->name }} Tax id : {{$cardata->owner->taxid }}<br/>
            ที่อยู่ : {{ $cardata->owner->address }}
            {{ $cardata->owner->sub_district}}
            {{ $cardata->owner->district}}
            {{ $cardata->owner->province.' '.
            $cardata->owner->postal_code }} <br/>
            @isset($cardata->owner->phoneno)
                Tel: {{ $cardata->owner->phoneno }}
            @endisset
            <br/>
        </td>
        <td style="width:50%;text-align: right;border:0px">
            <strong>
            ระหว่างวันที่: {{date("d-m-Y", strtotime($from))}}<br/> ถึงวันที่: {{date("d-m-Y", strtotime($to))}}<br/>
            </strong>
        </td>
    </tr>

    <tr>
     <td colspan="2"  style="width: 100%;text-align: left;border:0px">
        @php
            if($cardata->owner->account_type == 'saving'){
                $accounttype = 'ออมทรัพย์' ;
            }elseif($cardata->owner->account_type == 'current'){
                $accounttype = 'กระแสรายวัน' ;
            }elseif($cardata->owner->account_type == 'fixed'){
                $accounttype = 'ฝากประจำ' ;
            }
        @endphp
        <strong>ข้อมูลบัญชีธนาคาร</strong><br>
        @isset($cardata->owner->bankaccountno)
        เลขที่บัญชี {{ $cardata->owner->bankaccountno }}
        @endisset

        @isset($cardata->owner->bankaccountname)
          ชื่อบัญชี {{ $cardata->owner->bankaccountname }}
        @endisset


        @isset($cardata->owner->account_type)
          ประเภทบัญชี {{$accounttype}}
        @endisset
        @isset($cardata->owner->bank->name)
          ธนาคาร {{$cardata->owner->bank->name}}
        @endisset




    </td>
    </tr>

</table>
<br/>

<table style="width: 100%;" >
    <thead>
        <tr>
            <th style="width: 5%;">ลำดับ</th>
            <th style="width: 10%;text-align: center;">วันที่</th>
            <th style="width: 15%;text-align: center;">เลขที่เอกสาร</th>
            <th style="width: 35%;text-align: left;">รายการ</th>
            <th style="width: 10%;text-align: right;">รับ</th>
            <th style="width: 10%;text-align: right;">จ่าย</th>
            <th style="width: 15%;text-align: right;">คงเหลือ</th>
        </tr>

    </thead>

    <tbody>
            <tr style="font-weight: bold;">
                <td colspan="6" style="text-align: right">
                    ยอดยกมา
                </td>
                <td style="text-align: right">
                    @php
                        $bringforword = $recforword - $payforword;
                    @endphp
                    {{ number_format($bringforword,2,'.',',') }}
                </td>

            </tr>
            @php
                $bal_amount = $bringforword  ;
            @endphp
            @foreach ($carcards as $item )

                <tr style="vertical-align: top">
                    <td style="text-align: center">{{ $loop->iteration }}</td>

                    <td style="text-align: center">{{ $item->cardoc_date->format('d/m/Y') }}</td>

                    <td>{{ $item->docno}}</td>

                    <td>{{ $item->description }}</td>

                    <td style="text-align: right">
                        @if ($item->doctype == 'R')
                            {{ number_format($item->amount,2,'.',',') }}
                        @endif
                    </td>

                    <td style="text-align: right">
                        @if ($item->doctype == 'P')
                            {{ number_format($item->amount,2,'.',',') }}
                        @endif
                    </td>
                    <td style="text-align: right">

                        @if ($item->doctype == 'R')
                            @php
                                $bal_amount +=  $item->amount ;
                            @endphp
                        @else
                             @php
                                $bal_amount -=  $item->amount ;
                            @endphp

                        @endif
                        {{ number_format($bal_amount,2,'.',',') }}

                    </td>



                </tr>
            @endforeach


        <tr style="font-weight: bold;">
            <td colspan="4">
                    <strong>
                    ยอดระหว่างเดือน - {{count($carcards)}} รายการ
                    </strong>
            </td>

            <td style="text-align: right">

                    @php
                        $carrec =  $carcards->where('doctype','R')->sum('amount')
                    @endphp


                {{ number_format($carrec,2,'.',',') }}

            </td>
            <td style="text-align: right">

                    @php
                        $carpay =  $carcards->where('doctype','P')->sum('amount')
                    @endphp


                {{ number_format($carpay,2,'.',',') }}

            </td>
            <td style="text-align: right">
                {{ number_format($carrec - $carpay ,2,'.',',') }}
            </td>

        </tr>
        <tr style="font-weight: bold;">
            <td colspan="6" style="text-align: right">
                ยอดยกไป
            </td>
            <td style="text-align: right">
                {{ number_format($bringforword + ($carrec - $carpay) ,2,'.',',') }}
            </td>

        </tr>
    </tbody>

</table>



@endsection

