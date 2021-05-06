<table>
    <tr style="width: 100%;">
        <td style="width: 100%">
            <img src="{{ url('storage/'.$company->imagefile) }}" alt="{{$company->company_name}}" height="80">
        </td>
    </tr>
    <tr>

        <td style="width: 100%;">
            <strong>{{ $company->company_name }}</strong>
            สำนักงานใหญ่ เลขประตัวผู้เสียภาษีอากร {{ $company->taxid}}
            {{ $company->address .' '.$company->sub_district.' '.$company->district.' ' .$company->province.' '.$company->postal_code }}
            <br/>โทรศัพท์ {{$company->phoneno}} อีเมล : {{$company->email}}



        </td>
    </tr>



</table>
