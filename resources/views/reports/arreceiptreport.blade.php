@extends('layouts.docnojs')

@section('header')
    @include('partials.reportheader')

@endsection

@section('content')

<table style="width:100%;">

    <tr>
        <td style="width: 50%;text-align: left;border:0px">
            <strong>
            @if($branchdata <> null)
            สาขา {{ $branchdata->name }}
            @else
            ทุกสาขา
            @endif
            </strong>

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

            <th style="width: 10%;text-align: center;">วันที่</th>
            <th style="width: 35%;text-align: center;">ลูกค้า</th>
            <th style="width: 5%;">ลำดับ</th>
            <th style="width: 10%;text-align: center;">ใบเสร็จรับเงิน</th>
            <th style="width: 10%;text-align: center;">จำนวนเงินที่ต้องชำระ</th>
            <th style="width: 10%;text-align: center;">ภาษีหัก ณ ที่จ่าย</th>
            <th style="width: 10%;text-align: right;">ส่วนลด</th>
            <th style="width: 10%;text-align: right;">จำนวนเงินรับชำระ</th>
        </tr>

    </thead>

    <tbody>

        @foreach ($receipts_date as $ar_date => $cust_receipts)


            <tr style="font-weight: bold;background-color:#c0c0c0">

                <td style="text-align: center">
                    {{$ar_date}}
                </td>
                <td style="text-align: center">
                    {{count($cust_receipts)}} ราย
                </td>
                <td></td>
                <td style="text-align: center">
                    รวมตามวัน
                </td>
                <td style="text-align: right">
                    @php
                        $total_amount  = 0;
                    @endphp
                    @foreach ($cust_receipts as $cust_receipt )
                        @php
                            $total_amount +=  $cust_receipt->sum('total_amount');
                        @endphp

                    @endforeach
                    {{ number_format($total_amount,2,'.',',') }}
                </td>
                <td style="text-align: right">
                    @php
                        $tax_amount  = 0;
                    @endphp
                    @foreach ($cust_receipts as $cust_receipt )
                        @php
                            $tax_amount +=  $cust_receipt->sum('tax_amount');
                        @endphp

                    @endforeach
                    {{ number_format($tax_amount,2,'.',',') }}
                </td>
                <td style="text-align: right">
                  @php
                        $discount_amount  = 0;
                    @endphp
                    @foreach ($cust_receipts as $cust_receipt )
                        @php
                            $discount_amount +=  $cust_receipt->sum('discount_amount');
                        @endphp

                    @endforeach
                    {{ number_format($discount_amount,2,'.',',') }}
                </td>
                <td style="text-align: right">
                    @php
                        $pay_amount  = 0;
                    @endphp
                    @foreach ($cust_receipts as $cust_receipt )
                        @php
                            $pay_amount +=  $cust_receipt->sum('pay_amount');
                        @endphp

                    @endforeach
                    {{ number_format($pay_amount,2,'.',',') }}
                </td>
            </tr>

            @foreach ($cust_receipts as $ar_item => $receipt_items )


                <tr style="font-weight:bold">
                    <td></td>

                    <td style="text-align: left">
                        @php
                            $customer = \App\Models\Ar_customer::find($ar_item);
                        @endphp
                        {{ $loop->iteration}} .  {{ $customer->name}}
                    </td>
                    <td></td>
                    <td style="text-align: center">
                        รวมตามลูกค้า
                    </td>
                    <td style="text-align: right">
                        {{ number_format($receipt_items->sum('total_amount'),2,'.',',') }}
                    </td>
                    <td style="text-align: right">
                        {{ number_format($receipt_items->sum('tax_amount'),2,'.',',') }}
                    </td>
                    <td style="text-align: right">
                        {{ number_format($receipt_items->sum('discount_amount'),2,'.',',') }}
                    </td>
                    <td style="text-align: right">
                        {{ number_format($receipt_items->sum('pay_amount'),2,'.',',') }}
                    </td>
                </tr>

                @foreach ($receipt_items as $item )

                    <tr style="vertical-align: top;">

                        <td>
                        </td>

                        <td style="text-align: center">
                        </td>
                        <td style="text-align: center">
                            {{ $loop->iteration }}
                        </td>
                        <td style="text-align: center">
                            {{ $item->receipt_no}}
                        </td>
                        <td style="text-align: right">
                            {{ number_format($item->total_amount,2,'.',',')}}
                        </td>

                        <td style="text-align: right">
                             {{ number_format($item->tax_amount,2,'.',',')}}
                        </td>
                        <td style="text-align: right">
                            {{ number_format($item->discount_amount,2,'.',',') }}
                        </td>
                        <td style="text-align: right">
                            {{ number_format($item->pay_amount,2,'.',',') }}
                        </td>
                    </tr>
                @endforeach
            @endforeach
        @endforeach

        <tr style="font-weight:bold">
            <td colspan="4" style="text-align: center">
                รวมทั้งหมด
            </td>
            <td style="text-align: right">
                {{ number_format($ar_receipts->sum('total_amount'),2,'.',',') }}
            </td>
            <td style="text-align: right">
                {{ number_format($ar_receipts->sum('tax_amount'),2,'.',',') }}
            </td>
            <td style="text-align: right">
                {{ number_format($ar_receipts->sum('discount_amount'),2,'.',',') }}
            </td>
            <td style="text-align: right">
                {{ number_format($ar_receipts->sum('pay_amount'),2,'.',',') }}
            </td>
        </tr>

    </tbody>

</table>



@endsection

