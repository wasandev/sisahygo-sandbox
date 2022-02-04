@extends('layouts.docnojs')

@section('header')
    @include('partials.reportheader')

@endsection

@section('content')

<table style="width:100%;">

    <tr>
        <td style="width: 50%;text-align: left;border:0px">

            เจ้าของรถ: {{ $ownerdata->name }} Tax id : {{$ownerdata->taxid }}<br/>
            ที่อยู่ : {{ $ownerdata->address }}
            {{ $ownerdata->sub_district}}
            {{ $ownerdata->district}}
            {{ $ownerdata->province.' '.
            $ownerdata->postal_code }} <br/>
            @isset($ownerdata->phoneno)
                Tel: {{ $ownerdata->phoneno }}
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
            @foreach ($car_groups as $caritem => $car_cards)
                @php
                    $car = \App\Models\Car::find($caritem);
                    $car_recforword = \App\Models\Car_balance::where('car_id', $caritem)
                        ->where('cardoc_date', '<', $from)
                        ->where('doctype', '=', 'R')
                        ->sum('amount');
                    $car_payforword = \App\Models\Car_balance::where('car_id', $caritem)
                        ->where('cardoc_date', '<', $from)
                        ->where('doctype', '=', 'P')
                        ->sum('amount');
                    $car_bringforword = $car_recforword - $car_payforword;
                    $carbal_amount = $car_bringforword  ;
                @endphp
                <tr style="font-weight: bold;">
                    <td colspan="5">
                        {{ $car->car_regist }}
                    </td>
                    <td style="text-align: right">
                        ยอดยกมา
                    </td>
                    <td style="text-align: right">
                        {{ number_format($car_bringforword,2,'.',',') }}
                    </td>
                </tr>

                @foreach ($car_cards as $item )
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
                                    $carbal_amount +=  $item->amount ;
                                @endphp
                            @else
                                @php
                                    $carbal_amount -=  $item->amount ;
                                @endphp

                            @endif
                            {{ number_format($carbal_amount,2,'.',',') }}

                        </td>

                    </tr>
                @endforeach
                <tr style="font-weight: bold;">
                    <td colspan="4">
                            <strong>
                                รวมรายการ - {{count($car_cards)}} รายการ
                            </strong>
                    </td>

                    <td style="text-align: right">

                            @php
                                $carrec =  $car_cards->where('doctype','R')->sum('amount')
                            @endphp


                        {{ number_format($carrec,2,'.',',') }}

                    </td>
                    <td style="text-align: right">

                            @php
                                $carpay =  $car_cards->where('doctype','P')->sum('amount')
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
                        {{ number_format($car_bringforword + ($carrec - $carpay) ,2,'.',',') }}
                    </td>

                </tr>
            @endforeach

            <tr style="font-weight: bold;">
                <td colspan="4">

                        รวมรายการ - {{count($ownercards)}} รายการ

                </td>

                <td style="text-align: right">

                        @php
                            $ownerrec =  $ownercards->where('doctype','R')->sum('amount')
                        @endphp


                    {{ number_format($ownerrec,2,'.',',') }}

                </td>
                <td style="text-align: right">

                        @php
                            $ownerpay =  $ownercards->where('doctype','P')->sum('amount')
                        @endphp


                    {{ number_format($ownerpay,2,'.',',') }}

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
                    {{ number_format($bringforword + ($ownerrec - $ownerpay) ,2,'.',',') }}
                </td>

            </tr>
    </tbody>

</table>



@endsection

