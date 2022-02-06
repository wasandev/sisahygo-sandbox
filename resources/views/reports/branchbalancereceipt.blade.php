@extends('layouts.doclandscapenojs')

@section('header')
    @include('partials.reportheader')

@endsection

@section('content')

<table style="width:100%;">

    <tr>
        <td style="width: 50%;text-align: left;border:0px">

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
            <th style="width: 5%;text-align: left;">สาขา</th>
            <th style="width: 5%;text-align: center;">วันที่รับเงิน</th>
            <th style="width: 5%;">ลำดับ</th>
            <th style="width: 15%;text-align: center;">ลูกค้า</th>
            <th style="width: 10%;text-align: center;">เลขที่ใบรับส่ง</th>
            <th style="width: 10%;text-align: center;">วันที่ใบรับส่ง</th>
            <th style="width: 10%;text-align: center;">ใบเสร็จรับเงิน</th>
            <th style="width: 10%;text-align: right;">ค่าขนส่ง</th>
            <th style="width: 5%;text-align: right;">ส่วนลด</th>
            <th style="width: 5%;text-align: right;">ภาษีหัก ณ ที่จ่าย</th>
            <th style="width: 10%;text-align: right;">ยอดรับชำระ</th>
            <th style="width: 10%;text-align: center;">การจัดส่ง</th>

        </tr>

    </thead>

    <tbody>

        @foreach ($branch_groups as $branch_bal => $bal_amounts)

            <tr style="font-weight: bold;background-color:#c0c0c0">

                <td colspan="2" style="text-align: left">
                    @php
                        $branch = \App\Models\Branch::find($branch_bal);
                    @endphp
                    {{$branch->name}}

                </td>


                <td colspan="5" style="text-align: right">
                    รวมตามสาขา
                </td>

                <td style="text-align: right">

                    @foreach ($branch_balances as $item )
                        @php
                            $sumbranch_amount =  $item->where('branch_balances.branch_id',$branch_bal)
                                                      ->where('branch_balances.branchpay_date','>=',$from)
                                                      ->where('branch_balances.branchpay_date','<=',$to)
                                                      ->sum('bal_amount');
                            $sumbranch_discount =  $item->where('branch_balances.branch_id',$branch_bal)
                                                      ->where('branch_balances.branchpay_date','>=',$from)
                                                      ->where('branch_balances.branchpay_date','<=',$to)
                                                      ->sum('discount_amount');
                            $sumbranch_tax =  $item->where('branch_balances.branch_id',$branch_bal)
                                                      ->where('branch_balances.branchpay_date','>=',$from)
                                                      ->where('branch_balances.branchpay_date','<=',$to)
                                                      ->sum('tax_amount');
                            $sumbranch_pay =  $item->where('branch_balances.branch_id',$branch_bal)
                                                      ->where('branch_balances.branchpay_date','>=',$from)
                                                      ->where('branch_balances.branchpay_date','<=',$to)
                                                      ->sum('pay_amount');
                        @endphp

                    @endforeach
                    {{ number_format($sumbranch_amount,2,'.',',') }}
                </td>
                <td style="text-align: right">
                    {{ number_format($sumbranch_discount,2,'.',',') }}
                </td>
                <td style="text-align: right">
                    {{ number_format($sumbranch_tax,2,'.',',') }}
                </td>

                <td style="text-align: right">
                    {{ number_format($sumbranch_pay ,2,'.',',') }}
                </td>
                <td>
                </td>

            </tr>

            @foreach ($bal_amounts as $bal_item => $date_items )

                <tr style="font-weight: bold;background-color:#aaaaaa">


                    <td colspan="2" style="text-align: right">
                        {{ date("d-m-Y", strtotime($bal_item))}}

                    </td>

                    <td colspan="5" style="text-align: right">
                        รวมตามวัน
                    </td>

                    <td style="text-align: right">
                         @foreach ($branch_balances as $item )
                        @php
                            $sumdate_amount =  $item->where('branch_balances.branch_id',$branch_bal)
                                                      ->where('branch_balances.branchpay_date','=',$bal_item)
                                                      ->sum('bal_amount');
                            $sumdate_discount =  $item->where('branch_balances.branch_id',$branch_bal)
                                                      ->where('branch_balances.branchpay_date','=',$bal_item)
                                                      ->sum('discount_amount');
                            $sumdate_tax =  $item->where('branch_balances.branch_id',$branch_bal)
                                                      ->where('branch_balances.branchpay_date','=',$bal_item)
                                                      ->sum('tax_amount');
                            $sumdate_pay =  $item->where('branch_balances.branch_id',$branch_bal)
                                                      ->where('branch_balances.branchpay_date','=',$bal_item)
                                                      ->sum('pay_amount');



                        @endphp

                    @endforeach
                        {{ number_format($sumdate_amount,2,'.',',') }}
                    </td>
                    <td style="text-align: right">
                        {{ number_format($sumdate_discount,2,'.',',') }}
                    </td>
                    <td style="text-align: right">
                        {{ number_format($sumdate_tax,2,'.',',') }}
                    </td>

                    <td style="text-align: right">
                        {{ number_format($sumdate_pay ,2,'.',',') }}
                    </td>
                    <td>
                    </td>
                </tr>

                @foreach ($date_items as $item_type => $receipt_type)
                    <tr style="font-weight: bold;background-color:#d4d4d4">
                        <td colspan="2" style="text-align: right">
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


                    @foreach ($receipt_type  as $item )


                     <tr style="font-weight:normal">


                        <td colspan="2" style="text-align: center">
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
            <td colspan="6" style="text-align: center">
                รวมทั้งหมด
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

