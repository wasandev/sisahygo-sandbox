@extends('layouts.doclandscapenojs')

@section('header')
    @include('partials.reportheader')

@endsection

@section('content')

<table style="width:100%;">

    <tr>
        <td style="width: 50%;text-align: left;border:0px">
            สาขา {{ $branchdata->name }}<br/>
            <br/>
        </td>
        <td style="width:50%;text-align: right;border:0px">
            <strong>
            จากวันที่: {{ date("d-m-Y", strtotime($from))}}<br/>
            ถึงวันที่: {{ date("d-m-Y", strtotime($to))}}<br/>
            </strong>
        </td>
    </tr>

</table>
<br/>

<table style="width: 100%;" >
    <thead>
        <tr>

            <th style="width: 10%;text-align: center;">วันที่รับเงิน</th>
            <th style="width: 5%;text-align:center">ลำดับ</th>
            <th style="width: 15%;text-align: center;">ลูกค้า</th>
            <th style="width: 10%;text-align: center;">เลขที่ใบรับส่ง</th>
            <th style="width: 10%;text-align: center;">วันที่ใบรับส่ง</th>
            <th style="width: 10%;text-align: center;">ใบเสร็จรับเงิน</th>
            <th style="width: 10%;text-align: center;">ค่าขนส่ง</th>
            <th style="width: 5%;text-align: center;">ส่วนลด</th>
            <th style="width: 5%;text-align: center;">ภาษีฯ</th>
            <th style="width: 10%;text-align: center;">ยอดรับชำระ</th>
            <th style="width: 10%;text-align: center;">การจัดส่ง</th>

        </tr>

    </thead>

    <tbody>

        @foreach ($branch_groups as $date_item => $date_items)

            <tr style="font-weight: bold;background-color:#aaaaaa">

                <td  style="text-align: left">
                    {{ date("d-m-Y", strtotime($date_item))}}

                </td>

                <td colspan="5" style="text-align: right">
                    รวมตามวัน
                </td>
                @php
                    $datebal_amount= $date_items->sum(function ($group) {
                        return $group->sum('bal_amount');
                    });
                    $datediscount_amount= $date_items->sum(function ($group) {
                        return $group->sum('discount_amount');
                    });
                    $datetax_amount= $date_items->sum(function ($group) {
                        return $group->sum('tax_amount');
                    });
                    $datepay_amount= $date_items->sum(function ($group) {
                        return $group->sum('pay_amount');
                    });
                @endphp

                <td style="text-align: right">

                    {{ number_format($datebal_amount,2,'.',',') }}

                </td>
                <td style="text-align: right">
                    {{ number_format($datediscount_amount,2,'.',',') }}
                </td>
                <td style="text-align: right">
                    {{ number_format($datetax_amount,2,'.',',') }}
                </td>

                <td style="text-align: right">
                    {{ number_format($datepay_amount,2,'.',',') }}
                </td>
                <td>
                </td>
            </tr>

            @foreach ($date_items as $item_type => $receipt_type)
                <tr style="font-weight: bold;background-color:#d4d4d4">
                    <td  style="text-align: center">
                        @if($item_type == 'C')
                            เงินสด

                        @else
                            เงินโอน
                        @endif
                    </td>
                    <td colspan="5" style="text-align: right">
                            รวมตามประเภท
                    </td>

                    <td style="text-align: right">
                        {{ number_format($receipt_type->sum('bal_amount'),2,'.',',') }}
                    </td>
                    <td style="text-align: right">
                        {{ number_format($receipt_type->sum('discount_amount'),2,'.',',') }}
                    </td>
                    <td style="text-align: right">
                        {{ number_format($receipt_type->sum('tax_amount'),2,'.',',') }}
                    </td>

                    <td style="text-align: right">
                        {{ number_format($receipt_type->sum('pay_amount') ,2,'.',',') }}
                    </td>
                    <td>
                    </td>
                </tr>

                @foreach ($receipt_type->chunk(100) as $chunks)
                    @foreach ($chunks  as $item )

                        <tr style="font-weight:normal">
                            <td  style="text-align: center">
                            </td>
                            <td style="text-align: center">
                                {{ $loop->iteration }}
                            </td>
                            <td>
                                {{ $item->customer->name}}
                            </td>
                            <td style="text-align: center">
                                {{ $item->branchrec_order->order_header_no}}
                            </td>

                            <td style="text-align: center">

                                {{ date("d-m-Y", strtotime($item->branchrec_order->order_header_date)) }}
                            </td>
                            <td style="text-align: center">
                                @isset($item->receipt_id)
                                    {{ $item->receipt->receipt_no}}
                                @endisset

                            </td>
                            <td style="text-align: right">
                                {{ number_format($item->bal_amount,2,'.',',') }}
                            </td>

                            <td style="text-align: right">
                                {{ number_format($item->discount_amount,2,'.',',') }}
                            </td>
                            <td style="text-align: right">
                                {{ number_format($item->tax_amount,2,'.',',') }}
                            </td>
                            <td style="text-align: right">
                                {{ number_format($item->pay_amount,2,'.',',') }}
                            </td>
                            <td>
                                @isset($item->delivery->delivery_no)
                                    {{$item->delivery->delivery_no}}
                                @endisset
                                @empty($item->delivery_id)
                                    รับเอง
                                @endempty
                            </td>
                        </tr>

                    @endforeach
                @endforeach
            @endforeach
        @endforeach


        <tr style="font-weight: bold;background-color:#c0c0c0">
            <td colspan="5" style="text-align: center">
                รวมทั้งหมด -  {{count($branch_balances)}} - รายการ
            </td>
            <td>
            </td>
            <td style="text-align: right">
                {{ number_format($branch_balances->sum('bal_amount'),2,'.',',') }}
            </td>
            <td style="text-align: right">
                {{ number_format($branch_balances->sum('discount_amount'),2,'.',',') }}
            </td>
            <td style="text-align: right">
                {{ number_format($branch_balances->sum('tax_amount'),2,'.',',') }}
            </td>
            <td style="text-align: right">
                {{ number_format($branch_balances->sum('pay_amount'),2,'.',',') }}
            </td>
            <td>

            </td>
        </tr>

    </tbody>

</table>



@endsection

