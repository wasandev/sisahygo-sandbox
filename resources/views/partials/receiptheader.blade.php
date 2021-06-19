<table style="width: 100%;">
    <tr>
        <td style="width: 10%;border:0px">
            <img src="{{ url('storage/'.$company->logofile) }}" alt="{{$company->company_name}}" width="70" height="70">

        </td>


        <td style="width: 90%;padding-left:5px;border:0px">
            {{ $company->company_name }}
            {{ $company->address .' '.$company->sub_district.' '.$company->district.' ' .$company->province.' '.$company->postal_code }}
            <br/>สำนักงานใหญ่ Tax ID: {{ $company->taxid}}
            <br/>Tel: {{$company->phoneno}} E-Mail : {{$company->email}} Web Site: {{$company->weburl}}


        </td>

    </tr>



</table>
