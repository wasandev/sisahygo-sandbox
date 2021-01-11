<table style="border-bottom: 1px solid black; padding-bottom: 10px">
    <tr>
        <td style="width: 15%">
        <img style ="padding: 10px;" src="{{ url('storage/'.$company->logofile) }}" alt="{{$company->company_name}}" width="80" height="80">
        </td>
        <td style="width: 85%">
            <strong>{{ $company->company_name }}</strong><br />
            สำนักงานใหญ่
            {{ $company->address .' '.$company->sub_district.' '.$company->district.' ' .$company->province.' '.$company->postal_code }}<br />
            โทรศัพท์ {{$company->phoneno}} อีเมล : {{$company->email}} เลขประตัวผู้เสียภาษีอากร {{ $company->taxid}}



        </td>
    </tr>



</table>
