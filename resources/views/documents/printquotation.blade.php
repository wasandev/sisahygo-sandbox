@extends('layouts.forma4')

@section('header')
    @include('partials.docheader')

@endsection

@section('content')
<table style="width:100%;">
    <tr>
        <h1 style="text-align: center;font-size: 2.5em;">
            ใบเสนอราคา (Quotation)
        </h1>
    </tr>
</table>
<table style="width:100%">
    <tr>
        <td style="vertical-align: top;">
            ลูกค้า/Customer: {{ $quotation->customer->name }}<br />
            ที่อยู่/Address : {{ $quotation->customer->address.'
                     '.$quotation->customer->sub_district .'
                     '.$quotation->customer->district .' '.$quotation->customer->province .'
                    '.$quotation->customer->postal_code }}<br />
            โทร./Phone No.: {{ $quotation->customer->phoneno }} อีเมล์/Email: {{ $quotation->customer->email }}
        </td>
        <td style="vertical-align: top;" >
            เลขที่/Quotation No: {{ $quotation->quotation_no }}<br />
            วันที่/Date Of Issue :{{ date("d-m-Y", strtotime($quotation->quotation_date)) }}<br />



        </td>
    </tr>

</table>
<br />
<br />
<table style="width:100%;border: .5px solid black; border-collapse: collapse;">
    <thead>
        <tr style="vertical-align: middle;height: 50px;border: 1px solid black;text-align: center;">
            <th style="width: 5%;border: 1px solid black;">ลำดับ</th>
            <th style="width: 35%;border: 1px solid black;">ต้นทาง - ปลายทาง</th>
            <th style="width: 15%;border: 1px solid black;">ประเภทรถ</th>
            <th style="width: 15%;border: 1px solid black;">ลักษณะรถ</th>
            <th style="width: 10%;border: 1px solid black;">ค่าขนส่ง</th>
            <th style="width: 10%;border: 1px solid black;">จุดขึ้นลงสินค้า</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($quotation->charter_prices as $item )
         <tr style="vertical-align:top;">

            <td  style="text-align: center;border-top:0px;border-bottom: 0px;">
                {{ $loop->iteration }}
            </td>
            <td  style="text-align: center;border-top:0px;border-bottom: 0px;">
                {{ $item->charter_route->branch_area->district .'/'.$item->charter_route->branch_area->province .' - '.$item->charter_route->to_district .'/'.$item->charter_route->to_province   }}
            </td>
           <td  style="text-align: center;border-top:0px;border-bottom: 0px;">
                {{ $item->cartype->name }}
            </td>
            <td  style="text-align: center;border-top:0px;border-bottom: 0px;">
                {{ $item->carstyle->name }}
            </td>
            <td  style="text-align: center;border-top:0px;border-bottom: 0px;">
                {{ number_format($item->price,2) }}
            </td>
            <td  style="text-align: center;border-top:0px;border-bottom: 0px;">
                {{ $item->pickuppoint }}
            </td>

        </tr>
        @endforeach
        @if(count($quotation->charter_prices) < 15)
        @for ($i = 1; $i <= 10 - count($quotation->charter_prices); $i++)
            <tr style="vertical-align:top;height:1cm">
                <td  style="text-align: center;border-top:0px;border-bottom: 0px;">

                </td>
                <td  style="text-align: center;border-top:0px;border-bottom: 0px;">

                </td>
                <td  style="text-align: center;border-top:0px;border-bottom: 0px;">

                </td>
                <td  style="text-align: center;border-top:0px;border-bottom: 0px;">

                </td>
                <td  style="text-align: center;border-top:0px;border-bottom: 0px;">

                </td>
                <td  style="text-align: center;border-top:0px;border-bottom: 0px;">

                </td>
            </tr>

        @endfor
    @endif

    </tbody>
</table>
<br/><br/><br/>
@section('footer')
@include('partials.docfooter')
@endsection


@endsection
