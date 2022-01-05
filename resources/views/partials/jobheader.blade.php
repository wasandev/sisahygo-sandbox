<table style="margin-top: 50px;">

    <tr style="vertical-align: top;margin-top: 30px;">
        <td style="width: 20%">
            <img src="{{ url('storage/'.$company->logofile) }}" alt="{{$company->company_name}}" width="80" height="80">
        </td>
        <td>
            <strong>{{ $company->company_name }}</strong><br />
            สำนักงานใหญ่
            {{ $company->address .' '.$company->sub_district.' '.$company->district.' ' .$company->province.' '.$company->postal_code }}<br />
            โทรศัพท์ {{$company->phoneno}} อีเมล : {{$company->email}} เลขประตัวผู้เสียภาษีอากร {{ $company->taxid}}

        </td>
        <td style="vertical-align: top;text-align: center;font-size: 1.2em;">
            <p style="border: 1px solid black;border-radius: 5px;">
                <strong>ใบงานขนส่งเหมาคัน</strong>
                <br />FREIGHT BILL
            </p>


        </td>
    </tr>



</table>
