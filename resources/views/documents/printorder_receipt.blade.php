@extends('layouts.form')

@section('header')
    @include('partials.orderheader')
@endsection
@section('content')
<table style="width: 100%">
    <tr >
        <td style="width: 100%;text-align: center;font-size: .8em;vertical-align:top">
            @if ($order->paymenttype == 'H')
                <strong>ใบรับส่งสินค้า/ใบเสร็จรับเงิน</strong>
            @else
                </strong>ใบรับส่งสินค้า</strong>
            @endif
        </td>
    </tr>
</table>
<table style="width: 100%;">
    <tr>
        <td style="width: 50%;text-align: left;font-size: .8em;vertical-align:top">
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
        <td style="width: 50%;text-align: right;font-size: .8em;vertical-align:top">
            สาขาปลายทาง : {{$order->to_branch->name}}<br/>
            Tel : {{ $order->to_branch->phoneno }}<br/>
            @switch($order->trantype)
                @case(1)
                    การจัดส่งปลายทาง : จัดส่ง<br/>
                    @break

                @case(0)
                    การจัดส่งปลายทาง : รับเอง<br/>
                @break
            @endswitch

            @isset($order->waybill->car->car_regist)
                ทะเบียนรถ : {{ $order->waybill->car->car_regist }}
            @endisset

        </td>
    </tr>
</table>
<table style="width: 100%;border-top: 1px solid black">

    <tr>
        <td style="width: 50%;font-size: .8em;vertical-align:top" >
           <strong>ผู้ส่งสินค้า: {{ $order->customer->name }}</strong>
           @if($order->customer->taxid != '')
             Tax ID. {{$order->customer->taxid}}
           @endif
           <br>
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
            $order->customer->postal_code }} <br/>
           <strong>Tel: {{ $order->customer->phoneno }}</strong>

        </td>
        <td style="width: 50%;font-size: .8em;vertical-align:top" >

            <strong>ผู้รับสินค้า: {{ $order->to_customer->name }}</strong>
            @if($order->to_customer->taxid != '')
            Tax ID. {{$order->to_customer->taxid}}
           @endif
           <br>
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
            <strong>Tel:{{ $order->to_customer->phoneno }}</strong>

        </td>
    </tr>


</table>

<table  style="width: 100%;height: 2.5cm;border-top: 1px solid black;font-size: .8em;">
        @foreach ($order->order_details as $item )
         <tr style="vertical-align:top">
            <td  style="width: 47%;text-align: left">
                {{ $loop->iteration }}.{{$item->product->name .''.$item->remark }}
            </td>
            <td style="width: 18%;text-align: center">
                <strong>{{number_format($item->amount,2)}} {{$item->unit->name}}</strong>
            </td>
            <td style="width: 15%;text-align: center">
                {{number_format($item->price,2)}} / {{$item->unit->name}}
            </td>
            <td style="width: 20%;text-align: right">
                <strong>{{number_format($item->price*$item->amount,2)}} </strong>

            </td>
        </tr>
        @endforeach


</table>
<table  style="width: 100%;border-top: 1px solid black;">
    <tr style="vertical-align:top">
        <td style="width: 50%;font-size: .8em;">
            เลขที่ตรวจสอบสถานะ : <strong>{{$order->tracking_no}}</strong> Ref ID: {{$order->id}} <br/>
            พนักงานตรวจรับ : {{$order->checker->name}}<br/>
            พนักงานออกเอกสาร : {{$order->user->name}}<br/>

            @isset($order->loader->name)
                พนักงานจัดขึ้น : {{ $order->loader->name }}<br/>
            @endisset
            ผู้รับเงิน.................................................................
        </td>
        <td style="width: 50%;font-size: .8em;">

            หมายเหตุ : {{$order->remark}}<br/>
             <strong>
            รวมจำนวนสินค้า : {{$order->order_details->sum('amount')}} ชิ้น<br/>
            รวมจำนวนเงิน : {{number_format($order->order_amount,2)}} บาท  (จำนวนเงิน)<br>
            </strong>
            (ลงชื่อ) ผู้ส่งสินค้า........................................ ผู้รับสินค้า......................................................
        </td>



    </tr>
</table>
<table  style="width: 100%;border-top: 1px solid black;">
    <tr style="vertical-align:top;">
        <td style="width: 50%;font-size: .7em;">
            สินค้าไม่ประเมินราคาหากสูญหายหรือเสียหายชดใช้ไม่เกิน 500 บาท หากพ้นกำหนดไม่รับผิดชอบ
            ถ้าสินค้าสูญหายหรือเสียหายโปรดนำใบรับส่งสินค้าฉบับนี้มาทวงถามภายใน 50 วัน
            สินค้าไวเพลิง สินค้าผิดกฎหมาย สินค้าแตกหักง่ายที่บรรจุไม่เหมาะสม ทางบริษัทฯ ไม่รับผิดชอบทั้งสิ้น


        </td>
    </tr>

@endsection
