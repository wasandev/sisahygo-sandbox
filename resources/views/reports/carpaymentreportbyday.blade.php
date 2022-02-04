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
            <th style="width: 5%;">ลำดับ</th>
            <th style="width: 10%;text-align: left;">วันที่</th>
            <th style="width: 10%;text-align: left;">เลขที่เอกสาร</th>
            <th style="width: 10%;text-align: left;">ประเภท</th>
            <th style="width: 15%;text-align: left;">จ่ายให้</th>
            <th style="width: 10%;text-align: center;">ทะเบียนรถ</th>
            <th style="width: 10%;text-align: center;">จ่ายโดย</th>
            <th style="width: 10%;text-align: right;">จำนวนเงิน</th>
            <th style="width: 10%;text-align: right;">ภาษีหัก ณ ที่จ่าย</th>
            <th style="width: 10%;text-align: right;">ยอดจ่ายสุทธิ</th>
        </tr>

    </thead>

    <tbody>
       @foreach ($payment_date as $date_item => $date_groups)
            <tr style="font-weight: bold;">
                <td></td>
                <td style="text-align: center">
                    {{ $date_item }}
                </td>
                <td colspan="5" style="text-align: left">
                    จำนวน {{count($date_groups)}} รายการ
                </td>
                <td style="text-align: right">
                    {{ number_format($date_groups->sum('amount'),2,'.',',') }}
                </td>
                <td style="text-align: right">
                    {{ number_format($date_groups->sum('tax_amount'),2,'.',',') }}
                </td>
                <td style="text-align: right">
                    {{ number_format( $date_groups->sum('amount') - $date_groups->sum('tax_amount'),2,'.',',') }}
                </td>

            </tr>
            @foreach ($date_groups as $item )
            <tr style="vertical-align: top">
                <td style="text-align: center">{{ $loop->iteration }}</td>
                <td></td>
                <td>{{ $item->payment_no }}</td>
                <td>
                    @if($item->type == 'T')
                        ค่าขนส่ง
                    @elseif($item->type == 'O')
                        อื่นๆ
                    @else
                        เก็บปลายทาง
                    @endif
                </td>

                <td>{{ $item->vendor->name }}</td>
                <td>{{ $item->car->car_regist }}</td>
                <td style="text-align: center">
                    @if ($item->payment_by == 'H')
                        เงินสด
                    @elseif($item->payment_by == 'T')
                        เงินโอน
                    @elseif($item->payment_by == 'Q')
                        เช็ค
                    @else
                        ตัดบัญชี
                    @endif


                </td>

                <td style="text-align: right">
                    {{ number_format($item->amount,2,'.',',') }}
                </td>
                <td style="text-align: right">
                    {{ number_format($item->tax_amount,2,'.',',') }}
                </td>
                <td style="text-align: right">
                    {{ number_format($item->amount - $item->tax_amount,2,'.',',') }}
                </td>


            </tr>
            @endforeach

        @endforeach
        <tr style="font-weight: bold;">
            <td colspan="7">
                    <strong>
                    รวมทั้งหมด - {{count($carpayments)}} รายการ
                    </strong>
            </td>

            <td style="text-align: right">
                {{ number_format($carpayments->sum('amount'),2,'.',',') }}
            </td>
            <td style="text-align: right">
                {{ number_format($carpayments->sum('tax_amount'),2,'.',',') }}
            </td>
            <td style="text-align: right">
                {{ number_format($carpayments->sum('amount') - $carpayments->sum('tax_amount') ,2,'.',',') }}
            </td>

        </tr>
    </tbody>

</table>



@endsection

