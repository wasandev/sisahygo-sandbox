<table style="width: 100%;">
    <tr>
        <td style="width: 100%;border:0px">
            <img src="{{ url('storage/'.$company->imagefile) }}" alt="{{$company->company_name}}" height="100">
        </td>
    </tr>
    <tr>

        <td style="width: 100%;border:0px">
            <strong>{{ $company->company_name }}</strong>
            สำนักงานใหญ่ เลขประตัวผู้เสียภาษีอากร {{ $company->taxid}}<br/>
            {{ $company->address .' '.$company->sub_district.' '.$company->district.' ' .$company->province.' '.$company->postal_code }}
            <br/>โทรศัพท์ {{$company->phoneno}} E-mail : {{$company->email}} Web Site: {{$company->weburl}}

        </td>
    </tr>



</table>
