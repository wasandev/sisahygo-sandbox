@extends('layouts.pdfform')

@section('header')
    @include('partials.orderheader')
@endsection
@section('content')

<table style="width: 100%">
    <tr >
        <td style="width: 100%;text-align: center;vertical-align:top">
            @if ($order->paymenttype == 'H')
                ใบรับส่งสินค้า/ใบเสร็จรับเงิน
            @else
                ใบรับส่งสินค้า
            @endif
        </td>
    </tr>
</table>
<table style="width: 100%;">
    <tr>
        <td style="width: 50%;text-align: left;vertical-align:top">
            เลขที่ : {{ $order->order_header_no }}  <br/>
            วันที่ : {{ $order->created_at }}<br/>

            @switch($order->paymenttype)
                @case('H')
                    เงื่อนไขการชำระเงิน : เงินสดต้นทาง<br/>
                    @break

                @case('T')
                    เงื่อนไขการชำระเงิน : เงินโอนต้นทาง<br/>
                    @break
                @case('E')
                    เงื่อนไขการชำระเงิน : เก็บเงินปลายทาง<br/>
                    @break
                @case('F')
                    เงื่อนไขการชำระเงิน : วางบิลต้นทาง<br/>
                    @break
                @case('L')
                    เงื่อนไขการชำระเงิน : วางบิลปลายทาง
                    @break
            @endswitch
        </td>
        <td style="width: 50%;text-align: right;vertical-align:top">
            @if($order->waybill_id == '')
                ใบกำกับสินค้า: ...................................... ทะเบียนรถ:  .................
            @else
                ใบกำกับสินค้า: {{ $order->waybill->waybill_no}} ทะเบียนรถ:  {{ $order->waybill->car->car_regist }}

            @endif
            @switch($order->order_type)
                @case('general')
                    ประเภท : ทั่วไป<br/>
                    @break
                @case('express')
                    ประเภท : Express<br/>
                    @break
                @case('charter')
                    ประเภท : เหมาคัน<br/>
                    @break
            @endswitch
            สาขาปลายทาง : {{$order->to_branch->name}} <strong>Tel : {{ $order->to_branch->phoneno }}</strong>            <br/>
            @switch($order->trantype)
                @case(1)
                    การจัดส่งปลายทาง : จัดส่ง<br/>
                    @break

                @case(0)
                    การจัดส่งปลายทาง : รับเอง<br/>
                @break
            @endswitch



        </td>
    </tr>
</table>
<table style="width: 100%;border-top: 0.5px dotted black">

    <tr>
        <td style="width: 50%;vertical-align:top" >
           ผู้ส่งสินค้า: {{ $order->customer->name }}
           @isset($order->customer->taxid)
             Tax ID. {{$order->customer->taxid}}
           @endisset

            {{ $order->customer->address }}
           @if ( $order->customer->province === "กรุงเทพมหานคร" )
                แขวง{{ $order->customer->sub_district}}
            @else
                ต.{{ $order->customer->sub_district}}
            @endif

            @if($order->customer->province === "กรุงเทพมหานคร")
                เขต{{$order->customer->district}}
            @else
                อ.{{ $order->customer->district}}
            @endif

            จ.{{$order->customer->province.' '.
            $order->customer->postal_code }}<br/>
           <strong>Tel: {{ $order->customer->phoneno }}</strong>

        </td>
        <td style="width: 50%;vertical-align:top" >

            ผู้รับสินค้า: {{ $order->to_customer->name }}
            @if($order->to_customer->taxid != '')
            Tax ID. {{$order->to_customer->taxid}}
           @endif

             {{ $order->to_customer->address }}
            @if ( $order->to_customer->province === "กรุงเทพมหานคร" )
                แขวง{{ $order->to_customer->sub_district}}
            @else
                ต.{{ $order->to_customer->sub_district}}
            @endif


            @if($order->to_customer->province === "กรุงเทพมหานคร")
                เขต{{$order->to_customer->district}}
            @else
                อ.{{ $order->to_customer->district}}
            @endif

            จ.{{$order->to_customer->province.' '.
            $order->to_customer->postal_code}}<br/>
            <strong>Tel: {{ $order->to_customer->phoneno }}</strong>

        </td>
    </tr>


</table>
<table  style="width: 100%;border-top: 0.5px dotted black;">
    <tr style="vertical-align:top;">
            <td  style="width: 47%;text-align: left">
                รายการ
            </td>
            <td style="width: 9%;text-align: right">
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
<table  style="width: 100%;height: 3.0cm;border-top: 0.5px dotted black;">

        @foreach ($order->order_details as $item )
         <tr style="vertical-align:top">
            <td  style="width: 47%;text-align: left">
                {{ $loop->iteration }}.{{$item->product->name }}
                @isset($item->remark)
                 ( {{ $item->remark }} )
                 @endisset
            </td>
            <td style="width: 9%;text-align: right">
                {{number_format($item->amount,2)}}
            </td>
            <td style="width: 9%;text-align: center">
                {{$item->unit->name}}
            </td>
            <td style="width: 15%;text-align: right">
                {{number_format($item->price,2)}} บ./{{$item->unit->name}}
            </td>
            <td style="width: 20%;text-align: right">
                {{number_format($item->price*$item->amount,2)}}

            </td>
        </tr>
        @endforeach


</table>
<table  style="width: 100%;border-top: 0.5px dotted black;">
    <tr style="vertical-align:top">
        <td style="width: 50%;">
            พนักงานตรวจรับ : {{$order->checker->name}}<br/>
            พนักงานออกเอกสาร : {{$order->user->name}}<br/>
            พนักงานจัดขึ้น :
            @isset($order->loader->name)
                 {{ $order->loader->name }}<br/>
            @endisset

        </td>
        <td style="width: 50%;">
            รวมจำนวนเงิน : {{number_format($order->order_amount,2)}} บาท  ( {{ baht_text($order->order_amount)}} )<br>
            หมายเหตุ : {{$order->remark}}<br/>
            เลขที่ตรวจสอบสถานะ : {{$order->tracking_no}} Ref ID: {{$order->id}} <br/>



        </td>



    </tr>
</table>
<table  style="width: 100%;border-top: .05px dotted black;">
    <tr style="vertical-align:top;">
        <td style="width: 100%;">
            สินค้าไม่ประเมินราคาหากสูญหายหรือเสียหายชดใช้ไม่เกิน 500 บาท หากพ้นกำหนดไม่รับผิดชอบ
            ถ้าสินค้าสูญหายหรือเสียหายโปรดนำใบรับส่งสินค้าฉบับนี้มาทวงถามภายใน 50 วัน
            สินค้าไวเพลิง สินค้าผิดกฎหมาย สินค้าแตกหักง่ายที่บรรจุไม่เหมาะสม ทางบริษัทฯ ไม่รับผิดชอบทั้งสิ้น<br/><br/>
            (ลงชื่อ) ผู้ส่งสินค้า..........................................    (ลงชื่อ) ผู้รับเงิน..........................................  (ลงชื่อ) ผู้รับสินค้า..........................................วันที่.....................


        </td>
    </tr>
</table>

@endsection
