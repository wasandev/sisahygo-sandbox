@extends('layouts.doclandscapenojs')

@section('header')
    @include('partials.reportheader')

@endsection

@section('content')

<table style="width:100%;">

    <tr>
        <td style="width: 50%;text-align: left;border:0px">
            <strong>
            @if($branchdata <> null)
            สาขา {{ $branchdata->name }}
            @else
            ทุกสาขา
            @endif
            </strong>
        </td>
        <td style="width:50%;text-align: right;border:0px">
            <strong>
            ประจำปี: {{$year}}<br/>

            </strong>
        </td>
    </tr>

</table>
<br/>

<table style="width: 100%;" >
    <thead>
        <tr>
            <th style="width: 10%;">เดือน-ปี</th>
            @foreach ($order_date as $date_group => $date_groups)
                @foreach ( $date_groups->chunk(200) as $chunks)
                    @foreach ($chunks as $item)
                        <th style="text-align: center;">{{ $item->first()->name}}</th>
                    @endforeach
                @endforeach
            @endforeach
            <th style="text-align: right;">รวม</th>
        </tr>

    </thead>

    <tbody>
       @foreach ($order_date as $date_group => $date_groups)
            <tr style="font-weight: bold;background-color:#c0c0c0">

                <td  style="text-align: center">
                    <strong>{{ $date_group}}</strong>
                </td>
                @foreach ( $date_groups->chunk(200) as $chunks)
                    @foreach ($chunks as $item)

                        <td style="text-align: right">
                            {{ number_format($item->sum('order_amount'),2,'.',',') }}
                        </td>

                    @endforeach
                @endforeach
                <td style="text-align: right">
                    @php
                        $sumdate = 0 ;
                    @endphp

                    @foreach ($date_groups as $item)
                    @php
                        $sumdate = $sumdate + $item->sum('order_amount');
                    @endphp

                    @endforeach
                    <strong>
                    {{ number_format($sumdate,2,'.',',') }}
                    <strong>
                </td>
            </tr>

        @endforeach

            {{-- <tr style="font-weight: bold;background-color:#c0c0c0">
            <td colspan="3">
                    <strong>
                    รวมทั้งหมด
                    </strong>
            </td>

            <td style="text-align: right">
                {{ number_format($order->sum('order_amount'),2,'.',',') }}
            </td>





        </tr> --}}
    </tbody>

</table>



@endsection

