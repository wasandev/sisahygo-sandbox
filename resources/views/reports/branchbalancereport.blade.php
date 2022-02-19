@extends('layouts.docnojs')

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

            <th style="width: 20%;text-align: center;">วันที่ตั้งหนี้</th>
            <th style="width: 5%;">ลำดับ</th>
            <th style="width: 40%;text-align: center;">ลูกค้า</th>
            <th style="width: 10%;text-align: center;">เลขที่ใบรับส่ง</th>
            <th style="width: 10%;text-align: center;">วันที่ใบรับส่ง</th>
            <th style="width: 15%;text-align: right;">จำนวนเงิน</th>
        </tr>

    </thead>

    <tbody>



            @foreach ($branch_groups as $bal_item => $date_items )

                <tr style="font-weight: bold;background-color:#aaaaaa">


                    <td colspan="4" style="text-align: left">
                        {{ date("d-m-Y", strtotime($bal_item))}}

                    </td>

                    <td style="text-align: right">
                        รวมตามวัน
                    </td>
                    <td style="text-align: right">
                        {{ number_format($date_items->sum('bal_amount'),2,'.',',') }}
                    </td>

                </tr>

                @foreach ($date_items as $item )


                        <tr style="vertical-align: top;">


                            <td>
                            </td>
                            <td>
                                 {{ $loop->iteration }}
                            </td>
                            <td>
                                {{$item->customer->name}}
                            </td>
                            <td style="text-align: center">
                                {{$item->branchrec_order->order_header_no}}
                            </td>
                            <td style="text-align: center">
                                {{$item->branchrec_order->order_header_date->format('d/m/Y')}}
                            </td>
                            <td style="text-align: right">
                                {{number_format($item->bal_amount,2,'.',',')}}
                            </td>
                        </tr>
                    @endforeach

        @endforeach

        <tr style="font-weight: bold;background-color:#aaaaaa">
            <td colspan="5" style="text-align: center">
                รวมทั้งหมด -  {{count($branch_balances)}} - รายการ
            </td>

            <td style="text-align: right">
                {{ number_format($branch_balances->sum('bal_amount'),2,'.',',') }}
            </td>
        </tr>

    </tbody>

</table>



@endsection

