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
                <th style="width: 5%;">ลำดับ</th>
                <th style="width: 15%;text-align: center;">วันที่</th>
                <th style="width: 15%;text-align: center;">เลขที่เอกสาร</th>
                <th style="width: 15%;text-align: center;">เลขที่ใบแจ้งหนี้</th>
                <th style="width: 15%;text-align: center;">วันที่ครบกำหนด</th>
                <th style="width: 35%;text-align: right;">จำนวนเงิน</th>
            </tr>

        </thead>

        <tbody>
            @php

                $itemlist = 0;
            @endphp
            @foreach ($ar_groups as $ar_item => $ar_balance)
                @php
                    $itemlist++;
                @endphp
                <tr style="font-weight:bold">
                    <td colspan="4" style="text-align: left">
                        @php
                            $customer = \App\Models\Ar_customer::find($ar_item);
                        @endphp
                        {{ $itemlist }} - {{ $customer->name }}
                    </td>
                    <td style="text-align: center">
                        รวม
                    </td>
                    <td style="text-align: right">
                        {{ number_format($aroutstandings->where('customer_id', '=', $ar_item)->sum('ar_amount'), 2, '.', ',') }}
                    </td>
                </tr>


                @foreach ($ar_balance as $item)
                    <tr style="vertical-align: top;">
                        <td style="text-align: center">
                            {{ $loop->iteration }}
                        </td>

                        <td style="text-align: center">
                            {{ $item->docdate->format('d/m/Y') }}
                        </td>
                        <td style="text-align: center">
                            {{ $item->docno }}
                        </td>
                        <td style="text-align: center">
                            @isset($item->invoice->invoice_no)
                                {{ $item->invoice->invoice_no }}
                            @endisset

                        </td>

                        <td style="text-align: center">
                            @isset($item->invoice->invoice_no)
                                {{ $item->invoice->due_date->format('d/m/Y') }}
                            @endisset
                        </td>
                        <td style="text-align: right">
                            {{ number_format($item->ar_amount, 2, '.', ',') }}
                        </td>
                    </tr>
                @endforeach
            @endforeach

            <tr style="font-weight:bold">
                <td colspan="5" style="text-align: left">
                    รวมทั้งหมด
                </td>
                <td style="text-align: right">
                    {{ number_format($aroutstandings->sum('ar_amount'), 2, '.', ',') }}
                </td>
            </tr>

        </tbody>

    </table>
@endsection
