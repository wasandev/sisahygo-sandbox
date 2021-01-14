@extends('layouts.report')


@section('content')

<table style="width:100%;padding: 0px;margin: 0px;">
    <tr>
        <p style="text-align: center;font-size: 1.0em;padding: 0px">
            รายงานรถออกประจำวัน<br>
            <span style="text-align: center;font-size: 1.0em;padding: 0px">
            จากวันที่ {{$from_date }} ถึงวันที่ {{ $to_date }}</span>
        </p>
    </tr>
</table>



<table style="padding: 5px;" cellspacing="3" cellpadding="5">
    <thead>
        <tr>
            <th>ลำดับ</th>
            <th>เลขที่ใบกำกับ</th>
            <th>ทะเบียนรถ</th>
            <th>ประเภทรถ</th>
            <th>ประเภท</th>
            <th>ค่าระวาง</th>
            <th>ค่าบรรทุก</th>
            <th>%รายได้</th>
            <th>รายได้</th>
            <th>ต้นทาง</th>
            <th>วางบิล</th>
            <th>ปลายทาง</th>

        </tr>

    </thead>

    <tbody style="vertical-align: top;">

        @foreach ($waybill_branch as $branch => $waybill_groups)

            <tr>

                <td colspan="5">
                    <strong>
                    {{$branch}} - {{count($waybill_groups)}} เที่ยว
                    </strong>
                </td>
                <td  style="text-align: right;">
                    <strong>
                    {{ number_format($waybill_groups->sum('waybill_amount'),2,'.',',')}}
                    </strong>
                </td>
                <td style="text-align: right;">
                    <strong>
                    {{ number_format($waybill_groups->sum('waybill_payable'),2,'.',',')}}
                    </strong>
                </td>
                <td style="text-align: right;">
                    <strong>
                    {{ number_format( ($waybill_groups->sum('waybill_income') / $waybill_groups->sum('waybill_amount'))  * 100 ,2,'.',',')}}
                    </strong>
                </td>
                <td style="text-align: right;">
                    <strong>
                    {{ number_format($waybill_groups->sum('waybill_income'),2,'.',',')}}
                    </strong>
                </td>
                <td style="text-align: right;">
                    @php
                        $order_h = 0 ;
                    @endphp

                    @foreach ($waybill_groups as $item)
                    @php
                        $order_h = $order_h + $item->order_loaders->whereIn('paymenttype',['H','T'])->sum('order_amount')
                    @endphp

                    @endforeach
                    <strong>
                    {{ number_format($order_h,2,'.',',') }}
                    </strong>
                </td>
                <td style="text-align: right;">
                    @php
                        $order_f = 0 ;
                    @endphp

                    @foreach ($waybill_groups as $item)
                    @php
                        $order_f = $order_f + $item->order_loaders->whereIn('paymenttype',['F','L'])->sum('order_amount')
                    @endphp

                    @endforeach
                    <strong>
                    {{ number_format($order_f,2,'.',',') }}
                    </strong>
                </td>
                <td style="text-align: right;">
                     @php
                        $order_e = 0 ;
                    @endphp

                    @foreach ($waybill_groups as $item)
                    @php
                        $order_e = $order_e + $item->order_loaders->where('paymenttype','E')->sum('order_amount')
                    @endphp

                    @endforeach
                    <strong>
                    {{ number_format($order_e,2,'.',',') }}
                    </strong>
                </td>

            </tr>
            @foreach ($waybill_groups as $item )
            <tr style="vertical-align: top;">
                <td style="text-align: center">
                    {{ $loop->iteration }}
                </td>
                <td>
                     {{$item->waybill_no}}
                </td>

                <td>
                    {{$item->car->car_regist}}
                </td>
                <td>
                    {{$item->car->cartype->name}}
                </td>
                <td>
                    @if($item->waybill_type === 'general')
                        เบ็ดเตล็ด
                    @elseif ($item->waybill_type === 'express')
                        Express
                    @else
                        เหมาคัน'
                    @endif

                </td>
                <td style="text-align: right">
                    {{number_format($item->waybill_amount,2,'.',',')}}

                </td>
                 <td style="text-align: right">
                    {{number_format($item->waybill_payable,2,'.',',')}}

                </td>
                <td style="text-align: right">
                    {{number_format(($item->waybill_income/$item->waybill_amount)*100,2,'.',',')}}

                </td>
                <td style="text-align: right">
                    {{number_format($item->waybill_income,2,'.',',')}}
                </td>
                <td style="text-align: right;">
                    {{ number_format($item->order_loaders->whereIn('paymenttype',['H','T'])->sum('order_amount'),2,'.',',') }}

                </td>
                <td style="text-align: right;">
                    {{ number_format($item->order_loaders->whereIn('paymenttype',['F','L'])->sum('order_amount'),2,'.',',') }}

                </td>
                <td style="text-align: right;">
                    {{ number_format($item->order_loaders->where('paymenttype','E')->sum('order_amount'),2,'.',',') }}


                </td>
            </tr>
            @endforeach
        @endforeach

    </tbody>
    <tr>

                <td colspan="5">
                    <strong>
                    รวมทั้งหมด - {{count($waybills)}} เที่ยว
                    </strong>
                </td>
                <td  style="text-align: right;">
                    <strong>
                    {{ number_format($waybills->sum('waybill_amount'),2,'.',',')}}
                    </strong>
                </td>
                <td style="text-align: right;">
                    <strong>
                    {{ number_format($waybills->sum('waybill_payable'),2,'.',',')}}
                    </strong>
                </td>
                <td style="text-align: right;">
                    <strong>
                    {{ number_format( ($waybills->sum('waybill_income') / $waybills->sum('waybill_amount'))  * 100 ,2,'.',',')}}
                    </strong>
                </td>
                <td style="text-align: right;">
                    <strong>
                    {{ number_format($waybills->sum('waybill_income'),2,'.',',')}}
                    </strong>
                </td>
                <td style="text-align: right;">
                    @php
                        $orderall_h = 0 ;
                    @endphp

                    @foreach ($waybills as $item)
                    @php
                        $orderall_h = $orderall_h + $item->order_loaders->whereIn('paymenttype',['H','E'])->sum('order_amount')
                    @endphp

                    @endforeach
                    <strong>
                    {{ number_format($orderall_h,2,'.',',') }}
                    </strong>
                </td>
                <td style="text-align: right;">
                    @php
                        $orderall_f = 0 ;
                    @endphp

                    @foreach ($waybills as $item)
                    @php
                        $orderall_f = $orderall_f + $item->order_loaders->whereIn('paymenttype',['F','L'])->sum('order_amount')
                    @endphp

                    @endforeach
                    <strong>
                    {{ number_format($orderall_f,2,'.',',') }}
                    </strong>
                </td>
                <td style="text-align: right;">
                    @php
                        $orderall_e = 0 ;
                    @endphp

                    @foreach ($waybills as $item)
                    @php
                        $orderall_e = $orderall_e + $item->order_loaders->where('paymenttype','E')->sum('order_amount')
                    @endphp

                    @endforeach
                    <strong>
                    {{ number_format($orderall_e,2,'.',',') }}
                    </strong>
                </td>

            </tr>
</table>
@section('footer')
    @include('partials.reportfooter')
@endsection

