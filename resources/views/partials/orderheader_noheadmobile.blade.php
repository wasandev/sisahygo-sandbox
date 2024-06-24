<table style="width: 90% ;magin-top: -30px">
    <tr>
        <td style="width: 10%;">
            {{-- <img src="{{ url('storage/'.$company->logofile) }}" alt="{{$company->company_name}}" width="70" height="70"> --}}

        </td>


        <td style="width: 70%;padding-left:5px;">
            {{-- {{ $company->company_name }}
            {{ $company->address .' '.$company->sub_district.' '.$company->district.' ' .$company->province.' '.$company->postal_code }}
            <br/>สำนักงานใหญ่ Tax ID: {{ $company->taxid}}
            <br/>Tel: {{$company->phoneno}} E-Mail : {{$company->email}} Web Site: {{$company->weburl}} --}}


        </td>
        <td style="width: 20%;margin-left: 10px ">
            <div class="visible-print text-center">
                {{-- {!! QrCode::size(70)->generate($order->tracking_no); !!} --}}
                @isset($order->id)
                    {{-- <img src="data:image/png;base64, {!! QrCode::size(70)->generate($order->tracking_no)) !!} "> --}}
                    {!! QrCode::size(70)->generate('https://app.sisahygo.online/order-tracking?tracking=' . $order->id) !!}
                @endisset

            </div>
        </td>
    </tr>



</table>
