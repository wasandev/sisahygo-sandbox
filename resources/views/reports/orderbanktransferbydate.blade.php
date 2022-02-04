@extends('layouts.docnojs')

@section('header')
    @include('partials.reportheader')

@endsection

@section('content')

<table style="width:100%;">

    <tr>
        <td style="width: 50%;text-align: left;border:0px">
            <strong>

            </strong>
        </td>
        <td style="width:50%;text-align: right;border:0px">
            <strong>
            วันที่: {{date("d-m-Y", strtotime($from))}}<br/>

            </strong>
        </td>
    </tr>

</table>
<br/>

<table style="width: 100%;" >
    <thead>
        <tr>
            <th style="width: 5%;">ลำดับ</th>
            <th style="width: 10%;">ประเภทรายการ</th>
            <th style="width: 10%;">วันที่-เวลาโอน</th>
            <th style="width: 15%;">เลขที่ใบรับส่ง/ใบแจ้งหนี้</th>
            <th style="width: 20%;">ลูกค้า</th>
            <th style="width: 15%">บัญชีธนาคาร</th>
            <th style="width: 15%;text-align: right;">จำนวนเงิน</th>
        </tr>

    </thead>

    <tbody>
       @foreach ($transfer_type as $type_group => $order_groups)
            <tr>

                <td colspan="2" style="text-align: left">
                    @php
                        if($type_group == 'H') {
                            $transfertype = 'ค่าขนส่งต้นทาง' ;
                        }elseif($type_group == 'B'){
                            $transfertype = 'ค่าขนส่งวางบิล' ;
                        }else {
                            $transfertype = 'ค่าขนส่งปลายทาง' ;
                        }
                    @endphp
                    {{ $transfertype}}
                </td>

                <td colspan="5" style="text-align: right;">
                    {{ number_format($order_groups->sum('transfer_amount'),2,'.',',') }}
                </td>


            </tr>



            @foreach ($order_groups as $item_transfer )
                <tr>
                    <td style="text-align: center">{{ $loop->iteration }}
                    </td>
                    <td></td>
                    <td style="text-align: left">
                        {{$item_transfer->created_at}}
                        {{-- {{date("d-m-Y", strtotime($item_transfer->created_at))}} --}}
                    </td>
                    <td style="text-align: left">
                        @if ($item_transfer->transfer_type <> 'B')
                            {{$item_transfer->order_header->order_header_no}}

                        @endif
                                            </td>
                    <td style="text-align: left">
                        {{$item_transfer->customer->name}}
                    </td>

                    <td style="text-align: center">
                        {{$item_transfer->bankaccount->account_no }}
                    </td>

                    <td style="text-align: right">
                        {{ number_format($item_transfer->transfer_amount,2,'.',',') }}
                    </td>
                </tr>
            @endforeach


        @endforeach
        <tr style="font-weight: bold;">
            <td colspan="2">
                    <strong>
                    รวมทั้งหมด
                    </strong>
            </td>


            <td colspan="6" style="text-align: right">
                {{ number_format($ordertransfer->sum('transfer_amount'),2,'.',',') }}
            </td>



        </tr>
    </tbody>

</table>



@endsection

