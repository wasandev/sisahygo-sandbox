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
                    ประจำปี: {{ $year }}<br />

                </strong>
            </td>
        </tr>

    </table>
    <br />

    <table style="width: 100%;">
        <thead>
            <tr>
                <th style="width: 10%;">เดือน-ปี</th>
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
                <tr style="font-weight: bold;background-color:#c0c0c0">

                    <td style="text-align: center">
                        <strong>{{ $date_group }}</strong>
                    </td>
                    <td>

                    </td>
                    <td>

                    </td>

                    @php
                        $sumdate = 0;
                        $sum_general = 0;
                        $sum_charter = 0;
                        $sum_express = 0;
                    @endphp

                    @foreach ($date_groups->chunk(10) as $item)
                        @php
                            $sumdate = $sumdate + $item->sum('order_amount');
                            $sum_general =
                                $sum_general + $item->where('order_type', '=', 'general')->sum('order_amount');
                            $sum_charter =
                                $sum_charter + $item->where('order_type', '=', 'charter')->sum('order_amount');
                            $sum_express =
                                $sum_express + $item->where('order_type', '=', 'express')->sum('order_amount');

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

                    @foreach ($date_groups->chunk(20) as $chunks)
                        @foreach ($chunks as $item)
                <tr>

                    <td style="text-align: right">

                    </td>
                    <td style="text-align: center">
                        {{ $loop->iteration }}
                    </td>
                    <td>
                        {{ $item->first()->name }}
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
            @endforeach

            <tr style="font-weight: bold;background-color:#c0c0c0">
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
