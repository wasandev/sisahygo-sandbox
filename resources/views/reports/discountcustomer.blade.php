@extends('layouts.doclandscapenojs')

@section('header')
    @include('partials.reportheader')
@endsection



@section('content')
    <table style="width:100%;">
        <tr>
            <td style="width: 50%;text-align: left;border:0px">

                ระหว่างวันที่: {{ date('d-m-Y', strtotime($from)) }} ถึงวันที่: {{ date('d-m-Y', strtotime($to)) }}

            </td>
            <td style="width:50%;text-align: right;border:0px">

                เรียงตามวันที่ สาขา และประเภทการชำระเงิน

            </td>
        </tr>
    </table>



    <table style="padding: 5px; width:100%" cellspacing="3" cellpadding="5">
        <thead>
            <tr>
                <th style="width: 10%;text-align: center">ลำดับ</th>
                <th style="width: 15%;text-align: center">เลขที่ใบเสร็จรับเงิน</th>
                <th style="width: 35%;text-align: center">ชื่อลูกค้า</th>
                <th style="width: 20%;text-align: center">ค่าขนส่ง</th>
                <th style="width: 20%;text-align: center">ส่วนลด</th>

            </tr>

        </thead>

        <tbody style="vertical-align: top;">

            @foreach ($tax_groups as $tax_date => $branches)
                <tr style="font-weight: bold;background-color:#c0c0c0">


                    <td colspan="3">
                        วันที่ : {{ date('d/m/Y', strtotime($tax_date)) }}

                    </td>
                    @php
                        $sumdate_receiptamount = $branches->sum(function ($group1) {
                            return $group1->sum(function ($group2) {
                                return $group2->sum('total_amount');
                            });
                        });
                        $sumdate_discountamount = $branches->sum(function ($group1) {
                            return $group1->sum(function ($group2) {
                                return $group2->sum('discount_amount');
                            });
                        });

                    @endphp


                    <td style="text-align: right;">
                        {{ number_format($sumdate_receiptamount, 2, '.', ',') }}
                    </td>
                    <td style="text-align: right;">
                        {{ number_format($sumdate_discountamount, 2, '.', ',') }}
                    </td>



                </tr>
                @foreach ($branches as $branch => $types)
                    <tr style="font-weight: bold">
                        <td colspan="3">
                            @php
                                $branchdata = \App\Models\Branch::find($branch);
                            @endphp

                            {{ $branchdata->name }}


                        </td>
                        @php

                            $sumbranch_receipt = $types->sum(function ($group1) {
                                return $group1->sum('total_amount');
                            });
                            $sumbranch_discount = $types->sum(function ($group1) {
                                return $group1->sum('discount_amount');
                            });
                        @endphp
                        <td style="text-align: right;">

                            {{ number_format($sumbranch_receipt, 2, '.', ',') }}

                        </td>
                        <td style="text-align: right;">

                            {{ number_format($sumbranch_discount, 2, '.', ',') }}

                        </td>

                    </tr>
                    @foreach ($types as $type => $discount_items)
                        @php

                            $sumtype_receipt = $discount_items->sum('total_amount');
                            $sumtype_discount = $discount_items->sum('discount_amount');
                        @endphp

                        <tr style="vertical-align: top;font-weight: bold">
                            <td colspan="3">
                                @if ($type == 'H')
                                    ต้นทาง
                                @elseif($type == 'B')
                                    วางบิล
                                @else
                                    ปลายทาง
                                @endif


                            </td>

                            <td style="text-align: right;">
                                {{ number_format($sumtype_receipt, 2, '.', ',') }}
                            </td>
                            <td style="text-align: right;">

                                {{ number_format($sumtype_discount, 2, '.', ',') }}
                            </td>


                        </tr>

                        @foreach ($discount_items as $item)
                            <tr style="vertical-align: top;">
                                <td style="text-align: center">
                                    {{ $loop->iteration }}
                                </td>
                                <td>
                                    {{ $item->receipt_no }}
                                </td>

                                <td>
                                    {{ $item->customer->name }}
                                </td>


                                <td style="text-align: right">
                                    {{ number_format($item->total_amount, 2, '.', ',') }}

                                </td>
                                <td style="text-align: right">
                                    {{ number_format($item->discount_amount, 2, '.', ',') }}

                                </td>


                            </tr>
                        @endforeach
                    @endforeach
                @endforeach
            @endforeach

        </tbody>
        <tr style="font-weight: bold;background-color:#c0c0c0">

            <td colspan="3">

                รวมทั้งหมด - {{ count($receipts) }} รายการ

            </td>
            <td style="text-align: right;">

                {{ number_format($receipts->sum('total_amount'), 2, '.', ',') }}

            </td>
            <td style="text-align: right;">

                {{ number_format($receipts->sum('discount_amount'), 2, '.', ',') }}

            </td>


        </tr>
    </table>
@endsection
@section('footer')
    {{-- <div class="d-flex justify-content-center">
    {!! $waybills->links() !!}
</div> --}}
@endsection
