@extends('layouts.form')

@section('header')
    @include('partials.orderheader')
@endsection
@section('content')

    <table style="width: 100%;magin-top: -4px">
        <tr>
            <td style="width: 60%;text-align: right;vertical-align:top;">
                @if ($order->paymenttype == 'H' || $order->paymenttype == 'E')
                    <h3>ใบรับส่งสินค้า/ใบเสร็จรับเงิน</h3>
                @else
                    <h3>ใบรับส่งสินค้า</h3>
                @endif
            </td>
            <td style="width: 40%;text-align: right;vertical-align:top">
                @if ($order->paymenttype == 'H' || $order->paymenttype == 'T')
                    <h2>จ่ายเงินแล้ว</h2>
                @elseif($order->paymenttype == 'E')
                    <h2>เก็บเงินปลายทาง</h2>
                @else
                    <h2>วางบิล</h2>
                @endif
            </td>
        </tr>
    </table>
    <table style="width: 100%;">
        <tr>
            <td style="width: 40%;text-align: left;vertical-align:top">
                เลขที่ : <strong> {{ $order->order_header_no }}</strong> <br />
                วันที่ : <strong> {{ $order->created_at }}</strong><br />
                <strong>
                    @switch($order->paymenttype)
                        @case('H')
                            เงื่อนไขการชำระเงิน : เงินสดต้นทาง<br />
                        @break

                        @case('T')
                            เงื่อนไขการชำระเงิน : เงินโอนต้นทาง<br />
                        @break

                        @case('E')
                            เงื่อนไขการชำระเงิน : เก็บเงินปลายทาง<br />
                        @break

                        @case('F')
                            เงื่อนไขการชำระเงิน : วางบิลต้นทาง<br />
                        @break

                        @case('L')
                            เงื่อนไขการชำระเงิน : วางบิลปลายทาง
                        @break
                    @endswitch
                </strong>
            </td>
            <td style="width: 60%;text-align: right;vertical-align:top">

                @if ($order->waybill_id == '')
                    ใบกำกับสินค้า: .............................. ทะเบียนรถ: .....................
                @else
                    ใบกำกับสินค้า: <strong> {{ $order->waybill->waybill_no }}</strong> ทะเบียนรถ:
                    <strong>{{ $order->waybill->car->car_regist }}</strong>
                @endif

                @switch($order->order_type)
                    @case('general')
                        ประเภท : ทั่วไป<br />
                    @break

                    @case('express')
                        ประเภท : Express<br />
                    @break

                    @case('charter')
                        ประเภท : เหมาคัน<br />
                    @break
                @endswitch
                <strong> สาขาปลายทาง : {{ $order->to_branch->name }} Tel : {{ $order->to_branch->phoneno }} <br />
                    @switch($order->trantype)
                        @case(1)
                            การจัดส่งปลายทาง : จัดส่ง<br />
                        @break

                        @case(0)
                            การจัดส่งปลายทาง : รับเอง<br />
                        @break
                    @endswitch
                </strong>


            </td>
        </tr>
    </table>
    <table style="width: 100%;border-top: 0.5px dotted black">

        <tr>
            <td style="width: 50%;vertical-align:top">
                ผู้ส่งสินค้า: {{ $order->customer->name }}
                @isset($order->customer->taxid)
                    Tax ID. {{ $order->customer->taxid }}
                @endisset

                {{ $order->customer->address }}
                @if ($order->customer->province === 'กรุงเทพมหานคร')
                    แขวง{{ $order->customer->sub_district }}
                @else
                    ต.{{ $order->customer->sub_district }}
                @endif

                @if ($order->customer->province === 'กรุงเทพมหานคร')
                    เขต{{ $order->customer->district }}
                @else
                    อ.{{ $order->customer->district }}
                @endif
                @if ($order->customer->province === 'กรุงเทพมหานคร')
                    {{ $order->customer->province }}
                @else
                    จ.{{ $order->customer->province }}
                @endif
                {{ $order->customer->postal_code }}<br />
                <strong>Tel: {{ $order->customer->phoneno }}</strong>

            </td>
            <td style="width: 50%;vertical-align:top">

                ผู้รับสินค้า: {{ $order->to_customer->name }}
                @if ($order->to_customer->taxid != '')
                    Tax ID. {{ $order->to_customer->taxid }}
                @endif

                {{ $order->to_customer->address }}
                @if ($order->to_customer->province === 'กรุงเทพมหานคร')
                    แขวง{{ $order->to_customer->sub_district }}
                @else
                    ต.{{ $order->to_customer->sub_district }}
                @endif


                @if ($order->to_customer->province === 'กรุงเทพมหานคร')
                    เขต{{ $order->to_customer->district }}
                @else
                    อ.{{ $order->to_customer->district }}
                @endif
                @if ($order->to_customer->province === 'กรุงเทพมหานคร')
                    {{ $order->to_customer->province }}
                @else
                    จ.{{ $order->to_customer->province }}
                @endif
                {{ $order->to_customer->postal_code }}<br />
                <strong>Tel: {{ $order->to_customer->phoneno }}</strong>

            </td>
        </tr>


    </table>
    <table style="width: 100%;border-top: 0.5px dotted black;">
        <tr style="vertical-align:top;">
            <td style="width: 45%;text-align: left">
                รายการ
            </td>
            <td style="width: 11%;text-align: right">
                จำนวน
            </td>
            <td style="width: 9%;text-align: center">
                หน่วยนับ
            </td>
            <td style="width: 15%;text-align: right">
                ราคา/หน่วย
            </td>
            <td style="width: 20%;text-align: right">
                จำนวนเงิน
            </td>
        </tr>
    </table>
    <table style="width: 100%;height: 2.5cm;border-top: 0.5px dotted black;">

        @foreach ($order->order_details as $item)
            <tr style="vertical-align:top;height:14px">
                <td style="width: 45%;text-align: left">
                    {{ $loop->iteration }}.{{ $item->product->name }}
                    @isset($item->remark)
                        ({{ $item->remark }})
                    @endisset
                </td>
                <td style="width: 11%;text-align: right">
                    {{ number_format($item->amount, 2) }}
                </td>
                <td style="width: 9%;text-align: center">
                    {{ $item->unit->name }}
                </td>
                <td style="width: 15%;text-align: right">
                    {{ number_format($item->price, 2) }}
                </td>
                <td style="width: 20%;text-align: right">
                    {{ number_format($item->price * $item->amount, 2) }}

                </td>
            </tr>
        @endforeach
        @if (count($order->order_details) < 5)
            @for ($i = 1; $i <= 5 - count($order->order_details); $i++)
                <tr style="vertical-align:top;height:14px">
                    <td style="width: 45%;text-align: left">

                    </td>
                    <td style="width: 11%;text-align: right">

                    </td>
                    <td style="width: 9%;text-align: center">

                    </td>
                    <td style="width: 15%;text-align: right">

                    </td>
                    <td style="width: 20%;text-align: right">

                    </td>

                </tr>
            @endfor
        @endif
    </table>
    <table style="width: 100%;border-top: 0.5px dotted black;">
        <tr style="vertical-align:top;height:14px;">
            <td style="width: 45%;text-align: left">
                หมายเหตุ : {{ $order->remark }}
            </td>
            <td style="width: 11%;text-align: right">
                <strong>รวมสินค้า
                    {{ $order->order_details->where('unit_id', '<>', 10)->sum('amount') + $order->order_details->where('unit_id', '=', 10)->count('amount') }}</strong>
            </td>
            <td style="width: 9%;text-align: center">
                ชิ้น
            </td>
            <td style="width: 15%;text-align: right">
                รวมจำนวนเงิน
            </td>
            <td style="width: 20%;text-align: right">
                <strong>{{ number_format($order->order_amount, 2) }}</strong>
            </td>

        </tr>
    </table>


    <table style="width: 100%;border-top: 0.5px dotted black;">
        <tr style="vertical-align:top">
            <td style="width: 50%;">
                พนักงานตรวจรับ : {{ $order->checker->name }}<br />
                พนักงานออกเอกสาร : {{ $order->user->name }}<br />
                พนักงานจัดขึ้น :
                @isset($order->loader->name)
                    {{ $order->loader->name }}<br />
                @endisset

            </td>
            <td style="width: 50%;text-align: right">
                <strong> ( {{ baht_text($order->order_amount) }} ) </strong><br>

                เลขที่ตรวจสอบสถานะ : <strong> Ref ID: {{ $order->id }} </strong><br />



            </td>



        </tr>
    </table>
    <table style="width: 100%;border-top: .05px dotted black;">
        <tr style="vertical-align:top;">
            <td style="width: 100%;">
                สินค้าไม่ประเมินราคาหากสูญหายหรือเสียหายชดใช้ไม่เกิน 500 บาท หากพ้นกำหนดไม่รับผิดชอบ
                ถ้าสินค้าสูญหายหรือเสียหายโปรดนำใบรับส่งสินค้าฉบับนี้มาทวงถามภายใน 50 วัน
                สินค้าไวเพลิง สินค้าผิดกฎหมาย สินค้าแตกหักง่ายที่บรรจุไม่เหมาะสม ทางบริษัทฯ ไม่รับผิดชอบทั้งสิ้น<br /><br />
                (ลงชื่อ) ผู้ส่งสินค้า.................................. (ลงชื่อ) ผู้รับเงิน.............................
                (ลงชื่อ) ผู้รับสินค้า............................วันที่...................


            </td>
        </tr>
    </table>

@endsection
