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
            ระหว่างวันที่: {{date("d-m-Y", strtotime($from))}}<br/> ถึงวันที่: {{date("d-m-Y", strtotime($to))}}<br/>
            </strong>
        </td>
    </tr>

</table>
<br/>

<table style="width: 100%;" >
    <thead>
        <tr>
            <th style="width: 5%;"></th>
            <th style="width: 15%;text-align: center;"></th>
            <th style="width: 10%;text-align: center;"></th>
            <th style="width: 10%;text-align: center;"></th>
            <th style="width: 10%;text-align: center;"></th>
            <th style="width: 10%;text-align: right;"></th>
            <th style="width: 10%;text-align: right;"></th>
            <th style="width: 10%;text-align: right;"></th>
            <th style="width: 10%;text-align: right;"></th>

        </tr>
        <tr>
            <th style="width: 5%;">ลำดับ</th>
            <th style="width: 15%;text-align: center;">จ่ายให้</th>
            <th style="width: 10%;text-align: center;">ทะเบียนรถ</th>
            <th style="width: 10%;text-align: center;">วันที่จ่าย</th>
            <th style="width: 10%;text-align: center;">เลขที่เอกสาร</th>
            <th colspan="2" style="width: 20%;text-align: center;">จ่ายต้นทาง</th>
            <th colspan="2" style="width: 20%;text-align: center;">จ่ายปลายทาง</th>
        </tr>
        <tr>
            <th style="width: 5%;"></th>
            <th style="width: 15%;text-align: center;"></th>
            <th style="width: 10%;text-align: center;"></th>
            <th style="width: 10%;text-align: center;"></th>
            <th style="width: 10%;text-align: center;"></th>
            <th style="width: 10%;text-align: center;">จำนวนเงิน</th>
            <th style="width: 10%;text-align: center;">ภาษี</th>
            <th style="width: 10%;text-align: center;">จำนวนเงิน</th>
            <th style="width: 10%;text-align: center;">ภาษี</th>

        </tr>

    </thead>

    <tbody>


            @foreach ($carpayment_groups as $owneritem => $car_items)
                @php
                    $owner = \App\Models\Vendor::find($owneritem);

                    $vendor_pay1 = \App\Models\Carpayment::where('vendor_id', $owneritem)
                        ->where('status',  true)
                        ->where('type','<>','B')
                        ->where('payment_date', '>=', $from)
                        ->where('payment_date', '<=', $to)
                        ->where('tax_flag', true)
                        ->sum('amount');
                    $vendor_tax1 = \App\Models\Carpayment::where('vendor_id', $owneritem)
                        ->where('status',  true)
                        ->where('type','<>','B')
                        ->where('payment_date', '>=', $from)
                        ->where('payment_date', '<=', $to)
                        ->where('tax_flag', true)
                        ->sum('tax_amount');
                    $vendor_pay2 = \App\Models\Carpayment::where('vendor_id', $owneritem)
                        ->where('status',  true)
                        ->where('type','=','B')
                        ->where('payment_date', '>=', $from)
                        ->where('payment_date', '<=', $to)
                        ->where('tax_flag', true)
                        ->sum('amount');
                    $vendor_tax2 = \App\Models\Carpayment::where('vendor_id', $owneritem)
                        ->where('status',  true)
                        ->where('type','=','B')
                        ->where('payment_date', '>=', $from)
                        ->where('payment_date', '<=', $to)
                        ->where('tax_flag', true)
                        ->sum('tax_amount');

                @endphp
                <tr style="font-weight: bold;">
                    <td style="text-align: center">
                        {{ $loop->iteration }}
                    </td>
                    <td colspan="4">
                        {{ $owner->name}}
                    </td>
                    {{-- <td style="text-align: center">

                    </td>
                    <td style="text-align: center">

                    </td>
                    <td style="text-align: center">

                    </td> --}}

                    <td style="text-align: right">
                        @if ($vendor_pay1 > 0)
                            {{ number_format($vendor_pay1,2,'.',',') }}
                        @endif

                    </td>

                    <td style="text-align: right">
                        @if ($vendor_tax1 > 0)
                            {{ number_format($vendor_tax1,2,'.',',') }}
                        @endif

                    </td>
                    <td style="text-align: right">
                        @if ($vendor_pay2 > 0)
                            {{ number_format($vendor_pay2,2,'.',',') }}
                        @endif
                    </td>
                    <td style="text-align: right">
                        @if ($vendor_tax2 > 0)
                            {{ number_format($vendor_tax2,2,'.',',') }}
                        @endif
                    </td>
                </tr>

                @foreach ($car_items as $item => $payment_doc )
                    @php

                        $car = \App\Models\Car::find($item);
                        $car_pay1 = \App\Models\Carpayment::where('car_id', $item)
                        ->where('status',  true)
                        ->where('type','<>','B')
                        ->where('payment_date', '>=', $from)
                        ->where('payment_date', '<=', $to)
                        ->where('tax_flag', true)
                        ->sum('amount');
                        $car_tax1 = \App\Models\Carpayment::where('car_id', $item)
                        ->where('status',  true)
                        ->where('type','<>','B')
                        ->where('payment_date', '>=', $from)
                        ->where('payment_date', '<=', $to)
                        ->where('tax_flag', true)
                        ->sum('tax_amount');
                        $car_pay2 = \App\Models\Carpayment::where('car_id', $item)
                        ->where('status',  true)
                        ->where('type','=','B')
                        ->where('payment_date', '>=', $from)
                        ->where('payment_date', '<=', $to)
                        ->where('tax_flag', true)
                        ->sum('amount');
                        $car_tax2 = \App\Models\Carpayment::where('car_id', $item)
                        ->where('status',  true)
                        ->where('type','=','B')
                        ->where('payment_date', '>=', $from)
                        ->where('payment_date', '<=', $to)
                        ->where('tax_flag', true)
                        ->sum('tax_amount');

                    @endphp
                        <tr style="vertical-align: top">
                            <td style="text-align: center">

                            </td>
                            <td style="text-align: center"></td>
                            <td style="text-align: center">
                                {{ $car->car_regist }}
                            </td>
                            <td>
                            </td>
                            <td>

                            </td>


                            <td style="text-align: right">
                                @if ($car_pay1 > 0)
                                    {{ number_format($car_pay1,2,'.',',') }}
                                @endif
                            </td>
                            <td style="text-align: right">
                                @if($car_tax1 > 0)
                                    {{ number_format($car_tax1,2,'.',',') }}
                                @endif
                            </td>
                            <td style="text-align: right">
                                @if ($car_pay2 > 0)
                                    {{ number_format($car_pay2,2,'.',',') }}
                                @endif
                            </td>
                            <td style="text-align: right">
                                @if($car_tax2 > 0)
                                    {{ number_format($car_tax2,2,'.',',') }}
                                @endif
                            </td>

                        </tr>
                    @foreach ($payment_doc as $carpayment )
                        <tr style="vertical-align: top">
                            <td style="text-align: center">
                                {{ $loop->iteration }}
                            </td>
                            <td style="text-align: center">

                            </td>
                            <td style="text-align: center">
                            </td>
                            <td style="text-align: center">
                                {{ date("d-m-Y", strtotime($carpayment->payment_date)) }}
                            </td>
                            <td style="text-align: center">
                                {{ $carpayment->payment_no }}

                            </td>


                            <td style="text-align: right">
                                @if ($carpayment->type <> 'B')
                                    {{ number_format($carpayment->amount,2,'.',',') }}
                                @else

                                @endif

                            </td>
                            <td style="text-align: right">
                                @if ($carpayment->type <> 'B')
                                    {{ number_format($carpayment->tax_amount,2,'.',',') }}
                                @else

                                @endif
                            </td>
                            <td style="text-align: right">
                                @if ($carpayment->type  == 'B')
                                    {{ number_format($carpayment->amount,2,'.',',') }}
                                @else

                                @endif

                            </td>
                            <td style="text-align: right">
                                 @if ($carpayment->type  == 'B')
                                    {{ number_format($carpayment->tax_amount,2,'.',',') }}
                                @else

                                @endif
                            </td>

                        </tr>
                    @endforeach
                @endforeach


            @endforeach
            <tr style="font-weight: bold;">

                <td  colspan="5" style="text-align: center">
                    <strong>
                        รวมทั้งหมด
                    </strong>
                </td>

                <td style="text-align: right">
                    @php
                        $carpay1 =  $carpayments->where('type','<>','B')->sum('amount')
                    @endphp
                    {{ number_format($carpay1,2,'.',',') }}
                </td>
                <td style="text-align: right">
                    @php
                        $cartax1 =  $carpayments->where('type','<>','B')->sum('tax_amount')
                    @endphp
                    {{ number_format($cartax1,2,'.',',') }}
                </td>
                <td style="text-align: right">
                   @php
                        $carpay2 =  $carpayments->where('type','=','B')->sum('amount')
                    @endphp
                    {{ number_format($carpay2,2,'.',',') }}
                </td>
                <td style="text-align: right">
                    @php
                        $cartax2 =  $carpayments->where('type','=','B')->sum('tax_amount')
                    @endphp
                    {{ number_format($cartax2,2,'.',',') }}
                </td>


            </tr>
    </tbody>

</table>



@endsection

