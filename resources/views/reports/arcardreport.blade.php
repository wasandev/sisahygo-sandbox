@extends('layouts.docnojs')

@section('header')
    @include('partials.reportheader')

@endsection

@section('content')

<table style="width:100%;">

    <tr>
        <td style="width: 50%;text-align: left;border:0px">
            ชื่อลูกค้า: {{ $ardata->name }} <br/>
            ที่อยู่ : {{ $ardata->address }}
            {{ $ardata->sub_district}}
            {{ $ardata->district}}
            {{ $ardata->province.' '.
            $ardata->postal_code }} <br/>
            @isset($ardata->phoneno)
                Tel: {{ $ardata->phoneno }}
            @endisset
            <br/>
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
            <th style="width: 5%;">ลำดับ</th>
            <th style="width: 10%;text-align: center;">วันที่</th>
            <th style="width: 15%;text-align: center;">เลขที่เอกสาร</th>
            <th style="width: 35%;text-align: left;">รายการ</th>
            <th style="width: 10%;text-align: right;">ยอดตั้งหนี้</th>
            <th style="width: 10%;text-align: right;">ยอดชำระหนี้</th>
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
                        $bringforword =  $payforword - $recforword;
                    @endphp
                    {{ number_format($bringforword,2,'.',',') }}
                </td>

            </tr>
            @php
                $bal_amount = $bringforword  ;
            @endphp
            @foreach ($arcards as $item )

                <tr style="vertical-align: top">
                    <td style="text-align: center">{{ $loop->iteration }}</td>

                    <td style="text-align: center">{{ $item->docdate->format('d/m/Y') }}</td>

                    <td>{{ $item->docno}}</td>

                    <td>{{ $item->description }}</td>

                    <td style="text-align: right">
                        @if ($item->doctype == 'P')
                            {{ number_format($item->ar_amount,2,'.',',') }}
                        @endif
                    </td>

                    <td style="text-align: right">
                        @if ($item->doctype == 'R')
                            {{ number_format($item->ar_amount,2,'.',',') }}
                        @endif
                    </td>
                    <td style="text-align: right">

                        @if ($item->doctype == 'P')
                            @php
                                $bal_amount +=  $item->ar_amount ;
                            @endphp
                        @else
                             @php
                                $bal_amount -=  $item->ar_amount ;
                            @endphp

                        @endif
                        {{ number_format($bal_amount,2,'.',',') }}

                    </td>



                </tr>
            @endforeach


        <tr style="font-weight: bold;">
            <td colspan="4">
                    <strong>
                    ยอดระหว่างเดือน - {{count($arcards)}} รายการ
                    </strong>
            </td>

            <td style="text-align: right">

                    @php
                        $arpay =  $arcards->where('doctype','P')->sum('ar_amount')
                    @endphp


                {{ number_format($arpay,2,'.',',') }}

            </td>
            <td style="text-align: right">

                    @php
                        $arrec =  $arcards->where('doctype','R')->sum('ar_amount')
                    @endphp


                {{ number_format($arrec,2,'.',',') }}

            </td>
            <td style="text-align: right">
                {{ number_format($arpay - $arrec ,2,'.',',') }}
            </td>

        </tr>
        <tr style="font-weight: bold;">
            <td colspan="6" style="text-align: right">
                ยอดยกไป
            </td>
            <td style="text-align: right">
                {{ number_format($bringforword + ($arpay - $arrec) ,2,'.',',') }}
            </td>

        </tr>
    </tbody>

</table>



@endsection

