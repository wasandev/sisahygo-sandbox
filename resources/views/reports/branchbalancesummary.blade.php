@extends('layouts.doc')

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

            ถึงวันที่: {{ date("d-m-Y", strtotime($to))}}<br/>
            </strong>
        </td>
    </tr>

</table>
<br/>

<table style="width: 100%;" >
    <thead>
        <tr>
            <th style="width: 20%;text-align: left;">สาขา</th>
            <th style="width: 15%;text-align: center;">วันที่ตั้งหนี้</th>
            <th style="width: 5%;">ลำดับ</th>
            <th style="width: 25%;text-align: center;">ลูกค้า</th>
            <th style="width: 10%;text-align: center;">เลขที่ใบรับส่ง</th>
            <th style="width: 10%;text-align: center;">วันที่ใบรับส่ง</th>
            <th style="width: 15%;text-align: right;">จำนวนเงิน</th>
        </tr>

    </thead>

    <tbody>

        @foreach ($branch_groups as $branch_bal => $bal_amounts)

            <tr style="font-weight:bold">

                <td style="text-align: left">
                    @php
                        $branch = \App\Models\Branch::find($branch_bal);
                    @endphp
                    {{$branch->name}}
                </td>

                <td></td>
                <td colspan="4" style="text-align: right">
                    รวมตามสาขา
                </td>
                <td style="text-align: right">
                    @php
                        $total_amount  = 0;
                    @endphp
                    @foreach ($bal_amounts as $bal_item )
                        @php
                            $total_amount +=  $bal_item->sum('bal_amount');
                        @endphp

                    @endforeach
                    {{ number_format($total_amount,2,'.',',') }}
                </td>

            </tr>

            @foreach ($bal_amounts as $bal_item => $date_items )

                <tr style="font-weight:bold">
                    <td></td>

                    <td style="text-align: center">
                        {{ date("d-m-Y", strtotime($bal_item))}}

                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="text-align: right">
                        รวมตามวัน
                    </td>
                    <td style="text-align: right">
                        {{ number_format($date_items->sum('bal_amount'),2,'.',',') }}
                    </td>

                </tr>

                @foreach ($date_items as $item )

                     <tr style="font-weight:bold">

                        <td>
                        </td>

                        <td style="text-align: center">
                        </td>
                        <td style="text-align: center">
                            {{ $loop->iteration }}
                        </td>
                        <td>
                            {{ $item->customer->name}}
                        </td>
                        <td style="text-align: center">
                                {{$item->branchrec_order->order_header_no}}
                            </td>
                            <td style="text-align: center">
                                {{$item->branchrec_order->order_header_date->format('d/m/Y')}}
                            </td>
                            <td style="text-align: right">
                                {{number_format($item->branchrec_order->order_amount,2,'.',',')}}
                            </td>

                    </tr>

                @endforeach
            @endforeach
        @endforeach

        <tr style="font-weight:bold">
            <td colspan="6" style="text-align: center">
                รวมทั้งหมด
            </td>

            <td style="text-align: right">
                {{ number_format($branch_balances->sum('bal_amount'),2,'.',',') }}
            </td>
        </tr>

    </tbody>

</table>



@endsection

