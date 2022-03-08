@extends('layouts.docnojs')

@section('header')
    @include('partials.reportheader')
@endsection

@section('content')
    <table style="width:100%;">

        <tr>
            <td style="width: 50%;text-align: left;border:0px">
                สาขา {{ $branchdata->name }}<br />
                <br />
            </td>
            <td style="width:50%;text-align: right;border:0px">
                <strong>

                    ถึงวันที่: {{ date('d-m-Y', strtotime($to)) }}<br />
                </strong>
            </td>
        </tr>

    </table>
    <br />

    <table style="width: 100%;">
        <thead>
            <tr>

                <th style="width: 20%;text-align: left;">วันที่ตั้งหนี้</th>
                <th style="width: 10%;">ลำดับ</th>
                <th style="width: 25%;text-align: center;">ลูกค้า</th>
                <th style="width: 10%;text-align: center;">เลขที่ใบรับส่ง</th>
                <th style="width: 10%;text-align: center;">วันที่ใบรับส่ง</th>
                <th style="width: 10%;text-align: right;">จำนวนเงิน</th>
                <th style="width: 15%;text-align: right;">*ชำระ - วันที่</th>
            </tr>

        </thead>

        <tbody>



            @foreach ($branch_groups as $bal_item => $date_items)
                <tr style="font-weight: bold;background-color:#aaaaaa">


                    <td style="text-align: center">
                        {{ date('d-m-Y', strtotime($bal_item)) }}

                    </td>

                    <td colspan="4" style="text-align: right">
                        รวมตามวัน
                    </td>
                    <td style="text-align: right">
                        {{ number_format($date_items->sum('bal_amount'), 2, '.', ',') }}
                    </td>
                    <td style="text-align: right">
                        {{ number_format($date_items->sum('pay_amount'), 2, '.', ',') }}
                    </td>

                </tr>

                @foreach ($date_items as $item)
                    <tr>



                        <td style="text-align: center">
                        </td>
                        <td style="text-align: center">
                            {{ $loop->iteration }}
                        </td>
                        <td>
                            {{ $item->customer->name }}
                        </td>
                        <td style="text-align: center">
                            @isset($item->branchrec_order)
                                {{ $item->branchrec_order->order_header_no }}
                            @endisset

                        </td>
                        <td style="text-align: center">
                            @isset($item->branchrec_order)
                                {{ $item->branchrec_order->order_header_date->format('d/m/Y') }}
                            @endisset

                        </td>
                        <td style="text-align: right">

                            {{ number_format($item->bal_amount, 2, '.', ',') }}

                        </td>
                        <td style="text-align: right">
                            @isset($item->branchpay_date)
                                {{ number_format($item->pay_amount, 2, '.', ',') }} -
                                {{ $item->branchpay_date->format('d/m/Y') }}
                            @endisset

                        </td>
                    </tr>
                @endforeach
            @endforeach


            <tr style="font-weight:bold">
                <td colspan="5" style="text-align: center">
                    รวมทั้งหมด - {{ count($branch_balances) }} - รายการ
                </td>

                <td style="text-align: right">
                    {{ number_format($branch_balances->sum('bal_amount'), 2, '.', ',') }}
                </td>
                <td style="text-align: right">
                    {{ number_format($branch_balances->sum('pay_amount'), 2, '.', ',') }}
                </td>
            </tr>

        </tbody>

    </table>
@endsection
