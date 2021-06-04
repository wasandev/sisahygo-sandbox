<table style="width: 100%">
    <tr>
        <td style="width: 10%;">
            <img src="{{ url('storage/'.$company->logofile) }}" alt="{{$company->company_name}}" width="70" height="70">

        </td>


        <td style="width: 70%;padding-left:5px;">
            {{ $company->company_name }}
            {{ $company->address .' '.$company->sub_district.' '.$company->district.' ' .$company->province.' '.$company->postal_code }}
            <br/>สำนักงานใหญ่ Tax ID: {{ $company->taxid}}
            <br/>Tel: {{$company->phoneno}} อีเมล : {{$company->email}}


        </td>
         <td style="width: 20%;padding-right: 10px;text-align: right ">
            <h2>{{ $doc_title }}</h2>
         </td>
    </tr>



</table>
