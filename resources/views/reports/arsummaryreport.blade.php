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
                    ระหว่างวันที่: {{ date('d-m-Y', strtotime($from)) }}<br /> ถึงวันที่:
                    {{ date('d-m-Y', strtotime($to)) }}<br />
                </strong>
            </td>
        </tr>

    </table>
    <br />

    <table style="width: 100%;">
        <thead>
            <tr>
                <th style="width: 5%;">ลำดับ</th>
                <th style="width: 35%;text-align: center;">ชื่อลูกค้า</th>
                <th style="width: 15%;text-align: center;">ยอดยกมา</th>
                <th style="width: 15%;text-align: center;">ยอดตั้งหนี้</th>
                <th style="width: 15%;text-align: center;">ยอดชำระหนี้</th>
                <th style="width: 15%;text-align: center;">ยอดคงเหลือ</th>
            </tr>

        </thead>

        <tbody>
            @php
                $sum_forword = 0;
                $itemlist = 0;
            @endphp
            @foreach ($ar_groups as $ar_list => $ar_items)
                @php
                    $ardata = \App\Models\Ar_customer::find($ar_list);
                    $payforword = \App\Models\Ar_balance::where('customer_id', $ar_list)
                        ->where('docdate', '<', $from)
                        ->where('doctype', '=', 'P')
                        ->sum('ar_amount');
                    $recforword = \App\Models\Ar_balance::where('customer_id', $ar_list)
                        ->where('docdate', '<', $from)
                        ->where('doctype', '=', 'R')
                        ->sum('ar_amount');
                    $ar_bringforword = $payforword - $recforword;
                    $ar_paybalance = $ar_items
                        ->where('doctype', '=', 'P')
                        ->where('docdate', '>=', $from)
                        ->sum('ar_amount');
                    $ar_recbalance = $ar_items
                        ->where('doctype', '=', 'R')
                        ->where('docdate', '>=', $from)
                        ->sum('ar_amount');
                    $ar_balamount = $ar_bringforword + $ar_paybalance - $ar_recbalance;
                    $sum_forword += $ar_bringforword;
                @endphp
                @if ($ar_balamount != 0 || $ar_bringforword != 0 || $ar_paybalance != 0 || $ar_recbalance != 0)
                    @php
                        $itemlist++;
                    @endphp
                    <tr style="vertical-align: top">
                        <td style="text-align: center">{{ $itemlist }}</td>

                        <td style="text-align: left">{{ $ardata->name }}</td>

                        <td style="text-align: right">{{ number_format($ar_bringforword, 2, '.', ',') }}</td>

                        <td style="text-align: right">{{ number_format($ar_paybalance, 2, '.', ',') }}</td>

                        <td style="text-align: right">{{ number_format($ar_recbalance, 2, '.', ',') }}</td>


                        <td style="text-align: right">

                            {{ number_format($ar_balamount, 2, '.', ',') }}

                        </td>



                    </tr>
                @endif
            @endforeach


            <tr style="font-weight: bold;">
                <td colspan="2">
                    <strong>
                        รวมทั้งหมด
                    </strong>
                </td>
                <td style="text-align: right">
                    {{ number_format($sum_forword, 2, '.', ',') }}
                </td>
                <td style="text-align: right">
                    @php
                        $arpay = $ar_balances->where('doctype', 'P')->where('docdate', '>=', $from)->sum('ar_amount');
                    @endphp
                    {{ number_format($arpay, 2, '.', ',') }}
                </td>
                <td style="text-align: right">
                    @php
                        $arrec = $ar_balances->where('doctype', 'R')->where('docdate', '>=', $from)->sum('ar_amount');
                    @endphp
                    {{ number_format($arrec, 2, '.', ',') }}
                </td>
                <td style="text-align: right">
                    {{ number_format($sum_forword + $arpay - $arrec, 2, '.', ',') }}
                </td>

            </tr>

        </tbody>

    </table>
@endsection
