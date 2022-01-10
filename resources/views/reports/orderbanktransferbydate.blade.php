@extends('layouts.doc')

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
            <th style="width: 10%;">วันที่โอน</th>
            <th style="width: 15%;text-align: right;">เลขที่ใบรับส่ง</th>
            <th style="width: 20%;text-align: right;">ลูกค้า</th>
            <th style="width: 15%;text-align: right;">โอนเข้าบัญชี</th>
            <th style="width: 15%;text-align: right;">จำนวนเงิน</th>
        </tr>

    </thead>

    <tbody>
       @foreach ($transfer_type as $type_group => $order_groups)
            <tr>
                <td style="text-align: center">{{ $loop->iteration }}
                </td>
                <td style="text-align: left">
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
                    {{ number_format($type_groups->sum('transfer_amount'),2,'.',',') }}
                </td>


            </tr>
            @foreach ($order_groups as $item_transfer )
                <tr>
                    <td style="text-align: center">{{ $loop->iteration }}
                    </td>

                    <td style="text-align: left">
                        {{$item_transfer->order_header->order_header_no}}
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

