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
            <th style="width: 30%;text-align: left;">รับจาก</th>
            <th style="width: 15%;text-align: center;">ทะเบียนรถ</th>
            <th style="width: 15%;text-align: center;">รับโดย</th>
            <th style="width: 15%;text-align: right;">จำนวนเงิน</th>

        </tr>

    </thead>

    <tbody>
       @foreach ($receive_date as $date_item => $date_groups)
            <tr style="font-weight: bold;">
                <td></td>
                <td style="text-align: center">
                    {{ $date_item }}
                </td>
                <td colspan="4" style="text-align: left">
                    จำนวน {{count($date_groups)}} รายการ
                </td>
                <td style="text-align: right">
                    {{ number_format($date_groups->sum('amount'),2,'.',',') }}
                </td>


            </tr>
            @foreach ($date_groups as $item )
            <tr style="vertical-align: top">
                <td style="text-align: center">{{ $loop->iteration }}</td>
                <td></td>
                <td>{{ $item->receive_no }}</td>
                <td>{{ $item->vendor->name }}</td>
                <td>{{ $item->car->car_regist }}</td>
                <td style="text-align: center">
                    @if ($item->receive_by == 'H')
                        เงินสด
                    @elseif($item->receive_by == 'T')
                        เงินโอน
                    @elseif($item->receive_by == 'Q')
                        เช็ค
                    @else
                        ตัดบัญชี
                    @endif


                </td>

                <td style="text-align: right">
                    {{ number_format($item->amount,2,'.',',') }}
                </td>



            </tr>
            @endforeach

        @endforeach
        <tr style="font-weight: bold;">
            <td colspan="6">
                    <strong>
                    รวมทั้งหมด - {{count($carreceives)}} รายการ
                    </strong>
            </td>

            <td style="text-align: right">
                {{ number_format($carreceives->sum('amount'),2,'.',',') }}
            </td>


        </tr>
    </tbody>

</table>



@endsection

