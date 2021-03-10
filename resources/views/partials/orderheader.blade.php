<table style="width: 100%">
    <tr>
        <td style="width: 17%;">
            <img src="{{ url('storage/'.$company->logofile) }}" alt="{{$company->company_name}}" width="120" height="60">

        </td>


        <td style="width: 73%;padding-left:5px;font-size: .7em;">
            <strong>{{ $company->company_name }}</strong>
            {{ $company->address .' '.$company->sub_district.' '.$company->district.' ' .$company->province.' '.$company->postal_code }}
            <br/>สำนักงานใหญ่ Tax ID: {{ $company->taxid}}
            <br/>Tel: {{$company->phoneno}} อีเมล : {{$company->email}}


        </td>
         <td style="width: 10%;margin-left: 15px ">
            <div class="visible-print text-center">
                {{-- <img src="data:image/png;base64, {!! $qrcode !!}"> --}}
                {{-- {!! QrCode::size(60)->generate($order->tracking_no); !!} --}}
                <img src="data:image/png;base64, {{ base64_encode(QrCode::format('png')->size(80)->generate($order->tracking_no)) }} ">
            </div>
         </td>
    </tr>



</table>
