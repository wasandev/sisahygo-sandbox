@extends('layouts.form')

@section('header')
    @include('partials.orderheader')
@endsection
@section('content')
<table style="width: 100%;height: 74px;">
    <tr style="width: 100%;">
        <td>
            {!! QrCode::generate($order->tracking_no); !!}
        </td>
    </tr>
</table>
<table style="width: 100%;padding:0px;margin:0px;">
    <tr height="20px">
        <td style="width: 15%;margin: 0px;text-align: center"><strong>{{ $order->order_header_no }}</strong></td>
        <td style="width: 25%;margin: 0px;text-align: center"><strong>{{ $order->created_at }}</strong></td>
        <td style="text-align: center;">
        @switch($order->paymenttype)
            @case('H')
                <strong>เงินสดต้นทาง</strong>
                @break

            @case('T')
                <strong>เงินโอนต้นทาง</strong>
                @break
            @case('E')
                <strong>เก็บเงินปลายทาง</strong>
                @break
            @case('F')
                <strong>วางบิลต้นทาง</strong>
                @break
            @case('L')
                <strong>วางบิลปลายทาง</strong>
                @break
        @endswitch
        </td>
        <td style="text-align: center;">
        @switch($order->trantype)
            @case(1)
                <strong>จัดส่ง</strong>
                @break

            @case(0)
                <strong>รับเอง</strong>
            @break
        @endswitch
        </td>
        <td  style="text-align: center;"><strong>
             @isset($order->waybill->car->car_regist)
                {{ $order->waybill->car->car_regist }}
            @endisset

        </strong></td>
    </tr>
</table>
<table style="width: 100%;padding:0px;margin-top:8px;">

    <tr>
        <td style="width: 50%;" >
        <p style="padding-left: 5px;">
           <strong>{{ $order->customer->name }}</strong>
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
            $order->customer->postal_code }}
        </p>
        </td>
        <td style="width: 50%;" >
            <p style="padding-left: 20px;">
            <strong>{{ $order->to_customer->name }}</strong>
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
            $order->to_customer->postal_code}}</p>
        </td>
    </tr>

    <tr>
        <td style="width: 50%;padding-top: 8px">
            <div style="padding-left: 30px ">{{ $order->customer->phoneno }}</div>
        </td>
        <td style="width: 50%;padding-top: 8px">
            <div style="padding-left: 50px ">{{ $order->to_customer->phoneno }}</div>
        </td>
    </tr>
</table>

<table  style="height: 96px;width: 100%;margin-top: 18px;">
        @foreach ($order->order_details as $item )
         <tr>
            <td  style="width: 5%;text-align: left">
                {{ $loop->iteration }}
            </td>
            <td style="width: 42%;">
                {{$item->product->name .''.$item->remark }}
            </td>
            <td style="width: 8%;text-align: center">
                {{number_format($item->amount,2)}}
            </td>
            <td style="width: 10%;text-align: center">
                {{$item->unit->name}}
            </td>
            <td style="width: 15%;text-align: center">
                {{number_format($item->price,2)}}
            </td>
            <td style="width: 20%;text-align: right">
                {{number_format($item->price*$item->amount,2)}}

            </td>
        </tr>
        @endforeach

</table>
<table  style="width: 100%;padding:0px;margin:0px;margin-top: 0px">
    <tr>
        <td style="width: 60%;">{{$order->remark}}</td>
        <td style="width: 20%">{{$order->order_details->sum('amount')}}</td>
        <td style="width: 20%;text-align: right"><strong>{{number_format($order->order_amount,2)}}</strong><br>
                (จำนวนเงิน)<br>
                {{$order->to_branch->name}}
        </td>
    </tr>
</table>
<table  style="width: 100%;padding:0px;margin:0px;margin-top: -8px">
    <tr>
        <td style="width: 20%;text-align: center;">{{$order->checker->name}}</td>
        <td style="width: 20%;text-align: center;">{{$order->user->name}}</td>
        <td style="width: 20%;text-align: center;">
            @isset($order->loader->name)
                {{ $order->loader->name }}
            @endisset
        </td>
        <td style="width: 40%;text-align: right;"><strong>T.{{$order->to_branch->phoneno}}</strong></td>
    </tr>
</table>


@endsection
