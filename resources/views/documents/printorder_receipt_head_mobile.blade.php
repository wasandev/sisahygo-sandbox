@extends('layouts.formmobile')


@section('content')

    <table style="width: 96%;magin-top: -30px">
        <tr>

            <td style="width: 40%;text-align: left;vertical-align:bottom;">
                <br />
                <p>
                    @if ($order->paymenttype == 'H' || $order->paymenttype == 'E')
                        <strong>ใบรับส่งสินค้า/ใบเสร็จรับเงิน</strong>
                    @else
                        <strong>ใบรับส่งสินค้า</strong>
                    @endif
                </p>
            </td>
            <td style="width: 40%;text-align: center;vertical-align:bottom;">

                @if ($order->paymenttype == 'H' || $order->paymenttype == 'T')
                    -จ่ายเงินแล้ว-
                @elseif($order->paymenttype == 'E')
                    -เก็บเงินปลายทาง-
                @else
                    -วางบิล-
                @endif
                </p>
            </td>
            <td style="width: 20%;text-align: right;vertical-align:top;">

                @isset($order->id)
                    {!! QrCode::size(70)->generate('https://app.sisahygo.online/order-tracking?tracking=' . $order->id) !!}
                @endisset


            </td>
        </tr>
    </table>
    <table style="width: 96%;">
        <tr>
            <td style="width: 40%;text-align: left;vertical-align:top">
                เลขที่ : {{ $order->order_header_no }}
                @switch($order->order_type)
                    @case('general')
                        - ประเภท : ทั่วไป<br />
                    @break

                    @case('express')
                        - ประเภท : Express<br />
                    @break

                    @case('charter')
                        - ประเภท : เหมาคัน<br />
                    @break
                @endswitch
                วันที่ : {{ $order->created_at }}<br />

                @switch($order->paymenttype)
                    @case('H')
                        การชำระเงิน : เงินสดต้นทาง /
                    @break

                    @case('T')
                        การชำระเงิน : เงินโอนต้นทาง /
                    @break

                    @case('E')
                        การชำระเงิน : เก็บเงินปลายทาง /
                    @break

                    @case('F')
                        การชำระเงิน : วางบิลต้นทาง /
                    @break

                    @case('L')
                        การชำระเงิน : วางบิลปลายทาง /
                    @break
                @endswitch
                @switch($order->trantype)
                    @case(1)
                        การจัดส่ง : จัดส่ง
                    @break

                    @case(0)
                        การจัดส่ง : รับเอง
                    @break
                @endswitch

            </td>
            <td style="width: 50%;text-align: right;vertical-align:top">

                @if ($order->waybill_id == '')
                    ใบกำกับ: .............................. ทะเบียนรถ: .....................<br />
                @else
                    ใบกำกับ: {{ $order->waybill->waybill_no }} - ทะเบียน:
                    {{ $order->waybill->car->car_regist }} <br />
                @endif


                สาขา : {{ $order->to_branch->name }} Tel : {{ $order->to_branch->phoneno }}




            </td>
        </tr>
    </table>
    <table style="width: 96%;border-top: 0.5px dotted black">

        <tr>
            <td style="width: 50%;vertical-align:top">
                ผู้ส่ง:
                @isset($order->customer->name)
                    {{ $order->customer->name }}
                @endisset
                @isset($order->customer->taxid)
                    Tax ID. {{ $order->customer->taxid }}
                @endisset
                @isset($order->customer->address)
                    {{ $order->customer->address }}
                @endisset
                @isset($order->customer->province)
                    @if ($order->customer->province === 'กรุงเทพมหานคร')
                        แขวง{{ $order->customer->sub_district }}
                    @else
                        ต.{{ $order->customer->sub_district }}
                    @endif
                @endisset
                @isset($order->customer->province)
                    @if ($order->customer->province === 'กรุงเทพมหานคร')
                        เขต{{ $order->customer->district }}
                    @else
                        อ.{{ $order->customer->district }}
                    @endif
                @endisset
                @isset($order->customer->province)
                    @if ($order->customer->province === 'กรุงเทพมหานคร')
                        {{ $order->customer->province }}
                    @else
                        จ.
                        {{ $order->customer->province }}
                    @endif
                @endisset
                @isset($order->customer->postal_code)
                    {{ $order->customer->postal_code }}<br />
                @endisset
                @isset($order->customer->phoneno)
                    Tel: {{ $order->customer->phoneno }}
                @endisset

            </td>
            <td style="width: 50%;vertical-align:top">

                ผู้รับ: {{ $order->to_customer->name }}
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
                Tel: {{ $order->to_customer->phoneno }}

            </td>
        </tr>


    </table>
    <table style="width: 96%;border-top: 0.5px dotted black;">
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
    <table style="width: 96%;height: 2.0cm;border-top: 0.5px dotted black;">

        @foreach ($order->order_details as $item)
            <tr style="vertical-align:top;height:10px">
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
        @if (count($order->order_details) < 4)
            @for ($i = 1; $i <= 4 - count($order->order_details); $i++)
                <tr style="vertical-align:top;height:10px">
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
    <table style="width: 96%;border-top: 0.5px dotted black;">
        <tr style="vertical-align:top;height:10px;">
            <td style="width: 45%;text-align: left">
                หมายเหตุ : {{ $order->remark }}
            </td>
            <td style="width: 11%;text-align: right">
                สินค้า
                {{ $order->order_details->where('unit_id', '<>', 10)->sum('amount') + $order->order_details->where('unit_id', '=', 10)->count('amount') }}
            </td>
            <td style="width: 9%;text-align: center">
                ชิ้น
            </td>
            <td style="width: 15%;text-align: right">
                รวมจำนวนเงิน
            </td>
            <td style="width: 20%;text-align: right">
                {{ number_format($order->order_amount, 2) }}
            </td>

        </tr>
    </table>


    <table style="width: 96%;border-top: 0.5px dotted black;">
        <tr style="vertical-align:top">
            <td style="width: 35%; font-size:smaller ;font-style: thin">
                พนักงานตรวจรับ :
                @isset($order->checker->name)
                    {{ $order->checker->name }}<br />
                @endisset

                พนักงานออกเอกสาร :
                @isset($order->user->name)
                    {{ $order->user->name }}<br />
                @endisset
                พนักงานจัดขึ้น :
                @isset($order->loader->name)
                    {{ $order->loader->name }}<br />
                @endisset

            </td>
            <td style="width: 30%;text-align: center">
                @if ($order->paymenttype == 'E')
                    @switch($order->branch_rec_id)
                        @case(5)
                            การชำระเงิน<br />
                            โอนเข้าบัญชี ธ.ไทยพานิชย์<br>
                            หจก.บุญณรงค์รุ่งเรืองทรัพย์<br />
                            เลขที่บัญชี <strong>699-2-39128-8</strong>
                        @break

                        @case(7)
                            การชำระเงิน<br />
                            โอนเข้าบัญชี ธ.ไทยพานิชย์<br>
                            หจก.สี่สหายขนส่ง(บ้านนาสาร) 2016<br />
                            เลขที่บัญชี <strong>898-2-29369-6</strong>
                        @break

                        @case(11)
                            การชำระเงิน<br />
                            โอนเข้าบัญชี ธ.ไทยพานิชย์<br>
                            หจก.สี่สหายขนส่ง(บ้านนาสาร) 2016<br />
                            เลขที่บัญชี <strong>898-2-29369-6</strong>
                        @break

                        @case(13)
                            การชำระเงิน<br />
                            โอนเข้าบัญชี ธ.กสิกรไทย สาขาตรัง<br>
                            หจก.สี่สหายขนส่งตรัง-พัทลุง<br />
                            เลขที่บัญชี <strong>054-1-87635-9</strong>
                        @break

                        @default
                            การชำระเงิน<br />
                            โอนเข้าบัญชี ธ.กสิกรไทย สาขาบางมด<br>
                            บจก.สี่สหายขนส่ง(1988)<br />
                            เลขที่บัญชี <strong>090-1-00410-2</strong>
                    @endswitch
                @endif
            </td>
            <td style="width: 25%;text-align: right">
                <strong> ( {{ baht_text($order->order_amount) }} ) </strong><br>

                <strong> Ref ID: {{ $order->id }} </strong><br />



            </td>


        </tr>
    </table>
    <table style="width: 96%;border-top: .05px dotted black;">
        <tr style="vertical-align:top;">
            <td style="width: 96%; font-size:smaller ;font-style: thin">

                (ลงชื่อ) ผู้ส่งสินค้า......................................(ลงชื่อ)
                ผู้รับเงิน.....................................(ลงชื่อ)
                ผู้รับสินค้า..............................................วันที่........................


            </td>
        </tr>
    </table>

@endsection
