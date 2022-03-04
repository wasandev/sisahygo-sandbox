@extends('layouts.docnojs')

@section('header')
    @include('partials.reportheader')
@endsection

@section('content')
    <table style="width:100%;">

        <tr>
            <td style="width: 50%;text-align: left;border:0px">
                <strong>
                    @if ($branchdata != null)
                        สาขา {{ $branchdata->name }}
                    @else
                        ทุกสาขา
                    @endif
                </strong>
            </td>
            <td style="width:50%;text-align: right;border:0px">
                <strong>
                    ระหว่างวันที่: {{ date('d-m-Y', strtotime($from)) }}<br /> ถึงวันที่:
                    {{ date('d-m-Y', strtotime($to)) }}

                </strong>
            </td>
        </tr>

    </table>
    <br />

    <table style="width: 100%;">
        <thead>
            <tr>
                <th style="width: 10%;">วันที่</th>
                <th style="width: 5%;">ลำดับ</th>
                <th style="width: 40%;text-align: left;">สาขาปลายทาง</th>
                <th style="width: 10%;text-align: center;">ทั่วไป</th>
                <th style="width: 10%;text-align: center;">เหมาคัน</th>
                <th style="width: 10%;text-align: center;">Express</th>
                <th style="width: 15%;text-align: center;">รวมจำนวนเงิน</th>
            </tr>

        </thead>

        <tbody>
            @foreach ($order_date as $date_group => $date_groups)
                <tr>

                    <td style="text-align: center">
                        <strong>{{ $date_group }}</strong>
                    </td>
                    <td style="text-align: left">

                    </td>
                    <td style="text-align: left">

                    </td>

                    @php
                        $sumdate = 0;
                        $sum_general = 0;
                        $sum_charter = 0;
                        $sum_express = 0;
                    @endphp

                    @foreach ($date_groups as $item)
                        @php
                            $sumdate = $sumdate + $item->sum('order_amount');
                            $sum_general = $sum_general + $item->where('order_type', '=', 'general')->sum('order_amount');
                            $sum_charter = $sum_charter + $item->where('order_type', '=', 'charter')->sum('order_amount');
                            $sum_express = $sum_express + $item->where('order_type', '=', 'express')->sum('order_amount');

                        @endphp
                    @endforeach
                    <td style="text-align: right">
                        <strong>
                            {{ number_format($sum_general, 2, '.', ',') }}
                            <strong>
                    </td>
                    <td style="text-align: right">
                        <strong>
                            {{ number_format($sum_charter, 2, '.', ',') }}
                            <strong>
                    </td>
                    <td style="text-align: right">
                        <strong>
                            {{ number_format($sum_express, 2, '.', ',') }}
                            <strong>

                    </td>
                    <td style="text-align: right">
                        <strong>
                            {{ number_format($sumdate, 2, '.', ',') }}
                            <strong>
                    </td>
                </tr>
                @foreach ($date_groups as $item)
                    <tr>

                        <td style="text-align: right">

                        </td>
                        <td style="text-align: center">
                            {{ $loop->iteration }}
                        </td>
                        <td>
                            {{ $item->first()->to_branch->name }}
                        </td>
                        <td style="text-align: right">
                            {{ number_format($item->where('order_type', '=', 'general')->sum('order_amount'), 2, '.', ',') }}
                        </td>
                        <td style="text-align: right">
                            {{ number_format($item->where('order_type', '=', 'charter')->sum('order_amount'), 2, '.', ',') }}
                        </td>
                        <td style="text-align: right">
                            {{ number_format($item->where('order_type', '=', 'express')->sum('order_amount'), 2, '.', ',') }}
                        </td>
                        <td style="text-align: right">
                            {{ number_format($item->sum('order_amount'), 2, '.', ',') }}
                        </td>
                    </tr>
                @endforeach
            @endforeach
            <tr style="font-weight: bold;">
                <td colspan="3">
                    <strong>
                        รวมทั้งหมด
                    </strong>
                </td>
                <td style="text-align: right">
                    {{ number_format($order->where('order_type', '=', 'general')->sum('order_amount'), 2, '.', ',') }}
                </td>
                <td style="text-align: right">
                    {{ number_format($order->where('order_type', '=', 'charter')->sum('order_amount'), 2, '.', ',') }}
                </td>
                <td style="text-align: right">
                    {{ number_format($order->where('order_type', '=', 'express')->sum('order_amount', '='), 2, '.', ',') }}
                </td>
                <td style="text-align: right">
                    {{ number_format($order->sum('order_amount'), 2, '.', ',') }}
                </td>





            </tr>
        </tbody>

    </table>
@endsection
