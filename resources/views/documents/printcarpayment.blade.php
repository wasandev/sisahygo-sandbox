@extends('layouts.doc')

@section('header')
    @include('partials.docheader')

@endsection

@section('content')
<table style="width:100%;padding: 0px;margin: 0px;">
    <tr>
        <p style="text-align: center;font-size: 1.5em;padding: 0px">
            ใบสำคัญจ่าย
        </p>
    </tr>
</table>
<table>

    <tr>
        <td style="width: 50%">
            จ่ายให้: {{ $carpayment->carpayment_no }}<br />
            ที่อยู่: {{$carpayment->vendor->name }}<br/>
            ทะเบียนรถ: {{ $carpayment->car->car_regist }}<br />
            จ่ายโดย: {{ $carpayment->payment_by}}<br/>


        </td>
        <td style="width:50%;vertical-align: top;">
            เลขที่: {{ $carpayment->payment_no }}<br />
            วันที่: {{ $carpayment->payment_date }}<br />
            ยอดเก็บเงินปลายทาง: {{ number_format($delivery->receipt_amount,2,'.',',') }}<br />
            พนักงานจัดส่ง:
            @isset($delivery->sender->name)
                {{ $delivery->sender->name }}
            @endisset
            <br/>
            เส้นทางจัดส่ง: {{ $delivery->branch_route->name}}<br/>
            รายละเอียด/หมายเหตุ: {{$delivery->remark}}

        </td>


    </tr>


</table>


<table style="border-bottom: 1px solid black;border-top: 1px solid black;padding: 5px;" cellspacing="3" cellpadding="5">
    <thead>
        <tr>
            <th style="width: 5%";>ลำดับ</th>
            <th style="text-align: left;width: 10%;">อำเภอ</th>
            <th style="text-align: left;width: 25%">ผู้รับสินค้า</th>
            <th style="width: 10%;">จำนวนรายการ</th>
            <th style="text-align: left;width: 15%;">เลขที่ใบรับส่ง</th>
            <th style="width: 10%;">ยอดจัดเก็บ</th>
            <th style="width: 10%;">การจัดส่ง</th>
            <th style="width: 10%;">การเก็บเงิน</th>
        </tr>

    </thead>

    <tbody style="border-top: 1px solid black;vertical-align: top;">

            <tr>
                <td style="border-bottom: 1px solid black;"></td>
                <td style="border-bottom: 1px solid black;">
                    <strong>
                    {{$district}}
                    </strong>
                </td>
                <td style="border-bottom: 1px solid black;"></td>
                <td style="text-align: center;border-bottom: 1px solid black;">
                    <strong>
                    {{count($item_groups)}} ผู้รับ
                    </strong>
                </td>
                <td style="border-bottom: 1px solid black;"></td>
                <td  style="text-align: right;border-bottom: 1px solid black;">
                    <strong>
                    {{ number_format($item_groups->sum('payment_amount'),2,'.',',')}}
                    </strong>
                </td>
                <td style="border-bottom: 1px solid black;"></td>
                <td style="border-bottom: 1px solid black;"></td>

            </tr>



    </tbody>

</table>
<br>
<table style="padding: 10px;" >
    <tr>
        <td style="width:50%;vertical-align: top;font-size:18px;font-weight: bold;">
            พนักงานขับรถ.......................................<br/>
            ผู้จัดการ............................................<br/>

        </td>
        <td style="width:50%;vertical-align: top;text-align: right;font-size:18px;font-weight: bold;">

            ผู้ทำรายการ.........................................<br/>
            พนักงานจัดส่ง.......................................<br/>


        </td>
    </tr>

</table>


@endsection
