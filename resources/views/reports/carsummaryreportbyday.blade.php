@extends('layouts.docnojs')

@section('header')
    @include('partials.reportheader')

@endsection

@section('content')

<table style="width:100%;">

    <tr>
        <td style="width: 50%;text-align: left;border:0px">


        </td>
        <td style="width:50%;text-align: right;border:0px">
            <strong>
            ถึงวันที่: {{date("d-m-Y", strtotime($to))}}<br/>
            </strong>
        </td>
    </tr>

</table>
<br/>

<table style="width: 100%;" >
    <thead>
        <tr>
            <th style="width: 5%;">ลำดับ</th>
            <th style="width: 20%;text-align: center;">ชื่อเจ้าของรถ</th>
            <th style="width: 15%;text-align: center;">ทะเบียนรถ</th>
            <th style="width: 20%;text-align: right;">รับ</th>
            <th style="width: 20%;text-align: right;">จ่าย</th>
            <th style="width: 20%;text-align: right;">คงเหลือ</th>
        </tr>

    </thead>

    <tbody>
            @php
                $car_count = 0;
            @endphp

            @foreach ($balance_groups as $owneritem => $car_items)
                @php
                    $owner = \App\Models\Vendor::find($owneritem);
                    $vendor_recbalance = \App\Models\Car_balance::where('vendor_id', $owneritem)
                        ->where('cardoc_date', '<=', $to)
                        ->where('doctype', '=', 'R')
                        ->sum('amount');
                    $vendor_paybalance = \App\Models\Car_balance::where('vendor_id', $owneritem)
                        ->where('cardoc_date', '<=', $to)
                        ->where('doctype', '=', 'P')
                        ->sum('amount');
                    $vendor_balance = $vendor_recbalance - $vendor_paybalance;
                    $car_count += count($car_items);

                @endphp
                <tr style="font-weight: bold;">
                    <td>
                    </td>
                    <td>
                        {{ $owner->name}}
                    </td>
                    <td style="text-align: center">
                         รวม - {{count($car_items)}} คัน
                    </td>

                    <td style="text-align: right">
                        {{ number_format($vendor_recbalance,2,'.',',') }}
                    </td>
                    <td style="text-align: right">
                        {{ number_format($vendor_paybalance,2,'.',',') }}
                    </td>
                    <td style="text-align: right">
                        {{ number_format($vendor_balance,2,'.',',') }}
                    </td>
                </tr>

                @foreach ($car_items as $item => $balance_doc )
                    @php

                        $car = \App\Models\Car::find($item);
                        $car_recbalance = \App\Models\Car_balance::where('car_id', $item)
                            ->where('cardoc_date', '<=', $to)
                            ->where('doctype', '=', 'R')
                            ->sum('amount');
                        $car_paybalance = \App\Models\Car_balance::where('car_id', $item)
                            ->where('cardoc_date', '<=', $to)
                            ->where('doctype', '=', 'P')
                            ->sum('amount');
                        $car_balance = $car_recbalance - $car_paybalance;

                    @endphp
                    <tr style="vertical-align: top">
                        <td style="text-align: center">
                            {{ $loop->iteration }}
                        </td>
                        <td style="text-align: center"></td>
                        <td style="text-align: center">
                            {{ $car->car_regist }}
                        </td>

                        <td style="text-align: right">
                            {{ number_format($car_recbalance,2,'.',',') }}
                        </td>

                        <td style="text-align: right">
                            {{ number_format($car_paybalance,2,'.',',') }}
                        </td>
                        <td style="text-align: right">
                            {{ number_format($car_balance,2,'.',',') }}
                        </td>

                    </tr>
                @endforeach


            @endforeach
            <tr style="font-weight: bold;">

                <td  colspan="3" style="text-align: center">
                    <strong>
                        รวมทั้งหมด - {{$car_count}} คัน
                    </strong>
                </td>

                <td style="text-align: right">
                    @php
                        $carrec =  $car_balances->where('doctype','R')->sum('amount')
                    @endphp
                    {{ number_format($carrec,2,'.',',') }}
                </td>
                <td style="text-align: right">
                    @php
                        $carpay =  $car_balances->where('doctype','P')->sum('amount')
                    @endphp
                    {{ number_format($carpay,2,'.',',') }}
                </td>
                <td style="text-align: right">
                    {{ number_format($carrec - $carpay ,2,'.',',') }}
                </td>

            </tr>
    </tbody>

</table>



@endsection

